<?php
class ipTV_lib
{
    public static $request = array();
    public static $ipTV_db;
    public static $settings = array();
    public static $Bouquets = array();
    public static $StreamingServers = array();
    public static $SegmentsSettings = array();
    public static $blockedUA = array();
    public static $customISP = array();
    public static function init()
    {
        global $_INFO;
        if (!empty($_GET)) {
            self::cleanGlobals($_GET);
        }
        if (!empty($_POST)) {
            self::cleanGlobals($_POST);
        }
        if (!empty($_SESSION)) {
            self::cleanGlobals($_SESSION);
        }
        if (!empty($_COOKIE)) {
            self::cleanGlobals($_COOKIE);
        }
        $input = @self::parseIncomingRecursively($_GET, array());
        self::$request = @self::parseIncomingRecursively($_POST, $input);
        self::$settings = self::GetSettings();
        date_default_timezone_set(self::$settings['default_timezone']);
        self::$StreamingServers = self::GetServers();
        if (FETCH_BOUQUETS) {
            self::$Bouquets = self::GetBouquets();
        }
        self::$blockedUA = self::GetBlockedUserAgents();
        self::$customISP = self::GetIspAddon();
        if (self::$StreamingServers[SERVER_ID]['persistent_connections'] != $_INFO['pconnect']) {
            $_INFO['pconnect'] = self::$StreamingServers[SERVER_ID]['persistent_connections'];
            if (!empty($_INFO) && is_array($_INFO) && !empty($_INFO['db_user'])) {
                file_put_contents(IPTV_PANEL_DIR . 'config', base64_encode(decrypt_config(json_encode($_INFO), CONFIG_CRYPT_KEY)), LOCK_EX);
            }
        }
        self::$SegmentsSettings = self::calculateSegNumbers();
        crontab_refresh();
    }
    public static function GetDateUTCTimestamp($date)
    {
        $utcDefaultTimezone = new DateTime('UTC', new DateTimeZone(date_default_timezone_get()));
        $utcDate = new DateTime('UTC', new DateTimeZone($date));
        return $utcDate->getTimestamp() - $utcDefaultTimezone->getTimestamp();
    }
    public static function calculateSegNumbers()
    {
        $segments_settings = array();
        $segments_settings['seg_time'] = 10;
        $segments_settings['seg_list_size'] = 6;
        return $segments_settings;
    }
    public static function GetIspAddon()
    {
        $file = self::requestFile('customisp_cache');
        if ($file !== false) {
            return $file;
        }
        $output = array();
        self::$ipTV_db->query('SELECT id,isp,blocked FROM `isp_addon`');
        $output = self::$ipTV_db->get_rows();
        return $output;
    }
    public static function GetBlockedUserAgents()
    {
        $file = self::requestFile('uagents_cache');
        if ($file !== false) {
            return $file;
        }
        $output = array();
        self::$ipTV_db->query('SELECT id,exact_match,LOWER(user_agent) as blocked_ua FROM `blocked_user_agents`');
        $output = self::$ipTV_db->get_rows(true, 'id');
        return $output;
    }
    public static function GetBouquets()
    {
        $file = self::requestFile('bouquets_cache');
        if ($file !== false) {
            return $file;
        }
        $output = array();
        self::$ipTV_db->query('SELECT `id`,`bouquet_channels`,`bouquet_series` FROM `bouquets`');
        foreach (self::$ipTV_db->get_rows(true, 'id') as $id => $value) {
            $output[$id]['streams'] = json_decode($value['bouquet_channels'], true);
            $output[$id]['series'] = json_decode($value['bouquet_series'], true);
        }
        return $output;
    }
    public static function GetSettings()
    {
        $file = self::requestFile('settings_cache');
        if ($file !== false) {
            return $file;
        }
        $output = array();
        self::$ipTV_db->query('SELECT * FROM `settings`');
        $rows = self::$ipTV_db->get_row();
        foreach ($rows as $key => $val) {
            $output[$key] = $val;
        }
        $output['allow_countries'] = json_decode($output['allow_countries'], true);
        $output['allowed_stb_types'] = @array_map('strtolower', json_decode($output['allowed_stb_types'], true));
        $output['stalker_lock_images'] = json_decode($output['stalker_lock_images'], true);
        $output['use_https'] = json_decode($output['use_https'], true);
        $output['stalker_container_priority'] = json_decode($output['stalker_container_priority'], true);
        $output['gen_container_priority'] = json_decode($output['gen_container_priority'], true);
        if (array_key_exists('bouquet_name', $output)) {
            $output['bouquet_name'] = str_replace(' ', '_', $output['bouquet_name']);
        }
        $output['api_ips'] = explode(',', $output['api_ips']);
        return $output;
    }
    public static function phpFileCache($file, $data)
    {
        $data = '<?php $output = ' . var_export($data, true) . '; ?>';
        if (!file_exists(TMP_DIR . $file . '.php') || md5_file(TMP_DIR . $file . '.php') != md5($data)) {
            file_put_contents(TMP_DIR . $file . '.php_cache', $data, LOCK_EX);
            rename(TMP_DIR . $file . '.php_cache', TMP_DIR . $file . '.php');
        }
    }
    public static function requestFile($file)
    {
        if (file_exists(TMP_DIR . $file . '.php') && USE_CACHE === true) {
            include TMP_DIR . $file . '.php';
            return $output;
        }
        return false;
    }
    public static function seriesData()
    {
        $output = array();
        if (file_exists(TMP_DIR . 'series_data.php')) {
            include TMP_DIR . 'series_data.php';
        }
        return $output;
    }
    public static function movieProperties($stream_id)
    {
        $movie_properties = array();
        if (file_exists(TMP_DIR . $stream_id . '_cache_properties')) {
            $movie_properties = unserialize(file_get_contents(TMP_DIR . $stream_id . '_cache_properties'));
        }
        return isset($movie_properties) && is_array($movie_properties) ? $movie_properties : array();
    }
    public static function GetServers()
    {
        $file = self::requestFile('servers_cache');
        if ($file !== false) {
            return $file;
        }
        if (empty($_SERVER['REQUEST_SCHEME'])) {
            $_SERVER['REQUEST_SCHEME'] = 'http';
        }
        self::$ipTV_db->query('SELECT * FROM `streaming_servers`');
        $servers = array();
        $server_status = array(1, 3);
        foreach (self::$ipTV_db->get_rows() as $row) {
            if ((!empty($row['vpn_ip']) && inet_pton($row['vpn_ip']) !== false)) {
                $url = $row['vpn_ip'];
            }
            else if (empty($row['domain_name'])) { 
                $url = str_replace(array('http://', '/', 'https://'), '', $row['domain_name']);
            } else {
                $url = $row['server_ip'];
            }
            $server_protocol = is_array(self::$settings['use_https']) && in_array($row['id'], self::$settings['use_https']) ? 'https' : 'http';
            $http_port = $server_protocol == 'http' ? $row['http_broadcast_port'] : $row['https_broadcast_port'];
            $row['server_protocol'] = $server_protocol;
            $row['request_port'] = $http_port;
            $row['api_url'] = $server_protocol . '://' . $url . ':' . $http_port . '/system_api.php?password=' . ipTV_lib::$settings['live_streaming_pass'];
            $row['site_url'] = $server_protocol . '://' . $url . ':' . $http_port . '/';
            $row['rtmp_server'] = 'rtmp://' . $url . ':' . $row['rtmp_port'] . '/live/';
            $row['rtmp_mport_url'] = 'http://127.0.0.1:31210/';
            $row['api_url_ip'] = $server_protocol . '://' . $row['server_ip'] . ':' . $http_port . '/system_api.php?password=' . ipTV_lib::$settings['live_streaming_pass'];
            $row['site_url_ip'] = $server_protocol . '://' . $row['server_ip'] . ':' . $http_port . '/';
            $row['geoip_countries'] = empty($row['geoip_countries']) ? array() : json_decode($row['geoip_countries'], true);
            $row['isp_names'] = empty($row['isp_names']) ? array() : json_decode($row['isp_names'], true);
            $row['server_online'] = in_array($row['status'], $server_status) && time() - $row['last_check_ago'] <= 90 || SERVER_ID == $row['id'] ? true : false;
            unset($row['ssh_password'], $row['watchdog_data'], $row['last_check_ago']);
            $servers[intval($row['id'])] = $row;
        }
        return $servers;
    }
    public static function mc_decrypt($decrypt, $key)
    {
        $decrypt = explode('|', $decrypt . '|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if (strlen($iv) !== mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)) {
            return false;
        }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if ($calcmac !== $mac) {
            return false;
        }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }
    public static function SimpleWebGet($url, $save_cache = false)
    {
        if (file_exists(TMP_DIR . md5($url)) && time() - filemtime(TMP_DIR . md5($url)) <= 300) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $res = curl_exec($ch);
        $http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code != 200) {
            file_put_contents(TMP_DIR . md5($url), 0);
            return false;
        }
        if (file_exists(TMP_DIR . md5($url))) {
            unlink(TMP_DIR . md5($url));
        }
        return trim($res);
    }
    public static function curlMultiRequest($urls, $callback = null, $timeout = 5)
    {
        if (empty($urls)) {
            return array();
        }
        $cmrKeys = array();
        $ch = array();
        $results = array();
        $mh = curl_multi_init();
        foreach ($urls as $key => $val) {
            if (ipTV_lib::$StreamingServers[$key]['server_online']) {
                $ch[$key] = curl_init();
                curl_setopt($ch[$key], CURLOPT_URL, $val['url']);
                curl_setopt($ch[$key], CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch[$key], CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch[$key], CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch[$key], CURLOPT_TIMEOUT, $timeout);
                curl_setopt($ch[$key], CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch[$key], CURLOPT_SSL_VERIFYPEER, 0);
                if ($val['postdata'] != null) {
                    curl_setopt($ch[$key], CURLOPT_POST, true);
                    curl_setopt($ch[$key], CURLOPT_POSTFIELDS, http_build_query($val['postdata']));
                }
                curl_multi_add_handle($mh, $ch[$key]);
            } else {
                $cmrKeys[] = $key;
            }
        }
		
        $running = null;
		
        do {
            $mrc = curl_multi_exec($mh, $running);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
		
        while ($running && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) == -1) {
                usleep(50000);
            }
			do {
                $mrc = curl_multi_exec($mh, $running);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
        foreach ($ch as $key => $val) {
            $results[$key] = curl_multi_getcontent($val);
            if ($callback != null) {
                $results[$key] = call_user_func($callback, $results[$key], true);
            }
            curl_multi_remove_handle($mh, $val);
        }
        foreach ($cmrKeys as $key) {
            $results[$key] = false;
        }
        curl_multi_close($mh);
        return $results;
    }
    public static function cleanGlobals(&$data, $iteration = 0)
    {
        if ($iteration >= 10) {
            return;
        }
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                self::cleanGlobals($data[$k], ++$iteration);
            } else {
                $v = str_replace(chr('0'), '', $v);
                $v = str_replace(' ', '', $v);
                $v = str_replace(' ', '', $v);
                $v = str_replace('../', '&#46;&#46;/', $v);
                $v = str_replace('&#8238;', '', $v);
                $data[$k] = $v;
            }
        }
    }
    public static function parseIncomingRecursively(&$data, $input = array(), $iteration = 0)
    {
        if ($iteration >= 20) {
            return $input;
        }
        if (!is_array($data)) {
            return $input;
        }
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $input[$k] = self::parseIncomingRecursively($data[$k], array(), $iteration + 1);
            } else {
                $k = self::parseCleanKey($k);
                $v = self::parseCleanValue($v);
                $input[$k] = $v;
            }
        }
        return $input;
    }
    public static function parseCleanKey($key)
    {
        if ($key === '') {
            return '';
        }
        $key = htmlspecialchars(urldecode($key));
        $key = str_replace('..', '', $key);
        $key = preg_replace('/\\_\\_(.+?)\\_\\_/', '', $key);
        $key = preg_replace('/^([\\w\\.\\-\\_]+)$/', '$1', $key);
        return $key;
    }
    public static function parseCleanValue($val)
    {
        if ($val == '') {
            return '';
        }
        $val = str_replace('&#032;', ' ', stripslashes($val));
        $val = str_replace(array('', '', ''), '', $val);
        $val = str_replace('<!--', '&#60;&#33;--', $val);
        $val = str_replace('-->', '--&#62;', $val);
        $val = str_ireplace('<script', '&#60;script', $val);
        $val = preg_replace('/&amp;#([0-9]+);/s', '&#\\1;', $val);
        $val = preg_replace('/&#(\\d+?)([^\\d;])/i', '&#\\1;\\2', $val);
        return trim($val);
    }
    public static function SaveLog($msg)
    {
        self::$ipTV_db->query('INSERT INTO `panel_logs` (`log_message`,`date`) VALUES(\'%s\',\'%d\')', $msg, time());
    }
    public static function GenerateString($length = 10)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789qwertyuiopasdfghjklzxcvbnm';
        $str = '';
        $max = strlen($chars) - 1;
        $index = 0;
        while ($index < $length) {
            $str .= $chars[rand(0, $max)];
            $index++;
        }
        return $str;
    }
    public static function array_values_recursive($array)
    {
        if (!is_array($array)) {
            return $array;
        }
        $arrayValues = array();
        foreach ($array as $value) {
            if ((is_scalar($value) or is_resource($value))) { 
                $arrayValues[] = $value;
            }
            else if (is_array($value)) {
                $arrayValues = array_merge($arrayValues, self::array_values_recursive($value));
            }
        }            
        return $arrayValues;
    }
}
?>
