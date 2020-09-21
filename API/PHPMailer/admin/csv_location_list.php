<?php   require_once 'inc_classes.php';
        require_once '../classes/locationsClass.php';
        $locationsClass = new locationsClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    $recArgs=$_SESSION['location_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $locationsClass->LocationsList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];
    if($recListCount)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">                                
                            <td><b>Sr.No.</b></td>
                            <td><b>Location Name</b></td>
                            <td><b>PIN Code</b></td>
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

                $i= $i+1;
                
                include "include/paging_script.php";                  
                $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
                $datas .= '<td>'.$recListValue['location'].'</td>';
                $datas .= '<td>'.$recListValue['pin_code'].'</td>';
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
    $filepath="CSV/".time()."LocationList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=LocationList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>