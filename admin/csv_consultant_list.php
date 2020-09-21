<?php   require_once 'inc_classes.php';
        require_once '../classes/consultantsClass.php';
        $consultantsClass = new consultantsClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    $recArgs=$_SESSION['consultant_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $consultantsClass->ConsultantsList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];
    if($recListCount)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">                                
                            <td><b>Sr.No.</b></td>
                            <td><b>Name</b></td>
                            <td><b>Email Address</b></td>
                            <td><b>Phone Number</b></td>
                            <td><b>Mobile Number</b></td>
                            <td><b>Work Phone</b></td>
                            <td><b>Work Email Address</b></td>
                            <td><b>Type</b></td>
                            <td><b>Status</b></td>
                            <td><b>Added Date</b></td>
                        </tr>';
             $i = 0;
            foreach($recList as $recListKey => $recListValue)
            {
                if($recListValue['added_date'] && $recListValue['added_date'] != '0000-00-00 00:00:00' )
                    $Added_Date = date('d M Y H:i:s A',strtotime($recListValue['added_date']));
                else
                    $Added_Date = "Not Available";

                if(!empty($recListValue['email_id']))
                    $email_id =$recListValue['email_id'];
                else
                    $email_id = "Not Available";
                
                if(!empty($recListValue['phone_no']))
                    $phone_no =$recListValue['phone_no'];
                else
                    $phone_no = "Not Available";
                
                if(!empty($recListValue['mobile_no']))
                    $mobile_no =$recListValue['mobile_no'];
                else
                    $mobile_no = "Not Available";
                
                if(!empty($recListValue['work_email_id']))
                    $work_email_id =$recListValue['work_email_id'];
                else
                    $work_email_id = "Not Available";
                
                if(!empty($recListValue['work_phone_no']))
                    $work_phone_no =$recListValue['work_phone_no'];
                else
                    $work_phone_no= "Not Available";
                
                if(!empty($recListValue['work_address']))
                    $work_address =$recListValue['work_address'];
                else
                    $work_address= "Not Available";
                
                if(!empty($recListValue['speciality']))
                    $speciality =$recListValue['speciality'];
                else
                    $speciality= "Not Available";
                
                $i= $i+1;
                
                include "include/paging_script.php";                  
                $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
                $datas .= '<td>'.$recListValue['name']." ".$recListValue['first_name']." ".$recListValue['middle_name'].'</td>';
                $datas .= '<td>'.$email_id.'</td>';
                $datas .= '<td>'.$phone_no.'</td>';
                $datas .= '<td>'.$mobile_no.'</td>';
                $datas .= '<td>'.$work_email_id.'</td>';
                $datas .= '<td>'.$work_address.'</td>';
                $datas .= '<td>'.$recListValue['typeVal'].'</td>';
                $datas .= '<td>'.$Added_Date.'</td>';
                $datas .= '</tr>';
                
              //  $datas;exit;
            }
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();    
    //echo $csv;
    $filepath="CSV/".time()."ConsultantList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=ConsultantList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>