<?php require_once 'inc_classes.php'; ?>
<?php
    $csv = '';
    $bgColorCounter = 1;
    $formDate = $_GET['formDate_distance'];
	$date1 = date_create("$formDate");
    $formDate1 = date_format($date1, "Y-m-d H:i:s");

	$toDate = $_GET['toDate_distance'];
	$date2 = date_create("$toDate");
 	$toDate2 = date_format($date2, "Y-m-d H:i:s");
    $hospital_id = $_GET['hospital_id'];

	if($formDate!='' and $toDate!='')
	{
		//$events = mysql_query("SELECT * FROM sp_events ORDER BY added_date DESC");
		$events = mysql_query("SELECT * FROM sp_events where added_date BETWEEN '$formDate1%' AND '$toDate2%' AND estimate_cost!='2' AND hospital_id='$hospital_id' AND event_status >= '3' ORDER BY added_date DESC");
	}
	else
	{
		$events = mysql_query("SELECT * FROM sp_events where estimate_cost!='2'  AND hospital_id='$hospital_id' AND event_status >= '3' ORDER BY added_date DESC");
		//$events = mysql_query("SELECT * FROM sp_events where added_date BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY added_date DESC");
		
	}
	
	//$payments = mysql_query("SELECT * FROM sp_payments ORDER BY date_time DESC");
	$total=0;
	//echo 'abc';
	$row_count = mysql_num_rows($events);

	//echo $row_count;
	if($row_count > 0)
		{
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
					<td width="5%">Event Date</td>
					<td width="5%">Event code</td>
                    <td width="5%">Patient Name</td>
					<td width="5%">Professional Name</td>
                    <td width="5%">Patient Address</td>
					<td width="5%">Professional Address</td>
					<th width="2%">Service</th>
					<th width="2%">Sub-Service</th>
					<td width="5%">Service Date</td>
                    <td width="5%">Sessions</td>
                    <td width="5%">Distance KM</td>
					<td width="5%">Total KM</td>
        </tr>';
           
			while ($events_rows = mysql_fetch_array($events))
				{		
					$event_id=$events_rows['event_id'];
					$event_code=$events_rows['event_code'];
					$event_date=$events_rows['event_date'];
					$patient_id=$events_rows['patient_id'];
						
						$patient_hhc_no = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
						if(mysql_num_rows($patient_hhc_no) < 1 )
						{
						$first_name='';
						$middle_name='';
						$name='';
						$google_location='';
						}
						else
						{
						$row2 = mysql_fetch_array($patient_hhc_no) or die(mysql_error());
						$first_name1=$row2['first_name'];
						$middle_name1=$row2['middle_name'];
						$name1=$row2['name'];
						$google_location=$row2['google_location'];
						}
						
						$requirement_id= mysql_query("SELECT * FROM sp_event_requirements  where event_id='$event_id'");
						if(mysql_num_rows($requirement_id) < 1 )
						{
							$sub_service_id='';
						}
						else
						{
							//echo $event_id;
							//$row1 = mysql_fetch_array($requirement_id) or die(mysql_error());
							while($row1=mysql_fetch_array($requirement_id))
							{
							$event_requirement_id=$row1['event_requirement_id'];
							$service_id=$row1['service_id'];
							$sub_service_id=$row1['sub_service_id'];

							$service= mysql_query("SELECT * FROM sp_services  where service_id='$service_id'");
							$service_name = mysql_fetch_array($service) or die(mysql_error());
							$service_title=$service_name['service_title'];

							$sub_service= mysql_query("SELECT * FROM sp_sub_services  where sub_service_id='$sub_service_id'");
							$row3 = mysql_fetch_array($sub_service) or die(mysql_error());
							$recommomded_service=$row3['recommomded_service'];
						
							
						//echo $sub_service_id;
						//Professional Name
						$professional= mysql_query("SELECT * FROM sp_event_professional  where event_requirement_id='$event_requirement_id'");
						$row_count1 = mysql_num_rows($professional);
						if($row_count1 > 0)
						{
							$professional_new = mysql_fetch_array($professional) or die(mysql_error());
							$professional_vender_id=$professional_new['professional_vender_id'];
							
							$professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$professional_vender_id'");
							$professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
							$name=$professional_name_abc['name'];
							$title=$professional_name_abc['title'];
							$first_name=$professional_name_abc['first_name'];
							$middle_name=$professional_name_abc['middle_name'];
							$google_location_prof=$professional_name_abc['google_work_location'];
							$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
						}
						else
						{
							if($service_id=='9')
							{
								$professional_name='Purchase';
							}
							if($service_id=='10')
							{
								$professional_name='Conveyance_Cost';
							}
						}
			$plan_of_care= mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id'");
			if(mysql_num_rows($plan_of_care) < 1 )
			{
				$numberDays_qty ='';
			}
			else
							{
								while($row4=mysql_fetch_array($plan_of_care))
							{
							//$row4 = mysql_fetch_array($plan_of_care) or die(mysql_error());
							//$cost=$row4['service_cost'];
							$service_date=$row4['service_date'];
							$service_date_to=$row4['service_date_to'];
							
							$service_date_new= date('d-m-Y',strtotime($service_date));
							$service_date_to_new = date('d-m-Y',strtotime($service_date_to));
							
							$startTimeStamp = strtotime($service_date);
							$endTimeStamp = strtotime($service_date_to);
							$timeDiff = abs($endTimeStamp - $startTimeStamp);
							$numberDays = $timeDiff/86400;  // 86400 seconds in one day
							// and you might want to convert to integer
							$numberDays1=$numberDays + 1 ;
							$numberDays_qty = intval($numberDays1);
							
								
							$apiKey = 'AIzaSyBW_HR7a125NbuIVsomf-pzKIV5JT_CXzg';
		
							// Change address format
							$formattedAddrFrom    = str_replace(' ', '+', $google_location);
							$formattedAddrTo     = str_replace(' ', '+', $google_location_prof);
							
							// Geocoding API request with start address
							$geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
							$outputFrom = json_decode($geocodeFrom);
							if(!empty($outputFrom->error_message)){
								return $outputFrom->error_message;
							}
							
							// Geocoding API request with end address
							$geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
							$outputTo = json_decode($geocodeTo);
							if(!empty($outputTo->error_message)){
								return $outputTo->error_message;
							}
							
							// Get latitude and longitude from the geodata
							$latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
							$longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
							$latitudeTo        = $outputTo->results[0]->geometry->location->lat;
							$longitudeTo    = $outputTo->results[0]->geometry->location->lng;
							
							// Calculate distance between latitude and longitude
							$theta    = $longitudeFrom - $longitudeTo;
							$dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
							$dist    = acos($dist);
							$dist    = rad2deg($dist);
							$miles    = $dist * 60 * 1.1515;
							
							// Convert unit and return distance
							$unit = 'K';
							$unit = strtoupper($unit);
							if($unit == "K"){
									$total = round($miles * 1.609344, 2);
							}elseif($unit == "M"){
									$total =  round($miles * 1609.344, 2).' meters';
							}else{
									$total =  round($miles, 2).' miles';
							}			//	<td>'.$branch_code.'/'.$y.'-'.$fin_year_new.'/'.$bill_no_ref_no.'</td>
							$total_KM_travel=$total * $numberDays_qty;
							if($sub_service_id!=423)
							{
								
			
			 $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$event_date.'</td>';
             $datas .= '<td>'.$event_code.'</td>';
			$datas .= '<td>'.$first_name1.' '.$middle_name1.' '.$name1.'</td>';
			$datas .= '<td>'.$professional_name.'</td>';
			$datas .= '<td>'.$google_location.'</td>';
			$datas .= '<td>'.$google_location_prof.'</td>';
			$datas .= '<td>'.$service_title.'</td>';
			$datas .= '<td>'.$recommomded_service.'</td>';
			$datas .= '<td>'.$service_date.' To '.$service_date_to.'</td>';
			$datas .= '<td>'.$numberDays_qty.'</td>';
			$datas .= '<td>'.$total.' '.'KM'.'</td>';
			$datas .= '<td>'.$total_KM_travel.' '.'KM'.'</td>';
			
			
			$datas .= '</tr>';
							$total=$finalcost+$total;
							}
							
				}
	//Ashu
							}
							
							}
	
				}							
				
						
			}	
		}
		else
            $datas.='No record found related to your search criteria';

    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."Dist_travel_report.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=Distance_Travel_Report ".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>