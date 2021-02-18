<?php
require_once 'inc_classes.php';
require_once '../classes/AmbulanceClass.php';
require_once '../classes/commonClass.php';
$AmbulanceClass=new AmbulanceClass();
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
  /*  
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
    */
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($ProfDtls)) { echo "Edit"; } else { echo "Add"; } ?> Ambulance </h4>
    </div>
    <div class="modal-body">
     <form class="form-horizontal" name="frm_add_ambulance" id="frm_add_ambulance" method="post" action ="ambulance_ajax_process.php?action=add_ambulance" >
            <div class="scrollbars">
                
                <div class="editform">
                    <label>Ambulance No<span class="required">*</span></label>
                    
                    <div class="value">
                        <input type="hidden" name="service_professional_id" id="service_professional_id" value="<?php echo $arr['service_professional_id']; ?>" />
                        <input onblur="return valid(this.value);" type="text" name="amb_no" id="amb_no" value="<?php if(!empty($_POST['amb_no'])) { echo $_POST['amb_no']; } else if(!empty($ProfDtls['amb_no'])) { echo $ProfDtls['amb_no']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                        <span id="errmsg"></span>
                    </div>
                </div>
                <div class="editform">
                    <label>Mobile <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($ProfDtls['mobile_no'])) { echo $ProfDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="10" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Cost per Km <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="Cost_per_km" id="Cost_per_km" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($ProfDtls['mobile_no'])) { echo $ProfDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="3" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Ambulance Type</label>
                    <div class="value">
                    <select class="chosen-select form-control"  placeholder="Select Ambulance Type"  name="amb_type" id="amb_type" >
                        <option value="">Select Ambulance Type</option>
                        <?php
                        $selectRecord = "SELECT * FROM sp_ems_amb_type WHERE status='1' ORDER BY amb_type ASC";
                        $AllRrecord = $db->fetch_all_array($selectRecord);
                        foreach($AllRrecord as $key=>$valRecords)
                        {
                            echo '<option value="'.$valRecords['id'].'">'.$valRecords['amb_type'].'</option>';
                        }
                        ?>
                    </select>
                    </div>
                </div>
                <div class="editform">
                    <label>Ambualnce Status</label>
                    <div class="value">
                    <select class="chosen-select form-control"  placeholder="Select Ambulance status"  name="amb_status" id="amb_status" >
                        <option value="">Select Ambulance status</option>
                        <?php
                        $selectRecord = "SELECT * FROM sp_ems_amb_status WHERE status='1' ORDER BY amb_status ASC";
                        $AllRrecord = $db->fetch_all_array($selectRecord);
                        foreach($AllRrecord as $key=>$valRecords)
                        {
                            echo '<option value="'.$valRecords['id'].'">'.$valRecords['amb_status'].'</option>';
                        }
                        ?>
                    </select>
                    </div>
                </div>
                <div class="editform">
                    <label>Base Location <span class="required">*</span></label>
                    <div class="value">
                    <input maxlength="100" placeholder="Enter patient address" id="base_location1" name="base_location1" type="text"  class="validate[required] form-control"  />   
                    </div>
                </div>
                <div class="editform">
                    <label>Detail Address</label>
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
        $Cost_per_km=strip_tags($_POST['Cost_per_km']);
        $amb_type=strip_tags($_POST['amb_type']);
        $amb_status=strip_tags($_POST['amb_status']);
        $base_location=strip_tags($_POST['base_location1']);
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
            $errors[$i++]="Please enter base location";
        }
        if($address=='')
        {
            $success=0;
            $errors[$i++]="Please enter address";
        }
        if($Cost_per_km='')
        {
            $success=0;
            $errors[$i++]="Please cost per KM";
        }
        // Check Record Exists 
        $chk_professional_sql="SELECT id FROM sp_ems_ambulance WHERE mob_no='".$mobile_no."'"; 
        
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
            $arr['cost_per_km']=$Cost_per_km;
            $arr['amb_status']=$amb_status;
            $arr['amb_type']=$amb_type;
            $arr['base_location']=$base_location;
            $arr['address']=$address;
            $arr['lat']='';
            $arr['long']='';
            $arr['status']='1';
            $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['added_date']=date('Y-m-d H:i:s');
            $InsertRecord = $AmbulanceClass->AddAmbulance($arr); 
            if($InsertRecord)
                {
                    echo 'InsertSuccess'; // Update Record
                    exit;
                }
                else 
                {
                    echo 'Not_Insert'; // Insert Record
                    exit;
                }
        }
    }
}
else if($_REQUEST['action']=='vw_ambulance')
{
    $arr['amb_id']=$_REQUEST['amb_id'];
    $AmbDtls=$AmbulanceClass->GetambulanceById($arr);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Professional Details</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="editform">
                <label>Ambulance No</label>
                <div class="value">
                    <?php if(!empty($AmbDtls['amb_no'])) { echo $AmbDtls['amb_no']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Mobile No</label>
                <div class="value">
                    <?php if(!empty($AmbDtls['mob_no'])) { echo $AmbDtls['mob_no']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Address</label>
                <div class="value">
                    <?php if(!empty($AmbDtls['address'])) { echo $AmbDtls['address']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Lattitude </label>
                <div class="value">
                    <?php if(!empty($AmbDtls['lat'])) { echo $AmbDtls['lat']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Longitude</label>
                <div class="value">
                    <?php if(!empty($AmbDtls['long'])) { echo $AmbDtls['long']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Cost Per Km</label>
                <div class="value">
                    <?php if(!empty($AmbDtls['cost_per_km'])) { echo $AmbDtls['cost_per_km']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Ambulance Type</label>
                <div class="value">
                    <?php if(!empty($AmbDtls['amb_type'])) { echo $AmbDtls['amb_type']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Ambulance Status</label>
                <div class="value">
                    <?php if(!empty($AmbDtls['amb_status'])) { echo $AmbDtls['amb_status']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Base Location </label>
                <div class="value">
                    <?php if(!empty($AmbDtls['bs_nm'])) { echo $AmbDtls['bs_nm']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added Date  </label>
                <div class="value">
                    <?php if(!empty($AmbDtls['added_date'])) { echo $AmbDtls['added_date']; } else {  echo "-"; } ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
    
?>
