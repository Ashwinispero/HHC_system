<?php
require_once 'classes/eventClass.php';
//require_once 'classes/commonClass.php';
$eventClass = new eventClass();
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        date_default_timezone_set('Asia/Kolkata');
        $added_date = date('Y-m-d H:i:s');
        $data = json_decode(file_get_contents('php://input'));
        $Session_id = $data->id;
        $dateTimes = $data->dateTimes;
        $subServiceId = $data->subServices;
        $serviceId = $data->serviceId;
        $professional_vender_id = $_COOKIE['id'];
        //$count==0;
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
                foreach ($dateTimes as $key => $valServices)
                {

                    $fromDate = mysql_real_escape_string($valServices->startDateTime);
                    $toDate = mysql_real_escape_string($valServices->endDateTime);
                    $startTime = mysql_real_escape_string($valServices->startTime);
                    $endTime = mysql_real_escape_string($valServices->endTime);

                }

                $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id'");
                $num_rows = mysql_num_rows($plan_of_cares);
                if ($num_rows > 0)
                {
                    $plan_of_care_detail = mysql_fetch_array($plan_of_cares);

                    $event_id = $plan_of_care_detail['event_id'];
                    $Actual_Service_date = $plan_of_care_detail['Actual_Service_date'];
                    $event_requirement_id = $plan_of_care_detail['event_requirement_id'];
                    $startDateTime = $plan_of_care_detail['start_date'];
                    $endDateTime = $plan_of_care_detail['end_date'];

                    $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                    $sql11 = mysql_fetch_array($sql1);
                    $event_code = $sql11['event_code'];
                    $patient_id = $sql11['patient_id'];
                    $caller_id = $sql11['caller_id'];
                    $relation = $sql11['relation'];
                    $hospital_id = $sql11['hospital_id'];
                    $added_by = $sql11['added_by'];

                    $patient_id = (int)$patient_id;

                    $service_info = mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_id'");
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

                    $cost = $sub_service_row['cost'];

                    $patient_nm = mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'");
                    $patient_nm = mysql_fetch_array($patient_nm);
                    $Patient_name = $patient_nm['name'];
                    $Patient_first_name = $patient_nm['first_name'];
                    $Patient_middle_name = $patient_nm['middle_name'];
                    $mobile_no = $patient_nm['mobile_no'];

                    $date_from = $fromDate;

                    $date_from = strtotime($date_from);
                    $date_to = $toDate;

                    $date_to = strtotime($date_to);
                    for ($i = $date_from;$i <= $date_to;$i += 86400)
                    {

                        $date = date('Y-m-d H:i:s', $i);
                        $combinedDT = date('Y-m-d ', strtotime("$date"));
                        $combinedDTs = date('H:i:s', strtotime("$startTime"));
                        $fromDatest = date('Y-m-d H:i:s', strtotime("$combinedDT $combinedDTs"));
                        $combinedet = date('Y-m-d ', strtotime("$date"));
                        $combinedeTs = date('H:i:s', strtotime("$endTime"));
                        $fromDatet = date('Y-m-d H:i:s', strtotime("$combinedet $combinedeTs"));

                        $plan_of_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where ((start_date <='$fromDatest' AND end_date >='$fromDatest'  AND end_date >='$fromDatet' AND start_date <='$fromDatet' ) OR (Actual_Service_date BETWEEN '$fromDatest' AND '$fromDatet' OR  end_date BETWEEN '$fromDatest' AND '$fromDatet'))  AND  professional_vender_id='$professional_vender_id' AND status=1  ");
                        $num_rows = mysql_num_rows($plan_of_care);

                        if ($num_rows > 0)
                        {
                            $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where (start_date <='$fromDatest' AND end_date >='$fromDatest'  AND end_date >='$fromDatet' AND start_date <='$fromDatet' )   AND  professional_vender_id='$professional_vender_id' AND status=1  ");

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

                        }

                    }

                    $prof_name = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_vender_id' ");
                    $prof_name_array = mysql_fetch_array($prof_name);
                    $name = $prof_name_array['name'];
                    $first_name = $prof_name_array['first_name'];
                    $middle_name = $prof_name_array['middle_name'];

                    $prof_full_name = $first_name . ' ' . $middle_name . ' ' . $name;

                    $GetMaxRecordIdSql = mysql_query("SELECT MAX(event_id) AS MaxId FROM sp_events");
                    $GetMaxRecordIdSql_rows = mysql_num_rows($GetMaxRecordIdSql);

                    $MaxRecord = mysql_fetch_array($GetMaxRecordIdSql);
                    $getMaxRecordId = $MaxRecord['MaxId'];

                    $GetMaxbillIdSql = mysql_query("SELECT MAX(bill_no_ref_no) as bill_no_ref_no FROM sp_events");
                    $row = mysql_fetch_array($GetMaxbillIdSql);
                    $Maxbillid = $row['bill_no_ref_no'];

                    $prefix = 'E';
                    $EventCode = Generate_Number($prefix, $getMaxRecordId);

                    if ($out_session)
                    {

                        echo json_encode($out_session);
                    }
                    else
                    {

                        $date_from = $fromDate;
                        $date_from = strtotime($date_from);
                        $date_to = $toDate;
                        $date_to = strtotime($date_to);

                        $count = 0;
                        for ($i = $date_from;$i <= $date_to;$i += 86400)
                        {
                            $count++;
                        }
                        $final_cost = $cost * $count;

                        for ($i = $date_from;$i <= $date_to;$i += 86400)
                        {

                            $date = date('Y-m-d H:i:s', $i);
                            $timestamp = strtotime($date);
                            $arr['pancard_no'] = $date;
                            $days = date('D', $timestamp);
                            $dayofweek = date('w', strtotime($date));
                            $dayofweek = $dayofweek + 1;

                            $avail_sql = mysql_query("SELECT * FROM sp_professional_avaibility where  professional_service_id='$professional_vender_id' AND day='$dayofweek' ");
                            $avail_sql_rows = mysql_num_rows($avail_sql);

                            if ($avail_sql_rows > 0)
                            {
                                while ($arrs = mysql_fetch_array($avail_sql))
                                {

                                    $professional_avaibility_id = $arrs['professional_avaibility_id'];
                                    $day = $arrs['day'];
                                    $days = $arrs;

                                    $times = mysql_query("SELECT * FROM sp_professional_availability_detail where  (start_time <='$startTime' AND end_time >='$startTime' ) AND (end_time >='$endTime' AND start_time <='$endTime' )   AND professional_availability_id='$professional_avaibility_id'  ");
                                    $times_row_count = mysql_num_rows($times);
                                    if ($times_row_count > 0)
                                    {

                                        //	if ($P_start_time <= $startTime && $P_end_time >= $endTime OR $P_start_time <= $endTime && $P_end_time >= $startTime)
                                        //	{
                                        

                                        $out = (array(
                                            "data" => null,
                                            "error" => null
                                        ));

                                    }
                                    else
                                    {
                                        $query = mysql_query("SELECT * FROM sp_professional_avaibility WHERE professional_service_id = '$professional_vender_id' ");
                                        $row_count = mysql_num_rows($query);
                                        if ($row_count > 0)
                                        {
                                            while ($row = mysql_fetch_array($query))
                                            {
                                                $professional_service_id = $row['professional_service_id'];
                                                $professional_avaibility_id = $row['professional_avaibility_id'];
                                                $day = $row['day'];
                                                $day = (int)$day;
                                                $out_day[] = $day;

                                            }

                                            $datas = $out_day;

                                            $outs = array(
                                                "data" => $datas,
                                                "error" => array(
                                                    "code" => 2,
                                                    "message" => "This extension’s schedule is not matching with your availability Time."
                                                )
                                            );

                                        }

                                    }
                                    unset($out_day);

                                }
                            }
                            else
                            {
                                $query = mysql_query("SELECT * FROM sp_professional_avaibility WHERE professional_service_id = '$professional_vender_id' ");
                                $row_count = mysql_num_rows($query);
                                if ($row_count > 0)
                                {
                                    while ($row = mysql_fetch_array($query))
                                    {
                                        $professional_service_id = $row['professional_service_id'];
                                        $professional_avaibility_id = $row['professional_avaibility_id'];
                                        $day = $row['day'];
                                        $day = (int)$day;

                                        $out_day[] = $day;

                                    }

                                    $datas = $out_day;

                                }
                                $outs = array(
                                    "data" => $datas,
                                    "error" => array(
                                        "code" => 2,
                                        "message" => "This extension’s schedule is not matching with your availability ."
                                    )
                                );

                            }
                            unset($out_day);

                        }
                        if ($outs)
                        {
                            echo json_encode($outs);

                        }
                        elseif ($out)
                        {

                            $service_infos = mysql_query("SELECT * FROM sp_services where service_id='$serviceId'");
                            $service_infos = mysql_fetch_array($service_infos);
                            $service_id = $service_infos['service_id'];
                            $service_id = (int)$service_id;
                            $service_title = $service_infos['service_title'];

                            $otp = rand(1000, 9999);
                            $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                            $txtMsg = '';

                            $txtMsg .= "OTP for new service request for $service_title on $fromDatest to $fromDatet is : $otp. Kindly share OTP with your professional.";

                            $data_to_post = array();
                            $data_to_post['uname'] = 'SperocHL';
                            $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                            $data_to_post['send'] = 'speroc';
                            $data_to_post['dest'] = $mobile_no;
                            $data_to_post['msg'] = $txtMsg;
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $form_url);
                            curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                            $result = curl_exec($curl);
                            curl_close($curl);

                            $arc['event_code'] = $EventCode;
                            $arc['caller_id'] = $caller_id;
                            $arc['relation'] = Professional;
                            $arc['bill_no_ref_no'] = $Maxbillid + 1;
                            $arc['patient_id'] = $patient_id;
                            $arc['purpose_id'] = 1;
                            $arc['event_date'] = $fromDate;
                            $arc['service_date_of_Enquiry'] = $added_date;
                            $arc['enquiry_status'] = 1;
                            $arc['status'] = 2;
                            $arc['finalcost'] = $final_cost;
                            $arc['added_by'] = $added_by;
                            $arc['last_modified_by'] = $professional_vender_id;
                            $arc['last_modified_date'] = $added_date;
                            $arc['branch_code'] = DMH;
                            $arc['hospital_id'] = $hospital_id;
                            $arc['OTP'] = $otp;
                            $arc['added_date'] = $added_date;
                            $arc['note'] = "This event is Added by Professional $prof_full_name";
                            $InsertRecord = $eventClass->API_Add_event($arc);
                            $event_id_new = mysql_insert_id();

                            foreach ($subServiceId as $key => $sub_service_id)
                            {

                                $arr['event_id'] = $event_id_new;
                                $arr['service_id'] = $serviceId;
                                $arr['sub_service_id'] = $sub_service_id;
                                //$arr['added_by']=$professional_vender_id;
                                //	$arr['last_modified_by']=$professional_vender_id;
                                $arr['last_modified_date'] = $added_date;
                                $arr['added_date'] = $added_date;

                                $InsertRecord = $eventClass->API_Add_event_requirements($arr);
                                $event_req_id_new = mysql_insert_id();

                                $arrs['event_id'] = $event_id_new;
                                $arrs['event_requirement_id'] = $event_req_id_new;
                                $arrs['professional_vender_id'] = $professional_vender_id;
                                $arrs['service_date'] = $fromDate;
                                $arrs['service_date_to'] = $toDate;
                                $arrs['start_date'] = $startTime;
                                $arrs['end_date'] = $endTime;
                                $arrs['added_date'] = $added_date;
                                $arrs['added_by'] = $professional_vender_id;
                                $arrs['last_modified_by'] = $professional_vender_id;
                                $arrs['last_modified_date'] = $added_date;

                                $InsertRecord = $eventClass->API_Add_plan_of_care($arrs);
                                $event_plan_of_care_new = mysql_insert_id();

                                $current_time = date('Y-m-d H:i:s');
                                $OTP_timestamp = strtotime($current_time) + 30 * 60;
                                $otp_expiry_time = date('Y-m-d H:i:s', $OTP_timestamp);
                                $sql_otp_update = mysql_query("UPDATE sp_events SET  otp_expire_time='$otp_expiry_time' WHERE event_id='$event_id_new' ");

                                $index_of_Session = 1;

                                for ($i = $date_from;$i <= $date_to;$i += 86400)
                                {

                                    $date = date('Y-m-d H:i:s', $i);
                                    $combinedDT = date('Y-m-d ', strtotime("$date"));
                                    $combinedDTs = date('H:i:s', strtotime("$startTime"));
                                    $fromDatest = date('Y-m-d H:i:s', strtotime("$combinedDT $combinedDTs"));
                                    $combinedet = date('Y-m-d ', strtotime("$date"));
                                    $combinedeTs = date('H:i:s', strtotime("$endTime"));
                                    $fromDatet = date('Y-m-d H:i:s', strtotime("$combinedet $combinedeTs"));
                                    $args['event_id'] = $event_id_new;
                                    $args['event_requirement_id'] = $event_req_id_new;
                                    $args['plan_of_care_id'] = $event_plan_of_care_new;
                                    $args['index_of_Session'] = $index_of_Session;
                                    $args['professional_vender_id'] = $professional_vender_id;
                                    $args['service_date'] = $fromDate;
                                    $args['service_date_to'] = $toDate;
                                    $args['Actual_Service_date'] = $fromDatest;
                                    $args['start_date'] = $fromDatest;
                                    $args['end_date'] = $fromDatet;
                                    $args['added_date'] = $added_date;
                                    $args['last_modified_date'] = $added_date;
                                    $args['status'] = 2;
                                    $args['Session_status'] = 3;

                                    $InsertRecord = $eventClass->API_Extend_services($args);
                                    $index_of_Session++;

                                }

                                $sub_service_info = mysql_query("SELECT * FROM sp_sub_services where sub_service_id='$sub_service_id'");
                                $sub_service_row = mysql_fetch_array($sub_service_info);
                                $sub_service_id = $service_info['sub_service_id'];

                                $cost = $sub_service_row['cost'];
                                $count = 0;

                                for ($i = $date_from;$i <= $date_to;$i += 86400)
                                {
                                    $count++;
                                }
                                $estimate_cost = $cost * $count;

                                $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id_new'");
                                $sql11 = mysql_fetch_array($sql1);
                                $Current_finalcost = $sql11['finalcost'];
                                $sub_service_id = $service_info['sub_service_id'];

                                $Final_cost_event = $Current_finalcost + $estimate_cost;

                                $sql_pay_up = mysql_query("Update sp_events set finalcost=$Final_cost_event where event_id='$event_id_new' ");

                            }
                            echo json_encode(array(
                                "data" => array(
                                    "id" => $event_id_new
                                ) ,
                                "error" => null
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
