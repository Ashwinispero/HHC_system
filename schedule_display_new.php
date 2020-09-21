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
		include "include/scripts.php";
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
<link href="css/sb-admin.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<script>
function search_current_calls()
	{
      // alert('hii');
	 	
        var Profesional_service=document.getElementById('Profesional_service').value;
		if(Profesional_service=='')
		{
			document.getElementById('error_message_Current_call').innerHTML="Please Select date";
		}
		else
		{
			Popup_Display_Load();
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
					Popup_Hide_Load();
                   	document.getElementById("View_Professional_schedule_detail").innerHTML=xmlhttp.responseText;
				}
			}
            
            xmlhttp.open("POST","Professional_Schedule_display.php?Profesional_service="+Profesional_service,true);
            xmlhttp.send();
		}
	}
	function Previous_date()
	{
		var Previous_date=document.getElementById('Previous_date').value;
		var Profesional_service=document.getElementById('Profesional_service').value;
		Popup_Display_Load();
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
				Popup_Hide_Load();
				document.getElementById("View_Professional_schedule_detail").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("POST","Previous_Professional_schedule_display.php?Profesional_service="+Profesional_service+"&Previous_date="+Previous_date,true);
		xmlhttp.send();
		//Previous_day_report1
	}
	function Next_date()
	{
		var Next_date=document.getElementById('Next_date').value;
		var Profesional_service=document.getElementById('Profesional_service').value;
		Popup_Display_Load();
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
				Popup_Hide_Load();
				document.getElementById("View_Professional_schedule_detail").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("POST","Next_Professional_schedule_display.php?Profesional_service="+Profesional_service+"&Next_date="+Next_date,true);
		xmlhttp.send();
	}
	function Serch_professional_Schedule(Professional_id)
	{
		var Professional_id_old=$('option[value="'+Professional_id+'"]');
		var Professional_id = (Professional_id_old.attr('id'));
		var Profesional_service=document.getElementById('Profesional_service').value;
		Popup_Display_Load();
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
				Popup_Hide_Load();
				document.getElementById("View_Professional_schedule_detail").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("POST","Professional_schedule_search.php?Professional_id="+Professional_id+"&Profesional_service="+Profesional_service,true);
		xmlhttp.send();
	}
</script>
<?php 
date_default_timezone_set('Asia/Kolkata');
?>
<body style="background-color:white">
<div id="load"></div>
<div id="wrapper" >
    <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
				
				<div class="row">
                    <div class="col-lg-12">
                    <h1 class="page-header" align="center" style="color:black">
                            Spero Employee Schedule Details              
                    </h1>
                    </div>
                </div>
				<div class="row">
				<?php 
					date_default_timezone_set('Asia/Kolkata'); 
					$date = date('d-m-Y');
					$new_date=date('Y-m-d', strtotime($date)); 
				?>
				<input hidden type="text" id="todaydate" value="<?php echo $new_date ?>" >
				</div>
				<div class="row">
					<div class="form-group">
                      <label for="inputPassword3" class="col-sm-1 control-label">Select Service :</label>
                      <div class="col-sm-2">
                          <!--<input type="text" class="form-control" id="relation" name="relation" maxlength="30" >-->
                          <!--<label class="select-box-lbl">-->
                              <select class="chosen-select form-control"  name="Profesional_service" id="Profesional_service">
                                <option value="">Professional Service</option>
                                <?php
                                    $selectRecord = "SELECT service_id,service_title FROM sp_services where status='1' ";
                                    $AllRrecord = $db->fetch_all_array($selectRecord);
                                    foreach($AllRrecord as $key=>$valRecords)
                                    {
                                        if($EditedResponseArr['service_title'] == $valRecords['service_title'])
                                            echo '<option value="'.$valRecords['service_id'].'" selected="selected" >'.$valRecords['service_title'].'</option>';
                                        else
                                            echo '<option value="'.$valRecords['service_id'].'">'.$valRecords['service_title'].'</option>';
                                    }
                                ?>
                            </select>
                          <!--</label>-->
                      </div>
                    </div>
					<div class="col-lg-3 marginB20 paddingl0">
                    <input type="button" onclick="return search_current_calls();" value="View Professional Schedule" name="btn-view-schedule" class="btn btn-download" style="background-color:#00cfcb;color:white">
					</div>
				</div>
				<br>
				<div class="row" id="View_Professional_schedule_detail">
               
				<div>
				
          </div>	
		</div>
</body>
</html>
