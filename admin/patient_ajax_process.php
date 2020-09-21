<?php
require_once 'inc_classes.php';
require_once '../classes/patientsClass.php';
require_once '../classes/commonClass.php';
$patientsClass=new patientsClass();
$commonClass=new commonClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='vw_add_patient')
{
    // Getting Patient Details
    $arr['patient_id']=$_REQUEST['patient_id'];
    $PatientDtls=$patientsClass->GetPatientById($arr);
    // Get All Location
    $LocationList=$commonClass->GetAllLocations();
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($PatientDtls)) { echo "Edit"; } else { echo "Add"; } ?> Patient </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_patient" id="frm_add_patient" method="post" action ="patient_ajax_process.php?action=add_patient" autocomplete="off">
            <div class="scrollbars">
                <div class="editform">
                    <label>Last Name</label>
                    <div class="value">
                        <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $arr['patient_id']; ?>" />
                        <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])) { echo $_POST['name']; } else if(!empty($PatientDtls['name'])) { echo $PatientDtls['name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>First Name</label>
                    <div class="value"> 
                        <input type="text" name="first_name" id="first_name" value="<?php if(!empty($_POST['first_name'])) { echo $_POST['first_name']; } else if(!empty($PatientDtls['first_name'])) { echo $PatientDtls['first_name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Middle Name</label>
                    <div class="value">
                        <input type="text" name="middle_name" id="middle_name" value="<?php if(!empty($_POST['middle_name'])) { echo $_POST['middle_name']; } else if(!empty($PatientDtls['middle_name'])) { echo $PatientDtls['middle_name']; } else { echo ""; } ?>" class="validate[maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Email Address</label>
                    <div class="value">
                        <input type="text" name="email_id" id="email_id" value="<?php if(!empty($_POST['email_id'])) { echo $_POST['email_id']; } else if(!empty($PatientDtls['email_id'])) { echo $PatientDtls['email_id']; } else { echo ""; } ?>" class="validate[required,custom[email]] form-control" maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Phone</label>
                    <div class="value">
                        <input type="text" name="phone_no" id="phone_no" value="<?php if(!empty($_POST['phone_no'])) { echo $_POST['phone_no']; } else if(!empty($PatientDtls['phone_no'])) { echo $PatientDtls['phone_no']; }  else { echo ""; } ?>" class="validate[required,minSize[11],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Mobile</label>
                    <div class="value">
                        <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($PatientDtls['mobile_no'])) { echo $PatientDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Birth Date</label>
                    <div class="value">
                        <input type="text" name="dob" id="dob" value="<?php if(!empty($_POST['dob'])) { echo date('Y-m-d',strtotime($_POST['dob'])); } if(!empty($PatientDtls['dob'])) { echo date('Y-m-d',strtotime($PatientDtls['dob'])); }  else { echo ""; } ?>" class="validate[required] form-control datepicker" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Location</label>
                    <div class="value dropdown">
                        <label>
                            <select name="location_id" id="location_id" class="validate[required]">
                                <option value=""<?php if($_POST['location_id']=='') { echo 'selected="selected"'; } else if($PatientDtls['location_id']=='') { echo 'selected="selected"'; } ?>>Select Location</option>
                                <?php
                                    foreach($LocationList as $key=>$valLocation)
                                    {
                                        if($PatientDtls['location_id'] == $valLocation['location_id'])
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
                    <label>Residential Address</label>
                    <div class="value">
                        <textarea name="residential_address" id="residential_address" class="validate[required] form-control" maxlength="160" style="width: 265px; height: 100px;"><?php if(!empty($_POST['address'])) { echo $_POST['residential_address']; } else if(!empty($PatientDtls['residential_address'])) { echo $PatientDtls['residential_address']; }  else { echo ""; } ?></textarea>
                    </div>
                </div>
                <div class="editform">
                    <label>Permanent Address</label>
                    <div class="value">
                        <textarea name="permanant_address" id="permanant_address" class="validate[required] form-control" maxlength="160" style="width: 265px; height: 100px;"><?php if(!empty($_POST['permanant_address'])) { echo $_POST['permanant_address']; } else if(!empty($PatientDtls['permanant_address'])) { echo $PatientDtls['address']; }  else { echo ""; } ?></textarea>
                    </div>
                </div>
              </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_patient_submit();" />
                </div>  
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['patient_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['patient_id'] =$_REQUEST['patient_id'];
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

        $ChangeStatus =$patientsClass->ChangeStatus($arr);
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
else if($_REQUEST['action']=='vw_patient')
{
    // Getting Patient Details
    $arr['patient_id']=$_REQUEST['patient_id'];
    $PatientDtls=$patientsClass->GetPatientById($arr);  
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Patient Details</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="editform">
                <label>HHC Code</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['hhc_code'])) { echo $PatientDtls['hhc_code']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Name</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['name'])) { echo $PatientDtls['name']." "; } if(!empty($PatientDtls['first_name'])) { echo $PatientDtls['first_name']." "; } if(!empty($PatientDtls['middle_name'])) { echo $PatientDtls['middle_name']; } else {  echo ""; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Email Address</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['email_id'])) { echo $PatientDtls['email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Phone Number</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['phone_no'])) { echo $PatientDtls['phone_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Mobile Number</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['mobile_no'])) { echo $PatientDtls['mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Birth Date</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['dob']) && $PatientDtls['dob']!='0000-00-00') { echo date('d M Y',strtotime($PatientDtls['dob'])); } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Residential Address</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['residential_address'])) { echo $PatientDtls['residential_address']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Permanent Address</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['permanant_address'])) { echo $PatientDtls['permanant_address']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Location</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['locationNm'])) { echo $PatientDtls['locationNm']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>PIN Code</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['LocationPinCode'])) { echo $PatientDtls['LocationPinCode']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Status</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['statusVal'])) { echo $PatientDtls['statusVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['added_by'])) { echo $PatientDtls['added_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value"> 
                    <?php if(!empty($PatientDtls['added_date']) && $PatientDtls['added_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A', strtotime($PatientDtls['added_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified By</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['last_modified_by'])) { echo $PatientDtls['last_modified_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified Date</label>
                <div class="value">
                    <?php if(!empty($PatientDtls['last_modified_date']) && $PatientDtls['last_modified_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A',strtotime($PatientDtls['last_modified_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
        </div>
    </div>
<?php
} else if($_REQUEST['action']=='change_vip_status') {
    if(!empty($_REQUEST['patient_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['patient_id'] = $_REQUEST['patient_id'];
        if ($_REQUEST['actionval'] == 'Active')
            $arr['status'] = 'Y';
        if ($_REQUEST['actionval'] == 'Inactive')
           $arr['status'] = 'N';

        $arr['curr_status']   = $_REQUEST['curr_status'];
        $arr['login_user_id'] = $_REQUEST['login_user_id'];
        $arr['istrashDelete'] = $_REQUEST['trashDelete'];

        $ChangeStatus =$patientsClass->ChangeVIPStatus($arr);
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