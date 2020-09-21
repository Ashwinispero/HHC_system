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
	
	$event_id=$_GET['event_id'];
	$date_service=$_GET['date_service'];

	$event_detail=mysql_query("SELECT * FROM sp_events where event_id='$event_id'") or die(mysql_error());
	$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
	$patient_id=$event_detail['patient_id'];
	
	$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
	$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
	$name=$patient_nm['name'];
	$first_name=$patient_nm['first_name'];
	$middle_name=$patient_nm['middle_name'];

	
 $count=1;
$detials=mysql_query("select * from sp_assisted_living_schedule where event_id='$event_id' and service_date='$date_service'");
$row_count = mysql_num_rows($detials);
//echo $row_count;
if($row_count > 0)
{
	$today_date=date("Y-m-d");
	$date_time = explode(" ",$today_date);
	$exploded_date = $date_time[0];
	$date = date('d-m-Y',strtotime($exploded_date));
	$date_service = date('d-m-Y',strtotime($date_service));
	$datas .=  '<div align="Right">
					<span>Print on '.$date.'</span><br>
					</div>';
	$datas .=  '<div align="center">
				<span><b>Spero Assisted Living Centre - 2017-18</b></span><br>
				<span><b>Daily Schedule '.$date_service.'</span></b><br>
				</div>';
	$datas .=  '<div align="Center">
				<span><b>'.$first_name.' '.$middle_name.' '.$name.'</b></span><br>
				
				</div>';
	
	$datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                    <tr height="30">
					<th width="20%">Sr. No.</th>
					<th width="20%">Activity Name</th>
                    <th width="20%">Start Time</th>
                    <th width="20%">End Time</th>
					<th width="20%">Cost</th>
						
                    </tr>';
	while ($Schedule_list= mysql_fetch_array($detials))
	{
		
		$Activity_Name=$Schedule_list['Activity_Name'];
		$Start_time=$Schedule_list['Start_time'];
		$End_time=$Schedule_list['End_time'];
		$Cost=$Schedule_list['Cost'];
		include "include/paging_script.php";
			
			$datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$count.'</td>';
            $datas .= '<td>'.$Activity_Name.'</td>';
			$datas .= '<td>'.$Start_time.'</td>';
			$datas .= '<td>'.$End_time.'</td>';
			$datas .= '<td>'.$Cost.'</td>';
			$datas .= '</tr>';
		$count++;
	}
}
  
	
	
    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."Assisted_Living_Schedule.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=Assisted_living_schedule_List".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>