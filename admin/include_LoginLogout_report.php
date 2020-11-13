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
$formDate=$_GET['formDate_login'];
$date1=date_create("$formDate");
$formDate1=date_format($date1,"Y-m-d H:i:s");

$toDate=$_GET['toDate_login'];
$date2=date_create("$toDate");
$toDate2=date_format($date2,"Y-m-d H:i:s");
    
if($formDate!='' and $toDate!='')
{
$events = mysql_query("SELECT * FROM sp_session where added_date BETWEEN '$formDate1%' AND '$toDate2%' ORDER BY added_date DESC");
}
	
	$row_count = mysql_num_rows($events);
	if($row_count > 0)
	{
	echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                        <tr> 
		<th width="2%">Professional Name</th>
		<th width="2%">Status</th>
		 <th width="2%">Login</th>
		<th width="2%">Logout</th>
                        </tr>';
		for($i=1; $i<=$row_count;)
		{
			while ($events_rows = mysql_fetch_array($events))
			{		
				$service_professional_id=$events_rows['service_professional_id'];
				$status=$events_rows['status'];
				$added_date=$events_rows['added_date'];
				$last_modify_date=$events_rows['last_modify_date'];
				if($status=='1'){
                                                            $status_chk='Login';
                                                }else if($status=='2'){
                                                            $status_chk='Logout';
                                                }else if($status=='3'){
                                                            $status_chk='Device Removed';
                                                }
				$professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id'");
				$professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
			            $name=$professional_name_abc['name'];
				$title=$professional_name_abc['title'];
				$first_name=$professional_name_abc['first_name'];
                                                $middle_name=$professional_name_abc['middle_name'];
                                                $google_location_prof=$professional_name_abc['google_work_location'];
				$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
				
				
				echo '<tr>
				<td>'.$professional_name.'</td>
				<td>'.$status_chk.'</td>
				<td>'.$added_date.'</td>
                                                <td>'.$last_modify_date.'</td>';
				echo '</tr>';
		                        
			}
		}
	}
	else
	{
			
	echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                        <tr> 
		<th width="2%">Professional Name</th>
		<th width="2%">Status</th>
		<th width="2%">Login/th>
		<th width="2%">Logout</th>
		</tr>';
	echo "<td colspan='10' align='middle'>" . "Record Not found for this date" . "</td>";
            echo "</tr>";
	echo "</div>";
	echo "</table>";
	}
}
?>