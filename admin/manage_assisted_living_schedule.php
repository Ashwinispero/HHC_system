<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
	   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="stylesheet" href="css/wickedpicker.css">
        <script type="text/javascript" src="js/wickedpicker.js"></script>
    <title>Assisted Living Schedule</title>
    <?php include "include/css-includes.php";?>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php  include "include/header.php"; ?>
        <div id="page-wrapper">
            <d iv class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <img src="images/locations_big.png" alt="Manage Payments">Assisted Living Schedule                  
                            <!--<a href="javascript:void(0);" onclick="return vw_add_location(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD LOCATION</a>-->
                        </h1>
                    </div>
                </div>
<div id="Assisted_Living_div">
<?php 

	$Assisted_Living = mysql_query("SELECT * FROM sp_event_requirements where service_id='17' group by event_id");		 
	$row_count = mysql_num_rows($Assisted_Living);	
	if($row_count > 0)
	{
		echo '<div class="table-responsive" ><table class="table table-hover table-bordered">
                <tr> 
                    <th width="3%">Sr.No</th>
					<th width="5%">HHC No</th>
                    <th width="5%">Event No</th>
					<th width="5%">Patient Name</th>
                    <th width="5%">Service</th>
					<th width="5%">Veiw Schedule</th>
				</tr>';
				$count=1;
		while ($Assisted_Living_rows = mysql_fetch_array($Assisted_Living))
		{
			$event_id=$Assisted_Living_rows['event_id'];
			$service_id=$Assisted_Living_rows['service_id'];
			
			$Service_Name=mysql_query("SELECT * FROM sp_services where service_id='$service_id'");
			$Service_Name_row = mysql_fetch_array($Service_Name) or die(mysql_error());
			$service_title=$Service_Name_row['service_title'];
			
			$Event_Status= mysql_query("SELECT * FROM sp_events  where event_id='$event_id' and event_status='3'");
			$Event_Status_row_count = mysql_num_rows($Event_Status);	
			if($Event_Status_row_count > 0)
			{
				$Event_Status_row = mysql_fetch_array($Event_Status) or die(mysql_error());
				$patient_id=$Event_Status_row['patient_id'];
				$event_code=$Event_Status_row['event_code'];
				
				$Patient_Name = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
				$row = mysql_fetch_array($Patient_Name) or die(mysql_error());
				$Pfirst_name=$row['first_name'];
				$Pmiddle_name=$row['middle_name'];
				$Pname=$row['name'];
				$hhc_code=$row['hhc_code'];
				
				echo '<tr>
					<td>'.$count.'</td>
					<td>'.$hhc_code.'</td>
					<td>'.$event_code.'</td>
					<td>'.$Pfirst_name.' '.$Pmiddle_name.' '.$Pname.'</td>
					<td>'.$service_title.'</td>
					<td><input type="button" value="Add Schedule" style="background-color: #00cfcb;color:white" onclick="View_Schedule('.$event_id.');"</td>';
					echo '</tr>';
					$total=$amount+$total;
			$count++;	
			}
		}
		echo '</div>';
	}
?>  
</div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <div class="modal fade" id="edit_location"> 
        <div class="modal-dialog">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
   <?php  include "include/scripts.php"; ?>
   <link href="css/validationEngine.jquery.css" rel="stylesheet" />
    <script src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery.validationEngine-en.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootbox.js"></script>
    <script type="text/javascript">
    <link href="css/validationEngine.jquery.css" rel="stylesheet" />
    <script src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery.validationEngine-en.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootbox.js"></script>
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/jquery.classyscroll.js"></script>
    
    <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
    <link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
    <script src="js/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <link rel="stylesheet" href="css/wickedpicker.css">
    <script type="text/javascript" src="js/wickedpicker.js"></script>
    <!-- ------------- Timepicker ------------ -->   
<script type="text/javascript" src="../js/jquery-timepicker-master/jquery.timepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../js/jquery-timepicker-master/jquery.timepicker.css" />
<script type="text/javascript" src="../js/jquery-timepicker-master/datepair.js"></script>
<script type="text/javascript" src="../js/jquery-timepicker-master/jquery.datepair.js"></script>

<!--<link rel="stylesheet" href="../js/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="../js/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="../js/bootstrap-multiselect-master/dist/js/bootstrap-multiselect-collapsible-groups.js"></script>-->
<script type="text/javascript">
            $('.timepicker').wickedpicker({
			now: '00:00',
			twentyFour: false, 
			title:'Time', showSeconds: false
			});
        //$('.timepicker-24').wickedpicker({twentyFour: true});
    </script>
