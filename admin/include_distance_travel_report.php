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
$formDate=$_GET['formDate_audio'];
$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['toDate_audio'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
$hospital_id=$_GET['hospital_id'];

      
if($formDate=='' and $toDate=='')
{
            $preWhere .= " AND DATE_FORMAT(se.event_date,'%Y-%m-%d') >= '" . date('Y-m-d', strtotime(' -45 day')) . "'  AND DATE_FORMAT(se.event_date,'%Y-%m-%d') <= '" . date('Y-m-d') . "'";

            $RecordSql=mysql_query("SELECT sp.google_location,callerno.phone_no,se.caller_id,calls.call_audio,se.CallUniqueID,
                se.event_id,se.event_code, se.caller_id,se.purpose_event_id,se.patient_id,sp.mobile_no,sp.name,sp.first_name,
                se.purpose_id,se.event_date,se.note,se.description,se.status,se.event_status,se.estimate_cost,
                se.finalcost,se.added_by,se.Added_through,se.added_date ,sp.hhc_code,se.isArchive, sp.isVIP,
                se.isConvertedService, se.enquiry_status, se.enquiry_cancellation_reason,Invoice_narration,
                pur_call.name as purpose_of_call,dtl_pln.professional_vender_id
                FROM sp_events as se 
            LEFT JOIN sp_patients as sp ON se.patient_id = sp.patient_id 
            LEFT JOIN sp_detailed_event_plan_of_care as dtl_pln ON se.event_id = dtl_pln.event_id 
            LEFT JOIN sp_purpose_call as pur_call ON se.purpose_id = pur_call.purpose_id 
            LEFT JOIN sp_incoming_call as calls ON se.CallUniqueID = calls.CallUniqueID
            LEFT JOIN sp_callers as callerno ON se.caller_id = callerno.caller_id
            WHERE 1 and se.status !='3' ".$preWhere."  ");

	//$payments = mysql_query("SELECT * FROM sp_events where status=1 and hospital_id='$hospital_id' ORDER BY date  DESC");
}
else
{
            $daterange .= " AND DATE_FORMAT(se.event_date,'%Y-%m-%d') >= '".$formDate1."'  AND DATE_FORMAT(se.event_date,'%Y-%m-%d') <= '".$toDate2."'";       

            $RecordSql=mysql_query("SELECT sp.langitude as lang,sp.lattitude as lat,sp.google_location,callerno.phone_no,se.caller_id,calls.call_audio,se.CallUniqueID,
                se.event_id,se.event_code, se.caller_id,se.purpose_event_id,se.patient_id,sp.mobile_no,sp.name,sp.first_name,
                se.purpose_id,se.event_date,se.note,se.description,se.status,se.event_status,se.estimate_cost,
                se.finalcost,se.added_by,se.Added_through,se.added_date ,sp.hhc_code,se.isArchive, sp.isVIP,
                se.isConvertedService, se.enquiry_status, se.enquiry_cancellation_reason,Invoice_narration,
                pur_call.name as purpose_of_call,dtl_pln.professional_vender_id
                FROM sp_events as se 
                LEFT JOIN sp_patients as sp ON se.patient_id = sp.patient_id 
                LEFT JOIN sp_detailed_event_plan_of_care as dtl_pln ON se.event_id = dtl_pln.event_id
                LEFT JOIN sp_purpose_call as pur_call ON se.purpose_id = pur_call.purpose_id 
                LEFT JOIN sp_incoming_call as calls ON se.CallUniqueID = calls.CallUniqueID
                LEFT JOIN sp_callers as callerno ON se.caller_id = callerno.caller_id
                WHERE 1 and se.status !='3'  ".$daterange." ");
        
	//$payments = mysql_query("SELECT * FROM sp_events where hospital_id='$hospital_id' AND status=1 and date BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY date  ASC ");
}
$row_count = mysql_num_rows($RecordSql);
if($row_count > 0)
{
            echo '<div class="table-responsive" id="payment">
                  <table class="table table-hover table-bordered">
                  <tr> 
                        <th width="3%">Event ID</th>
                        <th width="5%">Purpose of call</th>
                        <th width="5%">Patient Name</th>
                        <th width="5%">Professional Name</th>
                        <th width="5%">Patient Address</th>
                        <th width="5%">Professional Address</th>
                        <th width="5%">Date</th>
                        <th width="5%">Session Units</th>
                        <th width="5%">Distance KM</th>
		<th width="5%">Total Distance Travelled</th>
                </tr>';
            while($RecordSql_rows=mysql_fetch_array($RecordSql))
            {	
                    $totalkm='';
                    $unit='K';	
                $professional_vender_id =  $RecordSql_rows['professional_vender_id'];
                $lat =  $RecordSql_rows['lat'];
                $lang =  $RecordSql_rows['lang'];
                $event_id = $RecordSql_rows['event_id'];
                $professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='".$professional_vender_id."' ");
                if(mysql_num_rows($professional_name) < 1 )
                {

                }else{
                $professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
                $name=$professional_name_abc['name'];
                $title=$professional_name_abc['title'];
                $first_name=$professional_name_abc['first_name'];
                $middle_name=$professional_name_abc['middle_name'];
                $google_location=$professional_name_abc['address'];
                $lattitude=$professional_name_abc['lattitude'];
                $langitude=$professional_name_abc['langitude'];
                }
             
                $apiKey = 'AIzaSyBW_HR7a125NbuIVsomf-pzKIV5JT_CXzg';
    
                // Change address format
                $formattedAddrFrom    = str_replace(' ', '+', $google_location);
                $formattedAddrTo     = str_replace(' ', '+', $RecordSql_rows['google_location']);
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
                $unit = strtoupper($unit);
                if($unit == "K"){
                    $totalkm = round($miles * 1.609344, 2);
                }elseif($unit == "M"){
                        $totalkm = round($miles * 1609.344, 2).' meters';
                }else{
                        $totalkm =  round($miles, 2).' miles';
                }

                $plan_of_care= mysql_query("SELECT * FROM sp_event_plan_of_care  where event_id='$event_id'");
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
                                                $start_date=$row4['start_date'];
                                                $end_date=$row4['end_date'];
                                                
						$service_date_new= date('d-m-Y',strtotime($service_date));
						$service_date_to_new = date('d-m-Y',strtotime($service_date_to));
						
						$startTimeStamp = strtotime($service_date);
						$endTimeStamp = strtotime($service_date_to);
						$timeDiff = abs($endTimeStamp - $startTimeStamp);
						$numberDays = $timeDiff/86400;  // 86400 seconds in one day
						// and you might want to convert to integer
						$numberDays1=$numberDays + 1 ;
                                                $numberDays_qty = intval($numberDays1);
                                                $totalkm_all_unit = $totalkm * numberDays_qty;           
        echo '<tr>
	<td>'.$RecordSql_rows['event_code'].'</td>
            <td>'.$RecordSql_rows['purpose_of_call'].'</td>
            <td>'.$RecordSql_rows['first_name'].' '.$RecordSql_rows['name'].'</td>
            <td>'.$title.' '.$first_name.' '.$name.'</td>
            <td>'.$RecordSql_rows['google_location'].'</td>
            <td>'.$google_location.'</td>
            <td>'.$service_date.' To '.$service_date_to.'Time :'.$start_date.' To '.$end_date.'</td>
            <td>'.$numberDays_qty.'</td>
            <td>'.$totalkm.'</td>
            <td>'.$totalkm_all_unit.'</td>';
            
        echo '</tr>';
        }
        }
	}
}
else
{
	echo "<tr>";
	echo "<td colspan='14' align='middle'>" . "Record Not found for this date" . "</td>";
            echo "</tr>";
	echo "</div>";
	echo "</table>";
}
}
?>