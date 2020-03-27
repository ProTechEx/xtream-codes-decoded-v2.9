<?php

require 'init.php';
header('Content-Type: application/json');
$streaming_block = true;
$remote_addr = $_SERVER['REMOTE_ADDR'];
$user_agent = trim($_SERVER['HTTP_USER_AGENT']);
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
}
header('Access-Control-Allow-Credentials: true');
$offset = empty(ipTV_lib::$request['params']['offset']) ? 0 : abs(intval(ipTV_lib::$request['params']['offset']));
$items_per_page = empty(ipTV_lib::$request['params']['items_per_page']) ? 0 : abs(intval(ipTV_lib::$request['params']['items_per_page']));
if (!empty(ipTV_lib::$request['username']) && !empty(ipTV_lib::$request['password'])) {
    ini_set('memory_limit', -1);
    $valid_actions = array(200 => 'get_vod_categories', 201 => 'get_live_categories', 202 => 'get_live_streams', 203 => 'get_vod_streams', 204 => 'get_series_info', 205 => 'get_short_epg', 206 => 'get_series_categories', 207 => 'get_simple_data_table', 208 => 'get_series', 209 => 'get_vod_info');
    $username = ipTV_lib::$request['username'];
    $password = ipTV_lib::$request['password'];
    $output = array();
    if ($result = ipTV_streaming::GetUserInfo(null, $username, $password, true, true, true, array(), false, '', '', array('offset' => $offset, 'items_per_page' => $items_per_page))) {
        $mobile_apps = ipTV_lib::$settings['mobile_apps'];
        if ($result['is_e2'] == 1) {
            if (!empty(ipTV_lib::$request['token'])) {
                $ipTV_db->query('SELECT * FROM enigma2_devices WHERE `token` = \'%s\' AND `public_ip` = \'%s\' AND `key_auth` = \'%s\' LIMIT 1', ipTV_lib::$request['token'], $remote_addr, $user_agent);
                if ($ipTV_db->num_rows() <= 0) {
                    die;
                }
            } else {
                die;
            }
        }
        $valid_action = false;
        if ($result['admin_enabled'] == 1 && $result['enabled'] == 1 && (is_null($result['exp_date']) or $result['exp_date'] > time())) {
            $streaming_block = false;
            $valid_action = true;
        }
        $action = !empty(ipTV_lib::$request['action']) && (in_array(ipTV_lib::$request['action'], $valid_actions) || array_key_exists(ipTV_lib::$request['action'], $valid_actions)) && $valid_action ? ipTV_lib::$request['action'] : '';
        switch ($action) {
            case 'get_series_info':
            case 204:
                $id = empty(ipTV_lib::$request['series_id']) ? 0 : intval(ipTV_lib::$request['series_id']);
                $series = ipTV_lib::seriesData();
                if (!empty($series[$id]) && in_array($id, $result['series_ids'])) {
                    $output['seasons'] = !empty($series[$id]['seasons']) ? array_values(json_decode($series[$id]['seasons'], true)) : array();
                    $output['info'] = array('name' => $series[$id]['title'], 'cover' => $series[$id]['cover'], 'plot' => $series[$id]['plot'], 'cast' => $series[$id]['cast'], 'director' => $series[$id]['director'], 'genre' => $series[$id]['genre'], 'releaseDate' => $series[$id]['releaseDate'], 'last_modified' => $series[$id]['last_modified'], 'rating' => $series[$id]['rating'], 'rating_5based' => number_format($series[$id]['rating'] * 0.5, 1) + 0, 'backdrop_path' => json_decode($series[$id]['backdrop_path'], true), 'youtube_trailer' => $series[$id]['youtube_trailer'], 'episode_run_time' => $series[$id]['episode_run_time'], 'category_id' => !empty($series[$id]['category_id']) ? $series[$id]['category_id'] : null);
                    foreach ($series[$id]['series_data'] as $id => $series) {
                        $episode_num = 1;
                        foreach ($series as $serie) {
                            $movie_properties = ipTV_lib::movieProperties($serie['stream_id']);
                            $output['episodes'][$id][] = array('id' => $serie['stream_id'], 'episode_num' => $episode_num++, 'title' => $serie['stream_display_name'], 'container_extension' => GetContainerExtension($serie['target_container']), 'info' => $movie_properties, 'custom_sid' => $serie['custom_sid'], 'added' => $serie['added'], 'season' => $id, 'direct_source' => !empty($serie['stream_source']) ? json_decode($serie['stream_source'], true)[0] : '');
                        }
                    }
                }
                break;
            case 'get_series':
            case 208:
                $category_id = empty(ipTV_lib::$request['category_id']) ? 0 : intval(ipTV_lib::$request['category_id']);
                $movie_num = 0;
                if (!empty($result['series_ids'])) {
                    $series = ipTV_lib::seriesData();
                    foreach ($series as $id => $serie) {
                        if (!in_array($id, $result['series_ids'])) {
                            continue;
                        }
                        if (!empty($category_id) && $serie['category_id'] != $category_id) {
                            continue;
                        }
                        $output[] = array('num' => ++$movie_num, 'name' => $serie['title'], 'series_id' => (int) $serie['id'], 'cover' => $serie['cover'], 'plot' => $serie['plot'], 'cast' => $serie['cast'], 'director' => $serie['director'], 'genre' => $serie['genre'], 'releaseDate' => $serie['releaseDate'], 'last_modified' => $serie['last_modified'], 'rating' => $serie['rating'], 'rating_5based' => number_format($serie['rating'] * 0.5, 1) + 0, 'backdrop_path' => json_decode($serie['backdrop_path'], true), 'youtube_trailer' => $serie['youtube_trailer'], 'episode_run_time' => $serie['episode_run_time'], 'category_id' => !empty($serie['category_id']) ? $serie['category_id'] : null);
                    }
                }
                break;
            case 'get_vod_categories':
            case 200:
                $categories = GetCategories('movie');
                foreach ($categories as $category) {
                    if (!ipTV_streaming::CategoriesBouq($category['id'], $result['bouquet'])) {
                        continue;
                    }
                    $output[] = array('category_id' => $category['id'], 'category_name' => $category['category_name'], 'parent_id' => 0);
                }
                break;
            case 'get_series_categories':
            case 206:
                $categories = GetCategories('series');
                foreach ($categories as $category) {
                    $output[] = array('category_id' => $category['id'], 'category_name' => $category['category_name'], 'parent_id' => 0);
                }
                break;
            case 'get_live_categories':
            case 201:
                $categories = GetCategories('live');
                foreach ($categories as $category) {
                    if (!ipTV_streaming::CategoriesBouq($category['id'], $result['bouquet'])) {
                        continue;
                    }
                    $output[] = array('category_id' => $category['id'], 'category_name' => $category['category_name'], 'parent_id' => 0);
                }
                break;
            case 'get_simple_data_table':
            case 207:
                $output['epg_listings'] = array();
                if (!empty(ipTV_lib::$request['stream_id'])) {
                    $ch_id = intval(ipTV_lib::$request['stream_id']);
                    $ipTV_db->query('SELECT `tv_archive_server_id`,`tv_archive_duration`,`channel_id`,`epg_id` FROM `streams` WHERE `id` = \'%d\' AND epg_id IS NOT NULL', $ch_id);
                    if ($ipTV_db->num_rows() > 0) {
                        $stream = $ipTV_db->get_row();
                        $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp,UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `epg_id` = \'%d\' AND `channel_id` = \'%s\' ORDER BY `start` ASC', $stream['epg_id'], $stream['channel_id']);
                        if ($ipTV_db->num_rows() > 0) {
                            foreach ($ipTV_db->get_rows() as $epg_data) {
                                $now_playing = 0;
                                $has_archive = 0;
                                if ($epg_data['start_timestamp'] <= time() && $epg_data['stop_timestamp'] >= time()) {
                                    $now_playing = 1;
                                }
                                if (!empty($stream['tv_archive_duration']) && time() > $epg_data['stop_timestamp'] && strtotime("-{$stream['tv_archive_duration']} days") <= $epg_data['stop_timestamp']) {
                                    $has_archive = 1;
                                }
                                $epg_data['now_playing'] = $now_playing;
                                $epg_data['has_archive'] = $has_archive;
                                $output['epg_listings'][] = $epg_data;
                            }
                        }
                    }
                }
                break;
            case 'get_short_epg':
            case 205:
                $output['epg_listings'] = array();
                if (!empty(ipTV_lib::$request['stream_id'])) {
                    $ch_id = intval(ipTV_lib::$request['stream_id']);
                    $items_per_page = empty(ipTV_lib::$request['limit']) ? 4 : intval(ipTV_lib::$request['limit']);
                    $ipTV_db->query('SELECT `channel_id`,`epg_id` FROM `streams` WHERE `id` = \'%d\' AND epg_id IS NOT NULL', $ch_id);
                    if ($ipTV_db->num_rows() > 0) {
                        $epg_data = $ipTV_db->get_row();
                        $ipTV_db->simple_query("SELECT *,UNIX_TIMESTAMP(start) as start_timestamp, UNIX_TIMESTAMP(end) as stop_timestamp  FROM `epg_data` WHERE `epg_id` = '{$epg_data['epg_id']}' AND `channel_id` = '{$epg_data['channel_id']}' AND ('" . date('Y-m-d H:i:00') . '\' BETWEEN `start` AND `end` OR `start` >= \'' . date('Y-m-d H:i:00') . "') ORDER BY `start` LIMIT {$items_per_page}");
                        if ($ipTV_db->num_rows() > 0) {
                            $output['epg_listings'] = $ipTV_db->get_rows();
                        }
                    }
                }
                break;
            case 'get_live_streams':
            case 202:
                $category_id = empty(ipTV_lib::$request['category_id']) ? 0 : intval(ipTV_lib::$request['category_id']);
                $live_num = 0;
                foreach ($result['channels'] as $channel) {
                    if ($channel['live'] != 1) {
                        continue;
                    }
                    if (!empty($category_id) && $channel['category_id'] != $category_id) {
                        continue;
                    }
                    $stream_icon = $channel['stream_icon'];
                    $tv_archive_duration = !empty($channel['tv_archive_server_id']) && !empty($channel['tv_archive_duration']) ? 1 : 0;
                    $output[] = array('num' => ++$live_num, 'name' => $channel['stream_display_name'], 'stream_type' => $channel['type_key'], 'stream_id' => (int) $channel['id'], 'stream_icon' => $stream_icon, 'epg_channel_id' => $channel['channel_id'], 'added' => $channel['added'], 'category_id' => !empty($channel['category_id']) ? $channel['category_id'] : null, 'custom_sid' => $channel['custom_sid'], 'tv_archive' => $tv_archive_duration, 'direct_source' => !empty($channel['stream_source']) ? json_decode($channel['stream_source'], true)[0] : '', 'tv_archive_duration' => $tv_archive_duration ? $channel['tv_archive_duration'] : 0);
                }
                break;
            case 'get_vod_info':
            case 209:
                $output['info'] = array();
                if (!empty(ipTV_lib::$request['vod_id'])) {
                    $vod_id = intval(ipTV_lib::$request['vod_id']);
                    if (!empty($result['channels'][$vod_id])) {
                        $row = $result['channels'][$vod_id];
                        $output['info'] = ipTV_lib::movieProperties($vod_id);
                        $output['movie_data'] = array('stream_id' => (int) $row['id'], 'name' => $row['stream_display_name'], 'added' => $row['added'], 'category_id' => !empty($row['category_id']) ? $row['category_id'] : null, 'container_extension' => GetContainerExtension($row['target_container']), 'custom_sid' => $row['custom_sid'], 'direct_source' => !empty($row['stream_source']) ? json_decode($row['stream_source'], true)[0] : '');
                    }
                }
                break;
            case 'get_vod_streams':
            case 203:
                $category_id = empty(ipTV_lib::$request['category_id']) ? 0 : intval(ipTV_lib::$request['category_id']);
                $movie_num = 0;
                foreach ($result['channels'] as $channel) {
                    if ($channel['live'] != 0 || $channel['type_key'] != 'movie') {
                        continue;
                    }
                    if (!empty($category_id) && $channel['category_id'] != $category_id) {
                        continue;
                    }
                    $movie_properties = ipTV_lib::movieProperties($channel['id']);
                    $output[] = array('num' => ++$movie_num, 'name' => $channel['stream_display_name'], 'stream_type' => $channel['type_key'], 'stream_id' => (int) $channel['id'], 'stream_icon' => $movie_properties['movie_image'], 'rating' => $movie_properties['rating'], 'rating_5based' => number_format($movie_properties['rating'] * 0.5, 1) + 0, 'added' => $channel['added'], 'category_id' => !empty($channel['category_id']) ? $channel['category_id'] : null, 'container_extension' => GetContainerExtension($channel['target_container']), 'custom_sid' => $channel['custom_sid'], 'direct_source' => !empty($channel['stream_source']) ? json_decode($channel['stream_source'], true)[0] : '');
                }
                break;
            default:
                $output['user_info'] = array();
                $url = empty(ipTV_lib::$StreamingServers[SERVER_ID]['domain_name']) ? ipTV_lib::$StreamingServers[SERVER_ID]['server_ip'] : ipTV_lib::$StreamingServers[SERVER_ID]['domain_name'];
                $output['server_info'] = array('url' => $url, 'port' => ipTV_lib::$StreamingServers[SERVER_ID]['http_broadcast_port'], 'https_port' => ipTV_lib::$StreamingServers[SERVER_ID]['https_broadcast_port'], 'server_protocol' => ipTV_lib::$StreamingServers[SERVER_ID]['server_protocol'], 'rtmp_port' => ipTV_lib::$StreamingServers[SERVER_ID]['rtmp_port'], 'timezone' => ipTV_lib::$settings['default_timezone'], 'timestamp_now' => time(), 'time_now' => date('Y-m-d H:i:s'));
                if ($mobile_apps == 1) {
                    $output['server_info']['process'] = true;
                }
                $output['user_info']['username'] = $result['username'];
                $output['user_info']['password'] = $result['password'];
                $output['user_info']['message'] = ipTV_lib::$settings['message_of_day'];
                $output['user_info']['auth'] = 1;
                if (($result['admin_enabled'] == 0)) {
                    $output['user_info']['status'] = 'Active';
                }
                else if (($result['enabled'] == 0)) {
                    $output['user_info']['status'] = 'Disabled';
                }
                if (is_null($result['exp_date']) or $result['exp_date'] > time()) {
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
        }
    } else {
        $output['user_info']['auth'] = 0;
    }
    die(json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR));
}
if ($streaming_block) {
    CheckFlood();
}
?>
