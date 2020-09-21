<?php
require_once 'inc_classes.php';
require_once '../classes/professionalsAvailabilityClass.php';
require_once '../classes/commonClass.php';
require_once '../classes/professionalsClass.php';
$professionalsAvailabilityClass = new professionalsAvailabilityClass();
$commonClass = new commonClass();
$professionalsClass = new professionalsClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
require_once "../classes/functions.php";

if(!class_exists('AbstractDB')) {
    require_once '../classes/AbstractDB.php';
}

if ($_REQUEST['action'] == 'getSubServices') {
	$serviceId = $_REQUEST['service_id'];
	$checkSubServiceExist = "SELECT sub_service_id, recommomded_service FROM sp_sub_services WHERE service_id = '" . $serviceId . "'";
	if(mysql_num_rows($db->query($checkSubServiceExist)))
	{
		$subServicesList = $professionalsClass->GetAllServicesByServiceId($serviceId);
		if (!empty($subServicesList)) {
			echo '<div class="col-lg-2 paddingR0 inline_dp pull-left dropdown">
					<div class="dd">
					<select name="sub_service_id[]" id="sub_service_id" class=dp_country dropdown multiselect" multiple="multiple" onchange="searchRecords();">';
						foreach($subServicesList as $key => $valSubService) {
							if(!empty($_REQUEST['sub_service_id']) && $valSubService['sub_service_id'] == $_REQUEST['sub_service_id']) {
								echo '<option value="' . $valSubService['sub_service_id'] . '" selected="selected">' . $valSubService['recommomded_service'] . '</option>';
							} else {
								echo '<option value="' . $valSubService['sub_service_id'] . '">' . $valSubService['recommomded_service'] . '</option>';
							}
						}
					echo '</select>
				</div>
			</div>';
		}
	}
	else
	{
		echo 'error';
		exit;
	}
}
else if ($_REQUEST['action'] == 'vw_professional_availability') {
	// Getting Professional Details
    $serviceProfessionalId = $_REQUEST['service_professional_id'];
    $locationValue = $_REQUEST['location_value'];
    $profAvailabilityDtls = $professionalsAvailabilityClass->getAvailabilityByProfessionalId($serviceProfessionalId, $locationValue);
	
	if (!empty($profAvailabilityDtls)) {
		$resultantArr = array();
		foreach ($profAvailabilityDtls AS $key => $valProfLocation) {
			$locationNm = "";
			$valProfLocation['location_name'] = '';
			$arg['service_professional_id'] = $serviceProfessionalId;
			$arg['professional_location_id'] = $valProfLocation['professional_availability_id'];
			$profLocationDtls = $professionalsAvailabilityClass->getLocationByLocationId($arg);
			if (!empty($profLocationDtls)) {
				$valProfLocation['location_name'] = str_replace("@#", "<br/><br/>", $profLocationDtls);
			}
			$resultantArr[] = $valProfLocation;
		}
	}
?>
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Professional Availability Details</h4>
    </div>
	<div class="modal-body">
		<div>
			<table class="table table-hover table-bordered">
				<tr>
					<th>Day Name</th>
					<th>Start Time</th>
					<th>End Time</th>
					<!--<th>Location</th>-->
				</tr>
				<?php
					if (!empty($resultantArr)) {
						foreach ($resultantArr AS $key => $valAvailability) {
							echo '<tr>
									<td>' . $valAvailability['dayVal'] . '</td>
									<td>' . date('h:i A', strtotime($valAvailability['start_time'])) . '</td>
									<td>' . date('h:i A', strtotime($valAvailability['end_time'])) . '</td>
                                </tr>';
                                
                                //<td>' . $valAvailability['location_name'] . '</td>
						}
					} else {
						echo '<tr>
									<td colspan="4"> No Record found</td>
								</tr>';
					}
				?>
			<table>
		</div>
	</div>
<?php
}
else if($_REQUEST['action']=='vw_professional')
{
    // Getting Professional Details
    $arr['service_professional_id']=$_REQUEST['service_professional_id'];
    $ProfDtls=$professionalsClass->GetProfessionalById($arr);
    
    if (!empty($ProfDtls)) {
        // Get Professional service details
        $profServiceList = $professionalsClass->GetProfessionalServices($arr);
        
        // Get Professional sub service details
        $arr['serviceType'] = 'subService';
        $arr['service_id'] = $profServiceList['service_id'];
        $profSubServiceList = $professionalsClass->GetProfessionalServices($arr);
        unset($arr['serviceType']);
    }
    
    // Getting Professional other details
    $ProfOtherDtls=$professionalsClass->GetProfessionalOtherDtlsById($arr);
    //print_r($ProfDtls);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Professional Details</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="editform">
                <label>Professional Code</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['professional_code'])) { echo $ProfDtls['professional_code']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Type</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['typeVal'])) { echo $ProfDtls['typeVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Name</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['name'])) { echo $ProfDtls['name']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Email Address</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['email_id'])) { echo $ProfDtls['email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Phone Number</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['phone_no'])) { echo $ProfDtls['phone_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Mobile Number</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['mobile_no'])) { echo $ProfDtls['mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Birth Date</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['dob']) && $ProfDtls['dob']!='0000-00-00') { echo date('d M Y',strtotime($ProfDtls['dob'])); } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Assigned Services</label>
                <div class="value">
                    <?php if(!empty($profServiceList)) {
                            echo (!empty($profServiceList)) ? $profServiceList['service_title'] : 'Not Available';
                        } else {  
                            echo "Not Available"; 
                        } 
                    ?>
                </div>
            </div>
            <div class="editform">
                <label>Assigned Sub Services</label>
                <div class="value">
                    <?php if(!empty($profSubServiceList)) {
                            $subServiceList = '';
                            foreach ($profSubServiceList AS $key => $valSubService) {
                                $subServiceList .= $valSubService['recommomded_service'] . "," . "<br/>";
                            }

                            echo (!empty($subServiceList)) ? rtrim(trim($subServiceList), ",") : 'Not Available';
                        } else {  
                            echo "Not Available"; 
                        } 
                    ?>
                </div>
            </div>
            <div class="editform">
                <label>Home Address</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['address'])) { echo $ProfDtls['address']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Home Location</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['google_home_location'])) { echo $ProfDtls['google_home_location']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Address</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['work_address'])) { echo $ProfDtls['work_address']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Location</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['google_work_location'])) { echo $ProfDtls['google_work_location']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Set Address</label>
                <div class="value">
                    <?php if($ProfDtls['set_location'] == '1') { echo 'Home Location'; } else {  echo "Work Location"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Phone</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['work_phone_no'])) { echo $ProfDtls['work_phone_no']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Email Address</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['work_email_id'])) { echo $ProfDtls['work_email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            
<!--            <div class="editform">
                <label>Location</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['locationNm'])) { echo $ProfDtls['locationNm']; } else {  echo "-"; } ?>
                </div>
            </div>-->
            
            <div class="editform">
                <label>PIN Code</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['LocationPinCode'])) { echo $ProfDtls['LocationPinCode']; } else {  echo "-"; } ?>
                </div>
            </div>
            <?php if(!empty($ProfDtls['reference_type'])) { if($ProfDtls['reference_type']=='1') { ?>
            <div class="editform">
                <label>Qualification</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['qualification'])) { echo $ProfOtherDtls['qualification']; } else {  echo "-"; } ?>
                </div>
            </div>
            
            <div class="editform">
                <label>Specialization</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['skill_set'])) { echo $ProfOtherDtls['skill_set']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Skill Sets</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['specialization'])) { echo $ProfOtherDtls['specialization']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Experience</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['work_experience'])) { echo $ProfOtherDtls['work_experience']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Hospital Attached To</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['hospital_attached_to'])) { echo $ProfOtherDtls['hospital_attached_to']; } else {  echo "-"; } ?>
                </div>
            </div>
            <?php } } ?>
            <div class="editform">
                <label>PAN CARD No.</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['pancard_no'])) { echo $ProfOtherDtls['pancard_no']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Status</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['statusVal'])) { echo $ProfDtls['statusVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['added_by'])) { echo $ProfDtls['added_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value"> 
                    <?php if(!empty($ProfDtls['added_date']) && $ProfDtls['added_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A', strtotime($ProfDtls['added_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified By</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['last_modified_by'])) { echo $ProfDtls['last_modified_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified Date</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['last_modified_date']) && $ProfDtls['last_modified_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A',strtotime($ProfDtls['last_modified_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
