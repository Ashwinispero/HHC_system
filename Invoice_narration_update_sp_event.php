<?php 
 
require_once 'inc_classes.php';
$Invoice_narration = $_GET['Invoice_narration'];
$PlanEvent_id=$_GET['PlanEvent_id'];
$query=mysql_query("update sp_events set Invoice_narration='$Invoice_narration' where event_id=".$PlanEvent_id." ")or die(mysql_error());

?>