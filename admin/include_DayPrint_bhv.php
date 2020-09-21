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
	$formDate=$_GET['formDate_dayPrint'];
$date1=date_create("$formDate");
 $formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['toDate_dayPrint'];
$date2=date_create("$toDate");
 $toDate2=date_format($date2,"Y-m-d H:i:s");
	if($formDate=='' and $toDate=='')
	{
		$payments = mysql_query("SELECT * FROM sp_payments where hospital_id=11 and status=1 ORDER BY date_time  DESC");
	}
	else
	{
		$payments = mysql_query("SELECT * FROM sp_payments where hospital_id=11 and status=1 and date_time BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY date_time DESC");
		
	}
	
	$total=0;
	$row_count = mysql_num_rows($payments);
	if($row_count > 0)
		{
			echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                <tr> 
                   <th width="5%">Date</th>
					<th width="5%">Particulars</th>
                    <th width="5%">Vch Type</th>
					<th width="5%">Vch No./Excise Inv.No.</th>
					<th width="5%">Type</th>
                    <th width="5%">Debit</th>
					
                </tr>';
		
		for($i=1; $i<=$row_count;)
			{
				
			while ($payment_rows = mysql_fetch_array($payments))
			{		
			//{		
				//code to get event_code from event_id...Amod
				$event_id1=$payment_rows['event_id'];
				$Refund=$payment_rows['Transaction_Type'];
				$type=$payment_rows['type'];
				$payments_event_code = mysql_query("SELECT * FROM sp_events  where event_id='$event_id1'");
				$row1 = mysql_fetch_array($payments_event_code) or die(mysql_error());
				$patient_id=$row1['patient_id'];
				$bill_no_ref_no=$row1['bill_no_ref_no'];
				$event_code=$row1['event_code'];
				//$hospital_id=$row1['hospital_id'];
				
				
				$payments_event_code = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
				$row2 = mysql_fetch_array($payments_event_code) or die(mysql_error());
				$first_name=$row2['first_name'];
				$middle_name=$row2['middle_name'];
				$name=$row2['name'];
				$hhc_code=$row2['hhc_code'];
				
			
			$date_time = explode(" ",$payment_rows['date_time']);
			$exploded_date = $date_time[0];
			$date = date('d-m-Y',strtotime($exploded_date));
			$payment_receipt_no_voucher_no=$payment_rows['payment_receipt_no_voucher_no'];
			$branch=$payment_rows['branch'];
			$receipt='Receipt';
	if($Refund!='Refund')
	{
		 echo '<tr>
                    <td>'.$date.'</td>
					<td><b>'.$first_name.' '.$middle_name.' '.$name.'</b><br>
							'.$payment_rows['professional_name'].'<br>
							'.$hhc_code.'/'.$event_code.'
							</td>
					<td>'.$receipt.'-'.$branch.'</td>
				
					<td>'.$payment_receipt_no_voucher_no.'</td>
					<td>'.$type.'</td>
					<td>'.$payment_rows['amount'].'</td>';
                   
			echo '</tr>';
			$total=$payment_rows['amount']+$total;
	}
	
			
			$i++;
			}
				
				echo "<td ></td>";
				echo "<td ></td>";
				echo "<td ></td>";
				echo "<td ></td>";
				echo "<td style='text-align:right;font-weight:bold'>Total</td>";
				 echo "<td style='font-weight:bold'>$total</td>";
			
			//}
			//echo "</div>";
			//echo "</table>";
		}
		
		}
		else
		{
			echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                <tr> 
                   <th width="5%">Date</th>
					<th width="5%">Particulars</th>
                    <th width="5%">Vch Type</th>
					<th width="5%">Vch No./Excise Inv.No.</th>
					<th width="5%">Type</th>
                    <th width="5%">Debit</th>
					
                </tr>';
				 echo "<tr>";
				  //echo "<td colspan="2">"."Sum: $180"."</td>";
              echo "<td colspan='6' align='middle'>" . "Record Not found for this date" . "</td>";
              

              echo "</tr>";
				 echo "</div>";
				 echo "</table>";
				 
				 
		}
		}?>