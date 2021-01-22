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
        $insertData['purpose_id']  =   $arg['CallType'];
        $insertData['name']  =   $arg['name'];
        $insertData['first_name']  =   $arg['caller_first_name'];
        $insertData['relation']  =   $arg['relation'];
        $insertData['phone_no']  =   $arg['phone_no'];

       // $insertData['No_of_Patient']  =   $arg['No_of_Patient'];
       // $insertData['Complaint_type']  =   $arg['Complaint_type'];

        
      //  $insertData['Patient_first_name']  =   $arg['Patient_first_name'];
       // $insertData['Patient_name']  =   $arg['Patient_name'];
       // $insertData['Patient_phone_no']  =   $arg['Patient_phone_no'];
       // $insertData['Age']  =   $arg['Age'];
       // $insertData['google_location']  =   $arg['google_location'];
        
       // $insertData['google_pickup_location']  =   $arg['google_pickup_location'];
      //  $insertData['google_drop_location']  =   $arg['google_drop_location'];
      //  $insertData['amb_no']  =   $arg['amb_no'];
       // $insertData['manual_pickup_location']  =   $arg['manual_pickup_location'];
       // $insertData['manual_drop_location']  =   $arg['manual_drop_location'];

      //  $insertData['date']  =   $arg['date'];
	//	$insertData['time']  =   $arg['time'];
      //  $insertData['notes']  =   $arg['notes'];
      //  $insertData['hospital_id']  =   $arg['hospital_id'];
      //  $insertData['employee_id']  =   $arg['employee_id'];
        

        $preWhereC = " and phone_no = '".$arg['phone_no']."'";
        $select_exist = "SELECT caller_id FROM sp_callers WHERE 1 ".$preWhereC."  ";
        if  (mysql_num_rows($this->query($select_exist))) {

        $insertData['last_modified_by'] = $arg['employee_id'];
        $insertData['last_modified_date'] = date('Y-m-d H:i:s');
        $val_existRecord = $this->fetch_array($this->query($select_exist));

        $where = "caller_id ='".$val_existRecord['caller_id']."' ";
        $this->query_update('sp_callers',$insertData,$where);
        $RecordId = $val_existRecord['caller_id'];
        } 
        else 
        {
            $insertData['attended_by'] = $arr['employee_id'];
            $insertData['added_date'] = date('Y-m-d H:i:s');
            $insertData['status'] = '1';
            $RecordId = $this->query_insert('sp_amb_callers',$insertData);
        }
        /*
            //---------- create event ---------------//
            $createEvent['caller_id'] = $RecordId;
            $createEvent['relation'] = $arg['relation'];
            
            // Generate Random Number 
            $GetMaxRecordIdSql="SELECT MAX(event_id) AS MaxId FROM sp_events";
            if ($this->num_of_rows($this->query($GetMaxRecordIdSql))) {
                $MaxRecord=$this->fetch_array($this->query($GetMaxRecordIdSql));
                $getMaxRecordId=$MaxRecord['MaxId'];
            } else {
                $getMaxRecordId=0;
            }
            $prefix='E';
            $EventCode=Generate_Number($prefix,$getMaxRecordId);
            
            //Check is it generated event code is already exits
            
            
            $chk_event_code="SELECT event_id FROM sp_events WHERE event_code='".$EventCode."'";		
            if($this->num_of_rows($this->query($chk_event_code)))
            {
                $createEvent['event_code'] = 'E00'.$RecordId.''.$val;
            }
            else 
            {
                $createEvent['event_code'] = $EventCode;
            }

            $Invoice_narration='';
            $employee_record_query="SELECT hospital_id FROM sp_employees WHERE employee_id=$employee_id";
            $employeee_record = $this->fetch_array($this->query($employee_record_query));
            $hospital_id=$employeee_record['hospital_id'];
            $GetMaxbillIdSql=mysql_query("SELECT MAX(bill_no_ref_no) as bill_no_ref_no FROM sp_events where hospital_id='$hospital_id'") or die(mysql_error());
            $row = mysql_fetch_array($GetMaxbillIdSql) or die(mysql_error());
            $Maxbillid=$row['bill_no_ref_no'];
            $createEvent['purpose_id'] = $arg['purpose_id'];
            $createEvent['bill_no_ref_no'] = $Maxbillid + 1;
            $createEvent['event_date'] = date('Y-m-d H:i:s');
            $createEvent['status'] = '2';
            $createEvent['event_status'] = '1';
            $createEvent['added_by'] = $employee_id;
            $createEvent['Added_through'] = 1;
            $createEvent['Invoice_narration'] = $Invoice_narration;
            $createEvent['CallUniqueID'] = $CallUniqueID;
            $createEvent['added_date'] = date('Y-m-d H:i:s');
    
            
            $Hospital_branch=mysql_query("SELECT branch FROM sp_hospitals where hospital_id='$hospital_id'") or die(mysql_error());
            $row_Hospital_branch = mysql_fetch_array($Hospital_branch) or die(mysql_error());
            $branch_code=$row_Hospital_branch['branch'];
            
            $createEvent['branch_code'] = $branch_code;	
            $createEvent['hospital_id'] = $employeee_record['hospital_id'];	
            $EventId=$this->query_insert('sp_events',$createEvent);
            //unset($_SESSION["CallUniqueID"]);
           // $_SESSION["CallUniqueID"]='';
            // Added Activity Log
            if (!empty($EventId)) {
                $insertActivityArr = array();

                $insertActivityArr['module_type'] = '1';
                $insertActivityArr['module_id']   = '';
                $insertActivityArr['module_name']   = 'Add Caller Details';
                $insertActivityArr['purpose_id']   = $arg['purpose_id'];
                $insertActivityArr['event_id']   = $EventId;
                $insertActivityArr['activity_description'] = "Caller details added successfully. New event (" .  $createEvent['event_code']  . ") is get created by " . $_SESSION['emp_nm'] . " ";
                $insertActivityArr['added_by_type']   = '1'; // 1 For Employee
                $insertActivityArr['added_by_id']   = $employee_id;
                $insertActivityArr['added_by_date']   = date('Y-m-d H:i:s');

                $this->query_insert('sp_user_activity',$insertActivityArr);

                unset($insertActivityArr);
            }	
        */
        return $EventId.'>>'.$RecordId;
    }
}