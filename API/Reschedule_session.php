<?php

require_once 'classes/eventClass.php';

$eventClass = new eventClass();

include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {

        date_default_timezone_set("Asia/Calcutta");

        $added_date = date('Y-m-d H:i:s');
        $data = json_decode(file_get_contents('php://input'));
        $Session_id = $data->id;
        $startDateTime = $data->startDateTime;
        $reason = $data->reason;

        $professional_vender_id = $_COOKIE['id'];
        $Session_status = 3;
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

                $plan_of_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id'");
                $num_rows = mysql_num_rows($plan_of_care);
                if ($num_rows > 0)
                {

                    $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id' AND Session_status!=2 ");
                    $num_row = mysql_num_rows($plan_of_cares);
                    if ($num_row > 0)
                    {

                        $plan_of_care_detail = mysql_fetch_array($plan_of_cares);
                        $event_id = $plan_of_care_detail['event_id'];
                        $Actual_Service_date = $plan_of_care_detail['Actual_Service_date'];
                        $event_requirement_id = $plan_of_care_detail['event_requirement_id'];
                        $Reschedule_count = $plan_of_care_detail['Reschedule_count'];
                        $start_date = $plan_of_care_detail['start_date'];
                        $end_date = $plan_of_care_detail['end_date'];

                        $t1 = strtotime($start_date);
                        $t2 = strtotime($end_date);
                        $t1_new = strtotime($startDateTime);
                        $diff = $t2 - $t1;
                        $newend_date = $diff + $t1_new;
                        $new_end_date = date("Y-m-d H:i:s", $newend_date);
                        $start_time = date("H:i:s", strtotime($startDateTime));
                        $end_time = date("H:i:s", strtotime($new_end_date));

                        $args['event_id'] = $event_id;
                        $args['professional_id'] = $professional_vender_id;
                        $args['detail_plan_of_care_id'] = $Session_id;
                        $args['reschedule_start_date'] = $startDateTime;
                        $args['reschedule_end_date'] = $new_end_date;
                        $args['reschedule_start_time'] = $start_time;
                        $args['reschedule_end_time'] = $end_time;
                        $args['added_date'] = $added_date;
                        $args['reschedule_reason'] = $reason;
                        $args['added_user_id'] = $professional_vender_id;
                        $args['session_start_date'] = $start_date;
                        $args['session_end_date'] = $end_date;
                        $InsertOtherDtlsRecord = $eventClass->API_session_reschedule($args);

                        $Reschedule_count_up = $Reschedule_count + 1;

                        $sql = mysql_query("Update sp_detailed_event_plan_of_care set Reschedule_count='$Reschedule_count_up' where Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id'");

                        echo json_encode(array(
                            "data" => null,
                            "error" => null
                        ));

                    }
                    else
                    {

                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 2,
                                "message" => "This session is already completed"
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
                            "message" => "Session Not Found"
                        )
                    ));
                }

            }
        }
    }
    else
    {
        http_response_code(400);

    }
}
else
{
    http_response_code(405);

}
error_reporting(E_ALL);
ini_set('error_log', 'on');

?>
