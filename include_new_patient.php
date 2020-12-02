<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass();
  
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    if($_POST['SearchByPatients'] && $_POST['SearchByPatients']!="undefined")
        $SearchByPatients=$_POST['SearchByPatients'];
    else
        $SearchByPatients="";
    
    $temp_event_id = $_POST['temp_event_id'];
    $exi_purpose_id = $_POST['Select_purpose_id'];
    
    if($EditedResponseArr['patient_id'])
    {
        $SearchByPatients = $EditedResponseArr['patient_id'];
        $temp_event_id = $EditedResponseArr['event_id'];
        $exi_purpose_id = $EditedResponseArr['purpose_id'];
    }
    if($temp_event_id)
        $temp_event_id = $temp_event_id;
    else
        $temp_event_id = $EID;
    $recArgs['patient_id']=$SearchByPatients;
    //$recArgs['temp_event_id']=$temp_event_id;
    $recArgs['employee_id']=$_SESSION['employee_id'];
    //var_dump($recArgs);
    $recListResponse= $patientsClass->GetPatientById($recArgs);
    
    if($EID || $SearchByPatients)
    {
        $argDoc['doctor_type'] = '1';
        $argDoc['event_id'] = $EID;
        $argDoc['patient_id'] = $SearchByPatients;
        $argDoc['exi_purpose_id'] = $exi_purpose_id;

        $EditedResponseDoctor = $eventClass->GetDoctorConsultant($argDoc);
        unset($argDoc['doctor_type']);
        $argCons['doctor_type'] = '2';
        $argCons['event_id'] = $EID;
        $argCons['patient_id'] = $SearchByPatients;
        $argCons['exi_purpose_id'] = $exi_purpose_id;
        $EditedResponseConsultant = $eventClass->GetDoctorConsultant($argCons);
        unset($argDoc['doctor_type']);         
    }
    ?>
        <form class="form-horizontal" name="NewPatientForm" id="NewPatientForm"  method="post" action="event_ajax_process.php?action=generateHHCno">
            <input type="hidden" class="prv_purpose_id" name="prv_purpose_id" id="prv_purpose_id" value="<?php echo $exi_purpose_id;?>" />
            <input type="hidden" name="temp_event_id" id="temp_event_id" value="<?php echo $temp_event_id; ?>" />
            <input type="hidden" name="hospital_id" id="hospital_id" value="<?php  if(!empty($_SESSION['employee_hospital_id'])) { echo $_SESSION['employee_hospital_id']; } ?>" />
                <?php if($recListResponse['hhc_code'])
                    {
                ?>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">HHC Code:</label>
                    <div class="col-sm-8">
                        <input type="text" maxlength="20" class="validate[required] form-control" readonly id="exist_hhc_code" name="exist_hhc_code" value="<?php if($recListResponse['hhc_code']) echo $recListResponse['hhc_code']; else echo $_POST['exist_hhc_code']; ?>" />
                    </div>
                </div>
                <?php
                    }
                ?>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Last Name: <span style="color:red;">*</span></label>
                    <div class="col-sm-8">
                      <input type="text" maxlength="30" style="text-transform: capitalize;" class="validate[required] form-control" id="patient_name" name="patient_name" value="<?php if($recListResponse['name']) echo $recListResponse['name']; else echo $_POST['patient_name']; ?>" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">First Name: <span style="color:red;">*</span></label>
                    <div class="col-sm-8">
                      <input type="text" maxlength="30" style="text-transform: capitalize;" class="validate[required] form-control" id="patient_first_name" name="patient_first_name" value="<?php if($recListResponse['first_name']) echo $recListResponse['first_name']; else echo $_POST['patient_first_name']; ?>" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Middle Name:</label>
                    <div class="col-sm-8">
                      <input type="text" maxlength="30" style="text-transform: capitalize;" class="form-control" id="patient_middle_name" name="patient_middle_name" value="<?php if($recListResponse['middle_name']) echo $recListResponse['middle_name']; else echo $_POST['patient_middle_name']; ?>" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
                    </div>
                </div>
                 <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Age:</label>
                    <div class="col-sm-8">
                      <input type="text" maxlength="30" style="text-transform: capitalize;" class="form-control" id="Age" name="Age" value="<?php if($recListResponse['Age']) echo $recListResponse['Age']; else echo $_POST['Age']; ?>" />
                    </div>
                </div>
				<div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Gender:</label>
                    <div class="col-sm-8">
                     <!-- <input type="text" maxlength="30" style="text-transform: capitalize;" class="form-control" id="Gender" name="Gender" value="<?php if($recListResponse['Gender']) echo $recListResponse['Gender']; else echo $_POST['Gender']; ?>" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
                    -->
					<select class="chosen-select form-control"  name="Gender" id="Gender">
                        <option value="">Gender</option>
						 <option value="MALE">Male</option>
						  <option value="FEMALE">Female</option>
					</select>
					</div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Residential Address: <span style="color:red;">*</span></label>
                    <div class="col-sm-8">
                        <textarea maxlength="100" style="text-transform: capitalize;" class="validate[required] form-control" id="residential_address" name="residential_address" onkeyup="if (/[^a-zA-Z0-9 ,-/()]/g.test(this.value)) this.value = this.value.replace(/[^a-zA-Z0-9 ,-/()]/g,'')"><?php if($recListResponse['residential_address']) echo $recListResponse['residential_address']; else echo $_POST['residential_address']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                	<div class="col-sm-4"></div>
                    <div class="col-sm-8">
                    <label for="inputPassword3" class="control-label"> <input type="checkbox" name="sameaddress" id="sameaddress" value="1" onclick="return checkAddress();" > &nbsp;Permanent address same as residential address</label>
                    </div>
                </div>
            
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Permanent Address:</label>
                    <div class="col-sm-8">
                      <textarea maxlength="100" style="text-transform: capitalize;" class="form-control" id="permanant_address" name="permanant_address" onkeyup="if (/[^a-zA-Z0-9 ,-/()]/g.test(this.value)) this.value = this.value.replace(/[^a-zA-Z0-9 ,-/()]/g,'')"><?php if($recListResponse['permanant_address']) echo $recListResponse['permanant_address']; else echo $_POST['permanant_address']; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Location : <span style="color:red;">*</span></label>
                    <div class="col-sm-8">
                        <input maxlength="100" id="google_location" name="google_location" type="text" value="<?php if($recListResponse['locationNm']) echo $recListResponse['locationNm']; else echo $_POST['locationNm']; ?>" class="validate[required] form-control"  />   
                        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
                    </div>
                </div>
                <div class="form-group margintop25">
                    <label for="inputPassword3" class="col-sm-4 control-label">City:</label>
                    <div class="col-sm-8">
                        <!--<label class="select-box-lbl">-->
                            
                            <select class="chosen-select form-control"  id="city" name="city" >
                              <option value="">City</option>
                              <?php
                              
                              $Resultcitylist = $eventClass->city_list($arr);    
                                                
                              foreach($Resultcitylist as $key=>$valRecords)
                              {
                                  //if($Resultcitylist['city_id'])
                                  if($recListResponse['city_id'] == $valRecords['city_id'])
                                      echo '<option value="'.$valRecords['city_id'].'" selected="selected">'.$valRecords['city_name'].'</option>';
                                  else
                                      echo '<option value="'.$valRecords['city_id'].'" >'.$valRecords['city_name'].'</option>';
                              }
                              ?>
                            </select>
                            <!--</label>-->
                    </div>
                </div>
                <div class="form-group margintop25">
                    <label for="inputPassword3" class="col-sm-4 control-label">Area:</label>
                    <div class="col-sm-8">
                        <!--<label class="select-box-lbl">-->
                            
                            <select class="chosen-select form-control"  id="area" name="area" >
                              <option value="">Area</option>
                              <?php
                              
                              $Resultcitylist = $eventClass->area_list($arr);                          
                              foreach($Resultcitylist as $key=>$valRecords)
                              {
                                if($recListResponse['location_id'] == $valRecords['location_id'])
                                      echo '<option value="'.$valRecords['location_id'].'" selected="selected">'.$valRecords['location'].'</option>';
                                  else
                                      echo '<option value="'.$valRecords['location_id'].'" >'.$valRecords['location'].'</option>';
                              }
                              ?>
                            </select>
                            <!--</label>-->
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Sub Area: </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="sub_area" name="sub_area"  maxlength="50" value="<?php if($recListResponse['sub_location']) echo $recListResponse['sub_location']; else echo $_POST['sub_location']; ?>" />
                    </div>
                </div>
            <!-- Below code is hide beacuse of google location list          -->  
