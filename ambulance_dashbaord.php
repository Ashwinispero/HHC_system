<?php   
require_once 'inc_classes.php';
        require_once "emp_authentication.php";
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";
        require_once 'classes/eventClass.php';
        require_once 'classes/config.php';
        $eventClass=new eventClass();
        require_once 'classes/commonClass.php';
        $commonClass=new commonClass();
        require_once 'classes/employeesClass.php';
        $employeesClass=new employeesClass();
        require_once 'classes/professionalsClass.php';
        $professionalsClass=new professionalsClass();     
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php include "include/scripts.php"; ?>
<?php include "include/eventLogscripts.php"; ?>
<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>

<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
<link rel="stylesheet" type="text/css" href="css/pinterest-style.css" />
<link rel="stylesheet" href="dropdown/docsupport/prism.css">
<link rel="stylesheet" href="dropdown/chosen.css">  
<link rel="stylesheet" href="js/jRange-master/jquery.range.css">

<script type="text/javascript"> var base_url = 'http://localhost/HHC_system/';</script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<!----Map JS--->
<link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
<script type="text/javascript" src="js/inc_map_here.js"></script>
<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
<script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>

<style type="text/css" media="all">
.gj-modal .gj-picker-md {
    border: 0;
    top: 20% !important;
}
.gj-picker-md [role=header] {
    background: #00cfcb !important;
}
.gj-picker-md [role=switch] {
    background: #00cfcb !important;
    }
    /* fix rtl for demo */
    .chosen-rtl .chosen-drop { left: -9000px; }
    #calendar { max-width: 900px; margin: 0 auto; }

  
.notification {
  background-color: #555;
  color: white;
  text-decoration: none;
  padding: 15px 26px;
  position: relative;
  display: inline-block;
  border-radius: 2px;
}


.notification .badge {
  position: absolute;
  
  right: -10px;
  padding: 5px 10px;
  border-radius: 50%;
  color: white;
  background-color: red;
}

</style>  

<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Welcome to SPERO</title>
</head>
<body>
<?php include "include/amb_header.php"; ?>
<section>

<div id="DispatchdDiv" class="container-fluid" style="background-color:white;margin-left:1%;margin-right:1%;border: 1px solid #23131357;border-radius: 8px;margin-top:-1.5%">
<div class="row">
<div class="modal-body">
<div  id="Drop_call_view" >
<?php include "amb_drop_call_view.php"; ?>
</div>
<div  id="amb_payment_view" style="display:none">
<?php include "amb_payment_view.php"; ?>
</div>
</div>

