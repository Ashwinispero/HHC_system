<?php
session_start();
$Service_id=$_SESSION['Service_id'];
$service_professional_id=$_SESSION['service_professional_id'];
$time=$_SESSION['login_time'];
//echo $time;
 include('config.php');
$Professional_name=mysql_query("SELECT * FROM sp_service_professionals where service_professional_id='$service_professional_id'") or die(mysql_error());
$Professional_name = mysql_fetch_array($Professional_name) or die(mysql_error());
$title=$Professional_name['title'];
$name=$Professional_name['name'];
$first_name=$Professional_name['first_name'];
$middle_name=$Professional_name['middle_name'];
$email_id=$Professional_name['email_id'];
$mobile_no=$Professional_name['mobile_no'];
$work_email_id=$Professional_name['work_email_id'];
$phone_no=$Professional_name['phone_no'];
//$address=$Professional_name['address'];
//Session expire after 15 min
if( !isset( $_SESSION['service_professional_id'] ) || time() - $_SESSION['login_time'] > 1800)
{
   header("Location:index.php");
}
 else {
    
$_SESSION['login_time'] = time();

if(isset($_SESSION['service_professional_id']) && !empty($_SESSION['service_professional_id']) ) {
?>
<head>
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

<link href="css/bootstrap.min.css" rel="stylesheet">

<title >Physiotherapy Job Closure</title>
</head>
	<script>
	function ViewEvent(event_id,time,service_professional_id)
	{
		//alert(event_id);
		//alert(service_professional_id);
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
					//window.location ="index.php";
				}
				else{
				document.getElementById("display_popup_view_event_form").innerHTML= xmlhttp.responseText;
				$("#overlay_display_view_event_form").fadeIn("slow");
				$("#popupwindow_display_view_event_form").fadeIn("slow");
				}
				
			}
		}
		xmlhttp.open("POST","view_event_form.php?event_id="+event_id+"&time="+time+"&service_professional_id="+service_professional_id,true);
		xmlhttp.send();
	}
	function close_popup_event_form()
	{
		$("#overlay_display_view_event_form").fadeOut("fast");
		$("#popupwindow_display_view_event_form").fadeOut("fast");
	}
	function change_password(service_professional_id,Service_id,time)
	{
		//alert(time);
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
					//window.location ="index.php";
				}
				else
				{
					document.getElementById("display_popup_change_pw").innerHTML=xmlhttp.responseText;
				$("#overlay_display_change_pw").fadeIn("slow");
				$("#popupwindow_display_change_pw").fadeIn("slow");
				}
				
				
			}
		}
		xmlhttp.open("POST","change_password_professional.php?service_professional_id="+service_professional_id+"&Service_id="+Service_id+"&time="+time,true);
		xmlhttp.send();
	}
	function close_popup_password_form()
	{
		$("#overlay_display_change_pw").fadeOut("fast");
		$("#popupwindow_display_change_pw").fadeOut("fast");
	}
	function Password_chnage(service_professional_id,Service_id)
	{
		//alert(Service_id);
		var old_pw=document.getElementById('old_pw').value;
		var new_pw=document.getElementById('new_pw').value;
		var Confirm_pw=document.getElementById('Confirm_pw').value;
		
		if(old_pw=='')
		{
			
			document.getElementById('error_message_old_msg').innerHTML="Enter Old Password";
		}
		else if(new_pw=='')
		{
			document.getElementById('error_message_new_pw').innerHTML="Enter New Password";
		}
		else if(Confirm_pw=='')
		{
			document.getElementById('error_message_confirm_pw').innerHTML="Enter Confirm Password";
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
				//document.getElementById("display_popup_change_pw").innerHTML=xmlhttp.responseText;
				//$("#overlay_display_change_pw").fadeIn("slow");
				//$("#popupwindow_display_change_pw").fadeIn("slow");
				if(xmlhttp.responseText=='Pw_incorrect'){
					document.getElementById('error_message').innerHTML="New Password & confirm password do not match";
				}
				if(xmlhttp.responseText=='old_pw_incorrect')
				{
					document.getElementById('error_message_old_msg').innerHTML="Old Password Not match";
					
		
				}
				if(xmlhttp.responseText=='success')
				{
					alert('Password Successfully Change');
					$("#overlay_display_change_pw").fadeOut("fast");
					$("#popupwindow_display_change_pw").fadeOut("fast");
				}
			}
		}
		xmlhttp.open("POST","Change_password_final_page.php?service_professional_id="+service_professional_id+"&old_pw="+old_pw+"&new_pw="+new_pw+"&Confirm_pw="+Confirm_pw+"&Service_id="+Service_id,true);
		xmlhttp.send();
		}
		
	}
	function remove_error_msg_old_pw()
	{
		var old_pw=document.getElementById('old_pw').value;
		if(old_pw!='')
		{
		document.getElementById('error_message_old_msg').innerHTML="";
		}
	}
	function remove_error_msg_new_pw()
	{
		var new_pw=document.getElementById('new_pw').value;
		if(new_pw!='')
		{
		document.getElementById('error_message_new_pw').innerHTML="";
		}
	}
	function remove_error_msg_confirm_pw()
	{
		var Confirm_pw=document.getElementById('Confirm_pw').value;
		if(Confirm_pw!='')
		{
		document.getElementById('error_message_confirm_pw').innerHTML="";
		}
	}
	
		
	
	</script>
	<style>
