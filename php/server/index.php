<?php

require_once("settings.php");

$db=mysql_pconnect(DB_HOST,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME,$db);

$channels = array (2,2,2,2,2,2,2,2,2,0);

$result = mysql_query("select quickgroup,count(vote) as qty from (select * from scanner_votes where unix_timestamp(now())-unix_timestamp(posted)<1800) AS a group by quickgroup;", $db);
if (mysql_num_rows($result) != 0)
{
	while ($row=mysql_fetch_array($result))
	{
		$channels[$row["quickgroup"]-1] = 1;
	}
}
else
{
	$channels[6] = 1;
}

echo join("",$channels);

echo "<p></p>";

#echo "1222221220";

#print_r($channels);

if (isset($_POST["status"])) {
	mysql_query(
	"insert into scanner_display values (now(),'" .
	sqlite_escape_string($_REQUEST["status"]) . "')");
}

if (isset($_POST["groupkey"])) {
	echo "Perform groupkey evaluation";
	foreach ($_POST["groupkey"] as $keynum => $keystat)
	{
		if ($keystat>0)
		{
			mysql_query(
			"insert into scanner_votes values (" . $keynum . "," . $keystat . 
			",now(),'" . $_SERVER["REMOTE_ADDR"] . "');", $db);
		}
	}
}

$result = mysql_query(
	"select * from scanner_display order by posted desc limit 1", $db);
if (mysql_num_rows($result)) {
$row = mysql_fetch_array($result);
?>

<P>Current scanner display:</p>

<div style="border: 1px solid black; padding: 2px">
<pre>
<?=$row["statustext"];?>
<?=$row["posted"];?> PDT
</pre>
</div>

<ul>
<li>Select "On" next to a group and click Vote to activate that channel for the next 30 minutes.
<li>Fire dispatch is activated by default only if no other channels have been voted on.
<li>If you want to listen to to Fire Dispatch and another channel, select On next to both channels.
</ul>

<?php
}
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
<select name="groupkey[<?=$groupkey?>]" <?php if (!in_array($groupkey, array(1,2,3,7))) { print "disabled"; } ?>>
  <option value="0">--</option>
  <option value="1">On</option>
  <!--<option value="2">Off</option>-->
</select>
</td></tr>
<?php
}

?>
</table>
<input type="Submit" value="Vote">
</form>

<iframe src="http://www.radioreference.com/assets/remote/player.php?key=23311297&amp;feedId=8266&amp;as=1&amp;stats=1" frameborder="0" width="365px" height="300px"></iframe>
