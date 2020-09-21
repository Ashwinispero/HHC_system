<?php
require_once 'inc_classes.php';
require_once '../classes/adminuserClass.php';
$adminuserClass=new adminuserClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='vw_add_system_user')
{
    // Getting System User Details
    $arr['admin_user_id']=$_REQUEST['admin_user_id'];
    $UserDtls=$adminuserClass->GetAdminUserById($arr);
    // Getting All Modules
    $ModuleDtls=$adminuserClass->GetAllPermissions();
    // Getting All Permission Details 
    $PermissionsDtls=$adminuserClass->GetUserPermissionsById($arr); 
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($UserDtls)) { echo "Edit"; } else { echo "Add"; } ?> System User </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_system_user" id="frm_add_system_user" method="post" action ="system_user_ajax_process.php?action=add_system_user" autocomplete="off">
            <div class="scrollbars">
                <div class="editform">
                    <label>Select User Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="type" id="type" class="validate[required]">
                                    <option value=""<?php if($_POST['type']=='') { echo 'selected="selected"'; } else if($UserDtls['type']=='') { echo 'selected="selected"'; } ?>>User Type</option>
                                    <option value="1"<?php if($_POST['type']=='1') { echo 'selected="selected"'; } else if($UserDtls['type']=='1') { echo 'selected="selected"'; }?>>Super Admin</option>
                                    <option value="2"<?php if($_POST['type']=='2') { echo 'selected="selected"'; } else if($UserDtls['type']=='2') { echo 'selected="selected"'; }?>>Admin</option>
                                    <option value="3"<?php if($_POST['type']=='3') { echo 'selected="selected"'; } else if($UserDtls['type']=='3') { echo 'selected="selected"'; }?>>HR Admin</option>
                                </select>
                            </label>
                        </div>
                </div>
                <div class="editform">
                    <label>Last Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="admin_user_id" id="admin_user_id" value="<?php echo $arr['admin_user_id']; ?>" />
                        <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])) { echo $_POST['name']; } else if(!empty($UserDtls['name'])) { echo $UserDtls['name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>First Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="first_name" id="first_name" value="<?php if(!empty($_POST['first_name'])) { echo $_POST['first_name']; } else if(!empty($UserDtls['first_name'])) { echo $UserDtls['first_name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;"  />
                    </div>
                </div>
                <div class="editform">
                    <label>Middle Name</label>
                    <div class="value">
                        <input type="text" name="middle_name" id="middle_name" value="<?php if(!empty($_POST['middle_name'])) { echo $_POST['middle_name']; } else if(!empty($UserDtls['middle_name'])) { echo $UserDtls['middle_name']; } else { echo ""; } ?>" class="validate[maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Email Address <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="email_id" id="email_id" value="<?php if(!empty($_POST['email_id'])) { echo $_POST['email_id']; } else if(!empty($UserDtls['email_id'])) { echo $UserDtls['email_id']; } else { echo ""; } ?>" class="validate[required,custom[email]] form-control" maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <?php if(empty($UserDtls)) { ?>
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
                    <label>Phone </label>
                    <div class="value">
                        <input type="text" name="landline_no" id="landline_no" value="<?php if(!empty($_POST['landline_no'])) { echo $_POST['landline_no']; } else if(!empty($UserDtls['landline_no'])) { echo $UserDtls['landline_no']; }  else { echo ""; } ?>" class="validate[minSize[10],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Mobile <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($UserDtls['mobile_no'])) { echo $UserDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Alternate Email Address </label>
                    <div class="value">
                        <input type="text" name="alternate_email_id" id="alternate_email_id" value="<?php if(!empty($_POST['alternate_email_id'])) { echo $_POST['alternate_email_id']; } else if(!empty($UserDtls['alternate_email_id'])) { echo $UserDtls['alternate_email_id']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" onblur="javascript:return chkEmails();" />
                        <div id="form_error" class="formErrorSelf"></div>
                    </div>
                </div>
                
                <div class="editform">
                    <label>Assign Modules</label>
                    <div class="value">
                    </div>
                </div>
                <div class="row">
                     <div class="col-lg-12">
                        <?php
                            $count = array();
                            $i = 0;
                            foreach($PermissionsDtls AS $key=>$allSelectedPermission) 
                            {
                                $allpermissionval .= $allSelectedPermission['module_id'];
                                $count[$i]=$allSelectedPermission['module_id'];
                                $i++; 
                            }
                            foreach($ModuleDtls AS $key=>$allModules) 
                            {
                                 $class = ''; 
                                 for($t=0;$t<=count($PermissionsDtls);$t++)
                                 {
                                     if($allModules['module_id'] == $count[$t])
                                        $class = 'checked="checked"';                 
                                 }
                                 echo '<div class="col-md-6">';
                                        if($class != '')
                                            echo '<input '.$class.' type="checkbox" name="module_name[]" id="module_name" value="'.$allModules['module_id'].'" />';                                                                    
                                        else
                                            echo '<input type="checkbox" name="module_name[]" id="module_name" value="'.$allModules['module_id'].'" />';

                                      echo '<label style="margin-left:10px;">'.$allModules['module_name'].'</label>';
                                echo '</div>';
                            }
                         ?>
                    </div>
                </div>
               
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_system_user_submit();" />
                </div>  
            </div>
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='add_system_user')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $admin_user_id=strip_tags($_POST['admin_user_id']);
        $name=strip_tags($_POST['name']);
        $first_name=strip_tags($_POST['first_name']);
        $middle_name=strip_tags($_POST['middle_name']);
        $email_id=strip_tags($_POST['email_id']);
        $password=strip_tags($_POST['password']);
        $confirm_password=strip_tags($_POST['confirm_password']);
        $landline_no=strip_tags($_POST['landline_no']);
        $mobile_no=strip_tags($_POST['mobile_no']);
        $alternate_email_id=strip_tags($_POST['alternate_email_id']);
        $type=strip_tags($_POST['type']);
        if($type=='')
        {
            $success=0;
            $errors[$i++]="Please select type";
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
        if($email_id=='')
        {
            $success=0;
            $errors[$i++]="Please enter email address";
        }
        if(empty($admin_user_id))
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
        if($alternate_email_id)
        {
            if($email_id==$alternate_email_id)
            {
              $success=0;
              $errors[$i++]="Please enter different email address for alternate email ";  
            }
        }
        if(count($errors))
        {
            echo 'validationError';
            exit;
        }
        // Check Record Exists 
        if($admin_user_id)
            $chk_system_user_sql="SELECT email_id FROM sp_admin_users WHERE email_id='".$email_id."' AND admin_user_id !='".$admin_user_id."'";
        else 
            $chk_system_user_sql="SELECT email_id FROM sp_admin_users WHERE email_id='".$email_id."'"; 
        
        if(mysql_num_rows($db->query($chk_system_user_sql)))
        {
            $success=0;
            echo 'userexists';
            exit;
        }
        if(count($errors))
        {
           echo 'validationError'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $arr['admin_user_id']=$admin_user_id;
            $arr['name']=ucwords(strtolower($name));
            $arr['first_name']=ucwords(strtolower($first_name));
            $arr['middle_name']=ucwords(strtolower($middle_name));
            $arr['email_id']=strtolower($email_id);
            $arr['password']=$password;
            $arr['landline_no']=$landline_no;
            $arr['mobile_no']=$mobile_no;
            $arr['alternate_email_id']=$alternate_email_id;
            $arr['type']=$type;
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($admin_user_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$adminuserClass->AddAdminUser($arr); 
            if(!empty($InsertRecord))
            {
                // Asssign Permission
                $Totalpermissions=$_POST['module_name'];
              
                if($admin_user_id && $Totalpermissions)
                {
                    // Delete All Permission first of this user
                    $DelPermissionSql="DELETE FROM sp_admin_users_modules WHERE admin_user_id='".$admin_user_id."'";
                    $db->query($DelPermissionSql);
                }
                
                $Permissionarr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
                $Permissionarr['last_modified_date']=date('Y-m-d H:i:s');
                
                if($admin_user_id)
                    $Permissionarr['admin_user_id']=$admin_user_id;
                else 
                    $Permissionarr['admin_user_id']=$InsertRecord;
                    
               
                for($i=0;$i<count($Totalpermissions);$i++)
                {
                   $Permissionarr['module_id'] = $Totalpermissions[$i];
                   $RecordId=$adminuserClass->addUserPermission($Permissionarr); 
                }
                
               
                if($admin_user_id)
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
                echo 'userexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['admin_user_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['admin_user_id'] =$_REQUEST['admin_user_id'];
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

        $ChangeStatus =$adminuserClass->ChangeStatus($arr);
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
else if($_REQUEST['action']=='vw_user')
{
    // Getting User Details
    $arr['admin_user_id']=$_REQUEST['admin_user_id'];
    $UserDtls=$adminuserClass->GetAdminUserById($arr);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View User Details</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="editform">
                <label>Name</label>
                <div class="value">
                    <?php if(!empty($UserDtls['name'])) { echo $UserDtls['name']." "; } if(!empty($UserDtls['first_name'])) { echo $UserDtls['first_name']." "; } if(!empty($UserDtls['last_name'])) { echo $UserDtls['last_name']; } else {  echo ""; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Email Address</label>
                <div class="value">
                    <?php if(!empty($UserDtls['email_id'])) { echo $UserDtls['email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Phone Number</label>
                <div class="value">
                    <?php if(!empty($UserDtls['landline_no'])) { echo $UserDtls['landline_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Mobile Number</label>
                <div class="value">
                    <?php if(!empty($UserDtls['mobile_no'])) { echo $UserDtls['mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Alternate Email Address</label>
                <div class="value">
                    <?php if(!empty($UserDtls['alternate_email_id'])) { echo $UserDtls['alternate_email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Type</label>
                <div class="value">
                    <?php if(!empty($UserDtls['typeVal'])) { echo $UserDtls['typeVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Status</label>
                <div class="value">
                    <?php if(!empty($UserDtls['statusVal'])) { echo $UserDtls['statusVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value">
                    <?php if(!empty($UserDtls['added_by'])) { echo $UserDtls['added_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value"> 
                    <?php if(!empty($UserDtls['added_date']) && $UserDtls['added_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A', strtotime($UserDtls['added_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified By</label>
                <div class="value">
                    <?php if(!empty($UserDtls['last_modified_by'])) { echo $UserDtls['last_modified_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified Date</label>
                <div class="value">
                    <?php if(!empty($UserDtls['last_modified_date']) && $UserDtls['last_modified_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A',strtotime($UserDtls['last_modified_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Login Date/Time</label>
                <div class="value">
                    <?php if(!empty($UserDtls['last_login_time']) && $UserDtls['last_login_time'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A',strtotime($UserDtls['last_login_time'])); } else {  echo "-"; } ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>