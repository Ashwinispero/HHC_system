<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
      require_once '../classes/commonClass.php';
      $commonClass=new commonClass();   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Sessions </title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:400px}
    
/* pac container class is for google locaion display on modal.. do not change it  */
.pac-container {
    z-index: 1051 !important;
}
.ui-autocomplete {
    z-index: 1051 !important;
}
    </style>
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
                            <img src="images/professionals_big.png" alt="Manage Professionals"> Manage Sessions                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Professional"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="col-lg-2 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="dp_country" name="Prof_service_id" id="Prof_service_id" onchange="searchRecords();">
                                <option value="">Search By Services</option>
                                <?php
                                    // Getting All Locations
                                    $ServiceList = $commonClass->GetAllServices();  
                                    foreach($ServiceList as $recListKey => $servicesAll)
                                    {
                                        if($_REQUEST['Prof_service_id']==$servicesAll['service_id'])
                                            echo '<option value="'.$servicesAll['service_id'].'" selected="selected">'.$servicesAll['service_title'].'</option>';
                                        else
                                            echo '<option value="' . $servicesAll['service_id'] . '">'.$servicesAll['service_title'].'</option>';
                                    }
                                ?>
                            </select>
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
                    <div class="clearfix"></div>
                    <div class="sessionListing">
                        <?php include "include_sessions.php";?>
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
    <div class="modal fade" id="edit_session"> 
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
        changePagination('sessionListing','include_sessions.php','','','','');
    }

    function view_session(detailedPlanOfCareId)
    {
        var data1="Detailed_plan_of_care_id="+detailedPlanOfCareId+"&action=vw_session_dtls";
        //alert(data1);
         $.ajax({
                url: "session_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                   // alert(html);
                    $('#edit_session').modal({backdrop: 'static',keyboard: false}); 
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
        var data1="Detailed_plan_of_care_id="+value+"&action=vw_change_status";
        $.ajax({
            url: "session_ajax_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               Popup_Display_Load();
            },
            success: function (html)
            {
               $('#edit_session').modal({backdrop: 'static',keyboard: false}); 
               $("#AllAjaxData").html(html);
               setTimeout("$('.scrollbars').ClassyScroll();",100);
               $("#frm_change_session_status").validationEngine('attach',{promptPosition : "bottomLeft"}); 
            },
            complete : function()
            {
               Popup_Hide_Load();
            }
        });
    }
	
	function showReason() {
		var selectedVal = $("#Session_status").val();
		if (selectedVal == '2') {
			$(".confirmationDiv").show();
			$(".paymentContentDiv, .reasonContentDiv").hide();
		} else if (selectedVal == '4' || selectedVal == '5') {
			$(".paymentContentDiv, .confirmationDiv").hide();
			$('input[name="payment_received_status"]').prop('checked', false);
			$(".reasonContentDiv").show();
		} else {
			$("#payment_type, #amount, #reason").val('');
			$('input[name="payment_received_status"]').prop('checked', false);
			$(".reasonContentDiv, .confirmationDiv, .paymentContentDiv").hide();
		}
	}
	
	function change_session_status_submit()
	{
		if($("#frm_change_session").validationEngine('validate')) 
		{
		   $('#submitForm').prop('disabled', true);
		   $("#frm_change_session").ajaxForm({
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
						 $('#edit_session').modal('hide');
						 if (result == 'Success')
						 {
							  bootbox.alert("<div class='msg-success'>Session status changed successfully.</div>",function()
							  {
								  changePagination('sessionListing','include_sessions.php','','','','');   
							  });
						 } else {
							 bootbox.alert("<div class='msg-error'>Error in change session status.</div>"); 
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
	
	function handleClick(myRadio)
	{
		$("#reason").val('');
		if (myRadio.value == 1) {
			$(".reasonContentDiv").hide();
			$(".paymentContentDiv").show();
		} else {
			$(".paymentContentDiv").hide();
			$(".reasonContentDiv").show();
		}
	}
</script>
</body>
</html>