<?php
include 'catchup-lib.php';
init_db();

echo create_feedback($_SESSION["db_conn"], $_POST["user_id"], $_POST["feedback"]);

?>