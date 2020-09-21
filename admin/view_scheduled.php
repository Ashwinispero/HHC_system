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
    <title>View Professionals Schedule</title>
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
                            <img src="images/view-schedule-big.png" alt="View Professionals Schedule"> View Professionals Schedule
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
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
                                       
<!--                            <a href="javascript:void(0);"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>-->
                            <input type="button" onclick="return searchRecords();" value="View Schedule" name="btn-view-schedule" class="btn btn-download">
                       
                    </div>                    
                    <div class="clearfix"></div>
                    <div>
                        <form name="scheduleform" id="scheduleform" method="post" action="professional_ajax_process.php?action=submitScheduled" >
                            <div class="ScheduledListing">
                        
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
             var data1 = "fromdate="+fromdate+"&toDate="+toDate+"&profID=<?php echo $profID;?>&action=view_editcheduled";
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
                // alert(result);
                $(".ScheduledListing").html(result);                   
                 datepair()                   
             },
             complete : function()
             {
               Hide_Load();
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
              url: "professional_ajax_process.php?action=AddMorescheduled", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                  //alert(html);
                  document.getElementById(curr_div).innerHTML = html;
                  datepair();
                },
                complete : function()
                {
                   Hide_Load();
                }
          });               
        }
    }
    function deleteScheduled(number)
    {
        var j=document.getElementById('extras_'+number).value;
        if(j != 0)
        {
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
        } 
    }
    function scheduleSubForm()
    {
        $("#scheduleform").ajaxForm({
            beforeSend: function() 
            {
               Popup_Display_Load();
            },
            success: function (html)
            {
                var result=html.trim();
                if(result=='success')
                {
                     bootbox.alert("<div class='msg-success'>Professional schedule updated successfully.</div>"); 
                }
                else 
                {
                    bootbox.alert("<div class='msg-error'>Error in update professional schedule.</div>"); 
                }
                searchRecords();
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