<?php
require_once ('config.php');

// this goes just before redirect line


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    $data = json_decode(file_get_contents('php://input'));
    $id = $data->id;

    $Professional_id = $_COOKIE['id'];

    $device_id = $_COOKIE['device_id'];

    $added_date = date('Y-m-d H:i:s');
    $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$Professional_id' AND status=2 ");
    $status_query_count = mysql_num_rows($status_query);
    if ($status_query_count > 0)
    {

        $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$added_date' WHERE service_professional_id='$Professional_id' AND device_id='$device_id' ");
        http_response_code(401);

    }
    else
    {

        $querys_session = mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$Professional_id AND device_id=$device_id AND status=2 ");
        $row_count_session = mysql_num_rows($querys_session);
        if ($row_count_session > 0)
        {
            http_response_code(401);

        }
        else
        {

            if ($id == '')
            {

                http_response_code(400);

            }
            if (isset($_COOKIE['id']))

            {

                $query = mysql_query("SELECT * FROM sp_professional_weekoff WHERE  professional_weekoff_id='$id'  ");
                $row_count = mysql_num_rows($query);

                if ($row_count > 0)
                {

                    $status = 5;
                    $sql = mysql_query("UPDATE sp_professional_weekoff SET  Leave_status = '$status' where professional_weekoff_id='$id' AND service_professional_id='$Professional_id' ");
                    $row = mysql_affected_rows();

                    if ($row > 0)
                    {

                        echo json_encode(array(
                            "data" => null,
                            "error" => null
                        ));

                    }
                    else
                    {

                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 1,
                                "message" => "This leave does not belong to you"
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
                            "message" => "Leave not found"
                        )
                    ));
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
    http_response_code(405);

}

?>
