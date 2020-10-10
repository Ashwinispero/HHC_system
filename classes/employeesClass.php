<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class employeesClass extends AbstractDB 
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
    public function EmployeesList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isHCM=$this->escape($arg['isHCM']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (employee_code LIKE '%".$search_value."%' OR name LIKE '%".$search_value."%' OR designation LIKE '%".$search_value."%' OR email_id LIKE '%".$search_value."%' OR phone_no LIKE '%".$search_value."%' OR mobile_no LIKE '%".$search_value."%')"; 
        }
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .="ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        if(!empty($isTrash) && $isTrash !='null')
        {
           $preWhere .="AND status='3'"; 
        }
        else 
        {
          $preWhere .="AND status IN ('1','2')";   
        }
        if(!empty($isHCM) && $isHCM !='null')
        {
           $preWhere .="AND type='1'"; 
        }
        $EmployeesSql="SELECT employee_id FROM sp_employees WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($EmployeesSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($EmployeesSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT employee_id,employee_code,type,hospital_id,name,first_name,middle_name,designation,email_id,phone_no,mobile_no,location_id,specialization,dob,status,isDelStatus,added_date FROM sp_employees WHERE employee_id='".$val_records['employee_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                
                // If landline Number Not Available
                
                if(empty($RecordResult['phone_no']))
                    $RecordResult['phone_no']='Not Available';
                
                // Getting User Type
                if(!empty($RecordResult['type']))
                {
                    $EmployeeTypeArr=array(1=>'HCM',2=>'HD',3=>'Accountant',4=>'Office Assistant',5=>'Trainer');
                    $RecordResult['typeVal']=$EmployeeTypeArr[$RecordResult['type']];
                }
                // Getting Location Name
            
                if(!empty($RecordResult['location_id']))
                {
                   $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$RecordResult['location_id']."'";
                   $LocationDtls=$this->fetch_array($this->query($LocationSql));
                   $RecordResult['locationNm']=$LocationDtls['location']; 
                   $RecordResult['LocationPinCode']=$LocationDtls['pin_code']; 
                }
                
                
                // Getting Hospital Name
            
                if(!empty($RecordResult['hospital_id']))
                {
                   $HospitalSql="SELECT hospital_id,hospital_name FROM sp_hospitals WHERE hospital_id='".$RecordResult['hospital_id']."'";
                   $HospitalDtls=$this->fetch_array($this->query($HospitalSql));
                   $RecordResult['hospitalNm']=$HospitalDtls['hospital_name'];  
                }
                
                
                // Getting Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                
                $this->resultEmployees[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultEmployees))
        {
            $resultArray['data']=$this->resultEmployees;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function get_emp_status($arg)
    {
        $employee_id=$this->escape($arg['employee_id']);
        $GetOneEmployeeStatusSql="SELECT * FROM sp_ready_pause_history WHERE user_id='".$employee_id."' order by date_time DESC limit 1";
        if($this->num_of_rows($this->query($GetOneEmployeeStatusSql)))
        {
            $Employee_status = $this->fetch_array($this->query($GetOneEmployeeStatusSql));
            $Employee_current['mode_status']=$Employee_status['mode_status']; 
            return $Employee_current;
        }
        else {
            return 0;
        }
        
    }
    public function GetEmployeeById($arg)
    {
        $employee_id=$this->escape($arg['employee_id']);
        $GetOneEmployeeSql="SELECT employee_id,employee_code,type,hospital_id,name,first_name,middle_name,designation,email_id,phone_no,mobile_no,dob,address,work_phone_no,work_email_id,location_id,qualification,specialization,work_experience,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date FROM sp_employees WHERE employee_id='".$employee_id."'";
        if($this->num_of_rows($this->query($GetOneEmployeeSql)))
        {
            $Employee = $this->fetch_array($this->query($GetOneEmployeeSql));
	
            // Getting User Type
            if(!empty($Employee['type']))
            {
                $EmployeeTypeArr=array(1=>'HCM',2=>'HD',3=>'Accountant',4=>'Office Assistant',5=>'Trainer');
                $Employee['typeVal']=$EmployeeTypeArr[$Employee['type']];
            }
            // Getting Location Name
            
            if(!empty($Employee['location_id']))
            {
               $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$Employee['location_id']."'";
               $LocationDtls=$this->fetch_array($this->query($LocationSql));
               $Employee['locationNm']=$LocationDtls['location']; 
               $Employee['LocationPinCode']=$LocationDtls['pin_code']; 
            }
            
             // Getting Hospital Name
            
            if(!empty($Employee['hospital_id']))
            {
               $HospitalSql="SELECT hospital_id,hospital_name FROM sp_hospitals WHERE hospital_id='".$Employee['hospital_id']."'";
               $HospitalDtls=$this->fetch_array($this->query($HospitalSql));
               $Employee['hospitalNm']=$HospitalDtls['hospital_name'];  
            }
            
            // Getting Status
            if(!empty($Employee['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Employee['statusVal']=$StatusArr[$Employee['status']];
            }
            
            // Getting Added User Name 
            if(!empty($Employee['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Employee['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Employee['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Employee['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Employee['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Employee['last_modified_by']=$ModifiedUser['name'];
            }
            return $Employee;
        }
        else 
            return 0;            
    }

    /**
     *
     * This function is used fo add employee details
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function AddEmployee($arg)
    {
        $employee_id = $this->escape($arg['employee_id']);

        // Generate Random Number 
        $GetMaxRecordIdSql = "SELECT MAX(employee_id) AS MaxId FROM sp_employees";
        if ($this->num_of_rows($this->query($GetMaxRecordIdSql))) {
            $MaxRecord = $this->fetch_array($this->query($GetMaxRecordIdSql));
            $getMaxRecordId = $MaxRecord['MaxId'];
        } else {
            $getMaxRecordId = 0;
        }

        $prefix = $GLOBALS['EmpPrefix'];
        $EmpCode = Generate_Number($prefix, $getMaxRecordId);

        if (!empty($employee_id) && $employee_id !='') {
            $employeeDtls = $this->GetEmployeeById($arg);
            $ChkEmployeeSql="SELECT employee_id FROM sp_employees WHERE email_id='".$arg['email_id']."' AND status !='3' AND employee_id !='".$employee_id."'";
        } else { 
            $ChkEmployeeSql="SELECT employee_id FROM sp_employees WHERE email_id='".$arg['email_id']."' AND status !='3' and employee_code !='".$EmpCode."'"; 
        }

        if ($this->num_of_rows($this->query($ChkEmployeeSql)) == 0) {
            $insertData = array();
            if (empty($employee_id)) {
                $insertData['employee_code'] = $EmpCode;
            }
            $insertData['type']               = $this->escape($arg['type']);
            $insertData['hospital_id']        = $this->escape($arg['hospital_id']);
            $insertData['name']               = $this->escape($arg['name']);
            $insertData['first_name']         = $this->escape($arg['first_name']);
            $insertData['middle_name']        = $this->escape($arg['middle_name']);
            $insertData['designation']        = $this->escape($arg['designation']);
            $insertData['email_id']           = $this->escape($arg['email_id']);
            $insertData['phone_no']           = $this->escape($arg['phone_no']);
            $insertData['mobile_no']          = $this->escape($arg['mobile_no']);
            $insertData['dob']                = $this->escape($arg['dob']);
            $insertData['address']            = $this->escape($arg['address']);
            $insertData['work_phone_no']      = $this->escape($arg['work_phone_no']);
            $insertData['work_email_id']      = $this->escape($arg['work_email_id']);
            $insertData['location_id']        = $this->escape($arg['location_id']);
            $insertData['qualification']      = $this->escape($arg['qualification']);
            $insertData['specialization']     = $this->escape($arg['specialization']);
            $insertData['work_experience']    = $this->escape($arg['work_experience']);
            $insertData['last_modified_by']   = $this->escape($arg['last_modified_by']);
            $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);

            if (!empty($employee_id)) {
                $where = "employee_id = '" . $employee_id . "'";
                $RecordId = $this->query_update('sp_employees',$insertData,$where); 
            } else {
                $insertData['password']   = $this->escape(md5($arg['password']));
                $insertData['status']     = $this->escape($arg['status']);
                $insertData['added_by']   = $this->escape($arg['added_by']);
                $insertData['added_date'] = $this->escape($arg['added_date']);
                $RecordId = $this->query_insert('sp_employees',$insertData);
            }
            
            if (!empty($RecordId)) {
                // Add activity details while adding employee details
                $param = array();
                $param['employee_id']   = $employee_id;
                $param['employee_dtls'] = $employeeDtls;
                $param['record_data']   = $insertData;
                $this->addActivity($param);
                unset($param);
                return $RecordId;
            } else {
                return 0;
            }
        } else { 
            return 0;  
        }
    }

    /**
     *
     * This function is used fo update employee status
     *
     * @param array $arg
     *
     * @return int 0|1
     *
     */
    public function ChangeStatus($arg)
    {
        $employee_id    = $this->escape($arg['employee_id']);
        $status         = $this->escape($arg['status']);
        $pre_status     = $this->escape($arg['curr_status']);
        $istrashDelete  = $this->escape($arg['istrashDelete']);
        $login_user_id  = $this->escape($arg['login_user_id']);

        $ChkAdminUserSql = "SELECT employee_id,
            status,
            isDelStatus,
            last_modified_by,
            last_modified_date
        FROM sp_employees 
        WHERE employee_id = '" . $employee_id . "'";

        if ($this->num_of_rows($this->query($ChkAdminUserSql))) {
            $employeeDtls = $this->fetch_array($this->query($ChkAdminUserSql));
            if ($istrashDelete) {
                $UpdateStatusSql = "DELETE FROM sp_employees WHERE employee_id = '"  .$employee_id . "'";
            } else {
                $UpdateStatusSql = "UPDATE sp_employees SET status = '" . $status . "', isDelStatus = '" . $pre_status . "', last_modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE employee_id = '" . $employee_id . "'";
            }
            $RecordId = $this->query($UpdateStatusSql);

            if (!empty($RecordId) && !empty($employeeDtls)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '5';
                $insertActivityArr['module_name']   = 'Manage Employees';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Consumables details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "status is change from " . $employeeDtls['status'] . " to " . $status . "\r\n";
                $activityDesc .= "isDelStatus is change from " . $employeeDtls['isDelStatus'] . " to " . $pre_status . "\r\n";
                $activityDesc .= "modified_by is change from " . $employeeDtls['modified_by'] . " to " . $_SESSION['admin_user_id'] . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $employeeDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
            }
            return $RecordId;
        }
        else { 
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
            $employeeId   = $args['employee_id'] ;
            $employeeDtls = $args['employee_dtls'];
            $insertData   = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '5';
            $insertActivityArr['module_name']   = 'Manage Employees';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Employee details " . ( $employeeId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($employeeDtls) && !empty($insertData)) {
                unset($employeeDtls['employee_id'],
                    $employeeDtls['status'],
                    $employeeDtls['statusVal'],
                    $employeeDtls['typeVal'],
                    $employeeDtls['locationNm'],
                    $employeeDtls['LocationPinCode'],
                    $employeeDtls['hospitalNm'],
                    $employeeDtls['added_by'],
                    $employeeDtls['added_date'],
                    $employeeDtls['last_modified_by']
                );
                $employeeDiff = array_diff_assoc($employeeDtls, $insertData);
                if (!empty($employeeDiff)) {
                    foreach ($employeeDtls AS $key => $valEmployee) {
                        $activityDesc .= $key . " is change from " . $valEmployee . " to " . $insertData[$key] . "\r\n";
                    }
                }
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