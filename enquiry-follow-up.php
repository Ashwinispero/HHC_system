<?php   
    require_once 'inc_classes.php';
    require_once "emp_authentication.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Enquiry Follow up</title>
    </head>
    <body>
    <?php include "include/header.php"; ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-title">Enquiry Follow up</h2>
                    <div class="col-lg-12 white-bg">         
                        <!-- ---------------- Event Log start ----------- -->
                        <div id="EventLogDiv">
                            <div class="form-inline serch-box">
                                <div class="form-group col-lg-6">
                                  <div class="row">
                                    <div class="input-group col-lg-11"> 
                                        <span class="input-group-addon text-left" style="width:5%;">
                                            <a href="javascript:void(0);"><img onclick="searchRecords();" src="images/search-icon.png" width="22" height="21" alt="Search icon"></a>
                                        </span>
                                        <input type="text" class="form-control searchKeywords" id="SearchKeyword" name="SearchKeyword" aria-describedby="" />
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group col-lg-6">
                                </div>
                            </div>
                            <div class="enquiryFollowUpListing">
                                <?php include 'include_equiry_follow_up.php'; ?>
                            </div>  
                        </div>
                        <!-- ---------------- Event Log End ----------- -->   
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /#wrapper -->
    <div class="modal fade" id="vw_add_follow_up_inquiry">
        <div class="modal-dialog">
          <div class="modal-content" id="followUpAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="vw_cancel_inquiry"> 
        <div class="modal-dialog">
          <div class="modal-content" id="inquiryAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<?php include "include/scripts.php"; ?>
