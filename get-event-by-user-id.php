<?php
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
echo '<response>';
mysql_connect('localhost', 'seayu_catchup', 'anios');
mysql_select_db('seayu_catchup');

$p_user_id = $_GET["user_id"];
$sql = 'SELECT event_name, event_desc
FROM tbl_event, tbl_event_user
WHERE 1=1
AND tbl_event.event_id = tbl_event_user.event_id
AND tbl_event_user.user_id = '.$p_user_id;

$result = mysql_query($sql);

while($data = mysql_fetch_assoc($result)) {
  foreach($data as $key => $value) {
    echo "<$key>$value</$key>";
  }
}
echo '</response>';

?>