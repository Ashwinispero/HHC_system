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
$formDate=$_GET['formDate_audio'];
$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['toDate_audio'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
//$hospital_id=$_GET['hospital_id'];
     
if($formDate=='' and $toDate=='')
{
             $RecordSql=mysql_query("SELECT * from sp_incoming_call limit 50 order by DESC");

	//$payments = mysql_query("SELECT * FROM sp_events where status=1 and hospital_id='$hospital_id' ORDER BY date  DESC");
}
else
{
        $RecordSql = mysql_query("SELECT * FROM sp_incoming_call where call_datetime BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY call_datetime DESC");
            
        //$payments = mysql_query("SELECT * FROM sp_events where hospital_id='$hospital_id' AND status=1 and date BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY date  ASC ");
}
$row_count = mysql_num_rows($RecordSql);
if($row_count > 0)
{
            echo '<div class="table-responsive" id="payment">
                  <table class="table table-hover table-bordered">
                  <tr> 
                        <th width="3%">Phone No</th>
                        <th width="5%">Extention No</th>
                        <th width="5%">Call Type</th>
                        <th width="5%">Date Time</th>
		
		<th width="5%">Audio File</th>
                </tr>';
            while($RecordSql_rows=mysql_fetch_array($RecordSql))
            {	
                if($RecordSql_rows['call_Type'] == 'I')
                {
                    $callType = 'Incoming Call';
                }
                else if($RecordSql_rows['call_Type'] == 'O')
                {
                    $callType = 'Outgoing';
                }
                else
                {
                    $callType = 'NA';
                }
	
	echo '<tr>
	<td>'.$RecordSql_rows['calling_phone_no'].'</td> 
            <td>'.$RecordSql_rows['ext_no'].'</td>
            <td>'.$callType.'</td>
	<td>'.$RecordSql_rows['call_datetime'].'</td>
        <td style = "' . $style .'" align="center">';
         if($RecordSql_rows['call_audio'] ==''){ echo 'No Audio File'; }else { ?><a  target="_blank" href=" <?php echo $RecordSql_rows['call_audio']; ?> " ><span aria-hidden="true" class="glyphicon glyphicon-play"></span></a> <?php  }
        echo '</td>';
        echo '</tr>';
	}
}
else
{
	echo "<tr>";
	echo "<td colspan='14' align='middle'>" . "Record Not found for this date" . "</td>";
            echo "</tr>";
	echo "</div>";
	echo "</table>";
}
}
?>