<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class hospitalClass extends AbstractDB 
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
    public function HospitalList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (hospital_name LIKE '%".$search_value."%' OR hospital_short_code LIKE '%".$search_value."%' OR phone_no LIKE '%".$search_value."%' OR website_url LIKE '%".$search_value."%')"; 
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
        $HospitalSql="SELECT hospital_id FROM sp_hospitals WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($HospitalSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($HospitalSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT hospital_id,hospital_name,hospital_short_code,phone_no,website_url,location_id,address,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date FROM sp_hospitals WHERE hospital_id='".$val_records['hospital_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));

                // Getting Hospital Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                
                //Getting Location Details
                if(!empty($RecordResult['location_id']))
                {
                    $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$RecordResult['location_id']."'";
                    $LocationDtls=$this->fetch_array($this->query($LocationSql));
                    $RecordResult['locationNm']=$LocationDtls['location']; 
                    $RecordResult['LocationPinCode']=$LocationDtls['pin_code']; 
                } 
                
                $this->resultHospital[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultHospital))
        {
            $resultArray['data']=$this->resultHospital;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetHospitalById($arg)
    {
        $hospital_id=$this->escape($arg['hospital_id']);
        $GetOneHospitalSql="SELECT hospital_id,hospital_name,hospital_short_code,phone_no,website_url,location_id,address,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date FROM sp_hospitals WHERE hospital_id='".$hospital_id."'";
        if($this->num_of_rows($this->query($GetOneHospitalSql)))
        {
            $Hosptal = $this->fetch_array($this->query($GetOneHospitalSql));
            // Getting Status
            if(!empty($Hosptal['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Hosptal['statusVal']=$StatusArr[$Hosptal['status']];
            }
            // Getting Added User Name 
            if(!empty($Hosptal['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Hosptal['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Hosptal['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Hosptal['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Hosptal['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Hosptal['last_modified_by']=$ModifiedUser['name'];
            }
            return $Hosptal;
        }
        else 
            return 0;            
    }
    public function AddHospital($arg)
    {
      $hospital_id=$this->escape($arg['hospital_id']);
      if(!empty($hospital_id) && $hospital_id !='')
          $ChkHospitalSql="SELECT hospital_id FROM sp_hospitals WHERE hospital_name='".$arg['hospital_name']."' AND hospital_short_code='".$arg['hospital_short_code']."' AND status !='3' AND hospital_id !='".$hospital_id."'";
      else 
          $ChkHospitalSql="SELECT hospital_id FROM sp_hospitals WHERE hospital_name='".$arg['hospital_name']."' AND hospital_short_code='".$arg['hospital_short_code']."' AND status !='3'"; 
      
      if($this->num_of_rows($this->query($ChkHospitalSql)) == 0)
      {
            $insertData = array();
            $insertData['hospital_name']=$this->escape($arg['hospital_name']);
            $insertData['hospital_short_code']=$this->escape($arg['hospital_short_code']);
            $insertData['phone_no']=$this->escape($arg['phone_no']);
            $insertData['website_url']=$this->escape($arg['website_url']);
            $insertData['location_id']=$this->escape($arg['location_id']);
            $insertData['address']=$this->escape($arg['address']);
            $insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            if(!empty($hospital_id))
            {
              $where = "hospital_id='".$hospital_id."'";
              $RecordId=$this->query_update('sp_hospitals',$insertData,$where); 
            }
            else 
            {
              $insertData['status']=$this->escape($arg['status']);
              $insertData['added_by']=$this->escape($arg['added_by']);
              $insertData['added_date']=$this->escape($arg['added_date']);
              $RecordId=$this->query_insert('sp_hospitals',$insertData);
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
        $hospital_id=$this->escape($arg['hospital_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkHospitalSql="SELECT hospital_id FROM sp_hospitals WHERE hospital_id='".$hospital_id."'";
        if($this->num_of_rows($this->query($ChkHospitalSql)))
        {
            if($istrashDelete)
            {
                $UpdateStatusSql="DELETE FROM sp_hospitals WHERE hospital_id='".$hospital_id."'";
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_hospitals SET status='".$status."',isDelStatus='".$pre_status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE hospital_id='".$hospital_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return $RecordId;
        }
        else 
            return 0;
    }
    
    // Hospital IP Address Management 
    
    public function AddHospitalIPS($arg)
    {
        $hospital_id=$this->escape($arg['hospital_id']);
        $hosp_ip_id=$this->escape($arg['hosp_ip_id']);
        $hospital_ip=$this->escape($arg['hospital_ip']);
        
        if(!empty($hosp_ip_id) && $hosp_ip_id !='')
          $ChkHospitalIPSql="SELECT hosp_ip_id FROM sp_hospital_ips WHERE hospital_ip='".$arg['hospital_ip']."' AND hospital_id='".$arg['hospital_id']."' AND status !='3' AND hosp_ip_id !='".$hosp_ip_id."'";
      else 
          $ChkHospitalIPSql="SELECT hosp_ip_id FROM sp_hospital_ips WHERE hospital_ip='".$arg['hospital_ip']."' AND hospital_id='".$arg['hospital_id']."' AND status !='3'"; 
      
      if($this->num_of_rows($this->query($ChkHospitalIPSql)) == 0)
      {
        $insertData = array();
        $insertData['hospital_id']=$this->escape($arg['hospital_id']);
        $insertData['hospital_ip']=$this->escape($arg['hospital_ip']);
        $insertData['last_modified_by']=$_SESSION['admin_user_id'];
        $insertData['last_modified_date']=date('Y-m-d H:i:s');

        if(!empty($hosp_ip_id))
        {
          $where = "hosp_ip_id='".$hosp_ip_id."'";
          $RecordId=$this->query_update('sp_hospital_ips',$insertData,$where); 
        }
        else 
        {
            $insertData['status']='1';
            $insertData['added_by']=$_SESSION['admin_user_id'];
            $insertData['added_date']=date('Y-m-d H:i:s');
            $RecordId=$this->query_insert('sp_hospital_ips',$insertData);
        }
        if(!empty($RecordId))
            return $RecordId; 
        else
            return 0;
        
      }
      else 
          return 0;   
    }
    
    public function GetIPSByHospitalId($arg)
    {
        $hospital_id=$this->escape($arg['hospital_id']);
        $GetIPSByHospitalSql="SELECT hosp_ip_id,hospital_id,hospital_ip,status,added_by,added_date,last_modified_by,last_modified_date FROM sp_hospital_ips WHERE hospital_id='".$hospital_id."'";
        if($this->num_of_rows($this->query($GetIPSByHospitalSql)))
        {
            $Hosptal_all_IPS = $this->fetch_all_array($GetIPSByHospitalSql);
            $HosptalIPS=array();
            foreach($Hosptal_all_IPS as $key=>$valIPS)
            {
                // Getting Status
                if(!empty($valIPS['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $valIPS['statusVal']=$StatusArr[$valIPS['status']];
                }
                // Getting Added User Name 
                if(!empty($valIPS['added_by']))
                {
                   $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$valIPS['added_by']."'";
                   $AddedUser=$this->fetch_array($this->query($AddedUserSql));
                   $valIPS['added_by']=$AddedUser['name']; 
                }
                // Getting Last Mpdofied User Name 
                if(!empty($valIPS['last_modified_by']))
                {
                   $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$valIPS['last_modified_by']."'";
                   $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
                   $valIPS['last_modified_by']=$ModifiedUser['name'];
                }
                
                $HosptalIPS[]=$valIPS;
            }
            if(!empty($HosptalIPS))
               return $HosptalIPS;
            
            else
                return 0;
        }
        else 
            return 0;  
    }
    
    public function RemoveIP($arg)
    {
        $hosp_ip_id=$this->escape($arg['hosp_ip_id']);
        if(!empty($hosp_ip_id))
        {
            $DelIpSql="DELETE FROM sp_hospital_ips WHERE hosp_ip_id='".$hosp_ip_id."'";
            $DelIp=$this->query($DelIpSql);
            if(!empty($DelIp))
                return 1;
            else 
                return 0;
        }
        else 
            return 0;
    }
}
//END
?>