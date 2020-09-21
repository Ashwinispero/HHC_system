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

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
.controls {
  margin-top: 10px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

#pac-input {
  background-color: #fff;
  font-family: Roboto;
  font-size: 15px;
  font-weight: 300;
  margin-left: 12px;
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
  width: 300px;
}

#pac-input:focus {
  border-color: #4d90fe;
}

.pac-container {
  font-family: Roboto;
}

#type-selector {
  color: #fff;
  background-color: #4d90fe;
  padding: 5px 11px 0px 11px;
}

#type-selector label {
  font-family: Roboto;
  font-size: 13px;
  font-weight: 300;
}

    </style>
    <title>Places Searchbox</title>
    <style>
      #target {
        width: 345px;
      }
    </style>
  </head>
  <body>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="mapDiv"></div>
    <script>
// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

 
     
function initAutocomplete() {
  var map = new google.maps.Map(document.getElementById('mapDiv'), {
     // types: ['Pune'],
     // componentRestrictions: {'country': 'ind'}
    //center: {lat: -33.8688, lng: 151.2195},
   // zoom: 13,
   // mapTypeId: google.maps.MapTypeId.ROADMAP
   


  });




     
  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var options = {
  //types: ['(cities)'],
  componentRestrictions: {country: 'in'}
};

  var Autocomplete = new google.maps.places.Autocomplete(input, options);
  //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  /*map.addListener('bounds_changed', function() {
   // searchBox.setBounds(map.getBounds());
  });*/

  //var markers = [];
  // [START region_getplaces]
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  Autocomplete.addListener('places_changed', function() {
    var places = Autocomplete.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    /*markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);*/
  });
  // [END region_getplaces]
}


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZ2VHhjx3nrDeL_28Tzs7heYbzCy0xoNw&libraries=places&callback=initAutocomplete"
         async defer></script>
  </body>
</html>