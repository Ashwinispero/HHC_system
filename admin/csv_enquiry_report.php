<?php require_once 'inc_classes.php'; ?>
<?php
$csv = '';
$bgColorCounter = 1;
$formDate1=$_GET['formDate_enquiry'];
$date1=date_create("$formDate1");
$formDate=date_format($date1,"Y-m-d H:i:s");

$toDate2=$_GET['toDate_enquiry'];
$date2=date_create("$toDate2");
$toDate=date_format($date2,"Y-m-d H:i:s");
$report_type=$_GET['report_type'];
if($report_type=='1'){
    if($formDate!='' and $toDate!='')
    {
        $events = mysql_query("SELECT ev.event_code,CONCAT (pt.name, ' ',pt.first_name, ' ',pt.middle_name) as patientName,ser.service_title,sub.recommomded_service FROM sp_events as ev LEFT JOIN sp_enquiry_requirements as er ON ev.event_id=er.event_id LEFT JOIN sp_patients as pt ON pt.patient_id=ev.patient_id LEFT JOIN sp_services as ser ON er.service_id=ser.service_id LEFT JOIN sp_sub_services as sub ON er.sub_service_id=sub.sub_service_id WHERE ev.purpose_id=2 AND ev.enquiry_status=1 AND ev.enquiry_added_date BETWEEN  '$formDate%' AND '$toDate%' ");
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
    {
    $datas.='No record found related to your search criteria';
    }
}
if($report_type=='2'){
    if($formDate!='' and $toDate!='')
    {
        $events = mysql_query("SELECT ev.event_code,CONCAT (pt.name, ' ',pt.first_name, ' ',pt.middle_name) as patientName,CONCAT(emp.name, ' ',emp.first_name, ' ',emp.middle_name) as addedby, ev.added_date, CONCAT(emp1.name, ' ',emp1.first_name, ' ',emp1.middle_name) as modifyby,ev.event_date,ev.note,pt.residential_address,
        ser.service_title,sub.recommomded_service FROM sp_events as ev
        LEFT JOIN sp_employees as emp on emp.employee_id=ev.added_by
        LEFT JOIN sp_employees as emp1 on emp1.employee_id=ev.last_modified_by
        LEFT JOIN sp_enquiry_requirements as er ON ev.event_id=er.event_id
        LEFT JOIN sp_patients as pt ON pt.patient_id=ev.patient_id
        LEFT JOIN sp_services as ser ON er.service_id=ser.service_id
        LEFT JOIN sp_sub_services as sub ON er.sub_service_id=sub.sub_service_id
        WHERE ev.purpose_id=2 AND ev.enquiry_status=1 AND ev.enquiry_added_date BETWEEN '$formDate%' AND '$toDate%' ");
    }
    $row_count = mysql_num_rows($events);
    if($row_count > 0)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                    <tr height="30">
                    <th width="2%">Event Code</th>
	            <th width="2%">Patient Name</th>
                <th width="2%">Added By</th>
                <th width="2%">Added Date</th>
                <th width="2%">Modify By</th>
                <th width="2%">Event Date</th>
                <th width="2%">Note</th>
                <th width="2%">Residential Address</th>
                <th width="2%">Service</th>
                <th width="2%">Recommended Service</th>
                </tr>';
        while ($events_rows = mysql_fetch_array($events))
        {		
            $patientName=$events_rows['patientName'];
		    $event_code=$events_rows['event_code'];
			$addedby=$events_rows['addedby'];
            $added_date=$events_rows['added_date'];
            $modifyby=$events_rows['modifyby'];
		    $event_date=$events_rows['event_date'];
			$note=$events_rows['note'];
			$residential_address=$events_rows['residential_address'];
            $service_title=$events_rows['service_title'];
            $recommomded_service=$events_rows['recommomded_service'];
                                            
            $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$event_code.'</td>';
            $datas .= '<td>'.$patientName.'</td>';
            $datas .= '<td>'.$addedby.'</td>';
            $datas .= '<td>'.$added_date.'</td>';
            $datas .= '<td>'.$modifyby.'</td>';
            $datas .= '<td>'.$event_date.'</td>';
            $datas .= '<td>'.$note.'</td>';
            $datas .= '<td>'.$residential_address.'</td>';
            $datas .= '<td>'.$service_title.'</td>';
            $datas .= '<td>'.$recommomded_service.'</td>';
            $datas .= '</tr>';
        }	
    }
    else
    {
    $datas.='No record found related to your search criteria';
    }
}
if($report_type=='3'){
    if($formDate!='' and $toDate!='')
    {
        $events = mysql_query("SELECT ev.event_code,CONCAT (pt.name, ' ',pt.first_name, ' ',pt.middle_name) as patientName,
        CONCAT(emp.name, ' ',emp.first_name, ' ',emp.middle_name) as addedby, ev.added_date, CONCAT(emp1.name, ' ',emp1.first_name, ' ',emp1.middle_name) as modifyby,ev.event_date,ev.note,pt.residential_address,
        ser.service_title,sub.recommomded_service FROM sp_events as ev
        LEFT JOIN sp_employees as emp on emp.employee_id=ev.added_by
        LEFT JOIN sp_employees as emp1 on emp1.employee_id=ev.last_modified_by
        LEFT JOIN sp_enquiry_requirements as er ON ev.event_id=er.event_id
        LEFT JOIN sp_patients as pt ON pt.patient_id=ev.patient_id
        LEFT JOIN sp_services as ser ON er.service_id=ser.service_id
        LEFT JOIN sp_sub_services as sub ON er.sub_service_id=sub.sub_service_id
        WHERE ev.purpose_id=1 AND ev.isConvertedService=2 AND ev.enquiry_added_date BETWEEN  '$formDate%' AND '$toDate%' ");
    }
    $row_count = mysql_num_rows($events);
    if($row_count > 0)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                    <tr height="30">
                    <th width="2%">Event Code</th>
	                <th width="2%">Patient Name</th>
                    <th width="2%">Added By</th>
                    <th width="2%">Added Date</th>
                    <th width="2%">Modify By</th>
                    <th width="2%">Event Date</th>
                    <th width="2%">Note</th>
                    <th width="2%">Residential Address</th>
                    <th width="2%">Service</th>
                    <th width="2%">Recommended Service</th>
                    </tr>';
        while ($events_rows = mysql_fetch_array($events))
        {		
            $patientName=$events_rows['patientName'];
			$event_code=$events_rows['event_code'];
			$addedby=$events_rows['addedby'];
            $added_date=$events_rows['added_date'];
            $modifyby=$events_rows['modifyby'];
			$event_date=$events_rows['event_date'];
			$note=$events_rows['note'];
			$residential_address=$events_rows['residential_address'];
            $service_title=$events_rows['service_title'];
            $recommomded_service=$events_rows['recommomded_service'];	
                                            
            $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$event_code.'</td>';
            $datas .= '<td>'.$patientName.'</td>';
            $datas .= '<td>'.$addedby.'</td>';
            $datas .= '<td>'.$added_date.'</td>';
            $datas .= '<td>'.$modifyby.'</td>';
			$datas .= '<td>'.$event_date.'</td>';
            $datas .= '<td>'.$note.'</td>';
            $datas .= '<td>'.$residential_address.'</td>';
			$datas .= '<td>'.$service_title.'</td>';
            $datas .= '<td>'.$recommomded_service.'</td>';
            $datas .= '</tr>';
        }	
    }
    else
    {
    $datas.='No record found related to your search criteria';
    }
}
if($report_type=='4'){
    if($formDate!='' and $toDate!='')
    {
        $events = mysql_query("SELECT ev.event_code,CONCAT (pt.name, ' ',pt.first_name, ' ',pt.middle_name) as patientName,
CONCAT(emp.name, ' ',emp.first_name, ' ',emp.middle_name) as addedby, ev.added_date, CONCAT(emp1.name, ' ',emp1.first_name, ' ',emp1.middle_name) as modifyby,ev.event_date,ev.note,pt.residential_address,
ser.service_title,sub.recommomded_service FROM sp_events as ev
LEFT JOIN sp_employees as emp on emp.employee_id=ev.added_by
LEFT JOIN sp_employees as emp1 on emp1.employee_id=ev.last_modified_by
LEFT JOIN sp_enquiry_requirements as er ON ev.event_id=er.event_id
LEFT JOIN sp_patients as pt ON pt.patient_id=ev.patient_id
LEFT JOIN sp_services as ser ON er.service_id=ser.service_id
LEFT JOIN sp_sub_services as sub ON er.sub_service_id=sub.sub_service_id
WHERE ev.purpose_id=1 AND ev.isConvertedService=2 AND ev.enquiry_added_date BETWEEN  '$formDate%' AND '$toDate%' ");
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
    {
    $datas.='No record found related to your search criteria';
    }
}
if($report_type=='5'){
    if($formDate!='' and $toDate!='')
    {
        $events = mysql_query("SELECT ev.event_code,CONCAT (pt.name, ' ',pt.first_name, ' ',pt.middle_name) as patientName,
        ev.enquiry_added_date,ev.added_by,ev.last_modified_by,
        ev.event_date,ev.note,
        ser.service_title,sub.recommomded_service,enquiry_cancel_from,enquiry_cancellation_reason,pt.residential_address,CONCAT(emp.name, ' ',emp.first_name, ' ',emp.middle_name) as addedby, ev.added_date, CONCAT(emp1.name, ' ',emp1.first_name, ' ',emp1.middle_name) as modifyby,ev.event_date,ev.note,pt.residential_address,
        ser.service_title,sub.recommomded_service FROM sp_events as ev
        LEFT JOIN sp_employees as emp on emp.employee_id=ev.added_by
        LEFT JOIN sp_employees as emp1 on emp1.employee_id=ev.last_modified_by
        LEFT JOIN sp_enquiry_requirements as er ON ev.event_id=er.event_id
        LEFT JOIN sp_patients as pt ON pt.patient_id=ev.patient_id
        LEFT JOIN sp_services as ser ON er.service_id=ser.service_id
        LEFT JOIN sp_sub_services as sub ON er.sub_service_id=sub.sub_service_id
        WHERE ev.purpose_id=2 AND ev.enquiry_status=4 AND ev.enquiry_added_date BETWEEN  '$formDate%' AND '$toDate%' ");
    }
    $row_count = mysql_num_rows($events);
    if($row_count > 0)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                    <tr height="30">
                    <th width="2%">Event Code</th>
                    <th width="2%">Patient Name</th>
                    <th width="2%">Enquiry Added Date</th>
                    <th width="2%">Added By</th>
                    <th width="2%">Last Modify By</th>
                    <th width="2%">Event Date</th>
                    <th width="2%">Note</th>
                    <th width="2%">Service</th>
                    <th width="2%">Recommended Service</th>
                    <th width="2%">Enquiry Cancle Form</th>
                    <th width="2%">Enquiry Canclelation reason</th>
                    <th width="2%">Residential Address</th>
                    </tr>';
        while ($events_rows = mysql_fetch_array($events))
        {		
            $patientName=$events_rows['patientName'];
			$event_code=$events_rows['event_code'];
			$enquiry_added_date=$events_rows['enquiry_added_date'];
            $added_date=$events_rows['added_date'];
            $added_by=$events_rows['added_by'];
            $addedby=$events_rows['addedby'];
            $last_modified_by=$events_rows['last_modified_by'];
            $modifyby=$events_rows['modifyby'];
			$event_date=$events_rows['event_date'];
			$note=$events_rows['note'];
            $service_title=$events_rows['service_title'];
            $recommomded_service=$events_rows['recommomded_service'];	
            $enquiry_cancel_from=$events_rows['enquiry_cancel_from'];
            $enquiry_cancellation_reason=$events_rows['enquiry_cancellation_reason'];
            $residential_address=$events_rows['residential_address'];
                                            
            $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$event_code.'</td>';
            $datas .= '<td>'.$patientName.'</td>';
            $datas .= '<td>'.$enquiry_added_date.'</td>';
            $datas .= '<td>'.$addedby.'</td>';
            $datas .= '<td>'.$modifyby.'</td>';
            $datas .= '<td>'.$event_date.'</td>';
            $datas .= '<td>'.$note.'</td>';
            $datas .= '<td>'.$service_title.'</td>';
            $datas .= '<td>'.$recommomded_service.'</td>';
            $datas .= '<td>'.$enquiry_cancel_from.'</td>';
            $datas .= '<td>'.$enquiry_cancellation_reason.'</td>';
            $datas .= '<td>'.$residential_address.'</td>';
            $datas .= '</tr>';
        }	
    }
    else
    {
    $datas.='No record found related to your search criteria';
    }
}
if($report_type=='6'){
    if($formDate!='' and $toDate!='')
    {
        $events = mysql_query("SELECT ev.event_code,CONCAT (pt.name, ' ',pt.first_name, ' ',pt.middle_name) as patientName, ev.enquiry_added_date,ev.added_by,ev.last_modified_by, ev.event_date,ev.note, ser.service_title,sub.recommomded_service,enquiry_cancel_from,enquiry_cancellation_reason,pt.residential_address FROM sp_events as ev LEFT JOIN sp_enquiry_requirements as er ON ev.event_id=er.event_id LEFT JOIN sp_patients as pt ON pt.patient_id=ev.patient_id LEFT JOIN sp_services as ser ON er.service_id=ser.service_id LEFT JOIN sp_sub_services as sub ON er.sub_service_id=sub.sub_service_id WHERE ev.purpose_id=2 AND ev.enquiry_status=4 AND ev.enquiry_added_date BETWEEN   '$formDate%' AND '$toDate%' ");
    }
    $row_count = mysql_num_rows($events);
    if($row_count > 0)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                    <tr height="30">
                    <th width="2%">Event Code</th>
	                <th width="2%">Patient Name</th>
                    <th width="2%">Enquiry Added Date</th>
                    <th width="2%">Added By</th>
                    <th width="2%">Last Modify By</th>
	                <th width="2%">Event Date</th>
                    <th width="2%">Note</th>
                    <th width="2%">Service</th>
                    <th width="2%">Recommended Service</th>
                    <th width="2%">Enquiry Cancle Form</th>
                    <th width="2%">Enquiry Canclelation reason</th>
                    <th width="2%">Residential Address</th>
                    </tr>';
        while ($events_rows = mysql_fetch_array($events))
        {		
            $patientName=$events_rows['patientName'];
				$event_code=$events_rows['event_code'];
				$enquiry_added_date=$events_rows['enquiry_added_date'];
                $added_date=$events_rows['added_date'];
                $added_by=$events_rows['added_by'];
                $addedby=$events_rows['addedby'];
                $last_modified_by=$events_rows['last_modified_by'];
                $modifyby=$events_rows['modifyby'];
				$event_date=$events_rows['event_date'];
				$note=$events_rows['note'];
                $service_title=$events_rows['service_title'];
                $recommomded_service=$events_rows['recommomded_service'];	
                $enquiry_cancel_from=$events_rows['enquiry_cancel_from'];
                $enquiry_cancellation_reason=$events_rows['enquiry_cancellation_reason'];
                $residential_address=$events_rows['residential_address'];
                                            
            $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$event_code.'</td>';
            $datas .= '<td>'.$patientName.'</td>';
            $datas .= '<td>'.$enquiry_added_date.'</td>';
            $datas .= '<td>'.$addedby.'</td>';
            $datas .= '<td>'.$modifyby.'</td>';
            $datas .= '<td>'.$event_date.'</td>';
            $datas .= '<td>'.$note.'</td>';
            $datas .= '<td>'.$service_title.'</td>';
            $datas .= '<td>'.$recommomded_service.'</td>';
            $datas .= '<td>'.$enquiry_cancel_from.'</td>';
            $datas .= '<td>'.$enquiry_cancellation_reason.'</td>';
            $datas .= '<td>'.$residential_address.'</td>';
            $datas .= '</tr>';
        }	
    }
    else
    {
    $datas.='No record found related to your search criteria';
    }
}
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