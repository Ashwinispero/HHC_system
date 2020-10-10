<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/Exception.php';
require './PHPMailer/PHPMailer.php';
require './PHPMailer/SMTP.php';
$mail = new PHPMailer(true); 

require_once 'inc_classes.php';        
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";        
        include "classes/eventClass.php";
        $eventClass = new eventClass();
        include "classes/employeesClass.php";
        $employeesClass = new employeesClass();
	include "classes/professionalsClass.php";
        $professionalsClass = new professionalsClass();
        include "classes/consultantsClass.php";
        $consultantsClass = new consultantsClass();
        include "classes/commonClass.php";
        $commonClass= new commonClass();
        require_once 'classes/functions.php'; 
?>
<?php
if($_REQUEST['action'] == 'updatePurposeId')
{
    $temp_event_id = $_REQUEST['temp_event_id'];
    $purpose_id = $_REQUEST['purpose_id'];
    $checkExist = "select event_id from sp_events where event_id = '".$temp_event_id."' ";
    if(mysql_num_rows($db->query($checkExist)))
    {
        $updateEvents= "update sp_events set purpose_id='".$purpose_id."' where event_id = '".$temp_event_id."' ";
        $db->query($updateEvents);
    }
}
elseif($_REQUEST['action'] == 'Checkdisconnect')
{
    $phone_no = $_REQUEST['phone_no'];
    $status = $_REQUEST['status'];
    $unic_id = $_REQUEST['unic_id'];
    $disconect_remark = $_REQUEST['disconect_remark'];
    if($status == 1){
        $updateEvents= "update sp_incoming_call set is_deleted='1',dis_conn_massage = '".$disconect_remark."',status='D' where calling_phone_no = '".$phone_no."' AND CallUniqueID='".$unic_id."' ";
        
        $db->query($updateEvents);
        $user = $_SESSION['first_name'];
        $form_url =  "http://192.168.0.131/API/CallResponse.php?user=".$user."&value=END";
        $data_to_post = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $form_url);
        curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
        $result = curl_exec($curl);
        curl_close($curl);
        echo 'Success';
   }
}
elseif($_REQUEST['action'] == 'CheckCallerExist')
{
    $phone_no = $_REQUEST['phone_no'];
    $status = $_REQUEST['status'];
    $unic_id = $_REQUEST['unic_id'];
    //var_dump($status);
    if($status == 1){
        $updateEvents= "update sp_incoming_call set status='C', call_connect_datetime='".date('Y-m-d H:i:s')."' ,message='Connect'  where calling_phone_no = '".$phone_no."' AND CallUniqueID='".$unic_id."' ";
          // var_dump($updateEvents);
        $db->query($updateEvents);
        
        $user = $_SESSION['first_name'];
        $form_url =  "http://192.168.0.131/API/CallResponse.php?user=".$user."&value=ACCEPT";
         
        $data_to_post = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $form_url);
        curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
        $result = curl_exec($curl);
        curl_close($curl);
        //echo $result;
        
   }
    $checkExist = "select caller_id,name,first_name,middle_name from sp_callers where phone_no = '".$phone_no."' ";
    if(mysql_num_rows($db->query($checkExist)))
    {
        $dataPassed = $db->fetch_array($db->query($checkExist));
        if(!empty($dataPassed))
            echo $dataPassed['name']."-".$dataPassed['first_name']."-".$dataPassed['middle_name'];
    }
}
else if($_REQUEST['action']=='submitCaller')
{ 
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $purpose_id=$_POST['purpose_id'];
        $name=strip_tags($_POST['name']);
        $first_name=strip_tags($_POST['caller_first_name']);
        $middle_name=strip_tags($_POST['caller_middle_name']);
        $relation=strip_tags($_POST['relation']);
        $phone_no=strip_tags($_POST['phone_no']);
        $Edit_CallerId = $_REQUEST['Edit_CallerId'];
        $Edit_event_id = $_REQUEST['Edit_event_id'];
		$hospital_id = $_SESSION['hospital_id'];
       /* if($purpose_id=='')
        {
            $success=0;
            $errors[$i++]="Please select purpose of call";
        }
        if($name=='')
        {
            $success=0;
            $errors[$i++]="Please enter name";
        }
        if($phone_no == '')
        {
            $success=0;
            $errors[$i++]="Please enter phone number.";
        }*/
//        $chk_ExistRecord="SELECT caller_id FROM sp_callers WHERE name='".$name."' and phone_no = '".$phone_no."' "; 
//        if(mysql_num_rows($db->query($chk_ExistRecord)))
//        {
//            $success=0;
//            $errors[$i++]="This caller already in use, please choose another one";  
//        }
        if(count($errors))
        {
           // print_r($errors);
           echo 'callerExists'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $arr['purpose_id']=$purpose_id;
            $arr['name']=ucwords(strtolower($name));
            $arr['first_name']=ucwords(strtolower($first_name));
            $arr['middle_name']=ucwords(strtolower($middle_name));
            $arr['relation']=$relation;
            $arr['phone_no']=$phone_no;
            $arr['Edit_CallerId']=$Edit_CallerId;
            $arr['Edit_event_id']=$Edit_event_id;
            $arr['employee_id']=$_SESSION['employee_id'];
			$arr['professional_id']=$_POST['choose_professional_id'];
            $arr['caller_consultant_id']=$_POST['caller_consultant_id'];
			$arr['hospital_id']=$_SESSION['hospital_id'];
            $arr['CallUniqueID']=$_SESSION['CallUniqueID'];
            if(isset($_SESSION['employee_id']))
            {
                $InsertRecord=$eventClass->InsertCallers($arr); 
                if($InsertRecord)
                {
                    echo $InsertRecord; // Insert Record
                    exit;
                }
                else
                {
                   echo 'RecordExist';
                   exit;
                }
            }
            else 
            {
                echo 'SessionExpired';
                exit;
            }
        }
    }
}
else if($_REQUEST['action'] == 'ChecklocationExist')
{
    $pin_code = $_REQUEST['patient_pincode'];
    $checkExist = "select location_id,location,pin_code from sp_locations where pin_code = '".$pin_code."' ";
    if(mysql_num_rows($db->query($checkExist)))
    {
        $dataPassed = $db->fetch_array($db->query($checkExist));
        echo $dataPassed['location'];
    }
}
else if($_REQUEST['action'] == 'generateHHCno')
{
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $exist_hhc_code=$_POST['exist_hhc_code'];
        $temp_event_id=$_POST['temp_event_id'];
        $prv_purpose_id=$_POST['prv_purpose_id'];
        $name=strip_tags($_POST['patient_name']);
        $first_name=strip_tags($_POST['patient_first_name']);
        $middle_name=strip_tags($_POST['patient_middle_name']);
         $Age=strip_tags($_POST['Age']);
		$Gender=strip_tags($_POST['Gender']);
        $residential_address=strip_tags($_POST['residential_address']);
        $permanant_address=strip_tags($_POST['permanant_address']);
        $city_id=strip_tags($_POST['city']);
        $area=strip_tags($_POST['area']);
        $sub_location=strip_tags($_POST['sub_area']);
        $location_id=strip_tags($_POST['patient_location']);
        $google_location=strip_tags($_POST['google_location']);
        $mobile_no=strip_tags($_POST['patient_mobile_no']);
        $phone_no=strip_tags($_POST['patient_phone_no']);
        $email_id=strip_tags($_POST['patient_email_id']);
        $dob=strip_tags($_POST['patientdob']);
        $doctor_id=strip_tags($_POST['doctor_id']);
        $hospital_id=strip_tags($_POST['hospital_id']);
        $consultant_id=strip_tags($_POST['consultant_id']);
        if($prv_purpose_id=='')
        {
            $success=0;
            $errors[$i++]="Please select purpose of call";
        }
        if($name=='')
        {
            $success=0;
            $errors[$i++]="Please enter name";
        }
        if($first_name=='')
        {
            $success=0;
            $errors[$i++]="Please enter first name";
        }
        if($mobile_no == '')
        {
            $success=0;
            $errors[$i++]="Please enter phone number.";
        }
        if($exist_hhc_code == '')
        {
            $chk_ExistRecord="SELECT patient_id FROM sp_patients WHERE name='".$name."' and mobile_no = '".$mobile_no."' "; 
            if(mysql_num_rows($db->query($chk_ExistRecord)))
            {
                $success=0;
                //$errors[$i++]="This record already in use, please choose another one";  
            }
        }
        if(count($errors))
        {
            //print_r($errors);
           echo 'callerExists'; // Validation error/record exists
           //exit;
        }
        else 
        {
            $success=1;
            $arr['purpose_id']=$prv_purpose_id;
            $arr['name']=  ucwords(strtolower($name));
            $arr['first_name']=ucwords(strtolower($first_name));
            $arr['middle_name']=ucwords(strtolower($middle_name));
            $arr['Age']=$Age;
			$arr['Gender']=ucwords(strtolower($Gender));
            $arr['residential_address']=ucwords(strtolower($residential_address));
            $arr['permanant_address']=ucwords(strtolower($permanant_address));
            $arr['city_id']=$city_id;   
            $arr['area']=$area;
            $arr['sub_location']=$sub_location; 
            $arr['location_id']=$location_id;
            $arr['google_location']=$google_location;
            $arr['mobile_no']=$mobile_no;
            $arr['phone_no']=$phone_no;
            $arr['email_id']=strtolower($email_id);
            $arr['dob']=$dob;
            $arr['doctor_id']=$doctor_id;
            $arr['consultant_id']=$consultant_id;
            $arr['temp_event_id']=$temp_event_id;
            $arr['employee_id']=$_SESSION['employee_id'];
            $arr['hospital_id']=$hospital_id;
            $arr['exist_hhc_code']=$exist_hhc_code;
            
            $InsertRecord=$eventClass->InsertPatients($arr); 
            //print_r($InsertRecord);
            //if($InsertRecord == 'Inserted')
            //    echo 'InsertSuccess'; // Insert Record
            //else 
            
            if(!empty($arr['exist_hhc_code']))
            {
               echo "UpdateSuccess";
               exit;
            }
            else 
            {
               echo "InsertSuccess";
               exit; 
            }
            
             //  echo $InsertRecord;
        }

    }
}
else if($_REQUEST['action'] == "DoctorsConsultantChange")
{
    $doctor_consId = $_REQUEST['doctor_consId'];
    $type = $_REQUEST['type'];
    $selectRecord = "select doctors_consultants_id,name,email_id,mobile_no,phone_no,type,status from sp_doctors_consultants where doctors_consultants_id = '".$doctor_consId."' ";        
    $valDoctors = $db->fetch_array($db->query($selectRecord));
    echo '<div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Contact No:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="familyDocmobile_no" name="familyDocmobile_no" value="'.$valDoctors['mobile_no'].'">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Email id:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="familyDocemail_id" name="familyDocemail_id" value="'.$valDoctors['email_id'].'" >
                    </div>
                  </div>';
}
else if($_REQUEST['action'] == 'LocationSelect')
{
    $preWhere="";
    $GroupBy="";
    $type_id = $_REQUEST['type_id'];
    $type = $_REQUEST['type'];
    $LocationId=$_REQUEST['LocationId'];
    $PinCode=$_REQUEST['PinCode'];
    if($type == 'location')
    {
        $arrs['location'] = $type_id;
        $GroupBy="GROUP BY pin_code";
    }
    else
    {
        $arrs['pin_code'] =$type_id;  
    }
    
    if($PinCode)
    {
       $preWhere="AND pin_code='".$PinCode."'";
       
    }

    $value = $eventClass->LocationList($arrs);
    $selectRecord = "select location_id,location,pin_code,status from sp_locations where status='1' ".$preWhere." ".$GroupBy." ";        
    $all_list = $db->fetch_all_array($selectRecord);
    if($type == 'location')
    {
        echo '<select class="form-control" id="patient_pin_code" name="patient_pin_code" onchange="return ChangeLocation(this.value,\'pin\');">
            <option value="">Pin Code</option>';
                foreach($all_list as $key=>$valLocation)
                {
                    if($value == $valLocation['pin_code'])
                        echo '<option value="'.$valLocation['pin_code'].'" selected="selected">'.$valLocation['pin_code'].'</option>';   
                    else
                        echo '<option value="'.$valLocation['pin_code'].'" >'.$valLocation['pin_code'].'</option>';   
                }
        echo '</select>';
    }
    else
    {
        echo '<select class="validate[required] chosen-select form-control" id="patient_location" name="patient_location" onchange="return ChangeLocation(this.value,\'location\');">
                <option value="">Location</option>';
                foreach($all_list as $key=>$valLocation)
                {
                    if($value == $valLocation['location'])
                        echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';   
                    else
                        echo '<option value="'.$valLocation['location_id'].'" >'.$valLocation['location'].'</option>';   
                }
        echo '</select>';
    }
}
else if($_REQUEST['action'] == 'SubmitRequirement')
{
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        //$sub_service_id=$_POST['sub_service_id'];
        $purpose_id_temp=$_POST['purpose_id_temp'];
        $event_id_temp=strip_tags($_POST['event_id_temp']);
        $patient_id_temp=strip_tags($_POST['patient_id_temp']);
		$hospital_name=strip_tags($_POST['hospital_name']);
		$Consultant=strip_tags($_POST['Consultant']);
		
		
        $notes=  strip_tags($_POST['notes']);
        if($purpose_id_temp=='')
        {
            $success=0;
            $errors[$i++]="Please select purpose of call";
        }
        if($sub_service_id=='')
        {
            $success=0;
            //$errors[$i++]="Please select service";
        }
        if($patient_id_temp == '')
        {
            $success=0;
            $errors[$i++]="Please enter patient information";
        }
        
        if(count($errors))
        {
            //print_r($errors);
           echo 'callerExists'; // Validation error/record exists
        }
        else 
        {
            $success=1;
            $arr['purpose_id']=$purpose_id_temp;
            //$arr['sub_service_id']=$sub_service_id;
            $arr['requireservices']=$_REQUEST['requireservices'];
            $arr['event_id_temp']=$event_id_temp;
	    $arr['hospital_name']=$hospital_name;
            $arr['Consultant']=$Consultant;
            $arr['notes']=ucfirst(strtolower($notes));
            $arr['employee_id']=$_SESSION['employee_id'];
            $InsertRecord=$eventClass->InsertRequirements($arr); 
            //print_r($InsertRecord);
            //if($InsertRecord == 'Inserted')
                echo $event_id_temp; // Insert Record
            //else 
            //   echo $event_id_temp;
        }

    }
}
else if($_REQUEST['action']=='vw_share_with_hcm')
{
    $event_id=$db->escape($_REQUEST['event_id']);

    // Getting HCM Details
    $recArgs['pageIndex']='1';
    $recArgs['pageSize']='all';
    $recArgs['isHCM']='1';
    $GetHCM=$employeesClass->EmployeesList($recArgs);
    $recList=$GetHCM['data'];
    $recListCount=$GetHCM['count']; 
    
   // echo '<pre>';
   // print_r($recList);
   // echo '</pre>';
    
    
    
    // Getting All Specialization
    // $GetSpecialization=$commonClass->GetAllSpecialization();
    // Getting All Locations
    $GetLocation=$commonClass->GetAllLocations();
    ?>
       <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">SHARE WITH HCM</h4>
       </div>
        <div class="modal-body">
        	<label>Filter By :</label>  
            <div class="clearfix"></div>
            <form class="form-inline">
                <div class="filter_content">
                    <input type="hidden" name="event_id" id="event_id" value="<?php echo $event_id; ?>" />
                    <div class="form-group marginright5" style="width:25%;">
<!--                        <label class="select-box-lbl">-->
                            <select name="employee_id" id="employee_id" class="chosen-select form-control" onchange="SearchRecord(this.value);">
                                <option value="">Name</option>
                                <?php 
                                    if($recListCount > 0)
                                    {   
                                        foreach($recList AS $key=>$valHCM)
                                        {
                                            if($_POST['employee_id'] == $valHCM['employee_id'])
                                                echo '<option value="'.$valHCM['employee_id'].'" selected="selected">'.$valHCM['name'].'</option>';
                                            else
                                                echo '<option value="'.$valHCM['employee_id'].'">'.$valHCM['name'].'</option>';
                                        }
                                    }
                                ?>
                            </select>
<!--                        </label>-->
                    </div>
                    <div class="form-group marginright5" style="width:25%;">
                        <input type="text" class="form-control" name="specialty" id="specialty" placeholder="Search Specialty" value="<?php if(isset($_POST['specialty'])) { echo $_POST['specialty']; } else { echo ""; } ?>" onblur="SearchRecord(this.value);" />
                    </div>
                    <!--
                    <div class="value dropdown" id="specialty_content">
                         <label>
                             <select name="selected_specialty_id" id="selected_specialty_id" onchange="SelectLocation(this.value);">
                                <option value="">Specialization</option>
                                 <?php  /*
                                    if(!empty($GetSpecialization))
                                    {
                                        foreach($GetSpecialization AS $key=>$valSpecialty)
                                        {
                                            if($_POST['selected_specialty'] == $valSpecialty['specialty_id'])
                                                echo '<option value="'.$valSpecialty['specialty_id'].'" selected="selected">'.$valSpecialty['abbreviation'].'</option>';
                                            else
                                                echo '<option value="'.$valSpecialty['specialty_id'].'">'.$valSpecialty['abbreviation'].'</option>';
                                        }
                                    }
                                    
                                    */
                                ?>
                            </select> 
                         </label>
                    </div>
                    -->
                    <div class="form-group marginright5" style="width:25%;">
<!--                        <label class="select-box-lbl">-->
                            <select name="location_id" class="chosen-select form-control" id="location_id" onchange="SearchRecord(this.value);">
                                <option value="">Geographic Location</option>
                                 <?php
                                    if(!empty($GetLocation))
                                    {
                                        foreach($GetLocation AS $key=>$valLocation)
                                        {
                                            if($_POST['location_id'] == $valLocation['location_id'])
                                                echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                            else
                                                echo '<option value="'.$valLocation['location_id'].'">'.$valLocation['location'].'</option>';
                                        } 
                                    }
                                ?>
                            </select> 
<!--                        </label>-->
                    </div>
                   
                    <div class="checkbox">
                    <label>
                        <input type="checkbox" class="form-control" name="chk_traffic_load" id="chk_traffic_load" value="" style="width:auto !important;"> Traffic Load
                    </label>
                    </div>
                </div>
            </form>
                <div class="clearfix"></div>
                <div class="share_table_content">
                    <table cellpadding="0" cellspacing="0" class="table table-bordered table-hover"> 
                        <tr>
                            <th width="10%">Prof.Id</th>
                            <th width="23%">Name</th>
                            <th width="23%">Specialization</th>
                            <th width="19%">Location</th>
                            <th width="25%">Traffic</th>
                        </tr>
                        <?php
                            if($recListCount > 0)
                            {
                              foreach($recList AS $key=>$valHCM)
                              {
                                  // Getting Traffic Details 
                                  $getTrafficSql="SELECT event_share_id FROM sp_event_share_hcm WHERE assigned_to='".$valHCM['employee_id']."' AND status='1'";
                                  if($db->num_of_rows($db->query($getTrafficSql)))
                                  {
                                      $traffic=$db->num_of_rows($db->query($getTrafficSql));
                                  }
                                  else 
                                  {
                                     $traffic=0; 
                                  }
                                echo '<tr>
                                          <td width="10%">'.$valHCM['employee_code'].'</td> 
                                          <td width="23%">'.$valHCM['name'].'</td>
                                          <td width="23%">'.$valHCM['specialization'].'</td>
                                          <td width="19%">'.$valHCM['locationNm'].'</td>
                                          <td width="25%">'.$traffic.' Pending  <input type="button" name="btn_share" id="btn_share" value="Share" class="btn btn-share" onclick="return ShareEventToHCM('.$valHCM['employee_id'].','.$event_id.')"></td>
                                      </tr>';
                              }
                            }
                            else 
                            {
                                echo '<tr><td colspan="6" style="text-align:center;color:#FF0000;">No Record Found...</td></tr>';
                            }
                        ?>

                    </table>
                </div>
        </div>
    <?php
}
else if($_REQUEST['action']=='share_event_to_hcm')
{
    $arr['event_id']=$db->escape($_REQUEST['event_id']); 
    $arr['assigned_to']=$db->escape($_REQUEST['employee_id']);
    $arr['assigned_by']=$db->escape($_REQUEST['login_user_id']); 
    $arr['status']='1'; 
    $arr['added_by']=$db->escape($_REQUEST['login_user_id']); 
    $arr['added_date']=date('Y-m-d H:i:s');
    $arr['modified_by']=$db->escape($_REQUEST['login_user_id']); 
    $arr['last_modified_date']=date('Y-m-d H:i:s');
    $InsertRecord=$eventClass->AssignEventWithHCM($arr);
    if(!empty($InsertRecord))
    {
        echo 'success';
        exit;
    }
    else 
    {
        echo 'error';
        exit;
    }
}
else if($_REQUEST['action']=='load_share_event_table_content')
{
   $arr['event_id']=$db->escape($_REQUEST['event_id']);
   $arr['employee_id']=$db->escape($_REQUEST['employee_id']);
   $arr['location_id']=$db->escape($_REQUEST['location_id']);
   $arr['specialty']=$db->escape($_REQUEST['specialty']);
   // Getting Details 
   $GetDetails=$eventClass->EventShareWithHCM($arr);
    echo '<table cellpadding="0" cellspacing="0" class="table table-bordered table-hover">
         <tr>
             <th width="10%">Prof.Id</th>
             <th width="23%">Name</th>
             <th width="23%">Specialization</th>
             <th width="19%">Location</th>
             <th width="25%">Traffic</th>
         </tr>';
           if(!empty($GetDetails))
           {
               foreach($GetDetails AS $key=>$ValRecords)
               {
                  echo '<tr>
                           <td width="10%">'.$ValRecords['employee_code'].'</td> 
                           <td width="23%">'.$ValRecords['name'].'</td>
                           <td width="23%">'.$ValRecords['specialization'].'</td>
                           <td width="19%">'.$ValRecords['locationNm'].'</td>
                           <td width="25%">'.$ValRecords['Traffic'].' Pending  <input type="button" name="btn_share" id="btn_share" class="btn btn-share" value="Share" onclick="return ShareEventToHCM('.$ValRecords['employee_id'].','.$arr['event_id'].')"></td>
                       </tr>'; 
               }
           }
           else 
               echo '<tr><td colspan="6" style="text-align:center;color:#FF0000;">No Record Found...</td></tr>';

     echo '</table>';   
}
else if ($_REQUEST['action'] == 'submitPlanofCare')
{
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $AllReqSel = $_POST['AllReqSel'];
        $explodeReq = explode(',',$AllReqSel);

        for($i=0;$i<count($explodeReq);$i++)
        {
            $argPass['event_requirement_id'] = $explodeReq[$i];
            //$argPass['service_date'] = $_POST['eve_date_'.$explodeReq[$i]];
            //$argPass['start_date'] = $_POST['starttime_'.$explodeReq[$i]];
            //$argPass['end_date'] = $_POST['endtime_'.$explodeReq[$i]];
            $argPass['event_id'] = $_POST['event_id'];            
            $argPass['employee_id'] = $_SESSION['employee_id'];            
            $argPass['extras'] = $_POST['PlanCareextras_'.$explodeReq[$i]];
            $argPass['finalcost_eve'] = $_POST['finalcost_eve'];

            /* Discount Code start here */
            $argPass['discount_type']   = $_POST['discount_id'];
            $argPass['discount_value']  = $_POST['discount_amount'];
            $argPass['discount_amount'] = $_POST['discountCost_eve'];
            $argPass['discount_narration'] = $_POST['discount_narration'];

            if ($argPass['discount_narration'] == 'Other') {
                $argPass['invoice_narration_desc'] = $_POST['discount_narration_content'];
            }

            if (!empty($argPass['discount_amount'])) {
                $argPass['finalcost_eve']   = $_POST['finalCostWithDiscount_eve'];
            }
            /* Discount Code ends here */
            
            $InsertPlanCare = $eventClass->InsertPlanOfCare($argPass);

            if (!empty($InsertPlanCare)) {
                $InsertPlanOfCareDetails = $eventClass->InsertDetailPlanOfCare($argPass);
                if (!empty($InsertPlanOfCareDetails)) {
                    echo $InsertPlanCare;
                } else {
                    echo $InsertPlanCare;
                }
            }
        }
    }
}

