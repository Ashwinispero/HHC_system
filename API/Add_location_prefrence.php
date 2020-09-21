<?php
require_once 'classes/locationsClass.php';
$locationsClass = new locationsClass();
include ('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {

        $data = json_decode(file_get_contents('php://input'));
        date_default_timezone_set("Asia/Calcutta");
        $professional_service_id = $_COOKIE['id'];
        $name = $data->name;
        $pois = $data->pois;
        $device_id = $_COOKIE['device_id'];

        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_service_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {
            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$added_date' WHERE service_professional_id='$professional_service_id' AND device_id='$device_id' ");
            http_response_code(401);

        }
        else
        {

            $querys_session = mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_service_id AND device_id=$device_id AND status=2 ");
            $row_count_session = mysql_num_rows($querys_session);
            if ($row_count_session > 0)
            {
                http_response_code(401);

            }
            else
            {
                if ($name == '' || $pois == '')
                {

                    http_response_code(400);

                }

                else
                {
                    $args['professional_service_id'] = $professional_service_id;
                    $args['Name'] = $name;

                    $InsertOtherDtlsRecord = $locationsClass->API_Locations($args);
                    $last_id = mysql_insert_id();

                    //$Loc_query=mysql_query("insert into sp_professional_location() VALUES('','$professional_service_id','$name')");
                    foreach ($pois as $key => $valServices)
                    {
                        $latitude = mysql_real_escape_string($valServices->latitude);
                        $longitude = mysql_real_escape_string($valServices->longitude);

                        $args['lattitude'] = $latitude;
                        $args['longitude'] = $longitude;
                        $args['professional_location_id'] = $last_id;
                        $geolocation = $latitude . "," . $longitude;

                        $request = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&latlng=' . $geolocation . '&sensor=false';
                        $file_contents = file_get_contents($request);
                        $output = json_decode($file_contents);

                        if (!empty($output))
                        {
                            $valResult['location_name'] = $output->results[0]->formatted_address;
                        }
                        $args['location_name'] = $valResult['location_name'];

                        $InsertOtherDtlsRecord = $locationsClass->API_LocationsDetails($args);

                        // $query=mysql_query("insert into sp_professional_location_details() VALUES('','$latitude','$longitude','$last_id')");
                        

                        
                    }

                    $sqls = mysql_query("UPDATE sp_service_professionals SET  location_status = 2 WHERE service_professional_id ='$id'");
                    echo json_encode(array(
                        "data" => array(
                            "id" => $last_id
                        ) ,
                        "error" => null
                    ));

                }

            }
        }
    }
    else
    {
        http_response_code(401);
    }
}
else
{
    http_response_code(405);
}

?>
