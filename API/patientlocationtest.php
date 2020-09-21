<?php

	include ('config.php');
  $data = json_decode(file_get_contents('php://input'));
  $pois = $data->P_id;
  
exit();

  foreach ($pois as $key => $patient_id)
  {

	// Get details from 
   $query = mysql_query("SELECT * FROM sp_patients WHERE patient_id=$patient_id  ");
   	$row_count = mysql_num_rows($query);
		
		 $arg = mysql_fetch_array($query);
	
            	$successCount = 0;
				$geolocation=$arg['google_location'];
				$patient_id=$arg['patient_id'];
				
			
	    $address = str_replace(" ", "+",$geolocation);
     
      $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&address=$address&sensor=false&region=$region");
      $json = json_decode($json);

        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
 
	
		$updateSql = mysql_query("UPDATE sp_patients SET  lattitude='$lat',langitude='$long' WHERE patient_id =$patient_id");
    $affectedrows=	mysql_affected_rows();
		if($affectedrows>0)
		{
		$successCount += 1;
		}
      				
 }
	
			
		
		
		echo "Total Number of records : <br>" . count($resultData) . "<br>";
		echo "Updated records : <br>" . $successCount . "<br>";
	?>

