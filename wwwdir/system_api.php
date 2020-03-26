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
        $c2714edb9f7cb977cefa4865b4718aeb = opcache_reset();
        echo (int) $c2714edb9f7cb977cefa4865b4718aeb;
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
                //ed4d20518c5b68508123ed7418990b68:
                //goto A519fe2f4510493e285a5f010c5ada6d;
            }
            die;
            //E06d5cf358563ba0520fb6b1916057a0:
            break;
            
        }
    case 'reload_epg':
        shell_exec(PHP_BIN . ' ' . CRON_PATH . 'epg.php >/dev/null 2>/dev/null &');
        break;
    case 'vod':
        if (!empty(ipTV_lib::$request['stream_ids']) && !empty(ipTV_lib::$request['function'])) {
            $stream_ids = array_map('intval', ipTV_lib::$request['stream_ids']);
            $Daacff3221aec728feb2b951e375d30c = ipTV_lib::$request['function'];
            switch ($Daacff3221aec728feb2b951e375d30c) {
                case 'start':
                    foreach ($stream_ids as $stream_id) {
                        ipTV_stream::b533e0F5F988919d1c3b076A87f9B0E3($stream_id);
                        ipTV_stream::f8ab00514D4db9462A088927B8d3A8e6($stream_id);
                        usleep(50000);
                    }
                    echo json_encode(array('result' => true));
                    die;
                    break;
                case 'stop':
                    foreach ($stream_ids as $stream_id) {
                        ipTV_stream::B533E0f5f988919D1C3b076A87F9b0e3($stream_id);
                        //d2e259c6c4dde0e29760d1be8596a183:
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
            $Daacff3221aec728feb2b951e375d30c = ipTV_lib::$request['function'];
            switch ($Daacff3221aec728feb2b951e375d30c) {
                case 'start':
                    foreach ($stream_ids as $stream_id) {
                        ipTV_stream::e79092731573697c16A932c339D0a101($stream_id, true);
                        usleep(50000);
                    }
                    echo json_encode(array('result' => true));
                    die;
                    break;
                case 'stop':
                    foreach ($stream_ids as $stream_id) {
                        ipTV_stream::C27c26B9eD331706a4C3F0292142FB52($stream_id, true);
                        //d38d68138bb88898325a5e31b37f3888:
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
        $D8dbdb2118a7a93a0eeb04fc548f2af4 = array();
        $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu'] = intval(A072e3167C4fD73eb67540546C961b7e());
        $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_cores'] = intval(shell_exec('cat /proc/cpuinfo | grep "^processor" | wc -l'));
        $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_avg'] = intval(sys_getloadavg()[0] * 100 / $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_cores']);
        if ($D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_avg'] > 100) {
            $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_avg'] = 100;
        }
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $2}\''));
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_free'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $4+$6+$7}\''));
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_used'] = $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem'] - $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_free'];
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_used_percent'] = (int) $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_used'] / $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem'] * 100;
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_disk_space'] = disk_total_space(IPTV_PANEL_DIR);
        $D8dbdb2118a7a93a0eeb04fc548f2af4['uptime'] = B46eFA30B8cF4A7596D9D54730aDB795();
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_running_streams'] = shell_exec('ps ax | grep -v grep | grep ffmpeg | grep -c ' . FFMPEG_PATH);
        $d0d324f3dbb8bbc5fff56e8a848beb7a = ipTV_lib::$StreamingServers[SERVER_ID]['network_interface'];
        $D8dbdb2118a7a93a0eeb04fc548f2af4['bytes_sent'] = 0;
        $D8dbdb2118a7a93a0eeb04fc548f2af4['bytes_received'] = 0;
        if (file_exists("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/tx_bytes")) {
            $b10021b298f7d4ce2f8e80315325fa1a = trim(file_get_contents("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/tx_bytes"));
            $C5b51b10f98c22fb985e90c23eade263 = trim(file_get_contents("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/rx_bytes"));
            sleep(1);
            $e54a6ff3afc52767cdd38f62ab4c38d1 = trim(file_get_contents("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/tx_bytes"));
            $d1a978924624c41845605404ded7e846 = trim(file_get_contents("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/rx_bytes"));
            $c01d5077f34dc0ef046a6efa9e8e24f4 = round(($e54a6ff3afc52767cdd38f62ab4c38d1 - $b10021b298f7d4ce2f8e80315325fa1a) / 1024 * 0.0078125, 2);
            $B5490c2f61c894c091e04441954a0f09 = round(($d1a978924624c41845605404ded7e846 - $C5b51b10f98c22fb985e90c23eade263) / 1024 * 0.0078125, 2);
            $D8dbdb2118a7a93a0eeb04fc548f2af4['bytes_sent'] = $c01d5077f34dc0ef046a6efa9e8e24f4;
            $D8dbdb2118a7a93a0eeb04fc548f2af4['bytes_received'] = $B5490c2f61c894c091e04441954a0f09;
        }
        echo json_encode($D8dbdb2118a7a93a0eeb04fc548f2af4);
        die;
        break;
    case 'BackgroundCLI':
        if (!empty(ipTV_lib::$request['cmds'])) {
            $F89e3c76f1417e0acb828e29b1a741bc = ipTV_lib::$request['cmds'];
            $output = array();
            foreach ($F89e3c76f1417e0acb828e29b1a741bc as $key => $cmd) {
                if (!is_array($cmd)) {
                    $output[$key] = shell_exec($cmd);
                    usleep(ipTV_lib::$settings['stream_start_delay']);
                } else {
                    foreach ($cmd as $bd8ff2f6ff707379a535b26ad00d9524 => $Ecd6895dce094cd665683aacb70b4039) {
                        $output[$key][$bd8ff2f6ff707379a535b26ad00d9524] = shell_exec($Ecd6895dce094cd665683aacb70b4039);
                        usleep(ipTV_lib::$settings['stream_start_delay']);
                    }
                }
            }
            echo json_encode($output);
        }
        die;
        break;
    case 'getDiskInfo':
        $Dae9fdb15b5050a6f71a0f3dabcac82f = 0;
        $b25cecdb955cf2fc1ccced0ac66e026f = 0;
        $A5a9ed0ba780cadbb57ddfd50eb42c47 = 0;
        $E9244d25cfa86ac9b3240cb86869e724 = disk_free_space('/var/lib');
        if ($E9244d25cfa86ac9b3240cb86869e724 < 1000000000) {
            $Dae9fdb15b5050a6f71a0f3dabcac82f = 1;
        }
        $E9244d25cfa86ac9b3240cb86869e724 = disk_free_space('/home/xtreamcodes');
        if ($E9244d25cfa86ac9b3240cb86869e724 < 1000000000) {
            $b25cecdb955cf2fc1ccced0ac66e026f = 1;
        }
        $A5a9ed0ba780cadbb57ddfd50eb42c47 = disk_free_space('/home/xtreamcodes/iptv_xtream_codes/streams');
        if ($A5a9ed0ba780cadbb57ddfd50eb42c47 < 100000000) {
            $A5a9ed0ba780cadbb57ddfd50eb42c47 = 1;
        }
        echo json_encode(array('varlib' => $Dae9fdb15b5050a6f71a0f3dabcac82f, 'xtreamcodes' => $b25cecdb955cf2fc1ccced0ac66e026f, 'ramdisk' => $A5a9ed0ba780cadbb57ddfd50eb42c47));
        die;
        break;
    case 'getCurrentTime':
        echo json_encode(time());
        break;
    case 'getDiff':
        if (!empty(ipTV_lib::$request['main_time'])) {
            $Ec8d3c4b950aa2b1c857a64ae5263c97 = ipTV_lib::$request['main_time'];
            echo json_encode($Ec8d3c4b950aa2b1c857a64ae5263c97 - time());
            die;
        }
        break;
    case 'pidsAreRunning':
        if (!empty(ipTV_lib::$request['pids']) && is_array(ipTV_lib::$request['pids']) && !empty(ipTV_lib::$request['program'])) {
            $B065e352842444ddce37346f0c648660 = array_map('intval', ipTV_lib::$request['pids']);
            $ffmpeg_path = ipTV_lib::$request['program'];
            $output = array();
            foreach ($B065e352842444ddce37346f0c648660 as $pid) {
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
            $dae587fac852b56aefd2f953ed975545 = ipTV_lib::$request['filename'];
            if (file_exists($dae587fac852b56aefd2f953ed975545) && is_readable($dae587fac852b56aefd2f953ed975545)) {
                header('Content-Type: application/octet-stream');
                $fp = @fopen($dae587fac852b56aefd2f953ed975545, 'rb');
                $Ff876e96994aa5b09ce92e771efe2038 = filesize($dae587fac852b56aefd2f953ed975545);
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
                //B5e74df099d83a93b56f89f2cc3c10f2:
                while (!feof($fp) && ($f11bd4ac0a2baf9850141d4517561cff = ftell($fp)) <= $ebe823668f9748302d3bd87782a71948) {
                    echo stream_get_line($fp, ipTV_lib::$settings['read_buffer_size']);
                }
                //ea0af39edc4d150dd8e9d6c757cef3e8:
                fclose($fp);
            }
            die;
        }
        break;
    case 'viewDir':
        $C7d43e76a407b920ecf2277b2eae4820 = urldecode(ipTV_lib::$request['dir']);
        if (file_exists($C7d43e76a407b920ecf2277b2eae4820)) {
            $Ba78aa94423804e042a0eb27ba2e39a4 = scandir($C7d43e76a407b920ecf2277b2eae4820);
            natcasesort($Ba78aa94423804e042a0eb27ba2e39a4);
            if (count($Ba78aa94423804e042a0eb27ba2e39a4) > 2) {
                echo '<ul class="jqueryFileTree" style="display: none;">';
                foreach ($Ba78aa94423804e042a0eb27ba2e39a4 as $Ca434bcc380e9dbd2a3a588f6c32d84f) {
                    if (file_exists($C7d43e76a407b920ecf2277b2eae4820 . $Ca434bcc380e9dbd2a3a588f6c32d84f) && $Ca434bcc380e9dbd2a3a588f6c32d84f != '.' && $Ca434bcc380e9dbd2a3a588f6c32d84f != '..' && is_dir($C7d43e76a407b920ecf2277b2eae4820 . $Ca434bcc380e9dbd2a3a588f6c32d84f) && is_readable($C7d43e76a407b920ecf2277b2eae4820 . $Ca434bcc380e9dbd2a3a588f6c32d84f)) {
                        echo '<li class="directory collapsed"><a href="#" rel="' . htmlentities($C7d43e76a407b920ecf2277b2eae4820 . $Ca434bcc380e9dbd2a3a588f6c32d84f) . '/">' . htmlentities($Ca434bcc380e9dbd2a3a588f6c32d84f) . '</a></li>';
                    }
                }
                foreach ($Ba78aa94423804e042a0eb27ba2e39a4 as $Ca434bcc380e9dbd2a3a588f6c32d84f) {
                    if (file_exists($C7d43e76a407b920ecf2277b2eae4820 . $Ca434bcc380e9dbd2a3a588f6c32d84f) && $Ca434bcc380e9dbd2a3a588f6c32d84f != '.' && $Ca434bcc380e9dbd2a3a588f6c32d84f != '..' && !is_dir($C7d43e76a407b920ecf2277b2eae4820 . $Ca434bcc380e9dbd2a3a588f6c32d84f) && is_readable($C7d43e76a407b920ecf2277b2eae4820 . $Ca434bcc380e9dbd2a3a588f6c32d84f)) {
                        $b1580f4b7a11eac92cfea41e2f09d832 = preg_replace('/^.*\\./', '', $Ca434bcc380e9dbd2a3a588f6c32d84f);
                        echo "<li class=\"file ext_{$b1580f4b7a11eac92cfea41e2f09d832}\"><a href=\"#\" rel=\"" . htmlentities($C7d43e76a407b920ecf2277b2eae4820 . $Ca434bcc380e9dbd2a3a588f6c32d84f) . '">' . htmlentities($Ca434bcc380e9dbd2a3a588f6c32d84f) . '</a></li>';
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
            $dae587fac852b56aefd2f953ed975545 = ipTV_lib::$request['filename'];
            if (file_exists($dae587fac852b56aefd2f953ed975545)) {
                header('Content-Type: image/jpeg');
                header('Content-Length: ' . (string) filesize($dae587fac852b56aefd2f953ed975545));
                echo file_get_contents($dae587fac852b56aefd2f953ed975545);
            }
        }
        break;
    case 'get_e2_screens':
        if (!empty(ipTV_lib::$request['device_id'])) {
            $a0bdfe2058b3579da2b71ebf929871e2 = intval(ipTV_lib::$request['device_id']);
            $results = array();
            $results['screens'] = array();
            $results['files'] = array();
            if (is_dir(ENIGMA2_PLUGIN_DIR)) {
                if ($abdb80e31a5182f342c715b2ea8096c7 = opendir(ENIGMA2_PLUGIN_DIR)) {
                    //cc5e7706d83f9b66a94d1874bdeeedd8:
                    while (($Ca434bcc380e9dbd2a3a588f6c32d84f = readdir($abdb80e31a5182f342c715b2ea8096c7)) !== false) {
                        $data = explode('_', $Ca434bcc380e9dbd2a3a588f6c32d84f);
                        if (count($data) == 4 && $data[0] == $a0bdfe2058b3579da2b71ebf929871e2) {
                            if ($data[1] == 'screen') {
                                $results['screens'][basename($data[2], '.jpg')] = $_SERVER['REQUEST_SchEME'] . '://' . $_SERVER['HTTP_HOST'] . '/images/enigma2/' . basename($Ca434bcc380e9dbd2a3a588f6c32d84f);
                            } else {
                                $results['files'][] = ENIGMA2_PLUGIN_DIR . $Ca434bcc380e9dbd2a3a588f6c32d84f;
                            }
                        }
                    }
                    //A222423d540901bd1cdec6a8df23b05a:
                    closedir($abdb80e31a5182f342c715b2ea8096c7);
                }
            }
            krsort($results['screens']);
            echo json_encode($results);
            die;
        }
        break;
    case 'runCMD':
        if (!empty(ipTV_lib::$request['command']) && in_array($user_ip, ipTV_streaming::B20c5d64B4C7dbFafFeA9f96934138a4())) {
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
//Ddade308d8bb86dc2d12f7f92835fc10:
