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
 $hospital_id=$_GET['hospital_id'];
	if($formDate=='' and $toDate=='')
	{
		$events = mysql_query("SELECT * FROM sp_events where estimate_cost!='2' and hospital_id='$hospital_id' AND event_status >= '3' ORDER BY added_date ASC");
	}
	else
	{
		$events = mysql_query("SELECT * FROM sp_events where (added_date BETWEEN '$formDate1%' AND '$toDate2%') AND (estimate_cost!='2') and hospital_id='$hospital_id'AND event_status >= '3' ORDER BY added_date ASC");
		
	}
	
	
	$row_count = mysql_num_rows($events);
	
    if($row_count > 0)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
							 <td width="3%">Branch</td>
					<td width="2%">Voucher Number/Bill No</td>
                    <td width="2%">Voucher Type</td>
					<td width="2%">Voucher Date</td>
                    <td width="2%">Voucher Ref</td>
                    <td width="2%">Party Name</td>
					<td width="2%">Address 1</td>
                    <td width="2%">Address 2</td>
                    <td width="2%">Address 3 / Phone No.</td>
					<td width="2%">Stock Item</td>
					 <td width="2%">Qty</td>
                    <td width="2%">Rate</td>
					<td width="2%">Amount</td>
					<td width="2%">From Date</td>
					<td width="2%">To Date</td>
                    <td width="2%">Name OF Professional</td>
					<td width="2%">Narration</td>
					<td width="2%">UOM</td>
					<td width="2%">Category</td>
                        </tr>';
             //$i = 0;
            for($i=1; $i<=$row_count;)
			{
				while ($events_rows = mysql_fetch_array($events))
				{		
					$event_id=$events_rows['event_id'];
					//$branch_code=$events_rows['branch_code'];
					$Hospital_branch=mysql_query("SELECT branch FROM sp_hospitals where hospital_id='$hospital_id'") or die(mysql_error());
					$row_Hospital_branch = mysql_fetch_array($Hospital_branch) or die(mysql_error());
					$branch_code=$row_Hospital_branch['branch'];
					$bill_no_ref_no=$events_rows['bill_no_ref_no'];
					$Voucher_Type='Sales'.'-'.$branch_code;
					$event_code=$events_rows['event_code'];
					$event_date=$events_rows['event_date'];
					$patient_id=$events_rows['patient_id'];
					$Invoice_narration=$events_rows['invoice_narration_desc'];
					$Invoice_note=$events_rows['note'];
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
					
					
					
					/*$payments = mysql_query("SELECT * FROM sp_payments  where event_id='$event_id'");
					if(mysql_num_rows($payments) < 1 )
					{
						$professional_name='';
						$Comments='';
					}
					else
					{
						$payments_id = mysql_fetch_array($payments) or die(mysql_error());
						$professional_name=$payments_id['professional_name'];
						$Comments=$payments_id['Comments'];
					}*/
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
							}
						
							
			if (date('m') > 3) {
				$financial_year = date('Y')."-".(date('y') +1);
			}
			else {
				$financial_year = (date('Y')-1)."-".date('y');
			}
			if($sub_service_id!=423)
						{
			include "include/paging_script.php";
			
			 $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$branch_code.'</td>';
             $datas .= '<td>'.$branch_code.'/'.$financial_year.'/'.$bill_no_ref_no.'</td>';
			$datas .= '<td>'.$Voucher_Type.'</td>';
			$datas .= '<td>'.$event_date.'</td>';
			$datas .= '<td>'.$Voucher_Ref.'</td>';
			$datas .= '<td>'.$first_name1.' '.$middle_name1.' '.$name1.'</td>';
			$datas .= '<td>'.$residential_address.'</td>';
			$datas .= '<td>'.$permanant_address.'</td>';
			$datas .= '<td>'.$mobile_no.'</td>';
			$datas .= '<td>'.$recommomded_service.'</td>';
			$datas .= '<td>'.$numberDays_qty.'</td>';
			$datas .= '<td>'.$cost.'</td>';
			$datas .= '<td>'.$finalcost.'</td>';
			$datas .= '<td>'.$service_date_new.'</td>';
			$datas .= '<td>'.$service_date_to_new.'</td>';
			$datas .= '<td>'.$professional_name.'</td>';
			$datas .= '<td>'.$Voucher_Ref.' - '.$Invoice_narration.'-'.$Invoice_note.'</td>';
			$datas .= '<td>'.$UOM.'</td>';
			$datas .= '<td>'.$recomended_service_name.'</td>';
			
			$datas .= '</tr>';
			$total=$payment_rows['amount']+$total;
			$i++;
						}			
			}
//Ashu
						}}

			}
					
			
			$i++;
				}
			}
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."InvoiceList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=incoiceList".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>