<!--                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Location: <span style="color:red;">*</span></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="patient_location" name="patient_location">
                        <label class="select-box-lbl" id="LOCATIONList">
                            <span id="LOCATIONList">
                                <select class="validate[required] chosen-select form-control" id="patient_location" name="patient_location" onchange="return ChangeLocation(this.value,'location');">
                                  <option value="">Location</option>
                                  <?php
                                  /*$arr['list'] = 'all';
                                  $ResultDoctors = $eventClass->LocationList($arr);                          
                                  foreach($ResultDoctors as $key=>$valRecords)
                                  {
                                    if($recListResponse['locationNm'] == $valRecords['location'])
                                        echo '<option value="'.$valRecords['location_id'].'" selected="selected">'.$valRecords['location'].'</option>';
                                    else
                                        echo '<option value="'.$valRecords['location_id'].'">'.$valRecords['location'].'</option>';
                                  }*/
                                  ?>
                                </select>
                            </span>
                        </label>
                    </div>
                </div>-->
          
<!--                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Pin Code:</label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="patient_pincode" name="patient_pincode" onchange="return ChangeLocation(this.value,'pin');">
                        <label class="select-box-lbl" id="PINCODEList">
                            <select class="form-control" id="patient_pin_code" name="patient_pin_code" onchange="return ChangeLocation(this.value,'pin');">
                              <option value="">Pin Code</option>
                                <?php
                                    /*$arr['list'] = 'all';
                                    $arr['uniquePINs']='1';
                                    $ResultDoctors = $eventClass->LocationList($arr);                          
                                    foreach($ResultDoctors as $key=>$valRecords)
                                    {
                                        if($recListResponse['LocationPinCode'] == $valRecords['pin_code'])
                                            echo '<option value="'.$valRecords['pin_code'].'" selected="selected">'.$valRecords['pin_code'].'</option>';
                                        else
                                            echo '<option value="'.$valRecords['pin_code'].'">'.$valRecords['pin_code'].'</option>';
                                    }*/
                                   // unset($arr['uniquePINs']);
                                ?>
                            </select>
                        </label>
                    </div>
                </div>-->
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Contact: <span style="color:red;">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" id="patient_mobile_no" name="patient_mobile_no" value="<?php if($recListResponse['mobile_no']) echo $recListResponse['mobile_no']; else echo $_POST['patient_mobile_no']; ?>" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Email Id:<span style="color:red;">*</span></label>
                    <div class="col-sm-8">
                      <input maxlength="50" type="text" class="validate[required],validate[custom[email]] form-control" id="patient_email_id" name="patient_email_id" value="<?php if($recListResponse['email_id']) echo $recListResponse['email_id']; else echo $_POST['patient_email_id']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Landline:</label>
                    <div class="col-sm-8">
                      <input maxlength="15" type="text" class="form-control" id="patient_phone_no" name="patient_phone_no" value="<?php if($recListResponse['phone_no']) echo $recListResponse['phone_no']; else echo $_POST['patient_phone_no']; ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">DOB:</label>
                    <div class="col-sm-8">
                        <input maxlength="12" type="text" class="form-control datepicker" id="patientdob" name="patientdob" value="<?php if(!empty($recListResponse['dob']) && $recListResponse['dob'] !='0000-00-00') echo date('d-m-Y',strtotime($recListResponse['dob'])); else { echo $_POST['patientdob']; } ?>" />
                    </div>
                </div>
                <div class="form-group margintop25">
                    <label for="inputPassword3" class="col-sm-4 control-label">Ref.Hospital Name:</label>
                    <div class="col-sm-8">
                        <!--<label class="select-box-lbl">-->
                            
                            <select class="validate[required] chosen-select form-control"  id="ref_hos_id" name="ref_hos_id" onchange="return SelctedOther(this.value,1);">
                              <option value="">Ref hospital Name</option>
                              <?php
                              
                              $Resulthos = $eventClass->Ref_Hospital_Name();                          
                              foreach($Resulthos as $key=>$valRecords)
                              {
                                  if($EditedResponseDoctor['hospital_id'] == $valRecords['hospital_id'])
                                      echo '<option value="'.$valRecords['hospital_id'].'" selected="selected">'.$valRecords['hospital_name'].'</option>';
                                  else
                                      echo '<option value="'.$valRecords['hospital_id'].'" >'.$valRecords['hospital_name'].'</option>';
                              }
                              echo '<option value="'.'Other'.'" >'.'Other'.'</option>';
                              ?>
                            </select>
                            <!--</label>-->
                    </div>
                </div>
                <div id="Other_Hos_Details" >
                  
                  
                </div>
