<?php 
$event_id =$_REQUEST['event_id'];
$flag=$_REQUEST['flag'];

require_once('inc_classes.php'); 
if($flag==1)
{
	$query=mysql_query("update sp_events set enquiry_status='2' where event_id=".$event_id."  ")or die(mysql_error());
}
if($flag==2)
{
	$query=mysql_query("update sp_events set enquiry_status='3' where event_id=".$event_id."  ")or die(mysql_error());
}
if($flag==3)
{
	$query=mysql_query("update sp_events set enquiry_status='4' where event_id=".$event_id."  ")or die(mysql_error());
}
?>