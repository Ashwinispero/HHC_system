<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
 {
		
	 
	if(isset($_COOKIE['id']))
	 
	 {
		
		$data = json_decode(file_get_contents('php://input'));
		$deviceId = $data->deviceId;
		$service_professional_id=$_COOKIE['id'];
		date_default_timezone_set("Asia/Calcutta");
		$added_date=date('Y-m-d H:i:s');
			
		$query=mysql_query("SELECT * FROM sp_session WHERE service_professional_id='$service_professional_id'  ");
		$row_count = mysql_num_rows($query);
			if ($row_count > 0)
		{
			
		$sql=mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$added_date' WHERE service_professional_id='$service_professional_id' AND device_id='$deviceId' ");	
	    
       
       
						
		echo json_encode(array("data"=>null,"error"=>null));
								
			
		
		}
		else{
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
		 http_response_code(405);
	 }
 
	 ?>