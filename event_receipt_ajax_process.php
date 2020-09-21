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
        if(!empty($getEventDetail))
        {
            $event_code = $getEventDetail['event_code'];
            $event_date = date('d M Y h:i A',strtotime($getEventDetail['event_date']));
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
                    $("#Block1,#Block2,#Block3,#Block4,#Block5,#Block6,#Block7,#Block8,#Block9,#Block10,#Block11").show();
                    //$("#Block4,#Block5,#Block6,#Block7,#Block8,#Block9,#Block10,#Block11").hide();
                </script>
    <?php 
           }
           else 
           {
              $heading_content="GENERAL INFORMATION"; 
               ?>
                <script type="text/javascript">
                    $("#Block1,#Block2,#Block3,#Block4,#Block5,#Block6,#Block7,#Block8,#Block9,#Block10,#Block11").show();
                   //$("#Block2,#Block4,#Block5,#Block6,#Block7,#Block8,#Block9,#Block10,#Block11").hide();
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
	
	
	/*$fetch_prof_nm = mysql_query("SELECT * FROM sp_event_professional WHERE event_id='$event_number'");
	$fetch_prof_nm = mysql_fetch_array($fetch_prof_nm);
	$professional_vender_id = $fetch_prof_nm['professional_vender_id'];
	
	$fetch_prof_name = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id='$professional_vender_id'");
	$fetch_prof_name = mysql_fetch_array($fetch_prof_name);
	$title = $fetch_prof_name['title'];
	$name = $fetch_prof_name['name'];
	$first_name = $fetch_prof_name['first_name'];
	$middle_name = $fetch_prof_name['middle_name'];
	$mobile_no=$fetch_prof_name['mobile_no'];*/
	
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
        if(!empty($EventSummaryDtls['CallerDtls']))
        { ?>
            <div id="Block1">
                <h4 class="section-head text-left"><input type="checkbox" name="caller_dtls_block" id="caller_dtls_block" class="case" value="1"> <span><img height="29" width="29" src="images/coller-icon.png" class="mCS_img_loaded"></span> BILL DETAILS</h4>
                <form class="form-horizontal" style="padding-left:50px;">
				
                        <div class="form-group">
                            <label class="col-sm-2 control-label" style="padding-top:0px;">Bill Number :</label>
                            <div class="col-sm-4">
                                <?php //if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo "$code / $y-$fin_year / $event_number"; } else {  echo "-"; }
								echo "$code / $financial_year / $bill_no_ref_no";?>
                            </div>
					<?php 
					//PHP code for invoice fields - Amod
					$row_date = "$get_event_number[added_date]";
					list($date,$time) = explode(' ',$row_date);
					$invoice_date = Date("d-m-Y", strtotime($date));
					?>
							<label class="col-sm-2 control-label" style="padding-top:0px;">Date :</label>
                            <div class="col-sm-4">
                                <?php //if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo "$invoice_date"; } else {  echo "-"; } 
								echo "$invoice_date"; ?>
                            </div>
                        </div>
						
						<div class="form-group">
							<label class="col-sm-2 control-label" style="padding-top:0px;">Reference Number :</label>
								<div class="col-sm-4">
									<?php //if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo "$name_address[hhc_code] / $event_code"; } else {  echo "-"; }
									echo "$name_address[hhc_code] / $event_code"; ?>
								</div>
								<label class="col-sm-2 control-label" style="padding-top:0px;">Professional Name:</label>
                            <div class="col-sm-4">
                                <?php //if(!empty($EventSummaryDtls['CallerDtls']['phone_no'])) { echo "$invoice_date"; } else {  echo "-"; } 
								echo "$professional_name" ; ?>
                            </div>
						</div>      
						
						<!--<div class="form-group">
							<label class="col-sm-2 control-label" style="padding-top:0px;">Professional :</label>
							<div class="col-sm-10">
								<?php //if(!empty($EventSummaryDtls['CallerDtls']['email_id'])) { echo "Pending"; } else {  echo "-"; }
								//echo "Pending"; ?>
							</div>
						</div>-->
					
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
                    <div class="col-sm-4">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }  else {  echo ""; } ?>
                    </div>
					<label class="col-sm-2 control-label" style="padding-top:0px;">Mobile :</label>
                    <div class="col-sm-4">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['mobile_no'])) { echo $EventSummaryDtls['PatientDtls']['mobile_no']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" style="padding-top:0px;">Residential Address :</label>
                    <div class="col-sm-4">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['residential_address'])) { echo $EventSummaryDtls['PatientDtls']['residential_address']; } else {  echo "-"; } ?>
                    </div>
					<label class="col-sm-2 control-label" style="padding-top:0px;">Permanent Address :</label>
                    <div class="col-sm-4">
                        <?php if(!empty($EventSummaryDtls['PatientDtls']['permanant_address'])) { echo $EventSummaryDtls['PatientDtls']['permanant_address']; } else {  echo "-"; } ?>
                    </div>
                </div>
				
        </form>
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
                <h4 class="section-head"><input type="checkbox" name="plan_of_care_dtls_block" id="plan_of_care_dtls_block" class="case" value="5" /> <span><img height="29" width="29" src="images/plan-of-care.png" class="mCS_img_loaded"></span> SERVICE DETAILS </h4>
                <table id="logTable" class="table table-striped" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Recommended Service</th>
                            <th>Date (From/To)</th>
                            <th>Time (Form/To)</th>
                            <th>Cost <img src="images/rupee.png" style="vertical-align:center;" /></th>
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
                                                
                                               //  echo '<pre>';
                                               // print_r($data);
                                              //  echo '</pre>';
                         $query_package=mysql_query("SELECT * FROM sp_services where service_id='$service_id' ") or die(mysql_error());
			$query_package_row = mysql_fetch_array($query_package) or die(mysql_error());
			$Package_status=$query_package_row['Package_status'];                       
                                                
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
                                                                                    echo '<td>'.$cost.'/-</td>
                                                                                </tr>';
																}}
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
								}
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
                    $finalcost = ($totalEstimatedCost - $all_event_details['discount_amount']);
                    $finalcost = round($finalcost);
                                
                    echo '<tr class="' . ($all_event_details['discount_amount'] == '0.00' ? 'total-row' : '') . '">
                            <td colspan="4" style="text-align:left;">TOTAL ESTIMATED COST:</td>';
                                if(!empty($fromDate) && !empty($toDate)) 
                                {
                                    echo '<td><img src="images/rupee.png" style="vertical-align:center;" />'.number_format($totalEstimatedCost, 2).'/-</td>';
                                }
                                else 
                                {
                                    echo '<td>NA</td>'; 
                                }
                    echo '</tr>
                          <tr>
                                <td colspan="5"></td>
                          </tr>
                    ';

                    if ($all_event_details['discount_amount'] != '0.00') {
                        echo '<tr>
                            <td colspan="4" style="padding:5px; font-size:11px; color:#333;">
                                TOTAL DISCOUNT COST:
                            </td>
                            <td>
                                <img src="images/rupee.png" style="vertical-align:center;" />
                                ' . ($all_event_details['discount_amount'] ? number_format($all_event_details['discount_amount'], 2) . '/-' : 'NA') . '
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>';

                        echo '<tr class="total-row">
                            <td colspan="4" style="background:#fdeed4; padding:5px; font-size:11px; color:#333;">
                                TOTAL ESTIMATED COST WITH DISCOUNT:
                            </td>
                            <td style="background:#fdeed4;">
                                <img src="images/rupee.png" style="vertical-align:center;" />
                                ' . ($finalcost ? number_format($finalcost, 2) . '/-' : 'NA') . '
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
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
					
					
					echo '<tr class="total-row"><td colspan="1" style="text-align:left;">AMOUNT IN WORDS:</td>';
                                            if(!empty($fromDate) && !empty($toDate)) 
                                            {
												$in_words = convert_number_to_words($finalcost);
                                               echo '<td colspan="4" style="text-align:right;">Rupees '.$in_words.' Only</td>';
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
            <div class="line-seprator"></div>
        </div>
        <?php } ?>
		<div class="row">
		<div class="col-lg-6">
		<form class="form-horizontal rounded-corner col-lg-12">
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
		
				
		</form> </div>
			<div class="col-lg-6">
    		<form class="form-horizontal rounded-corner col-lg-12" >
				<div class="form-group">
					<div class="col-lg-5"><b>Declaration:</b></div><br>
					<div class="col-lg-12">We declare that this Bill shows the actual price of the services described and that all particulars are true and correct.</div>
				<br><br><br>
				</div>
			</form> </div></div>
			 <div class="line-seprator"></div>
		 <?php
		 if(!empty($recList))
        {	
		 ?>
            <div id="Block6" >
                <h4 class="section-head"><input type="checkbox" name="plan_of_care_dtls_block" id="plan_of_care_dtls_block" class="case" value="6" /> <span><img height="29" width="29" src="images/plan-of-care.png" class="mCS_img_loaded"></span>PATIENT PAYMENT RECEIPT DETAILS </h4>
                <?php
			
				
				
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
				
                    <?php 
                        $eventid = $event_number;
                        $exist = mysql_query("SELECT * FROM sp_payments WHERE event_id='$eventid'");
                        $row_count123 = mysql_num_rows($exist);
                        if($row_count123 > 0)
                        {
                            while ($rows = mysql_fetch_array($exist))
                            {
                                $type=$rows['type'];
                                $cheque_DD__NEFT_no=$rows['cheque_DD__NEFT_no'];
                                $amount=$rows['amount'];
                                $Transaction_ID=$rows['Transaction_ID'];
                                $date_time=$rows['date_time'];
                                $payment_receipt_no_voucher_no=$rows['payment_receipt_no_voucher_no'];
                                ?>
                                <table id="logTable" class="table table-striped" cellspacing="0" width="100%" >
                                <thead>
                                    <tr>
                                         <th>Payment Mode </th>
                                        <th>Cheque/NEFT/Transaction No</th>
                                        <th>Date</th>
                                        <th>Amount <img src="images/rupee.png" style="vertical-align:center;" /></th>
                                    </tr>
                                </thead>
                                <tbody> 
                                <?php
                                echo '<tr>
                                        <td>'.$type.' </td>';
                                        if($type=='NEFT' OR $type=='Cheque') 
                                        { 
                                        echo '<td>'.$cheque_DD__NEFT_no.'</td>';
                                        }
                                        else if($type=='Card')
                                        {
                                            echo '<td>'.$Transaction_ID.'</td>'; 
                                        }
                                        else if($type=='Cash')
                                        {
                                            echo '<td>'.'NA'.'</td>'; 
                                        }
                                        echo '<td>'.$date_time.'</td>
                                        <td>'.$amount.'</td>
                                    </tr>';
                                    

                                    
                            }
                        }
                    ?>
                    </tbody>
                </table>
						
				<form class="form-horizontal rounded-corner col-lg-12" >
				<div class="col-lg-12">Receipt Against Bill Number:<?php echo "$code / $financial_year / $event_number / $payment_receipt_no_voucher_no";?></div>
				<div class="col-lg-12">Received From:
				<?php if(!empty($EventSummaryDtls['PatientDtls']['name'])) { echo $EventSummaryDtls['PatientDtls']['name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['first_name'])) { echo $EventSummaryDtls['PatientDtls']['first_name']." "; } if(!empty($EventSummaryDtls['PatientDtls']['middle_name'])) { echo $EventSummaryDtls['PatientDtls']['middle_name']; }  else {  echo ""; } ?></div>
				<!--<div class="col-lg-12">Received From:________________________________________________________________________________________________________________</div>-->
				<div class="col-lg-3">Received Rs.<b> </b></div>
				<div class="col-lg-6">(Rupees:__________________________________________________________________________________Only)</b></div>
				<div class="col-lg-12">Type Of Transaction: </b></div>
				<div class="col-lg-4">Mode Of Transaction:</div><div class="col-lg-6">Of Date :</b></div>
				<?php 
				if($type=='Card')
				{
					?>
					<div class="col-lg-12">Card Number:<?php echo $Card_Number; ?> </b></div>
					<div class="col-lg-12">Transaction ID: <?php echo $Transaction_ID; ?></b></div>
				<?php 
				}
				?>
				<div class="col-lg-12">Remark:</b></div>
				<div class="col-lg-12">towards settlement of the above bill.</div>
				
				<div class="form-group">
				
								<div class="col-lg-5 pull-left text-center" style="margin-top:20px;"><br>Signature with Name of Patient</div>
								<div class="col-lg-5 pull-right text-center" style="margin-top:20px;"><br>Signature with Name of Authority</div>
				</div>
						</form> <?php //}?>
				
			<form class="form-horizontal rounded-corner col-lg-12" style="padding-left:50px;">
				<div class="form-group">
					<div class="col-lg-5" ><b>Address:</b></div><br>
					<div class="col-lg-12" >Shiv Corner, Samarth Park - Brahma Garden Rd, Anand Nagar, Pune, Maharashtra 411051, Email : Info@sperohealthcare.in, Website:WWW.Sperohealthcare.in, Phone :7620400100</div>
				</div>
			</form>
			
			
        </div>
        <?php  }?>
		
			
			
			 <form class="form-horizontal rounded-corner col-lg-12" style="display:none">
				<div class="col-lg-12" >This is computer generated document and no authentication required.</div>
			</form>
			
			<!-- 
            <div id="Block11">
                 <h4 class="section-head">
                    <input type="checkbox" name="job_closure_block" id="job_closure_block" class="case" value="11" /> 
                    <span><img height="29" width="29" src="images/feedback.png" class="mCS_img_loaded"></span> PAYMENT RECEIPT DETAILS 
                </h4>
                 <form class="form-horizontal rounded-corner col-lg-12" style="padding-left:50px;">
                    <!--<div class="form-group">
                            <label class="col-sm-2 control-label" style="padding-top:0px;">Received Rs :</label>
							<div class="col-sm-10">
								<?php //echo 'Rupees '.$in_words.' Only'; ?>
							</div>
					</div>
					<div class="form-group">
                            <label class="col-sm-2 control-label" style="padding-top:0px;">Cash/Cheque :<br>Bank Name :</label>
							<div class="col-sm-4">
								<?php //echo "Pending"; ?>
							</div>
							<label class="col-sm-2 control-label" style="padding-top:0px;">Of Date :<br>Branch Name :</label>
							<div class="col-sm-4">
								<?php //echo ""; ?>
							</div>
					</div>
					<div class="form-group">
							<label class="col-sm-12 control-label" style="padding-top:0px;">towards settlement of the above bill.</label>
					</div>
					<div class="form-group">
                            <div class="col-lg-5 pull-left text-center" style="margin-top:20px;"><br>Signature with Name of Patient</div>
                            <div class="col-lg-5 pull-right text-center" style="margin-top:20px;"><br>Signature with Name of Authority</div>
					</div>
                 </form>
             </div>-->
            		
		
  </div>
</div>
<?php
    
        
    ?>
<div class="modal-footer"> 
    <!--<a href="javascript:void(0);" onclick="ViewEventActions('<?php echo $main_event_id; ?>','','continue')"  data-toggle="tooltip" data-original-title="Continue" title="Continue">
        <img src="images/continue.png" alt="Continue" />
    </a>&nbsp; -->
    <a href="javascript:void(0);" onclick="ViewReceiptActions('<?php echo $main_event_id; ?>','','download')"  data-toggle="tooltip" title="Download PDF">
        <img alt="Download PDF" src="images/pdf-icon.png" />
    </a>&nbsp; 
    <a href="javascript:void(0);" onclick="ViewReceiptActions('<?php echo $main_event_id; ?>','','email')" data-toggle="tooltip" title="Email">
        <img alt="Email" src="images/send-mail.png" />
    </a> 
</div>
<?php  
}
?>
