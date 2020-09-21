<?php
require_once ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if (isset($_COOKIE['id']))
    {
        $data = json_decode(file_get_contents('php://input'));
        $oldPassword = $data->oldPassword;
        $newPassword = $data->newPassword;

        $service_professional_id = $_COOKIE['id'];

        $device_id = $_COOKIE['device_id'];
        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$service_professional_id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {
            $added_date = date('Y-m-d H:i:s');
            $sql_logout = mysql_query("UPDATE  sp_session SET  status = '2',last_modify_date='$added_date' WHERE service_professional_id='$service_professional_id' AND device_id='$device_id' ");
            http_response_code(401);

        }
        else
        {

            $querys_session = mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$service_professional_id AND device_id=$device_id AND status=2 ");
            $row_count_session = mysql_num_rows($querys_session);
            if ($row_count_session > 0)
            {
                http_response_code(401);

            }
            else
            {

                if ($oldPassword == '' || $newPassword == '')
                {

                    http_response_code(400);

                }
                else
                {
                    $sql = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$service_professional_id'");

                    $row_count = mysql_num_rows($sql);
                    if ($row_count > 0)
                    {
                        $rows = mysql_fetch_array($sql);
                        $APP_password = $rows['APP_password'];
                        if ($APP_password == $oldPassword)
                        {
                            $sqls = mysql_query("UPDATE sp_service_professionals SET  APP_password  =  '$newPassword' WHERE service_professional_id ='$service_professional_id'");
                            if ($sqls)
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
                                    "message" => "InCorrect Old Password"
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
        http_response_code(401);

    }
}

else
{
    http_response_code(405);

}

?>
