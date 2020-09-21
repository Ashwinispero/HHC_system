<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
	  
	  
	  require_once 'inc_classes.php';        
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";        
        include "classes/eventClass.php";
        $eventClass = new eventClass();
        include "classes/employeesClass.php";
        $employeesClass = new employeesClass();
	include "classes/professionalsClass.php";
        $professionalsClass = new professionalsClass();
        include "classes/consultantsClass.php";
        $consultantsClass = new consultantsClass();
        include "classes/commonClass.php";
        $commonClass= new commonClass();
        require_once 'classes/functions.php';
?>
<html lang="en">
<head>
<title>Welcome to SPERO</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>
<script>
function search_current_calls()
	{
		//-----Author: ashwini -----
		var Current_call=document.getElementById('Current_call').value;
		if(Current_call=='')
		{
			document.getElementById('error_message_Current_call').innerHTML="Please Select date";
		}
		
		else
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
                   	document.getElementById("View_calls").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST","include_view_calls.php?Current_call="+Current_call,true);
			xmlhttp.send();
		}
	}
	function SMS()
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
					alert("MSG Successfully Send");
					alert(xmlhttp.responseText);
                   	//document.getElementById("View_calls").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST","include_SMS_calls.php?flag=1",true);
			xmlhttp.send();
	}
