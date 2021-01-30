<?php   require_once 'inc_classes.php';        
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";        
        include "classes/ambulanceClass.php";
        $AmbulanceClass = new AmbulanceClass();
        
        require_once 'classes/functions.php'; 
        require_once 'classes/config.php'; 
?>
<?php
if($_REQUEST['action']=="SubmitDropCall"){
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        //terminate call
        $terminatevalue=$_POST['terminatevalue'];
        $notes_terminate = $_POST['notes_terminate'];
        $terminate_reason_id =  $_POST['terminate_reason_id'];
        
        
        //Purpose of Call
        $CallType=$_POST['CallType'];
        //CAllerDetails
        $name=strip_tags($_POST['name']);
        $caller_first_name=strip_tags($_POST['caller_first_name']);
        $relation=strip_tags($_POST['relation']);
        $phone_no=strip_tags($_POST['phone_no']);
        //Incident Details
        $No_of_Patient=strip_tags($_POST['No_of_Patient']);
        $Complaint_type=strip_tags($_POST['Complaint_type']);
        //Patient Details
        $Patient_first_name=strip_tags($_POST['Patient_first_name']);
        $Patient_name=strip_tags($_POST['Patient_name']);
        $Patient_phone_no=strip_tags($_POST['Patient_phone_no']);
        $Age=strip_tags($_POST['Age']);
        $google_location=strip_tags($_POST['google_location']);
        //Ambulance
        $google_pickup_location=strip_tags($_POST['google_pickup_location']);
        $google_drop_location=strip_tags($_POST['google_drop_location']);
        $selected_amb=strip_tags($_POST['selected_amb']);
        
        $amb_no=strip_tags($_POST['amb_no']);
        //Ambulance Schedule Details
        $date=strip_tags($_POST['date']);
        $time=strip_tags($_POST['time']);
        //Other Details
        $notes=strip_tags($_POST['notes']);

        //Payment details
        
        $total_cost = strip_tags($_POST['total_cost']);
        $total_km = strip_tags($_POST['total_km']);



        $success=1;
        $arr['terminatevalue'] = $terminatevalue;
        $arr['notes_terminate'] = $notes_terminate ;
        $arr['terminate_reason_id'] = $terminate_reason_id;

        $arr['CallType']=$CallType;
        $arr['name']=ucwords(strtolower($name));
        $arr['caller_first_name']=ucwords(strtolower($caller_first_name));
        $arr['relation']=$relation;
        $arr['phone_no']=$phone_no;

        $arr['No_of_Patient']=$No_of_Patient;
        $arr['Complaint_type']=$Complaint_type;

        
        $arr['Patient_first_name']=ucwords(strtolower($Patient_first_name));
        $arr['Patient_name']=ucwords(strtolower($Patient_name));
        $arr['Patient_phone_no']=$Patient_phone_no;
        $arr['Age']=$Age;
        $arr['Gender']=$Gender;
        $arr['google_location']=$_POST['google_location'];
        
        $arr['google_pickup_location']=$google_pickup_location;
        $arr['google_drop_location']=$google_drop_location;
        $arr['amb_no']=$amb_no;
        $arr['selected_amb']=$selected_amb;
        $arr['manual_pickup_location']=$manual_pickup_location;
        $arr['manual_drop_location']=$manual_drop_location;

        $arr['date']=$date;
		$arr['time']=$time;
        $arr['notes']=$notes;
        $arr['hospital_id'] = $_SESSION['hospital_id'];
        $arr['employee_id']=$_SESSION['employee_id'];

        $arr['finalcost'] = $total_cost;
        $arr['total_km'] = $total_km; 

        $InsertRecord=$AmbulanceClass->InsertAmbCallers($arr); 
        if($InsertRecord)
                {
                    echo $InsertRecord; // Insert Record
                    exit;
                }
                else
                {
                   echo 'RecordExist';
                   exit;
                }

    }
}
else if($_REQUEST['action']=="vw_ambulance_list"){
    $amb_no=$db->escape($_REQUEST['amb_no']);
 
   ?>
   <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Ambulance No</th>
                <th>base Location</th>
                <th>Mobile No</th>
                <th>Ambulance Type</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php 
             $selectRecord = "SELECT amb.*,base_loc.id,base_loc.base_name FROM sp_ems_ambulance as amb
                              LEFT JOIN sp_ems_base_location as base_loc ON amb.base_loc = base_loc.id
                              WHERE amb.status='1' AND amb.amb_no='".$amb_no."' ORDER BY amb.id ASC";
             $AllRrecord = $db->fetch_all_array($selectRecord);
             foreach($AllRrecord as $key=>$valRecords)
             {
              echo '<tr style = "' . $complimentaryVisitStyle .'">
                <td>'.$valRecords['amb_no'].'</td>
                <td>'.$valRecords['base_name'].'</td>
                <td>'.$valRecords['mob_no'].'</td>
                <td>'.$valRecords['amb_type'].'</td>
                <td>'.$valRecords['amb_status'].'</td>
                <td>'; 
                ?> 
                <input type="checkbox" name="selected_amb" id="selected_amb" value="<?php echo $valRecords['amb_no']; ?> " >
                <?php 
                echo '</td>
                </tr>';
                } 
            ?>
            </tbody>
            </table>
<?php
}
else if($_REQUEST['action']=='vw_terminate_form'){
    $status=$db->escape($_REQUEST['status']);
    ?>
    <form class="form-horizontal" name="DropForm" id="DropForm" method="post" action="amb_incident_summary_ajax_process.php?action=SubmitDropCall">
            <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="page-title" style="align:center">Terminate From</h4>
  
</div>
<div class="modal-body">
<div class="mCustomScrollbar">
<div class="row" style="padding-left:5px;">
<label for="inputPassword3" class="col-lg-3 label_style">Termination Reason :<span style="color:red;">*</span></label>
<div class="col-lg-4 input_box_first">
                          <!--<input type="text" class="form-control" id="relation" name="relation" maxlength="30" >-->
                          <!--<label class="select-box-lbl">-->
                              <select class="chosen-select form-control"  name="terminate_reason_id_old" id="terminate_reason_id_old" >
                                <option value="">Termination Reason</option>
                                <?php
                                    $selectRecord = "SELECT reason,id FROM sp_amb_termination_reason WHERE status='1' ORDER BY reason ASC";
                                    $AllRrecord = $db->fetch_all_array($selectRecord);
                                    foreach($AllRrecord as $key=>$valRecords)
                                    {
                                        echo '<option value="'.$valRecords['id'].'">'.$valRecords['reason'].'</option>';
                                    }
                                ?>
                            </select>
                          <!--</label>-->
                      </div>
  </div>
  <input type="hidden"  class="validate[required,minSize[1],maxSize[1]] form-control" value="yes" id="terminatevalue_old" name="terminatevalue_old" />
  <br>
  <div class="row" style="padding-left:5px;">

<label for="inputPassword3" class="col-lg-3 label_style">Other Notes: <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<textarea id="notes_terminate_old" name="notes_terminate_old" rows="4" cols="80"></textarea>
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12 text-center">
<input type="button" class="btn btn-primary" id="submit" value="Terminate Call" onclick="return SubmitDropCall();">
</div>
</div>



</div>
</div>
</div>
    <?php
}
else if($_REQUEST['action']=='vw_payment_form'){
    $event_id=$db->escape($_REQUEST['event_id']);
    $recList= $AmbulanceClass->event_payment_details($event_id);
    //var_dump($recList[0]['event_id']);die();
?>
          <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="page-title">Payment For <?php echo $recList[0]['event_code'] ?></h4>
  
</div>
<div class="modal-body">
<div class="mCustomScrollbar">
<div id="Block1">
<div class="row">
<label class="col-sm-2 label_style">Total KM :<span style="color:red;">*</span></label>
<div class="col-lg-2 input_box_first">
<input type="text" value="<?php echo $recList[0]['total_km'] ?>" class="validate[required,custom[phone],minSize[6],maxSize[10]] form-control"  disabled maxlength="5" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label class="col-sm-2 label_style">Total Amount :<span style="color:red;">*</span></label>
<div class="col-lg-2 input_box_first">
<input type="text" value="<?php echo $recList[0]['finalcost'] ?>" class="validate[required,custom[phone],minSize[6],maxSize[10]] form-control"  disabled maxlength="5" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
</div>
<br><br>
<div class="row" style="padding-left:5px;">
<label class="col-sm-1">Date  :<span style="color:red;">*</span></label>
<div class="col-lg-2 input_box_first">
<input type="date" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control "  />
</div>
<label for="inputPassword3" class="col-lg-2 label_style">Payment Type :<span style="color:red;">*</span></label>
  <div class="col-lg-2 input_box_first">
    <select class="validate[required] chosen-select form-control"  name="CallType" id="CallType" onchange="return ChangeCallType(this.value);">
        <option value="">Payment Type</option>
        <option value='Cash'>Cash</option>
        <option value='Cheque'>Cheque</option>
        <option value='NEFT'>NEFT</option>
        <option value='Card'>Card</option>
    </select>
  </div>
  <label class="col-sm-2 label_style">Amount :<span style="color:red;">*</span></label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[6],maxSize[10]] form-control"  id="amount" name="amount" maxlength="5" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
