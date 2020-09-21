<?php 
include('config.php');
$service_professional_id = $_GET['service_professional_id'];
$Service_id=$_GET['Service_id'];
$time=$_GET['time'];
//echo $service_professional_id;
//Session expire after 15 min
 if( !isset( $service_professional_id) || time() - $time > 900)
{
   //header("Location:index.php");
  echo 'session_expire';
}
 else {
    
$time = time();

if(isset($service_professional_id) && !empty($service_professional_id) ) {

?>
<div id="overlay_display_change_pw">
<div id="popupwindow_display_change_pw" style="overflow:auto;">
<div id="close_btn" align='right'><input type="button" onclick="close_popup_password_form();"  style="color:white;background-color:#00cfcb;align:right;"  value=" X "></div>
<div class="col-lg-11" >
<div class="row"  ><h3 class="text-center title-services" style="color:#00cfcb">Change Password</h3>
</div>
</div>
<br>


<div class="col-lg-12" >
<div class="col-lg-12">
<div class="row"  >

<div class="col-sm-4" ><h5 class="text-left title-services" style="color:#00cfcb">Old Password</h5></div>
<div class="col-sm-8" >
   <input type="Password" placeholder="Enter Old Password" id="old_pw" onblur="remove_error_msg_old_pw();"> 
   <div id="error_message_old_msg" style="color:red"></div>
   
</div>

</div>
<div class="row"  >
<div class="col-sm-4" ><h5 class="text-left title-services" style="color:#00cfcb">New Password</h5></div>
<div class="col-lg-8" >
   <input  type="Password" placeholder="Enter new Password" id="new_pw" onblur="remove_error_msg_new_pw();"> 
   <div id="error_message_new_pw" style="color:red"></div>
</div>
</div>
<div class="row"  >
<div class="col-sm-4" ><h5 class="text-left title-services" style="color:#00cfcb">Confirm Password</h5></div>
<div class="col-sm-8" >
   <input type="Password" placeholder="Re-enter new Password" id="Confirm_pw" onblur="remove_error_msg_confirm_pw();"> 
    <div id="error_message_confirm_pw" style="color:red"></div>
</div>
</div>
<div id="error_message" style="color:red"></div>
</div>

<div class="col-lg-12" align="center" style="margin-top:4%;margin-bottom:2%">
<?php 
echo '<input  type="button" value="Submit" style="color:white;background-color:#00cfcb"  onclick="Password_chnage(\'' . $service_professional_id . '\',\'' . $Service_id . '\' );"></input>'
?>
</div>
</div>
</div></div>
<?php 
//session_destroy();
}

else
{
    //echo "<script type='text/javascript'>Alert.render('User Login');</script>";
   echo 'session_expire'; 
   
 }}
 ?>
