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
    public function Modifyadmin($arg)
    {
        $selectExist = "select admin_user_id from sp_admin_users where email_id ='".$arg['email_id']."' AND admin_user_id !='".$arg['admin_id']."'";
        if($this->num_of_rows($this->query($selectExist)) == 0)
        {
            $updateData = array();
            $updateData['name'] = ucwords(strtolower($arg['name']));
			$updateData['first_name'] = ucwords(strtolower($arg['first_name']));
			$updateData['middle_name'] = ucwords(strtolower($arg['middle_name']));
            $updateData['email_id'] = strtolower($arg['email_id']);
            $updateData['mobile_no'] = $arg['mobile_no'];
            $updateData['landline_no'] = $arg['landline_no'];
            $updateData['alternate_email_id'] = strtolower($arg['alternate_email_id']);
            $where ="admin_user_id=".$arg['admin_id']."";
            $AdminUpdated = $this->query_update('sp_admin_users',$updateData,$where);
            return $AdminUpdated;
        }
        else
           return 0;
    }
    public function changeAdminPassword($arg)
    {
        $AdminId = $arg['admin_id'];
        $Admin_Old_Password = md5($arg['admin_old_password']);      
        $Admin_New_Password = md5($arg['admin_new_password']);
        $selectExist = "select password from sp_admin_users where password = '".$Admin_Old_Password."' AND admin_user_id ='".$AdminId."'";
         if($this->num_of_rows($this->query($selectExist)))
         {
            // Update Password 
            // check new password is different than old password 
            if($Admin_Old_Password == $Admin_New_Password)
            {                
               return "SamePassword";
            }
            else 
            {
                $updateData = array(); 
                $updateData['password'] = $Admin_New_Password;
                $where ="admin_user_id=".$AdminId."";
                $PasswordUpdated = $this->query_update('sp_admin_users',$updateData,$where);
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
    public function InsertServices($arg)
    {
        $service_id=$this->escape($arg['service_id']);
        $added_by=$this->escape($arg['added_by']);
        $service_title=$this->escape($arg['service_title']);
        $is_hd_access=$this->escape($arg['is_hd_access']);
        $last_modified_by=$this->escape($arg['last_modified_by']);
        
        $select_exist = "select service_id from sp_services where service_id = '".$service_id."'";
        if(mysql_num_rows($this->query($select_exist)))
        {
            $update['service_title'] = $service_title;
            $update['is_hd_access'] = $is_hd_access;
            $update['last_modified_by'] = $last_modified_by;
            $update['last_modified_date'] = date('Y-m-d H:i:s');
            $val_existRecord = $this->fetch_array($this->query($select_exist));
            $where = "service_id ='".$val_existRecord['service_id']."' ";
            $RecordId=$this->query_update('sp_services',$update,$where); 
            return 'Updated';
        }
        else
        {
            $insert['service_title'] = $service_title;
            $insert['is_hd_access'] = $is_hd_access;
            $insert['added_by'] = $added_by;
            $insert['added_date'] = date('Y-m-d H:i:s');
            $insert['status'] = '1';
            $RecordId=$this->query_insert('sp_services',$insert);
            return 'Inserted';
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
        $GetOneRecord="SELECT sub_service_id,service_id,recommomded_service,cost,tax,status,added_by,added_date,last_modified_by,last_modified_date FROM sp_sub_services WHERE sub_service_id='".$sub_service_id."'";
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
    public function ChangeStatus($arg)
    {
        $actionval=$this->escape($arg['actionval']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $service_id=$this->escape($arg['service_id']);
        if($actionval == '4')
            $istrashDelete = 1;
        else
            $istrashDelete = 0;
        $ChkExistRec="SELECT service_id FROM sp_services WHERE service_id='".$service_id."'";
        if($this->num_of_rows($this->query($ChkExistRec)))
        {
            if($istrashDelete)
            {
                $selectExist_subServices = "delete from sp_sub_services where service_id='".$service_id."'"; 
                $RecordDeleted=$this->query($selectExist_subServices);
                $UpdateStatusSql="DELETE FROM sp_services WHERE service_id='".$service_id."'";                
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_services SET status='".$actionval."',last_modified_by='".$_SESSION['admin_user_id']."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE service_id='".$service_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return 1;
        }
        else 
            return 0;
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
    public function InsertSubServices($arg)
    {
        $service_id=$this->escape($arg['service_id']);
        $sub_service_id=$this->escape($arg['sub_service_id']);
        $added_by=$this->escape($arg['added_by']);
        $cost=$this->escape($arg['cost']);
        $tax=$this->escape($arg['tax']);
        $recommomded_service=$this->escape($arg['recommomded_service']);
        $last_modified_by=$this->escape($arg['last_modified_by']);
        
        
       $select_exist = "select sub_service_id from sp_sub_services where sub_service_id = '".$sub_service_id."'";
        if(mysql_num_rows($this->query($select_exist)))
        {
            $update['recommomded_service'] = $recommomded_service;
            $update['tax'] = $tax;
            $update['cost'] = $cost;
            $update['service_id'] = $service_id;
            $update['last_modified_by'] = $last_modified_by;
            $update['last_modified_date'] = date('Y-m-d H:i:s');
            
            $val_existRecord = $this->fetch_array($this->query($select_exist));
            $where = "sub_service_id ='".$val_existRecord['sub_service_id']."' ";
            $RecordId=$this->query_update('sp_sub_services',$update,$where); 
            return 'Updated';
        }
        else
        {
            $insert['recommomded_service'] = $recommomded_service;
            $insert['tax'] = $tax;
            $insert['cost'] = $cost;
            $insert['service_id'] = $service_id;
            $insert['added_by'] = $added_by;
            $insert['added_date'] = date('Y-m-d H:i:s');
            $insert['status'] = '1';
            $RecordId=$this->query_insert('sp_sub_services',$insert);
            return 'Inserted';
        }
    }
    public function ChangeStatusSubService($arg)
    {
        $actionval=$this->escape($arg['actionval']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $sub_service_id=$this->escape($arg['sub_service_id']);
        if($actionval == '4')
            $istrashDelete = 1;
        else
            $istrashDelete = 0;
        $ChkExistRec="SELECT sub_service_id FROM sp_sub_services WHERE sub_service_id='".$sub_service_id."'";
        if($this->num_of_rows($this->query($ChkExistRec)))
        {
            if($istrashDelete)
            {
                $UpdateStatusSql="DELETE FROM sp_sub_services WHERE sub_service_id='".$sub_service_id."'";                
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_sub_services SET status='".$actionval."',last_modified_by='".$_SESSION['admin_user_id']."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE sub_service_id='".$sub_service_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return 1;
        }
        else 
            return 0;
    }
}
//END
?>