<!--                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-3 control-label">DOB :</label>
                    <div class="col-sm-9">
                      <div class="col-sm-4">
                        <div class="row">
                          <label class="select-box-lbl">
                            <select class="form-control" id="inputEmail3">
                              <option>Month</option>
                              <option></option>
                              <option></option>
                            </select>
                          </label>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <label class="select-box-lbl">
                          <select class="form-control" id="inputEmail3">
                            <option>Day</option>
                            <option></option>
                            <option></option>
                          </select>
                        </label>
                      </div>
                      <div class="col-sm-4">
                        <div class="row">
                          <label class="select-box-lbl">
                            <select class="form-control" id="inputEmail3">
                              <option>Year</option>
                              <option></option>
                              <option></option>
                            </select>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>-->
                <div class="form-group margintop25">
                    <label for="inputPassword3" class="col-sm-4 control-label">Family Doctor:</label>
                    <div class="col-sm-8">
                        <!--<label class="select-box-lbl">-->
                            
                            <select class="chosen-select form-control"  id="doctor_id" name="doctor_id" onchange="return SelctedDoctors(this.value,1);">
                              <option value="">Family Doctor</option>
                              <?php
                              $arr['type'] = '1';
                              $ResultDoctors = $eventClass->DoctorsConsultantList($arr);                          
                              foreach($ResultDoctors as $key=>$valRecords)
                              {
                                  if($EditedResponseDoctor['doctor_consultant_id'] == $valRecords['doctors_consultants_id'])
                                      echo '<option value="'.$valRecords['doctors_consultants_id'].'" selected="selected">'.$valRecords['name']." ".$valRecords['first_name'].'</option>';
                                  else
                                      echo '<option value="'.$valRecords['doctors_consultants_id'].'" >'.$valRecords['name']." ".$valRecords['first_name'].'</option>';
                              }
                              ?>
                            </select>
                            <!--</label>-->
                    </div>
                </div>
                <div id="doctorsDetails" >
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Contact No:</label>
                    <div class="col-sm-8">
                        <input type="text" maxlength="15" class="form-control" id="familyDocmobile_no" name="familyDocmobile_no" value="<?php if($EditedResponseDoctor['mobile_no']) echo $EditedResponseDoctor['mobile_no']; else echo $_POST['familyDocmobile_no']; ?>" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Email id:</label>
                    <div class="col-sm-8">
                        <input type="text" maxlength="40" class="form-control" id="familyDocemail_id" name="familyDocemail_id" value="<?php if($EditedResponseDoctor['email_id']) echo $EditedResponseDoctor['email_id']; else echo $_POST['familyDocemail_id']; ?>" />
                    </div>
                  </div>
                </div>
                <div class="form-group margintop25">
                    <label for="inputPassword3" class="col-sm-4 control-label">Consultant:</label>
                    <div class="col-sm-8">
                        <!--<label class="select-box-lbl">-->
                            <select class="chosen-select form-control" id="consultant_id" name="consultant_id" onchange="return SelctedDoctors(this.value,2);">
                                <option value="">Consultant</option>
                                <?php
                                    $arr['type'] = '2';
                                    $ResultDoctors = $eventClass->DoctorsConsultantList($arr);                          
                                    foreach($ResultDoctors as $key=>$valRecords)
                                    {
                                        if($EditedResponseConsultant['doctor_consultant_id'] == $valRecords['doctors_consultants_id'])
                                            echo '<option value="'.$valRecords['doctors_consultants_id'].'" selected="selected">'.$valRecords['name']." ".$valRecords['first_name'].'</option>';
                                        else
                                            echo '<option value="'.$valRecords['doctors_consultants_id'].'">'.$valRecords['name']." ".$valRecords['first_name'].'</option>';
                                    }
                                ?>
                            </select>
                        <!--</label>-->
                    </div>
                </div>
                <div id="consultantDetails" >
                  <div class="form-group">
                    <label for="inputPassword3" class="col-sm-4 control-label">Contact No:</label>
                    <div class="col-sm-8">
                      <input type="text" maxlength="15" class="form-control" id="consultantMobile_no" name="consultantMobile_no" value="<?php if($EditedResponseConsultant['mobile_no']) echo $EditedResponseConsultant['mobile_no']; else echo $_POST['consultantMobile_no']; ?>" />
                    </div>
                  </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">Email id:</label>
                        <div class="col-sm-8">
                          <input type="text" maxlength="40" class="form-control" id="consultantEmail_id" name="consultantEmail_id" value="<?php if($EditedResponseConsultant['email_id']) echo $EditedResponseConsultant['email_id']; else echo $_POST['consultantEmail_id']; ?>" />
                        </div>
                    </div>
                </div>
                <?php 
                $dispShow = 'yes';
                if($exi_purpose_id == '7' || $exi_purpose_id == '3' || $exi_purpose_id == '4' || $exi_purpose_id == '5')                    
                {
                    $dispShow = 'No';
                }
                //echo $exi_purpose_id;
                if($dispShow == 'yes')
                {
                ?>
                    <div class="form-group" id="NewPatientButton">
                        <div class="col-sm-12">
                            <input type="button" class="btn btn-primary" name="patientSubmits" id="patientSubmits" value="<?php if($recListResponse['hhc_code']) echo 'UPDATE'; else echo 'GENERATE HHC NO';?>" onclick="return generate_hhc_no();">
                            <!--<button type="submit" class="btn btn-primary" data-toggle="button" onclick="return generate_hhc_no();"> GENERATE HHC NO</button>-->                      
                        </div>
                    </div>
                <?php } ?>
                  <div class="line-seprator"></div>   
                </form> 
<?php
}?>