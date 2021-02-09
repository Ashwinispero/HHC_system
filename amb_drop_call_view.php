<?php 
require_once('inc_classes.php'); 
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{ ?>

<form class="form-horizontal" name="DropForm" id="DropForm" method="post" action="amb_incident_summary_ajax_process.php?action=SubmitDropCall">
<input type="hidden"  class="form-control"  id="notes_terminate" name="notes_terminate" />
<input type="hidden"   class="form-control"  id="terminate_reason_id" name="terminate_reason_id" />
<input type="hidden"   class="form-control"  id="terminatevalue" name="terminatevalue" />

<div class="row">
<div class="col-lg-2">
<h4>Call Type & caller Details: </h4>
</div>
<div class="col-lg-2">
    <select class="validate[required] chosen-select form-control"  name="CallType" id="CallType" onchange="return ChangeCallType(this.value);">
        <option value="">Purpose Of Call</option>
        <option value='1'>Drop Call</option>
        <option value='2'>Payment & Job Closure</option>
    </select>
  </div>
  <div class="col-lg-2">
<input type="text" placeholder="Enter Caller No" class="validate[required,custom[phone],minSize[6],maxSize[10]] form-control"  id="phone_no" name="phone_no" maxlength="10" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<div class="col-lg-2">
<input type="text"  placeholder="Enter caller first Name" style="text-transform: capitalize;" class="validate[required] form-control"  id="caller_first_name" name="caller_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<div class="col-lg-2">
<input type="text"  placeholder="Enter caller Last Name" style="text-transform: capitalize;" class="validate[required] form-control"  id="name" name="name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<div class="col-lg-2">
    <select class="chosen-select form-control"  placeholder="Select caller relation"  name="relation" id="relation" >
        <option value="">Relation</option>
        <?php
          $selectRecord = "SELECT relation_id,relation FROM sp_caller_relation WHERE status='1' ORDER BY relation ASC";
          $AllRrecord = $db->fetch_all_array($selectRecord);
          foreach($AllRrecord as $key=>$valRecords)
          {
            echo '<option value="'.$valRecords['relation'].'">'.$valRecords['relation'].'</option>';
          }
        ?>
      </select>
    </div>
</div>
<div class="line-seprator"></div>
<div id="Block4">
<div class="row">
<div class="col-lg-2">
  <h4>Incident Details</h4>
</div>
<div class="col-lg-2">
<input type="text" placeholder="Enter patient no" class="validate[required,minSize[1],maxSize[1]] form-control"  id="No_of_Patient" name="No_of_Patient" maxlength="1" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<div class="col-lg-2">
    <select placeholder="Select Chief Complaint" class="validate[required] chosen-select form-control"  name="Complaint_type" id="Complaint_type">
    <option value="">Chief Complaint</option>
    <?php
      $selectRecord = "SELECT * FROM sp_ems_complaint_types WHERE ct_status='1' ORDER BY ct_type ASC";
      $AllRrecord = $db->fetch_all_array($selectRecord);
      foreach($AllRrecord as $key=>$valRecords)
      {
        echo '<option value="'.$valRecords['ct_id'].'">'.$valRecords['ct_type'].'</option>';
      }
    ?>
    </select>
</div>
</div>
</div>
<div class="line-seprator"></div>
<div class="row">
<div class="col-lg-2">
<h4>Patient Details</h4>
</div>
<div class="col-lg-2">
<input type="text" placeholder="Enter patient first name" style="text-transform: capitalize;" class="validate[required] form-control callerFNameText" id="Patient_first_name" name="Patient_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<div class="col-lg-2 ">
<input type="text" placeholder="Enter patient last name" style="text-transform: capitalize;" class="validate[required] form-control callerNameText"  id="Patient_name" name="Patient_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<div class="col-lg-2 ">
<input type="text" placeholder="Enter patient phone no" class="validate[required,custom[phone],minSize[6],maxSize[10]] form-control callerPhone"  id="Patient_phone_no" name="Patient_phone_no" maxlength="10" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<div class="col-lg-2">
<input type="text"  placeholder="Enter patient age" style="text-transform: capitalize;" maxlength="2" class="form-control" id="Age" name="Age" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<div class="col-lg-2 ">
<select placeholder="Enter patient gender" class="validate[required] chosen-select form-control"  name="Gender" id="Gender" >
        <option value="">Gender</option>
        <option value='Male'>Male</option>
        <option value='Female'>Female</option>
        <option value='Other'>Other</option>
</select>
</div>
</div><br>
<div class="row">
<div class="col-lg-2">
</div>
<div class="col-lg-4">
        <input maxlength="100" placeholder="Enter patient address" id="google_location" name="google_location" type="text"  class="validate[required] form-control"  />   
        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
</div>
</div>
<div class="line-seprator"></div>
<div class="row">
<div class="col-lg-2">
<h4>Ambulance Details</h4>
</div>
<div class="exPatientListing">
    <div class="col-lg-4">
      <input maxlength="100" placeholder="Enter patient pickup address"  id="google_pickup_location" name="google_pickup_location" type="text"  class="validate[required] form-control"  />   
      <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
    </div>    
</div>
<div class="exPatientListing">
  <div class="col-lg-4">
    <input maxlength="100" placeholder="Enter patient drop address" id="google_drop_location" name="google_drop_location" type="text"  class="validate[required] form-control"  />   
    <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
  </div>    
</div>
</div>
<br>
<div class="row">
<div class="col-lg-6">
<div role="tabpanel" id="Patienttabs"> 
  <ul id="MainTabs" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active" id="google_search"><a href="#google" aria-controls="home" role="tab" data-toggle="tab" id="google_search">Google Search</a></li>
    <li role="presentation" id="manual_search"><a href="#manual" aria-controls="profile" role="tab" data-toggle="tab" id="manual_serach">Manual Search</a></li>
  </ul>
  <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="google">
      <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
        <thead>
          <tr>
          <th>Ambulance No</th>
          <th>base Location</th>
          <th>Mobile No</th>
          <th>Ambulance Type</th>
          <th>Distance</th>
          <th>Status</th>
          <th>Action</th>
          </tr>
        </thead>
        <tbody>
            <?php 
             $selectRecord = "SELECT amb.*,base_loc.id,base_loc.base_name FROM sp_ems_ambulance as amb
                              LEFT JOIN sp_ems_base_location as base_loc ON amb.base_loc = base_loc.id
                              WHERE amb.status='1'  ORDER BY amb.id ASC Limit 10";
             $AllRrecord = $db->fetch_all_array($selectRecord);
             foreach($AllRrecord as $key=>$valRecords)
             {
              echo '<tr style = "' . $complimentaryVisitStyle .'">
                <td>'.$valRecords['amb_no'].'</td>
                <td>'.$valRecords['base_name'].'</td>
                <td>'.$valRecords['mob_no'].'</td>
                <td>'.$valRecords['amb_type'].'</td>
                <td>'.$valRecords['amb_type'].'</td>
                <td>'.$valRecords['amb_status'].'</td>
                <td>'; 
                ?> 
                <input type="checkbox" class="check_class" name="selected_amb" onchange="cbChange(this)" value="<?php echo $valRecords['amb_no']; ?> " >
                <?php 
                echo '</td>
                </tr>';
                } 
            ?>
        </tbody>
      </table>
      </div>
      <div role="tabpanel" class="tab-pane " id="manual" style="hight:50%;">
        <div class="newPatientListing" >
        <div class="row">
          <label for="inputPassword3" class="col-lg-3">Select Ambulance :</label>
          <div class="col-lg-4">
            <select class="chosen-select form-control"  name="amb_no" id="amb_no" onchange="return changeambulance(this.value);">
            <option value="">Ambulance</option>
              <?php
                $selectRecord = "SELECT id,amb_no FROM sp_ems_ambulance WHERE status='1' ORDER BY id ASC";
                $AllRrecord = $db->fetch_all_array($selectRecord);
                foreach($AllRrecord as $key=>$valRecords)
                {
                echo '<option value="'.$valRecords['amb_no'].'">'.$valRecords['amb_no'].'</option>';
                }
              ?>
            </select>
        </div>
        </div>
        <br>
       <div class="row" id="ambulance_list"></div>
       </div>
      </div>
                       
      </div>
</div>
</div>
<div class="col-lg-6">
</div>
</div>
<div id='payment_details' class="row">
</div>
<div class="line-seprator"></div>
<div class="row">
<div class="col-lg-2 ">
<h4>Schedule Details</h4>
</div>
<label class="col-sm-1 label_style">Date :</label>
<div class="col-lg-2 input_box_first">
<input type="text" id="date" name="date" class="form-control datepicker_from">
</div>
<label class="col-sm-1 label_style">Time :</label>
<div class="col-lg-2 input_box_first">
<input type="text" id="time" name="time" class="form-control start validate_time">
</div>
</div>
<div class="line-seprator"></div>
<div id="col-lg-12 Block6">
<div class="row">
<div class="col-lg-2">
<h4>Other Details</h4>
</div>
<div class="col-lg-2">
<textarea id="notes" placeholder="Enter other details....if any..." name="notes" rows="4" cols="80"></textarea>
</div>
</div>
<br>
</div>
<div id="col-lg-12 Block8">
<div class="row">
<div class="col-sm-12 text-center">
<input type="button" class="btn btn-primary" id="submit" value="Dispatch Call" onclick="return SubmitDropCall();">
<input type="button" class="btn btn-primary" id="submit" value="Terminate Call" onclick="return Terminatecall();">
</div>
</div>
</div>
</form>
<?php } ?>
