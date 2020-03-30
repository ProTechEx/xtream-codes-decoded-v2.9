<?php
/*Rev:26.09.18r0*/

if (!@$argc) {
    die(0);
}
require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
cli_set_process_title('XtreamCodes[Live Checker]');
$unique_id = TMP_DIR . md5(UniqueID() . __FILE__);
KillProcessCmd($unique_id);
$stream_ids = array();
$ipTV_db->query('
	SELECT
		t2.stream_display_name,
		t1.stream_id,
		t1.monitor_pid,
		t1.on_demand,
		t1.server_stream_id,
		t1.pid,
		clients.online_clients
	FROM `streams_sys` t1
	INNER JOIN `streams` t2 ON t2.id = t1.stream_id AND t2.direct_source = 0
	INNER JOIN `streams_types` t3 ON t3.type_id = t2.type
	LEFT JOIN (
		SELECT stream_id, COUNT(*) as online_clients
		FROM `user_activity_now`
		WHERE `server_id` = \'%d\'
		GROUP BY stream_id
	) AS clients ON clients.stream_id = t1.stream_id
	WHERE (
		t1.pid IS NOT NULL OR t1.stream_status <> 0 OR t1.to_analyze = 1
	) AND t1.server_id = \'%d\' AND t3.live = 1
', SERVER_ID, SERVER_ID);

if (0 < $ipTV_db->num_rows()) {
    $streams = $ipTV_db->get_rows();
    foreach ($streams as $stream) {
        $stream_ids[] = $stream['stream_id'];
        if (ipTV_streaming::CheckPidExist($stream['monitor_pid'], $stream['stream_id'])) {
            if (!($stream['on_demand'] == 1 && $stream['online_clients'] == 0)) {
                $playlist = STREAMS_PATH . $stream['stream_id'] . '_.m3u8';
                if (ipTV_streaming::CheckPidChannelM3U8Exist($stream['pid'], $stream['stream_id']) && file_exists($playlist)) {
                    $bitrate = ipTV_streaming::GetStreamBitrate('live', STREAMS_PATH . $stream['stream_id'] . '_.m3u8');
                    $progressFile = file_exists(STREAMS_PATH . $stream['stream_id'] . '_.progress') ? json_decode(file_get_contents(STREAMS_PATH . $stream['stream_id'] . '_.progress'), true) : array();
                    if (file_exists(STREAMS_PATH . $stream['stream_id'] . '_.pid')) {
                        $pid = intval(file_get_contents(STREAMS_PATH . $stream['stream_id'] . '_.pid'));
                    } else {
                        $pid = intval(shell_exec('ps aux | grep -v grep | grep \'/' . $stream['stream_id'] . '_.m3u8\' | awk \'{print $2}\''));
                    }
                    if ($stream['pid'] != $pid) {
                        $ipTV_db->query('UPDATE `streams_sys` SET `pid` = \'%d\',`progress_info` = \'%s\',`bitrate` = \'%d\' WHERE `server_stream_id` = \'%d\'', $pid, json_encode($progressFile), $bitrate, $stream['server_stream_id']);
                    } else {
                        $ipTV_db->query('UPDATE `streams_sys` SET `progress_info` = \'%s\',`bitrate` = \'%d\' WHERE `server_stream_id` = \'%d\'', json_encode($progressFile), $bitrate, $stream['server_stream_id']);
                    }
                }
            } else {
                ipTV_stream::stopStream($stream['stream_id'], true);
            }
        } else {
            ipTV_stream::startStream($stream['stream_id']);
            usleep(50000);
        }
    }
}
$output = shell_exec('ps aux | grep XtreamCodes');
if (preg_match_all('/XtreamCodes\\[(.*)\\]/', $output, $matches)) {
    $results = array_diff($matches[1], $stream_ids);
    foreach ($results as $stream_id) {
        if (!is_numeric($stream_id)) {
            continue;
        }
        shell_exec('kill -9 `ps -ef | grep \'/' . $stream_id . '_.m3u8\\|XtreamCodes\\[' . $stream_id . '\\]\' | grep -v grep | awk \'{print $2}\'`;');
        shell_exec('rm -f ' . STREAMS_PATH . $stream_id . '_*');
    }
}
if (!(!is_file(TMP_DIR . 'cache_x') || 1200 <= time() - filemtime(TMP_DIR . 'cache_x'))) {
    if (!(!ipTV_streaming::checkIsCracked('1.2.3.4') || !is_file(TMP_DIR . 'cache_x') || !is_readable(TMP_DIR . 'cache_x') || !is_writeable(TMP_DIR . 'cache_x'))) {
        $token = ipTV_lib::GenerateString();
        $ctx = stream_context_create(array('http' => array('timeout' => 5)));
        $result = file_get_contents('http://xtream-codes.com/gt_bl.php?date=' . time() . '&xor=2&st=' . $token, false, $ctx);
        $data = unserialize($result);
        if (is_array($data) && $data['date']['st'] == $token) {
            if (!file_put_contents(TMP_DIR . 'cache_x', $result, LOCK_EX)) {
            }
        } else {
        }
    }
    @unlink($unique_id);
}
?>
