<?php
require_once 'inc_classes.php';
require_once '../classes/locationsClass.php';
$locationsClass=new locationsClass();
if($_REQUEST['action']=='vw_add_location')
{
    // Getting Location Details
    $arr['location_id']=$_REQUEST['location_id'];
    $LocationDtls=$locationsClass->GetLocationById($arr);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($LocationDtls)) { echo "Edit"; } else { echo "Add"; } ?> Location </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_location" id="frm_add_location" method="post" action ="location_ajax_process.php?action=add_location" autocomplete="off">
            <div>
                <div class="editform">
                    <label>Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="location_id" id="location_id" value="<?php echo $arr['location_id']; ?>" />
                        <input type="text" name="location" id="location" value="<?php if(!empty($_POST['location'])) { echo $_POST['location']; } else if(!empty($LocationDtls['location'])) { echo $LocationDtls['location']; } else { echo ""; } ?>" class="validate[required,maxSize[35]] form-control" onkeyup="if (/[^A-Za-z./() ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z./() ]/g,'')"  maxlength="35" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>PIN Code </label>
                    <div class="value">
                        <input type="text" name="pin_code" id="pin_code" value="<?php if(!empty($_POST['pin_code'])) { echo $_POST['pin_code']; } else if(!empty($LocationDtls['pin_code'])) { echo $LocationDtls['pin_code']; } else { echo ""; } ?>" class="validate[minSize[6],maxSize[6],integer] form-control" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"  maxlength="6" style="width:100% !important;" />
                    </div>
                </div>
             </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_location_submit();" />
                </div>  
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='add_location')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $location_id=strip_tags($_POST['location_id']);
        $location=strip_tags($_POST['location']);
        $pin_code=strip_tags($_POST['pin_code']);
        if($location=='')
        {
            $success=0;
            $errors[$i++]="Please enter location";
        }
        /*if($pin_code=='')
        {
            $success=0;
            $errors[$i++]="Please enter pin code";
        }*/
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        
        // Check Record Exists 
        if($location_id)
            $chk_location_sql="SELECT location_id FROM sp_locations WHERE location='".$location."' AND pin_code='".$pin_code."' AND location_id !='".$location_id."'";
        else 
            $chk_location_sql="SELECT location_id FROM sp_locations WHERE location='".$location."' AND pin_code='".$pin_code."'"; 
        
        if(mysql_num_rows($db->query($chk_location_sql)))
        {
            $success=0;
            echo 'locationexists'; 
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
            $arr['location_id']=$location_id;
            $arr['location']=  ucfirst(strtolower($location));          
            $arr['pin_code']=$pin_code;
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($location_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$locationsClass->AddLocation($arr); 
            if(!empty($InsertRecord))
            {
                if($location_id)
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
               echo 'locationexists';
               exit;
            }
            
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['location_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['location_id'] =$_REQUEST['location_id'];
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

        $ChangeStatus =$locationsClass->ChangeStatus($arr);
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
else if($_REQUEST['action']=='vw_add_sub_location')
{
   // Getting Location Details
    $arr['location_id']=$_REQUEST['location_id'];
    $LocationDtls=$locationsClass->GetLocationById($arr);
    
    $arr['sub_location_id']=$_REQUEST['sub_location_id'];
    $SubLocationDtls=$locationsClass->GetSubLocationById($arr); 
    
    ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title"><?php if(!empty($LocationDtls)) { echo "Edit"; } else { echo "Add"; } ?> Sub Location </h4>
        </div>
        <div class="modal-body">
            <form class="form-inline" name="frm_add_sub_location" id="frm_add_sub_location" method="post" action ="location_ajax_process.php?action=add_sub_location" autocomplete="off">
                <div>
                    <div class="editform">
                        <label>Location Name</label>
                        <div class="value">
                            <input type="hidden" name="location_id" id="location_id" value="<?php echo $arr['location_id']; ?>" />
                            <input type="hidden" name="sub_location_id" id="sub_location_id" value="<?php echo $arr['sub_location_id']; ?>" />
                            <?php if(!empty($LocationDtls['location'])) { echo $LocationDtls['location']; } else { echo "-"; } ?>
                        </div>
                    </div>
                    <div class="editform">
                        <label>Sub Location Name <span class="required">*</span></label>
                        <div class="value">
                            <input type="text" name="location_name" id="location_name" value="<?php if(!empty($_POST['location_name'])) { echo $_POST['location_name']; } else if(!empty($LocationDtls['location'])) { echo $SubLocationDtls['location_name']; } else { echo ""; } ?>" class="validate[required,maxSize[35]] form-control" onkeyup="if (/[^A-Za-z./() ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z./() ]/g,'')"  maxlength="35" style="width:100% !important;" />
                        </div>
                    </div>
                 </div>
                    <div class="modal-footer">
                        <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_sub_location_submit();" />
                    </div>  
            </form>
        </div>  
    <?php 
} 
else if($_REQUEST['action']=='add_sub_location')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $location_id=strip_tags($_POST['location_id']);
        $sub_location_id=strip_tags($_POST['sub_location_id']);
        $location_name=strip_tags($_POST['location_name']);
        if($location_name=='')
        {
            $success=0;
            $errors[$i++]="Please enter sub location name";
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        
        // Check Record Exists 
        if($sub_location_id)
            $chk_sub_location_sql="SELECT sub_location_id FROM sp_sub_locations WHERE location_name='".$location_name."' AND location_id='".$location_id."' AND sub_location_id !='".$sub_location_id."'";
        else 
            $chk_sub_location_sql="SELECT sub_location_id FROM sp_sub_locations WHERE location_name='".$location_name."' AND sub_location_id='".$sub_location_id."'"; 
        
        if(mysql_num_rows($db->query($chk_sub_location_sql)))
        {
            $success=0;
            echo 'sublocationexists'; 
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
            $arr['location_id']=$location_id;
            $arr['sub_location_id']=$sub_location_id;
            $arr['location_name']=ucfirst(strtolower($location_name));
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($sub_location_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$locationsClass->AddSubLocation($arr); 
            if(!empty($InsertRecord))
            {
                if($sub_location_id)
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
               echo 'sublocationexists';
               exit;
            }
            
        } 
    }
}
?>