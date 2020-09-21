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

	//-----Author: ashwini 31-05-2016-----
	//--Code for date range--
	$formDate=$_GET['enquiry_from_date'];
$date1=date_create("$formDate");
 $formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['enquiry_to_date'];
$date2=date_create("$toDate");
 $toDate2=date_format($date2,"Y-m-d H:i:s");
 ?>
 <div id="thisdiv">
 <?php
	if($formDate!='' and $toDate!='')
	{
		//$events = mysql_query("SELECT * FROM sp_events ORDER BY added_date DESC");
		$events = mysql_query("SELECT * FROM sp_events where (enquiry_status=1 OR enquiry_status=2) and (service_date_of_Enquiry BETWEEN '$formDate1%' AND '$toDate2%') ORDER BY service_date_of_Enquiry DESC");
	}

	//$payments = mysql_query("SELECT * FROM sp_payments ORDER BY date_time DESC");
	$total=0;
	
	$row_count = mysql_num_rows($events);
	if($row_count > 0)
		{
			 echo '<table id="logTable" class="table table-striped" cellspacing="0">
            <thead>
              <tr bgcolor="#00cfcb">
                <th>Event Code</th>
                <th>HHC No</th>
                <th>Patient Name</th>
				<th>Mobile No</th>
                <th>Enquiry Date</th>
				<th>Required Service Date</th>
				<th>Enquiry Note</th>
              </tr>
            </thead>
            <tbody>';
            
            /*<th>Action</th>*/
		
		while($row=mysql_fetch_array($events))
	{
		$event_id=strip_tags($row['event_id']);
		$event_code=strip_tags($row['event_code']);
		$patient_id=strip_tags($row['patient_id']);
		$caller_id=strip_tags($row['caller_id']);
		$enquiry_status=strip_tags($row['enquiry_status']);
		$caller_id=mysql_query("SELECT * FROM sp_callers where caller_id='$caller_id'") or die(mysql_error());
		$caller_id = mysql_fetch_array($caller_id) or die(mysql_error());
		$phone_no=$caller_id['phone_no'];
		
		$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
		$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
		$name=$patient_nm['name'];
		$first_name=$patient_nm['first_name'];
		$middle_name=$patient_nm['middle_name'];
		$hhc_code=$patient_nm['hhc_code'];
		
			$added_date=strip_tags($row['added_date']);
		$added_date=date("d-m-Y", strtotime($added_date));
		$service_date_of_Enquiry=strip_tags($row['service_date_of_Enquiry']);
		$service_date_of_Enquiry=date("d-m-Y", strtotime($service_date_of_Enquiry));
		$note=strip_tags($row['note']);
		
		
		if($enquiry_status==1)
		{
		echo '<tr >
                <td >'.$event_code.' </td>
                <td>'.$hhc_code.'</td>
				<td>'.$name.' '.$first_name.' '.$middle_name.' </td>
				<td>'.$phone_no.' </td>
                <td>'.$added_date.'</td>
				<td>'.$service_date_of_Enquiry.' </td>
                <td>'.$note.'</td>
		</tr>';
		
		/*
		<td>
					<input type="button" value="Call Back" onclick="Enquiry_call_back(\'' . $event_id . '\');">
					<input type="button" value="Confirm" onclick="Enquiry_call_confirm(\'' . $event_id . '\');">
					<input type="button" value="Cancel" onclick="Enquiry_call_cancle(\'' . $event_id . '\');">
				</td>
		*/
		
		
		}
		if($enquiry_status==2)
		{
		echo '<tr >
                <td >'.$event_code.' </td>
                <td>'.$hhc_code.'</td>
				<td>'.$name.' '.$first_name.' '.$middle_name.' </td>
				<td>'.$phone_no.' </td>
                <td>'.$added_date.'</td>
				<td>'.$service_date_of_Enquiry.' </td>
                <td>'.$note.'</td>
				<td>
					<input type="button" value="Confirm" onclick="Enquiry_call_confirm(\'' . $event_id . '\');">
					<input type="button" value="Cancle" onclick="Enquiry_call_cancle(\'' . $event_id . '\');">
				
				</td>
              
		</tr>';
		}
		
	
	}
		}
?>
</div>