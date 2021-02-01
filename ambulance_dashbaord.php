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
<link rel="stylesheet" type="text/css" href="css/pinterest-style.css" />
<link rel="stylesheet" href="dropdown/docsupport/prism.css">
<link rel="stylesheet" href="dropdown/chosen.css">  
<link rel="stylesheet" href="js/jRange-master/jquery.range.css">


<style type="text/css" media="all">
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
.input_box
{
  margin-left:-10%
}
.input_box_first
{
  margin-left:-2%
}
</style>  

<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Welcome to SPERO</title>
</head>
<body>
<?php include "include/amb_header.php"; ?>
<section>
<div id="DispatchdDiv" style="background-color:white;">
<div class="container-fluid" style="margin-left:2%;margin-right:2%;border: 2px solid #E8E8E8;border-radius: 8px;">
<div class="row">
<div class="col-lg-12" style="margin-top:2%;">
<!--<label for="inputPassword3" class="col-lg-1 control-label">Location : <span style="color:red;">*</span></label>
<div class="col-lg-3">
        <input maxlength="100" id="google_location" name="google_location" type="text" value="<?php if($recListResponse['locationNm']) echo $recListResponse['locationNm']; else echo $_POST['locationNm']; ?>" class="validate[required] form-control"  />   
        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
</div>-->
<h2 class="page-title">Ambulance Dispatch Form</h2>
<div class="modal-body">
<div  id="Drop_call_view" >
<?php include "amb_drop_call_view.php"; ?>
</div>
<div  id="amb_payment_view" style="display:none">
<?php include "amb_payment_view.php"; ?>
</div>
<!--<a href="javascript:void(0);" title="Dispatch_form" onclick="Dispatch_form()"; data-toggle="tooltip" data-placement="top" title="View Log">
<span aria-hidden="true">Ambulance Dispatch Form</span></a>  -->   
<div>
</div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&libraries=places"></script>

<script>

      $(document).ready(function () 
      {
        $location_input = $("#google_location");
        
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

        $location_input = $("#google_drop_location");
        
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
function cbChange(obj) {
    var cbs = document.getElementsByClassName("check_class");
    for (var i = 0; i < cbs.length; i++) {
        cbs[i].checked = false;
    }
    obj.checked = true;

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

    }
 
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
</div>
</section>
</body>
</html>