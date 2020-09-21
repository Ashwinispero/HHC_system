<?php

require_once ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    date_default_timezone_set("Asia/Calcutta");
    $data = json_decode(file_get_contents('php://input'));
    $mobileNumber = $data->mobileNumber;
    $OTP = $data->OTP;
    $device_id = $_COOKIE['device_id'];
    $professional_vender_id = $_COOKIE['id'];

    if ($mobileNumber == '' || $OTP == '')
    {

        http_response_code(400);

    }
    else
    {

        $sql = mysql_query("SELECT * FROM sp_service_professionals WHERE mobile_no = '$mobileNumber'");
        $row_count = mysql_num_rows($sql);
        if ($row_count > 0)
        {
            $result = mysql_fetch_array($sql);
            $realotp = $result['OTP'];
            $mobile_no = $result['mobile_no'];
            $service_professional_id = $result['service_professional_id'];
            $OTP_count = $result['OTP_count'];
            $otp_expire_time = $result['otp_expire_time'];

            $current_time = date('Y-m-d H:i:s');

            if ($current_time >= $otp_expire_time)
            {

                $sqlqy = mysql_query("UPDATE sp_service_professionals SET  OTP = '',OTP_count= 0 WHERE mobile_no ='$mobileNumber'");

                echo json_encode(array(
                    "data" => null,
                    "error" => array(
                        "code" => 2,
                        "message" => "OTP expired"
                    )
                ));

            }

            else
            {

                if ($OTP_count > 2)
                {

                    echo json_encode(array(
                        "data" => null,
                        "error" => array(
                            "code" => 3,
                            "message" => "Too many attempts"
                        )
                    ));

                    $sqlqy = mysql_query("UPDATE sp_service_professionals SET  OTP = '' , OTP_count= 0 WHERE mobile_no ='$mobileNumber'");
                }
                else
                {
                    if ($realotp == '')
                    {

                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 2,
                                "message" => "OTP expired"
                            )
                        ));
                    }

                    else if ($OTP == $realotp)
                    {
                        //Creating an sql query to update the column verified to 1 for the specified user
                        $sql = mysql_query("UPDATE sp_service_professionals SET  OTP  =  '' WHERE mobile_no ='$mobileNumber'");
                        if ($sql)
                        {
                            $status = 2;
                            $sqlqy = mysql_query("UPDATE sp_professional_services SET  status = '1' WHERE service_professional_id = '$service_professional_id'");

                            $sql_service = mysql_query("UPDATE sp_service_professionals SET  OTP_verification = '$status',status='1' WHERE mobile_no ='$mobileNumber'");
                            $cookie_name = "id";
                            $cookie_value = $service_professional_id;
                            setcookie($cookie_name, $cookie_value);

                            echo json_encode(array(
                                "data" => null,
                                "error" => null
                            ));

                        }
                    }

                    else
                    {

                        $Set_limit = mysql_query("UPDATE sp_service_professionals SET OTP_count = OTP_count + 1 WHERE mobile_no = '$mobile_no' ");

                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "code" => 1,
                                "message" => "Invalid OTP"
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
    http_response_code(405);

}

?>
