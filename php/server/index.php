<?php

echo "1222221220";

$db=sqlite_open('bcd396t.sqlite', 0666, $sqliteerror);
#$query = sqlite_query($db, "SELECT * FROM quicklist");
#$totaltables = sqlite_num_rows($query);
#echo $totaltables;

#print_r($_POST);

if (isset($_POST["status"])) {
	sqlite_query($db,
	"insert into display values (datetime('now'),'" .
	sqlite_escape_string($_REQUEST["status"]) . "')");
}

if (isset($_POST["groupkey"])) {
	echo "Perform groupkey evaluation";
	foreach ($_POST["groupkey"] as $keynum => $keystat)
	{
		if ($keystat>0)
		{
			sqlite_query($db,
			"insert into votes values (" . $keynum . "," . $keystat . 
			",datetime('now'),'" . $_SERVER["REMOTE_ADDR"] . "');");
		}
	}
}

$result = sqlite_query($db,
	"select * from display order by posted desc limit 1");
$row = sqlite_fetch_array($result);
?>
<pre>
<?=$row["statustext"];?>
<?=$row["posted"];?> GMT
</pre>
<?php
$quickgroups=array(
	1 => "Parkville Police",
	2 => "Towson Police",
	3 => "White Marsh Police",
	4 => "Cockeysville Police",
	5 => "All Other Precincts",
	6 => "Police Special",
	7 => "Fire Dispatch",
	8 => "Firegrounds",
	9 => "Fire Special", );

?>
<form method="POST">
<table>
<?php
foreach ($quickgroups as $groupkey => $group) {
?>
<tr><td><?=$group?></td>
<td>
<select name="groupkey[<?=$groupkey?>]">
  <option value="0">--</option>
  <option value="1">On</option>
  <option value="2">Off</option>
</select>
</td></tr>
<?php
}

?>
</table>
<input type="Submit" value="Vote">
</form>
