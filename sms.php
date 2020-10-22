<?php  /* 
$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
$txtMsg = '';
$mobileNumber='8551995260';
$otp='1234';
$patient_name='Ashwini';
$patient_full_name='ashwini';
$patient_mobile_no='8551995260';
$age='28';
$sms_amb='MH12CL2324';
$inc_address='pune';
$ContactNo='8551995260';
$Chief_Complaint='Pain';
$inc_id='1234567';
$amb_url='www.sperohealthcare.in';
//var_dump($otp);die();
//$txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
//$txtMsg .= "Dear $patient_name, the following ambulance is dispatched to your location Ambulance No: $sms_amb,Contact No - $ContactNo,Chief complaint - $Chief_Complaint,Incident id: $inc_id,URL : $amb_url";
$txtMsg .= "Dear Doctor/Nurse, following is the patient details: $patient_full_name, Age - $age ,Address- $inc_address, Contact No - $patient_mobile_no,Chief complaint - $Chief_Complaint, Incident id: $inc_id URL : $amb_url";

$data_to_post = array();
$data_to_post['uname'] = 'SperocHL';
$data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
$data_to_post['send'] = 'speroc';
$data_to_post['dest'] = $mobileNumber;
$data_to_post['msg'] = $txtMsg;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $form_url);
curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
$result = curl_exec($curl);
echo $result;
curl_close($curl);
*/

?>
<?php
	// Account details
	$apiKey = urlencode('DYj0ooG2pfo-150ozYrDn36WfoGBkZOum6v5J76fIk');
	
	// Message details
	$numbers = array(918551995260);
	$sender = urlencode('TXTLCL');
	$message = rawurlencode('This is your message');
 
	$numbers = implode(',', $numbers);
 
	// Prepare data for POST request
	$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
 
	// Send the POST request with cURL
	$ch = curl_init('https://api.textlocal.in/send/');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	var_dump($response);die();
	curl_close($ch);
	
	// Process your response here
	echo $response;
?>