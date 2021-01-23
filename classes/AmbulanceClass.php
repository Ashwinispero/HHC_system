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
}