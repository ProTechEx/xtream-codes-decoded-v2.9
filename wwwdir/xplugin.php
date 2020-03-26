<?php
/*Rev:26.09.18r0*/

require 'init.php';
header('Content-Type: application/json');
$remote_addr = $_SERVER['REMOTE_ADDR'];
$user_agent = trim($_SERVER['HTTP_USER_AGENT']);
if (!empty(ipTV_lib::$request['action']) && ipTV_lib::$request['action'] == 'gen_mac' && !empty(ipTV_lib::$request['pversion'])) {
    if (ipTV_lib::$request['pversion'] != '0.0.1') {
        echo json_encode(strtoupper(implode(':', str_split(substr(md5(mt_rand()), 0, 12), 2))));
    }
    die;
}
if (!empty(ipTV_lib::$request['action']) && ipTV_lib::$request['action'] == 'auth') {
    $mac = isset(ipTV_lib::$request['mac']) ? htmlentities(ipTV_lib::$request['mac']) : '';
    $mmac = isset(ipTV_lib::$request['mmac']) ? htmlentities(ipTV_lib::$request['mmac']) : '';
    $ip = isset(ipTV_lib::$request['ip']) ? htmlentities(ipTV_lib::$request['ip']) : '';
    $version = isset(ipTV_lib::$request['version']) ? htmlentities(ipTV_lib::$request['version']) : '';
    $type = isset(ipTV_lib::$request['type']) ? htmlentities(ipTV_lib::$request['type']) : '';
    $pversion = isset(ipTV_lib::$request['pversion']) ? htmlentities(ipTV_lib::$request['pversion']) : '';
    $lversion = isset(ipTV_lib::$request['lversion']) ? base64_decode(ipTV_lib::$request['lversion']) : '';
    $dn = !empty(ipTV_lib::$request['dn']) ? htmlentities(ipTV_lib::$request['dn']) : '-';
    $cmac = !empty(ipTV_lib::$request['cmac']) ? htmlentities(strtoupper(ipTV_lib::$request['cmac'])) : '';
    $json = array();
    if ($enigma_devices = ipTV_streaming::EnigmaDevices(array('device_id' => null, 'mac' => strtoupper($mac)))) {
        if ($enigma_devices['enigma2']['lock_device'] == 1) {
            if (!empty($enigma_devices['enigma2']['modem_mac']) && $enigma_devices['enigma2']['modem_mac'] !== $mmac) {
                die(json_encode(array()));
            }
        }
        $token = strtoupper(md5(uniqid(rand(), true)));
        $seconds = mt_rand(60, 70);
        $ipTV_db->query('UPDATE `enigma2_devices` SET `original_mac` = \'%s\',`dns` = \'%s\',`key_auth` = \'%s\',`lversion` = \'%s\',`watchdog_timeout` = \'%d\',`modem_mac` = \'%s\',`local_ip` = \'%s\',`public_ip` = \'%s\',`enigma_version` = \'%s\',`cpu` = \'%s\',`version` = \'%s\',`token` = \'%s\',`last_updated` = \'%d\' WHERE `device_id` = \'%d\'', $cmac, $dn, $user_agent, $lversion, $seconds, $mmac, $ip, $remote_addr, $version, $type, $pversion, $token, time(), $enigma_devices['enigma2']['device_id']);
        $json['details'] = array();
        $json['details']['token'] = $token;
        $json['details']['username'] = $enigma_devices['user_info']['username'];
        $json['details']['password'] = $enigma_devices['user_info']['password'];
        $json['details']['watchdog_seconds'] = $seconds;
    }
    echo json_encode($json);
    die;
}
if (empty(ipTV_lib::$request['token'])) {
    die(json_encode(array('valid' => false)));
}
$token = ipTV_lib::$request['token'];
$ipTV_db->query('SELECT * FROM enigma2_devices WHERE `token` = \'%s\' AND `public_ip` = \'%s\' AND `key_auth` = \'%s\' LIMIT 1', $token, $remote_addr, $user_agent);
if ($ipTV_db->num_rows() <= 0) {
    die(json_encode(array('valid' => false)));
}
$device_info = $ipTV_db->get_row();
if (time() - $device_info['last_updated'] > $device_info['watchdog_timeout'] + 20) {
    die(json_encode(array('valid' => false)));
}
$page = isset(ipTV_lib::$request['page']) ? ipTV_lib::$request['page'] : '';
if (!empty($page)) {
    if ($page == 'file') {
        if (!empty($_FILES['f']['name'])) {
            if ($_FILES['f']['error'] == 0) {
                $tmp_name = strtolower($_FILES['f']['tmp_name']);
                $type = ipTV_lib::$request['t'];
                switch ($type) {
                    case 'screen':
                        move_uploaded_file($_FILES['f']['tmp_name'], ENIGMA2_PLUGIN_DIR . $device_info['device_id'] . '_screen_' . time() . '_' . uniqid() . '.jpg');
                        break;
                }
            }
        }
    } else {
        $ipTV_db->query('UPDATE `enigma2_devices` SET `last_updated` = \'%d\',`rc` = \'%d\' WHERE `device_id` = \'%d\'', time(), ipTV_lib::$request['rc'], $device_info['device_id']);
        $ipTV_db->query('SELECT * FROM `enigma2_actions` WHERE `device_id` = \'%d\'', $device_info['device_id']);
        $result = array();
        if ($ipTV_db->num_rows() > 0) {
            $device = $ipTV_db->get_row();
            if ('message' == $device['key']) {
                $result['message'] = array();
                $result['message']['title'] = $device['command2'];
                $result['message']['message'] = $device['command'];
            }
            if ('ssh' == $device['key']) {
                $result['ssh'] = $device['command'];
            }
            if ('screen' == $device['key']) {
                $result['screen'] = '1';
            }
            if ('reboot_gui' == $device['key']) {
                $result['reboot_gui'] = 1;
            }
            if ('reboot' == $device['key']) {
                $result['reboot'] = 1;
            }
            if ('update' == $device['key']) {
                $result['update'] = $device['command'];
            }
            if ('block_ssh' == $device['key']) {
                $result['block_ssh'] = (int) $device['type'];
            }
            if ('block_telnet' == $device['key']) {
                $result['block_telnet'] = (int) $device['type'];
            }
            if ('block_ftp' == $device['key']) {
                $result['block_ftp'] = (int) $device['type'];
            }
            if ('block_all' == $device['key']) {
                $result['block_all'] = (int) $device['type'];
            }
            if ('block_plugin' == $device['key']) {
                $result['block_plugin'] = (int) $device['type'];
            }
            $ipTV_db->query('DELETE FROM enigma2_actions where id = \'%d\'', $device['id']);
        }
        die(json_encode(array('valid' => true, 'data' => $result)));
    }
}
?>
