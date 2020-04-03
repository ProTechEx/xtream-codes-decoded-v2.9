<?php
/*Rev:26.09.18r0*/

register_shutdown_function('shutdown');
set_time_limit(0);
require '../init.php';
$streaming_block = true;
if (!isset(ipTV_lib::$request['username']) || !isset(ipTV_lib::$request['password']) || !isset(ipTV_lib::$request['stream'])) {
    die('Missing parameters.');
}
$geoip = new Reader(GEOIP2_FILENAME);
$activity_id = 0;
$container_priority = null;
$connection_speed_file = null;
$user_ip = ipTV_streaming::getUserIP();
$user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : htmlentities(trim($_SERVER['HTTP_USER_AGENT']));
$username = ipTV_lib::$request['username'];
$password = ipTV_lib::$request['password'];
$stream = pathinfo(ipTV_lib::$request['stream']);
$stream_id = intval($stream['filename']);
$extension = preg_replace('/[^A-Za-z0-9 ]/', '', trim($stream['extension']));
$type = empty(ipTV_lib::$request['type']) ? null : ipTV_lib::$request['type'];
if (ipTV_lib::$settings['use_buffer'] == 0) {
    header('X-Accel-Buffering: no');
}
header('Access-Control-Allow-Origin: *');
$e8d12aa38d4899d2d4d12fbd8d047fb0 = '';
$play_token = empty(ipTV_lib::$request['play_token']) ? null : ipTV_lib::$request['play_token'];
if ($user_info = ipTV_streaming::GetUserInfo(null, $username, $password, true, false, true, array(), false, $user_ip, $user_agent, array(), $play_token, $stream_id)) {
    if (isset($user_info['mag_invalid_token'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'MAG_TOKEN_INVALID', $user_ip);
        die;
    }
    if ($user_info['bypass_ua'] == 0) {
        ipTV_streaming::checkGlobalBlockUA($user_agent);
    }
    if ($user_info['is_stalker'] == 1) {
        die;
    }
    if (empty($user_agent) && ipTV_lib::$settings['disallow_empty_user_agents'] == 1) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'EMPTY_UA', $user_ip);
        die;
    }
    if (!is_null($user_info['exp_date']) && time() >= $user_info['exp_date']) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_EXPIRED', $user_ip);
        ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_expired_video', 'expired_video_path');
        die;
    }
    if ($user_info['admin_enabled'] == 0) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_BAN', $user_ip);
        ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_banned_video', 'banned_video_path');
        die;
    }
    if ($user_info['enabled'] == 0) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_DISABLED', $user_ip);
        ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_banned_video', 'banned_video_path');
        die;
    }
    if (empty($user_agent) && ipTV_lib::$settings['disallow_empty_user_agents'] == 1) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'EMPTY_UA', $user_ip);
        die;
    }
    $geoip_country_code = $geoip->getWithPrefixLen($user_ip)['registered_country']['iso_code'];
    if (!empty($user_info['allowed_ips']) && !in_array($user_ip, array_map('gethostbyname', $user_info['allowed_ips']))) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'IP_BAN', $user_ip);
        die;
    }
    if (!empty($geoip_country_code)) {
        $forced_country = !empty($user_info['forced_country']) ? true : false;
        if ($forced_country && $user_info['forced_country'] != 'ALL' && $geoip_country_code != $user_info['forced_country']) {
            ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'COUNTRY_DISALLOW', $user_ip);
            die;
        }
        if (!$forced_country && !in_array('ALL', ipTV_lib::$settings['allow_countries']) && !in_array($geoip_country_code, ipTV_lib::$settings['allow_countries'])) {
            ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'COUNTRY_DISALLOW', $user_ip);
            die;
        }
    }
    if (!empty($user_info['allowed_ua']) && !in_array($user_agent, $user_info['allowed_ua'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_AGENT_BAN', $user_ip);
        die;
    }
    if (isset($user_info['ip_limit_reached'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_ALREADY_CONNECTED', $user_ip);
        die;
    }
    if (ipTV_streaming::checkIsCracked($user_ip)) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'CRACKED', $user_ip);
        die;
    }
    $streaming_block = false;
    if (!ipTV_streaming::checkStreamExistInBouquet($stream_id, $type == 'movie' ? $user_info['channel_ids'] : $user_info['series_ids'], $type)) {
        http_response_code(406);
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'NOT_IN_BOUQUET', $user_ip);
        die;
    }
    if ($user_info['max_connections'] != 0) {
        if (!empty($user_info['pair_line_info'])) {
            if ($user_info['pair_line_info']['max_connections'] != 0) {
                if ($user_info['pair_line_info']['active_cons'] >= $user_info['pair_line_info']['max_connections']) {
                    ipTV_streaming::CloseLastCon($user_info['pair_id'], $user_info['pair_line_info']['max_connections']);
                }
            }
        }
        if ($user_info['active_cons'] >= $user_info['max_connections']) {
            ipTV_streaming::CloseLastCon($user_info['id'], $user_info['max_connections']);
        }
    }
    if ($user_info['isp_violate'] == 1) {
        http_response_code(401);
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'ISP_LOCK_FAILED', $user_ip, json_encode(array('old' => $user_info['isp_desc'], 'new' => $user_info['con_isp_name'])));
        die;
    }
    if ($user_info['isp_is_server'] == 1) {
        http_response_code(401);
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'CON_SVP', $user_ip, json_encode(array('user_agent' => $user_agent, 'isp' => $user_info['con_isp_name'], 'type' => $user_info['con_isp_type'])), true);
        die;
    }
    if ($channel_info = ipTV_streaming::ChannelInfo($stream_id, $extension, $user_info, $user_ip, $geoip_country_code, '', $user_info['con_isp_name'], 'movie')) {
        $date = time();
        $container_priority = 'VOD';
        $ipTV_db->query('INSERT INTO `user_activity_now` (`user_id`,`stream_id`,`server_id`,`user_agent`,`user_ip`,`container`,`pid`,`date_start`,`geoip_country_code`,`isp`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\',\'%d\',\'%s\',\'%s\')', $user_info['id'], $stream_id, SERVER_ID, $user_agent, $user_ip, $container_priority, getmypid(), $date, $geoip_country_code, $user_info['con_isp_name']);
        $activity_id = $ipTV_db->last_insert_id();
        $connection_speed_file = TMP_DIR . $activity_id . '.con';
        $ipTV_db->close_mysql();
        switch ($extension) {
            case 'mp4':
                header('Content-type: video/mp4');
                break;
            case 'mkv':
                header('Content-type: video/x-matroska');
                break;
            case 'avi':
                header('Content-type: video/x-msvideo');
                break;
            case '3gp':
                header('Content-type: video/3gpp');
                break;
            case 'flv':
                header('Content-type: video/x-flv');
                break;
            case 'wmv':
                header('Content-type: video/x-ms-wmv');
                break;
            case 'mov':
                header('Content-type: video/quicktime');
                break;
            case 'ts':
                header('Content-type: video/mp2t');
                break;
            default:
                header('Content-Type: application/octet-stream');
        }
        $b2ecba26bb0e977abdb88e118b553d51 = !empty($channel_info['bitrate']) ? $channel_info['bitrate'] * 125 : 0;
        $b2ecba26bb0e977abdb88e118b553d51 += $b2ecba26bb0e977abdb88e118b553d51 * ipTV_lib::$settings['vod_bitrate_plus'] * 0.01;
        $E6dd23f358d554b9a74e3ae676bc8c9b = MOVIES_PATH . $stream_id . '.' . $extension;
        if (file_exists($E6dd23f358d554b9a74e3ae676bc8c9b)) {
            $fp = @fopen($E6dd23f358d554b9a74e3ae676bc8c9b, 'rb');
            $size = filesize($E6dd23f358d554b9a74e3ae676bc8c9b);
            $length = $size;
            $start = 0;
            $end = $size - 1;
            header("Accept-Ranges: 0-{$length}");
            if (isset($_SERVER['HTTP_RANGE'])) {
                $c_start = $start;
                $c_end = $end;
                list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                if (strpos($range, ',') !== false) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes {$start}-{$end}/{$size}");
                    die;
                }
                if ($range == '-') {
                    $c_start = $size - substr($range, 1);
                } else {
                    $range = explode('-', $range);
                    $c_start = $range[0];
                    $c_end = isset($range[1]) && is_numeric($range[1]) ? $range[1] : $size;
                }
                $c_end = $c_end > $end ? $end : $c_end;
                if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes {$start}-{$end}/{$size}");
                    die;
                }
                $start = $c_start;
                $end = $c_end;
                $length = $end - $start + 1;
                fseek($fp, $start);
                header('HTTP/1.1 206 Partial Content');
            }
            header("Content-Range: bytes {$start}-{$end}/{$size}");
            header('Content-Length: ' . $length);
            $time_start = time();
            $b1125d7ae8a179e8c8a4c80974755bd7 = 0;
            $buffer = ipTV_lib::$settings['read_buffer_size'];
            $index = 0;
            $b0cd8de619914d3df89e9fc24acad4e6 = 0;
            if (ipTV_lib::$settings['vod_limit_at'] > 0) {
                $F6295a8bab3aa6bb5b9c4a70c99ec761 = intval($length * ipTV_lib::$settings['vod_limit_at'] / 100);
            } else {
                $F6295a8bab3aa6bb5b9c4a70c99ec761 = $length;
            }
            $A8e591a80910b24673b1a94b8219ab96 = false;
            //D7b3f9d60519ce61242d1941a0c77b14:
            while (!feof($fp) && ($p = ftell($fp)) <= $end) {
                $response = stream_get_line($fp, $buffer);
                ++$index;
                if (!$A8e591a80910b24673b1a94b8219ab96 && $b0cd8de619914d3df89e9fc24acad4e6 * $buffer >= $F6295a8bab3aa6bb5b9c4a70c99ec761) {
                    $A8e591a80910b24673b1a94b8219ab96 = true;
                } else {
                    ++$b0cd8de619914d3df89e9fc24acad4e6;
                }
                echo $response;
                $b1125d7ae8a179e8c8a4c80974755bd7 += strlen($response);
                if (time() - $time_start >= 30) {
                    file_put_contents($connection_speed_file, intval($b1125d7ae8a179e8c8a4c80974755bd7 / 1024 / 30));
                    $time_start = time();
                    $b1125d7ae8a179e8c8a4c80974755bd7 = 0;
                }
                if ($b2ecba26bb0e977abdb88e118b553d51 > 0 && $A8e591a80910b24673b1a94b8219ab96 && $index >= ceil($b2ecba26bb0e977abdb88e118b553d51 / $buffer)) {
                    sleep(1);
                    $index = 0;
                }
            }
            //f51f3eda71805424934a7449ccdb08d8:
            fclose($fp);
            die;
        }
    } else {
        ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_not_on_air_video', 'not_on_air_video_path');
    }
} else {
    ipTV_streaming::ClientLog($stream_id, 0, 'AUTH_FAILED', $user_ip);
}
function shutdown()
{
    global $ipTV_db, $activity_id, $connection_speed_file, $user_info, $container_priority, $streaming_block, $stream_id, $user_agent, $user_ip, $geoip_country_code, $external_device, $date;
    if ($streaming_block) {
        CheckFlood();
        http_response_code(401);
    }
    $ipTV_db->close_mysql();
    if ($activity_id != 0) {
        ipTV_streaming::CloseAndTransfer($activity_id);
        ipTV_streaming::SaveClosedConnection(SERVER_ID, $user_info['id'], $stream_id, $date, $user_agent, $user_ip, $container_priority, $geoip_country_code, $user_info['con_isp_name'], $external_device);
        if (file_exists($connection_speed_file)) {
            unlink($connection_speed_file);
        }
    }
    fastcgi_finish_request();
    if ($activity_id != 0 || !file_exists(IPTV_PANEL_DIR . 'kill_pids')) {
        posix_kill(getmypid(), 9);
    }
}
?>
