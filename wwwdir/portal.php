<?php
/*Rev:26.09.18r0*/

require './init.php';
include './langs/mag_langs.php';
header('cache-control: no-store, no-cache, must-revalidate');
header('cache-control: post-check=0, pre-check=0', false);
header('Pragma: no-cache'); 
@header('Content-type: text/javascript');
$f429d0e47085017e3f1e415952e44cba = ipTV_streaming::getUserIP();
$f34a0094f9db3be3b99dd1eb1e9a3b6d = !empty($_REQUEST['type']) ? $_REQUEST['type'] : null;
$req_action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : null;
$b25b89525a979cf56e2fd295b28327b8 = !empty($_REQUEST['sn']) ? $_REQUEST['sn'] : null;
$d8ba920e2a1ba9839322c2bca0a7a741 = !empty($_REQUEST['stb_type']) ? $_REQUEST['stb_type'] : null;
$mac = !empty($_REQUEST['mac']) ? $_REQUEST['mac'] : $_COOKIE['mac'];
$fca2439385f041f384419649ca2471d6 = !empty($_REQUEST['ver']) ? $_REQUEST['ver'] : null;
$user_agent = !empty($_SERVER['HTTP_X_USER_AGENT']) ? $_SERVER['HTTP_X_USER_AGENT'] : null;
$be29ac67a4314fc9435deb1462cae967 = !empty($_REQUEST['image_version']) ? $_REQUEST['image_version'] : null;
$device_id = !empty($_REQUEST['device_id']) ? $_REQUEST['device_id'] : null;
$Ba644b1066f7c673215de30d5ce5e62c = !empty($_REQUEST['device_id2']) ? $_REQUEST['device_id2'] : null;
$B71eec623f2edcac610184525828cc2d = !empty($_REQUEST['hw_version']) ? $_REQUEST['hw_version'] : null;
$be4275c3d5887706bcf4db19dc01637e = !empty($_REQUEST['gmode']) ? intval($_REQUEST['gmode']) : null;
$F7af965c868ad940b4c181abc987875f = false;
$A6dde9bd7afc06231a1481ec56fd5768 = ipTV_lib::$settings['enable_debug_stalker'] == 1 ? true : false;
$dev = array();
if ($dev = b9361cDf8f8f200F06F546758512060C($b25b89525a979cf56e2fd295b28327b8, $mac, $fca2439385f041f384419649ca2471d6, $d8ba920e2a1ba9839322c2bca0a7a741, $be29ac67a4314fc9435deb1462cae967, $device_id, $Ba644b1066f7c673215de30d5ce5e62c, $B71eec623f2edcac610184525828cc2d, $f429d0e47085017e3f1e415952e44cba, $A6dde9bd7afc06231a1481ec56fd5768, $f34a0094f9db3be3b99dd1eb1e9a3b6d, $req_action)) {
    $F7af965c868ad940b4c181abc987875f = true;
    ini_set('memory_limit', -1);
}
if ($f34a0094f9db3be3b99dd1eb1e9a3b6d == 'stb' && $req_action == 'handshake') {
    $token = strtoupper(md5(uniqid(rand(), true)));
    if (!empty($dev)) {
        $ipTV_db->query('UPDATE `mag_devices` SET token = \'%s\' WHERE `mag_id` = \'%d\'', $token, $dev['mag_id']);
        if (ipTV_lib::$settings['stb_change_pass'] == 1) {
            $ipTV_db->query('UPDATE `users` SET `password` = \'%s\' WHERE `id` = \'%d\'', ipTV_lib::GenerateString(10), $dev['user_id']);
        }
    }
    die(json_encode(array('js' => array('token' => $token))));
}
if (empty($dev['locale']) && !empty($_COOKIE['locale'])) {
    $dev['locale'] = $_COOKIE['locale'];
} else {
    $dev['locale'] = 'en_GB.utf8';
}
$_MAG_DATA = array();
$Acbe482ea62dee218013e22b65162a3f = array('id' => $dev['mag_id'], 'name' => $dev['mag_id'], 'sname' => '', 'pass' => '', 'parent_password' => '0000', 'bright' => '200', 'contrast' => '127', 'saturation' => '127', 'video_out' => '', 'volume' => '70', 'playback_buffer_bytes' => '0', 'playback_buffer_size' => '0', 'audio_out' => '1', 'mac' => $mac, 'ip' => $f429d0e47085017e3f1e415952e44cba, 'ls' => '', 'version' => '', 'lang' => '', 'locale' => $dev['locale'], 'city_id' => '0', 'hd' => '1', 'main_notify' => '1', 'fav_itv_on' => '0', 'now_playing_start' => '2018-02-18 17:33:43', 'now_playing_type' => '1', 'now_playing_content' => 'Test channel', 'additional_services_on' => '1', 'time_last_play_tv' => '0000-00-00 00:00:00', 'time_last_play_video' => '0000-00-00 00:00:00', 'operator_id' => '0', 'storage_name' => '', 'hd_content' => '0', 'image_version' => 'undefined', 'last_change_status' => '0000-00-00 00:00:00', 'last_start' => '2018-02-18 17:33:38', 'last_active' => '2018-02-18 17:33:43', 'keep_alive' => '2018-02-18 17:33:43', 'screensaver_delay' => '10', 'phone' => '', 'fname' => '', 'login' => '', 'password' => '', 'stb_type' => '', 'num_banks' => '0', 'tariff_plan_id' => '0', 'comment' => null, 'now_playing_link_id' => '0', 'now_playing_streamer_id' => '0', 'just_started' => '1', 'last_watchdog' => '2018-02-18 17:33:39', 'created' => '2018-02-18 14:40:12', 'plasma_saving' => '0', 'ts_enabled' => '0', 'ts_enable_icon' => '1', 'ts_path' => '', 'ts_max_length' => '3600', 'ts_buffer_use' => 'cyclic', 'ts_action_on_exit' => 'no_save', 'ts_delay' => 'on_pause', 'video_clock' => 'Off', 'verified' => '0', 'hdmi_event_reaction' => 1, 'pri_audio_lang' => '', 'sec_audio_lang' => '', 'pri_subtitle_lang' => '', 'sec_subtitle_lang' => '', 'subtitle_color' => '16777215', 'subtitle_size' => '20', 'show_after_loading' => '', 'play_in_preview_by_ok' => null, 'hw_version' => 'undefined', 'openweathermap_city_id' => '0', 'theme' => '', 'settings_password' => '0000', 'expire_billing_date' => '0000-00-00 00:00:00', 'reseller_id' => null, 'account_balance' => '', 'client_type' => 'STB', 'hw_version_2' => '62', 'blocked' => '0', 'units' => 'metric', 'tariff_expired_date' => null, 'tariff_id_instead_expired' => null, 'activation_code_auto_issue' => '1', 'last_itv_id' => 0, 'updated' => array('id' => '1', 'uid' => '1', 'anec' => '0', 'vclub' => '0'), 'rtsp_type' => '4', 'rtsp_flags' => '0', 'stb_lang' => 'en', 'display_menu_after_loading' => '', 'record_max_length' => 180, 'web_proxy_host' => '', 'web_proxy_port' => '', 'web_proxy_user' => '', 'web_proxy_pass' => '', 'web_proxy_exclude_list' => '', 'demo_video_url' => '', 'tv_quality_filter' => '', 'is_moderator' => false, 'timeslot_ratio' => 0.33333333333333, 'timeslot' => 40, 'kinopoisk_rating' => '1', 'enable_tariff_plans' => '', 'strict_stb_type_check' => '', 'cas_type' => 0, 'cas_params' => null, 'cas_web_params' => null, 'cas_additional_params' => array(), 'cas_hw_descrambling' => 0, 'cas_ini_file' => '', 'logarithm_volume_control' => '', 'allow_subscription_from_stb' => '1', 'deny_720p_gmode_on_mag200' => '1', 'enable_arrow_keys_setpos' => '1', 'show_purchased_filter' => '', 'timezone_diff' => 0, 'enable_connection_problem_indication' => '1', 'invert_channel_switch_direction' => '', 'play_in_preview_only_by_ok' => false, 'enable_stream_error_logging' => '', 'always_enabled_subtitles' => ipTV_lib::$settings['always_enabled_subtitles'] == 1 ? '1' : '', 'enable_service_button' => '', 'enable_setting_access_by_pass' => '', 'tv_archive_continued' => '', 'plasma_saving_timeout' => '600', 'show_tv_only_hd_filter_option' => '', 'tv_playback_retry_limit' => '0', 'fading_tv_retry_timeout' => '1', 'epg_update_time_range' => 0.6, 'store_auth_data_on_stb' => false, 'account_page_by_password' => '', 'tester' => false, 'enable_stream_losses_logging' => '', 'external_payment_page_url' => '', 'max_local_recordings' => '10', 'tv_channel_default_aspect' => 'fit', 'default_led_level' => '10', 'standby_led_level' => '90', 'show_version_in_main_menu' => '1', 'disable_youtube_for_mag200' => '1', 'auth_access' => false, 'epg_data_block_period_for_stb' => '5', 'standby_on_hdmi_off' => '1', 'force_ch_link_check' => '', 'stb_ntp_server' => 'pool.ntp.org', 'overwrite_stb_ntp_server' => '', 'hide_tv_genres_in_fullscreen' => null, 'advert' => null);
$f636a7ecab05fca43624a8178337cef1['get_locales']['English'] = 'en_GB.utf8';
$f636a7ecab05fca43624a8178337cef1['get_locales']['Ελληνικά'] = 'el_GR.utf8';
$_MAG_DATA['get_years'] = array('js' => array(array('id' => '*', 'title' => '*'), array('id' => '1937', 'title' => '1937'), array('id' => '1940', 'title' => '1940'), array('id' => '1941', 'title' => '1941'), array('id' => '1951', 'title' => '1951'), array('id' => '1953', 'title' => '1953'), array('id' => '1955', 'title' => '1955'), array('id' => '1961', 'title' => '1961'), array('id' => '1964', 'title' => '1964'), array('id' => '1970', 'title' => '1970'), array('id' => '1983', 'title' => '1983'), array('id' => '1986', 'title' => '1986'), array('id' => '1990', 'title' => '1990'), array('id' => '1992', 'title' => '1992'), array('id' => '1994', 'title' => '1994'), array('id' => '1994/1998/2004', 'title' => '1994/1998/2004'), array('id' => '1995', 'title' => '1995'), array('id' => '1995/1999/2010', 'title' => '1995/1999/2010'), array('id' => '1996', 'title' => '1996'), array('id' => '1998', 'title' => '1998'), array('id' => '1999', 'title' => '1999'), array('id' => '2000', 'title' => '2000'), array('id' => '2001', 'title' => '2001'), array('id' => '2002', 'title' => '2002'), array('id' => '2003', 'title' => '2003'), array('id' => '2004', 'title' => '2004'), array('id' => '2005', 'title' => '2005'), array('id' => '2006', 'title' => '2006'), array('id' => '2007', 'title' => '2007'), array('id' => '2008', 'title' => '2008'), array('id' => '2009', 'title' => '2009'), array('id' => '2010', 'title' => '2010'), array('id' => '2011', 'title' => '2011'), array('id' => '2012', 'title' => '2012'), array('id' => '2013', 'title' => '2013'), array('id' => '2013', 'title' => '2013'), array('id' => '2014', 'title' => '2014'), array('id' => '2015', 'title' => '2015'), array('id' => '2016', 'title' => '2016'), array('id' => '2017', 'title' => '2017')));
$_MAG_DATA['get_abc'] = array('js' => array(array('id' => '*', 'title' => '*'), array('id' => 'A', 'title' => 'A'), array('id' => 'B', 'title' => 'B'), array('id' => 'C', 'title' => 'C'), array('id' => 'D', 'title' => 'D'), array('id' => 'E', 'title' => 'E'), array('id' => 'F', 'title' => 'F'), array('id' => 'G', 'title' => 'G'), array('id' => 'H', 'title' => 'H'), array('id' => 'I', 'title' => 'I'), array('id' => 'G', 'title' => 'G'), array('id' => 'K', 'title' => 'K'), array('id' => 'L', 'title' => 'L'), array('id' => 'M', 'title' => 'M'), array('id' => 'N', 'title' => 'N'), array('id' => 'O', 'title' => 'O'), array('id' => 'P', 'title' => 'P'), array('id' => 'Q', 'title' => 'Q'), array('id' => 'R', 'title' => 'R'), array('id' => 'S', 'title' => 'S'), array('id' => 'T', 'title' => 'T'), array('id' => 'U', 'title' => 'U'), array('id' => 'V', 'title' => 'V'), array('id' => 'W', 'title' => 'W'), array('id' => 'X', 'title' => 'X'), array('id' => 'W', 'title' => 'W'), array('id' => 'Z', 'title' => 'Z')));
$fadb655b1efb9a22103bc37699dce015 = empty(ipTV_lib::$settings['stalker_theme']) ? 'default' : ipTV_lib::$settings['stalker_theme'];
$timezone = empty($_COOKIE['timezone']) || $_COOKIE['timezone'] == 'undefined' ? ipTV_lib::$settings['default_timezone'] : $_COOKIE['timezone'];
if ($F7af965c868ad940b4c181abc987875f && !$A6dde9bd7afc06231a1481ec56fd5768) {
    A1c6b49ec4F49777516666e14316D4B7($dev['token']);
}
switch ($f34a0094f9db3be3b99dd1eb1e9a3b6d) {
    case 'stb':
        switch ($req_action) {
            case 'get_ad':
                die(json_encode(array('js' => array())));
                break;
            case 'get_storages':
                die(json_encode(array('js' => array())));
                break;
            case 'get_profile':
                $A6f4ecc798bcb285eee6efb4467c6708 = $F7af965c868ad940b4c181abc987875f ? array_merge($Acbe482ea62dee218013e22b65162a3f, $dev['get_profile_vars']) : $Acbe482ea62dee218013e22b65162a3f;
                $A6f4ecc798bcb285eee6efb4467c6708['status'] = intval(!$F7af965c868ad940b4c181abc987875f);
                $A6f4ecc798bcb285eee6efb4467c6708['update_url'] = empty(ipTV_lib::$settings['update_url']) ? '' : ipTV_lib::$settings['update_url'];
                $A6f4ecc798bcb285eee6efb4467c6708['test_download_url'] = empty(ipTV_lib::$settings['test_download_url']) ? '' : ipTV_lib::$settings['test_download_url'];
                $A6f4ecc798bcb285eee6efb4467c6708['default_timezone'] = ipTV_lib::$settings['default_timezone'];
                $A6f4ecc798bcb285eee6efb4467c6708['default_locale'] = ipTV_lib::$settings['default_locale'];
                $A6f4ecc798bcb285eee6efb4467c6708['allowed_stb_types'] = ipTV_lib::$settings['allowed_stb_types'];
                $A6f4ecc798bcb285eee6efb4467c6708['allowed_stb_types_for_local_recording'] = ipTV_lib::$settings['allowed_stb_types'];
                $A6f4ecc798bcb285eee6efb4467c6708['storages'] = array();
                $A6f4ecc798bcb285eee6efb4467c6708['tv_channel_default_aspect'] = empty(ipTV_lib::$settings['tv_channel_default_aspect']) ? 'fit' : ipTV_lib::$settings['tv_channel_default_aspect'];
                $A6f4ecc798bcb285eee6efb4467c6708['playback_limit'] = empty(ipTV_lib::$settings['playback_limit']) ? false : intval(ipTV_lib::$settings['playback_limit']);
                if (empty($A6f4ecc798bcb285eee6efb4467c6708['playback_limit'])) {
                    $A6f4ecc798bcb285eee6efb4467c6708['enable_playback_limit'] = false;
                }
                $A6f4ecc798bcb285eee6efb4467c6708['show_tv_channel_logo'] = empty(ipTV_lib::$settings['show_tv_channel_logo']) ? false : true;
                $A6f4ecc798bcb285eee6efb4467c6708['show_channel_logo_in_preview'] = empty(ipTV_lib::$settings['show_channel_logo_in_preview']) ? false : true;
                $A6f4ecc798bcb285eee6efb4467c6708['enable_connection_problem_indication'] = empty(ipTV_lib::$settings['enable_connection_problem_indication']) ? false : true;
                $A6f4ecc798bcb285eee6efb4467c6708['hls_fast_start'] = '1';
                $A6f4ecc798bcb285eee6efb4467c6708['check_ssl_certificate'] = 0;
                $A6f4ecc798bcb285eee6efb4467c6708['enable_buffering_indication'] = 1;
                $A6f4ecc798bcb285eee6efb4467c6708['watchdog_timeout'] = mt_rand(80, 120);
                if (empty($A6f4ecc798bcb285eee6efb4467c6708['aspect']) && ipTV_lib::$StreamingServers[SERVER_ID]['server_protocol'] == 'https') {
                    $A6f4ecc798bcb285eee6efb4467c6708['aspect'] = '16';
                }
                die(json_encode(array('js' => $A6f4ecc798bcb285eee6efb4467c6708), JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'get_localization':
                die(json_encode(array('js' => $D2b2ff0086dc5578693175fa65e7a22a[$dev['locale']])));
                break;
            case 'log':
                die(json_encode(array('js' => true)));
                break;
            case 'get_modules':
                $F39dfb610c58d8a2365eb0db08648621 = array('all_modules' => array('media_browser', 'tv', 'vclub', 'sclub', 'radio', 'apps', 'youtube', 'dvb', 'tv_archive', 'time_shift', 'time_shift_local', 'epg.reminder', 'epg.recorder', 'epg', 'epg.simple', 'audioclub', 'downloads_dialog', 'downloads', 'karaoke', 'weather.current', 'widget.audio', 'widget.radio', 'records', 'remotepvr', 'pvr_local', 'settings.parent', 'settings.localization', 'settings.update', 'settings.playback', 'settings.common', 'settings.network_status', 'settings', 'course.nbu', 'weather.day', 'cityinfo', 'horoscope', 'anecdote', 'game.mastermind', 'account', 'demo', 'infoportal', 'internet', 'service_management', 'logout', 'account_menu'), 'switchable_modules' => array('sclub', 'vlub', 'karaoke', 'cityinfo', 'horoscope', 'anecdote', 'game.mastermind'), 'disabled_modules' => array('weather.current', 'weather.day', 'cityinfo', 'karaoke', 'game.mastermind', 'records', 'downloads', 'remotepvr', 'service_management', 'settings.update', 'settings.common', 'audioclub', 'course.nbu', 'infoportal', 'demo', 'widget.audio', 'widget.radio'), 'restricted_modules' => array(), 'template' => $fadb655b1efb9a22103bc37699dce015, 'launcher_url' => '', 'launcher_profile_url' => 'http://193.235.147.182:80//stalker_portal//server/api/launcher_profile.php');
                die(json_encode(array('js' => $F39dfb610c58d8a2365eb0db08648621)));
                break;
        }
        break;
    case 'watchdog':
        $ipTV_db->query('UPDATE `mag_devices` SET `last_watchdog` = \'%d\' WHERE `mag_id` = \'%d\'', time(), $dev['mag_id']);
        switch ($req_action) {
            case 'get_events':
                $ipTV_db->query('SELECT * FROM `mag_events` WHERE `mag_device_id` = \'%d\' AND `status` = 0 ORDER BY `id` ASC LIMIT 1', $dev['mag_id']);
                $data = array('data' => array('msgs' => 0, 'additional_services_on' => 1));
                if ($ipTV_db->num_rows() > 0) {
                    $d8846db162701bfd0863836727234c28 = $ipTV_db->get_row();
                    $ipTV_db->query('SELECT count(*) FROM `mag_events` WHERE `mag_device_id` = \'%d\' AND `status` = 0 ', $dev['mag_id']);
                    $A047ccc36dd93060cfe0876b17cde44d = $ipTV_db->get_col();
                    $data = array('data' => array('msgs' => $A047ccc36dd93060cfe0876b17cde44d, 'id' => $d8846db162701bfd0863836727234c28['id'], 'event' => $d8846db162701bfd0863836727234c28['event'], 'need_confirm' => $d8846db162701bfd0863836727234c28['need_confirm'], 'msg' => $d8846db162701bfd0863836727234c28['msg'], 'reboot_after_ok' => $d8846db162701bfd0863836727234c28['reboot_after_ok'], 'auto_hide_timeout' => $d8846db162701bfd0863836727234c28['auto_hide_timeout'], 'send_time' => date('d-m-Y H:i:s', $d8846db162701bfd0863836727234c28['send_time']), 'additional_services_on' => $d8846db162701bfd0863836727234c28['additional_services_on'], 'updated' => array('anec' => $d8846db162701bfd0863836727234c28['anec'], 'vclub' => $d8846db162701bfd0863836727234c28['vclub'])));
                    $a733b4acb800bcb524c36282bfd66041 = array('reboot', 'reload_portal', 'play_channel', 'cut_off');
                    if (in_array($d8846db162701bfd0863836727234c28['event'], $a733b4acb800bcb524c36282bfd66041)) {
                        $ipTV_db->query('UPDATE `mag_events` SET `status` = 1 WHERE `id` = \'%d\'', $d8846db162701bfd0863836727234c28['id']);
                    }
                }
                die(json_encode(array('js' => $data), JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'confirm_event':
                if (!empty(ipTV_lib::$request['event_active_id'])) {
                    $b46d56fec05467fa8cb18d269ea00983 = ipTV_lib::$request['event_active_id'];
                    $ipTV_db->query('UPDATE `mag_events` SET `status` = 1 WHERE `id` = \'%d\'', $b46d56fec05467fa8cb18d269ea00983);
                    if ($ipTV_db->affected_rows() > 0) {
                        die(json_encode(array('js' => array('data' => 'ok'))));
                    }
                }
                break;
        }
}
if (!$F7af965c868ad940b4c181abc987875f) {
    CheckFlood();
    die;
}
$player = !empty($dev['mag_player']) ? $dev['mag_player'] . ' ' : 'ffmpeg ';
switch ($f34a0094f9db3be3b99dd1eb1e9a3b6d) {
    case 'stb':
        switch ($req_action) {
            case 'get_preload_images':
                $C370fbca7449b2b4b2e363169656ee17 = is_numeric($be4275c3d5887706bcf4db19dc01637e) ? 'i_' . $be4275c3d5887706bcf4db19dc01637e : 'i';
                $c4be797e5512579c392d632772cbd10b = array("template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/loading.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_6_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/ico_info.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_pass_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_info.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_menu_act.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_2_cloudy.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_sidepanel.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_search.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/v_menu_1a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/loading_bg.gif", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_11_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table_act01.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_11_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/tv_table.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/vol_1.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_prev_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_8_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_youtube.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_4_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/tv_table_arrows.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_9_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_10_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/1x1.gif", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_karaoke.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_video.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table05.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table_act02.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/tv_table_separator.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_icons.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_btn.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_5_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_audio.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_7_hail.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table_act05.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_9_snow.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/v_menu_4.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_3_pasmurno.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/low_q.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_setting.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_context_borders.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/input_episode_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table_act04.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_hor_bg3.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/black85.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/pause_btn.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/ico_error26.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/input_episode.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/epg_red_mark.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_sidepanel_act.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_3_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_pass_input.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_bg2.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/osd_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/epg_orange_mark.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_mb.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/ears_arrow_l.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/hr_filminfo.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_rec.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_account.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_icon_rec.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_hor_left.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table04.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_player.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_search_act2.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/input_channel_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_12_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_9_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_android.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_hor_right.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_quality.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table02.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/bg2.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_1_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/osd_line_pos.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/input_channel.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_7_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/arr_right.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_radio.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/ico_confirm.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/osd_btn.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/osd_time.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_menu.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/volume_off.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/btn2.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_internet.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/volume_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_1_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/v_menu_2b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_3_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_4_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_255_NA.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_1_sun_cl.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_10_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/25alfa_20.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table_act06.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/input.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/tv_table_focus.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/skip.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/epg_green_mark.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_vert_cell.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_1_moon_cl.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/modal_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_4_short_rain.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/ears_arrow_r.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_default.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/osd_line.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table07.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_usb.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_context_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_sidepanel_r.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_2_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/v_menu_1b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table03.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table_act03.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table01.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_dm.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_5_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_6_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_sidepanel_l.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_sidepanel_line.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_tv.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_table06.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_scroll_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_8_rain_swon.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_scroll.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/v_menu_2a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/v_menu_5.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_2_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_10_heavy_snow.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/aspect_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_0_moon.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/volume_bar.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/v_menu_3.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_hor_bg1.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_12_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_ex.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_sidepanel_arr.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mb_icon_scrambled.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/ico_alert.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_ico_apps.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/input_act.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/ears.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_8_a.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/mm_hor_bg2.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/arr_left.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/horoscope_menu_button_1_7_b.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/footer_search_act.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_0_sun.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_6_lightning.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/osd_rec.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/tv_prev_bg.png", "template/{$fadb655b1efb9a22103bc37699dce015}/{$C370fbca7449b2b4b2e363169656ee17}/_5_rain.png");
                die(json_encode(array('js' => $c4be797e5512579c392d632772cbd10b)));
                break;
            case 'get_settings_profile':
                $ipTV_db->query('SELECT * FROM `mag_devices` WHERE `mag_id` = \'%d\'', $dev['mag_id']);
                $Ad51a25e8830a754308d0fcf7239425b = $ipTV_db->get_row();
                $Ba14412cc90b8eefb2a5a78fae3ffa84 = array('js' => array('modules' => array(array('name' => 'lock'), array('name' => 'lang'), array('name' => 'update'), array('name' => 'net_info', 'sub' => array(array('name' => 'wired'), array('name' => 'pppoe', 'sub' => array(array('name' => 'dhcp'), array('name' => 'dhcp_manual'), array('name' => 'disable'))), array('name' => 'wireless'), array('name' => 'speed'))), array('name' => 'video'), array('name' => 'audio'), array('name' => 'net', 'sub' => array(array('name' => 'ethernet', 'sub' => array(array('name' => 'dhcp'), array('name' => 'dhcp_manual'), array('name' => 'manual'), array('name' => 'no_ip'))), array('name' => 'pppoe', 'sub' => array(array('name' => 'dhcp'), array('name' => 'dhcp_manual'), array('name' => 'disable'))), array('name' => 'wifi', 'sub' => array(array('name' => 'dhcp'), array('name' => 'dhcp_manual'), array('name' => 'manual'))), array('name' => 'speed'))), array('name' => 'advanced'), array('name' => 'dev_info'), array('name' => 'reload'), array('name' => 'internal_portal'), array('name' => 'reboot'))));
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['parent_password'] = $Ad51a25e8830a754308d0fcf7239425b['parent_password'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['update_url'] = ipTV_lib::$settings['update_url'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['test_download_url'] = ipTV_lib::$settings['test_download_url'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['playback_buffer_size'] = $Ad51a25e8830a754308d0fcf7239425b['playback_buffer_size'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['screensaver_delay'] = $Ad51a25e8830a754308d0fcf7239425b['screensaver_delay'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['plasma_saving'] = $Ad51a25e8830a754308d0fcf7239425b['plasma_saving'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['spdif_mode'] = $Ad51a25e8830a754308d0fcf7239425b['spdif_mode'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['ts_enabled'] = $Ad51a25e8830a754308d0fcf7239425b['ts_enabled'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['ts_enable_icon'] = $Ad51a25e8830a754308d0fcf7239425b['ts_enable_icon'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['ts_path'] = $Ad51a25e8830a754308d0fcf7239425b['ts_path'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['ts_max_length'] = $Ad51a25e8830a754308d0fcf7239425b['ts_max_length'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['ts_buffer_use'] = $Ad51a25e8830a754308d0fcf7239425b['ts_buffer_use'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['ts_action_on_exit'] = $Ad51a25e8830a754308d0fcf7239425b['ts_action_on_exit'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['ts_delay'] = $Ad51a25e8830a754308d0fcf7239425b['ts_delay'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['hdmi_event_reaction'] = $Ad51a25e8830a754308d0fcf7239425b['hdmi_event_reaction'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['pri_audio_lang'] = $Acbe482ea62dee218013e22b65162a3f['pri_audio_lang'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['show_after_loading'] = $Ad51a25e8830a754308d0fcf7239425b['show_after_loading'];
                $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['sec_audio_lang'] = $Acbe482ea62dee218013e22b65162a3f['sec_audio_lang'];
                if (ipTV_lib::$settings['always_enabled_subtitles'] == 1) {
                    $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['pri_subtitle_lang'] = $Acbe482ea62dee218013e22b65162a3f['pri_subtitle_lang'];
                    $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['sec_subtitle_lang'] = $Acbe482ea62dee218013e22b65162a3f['sec_subtitle_lang'];
                } else {
                    $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['pri_subtitle_lang'] = $Ba14412cc90b8eefb2a5a78fae3ffa84['js']['sec_subtitle_lang'] = '';
                }
                die(json_encode($Ba14412cc90b8eefb2a5a78fae3ffa84));
                break;
            case 'get_locales':
                $ipTV_db->query('SELECT `locale` FROM `mag_devices` WHERE `mag_id` = \'%d\'', $dev['mag_id']);
                $Da49f0440d38d30deabb4d3fa41d1ae7 = $ipTV_db->get_row();
                $output = array();
                foreach ($f636a7ecab05fca43624a8178337cef1['get_locales'] as $a151dae784c52c6ba073f125d1949afe => $C8028ababe676a3ab15fe53b7a4cc161) {
                    $F9c9bf468e4737faebeca1de82c8ca0b = $Da49f0440d38d30deabb4d3fa41d1ae7['locale'] == $C8028ababe676a3ab15fe53b7a4cc161 ? 1 : 0;
                }
                die(json_encode(array('js' => $output)));
                break;
            case 'get_countries':
                die(json_encode(array('js' => array())));
                break;
            case 'get_timezones':
                $A77d90c75b404413dc008921f0c45418 = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                $output = array();
                foreach ($A77d90c75b404413dc008921f0c45418 as $F452bdee727ee4eb46f405cf1c973026) {
                    $F9c9bf468e4737faebeca1de82c8ca0b = $timezone == $F452bdee727ee4eb46f405cf1c973026 ? 1 : 0;
                }
                die(json_encode(array('js' => $output)));
                break;
            case 'get_cities':
                die(json_encode(array('js' => array())));
                break;
            case 'search_cities':
                die(json_encode(array('js' => array())));
                break;
            case 'get_tv_aspects':
                if (!empty($dev['aspect'])) {
                    die($dev['aspect']);
                } else {
                    die(json_encode($dev['aspect']));
                }
                break;
            case 'set_volume':
                $b6fbc6940572a4b6179d85a9eede771e = ipTV_lib::$request['vol'];
                if (!empty($b6fbc6940572a4b6179d85a9eede771e)) {
                    $ipTV_db->query('UPDATE `mag_devices` SET `volume` = \'%d\' WHERE `mag_id` = \'%d\'', $b6fbc6940572a4b6179d85a9eede771e, $dev['mag_id']);
                    if ($ipTV_db->affected_rows() > 0) {
                        die(json_encode(array('data' => true)));
                    }
                }
                break;
            case 'set_aspect':
                $ch_id = ipTV_lib::$request['ch_id'];
                $e452199ba3dab31b943dff58d34047da = ipTV_lib::$request['aspect'];
                $d8e3d66bf231000bd757e5923159fa8f = $dev['aspect'];
                if (empty($d8e3d66bf231000bd757e5923159fa8f)) {
                    $ipTV_db->query('UPDATE `mag_devices` SET `aspect` = \'%s\' WHERE mag_id = \'%d\'', json_encode(array('js' => array($ch_id => $e452199ba3dab31b943dff58d34047da))), $dev['mag_id']);
                } else {
                    $d8e3d66bf231000bd757e5923159fa8f = json_decode($d8e3d66bf231000bd757e5923159fa8f, true);
                    $d8e3d66bf231000bd757e5923159fa8f['js'][$ch_id] = $e452199ba3dab31b943dff58d34047da;
                    $ipTV_db->query('UPDATE `mag_devices` SET `aspect` = \'%s\' WHERE mag_id = \'%d\'', json_encode($d8e3d66bf231000bd757e5923159fa8f), $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'set_stream_error':
                die(json_encode(array('js' => true)));
                break;
            case 'set_screensaver_delay':
                if (!empty($_SERVER['HTTP_COOKIE'])) {
                    $e50b36bfbe1ab40a7b84a3ac6d827159 = intval($_REQUEST['screensaver_delay']);
                    $ipTV_db->query('UPDATE `mag_devices` SET `screensaver_delay` = \'%d\' WHERE `mag_id` = \'%d\'', $e50b36bfbe1ab40a7b84a3ac6d827159, $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'set_playback_buffer':
                if (!empty($_SERVER['HTTP_COOKIE'])) {
                    $B4089f477d0edfc15e3d8d962a07aadf = intval($_REQUEST['playback_buffer_bytes']);
                    $db581b3169d84efa33b6e6b040e5af09 = intval($_REQUEST['playback_buffer_size']);
                    $ipTV_db->query('UPDATE `mag_devices` SET `playback_buffer_bytes` = \'%d\' , `playback_buffer_size` = \'%d\' WHERE `mag_id` = \'%d\'', $B4089f477d0edfc15e3d8d962a07aadf, $db581b3169d84efa33b6e6b040e5af09, $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'set_plasma_saving':
                $e720f242f850f63f3ca7da204d441fab = intval($_REQUEST['plasma_saving']);
                $ipTV_db->query('UPDATE `mag_devices` SET `plasma_saving` = \'%d\' WHERE `mag_id` = \'%d\'', $e720f242f850f63f3ca7da204d441fab, $dev['mag_id']);
                die(json_encode(array('js' => true)));
                break;
            case 'set_parent_password':
                if (isset($_REQUEST['parent_password']) && isset($_REQUEST['pass']) && isset($_REQUEST['repeat_pass']) && $_REQUEST['pass'] == $_REQUEST['repeat_pass']) {
                    $ipTV_db->query('UPDATE `mag_devices` SET `parent_password` = \'%s\' WHERE `mag_id` = \'%d\'', $_REQUEST['pass'], $dev['mag_id']);
                    die(json_encode(array('js' => true)));
                } else {
                    die(json_encode(array('js' => true)));
                }
                break;
            case 'set_locale':
                if (!empty(ipTV_lib::$request['locale'])) {
                    $ipTV_db->query('UPDATE `mag_devices` SET `locale` = \'%s\' WHERE `mag_id` = \'%d\'', ipTV_lib::$request['locale'], $dev['mag_id']);
                }
                die(json_encode(array('js' => array())));
                break;
            case 'set_hdmi_reaction':
                if (!empty($_SERVER['HTTP_COOKIE']) && isset($_REQUEST['data'])) {
                    $E9ca6897602dd4c8870714b6f8ec2e04 = $_REQUEST['data'];
                    $ipTV_db->query('UPDATE `mag_devices` SET `hdmi_event_reaction` = \'%s\' WHERE `mag_id` = \'%d\'', $E9ca6897602dd4c8870714b6f8ec2e04, $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
        }
        break;
    case 'audioclub':
        switch ($req_action) {
            case 'get_categories':
                $output = array();
                $output['js'] = array();
                $categories = GetCategories('movie');
                if (ipTV_lib::$settings['show_all_category_mag'] == 1) {
                    $output['js'][] = array('id' => '*', 'title' => 'All', 'alias' => '*', 'censored' => 0);
                }
                foreach ($categories[0] as $key => $category) {
                    $output['js'][] = array('id' => $category['id'], 'title' => $category['category_name'], 'alias' => $category['category_name'], 'censored' => stristr($category['category_name'], 'adults') ? 1 : 0);
                    //B2ef3a278e02c687404ee90946b1144b:
                }
                die(json_encode($output));
                break;
        }
        break;
    case 'itv':
        switch ($req_action) {
            case 'create_link':
                $cmd = ipTV_lib::$request['cmd'];
                $value = 'http://localhost/ch/';
                list($stream_id, $Faefd94a7363d43fe709e5aaa80f5fc7) = explode('_', substr($cmd, strpos($cmd, $value) + strlen($value)));
                if (empty($Faefd94a7363d43fe709e5aaa80f5fc7)) {
                    $play_token = ipTV_lib::GenerateString();
                    $ipTV_db->query('UPDATE `users` SET `play_token` = \'%s\' WHERE `id` = \'%d\'', $play_token . ':' . (time() + 10) . ':' . $stream_id, $dev['user_id']);
                    if (!file_exists(TMP_DIR . 'new_rewrite') || ipTV_lib::$settings['mag_container'] == 'm3u8') {
                        $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "live/{$dev['username']}/{$dev['password']}/{$stream_id}." . ipTV_lib::$settings['mag_container'] . '?play_token=' . $play_token;
                    } else {
                        $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "{$dev['username']}/{$dev['password']}/{$stream_id}?play_token={$play_token}";
                    }
                } else {
                    $url = $player . $Faefd94a7363d43fe709e5aaa80f5fc7;
                }
                die(json_encode(array('js' => array('id' => $stream_id, 'cmd' => $url), 'streamer_id' => 0, 'link_id' => 0, 'load' => 0, 'error' => '')));
                break;
            case 'set_claim':
                if (!empty(ipTV_lib::$request['id']) && !empty(ipTV_lib::$request['real_type'])) {
                    $id = intval(ipTV_lib::$request['id']);
                    $real_type = ipTV_lib::$request['real_type'];
                    $date = date('Y-m-d H:i:s');
                    $ipTV_db->query('INSERT INTO `mag_claims` (`stream_id`,`mag_id`,`real_type`,`date`) VALUES(\'%d\',\'%d\',\'%s\',\'%s\')', $id, $dev['mag_id'], $real_type, $date);
                }
                echo json_encode(array('js' => true));
                die;
                break;
            case 'set_fav':
                $d570fd4b0baf3fb34898da3935354f80 = empty($_REQUEST['fav_ch']) ? '' : $_REQUEST['fav_ch'];
                $d570fd4b0baf3fb34898da3935354f80 = array_filter(array_map('intval', explode(',', $d570fd4b0baf3fb34898da3935354f80)));
                $dev['fav_channels']['live'] = $d570fd4b0baf3fb34898da3935354f80;
                $ipTV_db->query('UPDATE `mag_devices` SET `fav_channels` = \'%s\' WHERE `mag_id` = \'%d\'', json_encode($dev['fav_channels']), $dev['mag_id']);
                die(json_encode(array('js' => true)));
                break;
            case 'get_fav_ids':
                echo json_encode(array('js' => $dev['fav_channels']['live']));
                die;
                break;
            case 'get_all_channels':
                die(eca2A16cfbd94b2b895bCBc43EBd6e3d(null, true));
                break;
            case 'get_ordered_list':
                $fav = !empty($_REQUEST['fav']) ? 1 : null;
                $sortby = !empty($_REQUEST['sortby']) ? $_REQUEST['sortby'] : null;
                $c8cfef85abbd40fe21f8ec737b50463c = empty($_REQUEST['genre']) || !is_numeric($_REQUEST['genre']) ? null : intval($_REQUEST['genre']);
                die(eca2a16CfBd94B2B895BcBC43eBD6E3D($c8cfef85abbd40fe21f8ec737b50463c, false, $fav, $sortby));
                break;
            case 'get_all_fav_channels':
                $c8cfef85abbd40fe21f8ec737b50463c = empty($_REQUEST['genre']) || !is_numeric($_REQUEST['genre']) ? null : intval($_REQUEST['genre']);
                die(ecA2a16cfBD94b2b895BCbC43EBD6E3D($c8cfef85abbd40fe21f8ec737b50463c, true, 1));
                break;
            case 'get_epg_info':
                $aa9e4071844cf08595f5a0d39f136cba = empty($_REQUEST['period']) || !is_numeric($_REQUEST['period']) ? 3 : intval($_REQUEST['period']);
                $streamSys = GetStreamsFromUser($dev['user_id'], array('live', 'created_live'));
                $epg = array('js' => array());
                $epg['js']['data'] = array();
                $fc504d0185065e8ba8f4f217b7aba6e8 = ipTV_lib::GetDateUTCTimestamp($timezone);
                foreach ($streamSys['streams'] as $e2ed3b44381d63b5ea576aae3e88df98 => $stream) {
                    if (empty($stream['channel_id'])) {
                        continue;
                    }
                    if (file_exists(TMP_DIR . "epg_info_{$stream['id']}_stalker")) {
                        $A4a19971c78a0cf0fc5b24bf655fa5dc = json_decode(file_get_contents(TMP_DIR . "epg_info_{$stream['id']}_stalker"), true);
                    } else {
                        $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp, UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `start` >= \'%s\' AND `end` <= \'%s\' AND `channel_id` = \'%s\' ORDER BY `start` ASC LIMIT 10', date('Y-m-d H:i:00'), date('Y-m-d H:i:00', strtotime("+{$aa9e4071844cf08595f5a0d39f136cba} hours")), $stream['channel_id']);
                        $A4a19971c78a0cf0fc5b24bf655fa5dc = $ipTV_db->get_rows();
                        file_put_contents(TMP_DIR . "epg_info_{$stream['id']}_stalker", json_encode($A4a19971c78a0cf0fc5b24bf655fa5dc));
                    }
                    if (!empty($A4a19971c78a0cf0fc5b24bf655fa5dc)) {
                        $index = 0;
                        Efe8605ad4469ab8b571a2ec18427318:
                        while ($index < count($A4a19971c78a0cf0fc5b24bf655fa5dc)) {
                            $epgStart = new DateTime($A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['start']);
                            $epgStart->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                            $epgEnd = new DateTime($A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['end']);
                            $epgEnd->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                            $epg['js']['data'][$stream['id']][$index]['id'] = $A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['id'];
                            $epg['js']['data'][$stream['id']][$index]['ch_id'] = $stream['id'];
                            $epg['js']['data'][$stream['id']][$index]['time'] = $epgStart->format('Y-m-d H:i:s');
                            $epg['js']['data'][$stream['id']][$index]['time_to'] = $epgEnd->format('Y-m-d H:i:s');
                            $epg['js']['data'][$stream['id']][$index]['duration'] = $A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['start_timestamp'] - $A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['stop_timestamp'];
                            $epg['js']['data'][$stream['id']][$index]['name'] = base64_decode($A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['title']);
                            $epg['js']['data'][$stream['id']][$index]['descr'] = base64_decode($A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['description']);
                            $epg['js']['data'][$stream['id']][$index]['real_id'] = $stream['id'] . '_' . $A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['start_timestamp'];
                            $epg['js']['data'][$stream['id']][$index]['category'] = '';
                            $epg['js']['data'][$stream['id']][$index]['director'] = '';
                            $epg['js']['data'][$stream['id']][$index]['actor'] = '';
                            $epg['js']['data'][$stream['id']][$index]['start_timestamp'] = $epgStart->getTimestamp();
                            $epg['js']['data'][$stream['id']][$index]['stop_timestamp'] = $epgEnd->getTimestamp();
                            $epg['js']['data'][$stream['id']][$index]['t_time'] = $epgStart->format('H:i');
                            $epg['js']['data'][$stream['id']][$index]['t_time_to'] = $epgEnd->format('H:i');
                            $epg['js']['data'][$stream['id']][$index]['display_duration'] = $A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['start_timestamp'] - $A4a19971c78a0cf0fc5b24bf655fa5dc[$index]['stop_timestamp'];
                            $epg['js']['data'][$stream['id']][$index]['larr'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['rarr'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['mark_rec'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['mark_memo'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['mark_archive'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['on_date'] = $epgStart->format('l d.m.Y');
                            $index++;
                        }
                        //e64bf09bc6e32dfd2d9407407a6cc935:
                    }
                    //a8d3863014507990f8d5600485d0c5eb:
                }
                die(json_encode($epg, JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'set_fav_status':
                die(json_encode(array('js' => array())));
                break;
            case 'get_short_epg':
                if (!empty($_REQUEST['ch_id'])) {
                    $ch_id = $_REQUEST['ch_id'];
                    $epg = array('js' => array());
                    if (file_exists(TMP_DIR . "epg_{$ch_id}_stalker")) {
                        $fae8d113c9c337189077bc9a21138e1c = json_decode(file_get_contents(TMP_DIR . "epg_{$ch_id}_stalker"), true);
                    } else {
                        $fae8d113c9c337189077bc9a21138e1c = array();
                        $ipTV_db->query('SELECT `channel_id`,`epg_id` FROM `streams` WHERE `id` = \'%d\' AND epg_id IS NOT NULL', $ch_id);
                        if ($ipTV_db->num_rows() > 0) {
                            $epg_data = $ipTV_db->get_row();
                            $ipTV_db->simple_query("SELECT *,UNIX_TIMESTAMP(start) as start_timestamp, UNIX_TIMESTAMP(end) as stop_timestamp  FROM `epg_data` WHERE `epg_id` = '{$epg_data['epg_id']}' AND `channel_id` = '" . $ipTV_db->escape($epg_data['channel_id']) . '\' AND (\'' . date('Y-m-d H:i:00') . '\' BETWEEN `start` AND `end` OR `start` >= \'' . date('Y-m-d H:i:00') . '\') ORDER BY `start` LIMIT 4');
                            if ($ipTV_db->num_rows() > 0) {
                                $fae8d113c9c337189077bc9a21138e1c = $ipTV_db->get_rows();
                            }
                        }
                        file_put_contents(TMP_DIR . "epg_{$ch_id}_stalker", json_encode($fae8d113c9c337189077bc9a21138e1c));
                    }
                    if (!empty($fae8d113c9c337189077bc9a21138e1c)) {
                        $fc504d0185065e8ba8f4f217b7aba6e8 = ipTV_lib::GetDateUTCTimestamp($timezone);
                        $index = 0;
                        //Cbe729c6cd29f19a001104345f98ca72:
                        while ($index < count($fae8d113c9c337189077bc9a21138e1c)) {
                            $epgStart = new DateTime($fae8d113c9c337189077bc9a21138e1c[$index]['start']);
                            $epgStart->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                            $epgEnd = new DateTime($fae8d113c9c337189077bc9a21138e1c[$index]['end']);
                            $epgEnd->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                            $epg['js'][$index]['id'] = $fae8d113c9c337189077bc9a21138e1c[$index]['id'];
                            $epg['js'][$index]['ch_id'] = $ch_id;
                            $epg['js'][$index]['correct'] = $epgStart->format('Y-m-d H:i:s');
                            $epg['js'][$index]['time'] = $epgStart->format('Y-m-d H:i:s');
                            $epg['js'][$index]['time_to'] = $epgEnd->format('Y-m-d H:i:s');
                            $epg['js'][$index]['duration'] = $fae8d113c9c337189077bc9a21138e1c[$index]['stop_timestamp'] - $fae8d113c9c337189077bc9a21138e1c[$index]['start_timestamp'];
                            $epg['js'][$index]['name'] = base64_decode($fae8d113c9c337189077bc9a21138e1c[$index]['title']);
                            $epg['js'][$index]['descr'] = base64_decode($fae8d113c9c337189077bc9a21138e1c[$index]['description']);
                            $epg['js'][$index]['real_id'] = $ch_id . '_' . $fae8d113c9c337189077bc9a21138e1c[$index]['start_timestamp'];
                            $epg['js'][$index]['category'] = '';
                            $epg['js'][$index]['director'] = '';
                            $epg['js'][$index]['actor'] = '';
                            $epg['js'][$index]['start_timestamp'] = $epgStart->getTimestamp();
                            $epg['js'][$index]['stop_timestamp'] = $epgEnd->getTimestamp();
                            $epg['js'][$index]['t_time'] = $epgStart->format('H:i');
                            $epg['js'][$index]['t_time_to'] = $epgEnd->format('H:i');
                            $epg['js'][$index]['mark_memo'] = 0;
                            $epg['js'][$index]['mark_archive'] = 0;
                            $index++;
                        }
                        //e6c42594269c549c969abd2b1d3706f8:
                    }
                }
                die(json_encode($epg, JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'set_played':
                die(json_encode(array('js' => true)));
                break;
            case 'set_last_id':
                $ch_id = intval($_REQUEST['id']);
                if ($ch_id > 0) {
                    $ipTV_db->query('UPDATE `mag_devices` SET `last_itv_id` = \'%d\' WHERE `mag_id` = \'%d\'', $ch_id, $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'get_genres':
                $output = array();
                $episode_num = 1;
                $F93ee1f4357cf2c3676871a1bc44af65 = GetCategories('live');
                if (ipTV_lib::$settings['show_all_category_mag'] == 1) {
                    $output['js'][] = array('id' => '*', 'title' => 'All', 'alias' => 'All', 'active_sub' => true, 'censored' => 0);
                }
                foreach ($F93ee1f4357cf2c3676871a1bc44af65 as $b10d12e0226d30efcf0ab5f1cb845a0a => $F444052f6fa1f23fa68ffcaea6bde218) {
                    if (!ipTV_streaming::CategoriesBouq($b10d12e0226d30efcf0ab5f1cb845a0a, $dev['bouquet'])) {
                        continue;
                    }
                    $output['js'][] = array('id' => $F444052f6fa1f23fa68ffcaea6bde218['id'], 'title' => $F444052f6fa1f23fa68ffcaea6bde218['category_name'], 'modified' => '', 'number' => $episode_num++, 'alias' => mb_strtolower($F444052f6fa1f23fa68ffcaea6bde218['category_name']), 'censored' => stristr($F444052f6fa1f23fa68ffcaea6bde218['category_name'], 'adults') ? 1 : 0);
                    //E5d00a1e5e2edd1b54f2044710b22125:
                }
                die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
        }
        break;
    case 'remote_pvr':
        switch ($req_action) {
            case 'start_record_on_stb':
                die(json_encode(array('js' => true)));
                break;
            case 'stop_record_on_stb':
                die(json_encode(array('js' => true)));
                break;
            case 'get_active_recordings':
                die(json_encode(array('js' => array())));
                break;
        }
        break;
    case 'media_favorites':
        switch ($req_action) {
            case 'get_all':
                die(json_encode(array('js' => '')));
                break;
        }
        break;
    case 'tvreminder':
        switch ($req_action) {
            case 'get_all_active':
                die(json_encode(array('js' => array())));
                break;
        }
        break;
    case 'vod':
        switch ($req_action) {
            case 'set_claim':
                if (!empty(ipTV_lib::$request['id']) && !empty(ipTV_lib::$request['real_type'])) {
                    $id = intval(ipTV_lib::$request['id']);
                    $real_type = ipTV_lib::$request['real_type'];
                    $date = date('Y-m-d H:i:s');
                    $ipTV_db->query('INSERT INTO `mag_claims` (`stream_id`,`mag_id`,`real_type`,`date`) VALUES(\'%d\',\'%d\',\'%s\',\'%s\')', $id, $dev['mag_id'], $real_type, $date);
                }
                echo json_encode(array('js' => true));
                die;
                break;
            case 'set_not_ended':
                die(json_encode(array('js' => true)));
                break;
            case 'del_link':
                die(json_encode(array('js' => true)));
                break;
            case 'set_fav':
                if (!empty($_REQUEST['video_id'])) {
                    $video_id = intval($_REQUEST['video_id']);
                    if (!in_array($video_id, $dev['fav_channels']['movie'])) {
                        $dev['fav_channels']['movie'][] = $video_id;
                    }
                    $ipTV_db->query('UPDATE `mag_devices` SET `fav_channels` = \'%s\' WHERE `mag_id` = \'%d\'', json_encode($dev['fav_channels']), $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'del_fav':
                if (!empty($_REQUEST['video_id'])) {
                    $video_id = intval($_REQUEST['video_id']);
                    foreach ($dev['fav_channels']['movie'] as $key => $val) {
                        if ($val == $video_id) {
                            unset($dev['fav_channels']['movie'][$key]);
                            break;
                        }
                    }
                    $ipTV_db->query('UPDATE `mag_devices` SET `fav_channels` = \'%s\' WHERE `mag_id` = \'%d\'', json_encode($dev['fav_channels']), $dev['mag_id']);
                    break;
                }
                die(json_encode(array('js' => true)));
                break;
            case 'get_categories':
                $output = array();
                $output['js'] = array();
                $categories = GetCategories('movie');
                $F88f39f6dd753f755da2ba80e581e1fb = array_column($user_info['channels'], 'category_id');
                if (ipTV_lib::$settings['show_all_category_mag'] == 1) {
                    $output['js'][] = array('id' => '*', 'title' => 'All', 'alias' => '*', 'censored' => 0);
                }
                foreach ($categories as $key => $category) {
                    if (!ipTV_streaming::CategoriesBouq($key, $dev['bouquet'])) {
                        continue;
                    }
                    $output['js'][] = array('id' => $category['id'], 'title' => $category['category_name'], 'alias' => $category['category_name'], 'censored' => stristr($category['category_name'], 'adults') ? 1 : 0);
                    //b992240b0462e23736383e3eef2b2196:
                }
                die(json_encode($output));
                break;
            case 'get_genres_by_category_alias':
                $output = array();
                $output['js'][] = array('id' => '*', 'title' => '*');
                die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'get_years':
                die(json_encode($_MAG_DATA['get_years']));
                break;
            case 'get_ordered_list':
                $category = !empty(ipTV_lib::$request['category']) && is_numeric(ipTV_lib::$request['category']) ? ipTV_lib::$request['category'] : null;
                $fav = !empty($_REQUEST['fav']) ? 1 : null;
                $sortby = !empty($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 'added';
                $search = !empty($_REQUEST['search']) ? $_REQUEST['search'] : null;
                $movie_properties = array();
                $movie_properties['abc'] = !empty(ipTV_lib::$request['abc']) ? ipTV_lib::$request['abc'] : '*';
                $movie_properties['genre'] = !empty(ipTV_lib::$request['genre']) ? ipTV_lib::$request['genre'] : '*';
                $movie_properties['years'] = !empty(ipTV_lib::$request['years']) ? ipTV_lib::$request['years'] : '*';
                die(a4977163C2c5a8cc74F19596F616Aeee($category, $fav, $sortby, $search, $movie_properties));
                break;
            case 'create_link':
                $cmd = ipTV_lib::$request['cmd'];
                $series = !empty(ipTV_lib::$request['series']) ? (int) ipTV_lib::$request['series'] : 0;
                $error = '';
                if (!stristr($cmd, '/media/')) {
                    $cmd = json_decode(base64_decode($cmd), true);
                } else {
                    $cmd = array('series_data' => $cmd, 'type' => 'series');
                }
                $play_token = ipTV_lib::GenerateString();
                switch ($cmd['type']) {
                    case 'movie':
                        if (!empty($cmd['stream_source'])) {
                            $url = $player . json_decode($cmd['stream_source'], true)[0];
                        } else {
                            $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "{$cmd['type']}/{$dev['username']}/{$dev['password']}/{$cmd['stream_id']}." . GetContainerExtension($cmd['target_container'], true) . '?play_token=' . $play_token;
                        }
                        break;
                    case 'series':
                        if (!empty($cmd['series_data'])) {
                            list($cmd['series_id'], $cmd['season_num']) = explode(':', basename($cmd['series_data'], '.mpg'));
                        }
                        $ipTV_db->query('SELECT t1.stream_id,if(t2.direct_source = 1 AND t2.redirect_stream = 0,t2.stream_source,NULL) as stream_source,t2.target_container FROM `series_episodes` t1 INNER JOIN `streams` t2 ON t2.id = t1.stream_id WHERE t1.`sort` = \'%d\' AND t1.`series_id` = \'%d\' AND t1.`season_num` = \'%d\' LIMIT 1', $series, $cmd['series_id'], $cmd['season_num']);
                        if ($ipTV_db->num_rows() > 0) {
                            $row = $ipTV_db->get_row();
                            $cmd['stream_id'] = $row['stream_id'];
                            if (!empty($row['stream_source'])) {
                                $url = $player . json_decode($row['stream_source'], true)[0];
                            } else {
                                $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "{$cmd['type']}/{$dev['username']}/{$dev['password']}/{$cmd['stream_id']}." . GetContainerExtension($row['target_container'], true) . '?play_token=' . $play_token;
                            }
                        } else {
                            $error = 'player_file_missing';
                        }
                        break;
                }
                $ipTV_db->query('UPDATE `users` SET `play_token` = \'%s\' WHERE `id` = \'%d\'', $play_token . ':' . strtotime('+5 hours') . ':' . $cmd['stream_id'], $dev['user_id']);
                $output = array('js' => array('id' => $cmd['stream_id'], 'cmd' => $url, 'load' => '', 'subtitles' => array(), 'error' => $error));
                die(json_encode($output));
                break;
            case 'log':
                die(json_encode(array('js' => 1)));
                break;
            case 'get_abc':
                die(json_encode($_MAG_DATA['get_abc']));
                break;
        }
        break;
    case 'series':
        switch ($req_action) {
            case 'set_claim':
                if (!empty(ipTV_lib::$request['id']) && !empty(ipTV_lib::$request['real_type'])) {
                    $id = intval(ipTV_lib::$request['id']);
                    $real_type = ipTV_lib::$request['real_type'];
                    $date = date('Y-m-d H:i:s');
                    $ipTV_db->query('INSERT INTO `mag_claims` (`stream_id`,`mag_id`,`real_type`,`date`) VALUES(\'%d\',\'%d\',\'%s\',\'%s\')', $id, $dev['mag_id'], $real_type, $date);
                }
                echo json_encode(array('js' => true));
                die;
                break;
            case 'set_not_ended':
                die(json_encode(array('js' => true)));
                break;
            case 'del_link':
                die(json_encode(array('js' => true)));
                break;
            case 'set_fav':
                if (!empty($_REQUEST['video_id'])) {
                    $video_id = intval($_REQUEST['video_id']);
                    if (!in_array($video_id, $dev['fav_channels']['series'])) {
                        $dev['fav_channels']['series'][] = $video_id;
                    }
                    $ipTV_db->query('UPDATE `mag_devices` SET `fav_channels` = \'%s\' WHERE `mag_id` = \'%d\'', json_encode($dev['fav_channels']), $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'del_fav':
                if (!empty($_REQUEST['video_id'])) {
                    $video_id = intval($_REQUEST['video_id']);
                    foreach ($dev['fav_channels']['series'] as $key => $val) {
                        if ($val == $video_id) {
                            unset($dev['fav_channels']['series'][$key]);
                            break;
                        }
                    }
                    $ipTV_db->query('UPDATE `mag_devices` SET `fav_channels` = \'%s\' WHERE `mag_id` = \'%d\'', json_encode($dev['fav_channels']), $dev['mag_id']);
                    break;
                }
                die(json_encode(array('js' => true)));
                break;
            case 'get_categories':
                $output = array();
                $output['js'] = array();
                $categories = GetCategories('series');
                if (ipTV_lib::$settings['show_all_category_mag'] == 1) {
                    $output['js'][] = array('id' => '*', 'title' => 'All', 'alias' => '*', 'censored' => 0);
                }
                foreach ($categories as $key => $category) {
                    $output['js'][] = array('id' => $category['id'], 'title' => $category['category_name'], 'alias' => $category['category_name'], 'censored' => stristr($category['category_name'], 'adults') ? 1 : 0);
                }
                die(json_encode($output));
                break;
            case 'get_genres_by_category_alias':
                $output = array();
                $output['js'][] = array('id' => '*', 'title' => '*');
                die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'get_years':
                die(json_encode($_MAG_DATA['get_years']));
                break;
            case 'get_ordered_list':
                $category = !empty(ipTV_lib::$request['category']) && is_numeric(ipTV_lib::$request['category']) ? ipTV_lib::$request['category'] : null;
                $fav = !empty($_REQUEST['fav']) ? 1 : null;
                $sortby = !empty($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 'added';
                $search = !empty($_REQUEST['search']) ? $_REQUEST['search'] : null;
                $movie_id = !empty($_REQUEST['movie_id']) ? (int) $_REQUEST['movie_id'] : null;
                $movie_properties = array();
                $movie_properties['abc'] = !empty(ipTV_lib::$request['abc']) ? ipTV_lib::$request['abc'] : '*';
                $movie_properties['genre'] = !empty(ipTV_lib::$request['genre']) ? ipTV_lib::$request['genre'] : '*';
                $movie_properties['years'] = !empty(ipTV_lib::$request['years']) ? ipTV_lib::$request['years'] : '*';
                die(e9967fBb02A1eBf83F92f22e140AEBF9($movie_id, $category, $fav, $sortby, $search, $movie_properties));
                break;
            case 'log':
                die(json_encode(array('js' => 1)));
                break;
            case 'get_abc':
                die(json_encode($_MAG_DATA['get_abc']));
                break;
        }
        break;
    case 'downloads':
        switch ($req_action) {
            case 'save':
                die(json_encode(array('js' => true)));
                break;
            case 'get_all':
                die(json_encode(array('js' => '""')));
                break;
            case 'get_all':
                die(json_encode(array('js' => true)));
                break;
        }
        break;
    case 'weatherco':
        switch ($req_action) {
            case 'get_current':
                die(json_encode(array('js' => false)));
                break;
        }
        break;
    case 'course':
        switch ($req_action) {
            case 'get_data':
                die(json_encode(array('js' => true)));
                break;
        }
        break;
    case 'account_info':
        switch ($req_action) {
            case 'get_terms_info':
                die(json_encode(array('js' => true)));
                break;
            case 'get_payment_info':
                die(json_encode(array('js' => true)));
                break;
            case 'get_main_info':
                if (empty($dev['exp_date'])) {
                    $be24df3d43b7c72e96a81de919724a70 = $A22f04f5efe932bdb34320e92642825a['unlimited'];
                } else {
                    $be24df3d43b7c72e96a81de919724a70 = date('F j, Y, g:i a', $dev['exp_date']);
                }
                die(json_encode(array('js' => array('mac' => $mac, 'phone' => $be24df3d43b7c72e96a81de919724a70))));
                break;
            case 'get_demo_video_parts':
                die(json_encode(array('js' => true)));
                break;
            case 'get_agreement_info':
                die(json_encode(array('js' => true)));
                break;
        }
        break;
    case 'radio':
        switch ($req_action) {
            case 'get_ordered_list':
                $fav = !empty($_REQUEST['fav']) ? 1 : null;
                $sortby = !empty($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 'added';
                die(c22100704F3f8811F51506Fb1AB116da(null, $fav, $sortby));
                break;
            case 'get_all_fav_radio':
                die(c22100704F3F8811f51506fb1Ab116DA(null, 1, null));
                break;
            case 'set_fav':
                $a828b07e8eaa2884b37ec1b6ac498cbc = empty($_REQUEST['fav_radio']) ? '' : $_REQUEST['fav_radio'];
                $a828b07e8eaa2884b37ec1b6ac498cbc = array_filter(array_map('intval', explode(',', $a828b07e8eaa2884b37ec1b6ac498cbc)));
                $dev['fav_channels']['radio_streams'] = $a828b07e8eaa2884b37ec1b6ac498cbc;
                $ipTV_db->query('UPDATE `mag_devices` SET `fav_channels` = \'%s\' WHERE `mag_id` = \'%d\'', json_encode($dev['fav_channels']), $dev['mag_id']);
                die(json_encode(array('js' => true)));
                break;
            case 'get_fav_ids':
                die(json_encode(array('js' => $dev['fav_channels']['radio_streams'])));
                break;
        }
        break;
    case 'tv_archive':
        switch ($req_action) {
            case 'get_next_part_url':
                if (!empty(ipTV_lib::$request['id'])) {
                    $id = ipTV_lib::$request['id'];
                    $stream_id = substr($id, 0, strpos($id, '_'));
                    $date = substr($id, strpos($id, '_') + 1);
                    $ipTV_db->query('SELECT
                                      t2.*
                                    FROM
                                      `streams` t1
                                    INNER JOIN
                                      `epg_data` t2
                                    ON
                                      t2.channel_id = t1.channel_id AND t2.epg_id = t1.epg_id
                                    WHERE
                                      t1.id = \'%d\'
                                      AND t2.`start` > \'%s\' ORDER BY t2.start ASC limit 1;', $stream_id, $date);
                    if ($ipTV_db->num_rows() > 0) {
                        $row = $ipTV_db->get_row();
                        $d63483f71b465511492459c030579e0e = date('Y-m-d:H-i', strtotime($row['start']));
                        $duration = intval((strtotime($row['end']) - strtotime($row['start'])) / 60);
                        $E4416ae8f96620daee43ac43f9515200 = base64_decode($row['title']);
                        $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "timeshift/{$dev['username']}/{$dev['password']}/{$duration}/{$d63483f71b465511492459c030579e0e}/{$stream_id}.ts?&osd_title={$E4416ae8f96620daee43ac43f9515200}";
                        die(json_encode(array('js' => $url)));
                    }
                }
                die(json_encode(array('js' => false)));
                break;
            case 'create_link':
                $cmd = empty($_REQUEST['cmd']) ? '' : $_REQUEST['cmd'];
                list($E2b08d0d6a74fb4e054587ee7c572a9f, $stream_id) = explode('_', pathinfo($cmd)['filename']);
                $ipTV_db->query('SELECT t2.tv_archive_server_id,t1.start,t1.end,t2.id as stream_id
                                    FROM epg_data t1
                                    INNER JOIN `streams` t2 ON t2.id = \'%d\'
                                    WHERE t1.id = \'%d\' AND t2.tv_archive_server_id IS NOT NULL', $stream_id, $E2b08d0d6a74fb4e054587ee7c572a9f);
                if ($ipTV_db->num_rows() > 0) {
                    $row = $ipTV_db->get_row();
                    $d63483f71b465511492459c030579e0e = date('Y-m-d:H-i', strtotime($row['start']));
                    $duration = intval((strtotime($row['end']) - strtotime($row['start'])) / 60);
                    $play_token = ipTV_lib::GenerateString();
                    $ipTV_db->query('UPDATE `users` SET `play_token` = \'%s\' WHERE `id` = \'%d\'', $play_token . ':' . strtotime('+5 hours') . ':' . $stream_id, $dev['user_id']);
                    $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "timeshift/{$dev['username']}/{$dev['password']}/{$duration}/{$d63483f71b465511492459c030579e0e}/{$row['stream_id']}.ts?play_token={$play_token}";
                    $output['js'] = array('id' => 0, 'cmd' => $url, 'storage_id' => '', 'load' => 0, 'error' => '', 'download_cmd' => $url, 'to_file' => '');
                    die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
                }
                break;
            case 'get_link_for_channel':
                $output = array();
                $ch_id = !empty($_REQUEST['ch_id']) ? intval($_REQUEST['ch_id']) : 0;
                $start = date('Ymd-H');
                $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "timeshifts/{$dev['username']}/{$dev['password']}/60/{$ch_id}/{$start}.ts position:" . (intval(date('i')) * 60 + intval(date('s'))) . ' media_len:' . (intval(date('H')) * 3600 + intval(date('i')) * 60 + intval(date('s')));
                $output['js'] = array('id' => 0, 'cmd' => $url, 'storage_id' => '', 'load' => 0, 'error' => '');
                die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'set_played_timeshift':
                die(json_encode(array('js' => true)));
                break;
            case 'set_played':
                die(json_encode(array('js' => true)));
                break;
            case 'update_played_timeshift_end_time':
                die(json_encode(array('js' => true)));
                break;
        }
        break;
    case 'epg':
        switch ($req_action) {
            case 'get_week':
                $k = -16;
                $index = 0;
                $E42fe0abca3971a7cdea25259fa394a6 = array();
                $D79233cfa579ef72705a7b46e93931a9 = strtotime(date('Y-m-d'));
                //Cd94960334dd6329471afcf9a386b54c:
                while ($k < 10) {
                    $b301ce8cde6092fd3b68a9e120668bee = $D79233cfa579ef72705a7b46e93931a9 + $k * 86400;
                    $E42fe0abca3971a7cdea25259fa394a6['js'][$index]['f_human'] = date('D d F', $b301ce8cde6092fd3b68a9e120668bee);
                    $E42fe0abca3971a7cdea25259fa394a6['js'][$index]['f_mysql'] = date('Y-m-d', $b301ce8cde6092fd3b68a9e120668bee);
                    $E42fe0abca3971a7cdea25259fa394a6['js'][$index]['today'] = $k == 0 ? 1 : 0;
                    $k++;
                    $index++;
                }
                //f840d3a8d11964d7693313323a114644:
                die(json_encode($E42fe0abca3971a7cdea25259fa394a6));
                break;
            case 'get_data_table':
                die(json_encode(array('js' => getDataTable()), JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'get_simple_data_table':
                if (!empty($_REQUEST['ch_id']) && !empty($_REQUEST['date'])) {
                    $ch_id = $_REQUEST['ch_id'];
                    $e858a910ee73be3fcdb2d830280bb7d7 = $_REQUEST['date'];
                    $page = intval($_REQUEST['p']);
                    $page_items = 10;
                    $default_page = false;
                    $ipTV_db->query('SELECT `tv_archive_duration`,`channel_id`,`epg_id` FROM `streams` WHERE `id` = \'%d\' AND epg_id IS NOT NULL', $ch_id);
                    $b8fec948178730cb33571f551f1c12e8 = array();
                    $e4e9636f31489a09d3147a77b368eb6b = 0;
                    $ch_idx = 0;
                    if ($ipTV_db->num_rows() > 0) {
                        $stream = $ipTV_db->get_row();
                        $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp,UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `epg_id` = \'%d\' AND `channel_id` = \'%s\' AND `start` >= \'%s\' AND `start` <= \'%s\' ORDER BY `start` ASC', $stream['epg_id'], $stream['channel_id'], $e858a910ee73be3fcdb2d830280bb7d7 . ' 00:00:00', $e858a910ee73be3fcdb2d830280bb7d7 . ' 23:59:59');
                        if ($ipTV_db->num_rows() > 0) {
                            $b8fec948178730cb33571f551f1c12e8 = $ipTV_db->get_rows();
                            foreach ($b8fec948178730cb33571f551f1c12e8 as $key => $epg_data) {
                                if ($epg_data['start_timestamp'] <= time() && $epg_data['stop_timestamp'] >= time()) {
                                    $ch_idx = $key + 1;
                                    break;
                                }
                            }
                        }
                    }
                    if ($page == 0) {
                        $default_page = true;
                        $page = ceil($ch_idx / $page_items);
                        if ($page == 0) {
                            $page = 1;
                        }
                        if ($e858a910ee73be3fcdb2d830280bb7d7 != date('Y-m-d')) {
                            $page = 1;
                            $default_page = false;
                        }
                    }
                    $c81046da85315ba6f88258146eb675e8 = array_slice($b8fec948178730cb33571f551f1c12e8, ($page - 1) * $page_items, $page_items);
                    $data = array();
                    $fc504d0185065e8ba8f4f217b7aba6e8 = ipTV_lib::GetDateUTCTimestamp($timezone);
                    $index = 0;
                    while ($index < count($c81046da85315ba6f88258146eb675e8)) {
                        $F20a3d0e0b9a0bf63a0bd3e44254eb8b = 0;
                        if ($c81046da85315ba6f88258146eb675e8[$index]['stop_timestamp'] >= time()) {
                            $F20a3d0e0b9a0bf63a0bd3e44254eb8b = 1;
                        }
                        $epgStart = new DateTime($c81046da85315ba6f88258146eb675e8[$index]['start']);
                        $epgStart->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                        $epgEnd = new DateTime($c81046da85315ba6f88258146eb675e8[$index]['end']);
                        $epgEnd->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                        $data[$index]['id'] = $c81046da85315ba6f88258146eb675e8[$index]['id'] . '_' . $ch_id;
                        $data[$index]['ch_id'] = $ch_id;
                        $data[$index]['time'] = $epgStart->format('Y-m-d H:i:s');
                        $data[$index]['time_to'] = $epgEnd->format('Y-m-d H:i:s');
                        $data[$index]['duration'] = $c81046da85315ba6f88258146eb675e8[$index]['stop_timestamp'] - $c81046da85315ba6f88258146eb675e8[$index]['start_timestamp'];
                        $data[$index]['name'] = base64_decode($c81046da85315ba6f88258146eb675e8[$index]['title']);
                        $data[$index]['descr'] = base64_decode($c81046da85315ba6f88258146eb675e8[$index]['description']);
                        $data[$index]['real_id'] = $ch_id . '_' . $c81046da85315ba6f88258146eb675e8[$index]['start'];
                        $data[$index]['category'] = '';
                        $data[$index]['director'] = '';
                        $data[$index]['actor'] = '';
                        $data[$index]['start_timestamp'] = $epgStart->getTimestamp();
                        $data[$index]['stop_timestamp'] = $epgEnd->getTimestamp();
                        $data[$index]['t_time'] = $epgStart->format('H:i');
                        $data[$index]['t_time_to'] = $epgEnd->format('H:i');
                        $data[$index]['open'] = $F20a3d0e0b9a0bf63a0bd3e44254eb8b;
                        $data[$index]['mark_memo'] = 0;
                        $data[$index]['mark_rec'] = 0;
                        $data[$index]['mark_archive'] = !empty($stream['tv_archive_duration']) && time() > $epgEnd->getTimestamp() && strtotime("-{$stream['tv_archive_duration']} days") <= $epgEnd->getTimestamp() ? 1 : 0;
                        $index++;
                    }
                    //befc60667c01acf39fd47ab7192b135e:
                    if ($default_page) {
                        $a5059501c38fcd5e6e2e3af8e53670bb = $page;
                        $ca959924790f641d3f5e6f3eda4ee518 = $ch_idx - ($page - 1) * $page_items;
                    } else {
                        $a5059501c38fcd5e6e2e3af8e53670bb = 0;
                        $ca959924790f641d3f5e6f3eda4ee518 = 0;
                    }
                    $output = array();
                    $output['js']['cur_page'] = $a5059501c38fcd5e6e2e3af8e53670bb;
                    $output['js']['selected_item'] = $ca959924790f641d3f5e6f3eda4ee518;
                    $output['js']['total_items'] = count($b8fec948178730cb33571f551f1c12e8);
                    $output['js']['max_page_items'] = $page_items;
                    $output['js']['data'] = $data;
                    die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
                }
                die;
                break;
            case 'get_all_program_for_ch':
                $output = array();
                $output['js'] = array();
                $ch_id = empty($_REQUEST['ch_id']) ? 0 : intval($_REQUEST['ch_id']);
                $ipTV_db->query('SELECT `tv_archive_duration`,`channel_id`,`epg_id` FROM `streams` WHERE `id` = \'%d\' AND epg_id IS NOT NULL', $ch_id);
                if ($ipTV_db->num_rows() > 0) {
                    $stream = $ipTV_db->get_row();
                    $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp,UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `epg_id` = \'%d\' AND `channel_id` = \'%s\' AND `start` >= \'%s\' ORDER BY `start` ASC', $stream['epg_id'], $stream['channel_id'], date('Y-m-d 00:00:00'));
                    if ($ipTV_db->num_rows() > 0) {
                        $fc504d0185065e8ba8f4f217b7aba6e8 = ipTV_lib::GetDateUTCTimestamp($timezone);
                        foreach ($ipTV_db->get_rows() as $row) {
                            $epgStart = new DateTime($row['start']);
                            $epgStart->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                            $epgEnd = new DateTime($row['end']);
                            $epgEnd->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                            $output['js'][] = array('start_timestamp' => $epgStart->getTimestamp(), 'stop_timestamp' => $epgEnd->getTimestamp(), 'name' => base64_decode($row['title']));
                        }
                    }
                }
                die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
        }
        break;
}
function a4977163c2c5a8CC74F19596F616AEeE($category_id = null, $fav = null, $orderby = null, $stream_display_name = null, $movie_properties = array())
{
    global $dev, $player, $A22f04f5efe932bdb34320e92642825a;
    $page = !empty(ipTV_lib::$request['p']) ? ipTV_lib::$request['p'] : 0;
    $page_items = 14;
    $default_page = false;
    $streamSys = GetStreamsFromUser($dev['user_id'], array('movie'), $category_id, $fav, $orderby, $stream_display_name, $movie_properties);
    $counter = count($streamSys['streams']);
    $ch_idx = 0;
    if ($page == 0) {
        $default_page = true;
        $page = ceil($ch_idx / $page_items);
        if ($page == 0) {
            $page = 1;
        }
    }
    $streamSys = array_slice($streamSys['streams'], ($page - 1) * $page_items, $page_items);
    $D0b48d0a5773acd261b061496a380231 = array();
    foreach ($streamSys as $b7c504f559ac034e87cb43d0e37e3a75) {
        if (!is_null($fav) && $fav == 1) {
            if (in_array($b7c504f559ac034e87cb43d0e37e3a75['id'], $dev['fav_channels']['movie'])) {
                $movie_properties = ipTV_lib::movieProperties($b7c504f559ac034e87cb43d0e37e3a75['id']);
                $a9e3b114a3c003a754cbc0f56c02d468 = array('type' => 'movie', 'stream_id' => $b7c504f559ac034e87cb43d0e37e3a75['id'], 'stream_source' => $b7c504f559ac034e87cb43d0e37e3a75['stream_source'], 'target_container' => $b7c504f559ac034e87cb43d0e37e3a75['target_container']);
                $B59151d85f274ca985dc4d70be11dfc2 = date('m');
                $Ebc8eb6db758ae15cc58eaf554c34860 = date('d');
                $C60437cfc485aff3cca4e18e5e9c79cc = date('Y');
                if (($b7c504f559ac034e87cb43d0e37e3a75['added'] > mktime(0, 0, 0, $B59151d85f274ca985dc4d70be11dfc2, $Ebc8eb6db758ae15cc58eaf554c34860, $C60437cfc485aff3cca4e18e5e9c79cc))) {
                    $Af9e1f0f55679a31aacca521a9413b32 = 'today';
                    $bdc1336bc6a87307f9e50c2a74537914 = $A22f04f5efe932bdb34320e92642825a['today'];
                }
                else if (($b7c504f559ac034e87cb43d0e37e3a75['added'] > mktime(0, 0, 0, $B59151d85f274ca985dc4d70be11dfc2, $Ebc8eb6db758ae15cc58eaf554c34860 - 1, $C60437cfc485aff3cca4e18e5e9c79cc))) {
                    $Af9e1f0f55679a31aacca521a9413b32 = 'yesterday';
                    $bdc1336bc6a87307f9e50c2a74537914 = $A22f04f5efe932bdb34320e92642825a['yesterday'];   
                }
                else if ($b7c504f559ac034e87cb43d0e37e3a75['added'] > mktime(0, 0, 0, $B59151d85f274ca985dc4d70be11dfc2, $Ebc8eb6db758ae15cc58eaf554c34860 - 7, $C60437cfc485aff3cca4e18e5e9c79cc)) {
                    $Af9e1f0f55679a31aacca521a9413b32 = 'week_and_more';
                    $bdc1336bc6a87307f9e50c2a74537914 = $A22f04f5efe932bdb34320e92642825a['last_week'];
                } else {
                    $Af9e1f0f55679a31aacca521a9413b32 = 'week_and_more';
                    $bdc1336bc6a87307f9e50c2a74537914 = date('F', $b7c504f559ac034e87cb43d0e37e3a75['added']) . ' ' . date('Y', $b7c504f559ac034e87cb43d0e37e3a75['added']);
                    //B5c761efbdb8c437d9dba6e63007706f:
                }
                $duration = isset($movie_properties['duration_secs']) ? $movie_properties['duration_secs'] : 60;
                $D0b48d0a5773acd261b061496a380231[] = array('id' => $b7c504f559ac034e87cb43d0e37e3a75['id'], 'owner' => '', 'name' => $b7c504f559ac034e87cb43d0e37e3a75['stream_display_name'], 'old_name' => '', 'o_name' => $b7c504f559ac034e87cb43d0e37e3a75['stream_display_name'], 'fname' => '', 'description' => empty($movie_properties['plot']) ? 'N/A' : $movie_properties['plot'], 'pic' => '', 'cost' => 0, 'time' => intval($duration / 60), 'file' => '', 'path' => str_replace(' ', '_', $b7c504f559ac034e87cb43d0e37e3a75['stream_display_name']), 'protocol' => '', 'rtsp_url' => '', 'censored' => $b7c504f559ac034e87cb43d0e37e3a75['is_adult'], 'series' => array(), 'volume_correction' => 0, 'category_id' => $b7c504f559ac034e87cb43d0e37e3a75['category_id'], 'genre_id' => 0, 'genre_id_1' => 0, 'genre_id_2' => 0, 'genre_id_3' => 0, 'hd' => 1, 'genre_id_4' => 0, 'cat_genre_id_1' => $b7c504f559ac034e87cb43d0e37e3a75['category_id'], 'cat_genre_id_2' => 0, 'cat_genre_id_3' => 0, 'cat_genre_id_4' => 0, 'director' => empty($movie_properties['director']) ? 'N/A' : $movie_properties['director'], 'actors' => empty($movie_properties['cast']) ? 'N/A' : $movie_properties['cast'], 'year' => empty($movie_properties['releasedate']) ? 'N/A' : $movie_properties['releasedate'], 'accessed' => 1, 'status' => 1, 'disable_for_hd_devices' => 0, 'added' => date('Y-m-d H:i:s', $b7c504f559ac034e87cb43d0e37e3a75['added']), 'count' => 0, 'count_first_0_5' => 0, 'count_second_0_5' => 0, 'vote_sound_good' => 0, 'vote_sound_bad' => 0, 'vote_video_good' => 0, 'vote_video_bad' => 0, 'rate' => '', 'last_rate_update' => '', 'last_played' => '', 'for_sd_stb' => 0, 'rating_imdb' => empty($movie_properties['rating']) ? 'N/A' : $movie_properties['rating'], 'rating_count_imdb' => '', 'rating_last_update' => '0000-00-00 00:00:00', 'age' => '12+', 'high_quality' => 0, 'rating_kinopoisk' => empty($movie_properties['rating']) ? 'N/A' : $movie_properties['rating'], 'comments' => '', 'low_quality' => 0, 'is_series' => 0, 'year_end' => 0, 'autocomplete_provider' => 'imdb', 'screenshots' => '', 'is_movie' => 1, 'lock' => $b7c504f559ac034e87cb43d0e37e3a75['is_adult'], 'fav' => in_array($b7c504f559ac034e87cb43d0e37e3a75['id'], $dev['fav_channels']['movie']) ? 1 : 0, 'for_rent' => 0, 'screenshot_uri' => empty($movie_properties['movie_image']) ? '' : $movie_properties['movie_image'], 'genres_str' => empty($movie_properties['genre']) ? 'N/A' : $movie_properties['genre'], 'cmd' => base64_encode(json_encode($a9e3b114a3c003a754cbc0f56c02d468, JSON_PARTIAL_OUTPUT_ON_ERROR)), $Af9e1f0f55679a31aacca521a9413b32 => $bdc1336bc6a87307f9e50c2a74537914, 'has_files' => 0);
                //F699bf48add25f20344294f7755ebcb5:
            } else {
                --$counter;
            }
        }
    }
    if ($default_page) {
        $a5059501c38fcd5e6e2e3af8e53670bb = $page;
        $ca959924790f641d3f5e6f3eda4ee518 = $ch_idx - ($page - 1) * $page_items;
    } else {
        $a5059501c38fcd5e6e2e3af8e53670bb = 0;
        $ca959924790f641d3f5e6f3eda4ee518 = 0;
    }
    $output = array('js' => array('total_items' => $counter, 'max_page_items' => $page_items, 'selected_item' => $ca959924790f641d3f5e6f3eda4ee518, 'cur_page' => $a5059501c38fcd5e6e2e3af8e53670bb, 'data' => $D0b48d0a5773acd261b061496a380231));
    return json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR);
}
function EfbbCCe59aAbcEb0b973f0Ba1a94d948($id, $fav = null, $orderby = null, $stream_display_name = null, $movie_properties = array())
{
    global $ipTV_db;
    $ipTV_db->query('SELECT * FROM `series_episodes` t1 INNER JOIN `streams` t2 ON t2.id=t1.stream_id WHERE t1.series_id = \'%d\' ORDER BY t1.season_num DESC, t1.sort ASC', $id);
    $cc69dbb0749cadc4d6c0543ba360c09d = $ipTV_db->get_rows(true, 'season_num', false);
    return $cc69dbb0749cadc4d6c0543ba360c09d;
}
function e9967FbB02A1eBF83f92f22E140aEBf9($movie_id = null, $category_id = null, $fav = null, $orderby = null, $stream_display_name = null, $movie_properties = array())
{
    global $dev, $player, $A22f04f5efe932bdb34320e92642825a, $ipTV_db;
    $page = !empty(ipTV_lib::$request['p']) ? ipTV_lib::$request['p'] : 0;
    $page_items = 14;
    $default_page = false;
    if (empty($movie_id)) {
        $Fc0cf310dd1b2294ab167a0658937ab5 = eA44215481573d77C59D844454c19797($dev['user_id'], $category_id, $fav, $orderby, $stream_display_name, $movie_properties);
    } else {
        $Fc0cf310dd1b2294ab167a0658937ab5 = eFBbCCe59AaBcEb0b973F0Ba1A94D948($movie_id, $fav, $orderby, $stream_display_name, $movie_properties);
        $ipTV_db->query('SELECT * FROM `series` WHERE `id` = \'%d\'', $movie_id);
        $A0766c7ec9b7cbc336d730454514b34f = $ipTV_db->get_row();
    }
    $counter = count($Fc0cf310dd1b2294ab167a0658937ab5);
    $ch_idx = 0;
    if ($page == 0) {
        $default_page = true;
        $page = ceil($ch_idx / $page_items);
        if ($page == 0) {
            $page = 1;
        }
    }
    $Fc0cf310dd1b2294ab167a0658937ab5 = array_slice($Fc0cf310dd1b2294ab167a0658937ab5, ($page - 1) * $page_items, $page_items, true);
    $D0b48d0a5773acd261b061496a380231 = array();
    foreach ($Fc0cf310dd1b2294ab167a0658937ab5 as $key => $b7c504f559ac034e87cb43d0e37e3a75) {
        if (!is_null($fav) && $fav == 1 && empty($movie_id)) {
            if (in_array($b7c504f559ac034e87cb43d0e37e3a75['id'], $dev['fav_channels']['series'])) {
                if (!empty($A0766c7ec9b7cbc336d730454514b34f)) {
                    $d66f371f212188b56889e732be18574e = $A0766c7ec9b7cbc336d730454514b34f;
                    $Bb2aa338b4f24748194863e13511a725 = 0;
                    foreach ($b7c504f559ac034e87cb43d0e37e3a75 as $Eb16297b6431c4849004e993b69db954) {
                        if ($Eb16297b6431c4849004e993b69db954['added'] > $Bb2aa338b4f24748194863e13511a725) {
                            $Bb2aa338b4f24748194863e13511a725 = $Eb16297b6431c4849004e993b69db954['added'];
                        }
                    }
                } else {
                    $d66f371f212188b56889e732be18574e = $b7c504f559ac034e87cb43d0e37e3a75;
                    $Bb2aa338b4f24748194863e13511a725 = $b7c504f559ac034e87cb43d0e37e3a75['last_modified'];
                }
                $a9e3b114a3c003a754cbc0f56c02d468 = array('type' => 'series', 'series_id' => $movie_id, 'season_num' => $key);
                $B59151d85f274ca985dc4d70be11dfc2 = date('m');
                $Ebc8eb6db758ae15cc58eaf554c34860 = date('d');
                $C60437cfc485aff3cca4e18e5e9c79cc = date('Y');
                if (($Bb2aa338b4f24748194863e13511a725 > mktime(0, 0, 0, $B59151d85f274ca985dc4d70be11dfc2, $Ebc8eb6db758ae15cc58eaf554c34860, $C60437cfc485aff3cca4e18e5e9c79cc))) {
                    $Af9e1f0f55679a31aacca521a9413b32 = 'today';
                    $bdc1336bc6a87307f9e50c2a74537914 = $A22f04f5efe932bdb34320e92642825a['today'];
                    //goto fdd5c4249c9c2fdec3d844f7f6dc7937;
                }
                else if (($Bb2aa338b4f24748194863e13511a725 > mktime(0, 0, 0, $B59151d85f274ca985dc4d70be11dfc2, $Ebc8eb6db758ae15cc58eaf554c34860 - 1, $C60437cfc485aff3cca4e18e5e9c79cc))) {
                    //E1bdd6f7fb12352ff2b3aac67186ceb1:
                    $Af9e1f0f55679a31aacca521a9413b32 = 'yesterday';
                    $bdc1336bc6a87307f9e50c2a74537914 = $A22f04f5efe932bdb34320e92642825a['yesterday'];
                    //goto fdd5c4249c9c2fdec3d844f7f6dc7937;
                }
                else if ($Bb2aa338b4f24748194863e13511a725 > mktime(0, 0, 0, $B59151d85f274ca985dc4d70be11dfc2, $Ebc8eb6db758ae15cc58eaf554c34860 - 7, $C60437cfc485aff3cca4e18e5e9c79cc)) {
                    $Af9e1f0f55679a31aacca521a9413b32 = 'week_and_more';
                    $bdc1336bc6a87307f9e50c2a74537914 = $A22f04f5efe932bdb34320e92642825a['last_week'];
                } else {
                    $Af9e1f0f55679a31aacca521a9413b32 = 'week_and_more';
                    $bdc1336bc6a87307f9e50c2a74537914 = date('F', $Bb2aa338b4f24748194863e13511a725) . ' ' . date('Y', $Bb2aa338b4f24748194863e13511a725);
                    //F4f5605f180764c4308c8d97466fda2d:
                }
                if (!empty($A0766c7ec9b7cbc336d730454514b34f)) {
                    if ($key == 0) {
                        $E4416ae8f96620daee43ac43f9515200 = $A22f04f5efe932bdb34320e92642825a['specials'];
                    } else {
                        $E4416ae8f96620daee43ac43f9515200 = $A22f04f5efe932bdb34320e92642825a['season'] . ' ' . $key;
                    }
                } else {
                    $E4416ae8f96620daee43ac43f9515200 = $b7c504f559ac034e87cb43d0e37e3a75['title'];
                }
                $D0b48d0a5773acd261b061496a380231[] = array('id' => empty($movie_id) ? $d66f371f212188b56889e732be18574e['id'] : $d66f371f212188b56889e732be18574e['id'] . ':' . $key, 'owner' => '', 'name' => $E4416ae8f96620daee43ac43f9515200, 'old_name' => '', 'o_name' => $E4416ae8f96620daee43ac43f9515200, 'fname' => '', 'description' => empty($d66f371f212188b56889e732be18574e['plot']) ? 'N/A' : $d66f371f212188b56889e732be18574e['plot'], 'pic' => '', 'cost' => 0, 'time' => 'N/a', 'file' => '', 'path' => str_replace(' ', '_', $d66f371f212188b56889e732be18574e['title']), 'protocol' => '', 'rtsp_url' => '', 'censored' => 0, 'series' => !empty($A0766c7ec9b7cbc336d730454514b34f) ? range(1, count($b7c504f559ac034e87cb43d0e37e3a75)) : array(), 'volume_correction' => 0, 'category_id' => $d66f371f212188b56889e732be18574e['category_id'], 'genre_id' => 0, 'genre_id_1' => 0, 'genre_id_2' => 0, 'genre_id_3' => 0, 'hd' => 1, 'genre_id_4' => 0, 'cat_genre_id_1' => $d66f371f212188b56889e732be18574e['category_id'], 'cat_genre_id_2' => 0, 'cat_genre_id_3' => 0, 'cat_genre_id_4' => 0, 'director' => empty($d66f371f212188b56889e732be18574e['director']) ? 'N/A' : $d66f371f212188b56889e732be18574e['director'], 'actors' => empty($d66f371f212188b56889e732be18574e['cast']) ? 'N/A' : $d66f371f212188b56889e732be18574e['cast'], 'year' => empty($d66f371f212188b56889e732be18574e['releaseDate']) ? 'N/A' : $d66f371f212188b56889e732be18574e['releaseDate'], 'accessed' => 1, 'status' => 1, 'disable_for_hd_devices' => 0, 'added' => date('Y-m-d H:i:s', $Bb2aa338b4f24748194863e13511a725), 'count' => 0, 'count_first_0_5' => 0, 'count_second_0_5' => 0, 'vote_sound_good' => 0, 'vote_sound_bad' => 0, 'vote_video_good' => 0, 'vote_video_bad' => 0, 'rate' => '', 'last_rate_update' => '', 'last_played' => '', 'for_sd_stb' => 0, 'rating_imdb' => empty($d66f371f212188b56889e732be18574e['rating']) ? 'N/A' : $d66f371f212188b56889e732be18574e['rating'], 'rating_count_imdb' => '', 'rating_last_update' => '0000-00-00 00:00:00', 'age' => '12+', 'high_quality' => 0, 'rating_kinopoisk' => empty($d66f371f212188b56889e732be18574e['rating']) ? 'N/A' : $d66f371f212188b56889e732be18574e['rating'], 'comments' => '', 'low_quality' => 0, 'is_series' => 1, 'year_end' => 0, 'autocomplete_provider' => 'imdb', 'screenshots' => '', 'is_movie' => 1, 'lock' => 0, 'fav' => empty($movie_id) && in_array($d66f371f212188b56889e732be18574e['id'], $dev['fav_channels']['series']) ? 1 : 0, 'for_rent' => 0, 'screenshot_uri' => empty($d66f371f212188b56889e732be18574e['cover']) ? '' : $d66f371f212188b56889e732be18574e['cover'], 'genres_str' => empty($d66f371f212188b56889e732be18574e['genre']) ? 'N/A' : $d66f371f212188b56889e732be18574e['genre'], 'cmd' => !empty($A0766c7ec9b7cbc336d730454514b34f) ? base64_encode(json_encode($a9e3b114a3c003a754cbc0f56c02d468, JSON_PARTIAL_OUTPUT_ON_ERROR)) : '', $Af9e1f0f55679a31aacca521a9413b32 => $bdc1336bc6a87307f9e50c2a74537914, 'has_files' => empty($movie_id) ? 1 : 0);
                //Fd4514e10ddd8615f6307a54c0931100:
            } else {
                --$counter;
            }
        }
    }
    if ($default_page) {
        $a5059501c38fcd5e6e2e3af8e53670bb = $page;
        $ca959924790f641d3f5e6f3eda4ee518 = $ch_idx - ($page - 1) * $page_items;
    } else {
        $a5059501c38fcd5e6e2e3af8e53670bb = 0;
        $ca959924790f641d3f5e6f3eda4ee518 = 0;
    }
    $output = array('js' => array('total_items' => $counter, 'max_page_items' => $page_items, 'selected_item' => $ca959924790f641d3f5e6f3eda4ee518, 'cur_page' => $a5059501c38fcd5e6e2e3af8e53670bb, 'data' => $D0b48d0a5773acd261b061496a380231));
    return json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR);
}
function EA44215481573D77c59D844454c19797($user_id, $category_id = null, $fav = null, $orderby = null, $stream_display_name = null, $movie_properties = array())
{
    global $dev, $ipTV_db;
    $user_info = ipTV_streaming::GetUserInfo($user_id, null, null, true);
    $series = ipTV_lib::seriesData();
    $Cc88d22d55f69d2409f5c72665474b50 = array();
    foreach ($series as $id => $serie) {
        if (!in_array($id, $user_info['series_ids'])) {
            continue;
        }
        if (!empty($category_id) && $serie['category_id'] != $category_id) {
            continue;
        }
        if (!empty($stream_display_name) && !stristr($serie['title'], $stream_display_name)) {
            continue;
        }
        if (!empty($movie_properties['abc']) && $movie_properties['abc'] != '*' && strtoupper(substr($serie['title'], 0, 1)) != $movie_properties['abc']) {
            continue;
        }
        if (!empty($movie_properties['genre']) && $movie_properties['genre'] != '*' && $serie['category_id'] != $movie_properties['genre']) {
            continue;
        }
        if (!empty($movie_properties['years']) && $movie_properties['years'] != '*' && $serie['releaseDate'] != $movie_properties['years']) {
            continue;
        }
        if (!empty($fav) && !in_array($id, $dev['fav_channels']['series'])) {
            continue;
        }
        $Cc88d22d55f69d2409f5c72665474b50[$id] = $serie;
    }
    switch ($orderby) {
        case 'name':
            uasort($Cc88d22d55f69d2409f5c72665474b50, 'sortArrayStreamName');
            break;
        case 'top':
            uasort($Cc88d22d55f69d2409f5c72665474b50, 'sortArrayStreamRating');
            break;
        case 'rating':
            uasort($Cc88d22d55f69d2409f5c72665474b50, 'sortArrayStreamRating');
            break;
        case 'added':
            uasort($Cc88d22d55f69d2409f5c72665474b50, 'sortArrayStreamAdded');
            break;
        case 'number':
            uasort($Cc88d22d55f69d2409f5c72665474b50, 'sortArrayStreamNumber');
            break;
    }
    return $Cc88d22d55f69d2409f5c72665474b50;
}
function GetStreamsFromUser($user_id, $types = array(), $category_id = null, $fav = null, $orderby = null, $stream_display_name = null, $movie_properties = array())
{
    global $dev;
    $user_info = ipTV_streaming::GetUserInfo($user_id, null, null, true, true, false, $types, true);
    $streamSys = array();
    $streamSys['streams'] = array();
    if (!empty($user_info)) {
        $key = 1;
        foreach ($user_info['channels'] as $stream) {
            $stream['number'] = ipTV_lib::$settings['channel_number_type'] == 'bouquet' ? $key++ : (string) $stream['number'];
            if (!empty($category_id) && $stream['category_id'] != $category_id) {
                continue;
            }
            if (empty($category_id) && $stream['is_adult'] == 1) {
                continue;
            }
            if (!empty($stream_display_name) && !stristr($stream['stream_display_name'], $stream_display_name)) {
                continue;
            }
            if (!empty($movie_properties['abc']) && $movie_properties['abc'] != '*' && strtoupper(substr($stream['stream_display_name'], 0, 1)) != $movie_properties['abc']) {
                continue;
            }
            if (!empty($movie_properties['genre']) && $movie_properties['genre'] != '*' && $stream['category_id'] != $movie_properties['genre']) {
                continue;
            }
            if (!empty($fav)) {
                $A2b796e1bb70296d4bed8ce34ce5691b = false;
                foreach ($types as $type) {
                    if (!empty($dev['fav_channels'][$type]) && in_array($stream['id'], $dev['fav_channels'][$type])) {
                        $A2b796e1bb70296d4bed8ce34ce5691b = true;
                        break;
                    }
                }
                if (!$A2b796e1bb70296d4bed8ce34ce5691b) {
                    continue;
                }
            }
            $streamSys['streams'][$stream['id']] = $stream;
        }
        switch ($orderby) {
            case 'name':
                uasort($streamSys['streams'], 'sortArrayStreamName');
                break;
            case 'top':
                uasort($streamSys['streams'], 'sortArrayStreamRating');
                break;
            case 'rating':
                uasort($streamSys['streams'], 'sortArrayStreamRating');
                break;
            case 'added':
                uasort($streamSys['streams'], 'sortArrayStreamAdded');
                break;
            case 'number':
                uasort($streamSys['streams'], 'sortArrayStreamNumber');
                break;
        }
    }
    return $streamSys;
}
function sortArrayStreamRating($a, $b)
{
    if (!isset($a['rating'])) {
        if (!isset($a['movie_propeties']) || !isset($b['movie_propeties'])) {
            return 0;
        }
        if (!is_array($a['movie_propeties'])) {
            $a = json_decode($a['movie_propeties'], true);
        } else {
            $a = $a['movie_propeties'];
        }
        if (!is_array($b['movie_propeties'])) {
            $b = json_decode($b['movie_propeties'], true);
        } else {
            $b = $b['movie_propeties'];
        }
    }
    if ($a['rating'] == $b['rating']) {
        return 0;
    }
    return $a['rating'] > $b['rating'] ? -1 : 1;
}
function sortArrayStreamAdded($a, $b)
{
    $type = isset($a['added']) ? 'added' : 'last_modified';
    if (!is_numeric($a[$type])) {
        $a[$type] = strtotime($a['added']);
    }
    if (!is_numeric($b[$type])) {
        $b[$type] = strtotime($b[$type]);
    }
    if ($a[$type] == $b[$type]) {
        return 0;
    }
    return $a[$type] > $b[$type] ? -1 : 1;
}
function sortArrayStreamNumber($a, $b)
{
    if ($a['number'] == $b['number']) {
        return 0;
    }
    return $a['number'] < $b['number'] ? -1 : 1;
}
function sortArrayStreamName($a, $b)
{
    $type = isset($a['stream_display_name']) ? 'stream_display_name' : 'title';
    return strcmp($a[$type], $b[$type]);
}
function C22100704F3F8811f51506Fb1ab116da($category_id = null, $fav = null, $orderby = null)
{
    global $dev, $player, $ipTV_db;
    $page = isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 0;
    $page_items = 14;
    $default_page = false;
    $streamSys = GetStreamsFromUser($dev['user_id'], array('radio_streams'), $category_id, $fav, $orderby);
    $counter = count($streamSys['streams']);
    $ch_idx = 0;
    if ($page == 0) {
        $default_page = true;
        $page = ceil($ch_idx / $page_items);
        if ($page == 0) {
            $page = 1;
        }
    }
    $streamSys = array_slice($streamSys['streams'], ($page - 1) * $page_items, $page_items);
    $D0b48d0a5773acd261b061496a380231 = array();
    $index = 1;
    foreach ($streamSys as $e2ed3b44381d63b5ea576aae3e88df98 => $stream) {
        if (ipTV_lib::$settings['mag_security'] == 0) {
            if (!empty($stream['stream_source'])) {
                $url = $player . json_decode($stream['stream_source'], true)[0];
            } else {
                if (!file_exists(TMP_DIR . 'new_rewrite') || ipTV_lib::$settings['mag_container'] == 'm3u8') {
                    $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "live/{$dev['username']}/{$dev['password']}/{$stream['id']}." . ipTV_lib::$settings['mag_container'];
                } else {
                    $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "{$dev['username']}/{$dev['password']}/{$stream['id']}";
                }
            }
            $A924ba2fdd81aee087d60b59ecc7cb97 = 0;
        } else {
            if (!empty($stream['stream_source'])) {
                $url = $player . "http://localhost/ch/{$stream['id']}_" . json_decode($stream['stream_source'], true)[0];
            } else {
                $url = $player . "http://localhost/ch/{$stream['id']}_";
            }
            $A924ba2fdd81aee087d60b59ecc7cb97 = 1;
        }
        $D0b48d0a5773acd261b061496a380231[] = array('id' => $stream['id'], 'name' => $stream['stream_display_name'], 'number' => $index++, 'cmd' => $url, 'count' => 0, 'open' => 1, 'use_http_tmp_link' => "{$A924ba2fdd81aee087d60b59ecc7cb97}", 'status' => 1, 'volume_correction' => 0, 'fav' => in_array($stream['id'], $dev['fav_channels']['radio_streams']) ? 1 : 0);
    }
    if ($default_page) {
        $a5059501c38fcd5e6e2e3af8e53670bb = $page;
        $ca959924790f641d3f5e6f3eda4ee518 = $ch_idx - ($page - 1) * $page_items;
    } else {
        $a5059501c38fcd5e6e2e3af8e53670bb = 0;
        $ca959924790f641d3f5e6f3eda4ee518 = 0;
    }
    $output = array('js' => array('total_items' => $counter, 'max_page_items' => $page_items, 'selected_item' => $ca959924790f641d3f5e6f3eda4ee518, 'cur_page' => $a5059501c38fcd5e6e2e3af8e53670bb, 'data' => $D0b48d0a5773acd261b061496a380231));
    return json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR);
}
function eca2a16CFBd94b2b895BcbC43eBd6e3d($category_id = null, $F720b54e951bfeddce930cfae655a2ca = false, $fav = null, $orderby = null)
{
    global $dev, $player, $ipTV_db;
    $page = isset($_REQUEST['p']) ? intval($_REQUEST['p']) : 0;
    $page_items = 14;
    $default_page = false;
    $ch_idx = 0;
    if ($page == 0) {
        $default_page = true;
        $page = ceil($ch_idx / $page_items);
        if ($page == 0) {
            $page = 1;
        }
    }
    $streamSys = GetStreamsFromUser($dev['user_id'], array('live', 'created_live'), $category_id, $fav, $orderby);
    if ($default_page && array_key_exists($dev['last_itv_id'], $streamSys['streams'])) {
        $ch_idx = array_search($dev['last_itv_id'], array_keys($streamSys['streams'])) + 1;
        $page = $ch_idx / $page_items;
        if (is_float($page)) {
            $page = ceil($page);
        }
        if ($page == 0) {
            $page = 1;
        }
    }
    $counter = count($streamSys['streams']);
    if (!$F720b54e951bfeddce930cfae655a2ca) {
        $streamSys = array_slice($streamSys['streams'], ($page - 1) * $page_items, $page_items);
    } else {
        $streamSys = $streamSys['streams'];
    }
    $Adf01d0d1116712a679b41ad78bb22bd = '';
    $D0b48d0a5773acd261b061496a380231 = array();
    $index = 1;
    foreach ($streamSys as $e2ed3b44381d63b5ea576aae3e88df98 => $stream) {
        if (ipTV_lib::$settings['mag_security'] == 0) {
            if (!empty($stream['stream_source'])) {
                $url = $player . json_decode($stream['stream_source'], true)[0];
            } else {
                if (!file_exists(TMP_DIR . 'new_rewrite') || ipTV_lib::$settings['mag_container'] == 'm3u8') {
                    $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "live/{$dev['username']}/{$dev['password']}/{$stream['id']}." . ipTV_lib::$settings['mag_container'];
                } else {
                    $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "{$dev['username']}/{$dev['password']}/{$stream['id']}";
                }
            }
            $A924ba2fdd81aee087d60b59ecc7cb97 = 0;
        } else {
            if (!empty($stream['stream_source'])) {
                $url = $player . "http://localhost/ch/{$stream['id']}_" . json_decode($stream['stream_source'], true)[0];
            } else {
                $url = $player . "http://localhost/ch/{$stream['id']}_";
            }
            $A924ba2fdd81aee087d60b59ecc7cb97 = 1;
        }
        $D0b48d0a5773acd261b061496a380231[] = array('id' => $stream['id'], 'name' => $stream['stream_display_name'], 'number' => (string) $stream['number'], 'censored' => $stream['is_adult'] == 1 ? '1' : '', 'cmd' => $url, 'cost' => '0', 'count' => '0', 'status' => 1, 'hd' => 0, 'tv_genre_id' => $stream['category_id'], 'base_ch' => '1', 'hd' => '0', 'xmltv_id' => !empty($stream['channel_id']) ? $stream['channel_id'] : '', 'service_id' => '', 'bonus_ch' => '0', 'volume_correction' => '0', 'mc_cmd' => '', 'enable_tv_archive' => $stream['tv_archive_duration'] > 0 ? 1 : 0, 'wowza_tmp_link' => '0', 'wowza_dvr' => '0', 'use_http_tmp_link' => "{$A924ba2fdd81aee087d60b59ecc7cb97}", 'monitoring_status' => '1', 'enable_monitoring' => '0', 'enable_wowza_load_balancing' => '0', 'cmd_1' => '', 'cmd_2' => '', 'cmd_3' => '', 'logo' => $stream['stream_icon'], 'correct_time' => '0', 'nimble_dvr' => '0', 'allow_pvr' => (int) $stream['allow_record'], 'allow_local_pvr' => (int) $stream['allow_record'], 'allow_remote_pvr' => 0, 'modified' => '', 'allow_local_timeshift' => '1', 'nginx_secure_link' => "{$A924ba2fdd81aee087d60b59ecc7cb97}", 'tv_archive_duration' => $stream['tv_archive_duration'] > 0 ? $stream['tv_archive_duration'] * 24 : 0, 'locked' => 0, 'lock' => $stream['is_adult'], 'fav' => in_array($stream['id'], $dev['fav_channels']['live']) ? 1 : 0, 'archive' => $stream['tv_archive_duration'] > 0 ? 1 : 0, 'genres_str' => '', 'cur_playing' => '[No channel info]', 'epg' => array(), 'open' => 1, 'cmds' => array(array('id' => (string) $stream['id'], 'ch_id' => (string) $stream['id'], 'priority' => '0', 'url' => $player . "http : //localhost/ch/{$stream['id']}_{$Faefd94a7363d43fe709e5aaa80f5fc7}", 'status' => '1', 'use_http_tmp_link' => "{$A924ba2fdd81aee087d60b59ecc7cb97}", 'wowza_tmp_link' => '0', 'user_agent_filter' => '', 'use_load_balancing' => '0', 'changed' => '', 'enable_monitoring' => '0', 'enable_balancer_monitoring' => '0', 'nginx_secure_link' => "{$A924ba2fdd81aee087d60b59ecc7cb97}", 'flussonic_tmp_link' => '0')), 'use_load_balancing' => 0, 'pvr' => (int) $stream['allow_record']);
    }
    if ($default_page) {
        $a5059501c38fcd5e6e2e3af8e53670bb = $page;
        $ca959924790f641d3f5e6f3eda4ee518 = $ch_idx - ($page - 1) * $page_items;
    } else {
        $a5059501c38fcd5e6e2e3af8e53670bb = 0;
        $ca959924790f641d3f5e6f3eda4ee518 = 0;
    }
    $output = array('js' => array('total_items' => $counter, 'max_page_items' => $page_items, 'selected_item' => $ca959924790f641d3f5e6f3eda4ee518, 'cur_page' => $F720b54e951bfeddce930cfae655a2ca ? 0 : $a5059501c38fcd5e6e2e3af8e53670bb, 'data' => $D0b48d0a5773acd261b061496a380231));
    return json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR);
}
function getDataTable()
{
    global $dev, $ipTV_db, $timezone;
    $page = intval($_REQUEST['p']);
    $ch_id = intval($_REQUEST['ch_id']);
    $from = $_REQUEST['from'];
    $to = $_REQUEST['to'];
    $default_page = false;
    $page_items = 10;
    $streamSys = GetStreamsFromUser($dev['user_id'], array('live', 'created_live'));
    $all_user_ids = array_keys($streamSys['streams']);
    $all_user_channels_info = array_values($streamSys['streams']);
    $dvb_channels = array();
    $dvb_ch_idx = null;
    $ipTV_db->query('SELECT * FROM `streams` WHERE `id` = \'%d\'', $ch_id);
    $channel = $ipTV_db->get_row();
    if (empty($channel)) {
        foreach ($dvb_channels as $dvb_channel) {
            if ($dvb_channel['id'] == $ch_id) {
                $channel = $dvb_channel;
                break;
            }
        }
        $index = 0;

        while ($index < count($dvb_channels)) {
            if ($dvb_channels[$index]['id'] == $ch_id) {
                $channel = $dvb_channels[$index];
                $dvb_ch_idx = $index;
            }
            $index++;
        }

        if ($dvb_ch_idx != null) {
            $dvb_ch_idx++;
        }
    }
    $total_channels = count($all_user_ids);
    $total_iptv_channels = $total_channels;
    $total_channels += count($dvb_channels);
    $ch_idx = array_search($channel['id'], $all_user_ids);
    $ch_idx += $dvb_ch_idx;
    if ($ch_idx === false) {
        $ch_idx = 0;
    }
    if ($page == 0) {
        $default_page = true;
        $page = ceil($ch_idx / $page_items);
        if ($page == 0) {
            $page == 1;
        }
    }
    $ch_idx = $ch_idx - ($page - 1) * $page_items;
    $user_channels = array_slice($all_user_channels_info, ($page - 1) * $page_items, $page_items);
    $total_iptv_pages = ceil($total_iptv_channels / $page_items);
    if (count($user_channels) < $page_items) {
        if ($page == $total_iptv_pages) {
            $dvb_part_length = $page_items - $total_iptv_channels % $page_items;
        } else {
            $dvb_part_length = $page_items;
        }
        if ($page > $total_iptv_pages) {
            $dvb_part_offset = ($page - $total_iptv_pages - 1) * $page_items + ($page_items - $total_iptv_channels % $page_items);
        } else {
            $dvb_part_offset = 0;
        }
        if (isset($_REQUEST['p'])) {
            $dvb_channels = array_splice($dvb_channels, $dvb_part_offset, $dvb_part_length);
        }
        $user_channels = array_merge($user_channels, $dvb_channels);
    }
    $display_channels_ids = array();
    $index = 0;

    while ($index < count($user_channels)) {
        $display_channels_ids[] = $user_channels[$index]['id'];
        $index++;
    }

    if (!empty($display_channels_ids)) {
        $ipTV_db->query('
                SELECT t1.id as stream_id,t2.*
                FROM `streams` t1
                LEFT JOIN `epg_data` t2 ON t1.channel_id = t2.channel_id AND t2.`start` >= \'%s\' AND t2.`end` <= \'%s\'
                WHERE t1.id IN(' . implode(',', $display_channels_ids) . ')', $from, $to);
        $raw_epg = $ipTV_db->get_rows(true, 'stream_id');
        $result = array();
        $index = 0;
        $output = array();
        $key = 0;
        foreach ($display_channels_ids as $stream_id) {
            $channel = $streamSys['streams'][$stream_id];
            $result[$key] = array('ch_id' => $stream_id, 'name' => $channel['stream_display_name'], 'number' => (string) $index++, 'ch_type' => isset($channel['type']) && $channel['type'] == 'dvb' ? 'dvb' : 'iptv', 'dvb_id' => isset($channel['type']) && $channel['type'] == 'dvb' ? $channel['dvb_id'] : null, 'epg_container' => 1);
            $epg_dat = array();
            $epg_key = 0;
            $fc504d0185065e8ba8f4f217b7aba6e8 = ipTV_lib::GetDateUTCTimestamp($timezone);
            foreach (epg_search($raw_epg, 'stream_id', $stream_id) as $epg) {
                if (!empty($epg['epg_id'])) {
                    $epgStart = new DateTime($epg['start']);
                    $epgStart->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                    $epgEnd = new DateTime($epg['end']);
                    $epgEnd->modify("{$fc504d0185065e8ba8f4f217b7aba6e8} seconds");
                    $epg_dat[$epg_key]['id'] = $epg['id'];
                    $epg_dat[$epg_key]['ch_id'] = $epg['stream_id'];
                    $epg_dat[$epg_key]['time'] = $epgStart->format('Y-m-d H:i:s');
                    $epg_dat[$epg_key]['time_to'] = $epgEnd->format('Y-m-d H:i:s');
                    $epg_dat[$epg_key]['duration'] = $epgEnd->getTimestamp() - $epgStart->getTimestamp();
                    $epg_dat[$epg_key]['name'] = base64_decode($epg['title']);
                    $epg_dat[$epg_key]['descr'] = base64_decode($epg['description']);
                    $epg_dat[$epg_key]['real_id'] = $epg['stream_id'] . '_' . $epgStart->getTimestamp();
                    $epg_dat[$epg_key]['category'] = '';
                    $epg_dat[$epg_key]['director'] = '';
                    $epg_dat[$epg_key]['actor'] = '';
                    $epg_dat[$epg_key]['start_timestamp'] = $epgStart->getTimestamp();
                    $epg_dat[$epg_key]['stop_timestamp'] = $epgEnd->getTimestamp();
                    $epg_dat[$epg_key]['t_time'] = $epgStart->format('h:i A');
                    $epg_dat[$epg_key]['t_time_to'] = $epgEnd->format('h:i A');
                    $epg_dat[$epg_key]['display_duration'] = $epgEnd->getTimestamp() - $epgStart->getTimestamp();
                    $epg_dat[$epg_key]['larr'] = 0;
                    $epg_dat[$epg_key]['rarr'] = 0;
                    $epg_dat[$epg_key]['mark_rec'] = 0;
                    $epg_dat[$epg_key]['mark_memo'] = 0;
                    $epg_dat[$epg_key]['mark_archive'] = 0;
                    $epg_dat[$epg_key++]['on_date'] = $epgStart->format('l d.m.Y');
                }
            }
            $result[$key++]['epg'] = $epg_dat;
        }
    }
    $time_marks = array();
    $from_ts = strtotime($from);
    $to_ts = strtotime($to);
    $time_marks[] = date('H:i', $from_ts);
    $time_marks[] = date('H:i', $from_ts + 1800);
    $time_marks[] = date('H:i', $from_ts + 2 * 1800);
    $time_marks[] = date('H:i', $from_ts + 3 * 1800);
    if (!in_array($ch_id, $display_channels_ids)) {
        $ch_idx = 0;
        $page = 0;
    } else {
        $ch_idx = array_search($ch_id, $display_channels_ids) + 1;
    }
    return array('total_items' => $total_channels, 'max_page_items' => $page_items, 'cur_page' => $page, 'selected_item' => $ch_idx, 'time_marks' => $time_marks, 'from_ts' => $from_ts, 'to_ts' => $to_ts, 'data' => $result);
}
function A1c6b49ec4F49777516666E14316d4B7($token)
{
    if (empty($token)) {
        return;
    }
    if (function_exists('getallheaders')) {
        $Ccea43217aa47fe5576ce138dd8ef8c5 = getallheaders();
    } else {
        $Ccea43217aa47fe5576ce138dd8ef8c5 = df07Cb3e40dF776f703dB0f3F3529AC0();
    }
    if (!$Ccea43217aa47fe5576ce138dd8ef8c5) {
        return;
    }
    $A0aa0bbb0dde3dc86fc16d1493bbc86f = !empty($Ccea43217aa47fe5576ce138dd8ef8c5['Authorization']) ? $Ccea43217aa47fe5576ce138dd8ef8c5['Authorization'] : null;
    if ($A0aa0bbb0dde3dc86fc16d1493bbc86f && preg_match('/Bearer\\s+(.*)$/i', $A0aa0bbb0dde3dc86fc16d1493bbc86f, $matches)) {
        if ($token == trim($matches[1])) {
            return true;
        }
    }
    die;
}
function DF07CB3E40Df776f703dB0f3F3529aC0()
{
    $Ccea43217aa47fe5576ce138dd8ef8c5 = array();
    foreach ($_SERVER as $Ef1ee3c519bc282c0258c5a79264005f => $value) {
        if (substr($Ef1ee3c519bc282c0258c5a79264005f, 0, 5) == 'HTTP_') {
            $Ccea43217aa47fe5576ce138dd8ef8c5[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($Ef1ee3c519bc282c0258c5a79264005f, 5)))))] = $value;
        }
    }
    return $Ccea43217aa47fe5576ce138dd8ef8c5;
}
?>
