<?php   require_once 'inc_classes.php';
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";
        include "classes/adminClass.php";
        $adminClass = new adminClass();
        require_once "classes/eventClass.php";
        $eventClass = new eventClass();
        include "classes/commonClass.php";
        $commonClass= new commonClass();
        require_once "classes/config.php";

        include "classes/professionalsClass.php";
        $professionalsClass = new professionalsClass();

        require_once 'classes/avayaClass.php';
$avayaClass=new avayaClass();
?>
<?php
    if($_REQUEST['action']=='CheckLogin')
    {
        $UserName=$db->escape($_REQUEST['email_id']);
        $Password=md5($db->escape($_REQUEST['password']));
        $extention_id=$db->escape($_REQUEST['extention_id']);
        
        $checkExist="SELECT employee_id,hospital_id,employee_code,first_name,CONCAT(first_name, ' ', name) AS employee_name,type,email_id,password,status,last_login,avaya_agentid 
                    FROM sp_employees 
                    WHERE email_id = '" . $UserName . "' AND password = '" . $Password . "' ";
       // echo $checkExist;
        $Loginresult=$db->query($checkExist);
        if(mysql_num_rows($db->query($checkExist)))
        { 
            $_SESSION['eventAccess'] = '';
            $EmployeeLog=$db->fetch_array($Loginresult);
            if($EmployeeLog['status']=='2' || $EmployeeLog['status']=='3')
            {
                echo "inactive";
                exit;
            }
            else 
            {                
                $_SESSION['employee_id']          = $EmployeeLog['employee_id'];
                $_SESSION['employee_type']        = $EmployeeLog['type'];
                $_SESSION['employee_hospital_id'] = $EmployeeLog['hospital_id'];
                $_SESSION['emp_nm']               = $EmployeeLog['employee_name'];
                $_SESSION['first_name']           = $EmployeeLog['first_name'];
                $_SESSION['avaya_agentid']        = $EmployeeLog['avaya_agentid'];

                //After login user bydefault in pause mode
                $_SESSION['mode_status']          = '1';
                $unique_id = time();
                $avaya_data = array(
                    
                    'ext_no'=> $_SESSION['avaya_agentid'],
                    'CallUniqueID' => $unique_id,
                    'user_id' => $_SESSION['employee_id'],
                    'mode_status' => '1',
                    'date_time' => date('Y-m-d H:i:s'),
                    'is_deleted' => '0'
                );
                $avaya_data_insert =$avayaClass->insert_mode_status($avaya_data);  
               if($EmployeeLog['type'] == '2')
                {
                   
                    $remoteIP = $_SERVER['REMOTE_ADDR'];
                    //echo $remoteIP;
                    //121.242.76.226 - dinanath
                    //123.201.118.230 - hindavi
                    //106.221.129.87 - Clinic
                //     $cookie_SpNm = 'SpCkStNpDt';
                //   $cookieval = '';
                //     if(isset($_COOKIE[$cookie_SpNm])) {
                //         $cookie = 'yes';
                //         $cookieval = base64_decode($_COOKIE[$cookie_SpNm]);
                //     }
                    //$select_existip = "select hosp_ip_id from sp_hospital_ips where hospital_ip = '".$remoteIP."' ";
                    //if(mysql_num_rows($db->query($select_existip)))
                   
                    //if($cookieval == 'Spero@cookie123*#') 
                    // echo $cookieval; 
                    //$select_existip = "select hosp_ip_id from sp_hospital_ips where hospital_ip = '".$remoteIP."' ";
                    //$mysqlrows = mysql_num_rows($db->query($select_existip));
                   // if($cookieval == 'Spero@cookie123*#' ||  $mysqlrows!= '0')
                   // {
                    //}
                    //if($remoteIP == '123.201.118.230' || $remoteIP == '106.221.138.45' || $remoteIP == '192.168.12.195' || $remoteIP == '192.168.12.186' || $remoteIP == '121.242.76.226' || $remoteIP == '106.221.132.214' || $remoteIP == '172.28.52.3' || $remoteIP == '172.28.52.2' || $remoteIP == '122.182.19.2' || $remoteIP == '123.201.118.230')
                    //{
                        if($EmployeeLog['hospital_id'] == '1')
                            $_SESSION['eventAccess'] = 'All';
                        else
                        {
                            $_SESSION['eventAccess'] = 'No';
                        }
                        $updateData['last_login']=date('Y-m-d H:i:s');
                        $updateData['is_login']='0';
                        $db->query_update('sp_employees', $updateData, "employee_id='".$EmployeeLog['employee_id']."'");
                        echo "success";
                       // $data['msg']="success";
                       // $data['form_url']=$form_url;
                       // echo json_encode($data);
                        exit;
                   // }
                    //else
                        //echo 'IPWrong';
                }
                else
                {
                    $updateData['last_login']=date('Y-m-d H:i:s');
                    $db->query_update('sp_employees', $updateData, "employee_id='".$EmployeeLog['employee_id']."'");
                    echo "success";
                    exit;
                }
            }
        
        }
        else
        {
            echo "incorrect";
            exit;
        }     
    }
    else if($_REQUEST['action'] == 'ChangeSubServices')
    {
        $service_ids = $_REQUEST['service_ids'];
        $explodeIds = explode(',',$service_ids);
        //echo count($explodeIds);
        //print_r($explodeIds);
        for($i=0;$i<count($explodeIds);$i++)
        {
            $arr['service_id'] = $explodeIds[$i];
            $recordServices = $adminClass->GetServiceById($arr);
        ?>
                <div class="form-group" id="ServiceDiv_<?php echo $arr['service_id'];?>">
                    <div class="col-sm-12">
                      <!--<label class="select-box-lbl">-->
                          <select class="form-control" id="sub_service_id_multiselect_<?php echo $arr['service_id'];?>" name="sub_service_id_multiselect_<?php echo $arr['service_id'];?>[]" multiple="multiple">
                          <!--<option value="">Select <?php echo $recordServices['service_title'];?></option>-->
                            <?php
                                $selectServces = "select sub_service_id,recommomded_service from sp_sub_services where status = '1' and service_id = '".$arr['service_id']."' order by recommomded_service asc  ";
                                $dataServices = $db->fetch_all_array($selectServces);
                                foreach($dataServices as $key=>$valServices)
                                {
                                    echo '<option value="'.$valServices['sub_service_id'].'">'.$valServices['recommomded_service'].'</option>';
                                }
                            ?>
                        </select>
                      <!--</label>-->
                    </div>
                </div>
        <?php
        echo 'sepratedTitle--';
        echo $recordServices['service_title'];
        }
    }
    else if($_REQUEST['action']=='logout')
    {
        $updateData['is_login']='1';
        $db->query_update('sp_employees', $updateData, "employee_id='".$_SESSION['employee_id']."'");

        $employee_id = $_SESSION['employee_id'];
        $avaya_agentid = $_SESSION['avaya_agentid'] ;
        $unique_id = time();
        $avaya_data = array(
            
            'ext_no'=> $avaya_agentid,
            'CallUniqueID' => $unique_id,
            'user_id' => $employee_id,
            'mode_status' => '1',
            'date_time' => date('Y-m-d H:i:s'),
            'is_deleted' => '0'
        );
        $avaya_data_insert =$avayaClass->insert_mode_status($avaya_data);       
        $user = $_SESSION['first_name'];
        $form_url =  "http://183.87.122.153/API/Logout.php?user=".$user;
        $data_to_post = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $form_url);
        curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
        $result = curl_exec($curl);
        curl_close($curl);
       // echo $result;
       //var_dump($result);die();
        session_destroy();
            ?>
                <script language="javascript" type="text/javascript">
                    window.location ="index.php";
                </script>
            <?php   
     } 
     else if($_REQUEST['action']=='saveEventDetails')
     {
         $main_event_id = $_REQUEST['main_event_id'];
         $purpose_event_id = $_REQUEST['purpose_event_id'];
         $purpose_id = $_REQUEST['purpose_id'];
         $caller_id = $_REQUEST['caller_id'];
         $type = $_REQUEST['type'];
         
         if($purpose_id == '4' || $purpose_id == '5')
         {
            $update_event = "update sp_events set purpose_event_id = '".$purpose_event_id."' where event_id = '".$main_event_id."'";
            $db->query($update_event);
            if($purpose_id == '5')
            {
            /* ------------- Insert Follow Event -----------*/
                $select_exist = "select follow_up_id from sp_event_follow_up where event_id = '".$main_event_id."' and follow_event_id = '".$purpose_event_id."' ";
                if(mysql_num_rows($db->query($select_exist)) == 0)
                {
                    $InserFollow['event_id'] = $main_event_id;
                    $InserFollow['follow_event_id'] = $purpose_event_id;
                    $InserFollow['follow_caller_id'] = $caller_id;
                    $InserFollow['date'] = date('Y-m-d H:i:s');
                    $InserFollow['added_by'] = $_SESSION['employee_id'];
                    $InserFollow['added_date'] = date('Y-m-d H:i:s');
                    $db->query_insert('sp_event_follow_up',$InserFollow);
                }   
            /* ------------- Complete Insert Follow Event -----------*/   
            }
            else if($purpose_id == '4')
            {
                $select_exist = "select consultant_call_id from sp_event_consultant_call where event_id = '".$main_event_id."' and consultant_event_id = '".$purpose_event_id."' ";
                if(mysql_num_rows($db->query($select_exist)) == 0)
                {
                    $InserFollow['event_id'] = $main_event_id;
                    $InserFollow['consultant_event_id'] = $purpose_event_id;
                    $InserFollow['consultant_caller_id'] = $caller_id;
                    $InserFollow['added_by'] = $_SESSION['employee_id'];
                    $InserFollow['added_date'] = date('Y-m-d H:i:s');
                    $db->query_insert('sp_event_consultant_call',$InserFollow);
                }   
            }
            $update_event2 = "update sp_events set event_status = '5',purpose_event_id = '".$main_event_id."' where event_id = '".$purpose_event_id."'";
            $db->query($update_event2);
         }
         
     }
     else if($_REQUEST['action'] == 'AddMorePlan')
     {
        $event_requirement_id = $_REQUEST['event_requirement_id'];
        $event_service_type = $_REQUEST['event_service_type'];
        $i = $_REQUEST['curr_div'];  
        $j = $i+1;
        echo '<div id="div_'.$i.'_'.$event_requirement_id.'">
                <div class="main-row"> 
                    <div style="width:13%;display:inline-block;padding-right:1%;padding:4px;"> &nbsp; </div>
                    <div style="width:24%;display:inline-block;padding-right:1%;padding:4px;"> &nbsp; </div>
                    <div style="width:27%;display:inline-block;padding-right:1%;padding:4px;">
                    <div class="pull-left text-center" style="width:49%;display:inline-block;padding-right:2%;">
                        <input type="text"  value="" name="eve_from_date_'.$i.'_'.$event_requirement_id.'" id="eve_from_date_'.$i.'_'.$event_requirement_id.'" class="form-control datepicker_eve_'.$i.'" >
                    </div>
                    <div class="pull-left text-center" style="width:49%;display:inline-block;padding-right:2%;">
                        <input type="text"  value="" name="eve_to_date_'.$i.'_'.$event_requirement_id.'" id="eve_to_date_'.$i.'_'.$event_requirement_id.'" class="form-control datepicker_eve_to_'.$i.'" >
                    </div>
                    <div class="pull-left" style="width:4%;display:inline-block;padding-right:2%;"> </div> 
                    </div> 
                    <div style="width:27%;display:inline-block;padding-right:1%;">
                        <div class="datepairExample_'.$i.'">
                            <div class="pull-left" style="width:40%;display:inline-block;padding-right:2%;padding:4px;">
                                    <label style="display:block;">
                                            <input value="" name="starttime_'.$i.'_'.$event_requirement_id.'" id="starttime_'.$i.'_'.$event_requirement_id.'" type="text" class="form-control time start validate_time" />
                                    </label>
                            </div>
                            <div class="pull-left" style="width:40%;display:inline-block;padding-right:2%;padding:4px;">           
                                    <label style="display:block;">
                                            <input value="" name="endtime_'.$i.'_'.$event_requirement_id.'" id="endtime_'.$i.'_'.$event_requirement_id.'"  type="text" class="form-control time end validate_time" />
                                    </label>                
                            </div>
                            <div class="pull-left" style="width:10%;display:inline-block;padding-right:2%;padding:4px;"> &nbsp; </div>
                            <div class="pull-left" style="width:10%;display:inline-block;padding-right:2%;padding:4px;"> &nbsp; </div> 
                        </div>
                    </div>    
                    <input type="hidden" name="hidden_costService_'.$i.'_'.$event_requirement_id.'" id="hidden_costService_'.$i.'_'.$event_requirement_id.'" value="" > 
                    <div style="width:7%;display:inline-block;vertical-align:top;padding:4px;" id="costService_'.$i.'_'.$event_requirement_id.'">';
                    if($event_service_type=='2') { echo '<input type="text" name="other_service_cost_'.$i.'_'.$event_requirement_id.'" id="other_service_cost_'.$i.'_'.$event_requirement_id.'" class="form-control number" onkeyup="javascript:return CalculateTotEstCost(this,this.value);" maxlength="5" />'; } else { echo '&nbsp;'; }
                    echo '</div>
                  </div> 
               </div>
               <div id="div_'.$j.'_'.$event_requirement_id.'"><table>
               <tr><td colspan="5"> </td></tr></table></div>';
       
     }
	 else if($_REQUEST['action'] == 'AddMorePayments')
     {
        $event_requirement_id = $_REQUEST['event_requirement_id'];
        $event_service_type = $_REQUEST['event_service_type'];
        $i = $_REQUEST['curr_div'];  
        $j = $i+1;
		//echo "<script type='text/javascript'>alert('AddMorePayments AJAX!')</script>";
        echo '<div id="div_'.$i.'_'.$event_requirement_id.'">
                <div class="main-row"> 
                    
                    <div style="width:24%;display:inline-block;padding-right:1%;padding:4px;"> &nbsp; </div>
                    <div style="width:27%;display:inline-block;padding-right:1%;padding:4px;">
                    <div class="pull-left text-center" style="width:49%;display:inline-block;padding-right:2%;">
                        <input type="text"  value="" name="eve_from_date_'.$i.'_'.$event_requirement_id.'" id="eve_from_date_'.$i.'_'.$event_requirement_id.'" class="form-control datepicker_eve_'.$i.'" >
                    </div>
                    <div class="pull-left text-center" style="width:49%;display:inline-block;padding-right:2%;">
                        <input type="text"  value="" name="eve_to_date_'.$i.'_'.$event_requirement_id.'" id="eve_to_date_'.$i.'_'.$event_requirement_id.'" class="form-control datepicker_eve_to_'.$i.'" >
                    </div>
                    <div class="pull-left" style="width:4%;display:inline-block;padding-right:2%;"> </div> 
                    </div> 
                    <div style="width:27%;display:inline-block;padding-right:1%;">
                        <div class="datepairExample_'.$i.'">
                            <div class="pull-left" style="width:40%;display:inline-block;padding-right:2%;padding:4px;">
                                    <label style="display:block;">
                                            <input value="" name="starttime_'.$i.'_'.$event_requirement_id.'" id="starttime_'.$i.'_'.$event_requirement_id.'" type="text" class="form-control time start validate_time" />
                                    </label>
                            </div>
                            <div class="pull-left" style="width:40%;display:inline-block;padding-right:2%;padding:4px;">           
                                    <label style="display:block;">
                                            <input value="" name="endtime_'.$i.'_'.$event_requirement_id.'" id="endtime_'.$i.'_'.$event_requirement_id.'"  type="text" class="form-control time end validate_time" />
                                    </label>                
                            </div>
                            <div class="pull-left" style="width:10%;display:inline-block;padding-right:2%;padding:4px;"> &nbsp; </div>
                            <div class="pull-left" style="width:10%;display:inline-block;padding-right:2%;padding:4px;"> &nbsp; </div> 
                        </div>
                    </div>    
                    <input type="hidden" name="hidden_costService_'.$i.'_'.$event_requirement_id.'" id="hidden_costService_'.$i.'_'.$event_requirement_id.'" value="" > 
                    <div style="width:7%;display:inline-block;vertical-align:top;padding:4px;" id="costService_'.$i.'_'.$event_requirement_id.'">';
                    if($event_service_type=='2') { echo '<input type="text" name="other_service_cost_'.$i.'_'.$event_requirement_id.'" id="other_service_cost_'.$i.'_'.$event_requirement_id.'" class="form-control number" onkeyup="javascript:return CalculateTotEstCost(this,this.value);" maxlength="5" />'; } else { echo '&nbsp;'; }
                    echo '</div>
                  </div> 
               </div>
               <div id="div_'.$j.'_'.$event_requirement_id.'"><table>
               <tr><td colspan="5"> </td></tr></table></div>';
       
     }
     else if($_REQUEST['action'] == 'deletePlanCareEntry')
     {
        $plan_of_care_id = $_REQUEST['plan_of_care_id'];
        $arg['event_id'] = $_REQUEST['event_id'];

        //get event detail
        $getEventDtls = $eventClass->GetEvent($arg);

        //get plan of care details
        $getPlanOfCareDtls = $eventClass->getPlanOfCareById($plan_of_care_id);

        // Check is it detail plan of care entry present
         $checkDetailPlanOfCareSql = "SELECT Detailed_plan_of_care_id, Session_status
            FROM sp_detailed_event_plan_of_care
            WHERE plan_of_care_id = '" . $plan_of_care_id . "'";
        if ($db->num_of_rows($db->query($checkDetailPlanOfCareSql))) {
            $dtlPlanOfCareList = $db->fetch_all_array($checkDetailPlanOfCareSql);
            $totalRecords = count($dtlPlanOfCareList);
            $upcomingEventCount = 0;
            foreach ($dtlPlanOfCareList AS $key => $valPlanOfCare) {
                if ($valPlanOfCare['Session_status'] == '3') {
                    $upcomingEventCount += 1;
                }
            }
            //If all records are upcoming then delete both record entries
            if ($totalRecords == $upcomingEventCount) {
                $delDtlPlanOfCareRecord = "DELETE FROM sp_detailed_event_plan_of_care
                    WHERE plan_of_care_id = '" . $plan_of_care_id . "' ";
                $delDtlPlanOfCare = $db->query($delDtlPlanOfCareRecord);
                //Delete event plan of care record
                if ($delDtlPlanOfCare) {
                    $deletePlan = "DELETE FROM sp_event_plan_of_care
                        WHERE plan_of_care_id = '" . $plan_of_care_id . "' ";
                    $delStatus = $db->query($deletePlan);
                    if ($delStatus) {
                        echo "success";
                        exit;
                    } else {
                        echo "errorInDelPlanofCare"; //Error in delete sp_event_plan_of_care record
                        exit;
                    }
                } else {
                    echo "errorInDelDtlsPlanofCare"; //Error in delete sp_detailed_event_plan_of_care record
                    exit;
                }
            } else {
                echo "progressEventExists";
                exit;
            } 
        } else {
            $deletePlan = "DELETE FROM sp_event_plan_of_care
                WHERE plan_of_care_id = '" . $plan_of_care_id . "' ";
            $delStatus = $db->query($deletePlan);

            if ($delStatus) {
                // Add activity history for delete plan of care
                $EventDateTime = date('d M Y', strtotime($getPlanOfCareDtls['service_date'])) . " - " . $getPlanOfCareDtls['start_date'] .
                    date('d M Y', strtotime($getPlanOfCareDtls['service_date_to'])) . " - " . $getPlanOfCareDtls['end_date'];

                $activityDesc = $EventDateTime . " deleted successfully of  " . $getEventDtls['event_code'] . " by " . $_SESSION['emp_nm'];

                $insertActivityArr = array();
                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name'] = 'Remove Plan Of Care Details';
                $insertActivityArr['purpose_id']  = $getEventDtls['purpose_id'];
                $insertActivityArr['event_id']    = $getEventDtls['event_id'];
                $insertActivityArr['activity_description'] = $activityDesc;
                $insertActivityArr['added_by_type']  = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']    = $_SESSION['employee_id'];
                $insertActivityArr['added_by_date']  = date('Y-m-d H:i:s');

                $db->query_insert('sp_user_activity', $insertActivityArr);

                unset($insertActivityArr);

                echo "success";
                exit;
            } else {
                echo "errorInDelPlanofCare"; //Error in delete sp_event_plan_of_care record
                exit;
            }
        }
     }
     else if($_REQUEST['action'] == 'setCostPlancare')
     {
         $fromDtSelected = $_REQUEST['fromDtSelected'];
         $toDateSelcted = $_REQUEST['toDateSelcted'];
         $eventRequirementID = $_REQUEST['eventRequirementID'];
         $select_subserviceID = "select sub_service_id from sp_event_requirements where event_requirement_id = '".$eventRequirementID."' ";
         $ptr_subservice = $db->fetch_array($db->query($select_subserviceID));
         $select_cost = "select recommomded_service,cost,tax from sp_sub_services where sub_service_id = '".$ptr_subservice['sub_service_id']."'";
         $val_cost = $db->fetch_array($db->query($select_cost));
         
         if(!empty($val_cost))
         {
             $diff = (strtotime($toDateSelcted)- strtotime($fromDtSelected))/24/3600; 
            //echo $diff;
            $dateDiff = $diff+1;
            $CostPerDay = $val_cost['cost']*$dateDiff;
            
            if($val_cost['recommomded_service'] !='Other')
            {
               echo $CostPerDay.'.00';
               exit;
            }
            else 
            {
                echo 'Other';
                exit;
            }
         }
     }
     else if($_REQUEST['action'] == 'calculateTotalCost')
     {
        $event_id=$_REQUEST['Planevent'];
        $CostPerSubserv = 0;
        //$select_eventReq = "select event_id,event_requirement_id,service_id,sub_service_id from sp_event_requirements where event_id = '".$event_id."'";
        //$ptr_allreq = $db->fetch_all_array($select_eventReq);
        //foreach($ptr_allreq as $key=>$valEventReq)
        {
             $select_plan = "select event_requirement_id,service_date,service_date_to from sp_event_plan_of_care where event_id = '".$event_id."' ";
             $ptrAllDat = $db->fetch_all_array($select_plan);
             foreach($ptrAllDat as $key=>$valAllPlans)
            {
                $fromDtSelected = $valAllPlans['service_date'];
                $toDateSelcted = $valAllPlans['service_date_to'];
                
                $select_subserviceID = "select sub_service_id from sp_event_requirements where event_requirement_id = '".$valAllPlans['event_requirement_id']."' ";
                $ptr_subservice = $db->fetch_array($db->query($select_subserviceID));
                $select_cost = "select cost,tax from sp_sub_services where sub_service_id = '".$ptr_subservice['sub_service_id']."'";
                $val_cost = $db->fetch_array($db->query($select_cost));

                $diff = (strtotime($toDateSelcted)- strtotime($fromDtSelected))/24/3600; 
                //echo $diff;
                $dateDiff = $diff+1;
                $CostPerSubserv += $val_cost['cost']*$dateDiff;
            }
        }
        echo $CostPerSubserv;
         
     }
     
     else if($_REQUEST['action']=='ArchiveEvent')
     {
         $event_id = $_REQUEST['event_id']; 
         if (!empty($event_id)) {
            // Get event details
            $eventDtls = $eventClass->GetEvent($_REQUEST);
            $ArchiveSql = "UPDATE sp_events SET isArchive = '2' WHERE event_id='" . $event_id . "'";
            $Archive = $db->query($ArchiveSql); 
            if (!empty($Archive)) {
                // Add activity details
                $insertActivityArr = array();
                $insertActivityArr['module_type']    = '1';
                $insertActivityArr['module_id']      = '';
                $insertActivityArr['module_name']    = 'Archive Event Details';
                $insertActivityArr['purpose_id']     = $eventDtls['purpose_id'];
                $insertActivityArr['event_id']       = $eventDtls['event_id'];
                $insertActivityArr['activity_description'] = "Event (" .  $eventDtls['event_code']  . ") archived successfully by " . $_SESSION['emp_nm'];
                $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']   = $_SESSION['employee_id'];
                $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');
                $db->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);



                echo "success";
                exit;
            } else {
                echo "error";
                exit;
            }
         }
     }
     
     else if($_REQUEST['action']=='OpenPDFDoc')
     {
         $doc_id=$_REQUEST['doc_id']; 
         if(!empty($doc_id))
         {
             $GetDocument="SELECT document_id,document_file FROM sp_knowledge_base_documents WHERE document_id='".$doc_id."' AND status='1'";
             $Doc=$db->fetch_array($db->query($GetDocument));
             
            if(!empty($Doc))
            {
                 if($Doc['document_file'] && file_exists("admin/KnowlegeDocuments/".$Doc['document_file']))
                 {
                     $document_Path=$GLOBALS['knowledgeDocument'].$Doc['document_file']; 
                     echo "success";
                     echo "htmlSeperator";
                     echo '<iframe id="viewer" src="http://45.40.136.143/~spero/ViewerJS/#../admin/KnowlegeDocuments/'.$Doc['document_file'].'" width="1024" height="768"  allowfullscreen webkitallowfullscreen></iframe>'; 
                     exit;
                 }
                 else 
                 {
                     echo "doc_doesnot_exit";
                     exit;
                 }
            }
            else 
            {
                echo "error";
                exit;
            }
             
         }
     }
	 
	else if($_REQUEST['action'] == 'savePayment')
    {
        $arr = array();
        $arr['eventId']        = $_REQUEST['event_id'];
        $arr['amount']         = $_REQUEST['amount'];
        $arr['payType']        = $_REQUEST['paytype'];
        $arr['narration']      = $_REQUEST['Comments'];
        $arr['transType']      = $_REQUEST['Transaction_Type'];
        $arr['chequeNumber']   = (($arr['payType'] == 'Cash') ? '-' : $_REQUEST['Cheque_DD__NEFT_no']);
        $arr['transDate']      = (($arr['payType'] == 'Cash') ? '-' : $_REQUEST['Cheque_DD__NEFT_date']);
        $arr['partyBankName']  = $_REQUEST['Party_bank_name'];
        $arr['profName']       = $_REQUEST['Professional_name'];
        $arr['cardNumber']     = $_REQUEST['Card_Number'];
        $arr['transId']        = $_REQUEST['Transaction_ID'];


       // Get hospital details
        $arr['hospitalId'] = '';
        $arr['branchName'] = '';
        $getHospitalDtlsSql = "SELECT call_id.phone_no,e.event_id,eprof.professional_vender_id,h.hospital_id,h.branch,e.event_code,e.finalcost,e.patient_id,p.first_name,p.name,p.hhc_code,p.mobile_no,p.residential_address
            FROM sp_events AS e
            INNER JOIN sp_hospitals h ON e.hospital_id = h.hospital_id
            INNER JOIN sp_patients p ON e.patient_id = p.patient_id
            INNER JOIN sp_event_professional eprof ON eprof.event_id = e.event_id
            INNER JOIN sp_callers call_id ON call_id.caller_id = e.caller_id
            WHERE e.event_id = '" . $arr['eventId'] . "'";

        //echo '<pre>$getHospitalDtlsSql ----<br/>';
        //print_r($getHospitalDtlsSql);
        //echo '</pre>';

        if ($db->num_of_rows($db->query($getHospitalDtlsSql))) {
            $hospitalDtls = $db->fetch_array($db->query($getHospitalDtlsSql));
            $arr['hospitalId'] = $hospitalDtls['hospital_id'];
            $arr['branchName'] = $hospitalDtls['branch'];
            $finalcost         = $hospitalDtls['finalcost'];
            $first_name         = $hospitalDtls['first_name'];
            $name         = $hospitalDtls['name'];
            $hhc_code         = $hospitalDtls['hhc_code'];
            $mobile_no         = $hospitalDtls['mobile_no'];
            $event_code         = $hospitalDtls['event_code'];
            $phone_no =  $hospitalDtls['phone_no'];
            $residential_address = $hospitalDtls['residential_address'];
            $event_id = $hospitalDtls['event_id'];
        }

        // Generate receipt number
        $arr['receiptNumber'] = '';
        $getMaxReceiptNumSql = "SELECT MAX(payment_receipt_no_voucher_no) AS receiptNumber
            FROM sp_payments
            WHERE hospital_id = '" . $arr['hospitalId'] . "'";

        //echo '<pre>$getMaxReceiptNumSql ----<br/>';
        //print_r($getMaxReceiptNumSql);
        //echo '</pre>';

        if ($db->num_of_rows($db->query($getMaxReceiptNumSql))) {
            $receiptDtls = $db->fetch_array($db->query($getMaxReceiptNumSql));
            $arr['receiptNumber'] = $receiptDtls['receiptNumber'] + 1;
        }

        if (!empty($arr['eventId']) && !empty($arr['receiptNumber'])) {
            $arr['added_by']    = $_SESSION['employee_id'];
            $arr['Add_through'] = '1'; //  1 means HD and 2 means professional;
            $arr['status']      = '1';

            //Get event requirement details
            $eventRequirementDtls = $eventClass->getEventRequirementDtls($arr['eventId']);
            $eventServicetDtls = $eventClass->GetEventRequirement_service($arr['eventId']);
            //var_dump($eventServicetDtls);die();
            foreach($eventServicetDtls as $key=>$valRequirements)
            {
                
               $service_title = $valRequirements['service_title'];
               $recommomded_service = $valRequirements['recommomded_service'];
            }
            // var_dump($service_title);die();
            if (!empty($eventRequirementDtls)) {
                $arr['payment_id'] = $eventClass->addEventPayment($arr);
                // Insert details in payment details table
                if (!empty($arr['payment_id'])) {

                    // Update tally status in event table
                    $tallyStatus = $eventClass->updateTallyStatus($arr['eventId']);

                    $txtMsg = '';
                    $txtMsg1 .= "Spero Healthcare Innovation,";
                    $txtMsg1 .= "Dear ".$first_name." ".$name."[".$hhc_code."],";
                    $txtMsg1 .= "Event ID: ".$arr['eventId'];
                    $txtMsg1 .= ",We have received ".$arr['payType']." payment against ".$arr['eventId']."[".$service_title."-".$recommomded_service."] Rs.".$arr['amount']." On ".date('Y-m-d').",";
                    $txtMsg1 .= "Thank You.";
                    //$txtMsg .= "Spero Healthcare Innovation,\n Dear ".$first_name." ".$name." [".$hhc_code."],\n We have received ".$arr['payType']." payment Rs.".$arr['amount']." on ".date('Y-m-d H:i:s').".\n Against ".$service_title." [".$recommomded_service."].\n Thank You!";
                    $args = array(
                                     'event_code'=> $event_code,
                                    'msg' => $txtMsg1,
                                    'mob_no' => $mobile_no
                                );
                    //$sms_data =$commonClass->sms_send($args);
                    //Professional SMS after payment
                     
                    
                    $GetEventReqSql="SELECT event_requirement_id,event_id,service_id FROM sp_event_requirements WHERE event_id='".$arr['eventId']."'";
                    $EventRequirement=$db->fetch_all_array($GetEventReqSql);
                    if(!empty($EventRequirement))
                    {
                        $msgtimecount = '1';
                        foreach ($EventRequirement as $key=>$ValRequirement)
                        {
                            $GetServiceSql="SELECT service_title FROM sp_services WHERE service_id='".$ValRequirement['service_id']."'";
                            $GetService=$db->fetch_array($db->query($GetServiceSql));
                            if(!empty($GetService))
                                $service_name=$GetService['service_title'];
                            $selected_Services = "SELECT er.event_requirement_id,er.sub_service_id,poc.service_date,poc.service_date_to,poc.start_date,poc.end_date FROM "
                                . " sp_event_requirements as er LEFT JOIN sp_event_plan_of_care as poc ON er.event_requirement_id = poc.event_requirement_id "
                                . " where er.service_id = '".$ValRequirement['service_id']."' and er.event_id = '".$ValRequirement['event_id']."' and er.status='1' and er.event_requirement_id='".$ValRequirement['event_requirement_id']."' ";
                            $ptr_selSertvices = $db->fetch_all_array($selected_Services);
                            foreach($ptr_selSertvices as $key=>$valSelcServ)
                            {
                                $selectTitle = "select recommomded_service from sp_sub_services where sub_service_id = '".$valSelcServ['sub_service_id']."'";
                                $valRecService = $db->fetch_array($db->query($selectTitle));
                                $recommomded_service=$valRecService['recommomded_service'];

                                $service_date = '';
                                $serviceTime='';
                                if(date('d-m-Y',strtotime($valSelcServ['service_date']))==date('d-m-Y',strtotime($valSelcServ['service_date_to'])))
                                    $service_date= date('d-m-Y',strtotime($valSelcServ['service_date']));
                                else 
                                    $service_date=date('d-m-Y',strtotime($valSelcServ['service_date'])).' to '.date('d-m-Y',strtotime($valSelcServ['service_date_to']));
                                
                                $service_date1 = date('d-m-Y',strtotime($valSelcServ['service_date'])).' to '.date('d-m-Y',strtotime($valSelcServ['service_date_to']));
                                $serviceTime=$valSelcServ['start_date'].' to '.$valSelcServ['end_date'];

                                $dateofService .= " Date".$msgtimecount." : ".$service_date1." Reporting time : ".$serviceTime;
                                //$timeofService .= ;
                                $sub_service=$recommomded_service;
                                $sub_service_detail = $sub_service_detail.",".sub_service;
                               // unset($service_name);
                                unset($recommomded_service);
                                unset($service_date);
                                unset($serviceTime); 
                                //unset($serviceTime); 
                                $msgtimecount++;
                                                            
                            }
                            
                        }  
                    }
                    unset($args);
                    $msgtcount = 1;
                        $select_jobsummarydiv = "select service_id,event_professional_id,event_id,reporting_instruction from sp_event_job_summary where event_id = '".$_REQUEST['event_id']."' and service_id= '".$service_pass_ids."'";
                        $val_job_summarys = $db->fetch_all_array($select_jobsummarydiv);
                        foreach($val_job_summarys as $key=>$allRecJobSum)
                        {
                            $service_id = $allRecJobSum['service_id'];
                            $event_professional_id = $allRecJobSum['event_professional_id'];
                            $select_event_req = "select event_requirement_id from sp_event_professional where event_professional_id = '".$event_professional_id."' ";
                            $valReqr = $db->fetch_array($db->query($select_event_req));
                            $selectPlanofCsare = "select service_date,service_date_to,start_date,end_date from sp_event_plan_of_care where event_requirement_id = '".$valReqr['event_requirement_id']."'";
                            $valPlanofcare = $db->fetch_all_array($selectPlanofCsare);
                            foreach($valPlanofcare as $key=>$valDatData)
                            {
                                $valDatData['service_date'];
                                if(date('d-m-Y',strtotime($valDatData['service_date']))==date('d-m-Y',strtotime($valDatData['service_date_to'])))
                                    $service_date= date('d-m-Y',strtotime($valDatData['service_date']));
                                else 
                                    $service_date=date('d-m-Y',strtotime($valDatData['service_date'])).' to '.date('d-m-Y',strtotime($valDatData['service_date_to']));

                                $service_datenew1 = date('d-m-Y',strtotime($valDatData['service_date'])).' to '.date('d-m-Y',strtotime($valDatData['service_date_to']));
                                $serviceTimenew=$valDatData['start_date'].' to '.$valDatData['end_date'];
                                $serviceTime=$valDatData['start_date'];
                                
                                $dateofServicenew .= "Date : ".$service_datenew1."Reporting time : ".$serviceTimenew;
                                $dateofServicepatient .= " Date:".$service_datenew1." Reporting time : ".$serviceTime;
                               // $dateofServicenew .= " Date".$msgtcount." : ".$service_datenew1." Reporting time : ".$serviceTimenew;
                                $dateofServicepatient .= " Date".$msgtcount." : ".$service_datenew1." Reporting time : ".$serviceTime;
                                
                                $msgtcount++;
                            }
                            
                        }
                        $payments_deatils = mysql_query("SELECT * FROM sp_payments  where event_id='$event_id'");
                        $row_count = mysql_num_rows($payments_deatils);
                        if($row_count > 0)
                        {
                            $amt = 0;
                            while ($payment_rows = mysql_fetch_array($payments_deatils))
                            {	
                                $amt=$payment_rows['amount']+$amt;
                            }
                            if($finalcost == $amt || $finalcost <= $amt){
                                    $payment_status ='Received';
                            }elseif($finalcost > $amt){
                                $payment_status ='Partial Payment';
                            }
                            
                        }
                        else{
                        $payment_status='Pending';
                        }
                        $txtMsg1='';
                        $prof = array(
                            'service_professional_id'=>  $hospitalDtls['professional_vender_id']
                        );
                        $professionalDtls=$professionalsClass->GetProfessionalById($prof);
                    $profmob = $professionalDtls['mobile_no'];
                    $txtMsg1 .= "Dear ".$professionalDtls['title']." ".$professionalDtls['name']." ".$professionalDtls['first_name']."";
                    $txtMsg1 .= "\n\nPatient : ".$first_name." ".$name." [".$hhc_code."] ";
                    $txtMsg1 .= "\n\nCaller No : ".$phone_no;
                    $txtMsg1 .= " \nMob No : ".$mobile_no;
                    $txtMsg1 .= "\n\nAddress : ".$residential_address;
                    $txtMsg1 .= "\n\nEvent No : ".$event_code;
                    $txtMsg1 .= "\nService : ".$service_name."\nSub-Service : ".$sub_service;
                    $txtMsg1 .= "".$dateofServicenew;//$dateofService;
                    $txtMsg1 .= "\n\nPayment Status:".$payment_status;
                    $txtMsg1 .= "\n\nSpero";

                    $args1 = array(
                            'event_code'=> $event_code,
							'msg' => $txtMsg,
							'mob_no' => $profmob
						);  
                   // sms_data =$commonClass->sms_send_prof($args1); 
                    
                    if (empty($tallyStatus)) {
                        echo "errorInUpdateTallyStatus";
                        exit; 
                    }

                    $paymentDetailId = $eventClass->checkForEventRequirement($eventRequirementDtls, $arr);

                    if (!empty($paymentDetailId)) {
                        // Update payment received professional status
                        $paymentReceivedStatus = $eventClass->updatePaymentReceivedStatus($arr['eventId']);

                        if (empty($paymentReceivedStatus)) {
                            echo "errorInPaymentReceivedStatus";
                            exit;
                        } else {
                            echo "success";
                            exit;
                        }   
                    } else {
                        echo "error";
                        exit;
                    }
                } else {
                    echo "errorInAddPayment";
                    exit;
                }
            } else {
                echo "NoDataFound";
                exit;
            }
        }
    }

    else if($_REQUEST['action'] == 'ChangeEnquirySubServices')
    {
        $service_ids = $_REQUEST['service_ids'];
        $explodeIds = explode(',', $service_ids);
        for ($i = 0; $i < count($explodeIds); $i++)
        {
            $arr['service_id'] = $explodeIds[$i];
            $recordServices    = $adminClass->GetServiceById($arr);
        ?>
        <div class = "form-group" id = "enquirySubServiceDiv_<?php echo $arr['service_id'];?>">
            <div class = "col-sm-12">
                <div class = "form-group">
                    <select class = "form-control" id = "enquiry_sub_service_id_multiselect_<?php echo $arr['service_id'];?>" name="enquiry_sub_service_id_multiselect_<?php echo $arr['service_id'];?>[]" multiple="multiple">
                        <?php
                            $subServiceSql = "SELECT sub_service_id,recommomded_service FROM sp_sub_services WHERE status = '1' AND service_id = '" . $arr['service_id'] . "' ORDER BY recommomded_service ASC";
                            $subServiceList = $db->fetch_all_array($subServiceSql);
                            foreach ($subServiceList AS $key => $subServiceVal)
                            {
                                echo '<option value="' . $subServiceVal['sub_service_id'] . '">' . $subServiceVal['recommomded_service'] . '</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <?php
        echo 'sepratedTitle--';
        echo $recordServices['service_title'];
        }
    }
    else if($_REQUEST['action'] == 'professional_service_remainder')
    {
        $recListResponse = $professionalsClass->Professionals_Notifinction();
        $recList=$recListResponse['data'];
        foreach($recList as $key=>$valProfessional)
        {}
        
    }
    else if($_REQUEST['action'] == 'daily_service_count_SMS')
    {
        $recListResponse = $professionalsClass->daily_service_count();
        $recList=$recListResponse['data'];
        foreach($recList as $key=>$valProfessional)
        {}
        
    }
    else if($_REQUEST['action'] == 'Republic_day')
    {
        $sql = "SELECT * FROM sp_emp_spero WHERE Status = 1 ";
        if ($db->num_of_rows($db->query($sql))) {
            $sql_query = $db->fetch_all_array($sql);
            $totalRecords = count($sql_query);
            $upcomingEventCount = 0;
            foreach ($sql_query AS $key => $val) {
                $txtMsg1 .= "No nation is perfect, it is us who can make it a prosperous one.\nHappy Republic Day!\nSpero\n";
                
                $args = array(
                        'msg' => $txtMsg1,
                        'mob_no' => $val['mobile_no']
                       );  
                $recListResponse = $commonClass->Republic_day_sms($args);
                $txtMsg1='';
            }
        }
        
    }
    else if($_REQUEST['action'] == 'birth_day')
    {
        $sql = "SELECT * FROM sp_emp_spero WHERE Status = 1 ";
        if ($db->num_of_rows($db->query($sql))) {
            $sql_query = $db->fetch_all_array($sql);
            $totalRecords = count($sql_query);
            $upcomingEventCount = 0;
            foreach ($sql_query AS $key => $val) {
                $month = date('d-m');
                $birth_date = $val['birth_date'];
                $fName = $val['fname'];
               $birth_date_today = date("d-m", strtotime($birth_date));
                if($month==$birth_date_today){
                    $txtMsg1 .= "May God bless you today with a wonderful happy birthday and years of tomorrows filled with prosperity, joy, and happiness.\nwe wish you a great success and well-being.\nHappy Birthday\nSpero\n";
                    $args = array(
                            'msg' => $txtMsg1,
                            'mob_no' => $val['mobile_no']
                            );  
                $recListResponse = $commonClass->birthday_sms($args);
                $txtMsg1='';
                }
                
            }
        }
        
    }
    else if($_REQUEST['action'] == 'Makar_Sankrant')
    {
        $sql = "SELECT * FROM sp_emp_spero WHERE Status = 1 ";
        if ($db->num_of_rows($db->query($sql))) {
            $sql_query = $db->fetch_all_array($sql);
            $totalRecords = count($sql_query);
            $upcomingEventCount = 0;
            foreach ($sql_query AS $key => $val) {
                $txtMsg1 .= "As the sun starts its journey towards the north,\nhe makes all happy moments of this year come to life.\nI wish you and your family a very Happy Makar Sankranti.\nSpero\n";
                
                $args = array(
                        'msg' => $txtMsg1,
                        'mob_no' => $val['mobile_no']
                       );  
                $recListResponse = $commonClass->Makar_Sankrant_sms($args);
                $txtMsg1='';
            }
        }
        
    }
?> 