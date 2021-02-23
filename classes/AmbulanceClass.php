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

        $insertData['amb_id'] = $arg['amb_id'] ;
        $insertData['amb_no'] = $arg['selected_amb'] ;
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
        $patData['pat_lattitude']=$arg['Lat_pat'];
        $patData['pat_langitude']=$arg['lng_pat'];
        $patData['google_pickup_location_lat']=$arg['lat_pick'];
        $patData['google_pickup_location_long']=$arg['lng_pick'];
        $patData['google_drop_location_lat']=$arg['lat_drp'];
        $patData['google_drop_location_long']=$arg['lng_drp'];
        /*
        $arr['Lat_pat']=$_POST['Lat_pat'];
        $arr['lng_pat']=$_POST['lng_pat'];
        $arr['lat_pick']=$_POST['lat_pick'];
        $arr['lng_pick']=$_POST['lng_pick'];
        $arr['lat_drp']=$_POST['lat_drp'];
        $arr['lng_drp']=$_POST['lng_drp'];
        */
        /*
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
        } */
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
      $payments_event_code = mysql_query("SELECT * FROM sp_ems_ambulance  where amb_no='".$arg['selected_amb']."' ");
	    $row2 = mysql_fetch_array($payments_event_code) or die(mysql_error());
		$amb_id=$row2['id'];
		
      

      $createEvent['finalcost']  =   $arg['finalcost'];
      $createEvent['total_km']  =   $arg['total_km'];
      $createEvent['total_km_per'] = $arg['total_km_per'];
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
      $createEvent['amb_id'] = $amb_id;
      $createEvent['date']  =   date("Y-m-d", strtotime($arg['date']));
      $createEvent['time']  =   $arg['time'];
      $createEvent['note']  =   $arg['notes'];
      $createEvent['google_pickup_location_lat']=$arg['lat_pick'];
      $createEvent['google_pickup_location_long']=$arg['lng_pick'];
      $createEvent['google_drop_location_lat']=$arg['lat_drp'];
      $createEvent['google_drop_location_long']=$arg['lng_drp'];
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
        //var_dump($createEvent);die();
        $EventId=$this->query_insert('sp_amb_events',$createEvent);

       return $EventId.'>>'.$RecordId;
    }
    public function amb_event_details($event_code)
    {
        $RecordSql="SELECT se.*,jc.End_odo,sp.first_name,sp.name,sp.google_pickup_location,sp.google_drop_location,chief.ct_type FROM sp_amb_events as se 
                    LEFT JOIN sp_amb_patients as sp ON se.patient_id = sp.patient_id 
                    LEFT JOIN sp_ems_complaint_types as chief ON se.Complaint_type = chief.ct_id 
                    LEFT JOIN sp_amb_jobclosure as jc ON jc.amb_id = se.amb_id 
                    WHERE 1 AND event_status!='3' AND event_code = '".$event_code."' GROUP BY se.event_id";
       $AllRrecord = $this->fetch_all_array($RecordSql);
            
       return $AllRrecord;
    }
    public function amb_EventList($arg)
    { 
        $EmployeesSql="SELECT se.event_id as event_id FROM sp_amb_events as se 
        WHERE 1 AND event_status!='3' GROUP BY se.event_id";
     // echo $EmployeesSql;
        $this->result = $this->query($EmployeesSql);
        if ($this->num_of_rows($this->result))
        {
             
         // die;
            $pager = new PS_Pagination($EmployeesSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                $event = $val_records['event_id'];
                // Getting Record Detail
                $RecordSql="SELECT se.*,sp.first_name,cl.phone_no,cl.first_name,cl.name,sp.name,sp.Gender,sp.Age,sp.google_pickup_location,sp.google_drop_location,chief.ct_type FROM sp_amb_events as se 
                    LEFT JOIN sp_amb_patients as sp ON se.patient_id = sp.patient_id 
                    LEFT JOIN sp_ems_complaint_types as chief ON se.Complaint_type = chief.ct_id 
                    LEFT JOIN sp_amb_callers as cl ON cl.caller_id = se.caller_id 
                    WHERE 1 AND se.event_id = '".$event."' ";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                $this->resultEmployees[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultEmployees))
        {
            $resultArray['data']=$this->resultEmployees;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
       
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
    public function AmbulanceList($arg)
    {
        $EmployeesSql="SELECT amb.*
                    FROM sp_ems_ambulance as amb 
                    WHERE 1 AND amb.is_deleted = '1'";
                   
        $this->result = $this->query($EmployeesSql);
        if ($this->num_of_rows($this->result))
        {
           // echo $arg['pageSize'];
           // echo $arg['pageIndex']; 
         // die;
            $pager = new PS_Pagination($EmployeesSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT amb.*,amb_base.base_name as bs_nm,amb_ty.amb_type,amb_st.amb_status 
                FROM sp_ems_ambulance as amb 
                LEFT JOIN sp_ems_amb_status as amb_st ON amb_st.id = amb.amb_type
                LEFT JOIN sp_ems_amb_type as amb_ty ON amb_ty.id = amb.amb_status
                LEFT JOIN sp_ems_base_location as amb_base ON amb_base.id = amb.base_loc
                WHERE 1 AND amb.id='".$val_records['id']."' ";
                 $RecordResult=$this->fetch_array($this->query($RecordSql));
               // print_r($RecordResult);
                $this->resultEmployees[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultEmployees))
        {
           // var_dump($this->resultEmployees);die();
            $resultArray['data']=$this->resultEmployees;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function AddAmbulance($arg){
        $amb_id = $this->escape($arg['amb_id']);
        $insertData['amb_no']       = $this->escape($arg['amb_no']);
        $insertData['mob_no']       = $this->escape($arg['mobile_no']);
        $insertData['cost_per_km']       = $this->escape($arg['cost_per_km']);
        $insertData['amb_status']       = $this->escape($arg['amb_status']);
        $insertData['amb_type']       = $this->escape($arg['amb_type']);
        $insertData['base_loc']       = $this->escape($arg['base_location']);
        $insertData['address']       = $this->escape($arg['address']);
        $insertData['lat']       = $this->escape($arg['lat']);
        $insertData['long']       = $this->escape($arg['long']);
        if (!empty($amb_id)) {
            $where = "id ='" . $amb_id . "'";
            $RecordId = $this->query_update('sp_ems_ambulance', $insertData, $where); 
            
        } else {
        $insertData['status']       = $this->escape($arg['status']);
        $insertData['added_by']       = $this->escape($arg['added_by']);
        $insertData['added_date']       = $this->escape($arg['added_date']);
        $insertData['is_deleted']       = '1';
        $RecordId=$this->query_insert('sp_ems_ambulance', $insertData);
        }
        if($RecordId)
        {
            return $RecordId;
        }else{
            return 0;
        }
		 

    }
    public function GetambulanceById($arg)
    {
        $amb_id=$this->escape($arg['amb_id']);
        $GetambSql="SELECT amb.*,amb.id as amb_id,amb_ty.id as ty_id,amb_st.id as st_id,amb_base.base_name as bs_nm,amb_ty.amb_type,amb_st.amb_status 
        FROM sp_ems_ambulance as amb 
        LEFT JOIN sp_ems_amb_status as amb_st ON amb_st.id = amb.amb_status
        LEFT JOIN sp_ems_amb_type as amb_ty  ON amb_ty.id = amb.amb_type
        LEFT JOIN sp_ems_base_location as amb_base ON amb_base.id = amb.base_loc
        WHERE amb.id='".$amb_id."'";
        //echo $GetambSql;
        if($this->num_of_rows($this->query($GetambSql)))
        {
            $Professional=$this->fetch_array($this->query($GetambSql));
            return $Professional;
        }
        else 
            return 0;  
    }
    public function ChangeStatus($arg)
    {
        $amb_id                  = $this->escape($arg['amb_id']);
        $status                  = $this->escape($arg['status']);
       // $pre_status              = $this->escape($arg['curr_status']);
        //$istrashDelete           = $this->escape($arg['istrashDelete']);
        $login_user_id           = $this->escape($arg['login_user_id']);

        $ChkAmbSql = "SELECT *
		FROM sp_ems_ambulance 
		WHERE id = '" . $amb_id . "'";

        if ($this->num_of_rows($this->query($ChkAmbSql))) {
			$profDtls = $this->fetch_array($this->query($ChkAmbSql));
            
            // Update Professional Other Details
            $UpdateProfOtherDtls = "UPDATE sp_ems_ambulance SET status = '" . $status . "', last_modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE id = '" . $amb_id . "'";
            $RecordId = $this->query($UpdateProfOtherDtls);
			return $RecordId;
        }
        else {
			return 0;
		}
    }
}