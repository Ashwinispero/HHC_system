<?php 	require_once('inc_classes.php'); 
        require_once '../classes/eventClass.php';
        $eventClass=new eventClass();
		require_once '../classes/config.php';
?>
<?php
if(!$_SESSION['admin_user_id'])
    echo 'notLoggedIn';
else
{

    include "pagination-include.php";
    
    if($_POST['SearchKey'] && $_POST['SearchKey']!="undefined")
        $search_Value=$_POST['SearchKey'];
    else
        $search_Value=""; 
      
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['admin_id']=$_SESSION['admin_user_id'];
    
    if(isset($_REQUEST['patient_id']))
        $recArgs['patient_id']=base64_decode($_REQUEST['patient_id']);
    
    if(isset($_REQUEST['event_id']))
        $recArgs['event_id']=base64_decode($_REQUEST['event_id']);
    else if(isset($_REQUEST['record_id']))
        $recArgs['event_id']=$_REQUEST['record_id'];
    
    //var_dump($recArgs);
   //print_r($recArgs);
    $recListResponse= $eventClass->GetEventSummary($recArgs);
    
    
    $getEventDetail = $eventClass->GetEvent($recArgs);

    // echo '<pre>';
    // print_r($getEventDetail);
    // echo '</pre>';
    // exit;
    
    if(!empty($getEventDetail))
    {
        $event_code = $getEventDetail['event_code'];
        $event_date = date('d M Y H:i A',strtotime($getEventDetail['event_date']));
        $eventAddedDate = date('d M Y H:i A',strtotime($getEventDetail['added_date']));
        $eventModifiedDate = date('d M Y H:i A',strtotime($getEventDetail['last_modified_date']));
        $enquiryAddedDate = ((!empty($getEventDetail['enquiry_added_date']) && $getEventDetail['enquiry_added_date'] != '0000-00-00 00:00:00') ? date('d M Y H:i A',strtotime($getEventDetail['enquiry_added_date'])) : '');
        $enquiryCancelDate = ((!empty($getEventDetail['enquiry_cancel_date']) && $getEventDetail['enquiry_cancel_date'] != '0000-00-00 00:00:00') ? date('d M Y H:i A',strtotime($getEventDetail['enquiry_cancel_date'])) : '');
    }
    else 
    {
        $event_code ="";
        $event_date ="";
    }
    
     $sql_call_purpose="SELECT name FROM sp_purpose_call WHERE purpose_id='".$recListResponse['CallerDtls']['purpose_id']."'";
     $row_call_purpose=$db->fetch_array($db->query($sql_call_purpose));
     $call_purposeName =$row_call_purpose['name']; 

    if(empty($recListResponse))
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    if(recListResponse)
    {
        if($recListResponse['CallerDtls']['purpose_id']=='2' || $recListResponse['CallerDtls']['purpose_id']=='6')
        {
           if($recListResponse['CallerDtls']['purpose_id']=='2') 
           {
               $heading_content="ENQUIRY NOTE";
           }
           else 
           {
              $heading_content="GENERAL INFORMATION"; 
           }
        } 
        else 
        {
           $heading_content="NOTE";  
        }
    ?>    
        <div class="col-lg-12" style="padding-left:0px;" id="AllAjaxData">
            <div class="row">
            <div class="col-lg-11 title_holder">
                 <h1 class="page-header">
                     View <?php echo $event_code;?> Event Details (<?php echo $call_purposeName;?>)
                    <ul class="nav navbar-right pull-right top-nav" style="margin-top:10px; padding-left:20px; padding-right:0px;">
                        <li style="margin-right:10px;">
                            <a href="javascript:void(0);" style="padding:0px;" onclick="downloadPDFReport('<?php echo $recArgs['event_id']; ?>')" class="pull-right" data-toggle="tooltip"  data-original-title="Download Report"><img src="images/download.png" width="23" height="23" alt="Download"></a>
                        </li>
                    </ul>
                 </h1>
                <div style="font-family:futura_lt_btlight;color: #666;font-size: 20px;">
                    <?php if(!empty($recListResponse['PatientDtls']['hhc_code'])) { echo "HHC No. :".$recListResponse['PatientDtls']['hhc_code']; } ?>
                    <span class="nav navbar-right pull-right top-nav" style="margin-top:10px; padding-left:20px; padding-right:0px;"> Event Date :<?php echo $event_date;?></span>
                </div>
            </div>
        </div>
        <?php 
		if(!empty($recListResponse['CallerDtls'])) { ?>
            <div class="row" id="Block1">
                <h4 class="section-head text-left">
                        <span>
                            <img height="29" width="29" src="images/coller-icon.png" class="mCS_img_loaded">
                        </span> 
                        CALLER DETAILS
                </h4>
                <form class="form-horizontal" style="padding-left:50px;">
                    <?php if(!empty($recListResponse['CallerDtls']['phone_no'])) { ?>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" style="padding-top:0px;">Contact :</label>
                      <div class="col-sm-10">
                          <?php if(!empty($recListResponse['CallerDtls']['phone_no'])) { echo $recListResponse['CallerDtls']['phone_no']; } else {  echo "-"; } ?>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if(!empty($recListResponse['CallerDtls']['mobile_no'])) { ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" style="padding-top:0px;">Mobile Number :</label>
                            <div class="col-sm-10">
                                <?php if(!empty($recListResponse['CallerDtls']['mobile_no'])) { echo $recListResponse['CallerDtls']['mobile_no']; } else {  echo "-"; } ?>
                            </div>
                        </div>
                     <?php } ?>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" style="padding-top:0px;">Name :</label>
                      <div class="col-sm-10">
                          <?php if(!empty($recListResponse['CallerDtls']['caller_last_name'])) { echo $recListResponse['CallerDtls']['caller_last_name']." "; } if(!empty($recListResponse['CallerDtls']['caller_first_name'])) { echo $recListResponse['CallerDtls']['caller_first_name']." "; } if(!empty($recListResponse['CallerDtls']['caller_middle_name'])) { echo $recListResponse['CallerDtls']['caller_middle_name']." "; } else {  echo ""; } ?>
                      </div>
                    </div>
                    <?php if(!empty($recListResponse['CallerDtls']['relation'])) { ?>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" style="padding-top:0px;">Relation :</label>
                      <div class="col-sm-10">
                           <?php if(!empty($recListResponse['CallerDtls']['relation'])) { echo $recListResponse['CallerDtls']['relation']; } else {  echo "-"; } ?>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if(!empty($recListResponse['CallerDtls']['email_id'])) { ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" style="padding-top:0px;">Email Address :</label>
                        <div class="col-sm-10">
                            <?php if(!empty($recListResponse['CallerDtls']['email_id'])) { echo $recListResponse['CallerDtls']['email_id']; } else {  echo "-"; } ?>
                        </div>
                    </div>
                <?php } ?>
                 </form>
            </div>
        <div class="line-seprator"></div>
        <?php } ?>
        <?php
			 if(!empty($recListResponse['PatientDtls'])) { ?>
            <div class="row" id="Block2">
                    <h4 class="section-head"><span><img height="29" width="29" src="images/patient-icon.png" class="mCS_img_loaded"></span> PATIENT DETAILS </h4>
                    <form class="form-horizontal" style="padding-left:50px;">
                <div class="form-group">
                 <label class="col-sm-2 control-label" style="padding-top:0px;">Name :</label>
                 <div class="col-sm-10">
                      <?php if(!empty($recListResponse['PatientDtls']['name'])) { echo $recListResponse['PatientDtls']['name']." "; } if(!empty($recListResponse['PatientDtls']['first_name'])) { echo $recListResponse['PatientDtls']['first_name']." "; }  if(!empty($recListResponse['PatientDtls']['middle_name'])) { echo $recListResponse['PatientDtls']['middle_name']; }  else {  echo "-"; } ?>
                 </div>
                </div>
                <div class="form-group">
                 <label class="col-sm-2 control-label" style="padding-top:0px;">Residential Address :</label>
                 <div class="col-sm-10">
                     <?php if(!empty($recListResponse['PatientDtls']['residential_address'])) { echo $recListResponse['PatientDtls']['residential_address']; } else {  echo "-"; } ?>
                 </div>
                </div>
                <div class="form-group">
                 <label class="col-sm-2 control-label" style="padding-top:0px;">Permanent Address :</label>
                 <div class="col-sm-10">
                     <?php if(!empty($recListResponse['PatientDtls']['permanant_address'])) { echo $recListResponse['PatientDtls']['permanant_address']; } else {  echo "-"; } ?>
                 </div>
                </div>
                <div class="form-group">
                 <label class="col-sm-2 control-label" style="padding-top:0px;">Location :</label>
                 <div class="col-sm-10">
                     <?php if(!empty($recListResponse['PatientDtls']['locationNm'])) { echo $recListResponse['PatientDtls']['locationNm']; } else {  echo "-"; } ?>
                 </div>
                </div>
                <div class="form-group">
                     <label class="col-sm-2 control-label" style="padding-top:0px;">Pin Code :</label>
                     <div class="col-sm-10">
                         <?php if(!empty($recListResponse['PatientDtls']['LocationPinCode'])) { echo $recListResponse['PatientDtls']['LocationPinCode']; } else {  echo "-"; } ?>
                     </div>
                </div>
                <div class="form-group">
                 <label class="col-sm-2 control-label" style="padding-top:0px;">Mobile :</label>
                 <div class="col-sm-10">
                      <?php if(!empty($recListResponse['PatientDtls']['mobile_no'])) { echo $recListResponse['PatientDtls']['mobile_no']; } else {  echo "-"; } ?>
                 </div>
                </div>
                <div class="form-group">
                 <label class="col-sm-2 control-label" style="padding-top:0px;">Email Id :</label>
                 <div class="col-sm-10">
                     <?php if(!empty($recListResponse['PatientDtls']['email_id'])) { echo $recListResponse['PatientDtls']['email_id']; } else {  echo "-"; } ?>
                 </div>
                </div>
                <div class="form-group">
                 <label class="col-sm-2 control-label" style="padding-top:0px;">Landline :</label>
                 <div class="col-sm-10">
                     <?php if(!empty($recListResponse['PatientDtls']['phone_no'])) { echo $recListResponse['PatientDtls']['phone_no']; } else {  echo "-"; } ?>
                 </div>
                </div>
                <div class="form-group">
                     <label class="col-sm-2 control-label" style="padding-top:0px;">DOB :</label>
                     <div class="col-sm-10">
                         <?php if(!empty($recListResponse['PatientDtls']['dob']) && $recListResponse['PatientDtls']['dob'] !='0000-00-00' ) { echo date('d/m/Y',strtotime($recListResponse['PatientDtls']['dob'])) ; } else {  echo "-"; } ?>
                     </div>
                </div>

                <?php
				 if(!empty($recListResponse['FamilyDoctorDtls'])) { ?> 
                       <div class="line-dotted"></div>
                    <div class="form-group">
                         <label class="col-sm-2 control-label" style="padding-top:0px;">Family Doctor :</label>
                         <div class="col-sm-10">
                             <?php if(!empty($recListResponse['FamilyDoctorDtls']['name'])) { echo $recListResponse['FamilyDoctorDtls']['name']." "; } if(!empty($recListResponse['FamilyDoctorDtls']['first_name'])) { echo $recListResponse['FamilyDoctorDtls']['first_name']." "; } if(!empty($recListResponse['FamilyDoctorDtls']['middle_name'])) { echo $recListResponse['FamilyDoctorDtls']['middle_name']; } else {  echo ""; } ?>
                         </div>
                     </div>
                     <div class="form-group">
                         <label class="col-sm-2 control-label" style="padding-top:0px;">Contact No :</label>
                         <div class="col-sm-10">
                             <?php if(!empty($recListResponse['FamilyDoctorDtls']['mobile_no'])) { echo $recListResponse['FamilyDoctorDtls']['mobile_no']; } else {  echo "-"; } ?>
                         </div>
                     </div>
                     <div class="form-group">
                         <label class="col-sm-2 control-label" style="padding-top:0px;">Email id :</label>
                         <div class="col-sm-10">
                             <?php if(!empty($recListResponse['FamilyDoctorDtls']['email_id'])) { echo $recListResponse['FamilyDoctorDtls']['email_id']; } else {  echo "-"; } ?>
                         </div>
                     </div>
                <?php }
				 if(!empty($recListResponse['ConsultantDtls'])) { ?>
                       <div class="line-dotted"></div>
                    <div class="form-group">
                         <label class="col-sm-2 control-label" style="padding-top:0px;">Consultant :</label>
                         <div class="col-sm-10">
                             <?php if(!empty($recListResponse['ConsultantDtls']['name'])) { echo $recListResponse['ConsultantDtls']['name']; } if(!empty($recListResponse['ConsultantDtls']['first_name'])) { echo $recListResponse['ConsultantDtls']['first_name']; } if(!empty($recListResponse['ConsultantDtls']['middle_name'])) { echo $recListResponse['ConsultantDtls']['middle_name']; } else {  echo "-"; } ?>
                         </div>
                    </div>
                    <div class="form-group">
                     <label class="col-sm-2 control-label" style="padding-top:0px;">Contact No :</label>
                     <div class="col-sm-10">
                         <?php if(!empty($recListResponse['ConsultantDtls']['mobile_no'])) { echo $recListResponse['ConsultantDtls']['mobile_no']; } else {  echo "-"; } ?>
                     </div>
                       </div>
                   <div class="form-group">
                     <label class="col-sm-2 control-label" style="padding-top:0px;">Email id :</label>
                     <div class="col-sm-10">
                         <?php if(!empty($recListResponse['ConsultantDtls']['email_id'])) { echo $recListResponse['ConsultantDtls']['email_id']; } else {  echo "-"; } ?>
                     </div>
                       </div>
                <?php } ?>
                </form>
            </div>
            <div class="line-seprator"></div>
         <?php } ?>
         <?php if(!empty($recListResponse['note'])) { ?>
            <div class="row" id="Block3">
                <h4 class="section-head"><span><img height="29" width="29" src="images/requirnment-icon.png" class="mCS_img_loaded"></span><?php if(!empty($heading_content)) { echo $heading_content; } else { echo "-"; }?></h4>
                <form class="form-horizontal" style="padding-left:50px;">
                    <div class="form-group">
                      <label class="col-sm-2 control-label" style="padding-top:0px;"></label>
                      <div class="col-sm-10">
                          <?php if(!empty($recListResponse['note'])) { echo $recListResponse['note']; } else {  echo "-"; } ?>
                      </div>
                    </div>
                </form>
            </div>
            <div class="line-seprator"></div>
        <?php } ?>
        <?php if(!empty($recListResponse['ReqDtls'])) { ?>
            <div class="row" id="Block4">
                 <h4 class="section-head"><span><img height="29" width="29" src="images/requirnment-icon.png" class="mCS_img_loaded"></span>REQUIREMENTS</h4>
                 <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                    <thead> 
                    <tr>
                    <th width="30%">Service</th>
                    <th width="22%">Recommended Service</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(!empty($recListResponse['ReqDtls']))
                            {
                                    for($i=0;$i<count($recListResponse['ReqDtls']);$i++)
                                    {
                                            ?>
                                                <tr>
                                                   <td><?php if(!empty($recListResponse['ReqDtls'][$i]['service_title'])) { echo $recListResponse['ReqDtls'][$i]['service_title']; } else { echo "-"; } ?></td>
                                                   <td><?php  if(!empty($recListResponse['ReqDtls'][$i]['recommomded_service'])) { echo $recListResponse['ReqDtls'][$i]['recommomded_service']; } else { echo "-"; } ?></td>    
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
            </div>
            <div class="line-seprator"></div>
         <?php } ?>

        <!-- Event details code section start here -->
        <?php 
            if(!empty($getEventDetail)) {
                ?>
                    <div class="row" id="Block11">
                        <h4 class="section-head">
                            <span>
                                <img height="29" width="29" src="images/patient-icon.png" class="mCS_img_loaded">
                            </span>
                            Other Details
                        </h4>
                        <form class="form-horizontal" style="padding-left:50px;">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="padding-top:0px;">Event Code :</label>
                                <div class="col-sm-10">
                                    <?php if(!empty($getEventDetail['event_code'])) { echo $getEventDetail['event_code']; } else {  echo "-"; } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="padding-top:0px;">Added By :</label>
                                <div class="col-sm-10">
                                    <?php if(!empty($getEventDetail['addedByNm'])) { echo $getEventDetail['addedByNm']; } else {  echo "-"; } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="padding-top:0px;">Enquiry Added Date :</label>
                                <div class="col-sm-10">
                                    <?php if(!empty($enquiryAddedDate)) { echo $enquiryAddedDate; } else {  echo "-"; } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="padding-top:0px;">Added Date :</label>
                                <div class="col-sm-10">
                                    <?php if(!empty($eventAddedDate)) { echo $eventAddedDate; } else {  echo "-"; } ?>
                                </div>
                            </div>

                            <?php if (!empty($enquiryCancelDate)) { ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="padding-top:0px;">Enquiry Cancel Date :</label>
                                    <div class="col-sm-10">
                                        <?php if(!empty($enquiryCancelDate)) { echo $enquiryCancelDate; } else {  echo "-"; } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" style="padding-top:0px;">Enquiry Cancellation Reason :</label>
                                    <div class="col-sm-10">
                                        <?php if(!empty($getEventDetail['enquiry_cancellation_reason'])) { echo $getEventDetail['enquiry_cancellation_reason']; } else {  echo "-"; } ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="padding-top:0px;">Modified By :</label>
                                <div class="col-sm-10">
                                    <?php if(!empty($getEventDetail['modifiedByNm'])) { echo $getEventDetail['modifiedByNm']; } else {  echo "-"; } ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" style="padding-top:0px;">Modified Date :</label>
                                <div class="col-sm-10">
                                    <?php if(!empty($eventModifiedDate)) { echo $eventModifiedDate; } else {  echo "-"; } ?>
                                </div>
                            </div>
                        </form>
                    </div>
            <div class="line-seprator"></div>
                <?php
            }
        ?>
        <!-- Event details code section end here -->
         <?php 
                $recListResponseData=$recListResponse['plan_of_care'];
                $recList=$recListResponseData['data'];
                $recListCount=$recListResponseData['count'];   
            if(!empty($recList)) { ?>    
            <div class="row" id="Block5">
                <h4 class="section-head"><span><img height="29" width="29" src="images/plan-of-care.png" class="mCS_img_loaded"></span> PLAN OF CARE </h4>
                <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Service</th>
                        <th>Recommended Service</th>
                        <th>Date (From/To)</th>
                        <th>Time (From/To)</th>
                        <th>Cost <img src="images/rupee.png" style="vertical-align:initial;" /></th>
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
                                    $event_requirement_id = $recListValue['event_requirement_id'];
                                    $reqPlanArr['event_id'] = $recListValue['event_id'];
                                    $reqPlanArr['event_requirement_id'] = $event_requirement_id;
                                    $reqPlanArr['sub_service_id'] = $sub_service_id;
                                    $requirementPlan = $eventClass->MultipleplanofcareRecords($reqPlanArr);
                                    $data = $requirementPlan['data'];
                                    $st = 1; 
                                    $dateDiff = 1;
                                    if(!empty($requirementPlan['data']))
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
                                                  $cost = $dateDiff*$recListValue['cost'];
                                                } 
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
                                                                echo '<td>'.$cost.'/-</td>';
                                                             }
                                                             else 
                                                             {
                                                                 echo '<td>NA</td>';
                                                             }
                                                        echo '</tr>';
                                           }
                                           else 
                                           {
                                                if($recListValue['recommomded_service']=='Other')
                                                {
                                                    $cost =$valPlanCareMultiple['service_cost'];
                                                }
                                                else 
                                                {
                                                  $cost = $dateDiff*$recListValue['cost'];
                                                }
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
                                           }
                                           $st++;
                                           if(!empty($valPlanCareMultiple['service_cost']) && $valPlanCareMultiple['service_cost'] > '0.00')
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

                                       // echo '<tr><td colspan="5"><div><table><tr ><td colspan="5"></td></tr></table></div></td></tr>';
                                       $allRequirements[] = $event_requirement_id;
                                       $i++;
                                    }
                                        $passArray = implode(",",$allRequirements);	
                                        //echo '<tr class="tax-row"><td colspan="4" style="text-align:right;"></td><td></td></tr>';
                                        $totalTax = 0;
                                        $totalEstimatedCost = ($total_cost + $totalTax);
                                        $finalcost = ($totalEstimatedCost - $getEventDetail['discount_amount']);

                                        echo '<tr class="total-row">
                                        <td colspan="4" style="text-align:left;">
                                            TOTAL ESTIMATED COST:
                                        </td>';
                                            if(!empty($fromDate) && !empty($toDate))
                                            {
                                                echo '<td>'.$totalEstimatedCost.'/-</td>';
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
                                        if(!empty($recListResponse['estimate_cost'])) 
                                        { 
                                            if($recListResponse['estimate_cost']=='1') 
                                            { 
                                              echo '<span class="available">Not set</span>';
                                            }  
                                            else if($recListResponse['estimate_cost']=='2') 
                                            { 
                                              echo '<span class="busy">No</span>'; 
                                            } 
                                            else if($recListResponse['estimate_cost']=='3') 
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
                            else 
                            {
                               echo '<tr><td colspan="5" style="text-align:center;color:#FF0000;">No record found</td></tr>'; 
                            }
                      ?>
                    </tbody>
                </table>
            </div>
            <div class="line-seprator"></div>
         <?php }  ?>
		 
            <div class="row" id="Block10">
                <h4 class="section-head"><span><img height="29" width="29" src="images/plan-of-care.png" class="mCS_img_loaded"></span> Manage Payment </h4>
                <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Date</th>
						<th>Type</th>
                        <th>Amount</th>
                        <th>Comments</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <?php
					  	$query = mysql_query("SELECT * FROM sp_events WHERE event_code='$event_code'");
						$b=mysql_fetch_array($query);
                        $event_id= $b['event_id'];
						
						$payments = mysql_query("SELECT * FROM sp_payments WHERE event_id='$event_id' ORDER BY date_time ASC");
                         
								
							while($row=mysql_fetch_array($payments))
							{
								
							?>	
								<tr>
                                             <td><?php echo $row['date_time']; ?></td>
                                             <td><?php echo $row['type']; ?></td>
                                             <td><?php echo $row['amount']; ?></td>
                                             <td></td>
                                             
                                </tr>
								
							<?php
								
							}
						echo "</table>";
                      ?>
                    </tbody>
                </table>
            </div>
            <div class="line-seprator"></div>
        
		 
		 
         <?php 
		 	//echo '<pre>';
			//print_r($recListResponse['ProfessionalDtls']);
			//echo '</pre>';
		 
		 if(!empty($recListResponse['ProfessionalDtls'])) { ?> 
            <div class="row" id="Block6">
                <h4 class="section-head"><span><img height="29" width="29" src="images/profesnals.png" class="mCS_img_loaded"></span> PROFESSIONAL </h4>
                <table  class="table table-bordered-hca" cellspacing="0" width="100%">
                    <thead style="color:#fff !important">
                        <tr class="color-row">
                        <th>PROF.CODE</th>
                        <th>NAME</th>
                        <th>SKILL-SET</th>
                        <th>TYPE</th>
                        <th>LOCATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(!empty($recListResponse['ProfessionalDtls']))
                            {
                                for($k=0;$k<count($recListResponse['ProfessionalDtls']);$k++)
                                {
                                    ?>
                                         <tr>
                                             <td><?php if(!empty($recListResponse['ProfessionalDtls'][$k]['professional_code'])) { echo $recListResponse['ProfessionalDtls'][$k]['professional_code']; } else { echo "-"; } ?></td>
                                             <td><?php  if(!empty($recListResponse['ProfessionalDtls'][$k]['name'])) { echo $recListResponse['ProfessionalDtls'][$k]['name']." "; }  if(!empty($recListResponse['ProfessionalDtls'][$k]['first_name'])) { echo $recListResponse['ProfessionalDtls'][$k]['first_name']." "; } if(!empty($recListResponse['ProfessionalDtls'][$k]['middle_name'])) { echo $recListResponse['ProfessionalDtls'][$k]['middle_name']; } else { echo ""; } ?></td>
                                             <td><?php  if(!empty($recListResponse['ProfessionalDtls'][$k]['ProfOtherDtls']['skill_set'])) { echo $recListResponse['ProfessionalDtls'][$k]['ProfOtherDtls']['skill_set']; } else { echo "-"; } ?></td>
                                             <td><?php  if(!empty($recListResponse['ProfessionalDtls'][$k]['reference_type'])) { if($recListResponse['ProfessionalDtls'][$k]['reference_type']=='1') { echo "Professional"; } else if($recListResponse['ProfessionalDtls'][$k]['reference_type']=='2') { echo "Vendor"; } } else { echo "-"; } ?></td>
                                             <td><?php  if(!empty($recListResponse['ProfessionalDtls'][$k]['locationNm'])) { echo $recListResponse['ProfessionalDtls'][$k]['locationNm']; } else { echo "-"; } ?></td>
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
             </div>
            <div class="line-seprator"></div>
        <?php } ?>
        <?php if(!empty($recListResponse['JobSummary'])) { ?>
            <div class="row" id="Block7">
           <h4 class="section-head"><span><img height="29" width="29" src="images/feedback.png" class="mCS_img_loaded"></span> JOB SUMMARY </h4>
           <table  class="table table-bordered-hca" cellspacing="0" width="100%">
                <thead style="color:#fff !important">
                    <tr class="color-row">
                    <th>PROF.CODE</th>
                    <th>NAME</th>
                    <th>SERVICE</th>
                    <th>DATE</th>
                    <th>FORM/TO</th>
                    <th>INSTRUCTION</th>
                    <th>Media</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if(!empty($recListResponse['JobSummary']))
                        {
                            for($l=0;$l<count($recListResponse['JobSummary']);$l++)
                            {
                                ?>
                                     <tr>
                                         <td><?php if(!empty($recListResponse['JobSummary'][$l]['ProfessionalId'])) { echo $recListResponse['JobSummary'][$l]['ProfessionalId']; } else { echo "-"; } ?></td>
                                         <td><?php if(!empty($recListResponse['JobSummary'][$l]['ProfessionalNm'])) { echo $recListResponse['JobSummary'][$l]['ProfessionalNm']; } else { echo "-"; } ?></td>
                                         <td><?php if(!empty($recListResponse['JobSummary'][$l]['ServiceNm'])) { echo $recListResponse['JobSummary'][$l]['ServiceNm']; } else { echo "-"; } ?></td>
                                         <td><?php if(!empty($recListResponse['JobSummary'][$l]['ServiceDate']) && $recListResponse['JobSummary'][$l]['ServiceDate'] !='0000-00-00 00:00:00') { echo date('d-m-Y', strtotime($recListResponse['JobSummary'][$l]['ServiceDate'])); } else { echo "-"; } ?></td>
                                         <td><?php if(!empty($recListResponse['JobSummary'][$l]['StartTime']) && !empty($recListResponse['JobSummary'][$l]['EndTime'])) { echo $recListResponse['JobSummary'][$l]['StartTime']." to ".$recListResponse['JobSummary'][$l]['EndTime']; } else { echo "-"; } ?></td>
                                         <td><?php if(!empty($recListResponse['JobSummary'][$l]['Report_Inst'])) { echo $recListResponse['JobSummary'][$l]['Report_Inst']; } else { echo "-"; } ?></td>
                                         <td><?php if(!empty($recListResponse['JobSummary'][$l]['MediaType'])) { echo $recListResponse['JobSummary'][$l]['MediaType']; } else { echo "-"; } ?></td>
                                     </tr>
                                <?php 
                            }
                        } 
                        else 
                        {
                            echo '<tr><td colspan="7" style="text-align:center;color:#FF0000;">No Record Found</td></tr>';
                        }
                   ?>    
                </tbody>
            </table>
        </div>
            <div class="line-seprator"></div>
        <?php } ?>
        <?php if(!empty($recListResponse['JobClosure'])) { ?>
        	<div id="Block8">
                <h4 class="section-head"><span><img height="29" width="29" src="images/feedback.png" class="mCS_img_loaded"></span> JOB CLOSURE </h4>
                <?php
                    $AllJobClosureRecords=$recListResponse['JobClosure'];
                    for($i=0;$i<count($AllJobClosureRecords);$i++)
                    {
                        ?>
                            <form class="form-horizontal" style="padding-left:50px;">
                                <div class="col-lg-12">
                                    <h4><?php if(!empty($AllJobClosureRecords[$i]['professionalNm'])) { echo $AllJobClosureRecords[$i]['professionalNm']; }  if(!empty($AllJobClosureRecords[$i]['service_date'])) { echo " (". date('d-m-Y',strtotime($AllJobClosureRecords[$i]['service_date'])).")"; } ?></h4>
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
                                <?php if(!empty($AllJobClosureRecords[$i]['consumptions'])) { ?>
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
                                <?php }
								 if(!empty($AllJobClosureRecords[$i]['job_closure_file']) && file_exists("../JobClosureDocuments/".$AllJobClosureRecords[$i]['job_closure_file'])) { ?>
                                	<div class="rounded-corner col-lg-12">
                                    	<img src="<?php echo $siteURL; ?>JobClosureDocuments/<?php echo $AllJobClosureRecords[$i]['job_closure_file']; ?>" width="" height="" alt="" />
                                    </div>
                                <?php }  else { ?>
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
                                                <div> Airway:&nbsp; 
                                                   <?php 
                                                    if(!empty($AllJobClosureRecords[$i]['airway'])) 
                                                    { 
                                                        if($AllJobClosureRecords[$i]['airway']=='1') { echo "Open"; }
                                                        else if($AllJobClosureRecords[$i]['airway']=='2') { echo "Close"; }
                                                        else { echo "-"; }
                                                    }
                                                   else 
                                                   { 
                                                       echo "-";   
                                                   } ?>  
                                               </div>
                                                <div> Breathing:&nbsp; 
                                                    <?php 
                                                    if(!empty($AllJobClosureRecords[$i]['breathing'])) 
                                                    { 
                                                        if($AllJobClosureRecords[$i]['breathing']=='1') { echo "Present"; }
                                                        else if($AllJobClosureRecords[$i]['breathing']=='2') { echo "Compromised"; }
                                                        else if($AllJobClosureRecords[$i]['breathing']=='3') { echo "Absent"; }
                                                        else { echo "-"; }
                                                    }
                                                    else { echo "-"; } ?>  
                                                </div>
                                                <div> Circulation:&nbsp; 
                                                    <?php 
                                                    if(!empty($AllJobClosureRecords[$i]['circulation'])) 
                                                    { 
                                                        if($AllJobClosureRecords[$i]['circulation']=='1') { echo "Redial"; }
                                                        else if($AllJobClosureRecords[$i]['circulation']=='2') { echo "Present"; }
                                                        else if($AllJobClosureRecords[$i]['circulation']=='3') { echo "Absent"; }
                                                        else { echo "-"; }
                                                    }
                                                    else { echo "-"; } ?> 
                                                </div>
                                                <div class="clearfix margintop20"></div>
                                                <table cellpadding="3" cellspacing="0" class="table-baseline">
                                                    <tr>
                                                        <td>Temp (Core)</td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['temprature'])) { echo $AllJobClosureRecords[$i]['temprature']; }  else { echo "-"; } ?></td>
                                                        <td>*F</td>
                                                        <td style="width:50px;"></td>
                                                        <td>TBSL:</td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['bsl'])) { echo $AllJobClosureRecords[$i]['bsl']; }  else { echo "-"; } ?></td>
                                                        <td> mg/dl</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pulse: </td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['pulse'])) { echo $AllJobClosureRecords[$i]['pulse']; }  else { echo "-"; } ?></td>
                                                        <td>/min</td>
                                                        <td style="width:50px;"></td>
                                                        <td>SpO2: </td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['spo2'])) { echo $AllJobClosureRecords[$i]['spo2']; }  else { echo "-"; } ?></td>
                                                        <td> % </td>
                                                    </tr>
                                                    <tr>
                                                        <td>RR:</td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['rr'])) { echo $AllJobClosureRecords[$i]['rr']; }  else { echo "-"; } ?></td>
                                                        <td>/min</td>
                                                        <td style="width:50px;"></td>
                                                        <td>GCS Total:</td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['gcs_total'])) { echo $AllJobClosureRecords[$i]['gcs_total']; }  else { echo "-"; } ?></td>
                                                        <td>/15</td>
                                                    </tr>
                                                    <tr>
                                                        <td>BP:</td>
                                                        <td><?php if(!empty($AllJobClosureRecords[$i]['high_bp']) && !empty($AllJobClosureRecords[$i]['low_bp'])) { echo $AllJobClosureRecords[$i]['high_bp']."/".$AllJobClosureRecords[$i]['low_bp']; }  else { echo "-"; } ?></td>
                                                        <td>/MmHg</td>
                                                        <td style="width:50px;"></td>
                                                        <td colspan="2">Skin Perfusion: 
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
                                                <label class="name">Summary Note:</label>
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
                <div class="line-seprator"></div>  
            </div>
        <?php } ?>
        <?php if(!empty($recListResponse['FeedbackDtls'])) { ?>
            <div class="row" id="Block9">
                <h4 class="section-head"><span><img height="29" width="29" src="images/feedback.png" class="mCS_img_loaded"></span> FEEDBACK </h4>
                <table  class="table table-bordered-hca" cellspacing="0" width="100%">
                    <tbody>
                       <?php
                                if(!empty($recListResponse['FeedbackDtls']))
                                {
                                    for($l=0;$l<count($recListResponse['FeedbackDtls']);$l++)
                                    {
                                        ?>
                                          <tr>
                                              <td colspan="4"> 
                                                  <?php if(!empty($recListResponse['FeedbackDtls'][$l][0]['service_date']) && $recListResponse['FeedbackDtls'][$l][0]['service_date'] !='0000-00-00') { echo "<b>".date('d-m-Y',  strtotime($recListResponse['FeedbackDtls'][$l][0]['service_date']))."</b>"; } else { echo ""; } ?>
                                              </td>
                                          </tr>
                                        <?php 
                                        if(!empty($recListResponse['FeedbackDtls'][$l])) 
                                        { 
                                              for($m=0;$m<count($recListResponse['FeedbackDtls'][$l]);$m++)
                                              { ?>  
                                                <tr>
                                                    <td><?php echo $m+1; ?></td>
                                                    <td><?php if(!empty($recListResponse['FeedbackDtls'][$l][$m]['question'])) { echo $recListResponse['FeedbackDtls'][$l][$m]['question']; } else { echo "-"; } ?></td>
                                                    <td><?php if(!empty($recListResponse['FeedbackDtls'][$l][$m]['option_value'])) { echo $recListResponse['FeedbackDtls'][$l][$m]['option_value']; } else { echo "-"; } ?></td>
                                                    <td><?php if(!empty($recListResponse['FeedbackDtls'][$l][$m]['answer'])) { echo $recListResponse['FeedbackDtls'][$l][$m]['answer']; } else { echo "-"; } ?></td>
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
            <div class="line-seprator"></div>
			 
         <?php } ?>    
     </div>
	 <a class="btn btn-download pull-right font18"  data-original-title="" onclick="change_status( '<?php echo $event_code?>');" title="">Added Into Tally</a>
       <?php 
    }
}?>
<script>
function change_status(abc)
{

var event_id=abc;
//alert(event_id);
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
				
				document.getElementById("gh").innerHTML=xmlhttp.responseText;
			}
		}
		
		xmlhttp.open("POST","update_tally_status.php?event_id="+event_id,true);
		
		//xmlhttp.open("POST","include_payments.php?formDate="+formDate+"&toDate="+toDate+"&type_of_payment="+type_of_payment+"&event_id="+event_id+"&HHC_NO="+HHC_NO,true);
		xmlhttp.send();

}
</script>