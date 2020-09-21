<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
  
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    ?>
<form class="form-horizontal" name="ExistingPatientForm" id="ExistingPatientForm" method="post" action="search_existing_patient.php">
        <input type="hidden" name="callerEvent_id" id="callerEvent_id" value="" />
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-4 control-label">HHC No :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="existing_hhc_code" name="existing_hhc_code" />
                </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-4 control-label">Name :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="existing_patient_name" name="existing_patient_name" />
              </div>
            </div>
            
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-4 control-label">Mobile :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="existing_mobile_no" name="existing_mobile_no" />
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-4 control-label">Landline :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="ex_landline_no" name="ex_landline_no" />
              </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-4 control-label">DOB :</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control datepicker_ex" id="existing_dob" name="existing_dob" value="" />
                </div>
<!--              <div class="col-sm-9">
                <div class="col-sm-4">
                  <div class="row">
                    <label class="select-box-lbl">
                      <select class="form-control" id="inputEmail3">
                        <option value="">Month</option>
                        <?php
                        
                        ?>
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
              </div>-->
            </div>
            
              <div class="form-group">
                  <div class="col-sm-12">
                      <input type="button" class="btn btn-primary" id="submit" value="SEARCH" onclick="return SearchPatients();">
                      <!--<button type="submit" class="btn btn-primary" data-toggle="button"> SEARCH </button>-->                      
                  </div>
              </div>
            <div class="line-seprator"></div>   
        </form>
<?php
}?>