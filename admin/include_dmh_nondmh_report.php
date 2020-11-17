<?php require_once('inc_classes.php'); 
        //require_once '../classes/locationsClass.php';
        //$locationsClass = new locationsClass();
        if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1')
        {
          $col_class="icon3";
          $del_visible="Y";
        }
        else 
        {
         $col_class="icon2"; 
         $del_visible="N";
        } 
?>
<?php
if(!$_SESSION['admin_user_id'])
    echo 'notLoggedIn';
else
{	
	//-----Author: ashwini 31-05-2016-----
	//--Code for date range--
$formDate1=$_GET['formDate_rp'];
$date1=date_create("$formDate1");
$formDate=date_format($date1,"Y-m-d H:i:s");

$toDate2=$_GET['toDate_rp'];
$date2=date_create("$toDate2");
$toDate=date_format($date2,"Y-m-d H:i:s");

echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                        <tr> 
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
           	echo '<tr>
            <td>'.$cnt.'</td>
            <td>'.$hospital_name.'</td>
            <td>'.$ref_hos_id.'</td>';
            echo '</tr>';
            $cnt++;
            $row_count='';
 }




}
?>