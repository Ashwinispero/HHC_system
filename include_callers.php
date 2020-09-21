<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
  
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    //print_r($EditedResponseArr);
    if($EditedResponseArr['consultant_id'])
    {
        $GetConsultantSql="SELECT doctors_consultants_id,email_id,mobile_no FROM sp_doctors_consultants WHERE doctors_consultants_id='".$EditedResponseArr['consultant_id']."'";
        $Consultant=$db->fetch_array($db->query($GetConsultantSql));
    }
    ?>
<form class="form-horizontal" name="CallerForm" id="CallerForm" method="post" action="event_ajax_process.php?action=submitCaller">
    <input type="hidden" name="Edit_CallerId" id="Edit_CallerId" value="<?php echo $EditedResponseArr['caller_id'];?>" >
    <input type="hidden" name="Edit_event_id" id="Edit_event_id" value="<?php echo $EditedResponseArr['event_id'];?>" >
    <input type="hidden" name="eventIDForClosure" id="eventIDForClosure" value="<?php echo $EditedResponseArr['event_id'];?>" >
                 <!--<div class="form-group">
                  <div class="col-sm-12">
                    <label class="select-box-lbl">
                        <select class="validate[required] form-control " id="hospital_id" >
                          <option value="">Hospital List</option>
                          <?php/*
                          $selectRecord = "SELECT hospital_id,hospital_name FROM sp_hospitals";
                          $AllRrecord = $db->fetch_all_array($selectRecord);
                          foreach($AllRrecord as $key=>$valRecords)
                          {
                             
                                  echo '<option value="'.$valRecords['hospital_id'].'">'.$valRecords['hospital_name'].'</option>';
                          }*/
                          ?>
                      </select>
                        
                    </label>
                  </div>
                </div>-->
				
				<div class="form-group">
                  <div class="col-sm-12">
                    <label class="select-box-lbl">
                        <select class="validate[required] form-control " id="purpose_id" <?php if($EditedResponseArr['purpose_id']) echo 'disabled="true" ';?> name="purpose_id" onchange="return ChangePurposeCall(this.value);">
                          <option value="">Purpose of Call</option>
                          <?php
                          $selectRecord = "SELECT purpose_id,name FROM sp_purpose_call WHERE status='1'";
                          $AllRrecord = $db->fetch_all_array($selectRecord);
                          foreach($AllRrecord as $key=>$valRecords)
                          {
                              if($EditedResponseArr['purpose_id'] == $valRecords['purpose_id'])
                                  echo '<option value="'.$valRecords['purpose_id'].'" selected="selected" >'.$valRecords['name'].'</option>';
                              else
                                  echo '<option value="'.$valRecords['purpose_id'].'">'.$valRecords['name'].'</option>';
                          }
                          ?>
                      </select>
                        <?php if($EditedResponseArr['purpose_id']) echo '<input type="hidden" name="purpose_id" value="'.$EditedResponseArr['purpose_id'].'">';?>
                    </label>
                  </div>
                </div>
                <div class="line-seprator"></div>
                <h4 class="section-head"><span><img src="images/coller-icon.png" width="29" height="29"></span>Caller Details</h4>           
                <div id="CallerDivStart">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-4 control-label">Contact No:<span style="color:red;">*</span> </label>
                      <div class="col-sm-8">
                          <input type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-4 control-label">Last Name : <span style="color:red;">*</span></label>
                      <div class="col-sm-8">
                          <input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerNameText" value="<?php if($EditedResponseArr['caller_last_name']) echo $EditedResponseArr['caller_last_name']; else echo $_POST['caller_last_name'];  ?>" id="name" name="name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-4 control-label">First Name : <span style="color:red;">*</span></label>
                      <div class="col-sm-8">
                          <input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerFNameText" value="<?php if($EditedResponseArr['caller_first_name']) echo $EditedResponseArr['caller_first_name']; else echo $_POST['caller_first_name'];  ?>" id="caller_first_name" name="caller_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-4 control-label">Middle Name : </label>
                      <div class="col-sm-8">
                          <input type="text" style="text-transform: capitalize;" class="form-control callerMNameText" value="<?php if($EditedResponseArr['caller_middle_name']) echo $EditedResponseArr['caller_middle_name']; else echo $_POST['caller_middle_name'];  ?>" id="caller_middle_name" name="caller_middle_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-4 control-label">Relation :</label>
                      <div class="col-sm-8">
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
                    </div>
                </div>
                <div id="callerJobclosureDiv" style="display:none;">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Select Professional</label>
                      <div class="col-sm-9">
                          <!--<label class="select-box-lbl">-->
                            <select data-placeholder="Choose a Professional" tabindex="2" class="chosen-select form-control profchoserID" name="choose_professional_id" id="choose_professional_id" >
                                <option value="">Choose Professional</option>
                                <?php
                                    $selectRecord = "select service_professional_id,name,first_name,middle_name,professional_code from sp_service_professionals where status='1' ORDER BY name ASC";
                                    $AllRrecord = $db->fetch_all_array($selectRecord);
                                    foreach($AllRrecord as $key=>$valRecords)
                                    {
                                        if($EditedResponseArr['professional_id'] == $valRecords['service_professional_id'])
                                            echo '<option value="'.$valRecords['service_professional_id'].'" selected="selected" >'.$valRecords['name']." ".$valRecords['first_name']." ".$valRecords['middle_name'].'</option>';
                                        else
                                            echo '<option value="'.$valRecords['service_professional_id'].'">'.$valRecords['name']." ".$valRecords['first_name']." ".$valRecords['middle_name'].'</option>';
                                    }
                                ?>
                            </select>
                          <!--</label>-->
                      </div>
                    </div>
                </div>
                <div id="callerConsultantDiv" style="display: none;">
                    <div class="form-group margintop25">
                        <label for="inputPassword3" class="col-sm-3 control-label">Consultant:</label>
                        <div class="col-sm-9">
