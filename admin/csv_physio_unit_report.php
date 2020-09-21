<?php   require_once 'inc_classes.php';
        //require_once '../classes/locationsClass.php';
        //$locationsClass = new locationsClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
 
	
$service_professional_id=$_GET['service_professional_id'];
$Previous_date=$_GET['Previous_date'];
$formDate=$_GET['formDate'];
$toDate=$_GET['toDate'];

$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");
$formDate2=date_format($date1,"Y-m-d");

$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
$toDate1=date_format($date2,"Y-m-d");

$Professional_Name1= mysql_query("SELECT * FROM sp_service_professionals where service_professional_id='$service_professional_id'");
$Professional_Name_row = mysql_fetch_array($Professional_Name1) or die(mysql_error());
$first_name=$Professional_Name_row['first_name'];
$middle_name=$Professional_Name_row['middle_name'];
$name=$Professional_Name_row['name'];
$title=$Professional_Name_row['title'];
$Physio_Rate=$Professional_Name_row['Physio_Rate'];
$Professional_Name=$title.' '.$first_name.' '.$name;

$today_date=date("Y-m-d");
		$date_time = explode(" ",$today_date);
			$exploded_date = $date_time[0];
			$date = date('d-m-Y',strtotime($exploded_date));
		
		$datas .=  '<div align="Right">
			<span>Print on '.$date.'</span><br>
			</div>';
		$datas .=  '<div align="center">
					<span><b>Spero Healthcare Innovations Pvt. Ltd - 2017-18</b></span><br>
					<span><b>Unit Report For '.$Professional_Name.' </span></b><br>
					</div>';
		$datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                    <tr height="30">
					<th width="6%">Date</th>
					<th width="10%">Party Name</th>
					<th width="4%">Start Date</th>
					<th width="4%">End Date</th>
					<th width="3%">Unit</th>
					</tr>';
$total=0;
$Total_payment=0 ; 
$TDS=0;
$gross_total= 0;
$Net_Amount = 0; 
$Conveyance=0;


