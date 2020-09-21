<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class feedbackClass extends AbstractDB 
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
    public function FeedbackList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (question LIKE '%".$search_value."%' OR option_type LIKE '%".$search_value."%')"; 
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
        $FeedbackSql="SELECT feedback_id FROM sp_feedback_form WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($FeedbackSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($FeedbackSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT feedback_id,question,option_type,status,isDelStatus,added_date FROM sp_feedback_form WHERE feedback_id='".$val_records['feedback_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // Getting Feedback Option Type
                if(!empty($RecordResult['option_type']))
                {
                    $OptionTypeArr=array(1=>'Textual',2=>'Radio',3=>'CheckBox',4=>'Rating');
                    $RecordResult['option_typeVal']=$OptionTypeArr[$RecordResult['option_type']];
                }
                // Getting Medicine Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                $this->resultFeedback[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultFeedback))
        {
            $resultArray['data']=$this->resultFeedback;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetFeedbackById($arg)
    {
        $feedback_id=$this->escape($arg['feedback_id']);
        $GetOneFeedbackSql="SELECT feedback_id,question,option_type,status,isDelStatus,added_by,added_date,modified_by,last_modified_date FROM sp_feedback_form WHERE feedback_id='".$feedback_id."'";
        if($this->num_of_rows($this->query($GetOneFeedbackSql)))
        {
            $Feedback=$this->fetch_array($this->query($GetOneFeedbackSql));
            // Getting User Type
            if(!empty($Feedback['option_type']))
            {
                $OptionTypeArr=array(1=>'Textal',2=>'Radio',3=>'Checkbox',4=>'Rating');
                $Feedback['option_typeVal']=$OptionTypeArr[$Feedback['option_type']];
            }
            // Getting Status
            if(!empty($Feedback['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Feedback['statusVal']=$StatusArr[$Feedback['status']];
            }
            // Getting Added User Name 
            if(!empty($Feedback['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Feedback['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Feedback['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Feedback['modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Feedback['modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Feedback['last_modified_by']=$ModifiedUser['name'];
            }
            return $Feedback;
        }
        else 
            return 0;            
    }
    
    /**
     *
     * This function is used fo add feedback details
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function AddFeedback($arg)
    {
        $feedback_id = $this->escape($arg['feedback_id']);

        if (!empty($feedback_id) && $feedback_id != '') {
            // Get feedback details
            $feedbackDtls = $this->GetFeedbackById($arg);
            $ChkFeedbackSql = "SELECT feedback_id FROM sp_feedback_form WHERE question = '" . $arg['question'] . "' AND status != '3' AND feedback_id != '" . $feedback_id . "'";
        } else {
            $ChkFeedbackSql = "SELECT feedback_id FROM sp_feedback_form WHERE question = '" . $arg['question'] . "' AND status != '3'";
        }

        if ($this->num_of_rows($this->query($ChkFeedbackSql)) == 0) {
            $insertData = array();
            $insertData['question'] = $this->escape($arg['question']);
            $insertData['option_type'] = $this->escape($arg['option_type']);
            $insertData['modified_by'] = $this->escape($arg['modified_by']);
            $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);

            if (!empty($feedback_id)) {
                $where = "feedback_id = '" . $feedback_id . "'";
                $RecordId = $this->query_update('sp_feedback_form', $insertData, $where); 
            } else {
                $insertData['status']     = $this->escape($arg['status']);
                $insertData['added_by']   = $this->escape($arg['added_by']);
                $insertData['added_date'] = $this->escape($arg['added_date']);
                $RecordId = $this->query_insert('sp_feedback_form', $insertData);
            }
            
            if (!empty($RecordId)) {
                // Add activity details while adding feedback details
                $param = array();
                $param['feedback_id']   = $feedback_id;
                $param['feedback_dtls'] = $feedbackDtls;
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
     * This function is used fo add feedback options details
     *
     * @param array $arg
     *
     * @return int $RecordId
     *
     */
    public function addMultiOptions($arg)
    {
        $feedback_id = $this->escape($arg['feedback_id']);
        $feedback_option_id = $this->escape($arg['feedback_option_id']);

        if (!empty($feedback_option_id) && $feedback_option_id !='') {
            // Get feedback option details
            $feedbacOptionkDtls = $this->getFeedbackOptionById($feedback_option_id);
            $ChkFeedbackOptionSql = "SELECT feedback_option_id FROM sp_feedback_options WHERE option_value = '" . $arg['option_value'] . "' AND feedback_id = '" . $feedback_id . "' AND status !='3' AND feedback_option_id ! = '" . $feedback_option_id . "'";
        } else { 
            $ChkFeedbackOptionSql = "SELECT feedback_option_id FROM sp_feedback_options WHERE option_value = '" . $arg['option_value'] . "' AND feedback_id = '" . $feedback_id . "' AND status !='3'";
        }

        if ($this->num_of_rows($this->query($ChkFeedbackOptionSql)) == 0) {
            if ($arg['pre_option_type'] != $arg['option_type'] &&
                ($arg['option_type'] == '1' || $arg['option_type'] == '4')) {
                // Delete All Answers 
                $DelAnswers = "DELETE FROM sp_feedback_answers WHERE feedback_id = '" . $feedback_id . "'";
                $this->query($DelAnswers);
                // Delete All Options
                $DelOptions = "DELETE FROM sp_feedback_options WHERE feedback_id = '" . $feedback_id . "'";
                $this->query($DelOptions);
            }
            $insertData = array();
            $insertData['feedback_id'] = $this->escape($arg['feedback_id']);
            $insertData['option_value'] = $this->escape($arg['option_value']);
            $insertData['modified_by'] = $this->escape($arg['modified_by']);
            $insertData['last_modified_date'] = $this->escape($arg['last_modified_date']);
            if (!empty($feedback_option_id)) {
                $where = "feedback_option_id = '" . $feedback_option_id . "'";
                $RecordId = $this->query_update('sp_feedback_options',$insertData, $where);  
            } else {
                $insertData['status'] = $this->escape($arg['status']);
                $insertData['added_by'] = $this->escape($arg['added_by']);
                $insertData['added_date'] = $this->escape($arg['added_date']);
                $RecordId = $this->query_insert('sp_feedback_options', $insertData);
            }
            
            if (!empty($RecordId)) {
                // Add activity details while adding feedback details
                $param = array();
                $param['feedback_id']          = $feedback_id;
                $param['feedback_option_id']   = $feedback_option_id;
                $param['feedback_option_dtls'] = $feedbacOptionkDtls;
                $param['record_data']          = $insertData;
                $this->addActivity($param);
                unset($param);
                return $RecordId;
            } else {
                return 0;
            }
        } 
    }

    /**
     *
     * This function is used for get feedback option details by id
     */
    public function getFeedbackOptionById($feedbackOptionId)
    {
        $recordResult = array();

        if (!empty($feedbackOptionId)) {
            $feedbackOptionSql = "SELECT feedback_option_id,
                feedback_id,
                option_value,
                status,
                added_by,
                added_date,
                modified_by,
                last_modified_date
            FROM sp_feedback_options
            WHERE feedback_option_id = '" . $feedbackOptionId . "' ";

            if ($this->num_of_rows($this->query($feedbackOptionSql))) {
                $recordResult = $this->fetch_array($this->query($feedbackOptionSql));
            }
        }
        return $recordResult;
    }

    /**
     *
     * This function is used fo update feedback status
     *
     * @param array $arg
     *
     * @return int 0|1
     *
     */
    public function ChangeStatus($arg)
    {
        $feedback_id    = $this->escape($arg['feedback_id']);
        $status         = $this->escape($arg['status']);
        $pre_status     = $this->escape($arg['curr_status']);
        $istrashDelete  = $this->escape($arg['istrashDelete']);
        $login_user_id  = $this->escape($arg['login_user_id']);
        $ChkFeedbackSql = "SELECT feedback_id,
            status,
            modified_by,
            isDelStatus,
            last_modified_date
        FROM sp_feedback_form
        WHERE feedback_id = '" . $feedback_id . "'";

        if ($this->num_of_rows($this->query($ChkFeedbackSql))) {
            $feedbackDtls = $this->fetch_array($this->query($ChkFeedbackSql));
            if ($istrashDelete) {
                // Delete All Options
                $DelOptionSql="DELETE FROM sp_feedback_options WHERE feedback_id='".$feedback_id."'";
                $this->query($DelOptionSql);
                $UpdateStatusSql = "DELETE FROM sp_feedback_form WHERE feedback_id = '" . $feedback_id . "'";
            } else {
                // Change option Status 
                $UpdateOptionStatusSql = "UPDATE sp_feedback_options SET status = '" . $status . "', modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE feedback_id = '" . $feedback_id . "'";
                $this->query($UpdateOptionStatusSql);
                $UpdateStatusSql = "UPDATE sp_feedback_form SET status = '" . $status . "', isDelStatus = '" . $pre_status . "', modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE feedback_id = '" . $feedback_id . "'";
            }

            $RecordId = $this->query($UpdateStatusSql);

            if (!empty($RecordId) && !empty($feedbackDtls)) {
                $insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '10';
                $insertActivityArr['module_name']   = 'Manage Feedback';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Medicine details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "status is change from " . $feedbackDtls['status'] . " to " . $status . "\r\n";
                $activityDesc .= "isDelStatus is change from " . $feedbackDtls['isDelStatus'] . " to " . $pre_status . "\r\n";
                $activityDesc .= "modified_by is change from " . $feedbackDtls['modified_by'] . " to " . $login_user_id . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $feedbackDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
                return $RecordId;
            }
        } else { 
            return 0;
        }
    }

    /**
     *
     * This function is used for get feedback option by feedback id
     *
     * @param array $args
     *
     * @return array $result
     *
     */
    public function GetAllFeedbackOptions($arg)
    {
        $result = array();
        $feedback_id=$this->escape($arg['feedback_id']);
        $GetFeedbackOptionSql="SELECT feedback_option_id,feedback_id,option_value,status FROM sp_feedback_options WHERE feedback_id = '" . $feedback_id . "'";
        if ($this->num_of_rows($this->query($GetFeedbackOptionSql))) {
            $result =  $this->fetch_all_array($GetFeedbackOptionSql);
        }
        return $result;
    }

    /**
     *
     * This function is used for remove feedback option by id
     *
     * @param array $args
     *
     * @return int $recordId
     *
     */
    public function RemoveFeedbackOptionById($arg)
    {
       $feedback_option_id = $this->escape($arg['feedback_option_id']);
       $ChkFeedbackOptionSql = "SELECT feedback_option_id,feedback_id FROM sp_feedback_options WHERE feedback_option_id = '" . $feedback_option_id . "'";
       if ($this->num_of_rows($this->query($ChkFeedbackOptionSql))) {
            $Result = $this->fetch_array($this->query($ChkFeedbackOptionSql));
            // Delete All Answers
            $DelAnswersSql = "DELETE FROM sp_feedback_answers WHERE feedback_id = '" . $Result['feedback_id'] . "'";
            $this->query($DelAnswersSql);
            // Delete All Options
            $DelOptionSql = "DELETE FROM sp_feedback_options WHERE feedback_option_id = '" . $feedback_option_id . "'";
            return $this->query($DelOptionSql);
       }
       else {
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
            $feedbackId         = $args['feedback_id'];
            $feedbackOptionId   = $args['feedback_option_id'];
            $feedbackDtls       = $args['feedback_dtls'];
            $feedbackOptionDtls = $args['feedback_option_dtls'];
            $insertData         = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '10';
            $insertActivityArr['module_name']   = 'Manage Feedback';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = ( $feedbackOptionId ? 'Feedback option' : 'Feedback' ) . " details " . ( $feedbackId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if ((!empty($feedbackDtls) || !empty($feedbackOptionDtls))
                && !empty($insertData)) {
                $feedbackDtls = (empty($feedbackDtls) && !empty($feedbackOptionDtls) ? $feedbackOptionDtls : $feedbackDtls);
                unset($feedbackDtls['status'],
                    $feedbackDtls['added_by'],
                    $feedbackDtls['added_date'],
                    $feedbackDtls['last_modified_by']
                );

                // unset variable
                if (!empty($feedbackOptionDtls)) {
                    unset($feedbackDtls['feedback_option_id']);
                }  else {
                    unset($feedbackDtls['feedback_id'],
                        $feedbackDtls['statusVal'],
                        $feedbackDtls['last_modified_by']
                    );
                }
                $feedbackDiff = array_diff_assoc($feedbackDtls, $insertData);
                if (!empty($feedbackDiff)) {
                    foreach ($feedbackDtls AS $key => $valFeedback) {
                        $activityDesc .= $key . " is change from " . $valFeedback . " to " . $insertData[$key] . "\r\n";
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