#overlay_display_change_pw
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
#popupwindow_display_change_pw
{
	 background-color:white;
    height: 80%;
    position:relative;
    margin:0 auto;
    padding:3em;
    overflow-y: scroll;
	 width:40%;
	  top:10%;
    -webkit-overflow-scrolling: touch;
    @media (min-width: 100%) {
      height:75%;
  	 margin-left:5px;
	 margin-right:5px;
  	 max-height: 57em;
      max-width:100%;
      width:100%;
		display:none;
	}
}
	#overlay_display_view_event_form
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
	 #popupwindow_display_view_event_form
   {
      background-color:white;
    height: 80%;
    position:relative;
    margin:0 auto;
    padding:3em;
    overflow-y: scroll;
	 width:80%;
	  top:10%;
    -webkit-overflow-scrolling: touch;
    @media (min-width: 100%) {
      height:75%;
  	 margin-left:5px;
	 margin-right:5px;
  	 max-height: 57em;
      max-width:100%;
      width:100%;
	 
	}
   }
   </style>

<body style="background-color: #cecece;">
<header>
<div class="col-lg-12" >
 <nav class="navbar navbar-default">
    <div class="container-fluid" style="background-color: black;">
      <div class="navbar-header" >
       
        <div class="col-lg-4"><a href="event-log.php" ><img src="images/login-logo.png" alt="SPERO"></a> </div></div>
		 
		<div class="col-lg-7"><div align="center"><font size="5" style="color:#00cfcb">JOB CLOSURE</font></div></div>
		<div class="col-lg-3">
      <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav navbar-right">
           
        
          
          <li class="dropdown"><a href="javascript:void(0);" data-toggle="dropdown" class="dropdown-toggle" id="drop1" style="color:white;"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile</a>
          
		   <ul aria-labelledby="drop1" role="menu" class="dropdown-menu my-profile" style="width:550px;">
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text">
                    <div class="col-lg-5" style="color:#00cfcb;"><span>Name:</span></div><?php echo $title.'. '.$name.' '.$first_name.' '.$middle_name ?></a>
                </li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5" style="color:#00cfcb;"><span>Email:</span></div><?php echo $email_id; ?></a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5" style="color:#00cfcb;"><span>Mobile:</span></div><?php  echo $mobile_no; ?></a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5" style="color:#00cfcb;"><span>Landline:</span></div><?php echo $phone_no; ?></a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5" style="color:#00cfcb;"><span>Work Email:</span></div><?php echo $work_email_id; ?></a></li>
                <li class="divider" role="presentation"></li>
				<li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5" style="color:#00cfcb;"><span>Change Password:</span></div><input type="button" id="change_password" onclick="change_password(<?php echo $service_professional_id; ?>,<?php echo $Service_id; ?>, <?php echo $time; ?>);" style="color:white;background-color:#00cfcb"  value="Change Password"></a></li>
                <li class="divider" role="presentation"></li>
                
            </ul>

          </li>
          <li><a href="javascript:void(0);" class="headerText" style="color:white;"> Welcome <span class="user-name"><?php echo $title.'. '.$name;?></span></a></li>
          
		  <li><input type="button" value="Logout" style="margin-top:10px;background-color:#ffbf00;border-radius: 15px;" onclick="location.href='index.php'" ></input></li>
        	
        </ul>
      </div>
	  </div>
	  <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <!--/.nav-collapse --> 
    </div>
    <!--/.container-fluid --> 
  </nav>
  </div>
</header>
<div class="col-lg-12">
<div class="panel panel-default" style="background-color:#FFFAF0;">
<div class="row">
    <?php 
