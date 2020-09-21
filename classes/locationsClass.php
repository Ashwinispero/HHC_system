<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class locationsClass extends AbstractDB 
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
    public function LocationsList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (location LIKE '%".$search_value."%' OR pin_code LIKE '%".$search_value."%')"; 
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
        $LocationsSql="SELECT location_id FROM sp_locations WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($LocationsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($LocationsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT location_id,location,pin_code,status,isDelStatus,added_date FROM sp_locations WHERE location_id='".$val_records['location_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // Getting Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                $this->resultLocations[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultLocations))
        {
            $resultArray['data']=$this->resultLocations;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetLocationById($arg)
    {
        $location_id=$this->escape($arg['location_id']);
        $GetOneLocationSql="SELECT location_id,location,pin_code,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date FROM sp_locations WHERE location_id='".$location_id."'";
        if($this->num_of_rows($this->query($GetOneLocationSql)))
        {
            $Location=$this->fetch_array($this->query($GetOneLocationSql));
            
            // Getting Status
            if(!empty($Location['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Location['statusVal']=$StatusArr[$Location['status']];
            }
            
            // Getting Added User Name 
            if(!empty($Location['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Location['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Location['added_by']=$AddedUser['name']; 
            }
            // Getting Last Modofied User Name 
            if(!empty($Location['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Location['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Location['last_modified_by']=$ModifiedUser['name'];
            }
            return $Location;
        }
        else 
            return 0;            
    }
    public function AddLocation($arg)
    {
      $location_id=$this->escape($arg['location_id']);
      if(!empty($location_id) && $location_id !='')
          $ChkLocationSql="SELECT location_id FROM sp_locations WHERE location='".$arg['location']."' AND pin_code='".$arg['pin_code']."' AND location_id !='".$location_id."'";
      else 
          $ChkLocationSql="SELECT location_id FROM sp_locations WHERE location='".$arg['location']."' AND pin_code='".$arg['pin_code']."'"; 
      
      if($this->num_of_rows($this->query($ChkLocationSql))==0)
      {
            $insertData = array();
            $insertData['location']=$this->escape($arg['location']);
            $insertData['pin_code']=$this->escape($arg['pin_code']);
            $insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            if(!empty($location_id))
            {
              $where = "location_id ='".$location_id."'";
              $RecordId=$this->query_update('sp_locations',$insertData,$where); 
            }
            else 
            {
              $insertData['status']=$this->escape($arg['status']);
              $insertData['added_by']=$this->escape($arg['added_by']);
              $insertData['added_date']=$this->escape($arg['added_date']);
              $RecordId=$this->query_insert('sp_locations',$insertData);
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
        $location_id=$this->escape($arg['location_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkLocationSql="SELECT location_id FROM sp_locations WHERE location_id='".$location_id."'";
        if($this->num_of_rows($this->query($ChkLocationSql)))
        {
            if($istrashDelete)
            {
                $UpdateStatusSql="DELETE FROM sp_locations WHERE location_id='".$location_id."'";
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_locations SET status='".$status."',isDelStatus='".$pre_status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE location_id='".$location_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return $RecordId;
        }
        else 
            return 0;
    }
    /*
    public function SubLocationsList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $location_id=$this->escape($arg['location_id']);
        $isTrash=$this->escape($arg['isTrash']);
        
        if(!empty($location_id) && $location_id !='null')
        {
           $preWhere="AND location_id='".$location_id."'"; 
        }
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere .="AND (location LIKE '%".$search_value."%' OR pin_code LIKE '%".$search_value."%')"; 
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
        $SubLocationsSql="SELECT sub_location_id FROM sp_sub_locations WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($SubLocationsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($SubLocationsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT sub_location_id,location_id,location_name,status,isDelStatus,added_date FROM sp_sub_locations WHERE sub_location_id='".$val_records['sub_location_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // Getting Location Details 
                if(!empty($RecordResult['location_id']))
                {
                   $LocationSql="SELECT location,pin_code FROM sp_locations WHERE location_id='".$RecordResult['location_id']."'";
                   $Location=$this->fetch_array($this->query($LocationSql));
                   $RecordResult['location']=$Location['location']; 
                }
                // Getting Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                
                
                
                $this->resultSubLocations[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultSubLocations))
        {
            $resultArray['data']=$this->resultSubLocations;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetSubLocationById($arg)
    {
        $sub_location_id=$this->escape($arg['sub_location_id']);
        $GetOneSubLocationSql="SELECT sub_location_id,location_id,location_name,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date FROM sp_sub_locations WHERE sub_location_id='".$sub_location_id."'";
        if($this->num_of_rows($this->query($GetOneSubLocationSql)))
        {
            $SubLocation=$this->fetch_array($this->query($GetOneSubLocationSql));
            
            // Getting Location Details 
            if(!empty($SubLocation['location_id']))
            {
               $LocationSql="SELECT location,pin_code FROM sp_locations WHERE location_id='".$SubLocation['location_id']."'";
               $Location=$this->fetch_array($this->query($LocationSql));
               $SubLocation['location']=$Location['location']; 
            }
            
            // Getting Status
            if(!empty($SubLocation['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $SubLocation['statusVal']=$StatusArr[$SubLocation['status']];
            }
            
            // Getting Added User Name 
            if(!empty($SubLocation['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$SubLocation['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $SubLocation['added_by']=$AddedUser['name']; 
            }
            // Getting Last Modofied User Name 
            if(!empty($SubLocation['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$SubLocation['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $SubLocation['last_modified_by']=$ModifiedUser['name'];
            }
            return $SubLocation;
        }
        else 
            return 0;    
    }
    public function AddSubLocation($arg)
    {
      $sub_location_id=$this->escape($arg['sub_location_id']);
      if(!empty($sub_location_id) && $sub_location_id !='')
          $ChkSubLocationSql="SELECT sub_location_id FROM sp_sub_locations WHERE location_name='".$arg['location_name']."' AND location_id='".$arg['location_id']."' AND sub_location_id !='".$sub_location_id."'";
      else 
          $ChkSubLocationSql="SELECT sub_location_id FROM sp_sub_locations WHERE location_name='".$arg['location_name']."' AND location_id='".$arg['location_id']."'"; 
      
      if($this->num_of_rows($this->query($ChkSubLocationSql))==0)
      {
            $insertData = array();
            $insertData['location_id']=$this->escape($arg['location_id']);
            $insertData['location_name']=$this->escape($arg['location_name']);
            $insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            if(!empty($sub_location_id))
            {
              $where = "sub_location_id='".$sub_location_id."'";
              $RecordId=$this->query_update('sp_sub_locations',$insertData,$where); 
            }
            else 
            {
              $insertData['status']=$this->escape($arg['status']);
              $insertData['added_by']=$this->escape($arg['added_by']);
              $insertData['added_date']=$this->escape($arg['added_date']);
              $RecordId=$this->query_insert('sp_sub_locations',$insertData);
            }
            
            if(!empty($RecordId))
                return $RecordId; 
            else
                return 0;
      }
      else 
          return 0;      
    }
    public function ChangeSubLocationStatus($arg)
    {
        $sub_location_id=$this->escape($arg['sub_location_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkSubLocationSql="SELECT sub_location_id FROM sp_sub_locations WHERE sub_location_id='".$sub_location_id."'";
        if($this->num_of_rows($this->query($ChkSubLocationSql)))
        {
            if($istrashDelete)
            {
                $UpdateStatusSql="DELETE FROM sp_sub_locations WHERE sub_location_id='".$sub_location_id."'";
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_sub_locations SET status='".$status."',isDelStatus='".$pre_status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE sub_location_id='".$sub_location_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return $RecordId;
        }
        else 
            return 0;
    }
     */
}
//END
?>