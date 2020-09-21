<?php   require_once 'inc_classes.php';
        //require_once '../classes/locationsClass.php';
        //$locationsClass = new locationsClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
 
	
		$formDate=$_GET['formDate_receipt'];
$date1=date_create("$formDate");
 $formDate1=date_format($date1,"Y-m-d H:i:s");
//echo $formDate1;
$toDate=$_GET['toDate_receipt'];
$date2=date_create("$toDate");
 $toDate2=date_format($date2,"Y-m-d H:i:s");



$Pfrofessional_list=mysql_query("select sp_professional_services.service_professional_id, sp_service_professionals.title,sp_service_professionals.first_name, sp_service_professionals.middle_name,sp_service_professionals.name, sp_service_professionals.mobile_no, sp_service_professionals.work_phone_no, sp_service_professionals.email_id from sp_professional_services inner join sp_service_professionals on sp_professional_services.service_professional_id=sp_service_professionals.service_professional_id where sp_professional_services.service_id=3 AND sp_service_professionals.status=1 ORDER BY sp_professional_services.service_professional_id");
$row_count = mysql_num_rows($Pfrofessional_list);
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
					<span><b>Jon Closure Report</span></b><br>
					</div>';
	$datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
							 <td width="3%">Sr. No.</td>
					<td width="5%">Professional Name</td>
                    <td width="5%">Mobile no</td>
					<th width="5%">Total JobClosure</th>
			<th width="5%">JobClosure Done</th>
			<th width="5%">JC Total Remaining</th>
			<th width="5%">Actual remaing till this month</th>
					
                        </tr>';
	 $count=1;
$TotalCount=0;
$Donecount=0;
$Remainingcount=0;
$count=1;
$TotalCount=0;
$Donecount=0;
$Remainingcount=0;
	while ($payment_Name= mysql_fetch_array($Pfrofessional_list))
	{		
		$service_professional_id=$payment_Name['service_professional_id'];
		$first_name=$payment_Name['first_name'];
		$middle_name=$payment_Name['middle_name'];
		$name=$payment_Name['name'];
		$title=$payment_Name['title'];
		$mobile_no=$payment_Name['mobile_no'];
		
		if($formDate=='' and $toDate=='')
		{
		$Total_active_calls=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and (service_id='3' or service_id='16') and added_date>='2017-01-01 00:00:00' ");
		$row_count = mysql_num_rows($Total_active_calls);
		}
		else
		{
			$Total_active_calls=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and (service_id='3' or service_id='16') and added_date BETWEEN '$formDate1%' AND '$toDate2%' ");
			$row_count = mysql_num_rows($Total_active_calls);
		}
		
		if($formDate=='' and $toDate=='')
		{
		$Done_count=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and service_closed='Y' and (service_id='3' or service_id='16') and added_date>='2017-01-01 00:00:00' ");
		$Done_count = mysql_num_rows($Done_count);
		//$event_id=
		}
		else
		{
			$Done_count=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and service_closed='Y' and (service_id='3' or service_id='16') and added_date BETWEEN '$formDate1%' AND '$toDate2%' ");
		$Done_count = mysql_num_rows($Done_count);
		}
		if($formDate=='' and $toDate=='')
		{
			$remain_count=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and service_closed='N' and (service_id='3' or service_id='16') and added_date>='2017-01-01 00:00:00' ");
			$remaing_count = mysql_num_rows($remain_count);
		}
		else
		{
			$remain_count=mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and service_closed='N' and (service_id='3' or service_id='16') and added_date BETWEEN '$formDate1%' AND '$toDate2%' ");
			$remaing_count = mysql_num_rows($remain_count);
		}
		$remaing_count_total = mysql_num_rows($remain_count);
		if($remaing_count>0)
		{
			while($remain_count1=mysql_fetch_array($remain_count))
			{
				$event_requirement_id=strip_tags($remain_count1['event_requirement_id']);
				//echo $event_requirement_id;
				$Enddate=mysql_query("SELECT * FROM sp_event_plan_of_care where event_requirement_id='$event_requirement_id'") or die(mysql_error());
				while($Enddate=mysql_fetch_array($Enddate))
				{
					$service_date_to=strip_tags($Enddate['service_date_to']);
					//echo $service_date_to;
					if($service_date_to>='2017-07-31')
					{
						$remaing_count=$remaing_count - 1;
						//$remain_count=11;
						//echo 'abc';
						//$abc++;
					}
				}
			
			}
		}
		else
		{
			$remaing_count=0;
		}
		include "include/paging_script.php";
		
		 $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$count.'</td>';
             $datas .= '<td>'.$title.'. '.$first_name.' '.$middle_name.' '.$name.'</td>';
			$datas .= '<td>'.$mobile_no.'</td>';
			$datas .= '<td>'.$row_count.'</td>';
			
			$datas .= '<td>'.$Done_count.'</td>';
			$datas .= '<td>'.$remaing_count_total.'</td>';
			$datas .= '<td>'.$remaing_count.'</td>';
			$datas .= '</tr>';
		
		$count++;	
		$TotalCount=$TotalCount+$row_count ;
		$Donecount=$Donecount + $Done_count;
		$Remainingcount=$Remainingcount + $remain_count;
	}
	
}
else
{
	$datas.='No record found related to your search criteria';
}
            

    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."JobClosureReport.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=JobClosureReport".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>