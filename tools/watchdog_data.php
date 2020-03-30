<?php
/*Rev:26.09.18r0*/

if (!@$argc) {
    die(0);
}
require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
cli_set_process_title('XtreamCodes[Server WatchDog]');
shell_exec('kill $(ps aux | grep \'Server WatchDog\' | grep -v grep | grep -v ' . getmypid() . ' | awk \'{print $2}\')');
while (!false) {
    if (!$ipTV_db->query('UPDATE `streaming_servers` SET `watchdog_data` = \'%s\' WHERE `id` = \'%d\'', json_encode(watchdogData(), JSON_PARTIAL_OUTPUT_ON_ERROR), SERVER_ID)) {
        break;
    }
    sleep(2);
}
shell_exec('(sleep 1; ' . PHP_BIN . ' ' . __FILE__ . ' ) > /dev/null 2>/dev/null &');
?>
