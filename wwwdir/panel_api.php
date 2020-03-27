<?php
/*Rev:26.09.18r0*/

require 'init.php';
ini_set('memory_limit', -1);
$streaming_block = true;
if (!empty(ipTV_lib::$request['username']) && !empty(ipTV_lib::$request['password'])) {
    $valid_actions = array('get_epg');
    $username = ipTV_lib::$request['username'];
    $password = ipTV_lib::$request['password'];
    $action = !empty(ipTV_lib::$request['action']) && in_array(ipTV_lib::$request['action'], $valid_actions) ? ipTV_lib::$request['action'] : '';
    $output = array();
    $output['user_info'] = array();
    if ($result = ipTV_streaming::GetUserInfo(null, $username, $password, true, true, true)) {
        $streaming_block = false;
        switch ($action) {
            case 'get_epg':
                if (!empty(ipTV_lib::$request['stream_id']) && (is_null($result['exp_date']) or $result['exp_date'] > time())) {
                    $stream_id = intval(ipTV_lib::$request['stream_id']);
                    $from_now = !empty(ipTV_lib::$request['from_now']) && ipTV_lib::$request['from_now'] > 0 ? true : false;
                    $EPGs = GetEPGStream($stream_id, $from_now);
                    $index = 0;
                    while ($index < count($EPGs)) {
                        if (!isset($EPGs[$index]['start'])) {
                            break;
                        }
                        $EPGs[$index]['start'] = strtotime($EPGs[$index]['start']);
                        $EPGs[$index]['end'] = strtotime($EPGs[$index]['end']);
                        $index++;
                    }
                    echo json_encode($EPGs);
                    die;
                } else {
                    echo json_encode(array());
                    die;
                }
                break;
            default:
                $categories = GetCategories();
                $url = empty(ipTV_lib::$StreamingServers[SERVER_ID]['domain_name']) ? ipTV_lib::$StreamingServers[SERVER_ID]['server_ip'] : ipTV_lib::$StreamingServers[SERVER_ID]['domain_name'];
                $output['server_info'] = array('url' => $url, 'port' => ipTV_lib::$StreamingServers[SERVER_ID]['http_broadcast_port'], 'https_port' => ipTV_lib::$StreamingServers[SERVER_ID]['https_broadcast_port'], 'server_protocol' => ipTV_lib::$StreamingServers[SERVER_ID]['server_protocol']);
                $output['user_info']['username'] = $result['username'];
                $output['user_info']['password'] = $result['password'];
                $output['user_info']['auth'] = 1;
                if (($result['admin_enabled'] == 0)) {
                     $output['user_info']['status'] = 'Active';
                }
                else if (($result['enabled'] == 0)) {
                    $output['user_info']['status'] = 'Disabled';
                }
                else if (is_null($result['exp_date']) or $result['exp_date'] > time()) {
                      $output['user_info']['status'] = 'Expired';
                } else {
                    $output['user_info']['status'] = 'Banned';
                }
                $output['user_info']['exp_date'] = $result['exp_date'];
                $output['user_info']['is_trial'] = $result['is_trial'];
                $output['user_info']['active_cons'] = $result['active_cons'];
                $output['user_info']['created_at'] = $result['created_at'];
                $output['user_info']['max_connections'] = $result['max_connections'];
                $output['user_info']['allowed_output_formats'] = array_keys($result['output_formats']);
                $output['categories'] = array();
                foreach ($categories as $id => $category) {
                    $output['categories'][$category['category_type']][] = array('category_id' => $category['id'], 'category_name' => $category['category_name'], 'parent_id' => 0);
                }
                $output['available_channels'] = array();
                $live_num = $movie_num = 0;
                foreach ($result['channels'] as $channel) {
                    $movie_properties = ipTV_lib::movieProperties($channel['id']);
                    if ($channel['live'] == 1) {
                        $live_num++;
                        $stream_icon = $channel['stream_icon'];
                    } else {
                        $movie_num++;
                        $stream_icon = $movie_properties['movie_image'];
                    }
                    $tv_archive_duration = !empty($channel['tv_archive_server_id']) && !empty($channel['tv_archive_duration']) ? 1 : 0;
                    $output['available_channels'][$channel['id']] = array('num' => $channel['live'] == 1 ? $live_num : $movie_num, 'name' => $channel['stream_display_name'], 'stream_type' => $channel['type_key'], 'type_name' => $channel['type_name'], 'stream_id' => $channel['id'], 'stream_icon' => $stream_icon, 'epg_channel_id' => $channel['channel_id'], 'added' => $channel['added'], 'category_name' => $channel['category_name'], 'category_id' => !empty($channel['category_id']) ? $channel['category_id'] : null, 'series_no' => null, 'live' => $channel['live'], 'container_extension' => GetContainerExtension($channel['target_container']), 'custom_sid' => $channel['custom_sid'], 'tv_archive' => $tv_archive_duration, 'direct_source' => !empty($channel['stream_source']) ? json_decode($channel['stream_source'], true)[0] : '', 'tv_archive_duration' => $tv_archive_duration ? $channel['tv_archive_duration'] : 0);
                }
        }
    } else {
        $output['user_info']['auth'] = 0;
    }
    echo json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR);
    die;
}
if ($streaming_block) {
    CheckFlood();
}
?>
