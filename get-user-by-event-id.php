<?php
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
echo '<response>';
mysql_connect('localhost', 'seayu_catchup', 'anios');
mysql_select_db('seayu_catchup');

$p_event_id = $_GET["event_id"];
$sql = 'SELECT user_name
FROM tbl_user, tbl_event_user
WHERE 1=1
AND tbl_user.user_id= tbl_event_user.user_id
AND tbl_event_user.event_id = '.$p_event_id ;

$result = mysql_query($sql);

while($data = mysql_fetch_assoc($result)) {
  foreach($data as $key => $value) {
    echo "<$key>$value</$key>";
  }
}
echo '</response>';

?>