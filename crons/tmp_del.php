<?php
/*Rev:26.09.18r0*/

if (!@$argc) {
    die(0);
}
require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
cli_set_process_title('XtreamCodes[TMP Cleaner]');
$unique_id = TMP_DIR . md5(UniqueID() . __FILE__);
KillProcessCmd($unique_id);
$A0b0908a4d165ad72360bf4f9917b6bc = array('cloud_ips', 'cache_x', 'new_rewrite', 'series_data.php', 'bouquets_cache.php', 'servers_cache.php', 'settings_cache.php', 'customisp_cache.php', 'uagents_cache.php');
foreach (STREAM_TYPE as $connections) {
    $A0b0908a4d165ad72360bf4f9917b6bc[] = $connections . '_main.php';
}
if ($handle = opendir(TMP_DIR)) {
    while (false !== ($d1af25585916b0062524737f183dfb22 = readdir($handle))) {
        if ($d1af25585916b0062524737f183dfb22 != '.' && $d1af25585916b0062524737f183dfb22 != '..' && is_file(TMP_DIR . $d1af25585916b0062524737f183dfb22) && !in_array($d1af25585916b0062524737f183dfb22, $A0b0908a4d165ad72360bf4f9917b6bc)) {
            if (800 <= time() - filemtime(TMP_DIR . $d1af25585916b0062524737f183dfb22)) {
                unlink(TMP_DIR . $d1af25585916b0062524737f183dfb22);
            }
        }
    }
    closedir($handle);
}
clearstatcache();
?>
