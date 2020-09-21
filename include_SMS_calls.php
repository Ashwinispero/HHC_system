<?php 

      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
	  
	  
	  require_once 'inc_classes.php';        
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";        
        include "classes/eventClass.php";
        $eventClass = new eventClass();
        include "classes/employeesClass.php";
        $employeesClass = new employeesClass();
	include "classes/professionalsClass.php";
        $professionalsClass = new professionalsClass();
        include "classes/consultantsClass.php";
        $consultantsClass = new consultantsClass();
        include "classes/commonClass.php";
        $commonClass= new commonClass();
        require_once 'classes/functions.php';
		
		$date = date('d-m-Y');
			$new_date=date('Y-m-d H:i:s', strtotime($date));
			$new_date1 = date('Y-m-d H:i:s', strtotime($new_date . ' +1 days'));
			$today_date=date('Y-m-d', strtotime($date));
			$Previous_date = date('Y-m-d H:i:s', strtotime($new_date . ' -35 days'));
			
		$Current_call=$_GET['flag'];
		
		
		$Physician_assistant=0;
			$Physiotherapy=0;
			$Healthcare_attendants=0;
			$Nurse=0;
			$Laboratory_services=0;
			$Respiratory_care=0;
			$X_rayat_home=0;
			$Hca_package=0;
			$Medical_transportation=0;
			$Physiotherapy_New=0;
			$Assisted_living=0;
			$Physician_service=0;
			$Maid_service=0;
			$Total_Services=0;
			
			$plan_of_care=mysql_query("SELECT * FROM sp_event_plan_of_care  where added_date BETWEEN '$Previous_date%' AND '$new_date1%'");
			while($plan_of_care_detail=mysql_fetch_array($plan_of_care))
			{
				$service_date=$plan_of_care_detail['service_date'];
				$service_date_to=$plan_of_care_detail['service_date_to'];
				$event_requirement_id=$plan_of_care_detail['event_requirement_id'];
					$professional_vender_id=$plan_of_care_detail['professional_vender_id'];
				
				$event_requirement=mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'") or die(mysql_error());
				$event_requirement_row = mysql_fetch_array($event_requirement);
				$service_id=$event_requirement_row['service_id'];
				$sub_service_id=$event_requirement_row['sub_service_id'];
				if($service_id!=10 AND $service_id!=6 AND $sub_service_id!=423)
				{
					
					
					
				$event_Service=mysql_query("SELECT * FROM sp_services where service_id='$service_id'") or die(mysql_error());
				$event_Service_row = mysql_fetch_array($event_Service);
				$service_title=$event_Service_row['service_title'];
					
				$begin = new DateTime($service_date);
				$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
				$end = new DateTime($end);
				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
				foreach($daterange as $date)
				{
					$date_service=$date->format("Y-m-d") ;
					if($date_service==$today_date)
					{
						//echo $date_service;
						if($service_id==2){$Physician_assistant++;}
					elseif($service_id==3){$Physiotherapy++;}
					elseif($service_id==4){$Healthcare_attendants++;}
					elseif($service_id==5){$Nurse++;}
					elseif($service_id==8){$Laboratory_services++;}
					elseif($service_id==11){$Respiratory_care++;}
					elseif($service_id==12){$X_rayat_home++;}
					elseif($service_id==13){$Hca_package++;}
					elseif($service_id==15){$Medical_transportation++;}
					elseif($service_id==16){$Physiotherapy_New++;}
					elseif($service_id==17){$Assisted_living++;}
					elseif($service_id==18){$Physician_service++;}
					elseif($service_id==19){$Maid_service++;}
					$count++;
					}
				}
				}
			}
			$Total_Services=$Physician_assistant+$Physiotherapy+$Healthcare_attendants+$Nurse+$Laboratory_services+$Respiratory_care+$X_rayat_home+$Hca_package+$Medical_transportation+$Physiotherapy_New+$Assisted_living+$Physician_service+$Maid_service;
		
		$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
		
		//Dr.Rahil Mobile No 9890738374 & Dr.shelke mobile no 9552594108
		//$mobno=9890738374;
		$mobno=9552594108;
		//$mobno3=7774081894;
        $profmob =$mobno;
		//$profmob1 =$mobno2;
		//$profmob2 =$mobno3;
		
                        $txtMsg1 .= " Spero Home Healthcare,\n";
						$txtMsg1 .= " Total Services Count\n";
						$txtMsg1 .= " Physician Assistant-".$Physician_assistant."\n";
						$txtMsg1 .= " Physiotherapy-".$Physiotherapy."\n";
						$txtMsg1 .= " Healthcare Attendants-".$Healthcare_attendants."\n";
						$txtMsg1 .= " Nurse-".$Nurse."\n";
						$txtMsg1 .= " Laboratory Services-".$Laboratory_services."\n";
						$txtMsg1 .= " Respiratory Care-".$Respiratory_care."\n";
						$txtMsg1 .= " X-ray at home-".$X_rayat_home."\n";
						$txtMsg1 .= " Hca Package-".$Hca_package."\n";
						$txtMsg1 .= " Medical Transportation-".$Medical_transportation."\n";
						$txtMsg1 .= " Physiotherapy New-".$Physiotherapy_New."\n";
						$txtMsg1 .= " Assisted living-".$Assisted_living."\n";
						$txtMsg1 .= " Physician Service-".$Physician_service."\n";
						$txtMsg1 .= " Maid Service-".$Maid_service."\n";
						$txtMsg1 .= " Total Services-".$Total_Services;
                        
                        //dr.Rahil text msg
                        /*$args = array(
							'msg' => $txtMsg1,
							'mob_no' => $profmob
						);
						$sms_data = sms_send($args);*/
						
                        $data_to_post = array();
                        $data_to_post['uname'] = 'SperocHL';
                        $data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
                        $data_to_post['send'] = 'speroc';
                        $data_to_post['dest'] = $profmob; 
						$data_to_post['msg'] = $txtMsg1;
						
						
						
					

                        $curl = curl_init();
                        curl_setopt($curl,CURLOPT_URL, $form_url);
                        curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
                        curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
                        $result = curl_exec($curl);
                        curl_close($curl);
						
						//Dr.Shelke text msg 1 st
						
						$data_to_post = array();
                        $data_to_post['uname'] = 'SperocHL';
                        $data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
                        $data_to_post['send'] = 'speroc';
                        $data_to_post['dest'] = $profmob1; 
						$data_to_post['msg'] = $txtMsg1;

                        $curl = curl_init();
                        curl_setopt($curl,CURLOPT_URL, $form_url);
                        curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
                        curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
                        $result = curl_exec($curl);
                        curl_close($curl);
                        
                        //Dr.Shelke text msg 2nd no
                        
                        $data_to_post = array();
                        $data_to_post['uname'] = 'SperocHL';
                        $data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
                        $data_to_post['send'] = 'speroc';
                        $data_to_post['dest'] = $profmob2; 
						$data_to_post['msg'] = $txtMsg1;

                        $curl = curl_init();
                        curl_setopt($curl,CURLOPT_URL, $form_url);
                        curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
                        curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
                        $result = curl_exec($curl);
                        curl_close($curl);
?>