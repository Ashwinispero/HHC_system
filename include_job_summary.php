<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass(); 
require_once 'classes/professionalsClass.php';
$professionalsClass=new professionalsClass();
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";    
    $recProf['pageIndex']=$pageId;
    $recProf['pageSize']=PAGE_PER_NO;
    $recProf['employee_id']=$_SESSION['employee_id'];
    $recProf['event_id']=$_REQUEST['event_id'];
    $recListResponseJob= $eventClass->SelectedPlanCareServices($recProf);
    
//    echo '<pre>';
//    print_r($recListResponseJob);
//    echo '</pre>';
    
    
    $recListJob=$recListResponseJob['data'];
    //print_r($recListJob);
    $recJobListCount=$recListResponseJob['count']; 
    if($recJobListCount > 0)
    {
        $paginationCount=getAjaxPagination($recJobListCount);
    }
    if(!$recJobListCount)
        echo '<center><br><br><h1 class="messageText">No job summary found related to your search, please try again.</h1></center>';
    if($recJobListCount)
    {
    echo '<h4 class="text-center title-services">DESIGN JOB SUMMARY</h4>';
    ?>    
    <div id="parentHorizontalTab1" class="tabholder">
    <ul class="resp-tabs-list hor_2">
        <?php
        foreach ($recListJob as $recListKey => $recJobValue) 
        {
            echo '<li>'.$recJobValue['service_title'].'</li>';
        }
        ?>
    </ul>
    <div class="resp-tabs-container hor_2">
        <?php
        foreach($recListJob as $key=>$recJobValue)
        {
        ?>
            <div class="paddingTB25">
                <?php 
                    $serviceID=$recJobValue['service_id'];
                    // Getting Event Requirement
                    $GetEventReqSql="SELECT distinct service_id FROM sp_event_requirements WHERE event_id='".$_REQUEST['event_id']."' AND service_id='".$serviceID."'";
                    $EventRequirement=$db->fetch_all_array($GetEventReqSql);
                    if(!empty($EventRequirement))
                    {
                        ?>
                            <form name="jobSummaryDiv_<?php echo $serviceID;?>" id="jobSummaryDiv_<?php echo $serviceID;?>" method="post" action="event_ajax_process.php?action=SubmitJobSum&services=<?php echo $serviceID;?>">
                                <table  class="table table-bordered-job" cellspacing="0" width="100%">
                                    <thead>
                                      <tr>
                                        <th width="10%">Prof. Id</th>
                                        <th width="15%">Prof. Name</th>
                                        <th width="20%">Recommended Service</th>
                                        <th width="26%">Date Time</th>
                                        <th width="18%">Reporting Instructions</th>
                                      </tr>
                                    </thead>
                                        <tbody>  
                        <?php
                        foreach ($EventRequirement as $key=>$valEachService)
                        {
                            $GetEventReqSql1="SELECT event_requirement_id,event_id,service_id FROM sp_event_requirements WHERE   event_id='".$_REQUEST['event_id']."' AND service_id='".$valEachService['service_id']."'";
                            $ValRequirement=$db->fetch_array($db->query($GetEventReqSql1));
                            // Getting All Assigned Professional List 
                            $GetEventProfessionalSql="SELECT event_professional_id,event_id,event_requirement_id,professional_vender_id FROM sp_event_professional WHERE event_id='".$ValRequirement['event_id']."' AND event_requirement_id='".$ValRequirement['event_requirement_id']."' AND service_id='".$valEachService['service_id']."'";
                            if(mysql_num_rows($db->query($GetEventProfessionalSql)))
                            {
                                $EventProfessional=$db->fetch_all_array($GetEventProfessionalSql);
                                if(!empty($EventProfessional))
                                {  $profeServIDs = '';
                                    for($e=0;$e<count($EventProfessional);$e++)
                                    {
                                        // Getting Professional Details 
                                        $ProfArr['service_professional_id']=$EventProfessional[$e]['professional_vender_id'];
                                        $ProfessionalDtls=$professionalsClass->GetProfessionalById($ProfArr);
                                        
                                        // Getting Job Summary Description By Professional vender
                                        
                                        $GetJobSummarySql="SELECT reporting_instruction FROM sp_event_job_summary WHERE event_id='".$ValRequirement['event_id']."' AND service_id='".$ValRequirement['service_id']."' AND professional_vender_id='".$EventProfessional[$e]['professional_vender_id']."'";
                                        $GetJobSummary=$db->fetch_array($db->query($GetJobSummarySql));
                                        $service_date = '';
                                        $recommomded_service='';
                                        $serviceTime='';
                                        // Getting Plan of care records 
                                        
                                        $selected_Services = "SELECT er.event_requirement_id,er.sub_service_id,poc.service_date,poc.service_date_to,poc.start_date,poc.end_date FROM "
                                                                . " sp_event_requirements as er LEFT JOIN sp_event_plan_of_care as poc ON er.event_requirement_id = poc.event_requirement_id "
                                                                . " where er.service_id = '".$ValRequirement['service_id']."' and er.event_id = '".$ValRequirement['event_id']."' and er.status='1' ";
                                        
                                        $ptr_selSertvices = $db->fetch_all_array($selected_Services);
                                        
                                        echo '<tr>
                                                   <input type="hidden" name="event_id" id="event_id" value="'.$ValRequirement['event_id'].'" />
                                                   <input type="hidden" name="login_user_id" id="login_user_id" value="'.$recProf['employee_id'].'" />
                                                   <input type="hidden" name="event_professional_id_'.$serviceID.'[]" id="event_professional_id_'.$serviceID.'" value="'.$EventProfessional[$e]['event_professional_id'].'" />
                                                       <td>'.$ProfessionalDtls['professional_code'].'</td>
                                                       <td>';
                                                        if(!empty($ProfessionalDtls['name'])) { echo $ProfessionalDtls['name']." "; }  if(!empty($ProfessionalDtls['first_name'])) { echo $ProfessionalDtls['first_name']." "; } if(!empty($ProfessionalDtls['middle_name'])) { echo $ProfessionalDtls['middle_name']; }
                                                        echo '</td>';
                                                       $srno=0;
                                                       foreach($ptr_selSertvices as $key=>$valSelcServ)
                                                       {
                                                           // Getting Recommonded Service Name 
                                                           $selectTitle = "select recommomded_service from sp_sub_services where sub_service_id = '".$valSelcServ['sub_service_id']."'";
                                                           $valRecService = $db->fetch_array($db->query($selectTitle));
                                                           $recommomded_service=$valRecService['recommomded_service'];
                                                           
                                                            if(date('d-m-Y',strtotime($valSelcServ['service_date']))==date('d-m-Y',strtotime($valSelcServ['service_date_to'])))
                                                                $service_date= date('d-m-Y',strtotime($valSelcServ['service_date']));
                                                            else 
                                                                $service_date=date('d-m-Y',strtotime($valSelcServ['service_date'])).' to '.date('d-m-Y',strtotime($valSelcServ['service_date_to']));
                                                            
                                                            $serviceTime=$valSelcServ['start_date'].' to '.$valSelcServ['end_date'];
                                                            echo '<tr><td></td><td></td>';
                                                            echo '<td>'.rtrim($recommomded_service).'</td>';
                                                            echo '<td>'.rtrim($service_date)."<br/>(".rtrim($serviceTime).")".'</td>';
                                                            if($srno==0) 
                                                            { 
                                                                echo '<td><textarea class="form-control" name="reporting_instruction_'.$serviceID.'_'.$EventProfessional[$e]['event_professional_id'].'" id="reporting_instruction_'.$serviceID.'_'.$EventProfessional[$e]['event_professional_id'].'">'.$GetJobSummary['reporting_instruction'].'</textarea></td>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<td></td>';
                                                            }
                                                            echo '</td></tr>';
                                                            unset($recommomded_service);
                                                            unset($service_date);
                                                            unset($serviceTime);
                                                            $srno++;
                                                       }
                                        echo '</tr>';
                                        $profeServIDs .= $EventProfessional[$e]['event_professional_id'].':,';
                                         //unset Array Element
                                        unset($ProfArr);
                                    }
                                    $allProfIDs = rtrim($profeServIDs,':,');
                                    echo '<input type="hidden" name="profeServID_'.$serviceID.'" id="profeServID_'.$serviceID.'" value="'.$allProfIDs.'">';
                                }
                            }
                            
                        } 
                        
                        ?>
                                            <tr>
                                                <td colspan="6" class="text-right">
                                                    <input type="hidden" name="clicked_btn_type_<?php echo $serviceID;?>" id="clicked_btn_type_<?php echo $serviceID;?>"  />
                                                    <input type="button" class="btn btn-small btn_type" name="sms" id="sms" value="SMS" onclick="return SubmitJobSummary('1',<?php echo $serviceID;?>);"/> 
                                                    <input type="button" class="btn btn-small btn_type" name="email" id="email" value="Email" onclick="return SubmitJobSummary('2',<?php echo $serviceID;?>);" /> 
                                                    <input type="button" class="btn btn-small btn_type" name="call" id="call" value="Call" onclick="return SubmitJobSummary('3',<?php echo $serviceID;?>);" />
                                                </td>
                                            </tr>   
                                       </tbody>
                                 </table>
                           </form>
                        <?php 
                    }
                ?>
            </div>
    <?php 
        } 
    ?>
        </div>
    </div>   
    <?php
    } 
}
?>