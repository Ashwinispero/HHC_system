<?php 
 require_once('config.php');


if($_SERVER['REQUEST_METHOD']=='POST')
{
  
 if(isset($_COOKIE['id'])) 
 {
	$data = json_decode(file_get_contents('php://input'));
	$Password = $data->Password;
date_default_timezone_set("Asia/Calcutta");
	$service_professional_id=$_COOKIE['id'];

    if($Password == '' )
    {
	
		http_response_code(400);
		
	} 
	else
	{
		$sql = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$service_professional_id'");
		$row_count = mysql_num_rows($sql);
		if ($row_count > 0)
		{
			//$result = mysql_fetch_row($sql);
		     	  
		     
				$sqls = mysql_query("UPDATE sp_service_professionals SET  APP_password  =  '$Password' WHERE service_professional_id ='$service_professional_id'");
				if($sqls)
				{
				
					echo json_encode(array("data"=>null,"error"=>null));
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