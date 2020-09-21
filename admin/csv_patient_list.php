<?php   require_once 'inc_classes.php';
        require_once '../classes/patientsClass.php';
        $patientsClass = new patientsClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    $recArgs=$_SESSION['patient_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $patientsClass->PatientsList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];
    if($recListCount)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">                                
                            <td><b>Sr.No.</b></td>
                            <td><b>HHC Code</b></td>
                            <td><b>Name</b></td>
                            <td><b>Email Id</b></td>
                            <td><b>Phone Number</b></td>
                            <td><b>Mobile No</b></td>
                            <td><b>Birth Date</b></td>
                            <td><b>Residential Address</b></td>
                            <td><b>Permanent Address</b></td>
                            <td><b>Location</b></td>
                            <td><b>PIN Code</b></td>
                            <td><b>Status</b></td>
                            <td><b>VIP</b></td>
                            <td><b>Added Date</b></td>
                        </tr>';
             $i = 0;
            foreach($recList as $recListKey => $recListValue)
            {
                // Getting Patient Details
                $arr['patient_id']=$recListValue['patient_id'];
                $PatientDtls=$patientsClass->GetPatientById($arr);
               
                if($PatientDtls['dob'] && $PatientDtls['dob'] != '0000-00-00' )
                    $Birth_Date = date('d M Y',strtotime($recListValue['dob']));
                else
                    $Birth_Date = "Not Available";
                
                if($PatientDtls['added_date'] && $PatientDtls['added_date'] != '0000-00-00 00:00:00' )
                    $Added_Date = date('d M Y H:i:s A',strtotime($recListValue['added_date']));
                else
                    $Added_Date = "Not Available";
                
                $i= $i+1;
                
                include "include/paging_script.php";                  
                $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
                $datas .= '<td>'.$recListValue['hhc_code'].'</td>';
                $datas .= '<td>'.$recListValue['name']." ".$recListValue['first_name']." ".$recListValue['middle_name'].'</td>';
                $datas .= '<td>'.$recListValue['email_id'].'</td>';
                $datas .= '<td>'.$recListValue['phone_no'].'</td>';
                $datas .= '<td>'.$recListValue['mobile_no'].'</td>';
                $datas .= '<td>'.$Birth_Date.'</td>';
                $datas .= '<td>'.$recListValue['residential_address'].'</td>';
                $datas .= '<td>'.$recListValue['permanant_address'].'</td>';
                $datas .= '<td>'.$recListValue['locationNm'].'</td>';
                $datas .= '<td>'.$recListValue['LocationPinCode'].'</td>';
                $datas .= '<td>'.$recListValue['statusVal'].'</td>';
                $datas .= '<td>'.$recListValue['isVIPVal'].'</td>';
                $datas .= '<td>'.$Added_Date.'</td>';
                $datas .= '</tr>';
                
              //  $datas;exit;
            }
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();    
    //echo $csv;
    $filepath="CSV/".time()."PatientList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=PatientList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>