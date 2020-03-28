<?php
/*Rev:26.09.18r0*/

require './init.php';
class SimpleXMLExtended extends SimpleXMLElement
{
    public function addCData($value)
    {
        $dom = dom_import_simplexml($this);
        $no = $dom->ownerDocument;
        $dom->appendchild($no->createCDATASection($value));
    }
}
$streaming_block = true;
if (!isset(ipTV_lib::$request['username']) || !isset(ipTV_lib::$request['password'])) {
    die('Missing parameters.');
}
$username = ipTV_lib::$request['username'];
$password = ipTV_lib::$request['password'];
$type = !empty(ipTV_lib::$request['type']) ? ipTV_lib::$request['type'] : null;
$cat_id = !empty(ipTV_lib::$request['cat_id']) ? intval(ipTV_lib::$request['cat_id']) : null;
$scat_id = !empty(ipTV_lib::$request['scat_id']) ? intval(ipTV_lib::$request['scat_id']) : null;
$series_id = !empty(ipTV_lib::$request['series_id']) ? intval(ipTV_lib::$request['series_id']) : null;
$id = !empty(ipTV_lib::$request['season']) ? intval(ipTV_lib::$request['season']) : null;
$url = !empty($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/' : ipTV_lib::$StreamingServers[SERVER_ID]['site_url'];
ini_set('memory_limit', -1);
if ($D321370cfdc22e783dd897e5afed673e = ipTV_streaming::GetUserInfo(null, $username, $password, true, true, false)) {
    $streaming_block = false;
    $F93ee1f4357cf2c3676871a1bc44af65 = GetCategories('live');
    $a646f0bc753ffe6eb4d18abe30bbcd66 = GetCategories('movie');
    $f24472413ed27fc2ffc06adda68c0806 = GetCategories('series');
    $aeb2c11d5afc757ad86eb60a666c0eee = array();
    $e109afabe8e0c1e646e3f9ec3cd2a7c9 = array();
    foreach ($D321370cfdc22e783dd897e5afed673e['channels'] as $user_info) {
        if ($user_info['live'] == 0) {
            $e109afabe8e0c1e646e3f9ec3cd2a7c9[] = $user_info;
        } else {
            $aeb2c11d5afc757ad86eb60a666c0eee[] = $user_info;
        }
    }
    switch ($type) {
        case 'get_live_categories':
            $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
            $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
            $category->addchild('category_id', 1);
            $category->addchild('category_title', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
            $value->addchild('title', base64_encode('All'));
            $value->addchild('description', base64_encode('Live Streams Category [ ALL ]'));
            $value->addchild('category_id', 0);
            $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
            $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_live_streams&cat_id=0" . $category['id']);
            foreach ($F93ee1f4357cf2c3676871a1bc44af65 as $b10d12e0226d30efcf0ab5f1cb845a0a => $category) {
                if (!ipTV_streaming::CategoriesBouq($b10d12e0226d30efcf0ab5f1cb845a0a, $D321370cfdc22e783dd897e5afed673e['bouquet'])) {
                    continue;
                }
                $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $value->addchild('title', base64_encode($category['category_name']));
                $value->addchild('description', base64_encode('Live Streams Category'));
                $value->addchild('category_id', $category['id']);
                $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_live_streams&cat_id=" . $category['id']);
                eea37ab9bb3ebd062308df2328f770da:
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            break;
        case 'get_vod_categories':
            $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
            $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
            $category->addchild('category_id', 1);
            $category->addchild('category_title', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
            $value->addchild('title', base64_encode('All'));
            $value->addchild('description', base64_encode('Movie Streams Category [ ALL ]'));
            $value->addchild('category_id', 0);
            $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
            $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_vod_streams&cat_id=0" . $category['id']);
            foreach ($a646f0bc753ffe6eb4d18abe30bbcd66 as $key => $category) {
                if (!ipTV_streaming::CategoriesBouq($key, $D321370cfdc22e783dd897e5afed673e['bouquet'])) {
                    continue;
                }
                $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $value->addchild('title', base64_encode($category['category_name']));
                $value->addchild('description', base64_encode('Movie Streams Category'));
                $value->addchild('category_id', $category['id']);
                $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_vod_streams&cat_id=" . $category['id']);
                cdb972a52ed03d1d49655ffafda3bfba:
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            break;
        case 'get_series_categories':
            $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
            $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'SubCategory [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
            $category->addchild('category_id', 1);
            $category->addchild('category_title', 'SubCategory [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
            $value->addchild('title', base64_encode('All'));
            $value->addchild('description', base64_encode('TV Series Category [ ALL ]'));
            $value->addchild('category_id', 0);
            $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
            $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_series&cat_id=0" . $category['id']);
            foreach ($f24472413ed27fc2ffc06adda68c0806 as $key => $category) {
                $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $value->addchild('title', base64_encode($category['category_name']));
                $value->addchild('description', base64_encode('TV Series Category'));
                $value->addchild('category_id', $category['id']);
                $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_series&cat_id=" . $category['id']);
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            break;
        case 'get_series':
            if (isset($cat_id) || is_null($cat_id)) {
                $cat_id = is_null($cat_id) ? 0 : $cat_id;
                $A27497ef3d4dad3da90c414c89f81615 = !empty($f24472413ed27fc2ffc06adda68c0806[$cat_id]) ? $f24472413ed27fc2ffc06adda68c0806[$cat_id]['category_name'] : 'ALL';
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', "TV Series [ {$A27497ef3d4dad3da90c414c89f81615} ]");
                $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', "TV Series [ {$A27497ef3d4dad3da90c414c89f81615} ]");
                $series = ipTV_lib::seriesData();
                foreach ($series as $id => $A0766c7ec9b7cbc336d730454514b34f) {
                    if (!in_array($id, $D321370cfdc22e783dd897e5afed673e['series_ids'])) {
                        continue;
                    }
                    if ($cat_id != 0) {
                        if ($cat_id != $A0766c7ec9b7cbc336d730454514b34f['category_id']) {
                            continue;
                        }
                    }
                    $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $value->addchild('title', base64_encode($A0766c7ec9b7cbc336d730454514b34f['title']));
                    $value->addchild('description', '');
                    $value->addchild('category_id', $id);
                    $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
                    $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_seasons&series_id=" . $id);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        case 'get_seasons':
            if (isset($series_id)) {
                $ipTV_db->query('SELECT * FROM `series` WHERE `id` = \'%d\'', $series_id);
                $A0766c7ec9b7cbc336d730454514b34f = $ipTV_db->get_row();
                $A27497ef3d4dad3da90c414c89f81615 = $A0766c7ec9b7cbc336d730454514b34f['title'];
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', "TV Series [ {$A27497ef3d4dad3da90c414c89f81615} ]");
                $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', "TV Series [ {$A27497ef3d4dad3da90c414c89f81615} ]");
                $ipTV_db->query('SELECT * FROM `series_episodes` t1 INNER JOIN `streams` t2 ON t2.id=t1.stream_id WHERE t1.series_id = \'%d\' ORDER BY t1.season_num ASC, t1.sort ASC', $series_id);
                $rows = $ipTV_db->get_rows(true, 'season_num', false);
                foreach (array_keys($rows) as $c59070c3eab15fea2abe4546ccf476de) {
                    if ($cat_id != 0) {
                        if ($cat_id != $A0766c7ec9b7cbc336d730454514b34f['category_id']) {
                            continue;
                        }
                    }
                    $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $value->addchild('title', base64_encode("Season {$c59070c3eab15fea2abe4546ccf476de}"));
                    $value->addchild('description', '');
                    $value->addchild('category_id', $c59070c3eab15fea2abe4546ccf476de);
                    $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
                    $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_series_streams&series_id=" . $series_id . '&season=' . $c59070c3eab15fea2abe4546ccf476de);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        case 'get_series_streams':
            if (isset($series_id) && isset($id) && in_array($series_id, $D321370cfdc22e783dd897e5afed673e['series_ids'])) {
                $A0766c7ec9b7cbc336d730454514b34f = ipTV_lib::seriesData()[$series_id];
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', "TV Series [ {$A0766c7ec9b7cbc336d730454514b34f['title']} Season {$id} ]");
                $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', "TV Series [ {$A0766c7ec9b7cbc336d730454514b34f['title']} Season {$id} ]");
                foreach ($A0766c7ec9b7cbc336d730454514b34f['series_data'][$id] as $serie) {
                    $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $value->addchild('title', base64_encode('Episode ' . sprintf('%02d', ++$e831c6d2f20288c01902323cccc3733a)));
                    $d4c3c80b508f5d00d05316e7aa0858de = '';
                    $Ef1890c26c22f7b0ebc5881c7a8f4728 = $value->addchild('desc_image');
                    $Ef1890c26c22f7b0ebc5881c7a8f4728->addCData($A0766c7ec9b7cbc336d730454514b34f['cover']);
                    $value->addchild('description', base64_encode($d4c3c80b508f5d00d05316e7aa0858de));
                    $value->addchild('category_id', $cat_id);
                    $a1ef5a6a798dd2f8725ccec3f544f380 = $value->addchild('stream_url');
                    if (!empty($serie['stream_source'])) {
                        $source = json_decode($serie['stream_source'], true)[0];
                    } else {
                        $source = $url . "series/{$username}/{$password}/{$serie['stream_id']}." . GetContainerExtension($serie['target_container']);
                    }
                    $a1ef5a6a798dd2f8725ccec3f544f380->addCData($source);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        case 'get_live_streams':
            if (isset($cat_id) || is_null($cat_id)) {
                $cat_id = is_null($cat_id) ? 0 : $cat_id;
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                foreach ($aeb2c11d5afc757ad86eb60a666c0eee as $user_info) {
                    if ($cat_id != 0) {
                        if ($cat_id != $user_info['category_id']) {
                            continue;
                        }
                    }
                    $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp, UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `channel_id` = \'%s\' AND  `end` >= \'%s\' LIMIT 2', $user_info['channel_id'], date('Y-m-d H:i:00'));
                    $F8094cb3ced6b4e46ebea7b66bd0e870 = $ipTV_db->get_rows();
                    $d4c3c80b508f5d00d05316e7aa0858de = '';
                    $Ee61f16c515768a2a4ecfa726784a15f = '';
                    $index = 0;
                    foreach ($F8094cb3ced6b4e46ebea7b66bd0e870 as $row) {
                        $d4c3c80b508f5d00d05316e7aa0858de .= '[' . date('H:i', $row['start_timestamp']) . '] ' . base64_decode($row['title']) . '( ' . base64_decode($row['description']) . ')';
                        if ($index == 0) {
                            $Ee61f16c515768a2a4ecfa726784a15f = '[' . date('H:i', $row['start_timestamp']) . ' - ' . date('H:i', $row['stop_timestamp']) . '] + ' . round(($row['stop_timestamp'] - time()) / 60, 1) . ' min   ' . base64_decode($row['title']);
                            $index++;
                        }
                    }
                    $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $value->addchild('title', base64_encode($user_info['stream_display_name'] . ' ' . $Ee61f16c515768a2a4ecfa726784a15f));
                    $value->addchild('description', base64_encode($d4c3c80b508f5d00d05316e7aa0858de));
                    $Ef1890c26c22f7b0ebc5881c7a8f4728 = $value->addchild('desc_image');
                    $Ef1890c26c22f7b0ebc5881c7a8f4728->addCData($user_info['stream_icon']);
                    $value->addchild('category_id', $cat_id);
                    $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('stream_url');
                    if (!empty($user_info['stream_source'])) {
                        $source = json_decode($user_info['stream_source'], true)[0];
                    } else {
                        $source = $url . "live/{$username}/{$password}/{$user_info['id']}.ts";
                    }
                    $acfcb8efbada54f036f7bf632f1038a9->addCData($source);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        case 'get_vod_streams':
            if (isset($cat_id) || is_null($cat_id)) {
                $cat_id = is_null($cat_id) ? 0 : $cat_id;
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                foreach ($e109afabe8e0c1e646e3f9ec3cd2a7c9 as $user_info) {
                    if ($cat_id != 0) {
                        if ($cat_id != $user_info['category_id']) {
                            continue;
                        }
                    }
                    $movie_properties = ipTV_lib::movieProperties($user_info['id']);
                    $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $value->addchild('title', base64_encode($user_info['stream_display_name']));
                    $d4c3c80b508f5d00d05316e7aa0858de = '';
                    if ($movie_properties) {
                        foreach ($movie_properties as $key => $eb98f53b15ea5d816e72b353cc6c3326) {
                            if ($key == 'movie_image') {
                                continue;
                            }
                            $d4c3c80b508f5d00d05316e7aa0858de .= strtoupper($key) . ': ' . $eb98f53b15ea5d816e72b353cc6c3326 . '';
                        }
                    }
                    $Ef1890c26c22f7b0ebc5881c7a8f4728 = $value->addchild('desc_image');
                    $Ef1890c26c22f7b0ebc5881c7a8f4728->addCData($movie_properties['movie_image']);
                    $value->addchild('description', base64_encode($d4c3c80b508f5d00d05316e7aa0858de));
                    $value->addchild('category_id', $cat_id);
                    $a1ef5a6a798dd2f8725ccec3f544f380 = $value->addchild('stream_url');
                    if (!empty($user_info['stream_source'])) {
                        $source = json_decode($user_info['stream_source'], true)[0];
                    } else {
                        $source = $url . "movie/{$username}/{$password}/{$user_info['id']}." . GetContainerExtension($user_info['target_container']);
                    }
                    $a1ef5a6a798dd2f8725ccec3f544f380->addCData($source);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        default:
            $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
            $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', ipTV_lib::$settings['bouquet_name']);
            $category = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
            $category->addchild('category_id', 1);
            $category->addchild('category_title', ipTV_lib::$settings['bouquet_name']);
            if (!empty($aeb2c11d5afc757ad86eb60a666c0eee)) {
                $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $value->addchild('title', base64_encode('Live Streams'));
                $value->addchild('description', base64_encode('Live Streams Category'));
                $value->addchild('category_id', 0);
                $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_live_categories");
            }
            if (!empty($e109afabe8e0c1e646e3f9ec3cd2a7c9)) {
                $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $value->addchild('title', base64_encode('Vod'));
                $value->addchild('description', base64_encode('Video On Demand Category'));
                $value->addchild('category_id', 1);
                $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_vod_categories");
            }
            $value = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
            $value->addchild('title', base64_encode('TV Series'));
            $value->addchild('description', base64_encode('TV Series Category'));
            $value->addchild('category_id', 2);
            $acfcb8efbada54f036f7bf632f1038a9 = $value->addchild('playlist_url');
            $acfcb8efbada54f036f7bf632f1038a9->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_series_categories");
            header('Content-Type: application/xml; charset=utf-8');
            echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
    }
}
if ($streaming_block) {
    http_response_code(401);
    CheckFlood();
}
?>
