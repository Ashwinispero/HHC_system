<?php
        require_once('config.php');
         
         
		 if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
			 if(isset($_COOKIE['id']))
			 {
				$data = json_decode(file_get_contents('php://input'));
				$Session_id = $data->sessionId;
				
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
							
					
						
		            	$sql_media_query=mysql_query("SELECT * FROM sp_professional_media where service_id='$service_id' AND sub_service_id='$sub_service_id'  ");
		            	$num_rows_media = mysql_num_rows($sql_media_query);
						if($num_rows_media > 0)
						{
					$sql_media=mysql_query("SELECT MAX(service_day) As service_day FROM sp_professional_media where service_id='$service_id' AND sub_service_id='$sub_service_id'  ");
					$sql_media_array=mysql_fetch_array($sql_media);
					$media_id=$sql_media_array['service_day'];
					$day=$media_id;
					
					for($i=1;$i<=6;$i++)
					{
						
						$media_daywise=mysql_query("SELECT * FROM sp_professional_media where service_id='14' AND sub_service_id='$service_id' And  service_day='$i'");
						$num_rows_media_daywise = mysql_num_rows($media_daywise);
						if($num_rows_media_daywise > 0)
						{
    						while($media_daywise_array=mysql_fetch_array($media_daywise))
    						{	
    										
    										$media_id=$media_daywise_array['id'];
    										$media_id=(int)$media_id;
    										$path=$media_daywise_array['path'];	
    									
    										$Type=$media_daywise_array['Type'];	
    										$Type=(int)$Type;
    										$Service_day=$media_daywise_array['service_day'];
    										
    										$thumbnail_path=$media_daywise_array['thumbnail_path'];
    								
    										$result[]=array('mediaId' =>$media_id, 'type'=>$Type,'thumbnailUrl' =>$thumbnail_path, 'url'=>$path );
    							
    						
    			
    				        }
    				        
    						$results=$result;
    						$out[]=array('day' => $i, 'medialist'=>$results);
    						unset($result);
						}
						
					}
				
				
						
				
		
			 $data=$out;			
		     echo json_encode(array("data"=>$data,"error"=>null));		
						
						
						
						}
						else
						{
						      echo json_encode(array("data"=>[],"error"=>null));
						    
						}
						
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
      