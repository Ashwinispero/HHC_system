<?php   require_once 'inc_classes.php';
	require_once '../classes/rescheduleSessionClass.php';
	$rescheduleSessionClass = new rescheduleSessionClass();
	require_once "../classes/thumbnail_images.class.php";
	require_once "../classes/SimpleImage.php";
?>
<?php
if($_REQUEST['action'] == 'vw_add_reschedule_session')
{
	// Get Reschedule Session Details
	$rescheduleSessionId = $_REQUEST['reschedule_session_id'];
	$rescheduleSessionDtls = $rescheduleSessionClass->getRescheduleSessionById($rescheduleSessionId);
	?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<h4 class="modal-title"><?php if(!empty($rescheduleSessionDtls)) { echo "Edit"; } else { echo "Add"; } ?> reschedule session </h4>
		</div>
		<div class="modal-body">
			<form class="form-inline" name="frm_add_reschedule_session" id="frm_add_reschedule_session" method="post" action ="reschedule_session_ajax_process.php?action=add_reschedule_session" autocomplete="off">
				<div class="scrollbars">
					<div class="editform">
						<label>Select User <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="professional_type" id="professional_type" class="validate[required]">
                                    <option value=""<?php if($_POST['professional_type']=='') { echo 'selected="selected"'; } else if($rescheduleSessionDtls['professional_type']=='') { echo 'selected="selected"'; } ?>>User Type</option>
                                    <option value="1"<?php if($_POST['professional_type']=='1') { echo 'selected="selected"'; } else if($rescheduleSessionDtls['professional_type'] == '1') { echo 'selected="selected"'; }?>>Professional</option>
                                    <option value="2"<?php if($_POST['professional_type']=='2') { echo 'selected="selected"'; } else if($rescheduleSessionDtls['professional_type'] == '2') { echo 'selected="selected"'; }?>>Patient</option>
                                </select>
                            </label>
                        </div>
					</div>
					
					<div class="editform">
						<label>Select Event <span class="required">*</span></label>
						<div class="value">
							<input type="hidden" name="reschedule_session_id" id="reschedule_session_id" value="<?php echo $rescheduleSessionId; ?>" />
							<!-- Add dropdowon of select event 
							-->
							<select name="event_id" id="event_id"></select>
						</div>
					</div>
					
					<div class="editform">
						<label>Select Detail plan of care <span class="required">*</span></label>
						<div class="value">
							<!-- Add dropdowon of select detail plan of care 
							-->
							<select name="detail_plan_of_care_id" id="detail_plan_of_care_id"></select>
						</div>
					</div>
					
					<div class="editform">
						<label>Select Professional <span class="required">*</span></label>
						<div class="value">
							<!-- Add dropdowon of select detail plan of care 
							-->
							<select name="professional_id" id="professional_id"></select>
						</div>
					</div>
					
					<div class="editform">
						<label>Reschedule Start Date <span class="required">*</span></label>
						<div class="value">
							<!-- Add datepicker for select date
							-->
							<input type="text" name="reschedule_start_date" id="reschedule_start_date" value="<?php if(!empty($_POST['reschedule_start_date'])) { echo date('d-m-Y',strtotime($_POST['reschedule_start_date'])); } else if(!empty($rescheduleSessionDtls['reschedule_start_date'])) { echo date('d-m-Y',strtotime($rescheduleSessionDtls['reschedule_start_date'])); } else { echo ""; } ?>" class="validate[required] form-control datepicker" style="width:100% !important;" />
						</div>
					</div>
					
					<div class="editform">
						<label>Reschedule Start Time <span class="required">*</span></label>
						<div class="value">
							<!-- Add timepicker for select time
							-->
							<input type="text" name="reschedule_start_time" id="reschedule_start_time" value="<?php if(!empty($_POST['reschedule_start_time'])) { echo $_POST['reschedule_start_time']; } else if(!empty($rescheduleSessionDtls['reschedule_start_time'])) { echo $rescheduleSessionDtls['reschedule_start_time']; } else { echo ""; } ?>" class="validate[required] form-control time start" style="width:100% !important;" />
						</div>
					</div>
					
					<div class="editform">
						<label>Reschedule End Date <span class="required">*</span></label>
						<div class="value">
							<!-- Add datepicker for select date
							-->
							<input type="text" name="reschedule_end_date" id="reschedule_end_date" value="<?php if(!empty($_POST['reschedule_end_date'])) { echo date('d-m-Y',strtotime($_POST['reschedule_end_date'])); } else if(!empty($rescheduleSessionDtls['reschedule_end_date'])) { echo date('d-m-Y',strtotime($rescheduleSessionDtls['reschedule_end_date'])); } else { echo ""; } ?>" class="validate[required] form-control datepicker" style="width:100% !important;" />
						</div>
					</div>
					
					<div class="editform">
						<label>Reschedule End Time <span class="required">*</span></label>
						<div class="value">
							<!-- Add timepicker for select time
							-->
							<input type="text" name="reschedule_end_date" id="reschedule_end_date" value="<?php if(!empty($_POST['reschedule_end_time'])) { echo $_POST['reschedule_end_time']; } else if(!empty($rescheduleSessionDtls['reschedule_end_time'])) { echo $rescheduleSessionDtls['reschedule_end_time']; } else { echo ""; } ?>" class="validate[required] form-control time end" style="width:100% !important;" />
						</div>
					</div>
					
					<div class="editform">
						<label>Reschedule Reason <span class="required">*</span></label>
						<div class="value">
							<textarea name="reschedule_reason" id="reschedule_reason" class="form-control" maxlength="160" style="width: 265px; height: 100px;"><?php if(!empty($_POST['reschedule_reason'])) { echo $_POST['reschedule_reason']; } else if(!empty($rescheduleSessionDtls['reschedule_reason'])) { echo $rescheduleSessionDtls['reschedule_reason']; } ?></textarea>
						</div>
					</div>
					
					<div class="modal-footer">
						<input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_reschedule_session_submit();" />
					</div>  

				</div>
			</form>
		</div>
	<?php
} else if ($_REQUEST['action'] == 'vw_reschedule_session') {
	//Get reschedule session details
	$rescheduleSessionId = $_REQUEST['reschedule_session_id'];
	$rescheduleSessionDtls = $rescheduleSessionClass->getRescheduleSessionById($rescheduleSessionId);
	
	$actualDate = "";
	if (date('Y-m-d',strtotime($rescheduleSessionDtls['session_start_date'])) == date('Y-m-d', strtotime($rescheduleSessionDtls['session_end_date']))) {
		$actualDate = date('d M Y h:i A', strtotime($rescheduleSessionDtls['session_start_date'])) ." - " . 
			date('h:i A', strtotime($rescheduleSessionDtls['session_end_date']));
	} else {
		$actualDate = date('d M Y h:i A', strtotime($rescheduleSessionDtls['session_start_date'])) ." <br/> TO " . 
			date('d M Y h:i A',strtotime($rescheduleSessionDtls['session_end_date']));
	}

	$proposedDate = "";
	if (date('Y-m-d', strtotime($rescheduleSessionDtls['reschedule_start_date'])) == date('Y-m-d', strtotime($rescheduleSessionDtls['reschedule_end_date']))) {
		$proposedDate = date('d M Y h:i A', strtotime($rescheduleSessionDtls['reschedule_start_date'])) ." - " . 
			date('h:i A', strtotime($rescheduleSessionDtls['reschedule_end_date']));
	} else {
		$proposedDate = date('d M Y h:i A', strtotime($rescheduleSessionDtls['reschedule_start_date'])) ." <br/> TO " . 
			date('d M Y h:i A',strtotime($rescheduleSessionDtls['reschedule_end_date']));
	}
	?>
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Reschedule Session Details</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="editform">
                <label>Event Code</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['event_code'])) { echo $rescheduleSessionDtls['event_code']; } else {  echo ""; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Professional Name (Code)</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['professional_name'])) { echo $rescheduleSessionDtls['professional_name'] . "(" . $rescheduleSessionDtls['professional_code'] . ")"; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Professional Email Id</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['email_id'])) { echo $rescheduleSessionDtls['email_id']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Professional Mobile Number</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['mobile_no'])) { echo $rescheduleSessionDtls['mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
			<div class="editform">
                <label>Patient Name (HHC Code)</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['patient_name'])) { echo $rescheduleSessionDtls['patient_name'] . "(" . $rescheduleSessionDtls['hhc_code'] . ")"; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Patient Email Id</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['patient_email_id'])) { echo $rescheduleSessionDtls['patient_email_id']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Patient Mobile Number</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['patient_mobile_no'])) { echo $rescheduleSessionDtls['patient_mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
			<div class="editform">
                <label>Actual Date & Time</label>
                <div class="value">
                    <?php if (!empty($actualDate)) { echo $actualDate; } else {echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Proposed Date & Time</label>
                <div class="value">
                    <?php if (!empty($proposedDate)) { echo $proposedDate; } else {echo "-"; } ?>
                </div>
            </div>
			<div class="editform">
                <label>Reschedule Request Reason</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['reschedule_reason'])) { echo $rescheduleSessionDtls['reschedule_reason']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Reschedule Request Raised By</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['raisedBy'])) { echo $rescheduleSessionDtls['raisedBy']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Status</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['statusVal'])) { echo $rescheduleSessionDtls['statusVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['added_by'])) { echo $rescheduleSessionDtls['added_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added Date</label>
                <div class="value"> 
                    <?php if(!empty($rescheduleSessionDtls['added_date']) && $rescheduleSessionDtls['added_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A', strtotime($rescheduleSessionDtls['added_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified By</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['last_modified_by'])) { echo $rescheduleSessionDtls['last_modified_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified Date</label>
                <div class="value">
                    <?php if(!empty($rescheduleSessionDtls['modified_date']) && $rescheduleSessionDtls['modified_date'] != '0000-00-00 00:00:00') { echo date('jS F Y H:i:s A',strtotime($rescheduleSessionDtls['modified_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
        </div>
    </div>
	<?php
} else if ($_REQUEST['action'] == 'add_reschedule_session') {
	
} else if ($_REQUEST['action'] == 'vw_change_status') {
	//Get reschedule session details
	$rescheduleSessionId = $_REQUEST['reschedule_session_id'];
	$rescheduleSessionDtls = $rescheduleSessionClass->getRescheduleSessionById($rescheduleSessionId);
	
	if (!empty($rescheduleSessionDtls)) {
		$requesterType = "";
		$requesterName = "";
		$approvedByProfessional = 0;
		$approvedByPatient = 0;
		// Check request initated by
		if ($rescheduleSessionDtls['professional_type'] == '1') {
			$requesterType = "Professional";
			$requesterName = $rescheduleSessionDtls['professional_name'];
		} else {
			$requesterType = "Patient";
			$requesterName = $rescheduleSessionDtls['patient_name'];
		}
		
		// Check  is it request approved by professional
		if (!empty($rescheduleSessionDtls['professional_acceptance_status']) && !empty($rescheduleSessionDtls['professional_acceptance_narration'])) {
			$approvedByProfessional = 1;
		}
		
		// Check  is it request approved by professional
		if (!empty($rescheduleSessionDtls['patient_acceptance_status']) && !empty($rescheduleSessionDtls['patient_acceptance_narration'])) {
			$approvedByPatient = 1;
		}
		
		//Display appropriate block logic
		$showPatientDivContent = "N";
		$showProfessionalDivContent = "N";
		
		if ($requesterType == "Professional" && $approvedByProfessional == 1) {
			if ($approvedByPatient != 1) {
				$showPatientDivContent = "Y";
			}
		} else if ($requesterType == "Professional" && empty($approvedByProfessional)) {
			$showProfessionalDivContent = "Y";
		}
		
		if ($requesterType == "Patient" && $approvedByPatient == 1) {
			if ($approvedByProfessional != 1) {
				$showProfessionalDivContent = "Y";
			}
		} else if ($requesterType == "Patient" && empty($approvedByPatient)) {
			$showPatientDivContent = "Y";
		}

		if (!empty($rescheduleSessionDtls['professional_acceptance_status']) && !empty($rescheduleSessionDtls['professional_acceptance_narration'])) {
			$showProfessionalDivContent = "Y";
		}
		
		// Check  is it request approved by professional
		if (!empty($rescheduleSessionDtls['patient_acceptance_status']) && !empty($rescheduleSessionDtls['patient_acceptance_narration'])) {
			$showPatientDivContent = "Y";
		}
		
		$actualDate = "";
		if (date('Y-m-d',strtotime($rescheduleSessionDtls['session_start_date'])) == date('Y-m-d', strtotime($rescheduleSessionDtls['session_end_date']))) {
			$actualDate = date('d M Y h:i A', strtotime($rescheduleSessionDtls['session_start_date'])) ." - " . 
				date('h:i A', strtotime($rescheduleSessionDtls['session_end_date']));
		} else {
			$actualDate = date('d M Y h:i A', strtotime($rescheduleSessionDtls['session_start_date'])) ." <br/> TO " . 
				date('d M Y h:i A',strtotime($rescheduleSessionDtls['session_end_date']));
		}

		$proposedDate = "";
		if (date('Y-m-d', strtotime($rescheduleSessionDtls['reschedule_start_date'])) == date('Y-m-d', strtotime($rescheduleSessionDtls['reschedule_end_date']))) {
			$proposedDate = date('d M Y h:i A', strtotime($rescheduleSessionDtls['reschedule_start_date'])) ." - " . 
				date('h:i A', strtotime($rescheduleSessionDtls['reschedule_end_date']));
		} else {
			$proposedDate = date('d M Y h:i A', strtotime($rescheduleSessionDtls['reschedule_start_date'])) ." <br/> TO " . 
				date('d M Y h:i A',strtotime($rescheduleSessionDtls['reschedule_end_date']));
		}
	}
	?>
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Reschedule Session Details</h4>
    </div>
	<div class="modal-body">
		<form class="form-inline" name="frm_change_reschedule_session" id="frm_change_reschedule_session" method="post" action ="reschedule_session_ajax_process.php?action=change_reschedule_session_status" autocomplete="off">
				<div class="editform">
					<label>Request Initiated By</label>
					<div class="value">
						<input type="hidden" name="reschedule_session_id" id="reschedule_session_id" value="<?php echo $rescheduleSessionId; ?>" />
						<?php if (!empty($rescheduleSessionDtls['requesterType'])) { echo $rescheduleSessionDtls['requesterType']; } else { ""; } ?>
					</div>
				</div>
				
				<div class="editform">
					<label>Requester Name</label>
					<div class="value">
						<?php if (!empty($requesterName)) { echo $requesterName; } else { ""; } ?>
					</div>
				</div>
				
				<div class="editform">
                <label>Actual Date & Time</label>
					<div class="value">
						<?php if (!empty($actualDate)) { echo $actualDate; } else { echo "-"; } ?>
					</div>
				</div>
				
				<div class="editform">
					<label>Proposed Date & Time</label>
					<div class="value">
						<?php if (!empty($proposedDate)) { echo $proposedDate; } else { echo "-"; } ?>
					</div>
				</div>
				
				<div class="editform">
					<label>Request Reason</label>
					<div class="value">
						<?php if (!empty($rescheduleSessionDtls['reschedule_reason'])) { echo $rescheduleSessionDtls['reschedule_reason']; } else { ""; } ?>
					</div>
				</div>
				
				
				<div class="editform" style="<?php if ($showProfessionalDivContent == 'Y') { echo 'display:inline-block;'; } else { echo 'display:none;'; }?>">
					<label>Request Approval Status of professional </label>
					<div class="value">
						<?php
							if ($approvedByProfessional) {
								echo $rescheduleSessionDtls['profAccStatus'];
								echo "<br>";
								echo $rescheduleSessionDtls['professional_acceptance_narration'];
							} else {
								?>
								<input type="hidden" name="isProfessionalApproveRequest" id="isProfessionalApproveRequest" value="<?php if (empty($approvedByProfessional) && $showProfessionalDivContent == 'Y') { echo "1"; } ?>" />
								<select name="professional_acceptance_status" id="patient_acceptance_status" class="validate[required]">
									<option value="">Select status</option>
									<option value="2">Accepted</option>
									<option value="3">Rejected</option>
								</select>
								<div class="clearfix"></div>
								<div style="margin:10px 0px 10px 0px;">
									<textarea name="professional_acceptance_narration" id="professional_acceptance_narration" class="validate[required] form-control" maxlength="160" style="width: 265px; height: 100px;"></textarea>
								</div>
								<?php 
							}
						?>
					</div>
				</div>
				
				<div class="editform" style="<?php if ($showPatientDivContent == 'Y') { echo 'display:inline-block;'; } else { echo 'display:none;'; }?>">
					<label>Request Approval Status of patient</label>
					<div class="value">
						<?php
							if ($approvedByPatient) {
								echo $rescheduleSessionDtls['patientAccStatus'];
								echo "<br>";
								echo $rescheduleSessionDtls['patient_acceptance_narration'];
							} else {
								?>
									<input type="hidden" name="isPatientApproveRequest" id="isPatientApproveRequest" value="<?php if (empty($approvedByPatient) && $showPatientDivContent == 'Y') { echo "1"; } ?>" />
									<select name="patient_acceptance_status" id="patient_acceptance_status" class="validate[required]">
										<option value="">Select status</option>
										<option value="2">Accepted</option>
										<option value="3">Rejected</option>
									</select>
									<div class="clearfix"></div>
									<div style="margin:10px 0px 10px 0px;">
										<textarea name="patient_acceptance_narration" id="patient_acceptance_narration" class="validate[required] form-control" maxlength="160" style="width: 265px; height: 100px;"></textarea>
									</div>
								<?php 
							}
						?>
					</div>
				</div>
				
				<div class="modal-footer">
					<?php if (empty($approvedByProfessional) || empty($approvedByPatient)) { ?>
						<input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return change_reschedule_session_submit();" />
					<?php } ?>
				</div>
		</form>
	</div>
	<?php 
} else if ($_REQUEST['action'] == 'change_reschedule_session_status') {
	$success=0;
    $errors=array(); 
    $i=0;
	
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
    {
		$rescheduleSessionId = strip_tags($_POST['reschedule_session_id']); 
		$isPatientApproveRequest = strip_tags($_POST['isPatientApproveRequest']);
		$isProfessionalApproveRequest = strip_tags($_POST['isProfessionalApproveRequest']);
		
		$patientAcceptanceStatus = strip_tags($_POST['patient_acceptance_status']);
        $patientAcceptanceNarration = $_POST['patient_acceptance_narration'];
		
		$professionalAcceptanceStatus = strip_tags($_POST['professional_acceptance_status']);
        $professionalAcceptanceNarration = $_POST['professional_acceptance_narration'];
		
		if ($isPatientApproveRequest) {
			if($patientAcceptanceStatus == '')
			{
				$success = 0;
				$errors[$i++] = "Please select status";
			}
			
			if($patientAcceptanceNarration == '')
			{
				$success = 0;
				$errors[$i++] = "Please enter narration";
			}
		}
		
		if ($isProfessionalApproveRequest) {
			if($professionalAcceptanceStatus == '')
			{
				$success = 0;
				$errors[$i++] = "Please select status";
			}
			
			if ($professionalAcceptanceNarration == '')
			{
				$success = 0;
				$errors[$i++] = "Please enter narration";
			}
		}
		
		if(count($errors))
        {
            echo 'validationError';
            exit;
        } else {
			
			$success = 1;
			$arr['reschedule_session_id'] = $rescheduleSessionId;
			
			if ($isProfessionalApproveRequest) {
				$arr['professional_acceptance_status'] = $professionalAcceptanceStatus;
				$arr['professional_acceptance_narration'] = $professionalAcceptanceNarration;
			}
			
			if ($isPatientApproveRequest) {
				$arr['patient_acceptance_status'] = $patientAcceptanceStatus;
				$arr['patient_acceptance_narration'] = $patientAcceptanceNarration;
			}
			
			$arr['modified_user_id'] = strip_tags($_SESSION['admin_user_id']);
			$arr['modified_user_type'] = '2';
			$arr['modified_date'] = date('Y-m-d H:i:s');
			
			$recordStatus = $rescheduleSessionClass->changeRescheduleSessionStatus($arr);

			if (!empty($recordStatus)) {
				echo 'Success';
                exit;
			} else {
				echo 'Error';
                exit;
			}	
		}
	}
}
?>