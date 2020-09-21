<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class medicineClass extends AbstractDB 
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
    public function MedicineList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (name LIKE '%".$search_value."%' OR type LIKE '%".$search_value."%' OR manufacture_name LIKE '%".$search_value."%' OR rate LIKE '%".$search_value."%')"; 
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
        $MedicineSql="SELECT medicine_id FROM sp_medicines WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($MedicineSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($MedicineSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT medicine_id,name,type,manufacture_name,rate,status,isDelStatus,added_date FROM sp_medicines WHERE medicine_id='".$val_records['medicine_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // Getting Medicine Type
                if(!empty($RecordResult['type']))
                {
                    $MedicineTypeArr=array(1=>'Unit',2=>'Non Unit');
                    $RecordResult['typeVal']=$MedicineTypeArr[$RecordResult['type']];
                }
                // Getting Medicine Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                $this->resultMedicine[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultMedicine))
        {
            $resultArray['data']=$this->resultMedicine;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetMedicineById($arg)
    {
        $medicine_id=$this->escape($arg['medicine_id']);
        $GetOneMedicineSql="SELECT medicine_id,name,type,manufacture_name,rate,status,isDelStatus,added_by,added_date,modified_by,last_modified_date FROM sp_medicines WHERE medicine_id='".$medicine_id."'";
        if($this->num_of_rows($this->query($GetOneMedicineSql)))
        {
            $Medicine = $this->fetch_array($this->query($GetOneMedicineSql));
            // Getting User Type
            if(!empty($Medicine['type']))
            {
                $MedicineTypeArr=array(1=>'Unit',2=>'Non Unit');
                $Medicine['typeVal']=$MedicineTypeArr[$Medicine['type']];
            }
            // Getting Status
            if(!empty($Medicine['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Medicine['statusVal']=$StatusArr[$Medicine['status']];
            }
            // Getting Added User Name 
            if(!empty($Medicine['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Medicine['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Medicine['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Medicine['modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Medicine['modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Medicine['last_modified_by']=$ModifiedUser['name'];
            }
            return $Medicine;
        }
        else 
            return 0;            
    }
    public function AddMedicine($arg)
    {
      $medicine_id=$this->escape($arg['medicine_id']);
      if(!empty($medicine_id) && $medicine_id !='')
          $ChkMedicineSql="SELECT medicine_id FROM sp_medicines WHERE name='".$arg['name']."' AND status !='3' AND medicine_id !='".$medicine_id."'";
      else 
          $ChkMedicineSql="SELECT medicine_id FROM sp_medicines WHERE name='".$arg['name']."' AND status !='3'"; 
      if($this->num_of_rows($this->query($ChkMedicineSql)) == 0)
      {
            $insertData = array();
            $insertData['name']=$this->escape($arg['name']);
            $insertData['type']=$this->escape($arg['type']);
            $insertData['manufacture_name']=$this->escape($arg['manufacture_name']);
            $insertData['rate']=$this->escape($arg['rate']);
            $insertData['modified_by']=$this->escape($arg['modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            if(!empty($medicine_id))
            {
              $where = "medicine_id='".$medicine_id."'";
              $RecordId=$this->query_update('sp_medicines',$insertData,$where); 
            }
            else 
            {
              $insertData['status']=$this->escape($arg['status']);
              $insertData['added_by']=$this->escape($arg['added_by']);
              $insertData['added_date']=$this->escape($arg['added_date']);
              $RecordId=$this->query_insert('sp_medicines',$insertData);
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
        $medicine_id=$this->escape($arg['medicine_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkMedicineSql="SELECT medicine_id FROM sp_medicines WHERE medicine_id='".$medicine_id."'";
        if($this->num_of_rows($this->query($ChkMedicineSql)))
        {
            if($istrashDelete)
            {
                $UpdateStatusSql="DELETE FROM sp_medicines WHERE medicine_id='".$medicine_id."'";
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_medicines SET status='".$status."',isDelStatus='".$pre_status."',modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE medicine_id='".$medicine_id."'";
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