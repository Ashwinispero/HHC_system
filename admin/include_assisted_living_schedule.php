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
	$event_id=$_GET['event_id'];
	//echo $event_id;
}

?>
	<style>
#overlay_display_schedule_detail
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
 #popupwindow_display_schedule_detail
   {
      width:600px;
		height:470px;
		border-radius:10px;
		margin:0 auto;
		position:absolute;
		top:20%;
		right:20%;
		bottom:10%;
		left:30%;
		z-index:1500;
		border-radius: 20px;
    border: 3px solid #4D4D4D;
    background-color: #FFFFFF;
	 box-shadow: 0 2px 20px #666666;
	-moz-box-shadow: 0 2px 20px #666666;
	-webkit-box-shadow: 0 2px 20px #666666;
	auto-overflow:scroll;
		display:none;
		
   }
   .close {
    float: right;
    margin-right: 2px;
    color: #909090;
    text-decoration: none;
}
#overlay_display_schedule
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
 #popupwindow_display_schedule
   {
      width:400px;
		height:400px;
		border-radius:10px;
		margin:0 auto;
		position:absolute;
		top:20%;
		right:20%;
		bottom:10%;
		left:30%;
		z-index:1500;
		border-radius: 20px;
    border: 3px solid #4D4D4D;
    background-color: #FFFFFF;
	 box-shadow: 0 2px 20px #666666;
	-moz-box-shadow: 0 2px 20px #666666;
	-webkit-box-shadow: 0 2px 20px #666666;
	//overflow:scroll;
		display:none;
		
   }
   .close {
    float: right;
    margin-right: 2px;
    color: #909090;
    text-decoration: none;
}
</style>
<body style="background-color: #cecece;">

<div class="col-lg-12">
<div class="panel panel-default" style="background-color:#FFFAF0;">
<div class="row">
    <h2 align="center" style="color:#00cfcb;">Schedule for Assisted Living Service</h2>
	</div>
	<div class="row">
	<div class="col-lg-11" style="margin-left:4%;margin-right:4%">
		<div class="panel panel-default">
	
		<div class="table-responsive">
			<table class="table table-bordered">
			<tr bgcolor="#00cfcb" style="color:white"> 
			<th width="10%">Service Date</th>
			<th width="15%">Time</th>
			<th width="15%">Action</th>
			</tr>
			<?php 
	$time = time();
	$event_detail=mysql_query("SELECT * FROM sp_events where event_id='$event_id'") or die(mysql_error());
	$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
	$event_code=$event_detail['event_code'];
	$patient_id=$event_detail['patient_id'];
	$event_date=$event_detail['event_date'];
	
	$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
	$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
	$name=$patient_nm['name'];
	$first_name=$patient_nm['first_name'];
	$middle_name=$patient_nm['middle_name'];
	$Professional_service= mysql_query("SELECT * FROM sp_event_plan_of_care where event_id='$event_id'  ");
	$row_count = mysql_num_rows($Professional_service);
	if($row_count > 0)
	{
		while($row=mysql_fetch_array($Professional_service))
		{
			
			$service_date=strip_tags($row['service_date']);
			$service_date_to=strip_tags($row['service_date_to']);
			$event_requirement_id=strip_tags($row['event_requirement_id']);
			$event_id=strip_tags($row['event_id']);
			$start_date=strip_tags($row['start_date']);
			$end_date=strip_tags($row['end_date']);
			if($service_date!='0000-00-00' && $service_date_to!='0000-00-00')
			{
				$begin = new DateTime($service_date);
				$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
				$end = new DateTime($end);
				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
				foreach($daterange as $date)
				{
					$count=$count+1;
					$date_service=$date->format("Y-m-d") ;
					echo '<tr >
					<td>'.$date_service.'</td>';
					
					echo '<td>'.$start_date.' - '.$end_date.'</td>';
					echo '<td align="center">
					 <input type="button" style="background-color:#ffbf00;border-radius:15px;padding-left:10px;padding-right:10px;" value="Add Schedule" onclick="Save_schedule_datewise(\'' . $event_id . '\',\''.$date_service.'\')"; >
					<input type="button" style="background-color:#ffbf00;border-radius:15px;padding-left:10px;padding-right:10px;" value="View Schedule" onclick="View_schedule(\'' . $event_id . '\',\''.$date_service.'\')"; >
					</td>';
					echo '</tr>';
				} 
			}
			
			//echo 'ashwini';
		}
		
	}
	?>
		</div>
		</div>
	</div>
	</div>
