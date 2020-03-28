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
    $channel_info = $ipTV_db->get_row();
    $ipTV_db->close_mysql();
    $playlist = STREAMS_PATH . $stream_id . '_.m3u8';
    if (!ipTV_streaming::CheckPidChannelM3U8Exist($channel_info['pid'], $stream_id)) {
        if ($channel_info['on_demand'] == 1) {
            if (!ipTV_streaming::CheckPidExist($channel_info['monitor_pid'], $stream_id)) {
                ipTV_stream::startStream($stream_id);
            }
        } else {
            http_response_code(403);
            die;
        }
    }
    switch ($extension) {
        case 'm3u8':
            if (ipTV_streaming::IsValidStream($playlist, $channel_info['pid'])) {
                if (empty(ipTV_lib::$request['segment'])) {
                    if ($source = ipTV_streaming::GeneratePlayListWithAuthenticationAdmin($playlist, $password, $stream_id)) {
                        header('Content-Type: application/vnd.apple.mpegurl');
                        header('Content-Length: ' . strlen($source));
                        ob_end_flush();
                        echo $source;
                    }
                } else {
                    $segment = STREAMS_PATH . str_replace(array('\\', '/'), '', urldecode(ipTV_lib::$request['segment']));
                    if (file_exists($segment)) {
                        $size = filesize($segment);
                        header('Content-Length: ' . $size);
                        header('Content-Type: video/mp2t');
                        readfile($segment);
                    }
                }
            }
            break;
        default:
            header('Content-Type: video/mp2t');
            $segmentsOfPlaylist = ipTV_streaming::GetSegmentsOfPlaylist($playlist, ipTV_lib::$settings['client_prebuffer']);
            if (empty($segmentsOfPlaylist)) {
                if (!file_exists($playlist)) {
                    $current = -1;
                } else {
                    die;
                    //aec5a54201786b5bbe6573ae6b2aad85:
                    if (is_array($segmentsOfPlaylist)) {
                        foreach ($segmentsOfPlaylist as $segment) {
                            readfile(STREAMS_PATH . $segment);
                            A6ef4ccb381ec4a6b0c8c43e66d85825:
                        }
                        preg_match('/_(.*)\\./', array_pop($segmentsOfPlaylist), $pregmatches);
                        $current = $pregmatches[1];
                    } else {
                        $current = $segmentsOfPlaylist;
                    }
                    //goto f4dce90a7951c4692e46b0303393f7a4;
                }
                $fails = 0;
                $total_failed_tries = ipTV_lib::$SegmentsSettings['seg_time'] * 2;
                //d9f748ce9f53402f322a8d21a1736957:
                while (true) {
                    $segment_file = sprintf('%d_%d.ts', $stream_id, $current + 1);
                    $nextsegment_file = sprintf('%d_%d.ts', $stream_id, $current + 2);
                    $totalItems = 0;
                    E2548032ad734253ca2cc2ebbf6269b0:
                    while (!file_exists(STREAMS_PATH . $segment_file) && $totalItems <= $total_failed_tries * 10) {
                        usleep(100000);
                        ++$totalItems;
                    }
                    //eeb90bfe4c28dc9b04209fedc32ff5a3:
                    if (!file_exists(STREAMS_PATH . $segment_file)) {
                        die;
                    }
                    if (empty($channel_info['pid']) && file_exists(STREAMS_PATH . $stream_id . '_.pid')) {
                        $channel_info['pid'] = intval(file_get_contents(STREAMS_PATH . $stream_id . '_.pid'));
                    }
                    $fails = 0;
                    $fp = fopen(STREAMS_PATH . $segment_file, 'r');
                    //D2d24958a6f2888a694ed67016b06229:
                    while ($fails <= $total_failed_tries && !file_exists(STREAMS_PATH . $nextsegment_file)) {
                        $data = stream_get_line($fp, ipTV_lib::$settings['read_buffer_size']);
                        if (empty($data)) {
                            sleep(1);
                            if (!is_resource($fp) || !file_exists(STREAMS_PATH . $segment_file)) {
                                die;
                            }
                            ++$fails;
                            continue;
                        }
                        echo $data;
                        $fails = 0;
                    }
                    //ef89f77163837836531e009abed1ed0a:
                    if (ipTV_streaming::ps_running($channel_info['pid'], FFMPEG_PATH) && $fails <= $total_failed_tries && file_exists(STREAMS_PATH . $segment_file) && is_resource($fp)) {
                        $size = filesize(STREAMS_PATH . $segment_file);
                        $line = $size - ftell($fp);
                        if ($line > 0) {
                            echo stream_get_line($fp, $line);
                        }
                    } else {
                        die;
                    }
                    fclose($fp);
                    $fails = 0;
                    $current++;
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
