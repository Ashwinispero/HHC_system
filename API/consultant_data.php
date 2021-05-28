<?php
require_once 'classes/professionalsClass.php';
    
$professionalsClass=new professionalsClass();
include "classes/commonClass.php";
$commonClass= new commonClass();
include('config.php');
$data = json_decode(file_get_contents('php://input'));
date_default_timezone_set("Asia/Calcutta");
$password = md5($data->weblogin_password);
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $insertData = array();
	$insertData['name'] = $data->name;
	$insertData['hospital_id'] = $data->hospital_id;
	$insertData['email_id'] = $data->email_id;
	$insertData['mobile_no'] = $data->mobile_no;
	$insertData['work_address'] = $data->work_address;
	$insertData['type'] = $data->type;
	$insertData['added_date'] = $data->added_date;
    $insertData['weblogin_password'] = $password;
	
    $RecordId =$commonClass->submit_data_consultant($insertData);
    echo $RecordId;
}else{
    http_response_code(401); 
}

?>
