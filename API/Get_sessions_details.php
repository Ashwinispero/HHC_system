<?php
require_once ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        date_default_timezone_set("Asia/Calcutta");
        $data = json_decode(file_get_contents('php://input'));
        $Session_id = $data->id;
        $current_date = date('Y-m-d H:i:s');
        $professional_vender_id = $_COOKIE['id'];
        $device_id = $_COOKIE['device_id'];

        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_vender_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {
            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$current_date' WHERE service_professional_id='$professional_vender_id' AND device_id='$device_id' ");
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

                if ($Session_id == '')
                {

                    http_response_code(400);

                }

                else

                {
                    $plan_of_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id' ");
                    $num_rows = mysql_num_rows($plan_of_care);
                    if ($num_rows > 0)
                    {
                        while ($plan_of_care_detail = mysql_fetch_array($plan_of_care))
                        {

                            $Detailed_plan_of_care_id = $plan_of_care_detail['Detailed_plan_of_care_id'];
                            $Detailed_plan_of_care_id = (int)$Detailed_plan_of_care_id;
                            $service_date = $plan_of_care_detail['service_date'];
                            $event_requirement_id = $plan_of_care_detail['event_requirement_id'];

                            $startDateTime = $plan_of_care_detail['start_date'];
                            $endDateTime = $plan_of_care_detail['end_date'];
                            $actual_StartDate_Time = $plan_of_care_detail['actual_StartDate_Time'];
                            $actual_EndDate_Time = $plan_of_care_detail['actual_EndDate_Time'];
                            $event_id = $plan_of_care_detail['event_id'];
                            $plan_of_care_id = $plan_of_care_detail['plan_of_care_id'];
                            $event_id = (int)$event_id;
                            $index_of_Session = $plan_of_care_detail['index_of_Session'];
                            $index_of_Session = (int)$index_of_Session;
                            $Session_status = $plan_of_care_detail['Session_status'];
                            $Session_status = (int)$Session_status;
                            $Actual_Service_date = $plan_of_care_detail['Actual_Service_date'];
                            $previous_session_id = $index_of_Session - 1;

                            $event_req_details = mysql_query("SELECT * FROM sp_event_plan_of_care where  event_requirement_id='$event_requirement_id' AND professional_vender_id='$professional_vender_id' AND status=1 ");
                            while ($event_req_details_array = mysql_fetch_array($event_req_details))
                            {

                                $EventendDate = $event_req_details_array['service_date_to'];

                            }

                            $EventendDate = date('Y-m-d H:i:s', strtotime($EventendDate));

                            $new_date = date('Y-m-d H:i:s', strtotime($startDateTime));
                            $new_date1 = date('Y-m-d H:i:s ', strtotime($new_date . ' -1 Hour'));

                            //	$new_date1 = date('Y-m-d H:i:s ', strtotime($new_date . ' -1 days'));
                            

                            $number_of_session = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  event_id='$event_id' AND event_requirement_id='$event_requirement_id' AND professional_vender_id='$professional_vender_id' AND status=1 ");
                            $servicenum_rows = mysql_num_rows($number_of_session);

                            $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                            $sql11 = mysql_fetch_array($sql1);
                            $event_code = $sql11['event_code'];
                            $patient_id = $sql11['patient_id'];
                            $patient_id = (int)$patient_id;
                            $Comments = $sql11['note'];

                            $patient_nm = mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'");
                            $patient_nm = mysql_fetch_array($patient_nm);
                            $Patient_name = $patient_nm['name'];
                            $Patient_first_name = $patient_nm['first_name'];
                            $Patient_middle_name = $patient_nm['middle_name'];
                            $patient_full_name = $Patient_first_name . ' ' . $Patient_middle_name . ' ' . $Patient_name;
                            $lattitude = $patient_nm['lattitude'];
                            $langitude = $patient_nm['langitude'];
                            $mobileNumber = $patient_nm['mobile_no'];
                            $photoUrl = $patient_nm['Profile_pic'];
                            $residential_address = $patient_nm['residential_address'];

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
                            $Payment_type = $sql11['Payment_type'];
                            $Payment_type = (int)$Payment_type;

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

                            $log_location = mysql_query("SELECT * FROM sp_log_location_for_session where  Session_id='$Session_id' AND professional_id='$professional_vender_id'  AND event_by_professional=1 ");
                            $row_log_location = mysql_num_rows($log_location);
                            if ($current_date <= $new_date1)
                            {

                                $isReschedulable = true;
                                $isReschedulable = (bool)$isReschedulable;
                            }
                            else
                            {

                                $isReschedulable = false;
                                $isReschedulable = (bool)$isReschedulable;
                            }

                            $log_locations = mysql_query("SELECT * FROM sp_log_location_for_session where  Session_id='$Session_id' AND professional_id='$professional_vender_id'  AND event_by_professional=2 ");
                            $row_log_locations = mysql_num_rows($log_locations);
                            if ($row_log_locations > 0)
                            {

                                $isReached = true;
                                $isReached = (bool)$isReached;
                            }
                            else
                            {

                                $isReached = false;
                                $isReached = (bool)$isReached;
                            }

                            $payment_info = mysql_query("SELECT SUM(amount) as amount FROM sp_payments where event_id='$event_id'");
                            $payment_row = mysql_fetch_array($payment_info);

                            $amt_paid_spero = $payment_row['amount'];

                            $query_date = mysql_query("SELECT * FROM sp_payments_received_by_professional  where event_requirement_id='$event_requirement_id' AND OTP_verifivation=1 ");
                            while ($date_arr = mysql_fetch_array($query_date))
                            {
                                $date = $date_arr['date_time'];
                            }
                            $query2 = mysql_query("SELECT SUM(amount) as amount FROM sp_payments_received_by_professional  where event_requirement_id='$event_requirement_id' AND OTP_verifivation=1 ");
                            $pay_arr = mysql_fetch_array($query2);
                            $payment_amount = $pay_arr['amount'];
                            $payment_amount = (int)$payment_amount;

                            $session_id_prev = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  index_of_Session='$previous_session_id' AND professional_vender_id='$professional_vender_id' AND event_requirement_id='$event_requirement_id' ");
                            $session_id_pre = mysql_fetch_array($session_id_prev);

                            $pre_Session_status = $session_id_pre['Session_status'];
                            $pre_Session_status = (int)$pre_Session_status;
                            $preDetailed_plan_of_care_id = $session_id_pre['Detailed_plan_of_care_id'];
                            $session_note = $session_id_pre['session_note'];
                            $preDetailed_plan_of_care_id = (int)$preDetailed_plan_of_care_id;
                            $amt = $service_cost - $payment_amount - $amt_paid_spero;
                            if ($previous_session_id == 0)
                            {

                                $preDetailed_plan_of_care_id = null;
                                $pre_Session_status = null;

                            }
                            if ($index_of_Session == 1)
                            {
                                $session_comments = $Comments;
                            }
                            else
                            {
                                $session_comments = $session_note;
                            }

                            if ($amt <= 0)
                            {

                                $Pay_status = true;
                                $Pay_status = (bool)$Pay_status;

                            }
                            else
                            {

                                $Pay_status = false;
                                $Pay_status = (bool)$Pay_status;
                            }

                            $result = (array(
                                'id' => $Detailed_plan_of_care_id,
                                'event' => array(
                                    'id' => $event_id,
                                    'name' => $event_code
                                ) ,
                                'isReschedulable' => $isReschedulable,
                                'status' => $Session_status,
                                'indexForTheSession' => $index_of_Session,
                                'previous_session_id' => $preDetailed_plan_of_care_id,
                                'previous_session_status' => $pre_Session_status,
                                'numberOfSessionsInTheService' => $servicenum_rows,
                                'service' => array(
                                    'id' => $service_id,
                                    'name' => $service_title
                                ) ,
                                'sub_service' => array(
                                    'id' => $sub_service_id,
                                    'name' => $sub_service_title
                                ) ,
                                'patient' => array(
                                    'id' => $patient_id,
                                    'name' => $patient_full_name,
                                    'mobileNumber' => $mobileNumber,
                                    'photoUrl' => $photoUrl,
                                    'DetailAddress' => $residential_address
                                ) ,
                                'startDateTime' => $startDateTime,
                                'endDateTime' => $endDateTime,
                                'actualStartDateTime' => $actual_StartDate_Time,
                                'actualEndDateTime' => $actual_EndDate_Time,
                                'EventendDate' => $EventendDate,
                                'service' => array(
                                    'id' => $service_id,
                                    'name' => $service_title
                                ) ,
                                'location' => array(
                                    'latitude' => $lattitude,
                                    'longitude' => $langitude
                                ) ,
                                'notes' => $session_comments,
                                'payment' => array(
                                    'type' => $Payment_type,
                                    'amount' => $payment_amount,
                                    'pendingAmount' => $amt,
                                    'isPaid' => $Pay_status,
                                    'paidOn' => $date
                                )
                            ));
                            $data = $result;

                        }

                        $out = array(
                            "data" => $data,
                            "error" => null
                        );
                        echo json_encode($out);
                    }
                    else
                    {
                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 1,
                                "message" => "Session not found"
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
