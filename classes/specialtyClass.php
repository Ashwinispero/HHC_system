<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class specialtyClass extends AbstractDB 
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
    public function SpecialtyList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (abbreviation LIKE '%".$search_value."%')"; 
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
        $SpecialtySql="SELECT specialty_id FROM sp_specialty WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($SpecialtySql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($SpecialtySql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT specialty_id,abbreviation,status,isDelStatus,added_date FROM sp_specialty WHERE specialty_id='".$val_records['specialty_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // Getting Specialty Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                $this->resultSpecialty[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultSpecialty))
        {
            $resultArray['data']=$this->resultSpecialty;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetSpecialtyById($arg)
    {
        $specialty_id=$this->escape($arg['specialty_id']);
        $GetOneSpecialtySql="SELECT specialty_id,abbreviation,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date FROM sp_specialty WHERE specialty_id='".$specialty_id."'";
        if($this->num_of_rows($this->query($GetOneSpecialtySql)))
        {
            $Specialty=$this->fetch_array($this->query($GetOneSpecialtySql));
            // Getting Status
            if(!empty($Specialty['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Specialty['statusVal']=$StatusArr[$Specialty['status']];
            }
            // Getting Added User Name 
            if(!empty($Specialty['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Specialty['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Specialty['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Medicine['modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Specialty['modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Specialty['last_modified_by']=$ModifiedUser['name'];
            }
            return $Specialty;
        }
        else 
            return 0;            
    }
    public function AddSpecialty($arg)
    {
      $specialty_id=$this->escape($arg['specialty_id']);
      if(!empty($specialty_id) && $specialty_id !='')
          $ChkSpecialtySql="SELECT specialty_id FROM sp_specialty WHERE name='".$arg['name']."' AND status !='3' AND specialty_id !='".$specialty_id."'";
      else 
          $ChkSpecialtySql="SELECT specialty_id FROM sp_specialty WHERE name='".$arg['name']."' AND status !='3'"; 
      if($this->num_of_rows($this->query($ChkSpecialtySql)) == 0)
      {
            $insertData = array();
            $insertData['abbreviation']=$this->escape($arg['abbreviation']);
            $insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            if(!empty($specialty_id))
            {
              $where = "specialty_id='".$specialty_id."'";
              $RecordId=$this->query_update('sp_specialty',$insertData,$where); 
            }
            else 
            {
              $insertData['status']=$this->escape($arg['status']);
              $insertData['added_by']=$this->escape($arg['added_by']);
              $insertData['added_date']=$this->escape($arg['added_date']);
              $RecordId=$this->query_insert('sp_specialty',$insertData);
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
        $specialty_id=$this->escape($arg['specialty_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkSpecialtySql="SELECT specialty_id FROM sp_specialty WHERE specialty_id='".$specialty_id."'";
        if($this->num_of_rows($this->query($ChkSpecialtySql)))
        {
            if($istrashDelete)
            {
                $UpdateStatusSql="DELETE FROM sp_specialty WHERE specialty_id='".$specialty_id."'";
            }
            else 
            {
               $UpdateStatusSql="UPDATE sp_specialty SET status='".$status."',isDelStatus='".$pre_status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE specialty_id='".$specialty_id."'";
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