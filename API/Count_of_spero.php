<?php
include ('config.php');
date_default_timezone_set("Asia/Calcutta");
$now = date('Y-m-d');

 
            $querys_firstdate = mysql_query("SELECT * FROM `sp_events` WHERE event_id = '1'");
            $row_count_date = mysql_num_rows($querys_firstdate);
                        if ($row_count_date > 0)
            {
                $arr_date = mysql_fetch_array($querys_firstdate);
				$arr_dates = $arr_date['added_date'];
				$firstdate = date('Y-m-d',strtotime($arr_dates));
				  $date_from = $firstdate;
                        $date_from = strtotime($date_from);
                        $date_to = $now;
                        $date_to = strtotime($date_to);
		            
                       $count = 0;
                        for ($i = $date_from;$i <= $date_to;$i += 86400)
                        {
                            $count++;
                        }
				
				
				
				
			}
            
            $querys_patient = mysql_query("SELECT COUNT(patient_id)as patient_count FROM `sp_patients`");
            $row_count_patient = mysql_num_rows($querys_patient);
            if ($row_count_patient > 0)
            {
                $row = mysql_fetch_array($querys_patient);
				$patient_count=$row['patient_count'];
				
				
				
			}
			 $querys_prof = mysql_query("SELECT COUNT(service_professional_id) as service_professional_id  FROM `sp_service_professionals` WHERE `status` = '1' AND OTP_verification=1 AND document_status=1");
		     $row_count_prof = mysql_num_rows($querys_prof);
            if ($row_count_prof > 0)
            {
                $prof_array = mysql_fetch_array($querys_prof);
				$profcount=$prof_array['service_professional_id'];
				
				
			}
			 $querys_services = mysql_query("SELECT COUNT(t1.event_id) as event_requirement_id  FROM  sp_events as t1 JOIN sp_event_requirements as t2 on t2.event_id=t1.event_id WHERE t1.purpose_id=1 AND t1.estimate_cost=3");
		     $row_count_services = mysql_num_rows($querys_services);
            if ($row_count_services > 0)
            {
                $ser_array = mysql_fetch_array($querys_services);
				$eventcount=$ser_array['event_requirement_id'];
				
				
			}
			
		/*	$querys_servicesall = mysql_query("SELECT t1.event_id, t1.service_date,t1.service_date_to FROM sp_event_plan_of_care as t1 JOIN sp_event_requirements as t2 on t2.event_requirement_id =t1.event_requirement_id WHERE t2.service_id !=10 AND t2.service_id !=9");
		     $row_count_allservices = mysql_num_rows($querys_servicesall);
            if ($row_count_allservices > 0)
            {
                 while($ser_array = mysql_fetch_array($querys_servicesall)){
               // $ser_array = mysql_fetch_array($querys_servicesall);
				$fromDate=$ser_array['service_date'];
				$toDate=$ser_array['service_date_to'];
				
                        $counts = 0;
                        for ($i = $fromDate;$i <= $toDate;$i += 86400)
                        {
                            $counts++;
                        }
                        $allcount=$counts;
                 }
                
			}*/
		$eventcount=116935;
             
echo json_encode(array("data"=>array("dayodservices"=>$count,"patient"=>$patient_count,"profcount"=>$profcount,"eventcount"=>$eventcount),"error"=>null));	



?>
