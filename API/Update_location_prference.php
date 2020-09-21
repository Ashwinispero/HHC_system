<?php
require_once 'classes/locationsClass.php';
$locationsClass = new locationsClass();
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        $data = json_decode(file_get_contents('php://input'));
        $professional_service_id = $_COOKIE['id'];
        date_default_timezone_set("Asia/Calcutta");
        $id = $data->id;
        $name = $data->name;
        $pois = $data->pois;
        $professional_vender_id = $_COOKIE['id'];
        $device_id = $_COOKIE['device_id'];
        $added_date = date('Y-m-d H:i:s');
        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_vender_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {
            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$added_date' WHERE service_professional_id='$professional_vender_id' AND device_id='$device_id' ");
            http_response_code(401);

        }
        else
        {

            $querys_session = mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_vender_id AND device_id=$device_id AND status=2 ");
            $row_count_session = mysql_num_rows($querys_session);
            if ($row_count_session > 0)
            {
                http_response_code(401);

            }
            else
            {
                if ($id == '' || $name == '' || $pois == '')
                {

                    http_response_code(400);

                }

                else
                {
                    $Prof_query = mysql_query("SELECT * FROM sp_professional_location WHERE professional_service_id = '$professional_service_id' AND Professional_location_id = '$id' ");
                    $row_count = mysql_num_rows($Prof_query);
                    if ($row_count > 0)
                    {
                        $row = mysql_fetch_array($Prof_query);

                        $Loc_query = mysql_query("UPDATE sp_professional_location SET Name='$name' WHERE professional_service_id = '$professional_service_id' AND Professional_location_id = '$id'");

                        $sqls = mysql_query("SELECT * FROM sp_professional_location WHERE professional_service_id = '$professional_service_id' AND Professional_location_id = '$id' ");
                        $sqls_row = mysql_fetch_array($sqls);

                        $Professional_location_id = $sqls_row['Professional_location_id'];
                        $Professional_location_id = (int)$Professional_location_id;

                        $Lc_query = mysql_query("SELECT * FROM sp_professional_location_details WHERE professional_location_id = '$Professional_location_id' AND Professional_location_id = '$id' ");
                        $row_counts = mysql_num_rows($Lc_query);
                        if ($row_counts > 0)
                        {
                            $querys = mysql_query("DELETE FROM  sp_professional_location_details  WHERE professional_location_id ='$Professional_location_id' AND Professional_location_id = '$id' ");
                            if ($querys)
                            {
                                foreach ($pois as $key => $valServices)
                                {
                                    $latitude = mysql_real_escape_string($valServices->latitude);
                                    $longitude = mysql_real_escape_string($valServices->longitude);
                                    $args['lattitude'] = $latitude;
                                    $args['longitude'] = $longitude;
                                    $args['professional_location_id'] = $id;
                                    $geolocation = $latitude . "," . $longitude;

                                    $request = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC8lSxG4pg8hWyd52oqUQJKWnjQSe20dvc&latlng=' . $geolocation . '&sensor=false';
                                    $file_contents = file_get_contents($request);
                                    $output = json_decode($file_contents);

                                    if (!empty($output))
                                    {
                                        $valResult['location_name'] = $output->results[0]->formatted_address;
                                    }
                                    $args['location_name'] = $valResult['location_name'];

                                    //$query=mysql_query("insert into sp_professional_location_details() VALUES('','$latitude','$longitude','$Professional_location_id')");
                                    $InsertOtherDtlsRecord = $locationsClass->API_LocationsDetails($args);
                                }

                                echo json_encode(array(
                                    "data" => null,
                                    "error" => null
                                ));

                            }
                        }
                        else
                        {
                            echo json_encode(array(
                                "data" => null,
                                "error" => array(
                                    "code" => 1,
                                    "message" => "Location Preference not found"
                                )
                            ));
                        }

                    }
                    else
                    {
                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 1,
                                "message" => "Location Preference not found"
                            )
                        ));
                    }

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
