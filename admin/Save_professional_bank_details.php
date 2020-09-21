<?php
require_once('inc_classes.php'); 

$professional_id=$_GET['professional_id'];
$Account_Name=$_GET['Account_Name'];
$Account_Number=$_GET['Account_Number'];
$Bank_Name=$_GET['Bank_Name'];
$IFSC_Code=$_GET['IFSC_Code'];
$Account_Type=$_GET['Account_Type'];
$Branch_Name=$_GET['Branch_Name'];
$query=mysql_query("update sp_bank_details set Account_name='$Account_Name' , Account_number='$Account_Number' , Bank_name='$Bank_Name', Branch='$Branch_Name' , IFSC_code='$IFSC_Code' , Account_type='$Account_Type' where Professional_id=".$professional_id." ");
//echo "update sp_bank_details set Account_name='$Account_Name' AND Account_number='$Account_Number' AND Bank_name='$Bank_Name' AND Branch='$Branch_Name' AND IFSC_code='$IFSC_Code' AND Account_type='$Account_Type' where Professional_id=".$professional_id."";
?>