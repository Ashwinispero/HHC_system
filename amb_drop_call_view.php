<?php 
require_once('inc_classes.php'); 
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{ ?>

<form class="form-horizontal" name="DropForm" id="DropForm" method="post" action="amb_incident_summary_ajax_process.php?action=SubmitDropCall">
<div id="Block9">
<h1 class="div_header">Call Type</h1>
<div class="row" style="padding-left:5px;">
<label for="inputPassword3" class="col-lg-2 label_style">Purpose Of Call :<span style="color:red;">*</span></label>
  <div class="col-lg-2 input_box_first">
    <select class="validate[required] chosen-select form-control"  name="CallType" id="CallType" onchange="return ChangeCallType(this.value);">
        <option value="">Purpose Of Call</option>
        <option value='1'>Drop Call</option>
        <option value='2'>Payment</option>
    </select>
  </div>
  </div>
</div>
<div class="line-seprator"></div>
<div id="Block1">
<h1 class="div_header">Caller Details</h1>
<div class="row" style="padding-left:5px;">
<label class="col-sm-1 label_style">Contact :<span style="color:red;">*</span></label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[6],maxSize[10]] form-control"  id="phone_no" name="phone_no" maxlength="10" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-2 label_style">Last Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control"  id="name" name="name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-2 label_style">First Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control"  id="caller_first_name" name="caller_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" /></div>
<label for="inputPassword3" class="col-lg-1 label_style">Relation :</label>
                      <div class="col-lg-2 input_box_first">
                          <!--<input type="text" class="form-control" id="relation" name="relation" maxlength="30" >-->
                          <!--<label class="select-box-lbl">-->
                              <select class="chosen-select form-control"  name="relation" id="relation" >
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
                          <!--</label>-->
                      </div>
                      </div>
</div>
<div class="line-seprator"></div>
<div id="Block4">
<h5  class="div_header" >Incident Details</h5>
<div class="row" style="padding-left:5px;">
<label class="col-sm-2 label_style">No Of Patient :<span style="color:red;">*</span></label>
<div class="col-lg-3  ">
<input type="text" class="validate[required,minSize[1],maxSize[1]] form-control" value="1" id="No_of_Patient" name="No_of_Patient" maxlength="1" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-2 label_style">Chief Complaint :<span style="color:red;">*</span></label>
                      <div class="col-lg-3">
                          <!--<input type="text" class="form-control" id="relation" name="relation" maxlength="30" >-->
                          <!--<label class="select-box-lbl">-->
                              <select class="validate[required] chosen-select form-control"  name="Complaint_type" id="Complaint_type">
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
                          <!--</label>-->
                      </div>
                      </div>
</div>
<div class="line-seprator"></div>
<div id="Block2">
<h5 class="div_header">Patient Details</h5>
<div class="row" style="padding-left:5px;">
<label for="inputPassword3" class="col-lg-2 label_style callerNameText">First Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerFNameText" id="Patient_first_name" name="Patient_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" /></div>
<label for="inputPassword3" class="col-lg-2 label_style">Last Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerNameText"  id="Patient_name" name="Patient_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<label class="col-sm-1">Contact : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[6],maxSize[10]] form-control callerPhone"  id="Patient_phone_no" name="Patient_phone_no" maxlength="10" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-1 label_style">Age: <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box_first">
<input type="text"  style="text-transform: capitalize;" maxlength="2" class="form-control" id="Age" name="Age" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
</div><br>
<div class="row">
<label for="inputPassword3" class="col-lg-1 label_style">Gender: <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box_first">
<select class="validate[required] chosen-select form-control"  name="Gender" id="Gender" >
        <option value="">Gender</option>
        <option value='Male'>Male</option>
        <option value='Female'>Female</option>
        <option value='Other'>Other</option>
    </select>
    </div>
<label for="inputPassword3" class="col-lg-2 label_style">Address : <span style="color:red;">*</span></label>
<div class="col-lg-4 input_box">
        <input maxlength="100" id="google_location" name="google_location" type="text"  class="validate[required] form-control"  />   
        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
</div>

</div>
</div>
<div class="line-seprator"></div>

<div id="Block3">
<h5 class="div_header">Ambulance Details</h5>
<div class="row">
<div class="exPatientListing">
                            <label for="inputPassword3" class="col-lg-2 label_style">Pickup Location : <span style="color:red;">*</span></label>
                          <div class="col-lg-4">
                                  <input maxlength="100" id="google_pickup_location" name="google_pickup_location" type="text"  class="validate[required] form-control"  />   
                                  <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
                          </div>    
                            </div>
                            <div class="exPatientListing">
                            <label for="inputPassword3" class="col-lg-2 label_style">Drop Location : <span style="color:red;">*</span></label>
                          <div class="col-lg-4">
                                  <input maxlength="100" id="google_drop_location" name="google_drop_location" type="text"  class="validate[required] form-control"  />   
                                  <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
                          </div>    
                            </div>
</div>
<br>
<div class="col-lg-12">
<div role="tabpanel" id="Patienttabs"> 
                    <!-- Nav tabs -->
                    <ul id="MainTabs" class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active" id="google_search"><a href="#google" aria-controls="home" role="tab" data-toggle="tab" id="google_search">Google Search</a></li>
                      <li role="presentation" id="manual_search"><a href="#manual" aria-controls="profile" role="tab" data-toggle="tab" id="manual_serach">Manual Search</a></li>
                    </ul>
                    <!-- Tab panes -->
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
                <input type="checkbox" name="selected_amb" id="selected_amb" value="<?php echo $valRecords['amb_no']; ?> " >
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
                                  <label for="inputPassword3" class="col-lg-3 label_style">Select Ambulance :</label>
                                  <div class="col-lg-3 input_box">
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
                                  </div><br>
                                  </div>

                                  <br>
                                  <div class="row" id="ambulance_list"></div>
                                 
                            </div>
                        </div>
                       
                    </div>
                </div></div>
</div>
<div class="line-seprator"></div>
<div id="Block9">
<h5 class="div_header">Payment Details</h5>
<div class="row" style="padding-left:5px;">
<label class="col-sm-1 label_style">View :</label>
<div class="col-lg-2 input_box_first">
<input type="button" class="btn btn-primary" id="submit" value="SUBMIT" onclick="return ViewPaymentDetails();">
</div>
<div id='payment_details' class="row">

</div>
</div>
</div>
<div class="line-seprator"></div>
<div id="Block5">
<h5 class="div_header">Ambulance Schedule Details</h5>
<div class="row" style="padding-left:5px;">
<label class="col-sm-1 label_style">Date :</label>
<div class="col-lg-2 input_box_first">
<input type="text" id="date" name="date" class="form-control datepicker_from">
</div>
<label class="col-sm-1 label_style">Time :</label>
<div class="col-lg-2 input_box_first">
<input type="text" id="time" name="time" class="form-control start validate_time">
</div>
</div>
</div>
<div class="line-seprator"></div>
<div id="col-lg-12 Block6">
<h1 class="div_header">Other Details</h1>
<label for="inputPassword3" class="col-lg-2 label_style">Notes: <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<textarea id="notes" name="notes" rows="4" cols="80"></textarea>
</div>
</div>
<div id="col-lg-12 Block8">
<div class="row">
<div class="col-sm-9 text-center">
<input type="button" class="btn btn-primary" id="submit" value="SUBMIT" onclick="return SubmitDropCall();">
</div>
</div>
</div>
</form>
<?php } ?>
