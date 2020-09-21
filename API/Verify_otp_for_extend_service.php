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
        $extend_service_id = $data->id;
        $OTP = $data->otp;
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
                if ($extend_service_id == '' || $OTP == '')
                {

                    http_response_code(400);

                }

                else
                {

                    $sqls = mysql_query("SELECT * FROM sp_extend_service WHERE  extend_service_id='$extend_service_id' ");
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

                            $sqlqy = mysql_query("UPDATE sp_extend_service SET  OTP = '' , OTP_count= 0 WHERE extend_service_id ='$extend_service_id'");

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

                                $sqlqy = mysql_query("UPDATE sp_extend_service SET  OTP = '' , OTP_count= 0 WHERE extend_service_id ='$extend_service_id'");
                            }

                            else
                            {

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

                                    $sql = mysql_query("UPDATE sp_extend_service SET  OTP  =  '' WHERE extend_service_id='$extend_service_id' ");
                                    if ($sql)
                                    {
                                        $status = 1;
                                        $sqlqy = mysql_query("UPDATE sp_extend_service SET  status = '$status' WHERE extend_service_id='$extend_service_id' ");
                                        if ($sqlqy)
                                        {

                                            $sqlplanofcaredtls = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE  extend_service_id='$extend_service_id' ");
                                            $sqlplanofcaredtlsresult = mysql_fetch_array($sqlplanofcaredtls);
                                            $plan_of_care_id = $sqlplanofcaredtlsresult['plan_of_care_id'];

                                            $sql_session_up = mysql_query("UPDATE sp_detailed_event_plan_of_care SET  status = 1 WHERE extend_service_id ='$extend_service_id'");
                                            $sql_up = mysql_query("UPDATE sp_extend_service SET  OTP  =  '' WHERE extend_service_id='$extend_service_id' ");
                                            $updateplancare = mysql_query("UPDATE sp_event_plan_of_care SET  status  =  '1' WHERE plan_of_care_id='$plan_of_care_id' ");

                                            $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  extend_service_id ='$extend_service_id' ");
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

                                                $sql_pay_up = mysql_query("Update sp_events set finalcost='$Final_cost_event' where event_id='$event_id' ");

                                            }
                                            echo json_encode(array(
                                                "data" => null,
                                                "error" => null
                                            ));

                                        }

                                    }
                                    else
                                    {

                                        $Set_limit = mysql_query("UPDATE sp_extend_service SET OTP_count = OTP_count + 1 WHERE extend_service_id = '$extend_service_id' ");
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

                                    $Set_limit = mysql_query("UPDATE sp_extend_service SET OTP_count = OTP_count + 1 WHERE extend_service_id = '$extend_service_id' ");

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
