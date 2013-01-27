<?php
#echo "Sorry, scanner is down for routine fixes. Will return 9pm on Monday 1/14/13, Thanks for your patience.";
#exit();

require_once("settings.php");

$db=mysql_pconnect(DB_HOST,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME,$db);

if (isset($_POST["status"])) {
   mysql_query(
   "insert into scanner_display values (utc_timestamp(),'" .
   sqlite_escape_string($_REQUEST["status"]) . "')");
}

if (isset($_POST["groupkey"])) {
   #echo "Perform groupkey evaluation";
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

include("channels.php");

$result = mysql_query(
	"select statustext,convert_tz(posted,'GMT','-4:00') as posted from scanner_display order by posted desc limit 1", $db);
if (mysql_num_rows($result)) {
$row = mysql_fetch_array($result);

# Source of player:
# http://wpaudioplayer.com/standalone/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Baltimore County Scanner Radio</title>
<meta name="keywords" content="police,fire,scanner,baltimore,county" />
<meta name="description" content="User Programmable scanner radio" />
<meta name="viewport" content="width=device-width" /> 
        <script type="text/javascript" src="audio-player/audio-player.js"></script>  
        <script type="text/javascript">  
            AudioPlayer.setup("https://vees.net/scanner/audio-player/player.swf", {  
                width: 80,
					 autostart: "no", noinfo: "yes", animation: "yes"
            });  
        </script> 
</head>
<body id="home">
<div style="float: left; clear: both; border: 1px solid black; padding: 6px; background: #0FF;">
<p style="display: inline; font-family: monospace; "><?=str_replace("\n","<br/>",$row["statustext"]);?>
<?=$row["posted"];?> EDT</p>
</div>
<div style="clear: both">
<p>
<audio controls="controls" autoplay="autoplay">
	<source src="http://delta.vees.net:8000/baco1" type="audio/mpeg">
	<p id="audioplayer_1"><a href="http://delta.vees.net:8000/baco1.m3u">Play in your favorite MP3 player</a>.</p>  
<script type="text/javascript">  
   AudioPlayer.embed("audioplayer_1", {soundFile: "http://delta.vees.net:8000/baco1"});  
</script>  
</audio>
</p>
</div>
<?php
}
$quickgroups=array(
	1 => "Police: Wilkens, Woodlawn",
	2 => "Police: Franklin, Pikesville",
	3 => "Police: Towson, Parkville",
	4 => "Police: Cockeysville, Essex",
	5 => "Police: White Marsh, North Point",
	6 => "",
	7 => "Fire: Dispatch A/B/C/D",
	8 => "Fire: FID, B3, D3" );

?>
<div style="clear: both;">
<form method="POST">
<table>
<?php
foreach ($quickgroups as $groupkey => $group) {
?>
<tr><td><?=$group?></td>
<td>
<select name="groupkey[<?=$groupkey?>]" <?php if (in_array($groupkey, array())) { print "disabled"; } ?>>
  <option value="0">--</option>
  <option value="1" <?php if ($userselect[$groupkey-1] == 1) { print "SELECTED"; } ?>>On</option> 
  <option value="2" <?php if ($userselect[$groupkey-1] == 2) { print "SELECTED"; } ?>>Off</option>
</select> <?php if ($listeners[$groupkey-1] > 0) print $listeners[$groupkey-1] . " listeners"; ?><?php #if ($channels[$groupkey-1] == 1) { print "ON"; } ?>
</td></tr>
<?php
}

?>
</table>
<input type="Submit" value="Activate">
</form>
</div>
<div>
<ul>
<li>Select "On" or "Off" to select a channel and click "Activate".
<li>Selected channels should begin streaming within a minute.
<li>If other people are also listening, they may also turn on channels that you will hear as well.
<li>Please be courteous to other users and only select one or two channels.
<li><a href="http://eepurl.com/jjZn1">Sign up for email updates here</a>. Request a feature or report a problem to <a href="mailto:rob@vees.net">rob@vees.net</a>.
</ul>
</div>
</body>
</html>

