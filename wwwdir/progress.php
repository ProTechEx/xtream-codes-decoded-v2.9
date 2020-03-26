<?php
/*Rev:26.09.18r0*/

ignore_user_abort(true);
$b0554ea497c1f0abb950b566e375aeb9 = trim(file_get_contents('php://input'));
if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1' || empty($_GET['stream_id']) || empty($b0554ea497c1f0abb950b566e375aeb9)) {
    die;
}
$stream_id = intval($_GET['stream_id']);
$d76067cf9572f7a6691c85c12faf2a29 = array_filter(array_map('trim', explode('
', $b0554ea497c1f0abb950b566e375aeb9)));
$output = array();
foreach ($d76067cf9572f7a6691c85c12faf2a29 as $row) {
    list($key, $a1daec950dd361ae639ad3a57dc018c0) = explode('=', $row);
    $output[trim($key)] = trim($a1daec950dd361ae639ad3a57dc018c0);
}
$fp = fopen("/home/xtreamcodes/iptv_xtream_codes/streams/{$stream_id}_.progress", 'w');
fwrite($fp, json_encode($output));
fclose($fp);
?>
