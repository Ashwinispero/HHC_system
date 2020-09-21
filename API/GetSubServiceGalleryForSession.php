<?php
        require_once('config.php');
         
         
		 if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
			 if(isset($_COOKIE['id']))
			 {
				$data = json_decode(file_get_contents('php://input'));
				$Session_id = $data->sessionId;
				$day = $data->day;
				
				$professional_vender_id=$_COOKIE['id'];
			
		
			 if($Session_id == '' )
				{
				
					http_response_code(400);
					
				}
			
			else
				
				{
					$plan_of_care=mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id' ");
					$num_rows = mysql_num_rows($plan_of_care);
					if($num_rows > 0)
					
					{
								$Detailed_plan_of_care_id=$plan_of_care_detail['Detailed_plan_of_care_id'];
								$Detailed_plan_of_care_id=(int)$Detailed_plan_of_care_id;							
								$event_requirement_id=$plan_of_care_detail['event_requirement_id'];								
								$event_id=$plan_of_care_detail['event_id'];
								$plan_of_care_id=$plan_of_care_detail['plan_of_care_id'];
							 									
										$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
										$sql11 = mysql_fetch_array($sql1);
										
										$event_code=$sql11['event_code'];
										$patient_id=$sql11['patient_id'];
										$patient_id=(int)$patient_id;
										$service_info=mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'");
										$service_info = mysql_fetch_array($service_info);
										$service_id=$service_info['service_id'];
										$sub_service_id=$service_info['sub_service_id'];
										
										$service_infos=mysql_query("SELECT * FROM sp_services where service_id='$service_id'");
										$service_infos = mysql_fetch_array($service_infos);
										$service_id=$service_infos['service_id'];
										$service_id=(int)$service_id;
										$service_title=$service_infos['service_title'];
										
										$sub_service_info=mysql_query("SELECT * FROM sp_sub_services where sub_service_id='$sub_service_id'");
										$sub_service_row = mysql_fetch_array($sub_service_info);
										$sub_service_id=$service_info['sub_service_id'];
										$sub_service_id=(int)$sub_service_id;
										$sub_service_title=$sub_service_row['recommomded_service'];
							
	

								
					
						$media_query=mysql_query("SELECT * FROM sp_professional_media_day where  service_id='14' AND sub_service_id='365' AND Service_day='$day'");
			$media_query_row = mysql_num_rows($media_query);
		   while($media_query_array=mysql_fetch_array($media_query))			
			
			{
				
					$Service_day_id=$media_query_array['Service_day_id'];
					$Service_day=$media_query_array['Service_day'];
						
						$media_daywise=mysql_query("SELECT * FROM sp_professional_media where Service_day_id='$Service_day_id' ");
						while($media_daywise_array=mysql_fetch_array($media_daywise))
						
						{	
										
										$media_id=$media_daywise_array['id'];
										$path=$media_daywise_array['path'];										
										$Type=$media_daywise_array['Type'];					
					
						$result[]=array('mediaId' =>$media_id, 'type'=>$Type,'thumbnailUrl' =>$path, 'url'=>$path );
							
				        }
				
				$results=$result;
				$out[]=array('day' => $Service_day, 'medialist'=>$results);
			 unset($result);			
				
			}
			 $data=$out;			
		    echo json_encode(array("data"=>$data,"error"=>null));		
			 unset($out);			
						
						
						
						
						
						
					}
					else
					{
						echo json_encode(array("data"=>null,"error"=>array("code"=>1,"message"=>"Session not found")));
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
      