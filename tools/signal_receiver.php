<?php

set_time_limit(0);
if (!@$argc) {
    die(0);
}
require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
cli_set_process_title('XtreamCodes[XC Signal Receiver]');
shell_exec('kill $(ps aux | grep \'XC Signal Receiver\' | grep -v grep | grep -v ' . getmypid() . ' | awk \'{print $2}\')');
$ipTV_db->query('DELETE FROM `signals` WHERE `server_id` = \'%d\'', SERVER_ID);
while (!false) {
    if (!$ipTV_db->query('SELECT `signal_id`,`pid`,`rtmp` FROM `signals` WHERE `server_id` = \'%d\' ORDER BY signal_id ASC LIMIT 100', SERVER_ID)) {
        break;
    }
    if (0 < $ipTV_db->num_rows()) {
        $signal_id = array();
        foreach ($ipTV_db->get_rows() as $row) {
            $signal_id[] = $row['signal_id'];
            $pid = $row['pid'];
            if ($row['rtmp'] == 0) {
                if (!empty($pid) && file_exists('/proc/' . $pid)) {
                    shell_exec('kill -9 ' . $pid);
                }
            } else {
                file_get_contents(ipTV_lib::$StreamingServers[SERVER_ID]['rtmp_mport_url'] . ('control/drop/client?clientid=' . $pid));
            }
        }
        $ipTV_db->query('DELETE FROM `signals` WHERE `signal_id` IN(' . implode(',', $signal_id) . ')');
    }
    sleep(1);
}
shell_exec('(sleep 1; ' . PHP_BIN . ' ' . __FILE__ . ' ) > /dev/null 2>/dev/null &');
?>
