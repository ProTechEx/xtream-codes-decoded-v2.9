<?php
/*Rev:26.09.18r0*/

class ipTV_streaming
{
    public static $ipTV_db;
    public static $AllowedIPs = array();
    public static function RtmpIps()
    {
        self::$ipTV_db->query('SELECT `ip` FROM `rtmp_ips`');
        return array_merge(array('127.0.0.1'), array_map('gethostbyname', ipTV_lib::array_values_recursive(self::$ipTV_db->get_rows())));
    }
    public static function startFFMPEGSegment($data, $segment_file)
    {
        if (empty($data['xy_offset'])) {
            $x = rand(150, 380);
            $y = rand(110, 250);
        } else {
            list($x, $y) = explode('x', $data['xy_offset']);
        }
        passthru(FFMPEG_PATH . ' -nofix_dts -fflags +igndts -copyts -vsync 0 -nostats -nostdin -hide_banner -loglevel quiet -y -i "' . STREAMS_PATH . $segment_file . '" -filter_complex "drawtext=fontfile=' . FFMPEG_FONTS_PATH . ":text='{$data['message']}':fontsize={$data['font_size']}:x={$x}:y={$y}:fontcolor={$data['font_color']}\" -map 0 -vcodec libx264 -preset ultrafast -acodec copy -scodec copy -mpegts_flags +initial_discontinuity -mpegts_copyts 1 -f mpegts -");
        return true;
    }
    public static function getAllowedIPsCloudIps()
    {
        $ips = array('127.0.0.1', $_SERVER['SERVER_ADDR']);
        if (!file_exists(TMP_DIR . 'cloud_ips') || time() - filemtime(TMP_DIR . 'cloud_ips') >= 600) {
            $contents = ipTV_lib::SimpleWebGet('http://xtream-codes.com/cloud_ips');
            if (!empty($contents)) {
                file_put_contents(TMP_DIR . 'cloud_ips', $contents);
            }
        }
        if (file_exists(TMP_DIR . 'cloud_ips')) {
            $ips = array_filter(array_merge($ips, array_map('trim', file(TMP_DIR . 'cloud_ips'))));
        }
        return array_unique($ips);
    }
    public static function getAllowedIPsAdmin($reg_users = false)
    {
        if (!empty(self::$AllowedIPs)) {
            return self::$AllowedIPs;
        }
        $ips = array('127.0.0.1', $_SERVER['SERVER_ADDR']);
        foreach (ipTV_lib::$StreamingServers as $server_id => $server) {
            if (!empty($server['whitelist_ips'])) {
                $ips = array_merge($ips, json_decode($server['whitelist_ips'], true));
            }
            $ips[] = $server['server_ip'];
            $ips[] = $server['server_ip'];
        }
        if ($reg_users) {
            if (!empty(ipTV_lib::$settings['allowed_ips_admin'])) {
                $ips = array_merge($ips, explode(',', ipTV_lib::$settings['allowed_ips_admin']));
            }
            self::$ipTV_db->query('SELECT * FROM `xtream_main` WHERE id = 1');
            $xtream_main = self::$ipTV_db->get_row();
            if (!empty($xtream_main['root_ip'])) {
                $ips[] = $xtream_main['root_ip'];
            }
            self::$ipTV_db->query('SELECT DISTINCT t1.`ip` FROM `reg_users` t1 INNER JOIN `member_groups` t2 ON t2.group_id = t1.member_group_id AND t2.is_admin = 1 AND t1.`last_login` >= \'%d\'', strtotime('-2 hour'));
            $UsersIP = ipTV_lib::array_values_recursive(self::$ipTV_db->get_rows());
            $ips = array_merge($ips, $UsersIP);
        }
        if (!file_exists(TMP_DIR . 'cloud_ips') || time() - filemtime(TMP_DIR . 'cloud_ips') >= 600) {
            $contents = ipTV_lib::SimpleWebGet('http://xtream-codes.com/cloud_ips');
            if (!empty($contents)) {
                file_put_contents(TMP_DIR . 'cloud_ips', $contents);
            }
        }
        if (file_exists(TMP_DIR . 'cloud_ips')) {
            $ips = array_filter(array_merge($ips, array_map('trim', file(TMP_DIR . 'cloud_ips'))));
        }
        self::$AllowedIPs = $ips;
        return array_unique($ips);
    }
    public static function CloseAndTransfer($activity_id)
    {
        file_put_contents(CLOSE_OPEN_CONS_PATH . $activity_id, 1);
    }
    public static function GetStreamData($stream_id)
    {
        if (CACHE_STREAMS) {
            if (file_exists(TMP_DIR . $stream_id . '_cacheStream') && time() - filemtime(TMP_DIR . $stream_id . '_cacheStream') <= CACHE_STREAMS_TIME) {
                return unserialize(file_get_contents(TMP_DIR . $stream_id . '_cacheStream'));
            }
        }
        $output = array();
        self::$ipTV_db->query('SELECT * FROM `streams` t1 LEFT JOIN `streams_types` t2 ON t2.type_id = t1.type WHERE t1.`id` = \'%d\'', $stream_id);
        if (self::$ipTV_db->num_rows() > 0) {
            $streamData = self::$ipTV_db->get_row();
            $servers = array();
            if ($streamData['direct_source'] == 0) {
                self::$ipTV_db->query('SELECT * FROM `streams_sys` WHERE `stream_id` = \'%d\'', $stream_id);
                if (self::$ipTV_db->num_rows() > 0) {
                    $servers = self::$ipTV_db->get_rows(true, 'server_id');
                }
            }
            $output['info'] = $streamData;
            $output['servers'] = $servers;
            if (CACHE_STREAMS) {
                file_put_contents(TMP_DIR . $stream_id . '_cacheStream', serialize($output), LOCK_EX);
            }
        }
        return !empty($output) ? $output : false;
    }
    public static function ChannelInfo($stream_id, $extension, $user_info, $user_ip, $geoip_country_code, $external_device = '', $con_isp_name = '', $type)
    {
        if ($type == 'archive') {
            self::$ipTV_db->query('SELECT `tv_archive_server_id`,`tv_archive_duration` FROM `streams` WHERE `id` = \'%d\'', $stream_id);
            if (self::$ipTV_db->num_rows() > 0) {
                $row = self::$ipTV_db->get_row();
                if ($row['tv_archive_duration'] > 0 && $row['tv_archive_server_id'] > 0 && array_key_exists($row['tv_archive_server_id'], ipTV_lib::$StreamingServers)) {
                    if ($row['tv_archive_server_id'] != SERVER_ID) {
                        parse_str($_SERVER['QUERY_STRING'], $output);
                        $time = time() + $output['duration'] * 60;
                        $data = array('hash' => md5(json_encode(array('user_id' => $user_info['id'], 'username' => $user_info['username'], 'password' => $user_info['password'], 'user_ip' => $user_ip, 'live_streaming_pass' => ipTV_lib::$settings['live_streaming_pass'], 'external_device' => $external_device, 'isp' => $con_isp_name, 'country' => $geoip_country_code, 'stream_id' => $stream_id, 'start' => $output['start'], 'duration' => $output['duration'], 'extension' => $output['extension'], 'time' => $time))), 'user_id' => $user_info['id'], 'username' => $user_info['username'], 'password' => $user_info['password'], 'time' => $time, 'external_device' => $external_device, 'isp' => $con_isp_name, 'country' => $geoip_country_code, 'stream_id' => $stream_id, 'start' => $output['start'], 'duration' => $output['duration'], 'extension' => $output['extension']);
                        $uri = substr($_SERVER['REQUEST_URI'], 1);
                        header('Location: ' . ipTV_lib::$StreamingServers[$row['tv_archive_server_id']]['site_url'] . 'streaming/timeshift.php?token=' . base64_encode(decrypt_config(json_encode($data), md5(ipTV_lib::$settings['crypt_load_balancing']))));
                        die;
                    } else {
                        return true;
                    }
                }
            }
            return false;
        }
        $stream = self::GetStreamData($stream_id);
        if (empty($stream)) {
            return false;
        }
        if ($stream['info']['direct_source'] == 1) {
            header('Location: ' . str_replace(' ', '%20', json_decode($stream['info']['stream_source'], true)[0]));
            die;
        }
        $StreamSysIds = array();
        foreach (ipTV_lib::$StreamingServers as $createdChannelLocation => $server) {
            if (!array_key_exists($createdChannelLocation, $stream['servers']) || !ipTV_lib::$StreamingServers[$createdChannelLocation]['server_online']) {
                continue;
            }
            if ($type == 'movie') {
                if (!empty($stream['servers'][$createdChannelLocation]['pid']) && $stream['servers'][$createdChannelLocation]['to_analyze'] == 0 && $stream['servers'][$createdChannelLocation]['stream_status'] == 0 && $server['timeshift_only'] == 0) {
                    $StreamSysIds[] = $createdChannelLocation;
                }
            } else {
                if (($stream['servers'][$createdChannelLocation]['on_demand'] == 1 && $stream['servers'][$createdChannelLocation]['pid'] >= 0 && $stream['servers'][$createdChannelLocation]['stream_status'] == 0 || $stream['servers'][$createdChannelLocation]['pid'] > 0 && $stream['servers'][$createdChannelLocation]['stream_status'] == 0) && $stream['servers'][$createdChannelLocation]['to_analyze'] == 0 && time() >= (int) $stream['servers'][$createdChannelLocation]['delay_available_at'] && $server['timeshift_only'] == 0) {
                    $StreamSysIds[] = $createdChannelLocation;
                }
            }
        }
        if (empty($StreamSysIds)) {
            return false;
        }
        $servers = array();
        if (!(ipTV_lib::$settings['online_capacity_interval'] != 0 && file_exists(TMP_DIR . 'servers_capacity') && time() - filemtime(TMP_DIR . 'servers_capacity') <= ipTV_lib::$settings['online_capacity_interval'])) {
            self::$ipTV_db->query('SELECT server_id, COUNT(*) AS online_clients FROM `user_activity_now` GROUP BY server_id');
            $rows = self::$ipTV_db->get_rows(true, 'server_id');
            if (!(ipTV_lib::$settings['split_by'] == 'band')) {
                if (!(ipTV_lib::$settings['split_by'] == 'maxclients')) {
                    if (ipTV_lib::$settings['split_by'] == 'guar_band') {
                        foreach ($rows as $server_id => $row) {
                            $rows[$server_id]['capacity'] = (double) ($row['online_clients'] / ipTV_lib::$StreamingServers[$server_id]['network_guaranteed_speed']);
                        }
                    } else {
                        foreach ($rows as $server_id => $row) {
                            $rows[$server_id]['capacity'] = $row['online_clients'];
                        }
                        $D8d3ca7afab93e5c110124dc7611906c = array();
                        foreach ($StreamSysIds as $server_id) {
                            $A8897e590149896423cc3c897a6c6651 = json_decode(ipTV_lib::$StreamingServers[$server_id]['server_hardware'], true);
                            if (!empty($A8897e590149896423cc3c897a6c6651['network_speed'])) {
                                $D8d3ca7afab93e5c110124dc7611906c[$server_id] = (double) $A8897e590149896423cc3c897a6c6651['network_speed'];
                            } else {
                                $D8d3ca7afab93e5c110124dc7611906c[$server_id] = 1000;
                            }
                        }
                        foreach ($rows as $server_id => $row) {
                            $rows[$server_id]['capacity'] = (double) ($row['online_clients'] / $D8d3ca7afab93e5c110124dc7611906c[$server_id]);
                        }
                        foreach ($rows as $server_id => $row) {
                            $rows[$server_id]['capacity'] = (double) ($row['online_clients'] / ipTV_lib::$StreamingServers[$server_id]['total_clients']);
                        }
                    }
                    if (ipTV_lib::$settings['online_capacity_interval'] != 0) {
                        file_put_contents(TMP_DIR . 'servers_capacity', json_encode($rows), LOCK_EX);
                    }
                    $rows = json_decode(file_get_contents(TMP_DIR . 'servers_capacity'), true);
                    foreach ($StreamSysIds as $server_id) {
                        $online_clients = isset($rows[$server_id]['online_clients']) ? $rows[$server_id]['online_clients'] : 0;
                        if ($online_clients == 0) {
                            $rows[$server_id]['capacity'] = 0;
                        }
                        $servers[$server_id] = ipTV_lib::$StreamingServers[$server_id]['total_clients'] > 0 && ipTV_lib::$StreamingServers[$server_id]['total_clients'] > $online_clients ? $rows[$server_id]['capacity'] : false;
                    }
                    $servers = array_filter($servers, 'is_numeric');
                    if (!empty($servers)) {
                        $serverKeys = array_keys($servers);
                        $serverValues = array_values($servers);
                        array_multisort($serverValues, SORT_ASC, $serverKeys, SORT_ASC);
                        $servers = array_combine($serverKeys, $serverValues);
                        if (!($extension == 'rtmp' && array_key_exists(SERVER_ID, $servers))) {
                            if ($user_info['force_server_id'] != 0 and array_key_exists($user_info['force_server_id'], $servers)) {
                                $force_server_id = $user_info['force_server_id'];
                            } else {
                                $serverIds = array();
                                foreach (array_keys($servers) as $server_id) {
                                    if (!(ipTV_lib::$StreamingServers[$server_id]['enable_geoip'] == 1)) {
                                        if (!(ipTV_lib::$StreamingServers[$server_id]['enable_isp'] == 1)) {
                                            $serverIds[$server_id] = 1;
                                            if (!in_array($geoip_country_code, ipTV_lib::$StreamingServers[$server_id]['geoip_countries'])) {
                                                if (ipTV_lib::$StreamingServers[$server_id]['geoip_type'] == 'strict') {
                                                    unset($servers[$server_id]);
                                                } else {
                                                    $serverIds[$server_id] = ipTV_lib::$StreamingServers[$server_id]['geoip_type'] == 'low_priority' ? 1 : 2;
                                                    $force_server_id = $server_id;
                                                    break;
                                                }
                                                if (!in_array($con_isp_name, ipTV_lib::$StreamingServers[$server_id]['isp_names'])) {
                                                    if (ipTV_lib::$StreamingServers[$server_id]['isp_type'] == 'strict') {
                                                        unset($servers[$server_id]);
                                                    } else {
                                                        $serverIds[$server_id] = ipTV_lib::$StreamingServers[$server_id]['isp_type'] == 'low_priority' ? 1 : 2;
                                                        $force_server_id = $server_id;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                if (empty($serverIds) && empty($force_server_id)) {
                                    return false;
                                }
                                $force_server_id = empty($force_server_id) ? array_search(min($serverIds), $serverIds) : $force_server_id;
                                $force_server_id = SERVER_ID;
                            }
                            if ($force_server_id != SERVER_ID) {
                                if ($type == 'live') {
                                    $time = $extension == 'm3u8' ? 0 : time() + 6;
                                } else {
                                    $streamData = json_decode($stream['servers'][$force_server_id]['stream_info'], true);
                                    $time = time() + (int) $streamData['of_duration'];
                                }
                                $data = array('hash' => md5(json_encode(array('stream_id' => $stream_id, 'user_id' => $user_info['id'], 'username' => $user_info['username'], 'password' => $user_info['password'], 'user_ip' => $user_ip, 'live_streaming_pass' => ipTV_lib::$settings['live_streaming_pass'], 'pid' => $stream['servers'][$force_server_id]['pid'], 'external_device' => $external_device, 'on_demand' => $stream['servers'][$force_server_id]['on_demand'], 'isp' => $con_isp_name, 'bitrate' => $stream['servers'][$force_server_id]['bitrate'], 'country' => $geoip_country_code, 'extension' => $extension, 'is_restreamer' => $user_info['is_restreamer'], 'max_connections' => $user_info['max_connections'], 'monitor_pid' => $stream['servers'][$force_server_id]['monitor_pid'], 'time' => $time))), 'stream_id' => $stream_id, 'user_id' => $user_info['id'], 'time' => $time, 'pid' => $stream['servers'][$force_server_id]['pid'], 'external_device' => $external_device, 'on_demand' => $stream['servers'][$force_server_id]['on_demand'], 'isp' => $con_isp_name, 'bitrate' => $stream['servers'][$force_server_id]['bitrate'], 'country' => $geoip_country_code, 'extension' => $extension, 'is_restreamer' => $user_info['is_restreamer'], 'max_connections' => $user_info['max_connections'], 'monitor_pid' => $stream['servers'][$force_server_id]['monitor_pid']);
                                $uri = substr($_SERVER['REQUEST_URI'], 1);
                                $type = substr_count($uri, '?') == 0 ? '?' : '&';
                                header('Location: ' . ipTV_lib::$StreamingServers[$force_server_id]['site_url'] . $uri . $type . 'token=' . base64_encode(decrypt_config(json_encode($data), md5(ipTV_lib::$settings['crypt_load_balancing']))));
                                die;
                            } else {
                                return array_merge($stream['info'], $stream['servers'][SERVER_ID]);
                            }
                            return false;
                        }
                    }
                }
            }
        }
    }
    public static function checkStreamExistInBouquet($stream_id, $connections = array(), $type = 'movie')
    {
        if (!($type == 'movie')) {
            if ($type == 'series') {
                self::$ipTV_db->query('SELECT series_id FROM `series_episodes` WHERE `stream_id` = \'%d\' LIMIT 1', $stream_id);
                if (self::$ipTV_db->num_rows() > 0) {
                    return in_array(self::$ipTV_db->get_col(), $connections);
                }
            } else {
                return in_array($stream_id, $connections);
            }
            return false;
        }
    }
    public static function GetUserInfo($user_id = null, $username = null, $password = null, $get_ChannelIDS = false, $getBouquetInfo = false, $get_cons = false, $type = array(), $B5e1c013996afcec27bf828245c3ec37 = false, $user_ip = '', $user_agent = '', $a8851ef591e0cdd9aad6ec4f7bd4b160 = array(), $play_token = '', $stream_id = 0)
    {
        if (empty($user_id)) {
            self::$ipTV_db->query('SELECT * FROM `users` WHERE `username` = \'%s\' AND `password` = \'%s\' LIMIT 1', $username, $password);
        } else {
            self::$ipTV_db->query('SELECT * FROM `users` WHERE `id` = \'%d\'', $user_id);
        }
        if (self::$ipTV_db->num_rows() > 0) {
            $user_info = self::$ipTV_db->get_row();
            if (ipTV_lib::$settings['case_sensitive_line'] == 1 && !empty($username) && !empty($password)) {
                if ($user_info['username'] != $username || $user_info['password'] != $password) {
                    return false;
                }
            }
            if (ipTV_lib::$settings['county_override_1st'] == 1 && empty($user_info['forced_country']) && !empty($user_ip) && $user_info['max_connections'] == 1) {
                $user_info['forced_country'] = geoip_country_code_by_name($user_ip);
                self::$ipTV_db->query('UPDATE `users` SET `forced_country` = \'%s\' WHERE `id` = \'%d\'', $user_info['forced_country'], $user_info['id']);
            }
            if ($user_info['is_mag'] == 1 && ipTV_lib::$settings['mag_security'] == 1) {
                if (!empty($user_info['play_token']) && !empty($play_token)) {
                    list($token, $B96676565d19827b6e2eda6db94167c0, $cced8089119eaa83c17b19ea19d9af22) = explode(':', $user_info['play_token']);
                    if (!($token == $play_token && $B96676565d19827b6e2eda6db94167c0 >= time() && $cced8089119eaa83c17b19ea19d9af22 == $stream_id)) {
                        $user_info['mag_invalid_token'] = true;
                    }
                } else {
                    $user_info['mag_invalid_token'] = true;
                }
            }
            $user_info['bouquet'] = json_decode($user_info['bouquet'], true);
            $user_info['allowed_ips'] = @array_filter(array_map('trim', json_decode($user_info['allowed_ips'], true)));
            $user_info['allowed_ua'] = @array_filter(array_map('trim', json_decode($user_info['allowed_ua'], true)));
            if ($get_cons) {
                self::$ipTV_db->query('SELECT COUNT(`activity_id`) FROM `user_activity_now` WHERE `user_id` = \'%d\'', $user_info['id']);
                $user_info['active_cons'] = self::$ipTV_db->get_col();
                if ($user_info['max_connections'] == 1 && ipTV_lib::$settings['disallow_2nd_ip_con'] == 1 && $user_info['active_cons'] > 0 && !empty($user_ip)) {
                    self::$ipTV_db->query('SELECT user_ip FROM `user_activity_now` WHERE `user_id` = \'%d\' LIMIT 1', $user_info['id']);
                    if (self::$ipTV_db->num_rows() > 0) {
                        $user_ip_db = self::$ipTV_db->get_col();
                        if ($user_ip_db != $user_ip) {
                            $user_info['ip_limit_reached'] = 1;
                        }
                    }
                }
                $user_info['pair_line_info'] = array();
                if (!is_null($user_info['pair_id'])) {
                    self::$ipTV_db->query('SELECT COUNT(`activity_id`) FROM `user_activity_now` WHERE `user_id` = \'%d\'', $user_info['pair_id']);
                    $user_info['pair_line_info']['active_cons'] = self::$ipTV_db->get_col();
                    self::$ipTV_db->query('SELECT max_connections FROM `users` WHERE `id` = \'%d\'', $user_info['pair_id']);
                    $user_info['pair_line_info']['max_connections'] = self::$ipTV_db->get_col();
                }
            } else {
                $user_info['active_cons'] = 'N/A';
            }
            if (file_exists(TMP_DIR . 'user_output' . $user_info['id'])) {
                $user_info['output_formats'] = unserialize(file_get_contents(TMP_DIR . 'user_output' . $user_info['id']));
            } else {
                self::$ipTV_db->query('SELECT * FROM `access_output` t1 INNER JOIN `user_output` t2 ON t1.access_output_id = t2.access_output_id WHERE t2.user_id = \'%d\'', $user_info['id']);
                $user_info['output_formats'] = self::$ipTV_db->get_rows(true, 'output_key');
                file_put_contents(TMP_DIR . 'user_output' . $user_info['id'], serialize($user_info['output_formats']), LOCK_EX);
            }
            $user_info['con_isp_name'] = $user_info['con_isp_type'] = null;
            $user_info['isp_is_server'] = $user_info['isp_violate'] = 0;
            if (ipTV_lib::$settings['show_isps'] == 1 && !empty($user_ip)) {
                $isp_lock = self::apiGetISPName($user_ip, $user_agent);
                if (is_array($isp_lock)) {
                    if (!empty($isp_lock['isp_info']['description'])) {
                        $user_info['con_isp_name'] = $isp_lock['isp_info']['description'];
                        $IspIsBlocked = self::checkIspIsBlocked($user_info['con_isp_name']);
                        if ($user_info['is_restreamer'] == 0 && ipTV_lib::$settings['block_svp'] == 1 && !empty($isp_lock['isp_info']['is_server'])) {
                            $user_info['isp_is_server'] = $isp_lock['isp_info']['is_server'];
                        }
                        if ($user_info['isp_is_server'] == 1) {
                            $user_info['con_isp_type'] = $isp_lock['isp_info']['type'];
                        }
                        if ($IspIsBlocked !== false) {
                            $user_info['isp_is_server'] = $IspIsBlocked == 1 ? 1 : 0;
                            $user_info['con_isp_type'] = $user_info['isp_is_server'] == 1 ? 'Custom' : null;
                        }
                    }
                }
                if (!empty($user_info['con_isp_name']) && ipTV_lib::$settings['enable_isp_lock'] == 1 && $user_info['is_stalker'] == 0 && $user_info['is_isplock'] == 1 && !empty($user_info['isp_desc']) && strtolower($user_info['con_isp_name']) != strtolower($user_info['isp_desc'])) {
                    $user_info['isp_violate'] = 1;
                }
                if ($user_info['isp_violate'] == 0 && strtolower($user_info['con_isp_name']) != strtolower($user_info['isp_desc'])) {
                    self::$ipTV_db->query('UPDATE `users` SET `isp_desc` = \'%s\' WHERE `id` = \'%d\'', $user_info['con_isp_name'], $user_info['id']);
                }
            }
            if ($get_ChannelIDS) {
                $Ff48bb3649e5b84524bd8d318c03db3c = $A92229131e0f5177a362478fd6f3bd8d = array();
                if (ipTV_lib::$settings['new_sorting_bouquet'] != 1) {
                    sort($user_info['bouquet']);
                }
                foreach ($user_info['bouquet'] as $id) {
                    if (isset(ipTV_lib::$Bouquets[$id]['streams'])) {
                        $Ff48bb3649e5b84524bd8d318c03db3c = array_merge($Ff48bb3649e5b84524bd8d318c03db3c, ipTV_lib::$Bouquets[$id]['streams']);
                    }
                    if (isset(ipTV_lib::$Bouquets[$id]['series'])) {
                        $A92229131e0f5177a362478fd6f3bd8d = array_merge($A92229131e0f5177a362478fd6f3bd8d, ipTV_lib::$Bouquets[$id]['series']);
                    }
                }
                if (ipTV_lib::$settings['new_sorting_bouquet'] != 1) {
                    $user_info['channel_ids'] = array_unique($Ff48bb3649e5b84524bd8d318c03db3c);
                    $user_info['series_ids'] = array_unique($A92229131e0f5177a362478fd6f3bd8d);
                } else {
                    $user_info['channel_ids'] = array_reverse(array_unique(array_reverse($Ff48bb3649e5b84524bd8d318c03db3c)));
                    $user_info['series_ids'] = array_reverse(array_unique(array_reverse($A92229131e0f5177a362478fd6f3bd8d)));
                }
                if ($getBouquetInfo && !empty($user_info['channel_ids'])) {
                    $user_info['channels'] = array();
                    $output = array();
                    $types = empty($type) ? STREAM_TYPE : $type;
                    foreach ($types as $file) {
                        if (file_exists(TMP_DIR . $file . '_main.php')) {
                            $input = (include TMP_DIR . $file . '_main.php');
                            $output = array_replace($output, $input);
                        }
                    }
                    foreach ($user_info['channel_ids'] as $id) {
                        if (isset($output[$id])) {
                            if ($B5e1c013996afcec27bf828245c3ec37) {
                                $output[$id]['is_adult'] = strtolower($output[$id]['category_name']) == 'for adults' ? 1 : 0;
                            }
                            $user_info['channels'][$id] = $output[$id];
                        }
                    }
                    $output = null;
                    if (!empty($a8851ef591e0cdd9aad6ec4f7bd4b160['items_per_page'])) {
                        $user_info['total_found_rows'] = count($user_info['channels']);
                        $user_info['channels'] = array_slice($user_info['channels'], $a8851ef591e0cdd9aad6ec4f7bd4b160['offset'], $a8851ef591e0cdd9aad6ec4f7bd4b160['items_per_page']);
                    }
                }
            }
            return $user_info;
        }
        return false;
    }
    public static function CategoriesBouq($category_id, $bouquets)
    {
        if (!file_exists(TMP_DIR . 'categories_bouq')) {
            return true;
        }
        if (!is_array($bouquets)) {
            $bouquets = json_decode($bouquets, true);
        }
        $output = unserialize(file_get_contents(TMP_DIR . 'categories_bouq'));
        foreach ($bouquets as $bouquet) {
            if (isset($output[$bouquet])) {
                if (in_array($category_id, $output[$bouquet])) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function GetMagInfo($mag_id = null, $mac = null, $get_ChannelIDS = false, $getBouquetInfo = false, $get_cons = false)
    {
        if (empty($mag_id)) {
            self::$ipTV_db->query('SELECT * FROM `mag_devices` WHERE `mac` = \'%s\'', base64_encode($mac));
        } else {
            self::$ipTV_db->query('SELECT * FROM `mag_devices` WHERE `mag_id` = \'%d\'', $mag_id);
        }
        if (self::$ipTV_db->num_rows() > 0) {
            $maginfo = array();
            $maginfo['mag_device'] = self::$ipTV_db->get_row();
            $maginfo['mag_device']['mac'] = base64_decode($maginfo['mag_device']['mac']);
            $maginfo['user_info'] = array();
            if ($user_info = self::GetUserInfo($maginfo['mag_device']['user_id'], null, null, $get_ChannelIDS, $getBouquetInfo, $get_cons)) {
                $maginfo['user_info'] = $user_info;
            }
            $maginfo['pair_line_info'] = array();
            if (!empty($maginfo['user_info'])) {
                $maginfo['pair_line_info'] = array();
                if (!is_null($maginfo['user_info']['pair_id'])) {
                    if ($user_info = self::GetUserInfo($maginfo['user_info']['pair_id'], null, null, $get_ChannelIDS, $getBouquetInfo, $get_cons)) {
                        $maginfo['pair_line_info'] = $user_info;
                    }
                }
            }
            return $maginfo;
        }
        return false;
    }
    public static function EnigmaDevices($maginfo, $get_ChannelIDS = false, $getBouquetInfo = false, $get_cons = false)
    {
        if (empty($maginfo['device_id'])) {
            self::$ipTV_db->query('SELECT * FROM `enigma2_devices` WHERE `mac` = \'%s\'', $maginfo['mac']);
        } else {
            self::$ipTV_db->query('SELECT * FROM `enigma2_devices` WHERE `device_id` = \'%d\'', $maginfo['device_id']);
        }
        if (self::$ipTV_db->num_rows() > 0) {
            $enigma2devices = array();
            $enigma2devices['enigma2'] = self::$ipTV_db->get_row();
            $enigma2devices['user_info'] = array();
            if ($user_info = self::GetUserInfo($enigma2devices['enigma2']['user_id'], null, null, $get_ChannelIDS, $getBouquetInfo, $get_cons)) {
                $enigma2devices['user_info'] = $user_info;
            }
            $enigma2devices['pair_line_info'] = array();
            if (!empty($enigma2devices['user_info'])) {
                $enigma2devices['pair_line_info'] = array();
                if (!is_null($enigma2devices['user_info']['pair_id'])) {
                    if ($user_info = self::GetUserInfo($enigma2devices['user_info']['pair_id'], null, null, $get_ChannelIDS, $getBouquetInfo, $get_cons)) {
                        $enigma2devices['pair_line_info'] = $user_info;
                    }
                }
            }
            return $enigma2devices;
        }
        return false;
    }
    public static function CloseLastCon($user_id, $max_connections)
    {
        self::$ipTV_db->query('SELECT * FROM `user_activity_now` WHERE `user_id` = \'%d\' ORDER BY activity_id ASC', $user_id);
        $rows = self::$ipTV_db->get_rows();
        $length = count($rows) - $max_connections + 1;
        if ($length <= 0) {
            return;
        }
        $total = 0;
        $connections = array();
        $index = 0;
        while ($index < count($rows) && $index < $length) {
            if ($rows[$index]['hls_end'] == 1) {
                continue;
            }
            if (self::RemoveConnection($rows[$index], false)) {
                ++$total;
                if ($rows[$index]['container'] != 'hls') {
                    $connections[] = $rows[$index]['activity_id'];
                }
            }
            $index++;
        }
        if (!empty($connections)) {
            self::$ipTV_db->query('DELETE FROM `user_activity_now` WHERE `activity_id` IN (' . implode(',', $connections) . ')');
        }
        return $total;
    }
    public static function RemoveConnection($activity_id, $ActionUserActivityNow = true)
    {
        if (empty($activity_id)) {
            return false;
        }
        if (empty($activity_id['activity_id'])) {
            self::$ipTV_db->query('SELECT * FROM `user_activity_now` WHERE `activity_id` = \'%d\'', $activity_id);
            $activity_id = self::$ipTV_db->get_row();
        }
        if (empty($activity_id)) {
            return false;
        }
        if (!($activity_id['container'] == 'rtmp')) {
            if ($activity_id['container'] == 'hls') {
                if (!$ActionUserActivityNow) {
                    self::$ipTV_db->query('UPDATE `user_activity_now` SET `hls_end` = 1 WHERE `activity_id` = \'%d\'', $activity_id['activity_id']);
                }
            } else {
                if ($activity_id['server_id'] == SERVER_ID) {
                    shell_exec("kill -9 {$activity_id['pid']} >/dev/null 2>/dev/null &");
                } else {
                    self::$ipTV_db->query('INSERT INTO `signals` (`pid`,`server_id`,`time`) VALUES(\'%d\',\'%d\',UNIX_TIMESTAMP())', $activity_id['pid'], $activity_id['server_id']);
                }
                if ($activity_id['server_id'] == SERVER_ID) {
                    shell_exec('wget --timeout=2 -O /dev/null -o /dev/null "' . ipTV_lib::$StreamingServers[SERVER_ID]['rtmp_mport_url'] . "control/drop/client?clientid={$activity_id['pid']}\" >/dev/null 2>/dev/null &");
                } else {
                    self::$ipTV_db->query('INSERT INTO `signals` (`pid`,`server_id`,`rtmp`,`time`) VALUES(\'%d\',\'%d\',\'%d\',UNIX_TIMESTAMP())', $activity_id['pid'], $activity_id['server_id'], 1);
                }
            }
            if ($ActionUserActivityNow) {
                self::$ipTV_db->query('DELETE FROM `user_activity_now` WHERE `activity_id` = \'%d\'', $activity_id['activity_id']);
            }
            self::SaveClosedConnection($activity_id['server_id'], $activity_id['user_id'], $activity_id['stream_id'], $activity_id['date_start'], $activity_id['user_agent'], $activity_id['user_ip'], $activity_id['container'], $activity_id['geoip_country_code'], $activity_id['isp'], $activity_id['external_device']);
            return true;
        }
    }
    public static function playDone($pid)
    {
        if (empty($pid)) {
            return false;
        }
        self::$ipTV_db->query('SELECT * FROM `user_activity_now` WHERE `container` = \'rtmp\' AND `pid` = \'%d\' AND `server_id` = \'%d\'', $pid, SERVER_ID);
        if (self::$ipTV_db->num_rows() > 0) {
            $activity_id = self::$ipTV_db->get_row();
            self::$ipTV_db->query('DELETE FROM `user_activity_now` WHERE `activity_id` = \'%d\'', $activity_id['activity_id']);
            self::SaveClosedConnection($activity_id['server_id'], $activity_id['user_id'], $activity_id['stream_id'], $activity_id['date_start'], $activity_id['user_agent'], $activity_id['user_ip'], $activity_id['container'], $activity_id['geoip_country_code'], $activity_id['isp'], $activity_id['external_device']);
            return true;
        }
        return false;
    }
    public static function SaveClosedConnection($server_id, $user_id, $stream_id, $start, $user_agent, $user_ip, $extension, $geoip_country_code, $isp, $external_device = '')
    {
        if (ipTV_lib::$settings['save_closed_connection'] == 0) {
            return;
        }
        $activity_id = array('user_id' => intval($user_id), 'stream_id' => intval($stream_id), 'server_id' => intval($server_id), 'date_start' => intval($start), 'user_agent' => $user_agent, 'user_ip' => htmlentities($user_ip), 'date_end' => time(), 'container' => $extension, 'geoip_country_code' => $geoip_country_code, 'isp' => $isp, 'external_device' => htmlentities($external_device));
        file_put_contents(TMP_DIR . 'connections', base64_encode(json_encode($activity_id)) . '', FILE_APPEND | LOCK_EX);
    }
    public static function ClientLog($stream_id, $user_id, $action, $user_ip, $data = '', $clientLogsSave = false)
    {
        if (ipTV_lib::$settings['client_logs_save'] == 0 && !$clientLogsSave) {
            return;
        }
        $user_agent = !empty($_SERVER['HTTP_USER_AGENT']) ? htmlentities($_SERVER['HTTP_USER_AGENT']) : '';
        $query_string = empty($_SERVER['QUERY_STRING']) ? '' : $_SERVER['QUERY_STRING'];
        $data = array('user_id' => $user_id, 'stream_id' => $stream_id, 'action' => $action, 'query_string' => htmlentities($_SERVER['QUERY_STRING']), 'user_agent' => $user_agent, 'user_ip' => $user_ip, 'time' => time(), 'extra_data' => $data);
        file_put_contents(TMP_DIR . 'client_request.log', base64_encode(json_encode($data)) . '', FILE_APPEND);
    }
    public static function GetSegmentsOfPlaylist($playlist, $prebuffer = 0)
    {
        if (file_exists($playlist)) {
            $source = file_get_contents($playlist);
            if (preg_match_all('/(.*?).ts/', $source, $matches)) {
                if ($prebuffer > 0) {
                    $total_segs = intval($prebuffer / 10);
                    return array_slice($matches[0], -$total_segs);
                } else {
                    preg_match('/_(.*)\\./', array_pop($matches[0]), $pregmatches);
                    return $pregmatches[1];
                }
            }
        }
        return false;
    }
    public static function GeneratePlayListWithAuthenticationAdmin($m3u8_playlist, $password, $stream_id)
    {
        if (file_exists($m3u8_playlist)) {
            $source = file_get_contents($m3u8_playlist);
            if (preg_match_all('/(.*?)\\.ts/', $source, $matches)) {
                foreach ($matches[0] as $match) {
                    $source = str_replace($match, "/streaming/admin_live.php?password={$password}&extension=m3u8&segment={$match}&stream={$stream_id}", $source);
                }
                return $source;
            }
            return false;
        }
    }
    public static function GeneratePlayListWithAuthentication($m3u8_playlist, $username = '', $password = '', $streamID)
    {
        if (file_exists($m3u8_playlist)) {
            $source = file_get_contents($m3u8_playlist);
            if (preg_match_all('/(.*?)\\.ts/', $source, $matches)) {
                foreach ($matches[0] as $match) {
                    $token = md5($match . $username . ipTV_lib::$settings['crypt_load_balancing'] . filesize(STREAMS_PATH . $match));
                    $source = str_replace($match, "/hls/{$username}/{$password}/{$streamID}/{$token}/{$match}", $source);
                }
                return $source;
            }
            return false;
        }
    }
    public static function checkGlobalBlockUA($user_agent)
    {
        $user_agent = strtolower($user_agent);
        $id = false;
        foreach (ipTV_lib::$blockedUA as $key => $value) {
            if (($value['exact_match'] == 1)) { 
                if ($value['blocked_ua'] == $user_agent) {
                    $id = $key;
                    break;  
                }
            }
            else if (stristr($user_agent, $value['blocked_ua'])) {
                $id = $key;
            }    
        }
        if ($id > 0) {
            self::$ipTV_db->query('UPDATE `blocked_user_agents` SET `attempts_blocked` = `attempts_blocked`+1 WHERE `id` = \'%d\'', $id);
            die;
        }
    }
    public static function CheckPidExist($pid, $stream_id, $ffmpeg_path = PHP_BIN)
    {
        if (empty($pid)) {
            return false;
        }
        clearstatcache(true);
        if (file_exists('/proc/' . $pid) && is_readable('/proc/' . $pid . '/exe') && basename(readlink('/proc/' . $pid . '/exe')) == basename($ffmpeg_path)) {
            $value = trim(file_get_contents("/proc/{$pid}/cmdline"));
            if ($value == "XtreamCodes[{$stream_id}]") {
                return true;
            }
        }
        return false;
    }
    public static function checkIsCracked($user_ip)
    {
        $user_ip_file = TMP_DIR . md5($user_ip . 'cracked');
        if (file_exists($user_ip_file)) {
            $contents = intval(file_get_contents($user_ip_file));
            return $contents == 1 ? true : false;
        }
        if (file_exists(TMP_DIR . 'cache_x')) {
            $cachex = json_decode(decrypt_config(base64_decode(file_get_contents(TMP_DIR . 'cache_x')), KEY_CRYPT), true);
            if (is_array($cachex['ips']) && !empty($cachex['ips']) && in_array($user_ip, $cachex['ips'])) {
                file_put_contents($user_ip_file, 1);
                return true;
            }
        }
        file_put_contents($user_ip_file, 0);
        return false;
    }
    public static function CheckPidStreamExist($pid, $stream_id)
    {
        if (empty($pid)) {
            return false;
        }
        clearstatcache(true);
        if (file_exists('/proc/' . $pid) && is_readable('/proc/' . $pid . '/exe')) {
            $value = trim(file_get_contents("/proc/{$pid}/cmdline"));
            if ($value == "XtreamCodesDelay[{$stream_id}]") {
                return true;
            }
        }
        return false;
    }
    public static function CheckPidChannelM3U8Exist($pid, $stream_id, $ffmpeg_path = FFMPEG_PATH)
    {
        if (empty($pid)) {
            return false;
        }
        clearstatcache(true);
        if (file_exists('/proc/' . $pid) && is_readable('/proc/' . $pid . '/exe') && basename(readlink('/proc/' . $pid . '/exe')) == basename($ffmpeg_path)) {
            $value = trim(file_get_contents("/proc/{$pid}/cmdline"));
            if (stristr($value, "/{$stream_id}_.m3u8")) {
                return true;
            }
        }
        return false;
    }
    public static function ps_running($pid, $ffmpeg_path)
    {
        if (empty($pid)) {
            return false;
        }
        clearstatcache(true);
        if (file_exists('/proc/' . $pid) && is_readable('/proc/' . $pid . '/exe') && basename(readlink('/proc/' . $pid . '/exe')) == basename($ffmpeg_path)) {
            return true;
        }
        return false;
    }
    public static function ShowVideo($is_restreamer = 0, $video_id_setting, $video_path_id, $extension = 'ts')
    {
        if ($is_restreamer == 0 && ipTV_lib::$settings[$video_id_setting] == 1) {
            if ($extension == 'm3u8') {
                $extm3u = '#EXTM3U
				#EXT-X-VERSION:3
				#EXT-X-MEDIA-SEQUENCE:0
				#EXT-X-ALLOW-CACHE:YES
				#EXT-X-TARGETDURATION:11
				#EXTINF:10.0,
				' . ipTV_lib::$settings[$video_path_id] . '
				#EXT-X-ENDLIST';
                header('Content-Type: application/x-mpegurl');
                header('Content-Length: ' . strlen($extm3u));
                echo $extm3u;
                die;
            } else {
                header('Content-Type: video/mp2t');
                readfile(ipTV_lib::$settings[$video_path_id]);
                die;
            }
        }
        http_response_code(403);
        die;
    }
    public static function IsValidStream($playlist, $pid)
    {
        return self::ps_running($pid, FFMPEG_PATH) && file_exists($playlist);
    }
    public static function getUserIP()
    {
        return !empty(ipTV_lib::$settings['get_real_ip_client']) && !empty($_SERVER[ipTV_lib::$settings['get_real_ip_client']]) ? $_SERVER[ipTV_lib::$settings['get_real_ip_client']] : $_SERVER['REMOTE_ADDR'];
    }
    public static function GetStreamBitrate($type, $path, $force_duration = null)
    {
        clearstatcache();
        if (!file_exists($path)) {
            return false;
        }
        switch ($type) {
            case 'movie':
                if (!is_null($force_duration)) {
                    sscanf($force_duration, '%d:%d:%d', $hours, $minutes, $seconds);
                    $time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                    $bitrate = round(filesize($path) * 0.008 / $time_seconds);
                }
                break;
            case 'live':
                $fp = fopen($path, 'r');
                $bitrates = array();
                while (!feof($fp)) {
                    $line = trim(fgets($fp));
                    if (stristr($line, 'EXTINF')) {
                        list($trash, $seconds) = explode(':', $line);
                        $seconds = rtrim($seconds, ',');
                        if ($seconds <= 0) {
                            continue;
                        }
                        $segment_file = trim(fgets($fp));
                        if (!file_exists(dirname($path) . '/' . $segment_file)) {
                            fclose($fp);
                            return false;
                        }
                        $segment_size_in_kilobits = filesize(dirname($path) . '/' . $segment_file) * 0.008;
                        $bitrates[] = $segment_size_in_kilobits / $seconds;
                    }
                }
                fclose($fp);
                $bitrate = count($bitrates) > 0 ? round(array_sum($bitrates) / count($bitrates)) : 0;
                break;
        }
        return $bitrate > 0 ? $bitrate : false;
    }
    public static function apiGetISPName($user_ip, $user_agent)
    {
        if (empty($user_ip)) {
            return false;
        }
        if (file_exists(TMP_DIR . md5($user_ip))) {
            return json_decode(file_get_contents(TMP_DIR . md5($user_ip)), true);
        }
        $ctx = stream_context_create(array('http' => array('timeout' => 2)));
        $response = @file_get_contents("http://api.xtream-codes.com/api.php?ip={$user_ip}&user_agent=" . base64_encode($user_agent) . '&block_svp=' . ipTV_lib::$settings['block_svp'], false, $ctx);
        if (!empty($response)) {
            file_put_contents(TMP_DIR . md5($user_ip), $response);
        }
        return json_decode($response, true);
    }
    public static function checkIspIsBlocked($con_isp_name)
    {
        foreach (ipTV_lib::$customISP as $isp) {
            if (strtolower($con_isp_name) == strtolower($isp['isp'])) {
                return $isp['blocked'];
            }
        }
        return false;
    }
}
?>
