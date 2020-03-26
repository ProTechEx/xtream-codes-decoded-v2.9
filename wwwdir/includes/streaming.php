<?php
/*Rev:26.09.18r0*/

class ipTV_streaming
{
    public static $ipTV_db;
    public static $AllowedIPs = array();
    public static function a0218A0e77B606FEF8D734AC4510Ddb1()
    {
        self::$ipTV_db->query('SELECT `ip` FROM `rtmp_ips`');
        return array_merge(array('127.0.0.1'), array_map('gethostbyname', ipTV_lib::array_values_recursive(self::$ipTV_db->get_rows())));
    }
    public static function e8e54de10433EB446982a4Af8ADea379($d38a1c3d822bdbbd61f649f33212ebde, $segment_file)
    {
        if (empty($d38a1c3d822bdbbd61f649f33212ebde['xy_offset'])) {
            $d43f5adb4da33d3ded5cecc9d0c0b4c7 = rand(150, 380);
            $Cca4c808b355e55e72f3bfb3c6603659 = rand(110, 250);
        } else {
            list($d43f5adb4da33d3ded5cecc9d0c0b4c7, $Cca4c808b355e55e72f3bfb3c6603659) = explode('x', $d38a1c3d822bdbbd61f649f33212ebde['xy_offset']);
        }
        passthru(FFMPEG_PATH . ' -nofix_dts -fflags +igndts -copyts -vsync 0 -nostats -nostdin -hide_banner -loglevel quiet -y -i "' . STREAMS_PATH . $segment_file . '" -filter_complex "drawtext=fontfile=' . FFMPEG_FONTS_PATH . ":text='{$d38a1c3d822bdbbd61f649f33212ebde['message']}':fontsize={$d38a1c3d822bdbbd61f649f33212ebde['font_size']}:x={$d43f5adb4da33d3ded5cecc9d0c0b4c7}:y={$Cca4c808b355e55e72f3bfb3c6603659}:fontcolor={$d38a1c3d822bdbbd61f649f33212ebde['font_color']}\" -map 0 -vcodec libx264 -preset ultrafast -acodec copy -scodec copy -mpegts_flags +initial_discontinuity -mpegts_copyts 1 -f mpegts -");
        return true;
    }
    public static function B20C5d64B4C7dBfAffeA9f96934138A4()
    {
        $cde0861dbbf0191a801e3f0699b834ed = array('127.0.0.1', $_SERVER['SERVER_ADDR']);
        if (!file_exists(TMP_DIR . 'cloud_ips') || time() - filemtime(TMP_DIR . 'cloud_ips') >= 600) {
            $f0bdbe56c3b41dee80ecaf635ea527e1 = ipTV_lib::SimpleWebGet('http://xtream-codes.com/cloud_ips');
            if (!empty($f0bdbe56c3b41dee80ecaf635ea527e1)) {
                file_put_contents(TMP_DIR . 'cloud_ips', $f0bdbe56c3b41dee80ecaf635ea527e1);
            }
        }
        if (file_exists(TMP_DIR . 'cloud_ips')) {
            $cde0861dbbf0191a801e3f0699b834ed = array_filter(array_merge($cde0861dbbf0191a801e3f0699b834ed, array_map('trim', file(TMP_DIR . 'cloud_ips'))));
        }
        return array_unique($cde0861dbbf0191a801e3f0699b834ed);
    }
    public static function getAllowedIPsAdmin($fdb41a5a5b49d5e9c80473d2f1b86731 = false)
    {
        if (!empty(self::$AllowedIPs)) {
            return self::$AllowedIPs;
        }
        $cde0861dbbf0191a801e3f0699b834ed = array('127.0.0.1', $_SERVER['SERVER_ADDR']);
        foreach (ipTV_lib::$StreamingServers as $server_id => $server) {
            if (!empty($server['whitelist_ips'])) {
                $cde0861dbbf0191a801e3f0699b834ed = array_merge($cde0861dbbf0191a801e3f0699b834ed, json_decode($server['whitelist_ips'], true));
            }
            $cde0861dbbf0191a801e3f0699b834ed[] = $server['server_ip'];
        }
        if ($fdb41a5a5b49d5e9c80473d2f1b86731) {
            if (!empty(ipTV_lib::$settings['allowed_ips_admin'])) {
                $cde0861dbbf0191a801e3f0699b834ed = array_merge($cde0861dbbf0191a801e3f0699b834ed, explode(',', ipTV_lib::$settings['allowed_ips_admin']));
            }
            self::$ipTV_db->query('SELECT * FROM `xtream_main` WHERE id = 1');
            $fdf8df33b9a361067fee2f972282611d = self::$ipTV_db->get_row();
            if (!empty($fdf8df33b9a361067fee2f972282611d['root_ip'])) {
                $cde0861dbbf0191a801e3f0699b834ed[] = $fdf8df33b9a361067fee2f972282611d['root_ip'];
            }
            self::$ipTV_db->query('SELECT DISTINCT t1.`ip` FROM `reg_users` t1 INNER JOIN `member_groups` t2 ON t2.group_id = t1.member_group_id AND t2.is_admin = 1 AND t1.`last_login` >= \'%d\'', strtotime('-2 hour'));
            $Cfa6e78e5b50872422c16bab31113ce7 = ipTV_lib::array_values_recursive(self::$ipTV_db->get_rows());
            $cde0861dbbf0191a801e3f0699b834ed = array_merge($cde0861dbbf0191a801e3f0699b834ed, $Cfa6e78e5b50872422c16bab31113ce7);
        }
        if (!file_exists(TMP_DIR . 'cloud_ips') || time() - filemtime(TMP_DIR . 'cloud_ips') >= 600) {
            $f0bdbe56c3b41dee80ecaf635ea527e1 = ipTV_lib::SimpleWebGet('http://xtream-codes.com/cloud_ips');
            if (!empty($f0bdbe56c3b41dee80ecaf635ea527e1)) {
                file_put_contents(TMP_DIR . 'cloud_ips', $f0bdbe56c3b41dee80ecaf635ea527e1);
            }
        }
        if (file_exists(TMP_DIR . 'cloud_ips')) {
            $cde0861dbbf0191a801e3f0699b834ed = array_filter(array_merge($cde0861dbbf0191a801e3f0699b834ed, array_map('trim', file(TMP_DIR . 'cloud_ips'))));
        }
        self::$AllowedIPs = $cde0861dbbf0191a801e3f0699b834ed;
        return array_unique($cde0861dbbf0191a801e3f0699b834ed);
    }
    public static function CloseAndTransfer($activity_id)
    {
        file_put_contents(CLOSE_OPEN_CONS_PATH . $activity_id, 1);
    }
    public static function C1B5A5e17240E1fbe7502CCDb57EA2EF($stream_id)
    {
        if (CACHE_STREAMS) {
            if (file_exists(TMP_DIR . $stream_id . '_cacheStream') && time() - filemtime(TMP_DIR . $stream_id . '_cacheStream') <= CACHE_STREAMS_TIME) {
                return unserialize(file_get_contents(TMP_DIR . $stream_id . '_cacheStream'));
            }
        }
        $output = array();
        self::$ipTV_db->query('SELECT * FROM `streams` t1
                                LEFT JOIN `streams_types` t2 ON t2.type_id = t1.type
                                WHERE t1.`id` = \'%d\'', $stream_id);
        if (self::$ipTV_db->num_rows() > 0) {
            $Cb08b127bfe426d7f3ccbd3e38f05471 = self::$ipTV_db->get_row();
            $servers = array();
            if ($Cb08b127bfe426d7f3ccbd3e38f05471['direct_source'] == 0) {
                self::$ipTV_db->query('SELECT * FROM `streams_sys` WHERE `stream_id` = \'%d\'', $stream_id);
                if (self::$ipTV_db->num_rows() > 0) {
                    $servers = self::$ipTV_db->get_rows(true, 'server_id');
                }
            }
            $output['info'] = $Cb08b127bfe426d7f3ccbd3e38f05471;
            $output['servers'] = $servers;
            if (CACHE_STREAMS) {
                file_put_contents(TMP_DIR . $stream_id . '_cacheStream', serialize($output), LOCK_EX);
            }
        }
        return !empty($output) ? $output : false;
    }
    public static function F3c105bCCed491229d4Aed6937F96a8c($stream_id, $extension, $user_info, $user_ip, $geoip_country_code, $external_device = '', $Cf735adc0fa7bac523a6d09af79aa459 = '', $type)
    {
        if ($type == 'archive') {
            self::$ipTV_db->query('SELECT `tv_archive_server_id`,`tv_archive_duration` FROM `streams` WHERE `id` = \'%d\'', $stream_id);
            if (self::$ipTV_db->num_rows() > 0) {
                $row = self::$ipTV_db->get_row();
                if ($row['tv_archive_duration'] > 0 && $row['tv_archive_server_id'] > 0 && array_key_exists($row['tv_archive_server_id'], ipTV_lib::$StreamingServers)) {
                    if ($row['tv_archive_server_id'] != SERVER_ID) {
                        parse_str($_SERVER['QUERY_STRING'], $Cc31a34e0b1fa157d875f9946912d9fa);
                        $D4a67bbd52a22a102a646011a4bec962 = time() + $Cc31a34e0b1fa157d875f9946912d9fa['duration'] * 60;
                        $e3874676e9103a9996301beac4efcde2 = array('hash' => md5(json_encode(array('user_id' => $user_info['id'], 'username' => $user_info['username'], 'password' => $user_info['password'], 'user_ip' => $user_ip, 'live_streaming_pass' => ipTV_lib::$settings['live_streaming_pass'], 'external_device' => $external_device, 'isp' => $Cf735adc0fa7bac523a6d09af79aa459, 'country' => $geoip_country_code, 'stream_id' => $stream_id, 'start' => $Cc31a34e0b1fa157d875f9946912d9fa['start'], 'duration' => $Cc31a34e0b1fa157d875f9946912d9fa['duration'], 'extension' => $Cc31a34e0b1fa157d875f9946912d9fa['extension'], 'time' => $D4a67bbd52a22a102a646011a4bec962))), 'user_id' => $user_info['id'], 'username' => $user_info['username'], 'password' => $user_info['password'], 'time' => $D4a67bbd52a22a102a646011a4bec962, 'external_device' => $external_device, 'isp' => $Cf735adc0fa7bac523a6d09af79aa459, 'country' => $geoip_country_code, 'stream_id' => $stream_id, 'start' => $Cc31a34e0b1fa157d875f9946912d9fa['start'], 'duration' => $Cc31a34e0b1fa157d875f9946912d9fa['duration'], 'extension' => $Cc31a34e0b1fa157d875f9946912d9fa['extension']);
                        $Ad100f7d10d8567e78ddc1e86e51e4a9 = substr($_SERVER['REQUEST_URI'], 1);
                        header('Location: ' . ipTV_lib::$StreamingServers[$row['tv_archive_server_id']]['site_url'] . 'streaming/timeshift.php?token=' . base64_encode(decrypt_config(json_encode($e3874676e9103a9996301beac4efcde2), md5(ipTV_lib::$settings['crypt_load_balancing']))));
                        die;
                    } else {
                        return true;
                    }
                }
            }
            return false;
        }
        $stream = self::C1b5A5E17240e1fbE7502ccdb57eA2Ef($stream_id);
        if (empty($stream)) {
            return false;
        }
        if ($stream['info']['direct_source'] == 1) {
            header('Location: ' . str_replace(' ', '%20', json_decode($stream['info']['stream_source'], true)[0]));
            die;
        }
        $e3215fa97db12812ee074d6c110dea4b = array();
        foreach (ipTV_lib::$StreamingServers as $createdChannelLocation => $server) {
            if (!array_key_exists($createdChannelLocation, $stream['servers']) || !ipTV_lib::$StreamingServers[$createdChannelLocation]['server_online']) {
                continue;
            }
            if ($type == 'movie') {
                if (!empty($stream['servers'][$createdChannelLocation]['pid']) && $stream['servers'][$createdChannelLocation]['to_analyze'] == 0 && $stream['servers'][$createdChannelLocation]['stream_status'] == 0 && $server['timeshift_only'] == 0) {
                    $e3215fa97db12812ee074d6c110dea4b[] = $createdChannelLocation;
                }
            } else {
                if (($stream['servers'][$createdChannelLocation]['on_demand'] == 1 && $stream['servers'][$createdChannelLocation]['pid'] >= 0 && $stream['servers'][$createdChannelLocation]['stream_status'] == 0 || $stream['servers'][$createdChannelLocation]['pid'] > 0 && $stream['servers'][$createdChannelLocation]['stream_status'] == 0) && $stream['servers'][$createdChannelLocation]['to_analyze'] == 0 && time() >= (int) $stream['servers'][$createdChannelLocation]['delay_available_at'] && $server['timeshift_only'] == 0) {
                    $e3215fa97db12812ee074d6c110dea4b[] = $createdChannelLocation;
                }
            }
            //Be93cd107eaa4cfee6f31c440f905d39:
        }
        if (empty($e3215fa97db12812ee074d6c110dea4b)) {
            return false;
        }
        $aab0f9a311e1a69945f2338c5651dd87 = array();
        if (!(ipTV_lib::$settings['online_capacity_interval'] != 0 && file_exists(TMP_DIR . 'servers_capacity') && time() - filemtime(TMP_DIR . 'servers_capacity') <= ipTV_lib::$settings['online_capacity_interval'])) {
            self::$ipTV_db->query('SELECT
                              server_id,
                              COUNT(*) AS online_clients
                            FROM
                              `user_activity_now`
                            GROUP BY
                              server_id
                            ');
            $rows = self::$ipTV_db->get_rows(true, 'server_id');
            if (!(ipTV_lib::$settings['split_by'] == 'band')) {
                if (!(ipTV_lib::$settings['split_by'] == 'maxclients')) {
                    if (ipTV_lib::$settings['split_by'] == 'guar_band') {
                        foreach ($rows as $server_id => $row) {
                            $rows[$server_id]['capacity'] = (double) ($row['online_clients'] / ipTV_lib::$StreamingServers[$server_id]['network_guaranteed_speed']);
                            B51b6287ab4aaff6fd6723951a0ccb9e:
                        }
                    } else {
                        foreach ($rows as $server_id => $row) {
                            $rows[$server_id]['capacity'] = $row['online_clients'];
                            B08617ccbed7448049c5c8d7885fb284:
                        }
                        b7996ad12ff749ea18612a2fbc6c0799:
                        $D8d3ca7afab93e5c110124dc7611906c = array();
                        foreach ($e3215fa97db12812ee074d6c110dea4b as $server_id) {
                            $A8897e590149896423cc3c897a6c6651 = json_decode(ipTV_lib::$StreamingServers[$server_id]['server_hardware'], true);
                            if (!empty($A8897e590149896423cc3c897a6c6651['network_speed'])) {
                                $D8d3ca7afab93e5c110124dc7611906c[$server_id] = (double) $A8897e590149896423cc3c897a6c6651['network_speed'];
                            } else {
                                $D8d3ca7afab93e5c110124dc7611906c[$server_id] = 1000;
                            }
                        }
                        foreach ($rows as $server_id => $row) {
                            $rows[$server_id]['capacity'] = (double) ($row['online_clients'] / $D8d3ca7afab93e5c110124dc7611906c[$server_id]);
                            cb8e96a6955dbb0d22315e774bedfed9:
                        }
                        goto fb8512650313d24ebbda99a7e541af4a;
                        F91d1cc4be7db904cc6b94954999c9d5:
                        foreach ($rows as $server_id => $row) {
                            $rows[$server_id]['capacity'] = (double) ($row['online_clients'] / ipTV_lib::$StreamingServers[$server_id]['total_clients']);
                            C83ea4a3ff37b92b12b18e6cfc823d85:
                        }
                        goto fb8512650313d24ebbda99a7e541af4a;
                    }
                    if (ipTV_lib::$settings['online_capacity_interval'] != 0) {
                        file_put_contents(TMP_DIR . 'servers_capacity', json_encode($rows), LOCK_EX);
                    }
                    C9a1a2dc01ae36bb16ae75537f10f800:
                    $rows = json_decode(file_get_contents(TMP_DIR . 'servers_capacity'), true);
                    f9d0339b29f0177ec3021f090aed97c5:
                    foreach ($e3215fa97db12812ee074d6c110dea4b as $server_id) {
                        $Fe028c63f38ae95c5a00bf47dbfb97a9 = isset($rows[$server_id]['online_clients']) ? $rows[$server_id]['online_clients'] : 0;
                        if ($Fe028c63f38ae95c5a00bf47dbfb97a9 == 0) {
                            $rows[$server_id]['capacity'] = 0;
                        }
                        $aab0f9a311e1a69945f2338c5651dd87[$server_id] = ipTV_lib::$StreamingServers[$server_id]['total_clients'] > 0 && ipTV_lib::$StreamingServers[$server_id]['total_clients'] > $Fe028c63f38ae95c5a00bf47dbfb97a9 ? $rows[$server_id]['capacity'] : false;
                    }
                    $aab0f9a311e1a69945f2338c5651dd87 = array_filter($aab0f9a311e1a69945f2338c5651dd87, 'is_numeric');
                    if (!empty($aab0f9a311e1a69945f2338c5651dd87)) {
                        $aeab45b2c8e6c4f72bec66f6f1a380c0 = array_keys($aab0f9a311e1a69945f2338c5651dd87);
                        $C3a0e56f71bc74a3da1fc67955fac9a6 = array_values($aab0f9a311e1a69945f2338c5651dd87);
                        array_multisort($C3a0e56f71bc74a3da1fc67955fac9a6, SORT_ASC, $aeab45b2c8e6c4f72bec66f6f1a380c0, SORT_ASC);
                        $aab0f9a311e1a69945f2338c5651dd87 = array_combine($aeab45b2c8e6c4f72bec66f6f1a380c0, $C3a0e56f71bc74a3da1fc67955fac9a6);
                        if (!($extension == 'rtmp' && array_key_exists(SERVER_ID, $aab0f9a311e1a69945f2338c5651dd87))) {
                            if ($user_info['force_server_id'] != 0 and array_key_exists($user_info['force_server_id'], $aab0f9a311e1a69945f2338c5651dd87)) {
                                $B0e9c71612dc0f9cbfac184b33ec7cae = $user_info['force_server_id'];
                            } else {
                                $C8a559944c9ad8d120b437a065024840 = array();
                                foreach (array_keys($aab0f9a311e1a69945f2338c5651dd87) as $server_id) {
                                    if (!(ipTV_lib::$StreamingServers[$server_id]['enable_geoip'] == 1)) {
                                        if (!(ipTV_lib::$StreamingServers[$server_id]['enable_isp'] == 1)) {
                                            $C8a559944c9ad8d120b437a065024840[$server_id] = 1;
                                            a581ba46dded3a0d1670459d2009c099:
                                            if (!in_array($geoip_country_code, ipTV_lib::$StreamingServers[$server_id]['geoip_countries'])) {
                                                if (ipTV_lib::$StreamingServers[$server_id]['geoip_type'] == 'strict') {
                                                    unset($aab0f9a311e1a69945f2338c5651dd87[$server_id]);
                                                } else {
                                                    $C8a559944c9ad8d120b437a065024840[$server_id] = ipTV_lib::$StreamingServers[$server_id]['geoip_type'] == 'low_priority' ? 1 : 2;
                                                    Cc791c1e1088ae7c9c260d4ad4bd0ff3:
                                                    $B0e9c71612dc0f9cbfac184b33ec7cae = $server_id;
                                                    break;
                                                    goto D60a6b55cfd678a3cab0a9b2bb62cc0b;
                                                }
                                                c3d2f0f5bac224106a3c47723c91674f:
                                                if (!in_array($Cf735adc0fa7bac523a6d09af79aa459, ipTV_lib::$StreamingServers[$server_id]['isp_names'])) {
                                                    if (ipTV_lib::$StreamingServers[$server_id]['isp_type'] == 'strict') {
                                                        unset($aab0f9a311e1a69945f2338c5651dd87[$server_id]);
                                                    } else {
                                                        $C8a559944c9ad8d120b437a065024840[$server_id] = ipTV_lib::$StreamingServers[$server_id]['isp_type'] == 'low_priority' ? 1 : 2;
                                                        acfe9d9c88cfcc8196451a8ee08ffdbe:
                                                        $B0e9c71612dc0f9cbfac184b33ec7cae = $server_id;
                                                        break;
                                                        goto b5531bc8ac92447022ab49da65c2e7d0;
                                                    }
                                                    df1dab1529b7af4fa42d1ac9d461f6c7:
                                                }
                                            }
                                        }
                                    }
                                }
                                if (empty($C8a559944c9ad8d120b437a065024840) && empty($B0e9c71612dc0f9cbfac184b33ec7cae)) {
                                    return false;
                                }
                                $B0e9c71612dc0f9cbfac184b33ec7cae = empty($B0e9c71612dc0f9cbfac184b33ec7cae) ? array_search(min($C8a559944c9ad8d120b437a065024840), $C8a559944c9ad8d120b437a065024840) : $B0e9c71612dc0f9cbfac184b33ec7cae;
                                Bfc8df779a82702b4f229ad776f10b3d:
                                $B0e9c71612dc0f9cbfac184b33ec7cae = SERVER_ID;
                                goto Abfdd728972a2798d5f98e139390ecf3;
                            }
                            if ($B0e9c71612dc0f9cbfac184b33ec7cae != SERVER_ID) {
                                if ($type == 'live') {
                                    $D4a67bbd52a22a102a646011a4bec962 = $extension == 'm3u8' ? 0 : time() + 6;
                                } else {
                                    $Cb08b127bfe426d7f3ccbd3e38f05471 = json_decode($stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['stream_info'], true);
                                    $D4a67bbd52a22a102a646011a4bec962 = time() + (int) $Cb08b127bfe426d7f3ccbd3e38f05471['of_duration'];
                                }
                                $e3874676e9103a9996301beac4efcde2 = array('hash' => md5(json_encode(array('stream_id' => $stream_id, 'user_id' => $user_info['id'], 'username' => $user_info['username'], 'password' => $user_info['password'], 'user_ip' => $user_ip, 'live_streaming_pass' => ipTV_lib::$settings['live_streaming_pass'], 'pid' => $stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['pid'], 'external_device' => $external_device, 'on_demand' => $stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['on_demand'], 'isp' => $Cf735adc0fa7bac523a6d09af79aa459, 'bitrate' => $stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['bitrate'], 'country' => $geoip_country_code, 'extension' => $extension, 'is_restreamer' => $user_info['is_restreamer'], 'max_connections' => $user_info['max_connections'], 'monitor_pid' => $stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['monitor_pid'], 'time' => $D4a67bbd52a22a102a646011a4bec962))), 'stream_id' => $stream_id, 'user_id' => $user_info['id'], 'time' => $D4a67bbd52a22a102a646011a4bec962, 'pid' => $stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['pid'], 'external_device' => $external_device, 'on_demand' => $stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['on_demand'], 'isp' => $Cf735adc0fa7bac523a6d09af79aa459, 'bitrate' => $stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['bitrate'], 'country' => $geoip_country_code, 'extension' => $extension, 'is_restreamer' => $user_info['is_restreamer'], 'max_connections' => $user_info['max_connections'], 'monitor_pid' => $stream['servers'][$B0e9c71612dc0f9cbfac184b33ec7cae]['monitor_pid']);
                                $Ad100f7d10d8567e78ddc1e86e51e4a9 = substr($_SERVER['REQUEST_URI'], 1);
                                $cb8983ea8c2dc44d7be007079a71c336 = substr_count($Ad100f7d10d8567e78ddc1e86e51e4a9, '?') == 0 ? '?' : '&';
                                header('Location: ' . ipTV_lib::$StreamingServers[$B0e9c71612dc0f9cbfac184b33ec7cae]['site_url'] . $Ad100f7d10d8567e78ddc1e86e51e4a9 . $cb8983ea8c2dc44d7be007079a71c336 . 'token=' . base64_encode(decrypt_config(json_encode($e3874676e9103a9996301beac4efcde2), md5(ipTV_lib::$settings['crypt_load_balancing']))));
                                die;
                            } else {
                                return array_merge($stream['info'], $stream['servers'][SERVER_ID]);
                            }
                            d70ed864d6326141a170ff65fdc47989:
                            return false;
                        }
                    }
                }
            }
        }
    }
    public static function eC7E013cf424bDF03238C1d46AB2a9Ae($stream_id, $a65cbae81b158857c4230683ea812050 = array(), $type = 'movie')
    {
        if (!($type == 'movie')) {
            if ($type == 'series') {
                self::$ipTV_db->query('SELECT series_id FROM `series_episodes` WHERE `stream_id` = \'%d\' LIMIT 1', $stream_id);
                if (self::$ipTV_db->num_rows() > 0) {
                    return in_array(self::$ipTV_db->get_col(), $a65cbae81b158857c4230683ea812050);
                }
            } else {
                B4255ebb0d13ed6640b6609751c38bf6:
                return in_array($stream_id, $a65cbae81b158857c4230683ea812050);
                goto a2519e01e648e11947db8d70937f4195;
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
                    list($Aacb752351b5de80f12830c2026b757e, $B96676565d19827b6e2eda6db94167c0, $cced8089119eaa83c17b19ea19d9af22) = explode(':', $user_info['play_token']);
                    if (!($Aacb752351b5de80f12830c2026b757e == $play_token && $B96676565d19827b6e2eda6db94167c0 >= time() && $cced8089119eaa83c17b19ea19d9af22 == $stream_id)) {
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
                        $C529401a6487f7fc473dd0ec948c66c8 = self::$ipTV_db->get_col();
                        if ($C529401a6487f7fc473dd0ec948c66c8 != $user_ip) {
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
                self::$ipTV_db->query('SELECT *
                                    FROM `access_output` t1
                                    INNER JOIN `user_output` t2 ON t1.access_output_id = t2.access_output_id
                                    WHERE t2.user_id = \'%d\'', $user_info['id']);
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
                        $de64b4b9800f8407c8499fdc13f8e4f6 = self::A477369ead7aA63E77aD3F4634982a8A($user_info['con_isp_name']);
                        if ($user_info['is_restreamer'] == 0 && ipTV_lib::$settings['block_svp'] == 1 && !empty($isp_lock['isp_info']['is_server'])) {
                            $user_info['isp_is_server'] = $isp_lock['isp_info']['is_server'];
                        }
                        if ($user_info['isp_is_server'] == 1) {
                            $user_info['con_isp_type'] = $isp_lock['isp_info']['type'];
                        }
                        if ($de64b4b9800f8407c8499fdc13f8e4f6 !== false) {
                            $user_info['isp_is_server'] = $de64b4b9800f8407c8499fdc13f8e4f6 == 1 ? 1 : 0;
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
                    $e3a76043abaf369f5e7250f23baaf1bb = empty($type) ? STREAM_TYPE : $type;
                    foreach ($e3a76043abaf369f5e7250f23baaf1bb as $Ca434bcc380e9dbd2a3a588f6c32d84f) {
                        if (file_exists(TMP_DIR . $Ca434bcc380e9dbd2a3a588f6c32d84f . '_main.php')) {
                            $input = (include TMP_DIR . $Ca434bcc380e9dbd2a3a588f6c32d84f . '_main.php');
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
    public static function Bc358DB57D4903bFdDF6652560fae708($Fe9028a70727ba5f6b7129f9352b020c, $a9b4c615c3623cb531f93f87f402ccdc)
    {
        if (!file_exists(TMP_DIR . 'categories_bouq')) {
            return true;
        }
        if (!is_array($a9b4c615c3623cb531f93f87f402ccdc)) {
            $a9b4c615c3623cb531f93f87f402ccdc = json_decode($a9b4c615c3623cb531f93f87f402ccdc, true);
        }
        $output = unserialize(file_get_contents(TMP_DIR . 'categories_bouq'));
        foreach ($a9b4c615c3623cb531f93f87f402ccdc as $A4d6fb6268124336b7497e2f7283d227) {
            if (isset($output[$A4d6fb6268124336b7497e2f7283d227])) {
                if (in_array($Fe9028a70727ba5f6b7129f9352b020c, $output[$A4d6fb6268124336b7497e2f7283d227])) {
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
    public static function a2999eEDBe1FF2D9cE52ef5311680Cd4($E84b78040a42ede27e9c6a342a7cf406, $get_ChannelIDS = false, $getBouquetInfo = false, $get_cons = false)
    {
        if (empty($E84b78040a42ede27e9c6a342a7cf406['device_id'])) {
            self::$ipTV_db->query('SELECT * FROM `enigma2_devices` WHERE `mac` = \'%s\'', $E84b78040a42ede27e9c6a342a7cf406['mac']);
        } else {
            self::$ipTV_db->query('SELECT * FROM `enigma2_devices` WHERE `device_id` = \'%d\'', $E84b78040a42ede27e9c6a342a7cf406['device_id']);
        }
        if (self::$ipTV_db->num_rows() > 0) {
            $f80fde69180c88b387a3450bccab89de = array();
            $f80fde69180c88b387a3450bccab89de['enigma2'] = self::$ipTV_db->get_row();
            $f80fde69180c88b387a3450bccab89de['user_info'] = array();
            if ($user_info = self::GetUserInfo($f80fde69180c88b387a3450bccab89de['enigma2']['user_id'], null, null, $get_ChannelIDS, $getBouquetInfo, $get_cons)) {
                $f80fde69180c88b387a3450bccab89de['user_info'] = $user_info;
            }
            $f80fde69180c88b387a3450bccab89de['pair_line_info'] = array();
            if (!empty($f80fde69180c88b387a3450bccab89de['user_info'])) {
                $f80fde69180c88b387a3450bccab89de['pair_line_info'] = array();
                if (!is_null($f80fde69180c88b387a3450bccab89de['user_info']['pair_id'])) {
                    if ($user_info = self::GetUserInfo($f80fde69180c88b387a3450bccab89de['user_info']['pair_id'], null, null, $get_ChannelIDS, $getBouquetInfo, $get_cons)) {
                        $f80fde69180c88b387a3450bccab89de['pair_line_info'] = $user_info;
                    }
                }
            }
            return $f80fde69180c88b387a3450bccab89de;
        }
        return false;
    }
    public static function CloseLastCon($user_id, $d1137e717291f9bcc4c153ac7ea29f57)
    {
        self::$ipTV_db->query('SELECT * FROM `user_activity_now` WHERE `user_id` = \'%d\' ORDER BY activity_id ASC', $user_id);
        $E80aae019385d9c9558555fb07017028 = self::$ipTV_db->get_rows();
        $f5ab0145e33718c87b1ade175ab1ec24 = count($E80aae019385d9c9558555fb07017028) - $d1137e717291f9bcc4c153ac7ea29f57 + 1;
        if ($f5ab0145e33718c87b1ade175ab1ec24 <= 0) {
            return;
        }
        $Fc400afa4288af82b36b1a85c30416c2 = 0;
        $a65cbae81b158857c4230683ea812050 = array();
        $index = 0;
        //f800ffef6c9b9b805f7a8228580f4529:
        while ($index < count($E80aae019385d9c9558555fb07017028) && $index < $f5ab0145e33718c87b1ade175ab1ec24) {
            if ($E80aae019385d9c9558555fb07017028[$index]['hls_end'] == 1) {
                continue;
            }
            if (self::a1eAe86369aa95a55b4BE332F1e22FE3($E80aae019385d9c9558555fb07017028[$index], false)) {
                ++$Fc400afa4288af82b36b1a85c30416c2;
                if ($E80aae019385d9c9558555fb07017028[$index]['container'] != 'hls') {
                    $a65cbae81b158857c4230683ea812050[] = $E80aae019385d9c9558555fb07017028[$index]['activity_id'];
                }
            }
            //D8470f0f5f96e8c8546542dc9f3ff786:
            $index++;
        }
        //b98b4055285f7a72b8202ab9bf2d5b36:
        if (!empty($a65cbae81b158857c4230683ea812050)) {
            self::$ipTV_db->query('DELETE FROM `user_activity_now` WHERE `activity_id` IN (' . implode(',', $a65cbae81b158857c4230683ea812050) . ')');
        }
        return $Fc400afa4288af82b36b1a85c30416c2;
    }
    public static function A1Eae86369aa95A55B4bE332f1e22fE3($Cac03b89c9bf5eedf49be049cd3ad8b2, $F5478657dda4770727c6f4f19bcf419c = true)
    {
        if (empty($Cac03b89c9bf5eedf49be049cd3ad8b2)) {
            return false;
        }
        if (empty($Cac03b89c9bf5eedf49be049cd3ad8b2['activity_id'])) {
            self::$ipTV_db->query('SELECT * FROM `user_activity_now` WHERE `activity_id` = \'%d\'', $Cac03b89c9bf5eedf49be049cd3ad8b2);
            $Cac03b89c9bf5eedf49be049cd3ad8b2 = self::$ipTV_db->get_row();
        }
        if (empty($Cac03b89c9bf5eedf49be049cd3ad8b2)) {
            return false;
        }
        if (!($Cac03b89c9bf5eedf49be049cd3ad8b2['container'] == 'rtmp')) {
            if ($Cac03b89c9bf5eedf49be049cd3ad8b2['container'] == 'hls') {
                if (!$F5478657dda4770727c6f4f19bcf419c) {
                    self::$ipTV_db->query('UPDATE `user_activity_now` SET `hls_end` = 1 WHERE `activity_id` = \'%d\'', $Cac03b89c9bf5eedf49be049cd3ad8b2['activity_id']);
                }
            } else {
                if ($Cac03b89c9bf5eedf49be049cd3ad8b2['server_id'] == SERVER_ID) {
                    shell_exec("kill -9 {$Cac03b89c9bf5eedf49be049cd3ad8b2['pid']} >/dev/null 2>/dev/null &");
                } else {
                    self::$ipTV_db->query('INSERT INTO `signals` (`pid`,`server_id`,`time`) VALUES(\'%d\',\'%d\',UNIX_TIMESTAMP())', $Cac03b89c9bf5eedf49be049cd3ad8b2['pid'], $Cac03b89c9bf5eedf49be049cd3ad8b2['server_id']);
                }
                //bc7cb07d3ab6b11621d212824e65b772:
                if ($Cac03b89c9bf5eedf49be049cd3ad8b2['server_id'] == SERVER_ID) {
                    shell_exec('wget --timeout=2 -O /dev/null -o /dev/null "' . ipTV_lib::$StreamingServers[SERVER_ID]['rtmp_mport_url'] . "control/drop/client?clientid={$Cac03b89c9bf5eedf49be049cd3ad8b2['pid']}\" >/dev/null 2>/dev/null &");
                } else {
                    self::$ipTV_db->query('INSERT INTO `signals` (`pid`,`server_id`,`rtmp`,`time`) VALUES(\'%d\',\'%d\',\'%d\',UNIX_TIMESTAMP())', $Cac03b89c9bf5eedf49be049cd3ad8b2['pid'], $Cac03b89c9bf5eedf49be049cd3ad8b2['server_id'], 1);
                }
                //goto Db2da463655480a0a63d9e81a0940526;
            }
            if ($F5478657dda4770727c6f4f19bcf419c) {
                self::$ipTV_db->query('DELETE FROM `user_activity_now` WHERE `activity_id` = \'%d\'', $Cac03b89c9bf5eedf49be049cd3ad8b2['activity_id']);
            }
            self::A49C2fB1ebA096C52a352A85C8D09D8d($Cac03b89c9bf5eedf49be049cd3ad8b2['server_id'], $Cac03b89c9bf5eedf49be049cd3ad8b2['user_id'], $Cac03b89c9bf5eedf49be049cd3ad8b2['stream_id'], $Cac03b89c9bf5eedf49be049cd3ad8b2['date_start'], $Cac03b89c9bf5eedf49be049cd3ad8b2['user_agent'], $Cac03b89c9bf5eedf49be049cd3ad8b2['user_ip'], $Cac03b89c9bf5eedf49be049cd3ad8b2['container'], $Cac03b89c9bf5eedf49be049cd3ad8b2['geoip_country_code'], $Cac03b89c9bf5eedf49be049cd3ad8b2['isp'], $Cac03b89c9bf5eedf49be049cd3ad8b2['external_device']);
            return true;
        }
    }
    public static function BA58BB30969E80D158Da7Db06421D0d8($pid)
    {
        if (empty($pid)) {
            return false;
        }
        self::$ipTV_db->query('SELECT * FROM `user_activity_now` WHERE `container` = \'rtmp\' AND `pid` = \'%d\' AND `server_id` = \'%d\'', $pid, SERVER_ID);
        if (self::$ipTV_db->num_rows() > 0) {
            $Cac03b89c9bf5eedf49be049cd3ad8b2 = self::$ipTV_db->get_row();
            self::$ipTV_db->query('DELETE FROM `user_activity_now` WHERE `activity_id` = \'%d\'', $Cac03b89c9bf5eedf49be049cd3ad8b2['activity_id']);
            self::a49c2FB1Eba096c52a352a85C8d09D8d($Cac03b89c9bf5eedf49be049cd3ad8b2['server_id'], $Cac03b89c9bf5eedf49be049cd3ad8b2['user_id'], $Cac03b89c9bf5eedf49be049cd3ad8b2['stream_id'], $Cac03b89c9bf5eedf49be049cd3ad8b2['date_start'], $Cac03b89c9bf5eedf49be049cd3ad8b2['user_agent'], $Cac03b89c9bf5eedf49be049cd3ad8b2['user_ip'], $Cac03b89c9bf5eedf49be049cd3ad8b2['container'], $Cac03b89c9bf5eedf49be049cd3ad8b2['geoip_country_code'], $Cac03b89c9bf5eedf49be049cd3ad8b2['isp'], $Cac03b89c9bf5eedf49be049cd3ad8b2['external_device']);
            return true;
        }
        return false;
    }
    public static function A49c2Fb1ebA096C52a352A85C8d09D8D($server_id, $user_id, $stream_id, $start, $user_agent, $user_ip, $extension, $geoip_country_code, $isp, $external_device = '')
    {
        if (ipTV_lib::$settings['save_closed_connection'] == 0) {
            return;
        }
        $Cac03b89c9bf5eedf49be049cd3ad8b2 = array('user_id' => intval($user_id), 'stream_id' => intval($stream_id), 'server_id' => intval($server_id), 'date_start' => intval($start), 'user_agent' => $user_agent, 'user_ip' => htmlentities($user_ip), 'date_end' => time(), 'container' => $extension, 'geoip_country_code' => $geoip_country_code, 'isp' => $isp, 'external_device' => htmlentities($external_device));
        file_put_contents(TMP_DIR . 'offline_cons', base64_encode(json_encode($Cac03b89c9bf5eedf49be049cd3ad8b2)) . '', FILE_APPEND | LOCK_EX);
    }
    public static function ClientLog($stream_id, $A46fd5eee12ebb82d63744d80a987c05, $action, $f090771de8f10383e371fc62e73e226f, $data = '', $aa1dc37d3856d0124e1c6669bb98c933 = false)
    {
        if (ipTV_lib::$settings['client_logs_save'] == 0 && !$aa1dc37d3856d0124e1c6669bb98c933) {
            return;
        }
        $user_agent = !empty($_SERVER['HTTP_USER_AGENT']) ? htmlentities($_SERVER['HTTP_USER_AGENT']) : '';
        $edd8801e02c613e4742a16fe132ace86 = empty($_SERVER['QUERY_STRING']) ? '' : $_SERVER['QUERY_STRING'];
        $data = array('user_id' => $A46fd5eee12ebb82d63744d80a987c05, 'stream_id' => $stream_id, 'action' => $action, 'query_string' => htmlentities($_SERVER['QUERY_STRING']), 'user_agent' => $user_agent, 'user_ip' => $f090771de8f10383e371fc62e73e226f, 'time' => time(), 'extra_data' => $data);
        file_put_contents(TMP_DIR . 'client_request.log', base64_encode(json_encode($data)) . '
', FILE_APPEND);
    }
    public static function b8430212cC8301200A4976571DbA202C($playlist, $af46c19ad71d32d62575a30b0e1b2f2a = 0)
    {
        if (file_exists($playlist)) {
            $F3803fa85b38b65447e6d438f8e9176a = file_get_contents($playlist);
            if (preg_match_all('/(.*?).ts/', $F3803fa85b38b65447e6d438f8e9176a, $ae37877cee3bc97c8cfa6ec5843993ed)) {
                if ($af46c19ad71d32d62575a30b0e1b2f2a > 0) {
                    $D8eed577376997b90ec084598ddf5bab = intval($af46c19ad71d32d62575a30b0e1b2f2a / 10);
                    return array_slice($ae37877cee3bc97c8cfa6ec5843993ed[0], -$D8eed577376997b90ec084598ddf5bab);
                } else {
                    preg_match('/_(.*)\\./', array_pop($ae37877cee3bc97c8cfa6ec5843993ed[0]), $adb24597b0e7956b0f3baad7c260916d);
                    return $adb24597b0e7956b0f3baad7c260916d[1];
                }
            }
        }
        return false;
    }
    public static function B18c6BF534aE0B9B94354DB508D52A48($D556aa916c3639fd698001b7fef2c4ea, $password, $stream_id)
    {
        if (file_exists($D556aa916c3639fd698001b7fef2c4ea)) {
            $F3803fa85b38b65447e6d438f8e9176a = file_get_contents($D556aa916c3639fd698001b7fef2c4ea);
            if (preg_match_all('/(.*?)\\.ts/', $F3803fa85b38b65447e6d438f8e9176a, $ae37877cee3bc97c8cfa6ec5843993ed)) {
                foreach ($ae37877cee3bc97c8cfa6ec5843993ed[0] as $A35c84197cc933d0578522463b946147) {
                    $F3803fa85b38b65447e6d438f8e9176a = str_replace($A35c84197cc933d0578522463b946147, "/streaming/admin_live.php?password={$password}&extension=m3u8&segment={$A35c84197cc933d0578522463b946147}&stream={$stream_id}", $F3803fa85b38b65447e6d438f8e9176a);
                    //D96ec8b4163d7924093b501a8513de78:
                }
                return $F3803fa85b38b65447e6d438f8e9176a;
            }
            return false;
        }
    }
    public static function E7917f7f55606C448105A9A4016538b9($D556aa916c3639fd698001b7fef2c4ea, $username = '', $password = '', $Fa6494e569aed942b375e025f096b099)
    {
        if (file_exists($D556aa916c3639fd698001b7fef2c4ea)) {
            $F3803fa85b38b65447e6d438f8e9176a = file_get_contents($D556aa916c3639fd698001b7fef2c4ea);
            if (preg_match_all('/(.*?)\\.ts/', $F3803fa85b38b65447e6d438f8e9176a, $ae37877cee3bc97c8cfa6ec5843993ed)) {
                foreach ($ae37877cee3bc97c8cfa6ec5843993ed[0] as $A35c84197cc933d0578522463b946147) {
                    $Aacb752351b5de80f12830c2026b757e = md5($A35c84197cc933d0578522463b946147 . $username . ipTV_lib::$settings['crypt_load_balancing'] . filesize(STREAMS_PATH . $A35c84197cc933d0578522463b946147));
                    $F3803fa85b38b65447e6d438f8e9176a = str_replace($A35c84197cc933d0578522463b946147, "/hls/{$username}/{$password}/{$Fa6494e569aed942b375e025f096b099}/{$Aacb752351b5de80f12830c2026b757e}/{$A35c84197cc933d0578522463b946147}", $F3803fa85b38b65447e6d438f8e9176a);
                }
                return $F3803fa85b38b65447e6d438f8e9176a;
            }
            return false;
        }
    }
    public static function checkGlobalBlockUA($user_agent)
    {
        $user_agent = strtolower($user_agent);
        $ac3e12febbb571ae6bbc11a06c8f5331 = false;
        foreach (ipTV_lib::$blockedUA as $key => $e5703a07efed268fcb5c4c86a4cab348) {
            if (($e5703a07efed268fcb5c4c86a4cab348['exact_match'] == 1)) { 
                //B9542b8b744fb7f3a9b55871b86d0d8f:
                if ($e5703a07efed268fcb5c4c86a4cab348['blocked_ua'] == $user_agent) {
                    $ac3e12febbb571ae6bbc11a06c8f5331 = $key;
                    break;  
                }
            }
            else if (stristr($user_agent, $e5703a07efed268fcb5c4c86a4cab348['blocked_ua'])) {
                $ac3e12febbb571ae6bbc11a06c8f5331 = $key;
                //goto df050b7888b0372781dc39c439b3ca3a;
            }    
        }
        if ($ac3e12febbb571ae6bbc11a06c8f5331 > 0) {
            self::$ipTV_db->query('UPDATE `blocked_user_agents` SET `attempts_blocked` = `attempts_blocked`+1 WHERE `id` = \'%d\'', $ac3e12febbb571ae6bbc11a06c8f5331);
            die;
        }
    }
    public static function CdA72bC41975C364bc559db25648a5b2($pid, $stream_id, $ffmpeg_path = PHP_BIN)
    {
        if (empty($pid)) {
            return false;
        }
        clearstatcache(true);
        if (file_exists('/proc/' . $pid) && is_readable('/proc/' . $pid . '/exe') && basename(readlink('/proc/' . $pid . '/exe')) == basename($ffmpeg_path)) {
            $ea5780c60b0a2afa62b1d8395f019e9a = trim(file_get_contents("/proc/{$pid}/cmdline"));
            if ($ea5780c60b0a2afa62b1d8395f019e9a == "XtreamCodes[{$stream_id}]") {
                return true;
            }
        }
        return false;
    }
    public static function C57799E5196664CB99139813250673e2($user_ip)
    {
        $user_ip_file = TMP_DIR . md5($user_ip . 'cracked');
        if (file_exists($user_ip_file)) {
            $f0bdbe56c3b41dee80ecaf635ea527e1 = intval(file_get_contents($user_ip_file));
            return $f0bdbe56c3b41dee80ecaf635ea527e1 == 1 ? true : false;
        }
        if (file_exists(TMP_DIR . 'CACHE_x')) {
            $E39de148e1c9c7c038772e11158786c8 = json_decode(decrypt_config(base64_decode(file_get_contents(TMP_DIR . 'CACHE_x')), KEY_CRYPT), true);
            if (is_array($E39de148e1c9c7c038772e11158786c8['ips']) && !empty($E39de148e1c9c7c038772e11158786c8['ips']) && in_array($user_ip, $E39de148e1c9c7c038772e11158786c8['ips'])) {
                file_put_contents($user_ip_file, 1);
                return true;
            }
        }
        file_put_contents($user_ip_file, 0);
        return false;
    }
    public static function F4a9B20600bb9A41Ed2391b0ea000578($pid, $stream_id)
    {
        if (empty($pid)) {
            return false;
        }
        clearstatcache(true);
        if (file_exists('/proc/' . $pid) && is_readable('/proc/' . $pid . '/exe')) {
            $ea5780c60b0a2afa62b1d8395f019e9a = trim(file_get_contents("/proc/{$pid}/cmdline"));
            if ($ea5780c60b0a2afa62b1d8395f019e9a == "XtreamCodesDelay[{$stream_id}]") {
                return true;
            }
        }
        return false;
    }
    public static function BCaA9B8a7B46eb36CD507A218fa64474($pid, $stream_id, $ffmpeg_path = FFMPEG_PATH)
    {
        if (empty($pid)) {
            return false;
        }
        clearstatcache(true);
        if (file_exists('/proc/' . $pid) && is_readable('/proc/' . $pid . '/exe') && basename(readlink('/proc/' . $pid . '/exe')) == basename($ffmpeg_path)) {
            $ea5780c60b0a2afa62b1d8395f019e9a = trim(file_get_contents("/proc/{$pid}/cmdline"));
            if (stristr($ea5780c60b0a2afa62b1d8395f019e9a, "/{$stream_id}_.m3u8")) {
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
    public static function a477369eaD7aa63E77AD3F4634982a8a($Cf735adc0fa7bac523a6d09af79aa459)
    {
        foreach (ipTV_lib::$customISP as $isp) {
            if (strtolower($Cf735adc0fa7bac523a6d09af79aa459) == strtolower($isp['isp'])) {
                return $isp['blocked'];
            }
        }
        return false;
    }
}
?>
