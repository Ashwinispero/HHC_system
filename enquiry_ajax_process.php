<?php   require_once 'inc_classes.php'; 
        include "classes/eventClass.php";
        $eventClass = new eventClass();
        include "classes/employeesClass.php";
        $employeesClass = new employeesClass();
        include "classes/commonClass.php";
        $commonClass= new commonClass();
?>
<?php
if($_REQUEST['action'] == 'EnquiryNoteForm')
{
    $success = 0;  
    $errors = array();  
    $i = 0;
    if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $temp_event_id=$_POST['enquiryEvent_id'];
        $prv_purpose_id=$_POST['enq_purpose_id'];
        $enquiryNote=strip_tags($_POST['enquiry_note']);
        $newDate=strip_tags($_POST['date_of_service']);
        $date_of_service = date("Y-m-d h:m:s", strtotime($newDate));
       // var_dump($newDate);die();
        $serviceIds = $_POST['enquiryRequirnment'];

        if (!empty($serviceIds)) {
            $reqirementArr = array();
            foreach ($serviceIds AS $key => $serviceIdVal) {
                // Get selected sub service
                $reqirementArr[$serviceIdVal] = $_POST['enquiry_sub_service_id_multiselect_' . $serviceIdVal];
            }
        }

        if($prv_purpose_id=='')
        { 
            $success=0;
            $errors[$i++]="Please select purpose of call";
        }

        if (empty($serviceIds))
        { 
            $success=0;
            $errors[$i++]="Please select service";
        }

        // Check sub service selected
        if (!empty($serviceIds)) {
            $selectedSubServiceFlag = false;
            foreach ($serviceIds AS $key => $serviceIdVal) {
                if (empty($_POST['enquiry_sub_service_id_multiselect_' . $serviceIdVal])) {
                    $selectedSubServiceFlag = true;
                    break;
                }
            }

            if ($selectedSubServiceFlag) {
                $success = 0;
                $errors[$i++] = "Please select sub service";
            }

        }

        if($enquiryNote=='')
        {
            $success=0;
            $errors[$i++]="Please enter enquiry note";
        }
        if(count($errors))
        {
           echo 'error'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;

            // Get Event Details
            $getEventDtlsSql = "SELECT note,
                event_status,
                service_date_of_Enquiry,
                enquiry_added_date,
                last_modified_by,
                last_modified_date
            FROM sp_events
            WHERE event_id = '" . $temp_event_id . "'";

            if (mysql_num_rows($db->query($getEventDtlsSql))) {
                $eventDtls = $db->fetch_array($db->query($getEventDtlsSql));
            }

            $updateEditedData['note']=ucfirst(strtolower($enquiryNote));
            $updateEditedData['event_status']='5';
			$updateEditedData['service_date_of_Enquiry']=ucfirst(strtolower($date_of_service));
			$updateEditedData['enquiry_added_date'] = date('Y-m-d H:i:s');
            $updateEditedData['last_modified_by'] = $_SESSION['employee_id'];
            $updateEditedData['last_modified_date'] = date('Y-m-d H:i:s');
            $where = "event_id ='".$temp_event_id."' ";
            $updateRecord = $db->query_update('sp_events',$updateEditedData,$where);

            if (!empty($updateRecord) && !empty($eventDtls)) {

                // Added activity for enquiry details
                $insertActivityArr = array();
                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name'] = 'Add Enquiry Details';
                $insertActivityArr['purpose_id']  = $prv_purpose_id;
                $insertActivityArr['event_id']    = $temp_event_id;

                $result = array_diff_assoc($callerDtls, $updateEditedData);
                if (!empty($result)) {
                    $messageStr = "";
                    foreach ($result AS $key => $valResult) {
                        $messageStr .= $key . " is change from " . $valResult . " to " . $updateEditedData[$key] . "\r\n";
                    }
                }
                $insertActivityArr['activity_description'] = (!empty($messageStr) ? nl2br($messageStr) : "");
                $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']   = $_SESSION['employee_id'];
                $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');
                $db->query_insert('sp_user_activity',$insertActivityArr);

                $insertData = array();
                $insertData['event_id'] = $temp_event_id;
                if (!empty($reqirementArr)) {

                    // Check is it record present in table
                    $getRecordSql = "SELECT enquiry_requirement_id,service_id,sub_service_id
                        FROM sp_enquiry_requirements
                        WHERE event_id = '" . $temp_event_id . "'";
                    
                    if (mysql_num_rows($db->query($getRecordSql))) {
                        $recordResult = $db->fetch_all_array($getRecordSql);

                        if (!empty($recordResult)) {
                            $existingServiceIds = array();
                            $existingSubServiceIds = array();
                            foreach ($recordResult AS $recordVal) {
                                $existingServiceIds[]    = $recordVal['service_id'];
                                $existingSubServiceIds[] = $recordVal['sub_service_id'];
                            }

                            if (!empty($existingServiceIds) && !empty($serviceIds)) {
                                $existingServiceIds = array_unique($existingServiceIds);

                                $diffReq = array_diff($existingServiceIds, $serviceIds);

                                if (!empty($diffReq)) {
                                    foreach ($diffReq AS $diffReqVal) {
                                        $deleteReq = "DELETE FROM sp_enquiry_requirements
                                            WHERE service_id = '" . $diffReqVal . "' ";
                                        $delStatus = $db->query($deleteReq);
                                    }
                                }
                            }
                        }
                    }

                    foreach ($reqirementArr AS $key => $valRequirement) {
                        $insertData['service_id'] = $key;
                        if (!empty($valRequirement)) {
                            foreach ($valRequirement AS $subServiceVal) {
                                $insertData['sub_service_id'] = $subServiceVal;
                                // insert data in child table

                                // check is it same record present
                                $chkRecordExists = "SELECT enquiry_requirement_id 
                                    FROM sp_enquiry_requirements
                                    WHERE event_id = '" . $temp_event_id . "' AND 
                                    service_id = '" . $valRequirement[$key] . "' AND 
                                    sub_service_id = '" . $subServiceVal ."' ";

                                if (mysql_num_rows($db->query($chkRecordExists)) == 0)
                                {
                                    $db->query_insert('sp_enquiry_requirements', $insertData);
                                }
                            }
                        }
                    }
                }

                // Added activity for enquiry details
                $getEventCodeSql = "SELECT event_code FROM sp_events WHERE event_id = '" . $temp_event_id . "'";
                if (mysql_num_rows($db->query($getEventCodeSql))) {
                    $eventCodeDtls = $db->fetch_array($db->query($getEventCodeSql));
                }
                $insertActivityArr = array();
                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name'] = 'Add Enquiry Details';
                $insertActivityArr['purpose_id']  = $prv_purpose_id;
                $insertActivityArr['event_id']    = $temp_event_id;
                $insertActivityArr['activity_description'] = "Enquiry details added successfully. New enquiry (" .  $eventCodeDtls['event_code']  . ") is get created by " . $_SESSION['emp_nm'] . " ";
                $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']   = $employee_id;
                $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');
                $db->query_insert('sp_user_activity',$insertActivityArr);

                unset($insertActivityArr);

            }
            echo "updated";
            exit;
        }
    }
}
?>