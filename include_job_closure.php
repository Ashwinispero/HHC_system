<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass(); 
require_once 'classes/commonClass.php';
$commonClass=new commonClass();
require_once 'classes/config.php';

if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";    
    $recSummary['pageIndex']=$pageId;
    $recSummary['pageSize']=PAGE_PER_NO;
    $recSummary['employee_id']=$_SESSION['employee_id'];
    $recSummary['event_id']=$_REQUEST['event_id'];
    $recSummary['service_date']=$_REQUEST['service_date'];
    
    $CallerId=$_REQUEST['Edit_CallerId'];
    if(!empty($CallerId))
    {
        // Getting professional_id 
        $GetCallerSql="SELECT professional_id FROM sp_callers WHERE caller_id='".$CallerId."'";
        $GetCaller=$db->fetch_array($db->query($GetCallerSql));
    }
    
    if(!empty($GetCaller))
     $recSummary['professional_vender_id']=$GetCaller['professional_id'];
    
    if(!empty($_REQUEST['professional_id']))
        $recSummary['professional_vender_id']=$_REQUEST['professional_id'];
    
    //var_dump($recArgs);
    //print_r($recSummary);
    $recRspJobSummary= $eventClass->GetJobSummary($recSummary);
    // Getting Unit Medicine List
      $arr['type']='1';
      $UnitMedicinesList=$commonClass->GetAllMedicines($arr);
      unset($arr);
    // Getting Non Unit Medicine List
      $arr['type']='2';
      $NonUnitMedicinesList=$commonClass->GetAllMedicines($arr);
      unset($arr);
      
      // Getting Unit Consumables List
      $arr['type']='1';
      $UnitConsumablesList=$commonClass->GetAllConsumables($arr);
      unset($arr);
    // Getting Non Unit Consumables List
      $arr['type']='2';
      $NonUnitConsumablesList=$commonClass->GetAllConsumables($arr);
      unset($arr);
      
      
      // First Check is it job closure available for this event
      
      if(!empty($recSummary['service_date']))
      {
        $JobClosure= $eventClass->GetJobClosure($recSummary);
      }
      
