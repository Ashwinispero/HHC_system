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
$formDate=$_GET['formDate_enquiry'];
$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate_enquiry=$_GET['toDate_enquiry'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
$hospital_id=$_GET['hospital_id'];

      
if($formDate!='' and $toDate!='')
{
$events = mysql_query("SELECT ev.event_code,CONCAT (pt.name, ' ',pt.first_name, ' ',pt.middle_name) as patientName,ser.service_title,sub.recommomded_service FROM sp_events as ev LEFT JOIN sp_enquiry_requirements as er ON ev.event_id=er.event_id LEFT JOIN sp_patients as pt ON pt.patient_id=ev.patient_id LEFT JOIN sp_services as ser ON er.service_id=ser.service_id LEFT JOIN sp_sub_services as sub ON er.sub_service_id=sub.sub_service_id WHERE ev.purpose_id=2 AND ev.enquiry_status=1 AND ev.enquiry_added_date BETWEEN  '$formDate1%' AND '$toDate2%' ");
}
$row_count = mysql_num_rows($events);
if($row_count > 0)
{
	echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
            <tr> 
	<th width="2%">Event Code</th>
	<th width="2%">Patient Name</th>
            <th width="2%">Service Title </th>
            <th width="2%">Recommended Service</th>
	</tr>';
		
		for($i=1; $i<=$row_count;)
		{
			while ($events_rows = mysql_fetch_array($events))
			{		
				$patientName=$events_rows['patientName'];
				$event_code=$events_rows['event_code'];
				$service_title=$events_rows['service_title'];
				$recommomded_service=$events_rows['recommomded_service'];
					
				echo '<tr>
				<td>'.$event_code.'</td>
				<td>'.$patientName.'</td>
				<td>'.$service_title.'</td>
                                                <td>'.$recommomded_service.'</td>';
				echo '</tr>';
		            }
				
				
			}
}
else
{
			
	echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
            <tr> 
	<th width="2%">Event Code</th>
	<th width="2%">Patient Name</th>
            <th width="2%">Service Title </th>
            <th width="2%">Recommended Service</th>
	</tr>';
	echo "<td colspan='10' align='middle'>" . "Record Not found for this date" . "</td>";
            echo "</tr>";
	echo "</div>";
	echo "</table>";
				 
				 
}
}
?>