<?php
/*Rev:26.09.18r0*/

require './init.php';
$username = ipTV_lib::$request['username'];
$password = ipTV_lib::$request['password'];
$eb44bb017bb845b562c754c6978bad18 = ipTV_lib::$request['type'];
$bca72c242cf770f855c0eae8936335b7 = empty(ipTV_lib::$request['output']) ? '' : ipTV_lib::$request['output'];
$ipTV_db->query('SELECT `id`,`username`,`password` FROM `users` WHERE `username` = \'%s\' AND `password` = \'%s\' LIMIT 1', $username, $password);
if ($ipTV_db->num_rows() > 0) {
    $row = $ipTV_db->get_row();
    $user_id = $row['id'];
    if (ipTV_lib::$settings['case_sensitive_line'] == 0 || $row['username'] == $username && $row['password'] == $password) {
        ini_set('memory_limit', -1);
        if ($output = EA3020592126F8E67C0825492484aEf2($user_id, $eb44bb017bb845b562c754c6978bad18, $bca72c242cf770f855c0eae8936335b7, true)) {
            echo $output;
            die;
        }
    }
}
CheckFlood();
http_response_code(404);
?>
