<?php
/*Rev:26.09.18r0*/

class ipTV_servers
{
    static function dEfD75fAA43Cb772D0e9A6D9506178B6($serverIDS, $B065e352842444ddce37346f0c648660 = array(), $ffmpeg_path)
    {
        if (!is_array($serverIDS)) {
            $serverIDS = array(intval($serverIDS));
        }
        $B065e352842444ddce37346f0c648660 = array_map('intval', $B065e352842444ddce37346f0c648660);
        $output = array();
        foreach ($serverIDS as $server_id) {
            if (!array_key_exists($server_id, ipTV_lib::$StreamingServers)) {
                continue;
            }
            $response = self::ServerSideRequest($server_id, ipTV_lib::$StreamingServers[$server_id]['api_url_ip'] . '&action=pidsAreRunning', array('program' => $ffmpeg_path, 'pids' => $B065e352842444ddce37346f0c648660));
            if ($response) {
                $output[$server_id] = array_map('trim', json_decode($response, true));
            } else {
                $output[$server_id] = false;
            }
            //daeabfc5cea23f69ac49df5a3b95d48f:
        }
        return $output;
    }
    static function eD79A31441202a0d242a25777F316FaC($createdChannelLocation, $pid, $ffmpeg_path)
    {
        if (is_null($pid) || !is_numeric($pid) || !array_key_exists($createdChannelLocation, ipTV_lib::$StreamingServers)) {
            return false;
        }
        if ($output = self::dEfD75FAA43cB772d0e9a6D9506178B6($createdChannelLocation, array($pid), $ffmpeg_path)) {
            return $output[$createdChannelLocation][$pid];
        }
        return false;
    }
    static function B95e6892fb5B229151AAFF96d4D172e3($serverIDS, $ffmpeg_path)
    {
        $command = 'ps ax | grep \'' . basename($ffmpeg_path) . '\' | awk \'{print $1}\'';
        return self::RunCommandServer($serverIDS, $command);
    }
    public static function RunCommandServer($serverIDS, $cmd, $type = 'array')
    {
        $output = array();
        if (!is_array($serverIDS)) {
            $serverIDS = array(intval($serverIDS));
        }
        if (empty($cmd)) {
            foreach ($serverIDS as $server_id) {
                $output[$server_id] = '';
            }
            return $output;
        }
        foreach ($serverIDS as $server_id) {
            if (!($server_id == SERVER_ID)) {
                if (!array_key_exists($server_id, ipTV_lib::$StreamingServers)) {
                    continue;
                }
                $response = self::ServerSideRequest($server_id, ipTV_lib::$StreamingServers[$server_id]['api_url_ip'] . '&action=runCMD', array('command' => $cmd));
                if ($response) {
                    $result = json_decode($response, true);
                    $output[$server_id] = $type == 'array' ? $result : implode('', $result);
                } else {
                    $output[$server_id] = false;
                }
            } else {
                exec($cmd, $outputCMD);
                $output[$server_id] = $type == 'array' ? $outputCMD : implode('', $outputCMD);
            }
        }
        return $output;
    }
    static function ServerSideRequest($server_id, $URL, $PostData = array())
    {
        if (!ipTV_lib::$StreamingServers[$server_id]['server_online']) {
            return false;
        }
        $output = false;
        $result_index = 1;
		
        while ($result_index <= 2) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0) Gecko/20100101 Firefox/9.0');
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if (!empty($PostData)) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($PostData));
            }
            $output = curl_exec($ch);
            $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_errno($ch);
            @curl_close($ch);
            if ($error != 0 || $response_code != 200) {
                ipTV_lib::SaveLog("[MAIN->LB] Response from Server ID {$server_id} was Invalid ( ERROR: {$error} | Response Code: {$response_code} | Try: {$result_index} )");
            } else {
                break;
            }
            $result_index++;
        }
        return $output;
    }
}
?>
