<?php
    require_once 'inc_classes.php';            
    include "classes/eventClass.php";
    $eventClass = new eventClass();
    include "classes/employeesClass.php";
    $employeesClass = new employeesClass();
    include "classes/professionalsClass.php";
    $professionalsClass = new professionalsClass();
    include "classes/commonClass.php";
    $commonClass= new commonClass();
    include "classes/hospitalClass.php";
    $hospitalClass = new hospitalClass();
    require_once 'classes/functions.php'; 
    require_once 'classes/config.php'; 
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
    $evearrg['event_id'] = $event_id;
    $getEventDetail = $eventClass->GetEvent($evearrg);
    if(!empty($getEventDetail))
    {
        $event_code = $getEventDetail['event_code'];
        $event_date = date('d M Y h:i A',strtotime($getEventDetail['event_date']));
        //$last_modified_date = date('d M Y h:i A',strtotime($getEventDetail['last_modified_date']));
        
        // Get content details
        $arg['hospital_id'] = $getEventDetail['hospital_id'];
        $getContent = $hospitalClass->getContentById($arg);

        // echo '<pre> getContent <br>';
        // print_r($getContent);
        // echo '</pre>';
        // exit;

        if (!empty($getContent)) {
            $headerContentDetails = "";
            $footerContentDetails = "";
            foreach ($getContent AS $key => $valContent) {
                // Get header content details
                if ($valContent['content_type'] == '1') {
                    $headerContentDetails = $valContent['content_value'];
                }
                // Get footer content details
                if ($valContent['content_type'] == '2') {
                    $footerContentDetails = $valContent['content_value'];
                }
            }

            // echo '<pre> headerContentDetails <br>';
            // print_r($headerContentDetails);
            // echo '</pre>';

            // echo '<pre> footerContentDetails <br>';
            // print_r($footerContentDetails);
            // echo '</pre>';

            
        }
    }
    else 
    {
        $event_code ="";
        $event_date ="";
		$last_updated_date="";
    }

    $sql_call_purpose="SELECT name FROM sp_purpose_call WHERE purpose_id='".$EventSummaryDtls['CallerDtls']['purpose_id']."'";
    $row_call_purpose=$db->fetch_array($db->query($sql_call_purpose));
    $call_purposeName =$row_call_purpose['name'];

$fetch_event_id = mysql_query("SELECT event_id,patient_id,added_date,added_by,last_modified_date FROM sp_events WHERE event_code='$event_code'");
		$get_event_number = mysql_fetch_array($fetch_event_id);
		$last_modified_date = $get_event_number['last_modified_date'];	
		 $last_modified_date = date('d M Y h:i A',strtotime($last_modified_date));
