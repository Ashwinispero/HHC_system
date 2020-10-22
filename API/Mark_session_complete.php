<?php

require_once 'classes/professionalsClass.php';
include "classes/commonClass.php";
$commonClass= new commonClass();
$professionalsClass = new professionalsClass();

include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        date_default_timezone_set("Asia/Calcutta");

        $added_date = date('Y-m-d H:i:s');
        $data = json_decode(file_get_contents('php://input'));
        $Session_id = $data->id;
        $paymentType = $data->paymentType;
        $amt = $data->amountReceived;

        $paymentMode = $data->paymentMode;
        $note = $data->note;

        $actualStartDateTime = $data->actualStartDateTime;
        $actualEndDateTime = $data->actualEndDateTime;
        $chequeDetails = $data->chequeDetails;
        $chequeNo = $chequeDetails->chequeNo;
        $bank = $chequeDetails->bank;
        $date = $chequeDetails->date;
        $imageId = $chequeDetails->imageId;

        $professional_vender_id = $_COOKIE['id'];
        $Session_status = 3;
        $args['amount'] = $amt;
        $device_id = $_COOKIE['device_id'];

        $current_time = date('Y-m-d H:i:s');
        $OTP_timestamp = strtotime($current_time) + 30 * 60;
        $otp_expiry_time = date('Y-m-d H:i:s', $OTP_timestamp);
        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_vender_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {

            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$current_time' WHERE service_professional_id='$professional_vender_id' AND device_id='$device_id' ");
            http_response_code(401);

        }
        else
        {

            $session_query = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE Detailed_plan_of_care_id = '$Session_id' AND professional_vender_id = '$professional_vender_id' ");
            $session_details_array = mysql_fetch_array($session_query);
            $startDateTime = $session_details_array['start_date'];
            $endDateTime = $session_details_array['end_date'];
            $index_of_Session = $session_details_array['index_of_Session'];
            $plan_of_care_id = $session_details_array['plan_of_care_id'];
            $event_id = $session_details_array['event_id'];
            $event_requirement_id = $session_details_array['event_requirement_id'];

            $number_of_session = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  event_id='$event_id' AND event_requirement_id='$event_requirement_id' AND professional_vender_id='$professional_vender_id' AND status=1 ");
            $servicenum_rows = mysql_num_rows($number_of_session);
            if ($servicenum_rows == $index_of_Session)
            {
                $OTP_verifivation = 2;

            }
            else
            {

                $OTP_verifivation = 1;
            }
            $restrication_details = mysql_query("SELECT * FROM sp_restriction_for_session_complete WHERE  status=1 ");
            $row_count = mysql_num_rows($restrication_details);

            $restrication_details_row = mysql_fetch_array($restrication_details);

            $distance = $restrication_details_row['distance'];
            $distance = (int)$distance;
            $duration = $restrication_details_row['duration'];
            $duration = (int)$duration;

            $timestamp = strtotime($endDateTime) + $duration * 60;
            $time = date('Y-m-d H:i:s', $timestamp);
            
            $current_time = date('Y-m-d H:i:s');
            if ($current_time > $time)
            {
                echo json_encode(array(
                    "data" => null,
                    "error" => array(
                        "code" => 3,
                        "message" => "You cannot complete the session at this time. Please contact admin"
                    )
                ));

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

                    if ($paymentMode == 2)
                    {
                        if ($chequeNo == '' or $bank == '')
                        {
                            http_response_code(400);

                        }

                        else
                        {
                            $plan_of_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id'");
                            $num_rows = mysql_num_rows($plan_of_care);
                            if ($num_rows > 0)
                            {

                                $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id' AND Session_status!='$Session_status'");
                                $num_row = mysql_num_rows($plan_of_cares);
                                if ($num_row > 0)
                                {

                                    $plan_of_care_detail = mysql_fetch_array($plan_of_cares);

                                    $event_id = $plan_of_care_detail['event_id'];

                                    $Actual_Service_date = $plan_of_care_detail['Actual_Service_date'];
                                    $event_requirement_id = $plan_of_care_detail['event_requirement_id'];
                                    $startDateTime = $plan_of_care_detail['start_date'];
                                    $endDateTime = $plan_of_care_detail['end_date'];

                                    $session = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  event_id='$event_id' AND professional_vender_id='$professional_vender_id'");
                                    $session_num_rows = mysql_num_rows($session);

                                    $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                                    $sql11 = mysql_fetch_array($sql1);
                                    $event_code = $sql11['event_code'];
                                    $patient_id = $sql11['patient_id'];
                                    $patient_id = (int)$patient_id;
                                    $service_cost = $sql11['finalcost'];

                                    $patient_nm = mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'");
                                    $patient_nm = mysql_fetch_array($patient_nm);
                                    $mobileNumber = $patient_nm['mobile_no'];

                                    $querys = mysql_query("SELECT * FROM sp_service_professionals WHERE  service_professional_id=$professional_vender_id ");
                                    $Query_row = mysql_fetch_array($querys);
                                    $name = $Query_row['name'];
                                    $first_name = $Query_row['first_name'];
                                    $middle_name = $Query_row['middle_name'];
                                    $prof_mobile_no = $Query_row['mobile_no'];
                                    $Professional_Name = $first_name . ' ' . $middle_name . ' ' . $name;

                                    $service_info = mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'");
                                    $service_info = mysql_fetch_array($service_info);
                                    $service_id = $service_info['service_id'];
                                    $sub_service_id = $service_info['sub_service_id'];

                                    $payment_sql = mysql_query("SELECT * FROM sp_payments_received_by_professional where  Session_id='$Session_id' AND professional_vender_id='$professional_vender_id' ");
                                    $payment_sql_row = mysql_num_rows($payment_sql);
                                    if ($payment_sql_row > 0)
                                    {
                                        if ($servicenum_rows == $index_of_Session)
                                        {
                                            $otp = rand(1000, 9999);
                                            if ($mobileNumber)
                                            {
                                               
                                                $txtMsg = '';
                                                $txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
                                                $args = array(
                                                    'msg' => $txtMsg,
                                                    'mob_no' => $mobileNumber
                                                    );
                                                $sms_data =$commonClass->sms_send($args);
                                                /* $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                                $data_to_post = array();
                                                $data_to_post['uname'] = 'SperocHL';
                                                $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                                                $data_to_post['send'] = 'speroc';
                                                $data_to_post['dest'] = $mobileNumber;
                                                $data_to_post['msg'] = $txtMsg;

                                                $curl = curl_init();
                                                curl_setopt($curl, CURLOPT_URL, $form_url);
                                                curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                                                $result = curl_exec($curl);
                                                curl_close($curl);*/

                                            }
                                            if ($prof_mobile_no)
                                            {
                                                
                                                $txtMsg = '';
                                                $txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
                                                $args = array(
                                                    'msg' => $txtMsg,
                                                    'mob_no' => $prof_mobile_no
                                                    );
                                                $sms_data =$commonClass->sms_send($args);
                                                /*$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                                $data_to_post = array();
                                                $data_to_post['uname'] = 'SperocHL';
                                                $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                                                $data_to_post['send'] = 'speroc';
                                                $data_to_post['dest'] = $prof_mobile_no;
                                                $data_to_post['msg'] = $txtMsg;

                                                $curl = curl_init();
                                                curl_setopt($curl, CURLOPT_URL, $form_url);
                                                curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                                                $result = curl_exec($curl);
                                                curl_close($curl);*/

                                            }

                                            $sql_OTP = mysql_query("Update sp_detailed_event_plan_of_care set OTP='$otp',otp_expire_time='$otp_expiry_time' where Detailed_plan_of_care_id='$Session_id' ");
                                        }
                                        else
                                        {
                                            $sql_complete_session = mysql_query("Update sp_detailed_event_plan_of_care set Session_status=2 where Detailed_plan_of_care_id='$Session_id' ");
                                        }
                                        $sql = mysql_query("Update sp_detailed_event_plan_of_care set amount_received='$amt',session_note='$note' where Detailed_plan_of_care_id='$Session_id' ");

                                        $sql_payment_update = mysql_query("Update sp_payments_received_by_professional set 
				    	cheque_DD__NEFT_no='$chequeNo',
				    	cheque_path_id='$imageId',
				    	party_bank_name='$bank',
				    	amount='$amt',
				    	date_time='$added_date',
				    	Payment_type='$paymentType',
				    	Payment_mode='$paymentMode',
				    	amount='$amt',
				    	OTP_verifivation='$OTP_verifivation',
				    	Comments='$note'
				    
				    	where  Session_id='$Session_id'  ");

                                        echo json_encode(array(
                                            "data" => null,
                                            "error" => null
                                        ));

                                    }
                                    else
                                    {
                                        $args['event_id'] = $event_id;
                                        $args['cheque_DD__NEFT_no'] = $chequeNo;
                                        $args['cheque_path_id'] = $imageId;
                                        $args['party_bank_name'] = $bank;
                                        $args['professional_name'] = $Professional_Name;
                                        $args['amount'] = $amt;
                                        $args['date_time'] = $added_date;
                                        $args['Payment_type'] = $paymentType;
                                        $args['Payment_mode'] = $paymentMode;
                                        $args['Comments'] = $note;
                                        $args['OTP_verifivation'] = $OTP_verifivation;

                                        $args['professional_vender_id'] = $professional_vender_id;
                                        $args['event_requirement_id'] = $event_requirement_id;
                                        $args['Session_id'] = $Session_id;

                                        $InsertRecord = $professionalsClass->API_payments_by_professional($args);

                                        //$query=mysql_query("insert into sp_payments() VALUES('','$event_id','$chequeNo','','$bank','$Professional_Name','','$amt','','','','','$added_date','$note','','','1','$paymentType','$paymentMode')");
                                        $insert_id = mysql_insert_id();

                                        $args['event_id'] = $event_id;
                                        $args['service_id'] = $service_id;
                                        $args['sub_service_id'] = $sub_service_id;
                                        $args['service_date'] = $Actual_Service_date;
                                        $args['actual_service_date'] = $Actual_Service_date;
                                        $args['job_closure_detail'] = $note;
                                        $args['StartTime'] = $actualStartDateTime;
                                        $args['Endtime'] = $actualEndDateTime;
                                        $args['added_by'] = $professional_vender_id;
                                        $args['added_date'] = $added_date;

                                        $InsertOtherDtlsRecord = $professionalsClass->API_jobclosure_detail_datewise($args);

                                        //$query_rem=mysql_query("insert into sp_jobclosure_detail_datewise() VALUES('','$event_id','$service_id','$sub_service_id','$Actual_Service_date','$Actual_Service_date','$note','$startDateTime','$endDateTime','$professional_vender_id','1','$added_date')");
                                        if ($servicenum_rows == $index_of_Session)
                                        {
                                            $otp = rand(1000, 9999);

                                            if ($mobileNumber)
                                            {
                                               // $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                                $txtMsg = '';
                                                $txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
                                                $args = array(
                                                    'msg' => $txtMsg,
                                                    'mob_no' => $mobileNumber
                                                    );
                                                $sms_data =$commonClass->sms_send($args);
                                                /*$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                                $data_to_post = array();
                                                $data_to_post['uname'] = 'SperocHL';
                                                $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                                                $data_to_post['send'] = 'speroc';
                                                $data_to_post['dest'] = $mobileNumber;
                                                $data_to_post['msg'] = $txtMsg;

                                                $curl = curl_init();
                                                curl_setopt($curl, CURLOPT_URL, $form_url);
                                                curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                                                $result = curl_exec($curl);
                                                curl_close($curl);*/

                                            }
                                            if ($prof_mobile_no)
                                            {
                                               // $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                                $txtMsg = '';
                                                $txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
                                                $args = array(
                                                    'msg' => $txtMsg,
                                                    'mob_no' => $prof_mobile_no
                                                    );
                                                $sms_data =$commonClass->sms_send($args);
                                                /*$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                                $data_to_post = array();
                                                $data_to_post['uname'] = 'SperocHL';
                                                $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                                                $data_to_post['send'] = 'speroc';
                                                $data_to_post['dest'] = $prof_mobile_no;
                                                $data_to_post['msg'] = $txtMsg;

                                                $curl = curl_init();
                                                curl_setopt($curl, CURLOPT_URL, $form_url);
                                                curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                                                $result = curl_exec($curl);
                                                curl_close($curl);*/

                                            }
                                            $sql_OTP = mysql_query("Update sp_detailed_event_plan_of_care set OTP='$otp',otp_expire_time='$otp_expiry_time' where Detailed_plan_of_care_id='$Session_id' ");
                                        }
                                        else
                                        {
                                            $sql_complete_session = mysql_query("Update sp_detailed_event_plan_of_care set Session_status=2 where Detailed_plan_of_care_id='$Session_id' ");
                                        }

                                        $sql = mysql_query("Update sp_detailed_event_plan_of_care set session_note='$note'  where Detailed_plan_of_care_id='$Session_id' ");

                                        echo json_encode(array(
                                            "data" => null,
                                            "error" => null
                                        ));

                                    }

                                }

                                else
                                {

                                    echo json_encode(array(
                                        "data" => null,
                                        "error" => array(
                                            "code" => 1,
                                            "message" => "This session is not started yet"
                                        )
                                    ));
                                }
                            }
                            else
                            {

                                echo json_encode(array(
                                    "data" => null,
                                    "error" => array(
                                        "code" => 2,
                                        "message" => "Session Not Found"
                                    )
                                ));
                            }

                        }
                    }
                    else
                    {
                        $plan_of_care = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id'");
                        $num_rows = mysql_num_rows($plan_of_care);
                        if ($num_rows > 0)
                        {

                            $plan_of_cares = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  Detailed_plan_of_care_id='$Session_id' AND professional_vender_id='$professional_vender_id' AND Session_status!='$Session_status'");
                            $num_row = mysql_num_rows($plan_of_cares);
                            if ($num_row > 0)
                            {

                                $plan_of_care_detail = mysql_fetch_array($plan_of_cares);

                                $event_id = $plan_of_care_detail['event_id'];

                                $Actual_Service_date = $plan_of_care_detail['Actual_Service_date'];
                                $event_requirement_id = $plan_of_care_detail['event_requirement_id'];
                                $startDateTime = $plan_of_care_detail['start_date'];
                                $endDateTime = $plan_of_care_detail['end_date'];

                                $session = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care where  event_id='$event_id' AND professional_vender_id='$professional_vender_id'");
                                $session_num_rows = mysql_num_rows($session);

                                $sql1 = mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                                $sql11 = mysql_fetch_array($sql1);
                                $event_code = $sql11['event_code'];
                                $patient_id = $sql11['patient_id'];
                                $patient_id = (int)$patient_id;
                                $service_cost = $sql11['finalcost'];

                                $patient_nm = mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'");
                                $patient_nm = mysql_fetch_array($patient_nm);
                                $mobileNumber = $patient_nm['mobile_no'];

                                $querys = mysql_query("SELECT * FROM sp_service_professionals WHERE  service_professional_id=$professional_vender_id ");
                                $Query_row = mysql_fetch_array($querys);
                                $name = $Query_row['name'];
                                $first_name = $Query_row['first_name'];
                                $middle_name = $Query_row['middle_name'];
                                $prof_mobile_no = $Query_row['mobile_no'];
                                $Professional_Name = $first_name . ' ' . $middle_name . ' ' . $name;

                                $service_info = mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'");
                                $service_info = mysql_fetch_array($service_info);
                                $service_id = $service_info['service_id'];
                                $sub_service_id = $service_info['sub_service_id'];

                                $payment_sql = mysql_query("SELECT * FROM sp_payments_received_by_professional where  Session_id='$Session_id' AND professional_vender_id='$professional_vender_id' ");
                                $payment_sql_row = mysql_num_rows($payment_sql);
                                if ($payment_sql_row > 0)
                                {

                                    if ($servicenum_rows == $index_of_Session)
                                    {
                                        $otp = rand(1000, 9999);
                                        if ($mobileNumber)
                                        {
                                           
                                            $txtMsg = '';
                                            $txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
                                            $args = array(
                                                'msg' => $txtMsg,
                                                'mob_no' => $mobileNumber
                                                );
                                            $sms_data =$commonClass->sms_send($args);
                                            /* $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                            $data_to_post = array();
                                            $data_to_post['uname'] = 'SperocHL';
                                            $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                                            $data_to_post['send'] = 'speroc';
                                            $data_to_post['dest'] = $mobileNumber;
                                            $data_to_post['msg'] = $txtMsg;

                                            $curl = curl_init();
                                            curl_setopt($curl, CURLOPT_URL, $form_url);
                                            curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                                            $result = curl_exec($curl);
                                            curl_close($curl); */

                                        }
                                        if ($prof_mobile_no)
                                        {
                                            //$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                            $txtMsg = '';
                                            $txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
                                            $args = array(
                                                'msg' => $txtMsg,
                                                'mob_no' => $prof_mobile_no
                                                );
                                            $sms_data =$commonClass->sms_send($args);
                                            /* $form_url = "http://api.unicel.in/SendSMS/sendmsg.php"; 
                                                $data_to_post = array();
                                            $data_to_post['uname'] = 'SperocHL';
                                            $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                                            $data_to_post['send'] = 'speroc';
                                            $data_to_post['dest'] = $prof_mobile_no;
                                            $data_to_post['msg'] = $txtMsg;

                                            $curl = curl_init();
                                            curl_setopt($curl, CURLOPT_URL, $form_url);
                                            curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                                            $result = curl_exec($curl);
                                            curl_close($curl); */

                                        }
                                        $sql_OTP = mysql_query("Update sp_detailed_event_plan_of_care set OTP='$otp',otp_expire_time='$otp_expiry_time' where Detailed_plan_of_care_id='$Session_id' ");
                                    }
                                    else
                                    {
                                        $sql_complete_session = mysql_query("Update sp_detailed_event_plan_of_care set Session_status=2 where Detailed_plan_of_care_id='$Session_id' ");
                                    }
                                    $sql = mysql_query("Update sp_detailed_event_plan_of_care set amount_received='$amt',session_note='$note'  where Detailed_plan_of_care_id='$Session_id' ");

                                    $sql_payment_update = mysql_query("Update sp_payments_received_by_professional set cheque_DD__NEFT_no='$chequeNo',
				    	cheque_path_id='$imageId',party_bank_name='$bank',	amount='$amt',	date_time='$added_date',Payment_type='$paymentType',
				    	Payment_mode='$paymentMode',
				    	amount='$amt',OTP_verifivation='$OTP_verifivation',	Comments='$note'
				    	where  Session_id='$Session_id' ");

                                    echo json_encode(array(
                                        "data" => null,
                                        "error" => null
                                    ));

                                }
                                else
                                {

                                    if ($servicenum_rows == $index_of_Session)
                                    {
                                        $otp = rand(1000, 9999);
                                        if ($mobileNumber)
                                        {
                                            
                                            $txtMsg = '';
                                            $txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
                                            $args = array(
                                                'msg' => $txtMsg,
                                                'mob_no' => $mobileNumber
                                                );
                                            $sms_data =$commonClass->sms_send($args);
                                            /* $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                            $data_to_post = array();
                                            $data_to_post['uname'] = 'SperocHL';
                                            $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                                            $data_to_post['send'] = 'speroc';
                                            $data_to_post['dest'] = $mobileNumber;
                                            $data_to_post['msg'] = $txtMsg;

                                            $curl = curl_init();
                                            curl_setopt($curl, CURLOPT_URL, $form_url);
                                            curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                                            $result = curl_exec($curl);
                                            curl_close($curl); */

                                        }
                                        if ($prof_mobile_no)
                                        {
                                            //$form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                            $txtMsg = '';
                                            $txtMsg .= "OTP for completed service is : $otp. Kindly share OTP with your professional.";
                                            $args = array(
                                                'msg' => $txtMsg,
                                                'mob_no' => $prof_mobile_no
                                                );
                                            $sms_data =$commonClass->sms_send($args);
                                            /* $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                                            $data_to_post = array();
                                            $data_to_post['uname'] = 'SperocHL';
                                            $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                                            $data_to_post['send'] = 'speroc';
                                            $data_to_post['dest'] = $prof_mobile_no;
                                            $data_to_post['msg'] = $txtMsg;

                                            $curl = curl_init();
                                            curl_setopt($curl, CURLOPT_URL, $form_url);
                                            curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                                            $result = curl_exec($curl);
                                            curl_close($curl); */

                                        }
                                        $sql_OTP = mysql_query("Update sp_detailed_event_plan_of_care set OTP='$otp',otp_expire_time='$otp_expiry_time' where Detailed_plan_of_care_id='$Session_id' ");

                                    }
                                    else
                                    {
                                        $sql_complete_session = mysql_query("Update sp_detailed_event_plan_of_care set Session_status=2 where Detailed_plan_of_care_id='$Session_id' ");
                                    }
                                    $sql = mysql_query("Update sp_detailed_event_plan_of_care set amount_received='$amt',session_note='$note' where Detailed_plan_of_care_id='$Session_id' ");

                                    $args['event_id'] = $event_id;
                                    $args['professional_vender_id'] = $professional_vender_id;
                                    $args['event_requirement_id'] = $event_requirement_id;
                                    $args['Session_id'] = $Session_id;
                                    $args['cheque_DD__NEFT_no'] = $chequeNo;
                                    $args['party_bank_name'] = $bank;
                                    $args['Comments'] = $note;
                                    $args['professional_name'] = $Professional_Name;
                                    $args['amount'] = $amt;
                                    $args['date_time'] = $added_date;
                                    $args['Payment_type'] = $paymentType;
                                    $args['Payment_mode'] = $paymentMode;
                                    $args['OTP_verifivation'] = $OTP_verifivation;

                                    $InsertRecord = $professionalsClass->API_payments_by_professional($args);

                                    //$query=mysql_query("insert into sp_payments() VALUES('','$event_id','$chequeNo','','$bank','$Professional_Name','','$amt','','','','','$added_date','$note','','','1','$paymentType','$paymentMode')");
                                    $insert_id = mysql_insert_id();

                                    $args['event_id'] = $event_id;
                                    $args['service_id'] = $service_id;
                                    $args['sub_service_id'] = $sub_service_id;
                                    $args['service_date'] = $Actual_Service_date;
                                    $args['actual_service_date'] = $Actual_Service_date;
                                    $args['job_closure_detail'] = $note;
                                    $args['StartTime'] = $actualStartDateTime;
                                    $args['Endtime'] = $actualEndDateTime;
                                    $args['added_by'] = $professional_vender_id;
                                    $args['added_date'] = $added_date;

                                    $InsertOtherDtlsRecord = $professionalsClass->API_jobclosure_detail_datewise($args);

                                    //$query_rem=mysql_query("insert into sp_jobclosure_detail_datewise() VALUES('','$event_id','$service_id','$sub_service_id','$Actual_Service_date','$Actual_Service_date','$note','$startDateTime','$endDateTime','$professional_vender_id','1','$added_date')");
                                    $sql = mysql_query("Update sp_detailed_event_plan_of_care set amount_received='$amt',session_note='$note'  where Detailed_plan_of_care_id='$Session_id' ");

                                    echo json_encode(array(
                                        "data" => null,
                                        "error" => null
                                    ));

                                }

                            }
                            else
                            {

                                echo json_encode(array(
                                    "data" => null,
                                    "error" => array(
                                        "code" => 1,
                                        "message" => "This session is not started yet"
                                    )
                                ));
                            }
                        }
                        else
                        {

                            echo json_encode(array(
                                "data" => null,
                                "error" => array(
                                    "code" => 2,
                                    "message" => "Session Not Found"
                                )
                            ));
                        }
                    }

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
