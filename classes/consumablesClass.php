<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class consumablesClass extends AbstractDB 
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
    public function ConsumablesList($arg)
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
        $RecordSql="SELECT consumable_id FROM sp_consumables WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($RecordSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($RecordSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT consumable_id,name,type,manufacture_name,rate,status,added_date FROM sp_consumables WHERE consumable_id='".$val_records['consumable_id']."'";
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
    public function GetConsumablesById($arg)
    {
        $consumable_id=$this->escape($arg['consumable_id']);
        $GetOneRecord="SELECT consumable_id,name,type,manufacture_name,rate,status,added_by,added_date,last_modified_by,last_modified_date FROM sp_consumables WHERE consumable_id='".$consumable_id."'";
        if($this->num_of_rows($this->query($GetOneRecord)))
        {
            $Record = $this->fetch_array($this->query($GetOneRecord));
            // Getting User Type
            if(!empty($Record['type']))
            {
                $TypeArr=array(1=>'Unit',2=>'Non Unit');
                $Record['typeVal']=$TypeArr[$Record['type']];
            }
            // Getting Status
            if(!empty($Record['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Record['statusVal']=$StatusArr[$Record['status']];
            }
            // Getting Added User Name 
            if(!empty($Record['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Record['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Record['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Record['modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Record['modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Record['last_modified_by']=$ModifiedUser['name'];
            }
            return $Record;
        }
        else 
            return 0;            
    }

    /**
     *
     * This function is used fo add consumables details
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function AddConsumables($arg)
    {
        $consumable_id    = $this->escape($arg['consumable_id']);
        $last_modified_by = $arg['last_modified_by'];
        $added_by         = $arg['added_by'];

        $insertData['name']             = $this->escape($arg['name']);
        $insertData['type']             = $this->escape($arg['type']);
        $insertData['manufacture_name'] = $this->escape($arg['manufacture_name']);
        $insertData['rate']             = $this->escape($arg['rate']);
        
        $select_exist = "select consumable_id from sp_consumables where consumable_id = '" . $consumable_id . "'";
        if (mysql_num_rows($this->query($select_exist))) {
            $consumableDtls = $this->GetConsumablesById($arg);
            $insertData['last_modified_by'] = $last_modified_by;
            $insertData['last_modified_date'] = date('Y-m-d H:i:s');
            $insertData['status'] = '1';
            $val_existRecord = $this->fetch_array($this->query($select_exist));
            $where = "consumable_id ='" . $val_existRecord['consumable_id'] . "' ";
            $RecordId = $this->query_update('sp_consumables', $insertData, $where); 
            
        } else {
            $insertData['added_by']   = $added_by;
            $insertData['added_date'] = date('Y-m-d H:i:s');
            $insertData['status']     = '1';
            $RecordId = $this->query_insert('sp_consumables',$insertData);
        }

        if (!empty($RecordId)) {
            // Add activity details while adding consumables details
            $param = array();
            $param['consumable_id']   = $consumable_id;
            $param['consumable_dtls'] = $consumableDtls;
            $param['record_data']   = $insertData;
            $this->addActivity($param);
            unset($param);
            return  (!empty($consumable_id) ? 'Updated' : 'Inserted');
        }
    }

    /**
     *
     * This function is used fo update consumable status
     *
     * @param array $arg
     *
     * @return int 0|1
     *
     */
    public function ChangeStatus($arg)
    {
        $actionval     = $this->escape($arg['actionval']);
        $pre_status    = $this->escape($arg['curr_status']);
        $istrashDelete = $this->escape($arg['istrashDelete']);
        $consumable_id = $this->escape($arg['consumable_id']);

        if ($actionval == '4') {
            $istrashDelete = 1;
        } else {
            $istrashDelete = 0;
        }
        $ChkExistRec = "SELECT consumable_id,
            status,
            last_modified_by,
            last_modified_date
        FROM sp_consumables 
        WHERE consumable_id = '" . $consumable_id . "'";

        if ($this->num_of_rows($this->query($ChkExistRec))) {
            $consumableDtls = $this->fetch_array($this->query($ChkExistRec));
            if ($istrashDelete) {
                $UpdateStatusSql = "DELETE FROM sp_consumables WHERE consumable_id = '" . $consumable_id . "'";                
            } else {
                $UpdateStatusSql = "UPDATE sp_consumables SET status = '" . $actionval . "', last_modified_by = '" . $_SESSION['admin_user_id'] . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE consumable_id = '" . $consumable_id . "'";
            }
            $recordId = $this->query($UpdateStatusSql);

            if (!empty($recordId) && !empty($consumableDtls)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '9';
                $insertActivityArr['module_name']   = 'Manage Consumables';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Consumables details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "status is change from " . $consumableDtls['status'] . " to " . $actionval . "\r\n";
                $activityDesc .= "modified_by is change from " . $consumableDtls['modified_by'] . " to " . $_SESSION['admin_user_id'] . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $consumableDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
            }
            return 1;
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
            $consumableId   = $args['consumable_id'] ;
            $consumableDtls = $args['consumable_dtls'];
            $insertData   = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '9';
            $insertActivityArr['module_name']   = 'Manage Consumables';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Consumable details " . ( $consumableId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($consumableDtls) && !empty($insertData)) {
                unset($consumableDtls['consumable_id'],
                    $consumableDtls['status'],
                    $consumableDtls['statusVal'],
                    $consumableDtls['typeVal'],
                    $consumableDtls['added_by'],
                    $consumableDtls['added_date'],
                    $consumableDtls['last_modified_by']
                );
                $consumableDiff = array_diff_assoc($consumableDtls, $insertData);
                if (!empty($consumableDiff)) {
                    foreach ($consumableDtls AS $key => $valConsumable) {
                        $activityDesc .= $key . " is change from " . $valConsumable . " to " . $insertData[$key] . "\r\n";
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