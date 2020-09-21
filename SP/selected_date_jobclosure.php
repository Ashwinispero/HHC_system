<?php include('config.php');

$event_id = $_GET['event_id'];

$sub_service_id=$_GET['sub_service_id'];
$date_service=$_GET['date_service'];
$service_professional_id=$_GET['service_professional_id'];
$time=$_GET['time'];
$service_id=$_GET['service_id'];
$Actual_service_date=$_GET['Actual_service_date'];
$start_date=$_GET['start_date'];
$end_date=$_GET['end_date'];
//echo $start_date;
//Session expire after 15 min
 if( !isset( $service_professional_id) || time() - $time > 900)
{
   //header("Location:index.php");
   echo "<script>
alert('Your Session is expire...Palese Login again!! ');

</script>";
  echo 'session_expire';
}
 else {
    
$time = time();

if(isset($service_professional_id) && !empty($service_professional_id) ) {
		
		$event_detail=mysql_query("SELECT * FROM sp_events where event_id='$event_id'") or die(mysql_error());
			$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
			$event_code=$event_detail['event_code'];
			$patient_id=$event_detail['patient_id'];
			$event_date=$event_detail['event_date'];
			
			$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
			$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
			$name=$patient_nm['name'];
			$first_name=$patient_nm['first_name'];
			$middle_name=$patient_nm['middle_name'];
?>
<div id="overlay_display_add_jobclosure">
<div id="popupwindow_display_jobclosure" style="overflow:auto;">
<div id="close_btn" align='right'><input type="button" onclick="close_popup();" style="color:white;background-color:#00cfcb;align:right;" value=" X "></div>
<div class="col-lg-11" >
<div class="row"  ><h4 align="center" style="color:#00cfcb">Add Jobclosure</h4>
</div>
 <div class="row">
				<div class="col-lg-12" >
                  <h4>Patient Name:    <?php echo $name.' '.$first_name.' '.$middle_name; ?></h4>
				 </div>
				 </div>
				 <div class="row">
				<div class="col-lg-12" >
                  <h4>Event Code:    <?php echo $event_code; ?></h4>
				 </div>
				 </div>
</div>
<div class="col-lg-12">
<textarea class="form-control" rows="3"  id="job_closure_details" style="resize:none"></textarea>
</div>
<div class="col-lg-12" align="center" style="margin-top:3%">
<?php
	echo '<input type="button" value="Add" style="background-color:#ffbf00;border-radius:15px;padding-left:10px;padding-right:10px;" onclick="Save_Jobclosure_datewise_details(\'' . $event_id . '\',\'' . $sub_service_id  .'\',\'' . $date_service  .'\',\'' . $service_professional_id  .'\',\'' . $service_id  .'\',\'' . $Actual_service_date  .'\',\'' . $start_date  .'\',\'' . $end_date  .'\');">'
?>
<?php
	echo '<input type="button" value="Cancle" style="background-color:#ffbf00;border-radius:15px;padding-left:10px;padding-right:10px;" onclick="close_popup();" style="margin-left:2%;">'
?>
</div>
</div>
</div>
<?php 
//session_destroy();
}

else
{
	echo "<script>
alert('Your Session is expire...Palese Login again!! ');

</script>";
    //echo "<script type='text/javascript'>Alert.render('User Login');</script>";
   echo 'session_expire'; 
   
 }}
 ?>
