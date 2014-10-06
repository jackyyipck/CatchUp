<?php
include 'catchup-lib.php';
init_db();

echo get_server_status($_SERVER["HTTP_HOST"], 80);

?>