<?php   
    require_once 'inc_classes.php';
    require_once "emp_authentication.php";
    require_once 'classes/commonClass.php';
    $commonClass=new commonClass();
    require_once 'classes/employeesClass.php';
    $employeesClass=new employeesClass();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Requirement Assessment</title>
        <style type="text/css" rel="stylesheet">
       
            #container {
                width: 940px;
                margin: 0 auto;
            }
            @media only screen and (max-width: 768px) {
                #container {
                    width: 90%;
                    margin: 0 auto;
                }
            }
        </style>
        <link rel="stylesheet" href="dropdown/docsupport/prism.css">
        <link rel="stylesheet" href="dropdown/chosen.css">
    </head>
    <body>
    <?php include "include/header.php"; ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-left-right">
                    <h2 class="page-title">Requirement Assessment</h2>
                    <div class="col-lg-12 white-bg">            
                        <!-- ---------------- Event Log start ----------- -->
                        <div id="EventLogDiv">
                            <div class="form-inline serch-box">
                                <div class="form-group col-lg-12">
                                  <div class="row">
                                    <div class="input-group col-lg-12"> 
                                        <div class="form-inline serch-box" >
                                            <div class="form-group col-lg-3">
                                                <div class="row">
                                                  <div class="input-group col-lg-11"> <span class="input-group-addon text-left" style="width:5%;">
                                                          <a href="javascript:void(0);"><img onclick="searchRecords();" src="images/search-icon.png" width="22" height="21" alt="Search icon"></a></span>
                                                    <input type="text" class="form-control searchKeywords" id="SearchKeyword" name="SearchKeyword" aria-describedby="">
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2">
<!--                                                <label class="select-box-lbl">-->
                                                    <select class="chosen-select form-control" name="search_purpose_id" id="search_purpose_id" onchange="searchRecords();">
                                                         <option value="">Purpose of call</option>
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
<!--                                                </label>-->
                                            </div>
                                            <div class="form-group col-lg-2">
<!--                                                <label class="select-box-lbl">-->
                                                     <select class="chosen-select form-control" name="search_employee_id" id="search_employee_id" onchange="searchRecords();">
                                                         <option value="">Attend by</option>
                                                         <?php
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
                                                            ?>
                                                     </select>
<!--                                                </label>-->
                                            </div>
                                            <div class="form-group col-lg-5">
                                                <div class="row">
                                                  <div class="col-sm-4 text-right padingtop10"> Filter By:Date </div>
                                                  <div class="col-sm-4">
                                                    <input type="text" class="form-control datepicker_from"  id="event_from_date" name="event_from_date" placeholder="From">
                                                  </div>
                                                  <div class="col-sm-4 ">
                                                    <input type="text" class="form-control datepicker_to"  id="event_to_date" name="event_to_date" placeholder="To">
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group col-lg-6">
                                </div>
                            </div>
                            <div class="AssessmentListing">
                                <?php include 'include_requirement_assessment.php'; ?>   
                            </div>  
                        </div>
                        <!-- ---------------- Event Log End ----------- -->   
                    </div>
                </div>
            </div>
        </div>
    </section>
        
    <!-- Modal Popup code start ---> 
    <div class="modal fade" id="vw_event"> 
        <div class="modal-dialog" style="width:900px !important;">
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
    <!-- Modal Popup code end ---> 
