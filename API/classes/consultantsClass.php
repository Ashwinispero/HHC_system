<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class consultantsClass extends AbstractDB 
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
    public function ConsultantsList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (name LIKE '%".$search_value."%' OR first_name LIKE '%".$search_value."%' OR middle_name LIKE '%".$search_value."%' OR email_id LIKE '%".$search_value."%' OR phone_no LIKE '%".$search_value."%' OR mobile_no LIKE '%".$search_value."%')"; 
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
        $ConsultantsSql="SELECT doctors_consultants_id FROM sp_doctors_consultants WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($ConsultantsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($ConsultantsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT doctors_consultants_id,name,first_name,middle_name ,email_id,phone_no,mobile_no,work_email_id,work_phone_no,work_address,speciality,type,status,isDelStatus,added_date FROM sp_doctors_consultants WHERE doctors_consultants_id='".$val_records['doctors_consultants_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                
                // Check Email Id Present 
                if(!empty($RecordResult['email_id']))
                    $RecordResult['email_id']=$RecordResult['email_id'];
                else 
                   $RecordResult['email_id']="Not Available";
                
                // Check Phone number Present 
                if(!empty($RecordResult['phone_no']))
                    $RecordResult['phone_no']=$RecordResult['phone_no'];
                else 
                   $RecordResult['phone_no']="Not Available";
                
                // Check Phone number Present 
                if(!empty($RecordResult['mobile_no']))
                    $RecordResult['mobile_no']=$RecordResult['mobile_no'];
                else 
                   $RecordResult['mobile_no']="Not Available";
                
                
                // Check Work Email Id Present 
                if(!empty($RecordResult['work_email_id']))
                    $RecordResult['work_email_id']=$RecordResult['work_email_id'];
                else 
                   $RecordResult['work_email_id']="Not Available";
                
                // Check Work Phone Number Present 
                if(!empty($RecordResult['work_phone_no']))
                    $RecordResult['work_phone_no']=$RecordResult['work_phone_no'];
                else 
                   $RecordResult['work_phone_no']="Not Available"; 
                
                 // Check Work Phone Number Present 
                if(!empty($RecordResult['work_address']))
                    $RecordResult['work_address']=$RecordResult['work_address'];
                else 
                   $RecordResult['work_address']="Not Available"; 
                
                
                
                // Getting Consultant Type
                if(!empty($RecordResult['type']))
                {
                    $ConsultantTypeArr=array(1=>'Doctor',2=>'Consultant');
                    $RecordResult['typeVal']=$ConsultantTypeArr[$RecordResult['type']];
                }
                // Getting Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                $this->resultConsultant[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultConsultant))
        {
            $resultArray['data']=$this->resultConsultant;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetConsultantById($arg)
    {
        $doctors_consultants_id=$this->escape($arg['doctors_consultants_id']);
        $GetOneConsultantSql="SELECT doctors_consultants_id,name,first_name,middle_name ,email_id,phone_no,mobile_no,work_email_id,work_phone_no,work_address,speciality,type,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date FROM sp_doctors_consultants WHERE doctors_consultants_id='".$doctors_consultants_id."'";
        if($this->num_of_rows($this->query($GetOneConsultantSql)))
        {
            $Consultant=$this->fetch_array($this->query($GetOneConsultantSql));
            // Getting User Type
            if(!empty($Consultant['type']))
            {
                $ConsultantTypeArr=array(1=>'Doctor',2=>'Consultant');
                $Consultant['typeVal']=$ConsultantTypeArr[$Consultant['type']];
            }
            // Getting Status
            if(!empty($Professional['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Consultant['statusVal']=$StatusArr[$Consultant['status']];
            }
            
            // Getting Added User Name 
            if(!empty($Consultant['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Consultant['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Consultant['added_by']=$AddedUser['name']; 
            }
            // Getting Last Modofied User Name 
            if(!empty($Consultant['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Consultant['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Consultant['last_modified_by']=$ModifiedUser['name'];
            }
            return $Consultant;
        }
        else 
            return 0;            
    }
    public function AddConsultant($arg)
    {
      $doctors_consultants_id=$this->escape($arg['doctors_consultants_id']);
      if(!empty($doctors_consultants_id) && $doctors_consultants_id !='')
          $ChkConsultantSql="SELECT doctors_consultants_id FROM sp_doctors_consultants WHERE mobile_no='".$arg['mobile_no']."' AND doctors_consultants_id !='".$doctors_consultants_id."'";
      else 
          $ChkConsultantSql="SELECT doctors_consultants_id FROM sp_doctors_consultants WHERE mobile_no='".$arg['mobile_no']."'"; 
      
      if($this->num_of_rows($this->query($ChkConsultantSql))==0)
      {
            $insertData = array();
			$insertData['hospital_id']=$this->escape($arg['hospital_id']);
            $insertData['type']=$this->escape($arg['type']);
			$insertData['telephonic_consultation_fees']=$this->escape($arg['telephonic_consultation_fees']);
            $insertData['name']=$this->escape($arg['name']);
            $insertData['first_name']=$this->escape($arg['first_name']);
            $insertData['middle_name']=$this->escape($arg['middle_name']);
            $insertData['email_id']=$this->escape($arg['email_id']);
            $insertData['phone_no']=$this->escape($arg['phone_no']);
            $insertData['mobile_no']=$this->escape($arg['mobile_no']);
            $insertData['work_email_id']=$this->escape($arg['work_email_id']);
            $insertData['work_phone_no']=$this->escape($arg['work_phone_no']);
            $insertData['work_address']=$this->escape($arg['work_address']);
            $insertData['speciality']=$this->escape($arg['speciality']);
			$insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            if(!empty($doctors_consultants_id))
            {
              $where = "doctors_consultants_id ='".$doctors_consultants_id."'";
              $RecordId=$this->query_update('sp_doctors_consultants',$insertData,$where); 
            }
            else 
            {
              $insertData['status']=$this->escape($arg['status']);
              $insertData['added_by']=$this->escape($arg['added_by']);
              $insertData['added_date']=$this->escape($arg['added_date']);
              $RecordId=$this->query_insert('sp_doctors_consultants',$insertData);
            }
            
            if(!empty($RecordId))
                return $RecordId; 
            else
                return 0;
      }
      else 
          return 0;      
    }
    public function ChangeStatus($arg)
    {
        $doctors_consultants_id=$this->escape($arg['doctors_consultants_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkConsultantSql="SELECT doctors_consultants_id FROM sp_doctors_consultants WHERE doctors_consultants_id='".$doctors_consultants_id."'";
        if($this->num_of_rows($this->query($ChkConsultantSql)))
        {
            if($istrashDelete)
            {
                $UpdateStatusSql="DELETE FROM sp_doctors_consultants WHERE doctors_consultants_id='".$doctors_consultants_id."'";
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_doctors_consultants SET status='".$status."',isDelStatus='".$pre_status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE doctors_consultants_id='".$doctors_consultants_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return $RecordId;
        }
        else 
            return 0;
    }
}
//END
?>