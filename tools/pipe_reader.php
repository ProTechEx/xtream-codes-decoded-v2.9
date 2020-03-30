<?php

set_time_limit(0);
if (!@$argc) {
    die(0);
}
require str_replace('\\', '/', dirname($argv[0])) . '/../wwwdir/init.php';
shell_exec('kill $(ps aux | grep pipe_reader | grep -v grep | grep -v ' . getmypid() . ' | awk \'{print $2}\')');
if (!is_dir(CLOSE_OPEN_CONS_PATH)) {
    mkdir(CLOSE_OPEN_CONS_PATH);
}
if (!false) {
    $cdir = scandir(CLOSE_OPEN_CONS_PATH);
}
?>
