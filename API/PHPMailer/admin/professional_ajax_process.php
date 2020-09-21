<?php
require_once 'inc_classes.php';
require_once '../classes/professionalsClass.php';
require_once '../classes/commonClass.php';
$professionalsClass=new professionalsClass();
$commonClass=new commonClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
require_once "../classes/functions.php";
?>



<?php
if(!class_exists('AbstractDB'))
        require_once '../classes/AbstractDB.php';
if($_REQUEST['action']=='vw_add_professional')
{
    // Getting Professional Details
    $arr['service_professional_id']=$_REQUEST['service_professional_id'];
    $ProfDtls=$professionalsClass->GetProfessionalById($arr);
    
    //echo '<pre>';
    // print_r($ProfDtls);
    // echo '</pre>';

    // Getting Professional other details
    $ProfOtherDtls=$professionalsClass->GetProfessionalOtherDtlsById($arr);
    // Get All Location
    $LocationList=$commonClass->GetAllLocations();
    // Get All Services 
    $ServiceList=$commonClass->GetAllServices();  
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($ProfDtls)) { echo "Edit"; } else { echo "Add"; } ?> Professional </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_professional" id="frm_add_professional" method="post" action ="professional_ajax_process.php?action=add_professional" autocomplete="off">
            <div class="scrollbars">
                <div class="editform">
                    <label>Select Professional Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="reference_type" id="reference_type" class="validate[required]" onchange="return getProfOtherDtls(this.value);">
                                    <option value=""<?php if($_POST['reference_type']=='') { echo 'selected="selected"'; } else if($ProfDtls['reference_type']=='') { echo 'selected="selected"'; } ?>>Professional Type</option>
                                    <option value="1"<?php if($_POST['reference_type']=='1') { echo 'selected="selected"'; } else if($ProfDtls['reference_type']=='1') { echo 'selected="selected"'; }?>>PROFESSIONAL</option>
                                    <option value="2"<?php if($_POST['reference_type']=='2') { echo 'selected="selected"'; } else if($ProfDtls['reference_type']=='2') { echo 'selected="selected"'; }?>>VENDOR</option>
                                </select>
                            </label>
                        </div>
                </div>
                <div class="editform">
                    <label>Title <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="title" id="title" class="validate[required]">
                                    <option value=""<?php if($_POST['title']=='') { echo 'selected="selected"'; } else if($ProfDtls['title']=='') { echo 'selected="selected"'; } ?>>Title</option>
                                    <option value="Dr"<?php if($_POST['title']=='Dr') { echo 'selected="selected"'; } else if($ProfDtls['title']=='Dr') { echo 'selected="selected"'; }?>>Dr</option>
                                    <option value="Mr"<?php if($_POST['title']=='Mr') { echo 'selected="selected"'; } else if($ProfDtls['title']=='Mr') { echo 'selected="selected"'; }?>>Mr</option>
                                    <option value="Mrs"<?php if($_POST['title']=='Mrs') { echo 'selected="selected"'; } else if($ProfDtls['title']=='Mrs') { echo 'selected="selected"'; }?>>Mrs</option>
                                    <option value="Sis"<?php if($_POST['title']=='Sis') { echo 'selected="selected"'; } else if($ProfDtls['title']=='Sis') { echo 'selected="selected"'; }?>>Sister</option>
                                    <option value="Bro"<?php if($_POST['title']=='Bro') { echo 'selected="selected"'; } else if($ProfDtls['title']=='Bro') { echo 'selected="selected"'; }?>>Brother</option>
                                    <option value="Ms"<?php if($_POST['title']=='Ms') { echo 'selected="selected"'; } else if($ProfDtls['title']=='Ms') { echo 'selected="selected"'; }?>>Miss</option>
                                </select>
                            </label>
                        </div>
                </div>
                <div class="editform">
                    <label>Last Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="service_professional_id" id="service_professional_id" value="<?php echo $arr['service_professional_id']; ?>" />
                        <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])) { echo $_POST['name']; } else if(!empty($ProfDtls['name'])) { echo $ProfDtls['name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>First Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="first_name" id="first_name" value="<?php if(!empty($_POST['first_name'])) { echo $_POST['first_name']; } else if(!empty($ProfDtls['first_name'])) { echo $ProfDtls['first_name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Middle Name</label>
                    <div class="value">
                        <input type="text" name="middle_name" id="middle_name" value="<?php if(!empty($_POST['middle_name'])) { echo $_POST['middle_name']; } else if(!empty($ProfDtls['middle_name'])) { echo $ProfDtls['middle_name']; } else { echo ""; } ?>" class="validate[maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Email Address</label>
                    <div class="value">
                        <input type="text" name="email_id" id="email_id" value="<?php if(!empty($_POST['email_id'])) { echo $_POST['email_id']; } else if(!empty($ProfDtls['email_id'])) { echo $ProfDtls['email_id']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Phone</label>
                    <div class="value">
                        <input type="text" name="phone_no" id="phone_no" value="<?php if(!empty($_POST['phone_no'])) { echo $_POST['phone_no']; } else if(!empty($ProfDtls['phone_no'])) { echo $ProfDtls['phone_no']; }  else { echo ""; } ?>" class="validate[minSize[10],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Mobile <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($ProfDtls['mobile_no'])) { echo $ProfDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Birth Date</label>
                    <div class="value">
                        <input type="text" name="dob" id="dob" value="<?php if(!empty($_POST['dob'])) { echo date('Y-m-d',strtotime($_POST['dob'])); } if(!empty($ProfDtls['dob']) && $ProfDtls['dob'] !='0000-00-00') { echo date('d-m-Y',strtotime($ProfDtls['dob'])); }  else { echo ""; } ?>" class="form-control datepicker" style="width:100% !important;" />
                    </div>
                </div>                
                <div class="editform list_select">
                    <label>Assign Services <span class="required">*</span></label>
                     <div class="value dropdown">
                         <div class="dd">
                            <select name="service_id[]" id="service_id" class="col-lg-4 paddingR0 dropdown multiselect form-control" multiple="multiple">
                                <?php
                                    if(!empty($ServiceList))
                                    {
                                        foreach($ServiceList as $key=>$valService)
                                        {
                                            $sql_prof_service="SELECT service_id FROM sp_professional_services WHERE service_id='".$valService['service_id']."' and service_professional_id='".$_REQUEST['service_professional_id']."'"; 
                                            if($db->num_of_rows($db->query($sql_prof_service)))
                                                echo '<option value="'.$valService['service_id'].'" selected="selected">'.$valService['service_title'].'</option>';
                                            else if($_POST['service_id'] == $valService['service_id'])
                                                echo '<option value="'.$valService['service_id'].'" selected="selected">'.$valService['service_title'].'</option>';
                                            else
                                                echo '<option value="'.$valService['service_id'].'">'.$valService['service_title'].'</option>';
                                        }   
                                    }
                                    else 
                                    {
                                        echo '';
                                    }                          
                                ?>
                            </select>
                        </div>
                     </div>
                </div>
                <div class="editform">
                    <label>Home Address</label>
                    <div class="value">
                        <textarea name="address" id="address" class="form-control" maxlength="160" style="width: 265px; height: 100px;"><?php if(!empty($_POST['address'])) { echo $_POST['address']; } else if(!empty($ProfDtls['address'])) { echo $ProfDtls['address']; }  else { echo ""; } ?></textarea>
                    </div>
                </div>
<!--                <div class="editform">
                    <label>Home Location <span class="required">*</span></label>
                    <div class="value dropdown">
                        <label>
                            <select name="location_id_home" id="location_id_home" class="validate[required]">
                                <option value=""<?php if($_POST['location_id_home']=='') { echo 'selected="selected"'; } else if($ProfDtls['location_id_home']=='') { echo 'selected="selected"'; } ?>>Home Location</option>
                                <?php
                                  /*  foreach($LocationList as $key=>$valLocation)
                                    {
                                        if($ProfDtls['location_id_home'] == $valLocation['location_id'])
                                            echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                        else if($_POST['location_id_home'] == $valLocation['location_id'])
                                            echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                        else
                                            echo '<option value="'.$valLocation['location_id'].'">'.$valLocation['location'].'</option>';
                                    }   */                         
                                ?>
                            </select>
                        </label>
                    </div>
                </div>-->
                <div class="editform">
                    <label>Home Location <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="google_home_location" id="google_home_location" class="validate[required] form-control" value="<?php if(!empty($_POST['google_home_location'])) { echo $_POST['google_home_location']; } else  echo $ProfDtls['google_home_location']; ?>" maxlength="160" >
                    </div>
                </div>
                <div class="editform">
                    <label>Work Address </label>
                    <div class="value">
                        <textarea name="work_address" id="work_address" class="form-control" maxlength="160" style="width: 265px; height: 100px;"><?php if(!empty($_POST['work_address'])) { echo $_POST['work_address']; } else if(!empty($ProfDtls['work_address'])) { echo $ProfDtls['work_address']; }  else { echo ""; } ?></textarea>
                    </div>
                </div>
<!--                <div class="editform">
                    <label>Work Location <span class="required">*</span></label>
                    <div class="value dropdown">
                        <label>
                            <select name="location_id" id="location_id" class="validate[required]">
                                <option value=""<?php if($_POST['location_id']=='') { echo 'selected="selected"'; } else if($ProfDtls['location_id']=='') { echo 'selected="selected"'; } ?>>Location</option>
                                <?php
                                    /*foreach($LocationList as $key=>$valLocation)
                                    {
                                        if($ProfDtls['location_id'] == $valLocation['location_id'])
                                            echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                        else if($_POST['location_id'] == $valLocation['location_id'])
                                            echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                        else
                                            echo '<option value="'.$valLocation['location_id'].'">'.$valLocation['location'].'</option>';
                                    }  */                          
                                ?>
                            </select>
                        </label>
                    </div>
                </div>-->
                <div class="editform">
                    <label>Work Location <span class="required">*</span></label>
                    <div class="value" id="pac-container">
                        <input type="text" name="google_work_location" id="google_work_location" class="validate[required] form-control" value="<?php if(!empty($_POST['google_work_location'])) { echo $_POST['google_work_location']; } else  echo $ProfDtls['google_work_location']; ?>" maxlength="160" >
                        <input type="hidden" name="selcGog_Location" id="selcGog_Location" value="" >
                        
                    </div>
                </div>
                <div class="editform">
                    <label>Set Location </label>
                    <div class="value">
                        <input <?php if($ProfDtls['set_location'] == '1') echo 'checked="checked"';?> type="radio" name="set_location" id="set_location_h" value="1" style="width: 15px !important; float: none;" /> From Home
                        <input <?php if($ProfDtls['set_location'] != '1') echo 'checked="checked"';?> type="radio" name="set_location" id="set_location" value="2" style="width: 15px !important; float: none;"  /> From Work
                    </div>
                </div>
                
                <div class="editform">
                    <label>Work Phone</label>
                    <div class="value">
                        <input type="text" name="work_phone_no" id="work_phone_no" value="<?php if(!empty($_POST['work_phone_no'])) { echo $_POST['work_phone_no']; } else if(!empty($ProfDtls['work_phone_no'])) { echo $ProfDtls['work_phone_no']; }  else { echo ""; } ?>" class="validate[minSize[10],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Work Email Address</label>
                    <div class="value">
                        <input type="text" name="work_email_id" id="work_email_id" value="<?php if(!empty($_POST['work_email_id'])) { echo $_POST['work_email_id']; } else if(!empty($ProfDtls['work_email_id'])) { echo $ProfDtls['work_email_id']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" onblur="javascript:return chkEmails();" />
                        <div id="form_error" class="formErrorSelf"></div>
                    </div>
                </div>
                
                
                <div class="ProfOtherContent">
                    <div class="editform cls_prof">
                        <label>Qualification</label>
                        <div class="value">
                            <input type="hidden" name="detail_id" id="detail_id" value="<?php echo $ProfOtherDtls['detail_id']; ?>" />
                            <input type="text" name="qualification" id="qualification" value="<?php if(!empty($_POST['qualification'])) { echo $_POST['qualification']; } else if(!empty($ProfOtherDtls['qualification'])) { echo $ProfOtherDtls['qualification']; } else { echo ""; } ?>" class="validate[maxSize[70]] form-control" onkeyup="if (/[^A-Za-z.(), ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z.(), ]/g,'')"  maxlength="70" style="width:100% !important;" />
                        </div>
                    </div>
                    <div class="editform cls_prof">
                        <label>Specialization</label>
                        <div class="value">
                            <input type="text" name="specialization" id="specialization" value="<?php if(!empty($_POST['specialization'])) { echo $_POST['specialization']; } else if(!empty($ProfOtherDtls['specialization'])) { echo $ProfOtherDtls['specialization']; } else { echo ""; } ?>" class="validate[maxSize[70]] form-control" onkeyup="if (/[^A-Za-z.(), ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z.(), ]/g,'')"  maxlength="70" style="width:100% !important;" />
                        </div>
                    </div>
                    <div class="editform cls_prof">
                        <label>Skills</label>
                        <div class="value">
                            <input type="text" name="skill_set" id="skill_set" value="<?php if(!empty($_POST['skill_set'])) { echo $_POST['skill_set']; } else if(!empty($ProfOtherDtls['skill_set'])) { echo $ProfOtherDtls['skill_set']; } else { echo ""; } ?>" class="validate[maxSize[160]] form-control" onkeyup="if (/[^A-Za-z.(), ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z.(), ]/g,'')"  maxlength="160" style="width:100% !important;" />
                        </div>
                    </div>
                    <div class="editform cls_prof">
                        <label>Work Experience</label>
                        <div class="value">
                            <input type="text" name="work_experience" id="work_experience" value="<?php if(!empty($_POST['work_experience'])) { echo $_POST['work_experience']; } else if(!empty($ProfOtherDtls['work_experience'])) { echo $ProfOtherDtls['work_experience']; } else { echo ""; } ?>" class="validate[maxSize[70]] form-control" onkeyup="if (/[^A-Za-z0-9  ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9 ]/g,'')"  maxlength="70" style="width:100% !important;" />
                        </div>
                    </div>
                    <div class="editform cls_prof">
                        <label>Hospital Attached To</label>
                        <div class="value">
                            <input type="text" name="hospital_attached_to" id="hospital_attached_to" value="<?php if(!empty($_POST['hospital_attached_to'])) { echo $_POST['hospital_attached_to']; } else if(!empty($ProfOtherDtls['hospital_attached_to'])) { echo $ProfOtherDtls['hospital_attached_to']; } else { echo ""; } ?>" class="validate[maxSize[160]] form-control" onkeyup="if (/[^A-Za-z.(), ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z.(), ]/g,'')"  maxlength="160" style="width:100% !important;" />
                        </div>
                    </div>
                    <div class="editform">
                        <label>PAN Card Number</label>
                        <div class="value">
                            <input type="text" name="pancard_no" id="pancard_no" value="<?php if(!empty($_POST['pancard_no'])) { echo $_POST['pancard_no']; } else if(!empty($ProfOtherDtls['pancard_no'])) { echo $ProfOtherDtls['pancard_no']; } else { echo ""; } ?>" class="validate[minSize[10],maxSize[10]] form-control" onkeyup="if (/[^A-Za-z0-9]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z]/g,'')"  maxlength="10" style="width:100% !important;" />
                        </div>
                    </div>
                </div>
              </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_professional_submit();" />
                </div>  
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='add_professional')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $service_professional_id=strip_tags($_POST['service_professional_id']);
        $reference_type=strip_tags($_POST['reference_type']);
        $title=strip_tags($_POST['title']);
        $name=strip_tags($_POST['name']);
        $first_name=strip_tags($_POST['first_name']);
        $middle_name=strip_tags($_POST['middle_name']);
        $email_id=strip_tags($_POST['email_id']);
        $phone_no=strip_tags($_POST['phone_no']);
        $mobile_no=strip_tags($_POST['mobile_no']);
        $dob=date('Y-m-d',strtotime($_POST['dob']));
        $address=$_POST['address'];
        $work_phone_no=strip_tags($_POST['work_phone_no']);
        $work_email_id=strip_tags($_POST['work_email_id']);
        $work_address=$_POST['work_address'];
        $location_id=strip_tags($_POST['location_id']);
        $location_id_home=strip_tags($_POST['location_id_home']);
        $service_ids = $_POST['service_id'];
        // Professional Other Details
        $detail_id=strip_tags($_POST['detail_id']);
        $qualification=strip_tags($_POST['qualification']);
        $specialization=strip_tags($_POST['specialization']);
        $skill_set=strip_tags($_POST['skill_set']);
        $work_experience=strip_tags($_POST['work_experience']);
        $hospital_attached_to=$_POST['hospital_attached_to'];
        $pancard_no=strip_tags($_POST['pancard_no']);
        $set_location=strip_tags($_POST['set_location']);
        $google_home_location=strip_tags($_POST['google_home_location']);
        $google_work_location=strip_tags($_POST['google_work_location']);
        
        if($reference_type=='')
        {
            $success=0;
            $errors[$i++]="Please enter name";
        }
        if($title=='')
        {
            $success=0;
            $errors[$i++]="Please enter title";
        }
        if($name=='')
        {
            $success=0;
            $errors[$i++]="Please enter last name";
        }
        if($first_name=='')
        {
            $success=0;
            $errors[$i++]="Please enter first name";
        }
        if($mobile_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter mobile number";
        }
        /*if($email_id=='')
        {
            $success=0;
            $errors[$i++]="Please enter email address";
        }
        if($phone_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter landline number";
        }
        if($dob=='')
        {
            $success=0;
            $errors[$i++]="Please select birth date";
        }
        if($address=='')
        {
            $success=0;
            $errors[$i++]="Please enter address";
        }
        if($work_phone_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter work phone number";
        }
        if($work_email_id=='')
        {
            $success=0;
            $errors[$i++]="Please enter work email address";
        }
        if($work_address=='')
        {
            $success=0;
            $errors[$i++]="Please enter work address";
        }
        if($location_id=='')
        {
            $success=0;
            $errors[$i++]="Please select location";
        }*/
        if($service_ids == '')
        {
             $success=0;
             $errors[$i++]="Please select service";
        }
        
        // Professional Other Detail Validation 
        if(!empty($reference_type))
        {
            if($reference_type=='1')
            {
                /*if($qualification=='')
                {
                    $success=0;
                    $errors[$i++]="Please enter qualification";
                }
                if($specialization=='')
                {
                    $success=0;
                    $errors[$i++]="Please enter specialization";
                }
                if($skill_set=='')
                {
                    $success=0;
                    $errors[$i++]="Please enter skill set";
                }
                if($work_experience=='')
                {
                    $success=0;
                    $errors[$i++]="Please enter work experience";
                }*/
            } 
            
            /*
            
            if($pancard_no=='')
            {
                $success=0;
                $errors[$i++]="Please enter PAN number";
            }
             
            */
             
        }
        
        /*
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
         * 
         */
        // Check Record Exists 
        if($service_professional_id)
            $chk_professional_sql="SELECT service_professional_id FROM sp_service_professionals WHERE mobile_no='".$mobile_no."' AND service_professional_id !='".$service_professional_id."'";
        else 
            $chk_professional_sql="SELECT service_professional_id FROM sp_service_professionals WHERE mobile_no='".$mobile_no."'"; 
        
        if(mysql_num_rows($db->query($chk_professional_sql)))
        {
            $success=0;
            echo 'professionalexists';
            exit;
        }
         
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $arr['service_professional_id']=$service_professional_id;
            $arr['reference_type']=$reference_type;
            $arr['title']=$title;
            $arr['name']=ucwords($name);
            $arr['first_name']=ucwords($first_name);
            $arr['middle_name']=ucwords($middle_name);
            $arr['email_id']=strtolower($email_id);
            $arr['phone_no']=$phone_no;
            $arr['mobile_no']=$mobile_no;
            $arr['dob']=$dob;
            $arr['address']=$address;
            $arr['work_phone_no']=$work_phone_no;
            $arr['work_email_id']=strtolower($work_email_id);
            $arr['work_address']=$work_address;
            $arr['location_id']=$location_id;
            $arr['location_id_home']=$location_id_home;
            $arr['set_location']=$set_location;
            $arr['service_ids']=$service_ids;
            $arr['google_home_location']=$google_home_location;
            $arr['google_work_location']=$google_work_location;
            
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($employee_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$professionalsClass->AddProfessional($arr); 
            if(!empty($InsertRecord))
            {
                // Insert Professional Other Details
                if(!empty($service_professional_id))
                    $arg['service_professional_id']=$service_professional_id;
                else 
                   $arg['service_professional_id']=$InsertRecord;
                
                
                $arg['detail_id']=$detail_id;
                $arg['qualification']=$qualification;
                $arg['specialization']=$specialization;
                $arg['skill_set']=$skill_set;
                $arg['work_experience']=$work_experience;
                $arg['hospital_attached_to']=$hospital_attached_to;
                $arg['pancard_no']=$pancard_no;
                $arg['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
                $arg['last_modified_date']=date('Y-m-d H:i:s');
                
                if(empty($detail_id))
                {
                    $arg['status']='1';
                    $arg['added_by']=strip_tags($_SESSION['admin_user_id']);
                    $arg['added_date']=date('Y-m-d H:i:s');
                }
                $InsertOtherDtlsRecord=$professionalsClass->AddProfessionalOtherDtls($arg); 
                
                if($service_professional_id)
                {
                    echo 'UpdateSuccess'; // Update Record
                    exit;
                }
                else 
                {
                    echo 'InsertSuccess'; // Insert Record
                    exit;
                }
            }
            else 
            {
               echo 'professionalexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['service_professional_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['service_professional_id'] =$_REQUEST['service_professional_id'];
        if($_REQUEST['actionval']=='Active')
            $arr['status']='1';
        if($_REQUEST['actionval']=='Inactive')
           $arr['status']='2';
        if($_REQUEST['actionval']=='Delete')
           $arr['status']='3';
        if($_REQUEST['actionval']=='Revert')
        {
            if(!empty($_REQUEST['curr_status']))
                $arr['status']=$_REQUEST['curr_status'];
            else 
                $arr['status']='1';
        }
        if($_REQUEST['actionval']=='CompleteDelete')
           $arr['status']='5';
        
        $arr['curr_status']=$_REQUEST['curr_status'];
        $arr['login_user_id']=$_REQUEST['login_user_id'];
        $arr['istrashDelete']=$_REQUEST['trashDelete'];

        $ChangeStatus =$professionalsClass->ChangeStatus($arr);
        if(!empty($ChangeStatus))
        {
            echo 'success';
            exit;
        }
        else
        {
            echo 'error';
            exit;
        }
    } 
}
else if($_REQUEST['action']=='vw_professional')
{
    // Getting Professional Details
    $arr['service_professional_id']=$_REQUEST['service_professional_id'];
    $ProfDtls=$professionalsClass->GetProfessionalById($arr);
    // Getting Professional other details
    $ProfOtherDtls=$professionalsClass->GetProfessionalOtherDtlsById($arr);
    //print_r($ProfDtls);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View Professional Details</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="editform">
                <label>Professional Code</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['professional_code'])) { echo $ProfDtls['professional_code']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Type</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['typeVal'])) { echo $ProfDtls['typeVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Name</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['name'])) { echo $ProfDtls['name']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Email Address</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['email_id'])) { echo $ProfDtls['email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Phone Number</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['phone_no'])) { echo $ProfDtls['phone_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Mobile Number</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['mobile_no'])) { echo $ProfDtls['mobile_no']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Birth Date</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['dob']) && $ProfDtls['dob']!='0000-00-00') { echo date('d M Y',strtotime($ProfDtls['dob'])); } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Home Address</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['address'])) { echo $ProfDtls['address']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Home Location</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['google_home_location'])) { echo $ProfDtls['google_home_location']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Address</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['work_address'])) { echo $ProfDtls['work_address']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Location</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['google_work_location'])) { echo $ProfDtls['google_work_location']; } else {  echo "Not Available"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Set Address</label>
                <div class="value">
                    <?php if($ProfDtls['set_location'] == '1') { echo 'Home Location'; } else {  echo "Work Location"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Phone</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['work_phone_no'])) { echo $ProfDtls['work_phone_no']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Email Address</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['work_email_id'])) { echo $ProfDtls['work_email_id']; } else {  echo "-"; } ?>
                </div>
            </div>
            
