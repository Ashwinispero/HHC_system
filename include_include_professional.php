<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
require_once 'classes/functions.php';
$eventClass=new eventClass();

  
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";
    if($_POST['service_id'] && $_POST['service_id']!="undefined")
        $service_id=$_POST['service_id'];
    
    if($_POST['professionalKeyword'] && $_POST['professionalKeyword']!="undefined")
        $search_Value=$_POST['professionalKeyword'];
    else
        $search_Value=""; 
    
    if($_POST['availability'] && $_POST['availability']!="undefined")
        $availability=$_POST['availability'];
    else
        $availability=""; 
    
    if($_POST['Proflocation_id'] && $_POST['Proflocation_id']!="undefined")
        $location_id=$_POST['Proflocation_id'];
    else
        $location_id=""; 
    
    if($_POST['profes_event_id'] && $_POST['profes_event_id']!="undefined")
        $profEventID = $_POST['profes_event_id'];
    else
        $profEventID = $_REQUEST['event_id'];
    
    if($_POST['kmsliderfrom'] && $_POST['kmsliderfrom']!="undefined")
        $kmsliderfrom = $_POST['kmsliderfrom'];
    else
        $kmsliderfrom = "0";
    
    if($_POST['kmsliderto'] && $_POST['kmsliderto']!="undefined")
        $kmsliderto = $_POST['kmsliderto'];
    else
        $kmsliderto = "20";
        
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']='all';//PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['availability']=$availability;
    $recArgs['location_id']=$location_id;
    $recArgs['service_id']=$service_id;
    $recArgs['event_id']=$profEventID;
    $recArgs['kmsliderfrom']=$kmsliderfrom;
    $recArgs['kmsliderto']=$kmsliderto;
    $recArgs['employee_id']=$_SESSION['employee_id'];
    if($_POST['sort_order'])
        $order1=$_POST['sort_order'];
    else
        $order1='desc';    
    if($_POST['sort_order']=='asc')
    {
        $order='desc';
        $img = "<img src='images/sort_up.png' border='0'>";
    }
    else if($_POST['sort_order']=='desc')
    {
        $order='asc';
        $img = "<img src='images/sort_dwon.png' border='0'>";
    }
    else
    {
        $order='desc';
        $img = "<img src='images/sort_up.png' border='0'>";
    }
    if(isset($_POST['sort_field']))
    {
        $sort_variable=$_POST['sort_field'];
    }
    else
    {
        $sort_variable="";
    }
    if($_POST['sort_field']=='name')
        $img1 = $img;
    if($_POST['sort_field']=='professional_code')
        $img2 = $img;
    if($_POST['sort_field']=='qualification')
        $img3 = $img;
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='name' || $_POST['sort_field']=='professional_code' || $_POST['sort_field']=='qualification' ))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "service_professional_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
   // echo '<input type="hidden" name="profes_event_id" id="profes_event_id" value="'.$profEventID.'" >';
    $recListResponse= $eventClass->GetServiceProfessionals($recArgs);
     //print_r($recListResponse);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];
    if($recListCount > 0)
    {
        $paginationCount=getAjaxPagination($recListCount);
    }
    ?>
