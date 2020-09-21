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
     * This function is useful for get enquiry report
     */
    public function getEnquiryReport($arg)
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
}
?>