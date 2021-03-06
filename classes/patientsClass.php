<?php
    if (!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if (!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class patientsClass extends AbstractDB 
{
    private $result;
    public function __construct() 
    {
        parent::__construct();
        $this->result = NULL;
        $this->connect();
        return true;
    }
    public function close() 
    {
        parent::close();            
    }
    public function PatientsList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if (!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (hhc_code LIKE '%".$search_value."%' OR name LIKE '%".$search_value."%' OR  first_name LIKE '%".$search_value."%' OR middle_name LIKE '%".$search_value."%' OR email_id LIKE '%".$search_value."%' OR phone_no LIKE '%".$search_value."%' OR mobile_no LIKE '%".$search_value."%' OR dob LIKE '%".$search_value."%')"; 
        }
        if ((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .="ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        if (!empty($isTrash) && $isTrash !='null')
        {
           $preWhere .="AND status='3'"; 
        }
        else 
        {
          $preWhere .="AND status IN ('1','2')";   
        }
        
        $PatientsSql = "SELECT patient_id FROM sp_patients WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($PatientsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($PatientsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql = "SELECT patient_id,hhc_code,name,first_name,middle_name,email_id,residential_address,location_id,phone_no,mobile_no,dob,status,isDelStatus,added_date,google_location, isVIP FROM sp_patients WHERE patient_id='".$val_records['patient_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // If landline Number Not Available
                if (empty($RecordResult['phone_no']))
                    $RecordResult['phone_no']='Not Available';
                
                // If Birth Date Not Available
                if (!empty($RecordResult['dob']) && $RecordResult['dob'] !='0000-00-00')
                    $RecordResult['dob']=date('d M Y',strtotime($RecordResult['dob']));  
                else 
                   $RecordResult['dob']='Not Available';

                // Getting Location Name
                if ($RecordResult['google_location'] == '')
                {
                    if (!empty($RecordResult['location_id']))
                    {
                       $LocationSql = "SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$RecordResult['location_id']."'";
                       $LocationDtls=$this->fetch_array($this->query($LocationSql));
                       $RecordResult['locationNm']=$LocationDtls['location']; 
                       $RecordResult['LocationPinCode']=$LocationDtls['pin_code']; 
                    }
                }
                else
                {
                    $RecordResult['locationNm'] = $RecordResult['google_location'];
                }
                
                // Getting Status
                if (!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                
                // Getting isVIP
                if (!empty($RecordResult['isVIP']))
                {
                    $isVIPStatusArr = array('Y' =>'Yes', 'N' => 'No');
                    $RecordResult['isVIPVal'] = $isVIPStatusArr[$RecordResult['isVIP']];
                }
                
                // check is it any event log present for this patient
                
                $chk_event_log_sql = "SELECT event_id FROM sp_events WHERE patient_id='".$RecordResult['patient_id']."'";
                if ($this->num_of_rows($this->query($chk_event_log_sql)))
                   $RecordResult['isEvents']= "1";
                else 
                   $RecordResult['isEvents']= "0"; 

                $this->resultPatients[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if (count($this->resultPatients))
        {
            $resultArray['data']=$this->resultPatients;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function InsertPatient($arg)
    {
        $patient_id=$this->escape($arg['patient_id']);
        
        if (!empty($patient_id) && $patient_id !='')
          $ChkPatientSql = "SELECT patient_id FROM sp_patients WHERE name='".$arg['name']."' AND status !='3' AND patient_id !='".$patient_id."'";
        
        if ($this->num_of_rows($this->query($ChkPatientSql)) == 0)
        {
            $insertData = array();
            $insertData['name']=$this->escape($arg['name']);
            $insertData['first_name']=$this->escape($arg['first_name']);
            $insertData['middle_name']=$this->escape($arg['middle_name']);
            $insertData['email_id']=$this->escape($arg['email_id']);
            $insertData['phone_no']=$this->escape($arg['phone_no']);
            $insertData['mobile_no']=$this->escape($arg['mobile_no']);
            $insertData['dob']=$this->escape($arg['dob']);
            $insertData['residential_address']=$this->escape($arg['residential_address']);
            $insertData['permanant_address']=$this->escape($arg['permanant_address']);
            $insertData['location_id']=$this->escape($arg['location_id']);
            $insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            if (!empty($patient_id))
            {
              $where = "patient_id ='".$patient_id."' ";
              $RecordId=$this->query_update('sp_patients',$insertData,$where); 
            }
            if (!empty($RecordId))
                return $RecordId; 
            else
                return 0;
        }
        else
            return 0;  
    }
    public function GetPatientById($arg)
    {
        $patient_id=$this->escape($arg['patient_id']);
        $GetOnePatientSql = "SELECT patient_ref_name,patient_id,hhc_code,name,location_id,sub_location,city_id,first_name,middle_name,email_id,residential_address,permanant_address,location_id,phone_no,mobile_no,dob,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date,google_location FROM sp_patients WHERE patient_id='".$patient_id."'";
        if ($this->num_of_rows($this->query($GetOnePatientSql)))
        {
            $Patient = $this->fetch_array($this->query($GetOnePatientSql));
            
            // Getting Location Name
            if ($Patient['google_location'] == '')
            {
                if (!empty($Patient['location_id']))
                {
                   $LocationSql = "SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$Patient['location_id']."'";
                   $LocationDtls=$this->fetch_array($this->query($LocationSql));
                   $Patient['locationNm']=$LocationDtls['location']; 
                   $Patient['LocationPinCode']=$LocationDtls['pin_code']; 
                }
            }
            else
                $Patient['locationNm']=$Patient['google_location']; 
            
            // Getting Status
            if (!empty($Patient['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Patient['statusVal']=$StatusArr[$Patient['status']];
            }
            
            // Getting Added User Name 
            if (!empty($Patient['added_by']))
            {
               $AddedUserSql = "SELECT name FROM sp_admin_users WHERE admin_user_id='".$Patient['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Patient['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if (!empty($Patient['last_modified_by']))
            {
               $ModifiedUserSql = "SELECT name FROM sp_admin_users WHERE admin_user_id='".$Patient['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Patient['last_modified_by']=$ModifiedUser['name'];
            }
            return $Patient;
        }
        else 
            return 0;            
    }

    /**
     *
     * This function is used fo update patient status
     *
     * @param array $arg
     *
     * @return int 0|1
     *
     */
    public function ChangeStatus($arg)
    {
        $patient_id    = $this->escape($arg['patient_id']);
        $status        = $this->escape($arg['status']);
        $pre_status    = $this->escape($arg['curr_status']);
        $istrashDelete = $this->escape($arg['istrashDelete']);
        $login_user_id = $this->escape($arg['login_user_id']);
        $ChkPatientSql = "SELECT patient_id,
            status,
            isDelStatus,
            last_modified_by,
            last_modified_date
        FROM sp_patients 
        WHERE patient_id = '" . $patient_id . "'";

        if ($this->num_of_rows($this->query($ChkPatientSql))) {
            $patientDtls = $this->fetch_array($this->query($ChkPatientSql));
            $EventsSql = "SELECT event_id FROM sp_events WHERE patient_id = '" . $patient_id . "'";
            $AllEvents = $this->fetch_all_array($EventsSql);
             
            if ($istrashDelete) {
                // Getting All Events of this patient
                if ($this->num_of_rows($this->query($EventsSql))) {
                    foreach ($AllEvents AS $valEvents) {
                        // Delete Consultant Call
                        $DelConsultantCall = "DELETE FROM sp_event_consultant_call WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelConsultantCall);
                        
                        // Delete Event Doctor Mapping
                        $DelEveDocMap = "DELETE FROM sp_event_doctor_mapping WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelEveDocMap);
                        
                        // Delete Event Follow up Call
                        $DelEveFollowup = "DELETE FROM sp_event_follow_up WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelEveFollowup);
                        
                        // Delete Job Summary 
                        $DelEveJobSummary = "DELETE FROM sp_event_job_summary WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelEveJobSummary);
                        
                        // Delete Plan of care 
                        $DelPlanofcare = "DELETE FROM sp_event_plan_of_care WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelPlanofcare);
                        
                        // Delete Event Professional
                        $DelEveProfessional = "DELETE FROM sp_event_professional WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelEveProfessional);
                        
                        //Delete Event Requirement
                        $DelEveRequirement = "DELETE FROM sp_event_requirements WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelEveRequirement);
                        
                        //Delete Event Share with HCM
                        $DelEveSharewithHCM = "DELETE FROM sp_event_share_hcm WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelEveSharewithHCM);
                        
                        // Get Job Closure Details
                        $GetJobClosureSql = "SELECT job_closure_id,job_closure_file FROM sp_job_closure WHERE event_id = '" . $valEvents['event_id'] . "'";
                        if ($this->num_of_rows($this->query($GetJobClosureSql))) {
                            $GetJobClosure = $this->fetch_all_array($GetJobClosureSql);
                            foreach ($GetJobClosure AS $valJobClosure) {
                                // Delete Job Closure Consumption
                                $DelJobClosureConsumption = "DELETE FROM sp_job_closure_consumption_mapping WHERE job_closure_id = '" . $valJobClosure['job_closure_id'] . "'";
                                $this->query($DelJobClosureConsumption);
                                
                                $job_closure_file = $valJobClosure['job_closure_file'];
                                if (!empty($job_closure_file) && file_exists('../JobClosureDocuments/' . $job_closure_file)) {
                                    unlink('../JobClosureDocuments/'.$job_closure_file);
                                } 
                            }
                        }
                        // Delete Job Closure
                        $DelJobClosure = "DELETE FROM sp_job_closure WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelJobClosure);
                        
                        // Delete Feedback 
                        $DelFeedbacks = "DELETE FROM sp_feedback_answers WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelFeedbacks);
                        // Delete Dependent Event
                        $DelDependentEvents = "DELETE FROM sp_events WHERE purpose_event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelDependentEvents);
                        
                        $DelEvents = "DELETE FROM sp_events WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $this->query($DelEvents);
                    }   
                }
                $UpdateStatusSql = "DELETE FROM sp_patients WHERE patient_id = '" . $patient_id . "'";
            } else {
                if ($this->num_of_rows($this->query($EventsSql))) {
                    $UpdateEveDocMapArr       = array();
                    $UpdateEveJobSummaryArr   = array();
                    $UpdatePlanofcareArr      = array();
                    $UpdateEveProfessionalArr = array();
                    $UpdateEveRequirementArr  = array();
                    $UpdateEveSharewithHCMArr = array();
                    $UpdateJobClosureArr      = array();
                    $UpdateDependentEventArr  = array();
                    $UpdateEventArr           = array();

                    foreach ($AllEvents AS $valEvents) {
                        // Get event doctor mapping details
                        $eventDoctorMappingSql = "SELECT t1.event_doctor_id,
                            t1.status,
                            t2.event_code,
                            t1.last_modified_by,
                            t1.last_modified_date
                        FROM sp_event_doctor_mapping AS t1
                        INNER JOIN sp_events AS t2
                        ON t1.event_id = t2.event_id
                        WHERE t1.event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($eventDoctorMappingSql))) {
                            $eventDoctorMappingDtls = $this->fetch_all_array($eventDoctorMappingSql);
                        }

                        // Update Event Doctor Mapping
                        $UpdateEveDocMap = "UPDATE sp_event_doctor_mapping SET status = '" . $status . "', last_modified_by = '" . $login_user_id."', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $UpdateEveDocMapStatus = $this->query($UpdateEveDocMap);

                        // Create array of updated event doctor mapping details
                        if (!empty($UpdateEveDocMapStatus) && !empty($eventDoctorMappingDtls)) {
                           foreach ($eventDoctorMappingDtls AS $valEventDoctorMapping) {
                            $UpdateEveDocMapArr[] = "Event doctor mapping details updated of record " . $valEventDoctorMapping['event_doctor_id'] . " as follows \r\n" .
                                "status is change from " . $valEventDoctorMapping['status'] . " to " . $status . "\r\n" .
                                "last_modified_by is change from " . $valEventDoctorMapping['last_modified_by'] . " to " . $login_user_id . "\r\n" .
                                "last_modified_date is change from " .  $valEventDoctorMapping['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                " for event " . $valEventDoctorMapping['event_code'] . "\r\n";
                           }  
                        }

                        // Get event Job Summary  details
                        $eventJobSummarySql = "SELECT t1.job_summary_id,
                            t1.status,
                            t2.event_code,
                            t1.modified_by,
                            t1.last_modified_date
                        FROM sp_event_job_summary AS t1
                        INNER JOIN sp_events AS t2
                            ON t1.event_id = t2.event_id
                        WHERE t1.event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($eventJobSummarySql))) {
                            $eventJobSummaryDtls = $this->fetch_all_array($eventJobSummarySql);
                        }

                        // Update Job Summary 
                        $UpdateEveJobSummary = "UPDATE sp_event_job_summary SET status = '" . $status . "', modified_by = '" . $login_user_id."', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $UpdateEveJobSummaryStatus = $this->query($UpdateEveJobSummary);

                        // Create array of updated job summary details
                        if (!empty($UpdateEveJobSummaryStatus) && !empty($eventJobSummaryDtls)) {
                            foreach ($eventJobSummaryDtls AS $valEventJobSummary) {
                             $UpdateEveJobSummaryArr[] = "Event job summary details updated of record " . $valEventJobSummary['job_summary_id'] . " as follows \r\n" .
                                " status is change from " . $valEventJobSummary['status'] . " to " . $status . "\r\n" .
                                " modified_by is change from " . $valEventJobSummary['modified_by'] . " to " . $login_user_id . "\r\n" .
                                " last_modified_date is change from " .  $valEventJobSummary['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                " for the event " . $valEventJobSummary['event_code'] . "\r\n";
                            }  
                        }

                        // Get event plan of care details
                        $eventPlanOfcareSql = "SELECT t1.plan_of_care_id,
                            t1.status,
                            t2.event_code,
                            t1.last_modified_by,
                            t1.last_modified_date
                        FROM sp_event_plan_of_care AS t1
                        INNER JOIN sp_events AS t2
                            ON t1.event_id = t2.event_id
                        WHERE t1.event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($eventPlanOfcareSql))) {
                            $eventPlanOfcareDtls = $this->fetch_all_array($eventPlanOfcareSql);
                        }
                        
                        // Update Plan of care 
                        $UpdatePlanofcare = "UPDATE sp_event_plan_of_care SET status = '" . $status . "', last_modified_by = '" . $login_user_id."', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $UpdatePlanofcareStatus = $this->query($UpdatePlanofcare);

                        // Create array of updated plan of care details
                        if (!empty($UpdatePlanofcareStatus) && !empty($eventPlanOfcareDtls)) {
                            foreach ($eventPlanOfcareDtls AS $valEventPlanOfcare) {
                             $UpdatePlanofcareArr[] = "Event plan of care details updated of record " . $valEventPlanOfcare['plan_of_care_id'] . " as follows \r\n" .
                                " status is change from " . $valEventPlanOfcare['status'] . " to " . $status . "\r\n" .
                                " last_modified_by is change from " . $valEventPlanOfcare['last_modified_by'] . " to " . $login_user_id . "\r\n" .
                                " last_modified_date is change from " .  $valEventPlanOfcare['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                " for the event " . $valEventPlanOfcare['event_code'] . "\r\n";
                            }  
                        }

                        // Get event plan of care details
                        $eventProfessionalSql = "SELECT t1.event_professional_id,
                            t1.status,
                            t2.event_code,
                            t1.modified_by,
                            t1.last_modified_date
                        FROM sp_event_professional AS t1
                        INNER JOIN sp_events AS t2
                            ON t1.event_id = t2.event_id
                        WHERE t1.event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($eventProfessionalSql))) {
                            $eventProfessionalDtls = $this->fetch_all_array($eventProfessionalSql);
                        }
                        
                        // Update Event Professional
                        $UpdateEveProfessional = "UPDATE sp_event_professional SET status = '" . $status . "', modified_by = '" . $login_user_id."', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $UpdateEveProfessionalstatus = $this->query($UpdateEveProfessional);

                        // Create array of updated professional details
                        if (!empty($UpdateEveProfessionalstatus) && !empty($eventProfessionalDtls)) {
                            foreach ($eventPlanOfcareDtls AS $valEventProfessional) {
                             $UpdateEveProfessionalArr[] = "Event professional details updated of record " . $valEventProfessional['event_professional_id'] . " as follows \r\n" .
                                " status is change from " . $valEventProfessional['status'] . " to " . $status . "\r\n" .
                                " modified_by is change from " . $valEventProfessional['modified_by'] . " to " . $login_user_id . "\r\n" .
                                " last_modified_date is change from " .  $valEventProfessional['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                " for the event " . $valEventProfessional['event_code'] . "\r\n";
                            }  
                        }

                        // Get event plan of care details
                        $eventRequirementSql = "SELECT t1.event_requirement_id,
                            t1.status,
                            t2.event_code,
                            t1.last_modified_by,
                            t1.last_modified_date
                        FROM sp_event_requirements AS t1
                        INNER JOIN sp_events AS t2
                            ON t1.event_id = t2.event_id
                        WHERE t1.event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($eventRequirementSql))) {
                            $eventRequirementDtls = $this->fetch_all_array($eventRequirementSql);
                        }
                        
                        //Update Event Requirement
                        $UpdateEveRequirement = "UPDATE sp_event_requirements SET status = '" . $status . "', last_modified_by = '" . $login_user_id."', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $UpdateEveRequirementStatus = $this->query($UpdateEveRequirement);

                        // Create array of updated event requirement details
                        if (!empty($UpdateEveRequirementStatus) && !empty($eventRequirementDtls)) {
                            foreach ($eventPlanOfcareDtls AS $valEventRequirement) {
                             $UpdateEveRequirementArr[] = "Event requirement details updated of record " . $valEventRequirement['event_requirement_id'] . " as follows \r\n" .
                                " status is change from " . $valEventRequirement['status'] . " to " . $status . "\r\n" .
                                " last_modified_by is change from " . $valEventRequirement['last_modified_by'] . " to " . $login_user_id . "\r\n" .
                                " last_modified_date is change from " .  $valEventRequirement['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                " for the event " . $valEventRequirement['event_code'] . "\r\n";
                            }  
                        }

                        // Get event plan of care details
                        $eventSharewithHCMSql = "SELECT t1.event_share_id,
                            t1.status,
                            t2.event_code,
                            t1.modified_by,
                            t1.last_modified_date
                        FROM sp_event_share_hcm AS t1
                        INNER JOIN sp_events AS t2
                            ON t1.event_id = t2.event_id
                        WHERE t1.event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($eventSharewithHCMSql))) {
                            $eventSharewithHCMDtls = $this->fetch_all_array($eventSharewithHCMSql);
                        }
                        
                        //Update Event Share with HCM
                        $UpdateEveSharewithHCM = "UPDATE sp_event_share_hcm SET status = '" . $status . "', modified_by = '" . $login_user_id."', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $UpdateEveSharewithHCMStatus = $this->query($UpdateEveSharewithHCM);

                        // Create array of updated event requirement details
                        if (!empty($UpdateEveSharewithHCMStatus) && !empty($eventSharewithHCMDtls)) {
                            foreach ($eventSharewithHCMDtls AS $valEventSharewithHCM) {
                             $UpdateEveSharewithHCMArr[] = "Event share with HCM details updated of record " . $valEventSharewithHCM['event_share_id'] . " as follows \r\n" .
                                " status is change from " . $valEventSharewithHCM['status'] . " to " . $status . "\r\n" .
                                " modified_by is change from " . $valEventSharewithHCM['modified_by'] . " to " . $login_user_id . "\r\n" .
                                " last_modified_date is change from " .  $valEventSharewithHCM['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                " for the event " . $valEventSharewithHCM['event_code'] . "\r\n";
                            }  
                        }                        

                        // Get Job Closure Details
                        $GetJobClosureSql = "SELECT job_closure_id,
                                job_closure_file,
                                status,
                                modified_by,
                                last_modified_date
                            FROM sp_job_closure 
                            WHERE event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($GetJobClosureSql))) {
                            $GetJobClosure = $this->fetch_all_array($GetJobClosureSql);
                            foreach ($GetJobClosure AS $valJobClosure) {
                                // Update Job Closure Consumption
                                $UpdateJobClosureConsumption = "UPDATE sp_job_closure_consumption_mapping SET status = '" . $status . "',modified_by = '" . $login_user_id."',last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE event_id = '" . $valEvents['event_id'] . "'";
                                $this->query($UpdateJobClosureConsumption);
                            }
                        }

                        // Update Job Closure
                        $UpdateJobClosure = "UPDATE sp_job_closure SET status = '" . $status . "', modified_by = '" . $login_user_id."',last_modified_date = '" . date('Y-m-d H:i:s')."' WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $UpdateJobClosureStatus = $this->query($UpdateJobClosure);

                        // Create array of updated event job closure details
                        if (!empty($UpdateJobClosureStatus) && !empty($GetJobClosure)) {
                            foreach ($GetJobClosure AS $valJobClosure) {
                                $UpdateJobClosureArr[] = "Event job closure details updated of record " . $valJobClosure['job_closure_id'] . " as follows \r\n" .
                                    " status is change from " . $valJobClosure['status'] . " to " . $status . "\r\n" .
                                    " modified_by is change from " . $valJobClosure['modified_by'] . " to " . $login_user_id . "\r\n" .
                                    " last_modified_date is change from " .  $valJobClosure['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                    " for the event " . $valEventSharewithHCM['event_code'] . "\r\n";
                            }  
                        }

                        // Get Dependent Event Details
                        $getDependentEventSql = "SELECT event_id,
                                event_code,
                                status,
                                isDelStatus,
                                last_modified_by,
                                last_modified_date
                            FROM sp_events 
                            WHERE purpose_event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($getDependentEventSql))) {
                            $dependentEventDtls = $this->fetch_all_array($getDependentEventSql);
                        }

                        // Update Dependent Event
                        $UpdateDependentEvents = "UPDATE sp_events SET status = '" . $status . "', isDelStatus = '" . $pre_status."',last_modified_by = '" . $login_user_id."',last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE purpose_event_id = '" . $valEvents['event_id'] . "'";
                        $UpdateDependentEventsStatus = $this->query($UpdateDependentEvents);

                        // Create array of updated dependent event details
                        if (!empty($UpdateDependentEventsStatus) && !empty($dependentEventDtls)) {
                            foreach ($dependentEventDtls AS $valDependentEvent) {
                                $UpdateDependentEventArr[] = "Dependent event details updated of record " . $valDependentEvent['event_id'] . " as follows \r\n" .
                                " status is change from " . $valDependentEvent['status'] . " to " . $status . "\r\n" .
                                " isDelStatus is change from " . $valDependentEvent['isDelStatus'] . " to " . $pre_status . "\r\n" . 
                                " modified_by is change from " . $valDependentEvent['modified_by'] . " to " . $login_user_id . "\r\n" .
                                " last_modified_date is change from " .  $valDependentEvent['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                " for the event " . $valDependentEvent['event_code'] . "\r\n";
                            }  
                        }

                        // Get Event Details
                        $getEventSql = "SELECT event_id,
                                event_code,
                                status,
                                isDelStatus,
                                last_modified_by,
                                last_modified_date
                            FROM sp_events 
                            WHERE event_id = '" . $valEvents['event_id'] . "'";

                        if ($this->num_of_rows($this->query($getEventSql))) {
                            $eventDtls = $this->fetch_all_array($getEventSql);
                        }

                        // Update Event 
                        $UpdateEvents = "UPDATE sp_events SET status = '" . $status . "', isDelStatus = '" . $pre_status."',last_modified_by = '" . $login_user_id."',last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE event_id = '" . $valEvents['event_id'] . "'";
                        $UpdateEventStatus = $this->query($UpdateEvents);

                        // Create array of updated event details
                        if (!empty($UpdateEventStatus) && !empty($eventDtls)) {
                            foreach ($eventDtls AS $valEvent) {
                                $UpdateEventArr[] = "Event details  updated of record " . $valEvent['event_id'] . " are as follows \r\n" .
                                " status is change from " . $valEvent['status'] . " to " . $status . "\r\n" .
                                " isDelStatus is change from " . $valEvent['isDelStatus'] . " to " . $pre_status . "\r\n" . 
                                " modified_by is change from " . $valEvent['modified_by'] . " to " . $login_user_id . "\r\n" .
                                " last_modified_date is change from " .  $valEvent['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n" .
                                " for the event " . $valEvent['event_code'] . "\r\n";
                            }  
                        }
                    }   
                }

                // Update patient
                $UpdateStatusSql = "UPDATE sp_patients SET status = '" . $status . "', isDelStatus = '" . $pre_status . "', last_modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE patient_id = '" . $patient_id . "'";
            }
            
            $RecordId = $this->query($UpdateStatusSql);

            if (!empty($RecordId)) {
                
                $updatePatientArr[] = "Patient details updated of record ". $patientDtls['patient_id'] . "are as follows \r\n" .
                " status is change from " . $patientDtls['status'] . " to " . $status . "\r\n" .
                " isDelStatus is change from " . $patientDtls['isDelStatus'] . " to " . $pre_status . "\r\n" . 
                " last_modified_by is change from " . $patientDtls['last_modified_by'] . " to " . $login_user_id . "\r\n" .
                " last_modified_date is change from " .  $patientDtls['last_modified_date'] . " to " .  date('Y-m-d H:i:s'). "\r\n";


                $resultantArr = array_merge($UpdateEveDocMapArr, $UpdateEveJobSummaryArr, 
                    $UpdatePlanofcareArr, $UpdateEveProfessionalArr, 
                    $UpdateEveRequirementArr, $UpdateEveSharewithHCMArr,
                    $UpdateJobClosureArr, $UpdateDependentEventArr,
                    $UpdateEventArr,
                    $updatePatientArr
                );

                // Add activity log while changing patient status
                if (!empty($resultantArr)) {
                    $param = array();
                    $param['patient_id']   = $patient_id;
                    $param['record_data']  = $resultantArr;
                    $this->addActivity($param);
                    unset($param);
                }
            }

            return $RecordId;
        }
        else {
            return 0;
        }
    }

    public function GetPatientEventList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $join="";
        
        $search_value= $this->escape($arg['search_Value']);
        $SearchByPurpose= $this->escape($arg['SearchByPurpose']);
        $SearchByEmployee= $this->escape($arg['SearchByEmployee']);
        $SearchByProfessional= $this->escape($arg['SearchByProfessional']);
        $SearchByService= $this->escape($arg['SearchByService']);
        
        if ($arg['SearchfromDate'])
            $SearchfromDate= date('Y-m-d',strtotime($arg['SearchfromDate'])); 
        if ($arg['SearchToDate'])
            $SearchToDate= date('Y-m-d',strtotime($arg['SearchToDate'])); 
        
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $patient_id=$this->escape($arg['patient_id']);
        $isTrash=$this->escape($arg['isTrash']);
        
        if (!empty($search_value) && $search_value !='null')
        {
           $preWhere=" AND (se.event_code LIKE '%".$search_value."%' OR se.event_date LIKE '%".$search_value."%' OR se.note LIKE '%".$search_value."%' OR se.description LIKE '%".$search_value."%')"; 
        }
        if ((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .=" ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        if (!empty($patient_id) && $patient_id !='null')
        {
           $preWhere .=" AND se.patient_id='".$patient_id."'";
        }
        
        if ($SearchfromDate && $SearchToDate=='')
        {
            $daterange = " AND DATE_FORMAT(se.event_date,'%Y-%m-%d')  = '".$SearchfromDate."' ";
        }
        if ($SearchfromDate && $SearchToDate)
        {
            $daterange .= " AND DATE_FORMAT(se.event_date,'%Y-%m-%d') >= '".$SearchfromDate."'  AND DATE_FORMAT(se.event_date,'%Y-%m-%d') <= '".$SearchToDate."'";
        }
        
        if (!empty($isTrash) && $isTrash !='null')
        {
           $preWhere .=" AND se.status='3'"; 
        }
        else 
        {
          $preWhere .=" AND se.status IN ('1','2')";   
        }
        
        if (!empty($SearchByProfessional) && $SearchByProfessional !='null')
        {
             $join= " INNER JOIN sp_event_professional sep ON sep.event_id=se.event_id";
             $preWhere .= " AND sep.professional_vender_id='".$SearchByProfessional."' AND sep.plan_of_care_id !='0' ";
        }
        
        if (!empty($SearchByService) && $SearchByService !='null')
        {
             $join .= " INNER JOIN sp_event_requirements er ON er.event_id=se.event_id";
             $preWhere .= " AND er.service_id='".$SearchByService."'";
        }
        
        if (!empty($SearchByPurpose) && $SearchByPurpose !='null')
        {
            $preWhere .= " AND se.purpose_id='".$SearchByPurpose."'";
        }
        
        $PatientEventsSql = "SELECT se.event_id,se.event_code,se.caller_id,se.patient_id,se.purpose_id,se.event_date,se.note,se.description,se.status,se.event_status,se.isDelStatus,se.estimate_cost,se.added_date FROM sp_events AS se LEFT JOIN sp_patients as sp ON se.patient_id = sp.patient_id ".$join."  WHERE 1 ".$preWhere." ".$daterange." ".$filterWhere." ";
        $this->result = $this->query($PatientEventsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($PatientEventsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Purpose Detail
                if (!empty($val_records['purpose_id']))
                {
                   $PurposeSql = "SELECT purpose_id,name FROM sp_purpose_call WHERE purpose_id='".$val_records['purpose_id']."'";
                   $PurposeDtls=$this->fetch_array($this->query($PurposeSql));
                   $val_records['purposeNm']=$PurposeDtls['name']; 
                }
                // Getting Caller Details
                
                if (!empty($val_records['caller_id']))
                {
                   $sql_caller = "SELECT name,first_name,middle_name,professional_id,consultant_id FROM sp_callers WHERE caller_id='".$val_records['caller_id']."'";
                   $row_caller=$this->fetch_array($this->query($sql_caller));
                   if (!empty($row_caller['professional_id']))
                   {
                      $sql_professional = "SELECT name,first_name,middle_name FROM sp_service_professionals WHERE service_professional_id='".$row_caller['professional_id']."'";
                      $professionalresult=$this->fetch_array($this->query($sql_professional)); 
                      if (!empty($professionalresult))
                      {
                        $val_records['callerLName']=$professionalresult['name']; 
                        $val_records['callerFName']=$professionalresult['first_name'];
                        $val_records['callerMName']=$professionalresult['middle_name'];
                      }
                       unset($professionalresult);
                   }
                   else if (!empty($row_caller['consultant_id']))
                   {
                      $sql_consultant = "SELECT name,first_name,middle_name FROM sp_doctors_consultants WHERE doctors_consultants_id='".$row_caller['consultant_id']."'"; 
                      $consultantresult=$this->fetch_array($this->query($sql_consultant));
                      if (!empty($consultantresult))
                      {
                        $val_records['callerLName']=$consultantresult['name']; 
                        $val_records['callerFName']=$consultantresult['first_name'];
                        $val_records['callerMName']=$consultantresult['middle_name'];
                      }
                      unset($consultantresult);
                   }
                   else 
                   {
                        $val_records['callerLName']= $row_caller['name']; 
                        $val_records['callerFName']=$row_caller['first_name'];
                        $val_records['callerMName']=$row_caller['middle_name'];
                   }
                }
                // Getting Status
                if (!empty($val_records['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $val_records['statusVal']=$StatusArr[$val_records['status']];
                }
                
                // If Note and description not available
                if (empty($val_records['note']))
                  $val_records['note']="Not Available";
                else 
                  $val_records['note']= $val_records['note']; 
                    
                if (empty($val_records['description']))
                  $val_records['description']="Not Available";
                else 
                  $val_records['description']= $val_records['description']; 

                // Getting All Dependent Data From Event id
                $arr['event_id']=$val_records['event_id'];
                $val_records['dependent']=$this->GetDependentEvent($arr);
                unset($arr);
                $this->resultPatientEvents[]=$val_records;
                
            }
            $resultArray['count'] = $pager->total_rows;
        }
        
        //echo '<pre>';
       // print_r($this->resultPatientEvents);
       // echo '</pre>';
       // exit;
        
        
        
        if (count($this->resultPatientEvents))
        {
            $resultArray['data']=$this->resultPatientEvents;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function SearchCallerHistory($arg)
    {
        $preWhere="";
        $filterWhere="";
        $phone_no= trim($this->escape($arg['phone_no']));
         $filter_type=$this->escape($arg['filter_type']);
        if ($phone_no)
            $preWhere .= " AND phone_no = '".$phone_no."'";
        
        $PatientsSql = "SELECT caller_id FROM sp_callers WHERE 1 AND status='1' ".$preWhere." ";
        //echo $PatientsSql;
        $this->result = $this->query($PatientsSql);
        if ($this->num_of_rows($this->result))
        {
            $val_records=$this->fetch_array($this->query($PatientsSql));
            
           // $pager = new PS_Pagination($PatientsSql,$arg['pageSize'],$arg['pageIndex'],'');
            //$all_records= $pager->paginate();
           // while($val_records=$this->fetch_array($all_records))
            //{
                // Getting Record Detail

                $RecordSql_event= "SELECT event_code,patient_id,event_id,purpose_id,added_date FROM sp_events WHERE caller_id='".$val_records['caller_id']."'";
                $RecordResult_event=$this->fetch_array($this->query($RecordSql_event));
               // $patient_id = $RecordResult_event['patient_id'];
               // $event_code = $RecordResult_event['event_code'];
                $this->result = $this->query($RecordSql_event);
                if ($this->num_of_rows($this->result))
                {
                    $pager = new PS_Pagination($RecordSql_event,$arg['pageSize'],$arg['pageIndex'],'');
                    $all_records= $pager->paginate();
                    while($val_record=$this->fetch_array($all_records))
                    {
                        // If landline Number Not Available
                        $event_code = $val_record['event_code'];
                        $purpose_id = $val_record['purpose_id'];
                        $RecordResult['added_date'] = $val_record['added_date'];
                        if (empty($val_record['patient_id']))
                        $val_record['patient_id']='Not Available';

                        $RecordSql = "SELECT patient_id,hhc_code,name,first_name,middle_name,email_id,residential_address,location_id,phone_no,mobile_no,dob,status,isDelStatus,added_date FROM sp_patients WHERE patient_id='".$val_record['patient_id']."'";
                        $RecordResult=$this->fetch_array($this->query($RecordSql));
                            
                        //GETTING PURPOSE OF CALL
                        $PurposeSql = "SELECT * FROM sp_purpose_call WHERE purpose_id='".$purpose_id."' AND status='1' ";
                        $PurposeSqlDtls=$this->fetch_array($this->query($PurposeSql));
                        $RecordResult['purpose_name']=$PurposeSqlDtls['name']; 
                        
                        // Getting Location Name
                        if (!empty($RecordResult['location_id']))
                        {
                        $LocationSql = "SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$RecordResult['location_id']."'";
                        $LocationDtls=$this->fetch_array($this->query($LocationSql));
                        $RecordResult['locationNm']=$LocationDtls['location']; 
                        $RecordResult['LocationPinCode']=$LocationDtls['pin_code']; 
                        }
                        
                        // Getting Status
                        if (!empty($RecordResult['status']))
                        {
                            $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                            $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                        }
                        
                        // check is it any event log present for this patient
                        $RecordResult['event_code'] = $event_code;
                        $chk_event_log_sql = "SELECT event_id FROM sp_events WHERE patient_id='".$RecordResult['patient_id']."'";
                        if ($this->num_of_rows($this->query($chk_event_log_sql)))
                        $RecordResult['isEvents']= "1";
                        else 
                        $RecordResult['isEvents']= "0"; 

                        $this->resultPatients[]=$RecordResult;

                    } 
                }
         //}
            $resultArray['count'] = $pager->total_rows;
        }
        if (count($this->resultPatients))
        {
            $resultArray['data']=$this->resultPatients;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function SearchPatients($arg)
    {
        $preWhere="";
        $filterWhere="";
        //$search_value= $this->escape($arg['search_Value']);
        $existing_hhc_code= trim($this->escape($arg['existing_hhc_code']));
        $existing_patient_name= trim($this->escape($arg['existing_patient_name']));
        $existing_mobile_no= trim($this->escape($arg['existing_mobile_no']));
        $ex_landline_no= trim($this->escape($arg['ex_landline_no']));
        $ex_dob= trim($this->escape($arg['ex_dob']));
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        
        if ($existing_hhc_code)
            $preWhere .= " AND hhc_code LIKE '%".$existing_hhc_code."%'";
        if ($existing_patient_name)
            $preWhere .= " AND CONCAT(name,' ',first_name,' ',middle_name) LIKE '%".$existing_patient_name."%'";
        if ($existing_mobile_no)
            $preWhere .= " AND mobile_no LIKE '%".$existing_mobile_no."%'";
        if ($ex_landline_no)
            $preWhere .= " AND phone_no LIKE '%".$ex_landline_no."%'";
        if ($ex_dob)
            $preWhere .= " AND DATE_FORMAT(dob,'%Y-%m-%d') LIKE '%".$ex_dob."%'";
        
        if ((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .="ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        
        
        $PatientsSql = "SELECT patient_id FROM sp_patients WHERE 1 AND status='1' ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($PatientsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($PatientsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql = "SELECT patient_id,hhc_code,name,first_name,middle_name,email_id,residential_address,location_id,phone_no,mobile_no,dob,status,isDelStatus,added_date FROM sp_patients WHERE patient_id='".$val_records['patient_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // If landline Number Not Available
                if (empty($RecordResult['phone_no']))
                    $RecordResult['phone_no']='Not Available';
                
                // If Birth Date Not Available
                if (!empty($RecordResult['dob']) && $RecordResult['dob'] !='0000-00-00')
                    $RecordResult['dob']=date('d M Y',strtotime($RecordResult['dob']));  
                else 
                   $RecordResult['dob']='Not Available';

                // Getting Location Name
                if (!empty($RecordResult['location_id']))
                {
                   $LocationSql = "SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$RecordResult['location_id']."'";
                   $LocationDtls=$this->fetch_array($this->query($LocationSql));
                   $RecordResult['locationNm']=$LocationDtls['location']; 
                   $RecordResult['LocationPinCode']=$LocationDtls['pin_code']; 
                }
                
                // Getting Status
                if (!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                
                // check is it any event log present for this patient
                
                $chk_event_log_sql = "SELECT event_id FROM sp_events WHERE patient_id='".$RecordResult['patient_id']."'";
                if ($this->num_of_rows($this->query($chk_event_log_sql)))
                   $RecordResult['isEvents']= "1";
                else 
                   $RecordResult['isEvents']= "0"; 

                $this->resultPatients[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if (count($this->resultPatients))
        {
            $resultArray['data']=$this->resultPatients;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    
    public function GetDependentEvent($arg)
    {
        $event_id=$this->escape($arg['event_id']);
        $DependentEventsSql = "SELECT event_id,event_code,caller_id,patient_id,purpose_id,event_date,note,description,status,event_status,isDelStatus,estimate_cost,added_date FROM sp_events WHERE purpose_event_id='".$event_id."'";
        if ($this->num_of_rows($this->query($DependentEventsSql)))
        {  
           $EventResult=$this->fetch_array($this->query($DependentEventsSql));
           if (!empty($EventResult))
           {
               // Getting Purpose Detail
                if (!empty($EventResult['purpose_id']))
                {
                   $PurposeSql = "SELECT purpose_id,name FROM sp_purpose_call WHERE purpose_id='".$EventResult['purpose_id']."'";
                   $PurposeDtls=$this->fetch_array($this->query($PurposeSql));
                   $EventResult['purposeNm']=$PurposeDtls['name']; 
                }
                // Getting Caller Details
                if (!empty($EventResult['caller_id']))
                {
                   $CallerSql = "SELECT caller_id,name,first_name,middle_name FROM sp_callers WHERE caller_id='".$EventResult['caller_id']."'";
                   $CallerDtls=$this->fetch_array($this->query($CallerSql));
                   $EventResult['callerNm']=$CallerDtls['name']." ".$CallerDtls['first_name']." ".$CallerDtls['middle_name']; 
                }
                // Getting Status
                if (!empty($EventResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $EventResult['statusVal']=$StatusArr[$EventResult['status']];
                }
                
                // If Note and description not available
                if (empty($EventResult['note']))
                  $EventResult['note']="Not Available";
                else 
                  $EventResult['note']= $EventResult['note']; 
                    
                if (empty($EventResult['description']))
                  $EventResult['description']="Not Available";
                else 
                  $EventResult['description']= $EventResult['description']; 

               return $EventResult;
           }
           else 
               return 0;  
        } 
        else 
            return 0;
    }
    
    public function GetEventListByService($arg)
    {
        /* General Information
         *  sp_events t1
         *  sp_event_requirements t2
         *  sp_event_plan_of_care t3
         *  sp_patients t4
         */
        
        $preWhere="";
        $filterWhere="";
        $join="";
        
        $search_value= $this->escape($arg['search_Value']);
        $SearchByPurpose= "1";
        $SearchByService= "3";
        $SearchByProfessional= $this->escape($arg['SearchByProfessional']);
        
        $DateArr=array();
        $TimeArr=array();
        $TempArr=array();
        $ResulatantArr=array();
        
        if ($arg['SearchfromDate'])
            $SearchfromDate= date('Y-m-d',strtotime($arg['SearchfromDate'])); 
        if ($arg['SearchToDate'])
            $SearchToDate= date('Y-m-d',strtotime($arg['SearchToDate'])); 
        
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $patient_id=$this->escape($arg['patient_id']);
        $isTrash=$this->escape($arg['isTrash']);
        
        if (!empty($search_value) && $search_value !='null')
        {
           $preWhere=" AND (t1.event_code LIKE '%".$search_value."%' OR t4.name LIKE '%".$search_value."%' OR  t4.first_name LIKE '%".$search_value."%' OR t4.middle_name LIKE '%".$search_value."%')"; 
        }
        if ((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .=" ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        
        if (!empty($SearchByProfessional) && $SearchByProfessional !='null')
        {
             $preWhere .= " AND t5.professional_vender_id='".$SearchByProfessional."'";
        }
        
        
         $plan_of_care_sql = "SELECT t3.plan_of_care_id,t3.event_id,t1.event_code,t4.name,t4.first_name,t4.middle_name,t3.event_requirement_id,t3.service_date,t3.service_date_to,t3.start_date,t3.end_date FROM sp_event_plan_of_care t3 INNER JOIN sp_event_requirements t2 ON t3.event_requirement_id=t2.event_requirement_id ". 
                          " INNER JOIN sp_event_professional t5 ON t5.event_requirement_id=t2.event_requirement_id ".
                          " INNER JOIN sp_events t1 ON t1.event_id=t2.event_id ".
                          " INNER JOIN sp_patients t4 ON t4.patient_id=t1.patient_id ".
                          " WHERE t3.status='1' AND t2.service_id='16' AND t5.professional_vender_id !='null' AND 1  ".$preWhere." ".$daterange." ".$filterWhere." ";
     
     

        if ($this->num_of_rows($this->query($plan_of_care_sql)))
        {
           $Plan_of_care_Result=$this->fetch_all_array($plan_of_care_sql);
           
           $ValPlans['recommomded_service']="";
           
           foreach ($Plan_of_care_Result AS $key=>$ValPlans)
           { 
               // Getting Recommonded Service Name 
               
               $Sub_Service_Sql = "SELECT ss.recommomded_service FROM sp_event_requirements er INNER JOIN sp_sub_services ss ON  ss.sub_service_id=er.sub_service_id WHERE er.event_requirement_id='".$ValPlans['event_requirement_id']."'";
               if ($this->num_of_rows($this->query($Sub_Service_Sql)))
               {
                   $Sub_service=$this->fetch_array($this->query($Sub_Service_Sql));
                   $ValPlans['recommomded_service']=$Sub_service['recommomded_service'];
               }
               
               // Getting Service Professional Name 
               
               $service_professional_sql = "SELECT sp.name,sp.first_name,sp.middle_name FROM sp_service_professionals sp INNER JOIN sp_event_professional ep ON sp.service_professional_id =ep.professional_vender_id WHERE ep.event_requirement_id='".$ValPlans['event_requirement_id']."'";
               if ($this->num_of_rows($this->query($service_professional_sql)))
               {
                   $service_prof=$this->fetch_array($this->query($service_professional_sql));
                   $ValPlans['service_professional']=$service_prof['name']." ".$service_prof['first_name']." ".$service_prof['middle_name'];
               }
               
               // Getting All Plan of care records
               
               $arr['event_id']=$ValPlans['event_id'];
               $arr['event_code']=$ValPlans['event_code'];
               $arr['patient_name']=$ValPlans['name']." ".$ValPlans['first_name']." ".$ValPlans['middle_name'];
               $arr['service_professional']=$ValPlans['service_professional'];
               $arr['recommomded_service']=$ValPlans['recommomded_service'];
               $arr['service_start_date']=$ValPlans['service_date'];
               $arr['service_date_to']=$ValPlans['service_date_to'];
               $arr['start_date']=$ValPlans['start_date'];
               $arr['end_date']=$ValPlans['end_date'];
               // Getting All Records 
               $AllRecords=$this->GetPlanOfCareRecords($arr); 
               unset($arr);
               if (!empty($AllRecords))
               {
                   $ResulatantArr[]=$AllRecords;
               }
               unset($AllRecords);  
           }
           
           if (!empty($ResulatantArr))
           {
              // Combine all records in one array 
               $result=array();
               for($l=0;$l<count($ResulatantArr);$l++)
               {
                    for($m=0;$m<count($ResulatantArr[$l]);$m++)
                    {
                        $result[] =$ResulatantArr[$l][$m];
                    }
                    
               }
               if (!empty($result))
               {
                   return $result;
               }
               else 
               {
                  return 0; 
               }    
           }
           else 
           {
               return 0;
           }
        }
        else 
        {
            return 0;
        }
    }
    
    public function GetPlanOfCareRecords($arg)
    {
        $ResultantData=array();
        $service_start_date=$arg['service_start_date'];
        $service_end_date=$arg['service_date_to'];
        $diff = (strtotime($service_end_date)- strtotime($service_start_date))/24/3600; 
        $dateDiff = $diff+1;
        if ($dateDiff)
        {
            for($i=0;$i<$dateDiff;$i++)
            {
               $DateArr[]=date('Y-m-d',strtotime($service_start_date . "+$i days"));
               $TimeArr[]=$arg['start_date']." TO ".$arg['end_date'];
            }
        }
        if (!empty($DateArr))
        {
            for($j=0;$j<count($DateArr);$j++)
            {
               $TempArr['event_id']=$arg['event_id'];
               $TempArr['event_code']=$arg['event_code'];
               $TempArr['patient_name']=$arg['patient_name'];
               $TempArr['service_professional']=$arg['service_professional'];
               $TempArr['recommomded_service']=$arg['recommomded_service'];
               $TempArr['service_date']= $DateArr[$j];
               $TempArr['service_time']= $TimeArr[$j];     
               
               $ResultantData[]=$TempArr;
               unset($TempArr);
            }
            if (!empty($ResultantData))
            {
                return $ResultantData;
            }
            else 
            {
                return 0;
            }  
        }
        else 
        {
            return 0;
        }
    }
    
    /**
     * This function is used for change patient vip status
     *
     * @param array $arg
     * 
     * @return int $recordId
     *
     */
    public function ChangeVIPStatus($arg)
    {
        $patient_id    = $this->escape($arg['patient_id']);
        $status        = $this->escape($arg['status']);
        $login_user_id = $this->escape($arg['login_user_id']);

        $ChkPatientSql = "SELECT patient_id,
            isVIP,
            last_modified_by,
            last_modified_date
        FROM sp_patients 
        WHERE patient_id = '" . $patient_id . "' ";

        if ($this->num_of_rows($this->query($ChkPatientSql))) {
            $patientDtls = $this->fetch_array($this->query($ChkPatientSql));
            // Update pateint status
            $UpdateVIPStatusSql = "UPDATE sp_patients SET isVIP = '" . $status . "', last_modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE patient_id = '" . $patient_id . "'";
            $recordId = $this->query($UpdateVIPStatusSql);

            if (!empty($recordId) && !empty($patientDtls)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '12';
                $insertActivityArr['module_name']   = 'Manage Patients';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Patients details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "isVIP is change from " . $patientDtls['isVIP'] . " to " . $status . "\r\n";
                $activityDesc .= "last_modified_by is change from " . $patientDtls['last_modified_by'] . " to " . $login_user_id . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $patientDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
            }
            return $recordId;
        } else {
            return 0;
        }
    }

     /**
     *
     * This function is used for add activity
     *
     * @param array $args
     *
     * @return int $recordId
     *
     */
    public function addActivity($args)
    {
        $recordId = 0;
        if (!empty($args)) {
            $patientId   = $args['patient_id'] ;
            $insertData   = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '12';
            $insertActivityArr['module_name']   = 'Manage Patients';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Patient details " . ( $patientId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($insertData)) {
                $myString = implode(",", $insertData);
                $activityDesc .= rtrim($myString, ',');
            }
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
    }
}
//END
?>