</script>
<?php 
date_default_timezone_set('Asia/Kolkata');
?>
<body style="background-color:white">
<div id="wrapper" >
    <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
				
				<div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" align="Left" style="color:black">
							View call            
						</h1>
                    </div>
                </div>
				
				<div class="row">
					<div class="col-sm-2" style="font-size:20px">Select Date:</div>
                    <div class="col-sm-2" >
                        <input type="date"  id="Current_call" name="Current_call" placeholder="Current_call" style="width:100%" >
						<div id="error_message_Current_call" style="color:red"></div>
					</div>
					<div class="col-lg-3 marginB20 paddingl0">
                    <input type="button" onclick="return search_current_calls();" value="View Call's" name="btn-view-schedule" class="btn btn-download" style="background-color:#00cfcb;color:white">
					</div>
				</div>
				
				<div id="View_calls">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" align="left" style="color:black">
							Today Call-<?php echo date('d-m-Y');?>             
						</h1>
                    </div>
                </div>
				<div>
				<div class="panel-body">
                <?php 
			 
			$date = date('d-m-Y');
			$new_date=date('Y-m-d H:i:s', strtotime($date));
			$new_date1 = date('Y-m-d H:i:s', strtotime($new_date . ' +1 days'));
			$today_date=date('Y-m-d', strtotime($date));
			$Previous_date = date('Y-m-d H:i:s', strtotime($new_date . ' -35 days'));
			
			
			echo '<table id="logTable" class="table table-hover table-bordered" style="background-color:white" cellspacing="0">
            <thead>
            <tr>
			<th width="2%">Sr. No</th>
            <th width="5%">Event Code</th>
			<th width="8%">Patient Name</th>
			<th width="8%">Professional Name</th>
			<th width="4%">Service Name</th>
            <th width="3%">Start Date</th>
			<th width="3%">End Date</th>
			<th width="5%">Service Status</th>
			</tr>
            </thead>
            <tbody>';
			$count=0;
			$Physician_assistant=0;
			$Physiotherapy=0;
			$Healthcare_attendants=0;
			$Nurse=0;
			$Laboratory_services=0;
			$Respiratory_care=0;
			$X_rayat_home=0;
			$Hca_package=0;
			$Medical_transportation=0;
			$Physiotherapy_New=0;
			$Physician_service=0;
			$Maid_service=0;
			$Total_Services=0;
			
			$plan_of_care=mysql_query("SELECT * FROM sp_event_plan_of_care  where added_date BETWEEN '$Previous_date%' AND '$new_date1%'");
			while($plan_of_care_detail=mysql_fetch_array($plan_of_care))
			{
				$service_date=$plan_of_care_detail['service_date'];
				$service_date_to=$plan_of_care_detail['service_date_to'];
				$event_requirement_id=$plan_of_care_detail['event_requirement_id'];
				$professional_vender_id=$plan_of_care_detail['professional_vender_id'];
				$event_id=$plan_of_care_detail['event_id'];

				$max_id=mysql_query("SELECT MAX(plan_of_care_id) as max_id,service_date_to FROM sp_event_plan_of_care  where event_id='$event_id'") or die(mysql_error());
				$max_id_row = mysql_fetch_array($max_id);
				$plan_of_care_id=$max_id_row['max_id'];

				$plan_of_care_max=mysql_query("SELECT service_date_to FROM sp_event_plan_of_care  where plan_of_care_id=$plan_of_care_id");
				$plan_of_care_max_row = mysql_fetch_array($plan_of_care_max);
				$service_date_to_max=$plan_of_care_max_row['service_date_to'];
				//echo $service_date_to_max;
				$event_requirement=mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'") or die(mysql_error());
				$event_requirement_row = mysql_fetch_array($event_requirement);
				$service_id=$event_requirement_row['service_id'];
				$sub_service_id=$event_requirement_row['sub_service_id'];
				if($service_id!=10 AND $service_id!=6 AND $sub_service_id!=423 AND $service_id !=9 )
				{
					
					$query1=mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$professional_vender_id' and status='1'");
				if(mysql_num_rows($query1) < 1 )
				{
						//echo 'abc';
				}
				else
				{
					
					$Professional_name = mysql_fetch_array($query1) or die(mysql_error());
					$title=$Professional_name['title'];
					$name=$Professional_name['name'];
					$first_name=$Professional_name['first_name'];
					$middle_name=$Professional_name['middle_name'];
					$Prof_name=$title.' '.$first_name.' '.$middle_name.' '.$name;
				}
					
				$event_Service=mysql_query("SELECT * FROM sp_services where service_id='$service_id'") or die(mysql_error());
				$event_Service_row = mysql_fetch_array($event_Service);
				$service_title=$event_Service_row['service_title'];
					
				$begin = new DateTime($service_date);
				$today = date("Y-m-d"); 
				
				
				//echo $abc;
				$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
				$end = new DateTime($end);
				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
				foreach($daterange as $date)
				{
					$date_service=$date->format("Y-m-d") ;
					if($date_service==$today_date)
					{
						//echo $date_service;
						if($service_id==2){$Physician_assistant++;}
					elseif($service_id==3){$Physiotherapy++;}
					elseif($service_id==4){$Healthcare_attendants++;}
					elseif($service_id==5){$Nurse++;}
					elseif($service_id==8){$Laboratory_services++;}
					elseif($service_id==11){$Respiratory_care++;}
					elseif($service_id==12){$X_rayat_home++;}
					elseif($service_id==13){$Hca_package++;}
					elseif($service_id==15){$Medical_transportation++;}
					elseif($service_id==16){$Physiotherapy_New++;}
					elseif($service_id==18){$Physician_service++;}
					elseif($service_id==19){$Maid_service++;}
						
						
						$count++;
						//$event_id=$plan_of_care_detail['event_id'];
						$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
						$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
						$event_code=$sql11['event_code'];
						$patient_id=$sql11['patient_id'];
								
						$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
						$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
						$Patient_name=$patient_nm['name'];
						$Patient_first_name=$patient_nm['first_name'];
						$Patient_middle_name=$patient_nm['middle_name'];
						$patient_name=$Patient_first_name.' '.$Patient_name;
						

					echo '<tr>
					<td align="center">'.$count.'</td>
					<td align="center">'.$event_code.'</td>
					<td align="center">'.$patient_name.'</td>
					<td align="center">'.$Prof_name.'</td>
					<td align="center">'.$service_title.'</td>
					<td align="center">'.$service_date.'</td>
					<td align="center">'.$service_date_to.'</td>';
					if($today==$service_date_to_max)
					{
						echo '<td align="center" style="Color:red">'.'This Servcie will end today'.'</td>';
					}
					else{
						echo '<td align="center">'.'In-Progress'.'</td>';
					}
					echo '</tr>';
					}
				}
				}
			}
			echo '</tbody></table>';
			?>
			<div class="row">
                    <div class="col-lg-3">
					
			<?php
			$Total_Services=$Physician_assistant+$Physiotherapy+$Healthcare_attendants+$Nurse+$Laboratory_services+$Respiratory_care+$X_rayat_home+$Hca_package+$Medical_transportation+$Physiotherapy_New+$Physician_service+$Maid_service;
			?>
			
			<?php
			echo '<table id="logTable" class="table table-hover table-bordered" style="background-color:white" cellspacing="0">
            <thead>
            <tr>
			<th width="2%" align="center">Services</th>
            <th width="2%" align="center">Count</th>
			</tr>
            </thead>
            <tbody>';
				echo '<tr>
					<td>'.'Physician Assistant'.'</td>
					<td>'.$Physician_assistant.'</td>';
				 '</tr>';
				echo '<tr>
					<td>'.'Physiotherapy'.'</td>
					<td>'.$Physiotherapy.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Healthcare Attendants'.'</td>
					<td>'.$Healthcare_attendants.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Nurse'.'</td>
					<td>'.$Nurse.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Laboratory Services'.'</td>
					<td>'.$Laboratory_services.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Respiratory Care'.'</td>
					<td>'.$Respiratory_care.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'X rayat home'.'</td>
					<td>'.$X_rayat_home.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Hca Package'.'</td>
					<td>'.$Hca_package.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Medical Transportation'.'</td>
					<td>'.$Medical_transportation.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Physiotherapy New'.'</td>
					<td>'.$Physiotherapy_New.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Physician Service'.'</td>
					<td>'.$Physician_service.'</td>';
				 '</tr>';
				 echo '<tr>
					<td>'.'Maid Service'.'</td>
					<td>'.$Maid_service.'</td>';
				 '</tr>';
				 echo '<tr>
					<td><strong>'.'Total Services'.'</strong></td>
					<td><strong>'.$Total_Services.'</strong></td>';
				'</tr>';
				 
			echo '</tbody></table>';
			?>
			
			
			</div>
          </div>	
			<div class="row">
			<div class="col-lg-3" align="right">
				<input type="button" onclick="return SMS();" value="SMS" name="btn-view-schedule" class="btn btn-download" style="background-color:#00cfcb;color:white">
			</div>
			</div>
             </div>
          </div>
          </div>
        
    </div>
	</div>
	
</div>




   
</body>
</html>
