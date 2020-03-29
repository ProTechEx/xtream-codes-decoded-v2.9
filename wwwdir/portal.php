<?php

require './init.php';
include './langs/mag_langs.php';
header('cache-control: no-store, no-cache, must-revalidate');
header('cache-control: post-check=0, pre-check=0', false);
header('Pragma: no-cache'); 
@header('Content-type: text/javascript');
$user_ip = ipTV_streaming::getUserIP();
$req_type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : null;
$req_action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : null;
$sn = !empty($_REQUEST['sn']) ? $_REQUEST['sn'] : null;
$stb_type = !empty($_REQUEST['stb_type']) ? $_REQUEST['stb_type'] : null;
$mac = !empty($_REQUEST['mac']) ? $_REQUEST['mac'] : $_COOKIE['mac'];
$ver = !empty($_REQUEST['ver']) ? $_REQUEST['ver'] : null;
$user_agent = !empty($_SERVER['HTTP_X_USER_AGENT']) ? $_SERVER['HTTP_X_USER_AGENT'] : null;
$image_version = !empty($_REQUEST['image_version']) ? $_REQUEST['image_version'] : null;
$device_id = !empty($_REQUEST['device_id']) ? $_REQUEST['device_id'] : null;
$device_id2 = !empty($_REQUEST['device_id2']) ? $_REQUEST['device_id2'] : null;
$hw_version = !empty($_REQUEST['hw_version']) ? $_REQUEST['hw_version'] : null;
$gmode = !empty($_REQUEST['gmode']) ? intval($_REQUEST['gmode']) : null;
$continue = false;
$enable_debug_stalker = ipTV_lib::$settings['enable_debug_stalker'] == 1 ? true : false;
$dev = array();
if ($dev = portal_auth($sn, $mac, $ver, $stb_type, $image_version, $device_id, $device_id2, $hw_version, $user_ip, $enable_debug_stalker, $req_type, $req_action)) {
    $continue = true;
    ini_set('memory_limit', -1);
}
if ($req_type == 'stb' && $req_action == 'handshake') {
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
$profile = array('id' => $dev['mag_id'], 'name' => $dev['mag_id'], 'sname' => '', 'pass' => '', 'parent_password' => '0000', 'bright' => '200', 'contrast' => '127', 'saturation' => '127', 'video_out' => '', 'volume' => '70', 'playback_buffer_bytes' => '0', 'playback_buffer_size' => '0', 'audio_out' => '1', 'mac' => $mac, 'ip' => $user_ip, 'ls' => '', 'version' => '', 'lang' => '', 'locale' => $dev['locale'], 'city_id' => '0', 'hd' => '1', 'main_notify' => '1', 'fav_itv_on' => '0', 'now_playing_start' => '2018-02-18 17:33:43', 'now_playing_type' => '1', 'now_playing_content' => 'Test channel', 'additional_services_on' => '1', 'time_last_play_tv' => '0000-00-00 00:00:00', 'time_last_play_video' => '0000-00-00 00:00:00', 'operator_id' => '0', 'storage_name' => '', 'hd_content' => '0', 'image_version' => 'undefined', 'last_change_status' => '0000-00-00 00:00:00', 'last_start' => '2018-02-18 17:33:38', 'last_active' => '2018-02-18 17:33:43', 'keep_alive' => '2018-02-18 17:33:43', 'screensaver_delay' => '10', 'phone' => '', 'fname' => '', 'login' => '', 'password' => '', 'stb_type' => '', 'num_banks' => '0', 'tariff_plan_id' => '0', 'comment' => null, 'now_playing_link_id' => '0', 'now_playing_streamer_id' => '0', 'just_started' => '1', 'last_watchdog' => '2018-02-18 17:33:39', 'created' => '2018-02-18 14:40:12', 'plasma_saving' => '0', 'ts_enabled' => '0', 'ts_enable_icon' => '1', 'ts_path' => '', 'ts_max_length' => '3600', 'ts_buffer_use' => 'cyclic', 'ts_action_on_exit' => 'no_save', 'ts_delay' => 'on_pause', 'video_clock' => 'Off', 'verified' => '0', 'hdmi_event_reaction' => 1, 'pri_audio_lang' => '', 'sec_audio_lang' => '', 'pri_subtitle_lang' => '', 'sec_subtitle_lang' => '', 'subtitle_color' => '16777215', 'subtitle_size' => '20', 'show_after_loading' => '', 'play_in_preview_by_ok' => null, 'hw_version' => 'undefined', 'openweathermap_city_id' => '0', 'theme' => '', 'settings_password' => '0000', 'expire_billing_date' => '0000-00-00 00:00:00', 'reseller_id' => null, 'account_balance' => '', 'client_type' => 'STB', 'hw_version_2' => '62', 'blocked' => '0', 'units' => 'metric', 'tariff_expired_date' => null, 'tariff_id_instead_expired' => null, 'activation_code_auto_issue' => '1', 'last_itv_id' => 0, 'updated' => array('id' => '1', 'uid' => '1', 'anec' => '0', 'vclub' => '0'), 'rtsp_type' => '4', 'rtsp_flags' => '0', 'stb_lang' => 'en', 'display_menu_after_loading' => '', 'record_max_length' => 180, 'web_proxy_host' => '', 'web_proxy_port' => '', 'web_proxy_user' => '', 'web_proxy_pass' => '', 'web_proxy_exclude_list' => '', 'demo_video_url' => '', 'tv_quality_filter' => '', 'is_moderator' => false, 'timeslot_ratio' => 0.33333333333333, 'timeslot' => 40, 'kinopoisk_rating' => '1', 'enable_tariff_plans' => '', 'strict_stb_type_check' => '', 'cas_type' => 0, 'cas_params' => null, 'cas_web_params' => null, 'cas_additional_params' => array(), 'cas_hw_descrambling' => 0, 'cas_ini_file' => '', 'logarithm_volume_control' => '', 'allow_subscription_from_stb' => '1', 'deny_720p_gmode_on_mag200' => '1', 'enable_arrow_keys_setpos' => '1', 'show_purchased_filter' => '', 'timezone_diff' => 0, 'enable_connection_problem_indication' => '1', 'invert_channel_switch_direction' => '', 'play_in_preview_only_by_ok' => false, 'enable_stream_error_logging' => '', 'always_enabled_subtitles' => ipTV_lib::$settings['always_enabled_subtitles'] == 1 ? '1' : '', 'enable_service_button' => '', 'enable_setting_access_by_pass' => '', 'tv_archive_continued' => '', 'plasma_saving_timeout' => '600', 'show_tv_only_hd_filter_option' => '', 'tv_playback_retry_limit' => '0', 'fading_tv_retry_timeout' => '1', 'epg_update_time_range' => 0.6, 'store_auth_data_on_stb' => false, 'account_page_by_password' => '', 'tester' => false, 'enable_stream_losses_logging' => '', 'external_payment_page_url' => '', 'max_local_recordings' => '10', 'tv_channel_default_aspect' => 'fit', 'default_led_level' => '10', 'standby_led_level' => '90', 'show_version_in_main_menu' => '1', 'disable_youtube_for_mag200' => '1', 'auth_access' => false, 'epg_data_block_period_for_stb' => '5', 'standby_on_hdmi_off' => '1', 'force_ch_link_check' => '', 'stb_ntp_server' => 'pool.ntp.org', 'overwrite_stb_ntp_server' => '', 'hide_tv_genres_in_fullscreen' => null, 'advert' => null);
$locales['get_locales']['English'] = 'en_GB.utf8';
$locales['get_locales']['Ελληνικά'] = 'el_GR.utf8';
$_MAG_DATA['get_years'] = array('js' => array(array('id' => '*', 'title' => '*'), array('id' => '1937', 'title' => '1937'), array('id' => '1940', 'title' => '1940'), array('id' => '1941', 'title' => '1941'), array('id' => '1951', 'title' => '1951'), array('id' => '1953', 'title' => '1953'), array('id' => '1955', 'title' => '1955'), array('id' => '1961', 'title' => '1961'), array('id' => '1964', 'title' => '1964'), array('id' => '1970', 'title' => '1970'), array('id' => '1983', 'title' => '1983'), array('id' => '1986', 'title' => '1986'), array('id' => '1990', 'title' => '1990'), array('id' => '1992', 'title' => '1992'), array('id' => '1994', 'title' => '1994'), array('id' => '1994/1998/2004', 'title' => '1994/1998/2004'), array('id' => '1995', 'title' => '1995'), array('id' => '1995/1999/2010', 'title' => '1995/1999/2010'), array('id' => '1996', 'title' => '1996'), array('id' => '1998', 'title' => '1998'), array('id' => '1999', 'title' => '1999'), array('id' => '2000', 'title' => '2000'), array('id' => '2001', 'title' => '2001'), array('id' => '2002', 'title' => '2002'), array('id' => '2003', 'title' => '2003'), array('id' => '2004', 'title' => '2004'), array('id' => '2005', 'title' => '2005'), array('id' => '2006', 'title' => '2006'), array('id' => '2007', 'title' => '2007'), array('id' => '2008', 'title' => '2008'), array('id' => '2009', 'title' => '2009'), array('id' => '2010', 'title' => '2010'), array('id' => '2011', 'title' => '2011'), array('id' => '2012', 'title' => '2012'), array('id' => '2013', 'title' => '2013'), array('id' => '2013', 'title' => '2013'), array('id' => '2014', 'title' => '2014'), array('id' => '2015', 'title' => '2015'), array('id' => '2016', 'title' => '2016'), array('id' => '2017', 'title' => '2017')));
$_MAG_DATA['get_abc'] = array('js' => array(array('id' => '*', 'title' => '*'), array('id' => 'A', 'title' => 'A'), array('id' => 'B', 'title' => 'B'), array('id' => 'C', 'title' => 'C'), array('id' => 'D', 'title' => 'D'), array('id' => 'E', 'title' => 'E'), array('id' => 'F', 'title' => 'F'), array('id' => 'G', 'title' => 'G'), array('id' => 'H', 'title' => 'H'), array('id' => 'I', 'title' => 'I'), array('id' => 'G', 'title' => 'G'), array('id' => 'K', 'title' => 'K'), array('id' => 'L', 'title' => 'L'), array('id' => 'M', 'title' => 'M'), array('id' => 'N', 'title' => 'N'), array('id' => 'O', 'title' => 'O'), array('id' => 'P', 'title' => 'P'), array('id' => 'Q', 'title' => 'Q'), array('id' => 'R', 'title' => 'R'), array('id' => 'S', 'title' => 'S'), array('id' => 'T', 'title' => 'T'), array('id' => 'U', 'title' => 'U'), array('id' => 'V', 'title' => 'V'), array('id' => 'W', 'title' => 'W'), array('id' => 'X', 'title' => 'X'), array('id' => 'W', 'title' => 'W'), array('id' => 'Z', 'title' => 'Z')));
$stalker_theme = empty(ipTV_lib::$settings['stalker_theme']) ? 'default' : ipTV_lib::$settings['stalker_theme'];
$timezone = empty($_COOKIE['timezone']) || $_COOKIE['timezone'] == 'undefined' ? ipTV_lib::$settings['default_timezone'] : $_COOKIE['timezone'];
if ($continue && !$enable_debug_stalker) {
    GetAllHeadersFn($dev['token']);
}
switch ($req_type) {
    case 'stb':
        switch ($req_action) {
            case 'get_ad':
                die(json_encode(array('js' => array())));
                break;
            case 'get_storages':
                die(json_encode(array('js' => array())));
                break;
            case 'get_profile':
                $credits = $continue ? array_merge($profile, $dev['get_profile_vars']) : $profile;
                $credits['status'] = intval(!$continue);
                $credits['update_url'] = empty(ipTV_lib::$settings['update_url']) ? '' : ipTV_lib::$settings['update_url'];
                $credits['test_download_url'] = empty(ipTV_lib::$settings['test_download_url']) ? '' : ipTV_lib::$settings['test_download_url'];
                $credits['default_timezone'] = ipTV_lib::$settings['default_timezone'];
                $credits['default_locale'] = ipTV_lib::$settings['default_locale'];
                $credits['allowed_stb_types'] = ipTV_lib::$settings['allowed_stb_types'];
                $credits['allowed_stb_types_for_local_recording'] = ipTV_lib::$settings['allowed_stb_types'];
                $credits['storages'] = array();
                $credits['tv_channel_default_aspect'] = empty(ipTV_lib::$settings['tv_channel_default_aspect']) ? 'fit' : ipTV_lib::$settings['tv_channel_default_aspect'];
                $credits['playback_limit'] = empty(ipTV_lib::$settings['playback_limit']) ? false : intval(ipTV_lib::$settings['playback_limit']);
                if (empty($credits['playback_limit'])) {
                    $credits['enable_playback_limit'] = false;
                }
                $credits['show_tv_channel_logo'] = empty(ipTV_lib::$settings['show_tv_channel_logo']) ? false : true;
                $credits['show_channel_logo_in_preview'] = empty(ipTV_lib::$settings['show_channel_logo_in_preview']) ? false : true;
                $credits['enable_connection_problem_indication'] = empty(ipTV_lib::$settings['enable_connection_problem_indication']) ? false : true;
                $credits['hls_fast_start'] = '1';
                $credits['check_ssl_certificate'] = 0;
                $credits['enable_buffering_indication'] = 1;
                $credits['watchdog_timeout'] = mt_rand(80, 120);
                if (empty($credits['aspect']) && ipTV_lib::$StreamingServers[SERVER_ID]['server_protocol'] == 'https') {
                    $credits['aspect'] = '16';
                }
                die(json_encode(array('js' => $credits), JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'get_localization':
                die(json_encode(array('js' => $D2b2ff0086dc5578693175fa65e7a22a[$dev['locale']])));
                break;
            case 'log':
                die(json_encode(array('js' => true)));
                break;
            case 'get_modules':
                $modules = array('all_modules' => array('media_browser', 'tv', 'vclub', 'sclub', 'radio', 'apps', 'youtube', 'dvb', 'tv_archive', 'time_shift', 'time_shift_local', 'epg.reminder', 'epg.recorder', 'epg', 'epg.simple', 'audioclub', 'downloads_dialog', 'downloads', 'karaoke', 'weather.current', 'widget.audio', 'widget.radio', 'records', 'remotepvr', 'pvr_local', 'settings.parent', 'settings.localization', 'settings.update', 'settings.playback', 'settings.common', 'settings.network_status', 'settings', 'course.nbu', 'weather.day', 'cityinfo', 'horoscope', 'anecdote', 'game.mastermind', 'account', 'demo', 'infoportal', 'internet', 'service_management', 'logout', 'account_menu'), 'switchable_modules' => array('sclub', 'vlub', 'karaoke', 'cityinfo', 'horoscope', 'anecdote', 'game.mastermind'), 'disabled_modules' => array('weather.current', 'weather.day', 'cityinfo', 'karaoke', 'game.mastermind', 'records', 'downloads', 'remotepvr', 'service_management', 'settings.update', 'settings.common', 'audioclub', 'course.nbu', 'infoportal', 'demo', 'widget.audio', 'widget.radio'), 'restricted_modules' => array(), 'template' => $stalker_theme, 'launcher_url' => '', 'launcher_profile_url' => 'http://193.235.147.182:80//stalker_portal//server/api/launcher_profile.php');
                die(json_encode(array('js' => $modules)));
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
                    $eventRows = $ipTV_db->get_row();
                    $ipTV_db->query('SELECT count(*) FROM `mag_events` WHERE `mag_device_id` = \'%d\' AND `status` = 0 ', $dev['mag_id']);
                    $magEventsTotalMsg = $ipTV_db->get_col();
                    $data = array('data' => array('msgs' => $magEventsTotalMsg, 'id' => $eventRows['id'], 'event' => $eventRows['event'], 'need_confirm' => $eventRows['need_confirm'], 'msg' => $eventRows['msg'], 'reboot_after_ok' => $eventRows['reboot_after_ok'], 'auto_hide_timeout' => $eventRows['auto_hide_timeout'], 'send_time' => date('d-m-Y H:i:s', $eventRows['send_time']), 'additional_services_on' => $eventRows['additional_services_on'], 'updated' => array('anec' => $eventRows['anec'], 'vclub' => $eventRows['vclub'])));
                    $eventType = array('reboot', 'reload_portal', 'play_channel', 'cut_off');
                    if (in_array($eventRows['event'], $eventType)) {
                        $ipTV_db->query('UPDATE `mag_events` SET `status` = 1 WHERE `id` = \'%d\'', $eventRows['id']);
                    }
                }
                die(json_encode(array('js' => $data), JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'confirm_event':
                if (!empty(ipTV_lib::$request['event_active_id'])) {
                    $event_active_id = ipTV_lib::$request['event_active_id'];
                    $ipTV_db->query('UPDATE `mag_events` SET `status` = 1 WHERE `id` = \'%d\'', $event_active_id);
                    if ($ipTV_db->affected_rows() > 0) {
                        die(json_encode(array('js' => array('data' => 'ok'))));
                    }
                }
                break;
        }
}
if (!$continue) {
    CheckFlood();
    die;
}
$player = !empty($dev['mag_player']) ? $dev['mag_player'] . ' ' : 'ffmpeg ';
switch ($req_type) {
    case 'stb':
        switch ($req_action) {
            case 'get_preload_images':
                $mod = is_numeric($gmode) ? 'i_' . $gmode : 'i';
                $images = array("template/{$stalker_theme}/{$mod}/loading.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_6_a.png", "template/{$stalker_theme}/{$mod}/ico_info.png", "template/{$stalker_theme}/{$mod}/mb_pass_bg.png", "template/{$stalker_theme}/{$mod}/mm_ico_info.png", "template/{$stalker_theme}/{$mod}/footer_menu_act.png", "template/{$stalker_theme}/{$mod}/_2_cloudy.png", "template/{$stalker_theme}/{$mod}/footer_sidepanel.png", "template/{$stalker_theme}/{$mod}/footer_search.png", "template/{$stalker_theme}/{$mod}/v_menu_1a.png", "template/{$stalker_theme}/{$mod}/loading_bg.gif", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_11_b.png", "template/{$stalker_theme}/{$mod}/mb_table_act01.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_11_a.png", "template/{$stalker_theme}/{$mod}/tv_table.png", "template/{$stalker_theme}/{$mod}/vol_1.png", "template/{$stalker_theme}/{$mod}/mb_prev_bg.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_8_b.png", "template/{$stalker_theme}/{$mod}/mm_ico_youtube.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_4_a.png", "template/{$stalker_theme}/{$mod}/tv_table_arrows.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_9_a.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_10_a.png", "template/{$stalker_theme}/{$mod}/1x1.gif", "template/{$stalker_theme}/{$mod}/mm_ico_karaoke.png", "template/{$stalker_theme}/{$mod}/mm_ico_video.png", "template/{$stalker_theme}/{$mod}/mb_table05.png", "template/{$stalker_theme}/{$mod}/mb_table_act02.png", "template/{$stalker_theme}/{$mod}/tv_table_separator.png", "template/{$stalker_theme}/{$mod}/mb_icons.png", "template/{$stalker_theme}/{$mod}/footer_btn.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_5_b.png", "template/{$stalker_theme}/{$mod}/mm_ico_audio.png", "template/{$stalker_theme}/{$mod}/_7_hail.png", "template/{$stalker_theme}/{$mod}/mb_table_act05.png", "template/{$stalker_theme}/{$mod}/_9_snow.png", "template/{$stalker_theme}/{$mod}/v_menu_4.png", "template/{$stalker_theme}/{$mod}/_3_pasmurno.png", "template/{$stalker_theme}/{$mod}/low_q.png", "template/{$stalker_theme}/{$mod}/mm_ico_setting.png", "template/{$stalker_theme}/{$mod}/mb_context_borders.png", "template/{$stalker_theme}/{$mod}/input_episode_bg.png", "template/{$stalker_theme}/{$mod}/mb_table_act04.png", "template/{$stalker_theme}/{$mod}/mm_hor_bg3.png", "template/{$stalker_theme}/{$mod}/black85.png", "template/{$stalker_theme}/{$mod}/pause_btn.png", "template/{$stalker_theme}/{$mod}/ico_error26.png", "template/{$stalker_theme}/{$mod}/input_episode.png", "template/{$stalker_theme}/{$mod}/epg_red_mark.png", "template/{$stalker_theme}/{$mod}/footer_sidepanel_act.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_3_b.png", "template/{$stalker_theme}/{$mod}/mb_pass_input.png", "template/{$stalker_theme}/{$mod}/footer_bg2.png", "template/{$stalker_theme}/{$mod}/osd_bg.png", "template/{$stalker_theme}/{$mod}/epg_orange_mark.png", "template/{$stalker_theme}/{$mod}/mm_ico_mb.png", "template/{$stalker_theme}/{$mod}/ears_arrow_l.png", "template/{$stalker_theme}/{$mod}/hr_filminfo.png", "template/{$stalker_theme}/{$mod}/mm_ico_rec.png", "template/{$stalker_theme}/{$mod}/mm_ico_account.png", "template/{$stalker_theme}/{$mod}/mb_icon_rec.png", "template/{$stalker_theme}/{$mod}/mm_hor_left.png", "template/{$stalker_theme}/{$mod}/mb_table04.png", "template/{$stalker_theme}/{$mod}/mb_player.png", "template/{$stalker_theme}/{$mod}/footer_search_act2.png", "template/{$stalker_theme}/{$mod}/input_channel_bg.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_12_a.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_9_b.png", "template/{$stalker_theme}/{$mod}/mm_ico_android.png", "template/{$stalker_theme}/{$mod}/bg.png", "template/{$stalker_theme}/{$mod}/mm_hor_right.png", "template/{$stalker_theme}/{$mod}/mb_quality.png", "template/{$stalker_theme}/{$mod}/mb_table02.png", "template/{$stalker_theme}/{$mod}/bg2.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_1_a.png", "template/{$stalker_theme}/{$mod}/osd_line_pos.png", "template/{$stalker_theme}/{$mod}/input_channel.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_7_a.png", "template/{$stalker_theme}/{$mod}/arr_right.png", "template/{$stalker_theme}/{$mod}/mm_ico_radio.png", "template/{$stalker_theme}/{$mod}/ico_confirm.png", "template/{$stalker_theme}/{$mod}/osd_btn.png", "template/{$stalker_theme}/{$mod}/osd_time.png", "template/{$stalker_theme}/{$mod}/footer_menu.png", "template/{$stalker_theme}/{$mod}/volume_off.png", "template/{$stalker_theme}/{$mod}/btn2.png", "template/{$stalker_theme}/{$mod}/mm_ico_internet.png", "template/{$stalker_theme}/{$mod}/volume_bg.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_1_b.png", "template/{$stalker_theme}/{$mod}/v_menu_2b.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_3_a.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_4_b.png", "template/{$stalker_theme}/{$mod}/_255_NA.png", "template/{$stalker_theme}/{$mod}/_1_sun_cl.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_10_b.png", "template/{$stalker_theme}/{$mod}/25alfa_20.png", "template/{$stalker_theme}/{$mod}/mb_table_act06.png", "template/{$stalker_theme}/{$mod}/input.png", "template/{$stalker_theme}/{$mod}/tv_table_focus.png", "template/{$stalker_theme}/{$mod}/skip.png", "template/{$stalker_theme}/{$mod}/epg_green_mark.png", "template/{$stalker_theme}/{$mod}/mm_vert_cell.png", "template/{$stalker_theme}/{$mod}/_1_moon_cl.png", "template/{$stalker_theme}/{$mod}/modal_bg.png", "template/{$stalker_theme}/{$mod}/_4_short_rain.png", "template/{$stalker_theme}/{$mod}/ears_arrow_r.png", "template/{$stalker_theme}/{$mod}/mm_ico_default.png", "template/{$stalker_theme}/{$mod}/osd_line.png", "template/{$stalker_theme}/{$mod}/mb_table07.png", "template/{$stalker_theme}/{$mod}/mm_ico_usb.png", "template/{$stalker_theme}/{$mod}/mb_context_bg.png", "template/{$stalker_theme}/{$mod}/footer_sidepanel_r.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_2_a.png", "template/{$stalker_theme}/{$mod}/v_menu_1b.png", "template/{$stalker_theme}/{$mod}/mb_table03.png", "template/{$stalker_theme}/{$mod}/mb_table_act03.png", "template/{$stalker_theme}/{$mod}/mb_table01.png", "template/{$stalker_theme}/{$mod}/mm_ico_dm.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_5_a.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_6_b.png", "template/{$stalker_theme}/{$mod}/footer_sidepanel_l.png", "template/{$stalker_theme}/{$mod}/footer_sidepanel_line.png", "template/{$stalker_theme}/{$mod}/mm_ico_tv.png", "template/{$stalker_theme}/{$mod}/mb_table06.png", "template/{$stalker_theme}/{$mod}/mb_scroll_bg.png", "template/{$stalker_theme}/{$mod}/_8_rain_swon.png", "template/{$stalker_theme}/{$mod}/mb_scroll.png", "template/{$stalker_theme}/{$mod}/v_menu_2a.png", "template/{$stalker_theme}/{$mod}/v_menu_5.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_2_b.png", "template/{$stalker_theme}/{$mod}/_10_heavy_snow.png", "template/{$stalker_theme}/{$mod}/aspect_bg.png", "template/{$stalker_theme}/{$mod}/_0_moon.png", "template/{$stalker_theme}/{$mod}/volume_bar.png", "template/{$stalker_theme}/{$mod}/v_menu_3.png", "template/{$stalker_theme}/{$mod}/mm_hor_bg1.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_12_b.png", "template/{$stalker_theme}/{$mod}/mm_ico_ex.png", "template/{$stalker_theme}/{$mod}/footer_bg.png", "template/{$stalker_theme}/{$mod}/footer_sidepanel_arr.png", "template/{$stalker_theme}/{$mod}/mb_icon_scrambled.png", "template/{$stalker_theme}/{$mod}/ico_alert.png", "template/{$stalker_theme}/{$mod}/mm_ico_apps.png", "template/{$stalker_theme}/{$mod}/input_act.png", "template/{$stalker_theme}/{$mod}/ears.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_8_a.png", "template/{$stalker_theme}/{$mod}/mm_hor_bg2.png", "template/{$stalker_theme}/{$mod}/arr_left.png", "template/{$stalker_theme}/{$mod}/horoscope_menu_button_1_7_b.png", "template/{$stalker_theme}/{$mod}/footer_search_act.png", "template/{$stalker_theme}/{$mod}/_0_sun.png", "template/{$stalker_theme}/{$mod}/_6_lightning.png", "template/{$stalker_theme}/{$mod}/osd_rec.png", "template/{$stalker_theme}/{$mod}/tv_prev_bg.png", "template/{$stalker_theme}/{$mod}/_5_rain.png");
                die(json_encode(array('js' => $images)));
                break;
            case 'get_settings_profile':
                $ipTV_db->query('SELECT * FROM `mag_devices` WHERE `mag_id` = \'%d\'', $dev['mag_id']);
                $settings_info = $ipTV_db->get_row();
                $JsData = array('js' => array('modules' => array(array('name' => 'lock'), array('name' => 'lang'), array('name' => 'update'), array('name' => 'net_info', 'sub' => array(array('name' => 'wired'), array('name' => 'pppoe', 'sub' => array(array('name' => 'dhcp'), array('name' => 'dhcp_manual'), array('name' => 'disable'))), array('name' => 'wireless'), array('name' => 'speed'))), array('name' => 'video'), array('name' => 'audio'), array('name' => 'net', 'sub' => array(array('name' => 'ethernet', 'sub' => array(array('name' => 'dhcp'), array('name' => 'dhcp_manual'), array('name' => 'manual'), array('name' => 'no_ip'))), array('name' => 'pppoe', 'sub' => array(array('name' => 'dhcp'), array('name' => 'dhcp_manual'), array('name' => 'disable'))), array('name' => 'wifi', 'sub' => array(array('name' => 'dhcp'), array('name' => 'dhcp_manual'), array('name' => 'manual'))), array('name' => 'speed'))), array('name' => 'advanced'), array('name' => 'dev_info'), array('name' => 'reload'), array('name' => 'internal_portal'), array('name' => 'reboot'))));
                $JsData['js']['parent_password'] = $settings_info['parent_password'];
                $JsData['js']['update_url'] = ipTV_lib::$settings['update_url'];
                $JsData['js']['test_download_url'] = ipTV_lib::$settings['test_download_url'];
                $JsData['js']['playback_buffer_size'] = $settings_info['playback_buffer_size'];
                $JsData['js']['screensaver_delay'] = $settings_info['screensaver_delay'];
                $JsData['js']['plasma_saving'] = $settings_info['plasma_saving'];
                $JsData['js']['spdif_mode'] = $settings_info['spdif_mode'];
                $JsData['js']['ts_enabled'] = $settings_info['ts_enabled'];
                $JsData['js']['ts_enable_icon'] = $settings_info['ts_enable_icon'];
                $JsData['js']['ts_path'] = $settings_info['ts_path'];
                $JsData['js']['ts_max_length'] = $settings_info['ts_max_length'];
                $JsData['js']['ts_buffer_use'] = $settings_info['ts_buffer_use'];
                $JsData['js']['ts_action_on_exit'] = $settings_info['ts_action_on_exit'];
                $JsData['js']['ts_delay'] = $settings_info['ts_delay'];
                $JsData['js']['hdmi_event_reaction'] = $settings_info['hdmi_event_reaction'];
                $JsData['js']['pri_audio_lang'] = $profile['pri_audio_lang'];
                $JsData['js']['show_after_loading'] = $settings_info['show_after_loading'];
                $JsData['js']['sec_audio_lang'] = $profile['sec_audio_lang'];
                if (ipTV_lib::$settings['always_enabled_subtitles'] == 1) {
                    $JsData['js']['pri_subtitle_lang'] = $profile['pri_subtitle_lang'];
                    $JsData['js']['sec_subtitle_lang'] = $profile['sec_subtitle_lang'];
                } else {
                    $JsData['js']['pri_subtitle_lang'] = $JsData['js']['sec_subtitle_lang'] = '';
                }
                die(json_encode($JsData));
                break;
            case 'get_locales':
                $ipTV_db->query('SELECT `locale` FROM `mag_devices` WHERE `mag_id` = \'%d\'', $dev['mag_id']);
                $selected = $ipTV_db->get_row();
                $output = array();
                foreach ($locales['get_locales'] as $country => $code) {
                    $selected = $selected['locale'] == $code ? 1 : 0;
                }
                die(json_encode(array('js' => $output)));
                break;
            case 'get_countries':
                die(json_encode(array('js' => array())));
                break;
            case 'get_timezones':
                $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                $output = array();
                foreach ($timezones as $timezone) {
                    $selected = $timezone == $timezone ? 1 : 0;
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
                $vol = ipTV_lib::$request['vol'];
                if (!empty($vol)) {
                    $ipTV_db->query('UPDATE `mag_devices` SET `volume` = \'%d\' WHERE `mag_id` = \'%d\'', $vol, $dev['mag_id']);
                    if ($ipTV_db->affected_rows() > 0) {
                        die(json_encode(array('data' => true)));
                    }
                }
                break;
            case 'set_aspect':
                $ch_id = ipTV_lib::$request['ch_id'];
                $aspect = ipTV_lib::$request['aspect'];
                $aspect = $dev['aspect'];
                if (empty($aspect)) {
                    $ipTV_db->query('UPDATE `mag_devices` SET `aspect` = \'%s\' WHERE mag_id = \'%d\'', json_encode(array('js' => array($ch_id => $aspect))), $dev['mag_id']);
                } else {
                    $aspect = json_decode($aspect, true);
                    $aspect['js'][$ch_id] = $aspect;
                    $ipTV_db->query('UPDATE `mag_devices` SET `aspect` = \'%s\' WHERE mag_id = \'%d\'', json_encode($aspect), $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'set_stream_error':
                die(json_encode(array('js' => true)));
                break;
            case 'set_screensaver_delay':
                if (!empty($_SERVER['HTTP_COOKIE'])) {
                    $screensaver_delay = intval($_REQUEST['screensaver_delay']);
                    $ipTV_db->query('UPDATE `mag_devices` SET `screensaver_delay` = \'%d\' WHERE `mag_id` = \'%d\'', $screensaver_delay, $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'set_playback_buffer':
                if (!empty($_SERVER['HTTP_COOKIE'])) {
                    $playback_buffer_bytes = intval($_REQUEST['playback_buffer_bytes']);
                    $playback_buffer_size = intval($_REQUEST['playback_buffer_size']);
                    $ipTV_db->query('UPDATE `mag_devices` SET `playback_buffer_bytes` = \'%d\' , `playback_buffer_size` = \'%d\' WHERE `mag_id` = \'%d\'', $playback_buffer_bytes, $playback_buffer_size, $dev['mag_id']);
                }
                die(json_encode(array('js' => true)));
                break;
            case 'set_plasma_saving':
                $plasma_saving = intval($_REQUEST['plasma_saving']);
                $ipTV_db->query('UPDATE `mag_devices` SET `plasma_saving` = \'%d\' WHERE `mag_id` = \'%d\'', $plasma_saving, $dev['mag_id']);
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
                    $req_data = $_REQUEST['data'];
                    $ipTV_db->query('UPDATE `mag_devices` SET `hdmi_event_reaction` = \'%s\' WHERE `mag_id` = \'%d\'', $req_data, $dev['mag_id']);
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
                list($stream_id, $streamIdValue) = explode('_', substr($cmd, strpos($cmd, $value) + strlen($value)));
                if (empty($streamIdValue)) {
                    $play_token = ipTV_lib::GenerateString();
                    $ipTV_db->query('UPDATE `users` SET `play_token` = \'%s\' WHERE `id` = \'%d\'', $play_token . ':' . (time() + 10) . ':' . $stream_id, $dev['user_id']);
                    if (!file_exists(TMP_DIR . 'new_rewrite') || ipTV_lib::$settings['mag_container'] == 'm3u8') {
                        $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "live/{$dev['username']}/{$dev['password']}/{$stream_id}." . ipTV_lib::$settings['mag_container'] . '?play_token=' . $play_token;
                    } else {
                        $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "{$dev['username']}/{$dev['password']}/{$stream_id}?play_token={$play_token}";
                    }
                } else {
                    $url = $player . $streamIdValue;
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
                $fav_ch = empty($_REQUEST['fav_ch']) ? '' : $_REQUEST['fav_ch'];
                $fav_ch = array_filter(array_map('intval', explode(',', $fav_ch)));
                $dev['fav_channels']['live'] = $fav_ch;
                $ipTV_db->query('UPDATE `mag_devices` SET `fav_channels` = \'%s\' WHERE `mag_id` = \'%d\'', json_encode($dev['fav_channels']), $dev['mag_id']);
                die(json_encode(array('js' => true)));
                break;
            case 'get_fav_ids':
                echo json_encode(array('js' => $dev['fav_channels']['live']));
                die;
                break;
            case 'get_all_channels':
                die(GetOrderedList(null, true));
                break;
            case 'get_ordered_list':
                $fav = !empty($_REQUEST['fav']) ? 1 : null;
                $sortby = !empty($_REQUEST['sortby']) ? $_REQUEST['sortby'] : null;
                $genre = empty($_REQUEST['genre']) || !is_numeric($_REQUEST['genre']) ? null : intval($_REQUEST['genre']);
                die(GetOrderedList($genre, false, $fav, $sortby));
                break;
            case 'get_all_fav_channels':
                $genre = empty($_REQUEST['genre']) || !is_numeric($_REQUEST['genre']) ? null : intval($_REQUEST['genre']);
                die(GetOrderedList($genre, true, 1));
                break;
            case 'get_epg_info':
                $period = empty($_REQUEST['period']) || !is_numeric($_REQUEST['period']) ? 3 : intval($_REQUEST['period']);
                $streamSys = GetStreamsFromUser($dev['user_id'], array('live', 'created_live'));
                $epg = array('js' => array());
                $epg['js']['data'] = array();
                $timestampUTCTime = ipTV_lib::GetDateUTCTimestamp($timezone);
                foreach ($streamSys['streams'] as $order_id => $stream) {
                    if (empty($stream['channel_id'])) {
                        continue;
                    }
                    if (file_exists(TMP_DIR . "epg_info_{$stream['id']}_stalker")) {
                        $general_epg_datas = json_decode(file_get_contents(TMP_DIR . "epg_info_{$stream['id']}_stalker"), true);
                    } else {
                        $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp, UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `start` >= \'%s\' AND `end` <= \'%s\' AND `channel_id` = \'%s\' ORDER BY `start` ASC LIMIT 10', date('Y-m-d H:i:00'), date('Y-m-d H:i:00', strtotime("+{$period} hours")), $stream['channel_id']);
                        $general_epg_datas = $ipTV_db->get_rows();
                        file_put_contents(TMP_DIR . "epg_info_{$stream['id']}_stalker", json_encode($general_epg_datas));
                    }
                    if (!empty($general_epg_datas)) {
                        $index = 0;
                        while ($index < count($general_epg_datas)) {
                            $epgStart = new DateTime($general_epg_datas[$index]['start']);
                            $epgStart->modify("{$timestampUTCTime} seconds");
                            $epgEnd = new DateTime($general_epg_datas[$index]['end']);
                            $epgEnd->modify("{$timestampUTCTime} seconds");
                            $epg['js']['data'][$stream['id']][$index]['id'] = $general_epg_datas[$index]['id'];
                            $epg['js']['data'][$stream['id']][$index]['ch_id'] = $stream['id'];
                            $epg['js']['data'][$stream['id']][$index]['time'] = $epgStart->format('Y-m-d H:i:s');
                            $epg['js']['data'][$stream['id']][$index]['time_to'] = $epgEnd->format('Y-m-d H:i:s');
                            $epg['js']['data'][$stream['id']][$index]['duration'] = $general_epg_datas[$index]['start_timestamp'] - $general_epg_datas[$index]['stop_timestamp'];
                            $epg['js']['data'][$stream['id']][$index]['name'] = base64_decode($general_epg_datas[$index]['title']);
                            $epg['js']['data'][$stream['id']][$index]['descr'] = base64_decode($general_epg_datas[$index]['description']);
                            $epg['js']['data'][$stream['id']][$index]['real_id'] = $stream['id'] . '_' . $general_epg_datas[$index]['start_timestamp'];
                            $epg['js']['data'][$stream['id']][$index]['category'] = '';
                            $epg['js']['data'][$stream['id']][$index]['director'] = '';
                            $epg['js']['data'][$stream['id']][$index]['actor'] = '';
                            $epg['js']['data'][$stream['id']][$index]['start_timestamp'] = $epgStart->getTimestamp();
                            $epg['js']['data'][$stream['id']][$index]['stop_timestamp'] = $epgEnd->getTimestamp();
                            $epg['js']['data'][$stream['id']][$index]['t_time'] = $epgStart->format('H:i');
                            $epg['js']['data'][$stream['id']][$index]['t_time_to'] = $epgEnd->format('H:i');
                            $epg['js']['data'][$stream['id']][$index]['display_duration'] = $general_epg_datas[$index]['start_timestamp'] - $general_epg_datas[$index]['stop_timestamp'];
                            $epg['js']['data'][$stream['id']][$index]['larr'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['rarr'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['mark_rec'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['mark_memo'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['mark_archive'] = 0;
                            $epg['js']['data'][$stream['id']][$index]['on_date'] = $epgStart->format('l d.m.Y');
                            $index++;
                        }
                    }
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
                        $epg_dats = json_decode(file_get_contents(TMP_DIR . "epg_{$ch_id}_stalker"), true);
                    } else {
                        $epg_dats = array();
                        $ipTV_db->query('SELECT `channel_id`,`epg_id` FROM `streams` WHERE `id` = \'%d\' AND epg_id IS NOT NULL', $ch_id);
                        if ($ipTV_db->num_rows() > 0) {
                            $epg_data = $ipTV_db->get_row();
                            $ipTV_db->simple_query("SELECT *,UNIX_TIMESTAMP(start) as start_timestamp, UNIX_TIMESTAMP(end) as stop_timestamp  FROM `epg_data` WHERE `epg_id` = '{$epg_data['epg_id']}' AND `channel_id` = '" . $ipTV_db->escape($epg_data['channel_id']) . '\' AND (\'' . date('Y-m-d H:i:00') . '\' BETWEEN `start` AND `end` OR `start` >= \'' . date('Y-m-d H:i:00') . '\') ORDER BY `start` LIMIT 4');
                            if ($ipTV_db->num_rows() > 0) {
                                $epg_dats = $ipTV_db->get_rows();
                            }
                        }
                        file_put_contents(TMP_DIR . "epg_{$ch_id}_stalker", json_encode($epg_dats));
                    }
                    if (!empty($epg_dats)) {
                        $timestampUTCTime = ipTV_lib::GetDateUTCTimestamp($timezone);
                        $index = 0;
                        while ($index < count($epg_dats)) {
                            $epgStart = new DateTime($epg_dats[$index]['start']);
                            $epgStart->modify("{$timestampUTCTime} seconds");
                            $epgEnd = new DateTime($epg_dats[$index]['end']);
                            $epgEnd->modify("{$timestampUTCTime} seconds");
                            $epg['js'][$index]['id'] = $epg_dats[$index]['id'];
                            $epg['js'][$index]['ch_id'] = $ch_id;
                            $epg['js'][$index]['correct'] = $epgStart->format('Y-m-d H:i:s');
                            $epg['js'][$index]['time'] = $epgStart->format('Y-m-d H:i:s');
                            $epg['js'][$index]['time_to'] = $epgEnd->format('Y-m-d H:i:s');
                            $epg['js'][$index]['duration'] = $epg_dats[$index]['stop_timestamp'] - $epg_dats[$index]['start_timestamp'];
                            $epg['js'][$index]['name'] = base64_decode($epg_dats[$index]['title']);
                            $epg['js'][$index]['descr'] = base64_decode($epg_dats[$index]['description']);
                            $epg['js'][$index]['real_id'] = $ch_id . '_' . $epg_dats[$index]['start_timestamp'];
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
                $live_categories = GetCategories('live');
                if (ipTV_lib::$settings['show_all_category_mag'] == 1) {
                    $output['js'][] = array('id' => '*', 'title' => 'All', 'alias' => 'All', 'active_sub' => true, 'censored' => 0);
                }
                foreach ($live_categories as $live_category_id => $live_category) {
                    if (!ipTV_streaming::CategoriesBouq($live_category_id, $dev['bouquet'])) {
                        continue;
                    }
                    $output['js'][] = array('id' => $live_category['id'], 'title' => $live_category['category_name'], 'modified' => '', 'number' => $episode_num++, 'alias' => mb_strtolower($live_category['category_name']), 'censored' => stristr($live_category['category_name'], 'adults') ? 1 : 0);
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
                $category_id = array_column($user_info['channels'], 'category_id');
                if (ipTV_lib::$settings['show_all_category_mag'] == 1) {
                    $output['js'][] = array('id' => '*', 'title' => 'All', 'alias' => '*', 'censored' => 0);
                }
                foreach ($categories as $key => $category) {
                    if (!ipTV_streaming::CategoriesBouq($key, $dev['bouquet'])) {
                        continue;
                    }
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
                $movie_properties = array();
                $movie_properties['abc'] = !empty(ipTV_lib::$request['abc']) ? ipTV_lib::$request['abc'] : '*';
                $movie_properties['genre'] = !empty(ipTV_lib::$request['genre']) ? ipTV_lib::$request['genre'] : '*';
                $movie_properties['years'] = !empty(ipTV_lib::$request['years']) ? ipTV_lib::$request['years'] : '*';
                die(GetVodOrderedList($category, $fav, $sortby, $search, $movie_properties));
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
                die(GetSeriesOrderedList($movie_id, $category, $fav, $sortby, $search, $movie_properties));
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
                    $exp_date = $_LANG['unlimited'];
                } else {
                    $exp_date = date('F j, Y, g:i a', $dev['exp_date']);
                }
                die(json_encode(array('js' => array('mac' => $mac, 'phone' => $exp_date))));
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
                die(GetRadioOrderedList(null, $fav, $sortby));
                break;
            case 'get_all_fav_radio':
                die(GetRadioOrderedList(null, 1, null));
                break;
            case 'set_fav':
                $fav_radio = empty($_REQUEST['fav_radio']) ? '' : $_REQUEST['fav_radio'];
                $fav_radio = array_filter(array_map('intval', explode(',', $fav_radio)));
                $dev['fav_channels']['radio_streams'] = $fav_radio;
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
                        $start = date('Y-m-d:H-i', strtotime($row['start']));
                        $duration = intval((strtotime($row['end']) - strtotime($row['start'])) / 60);
                        $title = base64_decode($row['title']);
                        $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "timeshift/{$dev['username']}/{$dev['password']}/{$duration}/{$start}/{$stream_id}.ts?&osd_title={$title}";
                        die(json_encode(array('js' => $url)));
                    }
                }
                die(json_encode(array('js' => false)));
                break;
            case 'create_link':
                $cmd = empty($_REQUEST['cmd']) ? '' : $_REQUEST['cmd'];
                list($epgDataID, $stream_id) = explode('_', pathinfo($cmd)['filename']);
                $ipTV_db->query('SELECT t2.tv_archive_server_id,t1.start,t1.end,t2.id as stream_id
                                    FROM epg_data t1
                                    INNER JOIN `streams` t2 ON t2.id = \'%d\'
                                    WHERE t1.id = \'%d\' AND t2.tv_archive_server_id IS NOT NULL', $stream_id, $epgDataID);
                if ($ipTV_db->num_rows() > 0) {
                    $row = $ipTV_db->get_row();
                    $start = date('Y-m-d:H-i', strtotime($row['start']));
                    $duration = intval((strtotime($row['end']) - strtotime($row['start'])) / 60);
                    $play_token = ipTV_lib::GenerateString();
                    $ipTV_db->query('UPDATE `users` SET `play_token` = \'%s\' WHERE `id` = \'%d\'', $play_token . ':' . strtotime('+5 hours') . ':' . $stream_id, $dev['user_id']);
                    $url = $player . ipTV_lib::$StreamingServers[SERVER_ID]['site_url'] . "timeshift/{$dev['username']}/{$dev['password']}/{$duration}/{$start}/{$row['stream_id']}.ts?play_token={$play_token}";
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
                $epg_week = array();
                $curDate = strtotime(date('Y-m-d'));
                while ($k < 10) {
                    $thisDate = $curDate + $k * 86400;
                    $epg_week['js'][$index]['f_human'] = date('D d F', $thisDate);
                    $epg_week['js'][$index]['f_mysql'] = date('Y-m-d', $thisDate);
                    $epg_week['js'][$index]['today'] = $k == 0 ? 1 : 0;
                    $k++;
                    $index++;
                }
                die(json_encode($epg_week));
                break;
            case 'get_data_table':
                die(json_encode(array('js' => getDataTable()), JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
            case 'get_simple_data_table':
                if (!empty($_REQUEST['ch_id']) && !empty($_REQUEST['date'])) {
                    $ch_id = $_REQUEST['ch_id'];
                    $req_date = $_REQUEST['date'];
                    $page = intval($_REQUEST['p']);
                    $page_items = 10;
                    $default_page = false;
                    $ipTV_db->query('SELECT `tv_archive_duration`,`channel_id`,`epg_id` FROM `streams` WHERE `id` = \'%d\' AND epg_id IS NOT NULL', $ch_id);
                    $simple_data_epgs = array();
                    $total_items = 0;
                    $ch_idx = 0;
                    if ($ipTV_db->num_rows() > 0) {
                        $stream = $ipTV_db->get_row();
                        $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp,UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `epg_id` = \'%d\' AND `channel_id` = \'%s\' AND `start` >= \'%s\' AND `start` <= \'%s\' ORDER BY `start` ASC', $stream['epg_id'], $stream['channel_id'], $req_date . ' 00:00:00', $req_date . ' 23:59:59');
                        if ($ipTV_db->num_rows() > 0) {
                            $simple_data_epgs = $ipTV_db->get_rows();
                            foreach ($simple_data_epgs as $key => $epg_data) {
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
                        if ($req_date != date('Y-m-d')) {
                            $page = 1;
                            $default_page = false;
                        }
                    }
                    $program = array_slice($simple_data_epgs, ($page - 1) * $page_items, $page_items);
                    $data = array();
                    $timestampUTCTime = ipTV_lib::GetDateUTCTimestamp($timezone);
                    $index = 0;
                    while ($index < count($program)) {
                        $open = 0;
                        if ($program[$index]['stop_timestamp'] >= time()) {
                            $open = 1;
                        }
                        $epgStart = new DateTime($program[$index]['start']);
                        $epgStart->modify("{$timestampUTCTime} seconds");
                        $epgEnd = new DateTime($program[$index]['end']);
                        $epgEnd->modify("{$timestampUTCTime} seconds");
                        $data[$index]['id'] = $program[$index]['id'] . '_' . $ch_id;
                        $data[$index]['ch_id'] = $ch_id;
                        $data[$index]['time'] = $epgStart->format('Y-m-d H:i:s');
                        $data[$index]['time_to'] = $epgEnd->format('Y-m-d H:i:s');
                        $data[$index]['duration'] = $program[$index]['stop_timestamp'] - $program[$index]['start_timestamp'];
                        $data[$index]['name'] = base64_decode($program[$index]['title']);
                        $data[$index]['descr'] = base64_decode($program[$index]['description']);
                        $data[$index]['real_id'] = $ch_id . '_' . $program[$index]['start'];
                        $data[$index]['category'] = '';
                        $data[$index]['director'] = '';
                        $data[$index]['actor'] = '';
                        $data[$index]['start_timestamp'] = $epgStart->getTimestamp();
                        $data[$index]['stop_timestamp'] = $epgEnd->getTimestamp();
                        $data[$index]['t_time'] = $epgStart->format('H:i');
                        $data[$index]['t_time_to'] = $epgEnd->format('H:i');
                        $data[$index]['open'] = $open;
                        $data[$index]['mark_memo'] = 0;
                        $data[$index]['mark_rec'] = 0;
                        $data[$index]['mark_archive'] = !empty($stream['tv_archive_duration']) && time() > $epgEnd->getTimestamp() && strtotime("-{$stream['tv_archive_duration']} days") <= $epgEnd->getTimestamp() ? 1 : 0;
                        $index++;
                    }

                    if ($default_page) {
                        $cur_page = $page;
                        $selected_item = $ch_idx - ($page - 1) * $page_items;
                    } else {
                        $cur_page = 0;
                        $selected_item = 0;
                    }
                    $output = array();
                    $output['js']['cur_page'] = $cur_page;
                    $output['js']['selected_item'] = $selected_item;
                    $output['js']['total_items'] = count($simple_data_epgs);
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
                        $timestampUTCTime = ipTV_lib::GetDateUTCTimestamp($timezone);
                        foreach ($ipTV_db->get_rows() as $row) {
                            $epgStart = new DateTime($row['start']);
                            $epgStart->modify("{$timestampUTCTime} seconds");
                            $epgEnd = new DateTime($row['end']);
                            $epgEnd->modify("{$timestampUTCTime} seconds");
                            $output['js'][] = array('start_timestamp' => $epgStart->getTimestamp(), 'stop_timestamp' => $epgEnd->getTimestamp(), 'name' => base64_decode($row['title']));
                        }
                    }
                }
                die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
                break;
        }
        break;
}
function GetVodOrderedList($category_id = null, $fav = null, $orderby = null, $stream_display_name = null, $movie_properties = array())
{
    global $dev, $player, $_LANG;
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
    $datas = array();
    foreach ($streamSys as $movie) {
        if (!is_null($fav) && $fav == 1) {
            if (in_array($movie['id'], $dev['fav_channels']['movie'])) {
                $movie_properties = ipTV_lib::movieProperties($movie['id']);
                $cmd = array('type' => 'movie', 'stream_id' => $movie['id'], 'stream_source' => $movie['stream_source'], 'target_container' => $movie['target_container']);
                $this_mm = date('m');
                $this_dd = date('d');
                $this_yy = date('Y');
                if (($movie['added'] > mktime(0, 0, 0, $this_mm, $this_dd, $this_yy))) {
                    $added_key = 'today';
                    $added_val = $_LANG['today'];
                }
                else if (($movie['added'] > mktime(0, 0, 0, $this_mm, $this_dd - 1, $this_yy))) {
                    $added_key = 'yesterday';
                    $added_val = $_LANG['yesterday'];   
                }
                else if ($movie['added'] > mktime(0, 0, 0, $this_mm, $this_dd - 7, $this_yy)) {
                    $added_key = 'week_and_more';
                    $added_val = $_LANG['last_week'];
                } else {
                    $added_key = 'week_and_more';
                    $added_val = date('F', $movie['added']) . ' ' . date('Y', $movie['added']);
                }
                $duration = isset($movie_properties['duration_secs']) ? $movie_properties['duration_secs'] : 60;
                $datas[] = array('id' => $movie['id'], 'owner' => '', 'name' => $movie['stream_display_name'], 'old_name' => '', 'o_name' => $movie['stream_display_name'], 'fname' => '', 'description' => empty($movie_properties['plot']) ? 'N/A' : $movie_properties['plot'], 'pic' => '', 'cost' => 0, 'time' => intval($duration / 60), 'file' => '', 'path' => str_replace(' ', '_', $movie['stream_display_name']), 'protocol' => '', 'rtsp_url' => '', 'censored' => $movie['is_adult'], 'series' => array(), 'volume_correction' => 0, 'category_id' => $movie['category_id'], 'genre_id' => 0, 'genre_id_1' => 0, 'genre_id_2' => 0, 'genre_id_3' => 0, 'hd' => 1, 'genre_id_4' => 0, 'cat_genre_id_1' => $movie['category_id'], 'cat_genre_id_2' => 0, 'cat_genre_id_3' => 0, 'cat_genre_id_4' => 0, 'director' => empty($movie_properties['director']) ? 'N/A' : $movie_properties['director'], 'actors' => empty($movie_properties['cast']) ? 'N/A' : $movie_properties['cast'], 'year' => empty($movie_properties['releasedate']) ? 'N/A' : $movie_properties['releasedate'], 'accessed' => 1, 'status' => 1, 'disable_for_hd_devices' => 0, 'added' => date('Y-m-d H:i:s', $movie['added']), 'count' => 0, 'count_first_0_5' => 0, 'count_second_0_5' => 0, 'vote_sound_good' => 0, 'vote_sound_bad' => 0, 'vote_video_good' => 0, 'vote_video_bad' => 0, 'rate' => '', 'last_rate_update' => '', 'last_played' => '', 'for_sd_stb' => 0, 'rating_imdb' => empty($movie_properties['rating']) ? 'N/A' : $movie_properties['rating'], 'rating_count_imdb' => '', 'rating_last_update' => '0000-00-00 00:00:00', 'age' => '12+', 'high_quality' => 0, 'rating_kinopoisk' => empty($movie_properties['rating']) ? 'N/A' : $movie_properties['rating'], 'comments' => '', 'low_quality' => 0, 'is_series' => 0, 'year_end' => 0, 'autocomplete_provider' => 'imdb', 'screenshots' => '', 'is_movie' => 1, 'lock' => $movie['is_adult'], 'fav' => in_array($movie['id'], $dev['fav_channels']['movie']) ? 1 : 0, 'for_rent' => 0, 'screenshot_uri' => empty($movie_properties['movie_image']) ? '' : $movie_properties['movie_image'], 'genres_str' => empty($movie_properties['genre']) ? 'N/A' : $movie_properties['genre'], 'cmd' => base64_encode(json_encode($cmd, JSON_PARTIAL_OUTPUT_ON_ERROR)), $added_key => $added_val, 'has_files' => 0);
            } else {
                --$counter;
            }
        }
    }
    if ($default_page) {
        $cur_page = $page;
        $selected_item = $ch_idx - ($page - 1) * $page_items;
    } else {
        $cur_page = 0;
        $selected_item = 0;
    }
    $output = array('js' => array('total_items' => $counter, 'max_page_items' => $page_items, 'selected_item' => $selected_item, 'cur_page' => $cur_page, 'data' => $datas));
    return json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR);
}
function EfbbCCe59aAbcEb0b973f0Ba1a94d948($id, $fav = null, $orderby = null, $stream_display_name = null, $movie_properties = array())
{
    global $ipTV_db;
    $ipTV_db->query('SELECT * FROM `series_episodes` t1 INNER JOIN `streams` t2 ON t2.id=t1.stream_id WHERE t1.series_id = \'%d\' ORDER BY t1.season_num DESC, t1.sort ASC', $id);
    $cc69dbb0749cadc4d6c0543ba360c09d = $ipTV_db->get_rows(true, 'season_num', false);
    return $cc69dbb0749cadc4d6c0543ba360c09d;
}
function GetSeriesOrderedList($movie_id = null, $category_id = null, $fav = null, $orderby = null, $stream_display_name = null, $movie_properties = array())
{
    global $dev, $player, $_LANG, $ipTV_db;
    $page = !empty(ipTV_lib::$request['p']) ? ipTV_lib::$request['p'] : 0;
    $page_items = 14;
    $default_page = false;
    if (empty($movie_id)) {
        $Fc0cf310dd1b2294ab167a0658937ab5 = eA44215481573d77C59D844454c19797($dev['user_id'], $category_id, $fav, $orderby, $stream_display_name, $movie_properties);
    } else {
        $Fc0cf310dd1b2294ab167a0658937ab5 = eFBbCCe59AaBcEb0b973F0Ba1A94D948($movie_id, $fav, $orderby, $stream_display_name, $movie_properties);
        $ipTV_db->query('SELECT * FROM `series` WHERE `id` = \'%d\'', $movie_id);
        $serie = $ipTV_db->get_row();
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
    $datas = array();
    foreach ($Fc0cf310dd1b2294ab167a0658937ab5 as $key => $movie) {
        if (!is_null($fav) && $fav == 1 && empty($movie_id)) {
            if (in_array($movie['id'], $dev['fav_channels']['series'])) {
                if (!empty($serie)) {
                    $movie_propeties = $serie;
                    $Bb2aa338b4f24748194863e13511a725 = 0;
                    foreach ($movie as $Eb16297b6431c4849004e993b69db954) {
                        if ($Eb16297b6431c4849004e993b69db954['added'] > $Bb2aa338b4f24748194863e13511a725) {
                            $Bb2aa338b4f24748194863e13511a725 = $Eb16297b6431c4849004e993b69db954['added'];
                        }
                    }
                } else {
                    $movie_propeties = $movie;
                    $Bb2aa338b4f24748194863e13511a725 = $movie['last_modified'];
                }
                $cmd = array('type' => 'series', 'series_id' => $movie_id, 'season_num' => $key);
                $this_mm = date('m');
                $this_dd = date('d');
                $this_yy = date('Y');
                if (($Bb2aa338b4f24748194863e13511a725 > mktime(0, 0, 0, $this_mm, $this_dd, $this_yy))) {
                    $added_key = 'today';
                    $added_val = $_LANG['today'];
                }
                else if (($Bb2aa338b4f24748194863e13511a725 > mktime(0, 0, 0, $this_mm, $this_dd - 1, $this_yy))) {
                    $added_key = 'yesterday';
                    $added_val = $_LANG['yesterday'];
                }
                else if ($Bb2aa338b4f24748194863e13511a725 > mktime(0, 0, 0, $this_mm, $this_dd - 7, $this_yy)) {
                    $added_key = 'week_and_more';
                    $added_val = $_LANG['last_week'];
                } else {
                    $added_key = 'week_and_more';
                    $added_val = date('F', $Bb2aa338b4f24748194863e13511a725) . ' ' . date('Y', $Bb2aa338b4f24748194863e13511a725);
                }
                if (!empty($serie)) {
                    if ($key == 0) {
                        $title = $_LANG['specials'];
                    } else {
                        $title = $_LANG['season'] . ' ' . $key;
                    }
                } else {
                    $title = $movie['title'];
                }
                $datas[] = array('id' => empty($movie_id) ? $movie_propeties['id'] : $movie_propeties['id'] . ':' . $key, 'owner' => '', 'name' => $title, 'old_name' => '', 'o_name' => $title, 'fname' => '', 'description' => empty($movie_propeties['plot']) ? 'N/A' : $movie_propeties['plot'], 'pic' => '', 'cost' => 0, 'time' => 'N/a', 'file' => '', 'path' => str_replace(' ', '_', $movie_propeties['title']), 'protocol' => '', 'rtsp_url' => '', 'censored' => 0, 'series' => !empty($serie) ? range(1, count($movie)) : array(), 'volume_correction' => 0, 'category_id' => $movie_propeties['category_id'], 'genre_id' => 0, 'genre_id_1' => 0, 'genre_id_2' => 0, 'genre_id_3' => 0, 'hd' => 1, 'genre_id_4' => 0, 'cat_genre_id_1' => $movie_propeties['category_id'], 'cat_genre_id_2' => 0, 'cat_genre_id_3' => 0, 'cat_genre_id_4' => 0, 'director' => empty($movie_propeties['director']) ? 'N/A' : $movie_propeties['director'], 'actors' => empty($movie_propeties['cast']) ? 'N/A' : $movie_propeties['cast'], 'year' => empty($movie_propeties['releaseDate']) ? 'N/A' : $movie_propeties['releaseDate'], 'accessed' => 1, 'status' => 1, 'disable_for_hd_devices' => 0, 'added' => date('Y-m-d H:i:s', $Bb2aa338b4f24748194863e13511a725), 'count' => 0, 'count_first_0_5' => 0, 'count_second_0_5' => 0, 'vote_sound_good' => 0, 'vote_sound_bad' => 0, 'vote_video_good' => 0, 'vote_video_bad' => 0, 'rate' => '', 'last_rate_update' => '', 'last_played' => '', 'for_sd_stb' => 0, 'rating_imdb' => empty($movie_propeties['rating']) ? 'N/A' : $movie_propeties['rating'], 'rating_count_imdb' => '', 'rating_last_update' => '0000-00-00 00:00:00', 'age' => '12+', 'high_quality' => 0, 'rating_kinopoisk' => empty($movie_propeties['rating']) ? 'N/A' : $movie_propeties['rating'], 'comments' => '', 'low_quality' => 0, 'is_series' => 1, 'year_end' => 0, 'autocomplete_provider' => 'imdb', 'screenshots' => '', 'is_movie' => 1, 'lock' => 0, 'fav' => empty($movie_id) && in_array($movie_propeties['id'], $dev['fav_channels']['series']) ? 1 : 0, 'for_rent' => 0, 'screenshot_uri' => empty($movie_propeties['cover']) ? '' : $movie_propeties['cover'], 'genres_str' => empty($movie_propeties['genre']) ? 'N/A' : $movie_propeties['genre'], 'cmd' => !empty($serie) ? base64_encode(json_encode($cmd, JSON_PARTIAL_OUTPUT_ON_ERROR)) : '', $added_key => $added_val, 'has_files' => empty($movie_id) ? 1 : 0);
            } else {
                --$counter;
            }
        }
    }
    if ($default_page) {
        $cur_page = $page;
        $selected_item = $ch_idx - ($page - 1) * $page_items;
    } else {
        $cur_page = 0;
        $selected_item = 0;
    }
    $output = array('js' => array('total_items' => $counter, 'max_page_items' => $page_items, 'selected_item' => $selected_item, 'cur_page' => $cur_page, 'data' => $datas));
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
                $existFav = false;
                foreach ($types as $type) {
                    if (!empty($dev['fav_channels'][$type]) && in_array($stream['id'], $dev['fav_channels'][$type])) {
                        $existFav = true;
                        break;
                    }
                }
                if (!$existFav) {
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
function GetRadioOrderedList($category_id = null, $fav = null, $orderby = null)
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
    $datas = array();
    $index = 1;
    foreach ($streamSys as $order_id => $stream) {
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
            $stream_source_st = 0;
        } else {
            if (!empty($stream['stream_source'])) {
                $url = $player . "http://localhost/ch/{$stream['id']}_" . json_decode($stream['stream_source'], true)[0];
            } else {
                $url = $player . "http://localhost/ch/{$stream['id']}_";
            }
            $stream_source_st = 1;
        }
        $datas[] = array('id' => $stream['id'], 'name' => $stream['stream_display_name'], 'number' => $index++, 'cmd' => $url, 'count' => 0, 'open' => 1, 'use_http_tmp_link' => "{$stream_source_st}", 'status' => 1, 'volume_correction' => 0, 'fav' => in_array($stream['id'], $dev['fav_channels']['radio_streams']) ? 1 : 0);
    }
    if ($default_page) {
        $cur_page = $page;
        $selected_item = $ch_idx - ($page - 1) * $page_items;
    } else {
        $cur_page = 0;
        $selected_item = 0;
    }
    $output = array('js' => array('total_items' => $counter, 'max_page_items' => $page_items, 'selected_item' => $selected_item, 'cur_page' => $cur_page, 'data' => $datas));
    return json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR);
}
function GetOrderedList($category_id = null, $all = false, $fav = null, $orderby = null)
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
    if (!$all) {
        $streamSys = array_slice($streamSys['streams'], ($page - 1) * $page_items, $page_items);
    } else {
        $streamSys = $streamSys['streams'];
    }
    $epgInfo = '';
    $datas = array();
    $index = 1;
    foreach ($streamSys as $order_id => $stream) {
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
            $stream_source_st = 0;
        } else {
            if (!empty($stream['stream_source'])) {
                $url = $player . "http://localhost/ch/{$stream['id']}_" . json_decode($stream['stream_source'], true)[0];
            } else {
                $url = $player . "http://localhost/ch/{$stream['id']}_";
            }
            $stream_source_st = 1;
        }
        $datas[] = array('id' => $stream['id'], 'name' => $stream['stream_display_name'], 'number' => (string) $stream['number'], 'censored' => $stream['is_adult'] == 1 ? '1' : '', 'cmd' => $url, 'cost' => '0', 'count' => '0', 'status' => 1, 'hd' => 0, 'tv_genre_id' => $stream['category_id'], 'base_ch' => '1', 'hd' => '0', 'xmltv_id' => !empty($stream['channel_id']) ? $stream['channel_id'] : '', 'service_id' => '', 'bonus_ch' => '0', 'volume_correction' => '0', 'mc_cmd' => '', 'enable_tv_archive' => $stream['tv_archive_duration'] > 0 ? 1 : 0, 'wowza_tmp_link' => '0', 'wowza_dvr' => '0', 'use_http_tmp_link' => "{$stream_source_st}", 'monitoring_status' => '1', 'enable_monitoring' => '0', 'enable_wowza_load_balancing' => '0', 'cmd_1' => '', 'cmd_2' => '', 'cmd_3' => '', 'logo' => $stream['stream_icon'], 'correct_time' => '0', 'nimble_dvr' => '0', 'allow_pvr' => (int) $stream['allow_record'], 'allow_local_pvr' => (int) $stream['allow_record'], 'allow_remote_pvr' => 0, 'modified' => '', 'allow_local_timeshift' => '1', 'nginx_secure_link' => "{$stream_source_st}", 'tv_archive_duration' => $stream['tv_archive_duration'] > 0 ? $stream['tv_archive_duration'] * 24 : 0, 'locked' => 0, 'lock' => $stream['is_adult'], 'fav' => in_array($stream['id'], $dev['fav_channels']['live']) ? 1 : 0, 'archive' => $stream['tv_archive_duration'] > 0 ? 1 : 0, 'genres_str' => '', 'cur_playing' => '[No channel info]', 'epg' => array(), 'open' => 1, 'cmds' => array(array('id' => (string) $stream['id'], 'ch_id' => (string) $stream['id'], 'priority' => '0', 'url' => $player . "http : //localhost/ch/{$stream['id']}_{$streamIdValue}", 'status' => '1', 'use_http_tmp_link' => "{$stream_source_st}", 'wowza_tmp_link' => '0', 'user_agent_filter' => '', 'use_load_balancing' => '0', 'changed' => '', 'enable_monitoring' => '0', 'enable_balancer_monitoring' => '0', 'nginx_secure_link' => "{$stream_source_st}", 'flussonic_tmp_link' => '0')), 'use_load_balancing' => 0, 'pvr' => (int) $stream['allow_record']);
    }
    if ($default_page) {
        $cur_page = $page;
        $selected_item = $ch_idx - ($page - 1) * $page_items;
    } else {
        $cur_page = 0;
        $selected_item = 0;
    }
    $output = array('js' => array('total_items' => $counter, 'max_page_items' => $page_items, 'selected_item' => $selected_item, 'cur_page' => $all ? 0 : $cur_page, 'data' => $datas));
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
            $timestampUTCTime = ipTV_lib::GetDateUTCTimestamp($timezone);
            foreach (epg_search($raw_epg, 'stream_id', $stream_id) as $epg) {
                if (!empty($epg['epg_id'])) {
                    $epgStart = new DateTime($epg['start']);
                    $epgStart->modify("{$timestampUTCTime} seconds");
                    $epgEnd = new DateTime($epg['end']);
                    $epgEnd->modify("{$timestampUTCTime} seconds");
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
function GetAllHeadersFn($token)
{
    if (empty($token)) {
        return;
    }
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
    } else {
        $headers = newHeaders();
    }
    if (!$headers) {
        return;
    }
    $Authorization = !empty($headers['Authorization']) ? $headers['Authorization'] : null;
    if ($Authorization && preg_match('/Bearer\\s+(.*)$/i', $Authorization, $matches)) {
        if ($token == trim($matches[1])) {
            return true;
        }
    }
    die;
}
function newHeaders()
{
    $headers = array();
    foreach ($_SERVER as $key => $value) {
        if (substr($key, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))))] = $value;
        }
    }
    return $headers;
}
?>
