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
    
    $recProf['pageIndex']=$pageId;
    $recProf['pageSize']=PAGE_PER_NO;
    $recProf['employee_id']=$_SESSION['employee_id'];
    //if($EID)
    $recProf['event_id']=$_REQUEST['event_id'];
    //var_dump($recArgs);
    //print_r($recProf);
    //$recListResponse= $eventClass->planofcareRecords($recProf);
    $recListResponse= $eventClass->SelectedPlanCareServices($recProf);
    $EventResponse= $eventClass->GetEvent($recProf);
    $patArg['patient_id'] = $EventResponse['patient_id'];
    $patientHHCresponse = $patientsClass->GetPatientById($patArg);
    //var_dump($recListResponse);
   // exit;
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count']; 
    // Get Notification list
	$notiList = $eventClass->getPushNotification($_REQUEST['event_id'], $recList[0]['service_id']);
    
    if($recListCount > 0)
    {
        $paginationCount=getAjaxPagination($recListCount);
    }
    if(!$recListCount)
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    if($recListCount)
    { 
    echo '<h4 class="text-center title-services">FIND PROFESSIONAL</h4>';
    ?>
    <div id="parentHorizontalTab" class="tabholder">
            <ul class="resp-tabs-list hor_1">
                
                <?php
                foreach ($recList as $recListKey => $recListValue) 
                {
                    $servIDSSel .= $recListValue['service_id'].':,';
                    echo '<li>'.$recListValue['service_title'].'</li>';
                    echo '<input type="hidden" name="AllServiceEvent[]" id="AllServiceEvent" value="'.$recListValue['service_id'].'" >';
                }
                $trimedSelc = rtrim($servIDSSel,':,');
                ?>
                <input type="hidden" name="serviceSelecAll" id="serviceSelecAll" value="<?php echo $trimedSelc;?>" >
            </ul>
            <div class="resp-tabs-container hor_1">
                <?php
                foreach ($recList as $recListKey => $recListValue) 
                {
                   ?>
                <div>
<!--                    <div class="demo-output">
                        <input class="range-slider" type="hidden" value="5,10" name="kmslider_<?php echo $recListValue['service_id'];?>" id="kmslider_<?php echo $recListValue['service_id'];?>" />
                    </div>-->
<!--                    <div class="layout-slider col-lg-6">
                        KM: <input id="Slider5" class="Slider5" type="slider" name="area" value="0;45" />
                    </div>-->

                    <div class="clearfix margintop20"></div>
                    <div class="demo-output">
                        <input class="slider-location_<?php echo $recListValue['service_id'];?>" type="hidden" value="0,20" name="kmslider_<?php echo $recListValue['service_id'];?>" id="kmslider_<?php echo $recListValue['service_id'];?>" />
                    </div>
                    
                    <div class="clearfix margintop20"></div>
                    
                    <br>
                    <div class="paddingTB25">
                    <!--<form name="searchProfServ" id="searchProfServ" method="post" >-->
                    <div class="form-inline" >
                        <div class="form-group ">
                            <label class="color-text">Search By: </label>
                        </div>
                        <div class="form-group paddingLR10" style="position:relative;">
                            <input type="text" class="form-control" id="professionalKeyword_<?php echo $recListValue['service_id'];?>" name="professionalKeyword_<?php echo $recListValue['service_id'];?>" value="" placeholder="Name / Location" onchange="return serachProfessional(<?php echo $recListValue['service_id'];?>);" >
                            <div class="paddingLR10" style="position:absolute; right:12px; top:7px; width:40px; background:#fff;">
                            <img src="images/search-icon.png" class="mCS_img_loaded" onclick="return serachProfessional(<?php echo $recListValue['service_id'];?>)" />
                            </div>
                        </div>
						
						<!-- BroadCasting code start here -->
						<div class="pull-right">
							<input type="button" class="btn btn-primary" id="btn_boarscating_<?php echo $recListValue['service_id'];?>" name="btn_boarscating_<?php echo $recListValue['service_id'];?>" value="Broadcast" onclick="javascript: return boarscate_event(<?php echo $_REQUEST['event_id']; ?>,<?php echo $recListValue['service_id'];?>);" />
						</div>
						<!-- BroadCasting code ends here -->
						
						
                        <!--<div class="form-group paddingLR10">
                            
                            <img src="images/search-icon.png" class="mCS_img_loaded" onclick="return serachProfessional(<?php //echo $recListValue['service_id'];?>)" />
                        </div>-->
<!--                        <div class="form-group paddingLR10">
                            <label class="select-box-lbl">
                                <select class="form-control" name="availability_<?php echo $recListValue['service_id'];?>" id="availability_<?php echo $recListValue['service_id'];?>" onchange="return serachProfessional(<?php echo $recListValue['service_id'];?>);">
                                    <option value="">Availability</option>
                                    <option value="1">Not Scheduled</option>
                                    <option value="2">Scheduled</option>
                                </select>
                            </label>
                        </div>-->
<!--                        <div class="form-group paddingLR10" style="width:200px!important;">
                            <label class="select-box-lbl">
                                <select data-placeholder="Select Location" tabindex="2" class="chosen-select form-control profchoserID" name="Proflocation_id_<?php echo $recListValue['service_id'];?>" id="Proflocation_id_<?php echo $recListValue['service_id'];?>" onchange="return serachProfessional(<?php echo $recListValue['service_id'];?>);">
                                <option value="">Location</option>
                                <select class="form-control" name="Proflocation_id_<?php echo $recListValue['service_id'];?>" id="Proflocation_id_<?php echo $recListValue['service_id'];?>" onchange="return serachProfessional(<?php echo $recListValue['service_id'];?>);">
                                     <option value="">Location</option>
                                     <?php
                                     /*$arr1['list'] = 'all';
                                     $ResultDoctors = $eventClass->LocationList($arr1);                          
                                     foreach($ResultDoctors as $key=>$valRecords)
                                     {
                                        if($recListResponse['locationNm'] == $valRecords['location'])
                                            echo '<option value="'.$valRecords['location_id'].'" selected="selected">'.$valRecords['location'].'</option>';
                                        else
                                            echo '<option value="'.$valRecords['location_id'].'">'.$valRecords['location'].'</option>';
                                     }
                                     unset($arr1);*/
                                     ?>
                                 </select>
                            </label>
                        </div>-->
                        <input type="hidden" name="Prof_service_id_<?php echo $recListValue['service_id'];?>" id="Prof_service_id_<?php echo $recListValue['service_id'];?>" value="<?php echo $recListValue['service_id'];?>" >
                    </div>
                    <!--</form>-->
                    </div>
					
				<!-- This section is used for to display professional list who received notification -->
					<div class="bcProfIncludeDiv_<?php echo $recListValue['service_id'];?>">
						<?php
							if (!empty($notiList[0])) { ?>
								<table id="boarscateLogTable" class="table table-striped" cellspacing="0" width="100%">
									  <thead>
										<tr>
											<th width="10%">Prof Code</td>
											<th width="18%">Name</td>
											<th width="13%">Accepted Status</td>
										</tr>
									  </thead>
									  <tbody>
									  <?php 
										foreach ($notiList[0] AS $key => $valMsg) { ?>
											<tr>
												<td><?php echo $valMsg['professional_code']; ?></td>
												<td><?php echo $valMsg['professional_name']; ?></td>
												<td><?php echo $valMsg['acknowledgedStatus']; ?></td>
											</tr>
										<?php }
									  ?>
									  </tbody>
								</table>	  
							<?php } ?>
					</div>
					<!-- This section is used for to display professional list who received notification -->
					
                    <div class="ProfessionalIncludeDiv_<?php echo $recListValue['service_id'];?>">
                        <?php       
                            $service_id = $recListValue['service_id'];
                            include 'include_include_professional.php';
                        ?>
                    </div>
            </div>
                    <?php
                }
                ?>
                
        </div>
    </div>
          <?php $show = '';
          $selectExist_prof = "select event_professional_id from sp_event_professional where event_id='".$_REQUEST['event_id']."' and service_closed = 'N' ";
          if(mysql_num_rows($db->query($selectExist_prof)))
          {
              $show = 'show';
          }
          ?>
          <div class="clearfix"></div>
    <?php
    } 
}
?>