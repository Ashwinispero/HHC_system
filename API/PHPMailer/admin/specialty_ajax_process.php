<?php
require_once 'inc_classes.php';
require_once '../classes/specialtyClass.php';
$specialtyClass=new specialtyClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='vw_add_specialty')
{
    // Getting System User Details
    $arr['specialty_id']=$_REQUEST['specialty_id'];
    $SpecialtyDtls=$specialtyClass->GetSpecialtyById($arr);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($SpecialtyDtls)) { echo "Edit"; } else { echo "Add"; } ?> Specialty </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_specialty" id="frm_add_specialty" method="post" action ="specialty_ajax_process.php?action=add_specialty" autocomplete="off">
            <div>
                <div class="editform">
                    <label>Name</label>
                    <div class="value">
                        <input type="hidden" name="specialty_id" id="specialty_id" value="<?php echo $arr['specialty_id']; ?>" />
                        <input type="text" name="abbreviation" id="abbreviation" value="<?php if(!empty($_POST['abbreviation'])) { echo $_POST['abbreviation']; } else if(!empty($SpecialtyDtls['abbreviation'])) { echo $SpecialtyDtls['abbreviation']; } else { echo ""; } ?>" class="validate[required,maxSize[70]] form-control" onkeyup="if (/[^A-Za-z0-9-+() ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9-+() ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_specialty_submit();" />
                </div>  
            </div>
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='add_specialty')
{
    $success=0;
    $errors=array(); 
    $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $specialty_id=strip_tags($_POST['specialty_id']);
        $abbreviation=strip_tags($_POST['abbreviation']);
        if($abbreviation=='')
        {
            $success=0;
            $errors[$i++]="Please enter name";
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        // Check Record Exists 
        if($specialty_id)
            $chk_specialty_sql="SELECT specialty_id FROM sp_specialty WHERE abbreviation='".$abbreviation."' AND specialty_id !='".$specialty_id."'";
        else 
            $chk_specialty_sql="SELECT specialty_id FROM sp_specialty WHERE abbreviation='".$abbreviation."'"; 
        
        if(mysql_num_rows($db->query($chk_specialty_sql)))
        {
            $success=0;
            echo 'specialtyexists'; // Validation error/record exists
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
            $arr['specialty_id']=$specialty_id;
            $arr['abbreviation']=  ucfirst(strtolower($abbreviation));
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($specialty_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$specialtyClass->AddSpecialty($arr); 
            if(!empty($InsertRecord))
            {
                if($specialty_id)
                    echo 'UpdateSuccess'; // Update Record
                else 
                    echo 'InsertSuccess'; // Insert Record
            }
            else 
               echo 'specialtyexists';
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['specialty_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['specialty_id'] =$_REQUEST['specialty_id'];
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

        $ChangeStatus =$specialtyClass->ChangeStatus($arr);
        if(!empty($ChangeStatus))
        {
            echo 'success';
        }
        else
        {
            echo 'error';
        }
    } 
}
?>