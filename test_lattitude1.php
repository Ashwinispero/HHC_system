<?php
//phpinfo();
/*
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
curl_close($curl);*/


$region = 'IND';
//$address = '';
$address = str_replace(" ", "+", "Kothrud, Pune, Maharashtra, India");

$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
$json = json_decode($json);

echo $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};echo '  -------';
echo $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
//print_r($json);

echo '<br>';
$region1 = 'IND';
//$address = '';
$address1 = str_replace(" ", "+", "Erandwana Gaothan, Pune, Maharashtra, India");

$jsons = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address1&sensor=false&region=$region1");
$jsons = json_decode($jsons);

echo $lat1 = $jsons->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};echo '  -------';
echo $long2 = $jsons->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};


function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	 
	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);
	 
	  if ($unit == "K") {
	    return ($miles * 1.609344);
	  } else if ($unit == "N") {
	      return ($miles * 0.8684);
	    } else {
	        return $miles;
	      }
	}
echo '<br>';echo '<br>';	 
	echo distance($lat, $long, $lat1, $long2, "M") . " Miles<br>";
	echo distance($lat, $long, $lat1, $long2, "K") . " Kilometers<br>";
        echo distance($lat, $long, $lat1, $long2, "N") . " Nautical Miles<br>";
?>
 ?>
 