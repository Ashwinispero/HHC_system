<?php
require_once 'classes/eventClass.php';
//require_once 'classes/commonClass.php';
$eventClass=new eventClass();
include('config.php');
         
		 if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
			 if(isset($_COOKIE['id']))
			 {
				 date_default_timezone_set('Asia/Kolkata'); 
			$added_date=date('Y-m-d H:i:s');
			$data = json_decode(file_get_contents('php://input'));
			$Session_id = $data->id;
			$dateTimes = $data->dateTimes;
			$subServiceId = $data->subServices;
			$serviceId = $data->serviceId;		
			$professional_vender_id=$_COOKIE['id'];
			//$count==0;	
		foreach($dateTimes as $key=>$valServices)
		{
			
			 $fromDate = mysql_real_escape_string($valServices->startDateTime);
			 $toDate = mysql_real_escape_string($valServices->endDateTime);
			 $startTime = mysql_real_escape_string($valServices->startTime);
			 $endTime = mysql_real_escape_string($valServices->endTime);
		
				
		}
		
			
		
		$plan_of_cares=mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id'");
				
					 $plan_of_care_detail=mysql_fetch_array($plan_of_cares);
						
								$event_id=$plan_of_care_detail['event_id'];								
								$Actual_Service_date=$plan_of_care_detail['Actual_Service_date'];
								$event_requirement_id=$plan_of_care_detail['event_requirement_id'];
								$startDateTime= $plan_of_care_detail['start_date'];
								$endDateTime  = $plan_of_care_detail['end_date']; 
					
									$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
									$sql11 = mysql_fetch_array($sql1);
									$event_code=$sql11['event_code'];
									$patient_id=$sql11['patient_id'];
									$caller_id=$sql11['caller_id'];
									$relation=$sql11['relation'];
									$hospital_id=$sql11['hospital_id'];
									$added_by=$sql11['added_by'];
									
									$service_info=mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_id'");
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
									
										$cost=$sub_service_row['cost'];								
								
					$prof_name= mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_vender_id' ");		
					$prof_name_array = mysql_fetch_array($prof_name);
					$name=$prof_name_array['name'];
					$first_name=$prof_name_array['first_name'];
					$middle_name=$prof_name_array['middle_name'];
					
					$prof_full_name=$first_name.' '.$middle_name.' '.$name;
		
					$session_info=mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where Detailed_plan_of_care_id='$Session_id' AND  professional_vender_id='$professional_vender_id'  ");
										
					$session_arr=mysql_fetch_array($session_info);
						
						$event_requirement_id=$session_arr['event_requirement_id'];
						$event_id=$session_arr['event_id'];
							$plan_of_care_id=$session_arr['plan_of_care_id'];
							
							$session_infos=mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where event_requirement_id='$event_requirement_id'   ");
						
						 		while($session_infoss=mysql_fetch_array($session_infos))
								{
										$index_of_Session=$session_infoss['index_of_Session'];
								}
				
						   $GetMaxRecordIdSql=mysql_query("SELECT MAX(event_id) AS MaxId FROM sp_events");
						$GetMaxRecordIdSql_rows = mysql_num_rows($GetMaxRecordIdSql);
				
						$MaxRecord=mysql_fetch_array($GetMaxRecordIdSql);
						$getMaxRecordId=$MaxRecord['MaxId'];
					
						
					
							$prefix='E';
							$EventCode=Generate_Number($prefix,$getMaxRecordId);
					
            		    	$GetMaxbillIdSql=mysql_query("SELECT MAX(bill_no_ref_no) as bill_no_ref_no FROM sp_events");
            				$row = mysql_fetch_array($GetMaxbillIdSql);
            				$Maxbillid=$row['bill_no_ref_no'];
            				$branch_code='DMH';
                            $createEvent['purpose_id'] = $arg['purpose_id'];
			
				
				
			
    					    $date_from = $fromDate;   
    						$date_from = strtotime($date_from);
    						$date_to =  $toDate;  
    						$date_to = strtotime($date_to);
    
								
				    	$count=0;
				

					foreach($subServiceId as $key=>$sub_service_id)
					{
									
						$sub_service_info=mysql_query("SELECT * FROM sp_sub_services where sub_service_id='$sub_service_id'");
										$sub_service_row = mysql_fetch_array($sub_service_info);
										$sub_service_id=$service_info['sub_service_id'];
									
										$cost=$sub_service_row['cost'];
										
								
						for ($i=$date_from; $i<=$date_to; $i+=86400)
					{ 
						$count++;	
					}			
							
					
					}
									
							$final_cost=$cost*$count;	
						
							
									$arc['event_code']=$EventCode;
								    $arc['caller_id']=$caller_id;
									$arc['relation']=Professional;
									$arc['bill_no_ref_no'] = $Maxbillid + 1;
									$arc['patient_id']=$patient_id;
									$arc['purpose_id']=2;
									$arc['event_date']=$fromDate;
									$arc['service_date_of_Enquiry']=$added_date;
									$arc['enquiry_status']=1;
									$arc['status']=2;
									$arc['finalcost']=$final_cost;								
									$arc['added_by']=$added_by;
									$arc['last_modified_by']=$professional_vender_id;
									$arc['last_modified_date']=$added_date;
									$arc['branch_code']=DMH;
									$arc['hospital_id']=$hospital_id;
									$arc['OTP']=$otp;														
					            	$arc['added_date']=$added_date;
					            	$arc['note']="This event is Added by Professional $prof_full_name" ;
									$InsertRecord=$eventClass->API_Add_event($arc);
										$event_id_new=mysql_insert_id();
							
						foreach($subServiceId as $key=>$sub_service_id)
								{
								
									$arr['event_id']=$event_id_new;									
									$arr['service_id']=$serviceId;									
									$arr['sub_service_id']=$sub_service_id;																	
									//$arr['added_by']=$professional_vender_id;
								//	$arr['last_modified_by']=$professional_vender_id;
									$arr['last_modified_date']=$added_date;
									$arr['added_date']=$added_date;
									
										$InsertRecord=$eventClass->API_Add_event_requirements($arr);
										$event_req_id_new=mysql_insert_id();
										
										
									$arrs['event_id']=$event_id_new;
									$arrs['event_requirement_id']=$event_req_id_new;
									$arrs['service_date']=$fromDate;
									$arrs['service_date_to']=$toDate;									
									$arrs['start_date']=$startTime;
									$arrs['end_date']=$endTime;
									$arrs['added_date']=$added_date;									
									$arrs['added_by']=$professional_vender_id;
									$arrs['last_modified_by']=$professional_vender_id;									
									$arrs['last_modified_date']=$added_date;
									$arrs['status']=2;
									
									
										$InsertRecord=$eventClass->API_Add_plan_of_care($arrs);
										 	$event_plan_of_care_new=mysql_insert_id();                                        

								
								}	
							$session_infos=mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where event_requirement_id='$event_requirement_id' AND  professional_vender_id='$professional_vender_id'   ");
						
						while($session_infoss=mysql_fetch_array($session_infos))
						{
										$index_of_Session=$session_infoss['index_of_Session'];
						}								
					for ($i=$date_from; $i<=$date_to; $i+=86400)
						{
								$index_of_Session++;		
								$date = date('Y-m-d H:i:s', $i);
								$combinedDT = date('Y-m-d ', strtotime("$date"));
								$combinedDTs = date('H:i:s', strtotime("$startTime"));
								$fromDatest = date('Y-m-d H:i:s', strtotime("$combinedDT $combinedDTs"));
							   $combinedet = date('Y-m-d ', strtotime("$date"));
							   $combinedeTs = date('H:i:s', strtotime("$endTime"));
							   $fromDatet = date('Y-m-d H:i:s', strtotime("$combinedet $combinedeTs"));
									$args['event_id']=$event_id_new;
									$args['event_requirement_id']=$event_req_id_new;
									$args['plan_of_care_id']=$event_plan_of_care_new;
									$args['index_of_Session']=$index_of_Session;
									$args['professional_vender_id']=$professional_vender_id;										
									$args['service_date']=$fromDate;
									$args['service_date_to']=$toDate;
									$args['Actual_Service_date']=$fromDatest;
									$args['start_date']=$fromDatest;
									$args['end_date']=$fromDatet;
									$args['added_date']=$added_date;									
									$args['last_modified_date']=$added_date;
									
									$InsertRecord=$eventClass->API_Extend_services($args);
									
					}
					 $out=(array("data"=>null,"error"=>null));
						echo json_encode($out);
					
				
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