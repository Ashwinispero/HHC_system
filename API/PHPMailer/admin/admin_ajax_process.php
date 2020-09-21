<?php   require_once 'inc_classes.php';
        require_once '../classes/adminClass.php';
        $adminClass = new adminClass();
        require_once "../classes/thumbnail_images.class.php";
        require_once "../classes/SimpleImage.php";
?>

<?php
    if($_REQUEST['action']=='DoLogin')
    {
        $UserName = $_REQUEST['email_id'];
        $Password = md5($_REQUEST['password']);
        $loggedstatus = $_REQUEST['admin_remember'];
        $checkExist = "SELECT admin_user_id,email_id,password,type,status FROM sp_admin_users WHERE email_id ='".$UserName."' AND password ='".$Password."'";      
        $Loginresult = $db->query($checkExist);       
        if(mysql_num_rows($db->query($checkExist)))
        {
            $adminLog = $db->fetch_array($Loginresult);
            if($adminLog['status']=='1')
            {
                $_SESSION['admin_user_id'] = $adminLog['admin_user_id'];
                $_SESSION['admin_user_type'] = $adminLog['type']; 
                if(isset($_COOKIE['Adminname']))
                {
                  $adminLog['email_id'] = $_COOKIE['Adminname'];
                }                     
                if($loggedstatus)
                {
                   $_SESSION['admin_remember']='1';
                   setcookie("Adminname", $adminLog['email_id'], time()+60*60*24*100, "/");
                   setcookie("cookadmin_remember", $_SESSION['org_remember'], time()+60*60*24*100, "/");
                }
                else 
                {
                   $_SESSION['admin_remember']='0';
                   setcookie("Adminname", "", time()-60*60*24*100, "/");
                   setcookie("cookadmin_remember", "", time()-60*60*24*100, "/");
                }
                echo "success";
                exit;
            }
            else 
            {
                if($adminLog['status']=='2')
                {
                    echo "inactive";
                    exit;
                }
                else if($adminLog['status']=='3')
                {
                   echo "deleted";
                   exit; 
                }
                else 
                {
                   echo "notexists";
                   exit; 
                }
            }
        }
        else
        {
            echo "incorrect";
            exit;
        }
    }
    else if($_REQUEST['action']=='ModifyAdmin')
    {
        $adm_id=$_REQUEST['admin_id'];
        // Getting Admin details
        $modifyadmin=$adminClass->selectAdmin($adm_id);
        ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Edit Profile Details </h4>
            </div>
            <div class="modal-body">
                <div id="error" style="color:red;display:none;">  </div>
                <form class="form-inline" name="modify_admin" id="frm_modify_admin" method="post" enctype="multipart/form-data" action ="admin_ajax_process.php?action=SubmitAdminForm" autocomplete="off">
                    <div class="editform">
                        <label>Last Name <span class="required">*</span></label>
                            <div class="value">
                                <input type="hidden" name="admin_id" id="admin_id" value="<?php if(!empty($modifyadmin['admin_user_id'])) { echo $modifyadmin['admin_user_id']; } ?>" />
                                <input type="hidden" name="pre_email_id" id="pre_email_id" value="<?php if(!empty($modifyadmin['email_id'])) { echo $modifyadmin['email_id']; }  ?>"  />
                                <input type="text" name="name" id="name" value="<?php if(!empty($modifyadmin['name'])) { echo $modifyadmin['name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" maxlength="50" />
                            </div>
                    </div>
                    <div class="editform">
                        <label>First Name <span class="required">*</span></label>
                            <div class="value">
                                <input type="text" name="first_name" id="first_name" value="<?php if(!empty($modifyadmin['first_name'])) { echo $modifyadmin['first_name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" maxlength="50" />
                            </div>
                    </div>
                    <div class="editform">
                        <label>Middle Name</label>
                            <div class="value">
                                <input type="text" name="middle_name" id="middle_name" value="<?php if(!empty($modifyadmin['middle_name'])) { echo $modifyadmin['middle_name']; } else { echo ""; } ?>" class="validate[maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" maxlength="50" />
                            </div>
                    </div>
                    <div class="editform">
                        <label>Email Id</label>
                        <div class="value">
                            <input type="text" name="email_id" id="email_id" value="<?php if(!empty($modifyadmin['email_id'])) { echo $modifyadmin['email_id']; }  else { echo ""; } ?>" class="validate[required,custom[email],maxSize[50]] form-control" maxlength="50" />
                        </div>
                    </div>
                    <div class="editform">
                        <label>Mobile Number <span class="required">*</span></label>
                        <div class="value">
                         <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($modifyadmin['mobile_no'])) { echo $modifyadmin['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control"  onkeyup="if (/[^0-9()-.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()-.]/g,'')" maxlength="15" />
                        </div>
                    </div>
                    <div class="editform">
                        <label>Landline Number</label>
                        <div class="value">
                         <input type="text" name="landline_no" id="landline_no" value="<?php if(!empty($modifyadmin['landline_no'])) { echo $modifyadmin['landline_no']; }  else { echo ""; } ?>" class="validate[minSize[10],maxSize[15],custom[fax]] form-control" onkeyup="if (/[^0-9()-.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()-.]/g,'')" maxlength="15" />
                        </div>
                    </div>
                    <div class="editform">
                        <label>Contact Email Id <span class="required">*</span></label>
                        <div class="value">
                            <input type="text" name="alternate_email_id" id="alternate_email_id" value="<?php if(!empty($modifyadmin['alternate_email_id'])) { echo $modifyadmin['alternate_email_id']; }  else { echo ""; } ?>" class="validate[custom[email],maxSize[50]] form-control" maxlength="50" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" name="btn_submit" id="btn_submit" class="btn btn-download" value="Save Changes" onclick="return SubmitModifyAdmin();" />
                    </div>
            </form>
            </div>
        <?php
    }
    else if($_REQUEST['action']=='SubmitAdminForm')
    {
        $success=0;
        $errors=array(); 
        $i=0; 
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
        {
            $admin_id=strip_tags($_POST['admin_id']);
            $pre_email_id=strip_tags($_POST['pre_email_id']);
            $name=strip_tags($_POST['name']);
            $first_name=strip_tags($_POST['first_name']);
            $middle_name=strip_tags($_POST['middle_name']);
            $email_id=strip_tags($_POST['email_id']);
            $mobile_no=strip_tags($_POST['mobile_no']);
            $landline_no=strip_tags($_POST['landline_no']);
            $alternate_email=strip_tags($_POST['alternate_email_id']);
            if($name == '')
            {
                $success=0;
                $errors[$i++]="Please enter name";
            }
            if($email_id == '')
            {
                $success=0;
                $errors[$i++]="Please enter email address";
            }
            $chk_admin_exists = "SELECT admin_user_id from sp_admin_users where email_id='".$email_id."' AND admin_user_id !='".$admin_id."'";
            if(mysql_num_rows($db->query($chk_admin_exists)))	
            {
                $success=0;
                $errors[$i++]="This admin email address is already in use, please choose another one";
            }
            if($email_id==$alternate_email)
            {
                $success=0;
                echo "SameEmails";
                exit;
                $errors[$i++]="Alernate email address is same as email address please choose another one";
            }
            
            if(count($errors))
            {
                echo 'ValidationError';
                exit;
            }
            else 
            { 
               $success=1;
               $arr['admin_id']=$admin_id;
               $arr['name']=$name;
               $arr['first_name']=$first_name;
               $arr['middle_name']=$middle_name;
               $arr['email_id']=$email_id;
               $arr['mobile_no']=$mobile_no;
               $arr['landline_no']=$landline_no;
               $arr['alternate_email_id']=$alternate_email;
               $UpdateRecord = $adminClass->Modifyadmin($arr);
                if($UpdateRecord)
                {
                  if(!empty($arr['name'])) { echo $arr['name']." "; } if(!empty($arr['first_name'])) { echo $arr['first_name']." "; } if(!empty($arr['middle_name'])) { echo $arr['middle_name']." "; }
                  echo 'AdminProfileContent';
                  include('profile_content.php');
                  exit;
                }
                else
                {
                    echo 'emailExists';
                    exit;
                }
            }
        }
    }
    else if($_REQUEST['action']=='change_admin_password')
    {
       $adm_id=$_REQUEST['admin_id']; 
     ?>
        <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
             <h4 class="modal-title">Change Password</h4>
        </div>
        <div class="modal-body">
           <div>
               <div id="error" style="color:red;display:none;">
               </div>
               <form class="form-inline" name="change_admin_password" id="frm_change_admin_password" method="post"  action ="admin_ajax_process.php?action=SubmitChangePasswordForm" autocomplete="off">
                 <div class="editform">
                     <label>Current Password <span class="required">*</span></label>
                     <div class="value">
                         <input type="hidden" name="admin_id" id="admin_id" value="<?php if(!empty($modifyadmin['admin_user_id'])) { echo $modifyadmin['admin_user_id']; } ?>" />
                         <input type="password" name="admin_old_password" id="admin_old_password" value="<?php if(!empty($_POST['admin_old_password'])) { echo $_POST['admin_old_password']; } else { echo ""; } ?>" class="validate[required,maxSize[15]] form-control" maxlength="15" />
                     </div>
                </div>
                <div class="editform">
                     <label>New Password <span class="required">*</span></label>
                     <div class="value">
                         <input type="password" name="admin_new_password" id="admin_new_password" value="<?php if(!empty($_POST['admin_new_password'])) { echo $_POST['admin_new_password']; } else { echo ""; } ?>" class="validate[required,custom[passwordVal],minSize[8],maxSize[15]] form-control" maxlength="15" />
                         <div id="pwd_strength">
                         </div>
                     </div>
                </div>
                <div class="editform">
                     <label>Confirm Password <span class="required">*</span></label>
                     <div class="value">
                         <input type="password" name="admin_confirm_password" id="admin_confirm_password" value="<?php if(!empty($_POST['admin_confirm_password'])) { echo $_POST['admin_confirm_password']; } else { echo ""; } ?>" class="validate[required,custom[passwordVal],minSize[8],equals[admin_new_password],maxSize[15]] form-control" maxlength="15" />
                     </div>
                </div>
                 <div class="modal-footer">
                     <input type="button" name="btn_change_password" id="btn_change_password" class="btn btn-download" value="Save Changes" onclick="return change_admin_password_Submit();">
                 </div>
             </form>
             </div>
       </div>
     <?php   
    }
    else if($_REQUEST['action']=='SubmitChangePasswordForm')
    {
        $success=0;
        $errors=array(); 
        $i=0;
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
        {
            $admin_old_password = strip_tags($_POST['admin_old_password']);
            $admin_new_password = strip_tags($_POST['admin_new_password']);
            $admin_confirm_password = strip_tags($_POST['admin_confirm_password']);
            if($admin_old_password == '')
            {
                $success=0;
                $errors[$i++]="Please enter old password";
            }
            if($admin_new_password == '')
            {
               $success=0;
               $errors[$i++]="Please enter new password";
            }
            if($admin_confirm_password == '')
            {
               $success=0;
               $errors[$i++]="Please enter confirm password";
            }
            if(count($errors))
            {?>
                <div class="clearfix"></div>
                <div style="margin:0 auto; float:none; margin-top:20px; "></div>  
                    <div class="col-lg-12 registrationsteps" style="padding-left: 10px;">
                    <strong>Please correct the following errors</strong>
                            <ul>
                            <?php
                            for($k=0;$k<count($errors);$k++)
                                    echo '<li style="text-align:left;padding-top:5px;">'.$errors[$k].'</li>';?>
                            </ul>
                    </div>                      
              <?php
            }
            else
            {
                $success = 1;
                $arr['admin_id'] = $_SESSION['admin_user_id'];
                $arr['admin_old_password'] = $admin_old_password;
                $arr['admin_new_password'] = $admin_new_password;
                $ChangePassword = $adminClass->changeAdminPassword($arr);
                if($ChangePassword == 'SamePassword')
                {
                    echo "SamePassword";
                    exit;
                }
                else if($ChangePassword == 'invalidPassord')
                {
                    echo "invalidPassword"; 
                    exit;
                }
                else
                {
                    echo "success";
                    exit;
                }
  
            } 
        }
    }
    else if($_REQUEST['action']=='logout')
    {
        session_destroy();
        ?>
            <script language="javascript" type="text/javascript">
                window.location ="index.php";
            </script>
        <?php   
    }
?>