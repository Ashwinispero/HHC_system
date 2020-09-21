<?php

require_once 'classes/locationsClass.php';
$locationsClass = new locationsClass();
include "classes/eventClass.php";
$eventClass = new eventClass();
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        date_default_timezone_set("Asia/Calcutta");
        $data = json_decode(file_get_contents('php://input'));
        $professional_vender_id = $_COOKIE['id'];
        $notification_id = $data->id;
        $status = $data->status;

        $notification_query = mysql_query("SELECT * FROM sp_professional_notification  where  notification_id ='$notification_id' ");
        $notification_num_row = mysql_num_rows($notification_query);
        if ($notification_num_row > 0)
        {
            $notification_array = mysql_fetch_array($notification_query);
            $notification_detail_id = $notification_array['notification_detail_id'];
            $Acknowledged = $session_details_array['Acknowledged'];

            $session_query = mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE event_id = '$notification_detail_id' ");
            $session_details_array = mysql_fetch_array($session_query);
            $session_status = $session_details_array['status'];
            $event_id = $session_details_array['event_id'];
            $event_requirement_id = $session_details_array['event_requirement_id'];
            $plan_of_care_id = $session_details_array['plan_of_care_id'];
            $professional_id = $session_details_array['professional_vender_id'];

            if ($professional_id == $professional_vender_id)
            {
                echo json_encode(array(
                    "data" => null,
                    "error" => array(
                        "code" => 2,
                        "message" => "You have already accepted this Service"
                    )
                ));

            }
            elseif ($session_status == 1)
            {
                echo json_encode(array(
                    "data" => null,
                    "error" => array(
                        "code" => 1,
                        "message" => "This Event is expired"
                    )
                ));

                $status_expire = 3;

                $update_status_expiry = mysql_query("UPDATE sp_professional_notification SET Acknowledged='$status_expire' WHERE  notification_id='$notification_id'  AND type='service'  ");
            }
            else
            {

                if ($status == 1)
                {
                    //$update_status_event=mysql_query("UPDATE sp_events SET status='1' WHERE event_id ='$event_id'  ");
                    

                    $update_status_event_requirement = mysql_query("UPDATE sp_event_requirements SET status='1',professional_vender_id='$professional_vender_id' WHERE event_id ='$notification_detail_id'  ");
                    $update_status_plan_of_care = mysql_query("UPDATE sp_event_plan_of_care SET status='1',professional_vender_id='$professional_vender_id' WHERE event_id ='$notification_detail_id'  ");

                    $update_detail_plan_of_care = mysql_query("UPDATE sp_detailed_event_plan_of_care SET Session_status='3',professional_vender_id = '$professional_vender_id',status='1'  WHERE event_id = '$notification_detail_id' ");
                    $update_status_notification = mysql_query("UPDATE sp_professional_notification SET Acknowledged='$status' WHERE notification_id ='$notification_id'  ");

                    $service_query = mysql_query("SELECT * FROM sp_event_plan_of_care WHERE event_id = '$notification_detail_id' ");
                    while ($service_details_array = mysql_fetch_array($service_query))
                    {
                        $serviceid_query = mysql_query("SELECT * FROM sp_event_requirements WHERE event_id = '$notification_detail_id' ");
                        $serviceid_array = mysql_fetch_array($serviceid_query);

                        $serviceid_detail = mysql_fetch_array($service_query);
                        $service_id = $serviceid_array['service_id'];
                        $sub_service_id = $serviceid_array['sub_service_id'];
                        $event_requirement_id = $service_details_array['event_requirement_id'];
                        $plan_of_care_id = $service_details_array['plan_of_care_id'];

                        $argPass['professional_vender_id'] = $professional_vender_id;
                        $argPass['event_requirement_id'] = $event_requirement_id;
                        $argPass['plan_of_care_id'] = $plan_of_care_id;
                        $argPass['event_id'] = $notification_detail_id;
                        $argPass['added_by'] = $professional_vender_id;
                        $argPass['service_id'] = $service_id;
                        $argPass['status'] = '1';

                        $eventClass->InsertProfessional($argPass);

                    }

                    $status_expire = 3;
                    $status_pending = 0;
                    $update_status_expiry = mysql_query("UPDATE sp_professional_notification SET Acknowledged='$status_expire' WHERE notification_detail_id ='$notification_detail_id' AND notification_id!='$notification_id'  AND type='service' AND Acknowledged='$status_pending' ");

                    echo json_encode(array(
                        "data" => null,
                        "error" => null
                    ));
                }
                elseif ($status == 2)
                {

                    $update_status_notification = mysql_query("UPDATE sp_professional_notification SET Acknowledged='$status' WHERE notification_id ='$notification_id'  ");
                    echo json_encode(array(
                        "data" => null,
                        "error" => null
                    ));
                }
                elseif ($status == 3)
                {

                    $update_status_notification = mysql_query("UPDATE sp_professional_notification SET Acknowledged='$status' WHERE notification_id ='$notification_id'  ");

                    echo json_encode(array(
                        "data" => null,
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
