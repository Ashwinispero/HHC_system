<?php require_once 'inc_classes.php'; ?>
<?php
$csv = '';
$bgColorCounter = 1;
$formDate = $_GET['formDate_login'];
$date1 = date_create("$formDate");
$formDate1 = date_format($date1, "Y-m-d H:i:s");

$toDate = $_GET['toDate_login'];
$date2 = date_create("$toDate");
$toDate2 = date_format($date2, "Y-m-d H:i:s");
if($formDate!='' and $toDate!='')
{
$events = mysql_query("SELECT * FROM sp_session where added_date BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY added_date DESC");
}
$row_count = mysql_num_rows($events);
if($row_count > 0)
{
            $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
		<th width="2%">Professional Name</th>
		<th width="2%">Status</th>
		<th width="2%">Login</th>
		<th width="2%">Logout</th>
                        </tr>';
            while ($events_rows = mysql_fetch_array($events))
	{		
		$service_professional_id=$events_rows['service_professional_id'];
		$status=$events_rows['status'];
		$added_date=$events_rows['added_date'];
		$last_modify_date=$events_rows['last_modify_date'];
		if($status=='1'){
                        $status_chk='Login';
                        }else if($status=='2'){
                        $status_chk='Logout';
                        }else if($status=='3'){
                        $status_chk='Device Removed';
                        }
                        $professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id'");
				$professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
			            $name=$professional_name_abc['name'];
				$title=$professional_name_abc['title'];
				$first_name=$professional_name_abc['first_name'];
                                                $middle_name=$professional_name_abc['middle_name'];
                                                $google_location_prof=$professional_name_abc['google_work_location'];
				$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
						
		$datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$professional_name.'</td>';
                        $datas .= '<td>'.$status_chk.'</td>';
			$datas .= '<td>'.$added_date.'</td>';
			$datas .= '<td>'.$last_modify_date.'</td>';
			$datas .= '</tr>';
	}	
}
else
$datas.='No record found related to your search criteria';

    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."Professional_status_report.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=Professional_status_report ".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>