/*else if($_REQUEST['action']=='SendReportByEmail_patinet')
{
	$event_id=$db->escape($_REQUEST['event_id']);
	$file_nm=$db->escape($_REQUEST['file_nm']);
    $my_path="eventsPDF/"; 
	$subject="Event Summary of SPERO";
    $message= "ashwini";
    
    $headers="From: no-reply@".$siteURL."\nContent-Type:text/html;charset=iso-8859-1";
    $return_path = "no-reply@".$siteURL."";
    $replyto="no-reply@spero.in";
    $my_file=$file_nm;                   
    $my_mail = "info@sperohealthcare.in";
    $my_replyto = "info@sperohealthcare.in";
    $client_email="ashwinik.speroinfosystems@gmail.com";
   // $my_name=$loginUserNm; // from Name
   
    // Send Email to consultant 
    if(mail_attachment($my_file, $my_path,$client_email, $my_mail, $my_replyto, $subject, $message))
    {
        echo "success";
        exit;
    }
    else 
    {
        echo "error";
        exit;
    } 
}*/
else if($_REQUEST['action']=='SendReportByEmail')
{
    
 ini_set('max_execution_time',1000);   
 
    // Getting Details of login user 
    $arr['employee_id']=$_SESSION['employee_id'];
    $loginUserDtls=$employeesClass->GetEmployeeById($arr);
    
    if(!empty($loginUserDtls))
    {
        $loginUserNm =$loginUserDtls['name'];
        $loginUserEmail =$loginUserDtls['email_id'];
    }
    $event_id=$db->escape($_REQUEST['event_id']);
    $consultant_id=$db->escape($_REQUEST['consultant_id']);
    
    if(!empty($consultant_id))
    {
        $ConsultantDtls=$consultantsClass->GetConsultantById($arr);
        
        if(!empty($ConsultantDtls))
        {
            $ConsultantNm=$ConsultantDtls['name'];
        }  
    }
    $user_email=$db->escape($_REQUEST['email_id']);
    $user_email_msg=$db->escape($_REQUEST['email_msg']);
    $file_nm=$db->escape($_REQUEST['file_nm']);
    $my_path="eventsPDF/"; 
    
    // Getting File Content 
    if($ConsultantNm)
        $ConsultantNm = $ConsultantNm;
    else
        $ConsultantNm = " User";
    if($user_email_msg)
    { 
        $msgText = '<tr>
                        <td>'.$user_email_msg.'</td>
                    </tr>';    
    }
  
   
    
    
    
    $subject="Event Summary of SPERO";
    $message= "<table cellspacing='5' cellpadding='5' width='90%' >
                    <tr><td>Dear ".$ConsultantNm.",</td></tr>
                    ".$msgText."
                    <tr>
                        <td>Please find the attachement of patient detail.</td>
                    </tr>
                    <tr><td>Regards,</td></tr>  
                    <tr><td ><a href='".$siteURL."'>http://sperohealthcare.in</a></td></tr>
                </table>";
    
   $headers="From: no-reply@".$siteURL."\nContent-Type:text/html;charset=iso-8859-1";
    $return_path = "no-reply@".$siteURL."";
    $replyto="no-reply@spero.in";
    $my_file=$file_nm;                   
    $my_mail = "info@sperohealthcare.in";
    $my_replyto = "info@sperohealthcare.in";
    $client_email=$user_email;
    $my_name=$loginUserNm; // from Name
    
   
     
    try {
        

 // Send Email to consultant 
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 's45-40-136-143.secureserver.net';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'noreply@sperocloud.com';                 // SMTP username
    $mail->Password = 'p-UP?4KhOd)#';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;     
    $mail->setFrom('noreply@sperocloud.com', 'Spero Healthcare Innovations Pvt Ltd');
    $mail->addAddress($client_email); 
  $mail->addAttachment('./eventsPDF/'.$my_file);
	 
	  $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
      
        
  
    
    if($mail->send())
    {
        echo "success";
        exit;
    }
    else 
    {
        echo "error";
        exit;
    } 
    
    } catch (Exception $e) { print_r($e); }
}
else if($_REQUEST['action'] == 'submitProfessional')
{
    $service_pass_id = $_REQUEST['service_id'];
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $AllSel = $_POST['professionals_'.$service_pass_id];
      //print_r($AllSel);
        $service_id = $_POST['prof_service_id_'.$service_pass_id];
        $profes_event_id = $_POST['profes_event_id'];
        $select_eventReq = "SELECT event_requirement_id FROM sp_event_requirements WHERE event_id = '".$profes_event_id."' AND service_id='".$service_id."' ";
        //$datarequirement = $db->fetch_array($db->query($select_eventReq));
        $ptr_date = $db->fetch_all_array($select_eventReq);
        foreach($ptr_date as $key=>$datarequirement)
        {
            $select_plancare = "SELECT plan_of_care_id FROM sp_event_plan_of_care WHERE event_id = '".$profes_event_id."' AND event_requirement_id='".$datarequirement['event_requirement_id']."' ";
            $dataPlancare = $db->fetch_array($db->query($select_plancare));          
            $argPass['professional_vender_id'] = $AllSel;
            $argPass['event_requirement_id'] = $datarequirement['event_requirement_id'];
            $argPass['plan_of_care_id'] = $dataPlancare['plan_of_care_id'];
            $argPass['event_id'] = $_REQUEST['profes_event_id'];            
            $argPass['added_by'] = $_SESSION['employee_id'];            
            $argPass['service_id'] = $service_pass_id;        
            $argPass['status'] = '1';
            $InsertRecords=$eventClass->InsertProfessional($argPass);
        }
        
       // echo $InsertRecords;
    }
}
else if($_REQUEST['action'] == 'SubmitJobSum')
{
    $service_pass_ids = $_REQUEST['services'];
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $event_professional_ids =$_REQUEST['event_professional_id_'.$service_pass_ids];
        if(count($event_professional_ids))
        {
            for($i=0;$i<count($event_professional_ids);$i++)
            {
                $arr['event_id']=$_REQUEST['event_id'];
                $arr['service_id']=$service_pass_ids;
                $arr['event_professional_id']=$event_professional_ids[$i];
                
                // Getting Event Vendor Details
                
                if(!empty($event_professional_ids[$i]))
                {
                    $GetProfessionalSql="SELECT event_professional_id,professional_vender_id FROM sp_event_professional WHERE event_professional_id='".$event_professional_ids[$i]."'";
                    $GetProfessional=$db->fetch_array($db->query($GetProfessionalSql));
                    
                    if(!empty($GetProfessional))
                      $arr['professional_vender_id']=$GetProfessional['professional_vender_id'];
                    else 
                      $arr['professional_vender_id']="";
                }
                $arr['reporting_instruction']=$_REQUEST['reporting_instruction_'.$service_pass_ids.'_'.$event_professional_ids[$i]];
                $clicked_btn_type = $_REQUEST['clicked_btn_type_'.$service_pass_ids];
                $arr['report_status']='2';
                $arr['type']=$clicked_btn_type;
                $arr['modified_by']=$_REQUEST['login_user_id'];
                $arr['last_modified_date']=date('Y-m-d H:i:s');
                $arr['status']='1';
                $arr['added_by']=$_REQUEST['login_user_id'];
                $arr['added_date']=date('Y-m-d H:i:s');
                $InsertRecords=$eventClass->InsertJobSummary($arr);
                if($InsertRecords)
                {
                    $success=1;
                    // Get Professional Detail 
                    $args['service_professional_id']=$arr['professional_vender_id'];
                    $professionalDtls=$professionalsClass->GetProfessionalById($args);
                    //print_r($professionalDtls);
                    unset($args);
                    
                    // Get Event Details 
                    $args['event_id']=$arr['event_id'];
                    $EventDtls=$eventClass->GetEvent($args);                    
                    
                    // Get Service Details
                    $recListResponseJob= $eventClass->SelectedPlanCareServices($args);
                    $recListJob=$recListResponseJob['data'];
                    $recJobListCount=$recListResponseJob['count'];                    
                    if($recJobListCount > 0)
                    {
                        $msgContent="";
                         foreach($recListJob as $key=>$recJobValue)
                         {
                             $GetEventReqSql="SELECT event_requirement_id,event_id,service_id FROM sp_event_requirements WHERE event_id='".$args['event_id']."'";
                             $EventRequirement=$db->fetch_all_array($GetEventReqSql);
                             if(!empty($EventRequirement))
                             {
                                 
                                 $msgContent ='<div>
                                        <table class="table table-bordered-job" cellspacing="0" width="100%" style="border:1px solid #00cfcb; border-collapse:collapse;">
                                            <thead>
                                              <tr style="border:1px solid #00cfcb; border-collapse:collapse; background:#00cfcb;">
                                                <th colspan="4" width="100%" style="padding:5px; font-size:14px; color:#fff;">Service Details</th> 
                                              </tr>  
                                              <tr style="border:1px solid #00cfcb; border-collapse:collapse;">
                                                <th style="border:1px solid #00cfcb;  border-collapse:collapse; padding:5px;">Service Name</th>
                                                <th style="border:1px solid #00cfcb;  border-collapse:collapse; padding:5px;">Recommended Service</th>
                                                <th style="border:1px solid #00cfcb;  border-collapse:collapse; padding:5px;">Date Time</th>
                                                <th style="border:1px solid #00cfcb;  border-collapse:collapse; padding:5px;">Reporting Instructions</th>
                                              </tr>
                                            </thead>
                                            <tbody>';
                                 $msgtimecount = '1';
                                                    foreach ($EventRequirement as $key=>$ValRequirement)
                                                    { 
                                                        // Getting Service Name 
                                                        $GetServiceSql="SELECT service_title FROM sp_services WHERE service_id='".$ValRequirement['service_id']."'";
                                                        $GetService=$db->fetch_array($db->query($GetServiceSql));
                                                        
                                                        if(!empty($GetService))
                                                            $service_name=$GetService['service_title'];
                                                        
                                                        $msgContent .='<tr>'
                                                                    .'<td style="border:1px solid #00cfcb; border-collapse:collapse; padding:5px;">'.$service_name.'</td>'
                                                                    .'<td style="border:1px solid #00cfcb; border-collapse:collapse; padding:5px;"></td>'
                                                                    .'<td style="border:1px solid #00cfcb; border-collapse:collapse; padding:5px;"></td>'
                                                                    .'<td style="border:1px solid #00cfcb; border-collapse:collapse; padding:5px;"></td>'
                                                                    .'</tr>';
                                                        
                                                        $selected_Services = "SELECT er.event_requirement_id,er.sub_service_id,poc.service_date,poc.service_date_to,poc.start_date,poc.end_date FROM "
                                                                            . " sp_event_requirements as er LEFT JOIN sp_event_plan_of_care as poc ON er.event_requirement_id = poc.event_requirement_id "
                                                                            . " where er.service_id = '".$ValRequirement['service_id']."' and er.event_id = '".$ValRequirement['event_id']."' and er.status='1' and er.event_requirement_id='".$ValRequirement['event_requirement_id']."' ";

                                                        $ptr_selSertvices = $db->fetch_all_array($selected_Services);
                                                        foreach($ptr_selSertvices as $key=>$valSelcServ)
                                                        {
                                                            // Getting Recommonded Service Name 
                                                            $selectTitle = "select recommomded_service from sp_sub_services where sub_service_id = '".$valSelcServ['sub_service_id']."'";
                                                            $valRecService = $db->fetch_array($db->query($selectTitle));
                                                            $recommomded_service=$valRecService['recommomded_service'];
                                                            
                                                             // echo '<pre>';
                                                       // print_r($ptr_selSertvices);
                                                      //  echo '</pre>';
                                                            
                                                            // Getting Job Summary Description By Professional vender
                                                            $GetJobSummarySql="SELECT reporting_instruction FROM sp_event_job_summary WHERE event_id='".$ValRequirement['event_id']."' AND service_id='".$ValRequirement['service_id']."' AND professional_vender_id='".$arr['professional_vender_id']."'";
                                                            $GetJobSummary=$db->fetch_array($db->query($GetJobSummarySql));
                                                            $service_date = '';
                                                            $serviceTime='';
                                                            if(date('d-m-Y',strtotime($valSelcServ['service_date']))==date('d-m-Y',strtotime($valSelcServ['service_date_to'])))
                                                                $service_date= date('d-m-Y',strtotime($valSelcServ['service_date']));
                                                            else 
                                                                $service_date=date('d-m-Y',strtotime($valSelcServ['service_date'])).' to '.date('d-m-Y',strtotime($valSelcServ['service_date_to']));
                                                            
                                                            $service_date1 = date('d-m-Y',strtotime($valSelcServ['service_date'])).' to '.date('d-m-Y',strtotime($valSelcServ['service_date_to']));
                                                            $serviceTime=$valSelcServ['start_date'].' to '.$valSelcServ['end_date'];
                                                            $msgContent .='<tr>'
                                                                            . '<td style="border:1px solid #00cfcb; border-collapse:collapse; padding:5px;"></td>'                                                       
                                                                            . '<td style="border:1px solid #00cfcb; border-collapse:collapse; padding:5px;">'.rtrim($recommomded_service).'</td>'
                                                                            . '<td style="border:1px solid #00cfcb; border-collapse:collapse; padding:5px;">'.rtrim($service_date)."<br/>(".rtrim($serviceTime).")".'</td>'
                                                                            . '<td style="border:1px solid #00cfcb; border-collapse:collapse; padding:5px;">'.$GetJobSummary['reporting_instruction'].'</td>'
                                                                           .'</tr>';
                                                            $dateofService .= " Date".$msgtimecount." : ".$service_date1." Reporting time : ".$serviceTime;
                                                            //$timeofService .= ;
                                                            unset($service_name);
                                                            unset($recommomded_service);
                                                            unset($service_date);
                                                            unset($serviceTime); 
                                                            //unset($serviceTime); 
                                                            $reporintinst = $GetJobSummary['reporting_instruction'];
                                                            $msgtimecount++;
                                                        } 
                                                        
                                                        
                                                    }
                                                    
                                                    
                                                
                            $msgContent .='</tbody>
                                        </table>
                                    </div>';
                            //$msgContent = '';
                             } 
                         }   
                    }
                   //echo $msgContent;
                    unset($args);

                    // Get Patient Details 
                    $args['patient_id']=$EventDtls['patient_id'];
                    $PatientDtls=$eventClass->GetPatientById($args);                    

                    if($arr['type']=='1')
                    {
                        $msgtcount = 1;
                        $select_jobsummarydiv = "select service_id,event_professional_id,event_id,reporting_instruction from sp_event_job_summary where event_id = '".$_REQUEST['event_id']."' and service_id= '".$service_pass_ids."'";
                        $val_job_summarys = $db->fetch_all_array($select_jobsummarydiv);
                        foreach($val_job_summarys as $key=>$allRecJobSum)
                        {
                            $service_id = $allRecJobSum['service_id'];
                            $event_professional_id = $allRecJobSum['event_professional_id'];
                            $select_event_req = "select event_requirement_id from sp_event_professional where event_professional_id = '".$event_professional_id."' ";
                            $valReqr = $db->fetch_array($db->query($select_event_req));
                            $selectPlanofCsare = "select service_date,service_date_to,start_date,end_date from sp_event_plan_of_care where event_requirement_id = '".$valReqr['event_requirement_id']."'";
                            $valPlanofcare = $db->fetch_all_array($selectPlanofCsare);
                            foreach($valPlanofcare as $key=>$valDatData)
                            {
                                $valDatData['service_date'];
                                if(date('d-m-Y',strtotime($valDatData['service_date']))==date('d-m-Y',strtotime($valDatData['service_date_to'])))
                                    $service_date= date('d-m-Y',strtotime($valDatData['service_date']));
                                else 
                                    $service_date=date('d-m-Y',strtotime($valDatData['service_date'])).' to '.date('d-m-Y',strtotime($valDatData['service_date_to']));

                                $service_datenew1 = date('d-m-Y',strtotime($valDatData['service_date'])).' to '.date('d-m-Y',strtotime($valDatData['service_date_to']));
                                $serviceTimenew=$valDatData['start_date'].' to '.$valDatData['end_date'];
                                $serviceTime=$valDatData['start_date'];
                                
                                $dateofServicenew .= " Date".$msgtcount." : ".$service_datenew1." Reporting time : ".$serviceTimenew;
                                $dateofServicepatient .= " Date".$msgtcount." : ".$service_datenew1." Reporting time : ".$serviceTime;
                                
                                $msgtcount++;
                            }
                            $reporting_instructionNew = $allRecJobSum['reporting_instruction'];
                        }
                        // Send Message 
                        $mobile_no=$professionalDtls['mobile_no'];
                        /*     $txtMsg1   send to prof/HCM     */
                        // Patient name   // Contact NO:// address   // start date - end date   //retporting time
                        
                        /* ------------ textmsg3 send to pateint ----------*/
                        // professional name : // contact No //Reporting time
                        
                        $argsDoc['patient_id']=$EventDtls['patient_id'];
                        $argsDoc['event_id']=$_REQUEST['event_id'];
                        $argsDoc['type']='2';
                        $DocDtls=$eventClass->GetConsultantByPatient($argsDoc); 
                        
                                                                        
                        $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";

                        //.............. send msg to professional .......//
						$query=mysql_query("SELECT * FROM sp_events where event_id='".$ValRequirement['event_id']."'") or die(mysql_error());
															$row = mysql_fetch_array($query) or die(mysql_error());
															$caller_id=$row['caller_id'];
															$caller_detail=mysql_query("SELECT * FROM sp_callers where caller_id='".$caller_id."'") or die(mysql_error());
															$row1 = mysql_fetch_array($caller_detail) or die(mysql_error());
															$phone_no=$row1['phone_no'];
                        $profmob = $professionalDtls['mobile_no'];
                        $txtMsg1 .= " Dear ".$professionalDtls['name']." ".$professionalDtls['first_name'];
                        $txtMsg1 .= " Patient : ".$PatientDtls['name']." ".$PatientDtls['first_name'];
						$txtMsg1 .= " Caller No : ".$phone_no;
                        $txtMsg1 .= " Mob No : ".$PatientDtls['mobile_no'];
						
                        $txtMsg1 .= " Address : ".$PatientDtls['residential_address'];
                        
                        $txtMsg1 .= $dateofServicenew;//$dateofService;
                        $txtMsg1 .= " Msg : ".$reporting_instructionNew;
                        
                       $args = array(
							'msg' => $txtMsg1,
							'mob_no' => $profmob
						);
					$sms_data = sms_send($args);  
                        
                        
                        
                        
                    /*    $data_to_post = array();
                        $data_to_post['uname'] = 'SperocHL';
                        $data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
                        $data_to_post['send'] = 'speroc';
                        $data_to_post['dest'] = $professionalDtls['mobile_no']; 
                        $data_to_post['msg'] = $txtMsg1;

                        $curl = curl_init();
                        curl_setopt($curl,CURLOPT_URL, $form_url);
                        curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
                        curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
                        $result = curl_exec($curl);
                        curl_close($curl);*/
                        
                        //echo $txtMsg1;
                        //exit;
                        //.............. send msg to Doctors .......//
                        // sms send to dorctors is commented because of dr. anita said.
                        /*$DoctorMobNo = $DocDtls['mobile_no'];
                        $txtMsg2 .= " Dear ".$DocDtls['name']." ".$DocDtls['first_name'];
                        $txtMsg2 .= " Patient : ".$PatientDtls['name']." ".$PatientDtls['first_name'];
                        $txtMsg2 .= " Mob No : ".$PatientDtls['mobile_no'];
                        $txtMsg2 .= " Address : ".$PatientDtls['residential_address'];
                        
                        $txtMsg2 .= $dateofServicenew;//$dateofService;
                        $txtMsg2 .= " Msg : ".$reporting_instructionNew;//$reporintinst;
                        
                        $data_to_post1 = array();
                        $data_to_post1['uname'] = 'SperocHL';
                        $data_to_post1['pass'] = 'Pradnyes';//'s1M$t~I)';
                        $data_to_post1['send'] = 'speroc';
                        $data_to_post1['dest'] = $DoctorMobNo;
                        $data_to_post1['msg'] = $txtMsg2;

                        $curl1 = curl_init();
                        curl_setopt($curl1,CURLOPT_URL, $form_url);
                        curl_setopt($curl1,CURLOPT_POST, sizeof($data_to_post1));
                        curl_setopt($curl1,CURLOPT_POSTFIELDS, $data_to_post1);
                        $result1 = curl_exec($curl1);
                        curl_close($curl1);*/
                        
                        //.............. send msg to Patient .......//
                        $query=mysql_query("SELECT * FROM sp_events where event_id='".$ValRequirement['event_id']."'") or die(mysql_error());
															$row = mysql_fetch_array($query) or die(mysql_error());
															$caller_id=$row['caller_id'];
															$caller_detail=mysql_query("SELECT * FROM sp_callers where caller_id='".$caller_id."'") or die(mysql_error());
															$row1 = mysql_fetch_array($caller_detail) or die(mysql_error());
															$phone_no=$row1['phone_no'];
					/*										
                        $PatientDtlsno = $PatientDtls['mobile_no'];                        
                        $txtMsg3 .= "Dear ".$PatientDtls['name']." ".$PatientDtls['first_name'];
                        $txtMsg3 .= " Professional : ".$professionalDtls['name']." ".$professionalDtls['first_name'];
                        $txtMsg3 .= "- 7620400100"; //" Mob No : 7276489801";//.$professionalDtls['mobile_no'];
                        $txtMsg3 .= " Caller No : ".$phone_no;
						$txtMsg3 .= $dateofServicenew;//$dateofService;
					*/
					
					    $txtMsg3.= " Dear ".$PatientDtls['name']." ".$PatientDtls['first_name']." , "."\n";
						$txtMsg3.= " Professional: ".$professionalDtls['title']." ".$professionalDtls['first_name']." ".$professionalDtls['name']." , "."\n";
						$txtMsg3.= " Mob No: ".$profmob." , "."\n";
						$txtMsg3.= $dateofServicepatient." , "."\n";
						$txtMsg3.= " In case of E-Payments send SMS with Patient Name, NEFT Number, Event ID on 9130031532 \n";
						$txtMsg3.= " For feedback,service extention or any query please call Spero on 7620400100 " ;
						
					
						
                        $data_to_post2 = array();
                        $data_to_post2['uname'] = 'SperocHL';
                        $data_to_post2['pass'] = 'SpeRo@12';//s1M$t~I)';
                        $data_to_post2['send'] = 'speroc';
                        $data_to_post2['dest'] = $PatientDtls['mobile_no'];
                        $data_to_post2['msg'] = $txtMsg3;
                        
                        $patientmb=$PatientDtls['mobile_no'];
                        
                  
                 
                            $args = array(
							'msg' => $txtMsg3,
							'mob_no' => $patientmb
						);
						$sms_data = sms_send($args);  
                     
                        
                  

                        /*$curl2 = curl_init();
                        curl_setopt($curl2,CURLOPT_URL, $form_url);
                        curl_setopt($curl2,CURLOPT_POST, sizeof($data_to_post2));
                        curl_setopt($curl2,CURLOPT_POSTFIELDS, $data_to_post2);
                        $result2 = curl_exec($curl2);
                        curl_close($curl2);*/

                        /*
                        $t = 't';
                        $password = "s1M$$t~I)";
                        //.............. send msg to professional .......//
                        $profurl = "http://api.unicel.in/SendSMS/sendmsg.php?uname=SperocHL&pass=".$password."&send=speroc&dest=".$professionalDtls['mobile_no']."&msg=".$txtMsg1;
                        $curl_p = curl_init();
                        curl_setopt ($curl_p, CURLOPT_URL, $profurl);
                        curl_setopt($curl_p, CURLOPT_RETURNTRANSFER, 1);

                        $result_p = curl_exec ($curl_p);
                        curl_close ($curl_p);
                        echo $profurl;
                        echo $result_p;
                        
                        //.............. send msg to Doctors .......//
                        $HCMurl = "http://api.unicel.in/SendSMS/sendmsg.php?uname=SperocHL&pass=".$password."&send=speroc&dest=".$DoctorMobNo."&msg=".$txtMsg1;
                        $curl = curl_init();
                        curl_setopt ($curl, CURLOPT_URL, $HCMurl);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

                        $result = curl_exec ($curl);
                        curl_close ($curl);
                        echo $HCMurl;
                        echo $result;
                        
                        //.............. send msg to Patient .......//
                        $Patientsmsurl = "http://api.unicel.in/SendSMS/sendmsg.php?uname=SperocHL&pass=".$password."&send=speroc&dest=".$PatientDtls['mobile_no']."&msg=".$txtMsg3;
                        $curl1 = curl_init();
                        curl_setopt ($curl1, CURLOPT_URL, $Patientsmsurl);
                        curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);

                        $result1 = curl_exec ($curl1);
                        curl_close ($curl1);                        
                        echo $Patientsmsurl;
                        echo $result1;
                        */
                        /*echo $professionalDtls['mobile_no'];
                        echo '......';
                        echo $txtMsg1;
                        echo '......';
                        echo $DoctorMobNo;
                        echo '......';
                        echo $txtMsg2;
                        echo '......';
                        echo $PatientDtls['mobile_no'];
                        echo '......';
                        echo $txtMsg3;*/
                    }
                    else if($arr['type']=='2')
                    {
                        // Send Email to user 
                        $subject = "SEPRO JOB SUMMARY";
                        $message= "<table cellspacing='5' cellpadding='5' width='90%' >
                                        <tr>
                                            <td> Dear ".$professionalDtls['name']." ".$professionalDtls['first_name'].",</td>
                                        </tr>
                                        <tr>
                                            <td> A new job summary is designed for you details are as follows.</td>
                                        </tr>
										<tr>
                                            <th style='font-size:14px;'>Patient Information</th>
                                        </tr>
                                        <tr>
                                            <td> <label style='vertical-align:middle; width:180px; display:inline-block; font-weight:bold;'>Patient Id :</label> ".$PatientDtls['hhc_code']."</td>
                                        </tr>
                                        <tr>
                                            <td> <label style='vertical-align:middle; width:180px; display:inline-block; font-weight:bold;'>Patient Name :</label> ".$PatientDtls['name']." ".$PatientDtls['first_name']." ".$PatientDtls['middle_name']."</td>
                                        </tr>
                                        <tr>
                                            <td> <label style='vertical-align:middle; width:180px; display:inline-block; font-weight:bold;'>Residential Address :</label> ".$PatientDtls['residential_address']."</td>
                                        </tr>
                                        <tr>
                                            <td> <label style='vertical-align:middle; width:180px; display:inline-block; font-weight:bold;'>Location :</label> ".$PatientDtls['locationNm']."</td>
                                        </tr>
                                        <tr>
                                            <td> <label style='vertical-align:middle; width:180px; display:inline-block; font-weight:bold;'>PIN Code :</label> ".$PatientDtls['LocationPinCode']."</td>
                                        </tr>
                                        <tr>
                                            <td> <label style='vertical-align:middle; width:180px; display:inline-block; font-weight:bold;'>Phone Number :</label> ".$PatientDtls['phone_no']."</td>
                                        </tr>
                                        <tr>
                                            <td> <label style='vertical-align:middle; width:180px; display:inline-block; font-weight:bold;'>Mobile Number :</label> ".$PatientDtls['mobile_no']."</td>
                                        </tr>
                                        <tr>
                                            <td>".$msgContent."</td>
                                        </tr>
                                        <tr>
                                            <td> In case of any queries, please email <a href='mailto:connect@spero.in'>connect@spero.in </a> </td>
                                        </tr>
                                        <tr>
                                            <td >Best Regards,</td>
                                        </tr>   
                                        <tr>
                                            <td>Support Team</td>
                                        </tr>
                                   </table>";

                                        $headers="From: no-reply@".$domainName."\nContent-Type:text/html;charset=iso-8859-1";
                                        $return_path = "no-reply@".$domainName."";
                                        $sendMessage=$GLOBALS['box_message_top'];
                                        $sendMessage.=$message;
                                        $sendMessage.=$GLOBALS['box_message_bottom'];
                                        All_useremail($professionalDtls['email_id'],$sendMessage,$subject);

                    }
                    else if($arr['type']=='3')
                    {

                    }
                }
                else 
                {
                     $success=0;
                }     
            }

            if($success)
            {
                echo "success";
                exit;
            }
            else
            {
                echo "error";
                exit;
            }
        }
    }
}
else if($_REQUEST['action']=='SubmitJobClosure')
{
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $success=0;
        $errors=array(); 
        $i=0;
        $extra_unit_medicines = strip_tags($_POST['extras']);
        $extra_non_unit_medicines = strip_tags($_POST['extras_2']);
        $extra_unit_consumables = strip_tags($_POST['consumable_extras']);
        $extra_non_unit_consumables = strip_tags($_POST['non_unit_consumable_extras']);
        $arr['eventIDForClosure']=$_POST['eventIDForClosure'];
        $arr['event_id']=$_POST['event_id'];
        $arr['Edit_CallerId']=$_POST['Edit_CallerId'];
        $arr['job_closure_id']=$_POST['job_closure_id'];
        $arr['professional_vender_id']=$_POST['professional_vender_id'];
        $arr['service_date']=$_POST['service_date'];
        $arr['tot_unit_medicine']=$_POST['tot_unit_medicine'];
        $arr['tot_non_unit_medicine']=$_POST['tot_non_unit_medicine'];
        $arr['tot_unit_consumable_medicine']=$_POST['tot_unit_consumable_medicine'];
        $arr['tot_non_unit_consumable_medicine']=$_POST['tot_non_unit_consumable_medicine'];
        
        $arr['service_render']=$_POST['service_rendered'];
        //unit_medicine
        $UnitMedicineArr['unit_id']=$_POST['unit_medicine_id'];
        $UnitMedicineArr['unit_quantity']=$_POST['unit_medicine_quantity'];
        //non_unit_medicine
        $NonUnitMedicineArr['unit_id']=$_POST['non_unit_medicine_id'];
        $NonUnitMedicineArr['unit_quantity']=$_POST['non_unit_medicine_quantity'];
        //unit_consumable
        $UnitConsumableArr['unit_id']=$_POST['unit_consumable_id'];
        $UnitConsumableArr['unit_quantity']=$_POST['unit_consumable_quantity'];
        //non_unit_consumable
        $NonUnitConsumableArr['unit_id']=$_POST['non_unit_consumable_id'];
        $NonUnitConsumableArr['unit_quantity']=$_POST['non_unit_consumable_quantity'];
        // Baseline
        $arr['temprature']=$_POST['temprature'];
        $arr['bsl']=$_POST['bsl'];
        $arr['pulse']=$_POST['pulse'];
        $arr['spo2']=$_POST['spo2'];
        $arr['rr']=$_POST['rr'];
        $arr['gcs_total']=$_POST['gcs_total'];
        $arr['high_bp']=$_POST['high_bp'];
        $arr['low_bp']=$_POST['low_bp'];
        $arr['skin_perfusion']=$_POST['skin_perfusion'];
        $arr['airway']=$_POST['airway'];
        $arr['breathing']=$_POST['breathing'];
        $arr['circulation']=$_POST['circulation'];
        $arr['baseline']=$_POST['baseline'];
        $arr['status']='1';
        $arr['summary_note']=$_POST['summary_note'];
        $arr['added_by']=$_SESSION['employee_id'];
        $arr['added_date']=date('Y-m-d H:i:s');
        $arr['modified_by']=$_SESSION['employee_id'];
        $arr['last_modified_date']=date('Y-m-d H:i:s');
        if(!empty($arr['temprature']))
        {
            if($arr['temprature'] >=0 && $arr['temprature']<=110)
            {
            }
            else 
            {
               $success=0;
               $errors[$i++]="Invalid Temprature Value";
            }
        }
        if(!empty($arr['bsl']))
        {
            if($arr['bsl'] >=0 && $arr['bsl']<=500)
            {
            }
            else 
            {
               $success=0;
               $errors[$i++]="Invalid TBSL Value";
            }
        }
        if(!empty($arr['pulse']))
        {
            if($arr['pulse'] >=0 && $arr['pulse']<=300)
            {
            }
            else 
            {
               $success=0;
               $errors[$i++]="Invalid Pulse Value"; 
            }
        }
        if(!empty($arr['spo2']))
        {
            if($arr['spo2'] >=0 && $arr['spo2']<=100)
            {
            }
            else 
            {
               $success=0;
               $errors[$i++]="Invalid SPO2 Value"; 
            }
        }
        if(!empty($arr['rr']))
        {
            if($arr['rr'] >=0 && $arr['rr']<=40)
            {
            }
            else 
            {
               $success=0;
               $errors[$i++]="Invalid RR Value";
            }
        }
        if(!empty($arr['gcs_total']))
        {
            if($arr['gcs_total'] >=3 && $arr['gcs_total']<=15)
            {
            }
            else 
            {
               $success=0;
               $errors[$i++]="Invalid GCS total Value";
            }
        }
        if(!empty($arr['high_bp']))
        {
            if($arr['high_bp'] >=0 && $arr['high_bp']<=300)
            {
            }
            else 
            {
               $success=0;
               $errors[$i++]="Invalid High BP Value";
            }
        }
        if(!empty($arr['low_bp']))
        {
            if($arr['low_bp'] >=0 && $arr['low_bp']<=300)
            {
            }
            else 
            {
               $success=0;
               $errors[$i++]="Invalid Low BP Value";
            }
        }
        
        if(!empty($_FILES['userfile']["name"]))
        {
           $uploaded_job_closure_image="";
           if(count($errors)==0 && $_FILES['userfile']["name"])
           { 
               $file_str=preg_replace('/\s+/', '_', $_FILES['userfile']["name"]);
               $uploaded_job_closure_image=time().basename($file_str);
               $newfile = "JobClosureDocuments/";

               $filename = $_FILES['userfile']['tmp_name']; // File being uploaded.
               $filetype = $_FILES['userfile']['type']; // type of file being uploaded
               $filesize = filesize($filename); // File size of the file being uploaded.
               $source1 = $_FILES['userfile']['tmp_name'];
               $target_path1 = $newfile.$uploaded_job_closure_image;

               list($width1, $height1, $type1, $attr1) = getimagesize($source1);

               if(strtolower($filetype) == "image/jpeg" || strtolower($filetype) == "image/pjpeg" || strtolower($filetype) == "image/GIF" || strtolower($filetype) == "image/gif" || strtolower($filetype) == "image/png")
               {
                   if(move_uploaded_file($source1, $target_path1))
                   {
                       $thump_target_path="JobClosureDocuments/".$uploaded_job_closure_image;
                       copy($target_path1,$thump_target_path);

                       list($width, $height, $type, $attr) = getimagesize($thump_target_path);

                       // echo $width.$height;

                       if($width<=600 && $height<=600)
                       {
                           $job_closure_image_uploaded=1;
                       }
                       else
                       {
                           //------------resize the image----------------
                           $obj_img1 = new thumbnail_images();
                           $obj_img1->PathImgOld = $thump_target_path;
                           $obj_img1->PathImgNew = $thump_target_path;
                           $obj_img1->NewWidth = 600;
                           $obj_img1->NewHeight = 600;
                           if (!$obj_img1->create_thumbnail_images())
                           {
                               $job_closure_image_uploaded=0;
                               unlink($target_path1);
                               $success=0;
                               echo "ImageFileOnly";
                               exit;
                           }
                           else
                           {
                               $job_closure_image_uploaded=1;
                               list($width, $height, $type, $attr) = getimagesize($thump_target_path);
                               //echo $width.$height;
                                if($height>600)
                                {
                                    //------------resize the image----------------
                                    $obj_img1 = new thumbnail_images();
                                    $obj_img1->PathImgOld = $thump_target_path;
                                    $obj_img1->PathImgNew = $thump_target_path;
                                    $obj_img1->NewHeight = 600;
                                    if (!$obj_img1->create_thumbnail_images())
                                    {
                                        $job_closure_image_uploaded=0;
                                        unlink($target_path1);
                                        $uploaded_job_closure_image="";
                                    }
                                }
                           }
                       }
                   }
                   else
                   {
                       $job_closure_image_uploaded=0;
                       $success=0;
                       echo "FileUploadError";
                       exit;
                   }
               }
               else
               {
                   $job_closure_image_uploaded=0;
                   $success=0;
                   echo "ImageFileOnly";
                   exit;
               }
           }
        }
        if($job_closure_image_uploaded)
        {
           $arr['job_closure_file'] = $uploaded_job_closure_image;
        }

        if(count($errors))
        {
            echo "ValidationError";
            exit;
        }
        else 
        {
            $InsertRecord=$eventClass->InsertJobClosure($arr);
            if(!empty($InsertRecord))
            {

                if(!empty($arr['job_closure_id']))
                    $RecordId=$arr['job_closure_id'];
                else 
                    $RecordId=$InsertRecord;

                // Insert Unit medicine Record 
                $UnitMedicineArr['job_closure_id']=$RecordId;
                $UnitMedicineArr['consumption_type']='1';
                if(!empty($UnitMedicineArr['unit_id']))
                    $eventClass->InsertConsumptions($UnitMedicineArr);


                // Update records 

                if(!empty($arr['tot_unit_medicine']))
                {
                    for($s=0;$s<$arr['tot_unit_medicine'];$s++)
                    {
                        $UnitMedicineArr['consumption_id'] = mysql_real_escape_string($_POST['unit_medicine_consumption_id'.$s]);
                        $UnitMedicineArr['unit_id'] = mysql_real_escape_string($_POST['unit_medicine_id'.$s]);
                        $UnitMedicineArr['unit_quantity']= mysql_real_escape_string($_POST['unit_medicine_quantity'.$s]);
                        if(!empty($UnitMedicineArr['unit_id']))
                            $eventClass->InsertConsumptions($UnitMedicineArr);
                    }
                }

                unset($UnitMedicineArr['unit_id']);
                unset($UnitMedicineArr['unit_quantity']);

                if($extra_unit_medicines > 0)
                {
                    for($a=1;$a<=$extra_unit_medicines;$a++)
                    {
                        $UnitMedicineArr['unit_id'] = mysql_real_escape_string($_POST['unit_medicine_id'.$a]);
                        $UnitMedicineArr['unit_quantity']= mysql_real_escape_string($_POST['unit_medicine_quantity'.$a]);
                        if(!empty($UnitMedicineArr['unit_id']))
                            $eventClass->InsertConsumptions($UnitMedicineArr);

                        unset($UnitMedicineArr['unit_id']);
                        unset($UnitMedicineArr['unit_quantity']);
                    }
                }

                // Insert Non Unit medicine Record 

                $NonUnitMedicineArr['job_closure_id']=$RecordId;
                $NonUnitMedicineArr['consumption_type']='2';
                if(!empty($NonUnitMedicineArr['unit_id']))
                    $eventClass->InsertConsumptions($NonUnitMedicineArr);

                unset($NonUnitMedicineArr['unit_id']);
                unset($NonUnitMedicineArr['unit_quantity']);

                // Update records 

                if(!empty($arr['tot_non_unit_medicine']))
                {
                    for($t=0;$t<$arr['tot_non_unit_medicine'];$t++)
                    {
                        $NonUnitMedicineArr['consumption_id'] = mysql_real_escape_string($_POST['non_unit_medicine_consumption_id'.$t]);
                        $NonUnitMedicineArr['unit_id'] = mysql_real_escape_string($_POST['non_unit_medicine_id'.$t]);
                        $NonUnitMedicineArr['unit_quantity']= mysql_real_escape_string($_POST['non_unit_medicine_quantity'.$t]);
                        if(!empty($NonUnitMedicineArr['unit_id']))
                            $eventClass->InsertConsumptions($NonUnitMedicineArr);
                    }
                }

                if($extra_non_unit_medicines > 0)
                {
                    for($b=1;$b<=$extra_non_unit_medicines;$b++)
                    {
                        $NonUnitMedicineArr['unit_id'] = mysql_real_escape_string($_POST['non_unit_medicine_id'.$b]);
                        $NonUnitMedicineArr['unit_quantity']= mysql_real_escape_string($_POST['non_unit_medicine_quantity'.$b]);
                        if(!empty($NonUnitMedicineArr['unit_id']))
                            $eventClass->InsertConsumptions($NonUnitMedicineArr);

                        unset($NonUnitMedicineArr['unit_id']);
                        unset($NonUnitMedicineArr['unit_quantity']);
                    }
                }

                // Insert Unit Consumable Record

                $UnitConsumableArr['job_closure_id']=$RecordId;
                $UnitConsumableArr['consumption_type']='3';
                if(!empty($UnitConsumableArr['unit_id']))
                    $eventClass->InsertConsumptions($UnitConsumableArr);

                unset($UnitConsumableArr['unit_id']);
                unset($UnitConsumableArr['unit_quantity']);

                 // Update records 

                if(!empty($arr['tot_unit_consumable_medicine']))
                {
                    for($u=0;$u<$arr['tot_unit_consumable_medicine'];$u++)
                    {
                        $UnitConsumableArr['consumption_id'] = mysql_real_escape_string($_POST['unit_consumable_consumption_id'.$u]);
                        $UnitConsumableArr['unit_id'] = mysql_real_escape_string($_POST['unit_consumable_id'.$u]);
                        $UnitConsumableArr['unit_quantity']= mysql_real_escape_string($_POST['unit_consumable_quantity'.$u]);
                        if(!empty($UnitConsumableArr['unit_id']))
                            $eventClass->InsertConsumptions($UnitConsumableArr);
                    }
                }

                if($extra_unit_consumables > 0)
                {
                    for($c=1;$c<=$extra_unit_consumables;$c++)
                    {
                        $UnitConsumableArr['unit_id'] = mysql_real_escape_string($_POST['unit_consumable_id'.$c]);
                        $UnitConsumableArr['unit_quantity']= mysql_real_escape_string($_POST['unit_consumable_quantity'.$c]);
                        if(!empty($UnitConsumableArr['unit_id']))
                            $eventClass->InsertConsumptions($UnitConsumableArr);

                        unset($UnitConsumableArr['unit_id']);
                        unset($UnitConsumableArr['unit_quantity']);
                    }
                }

                 // Insert Non Unit Consumable Record

                $NonUnitConsumableArr['job_closure_id']=$RecordId;
                $NonUnitConsumableArr['consumption_type']='4';
                if(!empty($NonUnitConsumableArr['unit_id']))
                    $eventClass->InsertConsumptions($NonUnitConsumableArr);

                unset($NonUnitConsumableArr['unit_id']);
                unset($NonUnitConsumableArr['unit_quantity']);

                // Update records 

                if(!empty($arr['tot_non_unit_consumable_medicine']))
                {
                    for($v=0;$v<$arr['tot_non_unit_consumable_medicine'];$v++)
                    {
                        $NonUnitConsumableArr['consumption_id'] = mysql_real_escape_string($_POST['non_unit_consumable_consumption_id'.$v]);
                        $NonUnitConsumableArr['unit_id'] = mysql_real_escape_string($_POST['non_unit_consumable_id'.$v]);
                        $NonUnitConsumableArr['unit_quantity']= mysql_real_escape_string($_POST['non_unit_consumable_quantity'.$v]);
                        if(!empty($NonUnitConsumableArr['unit_id']))
                            $eventClass->InsertConsumptions($NonUnitConsumableArr);
                    }
                }

                if($extra_non_unit_consumables > 0)
                {
                    for($d=1;$d<=$extra_non_unit_consumables;$d++)
                    {
                        $NonUnitConsumableArr['unit_id'] = mysql_real_escape_string($_POST['non_unit_consumable_id'.$d]);
                        $NonUnitConsumableArr['unit_quantity']= mysql_real_escape_string($_POST['non_unit_consumable_quantity'.$d]);
                        if(!empty($NonUnitConsumableArr['unit_id']))
                            $eventClass->InsertConsumptions($NonUnitConsumableArr);

                        unset($NonUnitConsumableArr['unit_id']);
                        unset($NonUnitConsumableArr['unit_quantity']);
                    }
                }

                echo "success";
                exit;
            }
            else 
            {
                echo "error";
                exit;
            }
        }
    }
}
else if($_REQUEST['action'] == 'SubmitFeedbackFrm')
{     
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $feedbackEventId = $_REQUEST['feedbackEventId'];
        $feedbackCallerId = $_REQUEST['feedbackCallerId'];
        $arr['feedbackEventId'] = $feedbackEventId;
        $arr['feedbackCallerId'] = $feedbackCallerId;
        $arr['prevFeedbackEveId'] = $_REQUEST['prevFeedbackEveId'];
        $arr['service_date'] = $_REQUEST['service_date'];
        $arr['added_by'] = $_SESSION['employee_id'];
        //$arr['rating'] = $_REQUEST['rating_val'];
        $InsertRecords=$eventClass->InsertFeedbackForm($arr);
    }
}
else if($_REQUEST['action']=='vw_select_professional_for_email')
{
   $arr['event_id']=$_REQUEST['event_id']; 
   $arr['selected_block_id']=$_REQUEST['selected_block_id']; 
   $arr['selected_block_value']=$_REQUEST['selected_block_value']; 
   
   // Get Event Detail
   $EventDtls=$eventClass->GetEvent($arr); 
   if(!empty($EventDtls['purpose_id']))
   {
        $call_puropose=$EventDtls['purpose_id'];
        if($call_puropose=='4')
        {
           $EventCallerDtls=$eventClass->GetEventCallerDtls($arr); 
        }  
   }
   
   if(!empty($_REQUEST['Consultant_Email']))
   {
       // Getting consultant doctor id 
       
       $ConsultantSql="SELECT doctors_consultants_id,email_id FROM sp_doctors_consultants WHERE email_id='".$_REQUEST['Consultant_Email']."'";
       $ConsultantDtls=$db->fetch_array($db->query($ConsultantSql)); 
   }
   ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Send Email</h4>
        </div>
        <div class="modal-body">
            <form class="form-inline" name="frmSendEmail" id="frmSendEmail" method="post" action="">
                <div class="editform">
                    <label>Email Address : <span style="color:red;">*</span> </label> 
                    <div class="value">
                        <input type="hidden" name="consultant_id" id="consultant_id"  value="<?php if(!empty($ConsultantDtls)) { echo $ConsultantDtls['doctors_consultants_id']; }?>" />
                        <?php if(!empty($ConsultantDtls)) { ?>
                            <input type="text" name="email_id" id="email_id" class="form-control" value="<?php if(!empty($ConsultantDtls)) { echo $ConsultantDtls['email_id']; }?>" />
                        <?php } else { ?>
                            <input type="text" name="email_id" id="email_id" class="validate[required] form-control" value="" />
                        <?php } ?>
                    </div>
                </div>
                <div class="editform">
                    <label>Message : <span style="color:red;">*</span> </label>
                    <div class="value">
                        <textarea name="email_msg" id="email_msg" class="validate[required] form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer"> 
                    <a href="javascript:void(0);" onclick="EmailContent('<?php echo $arr['event_id']; ?>','<?php echo $arr['selected_block_id']; ?>','<?php echo $arr['selected_block_value']; ?>')" data-toggle="tooltip" title="Email">
                        <img alt="Email" src="images/send-mail.png" />
                    </a> 
                </div>
            </form>
        </div>
   <?php   
}
else if($_REQUEST['action']=='AddUnitMedicineRow')
{
    // Getting Unit Medicine List
    $arr['type']='1';
    $UnitMedicinesList=$commonClass->GetAllMedicines($arr);
    unset($arr);
    
    $i = $_REQUEST['curr_div'];  
    $j = $i+1;
    ?>
       <div id="div_<?php echo $i;?>">
           <div class="clearfix"></div>
           <div class="col-lg-4">
               <label class="name"></label>
               <div class="select-holder">
                   <label class="select-box-lbl chose">
                       <select class="chosen-select form-control" name="unit_medicine_id<?php echo $i;?>" id="unit_medicine_id<?php echo $i;?>">
                           <option value="">Medicines</option>
                            <?php   if(!empty($UnitMedicinesList))
                                    {
                                        for($a=0;$a<count($UnitMedicinesList);$a++)
                                        {
                                            $class = '';
                                            for($b=0;$b<count($UnitMedicineArr);$b++)
                                            {
                                                if($UnitMedicineArr[$b] == $UnitMedicinesList[$a]['medicine_id'])
                                                    $class = 'selected="selected"';
                                            }
                                            echo '<option '.$class.' value="'.$UnitMedicinesList[$a]['medicine_id'].'">'.$UnitMedicinesList[$a]['name'].'</option>';
                                        }
                                    }
                                    ?>
                        </select>
                   </label>
                </div>
           </div>
           <div class="col-lg-4">
               <label class="name"></label>
               <input type="text" name="unit_medicine_quantity<?php echo $i;?>" id="unit_medicine_quantity<?php echo $i;?>" value="" class="form-control" maxlength="20" />
           </div>
           <div class="col-lg-4"> 
           </div>
       </div>
       <div id="div_<?php echo $j;?>"></div>
   <?php 
}
else if($_REQUEST['action']=='AddNonUnitMedicineRow')
{
   // Getting Non Unit Medicine List
      $arr['type']='2';
      $NonUnitMedicinesList=$commonClass->GetAllMedicines($arr);
      unset($arr); 
      
    $i = $_REQUEST['curr_div'];  
    $j = $i+1;
    ?>
       <div id="non_div_<?php echo $i;?>">
           <div class="clearfix"></div>
           <div class="col-lg-4">
               <label class="name"></label>
               <div class="select-holder">
                   <label class="select-box-lbl chose">
                       <select class="chosen-select form-control" name="non_unit_medicine_id<?php echo $i;?>" id="non_unit_medicine_id<?php echo $i;?>">
                           <option value="">Medicines</option>
                            <?php   if(!empty($NonUnitMedicinesList))
                                    {
                                        for($b=0;$b<count($NonUnitMedicinesList);$b++)
                                        {
                                            $class = '';
                                            for($c=0;$c<count($NonUnitMedicineArr);$c++)
                                            {
                                                if($NonUnitMedicineArr[$c] == $NonUnitMedicinesList[$b]['medicine_id'])
                                                    $class = 'selected="selected"';
                                            }
                                            echo '<option '.$class.' value="'.$NonUnitMedicinesList[$b]['medicine_id'].'" >'.$NonUnitMedicinesList[$b]['name'].'</option>';
                                        }
                                    }
                            ?>
                        </select>
                   </label>
                </div>
           </div>
           <div class="col-lg-4">
               <label class="name"></label>
               <input type="text" name="non_unit_medicine_quantity<?php echo $i;?>" id="non_unit_medicine_quantity<?php echo $i;?>" value="" class="form-control" maxlength="20" />
           </div>
           <div class="col-lg-4"> 
           </div>
       </div>
       <div id="non_div_<?php echo $j;?>"></div>
   <?php  
}
else if($_REQUEST['action']=='AddUnitConsumableRow')
{
    // Getting Unit Consumables List
    $arr['type']='1';
    $UnitConsumablesList=$commonClass->GetAllConsumables($arr);
    unset($arr);
    
    $i = $_REQUEST['curr_div'];  
    $j = $i+1;
    ?>
       <div id="consumable_unit_div_<?php echo $i;?>">
           <div class="clearfix"></div>
           <div class="col-lg-4">
               <label class="name"></label>
               <div class="select-holder">
                   <label class="select-box-lbl chose">
                       <select class="chosen-select form-control" name="unit_consumable_id<?php echo $i;?>" id="unit_consumable_id<?php echo $i;?>">
                           <option value="">Consumable</option>
                            <?php   if(!empty($UnitConsumablesList))
                                    {
                                        for($a=0;$a<count($UnitConsumablesList);$a++)
                                        {
                                            $class = '';
                                            for($b=0;$b<count($UnitConsumbaleArr);$b++)
                                            {
                                                if($UnitConsumbaleArr[$b] == $UnitConsumablesList[$a]['consumable_id'])
                                                    $class = 'selected="selected"';
                                            }
                                            echo '<option '.$class.' value="'.$UnitConsumablesList[$a]['consumable_id'].'">'.$UnitConsumablesList[$a]['name'].'</option>';
                                        }
                                    }
                                    ?>
                        </select>
                   </label>
                </div>
           </div>
           <div class="col-lg-4">
               <label class="name"></label>
               <input type="text" name="unit_consumable_quantity<?php echo $i;?>" id="unit_consumable_quantity<?php echo $i;?>" value="" class="form-control" maxlength="20" />
           </div>
           <div class="col-lg-4"> 
           </div>
       </div>
       <div id="consumable_unit_div_<?php echo $j;?>"></div>
   <?php 
}
else if($_REQUEST['action']=='AddNonUnitConsumableRow')
{
    // Getting Non Unit Consumables List
    $arr['type']='2';
    $NonUnitConsumablesList=$commonClass->GetAllConsumables($arr);
    unset($arr);
      
    $i = $_REQUEST['curr_div'];  
    $j = $i+1;
    ?>
       <div id="non_unit_consumable_div_<?php echo $i;?>">
           <div class="clearfix"></div>
           <div class="col-lg-4">
               <label class="name"></label>
               <div class="select-holder">
                   <label class="select-box-lbl chose">
                       <select class="chosen-select form-control" name="non_unit_consumable_id<?php echo $i;?>" id="non_unit_consumable_id<?php echo $i;?>">
                           <option value="">Consumables</option>
                            <?php   if(!empty($NonUnitConsumablesList))
                                    {
                                        for($b=0;$b<count($NonUnitConsumablesList);$b++)
                                        {
                                            $class = '';
                                            for($c=0;$c<count($NonUnitConsumbaleArr);$c++)
                                            {
                                                if($NonUnitConsumbaleArr[$c] == $NonUnitConsumablesList[$b]['consumable_id'])
                                                    $class = 'selected="selected"';
                                            }
                                            echo '<option '.$class.' value="'.$NonUnitConsumablesList[$b]['consumable_id'].'" >'.$NonUnitConsumablesList[$b]['name'].'</option>';
                                        }
                                    }
                            ?>
                        </select>
                   </label>
                </div>
           </div>
           <div class="col-lg-4">
               <label class="name"></label>
               <input type="text" name="non_unit_consumable_quantity<?php echo $i;?>" id="non_unit_consumable_quantity<?php echo $i;?>" value="" class="form-control" maxlength="20" />
           </div>
           <div class="col-lg-4"> 
           </div>
       </div>
       <div id="non_unit_consumable_div_<?php echo $j;?>"></div>
       <?php 
}
else if($_REQUEST['action']=='chk_event_allocated_professional')
{
    $arr['event_id']=$_REQUEST['event_id'];
    $arr['professional_id']=$_REQUEST['professional_id'];
    $arr['Edit_CallerId']=$_REQUEST['Edit_CallerId'];
    $arr['eventIDForClosure']=$_REQUEST['eventIDForClosure'];
    
    if(!empty($arr['event_id']) && !empty($arr['professional_id']))
    {
        $chk_result=$eventClass->Chk_Professional_Exists($arr);
        if(!empty($chk_result))
        {
            echo "ProfessionalExists";
            exit;
        }
        else 
        {
            echo "ProfessionalNotExists";
            exit;
        }
    }
    else 
    {
        echo "MissingParameter";
        exit;
    }
}
else if($_REQUEST['action']=='delete_consumption_option')
{
    $arr['consumption_id']=$_REQUEST['consumption_id']; 
    
    if(!empty($arr['consumption_id']))
    {
        $DelRecord=$eventClass->delete_consumption_option($arr);
        if($DelRecord)
        {
            echo "success";
            exit;
        }
        else 
        {
            echo "error";
            exit;
        }
    }
    else 
    {
        echo "MissingParameter";
        exit;
    }
    
    
}
else if($_REQUEST['action']=='cancelEventPlan')
{
    $PlanEvent_id = $_REQUEST['PlanEvent_id'];
    $selectExist_event = "SELECT event_id,
        event_code,
        purpose_id,
        estimate_cost,
        event_status,
        last_modified_by,
        last_modified_date
    FROM sp_events WHERE event_id = '" . $PlanEvent_id . "'";
    if (mysql_num_rows($db->query($selectExist_event))) {

        //Get event details
        $recordResult = $db->fetch_array($db->query($selectExist_event));

        $updateQuery = "UPDATE sp_events SET estimate_cost = '2', event_status = '5' WHERE event_id = '" . $PlanEvent_id . "' ";
        $updateStatus = $db->query($updateQuery);

        if (!empty($recordResult) && !empty($updateStatus)) {

            $EventId   = $recordResult['event_id'];
            $eventCode = $recordResult['event_code'];
            $purposeId = $recordResult['purpose_id'];

            $activityDesc = "Event " . $eventCode . " is cancelled by " . $_SESSION['emp_nm'] . ". \r\n";

            $activityDesc .= "estimate_cost is changed from " . $recordResult['estimate_cost'] . " to 2  \r\n" .
                "event_status is changed from " . $recordResult['event_status'] ." to 5 \r\n" .
                "last_modified_by is changed from " . $recordResult['last_modified_date'] ." to ". $_SESSION['emp_nm'] . "\r\n" .
                "last_modified_date is changed from " . $recordResult['last_modified_date'] ." to " . date('Y-m-d H:i:s');

            $insertActivityArr = array();
            $insertActivityArr['module_type'] = '1';
            $insertActivityArr['module_id']   = '';
            $insertActivityArr['module_name'] = 'Cancel Plan of care details';
            $insertActivityArr['purpose_id']  = $purposeId;
            $insertActivityArr['event_id']    = $EventId;
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $insertActivityArr['added_by_type'] = '1'; // 1 For Employee
            $insertActivityArr['added_by_id']   = $_SESSION['employee_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $db->query_insert('sp_user_activity',$insertActivityArr);

            unset($insertActivityArr);
        }

    }
}
else if($_REQUEST['action']=='RefreshLocation')
{
    ?>
       <select class="validate[required] chosen-select form-control" id="patient_location" name="patient_location" onchange="return ChangeLocation(this.value,'location');">
            <option value="">Location</option>
            <?php
            $arr['list'] = 'all';
            $ResultDoctors = $eventClass->LocationList($arr);                          
            foreach($ResultDoctors as $key=>$valRecords)
            {
              if($recListResponse['locationNm'] == $valRecords['location'])
                  echo '<option value="'.$valRecords['location_id'].'" selected="selected">'.$valRecords['location'].'</option>';
              else
                  echo '<option value="'.$valRecords['location_id'].'">'.$valRecords['location'].'</option>';
            }
            ?>
        </select>
    <?php 
} 
else if ($_REQUEST['action'] == 'add_boarscate_event_msg') {
	$eventId = $_REQUEST['eventId'];
	$serviceId = $_REQUEST['serviceId'];
	
	if (!empty($eventId) && !empty($serviceId)) {
		// Get event details
		$boarscateMsgStatus = $eventClass->getProfForBoarscate($eventId, $serviceId);

		if(!empty($boarscateMsgStatus))
        {
            
            if (strpos($boarscateMsgStatus, 'activeNotiExists') !== false) {
                $msgContentData = explode('_', $boarscateMsgStatus);
                $boarscateMsgStatus = $msgContentData[0];
                $msgContent = $msgContentData[1];
                
            }
            
            
            
            if ($boarscateMsgStatus == 'NoRecordFound') {
				echo "As per our search criteria no professional found";
				exit;
			} else if ($boarscateMsgStatus == 'NoEventReqFound') {
				echo "Event Requirement detail not found";
				exit;
			} else if ($boarscateMsgStatus == 'NoLatLongFound') {
				echo "Patient latitude and longitude not found";
				exit;
			} else if ($boarscateMsgStatus == 'NoEventDtlsFound') {
				echo "Event detail not found";
				exit;
			} else if ($boarscateMsgStatus == 'ErrorInNotificaton') {
			    
			   
				echo "Having issue in a send notificaton";
				exit;
			} else if ($boarscateMsgStatus == 'success') {
				echo "success";
				exit;
			}
			 else if ($boarscateMsgStatus == 'alreadyAssigned') {
				echo "This event is already assigned to one of professional";
				exit;
			}
			else if ($boarscateMsgStatus == 'activeNotiExists') {
				echo "Active push notification exists. Active push notifcation will expires in " . $msgContent;
				exit;
			}
        }
        else 
        {
            echo "error";
            exit;
        }
	}
} else if ($_REQUEST['action'] == 'get_boarscate_prof_dtls') {
	$eventId = $_REQUEST['eventId'];
	$serviceId = $_REQUEST['serviceId'];
	if (!empty($eventId) && !empty($serviceId)) {
		// Get event details
		$boarscateMsgList = $eventClass->getPushNotification($eventId, $serviceId);
		$html = '';
		if (!empty($boarscateMsgList)) {
			$html = '<table id="boarscateLogTable" class="table table-striped" cellspacing="0" width="100%">
				  <thead>
					<tr>
						<th width="10%">Prof Code</td>
						<th width="18%">Name</td>
						<th width="13%">Accepted Status</td>
					</tr>
				  </thead>
				  <tbody>';
				  
				foreach ($boarscateMsgList[0] AS $key => $valMsg) {
					$html .= '<tr>
							<td>' . $valMsg['professional_code'] . '</td>
							<td>' . $valMsg['professional_name'] . '</td>
							<td>' . $valMsg['acknowledgedStatus'] . '</td>
						</tr>';
				}
		    $html .= '</tbody></table>';
			
			echo (!empty($html)) ? $html : "";
		}	
	}
}
else if($_REQUEST['action'] == 'convertEnquiryIntoService')
{
    $eventId = $_REQUEST['event_id'];
    $chkEventExists = "SELECT event_id FROM sp_events WHERE event_id = '" . $eventId . "' ";
    if (mysql_num_rows($db->query($chkEventExists)))
    {
        $status = $eventClass->convertEnquiryIntoService($eventId);

        if ($status) {
            echo "success";
            exit;
        } else {
            echo "error";
            exit;
        }
        
    } else {
        echo "invalidEvent";
        exit;
    }
}
else if($_REQUEST['action'] == 'vw_cancel_inquiry')
{
    $eventId = $db->escape($_REQUEST['event_id']);
    ?>
       <div class = "modal-header">
            <button type = "button" class = "close" data-dismiss = "modal"><span aria-hidden = "true">&times;</span>
                <span class = "sr-only">Close</span>
            </button>
            <h4 class = "modal-title">Cancel Enquiry</h4>
       </div>
        <div class = "modal-body">
            <form class = "form-inline" name = "frmCancelEnquiry" id = "frmCancelEnquiry" method = "post" action = "event_ajax_process.php?action=SubmitCancelEnquiry" autocomplete="off">
                <div class = "editform" style="margin:10px 0px 10px 0px !important;">
                    <label>
                        Enquiry Cancel From : <span style="color:red;">*</span>
                    </label>
                    <div class = "value">
                        <select name="enquiry_cancel_from" id="enquiry_cancel_from" class = "validate[required] form-control">
                            <option value="">select option</option>
                            <option value="1">From Spero</option>
                            <option value="2">From Professional</option>
                            <option value="3">Form Patient</option>
                            <option value="4">Other</option>
                        </select>
                    </div>
                </div>
                <div class = "editform">
                    <label>
                        Enquiry Cancellation Reason : <span style="color:red;">*</span> 
                    </label> 
                    <div class="value">
                        <input type = "hidden" name = "event_id" id = "event_id"  value = "<?php if(!empty($eventId)) { echo $eventId; } ?>" />
                        <input type = "textarea" name = "cancellation_reason" id = "cancellation_reason" class = "validate[required] form-control" />
                    </div>
                </div>

                <div class="modal-footer"> 
                    <input type = "button" name = "btn_cancel_enquiry" id = "btn_cancel_enquiry" class = "btn btn-download" value = "Save Changes" onclick="return cancelEnquirySubmit();">
                </div>
            </form>
        </div>
    <?php
}
else if ($_REQUEST['action'] == 'SubmitCancelEnquiry') {
    $success = 0;
    $errors = array(); 
    $i = 0;

    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $eventId            = $_POST['event_id'];
        $enquiryCancelForm  = $_POST['enquiry_cancel_from'];
        $cancellationReason = $_POST['cancellation_reason'];

        if ($enquiryCancelForm == '') {
            $success = 0;
            $errors[$i++] = "Please select enquiry cancellation from";
        }

        if ($cancellationReason == '') {
            $success = 0;
            $errors[$i++] = "Please enter enquiry cancellation reason";
        }

        if (count($errors)) {
            echo "validationError";
            exit;
        } else {
            $success = 1;
            $arr['event_id']            = $eventId;
            $arr['enquiry_cancel_from'] = $enquiryCancelForm;
            $arr['cancellation_reason'] = $cancellationReason;

            $updateRecord = $eventClass->updateEnquiryStatus($arr);

            if (!empty($updateRecord)) {
                echo "success";
                exit;
            } else {
                echo "error";
                exit;
            }

        }
    }
} else if ($_REQUEST['action'] == 'vw_add_follow_up_inquiry') {
    $eventId = $db->escape($_REQUEST['event_id']);
    $existFollowUpId = $db->escape($_REQUEST['follow_up_id']);
    ?>
       <div class = "modal-header">
            <button type = "button" class = "close" data-dismiss = "modal"><span aria-hidden = "true">&times;</span>
                <span class = "sr-only">Close</span>
            </button>
            <h4 class = "modal-title">Enquiry Follow Up</h4>
       </div>
        <div class = "modal-body">
            <form class = "form-inline" name = "frmAddFollowUp" id = "frmAddFollowUp" method = "post" action = "event_ajax_process.php?action=SubmitEnquiryFollowUp" autocomplete="off">
                <input type="hidden" name="exist_follow_up_id" id="exist_follow_up_id" value="<?php echo $existFollowUpId; ?>" />
                <!-- Add Date picker code start here -->
                <div class = "editform" style="margin:10px 0px 10px 0px;">
                    <label>
                        Select Date : <span style="color:red;">*</span> 
                    </label>
                    <div class = "value">
                        <input type = "text" name = "follow_up_date" id = "follow_up_date" class = "validate[required] form-control followup_Datepicker">
                    </div>
                </div>
                <!-- Add Date picker code ends here -->
                
                <!-- Add select time picker code start here -->
                <div class = "editform" style="margin:10px 0px 10px 0px;">
                    <label>
                        Select Time : <span style="color:red;">*</span> 
                    </label>
                    <div class = "value">
                    <input name = "follow_up_time" id = "follow_up_time" type = "text" class = "validate[required] form-control followup_time" autocomplete = "off" style="width :50% !important;">
                    </div>
                </div>
                <!-- Add select time picker code ends here -->
                
                <div class = "editform" style="margin:10px 0px 10px 0px;">
                    <label>
                        Follow up reason : <span style="color:red;">*</span> 
                    </label> 
                    <div class="value">
                        <input type = "hidden" name = "event_id" id = "event_id"  value = "<?php if(!empty($eventId)) { echo $eventId; } ?>" />
                        <input type = "textarea" name = "follow_up_desc" id = "follow_up_desc" class = "validate[required] form-control" />
                    </div>
                </div>

                <div class="modal-footer"> 
                    <input type = "button" name = "btn_enquiry_follow_up" id = "btn_enquiry_follow_up" class = "btn btn-download" value = "Save Changes" onclick="return enquiryFollowUpSubmit();">
                </div>
            </form>
        </div>
    <?php
} else if ($_REQUEST['action'] == 'SubmitEnquiryFollowUp') {
    $success = 0;
    $errors = array(); 
    $i = 0;

    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $eventId      = $_POST['event_id'];
        $followUpDate = $_POST['follow_up_date'];
        $followUpTime = $_POST['follow_up_time'];
        $followUpDesc = $_POST['follow_up_desc'];

        if ($followUpDate == '') {
            $success = 0;
            $errors[$i++] = "Please select follow up date";
        }

        if ($followUpTime == '') {
            $success = 0;
            $errors[$i++] = "Please select follow up time";
        }

        if ($followUpDesc == '') {
            $success = 0;
            $errors[$i++] = "Please enter follow up description";
        }

        if (count($errors)) {
            echo "validationError";
            exit;
        } else {
            $success = 1;
            $arr['event_id']            = $eventId;
            $tomorrow = date("Y-m-d", time() + 86400);
            $arr['follow_up_date'] = ($followUpDate ? date('Y-m-d', strtotime($followUpDate)) : $tomorrow);
            $arr['follow_up_time'] = ($followUpTime ? $followUpTime : "11:00 AM");
            $arr['follow_up_desc'] = $followUpDesc;

            $recordId = $eventClass->addEnquiryFollowUp($arr);

            if (!empty($recordId)) {
                echo "success";
                echo "HtmlSeperator";
                echo $eventId . "_" . $recordId;
                exit;
            } else {
                echo "error";
                exit;
            }

        }
    }
} else if ($_REQUEST['action'] == 'change_notification_status') {
    $followUpId = $_REQUEST['follow_up_id'];
    $eventId = $_REQUEST['event_id'];
    if (!empty($followUpId)) {
        // First check is it notification entry present
        $chkRecordExists = "SELECT follow_up_id FROM sp_enquiry_follow_up WHERE follow_up_id ='" . $followUpId . "'";

        if (mysql_num_rows($db->query($chkRecordExists)))
        {
            $recordId = $eventClass->changeEnquiryNotificationStatus($followUpId);
            if (!empty($recordId)) {
                echo "success";
                exit;
            } else {
                echo "error";
                exit;
            }
        } else {
            echo "recordNotFound";
            exit;
        }
    } else {
        echo "missingParameter";
        exit;
    }
}
?>     