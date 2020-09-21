<?php require_once('inc_classes.php');
    require_once '../classes/config.php';
    if (isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type'] == '1') {
      $col_class   = "icon3";
      $del_visible = "Y";
    } else {
      $col_class   = "icon2"; 
      $del_visible = "N";
    }

if(!$_SESSION['admin_user_id']) {
    echo 'notLoggedIn';
} else {
    $eventCode = $_GET['event_id'];
    // Get event details
    $getEventSql = "SELECT event_id,
        event_code,
        event_status,
        Tally_Remark
    FROM sp_events
    WHERE event_code = '" . $eventCode . "'";
    
    $eventDtls = $db->fetch_array($db->query($getEventSql));

    echo $eventCode;
    $Update_status = mysql_query("UPDATE sp_events SET Tally_Remark = '2' WHERE event_code = '" . $eventCode . "'")or die(mysql_error());

    if (!empty($Update_status)) {
        $insertActivityArr = array();
        $insertActivityArr['module_type']   = '2';
        $insertActivityArr['module_id']     = '15';
        $insertActivityArr['module_name']   = 'Manage Events';
        $insertActivityArr['event_id']      = '';
        $insertActivityArr['purpose_id']    = '';
        $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
        $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
        $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
        $activityDesc = "Event " . $eventDtls['event_code'] . " details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";
        $activityDesc .= "Tally_Remark is change from " . $eventDtls['Tally_Remark'] . " to 2 \r\n";
        $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
        $db->query_insert('sp_user_activity', $insertActivityArr);
        unset($insertActivityArr);
    }
}
?>