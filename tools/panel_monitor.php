<?php
/*Rev:26.09.18r0*/

set_time_limit(0);
if (!@$argc) {
    die(0);
}
require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
cli_set_process_title('XtreamCodes[XC Panel Monitor]');
shell_exec('kill $(ps aux | grep \'XC Panel Monitor\' | grep -v grep | grep -v ' . getmypid() . ' | awk \'{print $2}\')');
if (ipTV_lib::$settings['firewall'] == 0) {
    file_put_contents(TMP_DIR . 'd52d7d1df4f329bda8b2d9f67fa5d846', 1);
    unlink(TMP_DIR . '5a9ccab64e61d9af12baa7d4011acc1a');
    die;
}
file_put_contents(TMP_DIR . '5a9ccab64e61d9af12baa7d4011acc1a', 1);
unlink(TMP_DIR . 'd52d7d1df4f329bda8b2d9f67fa5d846');
$time = time();
while (!false) {
    if (!$ipTV_db->query('SELECT `firewall` FROM settings')) {
        break;
    }
    $a9b72a17757137f71123eee7cf9a0b25 = $ipTV_db->get_row();
    if ($a9b72a17757137f71123eee7cf9a0b25['firewall'] == 0) {
        file_put_contents(TMP_DIR . 'd52d7d1df4f329bda8b2d9f67fa5d846', 1);
        unlink(TMP_DIR . '5a9ccab64e61d9af12baa7d4011acc1a');
        die;
    }
    file_put_contents(TMP_DIR . '5a9ccab64e61d9af12baa7d4011acc1a', 1);
    if (file_exists(TMP_DIR . 'd52d7d1df4f329bda8b2d9f67fa5d846')) {
        unlink(TMP_DIR . 'd52d7d1df4f329bda8b2d9f67fa5d846');
    }
    if (!$ipTV_db->query('SELECT `username`,`password` FROM users WHERE enabled = 1 AND admin_enabled = 1 AND (exp_date > ' . time() . ' OR exp_date IS NULL)')) {
        break;
    }
    if (0 < $ipTV_db->num_rows()) {
        foreach ($ipTV_db->get_rows() as $row) {
            file_put_contents(TMP_DIR . md5(strtolower($row['username'] . $row['password'])), 1);
        }
    }
    if (600 <= time() - $time) {
        unlink(IPTV_PANEL_DIR . 'tmp/blacklist');
        $time = time();
    }
    sleep(3);
}
shell_exec('(sleep 1; ' . PHP_BIN . ' ' . __FILE__ . ' ) > /dev/null 2>/dev/null &');
?>
