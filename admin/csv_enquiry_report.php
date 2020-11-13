<?php require_once 'inc_classes.php'; ?>
<?php
$csv = '';
$bgColorCounter = 1;
$formDate = $_GET['formDate_enquiry'];
$date1 = date_create("$formDate");
$formDate1 = date_format($date1, "Y-m-d H:i:s");

$toDate = $_GET['toDate_enquiry'];
$date2 = date_create("$toDate");
$toDate2 = date_format($date2, "Y-m-d H:i:s");
if($formDate!='' and $toDate!='')
{
            $events = mysql_query("SELECT ev.event_code,CONCAT (pt.name, ' ',pt.first_name, ' ',pt.middle_name) as patientName,ser.service_title,sub.recommomded_service FROM sp_events as ev LEFT JOIN sp_enquiry_requirements as er ON ev.event_id=er.event_id LEFT JOIN sp_patients as pt ON pt.patient_id=ev.patient_id LEFT JOIN sp_services as ser ON er.service_id=ser.service_id LEFT JOIN sp_sub_services as sub ON er.sub_service_id=sub.sub_service_id WHERE ev.purpose_id=2 AND ev.enquiry_status=1 AND ev.enquiry_added_date BETWEEN  '$formDate1%' AND '$toDate2%' ");
}
$row_count = mysql_num_rows($events);
if($row_count > 0)
{
            $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
		<th width="2%">Event Code</th>
                        <th width="2%">Patient Name</th>
                        <th width="2%">Service Title </th>
                        <th width="2%">Recommended Service</th>
                        </tr>';
            while ($events_rows = mysql_fetch_array($events))
	{		
		$patientName=$events_rows['patientName'];
		$event_code=$events_rows['event_code'];
		$service_title=$events_rows['service_title'];
                        $recommomded_service=$events_rows['recommomded_service'];
                        				
		$datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$event_code.'</td>';
                        $datas .= '<td>'.$patientName.'</td>';
		$datas .= '<td>'.$service_title.'</td>';
		$datas .= '<td>'.$recommomded_service.'</td>';
		$datas .= '</tr>';
	}	
}
else
$datas.='No record found related to your search criteria';

    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."enquiry_report.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=enquiry_report ".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>