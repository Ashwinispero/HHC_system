<?php require_once('inc_classes.php'); 
        //require_once '../classes/locationsClass.php';
        //$locationsClass = new locationsClass();
        
?>
<?php
if(!$_SESSION['admin_user_id'])
    echo 'notLoggedIn';
else
{	
	//$total=0;
	$service_professional_id=$_GET['service_professional_id'];

?>
<div id="Professional_Deatil">
<?php 

		$query=mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id' and status='1'");
				if(mysql_num_rows($query) < 1 )
				{
					//echo 'abc';
				}
				else
				{
					$row1 = mysql_fetch_array($query) or die(mysql_error());
								$title=$row1['title'];
								$name=$row1['name'];
								$first_name=$row1['first_name'];
								$middle_name=$row1['middle_name'];
					echo '<div class="table-responsive" id="payment"><table class="table table-hover table-bordered">
                <tr> 
                    <th width="2%">Name</th>
					<th width="2%">From Date</th>
                    <th width="2%">To date</th>
					<th width="2%">Notes/Reason</th>
					<th width="2%">Action</th>
                  
				 </tr>';
				 echo '<tr>
							<td>'.$title.' '.$first_name.' '.$middle_name.' '.$name.'</td>
							<td>'. '<input type="Date" id="date_form">'.'</td>
							<td>'. '<input type="Date" id="date_to" >'.'</td>
							<td>'. '<textarea type="text" rows="2" id="note"></textarea>'.'</td>
							<td>'. '<input type="button" value="Save" onclick="Save_weekoff(\'' . $service_professional_id . '\');" >'.'</td>';
						   
							echo '</tr>';
					
				}
				echo '</div>' ;
?>
	

</div>
<?php } ?>