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
<script>
function cancle_Location(patient_id,Number)
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
			alert(xmlhttp.responseText);
			$("#overlay_display").fadeOut("fast");
			$("#popupwindow_display").fadeOut("fast");
			location.reload();
		}
	}
	xmlhttp.open("POST","Cancle_booking_patient.php?patient_id="+patient_id+"&Number="+Number,true);
	xmlhttp.send();
	
}
function Booking_popup(Number)
{
	
	//$("#overlay_display").fadeIn("slow");
	//$("#popupwindow_display").fadeIn("slow");
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
					//document.getElementById("Flat_Number").value = Number;
                   	document.getElementById("Assisted_View_booking_patient").innerHTML=xmlhttp.responseText;
					$("#overlay_display").fadeIn("slow");
					$("#popupwindow_display").fadeIn("slow");
					//location.reload();
					
				}
			}
			xmlhttp.open("POST","View_booking_patient.php?Number="+Number,true);
			xmlhttp.send();
		
}

function Close_Popup()
{
	$("#overlay_display").fadeOut("fast");
	$("#popupwindow_display").fadeOut("fast");
}	

function save_Booing_patient()
{
	var patient_id = document.getElementById("patient_id").value ;
	var Facility_type = document.getElementById("Facility_type").value ;
	var Patient_location = document.getElementById("Patient_location").value ;
	var Flat_Number = document.getElementById("Flat_Number").value ;
	var xmlhttp;
	if(patient_id=='')
	{
		document.getElementById('Error_Msg_Patient_name').innerHTML="Please Select Patient Name";
	}
	else if(Facility_type=='')
	{
		document.getElementById('Error_Msg_facility_name').innerHTML="Please Select Facility";
	}
	else if(Patient_location=='')
	{
		document.getElementById('Error_Msg_Patient_location').innerHTML="Please Select Patient Location";
	}
	else
	{
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
			$("#overlay_display").fadeOut("fast");
			$("#popupwindow_display").fadeOut("fast");
		}
		else if(xmlhttp.responseText='Busy')
		{
			alert('Sorry,Choose other bed');
		}
		else
		{
			alert('Please try again');
		}
		location.reload();
	}
	}
	xmlhttp.open("POST","Save_assisted_living_booking.php?patient_id="+patient_id+"&Facility_type="+Facility_type+"&Patient_location="+Patient_location+"&Flat_Number="+Flat_Number,true);
	xmlhttp.send();
	}
}
</script>
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
                            <img src="images/locations_big.png" alt="Manage Payments">Assisted Living Booking                 
                            <!--<a href="javascript:void(0);" onclick="return vw_add_location(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD LOCATION</a>-->
                        </h1>
                    </div>
                </div>

  <?php  include "include_assisted_living_booking.php"; ?>
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
</div>
</div>
<style>

#overlay_display
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
 #popupwindow_display
   {
      width:450px;
		height:500px;
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


</body>
</html>