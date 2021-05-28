<?php 

        
    require_once 'classes/professionalsClass.php';
    
    $professionalsClass=new professionalsClass();
    include "classes/commonClass.php";
    $commonClass= new commonClass();
	include('config.php');
			  
   
	 
    $data = json_decode(file_get_contents('php://input'));
	date_default_timezone_set("Asia/Calcutta");
    $mobileNumber = $data->mobile_no;
    $New_password = $data->New_password;
    $password = md5($New_password);
    //echo $mobileNumber;
    
	$added_date=date('Y-m-d H:i:s');

	$status=1;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
	   
		if($mobileNumber != '')
		{
		  
              $query=mysql_query("update  sp_doctors_consultants set last_modified_date=date('Y-m-d H:i:s'),weblogin_password='".$password."' where mobile_no=".$mobileNumber."  ")or die(mysql_error());
          
            echo json_encode(array("data"=>null,"message"=>"Success"));	
		}else{
			echo json_encode(array("data"=>null,"message"=>"Please Enter Mobile No"));	
		}
	}
	else
	{
		http_response_code(405); 
		
	}

?>