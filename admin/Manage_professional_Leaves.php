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
    <title>Manage Professionals Leaves</title>
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
                            <img src="images/professionals_big.png" alt="Manage Professionals"> Manage Professionals Leaves          
                                                       
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
                            <select class="dp_country" name="reference_type" id="reference_type" onchange="searchRecords();">
                                <option value=""<?php if($_REQUEST['reference_type']=='') { echo 'selected="selected"'; } ?>>Search By Professional</option>
                                 <option value="1"<?php if($_REQUEST['reference_type']=='1') { echo 'selected="selected"'; } ?>>Professional</option>
                                 <option value="2"<?php if($_REQUEST['reference_type']=='2') { echo 'selected="selected"'; } ?>>Vender</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="dp_country" name="Prof_service_id" id="Prof_service_id" onchange="searchRecords();">
                                <option value="">Search By Services</option>
                                <?php
                                    // Getting All Locations
                                    $ServiceList=$commonClass->GetAllServices();  
                                    foreach($ServiceList as $recListKey => $servicesAll)
                                    {
                                        if($_REQUEST['Prof_service_id']==$servicesAll['service_id'])
                                            echo '<option value="'.$servicesAll['service_id'].'" selected="selected">'.$servicesAll['service_title'].'</option>';
                                        else
                                            echo '<option value="'.$servicesAll['service_id'].'">'.$servicesAll['service_title'].'</option>';
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

                    <div class="clearfix"></div>
                    
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="searchBox">
                            <input type="text" class="data-entry-search datepicker_to"  id="event_to_date" name="event_to_date" placeholder="To" onChange="searchRecords();"> 
                        </div>
                    </div>
                   
                    <div class="clearfix"></div>
                    <div class="ProfessionalsLeaveListing">
                        <?php include "include_Professional_LeaveStatus.php"; ?>
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
    <div class="modal fade" id="edit_professional"> 
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
        changePagination('ProfessionalsLeaveListing','include_Professional_LeaveStatus.php','','','','');
    }
 
    function view_professional_Leave_dates(service_professional_id)
    {
        var data1="service_professional_id="+service_professional_id+"&action=vw_professional_leave_dtls";
         $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                    $('#edit_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);
					setTimeout("$('.scrollbars').ClassyScroll();",100);
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
         }); 
    }
	
	function changeLeaveStatus(profId, recordId) {
		if(profId && recordId) {
			var selectedRecordVal = $("#leave_status_" + recordId).val();
			if (selectedRecordVal == 4) {
				$("#RejectionReasonDiv_" + recordId).attr("style", "display:block;");
				$("#btn_update_" + recordId).attr("disabled", true);
			} else {
				$("#rejection_reason_" + recordId).val('');
				$("#RejectionReasonDiv_" + recordId).attr("style", "display:none;");
				$("#btn_update_" + recordId).removeAttr("disabled");
				
			}
		}
	}
	
	function keyDownFunction(event, recordId) {
		var minLength = 10;
	    var maxLength = 99;
		var selectedRecordVal = $("#rejection_reason_" + recordId).val();
		var textLength = ($("#rejection_reason_" + recordId).val()).length;
		if (textLength <= maxLength) {
			if (textLength >= minLength) {
				$("#btn_update_" + recordId).removeAttr("disabled");
			} else {
				$("#btn_update_" + recordId).attr("disabled", true);
			}
			
			if (!$(".leaveNotiMsg").is(':visible')) {
				$(".leaveNotiMsg").removeAttr("style", "display:none;");
				$(".leaveNotiMsg").attr("style", "display:block;");
			}
			$(".leaveNotiMsg").text("Characters left: " + (maxLength - textLength));
		} else {
			event.preventDefault(); 
		}
	}

	function updateLeaveStatus(profId, recordId)
	{
		var leaveStatus = $("#leave_status_" + recordId).val();
		var rejectReason = '';
		if (leaveStatus != '' && leaveStatus != undefined) {
			rejectReason = $("#rejection_reason_" + recordId).val();
		}
		var data1 = "service_professional_id="+profId+"&professional_weekoff_id="+recordId+"&leave_status="+leaveStatus+"&rejection_reason="+rejectReason+"&action=update_leave_status";
		$.ajax({
			url: "professional_ajax_process.php", type: "post", data: data1, cache: false, async: false,
			beforeSend: function() 
			{
				Popup_Display_Load();
			},
			success: function (html)
			{
				var result = html.trim();
				if (result == 'UpdateSuccess') {
					bootbox.alert("<div class='msg-success'>Leave status updated successfully.</div>");
					var selectedDocVal = $("#doc_Status_" + recordId).val();
					if (selectedDocVal == '1') {
						$("#leave_Status_" + recordId).attr("disabled", true);
						$("#btn_update_" + recordId).attr("disabled", true);
					}
					setTimeout("$('.scrollbars').ClassyScroll();",100);
					
				} else if (result == 'NotificationError') {
					bootbox.alert("<div class='msg-error'>Error in send push notification.</div>");
				} else if (result == 'Error') {
					bootbox.alert("<div class='msg-error'>Error in update leave status.</div>");
				}
			},
			complete : function()
			{
				Popup_Hide_Load();
			}
		});	
	}
</script>
</body>
</html>