<?php   require_once 'inc_classes.php';        
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";        
        include "classes/eventClass.php";
        $eventClass = new eventClass();
        include "classes/employeesClass.php";
        $employeesClass = new employeesClass();
	include "classes/professionalsClass.php";
        $professionalsClass = new professionalsClass();
        include "classes/commonClass.php";
        $commonClass= new commonClass();
        require_once 'classes/functions.php'; 
        require_once 'classes/config.php'; 
?>
<?php
    if($_REQUEST['action']=='vw_event')
    {
        $event_id=$db->escape($_REQUEST['event_id']);
        $event_share_id=$db->escape($_REQUEST['event_share_id']);
        $consultant_email_id=$db->escape($_REQUEST['Consultant_Email']);
        if(!empty($event_share_id))
        {
            // Get Event Summary 
             $ShareEventDtls=$eventClass->GetShareEventById($event_share_id);
             if(!empty($ShareEventDtls))
             {
                $arr['event_id']=$ShareEventDtls['event_id'];
                $event_id=$ShareEventDtls['event_id'];
                $EventSummaryDtls=$eventClass->GetEventSummary($arr);
             }
        }
        else 
        {
            $arr['event_id']=$event_id;
            $EventSummaryDtls=$eventClass->GetEventSummary($arr);
        }
        /* ------- main event ------------*/
        $evearrg['event_id'] = $event_id;
        $getEventDetail = $eventClass->GetEvent($evearrg);

        // echo '<pre>$getEventDetail <br>';
        // print_r($getEventDetail);
        // echo '</pre>';
        // exit;


        if(!empty($getEventDetail))
        {
            $event_code = $getEventDetail['event_code'];
            $event_date = date('d M Y h:i A',strtotime($getEventDetail['event_date']));

            // Get Event Requirement
            if ($getEventDetail['event_status'] == 5) {
                $enquiryReqDtls = $eventClass->getEnquiryReqDtls($event_id);

                //Get Professional list based on requirement
                if (!empty($enquiryReqDtls)) {
                    $selectedServices  = '';
                    $selectedSubServices  = '';
                    $serviceIdsArr    = array();
                    $subServiceIdsArr = array();

                    foreach ($enquiryReqDtls AS $key => $valReq) {
                        $serviceIdsArr[]    = $valReq['service_id'];
                        $subServiceIdsArr[] = $valReq['sub_service_id'];
                        $selectedServices .= $valReq['service_title'] .','; 
                        $selectedSubServices .= $valReq['recommomded_service'] . ','; 
                    }

                    if (!empty($serviceIdsArr) && !empty($subServiceIdsArr)) {
                        $arg['serviceIdsArr'] = array_unique($serviceIdsArr);
                        $arg['subServiceIdsArr'] = array_unique($subServiceIdsArr);
                        $arg['event_id'] = $event_id;
                        $arg['service_date_of_Enquiry'] = $getEventDetail['service_date_of_Enquiry'];
                        $professionalsList =  $eventClass->getProfessionals($arg);
                    }
                }

                // Get inquiry folow up details
                $enquiryFollowUpDtls = $eventClass->getEnquiryFollowUpDtls($event_id);
            }
        }
        else 
        {
            $event_code ="";
            $event_date ="";
        }

        $sql_call_purpose="SELECT name FROM sp_purpose_call WHERE purpose_id='".$EventSummaryDtls['CallerDtls']['purpose_id']."'";
        $row_call_purpose=$db->fetch_array($db->query($sql_call_purpose));
        $call_purposeName =$row_call_purpose['name']; 
        if($EventSummaryDtls['CallerDtls']['purpose_id']=='2' || $EventSummaryDtls['CallerDtls']['purpose_id']=='6')
        {
           if($EventSummaryDtls['CallerDtls']['purpose_id']=='2') 
           {
               $heading_content="ENQUIRY NOTE";
               ?>
                <script type="text/javascript">
                    $("#Block1,#Block2,#Block3").show();
                    $("#Block4,#Block5,#Block6,#Block7,#Block8,#Block9,#Block10,#Block11").hide();
                </script>
    <?php 
           }
           else 
           {
              $heading_content="GENERAL INFORMATION"; 
               ?>
                <script type="text/javascript">
                    $("#Block1,#Block3").show();
                   $("#Block2,#Block4,#Block5,#Block6,#Block7,#Block8,#Block9,#Block10,#Block11").hide();
                </script>
<?php 
           }
        }
        else 
        {
            ?>
            <script type="text/javascript">
                $("#Block3").hide();
            </script>
<?php    
        }
        $onclick = ''; $textForcall = '';
        $main_event_id =  $event_id;
        //echo $_REQUEST['Caller_purpose_Id'];
	if($_REQUEST['Caller_purpose_Id'] == '4' || $_REQUEST['Caller_purpose_Id'] == '5')
        {
            
            //$purpose_event_id =  $event_id;  
            $onclick = 'onclick="ViewEventActions(\''.$main_event_id.'\',\'\',\'continue\')"';
            
        }
        
  // echo '<pre>';
   // print_r($EventSummaryDtls);
   // echo '</pre>';
  //  exit;    
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">Invoice for <?php echo $event_code;?> Event Details (<?php echo $call_purposeName;?>)<span style="float:right;padding-right: 25px;"><?php if(!empty($EventSummaryDtls['PatientDtls']['hhc_code'])) { echo "HHC No. :".$EventSummaryDtls['PatientDtls']['hhc_code']; } ?></span></h4>
  <span><h4>Event Date :<?php echo $event_date;?></h4></span>
</div>
<div class="modal-body">
  <div class="mCustomScrollbar">
       <input type="checkbox" name="check_all" id="selectall" value="1" />  <b>Check All</b>
    <?php 
        if(!empty($EventSummaryDtls['CallerDtls']))
        { ?>
            <div id="Block1">
                <h4 class="section-head text-left"><input type="checkbox" name="caller_dtls_block" id="caller_dtls_block" class="case" value="1"> <span><img height="29" width="29" src="images/coller-icon.png" class="mCS_img_loaded"></span> CALLER DETAILS</h4>
                <form class="form-horizontal" style="padding-left:50px;">
                    <?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" style="padding-top:0px;">Contact :</label>
                            <div class="col-sm-10">
                                <?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo $EventSummaryDtls['CallerDtls']['phone_no']; } else {  echo "-"; } ?>
                            </div>
                        </div>
                    <?php } ?>
                     <?php if(!empty($EventSummaryDtls['CallerDtls']['mobile_no'])) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" style="padding-top:0px;">Mobile Number :</label>
                            <div class="col-sm-10">
                                <?php if(!empty($EventSummaryDtls['CallerDtls']['mobile_no'])) { echo $EventSummaryDtls['CallerDtls']['mobile_no']; } else {  echo "-"; } ?>
                            </div>
                        </div>
                     <?php } ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="padding-top:0px;">Name :</label>
                        <div class="col-sm-10">
                            <?php if(!empty($EventSummaryDtls['CallerDtls']['caller_last_name'])) { echo $EventSummaryDtls['CallerDtls']['caller_last_name']." "; } if(!empty($EventSummaryDtls['CallerDtls']['caller_first_name'])) { echo $EventSummaryDtls['CallerDtls']['caller_first_name']." "; }  if(!empty($EventSummaryDtls['CallerDtls']['caller_middle_name'])) { echo $EventSummaryDtls['CallerDtls']['caller_middle_name']; }  else {  echo ""; } ?>
                        </div>
                    </div>      
                    <?php if(!empty($EventSummaryDtls['CallerDtls']['relation'])) { ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="padding-top:0px;">Relation :</label>
                        <div class="col-sm-10">
                            <?php if(!empty($EventSummaryDtls['CallerDtls']['relation'])) { echo $EventSummaryDtls['CallerDtls']['relation']; } else {  echo "-"; } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if(!empty($EventSummaryDtls['CallerDtls']['email_id'])) { ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="padding-top:0px;">Email Address :</label>
                        <div class="col-sm-10">
                            <?php if(!empty($EventSummaryDtls['CallerDtls']['email_id'])) { echo $EventSummaryDtls['CallerDtls']['email_id']; } else {  echo "-"; } ?>
                        </div>
                    </div>
                <?php } ?>
            </form>
                <div class="line-seprator"></div>
            </div>
    <?php }
    ?>
    <?php 
    if(!empty($EventSummaryDtls['PatientDtls']))
    { ?>
        <div id="Block2">
            <h4 class="section-head"><input type="checkbox" name="patient_dtls_block" id="patient_dtls_block" class="case" value="2" /> <span><img height="29" width="29" src="images/patient-icon.png" class="mCS_img_loaded"></span> PATIENT DETAILS </h4>
            <form class="form-horizontal" style="padding-left:50px;">
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Name :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }  else {  echo ""; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Residential Address :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['residential_address'])) { echo $EventSummaryDtls['PatientDtls']['residential_address']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Permanent Address :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['permanant_address'])) { echo $EventSummaryDtls['PatientDtls']['permanant_address']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Location :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['locationNm'])) { echo $EventSummaryDtls['PatientDtls']['locationNm']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Pin Code :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['LocationPinCode'])) { echo $EventSummaryDtls['PatientDtls']['LocationPinCode']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Mobile :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['mobile_no'])) { echo $EventSummaryDtls['PatientDtls']['mobile_no']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Email Id :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['email_id'])) { echo $EventSummaryDtls['PatientDtls']['email_id']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Landline :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['phone_no'])) { echo $EventSummaryDtls['PatientDtls']['phone_no']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">DOB :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['dob']) && $EventSummaryDtls['PatientDtls']['dob'] !='0000-00-00' ) { echo date('d-m-Y',strtotime($EventSummaryDtls['PatientDtls']['dob'])) ; } else {  echo "-"; } ?>
                    </div>
                </div>
                <?php if(!empty($EventSummaryDtls['FamilyDoctorDtls'])) { ?>
                <div class="line-dotted"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Family Doctor :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['FamilyDoctorDtls']['name'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['name']." "; }  if(!empty($EventSummaryDtls['FamilyDoctorDtls']['first_name'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['first_name']." "; } if(!empty($EventSummaryDtls['FamilyDoctorDtls']['middle_name'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['middle_name']; } else {  echo ""; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Contact No :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['FamilyDoctorDtls']['mobile_no'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['mobile_no']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Email id :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['FamilyDoctorDtls']['email_id'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['email_id']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <?php } if(!empty($EventSummaryDtls['ConsultantDtls'])) { ?>
                <div class="line-dotted"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Consultant :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['ConsultantDtls']['name'])) { echo $EventSummaryDtls['ConsultantDtls']['name']." "; } if(!empty($EventSummaryDtls['ConsultantDtls']['first_name'])) { echo $EventSummaryDtls['ConsultantDtls']['first_name']." "; }  if(!empty($EventSummaryDtls['ConsultantDtls']['middle_name'])) { echo $EventSummaryDtls['ConsultantDtls']['middle_name']; }   else {  echo ""; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Contact No :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['ConsultantDtls']['mobile_no'])) { echo $EventSummaryDtls['ConsultantDtls']['mobile_no']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Email id :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($EventSummaryDtls['ConsultantDtls']['email_id'])) { echo $EventSummaryDtls['ConsultantDtls']['email_id']; } else {  echo "-"; } ?>
                    </div>
                </div>
            <?php } ?>
        </form>
            <div class="line-seprator"></div>
        </div>
<?php } ?>
    <?php 
        if(!empty($EventSummaryDtls['note']))
        { ?>
            <div id="Block3">
              <h4 class="section-head"><input type="checkbox" name="note_dtls_block" id="note_dtls_block" class="case" value="3" /> <span><img height="29" width="29" src="images/notes.png" class="mCS_img_loaded"></span>
                <?php if(!empty($heading_content)) { echo $heading_content; } else { echo "-"; }?>
              </h4>
              <form class="form-horizontal" style="padding-left:50px;">

                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Requirement :</label>
                    <div class="col-sm-10">
                      <?php if(!empty($enquiryReqDtls)) {
                            if (!empty($selectedServices) && !empty($selectedSubServices)) {
                                echo rtrim($selectedServices, ',') . "<br>" . rtrim($selectedSubServices, ',');
                            }
                        } else {  echo "-"; } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Enquiry Date :</label>
                    <div class="col-sm-10">
                        <?php if(!empty($getEventDetail['service_date_of_Enquiry'])) { echo date('d M Y', strtotime($getEventDetail['service_date_of_Enquiry'])); } else {  echo "-"; } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Note :</label>
                    <div class="col-sm-10">
                    <?php if(!empty($EventSummaryDtls['note'])) { echo $EventSummaryDtls['note']; } else {  echo "-"; } ?>
                    </div>
                </div>

                <?php if (!empty($getEventDetail['enquiry_cancellation_reason'])) { ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Cancellation Reason :</label>
                    <div class="col-sm-10">
                    <?php if(!empty($getEventDetail['enquiry_cancellation_reason'])) { echo $getEventDetail['enquiry_cancellation_reason']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <?php } ?>
                <!-- Professional List based on enquiry requirement start here -->
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Professional List:</label>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                                <th>Prof Code</th>
                                <th>Name</th>
                                <th>Distance (km)</th>
                            </thead>
                            <?php
                                if (!empty($professionalsList)) {
                                    foreach ($professionalsList AS $professionalVal) {
                                        echo '<tr>
                                                <td>' . $professionalVal['professional_code'] . '</td>
                                                <td>' . $professionalVal['professional_name'] . '</td>
                                                <td>' . $professionalVal['distanceKM'] . '</td>
                                            </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="3" style="text-align:center; color:#ff0000 !important;">No professional found</tr>';
                                }
                            ?>
                        </table>
                    </div>
                </div>
                <!-- Professional List based on enquiry requirement ends here -->


                <!-- Enquiry follow up list code start here -->
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Follow Up :</label>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                                <th>Name</th>
                                <th>Added Date</th>
                                <th>Note</th>
                                <th>isRead Status</th>
                            </thead>
                            <?php
                                if (!empty($enquiryFollowUpDtls)) {
                                    foreach ($enquiryFollowUpDtls AS $enquiryFollowUpVal) {
                                        echo '<tr>
                                                <td>' . $enquiryFollowUpVal['added_by_emp_name'] . '</td>
                                                <td>' . $enquiryFollowUpVal['added_date'] . '</td>
                                                <td>' . $enquiryFollowUpVal['follow_up_desc'] . '</td>
                                                <td>' . $enquiryFollowUpVal['isReadStatusVal'] . '</td>
                                            </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="3" style="text-align:center; color:#ff0000 !important;">No follow up details found</tr>';
                                }
                            ?>
                        </table>
                    </div>
                </div>
                <!-- Enquiry follow up list code end here -->

              </form>
            </div>
<?php } 
    ?>
    <?php 
    if(!empty($EventSummaryDtls['ReqDtls']))
    { ?>
        <div id="Block4">
            <h4 class="section-head"><input type="checkbox" name="requirement_dtls_block" id="requirement_dtls_block" class="case" value="4" /> <span><img height="29" width="29" src="images/requirnment-icon.png" class="mCS_img_loaded"></span>REQUIREMENTS</h4>
            <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <th width="30%">Service</th>
                    <th width="22%">Recommended Service</th>
                    </tr>
                </thead>
                <tbody>
            <?php 
                if(!empty($EventSummaryDtls['ReqDtls']))
                {
                    for($i=0;$i<count($EventSummaryDtls['ReqDtls']);$i++)
                    {
                    ?>
                    <tr>
                        <td><?php if(!empty($EventSummaryDtls['ReqDtls'][$i]['service_title'])) { echo $EventSummaryDtls['ReqDtls'][$i]['service_title']; } else { echo "-"; } ?></td>
                        <td><?php  if(!empty($EventSummaryDtls['ReqDtls'][$i]['recommomded_service'])) { echo $EventSummaryDtls['ReqDtls'][$i]['recommomded_service']; } else { echo "-"; } ?></td>
                    </tr>
            <?php 
                    }
                } 
                else 
                {
                    echo '<tr><td colspan="3" style="text-align:center;color:#FF0000;">No record found</td></tr>';
                }
            ?>
            </tbody>
            </table>
            <div class="line-seprator"></div>
        </div>
    <?php } ?>
    <?php
       // echo '<pre>';
       // print_r($EventSummaryDtls['plan_of_care']);
      //  echo '</pre>';
        $recListResponse=$EventSummaryDtls['plan_of_care'];
        $recList=$recListResponse['data'];
        $recListCount=$recListResponse['count'];
        if(!empty($recList))
        {	
		 ?>
            <div id="Block5">
                <h4 class="section-head"><input type="checkbox" name="plan_of_care_dtls_block" id="plan_of_care_dtls_block" class="case" value="5" /> <span><img height="29" width="29" src="images/plan-of-care.png" class="mCS_img_loaded"></span> PLAN OF CARE </h4>
                <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Recommended Service</th>
                            <th>Date (From/To)</th>
                            <th>Time (Form/To)</th>
                            <th>Cost <img src="images/rupee.png" style="vertical-align:inherit;" /></th>
                        </tr>
                    </thead>
                    <tbody>   	
        <?php
				if($recListCount)
				{
					$total_cost = 0; 
					$totalTax=0;
					$i=0;
					foreach ($recList as $recListKey => $recListValue) 
                                        {
						$sub_service_id = $recListValue['sub_service_id'];
						$service_id= $recListValue['service_id'];
						$event_requirement_id = $recListValue['event_requirement_id'];
						$reqPlanArr['event_id'] = $recListValue['event_id'];
						$reqPlanArr['event_requirement_id'] = $event_requirement_id;
						$reqPlanArr['sub_service_id'] = $sub_service_id;
						$requirementPlan = $eventClass->MultipleplanofcareRecords($reqPlanArr);
						$data = $requirementPlan['data'];
                                                
                                                
                        $query_package=mysql_query("SELECT * FROM sp_services where service_id='$service_id' ") or die(mysql_error());
		            	$query_package_row = mysql_fetch_array($query_package) or die(mysql_error());
		            	$Package_status=$query_package_row['Package_status'];
                                               //  echo '<pre>';
                                               // print_r($data);
                                              //  echo '</pre>';
                                                
                                                
						$st = 1; 
						$dateDiff = 1;
						if(!empty($data))
                                                {
							foreach($data as $key=>$valPlanCareMultiple)
                                                        {
								 $fromDate = '';
								 $toDate='';
								 $start_time='';
								 $endtime='';
								 
								 if($valPlanCareMultiple['service_date'] && $valPlanCareMultiple['service_date']!='0000-00-00')
									$fromDate = date('d-m-Y',strtotime($valPlanCareMultiple['service_date'])); //d-m-Y
								if($valPlanCareMultiple['service_date_to'] && $valPlanCareMultiple['service_date_to']!='0000-00-00')
									$toDate = date('d-m-Y',strtotime($valPlanCareMultiple['service_date_to']));
								if($valPlanCareMultiple['start_date'])
									$start_time =$valPlanCareMultiple['start_date'];
								if($valPlanCareMultiple['end_date'])
									$endtime = $valPlanCareMultiple['end_date'];
                                                                
								$diff = (strtotime($toDate)- strtotime($fromDate))/24/3600;
								$dateDiff = $diff+1;
								if($st == '1')
                                                                {
								    if($recListValue['recommomded_service']=='Other')
                                                                    {
                                                                        $cost =$valPlanCareMultiple['service_cost'];
                                                                    }
                                                                    else 
                                                                    {
                                                                      if(($Package_status==2) AND $sub_service_id!=425)
																					{
																						$cost =$recListValue['cost'];  
																					}
																					else
																					{
																						$cost = $dateDiff*$recListValue['cost']; 
																					}
                                                                    }
																	
                                                   if($recListValue['recommomded_service']=='Consultant Charges')
													   
												   {	
												      $query=mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_id' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$Consultant=$row['Consultant'];
			$hospital_id=$row['hospital_id'];
												   if($Consultant==0)
													{
														$telephonic_consultation_fees=0;
													}
													else{
												
			
			$query=mysql_query("SELECT * FROM sp_doctors_consultants where hospital_id='$hospital_id' and doctors_consultants_id='$Consultant' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$telephonic_consultation_fees=$row['telephonic_consultation_fees'];
			$first_name=$row['first_name'];
				$name=$row['name'];
				$Consent_name= $first_name .' '. $name ;
													}	
												   echo '<tr>
                                                            <td>'.$recListValue['service_title'].' </td>
                                                            <td>'.$recListValue['recommomded_service'].'</td>';
                                                            echo '<td>NA</td>';  
                                                            if(!empty($start_time) && !empty($endtime)) 
                                                           { 
                                                                  echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<td>NA</td>'; 
                                                            }
                                                            echo '<td>'.$telephonic_consultation_fees.'/-</td>
                                                        </tr>';
												   }
												   else{
												   
									echo '<tr>
                                                                                    <td>'.$recListValue['service_title'].' </td>
                                                                                    <td>'.$recListValue['recommomded_service'].'</td>';
                                                                                    if(!empty($fromDate) && !empty($toDate)) 
                                                                                    {  
                                                                                       echo '<td>'.$fromDate.' to '.$toDate.'</td>';
                                                                                    }
                                                                                    else 
                                                                                    {
                                                                                       echo '<td>NA</td>';  
                                                                                    }
                                                                                    if(!empty($start_time) && !empty($endtime)) 
                                                                                    { 
                                                                                        echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                                                    }
                                                                                    else 
                                                                                    {
                                                                                       echo '<td>NA</td>'; 
                                                                                    }
                                                                                    echo '<td>'.$cost.'/-</td>
                                                                                </tr>';
												   }
								}
								else 
								{
									if($recListValue['recommomded_service']=='Other')
                                                                        {
                                                                            $cost =$valPlanCareMultiple['service_cost'];
                                                                        }
                                                                        else 
                                                                        {
                                                                          if(($Package_status==2) AND $sub_service_id!=425)
																					{
																						$cost =$recListValue['cost'];  
																					}
																					else
																					{
																						$cost = $dateDiff*$recListValue['cost']; 
																					}
                                                                        }
																		          if($recListValue['recommomded_service']=='Consultant Charges')
													   
												   {
			$query=mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_id' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$Consultant=$row['Consultant'];
			$hospital_id=$row['hospital_id'];													   
												   if($Consultant==0)
													{
														$telephonic_consultation_fees=0;
													}
													else{
												
			
			$query=mysql_query("SELECT * FROM sp_doctors_consultants where hospital_id='$hospital_id' and doctors_consultants_id='$Consultant' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$telephonic_consultation_fees=$row['telephonic_consultation_fees'];
			$first_name=$row['first_name'];
				$name=$row['name'];
				$Consent_name= $first_name .' '. $name ;
													}
														
												   echo '<tr>
                                                            <td>'.$recListValue['service_title'].' </td>
                                                            <td>'.$recListValue['recommomded_service'].'</td>';
                                                            echo '<td>NA</td>';  
                                                            if(!empty($start_time) && !empty($endtime)) 
                                                           { 
                                                                  echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<td>NA</td>'; 
                                                            }
                                                            echo '<td>'.$telephonic_consultation_fees.'/-</td>
                                                        </tr>';
												   }
												   else{
									echo '<tr>
                                                                                <td>&nbsp;</td>
                                                                                <td>&nbsp;</td>';
                                                                                if(!empty($fromDate) && !empty($toDate)) 
                                                                                {  
                                                                                   echo '<td>'.$fromDate.' to '.$toDate.'</td>';
                                                                                }
                                                                                else 
                                                                                {
                                                                                   echo '<td>NA</td>';  
                                                                                }
                                                                                if(!empty($start_time) && !empty($endtime)) 
                                                                                { 
                                                                                    echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                                                }
                                                                                else 
                                                                                {
                                                                                   echo '<td>NA</td>'; 
                                                                                }
                                                                                if(!empty($fromDate) && !empty($toDate)) 
                                                                                {  
                                                                                   echo '<td>'.$cost.'/-</td>';
                                                                                }
                                                                                else 
                                                                               {
                                                                                  echo '<td>NA</td>';  
                                                                                }
                                                                               
                                                                            echo '</tr>';	
								}}
								$st++;
								
                                                                if(!empty($valPlanCareMultiple['service_cost']) && $valPlanCareMultiple['service_cost'] >'0.00')
                                                                {
                                                                   $total_cost += $valPlanCareMultiple['service_cost'];
                                                                }
                                                                else 
                                                                {
                                                                    $total_cost += $recListValue['cost']*$dateDiff;
                                                                }
                                                                
								$totalTax += $recListValue['tax'];
							}
						}
						else 
						{
							          if($recListValue['recommomded_service']=='Consultant Charges')
													   
												   {	
												      $query=mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_id' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$Consultant=$row['Consultant'];
			$hospital_id=$row['hospital_id'];
												   if($Consultant==0)
													{
														$telephonic_consultation_fees=0;
													}
													else{
												
			
			$query=mysql_query("SELECT * FROM sp_doctors_consultants where hospital_id='$hospital_id' and doctors_consultants_id='$Consultant' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$telephonic_consultation_fees=$row['telephonic_consultation_fees'];
			$first_name=$row['first_name'];
				$name=$row['name'];
				$Consent_name= $first_name .' '. $name ;
													}
														
												   echo '<tr>
                                                            <td>'.$recListValue['service_title'].' </td>
                                                            <td>'.$recListValue['recommomded_service'].'</td>';
                                                            echo '<td>NA</td>';  
                                                            if(!empty($start_time) && !empty($endtime)) 
                                                           { 
                                                                  echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<td>NA</td>'; 
                                                            }
                                                            echo '<td>'.$telephonic_consultation_fees.'/-</td>
                                                        </tr>';
												   }
												   else{
							echo '<tr>
                                                                <td>'.$recListValue['service_title'].' </td>
                                                                <td>'.$recListValue['recommomded_service'].'</td>';
                                                                if(!empty($fromDate) && !empty($toDate)) 
                                                                {  
                                                                   echo '<td>'.$fromDate.' to '.$toDate.'</td>';
                                                                }
                                                                else 
                                                                {
                                                                   echo '<td>NA</td>';  
                                                                }
                                                                if(!empty($start_time) && !empty($endtime)) 
                                                                { 
                                                                    echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                                }
                                                                else 
                                                                {
                                                                   echo '<td>NA</td>'; 
                                                                }
                                                                if(!empty($fromDate) && !empty($toDate)) 
                                                                {
                                                                    echo '<td>'.$recListValue['cost'].'/-</td>';
                                                                }
                                                                else 
                                                                {
                                                                   echo '<td>NA</td>'; 
                                                               }
                                                             echo '</tr>';
												   }
                                                        if(!empty($valPlanCareMultiple['service_cost']) && $valPlanCareMultiple['service_cost'] > '0.00')
                                                        {
                                                           $total_cost += $valPlanCareMultiple['service_cost'];
                                                        }
                                                        else 
                                                        {
                                                            $total_cost += $recListValue['cost'];
                                                        }      
                                                             
                                                             
                                                        $totalTax += $recListValue['tax'];
						}
						
						 echo '<tr><td colspan="5"><div><table><tr ><td colspan="5"></td></tr></table></div></td></tr>';
						$allRequirements[] = $event_requirement_id;
                                            $i++;
					}
					
					$passArray = implode(",",$allRequirements);	
					echo '<tr class="tax-row"><td colspan="4" style="text-align:right;"></td><td></td></tr>';
                    $totalTax = 0;
                    $totalEstimatedCost = ($total_cost + $totalTax + $telephonic_consultation_fees);
        			$finalcost = ($totalEstimatedCost - $getEventDetail['discount_amount']);
                    $finalcost = round($finalcost);

                    echo '<tr class="' . ($getEventDetail['discount_amount'] == '0.00' ? 'total-row' : '') . '">
                            <td colspan="4" style="text-align:left;">TOTAL ESTIMATED COST:</td>';
                                if(!empty($fromDate) && !empty($toDate)) 
                                {
                                    echo '<td>' . number_format($totalEstimatedCost, 2) . '/-</td>';
                                }
                                else 
                                {
                                    echo '<td>NA</td>'; 
                                }
                    echo '</tr>';

                    if ($getEventDetail['discount_amount'] != '0.00') {
                        echo '<tr>
                            <td colspan="4" style="text-align:left;">
                                TOTAL DISCOUNT COST:
                            </td>
                            <td>
                                ' . ($getEventDetail['discount_amount'] ? number_format($getEventDetail['discount_amount'], 2) . '/-' : 'NA') . '
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>';

                        echo '<tr class="total-row">
                            <td colspan="4" style="text-align:left;">
                                TOTAL ESTIMATED COST WITH DISCOUNT:
                            </td>
                            <td>
                                ' . ($finalcost ? number_format($finalcost, 2) . '/-' : 'NA') . '
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>';
                    }
					echo ' <tr><td colspan="4" class="text-right">Confirm Estimated Cost:</td> <td>';
                                        if(!empty($EventSummaryDtls['estimate_cost'])) 
                                        { 
                                            if($EventSummaryDtls['estimate_cost']=='1') 
                                            { 
                                              echo '<span class="available">Not set</span>';
                                            }  
                                            else if($EventSummaryDtls['estimate_cost']=='2') 
                                            { 
                                              echo '<span class="busy">No</span>'; 
                                            } 
                                            else if($EventSummaryDtls['estimate_cost']=='3') 
                                            { 
                                               echo '<span class="available">Yes</span>'; 
                                            } 
                                            else 
                                            { 
                                                echo "-"; 
                                            } 
                                        }
                 echo ' </td></tr>';
				}
     
          ?>
            </tbody>
            </table>
            <div class="line-seprator"></div>
        </div>
        <?php } ?>
    <?php 
        if(!empty($EventSummaryDtls['ProfessionalDtls']))
        { ?>
        <div id="Block6">
            <h4 class="section-head"><input type="checkbox" name="professional_dtls_block" id="professional_dtls_block" class="case" value="6" /> <span><img height="29" width="29" src="images/profesnals.png" class="mCS_img_loaded"></span> PROFESSIONAL </h4>
            <table  class="table table-bordered-hca" cellspacing="0" width="100%">
                <thead style="color:#fff !important">
                    <tr class="color-row">
                        <th width="15%">PROF.CODE</th>
                        <th width="18%">NAME</th>
                        <th width="10%">SKILL-SET</th>
                        <th width="10%">TYPE</th>
                        <th width="32%">LOCATION</th>
                        <th width="15%">SERVICE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php   
                        if(!empty($EventSummaryDtls['ProfessionalDtls']))
                        {
                            for($k=0;$k<count($EventSummaryDtls['ProfessionalDtls']);$k++)
                            {
                                ?>
                                <tr>
                                    <td><?php if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['professional_code'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['professional_code']; } else { echo "-"; } ?></td>
                                    <td><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['name'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['name']." "; }   if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['first_name'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['first_name']." "; }   if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['middle_name'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['middle_name']; } else { echo ""; } ?></td>
                                    <td><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['ProfOtherDtls']['skill_set'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['ProfOtherDtls']['skill_set']; } else { echo "-"; } ?></td>
                                    <td><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['reference_type'])) { if($EventSummaryDtls['ProfessionalDtls'][$k]['reference_type']=='1') { echo "Professional"; } else if($EventSummaryDtls['ProfessionalDtls'][$k]['reference_type']=='2') { echo "Vendor"; } } else { echo "-"; } ?></td>
                                    <td><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['locationNm'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['locationNm']; } else { echo "-"; } ?></td>
                                    <td><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['serviceNm'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['serviceNm']; } else { echo "-"; } ?></td>
                                </tr>
                    <?php 
                            }
                        } 
                        else 
                        {
                            echo '<tr><td colspan="6" style="text-align:center;color:#FF0000;">No Record Found</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
            <div class="line-seprator"></div>
        </div>
        <?php } ?>
    <?php 
        if(!empty($EventSummaryDtls['JobSummary']))
        { ?>
            <div id="Block7">
                <h4 class="section-head"><input type="checkbox" name="job_summary_dtls_block" id="job_summary_dtls_block" class="case" value="7" /> <span><img height="29" width="29" src="images/profesnals.png" class="mCS_img_loaded"></span> JOB SUMMARY </h4>
                <table  class="table table-bordered-hca" cellspacing="0" width="100%">
                    <thead style="color:#fff !important">
                        <tr class="color-row">
                            <th width="15%">PROF.CODE</th>
                            <th width="20%">NAME</th>
                            <th width="20%">SERVICE </th>
                            <th width="30%">INSTRUCTION</th>
                            <th width="10%">TYPE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                if(!empty($EventSummaryDtls['JobSummary']))
                                {
                                    for($l=0;$l<count($EventSummaryDtls['JobSummary']);$l++)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['ProfessionalId'])) { echo $EventSummaryDtls['JobSummary'][$l]['ProfessionalId']; } else { echo "-"; } ?></td>
                                            <td><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['ProfessionalNm'])) { echo $EventSummaryDtls['JobSummary'][$l]['ProfessionalNm']; } else { echo "-"; } ?></td>
                                            <td><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['ServiceNm'])) { echo $EventSummaryDtls['JobSummary'][$l]['ServiceNm']; } else { echo "-"; } ?></td>
                                            <td><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['Report_Inst'])) { echo $EventSummaryDtls['JobSummary'][$l]['Report_Inst']; } else { echo "-"; } ?></td>
                                            <td><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['MediaType'])) { echo $EventSummaryDtls['JobSummary'][$l]['MediaType']; } else { echo "-"; } ?></td>
                                        </tr>
                        <?php 
                                    }
                                } 
                                else 
                                {
                                    echo '<tr><td colspan="5" style="text-align:center;color:#FF0000;">No Record Found</td></tr>';
                                }
                           ?>
                   </tbody>
                </table>
            <div class="line-seprator"></div>
        </div>
        <?php } ?>
    <?php
        if(!empty($EventSummaryDtls['JobClosure']))
        { ?>
            <div id="Block8">
                <h4 class="section-head">
                    <input type="checkbox" name="job_closure_dtls_block" id="job_closure_dtls_block" class="case" value="8" /> 
                    <span><img height="29" width="29" src="images/feedback.png" class="mCS_img_loaded"></span> JOB CLOSURE 
                </h4>
                <?php
                    $AllJobClosureRecords=$EventSummaryDtls['JobClosure'];
                    for($i=0;$i<count($AllJobClosureRecords);$i++)
                    {
                        ?>
                            <form class="form-horizontal" style="padding-left:50px;">
                                <div class="col-lg-12">
                                    <h4><?php if(!empty($AllJobClosureRecords[$i]['professionalNm'])) { echo $AllJobClosureRecords[$i]['professionalNm']." "; } if(!empty($AllJobClosureRecords[$i]['service_date']) && $AllJobClosureRecords[$i]['service_date'] !='0000-00-00' ) { echo "(".date('d-m-Y',strtotime($AllJobClosureRecords[$i]['service_date'])).")"; }?></h4>
                                    <span class="color-text">Service Rendered : </span>
                                    <?php
                                         if(!empty($AllJobClosureRecords[$i]['service_render']))
                                         {
                                             if($AllJobClosureRecords[$i]['service_render']=='1')
                                             {
                                                 echo "Yes";
                                             }
                                             if($AllJobClosureRecords[$i]['service_render']=='2')
                                             {
                                                 echo "No";
                                             }
                                         }
                                    ?>
                                </div>
                                <?php 
                                if(!empty($AllJobClosureRecords[$i]['consumptions'])) { ?>
                                <div class="rounded-corner col-lg-12">
                                    <div class="color-text">Consumption Details:</div>
                                        <table cellpadding="3" cellspacing="0" class="table table-bordered-hca" width="100%">
                                            <thead style="color:#fff !important">
                                                <tr class="color-row">
                                                    <th>Medicine</th>
                                                    <th>Unit/Non Unit</th>
                                                    <th>Consumbale</th>
                                                    <th>Unit/Non Unit</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                 <?php
                                                        $UnitMedicineVal=array();
                                                        $UnitMedicineQty=array();
                                                        $NonUnitMedicineVal=array();
                                                        $NonUnitMedicineQty=array();
                                                        $UnitConsumbaleVal=array();
                                                        $UnitConsumbaleQty=array();
                                                        $NonUnitConsumbaleVal=array();
                                                        $NonUnitConsumbaleQty=array();
                                                        for($c=0;$c<count($AllJobClosureRecords[$i]['consumptions']);$c++)
                                                        {
                                                            if($AllJobClosureRecords[$i]['consumptions'][$c]['consumption_type']==1)
                                                            {
                                                                $UnitMedicineVal[]=$AllJobClosureRecords[$i]['consumptions'][$c]['name'];
                                                                $UnitMedicineQty[]=$AllJobClosureRecords[$i]['consumptions'][$c]['unit_quantity'];
                                                            }
                                                            else if($AllJobClosureRecords[$i]['consumptions'][$c]['consumption_type']==2)
                                                            {
                                                                $NonUnitMedicineVal[]=$AllJobClosureRecords[$i]['consumptions'][$c]['name'];
                                                                $NonUnitMedicineQty[]=$AllJobClosureRecords[$i]['consumptions'][$c]['unit_quantity'];
                                                            }
                                                            else if($AllJobClosureRecords[$i]['consumptions'][$c]['consumption_type']==3)
                                                            {
                                                                $UnitConsumbaleVal[]=$AllJobClosureRecords[$i]['consumptions'][$c]['name']; 
                                                                $UnitConsumbaleQty[]=$AllJobClosureRecords[$i]['consumptions'][$c]['unit_quantity']; 
                                                            }
                                                            else if($AllJobClosureRecords[$i]['consumptions'][$c]['consumption_type']==4)
                                                            {
                                                                $NonUnitConsumbaleVal[]=$AllJobClosureRecords[$i]['consumptions'][$c]['name']; 
                                                                $NonUnitConsumbaleQty[]=$AllJobClosureRecords[$i]['consumptions'][$c]['unit_quantity']; 
                                                            }

                                                            unset($AllJobClosureRecords[$i]['consumptions'][$c]['name']);
                                                            unset($AllJobClosureRecords[$i]['consumptions'][$c]['unit_quantity']);
                                                        }
                                                        
//                                                        echo '<pre>';
//                                                        print_r($UnitMedicineVal);
//                                                        echo '<br/>';
//                                                        print_r($UnitMedicineQty);
//                                                        echo '<br/>';
//                                                        print_r($NonUnitMedicineVal);
//                                                        echo '<br/>';
//                                                        print_r($NonUnitMedicineQty);
//                                                        echo '<br/>';
                                                        
                                                        $MedicineContent=array_merge($UnitMedicineVal,$NonUnitMedicineVal);
                                                        $MedicineQuantity=array_merge($UnitMedicineQty,$NonUnitMedicineQty);
                                                        $ConsumbaleContent=array_merge($UnitConsumbaleVal,$NonUnitConsumbaleVal);
                                                        $ConsumbaleQuantity=array_merge($UnitConsumbaleQty,$NonUnitConsumbaleQty); 
                                                        
                                                        // Get Count of Array
                                                        $loopVal=0;
                                                        $MedicineCount=count($MedicineContent);
                                                        $ConsumbaleCount=count($ConsumbaleContent);

                                                        if($MedicineCount>$ConsumbaleCount)
                                                        {
                                                           $loopVal=1;
                                                           $loopCondition=$MedicineContent;
                                                        }
                                                        else 
                                                          $loopCondition=$ConsumbaleContent;  
                                                        
                                                        for($s=0;$s<count($loopCondition);$s++)
                                                        {
                                                            ?>
                                                            <tr> 
                                                                <td> <?php echo $MedicineContent[$s];?></td>
                                                                <td><?php echo $MedicineQuantity[$s];?></td>
                                                                <td> <?php echo $ConsumbaleContent[$s];?></td>
                                                                <td><?php echo $ConsumbaleQuantity[$s];?></td>
                                                            </tr>
                                                            <?php 
                                                        }
                                                        
                                                         unset($UnitMedicineVal);
                                                         unset($UnitMedicineQty);
                                                         unset($NonUnitMedicineVal);
                                                         unset($NonUnitMedicineQty);
                                                         unset($UnitConsumbaleVal);
                                                         unset($UnitConsumbaleQty);
                                                         unset($NonUnitConsumbaleVal);
                                                         unset($NonUnitConsumbaleQty);
                                                 ?>
                                             </tbody>
                                        </table>   
                                </div>
                                <?php } if(!empty($AllJobClosureRecords[$i]['job_closure_file']) && file_exists("JobClosureDocuments/".$AllJobClosureRecords[$i]['job_closure_file'])) { ?>
                                	<div class="rounded-corner col-lg-12">
                                    	<img src="<?php echo $siteURL; ?>JobClosureDocuments/<?php echo $AllJobClosureRecords[$i]['job_closure_file']; ?>" width="" height="" alt="" />
                                    </div>
                                <?php } else { ?>
                                <div class="rounded-corner col-lg-12">
                                    <div class="color-text">Baseline:</div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div>
                                                    <?php 
                                                    if(!empty($AllJobClosureRecords[$i]['baseline'])) 
                                                    { 
                                                        if($AllJobClosureRecords[$i]['baseline']=='1') { echo "A"; }
                                                        else if($AllJobClosureRecords[$i]['baseline']=='2') { echo "V"; }
                                                        else if($AllJobClosureRecords[$i]['baseline']=='3') { echo "P"; }
                                                        else if($AllJobClosureRecords[$i]['baseline']=='4') { echo "U"; }
                                                        else { echo "-"; }
                                                    }
                                                    else 
                                                    { 
                                                        echo "-";        
                                                    } ?>
                                                </div>
                                                
                                                <table cellpadding="0" cellspacing="0" class="table-baseline">
                                                	<tr>
                                                    	<td><span class="color-text">Airway:</span></td>
                                                        <td><?php 
                                                    if(!empty($AllJobClosureRecords[$i]['airway'])) 
                                                    { 
                                                        if($AllJobClosureRecords[$i]['airway']=='1') { echo "Open"; }
                                                        else if($AllJobClosureRecords[$i]['airway']=='2') { echo "Close"; }
                                                        else { echo "-"; }
                                                    }
                                                   else 
                                                   { 
                                                       echo "-";   
                                                   } ?>  </td>
                                                   
                                                   <td width="20"></td>
                                                   <td><span class="color-text">Breathing:</span></td>
                                                        <td><?php 
                                                    if(!empty($AllJobClosureRecords[$i]['breathing'])) 
                                                    { 
                                                        if($AllJobClosureRecords[$i]['breathing']=='1') { echo "Present"; }
                                                        else if($AllJobClosureRecords[$i]['breathing']=='2') { echo "Compromised"; }
                                                        else if($AllJobClosureRecords[$i]['breathing']=='3') { echo "Absent"; }
                                                        else { echo "-"; }
                                                    }
                                                    else { echo "-"; } ?>  </td>
                                                    <td width="20"></td>
                                                    <td><span class="color-text">Circulation: </span></td>
                                                        <td><?php 
                                                    if(!empty($AllJobClosureRecords[$i]['circulation'])) 
                                                    { 
                                                        if($AllJobClosureRecords[$i]['circulation']=='1') { echo "Redial"; }
                                                        else if($AllJobClosureRecords[$i]['circulation']=='2') { echo "Present"; }
                                                        else if($AllJobClosureRecords[$i]['circulation']=='3') { echo "Absent"; }
                                                        else { echo "-"; }
                                                    }
                                                    else { echo "-"; } ?> </td>
                                                    </tr>
                                                    
                                                </table>
                                                
                                              
                                                <div class="clearfix margintop20"></div>
                                                <table cellpadding="3" cellspacing="0" class="table-baseline">
                                                    <tr>
                                                        <td><span class="color-text">Temp (Core)</span></td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['temprature'])) { echo $AllJobClosureRecords[$i]['temprature']; }  else { echo "-"; } ?></td>
                                                        <td>*F</td>
                                                        <td style="width:50px;"></td>
                                                        <td><span class="color-text">TBSL:</span></td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['bsl'])) { echo $AllJobClosureRecords[$i]['bsl']; }  else { echo "-"; } ?></td>
                                                        <td> mg/dl</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="color-text">Pulse: </span></td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['pulse'])) { echo $AllJobClosureRecords[$i]['pulse']; }  else { echo "-"; } ?></td>
                                                        <td>/min</td>
                                                        <td style="width:50px;"></td>
                                                        <td><span class="color-text">SpO2:</span> </td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['spo2'])) { echo $AllJobClosureRecords[$i]['spo2']; }  else { echo "-"; } ?></td>
                                                        <td> % </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="color-text">RR:</span></td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['rr'])) { echo $AllJobClosureRecords[$i]['rr']; }  else { echo "-"; } ?></td>
                                                        <td>/min</td>
                                                        <td style="width:50px;"></td>
                                                        <td><span class="color-text">GCS Total:</span></td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['gcs_total'])) { echo $AllJobClosureRecords[$i]['gcs_total']; }  else { echo "-"; } ?></td>
                                                        <td>/15</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="color-text">BP:</span></td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['high_bp']) && !empty($AllJobClosureRecords[$i]['low_bp'])) { echo $AllJobClosureRecords[$i]['high_bp']."/".$AllJobClosureRecords[$i]['low_bp']; }  else { echo "-"; } ?></td>
                                                        <td>/MmHg</td>
                                                        <td style="width:50px;"></td>
                                                        <td colspan="2"><span class="color-text">Skin Perfusion: </span>
                                                            <?php 
                                                            if(!empty($AllJobClosureRecords[$i]['skin_perfusion'])) 
                                                            { 
                                                                if($AllJobClosureRecords[$i]['skin_perfusion']=='1') { echo "Normal"; }
                                                                else if($AllJobClosureRecords[$i]['skin_perfusion']=='2') { echo "Abnormal"; }
                                                                else { echo "-"; }
                                                            }
                                                            else { echo "-"; } ?>   
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="col-lg-12 margintop10">
                                                <label class="name"><span class="color-text">Summary Note:</span></label>
                                                <div>
                                                    <?php if(!empty($AllJobClosureRecords[$i]['summary_note'])) { echo $AllJobClosureRecords[$i]['summary_note']; }  else { echo "-"; } ?>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                </div>
                                <?php } ?>
                            </form>  
                        <?php 
                    }
                ?>     
            </div>
             <div class="clearfix"></div>
             <div class="line-seprator"></div>
        <?php } ?>
    <?php 
        if(!empty($EventSummaryDtls['FeedbackDtls']))
        { ?>
            <div id="Block9">
                <h4 class="section-head">
                    <input type="checkbox" name="feedback_dtls_block" id="feedback_dtls_block" class="case" value="9" /> 
                    <span><img height="29" width="29" src="images/feedback.png" class="mCS_img_loaded"></span> FEEDBACK </h4>
        <table  class="table table-bordered-hca" cellspacing="0" width="100%">
        <tbody>
        <?php
              if(!empty($EventSummaryDtls['FeedbackDtls']))
              {
                  for($l=0;$l<count($EventSummaryDtls['FeedbackDtls']);$l++)
                  {
                      ?>
                        <tr>
                            <td colspan="4"> 
                                <?php if(!empty($EventSummaryDtls['FeedbackDtls'][$l][0]['service_date']) && $EventSummaryDtls['FeedbackDtls'][$l][0]['service_date'] !='0000-00-00') { echo "<b>".date('d-m-Y',  strtotime($EventSummaryDtls['FeedbackDtls'][$l][0]['service_date']))."</b>"; } else { echo ""; } ?>
                            </td>
                        </tr>
                      <?php 
                      if(!empty($EventSummaryDtls['FeedbackDtls'][$l])) 
                      { 
                            for($m=0;$m<count($EventSummaryDtls['FeedbackDtls'][$l]);$m++)
                            { ?>  
                              <tr>
                                  <td><?php echo $m+1; ?></td>
                                  <td><?php if(!empty($EventSummaryDtls['FeedbackDtls'][$l][$m]['question'])) { echo $EventSummaryDtls['FeedbackDtls'][$l][$m]['question']; } else { echo "-"; } ?></td>
                                  <td><?php if(!empty($EventSummaryDtls['FeedbackDtls'][$l][$m]['option_value'])) { echo $EventSummaryDtls['FeedbackDtls'][$l][$m]['option_value']; } else { echo "-"; } ?></td>
                                  <td><?php if(!empty($EventSummaryDtls['FeedbackDtls'][$l][$m]['answer'])) { echo $EventSummaryDtls['FeedbackDtls'][$l][$m]['answer']; } else { echo "-"; } ?></td>
                              </tr>
                              <?php 
                            }
                      }
                      else 
                      {
                          echo '<tr><td colspan="4" style="text-align:center;color:#FF0000;">No record found</td></tr>';
                      }
                  }
              }
              else 
              {
                  echo '<tr><td colspan="4" style="text-align:center;color:#FF0000;">No record found</td></tr>';
              }
            ?>
        </tbody>
        </table>
        </div>
        <?php } ?>
    <?php
        if(!empty($EventSummaryDtls['JobSummary']) && empty($EventSummaryDtls['JobClosure']) && empty($EventSummaryDtls['FeedbackDtls']))
        {
            ?>
             <div id="Block10">
                 <h4 class="section-head">
                     <input type="checkbox" name="consent_form_block" id="consent_form_block" class="case" value="10" /> 
                     <span><img height="29" width="29" src="images/patient-icon.png" class="mCS_img_loaded"></span> Consent 
                 </h4>
                      <div class="consent_form">
                            <div>I,
                              <label id="" style="font-weight:bold;padding-right: 25px;"><?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }?></label>
                              , Age
                              <label id="" style="font-weight:bold;padding-right: 25px;" class="label_small">
                                  <?php
                                    if(!empty($EventSummaryDtls['PatientDtls']['dob']) && $EventSummaryDtls['PatientDtls']['dob'] !='0000-00-00')
                                    {
                                         $birthDate = explode("-", $EventSummaryDtls['PatientDtls']['dob']);
                                        //get age from date or birthdate
                                        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md")
                                          ? ((date("Y") - $birthDate[0]) - 1)
                                          : (date("Y") - $birthDate[0])); 
                                       echo $age;  
                                    }
                                  ?>
                              </label>
                            </div>
                            <div>Residing at
                                <label id="" style="font-weight:bold;padding-right: 25px;">
                                    <?php if(!empty($EventSummaryDtls['PatientDtls']['residential_address'])) { echo $EventSummaryDtls['PatientDtls']['residential_address']; } ?>
                                </label>
                            </div>
                            <div>am suffering from
                              <label id="" style="font-weight:bold;"></label>
                              <span>(Provisional/Final Diagnosis)</span>
                            </div>
                            <div>and I,
                              <label id="" style="font-weight:bold;"></label>
                              <span>(Name of Relative),</span> Age
                              <label id="" class="label_small" style="font-weight:bold;">,</label>
                            </div>
                            <div>Residing at
                              <label id="" style="font-weight:bold;"></label>
                            </div>
                            <div>am made aware that
                                <label id="" style="font-weight:bold;padding-right: 25px;">
                                    <?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }?>
                                </label>
                              <span>(Name of Patient)</span> is suffering from
                              <label id="" style="font-weight:bold;"></label>
                              <span>(Provisional/Final Diagnosis)</span>
                            </div>
                            <ol style="margin-left:0px; padding-left:20px;">
                                <li>
                                  <div>That, above named patient has been taking treatment from
                                      <label  style="font-weight:bold;padding-right: 25px;">
                                          Dr. <?php 
										  $query=mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_id' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$Consultant=$row['Consultant'];
			$hospital_id=$row['hospital_id'];
			
			if($Consultant!=0)
			{
			$query=mysql_query("SELECT * FROM sp_doctors_consultants where hospital_id='$hospital_id' and doctors_consultants_id='$Consultant' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$telephonic_consultation_fees=$row['telephonic_consultation_fees'];
			$first_name=$row['first_name'];
				$name=$row['name'];
				$Consent_name= $first_name .' '. $name ;
			}
			else{
				$Consent_name='';
			}
										  echo  $Consent_name;?>
                                      </label>
                                    <span>(Name of Consultant) </span> for above mentioned disease.</div>
                                </li>
                                <li>
                                  <div>That, above named consultant has explained that, patient now can be treated/cared at home.</div>
                                </li>
								<?php
								$event_detail=mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_id'") or die(mysql_error());
								$row_count = mysql_num_rows($event_detail);
								if($row_count > 0)
								{
										$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
										$service_id=$event_detail['service_id']; 
										$sub_service_id=$event_detail['sub_service_id']; 		
										if(($service_id==17))
										{
								?>
											<li>
                                  <div>That, Assisted Living care professional has explained us all the details including 
                                    limitations, advantages, disadvantages, effects, side-effects, pros, cons etc. of Assisted
                                    Living care including instructions and rules Assisted Living Care
                                    <span>(Name of procedure).</span></div>
                                </li>
                                <li>
                                  <div>That, after understanding limitations of Assisted Living care all the details, I am giving free consent for
                                    providing Assisted Living care including Assisted Living care
                                    
                                    <span>(Name of procedure/service)</span> to
                                    <label id="" style="font-weight:bold;padding-right: 25px;">
                                        <?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }?>
                                    </label>
                                    <span>(Name of Patient).</span></div>
                                </li>
                                <li>
                                  <div>That, we have been made aware by Spero professional that, Spero Assisted Living care is involved only
                                    in executing medical instructions/advice's/orders of concerned consultant. Further, we have been made
                                    aware that, we shall contact/consult concerned consultant/Hospital in case of further medical management
                                    of disease condition.</div>
                                </li>
								<?php
										}
										else
										{
								?>
									<li>
                                  <div>That, Spero Home Health care professional has explained us all the details including 
                                    limitations, advantages, disadvantages, effects, side-effects, pros, cons etc. of Home
                                    Health care including instructions and rules
                                    <label style="font-weight:bold;"><?php if(!empty($recList[0]['recommomded_service'])) { echo $recList[0]['recommomded_service']; } else if(!empty($EventSummaryDtls['ProfessionalDtls'][0]['serviceNm'])) { echo $EventSummaryDtls['ProfessionalDtls'][0]['serviceNm']; } else { echo "-"; } ?></label>
                                    <span>(Name of procedure).</span></div>
                                </li>
                                <li>
                                  <div>That, after understanding limitations of Home Health Care all the details, I am giving free consent for
                                    providing Home Health Care including
                                    <label id="" style="font-weight:bold;padding-right: 25px;">
                                        <?php  if(!empty($recList[0]['recommomded_service'])) { echo $recList[0]['recommomded_service']; } else if(!empty($EventSummaryDtls['ProfessionalDtls'][0]['serviceNm'])) { echo $EventSummaryDtls['ProfessionalDtls'][0]['serviceNm']; } else { echo "-"; } ?>
                                    </label>
                                    <span>(Name of procedure/service)</span> to
                                    <label id="" style="font-weight:bold;padding-right: 25px;">
                                        <?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }?>
                                    </label>
                                    <span>(Name of Patient).</span></div>
                                </li>
                                <li>
                                  <div>That, we have been made aware by Spero professional that, Spero Home Health Care is involved only
                                    in executing medical instructions/advice's/orders of concerned consultant. Further, we have been made
                                    aware that, we shall contact/consult concerned consultant/Hospital in case of further medical management
                                    of disease condition.</div>
                                </li>
								<?php
								}
								}
								else
								{
								?>
									<li>
                                  <div>That, Spero Home Health care professional has explained us all the details including 
                                    limitations, advantages, disadvantages, effects, side-effects, pros, cons etc. of Home
                                    Health care including instructions and rules
                                    <label style="font-weight:bold;"><?php if(!empty($recList[0]['recommomded_service'])) { echo $recList[0]['recommomded_service']; } else if(!empty($EventSummaryDtls['ProfessionalDtls'][0]['serviceNm'])) { echo $EventSummaryDtls['ProfessionalDtls'][0]['serviceNm']; } else { echo "-"; } ?></label>
                                    <span>(Name of procedure).</span></div>
                                </li>
                                <li>
                                  <div>That, after understanding limitations of Home Health Care all the details, I am giving free consent for
                                    providing Home Health Care including
                                    <label id="" style="font-weight:bold;padding-right: 25px;">
                                        <?php  if(!empty($recList[0]['recommomded_service'])) { echo $recList[0]['recommomded_service']; } else if(!empty($EventSummaryDtls['ProfessionalDtls'][0]['serviceNm'])) { echo $EventSummaryDtls['ProfessionalDtls'][0]['serviceNm']; } else { echo "-"; } ?>
                                    </label>
                                    <span>(Name of procedure/service)</span> to
                                    <label id="" style="font-weight:bold;padding-right: 25px;">
                                        <?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; }  if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }?>
                                    </label>
                                    <span>(Name of Patient).</span></div>
                                </li>
                                <li>
                                  <div>That, we have been made aware by Spero professional that, Spero Home Health Care is involved only
                                    in executing medical instructions/advice's/orders of concerned consultant. Further, we have been made
                                    aware that, we shall contact/consult concerned consultant/Hospital in case of further medical management
                                    of disease condition.</div>
                                </li>
								<?php	
								}
								?>
                                
                                <li>
                                  <div>That, we have been made aware by Spero professional that, we shall follow all instructions/advice's/orders of 
                                    Hospital given by concerned consultant and we shall consult /visit concerned consultant/Hospital for follow -up, 
                                    if any. </div>
                                </li>
                                <li>
                                  <div>Patient / relative would not hire services directly from any of the employee of Spero Innovations Pvt. Ltd. In any form.</div>
                                </li>
                            </ol>
                            <div style="font-weight:bold;">Note :</div>
                            <ol style="margin-left:0px; padding-left:20px;">
                                <li>
                                  <div>Further, we have been made to understand that, Spero is different and independent legal entity than the hospital. Spero will be solely responsible,liable and answerable for Every services provided to the patient.Hospital will not be responsible,liable and answerable about the policy, practices and services provided by Spero and any untoward event arising thereof. Hence, in case of query/feedback etc. If any, we would contact Spero. </div>
                                </li>
                                <li>
                                  <div>We have been explained above information in our vernacular i.e Marathi/Hindi and after satisfaction, we are putting our signatures at place and date mentioned herein below.</div>
                                </li>
                            </ol>
                            <div style="clear:both; margin-top:20px;"></div>
                            <div class="col-lg-5 pull-left text-center" style="margin-top:20px;">Signature with Name of Patient</div>
                            <div class="col-lg-5 pull-right text-center" style="margin-top:20px;">Signature with Name of <br>Relative/Guardian/Attendant/Next Friend of Patient</div>
                      </div>
                      <div style="clear:both;"></div>
                     <div class="line-seprator"></div>
             </div>
             <div id="Block11">
                 <h4 class="section-head">
                    <input type="checkbox" name="job_closure_block" id="job_closure_block" class="case" value="11" /> 
                    <span><img height="29" width="29" src="images/feedback.png" class="mCS_img_loaded"></span> JOB CLOSURE 
                </h4>
                 <form style="padding-left:50px;" class="form-horizontal">
                    <div class="col-lg-12"> <span class="color-text">Service Rendered : </span> Yes
                        <input type="radio" value="1" id="service_rendered" name="service_rendered" />
                          &nbsp; No
                        <input type="radio" value="2" id="service_rendered" name="service_rendered" />
                    </div>
                    <div style="border: 1px solid #e3e3e3; border-radius: 8px; font-size: 12px;margin-bottom: 10px;margin-top: 10px;padding: 10px;">
                        <div style="color:#00cfcb;">Consumption Details:</div>
                        <div class="row">
                          <div class="col-lg-8">
                            <label style="color:#00cfcb;">Medicines:</label>
                            <br>
                            <br>
                            <label class="name">Unit:</label>
                            <span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;">   
                            </span> <br>
                            <br>
                            <label class="name">Non Unit:</label>
                            <span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;"></span> &nbsp;<span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;"></span></div>
                        </div>
                        <div class="row" style="margin-top:10px;">
                            <div class="col-lg-8">
                              <label style="color:#00cfcb;">Consumables:</label>
                              <br>
                              <br>
                              <label class="name">Unit:</label>
                              <span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;"></span><br>
                              <br>
                              <label class="name">Non Unit:</label>
                              <span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;"></span> </div>
                        </div>
                    </div>
                    <div class="rounded-corner col-lg-12">
                        <div style="color:#00cfcb;">Baseline:</div>
                        <div class="row">
                            <div class="col-lg-12">
                              
                              <table class="table-baseline" cellpadding="3" cellspacing="0">
                                  <tr>
                                      <td>A</td>
                                       <td> <input type="radio"  value="1" id="baseline" name="baseline"></td>
                                      <td>V</td>
                                       <td>  <input type="radio" value="2" id="baseline" name="baseline"></td>
                                      <td>P</td>
                                       <td> <input type="radio"  value="3" id="baseline" name="baseline"></td>
                                      <td>U</td>
                                      <td><input type="radio"  value="4" id="baseline" name="baseline"></td>
                                  </tr>
                              </table>
                              <table class="table-baseline" cellpadding="3" cellspacing="0">
                                  <tr>
                                      <td class="color-text">Airway:&nbsp;</td>
                                      <td>Open</td>
                                      <td><input type="radio" value="1" id="airway" name="airway"></td>
                                      <td>Close</td>
                                      <td> <input type="radio" value="2" id="airway" name="airway"></td>
                                      <td></td>
                                      <td></td>
                                      <td></td>
                                  </tr>
                                  <tr>
                                      <td class="color-text">Breathing:&nbsp; </td>
                                      <td>Present</td>
                                      <td><input type="radio" value="1" id="breathing" name="breathing"></td>
                                      <td> Compromised</td>
                                      <td><input type="radio" value="2" id="breathing" name="breathing"></td>
                                      <td> Absent</td>
                                      <td> <input type="radio" value="3" id="breathing" name="breathing"></td>
                                  </tr>
                                   <tr>
                                      <td class="color-text">Circulation:&nbsp; </td>
                                      <td> Redial</td>
                                      <td><input type="radio" value="1" id="circulation" name="circulation"></td>
                                      <td> Present</td>
                                      <td><input type="radio" value="2" id="circulation" name="circulation"></td>
                                      <td> Absent</td>
                                      <td>  <input type="radio"  value="3" id="circulation" name="circulation"></td>
                                  </tr>
                              </table>
                           
                            
                                <div class="" style="border-bottom: dotted 1px #d8d8d8; width:100%; display: inline-block;"></div>
                          
                             
                            <div class="clearfix"  style="margin-top:20px;"></div>
                            <table cellspacing="0" cellpadding="3" style="font-size:12px;">
                              <tbody>
                                <tr>
                                  <td width="80">Temp (Core)</td>
                                  <td style="border-bottom: dotted 1px #d8d8d8;" ><span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;"></span></td>
                                  <td>*F</td>
                                  <td style="width:50px;"></td>
                                  <td>TBSL:</td>
                                  <td style="border-bottom: dotted 1px #d8d8d8;"><span style="color:#666; padding:3px 40px 3px 5px; border-radius: 8px;"></span></td>
                                  <td> mg/dl</td>
                                </tr>
                                <tr>
                                  <td width="80">Pulse: </td>
                                  <td style="border-bottom: dotted 1px #d8d8d8;"><span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;"></span></td>
                                  <td>/min</td>
                                  <td style="width:50px;"></td>
                                  <td>SpO2: </td>
                                  <td style="border-bottom: dotted 1px #d8d8d8;"><span style="color:#666; padding:3px 40px 3px 5px;  border-radius: 8px;"></span></td>
                                  <td> % </td>
                                </tr>
                                <tr>
                                  <td width="80">RR:</td>
                                  <td style="border-bottom: dotted 1px #d8d8d8;"><span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;"></span></td>
                                  <td>/min</td>
                                  <td style="width:50px;"></td>
                                  <td>GCS Total:</td>
                                  <td style="border-bottom: dotted 1px #d8d8d8;"><span style="color:#666; padding:3px 40px 3px 5px;  border-radius: 8px;"></span></td>
                                  <td>/15</td>
                                </tr>
                                <tr>
                                  <td width="80">BP:</td>
                                  <td style="border-bottom: dotted 1px #d8d8d8;"><span style="color:#666; padding:3px 5px 3px 5px;  border-radius: 8px;"></span></td>
                                  <td>/MmHg</td>
                                  <td style="width:50px;"></td>
                                </tr>
                                <tr>
                                    <td class="color-text">Skin Perfusion:</td>
                                    <td>Normal:</td>
                                    <td><input type="radio" value="1" id="skin_perfusion" name="skin_perfusion"></td>
                                    <td>Abnormal:</td>
                                    <td><input type="radio" value="2" id="skin_perfusion" name="skin_perfusion"> </td>
                                    <td> </td>
                                    <td>  </td>
                                    
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div class="clearfix"></div>
                          <div class="col-lg-12" style="margin-top:20px;">
                              <div style="font-size:12px;" class="color-text"> Notes: <br>
                              
                                  <div class="col-lg-12" style="height: 100px; border: 1px solid #d8d8d8;"></div>
                              </div>
                            </div>
                          </div>
                          <div class="clearfix"></div>
                          <div style="clear:both; margin-top:20px;"></div>
                            <div class="col-lg-5 pull-left text-center" style="margin-top:20px;">Signature with Name of Patient</div>
                            <div class="col-lg-5 pull-right text-center" style="margin-top:20px;">Signature with Name of <br>Professional</div>
                        </div>
                 </form>
             </div>
            <?php 
        }
    ?>
  </div>
