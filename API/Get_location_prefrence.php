<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='GET')
 {
	if(isset($_COOKIE['id'])) 
	
	{
	    
		$id=$_COOKIE['id'];
			$device_id=$_COOKIE['device_id'];
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
			$query= mysql_query("SELECT * FROM sp_professional_location WHERE professional_service_id = '$id' ");
					$row_count = mysql_num_rows($query);
	if ($row_count > 0)
	{
			while ($row = mysql_fetch_array($query))
			{
				$professional_service_id=$row['professional_service_id'];				
				$Professional_location_id=$row['Professional_location_id'];	
					$Professional_location_id=(int)$Professional_location_id;
				$Name=$row['Name'];
			
				$querys= mysql_query("SELECT * FROM sp_professional_location_details WHERE Professional_location_id = '$Professional_location_id' ");
			  	while ($rows = mysql_fetch_array($querys))
				{
					$lattitude=$rows['lattitude'];
					$longitude=$rows['longitude'];
					$lattitude=(double)$lattitude;	$longitude=(double)$longitude;
					
				
				$result[]=array('latitude' => $lattitude, 'longitude'=>$longitude);
				
				$professional_service_id=(int)$professional_service_id;
				
				}
				$data=$result;
				$out[]=array('id' => $Professional_location_id, 'name'=>$Name,'pois'=>$data);
				unset($result);
			}
				
				$datas=$out;	
								
			 $sqls = mysql_query("UPDATE sp_service_professionals SET  location_status=1 WHERE service_professional_id ='$id'");
			
			  
			  echo json_encode(array("data"=>$datas,"error"=>null));
			 
	}
		else
		 {	
		     $sqls = mysql_query("UPDATE sp_service_professionals SET  location_status=1 WHERE service_professional_id ='$id'");
			  echo json_encode(array("data"=>[],"error"=>null));
			 
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