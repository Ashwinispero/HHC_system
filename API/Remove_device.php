<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
 {
	 if(isset($_COOKIE['id']))
	 {
	date_default_timezone_set("Asia/Calcutta");	
	$data = json_decode(file_get_contents('php://input'));
	$deviceId = $data->deviceId;

	if($deviceId == '' )
	{
			http_response_code(400); 
			
	}
		 else 
		{
		$query= mysql_query("SELECT *  FROM sp_session WHERE device_id='$deviceId'");
		 
		$row_count = mysql_num_rows($query);
			if ($row_count >0)
			{	
			   $query= mysql_query("UPDATE  sp_session SET  status = '2' WHERE  device_id='$deviceId'");	
		  
				echo json_encode(array("data"=>null,"error"=>null));

			 
			}
			 else
			 {	  
				
				
				  echo json_encode(array("data"=>null,"error"=>array("code"=>1,"message"=>"Device Not Found")));
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