<?php
require_once 'inc_classes.php';
require_once '../classes/adminClass.php';
$adminClass=new adminClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";

if($_REQUEST['action']=='add_services')
{
    // Getting System User Details
    $arr['service_id']=$_REQUEST['service_id'];
    $recListResponse= $adminClass->GetServiceById($arr);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($arr['service_id'])) { echo "Edit"; } else { echo "Add"; } ?> Service </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_services" id="frm_services" method="post" action ="services_ajax_process.php?action=submited_services" autocomplete="off">
            <input type="hidden" name="service_id" id="service_id" value="<?php echo $arr['service_id']; ?>" />
            <div class="scrollbars" >                
                <div class="editform">
                    <label>Service Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="service_title" id="service_title" value="<?php if(!empty($_POST['service_title'])) { echo $_POST['service_title']; } else if(!empty($recListResponse['service_title'])) { echo $recListResponse['service_title']; }  else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50"  style="width:100% !important;" />
                    </div>
                </div>
                <div>
                    <label style="margin-right:25px;">This service accessible for HD ?</label>
                    <div class="radio">
                        <label class="radio-inline" style="margin-right:25px;">
                            <input type="radio" name="is_hd_access" id="is_hd_access_yes" value="Y"<?php if($_POST['is_hd_access']=='Y') { echo 'checked="checked"'; } else if(!empty($recListResponse['is_hd_access']) && $recListResponse['is_hd_access']=='Y') { echo 'checked="checked"'; } ?> /> Yes
                        </label>
                        <label class="radio-inline" style="margin-right:25px;">
                            <input type="radio" name="is_hd_access" id="is_hd_access_no" value="N"<?php if($_POST['is_hd_access']=='N') { echo 'checked="checked"'; } else if(!empty($recListResponse['is_hd_access']) && $recListResponse['is_hd_access']=='N') { echo 'checked="checked"'; } ?> /> No
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return submit_services();" />
                </div>  
            </div>
        </form>
    </div>
 <?php   
} 

