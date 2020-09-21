<?php 
require_once('config.php');


		if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
			$data = json_decode(file_get_contents('php://input'));
			$mobileNumber = $data->mobileNumber; 
			$otpFor = $data->otpFor; 
			date_default_timezone_set("Asia/Calcutta");
			 if($mobileNumber == '' )
				{
				
					http_response_code(400);
					
				}
			
			else if($otpFor==1)
			{
					$otp = rand(1000, 9999);
					
					
					$query= mysql_query("SELECT * FROM sp_service_professionals WHERE mobile_no='$mobileNumber'");
					$row_count = mysql_num_rows($query);
					
									if ($row_count > 0)
									{
								
									
									$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
									$txtMsg='';
									
									
									$txtMsg .= " Welcome to Spero Home HealthCare ,Your Account Verification OTP is : $otp .OTP is valid for 30 Minutes ";
												
									
									$data_to_post = array();
									$data_to_post['uname'] = 'SperocHL';
									$data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
									$data_to_post['send'] = 'speroc';
									$data_to_post['dest'] = $mobileNumber; 
									$data_to_post['msg'] = $txtMsg;
									
									$curl = curl_init();
									curl_setopt($curl,CURLOPT_URL, $form_url);
									curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
									curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
									$result = curl_exec($curl);
									curl_close($curl);
									
								
										 $current_time = date('Y-m-d H:i:s');
								   $OTP_timestamp = strtotime($current_time) + 30*60;
                        			$otp_expiry_time = date('Y-m-d H:i:s', $OTP_timestamp);
                        			
								$sql_OTP_update = mysql_query("Update sp_service_professionals set OTP='$otp',otp_expire_time='$otp_expiry_time' where mobile_no='".$mobileNumber."' ");
									 echo json_encode(array("data"=>null,"error"=>null));
								}
			}
								else if($otpFor==2)
								{
									$otp = rand(1000, 9999);
									
									$query= mysql_query("SELECT * FROM sp_service_professionals WHERE mobile_no='$mobileNumber'");
									$row_count = mysql_num_rows($query);
								
												if ($row_count > 0)
												{
											
												
												$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
												$txtMsg='';
												
												
										$txtMsg .= "OTP to reset password is : $otp. OTP is valid for 30 Minutes";
												
												$data_to_post = array();
												$data_to_post['uname'] = 'SperocHL';
												$data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
												$data_to_post['send'] = 'speroc';
												$data_to_post['dest'] = $mobileNumber; 
												$data_to_post['msg'] = $txtMsg;
												
												$curl = curl_init();
												curl_setopt($curl,CURLOPT_URL, $form_url);
												curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
												curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
												curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
												$result = curl_exec($curl);
												curl_close($curl);

										 $current_time = date('Y-m-d H:i:s');
								   $OTP_timestamp = strtotime($current_time) + 30*60;
                        			$otp_expiry_time = date('Y-m-d H:i:s', $OTP_timestamp);
                        			
								$sql_OTP_update = mysql_query("Update sp_service_professionals set OTP='$otp',otp_expire_time='$otp_expiry_time' where mobile_no='".$mobileNumber."' ");
								 echo json_encode(array("data"=>null,"error"=>null));
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