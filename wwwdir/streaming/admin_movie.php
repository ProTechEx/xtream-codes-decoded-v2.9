<?php
/*Rev:26.09.18r0*/

register_shutdown_function('shutdown');
set_time_limit(0);
require '../init.php';
$streaming_block = true;
$E824497a502b6e5dd609c0acb45697c7 = false;
$size = 0;
$activity_id = 0;
$user_ip = $_SERVER['REMOTE_ADDR'];
if (!in_array($user_ip, ipTV_streaming::getAllowedIPsAdmin(true))) {
    http_response_code(401);
    die;
}
if (empty(ipTV_lib::$request['stream']) || empty(ipTV_lib::$request['password']) || ipTV_lib::$settings['live_streaming_pass'] != ipTV_lib::$request['password']) {
    http_response_code(401);
    die;
}
$streaming_block = false;
$stream = pathinfo(ipTV_lib::$request['stream']);
$stream_id = intval($stream['filename']);
$extension = $stream['extension'];
$ipTV_db->query('
                    SELECT t1.*
                    FROM `streams` t1
                    INNER JOIN `streams_sys` t2 ON t2.stream_id = t1.id AND t2.pid IS NOT NULL AND t2.server_id = \'%d\'
                    INNER JOIN `streams_types` t3 ON t3.type_id = t1.type AND t3.type_key = \'movie\'
                    WHERE t1.`id` = \'%d\'', SERVER_ID, $stream_id);
if (ipTV_lib::$settings['use_buffer'] == 0) {
    header('X-Accel-Buffering: no');
}
if ($ipTV_db->num_rows() > 0) {
    $Fc8c2b91e5fde0dc817c47293478940a = $ipTV_db->get_row();
    $ipTV_db->close_mysql();
    $E6dd23f358d554b9a74e3ae676bc8c9b = MOVIES_PATH . $stream_id . '.' . $extension;
    if (file_exists($E6dd23f358d554b9a74e3ae676bc8c9b)) {
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
        $buffer = 1024 * 8;
        //a28124da1815e0b87ed638f4cd963820:
        while (!feof($fp) && ($p = ftell($fp)) <= $end) {
            $response = stream_get_line($fp, $buffer);
            echo $response;
        }
        //d851a70aee5237a74fe51c01ffb880e3:
        fclose($fp);
        die;
    }
}
function shutdown()
{
    global $ipTV_db, $streaming_block;
    if ($streaming_block) {
        CheckFlood();
    }
    if (is_object($ipTV_db)) {
        $ipTV_db->close_mysql();
    }
    fastcgi_finish_request();
}
?>
