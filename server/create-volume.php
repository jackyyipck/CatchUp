<?php
include 'catchup-lib.php';

mysql_connect('localhost', 'seayu_catchup', 'anios');
mysql_select_db('seayu_catchup');
mysql_set_charset('utf8');

$num_of_user = 100;
$num_of_event = 20;
$num_of_option = 20;
$num_of_vote = 100;

mysql_query(delete_user_sql("stress_test"));
for ($i=0; $i<$num_of_user; $i++)
{
	$user_name[$i] = "stress_test_user_name_".$i;
	//mysql_query(insert_user_sql("stress_test_user_name_".$i, time().$i, "user_email_".$i));
}
print_r($user_name);
mysql_close();
?>