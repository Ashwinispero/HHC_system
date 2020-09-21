<?php
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (isset($_COOKIE['id']))
    {
        $data = json_decode(file_get_contents('php://input'));
        $professional_service_id = $_COOKIE['id'];
        $id = $data->id;

        $device_id = $_COOKIE['device_id'];

        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$professional_service_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {
            $added_date = date('Y-m-d H:i:s');
            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$added_date' WHERE service_professional_id='$professional_service_id' AND device_id='$device_id' ");
            http_response_code(401);

        }
        else
        {

            $querys_session = mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_service_id AND device_id=$device_id AND status=2 ");
            $row_count_session = mysql_num_rows($querys_session);
            if ($row_count_session > 0)
            {
                http_response_code(401);

            }
            else
            {
                $Prof_query = mysql_query("SELECT * FROM sp_professional_availability_detail WHERE professional_location_id = '$id' ");
                $row_count = mysql_num_rows($Prof_query);
                if ($row_count > 0)
                {

                    echo json_encode(array(
                        "data" => null,
                        "error" => array(
                            "code" => 2,
                            "message" => "This location preference is attached with one of the working slot"
                        )
                    ));
                }

                else
                {

                    $Prof_query = mysql_query("SELECT * FROM sp_professional_location WHERE professional_service_id = '$professional_service_id' AND Professional_location_id = '$id' ");
                    $row_count = mysql_num_rows($Prof_query);
                    if ($row_count > 0)
                    {
                        $query_delete = mysql_query("DELETE FROM  sp_professional_location  WHERE professional_location_id ='$id' ");

                        $Lc_query = mysql_query("SELECT * FROM sp_professional_location_details WHERE professional_location_id = '$id' ");
                        $row_counts = mysql_num_rows($Lc_query);
                        if ($row_counts > 0)
                        {
                            $query = mysql_query("DELETE FROM  sp_professional_location_details  WHERE professional_location_id ='$id' ");

                            if ($query)
                            {
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
                                    "message" => "Location Preference not found"
                                )
                            ));
                        }
                    }
                    else
                    {
                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 1,
                                "message" => "Location Preference not found"
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
