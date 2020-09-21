<?php
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass(); 
$hospital_id = $_GET['hospital_id'];
$employee_id = $_GET['employee_id'];
$Consultant_Name = $_GET['Consultant_Name'];
$Consultant_Mobile_no=$_GET['Consultant_Mobile_no'];

// EMS code
date_default_timezone_set('Asia/Kolkata'); 
$date = date('Y-m-d H:i:s');
$query1=mysql_query("select * from sp_doctors_consultants where name='$Consultant_Name'");
if(mysql_num_rows($query1) < 1)
{
	//$CI=mysql_query("select MAX(doctors_consultants_id) as doctors_consultants_id  from sp_doctors_consultants");
	//$row2=mysql_fetch_array($CI);
	//$Consultantid=$row2['doctors_consultants_id'];
	//$Consultantid=$Consultantid+1;
	$Consultant=mysql_query("insert into sp_doctors_consultants() VALUES('','$hospital_id','$Consultant_Name','','','NA','NA','$Consultant_Mobile_no','NA','0','NA','NA','2','0','1','','$employee_id','$date','$employee_id','$date')")or die(mysql_error("error"));
	if($Consultant)
	{
		echo "<select class='form-control ServiceClass' name=\"Consultant_Name\"  id=\"Consultant_Name\" \">";
		$Query=mysql_query("select * from sp_doctors_consultants ORDER BY Consultant_Name ASC");
		echo "<option value='' >Select Consulatant</option>";
		
			$Query=mysql_query("select doctors_consultants_id,first_name,name from sp_doctors_consultants where hospital_id='$hospital_id' ORDER BY name ASC");
			while($row=mysql_fetch_array($Query))
			{
				$first_name=$row['first_name'];
				$name=$row['name'];
				$doctors_consultants_id=$row['doctors_consultants_id'];
				$Query1=mysql_query("select * from sp_hospitals where hospital_id='$hospital_id'");
				$row1=mysql_fetch_array($Query1);
				$hospiatl_name=$row1['hospital_name'];
			?>
			<option value="<?php echo $doctors_consultants_id ;?>" ><?php echo $first_name .' '. $name . ' / ' . $hospiatl_name ;?> </option>
			<?php
				}
			
		
		echo "</select>";
	
    }
}
else
{
	 echo 'exist';
}
?>
