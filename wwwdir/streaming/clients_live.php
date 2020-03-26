<?php
/*Rev:26.09.18r0*/

register_shutdown_function('shutdown');
set_time_limit(0);
require '../init.php';
$f0ac6ad2b40669833242a10c23cad2e0 = true;
if (isset(ipTV_lib::$request['qs'])) {
    if (stristr(ipTV_lib::$request['qs'], ':p=')) {
        $Af236a5462da6c610990628f594f801e = explode(':p=', ipTV_lib::$request['qs']);
        ipTV_lib::$request['password'] = $Af236a5462da6c610990628f594f801e[1];
        ipTV_lib::$request['username'] = substr($Af236a5462da6c610990628f594f801e[0], 2);
    }
}
if (!isset(ipTV_lib::$request['extension']) || !isset(ipTV_lib::$request['username']) || !isset(ipTV_lib::$request['password']) || !isset(ipTV_lib::$request['stream'])) {
    die;
}
$ded15b7e9c47ec5a3dea3c69332153c8 = new geoip(GEOIP2_FILENAME);
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
    $A75f2436a5614184bfe3442ddd050ec5 = $ded15b7e9c47ec5a3dea3c69332153c8->C6a76952B4CeF18F3C98c0e6a9Dd1274($user_ip)['registered_country']['iso_code'];
    $ded15b7e9c47ec5a3dea3c69332153c8->close();
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
    if (ipTV_streaming::C57799e5196664cB99139813250673E2($user_ip)) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'CRACKED', $user_ip);
        die;
    }
    if (isset($user_info['ip_limit_reached'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_ALREADY_CONNECTED', $user_ip);
        die;
    }
    $f0ac6ad2b40669833242a10c23cad2e0 = false;
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
    if ($ffb1e0970b62b01f46c2e57f2cded6c2 = ipTV_streaming::f3c105BcceD491229d4aEd6937f96a8c($stream_id, $extension, $user_info, $user_ip, $A75f2436a5614184bfe3442ddd050ec5, $external_device, $user_info['con_isp_name'], 'live')) {
        $playlist = STREAMS_PATH . $stream_id . '_.m3u8';
        if (!ipTV_streaming::ps_running($ffb1e0970b62b01f46c2e57f2cded6c2['pid'], FFMPEG_PATH)) {
            if ($ffb1e0970b62b01f46c2e57f2cded6c2['on_demand'] == 1) {
                if (!ipTV_streaming::CDA72bC41975C364BC559dB25648A5b2($ffb1e0970b62b01f46c2e57f2cded6c2['monitor_pid'], $stream_id)) {
                    ipTV_stream::E79092731573697c16A932c339D0a101($stream_id);
                }
            } else {
                ipTV_streaming::ShowVideo($user_info['is_restreamer'], 'show_not_on_air_video', 'not_on_air_video_path', $extension);
            }
        }
        switch ($extension) {
            case 'm3u8':
                $close_connection = false;
                $B1772eb944c03052cd5d180cdee51b89 = 0;
                a5783fd272d37bf2cf23d06cadf2c0b5:
                while (!file_exists($playlist) && $B1772eb944c03052cd5d180cdee51b89 <= 20) {
                    usleep(500000);
                    ++$B1772eb944c03052cd5d180cdee51b89;
                }
                db0a7a079002a891925f78b87d872c81:
                if ($B1772eb944c03052cd5d180cdee51b89 == 20) {
                    die;
                }
                if (empty(ipTV_lib::$request['segment'])) {
                    $ipTV_db->query('SELECT activity_id,hls_end FROM `user_activity_now` WHERE `user_id` = \'%d\' AND `server_id` = \'%d\' AND `container` = \'hls\' AND `user_ip` = \'%s\' AND `user_agent` = \'%s\' AND `stream_id` = \'%d\'', $user_info['id'], SERVER_ID, $user_ip, $user_agent, $stream_id);
                    if ($ipTV_db->num_rows() == 0) {
                        if ($user_info['max_connections'] != 0) {
                            $ipTV_db->query('UPDATE `user_activity_now` SET `hls_end` = 1 WHERE `user_id` = \'%d\' AND `container` = \'hls\'', $user_info['id']);
                        }
                        $ipTV_db->query('INSERT INTO `user_activity_now` (`user_id`,`stream_id`,`server_id`,`user_agent`,`user_ip`,`container`,`pid`,`date_start`,`geoip_country_code`,`isp`,`external_device`,`hls_last_read`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\')', $user_info['id'], $stream_id, SERVER_ID, $user_agent, $user_ip, 'hls', getmypid(), $date, $A75f2436a5614184bfe3442ddd050ec5, $user_info['con_isp_name'], $external_device, time());
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
                    if ($F3803fa85b38b65447e6d438f8e9176a = ipTV_streaming::E7917F7F55606c448105A9a4016538b9($playlist, $username, $password, $stream_id)) {
                        header('Content-Type: application/x-mpegurl');
                        header('Content-Length: ' . strlen($F3803fa85b38b65447e6d438f8e9176a));
                        header('CACHE-Control: no-store, no-CACHE, must-revalidate');
                        echo $F3803fa85b38b65447e6d438f8e9176a;
                    }
                    die;
                } else {
                    $ipTV_db->close_mysql();
                    $fe9d0d199fc51f64065055d8bcade279 = STREAMS_PATH . str_replace(array('\\', '/'), '', urldecode(ipTV_lib::$request['segment']));
                    $ff808659f878dbd58bfa6fabe039f10c = explode('_', basename($fe9d0d199fc51f64065055d8bcade279));
                    if (!file_exists($fe9d0d199fc51f64065055d8bcade279) || $ff808659f878dbd58bfa6fabe039f10c[0] != $stream_id || empty(ipTV_lib::$request['token'])) {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
                        die;
                    }
                    $Aacb752351b5de80f12830c2026b757e = ipTV_lib::$request['token'];
                    $A0450eaeae72ee603999aa268ea82b0c = md5(urldecode(ipTV_lib::$request['segment']) . $user_info['username'] . ipTV_lib::$settings['crypt_load_balancing'] . filesize($fe9d0d199fc51f64065055d8bcade279));
                    if ($A0450eaeae72ee603999aa268ea82b0c != $Aacb752351b5de80f12830c2026b757e) {
                        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);
                        die;
                    }
                    $e13ac89e162bcc9913e553b949f755b6 = filesize($fe9d0d199fc51f64065055d8bcade279);
                    header('Content-Length: ' . $e13ac89e162bcc9913e553b949f755b6);
                    header('Content-Type: video/mp2t');
                    readfile($fe9d0d199fc51f64065055d8bcade279);
                }
                break;
            default:
                $ipTV_db->query('INSERT INTO `user_activity_now` (`user_id`,`stream_id`,`server_id`,`user_agent`,`user_ip`,`container`,`pid`,`date_start`,`geoip_country_code`,`isp`,`external_device`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\')', $user_info['id'], $stream_id, SERVER_ID, $user_agent, $user_ip, $extension, getmypid(), $date, $A75f2436a5614184bfe3442ddd050ec5, $user_info['con_isp_name'], $external_device);
                $activity_id = $ipTV_db->last_insert_id();
                $connection_speed_file = TMP_DIR . $activity_id . '.con';
                $ipTV_db->close_mysql();
                header('Content-Type: video/mp2t');
                $C325d28e238c3a646bd7b095aa1ffa85 = ipTV_streaming::B8430212Cc8301200a4976571DbA202C($playlist, ipTV_lib::$settings['client_prebuffer']);
                if (empty($C325d28e238c3a646bd7b095aa1ffa85)) {
                    if (!file_exists($playlist)) {
                        $E76c20c612d64210f5bcc0611992d2f7 = -1;
                    } else {
                        die;   
                    }
                    //C03b2f940f424083fdade1b2c96365d4:
                    if (is_array($C325d28e238c3a646bd7b095aa1ffa85)) {
                        if (ipTV_lib::$settings['restreamer_prebuffer'] == 1 && $user_info['is_restreamer'] == 1 || $user_info['is_restreamer'] == 0) {
                            $e13ac89e162bcc9913e553b949f755b6 = 0;
                            $A73d5129dfb465fd94f3e09e9b179de0 = time();
                            foreach ($C325d28e238c3a646bd7b095aa1ffa85 as $fe9d0d199fc51f64065055d8bcade279) {
                                if (file_exists(STREAMS_PATH . $fe9d0d199fc51f64065055d8bcade279)) {
                                    $e13ac89e162bcc9913e553b949f755b6 += readfile(STREAMS_PATH . $fe9d0d199fc51f64065055d8bcade279);
                                } else {
                                    die;
                                }
                            }
                            $D6db7e73f7da5e54d965f7ef1c369bd6 = time() - $A73d5129dfb465fd94f3e09e9b179de0;
                            if ($D6db7e73f7da5e54d965f7ef1c369bd6 == 0) {
                                $D6db7e73f7da5e54d965f7ef1c369bd6 = 0.1;
                            }
                            file_put_contents($connection_speed_file, intval($e13ac89e162bcc9913e553b949f755b6 / $D6db7e73f7da5e54d965f7ef1c369bd6 / 1024));
                        }
                        preg_match('/_(.*)\\./', array_pop($C325d28e238c3a646bd7b095aa1ffa85), $adb24597b0e7956b0f3baad7c260916d);
                        $E76c20c612d64210f5bcc0611992d2f7 = $adb24597b0e7956b0f3baad7c260916d[1];
                    } else {
                        $E76c20c612d64210f5bcc0611992d2f7 = $C325d28e238c3a646bd7b095aa1ffa85;
                    }
                    goto f4a60f5a64a086fc0304bf38dd04c18d;
                
                    $c45cc215a073632a9e20d474ea91f7e3 = 0;
                    $f065eccc0636f7fd92043c7118f7409b = ipTV_lib::$SegmentsSettings['seg_time'] * 2;
                    ec83cd6ff50c6b79e6b8cffbb78eecbf:
                    while (true) {
                        $segment_file = sprintf('%d_%d.ts', $ffb1e0970b62b01f46c2e57f2cded6c2['stream_id'], $E76c20c612d64210f5bcc0611992d2f7 + 1);
                        $Bf3da9b14ae368d39b642b3f83d656fc = sprintf('%d_%d.ts', $ffb1e0970b62b01f46c2e57f2cded6c2['stream_id'], $E76c20c612d64210f5bcc0611992d2f7 + 2);
                        $a88c8d86d7956601164a5f156d5df985 = 0;
                        Cf93be3ee45266203c1bef9fbf92206a:
                        while (!file_exists(STREAMS_PATH . $segment_file) && $a88c8d86d7956601164a5f156d5df985 <= $f065eccc0636f7fd92043c7118f7409b * 10) {
                            usleep(100000);
                            ++$a88c8d86d7956601164a5f156d5df985;
                        }
                        ca8d94736b3ae6c33685c0351c234242:
                        if (!file_exists(STREAMS_PATH . $segment_file)) {
                            die;
                        }
                        if (empty($ffb1e0970b62b01f46c2e57f2cded6c2['pid']) && file_exists(STREAMS_PATH . $stream_id . '_.pid')) {
                            $ffb1e0970b62b01f46c2e57f2cded6c2['pid'] = intval(file_get_contents(STREAMS_PATH . $stream_id . '_.pid'));
                        }
                        if (file_exists(SIGNALS_PATH . $activity_id)) {
                            $d38a1c3d822bdbbd61f649f33212ebde = json_decode(file_get_contents(SIGNALS_PATH . $activity_id), true);
                            switch ($d38a1c3d822bdbbd61f649f33212ebde['type']) {
                                case 'signal':
                                    $a88c8d86d7956601164a5f156d5df985 = 0;
                                    bebebcdc24b95d7496a99323abc492f0:
                                    while (!file_exists(STREAMS_PATH . $Bf3da9b14ae368d39b642b3f83d656fc) && $a88c8d86d7956601164a5f156d5df985 <= $f065eccc0636f7fd92043c7118f7409b) {
                                        sleep(1);
                                        ++$a88c8d86d7956601164a5f156d5df985;
                                    }
                                    Ee8cbf74db1494aaab7b6c23ad1834af:
                                    ipTV_streaming::e8E54De10433eB446982a4af8aDeA379($d38a1c3d822bdbbd61f649f33212ebde, $segment_file);
                                    ++$E76c20c612d64210f5bcc0611992d2f7;
                                    break;
                                case 'redirect':
                                    $stream_id = $ffb1e0970b62b01f46c2e57f2cded6c2['stream_id'] = $d38a1c3d822bdbbd61f649f33212ebde['stream_id'];
                                    $playlist = STREAMS_PATH . $stream_id . '_.m3u8';
                                    $ffb1e0970b62b01f46c2e57f2cded6c2['pid'] = null;
                                    $C325d28e238c3a646bd7b095aa1ffa85 = ipTV_streaming::B8430212cC8301200A4976571Dba202C($playlist, ipTV_lib::$settings['client_prebuffer']);
                                    preg_match('/_(.*)\\./', array_pop($C325d28e238c3a646bd7b095aa1ffa85), $adb24597b0e7956b0f3baad7c260916d);
                                    $E76c20c612d64210f5bcc0611992d2f7 = $adb24597b0e7956b0f3baad7c260916d[1];
                                    break;
                            }
                            $d38a1c3d822bdbbd61f649f33212ebde = null;
                            unlink(SIGNALS_PATH . $activity_id);
                            continue;
                        }
                        $c45cc215a073632a9e20d474ea91f7e3 = 0;
                        $c41986ad785eace90882e61c64cabb41 = time();
                        $fp = fopen(STREAMS_PATH . $segment_file, 'r');
                        Cec1b4b5d1ec19950895bdff075c35b9:
                        while ($c45cc215a073632a9e20d474ea91f7e3 <= $f065eccc0636f7fd92043c7118f7409b && !file_exists(STREAMS_PATH . $Bf3da9b14ae368d39b642b3f83d656fc)) {
                            $d76067cf9572f7a6691c85c12faf2a29 = stream_get_line($fp, ipTV_lib::$settings['read_buffer_size']);
                            if (empty($d76067cf9572f7a6691c85c12faf2a29)) {
                                if (!ipTV_streaming::ps_running($ffb1e0970b62b01f46c2e57f2cded6c2['pid'], FFMPEG_PATH)) {
                                    break;
                                }
                                sleep(1);
                                ++$c45cc215a073632a9e20d474ea91f7e3;
                                continue;
                            }
                            echo $d76067cf9572f7a6691c85c12faf2a29;
                            $c45cc215a073632a9e20d474ea91f7e3 = 0;
                        }
                        ef0705fe07490d2e2ab41bcda87af246:
                        if (ipTV_streaming::ps_running($ffb1e0970b62b01f46c2e57f2cded6c2['pid'], FFMPEG_PATH) && $c45cc215a073632a9e20d474ea91f7e3 <= $f065eccc0636f7fd92043c7118f7409b && file_exists(STREAMS_PATH . $segment_file) && is_resource($fp)) {
                            $F19b64ffad55876d290cb6f756a2dea5 = filesize(STREAMS_PATH . $segment_file);
                            $C73fe796a6baad7ca2e4251886562ef0 = $F19b64ffad55876d290cb6f756a2dea5 - ftell($fp);
                            if ($C73fe796a6baad7ca2e4251886562ef0 > 0) {
                                echo stream_get_line($fp, $C73fe796a6baad7ca2e4251886562ef0);
                            }
                            $D6db7e73f7da5e54d965f7ef1c369bd6 = time() - $c41986ad785eace90882e61c64cabb41;
                            if ($D6db7e73f7da5e54d965f7ef1c369bd6 <= 0) {
                                $D6db7e73f7da5e54d965f7ef1c369bd6 = 0.1;
                            }
                            file_put_contents($connection_speed_file, intval($F19b64ffad55876d290cb6f756a2dea5 / 1024 / $D6db7e73f7da5e54d965f7ef1c369bd6));
                        } else {
                            if ($user_info['is_restreamer'] == 1 || $c45cc215a073632a9e20d474ea91f7e3 > $f065eccc0636f7fd92043c7118f7409b) {
                                die;
                            }
                            $a88c8d86d7956601164a5f156d5df985 = 0;
                            F71d17aeef5dd4b69cc7d2e4bdabbeba:
                            while ($a88c8d86d7956601164a5f156d5df985 <= ipTV_lib::$SegmentsSettings['seg_time'] && !ipTV_streaming::BcAa9B8a7b46Eb36cD507A218fA64474($ffb1e0970b62b01f46c2e57f2cded6c2['pid'], $stream_id)) {
                                sleep(1);
                                if (file_exists(STREAMS_PATH . $stream_id . '_.pid')) {
                                    $ffb1e0970b62b01f46c2e57f2cded6c2['pid'] = intval(file_get_contents(STREAMS_PATH . $stream_id . '_.pid'));
                                }
                                ++$a88c8d86d7956601164a5f156d5df985;
                            }
                            Ce092c28451a42c8af012826f808a346:
                            if ($a88c8d86d7956601164a5f156d5df985 > ipTV_lib::$SegmentsSettings['seg_time'] || !ipTV_streaming::BCaa9B8A7B46Eb36CD507A218Fa64474($ffb1e0970b62b01f46c2e57f2cded6c2['pid'], $stream_id)) {
                                die;
                            }
                            $E76c20c612d64210f5bcc0611992d2f7 = -2;
                        }
                        fclose($fp);
                        $c45cc215a073632a9e20d474ea91f7e3 = 0;
                        $E76c20c612d64210f5bcc0611992d2f7++;
                    }
                    effe248a4af2e290cb140d4ae83e3334:
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
    global $ipTV_db, $activity_id, $close_connection, $connection_speed_file, $user_info, $extension, $f0ac6ad2b40669833242a10c23cad2e0, $stream_id, $user_agent, $user_ip, $A75f2436a5614184bfe3442ddd050ec5, $external_device, $date;
    if ($f0ac6ad2b40669833242a10c23cad2e0) {
        CheckFlood();
        http_response_code(401);
    }
    $ipTV_db->close_mysql();
    if ($activity_id != 0 && $close_connection) {
        ipTV_streaming::E990445B40642e0EfD070E994375f6af($activity_id);
        ipTV_streaming::A49C2fb1EbA096c52a352A85C8d09d8D(SERVER_ID, $user_info['id'], $stream_id, $date, $user_agent, $user_ip, $extension, $A75f2436a5614184bfe3442ddd050ec5, $user_info['con_isp_name'], $external_device);
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
