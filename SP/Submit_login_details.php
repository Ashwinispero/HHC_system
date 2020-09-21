<?php
//include database file
include('config.php');


$UserEmail = $_POST['UserEmail'];
$Password = $_POST['password'];
$Usertype = $_POST['Usertype'];
/*
$con=mysql_connect("localhost","root","","") or die(mysql_error("Not Connected"));
mysql_select_db("spero_ems",$con) or die(mysql_error("Not Connected"));*/


//$Password1=md5($Password);
//session_start(); 
session_start(); 
$query=mysql_query("SELECT * FROM sp_service_professionals where email_id='$UserEmail' and status='1'") or die(mysql_error());
if(mysql_num_rows($query) < 1 )
{
	echo 'Login fail';
}
else
{
	

					
$row = mysql_fetch_array($query) or die(mysql_error());
$service_professional_id=$row['service_professional_id'];
//echo $Usertype;
 
 if($Usertype=='3' OR $Usertype=='16') 
	{
		$query1=mysql_query("SELECT * FROM sp_professional_password where service_professional_id='$service_professional_id' and professional_password='$Password' and (service_id='3' OR service_id='16')") or die(mysql_error());
		$row1 = mysql_fetch_array($query1) or die(mysql_error("Not Retrieved"));
	}
	else
	{
		$query1=mysql_query("SELECT * FROM sp_professional_password where service_id='$Usertype' and service_professional_id='$service_professional_id' and professional_password='$Password'") or die(mysql_error());
		$row1 = mysql_fetch_array($query1) or die(mysql_error("Not Retrieved"));
	}
   
   
   if(!empty($row1['service_id']) AND !empty($row1['service_professional_id']) AND !empty($row1['professional_password']))
        { 
        $_SESSION['Service_id']=$Usertype;
		$_SESSION['service_professional_id']=$service_professional_id;
		//$_SESSION['type']=$Usertype;
		$_SESSION['login_time'] = time(); 
        
        echo 'Login';
		}
		else
		{
			echo 'Login fail';
		}
}
?>