<?php
	if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';

    class extendServiceClass extends AbstractDB 
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
		* This function is used for get extend service list
        */
        public function extendServiceList($arg)
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
			   $preWhere = " AND (e.event_code LIKE '%" . $searchValue . "%' OR es.service_date LIKE '%" . $searchValue . "%' OR es.service_date_to LIKE '%" . $searchValue . "%' OR es.startTime LIKE '%" .$searchValue . "%' OR es.endTime LIKE '%" .$searchValue . "%' OR es.estimate_cost LIKE '%" . $searchValue . "%')"; 
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

                $preWhere .= " AND (DATE_FORMAT(es.added_date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
            } else {
                $preWhere .= " AND DATE_FORMAT(es.added_date,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
                1 DAY)";
            }

            $extendServiceSql = "SELECT es.extend_service_id,
                    es.plan_of_care_id,
                    es.event_id,
                    e.event_code,
                    es.service_date,
                    es.service_date_to,
                    es.startTime,
                    es.endTime,
                    es.estimate_cost,
                    es.OTP,
                    es.status,
                    (CASE
                        WHEN es.status = '1' THEN 'Confirmed'
                        WHEN es.status = '2' THEN 'Enquiry'
                    END) AS statusVal,
                    es.added_date,
                    es.OTP_count,
                    es.otp_expire_time
				FROM sp_extend_service AS es
                INNER JOIN sp_events AS e
                    ON e.event_id = es.event_id
				" . $join . " 
                WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "";

            //echo '<pre>';
            //print_r($extendServiceSql);
            //echo '</pre>';
            //exit;
                
            $this->result = $this->query($extendServiceSql);

            if ($this->num_of_rows($this->result))
			{
				$pager = new PS_Pagination($extendServiceSql, $arg['pageSize'], $arg['pageIndex'], '');
				$allRecords = $pager->paginate();
				while($valRecords = $this->fetch_array($allRecords))
				{
					$this->resultExtendService[] = $valRecords;
				}
				$resultArray['count'] = $pager->total_rows;
			}
			
			if(count($this->resultExtendService))
			{
				$resultArray['data'] = $this->resultExtendService;
				return $resultArray;
			}
			else {
				return array(
					'data' => array(),
					'count' => 0
				);
			}
        }
    }
?>