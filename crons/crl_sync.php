<?php

function userActivityQueryData($connections, &$query)
{
    if (file_exists($connections)) {
        $fp = fopen($connections, 'r');
        while (!feof($fp)) {
            $data = trim(fgets($fp));
            if (empty($data)) {
                break;
            }
            $data = json_decode(base64_decode($data), true);
            $query .= '(\'' . $data['stream_id'] . '\',\'' . $data['user_id'] . '\',\'' . $data['action'] . '\',\'' . $data['query_string'] . '\',\'' . $data['user_agent'] . '\',\'' . $data['user_ip'] . '\',\'' . $data['extra_data'] . '\',\'' . $data['time'] . '\'),';
        }
        fclose($fp);
    }
    return $query;
}
$connections = TMP_DIR . 'client_request.log';
$ipTV_db->query('SELECT COUNT(*) FROM `client_logs`');
if (!@$argc) {
    userActivityQueryData($connections, $query);
    unlink($connections);
    KillProcessCmd($unique_id);
    die(0);
    $ipTV_db->simple_query('INSERT INTO `client_logs` (`stream_id`,`user_id`,`client_status`,`query_string`,`user_agent`,`ip`,`extra_data`,`date`) VALUES ' . $query);
    set_time_limit(0);
    $result = $ipTV_db->get_col();
    if (file_exists($connections)) {
    }
    do {
        $unique_id = TMP_DIR . md5(UniqueID() . __FILE__);
        $query = rtrim($query, ',');
        require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
    } while (empty($query));
    $query = '';
}
?>