<script>

      $(document).ready(function () 
      {
        /*$location_input = $("#google_location");
        
        var options = {
            //types: ['(postal_town)'],
            componentRestrictions: {country: 'in'}
        };
        autocomplete = new google.maps.places.Autocomplete($location_input.get(0), options);    
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var data = $("#google_location").val();
            //console.log('blah')
            show_submit_data(data);
            return false;
        });
*/
        /*
        $location_input = $("#google_pickup_location");
        
        var options = {
            //types: ['(postal_town)'],
            componentRestrictions: {country: 'in'}
        };
        autocomplete = new google.maps.places.Autocomplete($location_input.get(0), options);    
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var data = $("#google_pickup_location").val();
            //console.log('blah')
            show_submit_data(data);
            return false;
        }); 
        */
       /* $location_input = $("#google_drop_location");
        
        var options = {
            //types: ['(postal_town)'],
            componentRestrictions: {country: 'in'}
        };
        autocomplete = new google.maps.places.Autocomplete($location_input.get(0), options);    
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var data = $("#google_drop_location").val();
            //console.log('blah')
            show_submit_data(data);
            return false;
        });
        */
         $(".number").keydown(function (e) 
         {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                 // Allow: Ctrl+C
                (e.keyCode == 67 && e.ctrlKey === true) ||
                 // Allow: Ctrl+X
                (e.keyCode == 88 && e.ctrlKey === true) ||
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) 
            {
                e.preventDefault();
            }
        });
       
        /*
        
        $('.callerPhone').bind('cut copy paste', function (e) 
        {
            e.preventDefault(); //disable cut,copy,paste
        });
        
        */
    });

    function show_submit_data(data) {
        $("#selcGog_Location").val(data);
    }
    
    $(document).ready(function() 
    {
        initIncidentMap();
        map_autocomplete_drop();
        map_autocomplete_patient();
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
</script>
<script type="text/javascript"> 
function SubmitDropCall(){
 //Display_Load();
 var purpose_id = $("#CallType").val();

 var terminatevalue = $("#terminatevalue_old").val();
 //alert(terminatevalue);
 if(terminatevalue == 'yes'){
    document.getElementById("terminate_reason_id").value = $("#terminate_reason_id_old").val();
    document.getElementById("notes_terminate").value = $("#notes_terminate_old").val();
    document.getElementById("terminatevalue").value = $("#terminatevalue_old").val();
 }
 
 var submit = 'yes';
 if(purpose_id==''){
      submit = 'no';
      bootbox.alert("<div class='msg-error'>Please select purpose of call</div>");
      //alert('Please enter caller details.');
      return false;
 }
 else if(purpose_id == '1')
  {
            if($("#phone_no").val() == '' )
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller phone number.</div>");
                return false;
            }
            if($("#name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller name.</div>");
                return false;
            }
            if($("#name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller name.</div>");
                return false;
            }
            if($("#name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller name.</div>");
                return false;
            }
            if($("#name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller last name.</div>");
                return false;
            }
            if($("#caller_first_name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller first name.</div>");
                return false;
            }
            if($("#Complaint_type").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please select Chief Complaint.</div>");
                return false;
            }
            if($("#Patient_name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient name.</div>");
                return false;
            }
            if($("#Patient_first_name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient first name.</div>");
                return false;
            }
            if($("#Patient_phone_no").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient phone no .</div>");
                return false;
            }
            if($("#Age").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient age.</div>");
                return false;
            }
            if($("#Gender").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient gender.</div>");
                return false;
            }
            if($("#google_location").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient address.</div>");
                return false;
            }
            if($("#google_pickup_location").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter pickup address.</div>");
                return false;
            }
            if($("#google_drop_location").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter drop location.</div>");
                return false;
            }
            if($("#selected_amb").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please select ambulance.</div>");
                return false;
            }
            if($("#notes").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter notes.</div>");
                return false;
            }
            if($("#total_km").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Total KM Feild Blank.</div>");
                return false;
            }
            if($("#total_cost").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Total Cost Feild Blank.</div>");
                return false;
            }

  } 
  if(submit == 'yes')
  {
 $("#DropForm").ajaxForm({
                beforeSend: function() 
                {
                     Popup_Display_Load();
                }, 
               success: function (html)
               {
                    var htmls=html.trim();
                    if (html.indexOf('SessionExpired') > -1) 
                    {
                        bootbox.alert("<div class='msg-error'>Your Session has been expired please try again !</div>",function()
                        {
                            window.location='index.php';
                        });
                    }
                    else 
                    {
                        var values = htmls.split(">>"); 
                        var result = values[0];
                        var callerID = values[1];
                       // bootbox.alert("<div class='msg-success'>Details added successfully..</div>");
                        //window.location='ambulance_dashbaord.php';
                        bootbox.alert('<div class="msg-success">Details added successfully..</div>', function() 
                        {
                            window.location='ambulance_dashbaord.php';
                        });
                    }
                    
               },
                complete : function()
                {
                   Popup_Hide_Load();
                }
           }).submit();   
        }
}
function ChangepaymentType(){
    var PaymentType = $("#PaymentType").val();
    if(PaymentType == 2 )
    {
        //Cheque
        $("#cheque").show();
        $("#NEFT").hide();
       $("#card").hide();
    }
    else if(PaymentType == 3 )
    {
        //NEFT
        $("#NEFT").show();
        $("#cheque").hide();
        $("#card").hide();
    }
    else if(PaymentType == 4 )
    {
        //Card
        $("#card").show();
        $("#cheque").hide();
       $("#NEFT").hide();
    }
    else
    {
        //Cash
       $("#cheque").hide();
       $("#NEFT").hide();
       $("#card").hide();
    }
}
function payment_other(){
    var total_cost = parseInt(document.getElementById('total_cost').value);
    var other_cost = parseInt(document.getElementById('other_cost').value);
    var final_cost = total_cost + other_cost;
    document.getElementById('final_cost').value=final_cost;
    
}
function Start_odo_validation(){
    var pre_odo = parseInt(document.getElementById('pre_odo').value);
    var Start_odo = parseInt(document.getElementById('Start_odo').value);
    if(Start_odo > pre_odo)
    {
        bootbox.alert("<div class='msg-error'>Please Enter less than Previous odometer</div>");
         document.getElementById('Start_odo').value='';
         document.getElementById("Start_odo").focus();
    }
    else if(Start_odo < pre_odo)
    {
        bootbox.alert("<div class='msg-error'>Please Enter greater than Previous odometer</div>");
        document.getElementById('Start_odo').value='';
        document.getElementById("Start_odo").focus();
    }
}
function end_odo_validation()
{
    var End_odo = parseInt(document.getElementById('End_odo').value);
    var Start_odo = parseInt(document.getElementById('Start_odo').value);
    if(Start_odo > End_odo)
    {
        bootbox.alert("<div class='msg-error'>Please Enter less than start odometer</div>");
         document.getElementById('End_odo').value='';
         document.getElementById("End_odo").focus();
    }
    
}
function cbChange(obj) {
    var cbs = document.getElementsByClassName("check_class");
    for (var i = 0; i < cbs.length; i++) {
        cbs[i].checked = false;
    }
    obj.checked = true;
    
    var selected_amb =$('#Selected_ambulance').val();
    
    var google_pickup_location = $("#google_pickup_location").val();
    var google_drop_location = $("#google_drop_location").val();

    var lat_pick = $("#lat_pick").val();
    var lng_pick = $("#lng_pick").val();

    var lat_drp = $("#lat_drp").val();
    var lng_drp = $("#lng_drp").val();

    var submit = 'yes';
    if($("#Selected_ambulance").val() == '' )
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select ambulance number.</div>");
        obj.checked = false;
        return false;
    }
    if($("#google_pickup_location").val() == '' )
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please enter pickup address.</div>");
        obj.checked = false;
        return false;
    }
    if($("#google_drop_location").val() == '' )
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please enter drop address.</div>");
        obj.checked = false;
        return false;
    }
    if(submit = 'yes'){
        var data1="&selected_amb="+selected_amb+"&google_pickup_location="+google_pickup_location+"&google_drop_location="+google_drop_location+"&lat_pick="+lat_pick+"&lng_pick="+lng_pick+"&lat_drp="+lat_drp+"&lng_drp="+lng_drp+"&action=vw_payment";
            $.ajax({
                    url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        $("#payment_details").html(html);
                        //update_ambulance_inc_map();
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
    }
}
/*
function ViewPaymentDetails(){
    //var selected_amb = $(".check_class").val();
    
    var selected_amb =$('.check_class:checked').val();
    var google_pickup_location = $("#google_pickup_location").val();
    var google_drop_location = $("#google_drop_location").val();
    var submit = 'yes';
    if($("#selected_amb").val() == '' )
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select ambulance number.</div>");
        return false;
    }
    if($("#google_pickup_location").val() == '' )
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please enter pickup address.</div>");
        return false;
    }
    if($("#google_drop_location").val() == '' )
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please enter drop address.</div>");
        return false;
    }
    if(submit = 'yes'){
        var data1="&selected_amb="+selected_amb+"&google_pickup_location="+google_pickup_location+"&google_drop_location="+google_drop_location+"&action=vw_payment";
            $.ajax({
                    url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                      $("#payment_details").html(html);
                       
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
    }
}*/
function changeambulance(amb_no){
  var data1="amb_no="+amb_no+"&action=vw_ambulance_list";
            $.ajax({
                    url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                      $("#ambulance_list").html(html);
                       
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
}
function ChangeCallType(CallType){
  if(CallType == 1 )
  {
   $("#Drop_call_view").show();
   $("#amb_payment_view").hide();
   
  }
  else if(CallType == 2 )
  {
    $("#Drop_call_view").hide();
    $("#amb_payment_view").show();
  }
}
function Terminatecall(){
    var purpose_id = $("#CallType").val();
 var submit = 'yes'; 
 if(purpose_id==''){
      submit = 'no';
      bootbox.alert("<div class='msg-error'>Please select purpose of call</div>");
      //alert('Please enter caller details.');
      return false;
 }
 else if(purpose_id == '1')
  {
            if($("#phone_no").val() == '' )
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller phone number.</div>");
                return false;
            }
            if($("#name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller name.</div>");
                return false;
            }
            if($("#name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller name.</div>");
                return false;
            }
            if($("#name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller name.</div>");
                return false;
            }
            if($("#name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller last name.</div>");
                return false;
            }
            if($("#caller_first_name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter caller first name.</div>");
                return false;
            }
            if($("#Complaint_type").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please select Chief Complaint.</div>");
                return false;
            }
            if($("#Patient_name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient name.</div>");
                return false;
            }
            if($("#Patient_first_name").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient first name.</div>");
                return false;
            }
            if($("#Patient_phone_no").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient phone no .</div>");
                return false;
            }
            if($("#Age").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient age.</div>");
                return false;
            }
            if($("#Gender").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient gender.</div>");
                return false;
            }
            if($("#google_location").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter patient address.</div>");
                return false;
            }
            if($("#google_pickup_location").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter pickup address.</div>");
                return false;
            }
            if($("#google_drop_location").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter drop location.</div>");
                return false;
            }
            if($("#selected_amb").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please select ambulance.</div>");
                return false;
            }
            if($("#notes").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please enter notes.</div>");
                return false;
            }
            if($("#total_km").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Total KM Feild Blank.</div>");
                return false;
            }
            if($("#total_cost").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Total Cost Feild Blank.</div>");
                return false;
            }

  } 
  if(submit == 'yes'){

  
    var status = 2 ;
    var data1="status="+status+"&action=vw_terminate_form";
            $.ajax({
                    url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       // alert(html);
                        $('#vw_payment_form').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                        // start work on google location on modal - 
                       
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
}
}
function SubmitJobClosure_form(){
    var submit = 'yes'; 
    if($("#pro_id").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select Provider immpression dropdwon.</div>");
         return false;
    }
    if($("#level_id").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select LOC dropdwon.</div>");
         return false;
    }
    if($("#med_id").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select medicine dropdwon.</div>");
         return false;
    }
    if($("#inv_id").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select Consumables  dropdwon.</div>");
         return false;
    }
    if($("#datepicker_from_base").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select date time ..</div>");
         return false;
    }
    if($("#datepicker_from_pickup").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select date time ..</div>");
         return false;
    }
    if($("#datepicker_to_drop").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select date time ..</div>");
         return false;
    }
    if($("#datepicker_to_base").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select date time .</div>");
         return false;
    }
    if($("#Start_odo").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Enter start odometer.</div>");
         return false;
    }
    if($("#End_odo").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Enter end odometer.</div>");
         return false;
    }
    if(submit == 'yes'){
        $("#ColsureForm").ajaxForm({
                beforeSend: function() 
                {
                     Popup_Display_Load();
                }, 
               success: function (html)
               {
                    var htmls=html.trim();
                    if (html.indexOf('SessionExpired') > -1) 
                    {
                        bootbox.alert("<div class='msg-error'>Your Session has been expired please try again !</div>",function()
                        {
                            window.location='index.php';
                        });
                    }
                    else 
                    {
                        var values = htmls.split(">>"); 
                        var result = values[0];
                        var callerID = values[1];
                        bootbox.alert('<div class="msg-success">Job Details added successfully..</div>', function() 
                        {
                            window.location='ambulance_dashbaord.php';
                        });
                    }
                    
               },
                complete : function()
                {
                   Popup_Hide_Load();
                }
           }).submit();  
    }
}
function Submitpayment_details(event_id){
    
    var submit = 'yes'; 
    if($("#payment_date").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select Date.</div>");
         return false;
    }
    else if($("#PaymentType").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Select Payment Type</div>");
        return false;
    }
    else if($("#amount").val() == '')
    {
        submit = 'no';
        bootbox.alert("<div class='msg-error'>Please Enter Amount</div>");
        return false;
    }
    if(submit == 'yes'){
        $("#PaymentForm").ajaxForm({
                beforeSend: function() 
                {
                     Popup_Display_Load();
                }, 
               success: function (html)
               {
                    var htmls=html.trim();
                    if (html.indexOf('SessionExpired') > -1) 
                    {
                        bootbox.alert("<div class='msg-error'>Your Session has been expired please try again !</div>",function()
                        {
                            window.location='index.php';
                        });
                    }
                    else 
                    {
                        var values = htmls.split(">>"); 
                        var result = values[0];
                        var callerID = values[1];
                        bootbox.alert('<div class="msg-success">Payment Details added successfully..</div>', function() 
                        {
                            window.location='ambulance_dashbaord.php';
                        });
                    }
                    
               },
                complete : function()
                {
                   Popup_Hide_Load();
                }
           }).submit();  
    }
 
}
function SubmitJobClosure(event_code)
{
    var data1="event_code="+event_code+"&action=vw_JobClosure_form";
            $.ajax({
                    url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       // alert(html);
                        $('#vw_payment_form').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                        // start work on google location on modal - 
                       
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
}
function SubmitPayment(event_id)
{
    var data1="event_id="+event_id+"&action=vw_payment_form";
            $.ajax({
                    url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       // alert(html);
                        $('#vw_payment_form').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                        // start work on google location on modal - 
                       
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
}  
</script>
<script>

        $('#time').timepicker();
    </script>
<script src="dropdown/chosen.jquery.js" type="text/javascript"></script>
    <script src="dropdown/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
    var config = {
      '.chosen-select'           : {width:"99%"},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    </script>
  <!-- Modal Popup code start ---> 
  <div class="modal fade" id="vw_payment_form"> 
        <div class="modal-dialog" style="width:1200px !important;">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<?php // include "amb_dashboard.php"; ?>

</div>
</div>
<style>
h4{
    font-size: 15px;
    padding-right: 3px;
    padding-left: 3px;
    
}
.col-lg-2{
    
    position: relative;
    min-height: 1px;
    padding-right: 3px;
    padding-left: 3px;

}
.line-seprator {
    background: #e4e1e1;
    height: 1px;
    margin: 10px 0;
    background : #23131357
}
.col-lg-6{
    
    position: relative;
    min-height: 1px;
    padding-right: 3px;
    padding-left: 3px;

} .col-lg-4{
    
    position: relative;
    min-height: 1px;
    padding-right: 3px;
    padding-left: 3px;

}
.col-lg-1{
    
    position: relative;
    min-height: 1px;
    padding-right: 10px;
    padding-left: 10px;

}
</style>
</section>
</body>
</html>