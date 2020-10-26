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
$formDate=$_GET['formDate_audio'];
$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['toDate_audio'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
$hospital_id=$_GET['hospital_id'];

        
if($formDate=='' and $toDate=='')
{
            $preWhere .= " AND DATE_FORMAT(se.event_date,'%Y-%m-%d') >= '" . date('Y-m-d', strtotime(' -45 day')) . "'  AND DATE_FORMAT(se.event_date,'%Y-%m-%d') <= '" . date('Y-m-d') . "'";

            $RecordSql=mysql_query("SELECT callerno.phone_no,se.caller_id,calls.call_audio,se.CallUniqueID,se.event_id,se.event_code, se.caller_id,se.purpose_event_id,se.patient_id,sp.mobile_no,sp.name,sp.first_name,se.purpose_id,se.event_date,se.note,se.description,se.status,se.event_status,se.estimate_cost,se.finalcost,se.added_by,se.Added_through,se.added_date ,sp.hhc_code,se.isArchive, sp.isVIP, se.isConvertedService, se.enquiry_status, se.enquiry_cancellation_reason,Invoice_narration
            FROM sp_events as se LEFT JOIN sp_patients as sp ON se.patient_id = sp.patient_id 
            LEFT JOIN sp_detailed_event_plan_of_care as dtl_pln ON se.event_id = dtl_pln.event_id 
            LEFT JOIN sp_incoming_call as calls ON se.CallUniqueID = calls.CallUniqueID
            LEFT JOIN sp_callers as callerno ON se.caller_id = callerno.caller_id
            WHERE 1 and se.status !='3' ".$preWhere."  ");

	//$payments = mysql_query("SELECT * FROM sp_events where status=1 and hospital_id='$hospital_id' ORDER BY date  DESC");
}
else
{
            $daterange .= " AND DATE_FORMAT(se.event_date,'%Y-%m-%d') >= '".$formDate1."'  AND DATE_FORMAT(se.event_date,'%Y-%m-%d') <= '".$toDate2."'";       

            $RecordSql=mysql_query("SELECT callerno.phone_no,se.caller_id,calls.call_audio,se.CallUniqueID,se.event_id,se.event_code, se.caller_id,se.purpose_event_id,se.patient_id,sp.mobile_no,sp.name,sp.first_name,se.purpose_id,se.event_date,se.note,se.description,se.status,se.event_status,se.estimate_cost,se.finalcost,se.added_by,se.Added_through,se.added_date ,sp.hhc_code,se.isArchive, sp.isVIP, se.isConvertedService, se.enquiry_status, se.enquiry_cancellation_reason,Invoice_narration
                    FROM sp_events as se LEFT JOIN sp_patients as sp ON se.patient_id = sp.patient_id
                    LEFT JOIN sp_incoming_call as calls ON se.CallUniqueID = calls.CallUniqueID
                    LEFT JOIN sp_callers as callerno ON se.caller_id = callerno.caller_id
                    WHERE 1 and se.status !='3'  ".$preWhere." ".$daterange." ");
        
	//$payments = mysql_query("SELECT * FROM sp_events where hospital_id='$hospital_id' AND status=1 and date BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY date  ASC ");
}
$row_count = mysql_num_rows($RecordSql);
if($row_count > 0)
{
            echo '<div class="table-responsive" id="payment">
                  <table class="table table-hover table-bordered">
                  <tr> 
                        <th width="3%">Event ID</th>
                        <th width="5%">DMHHC</th>
                        <th width="5%">Purpose of call</th>
                        <th width="5%">Caller No</th>
		<th width="5%">Patient No.</th>
                        <th width="5%">Patient Name</th>
		<th width="5%">Audio File</th>
                </tr>';
            while($RecordSql_rows=mysql_fetch_array($RecordSql))
            {		
	
	echo '<tr>
	<td>'.$RecordSql_rows['event_code'].'</td>
            <td>'.$RecordSql_rows['hhc_code'].'</td>
            <td>'.$RecordSql_rows['hhc_code'].'</td>
	<td>'.$RecordSql_rows['phone_no'].'</td>
	<td>'.$RecordSql_rows['mobile_no'].'</td>
	<td>'.$RecordSql_rows['first_name'].' '.$RecordSql_rows['name'].'</td>
	<td>'.$RecordSql_rows['call_audio'].'</td>';
            echo '</tr>';
	}
}
else
{
	echo "<tr>";
	echo "<td colspan='14' align='middle'>" . "Record Not found for this date" . "</td>";
            echo "</tr>";
	echo "</div>";
	echo "</table>";
}
}
?>