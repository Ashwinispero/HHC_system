<?php

require_once 'classes/professionalsClass.php';
//require_once 'classes/commonClass.php';
$professionalsClass = new professionalsClass();
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if (isset($_COOKIE['id']))

    {
        date_default_timezone_set("Asia/Calcutta");

        $id = $_COOKIE['id'];
        $data = json_decode(file_get_contents('php://input'));
        $title = $data->title;
        $firstName = $data->firstName;
        $middleName = $data->middleName;
        $lastName = $data->lastName;
        $qualification = $data->qualification;
        $mobileNumber = $data->mobileNumber;
        $emailId = $data->emailId;
        $designation = $data->designation;
        $assignedServices = $data->assignedServices;

        $subServices = $data->subServices;

        $aboutMe = $data->aboutMe;

        $homeAddress = $data->homeAddress;
        $dateOfBirth = $data->dateOfBirth;

        $workAddress = $data->workAddress;
        $workPhone = $data->workPhone;
        $workEmail = $data->workEmail;
        $specialization = $data->specialization;
        $skills = $data->skills;
        $workExpereince = $data->workExpereince;
        $hospital = $data->hospital;

        $pan = $data->pan;

        $added_date = date('Y-m-d H:i:s');

        $device_id = $_COOKIE['device_id'];

        $status_query = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$id' AND status=2 ");
        $status_query_count = mysql_num_rows($status_query);
        if ($status_query_count > 0)
        {

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

                $query = mysql_query("SELECT * FROM sp_service_professionals WHERE  mobile_no='$mobileNumber' AND service_professional_id !='$id'  ");
                $row_count = mysql_num_rows($query);
                if ($row_count > 0)
                {

                    echo json_encode(array(
                        "data" => null,
                        "error" => array(
                            "message" => "This mobile number is being used by another user."
                        )
                    ));
                }
                else
                {
                    $Sql_email = mysql_query("SELECT * FROM sp_service_professionals WHERE  email_id='$emailId' AND service_professional_id !='$id' ");
                    $email_count = mysql_num_rows($Sql_email);
                    if ($email_count > 0)
                    {
                        echo json_encode(array(
                            "data" => null,
                            "error" => array(
                                "message" => "This email is being used by another user."
                            )
                        ));
                    }

                    else
                    {

                        $service_query = mysql_query("SELECT * FROM sp_professional_services WHERE  service_professional_id ='$id'  ");

                        while ($row = mysql_fetch_array($service_query))
                        {
                            $S_id = $row['professional_service_id'];

                            //$query_delete=mysql_query("DELETE FROM  sp_professional_sub_services  WHERE professional_service_id ='$S_id' ");
                            $querys = mysql_query("DELETE FROM  sp_professional_sub_services  WHERE service_professional_id ='$id' ");
                        }

                        $sql = mysql_query("UPDATE sp_service_professionals SET  title = '$title', name = '$lastName', first_name = '$firstName', middle_name = '$middleName', mobile_no = '$mobileNumber' , email_id = '$emailId', dob = '$dateOfBirth', address = '$homeAddress', work_email_id = '$workEmail', work_address = '$workAddress' , work_phone_no = '$workPhone', Description = '$aboutMe'   WHERE service_professional_id ='$id' ");
                        $sqls = mysql_query("UPDATE sp_service_professional_details SET  qualification = '$qualification', 
					specialization= '$specialization' , skill_set = '$skills', work_experience= '$workExpereince',pancard_no= '$pan',
					designation='$designation',hospital_attached_to='$hospital' WHERE service_professional_id ='$id' ");

                        if ($sqls)
                        {
                            //$query_insert=mysql_query("insert into sp_professional_services() VALUES('','$assignedServices','$valServices','1','$id','','','','1','','$added_date','','$added_date')");
                            // $Last_id=mysql_insert_id();
                            

                            foreach ($subServices as $key => $valServices)
                            {
                                //$arg['sub_service_id']=$valServices;
                                //$InsertOtherDtlsRecord=$professionalsClass->API_updateServices($args);
                                

                                $subs['service_professional_id'] = $id;
                                $subs['service_id'] = $assignedServices;
                                $subs['sub_service_id'] = $valServices;

                                $InsertOtherDtlsRecord = $professionalsClass->API_addsubservices($subs);

                                //$query=mysql_query("insert into sp_professional_sub_services() VALUES('','$id','$assignedServices',$valServices')");
                                
                            }
                        }
                        if ($sql)
                        {
                            if ($sqls)
                            {
                                if ($query)
                                {

                                    echo json_encode(array(
                                        "data" => null,
                                        "error" => null
                                    ));

                                }
                            }
                        }

                        else
                        {
                            http_response_code(400);

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
