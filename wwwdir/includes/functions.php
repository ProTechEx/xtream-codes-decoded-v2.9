<?php

function decrypt_config($data, $key)
{
    $index = 0;
    $output = '';
    foreach (str_split($data) as $char) {
        $output .= chr(ord($char) ^ ord($key[$index++ % strlen($key)]));
    }
    return $output;
}
function watchdogData()
{
    $json = array();
    $json['cpu'] = intval(GetTotalCPUsage());
    $json['cpu_cores'] = intval(shell_exec('cat /proc/cpuinfo | grep "^processor" | wc -l'));
    $json['cpu_avg'] = intval(sys_getloadavg()[0] * 100 / $json['cpu_cores']);
    if ($json['cpu_avg'] > 100) {
        $json['cpu_avg'] = 100;
    }
    $available = (int) trim(shell_exec('free | grep -c available'));
    if ($available == 0) {
        $json['total_mem'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $2}\''));
        $json['total_mem_free'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $4+$6+$7}\''));
    } else {
        $json['total_mem'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $2}\''));
        $json['total_mem_free'] = intval(shell_exec('/usr/bin/free -tk | grep -i Mem: | awk \'{print $7}\''));
    }
    $json['total_mem_used'] = $json['total_mem'] - $json['total_mem_free'];
    $json['total_mem_used_percent'] = (int) $json['total_mem_used'] / $json['total_mem'] * 100;
    $json['total_disk_space'] = disk_total_space(IPTV_PANEL_DIR);
    $json['uptime'] = get_boottime();
    $json['total_running_streams'] = shell_exec('ps ax | grep -v grep | grep ffmpeg | grep -c ' . FFMPEG_PATH);
    $int = ipTV_lib::$StreamingServers[SERVER_ID]['network_interface'];
    $json['bytes_sent'] = 0;
    $json['bytes_received'] = 0;
    if (file_exists("/sys/class/net/{$int}/statistics/tx_bytes")) {
        $bytes_sent_old = trim(file_get_contents("/sys/class/net/{$int}/statistics/tx_bytes"));
        $bytes_received_old = trim(file_get_contents("/sys/class/net/{$int}/statistics/rx_bytes"));
        sleep(1);
        $bytes_sent_new = trim(file_get_contents("/sys/class/net/{$int}/statistics/tx_bytes"));
        $bytes_received_new = trim(file_get_contents("/sys/class/net/{$int}/statistics/rx_bytes"));
        $total_bytes_sent = round(($bytes_sent_new - $bytes_sent_old) / 1024 * 0.0078125, 2);
        $total_bytes_received = round(($bytes_received_new - $bytes_received_old) / 1024 * 0.0078125, 2);
        $json['bytes_sent'] = $total_bytes_sent;
        $json['bytes_received'] = $total_bytes_received;
    }
    $json['cpu_load_average'] = sys_getloadavg()[0];
    return $json;
}
function isMobileDevice()
{
    $aMobileUA = array('/iphone/i' => 'iPhone', '/ipod/i' => 'iPod', '/ipad/i' => 'iPad', '/android/i' => 'Android', '/blackberry/i' => 'BlackBerry', '/webos/i' => 'Mobile');
    foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
        if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
    }
    return false;
}
function epg_search($array, $key, $value)
{
    $results = array();
    formatArrayResults($array, $key, $value, $results);
    return $results;
}
function formatArrayResults($array, $key, $value, &$results)
{
    if (!is_array($array)) {
        return;
    }
    if (isset($array[$key]) && $array[$key] == $value) {
        $results[] = $array;
    }
    foreach ($array as $item_value) {
        formatArrayResults($item_value, $key, $value, $results);
    }
}
function KillProcessCmd($file, $time = 600)
{
    if (file_exists($file)) {
        $pid = trim(file_get_contents($file));
        if (file_exists('/proc/' . $pid)) {
            if (time() - filemtime($file) < $time) {
                die('Running...');
            }
            posix_kill($pid, 9);
        }
    }
    file_put_contents($file, getmypid());
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
function GetTotalCPUsage()
{
    $total_cpu = intval(shell_exec('ps aux|awk \'NR > 0 { s +=$3 }; END {print s}\''));
    $cores = intval(shell_exec('grep --count processor /proc/cpuinfo'));
    return intval($total_cpu / $cores);
}
function portal_auth($sn, $mac, $ver, $stb_type, $image_version, $device_id, $device_id2, $hw_version, $user_ip, $enable_debug_stalker, $req_type, $req_action)
{
    global $ipTV_db;
    $mac = base64_encode(strtoupper(urldecode($mac)));
    $verUpdate = false;
    if (!$enable_debug_stalker && (!empty($ver) || !empty($stb_type) || !empty($image_version) || !empty($device_id) || !empty($device_id2) || !empty($hw_version))) {
        $verUpdate = true;
    }
    if (!$enable_debug_stalker && !$verUpdate && $req_type != 'stb' && $req_action != 'set_fav' && file_exists(TMP_DIR . 'stalker_' . md5($mac))) {
        $res = json_decode(file_get_contents(TMP_DIR . 'stalker_' . md5($mac)), true);
        return empty($res) ? false : $res;
    }
    $ipTV_db->query('SELECT * FROM `mag_devices` t1 INNER JOIN `users` t2 ON t2.id = t1.user_id WHERE t1.`mac` = \'%s\' LIMIT 1', $mac);
    if ($ipTV_db->num_rows() > 0) {
        $userMag = $ipTV_db->get_row();
        $userMag['allowed_ips'] = json_decode($userMag['allowed_ips'], true);
        if ($userMag['admin_enabled'] == 0 || $userMag['enabled'] == 0) {
            return false;
        }
        if (!empty($userMag['exp_date']) && time() > $userMag['exp_date']) {
            return false;
        }
        if (!empty($userMag['allowed_ips']) && !in_array($user_ip, array_map('gethostbyname', $userMag['allowed_ips']))) {
            return false;
        }
        if ($verUpdate) {
            $ipTV_db->query('UPDATE `mag_devices` SET `ver` = \'%s\' WHERE `mag_id` = \'%d\'', $ver, $userMag['mag_id']);
            if (!empty(ipTV_lib::$settings['allowed_stb_types']) && !in_array(strtolower($stb_type), ipTV_lib::$settings['allowed_stb_types'])) {
                return false;
            }
            if ($userMag['lock_device'] == 1 && !empty($userMag['sn']) && $userMag['sn'] !== $sn) {
                return false;
            }
            if ($userMag['lock_device'] == 1 && !empty($userMag['device_id']) && $userMag['device_id'] !== $device_id) {
                return false;
            }
            if ($userMag['lock_device'] == 1 && !empty($userMag['device_id2']) && $userMag['device_id2'] !== $device_id2) {
                return false;
            }
            if ($userMag['lock_device'] == 1 && !empty($userMag['hw_version']) && $userMag['hw_version'] !== $hw_version) {
                return false;
            }
            if (!empty(ipTV_lib::$settings['stalker_lock_images']) && !in_array($ver, ipTV_lib::$settings['stalker_lock_images'])) {
                return false;
            }
            $geoip = new Reader(GEOIP2_FILENAME);
            $geoip_country_code = $geoip->getWithPrefixLen($user_ip)['registered_country']['iso_code'];
            $geoip->close();
            if (!empty($geoip_country_code)) {
                $forced_country = !empty($userMag['forced_country']) ? true : false;
                if ($forced_country && $userMag['forced_country'] != 'ALL' && $geoip_country_code != $userMag['forced_country'] || !$forced_country && !in_array('ALL', ipTV_lib::$settings['allow_countries']) && !in_array($geoip_country_code, ipTV_lib::$settings['allow_countries'])) {
                    return false;
                }
            }
            $ipTV_db->query('UPDATE `mag_devices` SET `ip` = \'%s\',`stb_type` = \'%s\',`sn` = \'%s\',`ver` = \'%s\',`image_version` = \'%s\',`device_id` = \'%s\',`device_id2` = \'%s\',`hw_version` = \'%s\' WHERE `mag_id` = \'%d\'', $user_ip, htmlentities($stb_type), htmlentities($sn), htmlentities($ver), htmlentities($image_version), htmlentities($device_id), htmlentities($device_id2), htmlentities($hw_version), $userMag['mag_id']);
        }
        $userMag['fav_channels'] = !empty($userMag['fav_channels']) ? json_decode($userMag['fav_channels'], true) : array();
        if (empty($userMag['fav_channels']['live'])) {
            $userMag['fav_channels']['live'] = array();
        }
        if (empty($userMag['fav_channels']['movie'])) {
            $userMag['fav_channels']['movie'] = array();
        }
        if (empty($userMag['fav_channels']['radio_streams'])) {
            $userMag['fav_channels']['radio_streams'] = array();
        }
        $userMag['get_profile_vars'] = $userMag;
        unset($userMag['get_profile_vars']['use_embedded_settings'], $userMag['get_profile_vars']['mag_id'], $userMag['get_profile_vars']['user_id'], $userMag['get_profile_vars']['ver'], $userMag['get_profile_vars']['sn'], $userMag['get_profile_vars']['device_id'], $userMag['get_profile_vars']['device_id2'], $userMag['get_profile_vars']['spdif_mode'], $userMag['get_profile_vars']['mag_player'], $userMag['get_profile_vars']['fav_channels'], $userMag['get_profile_vars']['token'], $userMag['get_profile_vars']['lock_device'], $userMag['get_profile_vars']['member_id'], $userMag['get_profile_vars']['username'], $userMag['get_profile_vars']['exp_date'], $userMag['get_profile_vars']['admin_enabled'], $userMag['get_profile_vars']['enabled'], $userMag['get_profile_vars']['admin_notes'], $userMag['get_profile_vars']['reseller_notes'], $userMag['get_profile_vars']['bouquet'], $userMag['get_profile_vars']['max_connections'], $userMag['get_profile_vars']['is_restreamer'], $userMag['get_profile_vars']['allowed_ips'], $userMag['get_profile_vars']['allowed_ua'], $userMag['get_profile_vars']['is_trial'], $userMag['get_profile_vars']['created_at'], $userMag['get_profile_vars']['created_by'], $userMag['get_profile_vars']['pair_id'], $userMag['get_profile_vars']['is_mag'], $userMag['get_profile_vars']['is_e2'], $userMag['get_profile_vars']['force_server_id'], $userMag['get_profile_vars']['is_isplock'], $userMag['get_profile_vars']['as_number'], $userMag['get_profile_vars']['isp_desc'], $userMag['get_profile_vars']['forced_country'], $userMag['get_profile_vars']['is_stalker'], $userMag['get_profile_vars']['bypass_ua'], $userMag['get_profile_vars']['expires']);
        $userMag['mag_player'] = trim($userMag['mag_player']);
        file_put_contents(TMP_DIR . 'stalker_' . md5($mac), json_encode($userMag));
        return $userMag;
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
function UniqueID()
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
        list($host, $act) = explode(':', $_SERVER['HTTP_HOST']);
        $domain_name = ipTV_lib::$StreamingServers[SERVER_ID]['server_protocol'] . '://' . $host . ':' . ipTV_lib::$StreamingServers[SERVER_ID]['request_port'] . '/';
    }
    $streams_sys = array();
    if ($output_key == 'rtmp') {
        $ipTV_db->query('SELECT t1.id,t2.server_id FROM `streams` t1 INNER JOIN `streams_sys` t2 ON t2.stream_id = t1.id WHERE t1.rtmp_output = 1');
        $streams_sys = $ipTV_db->get_rows(true, 'id', false, 'server_id');
    }
    if (empty($output_ext)) {
        $output_ext = 'ts';
    }
    $ipTV_db->query('SELECT t1.*,t2.* FROM `devices` t1 LEFT JOIN `access_output` t2 ON t2.access_output_id = t1.default_output WHERE t1.device_key = \'%s\' LIMIT 1', $device_key);
    if ($ipTV_db->num_rows() > 0) {
        $device_info = $ipTV_db->get_row();
        $data = '';
        if (!empty($user_info['series_ids'])) {
            $series = ipTV_lib::seriesData();
            foreach ($series as $id => $serie) {
                if (!in_array($id, $user_info['series_ids'])) {
                    continue;
                }
                foreach ($serie['series_data'] as $category_id => $series) {
                    $epNumber = 0;
                    foreach ($series as $stream_id => $serie) {
                        $movie_properties = ipTV_lib::movieProperties($stream_id);
                        $serie['live'] = 0;
                        if (ipTV_lib::$settings['series_custom_name'] == 0) {
                            $serie['stream_display_name'] = $serie['title'] . ' S' . sprintf('%02d', $category_id) . ' E' . sprintf('%02d', ++$epNumber);
                        } else {
                            $serie['stream_display_name'] = $serie['title'] . ' S' . sprintf('%02d', $category_id) . " {$serie['stream_display_name']}";
                        }
                        $serie['movie_propeties'] = array('movie_image' => !empty($movie_properties['movie_image']) ? $movie_properties['movie_image'] : $serie['cover']);
                        $serie['type_output'] = 'series';
                        $serie['category_name'] = $serie['category_name'];
                        $serie['id'] = $stream_id;
                        $user_info['channels'][$stream_id] = $serie;
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
                if (preg_match('/\\{URL\\#(.*?)\\}/', $device_info['device_conf'], $matches)) {
                    $devicesArray = str_split($matches[1]);
                    $url_pattern = $matches[0];
                } else {
                    $devicesArray = array();
                    $url_pattern = '{URL}';
                }
                foreach ($user_info['channels'] as $channel) {
                    $movie_propeties = !isset($channel['movie_propeties']) ? ipTV_lib::movieProperties($channel['id']) : $channel['movie_propeties'];
                    if (empty($channel['stream_source'])) {
                        if (!($channel['live'] == 0)) {
                            if (!($output_key != 'rtmp' || !array_key_exists($channel['id'], $streams_sys))) {
                                $StreamSysIds = array_values(array_keys($streams_sys[$channel['id']]));
                                if (in_array($user_info['force_server_id'], $StreamSysIds)) {
                                    $server_id = $user_info['force_server_id'];
                                }
                                else if (ipTV_lib::$settings['rtmp_random'] == 1) {
                                    $server_id = $StreamSysIds[array_rand($StreamSysIds, 1)];
                                } else {
                                    $server_id = $StreamSysIds[0];
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
                                $data .= str_replace(array($url_pattern, '{ESR_ID}', '{SID}', '{chANNEL_NAME}', '{chANNEL_ID}', '{CATEGORY}', '{chANNEL_ICON}'), array(str_replace($devicesArray, array_map('urlencode', $devicesArray), $url), $esr_id, $sid, $channel['stream_display_name'], $channel['channel_id'], $channel['category_name'], $icon), $device_info['device_conf']) . '';
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
            $query = "SELECT t1.*,t2.*,t3.*,t5.bitrate FROM `user_activity_now` t1 LEFT JOIN `users` t2 ON t2.id = t1.user_id LEFT JOIN `streams` t3 ON t3.id = t1.stream_id LEFT JOIN `streams_sys` t5 ON t5.stream_id = t1.stream_id AND t5.server_id = t1.server_id {$extra} ORDER BY t1.activity_id ASC";
            break;
        case 'closed':
            $query = "SELECT t1.*,t2.*,t3.*,t5.bitrate FROM `user_activity` t1 LEFT JOIN `users` t2 ON t2.id = t1.user_id LEFT JOIN `streams` t3 ON t3.id = t1.stream_id LEFT JOIN `streams_sys` t5 ON t5.stream_id = t1.stream_id AND t5.server_id = t1.server_id {$extra} ORDER BY t1.activity_id ASC";
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
