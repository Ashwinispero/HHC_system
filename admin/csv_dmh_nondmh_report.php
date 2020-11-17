<?php require_once 'inc_classes.php'; ?>
<?php
$csv = '';
$bgColorCounter = 1;
$formDate1=$_GET['formDate_rp'];
$date1=date_create("$formDate1");
$formDate=date_format($date1,"Y-m-d H:i:s");

$toDate2=$_GET['toDate_rp'];
$date2=date_create("$toDate2");
$toDate=date_format($date2,"Y-m-d H:i:s");


$datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
            <tr height="30">
            <th width="2%">Sr.No</th>
            <th width="2%">Hospital Name</th>
            <th width="2%">Count</th>
            </tr>';
            $cnt=1;
$Query=mysql_query("select * from sp_hospitals ORDER BY hospital_id ASC");
while($row=mysql_fetch_array($Query))
{ 
            $hospital_id = $row['hospital_id'];
            $hospital_name = $row['hospital_name'];
            $events = mysql_query("SELECT COUNT(ref_hos_id) AS ref_hos_id FROM sp_events where added_date BETWEEN '$formDate%' AND '$toDate%'  AND ref_hos_id='$hospital_id'  ");
            $events_COUNT = mysql_fetch_array($events) or die(mysql_error());
	$ref_hos_id=$events_COUNT['ref_hos_id'];
           	
            $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                        <td>'.$cnt.'</td>';
            $datas .= '<td>'.$hospital_name.'</td>';
            $datas .= '<td>'.$ref_hos_id.'</td>';
            $datas .= '</tr>';
            $cnt++;
            $row_count='';
}
            $db->close();
    //echo $csv;
    $filepath="CSV/".time()."DMH_report.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=DMH_report ".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>