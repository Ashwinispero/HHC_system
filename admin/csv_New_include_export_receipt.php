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

	if($formDate == '' && $toDate == '') {
		$payments = mysql_query("SELECT * FROM sp_payment_details where hospital_id='$hospital_id' and status=1 ORDER BY date  DESC");
	}
	else {
		$payments = mysql_query("SELECT * FROM sp_payment_details where hospital_id='$hospital_id' and status=1 and date BETWEEN '$formDate1%' AND '$toDate2%' ");
	}
	
	//$payments = mysql_query("SELECT * FROM sp_payments ORDER BY date_time DESC");
	$total=0;
	//echo 'abc';
	$row_count = mysql_num_rows($payments);

	//echo $row_count;
	if($row_count > 0)
		{
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
							 <td width="3%">Branch</td>
					<td width="5%">Payment Receipt No/Voucher No</td>
                    <td width="5%">Payment Receipt Date/ Vch date</td>
					<td width="5%">Bill No/Ref no.</td>
                    <td width="5%">Customer Name</td>
					
                    <td width="5%">Amount</td>
					<td width="5%">Professional</td>
                    <td width="5%">Bank/Cash</td>
                    <td width="5%">Cheque/DD/NEFT No.</td>
					<td width="5%">Cheque/DD/NEFT Date</td>
                    <td width="5%">Party Bank Name</td>
					<td width="5%">Narration</td>
					
                        </tr>';
           
			while ($payment_rows = mysql_fetch_array($payments))
				{		
			
				$event_requirement_id=$payment_rows['event_requrement_id'];
				$payment_id=$payment_rows['payment_id'];
				$amount=$payment_rows['amount'];
				$event_id=$payment_rows['event_id'];
				$date_time = explode(" ",$payment_rows['date']);
				$exploded_date = $date_time[0];
				$date = date('d-m-Y',strtotime($exploded_date));
				
				$payments_details= mysql_query("SELECT * FROM sp_payments  where payment_id='$payment_id'");
				$payment_detail_row = mysql_fetch_array($payments_details) or die(mysql_error());
				$payment_receipt_no_voucher_no=$payment_detail_row['payment_receipt_no_voucher_no'];
				$branch=$payment_detail_row['branch'];
				$party_bank_name=$payment_detail_row['party_bank_name'];
				
				$Hospital_branch=mysql_query("SELECT branch FROM sp_hospitals where hospital_id='$hospital_id'") or die(mysql_error());
					$row_Hospital_branch = mysql_fetch_array($Hospital_branch) or die(mysql_error());
					$branch=$row_Hospital_branch['branch'];
				$payments_event_code = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
				$row1 = mysql_fetch_array($payments_event_code) or die(mysql_error());
				$patient_id=$row1['patient_id'];
				$bill_no_ref_no=$row1['bill_no_ref_no'];
				$event_code=$row1['event_code'];
				//echo $payment_id;
				$payments_event_code = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
							$row2 = mysql_fetch_array($payments_event_code) or die(mysql_error());
							$Pfirst_name=$row2['first_name'];
							$Pmiddle_name=$row2['middle_name'];
							$Pname=$row2['name'];
							$hhc_code=$row2['hhc_code'];
							$email_id=$row2['email_id'];
							if($email_id=='')
							{
								$email_id='-';
							}
							$Refund=$payment_detail_row['Transaction_Type'];
							$type=$payment_detail_row['type'];
							if($type=='Cash')
							{
								$bank_cash="Cash - Services - $branch";
							}
							if($type=='Card' OR $type=='Cheque' OR $type=='NEFT')
							{
								$bank_cash='HDFC BANK C.C A/C - 50200010027418';
							}
							if($payment_detail_row['cheque_DD__NEFT_date']!='0000-00-00')
							{
							$date_time1 = explode(" ",$payment_detail_row['cheque_DD__NEFT_date']);
							$exploded_date1 = $date_time1[0];
							$cheque_DD__NEFT_date = date('d-m-Y',strtotime($exploded_date1));
							}
							else{
								$cheque_DD__NEFT_date='-';
							}
							$Voucher_Ref=$hhc_code.'/'.$event_code;	
				
					
					$sub_service= mysql_query("SELECT * FROM sp_event_requirements  where event_requirement_id='$event_requirement_id'");
					$sub_service_id_new = mysql_fetch_array($sub_service) or die(mysql_error());
					$sub_service_id=$sub_service_id_new['sub_service_id'];
					$service_id=$sub_service_id_new['service_id'];
					
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
						$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
					}
					else
					{
						if($service_id=='9')
						{
							$professional_name='Material Charges';
						}
						if($service_id=='10')
						{
							$professional_name='Conveyance_Cost';
						}
					}
					
					$sub_service= mysql_query("SELECT * FROM sp_sub_services  where sub_service_id='$sub_service_id'");
					$row3 = mysql_fetch_array($sub_service) or die(mysql_error());
					$recommomded_service=$row3['recommomded_service'];
					/*$service= mysql_query("SELECT * FROM sp_services  where service_id='$service_id'");
					$service_name = mysql_fetch_array($service) or die(mysql_error());
					$recomended_service_name=$service_name['service_title'];
					//echo $recomended_service_name;
					if($recomended_service_name=='Conveyance1')
							{
								$professional_name='Conveyance_Cost';
							}
							else
							{
								$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
							}	
							*/
	if (date('m') > 3) {
				$financial_year = date('Y')."-".(date('y') +1);
			}
			else {
				$financial_year = (date('Y')-1)."-".date('y');
			}							
				include "include/paging_script.php";
			
			 $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$branch.'</td>';
             $datas .= '<td>'.$branch.'/'.$financial_year.'/'.$payment_receipt_no_voucher_no.'</td>';
			$datas .= '<td>'.$date.'</td>';
			$datas .= '<td>'.$branch.'/'.$financial_year.'/'.$bill_no_ref_no.'</td>';
			$datas .= '<td>'.$Pfirst_name.' '.$Pmiddle_name.' '.$Pname.'</td>';
			
			$datas .= '<td>'.$amount.'</td>';
			$datas .= '<td>'.$professional_name.'</td>';
			$datas .= '<td>'.$bank_cash.'</td>';
			$datas .= '<td>'.$payment_detail_row['cheque_DD__NEFT_no'].'</td>';
			$datas .= '<td>'.$cheque_DD__NEFT_date.'</td>';
			$datas .= '<td>'.$payment_detail_row['party_bank_name'].'</td>';
			$datas .= '<td>'.$Voucher_Ref.' - '.$payment_detail_row['Comments'].'</td>';
			
			$datas .= '</tr>';
							$total=$finalcost+$total;
						
			}	}
					
			
			//$i++;
				
			
			
			
        
        else
            $datas.='No record found related to your search criteria';

    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."receiptList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=receiptList".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>