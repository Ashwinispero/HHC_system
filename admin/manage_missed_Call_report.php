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
    <title>Manage Missed call</title>
    <?php include "include/css-includes.php";?>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php  include "include/header.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <img src="images/locations_big.png" alt="Manage Payments">Manage Missed Call Report            
                            <!--<a href="javascript:void(0);" onclick="return vw_add_location(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD LOCATION</a>-->
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" style="width:96%;">
                            <input class="data-entry-search datepicker_from" placeholder="From Date" type="text" name="formDate_missed" id="formDate_missed" value="" >                      
                        </div>
                    </div>
                    <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" style="width:96%;">                            
                            <input class="data-entry-search datepicker_to" placeholder="To Date" type="text" name="toDate_missed" id="toDate_missed" value="" >                           
                        </div>
                    </div>   
                    				
                   
				    <div class="col-lg-3 marginB20 paddingl0">
                             <input type="button" onclick="return search_missed_record();" value="View List" name="btn-view-schedule" class="btn btn-download">
                     </div>
					<div>
                    <!--<div class="pull-right paddingLR0" style="padding-left:15px !important;">
                        <?php //if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') { echo '<a href="manage_locations_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; }
                        ?>
                    </div>-->
                    <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <!--<a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_include_export_receipt.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;">-->
						<a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="return serch_receipt_dw();">
                            <img src="images/icon-download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>
					
					
					<div id="Missed_Call">
                    <div class="clearfix"></div>
                    <div class="LocationsListing">
					<div>Please Select Date First...</div>
                       <!-- <?php //include "include_export_receipt.php";?> -->
                    </div>
                </div>
              </div>
               <!-- Main Content End-->
            </div>
            <!-- /.container-fluid -->
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
    
    <!-- ------------- Timepicker ------------ -->   
<script type="text/javascript" src="../js/jquery-timepicker-master/jquery.timepicker.js"></script>
<link rel="stylesheet" type="text/css" href="../js/jquery-timepicker-master/jquery.timepicker.css" />
<script type="text/javascript" src="../js/jquery-timepicker-master/datepair.js"></script>
<script type="text/javascript" src="../js/jquery-timepicker-master/jquery.datepair.js"></script>

<!--<link rel="stylesheet" href="../js/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="../js/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="../js/bootstrap-multiselect-master/dist/js/bootstrap-multiselect-collapsible-groups.js"></script>-->

<script type="text/javascript">
    $(document).ready(function() 
    {
        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date(y, m + 1, 0);
        var firstDayPrevMonth = new Date(y,m-1,1);
        $('.datepicker_from').datepicker({ 
        changeMonth: true,
        changeYear: true, 
        dateFormat: 'dd-mm-yy',
        minDate:firstDayPrevMonth,
        maxDate:lastDay,
        onSelect: function(selected)
        {
           $(".datepicker_to").datepicker("option","minDate", selected);     
        },
        onClose: function() 
        { 
            this.focus();
        }

    });

     $(".datepicker_from").keypress(function(event) {event.preventDefault();});

        $('.datepicker_to').datepicker({ 
        changeMonth: true,
        changeYear: true, 
        dateFormat: 'dd-mm-yy',
        maxDate:$(".datepicker_from").val()+'1 m',
        onClose: function() 
        { 
            this.focus(); 
            var inputs = $(this).closest('form').find(':input'); inputs.eq( inputs.index(this)+ 1 ).focus();
        }
        }); 
        $(".datepicker_to").keypress(function(event) {event.preventDefault();});
    });
    function datepair()
    {
        $('.ServiceClass').multiselect({
                                        enableFiltering: true, 
                                        enableCaseInsensitiveFiltering: true,
                                        nonSelectedText:'Select Professionals',
                                        numberDisplayed: 2
                                    });

       $('.datepairExample_0 .time').timepicker({
                        'showDuration': true,
                        'timeFormat': 'h:i A'
                    });
                $('.datepairExample_0').keypress(function(event) {event.preventDefault();});                      
                $('.datepairExample_0').datepair();
    }	
	

		
        function search_missed_record()
        {	
            var formDate_missed=document.getElementById('formDate_missed').value;
			var toDate_missed=document.getElementById('toDate_missed').value;
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
                   	document.getElementById("Missed_Call").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST","New_include_export_receipt_old.php?formDate_missed="+formDate_missed+"&toDate_missed="+toDate_missed,true);
			xmlhttp.send();
        }
       
    </script>
</body>
</html>