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
	if($formDate=='' and $toDate=='')
	{
		$payments = mysql_query("SELECT * FROM sp_payments where status=1 ORDER BY date_time  DESC");
	}
	else
	{
		$payments = mysql_query("SELECT * FROM sp_payments where status=1 and date_time BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY date_time DESC");
		
	}
	//$payments = mysql_query("SELECT * FROM sp_payments ORDER BY date_time DESC");
	$total=0;
	
	$row_count = mysql_num_rows($payments);
	
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
					<th width="5%">Narration</th>
					
                </tr>';
		
		for($i=1; $i<=$row_count;)
		{
				
			while ($payment_rows = mysql_fetch_array($payments))
			{	
	
			$event_id=$payment_rows['event_id'];
			//echo $event_id;	
			$service_cost=0;
			$Scost= mysql_query("SELECT * FROM sp_event_requirements  where event_id='$event_id'");
			while($Scost1=mysql_fetch_array($Scost))
			{
				$event_requirement_id=$Scost1['event_requirement_id'];
				$query=mysql_query("SELECT  service_cost FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id'");
				$row = mysql_fetch_array($query) or die(mysql_error());
				$service_cost1=$row['service_cost'];
				$service_cost=$service_cost+$service_cost1;
			}
			//$query=mysql_query("SELECT SUM(service_cost) As service_cost FROM sp_event_plan_of_care  where event_id='$event_id'");
			//$row = mysql_fetch_array($query) or die(mysql_error());
			//$service_cost=$row['service_cost'];
			//$service_cost=round($service_cost);
			$query1=mysql_query("SELECT SUM(amount) As amount FROM sp_payments  where event_id='$event_id' and status='1'");
			$row1 = mysql_fetch_array($query1) or die(mysql_error());
			$amount=$row1['amount'];
			if($service_cost==$amount)
			{
				
				$requirement_id= mysql_query("SELECT * FROM sp_event_requirements  where event_id='$event_id'");
					if(mysql_num_rows($requirement_id) < 1 )
					{
						$sub_service_id='';
					}
					else
					{
						
						//$row1 = mysql_fetch_array($requirement_id) or die(mysql_error());
						while($row1=mysql_fetch_array($requirement_id))
						{
						$event_requirement_id=$row1['event_requirement_id'];
						
						$sub_service= mysql_query("SELECT * FROM sp_event_requirements  where event_requirement_id='$event_requirement_id'");
						$sub_service_id_new = mysql_fetch_array($sub_service) or die(mysql_error());
						$sub_service_id=$sub_service_id_new['sub_service_id'];
						//echo $event_requirement_id;
						//professional name
						$professional= mysql_query("SELECT * FROM sp_event_professional  where event_requirement_id='$event_requirement_id'");
						if(mysql_num_rows($professional) < 1 )
						{
							$professional_vender_id='';
						}
						else
						{
						$professional_new = mysql_fetch_array($professional) or die(mysql_error());
						$professional_vender_id=$professional_new['professional_vender_id'];
						
						$professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$professional_vender_id'");
						$professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
						$name=$professional_name_abc['name'];
						$title=$professional_name_abc['title'];
						$first_name=$professional_name_abc['first_name'];
						$middle_name=$professional_name_abc['middle_name'];
						
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
							
						$row4 = mysql_fetch_array($plan_of_care) or die(mysql_error());
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
					}
					
					
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
								$plan_of_care= mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id'");
								
								if(mysql_num_rows($plan_of_care) < 1 )
								{
									$numberDays_qty ='';
								}
								else
								{
									$row4 = mysql_fetch_array($plan_of_care) or die(mysql_error());
									$finalcost=$row4['service_cost'];
									//$finalcost=$finalcost / $numberDays_qty;
									$finalcost=round($finalcost);
								}
							}
							else
							{
								$finalcost=$cost * $numberDays_qty;
							}
							if($recomended_service_name=='Conveyance1')
							{
								$professional_name='Conveyance_Cost';
							}
							else
							{
								$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
							}
							
							//receipt
							$payment_receipt_no_voucher_no=$payment_rows['payment_receipt_no_voucher_no'];
							$branch=$payment_rows['branch'];
							
							$date_time = explode(" ",$payment_rows['date_time']);
							$exploded_date = $date_time[0];
							$date = date('d-m-Y',strtotime($exploded_date));
							
							$payments_event_code = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
							$row1 = mysql_fetch_array($payments_event_code) or die(mysql_error());
							$patient_id=$row1['patient_id'];
							$bill_no_ref_no=$row1['bill_no_ref_no'];
							$event_code=$row1['event_code'];
							
							$payments_event_code = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
							$row2 = mysql_fetch_array($payments_event_code) or die(mysql_error());
							$first_name=$row2['first_name'];
							$middle_name=$row2['middle_name'];
							$name=$row2['name'];
							$hhc_code=$row2['hhc_code'];
							$email_id=$row2['email_id'];
							if($email_id=='')
							{
								$email_id='-';
							}
							$Refund=$payment_rows['Transaction_Type'];
				$type=$payment_rows['type'];
				if($type=='Cash')
				{
					$bank_cash='Cash - Services - DMH';
				}
				if($type=='Card' OR $type=='Cheque' OR $type=='NEFT')
				{
					$bank_cash='HDFC BANK C.C A/C - 50200010027418';
				}
				if($payment_rows['cheque_DD__NEFT_date']!='0000-00-00')
			{
			$date_time1 = explode(" ",$payment_rows['cheque_DD__NEFT_date']);
			$exploded_date1 = $date_time1[0];
			$cheque_DD__NEFT_date = date('d-m-Y',strtotime($exploded_date1));
			}
			else{
				$cheque_DD__NEFT_date='-';
			}
			$Voucher_Ref=$hhc_code.'/'.$event_code;	
			
			$curdate=strtotime($date);
			$predate=strtotime('01-04-2017');
			if($curdate >= $predate)
			{
				$finasial_year='2017';
				$finasial_year1='18';
			}
			if($curdate < $predate)
			{
				$finasial_year='2016';
				$finasial_year1='17';
			}			
							echo '<tr>
							<td>'.$payment_rows['branch'].'</td>
							<td>'.$branch.'/'.$finasial_year.'-'.$finasial_year1.'/'.$payment_receipt_no_voucher_no.'</td>
							<td>'.$date.'</td>
							<td>'.$branch.'/'.$finasial_year.'-'.$finasial_year1.'/'.$bill_no_ref_no.'</td>
							<td>'.$first_name.' '.$middle_name.' '.$name.'</td>
							<td>'.$email_id.'</td>
							<td>'.$finalcost.'</td>
							<td>'.$professional_name.'</td>
							<td>'.$bank_cash.'</td>
							<td>'.$payment_rows['cheque_DD__NEFT_no'].'</td>
							<td>'.$cheque_DD__NEFT_date.'</td>
							<td>'.$payment_rows['party_bank_name'].'</td>
							
							<td>'.$Voucher_Ref.' - '.$payment_rows['Comments'].'</td>';
						   
							echo '</tr>';
							$total=$finalcost+$total;
						
			}	}
					
			
			//$i++;
			}	
			
			
			}
			$i++;
		}
				
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
			}
		}
?>