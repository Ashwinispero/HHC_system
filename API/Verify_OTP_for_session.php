<?php 
 require_once('config.php');


// this goes just before redirect line


if($_SERVER['REQUEST_METHOD']=='POST')
{
  	 if(isset($_COOKIE['id']))
			 {
			     
			     date_default_timezone_set("Asia/Calcutta");
			$data = json_decode(file_get_contents('php://input'));
					$professional_vender_id=$_COOKIE['id'];
					$Session_id = $data->id;
					$OTP = $data->otp;
					$device_id=$_COOKIE['device_id'];
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_vender_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
						 if($Session_id == '' || $OTP == '' )
							{
								
							
								http_response_code(400);
								
							}

						else
						{
							
							  
								$sqls = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id' ");
								$row_count = mysql_num_rows($sqls);
								if ($row_count > 0)
								{
									
									$result = mysql_fetch_array($sqls);
									$realotp = $result['OTP'];
									$OTP_count = $result['OTP_count'];
									$otp_expire_time = $result['otp_expire_time'];
									
									
									 $current_time = date('Y-m-d H:i:s');
			  
			  	if($current_time >= $otp_expire_time)
			  	{
				
			        	 echo json_encode(array("data"=>null,"error"=>array("code"=>2,"message"=>"OTP expired")));
			    	 	$sqlqy = mysql_query("UPDATE sp_detailed_event_plan_of_care SET  OTP = '' , OTP_count= 0 WHERE Detailed_plan_of_care_id ='$Session_id'");
				
		    	}
				else
				{
				    
							
									
									if($OTP_count >2)
									{
			                	$zero=Null;
			                	echo json_encode(array("data"=>null,"error"=>array("code"=>3,"message"=>"Too many attempts")));
				
			                	$sqlqy = mysql_query("UPDATE sp_detailed_event_plan_of_care SET  OTP = '' , OTP_count= 0 WHERE Detailed_plan_of_care_id ='$Session_id'");
			                	
		                    	}
								else if($realotp == '')
									{

										echo json_encode(array("data"=>null,"error"=>array("code"=>2,"message"=>"OTP expired")));
									}
									
									else if($OTP == $realotp)
									{
										
										$sql = mysql_query("UPDATE sp_detailed_event_plan_of_care SET  OTP  =  '' WHERE Detailed_plan_of_care_id ='$Session_id'");
									       	if($sql)
									            	{	
									            	    $sql_amount_prof = mysql_query("SELECT * FROM sp_payments_received_by_professional where  Session_id='$Session_id' AND professional_vender_id='$professional_vender_id' AND OTP_verifivation='1' AND payment_status='1' ");
        						     					$sql_amount_prof_array = mysql_fetch_array($sql_amount_prof);
                    								
                    									$amount_received = $sql_amount_prof_array['amount'];
                    								
    																				   $status=2;
																			    	$sqlqy = mysql_query("UPDATE sp_detailed_event_plan_of_care SET  Session_status = '$status' WHERE Detailed_plan_of_care_id ='$Session_id'");
																					$sql_up = mysql_query("UPDATE sp_payments_received_by_professional SET  OTP_verifivation  =  '1' WHERE Session_id ='$Session_id'");
																				
																					$sql_amount = mysql_query("SELECT * FROM sp_bank_details where  Professional_id='$professional_vender_id'  ");
                                                        						  	$sql_amount_array = mysql_fetch_array($sql_amount);
                                                        								
                                                        						    	$Amount_with_me = $sql_amount_array['Amount_with_me'];
                                                        						    	$Amount_with_me=(int)$Amount_with_me;
                                                        						    	$amount_received=(int)$amount_received;

                                                        							$Amount_with_me=$amount_received + $Amount_with_me ;
									                                               	
																					$sql_amount_update = mysql_query("UPDATE sp_bank_details SET  Amount_with_me  =  '$Amount_with_me' WHERE Professional_id ='$professional_vender_id'");
																					
																					if (mysql_affected_rows() > 0) 
																					{
																					    	$sqlqy = mysql_query("UPDATE sp_detailed_event_plan_of_care SET  amount_received = '' WHERE Detailed_plan_of_care_id ='$Session_id'");
                                                                                                       
                                                                                      }
                                                                                                    
																					
																					echo json_encode(array("data"=>null,"error"=>null));
																
																			
														
											
							                	}
								
							 }
							 else
										{
											$Set_limit = mysql_query("UPDATE sp_detailed_event_plan_of_care SET OTP_count = OTP_count + 1 WHERE Detailed_plan_of_care_id ='$Session_id' ");
											
											echo json_encode(array("data"=>null,"error"=>array("code"=>1,"message"=>"Invalid OTP")));
										}
				}					 
}
else
							 {
								 http_response_code(401);
							 }
}
			 }
			 }
}
else
{
	http_response_code(405); 
			 
			
}


?>