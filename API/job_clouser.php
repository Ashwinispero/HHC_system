<?php

require_once 'classes/eventClass.php';
$eventClass = new eventClass();
require_once ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        date_default_timezone_set("Asia/Calcutta");
        $added_date = date('Y-m-d H:i:s');
        $data = json_decode(file_get_contents('php://input'));
        $Session_id = $data->id;
        $baseLine = $data->baseLine;
        $airWay = $data->airWay;
        $breathing = $data->breathing;
        $circulation = $data->circulation;
        $temperature = $data->temperature;
        $tbsl = $data->tbsl;
        $pulse = $data->pulse;
        $SpO2 = $data->SpO2;
        $RR = $data->RR;
        $GCS = $data->GCS;
        $minBP = $data->minBP;
        $maxBP = $data->maxBP;
        $skinPerfusion = $data->skinPerfusion;
        $notes = $data->notes;
        $professional_vender_id = $_COOKIE['id'];
        $Session_status = 2;

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

                $plan_of_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id'");
                $num_rows = mysql_num_rows($plan_of_care);
                if ($num_rows > 0)
                {
                    $plan_of_care_detailS = mysql_fetch_array($plan_of_care);
                    $event_id = $plan_of_care_detailS['event_id'];
                    $Actual_Service_date = $plan_of_care_detailS['Actual_Service_date'];

                    $event_requirement_id = $plan_of_care_detailS['event_requirement_id'];
                    $plan_of_care_id = $plan_of_care_detailS['plan_of_care_id'];

                    $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  plan_of_care_id='$plan_of_care_id' AND professional_vender_id='$professional_vender_id' AND Session_status!='$Session_status' ");
                    $num_row = mysql_num_rows($plan_of_cares);
                    if ($num_row > 0)
                    {

                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 1,
                                "message" => "All sessions from this subService are not completed yet"
                            )
                        ));

                    }

                    else
                    {

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
                        $Sub_service_id = $sub_service_row['service_id'];
                        $Sub_service_id = (int)$Sub_service_id;
                        $sub_service_title = $sub_service_row['recommomded_service'];
                        $j_status = 1;
                        $args['event_id'] = $event_id;
                        $args['professional_vender_id'] = $professional_vender_id;
                        $args['service_id'] = $service_id;
                        $args['service_date'] = $Actual_Service_date;
                        $args['service_render'] = $j_status;
                        $args['temprature'] = $temperature;
                        $args['bsl'] = $tbsl;
                        $args['pulse'] = $pulse;
                        $args['spo2'] = $SpO2;
                        $args['rr'] = $RR;
                        $args['gcs_total'] = $GCS;
                        $args['high_bp'] = $maxBP;
                        $args['low_bp'] = $minBP;
                        $args['skin_perfusion'] = $skinPerfusion;
                        $args['airway'] = $airWay;
                        $args['breathing'] = $breathing;
                        $args['circulation'] = $circulation;
                        $args['baseline'] = $baseLine;
                        $args['summary_note'] = $notes;

                        $InsertRecord = $eventClass->API_JobClouser($args);

                        //$query=mysql_query("insert into sp_job_closure() VALUES('','$event_id','$professional_vender_id','$service_id','1','$Actual_Service_date','','','$temperature','$tbsl','$pulse','$SpO2','$RR','$GCS','$maxBP','$minBP','$skinPerfusion','$airWay','$breathing','$circulation','$baseLine','$notes','','','','$added_date','','$added_date')");
                        

                        $sqls = mysql_query("UPDATE sp_detailed_event_plan_of_care SET  Session_status=6 WHERE event_requirement_id='$event_requirement_id' AND professional_vender_id='$professional_vender_id' AND Session_status=2 ");

                        if ($sqls)
                        {

                            $update_event = mysql_query("UPDATE sp_events SET  event_status='4' WHERE event_id='$event_id'  ");
                            echo json_encode(array(
                                "data" => null,
                                "error" => null
                            ));
                        }

                    }

                }
                else
                {

                    echo json_encode(array(
                        "data" => null,
                        "error" => array(
                            "code" => 1,
                            "message" => "SubService not found"
                        )
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
error_reporting(E_ALL);
ini_set('error_log', 'on');

?>
