<?php require_once('inc_classes.php'); 
	if (isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') {
		$col_class   = "icon3";
		$del_visible = "Y";
	} else {
		$col_class   = "icon2"; 
		$del_visible = "N";
	} 
?>
<?php
if(!$_SESSION['admin_user_id']) {
    echo 'notLoggedIn';
} else {	
	date_default_timezone_set('Asia/Kolkata'); 
	$date                    = date('Y-m-d H:i:s');
	$service_professional_id = $_GET['service_professional_id'];
	$date_form               = $_GET['date_form'];
	$date_to                 = $_GET['date_to'];
	$note                    = $_GET['note'];
	$status                  = 1;
	$schedule = mysql_query("INSERT INTO sp_professional_weekoff(service_professional_id,date_form,date_to,Note,date,Leave_status) VALUES('$service_professional_id','$date_form','$date_to','$note','$date','$status')")or die(mysql_error());
	if ($schedule) {
		// Add activity log while adding professional week off details
		$insertActivityArr = array();
		$insertActivityArr['module_type']   = '2';
		$insertActivityArr['module_id']     = '22';
		$insertActivityArr['module_name']   = 'Manage Professional Weekoff';
		$insertActivityArr['event_id']      = '';
		$insertActivityArr['purpose_id']    = '';
		$insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
		$insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
		$insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
		$activityDesc = "Professional weekoff details added successfully by " . $_SESSION['admin_user_name'] . "\r\n";
		$insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
		$db->query_insert('sp_user_activity', $insertActivityArr);
		unset($insertActivityArr);
		echo 'Y';
	} else {
		echo 'N';
	}
}
?>