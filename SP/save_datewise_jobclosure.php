<?php include('config.php');

$event_id = $_GET['event_id'];
$service_id=$_GET['service_id'];
$sub_service_id=$_GET['sub_service_id'];
$date_service=$_GET['date_service'];
$service_professional_id=$_GET['service_professional_id'];
$job_closure_details=$_GET['job_closure_details'];
$Actual_service_date=$_GET['Actual_service_date'];
$start_date=$_GET['start_date'];
$end_date=$_GET['end_date'];
date_default_timezone_set('Asia/Kolkata'); 
$date = date('Y-m-d H:i:s');
$service_count=0;
$sql=mysql_query("SELECT * FROM sp_jobclosure_detail_datewise where added_by='$service_professional_id' and event_id='$event_id' AND service_id='$service_id' AND sub_service_id='$sub_service_id' AND service_date='$date_service' and StartTime='$start_date' and EndTime='$end_date'");
$sql = mysql_num_rows($sql);
if($sql>0)
{
	echo 'exist';
}
else
{
	$jobclosure_detail=mysql_query("insert into sp_jobclosure_detail_datewise() VALUES('','$event_id','$service_id','$sub_service_id','$date_service','$Actual_service_date','$job_closure_details','$start_date','$end_date','$service_professional_id','1','1','$date')")or die(mysql_error());
	echo 'insert';
}

?>