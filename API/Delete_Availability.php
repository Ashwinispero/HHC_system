<?php
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        $data = json_decode(file_get_contents('php://input'));

        $id = $_COOKIE['id'];

        $device_id = $_COOKIE['device_id'];
        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {
            $added_date = date('Y-m-d H:i:s');
            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$added_date' WHERE service_professional_id='$id' AND device_id='$device_id' ");
            http_response_code(401);

        }
        else
        {

            $querys_session = mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$id AND device_id=$device_id AND status=2 ");
            $row_count_session = mysql_num_rows($querys_session);
            if ($row_count_session > 0)
            {
                http_response_code(401);

            }
            else
            {

                $Lc_query = mysql_query("SELECT * FROM sp_professional_avaibility WHERE professional_service_id = '$id'   ");
                $row_counts = mysql_num_rows($Lc_query);
                if ($row_counts > 0)
                {

                    foreach ($data as $key => $valServices)
                    {
                        $availableDay = $valServices->availableDay;
                        $Prof_query = mysql_query("SELECT * FROM sp_professional_avaibility WHERE professional_service_id = '$id' AND day='$availableDay' ");
                        $row_count = mysql_num_rows($Prof_query);
                        if ($row_count > 0)
                        {
                            while ($rows = mysql_fetch_array($Prof_query))
                            {

                                $professional_avaibility_id = $rows['professional_avaibility_id'];

                                $professional_service_id = $rows['professional_service_id'];
                                $day = $rows['day'];

                                $workingHours = $valServices->workingHours;
                                foreach ($workingHours as $key => $Hours)
                                {

                                    $startTime = $Hours->startTime;
                                    $endTime = $Hours->endTime;
                                    $selectedLocation = $Hours->selectedLocation;

                                    $Delete_data = mysql_query("DELETE FROM  sp_professional_availability_detail  WHERE professional_availability_id ='$professional_avaibility_id' AND start_time='$startTime' AND end_time='$endTime' AND professional_location_id=$selectedLocation ");

                                }
                            }
                            $Availblity_query = mysql_query("SELECT * FROM sp_professional_availability_detail WHERE professional_availability_id = '$professional_avaibility_id' ");
                            $Availblity_query_count = mysql_num_rows($Availblity_query);
                            if ($Availblity_query_count == 0)
                            {

                                $Av_details = mysql_query("DELETE FROM  sp_professional_avaibility  WHERE  day='$availableDay' AND professional_service_id='$id'");
                            }
                            $query_update = mysql_query("SELECT * FROM sp_professional_avaibility WHERE professional_service_id = '$id' ");
                            $query_update_count = mysql_num_rows($query_update);
                            if ($query_update_count > 0)
                            {
                                $sqls = mysql_query("UPDATE sp_service_professionals SET  availability_status=2, location_status=2 WHERE service_professional_id ='$id'");

                            }
                            else
                            {
                                $sqls = mysql_query("UPDATE sp_service_professionals SET  availability_status=1, location_status=1 WHERE service_professional_id ='$id'");

                            }

                        }
                        $query_update = mysql_query("SELECT * FROM sp_professional_avaibility WHERE professional_service_id = '$id' ");
                        $query_update_count = mysql_num_rows($query_update);
                        if ($query_update_count > 0)
                        {
                            $sqls = mysql_query("UPDATE sp_service_professionals SET  availability_status=2, location_status=2 WHERE service_professional_id ='$id'");

                        }
                        else
                        {
                            $sqls = mysql_query("UPDATE sp_service_professionals SET  availability_status=1, location_status=1 WHERE service_professional_id ='$id'");

                        }
                    }
                    echo json_encode(array(
                        "data" => null,
                        "error" => null
                    ));

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
