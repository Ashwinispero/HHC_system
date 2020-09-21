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
    /**
     *
     * This function is used fo add hospital details
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function AddHospital($arg)
    {
        $hospital_id = $this->escape($arg['hospital_id']);

        if (!empty($hospital_id) && $hospital_id != '') {
            // Get hospital details
            $hospitalDtls = $this->GetHospitalById($arg);

            $ChkHospitalSql = "SELECT hospital_id FROM sp_hospitals WHERE hospital_name = '" . $arg['hospital_name'] . "' AND hospital_short_code = '" . $arg['hospital_short_code'] . "' AND status != '3' AND hospital_id != '" . $hospital_id . "'";
        } else {
            $ChkHospitalSql = "SELECT hospital_id FROM sp_hospitals WHERE hospital_name = '" . $arg['hospital_name'] . "' AND hospital_short_code = '" . $arg['hospital_short_code'] . "' AND status != '3'"; 
        }

        if ($this->num_of_rows($this->query($ChkHospitalSql)) == 0) {
            $insertData = array();

            $insertData['hospital_name']       = $this->escape($arg['hospital_name']);
            $insertData['hospital_short_code'] = $this->escape($arg['hospital_short_code']);
            $insertData['phone_no']           = $this->escape($arg['phone_no']);
            $insertData['website_url']        = $this->escape($arg['website_url']);
            $insertData['location_id']        = $this->escape($arg['location_id']);
            $insertData['address']            = $this->escape($arg['address']);
            $insertData['last_modified_by']   = $this->escape($arg['last_modified_by']);
            $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);

            if (!empty($hospital_id)) {
                $where = "hospital_id = '" . $hospital_id . "'";
                $RecordId = $this->query_update('sp_hospitals', $insertData, $where); 
            } else {
                $insertData['status']     = $this->escape($arg['status']);
                $insertData['added_by']   = $this->escape($arg['added_by']);
                $insertData['added_date'] = $this->escape($arg['added_date']);
                $RecordId = $this->query_insert('sp_hospitals',$insertData);
            }
            
            if (!empty($RecordId)) {
                // Add activity details while adding medicine details
                $param = array();
                $param['hospital_id']   = $hospital_id;
                $param['hospital_dtls'] = $hospitalDtls;
                $param['record_data']   = $insertData;
                $this->addActivity($param);
                unset($param);
                return $RecordId; 
            } else {
                return 0;
            }
        }
        else { 
            return 0;
        }
    }

    /**
     *
     * This function is used fo update hospital status
     *
     * @param array $arg
     *
     * @return int 0|1
     *
     */
    public function ChangeStatus($arg)
    {
        $hospital_id = $this->escape($arg['hospital_id']);
        $status      = $this->escape($arg['status']);
        $pre_status  = $this->escape($arg['curr_status']);
        $istrashDelete = $this->escape($arg['istrashDelete']);
        $login_user_id = $this->escape($arg['login_user_id']);
        $ChkHospitalSql = "SELECT hospital_id,
            status,
            isDelStatus,
            last_modified_by,
            last_modified_date
        FROM sp_hospitals 
        WHERE hospital_id = '" . $hospital_id . "'";

        if ($this->num_of_rows($this->query($ChkHospitalSql))) {
            if ($istrashDelete) {
                $hospitalDtls = $this->fetch_array($this->query($ChkHospitalSql));
                $UpdateStatusSql = "DELETE FROM sp_hospitals WHERE hospital_id = '" . $hospital_id . "'";
            } else {
                $UpdateStatusSql = "UPDATE sp_hospitals SET status = '" . $status . "', isDelStatus = '" . $pre_status . "', last_modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE hospital_id = '" . $hospital_id . "'";
            }
            $RecordId = $this->query($UpdateStatusSql);

            if (!empty($RecordId) && !empty($hospitalDtls)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '14';
                $insertActivityArr['module_name']   = 'Manage Hospitals';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Hospital details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "status is change from " . $hospitalDtls['status'] . " to " . $status . "\r\n";
                $activityDesc .= "isDelStatus is change from " . $hospitalDtls['isDelStatus'] . " to " . $pre_status . "\r\n";
                $activityDesc .= "modified_by is change from " . $hospitalDtls['modified_by'] . " to " . $login_user_id . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $hospitalDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

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
     * This function is used fo Hospital IP Address Management
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function AddHospitalIPS($arg)
    {
        $hospital_id  = $this->escape($arg['hospital_id']);
        $hosp_ip_id   = $this->escape($arg['hosp_ip_id']);
        $hospital_ip  = $this->escape($arg['hospital_ip']);

        if (!empty($hosp_ip_id) && $hosp_ip_id != '') {
            // Get hospital IP Address details
            $hospitalIPDtls = $this->GetIPSByHospitalId($arg);
            $ChkHospitalIPSql = "SELECT hosp_ip_id FROM sp_hospital_ips WHERE hospital_ip = '" . $arg['hospital_ip'] . "' AND hospital_id = '" . $arg['hospital_id'] . "' AND status != '3' AND hosp_ip_id != '" . $hosp_ip_id . "'";
        } else {
            $ChkHospitalIPSql = "SELECT hosp_ip_id FROM sp_hospital_ips WHERE hospital_ip = '" . $arg['hospital_ip'] . "' AND hospital_id = '" . $arg['hospital_id'] . "' AND status != '3'"; 
        }

        if ($this->num_of_rows($this->query($ChkHospitalIPSql)) == 0) {
            $insertData = array();
            $insertData['hospital_id']         = $this->escape($arg['hospital_id']);
            $insertData['hospital_ip']         = $this->escape($arg['hospital_ip']);
            $insertData['last_modified_by']    = $_SESSION['admin_user_id'];
            $insertData['last_modified_date']  = date('Y-m-d H:i:s');

            if(!empty($hosp_ip_id)) {
                $where = "hosp_ip_id = '" . $hosp_ip_id . "'";
                $RecordId = $this->query_update('sp_hospital_ips', $insertData, $where); 
            }
            else 
            {
                $insertData['status']     = '1';
                $insertData['added_by']   = $_SESSION['admin_user_id'];
                $insertData['added_date'] = date('Y-m-d H:i:s');
                $RecordId = $this->query_insert('sp_hospital_ips', $insertData);
            }
            if(!empty($RecordId)) {
                // Add activity details while adding medicine details
                $param = array();
                $param['hospital_id']      = $hospital_id;
                $param['hosp_ip_id']       = $hosp_ip_id;
                $param['hospital_ip_dtls'] = $hospitalIPDtls;
                $param['record_data']   = $insertData;
                $this->addActivity($param);
                unset($param);
                return $RecordId;
            }
            else {
                return 0;
            }
        }
        else {
            return 0;
        }  
    }
    
    /**
     *
     * This function is used for get hospital IP Address
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function GetIPSByHospitalId($arg)
    {
        $hospital_id = $this->escape($arg['hospital_id']);
        $GetIPSByHospitalSql = "SELECT hosp_ip_id,
            hospital_id,
            hospital_ip,
            status,
            added_by,
            added_date,
            last_modified_by,
            last_modified_date
        FROM sp_hospital_ips 
        WHERE hospital_id = '" . $hospital_id . "'";

        if ($this->num_of_rows($this->query($GetIPSByHospitalSql))) {
            $Hosptal_all_IPS = $this->fetch_all_array($GetIPSByHospitalSql);
            $HosptalIPS = array();

            foreach ($Hosptal_all_IPS AS $valIPS) {
                // Getting Status
                if (!empty($valIPS['status'])) {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $valIPS['statusVal']=$StatusArr[$valIPS['status']];
                }
                // Getting Added User Name 
                if (!empty($valIPS['added_by'])) {
                   $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$valIPS['added_by']."'";
                   $AddedUser=$this->fetch_array($this->query($AddedUserSql));
                   $valIPS['added_by']=$AddedUser['name']; 
                }
                // Getting Last Mpdofied User Name 
                if (!empty($valIPS['last_modified_by'])) {
                   $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$valIPS['last_modified_by']."'";
                   $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
                   $valIPS['last_modified_by']=$ModifiedUser['name'];
                }
                
                $HosptalIPS[] = $valIPS;
            }
            if (!empty($HosptalIPS)) {
               return $HosptalIPS;
            } else {
                return 0;
            }
        }
        else {
            return 0;
        }
    }
    
    /**
     *
     * This function is used for remove hospital IP Address
     *
     * @param array $arg
     *
     * @return int 1|0
     *
     */
    public function RemoveIP($arg)
    {
        $hosp_ip_id = $this->escape($arg['hosp_ip_id']);
        if (!empty($hosp_ip_id)) {
            // Get hospital IP Addres detail
            $hospitalIpAddressDtls = $this->getHospitalIpAddressById($hosp_ip_id);
            $DelIpSql = "DELETE FROM sp_hospital_ips WHERE hosp_ip_id = '" . $hosp_ip_id . "'";
            $DelIp = $this->query($DelIpSql);
            if (!empty($DelIp)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '14';
                $insertActivityArr['module_name']   = 'Manage Hospitals';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Hospital IP Address " . $hospitalIpAddressDtls['hospital_ip'] . " from " . $hospitalIpAddressDtls['hospital_name'] . " removed successfully by " . $_SESSION['admin_user_name'] . "\r\n";
                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
                return 1;
            } else {
                return 0;
            }
        }
        else {
            return 0;
        }
    }

    /**
     *
     * This function is used for get hospital ip address by id
     *
     * @param int $hospitalIpId
     *
     * @return array $result
     */
    public function getHospitalIpAddressById($hospitalIpId)
    {
        $result = array();
        if (!empty($hospitalIpId)) {
            $getHospitalIpSql = "SELECT t1.hosp_ip_id,
                t1.hospital_id,
                t1.hospital_ip,
                t1.status,
                t2.hospital_name,
                t2.hospital_short_code,
                t1.added_by,
                t1.added_date,
                t1.last_modified_by,
                t1.last_modified_date
            FROM sp_hospital_ips AS t1 
            INNER JOIN sp_hospitals AS t2
                ON t1.hospital_id = t2.hospital_id
            WHERE t1.hosp_ip_id = '" . $hospitalIpId . "'";

            if ($this->num_of_rows($this->query($getHospitalIpSql))) {
                $result = $this->fetch_array($this->query($getHospitalIpSql));
            }
        }
        return $result;
    }

    /**
     *
     * This function is used for add content details
     *
     * @param $arg
     *
     * @return $recordId
     *
     */
    public function addContent($arg)
    {
        $contentId    = $this->escape($arg['content_id']);
        $hospitalId   = $this->escape($arg['hospital_id']);
        $contentType  = $this->escape($arg['content_type']);
        $contentValue = $arg['content_value'];
        
        if (!empty($contentId) && $contentId != '') {
          // Get content
          $contentDtls = $this->getContentById($arg);
          $chkContentSql = "SELECT content_id FROM sp_content WHERE content_type = '" . $contentType . "' AND hospital_id = '" . $hospitalId . "' AND status != '3' AND content_id != '" . $contentId . "'";
        } else {
          $chkContentSql = "SELECT content_id FROM sp_content WHERE content_type = '" . $contentType . "' AND hospital_id = '" . $hospitalId . "' AND status != '3'";
        }

        if ($this->num_of_rows($this->query($chkContentSql)) == 0) {
            $insertData = array();
            $insertData['hospital_id']      = $this->escape($arg['hospital_id']);
            $insertData['content_type']     = $this->escape($arg['content_type']);
            $insertData['content_value']    = $arg['content_value'];
            $insertData['modified_user_id'] = $_SESSION['admin_user_id'];
            $insertData['modified_date']    = date('Y-m-d H:i:s');

            if (!empty($contentId)) {
                $where = "content_id = '" . $contentId . "'";
                $recordId = $this->query_update('sp_content', $insertData, $where); 
            }
            else {
                $insertData['status']          = '1';
                $insertData['added_user_id']   = $_SESSION['admin_user_id'];
                $insertData['added_date']      = date('Y-m-d H:i:s');
                $recordId = $this->query_insert('sp_content', $insertData);
            }

            if (!empty($recordId)) {
                // Add activity details while adding medicine details
                $param = array();
                $param['hospital_id']      = $hospitalId;
                $param['content_id']       = $contentId;
                $param['content_type']     = $contentType;
                $param['content_dtls']     = $contentDtls;
                $param['record_data']      = $insertData;
                $this->addContentActivity($param);
                unset($param);
                return $recordId;
            } else {
                return 0;
            } 
        }
        else {
            return 0; 
        }
    }

    /**
     *
     * Get content by hospital_id / content_type / content_id
     *
     * @param array $arg
     *
     * @return array $result
     *
     */
    public function getContentById($arg)
    {
        if (!empty($arg)) {
            $hospitalId  = $this->escape($arg['hospital_id']);
            $contentId   = $this->escape($arg['content_id']);
            $contentType = $this->escape($arg['content_type']);

            $preWhere = "";

            if (!empty($hospitalId)) {
                $preWhere .= " AND hospital_id = '" . $hospitalId . "' ";
            }

            if (!empty($contentId)) {
                $preWhere .= " AND content_id = '" . $contentId . "' ";
            }

            if (!empty($contentType)) {
                $preWhere .= " AND content_type = '" . $contentType . "' ";
            }

            $getContentSql = "SELECT content_id,
                    hospital_id,
                    content_type,
                    content_value,
                    status,
                    added_user_id,
                    added_date
                    modified_user_id,
                    modified_date
                FROM sp_content 
                WHERE 1 " . $preWhere . " ";
                
            if ($this->num_of_rows($this->query($getContentSql))) {
                return $this->fetch_all_array($getContentSql);
            } else {
                return 0;
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
            $hospitalId   = $args['hospital_id'] ;
            $hospitalIpId   = $args['hospital_ip_id'] ;
            $hospitalDtls = $args['hospital_dtls'];
            $hospitalIpDtls = $args['hospital_ip_dtls'];
            $insertData   = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '14';
            $insertActivityArr['module_name']   = 'Manage Hospitals';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Hospital ( $hospitalIpId ? 'IP' : '' ) details " . ( $hospitalId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if ((!empty($hospitalDtls) || !empty($hospitalIpDtls)) 
                && !empty($insertData)) {
                $hospitalDtls = (empty($hospitalDtls) && !empty($hospitalIpDtls) ? $hospitalIpDtls : $hospitalDtls);
                unset($hospitalDtls['status'],
                    $hospitalDtls['statusVal'],
                    $hospitalDtls['added_by'],
                    $hospitalDtls['added_date'],
                    $hospitalDtls['last_modified_by']
                );
                // unset variable
                if (!empty($hospitalIpDtls)) {
                    unset($hospitalDtls['hospital_ip_id']);
                }  else {
                    unset($hospitalDtls['hospital_id']);
                }

                $hospitalDiff = array_diff_assoc($hospitalDtls, $insertData);
                if (!empty($hospitalDiff)) {
                    foreach ($hospitalDtls AS $key => $valHospital) {
                        $activityDesc .= $key . " is change from " . $valHospital . " to " . $insertData[$key] . "\r\n";
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
     * This function is used for add activity for content
     *
     * @param array $args
     *
     * @return int $recordId
     *
     */
    public function addContentActivity($args)
    {
        $recordId = 0;
        if (!empty($args)) {
            $hospitalId   = $args['hospital_id'];
            $contentId    = $args['content_id'] ;
            $contentType  = $args['content_type'] ;
            $contentDtls = $args['content_dtls'];
            $insertData   = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '14';
            $insertActivityArr['module_name']   = 'Manage Hospitals';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Hospital content details " . ( $contentId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($contentDtls) && !empty($insertData)) {
                unset($contentDtls['content_id'],
                    $contentDtls['status'],
                    $contentDtls['added_user_id'],
                    $contentDtls['added_date']
                );
                $contentDiff = array_diff_assoc($contentDtls, $insertData);
                if (!empty($contentDiff)) {
                    foreach ($contentDiff AS $key => $valContent) {
                        $activityDesc .= $key . " is change from " . $valHospital . " to " . $insertData[$key] . "\r\n";
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