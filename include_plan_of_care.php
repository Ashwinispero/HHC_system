<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass(); 
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";    
    
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['employee_id']=$_SESSION['employee_id'];
    //if($EID)
    $recArgs['event_id']=$_REQUEST['event_id'];
    //var_dump($recArgs);
    //print_r($recArgs);
    $recListResponse= $eventClass->planofcareRecords($recArgs);
    
   // echo '<pre>';
    //print_r($recListResponse);
   // echo '</pre>';
   // exit;

    $EventResponse = $eventClass->GetEvent($recArgs);

    if (!empty($EventResponse)) {
        $discountType      = ($EventResponse['discount_type'] ? $EventResponse['discount_type'] : '');
        $discountValue     = ($EventResponse['discount_value'] ? $EventResponse['discount_value'] : '');
        $discountAmount    = ($EventResponse['discount_amount'] ? $EventResponse['discount_amount'] : '');
        $discountNarration = ($EventResponse['Invoice_narration'] ? $EventResponse['Invoice_narration'] : '');
        $discountNarrationContent = ($EventResponse['invoice_narration_desc'] ? $EventResponse['invoice_narration_desc'] : '');
    }

    $patArg['patient_id'] = $EventResponse['patient_id'];
    $patientHHCresponse = $patientsClass->GetPatientById($patArg);
    //var_dump($recListResponse);
   // exit;
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count']; 
    if($recListCount > 0)
    {
        $paginationCount=getAjaxPagination($recListCount);
    }
    if(!$recListCount)
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    if($recListCount)
    {
    echo '<h2 class="page-title">Plan of Care <small class="pull-right event-id">Event Id : '.$patientHHCresponse['hhc_code'].'/'.$EventResponse['event_code'].'</small></h2>';
    ?>
    <form name="PlanofCareForm" id="PlanofCareForm" method="post" action="event_ajax_process.php?action=submitPlanofCare">
        <div id="logTable"> 
            <input type="hidden"  name="PlanEvent_id" id="PlanEvent_id" value="<?php echo $_REQUEST['event_id'];?>" > 
            <input type="hidden" name="EstimationRadioStatus" id="EstimationRadioStatus">
            <div class="main-row" style="background: #00cfcb;color:#fff;font-size:16px;">
                <div style="width:13%;display:inline-block;padding-right:1%;padding:4px;">Service</div>
                <div style="width:23%;display:inline-block;padding-right:1%;padding:4px;">Recommended Service</div>
                <div class="text-center" style="width:27%;display:inline-block;padding-right:1% !important;padding:4px;">Date</div>
                <div class="text-center" style="width:27%;display:inline-block;padding-right:1% !important;padding:4px;">Time</div>
                <div class="text-right" style="width:7%;display:inline-block;padding:4px 4px 4px 10px;">Cost <img src="images/rupee.png" style="vertical-align:inherit;" /></div> 
            </div>
            <div class="clearfix"></div>
            <div class="main-row"> 
                    <div style="width:13%;display:inline-block;padding-right:1%;padding:4px;"> &nbsp; </div>
                    <div style="width:24%;display:inline-block;padding-right:1%;padding:4px;"> &nbsp; </div>
                    <div style="width:27%;display:inline-block;padding-right:1%;padding:4px;">
                        <div class="pull-left text-center" style="width:48%;display:inline-block;padding-right:1%;padding:4px;">From</div>
                        <div class="pull-left text-center" style="width:48%;display:inline-block;padding-right:1%;padding:4px;">To</div>
                        <div class="pull-left" style="width:4%;display:inline-block;padding-right:1%;padding:4px;"> &nbsp; </div> 
                    </div>
                    <div style="width:27%;display:inline-block;padding-right:1%;">
                        <div class="pull-left text-center" style="width:40%;display:inline-block;padding-right:1%;padding:4px;">From</div>
                        <div class="pull-left text-center" style="width:40%;display:inline-block;padding-left:1%;padding:4px;">To</div>
                        <div class="pull-left" style="width:10%;display:inline-block;padding-right:1%;padding:4px;"> &nbsp; </div>
                        <div class="pull-left" style="width:10%;display:inline-block;padding-right:1%;padding:4px;"> &nbsp; </div>
                    </div>
                    <div style="width:7%;display:inline-block;"> &nbsp; </div> 
            </div>
			 
            <div class="clearfix"></div>
    <?php
        $total_cost = 0; 
        $totalTax=0;
        $i=0;
        
        foreach ($recList as $recListKey => $recListValue) 
        {
           // echo '<pre>';
            //print_r($recListValue['recommomded_service']);
          //  echo '</pre>';
            
            $sub_service_id = $recListValue['sub_service_id'];
			 $event_requirement_id = $recListValue['event_requirement_id'];
			 $service_id = $recListValue['service_id'];
			 
			$query_package=mysql_query("SELECT * FROM sp_services where service_id='$service_id' ") or die(mysql_error());
			$query_package_row = mysql_fetch_array($query_package) or die(mysql_error());
			$Package_status=$query_package_row['Package_status'];
			
			  //$Consultant = $recListValue['Consultant'];
            //$hospital_id = $recListValue['hospital_id'];
            
            $reqPlanArr['event_id'] = $recArgs['event_id'];
            $reqPlanArr['event_requirement_id'] = $event_requirement_id;
            $reqPlanArr['sub_service_id'] = $sub_service_id;
			
            $requirementPlan = $eventClass->MultipleplanofcareRecords($reqPlanArr);
			$event_code=$recArgs['event_id'];
			$query=mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_code' ") or die(mysql_error());
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
			}
			
           // echo '<pre>';
          //  print_r($requirementPlan);
           // print_r($requirementPlan['count']);
          //  echo '</pre>';
            
            $data = $requirementPlan['data'];
            
           // echo '<pre>';
           // print_r($data);
          //  echo '</pre>';
            
            
            //print_r($data);
            $st = 1; $dateDiff = 1;$fromDate = '';$toDate=''; $start_time = '';$endtime='';$pkgdat='';
            if(!empty($requirementPlan['count']))
            {
                foreach($data as $key=>$valPlanCareMultiple)
                {
                    if($valPlanCareMultiple['service_date'] && $valPlanCareMultiple['service_date']!='0000-00-00')
                        $fromDate = date('d-m-Y',strtotime($valPlanCareMultiple['service_date'])); //d-m-Y
                    if($valPlanCareMultiple['service_date_to'] && $valPlanCareMultiple['service_date_to']!='0000-00-00')
                        $toDate = date('d-m-Y',strtotime($valPlanCareMultiple['service_date_to']));
                    if($valPlanCareMultiple['start_date'])
                        $start_time = date('H:i A',strtotime($valPlanCareMultiple['start_date']));
                    if($valPlanCareMultiple['end_date'])
                        $endtime = date('H:i A',strtotime($valPlanCareMultiple['end_date']));
                    /*------- date difference ----*/
                        $diff = (strtotime($toDate)- strtotime($fromDate))/24/3600; 
                        //echo $diff;
                        $dateDiff = $diff+1;
                    
                    if($st == '1')
                    {
                        if($recListValue['recommomded_service']=='Other' OR $recListValue['recommomded_service']=='Assisted Living Deposit')
                        {
                            $cost=$valPlanCareMultiple['service_cost'];
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
							 /*echo '<div class="main-row"> 
                                    <input type="hidden" name="existIDPlan_'.$event_requirement_id.'" id="existIDPlan_'.$event_requirement_id.'" value="'.$valPlanCareMultiple['plan_of_care_id'].'" >
                                    <div style="width:13%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['service_title'].' </div>
                                    <div style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['recommomded_service'].'</div>
									
                              <div style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;text-align:left" disabled>'.$telephonic_consultation_fees.'%</div>
							 </div>';*/
							  
						}	
						else{							
                        echo '<div class="main-row"> 
                                    <input type="hidden" name="existIDPlan_'.$event_requirement_id.'" id="existIDPlan_'.$event_requirement_id.'" value="'.$valPlanCareMultiple['plan_of_care_id'].'" >
                                    <div style="width:13%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['service_title'].' </div>
                                    <div style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['recommomded_service'].'</div>';
                                if($recListValue['recommomded_service']=='Assisted Living Deposit')
								{
									echo '<div style="width:92%;display:inline-block;padding-right:1%;">
                                         
                                </div>';
								}
								else
								{
									echo '<div style="width:27%;display:inline-block;padding-right:1%;">
                                        <div class="pull-left" style="width:50%;display:inline-block;padding-right:2%;padding:4px;">
                                                <input type="text" value="'.$fromDate.'" name="eve_from_date_0_'.$event_requirement_id.'" id="eve_from_date_0_'.$event_requirement_id.'" class="form-control datepicker_eve_0 readonly"  />
                                        </div>
                                        <div class="pull-left" style="width:50%;display:inline-block;padding-left:2%;padding:4px;">
                                                <input type="text"  value="'.$toDate.'" name="eve_to_date_0_'.$event_requirement_id.'" id="eve_to_date_0_'.$event_requirement_id.'" class="form-control datepicker_eve_to_0 readonly"  />
                                        </div> 
                                    </div>
                                    <div style="width:27%;display:inline-block;padding-right:1%;">
                                        <div class="datepairExample_0">
                                            <div class="pull-left" style="width:40%;display:inline-block;padding-right:2%;padding:4px;">
                                                    <label style="display:block;">
                                                            <input  value="'.$start_time.'" name="starttime_0_'.$event_requirement_id.'" id="starttime_0_'.$event_requirement_id.'" type="text" class="form-control time start validate_time readonly"  />
                                                    </label>
                                            </div>
                                            <div class="pull-left" style="width:40%;display:inline-block;padding-left:2%;padding:4px;">           
                                                    <label style="display:block;">
                                                            <input  value="'.$endtime.'" name="endtime_0_'.$event_requirement_id.'" id="endtime_0_'.$event_requirement_id.'"  type="text" class="form-control time end validate_time readonly"  />
                                                    </label>                
                                            </div>
                                            <div class="pull-left" style="width:10%;display:inline-block;padding-left:2%;padding:4px;">';
                                            if($valPlanCareMultiple['recommomded_service'] !="Other" OR $valPlanCareMultiple['recommomded_service'] !="Assisted Living Deposit") { $service_type="1"; } else {  $service_type="2"; }
                                                    echo '<a href="javascript:void(0);" title="Add" onclick="javascript:addMorePlanCare('.$event_requirement_id.','.$service_type.');" class="moreOptions"><img src="images/add.png"></a>
                                            </div>
                                            <div class="pull-left" style="width:10%;display:inline-block;padding-left:2%;padding:4px;">
                                                    <a href="javascript:void(0);" title="Minus" onclick="javascript:deleteMorePlanCare('.$event_requirement_id.','.$service_type.');" class="moreOptions"><img src="images/remove1.png"></a>
                                            </div> 
                                        </div>
                                    </div>';
								}		
									
									
                                    echo '<input type="hidden" name="hidden_costService_0_'.$event_requirement_id.'" id="hidden_costService_0_'.$event_requirement_id.'" value="'.$cost.'" >
                                    <div style="width:7%;display:inline-block;vertical-align:top;padding:4px;" class="text-right" id="costService_0_'.$event_requirement_id.'">';
                                    if($valPlanCareMultiple['recommomded_service']=='Other' OR $valPlanCareMultiple['recommomded_service'] =='Assisted Living Deposit') { echo '<input type="text" name="other_service_cost_0_'.$event_requirement_id.'" id="other_service_cost_0_'.$event_requirement_id.'" class="form-control number readonly" maxlength="5" onkeyup="javascript:return CalculateTotEstCost(this,this.value);" value="'.number_format($cost,2).'" />'; } else { echo number_format($cost,2); }
                                    echo ' </div> 
            		            </div>
				    <div class="clearfix"></div>';
					$telephonic_consultation_fees=0;
						}
                    }
                    else
                    {
                        if($recListValue['recommomded_service']=='Other' OR $recListValue['recommomded_service']=='Assisted Living Deposit')
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
							/* echo '<div class="main-row"> 
                                    <input type="hidden" name="existIDPlan_'.$event_requirement_id.'" id="existIDPlan_'.$event_requirement_id.'" value="'.$valPlanCareMultiple['plan_of_care_id'].'" >
                                    <div style="width:13%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['service_title'].' </div>
                                    <div style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['recommomded_service'].'</div>
                              	
                              <div style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;text-align:left" disabled>'.$telephonic_consultation_fees.'%</div>
							  </div>';*/
						}	
						else{	
                        echo '<div class="main-row" id="PlanDiv_'.$valPlanCareMultiple['plan_of_care_id'].'">
                                <div style="width:13%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;"> &nbsp; </div>
                                <div style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;"> &nbsp; </div>'; 
                                 if($sub_service_id=='423')
								{
									echo '<div style="width:92%;display:inline-block;padding-right:1%;">
                                         
                                </div>';
								
								}  
								else
								{
									echo '<div style="width:27%;display:inline-block;padding-right:1%;">
                                            <div class="pull-left" style="width:50%;display:inline-block;padding-right:2%;padding:4px;">
                                                    <input type="text" readonly value="'.$fromDate.'" name="eve_from_date'.$event_requirement_id.'" id="eve_from_date_0_'.$event_requirement_id.'" class="form-control"/>
                                            </div>
                                            <div class="pull-left" style="width:50%;display:inline-block;padding-left:2%;padding:4px;">
                                                    <input type="text" readonly value="'.$toDate.'" name="eve_to_date'.$event_requirement_id.'" id="eve_to_date_0_'.$event_requirement_id.'" class="form-control"  />
                                            </div> 
                                    </div>
                                    <div style="width:27%;display:inline-block;padding-right:1%;">
                                            <div class="datepairExample_0">
                                                    <div class="pull-left" style="width:40%;display:inline-block;padding-right:2%;padding:4px;">
                                                            <label style="display:block;">
                                                                    <input readonly value="'.$start_time.'" name="starttime'.$event_requirement_id.'" id="starttime_0_'.$event_requirement_id.'" type="text" class="form-control time start validate_time" />
                                                            </label>
                                                    </div>
                                                    <div class="pull-left" style="width:40%;display:inline-block;padding-left:2%;padding:4px;">       
                                                            <label style="display:block;">
                                                                    <input readonly value="'.$endtime.'" name="endtime'.$event_requirement_id.'" id="endtime_0_'.$event_requirement_id.'"  type="text" class="form-control time end validate_time" />
                                                            </label>                
                                                    </div>
                                                    <div class="pull-left" style="width:20%;display:inline-block;padding-left:2%;padding:4px;">
                                                            <a href="javascript:void(0);" title="Delete" onclick="javascript:DeletePlanRecord('.$valPlanCareMultiple['plan_of_care_id'].', ' . $EventResponse['event_id'] . ');" class="moreOptions"><img src="images/sm-icon-inactive.png"></a>
                                                    </div> 
                                            </div>
                                    </div> ';
								}
									
									
                                echo '<input type="hidden" name="hidden_costService_ex_'.$st.'_'.$event_requirement_id.'" id="hidden_costService_ex_'.$st.'_'.$event_requirement_id.'" value="'.$cost.'" >
                                <div style="width:7%;display:inline-block;vertical-align:top;padding:4px;" class="text-right" id="costService_'.$event_requirement_id.'">'.number_format($cost,2).'</div> 
                        </div>';
						$telephonic_consultation_fees=0;
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
                echo '<input type="hidden" name="existSelRec_'.$event_requirement_id.'" id="existSelRec_'.$event_requirement_id.'" value="'.$st.'" >';
            }
            else
            {
                //echo '<pre>Hi';
                // print_r($recListValue);
                 //  echo '</pre>';
				 if($recListValue['recommomded_service']=='Consultant Charges')	
						{
							/* echo '<div class="main-row"> 
                                    <input type="hidden" name="existIDPlan_'.$event_requirement_id.'" id="existIDPlan_'.$event_requirement_id.'" value="'.$valPlanCareMultiple['plan_of_care_id'].'" >
                                    <div style="width:13%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['service_title'].' </div>
                                    <div style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['recommomded_service'].'</div>
								
                              <div disabled style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;text-align:left">'.$telephonic_consultation_fees.'%</div>
							  </div>';*/
						}	
						else{	
                echo '<div class="main-row"> 
                        <input type="hidden" name="existIDPlan_'.$event_requirement_id.'" id="existIDPlan+'.$event_requirement_id.'" value="" >
                                <div style="width:13%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['service_title'].'</div>
                                <div style="width:24%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$recListValue['recommomded_service'].'</div> ';
								if(($Package_status==2) AND $sub_service_id!=425)  
							{
								if($sub_service_id=='423')
								{
									echo '<div style="width:27%;display:inline-block;padding-right:1%;">
                                         
                                </div>';
								}
								else
								{
									echo '<div style="width:27%;display:inline-block;padding-right:1%;">
                                        <div class="pull-left" style="width:50%;display:inline-block;padding-right:2%;padding:4px;">
                                                <input type="text"  value="'.$fromDate.'" name="eve_from_date_0_'.$event_requirement_id.'" id="eve_from_date_0_'.$event_requirement_id.'" class="form-control datepicker_eve_0" />
                                        </div>';
										
										$pkgdate= date('d-m-Y', strtotime('+30 day', strtotime($fromDate)));
										
                                        echo '<div class="pull-left" style="width:50%;display:inline-block;padding-left:2%;padding:4px;">
                                                <input type="text"  value="'.$pkgdate.'" name="eve_to_date_0_'.$event_requirement_id.'" id="eve_to_date_0_'.$event_requirement_id.'" class="form-control datepicker_eve_to_0"  readonly/>
                                        </div> 
                                </div>';
								}
								
							}	
							else
							{
								echo '<div style="width:27%;display:inline-block;padding-right:1%;">
                                        <div class="pull-left" style="width:50%;display:inline-block;padding-right:2%;padding:4px;">
                                                <input type="text"  value="'.$fromDate.'" name="eve_from_date_0_'.$event_requirement_id.'" id="eve_from_date_0_'.$event_requirement_id.'" class="form-control datepicker_eve_0" />
                                        </div>
                                        <div class="pull-left" style="width:50%;display:inline-block;padding-left:2%;padding:4px;">
                                                <input type="text"  value="'.$toDate.'" name="eve_to_date_0_'.$event_requirement_id.'" id="eve_to_date_0_'.$event_requirement_id.'" class="form-control datepicker_eve_to_0"  />
                                        </div> 
                                </div>';
							}								
							if($sub_service_id=='423')
								{
									echo '<div style="width:27%;display:inline-block;padding-right:1%;">
                                        
                                </div> ';
								}
								else	
								{
							echo '<div style="width:27%;display:inline-block;padding-right:1%;">
                                        <div class="datepairExample_0">
                                                <div class="pull-left" style="width:40%;display:inline-block;padding-right:2%;padding:4px;">
                                                        <label style="display:block;">
                                                                <input  value="'.$start_time.'" name="starttime_0_'.$event_requirement_id.'" id="starttime_0_'.$event_requirement_id.'" type="text" class="form-control time start validate_time"  />
                                                        </label>
                                                </div>
                                                <div class="pull-left" style="width:40%;display:inline-block;padding-left:2%;padding:4px;">       
                                                        <label style="display:block;">
                                                                <input  value="'.$endtime.'" name="endtime_0_'.$event_requirement_id.'" id="endtime_0_'.$event_requirement_id.'"  type="text" class="form-control time end validate_time"  />
                                                        </label>                
                                                </div>
                                                <div class="pull-left" style="width:10%;display:inline-block;padding-left:2%;padding:4px;">';
                                                    if($recListValue['recommomded_service'] !="Other" OR $recListValue['recommomded_service'] !="Assisted Living Deposit") { $service_type="1"; } else {  $service_type="2"; }
                                                    
                                                        echo '<a href="javascript:void(0);" title="Add" onclick="javascript:addMorePlanCare('.$event_requirement_id.','.$service_type.');"><img src="images/add.png"></a>
                                                </div> 
                                                <div class="pull-left" style="width:10%;display:inline-block;padding-left:2%;padding:4px;">
                                                        <a href="javascript:void(0);" title="Minus" onclick="javascript:deleteMorePlanCare('.$event_requirement_id.','.$service_type.');"><img src="images/remove1.png"></a>
                                                </div> 
                                        </div>
                                </div> '; 
								}								
                               echo ' <input type="hidden" name="hidden_costService_0_'.$event_requirement_id.'" id="hidden_costService_0_'.$event_requirement_id.'" value="'.$recListValue['cost'].'" > 
				<div style="width:7%;display:inline-block;vertical-align:top;padding:4px;" class="text-right" id="costService_0_'.$event_requirement_id.'">';
                                if($recListValue['recommomded_service']=='Other' OR $recListValue['recommomded_service'] =='Assisted Living Deposit') { echo '<input type="text" name="other_service_cost_0_'.$event_requirement_id.'" id="other_service_cost_0_'.$event_requirement_id.'" class="form-control number" maxlength="5" onkeyup="javascript:return CalculateTotEstCost(this,this.value);"  />'; } else { echo number_format($recListValue['cost'],2); }
                          echo '</div> 
		   </div>
		  
                   <div class="clear"></div>';
				   $telephonic_consultation_fees=0;
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
            echo '<input type="hidden" name="PlanCareextras_'.$event_requirement_id.'" id="PlanCareextras_'.$event_requirement_id.'" value="0" />';
            echo '<tr><td colspan="5"><div id="div_1_'.$event_requirement_id.'"><table><tr ><td colspan="5"> </td> </tr></table></div></td></tr>';
            
            $allRequirements[] = $event_requirement_id;
            $i++;
        }
        //print_r($allServices);
		
        $passArray = implode(",",$allRequirements);
        echo '<input type="hidden" name="AllReqSel" id="AllReqSel" value="'.$passArray.'" >';
        echo '<input type="hidden" name="event_id" id="event_id" value="'.$recArgs['event_id'].'" >';
        echo '<input type="hidden" name="encode_event_id" id="encode_event_id" value="'.base64_encode($recArgs['event_id']).'" >';
        echo '<tr class="tax-row"><td colspan="4" style="text-align:right;"></td><td></td></tr>';//(Included Tax):'.$totalTax.'
       
	   $totalTax = 0;
		
		//echo ' <div style="width:97%;display:inline-block;text-align:right">Consultants Charges: '.$telephonic_consultation_fees.'</div> ';
        $finalcost = ($total_cost + $totalTax);
        $telephonic_consultation_fees1 = $telephonic_consultation_fees / 100 ;
        $consultant_changes = $finalcost * $telephonic_consultation_fees1;
        echo '<input type="hidden" name="consultantCost" id="consultantCost" value="' . $telephonic_consultation_fees1 . '" >';
        $finalcost = $consultant_changes + $finalcost;
        $finalcost = round($finalcost);
        if($telephonic_consultation_fees != 0)
        {
        echo '<div class="main-row" style="background: #fdeed4; margin-top:20px;">
        <div style="width:92%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">TOTAL CONSULATNT CHARGES:'.$telephonic_consultation_fees.'%</div>
        <div id="TotalConCost" class="text-right" style="width:7%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.number_format(($consultant_changes), 2).'</div> 
        </div>';
        }
        echo '<div class="main-row" style="background: #fdeed4; margin-top:20px;">
				  <div style="width:92%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">TOTAL ESTIMATED COST:</div>
				  <div id="TotalEstCost" class="text-right" style="width:7%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.number_format(($finalcost), 2).'</div> 
              </div>';
    
        echo '<div class="row" style="margin-top:20px;">
                <div class = "col-md-3">
                    <label for="discount">
                        ADD DISCOUNT:
                    </label>
                </div>
                <div class = "col-md-2">
                    <select name = "discount_id" id = "discount_id" class="form-control" onChange = "return changeDiscountCombo()" disabled>
                        <option value = ""' . ($discountType == '' ? 'selected="selected"' : '') .'>-Select option-</option>
                        <option value = "1"' . ($discountType == '1' ? 'selected="selected"' : '') .'>In Percentage</option>
                        <option value = "2"' . ($discountType == '2' ? 'selected="selected"' : '') .'>In amount</option>
                    </select>
                </div>
                <div class = "col-md-2">
                    <input type = "text" name = "discount_amount" id = "discount_amount" value = "' . number_format($discountValue, 2) . '" class= "form-control" disabled onkeypress="return isNumberKey(event)" />
                </div>
                <div class = "col-md-4">
                    <select name = "discount_narration" id = "discount_narration" class="form-control" onChange = "return changeDiscountNarrationCombo()" disabled>
                        <option value = ""' . ($discountNarration == '' ? 'selected="selected"' : '') .'>-Select option-</option>
                        <option value = "Staff"' . ($discountNarration == 'Staff' ? 'selected="selected"' : '') .'>Staff</option>
                        <option value = "Consultant"' . ($discountNarration == 'Consultant' ? 'selected="selected"' : '') .'>Consultant</option>
                        <option value = "Complimentary visit"' . ($discountNarration == 'Complimentary visit' ? 'selected="selected"' : '') .'>Complimentary visit</option>
                        <option value = "Long term care"' . ($discountNarration == 'Long term care' ? 'selected="selected"' : '') .'>Long term care</option>
                        <option value = "Package discount"' . ($discountNarration == 'Package discount' ? 'selected="selected"' : '') .'>Package discount</option>
                        <option value = "Other"' . ($discountNarration == 'Other' ? 'selected="selected"' : '') .'>Other</option>
                    </select>
                    <div class="narrationContent" style="margin-top:10px; ' . ($discountNarrationContent ? 'display:block !important;' : 'display:none !important;' ) . '">
                        <input type="text" name="discount_narration_content" id="discount_narration_content" value="' . $discountNarrationContent . '" class= "form-control" placeholder = "Please enter narration" disabled />
                    </div>
                </div>
                <div class = "col-md-1 pull-right totalDiscountCost">
                    ' . ($discountAmount != '0.00' ? number_format($discountAmount, 2) : '') . '
                </div>
            </div>';


            echo '<div class="main-row finalAmountWithDiscountContentDiv" style="background: #fdeed4; margin-top:20px; ' . ($discountValue ? 'display:block !important;' : 'display:none !important;' ) . '">
                    <div style="width:92%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">
                        TOTAL ESTIMATED COST WITH DISCOUNT:
                    </div>
                    <div id="finalAmountWithDiscountCost" class="text-right" style="width:7%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">
                        ' . number_format(($finalcost - $discountAmount), 2) . '
                    </div> 
                </div>';


        echo '<input type="hidden" name="finalcost_eve" id="finalcost_eve" value="' . $finalcost . '" >
              <input type="hidden" name="discountCost_eve" id="discountCost_eve" value="" >
              <input type="hidden" name="finalCostWithDiscount_eve" id="finalCostWithDiscount_eve" value="" >';
        $select_submittedplan = "select plan_of_care_id from sp_event_plan_of_care where event_id = '".$recArgs['event_id']."' ";
        if(mysql_num_rows($db->query($select_submittedplan)))
        {
            $classCheck = "checked";
        }
        else $classCheck = '';
        
        echo '<div class="main-row planofcaredivcl" style="margin-bottom:10px;">
                    <div class="text-right" style="width:92%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">
                      Confirm Estimated Cost: 
                          <input type="radio" name="confirmEstimatedBox" id="confirmEstimatedBox1" '.$classCheck.' value="1" onclick="return confirmEstimatedCost(3,\''.$passArray.'\');"> Yes
                          <input type="radio" name="confirmEstimatedBox" id="confirmEstimatedBox" value="0" onclick="return confirmEstimatedCost(2,\''.$passArray.'\');" > No
                    </div>
                    <div style="width:7%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;"> &nbsp; </div> 
                  <div class="clearfix"></div>
            </div>';
        ?>
        </div>
    </form>
        <?php
        } 
}
?>