<?php 

require_once 'inc_classes.php';
require_once 'classes/avayaClass.php';
$avayaClass=new avayaClass();

/*
if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';

*/
$body = file_get_contents('php://input');
$post = json_decode($body);
//echo '<pre>';
//print_r($object);
//echo '</pre>';
if(json_last_error()){ 
echo json_encode(array('status'=>'fail','message'=>"Wrong json data!"));
die();
}
$agent = $post->CalledDevice;

$log_time = date('Y-m-d H:i:s');
//$post_encode = json_encode($post);
$current_time = strtotime(date('Y-m-d H:i:s')); 
$avaya_data = array(
    'calling_phone_no' => $post->CalledDevice,
    'CallUniqueID'=> $post->CallUniqueID,
    'ext_no' => $post->CallingDevice,
    'agent_no' => $post->CalledDevice,
    'message' => $post->CallStateDesc,
    'status' => $post->CallState,
    'call_datetime' => date('Y-m-d H:i:s'),
   'is_deleted' => '0');
   //print_r($avaya_data);
   if($post->CallState == "R" || $post->CallState == "B"){
      
       $avaya_data['call_rinning_datetime'] = date('Y-m-d').' '.$post->CallTime;
       $avaya_data['avaya_call_time'] = $post->calltime;
       $avaya_data['call_datetime'] = date('Y-m-d H:i:s');
       $avaya_data['cl_status'] ='1' ;
      $avaya_data_insert =$avayaClass->insert_avaya_incoming_call($avaya_data);
      
                 // print_r($avaya_data_insert);
      
      
     // $query_insert=mysql_query("insert into `sp_incoming_call` VALUES('".$avaya_data."')");
       //     echo $query_insert;
      // $this->query_insert('sp_incoming_call', $avaya_data);
   }
   else{
   // print_r($avaya_data);
    if($post->CallState == "D"){
        //print_r($avaya_data);
        $updateEvents= "update sp_incoming_call set status='D' , cl_status='2' where calling_phone_no = '".$post->CalledDevice."'";
        //print_r($updateEvents);
        $db->query($updateEvents); 
    }
}
               
       /*     if($post['Param1'] != '' && $post['calltype'] == 'I'){
                $avaya_data['call_audio'] = $post['Param1'];
            }
            
            $avaya_data['dis_conn_massage'] = $post['callstatedesc'];
            
            if($post['callstate'] == "D"){
                $avaya_data['call_disconnect_datetime'] = date('Y-m-d').' '.$post['CallTime'];
            }else if($post['callstate'] == "C"){
                $avaya_data['call_connect_datetime'] = date('Y-m-d').' '.$post['CallTime'];
            }
            
            $avaya_call = $this->call_model->update_avaya_call_by_calluniqueid($avaya_data);   
            $avaya_call = $this->corona_model->update_avaya_call_by_calluniqueid($avaya_data);   */
         
      /*  
            $inc_avaya_data = array('inc_avaya_uniqueid'=>$post['calluniqueid']);
            if($post['Param1'] != '' && $post['CallType'] == 'I'){
                $inc_avaya_data['inc_audio_file']=$post['Param1'];
            }
            $avaya_call = $this->inc_model->update_incident_by_avayaid($inc_avaya_data);
            

        }
        
        echo json_encode(array('status'=>'success','message'=>"Call receive successfully!"));
        die();*/
//echo 'hi';

?>