<?php   require_once 'inc_classes.php';
        //require_once '../classes/locationsClass.php';
        //$locationsClass = new locationsClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    /*$recArgs=$_SESSION['location_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $locationsClass->LocationsList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];*/
	
		$formDate=$_GET['formDate_invoice'];
$date1=date_create("$formDate");
 $formDate1=date_format($date1,"Y-m-d H:i:s");
//echo $formDate1;
$toDate=$_GET['toDate_invoice'];
$date2=date_create("$toDate");
 $toDate2=date_format($date2,"Y-m-d H:i:s");
 $total=0;
	if($formDate!='' and $toDate!='')
	{
		$payments = mysql_query("SELECT * FROM sp_payments where hospital_id=11 and status=1 and date_time BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY date_time DESC");
	}
	else
	{
		$payments = mysql_query("SELECT * FROM sp_payments where hospital_id=11 and status=1 ORDER BY date_time DESC");
		
	}
	$row_count = mysql_num_rows($payments);
	
    if($row_count > 0)
    {
		$today_date=date("Y-m-d");
		$date_time = explode(" ",$today_date);
			$exploded_date = $date_time[0];
			$date = date('d-m-Y',strtotime($exploded_date));
		$datas .=  '<div align="Right">
					<span>Print on '.$date.'</span><br>
					</div>';
		$datas .=  '<div align="center">
					<span><b>Spero Healthcare Innovations Pvt. Ltd - 2017-18</b></span><br>
					<span><b>Cash - Service - DMH Book</span></b><br>
					<span>Cash - In - hand</span><br>
					
					</div>';
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
					<th width="5%">Date</th>
					<th width="5%">Particulars</th>
                    <th width="5%">Vch Type</th>
					<th width="5%">Vch No./Excise Inv.No.</th>
					<th width="5%">Type</th>
                    <th width="5%">Debit</th>
                        </tr>';
             //$i = 0;
            for($i=1; $i<=$row_count;)
			{
			while ($payment_rows = mysql_fetch_array($payments))
				{		
			$event_id1=$payment_rows['event_id'];
				$Refund=$payment_rows['Transaction_Type'];
				$type=$payment_rows['type'];
				$payments_event_code = mysql_query("SELECT * FROM sp_events  where event_id='$event_id1'");
				$row1 = mysql_fetch_array($payments_event_code) or die(mysql_error());
				$patient_id=$row1['patient_id'];
				$bill_no_ref_no=$row1['bill_no_ref_no'];
				$event_code=$row1['event_code'];
				//echo $event_id1; 
				
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
			include "include/paging_script.php";
			
			 $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$date.'</td>';
             $datas .= '<td><b>'.$first_name.' '.$middle_name.' '.$name.'</b><br>
							'.$payment_rows['professional_name'].'<br>
							'.$hhc_code.'/'.$event_code.'</td>';
			$datas .= '<td>'.$receipt.'-'.$branch.'</td>';
			$datas .= '<td>'.$payment_receipt_no_voucher_no.'</td>';
			$datas .= '<td>'.$type.'</td>';
			$datas .= '<td>'.$payment_rows['amount'].'</td>';
			
			$datas .= '</tr>';
			$total=$payment_rows['amount']+$total;
	}
			$i++;
			
				}
				$datas .= "<td></td>";
				$datas .= "<td></td>";
				$datas .= "<td></td>";
				$datas .= "<td></td>";
				$datas .= "<td style='text-align:right;font-weight:bold'>Total</td>";
				 $datas .= "<td style='font-weight:bold'>$total</td>";
				
			}
        }
        else
		{
			$datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
					<th width="12%">Date</th>
					<th width="12%">Particulars</th>
                    <th width="12%">Vch Type</th>
					<th width="12%">Vch No./Excise Inv.No.</th>
					<th width="12%">Type</th>
                    <th width="12%">Debit</th>
                        </tr>';
				 $datas.= "<tr>";
				  //echo "<td colspan="2">"."Sum: $180"."</td>";
              $datas.= "<td colspan='6' align='middle'>" . "Record Not found for this date" . "</td>";
              

              $datas.= "</tr>";
				 $datas.= "</div>";
				 $datas.= "</table>";
            //$datas.='No record found related to your search criteria';
		}
    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."DayPrintList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=DayPrintList".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>