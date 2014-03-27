<form action="test-admin.php" method="POST">
	<table>
		<tr>
			<td>Action</td>
			<td>
				<select name="action">
					<option value="list_event">List Event</option>
					<option value="tear_down">Tear Down</option>
					<option value="create_mock_event">Create Mock Event</option>
					<option value="create_mock_user">Create Mock User</option>
					<option value="create_mock_vote">Create Mock Vote</option>		
				</select>
				<input type="text" name="param">
			</td>
		</tr>
		<tr>
			<td><a href="test-admin.php">Refresh</a></td>
			<td><input name="submit" type="submit" value="Submit"></td>			
		</tr>
	</table>
</form>

<?php

include 'catchup-lib.php';

if (isset($_POST["submit"])) 
{
	$response = NULL;
	switch ($_POST["action"])
	{
		case "list_event":
			$response = list_event();
			break;
		case "tear_down":
			$response = tear_down();
			break;
		case "create_mock_user":
			$response = create_mock_user($_POST["param"]);
			break;
		case "create_mock_event":
			$response = create_mock_event($_POST["param"]);
			break;
		case "create_mock_vote":
			$response = create_mock_vote();
			break;
	}
	echo "<table>";
	foreach($response as $line)
	{
		if($line[0])
		{
			echo "<tr>";
			echo "<td><font color='green'><b>Success</b></font></td>";			
		}
		else
		{
			echo "<tr><td><font color='red'><b>Fail</b></font></td>";		
		}
		echo "<td>".$line[1]."</td>";
		echo "</tr>";
	}
	echo "</table>";
}


function tear_down()
{
	init_db();
	$tables = Array("tbl_event", "tbl_event_option", "tbl_event_user", "tbl_option", "tbl_option_user", "tbl_user");
	foreach ($tables as $table)
	{	
		$sql = "DELETE FROM ".$table;
		if (mysql_query($sql))
		{
			$response[] = Array(true, $sql);
		}
		else
		{
			$response[] = Array(false, $sql);
		}
	}
	return $response;
}
function create_mock_event($event_count)
{
	init_db();	
	$timestamp = time();
	create_mock_user(4*$event_count);
	$user_id = NULL;
	$user_query_result = mysql_query("SELECT user_id FROM tbl_user");
	while($user_query_row = mysql_fetch_assoc($user_query_result))
	{
		$user_id[] = $user_query_row["user_id"];
	}
	mysql_free_result($user_query_result);
	$user_count = count($user_id);
	
		
	for($i=0; $i<$event_count; $i++)
	{
		$event_name = "Event ".get_random_name()." ".get_random_verb();
		$event_desc = get_random_name()." to ".get_random_name()." the ".get_random_noun();
		$expire_at = date("Y-m-d H:i:s", time() + rand(3,30)*60*60*24 + rand(0,23)*60*60);
		$create_by = $user_id[rand(0,$user_count-1)];
		$option_count = rand(2,8);
		$option_name = NULL;
		$option_desc = NULL;
		for($j=0; $j<$option_count; $j++)
		{
			$rand_option_name = get_random_noun();
			$option_name[] = $rand_option_name;
			$option_desc[] = get_random_adj()." and ".get_random_adj()." ".$rand_option_name;
		}
		/* generate random invitees */				
		$invitee_id = get_random_sub_array($user_id, rand(2,10));
		$invitee_id[] = $create_by;
		$event_id = create_event_detail($_SESSION["db_conn"],
							$event_name,
							$event_desc,
							$expire_at,
							$create_by,
							$option_name,
							$option_desc,
							$invitee_id);
		if($event_id != 0)
		{
			$response[] = Array(true, "<a href='get-all-detail-by-user-id.php?user_id=".$create_by."' target='new'>".$event_name."</a>");
		}
		else
		{
			$response[] = Array(false, $event_name.": ".mysql_error());
			
		}
	}
	create_mock_vote();
	return $response;
}

function create_mock_user($user_count)
{
	init_db();
	$timestamp = time();
	for($i=0; $i<$user_count; $i++)
	{
		$fullname = get_random_name()." ".chr(rand(65,90)).". ".strtoupper(get_random_name());
		$user_id = create_user($_SESSION["db_conn"],
					$fullname,
					md5($fullname).".jpg",
					rand(10,99).$timestamp.rand(10,99),
					strtolower(str_replace(" ","",$fullname))."@catchup.com");
		if($user_id != 0)
		{
			$response[] = Array(true, $fullname);
		}
		else
		{
			$response[] = Array(false, $fullname);
		}
	}		
	return $response;
}
function create_mock_vote()
{
	init_db();
	$event_query_result = mysql_query("SELECT event_id FROM tbl_event");
	while($event_query_row = mysql_fetch_assoc($event_query_result))
	{
		$event_id = $event_query_row["event_id"];
		$option_id = NULL;
		$option_query_result = mysql_query("SELECT option_id FROM tbl_event_option WHERE event_id = ".$event_id);
		while($option_query_row = mysql_fetch_assoc($option_query_result))
		{
			$option_id[] = $option_query_row["option_id"];
		}
		$invitee = NULL;
		$invitee_query_result = mysql_query("SELECT user_id FROM tbl_event_user WHERE event_id = ".$event_id);
		while($invitee_query_row = mysql_fetch_assoc($invitee_query_result))
		{
			$invitee[] = $invitee_query_row["user_id"];
		}
		$voters = get_random_sub_array($invitee, rand(0,count($invitee)-1));
		for($i=0; $i<count($voters); $i++)
		{
			$selected_option = get_random_sub_array($option_id, rand(1,count($option_id)-1));
			for($j=0; $j<count($selected_option); $j++)
			{
				create_vote($_SESSION["db_conn"], $voters[$i], $selected_option[$j]);
			}
		}
		
	}
	$response[] = Array(true, "Mock Vote Created");
	return $response;
}
function list_event()
{
	init_db();
	$event_query_result = mysql_query("SELECT event_name, event_create_by FROM tbl_event");
	while($event_query_row = mysql_fetch_assoc($event_query_result))
	{
		$response[] = Array(true,"<a href='get-all-detail-by-user-id.php?user_id=".$event_query_row["event_create_by"]."' target='new'>".$event_query_row["event_name"]."</a><br>");
	}
	return $response;
}

