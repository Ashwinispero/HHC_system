<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';

class dashboardClass extends AbstractDB 
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
     * This function is useful for get locationwise professional
     */
    public function getLocationWiseProfessionalList($arg)
    {
        $preWhere    = "";
        $filterWhere = "";
        $join        = " LEFT JOIN sp_service_professionals AS ssp
            ON pl.location_id = ssp.location_id AND ssp.location_id IS NOT NULL AND ssp.status = '1'";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        $groupBy = " GROUP BY ssp.location_id";

        $Sql = "SELECT COUNT(ssp.service_professional_id) AS totalRecords,
                pl.location_id,
                pl.location
            FROM sp_locations AS pl
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";

        if ($this->num_of_rows($this->query($Sql))) {
            $result = $this->fetch_all_array($Sql);
            
            return $result;
        }
        else {
            return 0;
        }
    }

    /**
     * This function is useful for get enquiry report
     */
    public function getEnquiryReport($arg)
    {
        $preWhere    = " AND enquiry_added_date != '0000-00-00 00:00:00'";
        $filterWhere = "";
        $join        = "";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        $searchByHospital = $this->escape($arg['hospital_id']);
        $searchByFromDate = $this->escape($arg['event_from_date']);
        $searchByToDate   = $this->escape($arg['event_to_date']);
     //$searchByFromDate='2020-05-01';
    //$searchByToDate='2020-05-31';
        $searchByConvertIntoService = $this->escape($arg['is_converted_service']);


        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        if (!empty($searchByHospital) && $searchByHospital !='null') {
           $preWhere .= " AND e.hospital_id = '" . $searchByHospital . "'"; 
        }

        if (!empty($searchByFromDate) && !empty($searchByToDate)) {
            $preWhere .= " AND DATE_FORMAT(e.enquiry_added_date, '%Y-%m-%d') BETWEEN '" . $searchByFromDate . "' AND '" . $searchByToDate . "'"; 
        }

        if (!empty($searchByConvertIntoService) && $searchByConvertIntoService !='null') {
            $preWhere .= " AND e.isConvertedService = '" . $searchByConvertIntoService . "'"; 
        }

        $Sql = "SELECT COUNT(event_id) AS totalRecords
                FROM sp_events AS e
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";
            
         /*echo '<pre>';
         print_r($Sql);
         echo '</pre>';
         exit;*/

        if ($this->num_of_rows($this->query($Sql))) {
            $result = $this->fetch_array($this->query($Sql));
            
            return $result;
        }
        else {
            return 0;
        }
    }

    /**
     * This function is useful for get service report
     */
    public function getServiceReport($arg)
    {
        $preWhere    = "";
        $filterWhere = "";
        $join        = "";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        $Sql = "SELECT 
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";
    }

    /**
     * This function is useful for get top professional
     */
    public function getTopProfessional($arg)
    {
        $preWhere    = "";
        $filterWhere = "";
        $join        = "";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        $Sql = "SELECT 
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";
    }

    /**
     * This function is useful for get top patients
     */
    public function getTopPatients($arg)
    {
        $preWhere    = "";
        $filterWhere = "";
        $join        = "";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        $Sql = "SELECT 
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";
    }

    /**
     * This function is useful for get top patients
     */
    public function getTopLocationByService($arg)
    {
        $preWhere    = "";
        $filterWhere = "";
        $join        = "";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        $Sql = "SELECT 
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";
    }

    /**
     * This function is useful for get top patients
     */
    public function getVIPPatientCount($arg)
    {
        $preWhere    = " AND p.isVIP = 'Y' AND p.status = '1' ";
        $filterWhere = "";
        $join        = " INNER JOIN sp_events AS e ON e.patient_id = p.patient_id";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        $searchByHospital = $this->escape($arg['hospital_id']);
        $searchByFromDate = $this->escape($arg['from_date']);
        $searchByToDate   = $this->escape($arg['to_date']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        if (!empty($searchByHospital) && $searchByHospital !='null') {
            $preWhere .= " AND e.hospital_id = '" . $searchByHospital . "'"; 
        }
 
        if (!empty($searchByFromDate) && !empty($searchByToDate)) {
        //$preWhere .= " AND added_date BETWEEN '" . $searchByFromDate . "' AND '" . $searchByToDate . "'"; 
        }

        $groupBy = " GROUP BY p.patient_id";

        $Sql = "SELECT p.patient_id
            FROM sp_patients AS p
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";
        $resulatantArr['totalRecords'] = 0;
        if ($this->num_of_rows($this->query($Sql))) {
            $resulatantArr['totalRecords'] =  $this->num_of_rows($this->query($Sql));
        }
        return $resulatantArr;
        
    }

    /**
     * This function is useful for get event
     */
    public function getEventReport($arg)
    {
        $preWhere    = "";
        $filterWhere = "";
        $join        = " INNER JOIN sp_events AS e ON e.event_id = dpc.event_id";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        $searchByHospital = $this->escape($arg['hospital_id']);
        $searchByFromDate = $this->escape($arg['event_from_date']);
        $searchByToDate   = $this->escape($arg['event_to_date']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        if (!empty($searchByHospital) && $searchByHospital !='null') {
            $preWhere .= " AND e.hospital_id = '" . $searchByHospital . "'"; 
        }
 
        if (!empty($searchByFromDate) && !empty($searchByToDate)) {
            $preWhere .= " AND DATE_FORMAT(dpc.start_date, '%Y-%m-%d') BETWEEN '" . $searchByFromDate . "' AND '" . $searchByToDate . "'"; 
        }

        $Sql = "SELECT COUNT(Detailed_plan_of_care_id) AS totalRecords
            FROM sp_detailed_event_plan_of_care AS dpc
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";

        if ($this->num_of_rows($this->query($Sql))) {
            $result = $this->fetch_array($this->query($Sql));
            
            return $result;
        }
        else {
            return 0;
        }
    }

    /**
     * This function is used for get service wise events report
     */
    public function getServiceWiseEventReport($arg)
    {
        $preWhere    = "";
        $filterWhere = "";
        $join        = "";
        $filterName = "ss.service_id"; //$this->escape($arg['filter_name']);
        $filterType = "ASC"; //$this->escape($arg['filter_type']);

        $searchByHospital = $this->escape($arg['hospital_id']);
        $searchByFromDate = $this->escape($arg['event_from_date']);
        $searchByToDate   = $this->escape($arg['event_to_date']);
        $searchByService  = $this->escape($arg['service_id']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        if (!empty($searchByHospital) && $searchByHospital !='null') {
            $preWhere .= " AND e.hospital_id = '" . $searchByHospital . "'"; 
        }
 
        if (!empty($searchByFromDate) && !empty($searchByToDate)) {
            $preWhere .= " AND DATE_FORMAT(depc.start_date, '%Y-%m-%d') BETWEEN '" . $searchByFromDate . "' AND '" . $searchByToDate . "'"; 
        }

        if (!empty($searchByService) && $searchByService !='null') {
            $preWhere .= " AND ss.service_id = '" . $searchByService . "'"; 
        }

        $join = " LEFT JOIN sp_event_requirements AS er 
                ON er.service_id = ss.service_id AND er.status = '1' 
            INNER JOIN sp_sub_services sss
            ON er.sub_service_id = sss.sub_service_id AND sss.status = '1' 
            INNER JOIN sp_events AS e
                ON er.event_id = e.event_id
            INNER JOIN sp_detailed_event_plan_of_care AS depc 
                ON depc.event_requirement_id = er.event_requirement_id AND
                depc.event_id = er.event_id
                AND depc.status = '1' ";

        $groupBy = "GROUP BY er.event_requirement_id";

        $Sql = "SELECT DISTINCT er.event_requirement_id,
                    ss.service_id,
                    ss.service_title,
                    sss.cost AS serviceCost
                FROM sp_services AS ss
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";

        if ($this->num_of_rows($this->query($Sql))) {
            $result = $this->fetch_all_array($Sql);
            $resultantArr = array();
            $totalRevenue = 0;
            foreach ($result AS $valResult) {
                $totalRevenue += $valResult['serviceCost'];
            }
            $resultantArr['service_id']    = $result[0]['service_id'];
            $resultantArr['service_title'] = $result[0]['service_title'];
            $resultantArr['totalCount']    = COUNT($result);
            $resultantArr['totalRevenue']  = $totalRevenue;

            return $resultantArr;
        }
        else {
            return 0;
        }
    }

    /**
     * This function is used for get professional wise services and revenue
     */
    public function getServiceWiseProfessionalRevenue($arg) 
    {
        $preWhere    = "";
        $filterWhere = "";
        $join        = "";
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);

        $searchByHospital = $this->escape($arg['hospital_id']);
        $searchByFromDate = $this->escape($arg['event_from_date']);
        $searchByToDate   = $this->escape($arg['event_to_date']);
        $searchByService  = $this->escape($arg['service_id']);

        $SearchByProfessional  = $this->escape($arg['SearchByProfessional']);
        $searchBySubService  = $this->escape($arg['SearchBySubService']);

        if ((!empty($filterName) && $filterName !='null')
            && (!empty($filterType) && $filterType !='null')) {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . " "; 
        }

        if (!empty($searchByHospital) && $searchByHospital !='null') {
            $preWhere .= " AND e.hospital_id = '" . $searchByHospital . "'"; 
        }
 
        if (!empty($searchByFromDate) && !empty($searchByToDate)) {
            $preWhere .= " AND DATE_FORMAT(dpc.start_date, '%Y-%m-%d') BETWEEN '" . $searchByFromDate . "' AND '" . $searchByToDate . "'"; 
        }

        if (!empty($searchByService) && $searchByService !='null') {
            $preWhere .= " AND ss.service_id = '" . $searchByService . "'"; 
        }

        if (!empty($SearchByProfessional) && $SearchByProfessional !='null') {
            $preWhere .= " AND dpc.professional_vender_id = '" . $SearchByProfessional . "'"; 
        }

        if (!empty($searchBySubService) && $searchBySubService !='null') {
            $preWhere .= " AND ss.sub_service_id = '" . $searchBySubService . "'"; 
        }

        $join = "INNER JOIN sp_event_requirements AS er
                    ON dpc.event_requirement_id = er.event_requirement_id AND
                        dpc.event_id = er.event_id
                INNER JOIN sp_events AS e
                    ON dpc.event_id = e.event_id
                INNER JOIN sp_service_professionals AS sp
                    ON dpc.professional_vender_id = sp.service_professional_id AND
                    sp.status = '1'
                INNER JOIN sp_services AS s
                    ON er.service_id = s.service_id
                INNER JOIN sp_sub_services AS ss
                    ON er.service_id = ss.service_id AND
                er.sub_service_id = ss.sub_service_id";

        $groupBy = "GROUP BY er.event_requirement_id";

        $Sql = "SELECT dpc.event_id,
                    dpc.event_requirement_id,
                    dpc.professional_vender_id,
                    sp.professional_code,
                    CONCAT(sp.first_name,' ', sp.name) AS professional_name,
                    ss.service_id,
                    s.service_title,
                    ss.sub_service_id,
                    ss.recommomded_service,
                    dpc.start_date,
                    ss.cost
                FROM sp_detailed_event_plan_of_care AS dpc
            " . $join . " 
            WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "
            ";

        if ($this->num_of_rows($this->query($Sql))) {
            return $this->fetch_all_array($Sql);
        }
        else {
            return 0;
        }

    }
}
?>