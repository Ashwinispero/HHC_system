<?php

include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if (isset($_COOKIE['id']))
    {
        $data = json_decode(file_get_contents('php://input'));
        $id = $data->id;
        $device_id = $_COOKIE['device_id'];
        $professional_vender_id = $_COOKIE['id'];

        $added_date = date('Y-m-d H:i:s');
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

                $query = mysql_query("SELECT * FROM sp_patients WHERE patient_id='$id' ");
                $num_rows = mysql_num_rows($query);
                if ($num_rows > 0)
                {
                    $row = mysql_fetch_assoc($query);
                    {

                        $patient_id = $row['patient_id'];
                        $patient_id = (int)$patient_id;
                        $Patient_name = $row['name'];
                        $Patient_first_name = $row['first_name'];
                        $Patient_middle_name = $row['middle_name'];
                        $patient_full_name = $Patient_first_name . ' ' . $Patient_middle_name . ' ' . $Patient_name;
                        $Patient_mobileNumber = $row['mobile_no'];

                        $Patient_Profile_pic = $row['Profile_pic'];
                        $hhc_code = $row['hhc_code'];
                        $Patient_Gender = $row['Gender'];
                        $residential_address = $row['residential_address'];
                        $lattitude = $row['lattitude'];
                        $langitude = $row['langitude'];

                        $Patient_history_Of_illness = $row['history_Of_illness'];
                        $email_id = $row['email_id'];
                        if ($Patient_Gender == 1)
                        {

                            $Gender = "Male";

                        }
                        else
                        {

                            $Gender = "Female";
                        }

                        $sql_query = mysql_query("SELECT * FROM sp_events WHERE patient_id='$patient_id' ");
                        while ($sql_query12 = mysql_fetch_array($sql_query))
                        {
                            $event_id = $sql_query12['event_id'];
                            $sqls_query = mysql_query("SELECT * FROM sp_event_requirements WHERE event_id='$event_id' ");
                            while ($sqls_query1 = mysql_fetch_array($sqls_query))
                            {
                                $service_id = $sqls_query1['service_id'];
                                $P_sub_service_id = $sqls_query1['sub_service_id'];
                                $service_professional_id = $sqls_query1['professional_vender_id'];

                                $squery = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id='$service_professional_id' ");
                                $rowcount = mysql_fetch_array($squery);
                                $service_professional_id = $rowcount['service_professional_id'];

                                $Pro_name = $rowcount['name'];
                                $Pro_first_name = $rowcount['first_name'];
                                $Pro_middle_name = $rowcount['middle_name'];
                                $pro_full_name = $Pro_first_name . ' ' . $Pro_middle_name . ' ' . $Pro_name;

                            }

                        }

                        echo json_encode(array(
                            "data" => array(
                                'id' => $patient_id,
                                'hhcNumber' => $hhc_code,
                                'name' => $patient_full_name,
                                'mobileNumber' => $Patient_mobileNumber,
                                'profilePictureUrl' => $Patient_Profile_pic,
                                'gender' => $Gender,
                                'email' => $email_id,
                                "address" => $residential_address,
                                'location' => array(
                                    'latitude' => $lattitude,
                                    'longitude' => $langitude
                                )
                            ) ,
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
                            "message" => "Patient not found"
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
