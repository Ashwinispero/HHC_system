<?php require_once 'inc_classes.php'; ?>
<?php
$csv = '';
$bgColorCounter = 1;
$formDate = $_GET['formDate_receipt'];
$date1 = date_create("$formDate");
$formDate1 = date_format($date1, "Y-m-d H:i:s");
$toDate = $_GET['toDate_receipt'];
$date2 = date_create("$toDate");
$toDate2 = date_format($date2, "Y-m-d H:i:s");
$hospital_id = $_GET['hospital_id'];
if($formDate!='' AND  $toDate!='' AND $hospital_id!='' )
{
	//$events = mysql_query("SELECT * FROM sp_events ORDER BY added_date DESC");
	$events = mysql_query("SELECT * FROM sp_events where added_date BETWEEN '$formDate1' AND '$toDate2' AND estimate_cost!='2' AND purpose_id='1' AND hospital_id='$hospital_id' AND event_status >= '2' ORDER BY added_date DESC");

}

$row_count = mysql_num_rows($events);
if($row_count > 0)
{ 
            $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
            <tr height="30">
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
	
            include "include/paging_script.php";
            $payments_deatils = mysql_query("SELECT * FROM sp_payments  where event_id='$event_id'");
            $row_count = mysql_num_rows($payments_deatils);
            if($row_count > 0)
            {
            $amt = 0;
            while ($payment_rows = mysql_fetch_array($payments_deatils))
            {	
            	$amt=$payment_rows['amount']+$amt;
            }
            if($finalcost == $amt || $finalcost <= $amt){
                        $payment_status ='Received';
                        $amount = 'NA';
                        
            }elseif($finalcost > $amt){
		$payment_status ='Partial Payment';
                        $amount = $finalcost - $amt ;
                        $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$event_code.'</td>';
            $datas .= '<td>'.$event_date.'</td>';
	$datas .= '<td>'.$hhc_code.'</td>';
	$datas .= '<td>'.$first_name1.' '.$middle_name1.' '.$name1.'</td>';
	$datas .= '<td>'.$mobile_no.'</td>';
	$datas .= '<td>'.$payment_status.'</td>';
	$datas .= '<td>'.$finalcost.'</td>';
	$datas .= '<td>'.$amount.'</td>';
	$datas .= '</tr>';
            }
            }
            else{
	$payment_status='Pending';
            $amount = $finalcost;
            $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$event_code.'</td>';
            $datas .= '<td>'.$event_date.'</td>';
	$datas .= '<td>'.$hhc_code.'</td>';
	$datas .= '<td>'.$first_name1.' '.$middle_name1.' '.$name1.'</td>';
	$datas .= '<td>'.$mobile_no.'</td>';
	$datas .= '<td>'.$payment_status.'</td>';
	$datas .= '<td>'.$finalcost.'</td>';
	$datas .= '<td>'.$amount.'</td>';
	$datas .= '</tr>';
            }
	
	
	}
}
else
$datas.='No record found related to your search criteria';

$db->close();
$filepath="CSV/".time()."Pening_Payment.xls";
$file=fopen($filepath,"w");
fwrite($file,$datas);
fclose($file);
header("Content-Disposition: attachment; filename=Pening_Payment".date("Y-m-d").".xls");
header("Content-Type: application/vnd.ms-excel");
readfile($filepath);
unlink($filepath);
?>