<?php include "include/scripts.php"; ?>
<!-- ------------- datepicker ------------ -->
<link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
<script src="js/development-bundle/ui/jquery.ui.core.js"></script>
<script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script>
<script src="js/bootbox.js"></script>
<script>    
    $(document).ready(function() 
    {
        $('.datepicker_from').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'yy-mm-dd',
            yearRange: '2015:+0',
            maxDate:new Date(),
            onSelect: function() {
                 searchRecords();
               }
        });
        $('.datepicker_to').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'yy-mm-dd',
            yearRange: '2015:+0',
            maxDate:new Date(),
            onSelect: function() {
                 searchRecords();
               }
        });
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
        changePagination('AssessmentListing','include_requirement_assessment.php','','','','');
    }
    function ViewEvent(event_share_id)
    {
        if(event_share_id)
        {
            Popup_Display_Load();
            var data1="event_share_id="+event_share_id+"&action=vw_event";
            //alert(data1);
             $.ajax({
                    url: "event_summary_ajax_process.php", type: "post", data: data1, cache: false,
                    success: function (html)
                    {
                       // alert(html);
                        $('#vw_event').modal({backdrop: 'static',keyboard: false}); 
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
                        Popup_Hide_Load();
                    }
             }); 
        }
    }
    function ViewEventActions(main_event_id,purpose_event_id,type)
    {
        var purpose_id = $('#purpose_id').val();
        var caller_id = $('#Edit_CallerId').val();
        purpose_event_id = $('#eventIDForClosure').val();
        var Consultant_Email=$('#familyDocemail_id').val();
        $("#AllAjaxData").css({ opacity: 0.5 });
        Popup_Display_Load();
        var data1="main_event_id="+main_event_id+"&type="+type+"&purpose_event_id="+purpose_event_id+"&purpose_id="+purpose_id+"&caller_id="+caller_id+"$Consultant_Email="+Consultant_Email+"&action=saveEventDetails";           
        //alert(data1);
        $.ajax({
                url: "ajax_public_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                    //alert(html);
                    //bootbox.alert('Feddback details submited successfully.');
                    
                    // Getting All Checked Checkbox values 
                    var selected_block_id = [];
                    var selected_block_value = [];
                    $("input:checkbox[class=case]:checked").each(function () 
                    {
                        selected_block_id.push($(this).attr("id"));
                        selected_block_value.push($(this).attr("value"));
                    });


                   // alert(selected_block_id);
                   // alert(selected_block_value);
                   
                   if(type !='continue')
                   {
                        if(selected_block_value !='null' && selected_block_value.length !=0 && selected_block_value !='undefined')
                        {

                             if(type == 'download')
                                 downloadPDFReport(main_event_id,selected_block_id,selected_block_value);
                             else if(type == 'email')
                                 SendReportByEmail(main_event_id,selected_block_id,selected_block_value);
                             else 
                                 window.location="event-log.php";
                         }
                         else 
                         {
                            bootbox.alert('<div class="msg-error">You are not selecting any option,please select atleast one oprion.</div>');
                         }
                   }
                   else 
                       window.location="event-log.php";
                   
                    Popup_Hide_Load();
                    //$('#vw_event').modal('hide'); 
                    $("#AllAjaxData").css({ opacity: 1 });
                }
         }); 
    }
    function downloadPDFReport(event_id,selected_block_id,selected_block_value)
    {
        var data1="event_id="+event_id+"&selected_block_id="+selected_block_id+"&selected_block_value="+selected_block_value;
       // alert(data1);
        $.ajax({
            url: "include_download_pdf.php", type: "post", data: data1, cache: false,
            success: function (html)
            {
                //alert(html);
                var dataRecipt=html;
                var maindata = {html:dataRecipt, event_id:+event_id};
                var siteurl='<?php echo $siteURL;?>';     
                $.ajax({
                url: 'download_event_pdf.php',
                data: maindata,
                type: 'POST',
                success: function(result) 
                {
                    var w = location.href=siteurl+'download_event_pdf.php?export=1&file='+result;
                }
                });				
                Popup_Hide_Load();
               
            }
        });
    }
    function SendReportByEmail(event_id,selected_block_id,selected_block_value)
    {
        if(event_id)
        {
            Popup_Display_Load();
            var Consultant_Email=$('#familyDocemail_id').val();
            $('#vw_event').modal('hide');
            var data1="event_id="+event_id+"&Consultant_Email="+Consultant_Email+"&selected_block_id="+selected_block_id+"&selected_block_value="+selected_block_value+"&action=vw_select_professional_for_email";
            //alert(data1);
             $.ajax({
                    url: "event_ajax_process.php", type: "post", data: data1, cache: false,
                    success: function (html)
                    {
                        //alert(html);
                        $('#vw_select_professional').modal({backdrop: 'static',keyboard: false}); 
                        $("#AjaxData").html(html);
                        $('[data-toggle="tooltip"]').tooltip();
                        Popup_Hide_Load();
                    }
             }); 
            
        } 
    }
    function EmailContent(event_id,selected_block_id,selected_block_value)
    {
        Popup_Display_Load();
        var email_id=$("#email_id").val();
        var email_msg=$("#email_msg").val();
        var consultant_id=$("#consultant_id").val();
        
        var data1="event_id="+event_id+"&selected_block_id="+selected_block_id+"&selected_block_value="+selected_block_value;
        $.ajax({
            url: "include_download_pdf.php", type: "post", data: data1, cache: false,
            success: function (html)
            {
                //alert(html);
                var dataRecipt=html;
                var maindata = {html:dataRecipt, event_id:+event_id};
                var siteurl='<?php echo $siteURL;?>';     
                $.ajax({
                url: 'download_event_pdf.php',
                data: maindata,
                type: 'POST',
                success: function(result) 
                {
                   // Getting File Name  
                    var file_nm=result;
                    var data1="event_id="+event_id+"&file_nm="+file_nm+"&email_id="+email_id+"&email_msg="+email_msg+"&consultant_id="+consultant_id+"&action=SendReportByEmail";
                    // alert(data1);
                      $.ajax({
                             url: "event_ajax_process.php", type: "post", data: data1, cache: false,
                             success: function (html)
                             {
                                 result=html.trim();
                               //  alert(result);
                                 if(result=='success')
                                 {
                                      bootbox.alert("<div class='msg-success'>Event details are successfully send on email address.</div>");
                                 }
                                 else
                                 {
                                      bootbox.alert("<div class='msg-error'>Error in send email.</div>");
                                 }

                                 Popup_Hide_Load();
                             }
                      }); 
                }
                });				
                Popup_Hide_Load();
               
            }
        });	
    }
</script>
<script src="dropdown/chosen.jquery.js" type="text/javascript"></script>
<script src="dropdown/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" language="javascript">
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
</body>
</html>