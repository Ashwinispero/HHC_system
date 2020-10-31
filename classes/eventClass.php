<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class eventClass extends AbstractDB 
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
    public function InsertCallers($arg)
    {
        $insertData['name']            = $arg['name'];
        $insertData['first_name']      = $arg['first_name'];
        $insertData['middle_name']     = $arg['middle_name'];
        $insertData['purpose_id']      = $arg['purpose_id'];
        $insertData['phone_no']        = $arg['phone_no'];
        $insertData['professional_id'] = $arg['professional_id'];
        $insertData['consultant_id']   = $arg['caller_consultant_id'];
        $employee_id                   = $arg['employee_id'];
        $CallUniqueID                  = $arg['CallUniqueID'];
        /* ---------- Edit Event Log     --------- */
        $Edit_CallerId = $arg['Edit_CallerId'];
        $Edit_event_id = $arg['Edit_event_id'];
        /* ---------- Edit Event Log     --------- */
        if ($Edit_CallerId)
        {
            $insertData['last_modified_by']   = $employee_id;
            $insertData['last_modified_date'] = date('Y-m-d H:i:s');

            // Get existing caller details
            $callerDtlsSql = "SELECT name,
                    first_name,
                    middle_name,
                    relation,
                    phone_no,
                    purpose_id,
                    professional_id,
                    consultant_id,
                    last_modified_by,
                    last_modified_date
                FROM sp_callers
                WHERE caller_id = '" . $Edit_CallerId . "' ";

            if (mysql_num_rows($this->query($callerDtlsSql))) {
                $callerDtls = $this->fetch_array($this->query($callerDtlsSql));
            }

            $where = "caller_id ='".$Edit_CallerId."' ";
            $this->query_update('sp_callers',$insertData,$where);
            $RecordId = $Edit_CallerId;

            // Caller details modification activity log
            $insertActivityArr = array();

            $insertActivityArr['module_type'] = '1';
            $insertActivityArr['module_id']   = '';
            $insertActivityArr['module_name']   = 'Edit Caller Details';
            $insertActivityArr['purpose_id']   = $arg['purpose_id'];
            $insertActivityArr['event_id']   = $Edit_event_id;
            
            if (!empty($RecordId) && !empty($callerDtls)) {
                $result = array_diff_assoc($callerDtls, $insertData);

                if (!empty($result)) {
                    $messageStr = "";
                    foreach ($result AS $key => $valResult) {
                        $messageStr .= $key . " is changed from " . $valResult . " to " . $insertData[$key] . "\r\n";
                    }
                    $insertActivityArr['activity_description'] = (!empty($messageStr) ? nl2br($messageStr) : "");
                    $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                    $insertActivityArr['added_by_id']   = $employee_id;
                    $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');

                    $this->query_insert('sp_user_activity',$insertActivityArr);
                }  
            }

            $updateEditedData['relation'] = $arg['relation'];
            $updateEditedData['last_modified_by'] = $employee_id;
            $updateEditedData['last_modified_date'] = date('Y-m-d H:i:s');

            // event details modification activity log
            $getEventDtlsSql = "SELECT relation,
                    last_modified_by,
                    last_modified_date
                FROM sp_events
                WHERE event_id = '" . $Edit_event_id . "' ";

            if (mysql_num_rows($this->query($getEventDtlsSql))) {
                $eventDtls = $this->fetch_array($this->query($getEventDtlsSql));

                if (!empty($eventDtls)) {
                    $eventResult = array_diff_assoc($eventDtls, $updateEditedData);

                    if (!empty($eventResult)) {
                        $str = "";
                        foreach ($eventResult AS $key => $valEventResult) {
                            $str .= $key . " is changed from " . $valEventResult . " to " . $updateEditedData[$key] . "\r\n";
                        }
                        $insertActivityArr['activity_description'] = (!empty($str) ? nl2br($str) : "");
                        $this->query_insert('sp_user_activity',$insertActivityArr);
                    }
                }
            }

            $where = "event_id ='".$Edit_event_id."' ";
            $this->query_update('sp_events',$updateEditedData,$where);
            $EventId = $Edit_event_id;
            unset($insertActivityArr);
        }
        else
        {
            if ($arg['caller_consultant_id']) {
                 $preWhereC = " and consultant_id = '".$arg['caller_consultant_id']."'";
            } else if ($arg['professional_id']) {
                 $preWhereC = " and professional_id = '".$arg['professional_id']."'";
            } else {
                $preWhereC = " and phone_no = '".$arg['phone_no']."'";
            }

            $select_exist = "SELECT caller_id FROM sp_callers WHERE 1 ".$preWhereC."  ";

            if  (mysql_num_rows($this->query($select_exist))) {
                $insertData['last_modified_by'] = $employee_id;
                $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                $val_existRecord = $this->fetch_array($this->query($select_exist));

                // Added activity history for modification of consultant / professional details

                $where = "caller_id ='".$val_existRecord['caller_id']."' ";
                $this->query_update('sp_callers',$insertData,$where);
                $RecordId = $val_existRecord['caller_id'];
            } else {
                $insertData['attended_by'] = $employee_id;
                $insertData['added_date'] = date('Y-m-d H:i:s');
                $insertData['status'] = '1';

                // Added activity history for added event details
                $RecordId = $this->query_insert('sp_callers',$insertData);
            }

            //---------- create event ---------------//
            $createEvent['caller_id'] = $RecordId;
            $createEvent['relation'] = $arg['relation'];
            
            // Generate Random Number 
            $GetMaxRecordIdSql="SELECT MAX(event_id) AS MaxId FROM sp_events";
            if ($this->num_of_rows($this->query($GetMaxRecordIdSql))) {
                $MaxRecord=$this->fetch_array($this->query($GetMaxRecordIdSql));
                $getMaxRecordId=$MaxRecord['MaxId'];
            } else {
                $getMaxRecordId=0;
            }
            $prefix='E';
            $EventCode=Generate_Number($prefix,$getMaxRecordId);
            
            //Check is it generated event code is already exits
            
            
            $chk_event_code="SELECT event_id FROM sp_events WHERE event_code='".$EventCode."'";		
            if($this->num_of_rows($this->query($chk_event_code)))
            {
                $createEvent['event_code'] = 'E00'.$RecordId.''.$val;
            }
            else 
            {
                $createEvent['event_code'] = $EventCode;
            }

            $Invoice_narration='';
            $employee_record_query="SELECT hospital_id FROM sp_employees WHERE employee_id=$employee_id";
            $employeee_record = $this->fetch_array($this->query($employee_record_query));
            $hospital_id=$employeee_record['hospital_id'];
            $GetMaxbillIdSql=mysql_query("SELECT MAX(bill_no_ref_no) as bill_no_ref_no FROM sp_events where hospital_id='$hospital_id'") or die(mysql_error());
            $row = mysql_fetch_array($GetMaxbillIdSql) or die(mysql_error());
            $Maxbillid=$row['bill_no_ref_no'];
            $createEvent['purpose_id'] = $arg['purpose_id'];
            $createEvent['bill_no_ref_no'] = $Maxbillid + 1;
            $createEvent['event_date'] = date('Y-m-d H:i:s');
            $createEvent['status'] = '2';
            $createEvent['event_status'] = '1';
            $createEvent['added_by'] = $employee_id;
            $createEvent['Added_through'] = 1;
            $createEvent['Invoice_narration'] = $Invoice_narration;
            $createEvent['CallUniqueID'] = $CallUniqueID;
            $createEvent['added_date'] = date('Y-m-d H:i:s');
    
            
            $Hospital_branch=mysql_query("SELECT branch FROM sp_hospitals where hospital_id='$hospital_id'") or die(mysql_error());
            $row_Hospital_branch = mysql_fetch_array($Hospital_branch) or die(mysql_error());
            $branch_code=$row_Hospital_branch['branch'];
            
            $createEvent['branch_code'] = $branch_code;	
            $createEvent['hospital_id'] = $employeee_record['hospital_id'];	
            $EventId=$this->query_insert('sp_events',$createEvent);
            //unset($_SESSION["CallUniqueID"]);
           // $_SESSION["CallUniqueID"]='';
            // Added Activity Log
            if (!empty($EventId)) {
                $insertActivityArr = array();

                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name']   = 'Add Caller Details';
                $insertActivityArr['purpose_id']   = $arg['purpose_id'];
                $insertActivityArr['event_id']   = $EventId;
                $insertActivityArr['activity_description'] = "Caller details added successfully. New event (" .  $createEvent['event_code']  . ") is get created by " . $_SESSION['emp_nm'] . " ";
                $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']   = $employee_id;
                $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');

                $this->query_insert('sp_user_activity',$insertActivityArr);

                unset($insertActivityArr);
            }	
        }
        return $EventId.'>>'.$RecordId;
    }
    public function EventList($arg)
    { 
        $preWhere="";
        $filterWhere="";
        $isStatusWhere="";
        $join="";
        
        $employee_id= $this->escape($arg['employee_id']);
        $employee_type= $this->escape($arg['employee_type']);
        $employee_hospital_type= $this->escape($arg['hospital_id']);

        $SearchByPatients= trim($this->escape($arg['SearchByPatients']));
        $search_value= trim($this->escape($arg['search_Value']));
        $SearchKeyword_new = trim($this->escape($arg['SearchKeyword_new']));
        $SearchByPurpose= $this->escape($arg['SearchByPurpose']);
      //  $SearchByEmployee= $this->escape($arg['SearchByEmployee']);
        
        $SearchByProfessional= $this->escape($arg['SearchByProfessional']);
        
        if($arg['SearchfromDate'])
            $SearchfromDate= date('Y-m-d',strtotime($arg['SearchfromDate'])); 
        if($arg['SearchToDate'])
            $SearchToDate= date('Y-m-d',strtotime($arg['SearchToDate'])); 
            
        if($arg['SearchfromDate_service'])
            $SearchfromDate_service= date('Y-m-d',strtotime($arg['SearchfromDate_service'])); 
        if($arg['SearchToDate_service'])
            $SearchToDate_service= date('Y-m-d',strtotime($arg['SearchToDate_service']));
            
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isStatus=$this->escape($arg['isStatus']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere .="AND (se.event_code LIKE '%".$search_value."%' OR DATE_FORMAT(se.event_date,'%Y-%m-%d') LIKE '%".$search_value."%' OR sp.hhc_code LIKE '%".$search_value."%' OR CONCAT(sp.name, ' ',sp.first_name,' ',sp.middle_name) LIKE '%".$search_value."%')"; 
        }
        if(!empty($SearchKeyword_new) && $SearchKeyword_new !='null')
        {
           $preWhere .="AND (se.event_code LIKE '%".$SearchKeyword_new."%' OR DATE_FORMAT(se.event_date,'%Y-%m-%d') LIKE '%".$SearchKeyword_new."%' OR sp.hhc_code LIKE '%".$SearchKeyword_new."%' OR CONCAT(sp.name, ' ',sp.first_name,' ',sp.middle_name) LIKE '%".$SearchKeyword_new."%')"; 
        }
        
        
        if($SearchfromDate && $SearchToDate=='')
        {
            $daterange = " AND DATE_FORMAT(se.event_date,'%Y-%m-%d')  = '".$SearchfromDate."' ";
        }
        if($SearchfromDate && $SearchToDate)
        {
            $daterange .= " AND DATE_FORMAT(se.event_date,'%Y-%m-%d') >= '".$SearchfromDate."'  AND DATE_FORMAT(se.event_date,'%Y-%m-%d') <= '".$SearchToDate."'";
        }
        if((!empty($SearchByPatients) && $search_value !='null') || (!empty($search_value) && $search_value !='null'))
        {
            if(!empty($SearchByPatients))
                $preWhere .= " AND sp.patient_id = '".$SearchByPatients."'"; 
        }
        else 
        {
            if(!empty($isStatus))
            {
                if($isStatus=='1')  // For Main Event Log Page 
                {
                   $isStatusWhere="AND se.event_status IN('1','2','3','5') AND se.isArchive='1'";
                }
                if($isStatus=='2') // For Feedback Event Log Page
                {
                    $isStatusWhere .="AND se.event_status IN('4') AND se.isArchive='1'";
                }
                if($isStatus=='3') // For Archive Event Log Page
                {
                   $isStatusWhere .="AND se.event_status='5' AND se.isArchive='2'"; 
                } 
            } 
        }
        
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .="ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        /*
        if(!empty($SearchByEmployee) && $SearchByEmployee !='null')
        {
            $preWhere .= " AND se.added_by='".$SearchByEmployee."'";
        } 
         * 
         */
        
        if(!empty($SearchByProfessional) && $SearchByProfessional !='null')
        {
             $join .= " INNER JOIN sp_event_professional sep ON sep.event_id=se.event_id";
             $preWhere .= " AND sep.professional_vender_id='".$SearchByProfessional."'";
        }
        if(!empty($SearchByPurpose) && $SearchByPurpose !='null')
        {
            $preWhere .= " AND se.purpose_id='".$SearchByPurpose."'";
        }
        if($_SESSION['eventAccess'] == 'No')
        {
            if(!empty($employee_type) && $employee_type !='null')
            {
                if($employee_type !='1')
                {
                    $join .= " INNER JOIN sp_employees emp ON se.added_by=emp.employee_id";
                    $preWhere .= " AND emp.hospital_id='".$employee_hospital_type."'";
                } 
            }
        }
        $preAddedBy = '';
        
        //echo $_SESSION['eventAccess'];
       /* if($_SESSION['eventAccess'] == 'No')
        {
            $hospitalId = $_SESSION['employee_hospital_id'];
            $select_empHosp= "select employee_id from sp_employees where hospital_id = '".$hospitalId."' ";
            $ptrEmployess = $this->fetch_all_array($select_empHosp);
            foreach($ptrEmployess as $key=>$valEmpHospitals)
            {
                $employee_ids .= $valEmpHospitals['employee_id'].',';
            }
            $employees = rtrim($employee_ids,',');
            $preAddedBy = " and added_by IN ($employees)";
        }*/

        // Added this condition for load last 45 days records on the event list page
		if (empty($SearchByPatients) && empty($search_value) && empty($SearchByPurpose) &&
            empty($SearchByProfessional) && empty($SearchfromDate_service) && empty($SearchfromDate) && !empty($arg['listPageDefaultFilter'])) {
            $preWhere .= " AND DATE_FORMAT(se.event_date,'%Y-%m-%d') >= '" . date('Y-m-d', strtotime(' -45 day')) . "'  AND DATE_FORMAT(se.event_date,'%Y-%m-%d') <= '" . date('Y-m-d') . "'";
        }
        
        
        if($SearchfromDate_service && $SearchToDate_service)
        {
            $daterange_service .= " AND dtl_pln.Actual_Service_date BETWEEN '".$SearchfromDate_service."' AND '".$SearchToDate_service."' ";
            $RecordSql="SELECT callerno.phone_no,se.caller_id,calls.call_audio,se.CallUniqueID,se.event_id,se.event_code, se.caller_id,se.purpose_event_id,se.patient_id,sp.mobile_no,sp.name,sp.first_name,se.purpose_id,se.event_date,se.note,se.description,se.status,se.event_status,se.estimate_cost,se.finalcost,se.added_by,se.Added_through,se.added_date ,sp.hhc_code,se.isArchive, sp.isVIP, se.isConvertedService, se.enquiry_status, se.enquiry_cancellation_reason,Invoice_narration
                    FROM sp_events as se LEFT JOIN sp_patients as sp ON se.patient_id = sp.patient_id 
                    LEFT JOIN sp_detailed_event_plan_of_care as dtl_pln ON se.event_id = dtl_pln.event_id 
                    LEFT JOIN sp_incoming_call as calls ON se.CallUniqueID = calls.CallUniqueID
                    LEFT JOIN sp_callers as callerno ON se.caller_id = callerno.caller_id
                     ".$join."
                    WHERE 1 and se.status !='3' ".$isStatusWhere." ".$preWhere." ".$daterange_service." GROUP BY se.event_id ".$filterWhere." ";
       
        }
        else{
        $RecordSql="SELECT callerno.phone_no,se.caller_id,calls.call_audio,se.CallUniqueID,se.event_id,se.event_code, se.caller_id,se.purpose_event_id,se.patient_id,sp.mobile_no,sp.name,sp.first_name,se.purpose_id,se.event_date,se.note,se.description,se.status,se.event_status,se.estimate_cost,se.finalcost,se.added_by,se.Added_through,se.added_date ,sp.hhc_code,se.isArchive, sp.isVIP, se.isConvertedService, se.enquiry_status, se.enquiry_cancellation_reason,Invoice_narration
                    FROM sp_events as se LEFT JOIN sp_patients as sp ON se.patient_id = sp.patient_id
                    LEFT JOIN sp_incoming_call as calls ON se.CallUniqueID = calls.CallUniqueID
                    LEFT JOIN sp_callers as callerno ON se.caller_id = callerno.caller_id
                     ".$join."
                    WHERE 1 and se.status !='3' ".$isStatusWhere." ".$preWhere." ".$daterange." GROUP BY se.event_id ".$filterWhere." ";
        }
        //echo '<pre>';
        //print_r($RecordSql);
        //echo '</pre>';

        
        $this->result = $this->query($RecordSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($RecordSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                if(!empty($val_records['added_by']))
                {
                   $AddedUserSql="SELECT name,first_name,middle_name FROM sp_employees WHERE employee_id='".$val_records['added_by']."'";
                   $AddedUser=$this->fetch_array($this->query($AddedUserSql));
                   $val_records['added_by']=$AddedUser['name'].' '.$AddedUser['first_name']; 
                }  
                if(!empty($val_records['purpose_id']))
                {
                   $sql_call_purpose="SELECT name FROM sp_purpose_call WHERE purpose_id='".$val_records['purpose_id']."'";
                   $row_call_purpose=$this->fetch_array($this->query($sql_call_purpose));
                   $val_records['call_purpose']=$row_call_purpose['name']; 
                }  
                if(!empty($val_records['caller_id']))
                {
                   $sql_caller="SELECT name,first_name,middle_name,professional_id,consultant_id FROM sp_callers WHERE caller_id='".$val_records['caller_id']."'";
                   $row_caller=$this->fetch_array($this->query($sql_caller));
                   if(!empty($row_caller['professional_id']))
                   {
                      $sql_professional="SELECT name,first_name,middle_name FROM sp_service_professionals WHERE service_professional_id='".$row_caller['professional_id']."'";
                      $professionalresult=$this->fetch_array($this->query($sql_professional)); 
                      if(!empty($professionalresult))
                      {
                        $val_records['callerLName']=$professionalresult['name']; 
                        $val_records['callerFName']=$professionalresult['first_name'];
                        $val_records['callerMName']=$professionalresult['middle_name'];
                      }
                       unset($professionalresult);
                   }
                   else if(!empty($row_caller['consultant_id']))
                   {
                      $sql_consultant="SELECT name,first_name,middle_name FROM sp_doctors_consultants WHERE doctors_consultants_id='".$row_caller['consultant_id']."'"; 
                      $consultantresult=$this->fetch_array($this->query($sql_consultant));
                      if(!empty($consultantresult))
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
                
                // Getting Customer Name 
                $val_records['patientNm'] ="";
                if(!empty($val_records['name']) && !empty($val_records['first_name']))
                {
                   $val_records['patientNm'] =$val_records['name']." ".$val_records['first_name'];
                }
                
                unset($val_records['name']);
                unset($val_records['first_name']);
                
                // Getting Event Professional Name 
                $val_records['profNm'] ="";
                $GetEventProfSql="SELECT event_professional_id,event_id,professional_vender_id FROM sp_event_professional WHERE event_id='".$val_records['event_id']."'";
                $GetEventProf=$this->fetch_array($this->query($GetEventProfSql));
                if(!empty($GetEventProf))
                {
                    $GetProfSql="SELECT service_professional_id,name,first_name FROM sp_service_professionals WHERE service_professional_id='".$GetEventProf['professional_vender_id']."'";
                    $GetProf=$this->fetch_array($this->query($GetProfSql));
                    
                    if(!empty($GetProf))
                    {
                        if(!empty($GetProf['name']) && !empty($GetProf['first_name']))
                        {
                           $val_records['profNm'] = $GetProf['name']." ".$GetProf['first_name'];
                        }
                    } 
                }
                unset($GetProf);

                $this->resultEvents[]=$val_records;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultEvents))
        {
            $resultArray['data']=$this->resultEvents;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0);
    }
    public function DoctorsConsultantList($arg)
    {
        $type=$this->escape($arg['type']);
        if($type)
            $preWhere = " and type = '".$type."' ";
        $selectRecord = "select doctors_consultants_id,name,first_name,email_id,mobile_no,phone_no,type,status from sp_doctors_consultants where status='1' ".$preWhere." order by name asc";        
        if($this->num_of_rows($this->query($selectRecord)))
        {
            $AllRrecord = $this->fetch_all_array($selectRecord);
            
            return $AllRrecord;
        }
        else 
            return 0;  
    }
    public function LocationList($arg)
    {
        $preWhere="";
        $GroupBy="";
        $location=$this->escape($arg['location']);
        $pin_code=$this->escape($arg['pin_code']);
        $location_id=$this->escape($arg['location_id']);
        $list=$this->escape($arg['list']);
        $uniquePINs=$this->escape($arg['uniquePINs']);
        
        if($list == 'all')
            $preWhere = "";
        else
        {
            if($location)
                $preWhere = " AND location_id = '".$location."' ";        
            if($pin_code)
                $preWhere = " AND pin_code = '".$pin_code."' ";
        }
        if($list == 'LocationId')
            $preWhere = " AND location_id = '".$location_id."' ";
        
        if($uniquePINs)
           $GroupBy ="GROUP BY pin_code";
     
         $selectRecord="SELECT location_id,location,pin_code,status FROM sp_locations WHERE status='1' ".$preWhere." ".$GroupBy." ORDER BY location ASC "; 
        
        if($this->num_of_rows($this->query($selectRecord)))
        {
            if($location)
            {
                $records = $this->fetch_array($this->query($selectRecord));
                $returnRecord = $records['pin_code'];
            }
            if($pin_code)
            {
                $records = $this->fetch_array($this->query($selectRecord));
                $returnRecord = $records['location'];
            }
            if($list=='all')
            {
                $returnRecord = $this->fetch_all_array($selectRecord);    
            }
            if($list == 'LocationId')
            {
                $returnRecord = $this->fetch_array($this->query($selectRecord));
                //$returnRecord = $records['location'];  
            }
            return $returnRecord;
        }
        else 
            return 0;  
        
    }
     public function city_list($arg)
    {
    
            $selectRecord="SELECT city_id,city_name FROM sp_city "; 
        if($this->num_of_rows($this->query($selectRecord)))
        {
            $AllRrecord = $this->fetch_all_array($selectRecord);
            
            return $AllRrecord;
        }
        else 
            return 0;  
    }
    public function area_list($arg)
    {
    
            $selectRecord="SELECT location_id,location FROM sp_locations "; 
        if($this->num_of_rows($this->query($selectRecord)))
        {
            $AllRrecord = $this->fetch_all_array($selectRecord);
            
            return $AllRrecord;
        }
        else 
            return 0;  
    }
    public function InsertPatients($arg)
    {
        $employee_id = $arg['employee_id'];        
        $purpose_id=$arg['purpose_id'];
        $hospital_id=$arg['hospital_id'];    
        $exist_hhc_code = $arg['exist_hhc_code'];
        $insertData['name']=$arg['name'];
        $insertData['first_name']=$arg['first_name'];
        $insertData['middle_name']=$arg['middle_name'];
        $insertData['residential_address']=$arg['residential_address'];
        $insertData['permanant_address']=$arg['permanant_address'];
        $insertData['location_id']=$arg['location_id'];
        $insertData['city_id']=$arg['city_id'];
        $insertData['location_id']=$arg['area'];
        $insertData['sub_location']=$arg['sub_location'];
        $insertData['google_location']=$arg['google_location'];
        $insertData['mobile_no']=$arg['mobile_no'];
        $insertData['phone_no']=$arg['phone_no'];
        $insertData['email_id']=$arg['email_id'];
        if(!empty($arg['dob']))
            $insertData['dob']= date('Y-m-d',strtotime($arg['dob'])); 
        
        $insertData['status']=1;        
        $valPat = randomPass(5,'1234567890');
        
        /*          get lettitude/ langitude          */
        $region = 'IND';
        
        if($arg['google_location'] == '')
        {
            $select_locationd = "select location from sp_locations where location_id = '".$arg['location_id']."'";
            $valLocation = $this->fetch_array($this->query($select_locationd));
            $location = $valLocation['location'];
            $mainAddress = $location.', Pune, Maharashtra,India';            
            $address = str_replace(" ", "+", $mainAddress);
        }
        else
            $address = str_replace(" ", "+", $arg['google_location']);
           //  $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyD3wZTkqi05uBxq-6ef7NvnxiSWI1Jixls&address=$address&sensor=false&region=$region");
       $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&address=$address&sensor=false&region=$region");
       // $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
        $json = json_decode($json);

        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        $insertData['lattitude']=$lat;
        $insertData['langitude']=$long;
        /*          get lettitude/ langitude          */
        if($exist_hhc_code)
        {
            $select_exist = "SELECT patient_id,
                name,
                first_name,
                middle_name,
                Age,
                Gender,
                email_id,
                residential_address,
                permanant_address,
                city_id,
                sub_location,
                location_id,
                google_location,
                phone_no,
                mobile_no,
                dob,
                last_modified_by,
                last_modified_date,
                lattitude,
                langitude
             FROM sp_patients WHERE hhc_code = '" . $exist_hhc_code . "' ";
            if (mysql_num_rows($this->query($select_exist))) {
                $existPatientDtls = $this->fetch_array($this->query($select_exist));
                $insertData['last_modified_by'] = $employee_id;
                $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                $where = "hhc_code ='".$exist_hhc_code."' ";
                $this->query_update('sp_patients',$insertData,$where);
                $RecordId = $existPatientDtls['patient_id'];

                if (!empty($RecordId)) {
                    $insertActivityArr = array();
                    $insertActivityArr['module_type'] = '1';
                    $insertActivityArr['module_id']   = '';
                    $insertActivityArr['module_name'] = 'Edit Patient Details';
                    $insertActivityArr['purpose_id']  = $arg['purpose_id'];
                    $insertActivityArr['event_id']    = $arg['temp_event_id'];

                    $patientDiff = array_diff_assoc($existPatientDtls, $insertData);

                    if (!empty($patientDiff)) {
                        unset($existPatientDtls['patient_id']);
                        $messageStr = "";
                        foreach ($patientDiff AS $key => $valPatientResult) {
                            $messageStr .= $key . " is changed from " . $valPatientResult . " to " . $insertData[$key] . "\r\n";
                        }
                        $insertActivityArr['activity_description'] = (!empty($messageStr) ? nl2br($messageStr) : "");
                        $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
                        $insertActivityArr['added_by_id']          = $employee_id;
                        $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

                        $this->query_insert('sp_user_activity',$insertActivityArr);
                        unset($insertActivityArr, $patientDiff);
                    }
                }
            }
        }
        else
        {
            $select_exist = "SELECT patient_id,
                name,
                first_name,
                middle_name,
                Age,
                Gender,
                email_id,
                residential_address,
                permanant_address,
                city_id,
                sub_location,
                location_id,
                google_location,
                phone_no,
                mobile_no,
                dob,
                last_modified_by,
                last_modified_date,
                lattitude,
                langitude
            FROM sp_patients 
            WHERE name = '" . $arg['name'] ."' AND
                first_name='" . $arg['first_name'] . "' AND
                mobile_no = '" . $arg['mobile_no'] . "'";

            if (mysql_num_rows($this->query($select_exist)) == 0) {
                
                // Getting Hospital Unique Code 
                
                $GetHospitalSql="SELECT hospital_id,hospital_name,hospital_short_code FROM sp_hospitals WHERE hospital_id='".$hospital_id."'";
                if(mysql_num_rows($this->query($GetHospitalSql)))
                {
                    $GetHospital=$this->fetch_array($this->query($GetHospitalSql));
                    if(!empty($GetHospital['hospital_short_code']))
                    {
                        $HospitalUniqueCode=$GetHospital['hospital_short_code'];
                        
                        // Generate Random Number 
                        
                        $GetMaxRecordIdSql="SELECT MAX(patient_id) AS MaxId FROM sp_patients WHERE hhc_code LIKE '%".$HospitalUniqueCode."%'";
                        if($this->num_of_rows($this->query($GetMaxRecordIdSql)))
                        {
                            $MaxRecord=$this->fetch_array($this->query($GetMaxRecordIdSql));
                            $getMaxRecordId=$MaxRecord['MaxId'];
                        }
                        else 
                        {
                            $getMaxRecordId=0;
                        }
                        
                        $prefix=$HospitalUniqueCode;
                        $HHCUniqueCode=Generate_Number($prefix,$getMaxRecordId);    
                    }
                    else
                    {
                        $HospitalUniqueCode="SPHHC";
                        $HHCUniqueCode=$HospitalUniqueCode.$valPat;
                    }
                        
                }
                else 
                {
                    $HospitalUniqueCode="SPHHC";
                    $HHCUniqueCode=$HospitalUniqueCode.$valPat;
                }
                
                $insertData['hhc_code']=$HHCUniqueCode; 
                $insertData['added_by'] = $employee_id;
                $insertData['added_date'] = date('Y-m-d H:i:s');
                $RecordId=$this->query_insert('sp_patients',$insertData);

                // Added activity history for patient details
                if (!empty($RecordId)) {
                    $insertActivityArr = array();

                    $insertActivityArr['module_type'] = '1';
                    $insertActivityArr['module_id']   = '';
                    $insertActivityArr['module_name']   = 'Add Patient Details';
                    $insertActivityArr['purpose_id']   = $arg['purpose_id'];
                    $insertActivityArr['event_id']   = $arg['temp_event_id'];
                    $insertActivityArr['activity_description'] = "New patient (" .  $insertData['hhc_code'] . ") is get created by " . $_SESSION['emp_nm'] . " ";
                    $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                    $insertActivityArr['added_by_id']   = $employee_id;
                    $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');

                    $this->query_insert('sp_user_activity',$insertActivityArr);

                    unset($insertActivityArr);
                }

            } else {
                $existPatientIds = $this->fetch_array($this->query($select_exist));
                $insertData['last_modified_by'] = $employee_id;
                $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                $where = "patient_id ='".$existPatientIds['patient_id']."' ";
                $this->query_update('sp_patients', $insertData, $where);
                $RecordId = $existPatientIds['patient_id'];

                // Added activity for modification of patient details
                if (!empty($RecordId)) {
                    unset($existPatientIds['patient_id']);

                    $insertActivityArr = array();
                    $insertActivityArr['module_type'] = '1';
                    $insertActivityArr['module_id']   = '';
                    $insertActivityArr['module_name'] = 'Edit Patient Details';
                    $insertActivityArr['purpose_id']  = $arg['purpose_id'];
                    $insertActivityArr['event_id']    = $arg['temp_event_id'];

                    $patientDiff = array_diff_assoc($existPatientIds, $insertData);

                    echo '</pre>';
                    print_r($patientDiff);
                    echo '</pre>';
                    exit;

                    if (!empty($patientDiff)) {
                        $messageStr = "";
                        foreach ($patientDiff AS $key => $valPatientResult) {
                            $messageStr .= $key . " is changed from " . $valPatientResult . " to " . $insertData[$key] . "\r\n";
                        }
                        $insertActivityArr['activity_description'] = (!empty($messageStr) ? nl2br($messageStr) : "");
                        $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
                        $insertActivityArr['added_by_id']          = $employee_id;
                        $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

                        $this->query_insert('sp_user_activity',$insertActivityArr);
                        unset($insertActivityArr);
                    }
                }
            }
        }
        /* ------- Update event  -------- */

        // Get event details 
        $getEventSql = "SELECT patient_id,
            purpose_id,
            event_status,
            last_modified_by,
            last_modified_date
        FROM sp_events
        WHERE event_id = '" . $arg['temp_event_id'] . "'";

        if (mysql_num_rows($this->query($getEventSql))) {
            $eventDtls = $this->fetch_array($this->query($getEventSql));
        }

        $temp_event_id                    = $arg['temp_event_id'];
        $updateData['patient_id']         = $RecordId;
        $updateData['purpose_id']         = $purpose_id;
        $updateData['event_status']       = 2;
        $updateData['last_modified_by']   = $employee_id;
        $updateData['last_modified_date'] = date('Y-m-d H:i:s');
        $where = "event_id = '" . $temp_event_id . "' ";
        $updateEvent = $this->query_update('sp_events', $updateData, $where);

        // Added activity history for event modification details
        if (!empty($updateEvent) && !empty($eventDtls)) {
            $eventResult = array_diff_assoc($eventDtls, $updateData);

            if (!empty($eventResult)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name']   = 'Edit Patient Details';
                $insertActivityArr['purpose_id']   = $arg['purpose_id'];
                $insertActivityArr['event_id']   = $temp_event_id;
                $str = "";
                foreach ($eventResult AS $key => $valEventResult) {
                    $str .= $key . " is changed from " . $valEventResult . " to " . $updateData[$key] . "\r\n";
                }

                $insertActivityArr['activity_description'] = (!empty($str) ? nl2br($str) : "");
                $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']   = $employee_id;
                $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
            }
        }
        /* ------- Update event Complete -------- */
        
        /* ------- Create event doctors mapping -------- */
        $doctor_id = $arg['doctor_id'];
        $consultant_id = $arg['consultant_id'];
        if ($doctor_id || $consultant_id) {
            
            // Caller details added successfully. New event (" .  $createEvent['event_code']  . ") is get created by " . $_SESSION['emp_nm'] . " 
            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '1';
            $insertActivityArr['module_id']     = '';
            $insertActivityArr['module_name']   = 'Edit Patient Details';
            $insertActivityArr['purpose_id']    = $arg['purpose_id'];
            $insertActivityArr['event_id']      = $temp_event_id;
            $insertActivityArr['added_by_type'] = '1'; // 1 For Employee
            $insertActivityArr['added_by_id']   = $_SESSION['employee_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            if ($doctor_id) {
                $insertDoctors['doctor_consultant_id'] = $doctor_id;
                $insertDoctors['type'] = 1;
                
                $insertDoctors['event_id'] = $temp_event_id;
                $insertDoctors['patient_id'] = $RecordId;                
                $select_exist_doctors = "SELECT event_doctor_id,
                    doctor_consultant_id,
                    type,
                    last_modified_by,
                    last_modified_date
                FROM sp_event_doctor_mapping 
                WHERE event_id = '" . $temp_event_id . "' AND
                    patient_id = '" . $RecordId . "' AND 
                    type = '" . $insertDoctors['type'] . "' ";
                if (mysql_num_rows($this->query($select_exist_doctors))) {
                    $doctorDtls = $this->fetch_array($this->query($select_exist_doctors));
                    $activityDesc = "Family doctor details modified succesfully by " . $_SESSION['emp_nm'] . ". \r\n"; 
                    $insertDoctors['last_modified_by'] = $employee_id;
                    $insertDoctors['last_modified_date'] = date('Y-m-d H:i:s');
                    $whereClause = "event_doctor_id = " . $doctorDtls['event_doctor_id'];
                    $updateDoctor = $this->query_update('sp_event_doctor_mapping', $insertDoctors, $whereClause);

                    // add activty log while edit consultant
                    if (!empty($updateDoctor)) {
                        unset($doctorDtls['event_doctor_id']);
                        $doctorDiff = array_diff_assoc($doctorDtls, $insertDoctors);
                        if (!empty($doctorDiff)) {
                            $strContent = $activityDesc;
                            foreach ($doctorDtls AS $key => $valDoctorResult) {
                                $strContent .= $key . " is changed from " . $valDoctorResult . " to " . $insertDoctors[$key] . "\r\n";
                            }
                            $insertActivityArr['activity_description'] = (!empty($strContent) ? nl2br($strContent) : "");
                        }
                    }
                } else {
                    $activityDesc = "Family doctor details added succesfully by " . $_SESSION['emp_nm'] . ". \r\n";
                    $insertDoctors['added_by'] = $employee_id;
                    $insertDoctors['added_date'] = date('Y-m-d H:i:s');
                    $this->query_insert('sp_event_doctor_mapping', $insertDoctors);

                    // add activty log while adding new consultant to event
                    $insertActivityArr['activity_description'] = $activityDesc;
                }

                $this->query_insert('sp_user_activity', $insertActivityArr);
            }
            if ($consultant_id) {
                $insertDoctors['doctor_consultant_id'] = $consultant_id;
                $insertDoctors['type'] = 2;
                
                $insertDoctors['event_id'] = $temp_event_id;
                $insertDoctors['patient_id'] = $RecordId;                
                $select_exist_consultant = "SELECT event_doctor_id,
                    doctor_consultant_id,
                    type,
                    last_modified_by,
                    last_modified_date
                FROM sp_event_doctor_mapping 
                WHERE event_id = '" . $temp_event_id . "' AND 
                    patient_id = '" . $RecordId . "' AND
                    type = '" . $insertDoctors['type'] . "' ";

                if (mysql_num_rows($this->query($select_exist_consultant))) {
                    $consultantDtls = $this->fetch_array($this->query($select_exist_consultant));
                    unset($activityDesc);
                    $activityDesc = "Consultant doctor details modified succesfully by " . $_SESSION['emp_nm'] . ". \r\n";
                    $insertDoctors['last_modified_by'] = $employee_id;
                    $insertDoctors['last_modified_date'] = date('Y-m-d H:i:s');
                    $whereClause = "event_doctor_id = " . $consultantDtls['event_doctor_id'];
                    $updateConsultant = $this->query_update('sp_event_doctor_mapping', $insertDoctors, $whereClause);

                    if (!empty($updateConsultant)) {
                        unset($consultantDtls['event_doctor_id']);
                        $consulantDiff = array_diff_assoc($consultantDtls, $insertDoctors);
                        if (!empty($consulantDiff)) {
                            $strCont = $activityDesc;
                            foreach ($consultantDtls AS $key => $valConsultantResult) {
                                $strCont .= $key . " is changed from " . $valConsultantResult . " to " . $insertDoctors[$key] . "\r\n";
                            }
                            $insertActivityArr['activity_description'] = (!empty($strCont) ? nl2br($strCont) : "");
                        }
                    }
                } else {
                    $insertDoctors['added_by'] = $employee_id;
                    $insertDoctors['added_date'] = date('Y-m-d H:i:s');
                    $this->query_insert('sp_event_doctor_mapping',$insertDoctors);
                    // add activty log while adding new consultant to event
                    unset($activityDesc);
                    $activityDesc = "Consultant doctor details added succesfully by " . $_SESSION['emp_nm'] . ". \r\n";
                    $insertActivityArr['activity_description'] = $activityDesc;
                }

                $this->query_insert('sp_user_activity', $insertActivityArr);
            }
            unset($insertActivityArr);
        }
        return $RecordId;
    }

    public function ChangeStatus($arg)
    {
        $event_id=$this->escape($arg['event_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkPatientSql="SELECT event_id FROM sp_events WHERE event_id='".$event_id."'";
        if($this->num_of_rows($this->query($ChkPatientSql)))
        {
            if($istrashDelete)
            {
                $UpdateStatusSql="DELETE FROM sp_events WHERE event_id='".$event_id."'";
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_events SET status='".$status."',isDelStatus='".$pre_status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE event_id='".$event_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return $RecordId;
        }
        else 
            return 0;
    }

    /**
     * This function is used for add event requirement details
     */
    public function InsertRequirements($arg)
    {
        $employee_id   = $arg['employee_id'];        
        $purposeId    = $arg['purpose_id'];
		$hospital_name = $arg['hospital_name'];        
        $Consultant    = $arg['Consultant'];
        $eventId       = $arg['event_id_temp'];
        $dataArr = "";
       
        // delete existing services
        $select_existRequirment = "select distinct service_id from sp_event_requirements where event_id = '" . $eventId . "' ";
        $val_existRequirement = $this->fetch_all_array($select_existRequirment);
        
        foreach ($val_existRequirement AS $AllRequ) {
            $arrayService[] = $AllRequ['service_id'];
        }
        $intersect = array();
        $exiSerArray = $arrayService;
        $NewSerArray = (!empty($arg['requireservices']) ? $arg['requireservices'] : $_REQUEST['requireservices']);

        if ($exiSerArray) {
            $intersect = array_intersect($NewSerArray, $exiSerArray);
        }

        if (!empty($intersect)) {
            $comma_separated = implode(",", $intersect);
            $predelete = " and service_id NOT IN (" . $comma_separated . ")";
        }

        $deleteUnwanted = "DELETE FROM sp_event_requirements WHERE event_id = '" . $eventId . "' $predelete ";
        $this->query($deleteUnwanted);
		
		//Ashwini code
		$delete_plan_of_care = "DELETE FROM sp_event_plan_of_care WHERE event_id = '" . $eventId . "'  ";
		$this->query($delete_plan_of_care);
		
		$delete_detail_plan_of_care = "DELETE FROM sp_detailed_event_plan_of_care WHERE event_id = '" . $eventId . "'  ";
		 $this->query($delete_detail_plan_of_care);

		$delete_event_professional = "DELETE FROM sp_event_professional WHERE event_id = '" . $eventId . "'  ";
		$this->query($delete_event_professional);
			
		
        $Servicediff = array_merge(array_diff($NewSerArray, $intersect), array_diff($intersect, $NewSerArray));
        // delete existing services complete
       
        if (empty($Servicediff)) {
            $Servicediff = $NewSerArray;
        }

        if (!empty($Servicediff)) {
            for ($ct=0; $ct < count($Servicediff); $ct++) {
                $sel_serviceID = $Servicediff[$ct];
                $SeltotalSubServices = (!empty($arg['sub_service_id_multiselect_' . $sel_serviceID]) ? $arg['sub_service_id_multiselect_' . $sel_serviceID] : $_REQUEST['sub_service_id_multiselect_' . $sel_serviceID]);
    
                // delete existing subservices
                $select_existsub = "select sub_service_id from sp_event_requirements where event_id = '" . $eventId . "' and service_id = '" . $sel_serviceID . "' ";
                $val_existReqSubSer = $this->fetch_all_array($select_existsub);
                foreach ($val_existReqSubSer as $key => $AllRequSubServ) {
                    $arraySubService[] = $AllRequSubServ['sub_service_id'];
                }
                $intersectSub = array();
                $exiSubSerArray = $arraySubService;
                $NewSubSerArray = $SeltotalSubServices;
                if ($exiSubSerArray) {
                    $intersectSub = array_intersect($NewSubSerArray, $exiSubSerArray);
                }
                if (!empty($intersectSub)) {
                    $comma_separated_sub = implode(",", $intersectSub);
                    $predeleteSub = " and sub_service_id NOT IN (".$comma_separated_sub.")";
                }
                $deleteUnwantedSub = "DELETE FROM sp_event_requirements WHERE event_id = '".$eventId."' and service_id = '".$sel_serviceID."'  $predeleteSub ";
                $this->query($deleteUnwantedSub);
    
                //Ashwinikoli Code
                $delete_plan_of_care = "DELETE FROM sp_event_plan_of_care WHERE event_id = '".$eventId."'  ";
                $this->query($delete_plan_of_care);
                
                $delete_detail_plan_of_care = "DELETE FROM sp_detailed_event_plan_of_care WHERE event_id = '".$eventId."'  ";
                $this->query($delete_detail_plan_of_care);
            
                $delete_event_professional = "DELETE FROM sp_event_professional WHERE event_id = '".$eventId."'  ";
                $this->query($delete_event_professional);
                
                $SubServicediff = array_merge(array_diff($NewSubSerArray, $intersectSub), array_diff($intersectSub, $NewSubSerArray));
    
                for ($ts = 0; $ts < count($SubServicediff); $ts++) {
                    $insertData['sub_service_id']=$SubServicediff[$ts];
                    $insertData['event_id']=$eventId;
                    $insertData['service_id']=$sel_serviceID;
                    $insertData['status']=1;
                    $insertData['hospital_id']=$hospital_name;
                    $insertData['Consultant']=$Consultant;
                    
                    if ($SubServicediff[$ts] && $sel_serviceID) {
                        $select_exist = "SELECT event_requirement_id FROM sp_event_requirements WHERE event_id = '".$eventId."' and sub_service_id = '".$SubServicediff[$ts]."'  ";
                        if (mysql_num_rows($this->query($select_exist)) == 0) {
                            $insertData['added_by'] = $employee_id;
                            $insertData['added_date'] = date('Y-m-d H:i:s');

                            // preapare activity log statement
                            $getServiceNameSql = "SELECT service_title
                            FROM sp_services
                            WHERE service_id = '" . $sel_serviceID . "'";

                            $serviceDtls = $this->fetch_array($this->query($getServiceNameSql));

                            $getSubServiceNameSql = "SELECT recommomded_service
                            FROM sp_sub_services
                            WHERE sub_service_id = '" . $SubServicediff[$ts] . "'";

                            $subServiceDtls = $this->fetch_array($this->query($getSubServiceNameSql));

                            $insertRecordId = $this->query_insert('sp_event_requirements',$insertData);

                            if (!empty($insertRecordId)) {
                                $dataArr[] = "Service Name :- " . $serviceDtls['service_title'] . " Sub Service Name :- " .  $subServiceDtls['recommomded_service'];
                            }
                        }
                    }
                }
            }
        }

        // Added activity history while adding event requirement
        $insertActivityArr = array();

        $insertActivityArr['module_type']          = '1';
        $insertActivityArr['module_id']            = '';
        $insertActivityArr['module_name']          = 'Add Requirement Details';
        $insertActivityArr['purpose_id']           = $purposeId;
        $insertActivityArr['event_id']             = $eventId;
        $activityDesc = "Event requirement details added successfully by " . $_SESSION['emp_nm'] . "\r\n";
        if (!empty($dataArr)) {
            $activityDesc .= "Requirement details are " . rtrim(implode(",", $dataArr), ',');
        }
        $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
        
        $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
        $insertActivityArr['added_by_id']          = $employee_id;
        $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

        $this->query_insert('sp_user_activity', $insertActivityArr);

        unset($insertActivityArr);

        /* ------- Update event  -------- */

        // Get event details
        $getEventdtlsSql = "SELECT note,
            event_status,
            last_modified_by,
            last_modified_date
        FROM sp_events
        WHERE event_id = '" . $eventId . "'";

        if (mysql_num_rows($this->query($getEventdtlsSql)) == 0) {
            $eventDtls = $this->fetch_array($this->query($getEventdtlsSql));
        }

        $updateData['note']               = $arg['notes'];
        $updateData['event_status']       = 2;
        $updateData['last_modified_by']   = $employee_id;
        $updateData['last_modified_date'] = date('Y-m-d H:i:s');
        $where = "event_id ='" . $eventId . "' ";
        $updateEvent = $this->query_update('sp_events', $updateData, $where);

        // Added activity history for update event details
        if (!empty($updateEvent) && !empty($eventDtls)) {
            $recordResult = array_diff_assoc($eventDtls, $updateData);

            if (!empty($recordResult)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name'] = 'Add Requirement Details';
                $insertActivityArr['purpose_id']  = $purposeId;
                $insertActivityArr['event_id']    = $eventId;
                $messageStr = "";
                foreach ($recordResult AS $key => $valResult) {
                    $messageStr .= $key . " is changed from " . $valResult . " to " . $updateData[$key] . "\r\n";
                }
                $insertActivityArr['activity_description'] = (!empty($messageStr) ? nl2br($messageStr) : "");
                $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']   = $employee_id;
                $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');

                $this->query_insert('sp_user_activity',$insertActivityArr);
            }
        }

        /* ------- Update event Complete -------- */
        return 'Inserted';
    }

    public function planofcareRecords($arg)
    {
        $event_id = $arg['event_id'];
        if($arg['event_requirement_id'])
            $preWhere = " and event_requirement_id = '".$arg['event_requirement_id']."'";

        $select_eventRequirements = "select event_requirement_id, event_id,service_id,sub_service_id from sp_event_requirements where  event_id = '".$event_id."' and status = '1' ".$preWhere." ";
        
       $this->result = $this->query($select_eventRequirements);
        if ($this->num_of_rows($this->result))
        {
            $this->resultRequirement=array();
            while($val_records=$this->fetch_array($this->result))
            {
                $select_subservice = "select recommomded_service, cost, tax from sp_sub_services where sub_service_id = '".$val_records['sub_service_id']."' ";
                $data_val = $this->fetch_array($this->query($select_subservice));
                $val_records['recommomded_service'] = $data_val['recommomded_service'];
                $val_records['cost'] = $data_val['cost'];
                $val_records['tax'] = $data_val['tax'];
                
                $select_service = "select service_title from sp_services where service_id = '".$val_records['service_id']."' ";
                $data_val_service = $this->fetch_array($this->query($select_service));
                $val_records['service_title'] = $data_val_service['service_title'];
                
                // Getting Plan of care details 
                
                $select_plan_of_care = "SELECT plan_of_care_id,service_date,service_date_to,start_date,end_date FROM sp_event_plan_of_care WHERE event_requirement_id='".$val_records['event_requirement_id']."'";
                $data_val_plan_of_care = $this->fetch_array($this->query($select_plan_of_care));
                $val_records['service_date'] = $data_val_plan_of_care['service_date'];
                $val_records['service_date_to'] = $data_val_plan_of_care['service_date_to'];
                $val_records['start_date'] = $data_val_plan_of_care['start_date'];
                $val_records['end_date'] = $data_val_plan_of_care['end_date'];
                
                $this->resultRequirement[]=$val_records;
            }
            
            $resultArray['count'] = $this->num_of_rows($this->result);
        }
        if(count($this->resultRequirement))
        {
            $resultArray['data']=$this->resultRequirement;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0);
    }
    public function MultipleplanofcareRecords($arg)
    {
        $event_id = $arg['event_id'];
        
        $select_plan_of_care = "SELECT plan_of_care_id,service_date,service_date_to,start_date,end_date,service_cost FROM sp_event_plan_of_care WHERE event_requirement_id='".$arg['event_requirement_id']."' and event_id = '".$event_id."' AND status=1";
        $ptrPlanCare = $this->fetch_all_array($select_plan_of_care);
        if(mysql_num_rows($this->query($select_plan_of_care)))
        {
            $this->resulPlanCare = array();
            foreach($ptrPlanCare as $key=>$valPlanCare)
            {
                $select_subservice = "select recommomded_service, cost, tax from sp_sub_services where sub_service_id = '".$arg['sub_service_id']."' ";
                $data_val = $this->fetch_array($this->query($select_subservice));
                $valPlanCare['recommomded_service'] = $data_val['recommomded_service'];
                $valPlanCare['cost'] = $data_val['cost'];
                $valPlanCare['tax'] = $data_val['tax'];
                
                $this->resulPlanCare[]=$valPlanCare;
            }
            $resultPlanArr['count'] = $this->num_of_rows($this->query($select_plan_of_care));
        }
        if(count($this->resulPlanCare))
        {
            $resultPlanArr['data']=$this->resulPlanCare;
            return $resultPlanArr;
        }
        else
            return array('data' => array(), 'count' => 0);
    }
    public function SelectedPlanCareServices($arg)
    {
        $event_id = $arg['event_id'];
        
        $select_eventRequirements = "select distinct service_id from sp_event_requirements where  event_id = '".$event_id."' and status = '1'  ";        
        $this->result = $this->query($select_eventRequirements);
        if ($this->num_of_rows($this->result))
        {
            $this->resultServices=array();
            while($val_records=$this->fetch_array($this->result))
            {
                $select_service = "select service_title from sp_services where service_id = '".$val_records['service_id']."' ";
                $data_val_service = $this->fetch_array($this->query($select_service));
                $val_records['service_title'] = $data_val_service['service_title'];
                
                
                $this->resultServices[]=$val_records;
            }
            
            $resultArray['count'] = $this->num_of_rows($this->result);
        }
        if(count($this->resultServices))
        {
            $resultArray['data']=$this->resultServices;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0);
    }
    public function GetDoctorConsultant($arg)
    {
        $event_id = $arg['event_id'];
        $patient_id = $arg['patient_id'];
        $type = $arg['doctor_type'];
        $exi_purpose_id = $arg['exi_purpose_id'];
        if($exi_purpose_id == '3' || $exi_purpose_id == '7' || $exi_purpose_id == '4' || $exi_purpose_id == '5')
            $preWhere = " and edm.patient_id = '".$patient_id."'"; 
        else
            $preWhere .= " and edm.event_id = '".$event_id."' ";
        
        $select_doctors = "select edm.event_doctor_id,edm.doctor_consultant_id,ed.name as DocName, ed.email_id, ed.mobile_no from "
                . "sp_event_doctor_mapping as edm left join sp_doctors_consultants as ed ON edm.doctor_consultant_id = ed.doctors_consultants_id "
                . " where 1 ".$preWhere." and edm.status='1' and edm.type = '".$type."' ";
        if(mysql_num_rows($this->query($select_doctors)))
        {
            $result = $this->fetch_array($this->query($select_doctors));
            return $result;
        }
        else
            return 0;
    }
    public function GetEvent($arg)
    {
        $event_id = $this->escape($arg['event_id']); 
        $selectEvent = "SELECT t1.event_id,
            t1.event_code,
            t1.caller_id,
            t1.relation,
            t1.patient_id,
            t1.purpose_id,
            t1.event_date,
            t1.note,
            t1.description,
            t1.event_status,
            t1.enquiry_status,
            t1.estimate_cost,
            t1.discount_type,
            t1.discount_value,
            t1.discount_amount,
            t1.Invoice_narration,
            t1.invoice_narration_desc,
            t1.enquiry_status,
            (CASE
                WHEN t1.enquiry_status = '0' THEN 'Enquiry Received'
                WHEN t1.enquiry_status = '1' THEN 'Called Back'
                WHEN t1.enquiry_status = '2' THEN 'Confirm'
                WHEN t1.enquiry_status = '3' THEN 'Cancel'
            END) AS enquiryStatusVal,
            t1.service_date_of_Enquiry,
            t1.enquiry_added_date,
            t1.enquiry_cancel_date,
            t1.enquiry_cancellation_reason,
            t1.added_date,
            t1.added_by,
            CONCAT_WS(' ', t2.first_name, t2.middle_name, t2.name) AS addedByNm,
            t1.last_modified_by,
            CONCAT_WS(' ', t3.first_name, t3.middle_name, t3.name) AS modifiedByNm,
            t1.last_modified_date,
	        t1.hospital_id
        FROM sp_events AS t1
        INNER JOIN sp_employees AS t2
            ON t1.added_by = t2.employee_id
            INNER JOIN sp_employees AS t3
            ON t1.last_modified_by = t3.employee_id   
        WHERE t1.event_id = '" . $event_id . "' ";

        $ptr_event = $this->fetch_array($this->query($selectEvent));
        if(mysql_num_rows($this->query($selectEvent)))
            return $ptr_event;
        else
            return 0;
    }
    public function GetEventSummary($arg)
    {
       $arr['event_id']=$this->escape($arg['event_id']); 
       
       // Get Caller Details
       $GetCaller=$this->GetEventCallerDtls($arr);
       if(!empty($GetCaller))
            $ValEventSummary['CallerDtls']=$GetCaller;
       else 
            $ValEventSummary['CallerDtls']="";
       // Getting Patient Details
       
       if(!empty($GetCaller['patient_id']))
       {
           $arr['patient_id']=$GetCaller['patient_id'];
           $ValEventSummary['PatientDtls']=$this->GetPatientById($arr);
       }
       else 
       {
           // Getting Patient Id 
           $GetPatientIdSql="SELECT patient_id FROM sp_events WHERE event_id='".$GetCaller['purpose_event_id']."' ";
          
           $GetPatient=$this->fetch_array($this->query($GetPatientIdSql));
           if(!empty($GetPatient))
           {
                $arr['patient_id']=$GetPatient['patient_id'];
                $ValEventSummary['PatientDtls']=$this->GetPatientById($arr);
                 unset($arr['event_id']);
                 $arr['event_id']=$GetCaller['purpose_event_id'];
           }
       }
       // Getting Family Doctor and consultant doctor
       $arr['type']='1';
       $ValEventSummary['FamilyDoctorDtls']=$this->GetConsultantByPatient($arr);
       unset($arr['type']);
       
       $arr['type']='2';
       $ValEventSummary['ConsultantDtls']=$this->GetConsultantByPatient($arr);
        unset($arr['type']);
		
		// Get Plan of care details
		$plan_of_care=$this->planofcareRecords($arr);
		
		if(!empty($plan_of_care))
		{
			$ValEventSummary['plan_of_care']=$plan_of_care;
		}
		else 
			$ValEventSummary['plan_of_care']="";

       // get Estimated Call Status 
        $Get_call_status=$this->GetEvent($arr); 
        
        if(!empty($Get_call_status))
        {
            $ValEventSummary['estimate_cost']=$Get_call_status['estimate_cost'];  /* 1 means not set 2 means no 3 means yes */
            $ValEventSummary['note']=$Get_call_status['note'];
        }
        else 
          $ValEventSummary['estimate_cost']=""; 
        
       // Get Requirement Details
        $GetRequirement=$this->GetEventRequirement($arr);
        if(!empty($GetRequirement))
            $ValEventSummary['ReqDtls']=$GetRequirement;
        else 
            $ValEventSummary['ReqDtls']="";
        
       // Get Share with HCM Details
       $GetShareWithHCM=$this->GetEventShareWithHCM($arr);
       if(!empty($GetShareWithHCM))
            $ValEventSummary['ShareWithHCM']=$GetShareWithHCM;
        else 
            $ValEventSummary['ShareWithHCM']="";
        
        // Get Professional details
        $GetEventProfessional=$this->GetEventProfessional($arr);   
        if(!empty($GetEventProfessional))
            $ValEventSummary['ProfessionalDtls']=$GetEventProfessional;
        else 
            $ValEventSummary['ProfessionalDtls']="";
        
        // Get job summary details
        $GetJobSummary=$this->GetEventJobSummary($arr);   
        if(!empty($GetJobSummary))
            $ValEventSummary['JobSummary']=$GetJobSummary;
        else 
            $ValEventSummary['JobSummary']="";
        
        // Get job closure details
        $GetJobClosure=$this->GetEventJobClosure($arr);
        
       // echo '<pre>';
       // print_r($GetJobClosure);
       // echo '</pre>';
        
        if(!empty($GetJobClosure))
            $ValEventSummary['JobClosure']=$GetJobClosure;
        else 
            $ValEventSummary['JobClosure']="";
        
        
        
        // Get feedback details
		
		$GetFeedback=$this->GetEventFeedback($arr);
        
         if(!empty($GetFeedback))
            $ValEventSummary['FeedbackDtls']=$GetFeedback;
        else 
            $ValEventSummary['FeedbackDtls']="";
        
        
        if(!empty($ValEventSummary))
            return $ValEventSummary;
        else 
            return 0;   
    }
    public function GetEventCaller($arg)
    {
        
        $event_id=$this->escape($arg['event_id']); 
        $CallerSql="SELECT t1.event_id,t1.event_status,t1.relation,t1.patient_id,t1.purpose_id,t2.professional_id,t2.consultant_id,t2.caller_id,t2.name AS caller_last_name,t2.first_name AS caller_first_name,t2.middle_name AS caller_middle_name,t2.phone_no,t3.name AS AttendBy,t3.type,t1.note FROM sp_events t1".
            " INNER JOIN sp_callers t2 ON t2.caller_id=t1.caller_id".
            " INNER JOIN sp_employees t3 ON t3.employee_id=t2.attended_by".
            " WHERE t1.event_id='".$event_id."'";
           if($this->num_of_rows($this->query($CallerSql)))
            {
                $Caller=$this->fetch_array($this->query($CallerSql));
                return $Caller;
            }
            else
                return 0;
    }
    public function GetEventRequirement_service($arg)
    {
       // var_dump($arg);die();
       $event_id=$this->escape($arg['event_id']); 
       $RequirementSql="SELECT t1.event_requirement_id,t1.event_id,t1.service_id,t1.sub_service_id,t2.service_title,t3.recommomded_service FROM sp_event_requirements t1".
                       " INNER JOIN sp_services t2 ON t2.service_id=t1.service_id".
                       " INNER JOIN sp_sub_services t3 ON t3.sub_service_id=t1.sub_service_id".
                       " WHERE t1.event_id='".$arg."'";
                     //  echo $RequirementSql;
       if($this->num_of_rows($this->query($RequirementSql)))
       {
            $Requirement=$this->fetch_all_array($RequirementSql);
            return $Requirement;
       }
       else 
           return 0;
    }
    public function GetEventRequirement($arg)
    {
        //var_dump($arg['event_id']);die();
       $event_id=$this->escape($arg['event_id']); 
       $RequirementSql="SELECT t1.event_requirement_id,t1.event_id,t1.service_id,t1.sub_service_id,t2.service_title,t3.recommomded_service FROM sp_event_requirements t1".
                       " INNER JOIN sp_services t2 ON t2.service_id=t1.service_id".
                       " INNER JOIN sp_sub_services t3 ON t3.sub_service_id=t1.sub_service_id".
                       " WHERE t1.event_id='".$event_id."'";
                     //  echo $RequirementSql;
       if($this->num_of_rows($this->query($RequirementSql)))
       {
            $Requirement=$this->fetch_all_array($RequirementSql);
            return $Requirement;
       }
       else 
           return 0;
    }
    public function GetEventShareWithHCM($arg)
    {
       $event_id=$this->escape($arg['event_id']);
       $ShareWithHCMSql="SELECT event_share_id FROM sp_event_share_hcm WHERE event_id='".$event_id."'";
       if($this->num_of_rows($this->query($ShareWithHCMSql)))
       {
            $ShareWithHCM=$this->fetch_array($this->query($ShareWithHCMSql));
            $RecordSql="SELECT event_share_id,event_id,assigned_to,assigned_by FROM sp_event_share_hcm WHERE event_share_id='".$ShareWithHCM['event_share_id']."'";
            $Record=$this->fetch_array($this->query($RecordSql));
            // Getting Assigned To User Details 
            $AssignedToSql="SELECT employee_id,employee_code,type,name,designation,email_id FROM sp_employees WHERE employee_id='".$Record['assigned_to']."'";
            $AssignedTo=$this->fetch_array($this->query($AssignedToSql));
            if(!empty($AssignedTo))
            {
                $SharedData['AssignTo']=$AssignedTo['name'];
            }   
            // Getting Assigned By User Details 
            $AssignedBySql="SELECT employee_id,employee_code,type,name,designation,email_id FROM sp_employees WHERE employee_id='".$Record['assigned_by']."'";
            $AssignedBy=$this->fetch_array($this->query($AssignedBySql)); 
            if(!empty($AssignedBy))
            {
              $SharedData['AssignBy']=$AssignedBy['name'];  
            }
            return $SharedData;
       }
       else 
           return 0;      
    } 
    public function GetEventProfessional($arg)
    {
        $event_id=$this->escape($arg['event_id']);
        $ProfessionalSql="SELECT event_professional_id FROM sp_event_professional WHERE event_id='".$event_id."'";
        if($this->num_of_rows($this->query($ProfessionalSql)))
        {
           $Professional=$this->fetch_all_array($ProfessionalSql);
           $ResultArr =array();
           foreach($Professional AS $key=>$ValProfessional)
           {
                // Getting record details
                $RecordSql="SELECT event_professional_id,event_id,event_requirement_id,professional_vender_id,plan_of_care_id FROM sp_event_professional WHERE event_professional_id='".$ValProfessional['event_professional_id']."'";
                $Record=$this->fetch_array($this->query($RecordSql));
                // Get Professional Details
                $GetProfessionalSql="SELECT service_professional_id,professional_code,reference_type,name,first_name,middle_name,location_id,set_location,google_home_location,google_work_location FROM sp_service_professionals WHERE service_professional_id='".$Record['professional_vender_id']."'";
                $valRecords=$this->fetch_array($this->query($GetProfessionalSql)); 
                
                if($valRecords['set_location'] == '1')
                {
                    $locations = $valRecords['location_id_home'];
                    $google_location = $valRecords['google_home_location'];
                }
                else
                {
                    $locations = $valRecords['location_id'];
                    $google_location = $valRecords['google_work_location'];
                }
                if($google_location == '')
                {
                    $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$locations."'";
                    $LocationDtls=$this->fetch_array($this->query($LocationSql));
                    if($LocationDtls['location'])
                        $locationNm=$LocationDtls['location']; 
                    $valRecords['LocationPinCode']=$LocationDtls['pin_code'];  
                }
                else
                    $locationNm = $google_location;
                
                $valRecords['locationNm']=$locationNm;
                $valRecords['LocationPinCode']='';  
                
                // Getting Other Details of Professional
                $GetProfOtherDtlsSql="SELECT detail_id,qualification,specialization,skill_set,work_experience,hospital_attached_to,pancard_no FROM sp_service_professional_details WHERE service_professional_id='".$valRecords['service_professional_id']."'";
                if($this->num_of_rows($this->query($GetProfOtherDtlsSql)))
                {
                   $GetProfOtherDtls=$this->fetch_array($this->query($GetProfOtherDtlsSql)); 
                   if(!empty($GetProfOtherDtls))
                      $valRecords['ProfOtherDtls']=$GetProfOtherDtls;
                   else 
                     $valRecords['ProfOtherDtls']="";       
                }
                // Get Service Name 
                
                $GetServiceSql="SELECT s.service_id,s.service_title FROM sp_event_requirements er ".
                               "INNER JOIN sp_event_professional ep ON ep.event_requirement_id=er.event_requirement_id ".
                               "INNER JOIN sp_services s ON s.service_id=er.service_id WHERE er.event_requirement_id='".$Record['event_requirement_id']."' AND er.event_id='".$Record['event_id']."' ";
                
                if($this->num_of_rows($this->query($GetServiceSql)))
                {
                   $GetServiceDtls=$this->fetch_array($this->query($GetServiceSql)); 
                   if(!empty($GetServiceDtls))
                      $valRecords['serviceNm']=$GetServiceDtls['service_title'];
                   else 
                     $valRecords['serviceNm']="";       
                }
                $ResultArr[]=$valRecords;  
           }
           if(count($ResultArr))
           {
              return $ResultArr;
           }
           else 
           {
               return 0;
           }
        }
        else 
            return 0; 
    }
    public function GetEventJobSummary($arg)
    {
       $event_id=$this->escape($arg['event_id']); 
       $JobSummarySql="SELECT job_summary_id FROM sp_event_job_summary WHERE event_id='".$event_id."'";
       if($this->num_of_rows($this->query($JobSummarySql)))
       {
           $JobSummary=$this->fetch_all_array($JobSummarySql);
           $ResultArr =array();
           foreach ($JobSummary AS $Key=>$ValJobSummary)
           {  
               // Getting Job Summary Detail
               
               $GetSummarySql="SELECT job_summary_id,event_id,service_id,event_professional_id,professional_vender_id,reporting_instruction,type,report_status,status,added_by,added_date,modified_by,last_modified_date FROM sp_event_job_summary WHERE job_summary_id='".$ValJobSummary['job_summary_id']."'";
               $GetSummary=$this->fetch_array($this->query($GetSummarySql));
               if(!empty($GetSummary))
               {
                  $ValJobSummary['Report_Inst']=$GetSummary['reporting_instruction'];
                  
                  if($GetSummary['type']=='1')
                    $ValJobSummary['MediaType']="SMS";
                  else if($GetSummary['type']=='2')
                    $ValJobSummary['MediaType']="EMAIL";
                  else if($GetSummary['type']=='3')
                   $ValJobSummary['MediaType']="CALL"; 
               }
               else 
                  $ValJobSummary['Report_Inst']="";
               
               // Getting Recommonded Service Name  
               $GetServiceSql="SELECT service_id,service_title FROM sp_services WHERE service_id='".$GetSummary['service_id']."'";
               $GetService=$this->fetch_array($this->query($GetServiceSql));
                       
               if(!empty($GetService))
               {
                  $ValJobSummary['ServiceNm']=$GetService['service_title'];  
               }
               else 
                 $ValJobSummary['ServiceNm']="";  
               
               // Getting Event Professional Details 
               $GetEvntProfSql="SELECT event_professional_id,event_id,event_requirement_id,professional_vender_id FROM sp_event_professional WHERE event_professional_id='".$GetSummary['event_professional_id']."' AND event_id='".$GetSummary['event_id']."'";
               $GetEvntProfessional=$this->fetch_array($this->query($GetEvntProfSql));
               
               // Getting Professional Details
               $GetProfessionalSql="SELECT service_professional_id,professional_code,reference_type,name,first_name,middle_name,email_id FROM sp_service_professionals WHERE service_professional_id ='".$GetSummary['professional_vender_id']."'";
               $GetProfessional=$this->fetch_array($this->query($GetProfessionalSql));
               
               if(!empty($GetProfessional))
               {
                  $ValJobSummary['ProfessionalId']=$GetProfessional['professional_code'];
                  $ValJobSummary['ProfessionalNm']=$GetProfessional['name']." ".$GetProfessional['first_name']." ".$GetProfessional['middle_name'];
               }
               else 
               {
                  $ValJobSummary['ProfessionalId']="";
                  $ValJobSummary['ProfessionalNm']="";
               }
               // Getting Recommonded service date and time 
               $GetRecommondedServiceSql="SELECT plan_of_care_id,event_id,service_date,start_date,end_date FROM sp_event_plan_of_care WHERE event_requirement_id='".$GetEvntProfessional['event_requirement_id']."' AND event_id='".$GetEvntProfessional['event_id']."' AND status=1";
               $GetRecommondedService=$this->fetch_array($this->query($GetRecommondedServiceSql));
               if(!empty($GetRecommondedService))
               {
                   $ValJobSummary['ServiceDate']=$GetRecommondedService['service_date'];
                   $ValJobSummary['StartTime']=$GetRecommondedService['start_date'];
                   $ValJobSummary['EndTime']=$GetRecommondedService['end_date'];
               }
               else 
               {
                  $ValJobSummary['ServiceDate']="";
                  $ValJobSummary['StartTime']="";
                  $ValJobSummary['EndTime']=""; 
               }
               
               $ResultArr[]=$ValJobSummary;  
           }
           if(count($ResultArr))
           {
               return $ResultArr;
           }
           else 
           {
               return 0;
           }    
       }
    }
    public function GetEventJobClosure($arg)
    {
       $event_id=$this->escape($arg['event_id']); 
       $GetJobClosureSql="SELECT job_closure_id FROM sp_job_closure WHERE event_id='".$event_id."'";
       if($this->num_of_rows($this->query($GetJobClosureSql)))
       {
            $Result=$this->fetch_all_array($GetJobClosureSql);
            $resArr=array();
            foreach($Result as $key=>$valIds)
            {
                // Fetching all Records of Job Closure
                $RecordSql="SELECT job_closure_id,event_id,professional_vender_id,service_id,service_render,service_date,baseline,airway,breathing,circulation,temprature,bsl,pulse,spo2,rr,gcs_total,high_bp,low_bp,skin_perfusion,summary_note,job_closure_file,status FROM sp_job_closure WHERE job_closure_id='".$valIds['job_closure_id']."'";
                $RecordResult =$this->fetch_array($this->query($RecordSql));
                
                // Getting Professional Name
                $professionalSql="SELECT name,first_name,middle_name FROM sp_service_professionals WHERE service_professional_id='".$RecordResult['professional_vender_id']."' ";
                $professionalDtls=$this->fetch_array($this->query($professionalSql));
                if(!empty($professionalDtls))
                {
                    $RecordResult['professionalNm']=$professionalDtls['name']." ".$professionalDtls['first_name']." ".$professionalDtls['middle_name'];
                }
                
                // Getting All consumption details 
                $ConsumptionSql="SELECT consumption_id,job_closure_id,consumption_type,unit_id,unit_quantity,status FROM sp_job_closure_consumption_mapping WHERE job_closure_id='".$RecordResult['job_closure_id']."'";
                $Consumption=$this->fetch_all_array($ConsumptionSql);	
                $recArr=array();
                foreach ($Consumption as $key => $valconsumption) 
                {
                    if($valconsumption['consumption_type']=='1' || $valconsumption['consumption_type']=='2') 
                    {
                        $GetConsumptionSql="SELECT name FROM sp_medicines WHERE medicine_id='".$valconsumption['unit_id']."'";
                        $ConsumptionNm=$this->fetch_array($this->query($GetConsumptionSql));
                        if(!empty($ConsumptionNm))
                        {
                               $valconsumption['name']=$ConsumptionNm['name'];
                        }
                        else 
                        {
                               $valconsumption['name']=""; 
                        } 
                    }
                    if($valconsumption['consumption_type']=='3' || $valconsumption['consumption_type']=='4') 
                    {
                        $GetConsumptionSql="SELECT name FROM sp_consumables WHERE consumable_id='".$valconsumption['unit_id']."'";
                        $ConsumptionNm=$this->fetch_array($this->query($GetConsumptionSql));
                        if(!empty($ConsumptionNm))
                        {
                               $valconsumption['name']=$ConsumptionNm['name'];
                        }
                        else 
                        {
                               $valconsumption['name']=""; 
                        }
                    }
                    $recArr[]=$valconsumption;
                }
                
                if(!empty($recArr))
                    $RecordResult['consumptions']=$recArr;
                else 
                   $RecordResult['consumptions']=""; 
                
                $resArr[]=$RecordResult; 
            }
            
            if(!empty($resArr))
                return $resArr;
            else 
               return 0;     
       }
        else 
	   return 0;
         
    }
    public function GetEventFeedback($arg)
    {
       $event_id=$this->escape($arg['event_id']);
       $GetFeedbackAnsSql="SELECT feedback_answer_id,service_date FROM sp_feedback_answers WHERE event_id='".$event_id."'";
       if($this->num_of_rows($this->query($GetFeedbackAnsSql)))
       {
           $FeedbackAns=$this->fetch_all_array($GetFeedbackAnsSql);
           
           // Getting Distinct Dates for Record List 
            $dateArray=array();
            foreach($FeedbackAns as $key=>$valDates)
            {
               $dateArray[]=$valDates['service_date'];         
            }
            // unique Dates 
            $UniqueDates=array_values(array_unique($dateArray));
            
             $ResultArr =array();
           //  $AllRecords=array();
            
            for($i=0;$i<count($UniqueDates);$i++)
            {
             // Fetching all Records of feedback answer
               $Sql="SELECT feedback_answer_id,event_id,feedback_id,option_id,answer,service_date FROM sp_feedback_answers WHERE event_id='".$event_id."' AND service_date='".$UniqueDates[$i]."'";
               $Result =$this->fetch_all_array($Sql);
               
               foreach($Result as $Key=>$valAns)
               {
                    $RecordSql="SELECT feedback_answer_id,event_id,feedback_id,option_id,service_date,answer FROM sp_feedback_answers WHERE feedback_answer_id='".$valAns['feedback_answer_id']."'";
                    $RecordResult =$this->fetch_array($this->query($RecordSql));
                    // Getting Feedback details 
                    $FeedbackSql="SELECT feedback_id,question,option_type FROM sp_feedback_form WHERE feedback_id='".$RecordResult['feedback_id']."'";
                    $FeedbackResult =$this->fetch_array($this->query($FeedbackSql));

                     $RecordResult['question']=$FeedbackResult['question'];
                     $RecordResult['option_type']=$FeedbackResult['option_type'];
                     // Getting Option values 
                    $FeedbackOptionSql="SELECT feedback_option_id,option_value FROM sp_feedback_options WHERE feedback_option_id='".$RecordResult['option_id']."'";
                    $FeedbackOptionResult =$this->fetch_array($this->query($FeedbackOptionSql));
                    $RecordResult['option_value']=$FeedbackOptionResult['option_value'];
                    unset($RecordResult['event_id']);
                    unset($RecordResult['feedback_id']);
                    unset($RecordResult['option_id']);
                    
                    $ResultArr[]=$RecordResult;   
               }
                $AllRecords[]=$ResultArr;
                unset($ResultArr);
            }
            if(count($AllRecords))
            {
                return $AllRecords;
            }
            else 
                return 0;  
       }
       else 
           return 0;
       
    }
    public function EventShareWithHCM($arg)
    {
        $preWhere="";
        $employee_id=$this->escape($arg['employee_id']); 
        $location_id=$this->escape($arg['location_id']); 
        $specialty=$this->escape($arg['specialty']);
        if(!empty($employee_id) && $employee_id !='null')
        {
            $preWhere="AND t1.employee_id='".$employee_id."'"; 
        }
        if(!empty($location_id) && $location_id !='null')
        {
          $preWhere .="AND t1.location_id='".$location_id."'"; 
        }
        if(!empty($specialty) && $specialty !='null')
        {
          $preWhere .="AND t1.specialization LIKE '%".$specialty."%'"; 
        }
        
        $GetHCMSql="SELECT t1.employee_id,t1.employee_code,t1.employee_code,t1.name,t1.name,t1.location_id,t1.specialization FROM sp_employees t1 ".
                   "WHERE 1 ".$preWhere." AND t1.status='1' AND t1.type='1'";
     
        if($this->num_of_rows($this->query($GetHCMSql)))
        {
             $GetHCM=$this->fetch_all_array($GetHCMSql);
             
             foreach($GetHCM AS $key=>$ValHCM)
             {
                // Get Traffic Details
                 $GetTrafficSql="SELECT event_share_id FROM sp_event_share_hcm WHERE assigned_to='".$ValHCM['employee_id']."' AND status='1' ";
                 if($this->num_of_rows($this->query($GetTrafficSql)))
                     $ValHCM['Traffic']=$this->num_of_rows($this->query($GetTrafficSql));
                 else 
                    $ValHCM['Traffic']="0";
                 
                 // Get Location Details
                $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$ValHCM['location_id']."' AND status='1' ";
                $LocationDtls=$this->fetch_array($this->query($LocationSql));
                $ValHCM['locationNm']=$LocationDtls['location']; 
                $ValHCM['LocationPinCode']=$LocationDtls['pin_code'];
             
                $this->result[]=$ValHCM;
             }
             if(count($this->result))
             {
                 return $this->result;
             }
             else 
                 return 0;
        }
        else 
            return 0;
    }

    /**
     *
     * This function is used for share event with HCM
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function AssignEventWithHCM($arg)
    {
        $assigned_to = $this->escape($arg['assigned_to']);
        $event_id    = $this->escape($arg['event_id']);
        
        $ChkAssignEventSql = "SELECT event_id 
        FROM sp_event_share_hcm 
        WHERE event_id = '" . $event_id . "' AND
        assigned_to = '" . $assigned_to . "'";

        if ($this->num_of_rows($this->query($ChkAssignEventSql)) == 0) {
           $insertData = array();
           $insertData['event_id']           = $this->escape($arg['event_id']);
           $insertData['assigned_to']        = $this->escape($arg['assigned_to']);
           $insertData['assigned_by']        = $this->escape($arg['assigned_by']);
           $insertData['status']             = $this->escape($arg['status']);
           $insertData['added_by']           = $this->escape($arg['added_by']);
           $insertData['added_date']         = $this->escape($arg['added_date']);
           $insertData['modified_by']        = $this->escape($arg['modified_by']);
           $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);
           $RecordId = $this->query_insert('sp_event_share_hcm', $insertData);
           if (!empty($RecordId)) {
               // Add activity while share event to HCM
               $this->addShareEventActivity($arg);
                return $RecordId; 
           } else {
                return 0;
           }
        }
        else { 
            return 0;
        }
    }
    public function GetAssessment($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $SearchfromDate= $this->escape($arg['SearchfromDate']);
        $SearchToDate= $this->escape($arg['SearchToDate']);
        $SearchByPurpose= $this->escape($arg['SearchByPurpose']);
        $SearchByEmployee= $this->escape($arg['SearchByEmployee']);
        
        $EmployeeId= $this->escape($arg['employee_id']);
        $isTrash=$this->escape($arg['isTrash']);
        
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (t4.hhc_code LIKE '%".$search_value."%' OR t3.event_code LIKE '%".$search_value."%' OR t2.name LIKE '%".$search_value."%')"; 
        }
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .="ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        if(!empty($SearchfromDate) && $SearchfromDate !='null' && !empty($SearchToDate) && $SearchToDate !='null')
        {
            $preWhere .= " and date(t1.added_date) BETWEEN  '".$SearchfromDate."' AND '".$SearchToDate."'  ";
        }
        if(!empty($isTrash) && $isTrash !='null')
        {
           $preWhere .="AND t1.status='3'"; 
        }
        else 
        {
          $preWhere .="AND t1.status IN ('1','2')";   
        }
        if(!empty($EmployeeId) && $EmployeeId !='null')
        {
            $preWhere .= " AND t1.assigned_to='".$EmployeeId."'";
        }
        
        if(!empty($EmployeeId) && $EmployeeId !='null')
        {
            $preWhere .= " AND t1.assigned_to='".$EmployeeId."'";
        }
        
        if(!empty($SearchByEmployee) && $SearchByEmployee !='null')
        {
            $preWhere .= " AND t1.added_by='".$SearchByEmployee."'";
        }
        
        if(!empty($SearchByPurpose) && $SearchByPurpose !='null')
        {
            $preWhere .= " AND t3.purpose_id='".$SearchByPurpose."'";
        }
  
        $AssessmentSql="SELECT t1.event_share_id FROM sp_events t3 ".
                        "INNER JOIN sp_event_share_hcm t1 ON t1.event_id=t3.event_id ".
                        "INNER JOIN sp_patients t4 ON t4.patient_id=t3.patient_id ".
                        "INNER JOIN sp_employees t2 ON t2.employee_id=t1.assigned_by ".
                        "WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($AssessmentSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($AssessmentSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT t1.event_share_id,t1.event_id,t1.assigned_by,t1.status,t1.added_by,t1.added_date FROM sp_event_share_hcm t1 WHERE t1.event_share_id='".$val_records['event_share_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // Getting shared by details  
                if(!empty($RecordResult['assigned_by']))
                {
                   $SharedBySql="SELECT employee_id,name FROM sp_employees WHERE employee_id='".$RecordResult['assigned_by']."'";
                   $SharedByDtls=$this->fetch_array($this->query($SharedBySql));
                   $RecordResult['shared_by']=$SharedByDtls['name']; 
                }
                
                if(!empty($RecordResult['added_date']) && $RecordResult['added_date'] !='0000-00-00 00:00:00')
                {
                   $RecordResult['shared_date']=date('d/m/Y',strtotime($RecordResult['added_date']));  
                }
                
                
                // Getting Event details  
                if(!empty($RecordResult['event_id']))
                {
                   $EventSql="SELECT event_id,event_code,patient_id,event_status,added_date,caller_id,purpose_id FROM sp_events WHERE event_id='".$RecordResult['event_id']."'";
                   $EventDtls=$this->fetch_array($this->query($EventSql));
                   
                   $RecordResult['event_code']=$EventDtls['event_code']; 
                   if(!empty($EventDtls['added_date']))
                        $RecordResult['event_date']=date('d-m-Y H:i:s A',strtotime($EventDtls['added_date']));
                   else 
                       $RecordResult['event_date']="Not Available";
                   
                   if(!empty($EventDtls['patient_id']))
                   {
                       $PatientSql="SELECT patient_id,hhc_code FROM sp_patients WHERE patient_id='".$EventDtls['patient_id']."'";
                       $PatientDtls=$this->fetch_array($this->query($PatientSql));
                       $RecordResult['hhc_code']=$PatientDtls['hhc_code'];
                   }
                    if(!empty($EventDtls['event_status']))
                    {
                       $StatusArr=array(1=>'20%',2=>'40%',3=>'60%',4=>'80%',5=>'100%');
                       $RecordResult['event_status']=$StatusArr[$EventDtls['event_status']];
                    }
                    
                    if(!empty($RecordResult['added_by']))
                    {
                       $AddedUserSql="SELECT name FROM sp_employees WHERE employee_id='".$RecordResult['added_by']."'";
                       $AddedUser=$this->fetch_array($this->query($AddedUserSql));
                       $RecordResult['added_by']=$AddedUser['name']; 
                    }
                   
                    if(!empty($EventDtls['purpose_id']))
                    {
                       $sql_call_purpose="SELECT name FROM sp_purpose_call WHERE purpose_id='".$EventDtls['purpose_id']."'";
                       $row_call_purpose=$this->fetch_array($this->query($sql_call_purpose));
                       $RecordResult['call_purpose']=$row_call_purpose['name']; 
                    }  
                    if(!empty($EventDtls['caller_id']))
                    {
                       $sql_caller="SELECT name FROM sp_callers WHERE caller_id='".$EventDtls['caller_id']."'";
                       $row_caller=$this->fetch_array($this->query($sql_caller));
                       $RecordResult['callerName']=$row_caller['name']; 
                    } 
                }
                $this->resultAssessment[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultAssessment))
        {
            $resultArray['data']=$this->resultAssessment;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetShareEventById($arg)
    {
        $event_share_id=$this->escape($arg['event_share_id']); 
        $ShareEventSql="SELECT event_share_id,event_id,assigned_to,assigned_by,status,added_by FROM sp_event_share_hcm ".
                       "WHERE event_share_id='".$event_share_id."'";
        
        if($this->num_of_rows($this->query($ShareEventSql)))
        {
            $ShareEvent=$this->fetch_array($this->query($ShareEventSql));
            return $ShareEvent;
        }
        else 
            return 0;   
    }

    public function InsertDetailPlanOfCare($arg)
    {
		$insertData['event_requirement_id'] = $arg['event_requirement_id'];
        $insertData['event_id']             = $arg['event_id'];
        $extras                             = $arg['extras'];
        $employee_id                        = $arg['employee_id'];

        for($v = 0; $v <= $extras; $v++)
        {
            $existIDPlan = '';
            $insertData['start_date'] = $_REQUEST['starttime_'.$v.'_'.$arg['event_requirement_id']];
			      $start_time = $_REQUEST['starttime_'.$v.'_'.$arg['event_requirement_id']];
            $insertData['end_date'] = $_REQUEST['endtime_'.$v.'_'.$arg['event_requirement_id']]; 
			      $End_time = $_REQUEST['endtime_'.$v.'_'.$arg['event_requirement_id']]; 			
            $service_dates = $_REQUEST['eve_from_date_'.$v.'_'.$arg['event_requirement_id']];
            $service_date_tos = $_REQUEST['eve_to_date_'.$v.'_'.$arg['event_requirement_id']];
            
            $insertData['service_date']    = date('Y-m-d',strtotime($service_dates));
            $insertData['service_date_to'] = date('Y-m-d',strtotime($service_date_tos));
            $insertData['service_cost']    = $_REQUEST['hidden_costService_'.$v.'_'.$arg['event_requirement_id']];
            
            if ($v == 0) {
              $existIDPlan = $_REQUEST['existIDPlan_'. $arg['event_requirement_id']];
            }

            $select_exist = "SELECT plan_of_care_id FROM sp_event_plan_of_care WHERE plan_of_care_id = '" . $existIDPlan . "'";

            if (mysql_num_rows($this->query($select_exist))) {
                $insertData['last_modified_by']   = $employee_id;
                $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                $val_existRecord                  = $this->fetch_array($this->query($select_exist));
                $where                            = "plan_of_care_id ='" . $val_existRecord['plan_of_care_id'] . "' ";

                $date_from = $service_dates;   
                $date_from = strtotime($date_from);

                $date_to =  $service_date_tos; 
                $date_to = strtotime($date_to);

                $index_of_Session = 0;

                $selectdeatils_exist = "SELECT Detailed_plan_of_care_id, Session_status FROM sp_detailed_event_plan_of_care WHERE plan_of_care_id = '" . $existIDPlan . "'";

                //echo '<pre>selectdeatils_exist : <br>';
                //print_r($selectdeatils_exist);
                //echo '</pre>';

                if (mysql_num_rows($this->query($selectdeatils_exist))) {
                    $dtlPlanOfCare = $this->fetch_all_array($selectdeatils_exist);
                    $totalCount = count($dtlPlanOfCare);
                    $upcomingEventCount = 0;
                    $pendingEventCount = 0;
                    foreach ($dtlPlanOfCare AS $key => $valdtlPlanOfCare) {
                        if ($valdtlPlanOfCare['Session_status'] == '3') {
                            $upcomingEventCount +=1;
                        }

                        if ($valdtlPlanOfCare['Session_status'] == '1') {
                            $pendingEventCount += 1; 
                        }
                    }

                    //echo '<pre>totalCount : <br>';
                    //print_r($totalCount);
                    //echo '<br>upcomingEventCount<br>';
                    //print_r($upcomingEventCount);
                    //echo '<br>pendingEventCount<br>';
                    //print_r($pendingEventCount);
                    //echo '</pre>';
                    //exit;

                    if ($totalCount == $upcomingEventCount || $totalCount == $pendingEventCount) {
                          // Delete record and insert new record
                          $delDetailPlanOfCareSql = "DELETE FROM sp_detailed_event_plan_of_care WHERE plan_of_care_id = '" . $existIDPlan . "'";
                          $this->query($delDetailPlanOfCareSql);

                          for ($i = $date_from; $i <= $date_to; $i += 86400)
                          {
                              $index_of_Session++;          
                              $date                              = date('Y-m-d H:i:s', $i);
                              $combinedDT                        = date('Y-m-d ', strtotime("$date"));
                              $service_date_start_time           = date('H:i:s', strtotime("$start_time"));
                              $CombineStartTime                  = date('Y-m-d H:i:s', strtotime("$combinedDT $service_date_start_time"));
                              $combinedet                        = date('Y-m-d ', strtotime("$date"));
                              $service_date_end_time             = date('H:i:s', strtotime("$End_time"));
                              $CombineendTime                    = date('Y-m-d H:i:s', strtotime("$combinedet $service_date_end_time"));


                            // Get hours difference
                            if ($start_time > $End_time) {
                                $total = strtotime($start_time) - strtotime($End_time);
                            } else {
                                $total = strtotime($End_time) - strtotime($start_time);
                            }
                            
                            $hours   = floor($total / 60 / 60);
                            $minutes = round(($total - ($hours * 60 * 60)) / 60);

                            $convertedTime = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($start_time)));
                            $endDate = date('Y-m-d H:i:s',strtotime('+'.$hours.' hour', strtotime($CombineStartTime)));

                            $insertData['status']              = 2;
                            $insertData['index_of_Session']    = $index_of_Session;
                            $insertData['Session_status']      = 3;
                            $insertData['plan_of_care_id']     = $existIDPlan;
                            $insertData['start_date']          = $CombineStartTime;
                            $insertData['end_date']            = date('Y-m-d', strtotime($endDate)) ." " . $End_time;
                            $insertData['Actual_Service_date'] = $CombineStartTime;

                            //echo '<pre>insertData All New : <br>';
                            //print_r($insertData);
                            //echo '</pre>';

                            $RecordId = $this->query_insert('sp_detailed_event_plan_of_care', $insertData);
                          }
                    } else {
                        // get all upcomimg and pending event
                        $getUpcomingEvent = "SELECT Detailed_plan_of_care_id FROM sp_detailed_event_plan_of_care WHERE plan_of_care_id = '" . $existIDPlan . "' AND (Session_status ='1' || Session_status ='3')";

                        //echo '<pre>getUpcomingEvent : <br>';
                        //print_r($getUpcomingEvent);
                        //echo '</pre>';


                        if (mysql_num_rows($this->query($getUpcomingEvent))) {
                            $upEventList = $this->fetch_all_array($getUpcomingEvent);

                            foreach ($upEventList AS $key => $valUpEvent) {
                                $delUpEventSql = "DELETE FROM sp_detailed_event_plan_of_care WHERE Detailed_plan_of_care_id = '" . $valUpEvent['Detailed_plan_of_care_id'] . "'";

                                //echo '<pre>delUpEventSql : <br>';
                                //print_r($delUpEventSql);
                                //echo '</pre>';

                                $this->query($delUpEventSql);
                            }
                        }

                        // Now check existing event plan of care details and detail plan of care event dates

                        for ($i = $date_from; $i <= $date_to; $i += 86400)
                        {      
                            $date                              = date('Y-m-d H:i:s', $i);
                            $combinedDT                        = date('Y-m-d ', strtotime("$date"));
                            $service_date_start_time           = date('H:i:s', strtotime("$start_time"));
                            $CombineStartTime                  = date('Y-m-d H:i:s', strtotime("$combinedDT $service_date_start_time"));
                            $combinedet                        = date('Y-m-d ', strtotime("$date"));
                            $service_date_end_time             = date('H:i:s', strtotime("$End_time"));
                            $CombineendTime                    = date('Y-m-d H:i:s', strtotime("$combinedet $service_date_end_time"));


                            // Get hours difference
                            if ($start_time > $End_time) {
                                $total = strtotime($start_time) - strtotime($End_time);
                            } else {
                                $total = strtotime($End_time) - strtotime($start_time);
                            }
                            
                            $hours   = floor($total / 60 / 60);
                            $minutes = round(($total - ($hours * 60 * 60)) / 60);

                            $convertedTime = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($start_time)));
                            $endDate = date('Y-m-d H:i:s',strtotime('+'.$hours.' hour', strtotime($CombineStartTime)));

                            $insertData['status']              = 2;
                            $insertData['index_of_Session']    = $index_of_Session;
                            $insertData['Session_status']      = 3;
                            $insertData['plan_of_care_id']     = $existIDPlan;
                            $insertData['start_date']          = $CombineStartTime;
                            $insertData['end_date']            = date('Y-m-d', strtotime($endDate)) ." " . $End_time;
                            $insertData['Actual_Service_date'] = $CombineStartTime;

                            //Check is it record exists of same day


                            $checkRecordExitsSql = "SELECT Detailed_plan_of_care_id FROM sp_detailed_event_plan_of_care WHERE plan_of_care_id = ' " . $existIDPlan . "' AND  start_date = ' " . $insertData['start_date'] . "'";

                            //echo '<pre>checkRecordExitsSql : <br>';
                            //print_r($checkRecordExitsSql);
                            //echo '</pre>';

                            if (mysql_num_rows($this->query($checkRecordExitsSql)) == 0) {

                                //echo '<pre>insertData Mixed records : <br>';
                                //print_r($insertData);
                                //echo '</pre>';

                                $RecordId = $this->query_insert('sp_detailed_event_plan_of_care', $insertData);
                            }  
                        }

                      //Now get all data and assign indexing to session
                      $getAllPlanOfCare = "SELECT Detailed_plan_of_care_id,index_of_Session FROM sp_detailed_event_plan_of_care WHERE plan_of_care_id = '" . $existIDPlan . "'
                        ORDER BY `start_date` ASC";

                       //echo '<pre>insertData : <br>';
                       //print_r($insertData);
                       //echo '</pre>';

                      if (mysql_num_rows($this->query($getAllPlanOfCare))) {
                          $planOfCareList = $this->fetch_all_array($getAllPlanOfCare);
                          foreach ($planOfCareList AS $key => $valPlanOfCare) {
                              $updateArr = array();
                              $index_of_Session++;

                              $updateArr['index_of_Session']    = $index_of_Session;
                              $where = "Detailed_plan_of_care_id ='" . $valPlanOfCare['Detailed_plan_of_care_id'] . "' ";

                              //echo '<pre>updateIndex : <br>' . $where . '<br>';
                              //print_r($updateArr);
                              //echo '</pre>';

                              $this->query_update('sp_detailed_event_plan_of_care', $updateArr, $where);
                          }
                      }
                    }
                } else {
                    $insertData['added_by'] = $employee_id;
                    $insertData['added_date'] = date('Y-m-d H:i:s');

                    $event_requirement_id = $arg['event_requirement_id'];
                    $sql = mysql_query("SELECT plan_of_care_id FROM sp_event_plan_of_care WHERE event_requirement_id = '$event_requirement_id' ");

                    $result = mysql_fetch_array($sql);
                    $plan_of_care_id = $result['plan_of_care_id'];
                    $date_from = $service_dates;   
                    $date_from = strtotime($date_from);
                    $date_to =  $service_date_tos; 
                    $date_to = strtotime($date_to);
                    $index_of_Session = 0;

                    for ($i = $date_from; $i <= $date_to; $i+=86400) {
                        $index_of_Session++;              
                        $date = date('Y-m-d H:i:s', $i);
                        $combinedDT = date('Y-m-d ', strtotime("$date"));
                        $service_date_start_time = date('H:i:s', strtotime("$start_time"));
                        $CombineStartTime = date('Y-m-d H:i:s', strtotime("$combinedDT $service_date_start_time"));
                        $combinedet = date('Y-m-d ', strtotime("$date"));
                        $service_date_end_time = date('H:i:s', strtotime("$End_time"));
                        $CombineendTime = date('Y-m-d H:i:s', strtotime("$combinedet $service_date_end_time"));

                        // Get hours difference
                        if ($start_time > $End_time) {
                            $total = strtotime($start_time) - strtotime($End_time);
                        } else {
                            $total = strtotime($End_time) - strtotime($start_time);
                        }
                        
                        $hours   = floor($total / 60 / 60);
                        $minutes = round(($total - ($hours * 60 * 60)) / 60);

                        $convertedTime = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($start_time)));
                        $endDate = date('Y-m-d H:i:s',strtotime('+'.$hours.' hour', strtotime($CombineStartTime)));
      
                        $insertData['status'] = 2;
                        $insertData['index_of_Session'] = $index_of_Session;
                        $insertData['Session_status'] = 3;
                        $insertData['plan_of_care_id'] = $plan_of_care_id;
                        $insertData['start_date'] = $CombineStartTime;
                        $insertData['end_date'] = date('Y-m-d', strtotime($endDate)) . " " .  $End_time;
                        $insertData['Actual_Service_date'] = $CombineStartTime;
                        $RecordId = $this->query_insert('sp_detailed_event_plan_of_care',$insertData);
                    }
                }
            } else {
                $eventRequirementId = $arg['event_requirement_id'];

                if (!empty($eventRequirementId)) {
                    // Get event plan of care details 
                    $getPlanOfCareSql = "SELECT plan_of_care_id,
                                            event_id,
                                            event_requirement_id,
                                            professional_vender_id,
                                            service_date,
                                            service_date_to,
                                            start_date,
                                            end_date,
                                            service_cost,
                                            status
                                        FROM sp_event_plan_of_care
                                        WHERE event_requirement_id = '" . $eventRequirementId . "' AND  status = 1";

                    //echo '<pre>getPlanOfCareSql <br>';
                    //print_r($getPlanOfCareSql);
                    //echo '</pre>';
                    
                    if (mysql_num_rows($this->query($getPlanOfCareSql))) {
                        $planOfCareDetails = $this->fetch_all_array($getPlanOfCareSql);

                        //echo '<pre>planOfCareDetails <br>';
                        //print_r($planOfCareDetails);
                        //echo '</pre>';

                        if (!empty($planOfCareDetails)) {
                            foreach ($planOfCareDetails AS $key => $valPlanOfCare) {
                                $insertPlanOfCareData = array();
                                $insertPlanOfCareData['plan_of_care_id'] = $valPlanOfCare['plan_of_care_id'];
                                $insertPlanOfCareData['event_id'] = $valPlanOfCare['event_id'];
                                $insertPlanOfCareData['event_requirement_id'] = $valPlanOfCare['event_requirement_id'];

                                // Get Service start date and end date
                                $serviceStartDate = $valPlanOfCare['service_date'];
                                $serviceEndDate = $valPlanOfCare['service_date_to'];

                                // Calculate date difference
                                $dateDiff = $this->dateDiff($serviceStartDate, $serviceEndDate);

                                //echo '<pre>dateDiff <br>';
                                //print_r($dateDiff);
                                //echo '</pre>';

                                // Get last index
                                $getIndexSql = "SELECT index_of_Session
                                    FROM sp_detailed_event_plan_of_care WHERE event_id = '" . $valPlanOfCare['event_id'] . "'
                                    AND event_requirement_id = '" . $valPlanOfCare['event_requirement_id'] . "'
                                    ORDER BY index_of_Session DESC LIMIT 0,1";

                                //echo '<pre>getIndexSql <br>';
                                //print_r($getIndexSql);
                                //echo '</pre>';

                                $sessionIndex = 0;

                                if (mysql_num_rows($this->query($getIndexSql))) {
                                    $lastRecordIndex = $this->fetch_array($this->query($getIndexSql));
                                    $sessionIndex = $lastRecordIndex['index_of_Session'];
                                }

                                //echo '<pre>sessionIndex <br>';
                                //print_r($sessionIndex);
                                //echo '</pre>';

                                for ($i = 0; $i <= $dateDiff; $i++) {

                                    //Convert date 
                                    $startDateVal = strtotime("+".$i." day", strtotime($valPlanOfCare['service_date']));
                                    $startDate = date("Y-m-d", $startDateVal);

                                    // Convet time into 24 hours format 
                                    $startTime = date("H:i", strtotime($valPlanOfCare['start_date']));
                                    $endTime = date("H:i", strtotime($valPlanOfCare['end_date']));

                                    // Get hours difference
                                    if ($startTime > $endTime) {
                                        $total      = strtotime($startTime) - strtotime($endTime);
                                    } else {
                                        $total      = strtotime($endTime) - strtotime($startTime);
                                    }
                                    
                                    $hours      = floor($total / 60 / 60);
                                    $minutes    = round(($total - ($hours * 60 * 60)) / 60);

                                    // echo '<pre>hours <br>';
                                    // print_r($hours);
                                    // echo '</pre>';

                                    // create complete start date
                                    $completeStartDate = $startDate . " " . $startTime;

                                    //echo '<pre>completeStartDate <br>';
                                    //print_r($completeStartDate);
                                    //echo '</pre>';

                                    $cenvertedTime = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($startTime)));

                                    $endDate = date('Y-m-d H:i:s',strtotime('+'.$hours.' hour', strtotime($completeStartDate)));

                                    // echo '<pre>endDate <br>';
                                    // print_r($endDate);
                                    // echo '</pre>';

                                    $insertPlanOfCareData['service_date'] = $valPlanOfCare['service_date'];
                                    $insertPlanOfCareData['service_date_to'] = $valPlanOfCare['service_date_to'];
                                    $insertPlanOfCareData['Actual_Service_date'] = $startDate . " " . $startTime;
                                    $insertPlanOfCareData['start_date'] = $startDate . " " . $startTime;
                                    $insertPlanOfCareData['end_date'] = date('Y-m-d', strtotime($endDate)) ." " . $endTime;
                                    $insertPlanOfCareData['service_cost'] = $valPlanOfCare['service_cost'];
                                    $insertPlanOfCareData['index_of_Session'] = $sessionIndex + 1;
                                    $insertPlanOfCareData['status'] = '2';
                                    $insertPlanOfCareData['added_by'] = $employee_id;
                                    $insertPlanOfCareData['added_date'] = date('Y-m-d H:i:s');
                                    $insertPlanOfCareData['modified_user_id'] = $employee_id;
                                    $insertPlanOfCareData['last_modified_by'] = '2';
                                    $insertPlanOfCareData['last_modified_date'] = date('Y-m-d H:i:s');
                                    $insertPlanOfCareData['Session_status'] = '3';

                                    //echo '<pre>insertPlanOfCareData <br>';
                                    //print_r($insertPlanOfCareData);
                                    //echo '</pre>';

                                    $chkRecordSql = "SELECT Detailed_plan_of_care_id FROM sp_detailed_event_plan_of_care
                                    WHERE plan_of_care_id = '" . $valPlanOfCare['plan_of_care_id'] . "' AND 
                                        event_id = '" . $valPlanOfCare['event_id'] . "' AND 
                                        event_requirement_id = '" . $valPlanOfCare['event_requirement_id'] . "' AND 
                                        start_date = '" . $insertPlanOfCareData['start_date'] . "' AND 
                                        end_date = '" . $insertPlanOfCareData['end_date'] . "'
                                    ";

                                    //echo '<pre>chkRecordSql '.$sessionIndex.' <br>';
                                    //print_r($chkRecordSql);
                                    //echo '</pre>';

                                    
                                    if (mysql_num_rows($this->query($chkRecordSql)) == 0 ) {
                                        $this->query_insert('sp_detailed_event_plan_of_care', $insertPlanOfCareData);
                                        $sessionIndex++;
                                    }
                                }
                                // Clear data
                                unset($serviceStartDate,$serviceEndDate,$dateDiff,$sessionIndex, $valPlanOfCare);
                            }
                        }
                    }
                }  
            }  
        }
	 }

    /**
     *
     * This function is used for add plan of care details
     *
     * @param array $arg
     *
     * @return int $recordId 
     */
    public function InsertPlanOfCare($arg)
    {
        // Get event details
        $eventDtls = $this->GetEvent($arg);

        $insertData['event_requirement_id'] = $arg['event_requirement_id'];

		$event_requirement_id = $arg['event_requirement_id'];
		$sub_service = mysql_query("SELECT * FROM sp_event_requirements  where event_requirement_id='$event_requirement_id'");
		$sub_service_id_new = mysql_fetch_array($sub_service) or die(mysql_error());
		$service_id = $sub_service_id_new['service_id'];
		$sub_service_id = $sub_service_id_new['sub_service_id'];
		
        $insertData['event_id'] = $arg['event_id'];
        $extras = $arg['extras'];
        $employee_id = $arg['employee_id'];
        for ($v = 0; $v <= $extras; $v++) {
            $existIDPlan = '';
            $insertData['start_date'] = $_REQUEST['starttime_' . $v . '_' . $arg['event_requirement_id']];
            $insertData['end_date']   = $_REQUEST['endtime_' . $v . '_' . $arg['event_requirement_id']];            
            $service_dates            = $_REQUEST['eve_from_date_' . $v . '_' . $arg['event_requirement_id']];
			$service_date_tos         = $_REQUEST['eve_to_date_' . $v . '_' . $arg['event_requirement_id']];
			
			if ($service_dates!='') {
                $insertData['service_date'] =  date('Y-m-d', strtotime($service_dates));
            } else {
                $insertData['service_date'] = '';
            }
				
			if (($service_id == 17 || $service_id == 13) && $sub_service_id != 425) {
				if ($service_dates != '') {
                    $todate=date('Y-m-d',strtotime($service_dates));
                    $pkgdate= date('d-m-Y', strtotime('+30 day', strtotime($todate)));
                    $insertData['service_date_to'] =  date('Y-m-d', strtotime($pkgdate));
				} else {
					$insertData['service_date_to'] =  '';
				}
			} else {
				$insertData['service_date_to'] =  date('Y-m-d', strtotime($service_date_tos));
			}

            $insertData['service_cost'] =  $_REQUEST['hidden_costService_' . $v . '_' . $arg['event_requirement_id']];
            
            if ($v == 0) {
                $existIDPlan = $_REQUEST['existIDPlan_' . $arg['event_requirement_id']];
            }
           
            $select_exist = "SELECT plan_of_care_id,
                event_requirement_id,
                event_id,
                start_date,
                end_date,
                service_date,
                service_date_to,
                service_cost,
                last_modified_by,
                last_modified_date
            FROM sp_event_plan_of_care 
            WHERE plan_of_care_id = '" . $existIDPlan . "'";

            if (mysql_num_rows($this->query($select_exist))) {
                $insertData['last_modified_by']   = $employee_id;
                $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                
                $val_existRecord = $this->fetch_array($this->query($select_exist));
                
                $where = "plan_of_care_id ='" . $val_existRecord['plan_of_care_id'] . "' ";
                $updatePlanOfCareRecord = $this->query_update('sp_event_plan_of_care', $insertData, $where);

                if (!empty($updatePlanOfCareRecord)) {
                    unset($val_existRecord['plan_of_care_id'], $val_existRecord['event_requirement_id'], $val_existRecord['event_id']);

                    $planOfCareDiff = array_diff_assoc($val_existRecord, $insertData);

                    if (!empty($planOfCareDiff)) {
                        $insertActivityArr = array();
                        $insertActivityArr['module_type']          = '1';
                        $insertActivityArr['module_id']            = '';
                        $insertActivityArr['module_name']          = 'Edit Plan of care Details';
                        $insertActivityArr['purpose_id']           = ($eventDtls['purpose_id'] ? $eventDtls['purpose_id'] : "");
                        $insertActivityArr['event_id']             = $arg['event_id'];
                        $str = "";
                        foreach ($val_existRecord AS $key => $valPlanOfCareResult) {
                            $str .= $key . " is changed from " . $valPlanOfCareResult . " to " . $insertData[$key] . "\r\n";
                        }
                        $insertActivityArr['activity_description'] = (!empty($str) ? nl2br($str) : "");
                        $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
                        $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');
                        $this->query_insert('sp_user_activity', $insertActivityArr);
                        unset($insertActivityArr, $str);
                    }
                }
                //Set record id
                $RecordId = $val_existRecord['plan_of_care_id'];
            } else {
                $insertData['added_by'] = $employee_id;
                $insertData['added_date'] = date('Y-m-d H:i:s');
                $insertData['status'] = '1';
                $RecordId = $this->query_insert('sp_event_plan_of_care', $insertData);

                // Added activity log while successfully add event paln of care details
                if (!empty($RecordId)) {
                    $activityDesc = "Plan of care details added successfully for " . $eventDtls['event_code'] . "  by " . $_SESSION['emp_nm'] . "\r\n";
                    $activityDesc .= "Details are as follows \r\n";

                    $activityDesc .= "service_date : " . $insertData['service_date'] . "\r\n";
                    $activityDesc .= "service_date_to : " . $insertData['service_date_to'] . "\r\n";
                    $activityDesc .= "start_date : " . $insertData['start_date'] . "\r\n";
                    $activityDesc .= "end_date : " . $insertData['end_date'] . "\r\n";
                    $activityDesc .= "service_cost : " . $insertData['service_cost'] . "\r\n";
                    
                    $insertActivityArr = array();
                    $insertActivityArr['module_type']          = '1';
                    $insertActivityArr['module_id']            = '';
                    $insertActivityArr['module_name']          = 'Add Plan of care Details';
                    $insertActivityArr['purpose_id']           = ($eventDtls['purpose_id'] ? $eventDtls['purpose_id'] : "");
                    $insertActivityArr['event_id']             = $arg['event_id'];
                    $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");

                    $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
                    $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
                    $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

                    $this->query_insert('sp_user_activity', $insertActivityArr);

                    unset($insertActivityArr);
                }
            }
        }

        // Get event details
        $getEventDtlsSql = "SELECT estimate_cost,
                discount_type,
                discount_value,
                discount_amount,
                Invoice_narration,
                finalcost
            FROM sp_events
            WHERE event_id = '" . $arg['event_id'] . "' ";

        if (mysql_num_rows($this->query($getEventDtlsSql))) {
            $eventActualDtls = $this->fetch_array($this->query($getEventDtlsSql));
        }

        $updateEve['estimate_cost'] = '3';

        /* Discount code start here */
        $updateEve['discount_type'] = $arg['discount_type'];
        $updateEve['discount_value'] = $arg['discount_value'];
        $updateEve['discount_amount'] = $arg['discount_amount'];
        $updateEve['Invoice_narration'] = $arg['discount_narration'];

        // Generate invoice_narration

        $serviceActualCost = $arg['finalcost_eve'] + $updateEve['discount_amount'];

        $narrationContent = $updateEve['Invoice_narration'] . ($arg['invoice_narration_desc'] ? " - " . $arg['invoice_narration_desc'] : '') .
        " - Service Actual Cost - " . $serviceActualCost .
        " - Discount  Amount - " . $updateEve['discount_amount'] .
        " - Service Final Cost - " . $arg['finalcost_eve'];

        $updateEve['invoice_narration_desc'] = $narrationContent;

        /* Discount code start here */

        $updateEve['finalcost'] = $arg['finalcost_eve'];

        $whereEve = "event_id ='" . $arg['event_id'] . "' ";
        $updateRecord = $this->query_update('sp_events', $updateEve, $whereEve);

        // Add activity details for event while insert plan of care detail
        if (!empty($updateRecord) && !empty($eventActualDtls)) {
            $diffResult = array_diff_assoc($eventActualDtls, $updateEve);
            if (!empty($diffResult)) {
                $insertActivityArr = array();
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name'] = 'Add Plan of care Details';
                $insertActivityArr['purpose_id']  = $eventDtls['purpose_id'];
                $insertActivityArr['event_id']    = $arg['event_id'];
                $str = "";
                foreach ($eventActualDtls AS $key => $valEventResult) {
                    $str .= $key . " is changed from " . $valEventResult . " to " . $updateEve[$key] . "\r\n";
                }

                $insertActivityArr['activity_description'] = (!empty($str) ? nl2br($str) : "");
                $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
                $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
            }
        }
            
        return $RecordId;
    }

    public function GetPatientById($arg)
    {
        $patient_id=$this->escape($arg['patient_id']);
        $GetOnePatientSql="SELECT patient_id,hhc_code,name,first_name,middle_name,email_id,residential_address,permanant_address,location_id,phone_no,mobile_no,dob,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date,lattitude,langitude,google_location FROM sp_patients WHERE patient_id='".$patient_id."'";
        if($this->num_of_rows($this->query($GetOnePatientSql)))
        {
            $Patient = $this->fetch_array($this->query($GetOnePatientSql));
            
            // Getting Location Name
            if($Patient['google_location']=='')
            {
                if(!empty($Patient['location_id']))
                {
                   $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$Patient['location_id']."'";
                   $LocationDtls=$this->fetch_array($this->query($LocationSql));
                   $Patient['locationNm']=$LocationDtls['location']; 
                   $Patient['LocationPinCode']=$LocationDtls['pin_code']; 
                }
            }
            else
                $Patient['locationNm']=$Patient['google_location']; 
            
            // Getting Status
            if(!empty($Patient['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Patient['statusVal']=$StatusArr[$Patient['status']];
            }
            
            // Getting Added User Name 
            if(!empty($Patient['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Patient['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Patient['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Patient['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Patient['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Patient['last_modified_by']=$ModifiedUser['name'];
            }
            return $Patient;
        }
        else 
            return 0;            
    }
    public function GetConsultantByPatient($arg)
    {
        $preWhere="";
        $event_id=$this->escape($arg['event_id']);
        $patient_id=$this->escape($arg['patient_id']);
        $type=$this->escape($arg['type']);
        
        if(!empty($type))
        {
            if($type=='1')
                $preWhere="AND type='1'";
            else 
                $preWhere="AND type='2'";
        }
        
        $GetAllConsultantSql="SELECT event_doctor_id,doctor_consultant_id,type FROM sp_event_doctor_mapping WHERE event_id='".$event_id."' AND patient_id='".$patient_id."' ".$preWhere." ";
        if($this->num_of_rows($this->query($GetAllConsultantSql)))
        {
            $AllConsultant=$this->fetch_array($this->query($GetAllConsultantSql));
            $GetConsultantSql="SELECT doctors_consultants_id,name,first_name,middle_name,email_id,phone_no,mobile_no,type FROM sp_doctors_consultants WHERE doctors_consultants_id='".$AllConsultant['doctor_consultant_id']."'";
            $Consultant=$this->fetch_array($this->query($GetConsultantSql));
            
            if($Consultant)
                return $Consultant;
            else 
                return 0;
            
        }
        else 
            return 0; 
    }
    public function GetServiceProfessionals($arg)
    {
        $event_id = $arg['event_id'];
        $service_id = $arg['service_id'];
        
        $preWhere="";
        $filterWhere="";
        
        $search_value = $this->escape($arg['search_Value']);
        $availability = $this->escape($arg['availability']);
        $location_id = $this->escape($arg['location_id']);
        $filter_name = $this->escape($arg['filter_name']);
        $filter_type = $this->escape($arg['filter_type']);
        $kmsliderfrom = $this->escape($arg['kmsliderfrom']);
        $kmsliderto = $arg['kmsliderto'];
        
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (sp.name LIKE '%".$search_value."%' || sp.first_name LIKE '%".$search_value."%' || sp.middle_name LIKE '%".$search_value."%' || CASE WHEN set_location=1 THEN sp.google_home_location else sp.google_work_location END LIKE '%".$search_value."%' ||  sp.email_id LIKE '%".$search_value."%'   )"; 
        }
        //if($availability)
            //$preWhere = " and psr.availability = '".$availability."' ";
        if($location_id)
        {
            $preWhereLoc = " and sp.location_id = '".$location_id."' ";
        }
        else 
            $preWhereLoc = '';
        
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .="ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        
        if($availability)
        {            
                $select_exist = "select distinct professional_vender_id from sp_event_professional where service_closed = 'N' ";
                $ptr_exist = $this->fetch_all_array($select_exist);
                foreach($ptr_exist as $key=>$valExtProf)
                {
                    $professional_vender_ids[] = $valExtProf['professional_vender_id'];
                }
                $profimplode = implode(",",$professional_vender_ids);
            if($profimplode)
            {
            if($availability == '2')
                $preWhereProf = " and sp.service_professional_id IN($profimplode)";
            else
                $preWhereProf = " and sp.service_professional_id NOT IN($profimplode)";
            }
        }
        //echo $kmsliderto;
        $lat = '';  $long = ''; $lat1 = ''; $long2='';
        if(!empty($kmsliderto))
        {
            //echo $event_id;
            $recProf['event_id'] = $event_id;
            $EventResponsed= $this->GetEvent($recProf);
            $patArg['patient_id'] = $EventResponsed['patient_id'];
            $patientHHCresponse = $this->GetPatientById($patArg);
            //print_r($EventResponsed);
            $lat = $patientHHCresponse['lattitude'];
            $long = $patientHHCresponse['langitude'];
			//Get event requirement details
			$eventReqDtls = $this->getEventRequirementDtls($event_id,$service_id);
			
			$subServiceId = 0;
			$join = "";
			if (!empty($eventReqDtls)) {
				$subServiceId = $eventReqDtls[0]['sub_service_id'];
				$join .= "INNER JOIN sp_professional_sub_services psser ON psr.service_id = psser.service_id AND psser.sub_service_id = '" . $subServiceId . "' ";
			}	
        }
        
      //   psr.service_id = '2' and psr.status = '1' and sp.status = '1' AND (sp.name LIKE '%kothrud%' || sp.first_name LIKE '%kothrud%' || sp.middle_name LIKE '%kothrud%' || sp.google_home_location LIKE '%kothrud%' || CASE WHEN set_location=1 THEN sp.google_home_location else sp.google_work_location END LIKE '%kothrud%' || sp.email_id LIKE '%kothrud%' )
                
        $select_Professional = "select sp.service_professional_id from 
                                sp_service_professionals as sp left join sp_professional_services as psr ON sp.service_professional_id = psr.service_professional_id
								" . $join . "
                                where  psr.service_id = '" . $service_id . "' and psr.status = '1' and sp.status = '1' AND sp.document_status = '1' " . $preWhere . " " . $preWhereLoc . " " . $preWhereProf . " GROUP BY sp.service_professional_id";
								
		//echo '<pre>';
		//print_r($select_Professional);
		//echo '</pre>';
		//exit;
								
								
								
        $this->result = $this->query($select_Professional);
        if ($this->num_of_rows($this->result))
        {
            $this->resultProfessional = array();
            while($val_records=$this->fetch_array($this->result))
            {
                $select_details = "select professional_code, reference_type,title,name,first_name,middle_name,email_id,phone_no,mobile_no,dob,address,work_email_id,work_phone_no,work_address,location_id,lattitude,langitude,google_home_location,google_work_location,set_location from sp_service_professionals where service_professional_id = '".$val_records['service_professional_id']."' ";
                $data_val = $this->fetch_array($this->query($select_details));
                $val_records['professional_code'] = $data_val['professional_code'];
                $val_records['reference_type'] = $data_val['reference_type'];
                $val_records['name'] = $data_val['name'];
                $val_records['first_name'] = $data_val['first_name'];
                $val_records['middle_name'] = $data_val['middle_name'];
                $val_records['email_id'] = $data_val['email_id'];
                $val_records['phone_no'] = $data_val['phone_no'];
                $val_records['mobile_no'] = $data_val['mobile_no'];
                $val_records['address'] = $data_val['address'];
                $val_records['distanceKM'] = '';
                
                $select_details_spec = "select qualification,skill_set from sp_service_professional_details where service_professional_id = '".$val_records['service_professional_id']."' ";
                $data_val_spec = $this->fetch_array($this->query($select_details_spec));
                $val_records['qualification'] = $data_val_spec['qualification'];
                $val_records['skill_set'] = $data_val_spec['skill_set'];
                $locationNm = '';
                $val_records['set_location'] = $data_val['set_location'];
                if($data_val['set_location'] == '1')
                {
                    $locations = $data_val['location_id_home'];
                    $google_location = $data_val['google_home_location'];                    
                }
                else
                {
                    $locations = $data_val['location_id'];
                    $google_location = $data_val['google_work_location'];
                }
                if($google_location == '')
                {
                    $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$locations."'";
                    $LocationDtls=$this->fetch_array($this->query($LocationSql));
                    if($LocationDtls['location'])
                        $locationNm=$LocationDtls['location']; 
                }
                else
                    $locationNm = $google_location;
            
                /*$LocationSql="SELECT location,pin_code FROM sp_locations WHERE location_id='".$data_val['location_id']."'";
                $LocationDtls=$this->fetch_array($this->query($LocationSql));
                $val_records['location']=$LocationDtls['location'];*/
                $val_records['location'] = $locationNm;
                $distanceKM = '';

                // echo '<pre>service_professional_id :- <br>';
                // print_r($val_records['service_professional_id']);
                // echo "<br>kmsliderto :-<br>". $kmsliderto . "<br>";

                if($kmsliderto)
                {
                    //print_r($data_val);
                    $lat1 = $data_val['lattitude'];
                    $long2 = $data_val['langitude'];
                    $units = 'K';

                    // echo "<br>" . $kmsliderfrom . '.... <br>';
                    // echo $lat . '-' . $lat1;
                    // echo '<br>';
                    // echo $long.'-'.$long2;
                    // echo '<br>';

                    if($lat && $long && $lat1 && $long2)
                        $distanceKM = distance($lat, $long, $lat1, $long2, $units);
                    else
                        $distanceKM = '';
                    // echo 'kmsliderto- <br>'. $kmsliderto.'<br>';
                    // echo 'distanceKM<br>' . $distanceKM . '<br>';


                    $val_records['distanceKM'] = $distanceKM;

                    if($distanceKM)
                    {
                        if($distanceKM>$kmsliderfrom && $distanceKM<$kmsliderto)
                        {
                           // echo '1..';
                            $this->resultProfessional[]=$val_records;
                        }
                        //else
                            //$this->resultProfessional[] = array();
                    }
                    else
                        $this->resultProfessional[]=$val_records;
                }
                else                
                    $this->resultProfessional[]=$val_records;
            }
            $resultArray['count'] = $this->num_of_rows($this->result);
        }
        if(count($this->resultProfessional))
        {
            $resultArray['data']=$this->resultProfessional;

            // echo '$resultArray ---- <br>';
            // print_r($resultArray['data']);
            // echo '</pre>';
            //exit;


            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0);
    }
    public function InsertProfessional($arg)
    {
        $countProfarray = '';
        $newReqArr      = $arg['professional_vender_id'];

        // Get event details
        $eventDtls = $this->GetEvent($arg);

        $selectprof_existing = "select professional_vender_id from sp_event_professional where event_id = '".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ";
        $allRequiremprof = $this->fetch_all_array($selectprof_existing);
        foreach ($allRequiremprof AS $valAllRequirements) {
            $countProfarray[] = $valAllRequirements['professional_vender_id'];
        }
        /* ------------------ delete/check existing services  -------- */
        $new_array = $newReqArr;
        $existArray = $countProfarray;
        if ($new_array && $existArray) {
            $intersect = array_intersect($new_array,$existArray);
            if (!empty($intersect)) {
                $comma_separated = implode(",", $intersect);
            }

            $exist_separated = implode(",", $existArray);
            if(!empty($comma_separated))
            {
                $preDelete = ' AND professional_vender_id NOT IN ('.$comma_separated.')';
            }
            
            // Update Flag of job summary table record 
            
            $GetEventProfSql="SELECT event_professional_id FROM sp_event_professional WHERE event_id = '".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' $preDelete ";
            if(mysql_num_rows($this->query($GetEventProfSql)))
            {
                $GetEventProf=$this->fetch_all_array($GetEventProfSql);
                foreach($GetEventProf as $valIds)
                {
                    $UpdateSql="UPDATE sp_event_job_summary SET isDelStatus='1' WHERE event_professional_id='".$valIds['event_professional_id']."'";
                    $this->query($UpdateSql);
                }
            }
            
            // First getting all records for delete job summary of unwanted professional 
            
            $GetAllProfDtlsSql="SELECT event_professional_id,event_id,professional_vender_id,service_id FROM sp_event_professional WHERE event_id='".$arg['event_id']."' and event_requirement_id='".$arg['event_requirement_id']."' $preDelete ";
            if (mysql_num_rows($this->query($GetAllProfDtlsSql))) {
                $GetAllProfDtls=$this->fetch_all_array($GetAllProfDtlsSql);
                foreach($GetAllProfDtls as $valProfIds) {
                    // Delete Design job summary of unwanted professional 
                    $deleteUnwantedSummary="DELETE FROM sp_event_job_summary WHERE event_id='".$valProfIds['event_id']."' AND service_id='".$valProfIds['service_id']."' AND event_professional_id='".$valProfIds['event_professional_id']."'";
                    $this->query($deleteUnwantedSummary);
                }   
            }
            $deleteUnwanted = "DELETE FROM sp_event_professional WHERE event_id = '".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' $preDelete ";
            $this->query($deleteUnwanted);

            $updateAvailability1 = "update sp_professional_services set availability = '1' where service_professional_id IN (".$exist_separated.") ";
            $this->query($updateAvailability1);
            
            $diff = array_merge(array_diff($new_array, $intersect), array_diff($intersect, $new_array));
        }
        else {
            $diff = $new_array;
        }
        /* -------- complete delete/check existing services --------- */
        
        $totalCount = count($diff);
	
        if ($totalCount) {
            $professionalIds = array();
            for ($i = 0; $i < $totalCount; $i++) {
                $selectExist = "SELECT event_professional_id,
                    modified_by,
                    professional_vender_id,
                    last_modified_date
                FROM sp_event_professional
                WHERE professional_vender_id = '" . $diff[$i] . "' AND
                    event_requirement_id = '" . $arg['event_requirement_id'] . "' AND
                    event_id = '" . $arg['event_id'] . "'";

                if (mysql_num_rows($this->query($selectExist))) {
                    $valProf = $this->fetch_array($this->query($selectExist));
                    $arg['modified_by'] = $arg['added_by'];
                    $arg['professional_vender_id'] = $diff[$i];
                    $arg['last_modified_date'] = date('Y-m-d H:i:s');
                    $whereEve = "event_professional_id ='" . $valProf['event_professional_id'] . "' ";
                    
                    $updateProf = $this->query_update('sp_event_professional', $arg, $whereEve);

                    if (!empty($updateProf)) {
                        $profDiff = array_diff_assoc($valProf, $arg);
                        if (!empty($profDiff)) {
                            foreach ($valProf AS $key => $profData) {
                                $activityDesc .= $key . " is changed from " . $profData . " to " . $arg[$key] . "\r\n";
                            }
                        }
                    }
                    $RecordId = $valProf['event_professional_id'];
                } else {
                    $arg['professional_vender_id'] = $diff[$i];
                    mysql_query("update sp_event_plan_of_care set professional_vender_id='".$diff[$i]."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
					mysql_query("update sp_event_requirements set professional_vender_id='".$diff[$i]."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
                    $arg['added_date'] = date('Y-m-d H:i:s');            
                    $RecordId = $this->query_insert('sp_event_professional', $arg);
                }

                // Assigned Professional Data
                $professionalIds[] = $diff[$i];
            }

            // Add activity while assiging professional to event
            $insertActivityArr = array();
            $insertActivityArr['module_type'] = '1';
            $insertActivityArr['module_id']   = '';
            $insertActivityArr['module_name'] = 'Add Event Professional Details';
            $insertActivityArr['purpose_id']  = $eventDtls['purpose_id'];
            $insertActivityArr['event_id']    = $eventDtls['event_id'];
            $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
            $insertActivityArr['added_by_id']   = $_SESSION['employee_id'];
            $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');

            // Get Professional Details
            if (!empty($professionalIds)) {
                foreach ($professionalIds AS $valProfId) {
                    $getProfessionalSql = "SELECT 
                    CONCAT(first_name,' ', name) AS professional_name
                    FROM sp_service_professionals
                    WHERE service_professional_id = '" . $valProfId . "'";
                    $profDtls = $this->fetch_array($this->query($getProfessionalSql));
                }
            }

            $activityDesc = "Professional " . $profDtls['professional_name'] . " is assigned for event (" .  $eventDtls['event_code']  . ") successfully by " . $_SESSION['emp_nm'] . "\r\n";
        }
       
        $upEvent['event_status'] = '3';
        $upEvent['event_id'] = $arg['event_id'];
        $updateEventstaus = $this->UpdateEventStatus($upEvent);

        if (!empty($updateEventstaus)) {
            $activityDesc .= "event_status is changed from " . $eventDtls['event_status'] . " to 3 \r\n";
        }

        $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
        $this->query_insert('sp_user_activity', $insertActivityArr);
        unset($insertActivityArr);

		$planofcareid=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id = '".$arg['event_requirement_id']."' and event_id = '".$arg['event_id']."'");
		$plan_of_care_detail = mysql_fetch_array($planofcareid) or die(mysql_error());
        $professional_vender_id=$plan_of_care_detail['professional_vender_id'];
        mysql_query("update sp_event_plan_of_care set professional_vender_id='".$professional_vender_id."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
        mysql_query("update sp_event_requirements set professional_vender_id='".$professional_vender_id."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
        mysql_query("update sp_event_professional set professional_vender_id='".$professional_vender_id."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
        
        $event_requirement_id=$arg['event_requirement_id'];
		$Get_Service_id= mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'");
		$Service_id_row = mysql_fetch_array($Get_Service_id) or die(mysql_error());
		$service_id=$Service_id_row['service_id'];
		{
			if ($service_id == 3 || $service_id == 16)
			{
				mysql_query("update sp_event_plan_of_care set professional_vender_id='".$professional_vender_id."' where event_id='".$arg['event_id']."' ")or die(mysql_error());
			}
		}
		
		  $query=mysql_query("update sp_detailed_event_plan_of_care set professional_vender_id='".$professional_vender_id."' , status='1'  where event_id='".$arg['event_id']."'   and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
			     $updateAvailability = "update sp_professional_services set availability = '2' where service_professional_id = '".$diff[$i]."'";
                $this->query($updateAvailability);

        /* This is code is used for send push notification when we assign profesional mannually */ 
      
       $locationDtls = '';
        if (!empty($arg['event_id'])) {
            $eventDtls = $this->GetEvent($arg);
            // Get Patient Details
            $arg['patient_id'] = $eventDtls['patient_id'];
            $patientDtls = $this->GetPatientById($arg);
            //Patient Google location
            $patientGoogleLocation = !empty($patientDtls['google_location']) ? $patientDtls['google_location'] : '';
            $locationDtls = '';
            if (!empty($patientGoogleLocation) && strpos($patientGoogleLocation, ', Maharashtra,') !== false) {
                $stringContent  = explode(', Maharashtra,', $patientGoogleLocation);
                if (!empty($stringContent[0])) {
                    $locationDtls = $stringContent[0];
                }  
            }
        }
       
       
        $data= array();
        $data = array(
            'Type'            => '1',
            'Professional_id' => $professional_vender_id,
            'Title'           => '4',
            "Event_id"        => $arg['event_id'],
            "locationDtls"       =>$locationDtls
            
        );

        $data  = json_encode($data);
        
        $FCM_FILE_URL = "http://hospitalguru.in/push_notify.php";
        $out = send_curl_request($FCM_FILE_URL, $data, "post");

        /* Send push notification when we assign profesional mannually code ends here */ 

	}
    public function GetJobSummary($arg)
    {
        $event_id=$this->escape($arg['event_id']);
        $service_id=$this->escape($arg['service_id']);
        $event_professional_id=$this->escape($arg['event_professional_id']);
        if($service_id)
            $preWhere = " AND service_id='".$service_id."' ";
        if($event_professional_id)
            $preWhere .= " AND event_professional_id='".$event_professional_id."'";
        $GetSummarySql="SELECT job_summary_id,event_id,service_id,event_professional_id,reporting_instruction,type,report_status,status FROM sp_event_job_summary WHERE event_id='".$event_id."' ".$preWhere."  ";
        if(mysql_num_rows($this->query($GetSummarySql)))
        {
           $Summary=$this->fetch_array($this->query($GetSummarySql));
           return $Summary;          
        }
        else 
            return 0;
        
    }
    /**
     *
     * This function is used for add job summary details
     *
     * @param array $arg
     *
     * @return int 0 | 1
     *
     */
    public function InsertJobSummary($arg)
    {
        $isUpdateFlag = false;
        // check is it record present of this user 
        $selectExist = "SELECT job_summary_id,
            event_id,
            service_id,
            event_professional_id,
            professional_vender_id,
            reporting_instruction,
            type,
            report_status,
            modified_by,
            last_modified_date
        FROM sp_event_job_summary 
        WHERE event_id = '" . $arg['event_id'] . "' AND 
            service_id ='". $arg['service_id'] . "' AND 
            event_professional_id = '" . $arg['event_professional_id'] . "' AND type = '" . $arg['type'] . "'";

        if (mysql_num_rows($this->query($selectExist))) {
            $Result = $this->fetch_array($this->query($selectExist));

            $isUpdateFlag = true;

            $updateData = array();
            $updateData['event_id']               = $this->escape($arg['event_id']);
            $updateData['service_id']             = $this->escape($arg['service_id']);
            $updateData['event_professional_id']  = $this->escape($arg['event_professional_id']);
            $updateData['professional_vender_id'] = $this->escape($arg['professional_vender_id']);
            $updateData['reporting_instruction']  = $this->escape($arg['reporting_instruction']);
            $updateData['type']                   = $this->escape($arg['type']);
            $updateData['report_status']          = $this->escape($arg['report_status']);
            $updateData['modified_by']            = $this->escape($arg['modified_by']);
            $updateData['last_modified_date']     = $this->escape($arg['last_modified_date']);

            $where = "job_summary_id ='" . $Result['job_summary_id'] . "' ";
            $this->query_update('sp_event_job_summary', $updateData, $where);
            $RecordId = $Result['job_summary_id'];

        } else {
            $insertData = array();
            $insertData['event_id']               = $this->escape($arg['event_id']);
            $insertData['service_id']             = $this->escape($arg['service_id']);
            $insertData['event_professional_id']  = $this->escape($arg['event_professional_id']);
            $insertData['professional_vender_id'] = $this->escape($arg['professional_vender_id']);
            $insertData['reporting_instruction']  = $this->escape($arg['reporting_instruction']);
            $insertData['type']                   = $this->escape($arg['type']);
            $insertData['report_status']          = $this->escape($arg['report_status']);
            $insertData['status']                = $this->escape($arg['status']);
            $insertData['added_by']               = $this->escape($arg['added_by']);
            $insertData['added_date']             = $this->escape($arg['added_date']);
            $insertData['modified_by']            = $this->escape($arg['modified_by']);
            $insertData['last_modified_date']     = $this->escape($arg['last_modified_date']);
            $RecordId = $this->query_insert('sp_event_job_summary',$insertData);
        }
        
        if (!empty($RecordId)) {
            // Get Event details
            $eventDetails = $this->GetEvent($arg);
            $activityDesc = "Event job summary details updated successfully for " . $eventDetails['event_code'] . " by " . $_SESSION['emp_nm'] . "\r\n";
            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '1';
            $insertActivityArr['module_id']     = '';
            $insertActivityArr['module_name']   =  ($isUpdateFlag ? 'Update job summary' : 'Add job summary');
            $insertActivityArr['purpose_id']    = $eventDetails['purpose_id'];
            $insertActivityArr['event_id']      = $eventDetails['event_id'];
            $insertActivityArr['added_by_type'] = '1'; // 1 For Employee
            $insertActivityArr['added_by_id']   = $_SESSION['employee_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            if ($isUpdateFlag) {
                unset($Result['job_summary_id']);
                $recordDiff = array_diff_assoc($Result, $updateData);
                if (!empty($recordDiff)) {
                    foreach ($Result AS $key => $valResult) {
                        $activityDesc .= $key . " is changed from " . $valResult . " to " . $updateData[$key] . "\r\n";
                    }
                }
                $insertActivityArr['activity_description'] = $activityDesc;
            } else {
                $insertActivityArr['activity_description'] = "Event job summary details added successfully for " . $eventDetails['event_code'] . " by " . $_SESSION['emp_nm'];
            }
            $this->query_insert('sp_user_activity', $insertActivityArr);

            unset($insertActivityArr['activity_description'], $activityDesc);

            // Check is it job Closure available for this event
            $chk_job_closure = "SELECT job_closure_id FROM sp_job_closure WHERE event_id = '" . $arg['event_id'] . "'";
            if (mysql_num_rows($this->query($chk_job_closure)) == 0) {
                // Update Event Completion Status
                $UpdateEventStatusSql="UPDATE sp_events SET event_status = '3' WHERE event_id = '" . $arg['event_id'] . "'";
                $updateEvent = $this->query($UpdateEventStatusSql);
                if (!empty($updateEvent)) {
                    //Add event update activity log
                    $activityDesc = "As we found job closure record for " . $eventDetails['event_code'] .
                        " event_status is changed from " . $eventDetails['event_status'] . " to 3 by " . $_SESSION['emp_nm'] . ".";
                    $insertActivityArr['activity_description'] = $activityDesc;
                    $this->query_insert('sp_user_activity', $insertActivityArr);
                    unset($insertActivityArr);
                }
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    public function GetJobClosure($arg)
    { 
        $event_id=$this->escape($arg['event_id']);
        $service_id=$this->escape($arg['service_id']);
        $service_date=$this->escape($arg['service_date']);
        $professional_vender_id=$this->escape($arg['professional_vender_id']);
        if($service_id)
            $preWhere = " AND service_id='".$service_id."' ";
        if($professional_vender_id)
            $preWhere .= " AND professional_vender_id='".$professional_vender_id."'";
        if(!empty($service_date) && $service_date !='00-00-0000')
            $preWhere .= " AND service_date='".date('Y-m-d',strtotime($service_date))."'";
        
        $GetJobClosureSql="SELECT job_closure_id,event_id,professional_vender_id,service_id,service_render,baseline,airway,breathing,circulation,temprature,bsl,pulse,spo2,rr,gcs_total,high_bp,low_bp,skin_perfusion,summary_note,job_closure_file,status FROM sp_job_closure WHERE event_id='".$event_id."' ".$preWhere."  ";
        if(mysql_num_rows($this->query($GetJobClosureSql)))
        {
           $JobClosure=$this->fetch_array($this->query($GetJobClosureSql));
           
           // Getting consumption details 
           $ConsumptionSql="SELECT consumption_id,job_closure_id,consumption_type,unit_id,unit_quantity FROM sp_job_closure_consumption_mapping WHERE job_closure_id='".$JobClosure['job_closure_id']."'";
           if(mysql_num_rows($this->query($ConsumptionSql)))
           {
               $Consumption=$this->fetch_all_array($ConsumptionSql);
               $recArr=array();
               foreach ($Consumption as $key => $valconsumption) 
               {
                  if($valconsumption['consumption_type']=='1' || $valconsumption['consumption_type']=='2') 
                  {
                      $GetConsumptionSql="SELECT name FROM sp_medicines WHERE medicine_id='".$valconsumption['unit_id']."'";
                      $ConsumptionNm=$this->fetch_array($this->query($GetConsumptionSql));
                      if(!empty($ConsumptionNm))
                      {
                         $valconsumption['name']=$ConsumptionNm['name'];
                      }
                      else 
                      {
                         $valconsumption['name']=""; 
                      } 
                  }
                  if($valconsumption['consumption_type']=='3' || $valconsumption['consumption_type']=='4') 
                  {
                      $GetConsumptionSql="SELECT name FROM sp_consumables WHERE consumable_id='".$valconsumption['unit_id']."'";
                      $ConsumptionNm=$this->fetch_array($this->query($GetConsumptionSql));
                      if(!empty($ConsumptionNm))
                      {
                         $valconsumption['name']=$ConsumptionNm['name'];
                      }
                      else 
                      {
                         $valconsumption['name']=""; 
                      }
                  }
                  $recArr[]=$valconsumption;
               }
               if(!empty($recArr))
                $JobClosure['consumption']=$recArr;
               else 
                  $JobClosure['consumption']=""; 
           }
           
           return $JobClosure;          
        }
        else 
            return 0;
    }
    public function InsertJobClosure($arg)
    {
        $job_closure_id = $this->escape($arg['job_closure_id']);

        // Get event Details
        $eventDtls = $this->GetEvent($arg);

        // Get job closure details
        $jobClosureDtls = $this->getJobClosureById($job_closure_id);

        $whereClause = " event_id = '" . $arg['event_id'] . "' AND
            professional_vender_id = '" . $arg['professional_vender_id'] . "' AND
            service_date = '" . $arg['service_date'] . "' ";

        // check is it record present of this user 
        if ($job_closure_id) {
            $whereClause .= " AND job_closure_id != '" . $job_closure_id . "'";
        }

        $selectExist = "SELECT job_closure_id
        FROM sp_job_closure 
        WHERE " . $whereClause . ""; 

        if ($this->num_of_rows($this->query($selectExist)) == 0) {
            $insertData = array();
            $insertData['event_id']               = $this->escape($arg['event_id']);
            $insertData['professional_vender_id'] = $arg['professional_vender_id'];
            $insertData['service_id']             = $this->escape($arg['service_id']);
            $insertData['service_date']           = $this->escape($arg['service_date']);
            $insertData['service_render']         = $this->escape($arg['service_render']);
            $insertData['temprature']             = $this->escape($arg['temprature']);
            $insertData['bsl']                    = $this->escape($arg['bsl']);
            $insertData['pulse']                  = $this->escape($arg['pulse']);
            $insertData['spo2']                   = $this->escape($arg['spo2']);
            $insertData['rr']                     = $this->escape($arg['rr']);
            $insertData['gcs_total']              = $this->escape($arg['gcs_total']);
            $insertData['high_bp']                = $this->escape($arg['high_bp']);
            $insertData['low_bp']                 = $this->escape($arg['low_bp']);
            $insertData['skin_perfusion']         = $this->escape($arg['skin_perfusion']);
            $insertData['airway']                 = $this->escape($arg['airway']);
            $insertData['breathing']              = $this->escape($arg['breathing']);
            $insertData['circulation']            = $this->escape($arg['circulation']);
            $insertData['baseline']               = $this->escape($arg['baseline']);
            $insertData['summary_note']           = $this->escape($arg['summary_note']);

            if (!empty($arg['job_closure_file'])) {
                if (!empty($job_closure_id)) {
                    // Getting File Name 
                    $GetFileSql = "SELECT job_closure_file FROM sp_job_closure WHERE job_closure_id = '" . $job_closure_id . "'";
                    $GetFile = $this->fetch_array($this->query($GetFileSql));

                    if (!empty($GetFile) && $GetFile['job_closure_file'] &&
                        file_exists("JobClosureDocuments/" . $GetFile['job_closure_file'])) {
                        // Unlink previous file
                        unlink("JobClosureDocuments/" . $GetFile['job_closure_file']);
                    }  
                }
                $insertData['job_closure_file'] = $this->escape($arg['job_closure_file']);
            }

            $insertData['modified_by']        = $this->escape($arg['modified_by']);
            $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);

            if (!empty($job_closure_id)) {
                $where = "job_closure_id ='" . $job_closure_id . "' ";
                $RecordId = $this->query_update('sp_job_closure', $insertData, $where);
            } else {
                $insertData['status']=$this->escape($arg['status']);
                $insertData['added_by']=$this->escape($arg['added_by']);
                $insertData['added_date']=$this->escape($arg['added_date']);
                $RecordId = $this->query_insert('sp_job_closure', $insertData);
            }

            if (!empty($RecordId)) {
                // Add activity history for job closure
                $param = array();
                $param['event_id']         = $arg['event_id'];
                $param['job_closure_id']   = $job_closure_id;
                $param['job_closure_dtls'] = $jobClosureDtls;
                $param['record_dtls']      = $insertData;
                $param['event_dtls']      = $eventDtls;
                $this->addJobClosureActivity($param);
                unset($param);

                // Update Event Completion Status
                $upEvent['event_status'] = '4';
                $upEvent['event_id'] = $arg['event_id'];
                $updateEventstaus = $this->UpdateEventStatus($upEvent);

                if (!empty($updateEventstaus)) {
                    $insertActivityArr = array();
                    $insertActivityArr['module_type']          = '1';
                    $insertActivityArr['module_id']            = '';
                    $insertActivityArr['module_name']          =  ($job_closure_id ? 'Edit Job Closure Details' : 'Add Job Closure Details');
                    $insertActivityArr['purpose_id']           = $eventDtls['purpose_id'];
                    $insertActivityArr['event_id']             = $eventDtls['event_id'];
                    $insertActivityArr['activity_description'] = "";
                    $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
                    $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
                    $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

                    $activityDesc = "Job closure details " . ($job_closure_id ? 'modified' : 'added') . " successfully for event " . $eventDtls['event_code']  . " by " . $_SESSION['emp_nm'] . "\r\n";
                    $activityDesc .= "event_status is changed from " . $eventDtls['event_status'] .  "to 4 \r\n";
                }
                $update_sesrviceClosed = "UPDATE sp_event_professional SET service_closed = 'Y' WHERE event_id = '" . $arg['event_id'] . "' ";
                $serviceClosedStatus = $this->query($update_sesrviceClosed);

                if (!empty($serviceClosedStatus)) {
                    $activityDesc .= "service_closed is changed from N to Y \r\n";
                }

                // Get detail event plan of care record details
                $getDtlsPlanOfCareSql = "SELECT Session_status 
                FROM sp_detailed_event_plan_of_care
                WHERE event_id = '" . $arg['event_id'] . "' AND
                    service_date = '" . $insertData['service_date'] . "'";

                if (mysql_num_rows($this->query($getDtlsPlanOfCareSql))) {
                    $getDtlsPlanOfCare = $this->fetch_array($this->query($getDtlsPlanOfCareSql));
                    //Update event detail plan of care status update
                    $updateDetailedEventPlanOfCare = "UPDATE sp_detailed_event_plan_of_care
                        SET Session_status = '6' 
                    WHERE event_id = '" . $arg['event_id'] . "' AND
                        service_date = '" . $insertData['service_date'] . "' ";

                    $recordUpdateStatus = $this->query($updateDetailedEventPlanOfCare);
                    if (!empty($recordUpdateStatus)) {
                        $activityDesc .= "Session_status is changed from " . $getDtlsPlanOfCare['Session_status'] . " to 6 \r\n";
                    }
                }

                $insertActivityArr['activity_description'] = $activityDesc;
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);

                // Delete Job Closure Entries from event list
                $eventIDForClosure = $arg['eventIDForClosure'];
                $DelRecordSql = "DELETE FROM sp_events WHERE event_id ='" . $eventIDForClosure . "' AND caller_id = '" . $arg['Edit_CallerId'] . "' AND purpose_id = '7' ";
                $this->query($DelRecordSql);

                if (!empty($job_closure_id)) {
                    return $job_closure_id;
                } else {
                    return $RecordId;
                }
            } else {
                return 0;
            }
        } else {
          return 0;
        }
    }
    public function InsertConsumptions($arg)
    {
        // check is it record present of this user 
        $selectExist="SELECT consumption_id FROM sp_job_closure_consumption_mapping WHERE job_closure_id='".$arg['job_closure_id']."' AND consumption_type ='".$arg['consumption_type']."' AND unit_id='".$arg['unit_id']."'";
        if(mysql_num_rows($this->query($selectExist)))
        {
            $Result=$this->fetch_array($this->query($selectExist));
            $where="consumption_id ='".$Result['consumption_id']."' ";
        }
        
        $insertData = array();
        $insertData['job_closure_id']=$this->escape($arg['job_closure_id']);
        $insertData['consumption_type']=$this->escape($arg['consumption_type']);
        $insertData['unit_id']=$this->escape($arg['unit_id']);
        $insertData['unit_quantity']=$this->escape($arg['unit_quantity']);
        $insertData['modified_by']=$this->escape($arg['modified_by']);
        $insertData['last_modified_date']=date('Y-m-d H:i:s');
        
        if(!empty($Result['consumption_id']))
        {
          $RecordId = $this->query_update('sp_job_closure_consumption_mapping',$insertData,$where);  
        }
        else 
        {
           $insertData['status']='1';
           $insertData['added_by']=$this->escape($arg['added_by']);
           $insertData['added_date']=date('Y-m-d H:i:s');
           $RecordId=$this->query_insert('sp_job_closure_consumption_mapping',$insertData);
        }
 
        if(!empty($RecordId))
            return 1;
        else
            return 0; 
    }
    public function GetFeedbackQuestions($arg)
    {
        $preWhere = '';
        
        if($arg['allques'] == 'No')
            $preWhere .= " and feedback_id !='1' ";
        $selectQuestion = "select feedback_id,question,option_type from sp_feedback_form where status = '1' ".$preWhere." ";                        
        if(mysql_num_rows($this->query($selectQuestion)))
        {
            $ptr_data = $this->fetch_all_array($selectQuestion);
            return $ptr_data;
        }
        else
            return 0;
    }
    public function GetFeedbackOptions($arg)
    {
        $select_options = "select feedback_option_id,option_value from sp_feedback_options where feedback_id = '".$arg['feedback_id']."' and status = '1' ";
        if(mysql_num_rows($this->query($select_options)))
        {
            $ptr_values = $this->fetch_all_array($select_options);
            return $ptr_values;
        }
        else
            return 0;
    }
    public function InsertFeedbackForm($arg)
    {
        $event_id = $arg['feedbackEventId'];

        // Get event details
        $param['event_id'] = $event_id;
        $eventDls = $this->GetEvent($param);
        unset($param);

        $service_date = $arg['service_date'];
        $argpass['allques'] = 'yes';
        $FeedBackQuestions= $this->GetFeedbackQuestions($argpass);
        foreach ($FeedBackQuestions as $valQuestions) {
            $feedback_id = $valQuestions['feedback_id'];
            
            $insertFeedback['event_id']    = $event_id;
            $insertFeedback['feedback_id'] = $feedback_id;
            
            
            if ($valQuestions['option_type'] =='4') {
               $insertFeedback['answer'] = $_REQUEST['rating_val_'.$feedback_id];
            }
            else {
                $insertFeedback['answer'] = $_REQUEST['answer_'.$feedback_id];
            }
            
            $insertFeedback['user_id'] = $_REQUEST['feedbackCallerId'];
            $insertFeedback['user_type'] = '3';

            if ($valQuestions['option_type'] != '3') {
                $insertFeedback['option_id']=$_REQUEST['option_value_'.$feedback_id];
                $insertFeedback['service_date']= $arg['service_date'];
                $selectExist = "select feedback_answer_id from sp_feedback_answers where event_id = '".$event_id."' and feedback_id = '".$feedback_id."' AND  service_date='".$service_date."'";
                if (mysql_num_rows($this->query($selectExist))) {
                    $valfeed = $this->fetch_array($this->query($selectExist));
                    $insertFeedback['modified_by'] = $arg['added_by'];
                    $insertFeedback['last_modified_date'] = date('Y-m-d H:i:s');
                    $where = "feedback_answer_id ='".$valfeed['feedback_answer_id']."' ";
                    $RecordId = $this->query_update('sp_feedback_answers',$insertFeedback,$where);  
                } else {                   
                   $insertFeedback['added_by']= $arg['added_by'];
                   $insertFeedback['added_date']=date('Y-m-d H:i:s');
                   $RecordId=$this->query_insert('sp_feedback_answers',$insertFeedback);
                }
            }
            else
            {
                $optionsArr = $_REQUEST['option_value_check_'.$feedback_id];
                for($i=0;$i<count($optionsArr);$i++)
                {
                    $selectedAnswer = $optionsArr[$i];
                    $insertFeedback['option_id']=$selectedAnswer;
                    $insertFeedback['service_date']= $arg['service_date'];
                    $selectExist = "SELECT feedback_answer_id FROM sp_feedback_answers WHERE option_id = '".$selectedAnswer."' AND event_id = '".$event_id."' AND feedback_id = '".$feedback_id."' AND service_date='".$service_date."' ";
                    if(mysql_num_rows($this->query($selectExist)))
                    {
                        $valfeed = $this->fetch_array($this->query($selectExist));
                        $insertFeedback['modified_by']= $arg['added_by'];
                        $insertFeedback['last_modified_date']=date('Y-m-d H:i:s');
                        $where = "feedback_answer_id ='".$valfeed['feedback_answer_id']."' ";
                        $RecordId = $this->query_update('sp_feedback_answers',$insertFeedback,$where);  
                    }
                    else 
                    {                   
                       $insertFeedback['added_by']= $arg['added_by'];
                       $insertFeedback['added_date']=date('Y-m-d H:i:s');
                       $RecordId=$this->query_insert('sp_feedback_answers',$insertFeedback);
                    }
                }
            }
        }

        // Add activity log
        $insertActivityArr = array();

        $activityDesc = "Feedback details added successfully for event (" .  $eventDls['event_code']  . ")  by " . $_SESSION['emp_nm'] . " \r\n";
        $insertActivityArr['module_type']   = '1';
        $insertActivityArr['module_id']     = '';
        $insertActivityArr['module_name']   = 'Add Event Feedback Details';
        $insertActivityArr['purpose_id']    = $eventDls['purpose_id'];
        $insertActivityArr['event_id']      = $eventDls['event_id'];
        $insertActivityArr['activity_description'] = $activityDesc;
        $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
        $insertActivityArr['added_by_id']   = $_SESSION['employee_id'];
        $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');
        $upEvent['event_status'] = '5';
        $upEvent['event_id'] = $event_id;
        $updateEventstaus = $this->UpdateEventStatus($upEvent);
        if (!empty($updateEventstaus)) {
            $activityDesc .= "event_status is changed from " . $eventDls['event_status'] . " to 5 \r\n";
        }
        $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
        $this->query_insert('sp_user_activity', $insertActivityArr);
        unset($insertActivityArr);

        $feedbackeveID = $arg['prevFeedbackEveId'];
        $deleteFeedbackEve = "delete from sp_events where event_id = '".$feedbackeveID."' ";
        $this->query($deleteFeedbackEve);
        return 1;
    }
    public function UpdateEventStatus($arg)
    {
        $update['event_status'] = $arg['event_status'];
        $event_id = $arg['event_id'];
        $where = "event_id ='".$event_id."' ";
        return $this->query_update('sp_events',$update,$where);
    }
    public function GetEventCallerDtls($arg)
    {
        $event_id=$this->escape($arg['event_id']); 
        $EventDtls=$this->GetEvent($arg);
        if(!empty($EventDtls))
        {
           $CallerDtlsSql="SELECT caller_id,purpose_id,professional_id,consultant_id,attended_by FROM sp_callers WHERE caller_id='".$EventDtls['caller_id']."' "; 
           $GetCaller=$this->fetch_array($this->query($CallerDtlsSql));
           if(!empty($GetCaller['professional_id']))
           {
               
                $CallerSql="SELECT t1.event_id,t1.purpose_event_id,t1.relation,t1.patient_id,t1.purpose_id,t2.professional_id,t2.consultant_id,t2.caller_id,t3.service_professional_id,t3.name AS caller_last_name,t3.first_name AS caller_first_name,t3.middle_name AS caller_middle_name,t3.email_id,t3.phone_no,t3.mobile_no FROM sp_events t1".
                   " INNER JOIN sp_callers t2 ON t2.caller_id=t1.caller_id".
                   " INNER JOIN sp_service_professionals t3 ON t3.service_professional_id=t2.professional_id".
                   " WHERE t1.event_id='".$event_id."' AND t3.service_professional_id='".$GetCaller['professional_id']."'";

               //$CallerSql="SELECT service_professional_id,name AS CallerNm,email_id,phone_no,mobile_no FROM sp_service_professionals WHERE service_professional_id='".$GetCaller['professional_id']."'";
           }
           else if(!empty($GetCaller['consultant_id']))
           {
               $CallerSql="SELECT t1.event_id,t1.purpose_event_id,t1.relation,t1.patient_id,t1.purpose_id,t2.professional_id,t2.consultant_id,t2.caller_id,t3.doctors_consultants_id,t3.name AS caller_last_name,t3.first_name AS caller_first_name,t3.middle_name AS caller_middle_name,t3.email_id,t3.phone_no,t3.mobile_no FROM sp_events t1".
                   " INNER JOIN sp_callers t2 ON t2.caller_id=t1.caller_id".
                   " INNER JOIN sp_doctors_consultants t3 ON t3.doctors_consultants_id=t2.consultant_id".
                   " WHERE t1.event_id='".$event_id."' AND t3.doctors_consultants_id='".$GetCaller['consultant_id']."'";
               
              // $CallerSql="SELECT doctors_consultants_id,name AS CallerNm,email_id,phone_no,mobile_no FROM sp_doctors_consultants WHERE doctors_consultants_id='".$GetCaller['consultant_id']."'"; 
           }
           else 
           {
               $CallerSql="SELECT t1.event_id,t1.relation,t1.patient_id,t1.purpose_id,t2.professional_id,t2.consultant_id,t2.caller_id,t2.name AS caller_last_name,t2.first_name AS caller_first_name,t2.middle_name AS caller_middle_name,t2.phone_no,t3.name AS AttendBy,t3.type,t1.note,t1.purpose_event_id FROM sp_events t1".
                   " INNER JOIN sp_callers t2 ON t2.caller_id=t1.caller_id".
                   " INNER JOIN sp_employees t3 ON t3.employee_id=t2.attended_by".
                   " WHERE t1.event_id='".$event_id."'";
           }
           if($this->num_of_rows($this->query($CallerSql)))
            {
                $Caller=$this->fetch_array($this->query($CallerSql));
                return $Caller;
            }
            else
                return 0;
        }
        else 
            return 0; 
    }
    
    public function Chk_Professional_Exists($arg)
    {
       $event_id=$this->escape($arg['event_id']); 
       $professional_id=$this->escape($arg['professional_id']); 
       $Edit_CallerId=$this->escape($arg['Edit_CallerId']);
       $eventIDForClosure=$this->escape($arg['eventIDForClosure']);
       $chk_professionalSql="SELECT event_professional_id FROM sp_event_professional WHERE event_id='".$event_id."' AND professional_vender_id='".$professional_id."'";
       if($this->num_of_rows($this->query($chk_professionalSql)))
       {
           return 1;
       }
       else 
       {
           // Delete Temp Event
           $DelRecordSql="DELETE FROM sp_events WHERE event_id ='".$eventIDForClosure."' AND caller_id='".$Edit_CallerId."' AND purpose_id='7'";
           $this->query($DelRecordSql);
           return 0;
       }
    }
    
    public function delete_consumption_option($arg)
    {
        $consumption_id=$this->escape($arg['consumption_id']);
        
        if(!empty($consumption_id))
        {
            $DelConsumptionOptionSql="DELETE FROM sp_job_closure_consumption_mapping WHERE consumption_id='".$consumption_id."'";
            $DelConsumptionOption=$this->query($DelConsumptionOptionSql);
            if($DelConsumptionOption)
                return 1;
            else 
                return 0;
        }
        else 
            return 0;
    }
	
	/**
	* This function is useful for get event requirement details
	*/
	public function getEventRequirementDtls($eventId, $serviceId='')
	{
		$whereClause = "";
		if (!empty($eventId)) {
			if (!empty($serviceId)) {
				$whereClause = " AND service_id= '" . $serviceId . "'";
			}
		   $eventReqDtlsSql = "SELECT event_requirement_id, event_id, service_id, sub_service_id, hospital_id FROM sp_event_requirements WHERE event_id='" . $eventId . "' AND status ='1' " . $whereClause . ""; 
           $eventReqDtls = $this->fetch_all_array($eventReqDtlsSql);
		   return $eventReqDtls;
		} else {
			return 0;
		}
	}
	
	/**
	* This function is useful for get event plan of care details
	*/
	public function getEventPlanOfCareDtls($eventId, $eventReqId)
	{
		if (!empty($eventId) && !empty($eventReqId)) {
		   $eventPlanOfCareDtlsSql = "SELECT plan_of_care_id, event_id, event_requirement_id, service_date, service_date_to, start_date, end_date FROM sp_event_plan_of_care WHERE event_id='" . $eventId . "' AND event_requirement_id='" . $eventReqId . "' AND status ='1'"; 
           $eventPlanOfCareDtls = $this->fetch_array($this->query($eventPlanOfCareDtlsSql));
		   return $eventPlanOfCareDtls;
		} else {
			return 0;
		}
	}
	
	/**
	* Get professional for boarcast event
	*/
	public function getProfForBoarscate($eventId, $serviceId)
	{
		$recProf['event_id'] = $eventId;
		// Get event details
		$eventDtls = $this->GetEvent($recProf);

		if (!empty($eventDtls)) {
			// Get Patient Details
			$arg['patient_id'] = $eventDtls['patient_id'];
			$patientDtls = $this->GetPatientById($arg);
			unset($arg);

			//Patient lattitude
			$patientLattitude = !empty($patientDtls['lattitude']) ? $patientDtls['lattitude'] : '';
			//Patient langitude
			$patientLangitude = !empty($patientDtls['langitude']) ? $patientDtls['langitude'] : '';
			//Patient Google location
            $patientGoogleLocation = !empty($patientDtls['google_location']) ? $patientDtls['google_location'] : '';

			//Get event requirement details
			$eventReqDtls = $this->getEventRequirementDtls($eventId, $serviceId);
			
			// check is it active push notification present
			$pushNoticationStatus = $this->checkActivePushNotication($eventId);
			
			if (!empty($patientLattitude) && !empty($patientLangitude) && !empty($eventReqDtls)) {
				$subServiceId = 0;
				$join = "";
				$recordResult = array(); 
				foreach ($eventReqDtls AS $key => $valEventReq) {
					
					// check is it this event assigned to any professional 
					$chkEventAssignedProfSql = "SELECT plan_of_care_id FROM sp_event_plan_of_care WHERE event_requirement_id = '" . $valEventReq['event_requirement_id'] . "' AND (professional_vender_id IS NOT NULL AND professional_vender_id != 0)";
				
					if ($this->num_of_rows($this->query($chkEventAssignedProfSql)) > 0) {
						return "alreadyAssigned";
					}
	
        			if (!empty($pushNoticationStatus)) {
        				return "activeNotiExists_" . $pushNoticationStatus;
        			} else {
        			    // Delete all expired push notification associated with this event requirement
						$this->removeExpiredPushNotication($eventId);
        			}

					$subServiceId = $valEventReq['sub_service_id'];
					$join = "INNER JOIN sp_professional_sub_services AS psser ON psr.service_id = psser.service_id AND psser.sub_service_id = '" . $subServiceId . "' ";
					
					$getProfessionalSql = "SELECT sp.service_professional_id FROM sp_service_professionals AS sp 
						LEFT JOIN sp_professional_services AS psr 
							ON sp.service_professional_id = psr.service_professional_id
							" . $join . "
						WHERE psr.service_id = '" .$serviceId. "' AND 
							psr.status = '1' AND
							sp.status = '1'
						GROUP BY sp.service_professional_id";
	
					$professionalList = $this->fetch_all_array($getProfessionalSql);
					
					if (!empty($professionalList)) {
						foreach ($professionalList AS $key => $valProfessional) {
							//check is it availability of this professional
							//get plan of care details
							$eventPlanOfCareDtls = $this->getEventPlanOfCareDtls($eventId, $valEventReq['event_requirement_id']);
						
							if (!empty($eventPlanOfCareDtls)) {
								$eventPlanOfCareId = $eventPlanOfCareDtls['plan_of_care_id'];
								//check is it service more than one day
								$serviceStartDate = $eventPlanOfCareDtls['service_date'];
								$serviceEndDate = $eventPlanOfCareDtls['service_date_to'];
								
								// check is it time slot available
								$serviceStartTime = date("H:i:s", strtotime($eventPlanOfCareDtls['start_date']));
							    $serviceEndTime = date("H:i:s", strtotime($eventPlanOfCareDtls['end_date']));
								
								// Check is professional assigned to any other event on that day
								$serviceStDate = $serviceStartDate . " " . $serviceStartTime;
								$serviceEtDate = $serviceEndDate . " " . $serviceEndTime;
								
								$chkProfEventExistsSql = "SELECT COUNT(*) AS totalRecord FROM sp_detailed_event_plan_of_care WHERE 
									((start_date <='" . $serviceStDate . "' AND end_date >='" . $serviceStDate . "'  AND end_date >='" . $serviceEtDate. "' AND start_date <='" . $serviceEtDate . "') 
									OR (Actual_Service_date BETWEEN '" . $serviceStDate . "' AND '" . $serviceEtDate . "' OR end_date BETWEEN '" . $serviceStDate . "' AND '" . $serviceEtDate . "'))
									AND professional_vender_id = '" . $valProfessional['service_professional_id'] . "' AND status = '1'
									";
									
								$ProfEventExist = $this->fetch_array($this->query($chkProfEventExistsSql));
								
								if ($ProfEventExist['totalRecord'] == 0) {	
									//service number of days 
									$dateDiff = $this->dateDiff($serviceStartDate, $serviceEndDate);

									$serviceStartDateDay = 0;
									$serviceEndDateDay = 0;
									$whereClause = "";
									if (!empty($dateDiff)) {
										
										if ($dateDiff >= 7) {
											$whereClause = " AND day IN ('1','2','3','4','5','6','7')";
										} else {
											//service start date day
											$serviceStartDateDay = $this->getDayFromDate($serviceStartDate);
											$serviceEndDateDay = $this->getDayFromDate($serviceEndDate);
											
											$serviceStartDateDay +=1;
											$serviceEndDateDay +=1;
											
											// get range of day 
											
											if ($serviceEndDateDay >= $serviceStartDateDay) {
												$whereClause = " AND (day >='" . $serviceStartDateDay . "' AND  day <='" . $serviceEndDateDay . "') ";
											} else {
												$dayArr = array();
												
												// Taking start date values
												for ($i = $serviceStartDateDay;  $i <= 7 ; $i++) {
													$dayArr[] = "'" . $i . "'" ;
												}
												
												for ($j = 1;  $j <= $serviceEndDateDay ; $j++) {
													$dayArr[] = "'" . $j . "'" ;
												}
											  
												$dayCondition = implode(",", $dayArr);
												$whereClause = " AND day IN (" . $dayCondition . ")";
											}
										}
									} else {
										$serviceStartDateDay = $this->getDayFromDate($serviceStartDate);
										// we are consideing 1 as sunday for mobile app
										$serviceStartDateDay += 1;
										$whereClause = " AND day='" . $serviceStartDateDay . "' ";
									}
									
									$checkProfAvailabilitySql = "SELECT professional_avaibility_id FROM sp_professional_avaibility t1
										WHERE professional_service_id ='" . $valProfessional['service_professional_id'] . "' " . $whereClause . " "; 
										
									if ($this->num_of_rows($this->query($checkProfAvailabilitySql))) {
										$profAvailabilityList = $this->fetch_all_array($checkProfAvailabilitySql);
										
										if (!empty($profAvailabilityList)) {
											//check is it availability records of this professional
											
											$timeSlotWhereClause = "";
			
										$timeSlotWhereClause = " AND (start_time <= '" . $serviceStartTime. "' AND end_time >= '" . $serviceStartTime . "') AND (end_time >= '" . $serviceEndTime. "' AND start_time <= '" . $serviceEndTime . "') ";
											
											foreach ($profAvailabilityList AS $key => $valProfAvailability) {
												$checkProfAvailRecordSql = "SELECT professional_availability_detail, professional_availability_id, start_time, end_time, professional_location_id FROM sp_professional_availability_detail
													WHERE professional_availability_id ='" . $valProfAvailability['professional_avaibility_id'] . "'  " . $timeSlotWhereClause . " ";
													
												if ($this->num_of_rows($this->query($checkProfAvailRecordSql))) {
													$profAvailRecordList = $this->fetch_array($this->query($checkProfAvailRecordSql));
													if (!empty($profAvailRecordList)) {
														
														//create resultset
														$resultArg = array();
														$resultArg['Type'] = 1;
														$resultArg['Professional_id'] = $valProfessional['service_professional_id'];
														$resultArg['Title'] = 1;
														$resultArg['Event_id'] =  $eventId; //$eventPlanOfCareId
														$resultArg['service_id'] = $serviceId;
														$resultArg['sub_service_id'] = $valEventReq['sub_service_id'];
														$resultArg['patient_id'] = $eventDtls['patient_id'];
														$resultArg['service_start_date'] = $serviceStartDate;
														$resultArg['service_end_date'] = $serviceEndDate;
														$resultArg['service_start_time'] = $serviceStartTime;
														$resultArg['service_end_time'] = $serviceEndTime;

														// Check location availability of this professional with event Record
														//Get Location details
														$getlocationSql = "SELECT Professional_location_id, 
															professional_service_id, Name FROM sp_professional_location WHERE Professional_location_id='" . $profAvailRecordList['professional_location_id'] . "' ";

														if ($this->num_of_rows($this->query($getlocationSql))) {
															$profLocationList = $this->fetch_array($this->query($getlocationSql));
															if (!empty($profLocationList)) {
																// Get Location area details
																$getlocationAreaSql = "SELECT professional_location_details_id,
																	MAX(lattitude) AS max_lattitude,
																	MIN(lattitude) AS min_lattitude,
																	MAX(longitude) AS max_longitude,
																	MIN(longitude) AS min_longitude
																FROM sp_professional_location_details 
																WHERE professional_location_id='" . $profLocationList['Professional_location_id'] . "' ";
																
																if ($this->num_of_rows($this->query($getlocationAreaSql))) {
																	$profLocationAreaList = $this->fetch_array($this->query($getlocationAreaSql));
																	
														
																	$min_lattitude = $profLocationAreaList['min_lattitude'];
																	$max_lattitude = $profLocationAreaList['max_lattitude'];
																	$min_longitude = $profLocationAreaList['min_longitude'];
																	$max_longitude = $profLocationAreaList['max_longitude'];
																		if (($patientLattitude >= $min_lattitude &&  $patientLattitude <= $max_lattitude)
																		&& ($patientLangitude >= $min_longitude &&  $patientLangitude <= $max_longitude))
																		{
																		$recordResult[] = $resultArg;
																	}
																
																}
																
															}
														}
													}
												} else {
													// Professional time slot availability is not available
													//return 0;
												}
											}
										}
									} else {
										// Professional availability is not available
										//continue;
										//return 0;
									}	
								} else {
									continue;
								}	
							}
						}
					}
				}
				
				// check is it resultSet contain any data
				if (!empty($recordResult)) {
					// send push notification
					$recordResult =  array_values(array_map("unserialize", array_unique(array_map("serialize", $recordResult))));
				
				$profIds = array();
				$subServiceIds  = array();
				foreach ($recordResult AS $key => $valRecord) 
				{
					// get all professional ids 
					$profIds[] = $valRecord['Professional_id'];
					// get all sub service ids
					$subServiceIds[] = $valRecord['sub_service_id'];
				}
				
				$profArr = $this->array_combine_(array_values($profIds), array_values($subServiceIds));
				
				$uniqueProfArr = array();
				$resultantArr = array();
				if (!empty($profArr)) {
					foreach ($profArr AS $key => $valProf) {
						if (is_array($valProf)) {
							$uniqueProfArr[$key] = $valProf;
						}
					}
					
					if (!empty($uniqueProfArr)) {
					   
						$resultantArr['Professional_id'] = array_keys($uniqueProfArr);
						
				        $subserviceIds=array_values(array_map("unserialize", array_unique(array_map("serialize", $uniqueProfArr))));
						$resultantArr['sub_service_id'] = $subserviceIds[0];
					} else {
						$resultantArr['Professional_id']= array_unique($profIds);
						$resultantArr['sub_service_id']=  array_values(array_unique($subServiceIds));
					}
				}
				
            	if (!empty($profIds) && !empty($subServiceIds)) {
            	    $resultantArr['Type'] = $recordResult[0]['Type'];
            	    $resultantArr['Event_id'] = $recordResult[0]['Event_id'];
            	    $resultantArr['Title'] = $recordResult[0]['Title'];
                }
                

                // check is it professional really using app
                $appUserSql = "SELECT status FROM sp_session WHERE service_professional_id = '" . $resultantArr['Professional_id'] . "'";
                // professional don't have mobile app yet.
                $mobileAppFlag = 0;
                $activeAppUser = 0;

                if (mysql_num_rows($this->query($appUserSql))) {
                    $appUser = $this->fetch_all_array($appUserSql);
                    if (!empty($appUser)) {
                        $mobileAppFlag  = 1;
                        foreach ($appUser AS $key => $valUser) {
                            if ($valUser['status'] == '1') {
                                $activeAppUser = 1;
                                break;
                            }
                        }
                    }
                }

                // echo '<pre>$resultantArr ------ <br/>';
                // print_r($resultantArr);
                // echo '</pre>';
                // exit;


                //if ($activeAppUser) {
                    $resultantArr['locationDtls'] = '';
                    if (!empty($patientGoogleLocation) && strpos($patientGoogleLocation, ', Maharashtra,') !== false) {
                        $stringContent  = explode(', Maharashtra,', $patientGoogleLocation);
                        if (!empty($stringContent[0])) {
                            $resultantArr['locationDtls'] = $stringContent[0];
                        }  
                    }
                    $pushNotificationStatus = $this->sendPushNotification($resultantArr);
               // } 
                //else {
                    // send sms to user
                    // first check sms preference (Marathi / English)
                   // $pushNotificationStatus = $this->sendSMSNotification($resultantArr);
               // }
				
				$notification_details=mysql_query("SELECT * FROM sp_professional_notification  where  notification_detail_id ='$eventId' AND title='New Service' ");
				$num_row = mysql_num_rows($notification_details);
				
					if ($pushNotificationStatus) {
					    if ($num_row == 0) {
					        return "ErrorInNotificaton";
					    } else {
                            // Add activity log successfully broadcast event
                            $activityDesc = "Event " . $eventDtls['event_code'] . " broadcasted succefully to " . COUNT($resultantArr) . " professionals  by " . $_SESSION['emp_nm'];
                            $insertActivityArr = array();
                            $insertActivityArr['module_type']          = '1';
                            $insertActivityArr['module_id']            = '';
                            $insertActivityArr['module_name']          = 'Broadcast event details';
                            $insertActivityArr['purpose_id']           = $eventDtls['purpose_id'];
                            $insertActivityArr['event_id']             = $eventDtls['event_id'];
                            $insertActivityArr['activity_description'] = $activityDesc;
                            $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
                            $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
                            $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');
                            $this->query_insert('sp_user_activity', $insertActivityArr);
                            unset($insertActivityArr);
					        return "success";
					    }
					} else {
						//error in send push notifcation
						return "ErrorInNotificaton";
					}
				} else {
					// no result set found as per selected condition
					return "NoRecordFound";
				}
			} else {
				// Patient lattitude langitude not available
				// Event requirement details not available
				return (empty($eventReqDtls)) ? "NoEventReqFound" : "NoLatLongFound";
			}				
		} else {
			// Event Details not available
			return "NoEventDtlsFound";
		}
	}
	
	/**
	* calcuate date difference between 2 days
	*/
	function dateDiff($date1, $date2) 
	{
	  $date1_ts = strtotime($date1);
	  $date2_ts = strtotime($date2);
	  $diff = $date2_ts - $date1_ts;
	  return round($diff / 86400);
	}
	
	/**
	* This function is helpful to find the date of a day of the week from a date
	*/
	function getDayFromDate($eventDate)
	{
		return date('w', strtotime($eventDate));
	}
	
	/*
	*This function calculates the distance between two points (given the latitude/longitude of those points).
	*
	*lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)
	*lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)
	*unit = the unit you desire for results      
	*where: 'M' is statute miles (default)  
	*'K' is kilometers       
	*'N' is nautical miles
	*/
	function distance($lat1, $lon1, $lat2, $lon2, $unit) 
	{
	  ////$this->distance(32.9697, -96.80322, 29.46786, -98.53506, "K");
	  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
		return 0;
	  }
	  else {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
		  return ($miles * 1.609344);
		} else if ($unit == "N") {
		  return ($miles * 0.8684);
		} else {
		  return $miles;
		}
	  }
	}
	
	/*
	* This function is used for sending push notification
	*/
	function sendPushNotification($result)
	{
		// FCM push notification 
		if (!empty($result) && is_array($result)) {
			// total records
			$totalRecords = count($result);
			$i = 0;

			$data = json_encode($result);
		
			$FCM_FILE_URL = "http://hospitalguru.in/push_notify.php";

		    //$json = str_replace('"','', (string) $data);
	
			$out = send_curl_request($FCM_FILE_URL, $data, "post");
			$resultData = json_decode($out);               				
			if ($resultData->success == 1) {
				$i += 1;
			}
			
			if ($totalRecords >= $i) {
				return 1;
			} elseif($i==0) {
				return 0;
			}
		}
	}
	/**
	* This function is used for getting push notifaction records details 
	*/
	function getPushNotification($eventId, $serviceId)
	{
		if (!empty($eventId) && !empty($serviceId)) {
			// Get Event requirement details 
		   $eventRequirementDtls = $this->getEventRequirementDtls($eventId, $serviceId);
		   if (!empty($eventRequirementDtls)) {
			   $recordResult = array();
			   foreach ($eventRequirementDtls AS $key => $valEventReq) {
				   // Get plan of care details
				   $eventPlanOfCare = $this->getEventPlanOfCareDtls($eventId, $valEventReq['event_requirement_id']);
				   
				   if (!empty($eventPlanOfCare)) {
					   $notificationDtlsSql = "SELECT t1.notification_id,
								t1.professional_id,
								t1.type,
								t1.title,
								t1.notification_detail_id,
								t1.message,
							(CASE
									WHEN t1.Acknowledged = '0' THEN 'Pending'
									WHEN t1.Acknowledged = '1' THEN 'Accepted'
									WHEN t1.Acknowledged = '2' THEN 'Rejected'
									WHEN t1.Acknowledged = '3' THEN 'Expired'
								END) AS acknowledgedStatus,
								t2.professional_code,
								CONCAT(t2.first_name,' ', t2.name) AS professional_name 
								FROM sp_professional_notification AS t1 INNER JOIN sp_service_professionals AS t2
								ON t1.professional_id = t2.service_professional_id
								WHERE t1.notification_detail_id = '" . $eventId . "'
						    	AND t1.type ='service' AND t1.added_date >= UNIX_TIMESTAMP(NOW() - INTERVAL 3 HOUR) ORDER BY notification_id DESC ";
								
	
					   $notificationDtls = $this->fetch_all_array($notificationDtlsSql);
					   
					   
					   
					   if (!empty($notificationDtls)) {
						   $recordResult[] = $notificationDtls;
					   } 
				   }
			   }
			   if (!empty($recordResult)) {
				   
				   
				   return $recordResult;
			   } else {
			     return 0;
			   }
		   } else {
			   return 0;
		   }
		}
	}
	/**
	* This function is used for checking active push notifactions exists 
	*/
	public function checkActivePushNotication($eventId) {
		if (!empty($eventId)) {
			$checkActivePushNoticationSql = "SELECT notification_id, added_date FROM sp_professional_notification WHERE notification_detail_id IN ($eventId) AND title = 'New Service' ORDER BY notification_id DESC LIMIT 0,1";
			if ($this->num_of_rows($this->query($checkActivePushNoticationSql))) {
				$activePushNotication = $this->fetch_array($this->query($checkActivePushNoticationSql));
				$currentDate = date('Y-m-d H:i:s');
				$addedDate = $activePushNotication['added_date'];
				
				$diff = strtotime($currentDate) - strtotime($addedDate);
				
				list($years,$months,$days,$hours,$minutes,$seconds) = $this->getDateTimeDtls($diff);
				
				if ($days <=0 && $hours <= 3) {
					// calculate actual time diff from 3 hours 
					$actualTimeDiff =  (2 - $hours). " hrs " . (59 - $minutes) . " mins " . (59 - $seconds) . " secs";
					return $actualTimeDiff;
				} else {
					return 0;
				}
			}
		} else {
			return 0;
		}
	}
	
	function array_combine_($keys, $values)
	{
		$result = array();
		foreach ($keys as $i => $k) {
			$result[$k][] = $values[$i];
		}
		array_walk($result, create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;'));
		return $result;
	}
	
	/**
	* This function is used to delete expired push notifaction based on event requirement id
	*/
	public function removeExpiredPushNotication($eventId)
	{
		if (!empty($eventId)) {
			$checkExpiredPushNoticationSql = "SELECT notification_id, added_date FROM sp_professional_notification WHERE notification_detail_id IN ($eventId) AND title = 'New Service' AND added_date > DATE_SUB(NOW(), INTERVAL 3 HOUR) AND added_date <= NOW()";
			if ($this->num_of_rows($this->query($checkExpiredPushNoticationSql))) {
				$expirePushNotiList = $this->fetch_all_array($checkExpiredPushNoticationSql);
				
				if (!empty($expirePushNotiList)) {
					foreach ($expirePushNotiList AS $key => $valExpirePushNoti) {
						$deleteExpirePushNoti = "DELETE FROM sp_professional_notification WHERE notification_id = '" . $valExpirePushNoti['notification_id']."'";
						$this->query($deleteExpirePushNoti);
					}
				}
			}
		}
	}
	
	/**
	* This function will gives you years hours details based on time difference
	*/
	public function getDateTimeDtls($diff) {
		if ($diff) {
			// To get the year divide the resultant date into 
			// total seconds in a year (365*60*60*24) 
			$years = floor($diff / (365*60*60*24));  
			  
			  
			// To get the month, subtract it with years and 
			// divide the resultant date into 
			// total seconds in a month (30*60*60*24) 
			$months = floor(($diff - $years * 365*60*60*24) 
			   / (30*60*60*24));  
                      
                      
			// To get the day, subtract it with years and  
			// months and divide the resultant date into 
			// total seconds in a days (60*60*24) 
			$days = floor(($diff - $years * 365*60*60*24 -  
			 $months*30*60*60*24)/ (60*60*24)); 
			  
                      
			// To get the hour, subtract it with years,  
			// months & seconds and divide the resultant 
			// date into total seconds in a hours (60*60) 
			$hours = floor(($diff - $years * 365*60*60*24  
			   - $months*30*60*60*24 - $days*60*60*24) 
			   / (60*60));  
			  
			  
			// To get the minutes, subtract it with years, 
			// months, seconds and hours and divide the  
			// resultant date into total seconds i.e. 60 
			$minutes = floor(($diff - $years * 365*60*60*24  
				- $months*30*60*60*24 - $days*60*60*24  
				- $hours*60*60)/ 60);  
			  
			  
			// To get the minutes, subtract it with years, 
			// months, seconds, hours and minutes  
			$seconds = floor(($diff - $years * 365*60*60*24  
				- $months*30*60*60*24 - $days*60*60*24 
				- $hours*60*60 - $minutes*60)); 
           
		   return array($years,$months,$days,$hours,$minutes,$seconds);
		}
    }
    
    /*
	* This function is used for sending sms notification
	*/
	public function sendSMSNotification($arg)
	{
        if (!empty($arg)) {
            $serviesForMarathiSMSContent = array ('4', '5', '13', '20', '22', '23', '24');
            // first check is it professional id is array

            if (!empty($arg['Professional_id'])) {
                foreach ($arg['Professional_id'] AS $key => $professionalId) {
                    // Get professional details
                            $professionalDtlSql = "SELECT service_professional_id,
                            professional_code,
                            reference_type,
                            title,
                            name,
                            first_name,
                            middle_name,
                            email_id,
                            mobile_no
                        FROM sp_service_professionals 
                        WHERE service_professional_id = '" . $professionalId . "' AND 
                            status = 1";

                    if ($this->num_of_rows($this->query($professionalDtlSql))) {
                        $professionalDtl = $this->fetch_array($this->query($professionalDtlSql));

                        // check is it assigned service to professional
                        $chkAssignedServicesSql = "SELECT ps.service_id,
                                s.service_title
                            FROM sp_professional_services AS ps
                            INNER JOIN sp_services s
                                ON ps.service_id = s.service_id 
                            WHERE ps.service_professional_id = '" . $professionalId . "' AND 
                            ps.status = 1";

                        if ($this->num_of_rows($this->query($chkAssignedServicesSql))) {
                            $serviceDtl = $this->fetch_array($this->query($chkAssignedServicesSql));

                            $msgContentInMarathi = "N";

                            if (!empty($serviceDtl) && in_array($serviceDtl['service_id'], $serviesForMarathiSMSContent)) {
                                $msgContentInMarathi = "Y";
                            }

                            // Create formatted date

                            $getEventDtlSql = "SELECT epc.service_date,
                                epc.service_date_to,
                                epc.start_date,
                                epc.end_date
                                FROM sp_event_plan_of_care epc
                                INNER JOIN sp_event_requirements er
                                ON epc.event_id = er.event_id
                                WHERE epc.event_id = '".$arg['Event_id']."' AND 
                                er.sub_service_id = '". $arg['sub_service_id'][$key] . "' ";

                            if ($this->num_of_rows($this->query($getEventDtlSql))) {
                                $eventDtl = $this->fetch_array($this->query($getEventDtlSql));

                                $serviceStartDate = $eventDtl['service_date'];
                                $serviceEndDate   = $eventDtl['service_date_to'];
                                $serviceStartTime = $eventDtl['start_date'];
                                $serviceEndTime   = $eventDtl['end_date'];
                            }


                            $serviceDate = '';
                            if ($serviceStartDate == $serviceEndDate) {
                                $serviceDate = "on ". $serviceStartDate;
                            } else {
                                $serviceDate = "from " . $serviceStartDate . " to " . $serviceEndDate;
                            }

                            // Calculate date difference
                            $dateDiff = $this->dateDiff($serviceStartDate, $serviceEndDate);

                            $serviceTime = '';

                            if ($dateDiff) {
                                $serviceTime = " daily at " . $serviceStartTime . " to " . $serviceEndTime;
                            } else {
                                $serviceTime = " at " . $serviceStartTime . " to " . $serviceEndTime;
                            }

                            // send sms 
                            
                            $txtMsg = '';

                            $txtMsg .= "New service requested for " . $serviceDtl['service_title'] . " Service  " . $serviceDate . ", " . $serviceTime . ". ";	
                            
                            $txtMsg .= ",For queries please contact 7620400100 ";
                            $txtMsg .= "Regards,";
                            $txtMsg .= " Spero Healthcare Innovations Pvt Ltd.";
                            $args = array(
                                'msg' => $txtMsg,
                                'mob_no' => $professionalDtl['mobile_no']
                                );
                            $sms_data =$commonClass->sms_send($args);
                            /*
                            $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                            $data_to_post = array();
                            $data_to_post['uname'] = 'SperocHL';
                            $data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
                            $data_to_post['send'] = 'speroc';
                            $data_to_post['dest'] = $professionalDtl['mobile_no']; 
                            $data_to_post['msg'] = $txtMsg;

                            $curl = curl_init();
                            curl_setopt($curl,CURLOPT_URL, $form_url);
                            curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
                            curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
                            $result = curl_exec($curl);
                            curl_close($curl);*/
                        }
                    }
                }
                return 1;
            }
        } else {
            return 0;
        }
    }

    /**
     * This function is used for check is it event payment details exists
     *
     *  @param int $eventId
     *
     * @return array $paymentDtl
     */
    public function checkEventPaymentExists($eventId)
    {
        $paymentDtl = array();
        $checkEventPaymentSql = "SELECT payment_id,
                            event_id,
                            hospital_id,
                            amount,
                            Transaction_Type
                        FROM sp_payments
                        WHERE event_id = '". $eventId . "' ";

        //echo '<pre>checkEventPaymentExists ----<br/>';
        //print_r($checkEventPaymentSql);
        //echo '</pre>';
        //exit;

        if ($this->num_of_rows($this->query($checkEventPaymentSql))) {
            $paymentDtl = $this->fetch_all_array($checkEventPaymentSql);
        }
        return $paymentDtl;
    }

    /**
     * This function is used for get event payment details
     * 
     * @param int eventId
     *
     * @param int eventRequirementId
     * 
     * @param int paymentId
     *
     * @param int getAmoumtSum 1/0
     * @return  $result
     *
     */
    public function getEventPaymentDetails($eventId = NULL, $eventRequirementId = NULL, $paymentId = NULL, $getAmoumtSum = NULL) 
    {
        $result = array(); 
        $whereClause = '1=1';
        $columnNames = 'paymentdetail_id,
            event_id,
            event_requrement_id,
            payment_id,
            amount,
            hospital_id,
            date,
            status';
        $groupBy = '';

        if (!empty($getAmoumtSum)) {
            $columnNames = 'SUM(amount) AS totalAmount';
        }

        if (!empty($eventId)) {
            $whereClause .= " AND event_id = '" . $eventId . "' ";
            $groupBy = ' GROUP BY event_id';
        }

        if (!empty($eventRequirementId)) {
            $whereClause .= " AND event_requrement_id = '" . $eventRequirementId . "' ";
            $groupBy = ' GROUP BY event_requrement_id';
        }

        if (!empty($paymentId)) {
            $whereClause .= " AND payment_id = '" . $paymentId . "' ";
            $groupBy = ' GROUP BY payment_id';
        }

        $sql = "SELECT " . $columnNames . "
            FROM sp_payment_details
            WHERE " . $whereClause . " " . $groupBy . " ";

        //echo '<pre>$sql ------ <br>';
        //print_r($sql);
        //echo '</pre>';

        if ($this->num_of_rows($this->query($sql))) {

            //echo '<pre>$getAmoumtSum ------ <br>';
            //print_r($getAmoumtSum);
            //echo '</pre>';
            
            $result = (!empty($getAmoumtSum) ? $this->fetch_array($this->query($sql)) : $this->fetch_all_array($sql));
        }

        //echo '<pre>$result ------ <br>';
        //print_r($result);
        //echo '</pre>';

        return $result;
    }

    /**
     *
     * This function is used for add event payment
     *
     * @param array $arg
     *
     * @return int $recordId
     */
    public function addEventPayment($arg)
    {
        $insertData = array();
        $insertData['event_id']                      = $arg['eventId'];
        $insertData['cheque_DD__NEFT_no']            = $arg['chequeNumber'];
        $insertData['cheque_DD__NEFT_date']          = $arg['transDate'];
        $insertData['party_bank_name']               = $arg['partyBankName'];
        $insertData['professional_name']             = $arg['profName'];
        $insertData['Transaction_Type']              = $arg['transType'];
        $insertData['amount']                        = $arg['amount'];
        $insertData['type']                          = $arg['payType'];
        $insertData['added_by']                      = $arg['added_by'];
        $insertData['Add_through']                   = $arg['Add_through'];
        $insertData['Card_Number']                   = $arg['cardNumber'];
        $insertData['Transaction_ID']                = $arg['transId'];
        $insertData['date_time']                     = date('Y-m-d H:i:s');
        $insertData['Comments']                      = $arg['narration'];
        $insertData['payment_receipt_no_voucher_no'] = $arg['receiptNumber'];
        $insertData['hospital_id']                   = $arg['hospitalId'];
        $insertData['status']                        = $arg['status'];

        $recordId = $this->query_insert('sp_payments', $insertData);
        /*
        $txtMsg = '';
        $txtMsg .= "OTP for new service request for $service_title on $fromDatest to $fromDatet is : $otp. Kindly share OTP with your professional.";
        $args = array(
                        'msg' => $txtMsg,
                        'mob_no' => $mobile_no
                    );
        $sms_data =$commonClass->sms_send($args);
        */
        if (!empty($recordId)) {
            // Get Event details
            $param['event_id'] = $arg['eventId'];
            $eventDtls = $this->GetEvent($param);
            // Added activity log for payment detils
            $activityDesc = "Event payment details added successfully for " . $eventDtls['event_code'] .
                " by " . $_SESSION['emp_nm'] . "\r\n";
            
            $activityDesc .= "Payments details are as follows \r\n";
            if ($arg['payType'] != 'Cash') {
                $activityDesc .= "cheque_DD__NEFT_no : " . $arg['chequeNumber'] . "\r\n";
                $activityDesc .= "cheque_DD__NEFT_date : " . $arg['transDate'] . "\r\n";
                $activityDesc .= "party_bank_name : " . $arg['partyBankName'] . "\r\n";
                $activityDesc .= "Transaction_Type : " . $arg['transType'] . "\r\n";
                $activityDesc .= "amount : " . $arg['amount'] . "\r\n";
                $activityDesc .= "type : " . $arg['payType'] . "\r\n";
                $activityDesc .= "Add_through : " . $arg['Add_through'] . "\r\n";
                $activityDesc .= "Card_Number : " . $arg['cardNumber'] . "\r\n";
                $activityDesc .= "Transaction_ID : " . $arg['transId'] . "\r\n";
            }

            $activityDesc .= "Comments : " . $arg['narration'] . "\r\n";
            $activityDesc .= "professional_name : " . $arg['profName'] . "\r\n";
            $activityDesc .= "payment_receipt_no_voucher_no : " . $arg['receiptNumber'] . "\r\n";
            $activityDesc .= "hospital_id : " . $arg['hospitalId'] . "\r\n";
            $activityDesc .= "status : " . $arg['status'] . "\r\n";

            $insertActivityArr = array();
            $insertActivityArr['module_type'] = '1';
            $insertActivityArr['module_id']   = '';
            $insertActivityArr['module_name']   = 'Add event payment details';
            $insertActivityArr['purpose_id']   = $eventDtls['purpose_id'];
            $insertActivityArr['event_id']   = $eventDtls['event_id'];
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
            $insertActivityArr['added_by_id']   = $_SESSION['employee_id'];
            $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');
            $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);

            return $recordId;
        } else {
            return 0;
        }
    }

    /**
     *
     * This function is used for add event payment details
     *
     * @param array $arg
     *
     * @return $recordId
     *
     */
    public function addEventPaymentDetails($arg)
    {
        $insertData = array();
        $insertData['event_id']            = $arg['eventId'];
        $insertData['event_requrement_id'] = $arg['event_requirement_id'];
        $insertData['amount']              = $arg['amount'];
        $insertData['payment_id']          = $arg['payment_id'];
        $insertData['hospital_id']         = $arg['hospitalId'];
        $insertData['date']                = date('Y-m-d H:i:s');
        $insertData['status']              = '1';
        $recordId = $this->query_insert('sp_payment_details', $insertData);
        if (!empty($recordId)) {
            return $recordId;
        } else {
            return 0;
        }
    }

    /**
     *
     * This function is used for check event requirement details
     *
     * @param array $eventRequirementDtls
     *
     * @param array $postedData
     *
     * @return int $paymentDetailId
     *
     */
    public function checkForEventRequirement($eventRequirementDtls, $postedData)
    {
        if (!empty($eventRequirementDtls) && !empty($postedData) && $postedData['transType'] != 'Refund') {
            $eventReqPaymentArr = array();
            $paymentArr = array();
            $currentAmount = $postedData['amount'];
            $totalRemainingAmount = 0;
            foreach ($eventRequirementDtls AS $key => $valEventReq) {
                $eventRequirementId = $valEventReq['event_requirement_id'];
                $eventId = $valEventReq['event_id'];

                // Get total amount as per plan of care details
                $eventAmtByEventReq =  $this->getEventAmtByEventReqId($eventId, $eventRequirementId);
                $eventReqPaymentArr[$eventRequirementId] = (!empty($eventAmtByEventReq['totalAmountByReq']) ? $eventAmtByEventReq['totalAmountByReq'] : 0);
        
                $paymentAmountByEventRequirementId = $this->getEventPaymentDetails(NULL, $eventRequirementId, NULL, 1);
                $paymentArr[$eventRequirementId] = (!empty($paymentAmountByEventRequirementId['totalAmount']) ? $paymentAmountByEventRequirementId['totalAmount'] : 0);
            }

            // echo '<pre>eventReqPaymentArr <br>';
            // print_r($eventReqPaymentArr);
            // echo '</pre>';


            // echo '<pre>paymentArr <br>';
            // print_r($paymentArr);
            // echo '</pre>';
            
            if (!empty($eventReqPaymentArr) && !empty($paymentArr)) {
                foreach ($eventReqPaymentArr AS $ReqPaymentKey => $valReqPayment) {
                    foreach ($paymentArr AS $paymentArrKey => $valPayment) {
                        if (empty($currentAmount)) {
                            break;
                        }
                        //////////////////////////////////////////////////////////////////////

                        if ($ReqPaymentKey == $paymentArrKey) {

                            //echo '<pre>$ReqPaymentKey <br>';
                            //print_r($ReqPaymentKey);
                            //echo '</pre>';

                            //echo '<pre>$paymentArrKey <br>';
                            //print_r($paymentArrKey);
                            //echo '</pre>';

                            //echo '<pre>$paymentArrKey <br>';
                            //print_r($valPayment);
                            //echo '</pre>';

                            if (empty($valPayment)) {
                                if ($valReqPayment >= $currentAmount) {
                                    $totalDueAmount = $valReqPayment - $currentAmount;
                                    $amountValue = $currentAmount;
                                    $totalRemainningAmount = 0;
                                    $currentAmount = $totalRemainningAmount;
                                } else {
                                    $totalDueAmount = 0;
                                    $totalRemainningAmount = $currentAmount - $valReqPayment;
                                    $amountValue = $valReqPayment;
                                    $currentAmount = $totalRemainningAmount;
                                }

                                //echo '<pre>Hi if --$totalDueAmount <br>';
                                //print_r($totalDueAmount);
                                //echo '</pre>';

                                //echo '<pre>$totalRemainningAmount <br>';
                                //print_r($totalRemainningAmount);
                                //echo '</pre>';

                                //echo '<pre>$amountValue <br>';
                                //print_r($amountValue);
                                //echo '</pre>';
                            } else {
                                $totalDueAmount = $valReqPayment - $valPayment;

                                if ($currentAmount >= $totalDueAmount) {
                                    $totalRemainningAmount = $currentAmount - $totalDueAmount;
                                    $amountValue = $totalDueAmount;
                                    $totalDueAmount = 0;
                                    $currentAmount = $totalRemainningAmount;
                                } else {
                                    $totalDueAmount = $totalDueAmount - $currentAmount;
                                    $totalRemainningAmount = 0;
                                    $amountValue = $currentAmount;
                                    $currentAmount = $totalRemainningAmount;
                                }

                                // echo '<pre>Hi else --$totalDueAmount <br>';
                                // print_r($totalDueAmount);
                                // echo '</pre>';

                                // echo '<pre>$totalRemainningAmount <br>';
                                // print_r($totalRemainningAmount);
                                // echo '</pre>';

                                // echo '<pre>$amountValue <br>';
                                // print_r($amountValue);
                                // echo '</pre>';
                            }

                            // Now insert reord in payment details table
                            $postedData['event_requirement_id'] =  $paymentArrKey;
                            $postedData['amount'] = $amountValue;

                            // echo '<pre>$postedData <br>';
                            // print_r($postedData);
                            // echo '</pre>';

                            $paymentDetailId = $this->addEventPaymentDetails($postedData);

                             //echo '<pre>$paymentDetailId <br>';
                             //print_r($paymentDetailId);
                             //echo '</pre>';

                             //echo '<pre>$totalRemainningAmount <br>';
                             //print_r($totalRemainningAmount);
                             //echo '</pre>';

                            if (!empty($paymentDetailId) && empty($totalRemainningAmount)) {
                                return $paymentDetailId;
                            }
                        }

                        //////////////////////////////////////////////////////////////////////
                    }
                }
            } else {

            }
        }
    }

    /**
     *
     * This function is useful for get event amount based on event requirement
     *
     * @param int $eventId
     *
     * @param int $eventRequirementId
     *
     * @return array $result
     *
     */
    public function getEventAmtByEventReqId($eventId, $eventRequirementId)
    {
        $result = array();
        if (!empty($eventId) && !empty($eventRequirementId))
        {
            $sql = "SELECT SUM(service_cost) AS totalAmountByReq
                FROM sp_event_plan_of_care
                WHERE event_id = '" . $eventId . "' AND event_requirement_id = '" . $eventRequirementId . "'";

            if ($this->num_of_rows($this->query($sql))) {    
                $result = $this->fetch_array($this->query($sql));
            }  
        }
        return $result;
    }

    /**
     *
     * Update tally status in event table
     *
     * @param int $eventId
     *
     * @return int 1/0
     *
     */
    public function updateTallyStatus($eventId)
    {
        if (!empty($eventId)) {
            $update['Tally_Remark'] = 1;
            $where = "event_id = '" . $eventId . "' ";
            $recordId = $this->query_update('sp_events', $update, $where); 
            return $recordId;
        } else {
            return 0;
        }
    }

    /**
     *
     * Update payment received status
     *
     * @param int $eventId
     *
     * @return int 1/0
     *
     */
    public function updatePaymentReceivedStatus($eventId)
    {
        if (!empty($eventId)) {
            $update['payment_status'] = 2;
            $where = "event_id = '" . $eventId . "' ";
            $recordId = $this->query_update('sp_payments_received_by_professional', $update, $where); 
            return $recordId;
        } else {
            return 0;
        }
    }

    /**
     *
     * Get physiotherapy Event List
     *
     * @param array $arg
     * 
     * @return array $resultArray
     *
     */
    public function physiotherapyEventList($arg)
    {
        $preWhere = "";
        $filterWhere = "";
        $join = "";
        $searchValue = $this->escape($arg['search_value']);
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);
        $searchfromDate  = $this->escape($arg['searchfromDate']);
        $searchToDate    = $this->escape($arg['searchToDate']);

        if (!empty($searchValue) && $searchValue !='null')
        {
            $preWhere = " AND (e.event_code LIKE '%" . $searchValue . "%' OR p.hhc_code LIKE '%" . $searchValue . "%')"; 
        }
			
        if ((!empty($filterName) && $filterName != 'null') && (!empty($filterType) && $filterType != 'null'))
        {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . ""; 
        }

        if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(e.added_date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND DATE_FORMAT(e.added_date,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
            1 DAY)";
        }

        $groupBy = " GROUP BY er.event_requirement_id";

        $physiotherapyEventSql = "SELECT e.event_id,
                    GROUP_CONCAT(er.event_requirement_id) AS event_requirement_id,
                    e.event_code,
                    e.patient_id,
                    e.purpose_id,
                    e.added_date,
                    p.hhc_code,
                    CONCAT(p.first_name,' ', p.name) AS patient_name,
                    p.mobile_no,
                    p.email_id,
                    CONCAT(ssp.first_name,' ', ssp.name) AS professional_name,
                    ssp.professional_code,
                    ssp.email_id AS professional_email,
                    ssp.mobile_no AS professional_mobile,
                    s.service_title,
                    ss.recommomded_service
				FROM sp_events AS e
                INNER JOIN sp_event_requirements AS er
                    ON e.event_id = er.event_id
                INNER JOIN sp_event_professional AS ep
                    ON e.event_id = ep.event_id
                INNER JOIN sp_service_professionals AS ssp
                    ON ep.professional_vender_id = ssp.service_professional_id AND 
                    ep.professional_vender_id IS NOT NULL
                INNER JOIN sp_patients AS p
                    ON e.patient_id = p.patient_id
                INNER JOIN sp_services AS s
                    ON er.service_id = s.service_id
                INNER JOIN sp_sub_services AS ss
                    ON er.sub_service_id = ss.sub_service_id
				" . $join . " 
                WHERE ep.service_id = '16' " . $preWhere . "  " . $groupBy . " " . $filterWhere . "";

        // echo '<pre>';
        // print_r($physiotherapyEventSql);
        // echo '</pre>';

        $this->result = $this->query($physiotherapyEventSql);

        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($physiotherapyEventSql, $arg['pageSize'], $arg['pageIndex'], '');
            $allRecords = $pager->paginate();
            while($valRecords = $this->fetch_array($allRecords))
            {
                $this->resultPhysiotherapyEvent[] = $valRecords;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        
        if(count($this->resultPhysiotherapyEvent))
        {
            $resultArray['data'] = $this->resultPhysiotherapyEvent;
            return $resultArray;
        }
        else {
            return array(
                'data' => array(),
                'count' => 0
            );
        }
    }

    /**
     *
     * This function is used for get enquiry requirement
     *
     * @param $eventId
     *
     * @return $resultArr
     *
     */
    public function getEnquiryReqDtls($eventId)
    {
        $resultArr = array();

        if (!empty($eventId)) {
            $enquiryReqSql = "SELECT er.enquiry_requirement_id,
                    er.service_id,
                    s.service_title,
                    er.sub_service_id,
                    ss.recommomded_service
                FROM sp_enquiry_requirements AS er
                INNER JOIN sp_services AS s
                    ON er.service_id = s.service_id AND s.status = '1'
                INNER JOIN sp_sub_services AS ss
                    ON er.sub_service_id = ss.sub_service_id AND ss.status = '1'   
                 WHERE event_id = '" . $eventId . "' ";

            if (mysql_num_rows($this->query($enquiryReqSql))) {
                $resultArr = $this->fetch_all_array($enquiryReqSql);
            }

            return $resultArr;
        }
    }

    /**
     *
     * This function is used for get professional list based on enquiry requirement
     *
     * @param array $arg
     *
     * @return array $resultArr
     *
     */
    public function getProfessionals($arg)
    {
        if (!empty($arg)) {
            $eventId          = $arg['event_id'];
            $serviceIdsArr    = $arg['serviceIdsArr'];
            $subServiceIdsArr = $arg['subServiceIdsArr'];
            $enquiryDate      = $arg['service_date_of_Enquiry'];

            $preWhere = '';
            $join = '';

            if (!empty($enquiryDate)) {
                $serviceDay = date('l', strtotime($enquiryDate));

                $StatusArr = array(
                    'Sunday'    => '1',
                    'Monday'    => '2',
                    'Tuesday'   => '3',
                    'Wednesday' => '4',
                    'Thursday'  => '5',
                    'Friday'    => '6',
                    'Saturday'  => '7',
                );
                $serviceAvaliabityDay = $StatusArr[$serviceDay];
                //Check availability of professional
                if (!empty($serviceAvaliabityDay)) {
                    $join = " INNER JOIN sp_professional_avaibility AS pa ON pa.professional_service_id = sp.service_professional_id AND day = '" . $serviceAvaliabityDay . "' ";
                }
            }

            if (!empty($serviceIdsArr)) {
                $preWhere .= " AND psr.service_id IN (" . implode(',', $serviceIdsArr) . ") ";
            }

            if (!empty($serviceIdsArr)) {
                $preWhere .= " AND psser.sub_service_id IN (". implode(',', $subServiceIdsArr) .") "; 
            }

            $recProf['event_id'] = $eventId;
            $EventResponsed = $this->GetEvent($recProf);

            $patArg['patient_id'] = $EventResponsed['patient_id'];
            $patientHHCresponse   = $this->GetPatientById($patArg);

            $lat  = $patientHHCresponse['lattitude'];
            $long = $patientHHCresponse['langitude'];

            $professionalSql = "SELECT sp.service_professional_id 
                FROM sp_service_professionals AS sp 
                INNER JOIN sp_professional_services AS psr 
                    ON sp.service_professional_id = psr.service_professional_id
                INNER JOIN sp_professional_sub_services psser 
                    ON psr.service_id = psser.service_id
                " . $join . "
                WHERE psr.status = '1' AND 
                    sp.status = '1' AND 
                    sp.document_status = '1' 
                    " . $preWhere . "
                GROUP BY sp.service_professional_id LIMIT 0,5";

            $this->result = $this->query($professionalSql);

            if ($this->num_of_rows($this->result))
            {
                while ($val_records = $this->fetch_array($this->result))
                {
                    $select_details = "SELECT professional_code,
                        reference_type,
                        title,
                        name,
                        first_name,
                        middle_name,
                        CONCAT(first_name, ' ' , name) AS professional_name,
                        email_id,
                        phone_no,
                        mobile_no,
                        dob,
                        address,
                        work_email_id,
                        work_phone_no,
                        work_address,location_id,lattitude,langitude,google_home_location,google_work_location,set_location FROM sp_service_professionals WHERE service_professional_id = '".$val_records['service_professional_id']."' ";
                    $data_val = $this->fetch_array($this->query($select_details));
                    $val_records['professional_code'] = $data_val['professional_code'];
                    $val_records['reference_type'] = $data_val['reference_type'];
                    $val_records['name'] = $data_val['name'];
                    $val_records['first_name'] = $data_val['first_name'];
                    $val_records['middle_name'] = $data_val['middle_name'];
                    $val_records['email_id'] = $data_val['email_id'];
                    $val_records['professional_name'] = $data_val['professional_name'];
                    $val_records['phone_no'] = $data_val['phone_no'];
                    $val_records['mobile_no'] = $data_val['mobile_no'];
                    $val_records['address'] = $data_val['address'];
                    $val_records['distanceKM'] = '';

                    $select_details_spec = "select qualification,skill_set from sp_service_professional_details where service_professional_id = '".$val_records['service_professional_id']."' ";
                    $data_val_spec = $this->fetch_array($this->query($select_details_spec));
                    $val_records['qualification'] = $data_val_spec['qualification'];
                    $val_records['skill_set'] = $data_val_spec['skill_set'];
                    $locationNm = '';
                    $val_records['set_location'] = $data_val['set_location'];

                    if ($data_val['set_location'] == '1')
                    {
                        $locations = $data_val['location_id_home'];
                        $google_location = $data_val['google_home_location'];                    
                    }
                    else
                    {
                        $locations = $data_val['location_id'];
                        $google_location = $data_val['google_work_location'];
                    }

                    if ($google_location == '')
                    {
                        $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$locations."'";
                        $LocationDtls=$this->fetch_array($this->query($LocationSql));
                        if($LocationDtls['location'])
                            $locationNm=$LocationDtls['location']; 
                    }
                    else
                        $locationNm = $google_location;

                    $val_records['location'] = $locationNm;
                    $distanceKM = '';

                    $lat1 = $data_val['lattitude'];
                    $long2 = $data_val['langitude'];
                    $units = 'K';

                    // echo '<pre>$lat <br>';
                    // print_r($lat);
                    // echo '<br>langitude<br>';
                    // print_r($long);
                    // echo '<br>langitude1<br>';
                    // print_r($lat1);
                    // echo '<br>langitude2<br>';
                    // print_r($long2);
                    // echo '</pre>';
                    // exit;

                    if($lat && $long && $lat1 && $long2)
                        $distanceKM = distance($lat, $long, $lat1, $long2, $units);
                    else
                        $distanceKM = '';

                    $val_records['distanceKM'] = number_format((float)$distanceKM, 2, '.', '');

                    $this->resultProfessional[] = $val_records;
                }

                if (!empty($this->resultProfessional)) {
                    return $this->resultProfessional;
                } else {
                    return array();
                }
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    /**
     *
     * This function is used for convert inquiry into service
     *
     * @param int $eventId
     *
     * @return boolean $result
     *
     */
    public function convertEnquiryIntoService($eventId)
    {
        $result = false;

        if (!empty($eventId)) {
            // Get requirement service and sub services
            $enquiryReqDtls = $this->getEnquiryReqDtls($eventId);

            if (!empty($enquiryReqDtls)) {
                $serviceIdsArr    = array();
                $subServiceIdsArr = array();
                foreach ($enquiryReqDtls AS $key => $valEnquiryReq) {
                    $serviceIdsArr[]    = $valEnquiryReq['service_id'];
                    $subServiceIdsArr[] = $valEnquiryReq['sub_service_id'];
                }

                // echo '<pre>$serviceIdsArr : <br>';
                // print_r($serviceIdsArr);
                // echo '<br>subServiceIdsArr : <br>';
                // print_r($subServiceIdsArr);
                // echo '</pre>';
                

                if (!empty($serviceIdsArr) && !empty($subServiceIdsArr)) {
                    $arr['requireservices'] = array_values(array_unique($serviceIdsArr));

                    //echo '<pre>requireservices : <br>';
                    //print_r($arr['requireservices']);
                


                    if (!empty($arr['requireservices'])) {
                        // Get sub services
                        foreach ($arr['requireservices'] AS $valService) {
                            $getSubServiceSql = "SELECT sub_service_id 
                                FROM sp_enquiry_requirements 
                                WHERE event_id = '" . $eventId . "' AND 
                                    service_id = '" . $valService . "' ";

                            //echo '<br>getSubServiceSql : <br>';
                            //print_r($getSubServiceSql);

                            if (mysql_num_rows($this->query($getSubServiceSql))) {
                                $subServiceArr = $this->fetch_all_array($getSubServiceSql);
                                if (!empty($subServiceArr)) {
                                    $subServiceValArr = array();
                                    foreach ($subServiceArr AS $subServiceVal) {
                                        $subServiceValArr[] = $subServiceVal['sub_service_id'];
                                    }
                                    $arr['sub_service_id_multiselect_'. $valService] = $subServiceValArr;
                                }
                            }
                        }
                    } 
                }
            }

            //Insert record in event requirement
            $arr['purpose_id']      = 1;
            $arr['event_id_temp']   = $eventId;
			$arr['hospital_name']   = '2';
            $arr['Consultant']      = '';
            $arr['notes']           = 'Enquiry converted into service';
            $arr['employee_id']     = $_SESSION['employee_id'];

            //echo '<br> $arr : <br>';
            //print_r( $arr);
            //echo '</pre>';
            //exit;
            
            $insertRecordStatus = $this->InsertRequirements($arr);

            if (!empty($insertRecordStatus)) {
                // Get event details
                $getEventDtlsSql = "SELECT purpose_id,
                    isConvertedService,
                    enquiry_status,
                    event_date,
                    added_date,
                    last_modified_by,
                    last_modified_date
                FROM sp_events
                WHERE event_id = '" . $eventId . "' ";

                if (mysql_num_rows($this->query($getEventDtlsSql))) {
                    $eventDtls = $this->fetch_array($this->query($getEventDtlsSql));
                }

                // update event details
                $updateData['purpose_id']         = 1;
                $updateData['isConvertedService'] = 2;
                $updateData['enquiry_status']     = 3;
                $updateData['event_date']     = date('Y-m-d H:i:s');
                $updateData['added_date']     = date('Y-m-d H:i:s');
                $updateData['last_modified_by']   = $_SESSION['employee_id'];
                $updateData['last_modified_date'] = date('Y-m-d H:i:s');
                $where = "event_id ='" . $eventId . "' ";
                $updateStatus = $this->query_update('sp_events', $updateData, $where);
                if (!empty($updateStatus) && !empty($eventDtls)) {

                    // Added activity while enquiry converted into service
                    $insertActivityArr = array();
                    $insertActivityArr['module_type'] = '1';
                    $insertActivityArr['module_id']   = '';
                    $insertActivityArr['module_name']   = 'Enquiry converted into service';
                    $insertActivityArr['purpose_id']   = 1;
                    $insertActivityArr['event_id']   = $eventId;

                    $result = array_diff_assoc($eventDtls, $updateData);

                    if (!empty($result)) {
                        $messageStr = "";
                        foreach ($result AS $key => $valResult) {
                            $messageStr .= $key . " is changed from " . $valResult . " to " . $updateData[$key] . "\r\n";
                        }
                    }

                    $insertActivityArr['activity_description'] = (!empty($messageStr) ? nl2br($messageStr) : "");
                    $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
                    $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
                    $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

                    $this->query_insert('sp_user_activity',$insertActivityArr);

                    // Check is it enquiry having follow up data
                    $followUpSql = "SELECT follow_up_id FROM sp_enquiry_follow_up WHERE event_id = '" . $eventId . "' AND is_read_status = 'N'";
                    if (mysql_num_rows($this->query($followUpSql))) {
                        $followUpDtls = $this->fetch_all_array($followUpSql);
                        foreach ($followUpDtls AS $valFollowUp) {
                            $followUpUpdateData['is_read_status'] = 'Y';
                            $followUpUpdateData['last_modified_by'] = $_SESSION['employee_id'];
                            $followUpUpdateData['last_modified_date'] = date('Y-m-d H:i:s');
                            $followUpWhere = "follow_up_id ='" . $valFollowUp['follow_up_id'] . "' ";
                            // Update enquiry follow up status
                            $followUpUpdateStatus = $this->query_update('sp_enquiry_follow_up', $followUpUpdateData, $followUpWhere);
                        }
                    }
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * Update enquiry cacellation details
     * 
     * @param array $arg
     * 
     * @return $result
     */
    public function updateEnquiryStatus($arg)
    {
        $result = false;
        if (!empty($arg)) {
            // Get event details
            $getEventDtlsSql = "SELECT purpose_id,
                enquiry_status,
                enquiry_cancel_from,
                enquiry_cancel_date,
                enquiry_cancellation_reason,
                last_modified_by,
                last_modified_date
            FROM sp_events
            WHERE event_id = '" . $arg['event_id'] . "' ";

            if (mysql_num_rows($this->query($getEventDtlsSql))) {
                $eventDtls = $this->fetch_array($this->query($getEventDtlsSql));
                $purposeId = $eventDtls['purpose_id'];
                unset($eventDtls['purpose_id']);
            }

            // update event details
            $updateData['enquiry_status']              = 4;
            $updateData['enquiry_cancel_from'] = $arg['enquiry_cancel_from'];
            $updateData['enquiry_cancel_date'] = date('Y-m-d H:i:s');
            $updateData['enquiry_cancellation_reason'] = $arg['cancellation_reason'];
            $updateData['last_modified_by']            = $_SESSION['employee_id'];
            $updateData['last_modified_date']          = date('Y-m-d H:i:s');
            $where = "event_id ='" . $arg['event_id'] . "' ";

            $updateStatus = $this->query_update('sp_events', $updateData, $where);
            if (!empty($updateStatus) && !empty($eventDtls)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name'] = 'Enquiry Cancel Details';
                $insertActivityArr['purpose_id']  = $purposeId;
                $insertActivityArr['event_id']    = $arg['event_id'] ;
                $result = array_diff_assoc($eventDtls, $updateData);
                if (!empty($result)) {
                    $messageStr = "";
                    foreach ($result AS $key => $valResult) {
                        $messageStr .= $key . " is changed from " . $valResult . " to " . $updateData[$key] . "\r\n";
                    }
                }
                $insertActivityArr['activity_description'] = (!empty($messageStr) ? nl2br($messageStr) : "");
                $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
                $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

                $this->query_insert('sp_user_activity',$insertActivityArr);

                unset($insertActivityArr);

                $result = true;
            }
        }
        return $result;
    }
    
    /**
     *
     * This function is used for add enquiry follow up details
     *
     * @param array $arg
     *
     * @return int $insertedRecordId
     *
     */
    public function addEnquiryFollowUp($arg)
    {
        if (!empty($arg)) {
            $insertData = array();
            // update event details
            $insertData['event_id']           = $arg['event_id'];
            $insertData['follow_up_date']     = $arg['follow_up_date'];
            $insertData['follow_up_time']     = $arg['follow_up_time'];
            $insertData['follow_up_desc']     = $arg['follow_up_desc'];
            $insertData['follow_up_status']   = '1';
            $insertData['is_read_status']     = 'N';
            $insertData['added_by']           = $_SESSION['employee_id'];
            $insertData['added_date']         = date('Y-m-d H:i:s');
            $insertData['last_modified_by']   = $_SESSION['employee_id'];
            $insertData['last_modified_date'] = date('Y-m-d H:i:s');
            $insertData['follow_up_next_date']     = $arg['follow_up_next_date'];
            

            /*echo '<pre>insertData .... <br>';
            print_r($insertData);
            echo '</pre>';
            exit;*/

            $insertedRecordId = $this->query_insert('sp_enquiry_follow_up', $insertData);
            if (!empty($insertedRecordId)) {

                $updateData['service_date_of_Enquiry'] = $arg['follow_up_next_date'];
                $updateData['enquiry_status'] = '2';
                $where = "event_id ='" . $arg['event_id'] . "' ";
                $updateResult = $this->query_update('sp_events', $updateData, $where);
                // Added activity while adding enquiry follow up
                $getEventDtlsSql = "SELECT event_code,
                    purpose_id
                FROM sp_events
                WHERE event_id = '" . $arg['event_id'] . "'";

                if (mysql_num_rows($this->query($getEventDtlsSql))) {
                    $eventDtls = $this->fetch_array($this->query($getEventDtlsSql));
                }

                // Added activity log while adding enquiry follow up details
                $insertActivityArr = array();
                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name'] = 'Add Enquiry Follow up Details';
                $insertActivityArr['purpose_id']  = $eventDtls['purpose_id'];
                $insertActivityArr['event_id']    = $arg['event_id'];
                $insertActivityArr['activity_description'] = "Enquiry follow up details added successfully. New follow up added for event (" .  $eventDtls['event_code']  . ") is get created by " . $_SESSION['emp_nm'] . " ";
                $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']     = $_SESSION['employee_id'];
                $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');

                $this->query_insert('sp_user_activity',$insertActivityArr);
                unset($insertActivityArr);

                return $insertedRecordId;
            } else {
                return 0;
            }
        } else {
            return 0;
        } 
    }

    /**
     *
     * This function is used for get enquiry follow up details by event id
     *
     * @param int $eventId
     *
     * @return array $resultantArr
     *
     */
    public function getEnquiryFollowUpDtls($eventId)
    {
        $resultantArr = array();
        if ($eventId) {
            $enquiryFollowUpSql = "SELECT efu.follow_up_id,
                    efu.event_id,
                    e.event_code,
                    efu.follow_up_date,
                    efu.follow_up_time,
                    efu.follow_up_desc,
                    efu.follow_up_status,
                    (CASE
                        WHEN efu.follow_up_status = '1' THEN 'Active'
                        WHEN efu.follow_up_status = '2' THEN 'Inactive'
                    END) AS followUpStatusVal,
                    efu.is_read_status,
                    (CASE
                        WHEN efu.is_read_status = 'Y' THEN 'Yes'
                        WHEN efu.is_read_status = 'N' THEN 'No'
                    END) AS isReadStatusVal,
                    efu.added_by,
                    CONCAT(ema.name, ' ',ema.first_name,' ',ema.middle_name) AS added_by_emp_name,
                    efu.added_date,
                    efu.last_modified_by,
                    CONCAT(emm.name, ' ',emm.first_name,' ',emm.middle_name) AS modified_by_emp_name,
                    efu.last_modified_date
                FROM sp_enquiry_follow_up AS efu
                INNER JOIN sp_events AS e
                    ON e.event_id = efu.event_id
                INNER JOIN sp_employees AS ema
                    ON ema.employee_id = efu.added_by
                INNER JOIN sp_employees AS emm
                    ON emm.employee_id = efu.last_modified_by   
                 WHERE efu.event_id = '" . $eventId . "' ";

            if (mysql_num_rows($this->query($enquiryFollowUpSql))) {
                $resultantArr = $this->fetch_all_array($enquiryFollowUpSql);
            }
        }
        return $resultantArr;
    }

    /**
     *
     * This function is used for get enquiry follow up list
     *
     * @param array $arg
     *
     * @return array $resultArray
     *
     */
    public function enquiryFollowUpList($arg)
    {
        $preWhere = "";
        $filterWhere = "";
        $search_value = $this->escape($arg['search_Value']);
        $filter_name  = $this->escape($arg['filter_name']);
        $filter_type  = $this->escape($arg['filter_type']);
        $isTrash      = $this->escape($arg['isTrash']);

        if (!empty($search_value) && $search_value !='null') {
           $preWhere = "AND (efu.follow_up_date LIKE '%" . $search_value . "%' OR efu.follow_up_time LIKE '%" . $searchValue . "%' OR efu.follow_up_desc LIKE '%" . $searchValue . "%')"; 
        }

        if ((!empty($filter_name) && $filter_name != 'null') && (!empty($filter_type) && $filter_type != 'null')) {
            $filterWhere .= "ORDER BY " . $filter_name . " " . $filter_type . ""; 
        }

        if (!empty($isTrash) && $isTrash != 'null') {
           $preWhere .= "AND efu.follow_up_status = '3'"; 
        } else {
          $preWhere .= "AND e.enquiry_status!='4' AND efu.is_read_status = 'N' AND efu.follow_up_status IN ('1','2') AND efu.follow_up_date = CURDATE() AND e.isConvertedService = '1'";   
        }

        $enquiryFollowUpSql = "SELECT efu.follow_up_id,
                efu.event_id,
                e.event_code,
                efu.follow_up_date,
                efu.follow_up_time,
                efu.follow_up_desc,
                efu.follow_up_status,
                p.hhc_code,
                CONCAT(p.name, ' ', p.first_name, ' ', p.middle_name) AS patient_name,
                p.mobile_no AS patient_phone_no,
                CONCAT(c.name, ' ', c.first_name, ' ', c.middle_name) AS caller_name,
                c.phone_no AS caller_phone_no,
                (CASE
                    WHEN efu.follow_up_status = '1' THEN 'Active'
                    WHEN efu.follow_up_status = '2' THEN 'Inactive'
                END) AS followUpStatusVal,
                efu.is_read_status,
                (CASE
                    WHEN efu.is_read_status = 'Y' THEN 'Yes'
                    WHEN efu.is_read_status = 'N' THEN 'No'
                END) AS isReadStatusVal,
                efu.added_by,
                CONCAT(ema.name, ' ',ema.first_name,' ',ema.middle_name) AS added_by_emp_name,
                efu.added_date,
                efu.last_modified_by,
                CONCAT(emm.name, ' ',emm.first_name,' ',emm.middle_name) AS modified_by_emp_name,
                efu.last_modified_date
            FROM sp_enquiry_follow_up AS efu
            INNER JOIN sp_events AS e
                ON e.event_id = efu.event_id
            LEFT JOIN sp_patients AS p
                ON e.patient_id = p.patient_id
            INNER JOIN sp_callers AS c
                ON e.caller_id = c.caller_id
            INNER JOIN sp_employees AS ema
                ON ema.employee_id = efu.added_by
            INNER JOIN sp_employees AS emm
                ON emm.employee_id = efu.last_modified_by
            WHERE 1 " . $preWhere . " " . $filterWhere . " ";


        //echo '<pre>';
        //print_r($enquiryFollowUpSql);
        //echo '</pre>';
        //exit;

        $this->result = $this->query($enquiryFollowUpSql);

        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($enquiryFollowUpSql, $arg['pageSize'], $arg['pageIndex'], '');
            $all_records = $pager->paginate();

            while($val_records = $this->fetch_array($all_records))
            {
                $this->resultEnquiryFollowUp[] = $val_records;
            }

            $resultArray['count'] = $pager->total_rows;
        }

        if (count($this->resultEnquiryFollowUp))
        {
            $resultArray['data'] = $this->resultEnquiryFollowUp;
            return $resultArray;
        } else {
            return array (
                'data' => array(),
                'count' => 0
            );
        }
    }

    /**
     *
     * This function is used for change enquiry notification status
     *
     * @param int $followupId
     *
     * @return int $result
     */
    public function changeEnquiryNotificationStatus($followupId)
    {
        if ($followupId) {
            $updateData = array();
            $updateData['is_read_status'] = 'Y';
            $updateData['last_modified_by'] = $_SESSION['employee_id'];
            $updateData['last_modified_date'] = date('Y-m-d H:i:s');
            $where = "follow_up_id ='" . $followupId . "' ";

            $updateResult = $this->query_update('sp_enquiry_follow_up', $updateData, $where);
            if (!empty($updateResult)) {
                return 1;
            } else {
                return 0; 
            }
        } else {
            return 0;
        }
    }

    /**
     * This function is used for get event job closure list
     */
    public function eventJobClosureList($arg)
    {
        $preWhere = "";
        $filterWhere = "";
        $join = "";
        $searchValue = $this->escape($arg['search_value']);
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);
        $searchfromDate  = $this->escape($arg['searchfromDate']);
        $searchToDate    = $this->escape($arg['searchToDate']);

        if (!empty($searchValue) && $searchValue !='null')
        {
            $preWhere = " AND (sp.first_name LIKE '%" . $searchValue . "%' OR sp.name LIKE '%" . $searchValue . "%'  OR sp.mobile_no LIKE '%" . $searchValue . "%')"; 
        }
        
        if ((!empty($filterName) && $filterName != 'null') && (!empty($filterType) && $filterType != 'null'))
        {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . ""; 
        }

        if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= "  AND sp.status = '1' AND (ep.service_id = '3' || ep.service_id = '16') AND (DATE_FORMAT(ep.added_date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND sp.status = '1' AND (ep.service_id = '3' || ep.service_id = '16') AND DATE_FORMAT(ep.added_date,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
        }

        $groupBy = 'GROUP BY sp.service_professional_id';

        //sp_professional_services
        //sp_service_professionals
        //service_id=3 
        //status=1

        //Total_active_calls
        //service_id='3' or service_id='16' //service_professional_id   // sp_event_professional

        // Done_count
        // service_closed = 'Y' service_id='3' or service_id='16' //service_professional_id   // sp_event_professional

        // remain_count

        // service_closed = 'N' service_id='3' or service_id='16' //service_professional_id   // sp_event_professional



        $jobClosureSql = "SELECT ep.event_professional_id,
                CONCAT(sp.title, '. ', sp.first_name, ' ', sp.middle_name, ' ', sp.name) AS professional_name,
                sp.email_id,
                sp.phone_no,
                sp.mobile_no,
                sp.work_email_id,
                sp.work_phone_no,
                ep.event_id,
                ep.event_requirement_id,
                ep.professional_vender_id,
                ep.plan_of_care_id,
                ep.service_id,
                ep.service_closed,
                COUNT(IF(ep.service_closed = 'Y', 1, NULL)) AS completeJobClosure,
				COUNT(IF(ep.service_closed = 'N', 1, NULL)) AS pendingJobClosure,
				COUNT(IF((ep.service_closed = 'Y' || ep.service_closed = 'N') , 1, NULL)) AS totalJobClosure
                FROM sp_event_professional AS ep
                LEFT JOIN sp_professional_services AS ps
                    ON ps.service_professional_id = ep.professional_vender_id
                LEFT JOIN sp_service_professionals AS sp
                    ON sp.service_professional_id = ps.service_professional_id 
                " . $join . " 
                WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "";

        //echo '<pre>';
        //print_r($jobClosureSql);
        //echo '</pre>';
        

        $this->result = $this->query($jobClosureSql);

        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($jobClosureSql, $arg['pageSize'], $arg['pageIndex'], '');
            $allRecords = $pager->paginate();
            while($valRecords = $this->fetch_array($allRecords))
            {
                $this->resultJobClosure[] = $valRecords;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        
        if(count($this->resultJobClosure))
        {
            $resultArray['data'] = $this->resultJobClosure;
            return $resultArray;
        }
        else {
            return array(
                'data' => array(),
                'count' => 0
            );
        }
    }

    /**
     *
     * This function is used for get plan of care details by Id
     *
     * @param int $planOfCareId
     *
     * @return array $planOfCareDtls
     *
     */
    public function getPlanOfCareById($planOfCareId)
    {
        $planOfCareDtls = array();
        if (!empty($planOfCareId)) {
            $getPlanOfCareSql = "SELECT plan_of_care_id,
                event_id,
                event_requirement_id,
                professional_vender_id,
                service_date,
                service_date_to,
                start_date,
                end_date,
                service_cost,
                status,
                added_by,
                added_date,
                last_modified_by,
                last_modified_date
            FROM sp_event_plan_of_care
            WHERE plan_of_care_id = '" . $planOfCareId . "'";

            if (mysql_num_rows($this->query($getPlanOfCareSql))) {
                $planOfCareDtls = $this->fetch__array($this->query($getPlanOfCareSql));
            }
        }
        return $planOfCareDtls;
    }

    /**
     *
     * This function is used for get job closure details by id
     *
     * @param int $jobClosureId
     *
     * @return array $jobClosureDtls
     *
     */
    public function getJobClosureById($jobClosureId) {
        $jobClosureDtls = array();
        if (!empty($jobClosureId)) {
            $getJobClosureSql = "SELECT job_closure_id,
                event_id,
                professional_vender_id,
                service_id,
                service_render,
                service_date,
                medicine_id, 
                consumable_id, 
                temprature, 
                bsl,
                pulse,
                spo2,
                rr,
                gcs_total,
                high_bp,
                low_bp,
                skin_perfusion,
                airway,
                breathing,
                circulation,
                baseline,
                summary_note,
                job_closure_file,
                status,
                added_by,
                added_date,
                modified_by,
                last_modified_date
            FROM sp_job_closure
            WHERE job_closure_id = '" . $jobClosureId . "'";

            if (mysql_num_rows($this->query($getJobClosureSql))) {
                $jobClosureDtls = $this->fetch__array($this->query($getJobClosureSql));
            }
        }
        return $jobClosureDtls;
    }

    /**
     *
     * This function is used for add job closure activity
     *
     * @param array $args
     *
     * @return int $insertedRecordId
     *
     */
    public function addJobClosureActivity($args) 
    {
        $insertedRecordId = 0;
        if (!empty($args)) {
            $jobClosureId   = $args['job_closure_id'];
            $jobClosureDtls = $args['job_closure_dtls'];
            $recordDtls     = $args['record_dtls'];
            $eventDtls     = $args['event_dtls'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']          = '1';
            $insertActivityArr['module_id']            = '';
            $insertActivityArr['module_name']          =  ($jobClosureId ? 'Edit Job Closure Details' : 'Add Job Closure Details');
            $insertActivityArr['purpose_id']           = $eventDtls['purpose_id'];
            $insertActivityArr['event_id']             = $eventDtls['event_id'];
            $insertActivityArr['activity_description'] = "";
            $insertActivityArr['added_by_type']        = '1'; // 1 For Employee
            $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
            $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

            $activityDesc = "Job closure details " . ($jobClosureId ? 'modified' : 'added') . " successfully for event " . $eventDtls['event_code']  . " by " . $_SESSION['emp_nm'] . "\r\n";

            if (!empty($jobClosureId) && !empty($jobClosureDtls)) {
                unset($jobClosureDtls['job_closure_id'], $jobClosureDtls['status'], $jobClosureDtls['added_by'], $jobClosureDtls['added_date']);
                $jobClosureDiff = array_diff_assoc($jobClosureDtls, $recordDtls);
                if (!empty($jobClosureDiff)) {
                    foreach ($jobClosureDtls AS $key => $valJobClosure) {
                        $activityDesc .= $key . " is changed from " . $valJobClosure . " to " . $recordDtls[$key] . "\r\n";
                    }
                }
            }
            $insertActivityArr['activity_description'] =  (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $insertedRecordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $insertedRecordId;
    }

    /**
     *
     * This function is used for add activity while share event to HCM
     *
     * @param array $args
     *
     * @return int $insertedRecordId
     *
     */
    public function addShareEventActivity($args) 
    {
        $insertedRecordId = 0;
        if (!empty($args)) {

            // Get Event Details
            $eventDtls = $this->GetEvent($args);

            // Get HCM DEtails
            $getEmpSql = "SELECT employee_id,
                employee_code,
                type,
                CONCAT(first_name,' ', name) AS employee_name
            FROM sp_employees
            WHERE employee_id = '" . $args['assigned_to'] . "'";

            if (mysql_num_rows($this->query($getEmpSql))) {
                $employeeDtls = $this->fetch_array($this->query($getEmpSql));
            }

            $insertActivityArr = array();
            $insertActivityArr['module_type']          = '1';
            $insertActivityArr['module_id']            = '';
            $insertActivityArr['module_name']          =  'Add Event Requirement Details';
            $insertActivityArr['purpose_id']           = $eventDtls['purpose_id'];
            $insertActivityArr['event_id']             = $eventDtls['event_id'];
            $insertActivityArr['activity_description'] = "";
            $insertActivityArr['added_by_type']        = '1';
            $insertActivityArr['added_by_id']          = $_SESSION['employee_id'];
            $insertActivityArr['added_by_date']        = date('Y-m-d H:i:s');

            $activityDesc = "Event " . $eventDtls['event_code']  . " is share with HCM  " . $employeeDtls['employee_name'] . " successfully by " . $_SESSION['emp_nm'] . "\r\n";
            $insertActivityArr['activity_description'] =  (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $insertedRecordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $insertedRecordId;
    }
}
//END
?>