$event_professional = mysql_query("SELECT * FROM sp_event_plan_of_care where (added_date BETWEEN '$Previous_date%' AND '$toDate2%')  and professional_vender_id='$service_professional_id' Order By event_id");
while ($event_professional_row = mysql_fetch_array($event_professional))
{
	$event_requirement_id=$event_professional_row['event_requirement_id'];
	$plan_of_care_id=$event_professional_row['plan_of_care_id'];
	$event_id=$event_professional_row['event_id'];
	$service_date=$event_professional_row['service_date'];
	$service_date_to=$event_professional_row['service_date_to'];
	//echo $event_id;
	
	$voucher_date= mysql_query("SELECT * FROM sp_events where event_id='$event_id'");
	$voucher_date_row = mysql_fetch_array($voucher_date) or die(mysql_error());
	$event_date=$voucher_date_row['event_date'];
	$patient_id=$voucher_date_row['patient_id']; 
	$event_date=date_create("$event_date");
	$event_date=date_format($event_date,"d-m-Y");
	//echo $event_date;

	$patient_Name= mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
	$patient_Name_row = mysql_fetch_array($patient_Name) or die(mysql_error());
	$P_first_name=$patient_Name_row['first_name'];
	$P_middle_name=$patient_Name_row['middle_name'];
	$P_name=$patient_Name_row['name'];
	
	$sub_service= mysql_query("SELECT * FROM sp_event_requirements  where event_requirement_id='$event_requirement_id'");
							$sub_service_id_new = mysql_fetch_array($sub_service) or die(mysql_error());
							$sub_service_id=$sub_service_id_new['sub_service_id'];
							$service_id=$sub_service_id_new['service_id'];
							
							$sub_service= mysql_query("SELECT * FROM sp_sub_services  where sub_service_id='$sub_service_id'");
							if(mysql_num_rows($sub_service) < 1 )
							{
							$recommomded_service='';
							$cost='';
							}
							else
							{
							$row3 = mysql_fetch_array($sub_service) or die(mysql_error());
							$recommomded_service=$row3['recommomded_service'];
							$service_id=$row3['service_id'];
							
							$service= mysql_query("SELECT * FROM sp_services  where service_id='$service_id'");
							$service_name = mysql_fetch_array($service) or die(mysql_error());
							$recomended_service_name=$service_name['service_title'];
							
							$UOM=$row3['UOM'];
							$cost=$row3['cost'];
							}
						
	
	if($service_date_to >= $formDate2)
	{
		if($service_date <=$formDate2 )
		{
			$Unitcount=0;
			$service_date_new= date('d-m-Y',strtotime($service_date));
			$service_date_to_new = date('d-m-Y',strtotime($service_date_to));
			$startTimeStamp = strtotime($service_date);
			$endTimeStamp = strtotime($service_date_to);
			$timeDiff = abs($endTimeStamp - $startTimeStamp);
			$numberDays = $timeDiff/86400;  // 86400 seconds in one day
			// and you might want to convert to integer
			$numberDays1=$numberDays + 1 ;
			$numberDays_qty = intval($numberDays1);
			
			$begin = new DateTime($service_date);
			$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
			$end = new DateTime($end);
			//echo $end;
			$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
			foreach($daterange as $date)
			{
				$date_service=$date->format("Y-m-d") ;
				if (($formDate2 <= $date_service) && ($toDate1 > $date_service))
				{
					$Unitcount=$Unitcount+1;
				}
			}
			if($Unitcount!=0)
			{	
				if($recommomded_service=='Other')
				{
					$cost=$row4['service_cost'];
					$cost=$finalcost / $Unitcount;
				}
				else
				{
					if(($service_id==17 OR $service_id==13) AND $sub_service_id!=425)
					{
						$finalcost=$row4['service_cost']; 
					}
					else
					{
						$finalcost=$cost * $Unitcount; 
					}
				}
				if($service_id==10)	
				{
					$Unitcount='-';		
				}
				
				include "include/paging_script.php";
		
				$datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
							<td>'.$event_date.'</td>';
				$datas .= '<td>'.$P_first_name.' '.$P_middle_name.' '.$P_name.'</td>';
				$datas .= '<td>'.$service_date.'</td>';
				$datas .= '<td>'.$service_date_to.'</td>';
				$datas .= '<td>'.$Unitcount.'</td>';
				$datas .= '</tr>';
			
				
				if($service_id==10)	
				{
					$Conveyance=$Conveyance+$finalcost;
				}
				else
				{
					$total=$total+$finalcost;
				}
			}
		}
		else if($service_date_to>=$toDate1)
		{
			$Unitcount=0;
			$begin = new DateTime($service_date);
			$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
			$end = new DateTime($end);
			//echo $end;
			$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
			foreach($daterange as $date)
			{
				$date_service=$date->format("Y-m-d") ;
				if (($formDate2 <= $date_service) && ($toDate1 > $date_service))
				{
					$Unitcount=$Unitcount+1;
					//echo $Unitcount;
				}
			}
			if($Unitcount!=0)
			{
				if($recommomded_service=='Other')
				{
					$cost=$row4['service_cost'];
					$cost=$finalcost / $Unitcount;
				}
				else
				{
					if(($service_id==17 OR $service_id==13) AND $sub_service_id!=425)
					{
						$finalcost=$row4['service_cost']; 
					}
					else
					{
						$finalcost=$cost * $Unitcount; 
					}
				}
				if($service_id==10)	
				{
					$Unitcount='-';		
				}
			
				
				include "include/paging_script.php";
		
				$datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
							<td>'.$event_date.'</td>';
				$datas .= '<td>'.$P_first_name.' '.$P_middle_name.' '.$P_name.'</td>';
				$datas .= '<td>'.$service_date.'</td>';
				$datas .= '<td>'.$service_date_to.'</td>';
				$datas .= '<td>'.$Unitcount.'</td>';
				$datas .= '</tr>';
				
				if($service_id==10)	
				{
					$Conveyance=$Conveyance+$finalcost;
				}
				else
				{
					$total=$total+$finalcost;
				}
			}
		}
		else
		{
			$service_date_new= date('d-m-Y',strtotime($service_date));
			$service_date_to_new = date('d-m-Y',strtotime($service_date_to));
			$startTimeStamp = strtotime($service_date);
			$endTimeStamp = strtotime($service_date_to);
			$timeDiff = abs($endTimeStamp - $startTimeStamp);
			$numberDays = $timeDiff/86400;  // 86400 seconds in one day
			// and you might want to convert to integer
			$numberDays1=$numberDays + 1 ;
			$numberDays_qty = intval($numberDays1);
			if($numberDays_qty!=0)
			{
				if($recommomded_service=='Other')
				{
					$cost=$row4['service_cost'];
					$cost=$finalcost / $numberDays_qty;
				}
				else
				{
					if(($service_id==17 OR $service_id==13) AND $sub_service_id!=425)
					{
						$finalcost=$row4['service_cost']; 
					}
					else
					{
						$finalcost=$cost * $numberDays_qty; 
					}
				}
				if($service_id==10)	
				{
					$numberDays_qty='-';		
				}
			
				
				include "include/paging_script.php";
		
				$datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
							<td>'.$event_date.'</td>';
				$datas .= '<td>'.$P_first_name.' '.$P_middle_name.' '.$P_name.'</td>';
				$datas .= '<td>'.$service_date.'</td>';
				$datas .= '<td>'.$service_date_to.'</td>';
				$datas .= '<td>'.$numberDays_qty.'</td>';
				$datas .= '</tr>';
				if($service_id==10)	
				{
					$Conveyance=$Conveyance+$finalcost;
				}
				else
				{
					$total=$total+$finalcost;
				}
				//$Total_payment=
			}
		}
	}
	
	
}

$datas .=  '<table >
            <tr height="30">
			
			</tr>';

$Total_payment=$total * $Physio_Rate ;

//$Total_payment=$total * 0.8 ; 
$TDS=$Total_payment * 0.1;
$gross_total= $Total_payment - $TDS ;
$Net_Amount = $Conveyance + $gross_total; 
		
	$datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                    <tr height="30">
					<th width="5%">Total</th>
					<th width="5%">TDS 10 %</th>
					<th width="5%">Gross Total</th>
					<th width="5%">Conveyance</th>
					<th width="5%">Net Amount</th>
					</tr>';
		
	
				include "include/paging_script.php";
		
				$datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
							<td>'.$Total_payment.'</td>';
				$datas .= '<td>'.$TDS.'</td>';
				$datas .= '<td>'.$gross_total.'</td>';
				$datas .= '<td>'.$Conveyance.'</td>';
				$datas .= '<td>'.$Net_Amount.'</td>';
				$datas .= '</tr>';
	
  ?>
 </div>
</div>
<?php          

    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."Unit_Report.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=$Professional_Name".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>