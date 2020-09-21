<?php require_once 'inc_classes.php';
require_once '../classes/professionalsClass.php';
$professionalsClass = new professionalsClass();
?>
<?php
$csv = '';
$bgColorCounter = 1;

$recArgs['pageSize'] = 'all';
$recArgs = $_SESSION['payment_list_args'];
$recListResponse = $professionalsClass->paymentsList($recArgs);
$recList = $recListResponse['data'];

$recListCount = $recListResponse['count'];

if ($recListCount)
{
    $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
        <tr height="30">                                
            <td><b>Sr.No.</b></td>
            <td><b>Event Code</b></td>
            <td><b>Payment Date</b></td>
            <td><b>Amount</b></td>
            <td><b>Mode</b></td>
        </tr>';

    $i = 0;

    foreach($recList as $recListKey => $recListValue)
    {
        $i = $i+1;

        include "include/paging_script.php";
               
        $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                    <td>'.$i.'</td>';
        $datas .= '<td>'.$recListValue['event_code'].'</td>';
        $datas .= '<td>'.date('d M Y',strtotime($recListValue['date_time'])).'</td>';
        $datas .= '<td>'.$recListValue['payment_amount'].'</td>';
        $datas .= '<td>'.$recListValue['payment_type'].'</td>';
        $datas .= '</tr>';
    }
} else {
    $datas .= 'No record found related to your search criteria';
}

$db->close();    
//echo $csv;
$filepath="CSV/".time()."paymentReport.xls";
//echo $filepath;exit;
$file=fopen($filepath,"w");
fwrite($file,$datas);
fclose($file);
header("Content-Disposition: attachment; filename=paymentReportList_".date("Y-m-d").".xls");
header("Content-Type: application/vnd.ms-excel");
readfile($filepath);
unlink($filepath);
?>