$service=mysql_query("SELECT * FROM sp_services where service_id='".$Service_id."'") or die(mysql_error());
		$service_name = mysql_fetch_array($service) or die(mysql_error());
		$service_title=$service_name['service_title'];
?>
    <h2 align="center" style="color:#00cfcb;"><?php echo $service_title; ?> Job Closure</h2>
	</div><div class="row">
	<div class="col-lg-11" style="margin-left:4%;margin-right:4%">
	<div class="panel panel-default">
	 <?php
if($Service_id=='3' OR $Service_id=='16') 
	{
		$Professional_service= mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and  service_closed='N' and (service_id='3' OR service_id='16') group by event_id order by event_id DESC");
		$row_count = mysql_num_rows($Professional_service);
	}
	else
	{
		$Professional_service= mysql_query("SELECT * FROM sp_event_professional where professional_vender_id='$service_professional_id' and service_id='$Service_id' and  service_closed='N' group by event_id order by event_id DESC");
		$row_count = mysql_num_rows($Professional_service);
	}
 //echo $row_count;
 if($row_count > 0)
		{
			?>
	
	
	<div class="table-responsive">
	<table class="table table-bordered">
	 <tr bgcolor="#00cfcb" style="color:white"> 
        <th width="15%">HHC No</th>
		<th width="15%">Event Code</th>
        <th width="20%">Event Eate</th>
		<th width="25%">Patient Name</th>
        <th width="13%">View Event Details</th>
		<th width="10%">Action</th>
	<tr>
	<?php 
	while($row=mysql_fetch_array($Professional_service))
		{
			//echo $row_count;
			$event_id=strip_tags($row['event_id']);
			//echo $event_id;
			$service_id=strip_tags($row['service_id']);
			$event_requirement_id=strip_tags($row['event_requirement_id']);
				$service_closed=strip_tags($row['service_closed']);
			//echo $event_requirement_id;
		
			
			$service=mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'") or die(mysql_error());
			 $row_count1 = mysql_num_rows($service);
			if($row_count1 > 0)
			{
				$service = mysql_fetch_array($service) or die(mysql_error());
			$sub_service_id=$service['sub_service_id'];
		//	echo $sub_service_id;
		//service name
		$service_name=mysql_query("SELECT * FROM sp_sub_services where sub_service_id='$sub_service_id'") or die(mysql_error());
		$service_name = mysql_fetch_array($service_name) or die(mysql_error());
		$recommomded_service=$service_name['recommomded_service'];
			}
			else
			{
				$recommomded_service=' ';
			}
			
	
			
			$event_detail=mysql_query("SELECT * FROM sp_events where event_id='$event_id'") or die(mysql_error());
			$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
			$event_code=$event_detail['event_code'];
			$patient_id=$event_detail['patient_id'];
			$event_date=$event_detail['event_date'];
			$Formated_event_date=date("d-m-Y h:m:s", strtotime($event_date));
			//$event_status=$event_detail['event_status'];
			
			$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
			$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
			$name=$patient_nm['name'];
			$first_name=$patient_nm['first_name'];
			$middle_name=$patient_nm['middle_name'];
			$hhc_code=$patient_nm['hhc_code'];
			//echo $event_id;
		  ?>
		
		
	
	<tr >
                <td ><?php echo $hhc_code; ?></td>
                <td><?php echo $event_code; ?></td>
				<td><?php echo $Formated_event_date; ?></td>
				<td><?php echo $name.' '.$first_name.' '.$middle_name; ?></td>
				<?php echo ' <td align="center">
				<a href="javascript:void(0);" title="View Event" onclick="ViewEvent('.$event_id.','.$time.','.$service_professional_id.')"; data-toggle="tooltip" data-placement="top" title="View Log" >
                    <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span>
                </a>
				</td>
				<td>
				    <a href="detail_of_services.php?event_requirement_id=' . $event_requirement_id . '&service_professional_id='.$service_professional_id.'&event_id='.$event_id.'&time='.$time.'&service_id='.$service_id.' " class="btn btn-logout">Add Job Closure</a>
				</td>' ;?>
		</tr>
		<?php } }?>
	</table>
	</div></div></div></div></div></div>
	<?php 
//session_destroy();
}

else
{
    //echo "<script type='text/javascript'>Alert.render('User Login');</script>";
    
   header("Location:index.php");
 }}
 ?>
 <div id="display_popup_view_event_form"></div>
<div id="display_popup_change_pw"></div>
 <div id="overlay_display_view_event_form">
  <div id="popupwindow_display_view_event_form">
  <div>Add New display</div>
  </div>
  </div>
</body>