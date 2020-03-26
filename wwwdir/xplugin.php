<?php
/*Rev:26.09.18r0*/

require 'init.php';
header('Content-Type: application/json');
$B626d33e939f0dd9b6a026aa3f8c87a3 = $_SERVER['REMOTE_ADDR'];
$user_agent = trim($_SERVER['HTTP_USER_AGENT']);
if (!empty(ipTV_lib::$request['action']) && ipTV_lib::$request['action'] == 'gen_mac' && !empty(ipTV_lib::$request['pversion'])) {
    if (ipTV_lib::$request['pversion'] != '0.0.1') {
        echo json_encode(strtoupper(implode(':', str_split(substr(md5(mt_rand()), 0, 12), 2))));
    }
    die;
}
if (!empty(ipTV_lib::$request['action']) && ipTV_lib::$request['action'] == 'auth') {
    $mac = isset(ipTV_lib::$request['mac']) ? htmlentities(ipTV_lib::$request['mac']) : '';
    $A772ae3d339199a2063a8114463187e9 = isset(ipTV_lib::$request['mmac']) ? htmlentities(ipTV_lib::$request['mmac']) : '';
    $fe0750f7aa30941e1e4cdf60bf6a717c = isset(ipTV_lib::$request['ip']) ? htmlentities(ipTV_lib::$request['ip']) : '';
    $Ad76f953cd176710f1445b66d793955d = isset(ipTV_lib::$request['version']) ? htmlentities(ipTV_lib::$request['version']) : '';
    $e001aedbc6e64693ba72fd0337a8fa76 = isset(ipTV_lib::$request['type']) ? htmlentities(ipTV_lib::$request['type']) : '';
    $db129c7b99cf0960c74f2c766ce8df9a = isset(ipTV_lib::$request['pversion']) ? htmlentities(ipTV_lib::$request['pversion']) : '';
    $A8dcbd7c47482ea0777db7c412f03a4d = isset(ipTV_lib::$request['lversion']) ? base64_decode(ipTV_lib::$request['lversion']) : '';
    $b37f0a028a0cad24cae4c9e61119f8de = !empty(ipTV_lib::$request['dn']) ? htmlentities(ipTV_lib::$request['dn']) : '-';
    $b5014e12f754ad55b57d1a8e17efe7b0 = !empty(ipTV_lib::$request['cmac']) ? htmlentities(strtoupper(ipTV_lib::$request['cmac'])) : '';
    $f5cab1816ec764ef073063a4c9596cb6 = array();
    if ($de0eb4ea8ae0aa5b5b8864529380cf22 = ipTV_streaming::A2999eeDbe1Ff2D9cE52EF5311680cD4(array('device_id' => null, 'mac' => strtoupper($mac)))) {
        if ($de0eb4ea8ae0aa5b5b8864529380cf22['enigma2']['lock_device'] == 1) {
            if (!empty($de0eb4ea8ae0aa5b5b8864529380cf22['enigma2']['modem_mac']) && $de0eb4ea8ae0aa5b5b8864529380cf22['enigma2']['modem_mac'] !== $A772ae3d339199a2063a8114463187e9) {
                die(json_encode(array()));
            }
        }
        $Aacb752351b5de80f12830c2026b757e = strtoupper(md5(uniqid(rand(), true)));
        $Fb012ef0b8c84139cf5b45c26d1a4d54 = mt_rand(60, 70);
        $ipTV_db->query('UPDATE `enigma2_devices` SET `original_mac` = \'%s\',`dns` = \'%s\',`key_auth` = \'%s\',`lversion` = \'%s\',`watchdog_timeout` = \'%d\',`modem_mac` = \'%s\',`local_ip` = \'%s\',`public_ip` = \'%s\',`enigma_version` = \'%s\',`cpu` = \'%s\',`version` = \'%s\',`token` = \'%s\',`last_updated` = \'%d\' WHERE `device_id` = \'%d\'', $b5014e12f754ad55b57d1a8e17efe7b0, $b37f0a028a0cad24cae4c9e61119f8de, $user_agent, $A8dcbd7c47482ea0777db7c412f03a4d, $Fb012ef0b8c84139cf5b45c26d1a4d54, $A772ae3d339199a2063a8114463187e9, $fe0750f7aa30941e1e4cdf60bf6a717c, $B626d33e939f0dd9b6a026aa3f8c87a3, $Ad76f953cd176710f1445b66d793955d, $e001aedbc6e64693ba72fd0337a8fa76, $db129c7b99cf0960c74f2c766ce8df9a, $Aacb752351b5de80f12830c2026b757e, time(), $de0eb4ea8ae0aa5b5b8864529380cf22['enigma2']['device_id']);
        $f5cab1816ec764ef073063a4c9596cb6['details'] = array();
        $f5cab1816ec764ef073063a4c9596cb6['details']['token'] = $Aacb752351b5de80f12830c2026b757e;
        $f5cab1816ec764ef073063a4c9596cb6['details']['username'] = $de0eb4ea8ae0aa5b5b8864529380cf22['user_info']['username'];
        $f5cab1816ec764ef073063a4c9596cb6['details']['password'] = $de0eb4ea8ae0aa5b5b8864529380cf22['user_info']['password'];
        $f5cab1816ec764ef073063a4c9596cb6['details']['watchdog_seconds'] = $Fb012ef0b8c84139cf5b45c26d1a4d54;
    }
    echo json_encode($f5cab1816ec764ef073063a4c9596cb6);
    die;
}
if (empty(ipTV_lib::$request['token'])) {
    die(json_encode(array('valid' => false)));
}
$Aacb752351b5de80f12830c2026b757e = ipTV_lib::$request['token'];
$ipTV_db->query('SELECT * FROM enigma2_devices WHERE `token` = \'%s\' AND `public_ip` = \'%s\' AND `key_auth` = \'%s\' LIMIT 1', $Aacb752351b5de80f12830c2026b757e, $B626d33e939f0dd9b6a026aa3f8c87a3, $user_agent);
if ($ipTV_db->num_rows() <= 0) {
    die(json_encode(array('valid' => false)));
}
$ef2191c41d898dd4d2c297b9115d985d = $ipTV_db->get_row();
if (time() - $ef2191c41d898dd4d2c297b9115d985d['last_updated'] > $ef2191c41d898dd4d2c297b9115d985d['watchdog_timeout'] + 20) {
    die(json_encode(array('valid' => false)));
}
$Efbabdfbd20db2470efbf8a713287c36 = isset(ipTV_lib::$request['page']) ? ipTV_lib::$request['page'] : '';
if (!empty($Efbabdfbd20db2470efbf8a713287c36)) {
    if ($Efbabdfbd20db2470efbf8a713287c36 == 'file') {
        if (!empty($_FILES['f']['name'])) {
            if ($_FILES['f']['error'] == 0) {
                $Da45e9a4a377f8bd28389cf977565923 = strtolower($_FILES['f']['tmp_name']);
                $type = ipTV_lib::$request['t'];
                switch ($type) {
                    case 'screen':
                        move_uploaded_file($_FILES['f']['tmp_name'], ENIGMA2_PLUGIN_DIR . $ef2191c41d898dd4d2c297b9115d985d['device_id'] . '_screen_' . time() . '_' . uniqid() . '.jpg');
                        break;
                }
            }
        }
    } else {
        //Ef9c0fd87485ae4e7ac1168d3ef632c3:
        $ipTV_db->query('UPDATE `enigma2_devices` SET `last_updated` = \'%d\',`rc` = \'%d\' WHERE `device_id` = \'%d\'', time(), ipTV_lib::$request['rc'], $ef2191c41d898dd4d2c297b9115d985d['device_id']);
        $ipTV_db->query('SELECT * FROM `enigma2_actions` WHERE `device_id` = \'%d\'', $ef2191c41d898dd4d2c297b9115d985d['device_id']);
        $result = array();
        if ($ipTV_db->num_rows() > 0) {
            $Ce7729bc93110c2030dc45bb29c9f93f = $ipTV_db->get_row();
            if ('message' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['message'] = array();
                $result['message']['title'] = $Ce7729bc93110c2030dc45bb29c9f93f['command2'];
                $result['message']['message'] = $Ce7729bc93110c2030dc45bb29c9f93f['command'];
            }
            if ('ssh' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['ssh'] = $Ce7729bc93110c2030dc45bb29c9f93f['command'];
            }
            if ('screen' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['screen'] = '1';
            }
            if ('reboot_gui' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['reboot_gui'] = 1;
            }
            if ('reboot' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['reboot'] = 1;
            }
            if ('update' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['update'] = $Ce7729bc93110c2030dc45bb29c9f93f['command'];
            }
            if ('block_ssh' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['block_ssh'] = (int) $Ce7729bc93110c2030dc45bb29c9f93f['type'];
            }
            if ('block_telnet' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['block_telnet'] = (int) $Ce7729bc93110c2030dc45bb29c9f93f['type'];
            }
            if ('block_ftp' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['block_ftp'] = (int) $Ce7729bc93110c2030dc45bb29c9f93f['type'];
            }
            if ('block_all' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['block_all'] = (int) $Ce7729bc93110c2030dc45bb29c9f93f['type'];
            }
            if ('block_plugin' == $Ce7729bc93110c2030dc45bb29c9f93f['key']) {
                $result['block_plugin'] = (int) $Ce7729bc93110c2030dc45bb29c9f93f['type'];
            }
            $ipTV_db->query('DELETE FROM enigma2_actions where id = \'%d\'', $Ce7729bc93110c2030dc45bb29c9f93f['id']);
        }
        die(json_encode(array('valid' => true, 'data' => $result)));
        //goto a5360c7a142d50608106d75d4ce0aa19;
    }
}
?>
