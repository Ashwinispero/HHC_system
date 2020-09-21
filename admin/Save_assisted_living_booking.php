<?php 
    include "inc_classes.php";
    include "admin_authentication.php";      
    include "pagination-include.php";
	
	$patient_id = $_GET['patient_id'];
	$Facility_type = $_GET['Facility_type'];
	$Patient_location = $_GET['Patient_location'];
	$Flat_Number = $_GET['Flat_Number'];
	
$status='1';
date_default_timezone_set('Asia/Kolkata'); 
$date = date('Y-m-d H:i:s');

$query1=mysql_query("select * from sp_assisted_living_booking where Flat_Number='$Flat_Number' AND Patient_location='$Patient_location' AND status='1'");
if(mysql_num_rows($query1) < 1)
{
	$assisted_living_facility=mysql_query("insert into sp_assisted_living_booking() VALUES('','$Flat_Number','$Patient_location','$Facility_type','$patient_id','$status','$date')")or die(mysql_error("error"));
	if($assisted_living_facility)
	{
		echo 'Insert';
	}
	else
	{
		echo 'Fail';
	}
}
else
{
	echo 'Busy';
}


?>

