<?php
 require_once 'classes/professionalsClass.php';
$professionalsClass=new professionalsClass();


mysql_connect("localhost","hospital_sp_pune","Spero@Pune@2016") or die(mysql_error("Not Connected"));
mysql_query("use hospital_spero_broadcast_live") or die(mysql_error("Not Connected"));



  define('API_ACCESS_KEY','AIzaSyDrnTkqxLolBzBz5a0Kvq6Kh65-Fz4BsB0');
	$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
	date_default_timezone_set("Asia/Calcutta");
	$data = json_decode(file_get_contents('php://input'));


	$added_date=date('Y-m-d H:i:s');	
			$Type = $data->Type;
			$Professional_id = $data->Professional_id;
			$Leave_id = $data->Leave_id;
			$Event_id = $data->Event_id;
			$Title = $data->Title;
			$Subserviceid = $data->sub_service_id;
			$Payment_id = $data->Payment_id;
	    	$Document_id = $data->Document_id;
	    	$rejection_reason = $data->rejection_reason;
	    	$reschedule_session_id = $data->reschedule_session_id;
	    	$locationDtls = $data->locationDtls;
	    	
	    	$rejection_reason=rtrim($rejection_reason,'.');
	    
		$querys= mysql_query("SELECT * FROM sp_session WHERE service_professional_id='$Professional_id' AND status=1 ");
		$row_counts = mysql_num_rows($querys);
		$query_detail=mysql_fetch_array($querys);
		$device_id=$query_detail['device_id'];
	
			
		    	$query= mysql_query("SELECT * FROM sp_professional_device_info WHERE device_id='$device_id' ");
				$row_count = mysql_num_rows($query);
				if ($row_count > 0)
				{	
					$rows = array();
			    	$Query_row = mysql_fetch_array($query);
					$token=$Query_row['Token'];
				  
				}	
		
				
			
		 
	 
	 if($Type==1)
	 {
		//if($OSName=='Android')	
	//	{
			if($Title==1)
			{
                        			  $Professional_count=0;
			                           foreach($Professional_id as $key=>$valServices)
											{
											    
												$Professional_id = $valServices;
												
										
			                        	$querys= mysql_query("SELECT * FROM sp_session WHERE service_professional_id='$Professional_id' AND status='1' ");
                                		$row_counts = mysql_num_rows($querys);
                                		$query_detail=mysql_fetch_array($querys);
                                		$device_id=$query_detail['device_id'];
                                	
                                			
                                		    	$query= mysql_query("SELECT * FROM sp_professional_device_info WHERE device_id='$device_id' ");
                                				$row_count = mysql_num_rows($query);
                                				if ($row_count > 0)
                                				{	
                                					$rows = array();
                                			    	$Query_row = mysql_fetch_array($query);
                                					$token=$Query_row['Token'];
                                				  
                                		    	}
										
									 $subsericecount=0;
									 $countsubservices=0;
								    	foreach($Subserviceid as $key=>$valServices)
											{
											    $subsericecount++;
											  	$Subserviceid = $valServices;  
                                               
                                            $services_of_prof= mysql_query("SELECT * FROM sp_professional_sub_services WHERE service_professional_id = '$Professional_id' AND sub_service_id=$Subserviceid");
					                                    
					                               	$services_of_prof_count = mysql_num_rows($services_of_prof);
                                    				if($services_of_prof_count > 0)
                                    				{
                                                            $countsubservices++;
                                                                    
                                    				}
            
                                               
											  	
											  	$service_info=mysql_query("SELECT * FROM sp_event_requirements where event_id= $Event_id AND sub_service_id=$Subserviceid ");
											  	$service_info = mysql_fetch_array($service_info);
											  	$event_requirement_id=$service_info['event_requirement_id'];
        										$service_id=$service_info['service_id'];
        										$sub_service_ids=$service_info['sub_service_id'];
        										
        				                    	$plan_of_care=mysql_query("SELECT * FROM sp_event_plan_of_care where event_requirement_id='$event_requirement_id' ");
        				                    	while($plan_of_care_array = mysql_fetch_array($plan_of_care))
        				                    	{
        										
        										$service_date=$plan_of_care_array['service_date'];
        										$service_date_to=$plan_of_care_array['service_date_to'];
        										$start_date=$plan_of_care_array['start_date'];
        										$end_date=$plan_of_care_array['end_date'];
        								
        									
        										$service_start_date=date('d-m-Y', strtotime($service_date));
        										$service_end_date=date('d-m-Y', strtotime($service_date_to));
        										
        									
        										$start_date_ampm= date('h:i a', strtotime($start_date));
        										$end_date_ampm= date('h:i a', strtotime($end_date));
											  	
										     	$Sub_service= mysql_query("SELECT * FROM sp_sub_services WHERE  sub_service_id=$sub_service_ids ");
                            					while($Sub_services = mysql_fetch_array($Sub_service))
                            					{
                            					$Sub_service_id=$Sub_services['sub_service_id'];
                            					$Sub_service_id=(int)$Sub_service_id;
                            					
                            					$Sub_service_name= mysql_query("SELECT * FROM sp_sub_services WHERE  sub_service_id=$Sub_service_id ");
                            					$Sub_servicesss = mysql_fetch_array($Sub_service_name);
                            					$Sub_service_names=$Sub_servicesss['recommomded_service'];
                            					
                            					$types_profs=$Sub_service_names;
                            					
                            				
                            					}
                            					$types_prof[]="$types_profs from $service_start_date to $service_end_date, daily at $start_date_ampm to $end_date_ampm";
                            					
                            				
                            					$sub_data=json_encode($types_prof);
        				                    	}
        				                    
										}
								
				
					$title="New Service";
			  		$type='service';
			     	$Event_id=$Event_id;
			     
			    $msgconten = str_replace(array( '\'', '"','[',']'), ' ', $sub_data);
           
				$message="New service requested for$msgconten Location: $locationDtls"; //from $service_start_date to $service_end_date, daily at $start_date_ampm to $end_date_ampm. ";
	            
	         
	            
	        	$notificaton_message = preg_replace('/\s+/', ' ', $message);
	        	$notificaton_message = preg_replace('/\s*,/', ',', $notificaton_message);
	        $notificaton_message=rtrim($notificaton_message);
	        $notificaton_message="$notificaton_message.";
	        
	       /*  $words = explode(",", $notificaton_message);
            $wordsTrimmed = array_map("trim", $words);
            $csvString = implode(",", $wordsTrimmed);*/
         
           if($subsericecount==$countsubservices)
           {
	        
				if ($row_counts > 0)
				{
				    $Professional_count++;
				 
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$notificaton_message;
	                        	$args['notification_detail_id']=$Event_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                    	    $args['Acknowledged']=0;
							$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
				            
                    $token=$token;
                    $notification = [
                            'notificationid' =>$InsertOtherDtlsRecord,
                            'type' =>'Service',
                            'id' => $Event_id,
                			 'title' => $title,
                            'message' => $notificaton_message,
                             'status' => 0
                           
                        ]; 
                        
                          
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                    $response = curl_exec($ch);  
                  
			    	}    
			
			}
	
		
			
		}
		
			}
			elseif($Title==2)
			{
			    	$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id='$Professional_id'  ");
                    $querys_row_counts = mysql_num_rows($querys_session);
			    
				$title=" Reschedule ";
				$message="Session is rescheduled. Please check session details.";
					$type='service';
				$Event_id=$Event_id;
				if ($querys_row_counts > 0)
				{				
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$reschedule_session_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                        
	                    	
							$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
				
				
                    $token=$token;
                    $notification = [
                         'notificationid' =>$InsertOtherDtlsRecord,
                            'type' =>'Service',
                            'id' => $reschedule_session_id,
                			 'title' => $title,
                            'message' => $message
                           
                        ]; 
                        
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                
                
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                    $response = curl_exec($ch);  
                   
                        
				}
			}
			elseif($Title==3)
			{
				$title=" Cancel ";
				$message="Session is canceled";
				$type='service';
				$Event_id=$Event_id;
				
				if ($row_counts > 0)
				{				
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$Event_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                        	$args['Acknowledged']=0;
	                    	
							$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
				
				   $token=$token;
                    $notification = [
                        
                         'notificationid' =>$InsertOtherDtlsRecord,
                            'type' =>'Service',
                            'id' => $Event_id,
                			 'title' => $title,
                            'message' => $message
                           
                        ]; 
                        
                        
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                    $response = curl_exec($ch);  
                   
				}
				
			}
			elseif($Title==4)
			{
			    	$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id='$Professional_id'  ");
                    $querys_row_counts = mysql_num_rows($querys_session);
                  
											  	$service_info=mysql_query("SELECT * FROM sp_event_requirements where event_id= $Event_id");
											  	while($service_info = mysql_fetch_array($service_info))
											  	{
											  	$event_requirement_id=$service_info['event_requirement_id'];
        										$service_id=$service_info['service_id'];
        										$sub_service_ids=$service_info['sub_service_id'];
        										
        				                    	$plan_of_care=mysql_query("SELECT * FROM sp_event_plan_of_care where event_requirement_id='$event_requirement_id' ");
        				                    	while($plan_of_care_array = mysql_fetch_array($plan_of_care))
        				                    	{
        										
        										$service_date=$plan_of_care_array['service_date'];
        										$service_date_to=$plan_of_care_array['service_date_to'];
        										$start_date=$plan_of_care_array['start_date'];
        										$end_date=$plan_of_care_array['end_date'];
        								
        									
        										$service_start_date=date('d-m-Y', strtotime($service_date));
        										$service_end_date=date('d-m-Y', strtotime($service_date_to));
        										
        									
        										$start_date_ampm= date('h:i a', strtotime($start_date));
        										$end_date_ampm= date('h:i a', strtotime($end_date));
											  	
										     	$Sub_service= mysql_query("SELECT * FROM sp_sub_services WHERE  sub_service_id=$sub_service_ids ");
                            					while($Sub_services = mysql_fetch_array($Sub_service))
                            					{
                            					$Sub_service_id=$Sub_services['sub_service_id'];
                            					$Sub_service_id=(int)$Sub_service_id;
                            					
                            					$Sub_service_name= mysql_query("SELECT * FROM sp_sub_services WHERE  sub_service_id=$Sub_service_id ");
                            					$Sub_servicesss = mysql_fetch_array($Sub_service_name);
                            					$Sub_service_names=$Sub_servicesss['recommomded_service'];
                            					
                            					$types_profs=$Sub_service_names;
                            					
                            				
                            					}
                            					$types_prof[]="$types_profs from $service_start_date to $service_end_date, daily at $start_date_ampm to $end_date_ampm ";
                            					
        				                    	
                            					$sub_data=json_encode($types_prof);
        				                    	}
											  	}
			    
			    	$title="New Service";
			  		$type='service';
			     	$Event_id=$Event_id;
			     		
			    $msgconten = str_replace(array( '\'', '"','[',']'), ' ', $sub_data);
           
				$message="New service assigned to you for$msgconten Location: $locationDtls"; //from $service_start_date to $service_end_date, daily at $start_date_ampm to $end_date_ampm. ";
	           
	        	$notificaton_message = preg_replace('/\s+/', ' ', $message);
	        	$notificaton_message = preg_replace('/\s*,/', ',', $notificaton_message);
	        $notificaton_message=rtrim($notificaton_message);
	        $notificaton_message="$notificaton_message.";
				
					if ($querys_row_counts > 0)
				{				
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$notificaton_message;
	                        	$args['notification_detail_id']=$Event_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                        	$args['Acknowledged']=1;
	                    	
							$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
				
				   $token=$token;
                    $notification = [
                        
                         'notificationid' =>$InsertOtherDtlsRecord,
                            'type' =>'Service',
                            'id' => $Event_id,
                			 'title' => $title,
                            'message' => $message
                           
                        ]; 
                        
                        
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                    $response = curl_exec($ch);  
                   
				}
			
			    
			    
			}
			 curl_close($ch);
            return $response;
		                
                    
	/*}
    elseif($OSName=='iOS')
           {
		    	$apnsHost = 'gateway.sandbox.push.apple.com';
				$apnsCert = 'ck.pem';
				$apnsPort = 2195;
				$apnsPass = '<PASSWORD_GOES_HERE>';
				$token = '974eb97d262c6a74e22ee7a632b1444d109956f7e48b44255aff1681bbc1d741';

				$payload['aps'] = array('alert' => array('title' => 'Oh hai!','body' => 'Oh hai!'), 'type' => 1, 'id' => '1');
				$output = json_encode($payload);
				$token = pack('H*', str_replace(' ', '', $token));
				$apnsMessage = chr(0).chr(0).chr(32).$token.chr(0).chr(strlen($output)).$output;

				$streamContext = stream_context_create();
				stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
				stream_context_set_option($streamContext, 'ssl', 'passphrase', $apnsPass);

				$apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
				fwrite($apns, $apnsMessage);
				fclose($apns);
	            echo $output;
	 }*/
	}
	elseif($Type==2)
	 {
	     	$Get_leave=mysql_query("SELECT * FROM sp_professional_weekoff WHERE service_professional_id='$Professional_id' AND professional_weekoff_id='$Leave_id'");
    		$leave_data = mysql_fetch_array($Get_leave);
    			$date_form=$leave_data['date_form'];
        			$date_to=$leave_data['date_to'];
        		   	$service_start_date=date('d-m-Y', strtotime($date_form));
        			$service_end_date=date('d-m-Y', strtotime($date_to));
        			$start_date_ampm= date('h:i a', strtotime($date_form));
        			$end_date_ampm= date('h:i a', strtotime($date_to));
        		   $leave_date="$service_start_date to $service_end_date";
	 
	     
	     if($Title==1)
		{
		    
		    
		    
		    
			$title="Leave Accepted ";
			$message="Your leave request for $leave_date is accepted.";
			
				$type='Leave';
				$Leave_id=$Leave_id;
					if ($row_counts > 0)
				{			
								
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$Leave_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                        	
	                    	
					$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
					
					  $notification = [
					              'notificationid' =>$InsertOtherDtlsRecord,
                                                'type' =>'Leave',
                                                'id' => $Leave_id,
                                    			 'title' => $title,
                                                'message' => $message
                                               
                                            ];
                       
// $notificationobj= json_encode(object (AndroidNotification));   
                   
                      	$token=$token;
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                
                	
                	
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                 
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                
                  
                    $response = curl_exec($ch);   
                    curl_close($ch);
                            
                    return $response;

					
				}	
			
		}
		elseif($Title==2)
		{
		    
			$title=" Leave Rejected ";
			$message="Your leave request for $leave_date is rejected. $rejection_reason.";
			
			
				$type='Leave';
								$Leave_id=$Leave_id;
							
					if ($row_counts > 0)
				{				
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$Leave_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                    	
					$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
					
					  $notification = [
					              'notificationid' =>$InsertOtherDtlsRecord,
                                                'type' =>'Leave',
                                                'id' => $Leave_id,
                                    			 'title' => $title,
                                                'message' => $message
                                               
                                            ];
                                            	$token=$token;
                   
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                
                	
                	
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                   
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                
                  
                    $response = curl_exec($ch);   
                    curl_close($ch);
                            
                    return $response;

				}
			
		}
	
	     
	 }
	 elseif($Type==3)
	 {
	      	
	 
	     if($Title==1)
		{
			$title=" Payment Submitted ";
			$message=" Payment is submitted. ";
			
			                   	$type='Payment';
								$Payment_id=$Payment_id;
								$title='Payment submitted';
								$message='Payment submitted';
				
					if ($row_counts > 0)
				{				
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$Payment_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                    	
							$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
			            	  $notification = [
			            	                       'notificationid' =>$InsertOtherDtlsRecord,
                                                    'type' =>'Payment',
                                                    'id' => $Payment_id,
                                        			 'title' => $title,
                                                'message' => $message
                                                   
                 
                                                ]; 
                                                $token=$token;
                    	
                      
                            $fcmNotification = [
                                
                               'to' => $token,"data"=>$notification
                                         
                            ];
                            
                            $headers = [
                                'Authorization: key='. API_ACCESS_KEY,
                                'Content-Type: application/json'
                            ];
                    
                    	
                    	
                    	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                        $json = json_encode($fcmNotification);
                       
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                    
                      
                        $response = curl_exec($ch);   
                        curl_close($ch);
                            
                        
                        return $response;
				}
			
		}
		elseif($Title==2)
		{
			$title="Payment Pending ";
			$message=" Payment is pending.";
			
			
			
			                   	$type='Payment';
								$Payment_id=$Payment_id;
								$title='Payment submitted';
								$message='Payment submitted';
							if ($row_counts > 0)
				{
								
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$Payment_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                    	
							$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
			            	  $notification = [
			            	                       'notificationid' =>$InsertOtherDtlsRecord,
                                                    'type' =>'Payment',
                                                    'id' => $Payment_id,
                                        			 'title' => $title,
                                                'message' => $message
                                                   
                                                ];
                                                $token=$token;
                    	
                      
                            $fcmNotification = [
                                
                               'to' => $token,"data"=>$notification
                                         
                            ];
                            
                            $headers = [
                                'Authorization: key='. API_ACCESS_KEY,
                                'Content-Type: application/json'
                            ];
                    
                    	
                    	
                    	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                        $json = json_encode($fcmNotification);
                       
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                    
                      
                        $response = curl_exec($ch);   
                        curl_close($ch);
                            
                        
                        return $response;
			
				}
		}
	
 
                    	
	
	 }
	 elseif($Type==4)
	 {
	     $Get_docs=mysql_query("SELECT * FROM sp_documetns_list WHERE document_list_id='$Document_id'");
    		$docs_data = mysql_fetch_array($Get_docs);
    		$Name=$docs_data['Documents_name'];
        
	 
       if($Title==1)
		{
			$title="Document Accepted ";
			$message="Your $Name document is accepted.";
			$type='Document';
			$Document_id=$Document_id;
			
		    	
				
			if ($row_counts > 0)
        	{			
				$args['type']=$type;
            	$args['professional_id']=$Professional_id;
            	$args['title']=$title;
            	$args['message']=$message;
            	$args['notification_detail_id']=$Document_id;
            	$args['added_date']=$added_date;
            	$args['last_modify_date']=$added_date;
        	
		    	$InsertOtherDtlsRecord = $professionalsClass->API_AddProfessional_notification($args);
		    	
		    
				if (!empty($InsertOtherDtlsRecord))	 {
				    $notification = [
			       'notificationid' => $InsertOtherDtlsRecord,
                        'type'      => 'Document',
                        'id'        => $Document_id,
            			'title'     => $title,
                        'message'   => $message
                       
                    ]; 	
                    $token=$token;
                    
                    $fcmNotification = [
                        
                       'to' => $token,"data"=>$notification
                                 
                    ];
                    $headers = [
                        'Authorization: key='. API_ACCESS_KEY,
                        'Content-Type: application/json'
                    ];

                	$ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                   
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                    $response = curl_exec($ch);   
                    curl_close($ch);
                    return $response;
				}
        	}
			
		}
		elseif($Title==2)
		{
			$title="Document Rejected ";
		
			
	      	    $msgconten = str_replace(array( '\'', '"','[',']'), ' ', $sub_data);
	        	$notificaton_message = preg_replace('/\s+/', ' ', $message);
	        	$notificaton_message = preg_replace('/\s*,/', ',', $notificaton_message);
	             $notificaton_message=rtrim($notificaton_message);
	             $notificaton_message="$notificaton_message.";
	        
			$message="Your $Name document is rejected. $rejection_reason.";
			
				$type='Document';
				$Document_id=$Document_id;
				if ($row_counts > 0)
				{	
								
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$Document_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                    	
							$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
										            	
							  $notification = [
							       'notificationid' =>$InsertOtherDtlsRecord,
                                                'type' =>'Document',
                                                'id' => $Document_id,
                                    			  'title' => $title,
                                                'message' => $message
                                               
                                            ]; 
                                             $token=$token;
                
                  
                       
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                
                	
                	
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                   
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                
                  
                    $response = curl_exec($ch);   
                    curl_close($ch);
                    
                    return $response;
                 
				}
		}
			elseif($Title==3)
		{
			$title="Documents Approved ";
			$message="All the documents you have uploaded are approved.";
			
				$type='Document';
				$Document_id=$Document_id;
							
				if ($row_counts > 0)
				{		
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$Document_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                    	
							$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
										            	
							  $notification = [
							       'notificationid' =>$InsertOtherDtlsRecord,
                                                'type' =>'Document',
                                                'id' => $Document_id,
                                    			  'title' => $title,
                                                'message' => $message
                                               
                                            ]; 	
                                             $token=$token;
                
                  
                       
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                    $response = curl_exec($ch);   
                    curl_close($ch);
                    
                    return $response;
                                            
				}
		}
	 
	 
                
               
	 }
	 elseif($Type==5)
	 {
	    	$id = $data->id;
			$details = $data->details;
			$locationAddress = $data->locationAddress;
			$patientCount = $data->patientCount;
			$location = $data->location;
	    	$latitude = $location->latitude;
	    	$longitude = $location->longitude;
 
                $token=$token;
                
                    $notification = [
                                         'type' =>'Ambulance',
                                        'id' =>$id,
                                        'details' => $details,
                                    	 'locationAddress' =>$locationAddress,
                                         'patientCount' => $patientCount,
                                           'location' =>[
                                        'latitude' => $latitude,
                                    	 'longitude' =>$longitude
                                    ]
                                               
                                 ]; 
                               
                       
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                    $json = json_encode($fcmNotification);
                 
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                
                    $response = curl_exec($ch);   
                    curl_close($ch);
                    return $response;

	 }
	  elseif($Type==6)
	 {
	        $Get_seesion_details=mysql_query("SELECT * FROM sp_reschedule_session WHERE reschedule_session_id='$reschedule_session_id'");
    		$Get_seesion_details_arr = mysql_fetch_array($Get_seesion_details);
    		$detail_plan_of_care_id=$Get_seesion_details_arr['detail_plan_of_care_id'];
    		$session_start_date=$Get_seesion_details_arr['session_start_date'];
    		$session_end_date=$Get_seesion_details_arr['session_end_date'];
    		$event_id=$Get_seesion_details_arr['event_id'];
    		
    		$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
			$sql11 = mysql_fetch_array($sql1);
			$event_code=$sql11['event_code'];
			$patient_id=$sql11['patient_id'];
		
    		$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'");
    		$patient_nm = mysql_fetch_array($patient_nm);
    		$Patient_name=$patient_nm['name'];
    		$Patient_first_name=$patient_nm['first_name'];
    		$Patient_middle_name=$patient_nm['middle_name'];
    		$patient_full_name=$Patient_first_name.' '.$Patient_middle_name.' '.$Patient_name;
    		$seesion_date=date('d-m-Y', strtotime($session_start_date));
    		$service_start_date=date('H:i:s', strtotime($session_start_date));
			$service_end_date=date('H:i:s', strtotime($session_end_date));
			
		
			$start_date_ampm= date('h:i a', strtotime($service_start_date));
			$end_date_ampm= date('h:i a', strtotime($service_end_date));

	     
	 
			if($Title==1)
			{
				$title="Reschedule Request Accepted";
				$message="Session rescheduled request for $patient_full_name on $seesion_date, $start_date_ampm to $end_date_ampm is accepted.";
				$type='Reschedule';
				$Event_id=$Event_id;
				
				if ($row_counts > 0)
				{				
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$reschedule_session_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                    	
					$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
                    $token=$token;
                    $notification = [
                         'notificationid' =>$InsertOtherDtlsRecord,
                            'type' =>'Reschedule',
                            'id' => $reschedule_session_id,
                			 'title' => $title,
                            'message' => $message
                           
                        ]; 
                        
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                     $json = json_encode($fcmNotification);
                     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                     curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                     $response = curl_exec($ch);  
                     curl_close($ch);
                     return $response;
                   
                        
				}
			}    
	    	if($Title==2)
			{
				$title="Reschedule Request Rejected";
				$message="Session rescheduled request for $patient_full_name on $seesion_date, $start_date_ampm to $end_date_ampm is rejected.";
				$type='Reschedule';
				$Event_id=$Event_id;
				
				if ($row_counts > 0)
				{				
								$args['type']=$type;
    	                    	$args['professional_id']=$Professional_id;
    	                    	$args['title']=$title;
    	                    	$args['message']=$message;
	                        	$args['notification_detail_id']=$reschedule_session_id;
	                        	$args['added_date']=$added_date;
	                        	$args['last_modify_date']=$added_date;
	                    	
					$InsertOtherDtlsRecord=$professionalsClass->API_AddProfessional_notification($args);
                    $token=$token;
                    $notification = [
                         'notificationid' =>$InsertOtherDtlsRecord,
                            'type' =>'Reschedule',
                            'id' => $reschedule_session_id,
                			 'title' => $title,
                            'message' => $message
                           
                        ]; 
                        
                        $fcmNotification = [
                            
                           'to' => $token,"data"=>$notification
                                     
                        ];
                        $headers = [
                            'Authorization: key='. API_ACCESS_KEY,
                            'Content-Type: application/json'
                        ];
                	 $ch = curl_init("https://fcm.googleapis.com/fcm/send");   
                     $json = json_encode($fcmNotification);
                     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                     curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                     curl_setopt($ch, CURLOPT_HTTPHEADER,$headers); 
                     $response = curl_exec($ch);  
                     curl_close($ch);
                     return $response;
                   
                        
				}
			}    

	 }
	 
	 
   ?>