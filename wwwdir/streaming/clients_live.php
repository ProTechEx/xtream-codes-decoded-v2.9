<?php
/*Rev:26.09.18r0*/

register_shutdown_function('shutdown');
set_time_limit(0);
require '../init.php';
$streaming_block = true;
if (isset(ipTV_lib::$request['qs'])) {
    if (stristr(ipTV_lib::$request['qs'], ':p=')) {
        $qs = explode(':p=', ipTV_lib::$request['qs']);
        ipTV_lib::$request['password'] = $qs[1];
        ipTV_lib::$request['username'] = substr($qs[0], 2);
    }
}
if (!isset(ipTV_lib::$request['extension']) || !isset(ipTV_lib::$request['username']) || !isset(ipTV_lib::$request['password']) || !isset(ipTV_lib::$request['stream'])) {
    die;
}
$geoip = new Reader(GEOIP2_FILENAME);
$activity_id = 0;
$close_connection = true;
$connection_speed_file = null;
$user_ip = ipTV_streaming::getUserIP();
$user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : htmlentities(trim($_SERVER['HTTP_USER_AGENT']));
$external_device = null;
$username = ipTV_lib::$request['username'];
$password = ipTV_lib::$request['password'];
$stream_id = intval(ipTV_lib::$request['stream']);
$extension = preg_replace('/[^A-Za-z0-9 ]/', '', trim(ipTV_lib::$request['extension']));
$date = time();
if (ipTV_lib::$settings['use_buffer'] == 0) {
    header('X-Accel-Buffering: no');
}
header('Access-Control-Allow-Origin: *');
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
        if (empty(ipTV_lib::$request['stalker_key']) || $extension != 'ts') {
            die;
        }
        $stalker_key = base64_decode(urldecode(ipTV_lib::$request['stalker_key']));
        if ($decrypt_key = ipTV_lib::mc_decrypt($stalker_key, md5(ipTV_lib::$settings['live_streaming_pass']))) {
            $stalker_data = explode('=', $decrypt_key);
            if ($stalker_data[2] != $stream_id) {
                ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'STALKER_chANNEL_MISMATch', $user_ip);
                die;
            }
            if ($stalker_data[1] != $user_ip) {
                ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'STALKER_IP_MISMATch', $user_ip);
                die;
            }
            if (time() > $stalker_data[3]) {
                ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'STALKER_KEY_EXPIRED', $user_ip);
                die;
            }
            $external_device = $stalker_data[0];
        } else {
            ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'STALKER_DECRYPT_FAILED', $user_ip);
            die;
        }
    }
    if (!is_null($user_info['exp_date']) && time() >= $user_info['exp_date']) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_EXPIRED', $user_ip);
        ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_expired_video', 'expired_video_path', $extension);
        die;
    }
    if ($user_info['admin_enabled'] == 0) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_BAN', $user_ip);
        ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_banned_video', 'banned_video_path', $extension);
        die;
    }
    if ($user_info['enabled'] == 0) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_DISABLED', $user_ip);
        ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_banned_video', 'banned_video_path', $extension);
        die;
    }
    if (empty($user_agent) && ipTV_lib::$settings['disallow_empty_user_agents'] == 1) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'EMPTY_UA', $user_ip);
        die;
    }
    $geoip_country_code = $geoip->getWithPrefixLen($user_ip)['registered_country']['iso_code'];
    $geoip->close();
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
    if (ipTV_streaming::checkIsCracked($user_ip)) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'CRACKED', $user_ip);
        die;
    }
    if (isset($user_info['ip_limit_reached'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_ALREADY_CONNECTED', $user_ip);
        die;
    }
    $streaming_block = false;
    if (!array_key_exists($extension, $user_info['output_formats'])) {
        http_response_code(405);
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_DISALLOW_EXT', $user_ip);
        die;
    }
    if (!in_array($stream_id, $user_info['channel_ids'])) {
        http_response_code(406);
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'NOT_IN_BOUQUET', $user_ip);
        die;
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
    if ($user_info['max_connections'] != 0) {
        if (!empty($user_info['pair_line_info'])) {
            if ($user_info['pair_line_info']['max_connections'] != 0) {
                if ($user_info['pair_line_info']['active_cons'] >= $user_info['pair_line_info']['max_connections']) {
                    ipTV_streaming::CloseLastCon($user_info['pair_id'], $user_info['pair_line_info']['max_connections']);
                }
            }
        }
        if ($user_info['active_cons'] >= $user_info['max_connections'] && $extension != 'm3u8') {
            ipTV_streaming::CloseLastCon($user_info['id'], $user_info['max_connections']);
        }
    }
    if ($channel_info = ipTV_streaming::ChannelInfo($stream_id, $extension, $user_info, $user_ip, $geoip_country_code, $external_device, $user_info['con_isp_name'], 'live')) {
        $playlist = STREAMS_PATH . $stream_id . '_.m3u8';
        if (!ipTV_streaming::ps_running($channel_info['pid'], FFMPEG_PATH)) {
            if ($channel_info['on_demand'] == 1) {
                if (!ipTV_streaming::CheckPidExist($channel_info['monitor_pid'], $stream_id)) {
                    ipTV_stream::startStream($stream_id);
                }
            } else {
                ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_not_on_air_video', 'not_on_air_video_path', $extension);
            }
        }
        switch ($extension) {
            case 'm3u8':
                $close_connection = false;
                $items = 0;
                while (!file_exists($playlist) && $items <= 20) {
                    usleep(500000);
                    ++$items;
                }
                if ($items == 20) {
                    die;
                }
                if (empty(ipTV_lib::$request['segment'])) {
                    $ipTV_db->query('SELECT activity_id,hls_end FROM `user_activity_now` WHERE `user_id` = \'%d\' AND `server_id` = \'%d\' AND `container` = \'hls\' AND `user_ip` = \'%s\' AND `user_agent` = \'%s\' AND `stream_id` = \'%d\'', $user_info['id'], SERVER_ID, $user_ip, $user_agent, $stream_id);
                    if ($ipTV_db->num_rows() == 0) {
                        if ($user_info['max_connections'] != 0) {
                            $ipTV_db->query('UPDATE `user_activity_now` SET `hls_end` = 1 WHERE `user_id` = \'%d\' AND `container` = \'hls\'', $user_info['id']);
                        }
                        $ipTV_db->query('INSERT INTO `user_activity_now` (`user_id`,`stream_id`,`server_id`,`user_agent`,`user_ip`,`container`,`pid`,`date_start`,`geoip_country_code`,`isp`,`external_device`,`hls_last_read`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\')', $user_info['id'], $stream_id, SERVER_ID, $user_agent, $user_ip, 'hls', getmypid(), $date, $geoip_country_code, $user_info['con_isp_name'], $external_device, time());
                        $activity_id = $ipTV_db->last_insert_id();
                    } else {
                        $row = $ipTV_db->get_row();
                        if ($row['hls_end'] == 1) {
                            header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
                            die;
                        }
                        $activity_id = $row['activity_id'];
                        $ipTV_db->query('UPDATE `user_activity_now` SET `hls_last_read` = \'%d\' WHERE `activity_id` = \'%d\'', time(), $row['activity_id']);
                    }
                    $ipTV_db->close_mysql();
                    if ($source = ipTV_streaming::GeneratePlayListWithAuthentication($playlist, $username, $password, $stream_id)) {
                        header('Content-Type: application/x-mpegurl');
                        header('Content-Length: ' . strlen($source));
                        header('cache-Control: no-store, no-cache, must-revalidate');
                        echo $source;
                    }
                    die;
                } else {
                    $ipTV_db->close_mysql();
                    $segment = STREAMS_PATH . str_replace(array('\\', '/'), '', urldecode(ipTV_lib::$request['segment']));
                    $current_ts = explode('_', basename($segment));
                    if (!file_exists($segment) || $current_ts[0] != $stream_id || empty(ipTV_lib::$request['token'])) {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
                        die;
                    }
                    $token = ipTV_lib::$request['token'];
                    $token_segment = md5(urldecode(ipTV_lib::$request['segment']) . $user_info['username'] . ipTV_lib::$settings['crypt_load_balancing'] . filesize($segment));
                    if ($token_segment != $token) {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
                        die;
                    }
                    $size = filesize($segment);
                    header('Content-Length: ' . $size);
                    header('Content-Type: video/mp2t');
                    readfile($segment);
                }
                break;
            default:
                $ipTV_db->query('INSERT INTO `user_activity_now` (`user_id`,`stream_id`,`server_id`,`user_agent`,`user_ip`,`container`,`pid`,`date_start`,`geoip_country_code`,`isp`,`external_device`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\')', $user_info['id'], $stream_id, SERVER_ID, $user_agent, $user_ip, $extension, getmypid(), $date, $geoip_country_code, $user_info['con_isp_name'], $external_device);
                $activity_id = $ipTV_db->last_insert_id();
                $connection_speed_file = TMP_DIR . $activity_id . '.con';
                $ipTV_db->close_mysql();
                header('Content-Type: video/mp2t');
                $segmentsOfPlaylist = ipTV_streaming::GetSegmentsOfPlaylist($playlist, ipTV_lib::$settings['client_prebuffer']);
                if (empty($segmentsOfPlaylist)) {
                    if (!file_exists($playlist)) {
                        $current = -1;
                    } else {
                        die;
                    }
                    if (is_array($segmentsOfPlaylist)) {
                        if (ipTV_lib::$settings['restreamer_prebuffer'] == 1 && $user_info['is_restreamer'] == 1 || $user_info['is_restreamer'] == 0) {
                            $size = 0;
                            $epgStart = time();
                            foreach ($segmentsOfPlaylist as $segment) {
                                if (file_exists(STREAMS_PATH . $segment)) {
                                    $size += readfile(STREAMS_PATH . $segment);
                                } else {
                                    die;
                                }
                            }
                            $final_time = time() - $epgStart;
                            if ($final_time == 0) {
                                $final_time = 0.1;
                            }
                            file_put_contents($connection_speed_file, intval($size / $final_time / 1024));
                        }
                        preg_match('/_(.*)\\./', array_pop($segmentsOfPlaylist), $pregmatches);
                        $current = $pregmatches[1];
                    } else {
                        $current = $segmentsOfPlaylist;
                    }
                    $fails = 0;
                    $total_failed_tries = ipTV_lib::$SegmentsSettings['seg_time'] * 2;
                    while (true) {
                        $segment_file = sprintf('%d_%d.ts', $channel_info['stream_id'], $current + 1);
                        $nextsegment_file = sprintf('%d_%d.ts', $channel_info['stream_id'], $current + 2);
                        $totalItems = 0;
                        while (!file_exists(STREAMS_PATH . $segment_file) && $totalItems <= $total_failed_tries * 10) {
                            usleep(100000);
                            ++$totalItems;
                        }
                        if (!file_exists(STREAMS_PATH . $segment_file)) {
                            die;
                        }
                        if (empty($channel_info['pid']) && file_exists(STREAMS_PATH . $stream_id . '_.pid')) {
                            $channel_info['pid'] = intval(file_get_contents(STREAMS_PATH . $stream_id . '_.pid'));
                        }
                        if (file_exists(SIGNALS_PATH . $activity_id)) {
                            $data = json_decode(file_get_contents(SIGNALS_PATH . $activity_id), true);
                            switch ($data['type']) {
                                case 'signal':
                                    $totalItems = 0;
                                    while (!file_exists(STREAMS_PATH . $nextsegment_file) && $totalItems <= $total_failed_tries) {
                                        sleep(1);
                                        ++$totalItems;
                                    }
                                    ipTV_streaming::startFFMPEGSegment($data, $segment_file);
                                    ++$current;
                                    break;
                                case 'redirect':
                                    $stream_id = $channel_info['stream_id'] = $data['stream_id'];
                                    $playlist = STREAMS_PATH . $stream_id . '_.m3u8';
                                    $channel_info['pid'] = null;
                                    $segmentsOfPlaylist = ipTV_streaming::GetSegmentsOfPlaylist($playlist, ipTV_lib::$settings['client_prebuffer']);
                                    preg_match('/_(.*)\\./', array_pop($segmentsOfPlaylist), $pregmatches);
                                    $current = $pregmatches[1];
                                    break;
                            }
                            $data = null;
                            unlink(SIGNALS_PATH . $activity_id);
                            continue;
                        }
                        $fails = 0;
                        $time_start = time();
                        $fp = fopen(STREAMS_PATH . $segment_file, 'r');
                        while ($fails <= $total_failed_tries && !file_exists(STREAMS_PATH . $nextsegment_file)) {
                            $data = stream_get_line($fp, ipTV_lib::$settings['read_buffer_size']);
                            if (empty($data)) {
                                if (!ipTV_streaming::ps_running($channel_info['pid'], FFMPEG_PATH)) {
                                    break;
                                }
                                sleep(1);
                                ++$fails;
                                continue;
                            }
                            echo $data;
                            $fails = 0;
                        }
                        if (ipTV_streaming::ps_running($channel_info['pid'], FFMPEG_PATH) && $fails <= $total_failed_tries && file_exists(STREAMS_PATH . $segment_file) && is_resource($fp)) {
                            $size = filesize(STREAMS_PATH . $segment_file);
                            $line = $size - ftell($fp);
                            if ($line > 0) {
                                echo stream_get_line($fp, $line);
                            }
                            $final_time = time() - $time_start;
                            if ($final_time <= 0) {
                                $final_time = 0.1;
                            }
                            file_put_contents($connection_speed_file, intval($size / 1024 / $final_time));
                        } else {
                            if ($user_info['is_restreamer'] == 1 || $fails > $total_failed_tries) {
                                die;
                            }
                            $totalItems = 0;
                            while ($totalItems <= ipTV_lib::$SegmentsSettings['seg_time'] && !ipTV_streaming::CheckPidChannelM3U8Exist($channel_info['pid'], $stream_id)) {
                                sleep(1);
                                if (file_exists(STREAMS_PATH . $stream_id . '_.pid')) {
                                    $channel_info['pid'] = intval(file_get_contents(STREAMS_PATH . $stream_id . '_.pid'));
                                }
                                ++$totalItems;
                            }
                            if ($totalItems > ipTV_lib::$SegmentsSettings['seg_time'] || !ipTV_streaming::CheckPidChannelM3U8Exist($channel_info['pid'], $stream_id)) {
                                die;
                            }
                            $current = -2;
                        }
                        fclose($fp);
                        $fails = 0;
                        $current++;
                    }
                }
        }
    } else {
        ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_not_on_air_video', 'not_on_air_video_path', $extension);
    }
} else {
    ipTV_streaming::ClientLog($stream_id, 0, 'AUTH_FAILED', $user_ip);
}
function shutdown()
{
    global $ipTV_db, $activity_id, $close_connection, $connection_speed_file, $user_info, $extension, $streaming_block, $stream_id, $user_agent, $user_ip, $geoip_country_code, $external_device, $date;
    if ($streaming_block) {
        CheckFlood();
        http_response_code(401);
    }
    $ipTV_db->close_mysql();
    if ($activity_id != 0 && $close_connection) {
        ipTV_streaming::CloseAndTransfer($activity_id);
        ipTV_streaming::SaveClosedConnection(SERVER_ID, $user_info['id'], $stream_id, $date, $user_agent, $user_ip, $extension, $geoip_country_code, $user_info['con_isp_name'], $external_device);
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
