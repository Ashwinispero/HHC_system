<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class commonClass extends AbstractDB 
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
    public function GetAllLocations()
    {
       $LocationsSql="SELECT location_id,location,pin_code FROM sp_locations WHERE status='1' ORDER BY location ASC";
       if($this->num_of_rows($this->query($LocationsSql)))
       {
          $Locations=$this->fetch_all_array($LocationsSql) ;
          return $Locations;
       }
       else 
           return 0; 
    }
    public function GetAllSpecialization()
    {
       $SpecialtySql="SELECT specialty_id,abbreviation FROM sp_specialty WHERE status='1' ORDER BY abbreviation ASC";
       if($this->num_of_rows($this->query($SpecialtySql)))
       {
          $Specialty=$this->fetch_all_array($SpecialtySql) ;
          return $Specialty;
       }
       else 
           return 0; 
    }
    public function GetAllCallPurposes()
    {
       $CallPurposeSql="SELECT purpose_id,name FROM sp_purpose_call WHERE status='1'";
       if($this->num_of_rows($this->query($CallPurposeSql)))
       {
          $CallPurpose=$this->fetch_all_array($CallPurposeSql) ;
          return $CallPurpose;
       }
       else 
           return 0; 
    }
    public function GetAllServices()
    {
       $ServicesSql="SELECT service_id,service_title FROM sp_services WHERE status='1'";
       if($this->num_of_rows($this->query($ServicesSql)))
       {
          $Services=$this->fetch_all_array($ServicesSql) ;
          return $Services;
       }
       else 
           return 0; 
    }
    
    public function GetAllMedicines($arg)
    {
        $preWhere="";
        if(!empty($arg['type']))
        {
           $preWhere=" AND type='".$arg['type']."'"; 
        }
       $MedicinesSql="SELECT medicine_id,name FROM sp_medicines WHERE status='1' ".$preWhere." ORDER BY name ASC";
       if($this->num_of_rows($this->query($MedicinesSql)))
       {
          $Medicines=$this->fetch_all_array($MedicinesSql) ;
          return $Medicines;
       }
       else 
           return 0;
    }
    
    public function GetAllConsumables($arg)
    {
        $preWhere="";
        if(!empty($arg['type']))
        {
           $preWhere=" AND type='".$arg['type']."'"; 
        }
       $ConsumablesSql="SELECT consumable_id,name FROM sp_consumables WHERE status='1' ".$preWhere." ORDER BY name ASC";
       if($this->num_of_rows($this->query($ConsumablesSql)))
       {
          $Consumables=$this->fetch_all_array($ConsumablesSql) ;
          return $Consumables;
       }
       else 
           return 0;
    } 
    
    public function GetAllHospitals($arg = array())
    {
       $preWhere="";
       $HospitalsSql="SELECT hospital_id,hospital_name FROM sp_hospitals WHERE status='1' ".$preWhere." ORDER BY hospital_name ASC";
       if($this->num_of_rows($this->query($HospitalsSql)))
       {
          $Hospitals=$this->fetch_all_array($HospitalsSql) ;
          return $Hospitals;
       }
       else 
           return 0;
    }
    
    /**
	* This function is used for get all subservies 
	*/
	public function getAllSubServices($arg = array())
    {
	   $whereClause = '';
	   if (!empty($arg['service_id'])) {
		   $whereClause = "AND service_id = '" . $arg['service_id'] ."'";
	   }		   
       $subServicesSql = "SELECT sub_service_id, service_id, recommomded_service FROM sp_sub_services WHERE status = '1'  " . $whereClause . "  ";
	   
       if($this->num_of_rows($this->query($subServicesSql)))
       {
          $subServices = $this->fetch_all_array($subServicesSql) ;
          return $subServices;
       }
       else {
           return 0;
	   }
    }
}
//END
?>