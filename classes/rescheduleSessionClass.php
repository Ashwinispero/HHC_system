<?php
	if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
 
	class rescheduleSessionClass extends AbstractDB 
	{
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
		* This function is used for get event reschedule session list
		*/
		public function rescheduleSessionList($arg)
		{
			$preWhere = "";
			$filterWhere = "";
			$join = "";
			$searchValue = $this->escape($arg['search_value']);
			$filterName = $this->escape($arg['filter_name']);
			$filterType = $this->escape($arg['filter_type']);
			$searchfromDate  = $this->escape($arg['searchfromDate']);
			$searchToDate    = $this->escape($arg['searchToDate']);

			
			if (!empty($searchValue) && $searchValue !='null')
			{
			   $preWhere = " AND (event_code LIKE '%" . $searchValue . "%' OR reschedule_start_date LIKE '%" . $searchValue . "%' OR reschedule_end_date LIKE '%" .$searchValue . "%' OR reschedule_reason LIKE '%" . $searchValue . "%')"; 
			}
			
			if ((!empty($filterName) && $filterName != 'null') && (!empty($filterType) && $filterType != 'null'))
			{
				$filterWhere .= " ORDER BY " . $filterName . " " . $filterType . ""; 
			}

			if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(rs.reschedule_start_date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND DATE_FORMAT(rs.reschedule_start_date,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
        }
			
			//$preWhere .= " AND DATE(rs.reschedule_start_date) >= DATE(NOW()) ";
			
			$rescheduleSessionSql = "SELECT rs.reschedule_session_id
				FROM sp_reschedule_session AS rs 
				LEFT JOIN sp_events AS e 
					ON rs.event_id = e.event_id
				LEFT JOIN sp_service_professionals AS sp 
					ON rs.professional_id = sp.service_professional_id
				LEFT JOIN sp_detailed_event_plan_of_care AS dpc
					ON rs.detail_plan_of_care_id = dpc.Detailed_plan_of_care_id 
				" . $join . " 
				WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "";


			//echo '<pre>';
			//print_r($rescheduleSessionSql);
			//echo '</pre>';
			//exit;
				
			$this->result = $this->query($rescheduleSessionSql);
			
			if ($this->num_of_rows($this->result))
			{
				$pager = new PS_Pagination($rescheduleSessionSql, $arg['pageSize'], $arg['pageIndex'], '');
				$allRecords = $pager->paginate();
				while($valRecords = $this->fetch_array($allRecords))
				{
					// Getting Record Detail
					$recordResult = $this->getRescheduleSessionById($valRecords['reschedule_session_id']);
					
					if (!empty($recordResult)) {			
						$this->resultRescheduleSession[] = $recordResult;
						unset($recordResult);
					}
				}
				$resultArray['count'] = $pager->total_rows;
			}
			
			if(count($this->resultRescheduleSession))
			{
				$resultArray['data'] = $this->resultRescheduleSession;
				return $resultArray;
			}
			else {
				return array(
					'data' => array(),
					'count' => 0
				);
			}				
		}
		
		/**
		* This function is used for get event reschedule session by reschedule_session_id
		*/
		public function getRescheduleSessionById($rescheduleSessionId)
		{
			if (!empty($rescheduleSessionId)) {
				$getRescheduleSessionSql = "SELECT rs.reschedule_session_id,
						rs.event_id,
						dpc.start_date As actual_session_start_date,
						dpc.end_date As actual_session_end_date,
						e.event_code,
						e.purpose_id,
						rs.detail_plan_of_care_id,
						rs.professional_id,
						rs.professional_type,
						(CASE
							WHEN rs.professional_type = '1' THEN 'Professional'
							WHEN rs.professional_type = '2' THEN 'Patient'
						END) AS requesterType,
						sp.professional_code,
						sp.email_id,
						sp.mobile_no,
						CONCAT_WS(' ', sp.first_name, sp.name) AS `professional_name`,
						CONCAT_WS(' ', p.first_name, p.name) AS `patient_name`,
						p.email_id AS patient_email_id,
						p.mobile_no AS patient_mobile_no,
						p.hhc_code,
						rs.session_start_date,
						rs.session_end_date,
						rs.reschedule_start_date,
						rs.reschedule_start_time,
						rs.reschedule_end_date,
						rs.reschedule_end_time,
						rs.reschedule_reason,
						rs.professional_acceptance_status,
						(CASE
							WHEN rs.professional_acceptance_status = '1' THEN 'Pending'
							WHEN rs.professional_acceptance_status = '2' THEN 'Accepted'
							WHEN rs.professional_acceptance_status = '3' THEN 'Rejected'
						END) AS profAccStatus,
						rs.professional_acceptance_narration,
						rs.patient_acceptance_status,
						(CASE
							WHEN rs.patient_acceptance_status = '1' THEN 'Pending'
							WHEN rs.patient_acceptance_status = '2' THEN 'Accepted'
							WHEN rs.patient_acceptance_status = '3' THEN 'Rejected'
						END) AS patientAccStatus,
						rs.patient_acceptance_narration,
						rs.added_user_id,
						rs.added_user_type,
						(CASE
							WHEN rs.added_user_type = '1' THEN 'Professional'
							WHEN rs.added_user_type = '2' THEN 'HD User'
						END) AS raisedBy,
						rs.added_date,
						rs.modified_user_id,
						rs.modified_user_type,
						rs.modified_date,
						rs.status,
						(CASE
							WHEN rs.status = '1' THEN 'Initiated'
							WHEN rs.status = '2' THEN 'Accepted'
							WHEN rs.status = '3' THEN 'Rejected'
						END) AS statusVal
					FROM `sp_reschedule_session` AS rs
					LEFT JOIN sp_events AS e 
						ON rs.event_id = e.event_id
					LEFT JOIN sp_patients AS p 
						ON p.patient_id = e.patient_id
					LEFT JOIN sp_service_professionals AS sp 
						ON rs.professional_id = sp.service_professional_id
					LEFT JOIN sp_detailed_event_plan_of_care AS dpc
						ON rs.detail_plan_of_care_id = dpc.Detailed_plan_of_care_id 
					WHERE rs.reschedule_session_id = '" . $rescheduleSessionId . "' ";

					
					if ($this->num_of_rows($this->query($getRescheduleSessionSql))) {
						return $this->fetch_array($this->query($getRescheduleSessionSql));
					} else {
						return 0;
					}
			} else {
				return 0;
			}
		}
		
		/**
		* This function is used for add reschedule session details
		*/
		public function addRescheduleSession($arg)
		{
			
		}
		
		/**
		* This function is used for update reschedule session details
		*/
		public function updateRescheduleSession($arg)
		{
			
		}
		
		/**
		* This function is used for change reschedule session status
		*/
		public function changeRescheduleSessionStatus($arg)
		{
			$rescheduleSessionId = $this->escape($arg['reschedule_session_id']);
			$professionalAcceptanceStatus = $this->escape($arg['professional_acceptance_status']);
			$professionalAcceptanceNarration = $this->escape($arg['professional_acceptance_narration']);
			$patientAcceptanceStatus = $this->escape($arg['patient_acceptance_status']);
			$patientAcceptanceNarration = $this->escape($arg['patient_acceptance_narration']);
			
			$ChkRescheduleSessionSql = "SELECT reschedule_session_id,
				professional_acceptance_status,
				professional_acceptance_narration,
				patient_acceptance_status,
				patient_acceptance_narration,
				modified_user_id,
				modified_user_type,
				modified_date
			FROM sp_reschedule_session 
			WHERE reschedule_session_id = '" . $rescheduleSessionId . "'";

			if ($this->num_of_rows($this->query($ChkRescheduleSessionSql)))
			{
				$updateData = array();

				// Get reschedule session details
				$rescheduleSessDtls = $this->fetch_array($this->query($ChkRescheduleSessionSql));

				$activityDesc = "Reschedule session details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

				if (!empty($professionalAcceptanceStatus) && !empty($professionalAcceptanceNarration)) {
					$updateData['professional_acceptance_status'] = $professionalAcceptanceStatus;
					$updateData['professional_acceptance_narration'] = $professionalAcceptanceNarration;

					$activityDesc .= "professional_acceptance_status is change from " . $rescheduleSessDtls['professional_acceptance_status'] . " to " . $professionalAcceptanceStatus . "\r\n";
					$activityDesc .= "professional_acceptance_narration is change from " . $rescheduleSessDtls['professional_acceptance_narration'] . " to " . $professionalAcceptanceNarration . "\r\n";
				}
				
				if (!empty($patientAcceptanceStatus) && !empty($patientAcceptanceNarration)) {
					$updateData['patient_acceptance_status'] = $patientAcceptanceStatus;
					$updateData['patient_acceptance_narration'] = $patientAcceptanceNarration;

					$activityDesc .= "patient_acceptance_status is change from " . $rescheduleSessDtls['patient_acceptance_status'] . " to " . $patientAcceptanceStatus . "\r\n";
					$activityDesc .= "patient_acceptance_narration is change from " . $rescheduleSessDtls['patient_acceptance_narration'] . " to " . $patientAcceptanceNarration . "\r\n";
				}
	
				$updateData['modified_user_id'] = $this->escape($arg['modified_user_id']);
				$updateData['modified_user_type'] = $this->escape($arg['modified_user_type']);
				$updateData['modified_date'] = $this->escape($arg['modified_date']);
				
				if (!empty($rescheduleSessionId)) {
				  $where = "reschedule_session_id = '" . $rescheduleSessionId . "' ";
				  $recordId = $this->query_update('sp_reschedule_session', $updateData, $where);

				  if (!empty($recordId)) {
					// Add activity log while re schedule session
					$insertActivityArr = array();
					$insertActivityArr['module_type']   = '2';
					$insertActivityArr['module_id']     = '37';
					$insertActivityArr['module_name']   = 'Manage Reschedule Session';
					$insertActivityArr['event_id']      = '';
					$insertActivityArr['purpose_id']    = '';
					$insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
					$insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
					$insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
					
					$activityDesc .= "modified_user_id is change from " . $rescheduleSessDtls['modified_user_id'] . " to " . $_SESSION['admin_user_id'] . "\r\n";
					$activityDesc .= "modified_date is change from " . $rescheduleSessDtls['modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

					$insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
					$this->query_insert('sp_user_activity', $insertActivityArr);
					unset($insertActivityArr, $activityDesc);
				  }
				  
				  //fetch record details
				  $rescheduleSessionDtls = $this->getRescheduleSessionById($rescheduleSessionId);
				  if (!empty($rescheduleSessionDtls)) {
						$updateArr = array();
						$updateArr['status'] = '';
						if ($rescheduleSessionDtls['professional_acceptance_status'] == '2' && $rescheduleSessionDtls['patient_acceptance_status'] == '2') {
						        // send notification to professional while both partner accept the request
							    $this->sendNotification($rescheduleSessionDtls, 'Accept');

							    // send sms to patient when both partner accept the request
							    $this->sendSMS($rescheduleSessionDtls, 'Accept');
								$updateArr['status'] = '2';
						} else if ($rescheduleSessionDtls['professional_acceptance_status'] == '3' || $rescheduleSessionDtls['patient_acceptance_status'] == '3') {
						    // send notification to professional while both partner reject the request
							$this->sendNotification($rescheduleSessionDtls, 'Reject');
							// send sms to patient when both partner reject the request
							$this->sendSMS($rescheduleSessionDtls, 'Reject');
							$updateArr['status'] = '3';
						}
						if (!empty($updateArr['status'])) {
							$this->query_update('sp_reschedule_session', $updateArr, $where);

							// Add activity log while re schedule session
							$insertActivityArr = array();
							$insertActivityArr['module_type']   = '2';
							$insertActivityArr['module_id']     = '37';
							$insertActivityArr['module_name']   = 'Manage Reschedule Session';
							$insertActivityArr['event_id']      = '';
							$insertActivityArr['purpose_id']    = '';
							$insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
							$insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
							$insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
							$activityDesc = "Reschedule session details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";
							$activityDesc .= "status is change from " . $rescheduleSessionDtls['status'] . " to " . $updateArr['status'] . "\r\n";
							$insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
							$this->query_insert('sp_user_activity', $insertActivityArr);
							unset($insertActivityArr, $activityDesc);

							//Update session details
							if ($updateArr['status'] == '2') {
								$this->updateSessionDtls($rescheduleSessionDtls);
							}
						}
						unset($updateArr['status']);
					}
				  
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
		*This function is used for update reschedule session details
		*/
		public function removeRescheduleSession($arg)
		{
			
		}
		
		/**
		* This function get plan of care details by evenyt id 
		*/
		public function getDtlPlanofCareByEventId($eventId, $eventRequirementId, $whereClause = '') {
			if (!empty($eventId) && !empty($eventRequirementId)) {
				$dtlPlanofCareSql ="SELECT Detailed_plan_of_care_id,
						plan_of_care_id,
						event_id,
						event_requirement_id,
						index_of_Session,
						professional_vender_id,
						extend_service_id,
						service_date,
						service_date_to,
						Actual_Service_date,
						start_date,
						end_date,
						actual_StartDate_Time,
						actual_EndDate_Time,
						service_cost,
						amount_received,
						status,
						Session_status,
						session_note,
						Reason_for_no_serivce,
						Comment_for_no_serivce,
						OTP,
						OTP_count,
						otp_expire_time,
						Reschedule_count
					FROM sp_detailed_event_plan_of_care
					WHERE event_requirement_id = '" . $eventRequirementId . "'
						AND event_id = '" . $eventId . "' " . $whereClause . "
					ORDER BY `sp_detailed_event_plan_of_care`.`index_of_Session` ASC";

					//echo '<pre>dtlPlanofCareSql <br>';
					//print_r($dtlPlanofCareSql);
					//echo '</pre>';
					//exit;
						
					
						
				if ($this->num_of_rows($this->query($dtlPlanofCareSql))) {
					$dtlPlanofCareDtls = $this->fetch_all_array($dtlPlanofCareSql);
					return $dtlPlanofCareDtls;
				} else {
					return 0;
				}
			}
		}
		
		/**
		* This function get plan of care details by plan of care id & event Id
		*/
		public function getPlanOfCareByReqId($eventId, $eventRequirementId) {
			if (!empty($eventId) && !empty($eventRequirementId)) {
				$planOfCareSql ="SELECT plan_of_care_id,
						event_id,
						event_requirement_id,
						professional_vender_id,
						service_date,
						service_date_to,
						start_date,
						end_date,
						service_cost,
						status
					FROM sp_event_plan_of_care
					WHERE event_id = '" . $eventId . "' AND event_requirement_id = '" . $eventRequirementId . "' ";
					
				if ($this->num_of_rows($this->query($planOfCareSql))) {
					$planofCareDtls = $this->fetch_all_array($planOfCareSql);
					return $planofCareDtls;
				} else {
					return 0;
				}
			}
		}
		
		/**
		* This function get plan of care details by evenyt id 
		*/
		public function getDtlPlanofCareById($dtlPlanOfCareId) {
			if (!empty($dtlPlanOfCareId)) {
				$dtlPlanofCareSql ="SELECT Detailed_plan_of_care_id,
						plan_of_care_id,
						event_id,
						event_requirement_id,
						index_of_Session,
						professional_vender_id,
						extend_service_id,
						service_date,
						service_date_to,
						Actual_Service_date,
						start_date,
						end_date,
						actual_StartDate_Time,
						actual_EndDate_Time,
						service_cost,
						amount_received,
						status,
						Session_status,
						session_note,
						Reason_for_no_serivce,
						Comment_for_no_serivce,
						OTP,
						OTP_count,
						otp_expire_time,
						Reschedule_count
					FROM sp_detailed_event_plan_of_care
					WHERE Detailed_plan_of_care_id = '" . $dtlPlanOfCareId . "' ";

					//echo '<pre>getAllDtlPlanofCare ; <br>';
					//print_r($dtlPlanofCareSql);
					//echo '</pre>';
					//exit;
					
				if ($this->num_of_rows($this->query($dtlPlanofCareSql))) {
					$dtlPlanofCareDtls = $this->fetch_array($this->query($dtlPlanofCareSql));
					return $dtlPlanofCareDtls;
				} else {
					return 0;
				}
			}
		}
		
		/**
		* This function is used for update session details
		*/
		public function updateSessionDtls($rescheduleSessionDtls) {
			if (!empty($rescheduleSessionDtls)) {
				// Get detail plan of care detail
				$eventId = $rescheduleSessionDtls['event_id'];
				$dtlPlanOfCareId = $rescheduleSessionDtls['detail_plan_of_care_id'];
				$getDtlPlanofCare = $this->getDtlPlanofCareById($dtlPlanOfCareId);

				if (!empty($getDtlPlanofCare)) {

					// First Need to check is it reschedule request for same day for differnt time
					$rescheduleStartDate = date("Y-m-d", strtotime($rescheduleSessionDtls['reschedule_start_date']));
					$rescheduleStartTime = date("H:i:s", strtotime($rescheduleSessionDtls['reschedule_start_date']));

					$rescheduleEndDate = date("Y-m-d", strtotime($rescheduleSessionDtls['reschedule_end_date']));
					$rescheduleEndTime = date("H:i:s", strtotime($rescheduleSessionDtls['reschedule_end_date']));

					$sessionActualStartDate = date("Y-m-d", strtotime($getDtlPlanofCare['start_date']));
					$sessionActualEndDate = date("Y-m-d", strtotime($getDtlPlanofCare['end_date']));

					if ((($rescheduleStartDate == $sessionActualStartDate) &&
						($rescheduleEndDate == $sessionActualEndDate))) {


						// Update only session time
						$updateSessionSql = "UPDATE sp_detailed_event_plan_of_care 
							SET start_date = '" . $rescheduleSessionDtls['reschedule_start_date'] . "',
							end_date = '" . $rescheduleSessionDtls['reschedule_end_date'] . "'
							WHERE  Detailed_plan_of_care_id = '" . $getDtlPlanofCare['Detailed_plan_of_care_id'] . "'";
								
						$updateSessionRecord = $this->query($updateSessionSql);

						if (!empty($updateSessionRecord)) {

							//Update plan of care record entry
							$updatePlanOfCareSql = "UPDATE sp_event_plan_of_care 
							SET start_date = '" . date('h:i A', strtotime($rescheduleSessionDtls['reschedule_start_date'])) . "',
							end_date = '" . date('h:i A', strtotime($rescheduleSessionDtls['reschedule_end_date'])) . "'
							WHERE  plan_of_care_id = '" . $getDtlPlanofCare['plan_of_care_id'] . "'";

							$updatePlanOfCareRecord = $this->query($updatePlanOfCareSql);
							if (!empty($updatePlanOfCareRecord)) {
								return 1;
							} else {
								return 0;
							}	
						} else {
							return 0;
						}
					}

					$planOfCareId = $getDtlPlanofCare['plan_of_care_id'];
					$eventRequirementId = $getDtlPlanofCare['event_requirement_id'];
					//Check day differnce between event date and reschedule date
					$eventActualStartDate = $getDtlPlanofCare['service_date'];
					$eventActualEndDate = $getDtlPlanofCare['service_date_to'];


					
					// Get detail of plan of care by 
					$whereClause = " AND DATE_FORMAT(start_date, '%Y-%m-%d') > '" . date('Y-m-d', strtotime($getDtlPlanofCare['start_date'])) . "' ";
					$getAllDtlPlanofCare = $this->getDtlPlanofCareByEventId($eventId, $eventRequirementId, $whereClause);


					//echo '<pre>getAllDtlPlanofCare ; <br>';
					//print_r($getAllDtlPlanofCare);
					//echo '</pre>';
					//exit;

					if (!empty($getAllDtlPlanofCare)) {
					    
					    $planOfCareDtls = $this->getPlanOfCareByReqId($eventId, $eventRequirementId);
						$totalPlanofCares = count($planOfCareDtls);
					    
						foreach ($getAllDtlPlanofCare AS $key =>$valRecord) {
							$updateArr = array();
							
							$updateArr['index_of_Session'] = $valRecord['index_of_Session'] - 1;
							
							$serviceStartDate = strtotime("+1 day", strtotime($rescheduleSessionDtls['session_start_date']));
				        	if ($totalPlanofCares == 1) {
							    $updateArr['service_date'] = date("Y-m-d", $serviceStartDate);
						    }
							
							//$serviceEndDate = strtotime("+1 day", strtotime($valRecord['service_date_to']));
							//$updateArr['service_date_to'] = date("Y-m-d", $serviceEndDate);
							
							$sessionStartDate = strtotime("+1 day", strtotime($valRecord['start_date']));
							//$updateArr['Actual_Service_date'] = date("Y-m-d", strtotime($valRecord['start_date'])) . " " . $rescheduleSessionDtls['reschedule_start_time'];
							//$updateArr['start_date'] = $updateArr['Actual_Service_date'];
							
							$sessionEndDate = strtotime("+1 day", strtotime($valRecord['start_date']));
							//$updateArr['end_date'] = date("Y-m-d", strtotime($valRecord['end_date'])) . " " . $rescheduleSessionDtls['reschedule_end_time'];
							
							$where = "Detailed_plan_of_care_id = '" . $valRecord['Detailed_plan_of_care_id'] . "' ";
							$this->query_update('sp_detailed_event_plan_of_care', $updateArr, $where);
							
							unset($serviceStartDate,$serviceEndDate,$sessionStartDate,$sessionEndDate,$where);
						}
						
						//Get last rececord session index id and date details
						
		            	$GetLastRecordSql = "SELECT Detailed_plan_of_care_id,index_of_Session,service_date,service_date_to,start_date,end_date FROM  sp_detailed_event_plan_of_care 
							WHERE event_requirement_id = '" . $eventRequirementId . "' AND event_id = '" . $eventId . "' ORDER BY index_of_Session DESC LIMIT 0,1";
						
						$lastRecordDtls = $this->fetch_array($this->query($GetLastRecordSql));
						
						//first update all record service date date by last record end date
				    $where = " AND DATE_FORMAT(start_date, '%Y-%m-%d') < '" . date('Y-m-d', strtotime($getDtlPlanofCare['start_date'])) . "' AND plan_of_care_id = '" . $planOfCareId . "' ";
						$getExistingPlanOfCare = $this->getDtlPlanofCareByEventId($eventId, $eventRequirementId, $where);
						
						if (!empty($getExistingPlanOfCare)) {
						    $stDateVal = date('Y-m-d', strtotime($getDtlPlanofCare['start_date']));
						    $sessStDate = strtotime("-1 day", strtotime($stDateVal));
							foreach ($getExistingPlanOfCare as $key => $value) {
								$whrClause = "Detailed_plan_of_care_id = '" . $value['Detailed_plan_of_care_id'] . "' ";
								$arr1['service_date_to'] = date("Y-m-d", $sessStDate);
								$this->query_update('sp_detailed_event_plan_of_care',$arr1, $whrClause);
								unset($arr1, $whrClause);
							}
						}

						
						// Update existing record
						$arr = array();
						
						$arr['index_of_Session'] = $lastRecordDtls['index_of_Session'] + 1;
						$arr['service_date'] = date("Y-m-d", strtotime($rescheduleSessionDtls['reschedule_start_date']));
						$arr['service_date_to'] = date("Y-m-d", strtotime($rescheduleSessionDtls['reschedule_end_date']));
						$arr['Actual_Service_date'] = $rescheduleSessionDtls['reschedule_start_date'];
						$arr['start_date'] = $arr['Actual_Service_date'];
						$arr['end_date'] = $rescheduleSessionDtls['reschedule_end_date'];
						$whr = "Detailed_plan_of_care_id = '" . $rescheduleSessionDtls['detail_plan_of_care_id'] . "' ";
						
						$recordResult = $this->query_update('sp_detailed_event_plan_of_care', $arr, $whr);
						
						if (!empty($recordResult)) {
							
							// Update plan of care details
							$arg['change_session_date'] = $getDtlPlanofCare['start_date'];
							$arg['new_session_date'] = $rescheduleSessionDtls['reschedule_start_date'];
							$arg['event_id'] = $eventId;
							$arg['plan_of_care_id'] = $planOfCareId;
							$arg['event_requirement_id'] = $eventRequirementId;
							$arg['professional_id'] = $rescheduleSessionDtls['professional_id'];
							$arg['reschedule_start_time'] = date('h:i A', strtotime($rescheduleSessionDtls['reschedule_start_time']));
							$arg['reschedule_end_time'] = date('h:i A', strtotime($rescheduleSessionDtls['reschedule_end_time']));
							$arg['service_start_date'] = date("Y-m-d", strtotime($lastRecordDtls['service_date']));
							$arg['service_end_date'] = date("Y-m-d", strtotime($lastRecordDtls['end_date']));
							
							$arg['event_actual_start_date'] = date("Y-m-d", strtotime($eventActualStartDate));
							$arg['event_actual_end_date'] = date("Y-m-d", strtotime($eventActualEndDate));
							
							$arg['actual_session_date'] = $rescheduleSessionDtls['session_start_date'];
							$arg['detail_plan_of_care_id'] = $rescheduleSessionDtls['detail_plan_of_care_id'];
							
							// first check difference betwwen date
							$dateDiffBtnDates = $this->dateDiff($arg['event_actual_start_date'], date('Y-m-d', strtotime($rescheduleSessionDtls['session_start_date'])));
							
							
						    //echo '<br>$dateDiffBtnDates';
					        //print_r($dateDiffBtnDates);
					        //echo '</pre>';
					        //exit;
							
							$arg['isUpdate'] = (($dateDiffBtnDates > 0) ? 1 : 0);
							$this->updatePlanOfCareRecord($arg);
							unset($arg);
						}
					} else {
						// is it single day event
						$eventDateDiff = $this->dateDiff($arg['service_date'], date('Y-m-d', strtotime($rescheduleSessionDtls['service_date_to'])));

						if (empty($eventDateDiff)) {

							// Update only session time
							$updateSessionSql = "UPDATE sp_detailed_event_plan_of_care 
								SET start_date = '" . $rescheduleSessionDtls['reschedule_start_date'] . "',
								end_date = '" . $rescheduleSessionDtls['reschedule_end_date'] . "',
								service_date = '" . date('Y-m-d', strtotime($rescheduleSessionDtls['reschedule_start_date'])) . "',
								service_date_to = '" . date('Y-m-d', strtotime($rescheduleSessionDtls['reschedule_end_date'])) . "',
								Actual_Service_date = '" . $rescheduleSessionDtls['reschedule_start_date'] . "'
								WHERE  Detailed_plan_of_care_id = '" . $getDtlPlanofCare['Detailed_plan_of_care_id'] . "'";


							$updateSessionRecord = $this->query($updateSessionSql);

							if (!empty($updateSessionRecord)) {

								//Update plan of care record entry
								$updatePlanOfCareSql = "UPDATE sp_event_plan_of_care 
								SET service_date = '" . date('Y-m-d', strtotime($rescheduleSessionDtls['reschedule_start_date'])) . "',
									service_date_to = '" . date('Y-m-d', strtotime($rescheduleSessionDtls['reschedule_end_date'])) . "',
								start_date = '" . date('h:i A', strtotime($rescheduleSessionDtls['reschedule_start_date'])) . "',
								end_date = '" . date('h:i A', strtotime($rescheduleSessionDtls['reschedule_end_date'])) . "'
								WHERE  plan_of_care_id = '" . $getDtlPlanofCare['plan_of_care_id'] . "'";

								$updatePlanOfCareRecord = $this->query($updatePlanOfCareSql);
								if (!empty($updatePlanOfCareRecord)) {
									return 1;
								} else {
									return 0;
								}
								
							} else {
								return 0;
							}
						}
					}
					//Update session index
					$getAllDtlPlanOfCare = "SELECT Detailed_plan_of_care_id, index_of_Session, start_date 
			            FROM sp_detailed_event_plan_of_care 
			            WHERE event_requirement_id = '" . $eventRequirementId . "'
                        ORDER BY `start_date` ASC";	
                    if (mysql_num_rows($this->query($getAllDtlPlanOfCare)))
                    {
                        $planOfCareList = $this->fetch_all_array($getAllDtlPlanOfCare);
                        $index_of_Session = 0;
                        foreach ($planOfCareList AS $key => $valDtlPlanOfCare) 
                        {
                            $updateDtlPlanOfCareArr = array();
                            $index_of_Session++;
                            $updateDtlPlanOfCareArr['index_of_Session']    = $index_of_Session;
                            
                            //Check is it session date is upcoming
							$sessionDt = (
								(!empty($valDtlPlanOfCare['start_date']) && $valDtlPlanOfCare['start_date'] != '0000-00-00 00:00:00') 
								? date('Y-m-d', strtotime($valDtlPlanOfCare['start_date'])) : ''
							);
							$currentDate = date('Y-m-d');
							if ($sessionDt > $currentDate) {
								$updateDtlPlanOfCareArr['Session_status']    = '3';
							}
                            
                            $whereClause = "Detailed_plan_of_care_id ='" . $valDtlPlanOfCare['Detailed_plan_of_care_id'] . "' ";
                            $this->query_update('sp_detailed_event_plan_of_care', $updateDtlPlanOfCareArr, $whereClause);
                        }
                    }
				}
			}
		}
		
		/**
		* This function is used to update plan of care detail record
		*/
		public function updatePlanOfCareRecord($arg) {
			if (!empty($arg)) {
				//Get event details
				$eventReqDtls = $this->getEventReqDtls($arg['event_id'], $arg['event_requirement_id']);
				if (!empty($eventReqDtls)) {
					//per day service cost
					$arg['perDayServiceCost'] = $eventReqDtls['cost'];
					
					//Get All plan of care associated with this event & event requirement Id
					$planOfCareDtls = $this->getPlanOfCareByReqId($arg['event_id'], $arg['event_requirement_id']);
					
					$totalPlanofCares = count($planOfCareDtls);
					if (!empty($planOfCareDtls)) {
						if ($totalPlanofCares > 1) {
							//Check in which slot your change date is present

							//echo '<pre>checkPlanofCareSlot Arg';
							//print_r($arg);
							//echo '</pre>';

                            $arg['isSinglePlanOfCare'] = 'N';
							$this->checkPlanofCareSlot($arg);
							//exit;	
						} else {
							// Update existing Record 
							$arg['actualServiceStartDate'] = $planOfCareDtls[0]['service_date'];
							$arg['actualServiceEndDate'] = $planOfCareDtls[0]['service_date_to'];
							$arg['actualServiceStartTime'] = $planOfCareDtls[0]['start_date'];
							$arg['actualServiceEndTime'] = $planOfCareDtls[0]['end_date'];
							$arg['actualServiceCost'] = $planOfCareDtls[0]['service_cost'];


							//echo '<pre>updateExistingPlanOfCare Arg';
							//print_r($arg);
							//echo '</pre>';
							$this->updateExistingPlanOfCare($arg);

							// code for insert new plan of care record based on updated detail plan of care record

							//echo '<pre>createRecordAsPerSplitedPlanOfCare Arg';
							//print_r($arg);
							//echo '</pre>';
                             $arg['isSinglePlanOfCare'] = 'Y';	
							$this->createRecordAsPerSplitedPlanOfCare($arg);
						
							// Create new plan of care as per reschedule date

							//echo '<pre>createRecordAsPerReschedule Arg';
							//print_r($arg);
							//echo '</pre>';
							$this->createRecordAsPerReschedule($arg);
						}
					}
				}
			}
		}
		
		/**
		* calcuate date difference between 2 days
		*/
		function dateDiff($date1, $date2) 
		{
		  $date1_ts = strtotime($date1);
		  $date2_ts = strtotime($date2);
		  $diff = $date2_ts - $date1_ts;
		  return round($diff / 86400);
		}
		
		/**
		 * This function is used for send notification 
 	     */
        public function sendNotification($rescheduleSessionDtls, $status) {
            $resultantArr = array();
            if (!empty($rescheduleSessionDtls)) {
                $resultantArr['Type'] = '6';
            	$resultantArr['reschedule_session_id'] = $rescheduleSessionDtls['reschedule_session_id'];
            	$resultantArr['Title'] = (($status == 'Accept') ? '1' : '2');
            	$resultantArr['Professional_id'] = $rescheduleSessionDtls['professional_id'];
            	
            	if (!empty($resultantArr)) {
            	    $data = json_encode($resultantArr);
            	    $FCM_FILE_URL = "http://hospitalguru.in/push_notify.php";
        			$out = send_curl_request($FCM_FILE_URL, $data, "post");
        			$resultData = json_decode($out);               				
        			if ($resultData->success == 1) {
						// added activity log while succesfully send notification to professional

						// Get reschedule session details
						$rescheduleSessDtls = $this->getRescheduleSessionById($rescheduleSessionDtls['reschedule_session_id']);

						$insertActivityArr = array();
						$insertActivityArr['module_type']   = '2';
						$insertActivityArr['module_id']     = '37';
						$insertActivityArr['module_name']   = 'Manage Reschedule Session';
						$insertActivityArr['event_id']      = $rescheduleSessDtls['event_id'];
						$insertActivityArr['purpose_id']    = $rescheduleSessDtls['purpose_id'];
						$insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
						$insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
						$insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
						$activityDesc = "Reschedule session " . (($status == 'Accept') ? 'Accepted successfully by both patient and professsional' : 'Rejected either patient or professsional') . "notification sent to " . $rescheduleSessDtls['professional_name'] . " This details added by " . $_SESSION['admin_user_name'] . "\r\n";
						$insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
						$this->query_insert('sp_user_activity', $insertActivityArr);
						unset($insertActivityArr);

        				return 1;
        			} else {
        			    return 0;
        			}
            	}
            }
        }
		
		/**
		* This function is useful for update new plan care of id in plan of care detail table
		*/
		public function updateDetailPlanOfCare($arg) {
			if (!empty($arg)) {
				// Update detail of plan of care record details
				$getDtlsPlanOfCareSql = "SELECT Detailed_plan_of_care_id 
					FROM sp_detailed_event_plan_of_care 
					WHERE service_date = '" . $arg['service_date'] . "' 
						AND service_date_to = '" . $arg['service_date_to'] . "'
						AND event_id = '" . $arg['event_id'] . "'
						AND event_requirement_id = '" . $arg['event_requirement_id'] . "'";
						
				//echo '<pre>createRecordAsPerSplitedPlanOfCare Arg';
				//print_r($getDtlsPlanOfCareSql);
				//echo '</pre>';
					
						
				if($this->num_of_rows($this->query($getDtlsPlanOfCareSql)))
				{
					// Update plan of care id selected records
					$updateSql = "UPDATE sp_detailed_event_plan_of_care 
							SET plan_of_care_id = '" . $arg['plan_of_care_id'] . "',
    							service_date = '" . $arg['service_date'] . "',
    							service_date_to = '" . $arg['service_date_to'] . "'
							WHERE  service_date = '" . $arg['service_date'] . "' 
								AND service_date_to = '" . $arg['service_date_to'] . "'
								AND event_id = '" . $arg['event_id'] . "'
								AND event_requirement_id = '" . $arg['event_requirement_id'] . "'";

                //echo '<pre> Arg';
				//print_r($updateSql);
				//echo '</pre>';

				//echo '<pre>updateDetailPlanOfCare';
				//print_r($arg);
				//echo '</pre>';
								
					$this->query($updateSql);
				}
				return 1;
			} else {
				return 0;
			}
		}
		
		/**
		* This function is used to get event requirement details
		*/
		public function getEventReqDtls($eventId, $eventReqId) {
			if (!empty($eventId) && !empty($eventReqId)) {
				$getEventReqDtlsSql = "SELECT er.event_requirement_id,
						er.event_id,
						er.service_id,
						er.sub_service_id,
						ss.cost,
						ss.tax,
						er.professional_vender_id 
					FROM sp_event_requirements er
					INNER JOIN sp_sub_services ss
						ON er.sub_service_id = ss.sub_service_id
					WHERE er.status = '1'
						AND er.event_id = '" . $eventId . "'
						AND er.event_requirement_id = '" . $eventReqId . "'";
				if($this->num_of_rows($this->query($getEventReqDtlsSql))) {
					$rescheduleSessionDtls = $this->fetch_array($this->query($getEventReqDtlsSql));
					return $rescheduleSessionDtls;
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		/**
		* This function is used for adding new plan of care record as per request date
		*/
		public function updateExistingPlanOfCare($arg) {
			if (!empty($arg)) {
				if (!empty($arg['isUpdate'])) {
					$updateArr = array();
					$updateArr['service_date'] = $arg['event_actual_start_date'];
					$evntEndDate = strtotime("-1 day", strtotime($arg['actual_session_date']));
					$updateArr['service_date_to'] = date("Y-m-d", $evntEndDate);
					
					//Calculate service cost of existing records
					$dateDiffExistRecord = $this->dateDiff($updateArr['service_date'], $updateArr['service_date_to']);
					$updateArr['service_cost'] = (($dateDiffExistRecord > 0) ? (($dateDiffExistRecord + 1) * $arg['perDayServiceCost']) : $arg['perDayServiceCost']);
					$existingRecordServiceCost = $updateArr['service_cost'];
					$where = "plan_of_care_id = '" . $arg['plan_of_care_id'] . "' ";
					$updateRecord = $this->query_update('sp_event_plan_of_care', $updateArr, $where);
					
					if (!empty($updateRecord)) {
						$updateExistingRecordSql = "UPDATE sp_detailed_event_plan_of_care 
						SET service_date = '" . $updateArr['service_date'] . "',
							service_date_to = '" . $updateArr['service_date_to'] . "'
						WHERE
							Detailed_plan_of_care_id < '" . $arg['detail_plan_of_care_id'] . "'
							AND event_id = '" . $arg['event_id'] . "'
							AND event_requirement_id = '" . $planOfCareDtls['event_requirement_id'] . "'";
					
						$this->query($updateExistingRecordSql);
					}
					unset($updateArr);
				} else {
					$deleteUnwantedSub = "DELETE FROM sp_event_plan_of_care WHERE event_id = '" . $arg['event_id'] . "' and plan_of_care_id = '" . $arg['plan_of_care_id'] . "' ";
					$this->query($deleteUnwantedSub);
				}
			} else {
				return 0;
			}
		}
		
		/**
		* This function is used for adding new plan of care record as per request date
		*/
		public function createRecordAsPerReschedule($arg) {
			if (!empty($arg)) {
				// code for insert new plan of care record based on reschedule request date
				$newServiceStartDateForInsert = date('Y-m-d', strtotime($arg['new_session_date']));
				$newServiceEndDateForInsert = $newServiceStartDateForInsert;
				$insertRescheduleArr = array();
				$insertRescheduleArr['event_id'] = $arg['event_id'];
				$insertRescheduleArr['event_requirement_id'] = $arg['event_requirement_id'];
				$insertRescheduleArr['professional_vender_id'] = $arg['professional_id'];
				$insertRescheduleArr['service_date'] = $newServiceStartDateForInsert;
				$insertRescheduleArr['service_date_to'] = $newServiceEndDateForInsert;
				$insertRescheduleArr['start_date'] = $arg['reschedule_start_time'];
				$insertRescheduleArr['end_date'] = $arg['reschedule_end_time'];
				$insertRescheduleArr['service_cost'] = $arg['perDayServiceCost'];
				$insertRescheduleArr['status'] = '1';
				$insertRescheduleArr['added_by'] = strip_tags($_SESSION['admin_user_id']);
				$insertRescheduleArr['added_date'] = date('Y-m-d H:i:s');
				$insertRescheduleArr['last_modified_by'] = strip_tags($_SESSION['admin_user_id']);
				$insertRescheduleArr['last_modified_date'] = date('Y-m-d H:i:s');
				
				$rescheduleRecordId = $this->query_insert('sp_event_plan_of_care', $insertRescheduleArr);
				
				if (!empty($rescheduleRecordId)) {
					$updateArg = array();
					$updateArg['service_date'] = $insertRescheduleArr['service_date'];
					$updateArg['service_date_to'] = $insertRescheduleArr['service_date_to'];
					$updateArg['event_id'] = $insertRescheduleArr['event_id'];
					$updateArg['event_requirement_id'] = $insertRescheduleArr['event_requirement_id'];
					$updateArg['plan_of_care_id'] = $rescheduleRecordId;
					$this->updateDetailPlanOfCare($updateArg);
					unset($updateArg);
				}
				unset($insertRescheduleArr);
			} else {
				return 0;
			}
		}

		/**
		* This function is used to check your change date present in which plan of care
		*/
		public function checkPlanofCareSlot($prams) {

			
			if (!empty($prams)) {

				//echo '<pre>pramsData';
				//print_r($prams);
				//echo '</pre>';

				$actualSessionDate = date('Y-m-d', strtotime($prams['actual_session_date']));
				$perDayServiceCost = $prams['perDayServiceCost'];
				$pickPlanofCareSlotSql = "SELECT * 
					FROM sp_event_plan_of_care
					WHERE DATE_FORMAT(service_date, '%Y-%m-%d') <= '" . $actualSessionDate . "' AND
					DATE_FORMAT(service_date_to, '%Y-%m-%d') >= '" . $actualSessionDate . "' AND
					event_id = '" . $prams['event_id'] . "' AND
					event_requirement_id = '" . $prams['event_requirement_id'] . "'";

				//echo '<pre>pickPlanofCareSlotSql';
				//print_r($pickPlanofCareSlotSql);
				//echo '</pre>';
				//exit;

				if($this->num_of_rows($this->query($pickPlanofCareSlotSql)))
				{
					//Get Plan of care details
					$planofCareDtls = $this->fetch_array($this->query($pickPlanofCareSlotSql));

					//echo '<pre>planofCareDtls';
					//print_r($planofCareDtls);
					//echo '</pre>';
					//exit;

					if (!empty($planofCareDtls)) {
						$serviceStartDate = $planofCareDtls['service_date'];
						$serviceEndDate = $planofCareDtls['service_date_to'];
						$serviceCost = $planofCareDtls['service_cost'];
						
						if ((($serviceStartDate == $actualSessionDate) && ($serviceEndDate == $actualSessionDate))) {
						    
						    //echo '<pre> if <br>';
						    //exit;
							// change date (service_date && service_date_to same)  // update record
							$newSessionDate = date('Y-m-d', strtotime($prams['new_session_date']));
							$updateExistingPlanofCare = "UPDATE sp_event_plan_of_care 
								SET service_date = '" . $newSessionDate . "',
									service_date_to = '" . $newSessionDate . "'
								WHERE plan_of_care_id = '" . $planofCareDtls['plan_of_care_id'] . "' AND
									event_id = '" . $prams['event_id'] . "' AND
									event_requirement_id = '" . $prams['event_requirement_id'] . "'";

							$this->query($updateExistingPlanofCare);

						} else if ((($serviceStartDate == $actualSessionDate) && ($serviceEndDate != $actualSessionDate))) {
						    
						    //echo '<pre>else if 1 <br>';
						    //exit;
							// change date (service_date same && service_date_to not same)
							//add +1 day for split event
							$planOfCareStartDate = strtotime("+1 day", strtotime($planofCareDtls['service_date']));
							$newServiceCost = $serviceCost - $perDayServiceCost; 
							$updateExistingPlanofCare = "UPDATE sp_event_plan_of_care 
								SET service_date = '" . $planOfCareStartDate . "',
								service_cost =  '" . $newServiceCost . "'
								WHERE plan_of_care_id = '" . $planofCareDtls['plan_of_care_id'] . "' AND
									event_id = '" . $prams['event_id'] . "' AND
									event_requirement_id = '" . $prams['event_requirement_id'] . "'";

							$this->query($updateExistingPlanofCare);

							// create new recrd form service date
							$prams['new_session_date'] = $actualSessionDate;

							//echo '<pre>service_date same pramsData';
							//print_r($prams);
							//echo '</pre>';
							$this->createRecordAsPerReschedule($prams);

							
						} else if ((($serviceStartDate != $actualSessionDate) && ($serviceEndDate == $actualSessionDate))) {
						    
						    //echo '<pre>else if 2 <br>';
						    //exit;
							// change date (service_date not same && service_date_to  same)
							$planOfCareEndDate = strtotime("-1 day", strtotime($planofCareDtls['service_date_to']));
							$newServiceCost = $serviceCost - $perDayServiceCost;
							$updateExistingPlanofCare = "UPDATE sp_event_plan_of_care 
								SET service_date_to = '" . $planOfCareEndDate . "',
								service_cost =  '" . $newServiceCost . "'
								WHERE plan_of_care_id = '" . $planofCareDtls['plan_of_care_id'] . "' AND
									event_id = '" . $prams['event_id'] . "' AND
									event_requirement_id = '" . $prams['event_requirement_id'] . "'";
									
									
						    	
            					//print_r($updateExistingPlanofCare);
            					//echo '</pre>';
            					//exit;

							$this->query($updateExistingPlanofCare);

							// create new recrd form service_date_to  and add -1 day for split event
							$prams['new_session_date'] = $prams['new_session_date'];//get last date of event 

							//echo '<pre>service_date not same pramsData';
							//print_r($prams);
							//echo '</pre>';

							$this->createRecordAsPerReschedule($prams);
						} else {
							// change date in between date
							$planOfCareEndDate = strtotime("-1 day", strtotime($actualSessionDate));
							$dateDiff = $this->dateDiff($serviceStartDate, date('Y-m-d', $planOfCareEndDate));
							$newServiceCost = (($dateDiff > 0) ?  ($perDayServiceCost * ($dateDiff+1)) : $perDayServiceCost);
							
							//update record  service_date To change date -1 day for split event
							$updateExistingPlanofCare = "UPDATE sp_event_plan_of_care 
								SET service_date_to = '" . date('Y-m-d', $planOfCareEndDate) . "',
								service_cost =  '" . $newServiceCost . "'
								WHERE plan_of_care_id = '" . $planofCareDtls['plan_of_care_id'] . "' AND
									event_id = '" . $prams['event_id'] . "' AND
									event_requirement_id = '" . $prams['event_requirement_id'] . "'";
									
									
								//echo '<pre>else';
            					//print_r($updateExistingPlanofCare);
            					//echo '</pre>';
            					//exit;


							$this->query($updateExistingPlanofCare);

							// create a new record change date +1 day to service_date_to
							$newPlanOfCareEndDate = strtotime("+1 day", strtotime($actualSessionDate));
							$prams['service_start_date'] = date('Y-m-d',$newPlanOfCareEndDate);
							$prams['service_end_date'] = $planofCareDtls['service_date_to'];

							//Calculate day differnce
							$dateDiff = $this->dateDiff($newPlanOfCareEndDate, $planofCareDtls['service_date_to']);
							$prams['service_cost'] = (($dateDiff > 0) ?  ($perDayServiceCost * $dateDiff) : $perDayServiceCost);

						
							
							$prams['actualServiceStartTime'] = $planofCareDtls['start_date'];
							$prams['actualServiceEndTime'] = $planofCareDtls['end_date'];
							
							//echo '<pre>change date in between date createRecordAsPerSplitedPlanOfCare';
							//print_r($prams);
							//echo '</pre>';
							
							$this->createRecordAsPerSplitedPlanOfCare($prams);

							// create a new record with change date 
							$prams['new_session_date'] = $prams['new_session_date'];//get last date of event 

							//echo '<pre>change date in between date createRecordAsPerReschedule';
							//print_r($prams);
							//echo '</pre>';

							$this->createRecordAsPerReschedule($prams);
						}
					} 
				}
			}
		}

		/**
		* This function is used for add splited plan of care details
		*/
		public function createRecordAsPerSplitedPlanOfCare($arg) {
			// code for insert new plan of care record based on reschedule request date	
			$insertArr = array();
			$insertArr['event_id'] = $arg['event_id'];
			$insertArr['event_requirement_id'] = $arg['event_requirement_id'];
			$insertArr['professional_vender_id'] = $arg['professional_id'];
			$insertArr['service_date'] = $arg['service_start_date'];
			$insertArr['service_date_to'] = $arg['service_end_date'];
			$insertArr['start_date'] = $arg['actualServiceStartTime'];
			$insertArr['end_date'] = $arg['actualServiceEndTime'];
			//Calculate service cost
			$dateDiff = $this->dateDiff($insertArr['service_date'], $insertArr['service_date_to']);
			$insertArr['service_cost'] = ($dateDiff > 0 ? (($dateDiff + 1) * $arg['perDayServiceCost']) : $arg['perDayServiceCost']);
			$insertArr['status'] = '1';
			$insertArr['added_by'] = strip_tags($_SESSION['admin_user_id']);
			$insertArr['added_date'] = date('Y-m-d H:i:s');
			$insertArr['last_modified_by'] = strip_tags($_SESSION['admin_user_id']);
			$insertArr['last_modified_date'] = date('Y-m-d H:i:s');

		
			
			$recordId = $this->query_insert('sp_event_plan_of_care', $insertArr);
			
			if (!empty($recordId)) {
				$updateArg = array();
				$updateArg['service_date'] = $insertArr['service_date'];
				$updateArg['service_date_to'] = $insertArr['service_date_to'];
				$updateArg['event_id'] = $insertArr['event_id'];
				$updateArg['event_requirement_id'] = $insertArr['event_requirement_id'];
				$updateArg['plan_of_care_id'] = $recordId;
				$updateArg['change_session_date'] = $arg['change_session_date'];


				// echo '<pre>updateDetailPlanOfCare Arg : ' . $arg['isSinglePlanOfCare'] . '<br> Update earg <br>';
				 //print_r($updateArg);
				// echo '</pre>';
				//exit;
                if ($arg['isSinglePlanOfCare'] == 'Y') {
					$this->updateDetailPlanOfCare($updateArg);
				} else {
					$this->updateDetailPlanOfCareAsPerSplit($updateArg);	
				}
			
				unset($updateArg, $recordId);
			} else {
				return 0;
			}
		}
		
		/**
		* This function is useful for update new plan care of id in plan of care detail table
		*/
		public function updateDetailPlanOfCareAsPerSplit($arg) {
			if (!empty($arg)) {
				// Update detail of plan of care record details
				$changeSessionDate = date('Y-m-d', strtotime($arg['change_session_date']));
				$getDtlsPlanOfCareSql = "SELECT Detailed_plan_of_care_id 
					FROM sp_detailed_event_plan_of_care 
					WHERE DATE_FORMAT(service_date, '%Y-%m-%d') <= '" . $changeSessionDate . "' 
						AND DATE_FORMAT(service_date_to, '%Y-%m-%d') >= '" . $changeSessionDate . "'
						AND event_id = '" . $arg['event_id'] . "'
						AND event_requirement_id = '" . $arg['event_requirement_id'] . "'";
						
						
				//echo '<pre> getDtlsPlanOfCareSql : <br>' ;
				//print_r($getDtlsPlanOfCareSql);
				//echo '</pre>';
								
						
				if($this->num_of_rows($this->query($getDtlsPlanOfCareSql)))
				{
					// Update plan of care id selected records

					$getRecords = $this->fetch_all_array($getDtlsPlanOfCareSql);

					foreach ($getRecords as $key => $value) {
						$updateSql = "UPDATE sp_detailed_event_plan_of_care 
							SET plan_of_care_id = '" . $arg['plan_of_care_id'] . "',
								service_date = '" . $arg['service_date'] . "',
								service_date_to = '" . $arg['service_date_to'] . "'
							WHERE  Detailed_plan_of_care_id = '" . $value['Detailed_plan_of_care_id'] . "'
								AND event_id = '" . $arg['event_id'] . "'
								AND event_requirement_id = '" . $arg['event_requirement_id'] . "'";
								
							//echo '<pre> sp_detailed_event_plan_of_care : <br>' ;
            				//print_r($updateSql);
            				//echo '</pre>';

						$this->query($updateSql);
					}
				}
				return 1;
			} else {
				return 0;
			}
		}
		
		/**
		* This function is useful for send sms when both person accept / reject reschedule request  
		*/
		public function sendSMS($rescheduleSessionDtls, $status) {
			if (!empty($rescheduleSessionDtls) && !empty($status)) {
				$txtMsg1 = "";
				//Get patient mobile number
				$patientMobileNo = $rescheduleSessionDtls['patient_mobile_no'];
				$requestStatus = ($status == 'Accept' ? 'Accepted' : 'Rejected');

				$formUrl = "http://api.unicel.in/SendSMS/sendmsg.php";

				$msgDesc = "Your reschedule session request dated of " . date('d M Y h:i A', strtotime($rescheduleSessionDtls['reschedule_start_date'])) . " has been " . $requestStatus;
				$txtMsg1 .= " Dear " . $rescheduleSessionDtls['patient_name'];
                $txtMsg1 .= " Msg : " . $msgDesc;
                        
                $data_to_post = array();
                $data_to_post['uname'] = 'SperocHL';
                $data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
                $data_to_post['send'] = 'speroc';
                $data_to_post['dest'] = $patientMobileNo; 
                $data_to_post['msg'] = $txtMsg1;

                $curl = curl_init();
                curl_setopt($curl,CURLOPT_URL, $form_url);
                curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
                curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
				$result = curl_exec($curl);
				
				if (!empty($result)) {
					// added activity log while succesfully send notification to patient
					$insertActivityArr = array();
					$insertActivityArr['module_type']   = '2';
					$insertActivityArr['module_id']     = '37';
					$insertActivityArr['module_name']   = 'Manage Reschedule Session';
					$insertActivityArr['event_id']      = $rescheduleSessionDtls['event_id'];
					$insertActivityArr['purpose_id']    = $rescheduleSessionDtls['purpose_id'];
					$insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
					$insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
					$insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
					$activityDesc = "Reschedule session " . (($status == 'Accept') ? 'Accepted successfully by both patient and professsional' : 'Rejected either patient or professsional') . " SMS sent to " . $rescheduleSessionDtls['patient_name'] . " This details added by " . $_SESSION['admin_user_name'] . "\r\n";
					$insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
					$this->query_insert('sp_user_activity', $insertActivityArr);
					unset($insertActivityArr);
				}

                curl_close($curl);
			}
		}
	}
?>