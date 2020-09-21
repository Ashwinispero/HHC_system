<?php 
    include "inc_classes.php";
    include "admin_authentication.php";      
    include "pagination-include.php";
	
	$event_id = $_GET['event_id'];
	$date_service=$_GET['date_service'];
	//echo $date_service;
?>
<div id="schedule_details">
<input type="button" value="Close" style="background-color:#00cfcb;float: right;border-radius: 25px;" onclick="Close_Popup_schedule();"></input>
  <div align="center"style="color:#00cfcb;font-size:25px;margin-top:10px;">Assisted Living View Schedule</div>
  <br>
  <?php 
	echo '<input type="button" style="background-color:#ffbf00;border-radius:15px;padding-left:10px;float: right;" value="Download Schedule" onclick="Download_schedule(\'' . $event_id . '\',\''.$date_service.'\')"; >';
  ?>
  <br>
  <br>
  <div class="col-lg-12">
  <?php 
  $count=1;
$detials=mysql_query("select * from sp_assisted_living_schedule where event_id='$event_id' and service_date='$date_service'");
$row_count = mysql_num_rows($detials);
//echo $row_count;
if($row_count > 0)
{
  echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
        <tr> 
            <th width="2%">Sr. No.</th>
			<th width="8%">Activity Name</th>
            <th width="4%">Start Time</th>
			<th width="5%">End Time</th>
			<th width="3%">Cost</th>
			<th width="3%">Tax</th>
		</tr>';
	while ($Schedule_list= mysql_fetch_array($detials))
	{
		
		$Activity_Name=$Schedule_list['Activity_Name'];
		$Start_time=$Schedule_list['Start_time'];
		$End_time=$Schedule_list['End_time'];
		$Cost=$Schedule_list['Cost'];
		$Tax=$Schedule_list['Tax'];
		echo '<tr>
			<td>'.$count.'</td>
			<td>'.$Activity_Name.'</td>
			<td>'.$Start_time.'</td>
			<td>'.$End_time.'</td>
			<td>'.$Cost.'</td>
			<td>'.$Tax.'</td>';
		echo '</tr>';
		$count++;
	}
}
  ?>
  
  
  <input id="event_id" style="display:none"></input>
  </div>
</div>