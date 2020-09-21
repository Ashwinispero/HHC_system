<?php
    require_once 'inc_classes.php';            
    include "../classes/eventClass.php";
    $eventClass = new eventClass();
    include "../classes/employeesClass.php";
    $employeesClass = new employeesClass();
    include "../classes/professionalsClass.php";
    $professionalsClass = new professionalsClass();
    include "../classes/commonClass.php";
    $commonClass= new commonClass();
    require_once '../classes/functions.php'; 
    require_once '../classes/config.php'; 
    
    $event_id=$db->escape($_REQUEST['event_id']);
    $event_share_id=$db->escape($_REQUEST['event_share_id']);
    if(!empty($event_share_id))
    {
        // Get Event Summary 
         $ShareEventDtls=$eventClass->GetShareEventById($event_share_id);
         if(!empty($ShareEventDtls))
         {
            $arr['event_id']=$ShareEventDtls['event_id'];
            $EventSummaryDtls=$eventClass->GetEventSummary($arr);
         }
    }
    else 
    {
        $arr['event_id']=$event_id;
        $EventSummaryDtls=$eventClass->GetEventSummary($arr);
    }
    
    $getEventDetail = $eventClass->GetEvent($arr);
    if(!empty($getEventDetail))
    {
        $event_code = $getEventDetail['event_code'];
        $event_date = date('d M Y H:i A',strtotime($getEventDetail['event_date']));
    }
    else 
    {
        $event_code ="";
        $event_date ="";
    }
    $sql_call_purpose="SELECT name FROM sp_purpose_call WHERE purpose_id='".$EventSummaryDtls['CallerDtls']['purpose_id']."'";
    $row_call_purpose=$db->fetch_array($db->query($sql_call_purpose));
    $call_purposeName =$row_call_purpose['name']; 
