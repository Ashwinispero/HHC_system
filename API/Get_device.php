<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='GET')
 {
	 if(isset($_COOKIE['id']))
		
		{
			$service_professional_id=$_COOKIE['id'];
	 	$status=1;
		$querys= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$service_professional_id AND status=$status");
			$row_counts = mysql_num_rows($querys);
				if ($row_counts > 0)
				{	
	        	while($query_detail=mysql_fetch_array($querys))
                		{	
                			$device_id=$query_detail['device_id'];
                			
                		
                			$query= mysql_query("SELECT * FROM sp_professional_device_info WHERE device_id='$device_id'  ");
                				$row_count = mysql_num_rows($query);
                				
                				$rows = array();
                					 while ($Query_row = mysql_fetch_array($query))
                				  {
                					 
                								$device_id=$Query_row['device_id'];
                								$device_id=(int)$device_id;
                								$OSVersion=$Query_row['OSVersion'];
                								$OSName=$Query_row['OSName'];
                								$DevicePlatform=$Query_row['DevicePlatform'];
                								$AppVersion=$Query_row['AppVersion'];
                								$DeviceTimezone=$Query_row['DeviceTimezone'];
                								$DeviceCurrentTimestamp=$Query_row['DeviceCurrentTimestamp'];
                								$added_date=$Query_row['added_date'];
                								
                								$ModelName=$Query_row['ModelName'];
                					
                					$user[] = array(
                									'deviceId'=>$device_id,
                									'OSVersion'=>$OSVersion, 
                									'OSName'=>$OSName, 
                									'DevicePlatform'=>$DevicePlatform, 
                									'AppVersion'=>$AppVersion,	
                									'DeviceTimezone'=>$DeviceTimezone,
                									'DeviceCurrentTimestamp'=>$DeviceCurrentTimestamp,
                									'deviceModifiedTimeStamp'=>$added_date,
                									'ModelName'=>$ModelName);
                									
                					
                						
                				  }
                				  $data=$user;
				  
			

				 
				}	
			echo json_encode(array("data"=>$data,"error"=>null));
				
		 
		
		  
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
	http_response_code(405); 
		 

}
?>