?>
<html>
    <head>
        <title>Event Receipt</title> 
         <?php
                if(isset($_REQUEST['selected_block_value']))
                {
                    $myArray = explode(',', $_REQUEST['selected_block_value']);
                }
            ?>
    </head>
    <body style="font-family:arial; font-size:11px; color:#000; background:no-repeat url(images/pdf-bg.png) center;">
        <div style="width:795px; margin:0 auto; padding:20px 35px 0;">
            <!--Header Start-->
            <div style="border-bottom:1px solid #fbb336;">
                <?php if ($arg['hospital_id'] == '120') {
                    echo $headerContentDetails;
                    ?>
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td align="left">
                                    <div style="float:left;">
                                        <h3 style="color:#00cfcb; font-size:20px; vertical-align:middle; font-weight:normal; color:#000;"> Invoice  
                                            <span style="float:right;padding-right: 25px;"><br/> <?php echo "Event:".$event_code; ?>     |  <?php if(!empty($EventSummaryDtls['PatientDtls']['hhc_code'])) { echo  "HHC No. :".$EventSummaryDtls['PatientDtls']['hhc_code']; } ?></span>
                                        </h3>
                                    </div>
                            
                                    <div style="float:left;">
                                        <h5 style="color:#00cfcb; font-size:15px; vertical-align:middle; font-weight:normal; color:#000;">Event Date :<?php echo $event_date;?> | Last Updated:<?php echo $last_modified_date; ?></h5>
                                    </div>
                            
                                    <div style="float:left;">
                                        <h5 style="color:#00cfcb; font-size:15px; vertical-align:middle; font-weight:normal; color:#000;">Contact No:7620400100</h5>
                                    </div>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    <?php 
                } else { ?>
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="left"> 
                                <div style="float:left;">
                                    <h3 style="color:#00cfcb; font-size:15px; vertical-align:middle; font-weight:normal; color:#000;"> Invoice  <span style="float:right;padding-right: 25px;"><br/> <?php echo "Event:".$event_code; ?>     |  <?php if(!empty($EventSummaryDtls['PatientDtls']['hhc_code'])) { echo  "HHC No. :".$EventSummaryDtls['PatientDtls']['hhc_code']; } ?></span></h3>
                                </div>
                                <div style="float:left;">
                                    <h5 style="color:#00cfcb; font-size:15px; vertical-align:middle; font-weight:normal; color:#000;">Event Date :<?php echo $event_date;?> | Last Updated:<?php echo $last_modified_date; ?></h5>
                                </div>
                                <div style="float:left;">
                                    <h5 style="color:#00cfcb; font-size:15px; vertical-align:middle; font-weight:normal; color:#000;">Contact No:7620400100</h5>
                                </div>
                            </td>
                            <td align="right">
                                <div style="float:right;">
                                    <img src="images/logo.png">
                                </div>
                            </td>
                        </tr>
                    </table>
                <?php } ?>
              <div style="clear:both;"></div>
            </div>
          <!--Header End-->
          <!--Body Start-->
          <div style="padding:15px 30px;">

	<?php
	//PHP code for invoice fields - Ashwini
	
	$fetch_event_id = mysql_query("SELECT event_id,patient_id,added_date,added_by FROM sp_events WHERE event_code='$event_code'");
	$get_event_number = mysql_fetch_array($fetch_event_id);
	$event_number = $get_event_number['event_id'];
	$patient_id = $get_event_number['patient_id'];

	$patient_details = mysql_query("SELECT hhc_code,name,first_name,residential_address FROM sp_patients WHERE patient_id='$patient_id'");
	$name_address = mysql_fetch_array($patient_details);

	$event_details = mysql_query("SELECT * FROM sp_events WHERE event_id='$event_number'");
	$all_event_details = mysql_fetch_array($event_details);
	$bill_no_ref_no=$all_event_details['bill_no_ref_no'];
	

	$added_by = $get_event_number['added_by'];
	$added_by_details = mysql_query("SELECT hospital_id FROM sp_employees WHERE employee_id='$added_by'");
	$hospital_id = mysql_fetch_array($added_by_details);

	$hospital_id_ref = $hospital_id['hospital_id'];
	$hosp_code = mysql_query("SELECT hospital_short_code FROM sp_hospitals WHERE hospital_id='$hospital_id_ref'");
	$hospital_short_code = mysql_fetch_array($hosp_code);

	$full_code = $hospital_short_code['hospital_short_code'];
	list($code,$hc) = explode('HC',$full_code);
	$current_year = Date("d-m-Y");
	list($d,$m,$y) = explode('-',$current_year);
	$fin_year = $y+1;
	
	$sql1=mysql_query("SELECT * FROM sp_event_requirements  where event_id='$event_number'");
			$row_count = mysql_num_rows($sql1);
			if($row_count > 0)
			{
				$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
				$professional_vender_id=$sql11['professional_vender_id'];
				if($professional_vender_id !=0)
				{
				$patient_nm=mysql_query("SELECT * FROM sp_service_professionals where service_professional_id='$professional_vender_id'") or die(mysql_error());
				$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
				$title=$patient_nm['title'];
				$Pname=$patient_nm['name'];
				$Pfirst_name=$patient_nm['first_name'];
				$Pmiddle_name=$patient_nm['middle_name'];
				$mobile_no=$patient_nm['mobile_no'];
				$professional_name=$Pname.' '.$Pfirst_name;
				}
				else
				{
				$professional_name='';
				}
			}
			else
			{
				$professional_name='';
			}
			if (date('m') > 3) {
				$financial_year = date('Y')."-".(date('Y') +1);
			}
			else {
				$financial_year = (date('Y')-1)."-".date('Y');
			}
	?>
              
			  <?php
                if (in_array("1", $myArray)) 
                {
                    if(!empty($EventSummaryDtls['CallerDtls']))
                    { ?>
                        <div style="margin-bottom:5px;" >
                            <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/coller-icon.png"></span>BILL DETAILS</h4>
                            <div style="padding-left:45px;">
                                <?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { ?>
                                    <div style="margin-bottom:10px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
										<tr>
                                            <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Bill Number :</div> </td>
                                            <td><div style="width:260px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo "$code / $financial_year / $bill_no_ref_no"; } else {  echo "-"; } ?></div><div style="clear:both;"></div></td>
										
<?php 
//PHP code for invoice fields - Amod
$row_date = "$get_event_number[added_date]";
list($date,$time) = explode(' ',$row_date);
$invoice_date = Date("d-m-Y", strtotime($date));
?>
										
											<td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Date :</div> </td>
                                            <td><div style="width:250px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo "$invoice_date"; } else {  echo "-"; } ?></div><div style="clear:both;"></div></td>
										</tr>
										<tr>
											<td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Reference Number :</div> </td>
                                            <td><div style="width:250px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo "$name_address[hhc_code] / $event_code"; } else {  echo "-"; } ?></div><div style="clear:both;"></div></td>
											<td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Professional Name :</div> </td>
                                            <td><div style="width:250px; color:#000;  float:left;"><?php echo "$professional_name" ; ?></div><div style="clear:both;"></div></td>
										
										</tr>
                                    </table>
                                    </div>
                                <?php } ?>                                
                            </div> 
                        </div>
                        <div style="background:#e4e1e1; height:1px;"></div>
                <?php }
                }
            ?>
            <?php
                if (in_array("2", $myArray)) 
                {
                    if(!empty($EventSummaryDtls['PatientDtls']))
                    {
                        ?>
                            <div style="margin-bottom:5px;">
                            <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/patient-icon.png"></span>PATIENT DETAILS</h4>
                            <div style="padding-left:45px;">
                              <div style="margin-bottom:10px;">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                     <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;"><label class="col-sm-2 control-label" style="padding-top:0px;">Name :</label></div> </td>
                                     <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; } else {  echo ""; } ?></div></td>
                                    
                                     <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Mobile:</div> </td>
                                     <td> <div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['mobile_no'])) { echo $EventSummaryDtls['PatientDtls']['mobile_no']; } else {  echo "-"; } ?></div></td>
                                    </tr>
									
                                    <tr>
                                     <td style="width:175px; color:#000;"> <div style="width:175px; margin-right:10px;  float:left;">Residential Address:</div> </td>
                                     <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['residential_address'])) { echo $EventSummaryDtls['PatientDtls']['residential_address']; } else {  echo "-"; } ?></div></td>
                                    
                                     <td style="width:175px; color:#000;"> <div style="width:175px; margin-right:10px;  float:left;">Permanent Address:</div> </td>
                                     <td><div style="width:500px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['PatientDtls']['permanant_address'])) { echo $EventSummaryDtls['PatientDtls']['permanant_address']; } else {  echo "-"; } ?></div></td>
                                    </tr>
									                               
                                </table>
                                <div style="clear:both;"></div>
                              </div>
							</div>
							</div>
                        <?php 
                    }
                }
            ?>  
            
            <?php
                if (in_array("5", $myArray)) 
                {
                    $recListResponse=$EventSummaryDtls['plan_of_care'];
                    $recList=$recListResponse['data'];
                    $recListCount=$recListResponse['count'];
                    if(!empty($recList))
                    { 		
                                    ?>
                                    
                        <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/plan-of-care.png"></span>Service Details</h4>
                        <div style="margin-bottom:10px; padding-left:0px;">
                          <table cellspacing="0" cellpadding="5" width="100%" style="border:0px solid #CCC; font-size:11px">
                              <thead>
                                <tr style="background:#00cfcb; color:#fff; font-size:11px; padding:3px;">
                                  <th>Service</th>
                                  <th>Recommended Service</th>
                                  <th>Date (From/To)</th>
                                  <th>Time(From/To)</th>
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
														  $service_id = $recListValue['service_id'];
                                                          $event_requirement_id = $recListValue['event_requirement_id'];
                                                          $reqPlanArr['event_id'] = $recListValue['event_id'];
                                                          $reqPlanArr['event_requirement_id'] = $event_requirement_id;
                                                          $reqPlanArr['sub_service_id'] = $sub_service_id;
                                                          $requirementPlan = $eventClass->MultipleplanofcareRecords($reqPlanArr);
                                                          $data = $requirementPlan['data'];
                                                          $st = 1; 
                                                          $dateDiff = 1;
                                                          
                                                          
                                                          $query_package=mysql_query("SELECT * FROM sp_services where service_id='$service_id' ") or die(mysql_error());
			                                                $query_package_row = mysql_fetch_array($query_package) or die(mysql_error());
			                                                $Package_status=$query_package_row['Package_status']; 
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
																				else
																				{
																			   
																			   $query=mysql_query("SELECT * FROM sp_doctors_consultants where hospital_id='$hospital_id' and doctors_consultants_id='$Consultant' ") or die(mysql_error());
																			   $row = mysql_fetch_array($query) or die(mysql_error());
																			   $telephonic_consultation_fees=$row['telephonic_consultation_fees'];
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
                                                                                             // if((!empty($fromDate) && !empty($toDate))) 
                                                                                             // {
                                                                                                  echo '<td>'.$cost.'/-</td>';
                                                                                              /////}
                                                                                             // else 
                                                                                             // {
                                                                                               //   echo '<td>NA</td>';  
                                                                                             // }
                                                                                         echo '</tr>';
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
													else
													{
												   
												   $query=mysql_query("SELECT * FROM sp_doctors_consultants where hospital_id='$hospital_id' and doctors_consultants_id='$Consultant' ") or die(mysql_error());
												   $row = mysql_fetch_array($query) or die(mysql_error());
												   $telephonic_consultation_fees=$row['telephonic_consultation_fees'];
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
                                                                                                //  if(!empty($fromDate) && !empty($toDate)) 
                                                                                                //  {
                                                                                                      echo '<td>'.$cost.'/-</td>';                                                                                              
                                                                                                //  }
                                                                                                 // else 
                                                                                                 // {
                                                                                                  //    echo '<td>NA</td>';
                                                                                                  //}
                                                                                          echo '</tr>';	
																				}
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
													else
													{
												   
												   $query=mysql_query("SELECT * FROM sp_doctors_consultants where hospital_id='$hospital_id' and doctors_consultants_id='$Consultant' ") or die(mysql_error());
												   $row = mysql_fetch_array($query) or die(mysql_error());
												   $telephonic_consultation_fees=$row['telephonic_consultation_fees'];
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
                                                                             // if(!empty($fromDate) && !empty($toDate)) 
                                                                             // {
                                                                                  echo '<td>'.$recListValue['cost'].'/-</td>';
                                                                             // }
                                                                             // else 
                                                                             // {
                                                                             //     echo '<td>NA</td>';
                                                                             // }
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
                                                  $finalcost = ($totalEstimatedCost - $all_event_details['discount_amount']);
                                                  $finalcost=round($finalcost);

                                                  echo '<tr class="total-row">
                                                    <td colspan="4" style="background:#fdeed4; padding:5px; font-size:11px; color:#333;">
                                                        TOTAL ESTIMATED COST:
                                                    </td>';
                                                    if(!empty($fromDate) && !empty($toDate)) 
                                                    {
                                                        echo '<td style="background:#fdeed4;"> ' . $totalEstimatedCost . '/-</td>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<td style="background:#fdeed4;">NA</td>';
                                                    }
                                                   echo '</tr>';

                                                   if ($all_event_details['discount_amount'] != '0.00') {
                                                    echo '<tr class="total-row">
                                                            <td colspan="4" style="background:#fdeed4; padding:5px; font-size:11px; color:#333;">
                                                                TOTAL DISCOUNT COST:
                                                            </td>
                                                            <td style="background:#fdeed4;">
                                                                ' . ($all_event_details['discount_amount'] ? $all_event_details['discount_amount'] . '/-' : 'NA') . '
                                                            </td>
                                                        </tr>';

                                                    echo '<tr class="total-row">
                                                            <td colspan="4" style="background:#fdeed4; padding:5px; font-size:11px; color:#333;">
                                                                TOTAL ESTIMATED COST WITH DISCOUNT:
                                                            </td>
                                                            <td style="background:#fdeed4;">
                                                                ' . ($finalcost ? $finalcost . '/-' : 'NA') . '
                                                            </td>
                                                        </tr>';
                                                   }



//Function to convert number to words			

function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}					
					
					
					echo '<tr class="total-row"><td colspan="1" style="text-align:left; background:#fdeed4; padding:5px; font-size:11px; color:#333;">AMOUNT IN WORDS:</td>';
                                            if(!empty($fromDate) && !empty($toDate)) 
                                            {
												$in_words = convert_number_to_words($finalcost);
                                               echo '<td colspan="4" style="text-align:right;background:#fdeed4;">Rupees '.$in_words.' Only</td>';
                                            }
                                            else 
                                            {
                                               echo '<td>NA</td>'; 
                                            }
                    echo '</tr>';
                                          }
  
                            ?>
                              </tbody>
                            </table>
                          </div>
						   <div style="background:#e4e1e1; height:1px; width:100%; margin-top:20px; margin-bottom:20px;"></div>
						
                       
                    <table id="logTable" class="table table-striped" cellspacing="0" width="100%"  >
                              <thead>
                                <tr style="font-size:11px; padding:2px;font-weight:normal;">
                                  <th style="text-align:left">
								<?php
		if($row_count > 0)
		{
			if($service_id=='17')
			{
		?>
				<div class="form-group">
				 <div class="col-lg-12"><b>GSTIN   :  27AAVCS5809H1ZQ</b></div>
				<div class="col-lg-12"><b>PAN     :    AAVCS5809H</b></div><br>
				<div class="col-lg-5"><b>Company's Bank Detail:</b></div><br>
				<div class="col-lg-12">Spero Healthcare Innovations Pvt. Ltd.</div>
				<div class="col-lg-12">Bank Name:XXX BANK C.C A/C - XXX</div>
				<div class="col-lg-12">A/C No.  : 00000000000</div>
				<div class="col-lg-12">Branch & IFS Code: XXX</div><br>
				</div>
		<?php 
			}
			else
			{
		?>
				<div class="form-group">
				 <div class="col-lg-12"><b>GSTIN   :  27AAVCS5809H1ZQ</b></div>
				<div class="col-lg-12"><b>PAN     :    AAVCS5809H</b></div><br>
				<div class="col-lg-5"><b>Company's Bank Detail:</b></div><br>
				<div class="col-lg-12">Spero Healthcare Innovations Pvt. Ltd.</div>
				<div class="col-lg-12">Bank Name:HDFC BANK C.C A/C - 50200010027418</div>
				<div class="col-lg-12">A/C No.  : 50200010027418</div>
				<div class="col-lg-12">Branch & IFS Code: BHANDARKAR ROAD & HDFC0000007</div><br>
				</div>
		<?php
			}
		}
		?>
								  </th>
                                  <th style="font-weight:normal;text-align:left;padding-left:2%;">
								  <div class="form-group">
				
				<div class="col-lg-5"><b>Declaration:</b></div>
				<div class="col-lg-2" style="font-weight:normal;">We declare that this Bill shows the actual price of the <br> services described and that all particulars are true and correct. </div>
			</div>
								  </th>
                                 
                                  
                                </tr> </thead></table>
					
					<?php } 
                }
            ?>
			<div style="background:#e4e1e1; height:1px; width:100%; margin-top:10px; margin-bottom:20px;"></div>
			<?php
                if (in_array("6", $myArray))
                {
                   ?>   
				  
                                <h4 style="color:#000; font-size:16px; vertical-align:middle;">
                                    <span><img style="margin-right:10px; vertical-align: middle;" height="29" width="29" src="images/feedback.png"></span> PATIENT PAYMENT RECEIPT DETAILS 
                                </h4>
							        <?php
									
				$eventid = $event_number;
				$exist = mysql_query("SELECT * FROM sp_payments WHERE event_id='$eventid'");
				$row_count123 = mysql_num_rows($exist);
				if($row_count123 > 0)
				{
					$payments = mysql_query("SELECT MAX(payment_id) as payment_id FROM sp_payments WHERE event_id='$eventid'");
					$row_count = mysql_num_rows($payments);
					if($row_count > 0)
					{
						$row = mysql_fetch_array($payments) or die(mysql_error());
						$payment_id=$row['payment_id'];
						
						$Last_payments = mysql_query("SELECT * FROM sp_payments WHERE payment_id='$payment_id'");
						$row1 = mysql_fetch_array($Last_payments) or die(mysql_error());
						$type=$row1['type'];
						$Card_Number=$row1['Card_Number'];
						$Transaction_ID=$row1['Transaction_ID'];
						$payment_receipt_no_voucher_no=$row1['payment_receipt_no_voucher_no'];
					}
				}
					/*	$eventid = $event_number;
						
						$payments = mysql_query("SELECT MAX(payment_id) as payment_id FROM sp_payments WHERE event_id='$eventid'");
						$row = mysql_fetch_array($payments) or die(mysql_error());
						$payment_id=$row['payment_id'];
						
						
						$Last_payments = mysql_query("SELECT * FROM sp_payments WHERE payment_id='$payment_id'");
						$row1 = mysql_fetch_array($Last_payments) or die(mysql_error());
						$date_time=$row1['date_time'];
						$amount=$row1['amount'];
						$type=$row1['type'];
						$Comments=$row1['Comments'];
						$Transaction_Type=$row1['Transaction_Type'];
						
			if($Transaction_Type=='Refund')
			{
				$Amount=explode("-",$amount);
				$Amount1=$Amount[1];
			}
			if($Transaction_Type=='Payment')
			{
				$Amount1=$amount;
			}
						
						
						$total_paid_query = mysql_query("SELECT SUM(amount) FROM sp_payments WHERE event_id='$eventid'");
						$total_paid_array = mysql_fetch_array($total_paid_query);
						$due_balance = $finalcost - $total_paid_array[0];
						
						if(!empty($fromDate) && !empty($toDate)) 
                                            {
												$in_words = convert_number_to_words($due_balance);
                                               
                                            }
                                            else 
                                            {
                                               $in_words='NA';
                                            }
						
					$date_time = explode(" ",$date_time);
							$exploded_date = $date_time[0];
							//$time = $date_time[1];
							$date = date('d-m-Y',strtotime($exploded_date));
						
						if(!$Last_payments)
						{ */?>
						
							<!--<div class="col-lg-12">No payment Mode</div>-->
					<?php	//}
						//else { ?>
						<table id="logTable" class="table table-striped" cellspacing="0" width="100%" style="border:0px solid #CCC; font-size:11px">
                              <thead>
							  <tr style="font-size:11px; padding:1px;">
                                  <th style="text-align:left;font-weight:normal;">Receipt Number:<?php echo "$code / $financial_year / $event_number / $payment_receipt_no_voucher_no";?></th>
                                  
								</tr>
								</thead></table>
								
				<div class="col-lg-12">Received From:
				<?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }  else {  echo ""; } ?></div>			 
                                  <!--<div style="text-align:left;font-weight:normal;">Received From:</Div>-->
                                 
								
								<table id="logTable" class="table table-striped" cellspacing="0" width="100%" style="border:0px solid #CCC; font-size:11px;">
							  <thead>
								<tr style=" padding:3px;">
                                  <th style="text-align:left;font-weight:normal;width:50%;">Received Rs. :</th>
                                  <th style="text-align:left;font-weight:normal;width:50%;">Rupees:</th>
								  <th style="text-align:left;font-weight:normal;">Only</th>
                                </tr></thead></table>
								
                                  <div style="text-align:left;font-weight:normal;">Type Of Transaction:</div>
                                 <table id="logTable" class="table table-striped" cellspacing="0" width="100%" style="border:0px solid #CCC; font-size:11px;">
							  <thead>
								<tr style=" padding:2px;">
                                  <th style="text-align:left;font-weight:normal;width:50%;">Mode Of Transaction:</th>
                                  <th style="text-align:left;font-weight:normal;">Of Date :</th>
                                </tr></thead>
									<?php 
									if($type=='Card')
									{
										?>
										<thead>
								<tr style=" padding:2px;">
                                  <th style="text-align:left;font-weight:normal;width:50%;">Card Number:<?php echo $Card_Number; ?></th>
                                  <th style="text-align:left;font-weight:normal;">Transaction ID: <?php echo $Transaction_ID; ?></th>
                                </tr></thead>
								<?php 
									}
									?>
								</table>
                                  <div style="text-align:left;font-weight:normal;">Remark:</div>
                                  
								
                                  <div style="text-align:left;font-weight:normal;">towards settlement of the above bill.</div>
                                  
								
						
				
				<table id="logTable" class="table table-striped" cellspacing="0" width="100%" style="border:0px solid #CCC; font-size:11px;margin-top:5%">
                              <thead>
                                <tr style="font-size:11px; padding:2px;">
                                  <th >Signature with Name of Patient</th>
                                  <th >Signature with Name of Authority</th>
                                </tr> </thead></table>
						<?php // }?>
				
					
					<br>
					
			
                   <?php
                }   
            ?>
			
            <?php
                if (in_array("11", $myArray))
                {
                   ?>   
                                <h4 style="color:#000; font-size:16px; vertical-align:middle;">
                                    <span><img style="margin-right:10px; vertical-align: middle;" height="29" width="29" src="images/feedback.png"></span> PATIENT PAYMENT RECEIPT DETAILS 
                                </h4>
								<div style="padding-left:45px;">
                                    <div style="margin-bottom:10px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
										<tr>
                                            <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Received Rs :</div> </td>
                                            <td><div style="width:250px; color:#000;  float:left;"><?php if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo 'Rupees '.$in_words.' Only'; } else {  echo "-"; } ?></div><div style="clear:both;"></div></td>
										</tr>
										
										<tr>
                                            <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Cash/Cheque :<br>Bank Name :</div> </td>
                                            <td><div style="width:250px; color:#000;  float:left;">Of Date :<br>Branch Name :</div><div style="clear:both;"></div></td>
										</tr>
										
										<tr>
                                            <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">towards settlement of the above bill.</div> </td>
										</tr>
										
										<tr>
                                            <td style="width:250px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;"><br><br>Signature with Name of <br>Patient</div></td>
                                            <td style="width:250px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;"><br><br>Signature with Name of <br>Authority</div></td>
										</tr>
									</table>
									</div>
								</div>
                   <?php
                }   
            ?>
			<form class="form-horizontal rounded-corner col-lg-12">
                <!-- footer content start here -->
                <?php echo $footerContentDetails; ?>
                <!-- footer content ends here -->
			</form>
			<form class="form-horizontal rounded-corner col-lg-12" style="display:none">
				<div class="col-lg-12" >This is computer generated document and no authentication required.</div>
			</form>
          </div>
          <!--Body End-->
        <div style="clear:both;"></div>
        </div>
    </body>
</html>