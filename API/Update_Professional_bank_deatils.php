<?php 
 

	include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
{
 	
	if(isset($_COOKIE['id']))
	{
		$professional_id=$_COOKIE['id'];
			$data = json_decode(file_get_contents('php://input'));
			date_default_timezone_set("Asia/Calcutta");
			$accountNumber = $data->accountNumber;
			$accountName = $data->accountName;
			$bank = $data->bank;
            $branch = $data->branch;
			$ifscCode = $data->ifscCode;
			$accountType = $data->accountType;
		$device_id=$_COOKIE['device_id'];
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{	
	
	if($accountNumber == '' || $accountName == '' || $bank == '' || $branch == '' || $ifscCode == '' ||$accountType == '')

	{
		http_response_code(400);
		echo json_encode(array("data"=>null,"error"=>array("message"=>"Paramters Missing")));
	}
	
			if(isset($_COOKIE['id']))
			{	

				$sql = mysql_query("SELECT * FROM sp_bank_details WHERE professional_id = '$professional_id'");
				$row_count = mysql_num_rows($sql);
				if ($row_count > 0)
				{																																																					
						$sqls = mysql_query("UPDATE sp_bank_details SET  Account_name = '$accountName', Account_number = '$accountNumber', Bank_name = '$bank', Branch = '$branch' , IFSC_code = '$ifscCode', Account_type = '$accountType' WHERE professional_id ='$professional_id'");
						
						if($sqls)
						{
						
							echo json_encode(array("data"=>null,"error"=>null));
						}
						else
						{
							
							http_response_code(401);
							
						}
	   
			    }
				else
				 {	  
					  http_response_code(401);
					 
				 }
			}
	
			else
			 {	  
				  http_response_code(401);
				 
			 }
	 

	}
	}
	 else
		 {	  
			  http_response_code(401);
			 
		 }
}
	
else
{
	http_response_code(405); 
	 
			
}


?>