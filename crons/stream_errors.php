<?php
/*Rev:26.09.18r0*/

/*Rev:21.09.19r0-goto Php7.2.4 L:10.2*/
function cF112d514B37bA6b0078F560C45A8bDB($a388c16cc5d913bb5d307d5ba263a4a8, $F593b8d18883f8072908a6cd56c4c1b4)
{
    foreach ($a388c16cc5d913bb5d307d5ba263a4a8 as $D3c32abd0d3bffc3578aff155e22d728) {
        if (stristr($F593b8d18883f8072908a6cd56c4c1b4, $D3c32abd0d3bffc3578aff155e22d728)) {
            return true;
        }
    }
    return false;
}
do {
    foreach ($A0313ccfdfe24c4c0d6fde7bf7afa9ef as $error) {
        if (empty($error) || CF112D514b37ba6b0078f560c45A8BDB($B8acc4ad0f238617a2c162c2035ce449, $error)) {
            continue;
        }
        $ipTV_db->query('INSERT INTO `stream_logs` (`stream_id`,`server_id`,`date`,`error`) VALUES(\'%d\',\'%d\',\'%d\',\'%s\')', $stream_id, SERVER_ID, time(), $error);
    }
    closedir($fb1d4f6290dabf126bb2eb152b0eb565);
    require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
    if ($fb1d4f6290dabf126bb2eb152b0eb565 = opendir(STREAMS_PATH)) {
        die(0);
        break;
        KillProcessCmd($unique_id);
    }
} while (!($d1af25585916b0062524737f183dfb22 != '.' && $d1af25585916b0062524737f183dfb22 != '..' && is_file(STREAMS_PATH . $d1af25585916b0062524737f183dfb22)));
$A0313ccfdfe24c4c0d6fde7bf7afa9ef = array_values(array_unique(array_map('trim', explode('
', file_get_contents($connections)))));
cli_set_process_title('XtreamCodes[Stream Error Parser]');
list($stream_id, $F1350a5569e4b73d2f9cb26483f2a0c1) = explode('.', $d1af25585916b0062524737f183dfb22);
$B8acc4ad0f238617a2c162c2035ce449 = array('the user-agent option is deprecated', 'Last message repeated', 'deprecated', 'Packets poorly interleaved');
$connections = STREAMS_PATH . $d1af25585916b0062524737f183dfb22;
unlink($connections);
do {
    $unique_id = TMP_DIR . md5(UniqueID() . __FILE__);
    do {
    } while (!(false !== ($d1af25585916b0062524737f183dfb22 = readdir($fb1d4f6290dabf126bb2eb152b0eb565))));
    if ($F1350a5569e4b73d2f9cb26483f2a0c1 == 'errors') {
        break;
        set_time_limit(0);
    }
} while (@$argc);
$ipTV_db->query('DELETE FROM `stream_logs` WHERE `date` <= \'%d\' AND `server_id` = \'%d\'', strtotime('-3 hours'), SERVER_ID);
?>
