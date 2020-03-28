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
if ($user_infos = ipTV_streaming::GetUserInfo(null, $username, $password, true, true, false)) {
    $streaming_block = false;
    $live_categories = GetCategories('live');
    $vod_categories = GetCategories('movie');
    $series_categories = GetCategories('series');
    $live_streams = array();
    $vod_streams = array();
    foreach ($user_infos['channels'] as $user_info) {
        if ($user_info['live'] == 0) {
            $vod_streams[] = $user_info;
        } else {
            $live_streams[] = $user_info;
        }
    }
    switch ($type) {
        case 'get_live_categories':
            $xml = new SimpleXMLExtended('<items/>');
            $xml->addchild('playlist_name', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $category = $xml->addchild('category');
            $category->addchild('category_id', 1);
            $category->addchild('category_title', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $value = $xml->addchild('channel');
            $value->addchild('title', base64_encode('All'));
            $value->addchild('description', base64_encode('Live Streams Category [ ALL ]'));
            $value->addchild('category_id', 0);
            $cdata = $value->addchild('playlist_url');
            $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_live_streams&cat_id=0" . $category['id']);
            foreach ($live_categories as $cat_id => $category) {
                if (!ipTV_streaming::CategoriesBouq($cat_id, $user_infos['bouquet'])) {
                    continue;
                }
                $value = $xml->addchild('channel');
                $value->addchild('title', base64_encode($category['category_name']));
                $value->addchild('description', base64_encode('Live Streams Category'));
                $value->addchild('category_id', $category['id']);
                $cdata = $value->addchild('playlist_url');
                $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_live_streams&cat_id=" . $category['id']);
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $xml->asXML();
            break;
        case 'get_vod_categories':
            $xml = new SimpleXMLExtended('<items/>');
            $xml->addchild('playlist_name', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $category = $xml->addchild('category');
            $category->addchild('category_id', 1);
            $category->addchild('category_title', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $value = $xml->addchild('channel');
            $value->addchild('title', base64_encode('All'));
            $value->addchild('description', base64_encode('Movie Streams Category [ ALL ]'));
            $value->addchild('category_id', 0);
            $cdata = $value->addchild('playlist_url');
            $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_vod_streams&cat_id=0" . $category['id']);
            foreach ($vod_categories as $key => $category) {
                if (!ipTV_streaming::CategoriesBouq($key, $user_infos['bouquet'])) {
                    continue;
                }
                $value = $xml->addchild('channel');
                $value->addchild('title', base64_encode($category['category_name']));
                $value->addchild('description', base64_encode('Movie Streams Category'));
                $value->addchild('category_id', $category['id']);
                $cdata = $value->addchild('playlist_url');
                $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_vod_streams&cat_id=" . $category['id']);
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $xml->asXML();
            break;
        case 'get_series_categories':
            $xml = new SimpleXMLExtended('<items/>');
            $xml->addchild('playlist_name', 'SubCategory [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $category = $xml->addchild('category');
            $category->addchild('category_id', 1);
            $category->addchild('category_title', 'SubCategory [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $value = $xml->addchild('channel');
            $value->addchild('title', base64_encode('All'));
            $value->addchild('description', base64_encode('TV Series Category [ ALL ]'));
            $value->addchild('category_id', 0);
            $cdata = $value->addchild('playlist_url');
            $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_series&cat_id=0" . $category['id']);
            foreach ($series_categories as $key => $category) {
                $value = $xml->addchild('channel');
                $value->addchild('title', base64_encode($category['category_name']));
                $value->addchild('description', base64_encode('TV Series Category'));
                $value->addchild('category_id', $category['id']);
                $cdata = $value->addchild('playlist_url');
                $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_series&cat_id=" . $category['id']);
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $xml->asXML();
            break;
        case 'get_series':
            if (isset($cat_id) || is_null($cat_id)) {
                $cat_id = is_null($cat_id) ? 0 : $cat_id;
                $category_name = !empty($series_categories[$cat_id]) ? $series_categories[$cat_id]['category_name'] : 'ALL';
                $xml = new SimpleXMLExtended('<items/>');
                $xml->addchild('playlist_name', "TV Series [ {$category_name} ]");
                $category = $xml->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', "TV Series [ {$category_name} ]");
                $series = ipTV_lib::seriesData();
                foreach ($series as $id => $serie) {
                    if (!in_array($id, $user_infos['series_ids'])) {
                        continue;
                    }
                    if ($cat_id != 0) {
                        if ($cat_id != $serie['category_id']) {
                            continue;
                        }
                    }
                    $value = $xml->addchild('channel');
                    $value->addchild('title', base64_encode($serie['title']));
                    $value->addchild('description', '');
                    $value->addchild('category_id', $id);
                    $cdata = $value->addchild('playlist_url');
                    $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_seasons&series_id=" . $id);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $xml->asXML();
            }
            break;
        case 'get_seasons':
            if (isset($series_id)) {
                $ipTV_db->query('SELECT * FROM `series` WHERE `id` = \'%d\'', $series_id);
                $serie = $ipTV_db->get_row();
                $category_name = $serie['title'];
                $xml = new SimpleXMLExtended('<items/>');
                $xml->addchild('playlist_name', "TV Series [ {$category_name} ]");
                $category = $xml->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', "TV Series [ {$category_name} ]");
                $ipTV_db->query('SELECT * FROM `series_episodes` t1 INNER JOIN `streams` t2 ON t2.id=t1.stream_id WHERE t1.series_id = \'%d\' ORDER BY t1.season_num ASC, t1.sort ASC', $series_id);
                $rows = $ipTV_db->get_rows(true, 'season_num', false);
                foreach (array_keys($rows) as $category_id) {
                    if ($cat_id != 0) {
                        if ($cat_id != $serie['category_id']) {
                            continue;
                        }
                    }
                    $value = $xml->addchild('channel');
                    $value->addchild('title', base64_encode("Season {$category_id}"));
                    $value->addchild('description', '');
                    $value->addchild('category_id', $category_id);
                    $cdata = $value->addchild('playlist_url');
                    $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_series_streams&series_id=" . $series_id . '&season=' . $category_id);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $xml->asXML();
            }
            break;
        case 'get_series_streams':
            if (isset($series_id) && isset($id) && in_array($series_id, $user_infos['series_ids'])) {
                $serie = ipTV_lib::seriesData()[$series_id];
                $xml = new SimpleXMLExtended('<items/>');
                $xml->addchild('playlist_name', "TV Series [ {$serie['title']} Season {$id} ]");
                $category = $xml->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', "TV Series [ {$serie['title']} Season {$id} ]");
                foreach ($serie['series_data'][$id] as $serie) {
                    $value = $xml->addchild('channel');
                    $value->addchild('title', base64_encode('Episode ' . sprintf('%02d', ++$epNumber)));
                    $desc = '';
                    $desc_channel = $value->addchild('desc_image');
                    $desc_channel->addCData($serie['cover']);
                    $value->addchild('description', base64_encode($desc));
                    $value->addchild('category_id', $cat_id);
                    $cdata_url = $value->addchild('stream_url');
                    if (!empty($serie['stream_source'])) {
                        $source = json_decode($serie['stream_source'], true)[0];
                    } else {
                        $source = $url . "series/{$username}/{$password}/{$serie['stream_id']}." . GetContainerExtension($serie['target_container']);
                    }
                    $cdata_url->addCData($source);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $xml->asXML();
            }
            break;
        case 'get_live_streams':
            if (isset($cat_id) || is_null($cat_id)) {
                $cat_id = is_null($cat_id) ? 0 : $cat_id;
                $xml = new SimpleXMLExtended('<items/>');
                $xml->addchild('playlist_name', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                $category = $xml->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                foreach ($live_streams as $user_info) {
                    if ($cat_id != 0) {
                        if ($cat_id != $user_info['category_id']) {
                            continue;
                        }
                    }
                    $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp, UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `channel_id` = \'%s\' AND  `end` >= \'%s\' LIMIT 2', $user_info['channel_id'], date('Y-m-d H:i:00'));
                    $epgData = $ipTV_db->get_rows();
                    $desc = '';
                    $title = '';
                    $index = 0;
                    foreach ($epgData as $row) {
                        $desc .= '[' . date('H:i', $row['start_timestamp']) . '] ' . base64_decode($row['title']) . '( ' . base64_decode($row['description']) . ')';
                        if ($index == 0) {
                            $title = '[' . date('H:i', $row['start_timestamp']) . ' - ' . date('H:i', $row['stop_timestamp']) . '] + ' . round(($row['stop_timestamp'] - time()) / 60, 1) . ' min   ' . base64_decode($row['title']);
                            $index++;
                        }
                    }
                    $value = $xml->addchild('channel');
                    $value->addchild('title', base64_encode($user_info['stream_display_name'] . ' ' . $title));
                    $value->addchild('description', base64_encode($desc));
                    $desc_channel = $value->addchild('desc_image');
                    $desc_channel->addCData($user_info['stream_icon']);
                    $value->addchild('category_id', $cat_id);
                    $cdata = $value->addchild('stream_url');
                    if (!empty($user_info['stream_source'])) {
                        $source = json_decode($user_info['stream_source'], true)[0];
                    } else {
                        $source = $url . "live/{$username}/{$password}/{$user_info['id']}.ts";
                    }
                    $cdata->addCData($source);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $xml->asXML();
            }
            break;
        case 'get_vod_streams':
            if (isset($cat_id) || is_null($cat_id)) {
                $cat_id = is_null($cat_id) ? 0 : $cat_id;
                $xml = new SimpleXMLExtended('<items/>');
                $xml->addchild('playlist_name', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                $category = $xml->addchild('category');
                $category->addchild('category_id', 1);
                $category->addchild('category_title', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                foreach ($vod_streams as $user_info) {
                    if ($cat_id != 0) {
                        if ($cat_id != $user_info['category_id']) {
                            continue;
                        }
                    }
                    $movie_properties = ipTV_lib::movieProperties($user_info['id']);
                    $value = $xml->addchild('channel');
                    $value->addchild('title', base64_encode($user_info['stream_display_name']));
                    $desc = '';
                    if ($movie_properties) {
                        foreach ($movie_properties as $key => $movie_property) {
                            if ($key == 'movie_image') {
                                continue;
                            }
                            $desc .= strtoupper($key) . ': ' . $movie_property . '';
                        }
                    }
                    $desc_channel = $value->addchild('desc_image');
                    $desc_channel->addCData($movie_properties['movie_image']);
                    $value->addchild('description', base64_encode($desc));
                    $value->addchild('category_id', $cat_id);
                    $cdata_url = $value->addchild('stream_url');
                    if (!empty($user_info['stream_source'])) {
                        $source = json_decode($user_info['stream_source'], true)[0];
                    } else {
                        $source = $url . "movie/{$username}/{$password}/{$user_info['id']}." . GetContainerExtension($user_info['target_container']);
                    }
                    $cdata_url->addCData($source);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $xml->asXML();
            }
            break;
        default:
            $xml = new SimpleXMLExtended('<items/>');
            $xml->addchild('playlist_name', ipTV_lib::$settings['bouquet_name']);
            $category = $xml->addchild('category');
            $category->addchild('category_id', 1);
            $category->addchild('category_title', ipTV_lib::$settings['bouquet_name']);
            if (!empty($live_streams)) {
                $value = $xml->addchild('channel');
                $value->addchild('title', base64_encode('Live Streams'));
                $value->addchild('description', base64_encode('Live Streams Category'));
                $value->addchild('category_id', 0);
                $cdata = $value->addchild('playlist_url');
                $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_live_categories");
            }
            if (!empty($vod_streams)) {
                $value = $xml->addchild('channel');
                $value->addchild('title', base64_encode('Vod'));
                $value->addchild('description', base64_encode('Video On Demand Category'));
                $value->addchild('category_id', 1);
                $cdata = $value->addchild('playlist_url');
                $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_vod_categories");
            }
            $value = $xml->addchild('channel');
            $value->addchild('title', base64_encode('TV Series'));
            $value->addchild('description', base64_encode('TV Series Category'));
            $value->addchild('category_id', 2);
            $cdata = $value->addchild('playlist_url');
            $cdata->addCData($url . "enigma2.php?username={$username}&password={$password}&type=get_series_categories");
            header('Content-Type: application/xml; charset=utf-8');
            echo $xml->asXML();
    }
}
if ($streaming_block) {
    http_response_code(401);
    CheckFlood();
}
?>
