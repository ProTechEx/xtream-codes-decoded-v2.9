<?php
/*Rev:26.09.18r0*/

set_time_limit(0);
ini_set('memory_limit', -1);
if (!@$argc) {
    die(0);
}
require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
cli_set_process_title('XtreamCodes[Users Parser]');
$unique_id = TMP_DIR . md5(UniqueID() . __FILE__);
KillProcessCmd($unique_id);
$user_auto_kick_hours = ipTV_lib::$settings['user_auto_kick_hours'] * 3600;
$bitrates = array();
$connections = GetConnections('open');
$connectionFiles = explode('', shell_exec('find /home/xtreamcodes/iptv_xtream_codes/tmp/ -maxdepth 1 -name "*.con" -print0 | xargs -0 grep "" -H'));
shell_exec('rm -f /home/xtreamcodes/iptv_xtream_codes/tmp/*.con');
foreach ($connections as $user_id => $rows) {
    $length = count($rows);
    foreach ($rows as $key => $connection) {
        if (!($connection['max_connections'] != 0 && $connection['max_connections'] < $length)) {
            if ($connection['server_id'] == SERVER_ID) {
                if (!(!is_null($connection['exp_date']) && $connection['exp_date'] < time())) {
                    $time = time() - $connection['date_start'];
                    if (!($user_auto_kick_hours != 0 && $user_auto_kick_hours <= $time && $connection['is_restreamer'] == 0)) {
                        if (!($connection['container'] == 'hls')) {
                            if ($connection['container'] != 'rtmp') {
                                if (ipTV_streaming::ps_running($connection['pid'], 'php-fpm')) {
                                    $bitrates[$connection['activity_id']] = intval($connection['bitrate'] / 8 * 0.92);
                                } else {
                                    echo '[+] Closing Connection (Closed UnExp): ' . $connection['activity_id'] . '';
                                    ipTV_streaming::RemoveConnection($connection);
                                }
                            } else {
                                if (60 <= time() - $connection['hls_last_read'] || $connection['hls_end'] == 1) {
                                    echo '[+] Closing ENDED Con HLS: ' . $connection['activity_id'] . '';
                                    ipTV_streaming::RemoveConnection($connection);
                                    $length--;
                                }
                            }
                        }
                    } else {
                        echo '[+] Closing Connection[KICK TIME ONLINE]: ' . $connection['activity_id'] . '';
                        ipTV_streaming::RemoveConnection($connection);
                        $length--;
                    }
                } else {
                    echo '[+] Closing Connection: ' . $connection['activity_id'] . '';
                    ipTV_streaming::RemoveConnection($connection);
                    $length = 0;
                }
            }
        } else {
            echo '[+] Closing Connection caused max Connections overflow...';
            ipTV_streaming::RemoveConnection($connection);
            $length--;
        }
    }
}
foreach ($connectionFiles as $conn) {
    if (empty($conn)) {
        continue;
    }
    list($fl, $total) = explode(':', basename($conn));
    list($activity_id, $value) = explode('.', $fl);
    if (isset($bitrates[$activity_id])) {
        $divergence = intval(($total - $bitrates[$activity_id]) / $bitrates[$activity_id] * 100);
        if (0 < $divergence) {
            $divergence = 0;
        }
        $ipTV_db->query('UPDATE `user_activity_now` SET `divergence` = \'%d\' WHERE `activity_id` = \'%d\'', abs($divergence), $activity_id);
    } else {
        @unlink(TMP_DIR . $fl);
    }
}
@unlink($unique_id);
?>
