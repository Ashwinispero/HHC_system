<?php
require_once('inc_classes.php'); 

$professional_id=$_GET['professional_id'];
$week_offID=$_GET['week_offID'];
$Leave_Status=$_GET['Leave_Status'];


$query=mysql_query("update sp_professional_weekoff set Leave_status ='$Leave_Status' where service_professional_id=".$professional_id." AND professional_weekoff_id=".$week_offID." ");

$url = "http://45.40.136.143/~spero/Spero_HHC_API/API/push_notify.php";

$data= array();


$data = '{"Type":2,
"Professional_id":$professional_id,
"Leave_id":$week_offID,
"Leave_id":$Leave_Status
}';

$out = send_curl_request($url, $data, "post");
?>