</div>
</div>
<div id="overlay_display_schedule">
  <div id="popupwindow_display_schedule">
  <input type="button" value="Close" style="background-color:#00cfcb;float: right;border-radius: 25px;" onclick="Close_Popup();"></input>
  <div align="center"style="color:#00cfcb;font-size:25px;margin-top:10px;">Assisted Living Schedule</div>
  <br>
  <div class="col-lg-12">
  <div class="row">
	<div class="col-lg-4"><label>Activity :<span Style="color:red">&#42;</span> </label></div>
	<div class="col-lg-8">
	<select id="Activity" class="form-control" onchange="Other_taxbox_display();">
	<option value="" >Select Activity</option> 
	<option value="Morning Exersize">Morning Exersize</option>
	<option value="Break fast">Break fast</option>
	<option value="Wolking">Wolking</option>
	<option value="Paly time">Paly time</option>
	<option value="Lecture">Lecture</option>
	<option value="Indoor Activity">Indoor Activity</option>
	<option value="OutDoor Activity">OutDoor Activity</option>
	<option value="Physio Appoiement">Physio Appoiement</option>
	<option value="Relative Visit">Relative Visit</option>
	<option value="Moive">Moive</option>
	<option value="Group Activity">Group Activity</option>
	<option value="Lunch">Lunch</option>
	<option value="Dinner">Dinner</option>
	<option value="Other">Other</option>
	</select>
	<div id="Error_Msg_activity_name" style="color:red"></div>
	</div>
  </div>
  <br>
  <div class="row" style="display:none" id="display_other">
	<div class="col-lg-4"><label>Activity Name:<span Style="color:red">&#42;</span></label></div>
	<div class="col-lg-8">
		<input type="text" class="form-control" id="Activity_name_other">
		<div id="Error_Msg_cost" style="color:red"></div>
	</div>
  </div>
  <br>
  <div class="row">
	<div class="col-lg-4"><label>Start Time :<span Style="color:red">&#42;</span> </label></div>
	<div class="col-lg-8">
		<input type="time" class="form-control" id="Start_time">
		<div id="Error_Msg_start_time" style="color:red"></div>
	</div>
  </div>
  <br>
  <div class="row">
	<div class="col-lg-4"><label>End Time : <span Style="color:red">&#42;</span></label></div>
	<div class="col-lg-8">
		<input type="time" class="form-control" id="End_time">
		<div id="Error_Msg_end_time" style="color:red"></div>
	</div>
  </div>
  <br>
  <div class="row">
	<div class="col-lg-4"><label>Cost : <span Style="color:red">&#42;</span></label></div>
	<div class="col-lg-8">
		<input type="text" class="form-control" id="Cost">
		<div id="Error_Msg_cost" style="color:red"></div>
	</div>
  </div>
  <br>
  <div class="row">
	<div class="col-lg-4"><label>Tax : <span Style="color:red">&#42;</span></label></div>
	<div class="col-lg-8">
		<input type="Tax" class="form-control" id="Tax">
		<div id="Error_Msg_tax" style="color:red"></div>
	</div>
  </div>
  <br>
  
  <div align="center">
  <input type="button" value="Submit" style="background-color:#00cfcb;border-radius: 25px;" align="center" onclick="Save_Schedule_patient();"></input>
  </div>
  <input id="date_service" style="display:none"></input>
  <input id="event_id" style="display:none"></input>
  </div>
  </div>
</div>

</body>
<div id="overlay_display_schedule_detail">
  <div id="popupwindow_display_schedule_detail">
  <div id="schedule_details"></div>
  </div>
</div>


