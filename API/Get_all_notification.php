<?php
require_once ('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        $data = json_decode(file_get_contents('php://input'));
        $pageIndex = $data->pageIndex;
        $pageSize = $data->pageSize;
        $professional_vender_id = $_COOKIE['id'];
        date_default_timezone_set('Asia/Kolkata');
        $date = date('Y-m-d H:i:s');

        $device_id = $_COOKIE['device_id'];

        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_vender_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {

            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$date' WHERE service_professional_id='$professional_vender_id' AND device_id='$device_id' ");
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

                if ($pageIndex == '' || $pageSize == '')
                {

                    http_response_code(400);

                }

                else

                {
                    $begin = ($pageIndex * $pageSize) - $pageSize;

                    $plan_of_cares = mysql_query("SELECT * FROM sp_professional_notification  where  professional_id ='$professional_vender_id' ");
                    $num_row = mysql_num_rows($plan_of_cares);

                    $plan_of_care = mysql_query("SELECT * FROM sp_professional_notification  where  professional_id ='$professional_vender_id' ORDER BY added_date DESC  LIMIT $begin, $pageSize   ");
                    $num_rows = mysql_num_rows($plan_of_care);
                    $pages = ceil($num_row / $pageSize);
                    if ($num_rows > 0)
                    {

                        while ($plan_of_care_detail = mysql_fetch_array($plan_of_care))
                        {

                            $notification_id = $plan_of_care_detail['notification_id'];
                            $professional_id = $plan_of_care_detail['professional_id'];
                            $type = $plan_of_care_detail['type'];
                            $title = $plan_of_care_detail['title'];
                            $notification_detail_id = $plan_of_care_detail['notification_detail_id'];
                            $message = $plan_of_care_detail['message'];
                            $professional_id = (int)$professional_id;
                            $notification_detail_id = (int)$notification_detail_id;
                            $notification_id = (int)$notification_id;
                            $added_date = $plan_of_care_detail['added_date'];
                            $Session_statuss = $plan_of_care_detail['Session_status'];
                            $Acknowledged_status = $plan_of_care_detail['Acknowledged'];
                            $Acknowledged_status = (int)$Acknowledged_status;

                            $result[] = array(
                                'id' => $notification_id,
                                'receivedTime' => $added_date,
                                'type' => $type,
                                'title' => $title,
                                'content' => $message,
                                'status' => $Acknowledged_status
                            );
                            $data = $result;

                        }

                        $out = array(
                            "data" => array(
                                "notifications" => $data,
                                "pageIndex" => $pageIndex,
                                "totalNumberOfPages" => $pages
                            ) ,
                            "error" => null
                        );
                        echo json_encode($out);
                    }
                    else
                    {

                        $out = array(
                            "data" => array(
                                "notifications" => [],
                                "pageIndex" => $pageIndex,
                                "totalNumberOfPages" => $pages
                            ) ,
                            "error" => null
                        );
                        echo json_encode($out);

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
