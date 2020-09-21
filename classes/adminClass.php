<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class adminClass extends AbstractDB 
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
    public function selectAdmin($arg)
    {
        $admin_user_id = $this->escape($arg);
        $selectOne = "SELECT admin_user_id,name,first_name,middle_name,email_id,password,alternate_email_id,mobile_no,type,landline_no FROM sp_admin_users WHERE admin_user_id ='".$admin_user_id."'";
        if($this->num_of_rows($this->query($selectOne)))
        {
            $AdminData = $this->fetch_array($this->query($selectOne));
            return $AdminData;
        }
        else
        {
            return 0;
        }
    }
    public function InsertPhoto($arg)
	{
		 $service_id=$this->escape($arg['service_id']);
        $sub_service_id_new=$this->escape($arg['sub_service_id']);
		$Service_day=$this->escape($arg['Service_day']);
		
		$Type=$this->escape($arg['Type']);
		$path=$this->escape($arg['path']);
		$added_by=$this->escape($arg['added_by']);
		$insert['service_id'] = $service_id;
        $insert['sub_service_id'] = $sub_service_id_new;
		$insert['Service_day'] = $Service_day;
		if($Type==1)
		{
			$insert['Type'] = '1';
		}
		if($Type==2)
		{
			$insert['Type'] = '2';
		}
		$insert['path'] = $path;
		$insert['added_by']=$added_by;
		$insert['added_date'] = date('Y-m-d H:i:s');
		$RecordId=$this->query_insert('sp_professional_media',$insert);
		 return 'Inserted';
	}

    public function selectAllAdminUser($arg)
    {
        $search_value=$this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
       
        if(isset($filter_name) && !empty($filter_name) && isset($filter_type) && !empty($filter_type))
        {
            $filterpreWhere =" ORDER BY ".$filter_name." ".$filter_type." ";
        }
 
        if(isset($search_value) && !empty($search_value))
        {
            $preWhere .=" AND (email_id like '%".$search_value."%')"; 
        }
        
        $selectOne = "SELECT admin_user_id,name,first_name,middle_name,email_id,password,alternate_email_id,mobile_no,type,landline_no,status,type FROM sp_admin_users WHERE 1 ".$preWhere."  ".$filterpreWhere." ";
        $this->result = $this->query($selectOne);
        
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($selectOne,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                
                $this->resultUsers[]=$val_records;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultUsers))
        {
            $resultArray['data']=$this->resultUsers;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0);
    }

    /**
     *
     * This function is used fo update user details
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function Modifyadmin($arg)
    {
        $adminUserId = $arg['admin_id'];
        $emailId     = $arg['email_id'];
        $selectExist = "SELECT admin_user_id FROM sp_admin_users WHERE email_id = '" . $emailId . "' AND admin_user_id != '" . $adminUserId . "'";
        if ($this->num_of_rows($this->query($selectExist)) == 0) {

            // Get admin details
            $adminDtls = $this->selectAdmin($adminUserId);

            $updateData = array();
            $updateData['name']               = ucwords(strtolower($arg['name']));
			$updateData['first_name']         = ucwords(strtolower($arg['first_name']));
			$updateData['middle_name']        = ucwords(strtolower($arg['middle_name']));
            $updateData['email_id']           = strtolower($emailId);
            $updateData['mobile_no']          = $arg['mobile_no'];
            $updateData['landline_no']        = $arg['landline_no'];
            $updateData['alternate_email_id'] = strtolower($arg['alternate_email_id']);
            $where = "admin_user_id = " . $adminUserId . "";
            $AdminUpdated = $this->query_update('sp_admin_users', $updateData, $where);

            if (!empty($AdminUpdated) && !empty($adminDtls)) {
                $param = array();
                $param['admin_user_id']  = $adminUserId;
                $param['admin_dtls']     = $adminDtls;
                $param['record_data']    = $updateData;
                $this->addAdminActivity($param);
                unset($param);
            }
            return $AdminUpdated;
        }
        else {
           return 0;
        }
    }

    /**
     *
     * This function is used fo update admin password
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function changeAdminPassword($arg)
    {
        $AdminId            = $arg['admin_id'];
        $Admin_Old_Password = md5($arg['admin_old_password']);      
        $Admin_New_Password = md5($arg['admin_new_password']);
        $selectExist = "select password from sp_admin_users where password = '" . $Admin_Old_Password . "' AND admin_user_id ='" . $AdminId . "'";
         if($this->num_of_rows($this->query($selectExist)))
         {
            // Update Password 
            // check new password is different than old password 
            if ($Admin_Old_Password == $Admin_New_Password) {                
               return "SamePassword";
            } else {
                $updateData = array(); 
                $updateData['password'] = $Admin_New_Password;
                $where = "admin_user_id = " . $AdminId . "";
                $PasswordUpdated = $this->query_update('sp_admin_users', $updateData, $where);

                if (!empty($PasswordUpdated)) {
                    $insertActivityArr = array();
                    $insertActivityArr['module_type']   = '2';
                    $insertActivityArr['module_id']     = '1';
                    $insertActivityArr['module_name']   = 'My Profile';
                    $insertActivityArr['event_id']      = '';
                    $insertActivityArr['purpose_id']    = '';
                    $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                    $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                    $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                    $activityDesc = "The password has been changed successfully  by " . $_SESSION['admin_user_name'] . "\r\n";
                    $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                    $this->query_insert('sp_user_activity', $insertActivityArr);
                    unset($insertActivityArr);
                }
                return 'success';
            } 
         }
         else
         {
            return "invalidPassord";  
         }
    }
    public function selectAllServices($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (service_title LIKE '%".$search_value."%' )"; 
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
        $AdminUserSql="SELECT service_id,service_title,status,added_date FROM sp_services WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($AdminUserSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($AdminUserSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {                  
                $this->resultService[]=$val_records;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultService))
        {
            $resultArray['data']=$this->resultService;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    /**
     *
     * This function is used fo add service details
     *
     * @param array $arg
     *
     * @return string $result
     *
     */
    public function InsertServices($arg)
    {
        $service_id       = $this->escape($arg['service_id']);
        $added_by         = $this->escape($arg['added_by']);
        $service_title    = $this->escape($arg['service_title']);
        $is_hd_access     = $this->escape($arg['is_hd_access']);
        $last_modified_by = $this->escape($arg['last_modified_by']);
        
        $select_exist = "select service_id from sp_services where service_id = '" . $service_id . "'";

        if(mysql_num_rows($this->query($select_exist))) {
            // get service details
            $serviceDtls = $this->GetServiceById($arg);
            $update = array();
            $update['service_title']      = $service_title;
            $update['is_hd_access']       = $is_hd_access;
            $update['last_modified_by']   = $last_modified_by;
            $update['last_modified_date'] = date('Y-m-d H:i:s');
            $val_existRecord = $this->fetch_array($this->query($select_exist));
            $where = "service_id ='" . $val_existRecord['service_id'] . "' ";
            $RecordId = $this->query_update('sp_services', $update, $where);
        } else {
            $insert = array();
            $insert['service_title'] = $service_title;
            $insert['is_hd_access']  = $is_hd_access;
            $insert['added_by']      = $added_by;
            $insert['added_date']    = date('Y-m-d H:i:s');
            $insert['status']        = '1';
            $RecordId = $this->query_insert('sp_services', $insert);
        }

        if (!empty($RecordId)) {
            // Add activity details while adding service details
            $param = array();
            $param['service_id']   = $service_id;
            $param['service_dtls'] = $serviceDtls;
            $param['record_data']   = ($insert ? $insert : $update);
            $this->addActivity($param);
            unset($param);
            return (!empty($serviceDtls) ? "Updated" : "Inserted");
        }
    }

    public function GetServiceById($arg)
    {
        $service_id=$this->escape($arg['service_id']);
        $GetOneRecord="SELECT service_id,service_title,status,is_hd_access,added_by,added_date,last_modified_by,last_modified_date FROM sp_services WHERE service_id='".$service_id."'";
        if($this->num_of_rows($this->query($GetOneRecord)))
        {
            $Records = $this->fetch_array($this->query($GetOneRecord));
            
            // Getting Status
            if(!empty($Records['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Delete');
                $Records['statusVal']=$StatusArr[$Records['status']];
            }
            // Getting Added User Name 
            if(!empty($Records['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Records['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Records['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Records['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Records['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Records['last_modified_by']=$ModifiedUser['name'];
            }
            return $Records;
        }
        else 
            return 0;            
    }
    public function GetSubServiceById($arg)
    {
        $sub_service_id=$this->escape($arg['sub_service_id']);
        $GetOneRecord="SELECT sub_service_id,service_id,recommomded_service,cost,tax,UOM,status,added_by,added_date,last_modified_by,last_modified_date FROM sp_sub_services WHERE sub_service_id='".$sub_service_id."'";
        if($this->num_of_rows($this->query($GetOneRecord)))
        {
            $Records = $this->fetch_array($this->query($GetOneRecord));
            
            // Getting Status
            if(!empty($Records['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Delete');
                $Records['statusVal']=$StatusArr[$Records['status']];
            }
            // Getting Added User Name 
            if(!empty($Records['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Records['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Records['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Records['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Records['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Records['last_modified_by']=$ModifiedUser['name'];
            }
            return $Records;
        }
        else 
            return 0; 
    }

    /**
     *
     * This function is used fo update service status
     *
     * @param array $arg
     *
     * @return int 0|1
     *
     */
    public function ChangeStatus($arg)
    {
        $actionval       = $this->escape($arg['actionval']);
        $pre_status      = $this->escape($arg['curr_status']);
        $istrashDelete   = $this->escape($arg['istrashDelete']);
        $service_id      = $this->escape($arg['service_id']);

        if ($actionval == '4') {
            $istrashDelete = 1;
        } else {
            $istrashDelete = 0;
        }

        $ChkExistRec = "SELECT service_id,
            status,
            last_modified_by,
            last_modified_date
        FROM sp_services 
        WHERE service_id = '" . $service_id . "'";

        if ($this->num_of_rows($this->query($ChkExistRec))) {
            $serviceDtls =$this->fetch_array($this->query($ChkExistRec));
            if ($istrashDelete) {
                $selectExist_subServices = "DELETE FROM sp_sub_services WHERE service_id = '" . $service_id . "'"; 
                $this->query($selectExist_subServices);
                $UpdateStatusSql = "DELETE FROM sp_services WHERE service_id = '" . $service_id . "'";                
            } else {
                $UpdateStatusSql = "UPDATE sp_services SET status = '" . $actionval . "', last_modified_by = '" . $_SESSION['admin_user_id'] . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE service_id = '" . $service_id . "'";
            }
            $RecordId = $this->query($UpdateStatusSql);

            if (!empty($RecordId)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '4';
                $insertActivityArr['module_name']   = 'Manage Services';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Service details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "status is change from " . $serviceDtls['status'] . " to " . $actionval . "\r\n";
                $activityDesc .= "modified_by is change from " . $serviceDtls['modified_by'] . " to " . $_SESSION['admin_user_id'] . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $serviceDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
            }
            return 1;
        }
        else {
            return 0;
        }
    }
    public function selectSubServices($arg)
    {        
        $preWhere="";
        $service_id=$this->escape($arg['service_id']);

        $selectAllChild = "SELECT sub_service_id,service_id,recommomded_service,cost,tax,status FROM sp_sub_services WHERE service_id ='".$service_id."'  ORDER BY recommomded_service asc";
        if($this->num_of_rows($this->query($selectAllChild)))
        {
            $AllSubServices = $this->fetch_all_array($selectAllChild);
            return $AllSubServices;
        }
        else 
        {
            return 0;
        }
    }
     /**
     *
     * This function is used fo add sub services details
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function InsertSubServices($arg)
    {
        $service_id          = $this->escape($arg['service_id']);
        $sub_service_id      = $this->escape($arg['sub_service_id']);
        $added_by            = $this->escape($arg['added_by']);
        $cost                = $this->escape($arg['cost']);
        $tax                 = $this->escape($arg['tax']);
        $uom                 = $this->escape($arg['uom']);
        $recommomded_service = $this->escape($arg['recommomded_service']);
        $last_modified_by    = $this->escape($arg['last_modified_by']);
        
        
       $select_exist = "select sub_service_id from sp_sub_services where sub_service_id = '" . $sub_service_id . "'";
    
        if (mysql_num_rows($this->query($select_exist))) {
            $subServiceDtls = $this->GetSubServiceById($arg);
            $update['recommomded_service'] = $recommomded_service;
            $update['tax']                 = $tax;
            $update['UOM']                 = $uom;
            $update['cost']                = $cost;
            $update['service_id']          = $service_id;
            $update['last_modified_by']    = $last_modified_by;
            $update['last_modified_date']  = date('Y-m-d H:i:s');
            
            $val_existRecord = $this->fetch_array($this->query($select_exist));
            $where = "sub_service_id = '" . $val_existRecord['sub_service_id'] . "' ";
            $RecordId = $this->query_update('sp_sub_services',$update,$where); 
        } else {
            $insert['recommomded_service'] = $recommomded_service;
            $insert['tax']                 = $tax;
            $insert['UOM']                 = $uom;
            $insert['cost']                = $cost;
            $insert['service_id']          = $service_id;
            $insert['added_by']            = $added_by;
            $insert['added_date']          = date('Y-m-d H:i:s');
            $insert['status']              = '1';
            $RecordId = $this->query_insert('sp_sub_services',$insert);
        }
        if (!empty($RecordId)) {
            // Add activity details while adding medicine details
            $param = array();
            $param['sub_service_id']   = $sub_service_id;
            $param['sub_service_dtls'] = $subServiceDtls;
            $param['record_data']      = ($insert ? $insert : $update);
            $this->addActivity($param);
            unset($param);
            return (!empty($subServiceDtls) ? "Updated" : "Inserted");
        } else {
            return 0;
        }
    }

    /**
     *
     * This function is used fo update sub service status
     *
     * @param array $arg
     *
     * @return int 0|1
     *
     */
    public function ChangeStatusSubService($arg)
    {
        $actionval          = $this->escape($arg['actionval']);
        $istrashDelete      = $this->escape($arg['istrashDelete']);
        $sub_service_id     = $this->escape($arg['sub_service_id']);
        if ($actionval == '4') {
            $istrashDelete = 1;
        } else {
            $istrashDelete = 0;
            $ChkExistRec = "SELECT sub_service_id,
                status,
                last_modified_by,
                last_modified_date
            FROM sp_sub_services 
            WHERE sub_service_id = '" . $sub_service_id . "'";
        }

        if ($this->num_of_rows($this->query($ChkExistRec))) {
            $subServiceDtls = $this->fetch_array($this->query($ChkExistRec));
            if ($istrashDelete) {
                $UpdateStatusSql = "DELETE FROM sp_sub_services WHERE sub_service_id ='" . $sub_service_id . "'";                
            } else {
                $UpdateStatusSql = "UPDATE sp_sub_services SET status = '" . $actionval . "', last_modified_by = '" . $_SESSION['admin_user_id'] . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE sub_service_id = '" . $sub_service_id . "'";
            }

            $RecordId = $this->query($UpdateStatusSql);

            if (!empty($RecordId) && !empty($subServiceDtls)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '4';
                $insertActivityArr['module_name']   = 'Manage Services';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Medicine details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "status is change from " . $subServiceDtls['status'] . " to " . $actionval . "\r\n";
                $activityDesc .= "modified_by is change from " . $subServiceDtls['modified_by'] . " to " . $_SESSION['admin_user_id'] . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $subServiceDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
                return 1;
            }
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
            $serviceId   = $args['service_id'] ;
            $serviceDtls = $args['service_dtls'];
            $subServiceDtls = $args['sub_service_dtls'];
            $insertData   = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '4';
            $insertActivityArr['module_name']   = 'Manage Services';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = ($subServiceDtls ? "Sub service" : "Service") . "  details " . ( $serviceId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if ((!empty($serviceDtls) || !empty($subServiceDtls)) 
                && !empty($insertData)) {
                $serviceDtls = (empty($serviceDtls) && !empty($subServiceDtls) ? $subServiceDtls : $serviceDtls);
                unset($serviceDtls['status'],
                    $serviceDtls['statusVal'],
                    $serviceDtls['added_by'],
                    $serviceDtls['added_date'],
                    $serviceDtls['last_modified_by']
                );

                // unset variable
                if (!empty($subServiceDtls)) {
                    unset($serviceDtls['sub_service_id']);
                }  else {
                    unset($serviceDtls['service_id']);
                }
                
                $serviceDiff = array_diff_assoc($serviceDtls, $insertData);
                if (!empty($serviceDiff)) {
                    foreach ($serviceDtls AS $key => $valservice) {
                        $activityDesc .= $key . " is change from " . $valservice . " to " . $insertData[$key] . "\r\n";
                    }
                }
            }
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
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
    public function addAdminActivity($args)
    {
        $recordId = 0;
        if (!empty($args)) {
            $adminUserId   = $args['admin_user_id'] ;
            $userDtls      = $args['admin_dtls'];
            $insertData    = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '1';
            $insertActivityArr['module_name']   = 'My Profile';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Admin details " . ( $adminUserId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($userDtls) && !empty($insertData)) {
                unset($userDtls['admin_user_id'],
                    $userDtls['password'],
                    $userDtls['type']
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
