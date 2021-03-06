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
    public function GetAllAvaya_agentid($arg = array())
    {
       $preWhere="";
       $Avaya_agentid="SELECT * FROM sp_avaya_extensions WHERE is_deleted='0' ".$preWhere." ORDER BY id ASC";
       if($this->num_of_rows($this->query($Avaya_agentid)))
       {
          $agentid=$this->fetch_all_array($Avaya_agentid) ;
          return $agentid;
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
    public function sms_send_prof($args){
      $text_msg = $args['msg'];
      $mobile_no=$args['mob_no'];
      $event_code=$args['event_code'];
      $mobile_no =  "8551995260";
      $curl = curl_init();
      var_dump($text_msg);die();
      $message = rawurlencode($text_msg);
      curl_setopt_array($curl, array(
      CURLOPT_URL => "http://chat.chatmybot.in/whatsapp/api/v1/sendmessage?access-token=4197-35YW4IZVOETDQT0MDI&phone=91-".$mobile_no."&content=".$message."&fileName=test.jpg&caption=testingonol&contentType=1",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "",
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      echo $response;
      curl_close($curl);

      if ($err) {
      echo "cURL Error #:" . $err;
      } else {
      echo $response;
      }
    }
    public function getpatifo($pt_id){
      $RecordSql="SELECT sp_pt.*,ser.service_title,sub_ser.recommomded_service FROM patinet_list_enquiry as sp_pt 
                  LEFT JOIN sp_services as ser ON ser.service_id = sp_pt.mainService
                  LEFT JOIN sp_sub_services as sub_ser ON sub_ser.sub_service_id = sp_pt.sub_service
                  WHERE 1  and pt_id='".$pt_id."'  ";
      $RecordResult=$this->fetch_array($this->query($RecordSql));
      return $RecordResult;
      
  }
    public function GetTodayEnquiryCallapi()
{
   $today_date = date("Y-m-d");
   
   $EnquiryCAllSql="SELECT * from patinet_list_enquiry WHERE  status =1 and (added_date BETWEEN '$today_date 00:00:00' AND '$today_date 23:59:59') ";
   if($this->num_of_rows($this->query($EnquiryCAllSql)))
   {
       $EnquiryCAll=$this->fetch_all_array($EnquiryCAllSql) ;
       return $EnquiryCAll;
    }
    else 
        return 0; 
}
    public function sms_send($args){
      
      $text_msg = $args['msg'];
      $mobile_no=$args['mob_no'];
      $event_code=$args['event_code'];
      //$mobile_no =  "8551995260";
      $apiKey = urlencode('DYj0ooG2pfo-150ozYrDn36WfoGBkZOum6v5J76fIk');
     // var_dump($text_msg);die();
      // Message details
      $numbers = array($mobile_no);
      $sender = urlencode('SPEROO');
      $message = rawurlencode($text_msg);
      $numbers = implode(',', $numbers);
      // Prepare data for POST request
      $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
     // var_dump($message);die();
      // Send the POST request with cURL
      $ch = curl_init('https://api.textlocal.in/send/');
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      var_dump($response);die();
      curl_close($ch);

      $today_datetime = date("Y-m-d H:i:s"); 
		$insertData = array();
		$insertData['sms_event_code'] = $event_code;
		$insertData['sms_mobile_no'] = $mobile_no;
		$insertData['sms_text'] = $message;
		$insertData['sms_datetime'] = $today_datetime;
		$RecordId=$this->query_insert('sp_sms_response', $insertData);
		echo $response;
      
      /*$form_url = "http://www.mgage.solutions/SendSMS/sendmsg.php?uname=BVGMEMS&pass=m2v5c2&send=BVGEMS&dest=".urlencode($mobile_no)."&msg=".urlencode($text_msg);
      $data_to_post = array();
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $form_url);
      curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
      $result = curl_exec($curl);
      curl_close($curl);*/
     // var_dump($result);die();
     // $asSMSReponse = explode("-", $send_dr_sms);
      //var_dump($result);die();
      /*$result = array('inc_ref_id' => $inc_id,
          'sms_usertype' => 'Dispatch',
          'sms_res' => $result[0],
          'sms_res_text' => $asSMSReponse[1] ? $asSMSReponse[1] : '',
          'sms_datetime' => $datetime);*/
     
      //$CI->inc_model->insert_sms_response($result);
}
public function GetTodayEnquiryCall()
{
  // $events = mysql_query("SELECT * FROM sp_events where (enquiry_status=1 OR enquiry_status=2) and (service_date_of_Enquiry BETWEEN '$formDate1%' AND '$toDate2%') ORDER BY service_date_of_Enquiry DESC");
      $today_date = date("Y-m-d");
      $today_date = date('Y-m-d', strtotime($today_date . ' +1 days'));
      $today_date1 = date("Y-m-d");
      $today_date1 = date('Y-m-d', strtotime($today_date1 . ' +1 days'));
      $EnquiryCAllSql="SELECT *  FROM sp_events WHERE  (enquiry_status=1 OR enquiry_status=2) and (service_date_of_Enquiry BETWEEN '$today_date 00:00:00' AND '$today_date1 23:59:59') ORDER BY service_date_of_Enquiry DESC ";
       //var_dump($EnquiryCAllSql);
      // echo $EnquiryCAllSql; die();
       if($this->num_of_rows($this->query($EnquiryCAllSql)))
       {
          $EnquiryCAll=$this->fetch_all_array($EnquiryCAllSql) ;
          return $EnquiryCAll;
       }
       else 
           return 0; 
}
public function days_sms($args){
   $text_msg = $args['msg'];
      $mobile_no=$args['mob_no'];
      $curl = curl_init();
      $message = rawurlencode($text_msg);
      curl_setopt_array($curl, array(
      CURLOPT_URL => "http://chat.chatmybot.in/whatsapp/api/v1/sendmessage?access-token=54844-82ef58263a584c2484363dff71736359&phone=91-".$mobile_no."&content=".$message."&fileName=test.jpg&caption=testingonol&contentType=1",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "",
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      echo $response;
      curl_close($curl);

      if ($err) {
      echo "cURL Error #:" . $err;
      } else {
      echo $response;
      }
}

}
//END
?>