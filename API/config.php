<?php
    header("Accept: application/json");
    header("Content-Type: application/json; charset=UTF-8");


mysql_connect("localhost","hospital_sp_pune","Spero@Pune@2016") or die(mysql_error("Not Connected"));
mysql_query("use hospital_spero_broadcast_live") or die(mysql_error("Not Connected"));


$GLOBALS['API_SITE_URL'] =  "https://www.hospitalguru.in/API/";
$GLOBALS['WEB_SITE_URL'] =  "https://www.hospitalguru.in/";

$GLOBALS['PROF_PROFILE_PIC'] = "assets/profProfilePic/";
$GLOBALS['PROF_DOCUMENTS'] = "assets/profDocuments/";

$GLOBALS['PROF_PROFILE_PIC_URL'] = $GLOBALS['WEB_SITE_URL'].$GLOBALS['PROF_PROFILE_PIC'];
$GLOBALS['PROF_PROF_DOCUMENTS_URL'] = $GLOBALS['WEB_SITE_URL'].$GLOBALS['PROF_DOCUMENTS'];

 /* function sms_send($args){
       
        $text_msg = $args['msg'];
       // $mobile_no=$args['mob_no'];
        $mobile_no =  "9623499965";
        $form_url = "http://www.mgage.solutions/SendSMS/sendmsg.php?uname=BVGMEMS&pass=m2v5c2&send=speroc&dest=".urlencode($mobile_no)."&msg=".urlencode($text_msg);
        $data_to_post = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $form_url);
        curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
        $result = curl_exec($curl);
        curl_close($curl);
 
}*/





?>