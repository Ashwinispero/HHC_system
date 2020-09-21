<?php
require_once 'inc_classes.php';
require_once '../classes/medicineClass.php';
$medicineClass=new medicineClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='vw_add_medicine')
{
    // Getting System User Details
    $arr['medicine_id']=$_REQUEST['medicine_id'];
    $MedicineDtls=$medicineClass->GetMedicineById($arr);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($MedicineDtls)) { echo "Edit"; } else { echo "Add"; } ?> Medicine </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_medicine" id="frm_add_medicine" method="post" action ="medicine_ajax_process.php?action=add_medicine" autocomplete="off">
            <div>
                <div class="editform">
                    <label>Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="medicine_id" id="medicine_id" value="<?php echo $arr['medicine_id']; ?>" />
                        <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])) { echo $_POST['name']; } else if(!empty($MedicineDtls['name'])) { echo $MedicineDtls['name']; } else { echo ""; } ?>" class="validate[required,maxSize[70]] form-control" onkeyup="if (/[^A-Za-z0-9-+() ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9-+() ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Select Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="type" id="type" class="validate[required]">
                                    <option value=""<?php if($_POST['type']=='') { echo 'selected="selected"'; } else if($MedicineDtls['type']=='') { echo 'selected="selected"'; } ?>>Type</option>
                                    <option value="1"<?php if($_POST['type']=='1') { echo 'selected="selected"'; } else if($MedicineDtls['type']=='1') { echo 'selected="selected"'; }?>>Unit</option>
                                    <option value="2"<?php if($_POST['type']=='2') { echo 'selected="selected"'; } else if($MedicineDtls['type']=='2') { echo 'selected="selected"'; }?>>Non Unit</option>
                                </select>
                            </label>
                        </div>
                </div>
                <div class="editform">
                    <label>Manufacture Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="manufacture_name" id="manufacture_name" value="<?php if(!empty($_POST['manufacture_name'])) { echo $_POST['manufacture_name']; } else if(!empty($MedicineDtls['manufacture_name'])) { echo $MedicineDtls['manufacture_name']; } else { echo ""; } ?>" class="validate[required,maxSize[70]] form-control" onkeyup="if (/[^A-Za-z0-9-+() ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9-+() ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Rate</label>
                    <div class="value">
                        <input type="text" name="rate" id="rate" value="<?php if(!empty($_POST['rate'])) { echo $_POST['rate']; } else if(!empty($MedicineDtls['rate'])) { echo $MedicineDtls['rate']; } else { echo ""; } ?>" class="form-control" onkeyup="if (/[^0-9.]/g.test(this.value)) this.value = this.value.replace(/[^0-9.]/g,'')"  maxlength="7" style="width:100% !important;" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_medicine_submit();" />
                </div>  
            </div>
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='add_medicine')
{
    $success=0;
    $errors=array(); 
    $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $medicine_id=strip_tags($_POST['medicine_id']);
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
         * 
         */
        
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
        }
        
        // Check Record Exists 
        if($medicine_id)
            $chk_medicine_sql="SELECT medicine_id FROM sp_medicines WHERE name='".$name."' AND medicine_id !='".$medicine_id."'";
        else 
            $chk_medicine_sql="SELECT medicine_id FROM sp_medicines WHERE name='".$name."'"; 
        
        if(mysql_num_rows($db->query($chk_medicine_sql)))
        {
            $success=0;
            echo 'medicineexists'; // Validation error/record exists
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
            $arr['medicine_id']=$medicine_id;
            $arr['name']=  ucfirst(strtolower($name));
            $arr['type']=$type;
            $arr['manufacture_name']=ucfirst(strtolower($manufacture_name));
            $arr['rate']=$rate;
            $arr['modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($medicine_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$medicineClass->AddMedicine($arr); 
            if(!empty($InsertRecord))
            {
                if($medicine_id)
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
               echo 'medicineexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['medicine_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['medicine_id'] =$_REQUEST['medicine_id'];
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

        $ChangeStatus =$medicineClass->ChangeStatus($arr);
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