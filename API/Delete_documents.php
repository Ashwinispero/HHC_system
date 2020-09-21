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

                $Doc_query = mysql_query("SELECT * FROM sp_professional_documents WHERE Documents_id = '$id'  ");
                $row_count = mysql_num_rows($Doc_query);
                if ($row_count > 0)
                {

                    $Prof_query = mysql_query("SELECT * FROM sp_professional_documents WHERE professional_id = '$professional_service_id' AND Documents_id = '$id'  ");
                    $row_count = mysql_num_rows($Prof_query);
                    if ($row_count > 0)
                    {

                        //$query=mysql_query("UPDATE sp_professional_documents SET url_path='',status='2' WHERE Documents_id ='$id' ");
                        $query = mysql_query("DELETE FROM sp_professional_documents  WHERE Documents_id ='$id' ");

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
                                "code" => 2,
                                "message" => "This document does not belong to you"
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
                            "message" => "Document not found"
                        )
                    ));
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
