<?php 

        
    require_once 'classes/professionalsClass.php';
    
    $professionalsClass=new professionalsClass();
	include('config.php');
			  
    
	 
    $data = json_decode(file_get_contents('php://input'));
	date_default_timezone_set("Asia/Calcutta");
    $mobileNumber = $data->mobileNumber;
    $password = $data->password;

    
	$roleId = $data->roleId;
	$device_id=$data->deviceId;
	$added_date=date('Y-m-d H:i:s');

	$status=1;

		  
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		if(!empty($mobileNumber) && !empty($password) && !empty($device_id) && !empty($roleId))
		{
			if($roleId == 1)
			{
				
				$Query= mysql_query("SELECT * FROM sp_service_professionals WHERE mobile_no = '$mobileNumber' AND APP_password = '$password'  ");
				$row_count = mysql_num_rows($Query);
				if ($row_count > 0)
				{
					
				
					$Query_row = mysql_fetch_array($Query);
					$service_professional_id=$Query_row['service_professional_id'];
					$name=$Query_row['name'];
					
					$first_name=$Query_row['first_name'];
					$middle_name=$Query_row['middle_name'];
					$email_id=$Query_row['email_id'];
					$mobile_no=$Query_row['mobile_no'];
					$title=$Query_row['title'];
					
					$dob=$Query_row['dob'];
					$address=$Query_row['address'];
					$work_address=$Query_row['work_address'];
					$work_phone_no=$Query_row['work_phone_no'];
					$work_email_id=$Query_row['work_email_id'];
					$dob=$Query_row['dob'];
					$Profile_pic=$Query_row['Profile_pic'];
					$Ratings=$Query_row['Ratings'];
					$Reviews=$Query_row['Reviews'];
					$Profile_pic=$PROF_PROFILE_PIC_URL.$Profile_pic;
				
					$Ratings=(int)$Ratings;
					$Reviews=(int)$Reviews;
					
					$Description=$Query_row['Description'];
					$OTP_verification=$Query_row['OTP_verification'];
				$status_query= mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$service_professional_id' AND (status='2' OR status='3' )");
				$status_query_count = mysql_num_rows($status_query);
				if ($status_query_count > 0)
				{
					echo json_encode(array("data"=>null,"error"=>array(  "code"=>4,"message"=>"Your Account is deactivated, Contact Admin on 7620400100")));	
				 
					
				}
				else
				{
					$sqls= mysql_query("SELECT * FROM sp_professional_services WHERE service_professional_id = '$service_professional_id' AND status=1 ");
					
					$row = mysql_fetch_array($sqls);
					{
						
					$service_id=$row['service_id'];
					$service_id=(int)$service_id;
					
					$sql_query= mysql_query("SELECT * FROM sp_services WHERE service_id = '$service_id'  ");
					
				
					$rowSS = mysql_fetch_array($sql_query);
					$service_title=$rowSS['service_title'];
					
					
					$types_prof=array('id'=>$service_id,
									'name'=>$service_title);
					
						$p_data=$types_prof;
					
					
					
					$Sub_service= mysql_query("SELECT * FROM sp_professional_sub_services WHERE  service_professional_id=$service_professional_id ");
					while($Sub_services = mysql_fetch_array($Sub_service))
					{
					$Sub_service_id=$Sub_services['sub_service_id'];
					$Sub_service_id=(int)$Sub_service_id;
					
					$Sub_service_name= mysql_query("SELECT * FROM sp_sub_services WHERE  sub_service_id=$Sub_service_id ");
					$Sub_servicesss = mysql_fetch_array($Sub_service_name);
					$Sub_service_names=$Sub_servicesss['recommomded_service'];
					
					$types_profs[]=array('id'=>$Sub_service_id,
									'name'=>$Sub_service_names);
					
					
					}
					
					$sub_data=$types_profs;
					
					}
					
					$sql= mysql_query("SELECT * FROM sp_service_professional_details WHERE service_professional_id = '$service_professional_id'");
					$rows = mysql_fetch_array($sql);
					$qualification=$rows['qualification'];
						$designation=$rows['designation'];
					$specialization=$rows['specialization'];
					$skill_set=$rows['skill_set'];
					$work_experience=$rows['work_experience'];
				    $work_experience = (float)$work_experience;
					$hospital_attached_to=$rows['hospital_attached_to'];
					$pancard_no=$rows['pancard_no'];
				
					
					$Professional_Name=$first_name.' '.$middle_name.' '.$name;
					
						
					$session_active=mysql_query("SELECT * FROM sp_session WHERE service_professional_id='$service_professional_id' AND status=1 ");
					$active_session_count = mysql_num_rows($session_active);
					
					$session=mysql_query("SELECT * FROM sp_session WHERE service_professional_id='$service_professional_id'  ");
					$session_count = mysql_num_rows($session);
					if($OTP_verification==1)
						{
									$Query= mysql_query("SELECT * FROM sp_service_professionals WHERE mobile_no = '$mobileNumber' ");
								$row_count = mysql_num_rows($Query);
								if ($row_count > 0)
								{
									$Query_row = mysql_fetch_array($Query);
									$service_professional_id=$Query_row['service_professional_id'];
										$mobile_no=$Query_row['mobile_no'];
										
											$otp = rand(1000, 9999);
											$sql = mysql_query("Update sp_service_professionals set OTP='".$otp."' where mobile_no='".$mobile_no."' ");
												$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
												$txtMsg='';
																		
																		
												$txtMsg .= " Spero Home Healthcare ";
												$txtMsg .= " OTP for account activation ".$otp;
												$txtMsg .= " OTP is validate for one time password generation ";
												
												
																		
												$data_to_post = array();
												$data_to_post['uname'] = 'SperocHL';
												$data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
												$data_to_post['send'] = 'speroc';
												$data_to_post['dest'] = $mobile_no; 
												$data_to_post['msg'] = $txtMsg;
																		
												$curl = curl_init();
												curl_setopt($curl,CURLOPT_URL, $form_url);
												curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
												curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
												$result = curl_exec($curl);
												curl_close($curl);
											
									echo json_encode(array("data"=>null,"error"=>array(  "code"=>3, "message"=>"Your account is not activated")));	
				 
							}
						}
				
				else if($session_count >0)
					{
						
						while($Query_rows = mysql_fetch_array($session))
						{
						$professional_device_id=$Query_rows['device_id'];
						$status=$Query_rows['status'];
						
						}
					
						if($status==1 AND $professional_device_id==$device_id)
						{
							$service_professional_id=$Query_row['service_professional_id'];
							$name=$Query_row['name'];
							
							$first_name=$Query_row['first_name'];
							$middle_name=$Query_row['middle_name'];
							$email_id=$Query_row['email_id'];
							$mobile_no=$Query_row['mobile_no'];
							$title=$Query_row['title'];
							
							$dob=$Query_row['dob'];
							$address=$Query_row['address'];
							$work_address=$Query_row['work_address'];
							$work_phone_no=$Query_row['work_phone_no'];
							$work_email_id=$Query_row['work_email_id'];
							$dob=$Query_row['dob'];
							$Profile_pic=$Query_row['Profile_pic'];
							$Profile_pic=$PROF_PROFILE_PIC_URL.$Profile_pic;
							$Ratings=$Query_row['Ratings'];
							$Reviews=$Query_row['Reviews'];
								$Ratings=(int)$Ratings;
								$Reviews=(int)$Reviews;
								
								$id=(int)$service_professional_id;
								$user = array(
									'id'=>$id,
									'title'=>$title, 
									'lastName'=>$name, 
									'firstName'=>$first_name, 
									'middleName'=>$middle_name,	
									'assignedServices'=>$p_data,
									'subServices'=>$sub_data,									
									'email'=>$email_id,
									'mobileNumber'=>$mobile_no,
									'Date_of_birth'=>$dob, 
																		
									'homeAddress'=>$address,
									'workAddress'=>$work_address, 
									'workPhone'=>$work_phone_no, 
									'workEmail'=>$work_email_id,
									
									'qualification'=>$qualification, 
									'specialization'=>$specialization, 
									'skills'=>$skill_set,
									'workExpereince'=>$work_experience,
									'hospital'=>$hospital_attached_to, 
									'designation'=>$designation, 
									'pan'=>$pancard_no,
									
									'aboutMe'=>$Description, 
									'ratings'=>$Ratings, 
									'numberOFReviews'=>$Reviews,
									'profilePictureUrl'=>$Profile_pic
									
								);
															
								
								$cookie_name = "id";
								$cookie_value = $service_professional_id;
								setcookie($cookie_name, $cookie_value); 
								$result=array("data"=>$user,"error"=>null);
								
								$cookie_device_name = "device_id";
								$cookie_device_value = $deviceId;
								setcookie($cookie_device_name, $cookie_device_value);
								
								echo json_encode($result);
						
						}
						else if($status==2 AND $professional_device_id==$device_id)
						{
							
								$service_professional_id=$Query_row['service_professional_id'];
							$name=$Query_row['name'];
							
							$first_name=$Query_row['first_name'];
							$middle_name=$Query_row['middle_name'];
							$email_id=$Query_row['email_id'];
							$mobile_no=$Query_row['mobile_no'];
							$title=$Query_row['title'];
							
							$dob=$Query_row['dob'];
							$address=$Query_row['address'];
							$work_address=$Query_row['work_address'];
							$work_phone_no=$Query_row['work_phone_no'];
							$work_email_id=$Query_row['work_email_id'];
							$dob=$Query_row['dob'];
							$Profile_pic=$Query_row['Profile_pic'];
							$Profile_pic=$PROF_PROFILE_PIC_URL.$Profile_pic;	
							$Ratings=$Query_row['Ratings'];
							$Reviews=$Query_row['Reviews'];
								$Ratings=(int)$Ratings;
								$Reviews=(int)$Reviews;
								
								
								$id=(int)$service_professional_id;
								$user = array(
									'id'=>$id,
									'title'=>$title, 
									'lastName'=>$name, 
									'firstName'=>$first_name, 
									'middleName'=>$middle_name,	
									'assignedServices'=>$p_data,
									'subServices'=>$sub_data,									
									'email'=>$email_id,
									'mobileNumber'=>$mobile_no,
									'Date_of_birth'=>$dob, 
																		
									'homeAddress'=>$address,
									'workAddress'=>$work_address, 
									'workPhone'=>$work_phone_no, 
									'workEmail'=>$work_email_id,
									
									'qualification'=>$qualification, 
									'specialization'=>$specialization, 
									'skills'=>$skill_set,
									'workExpereince'=>$work_experience,
									'hospital'=>$hospital_attached_to, 
								'designation'=>$designation,
									'pan'=>$pancard_no,
									
									'aboutMe'=>$Description, 
									'ratings'=>$Ratings, 
									'numberOFReviews'=>$Reviews,
									'profilePictureUrl'=>$Profile_pic
									
								);
								
							$added_date=date('Y-m-d H:i:s');
								$status=1;
								$query_session= mysql_query("SELECT * FROM sp_session WHERE device_id='$device_id' AND service_professional_id='$service_professional_id'  ");
				$query_session_row = mysql_num_rows($query_session);
				
				if ($query_session_row > 0)
				{
					 $Loc_query=mysql_query("UPDATE sp_session SET status=1,last_modify_date='$added_date' WHERE  device_id='$device_id' AND service_professional_id='$service_professional_id' ");
				}
				else
				{
				    	
								$args['device_id']=$device_id;
    	                    	$args['service_professional_id']=$service_professional_id;
    	                    	$args['added_date']=$added_date;
    	                    	$args['status']=$status;
	                        
				    	$InsertRecord=$professionalsClass->API_AddSession($args);
							//	$query=mysql_query("insert into sp_session() VALUES('','$device_id','$service_professional_id','$added_date','$status')");
				}
						
		
								$cookie_name = "id";
								$cookie_value = $service_professional_id;
								setcookie($cookie_name, $cookie_value); // 86400 = 1 day
								$result=array("data"=>$user,"error"=>null);
								
								$cookie_device_name = "device_id";
								$cookie_device_value = $device_id;
								setcookie($cookie_device_name, $cookie_device_value);
							
								echo json_encode($result);
								
						}
						elseif($active_session_count >0)
						{
							
									$cookie_name = "id";
								$cookie_value = $service_professional_id;
								setcookie($cookie_name, $cookie_value); 
								
									$cookie_device_name = "device_id";
								$cookie_device_value = $deviceId;
								setcookie($cookie_device_name, $cookie_device_value);
						
							echo json_encode(array("data"=>null,"error"=>array(  "code"=>1, "message"=>"You have logged in to maximum number of devices allowed")));
						}
						else 
						{
							
							$service_professional_id=$Query_row['service_professional_id'];
							$name=$Query_row['name'];
							
							$first_name=$Query_row['first_name'];
							$middle_name=$Query_row['middle_name'];
							$email_id=$Query_row['email_id'];
							$mobile_no=$Query_row['mobile_no'];
							$title=$Query_row['title'];
							
							$dob=$Query_row['dob'];
							$address=$Query_row['address'];
							$work_address=$Query_row['work_address'];
							$work_phone_no=$Query_row['work_phone_no'];
							$work_email_id=$Query_row['work_email_id'];
							$dob=$Query_row['dob'];
							$Profile_pic=$Query_row['Profile_pic'];
							$Profile_pic=$PROF_PROFILE_PIC_URL.$Profile_pic;
							$Ratings=$Query_row['Ratings'];
							$Reviews=$Query_row['Reviews'];
								$Ratings=(int)$Ratings;
								$Reviews=(int)$Reviews;
								
								$id=(int)$service_professional_id;
								$user = array(
									'id'=>$id,
									'title'=>$title, 
									'lastName'=>$name, 
									'firstName'=>$first_name, 
									'middleName'=>$middle_name,	
									'assignedServices'=>$p_data,'subServices'=>$sub_data,
									'email'=>$email_id,
									'mobileNumber'=>$mobile_no,
									'Date_of_birth'=>$dob, 
																		
									'homeAddress'=>$address,
									'workAddress'=>$work_address, 
									'workPhone'=>$work_phone_no, 
									'workEmail'=>$work_email_id,
									
									'qualification'=>$qualification, 
									'specialization'=>$specialization, 
									'skills'=>$skill_set,
									'workExpereince'=>$work_experience,
									'hospital'=>$hospital_attached_to, 
								'designation'=>$designation,
									'pan'=>$pancard_no,
									
									'aboutMe'=>$Description, 
									'ratings'=>$Ratings, 
									'numberOFReviews'=>$Reviews,
									'profilePictureUrl'=>$Profile_pic
									
								);
								
								$added_date=date('Y-m-d H:i:s');
								$status=1;
								$query_session= mysql_query("SELECT * FROM sp_session WHERE device_id='$device_id' AND service_professional_id='$service_professional_id'  ");
				$query_session_row = mysql_num_rows($query_session);
				
				if ($query_session_row > 0)
				{
					 $Loc_query=mysql_query("UPDATE sp_session SET status=1,last_modify_date='$added_date' WHERE  device_id='$device_id' AND service_professional_id='$service_professional_id' ");
				}
				else
				{
				    
				                 $args['device_id']=$device_id;
    	                    	$args['service_professional_id']=$service_professional_id;
    	                    	$args['added_date']=$added_date;
    	                    	$args['status']=$status;
	                        
				    	$InsertRecord=$professionalsClass->API_AddSession($args);
				    
				    
							//	$query=mysql_query("insert into sp_session() VALUES('','$device_id','$service_professional_id','$added_date','$status')");
				}
								
								
								
								$cookie_name = "id";
								$cookie_value = $service_professional_id;
								setcookie($cookie_name, $cookie_value); // 86400 = 1 day
								$result=array("data"=>$user,"error"=>null);
								
								$cookie_device_name = "device_id";
								$cookie_device_value = $device_id;
								setcookie($cookie_device_name, $cookie_device_value);
								
								echo json_encode($result);
								
						}
						
						
					}
					
					else
					{
								$service_professional_id=$Query_row['service_professional_id'];
							$name=$Query_row['name'];
							
							$first_name=$Query_row['first_name'];
							$middle_name=$Query_row['middle_name'];
							$email_id=$Query_row['email_id'];
							$mobile_no=$Query_row['mobile_no'];
							$title=$Query_row['title'];
							
							$dob=$Query_row['dob'];
							$address=$Query_row['address'];
							$work_address=$Query_row['work_address'];
							$work_phone_no=$Query_row['work_phone_no'];
							$work_email_id=$Query_row['work_email_id'];
							$dob=$Query_row['dob'];
							$Profile_pic=$Query_row['Profile_pic'];
							$Profile_pic=$PROF_PROFILE_PIC_URL.$Profile_pic;	
							$Ratings=$Query_row['Ratings'];
							$Reviews=$Query_row['Reviews'];
								$Ratings=(int)$Ratings;
								$Reviews=(int)$Reviews;
								
								$id=(int)$service_professional_id;
								
									$user = array(
									'id'=>$id,
									'title'=>$title, 
									'lastName'=>$name, 
									'firstName'=>$first_name, 
									'middleName'=>$middle_name,	
									'assignedServices'=>$p_data,
									'subServices'=>$sub_data,		
									'email'=>$email_id,
									'mobileNumber'=>$mobile_no,
									'Date_of_birth'=>$dob, 
																		
									'homeAddress'=>$address,
									'workAddress'=>$work_address, 
									'workPhone'=>$work_phone_no, 
									'workEmail'=>$work_email_id,
									
									'qualification'=>$qualification, 
									'specialization'=>$specialization, 
									'skills'=>$skill_set,
									'workExpereince'=>$work_experience,
									'hospital'=>$hospital_attached_to, 
							    	'designation'=>$designation, 
									'pan'=>$pancard_no,
									
									'aboutMe'=>$Description, 
									'ratings'=>$Ratings, 
									'numberOFReviews'=>$Reviews,
									'profilePictureUrl'=>$Profile_pic
									
								);
								
						     	$added_date=date('Y-m-d H:i:s');
								$status=1;
								$query_session= mysql_query("SELECT * FROM sp_session WHERE device_id='$device_id' AND service_professional_id='$service_professional_id'  ");
				$query_session_row = mysql_num_rows($query_session);
				
				if ($query_session_row > 0)
				{
					 $Loc_query=mysql_query("UPDATE sp_session SET status=1,last_modify_date='$added_date' WHERE  device_id='$device_id' AND service_professional_id='$service_professional_id' ");
				}
				else
				{
				    
				                $args['device_id']=$device_id;
    	                    	$args['service_professional_id']=$service_professional_id;
    	                    	$args['added_date']=$added_date;
    	                    	$args['status']=$status;
	                        
				    	$InsertRecord=$professionalsClass->API_AddSession($args);
				    
							//	$query=mysql_query("insert into sp_session() VALUES('','$device_id','$service_professional_id','$added_date','$status')");
				}
							$cookie_name = "id";
							$cookie_value = $service_professional_id;
							setcookie($cookie_name, $cookie_value); // 86400 = 1 day
						
							
								$cookie_device_name = "device_id";
								$cookie_device_value = $device_id;
								setcookie($cookie_device_name, $cookie_device_value);
								
									$result=array("data"=>$user,"error"=>null);
								
								echo json_encode($result);
					}
				}
				}
				
				else
				{
				    
					http_response_code(401); 
				}
				
				
			}
			else if($roleId == 2)
			{
				
				echo json_encode(array("data"=>null,"error"=>array("code"=>2,"message"=>"You are not allowed to log in as with this role ")));
			}
			else
			{
				
				echo json_encode(array("data"=>null,"error"=>array(  "code"=>2,"message"=>"You are not allowed to log in as with this role ")));
			}
		}
		else
	    {
		
	        http_response_code(400); 
		
		}	
	}
	else
	{
		http_response_code(405); 
		
	}			
	
?>