<?php

function WriteFileCacheProperties()
{
    global $ipTV_db;
    $ipTV_db->query('SELECT id,movie_propeties FROM `streams`');
    foreach ($ipTV_db->get_rows(true, 'id') as $key => $data) {
        if (3 < strlen($data['movie_propeties'])) {
            $movie_propeties = json_decode($data['movie_propeties'], true);
            if (is_array($movie_propeties)) {
                file_put_contents(TMP_DIR . $key . '_cache_properties', serialize($movie_propeties), LOCK_EX);
            }
        }
    }
}
function WriteFileCategoriesBouq($streamsArray)
{
    $categories = array();
    foreach (ipTV_lib::$Bouquets as $key => $item) {
        $categories[$key] = array();
        if (!is_array($item['streams'])) {
            continue;
        }
        foreach ($item['streams'] as $stream_id) {
            if (isset($streamsArray[$stream_id])) {
                if (!in_array($streamsArray[$stream_id]['category_id'], $categories[$key])) {
                    $categories[$key][] = $streamsArray[$stream_id]['category_id'];
                }
            }
        }
    }
    file_put_contents(TMP_DIR . 'categories_bouq', serialize($categories), LOCK_EX);
}
function saveCache()
{
    global $ipTV_db;
    $ipTV_db->query('SELECT t1.*,t2.category_name FROM `series` t1 LEFT JOIN `stream_categories` t2 ON t1.category_id = t2.id');
    $categories = $ipTV_db->get_rows(true, 'id');
    foreach ($categories as $id => $value) {
        $ipTV_db->query('SELECT t1.season_num,t2.added,if(t2.direct_source = 1 AND t2.redirect_stream = 0,t2.stream_source,NULL) as stream_source,t2.custom_sid,t1.stream_id,t2.stream_display_name,t2.target_container FROM `series_episodes` t1 INNER JOIN `streams` t2 ON t2.id=t1.stream_id WHERE t1.series_id = \'%d\' ORDER BY t1.season_num ASC, t1.sort ASC', $id);
        $series_data = $ipTV_db->get_rows(true, 'season_num', false, 'stream_id');
        $categories[$id]['series_data'] = $series_data;
    }
    $item = '<?php $output = ' . var_export($categories, true) . '; ?>';
    $data = TMP_DIR . 'series_data.php';
    if (!file_exists($data) || md5_file($data) != md5($item)) {
        file_put_contents($data . '_tmp', $item, LOCK_EX);
        rename($data . '_tmp', $data);
    }
}
ipTV_lib::phpFileCache('bouquets_cache', ipTV_lib::$Bouquets);
file_put_contents(TMP_DIR . 'new_rewrite', 1);
define('USE_CACHE', false);
ipTV_lib::phpFileCache('uagents_cache', ipTV_lib::$blockedUA);
ipTV_lib::phpFileCache('servers_cache', ipTV_lib::$StreamingServers);
$nginx_data = (int) shell_exec('cat ' . IPTV_PANEL_DIR . 'nginx/conf/nginx.conf | grep -c \'\\/(\\\\d+)\'');
saveCache();
die(0);
KillProcessCmd($unique_id);
do {
    @unlink($unique_id);
    $unique_id = TMP_DIR . md5(UniqueID() . __FILE__);
    ini_set('memory_limit', -1);
    if (@$argc) {
		$ipTV_db->query('SELECT t1.id, 
		   t1.added, 
		   t1.allow_record, 
		   t1.channel_id, 
		   if(t1.direct_source = 1 AND t1.redirect_stream = 0,t1.stream_source,NULL) as stream_source,
		   t1.tv_archive_server_id, 
		   t1.tv_archive_duration, 
		   t1.stream_icon, 
		   t1.custom_sid, 
		   t1.category_id, 
		   t1.stream_display_name, 
		   t2.type_output, 
		   t1.target_container, 
		   t2.live, 
		   t3.category_name, 
		   t1.rtmp_output, 
		   t1.number, 
		   t2.type_key,
		   t2.type_name
		   FROM   `streams` t1 
		   LEFT JOIN `stream_categories` t3 ON t3.id = t1.category_id 
		   INNER JOIN `streams_types` t2 ON t2.type_id = t1.type
		');
        ipTV_lib::phpFileCache('settings_cache', ipTV_lib::$settings);
        $streamsArray = array();
        WriteFileCacheProperties();
        $types = $ipTV_db->get_rows(true, 'type_key', false, 'id');
    } else {
        break;
        require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
        foreach ($types as $type_key => $streams) {
            $streamsArray = array_replace($streamsArray, $streams);
            $stream_array_data = '<?php return ' . var_export($streams, true) . '; ?>';
            $name_type = TMP_DIR . $type_key . '_main.php';
            if (!file_exists($name_type) || md5_file($name_type) != md5($stream_array_data)) {
                file_put_contents($name_type . '_tmp', $stream_array_data, LOCK_EX);
                rename($name_type . '_tmp', $name_type);
            }
        }
        ipTV_lib::phpFileCache('customisp_cache', ipTV_lib::$customISP);
    }
    cli_set_process_title('XtreamCodes[Cache Builder]');
    WriteFileCategoriesBouq($streamsArray);
} while (!($nginx_data == 1));
?>