else if($_REQUEST['action']=='submited_services')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $service_id=$_POST['service_id'];
        $service_title=strip_tags($_POST['service_title']);
        $is_hd_access=strip_tags($_POST['is_hd_access']);
        if($service_title=='')
        {
            $success=0;
            $errors[$i++]="Please enter service title";
        }
        if($is_hd_access=='')
        {
            $success=0;
            $errors[$i++]="Please select service access"; 
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        // Check Record Exists 
        if($service_id)
            $chk_exist="SELECT service_id FROM sp_services WHERE service_title='".$service_title."' AND service_id !='".$service_id."'";
        else 
            $chk_exist="SELECT service_id FROM sp_services WHERE service_title='".$service_title."'";
        
        if(mysql_num_rows($db->query($chk_exist)))
        {
            $success=0;
            echo 'RecordExist'; // Validation error/record exists
            exit;
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $arr['added_by']=$_SESSION['admin_user_id'];
            $arr['service_id']=$service_id;
            $arr['service_title']=  ucfirst(strtolower($service_title));
            $arr['is_hd_access']=$is_hd_access;
            $arr['last_modified_by']=$_SESSION['admin_user_id'];
            
            $InsertRecord=$adminClass->InsertServices($arr); 
            if($InsertRecord == 'Updated')
            {
                echo 'UpdateSuccess'; // Update Record
                exit;
            }
            else if($InsertRecord == 'Inserted')
            {
                echo 'InsertSuccess'; // Insert Record
                exit;
            }
            else 
            {
               echo 'RecordExist';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['service_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['service_id'] =$_REQUEST['service_id'];
        $arr['curr_status']=$_REQUEST['curr_status'];
        $arr['istrashDelete']=$_REQUEST['trashDelete'];
        $arr['actionval'] = $_REQUEST['actionval'];
        
        $ChangeStatus =$adminClass->ChangeStatus($arr);
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
                    <?php if(!empty($UserDtls['name'])) { echo $UserDtls['name']; } else {  echo "-"; } ?>
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
else if($_REQUEST['action'] == 'add_SubService')
{
    $arr['service_id']=$_REQUEST['service_id'];
    $arr['sub_service_id']=$_REQUEST['subservice_id'];
    $recListResponse1= $adminClass->GetSubServiceById($arr);
    $recListResponse= $adminClass->GetServiceById($arr);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($arr['sub_service_id'])) { echo "Edit"; } else { echo "Add"; } ?> Sub Service </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_sub_services" id="frm_sub_services" method="post" action ="services_ajax_process.php?action=inserted_sub_services" autocomplete="off">
            <input type="hidden" name="service_id" id="service_id" value="<?php echo $arr['service_id']; ?>" />
            <input type="hidden" name="sub_service_id" id="sub_service_id" value="<?php echo $arr['sub_service_id']; ?>" />
            <div class="scrollbars" >                
                <div class="editform" style="width:500px">
                    <label>Service Name</label>
                    <div class="value">
                        <input readonly="readonly" type="text" name="service_title" id="service_title" value="<?php echo $recListResponse['service_title']; ?>" class="validate[required,maxSize[50]] form-control" maxlength="50"  style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform" style="width:500px">
                    <label>Recommended Service</label>
                    <div class="value">
                        <input type="text" name="recommomded_service" id="recommomded_service" value="<?php if(!empty($_POST['recommomded_service'])) { echo $_POST['recommomded_service']; } else if(!empty($recListResponse1['recommomded_service'])) { echo $recListResponse1['recommomded_service']; }  else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50"  style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform" style="width:500px">
                    <label>Cost</label>
                    <div class="value">
                        <input type="text" name="cost" id="cost" value="<?php if(!empty($_POST['cost'])) { echo $_POST['cost']; } else if(!empty($recListResponse1['cost'])) { echo $recListResponse1['cost']; }  else { echo ""; } ?>" class="validate[required] form-control" maxlength="7" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform" style="width:500px">
                    <label>Tax</label>
                    <div class="value">
                        <input type="text" name="tax" id="tax" value="<?php if(!empty($_POST['tax'])) { echo $_POST['tax']; } else if(!empty($recListResponse1['tax'])) { echo $recListResponse1['tax']; }  else { echo ""; } ?>" class="form-control" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')" maxlength="7"  style="width:100% !important;" />
                    </div>
                </div>
                
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return submit_sub_services();" />
                </div>  
            </div>
        </form>
    </div>
 <?php  
}
else if($_REQUEST['action']=='inserted_sub_services')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $service_id=$_POST['service_id'];
        $sub_service_id=$_POST['sub_service_id'];
        $cost=strip_tags($_POST['cost']);
        $recommomded_service=strip_tags($_POST['recommomded_service']);
        $tax=strip_tags($_POST['tax']);
        if($recommomded_service=='')
        {
            $success=0;
            $errors[$i++]="Please enter recommomded service name.";
        }
        if($cost=='')
        {
            $success=0;
            $errors[$i++]="Please enter recommomded service name.";
        }
        
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        // Check Record Exists 
        if($sub_service_id)
            $chk_exist="SELECT sub_service_id FROM sp_sub_services WHERE recommomded_service='".$recommomded_service."' and service_id = '".$service_id."' AND sub_service_id !='".$sub_service_id."'";
        else 
            $chk_exist="SELECT sub_service_id FROM sp_sub_services WHERE recommomded_service='".$recommomded_service."' and service_id = '".$service_id."' ";     
        //echo $chk_exist;
        if(mysql_num_rows($db->query($chk_exist)))
        {
            $success=0;
            echo 'RecordExist';
            exit;
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $arr['added_by']=$_SESSION['admin_user_id'];
            $arr['service_id']=$service_id;
            $arr['sub_service_id']=$sub_service_id;
            $arr['recommomded_service']=ucwords(strtolower($recommomded_service));
            $arr['cost']=$cost;
            $arr['tax']=$tax;
            $arr['last_modified_by']=$_SESSION['admin_user_id'];
            $InsertRecord=$adminClass->InsertSubServices($arr); 
            if($InsertRecord == 'Updated')
            {
                echo 'UpdateSuccess'; // Update Record
                exit;
            }
            else if($InsertRecord == 'Inserted')
            {
                echo 'InsertSuccess'; // Insert Record
                exit;
            }
            else 
            {
               echo 'RecordExist';
               exit;
            }
        } 
    }    
}
else if($_REQUEST['action']=='ChangeSubService_statuis')
    {        
        if(!empty($_REQUEST['sub_service_id']) && !empty($_REQUEST['actionval']))
        {
            $arr['sub_service_id'] =$_REQUEST['sub_service_id'];
            $arr['istrashDelete']=$_REQUEST['trashDelete'];
            $arr['actionval'] = $_REQUEST['actionval'];

            $ChangeStatus =$adminClass->ChangeStatusSubService($arr);            
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
?>