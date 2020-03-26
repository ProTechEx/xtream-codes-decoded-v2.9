<?php
/*Rev:26.09.18r0*/

function decrypt_config($data, $key)
{
    $index = 0;
    $output = '';
    foreach (str_split($data) as $d88b7dc5f89e0e7d0d394100bf992462) {
        $output .= chr(ord($d88b7dc5f89e0e7d0d394100bf992462) ^ ord($key[$index++ % strlen($key)]));
    }
    return $output;
}
function D6E530a9573198395bDB5822b82478E2()
{
    $D8dbdb2118a7a93a0eeb04fc548f2af4 = array();
    $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu'] = intval(A072E3167C4fD73Eb67540546C961B7E());
    $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_cores'] = intval(shell_exec('cat /proc/cpuinfo | grep "^processor" | wc -l'));
    $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_avg'] = intval(sys_getloadavg()[0] * 100 / $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_cores']);
    if ($D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_avg'] > 100) {
        $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_avg'] = 100;
    }
    $b05334022f117f99e07e10e7120b3707 = (int) trim(shell_exec('free | grep -c available'));
    if ($b05334022f117f99e07e10e7120b3707 == 0) {
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $2}\''));
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_free'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $4+$6+$7}\''));
    } else {
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $2}\''));
        $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_free'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $7}\''));
    }
    $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_used'] = $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem'] - $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_free'];
    $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_used_percent'] = (int) $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem_used'] / $D8dbdb2118a7a93a0eeb04fc548f2af4['total_mem'] * 100;
    $D8dbdb2118a7a93a0eeb04fc548f2af4['total_disk_space'] = disk_total_space(IPTV_PANEL_DIR);
    $D8dbdb2118a7a93a0eeb04fc548f2af4['uptime'] = get_boottime();
    $D8dbdb2118a7a93a0eeb04fc548f2af4['total_running_streams'] = shell_exec('ps ax | grep -v grep | grep ffmpeg | grep -c ' . FFMPEG_PATH);
    $d0d324f3dbb8bbc5fff56e8a848beb7a = ipTV_lib::$StreamingServers[SERVER_ID]['network_interface'];
    $D8dbdb2118a7a93a0eeb04fc548f2af4['bytes_sent'] = 0;
    $D8dbdb2118a7a93a0eeb04fc548f2af4['bytes_received'] = 0;
    if (file_exists("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/tx_bytes")) {
        $b10021b298f7d4ce2f8e80315325fa1a = trim(file_get_contents("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/tx_bytes"));
        $C5b51b10f98c22fb985e90c23eade263 = trim(file_get_contents("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/rx_bytes"));
        sleep(1);
        $e54a6ff3afc52767cdd38f62ab4c38d1 = trim(file_get_contents("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/tx_bytes"));
        $d1a978924624c41845605404ded7e846 = trim(file_get_contents("/sys/class/net/{$d0d324f3dbb8bbc5fff56e8a848beb7a}/statistics/rx_bytes"));
        $c01d5077f34dc0ef046a6efa9e8e24f4 = round(($e54a6ff3afc52767cdd38f62ab4c38d1 - $b10021b298f7d4ce2f8e80315325fa1a) / 1024 * 0.0078125, 2);
        $B5490c2f61c894c091e04441954a0f09 = round(($d1a978924624c41845605404ded7e846 - $C5b51b10f98c22fb985e90c23eade263) / 1024 * 0.0078125, 2);
        $D8dbdb2118a7a93a0eeb04fc548f2af4['bytes_sent'] = $c01d5077f34dc0ef046a6efa9e8e24f4;
        $D8dbdb2118a7a93a0eeb04fc548f2af4['bytes_received'] = $B5490c2f61c894c091e04441954a0f09;
    }
    $D8dbdb2118a7a93a0eeb04fc548f2af4['cpu_load_average'] = sys_getloadavg()[0];
    return $D8dbdb2118a7a93a0eeb04fc548f2af4;
}
function e6A2B39B5861D06ca4034887864A5Fb5()
{
    $f93d929641c6b747360282c4db5c91dd = array('/iphone/i' => 'iPhone', '/ipod/i' => 'iPod', '/ipad/i' => 'iPad', '/android/i' => 'Android', '/blackberry/i' => 'BlackBerry', '/webos/i' => 'Mobile');
    foreach ($f93d929641c6b747360282c4db5c91dd as $Ce397562fcf2ed0fca47e4a48152c1ff => $f543392c71508ec7c2555f6fc8d3294d) {
        if (preg_match($Ce397562fcf2ed0fca47e4a48152c1ff, $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
    }
    return false;
}
function c39eD4eaD88eD7C28c7C17F4FBb37669($array, $key, $value)
{
    $results = array();
    B437c8Ac70D749dAD4936900DBa780F9($array, $key, $value, $results);
    return $results;
}
function B437c8AC70D749Dad4936900dbA780f9($array, $key, $value, &$results)
{
    if (!is_array($array)) {
        return;
    }
    if (isset($array[$key]) && $array[$key] == $value) {
        $results[] = $array;
    }
    foreach ($array as $cf893362b341e42756ec3a6055a2bb5f) {
        b437c8Ac70d749dad4936900DBA780f9($cf893362b341e42756ec3a6055a2bb5f, $key, $value, $results);
        //Ee858ab20550647c62f6e4338c6cadc1:
    }
}
function BBd9e78AC32626E138e758e840305a7C($e5ececd623496efd3a17d36d4eb4b945, $time = 600)
{
    if (file_exists($e5ececd623496efd3a17d36d4eb4b945)) {
        $pid = trim(file_get_contents($e5ececd623496efd3a17d36d4eb4b945));
        if (file_exists('/proc/' . $pid)) {
            if (time() - filemtime($e5ececd623496efd3a17d36d4eb4b945) < $time) {
                die('Running...');
            }
            posix_kill($pid, 9);
        }
    }
    file_put_contents($e5ececd623496efd3a17d36d4eb4b945, getmypid());
    return false;
}
function CheckFlood()
{
    global $ipTV_db;
    if (ipTV_lib::$settings['flood_limit'] == 0) {
        return;
    }
    $user_ip = ipTV_streaming::getUserIP();
    if (empty($user_ip) || in_array($user_ip, ipTV_streaming::getAllowedIPsAdmin())) {
        return;
    }
    $restreamers = array_filter(array_unique(explode(',', ipTV_lib::$settings['flood_ips_exclude'])));
    if (in_array($user_ip, $restreamers)) {
        return;
    }
    $user_ip_file = TMP_DIR . $user_ip . '.flood';
    if (file_exists($user_ip_file)) {
        $connected_ips = json_decode(file_get_contents($user_ip_file), true);
        $flood_seconds = ipTV_lib::$settings['flood_seconds'];
        $flood_limit = ipTV_lib::$settings['flood_limit'];
        if (time() - $connected_ips['last_request'] <= $flood_seconds) {
            ++$connected_ips['requests'];
            if ($connected_ips['requests'] >= $flood_limit) {
                $ipTV_db->query('INSERT INTO `blocked_ips` (`ip`,`notes`,`date`) VALUES(\'%s\',\'%s\',\'%d\')', $user_ip, 'FLOOD ATTACK', time());
                ipTV_servers::RunCommandServer(array_keys(ipTV_lib::$StreamingServers), "sudo /sbin/iptables -A INPUT -s {$user_ip} -j DROP");
                unlink($user_ip_file);
                return;
            }
            $connected_ips['last_request'] = time();
            file_put_contents($user_ip_file, json_encode($connected_ips), LOCK_EX);
        } else {
            $connected_ips['requests'] = 0;
            $connected_ips['last_request'] = time();
            file_put_contents($user_ip_file, json_encode($connected_ips), LOCK_EX);
        }
    } else {
        file_put_contents($user_ip_file, json_encode(array('requests' => 0, 'last_request' => time())), LOCK_EX);
    }
}
function GetEPGStream($stream_id, $from_now = false)
{
    global $ipTV_db;
    $ipTV_db->query('SELECT `type`,`movie_propeties`,`epg_id`,`channel_id` FROM `streams` WHERE `id` = \'%d\'', $stream_id);
    if ($ipTV_db->num_rows() > 0) {
        $data = $ipTV_db->get_row();
        if ($data['type'] != 2) {
            if ($from_now) {
                $ipTV_db->query('SELECT * FROM `epg_data` WHERE `epg_id` = \'%d\' AND `channel_id` = \'%s\' AND `end` >= \'%s\'', $data['epg_id'], $data['channel_id'], date('Y-m-d H:i:00'));
            } else {
                $ipTV_db->query('SELECT * FROM `epg_data` WHERE `epg_id` = \'%d\' AND `channel_id` = \'%s\'', $data['epg_id'], $data['channel_id']);
            }
            return $ipTV_db->get_rows();
        } else {
            return json_decode($data['movie_propeties'], true);
        }
    }
    return array();
}
function a072E3167c4Fd73EB67540546C961B7e()
{
    $A00fdf3e17773cc697a9e9760a752e67 = intval(shell_exec('ps aux|awk \'NR > 0 { s +=$3 }; END {print s}\''));
    $Beead58eb65f6a16b84a5d7f85a2dbd0 = intval(shell_exec('grep --count processor /proc/cpuinfo'));
    return intval($A00fdf3e17773cc697a9e9760a752e67 / $Beead58eb65f6a16b84a5d7f85a2dbd0);
}
function B9361CDF8F8f200F06F546758512060c($b25b89525a979cf56e2fd295b28327b8, $mac, $fca2439385f041f384419649ca2471d6, $d8ba920e2a1ba9839322c2bca0a7a741, $be29ac67a4314fc9435deb1462cae967, $a0bdfe2058b3579da2b71ebf929871e2, $Ba644b1066f7c673215de30d5ce5e62c, $B71eec623f2edcac610184525828cc2d, $f429d0e47085017e3f1e415952e44cba, $A6dde9bd7afc06231a1481ec56fd5768, $f34a0094f9db3be3b99dd1eb1e9a3b6d, $A60fc3238902ec8f309d806e5a28e0f7)
{
    global $ipTV_db;
    $mac = base64_encode(strtoupper(urldecode($mac)));
    $cfc7b4c8f12f119c2180693d0fa61648 = false;
    if (!$A6dde9bd7afc06231a1481ec56fd5768 && (!empty($fca2439385f041f384419649ca2471d6) || !empty($d8ba920e2a1ba9839322c2bca0a7a741) || !empty($be29ac67a4314fc9435deb1462cae967) || !empty($a0bdfe2058b3579da2b71ebf929871e2) || !empty($Ba644b1066f7c673215de30d5ce5e62c) || !empty($B71eec623f2edcac610184525828cc2d))) {
        $cfc7b4c8f12f119c2180693d0fa61648 = true;
    }
    if (!$A6dde9bd7afc06231a1481ec56fd5768 && !$cfc7b4c8f12f119c2180693d0fa61648 && $f34a0094f9db3be3b99dd1eb1e9a3b6d != 'stb' && $A60fc3238902ec8f309d806e5a28e0f7 != 'set_fav' && file_exists(TMP_DIR . 'stalker_' . md5($mac))) {
        $res = json_decode(file_get_contents(TMP_DIR . 'stalker_' . md5($mac)), true);
        return empty($res) ? false : $res;
    }
    $ipTV_db->query('SELECT * FROM `mag_devices` t1
                      INNER JOIN `users` t2 ON t2.id = t1.user_id
                      WHERE t1.`mac` = \'%s\'
                      LIMIT 1', $mac);
    if ($ipTV_db->num_rows() > 0) {
        $E574ed349c1c464172b5a4221afe809e = $ipTV_db->get_row();
        $E574ed349c1c464172b5a4221afe809e['allowed_ips'] = json_decode($E574ed349c1c464172b5a4221afe809e['allowed_ips'], true);
        if ($E574ed349c1c464172b5a4221afe809e['admin_enabled'] == 0 || $E574ed349c1c464172b5a4221afe809e['enabled'] == 0) {
            return false;
        }
        if (!empty($E574ed349c1c464172b5a4221afe809e['exp_date']) && time() > $E574ed349c1c464172b5a4221afe809e['exp_date']) {
            return false;
        }
        if (!empty($E574ed349c1c464172b5a4221afe809e['allowed_ips']) && !in_array($f429d0e47085017e3f1e415952e44cba, array_map('gethostbyname', $E574ed349c1c464172b5a4221afe809e['allowed_ips']))) {
            return false;
        }
        if ($cfc7b4c8f12f119c2180693d0fa61648) {
            $ipTV_db->query('UPDATE `mag_devices` SET `ver` = \'%s\' WHERE `mag_id` = \'%d\'', $fca2439385f041f384419649ca2471d6, $E574ed349c1c464172b5a4221afe809e['mag_id']);
            if (!empty(ipTV_lib::$settings['allowed_stb_types']) && !in_array(strtolower($d8ba920e2a1ba9839322c2bca0a7a741), ipTV_lib::$settings['allowed_stb_types'])) {
                return false;
            }
            if ($E574ed349c1c464172b5a4221afe809e['lock_device'] == 1 && !empty($E574ed349c1c464172b5a4221afe809e['sn']) && $E574ed349c1c464172b5a4221afe809e['sn'] !== $b25b89525a979cf56e2fd295b28327b8) {
                return false;
            }
            if ($E574ed349c1c464172b5a4221afe809e['lock_device'] == 1 && !empty($E574ed349c1c464172b5a4221afe809e['device_id']) && $E574ed349c1c464172b5a4221afe809e['device_id'] !== $a0bdfe2058b3579da2b71ebf929871e2) {
                return false;
            }
            if ($E574ed349c1c464172b5a4221afe809e['lock_device'] == 1 && !empty($E574ed349c1c464172b5a4221afe809e['device_id2']) && $E574ed349c1c464172b5a4221afe809e['device_id2'] !== $Ba644b1066f7c673215de30d5ce5e62c) {
                return false;
            }
            if ($E574ed349c1c464172b5a4221afe809e['lock_device'] == 1 && !empty($E574ed349c1c464172b5a4221afe809e['hw_version']) && $E574ed349c1c464172b5a4221afe809e['hw_version'] !== $B71eec623f2edcac610184525828cc2d) {
                return false;
            }
            if (!empty(ipTV_lib::$settings['stalker_lock_images']) && !in_array($fca2439385f041f384419649ca2471d6, ipTV_lib::$settings['stalker_lock_images'])) {
                return false;
            }
            $ded15b7e9c47ec5a3dea3c69332153c8 = new geoip(GEOIP2_FILENAME);
            $geoip_country_code = $ded15b7e9c47ec5a3dea3c69332153c8->c6A76952b4cef18F3C98C0E6A9Dd1274($f429d0e47085017e3f1e415952e44cba)['registered_country']['iso_code'];
            $ded15b7e9c47ec5a3dea3c69332153c8->close();
            if (!empty($geoip_country_code)) {
                $ab59908f6050f752836a953eb8bb8e52 = !empty($E574ed349c1c464172b5a4221afe809e['forced_country']) ? true : false;
                if ($ab59908f6050f752836a953eb8bb8e52 && $E574ed349c1c464172b5a4221afe809e['forced_country'] != 'ALL' && $geoip_country_code != $E574ed349c1c464172b5a4221afe809e['forced_country'] || !$ab59908f6050f752836a953eb8bb8e52 && !in_array('ALL', ipTV_lib::$settings['allow_countries']) && !in_array($geoip_country_code, ipTV_lib::$settings['allow_countries'])) {
                    return false;
                }
            }
            $ipTV_db->query('UPDATE `mag_devices` SET `ip` = \'%s\',`stb_type` = \'%s\',`sn` = \'%s\',`ver` = \'%s\',`image_version` = \'%s\',`device_id` = \'%s\',`device_id2` = \'%s\',`hw_version` = \'%s\' WHERE `mag_id` = \'%d\'', $f429d0e47085017e3f1e415952e44cba, htmlentities($d8ba920e2a1ba9839322c2bca0a7a741), htmlentities($b25b89525a979cf56e2fd295b28327b8), htmlentities($fca2439385f041f384419649ca2471d6), htmlentities($be29ac67a4314fc9435deb1462cae967), htmlentities($a0bdfe2058b3579da2b71ebf929871e2), htmlentities($Ba644b1066f7c673215de30d5ce5e62c), htmlentities($B71eec623f2edcac610184525828cc2d), $E574ed349c1c464172b5a4221afe809e['mag_id']);
        }
        $E574ed349c1c464172b5a4221afe809e['fav_channels'] = !empty($E574ed349c1c464172b5a4221afe809e['fav_channels']) ? json_decode($E574ed349c1c464172b5a4221afe809e['fav_channels'], true) : array();
        if (empty($E574ed349c1c464172b5a4221afe809e['fav_channels']['live'])) {
            $E574ed349c1c464172b5a4221afe809e['fav_channels']['live'] = array();
        }
        if (empty($E574ed349c1c464172b5a4221afe809e['fav_channels']['movie'])) {
            $E574ed349c1c464172b5a4221afe809e['fav_channels']['movie'] = array();
        }
        if (empty($E574ed349c1c464172b5a4221afe809e['fav_channels']['radio_streams'])) {
            $E574ed349c1c464172b5a4221afe809e['fav_channels']['radio_streams'] = array();
        }
        $E574ed349c1c464172b5a4221afe809e['get_profile_vars'] = $E574ed349c1c464172b5a4221afe809e;
        unset($E574ed349c1c464172b5a4221afe809e['get_profile_vars']['use_embedded_settings'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['mag_id'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['user_id'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['ver'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['sn'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['device_id'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['device_id2'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['spdif_mode'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['mag_player'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['fav_channels'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['token'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['lock_device'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['member_id'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['username'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['exp_date'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['admin_enabled'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['enabled'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['admin_notes'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['reseller_notes'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['bouquet'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['max_connections'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['is_restreamer'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['allowed_ips'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['allowed_ua'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['is_trial'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['created_at'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['created_by'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['pair_id'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['is_mag'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['is_e2'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['force_server_id'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['is_isplock'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['as_number'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['isp_desc'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['forced_country'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['is_stalker'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['bypass_ua'], $E574ed349c1c464172b5a4221afe809e['get_profile_vars']['expires']);
        $E574ed349c1c464172b5a4221afe809e['mag_player'] = trim($E574ed349c1c464172b5a4221afe809e['mag_player']);
        file_put_contents(TMP_DIR . 'stalker_' . md5($mac), json_encode($E574ed349c1c464172b5a4221afe809e));
        return $E574ed349c1c464172b5a4221afe809e;
    } else {
        file_put_contents(TMP_DIR . 'stalker_' . md5($mac), json_encode(array()));
        return false;
    }
    return false;
}
function GetCategories($type = null)
{
    global $ipTV_db;
    if (is_string($type)) {
        $ipTV_db->query('SELECT t1.* FROM `stream_categories` t1 WHERE t1.category_type = \'%s\' GROUP BY t1.id ORDER BY t1.cat_order ASC', $type);
    } else {
        $ipTV_db->query('SELECT t1.* FROM `stream_categories` t1 ORDER BY t1.cat_order ASC');
    }
    return $ipTV_db->num_rows() > 0 ? $ipTV_db->get_rows(true, 'id') : array();
}
function AfFb052CcA396818D81004fF99Db49AA()
{
    return substr(md5(ipTV_lib::$settings['unique_id']), 0, 15);
}
function GenerateList($user_id, $device_key, $output_key = 'ts', $force_download = false)
{
    global $ipTV_db;
    if (empty($device_key)) {
        return false;
    }
    if ($output_key == 'mpegts') {
        $output_key = 'ts';
    }
    if ($output_key == 'hls') {
        $output_key = 'm3u8';
    }
    if (empty($output_key)) {
        $ipTV_db->query('SELECT t1.output_ext FROM `access_output` t1 INNER JOIN `devices` t2 ON t2.default_output = t1.access_output_id AND `device_key` = \'%s\'', $device_key);
    } else {
        $ipTV_db->query('SELECT t1.output_ext FROM `access_output` t1 WHERE `output_key` = \'%s\'', $output_key);
    }
    if ($ipTV_db->num_rows() <= 0) {
        return false;
    }
    $output_ext = $ipTV_db->get_col();
    $user_info = ipTV_streaming::GetUserInfo($user_id, null, null, true, true, false);
    if (empty($user_info)) {
        return false;
    }
    if (!empty($user_info['exp_date']) && time() >= $user_info['exp_date']) {
        return false;
    }
    if (ipTV_lib::$settings['use_mdomain_in_lists'] == 1) {
        $domain_name = ipTV_lib::$StreamingServers[SERVER_ID]['site_url'];
    } else {
        list($C67d267db947e49f6df4c2c8f1f3a7e8, $B9037608c0d62641e46acd9b3d50eee8) = explode(':', $_SERVER['HTTP_HOST']);
        $domain_name = ipTV_lib::$StreamingServers[SERVER_ID]['server_protocol'] . '://' . $C67d267db947e49f6df4c2c8f1f3a7e8 . ':' . ipTV_lib::$StreamingServers[SERVER_ID]['request_port'] . '/';
    }
    $f53d081795585cc3a4de84113ceb7f31 = array();
    if ($output_key == 'rtmp') {
        $ipTV_db->query('SELECT t1.id,t2.server_id FROM 
                         `streams` t1
                          INNER JOIN `streams_sys` t2 ON t2.stream_id = t1.id
                          WHERE t1.rtmp_output = 1');
        $f53d081795585cc3a4de84113ceb7f31 = $ipTV_db->get_rows(true, 'id', false, 'server_id');
    }
    if (empty($output_ext)) {
        $output_ext = 'ts';
    }
    $ipTV_db->query('SELECT t1.*,t2.*
                              FROM `devices` t1
                              LEFT JOIN `access_output` t2 ON t2.access_output_id = t1.default_output
                              WHERE t1.device_key = \'%s\' LIMIT 1', $device_key);
    if ($ipTV_db->num_rows() > 0) {
        $device_info = $ipTV_db->get_row();
        $data = '';
        if (!empty($user_info['series_ids'])) {
            $deff942ee62f1e5c2c16d11aee464729 = ipTV_lib::seriesData();
            foreach ($deff942ee62f1e5c2c16d11aee464729 as $acb1d10773fb0d1b6ac8cf2c16ecf1b5 => $A0766c7ec9b7cbc336d730454514b34f) {
                if (!in_array($acb1d10773fb0d1b6ac8cf2c16ecf1b5, $user_info['series_ids'])) {
                    continue;
                }
                foreach ($A0766c7ec9b7cbc336d730454514b34f['series_data'] as $c59070c3eab15fea2abe4546ccf476de => $E86ff017778d0dc804add84ab1be9052) {
                    $e831c6d2f20288c01902323cccc3733a = 0;
                    foreach ($E86ff017778d0dc804add84ab1be9052 as $stream_id => $a14a8f906639aa7f5509518ff935b8f0) {
                        $movie_properties = ipTV_lib::movieProperties($stream_id);
                        $a14a8f906639aa7f5509518ff935b8f0['live'] = 0;
                        if (ipTV_lib::$settings['series_custom_name'] == 0) {
                            $a14a8f906639aa7f5509518ff935b8f0['stream_display_name'] = $A0766c7ec9b7cbc336d730454514b34f['title'] . ' S' . sprintf('%02d', $c59070c3eab15fea2abe4546ccf476de) . ' E' . sprintf('%02d', ++$e831c6d2f20288c01902323cccc3733a);
                        } else {
                            $a14a8f906639aa7f5509518ff935b8f0['stream_display_name'] = $A0766c7ec9b7cbc336d730454514b34f['title'] . ' S' . sprintf('%02d', $c59070c3eab15fea2abe4546ccf476de) . " {$a14a8f906639aa7f5509518ff935b8f0['stream_display_name']}";
                        }
                        $a14a8f906639aa7f5509518ff935b8f0['movie_propeties'] = array('movie_image' => !empty($movie_properties['movie_image']) ? $movie_properties['movie_image'] : $A0766c7ec9b7cbc336d730454514b34f['cover']);
                        $a14a8f906639aa7f5509518ff935b8f0['type_output'] = 'series';
                        $a14a8f906639aa7f5509518ff935b8f0['category_name'] = $A0766c7ec9b7cbc336d730454514b34f['category_name'];
                        $a14a8f906639aa7f5509518ff935b8f0['id'] = $stream_id;
                        $user_info['channels'][$stream_id] = $a14a8f906639aa7f5509518ff935b8f0;
                    }
                }
            }
        }
        if ($device_key == 'starlivev5') {
            $output_array = array();
            $output_array['iptvstreams_list'] = array();
            $output_array['iptvstreams_list']['@version'] = 1;
            $output_array['iptvstreams_list']['group'] = array();
            $output_array['iptvstreams_list']['group']['name'] = 'IPTV';
            $output_array['iptvstreams_list']['group']['channel'] = array();
            foreach ($user_info['channels'] as $channel_info) {
                $movie_propeties = !isset($channel_info['movie_propeties']) ? ipTV_lib::movieProperties($channel['id']) : $channel_info['movie_propeties'];
                if (!empty($channel_info['stream_source'])) {
                    $url = str_replace(' ', '%20', json_decode($channel_info['stream_source'], true)[0]);
                    $icon = !empty($movie_propeties['movie_image']) ? $movie_propeties['movie_image'] : $channel_info['stream_icon'];
                } else {
                    $url = $domain_name . "{$channel_info['type_output']}/{$user_info['username']}/{$user_info['password']}/";
                    if ($channel_info['live'] == 0) {
                        $url .= $channel_info['id'] . '.' . GetContainerExtension($channel_info['target_container']);
                        if (!empty($movie_propeties['movie_image'])) {
                            $icon = $movie_propeties['movie_image'];
                        }
                    } else {
                        $url .= $channel_info['id'] . '.' . $output_ext;
                        $icon = $channel_info['stream_icon'];
                    }
                }
                $channel = array();
                $channel['name'] = $channel_info['stream_display_name'];
                $icon = '';
                $channel['icon'] = $icon;
                $channel['stream_url'] = $url;
                $channel['stream_type'] = 0;
                $output_array['iptvstreams_list']['group']['channel'][] = $channel;
            }
            $data = json_encode((object) $output_array);
        } else {
            if (!empty($device_info['device_header'])) {
                $data = str_replace(array('{BOUQUET_NAME}', '{USERNAME}', '{PASSWORD}', '{SERVER_URL}', '{OUTPUT_KEY}'), array(ipTV_lib::$settings['bouquet_name'], $user_info['username'], $user_info['password'], $domain_name, $output_key), $device_info['device_header']) . '';
            }
            if (!empty($device_info['device_conf'])) {
                if (preg_match('/\\{URL\\#(.*?)\\}/', $device_info['device_conf'], $ae37877cee3bc97c8cfa6ec5843993ed)) {
                    $e5cb656483e7536471dc8d1c0bab1ed0 = str_split($ae37877cee3bc97c8cfa6ec5843993ed[1]);
                    $url_pattern = $ae37877cee3bc97c8cfa6ec5843993ed[0];
                } else {
                    $e5cb656483e7536471dc8d1c0bab1ed0 = array();
                    $url_pattern = '{URL}';
                }
                foreach ($user_info['channels'] as $channel) {
                    $movie_propeties = !isset($channel['movie_propeties']) ? ipTV_lib::movieProperties($channel['id']) : $channel['movie_propeties'];
                    if (empty($channel['stream_source'])) {
                        if (!($channel['live'] == 0)) {
                            if (!($output_key != 'rtmp' || !array_key_exists($channel['id'], $f53d081795585cc3a4de84113ceb7f31))) {
                                $e3215fa97db12812ee074d6c110dea4b = array_values(array_keys($f53d081795585cc3a4de84113ceb7f31[$channel['id']]));
                                if (in_array($user_info['force_server_id'], $e3215fa97db12812ee074d6c110dea4b)) {
                                    $server_id = $user_info['force_server_id'];
                                }
                                else if (ipTV_lib::$settings['rtmp_random'] == 1) {
                                    $server_id = $e3215fa97db12812ee074d6c110dea4b[array_rand($e3215fa97db12812ee074d6c110dea4b, 1)];
                                } else {
                                    $server_id = $e3215fa97db12812ee074d6c110dea4b[0];
                                }
                                $url = ipTV_lib::$StreamingServers[$server_id]['rtmp_server'] . "{$channel['id']}?username={$user_info['username']}&password={$user_info['password']}";
                                if (!file_exists(TMP_DIR . 'new_rewrite') || $output_ext != 'ts') {
                                    $url = $domain_name . "{$channel['type_output']}/{$user_info['username']}/{$user_info['password']}/{$channel['id']}.{$output_ext}";
                                } else {
                                    $url = $domain_name . "{$user_info['username']}/{$user_info['password']}/{$channel['id']}";
                                }
                                $icon = $channel['stream_icon'];
                                $url = $domain_name . "{$channel['type_output']}/{$user_info['username']}/{$user_info['password']}/{$channel['id']}." . GetContainerExtension($channel['target_container']);
                                if (!empty($movie_propeties['movie_image'])) {
                                    $icon = $movie_propeties['movie_image'];
                                }
                                $url = str_replace(' ', '%20', json_decode($channel['stream_source'], true)[0]);
                                $icon = !empty($movie_propeties['movie_image']) ? $movie_propeties['movie_image'] : $channel['stream_icon'];
                                $esr_id = $channel['live'] == 1 ? 1 : 4097;
                                $sid = !empty($channel['custom_sid']) ? $channel['custom_sid'] : ':0:1:0:0:0:0:0:0:0:';
                                $data .= str_replace(array($url_pattern, '{ESR_ID}', '{SID}', '{chANNEL_NAME}', '{chANNEL_ID}', '{CATEGORY}', '{chANNEL_ICON}'), array(str_replace($e5cb656483e7536471dc8d1c0bab1ed0, array_map('urlencode', $e5cb656483e7536471dc8d1c0bab1ed0), $url), $esr_id, $sid, $channel['stream_display_name'], $channel['channel_id'], $channel['category_name'], $icon), $device_info['device_conf']) . '';
                            }
                        }
                    }
                }
                $data .= $device_info['device_footer'];
                $data = trim($data);
            }
        }
        if ($force_download === true) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Expires: 0');
            header('cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Disposition: attachment; filename="' . str_replace('{USERNAME}', $user_info['username'], $device_info['device_filename']) . '"');
            header('Content-Length: ' . strlen($data));
            echo $data;
            die;
        }
        return $data;
    }
    return false;
}
function GetContainerExtension($target_container, $stalker_container_priority = false)
{
    $tmp = json_decode($target_container, true);
    if (is_array($tmp)) {
        $target_container = array_map('strtolower', $tmp);
    } else {
        return $target_container;
    }
    $container = $stalker_container_priority ? ipTV_lib::$settings['stalker_container_priority'] : ipTV_lib::$settings['gen_container_priority'];
    if (is_array($container)) {
        foreach ($container as $container_priority) {
            if (in_array($container_priority, $target_container)) {
                return $container_priority;
            }
        }
    }
    return $target_container[0];
}
function GetConnections($end, $server_id = null)
{
    global $ipTV_db;
    $extra = '';
    if (!empty($server_id)) {
        $extra = 'WHERE t1.server_id = \'' . intval($server_id) . '\'';
    }
    switch ($end) {
        case 'open':
            $query = "\r\n                SELECT t1.*,t2.*,t3.*,t5.bitrate\r\n                FROM `user_activity_now` t1\r\n                LEFT JOIN `users` t2 ON t2.id = t1.user_id\r\n                LEFT JOIN `streams` t3 ON t3.id = t1.stream_id\r\n                LEFT JOIN `streams_sys` t5 ON t5.stream_id = t1.stream_id AND t5.server_id = t1.server_id\r\n                {$extra}\r\n                ORDER BY t1.activity_id ASC";
            break;
        case 'closed':
            $query = "\r\n                SELECT t1.*,t2.*,t3.*,t5.bitrate\r\n                FROM `user_activity` t1\r\n                LEFT JOIN `users` t2 ON t2.id = t1.user_id\r\n                LEFT JOIN `streams` t3 ON t3.id = t1.stream_id\r\n                LEFT JOIN `streams_sys` t5 ON t5.stream_id = t1.stream_id AND t5.server_id = t1.server_id\r\n                {$extra}\r\n                ORDER BY t1.activity_id ASC";
            break;
    }
    $ipTV_db->query($query);
    return $ipTV_db->get_rows(true, 'user_id', false);
}
function crontab_refresh()
{
    if (file_exists(TMP_DIR . 'crontab_refresh')) {
        return false;
    }
    $crons = scandir(CRON_PATH);
    $jobs = array();
    foreach ($crons as $cron) {
        $full_path = CRON_PATH . $cron;
        if (!is_file($full_path)) {
            continue;
        }
        if (pathinfo($full_path, PATHINFO_EXTENSION) != 'php') {
            continue;
        }
        if ($cron != 'epg.php') {
            $time = '*/1 * * * *';
        } else {
            $time = '0 1 * * *';
        }
        $jobs[] = "{$time} " . PHP_BIN . ' ' . $full_path . ' # Xtream-Codes IPTV Panel';
    }
	
    $crontab = trim(shell_exec('crontab -l'));
	
    if (!empty($crontab)) {
        $lines = explode('', $crontab);
        $lines = array_map('trim', $lines);
        if ($lines == $jobs) {
            file_put_contents(TMP_DIR . 'crontab_refresh', 1);
            return true;
        }
        $counter = count($lines);
        $index = 0;
        while ($index < $counter) {
            if (stripos($lines[$index], CRON_PATH)) {
                unset($lines[$index]);
            }
            $index++;
        }
        foreach ($jobs as $job) {
            array_push($lines, $job);
        }
    } else {
        $lines = $jobs;
    }
    shell_exec('crontab -r');
    $tmpfname = tempnam('/tmp', 'crontab');
    $handle = fopen($tmpfname, 'w');
    fwrite($handle, implode('', $lines) . '');
    fclose($handle);
    shell_exec("crontab {$tmpfname}");
    @unlink($tmpfname);
    file_put_contents(TMP_DIR . 'crontab_refresh', 1);
}
function searchQuery($tableName, $columnName, $value)
{
    global $ipTV_db;
    $ipTV_db->query("SELECT * FROM `{$tableName}` WHERE `{$columnName}` = '%s'", $value);
    if ($ipTV_db->num_rows() > 0) {
        return true;
    }
    return false;
}
function get_boottime()
{
    if (file_exists('/proc/uptime') and is_readable('/proc/uptime')) {
        $tmp = explode(' ', file_get_contents('/proc/uptime'));
        return secondsToTime(intval($tmp[0]));
    }
    return '';
}
function secondsToTime($inputSeconds)
{
    $secondsInAMinute = 60;
    $secondsInAnHour = 60 * $secondsInAMinute;
    $secondsInADay = 24 * $secondsInAnHour;
    $days = (int) floor($inputSeconds / $secondsInADay);
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = (int) floor($hourSeconds / $secondsInAnHour);
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = (int) floor($minuteSeconds / $secondsInAMinute);
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = (int) ceil($remainingSeconds);
    $final = '';
    if ($days != 0) {
        $final .= "{$days}d ";
    }
    if ($hours != 0) {
        $final .= "{$hours}h ";
    }
    if ($minutes != 0) {
        $final .= "{$minutes}m ";
    }
    $final .= "{$seconds}s";
    return $final;
}
?>
