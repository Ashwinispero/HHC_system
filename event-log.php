<?php   require_once 'inc_classes.php';
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
<?php
if($_REQUEST['EID'])
{
    $requested_id = $_REQUEST['EID'];
    $EID = base64_decode($requested_id);
    $_REQUEST['event_id'] = $EID;
    $arg['event_id'] = $EID;
    $EditedResponseArr = $eventClass->GetEventCaller($arg);
    //var_dump($EditedResponseArr);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
</style>  
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Welcome to SPERO</title>
</head>

<body>
<style type="text/css">
    .free-wall {  margin: 15px; }
    .brick { width: 321.2px; }		
</style>
<?php include "include/header.php"; ?>
<section>
  <div class="container-fluid">
    <div class="row">
      <!-- Left Start-->
    <div class="col-left">
        <div id="content-1" class="content mCustomScrollbar">
            <div id="callerDiv">
                <?php include "include_callers.php"; ?>
            </div>
            <div class="line-seprator"></div>
            <div id="PatientDiv">
                <h4 class="section-head">
                    <span>
                        <img src="images/patient-icon.png" width="29" height="29">
                    </span>
                    PATIENT DETAILS
                </h4>
                <div role="tabpanel" id="Patienttabs"> 
                    <!-- Nav tabs -->
                    <ul id="MainTabs" class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active" id="ExTab"><a href="#existing" aria-controls="home" role="tab" data-toggle="tab" id="existingTabHd">EXISTING</a></li>
                      <li role="presentation" id="NewTab"><a href="#new" aria-controls="profile" role="tab" data-toggle="tab" id="NewPatienttab">NEW</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <input type="hidden" name="selected_patient_exist" id="selected_patient_exist" value="" >
                        <div role="tabpanel" class="tab-pane active" id="existing">
                            <div class="exPatientListing">
                                <?php include "include_existing_patient.php"; ?>     
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="new" >
                            <div class="newPatientListing">
                                <?php include "include_new_patient.php"; ?>   
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="new" display="none" >
                            <div class="newPatientListing_new">
                                <?php include "include_existing_caller.php"; ?>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="requirementDiv" class="requirementDivForServices">
                <div class="requirementListing" >
                    <?php include "include_requirements.php"; ?> 
                </div>                
            </div>   
            <div class="EnquiryNoteListing requirementDivForServices">
                <?php include "include_enquiry.php";?>
            </div>
            <div id="generalinfoDiv" class="requirementDivForServices">    
                <?php include "include_general_info.php";?>
            </div>
        </div>
      </div>
      <!-- Left End-->
      <div class="col-left-right">
        <div class="col-lg-12 paddingLR0" >            
            <!-- ---------------- Event Log start ----------- -->
            <div class="white-bg" id="RightSideDiv" style="display: n one;">
            
            <div class="form-group col-lg-4">
                        <?php  $recListResponse = $commonClass->GetTodayEnquiryCall();  
                       if($recListResponse)
                       {
                       ?>
                        <span class="badge" style="color: white;background-color: red;top: 50px" ><?php echo count($recListResponse); ?></span>
                                <select class="chosen-select form-control notification"  style="border-color:red" name="SearchKeyword_new" id="SearchKeyword_new" onChange="searchRecords();">
                               <option value="">Today Enquiry Calls</option>
                                 <?php
                                    $recListResponse = $commonClass->GetTodayEnquiryCall();  
                                   // $recList=$recListResponse['data'];
                                   
                                    foreach($recListResponse as $key=>$valProfessional)
                                    {
                                      if($_POST['event_code'] == $valProfessional['event_code'])
                                          echo '<option value="'.$valProfessional['event_id'].'" selected="selected">'.$valProfessional['event_code'].'</option>';
                                      else
                                          echo '<option value="'.$valProfessional['event_id'].'">'.$valProfessional['event_code'].'</option>';
                                    }

                                 ?>
                             </select>
                            
                        <?php } ?>
                        <!--</label>-->
            </div>
			<div align="right" class="row">
			<!--<a target = '_blank'  href='Assisted_Living_Avaibality.php' style="margin-right:5%">Assisted Living Avaibility</a>-->
			<a target = '_blank'  href='schedule_display_new.php' style="margin-right:5%">Professional Schedule</a>
			<!--<a target = '_blank'  href='schedule_display.php' style="margin-right:5%">Professional Schedule</a>-->
			<!--<a target = '_blank'  href='Enuiry_call_display.php' style="margin-right:5%">Enquiry call</a>-->
            <a target = '_blank'  href='Ongoing_calls.php' style="margin-right:5%">Ongoing Call's</a>
            
			</div>
		   <h2 class="page-title">Event Log</h2>
			
			
                <form class="form-inline serch-box" >
                    <div class="form-group col-lg-3">
                      <div class="row">
                        <div class="input-group col-lg-11"> 
                          <span class="input-group-addon text-left" style="width:5%;">
                                <a href="javascript:void(0);"><img onClick="searchRecords();" src="images/search-icon.png" width="22" height="21" alt="Search icon"></a>
                          </span>
                            <input type="text" placeholder="Search By Name/HHC No" class="form-control searchKeywords" id="SearchKeyword" name="SearchKeyword" aria-describedby="">
                        </div>
                      </div>
                    </div>
                    <input type="hidden" name="selected_patient_id" id="selected_patient_id" value="" />
                    <input type="hidden" name="purpose_call_event" id="purpose_call_event" value="" />
                     <div class="form-group col-lg-2">
                        <!--<label class="select-box-lbl">-->
                            <select class="chosen-select form-control" name="search_purpose_id" id="search_purpose_id" onChange="searchRecords();">
                                 <option value="">Purpose of Call</option>
                                  <?php
                                    $CallPurposeResult = $commonClass->GetAllCallPurposes();                          
                                    foreach($CallPurposeResult as $key=>$valRecords)
                                    {
                                      if($_POST['search_purpose_id'] == $valRecords['purpose_id'])
                                          echo '<option value="'.$valRecords['purpose_id'].'" selected="selected">'.$valRecords['name'].'</option>';
                                      else
                                          echo '<option value="'.$valRecords['purpose_id'].'">'.$valRecords['name'].'</option>';
                                    }
                                    ?>
                             </select>
                        <!--</label>-->
                    </div>
                    <div class="form-group col-lg-2">
                        <!--<label class="select-box-lbl">-->
                        
<!--                             <select class="chosen-select form-control" name="search_employee_id" id="search_employee_id" onChange="searchRecords();">
                                 <option value="">Attend By</option>
                                 <?php
                                    /*
                                    $recArgs['pageIndex']='1';
                                    $recArgs['pageSize']='all';
                                    
                                    $recListResponse = $employeesClass->EmployeesList($recArgs);
                                    
                                    $recList=$recListResponse['data'];
                                    foreach($recList as $key=>$valEmployee)
                                    {
                                      if($_POST['search_employee_id'] == $valEmployee['employee_id'])
                                          echo '<option value="'.$valEmployee['employee_id'].'" selected="selected">'.$valEmployee['name'].'</option>';
                                      else
                                          echo '<option value="'.$valEmployee['employee_id'].'">'.$valEmployee['name'].'</option>';
                                    }
                                     * 
                                     */
                                    ?>
                             </select>-->
                        
                             <select class="chosen-select form-control" name="search_professional_id" id="search_professional_id" onChange="searchRecords();">
                                 <option value="">Search Professional</option>
                                 <?php
                                    $recArgs['pageIndex']='1';
                                    $recArgs['pageSize']='all';
                                    $recArgs['isActiveOnly'] = '1';
                                    $recListResponse = $professionalsClass->ProfessionalsList_Active_Inactive($recArgs);
                                    $recList=$recListResponse['data'];
                                    foreach($recList as $key=>$valProfessional)
                                    {
                                      if($_POST['search_professional_id'] == $valProfessional['service_professional_id'])
                                          echo '<option value="'.$valProfessional['service_professional_id'].'" selected="selected">'.$valProfessional['name']." ".$valProfessional['first_name'].'</option>';
                                      else
                                          echo '<option value="'.$valProfessional['service_professional_id'].'">'.$valProfessional['name']." ".$valProfessional['first_name'].'</option>';
                                    }

                                 ?>
                             </select>
                        <!--</label>-->
                    </div>
                    <div class="form-group col-lg-2">
                        <!--<label class="select-box-lbl">-->
                            <select class="chosen-select form-control" name="filter_by" id="filter_by" onChange="filter_by_option();">
                                 <option value="">Select Date</option>
                                 <option value="1">Added Date</option>
                                 <option value="2">Service Date</option>
                            </select>
                        <!--</label>-->
                    </div>
                    <?php 
                    
                    ?>
                    <div class="form-group col-lg-3" style="display:none" id="filter_by_added_date">
                      <div class="row">
                        <div class="col-sm-4 text-right padingtop10" style="padding-right:0px !important;">Added Date </div>
                        <div class="col-sm-4" style="padding-right:0px !important;">
                          <input type="text" class="form-control datepicker_from"  id="event_from_date" name="event_from_date" placeholder="From" >
                        </div>
                        <div class="col-sm-4" style="padding-right:0px !important;">
                          <input type="text" class="form-control datepicker_to"  id="event_to_date" name="event_to_date" placeholder="To" >
                        </div>
                      </div>
                    </div>

                    <div class="form-group col-lg-3"  style="display:none" id="filter_by_service_date">
                      <div class="row">
                        <div class="col-sm-4 text-right padingtop10" style="padding-right:0px !important;">Service Date </div>
                        <div class="col-sm-4" style="padding-right:0px !important;">
                          <input type="text" class="form-control datepicker_from"  id="event_from_date_service" name="event_from_date" placeholder="From" >
                        </div>
                        <div class="col-sm-4" style="padding-right:0px !important;">
                          <input type="text" class="form-control datepicker_to"  id="event_to_date_service" name="event_to_date" placeholder="To" >
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-1 feedback-archieve" style="padding-right:0px !important;">
                        <a href="javascript:void(0);" title="Feedback List" onclick="javascript: return ShowEventLogList('2');" data-toggle="tooltip" data-placement="top" title="Feedback List"><img src="images/feedback-list.png" /></a>
                        <a href="javascript:void(0);" title="Archive List" onclick="javascript: return ShowEventLogList('3');" data-toggle="tooltip" data-placement="top" title="Archive List"><img src="images/archieve-list.png" /></a>
                        <input type="hidden" name="list_status_val" id="list_status_val"  />
                    </div>
              
                </form>
            <div class="eventLogListing">
                <?php  include "include_event_log.php"; ?> 
            </div>
            <div id="in1">
            <iframe></iframe>
            </div>
            </div>
            <!-- ---------------- Event Log End ----------- -->
            
            <!-- ---------------- Search Patient ----------- -->
            <div  id="SearchRightSideDiv" style="display: none;">               
                <div class="searchPatientListing">
                    <?php include "search_existing_patient.php"; ?> 
                </div>  
            </div>
            <!-- ---------------- Search Patient end ----------- -->      
          
            <div class="white-bg" id="PlanOfCareDiv" style="display: none;">               
                <div>
                    <?php  include "include_plan_of_care.php"; ?> 
                </div>  
            </div>

            <div class="white-bg" id="PaymentDetailsDiv" style="display: none;">               
                <div>
                    <?php  include "include_payment_details.php"; ?> 
                </div>  
            </div>
            <div class="white-bg" id="findProfessionalDiv" style="display: none;">               
                <div>
                    <?php  include "include_find_professional.php"; ?> 
                </div>  
            </div>			
            <div class="white-bg" id="jobSummaryDiv" style="display: none;">               
                <div>
                    <?php  include "include_job_summary.php"; ?> 
                </div>  
            </div>
            <div class="white-bg" id="JobClosureDiv" style="display: none;">               
                <div>
                    <?php  include "include_job_closure.php"; ?> 
                </div>  
            </div>
            <div class="white-bg" id="FeedbackDivs" style="display: none;">               
                <div>
                    <?php  include "include_feedback.php"; ?> 
                </div>  
            </div>
            <div class="white-bg" id="FeedbackDivs" style="display: none;">               
                <div>
                    <?php  include "include_feedback.php"; ?> 
                </div>  
            </div>			
            <!--<div id='calendar'></div>-->
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="vw_avaya">
    <div class="modal-dialog" style="width:350px !important;">
      <div class="modal-content" id="AllAjaxData_avaya"> </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
    <!-- Modal Popup code start ---> 
    <div class="modal fade" id="vw_professional"> 
        <div class="modal-dialog" style="width:950px !important;">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="vw_select_professional"> 
        <div class="modal-dialog">
          <div class="modal-content" id="AjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="vw_cancel_inquiry"> 
        <div class="modal-dialog">
          <div class="modal-content" id="inquiryAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="vw_add_follow_up_inquiry"> 
        <div class="modal-dialog">
          <div class="modal-content" id="followUpAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- Modal Popup code end ---> 
</section>
<?php include "include/scripts.php"; ?>
<?php include "include/eventLogscripts.php"; ?>

<script type="text/javascript">    
  function ChangeAjaxJs()
  {
    $('#parentHorizontalTab').easyResponsiveTabs({
        type: 'default', //Types: default, vertical, accordion
        width: 'auto', //auto or any width like 600px
        fit: true, // 100% fit in a container
        tabidentify: 'hor_1', // The tab groups identifier
        activate: function(event) { // Callback function if tab is switched
            var $tab = $(this);
            var $info = $('#nested-tabInfo');
            var $name = $('span', $info);
            $name.text($tab.text());
            $info.show();
        }
    });
    $('#parentHorizontalTab1').easyResponsiveTabs({
        type: 'default', //Types: default, vertical, accordion
        width: 'auto', //auto or any width like 600px
        fit: true, // 100% fit in a container
        tabidentify: 'hor_2', // The tab groups identifier
        activate: function(event) { // Callback function if tab is switched
            var $tab = $(this);
            var $info = $('#nested-tabInfo2');
            var $name = $('span', $info);
            $name.text($tab.text());
            $info.show();
        }
    });
    $('#parentVerticalTab').easyResponsiveTabs({
        type: 'vertical', //Types: default, vertical, accordion
        width: 'auto', //auto or any width like 600px
        fit: true, // 100% fit in a container
        closed: 'accordion', // Start closed if in accordion view
        tabidentify: 'hor_1', // The tab groups identifier
        activate: function(event) { // Callback function if tab is switched
            var $tab = $(this);
            var $info = $('#nested-tabInfo2');
            var $name = $('span', $info);
            $name.text($tab.text());
            $info.show();
        }
    });
  }
    $(document).ready(function() 
    {
        /***REQUIREMENTS SCROLLBAR BEGIN***/
        $('#requireservicesAll').change(function(){    
         setTimeout(function(){               
               $('#content-1').mCustomScrollbar("scrollTo", "bottom");
          },100);
        });
        /***REQUIREMENTS SCROLLBAR END***/
        $('#sub_service_id_multiselect').multiselect({enableFiltering: true,enableCaseInsensitiveFiltering: true});
        $('#requireservicesAll').multiselect({
            enableFiltering: true, 
            enableCaseInsensitiveFiltering: true,
            nonSelectedText:'Services',
            onChange: function(option, checked, select) 
            {
               Change_Subservice($(option).val(),checked);                
            }
        });
        var wall = new Freewall("#freewall");
        wall.fitWidth();
        ResizeWindow();                        
        ChangeAjaxJs();
        <?php
        if($EditedResponseArr['purpose_id']==2)
            echo '$(".EnquiryNoteListing").show();';
        else if($EditedResponseArr['purpose_id']==6)
        {
            echo '$("#generalinfoDiv").show();';
            echo '$("#PatientDiv").hide();';
        }
        else
            echo '$("#requirementDiv").show();';
        ?>
        $("#CallerForm").validationEngine('attach',{promptPosition : "bottomLeft"}); 
        $("#NewPatientForm").validationEngine('attach',{promptPosition : "bottomLeft"}); 
        $("#EnquiryNoteForm").validationEngine('attach',{promptPosition : "bottomLeft"}); 
        $("#generalInfoForm").validationEngine('attach',{promptPosition : "bottomLeft"}); 
        $('[data-toggle="tooltip"]').tooltip();
        $('.datepicker').datepicker({ 
                   changeMonth: true,
                   changeYear: true, 
                   dateFormat: 'dd-mm-yy',
                   yearRange: '1900:+0',
                   maxDate:new Date()
               });
        $(".datepicker").keypress(function(event) {event.preventDefault();});
        $('.datepicker_ex').datepicker({ 
                   changeMonth: true,
                   changeYear: true, 
                   dateFormat: 'dd-mm-yy',
                   yearRange: '1900:+0',
                   maxDate:new Date()
            }); 
        $(".datepicker_ex").keypress(function(event) {event.preventDefault();});
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
                
                if($('.datepicker_to').val())
                {
                    searchRecords();
                }  
            }
        });
        $(".datepicker_from").keypress(function(event) {event.preventDefault();});
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
        $(".datepicker_to").keypress(function(event) {event.preventDefault();});
        textboxes = $("input.searchKeywords");
        $(textboxes).keydown (checkForEnterSearch);
        //datesearch = $("input.datepicker_from");
        //$(datesearch).keyup(checkForEnterSearch);
        datepickerVal(0);

        // inquiry requirement section code
        $('#enquiryRequirnment').change(function() {    
            setTimeout(function() {               
                $('#content-1').mCustomScrollbar("scrollTo", "bottom");
            }, 100);
        });

        $('#enquiry_sub_service_id_multiselect').multiselect({enableFiltering: true,enableCaseInsensitiveFiltering: true});

        $('#enquiryRequirnment').multiselect({
            enableFiltering: true, 
            enableCaseInsensitiveFiltering: true,
            nonSelectedText:'Services',
            onChange: function(option, checked, select) 
            {
               ChangeEnquirySubservice($(option).val(),checked);                
            }
        });
        
        // check per page session value
        $(".clearSessioncls").click(function() {
            changePagination('eventLogListing','include_event_log.php','','10','desc','');
            <?php
                if (isset($_SESSION['per_page']) && $_SESSION['per_page'] > 10) {
                    unset($_SESSION['per_page']);
                }
            ?>
        });

    });
    function ResizeWindow()
    {
        var wall = new Freewall("#freewall");
            //alert(wall);
            wall.reset({
                selector: '.brick',
                animate: true,
                cellW: 300,
                cellH: 'auto',
                onResize: function() 
                {
                   wall.fitWidth();
                }
            });
    }
    function ChangePurposeCall(purpose_id)
    {
        if(purpose_id == 7)
        {
            $("#PatientDiv").show();
            $(".requirementListing").hide();
            $(".EnquiryNoteListing").hide();
            $("#generalinfoDiv").hide();
            $("#callerConsultantDiv").hide();
            $("#CallerDivStart").hide();            
            $("#callerJobclosureDiv").show();
            $("a#NewPatienttab").attr("href", "javascript:void(0);");
            $(".callerPhone").val('');
            $(".callerNameText").val('');
            $(".callerFNameText").val('');
            $(".callerMNameText").val('');
            $('#relation').prop('selectedIndex',0);
        }
        else if(purpose_id == 3)
        {
            $("#PatientDiv").show();
            $(".requirementListing").hide();
            $(".EnquiryNoteListing").hide();
            $("#generalinfoDiv").hide();
            $("#callerConsultantDiv").hide();
            $("#CallerDivStart").show();
            $("#callerJobclosureDiv").hide();
            $("a#NewPatienttab").attr("href", "javascript:void(0);");
        }
        else if(purpose_id == 6)
        {
            $("#PatientDiv").hide();
            $(".requirementListing").hide();
            $(".EnquiryNoteListing").hide();
            $("#generalinfoDiv").show();
            $("#callerConsultantDiv").hide();
            $("#CallerDivStart").show();
            $("#callerJobclosureDiv").hide();
            $("a#NewPatienttab").attr("href", "#new");
        }
        else if(purpose_id == 2)
        {
            $("#PatientDiv").show();
            $(".requirementListing").hide();
            $(".EnquiryNoteListing").show();
            $("#generalinfoDiv").hide();
            $("#callerConsultantDiv").hide();
            $("#CallerDivStart").show();
            $("#callerJobclosureDiv").hide();
            $("#new").addClass('active');
            $("#existing").removeClass('active');
            $("#ExTab").removeClass('active');
            $("#NewTab").addClass('active'); 
            $("a#NewPatienttab").attr("href", "#new");
            //$("a#existingTabHd").attr("href", "javascript:void(0);");
        }
        else if(purpose_id == 4)
        {
            $("#PatientDiv").show();
            $(".requirementListing").hide();
            $(".EnquiryNoteListing").hide();
            $("#generalinfoDiv").hide();
            $("#callerConsultantDiv").show();
            $("#CallerDivStart").hide();
            $("#callerJobclosureDiv").hide();
            $("#new").removeClass('active');
            $("#existing").addClass('active');
            $("#ExTab").addClass('active');
            $("#NewTab").removeClass('active'); 
            $("a#NewPatienttab").attr("href", "javascript:void(0);");
        }
        else if(purpose_id == 5)
        {
            $("#PatientDiv").show();
            $(".requirementListing").hide();
            $(".EnquiryNoteListing").hide();
            $("#generalinfoDiv").hide();
            $("#callerConsultantDiv").hide();
            $("#CallerDivStart").show();
            $("#callerJobclosureDiv").hide();
            $("#new").removeClass('active');
            $("#existing").addClass('active');
            $("#ExTab").addClass('active');
            $("#NewTab").removeClass('active'); 
            $("a#NewPatienttab").attr("href", "javascript:void(0);");
        }
        else
        {
            $("#PatientDiv").show();
            $(".requirementListing").show();
            $(".EnquiryNoteListing").hide();
            $("#generalinfoDiv").hide();
            $("#callerConsultantDiv").hide();
            $("#CallerDivStart").show();
            $("#callerJobclosureDiv").hide();
            $("a#NewPatienttab").attr("href", "#new");
        }
    }
    function checkForEnterSearch (event) 
    {
        if (event.keyCode == 13)
        {
            searchRecords();
        }
    }
    function searchRecords()
    {
        changePagination('eventLogListing','include_event_log.php','','','','');
    }
    $( ".callerPhone" ).blur(function()
    {
        var phno = $("#phone_no").val();
        
        if(phno)
        {
          //  var myLength = $("#phone_no").val().length;
            
           // alert(myLength);
            
          //  if(myLength >9)
         //   {
                var data1="phone_no="+phno+"&action=CheckCallerExist";
                $.ajax({
                    url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        // Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                        //alert(result);
                        if(result)
                        {
                            var res = result.split("-"); 
                            if(res[0])
                            {
                                $("#name").val(res[0]);
                            }
                            if(res[1])
                            {
                                $("#caller_first_name").val(res[1]);
                            }
                            if(res[2])
                            {
                                $("#caller_middle_name").val(res[2]);
                            }
                        }
                        else
                        {
                            $("#name,#caller_first_name,#caller_middle_name").val('');
                        }  
                    },
                    complete : function()
                    {
                      // Popup_Hide_Load();
                    }
                }); 
           // }  
        } 
        else 
        {
            $("#name,#caller_first_name,#caller_middle_name").val('');
        }
    });
    function Change_Subservice(value,checked)
    {
        var fld = document.getElementById('requireservicesAll');
        var CheckVals = [];
        for (var i = 0; i < fld.options.length; i++) {
          if (fld.options[i].selected) {
            CheckVals.push(fld.options[i].value);
          }
        }
        var checkAllservices = 'no';
        var loginType='<?php echo $_SESSION['employee_type'];?>';
        for (j = 0; j< CheckVals.length; j++)
        {            
            var HDAccess = $("#isAccessHD_"+CheckVals[j]).val();
            if(HDAccess == 'N')
                checkAllservices = 'yes';
        }
        if(checkAllservices == 'yes' && loginType!=1)
        {
            $("#invisibleDispatch").show();
            $("#dispatch").hide();
        }
        else
        {
            $("#invisibleDispatch").hide();
            $("#dispatch").show();
        }
        var checkdata = $('#services_'+value).is(':checked'); 
        var data1="service_ids="+value+"&action=ChangeSubServices";
        $.ajax({
            url: "ajax_public_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
                // Popup_Display_Load();
            },
            success: function (html)
            {
              //alert(html);
                result = html.trim();
                valuesData = result.split("sepratedTitle--"); 
                if(checked == true)
                {
                    $("#newData").append(valuesData[0]);
                   // $("#dropdownMenu1").html(valuesData[1]+'<span class="caret"></span>');
                }
                else
                {
                    $("#ServiceDiv_"+value).remove();   
                }
                
                $('#sub_service_id_multiselect_'+value).change(function(){    
                    setTimeout(function(){               
                          $('#content-1').mCustomScrollbar("scrollTo", "bottom");
                     },100);
                   });
                
                $('#sub_service_id_multiselect_'+value).multiselect({enableFiltering: true, 
                    enableCaseInsensitiveFiltering: true,nonSelectedText:valuesData[1]+" (Recommended Service)"});
            },
            complete : function()
            {
               //$('#loader_image').hide();
            }
        });
    }
    function SubmitCaller()
    {
        var purpose_id = $("#purpose_id").val();
        var submit = 'yes';
        if(purpose_id == '7')
        {            
            var professional_id = $("#choose_professional_id").val();
            if(professional_id == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please select professional for job closure.</div>");
                return false;
            }
        }
        else if(purpose_id == '4')
        {            
            var caller_consultant_id = $("#caller_consultant_id").val();
            if(caller_consultant_id == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please select consultant.</div>");
                return false;                
            }
        }
        else
        {
            if($("#purpose_id").val() == '')
            {
                submit = 'no';
                bootbox.alert("<div class='msg-error'>Please select purpose of call</div>");
                //alert('Please enter caller details.');
                return false;
            }
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
                //alert('Please enter caller details.');
                return false;
            }
        }
        if(submit == 'yes')
        {
            //Display_Load();
            $("#CallerForm").ajaxForm({
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
                        //alert(html);
                        $("#temp_event_id").val(result);
                        $("#event_id_temp").val(result);
                        $("#callerEvent_id").val(result);
                        $("#enquiryEvent_id").val(result);
                        $("#generalEvent_id").val(result);
                        $("#Edit_event_id").val(result);
                        $("#eventIDForClosure").val(result);
                        $("#Edit_CallerId").val(callerID);
                        $("#purpose_id").prop('disabled', 'disabled');
                        changePagination('eventLogListing','include_event_log.php','','','','');                    
                        //Hide_Load();
                        if(purpose_id == '6')
                        {
                            bootbox.alert("<div class='msg-success'>Caller details added successfully. Now you can add general information.</div>");
                        }
                        else
                        {
                            bootbox.alert("<div class='msg-success'>Caller details added successfully. Now you can add patient details.</div>");
                        }
                    }
               },
                complete : function()
                {
                   Popup_Hide_Load();
                }
           }).submit();           
           //Hide_Load();
        }
    }
    function generate_hhc_no()
    {    
       // var location_valid = 'no';
        if(commonValidation())
        {
            if($("#google_location").val())
            {
               // patient_generatehhc();
                var addressField = document.getElementById('google_location');
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode(
                {'address': addressField.value}, 
                function(results, status) { 
                    if (status == google.maps.GeocoderStatus.OK) 
                    {
                        var loc = results[0].geometry.location;
                        console.log(addressField.value+" found on Google");
                        patient_generatehhc();
                        //var datas = valid_google_location('yes');
                    } else {
                        console.log(addressField.value+" not found on Google");
                        alert('Please select valid location.');
                        //var datas = valid_google_location('no');
                        return false;
                    } 
                }
                );
            }
            else
            {
                alert('Please select location.');
                return false;
            }
        }
    }
    function patient_generatehhc()
    {
        var succ_msg="";
            //Display_Load();
            $("#NewPatientForm").ajaxForm({
                beforeSend: function() 
                {
                    Display_Load();
                },
               success: function (html)
               {
                   //alert(html);
                    var result=html.trim();  
                    if(result=="UpdateSuccess")
                    {
                        succ_msg="updated";
                    }
                    if(result=="InsertSuccess")
                    {
                        succ_msg="inserted";
                    }
                    $("#patient_id_temp").val(result);
                    var purposeID = $("#prv_purpose_id").val();
                    if(purposeID == '1')
                    {
                        bootbox.alert("<div class='msg-success'>Patient details "+succ_msg+" successfully. Now you can add requirement details.</div>",function(){
                            changePagination('eventLogListing','include_event_log.php','','','','');
                        });
                    }
                    else if(purposeID == '2')
                    {
                        bootbox.alert("<div class='msg-success'>Patient details "+succ_msg+" successfully. Now you can add enquiry note.</div>",function(){
                            changePagination('eventLogListing','include_event_log.php','','','','');
                        });
                    }
                    else if(purposeID == '6')
                    {
                        bootbox.alert("<div class='msg-success'>Patient details "+succ_msg+" successfully. Now you can add general information.</div>",function(){
                            changePagination('eventLogListing','include_event_log.php','','','','');
                        });
                    }  
               },
                complete : function()
                {
                   Hide_Load();
                }
           }).submit();
    }
    function SubmitEnquiryNote()
    {
        if(commonValidation())
        {
            if($("#enquiry_note").val()=='')
            {
                bootbox.alert("<div class='msg-error'>Please enter enquiry note.</div>");
                return false;
            }
            $("#EnquiryNoteForm").ajaxForm({
                beforeSend: function() 
                {
                    Display_Load();
                },
               success: function (html)
               {
                    var result=html.trim();  
                   //alert(result);      
                    if(result=="updated")
                    {
                        bootbox.alert('<div class="msg-success">Enquiry details added succesfully.</div>', function() {
                                window.location='event-log.php';
                               });
                    }
                    else
                    {
                        bootbox.alert("<div class='msg-error'>There is an error in submitting the form, please try again later.</div>");
                        return false;
                    }
               },
               complete : function()
                {
                   Hide_Load();
                }
           }).submit();
        }
    }
    function submitGeneralInfo()
    {
        var selected_purpose = $("#purpose_id").val();
        $(".prv_purpose_id").val(selected_purpose);
        var existEvent = $("#generalEvent_id").val();
        var existpurpose = $(".prv_purpose_id").val();
        if(existpurpose == '')
        {
            bootbox.alert("<div class='msg-error'>Please select purpose of call.</div>");
            return false;
        }
        else if(existEvent == '')
        {
            bootbox.alert("<div class='msg-error'>Please submit caller details form.</div>");
            return false;
        }
        $("#generalInfoForm").ajaxForm({
            beforeSend: function() 
            {
                Display_Load();
            },
           success: function (html)
           {
                var result=html.trim(); 
                if(result=="updated")
                {                    
                    bootbox.alert('<div class="msg-success">General information added successfully.</div>', function() {
                                window.location='event-log.php';
                               });
                }
                else
                {
                    bootbox.alert("<div class='msg-error'>There is an error in submitting the form, please try again later.</div>");

                    return false;
                }
           },
            complete : function()
            {
               Hide_Load();
            }
       }).submit();
    }
    function commonValidation()
    {
        var selected_purpose = $("#purpose_id").val();
        $(".prv_purpose_id").val(selected_purpose);
        var existEvent = $("#temp_event_id").val();
        var existpurpose = $("#prv_purpose_id").val();
        var Edit_event_id = $("#Edit_event_id").val();
        if(existpurpose == '')
        {
            bootbox.alert("<div class='msg-error'>Please select purpose of call.</div>");
            return false;
        }
        else if(existEvent == '' && Edit_event_id=='')
        {
            bootbox.alert("<div class='msg-error'>Please submit caller details form.</div>");
            return false;
        }
        else if($("#ref_hos_id").val() == ''  ||$("#patient_name").val() == ''  ||  $("#patient_mobile_no").val() =='' || $("#patient_email_id").val() =='' ) //|| $("#patient_location").val() ==''
        {
            bootbox.alert("<div class='msg-error'>Please enter all patient details.</div>");
            return false;
        }
        else 
            return true;
    }
    function SelctedOther(value,type){
        if(value=='Other'){
        var data1="value="+value+"&type="+type+"&action=OtherChange";
        $.ajax({
            url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
                Display_Load();
            },
            success: function (html)
            {
               //alert(html);
                $("#Other_Hos_Details").html(html);
                
            },
            complete : function()
            {
                Hide_Load();
            }
        });
    }
    }
    function SelctedDoctors(value,type)
    {
        var data1="doctor_consId="+value+"&type="+type+"&action=DoctorsConsultantChange";
        $.ajax({
            url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
                Display_Load();
            },
            success: function (html)
            {
               //alert(html);
                if(type == '2')
                    $("#consultantDetails").html(html);
                else
                    $("#doctorsDetails").html(html);
            },
            complete : function()
            {
                Hide_Load();
            }
        });
    }
    function SelctedConsultant(value,type)
    {
        var data1="doctor_consId="+value+"&type="+type+"&action=DoctorsConsultantChange";
        $.ajax({
            url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
                Display_Load();
            },
            success: function (html)
            {
               //alert(html);
                if(type == '2')
                    $("#CallerconsultantDetails").html(html); 
            },
            complete : function()
            {
                Hide_Load();
            }
        });
    }
    function changeRelation(relation)
    {
        if(relation == 'Self')
        {            
            var CallerNameVal = $("#name").val();
            var CallerFNameVal = $("#caller_first_name").val();
             var CallerMNameVal = $("#caller_middle_name").val();
            var CallerPhNo = $("#phone_no").val();
            $("#patient_name").val(CallerNameVal);
            $("#patient_first_name").val(CallerFNameVal);
            $("#patient_middle_name").val(CallerMNameVal);
            $("#patient_mobile_no").val(CallerPhNo);
        }
        else
        {
            $("#patient_name,#patient_first_name,#patient_middle_name,#patient_mobile_no").val('');
        }
    }
    function ChangeLocation(value,type)
    {
        var PinCode="";
        var LocationId=$("#patient_location").val();
        if(type == 'pin')
        {
            PinCode=$("#patient_pin_code").val();
        }
        else
        {
           PinCode= '';
        }
        if(LocationId)
        {
            var data1="type_id="+value+"&type="+type+"&LocationId="+LocationId+"&PinCode="+PinCode+"&action=LocationSelect";
            //alert(data1);
            $.ajax({
                url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                    //alert(html);
                    if(type == 'location')
                        $("#PINCODEList").html(html);
                    else
                        $("#LOCATIONList").html(html);

                    for (var selector in config) 
                    {
                      $(selector).chosen(config[selector]);
                    }                
                },
                complete : function()
                {
                   Hide_Load();
                }
            });
        }
        else 
        {
          // Loading Location and pin code data 
           var data1="action=RefreshLocation";
           $.ajax({
                url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                    var res=html.trim();
                    $("#LOCATIONList").html(res);
                    $('#patient_pin_code')[0].selectedIndex = 0;
                    for (var selector in config) 
                    {
                      $(selector).chosen(config[selector]);
                    } 
                },
                complete : function()
                {
                   Hide_Load();
                }
            });
          
        }
    }
    function dispatchRequirement(value)
    {
        var selected_purpose = $("#purpose_id").val();
        $("#purpose_id_temp").val(selected_purpose);
        var existEvent = $("#event_id_temp").val();
        var existPatient = $("#patient_id_temp").val();
        var existpurpose = $("#purpose_id_temp").val();
		 var hospital_name = $("#hospital_name").val();
		  var Consultant = $("#Consultant").val();
		
        var checkdatas = $("#requireservicesAll option:selected").length;
        var checkedSubser = 'No';
        if(checkdatas)
        {
            var fld = document.getElementById('requireservicesAll');
            var CheckVals = [];
            for (var i = 0; i < fld.options.length; i++) {
              if (fld.options[i].selected) {
                CheckVals.push(fld.options[i].value);
              }
            }            
            for (j = 0; j< CheckVals.length; j++)
            {
                var checkedSubser = 'No';
                var subsersel = document.getElementById('sub_service_id_multiselect_'+CheckVals[j]);
                
                for (var m = 0; m < subsersel.options.length; m++) {
                  if (subsersel.options[m].selected) {
                    checkedSubser = 'Yes';
                  }
                }
            }            
        }        
        if(existpurpose == '')
        {
            bootbox.alert("<div class='msg-error'>Please select purpose of call.</div>");
            return false;
        }
        else if(existEvent == '')
        {
            bootbox.alert("<div class='msg-error'>Please submit caller details form.</div>");
            return false;
        }
        else if(existPatient == '')
        {
            bootbox.alert("<div class='msg-error'>Please enter patient details.</div>");
            return false;
        }
        else if(checkdatas == 0 )
        {
            bootbox.alert("<div class='msg-error'>Please select services.</div>");
            return false;
        }
        else if(checkedSubser == 'No' )
        {
            bootbox.alert("<div class='msg-error'>Please select sub services.</div>");
            return false;
        }
        else
        {     
          //return false;
            $("#RequirementForm").ajaxForm({
                beforeSend: function() 
                {
                    Display_Load();
                },
               success: function (html)
               {
                    var result=html.trim();
                    //alert(result);
                    if(value=='2')  /* Value 2 means share with hcm  and Value 1 means dispatch */
                    {
                       ShareWithHCM(result);
                    }
                    else 
                    {
                        bootbox.alert("<div class='msg-success'>Requirements added successfully. Now you can add plan of care.</div>",function()
                        {
							//alert("Plan of care caller in 1");
                            planOfCare(result,1); 
                        }); 
                    }  
               },
                complete : function()
                {
                   Hide_Load();
                }
           }).submit();
        }
    }
    function planOfCare(event_id,set)
    {
       // alert(event_id);
        var data1="event_id="+event_id;
        $.ajax({
            url: "include_plan_of_care.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               Display_Load();
            },
            success: function (html)
            {
               //alert(html);
               $("#RightSideDiv").hide();
               $("#PlanOfCareDiv").show();
               $("#PlanOfCareDiv").html(html);
                datepickerVal(0);                    
                if(set == 1)
                {
                    $("#confirmEstimatedBox1").attr('checked', false);
                    $("#findProfessionalDiv").hide();
                    $("#jobSummaryDiv").hide();
                }
            },
            complete : function()
            {
               Hide_Load();
            }
        });
    }
    function changeamountbox(value)
    {
       // alert(value);
        if(value == '2')
        {
            $("#noamount").show();
        }
        else
            $("#noamount").hide();
    }
    function datepickerVal(value)
    {
        $('.datepicker_eve_'+value).datepicker({
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
           /* minDate:new Date(),*/
            onSelect: function(date)
            {
                var toDtEv = $(this).datepicker().attr('name');
                var splitString = toDtEv.split('eve_from_date');
                var splitID = splitString[1].split('_');    
                //alert(splitString[1]);
                var date1 = $('#eve_from_date_'+splitID[1]+'_'+splitID[2]).datepicker('getDate'); 
                var date = new Date(Date.parse(date1)); 
                //alert(date1);
                date.setDate(date.getDate());        
                var newDate = date.toDateString(); 
                newDate = new Date(Date.parse(newDate));
                //alert(newDate);
                $('#eve_to_date_'+splitID[1]+'_'+splitID[2]).datepicker("option","minDate",newDate); 
                var fromDtSelected = $("#eve_to_date"+splitString[1]).val();
                var toDateSelcted = $(this).datepicker().val();
                if(fromDtSelected)
                {
                    var data1="fromDtSelected="+toDateSelcted+"&toDateSelcted="+fromDtSelected+"&eventRequirementID="+splitID[2];
                   // alert(data1);
                    $.ajax({
                        url: "ajax_public_process.php?action=setCostPlancare", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                             Display_Load();
                        },
                        success: function (html)
                        {
                            //alert(html);
                            if (html.indexOf('Other') > -1) 
                            {
                                // Getting Value of other field 
                                var other_cost_val =$("other_service_cost_"+splitID[1]+"_"+splitID[2]).val();
                              //  alert(other_cost_val);
                              
                              if(other_cost_val !='undefined' && other_cost_val !='null')
                              {
                                $("#hidden_costService_"+splitID[1]+"_"+splitID[2]).val(other_cost_val); 
                              }
                              else 
                              {
                                $("#hidden_costService_"+splitID[1]+"_"+splitID[2]).val('0');  
                              }

                                // check is it discount type dropdown is disabled then enable it
                                if ($('#discount_id').prop('disabled')) {
                                    $('#discount_id').prop("disabled", false);
                                }
                                
                                // calculate discount
                                calDiscountAmount();

                              return false;
                            }
                            else 
                            {
                                $("#costService_"+splitID[1]+"_"+splitID[2]).html(html);         
                                $("#hidden_costService_"+splitID[1]+"_"+splitID[2]).val(html);    
                                var eventIDS = $("#PlanEvent_id").val();
                                var totalCost = calucateTotalCost(eventIDS);
                                if($("input:radio[name='confirmEstimatedBox']").is(":checked")) 
                                { 
                                    $("input:radio[name='confirmEstimatedBox']").removeAttr("checked");
                                }
                            }
                        },
                        complete : function()
                        {
                           Hide_Load();
                        }
                    });
                }   
            }
        });
        $('.datepicker_eve_to_'+value).datepicker({
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
            /*minDate:new Date(),*/
            onSelect: function() 
            {
                var toDtEv = $(this).datepicker().attr('name');
                var splitString = toDtEv.split('eve_to_date');
                var fromDtSelected = $("#eve_from_date"+splitString[1]).val();
                var toDateSelcted = $(this).datepicker().val();
                var splitID = splitString[1].split('_'); 
                if(fromDtSelected)
                {
                    var data1="fromDtSelected="+fromDtSelected+"&toDateSelcted="+toDateSelcted+"&eventRequirementID="+splitID[2];
                   // alert(data1);
                    $.ajax({
                        url: "ajax_public_process.php?action=setCostPlancare", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                             Display_Load();
                        },
                        success: function (html)
                        {
                            
                          var eventIDS = $("#PlanEvent_id").val();
                          
                          if (html.indexOf('Other') > -1) 
                          {
                               var other_cost_val =$("other_service_cost_"+splitID[1]+"_"+splitID[2]).val();
                               // alert(other_cost_val);
                               if(other_cost_val !='undefined' && other_cost_val !='null' && other_cost_val >0)
                               {
                                  $("#hidden_costService_"+splitID[1]+"_"+splitID[2]).val(other_cost_val);
                               }
                               else 
                               {
                                  $("#hidden_costService_"+splitID[1]+"_"+splitID[2]).val('0'); 
                               }
                               
                              var totalCost = calucateTotalCost(eventIDS);
                              return false;
                          }
                          else 
                          {
                            $("#costService_"+splitID[1]+"_"+splitID[2]).addClass("text-right");
                            $("#costService_"+splitID[1]+"_"+splitID[2]).html(html);         
                            $("#hidden_costService_"+splitID[1]+"_"+splitID[2]).val(html);    
                            var totalCost = calucateTotalCost(eventIDS);
                            if($("input:radio[name='confirmEstimatedBox']").is(":checked")) 
                            { 
                               $("input:radio[name='confirmEstimatedBox']").removeAttr("checked");
                               $('#EstimationRadioStatus').val(this.value);
                            }
                          }
                        },
                        complete : function()
                        {
                           Hide_Load();
                        }
                    });
                }    
            }
        });
        $(".datepicker_eve_"+value).keypress(function(event) {event.preventDefault();});
        $(".datepicker_eve_to_"+value).keypress(function(event) {event.preventDefault();});
        $('.datepairExample_'+value+' .time').timepicker({
                        'showDuration': true,
                        'timeFormat': 'h:i A'
                    });
        $('.datepairExample_'+value+' .time').on('changeTime', function() 
        {
            if($("input:radio[name='confirmEstimatedBox']").is(":checked")) 
            { 
                $("input:radio[name='confirmEstimatedBox']").removeAttr("checked");
                $('#EstimationRadioStatus').val(this.value);
            }
        }); 
        $('.datepairExample_'+value).keypress(function(event) {event.preventDefault();});                      
        $('.datepairExample_'+value).datepair();
    }
    function calucateTotalCost(Planevent)
    {
        var RequireArray = $("#AllReqSel").val();
        var temp = new Array();
            temp = RequireArray.split(',');
           // alert(temp.length);
            var costvalue = 0; var existValue = 0;
            for(i=0;i<temp.length;i++)
            {
                //alert(temp[i]); 
                var extrArray = parseInt(document.getElementById('PlanCareextras_'+temp[i]).value);  
                if(extrArray !='undefined' && extrArray !='null')
                {
                   // alert(extrArray);
                    for(ja=0;ja<=extrArray;ja++)
                    {                    
                        var fromDate = $("#eve_from_date_"+ja+"_"+temp[i]).val();
                        var toDate = $("#eve_to_date_"+ja+"_"+temp[i]).val();
                        var fromTime = $("#starttime_"+ja+"_"+temp[i]).val();
                        var toTime = $("#endtime_"+ja+"_"+temp[i]).val();      
                       // alert($("#hidden_costService_"+ja+"_"+temp[i]).val());
                        if($("#hidden_costService_"+ja+"_"+temp[i]).val() !='undefined' && $("#hidden_costService_"+ja+"_"+temp[i]).val() !='null' && $("#hidden_costService_"+ja+"_"+temp[i]).val()>0)
                        {
                           // alert("Hi"+" "+$("#hidden_costService_"+ja+"_"+temp[i]).val());
                            costvalue += parseInt($("#hidden_costService_"+ja+"_"+temp[i]).val());  
                           // alert($("#hidden_costService_"+ja+"_"+temp[i]).val());
                           // alert("extrArrayHi"+costvalue);
                        }
                        else 
                        {
                             costvalue +=parseInt('0');
                           //  alert("extrArrayHello"+costvalue);
                        }
                    }
                }
                var ExistArrDel = $("#existSelRec_"+temp[i]).val();
                if(ExistArrDel !='undefined' && ExistArrDel !='null')
                {
                    // alert(ExistArrDel);
                    for(ex=2;ex<ExistArrDel;ex++)
                    {
                        //alert(ex);
                        if($("#hidden_costService_ex_"+ex+"_"+temp[i]).val() !='undefined' && $("#hidden_costService_ex_"+ex+"_"+temp[i]).val() !='null' && $("#hidden_costService_ex_"+ex+"_"+temp[i]).val() >0)
                        {
                           // alert("Hello"+" "+$("#hidden_costService_"+ex+"_"+temp[i]).val());
                            existValue += parseInt($("#hidden_costService_ex_"+ex+"_"+temp[i]).val()); 
                          //  alert("ExistArrDel"+existValue);
                        }
                        else 
                        {
                            existValue += parseInt('0');
                            //alert("ExistArrDel"+existValue);
                        }
                        //alert($("#hidden_costService_ex_"+ex+"_"+temp[i]).val());
                        //alert('hi');
                    }  
                }
                  
            }
          //  alert(costvalue);
           //alert(existValue);
           if(costvalue>=0 && existValue >=0)
           {
                var finalcost = parseInt(costvalue)+parseInt(existValue);
                var printFinalcost = finalcost+".00";
                // alert(finalcost);
                $("#TotalEstCost").html(printFinalcost); 
                $("#finalcost_eve").val(printFinalcost); 
           }
           else 
           {
               $("#TotalEstCost").html('0'); 
               $("#finalcost_eve").val('0'); 
           }

           // check is it discount type dropdown is disabled then enable it
            if ($('#discount_id').prop('disabled')) {
                $('#discount_id').prop("disabled", false);
            }

           calDiscountAmount();
    }
    function SearchPatients()
    {
        var existCaller = $("#callerEvent_id").val();
        var Edit_event_id = $("#Edit_event_id").val();
        if(existCaller == '' && Edit_event_id=="")
        {
            bootbox.alert("<div class='msg-error'>Please submit caller details form.</div>");
            return false;
        }
        if($("#existing_hhc_code").val() == '' && $("#existing_patient_name").val() == '' && $("#existing_mobile_no").val() == '' && $("#ex_landline_no").val() == '' && $("#existing_dob").val() == '')
        {
            bootbox.alert("<div class='msg-error'>Please enter any search field.</div>");
            return false;
        }
        else
        {
            $("#ExistingPatientForm").ajaxForm({
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                     var result=html.trim(); 
                     //alert(result);
                     $("#RightSideDiv").hide();
                     $("#SearchRightSideDiv").show();         
                     changePagination('searchPatientListing','search_existing_patient.php','','','','');
                     $("#JobClosureDiv").hide();       
                     $("#FeedbackDivs").hide();       
                     //$("#SearchRightSideDiv").html(result);
                },
                complete : function()
                {
                   Hide_Load();
                }
            }).submit();
        }
    }
    function SeclectPatient(patient_id)
    {        
        $("#SearchRightSideDiv").hide();
        $("#RightSideDiv").show();
        $("#selected_patient_exist").val(patient_id);
        var callpurpose=$("#purpose_id").val();
        if(callpurpose==3)//------if feedback then show only sected patient logs---
        {
            $("#selected_patient_id").val(patient_id);
            $("#purpose_call_event").val(callpurpose);
        }
        if(callpurpose==4)//------if consultant call then show only sected patient logs---
        {
            $("#selected_patient_id").val(patient_id);
            $("#purpose_call_event").val(callpurpose);
        }
        if(callpurpose==5)//------if Follow up call then show only sected patient logs---
        {
            $("#selected_patient_id").val(patient_id);
            $("#purpose_call_event").val(callpurpose);
        }
        if(callpurpose==7)//------if job closure call then show only selected patient logs---
        {
            $("#selected_patient_id").val(patient_id);
            $("#purpose_call_event").val(callpurpose);
        }
        changePatientTab('New');
        changePagePatient('newPatientListing','include_new_patient.php','','','','');
        searchRecords();
    }

    function confirmEstimatedCost(value,RequireArray)
    {
        //alert(value);
		
        if(value == '2')
        {
            var PlanEvent_id = $("#PlanEvent_id").val();
            var prompt_msgs = "Are you sure to cancel the estimated cost?";
            bootbox.confirm(prompt_msgs, function (res) 
            {
               if(res==true)
               {
                    var data1="PlanEvent_id="+PlanEvent_id+"&action=cancelEventPlan";
                    $.ajax({
                        url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                            Display_Load();
                        },
                        success: function (html)
                        {
                            //alert(html);
                            window.location.href = '<?php echo $siteURL;?>event-log.php';
                        },
                        complete : function()
                        {
                            Hide_Load();
                        }
                    }); 
                }
            });
        }
        else
        {            
            var temp = new Array();
            temp = RequireArray.split(',');
            //alert(temp.length);
            for(i=0;i<temp.length;i++)
            {
                //alert(temp[i]); 
                var extrArray = parseInt(document.getElementById('PlanCareextras_'+temp[i]).value);
                //alert(extrArray);
                for(ja=0;ja<=extrArray;ja++)
                {
                    //alert(extrArray[ja]); 
                    var fromNextDate = '';
                    var toprevDate = '';
                    if(extrArray == 0)
                        countExtras = '0';
                    else
                    {
                        countExtras = extrArray[ja];
                        if(ja != 0)
                        {                            
                            fromNextDate = $("#eve_from_date_"+ja+"_"+temp[i]).val();
                            var prev = parseInt(ja)-1;
                            toprevDate = $("#eve_to_date_"+prev+"_"+temp[i]).val();                            
                        }
                    }
                    var fromDate = $("#eve_from_date_"+ja+"_"+temp[i]).val();
                    var toDate = $("#eve_to_date_"+ja+"_"+temp[i]).val();
                    var fromTime = $("#starttime_"+ja+"_"+temp[i]).val();
                    var toTime = $("#endtime_"+ja+"_"+temp[i]).val();
                    
                    if(fromDate == '')
                    {
                        bootbox.alert("<div class='msg-error'>Please select from date.</div>");
                        $("#eve_from_date_"+ja+"_"+temp[i]).focus();
                        return false;
                    }
                    if(toDate == '')
                    {
                        bootbox.alert("<div class='msg-error'>Please select to date.</div>");
                        $("#eve_to_date_"+ja+"_"+temp[i]).focus();
                        return false;
                    }
                    /*
                    if(toDate < fromDate)
                    {
                        bootbox.alert("<div class='msg-error'>To date should not be grater than from date. Please enter valid dates.</div>");
                        $("#eve_to_date_"+ja+"_"+temp[i]).focus();
                        return false;
                    }
                    */
                    if(fromTime == '')
                    {
                        bootbox.alert("<div class='msg-error'>Please select start time.</div>");
                        $("#starttime_"+ja+"_"+temp[i]).focus();
                        return false;
                    }
                    if(toTime == '')
                    {
                        bootbox.alert("<div class='msg-error'>Please select end time.</div>");
                        $("#endtime_"+ja+"_"+temp[i]).focus();
                        return false;
                    }
                   /* if(fromNextDate && toprevDate)
                    {
                        //if( (new Date(fromNextDate).getTime() > new Date(second).getTime()))
                        if(fromNextDate <= toprevDate)
                        {
                            bootbox.alert("<div class='msg-error'>Your to date should be grater than previous from date.</div>");
                            $("#eve_from_date_"+ja+"_"+temp[i]).focus();
                            return false;
                        }
                    }*/
                    
                }
            }
            var prompt_msg = "Are you sure to confirm estimated cost?";
            bootbox.confirm(prompt_msg, function (res) 
            {
               if(res==true)
               {
                $("#PlanofCareForm").ajaxForm({
                        beforeSend: function() 
                        {
                           Display_Load();
                        },
                        success: function (html)
                        {
                           // alert(html);
                             var result=html.trim();
                             //alert(result);     
                             //alert(temp[0]);
                             var newevent = $("#event_id").val();
                             planOfCare(newevent,2)
							 paymentsDetailsDivShow(newevent);
                             findprofessionalDivshow(newevent);
                             //$("#findProfessionalDiv").show();
                            bootbox.alert("<div class='msg-success'>Plan of care added successfully. Now you can assign professionals.</div>");
                        },
                        complete : function()
                        {
                           Hide_Load();
                        }
                    }).submit();
                }
            });  
        }
    }
	
	function paymentsDetailsDivShow(eventIDs)
	{
        var data1="event_id="+eventIDs;
        $.ajax({
            url: "include_payment_details.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               // Display_Load();
            },
            success: function (html)
            {
               //alert(html);
			    $("#PaymentDetailsDiv").show();
                $("#PaymentDetailsDiv").html(html);
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
                ChangeAjaxJs();
                $("#professionalContent").mCustomScrollbar({
                                    setHeight:350,
                                    //theme:"minimal-dark"
                            });
                //Hide_Load();
            },
            complete : function()
            {
               // Hide_Load();
            }
        });		
		
		
		
	}
    function findprofessionalDivshow(eventIDs)
    {
        var data1="event_id="+eventIDs;
        $.ajax({
            url: "include_find_professional.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               // Display_Load();
            },
            success: function (html)
            {
               //alert(html);
			    $("#findProfessionalDiv").show();
                $("#findProfessionalDiv").html(html);
                var allservSel = $("#serviceSelecAll").val();
                spltservies = allservSel.split(':,');
                //alert(allservSel);
                for(mk=0;mk<spltservies.length;mk++)
                {                   
                    funrangeslider(spltservies[mk]);
                }
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
                ChangeAjaxJs();
                $("#professionalContent").mCustomScrollbar({
                                    setHeight:350,
                                    //theme:"minimal-dark"
                            });
                //Hide_Load();
            },
            complete : function()
            {
               // Hide_Load();
            }
        });
    }
    function checkAddress()
    {
        var checkedAdd = $('#sameaddress').is(':checked'); 
        //alert(checkedAdd);
        var resAdd = $("#residential_address").val();
        if(checkedAdd == true)
        {                
            $("#permanant_address").val(resAdd);
        }
        else
            $("#permanant_address").val('');
    }
    function changePatientTab(value)
    {
        if(value == 'New')
        {
            $("#new").addClass('active');
            $("#existing").removeClass('active');
            $("#ExTab").removeClass('active');
            $("#NewTab").addClass('active');
            $("#NewPatienttab").html('UPDATE');
        }
    }
    function changeRightTab()
    {
        $("#PlanOfCareDiv").show();
        $("#RightSideDiv").hide();
        $("#SearchRightSideDiv").hide();
    }
    function ShareWithHCM(event_id)
    {
        var data1="event_id="+event_id+"&action=vw_share_with_hcm";
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
                $('#vw_professional').modal({backdrop: 'static',keyboard: false}); 
                $("#AllAjaxData").html(result);
                $("#specialty").keydown(function(event) 
                {
                    if (event.keyCode == 13) 
                    {
                        SearchRecord();
                        return false; 
                    }
                });                
                for (var selector in config) 
                {
                  $(selector).chosen(config[selector]);
                }
            },
            complete : function()
            {
               Hide_Load();
            }
        }); 
    }
    function SearchRecord()
    {
      // Getting Values
      var eventId=$('#event_id').val();
      var SearchKeyword="";
      if($('#employee_id').val())
      {
         SearchKeyword="employee_id="+$('#employee_id').val(); 
      }
      if($('#location_id').val())
      {
         SearchKeyword +="&location_id="+$('#location_id').val();  
      }
      if($('#specialty').val())
      {
        SearchKeyword +="&specialty="+$('#specialty').val();  
      }
      if(SearchKeyword)
      {
         // Display_Load();
          var data1=SearchKeyword+"&event_id="+eventId+"&action=load_share_event_table_content";
          //alert(data1);
          $.ajax({
            url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
                // Display_Load();
            },
            success: function (html)
            {
                var result=html.trim();
                //alert(html);
                $(".share_table_content").html(result);
              //  Hide_Load();
            },
            complete : function()
            {
               //Hide_Load();
            }
        }); 
      }
      else
      {
          ShareWithHCM(eventId);
      }
   }
    function ShareEventToHCM(employee_id,event_id)
    {
      if(employee_id && event_id) 
      {
          //  Display_Load();
            var data1="login_user_id=<?php echo $_SESSION['employee_id']; ?>&employee_id="+employee_id+"&event_id="+event_id+"&action=share_event_to_hcm";
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
                    if(result=='success')
                    {
                        bootbox.alert("<div class='msg-success'>Event shared successfully.</div>",function()
                        {
                           window.location="event-log.php"; 
                        }); 
                    }
                    else 
                    {
                        bootbox.alert("<div class='msg-error'>Error in shared event.</div>");
                    }
                },
                complete : function()
                {
                   Hide_Load();
                }
            });  
      }
    }
   function ViewbusyScheduled(professional_id)
   {
        var data1="professional_id="+professional_id+"&action=viewBusyScheduled";
       // alert(data1);
         $.ajax({
                url: "professional_schedule.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                  //  alert(html);
                    $('#vw_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);
                    $("#viewEventDetails .modal-body").mCustomScrollbar({
                                    setHeight:500,
                                    //theme:"minimal-dark"
                            });
                     $('[data-toggle="tooltip"]').tooltip();
                    //$('#vw_professional').modal('hide'); 
                },
                complete : function()
                {
                   Hide_Load();
                }
         });
   }
   function ViewScheduled(professional_id)
   {
        var data1="professional_id="+professional_id+"&action=viewProfessionalSchedule";
       // alert(data1);
         $.ajax({
                url: "professional_schedule.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                  //  alert(html);
                    $('#vw_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);

                    renderCalender(professional_id);
                    $("#viewEventDetails .modal-body").mCustomScrollbar({
                                    setHeight:500,
                                    //theme:"minimal-dark"
                            });
                     $('[data-toggle="tooltip"]').tooltip();
                    //$('#vw_professional').modal('hide'); 
                },
                complete : function()
                {
                   Hide_Load();
                }
         });
   }
   function renderCalender(professional_id)
   {
       var data1="professional_id="+professional_id+"&type=fetch&action=FetchProfessional";
           // alert(data1);
       $.ajax({
		url: 'professional_schedule.php',
                type: 'POST', // Send post data
                data: data1,
                async: false,
                cache: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function(s)
                {
                      //alert(s);
                    window.json_events = s;                
                    setTimeout('calenderIntialize()',2000);
                    //Popup_Hide_Load();
                },
                complete : function()
                {
                   Hide_Load();
                }
	});         
   }
   function calenderIntialize()
   {
       Display_Load();
       //alert(window.json_events);
       $('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},			
			eventLimit: true, // allow "more" link when too many events
                        events: JSON.parse(window.json_events),
                        utc: true			
		});
        Hide_Load();
   }
   
    function ViewEvent(event_id)
    {
        if(event_id)
        {
            var Caller_purpose_Id = $('#purpose_id').val(); // Used for consultant & follow up call.........
            var Consultant_Email=$('#familyDocemail_id').val();
            var data1="event_id="+event_id+"&Caller_purpose_Id="+Caller_purpose_Id+"&Consultant_Email="+Consultant_Email+"&action=vw_event";
           // alert(data1);
             $.ajax({
                    url: "event_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                      //  alert(html);
                        $('#vw_professional').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                                        setHeight:500,
                                        //theme:"minimal-dark"
                                });
                        $('#selectall').click(function() 
                        {
                            if($('#selectall').is(':checked'))
                            {
                                $('.case').prop('checked', true); 
                            }
                            else
                            {
                                $('.case').prop('checked', false); 
                            } 
                        });                   
                        $('[data-toggle="tooltip"]').tooltip();
                        //$('#vw_professional').modal('hide'); 
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
        }
    }
	function PrintReceipt(event_id)
    {
        if(event_id)
        {
            var Caller_purpose_Id = $('#purpose_id').val(); // Used for consultant & follow up call.........
            var Consultant_Email=$('#familyDocemail_id').val();
            var data1="event_id="+event_id+"&Caller_purpose_Id="+Caller_purpose_Id+"&Consultant_Email="+Consultant_Email+"&action=vw_event";
           // alert(data1);
             $.ajax({
                    url: "event_receipt_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                      //  alert(html);
                        $('#vw_professional').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                                        setHeight:500,
                                        //theme:"minimal-dark"
                                });
                        $('#selectall').click(function() 
                        {
                            if($('#selectall').is(':checked'))
                            {
                                $('.case').prop('checked', true); 
                            }
                            else
                            {
                                $('.case').prop('checked', false); 
                            } 
                        });                   
                        $('[data-toggle="tooltip"]').tooltip();
                        //$('#vw_professional').modal('hide'); 
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
        }
    }
	
    function downloadPDFReport(event_id,selected_block_id,selected_block_value,type)
    {
        var data1="event_id="+event_id+"&selected_block_id="+selected_block_id+"&selected_block_value="+selected_block_value;
        $.ajax({
            url: "include_download_pdf.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               Display_Load();
            },
            success: function (html)
            { 
                var dataRecipt=html;
                var maindata = {html:dataRecipt, event_id:+event_id,type :type};
                var siteurl='<?php echo $siteURL;?>';     
                $.ajax({
                    url: 'download_event_pdf.php',
                    async: false,
                    cache: false,
                    data: maindata,
                    type: 'POST',
                    beforeSend: function() 
                    {
                       Display_Load();
                    },
                    success: function(result) 
                    {//alert(result);
                        var w = location.href=siteurl+'download_event_pdf.php?export=1&file='+result; 
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
                });				  
            },
            complete : function()
            {
               Hide_Load();
            }
        }); 
    }
	function downloadPDFReceipt_payment(payment_id,eventid,type)
	{
		//alert(eventid);
		 var data1="payment_id="+payment_id+"&eventid="+eventid;
        $.ajax({
            url: "include_download_payment_pdf.php", type: "post", data: data1, cache: false,async: false,
           
            success: function (html)
            { 
                var dataRecipt=html;
                var maindata = {html:dataRecipt, eventid:eventid,type :type};
                var siteurl='<?php echo $siteURL;?>';     
                $.ajax({
                    url: 'download_event_payment_pdf.php',
                    async: false,
                    cache: false,
                    data: maindata,
                    type: 'POST',
                    beforeSend: function() 
                    {
                       Display_Load();
                    },
                    success: function(result) 
                    {//alert(result);
                        var w = location.href=siteurl+'download_event_payment_pdf.php?export=1&file='+result; 
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
                });				  
            },
            complete : function()
            {
               Hide_Load();
            }
        }); 
	}
	function downloadPDFReceipt(event_id,selected_block_id,selected_block_value,type)
    {
		//alert(selected_block_value);
        var data1="event_id="+event_id+"&selected_block_id="+selected_block_id+"&selected_block_value="+selected_block_value;
        $.ajax({
            url: "include_download_receipt_pdf.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
               Display_Load();
            },
            success: function (html)
            { 
                var dataRecipt=html;
                var maindata = {html:dataRecipt, event_id:+event_id,type :type};
                var siteurl='<?php echo $siteURL;?>';     
                $.ajax({
                    url: 'download_receipt_pdf.php',
                    async: false,
                    cache: false,
                    data: maindata,
                    type: 'POST',
                    beforeSend: function() 
                    {
                       Display_Load();
                    },
                    success: function(result) 
                    {   //alert(siteurl);
                        var w = location.href=siteurl+'download_receipt_pdf.php?export=1&file='+result; 
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
                });				  
            },
            complete : function()
            {
               Hide_Load();
            }
        }); 
    }
    function SendReportByEmail(event_id,selected_block_id,selected_block_value)
    {
        if(event_id)
        {
            var Consultant_Email=$('#familyDocemail_id').val();
            $('#vw_professional').modal('hide');
            var data1="event_id="+event_id+"&Consultant_Email="+Consultant_Email+"&selected_block_id="+selected_block_id+"&selected_block_value="+selected_block_value+"&action=vw_select_professional_for_email";
            //alert(data1);
             $.ajax({
                    url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        //alert(html);
                        $('#vw_select_professional').modal({backdrop: 'static',keyboard: false}); 
                        $("#AjaxData").html(html);
                        $("#frmSendEmail").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                        $('[data-toggle="tooltip"]').tooltip(); 
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
        } 
    }
    function EmailContent(event_id,selected_block_id,selected_block_value)
    {
        var email_id=$("#email_id").val();
        var email_msg=$("#email_msg").val();
        var consultant_id=$("#consultant_id").val();   
        
        if(email_id && email_msg)
        {
            var data1="event_id="+event_id+"&selected_block_id="+selected_block_id+"&selected_block_value="+selected_block_value;
                $.ajax({
                    url: "include_download_pdf.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var dataRecipt=html;
                        var maindata = {html:dataRecipt, event_id:+event_id};
                        var siteurl='<?php echo $siteURL;?>';     
                        $.ajax({
                        url: 'download_event_pdf.php',
                        data: maindata,
                        cache: false,
                        async: false,
                        type: 'POST',
                        beforeSend: function() 
                        {
                          // Display_Load();
                        },
                        success: function(result) 
                        {
                           // Getting File Name  
                            var file_nm=result;
                            var data1="event_id="+event_id+"&file_nm="+file_nm+"&email_id="+email_id+"&email_msg="+email_msg+"&consultant_id="+consultant_id+"&action=SendReportByEmail";
                            // alert(data1);
                              $.ajax({
                                     url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                                        beforeSend: function() 
                                        {
                                            Display_Load();
                                        },
                                        success: function (html)
                                        {
                                            result=html.trim();
                                            //  alert(result);
                                            if(result=='success')
                                            {
                                                 bootbox.alert("<div class='msg-success'>Event details are successfully send on email address.</div>",function()
                                                 {
                                                      $("#email_id,#email_msg").val('');
                                                      $('#vw_select_professional').hide();
                                                       window.location='event-log.php';
                                                 });
                                            }
                                            else
                                            {
                                                bootbox.alert("<div class='msg-success'>Event details are successfully send on email address.</div>",function()
                                                {
                                                    $("#email_id,#email_msg").val('');
                                                    $('#vw_select_professional').hide();
                                                     window.location='event-log.php';
                                                });
                                            }
                                        },
                                        complete : function()
                                        {
                                            Hide_Load();
                                        }

                              }); 
                        },
                        complete : function()
                        {
                          // Hide_Load();
                        }
                        });				
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
                });
        }
        else 
        {
            bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
            {
               $("#email_id").focus();
            });
        }  	
    }
    function serachProfessional(service_id)
    {            
        changePageProfessional('ProfessionalIncludeDiv_'+service_id,'include_include_professional.php','','','','',service_id);
        //$("#jobSummaryDiv").hide(); 
    }    
    function SubmitFindProfessional(service_id)
    {
        var i;
        var chks = document.getElementsByName('professionals_'+service_id+'[]');
        var hasChecked = false;
        for (i = 0; i< chks.length; i++)
        {
            if (chks[i].checked)
            {
                hasChecked = true;
                break;
            }
        }
        if (hasChecked == false)
        {
            bootbox.alert("<div class='msg-error'>Please select at least one record to do operation</div>");
            //alert("Please select at least one record to do operation");
            return false;
        }
        else
        {
            // Check is it confirm estimation cost radio button selected 
            if($("input:radio[name='confirmEstimatedBox']").is(":checked")==true) 
            { 
                //alert(service_id);
                 $("#professionalForm_"+service_id).ajaxForm({
                        beforeSend: function() 
                        {
                            Display_Load();
                        },
                        success: function (html)
                        {
                           //alert(html);
                             $("#JobSummaryCont").show();
                             serachProfessional(service_id);
                             // Get Job Summary Details
                             bootbox.alert("<div class='msg-success'>Professionals assigned successfully. Now you can create job summary.</div>",function()
                             {
                                 JobSummaryDtls();
                             });

                        },
                        complete : function()
                        {
                           Hide_Load();
                        }
                    }).submit();
            }
            else 
            {
                bootbox.alert("<div class='msg-error'>Please select confirm estimation cost first</div>");
            } 
        }        
    }
    function JobSummaryDtls()
    {
        var event_id=$("#profes_event_id").val()
        var data1="event_id="+event_id;
        $.ajax({
            url: "include_job_summary.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
                Display_Load();
            },
            success: function (html)
            {
                var result=html.trim();
                //alert(result);
                $("#jobSummaryDiv").show();
                $("#jobSummaryDiv").html(result);
                $('#parentHorizontalTab1').easyResponsiveTabs({
                    type: 'default', //Types: default, vertical, accordion
                    width: 'auto', //auto or any width like 600px
                    fit: true, // 100% fit in a container
                    tabidentify: 'hor_2', // The tab groups identifier
                    activate: function(event) { // Callback function if tab is switched
                            var $tab = $(this);
                            var $info = $('#nested-tabInfo2');
                            var $name = $('span', $info);
                            $name.text($tab.text());
                            $info.show();
                    }
                });   
            },
            complete : function()
            {
               Hide_Load();
            }
       });   
    }
    function SubmitJobSummary(type,service_id)
    {
        var process = 'yes';
        var eventProfID = $("#event_professional_id_"+service_id).val();
        var IDS = $("#profeServID_"+service_id).val();
        splitForProf = IDS.split(':,');
        for(jk=0;jk<splitForProf.length;jk++)
        {
            if($("#reporting_instruction_"+service_id+"_"+splitForProf[jk]).val() == '')
            {
                bootbox.alert('<div class="msg-error">Please enter reporting instruction.</div>',function()
                {
                    $("#reporting_instruction_"+service_id+"_"+splitForProf[jk]).focus();
                    process = 'no';
                    return false;
                });
                
            }
        }
        if(process == 'yes')
        {
            $('#clicked_btn_type_'+service_id).val(type);
            $("#jobSummaryDiv_"+service_id).ajaxForm({
                beforeSend: function() 
                {
                   Display_Load();
                },
               success: function (html)
               {
                    var result=html.trim();
                    //alert(result);
                    bootbox.alert('<div class="msg-success">Job summary details sent successfully</div>', function() 
                    {
                        window.location='event-log.php';
                    });
                    //setTimeout("window.location='event-log.php';",1500);
               },
                complete : function()
                {
                   Hide_Load();
                }
            }).submit();
        }
    }
    function ChangeAllCheckboxes(service_id)
    {
        if($('#selectall_'+service_id).is(':checked'))
        {
            $('#selectall_'+service_id).prop('checked', true);
            $('.case_'+service_id).prop('checked', true); 
        }
        else 
        {
            $('#selectall_'+service_id).prop('checked', false);
            $('.case_'+service_id).prop('checked', false);
        }
    }
    function SubmitJobClosure()
    {
        if($("input:radio[name=service_rendered]").is(":checked") && $("#service_date").find('option:selected').val())
        {
           // if($("input:radio[name=is_file_upload]").is(":checked"))
         //   {
                if($('input:radio[name=is_file_upload]:checked').val()=='1')
                {
                    if($('.jobclosurefile').val()=='')
                    {
                        bootbox.alert('<div class="msg-error">Please upload job closure file.</div>');
                    }
                    else 
                    {
                         $("#frmjobClosure").ajaxForm({
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
                                    //bootbox.alert('<div class="msg-success">Job closure details added successfully.</div>');
                                    bootbox.alert('<div class="msg-success">Job closure details added successfully.</div>', function() 
                                    {
                                       window.location='event-log.php';
                                    });
                                   // setTimeout("window.location='event-log.php';",1500);
                                }
                                else if(result=='ImageFileOnly')
                                {
                                    bootbox.alert('<div class="msg-error">Invalid file extension of job closure, valid extension are (.bmp,.gif,.png,.jpg,.jpeg).</div>'); 
                                }
                                else if(result=='FileUploadError')
                                {
                                   bootbox.alert('<div class="msg-error">Error in file uplaoding.</div>');  
                                }
                                else 
                                {
                                   bootbox.alert('<div class="msg-error">Please check all fields validation.</div>'); 
                                }
                            },
                            complete : function()
                            {
                               Hide_Load();
                            }
                        }).submit();
                    }
                }
                else 
                {
                    $("#frmjobClosure").ajaxForm({
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
                                //bootbox.alert('<div class="msg-success">Job closure details added successfully.</div>');
                                bootbox.alert('<div class="msg-success">Job closure details added successfully.</div>', function() 
                                {
                                   window.location='event-log.php';
                                });
                               // setTimeout("window.location='event-log.php';",1500);
                            }
                            else if(result=='ImageFileOnly')
                            {
                                bootbox.alert('<div class="msg-error">Invalid file extension of job closure, valid extension are (.bmp,.gif,.png,.jpg,.jpeg).</div>'); 
                            }
                            else if(result=='FileUploadError')
                            {
                               bootbox.alert('<div class="msg-error">Error in file uplaoding.</div>');  
                            }
                            else 
                            {
                               bootbox.alert('<div class="msg-error">Please check all fields validation.</div>'); 
                            }
                        },
                        complete : function()
                        {
                           Hide_Load();
                        }
                    }).submit();
                }
          //  }
        }
        else 
        {
            bootbox.alert('<div class="msg-error">Please select serice date and service rendered option.</div>');
        }   
    }
    function OpenJobClosureDiv(event_id,service_date)
    {
        var Edit_CallerId=$("#Edit_CallerId").val();
        var eventIDForClosure=$("#eventIDForClosure").val();
        var professional_id=$("#choose_professional_id").val(); 
        var _URL = window.URL || window.webkitURL;
        var data="event_id="+event_id+"&professional_id="+professional_id+"&eventIDForClosure="+eventIDForClosure+"&Edit_CallerId="+Edit_CallerId+"&action=chk_event_allocated_professional";
       // alert(data);
        $.ajax({
                url: "event_ajax_process.php", type: "post", data: data, cache: false,async: false,
                beforeSend: function() 
                {
                },
                success: function (html)
                { 
                    var result=html.trim();
                   // alert(result);
                    if(result=='ProfessionalNotExists')
                    {
                       bootbox.alert('<div class="msg-error">This professional is not assigned to this event please choose correct one !</div>', function() 
                       {
                          window.location='event-log.php';
                       });
                       //setTimeout("window.location='event-log.php';",1500);
                    }
                    else if(result=='MissingParameter')
                    {
                       bootbox.alert('Please check professional is selected'); 
                    }
                    else 
                    {
                        // If Service Date Present 
                        var data1="";
                        if(service_date && professional_id)
                        {
                            data1="event_id="+event_id+"&eventIDForClosure="+eventIDForClosure+"&Edit_CallerId="+Edit_CallerId+"&service_date="+service_date+"&professional_id"+professional_id;
                        }
                        else 
                        {
                           data1="event_id="+event_id+"&eventIDForClosure="+eventIDForClosure+"&Edit_CallerId="+Edit_CallerId;
                        }
                        //alert(data1);
                         $.ajax({
                                url: "include_job_closure.php", type: "post", data: data1, cache: false,async: false,
                                beforeSend: function() 
                                {
                                    Display_Load();
                                },
                                success: function (html)
                                {
                                   // alert(html);
                                   var result =html.trim();
                                    $("#JobClosureDiv").show();
                                   // $(result).find('#upload_file_content').hide();
                                    $("#JobClosureDiv").html(result);
                                    var service_date=$("#service_date").val();
                                    if(service_date)
                                    {
                                        $("#job_closure_content").show();
                                    } 
                                    else 
                                    {
                                      // bootbox.alert("<div class='msg-error'>Please select service date first</div>");  
                                       $("#job_closure_content").hide();
                                    }
                                    $('input[name="service_rendered"]').on('change', function() 
                                    {
                                        var service_date=$("#service_date").val();
                                        if(service_date)
                                        {
                                           if($('input[name="is_file_upload"]:checked').val()=='1')
                                           {
                                               $("#job_closure_content").hide();
                                           }
                                           else 
                                           {
                                               $('.jobclosurefile').val('');
                                               $("#job_closure_content").show();
                                           }  
                                        } 
                                        else 
                                        {
                                           bootbox.alert("<div class='msg-error'>Please select service date first</div>");
                                           $("#job_closure_content").hide();
                                        }
                                    });
                                    // If Job closure image file available then 
                                    $('input[name="is_file_upload"]').on('change', function() 
                                    {
                                        var service_date=$("#service_date").val();
                                        if(service_date)
                                        {
                                            var radioValue = $('input[name="is_file_upload"]:checked').val(); 
                                            if(radioValue)
                                            {
                                                if(radioValue=='1')
                                                {
                                                    $("#upload_file_content").show();
                                                    $("#job_closure_content").hide();
                                                }
                                                if(radioValue=='2')
                                                {
                                                    $("#upload_file_content").hide();
                                                    $("#job_closure_content").show();
                                                }   
                                            }   
                                        }
                                        else 
                                        {
                                           bootbox.alert("<div class='msg-error'>Please select service date first</div>"); 
                                        }     
                                   });
                                    $(document).on("change", ".jobclosurefile", function()
                                    {
                                        var logo_file,logo_img;
                                        logo_file = $('.jobclosurefile')[0].files[0];
                                        var f_type = "job closure";
                                        var f_ext = $(".jobclosurefile").val().split('.').pop().toLowerCase();
                                        if (window.File && window.FileReader && window.FileList && window.Blob)
                                        {
                                             var f_size = $('.jobclosurefile')[0].files[0].size;
                                        }
                                        var f_valid_format =['bmp','gif','png','jpg','jpeg']; 
                                        var validate_file = chk_file_validation(f_type,f_size,f_ext,f_valid_format);

                                        if(validate_file !="success")
                                        {
                                          bootbox.alert("<div class='msg-error'>"+validate_file+"</div>");
                                          $('.jobclosurefile').val('');
                                          return false;
                                        }
                                        else
                                        {
                                         logo_img = new Image();
                                         logo_img.onload = function() 
                                         {
                                            var img_width = this.width;
                                            var img_height = this.height;

                                            if(img_width<600 && img_height<600)
                                            {
                                                bootbox.alert("Your job closure couldn't be uploaded. file should be greater than 600*600");
                                                $('.jobclosurefile').val('');
                                            }
                                            else
                                            {
                                                 return true;
                                            }
                                         };
                                         logo_img.src = _URL.createObjectURL(logo_file);
                                        }
                                    });
                                    for (var selector in config) 
                                    {
                                      $(selector).chosen(config[selector]);
                                    } 
                                },
                                complete : function()
                                {
                                   Hide_Load();
                                }
                         }); 
                    }
                },
                complete : function()
                {
                }
         });  
    }
    function GetJobClosureDtls(service_date)
    {
       var professional_id=$("#choose_professional_id").val(); 
       var event_id=$("#event_id").val();
       if(service_date && professional_id)
       {
          OpenJobClosureDiv(event_id,service_date); 
       }
       else 
       {
          OpenJobClosureDiv(event_id);
       }
    }
    function OpenFeedbackDiv(event_id,service_date)
    {
        var callerID = $("#Edit_CallerId").val();
        var eventIDForClosure=$("#eventIDForClosure").val();
        // If Service Date Present 
        var data1="";
        if(service_date)
        {
            data1="event_id="+event_id+"&eventIDForClosure="+eventIDForClosure+"&caller_id="+callerID+"&feedback_service_date="+service_date;
        }
        else 
        {
           data1="event_id="+event_id+"&eventIDForClosure="+eventIDForClosure+"&caller_id="+callerID; 
        }
         $.ajax({
                url: "include_feedback.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                    //alert(html);
                    $("#FeedbackDivs").show();
                    $("#FeedbackDivs").html(html);
                    
                   // var service_date=$("#service_date").val();
                   
                   if(service_date)
                   {
                       $("#FeedbackContent").show();
                   }
                   else 
                   {
                       $("#FeedbackContent").hide();
                      // bootbox.alert('<div class="msg-error">Select service date first</div>');
                   }
                    $('.basic').jRating({
                        decimalLength : 0,
                            canRateAgain : true,
                            nbRates : 10,
                            rateMax :10,
                            onClick : function(element,rate) {
                                        //alert(rate);
                                        //alert(element.id);
                                        var newrate = element.id;
                                        $("#rating_val_"+newrate).val(rate);
                                       }
                                     });                                     
                    for (var selector in config) 
                    {
                      $(selector).chosen(config[selector]);
                    } 
                },
                complete : function()
                {
                   Hide_Load();
                }
         });  
    }
    function GetFeedbackDtls(service_date)
    { 
       var event_id=$("#feedbackEventId").val();
       //alert(event_id);
       //alert(service_date);
       if(service_date && event_id)
       {
          OpenFeedbackDiv(event_id,service_date); 
       }
       else 
       {
          OpenFeedbackDiv(event_id);
       } 
    }
    function SubmitFeedback()
    {
        if($("#formfeedback").validationEngine('validate'))
        {
            $("#formfeedback").ajaxForm({
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                     var result=html.trim();
                     //alert(result);
                     //bootbox.alert('Feedback information added successfully.');
                     bootbox.alert('<div class="msg-success">Feedback information added successfully.</div>', function() {
                                     window.location='event-log.php';
                                    });
                     //setTimeout("window.location='event-log.php';",1500);
                },
                complete : function()
                {
                   Hide_Load();
                }
            }).submit();
        }
        else 
        {
            bootbox.alert('<div class="msg-error">Please select serice date.</div>');
        }
    }
    function ViewEventActions(main_event_id,purpose_event_id,type)
    {
        var purpose_id = $('#purpose_id').val();
        var caller_id = $('#Edit_CallerId').val();
        purpose_event_id = $('#eventIDForClosure').val();
        var Consultant_Email=$('#familyDocemail_id').val();
        $("#AllAjaxData").css({ opacity: 0.5 });
        var data1="main_event_id="+main_event_id+"&type="+type+"&purpose_event_id="+purpose_event_id+"&purpose_id="+purpose_id+"&caller_id="+caller_id+"$Consultant_Email="+Consultant_Email+"&action=saveEventDetails";           
        //alert(data1);
        $.ajax({
                url: "ajax_public_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                    // Getting All Checked Checkbox values 
                    var selected_block_id = [];
                    var selected_block_value = [];
                    $("input:checkbox[class=case]:checked").each(function () 
                    {
                        selected_block_id.push($(this).attr("id"));
                        selected_block_value.push($(this).attr("value"));
                    });
                   if(type !='continue')
                   {
                        if(selected_block_value !='null' && selected_block_value.length !=0 && selected_block_value !='undefined')
                        {
                             if(type == 'download')
                             {
                                 downloadPDFReport(main_event_id,selected_block_id,selected_block_value,type);
                             }
                             else if(type == 'email')
                             {
                                 SendReportByEmail(main_event_id,selected_block_id,selected_block_value);
                             }
                             else
                             {
                                 window.location="event-log.php";
                             }
                         }
                         else 
                         {
                             bootbox.alert('<div class="msg-error">You are not selecting any option,please select atleast one oprion.</div>');
                         }
                   }
                   else
                   {
                       window.location="event-log.php";
                   }
                   //$('#vw_professional').modal('hide'); 
                   $("#AllAjaxData").css({ opacity: 1 });
                },
                complete : function()
                {
                   Hide_Load();
                }
         }); 
    }
	function download_receipt(payment_id,eventid)
	{
		//alert(payment_id);
		var type='download';
		if(type == 'download')
        {
			//alert(type);
             downloadPDFReceipt_payment(payment_id,eventid,type);
        }
	}
	function ViewReceiptActions(main_event_id,purpose_event_id,type)
    {
        var purpose_id = $('#purpose_id').val();
        var caller_id = $('#Edit_CallerId').val();
        purpose_event_id = $('#eventIDForClosure').val();
        var Consultant_Email=$('#familyDocemail_id').val();
        $("#AllAjaxData").css({ opacity: 0.5 });
        var data1="main_event_id="+main_event_id+"&type="+type+"&purpose_event_id="+purpose_event_id+"&purpose_id="+purpose_id+"&caller_id="+caller_id+"$Consultant_Email="+Consultant_Email+"&action=saveEventDetails";           
        //alert(data1);
        $.ajax({
                url: "ajax_public_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                    // Getting All Checked Checkbox values 
                    var selected_block_id = [];
                    var selected_block_value = [];
                    $("input:checkbox[class=case]:checked").each(function () 
                    {
                        selected_block_id.push($(this).attr("id"));
                        selected_block_value.push($(this).attr("value"));
                    });
                   if(type !='continue')
                   {
                        if(selected_block_value !='null' && selected_block_value.length !=0 && selected_block_value !='undefined')
                        {
                             if(type == 'download')
                             {
                                 downloadPDFReceipt(main_event_id,selected_block_id,selected_block_value,type);
                             }
                             else if(type == 'email')
                             {
                                 SendReportByEmail(main_event_id,selected_block_id,selected_block_value);
                             }
                             else
                             {
                                 window.location="event-log.php";
                             }
                         }
                         else 
                         {
                             bootbox.alert('<div class="msg-error">You are not selecting any option,please select atleast one oprion.</div>');
                         }
                   }
                   else
                   {
                       window.location="event-log.php";
                   }
                   //$('#vw_professional').modal('hide'); 
                   $("#AllAjaxData").css({ opacity: 1 });
                },
                complete : function()
                {
                   Hide_Load();
                }
         }); 
    }
	
    function add_more_unit_medicine()
    {
        var i = parseInt(document.getElementById('extras').value);
        if(i==0)
        {
            i=1;
        }
        else
        {
            i= parseInt(i)+1;
        }
        document.getElementById('extras').value= i;
        var next = parseInt(i)+1;
        var curr_div = "div_"+i;
        if(document.getElementById(curr_div).style.display === 'none')
        {
            document.getElementById(curr_div).style.display = 'block';
        }
        else
        {
            var data1="curr_div="+i;
            $.ajax({
                url: "event_ajax_process.php?action=AddUnitMedicineRow", type: "post", data: data1, cache: false,async: false,
                  beforeSend: function() 
                  {
                      Display_Load();
                  },
                  success: function (html)
                  {
                   // alert(html);
                   document.getElementById(curr_div).innerHTML = html;
                      for (var selector in config) 
                      {
                        $(selector).chosen(config[selector]);
                      }
                  },
                  complete : function()
                  {
                     Hide_Load();
                  }
            });               
        }
    }
    function del_more_unit_medicine()
    {
        var j=document.getElementById('extras').value;
        if(j != 0)
        {
           Display_Load();
           var curr_div = "div_"+j;
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
           document.getElementById('extras').value=previouss;
          // $("#unit_medicine_id"+j).val('');
           $('#unit_medicine_id'+j)[0].selectedIndex = 0;
           $("#unit_medicine_quantity"+j).val('');
           Hide_Load();
        } 
    }
    function add_more_non_unit_medicine()
    {
        var i = parseInt(document.getElementById('extras_2').value);
        if(i==0)
        {
            i=1;
        }
        else
        {
            i= parseInt(i)+1;
        }
        document.getElementById('extras_2').value= i;
        var next = parseInt(i)+1;
        var curr_div = "non_div_"+i;
       // alert(curr_div);
        if(document.getElementById(curr_div).style.display === 'none')
        {
            document.getElementById(curr_div).style.display = 'block';
        }
        else
        {
            var data1="curr_div="+i;
            $.ajax({
                url: "event_ajax_process.php?action=AddNonUnitMedicineRow", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                    //alert(html);
                    document.getElementById(curr_div).innerHTML = html;
                    for (var selector in config) 
                    {
                      $(selector).chosen(config[selector]);
                    }
                },
                complete : function()
                {
                   Hide_Load();
                }
            });               
        }
    }
    function del_more_non_unit_medicine()
    {
        var j=document.getElementById('extras_2').value;
        if(j != 0)
        {
           Display_Load();
           var curr_div = "non_div_"+j;
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
           document.getElementById('extras_2').value=previouss;
           $('#non_unit_medicine_id'+j)[0].selectedIndex = 0;
           $("#non_unit_medicine_quantity"+j).val('');
           Hide_Load();
        } 
    }
    function add_more_unit_consumable()
    {
        var i = parseInt(document.getElementById('consumable_extras').value);
        if(i==0)
        {
            i=1;
        }
        else
        {
            i= parseInt(i)+1;
        }
        document.getElementById('consumable_extras').value= i;
        var next = parseInt(i)+1;
        var curr_div = "consumable_unit_div_"+i;
        if(document.getElementById(curr_div).style.display === 'none')
        {
            document.getElementById(curr_div).style.display = 'block';
        }
        else
        {
            var data1="curr_div="+i;
          $.ajax({
              url: "event_ajax_process.php?action=AddUnitConsumableRow", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                   document.getElementById(curr_div).innerHTML = html;
                   for (var selector in config) 
                    {
                      $(selector).chosen(config[selector]);
                    }
                },
                complete : function()
                {
                   Hide_Load();
                }
          });               
        }
    }
    function del_more_unit_consumable()
    {
        var j=document.getElementById('consumable_extras').value;
        if(j != 0)
        {
           Display_Load();
           var curr_div = "consumable_unit_div_"+j;
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
           document.getElementById('consumable_extras').value=previouss;
           $('#unit_consumable_id'+j)[0].selectedIndex = 0;
           $("#unit_consumable_quantity"+j).val('');
           Hide_Load();
        } 
    }
    function add_more_non_unit_consumable()
    {
        var i = parseInt(document.getElementById('non_unit_consumable_extras').value);
        if(i==0)
        {
            i=1;
        }
        else
        {
            i= parseInt(i)+1;
        }
        document.getElementById('non_unit_consumable_extras').value= i;
        var next = parseInt(i)+1;
        var curr_div = "non_unit_consumable_div_"+i;
        if(document.getElementById(curr_div).style.display === 'none')
        {
            document.getElementById(curr_div).style.display = 'block';
        }
        else
        {
            var data1="curr_div="+i;
            $.ajax({
                url: "event_ajax_process.php?action=AddNonUnitConsumableRow", type: "post", data: data1, cache: false,async: false,
                  beforeSend: function() 
                  {
                    Display_Load();
                  },
                  success: function (html)
                  {
                     // alert(html);
                      document.getElementById(curr_div).innerHTML = html;
                      for (var selector in config) 
                        {
                          $(selector).chosen(config[selector]);
                        }
                  },
                  complete : function()
                  {
                     Hide_Load();
                  }
            });               
        }
    }
    function del_more_non_unit_consumable()
    {
        var j=document.getElementById('non_unit_consumable_extras').value;
        if(j != 0)
        {
           Display_Load();
           var curr_div = "non_unit_consumable_div_"+j;
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
           document.getElementById('non_unit_consumable_extras').value=previouss;
           $('#non_unit_consumable_id'+j)[0].selectedIndex = 0;
           $("#non_unit_consumable_quantity"+j).val('');
           Hide_Load();
        } 
    }
    function delete_consumption_option(val,event_id)
    {
      var recordId=val;
      var service_date=$("#service_date").val();
      if(recordId && service_date)
      {
          bootbox.confirm("Are you sure you want to delete this record ?", function (res) 
          {
                if(res==true)
                {
                    var data="consumption_id="+recordId;
                    // alert(data1);
                     $.ajax({
                         url: "event_ajax_process.php?action=delete_consumption_option", type: "post", data: data, cache: false,async: false,
                         beforeSend: function() 
                         {
                            Display_Load();
                         },
                         success: function (html)
                         {
                             var result=html.trim();
                             if(result=='success')
                             {
                                 var Edit_CallerId=$("#Edit_CallerId").val();
                                 var eventIDForClosure=$("#eventIDForClosure").val();
                                 var professional_id=$("#choose_professional_id").val(); 
                                 var data1="event_id="+event_id+"&eventIDForClosure="+eventIDForClosure+"&Edit_CallerId="+Edit_CallerId+"&service_date="+service_date+"&professional_id"+professional_id;
                                //alert(data1);
                                 $.ajax({
                                        url: "include_job_closure.php", type: "post", data: data1, cache: false,async: false,
                                        beforeSend: function() 
                                        {
                                            Display_Load();
                                        },
                                        success: function (htmlData)
                                        {
                                            var ResData=htmlData.trim();
                                            $("#JobClosureDiv").html(ResData);
                                            for (var selector in config) 
                                            {
                                              $(selector).chosen(config[selector]);
                                            }
                                        },
                                        complete : function()
                                        {
                                           Hide_Load();
                                        }
                                 }); 
                             }
                             else if(result=='error')
                             {
                                bootbox.alert('<div class="msg-error">Error in deleting option .</div>'); 
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
    function validate_baseline(field_name,value)
    {
       if(field_name && value)
       {
           if(field_name=='temprature')
           {
               if(value>=0 && value<=110)
               {
                   return true;
               }
               else 
               {
                  bootbox.alert('<div class="msg-error">Invalid temprature value</div>',function()
                  {
                       $("#"+field_name).val("");
                       $("#"+field_name).focus();
                  });
               }
           }
           if(field_name=='bsl')
           {
               if(value>=0 && value<=500)
               {
                   return true;
               }
               else 
               {
                   bootbox.alert('<div class="msg-error">Invalid TBSL value</div>',function()
                   {
                       $("#"+field_name).val("");
                       $("#"+field_name).focus(); 
                   });
               }
           }
           if(field_name=='pulse')
           {
               if(value>=0 && value<=300)
               {
                   return true;
               }
               else 
               {
                  bootbox.alert('<div class="msg-error">Invalid pulse value</div>',function()
                  {
                      $("#"+field_name).val("");
                      $("#"+field_name).focus();
                  });
                  
               }
                   
           }
           if(field_name=='spo2')
           {
               if(value>=0 && value<=100)
               {
                   return true;
               }
               else
               {
                  bootbox.alert('<div class="msg-error">Invalid SPO2 value</div>',function()
                  {
                      $("#"+field_name).val("");
                      $("#"+field_name).focus();
                  });
                  
               }
           }
           if(field_name=='rr')
           {
               if(value>=0 && value<=40)
               { 
                   return true;
               }
               else 
               {
                  bootbox.alert('<div class="msg-error">Invalid RR value</div>',function()
                  {
                      $("#"+field_name).val("");
                      $("#"+field_name).focus();
                  }); 
               }
           }
           if(field_name=='gcs_total')
           {
               if(value>=3 && value<=15)
               {
                  return true;
               }
               else
               {
                  bootbox.alert('<div class="msg-error">Invalid GCS Total value</div>',function()
                  {
                      $("#"+field_name).val("");
                      $("#"+field_name).focus();
                  });
               }
           }
           if(field_name=='high_bp')
           {
               if(value>=0 && value<=300)
               {
                   return true;
               }
               else
               {
                  bootbox.alert('<div class="msg-error">Invalid  high BP value</div>',function()
                  {
                      $("#"+field_name).val("");
                      $("#"+field_name).focus();
                  });
               }
           }
           if(field_name=='low_bp')
           {
               if(value>=0 && value<=300)
               {
                  return true;
               }
               else
               {
                  bootbox.alert('<div class="msg-error">Invalid Low BP value</div>',function()
                  {
                     $("#"+field_name).val("");
                     $("#"+field_name).focus();
                  }); 
               } 
           } 
           
       }
    }
    function funrangeslider(value)
    {
            $('.slider-location_'+value).jRange({
                from: 0,
                to: 50,
                step: 1,
                round: 1, 
                scale: [0,10,20,30,40,50],
                format: '%s',
                width: 300,
                showLabels: true,
                isRange : true,
                onstatechange:function(event){
                    console.log(event);
                  //alert(event);
                },
                ondragendcustom:function(event){
                    serachProfessional(value);
                }
            });
            
            $('.range-slider').jRange({
                from: 0,
                to: 45,
                step: 1,
                round: 1, 
                scale: [0,5,10,15,20,25,30,35,40,45],
                format: '%s',
                width: 300,
                showLabels: true,
                isRange : true,
                onstatechange:function(event){
                    console.log(event);
                  //alert(event);
                },
                ondragendcustom:function(event){
                    serachProfessional(value);
                }
            });
    } 
    function filter_by_option()
    {
        var filter_by=document.getElementById('filter_by').value;
        document.getElementById("event_from_date_service").value = '';
        document.getElementById("event_to_date_service").value = '';
        document.getElementById("event_from_date").value = '';
        document.getElementById("event_to_date").value = '';
         
        if(filter_by==1)
        {
            $("#filter_by_service_date").hide(); 
            $("#filter_by_added_date").show(); 
        }
        if(filter_by==2)
        {
            $("#filter_by_added_date").hide();
            $("#filter_by_service_date").show(); 
        }
    }
</script>

<script src="js/jRating.jquery.js" type="text/javascript"></script>
  <link rel="stylesheet" href="css/jRating.jquery.css" type="text/css" />
<?php
if($EID)
{
    ?>
    <script type="text/javascript">
        ChangePurposeCall(<?php echo $EditedResponseArr['purpose_id'];?>);
    </script>
<?php
  if($EditedResponseArr['patient_id'])
  {
?>
<script type="text/javascript">
    changePatientTab('New');
    $("a#existingTabHd").attr("href", "javascript:void(0);");
</script>
<?php
  }
$selectExistPlanofcare = "select distinct service_id from sp_event_requirements where event_id = '".$EID."' and status = '1' ";
if(mysql_num_rows($db->query($selectExistPlanofcare)))
{
    ?>
    <script type="text/javascript">
        changeRightTab('New');
    </script>
<?php
    $valAllServReq = $db->fetch_all_array($selectExistPlanofcare);
    foreach($valAllServReq as $key=>$valSerRequirement)
    {
        $service_id = $valSerRequirement['service_id'];
        ?>
    <script type="text/javascript">
        $('#sub_service_id_multiselect_<?php echo $service_id;?>').multiselect({enableFiltering: true, 
                    enableCaseInsensitiveFiltering: true});
        funrangeslider('<?php echo $service_id;?>');
    </script>
<?php
    }
}

$selectExtPlanSubmit = "select plan_of_care_id from sp_event_plan_of_care where event_id = '".$EID."' and status = '1' ";
if(mysql_num_rows($db->query($selectExtPlanSubmit)))
{
    ?>
    <script type="text/javascript">
        $("#PaymentDetailsDiv").show(); 
    </script>
	<?php
	
    ?>
    <script type="text/javascript">
        $("#findProfessionalDiv").show(); 
    </script>
<?php
}
$selectExtProf = "select event_professional_id from sp_event_professional where event_id = '".$EID."' and status = '1' ";
if(mysql_num_rows($db->query($selectExtProf)))
{
    ?>
    <script type="text/javascript">
        $("#jobSummaryDiv").show();
        //$(".planofcaredivcl").hide();
    </script>
<?php
}
 $selectExtProfjob = "select job_summary_id from sp_event_job_summary where event_id = '".$EID."' and status = '1' ";
if(mysql_num_rows($db->query($selectExtProfjob)))
{
    ?>
    <script type="text/javascript">
        $("#jobSummaryDiv").show();
        $(".planofcaredivcl").hide();
        $(".moreOptions").hide();
        $(".readonly").prop('readonly', true);
        $(".readonly").removeClass().addClass('form-control readonly');
       $("#ProfSubmit").hide();
    </script>
<?php
}

// Check is it job closure is submited or not 
$chk_job_closure_sql="SELECT job_closure_id FROM sp_job_closure WHERE event_id = '".$EID."' AND status = '1'";
if(mysql_num_rows($db->query($chk_job_closure_sql)))
{
    ?>
    <script type="text/javascript">
        $("#ProfSubmit").hide();
        $("#sms").hide();
        $("#email").hide();
        $("#call").hide();
    </script>
<?php
}

}?>
    
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
    function addMorePlanCare(event_requirement_id,service_type)
    {
        var i = parseInt(document.getElementById('PlanCareextras_'+event_requirement_id).value);
        if(i==0)
        {
            i=1;
        }
        else
        {
            i= parseInt(i)+1;
        }
        document.getElementById('PlanCareextras_'+event_requirement_id).value= i;
        var next = parseInt(i)+1;
        var curr_div = "div_"+i+"_"+event_requirement_id;
        if(document.getElementById(curr_div).style.display === 'none')
        {
            document.getElementById(curr_div).style.display = 'block';
        }
        else
        {
            var data1="event_requirement_id="+event_requirement_id+"&event_service_type="+service_type+"&curr_div="+i;
            $.ajax({
              url: "ajax_public_process.php?action=AddMorePlan", type: "post", data: data1,cache: false,async: false,
                beforeSend: function() 
                {
                  Display_Load();
                },
                success: function (html)
                {
                    //alert(html);
                  document.getElementById(curr_div).innerHTML = html;
                  //var date1 = $("#eve_to_date_0_88").datepicker('getDate');
                  //$("#eve_from_date_"+i+"_"+event_requirement_id).datepicker({ minDate:new Date(Date.parse(date1)), dateFormat: 'dd-mm-yy'});
                  datepickerVal(i);
                  //minval = $("#datepicker_eve_to_0").val();
                  return false;
                },
                complete : function()
                {
                   Hide_Load();
                }
          });               
        }
    }
	function addMorePayments(event_requirement_id,service_type)
    {
        var i = parseInt(document.getElementById('Paymentextras_'+event_requirement_id).value);
        if(i==0)
        {
            i=1;
        }
        else
        {
            i= parseInt(i)+1;
        }
		alert(i);
        document.getElementById('Paymentextras_'+event_requirement_id).value= i;
        var next = parseInt(i)+1;
        var curr_div = "div_"+i+"_"+event_requirement_id;
		alert(curr_div);
        if(document.getElementById(curr_div).style.display === 'none')
        {
            document.getElementById(curr_div).style.display = 'block';
        }
        else
        {
            var data1="event_requirement_id="+event_requirement_id+"&event_service_type="+service_type+"&curr_div="+i;
            $.ajax({
              url: "ajax_public_process.php?action=AddMorePayments", type: "post", data: data1,cache: false,async: false,
                beforeSend: function() 
                {
                  Display_Load();
                },
                success: function (html)
                {
                    //alert(html);
                  document.getElementById(curr_div).innerHTML = html;
                  //var date1 = $("#eve_to_date_0_88").datepicker('getDate');
                  //$("#eve_from_date_"+i+"_"+event_requirement_id).datepicker({ minDate:new Date(Date.parse(date1)), dateFormat: 'dd-mm-yy'});
                  datepickerVal(i);
                  //minval = $("#datepicker_eve_to_0").val();
                  return false;
                },
                complete : function()
                {
                   Hide_Load();
                }
          });               
        }
    }
    function deleteMorePlanCare(event_requirement_id,event_service_type)
    {
        var j=document.getElementById('PlanCareextras_'+event_requirement_id).value;
        if(j != 0)
        {
           Display_Load();
           var curr_div = "div_"+j+"_"+event_requirement_id;
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
            document.getElementById('PlanCareextras_'+event_requirement_id).value=previouss;

            $("#eve_from_date_"+j+"_"+event_requirement_id).val("");
            $("#eve_to_date_"+j+"_"+event_requirement_id).val("");
            $("#starttime_"+j+"_"+event_requirement_id).val("");
            $("#endtime_"+j+"_"+event_requirement_id).val("");
            if(event_service_type !='2')
            {
                $("#costService_"+j+"_"+event_requirement_id).empty();
            }
            else 
            {
               $("#other_service_cost_"+j+"_"+event_requirement_id).val(""); 
            }
            
            Hide_Load();
        } 
    }
    function DeletePlanRecord(plan_of_care_id, eventId)
    {
        var prompt_msg = "Are you sure you want to delete this plan of care record?";
        bootbox.confirm(prompt_msg, function (res) 
        {
           if(res==true)
           {
                var data1 = "plan_of_care_id="+plan_of_care_id+"&event_id="+event_id;
                // alert(data1);
                $.ajax({
                     url: "ajax_public_process.php?action=deletePlanCareEntry", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                          Display_Load();
                        },
                        success: function (html)
                        {
                            //alert(html);
                            var result = html.trim();
                            if (result == 'success') {
                                $("#PlanDiv_" + plan_of_care_id).remove();
                            } else if (result == 'errorInDelPlanofCare') {
                                bootbox.alert('<div class="msg-error">Error in delete event plan of care record.</div>');
                            } else if (result == 'errorInDelDtlsPlanofCare') {
                                bootbox.alert('<div class="msg-error">Error in delete event detail plan of care record.</div>');
                            } else if (result == 'progressEventExists') {
                                bootbox.alert('<div class="msg-error">You cannot delete this record. Either completed or inprogress event detail plan of care record exists.</div>');
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
    function isArchive(event_id)
    {
       var prompt_msg = "Are you sure you want to Archive this event record?";
        bootbox.confirm(prompt_msg, function (res) 
        {
           if(res==true)
           {
                var data1="event_id="+event_id;
                // alert(data1);
                $.ajax({
                     url: "ajax_public_process.php?action=ArchiveEvent", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                         var result= html.trim();
                         
                         if(result=='success')
                         {
                             bootbox.alert('<div class="msg-success">Event Archived successfully.</div>', function() 
                             {
                                window.location='event-log.php';
                             });
                         }
                         else 
                         {
                             bootbox.alert('<div class="msg-error">Error in event Archive.</div>'); 
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
    function ShowEventLogList(statusval)
    {
        // Assing Value to variable
        $("#list_status_val").val(statusval);
        searchRecords();        
    }
    function isNumber(evt) 
    {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        
        //alert(charCode);
        
        if (charCode > 31 && (charCode < 48 || charCode > 57)) 
        {
            e.preventDefault();
        }
        return true;
    }
	
	function SubmitPayment(event_id,event_code)
    {
		var input1 = document.getElementById('amount');
        amount = input1.value;
		//alert(amount);
		var input2 = document.getElementById('payment_type');
		paytype = input2.value;
		var Transaction = document.getElementById('Transaction_Type');
        Transaction_Type = Transaction.value;
		var Comments1=document.getElementById('Comments');
		Comments=Comments1.value;
		var Cheque_DD__NEFT_no=document.getElementById('Cheque_DD__NEFT_no').value;
		var Cheque_DD__NEFT_date=document.getElementById('Cheque_DD__NEFT_date').value;
		var Card_Number=document.getElementById('Card_Number').value;
		//alert(Card_Number);
		var Transaction_ID=document.getElementById('Transaction_ID').value;
		//alert(Transaction_ID);
		if(paytype=='Cash')
		{
			var Party_bank_name='Cash';
		}
		else
		{
			var Party_bank_name=document.getElementById('Party_bank_name').value;
		}
		var Professional_name=document.getElementById('Professional_name').value;
		if(Professional_name=='')
		{
			document.getElementById('error_message_Professional_name').innerHTML="Please Enter Select professional name";
		}
		else if(Transaction_Type=='')
		{
			document.getElementById('error_message_Transaction_Type').innerHTML="Please Select mode of payment";
		}
		else if(amount=='')
		{
			//alert();
			document.getElementById('error_message_amount').innerHTML="Please Enter amount";
		}
		else if(paytype=='')
		{
			document.getElementById('error_message_paytype').innerHTML="Please Select payment type";
		}
		
		else
		{
            // Disable submit button 
            $('#submit').attr("disabled", true);
		
		var prompt_msg ="You are completing a payment for <b>"+event_code+". </b><br> The amount is <b> "+amount+"</b>.<br>Payment mode is <b>"+paytype+" </b>.<br>Are you sure you want to save this payment?";
        bootbox.confirm(prompt_msg, function (res) 
        {
           if(res==true)
           {
			    //var data1="event_requirement_id="+event_requirement_id+"&event_service_type="+service_type+"&curr_div="+i;
                var data1="event_id="+event_id+"&amount="+amount+"&paytype="+paytype+"&Comments="+Comments+"&Transaction_Type="+Transaction_Type+"&Cheque_DD__NEFT_no="+Cheque_DD__NEFT_no+"&Cheque_DD__NEFT_date="+Cheque_DD__NEFT_date+"&Party_bank_name="+Party_bank_name+"&Professional_name="+Professional_name+"&Card_Number="+Card_Number+"&Transaction_ID="+Transaction_ID;
                 //alert(data1);
                $.ajax({
                     url: "ajax_public_process.php?action=savePayment", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result = html.trim();
                        if (result == 'success') {
                            bootbox.alert('<div class="msg-success">Payment details added successfully.</div>', function() {
                            Email_receipt(event_id);
                        });
                        } else if (result == 'errorInUpdateTallyStatus' ||
                            result == 'errorInPaymentReceivedStatus' ||
                            result == 'errorInAddPayment' ||
                            result == 'NoDataFound') {
                            bootbox.alert('<div class="msg-error">Payment Failed. Please try again.</div>');
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
    function Email_receipt(eventid)
	{
		var type='download';
		if(type == 'download')
        {
			//alert('type');
             Email_PDFReceipt_payment(eventid,type);
        }
	}
	function Email_PDFReceipt_payment(eventid,type)
	{
		//alert(eventid);
		 var data1="eventid="+eventid;
        $.ajax({
            url: "include_Email_payment_pdf.php", type: "post", data: data1, cache: false,async: false,
           
            success: function (html)
            { 
                var dataRecipt=html;
                var maindata = {html:dataRecipt, eventid:eventid,type :type};
                var siteurl='<?php echo $siteURL;?>';     
                $.ajax({
                    url: 'Email_event_payment_pdf.php',
                    async: false,
                    cache: false,
                    data: maindata,
                    type: 'POST',
                    beforeSend: function() 
                    {
                       Display_Load();
                    },
                    success: function(result) 
                    {
					        location.reload(true);
					    
					
                    },
                    complete : function()
                    {
                       Hide_Load();
					  // location.reload(true);
                    }
                });				  
            },
            complete : function()
            {
               Hide_Load();
			  //location.reload(true);
            }
        }); 
	}
	function remove_error_professional_name()
	{
		document.getElementById('error_message_Professional_name').innerHTML="";
	}
	function remove_error_Transaction_Type()
	{
		document.getElementById('error_message_Transaction_Type').innerHTML="";
	}
	function remove_error_amount()
	{
		document.getElementById('error_message_amount').innerHTML="";
	}
	function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
  </script>
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
    function CalculateTotEstCost(curr_field,field_val)
    {
       // alert(field_nm);
       var pre_val,cal_val,new_val=0;
       if(curr_field) 
       {
           var eventIDS = $("#PlanEvent_id").val();
          // alert(eventIDS);
           var field_nm=curr_field.id;
           var res = field_nm.split("_"); 
           if(field_val.length>=0)
           {
                pre_val=$("#TotalEstCost").text();
                if(field_val !='undefined' && field_val !='null' && field_val >=0)
                {
                     cal_val= parseFloat(pre_val)+parseFloat(field_val);
                }
                else 
                {
                   cal_val= parseFloat(pre_val);
                }

                new_val=parseFloat(cal_val).toFixed(2);

                if(new_val)
                {
                  if(res)
                  {
                      if(field_val !='undefined' && field_val !='null' && field_val >0)
                      {
                         $("#hidden_costService_"+res[3]+"_"+res[4]).val(field_val);
                      }
                      else 
                      {
                         $("#hidden_costService_"+res[3]+"_"+res[4]).val('0'); 
                      }

                     // calucateTotalCost(res[4]);

                   // $("#TotalEstCost").text(new_val);

                  }
                }
           }
           else 
           {
               // Remove current value from total value 
               
              // cal_val= parseFloat(pre_val)-parseFloat(field_val);  
           }
           calucateTotalCost(eventIDS); 
       }
    }
	function change_type_of_payment()
	{
		document.getElementById('error_message_paytype').innerHTML="";
		var payment_type=document.getElementById('payment_type').value;
		//alert(payment_type);
		if(payment_type=='Cash')
		{
			 document.getElementById('Cheque_DD__NEFT_no_div').style.display = "none";
			 document.getElementById('Cheque_DD__NEFT_date_div').style.display = "none";
			 document.getElementById('Party_bank_name_div').style.display = "none";
			 //document.getElementById('Party_bank_name_div').style.display = "inline-block";
			 document.getElementById('Card_Number_div').style.display = "none";
			 document.getElementById('Transaction_ID_div').style.display = "none";
			 
		}
		if(payment_type=='Card')
		{
			 document.getElementById('Cheque_DD__NEFT_no_div').style.display = "inline-block";
			 document.getElementById('Cheque_DD__NEFT_date_div').style.display = "inline-block";
			 document.getElementById('Party_bank_name_div').style.display = "inline-block";
			 document.getElementById('Card_Number_div').style.display = "inline-block";
			 document.getElementById('Transaction_ID_div').style.display = "inline-block";
			 
		}
		if(payment_type=='Cheque')
		{
			 document.getElementById('Cheque_DD__NEFT_no_div').style.display = "inline-block";
			 document.getElementById('Cheque_DD__NEFT_date_div').style.display = "inline-block";
			 document.getElementById('Party_bank_name_div').style.display = "inline-block";
			 document.getElementById('Card_Number_div').style.display = "none";
			 document.getElementById('Transaction_ID_div').style.display = "none";
		}
		if(payment_type=='NEFT')
		{
			 document.getElementById('Cheque_DD__NEFT_no_div').style.display = "inline-block";
			 document.getElementById('Cheque_DD__NEFT_date_div').style.display = "inline-block";
			 document.getElementById('Party_bank_name_div').style.display = "inline-block";
			 document.getElementById('Card_Number_div').style.display = "none";
			 document.getElementById('Transaction_ID_div').style.display = "none";
		}
		
	}
	
	// This function is used for boarscate event to professional
	function boarscate_event(eventId, serviceId) {
		if (eventId && serviceId) {
			//Get current active tab details
			var data1="eventId="+eventId+"&serviceId="+serviceId+"&action=add_boarscate_event_msg";
			$.ajax({
				url: "event_ajax_process.php",
				type: "post",
				data: data1,
				cache: false,
				async: false,
				success: function (html)
				{
					var result= html.trim();

					 if(result=='error')
					 {
						 bootbox.alert('<div class="msg-error">Broadcasting Failed. Please try again.</div>');	 
					 }
					 else if(result=='success')
					 {
						//alert(result);
						bootbox.alert('<div class="msg-success">Broadcasting message sent successfully.</div>');
						//$("#btn_boarscating_" + serviceId).prop('disabled', true);
					//	$("#ProfSubmit").prop('disabled', true);
						//Getting details of professional
						getProfessionalDetails(eventId, serviceId);
					 } else {
						bootbox.alert('<div class="msg-success">'+ result + '</div>');
					 }
				},
				complete : function()
				{
					
				}
			});
		}
	}
	
	// This function is used for get professional details
	
function getProfessionalDetails(eventId, serviceId)
	{
		var data1="eventId="+eventId+"&serviceId="+serviceId+"&action=get_boarscate_prof_dtls";
		$.ajax({
			url: "event_ajax_process.php",
			type: "post",
			data: data1,
			cache: false,
			async: false,
			success: function (html)
			{
			    var result= html.trim();
			    $(".bcProfIncludeDiv_" + serviceId).empty().html(result);
			}
		});
	}
	
	function startTimer(duration, display)
	{
		var timer = duration, minutes, seconds;
		setInterval(function () {
			minutes = parseInt(timer / 60, 10)
			seconds = parseInt(timer % 60, 10);

			minutes = minutes < 10 ? "0" + minutes : minutes;
			seconds = seconds < 10 ? "0" + seconds : seconds;

			display.textContent = minutes + ":" + seconds;

			if (--timer < 0) {
				timer = duration;
			}
		}, 1000);
	}

    $(document).on('blur change','#discount_amount',function() {
        console.log("discount_amount blur change event called....");
        if ($("#discount_id").val() != '' && $("#discount_id").val() != undefined) {
            if ($("#discount_id").val() == 1) {
                console.log("discount_amount", $("#discount_amount").val());
                if (parseInt($("#discount_amount").val()) > 100) {
                bootbox.alert('<div class="msg-error">Please enter discount percentage is less than 100</div>', function() {
                    $("#discount_amount").val('').focus();
                });
                return false;
                }
            } else if ($("#discount_id").val() == 2) {
                console.log("finalcost_eve 2", $("#finalcost_eve").val());
                if (parseInt($("#discount_amount").val()) > parseInt($("#finalcost_eve").val())) {
                    bootbox.alert('<div class="msg-error">Please enter discount amount is less than total amount</div>', function() {
                        $("#discount_amount").val('').focus();
                    });
                    return false;  
                }
                
            }

            // check is it discount type dropdown is disabled then enable it
            if ($('#discount_id').prop('disabled')) {
                $('#discount_id').prop("disabled", false);
            }

            // calculate amount
            calDiscountAmount();
        } else {
            $(".finalAmountWithDiscountContentDiv").hide();
        }
    });

    // This method is used for handle change discount combo event
    function changeDiscountCombo()
    {
        //console.log("datepicker_eve_0", $(".datepicker_eve_0").val());
        //console.log("datepicker_eve_to_0", $(".datepicker_eve_to_0").val());
        if ($(".datepicker_eve_0").val() != '' && $(".datepicker_eve_to_0 ").val() != '') {
            if ($("#discount_id").val() != '') {
                $("#discount_amount, #discount_narration").removeAttr("disabled");
                $("#discount_amount").val('').focus();
            } else {
                $("#discount_amount, #discount_narration").attr("disabled", true);
                $("#discount_id, #discount_amount, #discount_narration").val('');
            }
        } else {
            bootbox.alert('<div class="msg-error">Please select from date.</div>');
        }
    }

    // This method is used for calculate discount amount
    function calDiscountAmount()
    {
        var discountOption = $("#discount_id").val();
        var discountAmt    = parseFloat($("#discount_amount").val());
        var finalCost      = parseFloat($("#finalcost_eve").val());
        var discountedAmountFinalVal = 0.0;
        var eventCostWithDiscount = 0.0;

        if (discountOption && discountAmt && finalCost) {
            if (discountOption == 1) {
                // calculate percentage anmount
                discountedAmountFinalVal = (finalCost * discountAmt) / 100 ;
                $("#discountCost_eve").val(discountedAmountFinalVal);
                eventCostWithDiscount = finalCost - discountedAmountFinalVal;
                
            } else if (discountOption == 2) {
                // calculate percentage anmount
                $("#discountCost_eve").val(discountAmt);
                eventCostWithDiscount = finalCost - discountAmt;
            }


            console.log("eventCostWithDiscount", parseFloat(eventCostWithDiscount).toFixed(2));

            // Show Div
            $(".finalAmountWithDiscountContentDiv").show();
            $("#finalCostWithDiscount_eve").val(eventCostWithDiscount);
            $(".totalDiscountCost").empty().text(parseFloat($("#discountCost_eve").val()).toFixed(2));

            console.log("finalAmountWithDiscountCost", parseFloat(eventCostWithDiscount).toFixed(2));

            $("#finalAmountWithDiscountCost").text(parseFloat(eventCostWithDiscount).toFixed(2));
        }
    }

    // This method is used for handle change discount narration combo event
    function changeDiscountNarrationCombo()
    {
        var discountNarration = $("#discount_narration").val();
        if (discountNarration != '' && discountNarration == 'Other') {
            $(".narrationContent").show();
            $('#discount_narration_content').prop("disabled", false);

        } else {
            $(".narrationContent").hide();
            $("#discount_narration_content").val('');
            $('#discount_narration_content').prop("disabled", true);
        }
    }


    // change enquiry sub service
    function ChangeEnquirySubservice(value,checked)
    {
        var fld = document.getElementById('enquiryRequirnment');
        var CheckVals = [];
        for (var i = 0; i < fld.options.length; i++) {
          if (fld.options[i].selected) {
            CheckVals.push(fld.options[i].value);
          }
        }

        var checkdata = $('#services_'+value).is(':checked'); 
        var data1="service_ids="+value+"&action=ChangeEnquirySubServices";
        $.ajax({
            url: "ajax_public_process.php", type: "post", data: data1, cache: false,async: false,
            beforeSend: function() 
            {
                // Popup_Display_Load();
            },
            success: function (html)
            {
              //alert(html);
                result = html.trim();
                valuesData = result.split("sepratedTitle--"); 
                if(checked == true) {
                    $("#subServiceData").append(valuesData[0]);
                } else {
                    $("#ServiceDiv_"+value).remove();   
                }

                $('#enquiry_sub_service_id_multiselect_'+value).change(function() {    
                    setTimeout(function() {               
                          $('#content-1').mCustomScrollbar("scrollTo", "bottom");
                     },100);
                   });
                
                $('#enquiry_sub_service_id_multiselect_'+value).multiselect({enableFiltering: true, 
                    enableCaseInsensitiveFiltering: true,nonSelectedText:valuesData[1]+" (Recommended Service)"});
            },
            complete : function()
            {
               //$('#loader_image').hide();
            }
        });
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

    /**
     * Follow up inquiry
     */
     function followUpEnquiry(eventId)
     {
        if (eventId != '' && eventId != undefined) {
            var prompt_msgs = "Are you sure you want to add follow up  this enquiry ?";
            bootbox.confirm(prompt_msgs, function (res) 
            {
                if (res == true)
                {
                    var data1="event_id="+eventId+"&action=vw_add_follow_up_inquiry";
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
            });
        }
     }

     /**
      * 
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
                    var result=html.trim();
                    $("#follow_up_date").val('');
                    $("#follow_up_time").val('');
                    $("#follow_up_desc").val('');
                    $('#vw_add_follow_up_inquiry').modal('hide');

                    if (result != 'error') {
                        var res = result.split("HtmlSeperator");
                        if (res[0] == 'success') {
                            bootbox.alert('<div class="msg-success"> Enquiry follow up details added successfully.</div>', function() {
                                $('#vw_professional').modal('hide');
                                window.location='event-log.php';
                            });
                        }
                    } else {
                        bootbox.alert('<div class="msg-error"> Error in add Enquiry follow up details.</div>');
                    }
                },
                complete : function()
                {
                   Hide_Load();
                }
            }).submit();  
        }
      }
    </script>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=true"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD3wZTkqi05uBxq-6ef7NvnxiSWI1Jixls&libraries=places"></script>OLD KEY
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8lSxG4pg8hWyd52oqUQJKWnjQSe20dvc&libraries=places"></script>BVG KEY-->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&libraries=places"></script>

<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_Xe7p9CSOdwHIU7mB7CdcL_w_LZfRX8o&libraries=places"></script>-->

</body>
</body>
<script>
var $AVAYA_INCOMING_CALL_FLAG = 1;
var $Avaya_Incoming_Call_Timer = null;
$(window).load(function () {
    $Avaya_Incoming_Call_Timer = setInterval(avaya_change_incoming_call, 5000);
});
function avaya_change_incoming_call() {
    
    if ($AVAYA_INCOMING_CALL_FLAG == 1) {
       // alert($AVAYA_INCOMING_CALL_FLAG);
        var status='R';
         var data1="status="+status+"&action=chk_call";
    $.ajax({
                    url: "incomming_popup.php", type: "post", data: data1, cache: false,async: false,
            
            beforeSend: function() 
            {
                //Display_Load();
            },
            success: function (html)
                {
                    $abc = html;
                    if($abc != ' '){
                    //alert(html);
                        $('#vw_avaya').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData_avaya").html(html);
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                        setHeight:500,
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                //    $(".modal-dialog").css("width", "350px");
               //     $(".modal-dialog").css("background-color", "#A3E4D7");
                   // $(".modal-content").css("background-color", "#45B39D ");
                    //$('.modal-dialog').modal('hide'); 
                    }
                },
                complete : function()
                {
                    Hide_Load();
                }
             });
    }
}

function avaya_start_incoming_call() {
    $AVAYA_INCOMING_CALL_FLAG = 1;
}
function soft_call_dial(no){
    //alert(no);
   // var phone_no = parseInt(document.getElementById('output').value);
    //var user = '<?php //echo $_SESSION['first_name'];?>';
    $("#ready_mode").hide();
    $("#pause_mode").hide();
    var data1="phone_no="+no+"&action=vw_softdial";
    //var_dump(data1);die();
    $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        // var res = html.split(" ", 1);
                        // alert(html);
                        var sttr1="ERROR:"
                        var val = sttr1.search(html);
                        //alert(val);
                        if(val == 0){
                            //bootbox.alert("<div class='msg-success'>Call Connected Successfully</div>");
                            $("#hang_mode").show();
                            $("#conf_mode").show();
                            return false;
                        }else{
                            //alert(html);
                           // bootbox.alert("<div class='msg-error'>ERROR : Something Wents Wrong</div>");
                            $("#pause_mode").show();
                            return false;
                            
                        }
                        
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                        
                    }
             });
}
function soft_call(){
    var phone_no = parseInt(document.getElementById('output').value);
    //var user = '<?php //echo $_SESSION['first_name'];?>';
    $("#ready_mode").hide();
    $("#pause_mode").hide();
    var data1="phone_no="+phone_no+"&action=vw_softdial";
    //var_dump(data1);die();
    $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        // var res = html.split(" ", 1);
                        // alert(html);
                        var sttr1="ERROR:"
                        var val = sttr1.search(html);
                        //alert(val);
                        if(val == 0){
                           // bootbox.alert("<div class='msg-success'>Call Connected Successfully</div>");
                            $("#hang_mode").show();
                            $("#conf_mode").show();
                            //return false;
                        }else{
                           // bootbox.alert("<div class='msg-error'>ERROR : Something Wents Wrong</div>");
                            $("#pause_mode").show();
                            //return false;
                        }
                        
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                        
                    }
             }); 
}
function search_missed_calls(){
    //alert();
    var from_date=document.getElementById('from_date').value;
    var to_date=document.getElementById('to_date').value;
   /* var data1="from_date="+from_date+"&to_date="+to_date+"&action=check_missed_call";
    $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        //Display_Load();
                    },
                    success: function (html)
                    {
                        alert(html);
                    },
                    complete : function()
                    {
                       Hide_Load();
                       
                    }
             });  */
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
					//alert(xmlhttp.responseText);
                   	document.getElementById("Missed_call_list").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST","dialerbox.php?from_date="+from_date+"&to_date="+to_date+"&action=check_missed_call",true);
			xmlhttp.send();

}
function missedcall(){
    $status='1';
    var data1="status="+status+"&action=vw_MissedCall";
    $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        //Display_Load();
                    },
                    success: function (html)
                    {
                        $('#vw_professional').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                                       setHeight:100,
                                    
                                });
                    },
                    complete : function()
                    {
                       Hide_Load();
                       
                    }
             });  
}
function softdial(){
    // ready_mode
   // var disconect_remark = document.getElementById('disconect_remark').value
       $status='1';
    var data1="status="+status+"&action=vw_dial";
    $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        //Display_Load();
                    },
                    success: function (html)
                    {
                        $('#vw_avaya').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData_avaya").html(html);
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                                       setHeight:100,
                                    
                                });
                                         
                       // $('[data-toggle="tooltip"]').tooltip();
                        //alert(html);
                       /* $('#vw_avaya').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData_avaya").html(html);
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                                        setHeight:300,
                                        
                                        //theme:"minimal-dark"
                                });
                                         
                        $('[data-toggle="tooltip"]').tooltip();*/
                      //  $(".modal-dialog").css("width", "350px");
                      //  $(".modal-dialog").css("background-color", "#A3E4D7");
                      //  $(".modal-content").css("background-color", "#45B39D ");
                        
                        //$('.modal-dialog').modal('hide'); 
                    },
                    complete : function()
                    {
                       Hide_Load();
                       
                    }
             }); 
   }
   function Caller_history(calling_phone_no,unic_id)
    {
        
        document.getElementById("phone_no").value = calling_phone_no;
        //alert('hi');
    $("#ExistingCallerForm").ajaxForm({
        beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                     var result=html.trim(); 
                     //alert(result);
                     $("#RightSideDiv").hide();
                     $("#SearchRightSideDiv").show();         
                     changePagination('searchPatientListing','search_existing_caller.php','','','','');
                     $("#JobClosureDiv").hide();       
                     $("#FeedbackDivs").hide();       
                     //$("#SearchRightSideDiv").html(result);
                },
                complete : function()
                {
                    acceptCaller(calling_phone_no,unic_id);
                    $("#vw_avaya").modal("hide");
                        $("#ready_mode").hide();
                        $("#pause_mode").hide();
                        $("#hang_mode").show();
                        $("#conf_mode").show();
                }
            }).submit();
    }
   function acceptCaller(calling_phone_no,unic_id)
   {
       if(calling_phone_no)
        {
           // alert(unic_id);
          // window.location = '/event-log.php?calling_phone_no=' + calling_phone_no+'&unic_id='+unic_id;
            var status='1';
            document.getElementById("phone_no").value = calling_phone_no;
            var data1="phone_no="+calling_phone_no+"&unic_id="+unic_id+"&status="+status+"&action=CheckCallerExist";
            //alert(data1);
                $.ajax({
                    url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        // alert('hi');
                        // Popup_Display_Load();
                        //window.location = "event-log.php";
                        // changePagination('eventLogListing','include_event_log.php','','','','');  
                        //window.location='event-log.php';
                        //$( "#RightSideDiv" ).load(window.location.href + " #RightSideDiv" );
                        //window.location = '/event-log.php?EID=' + EID;
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                        //alert(result);
                        if(result)
                        {
                            var res = result.split("-"); 
                            if(res[0])
                            {
                                $("#name").val(res[0]);
                            }
                            if(res[1])
                            {
                                $("#caller_first_name").val(res[1]);
                            }
                            if(res[2])
                            {
                                $("#caller_middle_name").val(res[2]);
                            }
                        }
                        else
                        {
                            $("#name,#caller_first_name,#caller_middle_name").val('');
                        }  
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                        $("#ready_mode").hide();
                        $("#pause_mode").hide();
                        $("#hang_mode").show();
                        $("#conf_mode").show();
                    }
                }); 
           // }  
        } 
        else 
        {
            $("#name,#caller_first_name,#caller_middle_name").val('');
        }
    }
    function disconnect_HD(calling_phone_no,unic_id){
        
        if(calling_phone_no)
        {
           // alert(unic_id);
            var status='1';
         /*   var disconect_remark = document.getElementById('disconect_remark').value
            if(disconect_remark=='')
            {
                alert('hi');
            }else{ }*/
            var data1="phone_no="+calling_phone_no+"&unic_id="+unic_id+"&status="+status+"&disconect_remark="+disconect_remark+"&action=Checkdisconnect";
            //alert(data1);
                $.ajax({
                    url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        // Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                        //alert(result);
                        //$("#vw_avaya").modal("hide");
                    },
                    complete : function()
                    {
                        //$("#vw_avaya").modal("hide");
                        $("#remark_disconect").show();
                        $("#btn_incoming").hide();
                        
                    }
                }); 
           // }  
        
        } 
        else 
        {
            $("#name,#caller_first_name,#caller_middle_name").val('');
        }
    }
    function disconnect_Caller(calling_phone_no,unic_id){
        if(calling_phone_no)
        {
           // alert(unic_id);
            var status='1';
            var disconect_remark = document.getElementById('disconect_remark').value;
            var data1="phone_no="+calling_phone_no+"&unic_id="+unic_id+"&status="+status+"&disconect_remark="+disconect_remark+"&action=Checkdisconnect";
            //alert(data1);
                $.ajax({
                    url: "event_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        // Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                        //alert(result);
                        $("#vw_avaya").modal("hide");
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                    }
                }); 
           // }  
        } 
        else 
        {
            $("#name,#caller_first_name,#caller_middle_name").val('');
        }
    }
    function ready_mode(){
        $("#pause_mode").show();
        $("#ready_mode").hide();
        var status='1'
        var data1="status="+status+"&action=vw_ready_mode";
        $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       //bootbox.alert("<div class='msg-success'>Now you are in Ready mode</div>");
                       // alert('User now Ready mode');
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                    }
                }); 
    }
    function pause_mode(){
        $("#ready_mode").show();
        $("#pause_mode").hide();
        var status='1'
        var data1="status="+status+"&action=vw_pause_mode";
        $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        //bootbox.alert("<div class='msg-success'>Now you are in Pause mode</div>");
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                    }
                });
    }
    function hang_mode(){
      //  $("#ready_mode").show();
       // $("#pause_mode").hide();
        var status='1'
        var data1="status="+status+"&action=vw_hang_mode";
        $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        //bootbox.alert("<div class='msg-success'>Call disconnected..Now you are in Ready mode</div>");
                        $("#pause_mode").show();
                        $("#ready_mode").hide();
                        $("#hang_mode").hide();
                        $("#conf_mode").hide();
                        $("#merge_mode").hide();
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                    }
                });
    }
    function conf_mode()
    {
        $status='1';
        var data1="status="+status+"&action=vw_conf";
        $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        //Display_Load();
                    },
                    success: function (html)
                    {
                      //  alert(html);
                        $('#vw_avaya').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData_avaya").html(html);
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                                        setHeight:300,
                                     
                                });
                                         
                        $('[data-toggle="tooltip"]').tooltip();
                      
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
    }
    function whats_app_sms(){
        var status='1'
        var Whats_App_No=document.getElementById('Whats_App_No').value;
        var Whats_App_Msg=document.getElementById('Whats_App_Msg').value
        
        var data1="Whats_App_Msg="+Whats_App_Msg+"&Whats_App_No="+Whats_App_No+"&action=whats_App_Sms";
        $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       
                        
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                    }
                });
    }
    function add_call(){
        var status='1'
        var confer_no=document.getElementById('conf_no').value
        var data1="status="+status+"&conf_no="+confer_no+"&action=vw_conf_mode";
        $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var sttr1="ERROR:"
                        var val = sttr1.search(html);
                        //alert(val);
                        if(val == 0){
                            //bootbox.alert("<div class='msg-success'>Conferance Call Connected..</div>");
                            $("#hang_mode").hide();
                            $("#conf_mode").hide();
                            $("#merge_mode").show();
                        }else{
                           // bootbox.alert("<div class='msg-error'>ERROR: Conferance Call Not Connected..</div>");
                            $("#hang_mode").show();
                            $("#conf_mode").show();
                        }
                        
                    },
                    complete : function()
                    {
                        $("#vw_avaya").modal("hide");
                    }
                });
    }
    function merge_mode(){
        //ttp://183.87.122.153/API/GrabCall.php?user=ashwini
        var status='1'
        var data1="status="+status+"&action=vw_merge_mode";
        $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       /// alert(html);
                        var sttr1="SUCCESS:"
                        var val = sttr1.search(html);
                      //  alert(val);
                        if(val == -1){
                      //  bootbox.alert("<div class='msg-success'>Successfully call Merged</div>");
                        $("#pause_mode").hide();
                        $("#ready_mode").hide();
                        $("#hang_mode").show();
                        $("#conf_mode").hide();
                        $("#merge_mode").hide();
                        }else{
                          //  bootbox.alert("<div class='msg-error'>ERROR: merge Call..</div>");
                            $("#hang_mode").show();
                            //$("#conf_mode").show();
                            $("#merge_mode").show();
                        }
                    },
                    complete : function()
                    {
                       // $("#vw_avaya").modal("hide");
                    }
                });
    }
    function Whats_App_SMS(){
      //  $("#ready_mode").show();
       // $("#pause_mode").hide();
        var status='1'
        var data1="status="+status+"&action=WhatsAppSMS";
        $.ajax({
                    url: "dialerbox.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        //bootbox.alert("<div class='msg-success'>Call disconnected..Now you are in Ready mode</div>");
                        $('#vw_avaya').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData_avaya").html(html);
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                                       setHeight:100,
                                    
                                });
                    },
                    complete : function()
                    {
                        Hide_Load();
                    }
                });
    }
    function search_professional(){
        
        var search_professionalid = document.getElementById('search_professionalid').value;
        //alert(search_professionalid);
        document.getElementById("conf_no").value = search_professionalid;
        
    }
    function search_professional_whatsapp(){
        
        var search_professionalid = document.getElementById('search_professionalid').value;
        //alert(search_professionalid);
        document.getElementById("Whats_App_No").value = search_professionalid;
        
    }
    function professional_remainder(){
        //ttp://183.87.122.153/API/GrabCall.php?user=ashwini
        var status='1'
        var data1="status="+status+"&action=professional_service_remainder";
        $.ajax({
                    url: "ajax_public_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       alert('done');
                    },
                    complete : function()
                    {
                       // $("#vw_avaya").modal("hide");
                    }
                });
    }
</script>
<?php
$db->close(); 
?>
</html>