<?php   require_once 'inc_classes.php'; 
        include "classes/eventClass.php";
        $eventClass = new eventClass();
        include "classes/employeesClass.php";
        $employeesClass = new employeesClass();
        include "classes/commonClass.php";
        $commonClass= new commonClass();
?>
<?php
if($_REQUEST['action'] == 'GeneralInfoFormSubmit')
{
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $temp_event_id=$_POST['enquiryEvent_id'];
        $prv_purpose_id=$_POST['general_purpose_id'];
        $enquiryNote=strip_tags($_POST['general_info']);
        //var_dump($_POST);
        if($prv_purpose_id=='')
        {
            $success=0;
            $errors[$i++]="Please select purpose of call";
        }
        if($enquiryNote=='')
        {
            $success=0;
            $errors[$i++]="Please enter information";
        }
        if(count($errors))
        {
           //print_r($errors);
           echo 'error'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $updateEditedData['note'] =  ucfirst(strtolower($enquiryNote));
            $updateEditedData['event_status']='5';
            $updateEditedData['last_modified_by'] = $_SESSION['employee_id'];
            $updateEditedData['last_modified_date'] = date('Y-m-d H:i:s');
            $where = "event_id ='".$temp_event_id."' ";
            $db->query_update('sp_events',$updateEditedData,$where);
            echo "updated";
        }

    }
}
?>