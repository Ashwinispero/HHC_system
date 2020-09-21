<?php   require_once 'inc_classes.php';
        require_once '../classes/employeesClass.php';
        $employeesClass = new employeesClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    $recArgs=$_SESSION['employee_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $employeesClass->EmployeesList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];
    if($recListCount)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">                                
                            <td><b>Sr.No.</b></td>
                            <td><b>Employee Code</b></td>
                            <td><b>Name</b></td>
                            <td><b>Type</b></td>
                            <td><b>Hospital Name</b></td>
                            <td><b>Designation</b></td>
                            <td><b>Email Address</b></td>
                            <td><b>Phone Number</b></td>
                            <td><b>Mobile Number</b></td>
                            <td><b>Birth Date</b></td>
                            <td><b>Address</b></td>
                            <td><b>Work Phone</b></td>
                            <td><b>Work Email Address</b></td>
                            <td><b>Location</b></td>
                            <td><b>PIN Code</b></td>
                            <td><b>Qualification</b></td>
                            <td><b>Specialization</b></td>
                            <td><b>Work Experience</b></td>
                            <td><b>Status</b></td>
                            <td><b>Added Date</b></td>
                        </tr>';
             $i = 0;
            foreach($recList as $recListKey => $recListValue)
            {
                
                // Getting Employee Details
                $arr['employee_id']=$recListValue['employee_id'];
                $EmpDtls=$employeesClass->GetEmployeeById($arr);
                
                if(!empty($EmpDtls['mobile_no']))
                    $mobile_no=$EmpDtls['mobile_no'];
                else
                    $mobile_no="NA";
                
                if(!empty($EmpDtls['phone_no']))
                    $phone_no=$EmpDtls['phone_no'];
                else
                    $phone_no="NA";
                
                if(!empty($EmpDtls['work_email_id']))
                    $work_email_id=$EmpDtls['work_email_id'];
                else
                    $work_email_id="NA";
                
                if($EmpDtls['dob'] && $EmpDtls['dob'] != '0000-00-00 00:00:00' )
                    $birth_Date = date('d M Y',strtotime($EmpDtls['dob']));
                else
                    $birth_Date = "NA";
                
                if(!empty($EmpDtls['specialization']))
                    $specialization=$EmpDtls['specialization'];
                else
                    $specialization="NA";
                
                if(!empty($EmpDtls['qualification']))
                    $qualification=$EmpDtls['qualification'];
                else
                    $qualification="NA";
                
                if(!empty($EmpDtls['work_experience']))
                    $work_experience=$EmpDtls['work_experience'];
                else
                    $work_experience="NA";
                
                if(!empty($EmpDtls['LocationPinCode']))
                    $LocationPinCode=$EmpDtls['LocationPinCode'];
                else
                    $LocationPinCode="NA";
                
               if($EmpDtls['added_date'] && $EmpDtls['added_date'] != '0000-00-00 00:00:00' )
                    $Added_Date = date('d M Y H:i:s A',strtotime($EmpDtls['added_date']));
                else
                    $Added_Date = "NA";

                $i= $i+1;
                
                include "include/paging_script.php";                  
                $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
                $datas .= '<td>'.$EmpDtls['employee_code'].'</td>';
                $datas .= '<td>'.$EmpDtls['name']." ".$EmpDtls['first_name']." ".$EmpDtls['middle_name'].'</td>';
                $datas .= '<td>'.$EmpDtls['typeVal'].'</td>';
                $datas .= '<td>'.$EmpDtls['hospitalNm'].'</td>';
                $datas .= '<td>'.$EmpDtls['designation'].'</td>';
                $datas .= '<td>'.$EmpDtls['email_id'].'</td>';
                $datas .= '<td>'.$phone_no.'</td>';
                $datas .= '<td>'.$mobile_no.'</td>';
                $datas .= '<td>'.$birth_Date.'</td>';
                $datas .= '<td>'.$EmpDtls['address'].'</td>';
                $datas .= '<td>'.$work_phone_no.'</td>';
                $datas .= '<td>'.$work_email_id.'</td>';
                $datas .= '<td>'.$EmpDtls['locationNm'].'</td>';
                $datas .= '<td>'.$LocationPinCode.'</td>';
                $datas .= '<td>'.$qualification.'</td>';
                $datas .= '<td>'.$specialization.'</td>';
                $datas .= '<td>'.$work_experience.'</td>';
                $datas .= '<td>'.$EmpDtls['statusVal'].'</td>';
                $datas .= '<td>'.$Added_Date.'</td>';
                $datas .= '</tr>';
                
              //  $datas;exit;
            }
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();    
    //echo $csv;
    $filepath="CSV/".time()."EmployeeList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=EmployeeList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>