</div>
<br>
<div class="row">
<div class="col-sm-12 text-center">
<input type="button" class="btn btn-primary" id="submit" value="Submit Payment" onclick="return Submitpayment();">
</div>
</div>
</div>



</div>
</div>

<?php
}
else if($_REQUEST['action']=='vw_payment')
{
     
     $google_pickup_location = $_REQUEST['google_pickup_location'];
     $selected_ambumance = $_REQUEST['selected_amb'];
      $google_drop_location = $_REQUEST['google_drop_location'];
    
     // ************Distance Calculation pickup to Drop Start**********
     $apiKey = 'AIzaSyBW_HR7a125NbuIVsomf-pzKIV5JT_CXzg';
		
     // Change address format
     $formattedAddrFrom    = str_replace(' ', '+', $google_pickup_location);
     $formattedAddrTo     = str_replace(' ', '+', $google_drop_location);
     
     // Geocoding API request with start address
     $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrFrom.'&sensor=false&key='.$apiKey);
     $outputFrom = json_decode($geocodeFrom);
     if(!empty($outputFrom->error_message)){
         return $outputFrom->error_message;
     }
     
     // Geocoding API request with end address
     $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
     $outputTo = json_decode($geocodeTo);
     if(!empty($outputTo->error_message)){
         return $outputTo->error_message;
     }
     
     // Get latitude and longitude from the geodata
     // $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
     // $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
     // $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
     // $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
     
      $latitudeFrom    = 18.453038019232814;
      $longitudeFrom    = 73.86473972256739;
      $latitudeTo        = 18.511087639053997;
      $longitudeTo    = 73.93255149226636;
     
     // Calculate distance between latitude and longitude
     $theta    = $longitudeFrom - $longitudeTo;
     $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
     $dist    = acos($dist);
     $dist    = rad2deg($dist);
     $miles    = $dist * 60 * 1.1515;
     
     // Convert unit and return distance
     $unit = 'K';
     $unit = strtoupper($unit);
     if($unit == "K"){
             $total = round($miles * 1.609344, 2);
     }elseif($unit == "M"){
             $total =  round($miles * 1609.344, 2).' meters';
     }else{
             $total =  round($miles, 2).' miles';
     }
    // ***********Distance Calculation pickup to Drop END***************
  
     // ************Distance Calculation base location to pickup Start**********
    $amb_details= mysql_query("SELECT * FROM sp_ems_ambulance  where amb_no='$selected_ambumance'");
    $amb_details_row = mysql_fetch_array($amb_details) or die(mysql_error());
    $base_latfrom=$amb_details_row['lat'];  
    $base_longfrom=$amb_details_row['long'];
    $pickup_latitudeFrom = $latitudeFrom;
    $pickup_longitudeFrom = $longitudeFrom;

     // Calculate distance between latitude and longitude
     $theta    = $base_longfrom - $pickup_longitudeFrom;
     $dist    = sin(deg2rad($base_latfrom)) * sin(deg2rad($pickup_latitudeFrom)) +  cos(deg2rad($base_latfrom)) * cos(deg2rad($pickup_latitudeFrom)) * cos(deg2rad($theta));
     $dist    = acos($dist);
     $dist    = rad2deg($dist);
     $miles    = $dist * 60 * 1.1515;
     
     // Convert unit and return distance
     $unit = 'K';
     $unit = strtoupper($unit);
     if($unit == "K"){
             $total_1 = round($miles * 1.609344, 2);
     }elseif($unit == "M"){
             $total_1 =  round($miles * 1609.344, 2).' meters';
     }else{
             $total_1 =  round($miles, 2).' miles';
     }
     // ************Distance Calculation  base location to pickup  END************
        // ************Distance Calculation drop to base location start **********
      $base_latto=$amb_details_row['lat'];
      $base_longto=$amb_details_row['long'];
      $drop_latitudeFrom = $latitudeFrom;
      $drop_longitudeFrom = $longitudeFrom;

     // Calculate distance between latitude and longitude
     $theta    = $base_longto - $drop_longitudeFrom;
     $dist    = sin(deg2rad($base_latto)) * sin(deg2rad($drop_latitudeFrom)) +  cos(deg2rad($base_latto)) * cos(deg2rad($drop_latitudeFrom)) * cos(deg2rad($theta));
     $dist    = acos($dist);
     $dist    = rad2deg($dist);
     $miles    = $dist * 60 * 1.1515;
     
     // Convert unit and return distance
     $unit = 'K';
     $unit = strtoupper($unit);
     if($unit == "K"){
             $total_2 = round($miles * 1.609344, 2);
     }elseif($unit == "M"){
             $total_2 =  round($miles * 1609.344, 2).' meters';
     }else{
             $total_2 =  round($miles, 2).' miles';
     }
