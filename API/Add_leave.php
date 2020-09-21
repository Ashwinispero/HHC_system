<?php
require_once 'classes/professionalsClass.php';
$professionalsClass = new professionalsClass();
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        date_default_timezone_set('Asia/Kolkata');
        $added_date = date('Y-m-d H:i:s');
        $data = json_decode(file_get_contents('php://input'));

        $fromDate = $data->startDateTime;
        $toDate = $data->endDateTime;
        $reason = $data->reason;
        $professional_vender_id = $_COOKIE['id'];
        $device_id = $_COOKIE['device_id'];

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

                if ($fromDate == '' || $toDate == '')
                {

                    http_response_code(400);

                }

                else
                {

                    $plan_of_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where (Actual_Service_date BETWEEN '$fromDate' AND '$toDate') AND professional_vender_id='$professional_vender_id'  ORDER BY start_date DESC ");
                    $num_rows = mysql_num_rows($plan_of_care);
                    if ($num_rows > 0)
                    {
                        while ($plan_of_care_detail = mysql_fetch_array($plan_of_care))
                        {

                            $Detailed_plan_of_care_id = $plan_of_care_detail['Detailed_plan_of_care_id'];
                            $Detailed_plan_of_care_id = (int)$Detailed_plan_of_care_id;
                            $service_date = $plan_of_care_detail['service_date'];
                            $service_date_to = $plan_of_care_detail['service_date_to'];
                            $startDateTime = $plan_of_care_detail['start_date'];
                            $endDateTime = $plan_of_care_detail['end_date'];
                            $event_id = $plan_of_care_detail['event_id'];
                            $event_id = (int)$event_id;
                            $Actual_Service_date = $plan_of_care_detail['Actual_Service_date'];
                            $Session_status = $plan_of_care_detail['Session_status'];
                            $Session_status = (int)$Session_status;
                            $event_requirement_id = $plan_of_care_detail['event_requirement_id'];

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
                            $service_info = mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'");
                            $service_info = mysql_fetch_array($service_info);
                            $service_id = $service_info['service_id'];
                            $sub_service_id = $service_info['sub_service_id'];

                            $service_infos = mysql_query("SELECT * FROM sp_services where service_id='$service_id'");
                            $service_infos = mysql_fetch_array($service_infos);
                            $service_id = $service_infos['service_id'];
                            $service_id = (int)$service_id;
                            $service_title = $service_infos['service_title'];
                            $sub_service_info = mysql_query("SELECT * FROM sp_sub_services where sub_service_id='$sub_service_id'");
                            $sub_service_row = mysql_fetch_array($sub_service_info);
                            $sub_service_id = $service_info['sub_service_id'];
                            $sub_service_title = $sub_service_row['recommomded_service'];

                            $service_id = (int)$service_id;
                            $sub_service_id = (int)$sub_service_id;

                            $result[] = (array(
                                'id' => $Detailed_plan_of_care_id,
                                'patient' => array(
                                    'id' => $patient_id,
                                    'name' => $patient_full_name
                                ) ,
                                'status' => $Session_status,
                                'startDateTime' => $startDateTime,
                                'endDateTime' => $endDateTime,
                                'service' => array(
                                    'id' => $service_id,
                                    'name' => $service_title
                                ) ,
                                'sub_service' => array(
                                    'id' => $sub_service_id,
                                    'name' => $sub_service_title
                                ) ,
                                'location' => array(
                                    'lattitude' => $lattitude,
                                    'langitude' => $langitude
                                )
                            ));

                            $data = $result;

                        }

                        $out_session = array(
                            "data" => $data,
                            "error" => array(
                                "code" => 1,
                                "message" => "There is conflict with the Sessions"
                            )
                        );

                        $arg['service_professional_id'] = $professional_vender_id;
                        $arg['startDateTime'] = $fromDate;
                        $arg['endDateTime'] = $toDate;
                        $arg['reason'] = $reason;
                        $arg['date'] = $added_date;
                        $arg['Leave_Conflit'] = 1;

                        $InsertOtherDtlsRecord = $professionalsClass->API_AddProfessionalLeaves($arg);
                        $i_id = mysql_insert_id();

                        echo json_encode($out_session);
                    }

                    else
                    {
                        $arg['service_professional_id'] = $professional_vender_id;
                        $arg['startDateTime'] = $fromDate;
                        $arg['endDateTime'] = $toDate;
                        $arg['reason'] = $reason;
                        $arg['date'] = $added_date;
                        $arg['Leave_Conflit'] = 2;

                        $InsertOtherDtlsRecord = $professionalsClass->API_AddProfessionalLeaves($arg);
                        $i_id = mysql_insert_id();

                        echo json_encode(array(
                            "data" => array(
                                "isConflict" => false,
                                "id" => "$i_id"
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
