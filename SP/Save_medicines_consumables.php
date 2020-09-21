<?php include('config.php');

$event_id = $_GET['event_id'];
$service_professional_id=$_GET['service_professional_id'];
$service_id=$_GET['service_id'];
$date_service=$_GET['date_service'];
$flag=$_GET['flag'];
date_default_timezone_set('Asia/Kolkata'); 
$date = date('Y-m-d H:i:s');
if($flag=='1')
{
	$Medicine_unit_textbox=$_GET['Medicine_unit_textbox'];
	$Medicine_unit=$_GET['Medicine_unit'];
	$event_detail=mysql_query("SELECT * FROM sp_job_closure where event_id='$event_id' and professional_vender_id='$service_professional_id' ") or die(mysql_error());
	$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
	$job_closure_id=$event_detail['job_closure_id'];
	$job_closure_medicine=mysql_query("insert into sp_job_closure_consumption_mapping() VALUES('','$job_closure_id','$flag','$Medicine_unit','$Medicine_unit_textbox','1','$service_professional_id','$date','$service_professional_id','$date')")or die(mysql_error("error"));
}
if($flag=='2')
{
	$Medicine_Non_unit=$_GET['Medicine_Non_unit'];
	$Medicine_Non_unit_textbox=$_GET['Medicine_Non_unit_textbox'];
	$event_detail=mysql_query("SELECT * FROM sp_job_closure where event_id='$event_id' and professional_vender_id='$service_professional_id' ") or die(mysql_error());
	$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
	$job_closure_id=$event_detail['job_closure_id'];
	$job_closure_medicine=mysql_query("insert into sp_job_closure_consumption_mapping() VALUES('','$job_closure_id','$flag','$Medicine_Non_unit','$Medicine_Non_unit_textbox','1','$service_professional_id','$date','$service_professional_id','$date')")or die(mysql_error("error"));
}
if($flag=='3')
{
	$consumables_unit=$_GET['consumables_unit'];
	$consumables_unit_textbox=$_GET['consumables_unit_textbox'];
	$event_detail=mysql_query("SELECT * FROM sp_job_closure where event_id='$event_id' and professional_vender_id='$service_professional_id' ") or die(mysql_error());
	$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
	$job_closure_id=$event_detail['job_closure_id'];
	$job_closure_medicine=mysql_query("insert into sp_job_closure_consumption_mapping() VALUES('','$job_closure_id','$flag','$consumables_unit','$consumables_unit_textbox','1','$service_professional_id','$date','$service_professional_id','$date')")or die(mysql_error("error"));
}
if($flag=='4')
{
	$consumables_Non_unit=$_GET['consumables_Non_unit'];
	$consumables_Non_unit_textbox=$_GET['consumables_Non_unit_textbox'];
	$event_detail=mysql_query("SELECT * FROM sp_job_closure where event_id='$event_id' and professional_vender_id='$service_professional_id' ") or die(mysql_error());
	$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
	$job_closure_id=$event_detail['job_closure_id'];
	$job_closure_medicine=mysql_query("insert into sp_job_closure_consumption_mapping() VALUES('','$job_closure_id','$flag','$consumables_Non_unit','$consumables_Non_unit_textbox','1','$service_professional_id','$date','$service_professional_id','$date')")or die(mysql_error("error"));
}

?>