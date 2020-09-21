<?php   require_once 'inc_classes.php';
	require_once '../classes/sessionsClass.php';
	$sessionsClass = new sessionsClass();
	require_once "../classes/thumbnail_images.class.php";
	require_once "../classes/SimpleImage.php";
?>
<?php
if ($_REQUEST['action'] == 'vw_session_dtls') {
	//Get session details
	$detailedPlanOfCareId = $_REQUEST['Detailed_plan_of_care_id'];
	$sessionDtls = $sessionsClass->getSessionById($detailedPlanOfCareId);

	$actualDate = "";
	if (date('Y-m-d',strtotime($sessionDtls['start_date'])) == date('Y-m-d', strtotime($sessionDtls['end_date']))) {
		$actualDate = date('d M Y h:i A', strtotime($sessionDtls['start_date'])) ." - " . 
			date('h:i A', strtotime($sessionDtls['end_date']));
	} else {
		$actualDate = date('d M Y h:i A', strtotime($sessionDtls['start_date'])) ." <br/> TO " . 
			date('d M Y h:i A',strtotime($sessionDtls['end_date']));
	}
	?>
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Session Details</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="editform">
                <label>Event Code</label>
                <div class="value">
                    <?php if(!empty($sessionDtls['event_code'])) { echo $sessionDtls['event_code']; } else {  echo ""; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Professional Name (Code)</label>
                <div class="value">
                    <?php if(!empty($sessionDtls['professional_name'])) { echo $sessionDtls['professional_name'] . "(" . $sessionDtls['professional_code'] . ")"; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Professional Email Id</label>
                <div class="value">
                    <?php if(!empty($sessionDtls['email_id'])) { echo $sessionDtls['email_id']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Professional Mobile Number</label>
                <div class="value">
                    <?php if(!empty($sessionDtls['mobile_no'])) { echo $sessionDtls['mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
			<div class="editform">
                <label>Patient Name (HHC Code)</label>
                <div class="value">
                    <?php if(!empty($sessionDtls['patient_name'])) { echo $sessionDtls['patient_name'] . "(" . $sessionDtls['hhc_code'] . ")"; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Patient Email Id</label>
                <div class="value">
                    <?php if(!empty($sessionDtls['patient_email_id'])) { echo $sessionDtls['patient_email_id']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Patient Mobile Number</label>
                <div class="value">
                    <?php if(!empty($sessionDtls['patient_mobile_no'])) { echo $sessionDtls['patient_mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
			<div class="editform">
                <label>Actual Date & Time</label>
                <div class="value">
                    <?php if (!empty($actualDate)) { echo $actualDate; } else {echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Status</label>
                <div class="value">
                    <?php if(!empty($sessionDtls['statusVal'])) { echo $sessionDtls['statusVal']; } else {  echo "-"; } ?>
                </div>
            </div>
        </div>
    </div>
	<?php
} else if ($_REQUEST['action'] == 'vw_change_status') {
	//Get session details
	$detailedPlanOfCareId = $_REQUEST['Detailed_plan_of_care_id'];
	$sessionDtls = $sessionsClass->getSessionById($detailedPlanOfCareId);

	// echo '<pre>';
	// print_r($sessionDtls);
	// echo '</pre>';
	// exit;


	?>
		<div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title">Change Session Status</h4>
	    </div>
	    <div class="modal-body">
	    	<form class="form-inline" name="frm_change_session" id="frm_change_session" method="post" action ="session_ajax_process.php?action=change_session_status" autocomplete="off">
	    		<div class="editform">
					<label>Session Status</label>
					<div class="value">
						<input type="hidden" name="Detailed_plan_of_care_id" id="Detailed_plan_of_care_id" value="<?php echo $detailedPlanOfCareId; ?>" />
						<?php if (!empty($sessionDtls['statusVal'])) { echo $sessionDtls['statusVal']; } else { ""; } ?>
					</div>
				</div>

				<div class="editform">
					<label>Select Status<span class="required">*</span></label>
					<div class="value">
						<select name = "Session_status" id = "Session_status" class = "validate[required] form-control" onChange = "javascript : showReason();">
							<option value=""<?php if ($sessionDtls['Session_status'] == '') { echo "selected = selected"; } ?>>-select status-</option>
							<option value="1"<?php if ($sessionDtls['Session_status'] == 1)  { echo "selected = selected"; } ?>>Pending</option>
							<option value="2"<?php if ($sessionDtls['Session_status'] == 2)  { echo "selected = selected"; } ?>>Completed</option>
							<!--<option value="3"<?php if ($sessionDtls['Session_status'] == 3)  { echo "selected = selected"; } ?>>Upcoming</option>-->
							<option value="4"<?php if ($sessionDtls['Session_status'] == 4)  { echo "selected = selected"; } ?>>Patient No show </option>
							<option value="5"<?php if ($sessionDtls['Session_status'] == 5)  { echo "selected = selected"; } ?>>Professional No show</option>
							<!--<option value="6"<?php if ($sessionDtls['Session_status'] == 6)  { echo "selected = selected"; } ?>>Closed</option>-->
							<!--<option value="7"<?php if ($sessionDtls['Session_status'] == 7)  { echo "selected = selected"; } ?>>Enroute</option>-->
							<!--<option value="8"<?php if ($sessionDtls['Session_status'] == 8)  { echo "selected = selected"; } ?>>Started Session</option>-->
							<!--<option value="9"<?php if ($sessionDtls['Session_status'] == 9)  { echo "selected = selected"; } ?>>Completed Session</option>-->
						</select>
					</div>
				</div>
				
				<div class = "editform reasonContentDiv" style="display:none;">
					<label>Reason<span class="required">*</span></label>
					<div class="value">
						<input type="text" name="reason" id="reason" class="validate[required] form-control" />
					</div>
				</div>
				
				<div class = "editform confirmationDiv" style="display:none;">
					<label>Payment by professional <span class="required">*</span></label>
					<div class="radio">
						<label>
							<input type="radio" name="payment_received_status" id="payment_received_status" value="1" onclick="handleClick(this);"> Yes
							<input type="radio" name="payment_received_status" id="payment_received_status" value="2" onclick="handleClick(this);"> No
						</label>
					</div>
				</div>
				
				<div class = "paymentContentDiv" style="display:none;">
					
					<div class = "editform">
						<label>Type of Payment<span class="required">*</span></label>
						<div class="value">
							<select name="payment_type" id="payment_type" class="validate[required] form-control" >
								<option value = "">-Select-</option>
								<option value = "1">Cash</option>
								<option value = "2">Cheque</option>
								<option value = "3">Card</option>
								<option value = "4">NEFT</option>
							</select>
						</div>
					</div>
					
					<div class = "editform">
						<label>Amount<span class="required">*</span></label>
						<div class="value">
							<input type="text" name="amount" id="amount" class="validate[required] form-control" />
						</div>
					</div>
					
					<div class = "editform">
						<label>Narration<span class="required">*</span></label>
						<div class="value">
							<input type="text" name="narration" id="narration" class="validate[required] form-control" />
						</div>
					</div>
				</div>
				
	    		<div class="modal-footer">
					<input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return change_session_status_submit();" />
				</div>
	    	</form>
	    </div>
	<?php
} else if ($_REQUEST['action'] == 'change_session_status') {
	$success = 0;
    $errors = array(); 
    $i = 0;
	
	if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
    {
		$detailedPlanOfCareId  = strip_tags($_POST['Detailed_plan_of_care_id']); 
		$sessionStatus         = strip_tags($_POST['Session_status']);
		$paymentReceivedStatus = strip_tags($_POST['payment_received_status']);
		$paymentType           = strip_tags($_POST['payment_type']);
		$amount                = strip_tags($_POST['amount']);
		$narration             = strip_tags($_POST['narration']);
		$reason                = strip_tags($_POST['reason']);
		
		if($sessionStatus == '')
		{
			$success = 0;
			$errors[$i++] = "Please select status";
		}
		
		if (!empty($sessionStatus))
		{
			if ($sessionStatus == '2') {
				if($paymentReceivedStatus == '')
				{
					$success = 0;
					$errors[$i++] = "Please select payment received status";
				}
				
				if (!empty($aymentReceivedStatus) && $paymentReceivedStatus == 1) {
					if($paymentType == '')
					{
						$success = 0;
						$errors[$i++] = "Please select payment type";
					}
					
					if($amount == '')
					{
						$success = 0;
						$errors[$i++] = "Please enter amount";
					}
					
					if($narration == '')
					{
						$success = 0;
						$errors[$i++] = "Please enter narration";
					}
				}
			}
			
			if ($sessionStatus == '4' || $sessionStatus == '5') {
				if($reason == '')
				{
					$success = 0;
					$errors[$i++] = "Please enter reason";
				}
			}
		}
		
		if(count($errors))
        {
            echo 'validationError';
            exit;
        } else {
			$success = 1;
			$arr['Detailed_plan_of_care_id'] = $detailedPlanOfCareId;
			$arr['Session_status']           = $sessionStatus;
			$arr['payment_received_status']  = $paymentReceivedStatus;
			$arr['payment_type']             = $paymentType;
			$arr['amount']                   = $amount;
			$arr['narration']                = $narration;
			$arr['reason']                   = $reason;
			$arr['modified_user_id']         = strip_tags($_SESSION['admin_user_id']);
			$arr['modified_by']       		 = '1';
			$arr['modified_date']            = date('Y-m-d H:i:s');
			
			$recordStatus = $sessionsClass->changeSessionStatus($arr);

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