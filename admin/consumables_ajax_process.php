<?php
require_once 'inc_classes.php';
require_once '../classes/consumablesClass.php';
$consumablesClass=new consumablesClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='add_consumable_form')
{
    // Getting System User Details
    $arr['consumable_id']=$_REQUEST['consumable_id'];
    $RecordDtls=$consumablesClass->GetConsumablesById($arr);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($RecordDtls)) { echo "Edit"; } else { echo "Add"; } ?> Consumables </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_record" id="frm_add_record" method="post" action ="consumables_ajax_process.php?action=submit_consumables_form" autocomplete="off">
            <div>
                <div class="editform">
                    <label>Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="consumable_id" id="consumable_id" value="<?php echo $arr['consumable_id']; ?>" />
                        <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])) { echo $_POST['name']; } else if(!empty($RecordDtls['name'])) { echo $RecordDtls['name']; } else { echo ""; } ?>" class="validate[required,maxSize[70]] form-control" onkeyup="if (/[^A-Za-z0-9-+() ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9-+() ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Select Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="type" id="type" class="validate[required]">
                                    <option value=""<?php if($_POST['type']=='') { echo 'selected="selected"'; } else if($RecordDtls['type']=='') { echo 'selected="selected"'; } ?>>Type</option>
                                    <option value="1"<?php if($_POST['type']=='1') { echo 'selected="selected"'; } else if($RecordDtls['type']=='1') { echo 'selected="selected"'; }?>>Unit</option>
                                    <option value="2"<?php if($_POST['type']=='2') { echo 'selected="selected"'; } else if($RecordDtls['type']=='2') { echo 'selected="selected"'; }?>>Non Unit</option>
                                </select>
                            </label>
                        </div>
                </div>
                <div class="editform">
                    <label>Manufacture Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="manufacture_name" id="manufacture_name" value="<?php if(!empty($_POST['manufacture_name'])) { echo $_POST['manufacture_name']; } else if(!empty($RecordDtls['manufacture_name'])) { echo $RecordDtls['manufacture_name']; } else { echo ""; } ?>" class="validate[required,maxSize[70]] form-control" onkeyup="if (/[^A-Za-z0-9-+() ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9-+() ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Rate </label>
                    <div class="value">
                        <input type="text" name="rate" id="rate" value="<?php if(!empty($_POST['rate'])) { echo $_POST['rate']; } else if(!empty($RecordDtls['rate'])) { echo $RecordDtls['rate']; } else { echo ""; } ?>" class="form-control" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"  maxlength="7" style="width:100% !important;" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_consumables_submit();" />
                </div>  
            </div>
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='submit_consumables_form')
{
    $success=0;
    $errors=array(); 
    $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $consumable_id=strip_tags($_POST['consumable_id']);
        $name=strip_tags($_POST['name']);
        $type=strip_tags($_POST['type']);
        $manufacture_name=strip_tags($_POST['manufacture_name']);
        $rate=strip_tags($_POST['rate']);
        if($name=='')
        {
            $success=0;
            $errors[$i++]="Please enter name";
        }
        if($type=='')
        {
            $success=0;
            $errors[$i++]="Please select type";
        }
        if($manufacture_name=='')
        {
            $success=0;
            $errors[$i++]="Please enter manufacture name";
        }
        /*
        if($rate=='')
        {
            $success=0;
            $errors[$i++]="Please enter rate";
        }
         */
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        
        // Check Record Exists 
        if($consumable_id)
            $chk_exist="SELECT consumable_id FROM sp_consumables WHERE name='".$name."' AND consumable_id !='".$consumable_id."'";
        else 
            $chk_exist="SELECT consumable_id FROM sp_consumables WHERE name='".$name."'"; 
        if(mysql_num_rows($db->query($chk_exist)))
        {
            $success=0;
            echo 'Recordexists';
            exit;
        }
     
        if(count($errors))
        {
           echo 'Recordexists'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $arr['consumable_id']=$consumable_id;
            $arr['name']=ucwords($name);
            $arr['type']=$type;
            $arr['manufacture_name']=ucfirst(strtolower($manufacture_name));
            $arr['rate']=$rate;
            $arr['last_modified_by'] = $_SESSION['admin_user_id'];
            $arr['added_by'] = $_SESSION['admin_user_id'];   
            
            $InsertRecord=$consumablesClass->AddConsumables($arr); 
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
    if(!empty($_REQUEST['consumable_id']) && !empty($_REQUEST['actionval']))
    {        
        $arr['consumable_id'] =$_REQUEST['consumable_id'];
        $arr['curr_status']=$_REQUEST['curr_status'];
        $arr['actionval'] = $_REQUEST['actionval'];
        
        $ChangeStatus =$consumablesClass->ChangeStatus($arr);
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