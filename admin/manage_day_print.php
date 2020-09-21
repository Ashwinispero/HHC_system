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
    <title>Manage Day Print  </title>
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
                            <img src="images/locations_big.png" alt="Manage Payments">Manage Day Print                
                            <!--<a href="javascript:void(0);" onclick="return vw_add_location(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD LOCATION</a>-->
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" style="width:96%;">
                            <input class="data-entry-search datepicker_from" placeholder="From Date" type="text" name="formDate" id="formDate_dayPrint" value="" >                      
                        </div>
                    </div>
                    <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" style="width:96%;">                            
                            <input class="data-entry-search datepicker_to" placeholder="To Date" type="text" name="toDate" id="toDate_dayPrint" value="" >                           
                        </div>
                    </div>  
					<div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" style="width:96%;">                            
                            <select  name="hospital_name" id="hospital_id" onchange="Hospital_List();" >
                            <?php
								$Query=mysql_query("select * from sp_hospitals ORDER BY hospital_id ASC");
								while($row=mysql_fetch_array($Query))
								{
							?>
							<option value="<?php echo $row['hospital_id'] ;?>" ><?php echo $row['hospital_name'];?> </option>
							<?php
								}
							?>
							<option value="Other">Other</option>
							</select>
                        </div>
                    </div> 					
                   
				    <div class="col-lg-3 marginB20 paddingl0">
                             <input type="button" onclick="return search_dayPrint_record();" value="View Payments" name="btn-view-schedule" class="btn btn-download">
                     </div>
					<div>
                    <!--<div class="pull-right paddingLR0" style="padding-left:15px !important;">
                        <?php //if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') { echo '<a href="manage_locations_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; }
                        ?>
                    </div>-->
                    <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <!--<a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_include_export_receipt.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;">-->
						<a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="return serch_DayPrint_dw();">
                            <img src="images/icon-download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>
					
					
					<div id="Day_print">
                    <div class="clearfix"></div>
                    <div class="LocationsListing">
					<div>Please Select Date First...</div>
                        <?php //include "include_DayPrint.php";?>
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
	

        function checkForEnterSearch (event) 
        {
            if (event.keyCode == 13) 
            {
                searchRecords();
            }
        }
		function serch_DayPrint_dw()
		{
			var formDate_invoice=document.getElementById('formDate_dayPrint').value;
			var toDate_invoice=document.getElementById('toDate_dayPrint').value;
			var hospital_id=document.getElementById('hospital_id').value;
			//var w = window.open('/apex/CompetencyDrillDownPage?testvalue='+drilldownparam, target='_blank')
			window.open('csv_include_dayPrint.php?formDate_invoice='+formDate_invoice+'&toDate_invoice='+toDate_invoice+"&hospital_id="+hospital_id,'_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); 
			return false

		}
        function search_dayPrint_record()
        {	//-------Sort By payment date function-----------
		    //-----Author: ashwini 16-011-2016-----
            //changePagination('LocationsListing','include_payments.php','','','','');
			
			 var formDate_dayPrint=document.getElementById('formDate_dayPrint').value;
			 var toDate_dayPrint=document.getElementById('toDate_dayPrint').value;
			var hospital_id=document.getElementById('hospital_id').value;
			 
			// var event_code=document.getElementById('event_code').value;
			 //alert(event_code);
			// var HHC_NO=document.getElementById('HHC_NO').value;
			//alert(toDate_invoice);
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
                   	document.getElementById("Day_print").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST","include_DayPrint.php?formDate_dayPrint="+formDate_dayPrint+"&toDate_dayPrint="+toDate_dayPrint+"&hospital_id="+hospital_id,true);
			xmlhttp.send();
        }
        function vw_add_location(value)
        {
            Popup_Display_Load();
            var data1="location_id="+value+"&action=vw_add_location";
            $.ajax({
                url: "location_ajax_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                   // alert(html);
                   $('#edit_location').modal('show'); 
                   $("#AllAjaxData").html(html);
                   $("#frm_add_location").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                   Popup_Hide_Load();
                }
            });
        }
        function add_location_submit()
        {
           if($("#frm_add_location").validationEngine('validate')) 
           {
               $('#submitForm').prop('disabled', true);
               $("#frm_add_location").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                   success: function (html)
                   {
                       var result=html.trim();
                      // alert(result);
                      if(result=='ValidationError')
                       {
                          bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>");  
                       }
                       if(result=='locationexists')
                       {
                          bootbox.alert("<div class='msg-error'>Location details already exists, it may be on trash list, so please try another one.</div>"); 
                       }
                       else 
                       {
                            $('#edit_location').modal('hide'); 
                            if(result=='InsertSuccess')
                            {
                                 bootbox.alert("<div class='msg-success'>Location details added successfully.</div>",function()
                                 {
                                     changePagination('LocationsListing','include_payments.php','','','','');
                                 });
                            }
                            else if(result=='UpdateSuccess')
                            {
                                 bootbox.alert("<div class='msg-success'>Location details updated successfully.</div>",function()
                                 {
                                     changePagination('LocationsListing','include_payments.php','','','','');
                                 });
                            }  
                       }
                       $('#submitForm').prop('disabled', false);
                   },
                    complete : function()
                    {
                       Hide_Load();
                    }  
               }).submit();
           }
           else 
           {
                $('#submitForm').prop('disabled', false);
                bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
                {
                    $("#location").focus();
                });
           }
        }
        function change_status(location_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this location ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this location ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this location ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&location_id="+location_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                 //  alert(data1);
                   $.ajax({
                       url: "location_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                            Display_Load();
                        },
                        success: function (html)
                        {
                          var result=html.trim();
                          // alert(result);

                          if(result=='success')
                          {
                              bootbox.alert("<div class='msg-success'>Location "+success_msg+" successfully.</div>",function()
                              {
                                  changePagination('LocationsListing','include_payments.php','','','','');
                              });
                          }
                          else
                          {
                              bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                          }
                        },
                        complete : function()
                        {
                           Hide_Load();
                        }
                   });
               }
           });   
        }
    </script>
</body>
</html>