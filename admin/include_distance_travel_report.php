<?php require_once('inc_classes.php'); 
        //require_once '../classes/locationsClass.php';
        //$locationsClass = new locationsClass();
        if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1')
        {
          $col_class="icon3";
          $del_visible="Y";
        }
        else 
        {
         $col_class="icon2"; 
         $del_visible="N";
        } 
?>
<?php
if(!$_SESSION['admin_user_id'])
    echo 'notLoggedIn';
else
{	
	//-----Author: ashwini 31-05-2016-----
	//--Code for date range--
$formDate=$_GET['formDate_distance'];
$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['toDate_distance'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
$hospital_id=$_GET['hospital_id'];

      
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
	$total=0;
	
	$row_count = mysql_num_rows($events);
	if($row_count > 0)
		{
			echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                <tr> 
                <th width="2%">Event Date</th>
		<th width="2%">Patient Name</th>
                <th width="2%">Professional Name</th>
		<th width="2%">Patient Address</th>
                <th width="2%">Professional Address</th>
                <th width="2%">Service Date</th>
		<th width="2%">Sessions</th>
                <th width="2%">Distance KM</th>
                <th width="2%">Total KM</th>
		
		
		</tr>';
		
		for($i=1; $i<=$row_count;)
			{
				
			while ($events_rows = mysql_fetch_array($events))
				{		
					$event_id=$events_rows['event_id'];
					$event_code=$events_rows['event_code'];
					$event_date=$events_rows['event_date'];
					$patient_id=$events_rows['patient_id'];
					
					$patient_hhc_no = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
					if(mysql_num_rows($patient_hhc_no) < 1 )
					{
					$hhc_code='';
					$first_name='';
					$middle_name='';
					$name='';
					$residential_address='';
					$permanant_address='';
					$mobile_no='';
					}
					else
					{
					$row2 = mysql_fetch_array($patient_hhc_no) or die(mysql_error());
					$hhc_code=$row2['hhc_code'];
					$first_name1=$row2['first_name'];
					$middle_name1=$row2['middle_name'];
					$name1=$row2['name'];
					$residential_address=$row2['residential_address'];
					$permanant_address=$row2['permanant_address'];
                                        $mobile_no=$row2['mobile_no'];
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
						
						$sub_service= mysql_query("SELECT * FROM sp_event_requirements  where event_requirement_id='$event_requirement_id'");
						$sub_service_id_new = mysql_fetch_array($sub_service) or die(mysql_error());
						$sub_service_id=$sub_service_id_new['sub_service_id'];
						$service_id=$sub_service_id_new['service_id'];
						
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
						
						//echo $sub_service_id;professional_vender_id
						$sub_service= mysql_query("SELECT * FROM sp_sub_services  where sub_service_id='$sub_service_id'");
						if(mysql_num_rows($sub_service) < 1 )
						{
						$recommomded_service='';
						$cost='';
						}
						else{
						$row3 = mysql_fetch_array($sub_service) or die(mysql_error());
						$recommomded_service=$row3['recommomded_service'];
						$service_id=$row3['service_id'];
						
						$service= mysql_query("SELECT * FROM sp_services  where service_id='$service_id'");
						$service_name = mysql_fetch_array($service) or die(mysql_error());
						$recomended_service_name=$service_name['service_title'];
						
						$UOM=$row3['UOM'];
						$cost=$row3['cost'];
							
							//$cost=$row4['service_cost'];
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
						
							$Voucher_Ref=$hhc_code.'/'.$event_code;
							//date format
							$date_time1 = explode(" ",$event_date);
							$exploded_date1 = $date_time1[0];
							$event_date = date('d-m-Y',strtotime($exploded_date1));
							//date
							$current_year = Date("d-m-Y");
							list($d,$m,$y) = explode('-',$current_year);
							$fin_year = $y+1;
							$current_year_new = Date("d-m-y");
							list($d_new,$m_new,$y_new) = explode('-',$current_year_new);
							$fin_year_new = $y_new+1;
					
							if($recommomded_service=='Other')
							{
								$finalcost=$row4['service_cost'];
								$cost=$finalcost / $numberDays_qty;
								
							}
							else
							{
								if(($service_id==17 OR $service_id==13) AND $sub_service_id!=425)
								{
									$finalcost=$row4['service_cost']; 
									$cost=$finalcost / $numberDays_qty;
								}
								else
								{
									$finalcost=$cost * $numberDays_qty; 
								}
								//$finalcost=$cost * $numberDays_qty;
							}
						
							
			if (date('m') > 3) {
				$financial_year = date('Y')."-".(date('y') +1);
			}
			else {
				$financial_year = (date('Y')-1)."-".date('y');
			}
			//$finalcost=round($finalcost);
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
                                $total = round($miles * 1.609344, 2).' km';
                        }elseif($unit == "M"){
                                $total =  round($miles * 1609.344, 2).' meters';
                        }else{
                                $total =  round($miles, 2).' miles';
                        }			//	<td>'.$branch_code.'/'.$y.'-'.$fin_year_new.'/'.$bill_no_ref_no.'</td>
						
						if($sub_service_id!=423)
						{
							echo '<tr>
							<td>'.$event_date.'</td>
                                                        <td>'.$first_name1.' '.$middle_name1.' '.$name1.'</td>
                                                        <td>'.$professional_name.'</td>
							<td>'.$google_location.'</td>
							<td>'.$google_location_prof.'</td>
							<td>'.$service_date.' To '.$service_date_to.'</td>
							<td>'.$numberDays_qty.'</td>
							<td>'.$total.'</td>
							<td>'.''.'</td>';
						   
							echo '</tr>';
						
						}
						
			}
//Ashu
						}
						
						}

			}
					
			
			$i++;
				}
				
				
			}
		}
		else
		{
			echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                <tr> 
                     <th width="2%">Branch</th>
					<th width="2%">Voucher Number/Bill No</th>
                    <th width="2%">Voucher Type</th>
					<th width="2%">Voucher Date</th>
                    <th width="2%">Voucher Ref</th>
                    <th width="2%">Party Name</th>
					<th width="2%">Address 1</th>
                    <th width="2%">Address 2</th>
                    <th width="2%">Address 3 / Phone No.</th>
					<th width="2%">Stock Item</th>
                    <th width="2%">Qty</th>
					<th width="2%">Rate</th>
					<th width="2%">Amount</th>
					<th width="2%">From Date</th>
					<th width="2%">To Date</th>
                    <th width="2%">Name OF Professional</th>
                    <th width="2%">Narration</th>
					 <th width="2%">UOM</th>
					 <th width="2%">Category</th>
					
                </tr>';
				 echo "<tr>";
				  //echo "<td colspan="2">"."Sum: $180"."</td>";
              echo "<td colspan='14' align='middle'>" . "Record Not found for this date" . "</td>";
              

              echo "</tr>";
				 echo "</div>";
				 echo "</table>";
				 
				 
		}
}
?>