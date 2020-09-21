<?php 
    include "inc_classes.php";
    include "admin_authentication.php";      
    include "pagination-include.php";
	
	$patient_id = $_GET['patient_id'];
	$Number=$_GET['Number'];
	//echo $date_service;
	$query=mysql_query("update sp_assisted_living_booking set status='2' where Flat_Number='$Number' and patient_id='$patient_id' ")or die(mysql_error());
	if($query)
	{
		echo 'Updated';
	}
	else
	{
		echo 'Fail';
	}
	?>