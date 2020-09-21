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
	$Professional_type_id=$_GET['Professional_id'];

?>
<div id="Professional_name">

	<div class="searchBox" style="width:96%;"> 

                        <select id="service_professional_id" name="service_professional_id" onchange="professional_details();">
						<option value="" >------Select Professional------</option> 
						<?php
						
						$Query=mysql_query("select * from sp_professional_services  where service_id='$Professional_type_id' and status='1'");
						while($row=mysql_fetch_array($Query))
						{
								$service_professional_id=$row['service_professional_id'];
								
								$query1=mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id' and status='1'");
								if(mysql_num_rows($query1) < 1 )
								{
					
								}
								else
								{
								$row1 = mysql_fetch_array($query1) or die(mysql_error());
								$title=$row1['title'];
								$name=$row1['name'];
								$first_name=$row1['first_name'];
								$middle_name=$row1['middle_name'];
								
						?>
						<option value="<?php echo $row['service_professional_id'] ;?>" ><?php echo $title.' '.$first_name.' '.$middle_name.' '.$name;?> </option>
						<?php
								}
						}
						?></select>
						
                        </div>

</div>
<?php } ?>