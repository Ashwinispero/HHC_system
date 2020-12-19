<?php 
$text_msg = 'ashwini patil';
$mobile_no =  "8551995260";
//$form_url = "http://chat.chatmybot.in/whatsapp/api/v1/sendmessage?access-token=4197-35YW4IZVOETDQT0MDI&phone=91-".$mobile_no."&content=".$text_msg."&contentType=1&fileName&caption";
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://chat.chatmybot.in/whatsapp/api/v1/sendmessage?access-token=4197-35YW4IZVOETDQT0MDI&phone=91-".$mobile_no."&content=TESTING%20FROM%20TEAM&fileName=test.jpg&caption=testingonol&contentType=1",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
));

$response = curl_exec($curl);
$err = curl_error($curl);
//echo $response;
curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
?>