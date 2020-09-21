<?php
require_once ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        date_default_timezone_set("Asia/Calcutta");
        $data = json_decode(file_get_contents('php://input'));
        $pageIndex = $data->pageIndex;
        $pageSize = $data->pageSize;
        $professional_vender_id = $_COOKIE['id'];
        $device_id = $_COOKIE['device_id'];

        $date = date('d-m-Y H:i:s');
        $new_date = date('Y-m-d H:i:s', strtotime($date));
        $new_date1 = date('Y-m-d H:i:s', strtotime($new_date . ' +1 days'));
        $today_date = date('Y-m-d H:i:s', strtotime($date));
        $Previous_date = date('Y-m-d H:i:s', strtotime($new_date . ' -365 days'));

        $restrication_details = mysql_query("SELECT * FROM sp_restriction_for_session_complete WHERE  status=1 ");
        $restrication_details_row = mysql_fetch_array($restrication_details);
        $distance = $restrication_details_row['distance'];
        $distance = (int)$distance;
        $duration = $restrication_details_row['duration'];
        $duration = (int)$duration;
        $timestamp = strtotime($date) - $duration * 60;
        $time = date('Y-m-d H:i:s', $timestamp);

        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_vender_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {
            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$date' WHERE service_professional_id='$professional_vender_id' AND device_id='$device_id' ");

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

                $plan_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE end_date <'$time' AND professional_vender_id='$professional_vender_id' AND (Session_status =3 OR Session_status =7 OR Session_status =8 OR Session_status =9) ");
                $row = mysql_num_rows($plan_care);
                if ($row > 0)
                {
                    $Loc_query = mysql_query("UPDATE sp_detailed_event_plan_of_care SET Session_status=1  WHERE end_date <'$time' AND professional_vender_id='$professional_vender_id' AND (Session_status =3 OR Session_status =7 OR Session_status =8 OR Session_status =9)  ");

                }
                if ($pageIndex == '' || $pageSize == '')
                {

                    http_response_code(400);

                }

                else

                {
                    $begin = ($pageIndex * $pageSize) - $pageSize;
                    $count = 0;
                    $session_status = 1;

                    //$Loc_query=mysql_query("UPDATE sp_detailed_event_plan_of_care SET Session_status=1 WHERE (Actual_Service_date= '$Previous_date' ) professional_vender_id='$professional_vender_id' AND Session_status =3 ");
                    

                    $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE   professional_vender_id='$professional_vender_id' AND Session_status='$session_status' AND status=1  ");
                    $num_row = mysql_num_rows($plan_of_cares);

                    $plan_of_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE professional_vender_id='$professional_vender_id' AND (Session_status=1 OR Session_status=7 OR Session_status=8 OR Session_status=9) AND status=1 ORDER BY Actual_Service_date DESC LIMIT $begin, $pageSize  ");
                    $num_rows = mysql_num_rows($plan_of_care);
                    $pages = ceil($num_row / $pageSize);
                    if ($num_rows > 0)
                    {
                        $pages = ceil($num_row / $pageSize);
                        while ($plan_of_care_detail = mysql_fetch_array($plan_of_care))
                        {
                            $count++;
                            $Detailed_plan_of_care_id = $plan_of_care_detail['Detailed_plan_of_care_id'];
                            $Detailed_plan_of_care_id = (int)$Detailed_plan_of_care_id;
                            $service_date = $plan_of_care_detail['service_date'];
                            $service_date_to = $plan_of_care_detail['service_date_to'];
                            $startDateTime = $plan_of_care_detail['start_date'];
                            $endDateTime = $plan_of_care_detail['end_date'];
                            $event_id = $plan_of_care_detail['event_id'];
                            $event_requirement_id = $plan_of_care_detail['event_requirement_id'];

                            $event_id = (int)$event_id;

                            $Actual_Service_date = $plan_of_care_detail['Actual_Service_date'];

                            $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                            $sql11 = mysql_fetch_array($sql1);
                            $event_code = $sql11['event_code'];
                            $patient_id = $sql11['patient_id'];
                            $patient_id = (int)$patient_id;

                            $patient_nm = mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'");
                            $patient_nm = mysql_fetch_array($patient_nm);
                            $Patient_name = $patient_nm['name'];
                            $Patient_first_name = $patient_nm['first_name'];
                            $Patient_middle_name = $patient_nm['middle_name'];
                            $patient_full_name = $Patient_first_name . ' ' . $Patient_middle_name . ' ' . $Patient_name;
                            $lattitude = $patient_nm['lattitude'];
                            $langitude = $patient_nm['langitude'];
                            $lattitude = (double)$lattitude;
                            $langitude = (double)$langitude;
                            $service_nm = mysql_query("SELECT * FROM sp_events where patient_id='$patient_id'");
                            $service_nm = mysql_fetch_array($service_nm);
                            $event_ids = $service_nm['event_id'];

                            $service_info = mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'");
                            $service_info = mysql_fetch_array($service_info);
                            $service_id = $service_info['service_id'];
                            $sub_service_id = $service_info['sub_service_id'];
                            $sub_service_id = (int)$sub_service_id;
                            $service_infos = mysql_query("SELECT * FROM sp_services where service_id='$service_id'");
                            $service_infos = mysql_fetch_array($service_infos);
                            $service_id = $service_infos['service_id'];
                            $service_id = (int)$service_id;
                            $service_title = $service_infos['service_title'];

                            $Sub_service_name = mysql_query("SELECT * FROM sp_sub_services WHERE  sub_service_id=$sub_service_id ");
                            $Sub_servicesss = mysql_fetch_array($Sub_service_name);
                            $Sub_service_names = $Sub_servicesss['recommomded_service'];

                            $result[] = (array(
                                'id' => $Detailed_plan_of_care_id,
                                'patient' => array(
                                    'id' => $patient_id,
                                    'name' => $patient_full_name
                                ) ,
                                'startDateTime' => $startDateTime,
                                'endDateTime' => $endDateTime,
                                'service' => array(
                                    'id' => $service_id,
                                    'name' => $service_title
                                ) ,
                                'sub_service' => array(
                                    'id' => $sub_service_id,
                                    'name' => $Sub_service_names
                                ) ,
                                'location' => array(
                                    'latitude' => $lattitude,
                                    'longitude' => $langitude
                                )
                            ));

                        }
                        $data = $result;

                        $out = array(
                            "data" => array(
                                "session" => $data,
                                "pageIndex" => $pageIndex,
                                "totalNumberOfPages" => $pages
                            ) ,
                            "error" => null
                        );
                        echo json_encode($out);
                    }
                    else
                    {
                        $out = array(
                            "data" => array(
                                "session" => [],
                                "pageIndex" => $pageIndex,
                                "totalNumberOfPages" => $pages
                            ) ,
                            "error" => null
                        );
                        echo json_encode($out);

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
