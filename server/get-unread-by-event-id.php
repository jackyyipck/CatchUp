<?php
include 'catchup-lib.php';
init_db();
mysql_set_charset('utf8');

if (isset($_GET["last_comment_id"]))
{
	$comment_sql = get_comment_by_event_id_sql($_GET["event_id"], $_GET["last_comment_id"]);
}
else
{
	$comment_sql = get_comment_by_event_id_sql($_GET["event_id"], 0);
}

$comment_query_result = mysql_query($comment_sql);
$comment_unread_num = mysql_num_rows($comment_query_result);

echo $comment_unread_num;

if ($comment_query_result != null) mysql_free_result($comment_query_result);

?>