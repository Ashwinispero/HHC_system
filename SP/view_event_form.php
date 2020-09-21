<?php 
include('config.php');
$event_id = $_GET['event_id'];
$service_professional_id=$_GET['service_professional_id'];
$time=$_GET['time'];
//Session expire after 15 min
if( !isset( $service_professional_id) || time() - $time > 900)
{
  echo 'session_expire';
}
else
{
$time = time();

if(isset($service_professional_id) && !empty($service_professional_id) ) {
$event_detail=mysql_query("SELECT * FROM sp_events where event_id='$event_id'") or die(mysql_error());
			$event_detail = mysql_fetch_array($event_detail) or die(mysql_error());
			$event_code=$event_detail['event_code'];
			$patient_id=$event_detail['patient_id'];
			$event_date=$event_detail['event_date'];
			$caller_id=$event_detail['caller_id'];
			
			$caller_detail=mysql_query("SELECT * FROM sp_callers where caller_id='$caller_id'") or die(mysql_error());
			$caller_detail = mysql_fetch_array($caller_detail) or die(mysql_error());
			$phone_no=$caller_detail['phone_no'];
			$name=$caller_detail['name'];
			$first_name=$caller_detail['first_name'];
			$middle_name=$caller_detail['middle_name'];
			
			
			$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
			$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
			$name_pt=$patient_nm['name'];
			$first_name_pt=$patient_nm['first_name'];
			$middle_name_pt=$patient_nm['middle_name'];
			$hhc_code=$patient_nm['hhc_code'];
			
			$phone_no_pt=$patient_nm['phone_no'];
			$mobile_no=$patient_nm['mobile_no'];
			$residential_address=$patient_nm['residential_address'];
			$permanant_address=$patient_nm['permanant_address'];
			$location_id=$patient_nm['location_id'];
			$dob=$patient_nm['dob'];
			$email_id=$patient_nm['email_id'];
			
if($location_id!='0')
{
			$location=mysql_query("SELECT * FROM sp_locations where location_id='$location_id'") or die(mysql_error());
			$location1 = mysql_fetch_array($location) or die(mysql_error());
			$location2=$location1['location'];
			$pin_code=$location['pin_code'];
}
else
{
	$location2='';
			$pin_code='';
}
			//$location=$location['location'];
?>
<div id="overlay_display_view_event_form">
<div id="popupwindow_display_view_event_form" style="overflow:auto;">
<div id="close_btn" align='right'><input type="button" onclick="close_popup_event_form();" style="align:right;" value="X">
</div>
<div class="col-lg-11" >
<div class="row"  ><h3 class="text-center title-services" style="color:#00cfcb">View Event Form</h3>
</div>
</div>
<div class="col-lg-11">
<div class="modal-header">
  
  <h4 class="modal-title"><?php echo $event_code;?>  Event Details    (Spero Services)  </h4>
   <span><h4>HHC Code :   <?php echo $hhc_code;?></h4></span>
  <span><h4>Event Date :   <?php echo $event_date;?></h4></span>
</div>
<div class="col-lg-11" >
<h4 class="section-head text-left"><span><img height="29" width="29" src="images/coller-icon.png" class="mCS_img_loaded"></span> CALLER DETAILS</h4>
</div>
<form class="form-horizontal" style="padding-left:50px;">
        <div class="form-group">
                    <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Name :</label>
                        <div class="col-sm-9">
                            <?php  echo  $name.' '. $first_name.' '. $middle_name;?>
                        </div>
        </div>      
            
              
            </form>
                <div class="line-seprator"></div>
	<div class="col-lg-11" >
	<h4 class="section-head"> <span><img height="29" width="29" src="images/patient-icon.png" class="mCS_img_loaded"></span> PATIENT DETAILS </h4>
</div>
				<form class="form-horizontal" style="padding-left:50px;">
                   
                        <div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Contact :</label>
                            <div class="col-sm-9">
                                <?php echo  $phone_no_pt ;?>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Mobile No :</label>
                            <div class="col-sm-9">
                                <?php echo  $mobile_no ;?>
                            </div>
                        </div>
                     <div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Residential Address :</label>
                            <div class="col-sm-9">
                                <?php echo  $name_pt.' '.$first_name_pt.' '.$middle_name_pt;?>
                            </div>
                        </div>   
						<div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Residential Address :</label>
                            <div class="col-sm-9">
                                <?php echo  $residential_address;?>
                            </div>
                        </div> 
				<div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Permanent Address :</label>
                            <div class="col-sm-9">
                                <?php echo  $permanant_address ;?>
                            </div>
                        </div>	
<div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Location:</label>
                            <div class="col-sm-9">
                                <?php echo  $location2 ;?>
                            </div>
                        </div>						
				  <div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Pincode:</label>
                            <div class="col-sm-9">
                                <?php echo  $pin_code ;?>
                            </div>
                        </div>
						  <div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Email Id:</label>
                            <div class="col-sm-9">
                                <?php echo  $email_id ;?>
                            </div>
                        </div>
						 
						  <div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">Pincode:</label>
                            <div class="col-sm-9">
                                <?php echo  $phone_no ;?>
                            </div>
                        </div>
						  <div class="form-group">
                            <label class="col-sm-3 text-left title-services" style="padding-top:0px;">DOB:</label>
                            <div class="col-sm-9">
                                <?php echo  $dob ;?>
                            </div>
                        </div>    
                   
				  
              
            </form>
			</div>	</div>
</div>

<?php 
//session_destroy();
}

else
{
 echo 'session_expire'; 
}}
 ?>