<?php
require_once("settings.php");
$db=mysql_pconnect(DB_HOST,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME,$db);

$result = mysql_query(
	"select statustext, convert_tz(posted,'+00:00','-04:00') as posted from scanner_display order by posted desc limit 1", $db);
if (mysql_num_rows($result)) {
$row = mysql_fetch_array($result);
}
?>
<p style="display: inline; font-family: monospace; "><?=str_replace("\n","<br/>",$row["statustext"]);?>
<?=$row["posted"];?> EDT</p>
