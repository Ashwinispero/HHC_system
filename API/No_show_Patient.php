<?php 
 require_once('config.php');


if($_SERVER['REQUEST_METHOD']=='POST')
{
  
	 if(isset($_COOKIE['id']))
			 {
			     date_default_timezone_set("Asia/Calcutta");
				 $data = json_decode(file_get_contents('php://input'));
					$professional_vender_id=$_COOKIE['id'];
					$Session_id = $data->id;
					$reason = $data->reason;
					$reason_id = $reason->id;
					$comment = $reason->comment;
						$device_id=$_COOKIE['device_id'];
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_vender_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
						if($Session_id == '' )
							{
							
								http_response_code(400);
								
							}


				else
				 {
				  
					$plan_of_care=mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id'  ");
						$num_rows = mysql_num_rows($plan_of_care);
						if($num_rows > 0)
						{
								$result = mysql_fetch_array($plan_of_care);
								$Session_status = $result['Session_status'];
							
								$status=4;
							/*	if($Session_status==3)
								{
									echo json_encode(array("data"=>null,"error"=>array("code"=>1,"message"=>"This session is not started yet")));
								
									
								}*/
								//else
							//	{
									$sql = mysql_query("UPDATE sp_detailed_event_plan_of_care SET Session_status = 2,Reason_for_no_serivce  = '$reason_id',Comment_for_no_serivce  = '$comment' WHERE Detailed_plan_of_care_id ='$Session_id' AND professional_vender_id='$professional_vender_id' ");
									if($sql)
									{
													echo json_encode(array("data"=>null,"error"=>null));
													
									}
							//	}
						}
						
							else
							{
								 
								
								echo json_encode(array("data"=>null,"error"=>array("code"=>2,"message"=>"Session Not Found")));
							}
						
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