<?php 
$event_requirement_id123 = $_GET['event_requirement_id'];
$service_professional_id=$_GET['service_professional_id'];
$event_id=$_GET['event_id'];
$time=$_GET['time'];
$service_id=$_GET['service_id'];
//echo $time;
 include('config.php');
 //Session expire after 15 min
 if( !isset( $service_professional_id) || time() - $time >1800)
{
		

echo "<script>
alert('Your Session is expire...Palese Login again!! ');

</script>";
   header("Location:index.php");
}
 else {
    
$time = time();

if(isset($service_professional_id) && !empty($service_professional_id) ) {
	
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
?>

<html xmlns="http://www.w3.org/1999/xhtml">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="shortcut icon" type="image/x-icon" href="images/logo.png" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	
	 <!--Javascript Files-->
	<!--<script src="js/prefixfree.min.js"></script>-->
	<script type="text/javascript" language="javascript" src="js/jquery_1.5.2.js"></script>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<!--<script type="text/javascript" src="js/custom.js"></script>-->
	<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="js/jquery.label_better.js"></script>
	<script type="text/javascript" src="js/jquery.label_better.min.js"></script>
	<script type="text/javascript" src="js/jquery.label_better_1.js"></script>
	<script type="text/javascript" src="js/jquery.timepicker.js"></script>
	<script type="text/javascript" src="lib/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="lib/site.js"></script>
	<script type="text/javascript" src="js/googleapis.jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<!--CSS Files-->
	<!--<link href="css/style.css" rel="stylesheet" type="text/css" />-->
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
	<link rel="stylesheet" type="text/css" href="lib/bootstrap-datepicker.css" />
	<link rel="stylesheet" type="text/css" href="lib/site.css" />
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">
	<!--<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">-->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!--<link rel="stylesheet" href="css/wickedpicker.css">-->
	<link href="css/custom.css" rel="stylesheet">

<title>Service Details Job Closure</title>
   
</head>
	<script type="text/javascript">
	function Job_closure_form(event_id,service_professional_id,service_id,date_service,time)
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
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText=='session_expire')
				{
					alert('Your Session is expire...Palese Login again!! ');
					window.location ="index.php";
				}
				else{
				document.getElementById("display_popup_jobclosure_form").innerHTML=xmlhttp.responseText;
				$("#overlay_display_JobClosure_form").fadeIn("slow");
				$("#popupwindow_display_JobClosure_form").fadeIn("slow");
				}
				
			}
		}
		xmlhttp.open("POST","Job_closure_form.php?event_id="+event_id+"&service_professional_id="+service_professional_id+"&service_id="+service_id+"&date_service="+date_service+"&time="+time,true);
		xmlhttp.send();
	}
	function Save_Jobclosure_datewise_details(event_id,sub_service_id,date_service,service_professional_id,service_id,Actual_service_date)
	{
		//alert(service_id);
		var job_closure_details=document.getElementById('job_closure_details').value;
		
		if(job_closure_details=='')
		{
			alert('Please Add Job Closure');
		}
		else{
			
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
				
					alert('Job closure successfully added');
				
				$("#overlay_display_add_jobclosure").fadeOut("fast");
				$("#popupwindow_display_jobclosure").fadeOut("fast");
				
				location.reload();
			}
		}
		xmlhttp.open("POST","save_datewise_jobclosure.php?event_id="+event_id+"&sub_service_id="+sub_service_id+"&date_service="+date_service+"&service_professional_id="+service_professional_id+"&job_closure_details="+job_closure_details+"&service_id="+service_id+"&Actual_service_date="+Actual_service_date,true);
		xmlhttp.send();
		}
	}
	function Save_Jobclosure_datewise(event_id,sub_service_id,date_service,service_professional_id,time,service_id)
	{
			
		var Actual_service_date=document.getElementById('Actual_service_date').value;		
				//alert(count);
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
				//alert(xmlhttp.responseText);
				if(xmlhttp.responseText=='session_expire')
				{
					alert('Your Session is expire...Palese Login again!! ');
					window.location ="index.php";
				}
				else
				{
					document.getElementById("display_popup_jobclosure").innerHTML=xmlhttp.responseText;
				$("#overlay_display_add_jobclosure").fadeIn("slow");
				$("#popupwindow_display_jobclosure").fadeIn("slow");
				}
				
				
			}
		}
		xmlhttp.open("POST","selected_date_jobclosure.php?event_id="+event_id+"&sub_service_id="+sub_service_id+"&date_service="+date_service+"&service_professional_id="+service_professional_id+"&time="+time+"&service_id="+service_id+"&Actual_service_date="+Actual_service_date,true);
		xmlhttp.send();
		
        
	}
	function close_popup()
	{
		 $("#overlay_display_add_jobclosure").fadeOut("fast");
		$("#popupwindow_display_jobclosure").fadeOut("fast");
	}
	function close_popup_jobclosure_form()
	{
		 $("#overlay_display_JobClosure_form").fadeOut("fast");
		$("#popupwindow_display_JobClosure_form").fadeOut("fast");
		//location.reload();
		window.location ="Physiotherapy_job_closure.php";	
	}
	
	//job closure form
	var i = 1;
	function add_more_unit_medicine()
	{
		Medicine_unit_dropbox.innerHTML = Medicine_unit_dropbox.innerHTML +"<br><input style='width:100%' type='text' name='mytext'+ i> <br>"
		Medicine_unit_textbox.innerHTML = Medicine_unit_textbox.innerHTML +"<br><input style='width:100%' type='text' name='mytext'+ i> <br>"
		i++;
	}
	function save_jobclosure(event_id,service_professional_id,service_id,date_service)
	{
		var baseline = $('input:radio[name=baseline]:checked').val();
		var airway = $('input:radio[name=airway]:checked').val();
		var Breathing = $('input:radio[name=Breathing]:checked').val();
		var Circulation = $('input:radio[name=Circulation]:checked').val();
		var skin_perfusion = $('input:radio[name=skin_perfusion]:checked').val();
		var JobClosure_temp=document.getElementById('JobClosure_temp').value;
		var JobClosure_TBSL=document.getElementById('JobClosure_TBSL').value;
		var JobClosure_Pulse=document.getElementById('JobClosure_Pulse').value;
		var JobClosure_SpO2=document.getElementById('JobClosure_SpO2').value;
		var JobClosure_RR=document.getElementById('JobClosure_RR').value;
		var JobClosure_GCS=document.getElementById('JobClosure_GCS').value;
		var JobClosure_BP_high=document.getElementById('JobClosure_BP_high').value;
		var JobClosure_BP_low=document.getElementById('JobClosure_BP_low').value;
		var Jobclosure_summery=document.getElementById('Jobclosure_summery').value;
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
				alert('Add medicines');
				//document.getElementById("display_popup_jobclosure").innerHTML=xmlhttp.responseText;
				//	$("#overlay_display_JobClosure_form").fadeOut("fast");
				//$("#popupwindow_display_JobClosure_form").fadeOut("fast");
				$("#disables_div *").attr("disabled", "disabled").off('click');
				 document.getElementById('medicines').style.display = "block";
				
			}
		}
		xmlhttp.open("POST","Save_Job_closure_form.php?event_id="+event_id+"&service_professional_id="+service_professional_id+"&service_id="+service_id+"&date_service="+date_service+"&baseline="+baseline+"&airway="+airway+"&Breathing="+Breathing+"&Circulation="+Circulation+"&skin_perfusion="+skin_perfusion+"&JobClosure_temp="+JobClosure_temp+"&JobClosure_TBSL="+JobClosure_TBSL+"&JobClosure_Pulse="+JobClosure_Pulse+"&JobClosure_SpO2="+JobClosure_SpO2+"&JobClosure_RR="+JobClosure_RR+"&JobClosure_GCS="+JobClosure_GCS+"&JobClosure_BP_high="+JobClosure_BP_high+"&JobClosure_BP_low="+JobClosure_BP_low+"&Jobclosure_summery="+Jobclosure_summery,true);
		xmlhttp.send();
	}
	function Save_jobclosure_unit_medicine(event_id,service_professional_id,service_id,date_service)
	{
		
		var Medicine_unit=document.getElementById('Medicine_unit').value;
		var Medicine_unit_textbox=document.getElementById('Medicine_unit_textbox').value;
		
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
				//alert(xmlhttp.responseText);
				alert('unit medicine add');
				$('#Medicine_unit_textbox').val('');
				$('#Medicine_unit').val('');
				
			}
		}
		xmlhttp.open("POST","Save_medicines_consumables.php?event_id="+event_id+"&service_professional_id="+service_professional_id+"&service_id="+service_id+"&date_service="+date_service+"&flag=1"+"&Medicine_unit="+Medicine_unit+"&Medicine_unit_textbox="+Medicine_unit_textbox,true);
		xmlhttp.send();
	}
	function Save_jobclosure_non_unit_medicines(event_id,service_professional_id,service_id,date_service)
	{
		var Medicine_Non_unit=document.getElementById('Medicine_Non_unit').value;
		var Medicine_Non_unit_textbox=document.getElementById('Medicine_Non_unit_textbox').value;
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
				
				alert('non unit medicine add');
				$('#Medicine_Non_unit_textbox').val('');
				$('#Medicine_Non_unit').val('');
				
			}
		}
		xmlhttp.open("POST","Save_medicines_consumables.php?event_id="+event_id+"&service_professional_id="+service_professional_id+"&service_id="+service_id+"&date_service="+date_service+"&flag=2"+"&Medicine_Non_unit="+Medicine_Non_unit+"&Medicine_Non_unit_textbox="+Medicine_Non_unit_textbox,true);
		xmlhttp.send();
	}
	function Save_jobclosure_unit_consumables(event_id,service_professional_id,service_id,date_service)
	{
		var consumables_unit=document.getElementById('consumables_unit').value;
		var consumables_unit_textbox=document.getElementById('consumables_unit_textbox').value;
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
				
				alert(' unit consumable add');
				$('#consumables_unit_textbox').val('');
				$('#consumables_unit').val('');
			}
		}
		xmlhttp.open("POST","Save_medicines_consumables.php?event_id="+event_id+"&service_professional_id="+service_professional_id+"&service_id="+service_id+"&date_service="+date_service+"&flag=3"+"&consumables_unit="+consumables_unit+"&consumables_unit_textbox="+consumables_unit_textbox,true);
		xmlhttp.send();
	}
	function Save_jobclosure_non_unit_consumables(event_id,service_professional_id,service_id,date_service)
	{
		var consumables_Non_unit=document.getElementById('consumables_Non_unit').value;
		var consumables_Non_unit_textbox=document.getElementById('consumables_Non_unit_textbox').value;
		
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
				
				alert('non unit consumable add');
				$('#consumables_Non_unit_textbox').val('');
				$('#consumables_Non_unit').val('');
			}
		}
		xmlhttp.open("POST","Save_medicines_consumables.php?event_id="+event_id+"&service_professional_id="+service_professional_id+"&service_id="+service_id+"&date_service="+date_service+"&flag=4"+"&consumables_Non_unit="+consumables_Non_unit+"&consumables_Non_unit_textbox="+consumables_Non_unit_textbox,true);
		xmlhttp.send();
    }

	</script>
	<style>
	#overlay_display_JobClosure_form
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
	 #popupwindow_display_JobClosure_form
   {
      width:850px;
		min-height:auto;
		border-radius:10px;
		margin:0 auto;
		position:absolute;
		top:10%;
		right:20%;
		bottom:10%;
		left:20%;
		z-index:1500;
		background-color: white;
		box-shadow: 0 2px 20px #666666;
		-moz-box-shadow: 0 2px 20px #666666;
		-webkit-box-shadow: 0 2px 20px #666666;
		overflow:scroll;
		display:none;
		
   }
	#overlay_display_add_jobclosure
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
 #popupwindow_display_jobclosure
   {
      width:610px;
		min-height:auto;
		border-radius:10px;
		margin:0 auto;
		position:absolute;
		top:10%;
		right:20%;
		bottom:10%;
		left:20%;
		z-index:1500;
		background-color: white;
		box-shadow: 0 2px 20px #666666;
		-moz-box-shadow: 0 2px 20px #666666;
		-webkit-box-shadow: 0 2px 20px #666666;
		overflow:scroll;
		display:none;
		
   }
	</style>
    <body class="col-lg-12" style="background-color: #00cfcb;"> 

    <div id="page-wrapper">
           
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-11" style="margin-left:5%;margin-right:5%;margin-top:2%">
                <div class="panel panel-default">
                <div class="panel-body">
				<div class="col-lg-12" >
				 <div class="panel panel-default">
				 <div class="panel-body shadow_border">	
				
				<div class="col-lg-12" >
                  <h1>Service Details Of Job Closure</h1>
				 </div>
				 <div class="row">
				<div class="col-lg-12" >
                  <h4>Patient Name:    <?php echo $name.' '.$first_name.' '.$middle_name; ?></h4>
				 </div>
				 </div>
				 <div class="row">
				<div class="col-lg-12" >
                  <h4>Event Code:    <?php echo $event_code; ?></h4>
				 </div>
				
				 </div>
				
	
				 <div id="thisdiv">
				 
 <?php
 $Professional_service123= mysql_query("SELECT * FROM sp_event_professional where event_id='$event_id'  and professional_vender_id='$service_professional_id'  and service_id='$service_id'");
 $row_count123 = mysql_num_rows($Professional_service123);
  $count=0;
 if($row_count123 > 0)
{
	echo '<table id="logTable" class="table table-striped">
            <thead>
              <tr bgcolor="#00cfcb" style="color:white">
                
                <th>Service</th>
				<th>Day</th>
				<th>Date</th>
				<th>Time</th>
                <th>Add</th>
				
              </tr>
            </thead>
            <tbody>';
	while($row123=mysql_fetch_array($Professional_service123))
	{
	
	$event_requirement_id=strip_tags($row123['event_requirement_id']);
$Professional_service= mysql_query("SELECT * FROM sp_event_plan_of_care where event_id='$event_id'  and event_requirement_id='$event_requirement_id' ");
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
			
			
			$service=mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'") or die(mysql_error());
		$service = mysql_fetch_array($service) or die(mysql_error());
		$sub_service_id=$service['sub_service_id'];
		$service_id=$service['service_id'];
		
		//service name
		$service_name=mysql_query("SELECT * FROM sp_sub_services where sub_service_id='$sub_service_id'") or die(mysql_error());
		$service_name = mysql_fetch_array($service_name) or die(mysql_error());
		$recommomded_service=$service_name['recommomded_service'];
		
		$begin = new DateTime($service_date);
//$end = new DateTime($service_date_to);
$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
$end = new DateTime($end);
$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);


foreach($daterange as $date){
    //$count++;
	
	$count=$count+1;
//<td>'.'Day-'.$count.'</td>
$date_service=$date->format("Y-m-d") ;
			echo '<tr >
                <td >'.$recommomded_service.' </td>
                
				 <td>'.$date_service.'</td>';
				
			
			$query=mysql_query("select * from sp_jobclosure_detail_datewise where service_date='$date_service' and event_id='$event_id' and sub_service_id='$sub_service_id'  and added_by='$service_professional_id' ")or die(mysql_error("error"));
				if(mysql_num_rows($query) < 1 )
				{
					echo '<td>
					 <input type="date" id="Actual_service_date">
					</td>';
				
				}
				else
				{
					$row=mysql_fetch_array($query);
					$actual_service_date=$row['actual_service_date'];
					echo '<td>'.$actual_service_date.'</td>';
				
				}
			echo '<td>'.$start_date.' - '.$end_date.'</td>';
				
				//start_date
				$query=mysql_query("select * from sp_jobclosure_detail_datewise where service_date='$date_service' and event_id='$event_id' and sub_service_id='$sub_service_id'  and added_by='$service_professional_id' ")or die(mysql_error("error"));
				if(mysql_num_rows($query) < 1 )
				{
					echo '<td>
					 <input type="button" value="Add" onclick="Save_Jobclosure_datewise(\'' . $event_id . '\',\'' . $sub_service_id  .'\',\'' . $date_service  .'\',\'' . $service_professional_id  .'\',\'' . $time  .'\',\'' . $service_id  .'\')"; >
					</td>';
				
				}
				else
				{
					$row=mysql_fetch_array($query);
					$job_closure_detail=$row['job_closure_detail'];
					
					echo '<td>'.$job_closure_detail.'</td>';
				
				}
				echo '</tr>';
			
		
			
}

		}
		//$i++;
		
 }}}
		else
		{
			echo 'Not avilable';
		}
 
		$service_count=0;
		$service= mysql_query("SELECT * FROM sp_jobclosure_detail_datewise where event_id='$event_id' and service_id='$service_id' and status='1'");
		while($row=mysql_fetch_array($service))
		{
			$service_count++;
		}
		//echo $service_count;
		if($count==$service_count)
		{
				echo '<div  align="right"><input type="button" value="Close Job Closure" onclick="Job_closure_form(\'' . $event_id . '\',\'' . $service_professional_id . '\',\'' . $service_id . '\',\'' . $date_service . '\',\'' . $time . '\');" style="margin-right:2%;margin-bottom:1%;background-color: #00cfcb;color:white"></div>';
		}
		else
		{
			
		}
		
		?>
	
<div id="display_popup_jobclosure">
</div>
<div id="display_popup_jobclosure_form"></div>
</div>
					</div></div></div>
					
                </div>
				
				 
                </div>
                <!-- /.col-lg-12 -->
            </div>
			</div>
  </div>
  
  <div id="overlay_display_add_jobclosure">
  <div id="popupwindow_display_jobclosure">
  <div>Add New display</div>
  </div>
  </div>
  
    </body>
	<?php 
//session_destroy();
}

else
{
    //echo "<script type='text/javascript'>Alert.render('User Login');</script>";
    echo "<script>
alert('Your Session is expire...Palese Login again!! ');

</script>";
   header("Location:index.php");
 }}
 ?>
</html>
