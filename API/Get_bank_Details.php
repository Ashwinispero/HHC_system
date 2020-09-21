<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='GET')
 {
			
	
	 if(isset($_COOKIE['id']))
	 {
		$Professional_id=$_COOKIE['id'];
		$device_id=$_COOKIE['device_id'];
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$Professional_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
	$query= mysql_query("SELECT Account_name,Account_number,Bank_name,Branch,IFSC_code,Account_type,Amount_with_spero,Amount_with_me FROM sp_bank_details WHERE Professional_id='$Professional_id'  ");
	$row_count = mysql_num_rows($query);
	
	if ($row_count > 0)
	{	
	    
	    $row = mysql_fetch_assoc($query);
	  
	
			  echo json_encode(array("data"=>$row,"error"=>null));
	 

	 
	}
	 }
	 }
	 else
	 {	  
		  http_response_code(401);
		  echo json_encode(array("data"=>null,"error"=>array("message"=>"Unauthorized")));
	 }
	 
	
 }
else 
{
	http_response_code(405); 
		 
	echo json_encode(array("data"=>null,"error"=>array("message"=>"Invalid_method call")));
}
?>