<?php   require_once 'inc_classes.php';
        require_once '../classes/professionalsClass.php';
        $professionalsClass = new professionalsClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    $recArgs=$_SESSION['professional_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $professionalsClass->ProfessionalsList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];
    if($recListCount)
    {
        $datas .='<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">                                
                            <td><b>Sr.No.</b></td>
                            <td><b>Professional Code</b></td>
                            <td><b>Name</b></td>
                            <td><b>Type</b></td>
                            <td><b>Email Address</b></td>
                            <td><b>Phone Number</b></td>
                            <td><b>Mobile Number</b></td>
                            <td><b>Birth Date</b></td>
                            <td><b>Home Address</b></td>
                            <td><b>Home Location</b></td>
                            <td><b>Work Address</b></td>
                            <td><b>Work Location</b></td>
                            <td><b>Set Address By</b></td>
                            <td><b>Work Phone</b></td>
                            <td><b>Work Email Address</b></td>
                            <td><b>Services</b></td>
                            <td><b>Qualification</b></td>
                            <td><b>Specialization</b></td>
                            <td><b>Skill Sets</b></td>
                            <td><b>Work Experience</b></td>
                            <td><b>Hospital Attached To</b></td>
                            <td><b>PAN CARD No</b></td>
                            <td><b>Status</b></td>
                            <td><b>Added Date</b></td>
                        </tr>';
             $i = 0;
            foreach($recList as $recListKey => $recListValue)
            {
                
                // Getting Professional Details
                $arr['service_professional_id']=$recListValue['service_professional_id'];
                $ProfDtls=$professionalsClass->GetProfessionalById($arr);
                $ProfOtherDtls=$professionalsClass->GetProfessionalOtherDtlsById($arr);
                if(!empty($ProfDtls['email_id']))
                    $email_id=$ProfDtls['email_id'];
                else
                    $email_id="NA";
                
                if(!empty($ProfDtls['mobile_no']))
                    $mobile_no=$ProfDtls['mobile_no'];
                else
                    $mobile_no="NA";
                
                if(!empty($ProfDtls['phone_no']))
                    $phone_no=$ProfDtls['phone_no'];
                else
                    $phone_no="NA";
                
                if(!empty($ProfDtls['address']))
                    $address=$ProfDtls['address'];
                else
                    $address="NA";
                
                if(!empty($ProfDtls['work_phone_no']))
                    $work_phone_no=$ProfDtls['work_phone_no'];
                else
                    $work_phone_no="NA";
                
                if(!empty($ProfDtls['work_email_id']))
                    $work_email_id=$ProfDtls['work_email_id'];
                else
                    $work_email_id="NA";
                
                if(!empty($ProfDtls['work_address']))
                    $work_address=$ProfDtls['work_address'];
                else
                    $work_address="NA";
                
                if($ProfDtls['dob'] && $ProfDtls['dob'] != '0000-00-00 00:00:00' )
                    $birth_Date = date('d M Y',strtotime($ProfDtls['dob']));
                else
                    $birth_Date = "NA";
                
                if(!empty($ProfOtherDtls['specialization']))
                    $specialization=$ProfOtherDtls['specialization'];
                else
                    $specialization="NA";
                
                if(!empty($ProfOtherDtls['qualification']))
                    $qualification=$ProfOtherDtls['qualification'];
                else
                    $qualification="NA";
                
                if(!empty($ProfOtherDtls['skill_set']))
                    $skill_set=$ProfOtherDtls['skill_set'];
                else
                    $skill_set="NA";
                
                if(!empty($ProfOtherDtls['skill_set']))
                    $skill_set=$ProfOtherDtls['skill_set'];
                else
                    $skill_set="NA";
                
                if(!empty($ProfOtherDtls['work_experience']))
                    $work_experience=$ProfOtherDtls['work_experience'];
                else
                    $work_experience="NA";
                
                if(!empty($ProfOtherDtls['hospital_attached_to']))
                    $hospital_attached_to=$ProfOtherDtls['hospital_attached_to'];
                else
                    $hospital_attached_to="NA";
                
                if(!empty($ProfOtherDtls['pancard_no']))
                    $pancard_no=$ProfOtherDtls['pancard_no'];
                else
                    $pancard_no="NA";
                
                if(!empty($ProfOtherDtls['pancard_no']))
                    $pancard_no=$ProfOtherDtls['pancard_no'];
                else
                    $pancard_no="NA";
                
                if(!empty($ProfDtls['LocationPinCode']))
                    $LocationPinCode=$ProfDtls['LocationPinCode'];
                else
                    $LocationPinCode="NA";
                
               if($ProfDtls['added_date'] && $ProfDtls['added_date'] != '0000-00-00 00:00:00' )
                    $Added_Date = date('d M Y H:i:s A',strtotime($ProfDtls['added_date']));
                else
                    $Added_Date = "NA";

                if($ProfDtls['set_location'] == '1')
                    $homeAdd = 'Home Location';
                else
                    $homeAdd = 'Work Location';
                $i= $i+1;
                
                include "include/paging_script.php";                  
                $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
                $datas .= '<td>'.$ProfDtls['professional_code'].'</td>';
                $datas .= '<td>'.$ProfDtls['name']." ".$ProfDtls['first_name']." ".$ProfDtls['middle_name'].'</td>';
                $datas .= '<td>'.$ProfDtls['typeVal'].'</td>';
                $datas .= '<td>'.$email_id.'</td>';
                $datas .= '<td>'.$phone_no.'</td>';
                $datas .= '<td>'.$mobile_no.'</td>';
                $datas .= '<td>'.$birth_Date.'</td>';
                $datas .= '<td>'.$address.'</td>';
                $datas .= '<td>'.$ProfDtls['google_home_location'].'</td>';
                $datas .= '<td>'.$work_address.'</td>';
                $datas .= '<td>'.$ProfDtls['google_work_location'].'</td>';
                $datas .= '<td>'.$homeAdd.'</td>';
                $datas .= '<td>'.$work_phone_no.'</td>';
                $datas .= '<td>'.$work_email_id.'</td>';
                
                
                $datas .= '<td>'.$ProfDtls['Services'].'</td>';
                $datas .= '<td>'.$qualification.'</td>';
                $datas .= '<td>'.$specialization.'</td>';
                $datas .= '<td>'.$skill_set.'</td>';
                $datas .= '<td>'.$work_experience.'</td>';
                $datas .= '<td>'.$hospital_attached_to.'</td>';
                $datas .= '<td>'.$pancard_no.'</td>';
                $datas .= '<td>'.$ProfDtls['statusVal'].'</td>';
                $datas .= '<td>'.$Added_Date.'</td>';
                $datas .= '</tr>';
                
              //  $datas;exit;
            }
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();    
    //echo $csv;
    $filepath="CSV/".time()."ProfessionalList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=ProfessionalList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>