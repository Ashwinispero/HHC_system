<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='GET')
 {
	if(isset($_COOKIE['id'])) 
	
	{
		$id=$_COOKIE['id'];
			$device_id=$_COOKIE['device_id'];
		 $professional_vender_id=$_COOKIE['id'];
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_vender_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
			$query= mysql_query("SELECT * FROM sp_professional_avaibility WHERE professional_service_id = '$id' ");
			$row_count = mysql_num_rows($query);
	if ($row_count > 0)
	{
			while ($row = mysql_fetch_array($query))
			{
				$professional_service_id=$row['professional_service_id'];				
				$professional_avaibility_id=$row['professional_avaibility_id'];	
				$day=$row['day'];					
				$day=(int)$day;

				$querys= mysql_query("SELECT * FROM sp_professional_availability_detail WHERE professional_availability_id = '$professional_avaibility_id' ");
			  	while ($rows = mysql_fetch_array($querys))
				{
					$start_time=$rows['start_time'];
					$end_time=$rows['end_time'];
					$professional_location_id=$rows['professional_location_id'];
						
					//$result[]=array('startTime' => $start_time, 'endTime'=>$end_time);
				
					$professional_avaibility_id=(int)$professional_avaibility_id;
						$Loc_query= mysql_query("SELECT * FROM sp_professional_location WHERE Professional_location_id = '$professional_location_id' AND professional_service_id = '$id' ");
			  	while ($Locations = mysql_fetch_array($Loc_query))
				{
				
					$Name=$Locations['Name'];
					$Professional_location_id=$Locations['Professional_location_id'];
						$Professional_location_id=(int)$Professional_location_id;
					$results=array('id' => $Professional_location_id, 'name'=>$Name);
				}
				$datas=$results;
				$result[]=array('startTime' => $start_time, 'endTime'=>$end_time,'selectedLocation'=>$datas);
				unset($results);	
				}
				$data=$result;
				
				$out[]=array('availableDay' => $day, 'workingHours'=>$data);
				unset($result);	
						
			}
				
				$datas=$out;	
			
			
			  
			  echo json_encode(array("data"=>$datas,"error"=>null));
			 
				
	}
	else
		 {	
		     $sqls = mysql_query("UPDATE sp_service_professionals SET  availability_status  =  1, location_status=1 WHERE service_professional_id ='$id'");
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