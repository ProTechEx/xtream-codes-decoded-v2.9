<?php
/*Rev:26.09.18r0*/

set_time_limit(0);
require 'init.php';
$user_ip = $_SERVER['REMOTE_ADDR'];
if (!in_array($user_ip, ipTV_streaming::getAllowedIPsAdmin()) && !in_array($user_ip, ipTV_lib::$settings['api_ips'])) {
    die(json_encode(array('result' => false, 'IP FORBIDDEN')));
}
if (!empty(ipTV_lib::$settings['api_pass']) && ipTV_lib::$request['api_pass'] != ipTV_lib::$settings['api_pass']) {
    die(json_encode(array('result' => false, 'KEY WRONG')));
}
$b4af8b82d0e004d138b6f62947d7a1fa = !empty(ipTV_lib::$request['action']) ? ipTV_lib::$request['action'] : '';
$E6ff6085a41cbca3609766e3f9a666ee = !empty(ipTV_lib::$request['sub']) ? ipTV_lib::$request['sub'] : '';
switch ($b4af8b82d0e004d138b6f62947d7a1fa) {
    case 'server':
        switch ($E6ff6085a41cbca3609766e3f9a666ee) {
            case 'list':
                $output = array();
                foreach (ipTV_lib::$StreamingServers as $server_id => $C3af9fee694e49882d2d0c32f538efc8) {
                    $output[] = array('id' => $server_id, 'server_name' => $C3af9fee694e49882d2d0c32f538efc8['server_name'], 'online' => $C3af9fee694e49882d2d0c32f538efc8['server_online'], 'info' => json_decode($C3af9fee694e49882d2d0c32f538efc8['server_hardware'], true));
                    //c657bfc83013e3803533d63a251e5086:
                }
                echo json_encode($output);
                break;
        }
        break;
    case 'vod':
        $B5dac75572776cad02b4f375a2781a87 = array_map('intval', ipTV_lib::$request['stream_ids']);
        switch ($E6ff6085a41cbca3609766e3f9a666ee) {
            case 'start':
            case 'stop':
                $f9b9c9baaec5b82b03b15c6eb07ec8f9 = empty(ipTV_lib::$request['servers']) ? array_keys(ipTV_lib::$StreamingServers) : array_map('intval', ipTV_lib::$request['servers']);
                foreach ($f9b9c9baaec5b82b03b15c6eb07ec8f9 as $server_id) {
                    $B13e3f304ca1f14e137f209a5138ea10[$server_id] = array('url' => ipTV_lib::$StreamingServers[$server_id]['api_url_ip'] . '&action=vod', 'postdata' => array('function' => $E6ff6085a41cbca3609766e3f9a666ee, 'stream_ids' => $B5dac75572776cad02b4f375a2781a87));
                    //df9a384a8b1f6f6f6ae97662b1f5d706:
                }
                ipTV_lib::D0124AFE61D44214B63588b31303A8c4($B13e3f304ca1f14e137f209a5138ea10);
                echo json_encode(array('result' => true));
                die;
                break;
        }
        break;
    case 'stream':
        switch ($E6ff6085a41cbca3609766e3f9a666ee) {
            case 'start':
            case 'stop':
                $B5dac75572776cad02b4f375a2781a87 = array_map('intval', ipTV_lib::$request['stream_ids']);
                $f9b9c9baaec5b82b03b15c6eb07ec8f9 = empty(ipTV_lib::$request['servers']) ? array_keys(ipTV_lib::$StreamingServers) : array_map('intval', ipTV_lib::$request['servers']);
                foreach ($f9b9c9baaec5b82b03b15c6eb07ec8f9 as $server_id) {
                    $B13e3f304ca1f14e137f209a5138ea10[$server_id] = array('url' => ipTV_lib::$StreamingServers[$server_id]['api_url_ip'] . '&action=stream', 'postdata' => array('function' => $E6ff6085a41cbca3609766e3f9a666ee, 'stream_ids' => $B5dac75572776cad02b4f375a2781a87));
                    //a7f49c96cd32b78607c69a117274e36f:
                }
                ipTV_lib::d0124afE61D44214B63588b31303A8C4($B13e3f304ca1f14e137f209a5138ea10);
                echo json_encode(array('result' => true));
                die;
                break;
            case 'list':
                $output = array();
                $ipTV_db->query('SELECT id,stream_display_name FROM `streams` WHERE type <> 2');
                foreach ($ipTV_db->get_rows() as $row) {
                    $output[] = array('id' => $row['id'], 'stream_name' => $row['stream_display_name']);
                    //E283b1770ca1413a7db2234f55ee3b96:
                }
                echo json_encode($output);
                break;
            case 'offline':
                $ipTV_db->query('SELECT t1.stream_status,t1.server_id,t1.stream_id 
                                  FROM `streams_sys` t1
                                  INNER JOIN `streams` t2 ON t2.id = t1.stream_id AND t2.type <> 2
                                  WHERE t1.stream_status = 1');
                $D465fc5085f41251c6fa7c77b8333b0f = $ipTV_db->get_rows(true, 'stream_id', false, 'server_id');
                $output = array();
                foreach ($D465fc5085f41251c6fa7c77b8333b0f as $stream_id => $f9b9c9baaec5b82b03b15c6eb07ec8f9) {
                    $output[$stream_id] = array_keys($f9b9c9baaec5b82b03b15c6eb07ec8f9);
                    //dc36140b17e2214cd0291f81609d6855:
                }
                echo json_encode($output);
                break;
            case 'online':
                $ipTV_db->query('SELECT t1.stream_status,t1.server_id,t1.stream_id 
                                  FROM `streams_sys` t1
                                  INNER JOIN `streams` t2 ON t2.id = t1.stream_id AND t2.type <> 2
                                  WHERE t1.pid > 0 AND t1.stream_status = 0');
                $D465fc5085f41251c6fa7c77b8333b0f = $ipTV_db->get_rows(true, 'stream_id', false, 'server_id');
                $output = array();
                foreach ($D465fc5085f41251c6fa7c77b8333b0f as $stream_id => $f9b9c9baaec5b82b03b15c6eb07ec8f9) {
                    $output[$stream_id] = array_keys($f9b9c9baaec5b82b03b15c6eb07ec8f9);
                    //b95fa06e61bc3c48ce56f6bf18da484c:
                }
                echo json_encode($output);
                break;
        }
        break;
    case 'stb':
        switch ($E6ff6085a41cbca3609766e3f9a666ee) {
            case 'info':
                if (!empty(ipTV_lib::$request['mac'])) {
                    $bad0c96fedbc6eccfe927016a4dc3cd6 = ipTV_lib::$request['mac'];
                    $user_info = ipTV_streaming::f2cBD6b6F59558B819C0CFF8c3b2Ef2c(false, $bad0c96fedbc6eccfe927016a4dc3cd6, true, false, true);
                    if (!empty($user_info)) {
                        echo json_encode(array_merge(array('result' => true), $user_info));
                    } else {
                        echo json_encode(array('result' => false, 'error' => 'NOT EXISTS'));
                    }
                } else {
                    echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR (mac)'));
                }
                break;
            case 'edit':
                if (!empty(ipTV_lib::$request['mac'])) {
                    $bad0c96fedbc6eccfe927016a4dc3cd6 = ipTV_lib::$request['mac'];
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f = empty(ipTV_lib::$request['user_data']) ? array() : ipTV_lib::$request['user_data'];
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['is_mag'] = 1;
                    $query = FBAac025084A44F7876230Ff53A6137F($Bf4bb0ad11102aaccbf77b6cdc1fd66f);
                    if ($ipTV_db->query("UPDATE `users` SET {$query} WHERE id = ( SELECT user_id FROM mag_devices WHERE `mac` = '%s' )", base64_encode(strtoupper($bad0c96fedbc6eccfe927016a4dc3cd6)))) {
                        if ($ipTV_db->affected_rows() > 0) {
                            echo json_encode(array('result' => true));
                            $ipTV_db->query('INSERT INTO `reg_userlog` ( `owner`, `username`, `password`, `date`, `type` ) VALUES( \'%s\', \'%s\', \'%s\', \'%s\', \'%s\' )', "SYSTEM API[{$user_ip}]", $bad0c96fedbc6eccfe927016a4dc3cd6, '-', time(), '[API->Edit MAG Device]');
                        } else {
                            echo json_encode(array('result' => false));
                        }
                    } else {
                        echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR'));
                    }
                } else {
                    echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR (user/pass)'));
                }
                break;
            case 'create':
                $Bf4bb0ad11102aaccbf77b6cdc1fd66f = empty(ipTV_lib::$request['user_data']) ? array() : ipTV_lib::$request['user_data'];
                if (!empty($Bf4bb0ad11102aaccbf77b6cdc1fd66f['mac'])) {
                    $fb226b0ab56e366f44da9cf9ee107fff = array(1, 2, 3);
                    $bad0c96fedbc6eccfe927016a4dc3cd6 = base64_encode(strtoupper($Bf4bb0ad11102aaccbf77b6cdc1fd66f['mac']));
                    unset($Bf4bb0ad11102aaccbf77b6cdc1fd66f['mac']);
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['username'] = ipTV_lib::e5182e3aFA58aC7EC5D69d56B28819cd(10);
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['password'] = ipTV_lib::E5182e3afA58AC7Ec5D69D56B28819cd(10);
                    if (!array_key_exists('allowed_ips', $Bf4bb0ad11102aaccbf77b6cdc1fd66f) || !Ef9fcEFFa62DB6eCc4c8a628b9B5A9aF($Bf4bb0ad11102aaccbf77b6cdc1fd66f['allowed_ips'])) {
                        $Bf4bb0ad11102aaccbf77b6cdc1fd66f['allowed_ips'] = json_encode(array());
                    }
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['allowed_ua'] = json_encode(array());
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['created_at'] = time();
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['created_by'] = 0;
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['exp_date'] = empty($Bf4bb0ad11102aaccbf77b6cdc1fd66f['exp_date']) ? null : intval($Bf4bb0ad11102aaccbf77b6cdc1fd66f['exp_date']);
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['bouquet'] = empty($Bf4bb0ad11102aaccbf77b6cdc1fd66f['bouquet']) || !EF9fCefFa62DB6ECc4c8a628B9B5A9aF($Bf4bb0ad11102aaccbf77b6cdc1fd66f['bouquet']) ? array() : array_map('intval', json_decode($Bf4bb0ad11102aaccbf77b6cdc1fd66f['bouquet'], true));
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['is_mag'] = 1;
                    if (array_key_exists('mac', $Bf4bb0ad11102aaccbf77b6cdc1fd66f)) {
                        unset($Bf4bb0ad11102aaccbf77b6cdc1fd66f['mac']);
                    }
                    if (array_key_exists('output_formats', $Bf4bb0ad11102aaccbf77b6cdc1fd66f)) {
                        unset($Bf4bb0ad11102aaccbf77b6cdc1fd66f['output_formats']);
                    }
                    if (!CE15043404aa3e950fc9C9dd8bc0325a('mag_devices', 'mac', $bad0c96fedbc6eccfe927016a4dc3cd6)) {
                        $query = b484C4Ff0e3EE69B9d98B92884B88c0F($Bf4bb0ad11102aaccbf77b6cdc1fd66f);
                        if ($ipTV_db->simple_query("INSERT INTO `users` {$query}")) {
                            if ($ipTV_db->affected_rows() > 0) {
                                $E38668abaa324e464e266fb7b7e784b1 = $ipTV_db->last_insert_id();
                                foreach ($fb226b0ab56e366f44da9cf9ee107fff as $b1f84f020035bf724cdc2f6d05ee33c3) {
                                    $ipTV_db->query('INSERT INTO `user_output` ( `user_id`, `access_output_id` )VALUES( \'%d\', \'%d\' )', $E38668abaa324e464e266fb7b7e784b1, $b1f84f020035bf724cdc2f6d05ee33c3);
                                    //D65ea973d5f15f8d2dcfd5c4a658493f:
                                }
                                $ipTV_db->query('INSERT INTO `mag_devices` ( `user_id`, `mac`, `created` )VALUES( \'%d\', \'%s\', \'%d\' )', $E38668abaa324e464e266fb7b7e784b1, $bad0c96fedbc6eccfe927016a4dc3cd6, time());
                                echo json_encode(array('result' => true));
                                $ipTV_db->query('INSERT INTO `reg_userlog` ( `owner`, `username`, `password`, `date`, `type` )VALUES( \'%s\', \'%s\', \'%s\', \'%s\', \'%s\' )', "SYSTEM API[{$user_ip}]", base64_decode($bad0c96fedbc6eccfe927016a4dc3cd6), '-', time(), '[API->New MAG Device]');
                            } else {
                                echo json_encode(array('result' => false));
                            }
                        } else {
                            echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR'));
                        }
                    } else {
                        echo json_encode(array('result' => false, 'error' => 'EXISTS'));
                    }
                } else {
                    echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR (mac)'));
                }
                break;
        }
        break;
    case 'user':
        switch ($E6ff6085a41cbca3609766e3f9a666ee) {
            case 'info':
                if (!empty(ipTV_lib::$request['username']) && !empty(ipTV_lib::$request['password'])) {
                    $username = ipTV_lib::$request['username'];
                    $password = ipTV_lib::$request['password'];
                    $user_info = ipTV_streaming::GetUserInfo(false, $username, $password, true, false, true);
                    if (!empty($user_info)) {
                        echo json_encode(array('result' => true, 'user_info' => $user_info));
                    } else {
                        echo json_encode(array('result' => false, 'error' => 'NOT EXISTS'));
                    }
                } else {
                    echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR (user/pass)'));
                }
                break;
            case 'edit':
                if (!empty(ipTV_lib::$request['username']) && !empty(ipTV_lib::$request['password'])) {
                    $username = ipTV_lib::$request['username'];
                    $password = ipTV_lib::$request['password'];
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f = empty(ipTV_lib::$request['user_data']) ? array() : ipTV_lib::$request['user_data'];
                    $ipTV_db->query('SELECT * FROM `users` WHERE `username` = \'%s\' and `password` = \'%s\'', $username, $password);
                    if ($ipTV_db->num_rows() > 0) {
                        $query = fBaaC025084a44F7876230Ff53A6137F($Bf4bb0ad11102aaccbf77b6cdc1fd66f);
                        if ($ipTV_db->query("UPDATE `users` SET {$query} WHERE `username` = '%s' and `password` = '%s'", $username, $password)) {
                            echo json_encode(array('result' => true));
                            $ipTV_db->query('INSERT INTO `reg_userlog` ( `owner`, `username`, `password`, `date`, `type` )VALUES( \'%s\', \'%s\', \'%s\', \'%s\', \'%s\' )', "SYSTEM API[{$user_ip}]", $username, $password, time(), '[API->Edit Line]');
                        } else {
                            echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR'));
                        }
                    } else {
                        echo json_encode(array('result' => false, 'error' => 'NOT EXISTS'));
                    }
                } else {
                    echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR (user/pass)'));
                }
                break;
            case 'create':
                $fb226b0ab56e366f44da9cf9ee107fff = array(1, 2, 3);
                $Bf4bb0ad11102aaccbf77b6cdc1fd66f = empty(ipTV_lib::$request['user_data']) ? array() : ipTV_lib::$request['user_data'];
                if (!array_key_exists('username', $Bf4bb0ad11102aaccbf77b6cdc1fd66f)) {
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['username'] = ipTV_lib::E5182E3afa58AC7ec5D69d56B28819Cd(10);
                }
                if (!array_key_exists('password', $Bf4bb0ad11102aaccbf77b6cdc1fd66f)) {
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['password'] = ipTV_lib::E5182e3AFa58ac7Ec5D69d56B28819CD(10);
                }
                if (!array_key_exists('allowed_ips', $Bf4bb0ad11102aaccbf77b6cdc1fd66f) || !ef9fCeffa62dB6ECC4C8A628B9B5a9aF($Bf4bb0ad11102aaccbf77b6cdc1fd66f['allowed_ips'])) {
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['allowed_ips'] = json_encode(array());
                }
                if (!array_key_exists('allowed_ua', $Bf4bb0ad11102aaccbf77b6cdc1fd66f) || !eF9FCEfFa62Db6ecC4C8A628b9b5A9aF($Bf4bb0ad11102aaccbf77b6cdc1fd66f['allowed_ua'])) {
                    $Bf4bb0ad11102aaccbf77b6cdc1fd66f['allowed_ua'] = json_encode(array());
                }
                $Bf4bb0ad11102aaccbf77b6cdc1fd66f['created_at'] = time();
                $Bf4bb0ad11102aaccbf77b6cdc1fd66f['created_by'] = 0;
                $Bf4bb0ad11102aaccbf77b6cdc1fd66f['exp_date'] = empty($Bf4bb0ad11102aaccbf77b6cdc1fd66f['exp_date']) ? null : intval($Bf4bb0ad11102aaccbf77b6cdc1fd66f['exp_date']);
                $Bf4bb0ad11102aaccbf77b6cdc1fd66f['bouquet'] = empty($Bf4bb0ad11102aaccbf77b6cdc1fd66f['bouquet']) || !EF9FcEFFA62dB6Ecc4C8a628b9b5A9af($Bf4bb0ad11102aaccbf77b6cdc1fd66f['bouquet']) ? array() : array_map('intval', json_decode($Bf4bb0ad11102aaccbf77b6cdc1fd66f['bouquet'], true));
                $fb226b0ab56e366f44da9cf9ee107fff = empty($Bf4bb0ad11102aaccbf77b6cdc1fd66f['output_formats']) || !ef9FCefFa62DB6ECC4C8A628B9B5A9AF($Bf4bb0ad11102aaccbf77b6cdc1fd66f['output_formats']) ? $fb226b0ab56e366f44da9cf9ee107fff : array_map('intval', $Bf4bb0ad11102aaccbf77b6cdc1fd66f['output_formats']);
                if (array_key_exists('output_formats', $Bf4bb0ad11102aaccbf77b6cdc1fd66f)) {
                    unset($Bf4bb0ad11102aaccbf77b6cdc1fd66f['output_formats']);
                }
                $ipTV_db->query('SELECT id FROM `users` WHERE `username` = \'%s\' AND `password` = \'%s\' LIMIT 1', $Bf4bb0ad11102aaccbf77b6cdc1fd66f['username'], $Bf4bb0ad11102aaccbf77b6cdc1fd66f['password']);
                if ($ipTV_db->num_rows() == 0) {
                    $query = b484C4FF0E3eE69b9D98b92884B88c0F($Bf4bb0ad11102aaccbf77b6cdc1fd66f);
                    if ($ipTV_db->simple_query("INSERT INTO `users` {$query}")) {
                        if ($ipTV_db->affected_rows() > 0) {
                            $E38668abaa324e464e266fb7b7e784b1 = $ipTV_db->last_insert_id();
                            foreach ($fb226b0ab56e366f44da9cf9ee107fff as $b1f84f020035bf724cdc2f6d05ee33c3) {
                                $ipTV_db->query('INSERT INTO `user_output` ( `user_id`, `access_output_id` ) VALUES( \'%d\', \'%d\' )', $E38668abaa324e464e266fb7b7e784b1, $b1f84f020035bf724cdc2f6d05ee33c3);
                                //deafaac83305037a219641703427257d:
                            }
                            echo json_encode(array('result' => true, 'created_id' => $E38668abaa324e464e266fb7b7e784b1, 'username' => $Bf4bb0ad11102aaccbf77b6cdc1fd66f['username'], 'password' => $Bf4bb0ad11102aaccbf77b6cdc1fd66f['password']));
                            $ipTV_db->query('INSERT INTO `reg_userlog` ( `owner`, `username`, `password`, `date`, `type` )VALUES( \'%s\', \'%s\', \'%s\', \'%s\', \'%s\' )', "SYSTEM API[{$user_ip}]", $Bf4bb0ad11102aaccbf77b6cdc1fd66f['username'], $Bf4bb0ad11102aaccbf77b6cdc1fd66f['password'], time(), '[API->New Line]');
                        } else {
                            echo json_encode(array('result' => false));
                        }
                    } else {
                        echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR'));
                    }
                } else {
                    echo json_encode(array('result' => false, 'error' => 'EXISTS'));
                }
                break;
        }
        break;
    case 'reg_user':
        switch ($E6ff6085a41cbca3609766e3f9a666ee) {
            case 'list':
                $ipTV_db->query('SELECT id,username,credits,group_id,group_name,last_login,date_registered,email,ip,status
                            FROM `reg_users` t1
                        INNER JOIN `member_groups` t2 ON t1.member_group_id = t2.group_id');
                $Af301a166badb15e0b00336d72fb9497 = $ipTV_db->get_rows();
                echo json_encode($Af301a166badb15e0b00336d72fb9497);
                break;
            case 'credits':
                if (!empty(ipTV_lib::$request['amount']) && (!empty(ipTV_lib::$request['id']) || !empty(ipTV_lib::$request['username']))) {
                    $Cadd766037a4c84044843f30dd506e37 = sprintf('%.2f', ipTV_lib::$request['amount']);
                    if (!empty(ipTV_lib::$request['id'])) {
                        $ipTV_db->query('SELECT * FROM reg_users WHERE `id` = \'%d\'', ipTV_lib::$request['id']);
                    } else {
                        $ipTV_db->query('SELECT * FROM reg_users WHERE `username` = \'%s\'', ipTV_lib::$request['username']);
                    }
                    if ($ipTV_db->num_rows()) {
                        $Eb809884ee4b7eb427d7a2ae5a5fb355 = $ipTV_db->get_row();
                        $A6f4ecc798bcb285eee6efb4467c6708 = $Cadd766037a4c84044843f30dd506e37 + $Eb809884ee4b7eb427d7a2ae5a5fb355['credits'];
                        if ($A6f4ecc798bcb285eee6efb4467c6708 < 0) {
                            echo json_encode(array('result' => true, 'error' => 'NOT ENOUGH CREDITS'));
                        } else {
                            $ipTV_db->query('UPDATE reg_users SET `credits` = \'%.2f\' WHERE `id` = \'%d\'', $A6f4ecc798bcb285eee6efb4467c6708, $Eb809884ee4b7eb427d7a2ae5a5fb355['id']);
                            echo json_encode(array('result' => true));
                            $ipTV_db->query('INSERT INTO `reg_userlog` ( `owner`, `username`, `password`, `date`, `type` )VALUES( \'%s\', \'%s\', \'%s\', \'%s\', \'%s\' )', "SYSTEM API[{$user_ip}]", $Bf4bb0ad11102aaccbf77b6cdc1fd66f['username'], $Bf4bb0ad11102aaccbf77b6cdc1fd66f['password'], time(), "[API->ADD Credits {$Cadd766037a4c84044843f30dd506e37}]");
                        }
                    } else {
                        echo json_encode(array('result' => false, 'error' => 'NOT EXISTS'));
                    }
                } else {
                    echo json_encode(array('result' => false, 'error' => 'PARAMETER ERROR (amount & id||username)'));
                }
                break;
        }
        break;
}
function fbAAC025084A44f7876230ff53A6137f($d76067cf9572f7a6691c85c12faf2a29)
{
    global $ipTV_db;
    $query = '';
    foreach ($d76067cf9572f7a6691c85c12faf2a29 as $bca37bc3b9c255b1666da6076ce9aa30 => $a1daec950dd361ae639ad3a57dc018c0) {
        $bca37bc3b9c255b1666da6076ce9aa30 = preg_replace('/[^a-zA-Z0-9\\_]+/', '', $bca37bc3b9c255b1666da6076ce9aa30);
        if (is_array($a1daec950dd361ae639ad3a57dc018c0)) {  
            $query .= "`{$bca37bc3b9c255b1666da6076ce9aa30}` = '" . $ipTV_db->escape(json_encode($a1daec950dd361ae639ad3a57dc018c0)) . '\',';
        }
        else if (is_null($a1daec950dd361ae639ad3a57dc018c0)) {
            $query .= "`{$bca37bc3b9c255b1666da6076ce9aa30}` = null,";
        } else {
            $query .= "`{$bca37bc3b9c255b1666da6076ce9aa30}` = '" . $ipTV_db->escape($a1daec950dd361ae639ad3a57dc018c0) . '\',';
            //c075392751c4828bc4e00e0965478472:
            //goto Eb823b81d52a0b2a1256d64215290521;
        }
        
    }
    return rtrim($query, ',');
}
function b484c4Ff0e3eE69B9D98B92884B88C0f($d76067cf9572f7a6691c85c12faf2a29)
{
    global $ipTV_db;
    $query = '(';
    foreach (array_keys($d76067cf9572f7a6691c85c12faf2a29) as $bca37bc3b9c255b1666da6076ce9aa30) {
        $bca37bc3b9c255b1666da6076ce9aa30 = preg_replace('/[^a-zA-Z0-9\\_]+/', '', $bca37bc3b9c255b1666da6076ce9aa30);
        $query .= "`{$bca37bc3b9c255b1666da6076ce9aa30}`,";
    }
    $query = rtrim($query, ',') . ') VALUES (';
    foreach (array_values($d76067cf9572f7a6691c85c12faf2a29) as $a1daec950dd361ae639ad3a57dc018c0) {
        if (is_array($a1daec950dd361ae639ad3a57dc018c0)) { 
            $query .= '\'' . $ipTV_db->escape(json_encode($a1daec950dd361ae639ad3a57dc018c0)) . '\',';
        }
        else if (is_null($a1daec950dd361ae639ad3a57dc018c0)) {
            $query .= 'NULL,';
        } else {
            //cb6f681d4fbd2684f1c003ecc9ac7a91:
            $query .= '\'' . $ipTV_db->escape($a1daec950dd361ae639ad3a57dc018c0) . '\',';
            //goto e1365d05c90b3128c0d7a8cfecb5a3d5;
        }
        
    }
    $query = rtrim($query, ',') . ');';
    return $query;
}
function eF9fcefFa62Db6ecC4c8a628B9b5a9Af($string)
{
    return is_array(json_decode($string, true));
}
?>