//      echo '<pre>';
//      print_r($JobClosure);
//      echo '</pre>';
      
      
      // Getting plan of care date
      
      $GetPlanofCareDatesSql="SELECT service_date,service_date_to FROM sp_event_plan_of_care WHERE event_id='".$recSummary['event_id']."'";
      $GetPlanofCareDates=$db->fetch_all_array($GetPlanofCareDatesSql);
      
     // echo '<pre>';
    //  print_r($GetPlanofCareDates);
    //  echo '</pre>';
      
      
      
      if(!empty($GetPlanofCareDates))
      {
          $DateArr=array();
          foreach($GetPlanofCareDates as $valDates) 
          {
            /*------- date difference ----*/
              
            $service_start_date=$valDates['service_date'];
            $service_end_date=$valDates['service_date_to'];
            $diff = (strtotime($service_end_date)- strtotime($service_start_date))/24/3600; 
            $dateDiff = $diff+1;
            if($dateDiff)
            {
                for($i=0;$i<$dateDiff;$i++)
                {
                   $DateArr[]=date('Y-m-d',strtotime($service_start_date . "+$i days"));
                }
            }
          }
          $AllDates=array_values(array_unique($DateArr));
          
         // echo '<pre>';
         // print_r($AllDates);
        //  echo '</pre>';
          
          $resultDates=bubbleSort($AllDates);  
          
        //  echo '<pre>';
        //  print_r($resultDates);
        //  echo '</pre>';
          
      }
      if(!empty($JobClosure['consumption']))
      {
          $UnitMedicineRecordArr=array();
          $UnitMedicineArr=array();
          $UnitMedicineQtyArr=array();
          $NonUnitMedicineRecordArr=array();
          $NonUnitMedicineArr=array();
          $NonUnitMedicineQtyArr=array();
          $UnitConsumbaleRecordArr=array();
          $UnitConsumbaleArr=array();
          $UnitConsumbaleQtyArr=array();
          $NonUnitConsumbaleRecordArr=array();
          $NonUnitConsumbaleArr=array();
          $NonUnitConsumbaleQtyArr=array();
          
          for($i=0;$i<count($JobClosure['consumption']);$i++)
          {
              if($JobClosure['consumption'][$i]['consumption_type']==1)
              {
                  $UnitMedicineRecordArr[]=$JobClosure['consumption'][$i]['consumption_id'];
                  $UnitMedicineArr[]=$JobClosure['consumption'][$i]['unit_id'];
                  $UnitMedicineQtyArr[]=$JobClosure['consumption'][$i]['unit_quantity'];
              }
              if($JobClosure['consumption'][$i]['consumption_type']==2)
              {
                  $NonUnitMedicineRecordArr[]=$JobClosure['consumption'][$i]['consumption_id'];
                  $NonUnitMedicineArr[]=$JobClosure['consumption'][$i]['unit_id'];
                  $NonUnitMedicineQtyArr[]=$JobClosure['consumption'][$i]['unit_quantity'];
              }
              if($JobClosure['consumption'][$i]['consumption_type']==3)
              {
                  $UnitConsumbaleRecordArr[]=$JobClosure['consumption'][$i]['consumption_id']; 
                  $UnitConsumbaleArr[]=$JobClosure['consumption'][$i]['unit_id']; 
                  $UnitConsumbaleQtyArr[]=$JobClosure['consumption'][$i]['unit_quantity'];
              }
              if($JobClosure['consumption'][$i]['consumption_type']==4)
              {
                  $NonUnitConsumbaleRecordArr[]=$JobClosure['consumption'][$i]['consumption_id'];
                  $NonUnitConsumbaleArr[]=$JobClosure['consumption'][$i]['unit_id']; 
                  $NonUnitConsumbaleQtyArr[]=$JobClosure['consumption'][$i]['unit_quantity']; 
              }
          }
      }
    if(!$recRspJobSummary)
        echo '<center><br><br><h1 class="messageText">Please assign the services to professionals. </h1></center>';
    if($recRspJobSummary)
    {
    echo '<h4 class="text-center title-services">JOB CLOSURE</h4>';
    ?>    
<form  name="frmjobClosure" id="frmjobClosure" method="post" enctype="multipart/form-data" action="event_ajax_process.php?action=SubmitJobClosure">
            <input type="hidden" name="event_id" id="event_id" value="<?php echo $recSummary['event_id']; ?>" />
            <input type="hidden" name="eventIDForClosure" id="eventIDForClosure" value="<?php echo $_REQUEST['eventIDForClosure']; ?>" />
            <input type="hidden" name="Edit_CallerId" id="Edit_CallerId" value="<?php echo $_REQUEST['Edit_CallerId']; ?>" />
            <input type="hidden" name="job_closure_id" id="job_closure_id" value="<?php if(!empty($JobClosure['job_closure_id'])) { echo $JobClosure['job_closure_id']; } ?>" />
            <input type="hidden" name="professional_vender_id" id="professional_vender_id" value="<?php if(!empty($recSummary['professional_vender_id'])) { echo $recSummary['professional_vender_id']; } ?>" />
             <div class="col-lg-4">
                <span class="color-text">Select Service Date : </span>
                <div class="select-holder">
<!--                    <label class="select-box-lbl chose">-->
                        <select class="validate[required] chosen-select form-control" name="service_date" id="service_date" onchange="return GetJobClosureDtls(this.value);"> 
                            <option value="">Select Date</option>
                            <?php   if(!empty($resultDates))
                                    {
                                        for($s=0;$s<count($resultDates);$s++)
                                        {
                                            $class = '';
                                            if(!empty($recSummary['service_date']) && $recSummary['service_date'] !='0000-00-00')
                                            {
                                                if($resultDates[$s]==$recSummary['service_date'])
                                                {
                                                     $class = 'selected="selected"'; 
                                                }
                                            }
                                            echo '<option '.$class.' value="'.$resultDates[$s].'">'.date('d-m-Y',strtotime($resultDates[$s])).'</option>';  
                                        }
                                    }
                            ?>
                        </select>
<!--                    </label>-->
                </div>
            </div>
            
            <div class="col-lg-12">
                <span class="color-text">Service Rendered : </span>  Yes <input type="radio" name="service_rendered" id="service_rendered" value="1"<?php if($_POST['service_rendered']=='1') { echo 'checked="checked"'; } else if(!empty($JobClosure['service_render']) && $JobClosure['service_render']=='1') { echo 'checked="checked"'; } ?> /> &nbsp; No <input type="radio" name="service_rendered" id="service_rendered" value="2"<?php if($_POST['service_rendered']=='1') { echo 'checked="checked"'; } else if(!empty($JobClosure['service_render']) && $JobClosure['service_render']=='2') { echo 'checked="checked"'; } ?>  />
            </div>
            
            <div class="col-lg-12">
                <span class="color-text">Upload Job closure file  : </span>  Yes <input type="radio" name="is_file_upload" id="is_file_upload" value="1"<?php if($_POST['is_file_upload']=='1') { echo 'checked="checked"'; } else if(!empty($JobClosure['job_closure_file'])) { echo 'checked="checked"'; } ?> /> &nbsp; No <input type="radio" name="is_file_upload" id="is_file_upload" value="2"  />
            </div>
            <div class="col-lg-4" id="upload_file_content" <?php if(!empty($JobClosure['job_closure_file'])) { echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?> >
                <?php if(!empty($JobClosure['job_closure_file']) && file_exists('JobClosureDocuments/'.$JobClosure['job_closure_file'])) { ?>
                <a href="<?php echo $siteURL;?>JobClosureDocuments/<?php echo $JobClosure['job_closure_file']; ?>" target="_blank">View Job Closure Image </a>
                <?php } ?>
                <input type="file" name="userfile" id="userfile" class="jobclosurefile" />
            </div>
            <div id="job_closure_content" <?php if(!empty($JobClosure['job_closure_file'])) { echo 'style="display: none;"'; } else { echo 'style="display: block;"'; } ?>>
            <?php  
               if(!empty($JobClosure['consumption'])) 
               { 
                   include 'include_job_closure_consumption.php';
               } 
            else {  ?>
               <div class="rounded-corner col-lg-12">
               <div class="color-text">Consumption Details:</div>
               <div class="row margintop10">
                   <div class="col-lg-4">
                       <label class="name color-text">Medicines:</label>
                       <br/>
                   </div>
                   <div class="clearfix"></div>
                   <div class="col-lg-4">
                       <label class="name">Unit:</label>
                       <div class="select-holder">
<!--                            <label class="select-box-lbl chose">-->
                               <select class="chosen-select form-control" name="unit_medicine_id" id="unit_medicine_id"> 
                                   <option value="">Medicines</option>
                                   <?php   if(!empty($UnitMedicinesList))
                                           {
                                               for($i=0;$i<count($UnitMedicinesList);$i++)
                                               {
                                                   $class = '';
                                                   for($a=0;$a<count($UnitMedicineArr);$a++)
                                                   {
                                                       if($UnitMedicineArr[$a] == $UnitMedicinesList[$i]['medicine_id'])
                                                           $class = 'selected="selected"';
                                                   }
                                                   echo '<option '.$class.' value="'.$UnitMedicinesList[$i]['medicine_id'].'">'.$UnitMedicinesList[$i]['name'].'</option>';
                                               }
                                           }
                                   ?>
                               </select>
<!--                            </label>-->
                       </div>
                   </div>
                   <div class="col-lg-4">
                       <label class="name"></label>
                       <input type="text" name="unit_medicine_quantity" id="unit_medicine_quantity" class="form-control" value="" maxlength="20" />
                   </div>
                   <div class="col-lg-4">
                       <label class="name"></label>
                       <div class="clearfix"></div>
                       <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_unit_medicine('1');"><img src="images/add.png"></a> &nbsp;  
                       <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_unit_medicine('1');"><img src="images/remove1.png"></a>  
                   </div>
                   <input type="hidden" name="extras" id="extras" value='0' />
                   <div id='div_1'>
                   </div>
                   <div class="clearfix"></div>
                   <div class="col-lg-4">
                       <label class="name"> Non Unit:</label>
                       <div class="select-holder">
<!--                            <label class="select-box-lbl chose">-->
                               <select class="chosen-select  form-control" name="non_unit_medicine_id" id="non_unit_medicine_id"> 
                                   <option value="">Medicines</option>
                                   <?php   if(!empty($NonUnitMedicinesList))
                                           {
                                               for($j=0;$j<count($NonUnitMedicinesList);$j++)
                                               {
                                                   $class = '';
                                                   for($i=0;$i<count($NonUnitMedicineArr);$i++)
                                                   {
                                                       if($NonUnitMedicineArr[$i] == $NonUnitMedicinesList[$j]['medicine_id'])
                                                           $class = 'selected="selected"';
                                                   }
                                                   echo '<option '.$class.' value="'.$NonUnitMedicinesList[$j]['medicine_id'].'" >'.$NonUnitMedicinesList[$j]['name'].'</option>';
                                               }
                                           }
                                   ?>
                               </select>
<!--                            </label>-->
                       </div>

                   </div>
                   <div class="col-lg-4">
                       <label class="name"></label>
                       <input type="text" name="non_unit_medicine_quantity" id="non_unit_medicine_quantity" class="form-control" value="" maxlength="20" />
                   </div>
                   <div class="col-lg-4">
                       <label class="name"></label>
                       <div class="clearfix"></div>
                       <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_non_unit_medicine('1');"><img src="images/add.png"></a> &nbsp;  
                       <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_non_unit_medicine('1');"><img src="images/remove1.png"></a> 
                   </div>
                   <input type="hidden" name="extras_2" id="extras_2" value='0' />
                   <div id='non_div_1'>
                   </div>
               </div>
               <div class="row margintop10">
                   <div class="col-lg-4">
                       <label class="name color-text">Consumables:</label>
                       <br/>
                   </div>
                   <div class="clearfix"></div>
                   <div class="col-lg-4">
                       <label class="name">Unit:</label>
                       <div class="select-holder">
<!--                            <label class="select-box-lbl chose">-->
                               <select class="chosen-select form-control" name="unit_consumable_id" id="unit_consumable_id"> 
                                   <option value="">Consumables</option>
                                   <?php   if(!empty($UnitConsumablesList))
                                           {
                                               for($i=0;$i<count($UnitConsumablesList);$i++)
                                               {
                                                   $class = '';
                                                   for($a=0;$a<count($UnitConsumbaleArr);$a++)
                                                   {
                                                       if($UnitConsumbaleArr[$a] == $UnitConsumablesList[$i]['consumable_id'])
                                                           $class = 'selected="selected"';
                                                   }
                                                   echo '<option '.$class.' value="'.$UnitConsumablesList[$i]['consumable_id'].'">'.$UnitConsumablesList[$i]['name'].'</option>';
                                               }
                                           }
                                   ?>
                               </select>
<!--                            </label>-->
                       </div>

                   </div>
                   <div class="col-lg-4">
                       <label class="name"></label>
                       <input type="text" name="unit_consumable_quantity" id="unit_consumable_quantity" class="form-control" value="" maxlength="20" />
                   </div>
                   <div class="col-lg-4">
                       <label class="name"></label>
                       <div class="clearfix"></div>
                       <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_unit_consumable('1');"><img src="images/add.png"></a> &nbsp;  
                       <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_unit_consumable('1');"><img src="images/remove1.png"></a>  
                   </div>
                   <input type="hidden" name="consumable_extras" id="consumable_extras" value='0' />
                   <div id='consumable_unit_div_1'>
                   </div>
                   <div class="clearfix"></div>
                   <div class="col-lg-4">
                       <label class="name"> Non Unit:</label>
                       <div class="select-holder">
<!--                            <label class="select-box-lbl chose">-->
                               <select class="chosen-select form-control" name="non_unit_consumable_id" id="non_unit_consumable_id"> 
                                   <option value="">Consumables</option>
                                   <?php   if(!empty($NonUnitConsumablesList))
                                           {
                                               for($j=0;$j<count($NonUnitConsumablesList);$j++)
                                               {
                                                   $class = '';
                                                   for($i=0;$i<count($NonUnitConsumbaleArr);$i++)
                                                   {
                                                       if($NonUnitConsumbaleArr[$i] == $NonUnitConsumablesList[$j]['consumable_id'])
                                                           $class = 'selected="selected"';
                                                   }
                                                   echo '<option '.$class.' value="'.$NonUnitConsumablesList[$j]['consumable_id'].'" >'.$NonUnitConsumablesList[$j]['name'].'</option>';
                                               }
                                           }
                                   ?>
                               </select>
<!--                            </label>-->
                       </div>

                   </div>
                   <div class="col-lg-4">
                       <label class="name"></label>
                       <input type="text" name="non_unit_consumable_quantity" id="non_unit_consumable_quantity" class="form-control" value="" maxlength="20" />
                   </div>
                   <div class="col-lg-4">
                       <label class="name"></label>
                       <div class="clearfix"></div>
                       <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_non_unit_consumable('1');"><img src="images/add.png"></a> &nbsp;  
                       <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_non_unit_consumable('1');"><img src="images/remove1.png"></a> 
                   </div>
                   <input type="hidden" name="non_unit_consumable_extras" id="non_unit_consumable_extras" value='0' />
                   <div id='non_unit_consumable_div_1'>
                   </div>
               </div>
           </div>
           <?php  } ?> 
            
            <div class="rounded-corner col-lg-12">
                <div class="color-text">Baseline:</div>
                <div class="row">
                  <div class="col-lg-12"> 
                  <div>
                      A <input type="radio" name="baseline" id="baseline" value="1"<?php if($_POST['baseline']=='1') { echo 'checked="checked"'; } else if(!empty($JobClosure['baseline']) && $JobClosure['baseline']=='1') { echo 'checked="checked"'; } ?> /> &nbsp; 
                      V <input type="radio" name="baseline" id="baseline" value="2"<?php if($_POST['baseline']=='2') { echo 'checked="checked"'; } else if(!empty($JobClosure['baseline']) && $JobClosure['baseline']=='2') { echo 'checked="checked"'; } ?> /> &nbsp; 
                      P <input type="radio" name="baseline" id="baseline" value="3"<?php if($_POST['baseline']=='3') { echo 'checked="checked"'; } else if(!empty($JobClosure['baseline']) && $JobClosure['baseline']=='3') { echo 'checked="checked"'; } ?> />&nbsp; 
                      U <input type="radio" name="baseline" id="baseline" value="4"<?php if($_POST['baseline']=='4') { echo 'checked="checked"'; } else if(!empty($JobClosure['baseline']) && $JobClosure['baseline']=='4') { echo 'checked="checked"'; } ?> />
                  </div>
                  
                  <table cellpadding="0" cellspacing="0" class="margintop10">
                  	<tr>
                    	<td class="color-text">Airway: &nbsp;</td>
                        <td>Open &nbsp;&nbsp;</td>
                        <td><input type="radio" name="airway" id="airway" value="1"<?php if($_POST['airway']=='1') { echo 'checked="checked"'; } else if(!empty($JobClosure['airway']) && $JobClosure['airway']=='1') { echo 'checked="checked"'; } ?> /> &nbsp; &nbsp; </td>
                        <td>Close &nbsp;&nbsp;</td>
                        <td><input type="radio" name="airway" id="airway" value="2"<?php if($_POST['airway']=='2') { echo 'checked="checked"'; } else if(!empty($JobClosure['airway']) && $JobClosure['airway']=='2') { echo 'checked="checked"'; } ?> /></td>
                    	<td></td>
                        <td></td>
                    </tr>
                    <tr>
                    	<td class="color-text">Breathing: &nbsp;</td>
                        <td>Present &nbsp;</td>
                        <td><input type="radio" name="breathing" id="breathing" value="1"<?php if($_POST['breathing']=='1') { echo 'checked="checked"'; } else if(!empty($JobClosure['breathing']) && $JobClosure['breathing']=='1') { echo 'checked="checked"'; } ?> /> &nbsp; &nbsp; </td>
                        <td>Compromised &nbsp;</td>
                        <td><input type="radio" name="breathing" id="breathing" value="2"<?php if($_POST['breathing']=='2') { echo 'checked="checked"'; } else if(!empty($JobClosure['breathing']) && $JobClosure['breathing']=='2') { echo 'checked="checked"'; } ?> />&nbsp; &nbsp; </td>
                    	<td>Absent &nbsp;</td>
                        <td><input type="radio" name="breathing" id="breathing" value="3"<?php if($_POST['breathing']=='3') { echo 'checked="checked"'; } else if(!empty($JobClosure['breathing']) && $JobClosure['breathing']=='3') { echo 'checked="checked"'; } ?> /> </td>
                    </tr>
                    <tr>
                    	<td class="color-text">Circulation: &nbsp;</td>
                        <td>Redial &nbsp;</td>
                        <td><input type="radio" name="circulation" id="circulation" value="1"<?php if($_POST['circulation']=='1') { echo 'checked="checked"'; } else if(!empty($JobClosure['circulation']) && $JobClosure['circulation']=='1') { echo 'checked="checked"'; } ?> /> &nbsp; &nbsp;</td>
                        <td>Present &nbsp;</td>
                        <td><input type="radio" name="circulation" id="circulation" value="2"<?php if($_POST['circulation']=='2') { echo 'checked="checked"'; } else if(!empty($JobClosure['circulation']) && $JobClosure['circulation']=='2') { echo 'checked="checked"'; } ?> /> &nbsp; &nbsp; </td>
                        <td>Absent &nbsp;</td>
                        <td><input type="radio" name="circulation" id="circulation" value="3"<?php if($_POST['circulation']=='3') { echo 'checked="checked"'; } else if(!empty($JobClosure['circulation']) && $JobClosure['circulation']=='3') { echo 'checked="checked"'; } ?> /></td>
                    </tr>
                  </table>
                  
                    
                  <div class="clearfix margintop20"></div>
                  <table cellpadding="3" cellspacing="0" class="table-baseline">
                      <tr>
                          <td>Temp (Core)</td>
                          <td style="width:150px;"><input type="text" class="job-input" name="temprature" id="temprature" value="<?php if(!empty($_POST['temprature'])) { echo $_POST['temprature']; } else if(!empty($JobClosure['temprature'])) { echo $JobClosure['temprature']; }  else { echo ""; } ?>" maxlength="5"  onkeyup="if (/[^0-9\.]/g.test(this.value)) this.value = this.value.replace(/[^0-9\.]/g,'')" onblur="return validate_baseline('temprature',this.value);" /></td>
                          <td>*F</td>
                          <td style="width:50px;"></td>
                          <td>TBSL:</td>
                          <td><input type="text" class="job-input" name="bsl" id="bsl" value="<?php if(!empty($_POST['bsl'])) { echo $_POST['bsl']; } else if(!empty($JobClosure['bsl'])) { echo $JobClosure['bsl']; }  else { echo ""; } ?>" maxlength="3" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" onblur="return validate_baseline('bsl',this.value);" /></td>
                          <td> mg/dl</td>
                      </tr>
                      <tr>
                          <td>Pulse: </td>
                          <td><input type="text" class="job-input" name="pulse" id="pulse" value="<?php if(!empty($_POST['pulse'])) { echo $_POST['pulse']; } else if(!empty($JobClosure['pulse'])) { echo $JobClosure['pulse']; }  else { echo ""; } ?>" maxlength="3" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" onblur="return validate_baseline('pulse',this.value);" /></td>
                          <td>/min</td>
                          <td style="width:50px;"></td>
                          <td>SpO2: </td>
                          <td><input type="text" class="job-input" name="spo2" id="spo2" value="<?php if(!empty($_POST['spo2'])) { echo $_POST['spo2']; } else if(!empty($JobClosure['spo2'])) { echo $JobClosure['spo2']; }  else { echo ""; } ?>" maxlength="3" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" onblur="return validate_baseline('spo2',this.value);" /></td>
                          <td> % </td>
                      </tr>
                      <tr>
                          <td>RR:</td>
                          <td><input type="text" class="job-input" name="rr" id="rr" value="<?php if(!empty($_POST['rr'])) { echo $_POST['rr']; } else if(!empty($JobClosure['rr'])) { echo $JobClosure['rr']; }  else { echo ""; } ?>" maxlength="2" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" onblur="return validate_baseline('rr',this.value);" /></td>
                          <td>/min</td>
                          <td style="width:50px;"></td>
                          <td>GCS Total:</td>
                          <td><input type="text" class="job-input" name="gcs_total" id="gcs_total" value="<?php if(!empty($_POST['gcs_total'])) { echo $_POST['gcs_total']; } else if(!empty($JobClosure['gcs_total'])) { echo $JobClosure['gcs_total']; }  else { echo ""; } ?>" maxlength="2" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" onblur="return validate_baseline('gcs_total',this.value);" /></td>
                          <td>/15</td>
                      </tr>
                      <tr>
                          <td>BP:</td>
                          <td>
                              <input type="text" class="job-input" name="high_bp" id="high_bp" value="<?php if(!empty($_POST['high_bp'])) { echo $_POST['high_bp']; } else if(!empty($JobClosure['high_bp'])) { echo $JobClosure['high_bp']; }  else { echo ""; } ?>" maxlength="3" style="width:45% !important;" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" onblur="return validate_baseline('high_bp',this.value);" /> /
                              <input type="text" class="job-input" name="low_bp" id="low_bp" value="<?php if(!empty($_POST['low_bp'])) { echo $_POST['low_bp']; } else if(!empty($JobClosure['low_bp'])) { echo $JobClosure['low_bp']; }  else { echo ""; } ?>" maxlength="3" style="width:45% !important;" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" onblur="return validate_baseline('low_bp',this.value);" />
                          </td>
                          <td>/MmHg</td>
                          <td style="width:50px;"></td>
                          <td colspan="2">Skin Perfusion: Normal <input type="radio" name="skin_perfusion" id="skin_perfusion" value="1"<?php if($_POST['skin_perfusion']=='1') { echo 'checked="checked"'; } else if(!empty($JobClosure['skin_perfusion']) && $JobClosure['skin_perfusion']=='1') { echo 'checked="checked"'; } ?> /> &nbsp; &nbsp; 
                              Abnormal:<input type="radio"  name="skin_perfusion" id="skin_perfusion" value="2"<?php if($_POST['skin_perfusion']=='2') { echo 'checked="checked"'; } else if(!empty($JobClosure['service_render']) && $JobClosure['skin_perfusion']=='2') { echo 'checked="checked"'; } ?> /></td>
                      </tr>
                  </table>
              </div>

              <div class="clearfix"></div>
              <div class="col-lg-12 margintop10">
                  <div class="text-holder">
                          <div class="row">
                              <textarea maxlength="200" name="summary_note" id="summary_note" placeholder="Patient care summary notes" onkeyup="if (/[^a-zA-Z0-9 ,-/()]/g.test(this.value)) this.value = this.value.replace(/[^a-zA-Z0-9 ,-/()]/g,'')"><?php if(!empty($_POST['summary_note'])) { echo $_POST['summary_note']; } else if(!empty($JobClosure['summary_note'])) { echo $JobClosure['summary_note']; }  else { echo ""; } ?></textarea>
                      </div>
                  </div>
                 </div>   
          </div> 
            </div>
           </div>
            <div class="clearfix"></div>
            <div class="col-lg-12 margintop10 text-right">
                  <input type="button" class="btn btn-primary" id="jobClosureSubmit" value="SUBMIT" onclick="return SubmitJobClosure();">        
            </div>  
        </form>
        <div class="clearfix"></div>
    <?php
    } 
}
?>