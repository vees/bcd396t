<?php

require_once("settings.php");

$db=mysql_pconnect(DB_HOST,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME,$db);

if (isset($_POST["status"])) {
	mysql_query(
	"insert into scanner_display values (utc_timestamp(),'" .
	sqlite_escape_string($_REQUEST["status"]) . "')");
}

mysql_query("update scanner_iplist set online=0;");

# Add portion to update IP list here
if (isset($_POST["iponline"])) { 
	$iplist = preg_split('/,/', $_POST["iponline"]);
	foreach ($iplist as $ip){
		mysql_query("insert into scanner_iplist (ipaddr,online,laston) VALUES ('".$ip."', 1, now()) ON DUPLICATE KEY UPDATE online=1, laston=now();");	
	}
}

include "channels.php";

echo join("",$channels);


?>
