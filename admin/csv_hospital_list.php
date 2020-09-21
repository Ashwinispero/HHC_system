<?php   require_once 'inc_classes.php';
        require_once '../classes/hospitalClass.php';
        $hospitalClass=new hospitalClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    $recArgs=$_SESSION['location_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $hospitalClass->HospitalList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];
    if($recListCount)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">                                
                            <td><b>Sr.No.</b></td>
                            <td><b>Hospital Name</b></td>
                            <td><b>Phone Number</b></td>
                            <td><b>Website</b></td>
                            <td><b>Location</b></td>
                            <td><b>Address</b></td>
                             <td><b>Short Name</b></td>
                            <td><b>Added Date</b></td>
                            <td><b>Status</b></td>
                        </tr>';
             $i = 0;
            foreach($recList as $recListKey => $recListValue)
            {
                if($recListValue['added_date'] && $recListValue['added_date'] != '0000-00-00 00:00:00' )
                    $Added_Date = date('d M Y H:i:s A',strtotime($recListValue['added_date']));
                else
                    $Added_Date = "NA";
                
                 if(!empty($recListValue['website_url']))
                    $website_url=$recListValue['website_url'];
                else
                    $website_url="NA";

                $i= $i+1;
                
                include "include/paging_script.php";                  
                $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
                $datas .= '<td>'.$recListValue['hospital_name'].'</td>';
                $datas .= '<td>'.$recListValue['phone_no'].'</td>';
                $datas .= '<td>'.$website_url.'</td>';
                $datas .= '<td>'.$recListValue['locationNm'].'</td>';
                $datas .= '<td>'.$recListValue['address'].'</td>';
                $datas .= '<td>'.$recListValue['hospital_short_code'].'</td>';
                $datas .= '<td>'.$Added_Date.'</td>';
                $datas .= '<td>'.$recListValue['statusVal'].'</td>';
                $datas .= '</tr>';
                
              //  $datas;exit;
            }
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();    
    //echo $csv;
    $filepath="CSV/".time()."HospitalList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=HospitalList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>