?>
<html>
    <head>
        <title>Welcome to SPERO</title> 
    </head>
    <body style="font-family:arial; font-size:14px; color:#999; background:no-repeat url(images/pdf-bg.png) center;">
        <div style="width:795px; margin:0 auto; padding:20px 35px 0;">
            <!--Header Start-->
            <div style="border-bottom:1px solid #fbb336;">
             <table cellpadding="0" cellspacing="0" width="100%">
             	<tr>
                	<td align="left"> 
                            <div style="float:left;">
                              <h3 style="color:#00cfcb; font-size:20px; vertical-align:middle; font-weight:normal; color:#000;">Event  <?php echo $event_code;?> Summary Details (<?php echo $call_purposeName;?>) <span style="float:right;padding-right: 25px;"><br/><?php if(!empty($EventSummaryDtls['PatientDtls']['hhc_code'])) { echo "HHC No. :".$EventSummaryDtls['PatientDtls']['hhc_code']; } ?></span></h3>
                            </div>
                            <div style="float:left;">
                                <h3 style="color:#00cfcb; font-size:20px; vertical-align:middle; font-weight:normal; color:#000;">Event Date :<?php echo $event_date;?></h3>
                            </div>
                        </td>
                    <td align="right"><div style="float:right;"><img src="images/logo.png"></div></td>
                </tr>
             </table>
              <div style="clear:both;"></div>
            </div>
          <!--Header End-->
          <!--Body Start-->
          <div style="padding:15px 30px;">
              <?php 
                if(!empty($EventSummaryDtls['CallerDtls']))
                { ?>
                    <div style="margin-bottom:10px;">
                        <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/coller-icon.png"></span>CALLER DETAILS</h4>
                        <div style="padding-left:45px;">
                            <?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { ?>
                                <div style="margin-bottom:10px;">
                                <table cellpadding="0" cellspacing="0">
                                	<tr>
                                    	<td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Contact:</div> </td>
                                        <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo $EventSummaryDtls['CallerDtls']['phone_no']; } else {  echo "-"; } ?></div><div style="clear:both;"></div></td>
                                    </tr>
                                </table>
                                    
                                    
                                </div>
                            <?php } ?>
                            <?php if(!empty($EventSummaryDtls['CallerDtls']['mobile_no'])) { ?>
                                <div style="margin-bottom:10px;">
                                <table cellpadding="0" cellspacing="0">
                                	<tr>
                                    <td style="width:175px; color:#999;">
                                   		 <div style="width:175px; margin-right:10px;  float:left;">Mobile Number:</div> 
                                    </td>
                                    <td>
                                    	<div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['CallerDtls']['mobile_no'])) { echo $EventSummaryDtls['CallerDtls']['mobile_no']; } else {  echo "-"; } ?></div><div style="clear:both;"></div>
                                    </td>
                                    </tr>
                                    </table>
                                </div>
                            <?php } ?>
                                <div style="margin-bottom:10px;">
                                 <table cellpadding="0" cellspacing="0">
                                	<tr>
                                    <td style="width:175px; color:#999;">
                                    <div style="width:175px; margin-right:10px;  float:left;">Name:</div> 
                                    </td>
                                    <td>
                                    <div style="width:500px; color:#000;"> <?php if(!empty($EventSummaryDtls['CallerDtls']['caller_last_name'])) { echo $EventSummaryDtls['CallerDtls']['caller_last_name']." "; } if(!empty($EventSummaryDtls['CallerDtls']['caller_first_name'])) { echo $EventSummaryDtls['CallerDtls']['caller_first_name']." "; } if(!empty($EventSummaryDtls['CallerDtls']['caller_middle_name'])) { echo $EventSummaryDtls['CallerDtls']['caller_middle_name']." "; } else {  echo ""; } ?></div>
                                    </td>
                                    </tr>
                                    </table>
                                    <div style="clear:both;"></div>
                                </div>
                            <?php if(!empty($EventSummaryDtls['CallerDtls']['relation'])) { ?>
                                <div style="margin-bottom:10px;">
                                <table cellpadding="0" cellspacing="0">
                                	<tr>
                                    <td style="width:175px; color:#999;">
                                    <div style="width:175px; margin-right:10px;  float:left;">Relation:</div> 
                                    </td>
                                    <td>
                                    <div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['CallerDtls']['relation'])) { echo $EventSummaryDtls['CallerDtls']['relation']; } else {  echo "-"; } ?></div>
                                    </td>
                                    </tr>
                                    </table>
                                    <div style="clear:both;"></div>
                                </div> 
                            <?php } ?>
                            <?php if(!empty($EventSummaryDtls['CallerDtls']['email_id'])) { ?>
                               <div style="margin-bottom:10px;">
                              <table cellpadding="0" cellspacing="0">
                                	<tr>
                                    <td style="width:175px; color:#999;">
                                    <div style="width:175px; margin-right:10px;  float:left;">Relation:</div> 
                                    </td>
                                    <td>
                                    <div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['CallerDtls']['email_id'])) { echo $EventSummaryDtls['CallerDtls']['email_id']; } else {  echo "-"; } ?></div>
                                    </td>
                                    </tr>
                                    </table>
                                    <div style="clear:both;"></div>
                                </div> 
                            <?php } ?>
                        </div> 
                    </div>
                    <div style="background:#e4e1e1; height:1px;"></div>
            <?php } ?>
            <?php 
                if(!empty($EventSummaryDtls['PatientDtls']))
                { ?>
                    <div style="margin-bottom:10px;">
                        <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/patient-icon.png"></span>PATIENT DETAILS</h4>
                        <div style="padding-left:45px;">
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Name:</div> </td>
                                 <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; } else {  echo ""; } ?></div></td>
                                </tr>
                            </table>
                                
                                
                                <div style="clear:both;"></div>
                            </div> 
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"> <div style="width:175px; margin-right:10px;  float:left;">Residential Address:</div> </td>
                                 <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['residential_address'])) { echo $EventSummaryDtls['PatientDtls']['residential_address']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                               
                                
                                <div style="clear:both;"></div>
                            </div>
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"> <div style="width:175px; margin-right:10px;  float:left;">Permanent Address:</div> </td>
                                 <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['permanant_address'])) { echo $EventSummaryDtls['PatientDtls']['permanant_address']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                               
                                
                                <div style="clear:both;"></div>
                            </div>
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Location:</div> </td>
                                 <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['locationNm'])) { echo $EventSummaryDtls['PatientDtls']['locationNm']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                
                                
                                <div style="clear:both;"></div>
                            </div> 
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Pin Code:</div> </td>
                                 <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['LocationPinCode'])) { echo $EventSummaryDtls['PatientDtls']['LocationPinCode']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                
                                
                                <div style="clear:both;"></div>
                            </div>
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Mobile:</div> </td>
                                 <td> <div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['mobile_no'])) { echo $EventSummaryDtls['PatientDtls']['mobile_no']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                
                               
                                <div style="clear:both;"></div>
                            </div> 
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Email Id:</div> </td>
                                 <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['email_id'])) { echo $EventSummaryDtls['PatientDtls']['email_id']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                
                                
                                <div style="clear:both;"></div>
                            </div>
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Landline:</div> </td>
                                 <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['phone_no'])) { echo $EventSummaryDtls['PatientDtls']['phone_no']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                
                                
                                <div style="clear:both;"></div>
                            </div> 
                            <div style="margin-bottom:10px;">
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">DOB:</div> </td>
                                 <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['dob']) && $EventSummaryDtls['PatientDtls']['dob'] !='0000-00-00' ) { echo date('d-m-Y',strtotime($EventSummaryDtls['PatientDtls']['dob'])) ; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                
                                
                                <div style="clear:both;"></div>
                            </div> 
                            <?php } ?>
                            
                            <?php if(!empty($EventSummaryDtls['FamilyDoctorDtls'])) { ?>
                                <div style="border-bottom:1px dashed #e4e1e1; margin-bottom:10px; padding-top:10px;"></div>
                                <div>
                                    <div style="margin-bottom:10px;">
                                    <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Family Doctor :</div> </td>
                                 <td><div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['FamilyDoctorDtls']['name'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['name']." "; } if(!empty($EventSummaryDtls['FamilyDoctorDtls']['first_name'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['first_name']." "; } if(!empty($EventSummaryDtls['FamilyDoctorDtls']['middle_name'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['middle_name']; } else {  echo ""; } ?></div></td>
                                </tr>
                            </table>
                                        <div style="clear:both;"></div>
                                    </div>
                                    <div style="margin-bottom:10px;">
                                    <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Contact No:</div> </td>
                                 <td><div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['FamilyDoctorDtls']['mobile_no'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['mobile_no']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                        
                                        
                                        <div style="clear:both;"></div>
                                    </div>
                                    <div style="margin-bottom:10px;">
                                    <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Email Id:</div> </td>
                                 <td><div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['FamilyDoctorDtls']['email_id'])) { echo $EventSummaryDtls['FamilyDoctorDtls']['email_id']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                        
                                        
                                        <div style="clear:both;"></div>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <?php if(!empty($EventSummaryDtls['ConsultantDtls'])) { ?>
                                <div style="border-bottom:1px dashed #e4e1e1; margin-bottom:10px; padding-top:10px;"></div>
                            <div>
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"> <div style="width:175px; margin-right:10px;  float:left;">Consultant:</div></td>
                                 <td><div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['ConsultantDtls']['name'])) { echo $EventSummaryDtls['ConsultantDtls']['name']." "; } if(!empty($EventSummaryDtls['ConsultantDtls']['first_name'])) { echo $EventSummaryDtls['ConsultantDtls']['first_name']." "; } if(!empty($EventSummaryDtls['ConsultantDtls']['middle_name'])) { echo $EventSummaryDtls['ConsultantDtls']['middle_name']; } else {  echo ""; } ?></div></td>
                                </tr>
                            </table>
                                <div style="margin-bottom:10px;">
                                    
                                    
                                    <div style="clear:both;"></div>
                                </div>
                                <div style="margin-bottom:10px;">
                                <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Contact No:</div></td>
                                 <td><div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['ConsultantDtls']['mobile_no'])) { echo $EventSummaryDtls['ConsultantDtls']['mobile_no']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                     
                                    
                                    <div style="clear:both;"></div>
                                </div> 
                                <div style="margin-bottom:10px;">
                                <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px; margin-right:10px;  float:left;">Email Id:</div> </td>
                                 <td><div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['ConsultantDtls']['email_id'])) { echo $EventSummaryDtls['ConsultantDtls']['email_id']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                                    
                                    
                                    <div style="clear:both;"></div>
                                </div> 
                            </div>
                            <?php } ?>
                        </div>
                    </div>
            <div style="clear:both;"></div>
            <?php 
                if(!empty($EventSummaryDtls['note']))
                { ?>
                    <div>
                    <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;"  src="images/notes.png"></span>Note</h4>
                        <div style="margin-bottom:10px;">
                            
                            <table cellpadding="0" cellspacing="0">
                            	<tr>
                                 <td style="width:175px; color:#999;"><div style="width:175px;margin-right:10px;float:left;">Note:</div></td>
                                 <td><div style="width:500px; color:#000;"><?php if(!empty($EventSummaryDtls['note'])) { echo $EventSummaryDtls['note']; } else {  echo "-"; } ?></div></td>
                                </tr>
                            </table>
                             
                            
                            <div style="clear:both;"></div>
                        </div>
                    </div>
            <?php } ?> 
            <div style="clear:both;"></div>
            <?php 
                if(!empty($EventSummaryDtls['ReqDtls']))
                { ?>
                    <div style="background:#e4e1e1; height:1px;"></div>
                    <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/requirnment-icon.png"></span>REQUIREMENTS</h4>
                    <table cellspacing="0" cellpadding="5" width="100%" style="font-size:13px;">
                        <thead>
                          <tr style="background:#00cfcb; color:#fff; font-size:14px; padding:3px;">
                            <th width="30%">Service</th>
                            <th width="22%">Recommended Service</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($EventSummaryDtls['ReqDtls']))
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
                                  } else {  ?>
                                    <tr>
                                        <td colspan="3" style="text-align:center;color:#FF0000;">No Record Found</td>
                                    </tr>
                                  <?php } ?>
                        </tbody>
                      </table>
                    <div style="background:#e4e1e1; height:1px; width:100%; margin-top:20px; margin-bottom:20px;"></div>
                <?php } ?>
            <?php 
                $recListResponse=$EventSummaryDtls['plan_of_care'];
                $recList=$recListResponse['data'];
                $recListCount=$recListResponse['count'];
                if(!empty($recList))
                {	
				?>
                    <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/plan-of-care.png"></span>PLAN OF CARE</h4>
                    <table cellspacing="0" cellpadding="5" width="100%" style="border:0px solid #CCC; font-size:13px">
                        <thead>
                          <tr style="background:#00cfcb; color:#fff; font-size:14px; padding:3px;">
                            <th>Service</th>
                            <th>Recommended Service</th>
                            <th>Date (Form/To)</th>
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
                                                                                        echo '<td>'.$fromDate.' to '.$toDate.'</td>';
                                                                                    else 
                                                                                       echo '<td>NA</td>';
                                                                                    
                                                                                    if(!empty($start_time) && !empty($endtime))
                                                                                        echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                                                    else 
                                                                                       echo '<td>NA</td>';
                                                                                    
                                                                                    if(!empty($fromDate) && !empty($toDate))
                                                                                        echo '<td>'.$cost.'/-</td>';
                                                                                    else 
                                                                                        echo '<td>NA</td>';
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
                                                                                            echo '<td>'.$fromDate.' to '.$toDate.'</td>';
                                                                                        else 
                                                                                           echo '<td>NA</td>';
                                                                                        
                                                                                        if(!empty($start_time) && !empty($endtime))
                                                                                            echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                                                        else 
                                                                                           echo '<td>NA</td>';
                                                                                        if(!empty($fromDate) && !empty($toDate))
                                                                                            echo '<td>'.$cost.'/-</td>';
                                                                                        else 
                                                                                            echo '<td>NA</td>';
                                                                                        
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
                                                                            echo '<td>'.$fromDate.' to '.$toDate.'</td>';
                                                                        else 
                                                                            echo '<td>NA</td>';
                                                                        if(!empty($start_time) && !empty($endtime))
                                                                            echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                                        else 
                                                                           echo '<td>NA</td>'; 
                                                                        
                                                                        echo '<td>'.$recListValue['cost'].'/-</td>
                                                                  </tr>';
                                                                        
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
                                        $finalcost = $total_cost+$totalTax;
                                        echo '<tr class="total-row"><tr class="total-row"><td colspan="4" style="text-align:left;">TOTAL ESTIMATED COST: ';
                                        if(!empty($fromDate) && !empty($toDate))
                                            echo $finalcost.'/-';
                                        else 
                                           echo 'NA'; 
                                        
                                        echo '</td></tr>';
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
                    <div style="background:#e4e1e1; height:1px; width:100%; margin-top:20px; margin-bottom:20px;"></div>
                <?php } ?>
           <?php 
                if(!empty($EventSummaryDtls['ProfessionalDtls']))
                { ?>
                    <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/profesnals.png"></span>PROFESSIONAL</h4>
                     <table cellspacing="0" cellpadding="5" width="100%;" style="font-size:13px;">
                        <thead style="color:#fff !important">
                            <tr style="background:#00cfcb; color:#fff; font-size:14px; padding:3px;">
                              <th>PROF.CODE</th>
                              <th>NAME</th>
                              <th>SKILL-SET</th>
                              <th>TYPE</th>
                              <th>LOCATION</th>
                              <th>SERVICE</th>
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
                                                <td style="border-bottom:1px solid #ddd"><?php if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['professional_code'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['professional_code']; } else { echo "-"; } ?></td>
                                                <td style="border-bottom:1px solid #ddd"><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['name'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['name']." "; } if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['first_name'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['first_name']." "; } if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['middle_name'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['middle_name']; }  else { echo ""; } ?></td>
                                                <td style="border-bottom:1px solid #ddd"><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['ProfOtherDtls']['skill_set'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['ProfOtherDtls']['skill_set']; } else { echo "-"; } ?></td>
                                                <td style="border-bottom:1px solid #ddd"><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['reference_type'])) { if($EventSummaryDtls['ProfessionalDtls'][$k]['reference_type']=='1') { echo "Professional"; } else if($EventSummaryDtls['ProfessionalDtls'][$k]['reference_type']=='2') { echo "Vendor"; } } else { echo "-"; } ?></td>
                                                <td style="border-bottom:1px solid #ddd"><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['locationNm'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['locationNm']; } else { echo "-"; } ?></td>
                                                <td style="border-bottom:1px solid #ddd"><?php  if(!empty($EventSummaryDtls['ProfessionalDtls'][$k]['serviceNm'])) { echo $EventSummaryDtls['ProfessionalDtls'][$k]['serviceNm']; } else { echo "-"; } ?></td>
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
                    <div style="background:#e4e1e1; height:1px; width:100%; margin-top:20px; margin-bottom:20px;"></div>
                <?php } ?>
                
            <?php 
                if(!empty($EventSummaryDtls['JobSummary']))
                { ?>
                  <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/profesnals.png"></span>JOB SUMMARY</h4>
                  <table cellspacing="0" cellpadding="5" width="100%;" style="font-size:13px;">
                        <thead style="color:#fff !important">
                            <tr style="background:#00cfcb; color:#fff; font-size:14px; padding:3px;">
                                <th>PROF.CODE</th>
                                <th>NAME</th>
                                <th>SERVICE </th>
                                <th>INSTRUCTION</th>
                                <th>TYPE</th>
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
                                        <td style="border-bottom:1px solid #ddd"><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['ProfessionalId'])) { echo $EventSummaryDtls['JobSummary'][$l]['ProfessionalId']; } else { echo "-"; } ?></td>
                                        <td style="border-bottom:1px solid #ddd"><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['ProfessionalNm'])) { echo $EventSummaryDtls['JobSummary'][$l]['ProfessionalNm']; } else { echo "-"; } ?></td>
                                        <td style="border-bottom:1px solid #ddd"><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['ServiceNm'])) { echo $EventSummaryDtls['JobSummary'][$l]['ServiceNm']; } else { echo "-"; } ?></td>
                                        <td style="border-bottom:1px solid #ddd"><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['Report_Inst'])) { echo $EventSummaryDtls['JobSummary'][$l]['Report_Inst']; } else { echo "-"; } ?></td>
                                        <td style="border-bottom:1px solid #ddd"><?php if(!empty($EventSummaryDtls['JobSummary'][$l]['MediaType'])) { echo $EventSummaryDtls['JobSummary'][$l]['MediaType']; } else { echo "-"; } ?></td>
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
                  <div style="background:#e4e1e1; height:1px; width:100%; margin-top:20px; margin-bottom:20px;"></div>
                <?php } ?> 
               
            <?php 
                if(!empty($EventSummaryDtls['JobClosure']))
                { 
                    $AllJobClosureRecords=$EventSummaryDtls['JobClosure'];
                    for($i=0;$i<count($AllJobClosureRecords);$i++)
                    {
                    ?>
                    <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/feedback.png"></span>JOB CLOSURE</h4>
                    <form style="padding-left:50px;" class="form-horizontal">
                        <div class="col-lg-12"> 
                            <h4><?php if(!empty($AllJobClosureRecords[$i]['professionalNm'])) { echo $AllJobClosureRecords[$i]['professionalNm']; } if(!empty($AllJobClosureRecords[$i]['service_date'])) { echo " (". date('d-m-Y',strtotime($AllJobClosureRecords[$i]['service_date'])).")"; }?></h4>
                            <span class="color-text">Service Rendered : </span> 
                            <?php if(!empty($AllJobClosureRecords[$i]['service_render'])) { 
                                            if($AllJobClosureRecords[$i]['service_render']=='1') { echo "Yes"; }
                                            else if($AllJobClosureRecords[$i]['service_render']=='2') { echo "No"; }
                                            else { echo "-"; }
                                        }
                                        else { echo "-"; } ?>  
                        </div>
                        <?php if(!empty($AllJobClosureRecords[$i]['consumptions'])) { ?>
                            <div style="border: 1px solid #e3e3e3;border-radius: 8px;font-size: 12px;margin-bottom: 10px;margin-top: 10px;padding: 10px;">
                            <div style="color:#00cfcb;">Consumption Details:</div>
                            <?php
                                if(!empty($AllJobClosureRecords[$i]['consumptions']))
                                {
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

                                   $MedicineContent=array_merge($UnitMedicineVal,$NonUnitMedicineVal);
                                   $MedicineQuantity=array_merge($UnitMedicineQty,$NonUnitMedicineQty);
                                   $ConsumbaleContent=array_merge($UnitConsumbaleVal,$NonUnitConsumbaleVal);
                                   $ConsumbaleQuantity=array_merge($UnitConsumbaleQty,$NonUnitConsumbaleQty); 
                                }       
                            ?>
                            <table cellspacing="0" cellpadding="5" width="100%;" style="font-size:13px;">
                                <thead style="color:#fff !important">
                                    <tr style="background:#00cfcb; color:#fff; font-size:14px; padding:3px;">
                                         <th>Medicine</th>
                                         <th>Unit/Non Unit</th>
                                         <th>Consumbale</th>
                                         <th>Unit/Non Unit</th>
                                    </tr>
                                </thead>    
                                <tbody>
                                    <?php 
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
                                        <?php }
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
                        <?php } if(!empty($AllJobClosureRecords[$i]['job_closure_file']) && file_exists("../JobClosureDocuments/".$AllJobClosureRecords[$i]['job_closure_file'])) { ?>
                        	<div class="rounded-corner col-lg-12">
                                    	<img src="<?php echo $siteURL; ?>JobClosureDocuments/<?php echo $AllJobClosureRecords[$i]['job_closure_file']; ?>" width="" height="" alt="" />
                                    </div>
                        <?php } else { ?>
                         <div class="rounded-corner col-lg-12">
                            <div style="color:#00cfcb;">Baseline:</div>
                            <div class="row">
                              <div>
                                <div style="font-size:12px"> 
                                    <?php if(!empty($AllJobClosureRecords[$i]['baseline'])) { 
                                            if($AllJobClosureRecords[$i]['baseline']=='1') { echo "A"; }
                                            else if($AllJobClosureRecords[$i]['baseline']=='2') { echo "V"; }
                                            else if($AllJobClosureRecords[$i]['baseline']=='3') { echo "P"; }
                                            else if($AllJobClosureRecords[$i]['baseline']=='4') { echo "U"; }
                                            else { echo "-"; }
                                        }
                                        else { echo "-"; } ?>
                                </div>
                                <div style="font-size:12px">  
                                    <?php if(!empty($AllJobClosureRecords[$i]['airway'])) { 
                                            if($AllJobClosureRecords[$i]['airway']=='1') { echo "Open"; }
                                            else if($AllJobClosureRecords[$i]['airway']=='2') { echo "Close"; }
                                            else { echo "-"; }
                                        }
                                        else { echo "-"; } ?> 
                                </div>
                                <div style="font-size:12px">
                                    <?php if(!empty($AllJobClosureRecords[$i]['breathing'])) { 
                                            if($AllJobClosureRecords[$i]['breathing']=='1') { echo "Present"; }
                                            else if($AllJobClosureRecords[$i]['breathing']=='2') { echo "Compromised"; }
                                            else if($AllJobClosureRecords[$i]['breathing']=='3') { echo "Absent"; }
                                            else { echo "-"; }
                                        }
                                        else { echo "-"; } ?>  
                                </div>
                                <div style="font-size:12px">
                                    <?php if(!empty($AllJobClosureRecords[$i]['circulation'])) { 
                                            if($AllJobClosureRecords[$i]['circulation']=='1') { echo "Redial"; }
                                            else if($AllJobClosureRecords[$i]['circulation']=='2') { echo "Present"; }
                                            else if($AllJobClosureRecords[$i]['circulation']=='3') { echo "Absent"; }
                                            else { echo "-"; }
                                        }
                                        else { echo "-"; } ?> 
                                </div>
                                <div class="clearfix"  style="margin-top:20px;"></div>
                                <table cellspacing="0" cellpadding="3" style="font-size:12px;">
                                  <tbody>
                                    <tr>
                                      <td>Temp (Core)</td>
                                      <td><?php if(!empty($AllJobClosureRecords[$i]['temprature'])) { echo $AllJobClosureRecords[$i]['temprature']; }  else { echo ""; } ?></td>
                                      <td>*F</td>
                                      <td style="width:50px;"></td>
                                      <td>TBSL:</td>
                                      <td><?php if(!empty($AllJobClosureRecords[$i]['bsl'])) { echo $AllJobClosureRecords[$i]['bsl']; }  else { echo ""; } ?></td>
                                      <td> mg/dl</td>
                                    </tr>
                                    <tr>
                                      <td>Pulse: </td>
                                      <td><?php if(!empty($AllJobClosureRecords[$i]['pulse'])) { echo $AllJobClosureRecords[$i]['pulse']; }  else { echo ""; } ?></td>
                                      <td>/min</td>
                                      <td style="width:50px;"></td>
                                      <td>SpO2: </td>
                                      <td><?php if(!empty($AllJobClosureRecords[$i]['spo2'])) { echo $AllJobClosureRecords[$i]['spo2']; }  else { echo ""; } ?></td>
                                      <td> % </td>
                                    </tr>
                                    <tr>
                                      <td>RR:</td>
                                      <td><?php if(!empty($AllJobClosureRecords[$i]['rr'])) { echo $AllJobClosureRecords[$i]['rr']; }  else { echo ""; } ?></td>
                                      <td>/min</td>
                                      <td style="width:50px;"></td>
                                      <td>GCS Total:</td>
                                      <td><?php if(!empty($AllJobClosureRecords[$i]['gcs_total'])) { echo $AllJobClosureRecords[$i]['gcs_total']; }  else { echo ""; } ?></td>
                                      <td>/15</td>
                                    </tr>
                                    <tr>
                                      <td>BP:</td>
                                      <td><?php if(!empty($AllJobClosureRecords[$i]['high_bp']) && !empty($AllJobClosureRecords[$i]['low_bp'])) { echo $AllJobClosureRecords[$i]['high_bp']."/".$AllJobClosureRecords[$i]['low_bp']; }  else { echo ""; } ?></td>
                                      <td>/MmHg</td>
                                      <td style="width:50px;"></td>
                                      <td colspan="2">Skin Perfusion:<?php if(!empty($AllJobClosureRecords[$i]['skin_perfusion'])) { 
                                            if($AllJobClosureRecords[$i]['skin_perfusion']=='1') { echo "Normal"; }
                                            else if($AllJobClosureRecords[$i]['skin_perfusion']=='2') { echo "Abnormal"; }
                                            else { echo "-"; }
                                        }
                                        else { echo "-"; } ?>   
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-lg-12" style="margin-top:20px;">
                                <div style="font-size:12px;"> Notes: <br>
                                  <div >
                                    <?php if(!empty($AllJobClosureRecords[$i]['summary_note'])) { echo $AllJobClosureRecords[$i]['summary_note']; }  else { echo ""; } ?>
                                  </div>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                            </div>
                          </div>
                          <?php } ?>
                    </form>
                    <?php  } ?>    
                    <div style="background:#e4e1e1; height:1px; width:100%; margin-top:20px; margin-bottom:20px;"></div> 
          <?php } ?> 
             
             <?php 
                if(!empty($EventSummaryDtls['FeedbackDtls']))
                { ?>
                    <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/feedback.png"></span>FEEDBACK</h4>
                     <table cellspacing="0" cellpadding="5" width="100%;" style="font-size:13px;">
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
                                                        <td style="border-bottom:1px solid #ddd"><?php echo $m+1; ?></td>
                                                        <td style="border-bottom:1px solid #ddd; font-size:14px;"><?php if(!empty($EventSummaryDtls['FeedbackDtls'][$l][$m]['question'])) { echo $EventSummaryDtls['FeedbackDtls'][$l][$m]['question']; } else { echo "-"; } ?></td>
                                                        <td style="border-bottom:1px solid #ddd"><?php if(!empty($EventSummaryDtls['FeedbackDtls'][$l][$m]['option_value'])) { echo $EventSummaryDtls['FeedbackDtls'][$l][$m]['option_value']; } else { echo "-"; } ?></td>
                                                        <td style="border-bottom:1px solid #ddd; color:#333;"><?php if(!empty($EventSummaryDtls['FeedbackDtls'][$l][$m]['answer'])) { echo $EventSummaryDtls['FeedbackDtls'][$l][$m]['answer']; } else { echo "-"; } ?></td>
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
         <?php } ?>
          </div>
          
          <!--Body End-->
        <div style="clear:both;"></div>
        </div>
    </body>
</html>