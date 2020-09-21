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
    <title>Manage Professionals Feedback</title>
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
                            <img src="images/professionals_big.png" alt="Manage Professionals"> Manage No Show Patient(Work Pending)       
                                                       
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    
                    <div class="clearfix"></div>
                    <div class="ProfessionalsListing">
                        <?php // include "include_extend_service_enquiry.php";?>
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
        changePagination('ProfessionalsListing','include_Professional_Feedback.php','','','','');
    }
    function vw_add_professional(value)
    {
        //alert(value);
        var data1="service_professional_id="+value+"&action=vw_add_professional";
        $.ajax({
            url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               Popup_Display_Load();
            },
            success: function (html)
            {
               // alert(html);
               if(value)
               {
                   var ref_type= $(html).find('#detail_id').val();
                   if(ref_type=='1')
                   {
                       $(".cls_prof").show();
                   }
                   else 
                   {
                       $(".cls_prof").hide();
                   }
                   $(".ProfOtherContent").show();
               }
               else 
               {
                   $(".cls_prof").hide();
                   $(".ProfOtherContent").hide();
               }               
               
               $('#edit_professional').modal({backdrop: 'static',keyboard: false});  
               $("#AllAjaxData").html(html);                       
                    // start work on google location on modal - 
                    $location_input = $("#google_work_location");
                    var options = {
                        //types: ['(postal_town)'],
                        componentRestrictions: {country: 'in'}
                    };
                    autocomplete = new google.maps.places.Autocomplete($location_input.get(0), options);    
                    google.maps.event.addListener(autocomplete, 'place_changed', function() {
                        var data = $("#google_work_location").val();
                        console.log('blah');
                      //  show_submit_data(data);
                        return false;
                    });
                    
                    $location_input_home = $("#google_home_location");
                    var options = {
                        //types: ['(postal_town)'],
                        componentRestrictions: {country: 'in'}
                    };
                    autocomplete_home = new google.maps.places.Autocomplete($location_input_home.get(0), options);    
                    google.maps.event.addListener(autocomplete_home, 'place_changed', function() {
                        var datas = $("#google_home_location").val();
                        console.log('blah');
                      //  show_submit_data(data);
                        return false;
                    });
                    
                    // complete google location
        
               setTimeout("$('.scrollbars').ClassyScroll();",100);
               $('#service_id').multiselect({
                                            enableCaseInsensitiveFiltering: true,
                                            enableFiltering: true,
                                            nonSelectedText: 'Select Service',
                                            maxHeight: 250,
                                            buttonWidth:'auto!important'
                                      });
               $(".multiselect-search").keydown(function(event) 
               {
                    if (event.keyCode == 13) 
                    {
                        return false;
                    }
               });

             //  $("#dob").attr( 'readOnly' , 'true' );

               $("#frm_add_professional").validationEngine('attach',{promptPosition : "bottomLeft"}); 
               $('.datepicker').datepicker({changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy',yearRange: "-60:-20",onClose: function() { this.focus(); var inputs = $(this).closest('form').find(':input'); inputs.eq( inputs.index(this)+ 1 ).focus(); }});
               $("#dob").keypress(function(event) {event.preventDefault();});
               
                $('#name,#first_name,#middle_name').keyup(function(event) 
                {
                    var textBox = event.target;
                    var start = textBox.selectionStart;
                    var end = textBox.selectionEnd;
                    textBox.value = textBox.value.charAt(0).toUpperCase() + textBox.value.slice(1);
                    textBox.setSelectionRange(start, end);
                });             
    
            },
            complete : function()
            {
               Popup_Hide_Load();
            }
        });
    }
    function add_professional_submit()
    {
      // check is it atleast one checkbox selected
      if($('#frm_add_professional input[type="checkbox"]').is(':checked') && $("#frm_add_professional").validationEngine('validate'))
      {  
                var addressField = document.getElementById('google_home_location');
                var geocoder = new google.maps.Geocoder();
               geocoder.geocode(
                {'address': addressField.value}, 
                function(results, status) { 
                    if (status == google.maps.GeocoderStatus.OK) 
                    {
                        var loc = results[0].geometry.location;
                        console.log(addressField.value+" found on Google");
                        checkworklocation();
                        //var datas = valid_google_location('yes');
                    } else {
                        console.log(addressField.value+" not found on Google");
                        alert('Please select valid home location.');
                        //var datas = valid_google_location('no');
                        return false;
                    } 
                }
                );
      }
      else 
      {
            $('#submitForm').prop('disabled', false);
            bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
            {
               $("#reference_type").focus();
            }); 
      }  
    }
    function checkworklocation()
    {
        var addressField = document.getElementById('google_work_location');
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode(
        {'address': addressField.value}, 
        function(results, status) { 
            if (status == google.maps.GeocoderStatus.OK) 
            {
                var loc = results[0].geometry.location;
                console.log(addressField.value+" found on Google");
                submitPorfForm();
                //var datas = valid_google_location('yes');
            } else {
                console.log(addressField.value+" not found on Google");
                alert('Please select valid work location.');
                //var datas = valid_google_location('no');
                var found = '2';
                return false;
            } 
        }
        );
    }
    function submitPorfForm()
    {
         $('#submitForm').prop('disabled', true);
            $("#frm_add_professional").ajaxForm({
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
                    if(result=='professionalexists')
                    {
                       bootbox.alert("<div class='msg-error'>Professional details already exists, it may be on trash list, so please try another one.</div>"); 
                    }
                    else 
                    {
                         $('#edit_professional').modal('hide'); 
                         if(result=='InsertSuccess')
                         {
                              bootbox.alert("<div class='msg-success'>Professional details added successfully.</div>",function()
                              {
                                  changePagination('ProfessionalsListing','include_professionals.php','','','','');
                              });
                          }

                         else if(result=='UpdateSuccess')
                         {
                              bootbox.alert("<div class='msg-success'>Professional details updated successfully.</div>",function()
                              {
                                  changePagination('ProfessionalsListing','include_professionals.php','','','','');
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
    function change_status(service_professional_id,curr_status,actionVal)
    { 
       var prompt_msg="";
       var success_msg=""; 
       if(actionVal=='Active')
       {
           prompt_msg ="Are you sure you want to activate this professional ?"; 
           success_msg="activated";  
       }
       else if(actionVal=='Inactive')
       {
           prompt_msg ="Are you sure you want to inactive this professional ?"; 
           success_msg="deactivated";  
       }
       else if(actionVal=='Delete')
       {
           prompt_msg="Are you sure you want to delete this professional ?";
           success_msg="deleted";
       }
       bootbox.confirm(prompt_msg, function (res) 
       {
           if(res==true)
           {
               var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&service_professional_id="+service_professional_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
             //  alert(data1);
               $.ajax({
                   url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
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
                          bootbox.alert("<div class='msg-success'>Professional "+success_msg+" successfully.</div>",function()
                          {
                               changePagination('ProfessionalsListing','include_professionals.php','','','','');
                          });  
                      }
                      else
                          bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                   },
                  complete : function()
                  {
                     Hide_Load();
                  }
               });
           }
       });   
    }
    function view_detail_payment(service_professional_id)
    {
        var data1="service_professional_id="+service_professional_id+"&action=vw_professional_with_payment_list";
        //alert(data1);
         $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                   // alert(html);
                    $('#edit_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
         }); 
    }
    function getProfOtherDtls(reference_Id)
    {
        var ref_id=reference_Id;
        if(ref_id)
        {
           $(".ProfOtherContent").show();
           if(ref_id=='1')
               $(".cls_prof").show();
           else 
              $(".cls_prof").hide(); 
        }
        else 
        {
           $(".ProfOtherContent").hide();
           $(".cls_prof").hide();
        }

    }
    function chkEmails()
    {
        if($("#email_id").val() && $("#work_email_id").val())
        {
            var email_id=$("#email_id").val();
            var work_email_id=$("#work_email_id").val();

            if(email_id==work_email_id)
            {
               $("#work_email_id").val('');
               $("#work_email_id").focus();
               $("#form_error").show();
               $("#form_error").text("Work email address is same as email address please choose another one !");
               $('#form_error').delay(6000).fadeOut('slow');
            }
            else
                return false;
        }
        else
            return false;
    }
    function viewScheduled(service_professional_id)
    {
        var data1="service_professional_id="+service_professional_id+"&action=viewScheduleProf";
        //alert(data1);
         $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                   // alert(html);
                    $('#edit_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);
                    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
                    var firstDay = new Date(y, m, 1);
                    var lastDay = new Date(y, m + 1, 0);
                    var firstDayPrevMonth = new Date(y,m-1,1);

                    $('.datepicker_from').datepicker({ 
                        changeMonth: true,
                        changeYear: true, 
                        dateFormat: 'dd-mm-yy',
                       /* minDate:firstDayPrevMonth,
                        maxDate:lastDay,
                        onSelect: function(selected)
                        {
                           $(".datepicker_to").datepicker("option","minDate", selected);     
                        },
                        onClose: function() 
                        { 
                            this.focus();
                        }*/
                    });

                    //$(".datepicker_from").keypress(function(event) {event.preventDefault();});

                    $('.datepicker_to').datepicker({ 
                        changeMonth: true,
                        changeYear: true, 
                        dateFormat: 'dd-mm-yy',
                       /* maxDate:$(".datepicker_from").val()+'1 m',
                        onClose: function() 
                        { 
                            this.focus(); 
                            var inputs = $(this).closest('form').find(':input'); inputs.eq( inputs.index(this)+ 1 ).focus();
                        }*/
                    });

                    //$(".datepicker_to").keypress(function(event) {event.preventDefault();});
                    datepair();
                },
                complete : function()
                {
                    Hide_Load();
                }
        }); 
    }   
    function searchScheduleRec()
    {
       var fromdate = $("#formDate").val();
       var toDate = $("#toDate").val();
       var profeID = $("#profeID").val();
       if(fromdate == '')
       {
           bootbox.alert("<div class='msg-error'>Please select from date</div>");
           return false;
       }
       if(toDate == '')
       {
           bootbox.alert("<div class='msg-error'>Please select to date</div>");
           return false;
       }
       if(fromdate && toDate)
       {
            var data1 = "fromdate="+fromdate+"&toDate="+toDate+"&profID="+profeID+"&action=Edit_deleteScheduled";
            //alert(data1);
            $.ajax({
            url: "viewWordScheduled.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               Display_Load();
            },
            success: function (html)
            {
               var result=html.trim();
               // alert(result);
               $(".ScheduledListing").html(result);                   
                datepair();                   
            },
            complete : function()
            {
                Hide_Load();
            }
            });
        }
   }
    function datepair()
    {
        $('.ServiceClass').multiselect({
                                        nonSelectedText:'Select Professionals'
                                    });

       $('.datepairExample_0 .time').timepicker({
                        'showDuration': true,
                        'timeFormat': 'h:i A'
                    });
        $('.datepairExample_0').keypress(function(event) {event.preventDefault();});                      
        $('.datepairExample_0').datepair();
    }
    function scheduleSubForm()
    {
        $("#scheduleform").ajaxForm({
            beforeSend: function() 
            {
                Display_Load();
            },
           success: function (html)
           {
               //alert(html);
               searchScheduleRec();
           },
            complete : function()
            {
              Hide_Load();
            }
        }).submit();
    }
    function deleteScheduled(scheduled_id)
    {
        var prompt_msg="";
        var success_msg="";
        
        prompt_msg="Are you sure you want to delete this record ?";
        success_msg="deleted";
        bootbox.confirm(prompt_msg, function (res) 
        {
            if(res==true)
            {
                var data1 = "scheduled_id="+scheduled_id+"&action=deleteDatOfSchedule";
                //alert(data1);
                $.ajax({
                    url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       var result=html.trim();
                        //alert(result);
                       if(result=='success')
                       {
                           bootbox.alert("<div class='msg-success'>Professional "+success_msg+" successfully.</div>",function()
                           {
                               searchScheduleRec();
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
    /*
    * Import Excel file
    */
    function ImportExcel()
    { 
       // var _URL = window.URL || window.webkitURL;
        var data1="action=ImportExcel";
        $.ajax({
            url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               Display_Load();
            },
            success: function (html)
            {
               $('#edit_professional').modal({backdrop: 'static',keyboard: false});                     
               $("#AllAjaxData").html(html);

            },
            complete : function()
            {
              Hide_Load();
            }
        });
    }   
    function professionFile_submit()
    {
        //alert('test');
        $("#frm_add_expo").ajaxForm({
        beforeSend: function() 
        {
            Display_Load();
        },
        success: function (html)
        {
            var res = html.trim();
           // alert(res);
            if(res =='error')
            {
                bootbox.alert("<div class='msg-error'>Please import excel file in sample format.</div>");
            }
            else
            {
               $('#edit_professional').modal('hide'); 
               bootbox.alert("<div class='msg-success'>Professionals records imported successfully.</div>",function()
               {
                    changePagination('ProfessionalsListing','include_professionals.php','','','','');
               });
            }
        },
        complete : function()
        { //0a32c1603455e42222a10e0dbf92cacf
           Hide_Load();
        }
        }).submit(); 
    }
	function SaveodcumentStatus(professional_id,document_list_id)
	{
		var Doc_Status=document.getElementById('Doc_Status').value;
		var flag=1;
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
                   alert('Successfully Update');
				}
			}
			xmlhttp.open("POST","Save_professional_document_status.php?professional_id="+professional_id+"&document_list_id="+document_list_id+"&Doc_Status="+Doc_Status+"&flag=1",true);
			xmlhttp.send();
	}
	function Save_final_document_status(professional_id,document_list_id)
	{
		var flag=2;
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
                   alert('Successfully Update');
				}
			}
			xmlhttp.open("POST","Save_professional_document_status.php?professional_id="+professional_id+"&document_list_id="+document_list_id+"&flag=2",true);
			xmlhttp.send();
	}
	 function Add_Professinal_Bank_details(service_professional_id)
    {
        var data1="service_professional_id="+service_professional_id+"&action=Add_professional_Bank_details";
        //alert(data1);
         $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                   // alert(html);
                    $('#edit_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
         }); 
    }
    function Submit_professional_bank_details(professional_id)
	{
		
		var Account_Name=document.getElementById('Account_Name').value;
		var Bank_Name=document.getElementById('Bank_Name').value;
		var IFSC_Code=document.getElementById('IFSC_Code').value;
		var Account_status=document.getElementById('Account_Type').value;
		var Account_Number=document.getElementById('Account_Number').value;
		var Branch_Name=document.getElementById('Branch_Name').value;
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
                   	alert('Successfully Update');
				}
			}
			xmlhttp.open("POST","Save_professional_bank_details.php?professional_id="+professional_id+"&Account_Name="+Account_Name+"&Bank_Name="+Bank_Name+"&IFSC_Code="+IFSC_Code+"&Account_Type="+Account_status+"&Account_Number="+Account_Number+"&Branch_Name="+Branch_Name,true);
			xmlhttp.send();
	}
</script>

<script>
    function show_submit_data(data) {
        $("#selcGog_Location").val(data);
    }    
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=true"></script>

</body>
</html>