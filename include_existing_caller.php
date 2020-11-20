<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
  
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    ?>
<form class="form-horizontal" name="ExistingCallerForm" id="ExistingCallerForm" method="post" action="search_existing_caller.php">
        <input type="hidden" name="callerEvent_id" id="callerEvent_id" value="" />
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
            <div class="line-seprator"></div>   
        </form>
<?php
}?>