<!--                            <label class="select-box-lbl">-->
                                <select class="chosen-select form-control" id="caller_consultant_id" name="caller_consultant_id" onchange="return SelctedConsultant(this.value,2);">
                                    <option value="">Consultant</option>
                                    <?php
                                        $arr['type'] = '2';
                                        $ResultDoctors = $eventClass->DoctorsConsultantList($arr);                          
                                        foreach($ResultDoctors as $key=>$valRecords)
                                        {
                                            if($EditedResponseArr['consultant_id'] == $valRecords['doctors_consultants_id'])
                                                echo '<option value="'.$valRecords['doctors_consultants_id'].'" selected="selected">'.$valRecords['name'].'</option>';
                                            else
                                                echo '<option value="'.$valRecords['doctors_consultants_id'].'">'.$valRecords['name'].'</option>';
                                        }
                                    ?>
                                </select>
<!--                            </label>-->
                        </div>
                    </div>
                    <div id="CallerconsultantDetails" >
                        <div class="form-group">
                          <label for="inputPassword3" class="col-sm-3 control-label">Contact No:</label>
                          <div class="col-sm-9">
                                <input type="text" class="form-control" id="consultantMobile_no" name="consultantMobile_no" value="<?php if($Consultant['mobile_no']) echo $Consultant['mobile_no']; else echo $_POST['consultantMobile_no']; ?>">
                          </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Email id:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="consultantEmail_id" name="consultantEmail_id" value="<?php if($Consultant['email_id']) echo $Consultant['email_id']; else echo $_POST['consultantEmail_id']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-3 control-label"></label>
                  <div class="col-sm-9 text-right">
                    <input type="button" class="btn btn-primary" id="submit" value="SUBMIT" onclick="return SubmitCaller();">
                  </div>
                </div>
            </form>
<?php
}?>