function get_random_name()
{
	$rand_name_list = Array("Sean","Parry","Darius","Boner","Rosann","Ingenito","Maris","Kells","Hermelinda","Beets","Dale","Rain","Dorotha","Every","Lisandra","Hayne","Trish","Vancuren","Jenna","Pea","Darrell","Maffei","Mindi","Witman","Noma","Nagle","Cleopatra","Colangelo","Yee","Melgarejo","Mildred","Angert","Keva","Klump","Catherina","Halberg","Mitzi","Mcnerney","Donny","Kelleher","Eli","Tavares","Gale","Talley","Dominica","Panek","Mireille","Ong","Melani","Thong","Claudie","Catalfamo","Vernie","Lemelin","Cornell","Wilken","Deana","Meisinger","Randy","Bohanon","Jessia","Sweat","Cecille","Witt","Haywood","Orem","Paola","Click","Gladis","Ranger","Lucy","Mcmaster","Jacquelyne","Patton","Karl","Sheehan","Lilli","Leisy","Tony","Adolph","Edna","Dentler","Lizzie","Mcquinn","Mason","Cammack","Jacquetta","Maglio","Felton","Trice","Merri","Popa","Tanner","Albin","Geneva","Picard","Phebe","Haymaker","Donita","Dorado");
	return $rand_name_list[rand(0,count($rand_name_list)-1)];
}
function get_random_verb()
{
	$rand_verb_list = Array("prefer","sin","coach","separate","pat","tour","judge","touch","delay","switch","order","bake","train","deceive","bury","discover","return","flood","expand","bounce","tire","cycle","itch","mug","breathe","pine","fire","cough","moor","fill","gaze","share","possess","obtain","extend","waste","kiss","reply","wave","ski","push","coil","soak","tease","man","buzz","surprise","harm","wink","complain","hand","borrow","shiver","bare","agree","increase","rule","smash","puncture","impress","force","blot","continue","punch","entertain","suspect","treat","spray","preserve","fry","bore","whisper","tow","please","empty","shade","knot","explode","joke","frame","cry","owe","pick","mine","spot","transport","suit","plant","argue","record","inject","mark","trip","disarm","realise","refuse","confuse","grip","park","imagine");
	return ucfirst($rand_verb_list[rand(0,count($rand_verb_list)-1)]);
}
function get_random_noun()
{
	$rand_noun_list = Array("fog","bead","uncle","instrument","theory","tiger","front","square","wave","cellar","approval","chalk","worm","hope","government","shelf","cows","lunchroom","friend","picture","steel","mitten","milk","finger","pipe","ink","run","hose","road","woman","stick","notebook","women","pen","bubble","letters","whistle","growth","knot","feeling","fish","sign","receipt","birthday","brass","quill","cannon","cream","temper","apparel","throat","control","scissors","ice","pocket","measure","badge","wine","root","tree","button","store","sleet","idea","shade","snail","gun","oven","system","meeting","place","rub","question","poison","blow","yak","iron","field","appliance","hot","space","ladybug","snails","stew","reason","request","shape","debt","zephyr","kick","tendency","kiss","bite","finger","toe","hour","record","flowers","cakes","show");
	return ucfirst($rand_noun_list[rand(0,count($rand_noun_list)-1)]);
}
function get_random_adj()
{
	$rand_adj_list = Array("secret","royal","well-off","spooky","brief","humdrum","few","faint","sore","wild","loving","dynamic","decisive","mellow","aware","kind","determined","changeable","precious","cloudy","scandalous","temporary","soggy","rebel","longing","shaggy","short","old-fashioned","mature","misty","oafish","sassy","automatic","like","cloistered","ossified","adhesive","enchanting","rambunctious","curved","substantial","cluttered","terrific","stiff","prickly","numerous","boring","puny","weary","mixed","volatile","wry","exuberant","spiteful","elfin","second-hand","hurt","colossal","jittery","unique","moldy","infamous","gray","numberless","breezy","peaceful","classy","embarrassed","rural","acrid","aloof","incompetent","mighty","sleepy","stormy","difficult","rotten","sad","unhealthy","earthy","draconian","real","cheap","cute","nappy","scattered","lonely","garrulous","lying","habitual","descriptive","abrasive","disastrous","innate","used","hissing","slim","thundering","guarded","sudden");
	return ucfirst($rand_adj_list[rand(0,count($rand_adj_list)-1)]);
}
function get_random_sub_array($arr, $max_sub_arr_len)
{
	/* generate random invitees */	
	
	$result_arr = NULL;	
	if($max_sub_arr_len !=0)
	{	
		$avg_inc = round(count($arr)/$max_sub_arr_len, 0, PHP_ROUND_HALF_DOWN);
		$selected_ptr = rand(1,$avg_inc);
		for($i=0; $i<$max_sub_arr_len && $selected_ptr < count($arr); $i++)
		{
			$result_arr[] = $arr[$selected_ptr];
			$selected_ptr += rand(1,$avg_inc);
		}
	}
	return $result_arr;
}
?>
