<?php
    if (!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if (!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class adminuserClass extends AbstractDB 
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
    public function AdminUserList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if (!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (name LIKE '%".$search_value."%'  OR first_name LIKE '%".$search_value."%' OR middle_name LIKE '%".$search_value."%' OR email_id LIKE '%".$search_value."%' OR landline_no LIKE '%".$search_value."%' OR mobile_no LIKE '%".$search_value."%')"; 
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
        $AdminUserSql="SELECT admin_user_id FROM sp_admin_users WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($AdminUserSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($AdminUserSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT admin_user_id,name,first_name,middle_name,email_id,landline_no,mobile_no,alternate_email_id,type,status,isDelStatus,added_by,added_date,last_login_time FROM sp_admin_users WHERE admin_user_id = '" .$val_records['admin_user_id'] . "'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // If landline Number Not Available
                if (empty($RecordResult['landline_no']))
                    $RecordResult['landline_no']='Not Available';
                
                // Getting User Type
                if (!empty($RecordResult['type']))
                {
                    $UserTypeArr=array(1=>'Super Admin',2=>'Admin',3=>'HR Admin');
                    $RecordResult['typeVal']=$UserTypeArr[$RecordResult['type']];
                }
                // Getting Status
                if (!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                $this->resultAdminUser[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if (count($this->resultAdminUser))
        {
            $resultArray['data']=$this->resultAdminUser;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetAdminUserById($arg)
    {
        $admin_user_id=$this->escape($arg['admin_user_id']);
        $GetOneAdminUserSql="SELECT admin_user_id,name,first_name,middle_name,email_id,landline_no,mobile_no,alternate_email_id,type,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date,last_login_time FROM sp_admin_users WHERE admin_user_id = '" .$admin_user_id . "'";
        if ($this->num_of_rows($this->query($GetOneAdminUserSql)))
        {
            $AdminUser = $this->fetch_array($this->query($GetOneAdminUserSql));
            // Getting User Type
            if (!empty($AdminUser['type']))
            {
                $UserTypeArr=array(1=>'Super Admin',2=>'Admin',3=>'HR Admin');
                $AdminUser['typeVal']=$UserTypeArr[$AdminUser['type']];
            }
            // Getting Status
            if (!empty($AdminUser['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $AdminUser['statusVal']=$StatusArr[$AdminUser['status']];
            }
            
            // Getting Added User Name 
            if (!empty($AdminUser['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id = '" .$AdminUser['added_by'] . "'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $AdminUser['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if (!empty($AdminUser['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id = '" .$AdminUser['last_modified_by'] . "'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $AdminUser['last_modified_by']=$ModifiedUser['name'];
            }
            return $AdminUser;
        }
        else 
            return 0;            
    }

    /**
     *
     * This function is used fo add admin user details
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function AddAdminUser($arg)
    {
        $admin_user_id = $this->escape($arg['admin_user_id']);

        if (!empty($admin_user_id) && $admin_user_id != '') {
            // Get medicine details
            $userDtls = $this->GetAdminUserById($arg);
            $ChkAdminUserSql = "SELECT admin_user_id FROM sp_admin_users WHERE email_id = '" . $arg['email_id'] . "' AND status !='3' AND admin_user_id ! = '" . $admin_user_id . "'";
        } else { 
            $ChkAdminUserSql="SELECT admin_user_id FROM sp_admin_users WHERE email_id = '" . $arg['email_id'] . "' AND status !='3'";
        }

        if ($this->num_of_rows($this->query($ChkAdminUserSql)) == 0) {
            $insertData = array();
            $insertData['name']               = $this->escape($arg['name']);
            $insertData['first_name']         = $this->escape($arg['first_name']);
            $insertData['middle_name']        = $this->escape($arg['middle_name']);
            $insertData['email_id']           = $this->escape($arg['email_id']);
            $insertData['landline_no']        = $this->escape($arg['landline_no']);
            $insertData['mobile_no']          = $this->escape($arg['mobile_no']);
            $insertData['alternate_email_id'] = $this->escape($arg['alternate_email_id']);
            $insertData['type']               = $this->escape($arg['type']);
            $insertData['last_modified_by']   = $this->escape($arg['last_modified_by']);
            $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);

            if (!empty($admin_user_id)) {
                $where = "admin_user_id  = '" . $admin_user_id . "' ";
                $RecordId = $this->query_update('sp_admin_users', $insertData, $where); 
            } else {
                $insertData['password']   = $this->escape(md5($arg['password']));
                $insertData['status']     = $this->escape($arg['status']);
                $insertData['added_by']   = $this->escape($arg['added_by']);
                $insertData['added_date'] = $this->escape($arg['added_date']);
                $RecordId = $this->query_insert('sp_admin_users', $insertData);
            }
            
            if (!empty($RecordId)) {
                // Add activity details while adding medicine details
                $param = array();
                $param['admin_user_id']   = $admin_user_id;
                $param['medicine_dtls']   = $userDtls;
                $param['record_data']     = $insertData;
                $this->addActivity($param);
                unset($param);
                return $RecordId; 
            } else {
                return 0;
            }
        }
        else  {
            return 0;
        }
    }

    /**
     *
     * This function is used fo update admin user status
     *
     * @param array $arg
     *
     * @return int 0|1
     *
     */
    public function ChangeStatus($arg)
    {
        $admin_user_id = $this->escape($arg['admin_user_id']);
        $status        = $this->escape($arg['status']);
        $pre_status    = $this->escape($arg['curr_status']);
        $istrashDelete = $this->escape($arg['istrashDelete']);
        $login_user_id = $this->escape($arg['login_user_id']);

        $ChkAdminUserSql = "SELECT admin_user_id,
            status,
            isDelStatus,
            last_modified_by,
            last_modified_date
        FROM sp_admin_users 
        WHERE admin_user_id = '" . $admin_user_id . "'";

        if ($this->num_of_rows($this->query($ChkAdminUserSql))) {
            $adminUserDtls = $this->fetch_array($this->query($ChkAdminUserSql));
            if ($istrashDelete) {
                // Delete All Permission Records
                $DelPermissionsSql="DELETE FROM sp_admin_users_modules WHERE admin_user_id = '" . $admin_user_id . "'";
                $this->query($DelPermissionsSql);
                $UpdateStatusSql="DELETE FROM sp_admin_users WHERE admin_user_id = '" . $admin_user_id . "'";
            } else {
                $UpdateStatusSql="UPDATE sp_admin_users SET status = '" .$status . "',isDelStatus = '" .$pre_status . "',last_modified_by = '" .$login_user_id . "',last_modified_date = '" .date('Y-m-d H:i:s') . "' WHERE admin_user_id = '" .$admin_user_id . "'";
            }
            $RecordId = $this->query($UpdateStatusSql);

            if (!empty($RecordId)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '2';
                $insertActivityArr['module_name']   = 'Manage System Users';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Admin user details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "status is change from " . $adminUserDtls['status'] . " to " . $status . "\r\n";
                $activityDesc .= "isDelStatus is change from " . $adminUserDtls['isDelStatus'] . " to " . $pre_status . "\r\n";
                $activityDesc .= "modified_by is change from " . $adminUserDtls['modified_by'] . " to " . $login_user_id . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $adminUserDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

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
    public function GetAllPermissions()
    {
        $GetPermissionsSql="SELECT module_id,module_name FROM sp_modules WHERE status='1'";
        if ($this->num_of_rows($this->query($GetPermissionsSql)))
        {
            $Modules=$this->fetch_all_array($GetPermissionsSql);
            return $Modules;
        }
        else 
            return 0;
    }
    public function GetUserPermissionsById($arg)
    {
       $admin_user_id=$this->escape($arg['admin_user_id']);
       $GetUserPermissionsSql="SELECT admin_user_module_id,admin_user_id,module_id,status,added_by,added_date,last_modified_by,last_modified_date FROM sp_admin_users_modules WHERE admin_user_id = '" .$admin_user_id . "' AND status !='3'";
       if ($this->num_of_rows($this->query($GetUserPermissionsSql)))
       {
          $UserPermissions=$this->fetch_all_array($GetUserPermissionsSql);
          return $UserPermissions;
       }
       else 
           return 0;
    }

    /**
     *
     * This function is used for add user permission
     *
     * @param array $arg
     *
     * @return int $RecordId
     */
    public function addUserPermission($arg)
    {
        $admin_user_id = $this->escape($arg['admin_user_id']);
        $module_id     = $this->escape($arg['module_id']);
        $ChkUserPermissionSql = "SELECT admin_user_module_id FROM sp_admin_users_modules WHERE admin_user_id = '" . $admin_user_id . "' AND module_id = '" . $module_id . "'";
        if ($this->num_of_rows($this->query($ChkUserPermissionSql)) == 0) {
            $insertData = array();
            $insertData['admin_user_id']      = $this->escape($arg['admin_user_id']);
            $insertData['module_id']          = $this->escape($arg['module_id']);
            $insertData['status']             = '1';
            $insertData['added_by']           = $_SESSION['admin_user_id'];
            $insertData['added_date']         = date('Y-m-d H:i:s');
            $insertData['last_modified_by']   = $this->escape($arg['last_modified_by']);
            $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);
            $RecordId = $this->query_insert('sp_admin_users_modules', $insertData);

            if (!empty($RecordId)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '2';
                $insertActivityArr['module_name']   = 'Manage System Users';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "The module " . $module_id . "has been successfully assigned to user " . $admin_user_id . "  by " . $_SESSION['admin_user_name'] . "\r\n";
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
     * This function is used for get assigned hospital list by system user id
     * 
     * @param array $arg
     * 
     * @return array $result
     */
    public function getAssignedHospitalById($arg)
    {
        $result = array();
        $adminUserId = $this->escape($arg['admin_user_id']);
        if (!empty($adminUserId)) {
            $getAssignedHospitalSql = "SELECT id,
                    admin_user_id,
                    hospital_id,
                    added_by,
                    added_date,
                    last_modified_by,
                    last_modified_date
                FROM sp_admin_user_hospital_mapping 
                WHERE admin_user_id = '" . $adminUserId . "' ";

            if ($this->num_of_rows($this->query($getAssignedHospitalSql))) {
                $result = $this->fetch_all_array($getAssignedHospitalSql);
            }
        }
        return $result;
    }

    /**
     *
     * This function is used for assign hospital to system user id
     *
     * @param array $arg
     * 
     * @return int $recordId
     */
    public function assignHospitalToUser($arg)
    {
        $recordId = 0;
        if (!empty($arg)) {
            $adminUserId = $this->escape($arg['admin_user_id']);
            $hospitalId = $this->escape($arg['hospital_id']);

            $chkAssignedHospitalSql = "SELECT id FROM sp_admin_user_hospital_mapping WHERE admin_user_id = '" . $adminUserId . "' AND hospital_id = '" . $hospitalId . "'";
            if ($this->num_of_rows($this->query($chkAssignedHospitalSql)) == 0)
            {
                $insertData = array();
                $insertData['admin_user_id'] = $this->escape($arg['admin_user_id']);
                $insertData['hospital_id'] = $this->escape($arg['hospital_id']);
                $insertData['added_by'] = $_SESSION['admin_user_id'];
                $insertData['added_date'] = date('Y-m-d H:i:s');
                $insertData['last_modified_by'] = $this->escape($arg['last_modified_by']);
                $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);
                $recordId = $this->query_insert('sp_admin_user_hospital_mapping', $insertData);

                if (!empty($recordId)) {
                    $insertActivityArr = array();
                    $insertActivityArr['module_type']   = '2';
                    $insertActivityArr['module_id']     = '2';
                    $insertActivityArr['module_name']   = 'Manage System Users';
                    $insertActivityArr['event_id']      = '';
                    $insertActivityArr['purpose_id']    = '';
                    $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                    $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                    $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                    $activityDesc = "The hospital " . $hospitalId . "has been successfully assigned to user " . $adminUserId . "  by " . $_SESSION['admin_user_name'] . "\r\n";
                    $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                    $this->query_insert('sp_user_activity', $insertActivityArr);
                    unset($insertActivityArr);
                }

            }
        }
        return $recordId;
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
            $adminUserId   = $args['admin_user_id'] ;
            $userDtls      = $args['user_dtls'];
            $insertData    = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '2';
            $insertActivityArr['module_name']   = 'Manage System Users';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Admin user details " . ( $adminUserId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($userDtls) && !empty($insertData)) {
                unset($userDtls['admin_user_id'],
                    $userDtls['typeVal'],
                    $userDtls['statusVal'],
                    $userDtls['added_by']
                );
                $userDiff = array_diff_assoc($userDtls, $insertData);
                if (!empty($userDiff)) {
                    foreach ($userDtls AS $key => $valUser) {
                        $activityDesc .= $key . " is change from " . $valUser . " to " . $insertData[$key] . "\r\n";
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