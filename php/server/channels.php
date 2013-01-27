<?php
$channels = array (2,2,2,2,2,2,2,2,2,0);
$userselect = array (0,0,0,0,0,0,0,0,0,0);
$listeners = array (0,0,0,0,0,0,0,0,0);

# select distinct allvotes.quickgroup from scanner_votes allvotes join (select ipaddr,max(posted) lastdt from scanner_votes group by ipaddr) lastvote ON allvotes.ipaddr=lastvote.ipaddr and allvotes.posted=lastvote.lastdt where allvotes.ipaddr in ('65.244.99.5','71.166.69.30') and vote=1 order by quickgroup;

# $_SERVER["REMOTE_ADDR"]
#$result = mysql_query("select distinct allvotes.quickgroup from scanner_votes allvotes join (select ipaddr,max(posted) lastdt from scanner_votes group by ipaddr) lastvote ON allvotes.ipaddr=lastvote.ipaddr and allvotes.posted=lastvote.lastdt where allvotes.ipaddr in ('" . $_SERVER["REMOTE_ADDR"] . "') order by quickgroup", $db);

$result = mysql_query("select allvotes.quickgroup, allvotes.vote from scanner_votes allvotes join (select quickgroup,max(posted) lastpost from scanner_votes where ipaddr = '".$_SERVER["REMOTE_ADDR"]."' group by quickgroup) myvotes on allvotes.posted=myvotes.lastpost and allvotes.ipaddr='".$_SERVER["REMOTE_ADDR"]."' and allvotes.quickgroup=myvotes.quickgroup order by quickgroup;", $db);
if (mysql_num_rows($result) != 0)
{
   while ($row=mysql_fetch_array($result))
   {
      $userselect[$row["quickgroup"]-1] = $row["vote"];
   }
}

#$result = mysql_query("select quickgroup,count(vote) as qty from (select * from scanner_votes where unix_timestamp(now())-unix_timestamp(posted)<3600) AS a group by quickgroup;", $db);
$result = mysql_query("select distinct allvotes.quickgroup,count(allvotes.ipaddr) listener from scanner_votes allvotes join (select ipaddr,max(posted) lastdt from scanner_votes group by ipaddr) lastvote ON allvotes.ipaddr=lastvote.ipaddr and allvotes.vote=1 and allvotes.posted=lastvote.lastdt where allvotes.ipaddr in (select ipaddr from scanner_iplist where online=1) group by quickgroup order by quickgroup;", $db);
if (mysql_num_rows($result) != 0)
{
   while ($row=mysql_fetch_array($result))
   {
      $channels[$row["quickgroup"]-1] = 1;
		$listeners[$row["quickgroup"]-1] = $row["listener"];
   }
}
else
{
   #$channels[6] = 1;
}
?>
