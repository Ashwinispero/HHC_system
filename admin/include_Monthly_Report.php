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
	$formDate=$_GET['formDate_receipt'];
$date1=date_create("$formDate");
 $formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['toDate_receipt'];
$date2=date_create("$toDate");
 $toDate2=date_format($date2,"Y-m-d H:i:s");
 $hospital_id=$_GET['hospital_id'];
 //echo $formDate1;
 
 //echo $toDate2;
	if($formDate=='' and $toDate=='')
	{
		
	}
	else
	{
                        
                        
		$payments = mysql_query("SELECT t2.event_code,t4.service_title,sbs.recommomded_service,CONCAT (spp.name, ' ',spp.first_name, ' ',spp.middle_name) as ProfName,spp.Job_type,
                        t1.patient_id,CONCAT (t1.name, ' ',t1.first_name, ' ',t1.middle_name) as patientName,pln.service_date,pln.service_date_to,pln.Actual_Service_date,
                        t1.residential_address,
                        t1.sub_location, t1.google_location,t1.permanant_address
                        FROM sp_detailed_event_plan_of_care as pln
                        LEFT JOIN sp_events as t2 ON pln.event_id=t2.event_id
                        JOIN sp_patients as t1 on t2.patient_id=t1.patient_id
                        LEFT join sp_event_requirements as t3 ON t2.event_id = t3.event_id
                        LEFT join sp_services as t4 on t4.service_id=t3.service_id
                        LEFT JOIN sp_sub_services as sbs ON t3.sub_service_id = sbs.sub_service_id
                        LEFT JOIN sp_service_professionals as spp ON spp.service_professional_id = t3.professional_vender_id
                        WHERE t2.purpose_id=1 AND t2.estimate_cost=3 AND pln.Actual_Service_date BETWEEN 'formDate1' AND '$toDate2' ORDER BY pln.service_date ASC
                        ");
		
	}
	//$payments = mysql_query("SELECT * FROM sp_payments ORDER BY date_time DESC");
	$total=0;
	//echo 'abc';
	$row_count = mysql_num_rows($payments);

//	echo $row_count;
	if($row_count > 0)
		{
			echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                                    <tr> 
                                    <th width="3%">Branch</th>
			<th width="5%">Payment Receipt No/Voucher No</th>
                                    <th width="5%">Payment Receipt Date/ Vch date</th>
			<th width="5%">Bill No/Ref no.</th>
                                    <th width="5%">Customer Name</th>
			<th width="5%">Email ID</th>
			<th width="5%">Amount</th>
			<th width="5%">Professional</th>
                                    <th width="5%">Bank/Cash</th>
                                    <th width="5%">Cheque/DD/NEFT No.</th>
			<th width="5%">Cheque/DD/NEFT Date</th>
                                    <th width="5%">Party Bank Name</th>
			<th width="5%">Download Receipt</th>
                                    </tr>';
		
		
			
				//$payment_rows = mysql_fetch_array($payments);
			while ($payment_rows = mysql_fetch_array($payments))
			{		
				//$event_requirement_id=$payment_rows['event_code'];
				
				
				echo '<tr>
                                                <td>'.$payment_rows['event_code'].'</td>
                                                <td>'.$payment_rows['service_title'].'</td>
                                                <td>'.$payment_rows['recommomded_service'].'</td>
                                                <td>'.$payment_rows['ProfName'].'</td>
                                                <td>'.$payment_rows['Job_type'].'</td>
                                                <td>'.$payment_rows['patientName'].'</td>
                                                <td>'.$payment_rows['service_date'].'</td>
                                                <td>'.$payment_rows['service_date_to'].'</td>
                                                <td>'.$payment_rows['Actual_Service_date'].'</td>
                                                <td>'.$payment_rows['residential_address'].'</td>
                                                <td>'.$payment_rows['sub_location'].'</td>
                                                <td>'.$payment_rows['google_location'].'</td>
                                                <td>'.$payment_rows['permanant_address'].'</td>';
				echo '</tr>';
				
			}

			$total=round($total);
				
				echo "<td ></td>";
				echo "<td ></td>";
				echo "<td ></td>";
				echo "<td ></td>";
				echo "<td ></td>";
				
				echo "<td style='text-align:right;font-weight:bold'>Total</td>";
				 echo "<td style='font-weight:bold'>$total</td>";
				 echo "<td ></td>";
				 echo "<td ></td>";
				 echo "<td ></td>";
				 echo "<td ></td>";
				 echo "<td ></td>";
				 echo "<td ></td>";
				 echo "<td ></td>";
			}
			else
		{
			echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                <tr> 
                    <th width="3%">Branch</th>
					<th width="5%">Payment Receipt No/Voucher No</th>
                    <th width="5%">Payment Receipt Date/ Vch date</th>
					<th width="5%">Bill No/Ref no.</th>
                    <th width="5%">Customer Name</th>
					<th width="5%">Email ID</th>
					<th width="5%">Amount</th>
					<th width="5%">Professional</th>
                    <th width="5%">Bank/Cash</th>
                    <th width="5%">Cheque/DD/NEFT No.</th>
					<th width="5%">Cheque/DD/NEFT Date</th>
                    <th width="5%">Party Bank Name</th>
					<th width="5%">Narration</th>
					<th width="5%">Download Receipt</th>
					
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