<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
      require_once '../classes/commonClass.php';
      $commonClass=new commonClass();
      $prID = $_REQUEST['prID'];
      $profID = '';
      if($prID)
      {
          $profID = base64_decode($prID);
      }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Add Professionals Schedule</title>
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
                            <img src="images/add-schedule-big.png" alt="Add Professional Schedule"> Add Professional Schedule
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                    <?php
                    if($profID)
                    {
                        $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where service_professional_id = '".$profID."' ";
                        $ptrval = $db->fetch_array($db->query($selectProfessional));
                        echo '<div class="col-lg-6 marginB20 paddingl0" ><span style="color:#00cfcb; font-size:18px;">Professional Name</span> : '.$ptrval['name'].' '.$ptrval['first_name'].' '.$ptrval['middle_name'].'</div>';
                    }
                    ?>
                <div class="col-lg-12 paddingLR20 paddingt20">
                    
                    <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" style="width:96%;">
                            <input class="data-entry-search datepicker_from" placeholder="From Date" type="text" name="formDate" id="formDate" value="" >                           
                        </div>
                    </div>
                    <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" style="width:96%;">                            
                            <input class="data-entry-search datepicker_to" placeholder="To Date" type="text" name="toDate" id="toDate" value="" >                           
                        </div>
                    </div>
                    <div class="col-lg-3 marginB20 paddingl0"> 
                        <!--<a href="javascript:void(0);"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>-->
                        <input type="button" onclick="return searchRecords();" value="Add Schedule" name="btn-add-schedule" class="btn btn-download"> 
                    </div>                    
                    <div class="clearfix"></div>
                    
                    <div>
                        <form name="scheduleform" id="scheduleform" method="post" action="professional_ajax_process.php?action=submitScheduled" >
                            <div class="ScheduledListing">
<!--                        <div class="row">
                        <div style="width:15%; display:inline-block; float:left; vertical-align:top; padding-right:1%;padding:4px;">Date </div>
                       
                                
                                        <div class="pull-left" style="width:15%;display:inline-block;padding-right:2%;padding:4px;">
                                                <label style="display:block;">
                                                        <input value="" placeholder="From Time" name="starttime'.$event_requirement_id.'" id="starttime_0_'.$event_requirement_id.'" type="text" class="form-control time start validate_time" />
                                                </label>
                                        </div>
                                        <div class="pull-left" style="width:15%;display:inline-block;padding-left:2%;padding:4px;">       
                                                <label style="display:block;">
                                                        <input  placeholder="To Time"  value="" name="endtime'.$event_requirement_id.'" id="endtime_0_'.$event_requirement_id.'"  type="text" class="form-control time end validate_time" />
                                                </label>                
                                        </div>                                        
                                
                                                 
                        <div style="width:20%; display:inline-block; vertical-align:top; padding:4px;" class="text-right value select-pro">
                        <label>
                            <select class="validate[required]" style="background:#fff;">
                                <option>Select Professional</option>
                             </select>
                         </label>
                        </div> 
                        <div style="display:inline-block; padding:7px;">
                                <a href="javascript:void(0);" title="Add" onclick="javascript:addMorePlanCare('.$event_requirement_id.');"><img src="images/add.png"></a>
                        </div>
                        <div  style="width:10%;display:inline-block;">
                                <a href="javascript:void(0);" title="Remove" onclick="javascript:deleteMorePlanCare('.$event_requirement_id.');"><img src="images/remove1.png"></a>
                        </div> 
                        </div>-->
                        
                        
                        
                            </div>
                           
                        </form>
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
    function searchRecords()
    {
        var fromdate = $("#formDate").val();
        var toDate = $("#toDate").val();
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
             var data1 = "fromdate="+fromdate+"&toDate="+toDate+"&profID=<?php echo $profID;?>&action=AddScheduled";
             //alert(data1);
             $.ajax({
             url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
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
                    Popup_Hide_Load();
                }
             });
         }
    }  
    function addScheduled(number)
    {
        var i = parseInt(document.getElementById('extras_'+number).value);
        //alert(i);
        if(i==0)
        {
            i=1;
        }
        else
        {
            i= parseInt(i)+1;
        }
        document.getElementById('extras_'+number).value= i;
        var next = parseInt(i)+1;
        var curr_div = "div_"+i+"_"+number;
        //alert(curr_div);
        if(document.getElementById(curr_div).style.display === 'none')
        {
            document.getElementById(curr_div).style.display = 'block';
        }
        else
        {
            var data1="number="+number+"&curr_div="+i;
         // alert(data1);
          $.ajax({
              url: "professional_ajax_process.php?action=AddMorescheduled&profID=<?php echo $profID;?>", type: "post", data: data1, cache: false,async: false,
              beforeSend: function() 
              {
                 Popup_Display_Load();
              },
              success: function (html)
              {
                  //alert(html);
                  document.getElementById(curr_div).innerHTML = html;
                  datepair();
              },
              complete : function()
              {
                   Popup_Hide_Load();
              }
          });               
        }
    }
    function deleteScheduled(number)
    {
        var j=document.getElementById('extras_'+number).value;
        if(j != 0)
        {
           Popup_Display_Load();
           var curr_div = "div_"+j+"_"+number;
           document.getElementById(curr_div).style.display='none';
           previouss= j;
           if(previouss==0)
           {
               previouss=0;
           }
           else
            {
                previouss= parseInt(j)-1;
            }
           document.getElementById('extras_'+number).value=previouss;
           Popup_Hide_Load();
        } 
    }
    function scheduleSubForm()
    {
        var datediff = $("#totaldays").val();
        var Existrecord = 'no';
        if(datediff)
        {
            for(j=0;j<=datediff;j++)
            {
                var starttime = $("#starttime_0_"+j).val();
                var endtime = $("#endtime_0_"+j).val();
                //var fromdates = $("#starttime_0_"+j).val();
                if(starttime != '' && endtime != '')
                {
                    var existprof = $("#existProfId").val();
                    if(existprof == '')
                    {
                        var checkedSubser = 'No';
                        var subsersel = document.getElementById('professional_id_0_'+j);

                        for (var m = 0; m < subsersel.options.length; m++) {
                          if (subsersel.options[m].selected) {
                            checkedSubser = 'Yes';
                          }
                        }
                        if(checkedSubser =='Yes')
                            Existrecord = 'Yes';
                    }
                    else
                        Existrecord = 'Yes';
                }
            }
        }
       // alert(Existrecord);
        if(Existrecord == 'no')
        {
            bootbox.alert('<div class="msg-error">Please select atleast one record.</div>');
            return false;
        }
        $("#scheduleform").ajaxForm({
            beforeSend: function() 
            {
               Popup_Display_Load();
            },
            success: function (html)
            {
                //alert(html);
                bootbox.alert('<div class="msg-success">Scheduled added succesfully.</div>', function() 
                {
                    window.location='add_scheduled.php';
                 });
            },
            complete : function()
            {
               Popup_Hide_Load();
            }
            }).submit();
    }
    </script>
</body>
</html>