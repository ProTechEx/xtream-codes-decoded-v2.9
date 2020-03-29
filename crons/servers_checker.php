<?php

if (!@$argc) {
    die(0);
}
require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
cli_set_process_title('XtreamCodes[Server Checker]');
$unique_id = TMP_DIR . md5(UniqueID() . __FILE__);
KillProcessCmd($unique_id);
$signal_receiver = intval(trim(shell_exec('ps aux | grep signal_receiver | grep -v grep | wc -l')));
if ($signal_receiver == 0) {
    shell_exec(PHP_BIN . ' ' . IPTV_PANEL_DIR . 'tools/signal_receiver.php > /dev/null 2>/dev/null &');
}
$pipe_reader = intval(trim(shell_exec('ps aux | grep pipe_reader | grep -v grep | wc -l')));
if ($pipe_reader == 0) {
    shell_exec(PHP_BIN . ' ' . IPTV_PANEL_DIR . 'tools/pipe_reader.php > /dev/null 2>/dev/null &');
}
$panel_monitor = intval(trim(shell_exec('ps aux | grep panel_monitor | grep -v grep | wc -l')));
if ($panel_monitor == 0) {
    shell_exec(PHP_BIN . ' ' . IPTV_PANEL_DIR . 'tools/panel_monitor.php > /dev/null 2>/dev/null &');
}
$watchdog_data = intval(trim(shell_exec('ps aux | grep watchdog_data | grep -v grep | wc -l')));
if ($watchdog_data == 0) {
    shell_exec(PHP_BIN . ' ' . IPTV_PANEL_DIR . 'tools/watchdog_data.php > /dev/null 2>/dev/null &');
}
if (!file_exists(MOVIES_IMAGES)) {
    mkdir(MOVIES_IMAGES);	
}
if (!file_exists(ENIGMA2_PLUGIN_DIR)) {
    mkdir(ENIGMA2_PLUGIN_DIR);
}
$available = (int) trim(shell_exec('free | grep -c available'));
if ($available == 0) {
    $total_ram = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $2}\''));
    $total_used = $total_ram - intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $4+$6+$7}\''));
} else {
    $total_ram = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $2}\''));
    $total_used = $total_ram - intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $7}\''));
}
$cpu_cores = intval(shell_exec('lscpu | awk -F " : " \'/Core/ { c=$2; }; /Socket/ { print c*$2 }\' '));
$cpu_threads = intval(shell_exec('grep --count ^processor /proc/cpuinfo'));
$cpu_name = trim(shell_exec('cat /proc/cpuinfo | grep \'model name\' | uniq | awk -F: \'{print $2}\''));
$cpu_usage = intval(shell_exec('ps aux|awk \'NR > 0 { s +=$3 }; END {print s}\''));
$int = ipTV_lib::$StreamingServers[SERVER_ID]['network_interface'];
$total_bytes_sent = $total_bytes_received = $speed = NULL;
if (!empty($int)) {
    $speed = file_get_contents('/sys/class/net/' . $int . '/speed');
    $bytes_sent_old = trim(file_get_contents('/sys/class/net/' . $int . '/statistics/tx_bytes'));
    $bytes_received_old = trim(file_get_contents('/sys/class/net/' . $int . '/statistics/rx_bytes'));
    sleep(1);
    $bytes_sent_new = trim(file_get_contents('/sys/class/net/' . $int . '/statistics/tx_bytes'));
    $bytes_received_new = trim(file_get_contents('/sys/class/net/' . $int . '/statistics/rx_bytes'));
    $total_bytes_sent = round(($bytes_sent_new - $bytes_sent_old) / 1024 * 0.0078125, 2);
    $total_bytes_received = round(($bytes_received_new - $bytes_received_old) / 1024 * 0.0078125, 2);
}
$total_running_streams = shell_exec('ps ax | grep -v grep | grep ffmpeg | grep -c ' . FFMPEG_PATH);
$server_hardware = array('total_ram' => $total_ram, 'total_used' => $total_used, 'cores' => $cpu_cores, 'threads' => $cpu_threads, 'kernel' => trim(shell_exec('uname -r')), 'total_running_streams' => $total_running_streams, 'cpu_name' => $cpu_name, 'cpu_usage' => (int) $cpu_usage / $cpu_threads, 'network_speed' => $speed, 'bytes_sent' => $total_bytes_sent, 'bytes_received' => $total_bytes_received);
$whitelist_ips = array_values(array_unique(array_map('trim', explode('', shell_exec('ip -4 addr | grep -oP \'(?<=inet\\s)\\d+(\\.\\d+){3}\'')))));
$ipTV_db->query('UPDATE `streaming_servers` SET `server_hardware` = \'%s\',`whitelist_ips` = \'%s\' WHERE `id` = \'%d\'', json_encode($server_hardware), json_encode($whitelist_ips), SERVER_ID);
if (!file_exists(GEOIP2_FILENAME) || 86400 <= time() - filemtime(GEOIP2_FILENAME)) {
    passthru('wget --no-check-certificate --user-agent "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0" --timeout=40 "http://downloads.xtream-codes.com/v2/GeoLite2.mmdb" -O "' . GEOIP2_FILENAME . '" -q 2>/dev/null');
    touch(GEOIP2_FILENAME);
}
?>
