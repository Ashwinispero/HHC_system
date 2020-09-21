<?php
require_once 'inc_classes.php';
require_once '../classes/employeesClass.php';
require_once '../classes/commonClass.php';
$employeesClass=new employeesClass();
$commonClass=new commonClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
require_once "../classes/config.php";
require_once "../classes/functions.php";
if($_REQUEST['action']=='vw_add_employee')
{
    // Getting Employee Details
    $arr['employee_id']=$_REQUEST['employee_id'];
    $EmpDtls=$employeesClass->GetEmployeeById($arr);
    // Get All Location
    $LocationList=$commonClass->GetAllLocations();
    // Get All hospital List 
    $HospitalList=$commonClass->GetAllHospitals();   
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($EmpDtls)) { echo "Edit"; } else { echo "Add"; } ?> Employee </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_employee" id="frm_add_employee" method="post" action ="employee_ajax_process.php?action=add_employee" autocomplete="off">
            <div class="scrollbars">
                <div class="editform">
                    <label>Select Employee Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="type" id="type" class="validate[required]">
                                    <option value=""<?php if($_POST['type']=='') { echo 'selected="selected"'; } else if($EmpDtls['type']=='') { echo 'selected="selected"'; } ?>>Employee Type</option>
                                    <option value="1"<?php if($_POST['type']=='1') { echo 'selected="selected"'; } else if($EmpDtls['type']=='1') { echo 'selected="selected"'; }?>>HCM</option>
                                    <option value="2"<?php if($_POST['type']=='2') { echo 'selected="selected"'; } else if($EmpDtls['type']=='2') { echo 'selected="selected"'; }?>>HD</option>
                                    <option value="3"<?php if($_POST['type']=='3') { echo 'selected="selected"'; } else if($EmpDtls['type']=='3') { echo 'selected="selected"'; }?>>Accountant</option>
                                    <option value="4"<?php if($_POST['type']=='4') { echo 'selected="selected"'; } else if($EmpDtls['type']=='4') { echo 'selected="selected"'; }?>>Office Assistant</option>
                                    <option value="5"<?php if($_POST['type']=='5') { echo 'selected="selected"'; } else if($EmpDtls['type']=='5') { echo 'selected="selected"'; }?>>Trainer</option>
                                </select>
                            </label>
                        </div>
                </div>
                 <div class="editform">
                   <label>Select Hospital <span class="required">*</span></label>
                       <div class="value dropdown">
                           <label>
                               <select name="hospital_id" id="hospital_id" class="validate[required]">
                                   <option value=""<?php if($_POST['hospital_id']=='') { echo 'selected="selected"'; } else if($EmpDtls['hospital_id']=='') { echo 'selected="selected"'; } ?>>Hospital</option>
                                    <?php
                                        foreach($HospitalList as $key=>$valHospital)
                                        {
                                            if($EmpDtls['hospital_id'] == $valHospital['hospital_id'])
                                                echo '<option value="'.$valHospital['hospital_id'].'" selected="selected">'.$valHospital['hospital_name'].'</option>';
                                            else if($_POST['hospital_id'] == $valHospital['hospital_id'])
                                                echo '<option value="'.$valHospital['hospital_id'].'" selected="selected">'.$valHospital['hospital_name'].'</option>';
                                            else
                                                echo '<option value="'.$valHospital['hospital_id'].'">'.$valHospital['hospital_name'].'</option>';
                                        }                            
                                    ?>
                               </select>
                           </label>
                       </div>
                </div>        
                <div class="editform">
                    <label>Last Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $arr['employee_id']; ?>" />
                        <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])) { echo $_POST['name']; } else if(!empty($EmpDtls['name'])) { echo $EmpDtls['name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>First Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="first_name" id="first_name" value="<?php if(!empty($_POST['first_name'])) { echo $_POST['first_name']; } else if(!empty($EmpDtls['first_name'])) { echo $EmpDtls['first_name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Middle Name</label>
                    <div class="value">
                        <input type="text" name="middle_name" id="middle_name" value="<?php if(!empty($_POST['middle_name'])) { echo $_POST['middle_name']; } else if(!empty($EmpDtls['middle_name'])) { echo $EmpDtls['middle_name']; } else { echo ""; } ?>" class="validate[maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Email Address <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="email_id" id="email_id" value="<?php if(!empty($_POST['email_id'])) { echo $_POST['email_id']; } else if(!empty($EmpDtls['email_id'])) { echo $EmpDtls['email_id']; } else { echo ""; } ?>" class="validate[required,custom[email]] form-control" maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <?php if(empty($EmpDtls)) { ?>
                <div class="editform">
                    <label>Password <span class="required">*</span></label>
                    <div class="value">
                        <input type="password" name="password" id="password" value="<?php if(!empty($_POST['password'])) { echo $_POST['password']; } else { echo ""; } ?>" class="validate[required,custom[passwordVal],minSize[8],maxSize[15]] form-control" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Confirm Password <span class="required">*</span></label>
                    <div class="value">
                        <input type="password" name="confirm_password" id="confirm_password" value="<?php if(!empty($_POST['confirm_password'])) { echo $_POST['confirm_password']; } else { echo ""; } ?>" class="validate[required,custom[passwordVal],minSize[8],equals[password],maxSize[15]] form-control" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <?php } ?>
                <div class="editform">
                    <label>Phone</label>
                    <div class="value">
                        <input type="text" name="phone_no" id="phone_no" value="<?php if(!empty($_POST['phone_no'])) { echo $_POST['phone_no']; } else if(!empty($EmpDtls['phone_no'])) { echo $EmpDtls['phone_no']; }  else { echo ""; } ?>" class="validate[minSize[10],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Mobile <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($EmpDtls['mobile_no'])) { echo $EmpDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Birth Date</label>
                    <div class="value">
                        <input type="text" name="dob" id="dob" value="<?php if(!empty($_POST['dob'])) { echo date('d-m-Y',strtotime($_POST['dob'])); } if(!empty($EmpDtls['dob']) && $EmpDtls['dob'] !='0000-00-00') { echo date('d-m-Y',strtotime($EmpDtls['dob'])); }  else { echo ""; } ?>" class="form-control datepicker" style="width:100% !important;"  />
                    </div>
                </div>
                <div class="editform">
                    <label>Location <span class="required">*</span></label>
                    <div class="value dropdown">
                        <label>
                            <select name="location_id" id="location_id" class="validate[required]">
                                <option value=""<?php if($_POST['location_id']=='') { echo 'selected="selected"'; } else if($EmpDtls['location_id']=='') { echo 'selected="selected"'; } ?>>Location</option>
                                <?php
                                    foreach($LocationList as $key=>$valLocation)
                                    {
                                        if($EmpDtls['location_id'] == $valLocation['location_id'])
                                            echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                        else if($_POST['location_id'] == $valLocation['location_id'])
                                            echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                        else
                                            echo '<option value="'.$valLocation['location_id'].'">'.$valLocation['location'].'</option>';
                                    }                            
                                ?>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="editform">
                    <label>Address</label>
                    <div class="value">
                        <textarea name="address" id="address" class="form-control" maxlength="160" style="width: 265px; height: 100px;"><?php if(!empty($_POST['address'])) { echo $_POST['address']; } else if(!empty($EmpDtls['address'])) { echo $EmpDtls['address']; }  else { echo ""; } ?></textarea>
                    </div>
                </div>
                <div class="editform">
                    <label>Designation</label>
                    <div class="value">
                        <input type="text" name="designation" id="designation" value="<?php if(!empty($_POST['designation'])) { echo $_POST['designation']; } else if(!empty($EmpDtls['designation'])) { echo $EmpDtls['designation']; } else { echo ""; } ?>" class="validate[maxSize[70]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Work Phone</label>
                    <div class="value">
                        <input type="text" name="work_phone_no" id="work_phone_no" value="<?php if(!empty($_POST['work_phone_no'])) { echo $_POST['work_phone_no']; } else if(!empty($EmpDtls['work_phone_no'])) { echo $EmpDtls['work_phone_no']; }  else { echo ""; } ?>" class="validate[minSize[11],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Work Email Address</label>
                    <div class="value">
                        <input type="text" name="work_email_id" id="work_email_id" value="<?php if(!empty($_POST['work_email_id'])) { echo $_POST['work_email_id']; } else if(!empty($EmpDtls['work_email_id'])) { echo $EmpDtls['work_email_id']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" onblur="javascript:return chkEmails();"  />
                        <div id="form_error" class="formErrorSelf"></div>
                    </div>
                </div>
                <div class="editform">
                    <label>Qualification</label>
                    <div class="value">
                        <input type="text" name="qualification" id="qualification" value="<?php if(!empty($_POST['qualification'])) { echo $_POST['qualification']; } else if(!empty($EmpDtls['qualification'])) { echo $EmpDtls['qualification']; } else { echo ""; } ?>" class="validate[maxSize[70]] form-control" onkeyup="if (/[^A-Za-z.(), ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z.(), ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Specialization</label>
                    <div class="value">
                        <input type="text" name="specialization" id="specialization" value="<?php if(!empty($_POST['specialization'])) { echo $_POST['specialization']; } else if(!empty($EmpDtls['specialization'])) { echo $EmpDtls['specialization']; } else { echo ""; } ?>" class="validate[maxSize[70]] form-control" onkeyup="if (/[^A-Za-z.(), ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z.(), ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Work Experience</label>
                    <div class="value">
                        <input type="text" name="work_experience" id="work_experience" value="<?php if(!empty($_POST['work_experience'])) { echo $_POST['work_experience']; } else if(!empty($EmpDtls['work_experience'])) { echo $EmpDtls['work_experience']; } else { echo ""; } ?>" class="validate[maxSize[70]] form-control" onkeyup="if (/[^A-Za-z0-9  ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9 ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
              </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_employee_submit();" />
                </div>  
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='add_employee')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $employee_id=strip_tags($_POST['employee_id']);
        $type=strip_tags($_POST['type']);
        $hospital_id=strip_tags($_POST['hospital_id']);
        $name=strip_tags($_POST['name']);
        $first_name=strip_tags($_POST['first_name']);
        $middle_name=strip_tags($_POST['middle_name']);
        $designation=strip_tags($_POST['designation']);
        $email_id=strip_tags($_POST['email_id']);
        $password=strip_tags($_POST['password']);
        $confirm_password=strip_tags($_POST['confirm_password']);
        $phone_no=strip_tags($_POST['phone_no']);
        $mobile_no=strip_tags($_POST['mobile_no']);
        if(!empty($_POST['dob']))
        {
            $dob=date('Y-m-d',strtotime($_POST['dob']));
        }
        $address=$_POST['address'];
        $work_phone_no=strip_tags($_POST['work_phone_no']);
        $work_email_id=strip_tags($_POST['work_email_id']);
        $location_id=strip_tags($_POST['location_id']);
        $qualification=strip_tags($_POST['qualification']);
        $specialization=strip_tags($_POST['specialization']);
        $work_experience=strip_tags($_POST['work_experience']);
        if($type=='')
        {
            $success=0;
            $errors[$i++]="Please enter name";
        }
        if($hospital_id=='')
        {
            $success=0;
            $errors[$i++]="Please select hospital";
        }
        if($name=='')
        {
            $success=0;
            $errors[$i++]="Please enter last name";
        }
		if($first_name=='')
        {
            $success=0;
            $errors[$i++]="Please enter first name";
        }
        /*if($email_id=='')
        {
            $success=0;
            $errors[$i++]="Please enter email address";
        }*/
        if(empty($employee_id))
        {
            if($password=='')
            {
                $success=0;
                $errors[$i++]="Please enter password";
            }
            if($confirm_password=='')
            {
                $success=0;
                $errors[$i++]="Please enter confirm password";
            }
        }
        /*if($phone_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter landline number";
        }
        if($mobile_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter mobile number";
        }
        if($dob=='')
        {
            $success=0;
            $errors[$i++]="Please select birth date";
        }
        if($address=='')
        {
            $success=0;
            $errors[$i++]="Please enter address";
        }
        if($work_phone_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter work phone number";
        }
        if($work_email_id=='')
        {
            $success=0;
            $errors[$i++]="Please enter work email address";
        }
        if($location_id=='')
        {
            $success=0;
            $errors[$i++]="Please select location";
        }
        if($qualification=='')
        {
            $success=0;
            $errors[$i++]="Please enter qualification";
        }
        if($specialization=='')
        {
            $success=0;
            $errors[$i++]="Please enter specialization";
        }
        if($work_experience=='')
        {
            $success=0;
            $errors[$i++]="Please enter work experience";
        }*/
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        // Check Record Exists 
        if($employee_id)
            $chk_employee_sql="SELECT employee_id FROM sp_employees WHERE email_id='".$email_id."' AND employee_id !='".$employee_id."'";
        else 
            $chk_employee_sql="SELECT employee_id FROM sp_employees WHERE email_id='".$email_id."'"; 
        
        if(mysql_num_rows($db->query($chk_employee_sql)))
        {
            $success=0;
            echo 'employeeexists';
            exit;
        }
        if(count($errors))
        {
           echo 'employeeexists'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $arr['employee_id']=$employee_id;
            $arr['type']=$type;
            $arr['hospital_id']=$hospital_id;
            $arr['name']=ucwords(strtolower($name));
            $arr['first_name']=ucwords(strtolower($first_name));
            $arr['middle_name']=ucwords(strtolower($middle_name));
            $arr['designation']=ucfirst($designation);
            $arr['email_id']=strtolower($email_id);
            $arr['password']=$password;
            $arr['phone_no']=$phone_no;
            $arr['mobile_no']=$mobile_no;
            $arr['dob']=$dob;
            $arr['address']=$address;
            $arr['work_phone_no']=$work_phone_no;
            $arr['work_email_id']=strtolower($work_email_id);
            $arr['location_id']=$location_id;
            $arr['qualification']=$qualification;
            $arr['specialization']=$specialization;
            $arr['work_experience']=$work_experience;
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($employee_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$employeesClass->AddEmployee($arr); 
            if(!empty($InsertRecord))
            {
                if($employee_id)
                {
                    echo 'UpdateSuccess'; // Update Record
                    exit;
                }
                else 
                {
                    echo 'InsertSuccess'; // Insert Record
                    exit;
                }
            }
            else 
            {
               echo 'employeeexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['employee_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['employee_id'] =$_REQUEST['employee_id'];
        if($_REQUEST['actionval']=='Active')
            $arr['status']='1';
        if($_REQUEST['actionval']=='Inactive')
           $arr['status']='2';
        if($_REQUEST['actionval']=='Delete')
           $arr['status']='3';
        if($_REQUEST['actionval']=='Revert')
        {
            if(!empty($_REQUEST['curr_status']))
                $arr['status']=$_REQUEST['curr_status'];
            else 
                $arr['status']='1';
        }
        if($_REQUEST['actionval']=='CompleteDelete')
           $arr['status']='5';
        
        $arr['curr_status']=$_REQUEST['curr_status'];
        $arr['login_user_id']=$_REQUEST['login_user_id'];
        $arr['istrashDelete']=$_REQUEST['trashDelete'];

        $ChangeStatus =$employeesClass->ChangeStatus($arr);
        if(!empty($ChangeStatus))
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
}
else if($_REQUEST['action']=='vw_employee')
{
    // Getting Employee Details
    $arr['employee_id']=$_REQUEST['employee_id'];
    $EmpDtls=$employeesClass->GetEmployeeById($arr);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Employee Details</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="editform">
                <label>Employee Code</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['employee_code'])) { echo $EmpDtls['employee_code']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Type</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['typeVal'])) { echo $EmpDtls['typeVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Hospital Name</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['hospitalNm'])) { echo $EmpDtls['hospitalNm']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Name</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['name'])) { echo $EmpDtls['name']." "; } if(!empty($EmpDtls['first_name'])) { echo $EmpDtls['first_name']." "; } if(!empty($EmpDtls['middle_name'])) { echo $EmpDtls['middle_name']; } else {  echo ""; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Designation</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['designation'])) { echo $EmpDtls['designation']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Email Address</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['email_id'])) { echo $EmpDtls['email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Phone Number</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['phone_no'])) { echo $EmpDtls['phone_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Mobile Number</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['mobile_no'])) { echo $EmpDtls['mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Birth Date</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['dob']) && $EmpDtls['dob']!='0000-00-00') { echo date('d M Y',strtotime($EmpDtls['dob'])); } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Address</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['address'])) { echo $EmpDtls['address']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Phone</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['work_phone_no'])) { echo $EmpDtls['work_phone_no']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Email Address</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['work_email_id'])) { echo $EmpDtls['work_email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Location</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['locationNm'])) { echo $EmpDtls['locationNm']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>PIN Code</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['LocationPinCode'])) { echo $EmpDtls['LocationPinCode']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Qualification</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['qualification'])) { echo $EmpDtls['qualification']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Specialization</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['specialization'])) { echo $EmpDtls['specialization']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Experience</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['work_experience'])) { echo $EmpDtls['work_experience']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Status</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['statusVal'])) { echo $EmpDtls['statusVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['added_by'])) { echo $EmpDtls['added_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value"> 
                    <?php if(!empty($EmpDtls['added_date']) && $EmpDtls['added_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A', strtotime($EmpDtls['added_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified By</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['last_modified_by'])) { echo $EmpDtls['last_modified_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified Date</label>
                <div class="value">
                    <?php if(!empty($EmpDtls['last_modified_date']) && $EmpDtls['last_modified_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A',strtotime($EmpDtls['last_modified_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
        </div>
    </div>
<?php
}

else if($_REQUEST['action'] == 'ImportExcel')
{
    ?>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Import Employee File</h4>
        </div>
        <div class="modal-body">
            <form class="form-inline" name="frm_add_employee_excel" id="frm_add_employee_excel" method="post"  enctype="multipart/form-data" action="employee_ajax_process.php?action=SubmitEmployeeExcelForm" autocomplete="off">
                <div class="editform" >
                    <label>Upload Employee File</label>
                    <div class="value" >
                        <input type="file" name="employeeFile" id="employeeFile" class="brochurefile" />
                        <br><br>
                        <a href="include/employeeExcel.xls"  target="_blank"><img src="images/icon-xls25.png" /> Sample File </a>
                    </div>
                </div>
                
                 <div class="editform" >
                    <label style="color:#e7394d;font-size:15px;">Important Notes :-</label>
                    <div class="value" style="color:#e7394d;font-size:13px;">
                        Employee Type,Hospital Name,Last Name,First Name, Email Address,Password,Mobile Number,Location fields are compulsory.<br/>
                        Password should be contain 8-15 characters, including at least one upper case letter,one lowercase letter,one special character and one number.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Upload" onclick="return employee_excel_submit();" />
                </div>
            </form>
        </div>
    </div>
    <?php 
}
else if($_REQUEST['action']=='SubmitEmployeeExcelForm')
{
   $success=0;
   $errors=array(); 
   $i=0;
   if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
   {
        $employeeFile_image="";
        if(count($errors)==0 && $_FILES['employeeFile']["name"])
        {

            $file_str=preg_replace('/\s+/', '_', $_FILES['employeeFile']["name"]);
            $employeeFile_image=time().basename($file_str);
            $newfile = "employeeImport/";

            $filename = $_FILES['employeeFile']['tmp_name']; // File being uploaded.
            $filetype = $_FILES['employeeFile']['type']; // type of file being uploaded
            $filesize = filesize($filename); // File size of the file being uploaded.
            $source1 = $_FILES['employeeFile']['tmp_name'];
            $target_path1 = $newfile.$employeeFile_image;

            $filename_temp = basename($_FILES['employeeFile']['name']);
             $ext = substr($filename_temp, strrpos($filename_temp, '.') + 1);

                 list($width1, $height1, $type1, $attr1) = getimagesize($source1);
                 if(strtolower($ext) == "xls"  || strtolower($ext) == "xlsx" ) //|| strtolower($ext) == "csv" )
                 {
                     if(move_uploaded_file($source1, $target_path1))
                     {
                         $thump_target_path="employeeImport/".$employeeFile_image;
                         copy($target_path1,$thump_target_path);
                         $file_uploaded1=1;
                     }
                     else
                     {
                         $file_uploaded1=0;
                         $success=0;
                         $errors[$i++]="There are some errors while uploading xls, please try again";
                     }
                 }
                 else
                 {
                     $file_uploaded1=0;
                     $success=0;
                     $errors[$i++]="Only xls files allowed";

                 }


        }
        else
        {
            $file_uploaded1=0;
            $success=0;
            $errors[$i++]="Please upload Professional excel file";
        }
        if(count($errors))
        {
            $errorBulk = 'yes';  
            for($k=0;$k<count($errors);$k++)
                echo ''.$errors[$k].'';
        }
        else 
        { 
           
            include 'excel_reader.php';     // include the class
            // creates an object instance of the class, and read the excel file data
            $excel = new PhpExcelReader;
          //   echo "3professionalImport/".$employeeFile_image;
            $excel->read("employeeImport/".$employeeFile_image);
           // Excel file data is stored in $sheets property, an Array of worksheets
            
            // this function creates and returns a HTML table with excel rows and columns data
            // Parameter - array with excel worksheet data
            function sheetData($sheet) 
                {
                        $x = 2; //2
                        //$recordExist = 'no';
                        $cell = '';
                        while($x <= $sheet['numRows']) 
                        {
                            $y = 1;
                            
                            while($y <= $sheet['numCols']) 
                            {
                                
                                //print_r($sheet['cells']);
                                //$re .= $sheet['cells'][2][3];
                              $cell .= isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              //$re .= $cell;  
                              if($y == '1')
                                  $cellarr['employee_type'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              else if($y == '2')
                                  $cellarr['hosp_name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              else if($y == '3')
                                  $cellarr['last_name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              else if($y == '4')
                                  $cellarr['first_name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              else if($y == '5')
                                  $cellarr['middle_name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              else if($y == '6')
                                  $cellarr['email_id'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              else if($y == '7')
                                  $cellarr['password'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              else if($y == '8')
                                  $cellarr['mobile'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              else if($y == '9')
                                  $cellarr['location'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                              
                              
                              
                              //$val[] = $sheet['cells'][$x][$y].':,'.$sheet['cells'][$x][++$y];
                              $y++;
                            }
                            $val[] = $cellarr;
                            $x++;                            
                        }
                        //print_r($val);
                        $data = $val;
                        
                        return $val;
                    
                }
            $nr_sheets = count($excel->sheets);    // gets the number of sheets
            $excel_data = '';              // to store the the html tables with data of each sheet
            // traverses the number of sheets and sets html table with each sheet data in $excel_data
            $type=str_replace(' ','',$excel->sheets[0]['cells'][1][1]);  
            $hospital_name=str_replace(' ','',$excel->sheets[0]['cells'][1][2]);
            $name=str_replace(' ','',$excel->sheets[0]['cells'][1][3]);
            $first_name=str_replace(' ','',$excel->sheets[0]['cells'][1][4]);
            $middle_name=str_replace(' ','',$excel->sheets[0]['cells'][1][5]);
            $email_id=str_replace(' ','',$excel->sheets[0]['cells'][1][6]);
            $password=str_replace(' ','',$excel->sheets[0]['cells'][1][7]);
            $mobile_no=str_replace(' ','',$excel->sheets[0]['cells'][1][8]);
            $location_name=str_replace(' ','',$excel->sheets[0]['cells'][1][9]);
            if((strtolower($type)=='employeetype') && (strtolower($hospital_name) == 'hospitalname') && (strtolower($name) == 'lastname') && (strtolower($first_name) == 'firstname') && (strtolower($middle_name) == 'middlename') && (strtolower($email_id) == 'emailaddress') && (strtolower($password) == 'password') && (strtolower($mobile_no) == 'mobile') && (strtolower($location_name) == 'location'))
            {
               $totalRowsCount = $excel->sheets[0]['numRows']; 
               // echo 'cols'.$excel->sheets[0]['numCols'];
               // exit;
               /*
                * add employee excel import data 
                */
               
               $excel_data = sheetData($excel->sheets[0]);
                    //print_r($excel_data);
               //------------------- new code for import employee
                for($j=0;$j<count($excel_data);$j++)
                {
                    $employee_type = $db->escape(trim($excel_data[$j]['employee_type'])); 
                    $hosp_name = $db->escape(trim($excel_data[$j]['hosp_name'])); 
                    $name = $db->escape(trim($excel_data[$j]['last_name'])); 
                    $first_name = $db->escape(trim($excel_data[$j]['first_name'])); 
                    $middle_name = $db->escape(trim($excel_data[$j]['middle_name'])); 
                    $email_id = $db->escape(trim($excel_data[$j]['email_id'])); 
                    $password = $db->escape(trim($excel_data[$j]['password'])); 
                    $mobile = $db->escape(trim($excel_data[$j]['mobile'])); 
                    $location = $db->escape(trim($excel_data[$j]['location'])); 

                    if(!empty($employee_type) &&  !empty($hosp_name) &&  !empty($name) &&  !empty($first_name) &&  !empty($email_id) &&  !empty($location) &&  !empty($password)  &&  !empty($mobile))
                    {
                        $EmployeeId='';
                        
                        if(strtolower($employee_type) == 'hcm')
                            $typeVal="1";
                        else if(strtolower($employee_type) == 'hd')
                            $typeVal="2";
                        else if(strtolower($employee_type) == 'accountant')
                            $typeVal="3";
                        else if(strtolower($employee_type) == 'officeassistant')
                            $typeVal="4";
                        else if(strtolower($employee_type) == 'trainer')
                            $typeVal="5";
                        
                        $getHospitalSql="SELECT hospital_id FROM sp_hospitals WHERE hospital_name='".$hosp_name."'";
                        $getHospital=$db->fetch_array($db->query($getHospitalSql));
                        if(!empty($getHospital))
                        {
                           $hospital_id=$getHospital['hospital_id'];
                        }
                        else 
                        {
                           $hospital_id="";
                        } 
                        
                        $getLocationSql="SELECT location_id FROM sp_locations WHERE location='".$location."'";
                        $getLocation=$db->fetch_array($db->query($getLocationSql));
                        if(!empty($getLocation))
                        {
                           $location_id=str_replace(' ','',$getLocation['location_id']); 
                        }
                        else 
                        {
                           $location_id=""; 
                        } 
                        $importData['type'] = $typeVal;
                        $importData['hospital_id'] = $hospital_id;
                        $importData['name'] = $name;
                        $importData['first_name'] = $first_name;
                        $importData['middle_name'] = $middle_name;
                        $importData['email_id'] = $email_id;
                        $importData['password'] = md5($password);
                        $importData['mobile_no'] = $mobile;
                        $importData['location_id'] = $location_id;
                        $importData['added_by']=strip_tags($_SESSION['admin_user_id']);
                        $importData['added_date']=date('Y-m-d H:i:s');
                        
                        $ChkEmployeeSql="SELECT employee_id FROM sp_employees WHERE email_id='".$email_id."' "; 
                        $EmployeeResult=$db->fetch_array($db->query($ChkEmployeeSql));
                        if(!empty($EmployeeResult))
                        {
                             $EmployeeId=$EmployeeResult['employee_id'];
                        }
                   
                        
                        if(!empty($EmployeeId))
                        {
                            $importData['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
                            $importData['last_modified_date']=date('Y-m-d H:i:s');
                            $where = "employee_id='".$EmployeeId."'";
                            $RecordId=$db->query_update('sp_employees',$importData,$where); 
                        }
                        else 
                        {
                            
                            $GetMaxRecordIdSql="SELECT MAX(employee_id) AS MaxId FROM sp_employees";
                            if($db->num_of_rows($db->query($GetMaxRecordIdSql)))
                            {
                                $MaxRecord=$db->fetch_array($db->query($GetMaxRecordIdSql));
                                $getMaxRecordId=$MaxRecord['MaxId'];
                            }
                            else 
                            {
                                $getMaxRecordId=0;
                            }
                            $prefix=$GLOBALS['EmpPrefix'];
                            $EmpCode=Generate_Number($prefix,$getMaxRecordId);
                            $importData['employee_code'] = $EmpCode;
                            $importData['status']='1';
                            $RecordId=$db->query_insert('sp_employees',$importData); 
                        }
                        
                        unset($getMaxRecordId);
                        unset($EmpCode);
                        unset($importData);
                    }
                }
               //===============================
                echo 'success';  
                exit;
            }
            else
            {
                echo 'error';
                exit;
            }
        }   

    } 
}
?>