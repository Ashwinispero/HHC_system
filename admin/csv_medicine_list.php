<?php   require_once 'inc_classes.php';
        require_once '../classes/medicineClass.php';
        $medicineClass=new medicineClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    $recArgs=$_SESSION['medicine_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $medicineClass->MedicineList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];
    if($recListCount)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">                                
                            <td><b>Sr.No.</b></td>
                            <td><b>Name</b></td>
                            <td><b>Type</b></td>
                            <td><b>Manufacture</b></td>
                            <td><b>Rate</b></td>
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

                if(!empty($recListValue['rate']))
                    $rate =$recListValue['rate'];
                else
                    $rate = "Not Available";
                
                $i= $i+1;
                
                include "include/paging_script.php";                  
                $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
                $datas .= '<td>'.$recListValue['name'].'</td>';
                $datas .= '<td>'.$recListValue['typeVal'].'</td>';
                $datas .= '<td>'.$recListValue['manufacture_name'].'</td>';
                $datas .= '<td>'.$rate.'</td>';
                $datas .= '<td>'.$recListValue['statusVal'].'</td>';
                $datas .= '<td>'.$Added_Date.'</td>';
                $datas .= '</tr>';
                
              //  $datas;exit;
            }
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();    
    //echo $csv;
    $filepath="CSV/".time()."MedicineList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=MedicineList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>