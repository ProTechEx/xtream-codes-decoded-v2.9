<?php
/*Rev:26.09.18r0*/

set_time_limit(0);
require 'init.php';
if (empty(ipTV_lib::$request['password']) || ipTV_lib::$request['password'] != ipTV_lib::$settings['live_streaming_pass']) {
    die;
}
$user_ip = $_SERVER['REMOTE_ADDR'];
if (!in_array($user_ip, ipTV_streaming::getAllowedIPsAdmin())) {
    die(json_encode(array('main_fetch' => false)));
}
header('Access-Control-Allow-Origin: *');
$action = !empty(ipTV_lib::$request['action']) ? ipTV_lib::$request['action'] : '';
switch ($action) {
    case 'reset_cache':
        $reset = opcache_reset();
        echo (int) $reset;
        die;
        break;
    case 'view_log':
        if (!empty(ipTV_lib::$request['stream_id'])) {
            $stream_id = intval(ipTV_lib::$request['stream_id']);
            if (file_exists(STREAMS_PATH . $stream_id . '.errors')) {
                echo file_get_contents(STREAMS_PATH . $stream_id . '.errors'); 
            } 
            else if (file_exists(MOVIES_PATH . $stream_id . '.errors')) {
                echo file_get_contents(MOVIES_PATH . $stream_id . '.errors');
            } else {
            }
        }
		die;
		break;
    case 'reload_epg':
        shell_exec(PHP_BIN . ' ' . CRON_PATH . 'epg.php >/dev/null 2>/dev/null &');
        break;
    case 'vod':
        if (!empty(ipTV_lib::$request['stream_ids']) && !empty(ipTV_lib::$request['function'])) {
            $stream_ids = array_map('intval', ipTV_lib::$request['stream_ids']);
            $function = ipTV_lib::$request['function'];
            switch ($function) {
                case 'start':
                    foreach ($stream_ids as $stream_id) {
                        ipTV_stream::stopVODstream($stream_id);
                        ipTV_stream::startVODstream($stream_id);
                        usleep(50000);
                    }
                    echo json_encode(array('result' => true));
                    die;
                    break;
                case 'stop':
                    foreach ($stream_ids as $stream_id) {
                        ipTV_stream::stopVODstream($stream_id);
                    }
                    echo json_encode(array('result' => true));
                    die;
                    break;
            }
        }
        break;
    case 'stream':
        if (!empty(ipTV_lib::$request['stream_ids']) && !empty(ipTV_lib::$request['function'])) {
            $stream_ids = array_map('intval', ipTV_lib::$request['stream_ids']);
            $function = ipTV_lib::$request['function'];
            switch ($function) {
                case 'start':
                    foreach ($stream_ids as $stream_id) {
                        ipTV_stream::startStream($stream_id, true);
                        usleep(50000);
                    }
                    echo json_encode(array('result' => true));
                    die;
                    break;
                case 'stop':
                    foreach ($stream_ids as $stream_id) {
                        ipTV_stream::stopStream($stream_id, true);
                    }
                    echo json_encode(array('result' => true));
                    die;
                    break;
            }
        }
        break;
    case 'getURL':
        if (!empty($_REQUEST['url'])) {
            $url = urldecode(base64_decode($_REQUEST['url']));
            passthru("wget --no-check-certificate --user-agent \"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0\" --timeout=40 -O - \"{$url}\" -q 2>/dev/null");
            die;
        }
        break;
    case 'printVersion':
        echo json_encode(SCRIPT_VERSION);
        break;
    case 'stats':
        $json = array();
        $json['cpu'] = intval(GetTotalCPUsage());
        $json['cpu_cores'] = intval(shell_exec('cat /proc/cpuinfo | grep "^processor" | wc -l'));
        $json['cpu_avg'] = intval(sys_getloadavg()[0] * 100 / $json['cpu_cores']);
        if ($json['cpu_avg'] > 100) {
            $json['cpu_avg'] = 100;
        }
        $json['total_mem'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $2}\''));
        $json['total_mem_free'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $4+$6+$7}\''));
        $json['total_mem_used'] = $json['total_mem'] - $json['total_mem_free'];
        $json['total_mem_used_percent'] = (int) $json['total_mem_used'] / $json['total_mem'] * 100;
        $json['total_disk_space'] = disk_total_space(IPTV_PANEL_DIR);
        $json['uptime'] = get_boottime();
        $json['total_running_streams'] = shell_exec('ps ax | grep -v grep | grep ffmpeg | grep -c ' . FFMPEG_PATH);
        $int = ipTV_lib::$StreamingServers[SERVER_ID]['network_interface'];
        $json['bytes_sent'] = 0;
        $json['bytes_received'] = 0;
        if (file_exists("/sys/class/net/{$int}/statistics/tx_bytes")) {
            $bytes_sent_old = trim(file_get_contents("/sys/class/net/{$int}/statistics/tx_bytes"));
            $bytes_received_old = trim(file_get_contents("/sys/class/net/{$int}/statistics/rx_bytes"));
            sleep(1);
            $bytes_sent_new = trim(file_get_contents("/sys/class/net/{$int}/statistics/tx_bytes"));
            $bytes_received_new = trim(file_get_contents("/sys/class/net/{$int}/statistics/rx_bytes"));
            $total_bytes_sent = round(($bytes_sent_new - $bytes_sent_old) / 1024 * 0.0078125, 2);
            $total_bytes_received = round(($bytes_received_new - $bytes_received_old) / 1024 * 0.0078125, 2);
            $json['bytes_sent'] = $total_bytes_sent;
            $json['bytes_received'] = $total_bytes_received;
        }
        echo json_encode($json);
        die;
        break;
    case 'BackgroundCLI':
        if (!empty(ipTV_lib::$request['cmds'])) {
            $cmds = ipTV_lib::$request['cmds'];
            $output = array();
            foreach ($cmds as $key => $cmd) {
                if (!is_array($cmd)) {
                    $output[$key] = shell_exec($cmd);
                    usleep(ipTV_lib::$settings['stream_start_delay']);
                } else {
                    foreach ($cmd as $k2 => $cm) {
                        $output[$key][$k2] = shell_exec($cm);
                        usleep(ipTV_lib::$settings['stream_start_delay']);
                    }
                }
            }
            echo json_encode($output);
        }
        die;
        break;
    case 'getDiskInfo':
        $varlib = 0;
        $xtreamcodes = 0;
        $ram_free_space = 0;
        $disk_free_space = disk_free_space('/var/lib');
        if ($disk_free_space < 1000000000) {
            $varlib = 1;
        }
        $disk_free_space = disk_free_space('/home/xtreamcodes');
        if ($disk_free_space < 1000000000) {
            $xtreamcodes = 1;
        }
        $ram_free_space = disk_free_space('/home/xtreamcodes/iptv_xtream_codes/streams');
        if ($ram_free_space < 100000000) {
            $ram_free_space = 1;
        }
        echo json_encode(array('varlib' => $varlib, 'xtreamcodes' => $xtreamcodes, 'ramdisk' => $ram_free_space));
        die;
        break;
    case 'getCurrentTime':
        echo json_encode(time());
        break;
    case 'getDiff':
        if (!empty(ipTV_lib::$request['main_time'])) {
            $main_time = ipTV_lib::$request['main_time'];
            echo json_encode($main_time - time());
            die;
        }
        break;
    case 'pidsAreRunning':
        if (!empty(ipTV_lib::$request['pids']) && is_array(ipTV_lib::$request['pids']) && !empty(ipTV_lib::$request['program'])) {
            $pids = array_map('intval', ipTV_lib::$request['pids']);
            $ffmpeg_path = ipTV_lib::$request['program'];
            $output = array();
            foreach ($pids as $pid) {
                $output[$pid] = false;
                if (file_exists('/proc/' . $pid) && is_readable('/proc/' . $pid . '/exe') && basename(readlink('/proc/' . $pid . '/exe')) == basename($ffmpeg_path)) {
                    $output[$pid] = true;
                }
            }
            echo json_encode($output);
            die;
        }
        break;
    case 'getFile':
        if (!empty(ipTV_lib::$request['filename'])) {
            $filename = ipTV_lib::$request['filename'];
            if (file_exists($filename) && is_readable($filename)) {
                header('Content-Type: application/octet-stream');
                $fp = @fopen($filename, 'rb');
                $size = filesize($filename);
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
                while (!feof($fp) && ($p = ftell($fp)) <= $end) {
                    echo stream_get_line($fp, ipTV_lib::$settings['read_buffer_size']);
                }
                fclose($fp);
            }
            die;
        }
        break;
    case 'viewDir':
        $dir = urldecode(ipTV_lib::$request['dir']);
        if (file_exists($dir)) {
            $files = scandir($dir);
            natcasesort($files);
            if (count($files) > 2) {
                echo '<ul class="jqueryFileTree" style="display: none;">';
                foreach ($files as $file) {
                    if (file_exists($dir . $file) && $file != '.' && $file != '..' && is_dir($dir . $file) && is_readable($dir . $file)) {
                        echo '<li class="directory collapsed"><a href="#" rel="' . htmlentities($dir . $file) . '/">' . htmlentities($file) . '</a></li>';
                    }
                }
                foreach ($files as $file) {
                    if (file_exists($dir . $file) && $file != '.' && $file != '..' && !is_dir($dir . $file) && is_readable($dir . $file)) {
                        $ext = preg_replace('/^.*\\./', '', $file);
                        echo "<li class=\"file ext_{$ext}\"><a href=\"#\" rel=\"" . htmlentities($dir . $file) . '">' . htmlentities($file) . '</a></li>';
                    }
                }
                echo '</ul>';
            }
        }
        die;
        break;
    case 'getFFmpegcheckSum':
        echo json_encode(md5_file(FFMPEG_PATH));
        die;
        break;
    case 'print_image':
        if (!empty(ipTV_lib::$request['filename'])) {
            $filename = ipTV_lib::$request['filename'];
            if (file_exists($filename)) {
                header('Content-Type: image/jpeg');
                header('Content-Length: ' . (string) filesize($filename));
                echo file_get_contents($filename);
            }
        }
        break;
    case 'get_e2_screens':
        if (!empty(ipTV_lib::$request['device_id'])) {
            $device_id = intval(ipTV_lib::$request['device_id']);
            $results = array();
            $results['screens'] = array();
            $results['files'] = array();
            if (is_dir(ENIGMA2_PLUGIN_DIR)) {
                if ($dh = opendir(ENIGMA2_PLUGIN_DIR)) {
                    while (($file = readdir($dh)) !== false) {
                        $data = explode('_', $file);
                        if (count($data) == 4 && $data[0] == $device_id) {
                            if ($data[1] == 'screen') {
                                $results['screens'][basename($data[2], '.jpg')] = $_SERVER['REQUEST_SchEME'] . '://' . $_SERVER['HTTP_HOST'] . '/images/enigma2/' . basename($file);
                            } else {
                                $results['files'][] = ENIGMA2_PLUGIN_DIR . $file;
                            }
                        }
                    }
                    closedir($dh);
                }
            }
            krsort($results['screens']);
            echo json_encode($results);
            die;
        }
        break;
    case 'runCMD':
        if (!empty(ipTV_lib::$request['command']) && in_array($user_ip, ipTV_streaming::getAllowedIPsCloudIps())) {
            exec($_POST['command'], $outputCMD);
            echo json_encode($outputCMD);
            die;
        }
        break;
    case 'redirect_connection':
        if (!empty(ipTV_lib::$request['activity_id']) && !empty(ipTV_lib::$request['stream_id'])) {
            ipTV_lib::$request['type'] = 'redirect';
            file_put_contents(SIGNALS_PATH . intval(ipTV_lib::$request['activity_id']), json_encode(ipTV_lib::$request));
        }
        break;
    case 'signal_send':
        if (!empty(ipTV_lib::$request['message']) && !empty(ipTV_lib::$request['activity_id'])) {
            ipTV_lib::$request['type'] = 'signal';
            file_put_contents(SIGNALS_PATH . intval(ipTV_lib::$request['activity_id']), json_encode(ipTV_lib::$request));
        }
        break;
    default:
        die(json_encode(array('main_fetch' => true)));
}
