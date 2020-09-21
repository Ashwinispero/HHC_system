<?php
//phpinfo();

// Define URL where the form resides
$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";

// This is the data to POST to the form. The KEY of the array is the name of the field. The value is the value posted.
$data_to_post = array();
$data_to_post['uname'] = 'SperocHL';
$data_to_post['pass'] = 's1M$t~I)';
$data_to_post['send'] = 'speroc';
$data_to_post['dest'] = '8600334476';
$data_to_post['msg'] = 'Dear Punekar nag Patient : Pawar Shital Mob No : 8600334476 Address : Dhanashree Society, Karve Nagar Date1 : 06-08-2015 to 06-08-2015 Reporting time : 12:30 PM to 01:30 PM Msg : amount paid';

// Initialize cURL
$curl = curl_init();

// Set the options
curl_setopt($curl,CURLOPT_URL, $form_url);

// This sets the number of fields to post
curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));

// This is the fields to post in the form of an array.
curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);

//execute the post
$result = curl_exec($curl);

//close the connection
curl_close($curl);
 ?>
 