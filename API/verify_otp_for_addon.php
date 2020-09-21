<?php
require_once 'classes/eventClass.php';
//require_once 'classes/commonClass.php';
$eventClass = new eventClass();
require_once ('config.php');

// this goes just before redirect line


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {

        date_default_timezone_set("Asia/Calcutta");
        $data = json_decode(file_get_contents('php://input'));
        $professional_vender_id = $_COOKIE['id'];
        $event_id = $data->id;
        $OTP = $data->otp;
        $added_date = date('Y-m-d H:i:s');
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
                if ($event_id == '' || $OTP == '')
                {

                    http_response_code(400);

                }

                else
                {

                    $sqls = mysql_query("SELECT * FROM sp_events WHERE  event_id='$event_id' ");
                    $row_count = mysql_num_rows($sqls);
                    if ($row_count > 0)
                    {

                        $result = mysql_fetch_array($sqls);
                        $realotp = $result['OTP'];
                        $estimate_cost = $result['estimate_cost'];
                        $date_from = $result['service_date'];
                        $date_to = $result['service_date_to'];
                        $startTime = $result['startTime'];
                        $endTime = $result['endTime'];

                        $OTP_count = $result['OTP_count'];
                        $otp_expire_time = $result['otp_expire_time'];

                        $current_time = date('Y-m-d H:i:s');

                        if ($current_time >= $otp_expire_time)
                        {

                            $sqlqy = mysql_query("UPDATE sp_events SET  OTP = '' , OTP_count= 0 WHERE event_id='$event_id'");

                            echo json_encode(array(
                                "data" => null,
                                "error" => array(
                                    "code" => 2,
                                    "message" => "OTP expired"
                                )
                            ));

                        }

                        else
                        {

                            if ($OTP_count > 2)
                            {
                                $zero = Null;
                                echo json_encode(array(
                                    "data" => null,
                                    "error" => array(
                                        "code" => 3,
                                        "message" => "Too many attempts"
                                    )
                                ));

                                $sqlqy = mysql_query("UPDATE sp_events SET  OTP = '' , OTP_count= 0 WHERE event_id='$event_id'");
                            }

                            if ($realotp == '')
                            {

                                echo json_encode(array(
                                    "data" => null,
                                    "error" => array(
                                        "code" => 2,
                                        "message" => "OTP expired"
                                    )
                                ));
                            }

                            else if ($OTP == $realotp)
                            {

                                $sql = mysql_query("UPDATE sp_events SET  OTP  =  '' WHERE event_id='$event_id' ");
                                if ($sql)
                                {
                                    $status = 3;
                                    $sqlqy = mysql_query("UPDATE sp_events SET  estimate_cost = '$status', event_status='3' WHERE event_id='$event_id' ");
                                    if ($sqlqy)
                                    {

                                        $update_status_event_requirement = mysql_query("UPDATE sp_event_requirements SET status='1',professional_vender_id='$professional_vender_id' WHERE event_id ='$event_id'  ");
                                        $update_status_plan_of_care = mysql_query("UPDATE sp_event_plan_of_care SET status='1',professional_vender_id='$professional_vender_id' WHERE event_id ='$event_id'  ");
                                        $sql_pay_up = mysql_query("Update sp_detailed_event_plan_of_care set status=1 where event_id='$event_id' ");

                                        $service_query = mysql_query("SELECT * FROM sp_event_plan_of_care WHERE event_id = '$event_id' ");
                                        while ($service_details_array = mysql_fetch_array($service_query))
                                        {
                                            $serviceid_query = mysql_query("SELECT * FROM sp_event_requirements WHERE event_id = '$event_id' ");
                                            $serviceid_array = mysql_fetch_array($serviceid_query);

                                            $service_id = $serviceid_array['service_id'];
                                            $sub_service_id = $serviceid_array['sub_service_id'];
                                            $event_requirement_id = $service_details_array['event_requirement_id'];
                                            $plan_of_care_id = $service_details_array['plan_of_care_id'];

                                            $argPass['professional_vender_id'] = $professional_vender_id;
                                            $argPass['event_requirement_id'] = $event_requirement_id;
                                            $argPass['plan_of_care_id'] = $plan_of_care_id;
                                            $argPass['event_id'] = $event_id;
                                            $argPass['added_by'] = $professional_vender_id;
                                            $argPass['service_id'] = $service_id;
                                            $argPass['status'] = '1';

                                            $eventClass->InsertProfessional($argPass);

                                        }

                                        $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  event_id='$event_id'  ");
                                        $num_rows = mysql_num_rows($plan_of_cares);
                                        if ($num_rows > 0)
                                        {
                                            $plan_of_care_detail = mysql_fetch_array($plan_of_cares);

                                            $event_id = $plan_of_care_detail['event_id'];
                                            $event_requirement_id = $plan_of_care_detail['event_requirement_id'];

                                            $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                                            $sql11 = mysql_fetch_array($sql1);
                                            $event_code = $sql11['event_code'];
                                            $patient_id = $sql11['patient_id'];
                                            $Current_finalcost = $sql11['finalcost'];
                                            $patient_id = (int)$patient_id;
                                            $Final_cost_event = $Current_finalcost + $estimate_cost;

                                        }
                                        echo json_encode(array(
                                            "data" => null,
                                            "error" => null
                                        ));

                                    }

                                }
                                else
                                {

                                    $Set_limit = mysql_query("UPDATE sp_events SET OTP_count = OTP_count + 1 WHERE event_id = '$event_id' ");
                                    echo json_encode(array(
                                        "data" => null,
                                        "error" => array(
                                            "code" => 1,
                                            "message" => "Invalid OTP"
                                        )
                                    ));
                                }

                            }
                            else
                            {

                                $Set_limit = mysql_query("UPDATE sp_events SET OTP_count = OTP_count + 1 WHERE event_id = '$event_id' ");
                                echo json_encode(array(
                                    "data" => null,
                                    "error" => array(
                                        "code" => 1,
                                        "message" => "Invalid OTP"
                                    )
                                ));
                            }
                        }
                    }
                    else
                    {
                        http_response_code(401);
                    }
                }
            }
        }
    }
}
else
{
    http_response_code(405);

}

?>