// ************Distance Calculation drop to base location end **********
$total_KM = $total + $total_1 + total_2;
$total_cost = $total_KM * 10 ;

     ?>
<div id="Block1">
<form  style="padding-left:5px;">
<div class="row">
<label class="col-sm-3 ">Pickup Location to Drop Location:</label>
<div class="col-lg-3 input_box_first">
<input disabled type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php echo $total; ?> " />
</div>
<label class="col-sm-3">Base locatin  to Pickup Location:</label>
<div class="col-lg-3 input_box_first">
<input disabled type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php echo $total_1; ?> " />
</div>
</div>
<br>
<div class="row">
<label class="col-sm-3">Drop Location to base Location:</label>
<div class="col-lg-3 input_box_first">
<input disabled type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php echo $total_2; ?> " />
</div>
<label  class="col-lg-3">Total KM : <span style="color:red;">*</span></label>
<div class="col-lg-3 input_box_first">
<input  type="text" id="total_km" name="total_km" value="<?php echo $total_KM; ?>" class="form-control datepicker_from">
</div>
</div>
<br>
<div class="row">

<label  class="col-lg-3">Total Cost : <span style="color:red;">*</span></label>
<div class="col-lg-3 input_box_first">
<input  type="text" id="total_cost" name="total_cost" value="<?php echo $total_cost; ?>" class="form-control datepicker_from">
</div>
</div>
</form>
</div>

     <?php
}
else if($_REQUEST['action']=='vw_dispatch_form')
    {
                ?>
                <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="page-title">Dispatch From</h4>
  
</div>

<div class="modal-body">
<div class="mCustomScrollbar">
<div id="Block1">
<h5 class="page-title">Caller Details</h5>
<form class="row" style="padding-left:5px;">
<label class="col-sm-1">Contact :</label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-2">Last Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerNameText" value="<?php if($EditedResponseArr['caller_last_name']) echo $EditedResponseArr['caller_last_name']; else echo $_POST['caller_last_name'];  ?>" id="name" name="name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-2 control-label">First Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerFNameText" value="<?php if($EditedResponseArr['caller_first_name']) echo $EditedResponseArr['caller_first_name']; else echo $_POST['caller_first_name'];  ?>" id="caller_first_name" name="caller_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" /></div>
<label for="inputPassword3" class="col-lg-1 ">Relation :</label>
                      <div class="col-lg-2 input_box_first">
                          <!--<input type="text" class="form-control" id="relation" name="relation" maxlength="30" >-->
                          <!--<label class="select-box-lbl">-->
                              <select class="chosen-select form-control"  name="relation" id="relation" onchange="return changeRelation(this.value);">
                                <option value="">Relation</option>
                                <?php
                                    $selectRecord = "SELECT relation_id,relation FROM sp_caller_relation WHERE status='1' ORDER BY relation ASC";
                                    $AllRrecord = $db->fetch_all_array($selectRecord);
                                    foreach($AllRrecord as $key=>$valRecords)
                                    {
                                        if($EditedResponseArr['relation'] == $valRecords['relation'])
                                            echo '<option value="'.$valRecords['relation'].'" selected="selected" >'.$valRecords['relation'].'</option>';
                                        else
                                            echo '<option value="'.$valRecords['relation'].'">'.$valRecords['relation'].'</option>';
                                    }
                                ?>
                            </select>
                          <!--</label>-->
                      </div>
                      </form>
</div>
<div id="Block2">
<h5 class="page-title">Patient Details</h5>
<form style="padding-left:5px;">
<div class="row">
<label for="inputPassword3" class="col-lg-2 control-label">First Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerFNameText" value="<?php if($EditedResponseArr['caller_first_name']) echo $EditedResponseArr['caller_first_name']; else echo $_POST['caller_first_name'];  ?>" id="caller_first_name" name="caller_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" /></div>
<label for="inputPassword3" class="col-lg-2">Last Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerNameText" value="<?php if($EditedResponseArr['caller_last_name']) echo $EditedResponseArr['caller_last_name']; else echo $_POST['caller_last_name'];  ?>" id="name" name="name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<label class="col-sm-1">Contact :</label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-1 control-label">Age:</label>
<div class="col-lg-2">
<input type="text" maxlength="30" style="text-transform: capitalize;" class="form-control" id="Age" name="Age" value="<?php if($recListResponse['Age']) echo $recListResponse['Age']; else echo $_POST['Age']; ?>" />
</div>
</div><br>
<div class="row">
<label for="inputPassword3" class="col-lg-2 control-label">Location : <span style="color:red;">*</span></label>
<div class="col-lg-3 input_box">
        <input maxlength="100" id="google_location_new" name="google_location_new" type="text" value="<?php if($recListResponse['locationNm']) echo $recListResponse['locationNm']; else echo $_POST['locationNm']; ?>" class="validate[required] form-control"  />   
        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
</div>
<label for="inputPassword3" class="col-lg-2 control-label">Home Location <span class="required">*</span></label>
    <div class="col-lg-3">
    <input type="text" name="google_home_location" id="google_home_location" class="validate[required] form-control" value="<?php if(!empty($_POST['google_home_location'])) { echo $_POST['google_home_location']; } else  echo $ProfDtls['google_home_location']; ?>" maxlength="160" >
    </div>
</div>


</form>
</div>

<div id="Block3">
<h5 class="page-title">Ambulance Details</h5>
<form style="padding-left:5px;">
<div class="row">
<label for="inputPassword3" class="col-lg-2 control-label">First Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerFNameText" value="<?php if($EditedResponseArr['caller_first_name']) echo $EditedResponseArr['caller_first_name']; else echo $_POST['caller_first_name'];  ?>" id="caller_first_name" name="caller_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" /></div>
<label for="inputPassword3" class="col-lg-2">Last Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerNameText" value="<?php if($EditedResponseArr['caller_last_name']) echo $EditedResponseArr['caller_last_name']; else echo $_POST['caller_last_name'];  ?>" id="name" name="name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<label class="col-sm-1">Contact :</label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-1 control-label">Age:</label>
<div class="col-lg-2">
<input type="text" maxlength="30" style="text-transform: capitalize;" class="form-control" id="Age" name="Age" value="<?php if($recListResponse['Age']) echo $recListResponse['Age']; else echo $_POST['Age']; ?>" />
</div>
</div><br>
<div class="row">
<label for="inputPassword3" class="col-lg-2 control-label">Location : <span style="color:red;">*</span></label>
<div class="col-lg-3 input_box">
        <input maxlength="100" id="google_location_new" name="google_location_new" type="text" value="<?php if($recListResponse['locationNm']) echo $recListResponse['locationNm']; else echo $_POST['locationNm']; ?>" class="validate[required] form-control"  />   
        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
</div>
<label for="inputPassword3" class="col-lg-2 control-label">Home Location <span class="required">*</span></label>
    <div class="col-lg-3">
    <input type="text" name="google_home_location" id="google_home_location" class="validate[required] form-control" value="<?php if(!empty($_POST['google_home_location'])) { echo $_POST['google_home_location']; } else  echo $ProfDtls['google_home_location']; ?>" maxlength="160" >
    </div>
</div>


</form>
</div>
</div>
</div>


<?php
}
?>