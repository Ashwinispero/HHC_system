<?php include('config.php');

$event_id = $_GET['event_id'];
$service_professional_id=$_GET['service_professional_id'];
$service_id=$_GET['service_id'];
$date_service=$_GET['date_service'];

$baseline = $_GET['baseline'];
$airway=$_GET['airway'];
$Breathing=$_GET['Breathing'];
$Circulation=$_GET['Circulation'];
$skin_perfusion = $_GET['skin_perfusion'];

$JobClosure_temp=$_GET['JobClosure_temp'];
$JobClosure_TBSL=$_GET['JobClosure_TBSL'];
$JobClosure_Pulse=$_GET['JobClosure_Pulse'];
$JobClosure_SpO2 = $_GET['JobClosure_SpO2'];
$JobClosure_RR=$_GET['JobClosure_RR'];
$JobClosure_GCS=$_GET['JobClosure_GCS'];
$JobClosure_BP_high=$_GET['JobClosure_BP_high'];
$JobClosure_BP_low = $_GET['JobClosure_BP_low'];
$Jobclosure_summery=$_GET['Jobclosure_summery'];

date_default_timezone_set('Asia/Kolkata'); 
$date = date('Y-m-d H:i:s');
$job_closure=mysql_query("insert into sp_job_closure() VALUES('','$event_id','$service_professional_id','$service_id','1','$date_service','0','0','$JobClosure_temp','$JobClosure_TBSL','$JobClosure_Pulse','$JobClosure_SpO2','$JobClosure_RR','$JobClosure_GCS','$JobClosure_BP_high','$JobClosure_BP_low','$skin_perfusion','$airway','$Breathing','$Circulation','$baseline','$Jobclosure_summery','','1','$service_professional_id','$date','$service_professional_id','$date')")or die(mysql_error("error"));

$query=mysql_query("update sp_event_professional set service_closed='Y' where event_id=".$event_id." and  service_id=".$service_id." ")or die(mysql_error());
$query=mysql_query("update sp_events set event_status='4' where event_id=".$event_id."  ")or die(mysql_error());

?>