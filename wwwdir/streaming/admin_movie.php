<?php
/*Rev:26.09.18r0*/

register_shutdown_function('shutdown');
set_time_limit(0);
require '../init.php';
$f0ac6ad2b40669833242a10c23cad2e0 = true;
$E824497a502b6e5dd609c0acb45697c7 = false;
$e13ac89e162bcc9913e553b949f755b6 = 0;
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
$f0ac6ad2b40669833242a10c23cad2e0 = false;
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
        $Ff876e96994aa5b09ce92e771efe2038 = filesize($E6dd23f358d554b9a74e3ae676bc8c9b);
        $length = $Ff876e96994aa5b09ce92e771efe2038;
        $start = 0;
        $ebe823668f9748302d3bd87782a71948 = $Ff876e96994aa5b09ce92e771efe2038 - 1;
        header("Accept-Ranges: 0-{$length}");
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
            $length = $ebe823668f9748302d3bd87782a71948 - $start + 1;
            fseek($fp, $start);
            header('HTTP/1.1 206 Partial Content');
        }
        header("Content-Range: bytes {$start}-{$ebe823668f9748302d3bd87782a71948}/{$Ff876e96994aa5b09ce92e771efe2038}");
        header('Content-Length: ' . $length);
        $C7558f823ac28009bfd4730a82f1f01b = 1024 * 8;
        //a28124da1815e0b87ed638f4cd963820:
        while (!feof($fp) && ($f11bd4ac0a2baf9850141d4517561cff = ftell($fp)) <= $ebe823668f9748302d3bd87782a71948) {
            $response = stream_get_line($fp, $C7558f823ac28009bfd4730a82f1f01b);
            echo $response;
        }
        //d851a70aee5237a74fe51c01ffb880e3:
        fclose($fp);
        die;
    }
}
function shutdown()
{
    global $ipTV_db, $f0ac6ad2b40669833242a10c23cad2e0;
    if ($f0ac6ad2b40669833242a10c23cad2e0) {
        CheckFlood();
    }
    if (is_object($ipTV_db)) {
        $ipTV_db->close_mysql();
    }
    fastcgi_finish_request();
}
?>
