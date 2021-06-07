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
	$Query= mysql_query("SELECT * FROM sp_hospitals WHERE hospital_name='".$data->hospital_id."'");
	echo "SELECT * FROM sp_hospitals WHERE hospital_name='".$data->hospital_id."'";
	$row_count = mysql_num_rows($Query);
	if ($row_count > 0)
	{
	    
	}
	else{
	    echo 'hello';
		$arr = array();
		$arr['hospital_name']=ucfirst(strtolower($data->hospital_id));
		$arr['status']='1';
		$arr['added_date']=date('Y-m-d H:i:s');
		$InsertRecord=$commonClass->AddHospital($arr); 
	}echo $RecordId;
}else{
    http_response_code(401); 
}

?>
