<?php
/*Rev:26.09.18r0*/

require './init.php';
class SimpleXMLExtended extends SimpleXMLElement
{
    public function addCData($fca317707effbca84f074c293920f0f7)
    {
        $E21561cba90465c4a869c01eb89269bf = dom_import_simplexml($this);
        $B10ca538b01a4f983cf13350e70508a9 = $E21561cba90465c4a869c01eb89269bf->ownerDocument;
        $E21561cba90465c4a869c01eb89269bf->appendchild($B10ca538b01a4f983cf13350e70508a9->createCDATASection($fca317707effbca84f074c293920f0f7));
    }
}
$f0ac6ad2b40669833242a10c23cad2e0 = true;
if (!isset(ipTV_lib::$request['username']) || !isset(ipTV_lib::$request['password'])) {
    die('Missing parameters.');
}
$username = ipTV_lib::$request['username'];
$password = ipTV_lib::$request['password'];
$type = !empty(ipTV_lib::$request['type']) ? ipTV_lib::$request['type'] : null;
$f409603490270683e24dc87b262cfe7d = !empty(ipTV_lib::$request['cat_id']) ? intval(ipTV_lib::$request['cat_id']) : null;
$D3a052197c7120cda8c89f487458f986 = !empty(ipTV_lib::$request['scat_id']) ? intval(ipTV_lib::$request['scat_id']) : null;
$b3671c2f351ad83d82df47bd43ef8768 = !empty(ipTV_lib::$request['series_id']) ? intval(ipTV_lib::$request['series_id']) : null;
$c0792eb00d656504ed969c0d4d84f7e3 = !empty(ipTV_lib::$request['season']) ? intval(ipTV_lib::$request['season']) : null;
$e3539ad64f4d9fc6c2e465986c622369 = !empty($_SERVER['HTTP_HOST']) ? 'http://' . $_SERVER['HTTP_HOST'] . '/' : ipTV_lib::$StreamingServers[SERVER_ID]['site_url'];
ini_set('memory_limit', -1);
if ($D321370cfdc22e783dd897e5afed673e = ipTV_streaming::GetUserInfo(null, $username, $password, true, true, false)) {
    $f0ac6ad2b40669833242a10c23cad2e0 = false;
    $F93ee1f4357cf2c3676871a1bc44af65 = b303f4b9bcfA8d2FfC2aE41c5d2aA387('live');
    $a646f0bc753ffe6eb4d18abe30bbcd66 = B303F4B9bCFA8D2FfC2ae41C5D2AA387('movie');
    $f24472413ed27fc2ffc06adda68c0806 = B303f4b9BCFa8d2ffc2aE41c5D2aa387('series');
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
            $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
            $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
            $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode('All'));
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('Live Streams Category [ ALL ]'));
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', 0);
            $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
            $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_live_streams&cat_id=0" . $d623cb8e6629e10f288da34e620b78b9['id']);
            foreach ($F93ee1f4357cf2c3676871a1bc44af65 as $b10d12e0226d30efcf0ab5f1cb845a0a => $d623cb8e6629e10f288da34e620b78b9) {
                if (!ipTV_streaming::Bc358DB57d4903Bfddf6652560fae708($b10d12e0226d30efcf0ab5f1cb845a0a, $D321370cfdc22e783dd897e5afed673e['bouquet'])) {
                    continue;
                }
                $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode($d623cb8e6629e10f288da34e620b78b9['category_name']));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('Live Streams Category'));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', $d623cb8e6629e10f288da34e620b78b9['id']);
                $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_live_streams&cat_id=" . $d623cb8e6629e10f288da34e620b78b9['id']);
                eea37ab9bb3ebd062308df2328f770da:
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            break;
        case 'get_vod_categories':
            $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
            $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
            $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
            $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode('All'));
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('Movie Streams Category [ ALL ]'));
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', 0);
            $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
            $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_vod_streams&cat_id=0" . $d623cb8e6629e10f288da34e620b78b9['id']);
            foreach ($a646f0bc753ffe6eb4d18abe30bbcd66 as $B51a7e937f03bda1f1e77ebf1bb5a96e => $d623cb8e6629e10f288da34e620b78b9) {
                if (!ipTV_streaming::bC358dB57d4903BfDdf6652560fae708($B51a7e937f03bda1f1e77ebf1bb5a96e, $D321370cfdc22e783dd897e5afed673e['bouquet'])) {
                    continue;
                }
                $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode($d623cb8e6629e10f288da34e620b78b9['category_name']));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('Movie Streams Category'));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', $d623cb8e6629e10f288da34e620b78b9['id']);
                $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_vod_streams&cat_id=" . $d623cb8e6629e10f288da34e620b78b9['id']);
                cdb972a52ed03d1d49655ffafda3bfba:
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            break;
        case 'get_series_categories':
            $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
            $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'SubCategory [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
            $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
            $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', 'SubCategory [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
            $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode('All'));
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('TV Series Category [ ALL ]'));
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', 0);
            $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
            $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_series&cat_id=0" . $d623cb8e6629e10f288da34e620b78b9['id']);
            foreach ($f24472413ed27fc2ffc06adda68c0806 as $B51a7e937f03bda1f1e77ebf1bb5a96e => $d623cb8e6629e10f288da34e620b78b9) {
                $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode($d623cb8e6629e10f288da34e620b78b9['category_name']));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('TV Series Category'));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', $d623cb8e6629e10f288da34e620b78b9['id']);
                $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_series&cat_id=" . $d623cb8e6629e10f288da34e620b78b9['id']);
            }
            header('Content-Type: application/xml; charset=utf-8');
            echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            break;
        case 'get_series':
            if (isset($f409603490270683e24dc87b262cfe7d) || is_null($f409603490270683e24dc87b262cfe7d)) {
                $f409603490270683e24dc87b262cfe7d = is_null($f409603490270683e24dc87b262cfe7d) ? 0 : $f409603490270683e24dc87b262cfe7d;
                $A27497ef3d4dad3da90c414c89f81615 = !empty($f24472413ed27fc2ffc06adda68c0806[$f409603490270683e24dc87b262cfe7d]) ? $f24472413ed27fc2ffc06adda68c0806[$f409603490270683e24dc87b262cfe7d]['category_name'] : 'ALL';
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', "TV Series [ {$A27497ef3d4dad3da90c414c89f81615} ]");
                $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', "TV Series [ {$A27497ef3d4dad3da90c414c89f81615} ]");
                $deff942ee62f1e5c2c16d11aee464729 = ipTV_lib::DCa7Aa6Db7c4ce371E41571a19bcE930();
                foreach ($deff942ee62f1e5c2c16d11aee464729 as $acb1d10773fb0d1b6ac8cf2c16ecf1b5 => $A0766c7ec9b7cbc336d730454514b34f) {
                    if (!in_array($acb1d10773fb0d1b6ac8cf2c16ecf1b5, $D321370cfdc22e783dd897e5afed673e['series_ids'])) {
                        continue;
                    }
                    if ($f409603490270683e24dc87b262cfe7d != 0) {
                        if ($f409603490270683e24dc87b262cfe7d != $A0766c7ec9b7cbc336d730454514b34f['category_id']) {
                            continue;
                        }
                    }
                    $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode($A0766c7ec9b7cbc336d730454514b34f['title']));
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', '');
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', $acb1d10773fb0d1b6ac8cf2c16ecf1b5);
                    $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
                    $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_seasons&series_id=" . $acb1d10773fb0d1b6ac8cf2c16ecf1b5);
                    //Edc907f4ce7d81ffbc13f1b13c4cf06a:
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        case 'get_seasons':
            if (isset($b3671c2f351ad83d82df47bd43ef8768)) {
                $ipTV_db->query('SELECT * FROM `series` WHERE `id` = \'%d\'', $b3671c2f351ad83d82df47bd43ef8768);
                $A0766c7ec9b7cbc336d730454514b34f = $ipTV_db->get_row();
                $A27497ef3d4dad3da90c414c89f81615 = $A0766c7ec9b7cbc336d730454514b34f['title'];
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', "TV Series [ {$A27497ef3d4dad3da90c414c89f81615} ]");
                $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', "TV Series [ {$A27497ef3d4dad3da90c414c89f81615} ]");
                $ipTV_db->query('SELECT * FROM `series_episodes` t1 INNER JOIN `streams` t2 ON t2.id=t1.stream_id WHERE t1.series_id = \'%d\' ORDER BY t1.season_num ASC, t1.sort ASC', $b3671c2f351ad83d82df47bd43ef8768);
                $Cd4eabf7ecf553f46c17f0bd5a382c46 = $ipTV_db->get_rows(true, 'season_num', false);
                foreach (array_keys($Cd4eabf7ecf553f46c17f0bd5a382c46) as $c59070c3eab15fea2abe4546ccf476de) {
                    if ($f409603490270683e24dc87b262cfe7d != 0) {
                        if ($f409603490270683e24dc87b262cfe7d != $A0766c7ec9b7cbc336d730454514b34f['category_id']) {
                            continue;
                        }
                    }
                    $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode("Season {$c59070c3eab15fea2abe4546ccf476de}"));
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', '');
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', $c59070c3eab15fea2abe4546ccf476de);
                    $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
                    $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_series_streams&series_id=" . $b3671c2f351ad83d82df47bd43ef8768 . '&season=' . $c59070c3eab15fea2abe4546ccf476de);
                    //a811c65c2d3a8c282fa33b70d485f93a:
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        case 'get_series_streams':
            if (isset($b3671c2f351ad83d82df47bd43ef8768) && isset($c0792eb00d656504ed969c0d4d84f7e3) && in_array($b3671c2f351ad83d82df47bd43ef8768, $D321370cfdc22e783dd897e5afed673e['series_ids'])) {
                $A0766c7ec9b7cbc336d730454514b34f = ipTV_lib::DcA7Aa6Db7c4cE371E41571A19BCE930()[$b3671c2f351ad83d82df47bd43ef8768];
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', "TV Series [ {$A0766c7ec9b7cbc336d730454514b34f['title']} Season {$c0792eb00d656504ed969c0d4d84f7e3} ]");
                $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', "TV Series [ {$A0766c7ec9b7cbc336d730454514b34f['title']} Season {$c0792eb00d656504ed969c0d4d84f7e3} ]");
                foreach ($A0766c7ec9b7cbc336d730454514b34f['series_data'][$c0792eb00d656504ed969c0d4d84f7e3] as $a14a8f906639aa7f5509518ff935b8f0) {
                    $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode('Episode ' . sprintf('%02d', ++$e831c6d2f20288c01902323cccc3733a)));
                    $d4c3c80b508f5d00d05316e7aa0858de = '';
                    $Ef1890c26c22f7b0ebc5881c7a8f4728 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('desc_image');
                    $Ef1890c26c22f7b0ebc5881c7a8f4728->addCData($A0766c7ec9b7cbc336d730454514b34f['cover']);
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode($d4c3c80b508f5d00d05316e7aa0858de));
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', $f409603490270683e24dc87b262cfe7d);
                    $a1ef5a6a798dd2f8725ccec3f544f380 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('stream_url');
                    if (!empty($a14a8f906639aa7f5509518ff935b8f0['stream_source'])) {
                        $F3803fa85b38b65447e6d438f8e9176a = json_decode($a14a8f906639aa7f5509518ff935b8f0['stream_source'], true)[0];
                    } else {
                        $F3803fa85b38b65447e6d438f8e9176a = $e3539ad64f4d9fc6c2e465986c622369 . "series/{$username}/{$password}/{$a14a8f906639aa7f5509518ff935b8f0['stream_id']}." . dC53aE228Df72D4C140fda7fD5E7e0BE($a14a8f906639aa7f5509518ff935b8f0['target_container']);
                    }
                    $a1ef5a6a798dd2f8725ccec3f544f380->addCData($F3803fa85b38b65447e6d438f8e9176a);
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        case 'get_live_streams':
            if (isset($f409603490270683e24dc87b262cfe7d) || is_null($f409603490270683e24dc87b262cfe7d)) {
                $f409603490270683e24dc87b262cfe7d = is_null($f409603490270683e24dc87b262cfe7d) ? 0 : $f409603490270683e24dc87b262cfe7d;
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', 'Live [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                foreach ($aeb2c11d5afc757ad86eb60a666c0eee as $user_info) {
                    if ($f409603490270683e24dc87b262cfe7d != 0) {
                        if ($f409603490270683e24dc87b262cfe7d != $user_info['category_id']) {
                            continue;
                        }
                    }
                    $ipTV_db->query('SELECT *,UNIX_TIMESTAMP(start) as start_timestamp, UNIX_TIMESTAMP(end) as stop_timestamp FROM `epg_data` WHERE `channel_id` = \'%s\' AND  `end` >= \'%s\' LIMIT 2', $user_info['channel_id'], date('Y-m-d H:i:00'));
                    $F8094cb3ced6b4e46ebea7b66bd0e870 = $ipTV_db->get_rows();
                    $d4c3c80b508f5d00d05316e7aa0858de = '';
                    $Ee61f16c515768a2a4ecfa726784a15f = '';
                    $index = 0;
                    foreach ($F8094cb3ced6b4e46ebea7b66bd0e870 as $row) {
                        $d4c3c80b508f5d00d05316e7aa0858de .= '[' . date('H:i', $row['start_timestamp']) . '] ' . base64_decode($row['title']) . '
( ' . base64_decode($row['description']) . ')
';
                        if ($index == 0) {
                            $Ee61f16c515768a2a4ecfa726784a15f = '[' . date('H:i', $row['start_timestamp']) . ' - ' . date('H:i', $row['stop_timestamp']) . '] + ' . round(($row['stop_timestamp'] - time()) / 60, 1) . ' min   ' . base64_decode($row['title']);
                            $index++;
                        }
                    }
                    $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode($user_info['stream_display_name'] . ' ' . $Ee61f16c515768a2a4ecfa726784a15f));
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode($d4c3c80b508f5d00d05316e7aa0858de));
                    $Ef1890c26c22f7b0ebc5881c7a8f4728 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('desc_image');
                    $Ef1890c26c22f7b0ebc5881c7a8f4728->addCData($user_info['stream_icon']);
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', $f409603490270683e24dc87b262cfe7d);
                    $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('stream_url');
                    if (!empty($user_info['stream_source'])) {
                        $F3803fa85b38b65447e6d438f8e9176a = json_decode($user_info['stream_source'], true)[0];
                    } else {
                        $F3803fa85b38b65447e6d438f8e9176a = $e3539ad64f4d9fc6c2e465986c622369 . "live/{$username}/{$password}/{$user_info['id']}.ts";
                    }
                    $acfcb8efbada54f036f7bf632f1038a9->addCData($F3803fa85b38b65447e6d438f8e9176a);
                    //fc9a711637d2e647db1f9963f1e93860:
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        case 'get_vod_streams':
            if (isset($f409603490270683e24dc87b262cfe7d) || is_null($f409603490270683e24dc87b262cfe7d)) {
                $f409603490270683e24dc87b262cfe7d = is_null($f409603490270683e24dc87b262cfe7d) ? 0 : $f409603490270683e24dc87b262cfe7d;
                $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
                $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
                $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', 'Movie [ ' . ipTV_lib::$settings['bouquet_name'] . ' ]');
                foreach ($e109afabe8e0c1e646e3f9ec3cd2a7c9 as $user_info) {
                    if ($f409603490270683e24dc87b262cfe7d != 0) {
                        if ($f409603490270683e24dc87b262cfe7d != $user_info['category_id']) {
                            continue;
                        }
                    }
                    $movie_properties = ipTV_lib::cAdEB9125B2e81b183688842C5AC3AD7($user_info['id']);
                    $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode($user_info['stream_display_name']));
                    $d4c3c80b508f5d00d05316e7aa0858de = '';
                    if ($movie_properties) {
                        foreach ($movie_properties as $E7cca48cfca85fc445419a32d7d8f973 => $eb98f53b15ea5d816e72b353cc6c3326) {
                            if ($E7cca48cfca85fc445419a32d7d8f973 == 'movie_image') {
                                continue;
                            }
                            $d4c3c80b508f5d00d05316e7aa0858de .= strtoupper($E7cca48cfca85fc445419a32d7d8f973) . ': ' . $eb98f53b15ea5d816e72b353cc6c3326 . '
';
                            //Ad82bd38f29233cb618b80180dff471f:
                        }
                    }
                    $Ef1890c26c22f7b0ebc5881c7a8f4728 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('desc_image');
                    $Ef1890c26c22f7b0ebc5881c7a8f4728->addCData($movie_properties['movie_image']);
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode($d4c3c80b508f5d00d05316e7aa0858de));
                    $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', $f409603490270683e24dc87b262cfe7d);
                    $a1ef5a6a798dd2f8725ccec3f544f380 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('stream_url');
                    if (!empty($user_info['stream_source'])) {
                        $F3803fa85b38b65447e6d438f8e9176a = json_decode($user_info['stream_source'], true)[0];
                    } else {
                        $F3803fa85b38b65447e6d438f8e9176a = $e3539ad64f4d9fc6c2e465986c622369 . "movie/{$username}/{$password}/{$user_info['id']}." . dc53AE228df72D4C140FdA7Fd5E7e0bE($user_info['target_container']);
                    }
                    $a1ef5a6a798dd2f8725ccec3f544f380->addCData($F3803fa85b38b65447e6d438f8e9176a);
                    //ad1b0d55ff130bfff011d2a9d71d4a15:
                }
                header('Content-Type: application/xml; charset=utf-8');
                echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
            }
            break;
        default:
            $a41f6a5b2ce6655f27b7747349ad1f33 = new SimpleXMLExtended('<items/>');
            $a41f6a5b2ce6655f27b7747349ad1f33->addchild('playlist_name', ipTV_lib::$settings['bouquet_name']);
            $d623cb8e6629e10f288da34e620b78b9 = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('category');
            $d623cb8e6629e10f288da34e620b78b9->addchild('category_id', 1);
            $d623cb8e6629e10f288da34e620b78b9->addchild('category_title', ipTV_lib::$settings['bouquet_name']);
            if (!empty($aeb2c11d5afc757ad86eb60a666c0eee)) {
                $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode('Live Streams'));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('Live Streams Category'));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', 0);
                $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_live_categories");
            }
            if (!empty($e109afabe8e0c1e646e3f9ec3cd2a7c9)) {
                $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode('Vod'));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('Video On Demand Category'));
                $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', 1);
                $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
                $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_vod_categories");
            }
            $B6fc8577128465b7a7ca16798a93f3cd = $a41f6a5b2ce6655f27b7747349ad1f33->addchild('channel');
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('title', base64_encode('TV Series'));
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('description', base64_encode('TV Series Category'));
            $B6fc8577128465b7a7ca16798a93f3cd->addchild('category_id', 2);
            $acfcb8efbada54f036f7bf632f1038a9 = $B6fc8577128465b7a7ca16798a93f3cd->addchild('playlist_url');
            $acfcb8efbada54f036f7bf632f1038a9->addCData($e3539ad64f4d9fc6c2e465986c622369 . "enigma2.php?username={$username}&password={$password}&type=get_series_categories");
            header('Content-Type: application/xml; charset=utf-8');
            echo $a41f6a5b2ce6655f27b7747349ad1f33->asXML();
    }
}
if ($f0ac6ad2b40669833242a10c23cad2e0) {
    http_response_code(401);
    d9f93B7C177e377d0BBFE315eAeaE505();
}
?>