<?php include "include/eventLogscripts.php"; ?>
<script>    
    $(document).ready(function() 
    {
        textboxes = $("input.searchKeywords");
        $(textboxes).keydown (checkForEnterSearch);
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
        changePagination('enquiryFollowUpListing','include_equiry_follow_up.php','','','','');
    }

    function change_notification_status(eventId,followupId)
    {
        if (eventId != '' && followupId != '' && followupId != undefined) {
            var prompt_msgs = "Are you sure you want to mark this enquiry notifcationas read ?";
            bootbox.confirm(prompt_msgs, function (res) 
            {
                if (res == true)
                {
                    followUpEnquiry(eventId, followupId);
                }
            });
        }
    }

    /**
     * Follow up inquiry
     */
    function followUpEnquiry(eventId, followupId)
    {
        if (eventId != '' && eventId != undefined && followupId != '' && followupId !=undefined) {
            var data1="event_id="+eventId+"&follow_up_id="+followupId+"&action=vw_add_follow_up_inquiry";
            //alert(data1);
            $.ajax({
                url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                    var result=html.trim();
                    //alert(html);
                    $('#vw_add_follow_up_inquiry').modal({backdrop: 'static',keyboard: false}); 
                    $("#followUpAjaxData").html(result);
                    // Init datepicker
                    $('.followup_Datepicker').datepicker({
                        changeMonth: true,
                        changeYear: true, 
                        dateFormat: 'dd-mm-yy',
                        minDate: 0
                    });

                    $(".followup_Datepicker").keypress(function(event) {event.preventDefault();});

                    //Init timepicker
                    $(".followup_time").timepicker({
                        'showDuration': true,
                        'timeFormat': 'h:i A'
                    });
                    $("#frmAddFollowUp").validationEngine('attach',{promptPosition : "bottomLeft"});
                },
                complete : function()
                {
                    Hide_Load();
                }
            });
        }
    }
    /**
    * enquiry FollowUp Submit
    */
    function enquiryFollowUpSubmit()
    {
        if ($("#frmAddFollowUp").validationEngine('validate')) {
            $("#frmAddFollowUp").ajaxForm({
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                    var result = html.trim();
                    $("#follow_up_date").val('');
                    $("#follow_up_time").val('');
                    $("#follow_up_desc").val('');
                    $('#vw_add_follow_up_inquiry').modal('hide');
                    bootbox.alert('<div class="msg-success"> Enquiry follow up details added successfully.</div>', function() {
                        var existFollowUpId = $("#exist_follow_up_id").val();
                        //console.log("existFollowUpId", existFollowUpId);
                        var res = result.split("HtmlSeperator");
                        //console.log("res",res);
                        if (res[1]) {
                            var resString = res[1].split("_");
                            //console.log("resString", resString);
                            if (resString[0] && resString[1]) {
                                var eventId = resString[0];
                                var followupId = resString[1];
                                //console.log("eventId", eventId);
                                //console.log("followupId", followupId);
                                changeNotificationStatus(eventId, existFollowUpId);
                            }
                        }
                    });
                },
                complete : function()
                {
                    Hide_Load();
                }
            }).submit();  
        }
    }

    /**
    * This function is used for change notification status
    */
    function changeNotificationStatus(eventId, followupId)
    {
        if (eventId && followupId) {
            var data1="event_id="+eventId+"&follow_up_id="+followupId+"&action=change_notification_status";
            $.ajax({
                url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                    var result = html.trim();

                    if (result == "missingParameter") {
                        bootbox.alert('<div class="msg-error">follow up id is missing.</div>');
                    } else if (result == "recordNotFound") {
                        bootbox.alert('<div class="msg-error">No record found.</div>');
                    } else if (result == "error") {
                        bootbox.alert('<div class="msg-error">error in change notification status.</div>');
                    } else if (result == "success") {
                        bootbox.alert('<div class="msg-success">Notification marked as read succesfully.</div>', function() {
                            location.reload();
                        });
                    }
                },
                complete : function()
                {
                Hide_Load();
                }
            });
        }
    }

    /**
     * This function is used for cancel inquiry request
    */
   function cancelEnquiry(eventId)
   {
        if (eventId != '' && eventId != undefined) {
            var prompt_msgs = "Are you sure you want to cancel this enquiry ?";
            bootbox.confirm(prompt_msgs, function (res) 
            {
                if (res == true)
                {
                    var data1="event_id="+eventId+"&action=vw_cancel_inquiry";
                    //alert(data1);
                    $.ajax({
                        url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                            Display_Load();
                        },
                        success: function (html)
                        {
                            var result=html.trim();
                            //alert(html);
                            $('#vw_cancel_inquiry').modal({backdrop: 'static',keyboard: false}); 
                            $("#inquiryAjaxData").html(result);
                            $("#frmCancelEnquiry").validationEngine('attach',{promptPosition : "bottomLeft"});
                        },
                        complete : function()
                        {
                        Hide_Load();
                        }
                    });
                }
            });
        }
   }

   /**
     * This function is used for convert enquiry into event
    */

    function convertEnquiryIntoService(eventId)
    {
        if (eventId != '' && eventId != undefined) {
            var prompt_msgs = "Are you sure you want to convert this enquiry into service ?";
            bootbox.confirm(prompt_msgs, function (res) 
            {
                if (res == true)
                {
                    var data1="event_id="+eventId+"&action=convertEnquiryIntoService";
                    $.ajax({
                        url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                            Display_Load();
                        },
                        success: function (html)
                        {
                            var result = html.trim();
                            if (result == 'invalidEvent') {
                                bootbox.alert("<div class='msg-error'>Event not found.</div>");
                            } else if (result == 'error') {
                                bootbox.alert("<div class='msg-error'>Error while convert enquiry into service.</div>");
                            } else if (result == 'success') {
                                bootbox.alert("<div class='msg-success'>Enquiry converted into service successfully.</div>",function(){
                                    window.location.href = '<?php echo $siteURL;?>event-log.php';
                                }); 
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
    }

    /**
    * 
    */
    function cancelEnquirySubmit()
    {
        if ($("#frmCancelEnquiry").validationEngine('validate')) {
            $("#frmCancelEnquiry").ajaxForm({
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                    var result=html.trim();
                    $("#enquiry_cancel_from").val('');
                    $("#cancellation_reason").val('');
                    $('#vw_cancel_inquiry').modal('hide'); 
                    bootbox.alert('<div class="msg-success"> Enquiry cancellation details added successfully.</div>', function() {
                        $('#vw_professional').modal('hide');
                        window.location='event-log.php';
                    });
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