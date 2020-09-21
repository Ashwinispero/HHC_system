<?php 
    include "inc_classes.php";
    include "admin_authentication.php";      
    include "pagination-include.php";
	
	$Number = $_GET['Number'];
	//$date_service=$_GET['date_service'];
	//echo $date_service;
?>
<div id="Assisted_View_booking_patient">
	
  
  <input type="button" value="Close" style="background-color:#00cfcb;float: right;border-radius: 25px;" onclick="Close_Popup();"></input>
  <div align="center"style="color:#00cfcb;font-size:25px;margin-top:10px;">Assisted Living Booking</div>
  <br>
  <div class="col-lg-12">
  <div class="row">
	<div class="col-lg-5"><label>Patient Name : <span Style="color:red">&#42;</span></label></div>
	<div class="col-lg-7">
	<select id="patient_id" class="form-control" >
	<option value="" >Select Patient Name</option> 
	<?php
		$Assisted_Living = mysql_query("SELECT * FROM sp_event_requirements where service_id='17' group by event_id");		 
	while ($Assisted_Living_rows = mysql_fetch_array($Assisted_Living))
	{
		$event_id=$Assisted_Living_rows['event_id'];
		$Event_Status1= mysql_query("SELECT * FROM sp_events where event_id='$event_id'");
		$Event_Status_row = mysql_fetch_array($Event_Status1) or die(mysql_error());
		$patient_id=$Event_Status_row['patient_id'];
		
		$Patient_Name = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
		$row = mysql_fetch_array($Patient_Name) or die(mysql_error());
		$Pfirst_name=$row['first_name'];
		$Pmiddle_name=$row['middle_name'];
		$Pname=$row['name'];
		$hhc_code=$row['hhc_code'];
	?>
	<option value="<?php echo $patient_id;?>"><?php echo $Pfirst_name.' '.$Pmiddle_name.' '.$Pname;?> </option>
	<?php
	}
	?>
	</select>
	<div id="Error_Msg_Patient_name" style="color:red"></div>
	</div>
  </div>
  <br>
  <div class="row">
	<div class="col-lg-5"><label>Facility Type:<span Style="color:red">&#42;</span> </label></div>
	<div class="col-lg-7">
	<select  id="Facility_type" class="form-control" >
		<option value="">Select Type</option>
		<option value="1">Day care</option>		
		<option value="2">Reguler</option> 
		<option value="3">luxury</option> 
	</select>
	<div id="Error_Msg_facility_name" style="color:red"></div>
	</div>
  </div>
  <br>
  <div class="row">
	<div class="col-lg-5"><label>Patient location:<span Style="color:red">&#42;</span> </label></div>
	<div class="col-lg-7">
	<select id="Patient_location" class="form-control" >
		<option value="">Select Bed</option>
		<option value="H1">H1</option>		
		<option value="H2">H2</option> 
		<option value="B1">B1</option> 
		<option value="B2">B2</option> 
	</select>
	<div id="Error_Msg_Patient_location" style="color:red"></div>
	</div>
  </div>
  <br>
  
  <div align="center">
  <input type="button" value="Submit" style="background-color:#00cfcb;border-radius: 25px;" align="center" onclick="save_Booing_patient();"></input>
  </div>
  <input id="Flat_Number" value="<?php echo $Number; ?>" style="display:none"></input>

  </div>
  <br>
  <div align="center"style="color:#00cfcb;font-size:25px;margin-top:10px;">Assisted  Living <?php echo $Number ?> Patient</div>
  <div class="col-lg-12">
  
  <?php 
	 $count=1;
$detials=mysql_query("select * from sp_assisted_living_booking where Flat_Number='$Number' and status='1'");
$row_count = mysql_num_rows($detials);
//echo $row_count;
if($row_count > 0)
{
  echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
        <tr> 
            <th width="12%">Patient Name</th>
            <th width="5%">Location</th>
			<th width="1%">Action</th>
		</tr>';
	while ($Schedule_list= mysql_fetch_array($detials))
	{
		
		$Patient_location=$Schedule_list['Patient_location'];
		$patient_id=$Schedule_list['patient_id'];
		$Patient_Name = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
				$row = mysql_fetch_array($Patient_Name) or die(mysql_error());
				$Pfirst_name=$row['first_name'];
				$Pmiddle_name=$row['middle_name'];
				$Pname=$row['name'];
				$hhc_code=$row['hhc_code'];
		//$End_time=$Schedule_list['End_time'];
		echo '<tr>
			<td>'.$Pfirst_name.' '.$Pmiddle_name.' '.$Pname.'</td>
			<td>'.$Patient_location.'</td>
			<td>'.'<input type="button" style="background-color:#ffbf00;border-radius:15px;padding-left:10px;padding-right:10px;" value="Cancle" onclick="cancle_Location(\'' . $patient_id . '\',\''.$Number.'\');"></input>'.'</td>';
		echo '</tr>';
		$count++;
	}
}
else
{
	?>
	 <div align="center"style="color:red;font-size:15px;margin-top:10px;">No Patient In This Room</div>
	<?php 
}
  ?>
 </div>
</div>