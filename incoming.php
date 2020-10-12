<?php 

require_once 'inc_classes.php';
require_once 'classes/avayaClass.php';
$avayaClass=new avayaClass();

$body = file_get_contents('php://input');
$post = json_decode($body);
if(json_last_error()){ 
echo json_encode(array('status'=>'fail','message'=>"Wrong json data!"));
die();
}
$agent = $post->CalledDevice;

$log_time = date('Y-m-d H:i:s');
//$post_encode = json_encode($post);
$current_time = strtotime(date('Y-m-d H:i:s')); 

if (strlen($agent) == 12 && substr($agent, 0, 2) == "91")
    $mobile = substr($agent, 2, 10);
//echo $mobile;
$avaya_data = array(
    'calling_phone_no' => $mobile,
    'CallUniqueID'=> $post->CallUniqueID,
    'ext_no' => $post->CallingDevice,
    'agent_no' => $post->CalledDevice,
   // 'message' => $post->CallStateDesc,
   'message' => 'Ringing',
    'status' => $post->CallState,
    'call_audio'=> $post->Param1,
    'call_datetime' => date('Y-m-d H:i:s'),
   'is_deleted' => '0');
   //print_r($avaya_data);
   if($post->CallState == "R" || $post->CallState == "B"){
       //if($_SESSION['mode_status'] == '2'){ 
       $avaya_data['call_rinning_datetime'] = date('Y-m-d').' '.$post->CallTime;
       $avaya_data['avaya_call_time'] = $post->calltime;
       $avaya_data['call_datetime'] = date('Y-m-d H:i:s');
       $avaya_data['cl_status'] ='1' ;
       $avaya_data_insert =$avayaClass->insert_avaya_incoming_call($avaya_data);
       echo $avaya_data_insert;
    } 
//}
    else{
        if($post->CallState == "D"){
        $updateEvents= "update sp_incoming_call set status='D' , cl_status='2' where calling_phone_no = '".$post->CalledDevice."' AND CallUniqueID='".$post->CallUniqueID."' ";
        $db->query($updateEvents); 
    }
}
?>