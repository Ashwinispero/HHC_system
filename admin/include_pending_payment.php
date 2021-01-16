<?php require_once('inc_classes.php'); 
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
$formDate=$_GET['formDate_receipt'];
$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['toDate_receipt'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");

$hospital_id=$_GET['hospital_id'];
if($formDate!='' and $toDate!='' and $hospital_id != '')
{
	//$events = mysql_query("SELECT * FROM sp_events ORDER BY added_date DESC");
	$events = mysql_query("SELECT * FROM sp_events where added_date BETWEEN '$formDate1' AND '$toDate2' AND estimate_cost!='2' AND purpose_id='1' AND hospital_id='$hospital_id' AND event_status >= '2' ORDER BY added_date DESC");
}

    

$row_count = mysql_num_rows($events);
if($row_count > 0)
{
	echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
            <tr> 
            <th width="2%">Event Code</th>
            <th width="2%">Event Date</th>
	<th width="2%">HHC No</th>
            <th width="2%">Patient Name</th>
            <th width="2%">Mobile No</th>
            <th width="2%">payment_status</th>
	<th width="2%">Total Amount</th>
	<th width="2%">Pending Amount</th>
	</tr>';
	
	while ($events_rows = mysql_fetch_array($events))
	{		
	$event_id=$events_rows['event_id'];
	$event_code=$events_rows['event_code'];
	$event_date=$events_rows['event_date'];
	$patient_id=$events_rows['patient_id'];
	$finalcost=$events_rows['finalcost'];

		//Patient Details
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
		}
			$payments_deatils = mysql_query("SELECT * FROM sp_payments  where event_id='$event_id'");
            $row_count = mysql_num_rows($payments_deatils);
            if($row_count > 0)
            {
            $amt = 0;
            while ($payment_rows = mysql_fetch_array($payments_deatils))
            {	
            	$amt=$payment_rows['amount']+$amt;
            	$amount = 'NA';
            }
            if($finalcost == $amt || $finalcost <= $amt){
            	$payment_status ='Received';
            }elseif($finalcost > $amt){
		$payment_status ='Partial Payment';
		$amount = $finalcost - $amt ;
			echo '<tr>
		<td>'.$event_code.'</td>
		<td>'.$event_date.'</td>
		<td>'.$hhc_code.'</td>
		<td>'.$first_name1.' '.$middle_name1.' '.$name1.'</td>
		<td>'.$mobile_no.'</td>
		<td>'.$payment_status.'</td>
		<td>'.$finalcost.'</td>
		<td>'.$amount.'</td>';
		echo '</tr>';	
            }
            }
            else{
	$payment_status='Pending';
	$amount = $finalcost;
	
		echo '<tr>
		<td>'.$event_code.'</td>
		<td>'.$event_date.'</td>
		<td>'.$hhc_code.'</td>
		<td>'.$first_name1.' '.$middle_name1.' '.$name1.'</td>
		<td>'.$mobile_no.'</td>
		<td>'.$payment_status.'</td>
		<td>'.$finalcost.'</td>
		<td>'.$amount.'</td>';
		echo '</tr>';	
            }				
					
	
	}
}
else
{
	echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
            <tr> 
            <th width="2%">Event Code</th>
            <th width="2%">Event Date</th>
	<th width="2%">HHC No</th>
            <th width="2%">Patient Name</th>
            <th width="2%">Mobile No</th>
            <th width="2%">payment_status</th>
	<th width="2%">Total Amount</th>
	<th width="2%">Pending Amount</th>
	</tr>';
	echo "<tr>";
	echo "<td colspan='15' align='middle'>" . "Record Not found for this date" . "</td>";
            echo "</tr>";
	echo "</div>";
	echo "</table>";
				 
				 
		}
}?>