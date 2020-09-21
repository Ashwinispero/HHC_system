<?php
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass(); 
$hospital_id = $_GET['hospital_id'];

if($hospital_id=='Other')
{
?>
<div id="hospital_dropdown">
	<br>
	<div class="value dropdown">
        <input class="form-control ServiceClass" id="Hospital_Name" Placeholder="Enter Hospital Name" style="width:340px;" onkeypress="error_remove_hospital_name();"></input>
		
	<div id="error_msg_hospital_name" style="color:red"></div>
	</div>
	<br>
	<div class="col-sm-12 text-right">
		<input class="btn btn-primary" type="button" value="Save" onclick="Save_Hospital_Name();"></input>
	</div>
</div>
<?php
}
else
{
?>
<div id="hospital_dropdown">
	<br>
	<div class="value dropdown">
        <label>
		<select class="form-control ServiceClass" name="Consultant" id="Consultant"  style="width:340px;" onchange="Cosultant_add();">
        <option value="0" >Consultant</option> 
		<?php
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
			?>
			<option value="Other">Other</option>
		</select>
		</label>
    </div>
	
</div>
<?php
}
?>
