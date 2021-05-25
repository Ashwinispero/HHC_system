<?php
require_once 'classes/professionalsClass.php';
    
$professionalsClass=new professionalsClass();
include "classes/commonClass.php";
$commonClass= new commonClass();
include('config.php');
$data = json_decode(file_get_contents('php://input'));
date_default_timezone_set("Asia/Calcutta");
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $insertData = array();
	$insertData['patient_fname'] = $data->patient_fname;
	$insertData['patient_contact'] = $data->patient_contact;
	$insertData['patient_age'] = $data->patient_age;
	$insertData['patient_gender'] = $data->patient_gender;
	$insertData['google_location'] = $data->google_location;

	$insertData['mainService'] = $data->mainService;
	$insertData['sub_service'] = $data->sub_service;
	$insertData['note'] = $data->note;
	$insertData['status'] = '1';
	$insertData['added_date'] = $data->added_date;
    $RecordId =$commonClass->submit_data($insertData);
    echo $RecordId;
}else{
    http_response_code(401); 
}

?>
