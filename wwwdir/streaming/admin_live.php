<?php
/*Rev:26.09.18r0*/

header('Access-Control-Allow-Origin: *');
register_shutdown_function('shutdown');
set_time_limit(0);
require '../init.php';
$user_ip = $_SERVER['REMOTE_ADDR'];
if (!in_array($user_ip, ipTV_streaming::getAllowedIPsAdmin(true))) {
    http_response_code(401);
    die;
}
if (empty(ipTV_lib::$request['stream']) || empty(ipTV_lib::$request['extension']) || empty(ipTV_lib::$request['password']) || ipTV_lib::$settings['live_streaming_pass'] != ipTV_lib::$request['password']) {
    http_response_code(401);
    die;
}
$password = ipTV_lib::$settings['live_streaming_pass'];
$stream_id = intval(ipTV_lib::$request['stream']);
$extension = ipTV_lib::$request['extension'];
$ipTV_db->query('SELECT * 
                    FROM `streams` t1
                    INNER JOIN `streams_sys` t2 ON t2.stream_id = t1.id AND t2.server_id = \'%d\'
                    WHERE t1.`id` = \'%d\'', SERVER_ID, $stream_id);
if (ipTV_lib::$settings['use_buffer'] == 0) {
    header('X-Accel-Buffering: no');
}
if ($ipTV_db->num_rows() > 0) {
    $ffb1e0970b62b01f46c2e57f2cded6c2 = $ipTV_db->get_row();
    $ipTV_db->close_mysql();
    $playlist = STREAMS_PATH . $stream_id . '_.m3u8';
    if (!ipTV_streaming::bCaa9B8A7b46eb36Cd507A218fa64474($ffb1e0970b62b01f46c2e57f2cded6c2['pid'], $stream_id)) {
        if ($ffb1e0970b62b01f46c2e57f2cded6c2['on_demand'] == 1) {
            if (!ipTV_streaming::CDa72Bc41975C364BC559dB25648a5B2($ffb1e0970b62b01f46c2e57f2cded6c2['monitor_pid'], $stream_id)) {
                ipTV_stream::e79092731573697C16a932C339d0a101($stream_id);
            }
        } else {
            http_response_code(403);
            die;
        }
    }
    switch ($extension) {
        case 'm3u8':
            if (ipTV_streaming::IsValidStream($playlist, $ffb1e0970b62b01f46c2e57f2cded6c2['pid'])) {
                if (empty(ipTV_lib::$request['segment'])) {
                    if ($F3803fa85b38b65447e6d438f8e9176a = ipTV_streaming::B18C6Bf534aE0B9b94354Db508d52a48($playlist, $password, $stream_id)) {
                        header('Content-Type: application/vnd.apple.mpegurl');
                        header('Content-Length: ' . strlen($F3803fa85b38b65447e6d438f8e9176a));
                        ob_end_flush();
                        echo $F3803fa85b38b65447e6d438f8e9176a;
                    }
                } else {
                    $fe9d0d199fc51f64065055d8bcade279 = STREAMS_PATH . str_replace(array('\\', '/'), '', urldecode(ipTV_lib::$request['segment']));
                    if (file_exists($fe9d0d199fc51f64065055d8bcade279)) {
                        $e13ac89e162bcc9913e553b949f755b6 = filesize($fe9d0d199fc51f64065055d8bcade279);
                        header('Content-Length: ' . $e13ac89e162bcc9913e553b949f755b6);
                        header('Content-Type: video/mp2t');
                        readfile($fe9d0d199fc51f64065055d8bcade279);
                    }
                }
            }
            break;
        default:
            header('Content-Type: video/mp2t');
            $C325d28e238c3a646bd7b095aa1ffa85 = ipTV_streaming::b8430212cC8301200A4976571dbA202c($playlist, ipTV_lib::$settings['client_prebuffer']);
            if (empty($C325d28e238c3a646bd7b095aa1ffa85)) {
                if (!file_exists($playlist)) {
                    $E76c20c612d64210f5bcc0611992d2f7 = -1;
                } else {
                    die;
                    //aec5a54201786b5bbe6573ae6b2aad85:
                    if (is_array($C325d28e238c3a646bd7b095aa1ffa85)) {
                        foreach ($C325d28e238c3a646bd7b095aa1ffa85 as $fe9d0d199fc51f64065055d8bcade279) {
                            readfile(STREAMS_PATH . $fe9d0d199fc51f64065055d8bcade279);
                            A6ef4ccb381ec4a6b0c8c43e66d85825:
                        }
                        preg_match('/_(.*)\\./', array_pop($C325d28e238c3a646bd7b095aa1ffa85), $adb24597b0e7956b0f3baad7c260916d);
                        $E76c20c612d64210f5bcc0611992d2f7 = $adb24597b0e7956b0f3baad7c260916d[1];
                    } else {
                        $E76c20c612d64210f5bcc0611992d2f7 = $C325d28e238c3a646bd7b095aa1ffa85;
                    }
                    //goto f4dce90a7951c4692e46b0303393f7a4;
                }
                $c45cc215a073632a9e20d474ea91f7e3 = 0;
                $f065eccc0636f7fd92043c7118f7409b = ipTV_lib::$SegmentsSettings['seg_time'] * 2;
                //d9f748ce9f53402f322a8d21a1736957:
                while (true) {
                    $segment_file = sprintf('%d_%d.ts', $stream_id, $E76c20c612d64210f5bcc0611992d2f7 + 1);
                    $Bf3da9b14ae368d39b642b3f83d656fc = sprintf('%d_%d.ts', $stream_id, $E76c20c612d64210f5bcc0611992d2f7 + 2);
                    $a88c8d86d7956601164a5f156d5df985 = 0;
                    E2548032ad734253ca2cc2ebbf6269b0:
                    while (!file_exists(STREAMS_PATH . $segment_file) && $a88c8d86d7956601164a5f156d5df985 <= $f065eccc0636f7fd92043c7118f7409b * 10) {
                        usleep(100000);
                        ++$a88c8d86d7956601164a5f156d5df985;
                    }
                    //eeb90bfe4c28dc9b04209fedc32ff5a3:
                    if (!file_exists(STREAMS_PATH . $segment_file)) {
                        die;
                    }
                    if (empty($ffb1e0970b62b01f46c2e57f2cded6c2['pid']) && file_exists(STREAMS_PATH . $stream_id . '_.pid')) {
                        $ffb1e0970b62b01f46c2e57f2cded6c2['pid'] = intval(file_get_contents(STREAMS_PATH . $stream_id . '_.pid'));
                    }
                    $c45cc215a073632a9e20d474ea91f7e3 = 0;
                    $fp = fopen(STREAMS_PATH . $segment_file, 'r');
                    //D2d24958a6f2888a694ed67016b06229:
                    while ($c45cc215a073632a9e20d474ea91f7e3 <= $f065eccc0636f7fd92043c7118f7409b && !file_exists(STREAMS_PATH . $Bf3da9b14ae368d39b642b3f83d656fc)) {
                        $data = stream_get_line($fp, ipTV_lib::$settings['read_buffer_size']);
                        if (empty($data)) {
                            sleep(1);
                            if (!is_resource($fp) || !file_exists(STREAMS_PATH . $segment_file)) {
                                die;
                            }
                            ++$c45cc215a073632a9e20d474ea91f7e3;
                            continue;
                        }
                        echo $data;
                        $c45cc215a073632a9e20d474ea91f7e3 = 0;
                    }
                    //ef89f77163837836531e009abed1ed0a:
                    if (ipTV_streaming::ps_running($ffb1e0970b62b01f46c2e57f2cded6c2['pid'], FFMPEG_PATH) && $c45cc215a073632a9e20d474ea91f7e3 <= $f065eccc0636f7fd92043c7118f7409b && file_exists(STREAMS_PATH . $segment_file) && is_resource($fp)) {
                        $F19b64ffad55876d290cb6f756a2dea5 = filesize(STREAMS_PATH . $segment_file);
                        $C73fe796a6baad7ca2e4251886562ef0 = $F19b64ffad55876d290cb6f756a2dea5 - ftell($fp);
                        if ($C73fe796a6baad7ca2e4251886562ef0 > 0) {
                            echo stream_get_line($fp, $C73fe796a6baad7ca2e4251886562ef0);
                        }
                    } else {
                        die;
                    }
                    fclose($fp);
                    $c45cc215a073632a9e20d474ea91f7e3 = 0;
                    $E76c20c612d64210f5bcc0611992d2f7++;
                }
                //a10c19f796b5df61809eb2fe8ea2a035:
            }
    }
} else {
    http_response_code(403);
}
function shutdown()
{
    fastcgi_finish_request();
}
?>
