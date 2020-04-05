<?php

class ipTV_stream
{
    public static $ipTV_db;
    static function ($sources)
    {
        if (empty($sources)) {
            return;
        }
        foreach ($sources as $source) {
            if (file_exists(STREAMS_PATH . md5($source))) {
                unlink(STREAMS_PATH . md5($source));
            }
        }
    }
    static function EeeD2f36fa093b45bC2D622ed0231684($stream_id)
    {
        self::$ipTV_db->query('
                SELECT * FROM `streams` t1 
                LEFT JOIN `transcoding_profiles` t3 ON t1.transcode_profile_id = t3.profile_id
                WHERE t1.`id` = \'%d\'', $stream_id);
        $stream = self::$ipTV_db->get_row();
        $stream['cchannel_rsources'] = json_decode($stream['cchannel_rsources'], true);
        $stream['stream_source'] = json_decode($stream['stream_source'], true);
        $stream['pids_create_channel'] = json_decode($stream['pids_create_channel'], true);
        $stream['transcode_attributes'] = json_decode($stream['profile_options'], true);
        if (!array_key_exists('-acodec', $stream['transcode_attributes'])) {
            $stream['transcode_attributes']['-acodec'] = 'copy';
        }
        if (!array_key_exists('-vcodec', $stream['transcode_attributes'])) {
            $stream['transcode_attributes']['-vcodec'] = 'copy';
        }
        $ffmpegCommand = FFMPEG_PATH . ' -fflags +genpts -async 1 -y -nostdin -hide_banner -loglevel quiet -i "{INPUT}" ';
        $ffmpegCommand .= implode(' ', self::F6664C80BDe3e9BbE2C12ceB906D5A11($stream['transcode_attributes'])) . ' ';
        $ffmpegCommand .= '-strict -2 -mpegts_flags +initial_discontinuity -f mpegts "' . CREATED_CHANNELS . $stream_id . '_{INPUT_MD5}.ts" >/dev/null 2>/dev/null & jobs -p';
        $result = array_diff($stream['stream_source'], $stream['cchannel_rsources']);
        $json_string_data = '';
        foreach ($stream['stream_source'] as $source) {
            $json_string_data .= 'file \'' . CREATED_CHANNELS . $stream_id . '_' . md5($source) . '.ts\'';
        }
        $json_string_data = base64_encode($json_string_data);
        if ((!empty($result) || $stream['stream_source'] !== $stream['cchannel_rsources'])) {
            foreach ($result as $source) {
                $stream['pids_create_channel'][] = ipTV_servers::RunCommandServer($stream['created_channel_location'], str_ireplace(array('{INPUT}', '{INPUT_MD5}'), array($source, md5($source)), $ffmpegCommand), 'raw')[$stream['created_channel_location']];
            }
            self::$ipTV_db->query('UPDATE `streams` SET pids_create_channel = \'%s\',`cchannel_rsources` = \'%s\' WHERE `id` = \'%d\'', json_encode($stream['pids_create_channel']), json_encode($stream['stream_source']), $stream_id);
            ipTV_servers::RunCommandServer($stream['created_channel_location'], "echo {$json_string_data} | base64 --decode > \"" . CREATED_CHANNELS . $stream_id . '_.list"', 'raw');
            return 1;
        }
        else if (!empty($stream['pids_create_channel'])) {
            foreach ($stream['pids_create_channel'] as $key => $pid) {
                if (!ipTV_servers::PidsChannels($stream['created_channel_location'], $pid, FFMPEG_PATH)) {
                    unset($stream['pids_create_channel'][$key]);
                }
            }
            self::$ipTV_db->query('UPDATE `streams` SET pids_create_channel = \'%s\' WHERE `id` = \'%d\'', json_encode($stream['pids_create_channel']), $stream_id);
            return empty($stream['pids_create_channel']) ? 2 : 1;
        } 
    
        return 2;    
    }
    static function E0A1164567005185e0818F081674E240($C0379dd6700deb6b1021ed6026f648b9, $Aa894918d6f628c53ace2682189e44d5, $f84c1c6145bb73410b3ea7c0f8b4a9f3 = array(), $A7da0ef4553f5ea253d3907a7c9ef7f0 = '')
    {
        $stream_max_analyze = abs(intval(ipTV_lib::$settings['stream_max_analyze']));
        $probesize = abs(intval(ipTV_lib::$settings['probesize']));
        $timeout = intval($stream_max_analyze / 1000000) + 5;
        $command = "{$A7da0ef4553f5ea253d3907a7c9ef7f0}/usr/bin/timeout {$timeout}s " . FFPROBE_PATH . " -probesize {$probesize} -analyzeduration {$stream_max_analyze} " . implode(' ', $f84c1c6145bb73410b3ea7c0f8b4a9f3) . " -i \"{$C0379dd6700deb6b1021ed6026f648b9}\" -v quiet -print_format json -show_streams -show_format";
        $result = ipTV_servers::RunCommandServer($Aa894918d6f628c53ace2682189e44d5, $command, 'raw', $timeout * 2, $timeout * 2);
        return self::cCBD051C8a19a02Dc5B6dB256Ae31c07(json_decode($result[$Aa894918d6f628c53ace2682189e44d5], true));
    }
    public static function CcBd051c8a19a02dc5B6dB256AE31c07($d8c887d4a07ddc3992dca7f1d440e7de)
    {
        if (!empty($d8c887d4a07ddc3992dca7f1d440e7de)) {
            if (!empty($d8c887d4a07ddc3992dca7f1d440e7de['codecs'])) {
                return $d8c887d4a07ddc3992dca7f1d440e7de;
            }
            $output = array();
            $output['codecs']['video'] = '';
            $output['codecs']['audio'] = '';
            $output['container'] = $d8c887d4a07ddc3992dca7f1d440e7de['format']['format_name'];
            $output['filename'] = $d8c887d4a07ddc3992dca7f1d440e7de['format']['filename'];
            $output['bitrate'] = !empty($d8c887d4a07ddc3992dca7f1d440e7de['format']['bit_rate']) ? $d8c887d4a07ddc3992dca7f1d440e7de['format']['bit_rate'] : null;
            $output['of_duration'] = !empty($d8c887d4a07ddc3992dca7f1d440e7de['format']['duration']) ? $d8c887d4a07ddc3992dca7f1d440e7de['format']['duration'] : 'N/A';
            $output['duration'] = !empty($d8c887d4a07ddc3992dca7f1d440e7de['format']['duration']) ? gmdate('H:i:s', intval($d8c887d4a07ddc3992dca7f1d440e7de['format']['duration'])) : 'N/A';
            foreach ($d8c887d4a07ddc3992dca7f1d440e7de['streams'] as $E91d1cd26e7223a0f44a617b8ab51d10) {
                if (!isset($E91d1cd26e7223a0f44a617b8ab51d10['codec_type'])) {
                    continue;
                }
                if ($E91d1cd26e7223a0f44a617b8ab51d10['codec_type'] != 'audio' && $E91d1cd26e7223a0f44a617b8ab51d10['codec_type'] != 'video') {
                    continue;
                }
                $output['codecs'][$E91d1cd26e7223a0f44a617b8ab51d10['codec_type']] = $E91d1cd26e7223a0f44a617b8ab51d10;
            }
            return $output;
        }
        return false;
    }
    static function stopStream($stream_id, $reset_stream_sys = false)
    {
        if (file_exists("/home/xtreamcodes/iptv_xtream_codes/streams/{$stream_id}.monitor")) {
            $e9d30118d498945b35ee33aa90ed9822 = intval(file_get_contents("/home/xtreamcodes/iptv_xtream_codes/streams/{$stream_id}.monitor"));
            if (self::F198E55FC8231996C50ee056Ac4226E0($e9d30118d498945b35ee33aa90ed9822, "XtreamCodes[{$stream_id}]")) {
                posix_kill($e9d30118d498945b35ee33aa90ed9822, 9);
            }
        }
        if (file_exists(STREAMS_PATH . $stream_id . '_.pid')) {
            $pid = intval(file_get_contents(STREAMS_PATH . $stream_id . '_.pid'));
            if (self::F198E55fC8231996C50eE056aC4226e0($pid, "{$stream_id}_.m3u8")) {
                posix_kill($pid, 9);
            }
        }
        shell_exec('rm -f ' . STREAMS_PATH . $stream_id . '_*');
        if ($reset_stream_sys) {
            shell_exec('rm -f ' . DELAY_STREAM . $stream_id . '_*');
            self::$ipTV_db->query('UPDATE `streams_sys` SET `bitrate` = NULL,`current_source` = NULL,`to_analyze` = 0,`pid` = NULL,`stream_started` = NULL,`stream_info` = NULL,`stream_status` = 0,`monitor_pid` = NULL WHERE `stream_id` = \'%d\' AND `server_id` = \'%d\'', $stream_id, SERVER_ID);
        }
    }
    static function F198e55Fc8231996C50eE056ac4226e0($pid, $search)
    {
        if (file_exists('/proc/' . $pid)) {
            $value = trim(file_get_contents("/proc/{$pid}/cmdline"));
            if (stristr($value, $search)) {
                return true;
            }
        }
        return false;
    }
    static function startStream($stream_id, $delay_minutes = 0)
    {
        $stream_lock_file = STREAMS_PATH . $stream_id . '.lock';
        $fp = fopen($stream_lock_file, 'a+');
        if (flock($fp, LOCK_EX | LOCK_NB)) {
            $delay_minutes = intval($delay_minutes);
            shell_exec(PHP_BIN . ' ' . TOOLS_PATH . "stream_monitor.php {$stream_id} {$delay_minutes} >/dev/null 2>/dev/null &");
            usleep(300);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
    static function stopVODstream($stream_id)
    {
        if (file_exists(MOVIES_PATH . $stream_id . '_.pid')) {
            $pid = (int) file_get_contents(MOVIES_PATH . $stream_id . '_.pid');
            posix_kill($pid, 9);
        }
        shell_exec('rm -f ' . MOVIES_PATH . $stream_id . '.*');
        self::$ipTV_db->query('UPDATE `streams_sys` SET `bitrate` = NULL,`current_source` = NULL,`to_analyze` = 0,`pid` = NULL,`stream_started` = NULL,`stream_info` = NULL,`stream_status` = 0 WHERE `stream_id` = \'%d\' AND `server_id` = \'%d\'', $stream_id, SERVER_ID);
    }
    static function startVODstream($stream_id)
    {
        $stream = array();
        self::$ipTV_db->query('SELECT * FROM `streams` t1 
                               INNER JOIN `streams_types` t2 ON t2.type_id = t1.type AND t2.live = 0
                               LEFT JOIN `transcoding_profiles` t4 ON t1.transcode_profile_id = t4.profile_id 
                               WHERE t1.direct_source = 0 AND t1.id = \'%d\'', $stream_id);
        if (self::$ipTV_db->num_rows() <= 0) {
            return false;
        }
        $stream['stream_info'] = self::$ipTV_db->get_row();
        $ecb89a457f7f7216f5564141edfd6269 = json_decode($stream['stream_info']['target_container'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $stream['stream_info']['target_container'] = $ecb89a457f7f7216f5564141edfd6269;
        } else {
            $stream['stream_info']['target_container'] = array($stream['stream_info']['target_container']);
        }
        self::$ipTV_db->query('SELECT * FROM `streams_sys` WHERE stream_id  = \'%d\' AND `server_id` = \'%d\'', $stream_id, SERVER_ID);
        if (self::$ipTV_db->num_rows() <= 0) {
            return false;
        }
        $stream['server_info'] = self::$ipTV_db->get_row();
        self::$ipTV_db->query('SELECT t1.*, t2.* FROM `streams_options` t1, `streams_arguments` t2 WHERE t1.stream_id = \'%d\' AND t1.argument_id = t2.id', $stream_id);
        $stream['stream_arguments'] = self::$ipTV_db->get_rows();
        $stream_source = urldecode(json_decode($stream['stream_info']['stream_source'], true)[0]);
        if (substr($stream_source, 0, 2) == 's:') {
            $a3cc823f429df879ce4a238c730d5eb1 = explode(':', $stream_source, 3);
            $dc4f2a655eb3f009a9e741402d02f5fb = $a3cc823f429df879ce4a238c730d5eb1[1];
            if ($dc4f2a655eb3f009a9e741402d02f5fb != SERVER_ID) {
                $ed147a39fb35be93248b6f1c206a8023 = ipTV_lib::$StreamingServers[$dc4f2a655eb3f009a9e741402d02f5fb]['api_url'] . '&action=getFile&filename=' . urlencode($a3cc823f429df879ce4a238c730d5eb1[2]);
            } else {
                $ed147a39fb35be93248b6f1c206a8023 = $a3cc823f429df879ce4a238c730d5eb1[2];
            }
            $server_protocol = null;
        } else {
            $server_protocol = substr($stream_source, 0, strpos($stream_source, '://'));
            $ed147a39fb35be93248b6f1c206a8023 = str_replace(' ', '%20', $stream_source);
            $be9f906faa527985765b1d8c897fb13a = implode(' ', self::eA860C1D3851c46D06e64911E3602768($stream['stream_arguments'], $server_protocol, 'fetch'));
        }

        if (!(isset($dc4f2a655eb3f009a9e741402d02f5fb) && $dc4f2a655eb3f009a9e741402d02f5fb == SERVER_ID && $stream['stream_info']['movie_symlink'] == 1)) {
            $fd91db723d1a9a2b33d242b8850c593f = json_decode($stream['stream_info']['movie_subtitles'], true);
            $cded98e960569a6cd37bbbc155e6a799 = '';
            $index = 0;

            while ($index < count($fd91db723d1a9a2b33d242b8850c593f['files'])) {
                $f26614792b40297912d260cb0d2fa273 = urldecode($fd91db723d1a9a2b33d242b8850c593f['files'][$index]);
                $d8143e98f4313d9c05f0b2697179789c = $fd91db723d1a9a2b33d242b8850c593f['charset'][$index];
                if ($fd91db723d1a9a2b33d242b8850c593f['location'] == SERVER_ID) {
                    $cded98e960569a6cd37bbbc155e6a799 .= "-sub_charenc \"{$d8143e98f4313d9c05f0b2697179789c}\" -i \"{$f26614792b40297912d260cb0d2fa273}\" ";
                } else {
                    $cded98e960569a6cd37bbbc155e6a799 .= "-sub_charenc \"{$d8143e98f4313d9c05f0b2697179789c}\" -i \"" . ipTV_lib::$StreamingServers[$fd91db723d1a9a2b33d242b8850c593f['location']]['api_url'] . '&action=getFile&filename=' . urlencode($f26614792b40297912d260cb0d2fa273) . '" ';
                }
                $index++;
            }

            $f2130ba0f82d2308b743977b2ba5eaa9 = '';
            $index = 0;

            while ($index < count($fd91db723d1a9a2b33d242b8850c593f['files'])) {
                $f2130ba0f82d2308b743977b2ba5eaa9 .= '-map ' . ($index + 1) . " -metadata:s:s:{$index} title={$fd91db723d1a9a2b33d242b8850c593f['names'][$index]} -metadata:s:s:{$index} language={$fd91db723d1a9a2b33d242b8850c593f['names'][$index]} ";
                $index++;
            }

            $af428179032a83d9ec1df565934b1c89 = FFMPEG_PATH . " -y -nostdin -hide_banner -loglevel warning -err_detect ignore_err {FETCH_OPTIONS} -fflags +genpts -async 1 {READ_NATIVE} -i \"{STREAM_SOURCE}\" {$cded98e960569a6cd37bbbc155e6a799}";
            $feb3f2070e6ccf961f6265281e875b1a = '';
            if ($stream['stream_info']['read_native'] == 1) {
                $feb3f2070e6ccf961f6265281e875b1a = '-re';
            }
            if ($stream['stream_info']['enable_transcode'] == 1) {
                if ($stream['stream_info']['transcode_profile_id'] == -1) {
                    $stream['stream_info']['transcode_attributes'] = array_merge(self::ea860c1d3851c46d06E64911e3602768($stream['stream_arguments'], $server_protocol, 'transcode'), json_decode($stream['stream_info']['transcode_attributes'], true));
                } else {
                    $stream['stream_info']['transcode_attributes'] = json_decode($stream['stream_info']['profile_options'], true);
                }
            } else {
                $stream['stream_info']['transcode_attributes'] = array();
            }
            $map = '-map 0 -copy_unknown ';
            if (empty($stream['stream_info']['custom_map'])) {
                $map = $stream['stream_info']['custom_map'] . ' -copy_unknown ';
            }
            else if ($stream['stream_info']['remove_subtitles'] == 1) {
                $map = '-map 0:a -map 0:v';
            }

            if (!array_key_exists('-acodec', $stream['stream_info']['transcode_attributes'])) {
                $stream['stream_info']['transcode_attributes']['-acodec'] = 'copy';
            }
            if (!array_key_exists('-vcodec', $stream['stream_info']['transcode_attributes'])) {
                $stream['stream_info']['transcode_attributes']['-vcodec'] = 'copy';
            }
            $A7c6258649492b26d77c75c60c793409 = array();
            foreach ($stream['stream_info']['target_container'] as $container_priority) {
                $A7c6258649492b26d77c75c60c793409[$container_priority] = "-movflags +faststart -dn {$map} -ignore_unknown {$f2130ba0f82d2308b743977b2ba5eaa9} " . MOVIES_PATH . $stream_id . '.' . $container_priority . ' ';
            }
            foreach ($A7c6258649492b26d77c75c60c793409 as $output_key => $cd7bafd64552e6ca58318f09800cbddd) {
                if (($output_key == 'mp4')) { 
                    $stream['stream_info']['transcode_attributes']['-scodec'] = 'mov_text';
                } else if ($output_key == 'mkv') {
                    $stream['stream_info']['transcode_attributes']['-scodec'] = 'srt';
                } else {
                    $stream['stream_info']['transcode_attributes']['-scodec'] = 'copy';
                }
                $af428179032a83d9ec1df565934b1c89 .= implode(' ', self::F6664c80BDe3e9bbe2c12CEb906D5A11($stream['stream_info']['transcode_attributes'])) . ' ';
                $af428179032a83d9ec1df565934b1c89 .= $cd7bafd64552e6ca58318f09800cbddd;
            }
            
            $af428179032a83d9ec1df565934b1c89 .= ' >/dev/null 2>' . MOVIES_PATH . $stream_id . '.errors & echo $! > ' . MOVIES_PATH . $stream_id . '_.pid';
            $af428179032a83d9ec1df565934b1c89 = str_replace(array('{FETCH_OPTIONS}', '{STREAM_SOURCE}', '{READ_NATIVE}'), array(empty($be9f906faa527985765b1d8c897fb13a) ? '' : $be9f906faa527985765b1d8c897fb13a, $ed147a39fb35be93248b6f1c206a8023, empty($stream['stream_info']['custom_ffmpeg']) ? $feb3f2070e6ccf961f6265281e875b1a : ''), $af428179032a83d9ec1df565934b1c89);
            $af428179032a83d9ec1df565934b1c89 = "ln -s \"{$ed147a39fb35be93248b6f1c206a8023}\" " . MOVIES_PATH . $stream_id . '.' . pathinfo($ed147a39fb35be93248b6f1c206a8023, PATHINFO_EXTENSION) . ' >/dev/null 2>/dev/null & echo $! > ' . MOVIES_PATH . $stream_id . '_.pid';
            shell_exec($af428179032a83d9ec1df565934b1c89);
            file_put_contents('/tmp/commands', $af428179032a83d9ec1df565934b1c89 . '', FILE_APPEND);
            $pid = intval(file_get_contents(MOVIES_PATH . $stream_id . '_.pid'));
            self::$ipTV_db->query('UPDATE `streams_sys` SET `to_analyze` = 1,`stream_started` = \'%d\',`stream_status` = 0,`pid` = \'%d\' WHERE `stream_id` = \'%d\' AND `server_id` = \'%d\'', time(), $pid, $stream_id, SERVER_ID);
            return $pid;
            }
        
    }
    static function CEBeee6A9C20e0da24C41A0247cf1244($stream_id, &$bb1b9dfc97454460e165348212675779, $B71703fbd9f237149967f9ac3c41dc19 = null)
    {
        ++$bb1b9dfc97454460e165348212675779;
        if (file_exists(STREAMS_PATH . $stream_id . '_.pid')) {
            unlink(STREAMS_PATH . $stream_id . '_.pid');
        }
        $stream = array();
        self::$ipTV_db->query('SELECT * FROM `streams` t1 
                               INNER JOIN `streams_types` t2 ON t2.type_id = t1.type AND t2.live = 1
                               LEFT JOIN `transcoding_profiles` t4 ON t1.transcode_profile_id = t4.profile_id 
                               WHERE t1.direct_source = 0 AND t1.id = \'%d\'', $stream_id);
        if (self::$ipTV_db->num_rows() <= 0) {
            return false;
        }
        $stream['stream_info'] = self::$ipTV_db->get_row();
        self::$ipTV_db->query('SELECT * FROM `streams_sys` WHERE stream_id  = \'%d\' AND `server_id` = \'%d\'', $stream_id, SERVER_ID);
        if (self::$ipTV_db->num_rows() <= 0) {
            return false;
        }
        $stream['server_info'] = self::$ipTV_db->get_row();
        self::$ipTV_db->query('SELECT t1.*, t2.* FROM `streams_options` t1, `streams_arguments` t2 WHERE t1.stream_id = \'%d\' AND t1.argument_id = t2.id', $stream_id);
        $stream['stream_arguments'] = self::$ipTV_db->get_rows();
        if ($stream['server_info']['on_demand'] == 1) {
            $probesize = $stream['stream_info']['probesize_ondemand'];
            $stream_max_analyze = '10000000';
        } else {
            $stream_max_analyze = abs(intval(ipTV_lib::$settings['stream_max_analyze']));
            $probesize = abs(intval(ipTV_lib::$settings['probesize']));
        }
        $d1c5b35a94aa4152ee37c6cfedfb2ec3 = intval($stream_max_analyze / 1000000) + 7;
        $Fa28e3498375fc4da68f3f818d774249 = "/usr/bin/timeout {$d1c5b35a94aa4152ee37c6cfedfb2ec3}s " . FFPROBE_PATH . " {FETCH_OPTIONS} -probesize {$probesize} -analyzeduration {$stream_max_analyze} {CONCAT} -i \"{STREAM_SOURCE}\" -v quiet -print_format json -show_streams -show_format";
        $be9f906faa527985765b1d8c897fb13a = array();
        if ($stream['server_info']['parent_id'] == 0) {
            $sources = $stream['stream_info']['type_key'] == 'created_live' ? array(CREATED_CHANNELS . $stream_id . '_.list') : json_decode($stream['stream_info']['stream_source'], true);
        } else {
            $sources = array(ipTV_lib::$StreamingServers[$stream['server_info']['parent_id']]['site_url_ip'] . 'streaming/admin_live.php?stream=' . $stream_id . '&password=' . ipTV_lib::$settings['live_streaming_pass'] . '&extension=ts');
        }
        if (count($sources) > 0) {
            if (empty($B71703fbd9f237149967f9ac3c41dc19)) {
                if (ipTV_lib::$settings['priority_backup'] != 1) {
                     $sources = array($B71703fbd9f237149967f9ac3c41dc19);
                }
                else if (!empty($stream['server_info']['current_source'])) {
                    $k = array_search($stream['server_info']['current_source'], $sources);
                    if ($k !== false) {
                        $index = 0;
                        while ($index <= $k) {
                            $Ad110d626a9e62f0778a8f19383a0613 = $sources[$index];
                            unset($sources[$index]);
                            array_push($sources, $Ad110d626a9e62f0778a8f19383a0613);
                            $index++;
                        }
                        $sources = array_values($sources);
                    }
                }

                $F7b03a1f7467c01c6ea18452d9a5202f = $bb1b9dfc97454460e165348212675779 <= RESTART_TAKE_CACHE ? true : false;
                if (!$F7b03a1f7467c01c6ea18452d9a5202f) {
                    self::($sources);
                }
                foreach ($sources as $source) {
                    $stream_source = self::ParseStreamURL($source);
                    $server_protocol = strtolower(substr($stream_source, 0, strpos($stream_source, '://')));
                    $be9f906faa527985765b1d8c897fb13a = implode(' ', self::Ea860c1d3851C46D06E64911E3602768($stream['stream_arguments'], $server_protocol, 'fetch'));
                    if ($F7b03a1f7467c01c6ea18452d9a5202f && file_exists(STREAMS_PATH . md5($stream_source))) {
                        $e49460014c491accfafaa768ea84cd9c = json_decode(file_get_contents(STREAMS_PATH . md5($stream_source)), true);
                        break;
                    }
                    $e49460014c491accfafaa768ea84cd9c = json_decode(shell_exec(str_replace(array('{FETCH_OPTIONS}', '{CONCAT}', '{STREAM_SOURCE}'), array($be9f906faa527985765b1d8c897fb13a, $stream['stream_info']['type_key'] == 'created_live' && $stream['server_info']['parent_id'] == 0 ? '-safe 0 -f concat' : '', $stream_source), $Fa28e3498375fc4da68f3f818d774249)), true);
                    if (!empty($e49460014c491accfafaa768ea84cd9c)) {
                        break;
                    }
                }
                if (empty($e49460014c491accfafaa768ea84cd9c)) {
                    if ($stream['server_info']['stream_status'] == 0 || $stream['server_info']['to_analyze'] == 1 || $stream['server_info']['pid'] != -1) {
                        self::$ipTV_db->query('UPDATE `streams_sys` SET `progress_info` = \'\',`to_analyze` = 0,`pid` = -1,`stream_status` = 1 WHERE `server_id` = \'%d\' AND `stream_id` = \'%d\'', SERVER_ID, $stream_id);
                    }
                    return 0;
                }
                if (!$F7b03a1f7467c01c6ea18452d9a5202f) {
                    file_put_contents(STREAMS_PATH . md5($stream_source), json_encode($e49460014c491accfafaa768ea84cd9c));
                }
                $e49460014c491accfafaa768ea84cd9c = self::Ccbd051c8A19a02dC5B6db256Ae31C07($e49460014c491accfafaa768ea84cd9c);
                $Ee11a0d09ece7de916fbc0b2ca0136a3 = json_decode($stream['stream_info']['external_push'], true);
                $progress = 'http://127.0.0.1:' . ipTV_lib::$StreamingServers[SERVER_ID]['http_broadcast_port'] . "/progress.php?stream_id={$stream_id}";
                if (empty($stream['stream_info']['custom_ffmpeg'])) {
                    $af428179032a83d9ec1df565934b1c89 = FFMPEG_PATH . " -y -nostdin -hide_banner -loglevel warning -err_detect ignore_err {FETCH_OPTIONS} {GEN_PTS} {READ_NATIVE} -probesize {$probesize} -analyzeduration {$stream_max_analyze} -progress \"{$progress}\" {CONCAT} -i \"{STREAM_SOURCE}\" ";
                    if (($stream['stream_info']['stream_all'] == 1)) {
                        $map = '-map 0 -copy_unknown ';
                    }
                    else if (empty($stream['stream_info']['custom_map'])) {
                        $map = $stream['stream_info']['custom_map'] . ' -copy_unknown ';
                    }
                    if ($stream['stream_info']['type_key'] == 'radio_streams') {
                        $map = '-map 0:a? ';
                    } else {
                        $map = '';
                    }
                    if (($stream['stream_info']['gen_timestamps'] == 1 || empty($server_protocol)) && $stream['stream_info']['type_key'] != 'created_live') {
                        $e9652f3db39531a69b91900690d5d064 = '-fflags +genpts -async 1';
                    } else {
                        $e9652f3db39531a69b91900690d5d064 = '-nofix_dts -start_at_zero -copyts -vsync 0 -correct_ts_overflow 0 -avoid_negative_ts disabled -max_interleave_delta 0';
                    }
                    $feb3f2070e6ccf961f6265281e875b1a = '';
                    if ($stream['server_info']['parent_id'] == 0 && ($stream['stream_info']['read_native'] == 1 or stristr($e49460014c491accfafaa768ea84cd9c['container'], 'hls') or empty($server_protocol) or stristr($e49460014c491accfafaa768ea84cd9c['container'], 'mp4') or stristr($e49460014c491accfafaa768ea84cd9c['container'], 'matroska'))) {
                        $feb3f2070e6ccf961f6265281e875b1a = '-re';
                    }
                    if ($stream['server_info']['parent_id'] == 0 and $stream['stream_info']['enable_transcode'] == 1 and $stream['stream_info']['type_key'] != 'created_live') {
                        if ($stream['stream_info']['transcode_profile_id'] == -1) {
                            $stream['stream_info']['transcode_attributes'] = array_merge(self::EA860c1D3851c46d06E64911E3602768($stream['stream_arguments'], $server_protocol, 'transcode'), json_decode($stream['stream_info']['transcode_attributes'], true));
                        } else {
                            $stream['stream_info']['transcode_attributes'] = json_decode($stream['stream_info']['profile_options'], true);
                        }
                    } else {
                        $stream['stream_info']['transcode_attributes'] = array();
                    }
                    if (!array_key_exists('-acodec', $stream['stream_info']['transcode_attributes'])) {
                        $stream['stream_info']['transcode_attributes']['-acodec'] = 'copy';
                    }
                    if (!array_key_exists('-vcodec', $stream['stream_info']['transcode_attributes'])) {
                        $stream['stream_info']['transcode_attributes']['-vcodec'] = 'copy';
                    }
                    if (!array_key_exists('-scodec', $stream['stream_info']['transcode_attributes'])) {
                        $stream['stream_info']['transcode_attributes']['-scodec'] = 'copy';
                    }
                    $A7c6258649492b26d77c75c60c793409 = array();
                    $A7c6258649492b26d77c75c60c793409['mpegts'][] = '{MAP} -individual_header_trailer 0 -f segment -segment_format mpegts -segment_time ' . ipTV_lib::$SegmentsSettings['seg_time'] . ' -segment_list_size ' . ipTV_lib::$SegmentsSettings['seg_list_size'] . ' -segment_format_options "mpegts_flags=+initial_discontinuity:mpegts_copyts=1" -segment_list_type m3u8 -segment_list_flags +live+delete -segment_list "' . STREAMS_PATH . $stream_id . '_.m3u8" "' . STREAMS_PATH . $stream_id . '_%d.ts" ';
                    if ($stream['stream_info']['rtmp_output'] == 1) {
                        $A7c6258649492b26d77c75c60c793409['flv'][] = '{MAP} {AAC_FILTER} -f flv rtmp://127.0.0.1:' . ipTV_lib::$StreamingServers[$stream['server_info']['server_id']]['rtmp_port'] . "/live/{$stream_id} ";
                    }
                    if (!empty($Ee11a0d09ece7de916fbc0b2ca0136a3[SERVER_ID])) {
                        foreach ($Ee11a0d09ece7de916fbc0b2ca0136a3[SERVER_ID] as $b202bc9c1c41da94906c398ceb9f3573) {
                            $A7c6258649492b26d77c75c60c793409['flv'][] = "{MAP} {AAC_FILTER} -f flv \"{$b202bc9c1c41da94906c398ceb9f3573}\" ";
                        }
                    }
                    $f32785b2a16d0d92cda0b44ed436f505 = 0;
                    if (!($stream['stream_info']['delay_minutes'] > 0 && $stream['server_info']['parent_id'] == 0)) {
                        foreach ($A7c6258649492b26d77c75c60c793409 as $output_key => $f72c3a34155eca511d79ca3671e1063f) {
                            foreach ($f72c3a34155eca511d79ca3671e1063f as $cd7bafd64552e6ca58318f09800cbddd) {
                                $af428179032a83d9ec1df565934b1c89 .= implode(' ', self::f6664c80bde3e9BBe2c12ceb906d5a11($stream['stream_info']['transcode_attributes'])) . ' ';
                                $af428179032a83d9ec1df565934b1c89 .= $cd7bafd64552e6ca58318f09800cbddd;
                            }
                        }
                    } else {
                        $segment_start_number = 0;
                        if (file_exists(DELAY_STREAM . $stream_id . '_.m3u8')) {
                            $file = file(DELAY_STREAM . $stream_id . '_.m3u8');
                            if (stristr($file[count($file) - 1], $stream_id . '_')) {
                                if (preg_match('/\\_(.*?)\\.ts/', $file[count($file) - 1], $matches)) {
                                    $segment_start_number = intval($matches[1]) + 1;
                                }
                            } else {
                                if (preg_match('/\\_(.*?)\\.ts/', $file[count($file) - 2], $matches)) {
                                    $segment_start_number = intval($matches[1]) + 1;
                                }
                            }
                            if (file_exists(DELAY_STREAM . $stream_id . '_.m3u8_old')) {
                                file_put_contents(DELAY_STREAM . $stream_id . '_.m3u8_old', file_get_contents(DELAY_STREAM . $stream_id . '_.m3u8_old') . file_get_contents(DELAY_STREAM . $stream_id . '_.m3u8'));
                                shell_exec('sed -i \'/EXTINF\\|.ts/!d\' ' . DELAY_STREAM . $stream_id . '_.m3u8_old');
                            } else {
                                copy(DELAY_STREAM . $stream_id . '_.m3u8', DELAY_STREAM . $stream_id . '_.m3u8_old');
                            }
                        }
                        $af428179032a83d9ec1df565934b1c89 .= implode(' ', self::f6664C80BDe3E9bbe2c12ceB906D5A11($stream['stream_info']['transcode_attributes'])) . ' ';
                        $af428179032a83d9ec1df565934b1c89 .= '{MAP} -individual_header_trailer 0 -f segment -segment_format mpegts -segment_time ' . ipTV_lib::$SegmentsSettings['seg_time'] . ' -segment_list_size ' . $stream['stream_info']['delay_minutes'] * 6 . " -segment_start_number {$segment_start_number} -segment_format_options \"mpegts_flags=+initial_discontinuity:mpegts_copyts=1\" -segment_list_type m3u8 -segment_list_flags +live+delete -segment_list \"" . DELAY_STREAM . $stream_id . '_.m3u8" "' . DELAY_STREAM . $stream_id . '_%d.ts" ';
                        $delay_minutes = $stream['stream_info']['delay_minutes'] * 60;
                        if ($segment_start_number > 0) {
                            $delay_minutes -= ($segment_start_number - 1) * 10;
                            if ($delay_minutes <= 0) {
                                $delay_minutes = 0;
                            }
                        }
                    }
                    $af428179032a83d9ec1df565934b1c89 .= ' >/dev/null 2>>' . STREAMS_PATH . $stream_id . '.errors & echo $! > ' . STREAMS_PATH . $stream_id . '_.pid';
                    $af428179032a83d9ec1df565934b1c89 = str_replace(array('{INPUT}', '{FETCH_OPTIONS}', '{GEN_PTS}', '{STREAM_SOURCE}', '{MAP}', '{READ_NATIVE}', '{CONCAT}', '{AAC_FILTER}'), array("\"{$stream_source}\"", empty($stream['stream_info']['custom_ffmpeg']) ? $be9f906faa527985765b1d8c897fb13a : '', empty($stream['stream_info']['custom_ffmpeg']) ? $e9652f3db39531a69b91900690d5d064 : '', $stream_source, empty($stream['stream_info']['custom_ffmpeg']) ? $map : '', empty($stream['stream_info']['custom_ffmpeg']) ? $feb3f2070e6ccf961f6265281e875b1a : '', $stream['stream_info']['type_key'] == 'created_live' && $stream['server_info']['parent_id'] == 0 ? '-safe 0 -f concat' : '', !stristr($e49460014c491accfafaa768ea84cd9c['container'], 'flv') && $e49460014c491accfafaa768ea84cd9c['codecs']['audio']['codec_name'] == 'aac' && $stream['stream_info']['transcode_attributes']['-acodec'] == 'copy' ? '-bsf:a aac_adtstoasc' : ''), $af428179032a83d9ec1df565934b1c89);
                    shell_exec($af428179032a83d9ec1df565934b1c89);
                    $pid = $pid = intval(file_get_contents(STREAMS_PATH . $stream_id . '_.pid'));
                    if (SERVER_ID == $stream['stream_info']['tv_archive_server_id']) {
                        shell_exec(PHP_BIN . ' ' . TOOLS_PATH . 'archive.php ' . $stream_id . ' >/dev/null 2>/dev/null & echo $!');
                    }
                    $Dac1208baefb5d684938829a3a0e0bc6 = $stream['stream_info']['delay_minutes'] > 0 && $stream['server_info']['parent_id'] == 0 ? true : false;
                    $f32785b2a16d0d92cda0b44ed436f505 = $Dac1208baefb5d684938829a3a0e0bc6 ? time() + $delay_minutes : 0;
                    self::$ipTV_db->query('UPDATE `streams_sys` SET `delay_available_at` = \'%d\',`to_analyze` = 0,`stream_started` = \'%d\',`stream_info` = \'%s\',`stream_status` = 0,`pid` = \'%d\',`progress_info` = \'%s\',`current_source` = \'%s\' WHERE `stream_id` = \'%d\' AND `server_id` = \'%d\'', $f32785b2a16d0d92cda0b44ed436f505, time(), json_encode($e49460014c491accfafaa768ea84cd9c), $pid, json_encode(array()), $source, $stream_id, SERVER_ID);
                    $playlist = !$Dac1208baefb5d684938829a3a0e0bc6 ? STREAMS_PATH . $stream_id . '_.m3u8' : DELAY_STREAM . $stream_id . '_.m3u8';
                    return array('main_pid' => $pid, 'stream_source' => $stream_source, 'delay_enabled' => $Dac1208baefb5d684938829a3a0e0bc6, 'parent_id' => $stream['server_info']['parent_id'], 'delay_start_at' => $f32785b2a16d0d92cda0b44ed436f505, 'playlist' => $playlist);
                
                    
                } else {
                    $stream['stream_info']['transcode_attributes'] = array();
                    $af428179032a83d9ec1df565934b1c89 = FFMPEG_PATH . " -y -nostdin -hide_banner -loglevel quiet {$d1006c7cc041221972025137b5112b7d} -progress \"{$progress}\" " . $stream['stream_info']['custom_ffmpeg'];
                }
            }
        }
    }
    public static function customOrder($a, $b)
    {
        if (substr($a, 0, 3) == '-i ') {
            return -1;
        }
        return 1;
    }
    public static function EA860c1D3851C46d06E64911E3602768($c31311861794ebdea68a9eab6a24fd6d, $server_protocol, $type)
    {
        $Eb6e347d24315f277ac38240a6589dd0 = array();
        if (!empty($c31311861794ebdea68a9eab6a24fd6d)) {
            foreach ($c31311861794ebdea68a9eab6a24fd6d as $f091df572e6d2b79881acbf4e5500a7e => $e380987e83a27088358f65f47ff3117f) {
                if ($e380987e83a27088358f65f47ff3117f['argument_cat'] != $type) {
                    continue;
                }
                if (!is_null($e380987e83a27088358f65f47ff3117f['argument_wprotocol']) && !stristr($server_protocol, $e380987e83a27088358f65f47ff3117f['argument_wprotocol']) && !is_null($server_protocol)) {
                    continue;
                }
                if ($e380987e83a27088358f65f47ff3117f['argument_type'] == 'text') {
                    $Eb6e347d24315f277ac38240a6589dd0[] = sprintf($e380987e83a27088358f65f47ff3117f['argument_cmd'], $e380987e83a27088358f65f47ff3117f['value']);
                } else {
                    $Eb6e347d24315f277ac38240a6589dd0[] = $e380987e83a27088358f65f47ff3117f['argument_cmd'];
                }
            }
        }
        return $Eb6e347d24315f277ac38240a6589dd0;
    }
    public static function F6664c80bdE3E9BBe2C12CeB906D5a11($Bddd92df0619e485304556731bb7ca2f)
    {
        $e80cbed8655f14b141bd53699dbbdc10 = array();
        foreach ($Bddd92df0619e485304556731bb7ca2f as $k => $e380987e83a27088358f65f47ff3117f) {
            if (isset($e380987e83a27088358f65f47ff3117f['cmd'])) {
                $Bddd92df0619e485304556731bb7ca2f[$k] = $e380987e83a27088358f65f47ff3117f = $e380987e83a27088358f65f47ff3117f['cmd'];
            }
            if (preg_match('/-filter_complex "(.*?)"/', $e380987e83a27088358f65f47ff3117f, $matches)) {
                $Bddd92df0619e485304556731bb7ca2f[$k] = trim(str_replace($matches[0], '', $Bddd92df0619e485304556731bb7ca2f[$k]));
                $e80cbed8655f14b141bd53699dbbdc10[] = $matches[1];
            }
        }
        if (!empty($e80cbed8655f14b141bd53699dbbdc10)) {
            $Bddd92df0619e485304556731bb7ca2f[] = '-filter_complex "' . implode(',', $e80cbed8655f14b141bd53699dbbdc10) . '"';
        }
        $B54918193a6b3b39c547eb9486c4c2ff = array();
        foreach ($Bddd92df0619e485304556731bb7ca2f as $k => $e7ddd0b219bd2e9b7547185c8bccb6a9) {
            if (is_numeric($k)) {
                $B54918193a6b3b39c547eb9486c4c2ff[] = $e7ddd0b219bd2e9b7547185c8bccb6a9;
            } else {
                $B54918193a6b3b39c547eb9486c4c2ff[] = $k . ' ' . $e7ddd0b219bd2e9b7547185c8bccb6a9;
            }
        }
        $B54918193a6b3b39c547eb9486c4c2ff = array_filter($B54918193a6b3b39c547eb9486c4c2ff);
        uasort($B54918193a6b3b39c547eb9486c4c2ff, array(__CLASS__, 'customOrder'));
        return array_map('trim', array_values(array_filter($B54918193a6b3b39c547eb9486c4c2ff)));
    }
    public static function ParseStreamURL($D849b6918b9e10195509dc8a824f49eb)
    {
        $server_protocol = strtolower(substr($D849b6918b9e10195509dc8a824f49eb, 0, 4));
        if (($server_protocol == 'rtmp')) {
            if (stristr($D849b6918b9e10195509dc8a824f49eb, '$OPT')) {
                $b853b956930a081396b7a6beb8404265 = 'rtmp://$OPT:rtmp-raw=';
                $D849b6918b9e10195509dc8a824f49eb = trim(substr($D849b6918b9e10195509dc8a824f49eb, stripos($D849b6918b9e10195509dc8a824f49eb, $b853b956930a081396b7a6beb8404265) + strlen($b853b956930a081396b7a6beb8404265)));
            }
            $D849b6918b9e10195509dc8a824f49eb .= ' live=1 timeout=10';
        }
        else if ($server_protocol == 'http') {
            $d412be7a00d131e9be20aca9526c741f = array('youtube.com', 'youtu.be', 'livestream.com', 'ustream.tv', 'twitch.tv', 'vimeo.com', 'facebook.com', 'dailymotion.com', 'cnn.com', 'edition.cnn.com', 'youporn.com', 'pornhub.com', 'youjizz.com', 'xvideos.com', 'redtube.com', 'ruleporn.com', 'pornotube.com', 'skysports.com', 'screencast.com', 'xhamster.com', 'pornhd.com', 'pornktube.com', 'tube8.com', 'vporn.com', 'giniko.com', 'xtube.com');
            $E8cb364637af05312e9ad4e7c0680ce2 = str_ireplace('www.', '', parse_url($D849b6918b9e10195509dc8a824f49eb, PHP_URL_HOST));
            if (in_array($E8cb364637af05312e9ad4e7c0680ce2, $d412be7a00d131e9be20aca9526c741f)) {
                $urls = trim(shell_exec(YOUTUBE_PATH . " \"{$D849b6918b9e10195509dc8a824f49eb}\" -q --get-url --skip-download -f best"));
                $D849b6918b9e10195509dc8a824f49eb = explode('', $urls)[0];
            }
        }
        return $D849b6918b9e10195509dc8a824f49eb;
    }
}
?>
