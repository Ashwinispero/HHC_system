<?php
	require_once 'inc_classes.php';

	
	// Get details from 
	$getLocationsSql = "SELECT professional_location_details_id,
			lattitude,
			longitude,
			professional_location_id
		FROM sp_professional_location_details";
		
	if($db->num_of_rows($db->query($getLocationsSql)))
	{
		$resultData = $db->fetch_all_array($getLocationsSql);
		$successCount = 0;
		foreach ($resultData AS $key => $valResult) {
			$valResult['location_name'] = '';
			//Get location name by lattitude and longitude
			$geolocation = $valResult['lattitude'] . "," . $valResult['longitude'];
			
			$request = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&latlng='.$geolocation.'&sensor=false'; 
			$file_contents = file_get_contents($request);
			$output = json_decode($file_contents);
			
			//echo '<pre>';
			//print_r($output);
			//echo '</pre>';
			//exit;
			
			if (!empty($output)) {
				$valResult['location_name'] = $output->results[0]->formatted_address;
				
				// Update Record details
				$updateSql = "UPDATE sp_professional_location_details SET location_name = '" . $valResult['location_name'] . "' WHERE professional_location_details_id = '" . $valResult['professional_location_details_id'] . "'";
				
				$updateResult = $db->query($updateSql);
				if ($updateResult) {
					$successCount += 1;
				}
			}
			
		}
		
		echo "Total Number of records : <br>" . count($resultData) . "<br>";
		echo "Updated records : <br>" . $successCount . "<br>";
	} else {
		echo "No record found";
	}
?>




<?php
//echo '<pre>';
//print_r($_SERVER);
//echo '</pre>';
 
//echo "https://rest.nexmo.com/sms/json?api_key=bc0bb193&api_secret=59d90c73&from=NEXMO&to=918600334476&text=Welcome+to+Nexmo";

// close curl resource to free up system resources

/*date_default_timezone_set("Asia/Kolkata");
echo date("Y/m/d H:i:s");

$arr1 = array(118,45,32,65,87);
$arr2 = array(25,118,56,387);
$inter = array_intersect($arr1,$arr2);
print_r($inter);

$comma_separated = implode(",", $inter);
//echo $comma_separated;
$inter = array();
$diff = array_merge(array_diff($arr1, $inter), array_diff($inter, $arr1));
print_r($diff);*/
?>

<!--/ ALTER TABLE `sp_feedback_form` CHANGE `option_type` `option_type` ENUM( '1', '2', '3', '4' ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Text,Radio,Checkbox, Rating'; 

ALTER TABLE `sp_callers` ADD `consultant_id` INT NULL DEFAULT NULL AFTER `professional_id` ; 

ALTER TABLE `sp_callers` CHANGE `professional_id` `professional_id` INT( 11 ) NULL DEFAULT NULL COMMENT 'use when job closure',
CHANGE `consultant_id` `consultant_id` INT( 11 ) NULL DEFAULT NULL COMMENT 'use when consultant call';

ALTER TABLE `sp_events` ADD `purpose_event_id` INT NOT NULL COMMENT 'for consultant & follow up call';



ALTER TABLE `sp_event_plan_of_care` CHANGE `service_date` `service_date` DATE NOT NULL ;


ALTER TABLE `sp_patients` ADD `lattitude` VARCHAR( 240 ) NOT NULL ,
ADD `langitude` VARCHAR( 240 ) NOT NULL ;


ALTER TABLE `sp_service_professionals` ADD `lattitude` VARCHAR( 240 ) NOT NULL ,
ADD `langitude` VARCHAR( 240 ) NOT NULL ;
/-->