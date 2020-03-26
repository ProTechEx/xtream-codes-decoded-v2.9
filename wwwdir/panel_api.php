<?php
/*Rev:26.09.18r0*/

require 'init.php';
ini_set('memory_limit', -1);
$f0ac6ad2b40669833242a10c23cad2e0 = true;
if (!empty(ipTV_lib::$request['username']) && !empty(ipTV_lib::$request['password'])) {
    $e4b5e869e986190a8c65320793f9f9c7 = array('get_epg');
    $username = ipTV_lib::$request['username'];
    $password = ipTV_lib::$request['password'];
    $b4af8b82d0e004d138b6f62947d7a1fa = !empty(ipTV_lib::$request['action']) && in_array(ipTV_lib::$request['action'], $e4b5e869e986190a8c65320793f9f9c7) ? ipTV_lib::$request['action'] : '';
    $output = array();
    $output['user_info'] = array();
    if ($result = ipTV_streaming::GetUserInfo(null, $username, $password, true, true, true)) {
        $f0ac6ad2b40669833242a10c23cad2e0 = false;
        switch ($b4af8b82d0e004d138b6f62947d7a1fa) {
            case 'get_epg':
                if (!empty(ipTV_lib::$request['stream_id']) && (is_null($result['exp_date']) or $result['exp_date'] > time())) {
                    $stream_id = intval(ipTV_lib::$request['stream_id']);
                    $ea6531b28219f4f71cfd02aec23a0f33 = !empty(ipTV_lib::$request['from_now']) && ipTV_lib::$request['from_now'] > 0 ? true : false;
                    $Ebcbee307f9edf43bc23a9acc461bcd6 = B66daC37E77d0b4B60e2De1E5e4fa184($stream_id, $ea6531b28219f4f71cfd02aec23a0f33);
                    $index = 0;
                    while ($index < count($Ebcbee307f9edf43bc23a9acc461bcd6)) {
                        if (!isset($Ebcbee307f9edf43bc23a9acc461bcd6[$index]['start'])) {
                            break;
                        }
                        $Ebcbee307f9edf43bc23a9acc461bcd6[$index]['start'] = strtotime($Ebcbee307f9edf43bc23a9acc461bcd6[$index]['start']);
                        $Ebcbee307f9edf43bc23a9acc461bcd6[$index]['end'] = strtotime($Ebcbee307f9edf43bc23a9acc461bcd6[$index]['end']);
                        $index++;
                    }
                    echo json_encode($Ebcbee307f9edf43bc23a9acc461bcd6);
                    die;
                } else {
                    echo json_encode(array());
                    die;
                }
                break;
            default:
                $afdd6246d0a110a7f7c2599f764bb8e9 = B303F4b9BcFa8D2fFc2aE41c5d2AA387();
                $e3539ad64f4d9fc6c2e465986c622369 = empty(ipTV_lib::$StreamingServers[SERVER_ID]['domain_name']) ? ipTV_lib::$StreamingServers[SERVER_ID]['server_ip'] : ipTV_lib::$StreamingServers[SERVER_ID]['domain_name'];
                $output['server_info'] = array('url' => $e3539ad64f4d9fc6c2e465986c622369, 'port' => ipTV_lib::$StreamingServers[SERVER_ID]['http_broadcast_port'], 'https_port' => ipTV_lib::$StreamingServers[SERVER_ID]['https_broadcast_port'], 'server_protocol' => ipTV_lib::$StreamingServers[SERVER_ID]['server_protocol']);
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
                    //Ceacd43cd89a93754b1ff31f6dce16bb:
                    $output['user_info']['status'] = 'Banned';
                    //goto c4bbc94387ce9cde8ad8382af05a8514;
                    //ab19daedd2a81bf9f05fae25a3e25df3:
                    //goto c4bbc94387ce9cde8ad8382af05a8514;
                }
                $output['user_info']['exp_date'] = $result['exp_date'];
                $output['user_info']['is_trial'] = $result['is_trial'];
                $output['user_info']['active_cons'] = $result['active_cons'];
                $output['user_info']['created_at'] = $result['created_at'];
                $output['user_info']['max_connections'] = $result['max_connections'];
                $output['user_info']['allowed_output_formats'] = array_keys($result['output_formats']);
                $output['categories'] = array();
                foreach ($afdd6246d0a110a7f7c2599f764bb8e9 as $b3c28ce8f38cc88b3954fadda9ca6553 => $d623cb8e6629e10f288da34e620b78b9) {
                    $output['categories'][$d623cb8e6629e10f288da34e620b78b9['category_type']][] = array('category_id' => $d623cb8e6629e10f288da34e620b78b9['id'], 'category_name' => $d623cb8e6629e10f288da34e620b78b9['category_name'], 'parent_id' => 0);
                    //E98143a2296a1b5a9fe04dd4c4131787:
                }
                $output['available_channels'] = array();
                $ffbf5ba007ab5c76700047a4ec5b648e = $A53459db49b9c062de3f1777e4c87981 = 0;
                foreach ($result['channels'] as $channel) {
                    $movie_properties = ipTV_lib::caDEb9125B2E81b183688842C5AC3AD7($channel['id']);
                    if ($channel['live'] == 1) {
                        $ffbf5ba007ab5c76700047a4ec5b648e++;
                        $f6cb8ff50fa6609892442191828c234b = $channel['stream_icon'];
                    } else {
                        $A53459db49b9c062de3f1777e4c87981++;
                        $f6cb8ff50fa6609892442191828c234b = $movie_properties['movie_image'];
                    }
                    $B9a8ab6cf4c1498733180431a3d477f5 = !empty($channel['tv_archive_server_id']) && !empty($channel['tv_archive_duration']) ? 1 : 0;
                    $output['available_channels'][$channel['id']] = array('num' => $channel['live'] == 1 ? $ffbf5ba007ab5c76700047a4ec5b648e : $A53459db49b9c062de3f1777e4c87981, 'name' => $channel['stream_display_name'], 'stream_type' => $channel['type_key'], 'type_name' => $channel['type_name'], 'stream_id' => $channel['id'], 'stream_icon' => $f6cb8ff50fa6609892442191828c234b, 'epg_channel_id' => $channel['channel_id'], 'added' => $channel['added'], 'category_name' => $channel['category_name'], 'category_id' => !empty($channel['category_id']) ? $channel['category_id'] : null, 'series_no' => null, 'live' => $channel['live'], 'container_extension' => Dc53AE228DF72D4c140FDa7fd5E7e0BE($channel['target_container']), 'custom_sid' => $channel['custom_sid'], 'tv_archive' => $B9a8ab6cf4c1498733180431a3d477f5, 'direct_source' => !empty($channel['stream_source']) ? json_decode($channel['stream_source'], true)[0] : '', 'tv_archive_duration' => $B9a8ab6cf4c1498733180431a3d477f5 ? $channel['tv_archive_duration'] : 0);
                }
        }
    } else {
        $output['user_info']['auth'] = 0;
    }
    echo json_encode($output, JSON_PARTIAL_OUTPUT_ON_ERROR);
    die;
}
if ($f0ac6ad2b40669833242a10c23cad2e0) {
    CheckFlood();
}
?>
