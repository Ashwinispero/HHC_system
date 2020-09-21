<?php 
    include "inc_classes.php";
    include "admin_authentication.php";      
    include "pagination-include.php";
	
	$event_id = $_GET['event_id'];
	$Activity = $_GET['Activity'];
	$Start_time = $_GET['Start_time'];
	$End_time = $_GET['End_time'];
	$date_service = $_GET['date_service'];
	$Cost=$_GET['Cost'];
	$Tax=$_GET['Tax'];
	//$date = '19:24'; 
	$Start_time = date('h:i a', strtotime($Start_time));
	$End_time = date('h:i a', strtotime($End_time));
$status='1';
date_default_timezone_set('Asia/Kolkata'); 
$date = date('Y-m-d H:i:s');


	$assisted_living_facility=mysql_query("insert into sp_assisted_living_schedule() VALUES('','$event_id','$date_service','$Activity','$Cost','$Tax','$Start_time','$End_time','$status','$date')")or die(mysql_error("error"));
	if($assisted_living_facility)
	{
		echo 'Insert';
	}
	else
	{
		echo 'Fail';
	}
	
	
	?>