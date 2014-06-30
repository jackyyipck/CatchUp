<?php

include 'catchup-lib.php';

init_db();

header("Content-Type: text/xml; charset=utf-8");

echo '<response>';
$p_user_id = $_REQUEST["user_id"];
$sql = 'SELECT event_name, event_desc, is_allday
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