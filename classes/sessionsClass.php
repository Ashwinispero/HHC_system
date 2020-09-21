<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
 
class sessionsClass extends AbstractDB 
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

    /**
    * This function is used for get all active session 
    */
    public function sessionsList($arg)
    {
        $preWhere        = "";
        $filterWhere     = "";
        $join            = "";
        $search_value    = $this->escape($arg['search_Value']);
        $ref_type_value  = $this->escape($arg['ref_type_Value']);
        $service_Value   = $this->escape($arg['service_Value']);
        $isPhysiotherapy = $this->escape($arg['isPhysiotherapy']);
        $filter_name     = $this->escape($arg['filter_name']);
        $filter_type     = $this->escape($arg['filter_type']);
        $searchfromDate  = $this->escape($arg['searchfromDate']);
        $searchToDate    = $this->escape($arg['searchToDate']);
        $isTrash         = $this->escape($arg['isTrash']);

        if (!empty($search_value) && $search_value !='null')
        {
           $preWhere = " AND (t4.event_code LIKE '%".$search_value."%' OR t5.professional_code LIKE '%".$search_value."%' OR t5.first_name LIKE '%".$search_value."%' OR t5.name LIKE '%".$search_value."%' OR t6.first_name LIKE '%".$search_value."%' OR t6.name LIKE '%".$search_value."%')"; 
        }

        if(!empty($service_Value) && $service_Value !='null')
        {
           $preWhere .=" AND t3.service_id = '" . $service_Value . "'";   
        }

        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .=" ORDER BY " . $filter_name . " " . $filter_type . ""; 
        }
        
        if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(t1.start_date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND (DATE_FORMAT(t1.start_date, '%Y-%m-%d') BETWEEN CURDATE() - INTERVAL 1 DAY  AND CURDATE())";
        }

        $preWhere .= " AND t1.status IN ('1','2')";
      
        $groupBy = "GROUP BY t1.Detailed_plan_of_care_id"; 
        $filterWhere ="ORDER BY t1.start_date ASC";

        $sessionsSql = "SELECT t1.Detailed_plan_of_care_id,
                CONCAT_WS(' ', t5.first_name, t5.name) AS `professional_name`,
                CONCAT_WS(' ', t6.first_name, t6.name) AS `patient_name`
                FROM sp_detailed_event_plan_of_care AS t1
                INNER JOIN  sp_event_professional AS t2
                    ON t1.professional_vender_id = t2.professional_vender_id
                INNER JOIN  sp_event_requirements AS t3
                    ON t1.event_requirement_id = t3.event_requirement_id
                INNER JOIN  sp_events AS t4
                    ON t1.event_id = t4.event_id
                INNER JOIN  sp_service_professionals AS t5
                    ON t1.professional_vender_id = t5.service_professional_id
                INNER JOIN  sp_patients AS t6
                    ON t4.patient_id = t6.patient_id
                INNER JOIN  sp_services AS t7
                    ON t3.service_id = t7.service_id
                INNER JOIN  sp_sub_services AS t8
                    ON t3.sub_service_id = t8.sub_service_id
            " . $join . " WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . " ";

        $this->result = $this->query($sessionsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($sessionsSql, $arg['pageSize'], $arg['pageIndex'], '');
            $allRecords = $pager->paginate();
            while ($valRecords = $this->fetch_array($allRecords))
            {
                // Getting Record Detail
                $recordResult = $this->getSessionById($valRecords['Detailed_plan_of_care_id']);

                $this->resultSession[] = $recordResult;
                unset($recordResult['Services']);
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if (count($this->resultSession))
        {
            $resultArray['data'] = $this->resultSession;
            return $resultArray;
        }
        else {
            return array('data' => array(), 'count' => 0); 
        }
    }

    /**
    * This function is used for get active session by sessionId 
    */
    public function getSessionById($detailedPlanOfCareId)
    {
        if (!empty($detailedPlanOfCareId)) {
            $getSessionSql = "SELECT
                t1.Detailed_plan_of_care_id,
                t1.index_of_Session,
                t1.start_date,
                t1.end_date,
                t1.status,
                t1.Session_status,
                t1.session_note,
                t1.Reason_for_no_serivce,
                t1.Comment_for_no_serivce,
                t1.modified_user_id,
                t1.last_modified_by,
                t1.last_modified_date,
                t2.plan_of_care_id,
                t2.event_id,
                t2.event_requirement_id,
                t2.professional_vender_id,
                t2.service_date,
                t2.service_date_to,
                t2.service_cost,
                t5.event_code,
                t5.purpose_id,
                t5.patient_id,
                t7.hhc_code,
                t6.professional_code,
                t6.email_id,
                t6.mobile_no,
                CONCAT_WS(' ', t6.first_name, t6.name) AS `professional_name`,
                CONCAT_WS(' ', t7.first_name, t7.name) AS `patient_name`,
                t7.email_id AS patient_email_id,
                t7.mobile_no AS patient_mobile_no,
                t4.service_id,
                t4.sub_service_id,
                t8.service_title,
                t9.recommomded_service,
                (CASE
                    WHEN t1.Session_status = '1' THEN 'Pending'
                    WHEN t1.Session_status = '2' THEN 'Completed'
                    WHEN t1.Session_status = '3' THEN 'Upcoming'
                    WHEN t1.Session_status = '4' THEN 'No show by Patient'
                    WHEN t1.Session_status = '5' THEN 'No show by Professional'
                    WHEN t1.Session_status = '6' THEN 'Closed'
                    WHEN t1.Session_status = '7' THEN 'Enroute'
                    WHEN t1.Session_status = '8' THEN 'Started Session'
                    WHEN t1.Session_status = '9' THEN 'Completed Session'
                END) AS statusVal
            FROM sp_detailed_event_plan_of_care AS t1
            INNER JOIN sp_event_plan_of_care AS t2
                ON t1.plan_of_care_id = t2.plan_of_care_id
            INNER JOIN sp_event_professional AS t3
                ON t2.professional_vender_id = t3.professional_vender_id
            INNER JOIN sp_event_requirements AS t4
                ON t2.event_requirement_id = t4.event_requirement_id
            INNER JOIN sp_events AS t5
                ON t2.event_id = t5.event_id
            INNER JOIN sp_service_professionals AS t6
                ON t2.professional_vender_id = t6.service_professional_id
            INNER JOIN sp_patients AS t7
                ON t5.patient_id = t7.patient_id
            INNER JOIN sp_services AS t8
                ON t4.service_id = t8.service_id
            INNER JOIN sp_sub_services AS t9
                ON t4.sub_service_id = t9.sub_service_id

            WHERE Detailed_plan_of_care_id = '" . $detailedPlanOfCareId . "' GROUP BY Detailed_plan_of_care_id ";

            if($this->num_of_rows($this->query($getSessionSql)))
            {
                return $this->fetch_array($this->query($getSessionSql));
            }
            else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    
    /**
    * This function is used for change session status 
    */
    public function changeSessionStatus($arg)
    {
        $detailedPlanOfCareId     = $this->escape($arg['Detailed_plan_of_care_id']);
        $status                   = $this->escape($arg['Session_status']);
		$payment_received_status  = $this->escape($arg['payment_received_status']);
		$payment_type             = $this->escape($arg['payment_type']);
		$amount                   = $this->escape($arg['amount']);
		$narration                = $this->escape($arg['narration']);
		$reason                   = $this->escape($arg['reason']);
		$modified_user_id         = $this->escape($arg['modified_user_id']);
		$modified_by              = $this->escape($arg['modified_by']);
        $modified_date            = $this->escape($arg['modified_date']);
        
        // Get session details
        $sessionDtls = $this->getSessionById($detailedPlanOfCareId);

        $activityDesc ="";
		
        $ChkDetailedPlanOfCareSql = "SELECT Detailed_plan_of_care_id FROM sp_detailed_event_plan_of_care WHERE Detailed_plan_of_care_id = '" . $detailedPlanOfCareId . "'";
        if ($this->num_of_rows($this->query($ChkDetailedPlanOfCareSql))) {
			if ($status == '2') {
				//Insert Payment details
                $paymentDtls = $this->insertPaymentDetail($arg);
                
                if (!empty($paymentDtls)) {
                    $activityDesc .= $paymentDtls;
                }
				//Insert Job closure details
				$JobClosure = $this->insertJobClosureDetailDateWise($arg);
				if (empty($JobClosure)) {
					return 0;
				} else {
                    $activityDesc .= $JobClosure;
                }
			}
			// Update detail Plan of care details
			$UpdateDtls = "UPDATE sp_detailed_event_plan_of_care 
				SET Session_status = '" . $status . "', modified_user_id = '" . $modified_user_id . "', last_modified_by = '" . $modified_by . "' , last_modified_date = '" . date('Y-m-d H:i:s') . "', Reason_for_no_serivce = '3', Comment_for_no_serivce = '" . $reason . "' WHERE Detailed_plan_of_care_id = '" . $detailedPlanOfCareId . "'";
            $RecordId = $this->query($UpdateDtls);

            if (!empty($RecordId)) {
                // Add activity for session status change
                $arg['activity_desc_dtls'] = $activityDesc;
                $this->addActivity($sessionDtls, $arg);
            }

            return $RecordId;
        }
        else {
            return 0;
		}
    }
	
	public function insertJobClosureDetailDateWise($param)
	{
		if (!empty($param)) {
			//Get detail plan of care detail
			$detailedPlanOfCareId = $param['Detailed_plan_of_care_id'];
			$getDetailPlanOfCareSql = "SELECT er.event_requirement_id,
					er.event_id,
					er.service_id,
					er.sub_service_id,
					dpc.service_date AS eventStartDate,
					dpc.service_date_to AS eventEndDate,
					dpc.start_date AS sessionStartDate,
					dpc.end_date AS sessionEndDate
				FROM sp_event_requirements er
				INNER JOIN sp_detailed_event_plan_of_care dpc
					ON dpc.event_requirement_id = er.event_requirement_id
				WHERE dpc.Detailed_plan_of_care_id = '" . $detailedPlanOfCareId . "' ";
				
			if ($this->num_of_rows($this->query($getDetailPlanOfCareSql))) {
				$getDetailPlanOfCare = $this->fetch_array($this->query($getDetailPlanOfCareSql));
				
				if (!empty($getDetailPlanOfCare)) {
					$insertData = array();
					$insertData['event_id']            = $getDetailPlanOfCare['event_id'];
					$insertData['service_id']          = $getDetailPlanOfCare['service_id'];
					$insertData['sub_service_id']      = $getDetailPlanOfCare['sub_service_id'];
					$insertData['service_date']        = date('Y-m-d', strtotime($getDetailPlanOfCare['sessionStartDate']));
					$insertData['actual_service_date'] = date('Y-m-d', strtotime($getDetailPlanOfCare['sessionStartDate']));
					$insertData['job_closure_detail']  = $param['reason'];											
					$insertData['StartTime']           = date('h:i A', strtotime($getDetailPlanOfCare['sessionStartDate']));						
					$insertData['Endtime']             = date('h:i A', strtotime($getDetailPlanOfCare['sessionEndDate']));
					$insertData['added_by']            = $this->escape($param['modified_user_id']);
					$insertData['added_by_type']       = $this->escape($param['modified_by']);
					$insertData['added_date']          = date('Y-m-d H:i:s');
					$RecordId = $this->query_insert('sp_jobclosure_detail_datewise', $insertData);
					if (!empty($RecordId)) {

                        // Activity log while adding payment received details
                        $activityDesc = " Job closure details added successfully by " . $_SESSION['admin_user_name'] . "\r\n";
                        $activityDesc .= " service_id : " . $getDetailPlanOfCare['service_id'] . "\r\n";
                        $activityDesc .= " sub_service_id : " . $getDetailPlanOfCare['sub_service_id'] . "\r\n";
                        $activityDesc .= " service_date : " . $insertData['service_date'] . "\r\n";
                        $activityDesc .= " actual_service_date : " . $insertData['actual_service_date'] . "\r\n";
                        $activityDesc .= " job_closure_detail : " . $insertData['job_closure_detail'] . "\r\n";
                        $activityDesc .= " StartTime : " . $insertData['StartTime'] . "\r\n";
                        $activityDesc .= " Endtime : " . $insertData['Endtime'] . "\r\n";
                        $activityDesc .= " added_by : " . $insertData['added_by'] . "\r\n";
                        $activityDesc .= " added_by_type : " . $insertData['added_by_type'] . "\r\n";
                        $activityDesc .= " added_date : " . $insertData['added_date'] . "\r\n";
                        
						return $activityDesc;
					} else {
						return 0;
					}
				}
			}
		} else {
			return 0;
		}
	}
	
	public function insertPaymentDetail($param)
	{
		if (!empty($param)) {
			//Get detail plan of care detail
			$detailedPlanOfCareId = $param['Detailed_plan_of_care_id'];
			$getDetailPlanOfCareSql = "SELECT er.event_requirement_id,
					er.event_id,
					er.service_id,
					er.sub_service_id,
					dpc.service_date AS eventStartDate,
					dpc.service_date_to AS eventEndDate,
					dpc.start_date AS sessionStartDate,
					dpc.end_date AS sessionEndDate,
					dpc.professional_vender_id,
                    CONCAT_WS(' ', ssp.first_name, ssp.name) AS `professional_name`
				FROM sp_event_requirements er
				INNER JOIN sp_detailed_event_plan_of_care dpc
					ON dpc.event_requirement_id = er.event_requirement_id
                INNER JOIN sp_service_professionals AS ssp
                    ON ssp.service_professional_id = dpc.professional_vender_id
				WHERE dpc.Detailed_plan_of_care_id = '" . $detailedPlanOfCareId . "' ";
			if ($this->num_of_rows($this->query($getDetailPlanOfCareSql))) {
				$getDetailPlanOfCare = $this->fetch_array($this->query($getDetailPlanOfCareSql));
				$insertData = array();
				$insertData['event_id']                 = $getDetailPlanOfCare['event_id'];
				$insertData['event_requirement_id']     = $getDetailPlanOfCare['event_requirement_id'];
				$insertData['Session_id']               = $detailedPlanOfCareId;
				$insertData['cheque_DD__NEFT_no']       = $this->escape($param['narration']);
				$insertData['professional_vender_id']   = $getDetailPlanOfCare['professional_vender_id'];
				$insertData['amount']                   = $this->escape($param['amount']);
				$insertData['date_time']                = date('Y-m-d H:i:s');
				$insertData['Payment_type']             = $this->escape($param['payment_type']);
				$insertData['Payment_mode']             = '1';
				$insertData['Comments']                 = $this->escape($param['narration']);
				$insertData['status']                   = '1';
				$insertData['added_by']                 = $this->escape($param['modified_user_id']);
				$insertData['added_by_type']            = $this->escape($param['modified_by']);
				
				$RecordId = $this->query_insert('sp_payments_received_by_professional', $insertData);
				if (!empty($RecordId)) {
                    // Activity log while adding payment received details
                    $activityDesc = "Payment received from  " . $getDetailPlanOfCare['professional_name'] . " details added successfully by " . $_SESSION['admin_user_name'] . "\r\n";
                    $activityDesc .= " event_requirement_id : " . $getDetailPlanOfCare['event_requirement_id'] . "\r\n";
                    $activityDesc .= " Session_id : " . $detailedPlanOfCareId . "\r\n";
                    $activityDesc .= " cheque_DD__NEFT_no : " . $insertData['cheque_DD__NEFT_no'] . "\r\n";
                    $activityDesc .= " amount : " . $insertData['amount'] . "\r\n";
                    $activityDesc .= " date_time : " . date('Y-m-d H:i:s') . "\r\n";
                    $activityDesc .= " Payment_type : " . $insertData['Payment_type'] . "\r\n";
                    $activityDesc .= " Payment_mode : 1 \r\n";
                    $activityDesc .= " Comments : " . $insertData['Comments'] . "\r\n";
                    $activityDesc .= " status : 1 \r\n";
                    $activityDesc .= " added_by : " . $insertData['added_by'] . "\r\n";
                    $activityDesc .= " added_by_type : " . $insertData['added_by_type'] . "\r\n";
                    
					return $activityDesc;
				} else {
					return 0;
				}
			} 
		} else {
			return 0;
		}
    }
    
    /**
     *
     * This function is used for add session activity
     *
     * @param array $sessionDtls
     *
     * @param array $modifiedData
     *
     * @return int $recordId
     *
     */
    public function addActivity($sessionDtls, $modifiedData)
    {
        $recordId = 0;
        if (!empty($sessionDtls)  && !empty($modifiedData)) {
            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '40';
            $insertActivityArr['module_name']   = 'Manage Sessions';
            $insertActivityArr['event_id']      = $sessionDtls['event_id'];
            $insertActivityArr['purpose_id']    = $sessionDtls['purpose_id'];
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = $modifiedData['activity_desc_dtls'] . "\r\n";

            unset($modifiedData['activity_desc_dtls']);

            $activityDesc .= "Session details " . ( $sessionDtls ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";
            $activityDesc .=  "Session_status is change from " . $sessionDtls['status'] . " to " . $modifiedData['Session_status'] . "\r\n";
            $activityDesc .=  "modified_user_id is change from " . $sessionDtls['modified_user_id'] . " to " . $_SESSION['admin_user_id'] . "\r\n";
            $activityDesc .=  "last_modified_by is change from " . $sessionDtls['last_modified_by'] . " to " . $modifiedData['modified_by'] . "\r\n";
            $activityDesc .=  "last_modified_date is change from " . $sessionDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";
            $activityDesc .=  "Reason_for_no_serivce is change from " . $sessionDtls['Reason_for_no_serivce'] . " to 3 \r\n";
            $activityDesc .=  "mment_for_no_serivce is change from " . $sessionDtls['Reason_for_no_serivce'] . " to " . $modifiedData['reason'] . " \r\n";

            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
    }
}
//END
?>