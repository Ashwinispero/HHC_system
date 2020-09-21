<?php

include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if (isset($_COOKIE['id']))
    {
        $id = $_COOKIE['id'];
        $device_id = $_COOKIE['device_id'];

        $added_date = date('Y-m-d H:i:s');
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

                $query = mysql_query("SELECT * FROM sp_service_professionals WHERE  service_professional_id=$id ");
                $row_count = mysql_num_rows($query);

                if ($row_count > 0)
                {

                    $Query_row = mysql_fetch_assoc($query);
                    {
                        $service_professional_id = $Query_row['service_professional_id'];
                        $name = $Query_row['name'];

                        $first_name = $Query_row['first_name'];
                        $middle_name = $Query_row['middle_name'];
                        $email_id = $Query_row['email_id'];
                        $mobile_no = $Query_row['mobile_no'];
                        $title = $Query_row['title'];

                        $dob = $Query_row['dob'];
                        $address = $Query_row['address'];
                        $work_address = $Query_row['work_address'];
                        $work_phone_no = $Query_row['work_phone_no'];
                        $work_email_id = $Query_row['work_email_id'];
                        $dob = $Query_row['dob'];
                        $Profile_pic = $Query_row['Profile_pic'];
                        $Ratings = $Query_row['Ratings'];
                        $Reviews = $Query_row['Reviews'];
                        $Description = $Query_row['Description'];
                        $Ratings = (int)$Ratings;
                        $Reviews = (int)$Reviews;
                        $service_professional_id = (int)$service_professional_id;
                        $Profile_pic_url = $PROF_PROFILE_PIC_URL . $Profile_pic;

                        $sql = mysql_query("SELECT * FROM sp_service_professional_details WHERE  service_professional_id=$id ");
                        $rows = mysql_fetch_array($sql);
                        $qualification = $rows['qualification'];
                        $specialization = $rows['specialization'];
                        $skill_set = $rows['skill_set'];
                        $work_experience = $rows['work_experience'];
                        $work_experience = (float)$work_experience;
                        $hospital_attached_to = $rows['hospital_attached_to'];
                        $pancard_no = $rows['pancard_no'];
                        $Designation = $rows['designation'];

                        $sqls = mysql_query("SELECT * FROM sp_professional_services WHERE service_professional_id = '$service_professional_id'");

                        $row = mysql_fetch_array($sqls);

                        $service_id = $row['service_id'];
                        $professional_service_id = $row['professional_service_id'];

                        $service_id = (int)$service_id;

                        $sql_query = mysql_query("SELECT * FROM sp_services WHERE service_id = '$service_id'");

                        $rowSS = mysql_fetch_array($sql_query);
                        $service_title = $rowSS['service_title'];

                        $types_prof = array(
                            'id' => $service_id,
                            'name' => $service_title
                        );

                        $p_data = $types_prof;

                        $Sub_service = mysql_query("SELECT * FROM sp_professional_sub_services WHERE  service_professional_id=$service_professional_id ");
                        $Sub_service_row_count = mysql_num_rows($Sub_service);
                        if ($Sub_service_row_count > 0)
                        {
                            while ($Sub_services = mysql_fetch_array($Sub_service))
                            {
                                $Sub_service_id = $Sub_services['sub_service_id'];
                                $Sub_service_id = (int)$Sub_service_id;

                                $Sub_service_name = mysql_query("SELECT * FROM sp_sub_services WHERE  sub_service_id=$Sub_service_id ");
                                $Sub_servicesss = mysql_fetch_array($Sub_service_name);
                                $Sub_service_names = $Sub_servicesss['recommomded_service'];

                                $types_profs[] = array(
                                    'id' => $Sub_service_id,
                                    'name' => $Sub_service_names
                                );

                            }
                            $sub_data = $types_profs;
                        }
                        else
                        {
                            $types_profs = [];
                        }

                    }
                    $user = array(
                        'id' => $service_professional_id,
                        'title' => $title,
                        'lastName' => $name,
                        'firstName' => $first_name,
                        'middleName' => $middle_name,
                        'assignedServices' => $types_prof,
                        'subServices' => $types_profs,
                        'email' => $email_id,
                        'mobileNumber' => $mobile_no,
                        'Date_of_birth' => $dob,

                        'homeAddress' => $address,
                        'workAddress' => $work_address,
                        'workPhone' => $work_phone_no,
                        'workEmail' => $work_email_id,

                        'qualification' => $qualification,
                        'specialization' => $specialization,
                        'skills' => $skill_set,
                        'workExpereince' => $work_experience,
                        'hospital' => $hospital_attached_to,
                        'designation' => $Designation,
                        'pan' => $pancard_no,

                        'aboutMe' => $Description,
                        'ratings' => $Ratings,
                        'numberOFReviews' => $Reviews,
                        'profilePictureUrl' => $Profile_pic_url

                    );

                    echo json_encode(array(
                        "data" => $user,
                        "error" => null
                    ));

                    //echo json_encode(array("data"=>array('name'=>$p_full_name, 'Qulification'=>$P_qulification, 'mobileNumber'=>$P_mob_no,'emailid'=>$P_email,'designation'=>$service_title,'profilePictureUrl'=>$P_Profie_pic,"type"=>array("id"=>$S_id,"name"=>$service_title),"subtype"=>(array("id"=>$Sub_service_id,"name"=>$Sub_service_name)),'rating'=>$P_Ratings,'numberofReviews'=>$P_Reviews,'description'=>$P_Description),"error"=>null));
                    //$result[]=(array('name'=>$p_full_name, 'Qulification'=>$P_qulification, 'mobileNumber'=>$P_mob_no,'emailid'=>$P_email,'designation'=>$service_title,'profilePictureUrl'=>$P_Profie_pic,"type"=>array("id"=>$S_id,"name"=>$service_title),"subtype"=>(array("id"=>$Sub_service_id,"name"=>$Sub_service_name)),'rating'=>$P_Ratings,'numberofReviews'=>$P_Reviews,'description'=>$P_Description));
                    
                }

            }
        }
        //$data=$result;
        //echo json_encode(array("data"=>array('name'=>$p_full_name, 'Qulification'=>$P_qulification, 'mobileNumber'=>$P_mob_no,'emailid'=>$P_email,'designation'=>$service_title,'profilePictureUrl'=>$P_Profie_pic,"type"=>array("id"=>$S_id,"name"=>$service_title),"subtype"=>(array("id"=>$Sub_service_id,"name"=>$Sub_service_name)),'rating'=>$P_Ratings,'numberofReviews'=>$P_Reviews,'description'=>$P_Description),(array("error"=>null))));
        
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
