<?php

class E3223A8ad822526d8F69418863b6E8B5
{
    public $validEpg = false;
    public $epgSource;
    public $from_cache = false;
    function __construct($result, $F7b03a1f7467c01c6ea18452d9a5202f = false)
    {
        $this->eCe97C9Fe9A866e5B522e80E43B30997($result, $F7b03a1f7467c01c6ea18452d9a5202f);
    }
    public function a53d17AB9BD15890715e7947C1766953()
    {
        $output = array();
        foreach ($this->epgSource->channel as $item) {
            $e818ebc908da0ee69f4f99daba6a1a18 = trim((string) $item->attributes()->id);
            $cfd246a8499e5bb4a9d89e37c524322a = !empty($item->{'display-name'}) ? trim((string) $item->{'display-name'}) : '';
            if (array_key_exists($e818ebc908da0ee69f4f99daba6a1a18, $output)) {
                continue;
            }
            $output[$e818ebc908da0ee69f4f99daba6a1a18] = array();
            $output[$e818ebc908da0ee69f4f99daba6a1a18]['display_name'] = $cfd246a8499e5bb4a9d89e37c524322a;
            $output[$e818ebc908da0ee69f4f99daba6a1a18]['langs'] = array();
        }
        foreach ($this->epgSource->programme as $item) {
            $e818ebc908da0ee69f4f99daba6a1a18 = trim((string) $item->attributes()->channel);
            if (!array_key_exists($e818ebc908da0ee69f4f99daba6a1a18, $output)) {
                continue;
            }
            $b798ef834bcdc73cfeb4e4e0309db68d = $item->title;
            foreach ($b798ef834bcdc73cfeb4e4e0309db68d as $E4416ae8f96620daee43ac43f9515200) {
                $lang = (string) $E4416ae8f96620daee43ac43f9515200->attributes()->lang;
                if (!in_array($lang, $output[$e818ebc908da0ee69f4f99daba6a1a18]['langs'])) {
                    $output[$e818ebc908da0ee69f4f99daba6a1a18]['langs'][] = $lang;
                }
            }
        }
        return $output;
    }
    public function a0b90401c3241088846A84F33c2B50fF($E2b08d0d6a74fb4e054587ee7c572a9f, $dfc6b62ce4c2bd11aeb45ae2e9441819)
    {
        global $ipTV_db;
        $f8f0da104ec866e0d96947b27214d28a = array();
        foreach ($this->epgSource->programme as $item) {
            $e818ebc908da0ee69f4f99daba6a1a18 = (string) $item->attributes()->channel;
            if (!array_key_exists($e818ebc908da0ee69f4f99daba6a1a18, $dfc6b62ce4c2bd11aeb45ae2e9441819)) {
                continue;
            }
            $ff153ef1378baba89ae1f33db3ad14bf = $Fe7c1055293ad23ed4b69b91fd845cac = '';
            $start = strtotime(strval($item->attributes()->start));
            $stop = strtotime(strval($item->attributes()->stop));
            if (empty($item->title)) {
                continue;
            }
            $b798ef834bcdc73cfeb4e4e0309db68d = $item->title;
            if (is_object($b798ef834bcdc73cfeb4e4e0309db68d)) {
                $A2b796e1bb70296d4bed8ce34ce5691b = false;
                foreach ($b798ef834bcdc73cfeb4e4e0309db68d as $E4416ae8f96620daee43ac43f9515200) {
                    if ($E4416ae8f96620daee43ac43f9515200->attributes()->lang == $dfc6b62ce4c2bd11aeb45ae2e9441819[$e818ebc908da0ee69f4f99daba6a1a18]['epg_lang']) {
                        $A2b796e1bb70296d4bed8ce34ce5691b = true;
                        $ff153ef1378baba89ae1f33db3ad14bf = base64_encode($E4416ae8f96620daee43ac43f9515200);
                        break;
                    }
                }
                if (!$A2b796e1bb70296d4bed8ce34ce5691b) {
                    $ff153ef1378baba89ae1f33db3ad14bf = base64_encode($b798ef834bcdc73cfeb4e4e0309db68d[0]);
                }
            } else {
                $ff153ef1378baba89ae1f33db3ad14bf = base64_encode($b798ef834bcdc73cfeb4e4e0309db68d);
            }
            if (!empty($item->desc)) {
                $d1294148eb5638fe195478093cd6b93b = $item->desc;
                if (is_object($d1294148eb5638fe195478093cd6b93b)) {
                    $A2b796e1bb70296d4bed8ce34ce5691b = false;
                    foreach ($d1294148eb5638fe195478093cd6b93b as $d4c3c80b508f5d00d05316e7aa0858de) {
                        if ($d4c3c80b508f5d00d05316e7aa0858de->attributes()->lang == $dfc6b62ce4c2bd11aeb45ae2e9441819[$e818ebc908da0ee69f4f99daba6a1a18]['epg_lang']) {
                            $A2b796e1bb70296d4bed8ce34ce5691b = true;
                            $Fe7c1055293ad23ed4b69b91fd845cac = base64_encode($d4c3c80b508f5d00d05316e7aa0858de);
                            break;
                        }
                    }
                    if (!$A2b796e1bb70296d4bed8ce34ce5691b) {
                        $Fe7c1055293ad23ed4b69b91fd845cac = base64_encode($d1294148eb5638fe195478093cd6b93b[0]);
                    }
                } else {
                    $Fe7c1055293ad23ed4b69b91fd845cac = base64_encode($item->desc);
                }
            }
            $e818ebc908da0ee69f4f99daba6a1a18 = addslashes($e818ebc908da0ee69f4f99daba6a1a18);
            $dfc6b62ce4c2bd11aeb45ae2e9441819[$e818ebc908da0ee69f4f99daba6a1a18]['epg_lang'] = addslashes($dfc6b62ce4c2bd11aeb45ae2e9441819[$e818ebc908da0ee69f4f99daba6a1a18]['epg_lang']);
            $A73d5129dfb465fd94f3e09e9b179de0 = date('Y-m-d H:i:s', $start);
            $cdd6af41b10abec2ff03fe043f3df1cf = date('Y-m-d H:i:s', $stop);
            $f8f0da104ec866e0d96947b27214d28a[] = '(\'' . $ipTV_db->escape($E2b08d0d6a74fb4e054587ee7c572a9f) . '\', \'' . $ipTV_db->escape($e818ebc908da0ee69f4f99daba6a1a18) . '\', \'' . $ipTV_db->escape($A73d5129dfb465fd94f3e09e9b179de0) . '\', \'' . $ipTV_db->escape($cdd6af41b10abec2ff03fe043f3df1cf) . '\', \'' . $ipTV_db->escape($dfc6b62ce4c2bd11aeb45ae2e9441819[$e818ebc908da0ee69f4f99daba6a1a18]['epg_lang']) . '\', \'' . $ipTV_db->escape($ff153ef1378baba89ae1f33db3ad14bf) . '\', \'' . $ipTV_db->escape($Fe7c1055293ad23ed4b69b91fd845cac) . '\')';
        }
        return !empty($f8f0da104ec866e0d96947b27214d28a) ? $f8f0da104ec866e0d96947b27214d28a : false;
    }
    public function ece97c9FE9a866e5B522E80e43b30997($result, $F7b03a1f7467c01c6ea18452d9a5202f)
    {
        $errors = pathinfo($result, PATHINFO_EXTENSION);
        if (($errors == 'gz')) {
            $d31de515789f8101b06d8ca646ef5e24 = file_get_contents($result);
            $a41f6a5b2ce6655f27b7747349ad1f33 = simplexml_load_string($d31de515789f8101b06d8ca646ef5e24, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
            $d31de515789f8101b06d8ca646ef5e24 = gzdecode(file_get_contents($result));
            $a41f6a5b2ce6655f27b7747349ad1f33 = simplexml_load_string($d31de515789f8101b06d8ca646ef5e24, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
        }
        else if ($errors == 'xz') {
            $d31de515789f8101b06d8ca646ef5e24 = shell_exec("wget -qO- \"{$result}\" | unxz -c");
            $a41f6a5b2ce6655f27b7747349ad1f33 = simplexml_load_string($d31de515789f8101b06d8ca646ef5e24, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
        } 
        if ($a41f6a5b2ce6655f27b7747349ad1f33 !== false) {
            $this->epgSource = $a41f6a5b2ce6655f27b7747349ad1f33;
            if (empty($this->epgSource->programme)) {
                ipTV_lib::E501281ad19aF8A4BBbf9BED91Ee9299('Not A Valid EPG Source Specified or EPG Crashed: ' . $result);
            } else {
                $this->validEpg = true;
            }
        } else {
            ipTV_lib::e501281AD19AF8a4BBbF9BED91EE9299('No XML Found At: ' . $result);
        }
        $a41f6a5b2ce6655f27b7747349ad1f33 = $d31de515789f8101b06d8ca646ef5e24 = null; 
    }
}
?>
