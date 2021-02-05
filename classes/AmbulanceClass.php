<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class AmbulanceClass extends AbstractDB 
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
    public function InsertClosure($arg){
        $insertData['incident_id']  =   $arg['incident_id'];
        $insertData['level_id']  =   $arg['level_id'];
        $insertData['med_id']  =   $arg['med_id'];
        $insertData['inv_id']  =   $arg['inv_id'];
        $insertData['datepicker_from_base']  =   $arg['datepicker_from_base'];
        $insertData['datepicker_from_pickup']  =   $arg['datepicker_from_pickup'];
        $insertData['datepicker_to_drop']  =   $arg['datepicker_to_drop'];
        $insertData['datepicker_to_base']  =   $arg['datepicker_to_base'];
        $insertData['Start_odo']  =   $arg['Start_odo'];
        $insertData['End_odo']  =   $arg['End_odo'];
        $insertData['remark']  =   $arg['remark'];
        $insertData['inc_added_date']  =   $arg['inc_added_date'];
        $insertData['pro_id']  =   $arg['pro_id'];

        $insertData['hospital_id']  =   $arg['hospital_id'];
        $insertData['added_by']  =   $arg['employee_id'];
        $insertData['status'] = '1';
        $insertData['added_date'] = date('Y-m-d H:i:s');

        $updateData['event_status'] = '3';
        $where = "event_code ='".$arg['incident_id']."' ";
        $this->query_update('sp_amb_events',$updateData,$where);

        $RecordId = $this->query_insert('sp_amb_jobclosure',$insertData);
        
        
    }
    public function InsertPayment($arg)
    {
       
        $insertData['event_code']  =   $arg['event_id'];
        $insertData['payment_date']  =   $arg['payment_date'];
        $insertData['PaymentType']  =   $arg['PaymentType'];
        $insertData['amount']  =   $arg['amount'];

        $insertData['Cheque_DD_NEFTNO']  =   $arg['Cheque_DD_NEFTNO'];
        $insertData['Party_Bank_Name']  =   $arg['Party_Bank_Name'] ;
        $insertData['card_no']  =   $arg['card_no'];

        $insertData['Transaction_ID']  =   $arg['Transaction_ID'];
        $insertData['narration']  =   $arg['narration'];
        
        $insertData['hospital_id']  =   $arg['hospital_id'];
        $insertData['added_by']  =   $arg['employee_id'];
        $insertData['status'] = '1';
        $insertData['added_date'] = date('Y-m-d H:i:s');


        $updateData['event_status'] = '2';
        $where = "event_code ='".$arg['event_id']."' ";
        $this->query_update('sp_amb_events',$updateData,$where);

        $RecordId = $this->query_insert('sp_amb_payment',$insertData);

        
    }
    public function InsertAmbCallers($arg)
    {
        
      //Caller Details
        $insertData['purpose_id']  =   $arg['CallType'];
        $insertData['name']  =   $arg['name'];
        $insertData['first_name']  =   $arg['caller_first_name'];
        $insertData['relation']  =   $arg['relation'];
        $insertData['phone_no']  =   $arg['phone_no'];
        $insertData['attended_by'] = $arg['employee_id'];
        $insertData['added_date'] = date('Y-m-d H:i:s');
        $insertData['status'] = '1';
         $RecordId = $this->query_insert('sp_amb_callers',$insertData);
       
       
       //Patient Details
        
        $patData['first_name']  =   $arg['Patient_first_name'];
        $patData['name']  =   $arg['Patient_name'];
        $patData['mobile_no']  =   $arg['Patient_phone_no'];
        $patData['Age']  =   $arg['Age'];
        $patData['Gender']  =   $arg['Gender'];
        $patData['google_location']  =   $arg['google_location'];
        $patData['google_pickup_location']  =   $arg['google_pickup_location'];
        $patData['google_drop_location']  =   $arg['google_drop_location'];
        $patData['status']  =   '1';
        $patData['added_by'] = $arg['employee_id'];
        $patData['added_date'] = date('Y-m-d H:i:s');

        if($arg['google_location'] != '')
        {
       $address = str_replace(" ", "+", $arg['google_location']);
       $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&address=$address&sensor=false&region=$region");
       $json1 = json_decode($json);
       
        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        $patData['pat_lattitude']=$lat;
        $patData['pat_langitude']=$long;
        }
        if($arg['google_pickup_location'] != '')
        {
        $address = str_replace(" ", "+", $arg['google_location']);
           //  $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyD3wZTkqi05uBxq-6ef7NvnxiSWI1Jixls&address=$address&sensor=false&region=$region");
       $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&address=$address&sensor=false&region=$region");
       // $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
        $json = json_decode($json);

        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        $google_pickup_location_lat=$lat;
        $google_pickup_location_long=$long;
        }

        if($arg['google_drop_location'] != '')
        {
        $address = str_replace(" ", "+", $arg['google_location']);
           //  $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyD3wZTkqi05uBxq-6ef7NvnxiSWI1Jixls&address=$address&sensor=false&region=$region");
       $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&address=$address&sensor=false&region=$region");
       // $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
        $json = json_decode($json);

        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        $google_drop_location_lat=$lat;
        $google_drop_location_long=$long;
        }
        $patData['google_pickup_location_lat']=$google_pickup_location_lat;
        $patData['google_pickup_location_long']=$google_pickup_location_long;
        $patData['google_drop_location_lat']=$google_drop_location_lat;
        $patData['google_drop_location_long']=$google_drop_location_long;


        $RecordId_pat = $this->query_insert('sp_amb_patients',$patData);
        
      

      //---------- create Incident ---------------//
      $GetMaxIdSql=mysql_query("SELECT MAX(event_code) as max_event_code FROM sp_amb_events") or die(mysql_error());
        if(mysql_num_rows($GetMaxIdSql) <= 1 )
		{
           $row_id = mysql_fetch_array($GetMaxIdSql) or die(mysql_error());
           $event_code=$row_id['max_event_code'];
        }else{
            $event_code=0;
        }
      //$last_id = $data['last_id'][0]['inc_ref_id'];
      $dt = substr($event_code, 0, 8);
      $today = date('Ymd');
      if($dt == $today){
          $no = substr($event_code, -4);
          $inc_ref_no = str_pad(date('Ymd'), 4, "0", STR_PAD_LEFT) . str_pad(($no+1), 4, "0", STR_PAD_LEFT);
      }else{
          $no = '0000';
          $inc_ref_no = str_pad(date('Ymd'), 4, "0", STR_PAD_LEFT) . str_pad(($no+1), 4, "0", STR_PAD_LEFT);
      }
      

       if( $arg['terminatevalue'] == 'yes' ){
          $createEvent['event_status']  = '2';
          $createEvent['notes_terminate']  =   $arg['notes_terminate']  ;
         $createEvent['terminate_reason_id']  =   $arg['terminate_reason_id'] ;
      } else{
        $createEvent['event_status'] = '1';  
        $createEvent['notes_terminate']  =   '' ;
         $createEvent['terminate_reason_id']  =   '' ;
      }
      $createEvent['finalcost']  =   $arg['finalcost'];
      $createEvent['total_km']  =   $arg['total_km'];
      $createEvent['purpose_id']  =   $arg['CallType'];
      $createEvent['event_code']  =   $inc_ref_no;
      $createEvent['caller_id'] = $RecordId;
      $createEvent['patient_id'] = $RecordId_pat;
      $createEvent['relation'] = $arg['relation'];
      $createEvent['No_of_Patient']  =   $arg['No_of_Patient'];
      $createEvent['Complaint_type']  =   $arg['Complaint_type'];
      $createEvent['google_pickup_location']  =   $arg['google_pickup_location'];
      $createEvent['google_drop_location']  =   $arg['google_drop_location'];
      $createEvent['amb_no']  =   $arg['amb_no'];
      $createEvent['selected_amb']  =   $arg['selected_amb'];
      $createEvent['date']  =   date("Y-m-d", strtotime($arg['date']));
      $createEvent['time']  =   $arg['time'];
      $createEvent['note']  =   $arg['notes'];
      $createEvent['google_pickup_location_lat']=$google_pickup_location_lat;
      $createEvent['google_pickup_location_long']=$google_pickup_location_long;
      $createEvent['google_drop_location_lat']=$google_drop_location_lat;
      $createEvent['google_drop_location_long']=$google_drop_location_long;
       $Invoice_narration='';
      $employee_record_query="SELECT hospital_id FROM sp_employees WHERE employee_id='".$arg['employee_id']."' ";
      $employeee_record = $this->fetch_array($this->query($employee_record_query));
      $hospital_id=$employeee_record['hospital_id'];
      
        $GetMaxbillIdSql=mysql_query("SELECT MAX(bill_no_ref_no) as bill_no_ref_no FROM sp_events where hospital_id='$hospital_id'") or die(mysql_error());
        if(mysql_num_rows($GetMaxbillIdSql) < 1 )
		{
        $row = mysql_fetch_array($GetMaxbillIdSql) or die(mysql_error());
           $Maxbillid=$row['bill_no_ref_no'];
        }else{
            $Maxbillid=0;
        }
       
        
        $createEvent['bill_no_ref_no'] = $Maxbillid + 1;
        $createEvent['event_date'] = date('Y-m-d H:i:s');
        $createEvent['status'] = '2';
        $createEvent['event_status'] = '1';
        $createEvent['added_by'] = $arg['employee_id'];
        $createEvent['Added_through'] = 1;
        $createEvent['Invoice_narration'] = $Invoice_narration;
        $createEvent['added_date'] = date('Y-m-d H:i:s');
        
        $Hospital_branch=mysql_query("SELECT branch FROM sp_hospitals where hospital_id='".$hospital_id."' ") or die(mysql_error());
        $row_Hospital_branch = mysql_fetch_array($Hospital_branch) or die(mysql_error());
        $branch_code=$row_Hospital_branch['branch'];
        $createEvent['branch_code'] = $branch_code;	
        $createEvent['hospital_id'] = $hospital_id;	
        
        $EventId=$this->query_insert('sp_amb_events',$createEvent);

       return $EventId.'>>'.$RecordId;
    }
    public function amb_event_details($event_code)
    {
        $RecordSql="SELECT se.*,sp.first_name,sp.name,sp.google_pickup_location,sp.google_drop_location,chief.ct_type FROM sp_amb_events as se 
                    LEFT JOIN sp_amb_patients as sp ON se.patient_id = sp.patient_id 
                    LEFT JOIN sp_ems_complaint_types as chief ON se.Complaint_type = chief.ct_id 
                    WHERE 1 AND event_status!='3' AND event_code = '".$event_code."' GROUP BY se.event_id";
       $AllRrecord = $this->fetch_all_array($RecordSql);
            
       return $AllRrecord;
    }
    public function amb_EventList()
    { 
        $RecordSql="SELECT se.*,sp.first_name,cl.phone_no,cl.first_name,cl.name,sp.name,sp.Gender,sp.Age,sp.google_pickup_location,sp.google_drop_location,chief.ct_type FROM sp_amb_events as se 
                    LEFT JOIN sp_amb_patients as sp ON se.patient_id = sp.patient_id 
                    LEFT JOIN sp_ems_complaint_types as chief ON se.Complaint_type = chief.ct_id 
                    LEFT JOIN sp_amb_callers as cl ON cl.caller_id = se.caller_id 
                    WHERE 1 AND event_status!='3' GROUP BY se.event_id";
       $AllRrecord = $this->fetch_all_array($RecordSql);
            
       return $AllRrecord;
       
    }
    public function event_payment_details($event)
    { 
    
        $RecordSql="SELECT se.*,sp.first_name,sp.name,sp.google_pickup_location,sp.google_drop_location,chief.ct_type FROM sp_amb_events as se 
                    LEFT JOIN sp_amb_patients as sp ON se.patient_id = sp.patient_id 
                    LEFT JOIN sp_ems_complaint_types as chief ON se.Complaint_type = chief.ct_id 
                    WHERE 1 AND se.event_id = '".$event."' GROUP BY se.event_id";
       $AllRrecord = $this->fetch_all_array($RecordSql);
            
       return $AllRrecord;
       
    }
}