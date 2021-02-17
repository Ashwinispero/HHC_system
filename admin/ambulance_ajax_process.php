<?php
require_once 'inc_classes.php';
require_once '../classes/professionalsClass.php';
require_once '../classes/commonClass.php';
$professionalsClass=new professionalsClass();
$commonClass=new commonClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
require_once "../classes/functions.php";


?>



<?php
if(!class_exists('AbstractDB'))
        require_once '../classes/AbstractDB.php';
if($_REQUEST['action']=='vw_add_ambulance')
{
    
    // Getting Professional Details
    $arr = array();
    $arr['id'] = $_REQUEST['id'];
    $ProfDtls = $professionalsClass->GetProfessionalById($arr);

    if (!empty($ProfDtls)) {
        // Get Professional service details
        $profServiceList = $professionalsClass->GetProfessionalServices($arr);
        
        // Get Professional sub service details
        $arr['serviceType'] = 'subService';
        $arr['service_id'] =  $profServiceList['service_id'];
        $profSubServiceList = $professionalsClass->GetProfessionalServices($arr);
    }
    // Getting Professional other details
    $ProfOtherDtls=$professionalsClass->GetProfessionalOtherDtlsById($arr);
    // Get All Location
    $LocationList=$commonClass->GetAllLocations();
    // Get All Services 
    $ServiceList=$commonClass->GetAllServices();
    $param['service_id'] =  $profServiceList['service_id'];
    $subServiceList = (!empty($ProfDtls) ? $commonClass->getAllSubServices($param) : $commonClass->getAllSubServices());
    
    unset($arr['serviceType'], $arr['service_id'], $param);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($ProfDtls)) { echo "Edit"; } else { echo "Add"; } ?> Ambulance </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_ambulance" id="frm_add_ambulance" method="post" action ="ambulance_ajax_process.php?action=add_ambulance" autocomplete="off">
            <div class="scrollbars">
                
                <div class="editform">
                    <label>Ambulance No<span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="service_professional_id" id="service_professional_id" value="<?php echo $arr['service_professional_id']; ?>" />
                        <input type="text" name="amb_no" id="amb_no" value="<?php if(!empty($_POST['amb_no'])) { echo $_POST['amb_no']; } else if(!empty($ProfDtls['amb_no'])) { echo $ProfDtls['amb_no']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Mobile <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($ProfDtls['mobile_no'])) { echo $ProfDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Ambulance Type</label>
                    <div class="value">
                        <input type="text" name="amb_type" id="amb_type" value="<?php if(!empty($_POST['amb_type'])) { echo $_POST['amb_type']; } else if(!empty($ProfDtls['amb_type'])) { echo $ProfDtls['amb_type']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Ambualnce Status</label>
                    <div class="value">
                        <input type="text" name="amb_status" id="amb_status" value="<?php if(!empty($_POST['amb_status'])) { echo $_POST['amb_status']; } else if(!empty($ProfDtls['amb_status'])) { echo $ProfDtls['amb_status']; }  else { echo ""; } ?>" class="validate[minSize[10],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Base Location <span class="required">*</span></label>
                    <div class="value">
                    <input maxlength="100" placeholder="Enter patient address" id="base_location1" name="base_location1" type="text"  class="validate[required] form-control"  />   
                    </div>
                </div>
                <div class="editform">
                    <label>Dtail Address</label>
                    <div class="value">
                        <textarea name="address" id="address" class="form-control" maxlength="160" style="width: 265px; height: 100px;"><?php if(!empty($_POST['address'])) { echo $_POST['address']; } else if(!empty($ProfDtls['address'])) { echo $ProfDtls['address']; }  else { echo ""; } ?></textarea>
                    </div>
                </div>  
              </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_ambulance_submit();" />
                </div>  
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='add_ambulance')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $amb_no=strip_tags($_POST['amb_no']);
        $mobile_no=strip_tags($_POST['mobile_no']);
        $amb_type=strip_tags($_POST['amb_type']);
        $amb_status=strip_tags($_POST['amb_status']);
        $base_location=strip_tags($_POST['base_location']);
        $address=strip_tags($_POST['address']);

        if($amb_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter Ambualnce No";
        }
        if($mobile_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter Mobile No";
        }
        if($amb_status=='')
        {
            $success=0;
            $errors[$i++]="Please select ambulance status";
        }
        if($amb_type=='')
        {
            $success=0;
            $errors[$i++]="Please ambulance type ";
        }
        if($base_location=='')
        {
            $success=0;
            $errors[$i++]="Please enter Ambualnce No";
        }
        if($address=='')
        {
            $success=0;
            $errors[$i++]="Please enter address";
        }
        // Check Record Exists 
        $chk_professional_sql="SELECT service_professional_id FROM sp_service_professionals WHERE mobile_no='".$mobile_no."'"; 
        
        if(mysql_num_rows($db->query($chk_professional_sql)))
        {
            $success=0;
            echo 'Ambulanceexists';
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
            $arr['amb_no']=$amb_no;
            $arr['mobile_no']=$mobile_no;
            $arr['amb_status']=$amb_status;
            $arr['base_location']=$base_location;
            $arr['address']=$address;
            $arr['lng']='';
            $arr['long']='';
            $arr['status']='1';
            $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['added_date']=date('Y-m-d H:i:s');
            $InsertRecord = $professionalsClass->AddProfessional($arr); 
            if($InsertRecord)
                {
                    echo 'Insert Success'; // Update Record
                    exit;
                }
                else 
                {
                    echo 'Not Insert'; // Insert Record
                    exit;
                }
        }
    }
}

    
?>
