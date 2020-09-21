<?php

require_once 'classes/locationsClass.php';
$locationsClass = new locationsClass();
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        date_default_timezone_set("Asia/Calcutta");
        $data = json_decode(file_get_contents('php://input'));
        $professional_vender_id = $_COOKIE['id'];
        $session_id = $data->id;
        $event = $data->sessionStatus;
        $location = $data->location;
        $latitude = $location->latitude;
        $longitude = $location->longitude;
        $current_time = date('Y-m-d H:i:s');
        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_vender_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {

            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$current_time' WHERE service_professional_id='$professional_vender_id' AND device_id='$device_id' ");
            http_response_code(401);

        }
        else
        {

            if ($event == 7)
            {
                $event_status = 1;
            }
            elseif ($event == 8)
            {
                $event_status = 2;
            }
            elseif ($event == 9)
            {
                $event_status = 3;
            }
            $args['Session_id'] = $session_id;
            $args['event_by_professional'] = $event_status;
            $args['professional_id'] = $professional_vender_id;
            $args['latitude'] = $latitude;
            $args['longitude'] = $longitude;

            if ($event == '' || $location == '' || $latitude == '' || $longitude == '')
            {

                http_response_code(400);

            }

            else
            {
                if ($event == 8)
                {

                    $session_query = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE Detailed_plan_of_care_id = '$session_id' AND professional_vender_id = '$professional_vender_id' ");
                    $session_details_array = mysql_fetch_array($session_query);
                    $startDateTime = $session_details_array['start_date'];
                    $endDateTime = $session_details_array['end_date'];

                    $restrication_details = mysql_query("SELECT * FROM sp_restriction_for_session_complete WHERE  status=1 ");
                    $restrication_details_row = mysql_fetch_array($restrication_details);
                    $distance = $restrication_details_row['distance'];
                    $distance = (int)$distance;
                    $duration = $restrication_details_row['duration'];
                    $duration = (int)$duration;
                    $timestamp = strtotime($startDateTime) - $duration * 60;
                    $time = date('Y-m-d H:i:s', $timestamp);
                    $current_time = date('Y-m-d H:i:s');

                    if ($current_time < $time)
                    {
                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 1,
                                "message" => "You cannot start the session at this time. Please contact admin."
                            )
                        ));

                    }
                    else
                    {

                        $Prof_query = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE Detailed_plan_of_care_id = '$session_id' AND professional_vender_id = '$professional_vender_id' ");
                        $row_count = mysql_num_rows($Prof_query);
                        if ($row_count > 0)
                        {

                            $InsertOtherDtlsRecord = $locationsClass->API_LogLocationsDetails($args);

                            echo json_encode(array(
                                "data" => null,
                                "error" => null
                            ));
                            $Loc_query = mysql_query("UPDATE sp_detailed_event_plan_of_care SET Session_status=$event  WHERE Detailed_plan_of_care_id = '$session_id' AND professional_vender_id = '$professional_vender_id' ");

                        }
                        else
                        {
                            http_response_code(401);
                        }

                    }

                }
                elseif ($event == 9)
                {
                    $session_query = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE Detailed_plan_of_care_id = '$session_id' AND professional_vender_id = '$professional_vender_id' ");
                    $session_details_array = mysql_fetch_array($session_query);
                    $startDateTime = $session_details_array['start_date'];
                    $endDateTime = $session_details_array['end_date'];

                    $restrication_details = mysql_query("SELECT * FROM sp_restriction_for_session_complete WHERE  status=1 ");
                    $restrication_details_row = mysql_fetch_array($restrication_details);
                    $distance = $restrication_details_row['distance'];
                    $distance = (int)$distance;
                    $duration = $restrication_details_row['duration'];
                    $duration = (int)$duration;
                    $timestamp = strtotime($endDateTime) + $duration * 60;
                    $time = date('Y-m-d H:i:s', $timestamp);
                    $current_time = date('Y-m-d H:i:s');
                    $time_complete = strtotime($endDateTime) - $duration * 60;
                    $end_time = date('Y-m-d H:i:s', $time_complete);

                    if ($current_time < $startDateTime)
                    {

                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 2,
                                "message" => "You cannot complete the session at this time. Please contact admin."
                            )
                        ));

                    }

                    elseif ($current_time < $end_time)
                    {

                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 2,
                                "message" => "You cannot complete the session at this time. Please contact admin."
                            )
                        ));

                    }
                    elseif ($current_time > $time)
                    {

                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 2,
                                "message" => "You cannot complete the session at this time. Please contact admin."
                            )
                        ));

                    }

                    else
                    {

                        $Prof_query = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE Detailed_plan_of_care_id = '$session_id' AND professional_vender_id = '$professional_vender_id' ");
                        $row_count = mysql_num_rows($Prof_query);
                        if ($row_count > 0)
                        {

                            $InsertOtherDtlsRecord = $locationsClass->API_LogLocationsDetails($args);

                            echo json_encode(array(
                                "data" => null,
                                "error" => null
                            ));
                            $Loc_query = mysql_query("UPDATE sp_detailed_event_plan_of_care SET Session_status=$event  WHERE Detailed_plan_of_care_id = '$session_id' AND professional_vender_id = '$professional_vender_id' ");

                        }
                        else
                        {
                            http_response_code(401);
                        }

                    }
                }
                else
                {

                    $Prof_query = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE Detailed_plan_of_care_id = '$session_id' AND professional_vender_id = '$professional_vender_id' ");
                    $row_count = mysql_num_rows($Prof_query);
                    if ($row_count > 0)
                    {

                        $InsertOtherDtlsRecord = $locationsClass->API_LogLocationsDetails($args);

                        echo json_encode(array(
                            "data" => null,
                            "error" => null
                        ));
                        $Loc_query = mysql_query("UPDATE sp_detailed_event_plan_of_care SET Session_status=$event  WHERE Detailed_plan_of_care_id = '$session_id' AND professional_vender_id = '$professional_vender_id' ");

                    }
                    else
                    {
                        http_response_code(401);
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