<!--            <div class="editform">
                <label>Location</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['locationNm'])) { echo $ProfDtls['locationNm']; } else {  echo "-"; } ?>
                </div>
            </div>-->
            
            <div class="editform">
                <label>PIN Code</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['LocationPinCode'])) { echo $ProfDtls['LocationPinCode']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Services</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['Services'])) { echo $ProfDtls['Services']; } else {  echo "-"; } ?>
                </div>
            </div>
            <?php if(!empty($ProfDtls['reference_type'])) { if($ProfDtls['reference_type']=='1') { ?>
            <div class="editform">
                <label>Qualification</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['qualification'])) { echo $ProfOtherDtls['qualification']; } else {  echo "-"; } ?>
                </div>
            </div>
            
            <div class="editform">
                <label>Specialization</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['skill_set'])) { echo $ProfOtherDtls['skill_set']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Skill Sets</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['specialization'])) { echo $ProfOtherDtls['specialization']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Work Experience</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['work_experience'])) { echo $ProfOtherDtls['work_experience']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Hospital Attached To</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['hospital_attached_to'])) { echo $ProfOtherDtls['hospital_attached_to']; } else {  echo "-"; } ?>
                </div>
            </div>
            <?php } } ?>
            <div class="editform">
                <label>PAN CARD No.</label>
                <div class="value">
                    <?php if(!empty($ProfOtherDtls['pancard_no'])) { echo $ProfOtherDtls['pancard_no']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Status</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['statusVal'])) { echo $ProfDtls['statusVal']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['added_by'])) { echo $ProfDtls['added_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Added By</label>
                <div class="value"> 
                    <?php if(!empty($ProfDtls['added_date']) && $ProfDtls['added_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A', strtotime($ProfDtls['added_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified By</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['last_modified_by'])) { echo $ProfDtls['last_modified_by']; } else {  echo "-"; } ?>
                </div>
            </div>
            <div class="editform">
                <label>Last Modified Date</label>
                <div class="value">
                    <?php if(!empty($ProfDtls['last_modified_date']) && $ProfDtls['last_modified_date'] !='0000-00-00 00:00:00') { echo date('jS F Y H:i:s A',strtotime($ProfDtls['last_modified_date'])); } else {  echo "-"; } ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
else if($_REQUEST['action'] == 'AddScheduled')
{   
    $formDate = date('Y-m-d', strtotime($_REQUEST['fromdate']));
    $toDate = date('Y-m-d', strtotime($_REQUEST['toDate']));
    $profID = $_REQUEST['profID'];
    $date1 = strtotime($toDate);
    $date2 = strtotime($formDate);
    $diff = ($date1-$date2);
    $totaldays = floor($diff/(60*60*24));
    //echo $totaldays;
    echo '<input type="hidden" name="fromDateselcted" id="fromDateselcted" value="'.$formDate.'" >
            <input type="hidden" name="toDateselc" id="toDateselc" value="'.$toDate.'" >'
            . '<input type="hidden" name="editedRecord" id="editedRecord"  value="" >'
            . '<input type="hidden" name="totaldays" id="totaldays"  value="'.$totaldays.'" >';
    for($i=0;$i<=$totaldays;$i++)
    {
        $newdate = date('d-m-Y', strtotime($formDate));
        
        $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where status = '1' order by name asc ";
        $ptrval = $db->fetch_all_array($selectProfessional);
        
        
        //else
        {
            echo '
            <div class="row">            
            <input type="hidden" name="schedule_date_'.$i.'" id="schedule_date_'.$i.'" value="'.$newdate.'" >
                <div style="width:10%; display:inline-block; float:left; vertical-align:top; padding-right:1%; padding:4px; margin-left: 15px;">'.$newdate.' </div>
                <div class="datepairExample_0">
                <div class="pull-left" style="width:15%;display:inline-block;padding-right:2%;padding:4px;">
                    <label style="display:block;">
                        <input value="" placeholder="From Time" name="starttime_0_'.$i.'" id="starttime_0_'.$i.'" type="text" class="form-control time start validate_time" />
                    </label>
                </div>
                <div class="pull-left" style="width:15%;display:inline-block;padding-left:2%;padding:4px;">       
                    <label style="display:block;">
                        <input  placeholder="To Time"  value="" name="endtime_0_'.$i.'" id="endtime_0_'.$i.'"  type="text" class="form-control time end validate_time" />
                    </label>                
                </div>   
                </div>';                
                /*--------- professional Listing ----------*/ 
                if($profID == '')
                {
                    echo '<div style="width:25%; display:inline-block; vertical-align:top; padding:4px;" class="text-right value select-pro">';
                    echo '<label><select name="professional_id_0_'.$i.'[]" id="professional_id_0_'.$i.'" class="validate[required] ServiceClass" multiple="multiple">';

                        foreach($ptrval as $key=>$valProfessional)
                        {
                            echo '<option value="'.$valProfessional['service_professional_id'].'">'.$valProfessional['name'].' '.$valProfessional['first_name'].' '.$valProfessional['middle_name'].'</option>';
                        }     
                    echo '</select></label>';
                    echo '<input type="hidden" name="existProfId" id="existProfId" value="" >';
                    echo '</div> ';
                }
                else
                    echo '<input type="hidden" name="existProfId" id="existProfId" value="'.$profID.'" >';
                /*--------- professional Listing End----------*/ 
                echo '<div style="display:inline-block; padding:7px;">
                        <a href="javascript:void(0);" title="Add" onclick="javascript:addScheduled('.$i.');"><img src="images/add.png"></a>
                    </div>
                    <div  style="width:10%;display:inline-block;">
                        <a href="javascript:void(0);" title="Remove" onclick="javascript:deleteScheduled('.$i.');"><img src="images/remove1.png"></a>
                    </div>
            </div>
            ';
            echo '<input type="hidden" name="extras_'.$i.'" id="extras_'.$i.'" value="0" />';
            echo '<div id="div_1_'.$i.'"></div>';
        }
       $formDate = date('Y-m-d', strtotime('+1 day' , strtotime($newdate)));
       echo '<div class="line" style="margin:15px 0 20px;"></div>';
    }
    
    ?>
    <div>
        <input class="btn btn-download" type="button" name="scheduledSubmit" id="scheduledSubmit" value="Add Schedule" onclick="return scheduleSubForm();"> 
    </div>
<?php
}
else if($_REQUEST['action'] == 'AddMorescheduled')
{   
    $profID = $_REQUEST['profID'];
        $number = $_REQUEST['number'];
        $i = $_REQUEST['curr_div'];  
        $j = $i+1;
        echo '
            <div class="row">
                <div style="width:10%; display:inline-block; float:left; vertical-align:top; padding-right:1%; padding:4px; margin-left: 15px;"> </div>
                <div class="datepairExample_0">
                <div class="pull-left" style="width:15%;display:inline-block;padding-right:2%;padding:4px;">
                    <label style="display:block;">
                        <input value="" placeholder="From Time" name="starttime_'.$i.'_'.$number.'" id="starttime_'.$i.'_'.$number.'" type="text" class="form-control time start validate_time" />
                    </label>
                </div>
                <div class="pull-left" style="width:15%;display:inline-block;padding-left:2%;padding:4px;">       
                    <label style="display:block;">
                        <input  placeholder="To Time"  value="" name="endtime_'.$i.'_'.$number.'" id="endtime_'.$i.'_'.$number.'"  type="text" class="form-control time end validate_time" />
                    </label>                
                </div>   
                </div>';
            /*--------- professional Listing ----------*/ 
                if($profID == '')
                {
                    echo '<div style="width:25%; display:inline-block; vertical-align:top; padding:4px;" class="text-right value select-pro">';
                    echo '<label><select name="professional_id_'.$i.'_'.$number.'[]" id="professional_id" class="validate[required] ServiceClass" multiple="multiple">';
                        $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where status = '1' order by name asc ";
                        $ptrval = $db->fetch_all_array($selectProfessional);
                        foreach($ptrval as $key=>$valProfessional)
                        {
                            echo '<option value="'.$valProfessional['service_professional_id'].'">'.$valProfessional['name'].' '.$valProfessional['first_name'].' '.$valProfessional['middle_name'].'</option>';
                        }     
                    echo '</select></label>';
                    echo '</div> ';
                }
                else
                    echo '<input type="hidden" name="existProfId" id="existProfId" value="'.$profID.'" >';
            /*--------- professional Listing End----------*/        
                echo '   <div  style="width:10%;display:inline-block;">
                       
                    </div> 
            </div> ';
        echo '<div id="div_'.$j.'_'.$number.'"></div>';
}
else if($_REQUEST['action'] == 'submitScheduled')
{
    
    $success=0;  $errors=array();  $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
//        echo '<pre>';
//        print_r($_POST);
//        echo '</pre>';
//        exit;
        $formDates = $_POST['fromDateselcted'];
        $toDates = $_POST['toDateselc'];
        $existProfId = $_POST['existProfId'];
        $editedRecord = $_POST['editedRecord'];
        $editedPROfRecord = $_POST['editedPROfRecord'];
        if($editedRecord == 'yes')
        {
            if($editedPROfRecord)
            {
                $deleteExist = "delete from sp_professional_scheduled where scheduled_date BETWEEN '".$formDates."' AND '".$toDates."' and professiona_id = '".$editedPROfRecord."' ";
                $db->query($deleteExist);
            }
            else 
            {
               // Delete All Records  
                
               $deleteExist = "delete from sp_professional_scheduled where scheduled_date BETWEEN '".$formDates."' AND '".$toDates."'";
               $db->query($deleteExist);  
            }
        }
        $date1 = strtotime($toDates);
        $date2 = strtotime($formDates);
        $diff = ($date1-$date2);
        $totaldays = floor($diff/(60*60*24));
        //echo $totaldays;
        for($i=0;$i<=$totaldays;$i++)
        {
            $extras = $_POST['extras_'.$i];
            $schedule_date = '';
            $starttime = '';
            $endtime = '';
            for($v=0;$v<=$extras;$v++)
            {
                $existIDPlan = '';
                $schedule_date = $_POST['schedule_date_'.$i];
                $starttime = $_POST['starttime_'.$v.'_'.$i];
                $endtime = $_POST['endtime_'.$v.'_'.$i];
                
                $insertData['scheduled_date'] =  date('Y-m-d',strtotime($schedule_date));
                $insertData['from_time'] = $starttime;
                $insertData['to_time'] = $endtime;
                $insertData['added_date'] = date('Y-m-d H:i:s');
                $insertData['added_by'] = $_SESSION['admin_user_id'];
                $insertData['modified_date'] = date('Y-m-d H:i:s');
                $insertData['modified_by'] = $_SESSION['admin_user_id'];
                $insertData['status'] = '1';
                if($existProfId)
                {
                    $insertData['professiona_id'] = $existProfId;
                    if($starttime && $endtime && $existProfId)
                    {
                       $select_exist = "select schedule_id from sp_professional_scheduled where professiona_id = '".$existProfId."' and scheduled_date = '".$insertData['scheduled_date']."' and from_time = '".$starttime."' and to_time = '".$endtime."' ";
                        //if(mysql_num_rows($db->query($select_exist)) == 0)
                        {                                
                            $RecordId=$db->query_insert('sp_professional_scheduled',$insertData);
                        }
                    }    
                }
                else
                {
                    $professional_id = $_POST['professional_id_'.$v.'_'.$i];
                    $totalProf = count($professional_id);
                    for($z=0;$z<$totalProf;$z++)
                    {
                        $profeID = $professional_id[$z];                        
                        $insertData['professiona_id'] = $profeID;
                        if($starttime && $endtime && $profeID)
                        {
                            $select_exist = "select schedule_id from sp_professional_scheduled where professiona_id = '".$profeID."' and scheduled_date = '".$insertData['scheduled_date']."' and from_time = '".$starttime."' and to_time = '".$endtime."' ";
                            if(mysql_num_rows($db->query($select_exist)) == 0)
                            {                                
                                $RecordId=$db->query_insert('sp_professional_scheduled',$insertData);
                            }
                        }                    
                    }
                }
            }
        }
        
        if($RecordId)
        {
            echo "success";
            exit;
        }
        else 
        {
            echo "error";
            exit;
        }
       
    }
}
else if($_REQUEST['action'] == 'view_editcheduled')
{
       
    $formDate = date('Y-m-d', strtotime($_REQUEST['fromdate']));
    $toDate = date('Y-m-d', strtotime($_REQUEST['toDate']));
    $profID = $_REQUEST['profID'];
    $date1 = strtotime($toDate);
    $date2 = strtotime($formDate);
    $diff = ($date1-$date2);
    $totaldays = floor($diff/(60*60*24));
    $record = 'No';
    //echo $totaldays;
    echo '<input type="hidden" name="fromDateselcted" id="fromDateselcted" value="'.$formDate.'" >
            <input type="hidden" name="toDateselc" id="toDateselc" value="'.$toDate.'" >'
            . '<input type="hidden" name="editedRecord" id="editedRecord"  value="yes" >'
            . '<input type="hidden" name="editedPROfRecord" id="editedPROfRecord"  value="'.$profID.'" >';
    for($i=0;$i<=$totaldays;$i++)
    {
        if($profID)
            $preID = " and professiona_id = '".$profID."'";
        else
            $preID = '';
        $newdate = date('d-m-Y', strtotime($formDate));
        
        $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where status = '1'  order by name asc ";
        $ptrval = $db->fetch_all_array($selectProfessional);
        
        $select_existRecord = "select distinct from_time, to_time from sp_professional_scheduled where scheduled_date = '".date('Y-m-d',strtotime($newdate))."' ".$preID." ";
        if(mysql_num_rows($db->query($select_existRecord)))
        {
            $ptrSchrec = $db->fetch_all_array($select_existRecord);
            $srNo = 0;
            foreach($ptrSchrec as $key=>$valScheduledData)
            {
                $extArray = array();
                $selectexistProf = "select professiona_id from sp_professional_scheduled where scheduled_date = '".date('Y-m-d',strtotime($newdate))."' and from_time = '".$valScheduledData['from_time']."' and to_time = '".$valScheduledData['to_time']."' ";
                $ptrvalExit = $db->fetch_all_array($selectexistProf);
                foreach($ptrvalExit as $key=>$valExisArray)
                {
                    $professiona_id = $valExisArray['professiona_id'];
                    $extArray[] = $professiona_id;
                }
                
                // print_r($extArray);
                
                
                if($srNo == '0')
                    $printdate = $newdate;
                else
                    $printdate = '';
                echo '
                <div class="row">            
                <input type="hidden" name="schedule_date_'.$i.'" id="schedule_date_'.$i.'" value="'.$newdate.'" />
                <input type="hidden" name="pre_schedule_date_professional_'.$i.'" id="pre_schedule_date_professional_'.$i.'" value="'.$extArray[$i].'" />
                    <div style="width:10%; display:inline-block; float:left; vertical-align:top; padding-right:1%; padding:4px; margin-left: 15px;">'.$printdate.' </div>
                    <div class="datepairExample_0">
                    <div class="pull-left" style="width:15%;display:inline-block;padding-right:2%;padding:4px;">
                        <label style="display:block;">
                            <input value="'.$valScheduledData['from_time'].'" placeholder="From Time" name="starttime_'.$srNo.'_'.$i.'" id="starttime_'.$srNo.'_'.$i.'" type="text" class="form-control time start validate_time" />
                        </label>
                    </div>
                    <div class="pull-left" style="width:15%;display:inline-block;padding-left:2%;padding:4px;">       
                        <label style="display:block;">
                            <input  placeholder="To Time"  value="'.$valScheduledData['to_time'].'" name="endtime_'.$srNo.'_'.$i.'" id="endtime_'.$srNo.'_'.$i.'"  type="text" class="form-control time end validate_time" />
                        </label>                
                    </div>   
                    </div>';
                if($profID == '')
                {
                    echo '<div style="width:25%; display:inline-block; vertical-align:top; padding:4px;" class="text-left value select-pro">';
                
                    echo '<label><select name="professional_id_'.$srNo.'_'.$i.'[]" id="professional_id" class="validate[required] ServiceClass" multiple="multiple">';
                    $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where status = '1' order by name asc ";
                    $ptrval = $db->fetch_all_array($selectProfessional);
                    foreach($ptrval as $key=>$valProfessional)
                    {
                        $class = '';
                        for($m=0;$m<=count($extArray);$m++)
                        {
                            if($extArray[$m] == $valProfessional['service_professional_id'])
                                $class = 'selected="selected"';
                        }
                        echo '<option '.$class.' value="'.$valProfessional['service_professional_id'].'">'.$valProfessional['name'].' '.$valProfessional['first_name'].' '.$valProfessional['middle_name'].'</option>';
                    }
                echo '</select></label>';
                echo '</div> ';
                }
                else
                {
                    echo '<div style="width:25%; display:inline-block; vertical-align:top; padding:4px;" class="text-left value select-pro">
                        <a href="javascript:void(0);" title="Remove" onclick="javascript:deleteScheduled('.$i.');"><img src="images/icon-inactive.png"></a>
                          </div>';
                    echo '<input type="hidden" name="existProfId" id="existProfId" value="'.$profID.'" >';    
                }
                echo '</div>  
                    
                ';
                $srNo++;
            }
            echo '<div class="line" style="margin:15px 0 20px;"></div>'; 
            $record = 'Yes';
        }
       $formDate = date('Y-m-d', strtotime('+1 day' , strtotime($newdate)));
       echo '<input type="hidden" name="extras_'.$i.'" id="extras_'.$i.'" value="'.$srNo.'" >';
    }
    if($record == 'Yes')
    {
    ?>
    <div>
        <input class="btn btn-download" type="button" name="scheduledSubmit" id="scheduledSubmit" value="Edit Scheduled" onclick="return scheduleSubForm();"> 
    </div>
<?php
    }
    else
    {
        echo '<h1 class="messageText">No records found related to your search, please try again.</h1>';
    }
}

else if($_REQUEST['action']=='viewScheduleProf')
{
    // Getting Professional Details
    $arr['service_professional_id']=$_REQUEST['service_professional_id'];
    $ProfDtls=$professionalsClass->GetProfessionalById($arr);
    // Getting Professional other details
    $ProfOtherDtls=$professionalsClass->GetProfessionalOtherDtlsById($arr);
    $todaysdate = date('Y-m-d');
    
    $Selcfromdate = date('d-m-Y', strtotime('-4 days', strtotime($todaysdate)));
    $SelctoDate = date('d-m-Y', strtotime('+4 days', strtotime($todaysdate)));
    $_REQUEST['profID'] = $_REQUEST['service_professional_id'];
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">View/Edit Professional Schedule</h4>
    </div>
    <div class="modal-body">
        <div>
            <div class="edditform">
                <div class="col-lg-4 marginB20 paddingl0">
                <div class="searchBox">
                    <input type="hidden" name="profeID" id="profeID" value="<?php echo $_REQUEST['service_professional_id'];?>" >
                    <input class="data-entry-search datepicker_from" placeholder="From Date" type="text" name="formDate" id="formDate" value="<?php echo $Selcfromdate;?>" >                           
                </div>
            </div>
            <div class="col-lg-4 marginB20 paddingl0">
                <div class="searchBox">                            
                    <input class="data-entry-search datepicker_to" placeholder="To Date" type="text" name="toDate" id="toDate" value="<?php echo $SelctoDate;?>" >                           
                </div>
            </div>
            <div class="col-lg-4 marginB20 paddingl0">
                    <a href="javascript:void(0);"><img onclick="searchScheduleRec();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
            </div>  
            </div>
        </div>
        <div class="clearfix"></div>
        <div>
            <form name="scheduleform" id="scheduleform" method="post" action="professional_ajax_process.php?action=submitScheduled" >
                <div class="ScheduledListing">
                    <?php include_once "viewWordscheduled.php"; ?>
                </div>

            </form>
        </div>
        <div class="clearfix"></div>
    </div>
<div class="clearfix"></div>
<?php
}

else if($_REQUEST['action'] == 'Edit_deleteScheduled')
{
       
    $formDate = date('Y-m-d', strtotime($_REQUEST['fromdate']));
    $toDate = date('Y-m-d', strtotime($_REQUEST['toDate']));
    $profID = $_REQUEST['profID'];
    $date1 = strtotime($toDate);
    $date2 = strtotime($formDate);
    $diff = ($date1-$date2);
    $totaldays = floor($diff/(60*60*24));
    //echo $totaldays;
    echo '<input type="hidden" name="fromDateselcted" id="fromDateselcted" value="'.$formDate.'" >
            <input type="hidden" name="toDateselc" id="toDateselc" value="'.$toDate.'" >'
            . '<input type="hidden" name="editedRecord" id="editedRecord"  value="yes" >'
            . '<input type="hidden" name="editedPROfRecord" id="editedPROfRecord"  value="'.$profID.'" >';
    for($i=0;$i<=$totaldays;$i++)
    {
        if($profID)
            $preID = " and professiona_id = '".$profID."'";
        else
            $preID = '';
        $newdate = date('d-m-Y', strtotime($formDate));
        
        $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where status = '1'  order by name asc ";
        $ptrval = $db->fetch_all_array($selectProfessional);
        
        $select_existRecord = "select distinct from_time, to_time, schedule_id from sp_professional_scheduled where scheduled_date = '".date('Y-m-d',strtotime($newdate))."' ".$preID." ";
        if(mysql_num_rows($db->query($select_existRecord)))
        {
            $ptrSchrec = $db->fetch_all_array($select_existRecord);
            $srNo = 0;
            foreach($ptrSchrec as $key=>$valScheduledData)
            {
                $extArray = array();
                $selectexistProf = "select professiona_id from sp_professional_scheduled where scheduled_date = '".date('Y-m-d',strtotime($newdate))."' and from_time = '".$valScheduledData['from_time']."' and to_time = '".$valScheduledData['to_time']."' ";
                $ptrvalExit = $db->fetch_all_array($selectexistProf);
                foreach($ptrvalExit as $key=>$valExisArray)
                {
                    $professiona_id = $valExisArray['professiona_id'];
                    $extArray[] = $professiona_id;
                }
                //print_r($extArray);
                
                if($srNo == '0')
                    $printdate = $newdate;
                else
                    $printdate = '';
                echo '
                <div class="row">            
                <input type="hidden" name="schedule_date_'.$i.'" id="schedule_date_'.$i.'" value="'.$newdate.'" >
                    <div style="width:10%; display:inline-block; float:left; vertical-align:top; padding-right:1%; padding:4px; margin-left: 15px;">'.$printdate.' </div>
                    <div class="datepairExample_0">
                    <div class="pull-left" style="width:15%;display:inline-block;padding-right:2%;padding:4px;">
                        <label style="display:block;">
                            <input value="'.$valScheduledData['from_time'].'" placeholder="From Time" name="starttime_'.$srNo.'_'.$i.'" id="starttime_'.$srNo.'_'.$i.'" type="text" class="form-control time start validate_time" />
                        </label>
                    </div>
                    <div class="pull-left" style="width:15%;display:inline-block;padding-left:2%;padding:4px;">       
                        <label style="display:block;">
                            <input  placeholder="To Time"  value="'.$valScheduledData['to_time'].'" name="endtime_'.$srNo.'_'.$i.'" id="endtime_'.$srNo.'_'.$i.'"  type="text" class="form-control time end validate_time" />
                        </label>                
                    </div>   
                    </div>';
                if($profID == '')
                {
                    echo '<div style="width:25%; display:inline-block; vertical-align:top; padding:4px;" class="text-left value select-pro">';
                
                    echo '<label><select name="professional_id_'.$srNo.'_'.$i.'[]" id="professional_id" class="validate[required] ServiceClass" multiple="multiple">';
                    $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where status = '1' order by name asc ";
                    $ptrval = $db->fetch_all_array($selectProfessional);
                    foreach($ptrval as $key=>$valProfessional)
                    {
                        $class = '';
                        for($m=0;$m<=count($extArray);$m++)
                        {
                            if($extArray[$m] == $valProfessional['service_professional_id'])
                                $class = 'selected="selected"';
                        }
                        echo '<option '.$class.' value="'.$valProfessional['service_professional_id'].'">'.$valProfessional['name'].' '.$valProfessional['first_name'].' '.$valProfessional['middle_name'].'</option>';
                    }
                echo '</select></label>';
                echo '</div> ';
                }
                else
                {
                    echo '<div style="width:25%; display:inline-block; vertical-align:top; padding:4px;" class="text-left value select-pro">
                        <a href="javascript:void(0);" title="Remove" onclick="javascript:deleteScheduled('.$valScheduledData['schedule_id'].');"><img src="images/icon-inactive.png"></a>
                          </div>';
                    echo '<input type="hidden" name="existProfId" id="existProfId" value="'.$profID.'" >';    
                }
                echo '</div>  
                    
                ';
                $srNo++;
            }
            echo '<div class="line" style="margin:15px 0 20px;"></div>';
            $record = 'Yes';
        }
       $formDate = date('Y-m-d', strtotime('+1 day' , strtotime($newdate)));
       echo '<input type="hidden" name="extras_'.$i.'" id="extras_'.$i.'" value="'.$srNo.'" >';
    }
    
    if($record == 'Yes')
    {
    ?>
    <div>
        <input class="btn btn-download" type="button" name="scheduledSubmit" id="scheduledSubmit" value="Edit Scheduled" onclick="return scheduleSubForm();"> 
    </div>
<?php
    }
    else
    {
        echo '<h1 class="messageText">No records found related to your search, please try again.</h1>';
    }
}

else if($_REQUEST['action'] == 'deleteDatOfSchedule')
{
    $schedule_id = $_REQUEST['scheduled_id'];
    $selectexist = "select schedule_id from sp_professional_scheduled where schedule_id = '".$schedule_id."'";
    if(mysql_num_rows($db->query($selectexist)))
    {
        $deleteFROM = " delete from sp_professional_scheduled  where schedule_id = '".$schedule_id."' ";
        $db->query($deleteFROM);
        echo 'success';
        exit;
    }
    else
    {
        echo 'error';
        exit;
    }
}
else if($_REQUEST['action'] == 'ImportExcel')
{
    ?>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Import Professional File</h4>
        </div>
        <div class="modal-body">
            <form class="form-inline" name="add_expo" id="frm_add_expo" method="post"  enctype="multipart/form-data" action="professional_ajax_process.php?action=SubmitProfessionalForm" autocomplete="off">
                <div class="editform" >
                    <label>Upload Professional File</label>
                    <div class="value" >
                        <input type="file" name="professionalFile" id="professionalFile" class="brochurefile" />
                        <br><br>
                        <a href="include/professionalExcel.xls" target="_blank"><img src="images/icon-xls25.png" /> Sample File </a>
                    </div>
                </div>
                <div class="editform" >
                    <label style="color:#e7394d;font-size:15px;">Important Notes :-</label>
                    <div class="value" style="color:#e7394d;font-size:13px;">
                        Professional Type,Title,Last Name,First Name,Mobile,home Location, work location, set location, Services fields are compulsory.<br/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Upload" onclick="return professionFile_submit();" />
                </div>
            </form>
        </div>
    </div>
        <?php
    
}
else if($_REQUEST['action']=='SubmitProfessionalForm')
    {
       $success=0;
       $errors=array(); 
       $i=0;
       if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
       {
            $professionalFile_image="";
            if(count($errors)==0 && $_FILES['professionalFile']["name"])
            {
                $file_str=preg_replace('/\s+/', '_', $_FILES['professionalFile']["name"]);
                $professionalFile_image=time().basename($file_str);
                $newfile = "professionalImport/";
                $filename = $_FILES['professionalFile']['tmp_name']; // File being uploaded.
                $filetype = $_FILES['professionalFile']['type']; // type of file being uploaded
                $filesize = filesize($filename); // File size of the file being uploaded.
                $source1 = $_FILES['professionalFile']['tmp_name'];
                $target_path1 = $newfile.$professionalFile_image;

                $filename_temp = basename($_FILES['professionalFile']['name']);
                 $ext = substr($filename_temp, strrpos($filename_temp, '.') + 1);

                     list($width1, $height1, $type1, $attr1) = getimagesize($source1);
                     if(strtolower($ext) == "xls"  || strtolower($ext) == "xlsx" ) //|| strtolower($ext) == "csv" )
                     {
                         if(move_uploaded_file($source1, $target_path1))
                         {
                             $thump_target_path="professionalImport/".$professionalFile_image;
                             copy($target_path1,$thump_target_path);
                             $file_uploaded1=1;
                         }
                         else
                         {
                             $file_uploaded1=0;
                             $success=0;
                             $errors[$i++]="There are some errors while uploading xls, please try again";
                         }
                     }
                     else
                     {
                         $file_uploaded1=0;
                         $success=0;
                         $errors[$i++]="Only xls files allowed";

                     }
            }
            else
            {
                $file_uploaded1=0;
                $success=0;
                $errors[$i++]="Please upload Professional excel file";
            }
            if(count($errors))
            {
                $errorBulk = 'yes';  
                for($k=0;$k<count($errors);$k++)
                                echo ''.$errors[$k].'';
            }
            else 
            { 
                include 'excel_reader.php';     // include the class
                $excel = new PhpExcelReader;
                $excel->read("professionalImport/".$professionalFile_image);
                function sheetData($sheet) 
                {
                    $x = 2; //2
                    //$recordExist = 'no';
                    $cell = '';
                    while($x <= $sheet['numRows']) 
                    {
                        $y = 1;
                        while($y <= $sheet['numCols']) 
                        {
                          $cell .= isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          if($y == '1')
                              $cellarr['reference_type'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '2')
                              $cellarr['title'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '3')
                              $cellarr['name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '4')
                              $cellarr['first_name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '5')
                              $cellarr['middle_name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '6')
                              $cellarr['email_id'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '7')
                              $cellarr['phone_no'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '8')
                              $cellarr['mobile_no'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '9')
                              $cellarr['dob'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '10')
                              $cellarr['home_address'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          else if($y == '11')
                              $cellarr['google_home_location'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '12')
                              $cellarr['work_address'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '13')
                              $cellarr['google_worklocation'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '14')
                              $cellarr['work_phone_no'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '15')
                              $cellarr['work_email_id'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';                          
                          else if($y == '16')
                              $cellarr['services'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          else if($y == '17')
                              $cellarr['set_location'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          //$val[] = $sheet['cells'][$x][$y].':,'.$sheet['cells'][$x][++$y];
                          $y++;
                        }
                        $val[] = $cellarr;
                        $x++;                            
                    }
                    //print_r($val);
                    $data = $val;

                    return $val; 
                }
                $nr_sheets = count($excel->sheets);    // gets the number of sheets

                $excel_data = '';              // to store the the html tables with data of each sheet
                // traverses the number of sheets and sets html table with each sheet data in $excel_data
                $reference_type=str_replace(' ','',$excel->sheets[0]['cells'][1][1]);
                $title=str_replace(' ','',$excel->sheets[0]['cells'][1][2]);
                $name=str_replace(' ','',$excel->sheets[0]['cells'][1][3]);
                $first_name=str_replace(' ','',$excel->sheets[0]['cells'][1][4]);
                $middle_name=str_replace(' ','',$excel->sheets[0]['cells'][1][5]);
                $email_id=str_replace(' ','',$excel->sheets[0]['cells'][1][6]);
                $phone_no=str_replace(' ','',$excel->sheets[0]['cells'][1][7]);
                $mobile_no=str_replace(' ','',$excel->sheets[0]['cells'][1][8]);
                $dob=str_replace(' ','',$excel->sheets[0]['cells'][1][9]);
                $address=str_replace(' ','',$excel->sheets[0]['cells'][1][10]);
                $home_location=str_replace(' ','',$excel->sheets[0]['cells'][1][11]);
                $work_address=str_replace(' ','',$excel->sheets[0]['cells'][1][12]);
                $work_location=str_replace(' ','',$excel->sheets[0]['cells'][1][13]);
                $work_phone_no=str_replace(' ','',$excel->sheets[0]['cells'][1][14]);
                $work_email_id=str_replace(' ','',$excel->sheets[0]['cells'][1][15]);

                $services=str_replace(' ','',$excel->sheets[0]['cells'][1][16]);
                $set_location=str_replace(' ','',$excel->sheets[0]['cells'][1][17]);

                if((strtolower($reference_type) == 'professionaltype') && (strtolower($title) == 'title') && (strtolower($name) == 'lastname') && (strtolower($first_name) == 'firstname') && (strtolower($middle_name) == 'middlename') && (strtolower($email_id) == 'emailaddress') && (strtolower($phone_no) == 'phone') && (strtolower($mobile_no) == 'mobile') && (strtolower($dob) == 'birthdate') && (strtolower($address) == 'homeaddress') && (strtolower($work_phone_no) == 'workphone') && (strtolower($work_email_id) == 'workemailaddress') && (strtolower($work_address) == 'workaddress') && (strtolower($home_location) == 'homelocation')   && (strtolower($work_location) == 'worklocation') && (strtolower($services) == 'services') && (strtolower($set_location) == 'setlocation') )
                {
                    $totalRowsCount = $excel->sheets[0]['numRows']; 
                    $excel_data = sheetData($excel->sheets[0]);
                    
                     for($j=0;$j<count($excel_data);$j++)
                     {
                         $prof_ref_type = $db->escape(trim($excel_data[$j]['reference_type'])); 
                         $prof_title = $db->escape(trim($excel_data[$j]['title'])); 
                         $prof_name = $db->escape(trim($excel_data[$j]['name'])); 
                         $prof_fname = $db->escape(trim($excel_data[$j]['first_name'])); 
                         $prof_mname = $db->escape(trim($excel_data[$j]['middle_name'])); 
                         $prof_email = $db->escape(trim($excel_data[$j]['email_id'])); 
                         $prof_phone = $db->escape(trim($excel_data[$j]['phone_no']));
                         $prof_mobile = $db->escape(trim($excel_data[$j]['mobile_no']));
                         $prof_dob = $db->escape(trim($excel_data[$j]['dob']));
                         $prof_address = $excel_data[$j]['home_address'];
                         $prof_work_phone = $db->escape(trim($excel_data[$j]['work_phone_no']));
                         $prof_work_email = $db->escape(trim($excel_data[$j]['work_email_id']));
                         $prof_work_address = $excel_data[$j]['work_address'];
                         //$prof_location = $db->escape(trim($excel_data[$j]['location']));
                         $prof_services =$excel_data[$j]['services'];
                         $setLocation =$excel_data[$j]['set_location'];
                         $home_locations = $db->escape(trim($excel_data[$j]['google_home_location']));
                         $work_locations = $db->escape(trim($excel_data[$j]['google_worklocation']));
                         
                         $professional_id = '';$ServiceIds = '';$typeVal='';
                            
                         if(!empty($prof_ref_type) &&  !empty($prof_title) &&  !empty($prof_name) &&  !empty($prof_fname) &&  !empty($prof_mobile)  && !empty($work_locations) && !empty($prof_services) )
                         {
                           $ChkProfessionalSql="SELECT service_professional_id FROM sp_service_professionals WHERE mobile_no='".$prof_mobile."'"; 
                           $ProfessionalResult=$db->fetch_array($db->query($ChkProfessionalSql));
                           if(!empty($ProfessionalResult))
                           {
                                $service_professional_id=$ProfessionalResult['service_professional_id'];
                           }
                           else 
                           {
                               $service_professional_id=""; 
                           }                           
                           
                           if(strtolower($prof_ref_type)=='professional')
                            {
                                $typeVal="1";
                            }
                            if(strtolower($prof_ref_type)=='vender')
                            {
                                $typeVal="2";
                            }
                            
                            if(strtolower($setLocation) == 'work')
                                $importData['set_location']="2";
                            else if(strtolower($setLocation) == 'home')
                                $importData['set_location']="1";         
                            
                            $importData['google_home_location']=$home_locations;
                            $importData['google_work_location']=$work_locations;  
                            
                            if(strtolower($setLocation) == 'work')
                            {
                                $Mainlocation = $work_locations;
                            }
                            else
                                $Mainlocation = $home_locations;
                            // get lattitude and langitude
                            if(!empty($Mainlocation))
                            {
                                $mainAddress = $Mainlocation.', Pune, Maharashtra,India';
                                $region = 'IND';
                                $address = str_replace(" ", "+", $mainAddress);
                                $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
                                $json = json_decode($json);
                                $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                                $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
                                $importData['lattitude']=$lat;
                                $importData['langitude']=$long;
                            }
                            $importData['reference_type'] = $typeVal;
                            $importData['title'] = $prof_title;
                            $importData['name'] = $prof_name;
                            $importData['first_name'] = $prof_fname;
                            $importData['middle_name'] = $prof_mname;
                            $importData['email_id'] = $prof_email;
                            $importData['phone_no'] = $prof_phone;
                            $importData['mobile_no'] = $prof_mobile;
                            $importData['dob'] = $prof_dob;
                            $importData['address'] = $prof_address;
                            $importData['work_phone_no'] = $prof_work_phone;
                            $importData['work_email_id'] = $prof_work_email;
                            $importData['work_address'] = $prof_work_address;
                            $importData['added_by']=strip_tags($_SESSION['admin_user_id']);
                            $importData['added_date']=date('Y-m-d H:i:s');
                            $importData['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
                            $importData['last_modified_date']=date('Y-m-d H:i:s');
                            if(!empty($service_professional_id))
                            {
                              $where = "service_professional_id ='".$service_professional_id."'";
                              $RecordId=$db->query_update('sp_service_professionals',$importData,$where); 
                            }
                            else 
                            {
                                
                               // Generate Random Number 
                                $GetMaxRecordIdSql="SELECT MAX(service_professional_id) AS MaxId FROM sp_service_professionals";
                                if($db->num_of_rows($db->query($GetMaxRecordIdSql)))
                                {
                                    $MaxRecord=$db->fetch_array($db->query($GetMaxRecordIdSql));
                                    $getMaxRecordId=$MaxRecord['MaxId'];
                                }
                                else 
                                {
                                    $getMaxRecordId=0;
                                }
                                $prefix=$GLOBALS['ProfPrefix'];
                                $ProfessionalCode=Generate_Number($prefix,$getMaxRecordId);
                                
                                $importData['status']='1';
                                $importData['professional_code'] = $ProfessionalCode;
                                $RecordId=$db->query_insert('sp_service_professionals',$importData);
                            }
                            
                            unset($getMaxRecordId);
                            unset($ProfessionalCode);
                            unset($importData);
                            
                            // Assign services for this professional 
                           
                            if(!empty($prof_services))
                            {
                                $ServiceIds=array();
                                $ServiceVal=explode(",",$prof_services);

                                if(!empty($ServiceVal))
                                {
                                    for($i=0;$i<count($ServiceVal);$i++)
                                    {
                                        $GetServiceSql="SELECT service_id FROM sp_services WHERE service_title='".$ServiceVal[$i]."'";
                                        $ServiceResult=$db->fetch_array($db->query($GetServiceSql));
                                        if(!empty($ServiceResult))
                                        {
                                            $ServiceIds[]=$ServiceResult['service_id'];
                                        }
                                    }
                                }

                                if(!empty($ServiceIds))
                                {
                                    // Delete All records of this professional 
                                    if(!empty($service_professional_id))
                                    {
                                        $professional_id=$service_professional_id;
                                    }
                                    else 
                                    {
                                       $professional_id=$RecordId;
                                    }

                                    $delete_allExistingrecord ="DELETE FROM sp_professional_services WHERE service_professional_id ='".$professional_id."'";
                                    $db->query($delete_allExistingrecord);

                                    for($m=0;$m<count($ServiceIds);$m++)
                                    {
                                         $serviceData = array();
                                         $serviceData['service_id']=$ServiceIds[$m];
                                         $serviceData['service_professional_id']=$professional_id;
                                         $serviceData['status']=1;
                                         $serviceData['added_by']=strip_tags($_SESSION['admin_user_id']);
                                         $serviceData['added_date'] = date('Y-m-d H:i:s');
                                         $serviceData['modified_by']=strip_tags($_SESSION['admin_user_id']);
                                         $serviceData['last_modified_date'] = date('Y-m-d H:i:s');
                                         $db->query_insert('sp_professional_services',$serviceData); 
                                    }
                                }  
                                
                                unset($ServiceIds);
                                unset($serviceData);
                            }
                            
                         }   
                     }
                   //===============================
                    echo 'success';
                    exit;
                }
                else
                {
                    echo 'error';
                    exit;
                }
            }   
        } 
    }
?>
