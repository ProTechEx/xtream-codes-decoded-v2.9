<?php

function delete_old_segments($stream_id, $duration)
{
    $total_segments = intval(count(scandir(TV_ARCHIVE . $stream_id . '/')) - 2);
    if ($duration * 24 * 60 < $total_segments) {
        $total = $total_segments - $duration * 24 * 60;
        $files = array_values(array_filter(explode('', shell_exec('ls -tr ' . TV_ARCHIVE . $stream_id . ' | sed -e \'s/\\s\\+/\\n/g\''))));
        $i = 0;
        while ($i < $total) {
            unlink(TV_ARCHIVE . $stream_id . '/' . $files[$i]);
            $i++;
        }
    }
}
if (date('Y-m-d:H-i') != $time_file) {
    fclose($fp);
    $ipTV_db->query('SELECT * FROM `streams` t1 INNER JOIN `streams_sys` t2 ON t1.id = t2.stream_id AND t2.server_id = t1.tv_archive_server_id WHERE t1.`id` = \'%d\' AND t1.`tv_archive_server_id` = \'%d\' AND t1.`tv_archive_duration` > 0', $stream_id, SERVER_ID);
    if (!@$argc) {
        shell_exec('(sleep 10; ' . PHP_BIN . ' ' . __FILE__ . ' ' . $stream_id . ') > /dev/null 2>/dev/null & echo $!');
        if (!ipTV_streaming::ps_running($row['tv_archive_pid'], PHP_BIN)) {
            mkdir(TV_ARCHIVE . $stream_id);
            require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
            if (empty($row['pid'])) {
                do {
                    $time_last_check = time();
                    posix_kill($row['tv_archive_pid'], 9);
                    $file_pointer = fopen(TV_ARCHIVE . $stream_id . '/' . $time_file . '.ts', 'a');
                    $stream_id = intval($argv[1]);
                    $row = $ipTV_db->get_row();
                    $fp = @fopen('http://127.0.0.1:' . ipTV_lib::$StreamingServers[SERVER_ID]['http_broadcast_port'] . '/streaming/admin_live.php?password=' . ipTV_lib::$settings['live_streaming_pass'] . '&stream=' . $stream_id . '&extension=ts', 'r');
                    $ipTV_db->close_mysql();
                    cli_set_process_title('TVArchive[' . $stream_id . ']');
                    $time_file = date('Y-m-d:H-i');
                    do {
                    } while (!(3600 <= time() - $time_last_check));
                    delete_old_segments($stream_id, $row['tv_archive_duration']);
                    die;
                } while (!(0 < $ipTV_db->num_rows()));
                echo '[*] Correct Usage: php ' . __FILE__ . ' <stream_id>
';
                do {
                    fclose($file_pointer);
                    fflush($file_pointer);
                    if ($fp) {
                        posix_kill(getmypid(), 9);
                        do {
                        } while (file_exists(TV_ARCHIVE . $stream_id));
                        break;
                        delete_old_segments($stream_id, $row['tv_archive_duration']);
                        define('FETCH_BOUQUETS', false);
                        die(0);
                        $file_pointer = fopen(TV_ARCHIVE . $stream_id . '/' . $time_file . '.ts', 'a');
                        $time_file = date('Y-m-d:H-i');
                        fwrite($file_pointer, stream_get_line($fp, 4096));
                        do {
                        } while (feof($fp));
                        $ipTV_db->query('UPDATE `streams` SET `tv_archive_pid` = \'%d\' WHERE `id` = \'%d\'', getmypid(), $stream_id);
                        $time_last_check = time();
                    }
                } while (!($argc != 2));
            }
        } else {
            die;
        }
    }
}
?>
