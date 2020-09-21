<?php
include "inc_classes.php";
    include "admin_authentication.php";      
    include "pagination-include.php";
	
$service_professional_id=$_GET['service_professional_id'];
$Previous_date=$_GET['Previous_date'];
$formDate=$_GET['formDate1'];

$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");
$formDate2=date_format($date1,"Y-m-d");
//echo $formDate2;
$toDate=$_GET['toDate2'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
$toDate1=date_format($date2,"Y-m-d");
//echo $service_professional_id;


$Professional_Name1= mysql_query("SELECT * FROM sp_service_professionals where service_professional_id='$service_professional_id'");
$Professional_Name_row = mysql_fetch_array($Professional_Name1) or die(mysql_error());
$first_name=$Professional_Name_row['first_name'];
$middle_name=$Professional_Name_row['middle_name'];
$name=$Professional_Name_row['name'];
$title=$Professional_Name_row['title'];
$Physio_Rate=$Professional_Name_row['Physio_Rate'];
$Professional_Name=$title.' '.$first_name.' '.$name;
?>
<div id="physio_details">
  <input type="button" value="Close" style="background-color:#00cfcb;float: right;border-radius: 25px;" onclick="Close_Popup_unit();"></input>
  <div align="center"style="color:#00cfcb;font-size:25px;margin-top:10px;">Details Of Unit</div>
   <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
   <?php
	echo '<input type="button" style="background-color:#ffbf00;border-radius:15px;padding-left:10px;padding-right:10px;" value="Download Details" onclick="physiotherapy_Unit_report_dw(\'' . $service_professional_id . '\',\''.$Previous_date.'\',\'' . $formDate . '\',\''.$toDate.'\')"; >';
   ?> 
    
    </div>
	<br>
  <br>
  <div class="col-lg-12">
  <?php 
   echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
        <tr> 
            <th width="4%">Voucher Date</th>
			<th width="7%">Party Name</th>
            <th width="7%">Stock Item</th>
			<th width="2%">QTY</th>
			<th width="4%">Rate</th>
			<th width="3%">Amount</th>
            <th width="3%">Start Date</th>
			<th width="3%">End Date</th>
			<th width="2%">UOM</th>
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
	//echo $event_id;
	//echo $event_requirement_id;
	$sub_service= mysql_query("SELECT * FROM sp_event_requirements  where event_requirement_id='$event_requirement_id' OR event_id='$event_id'");
							$sub_service_id_new =  mysql_fetch_array($sub_service) or die(mysql_error());
							$sub_service_id=$sub_service_id_new['sub_service_id'];
							$service_id=$sub_service_id_new['service_id'];
							//echo $service_id;
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
			echo '<tr>
				<td>'.$event_date.'</td>
				<td>'.$P_first_name.' '.$P_middle_name.' '.$P_name.'</td>
				<td>'.$recommomded_service.'</td>
				<td>'.$Unitcount.'</td>
				<td>'.$cost.'</td>
				<td>'.$finalcost.'</td>
				<td>'.$service_date.'</td>
				<td>'.$service_date_to.'</td>
				<td>'.$UOM.'</td>';
				echo '</tr>';
				
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
			echo '<tr>
				<td>'.$event_date.'</td>
				<td>'.$P_first_name.' '.$P_middle_name.' '.$P_name.'</td>
				<td>'.$recommomded_service.'</td>
				<td>'.$Unitcount.'</td>
				<td>'.$cost.'</td>
				<td>'.$finalcost.'</td>
				<td>'.$service_date.'</td>
				<td>'.$service_date_to.'</td>
				<td>'.$UOM.'</td>';
				echo '</tr>';
				
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
			echo '<tr>
				<td>'.$event_date.'</td>
				<td>'.$P_first_name.' '.$P_middle_name.' '.$P_name.'</td>
				<td>'.$recommomded_service.'</td>
				<td>'.$numberDays_qty.'</td>
				<td>'.$cost.'</td>
				<td>'.$finalcost.'</td>
				<td>'.$service_date.'</td>
				<td>'.$service_date_to.'</td>
				<td>'.$UOM.'</td>';
				echo '</tr>';
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
$Total_payment=$total * $Physio_Rate ; 
//$Total_payment=floor($Total_payment);
$TDS=$Total_payment * 0.1;
//$TDS=floor($TDS);
$gross_total= $Total_payment - $TDS ;
//$gross_total=floor($gross_total);
$Net_Amount = $Conveyance + $gross_total; 
$Net_Amount=floor($Net_Amount);
	echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
        <tr> 
            <th width="5%">Total</th>
			<th width="5%">Total Cost</th>
            <th width="5%">TDS 10 %</th>
			<th width="5%">Gross Total</th>
			<th width="5%">Conveyance</th>
			<th width="5%">Net Amount</th>
        </tr>';	
		echo '<tr>
				<td >'.$total.'</td>
				<td >'.$Total_payment.'</td>
				<td >'.$TDS.'</td>
				<td >'.$gross_total.'</td>
				<td >'.$Conveyance.'</td>
				<td >'.$Net_Amount.'</td>';
				
				echo '</tr>';
	echo '</div>';
  ?>
  
  </div>
</div>