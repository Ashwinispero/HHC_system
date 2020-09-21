<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class professionalsAvailabilityClass extends AbstractDB 
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

    public function professionalsAvailabilityList($arg)
    {
        $preWhere    		   = "";
        $filterWhere 		   = "";
        $join                  = "";
        $search_value          = $this->escape($arg['search_Value']);
        $location_value        = $this->escape($arg['location_Value']);
        $service_Value         = $this->escape($arg['service_Value']);
		$sub_service_Value     = $this->escape($arg['sub_service_Value']);
		$google_location_value = $this->escape($arg['google_location_value']);
        $filter_name           = $this->escape($arg['filter_name']);
        $filter_type           = $this->escape($arg['filter_type']);
		
		//echo '<pre>$google_location_value : <br>';
		//print_r($arg);
		//echo '</pre>';
		//exit;
		
        if (!empty($search_value) && $search_value !='null')
        {
           $preWhere = " AND (professional_code LIKE '%".$search_value."%' OR name LIKE '%".$search_value."%'  OR first_name LIKE '%".$search_value."%' OR middle_name LIKE '%".$search_value."%' OR email_id LIKE '%".$search_value."%' OR phone_no LIKE '%".$search_value."%' OR mobile_no LIKE '%".$search_value."%' OR work_email_id LIKE '%".$search_value."%' OR work_phone_no LIKE '%".$search_value."%' OR google_home_location LIKE '%".$search_value."%' OR google_work_location LIKE '%".$search_value."%' )"; 
        }
        
        if (!empty($location_value) && $location_value !='null')
        {
           $preWhere .= " AND t1.location_id='".$location_value."'";   
        }
        if (!empty($service_Value) && $service_Value !='null')
        {
           $preWhere .= " AND t2.service_id='".$service_Value."'";   
        }
		
		if (!empty($sub_service_Value) && $sub_service_Value !='null')
        {
           $preWhere .= " AND t3.sub_service_id IN ($sub_service_Value)";
		   $join .= " LEFT JOIN sp_professional_sub_services AS t3
				ON t1.service_professional_id = t3.service_professional_id";
        }
		
		// Getting Location Name by location id
		if(!empty($location_value) && $location_value !='null')
		{
		   $locSql = "SELECT location_id,
					location,pin_code
				FROM sp_locations
				WHERE location_id = '" . $location_value . "'
			";
			
		   $locDtls = $this->fetch_array($this->query($locSql));
		   $locationNm      = $locDtls['location']; 
		   $LocationPinCode = $locDtls['pin_code'];
		   
		   if (!empty($locationNm)) {
			   //$locationNm = preg_replace('/\s+/', '', $locationNm);
			   $preWhere .= " OR (LOWER(t5.location_name) LIKE '%" . strtolower($locationNm) . "%')";
			   $join .= " LEFT JOIN sp_professional_location AS t4
					ON t1.service_professional_id = t4.professional_service_id
				LEFT JOIN sp_professional_location_details AS t5
					ON t4.Professional_location_id = t5.professional_location_id";
		   }
		   
		}
		
		// Search data by google location name
		if (!empty($google_location_value) && $google_location_value !='null')
        {
			$region = 'IND';
			$address = str_replace(" ", "+", $google_location_value);
            $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyC8lSxG4pg8hWyd52oqUQJKWnjQSe20dvc&address=$address&sensor=false&region=$region");
            //$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
            $json = json_decode($json);

            $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
			
			if (!empty($lat) && !empty($long)) {
				$preWhere .= " AND ((t6.min_latitude <= '" . $lat . "' AND  t6.max_latitude >= '" . $lat . "') AND 
					(t6.min_longitude <= '" . $long . "' AND t6.max_longitude >= '" . $long . "'))";
				
				$join .= " LEFT JOIN sp_professional_location_preferences AS t6
					ON t1.service_professional_id = t6.service_professional_id
					JOIN sp_professional_availability_detail t7 
					ON t6.professional_location_id = t7.professional_location_id";
			}
        }
        
        if ((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .=" ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        
		$preWhere .= " AND t1.status IN ('1')";   
		$groupBy = " GROUP BY t1.service_professional_id";
	
     
        $professionalsSql = "SELECT t1.service_professional_id
			FROM sp_service_professionals AS t1
			LEFT JOIN sp_professional_services AS t2
				ON t1.service_professional_id = t2.service_professional_id
				" . $join . " WHERE 1 ".$preWhere."  " . $groupBy . " ".$filterWhere."
			";
			
		 //echo '<pre>';
		 //print_r($professionalsSql);
		 //echo '</pre>';
		

        $this->result = $this->query($professionalsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($professionalsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $allRecords = $pager->paginate();
            while($valRecords = $this->fetch_array($allRecords))
            {
                // Getting Record Detail
				$recordResult = $this->getProfessionalById($valRecords['service_professional_id']);
				
				//echo '<pre>';
				//print_r($recordResult);
				//echo '</pre>';
				//exit;
				
                $this->resultProfessional[] = $recordResult;
                
                unset($arr);
                unset($recordResult['Services']);
            }
            $resultArray['count'] = $pager->total_rows;
        }
		
        if(count($this->resultProfessional))
        {
            $resultArray['data']=$this->resultProfessional;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
	
    public function getProfessionalById($serviceProfessionalId)
    {
        if (!empty($serviceProfessionalId)) {
			$getProfessionalSql = "SELECT 
					service_professional_id,
					professional_code,
					reference_type,
					title,
					Job_type,name,
					first_name,
					middle_name,
					email_id,
					phone_no,
					mobile_no,
					dob,address,
					work_email_id,
					work_phone_no,
					work_address,
					location_id,
					location_id_home,
					set_location,
					status,
					(CASE
                        WHEN status = '1' THEN 'Active'
                        WHEN status = '2' THEN 'Inactive'
                        WHEN status = '3' THEN 'Deleted'
                    END) AS statusVal,
					isDelStatus,
					added_by,
					added_date,
					last_modified_by,
					last_modified_date,
					google_home_location,
					google_work_location 
				FROM sp_service_professionals
				WHERE service_professional_id = '" . $serviceProfessionalId . "'
			";
			
			if($this->num_of_rows($this->query($getProfessionalSql)))
			{
				$professional = $this->fetch_array($this->query($getProfessionalSql));
				
				// Getting Location Name
				if(!empty($professional['location_id']))
				{
				   $locationSql = "SELECT location_id,
							location,pin_code
						FROM sp_locations
						WHERE location_id = '" . $professional['location_id'] . "'
					";
					
				   $locationDtls = $this->fetch_array($this->query($locationSql));
				   $professional['locationNm']      = $locationDtls['location']; 
				   $professional['LocationPinCode'] = $locationDtls['pin_code']; 
				}
				
				// Getting Added User Name 
				if(!empty($professional['added_by']))
				{
				   $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$professional['added_by']."'";
				   $AddedUser=$this->fetch_array($this->query($AddedUserSql));
				   $professional['added_by']=$AddedUser['name']; 
				}
				// Getting Last Mpdofied User Name 
				if(!empty($professional['last_modified_by']))
				{
				   $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$professional['last_modified_by']."'";
				   $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
				   $professional['last_modified_by']=$ModifiedUser['name'];
				}
				
				//Getting Services
				$professional['Services'] = $this->getAssignedServicesByProfessional($professional['service_professional_id']);
				
				//Getting locations
				$professional['mobileLocations'] = $this->getLocationsByProfessionalId($professional['service_professional_id']);
				
				//Getting availability
				$professional['mobileAvailability'] = $this->getAvailabilityByProfessionalId($professional['service_professional_id']);

				unset($arr);   
				return $professional;
			}
			else {
				return 0; 
			}			
		} else {
			return 0;
		} 
    }
	
	/**
	*
	*/
	public function getAssignedServicesByProfessional($serviceProfessionalId)
    {
        if (!empty($serviceProfessionalId)) {
			$getServicesSql = "SELECT t2.service_id,
				t1.service_title
				FROM sp_professional_services AS t2 
				INNER JOIN sp_services AS t1 
					ON t2.service_id = t1.service_id
				WHERE t2.service_professional_id = '" . $serviceProfessionalId . "'
			";
			
			if($this->num_of_rows($this->query($getServicesSql)))
			{
				$resultData = $this->fetch_all_array($getServicesSql);
				
				$allServices = "";
				
				foreach($resultData AS $key => $valServices)
				{
					$allServices .= $valServices['service_title'] . ",";
				}
				
				$services = substr_replace($allServices, "", -1);
				
				if(!empty($services))
					return $services;
				else 
					return 0;   
			}
			else 
				return 0;
		} else {
			return 0;
		}
    }
	
	/**
	* This function is used for get all mobile location based on professional id
	*/
	public function getLocationsByProfessionalId($serviceProfessionalId)
	{
		if (!empty($serviceProfessionalId)) {
			$getLocationsSql = "SELECT t1.professional_service_id,
			t2.professional_location_details_id,
				t2.lattitude,
				t2.longitude,
				t2.location_name
				FROM sp_professional_location AS t1 
				INNER JOIN sp_professional_location_details AS t2 
					ON t1.Professional_location_id = t2.professional_location_id
				WHERE t1.professional_service_id = '" . $serviceProfessionalId . "'
			";

			if($this->num_of_rows($this->query($getLocationsSql)))
			{
				$resultData = $this->fetch_all_array($getLocationsSql);
				$resultantArr = array();
				$locationStr = "";
				foreach ($resultData AS $key => $valResult) {
					//Get location name 
					list($firstContent, $lastContent) = explode(' Maharashtra ', $valResult['location_name']);
					$locationStr .= $firstContent . "@#";
				}
				return (!empty($locationStr) ? substr($locationStr, 0, -3) : '');
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	/**
	* This function is used for get all mobile availability based on professional id
	*/
	public function getAvailabilityByProfessionalId($serviceProfessionalId, $locationnValue='')
	{
		if (!empty($serviceProfessionalId)) {
			$join = '';
			$preWhere = '';
			$groupBy = '';

			if (!empty($locationnValue)) {
				// Search data by google location name
				$region = 'IND';
				$address = str_replace(" ", "+", $locationnValue);
				$json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyC8lSxG4pg8hWyd52oqUQJKWnjQSe20dvc&address=$address&sensor=false&region=$region");
				//$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
				$json = json_decode($json);

				$lat = number_format((float)$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}, 6, '.', '');
				$long = number_format((float)$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}, 6, '.', '');
				
				if (!empty($lat) && !empty($long)) {
					$preWhere .= " AND ((t4.min_latitude <= '" . $lat . "' AND  t4.max_latitude >= '" . $lat . "') AND 
						(t4.min_longitude <= '" . $long . "' AND t4.max_longitude >= '" . $long . "'))";
					
					$join .= "  INNER JOIN sp_professional_location_details AS t3 
							ON t2.professional_location_id = t3.professional_location_id
						INNER JOIN sp_professional_location_preferences AS t4
							ON t3.professional_location_id = t4.professional_location_id";

					$groupBy .= ' GROUP BY t2.professional_availability_detail';
				}
			}

			$getAvailabilitySql = "SELECT t1.day,
				(CASE
					WHEN day = '1' THEN 'Sunday'
					WHEN day = '2' THEN 'Monday'
					WHEN day = '3' THEN 'Tuesday'
					WHEN day = '4' THEN 'Wednesday'
					WHEN day = '5' THEN 'Thursday'
					WHEN day = '6' THEN 'Friday'
					WHEN day = '7' THEN 'Saturday'
				END) AS dayVal,
				t2.start_time,
				t2.end_time,
				t2.professional_availability_id
				FROM sp_professional_avaibility AS t1 
				INNER JOIN sp_professional_availability_detail AS t2 
					ON t1.professional_avaibility_id = t2.professional_availability_id
				" . $join . "
				WHERE t1.professional_service_id = '" . $serviceProfessionalId . "' " . $preWhere . " " . $groupBy . "
			";

			//echo '<pre>';
			//print_r($getAvailabilitySql);
			//echo '</pre>';


			if($this->num_of_rows($this->query($getAvailabilitySql)))
			{
				$resultData = $this->fetch_all_array($getAvailabilitySql);
				$resultantArr = array();
				$startTimeArr = array();
				$endTimeArr = array();
				$allDaysIdenticalStartTime = 0;
				$allDaysIdenticalEndTime = 0;
				foreach ($resultData AS $key => $valResult) {
					$startTimeArr = $valResult['start_time'];
					$endTimeArr = $valResult['end_time'];
				}
				
				if (count(array_unique($startTimeArr)) === 1 && end($startTimeArr) === true) {
					$allDaysIdenticalStartTime = 1;
				}
				
				if (count(array_unique($endTimeArr)) === 1 && end($endTimeArr) === true) {
					$allDaysIdenticalEndTime = 1;
				}
				return $resultData;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	/**
	* This function is used for get location name
	*/
	public function getLocationByLocationId($arg)
	{
		if (!empty($arg)) {
			$getLocationsSql = "SELECT t2.professional_availability_id,
				t3.professional_location_id,
				t3.professional_location_details_id,
				t3.lattitude,
				t3.longitude,
				t3.location_name
				FROM sp_professional_avaibility AS t1
				INNER JOIN sp_professional_availability_detail AS t2
					ON t1.professional_avaibility_id = t2.professional_availability_id 
				INNER JOIN sp_professional_location_details AS t3
					ON t2.professional_location_id = t3.professional_location_id
				WHERE t1.professional_service_id = '" . $arg['service_professional_id'] . "'
				GROUP BY t3.professional_location_details_id
			";
			
			if($this->num_of_rows($this->query($getLocationsSql)))
			{
				$resultData = $this->fetch_all_array($getLocationsSql);
				$resultantArr = array();
				foreach ($resultData AS $key => $valResult) {
					//Get location name 
					list($firstContent, $lastContent) = explode(' Maharashtra ', $valResult['location_name']);
					$locationStr .= $firstContent . "@#";
				}
				return (!empty($locationStr) ? substr($locationStr, 0, -3) : '');
			}
		}
	}
	
	/**
	* This function is used for get all sub services based on service id
	*/
	public function GetAllServicesByServiceId($serviceId)
	{
		if (!empty($serviceId)) {
			$subServiceSql = "SELECT sub_service_id, recommomded_service FROM sp_sub_services WHERE service_id = '" . $serviceId . "' AND status = '1'";
			if ($this->num_of_rows($this->query($subServiceSql))) {
				$subServiceList = $this->fetch_all_array($subServiceSql);
				
				if (!empty($subServiceList)) {
					return $subServiceList;
				} else {
					return 0;
				}
			}
		} else {
			return 0;
		}
	} 

	/**
	* This function is used to get details service / sub service details
	*/
	public function GetProfessionalServices($arg)
    {
        $service_professional_id = $this->escape($arg['service_professional_id']);
		$serviceType = $this->escape($arg['serviceType']);
		$serviceId = $this->escape($arg['service_id']);
		$tableName = (!empty($serviceType) && $serviceType == 'subService') ? 'sp_professional_sub_services t1' : 'sp_professional_services t1';
		$columnName = (!empty($serviceType) && $serviceType == 'subService') ? 't1.professional_services_subservices_id, t1.sub_service_id, t2.recommomded_service' : 't1.professional_service_id, t1.service_id, t2.service_title';
		$join = (!empty($serviceType) && $serviceType == 'subService') ? ' INNER JOIN sp_sub_services t2 ON t1.sub_service_id = t2.sub_service_id' : ' INNER JOIN sp_services t2 ON t1.service_id = t2.service_id';
		$whereClause = (!empty($serviceType) && $serviceType == 'subService') ? "WHERE t1.service_professional_id = '" . $service_professional_id . "' AND t1.service_id = '" . $serviceId ."'"  : " WHERE t1.service_professional_id = " . $service_professional_id;
		
        $GetServiceDtlsSql = "SELECT  " . $columnName . " FROM " . $tableName . " " . $join . " " . $whereClause . "";
	
        if($this->num_of_rows($this->query($GetServiceDtlsSql)))
        {
            $serviceDtls = ($serviceType == 'subService') ? $this->fetch_all_array($GetServiceDtlsSql) : $this->fetch_array($this->query($GetServiceDtlsSql));
            return $serviceDtls;
        }
        else 
            return 0;
    }
}
//END
?>