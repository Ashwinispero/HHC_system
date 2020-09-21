<?php
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass(); 
$Hospital_Name = $_GET['Hospital_Name'];
$employee_id = $_GET['employee_id'];

date_default_timezone_set('Asia/Kolkata'); 
$date = date('Y-m-d H:i:s');
$query1=mysql_query("select * from sp_hospitals where hospital_name='$Hospital_Name'");
if(mysql_num_rows($query1) < 1)
{
	//$HI=mysql_query("select MAX(hospital_id) as Hospital_id  from sp_hospitals");
	//$row1=mysql_fetch_array($HI);
	//$Hospitalid=$row1['Hospital_id'];
	//$Hospitalid=$Hospitalid+1;
	$hospital=mysql_query("insert into sp_hospitals() VALUES('','$Hospital_Name','NA','0','NA','','NA','1','','$employee_id','$date','$employee_id','$date')")or die(mysql_error("error"));
	if($hospital)
	{
		echo "<select name=\"Hospital_Name\"  id=\"Hospital_Name\" Onchange=\"Hospital_List();\">";
		$Query=mysql_query("select * from sp_hospitals ORDER BY hospital_name ASC");
		echo "<option value='' >Select Hospital</option>";
		while($row=mysql_fetch_array($Query))
		{
		?>
			<option value="<?php echo $row['hospital_id'] ;?>" ><?php echo $row['hospital_name'];?> </option>
		<?php
		}
		echo "<option value='Other'>other</option>";
		echo "</select>";
	
    }
}
else
{
	 echo 'exist';
}
?>