<script type="text/javascript">
    function Download_schedule(event_id,date_service)
	{
		window.open('csv_assisted_living_schedule.php?event_id='+event_id+"&date_service="+date_service,'_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); 
		return false
	}
	function View_schedule(event_id,date_service)
	{
		//document.getElementById("event_id").value = event_id;
		//$("#overlay_display_schedule_detail").fadeIn("slow");
		//$("#popupwindow_display_schedule_detail").fadeIn("slow");
		var xmlhttp;
		if(window.XMLHttpRequest)
		{
			xmlhttp=new XMLHttpRequest();
		}
		else
		{
			xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function()
		{
            if(xmlhttp.readyState==4 && xmlhttp.status==200)
			{
                   	document.getElementById("schedule_details").innerHTML=xmlhttp.responseText;
					$("#overlay_display_schedule_detail").fadeIn("slow");
					$("#popupwindow_display_schedule_detail").fadeIn("slow");
			}
		}
		xmlhttp.open("POST","View_Schedule_page.php?event_id="+event_id+"&date_service="+date_service,true);
		xmlhttp.send();
	}
	function Close_Popup_schedule()
	{
		$("#overlay_display_schedule_detail").fadeOut("fast");
		$("#popupwindow_display_schedule_detail").fadeOut("fast");
	}
	function Save_schedule_datewise(event_id,date_service)
	{
		document.getElementById("event_id").value = event_id;
		document.getElementById("date_service").value = date_service;
		$("#overlay_display_schedule").fadeIn("slow");
		$("#popupwindow_display_schedule").fadeIn("slow");
	}
	
	function Close_Popup()
	{
		$("#overlay_display_schedule").fadeOut("fast");
		$("#popupwindow_display_schedule").fadeOut("fast");
	}	
	
        function View_Schedule(event_id)
        {	
			var xmlhttp;
			if(window.XMLHttpRequest)
			{
				xmlhttp=new XMLHttpRequest();
			}
			else
			{
				xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function()
			{
                if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
                   	document.getElementById("Assisted_Living_div").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST","include_assisted_living_schedule.php?event_id="+event_id,true);
			xmlhttp.send();
        }
        function Save_Schedule_patient()
		{
			var event_id = document.getElementById("event_id").value ;
			var Activity = document.getElementById("Activity").value ;
			var Start_time = document.getElementById("Start_time").value ;
			var End_time = document.getElementById("End_time").value ;
			var date_service=document.getElementById("date_service").value ;
			var Cost = document.getElementById("Cost").value ;
			var Tax=document.getElementById("Tax").value ;
			var xmlhttp;
			if(Activity=='')
			{
				document.getElementById('Error_Msg_activity_name').innerHTML="Please Select Activity";
			}
			else if(Start_time=='')
			{
				document.getElementById('Error_Msg_start_time').innerHTML="Please Enter start time";
			}
			else if(End_time=='')
			{
				document.getElementById('Error_Msg_end_time').innerHTML="Please Enter end time";
			}
			else 
			{
				if(Activity=='Other')
				{
					var Activity = document.getElementById("Activity_name_other").value ;
				}
				if(window.XMLHttpRequest)
				{
					xmlhttp=new XMLHttpRequest();
				}
				else
				{
					xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function()
				{
					if(xmlhttp.readyState==4 && xmlhttp.status==200)
					{
						if(xmlhttp.responseText='Insert')
						{
							alert('Booking Successfully added');
							$("#overlay_display_schedule").fadeOut("fast");
							$("#popupwindow_display_schedule").fadeOut("fast");
							location.reload();
						}
						else
						{
							alert('Please try again');
						}
					}
				}
				xmlhttp.open("POST","Save_schedule.php?event_id="+event_id+"&Activity="+Activity+"&Start_time="+Start_time+"&End_time="+End_time+"&date_service="+date_service+"&Cost="+Cost+"&Tax="+Tax,true);
				xmlhttp.send();
			}
		}
		function Other_taxbox_display()
		{
			//alert();
			var Activity = document.getElementById("Activity").value ;
			if(Activity=='Other')
			{
				document.getElementById('display_other').style.display = "block";
			}
			else
			{
				document.getElementById('display_other').style.display = "none";
			}
		}
    </script>

</body>
</html>