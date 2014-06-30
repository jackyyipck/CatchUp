<?php
include 'catchup-lib.php';
init_db();
mysql_set_charset('utf8');
$last_comment_id = isset($_REQUEST["last_comment_id"])?$_REQUEST["last_comment_id"]:0;
$comment_sql = get_unread_by_event_id_sql($_REQUEST["event_id"], $last_comment_id, $_REQUEST["user_id"]);

$comment_query_result = mysql_query($comment_sql);
$comment_unread_num = mysql_num_rows($comment_query_result);

echo $comment_unread_num;

if ($comment_query_result != null) mysql_free_result($comment_query_result);

?>