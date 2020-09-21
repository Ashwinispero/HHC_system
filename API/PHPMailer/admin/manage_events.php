<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php"; 
      require_once '../classes/commonClass.php';
      $commonClass=new commonClass();
      require_once '../classes/employeesClass.php';
      $employeesClass=new employeesClass();
      require_once '../classes/professionalsClass.php';
      $professionalsClass=new professionalsClass(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Events </title>
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
                            <img src="images/events_big.png" alt="Manage Events"> Manage Events 
                            <a class="btn btn-download pull-right font18" href="manage_patients.php" data-original-title="" title="">VIEW PATIENTS</a>
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-3 paddingR0 inline_dp">
                        <div class="searchBox" >
                            <input type="hidden" name="record_id" id="record_id" value="<?php if(isset($_REQUEST['patient_id'])) { echo base64_decode($_REQUEST['patient_id']); } ?>"/> 
                            <input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Event "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onClick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                     <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">  
                            <select class="chosen-select form-control" name="search_purpose_id" id="search_purpose_id" onChange="searchRecords();">
                                <option value="">Purpose of call</option>
                                <?php
                                  $CallPurposeResult = $commonClass->GetAllCallPurposes();
                                  if(!empty($CallPurposeResult))
                                  {
                                        foreach($CallPurposeResult as $key=>$valRecords)
                                        {
                                          if($_POST['search_purpose_id'] == $valRecords['purpose_id'])
                                          {
                                              echo '<option value="'.$valRecords['purpose_id'].'" selected="selected">'.$valRecords['name'].'</option>';
                                          }
                                          else
                                          {
                                              echo '<option value="'.$valRecords['purpose_id'].'">'.$valRecords['name'].'</option>';
                                          }
                                        }
                                  }
                                  ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="chosen-select form-control" name="search_service_id" id="search_service_id" onChange="searchRecords();">
                                 <option value="">Search Requirement</option>
                                 <?php
                                    $recList = $commonClass->GetAllServices();
                                    if(!empty($recList))
                                    {
                                        foreach($recList as $key=>$valServices)
                                        {
                                          if($_POST['search_service_id'] == $valServices['service_id'])
                                          {
                                              echo '<option value="'.$valServices['service_id'].'" selected="selected">'.$valServices['service_title'].'</option>';
                                          }
                                          else
                                          {
                                              echo '<option value="'.$valServices['service_id'].'">'.$valServices['service_title'].'</option>';
                                          }
                                        }
                                    }
                                 ?>
                             </select>
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <?php
                                $recArgs['pageIndex']='1';
                                $recArgs['pageSize']='all';
                                $recListResponse = $professionalsClass->ProfessionalsList($recArgs);
                                $recList=$recListResponse['data'];
                            ?>
                            <select class="chosen-select form-control" name="search_professional_id" id="search_professional_id" onChange="searchRecords();">
                                 <option value="">Search Professional</option>
                                 <?php
                                    if(!empty($recList))
                                    {
                                        foreach($recList as $key=>$valProfessional)
                                        {
                                          if($_POST['search_professional_id'] == $valProfessional['service_professional_id'])
                                              echo '<option value="'.$valProfessional['service_professional_id'].'" selected="selected">'.$valProfessional['name']." ".$valProfessional['first_name'].'</option>';
                                          else
                                              echo '<option value="'.$valProfessional['service_professional_id'].'">'.$valProfessional['name']." ".$valProfessional['first_name'].'</option>';
                                        } 
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
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                        <a href="manage_events_trash.php?patient_id=<?php if(isset($_REQUEST['patient_id'])) { echo $_REQUEST['patient_id']; } ?>" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a> 
                    </div>
                    <div class="clearfix"></div>
                    <div class="EventsListing">
                        <?php include "include_events.php";?>
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
    <div class="modal fade" id="edit_event"> 
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
    <link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
    <script src="js/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <link rel="stylesheet" href="../dropdown/docsupport/prism.css">
    <link rel="stylesheet" href="../dropdown/chosen.css"> 
    <script src="../dropdown/chosen.jquery.js" type="text/javascript"></script>
    <script src="../dropdown/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function() 
        {
            textboxes = $("input.data-entry-search");
            $(textboxes).keydown (checkForEnterSearch);
            
            var config = {
                            '.chosen-select'           : {width:"99%"},
                            '.chosen-select-deselect'  : {allow_single_deselect:true},
                            '.chosen-select-no-single' : {disable_search_threshold:10},
                            '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                            '.chosen-select-width'     : {width:"95%"}
                         }
                        for (var selector in config) 
                        {
                          $(selector).chosen(config[selector]);
                        }
                        
          $('.datepicker_from').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
            yearRange: '2015:+0',
            maxDate:new Date(),
            onSelect: function() 
            {
                var date1 = $('.datepicker_from').datepicker('getDate');           
                var date = new Date( Date.parse( date1 ) ); 
                date.setDate( date.getDate() + 1 );        
                var newDate = date.toDateString(); 
                newDate = new Date( Date.parse( newDate ) );                      
                $('.datepicker_to').datepicker("option","minDate",newDate); 
                searchRecords();
            }
        });
        
       // $(".datepicker_from").keypress(function(event) {event.preventDefault();});
      
        $('.datepicker_to').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
            yearRange: '2015:+0',
            maxDate:new Date(),
            onSelect: function() 
            {
                searchRecords();
            }
        });
        
       // $(".datepicker_to").keypress(function(event) {event.preventDefault();});  
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
            changePagination('EventsListing','include_events.php','','','','');
        }
        function change_status(event_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this event ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this event ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this event ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&event_id="+event_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
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
                         //  alert(result);
                          if(result=='success')
                          {
                              bootbox.alert("<div class='msg-success'>Event "+success_msg+" successfully.</div>",function(){
                                  changePagination('EventsListing','include_events.php','','','','');
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