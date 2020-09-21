<?php   require_once 'inc_classes.php';
        require_once '../classes/adminuserClass.php';
        $adminuserClass = new adminuserClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    $recArgs=$_SESSION['system_user_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $adminuserClass->AdminUserList($recArgs);
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
                            <td><b>Alternate Email Address</b></td>
                            <td><b>Type</b></td>
                            <td><b>Status</b></td>
                            <td><b>Added Date</b></td>
                            <td><b>Last Login Date/Time</b></td>
                        </tr>';
             $i = 0;
            foreach($recList as $recListKey => $recListValue)
            {
                if($recListValue['added_date'] && $recListValue['added_date'] != '0000-00-00 00:00:00' )
                    $Added_Date = date('d M Y H:i:s A',strtotime($recListValue['added_date']));
                else
                    $Added_Date = "Not Available";
                
                if($recListValue['last_login_time'] && $recListValue['last_login_time'] != '0000-00-00 00:00:00' )
                    $Last_Login_Time = date('d M Y H:i:s A',strtotime($recListValue['last_login_time']));
                else
                    $Last_Login_Time = "Not Available";
                
                if(!empty($recListValue['alternate_email_id']))
                    $alternate_email =$recListValue['alternate_email_id'];
                else
                    $alternate_email = "Not Available";

                $i= $i+1;
                
                include "include/paging_script.php";                  
                $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
                $datas .= '<td>'.$recListValue['name']." ".$recListValue['first_name']." ".$recListValue['middle_name'].'</td>';
                $datas .= '<td>'.$recListValue['email_id'].'</td>';
                $datas .= '<td>'.$recListValue['landline_no'].'</td>';
                $datas .= '<td>'.$recListValue['mobile_no'].'</td>';
                $datas .= '<td>'.$alternate_email.'</td>';
                $datas .= '<td>'.$recListValue['typeVal'].'</td>';
                $datas .= '<td>'.$recListValue['statusVal'].'</td>';
                $datas .= '<td>'.$Added_Date.'</td>';
                $datas .= '<td>'.$Last_Login_Time.'</td>';
                $datas .= '</tr>';
                
              //  $datas;exit;
            }
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();    
    //echo $csv;
    $filepath="CSV/".time()."SystemUserList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=SystemUserList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>