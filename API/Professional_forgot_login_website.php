<?php 

        
    require_once 'classes/professionalsClass.php';
    
    $professionalsClass=new professionalsClass();
    include "classes/commonClass.php";
    $commonClass= new commonClass();
	include('config.php');
			  
   
	 
    $data = json_decode(file_get_contents('php://input'));
	date_default_timezone_set("Asia/Calcutta");
    $mobileNumber = $data->mobileNumber;
    
 
	$added_date=date('Y-m-d H:i:s');

	$status=1;
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
	    
		if(!empty($mobileNumber))
		{
			$Query= mysql_query("SELECT * FROM sp_doctors_consultants WHERE mobile_no = '$mobileNumber' AND status='1' ");
		//	echo "SELECT * FROM sp_service_professionals WHERE mobile_no = '$mobileNumber' AND APP_password = '$password'  ";
				$row_count = mysql_num_rows($Query);
				if ($row_count > 0)
				{
					$Query_row = mysql_fetch_array($Query);
					$doctors_consultants_id=$Query_row['doctors_consultants_id'];
					$name=$Query_row['name'];
					$first_name=$Query_row['first_name'];
					$middle_name=$Query_row['middle_name'];
					$email_id=$Query_row['email_id'];
					$mobile_no=$Query_row['mobile_no'];
					$title=$Query_row['title'];
					
					echo json_encode(array("doctors_consultants_id"=>$doctors_consultants_id,
										"name"=>$name,
										"first_name"=>$first_name,
										"middle_name"=>$middle_name,
										"email_id"=>$email_id,
										"mobile_no"=>$mobile_no,
										"title"=>$title,
										"message"=>'Success'
						));	
				}
				else{
					echo json_encode(array("data"=>null,"message"=>"Your Account is deactivated, Contact Admin on 7620400100"));	
				}
		}else{
			http_response_code(400); 
		}
	}
	else
	{
		http_response_code(405); 
		
	}

?>