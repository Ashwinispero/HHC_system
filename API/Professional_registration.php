<?php

require_once 'classes/professionalsClass.php';
//require_once 'classes/commonClass.php';
$professionalsClass = new professionalsClass();
//$commonClass=new commonClass();
//require_once "classes/thumbnail_images.class.php";
//require_once "classes/SimpleImage.php";
//require_once "classes/functions.php";
//require_once "classes/Professional_API.php";
//require_once 'classes/AbstractDB.php';
// header("Accept: application/json");
//header("Content-Type: application/json; charset=UTF-8");
include "classes/commonClass.php";
$commonClass= new commonClass();
include ('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    date_default_timezone_set("Asia/Calcutta");

    $data = json_decode(file_get_contents('php://input'));
    //$service_professional_id=$data->Job_type;
    //$reference_type=$data->Job_type;
    $title = $data->title;
    $Job_type = $data->Job_type;
    $name = $data->lastName;
    $first_name = $data->firstName;
    $middle_name = $data->middleName;

    $email_id = $data->email;
    $phone_no = $data->phone_no;
    $mobile_no = $data->mobileNumber;
    $dob = $data->dob;
    // Home address
    $address = $data->address;
    $work_phone_no = $data->work_phone_no;
    $work_email_id = $data->work_email_id;
    $work_address = $data->work_address;

    //google location id
    $location_id = $data->location_id;
    $location_id_home = $data->location_id_home;
    $service_ids = $data->assignedServices;
    $sub_service_id = $data->subServices;

    $qualification = $data->qualification;
    $specialization = $data->specialization;
    $skill_set = $data->skill_set;
    $work_experience = $data->work_experience;
    $hospital_attached_to = $data->hospital_attached_to;
    $ref1 = $data->ref1;
    $ref2 = $data->ref2;

    $ref2mobileNumber = $data->ref1mobileNumber;
    $ref2mobileNumber = $data->ref2mobileNumber;

    $pancard_no = $data->pancard_no;
    $set_location = $data->set_location;
    $google_home_location = $data->google_home_location;
    $google_work_location = $data->google_work_location;
    $Login_PW = $data->password;
    $document_status = 2;

    if ($title == '' or $name == '' or $first_name == '' or $mobile_no == '' or $email_id == '' or $Login_PW == '')
    {

        http_response_code(400);

    }

    else
    {
        $Email = mysql_query("SELECT * FROM sp_service_professionals WHERE email_id='$email_id'");
        $row_count = mysql_num_rows($Email);
        $mobile_no_query = mysql_query("SELECT * FROM sp_service_professionals WHERE mobile_no='$mobile_no'");
        $mob_row_count = mysql_num_rows($mobile_no_query);
        if ($row_count > 0)
        {

            echo json_encode(array(
                "data" => null,
                "error" => array(
                    "code" => 1,
                    "message" => "Email  Already exist"
                )
            ));
        }
        elseif ($mob_row_count > 0)
        {

            echo json_encode(array(
                "data" => null,
                "error" => array(
                    "code" => 2,
                    "message" => "Mobile Number Already exist"
                )
            ));
        }
        else
        {
            $reference_type = '1';
            $arr = array();
            //$arr['reference_type']=$reference_type;
            $arr['title'] = $title;
            $arr['Job_type'] = $Job_type;
            $arr['name'] = $name;
            $arr['first_name'] = $first_name;
            $arr['middle_name'] = $middle_name;
            $arr['document_status'] = $document_status;
            $arr['email_id'] = $email_id;
            $arr['phone_no'] = $phone_no;
            $arr['mobile_no'] = $mobile_no;
            $arr['dob'] = $dob;
            $arr['address'] = $address;
            $arr['work_phone_no'] = $work_phone_no;
            $arr['work_email_id'] = $work_email_id;
            $arr['work_address'] = $work_address;

            //google id
            $arr['location_id'] = $location_id;
            $arr['location_id_home'] = $location_id_home;
            $arr['service_ids'] = $service_ids;

            // Professional Other Details
            $arr['qualification'] = $qualification;
            $arr['specialization'] = $specialization;
            $arr['skill_set'] = $skill_set;
            $arr['work_experience'] = $work_experience;
            $arr['hospital_attached_to'] = $hospital_attached_to;
            $arr['pancard_no'] = $pancard_no;
            $arr['set_location'] = $set_location;
            $arr['google_home_location'] = $google_home_location;
            $arr['google_work_location'] = $google_work_location;
            $arr['last_modified_by'] = strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date'] = date('Y-m-d H:i:s');
            $arr['Login_PW'] = $Login_PW;

            if (empty($employee_id))
            {
                $arr['status'] = '2';
                $arr['added_by'] = strip_tags($_SESSION['admin_user_id']);
                $arr['added_date'] = date('Y-m-d H:i:s');
            }
            //$InsertRecord=$professionalsClass->AddProfessional($arr);
            $InsertRecord = $professionalsClass->API_AddProfessional($arr);

            if (!empty($InsertRecord))
            {

                $otp = rand(1000, 9999);
                $query = mysql_query("SELECT * FROM sp_service_professionals WHERE mobile_no='$mobile_no'");
                $row_count = mysql_num_rows($query);
                if ($row_count > 0)
                {
                    $row = mysql_fetch_assoc($query);
                    {
                        $professional_id = $row['service_professional_id'];
                        $arg['Professional_id'] = $professional_id;

                        $arg['Account_number'] = $accountNumber;
                        $arg['Account_name'] = $accountName;
                        $arg['Bank_name'] = $bank;
                        $arg['Branch'] = $branch;
                        $arg['IFSC_code'] = $ifscCode;
                        $arg['Account_type'] = $accountType;
                        $InsertOtherDtlsRecord = $professionalsClass->API_AddBankDetails($arg);

                        $service_professional_id = $row['service_professional_id'];
                        $arg['service_professional_id'] = $service_professional_id;
                        $arg['qualification'] = $qualification;
                        $arg['specialization'] = $specialization;
                        $arg['skill_set'] = $skill_set;
                        $arg['work_experience'] = $work_experience;
                        $arg['hospital_attached_to'] = $hospital_attached_to;
                        $arg['reference_1'] = $ref1;
                        $arg['reference_2'] = $ref2;
                        $arg['reference_1_contact_num'] = $ref2mobileNumber;
                        $arg['reference_2_contact_num'] = $ref2mobileNumber;
                        $arg['pancard_no'] = $pancard_no;
                        $arg['last_modified_by'] = strip_tags($_SESSION['admin_user_id']);
                        $arg['last_modified_date'] = date('Y-m-d H:i:s');
                        $arg['status'] = '1';
                        $arg['added_by'] = strip_tags($_SESSION['admin_user_id']);
                        $arg['added_date'] = date('Y-m-d H:i:s');

                        $InsertRecord = $professionalsClass->API_AddProfessionalOtherDtls($arg);

                    }
                    $arg['service_id'] = $service_ids;
                    $arg['service_professional_id'] = $service_professional_id;
                    $InsertRecord = $professionalsClass->API_AssignServices($arg);

                    foreach ($sub_service_id as $key => $valServices)
                    {
                        $service_professional_id = $row['service_professional_id'];
                        $subs['service_professional_id'] = $service_professional_id;
                        $subs['service_id'] = $service_ids;
                        $subs['sub_service_id'] = $valServices;

                        $InsertOtherDtlsRecord = $professionalsClass->API_addsubservices($subs);

                    }

                    
                    $txtMsg = '';

                    $txtMsg .= " Welcome to Spero Home HealthCare ,Your Account Verification OTP is:$otp .OTP is valid for 30 Minutes ";
                    $args = array(
                        'msg' => $txtMsg,
                        'mob_no' => $mobile_no
                        );
                    $sms_data =$commonClass->sms_send($args);
                    /*  $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                    $data_to_post = array();
                    $data_to_post['uname'] = 'SperocHL';
                    $data_to_post['pass'] = 'SpeRo@12'; //s1M$t~I)';
                    $data_to_post['send'] = 'speroc';
                    $data_to_post['dest'] = $mobile_no;
                    $data_to_post['msg'] = $txtMsg;

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $form_url);
                    curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
                    $result = curl_exec($curl);
                    curl_close($curl);
                        */
                    $current_time = date('Y-m-d H:i:s');
                    $OTP_timestamp = strtotime($current_time) + 30 * 60;
                    $otp_expiry_time = date('Y-m-d H:i:s', $OTP_timestamp);

                    $sql_OTP_update = mysql_query("Update sp_service_professionals set OTP='$otp',otp_expire_time='$otp_expiry_time' where mobile_no='" . $mobile_no . "' ");

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
                            "message" => "Invalid Mobile Number"
                        )
                    ));
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
