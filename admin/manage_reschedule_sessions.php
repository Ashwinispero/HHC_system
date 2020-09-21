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
    <title>Manage Reschedule Sessions </title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:400px}</style>
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
                            <img src="images/system_user_big.png"> Manage Reschedule Sessions                   
							<!--<a href="javascript:void(0);" onclick="return vw_add_reschedule_session(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD RESCHEDULE SESSION</a>-->
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Reschedule Sessions"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="searchBox">
                            <input type="text" class="data-entry-search datepicker_from"  id="event_from_date" name="event_from_date" placeholder="From" onChange="searchRecords();">  
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="searchBox">
                            <input type="text" class="data-entry-search datepicker_to"  id="event_to_date" name="event_to_date" placeholder="To" onChange="searchRecords();"> 
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                    </div>
                    <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_reschedule_session_list.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;" >
                            <img src="images/icon-download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="rescheduleSessionListing">
                        <?php include "include_reschedule_sessions.php"; ?>
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
    <div class="modal fade" id="edit_reschedule_session"> 
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
	<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/jquery.classyscroll.js"></script>
    <link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
    <script src="js/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script>
	<script src="js/development-bundle/ui/jquery.timepicker.min.js"></script>
	<script src="js/development-bundle/ui/jquery.datepair.js"></script>
	
	<script src="js/jquery-timepicker-master/jquery.timepicker.js"></script>
	
    <script type="text/javascript">
        $(document).ready(function() 
        {
            textboxes = $("input.data-entry-search");
            $(textboxes).keydown (checkForEnterSearch);
			
			$.datepicker._generateHTML_Old = $.datepicker._generateHTML; $.datepicker._generateHTML = function(inst)
            {
               res = this._generateHTML_Old(inst); res = res.replace("_hideDatepicker()","_clearDate('#"+inst.id+"')"); return res;
            }

            // Start Date code start here
        $('.datepicker_from').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
            maxDate:new Date(),
            minDate: '-3M',      // minDate: -5,
            onSelect: function() 
            {
                var date1 = $('.datepicker_from').datepicker('getDate');           
                var date = new Date( Date.parse( date1 ) ); 
                date.setDate( date.getDate() + 1 );        
                var newDate = date.toDateString(); 
                newDate = new Date( Date.parse( newDate ) );                      
                $('.datepicker_to').datepicker("option","minDate",newDate);

                // check is it end date selected
                if ($('#event_to_date').val()) {
                    searchRecords();
                } else {
                    bootbox.alert("<div class='msg-error'>Please select end date</div>", function() {
                        $('#event_to_date').focus();
                    });
                }  
            }
        });
        // Start Date code end here

        // end date code start here
        $('.datepicker_to').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
            maxDate: new Date(),
            onSelect: function() 
            {
                searchRecords();
            }
        });
        // end date code ends here
        });
        function checkForEnterSearch (event) 
        {
            if (event.keyCode == 13) 
            {
                searchRecords();
            }
        }
        function searchRecords()
        {
            changePagination('rescheduleSessionListing','include_reschedule_sessions.php','','','','');
        }
        function vw_add_reschedule_session(value)
        {
            var data1="reschedule_session_id="+value+"&action=vw_add_reschedule_session";
            $.ajax({
                url: "reschedule_session_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
                },
                success: function (html)
                {
                   $('#edit_reschedule_session').modal({backdrop: 'static',keyboard: false}); 
                   $("#AllAjaxData").html(html);
                   setTimeout("$('.scrollbars').ClassyScroll();",100);
                   $("#frm_add_reschedule_session").validationEngine('attach',{promptPosition : "bottomLeft"}); 
				   $('.datepicker').datepicker({
					   changeMonth: true,
					   changeYear: true,
					   dateFormat: 'dd-mm-yy',
					   minDate:new Date(),
					   onClose: function() { 
						   this.focus(); 
						   var inputs = $(this).closest('form').find(':input'); inputs.eq( inputs.index(this)+ 1 ).focus(); 
					   }
				   });
                   $("#reschedule_start_date").keypress(function(event) {event.preventDefault();});
				   $("#reschedule_end_date").keypress(function(event) {event.preventDefault();});
				   
				   $('.time').timepicker({
                        'showDuration': true,
                        'timeFormat': 'h:i A'
                    });
					
					$('#reschedule_start_time').keypress(function(event) {event.preventDefault();});                      
					$('#reschedule_end_time').keypress(function(event) {event.preventDefault();});                      
					
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
            });
        }
        function add_reschedule_session_submit()
        {
           if($("#frm_add_reschedule_session").validationEngine('validate')) 
           {
               $('#submitForm').prop('disabled', true);
               $("#frm_add_reschedule_session").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                       // alert(result);
                        if(result=='validationError')
                        {
                             bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                        }
                        if(result=='sessionExists')
                        {
                            $("#email_id").val('');
                            $("#email_id").focus();
                            bootbox.alert("<div class='msg-error'>Session already exists please try another one.</div>");
                        }
                        else
                        {
                             $('#edit_reschedule_session').modal('hide');
                             if (result == 'InsertSuccess')
                             {
                                  bootbox.alert("<div class='msg-success'>Session details added successfully.</div>",function()
                                  {
                                      changePagination('rescheduleSessionListing','include_reschedule_sessions.php','','','','');   
                                  });
                             }
                             if (result == 'UpdateSuccess')
                             {
                                    bootbox.alert("<div class='msg-success'>Session details updated successfully.</div>",function()
                                    {
                                        changePagination('rescheduleSessionListing','include_reschedule_sessions.php','','','','');   
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
                    $("#type").focus();
                });
            }
        }
        function change_status(reschedule_session_id,curr_status,actionVal)
        { 
           var prompt_msg = "";
           var success_msg = ""; 
           if(actionVal == 'Delete')
           {
               prompt_msg = "Are you sure you want to delete this session ?";
               success_msg = "deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&reschedule_session_id="+reschedule_session_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                 //  alert(data1);
                   $.ajax({
                       url: "reschedule_session_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                           Popup_Display_Load();
                        },
                       success: function (html)
                       {
                          var result=html.trim();
                          // alert(result);
                          
                          if(result=='success')
                          {
                              bootbox.alert("<div class='msg-success'>System user "+success_msg+" successfully.</div>",function()
                              {
                                  changePagination('rescheduleSessionListing','include_reschedule_sessions.php','','','','');
                              }); 
                          }
                          else
                          {
                              bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                          }
                       },
                        complete : function()
                        {
                           Popup_Hide_Load();
                        }
                   });
               }
           });   
        }
        function view_reschedule_session(rescheduleSessionId)
        {
            var data1="reschedule_session_id="+rescheduleSessionId+"&action=vw_reschedule_session";
            //alert(data1);
             $.ajax({
                    url: "reschedule_session_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                       Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        //alert(html);
                        $('#edit_reschedule_session').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                    },
                    complete : function()
                    {
                       Popup_Hide_Load();
                    }
             }); 
        }
		
		function vw_change_status(value)
        {
            var data1="reschedule_session_id="+value+"&action=vw_change_status";
            $.ajax({
                url: "reschedule_session_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
                },
                success: function (html)
                {
                   $('#edit_reschedule_session').modal({backdrop: 'static',keyboard: false}); 
                   $("#AllAjaxData").html(html);
                   setTimeout("$('.scrollbars').ClassyScroll();",100);
                   $("#frm_change_reschedule_session_status").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
            });
        }
		
		function change_reschedule_session_submit()
		{
			if($("#frm_change_reschedule_session").validationEngine('validate')) 
            {
               $('#submitForm').prop('disabled', true);
               $("#frm_change_reschedule_session").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                       // alert(result);
                        if(result=='validationError')
                        {
                             bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                        }
                        else
                        {
                             $('#edit_reschedule_session').modal('hide');
                             if (result == 'Success')
                             {
                                  bootbox.alert("<div class='msg-success'>Request approval status changed successfully.</div>",function()
                                  {
                                      changePagination('rescheduleSessionListing','include_reschedule_sessions.php','','','','');   
                                  });
                             } else {
								 bootbox.alert("<div class='msg-error'>Error in request approval status.</div>"); 
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
		}
    </script>
</body>
</html>