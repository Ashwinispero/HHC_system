<?php require_once 'inc_classes.php';
require_once '../classes/eventClass.php';
$eventClass = new eventClass();
?>
<?php
$csv = '';
$bgColorCounter = 1;

$recArgs['pageSize'] = 'all';

$recArgs = $_SESSION['job_closure_list_args'];
$recListResponse = $eventClass->eventJobClosureList($recArgs);
$recList = $recListResponse['data'];

$recListCount = $recListResponse['count'];

if ($recListCount)
{
    $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
        <tr height="30">                                
            <td><b>Sr.No.</b></td>
            <td><b>Professional Name</b></td>
            <td><b>Mobile No</b></td>
            <td><b>Total JobClosure</b></td>
            <td><b>JobClosure Done</b></td>
            <td><b>JC Total Remaining</b></td>
            <td><b>Actual remaing till this month</b></td>
        </tr>';

    $i = 0;

    foreach($recList as $recListKey => $recListValue)
    {
        $i = $i+1;

        include "include/paging_script.php";
               
        $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                    <td>'.$i.'</td>';
        $datas .= '<td>'.$recListValue['professional_name'].'</td>';
        $datas .= '<td>'.$recListValue['mobile_no'].'</td>';
        $datas .= '<td>'.$recListValue['totalJobClosure'].'</td>';
        $datas .= '<td>'.$recListValue['completeJobClosure'].'</td>';
        $datas .= '<td>'.$recListValue['pendingJobClosure'].'</td>';
        $datas .= '<td>'.$recListValue['pendingJobClosure'].'</td>';
        $datas .= '</tr>';
    }
} else {
    $datas .= 'No record found related to your search criteria';
}

$db->close();    
//echo $csv;
$filepath="CSV/".time()."JobClosureReport.xls";
//echo $filepath;exit;
$file=fopen($filepath,"w");
fwrite($file,$datas);
fclose($file);
header("Content-Disposition: attachment; filename=JobClosureList_".date("Y-m-d").".xls");
header("Content-Type: application/vnd.ms-excel");
readfile($filepath);
unlink($filepath);
?>