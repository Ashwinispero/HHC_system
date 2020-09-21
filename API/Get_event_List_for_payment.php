<?php
require_once ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        $data = json_decode(file_get_contents('php://input'));

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

                $payment_sql = mysql_query("SELECT * FROM sp_payments_received_by_professional where   professional_vender_id='$professional_vender_id' AND OTP_verifivation=1 AND Payment_type=1 AND Payment_mode=1 AND payment_status=1  ");

                $payment_sql_rows = mysql_num_rows($payment_sql);
                if ($payment_sql_rows > 0)
                {

                    while ($payment_sql_array = mysql_fetch_array($payment_sql))
                    {

                        $payment_id = $payment_sql_array['payment_id'];
                        $event_id = $payment_sql_array['event_id'];
                        $event_requirement_id = $payment_sql_array['event_requirement_id'];
                        $service_date = $payment_sql_array['service_date'];
                        $payment_amount = $payment_sql_array['amount'];
                        $payment_id = (int)$payment_id;
                        $payment_amount = (int)$payment_amount;

                        /*$query2=mysql_query("SELECT SUM(amount) as amount FROM sp_payments_received_by_professional  where event_requirement_id='$event_requirement_id'");
                        $query2 = mysql_fetch_array($query2);
                        $payment_amount=$query2['amount'];
                        $payment_amount=(int)$payment_amount;*/

                        $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  event_requirement_id='$event_requirement_id' AND professional_vender_id='$professional_vender_id' ");
                        while ($plan_of_care_detail = mysql_fetch_array($plan_of_cares))
                        {
                            $startDateTime = $plan_of_care_detail['start_date'];
                            $endDateTime = $plan_of_care_detail['end_date'];

                            $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                            $sql11 = mysql_fetch_array($sql1);

                            $event_id_main = $sql11['event_id'];
                            $event_code = $sql11['event_code'];
                            $patient_id = $sql11['patient_id'];
                            $patient_id = (int)$patient_id;
                            $patient_nm = mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'");
                            $patient_nm = mysql_fetch_array($patient_nm);
                            $Patient_name = $patient_nm['name'];
                            $Patient_first_name = $patient_nm['first_name'];
                            $Patient_middle_name = $patient_nm['middle_name'];
                            $patient_full_name = $Patient_first_name . ' ' . $Patient_middle_name . ' ' . $Patient_name;

                            $service_info = mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'");
                            $service_info = mysql_fetch_array($service_info);
                            $service_id = $service_info['service_id'];
                            $sub_service_id = $service_info['sub_service_id'];

                            $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                            $sql11 = mysql_fetch_array($sql1);
                            $event_code = $sql11['event_code'];
                            $patient_id = $sql11['patient_id'];
                            $patient_id = (int)$patient_id;
                            $service_cost = $sql11['finalcost'];

                            $service_infos = mysql_query("SELECT * FROM sp_services where service_id='$service_id'");
                            $service_infos = mysql_fetch_array($service_infos);
                            $service_id = $service_infos['service_id'];
                            $service_id = (int)$service_id;
                            $service_title = $service_infos['service_title'];

                            $sub_service_info = mysql_query("SELECT * FROM sp_sub_services where sub_service_id='$sub_service_id'");
                            $sub_service_row = mysql_fetch_array($sub_service_info);
                            $sub_service_id = $service_info['sub_service_id'];
                            $sub_service_id = (int)$sub_service_id;
                            $sub_service_title = $sub_service_row['recommomded_service'];

                        }

                        $result[] = (array(
                            'paymentId' => $payment_id,
                            'service' => $service_title,
                            'subService' => $sub_service_title,
                            'amount' => $payment_amount,
                            'patientName' => $patient_full_name,
                            'startDateTime' => $startDateTime,
                            'endDateTime' => $endDateTime
                        ));

                    }
                    $data = $result;
                    unset($result);

                    $out = array(
                        "data" => $data,
                        "error" => null
                    );
                    echo json_encode($out);

                }
                else
                {
                    $out = array(
                        "data" => [],
                        "error" => null
                    );
                    echo json_encode($out);
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
