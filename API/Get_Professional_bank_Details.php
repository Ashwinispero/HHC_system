<?php
require_once 'classes/professionalsClass.php';
//require_once 'classes/commonClass.php';
$professionalsClass = new professionalsClass();
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{

    if (isset($_COOKIE['id']))

    {

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

                $query2 = mysql_query("SELECT SUM(amount) as amount FROM sp_payments_received_by_professional  where payment_status=1 AND OTP_verifivation=1 AND Payment_type=1 AND Payment_mode=1 AND professional_vender_id=$Professional_id ");
                $pay_arr = mysql_fetch_array($query2);
                $payment_amount = $pay_arr['amount'];
                $payment_amount = (int)$payment_amount;

                $sql_amount_update = mysql_query("UPDATE sp_bank_details SET  Amount_with_me  =  '$payment_amount' WHERE Professional_id ='$Professional_id'");
                $query = mysql_query("SELECT * FROM sp_bank_details WHERE Professional_id='$Professional_id'  ");
                $row_count = mysql_num_rows($query);

                if ($row_count == 0)
                {

                    $arg['Professional_id'] = $Professional_id;
                    $arg['Account_number'] = $accountNumber;
                    $arg['Account_name'] = $accountName;
                    $arg['Bank_name'] = $bank;
                    $arg['Branch'] = $branch;
                    $arg['IFSC_code'] = $ifscCode;
                    $arg['Account_type'] = $accountType;
                    $InsertOtherDtlsRecord = $professionalsClass->API_AddBankDetails($arg);

                }

                $query = mysql_query("SELECT * FROM sp_bank_details WHERE Professional_id='$Professional_id'  ");
                $row_count = mysql_num_rows($query);

                if ($row_count > 0)
                {

                    $row = mysql_fetch_array($query);

                    $accountNumber = $row['Account_number'];
                    $accountName = $row['Account_name'];
                    $bank = $row['Bank_name'];
                    $branch = $row['Branch'];
                    $ifscCode = $row['IFSC_code'];
                    $accountType = $row['Account_type'];
                    $amountWithSpero = $row['Amount_with_spero'];
                    $amountWithSpero = (int)$amountWithSpero;
                    $amountWithMe = $row['Amount_with_me'];
                    $amountWithMe = (int)$amountWithMe;
                    if ($accountNumber == '')
                    {
                        $isEditable = "True";
                    }
                    else
                    {
                        $isEditable = "False";
                    }

                    $querys = mysql_query("SELECT * FROM sp_job_closure WHERE professional_vender_id='$Professional_id'  ");
                    $row_counts = mysql_num_rows($querys);

                    $user = array(
                        'accountNumber' => $accountNumber,
                        'accountName' => $accountName,
                        'bank' => $bank,
                        'branch' => $branch,
                        'ifscCode' => $ifscCode,
                        'accountType' => $accountType,
                        'numberOfServices' => $row_counts,
                        'isEditable' => $isEditable,
                        'amountWithMe' => $amountWithMe

                    );

                    echo json_encode(array(
                        "data" => $user,
                        "error" => null
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