</div>
<?php
    
        
    ?>
<div class="modal-footer">
    <?php if ($getEventDetail['purpose_id'] == 2 && ($getEventDetail['enquiry_status'] == '1' || $getEventDetail['enquiry_status'] == '2')) { ?>
        
        <!-- Inquiry follow up section start here --->
        <a href="javascript:void(0);" onclick="followUpEnquiry('<?php echo $main_event_id; ?>')"  data-toggle="tooltip" data-original-title="Follow up" title="Follow up">
            <img src="images/follow-up.png" alt="Follow up" width = "30px" height="30px" />
        </a>
        <!-- Inquiry follow up section end here --->

        <a href="javascript:void(0);" onclick="cancelEnquiry('<?php echo $main_event_id; ?>')"  data-toggle="tooltip" data-original-title="Cancel" title="Cancel">
            <img src="images/cancel.png" alt="Cancel" width = "30px" height="30px" />
        </a>
        &nbsp;
        <a href="javascript:void(0);" onclick="convertEnquiryIntoService('<?php echo $main_event_id; ?>')"  data-toggle="tooltip" data-original-title="Convert" title="Convert">
            <img src="images/convert.png" alt="Convert" width = "26px" height="26px" />
        </a>&nbsp; 
    <?php } ?>
    <a href="javascript:void(0);" onclick="ViewEventActions('<?php echo $main_event_id; ?>','','continue')"  data-toggle="tooltip" data-original-title="Continue" title="Continue">
        <img src="images/continue.png" alt="Continue" />
    </a>&nbsp; 
    <a href="javascript:void(0);" onclick="ViewEventActions('<?php echo $main_event_id; ?>','','download')"  data-toggle="tooltip" title="Download PDF">
        <img alt="Download PDF" src="images/pdf-icon.png" />
    </a>&nbsp; 
    <a href="javascript:void(0);" onclick="ViewEventActions('<?php echo $main_event_id; ?>','','email')" data-toggle="tooltip" title="Email">
        <img alt="Email" src="images/send-mail.png" />
    </a> 
</div>
<?php  
}
?>
