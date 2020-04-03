<?php
/*Rev:26.09.18r0*/

if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
    die;
}
set_time_limit(0);
require '../init.php';
if (ipTV_lib::$request['call'] == 'publish') {
    if (!in_array(ipTV_lib::$request['addr'], ipTV_streaming::RtmpIps())) {
        http_response_code(404);
        die;
    } else {
        http_response_code(200);
        die;
    }
}
if (ipTV_lib::$request['call'] == 'play_done') {
    ipTV_streaming::playDone(ipTV_lib::$request['clientid']);
    http_response_code(200);
    die;
}
if (empty(ipTV_lib::$request['username']) && empty(ipTV_lib::$request['password']) && in_array(ipTV_lib::$request['addr'], ipTV_streaming::getAllowedIPsAdmin())) {
    http_response_code(200);
    die;
}
if (!isset(ipTV_lib::$request['username']) || !isset(ipTV_lib::$request['password']) || !isset(ipTV_lib::$request['tcurl']) || !isset(ipTV_lib::$request['app'])) {
    http_response_code(404);
    die('Missing parameters.');
}
$stream_id = intval(ipTV_lib::$request['name']);
$user_ip = ipTV_lib::$request['addr'];
$username = ipTV_lib::$request['username'];
$password = ipTV_lib::$request['password'];
$extension = 'rtmp';
$external_device = '';
if ($user_info = ipTV_streaming::GetUserInfo(null, $username, $password, true, false, true, array(), false, $user_ip)) {
    if (!is_null($user_info['exp_date']) && time() >= $user_info['exp_date']) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_EXPIRED', $user_ip);
        http_response_code(404);
        die;
    }
    if ($user_info['admin_enabled'] == 0) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_BAN', $user_ip);
        http_response_code(404);
        die;
    }
    if ($user_info['enabled'] == 0) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_DISABLED', $user_ip);
        http_response_code(404);
        die;
    }
    $geoip = new Reader(GEOIP2_FILENAME);
    $geoip_country_code = $geoip->getWithPrefixLen($user_ip)['registered_country']['iso_code'];
    $geoip->close();
    if (!empty($user_info['allowed_ips']) && !in_array($user_ip, array_map('gethostbyname', $user_info['allowed_ips']))) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'IP_BAN', $user_ip);
        http_response_code(404);
    }
    if (!empty($geoip_country_code)) {
        $forced_country = !empty($user_info['forced_country']) ? true : false;
        if ($forced_country && $user_info['forced_country'] != 'ALL' && $geoip_country_code != $user_info['forced_country']) {
            ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'COUNTRY_DISALLOW', $user_ip);
            http_response_code(404);
            die;
        }
        if (!$forced_country && !in_array('ALL', ipTV_lib::$settings['allow_countries']) && !in_array($geoip_country_code, ipTV_lib::$settings['allow_countries'])) {
            ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'COUNTRY_DISALLOW', $user_ip);
            http_response_code(404);
            die;
        }
    }
    if (isset($user_info['ip_limit_reached'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_ALREADY_CONNECTED', $user_ip);
        http_response_code(404);
        die;
    }
    if (ipTV_streaming::checkIsCracked($user_ip)) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'CRACKED', $user_ip);
        http_response_code(404);
        die;
    }
    if (!array_key_exists($extension, $user_info['output_formats'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'USER_DISALLOW_EXT', $user_ip);
        http_response_code(404);
        die;
    }
    if (!in_array($stream_id, $user_info['channel_ids'])) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'NOT_IN_BOUQUET', $user_ip);
        http_response_code(404);
        die;
    }
    if ($user_info['max_connections'] != 0) {
        if (!empty($user_info['pair_line_info'])) {
            if ($user_info['pair_line_info']['max_connections'] != 0) {
                if ($user_info['pair_line_info']['active_cons'] >= $user_info['pair_line_info']['max_connections']) {
                    ipTV_streaming::CloseLastCon($user_info['pair_id'], $user_info['pair_line_info']['max_connections']);
                }
            }
        }
        if ($user_info['active_cons'] >= $user_info['max_connections']) {
            ipTV_streaming::CloseLastCon($user_info['id'], $user_info['max_connections']);
        }
    }
    if ($user_info['isp_violate'] == 1) {
        http_response_code(401);
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'ISP_LOCK_FAILED', $user_ip, json_encode(array('old' => $user_info['isp_desc'], 'new' => $user_info['con_isp_name'])));
        die;
    }
    if ($user_info['isp_is_server'] == 1) {
        ipTV_streaming::ClientLog($stream_id, $user_info['id'], 'CON_SVP', $user_ip, json_encode(array('user_agent' => $user_agent, 'isp' => $user_info['con_isp_name'], 'type' => $user_info['con_isp_type'])), true);
        http_response_code(401);
        die;
    }
    if ($channel_info = ipTV_streaming::ChannelInfo($stream_id, $extension, $user_info, $user_ip, $geoip_country_code, $external_device, $user_info['con_isp_name'], 'live')) {
        $playlist = STREAMS_PATH . $stream_id . '_.m3u8';
        if (!ipTV_streaming::ps_running($channel_info['pid'], FFMPEG_PATH) && $channel_info['on_demand'] == 1) {
            ipTV_stream::startStream($stream_id);
            sleep(5);
        }
        if ($user_info['max_connections'] == 0 || $user_info['active_cons'] < $user_info['max_connections']) {
            $ipTV_db->query('INSERT INTO `user_activity_now` (`user_id`,`stream_id`,`server_id`,`user_agent`,`user_ip`,`container`,`pid`,`date_start`,`geoip_country_code`,`isp`,`external_device`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\',\'%d\',\'%d\',\'%s\',\'%s\',\'%s\')', $user_info['id'], $stream_id, SERVER_ID, '', $user_ip, $extension, ipTV_lib::$request['clientid'], time(), $geoip_country_code, $user_info['con_isp_name'], $external_device);
            $activity_id = $ipTV_db->last_insert_id();
            $ipTV_db->close_mysql();
            http_response_code(200);
            die;
        }
    }
} else {
    ipTV_streaming::ClientLog($stream_id, 0, 'AUTH_FAILED', $user_ip);
    http_response_code(404);
    die;
}
http_response_code(404);
?>