<form method="post" name="professionalForm_<?php echo $service_id;?>" id="professionalForm_<?php echo $service_id;?>" action="event_ajax_process.php?action=submitProfessional&service_id=<?php echo $service_id;?>" >
    <?php
    echo '<input type="hidden" name="profes_event_id" id="profes_event_id" value="'.$profEventID.'" >';
    
    if(!$recListCount)
        echo '<center><br><br><h1 class="messageText">No professional/vendor found related to your search, please try again.</h1></center>';
    if($recListCount)
    {
        
       // print_r($countProfarray);
        

        if($recListCount==count($countProfarray))
        {
             $SelectAllCheckBoxes ="1";
        }
        else 
        {
             $SelectAllCheckBoxes ="2";
        }
    ?>
    
       <div id="professionalContent" class="mCustomScrollbar profeCOntent" style="height:250px !important;">
        <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
              <tr>
                    <th width="2%"><input type="checkbox" name="check_all_<?php echo $service_id;?>" id="selectall_<?php echo $service_id;?>"  onclick="ChangeAllCheckboxes('<?php echo $service_id;?>');" /></th>
                    <th width="10%">Prof Code</th>
                    <th width="18%">Name</th>                
                    <th width="13%">Availability</th>
                    <th width="10%">Set From</th>
                    <th width="14%">Distance (km)</th>
                    <th width="27%">Location</th>
                    <th width="5%">View</th>
              </tr>
            </thead>
            <tbody>
                
    <?php 
            echo '<input type="hidden" name="prof_service_id_'.$service_id.'" id="prof_service_id_'.$service_id.'" value="'.$service_id.'">';
           
          // print_r($recList); 
            
            $select_eventReq = "select event_requirement_id from sp_event_requirements where event_id = '".$profEventID."' and service_id = '".$service_id."' ";
            $datarequirement = $db->fetch_array($db->query($select_eventReq));
                
            $selectedPlan = "select plan_of_care_id,service_date,service_date_to,start_date,end_date from sp_event_plan_of_care where event_requirement_id = '".$datarequirement['event_requirement_id']."' ";
            $planofcareEx = $db->fetch_all_array($selectedPlan);
            foreach($planofcareEx as $key=>$selectedcare)
            {
                $selectedservice_date = $selectedcare['service_date'];
                $selectedservice_date_to = $selectedcare['service_date_to'];
                $selectedstart_date = $selectedcare['start_date'];
                $selectedtend_date = $selectedcare['end_date'];
                $daterange = getDatesFromRange( $selectedservice_date, $selectedservice_date_to);
                //print_r($daterange);
                $selDates = implode(",",$daterange);
                //$timerange = 
                $newdates .= $selDates.',';
                
            }
            $finaldatearray =  rtrim($newdates,',');
            $alldates = explode(",",$finaldatearray);
            //print_r($alldates);
        foreach ($recList as $recListKey => $recListValue) 
        {
            $countProfarray =array();
            if($profEventID)
            {
                

                $selectprof_existing = "select professional_vender_id from sp_event_professional where event_id = '".$profEventID."' and event_requirement_id = '".$datarequirement['event_requirement_id']."' ";
                $allRequiremprof = $db->fetch_all_array($selectprof_existing);
                foreach($allRequiremprof as $key=>$valAllRequirements)
                {
                    $countProfarray[] = $valAllRequirements['professional_vender_id'];
                }
            }
            
            
              
            $j=0; 
            $service_professional_id=$recListValue['service_professional_id']; 
            $selectStatus = "select service_closed, event_requirement_id, plan_of_care_id,event_id from sp_event_professional where professional_vender_id = '".$service_professional_id."' and service_closed = 'N'  ";
            if(mysql_num_rows($db->query($selectStatus)))
            {
                $valStatus = $db->fetch_array($db->query($selectStatus));
                
              
                $plancareArr['event_requirement_id'] = $valStatus['event_requirement_id'];
                $plancareArr['event_id'] = $valStatus['event_id'];
                $recPlanCare= $eventClass->planofcareRecords($plancareArr);
                $valServicedetail = $recPlanCare['data'];
                //print_r($valServicedetail); 
                
                $EveArr['event_id'] = $valStatus['event_id'];
                $GetCaller=$eventClass->GetEventCaller($EveArr);
                $Patiarr['patient_id']=$GetCaller['patient_id'];
                $ValpatientSummary=$eventClass->GetPatientById($Patiarr);
                
                $busyStatus_old = '<a href="javascript:void(0);" class="status busy">Scheduled
                                <span>
                                    <img class="callout" src="images/callout_black.png" />
                                    <div>Patient Name: '.$ValpatientSummary['name'].'</div>
                                    <div> Location: '.$ValpatientSummary['locationNm'].'</div>
                                    <div>Date: '.$valServicedetail[$j]['service_date'].'</div>
                                    <div>Time: '.$valServicedetail[$j]['start_date'].' to '.$valServicedetail[$j]['end_date'].'</div>
                                    <div>Recommended Service: '.$valServicedetail[$j]['service_title'].'</div>
                                </span>
                                </a>';
                $isfound = 'no';  
                $valallProf = $db->fetch_all_array($selectStatus);
                foreach($valallProf as $key=>$allProfAssign)
                {
                    $plan_of_care_id = $allProfAssign['plan_of_care_id'];
                    $selectPlanCare = "select plan_of_care_id,service_date,service_date_to,start_date,end_date from sp_event_plan_of_care where plan_of_care_id = '".$plan_of_care_id."' ";
                    $valPlan = $db->fetch_array($db->query($selectPlanCare));
                    $service_date = $valPlan['service_date'];
                    $service_date_to = $valPlan['service_date_to'];
                    $prevstart_date = $valPlan['start_date'];
                    $prevend_date = $valPlan['end_date'];
                    if (in_array($service_date, $alldates, true)) {
                        //echo $service_date;
                        $selectedCHeckPlan = "select plan_of_care_id,start_date,end_date from sp_event_plan_of_care where event_requirement_id = '".$datarequirement['event_requirement_id']."' and service_date = '".$service_date."' ";
                        $yesfind = $db->fetch_array($db->query($selectedCHeckPlan));
                        $selfromTime = $yesfind['start_date'];
                        $seltoTime = $yesfind['end_date'];
                        $fromtime = date("H:i", strtotime($selfromTime));
                        $totime = date("H:i", strtotime($seltoTime));
                        $prevStarttime = date("H:i", strtotime($prevstart_date));
                        $prevTotime = date("H:i", strtotime($prevend_date));    
                        if($fromtime<=$prevTotime)
                        {
                            
                            if($totime>=$prevStarttime)
                            {
                                $isfound = 'yes'; 
                            }
                            else
                                $isfound = 'no'; 
                                
                        }
                        else
                            $isfound = 'no'; 
                    }
                    //else
                        //$isfound = 'no';
                   
                }
                
                
                if($isfound == 'yes')
                {
                    $busyStatus = '<a href="javascript:void(0);" title="View Busy Schedule" onclick="ViewbusyScheduled('.$service_professional_id.')"; data-toggle="tooltip" data-placement="top" title="View Busy Schedule"><span class="status busy">Scheduled</span></a>';
                    //$busyStatus .='-yes';
                }
                else
                    $busyStatus = '<span class="available">Not Scheduled</span>';
            }
            else
                $busyStatus = '<span class="available">Not Scheduled</span>';
            //echo $service_professional_id;
                $class = '';
                //echo count($countProfarray);
                for($i=0;$i<count($countProfarray);$i++)
                {
                    if($countProfarray[$i] == $service_professional_id)
                        $class = "checked";
                }
                
                if($recListValue['set_location'] == '1')
                    $setLocation = 'Home';
                else
                    $setLocation = 'Work';
            echo '<tr>
                    <td> <input '.$class.' id="professionals_'.$service_id.'" name="professionals_'.$service_id.'[]" value="'.$service_professional_id.'" type="checkbox" class="case_'.$service_id.'"  /> </td>
                    <td>'.$recListValue['professional_code'].'</td>
                    <td>';
                    if(!empty($recListValue['name'])) { echo $recListValue['name']." "; }  if(!empty($recListValue['first_name'])) { echo $recListValue['first_name']." "; } if(!empty($recListValue['middle_name'])) { echo $recListValue['middle_name']; }
                    echo '</td>                    
                    <td>'.$busyStatus.'</td>
                    <td>'.$setLocation.'</td>
                    <td>'.round($recListValue['distanceKM'],2).'</td>
                    <td>'.$recListValue['location'].'</td>                
                    <td><a href="javascript:void(0);" title="View Work Scheduled" onclick="ViewScheduled('.$service_professional_id.')"; data-toggle="tooltip" data-placement="top" title="View Work Scheduled">
                            <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span>
                        </a>
                    </td>                
                </tr>'; 
                    
        }            
        ?>
                        
            </tbody>
          </table>
       </div>
        
        <div class="form-group" style="margin-top: 15px !important;">
            <label for="inputPassword3" class="col-sm-3 control-label"></label>
            <div class="col-sm-9 text-right">
              <input type="button" class="btn btn-primary" id="ProfSubmit" value="SUBMIT" onclick="return SubmitFindProfessional(<?php echo $service_id;?>);">
            </div>
        </div>
        
        <?php
        } 
       /* if($paginationCount)
        {
        echo '<div class="clearfix"></div>';
        echo '<div class="col-lg-12 paddingR0 text-right">
                <table cellspacing="0" cellpadding="0" align="right">
                    <tbody>
                        <tr>
                            <td>Show</td>
                            <td style="width:10px;"></td>
                            <td class="pagination-dropdown">
                                <label class="select-box-lbl">
                                    <select class="form-control" name="show_records" onchange="changePagination(\'eventLogListing\',\'include_event_log.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
                                    for($s=0;$s<count($GLOBALS['show_records_arr']);$s++)
                                    {
                                        if($_SESSION['per_page']==$GLOBALS['show_records_arr'][$s] || $_SESSION['per_page']==$GLOBALS["records_all"])
                                            echo '<option selected="selected" value="'.$GLOBALS['show_records_arr'][$s].'">'.$GLOBALS['show_records_arr'][$s].' Records</option>';
                                        else
                                            echo '<option value="'.$GLOBALS['show_records_arr'][$s].'">'.$GLOBALS['show_records_arr'][$s].' Records</option>';
                                    }
                                echo'</select>
                                </label>
                            </td>
                            <td style="width:10px;"></td>';
        if($recListCount<($start+PAGE_PER_NO))
            $pagesOf=($start+1).'-'.($recListCount).' of '.$recListCount;
        else
            $pagesOf=($start+1).'-'.($start+PAGE_PER_NO).' of '.$recListCount;
                        echo '<td>'.$pagesOf.'</td>';
        if($pageId>1)
        {
            echo '
                <td style="width:10px;"></td>
                <td align="right" onclick="changePagination(\'eventLogListing\',\'include_event_log.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
        }
        else
        {
            echo '
                <td style="width:10px;"></td>
                <td align="right" valign="middle"><input type="button" class="btn" value="<"></td>';
        }
        if($pageId!=($paginationCount))
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input onclick="changePagination(\'eventLogListing\',\'include_event_log.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
        }
        else
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input type="button" class="btn" value=">"></td>';
        }
        echo '          </tr>
                    </tbody>
                </table>
            </div>';
    }*/
    ?>
        
<!--          <div class="text-right">
            <nav>
              <ul class="pagination pagination-sm">
                <li><a href="#">Previous</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">Next</a></li>
              </ul>
            </nav>
          </div>-->
</form>    
<?php
}?>