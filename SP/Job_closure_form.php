<?php 
include('config.php');
$event_id = $_GET['event_id'];
$service_professional_id=$_GET['service_professional_id'];
$service_id=$_GET['service_id'];
$date_service=$_GET['date_service'];
$time=$_GET['time'];
//Session expire after 15 min
if( !isset( $service_professional_id) || time() - $time > 900)
{
	echo "<script>
alert('Your Session is expire...Palese Login again!! ');

</script>";
  echo 'session_expire';
}
else
{
$time = time();

if(isset($service_professional_id) && !empty($service_professional_id) ) {
?>
<div id="overlay_display_JobClosure_form">
<div id="popupwindow_display_JobClosure_form" style="overflow:auto;">
<div id="close_btn" align='right'><input type="button" onclick="close_popup_jobclosure_form();" style="color:white;background-color:#00cfcb;align:right;" value=" X "></div>
<div class="col-lg-11" >
<div class="row"  ><h3 class="text-center title-services" style="color:#00cfcb">Add Jobclosure Form</h3>
</div>
</div>
<br>


<div class="col-lg-12" id="disables_div">
<div class="col-lg-12">
<div class="row"  >
<div class="col-sm-2" ><h5 class="text-left title-services" style="color:#00cfcb">Baseline</h5></div>
<div class="col-sm-1" >
    <label>
        <input type="radio" value="1" id="baseline" name="baseline"> A
    </label>
 </div>
<div class="col-sm-1" >     
    <label>
         <input type="radio" value="2" id="baseline" name="baseline"> V
     </label>
</div>
<div class="col-sm-1" >
    <label>
        <input type="radio" value="3" id="baseline" name="baseline"> P
    </label>
 </div>
<div class="col-sm-1" >     
    <label>
         <input type="radio" value="4" id="baseline" name="baseline"> U
     </label>
</div>
</div>
</div>
<div class="col-lg-12">
<div class="row"  >
<div class="col-sm-2" ><h5 class="text-left title-services" style="color:#00cfcb">Airway</h5></div>
<div class="col-sm-2" >
    <label>
        <input type="radio" value="1" id="airway" name="airway"> Open
    </label>
 </div>
<div class="col-sm-2" >     
    <label>
         <input type="radio"  value="2" id="airway" name="airway"> Close
     </label>
</div>
</div>
</div>
<div class="col-lg-12">
<div class="row"  >
<div class="col-sm-2" ><h5 class="text-left title-services" style="color:#00cfcb">Breathing</h5></div>
<div class="col-sm-2" >
    <label>
        <input type="radio"  value="1" id="Breathing" name="Breathing"> Present
    </label>
 </div>
<div class="col-sm-3" >     
    <label>
         <input type="radio" value="2" id="Breathing" name="Breathing"> Compromised
     </label>
</div>
<div class="col-sm-2" >     
    <label>
         <input type="radio" value="3" id="Breathing" name="Breathing"> Absent
     </label>
</div>
</div>
</div>
<div class="col-lg-12">
<div class="row"  >
<div class="col-sm-2" ><h5 class="text-left title-services" style="color:#00cfcb">Circulation</h5></div>
<div class="col-sm-2" >
    <label>
        <input type="radio"  value="1" id="Circulation" name="Circulation"> Radial
    </label>
 </div>
<div class="col-sm-3" >     
    <label>
         <input type="radio" value="2" id="Circulation" name="Circulation"> Present
     </label>
</div>
<div class="col-sm-2" >     
    <label>
         <input type="radio" value="3" id="Circulation" name="Circulation"> Absent
     </label>
</div>
</div>
</div>
<div class="col-lg-12">
<div class="col-lg-6">
<div class="row"  >
<div class="col-sm-3" ><h5 class="text-left title-services">Temp (Core) :</h5></div>
<div class="col-sm-6" >
         <input class="form-control" id="JobClosure_temp"></input>
		</div>
		<div class="col-sm-2" ><h5 class="text-left title-services">*F</h5></div>
</div></div>
<div class="col-lg-6">
<div class="row"  >
<div class="col-sm-3" ><h5 class="text-left title-services">TBSL : </h5></div>
<div class="col-sm-7" >
         <input class="form-control" id="JobClosure_TBSL"></input>
		</div>
		<div class="col-sm-2" ><h5 class="text-left title-services">Mg/dl</h5></div>
</div>
</div>
</div>
<div class="col-lg-12">
<div class="col-lg-6">
<div class="row"  >
<div class="col-sm-3" ><h5 class="text-left title-services">Pulse : </h5></div>
<div class="col-sm-6" >
         <input class="form-control" id="JobClosure_Pulse"></input>
		</div>
		<div class="col-sm-2" ><h5 class="text-left title-services">/min</h5></div>
</div></div>
<div class="col-lg-6">
<div class="row"  >
<div class="col-sm-3" ><h5 class="text-left title-services">SpO2 : </h5></div>
<div class="col-sm-7" >
         <input class="form-control" id="JobClosure_SpO2"></input>
		</div>
		<div class="col-sm-2" ><h5 class="text-left title-services">%</h5></div>
</div>
</div>

</div>
<div class="col-lg-12" style="margin-top:2%">
<div class="col-lg-6">
<div class="row"  >
<div class="col-sm-3" ><h5 class="text-left title-services">RR : </h5></div>
<div class="col-sm-6" >
         <input class="form-control" id="JobClosure_RR"></input>
		</div>
		<div class="col-sm-2" ><h5 class="text-left title-services">/min</h5></div>
</div></div>
<div class="col-lg-6">
<div class="row"  >
<div class="col-sm-3" ><h5 class="text-left title-services">GCS Total: </h5></div>
<div class="col-sm-7" >
         <input class="form-control" id="JobClosure_GCS"></input>
		</div>
		<div class="col-sm-2" ><h5 class="text-left title-services">/15</h5></div>
</div>
</div>
</div>
<div class="col-lg-12" style="margin-top:2%">
<div class="col-lg-6">
<div class="row"  >
<div class="col-sm-3" ><h5 class="text-left title-services">BP : </h5></div>
<div class="col-sm-3" >
         <input class="form-control" id="JobClosure_BP_high"></input>
		</div>
		
		<div class="col-sm-3" >
         <input class="form-control" id="JobClosure_BP_low"></input>
		</div>
		<div class="col-sm-2" ><h5 class="text-left title-services">/MmHg</h5></div>
</div></div>
<div class="col-lg-6" >
<div class="row"  >
<div class="col-sm-4" ><h5 class="text-left title-services">Skin Perfusion:</h5></div>
<div class="col-sm-3" >     
    <label>
         <input type="radio" value="1" id="skin_perfusion" name="skin_perfusion"> Normal
     </label>
</div>
<div class="col-sm-4" >     
    <label>
         <input type="radio" value="2" id="skin_perfusion" name="skin_perfusion"> Abnormal
     </label>
</div>
</div>
</div>
</div>
<div class="col-lg-12" style="margin-top:2%">
<textarea class="form-control" rows="3"   style="resize:none" id="Jobclosure_summery" placeholder="Patient care summery notes..."></textarea>
</div>
<div class="col-lg-12" align="center" style="margin-top:2%;margin-bottom:2%">
<?php 
echo '<input  type="button" value="Submit" style="background-color:#ffbf00;border-radius:15px;padding-left:10px;padding-right:10px;"  onclick="save_jobclosure(\'' . $event_id . '\',\'' . $service_professional_id . '\',\'' . $service_id . '\',\'' . $date_service . '\' );"></input>'
?>
</div>
</div>

<div class="col-lg-12" id="medicines" style="display:none">
<div class="col-lg-12">
<div class="row"  ><h4 class="text-left title-services" style="color:#00cfcb">Consumption Details</h4>
</div>
<div class="row" >
<div class="col-lg-12">
<div class="row"  >

 <h4 class="text-left title-services" style="color:#00cfcb">Medicines:</h4>
 </div>
 <div class="row"  >
    <label class="control-label col-sm-2">Unit:</label>
        <div class="col-sm-4" >
        <select  class="form-control"  id="Medicine_unit" >
				<option value="" >Medicines</option> 
				<?php
					$Query=mysql_query("select * from sp_medicines where type='1' ORDER BY name ASC");
					while($row=mysql_fetch_array($Query))
					{
				?>
						<option value="<?php echo $row['medicine_id'] ;?>" ><?php echo $row['name'];?> </option>
		        <?php
					}
				?>
		</select>
		</div>
		 <div class="col-sm-4" >
         <input class="form-control" id="Medicine_unit_textbox"></input>
		</div>	
	<div class="col-lg-2">
<?php 
echo '<input  type="button" value="Save"  style="background:#00cfcb;color:white" onclick="Save_jobclosure_unit_medicine(\'' . $event_id . '\',\'' . $service_professional_id . '\',\'' . $service_id . '\',\'' . $date_service . '\' );"></input>'
?>
</div>	
</div>

<br>
 <div class="row"  >
    <label class="control-label col-sm-2">Non-Unit:</label>
        <div class="col-sm-4" >
        <select  class="form-control"   id="Medicine_Non_unit">
				<option value="" >Medicines</option> 
				<?php
					$Query=mysql_query("select * from sp_medicines where type='2' ORDER BY name ASC");
					while($row=mysql_fetch_array($Query))
					{
				?>
						<option value="<?php echo $row['medicine_id'] ;?>" ><?php echo $row['name'];?> </option>
		        <?php
					}
				?>
		</select>
		</div>
		 <div class="col-sm-4" >
         <input class="form-control" id="Medicine_Non_unit_textbox"></input>
		</div>	
<div class="col-lg-2">
<?php 
echo '<input  type="button" value="Save"  style="background:#00cfcb;color:white" onclick="Save_jobclosure_non_unit_medicines(\'' . $event_id . '\',\'' . $service_professional_id . '\',\'' . $service_id . '\',\'' . $date_service . '\' );"></input>'
?>
</div>	
</div>
</div>
</div>


</div>
<div class="row"  >
 <div class="col-sm-5" >
 <h4 class="text-left title-services" style="color:#00cfcb">Consumables:</h4>
 </div></div>
 <div class="row"  >
    <label class="control-label col-sm-2">Unit:</label>
        <div class="col-sm-4" >
        <select  class="form-control"   id="consumables_unit" >
				<option value="" >Consumables</option> 
				<?php
					$Query=mysql_query("select * from sp_consumables where type='1' ORDER BY name ASC");
					while($row=mysql_fetch_array($Query))
					{
				?>
						<option value="<?php echo $row['consumable_id'] ;?>" ><?php echo $row['name'];?> </option>
		        <?php
					}
				?>
		</select>
		</div>
		 <div class="col-sm-4" >
        <input class="form-control" id="consumables_unit_textbox"></input>
		</div>	
	<div class="col-lg-2">
<?php 
echo '<input  type="button" value="Save"  style="background:#00cfcb;color:white" onclick="Save_jobclosure_unit_consumables(\'' . $event_id . '\',\'' . $service_professional_id . '\',\'' . $service_id . '\',\'' . $date_service . '\' );"></input>'
?>
</div>	
</div>
<br>
 <div class="row"  >
    <label class="control-label col-sm-2">Non-Unit:</label>
        <div class="col-sm-4" >
        <select  class="form-control"  id="consumables_Non_unit" >
				<option value="" >Consumables</option> 
				<?php
					$Query=mysql_query("select * from sp_consumables where type='2' ORDER BY name ASC");
					while($row=mysql_fetch_array($Query))
					{
				?>
						<option value="<?php echo $row['consumable_id'] ;?>" ><?php echo $row['name'];?> </option>
		        <?php
					}
				?>
		</select>
		</div>
		 <div class="col-sm-4" >
         <input class="form-control" id="consumables_Non_unit_textbox"></input>
		</div>	
		<br>
<div class="col-lg-2">
<?php 
echo '<input  type="button" value="Save"  style="background:#00cfcb;color:white" onclick="Save_jobclosure_non_unit_consumables(\'' . $event_id . '\',\'' . $service_professional_id . '\',\'' . $service_id . '\',\'' . $date_service . '\' );"></input>'
?>
</div>		
				   
</div>
 <div class="row"  style="margin-top:2%;margin-bottom:2%" align="center">
 <?php 
echo '<input  type="button" value="Close Job Closure Final"  style="background:#00cfcb;color:white" onclick="close_popup_jobclosure_form();"></input>'
?>
 </div>
</div>
<?php 
//session_destroy();
}

else
{
	echo "<script>  alert('Your Session is expire...Palese Login again!! '); </script>";
 echo 'session_expire'; 
}}
 ?>