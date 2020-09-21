<?php 
include('config.php');
$service_professional_id = $_GET['service_professional_id'];
$old_pw=$_GET['old_pw'];
$new_pw=$_GET['new_pw'];
$Confirm_pw=$_GET['Confirm_pw'];
$Service_id=$_GET['Service_id'];
$query=mysql_query("select * from sp_professional_password where service_professional_id='$service_professional_id' ")or die(mysql_error("error"));
$query = mysql_fetch_array($query);
$professional_password = $query['professional_password'];
if($professional_password==$old_pw)
{	
	if($new_pw==$Confirm_pw)
	{
		$query=mysql_query("update sp_professional_password set professional_password='$Confirm_pw' where service_professional_id='$service_professional_id' ")or die(mysql_error());
	    echo 'success';
	}
	else
	{
		echo 'Pw_incorrect';
	}
}
else
{
	echo 'old_pw_incorrect';
}

?>