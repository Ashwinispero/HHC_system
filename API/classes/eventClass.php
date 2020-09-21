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
        $insertData['name']=$arg['name'];
        $insertData['first_name']=$arg['first_name'];
        $insertData['middle_name']=$arg['middle_name'];
        $insertData['purpose_id']=$arg['purpose_id'];
        //$insertData['relation']=$arg['relation'];
        $insertData['phone_no']=$arg['phone_no'];
        $insertData['professional_id']=$arg['professional_id'];
        $insertData['consultant_id']=$arg['caller_consultant_id'];
        $employee_id = $arg['employee_id'];
       // $val = randomPass(3,'1234567890');
        /* ---------- Edit Event Log     --------- */
        $Edit_CallerId = $arg['Edit_CallerId'];
        $Edit_event_id = $arg['Edit_event_id'];
        /* ---------- Edit Event Log     --------- */
        if($Edit_CallerId)
        {
            $insertData['last_modified_by'] = $employee_id;
            $insertData['last_modified_date'] = date('Y-m-d H:i:s');
            $where = "caller_id ='".$Edit_CallerId."' ";
            $this->query_update('sp_callers',$insertData,$where);
            $RecordId = $Edit_CallerId;
            
            $updateEditedData['relation'] = $arg['relation'];
            $updateEditedData['last_modified_by'] = $employee_id;
            $updateEditedData['last_modified_date'] = date('Y-m-d H:i:s');
            $where = "event_id ='".$Edit_event_id."' ";
            $this->query_update('sp_events',$updateEditedData,$where);
            $EventId = $Edit_event_id;
        }
        else
        {
            if($arg['caller_consultant_id'])
            {
                 $preWhereC = " and consultant_id = '".$arg['caller_consultant_id']."'";
            }
            else if($arg['professional_id'])
            {
                 $preWhereC = " and professional_id = '".$arg['professional_id']."'";
            }
            else
                $preWhereC = " and phone_no = '".$arg['phone_no']."'";
            $select_exist = "SELECT caller_id FROM sp_callers WHERE 1 ".$preWhereC."  ";
                 if(mysql_num_rows($this->query($select_exist)))
                 {
                     $insertData['last_modified_by'] = $employee_id;
                     $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                     $val_existRecord = $this->fetch_array($this->query($select_exist));
                     $where = "caller_id ='".$val_existRecord['caller_id']."' ";
                     $this->query_update('sp_callers',$insertData,$where);
                     $RecordId = $val_existRecord['caller_id'];
                 }
                 else
                 {
                     $insertData['attended_by'] = $employee_id;
                     $insertData['added_date'] = date('Y-m-d H:i:s');
                     $insertData['status'] = '1';
                     $RecordId=$this->query_insert('sp_callers',$insertData);
                 }
            //---------- create event ---------------//
                $createEvent['caller_id'] = $RecordId;
                $createEvent['relation'] = $arg['relation'];
                
                // Generate Random Number 
                $GetMaxRecordIdSql="SELECT MAX(event_id) AS MaxId FROM sp_events";
                if($this->num_of_rows($this->query($GetMaxRecordIdSql)))
                {
                    $MaxRecord=$this->fetch_array($this->query($GetMaxRecordIdSql));
                    $getMaxRecordId=$MaxRecord['MaxId'];
                }
                else 
                {
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
				$GetMaxbillIdSql=mysql_query("SELECT MAX(bill_no_ref_no) as bill_no_ref_no FROM sp_events") or die(mysql_error());
				$row = mysql_fetch_array($GetMaxbillIdSql) or die(mysql_error());
				$Maxbillid=$row['bill_no_ref_no'];
				$branch_code='DMH';
                $createEvent['purpose_id'] = $arg['purpose_id'];
				$createEvent['bill_no_ref_no'] = $Maxbillid + 1;
                $createEvent['event_date'] = date('Y-m-d H:i:s');
                $createEvent['status'] = '2';
                $createEvent['event_status'] = '1';
                $createEvent['added_by'] = $employee_id;
				$createEvent['Invoice_narration'] = $Invoice_narration;
				
                $createEvent['added_date'] = date('Y-m-d H:i:s');
				$createEvent['branch_code'] = $branch_code;	
				
				$employee_record_query="SELECT hospital_id FROM sp_employees WHERE employee_id=$employee_id";
				$employeee_record = $this->fetch_array($this->query($employee_record_query));
				
				$createEvent['hospital_id'] = $employeee_record['hospital_id'];	
                $EventId=$this->query_insert('sp_events',$createEvent);
				
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
        $SearchByPurpose= $this->escape($arg['SearchByPurpose']);
      //  $SearchByEmployee= $this->escape($arg['SearchByEmployee']);
        
        $SearchByProfessional= $this->escape($arg['SearchByProfessional']);
        
        if($arg['SearchfromDate'])
            $SearchfromDate= date('Y-m-d',strtotime($arg['SearchfromDate'])); 
        if($arg['SearchToDate'])
            $SearchToDate= date('Y-m-d',strtotime($arg['SearchToDate'])); 
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isStatus=$this->escape($arg['isStatus']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere .="AND (se.event_code LIKE '%".$search_value."%' OR DATE_FORMAT(se.event_date,'%Y-%m-%d') LIKE '%".$search_value."%' OR sp.hhc_code LIKE '%".$search_value."%' OR CONCAT(sp.name, ' ',sp.first_name,' ',sp.middle_name) LIKE '%".$search_value."%')"; 
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
        
        
        $RecordSql="SELECT se.event_id,se.event_code,se.caller_id,se.purpose_event_id,se.patient_id,sp.name,sp.first_name,se.purpose_id,se.event_date,se.note,se.description,se.status,se.event_status,se.estimate_cost,se.added_by,se.added_date ,sp.hhc_code,se.isArchive
                    FROM sp_events as se LEFT JOIN sp_patients as sp ON se.patient_id = sp.patient_id  ".$join."
                    WHERE 1 and se.status !='3' ".$isStatusWhere." ".$preWhere." ".$daterange."  ".$filterWhere." ";
        
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
        
        $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
        $json = json_decode($json);

        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        $insertData['lattitude']=$lat;
        $insertData['langitude']=$long;
        /*          get lettitude/ langitude          */
        if($exist_hhc_code)
        {
            $select_exist = "SELECT patient_id FROM sp_patients WHERE hhc_code = '".$exist_hhc_code."' ";
            if(mysql_num_rows($this->query($select_exist)))
            {
                $existPatientId = $this->fetch_array($this->query($select_exist));
                $insertData['last_modified_by'] = $employee_id;
                $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                $where = "hhc_code ='".$exist_hhc_code."' ";
                $this->query_update('sp_patients',$insertData,$where);
                $RecordId = $existPatientId['patient_id'];
            }
        }
        else
        {
            $select_exist = "SELECT patient_id FROM sp_patients WHERE name = '".$arg['name']."' and first_name='".$arg['first_name']."'  and mobile_no = '".$arg['mobile_no']."'  ";
            if(mysql_num_rows($this->query($select_exist)) == 0)
            {
                
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
            }
            else
            {
                $existPatientIds = $this->fetch_array($this->query($select_exist));
                $insertData['last_modified_by'] = $employee_id;
                $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                $where = "patient_id ='".$existPatientIds['patient_id']."' ";
                $this->query_update('sp_patients',$insertData,$where);
                $RecordId = $existPatientIds['patient_id'];
            }
        }
        /* ------- Update event  -------- */
        $temp_event_id = $arg['temp_event_id'];
        $updateData['patient_id'] = $RecordId;
        $updateData['purpose_id'] = $purpose_id;
        $updateData['event_status'] = 2;
        $updateData['last_modified_by'] = $employee_id;
        $updateData['last_modified_date'] = date('Y-m-d H:i:s');
        $where = "event_id ='".$temp_event_id."' ";
        $this->query_update('sp_events',$updateData,$where);
        /* ------- Update event Complete -------- */
        
        /* ------- Create event doctors mapping -------- */
        $doctor_id = $arg['doctor_id'];
        $consultant_id = $arg['consultant_id'];
        if($doctor_id || $consultant_id)
        {
            if($doctor_id)
            {
                $insertDoctors['doctor_consultant_id'] = $doctor_id;
                $insertDoctors['type'] = 1;
                
                $insertDoctors['event_id'] = $temp_event_id;
                $insertDoctors['patient_id'] = $RecordId;                
                $select_exist_doctors = "SELECT event_doctor_id FROM sp_event_doctor_mapping WHERE event_id = '".$temp_event_id."' and patient_id = '".$RecordId."' and type = '".$insertDoctors['type']."' ";
                if(mysql_num_rows($this->query($select_exist_doctors)))
                {
                    $insertDoctors['last_modified_by'] = $employee_id;
                    $insertDoctors['last_modified_date'] = date('Y-m-d H:i:s');
                    $this->query_update('sp_event_doctor_mapping',$insertDoctors);
                }
                else
                {
                    $insertDoctors['added_by'] = $employee_id;
                    $insertDoctors['added_date'] = date('Y-m-d H:i:s');
                    $this->query_insert('sp_event_doctor_mapping',$insertDoctors);
                }
            }
            if($consultant_id)
            {
                $insertDoctors['doctor_consultant_id'] = $consultant_id;
                $insertDoctors['type'] = 2;
                
                $insertDoctors['event_id'] = $temp_event_id;
                $insertDoctors['patient_id'] = $RecordId;                
                $select_exist_doctors = "SELECT event_doctor_id FROM sp_event_doctor_mapping WHERE event_id = '".$temp_event_id."' and patient_id = '".$RecordId."' and type = '".$insertDoctors['type']."' ";
                if(mysql_num_rows($this->query($select_exist_doctors)))
                {
                    $insertDoctors['last_modified_by'] = $employee_id;
                    $insertDoctors['last_modified_date'] = date('Y-m-d H:i:s');
                    $this->query_update('sp_event_doctor_mapping',$insertDoctors);
                }
                else
                {
                    $insertDoctors['added_by'] = $employee_id;
                    $insertDoctors['added_date'] = date('Y-m-d H:i:s');
                    $this->query_insert('sp_event_doctor_mapping',$insertDoctors);
                }
            }            
            
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
    public function InsertRequirements($arg)
    { 
        $arraySubSer=array();
        $employee_id = $arg['employee_id'];        
        $purpose_id=$arg['purpose_id'];
		$hospital_name = $arg['hospital_name'];        
        $Consultant=$arg['Consultant'];
        //print_r($arg['requireservices']);
        
       /* $subservices = $arg['sub_service_id'];
        $countService = count($arg['sub_service_id']);
        $select_existRequirment = "select event_requirement_id,sub_service_id,service_id from sp_event_requirements where event_id = '".$arg['event_id_temp']."' ";
        $val_existRequirement = $this->fetch_all_array($select_existRequirment);
        foreach($val_existRequirement as $key=>$AllRequ)
        {
            $arraySubSer[] = $AllRequ['sub_service_id'];
            $arrayService[] = $AllRequ['service_id'];
        }
        // ------------------ delete/check existing services  -------- //
        $new_array = $subservices;
        $existArray = $arraySubSer;
        //print_r($new_array);
        //print_r($existArray);
        //if($new_array && $existArray)
        {
            $intersect = array_intersect($new_array,$existArray);
            if(!empty($intersect))
                $comma_separated = implode(",", $intersect);

            if(!empty($comma_separated))
                $preDelete = ' AND sub_service_id NOT IN ('.$comma_separated.')';

            $deleteUnwanted = "DELETE FROM sp_event_requirements WHERE event_id = '".$arg['event_id_temp']."' $preDelete ";
           // $predelete = $this->query($deleteUnwanted);

            $diff = array_merge(array_diff($new_array, $intersect), array_diff($intersect, $new_array));
        }*/
        //else
            //$diff = $new_array;
        //print_r($diff);
        /* -------- complete delete/check existing services --------- */

        //print_r($_REQUEST['requireservices']);
        
        // delete existing services
        $select_existRequirment = "select distinct service_id from sp_event_requirements where event_id = '".$arg['event_id_temp']."' ";
        $val_existRequirement = $this->fetch_all_array($select_existRequirment);
        foreach($val_existRequirement as $key=>$AllRequ)
        {
            $arrayService[] = $AllRequ['service_id'];
        }
        $intersect = array();
        $exiSerArray = $arrayService;
        $NewSerArray = $_REQUEST['requireservices'];
        if($exiSerArray)
            $intersect = array_intersect($NewSerArray,$exiSerArray);
        if(!empty($intersect))
        {
            $comma_separated = implode(",", $intersect);
            $predelete = " and service_id NOT IN (".$comma_separated.")";
        }
        $deleteUnwanted = "DELETE FROM sp_event_requirements WHERE event_id = '".$arg['event_id_temp']."' $predelete ";
        $predelete = $this->query($deleteUnwanted);
		
		//Ashwini code
		$delete_plan_of_care = "DELETE FROM sp_event_plan_of_care WHERE event_id = '".$arg['event_id_temp']."'  ";
		$predelete11 = $this->query($delete_plan_of_care);

		$delete_event_professional = "DELETE FROM sp_event_professional WHERE event_id = '".$arg['event_id_temp']."'  ";
		$predelete111 = $this->query($delete_event_professional);
			
		
        $Servicediff = array_merge(array_diff($NewSerArray, $intersect), array_diff($intersect, $NewSerArray));
        // delete existing services complete
        //print_r($NewSerArray);
        //print_r($intersect);
        
        if(!empty($Servicediff))
            $Servicediff = $Servicediff;
        else
            $Servicediff = $NewSerArray;
        //print_r($Servicediff);
        for($ct=0;$ct<count($Servicediff);$ct++)
        {
            $sel_serviceID = $Servicediff[$ct];
            $SeltotalSubServices = $_REQUEST['sub_service_id_multiselect_'.$sel_serviceID];
            //print_r($SeltotalSubServices);
            // delete existing subservices
            $select_existsub = "select sub_service_id from sp_event_requirements where event_id = '".$arg['event_id_temp']."' and service_id = '".$sel_serviceID."' ";
            $val_existReqSubSer = $this->fetch_all_array($select_existsub);
            foreach($val_existReqSubSer as $key=>$AllRequSubServ)
            {
                $arraySubService[] = $AllRequSubServ['sub_service_id'];
            }
            $intersectSub = array();
            $exiSubSerArray = $arraySubService;
            $NewSubSerArray = $SeltotalSubServices;
            if($exiSubSerArray)
                $intersectSub = array_intersect($NewSubSerArray,$exiSubSerArray);
            if(!empty($intersectSub))
            {
                $comma_separated_sub = implode(",", $intersectSub);
                $predeleteSub = " and sub_service_id NOT IN (".$comma_separated_sub.")";
            }
            $deleteUnwantedSub = "DELETE FROM sp_event_requirements WHERE event_id = '".$arg['event_id_temp']."' and service_id = '".$sel_serviceID."'  $predeleteSub ";
            $predelete1 = $this->query($deleteUnwantedSub);

			//Ashwinikoli Code
			$delete_plan_of_care = "DELETE FROM sp_event_plan_of_care WHERE event_id = '".$arg['event_id_temp']."'  ";
			$predelete11 = $this->query($delete_plan_of_care);
			
			$delete_event_professional = "DELETE FROM sp_event_professional WHERE event_id = '".$arg['event_id_temp']."'  ";
			$predelete111 = $this->query($delete_event_professional);
			
            $SubServicediff = array_merge(array_diff($NewSubSerArray, $intersectSub), array_diff($intersectSub, $NewSubSerArray));
            //print_r($SubServicediff);
            for($ts=0;$ts<count($SubServicediff);$ts++)
            {
                $insertData['sub_service_id']=$SubServicediff[$ts];
                $insertData['event_id']=$arg['event_id_temp'];
                $insertData['service_id']=$sel_serviceID;
                $insertData['status']=1;
				$insertData['hospital_id']=$hospital_name;
				$insertData['Consultant']=$Consultant;
				
                if($SubServicediff[$ts] && $sel_serviceID)
                {
                    $select_exist = "SELECT event_requirement_id FROM sp_event_requirements WHERE event_id = '".$arg['event_id_temp']."' and sub_service_id = '".$SubServicediff[$ts]."'  ";
                    if(mysql_num_rows($this->query($select_exist)) == 0)
                    {
                        $insertData['added_by'] = $employee_id;
                        $insertData['added_date'] = date('Y-m-d H:i:s');
                        $RecordId=$this->query_insert('sp_event_requirements',$insertData);
						
						
						
                    }
                }
            }
        }
            
        /*$totalCount = count($diff);
        if($totalCount)
        {
            for($i=0;$i<$totalCount;$i++)
            {
                $select_service = "select service_id from sp_sub_services where sub_service_id = '".$diff[$i]."' ";
                $val_service = $this->fetch_array($this->query($select_service));

                $insertData['sub_service_id']=$diff[$i];
                $insertData['event_id']=$arg['event_id_temp'];
                $insertData['service_id']=$val_service['service_id'];
                $insertData['status']=1;
                if($diff[$i] && $val_service['service_id'])
                {
                    $select_exist = "SELECT event_requirement_id FROM sp_event_requirements WHERE event_id = '".$arg['event_id_temp']."' and sub_service_id = '".$diff[$i]."'  ";
                    if(mysql_num_rows($this->query($select_exist)) == 0)
                    {
                        $insertData['added_by'] = $employee_id;
                        $insertData['added_date'] = date('Y-m-d H:i:s');
                       // $RecordId=$this->query_insert('sp_event_requirements',$insertData);
                    }
                }
            }
        }*/
       
        /* ------- Update event  -------- */
        $temp_event_id = $arg['event_id_temp'];
        $updateData['note'] = $arg['notes'];
        $updateData['event_status'] = 2;
        $updateData['last_modified_by'] = $employee_id;
        $updateData['last_modified_date'] = date('Y-m-d H:i:s');
        $where = "event_id ='".$arg['event_id_temp']."' ";
        $this->query_update('sp_events',$updateData,$where);
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
        
        $select_plan_of_care = "SELECT plan_of_care_id,service_date,service_date_to,start_date,end_date,service_cost FROM sp_event_plan_of_care WHERE event_requirement_id='".$arg['event_requirement_id']."' and event_id = '".$event_id."'";
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
        $selectEvent = "select event_code,caller_id,relation,patient_id,purpose_id,event_date,note,description,event_status,estimate_cost from sp_events where event_id = '".$event_id."' ";
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
    public function GetEventRequirement($arg)
    {
       $event_id=$this->escape($arg['event_id']); 
       $RequirementSql="SELECT t1.event_requirement_id,t1.event_id,t1.service_id,t1.sub_service_id,t2.service_title,t3.recommomded_service FROM sp_event_requirements t1".
                       " INNER JOIN sp_services t2 ON t2.service_id=t1.service_id".
                       " INNER JOIN sp_sub_services t3 ON t3.sub_service_id=t1.sub_service_id".
                       " WHERE t1.event_id='".$event_id."'";
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
               $GetRecommondedServiceSql="SELECT plan_of_care_id,event_id,service_date,start_date,end_date FROM sp_event_plan_of_care WHERE event_requirement_id='".$GetEvntProfessional['event_requirement_id']."' AND event_id='".$GetEvntProfessional['event_id']."'";
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
    public function AssignEventWithHCM($arg)
    {
        $assigned_to=$this->escape($arg['assigned_to']);
        $event_id=$this->escape($arg['event_id']);
        
        $ChkAssignEventSql="SELECT event_id FROM sp_event_share_hcm WHERE event_id='".$event_id."' AND assigned_to='".$assigned_to."'"; 
        if($this->num_of_rows($this->query($ChkAssignEventSql)) == 0)
        {
           $insertData = array();
           $insertData['event_id']=$this->escape($arg['event_id']);
           $insertData['assigned_to']=$this->escape($arg['assigned_to']);
           $insertData['assigned_by']=$this->escape($arg['assigned_by']);
           $insertData['status']=$this->escape($arg['status']);
           $insertData['added_by']=$this->escape($arg['added_by']);
           $insertData['added_date']=$this->escape($arg['added_date']);
           $insertData['modified_by']=$this->escape($arg['modified_by']);
           $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
           $RecordId=$this->query_insert('sp_event_share_hcm',$insertData);
           if(!empty($RecordId))
                return $RecordId; 
            else
                return 0; 
        }
        else 
            return 0; 
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
    public function InsertPlanOfCare($arg)
    {
       // echo '<pre>';
      //  print_r($arg);
       // echo '</pre>';
       // exit;
        
        $insertData['event_requirement_id'] = $arg['event_requirement_id'];
        //$insertData['service_date'] = $arg['service_date'];
        //$insertData['start_date'] = $arg['start_date'];
        //$insertData['end_date'] = $arg['end_date'];
		$event_requirement_id=$arg['event_requirement_id'];
		$sub_service= mysql_query("SELECT * FROM sp_event_requirements  where event_requirement_id='$event_requirement_id'");
		$sub_service_id_new = mysql_fetch_array($sub_service) or die(mysql_error());
		$service_id=$sub_service_id_new['service_id'];
		$sub_service_id= $sub_service_id_new['sub_service_id'];
		
        $insertData['event_id'] = $arg['event_id'];
        $extras = $arg['extras'];
        $employee_id = $arg['employee_id'];
        for($v=0;$v<=$extras;$v++)
        {
            $existIDPlan = '';
            $insertData['start_date'] = $_REQUEST['starttime_'.$v.'_'.$arg['event_requirement_id']];
            $insertData['end_date'] = $_REQUEST['endtime_'.$v.'_'.$arg['event_requirement_id']];            
            $service_dates = $_REQUEST['eve_from_date_'.$v.'_'.$arg['event_requirement_id']];
			$service_date_tos = $_REQUEST['eve_to_date_'.$v.'_'.$arg['event_requirement_id']];
			
			if($service_dates!='')
				{
					$insertData['service_date'] =  date('Y-m-d',strtotime($service_dates));
				}
				else
				{
					$insertData['service_date'] = '';
				}
				
			if(($service_id==17 OR $service_id==13) AND $sub_service_id!=425)  
			{
				if($service_dates!='')
				{
				$todate=date('Y-m-d',strtotime($service_dates));
				$pkgdate= date('d-m-Y', strtotime('+30 day', strtotime($todate)));
				$insertData['service_date_to'] =  date('Y-m-d',strtotime($pkgdate));
				}
				else
				{
					$insertData['service_date_to'] =  '';
				}
			}
			else
			{
				$insertData['service_date_to'] =  date('Y-m-d',strtotime($service_date_tos));
			}
            //$service_date_tos = $_REQUEST['eve_to_date_'.$v.'_'.$arg['event_requirement_id']];
            //$abc= $_REQUEST['starttime_'.$v.'_'.$arg['event_requirement_id']];
			
            $insertData['service_cost'] =  $_REQUEST['hidden_costService_'.$v.'_'.$arg['event_requirement_id']];
            
            if($v == 0)
                $existIDPlan = $_REQUEST['existIDPlan_'.$arg['event_requirement_id']];
           
            $select_exist = "SELECT plan_of_care_id FROM sp_event_plan_of_care WHERE plan_of_care_id = '".$existIDPlan."'  ";
            if(mysql_num_rows($this->query($select_exist)))
            {
                $insertData['last_modified_by'] = $employee_id;
                $insertData['last_modified_date'] = date('Y-m-d H:i:s');
                $val_existRecord = $this->fetch_array($this->query($select_exist));
                $where = "plan_of_care_id ='".$val_existRecord['plan_of_care_id']."' ";
                $this->query_update('sp_event_plan_of_care',$insertData,$where);
                $RecordId = $val_existRecord['plan_of_care_id'];
            }
            else
            {
                $insertData['added_by'] = $employee_id;
                $insertData['added_date'] = date('Y-m-d H:i:s');
                $insertData['status'] = '1';
                $RecordId=$this->query_insert('sp_event_plan_of_care',$insertData);
            }
        }

            $updateEve['estimate_cost'] = '3';
            $updateEve['finalcost'] = $arg['finalcost_eve'];
            $whereEve = "event_id ='".$arg['event_id']."' ";
            $this->query_update('sp_events',$updateEve,$whereEve);
                
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
        }
        
      //   psr.service_id = '2' and psr.status = '1' and sp.status = '1' AND (sp.name LIKE '%kothrud%' || sp.first_name LIKE '%kothrud%' || sp.middle_name LIKE '%kothrud%' || sp.google_home_location LIKE '%kothrud%' || CASE WHEN set_location=1 THEN sp.google_home_location else sp.google_work_location END LIKE '%kothrud%' || sp.email_id LIKE '%kothrud%' )
                
        $select_Professional = "select sp.service_professional_id from 
                                sp_service_professionals as sp left join sp_professional_services as psr ON sp.service_professional_id = psr.service_professional_id
                                where  psr.service_id = '".$service_id."' and psr.status = '1' and sp.status = '1' ".$preWhere." ".$preWhereLoc." ".$preWhereProf." ";
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
                //echo $kmsliderto;
                if($kmsliderto)
                {
                    //print_r($data_val);
                    $lat1 = $data_val['lattitude'];
                    $long2 = $data_val['langitude'];
                    $units = 'K';
                    //echo $kmsliderfrom.'....';
                    //echo $lat.'-'.$lat1;
                    //echo '<br>';
                    //echo $long.'-'.$long2;
                   // echo '<br>';
                    if($lat && $long && $lat1 && $long2)
                        $distanceKM = distance($lat, $long, $lat1, $long2, $units);
                    else
                        $distanceKM = '';
                    //echo $kmsliderto.'///////';
                    //echo $distanceKM.'...';
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
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0);
    }
    public function InsertProfessional($arg)
    {
        $countProfarray = '';
        $newReqArr = $arg['professional_vender_id'];
        $selectprof_existing = "select professional_vender_id from sp_event_professional where event_id = '".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ";
        $allRequiremprof = $this->fetch_all_array($selectprof_existing);
        foreach($allRequiremprof as $key=>$valAllRequirements)
        {
            $countProfarray[] = $valAllRequirements['professional_vender_id'];
        }
        /* ------------------ delete/check existing services  -------- */
        $new_array = $newReqArr;
        $existArray = $countProfarray;
        //print_r($new_array);
        //print_r($existArray);
        if($new_array && $existArray)
        {
            $intersect = array_intersect($new_array,$existArray);
            //print_r($intersect);
            if(!empty($intersect))
                $comma_separated = implode(",", $intersect);

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
                foreach($GetEventProf as $key=>$valIds)
                {
                    $UpdateSql="UPDATE sp_event_job_summary SET isDelStatus='1' WHERE event_professional_id='".$valIds['event_professional_id']."'";
                    $this->query($UpdateSql);
                }
            }
            
            // First getting all records for delete job summary of unwanted professional 
            
            $GetAllProfDtlsSql="SELECT event_professional_id,event_id,professional_vender_id,service_id FROM sp_event_professional WHERE event_id='".$arg['event_id']."' and event_requirement_id='".$arg['event_requirement_id']."' $preDelete ";
            if(mysql_num_rows($this->query($GetAllProfDtlsSql)))
            {
                $GetAllProfDtls=$this->fetch_all_array($GetAllProfDtlsSql);
                foreach($GetAllProfDtls as $key=>$valProfIds)
                {
                    // Delete Design job summary of unwanted professional 
                    $deleteUnwantedSummary="DELETE FROM sp_event_job_summary WHERE event_id='".$valProfIds['event_id']."' AND service_id='".$valProfIds['service_id']."' AND event_professional_id='".$valProfIds['event_professional_id']."'";
                    $this->query($deleteUnwantedSummary);
                }   
            }
            $deleteUnwanted = "DELETE FROM sp_event_professional WHERE event_id = '".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' $preDelete ";
            $predelete = $this->query($deleteUnwanted);
            
            
            
            
            $updateAvailability1 = "update sp_professional_services set availability = '1' where service_professional_id IN (".$exist_separated.") ";
            $this->query($updateAvailability1);
            
            $diff = array_merge(array_diff($new_array, $intersect), array_diff($intersect, $new_array));
        }
        else
            $diff = $new_array;
        /* -------- complete delete/check existing services --------- */
        
        $totalCount = count($diff);
        //print_r($totalCount);
		
				
        if($totalCount)
        {
            for($i=0;$i<$totalCount;$i++)
            {
                $selectExist = "select event_professional_id from sp_event_professional where professional_vender_id = '".$arg['professional_vender_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' and event_id = '".$arg['event_id']."'  ";
                if(mysql_num_rows($this->query($selectExist)))
                {
                    $valProf = $this->fetch_array($this->query($selectExist));
                    $arg['modified_by'] = $arg['added_by'];
                    $arg['professional_vender_id'] = $arg['professional_vender_id'];
                    $arg['last_modified_date'] = date('Y-m-d H:i:s');
                    $whereEve = "event_professional_id ='".$valProf['event_professional_id']."' ";
                    $this->query_update('sp_event_professional',$arg,$whereEve);
                    $RecordId = $valProf['event_professional_id'];
                }
                else
                {
					/*$plan_of_care=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."'");
					while ($plan_of_care_detail1 = mysql_fetch_array($plan_of_care))
					{
						$plan_of_care_id=$plan_of_care_detail1['plan_of_care_id'];
						$arg['professional_vender_id'] = $diff[$i];
						$arg['plan_of_care_id'] = $plan_of_care_id;
						$arg['added_date'] = date('Y-m-d H:i:s');            
						$RecordId=$this->query_insert('sp_event_professional',$arg);
					}*/
					$arg['professional_vender_id'] =$arg['professional_vender_id'];
					$query=mysql_query("update sp_event_plan_of_care set professional_vender_id='".$arg['professional_vender_id']."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
					$query=mysql_query("update sp_event_requirements set professional_vender_id='".$arg['professional_vender_id']."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
                    $arg['added_date'] = date('Y-m-d H:i:s');            
                    $RecordId=$this->query_insert('sp_event_professional',$arg);
					//$query=mysql_query("update sp_event_plan_of_care set professional_vender_id='".$diff[$i]."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
					
                }
                $updateAvailability = "update sp_professional_services set availability = '2' where service_professional_id = '".$arg['professional_vender_id']."'";
                $this->query($updateAvailability);
				
            }
        }
        /*$select_extAvailability = "select professional_vender_id from sp_event_professional where professional_vender_id IN (".$exist_separated.")  ";
        if(mysql_num_rows($this->query($select_extAvailability)))
        {
            $alldataExPro = $this->fetch_all_array($select_extAvailability);
            foreach($alldataExPro as $key=>$valExiAvail)
            {
                $updateAvailability11 = "update sp_professional_services set availability = '2' where service_professional_id = '".$valExiAvail['professional_vender_id']."' ";
                $this->query($updateAvailability11);
            }
        }*/
        $upEvent['event_status'] = '3';
        $upEvent['event_id'] = $arg['event_id'];
        $updateEventstaus = $this->UpdateEventStatus($upEvent);
        //return $RecordId;
		$planofcareid=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id = '".$arg['event_requirement_id']."' and event_id = '".$arg['event_id']."'");
		$plan_of_care_detail = mysql_fetch_array($planofcareid) or die(mysql_error());
		$professional_vender_id=$plan_of_care_detail['professional_vender_id'];
		$query=mysql_query("update sp_event_plan_of_care set professional_vender_id='".$professional_vender_id."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
		$query=mysql_query("update sp_event_requirements set professional_vender_id='".$professional_vender_id."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
		$query=mysql_query("update sp_event_professional set professional_vender_id='".$professional_vender_id."' where event_id='".$arg['event_id']."' and event_requirement_id = '".$arg['event_requirement_id']."' ")or die(mysql_error());
	
		
		$event_requirement_id=$arg['event_requirement_id'];
		$Get_Service_id= mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'");
		$Service_id_row = mysql_fetch_array($Get_Service_id) or die(mysql_error());
		$service_id=$Service_id_row['service_id'];
		{
			if($service_id==3 Or $service_id==16)
			{
				$query=mysql_query("update sp_event_plan_of_care set professional_vender_id='".$professional_vender_id."' where event_id='".$arg['event_id']."' ")or die(mysql_error());
			}
		}
		
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
    public function InsertJobSummary($arg)
    {
        // check is it record present of this user 
        $selectExist="SELECT job_summary_id FROM sp_event_job_summary WHERE event_id='".$arg['event_id']."' AND service_id ='".$arg['service_id']."' AND event_professional_id='".$arg['event_professional_id']."' AND type='".$arg['type']."'";
        if(mysql_num_rows($this->query($selectExist)))
        {
            $Result=$this->fetch_array($this->query($selectExist));
            $updateData = array();
            $updateData['event_id']=$this->escape($arg['event_id']);
            $updateData['service_id']=$this->escape($arg['service_id']);
            $updateData['event_professional_id']=$this->escape($arg['event_professional_id']);
            $updateData['professional_vender_id']=$this->escape($arg['professional_vender_id']);
            $updateData['reporting_instruction']=$this->escape($arg['reporting_instruction']);
            $updateData['type']=$this->escape($arg['type']);
            $updateData['report_status']=$this->escape($arg['report_status']);
            $updateData['modified_by']=$this->escape($arg['modified_by']);
            $updateData['last_modified_date']=$this->escape($arg['last_modified_date']);
            $where="job_summary_id ='".$Result['job_summary_id']."' ";
            $RecordId = $this->query_update('sp_event_job_summary',$updateData,$where); 
        }
        else 
        {
            $insertData = array();
            $insertData['event_id']=$this->escape($arg['event_id']);
            $insertData['service_id']=$this->escape($arg['service_id']);
            $insertData['event_professional_id']=$this->escape($arg['event_professional_id']);
            $insertData['professional_vender_id']=$this->escape($arg['professional_vender_id']);
            $insertData['reporting_instruction']=$this->escape($arg['reporting_instruction']);
            $insertData['type']=$this->escape($arg['type']);
            $insertData['report_status']=$this->escape($arg['report_status']);
             $insertData['status']=$this->escape($arg['status']);
            $insertData['added_by']=$this->escape($arg['added_by']);
            $insertData['added_date']=$this->escape($arg['added_date']);
            $insertData['modified_by']=$this->escape($arg['modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            $RecordId=$this->query_insert('sp_event_job_summary',$insertData);
        } 	
        if(!empty($RecordId)) 
        {
            
            // Check is it job Closure available  for this event 
            
            $chk_job_closure="SELECT job_closure_id FROM sp_job_closure WHERE event_id='".$arg['event_id']."'";
            if(mysql_num_rows($this->query($selectExist))==0)
            {
                // Update Event Completion Status
                $UpdateEventStatusSql="UPDATE sp_events SET event_status='3' WHERE event_id='".$arg['event_id']."'";
                $this->query($UpdateEventStatusSql);
            }
            
            return 1;
        }
        else
           return 0; 		        
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
        $job_closure_id=$this->escape($arg['job_closure_id']);
        // check is it record present of this user 
        if($job_closure_id)
        {
            $selectExist="SELECT job_closure_id FROM sp_job_closure WHERE event_id='".$arg['event_id']."' AND professional_vender_id='".$arg['professional_vender_id']."' AND service_date='".$arg['service_date']."' AND job_closure_id !='".$job_closure_id."'"; 
        }
        else 
        {
            $selectExist="SELECT job_closure_id FROM sp_job_closure WHERE event_id='".$arg['event_id']."' AND professional_vender_id='".$arg['professional_vender_id']."' AND service_date='".$arg['service_date']."'"; 
        }
        if($this->num_of_rows($this->query($selectExist))==0)
        {
          $insertData = array();
          $insertData['event_id']=$this->escape($arg['event_id']);
          $insertData['professional_vender_id']=$arg['professional_vender_id'];
          $insertData['service_id']=$this->escape($arg['service_id']);
          $insertData['service_date']=$this->escape($arg['service_date']);
          $insertData['service_render']=$this->escape($arg['service_render']);
          $insertData['temprature']=$this->escape($arg['temprature']);
          $insertData['bsl']=$this->escape($arg['bsl']);
          $insertData['pulse']=$this->escape($arg['pulse']);
          $insertData['spo2']=$this->escape($arg['spo2']);
          $insertData['rr']=$this->escape($arg['rr']);
          $insertData['gcs_total']=$this->escape($arg['gcs_total']);
          $insertData['high_bp']=$this->escape($arg['high_bp']);
          $insertData['low_bp']=$this->escape($arg['low_bp']);
          $insertData['skin_perfusion']=$this->escape($arg['skin_perfusion']);
          $insertData['airway']=$this->escape($arg['airway']);
          $insertData['breathing']=$this->escape($arg['breathing']);
          $insertData['circulation']=$this->escape($arg['circulation']);
          $insertData['baseline']=$this->escape($arg['baseline']);
          $insertData['summary_note']=$this->escape($arg['summary_note']);
          if(!empty($arg['job_closure_file']))
          {
              if(!empty($job_closure_id))
              {
                  // Getting File Name 
                  
                  $GetFileSql="SELECT job_closure_file FROM sp_job_closure WHERE job_closure_id='".$job_closure_id."'";
                  $GetFile=$this->fetch_array($this->query($GetFileSql));
                  
                  if(!empty($GetFile))
                  {
                    // Unlink previous file
                    if($GetFile['job_closure_file'] && file_exists("JobClosureDocuments/".$GetFile['job_closure_file']))
                    {
                      unlink("JobClosureDocuments/".$GetFile['job_closure_file']);
                    }
                  }  
              }
              
            $insertData['job_closure_file']=$this->escape($arg['job_closure_file']);
          }
          
          
          $insertData['modified_by']=$this->escape($arg['modified_by']);
          $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
          if(!empty($job_closure_id))
          {
             $where="job_closure_id ='".$job_closure_id."' ";
             $RecordId=$this->query_update('sp_job_closure',$insertData,$where);
          }
          else 
          {
              $insertData['status']=$this->escape($arg['status']);
              $insertData['added_by']=$this->escape($arg['added_by']);
              $insertData['added_date']=$this->escape($arg['added_date']);
              $RecordId=$this->query_insert('sp_job_closure',$insertData);
          }
          
          if(!empty($RecordId)) 
          {
              // Update Event Completion Status
              $upEvent['event_status'] = '4';
              $upEvent['event_id'] = $arg['event_id'];
              $updateEventstaus = $this->UpdateEventStatus($upEvent);

              $update_sesrviceClosed = "update sp_event_professional set service_closed = 'Y' where event_id = '".$arg['event_id']."' ";
              $this->query($update_sesrviceClosed);
              // Delete Job Closure Entries from event list
              $eventIDForClosure = $arg['eventIDForClosure'];
              $DelRecordSql="DELETE FROM sp_events WHERE event_id ='".$eventIDForClosure."' AND caller_id='".$arg['Edit_CallerId']."' AND purpose_id='7'";
              $this->query($DelRecordSql);

              if(!empty($job_closure_id))
              {
                  return $job_closure_id;
              }
              else 
              {
                  return $RecordId;
              }
          }
          else
              return 0; 	

        }
        else
          return 0;  
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
        $service_date=$arg['service_date'];
        $argpass['allques'] = 'yes';
        $FeedBackQuestions= $this->GetFeedbackQuestions($argpass);
        foreach($FeedBackQuestions as $key=>$valQuestions)
        {
            $feedback_id = $valQuestions['feedback_id'];
            
            $insertFeedback['event_id']=$event_id;
            $insertFeedback['feedback_id']=$feedback_id;
            
            
            if($valQuestions['option_type'] =='4')
               $insertFeedback['answer']=$_REQUEST['rating_val_'.$feedback_id]; 
            else
                $insertFeedback['answer']=$_REQUEST['answer_'.$feedback_id];
            
            $insertFeedback['user_id']= $_REQUEST['feedbackCallerId'];
            $insertFeedback['user_type']='3';

            if($valQuestions['option_type'] != '3')
            {
                $insertFeedback['option_id']=$_REQUEST['option_value_'.$feedback_id];
                $insertFeedback['service_date']= $arg['service_date'];
                $selectExist = "select feedback_answer_id from sp_feedback_answers where event_id = '".$event_id."' and feedback_id = '".$feedback_id."' AND  service_date='".$service_date."'";
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
        
        $upEvent['event_status'] = '5';
        $upEvent['event_id'] = $event_id;
        $updateEventstaus = $this->UpdateEventStatus($upEvent);
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
        $RecordId = $this->query_update('sp_events',$update,$where); 
        return $RecordId;
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
     public function API_Add_event($arc)
	{
		
			
			
																			
		 $insertData['event_code']=$this->escape($arc['event_code']);
		 $insertData['caller_id']=$this->escape($arc['caller_id']);
		 $insertData['relation']=$this->escape($arc['relation']);
         $insertData['bill_no_ref_no']=$this->escape($arc['bill_no_ref_no']);
		 $insertData['patient_id']=$this->escape($arc['patient_id']);
		 $insertData['purpose_id']=$this->escape($arc['purpose_id']);
		 $insertData['enquiry_status']=$this->escape($arc['enquiry_status']);
         $insertData['status']=$this->escape($arc['status']);
		 $insertData['estimate_cost']=1;
		 $insertData['finalcost']=$this->escape($arc['finalcost']);
		 $insertData['event_status']=2;
         $insertData['branch_code']=$this->escape($arc['branch_code']);
		 $insertData['service_date_of_Enquiry']=$this->escape($arc['service_date_of_Enquiry']);
		 $insertData['event_date']=$this->escape($arc['event_date']);
		 $insertData['last_modified_date']=$this->escape($arc['last_modified_date']);
		  $insertData['added_date']=$this->escape($arc['added_date']);
		  $insertData['OTP']=$this->escape($arc['OTP']);
		  $insertData['Added_through']=2;
		   $insertData['hospital_id']=$this->escape($arc['hospital_id']);
		   $insertData['note']=$this->escape($arc['note']);
		  $insertData['added_by']=$this->escape($arc['added_by']);
		  $insertData['last_modified_by']=$this->escape($arc['last_modified_by']);
		 
	   $RecordId=$this->query_insert('sp_events',$insertData);
	   	 $event_id_new=mysql_insert_id();

	}
		 public function API_Extend_services($args)
	{
		 $insertData['event_id']=$this->escape($args['event_id']);
		 $insertData['plan_of_care_id']=$this->escape($args['plan_of_care_id']);
         $insertData['event_requirement_id']=$this->escape($args['event_requirement_id']);
		 $insertData['index_of_Session']=$this->escape($args['index_of_Session']);
		 $insertData['service_date']=$this->escape($args['service_date']);
		 $insertData['service_date_to']=$this->escape($args['service_date_to']);
		  $insertData['professional_vender_id']=$this->escape($args['professional_vender_id']);
         $insertData['Actual_Service_date']=$this->escape($args['Actual_Service_date']);
		 $insertData['start_date']=$this->escape($args['start_date']);
		
         $insertData['end_date']=$this->escape($args['end_date']);
         $insertData['added_date']=$this->escape($args['added_date']);
		 $insertData['last_modified_date']=$this->escape($args['last_modified_date']);
		 $insertData['status']=$this->escape($args['status']);
		 $insertData['extend_service_id']=$this->escape($args['extend_service_id']);
		 $insertData['Session_status']=$this->escape($args['Session_status']);
		 
	   $RecordId=$this->query_insert('sp_detailed_event_plan_of_care',$insertData);
	   	$event_req_id_new=mysql_insert_id();

	}
		 public function API_Add_event_requirements($arr)
	{
												
		
																			
		 $insertData['event_id']=$this->escape($arr['event_id']);	
		$insertData['service_id']=$this->escape($arr['service_id']);
		  //$insertData['last_modified_by']=$this->escape($arr['last_modified_by']);
         $insertData['sub_service_id']=$this->escape($arr['sub_service_id']);		
			// $insertData['added_by']=$this->escape($arr['added_by']);
		 $insertData['last_modified_date']=$this->escape($arr['last_modified_date']);
		  $insertData['added_date']=$this->escape($arr['added_date']);
		 		 
		
		 
	   $RecordId=$this->query_insert('sp_event_requirements',$insertData);
	   	

	}
	 public function API_Add_plan_of_care($arrs)
	{
																			
		 $insertData['event_id']=$this->escape($arrs['event_id']);
		 
         $insertData['event_requirement_id']=$this->escape($arrs['event_requirement_id']);
		 $insertData['last_modified_by']=$this->escape($arrs['last_modified_by']);
		 $insertData['service_date']=$this->escape($arrs['service_date']);
		 $insertData['service_date_to']=$this->escape($arrs['service_date_to']);
		  $insertData['professional_vender_id']=$this->escape($arrs['professional_vender_id']);
         $insertData['added_by']=$this->escape($arrs['added_by']);
		 $insertData['start_date']=$this->escape($arrs['start_date']);
		
         $insertData['end_date']=$this->escape($arrs['end_date']);
         $insertData['added_date']=$this->escape($arrs['added_date']);
		 $insertData['last_modified_date']=$this->escape($arrs['last_modified_date']);
		 $insertData['status']=$this->escape($arrs['status']);
		  
		
		 
	   $RecordId=$this->query_insert('sp_event_plan_of_care',$insertData);
	   $event_plan_of_care_new=mysql_insert_id();	

	}
	 public function API_Extend_services_enquiry($arg)
	 {
			
																		
		 $insertData['estimate_cost']=$this->escape($arg['estimate_cost']);	 
		 $insertData['service_date']=$this->escape($arg['service_date']);
		 $insertData['service_date_to']=$this->escape($arg['service_date_to']);		
		 $insertData['startTime']=$this->escape($arg['startTime']);
		 $insertData['endTime']=$this->escape($arg['endTime']);				 
         $insertData['event_id']=$this->escape($arg['event_id']);
		 $insertData['plan_of_care_id']=$this->escape($arg['plan_of_care_id']);
		 $insertData['OTP']=$this->escape($arg['OTP']);
		 
		 
		 $insertData['status']=$this->escape($arg['status']);		 
         $insertData['added_date']=$this->escape($arg['added_date']);
		 		 
		
		 
	   $RecordId=$this->query_insert('sp_extend_service',$insertData);
	   $extend_service_id=mysql_insert_id();	

	}
	public function API_Initialize_Transaction($arg)
	 {
			
													
																		
		 $insertData['mId']=$this->escape($arg['mId']);	 
		 $insertData['channelId']=$this->escape($arg['channelId']);
		 $insertData['professional_id']=$this->escape($arg['professional_id']);		
		 $insertData['mobileNo']=$this->escape($arg['mobileNo']);
		 $insertData['email']=$this->escape($arg['email']);				 
         $insertData['transaction_Amount']=$this->escape($arg['transaction_Amount']);
		 $insertData['website']=$this->escape($arg['website']);
		 $insertData['industryTypeId']=$this->escape($arg['industryTypeId']);
		 $insertData['callbackUrl']=$this->escape($arg['callbackUrl']);	
		  $insertData['added_date']=$this->escape($arg['added_date']);
        	 $insertData['pay_status']=1;	
		 		 
		
		 
	   $RecordId=$this->query_insert('sp_payment_transaction',$insertData);
	   $ORDER_ID=mysql_insert_id();	

	}
	 public function API_Payment_response($arg)
	 {
			
																		
		 $insertData['transaction_id']=$this->escape($arg['transaction_id']);	 
		 $insertData['bank_transaction_id']=$this->escape($arg['bank_transaction_id']);
		 $insertData['order_id']=$this->escape($arg['order_id']);		
		 $insertData['transcation_amount']=$this->escape($arg['transcation_amount']);
		 $insertData['status']=$this->escape($arg['status']);				 
         $insertData['transcation_type']=$this->escape($arg['transcation_type']);
		 $insertData['gateway_name']=$this->escape($arg['gateway_name']);
		 $insertData['response_code']=$this->escape($arg['response_code']);
		 $insertData['response_msg']=$this->escape($arg['response_msg']);		 
         $insertData['bank_name']=$this->escape($arg['bank_name']);
		 $insertData['MID']=$this->escape($arg['MID']);
		 $insertData['payment_mode']=$this->escape($arg['payment_mode']);
		 $insertData['refund_amount']=$this->escape($arg['refund_amount']);
				 
         $insertData['transcation_date']=$this->escape($arg['transcation_date']);
		 		 		 
		
		 
	   $RecordId=$this->query_insert('sp_payment_response',$insertData);
		

	}
		public function API_JobClouser($args)
	{								
	
		 $date = date('Y-m-d H:i:s');				
		$insertData['event_id']=$this->escape($args['event_id']);
		$insertData['professional_vender_id']=$this->escape($args['professional_vender_id']);
		$insertData['service_id']=$this->escape($args['service_id']);
		$insertData['service_render']=1;
		$insertData['temprature']=$this->escape($args['temprature']);
		$insertData['bsl']=$this->escape($args['bsl']);
		$insertData['spo2']=$this->escape($args['spo2']);
		$insertData['pulse']=$this->escape($args['pulse']);
		$insertData['rr']=$this->escape($args['rr']);
		$insertData['gcs_total']=$this->escape($args['gcs_total']);
		$insertData['high_bp']=$this->escape($args['high_bp']);
		$insertData['low_bp']=$this->escape($args['low_bp']);
		$insertData['skin_perfusion']=$this->escape($args['skin_perfusion']);
		$insertData['airway']=$this->escape($args['airway']);
		$insertData['breathing']=$this->escape($args['breathing']);
		$insertData['circulation']=$this->escape($args['circulation']);
		$insertData['baseline']=$this->escape($args['baseline']);
		$insertData['summary_note']=$this->escape($args['summary_note']);
		$insertData['added_date']=$date;
		$insertData['last_modified_date']=$date;
		
		
         
		 
	   $RecordId=$this->query_insert('sp_job_closure',$insertData);
	   	 
}
	public function API_session_reschedule($arg)
	 {
					
							
																		
		 $insertData['event_id']=$this->escape($arg['event_id']);	 
		 $insertData['professional_id']=$this->escape($arg['professional_id']);
		 $insertData['detail_plan_of_care_id']=$this->escape($arg['detail_plan_of_care_id']);		
		 $insertData['reschedule_start_date']=$this->escape($arg['reschedule_start_date']);
		 $insertData['added_date']=$this->escape($arg['added_date']);	
		 $insertData['reschedule_reason']=$this->escape($arg['reschedule_reason']);
		  $insertData['reschedule_end_date']=$this->escape($arg['reschedule_end_date']);
		  $insertData['reschedule_start_time']=$this->escape($arg['reschedule_start_time']);
		  $insertData['reschedule_end_time']=$this->escape($arg['reschedule_end_time']);
		   $insertData['added_user_id']=$this->escape($arg['added_user_id']);
		   	   $insertData['session_start_date']=$this->escape($arg['session_start_date']);
		   	   	   $insertData['session_end_date']=$this->escape($arg['session_end_date']);
		  	
		 
	   $RecordId=$this->query_insert('sp_reschedule_session',$insertData);
	  

	}


		
}
//END
?>