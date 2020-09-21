<?php 
$text_msg = 'ashwini';
$mobile_no =  "9623499965";

$form_url = "http://www.mgage.solutions/SendSMS/sendmsg.php?uname=BVGMEMS&pass=m2v5c2&send=BVGEMS&dest=".urlencode($mobile_no)."&msg=".urlencode($text_msg);
//var_dump($form_url);die();
        $data_to_post = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $form_url);
        curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
        $result = curl_exec($curl);

		curl_close($curl);    

?>