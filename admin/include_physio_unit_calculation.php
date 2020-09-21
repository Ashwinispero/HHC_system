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
$formDate2=date_format($date1,"Y-m-d");
 
$Previous_date = date('Y-m-d H:i:s', strtotime($formDate1 . ' -60 days'));
 //echo $Previous_date;
 
$toDate=$_GET['toDate_receipt'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
$toDate1=date_format($date2,"Y-m-d");
//echo $toDate2;
$count=1;
$TotalCount=0;
$Donecount=0;
$Remainingcount=0;

$Pfrofessional_list=mysql_query("select sp_professional_services.service_professional_id, sp_service_professionals.title,sp_service_professionals.first_name, sp_service_professionals.middle_name,sp_service_professionals.name, sp_service_professionals.mobile_no, sp_service_professionals.work_phone_no, sp_service_professionals.email_id 
from sp_professional_services
inner join sp_service_professionals 
on sp_professional_services.service_professional_id=sp_service_professionals
.service_professional_id 
where sp_professional_services.service_id=16
AND sp_service_professionals.status=1
ORDER BY sp_professional_services.service_professional_id");
$row_count = mysql_num_rows($Pfrofessional_list);
if($row_count > 0)
{
	echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
        <tr> 
            <th width="2%">Sr. No.</th>
			<th width="8%">Professional Name</th>
            <th width="4%">Mobile no</th>
			<th width="5%">Unit</th>
			<th width="5%">Action</th>
		</tr>';
	while ($payment_Name= mysql_fetch_array($Pfrofessional_list))
	{		
		//$service_professional_id=$payment_Name['service_professional_id'];
		$service_professional_id=$payment_Name['service_professional_id'];
		$first_name=$payment_Name['first_name'];
		$middle_name=$payment_Name['middle_name'];
		$name=$payment_Name['name'];
		$title=$payment_Name['title'];
		$mobile_no=$payment_Name['mobile_no'];
		
		if($formDate=='' and $toDate=='')
		{
			$Unitcount=0;
		$Total_active_calls=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and (service_id='3' or service_id='16') and added_date>='2017-01-01 00:00:00' ");
		$row_count = mysql_num_rows($Total_active_calls);
		while($row=mysql_fetch_array($Total_active_calls))
			{
				//$event_id=$row['event_id'];
				$event_requirement_id = $row['event_requirement_id'];
				$Professional_service= mysql_query("SELECT * FROM sp_event_plan_of_care where event_requirement_id='$event_requirement_id' ");
				$Professional_service1 = mysql_num_rows($Professional_service);
				if($Professional_service1 > 0)
				{
					
					while($row=mysql_fetch_array($Professional_service))
					{
						
						$service_date=strip_tags($row['service_date']);
						$service_date_to=strip_tags($row['service_date_to']);
						
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
					}
				}
			}
		}
		else
		{
			$Unitcount=0;
			$Total_active_calls=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and (service_id='3' or service_id='16') and added_date BETWEEN '$formDate1%' AND '$toDate2%' ");
			$row_count = mysql_num_rows($Total_active_calls);
			
			//echo $Previous_date;
			$Total_active_calls1=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and (service_id='3' or service_id='16') and added_date BETWEEN '$Previous_date%' AND '$toDate2%' ");
			while($row=mysql_fetch_array($Total_active_calls1))
			{
				//$event_id=$row['event_id'];
				$event_requirement_id = $row['event_requirement_id'];
				$Professional_service= mysql_query("SELECT * FROM sp_event_plan_of_care where event_requirement_id='$event_requirement_id' ");
				$Professional_service1 = mysql_num_rows($Professional_service);
				if($Professional_service1 > 0)
				{
					
					while($row=mysql_fetch_array($Professional_service))
					{
						
						$service_date=strip_tags($row['service_date']);
						$service_date_to=strip_tags($row['service_date_to']);
						
						$begin = new DateTime($service_date);
						
						$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
						$end = new DateTime($end);
						//echo $end;
						$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
						foreach($daterange as $date)
						{
							//echo $toDate1;
							$date_service=$date->format("Y-m-d") ;
							if (($formDate2 <= $date_service) && ($toDate1 > $date_service))
							{
								$Unitcount=$Unitcount+1;
							}
							
						}
					}
				}
			}
		}
		
		echo '<tr>
			<td>'.$count.'</td>
			<td>'.$title.'. '.$first_name.' '.$middle_name.' '.$name.'</td>
			<td>'.$mobile_no.'</td>
			
			<td>'.$Unitcount.'</td>
			<td align="center">
					 <input type="button" class="btn btn-download"  style="border-radius:15px;padding-left:10px;padding-right:10px;" value="View Details" onclick="Show_physio_details(\'' . $service_professional_id . '\' ,\''.$Previous_date.'\',\''.$formDate.'\',\''.$toDate.'\')"; >
					
					</td>';
		echo '</tr>';
		$count++;	
		
	}
	
}
}
?>
<div id="overlay_display_Physio_unit">
	<div id="popupwindow_display_Physio_unit">
	<div id="physio_details">
	
	</div>
	</div>
</div>
<style>
#overlay_display_Physio_unit
{
        width:100%;
		height:100%;
		background:#000;
		position:fixed;
		top:0;
		right:0;
		bottom:0;
		left:0;
		opacity:1.0;
		z-index:1000;
		display:none;
      
}
 #popupwindow_display_Physio_unit
   {
      width:1250px;
		height:470px;
		border-radius:10px;
		margin:0 auto;
		position:absolute;
		top:20%;
		right:20%;
		bottom:10%;
		left:15%;
		z-index:1500;
		border-radius: 20px;
    border: 3px solid #4D4D4D;
    background-color: #FFFFFF;
	 box-shadow: 0 2px 20px #666666;
	-moz-box-shadow: 0 2px 20px #666666;
	-webkit-box-shadow: 0 2px 20px #666666;
	overflow:scroll;
		display:none;
		
   }
</style>
<script>
 function Close_Popup()
		{
			$("#overlay_display_Physio_unit").fadeout("fast");
					$("#popupwindow_display_Physio_unit").fadeout("fast");
		}
</script>