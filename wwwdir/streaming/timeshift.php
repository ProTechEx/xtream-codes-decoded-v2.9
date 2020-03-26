<?php
/*Rev:26.09.18r0*/

register_shutdown_function('shutdown');
set_time_limit(0);
require '../init.php';
header('Access-Control-Allow-Origin: *');
$f0ac6ad2b40669833242a10c23cad2e0 = true;
if (!isset(ipTV_lib::$request['start']) || !isset(ipTV_lib::$request['duration']) || !isset(ipTV_lib::$request['stream'])) {
    die('Missing parameters.');
}
if (ipTV_lib::$settings['use_buffer'] == 0) {
    header('X-Accel-Buffering: no');
}
$ded15b7e9c47ec5a3dea3c69332153c8 = new geoip(GEOIP2_FILENAME);
$activity_id = 0;
$connection_speed_file = null;
$E2e6656d8b1675f70c487f89e4f27a3b = null;
$username = empty(ipTV_lib::$request['username']) ? '' : ipTV_lib::$request['username'];
$password = empty(ipTV_lib::$request['password']) ? '' : ipTV_lib::$request['password'];
$stream_id = ipTV_lib::$request['stream'];
$F19b64ffad55876d290cb6f756a2dea5 = 0;
if (!is_numeric($stream_id) && stristr($stream_id, '_')) {
    list($stream_id, $Fe917966573bdf0b43ab9723bb50fc6b, $F19b64ffad55876d290cb6f756a2dea5) = explode('_', $stream_id);
    $stream_id = intval($stream_id);
    $F919000263e8ad8e2791f92d8919f629 = intval($Fe917966573bdf0b43ab9723bb50fc6b);
    $F19b64ffad55876d290cb6f756a2dea5 = intval($F19b64ffad55876d290cb6f756a2dea5);
    ipTV_lib::$request['extension'] = 'm3u8';
}
$user_ip = ipTV_streaming::getUserIP();
$user_agent = empty($_SERVER['HTTP_USER_AGENT']) ? '' : htmlentities(trim($_SERVER['HTTP_USER_AGENT']));
$A75f2436a5614184bfe3442ddd050ec5 = $ded15b7e9c47ec5a3dea3c69332153c8->c6a76952B4CEf18f3c98c0e6A9DD1274($user_ip)['registered_country']['iso_code'];
$ded15b7e9c47ec5a3dea3c69332153c8->close();
$play_token = empty(ipTV_lib::$request['play_token']) ? null : ipTV_lib::$request['play_token'];
if ($user_info = ipTV_streaming::GetUserInfo(null, $username, $password, true, false, true, array(), false, $user_ip, $user_agent, array(), $play_token, $stream_id)) {
    if (isset($user_info['mag_invalid_token'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'MAG_TOKEN_INVALID', $user_ip);
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
    if (!empty($user_info['allowed_ips']) && !in_array($user_ip, array_map('gethostbyname', $user_info['allowed_ips']))) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'IP_BAN', $user_ip);
        die;
    }
    if (!empty($A75f2436a5614184bfe3442ddd050ec5)) {
        $ab59908f6050f752836a953eb8bb8e52 = !empty($user_info['forced_country']) ? true : false;
        if ($ab59908f6050f752836a953eb8bb8e52 && $user_info['forced_country'] != 'ALL' && $A75f2436a5614184bfe3442ddd050ec5 != $user_info['forced_country']) {
            ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'COUNTRY_DISALLOW', $user_ip);
            die;
        }
        if (!$ab59908f6050f752836a953eb8bb8e52 && !in_array('ALL', ipTV_lib::$settings['allow_countries']) && !in_array($A75f2436a5614184bfe3442ddd050ec5, ipTV_lib::$settings['allow_countries'])) {
            ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'COUNTRY_DISALLOW', $user_ip);
            die;
        }
    }
    if (!empty($user_info['allowed_ua']) && !in_array($user_agent, $user_info['allowed_ua'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_AGENT_BAN', $user_ip);
        die;
    }
    if (ipTV_streaming::C57799E5196664CB99139813250673e2($user_ip)) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'CRACKED', $user_ip);
        die;
    }
    if (isset($user_info['ip_limit_reached'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_ALREADY_CONNECTED', $user_ip);
        die;
    }
    $f0ac6ad2b40669833242a10c23cad2e0 = false;
    if (!in_array($stream_id, $user_info['channel_ids'])) {
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
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'CON_SVP', $user_ip, json_encode(array('user_agent' => $user_agent, 'isp' => $user_info['con_isp_name'], 'type' => $user_info['con_isp_type'])), true);
        http_response_code(401);
        die;
    }
} else {
    die;
}
$ffb1e0970b62b01f46c2e57f2cded6c2 = ipTV_streaming::F3c105BCCEd491229D4Aed6937F96A8C($stream_id, 'ts', $user_info, $user_ip, $A75f2436a5614184bfe3442ddd050ec5, '', $user_info['con_isp_name'], 'archive');
if (empty($ffb1e0970b62b01f46c2e57f2cded6c2)) {
    http_response_code(403);
    die;
}
$Be553c1662ffa5054ccb6c5ce822974b = ipTV_lib::$request['start'];
$fd08711a26bab44719872c7fff1f2dfb = intval(ipTV_lib::$request['duration']);
if (!is_numeric($Be553c1662ffa5054ccb6c5ce822974b)) {
    if (substr_count($Be553c1662ffa5054ccb6c5ce822974b, '-') == 1) {
        list($e309bb80a71b96ca2c0ff856446be219, $Af218a53429705d6e319475a2185cd90) = explode('-', $Be553c1662ffa5054ccb6c5ce822974b);
        $Ee43d9ecc9cbf5787673058445cfac70 = substr($e309bb80a71b96ca2c0ff856446be219, 0, 4);
        $Dee598827978959770188b0749ebd8dd = substr($e309bb80a71b96ca2c0ff856446be219, 4, 2);
        $b8c55e6036c9c00eccabf835e272cdcb = substr($e309bb80a71b96ca2c0ff856446be219, 6, 2);
        $minutes = 0;
        $Ed62709841469f20fe0f7a17a4268692 = $Af218a53429705d6e319475a2185cd90;
    } else {
        list($e309bb80a71b96ca2c0ff856446be219, $Af218a53429705d6e319475a2185cd90) = explode(':', $Be553c1662ffa5054ccb6c5ce822974b);
        list($Ee43d9ecc9cbf5787673058445cfac70, $Dee598827978959770188b0749ebd8dd, $b8c55e6036c9c00eccabf835e272cdcb) = explode('-', $e309bb80a71b96ca2c0ff856446be219);
        list($Ed62709841469f20fe0f7a17a4268692, $minutes) = explode('-', $Af218a53429705d6e319475a2185cd90);
    }
    $c4eb261e96f50c6cac5c08c60d657d9c = mktime($Ed62709841469f20fe0f7a17a4268692, $minutes, 0, $Dee598827978959770188b0749ebd8dd, $b8c55e6036c9c00eccabf835e272cdcb, $Ee43d9ecc9cbf5787673058445cfac70);
} else {
    $fd08711a26bab44719872c7fff1f2dfb *= 24;
    $Ba78aa94423804e042a0eb27ba2e39a4 = array_values(array_filter(explode('
', shell_exec('ls -tr ' . TV_ARCHIVE . $stream_id . ' | sed -e \'s/\\s\\+/\\n/g\''))));
    $f9214002d8ab6575c8e959794518358d = $Be553c1662ffa5054ccb6c5ce822974b * 24;
    if (count($Ba78aa94423804e042a0eb27ba2e39a4) >= $f9214002d8ab6575c8e959794518358d) {
        $f9214002d8ab6575c8e959794518358d = $Ba78aa94423804e042a0eb27ba2e39a4[count($Ba78aa94423804e042a0eb27ba2e39a4) - $f9214002d8ab6575c8e959794518358d];
    } else {
        $f9214002d8ab6575c8e959794518358d = $Ba78aa94423804e042a0eb27ba2e39a4[0];
    }
    if (preg_match('/(.*)-(.*)-(.*):(.*)\\./', $f9214002d8ab6575c8e959794518358d, $ae37877cee3bc97c8cfa6ec5843993ed)) {
        $c4eb261e96f50c6cac5c08c60d657d9c = mktime($ae37877cee3bc97c8cfa6ec5843993ed[4], 0, 0, $ae37877cee3bc97c8cfa6ec5843993ed[2], $ae37877cee3bc97c8cfa6ec5843993ed[3], $ae37877cee3bc97c8cfa6ec5843993ed[1]);
    } else {
        die;
    }
}
$Df55b74833e9468cafb620fe446225a1 = array();
$Ca434bcc380e9dbd2a3a588f6c32d84f = TV_ARCHIVE . $stream_id . '/' . date('Y-m-d:H-i', $c4eb261e96f50c6cac5c08c60d657d9c) . '.ts';
if (empty($stream_id) || empty($c4eb261e96f50c6cac5c08c60d657d9c) || empty($fd08711a26bab44719872c7fff1f2dfb)) {
    header('HTTP/1.1 400 Bad Request');
    die;
}
if (!file_exists($Ca434bcc380e9dbd2a3a588f6c32d84f) || !is_readable($Ca434bcc380e9dbd2a3a588f6c32d84f)) {
    header('HTTP/1.1 404 Not Found');
    die;
}
$Df55b74833e9468cafb620fe446225a1 = array();
$index = 0;
//D5fcf2a72e99ec35092bea70c6000d54:
while ($index < $fd08711a26bab44719872c7fff1f2dfb) {
    $Ca434bcc380e9dbd2a3a588f6c32d84f = TV_ARCHIVE . $stream_id . '/' . date('Y-m-d:H-i', $c4eb261e96f50c6cac5c08c60d657d9c + $index * 60) . '.ts';
    if (file_exists($Ca434bcc380e9dbd2a3a588f6c32d84f)) {
        $Df55b74833e9468cafb620fe446225a1[] = array('filename' => $Ca434bcc380e9dbd2a3a588f6c32d84f, 'filesize' => filesize($Ca434bcc380e9dbd2a3a588f6c32d84f));
    }
    $index++;
}
//cd977541493ad22af6d956e1b361ac01:
if (!empty($Df55b74833e9468cafb620fe446225a1)) {
    $date = time();
    $E2e6656d8b1675f70c487f89e4f27a3b = 'TV Archive';
    switch (ipTV_lib::$request['extension']) {
        case 'm3u8':
            if (isset($F919000263e8ad8e2791f92d8919f629)) {
                if (!empty($Df55b74833e9468cafb620fe446225a1[$F919000263e8ad8e2791f92d8919f629]) && file_exists($Df55b74833e9468cafb620fe446225a1[$F919000263e8ad8e2791f92d8919f629]['filename']) && $Df55b74833e9468cafb620fe446225a1[$F919000263e8ad8e2791f92d8919f629]['filesize'] == $F19b64ffad55876d290cb6f756a2dea5) {
                    $B3acfaf2dca0db7e9507c5e640b4ba41 = 0;
                    $b362cb2e1492b66663cf3718328409ad = $Df55b74833e9468cafb620fe446225a1[$F919000263e8ad8e2791f92d8919f629]['filesize'];
                    if ($F919000263e8ad8e2791f92d8919f629 == 0) {
                        $B3acfaf2dca0db7e9507c5e640b4ba41 = $Df55b74833e9468cafb620fe446225a1[$F919000263e8ad8e2791f92d8919f629]['filesize'] * 0.3;
                        $b362cb2e1492b66663cf3718328409ad = $Df55b74833e9468cafb620fe446225a1[$F919000263e8ad8e2791f92d8919f629]['filesize'] - $B3acfaf2dca0db7e9507c5e640b4ba41;
                    }
                    header('Content-Type: video/mp2t');
                    header('Content-Length: ' . $b362cb2e1492b66663cf3718328409ad);
                    $fp = fopen($Df55b74833e9468cafb620fe446225a1[$F919000263e8ad8e2791f92d8919f629]['filename'], 'r');
                    fseek($fp, $B3acfaf2dca0db7e9507c5e640b4ba41);
                    B54b3db2c76fb47060bd112d83e284c6:
                    while (!feof($fp)) {
                        echo stream_get_line($fp, ipTV_lib::$settings['read_buffer_size']);
                    }
                    //af61afebb53878a18988940893cd0687:
                    fclose($fp);
                }
                die;
            }
            $ipTV_db->query('SELECT activity_id,hls_end FROM `user_activity_now` WHERE `user_id` = \'%d\' AND `server_id` = \'%d\' AND `container` = \'hls\' AND `user_ip` = \'%s\' AND `user_agent` = \'%s\' AND `stream_id` = \'%d\'', $user_info['id'], SERVER_ID, $user_ip, $user_agent, $stream_id);
            if ($ipTV_db->num_rows() == 0) {
                if ($user_info['max_connections'] != 0) {
                    $ipTV_db->query('UPDATE `user_activity_now` SET `hls_end` = 1 WHERE `user_id` = \'%d\' AND `container` = \'hls\'', $user_info['id']);
                }
                $ipTV_db->query('INSERT INTO `user_activity_now` (`user_id`,`stream_id`,`server_id`,`user_agent`,`user_ip`,`container`,`pid`,`date_start`,`geoip_country_code`,`isp`,`external_device`,`hls_last_read`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\')', $user_info['id'], $stream_id, SERVER_ID, $user_agent, $user_ip, $E2e6656d8b1675f70c487f89e4f27a3b . ' (HLS)', getmypid(), $date, $A75f2436a5614184bfe3442ddd050ec5, $user_info['con_isp_name'], $external_device, $date);
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
            $output = '#EXTM3U
';
            $output .= '#EXT-X-VERSION:3
';
            $output .= '#EXT-X-TARGETDURATION:60
';
            $output .= '#EXT-X-MEDIA-SEQUENCE:0
';
            $output .= '#EXT-X-PLAYLIST-TYPE:VOD
';
            foreach ($Df55b74833e9468cafb620fe446225a1 as $Baee0c34e5755f1cfaa4159ea7e8702e => $B5d14e09bc25553da9030273f23468aa) {
                $output .= '#EXTINF:60.0,
';
                $output .= "/timeshift/{$username}/{$password}/{$fd08711a26bab44719872c7fff1f2dfb}/{$Be553c1662ffa5054ccb6c5ce822974b}/{$stream_id}_{$Baee0c34e5755f1cfaa4159ea7e8702e}_" . $B5d14e09bc25553da9030273f23468aa['filesize'] . '.ts
';
            }
            $output .= '#EXT-X-ENDLIST';
            header('Content-Type: application/x-mpegurl');
            header('Content-Length: ' . strlen($output));
            echo $output;
            die;
            break;
        default:
            header('Content-Type: video/mp2t');
            if (!empty($user_info)) {
                $ipTV_db->query('INSERT INTO `user_activity_now` (`user_id`,`stream_id`,`server_id`,`user_agent`,`user_ip`,`container`,`pid`,`date_start`,`geoip_country_code`,`isp`,`external_device`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\')', $user_info['id'], $stream_id, SERVER_ID, $user_agent, $user_ip, $E2e6656d8b1675f70c487f89e4f27a3b, getmypid(), $date, $A75f2436a5614184bfe3442ddd050ec5, $user_info['con_isp_name'], $external_device);
                $activity_id = $ipTV_db->last_insert_id();
                $connection_speed_file = TMP_DIR . $activity_id . '.con';
                $ipTV_db->close_mysql();
            }
            $b362cb2e1492b66663cf3718328409ad = $Ff876e96994aa5b09ce92e771efe2038 = D86041F168a5452E8fDEACFbFd659E19($Df55b74833e9468cafb620fe446225a1);
            $bitrate = $Ff876e96994aa5b09ce92e771efe2038 * 0.008 / ($fd08711a26bab44719872c7fff1f2dfb * 60);
            header("Accept-Ranges: 0-{$b362cb2e1492b66663cf3718328409ad}");
            $start = 0;
            $ebe823668f9748302d3bd87782a71948 = $Ff876e96994aa5b09ce92e771efe2038 - 1;
            if (isset($_SERVER['HTTP_RANGE'])) {
                $dccf2f0f292208ba833261a4da87860d = $start;
                $A34771e85be87aded632236239e94d98 = $ebe823668f9748302d3bd87782a71948;
                list(, $cabafd9509f1a525c1d85143a5372ed8) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                if (strpos($cabafd9509f1a525c1d85143a5372ed8, ',') !== false) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes {$start}-{$ebe823668f9748302d3bd87782a71948}/{$Ff876e96994aa5b09ce92e771efe2038}");
                    die;
                }
                if ($cabafd9509f1a525c1d85143a5372ed8 == '-') {
                    $dccf2f0f292208ba833261a4da87860d = $Ff876e96994aa5b09ce92e771efe2038 - substr($cabafd9509f1a525c1d85143a5372ed8, 1);
                } else {
                    $cabafd9509f1a525c1d85143a5372ed8 = explode('-', $cabafd9509f1a525c1d85143a5372ed8);
                    $dccf2f0f292208ba833261a4da87860d = $cabafd9509f1a525c1d85143a5372ed8[0];
                    $A34771e85be87aded632236239e94d98 = isset($cabafd9509f1a525c1d85143a5372ed8[1]) && is_numeric($cabafd9509f1a525c1d85143a5372ed8[1]) ? $cabafd9509f1a525c1d85143a5372ed8[1] : $Ff876e96994aa5b09ce92e771efe2038;
                }
                $A34771e85be87aded632236239e94d98 = $A34771e85be87aded632236239e94d98 > $ebe823668f9748302d3bd87782a71948 ? $ebe823668f9748302d3bd87782a71948 : $A34771e85be87aded632236239e94d98;
                if ($dccf2f0f292208ba833261a4da87860d > $A34771e85be87aded632236239e94d98 || $dccf2f0f292208ba833261a4da87860d > $Ff876e96994aa5b09ce92e771efe2038 - 1 || $A34771e85be87aded632236239e94d98 >= $Ff876e96994aa5b09ce92e771efe2038) {
                    header('HTTP/1.1 416 Requested Range Not Satisfiable');
                    header("Content-Range: bytes {$start}-{$ebe823668f9748302d3bd87782a71948}/{$Ff876e96994aa5b09ce92e771efe2038}");
                    die;
                }
                $start = $dccf2f0f292208ba833261a4da87860d;
                $ebe823668f9748302d3bd87782a71948 = $A34771e85be87aded632236239e94d98;
                $b362cb2e1492b66663cf3718328409ad = $ebe823668f9748302d3bd87782a71948 - $start + 1;
                header('HTTP/1.1 206 Partial Content');
            }
            header("Content-Range: bytes {$start}-{$ebe823668f9748302d3bd87782a71948}/{$Ff876e96994aa5b09ce92e771efe2038}");
            header('Content-Length: ' . $b362cb2e1492b66663cf3718328409ad);
            $b3fcd87510baa9521882b459861dcb64 = 0;
            if ($start > 0) {
                $b3fcd87510baa9521882b459861dcb64 = floor($start / ($Ff876e96994aa5b09ce92e771efe2038 / count($Df55b74833e9468cafb620fe446225a1)));
            }
            $c77e7ff2c5d6b14d931b3344c54e0cc5 = false;
            $B3acfaf2dca0db7e9507c5e640b4ba41 = 0;
            $F1e7eccb846733c8f188bcdec720f3b7 = 0;
            $b2ecba26bb0e977abdb88e118b553d51 = $bitrate * 125;
            $b2ecba26bb0e977abdb88e118b553d51 += $b2ecba26bb0e977abdb88e118b553d51 * ipTV_lib::$settings['vod_bitrate_plus'] * 0.01;
            $c41986ad785eace90882e61c64cabb41 = time();
            $b1125d7ae8a179e8c8a4c80974755bd7 = 0;
            $C7558f823ac28009bfd4730a82f1f01b = ipTV_lib::$settings['read_buffer_size'];
            $index = 0;
            $b0cd8de619914d3df89e9fc24acad4e6 = 0;
            if (ipTV_lib::$settings['vod_limit_at'] > 0) {
                $F6295a8bab3aa6bb5b9c4a70c99ec761 = intval($Ff876e96994aa5b09ce92e771efe2038 * ipTV_lib::$settings['vod_limit_at'] / 100);
            } else {
                $F6295a8bab3aa6bb5b9c4a70c99ec761 = $Ff876e96994aa5b09ce92e771efe2038;
            }
            $A8e591a80910b24673b1a94b8219ab96 = false;
            foreach ($Df55b74833e9468cafb620fe446225a1 as $Baee0c34e5755f1cfaa4159ea7e8702e => $B5d14e09bc25553da9030273f23468aa) {
                $F1e7eccb846733c8f188bcdec720f3b7 += $B5d14e09bc25553da9030273f23468aa['filesize'];
                if (!$c77e7ff2c5d6b14d931b3344c54e0cc5 && $b3fcd87510baa9521882b459861dcb64 > 0) {
                    if ($b3fcd87510baa9521882b459861dcb64 > $Baee0c34e5755f1cfaa4159ea7e8702e) {
                        //goto bf705a2f20da4ec4abb5062ccbc64ff2;
                    } else {
                        $c77e7ff2c5d6b14d931b3344c54e0cc5 = true;
                        $B3acfaf2dca0db7e9507c5e640b4ba41 = $start - $F1e7eccb846733c8f188bcdec720f3b7;
                    }
                }
                $fp = fopen($B5d14e09bc25553da9030273f23468aa['filename'], 'rb');
                fseek($fp, $B3acfaf2dca0db7e9507c5e640b4ba41);
                dafe2b9523cba3c52c343947c994cdff:
                while (!feof($fp)) {
                    $Fe917966573bdf0b43ab9723bb50fc6b = ftell($fp);
                    $response = stream_get_line($fp, $C7558f823ac28009bfd4730a82f1f01b);
                    echo $response;
                    $b1125d7ae8a179e8c8a4c80974755bd7 += strlen($response);
                    ++$index;
                    if (!$A8e591a80910b24673b1a94b8219ab96 && $b0cd8de619914d3df89e9fc24acad4e6 * $C7558f823ac28009bfd4730a82f1f01b >= $F6295a8bab3aa6bb5b9c4a70c99ec761) {
                        $A8e591a80910b24673b1a94b8219ab96 = true;
                    } else {
                        ++$b0cd8de619914d3df89e9fc24acad4e6;
                    }
                    if ($b2ecba26bb0e977abdb88e118b553d51 > 0 && $A8e591a80910b24673b1a94b8219ab96 && $index >= ceil($b2ecba26bb0e977abdb88e118b553d51 / $C7558f823ac28009bfd4730a82f1f01b)) {
                        sleep(1);
                        $index = 0;
                    }
                    if (time() - $c41986ad785eace90882e61c64cabb41 >= 30) {
                        file_put_contents($connection_speed_file, intval($b1125d7ae8a179e8c8a4c80974755bd7 / 1024 / 30));
                        $c41986ad785eace90882e61c64cabb41 = time();
                        $b1125d7ae8a179e8c8a4c80974755bd7 = 0;
                    }
                }
                //d01dd0fdc665b83fc4d231b1aff5f5dd:
                if (is_resource($fp)) {
                    fclose($fp);
                }
                $B3acfaf2dca0db7e9507c5e640b4ba41 = 0;
                //bf705a2f20da4ec4abb5062ccbc64ff2:
            }
    }
}
function d86041f168a5452e8FDEacfbfD659E19($Df55b74833e9468cafb620fe446225a1)
{
    $b362cb2e1492b66663cf3718328409ad = 0;
    foreach ($Df55b74833e9468cafb620fe446225a1 as $B5d14e09bc25553da9030273f23468aa) {
        $b362cb2e1492b66663cf3718328409ad += $B5d14e09bc25553da9030273f23468aa['filesize'];
        Ca7d41373e0e61846f92a6a9d1e5cf3c:
    }
    return $b362cb2e1492b66663cf3718328409ad;
}
function shutdown()
{
    global $ipTV_db, $f0ac6ad2b40669833242a10c23cad2e0, $activity_id, $connection_speed_file, $user_info, $E2e6656d8b1675f70c487f89e4f27a3b, $stream_id, $user_agent, $user_ip, $A75f2436a5614184bfe3442ddd050ec5, $external_device, $date;
    if ($f0ac6ad2b40669833242a10c23cad2e0) {
        CheckFlood();
        http_response_code(401);
    }
    $ipTV_db->close_mysql();
    if ($activity_id !== false) {
        ipTV_streaming::e990445B40642E0efD070E994375f6AF($activity_id);
        ipTV_streaming::A49c2Fb1EBa096c52a352a85C8d09d8D(SERVER_ID, $user_info['id'], $stream_id, $date, $user_agent, $user_ip, $E2e6656d8b1675f70c487f89e4f27a3b, $A75f2436a5614184bfe3442ddd050ec5, $user_info['con_isp_name'], $external_device);
        if (file_exists($connection_speed_file)) {
            unlink($connection_speed_file);
        }
    }
    fastcgi_finish_request();
    posix_kill(getmypid(), 9);
}
?>
