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
    $arr = array();
    $arr['service_professional_id'] = $_REQUEST['service_professional_id'];
    $ProfDtls = $professionalsClass->GetProfessionalById($arr);

    if (!empty($ProfDtls)) {
        // Get Professional service details
        $profServiceList = $professionalsClass->GetProfessionalServices($arr);
        
        // Get Professional sub service details
        $arr['serviceType'] = 'subService';
        $arr['service_id'] =  $profServiceList['service_id'];
        $profSubServiceList = $professionalsClass->GetProfessionalServices($arr);
    }
    // Getting Professional other details
    $ProfOtherDtls=$professionalsClass->GetProfessionalOtherDtlsById($arr);
    // Get All Location
    $LocationList=$commonClass->GetAllLocations();
    // Get All Services 
    $ServiceList=$commonClass->GetAllServices();
    $param['service_id'] =  $profServiceList['service_id'];
    $subServiceList = (!empty($ProfDtls) ? $commonClass->getAllSubServices($param) : $commonClass->getAllSubServices());
    
    unset($arr['serviceType'], $arr['service_id'], $param);
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
                                    <option value="Nurse_Male"<?php if($_POST['title']=='Nurse_Male') { echo 'selected="selected"'; } else if($ProfDtls['title']=='Nurse(Male)') { echo 'selected="selected"'; }?>>Nurse(Male)</option>
                                    <option value="Nurse_Female"<?php if($_POST['title']=='Nurse_Female') { echo 'selected="selected"'; } else if($ProfDtls['title']=='Nurse(Female)') { echo 'selected="selected"'; }?>>Nurse(Female)</option>
                                 </select>
                            </label>
                        </div>
                </div>
                <div class="editform">
                    <label>Job Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="Job_type" id="Job_type" class="validate[required]">
                                    <option value=""<?php if($_POST['Job_type']=='') { echo 'selected="selected"'; } else if($ProfDtls['Job_type']=='') { echo 'selected="selected"'; } ?>>Job Type</option>
                                    <option value="Oncall"<?php if($_POST['Job_type']=='Oncall') { echo 'selected="selected"'; } else if($ProfDtls['Job_type']=='Oncall') { echo 'selected="selected"'; }?>>Oncall</option>
                                    <option value="Retainer"<?php if($_POST['Job_type']=='Retainer') { echo 'selected="selected"'; } else if($ProfDtls['Job_type']=='Retainer ') { echo 'selected="selected"'; }?>>Retainer</option>
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
                <!-- Add Services code start here -->
                <div class="editform list_select">
                    <label>Assign Services <span class="required">*</span></label>
                     <div class="value dropdown">
                         <div class="dd">
                            <select name="service_id" id="service_id" class="col-lg-4 paddingR0 dropdown multiselect form-control" onchange="getSubServices(this.value);">
                                <option value = "">Select Service</option>
                                <?php
                                    if(!empty($ServiceList))
                                    {
                                        foreach($ServiceList as $key => $valService)
                                        {
                                            if(!empty($_REQUEST['service_id']) && $valService['service_id'] == $_REQUEST['service_id']) {
                                                echo '<option value="' . $valService['service_id'] . '" selected="selected">' . $valService['service_title'] . '</option>';
                                            } else if (!empty($profServiceList) && $profServiceList['service_id'] == $valService['service_id']) {
                                                echo '<option value="' . $valService['service_id'] . '" selected="selected">' . $valService['service_title'] . '</option>';
                                            } else {
                                                echo '<option value="' . $valService['service_id'] . '">' . $valService['service_title'] . '</option>';
                                            }
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
                <!-- Add Services code ends here -->
                <!-- Add sub services code start here -->
                <div class="editform" id ="subServiceDivContent" style="<?php if (!empty($profSubServiceList)) { ?>display:block !important; <?php } else { ?>display:none;<?php } ?>">
                    <div id = "" class="editform list_select">
                        <label>Assign Sub Services <span class="required">*</span></label>
                        <div class="value dropdown">
                             <div class="dd">
                                <select name="sub_service_id[]" id="sub_service_id" class="col-lg-4 paddingR0 dropdown multiselect form-control" multiple="multiple">
                                    <?php
                                        if(!empty($subServiceList))
                                        {
                                            $subServiceSelectedIds = array();
                                            foreach ($profSubServiceList AS $key => $valsubServiceSeletedId) {
                                                $subServiceSelectedIds[] = $valsubServiceSeletedId['sub_service_id'];
                                            }
            
                                            for ($i = 0;$i < count($subServiceList); $i++)
                                            {
                                                if (!empty($_REQUEST[$i]['sub_service_id']) && $valSubService['sub_service_id'] == $_REQUEST[$i]['sub_service_id']) {
                                                    echo '<option value="' . $subServiceList[$i]['sub_service_id'] . '" selected="selected">' . $subServiceList[$i]['recommomded_service'] . '</option>';
                                                } else if (!empty($subServiceSelectedIds)) {
                                                    $class = '';
                                                    for($s=0; $s <= count($subServiceSelectedIds); $s++)
                                                    {
                                                        if($subServiceSelectedIds[$s] == $subServiceList[$i]['sub_service_id'])
                                                            $class = 'selected="selected"';
                                                    }
                                                    echo '<option '.$class.' value="' . $subServiceList[$i]['sub_service_id'] . '">' . $subServiceList[$i]['recommomded_service'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $subServiceList[$i]['sub_service_id'] . '">' . $subServiceList[$i]['recommomded_service'] . '</option>';
                                                }
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
                </div>
                <!-- Add sub services code ends here -->
                <!--
                <div class="editform list_select">
                    <label>Assign Services <span class="required">*</span></label>
                     <div class="value dropdown">
                         <div class="dd">
                            <select name="service_id[]" id="service_id" class="col-lg-4 paddingR0 dropdown multiselect form-control" multiple="multiple">
                                <?php /*
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
                                */?>
                            </select>
                        </div>
                     </div>
                </div>
                -->
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
                        <label>Rate</label>
                        <div class="value">
                            <input type="text" name="Physio_Rate" id="Physio_Rate" value="<?php if(!empty($_POST['Physio_Rate'])) { echo $_POST['Physio_Rate']; } else if(!empty($ProfDtls['Physio_Rate'])) { echo $ProfDtls['Physio_Rate']; } else { echo ""; } ?>" class="validate[maxSize[5]] form-control" onkeyup="if (/[^.0-9]/g.test(this.value)) this.value = this.value.replace(/[^.0-9 ]/g,'')"  maxlength="5" style="width:100% !important;" />
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
else if($_REQUEST['action']=='vw_professional_leave_dtls')
{
     $serviceProfessionalId =$_REQUEST['service_professional_id'];
     ?>
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"> Professional Leaves List</h4>
    </div>
    <?php 
    // Get Professional Leave Details
    $ProfLeaveDtls = $professionalsClass->getProfessionalLeaveList($serviceProfessionalId);
    ?>
    <div class="modal-body">
    <?php
        if (!empty($ProfLeaveDtls)) {
            $i = 1;
            foreach ($ProfLeaveDtls AS $key => $valProfLeave) {
                ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="editform">
                                <h4 class="modal-title"> <?php echo $i .". "; ?>  Applied leave date </h4>
                                <div class="value">
                                </div>
                            </div>
                            <div class="editform">
                                <label>Leave Date</label>
                                <div class="value">
                                    <?php if (!empty($valProfLeave['date_form']) && $valProfLeave['date_form'] != '0000-00-00') { 
                                             echo date('d M Y',strtotime($valProfLeave['date_form'])); 
                                        } if (!empty($valProfLeave['date_to']) && $valProfLeave['date_to'] != '0000-00-00') {
                                            if ($valProfLeave['date_form'] != $valProfLeave['date_to']) {
                                                echo " TO " . date('d M Y',strtotime($valProfLeave['date_to']));
                                            }
                                        } 
                                    ?>
                                </div>
                            </div>
                            
                            <div class="editform">
                                <label>Leave Reason</label>
                                <div class="value">
                                    <?php echo $valProfLeave['Note']; ?>
                                </div>
                            </div>
                            
                            <div class="editform">
                                <label>Status</label>
                                <div class="value">
                                    <?php echo $valProfLeave['leaveConflictStatus']; ?>
                                </div>
                            </div>
                            
                            <div class="editform">
                                <label>Approved Status</label>
                                <div class="value">
                                    <?php echo $valProfLeave['leaveStatus']; ?>
                                </div>
                            </div>
                            <?php if ($valProfLeave['Leave_status'] != '2') { ?>
                                <div class="editform" id="leaveActionDiv_<?php echo $valProfLeave['professional_weekoff_id']; ?>">
                                    <label>Choose Action</label>
                                    <div class="value">
                                        <select name="leave_status_<?php echo $valProfLeave['professional_weekoff_id']; ?>" id="leave_status_<?php echo $valProfLeave['professional_weekoff_id']; ?>" onChange="javascript: return changeLeaveStatus('<?php echo $serviceProfessionalId; ?>','<?php echo $valProfLeave['professional_weekoff_id']; ?>');" <?php if ($valProfLeave['Leave_status'] == '2' || $valProfLeave['Leave_status'] == '5') { echo "disabled"; } ?>>
                                            <option value=""<?php if ($valProfLeave['Leave_status'] == '') { echo 'selected="selected"'; } ?>>Choose action</option>
                                            <option value="2"<?php if ($valProfLeave['Leave_status'] == '2') { echo 'selected="selected"'; } ?>>Approve</option>
                                            <option value="4"<?php if ($valProfLeave['Leave_status'] == '4') { echo 'selected="selected"'; } ?>>Reject</option>
                                        </select>
                                        <div class="clearfix"></div>
                                        <div id="RejectionReasonDiv_<?php echo $valProfLeave['professional_weekoff_id']; ?>" class="marginT10" style="<?php if ($valProfLeave['Leave_status'] != '4') { echo 'display:none;'; } ?>">
                                            <textarea name="rejection_reason_<?php echo $valProfLeave['professional_weekoff_id']; ?>" id="rejection_reason_<?php echo $valProfLeave['professional_weekoff_id']; ?>" rows="5" cols="5" class="form-control" onkeydown="keyDownFunction(event, <?php echo $valProfLeave['professional_weekoff_id']; ?>)"><?php echo $valProfLeave['rejection_reason']; ?></textarea>
                                            <span class="formErrorSelf leaveNotiMsg" style="<?php if (!empty($valProfLeave['rejection_reason']) && strlen($valProfLeave['rejection_reason']) >= 10) { echo 'display:none'; } else { 'display:block'; } ?>">Charcter limit Min :10 & Max :100</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 float-left">
                                        <input type="button" value="Save" name="btn_update_<?php echo $valProfLeave['professional_weekoff_id']; ?>" id="btn_update_<?php echo $valProfLeave['professional_weekoff_id']; ?>" class="btn btn-download" onclick="return updateLeaveStatus(<?php echo $serviceProfessionalId; ?>,<?php echo $valProfLeave['professional_weekoff_id']; ?>)" <?php if ($valProfLeave['Leave_status'] == '5') { echo "disabled"; } ?>/>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                $i++;
            }
        }
    ?>
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
        $Job_type=strip_tags($_POST['Job_type']);
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
        $service_id = $_POST['service_id'];
        $sub_service_ids = $_POST['sub_service_id'];
        // Professional Other Details
        $detail_id=strip_tags($_POST['detail_id']);
        $qualification=strip_tags($_POST['qualification']);
        $specialization=strip_tags($_POST['specialization']);
        $skill_set=strip_tags($_POST['skill_set']);
        $work_experience=strip_tags($_POST['work_experience']);
        $Physio_Rate = strip_tags($_POST['Physio_Rate']);
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
        if($service_id == '')
        {
             $success=0;
             $errors[$i++]="Please select service";
        }
        if ($sub_service_ids == '') {
            $success = 0;
            $errors[$i++] = "Please select sub service";
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
            $arr['Job_type']=$Job_type;
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
            $arr['Physio_Rate']= $Physio_Rate;
            $arr['location_id']=$location_id;
            $arr['location_id_home']=$location_id_home;
            $arr['set_location']=$set_location;
            $arr['service_id']=$service_id;
            $arr['sub_service_id']=$sub_service_ids;
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
            $InsertRecord = $professionalsClass->AddProfessional($arr); 
            
            //Add employee Spero
            $arr_new['birth_date']=$dob;
            $arr_new['fname']=$title.' '.ucwords($first_name).' '.ucwords($name);
            $arr_new['mobile_no']=$mobile_no;
            $arr_new['DOJ']=date('Y-m-d H:i:s');
            $arr_new['status']='1';
            $InsertRecord_new = $professionalsClass->Add_emp_Spero($arr_new); 
            

            if(!empty($InsertRecord))
            {
                
                // Insert Professional Other Details
                if(!empty($service_professional_id))
                    $arg['service_professional_id']=$service_professional_id;
                else 
                   $arg['service_professional_id']= $InsertRecord;
                
                
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
    
    if (!empty($ProfDtls)) {
        // Get Professional service details
        $profServiceList = $professionalsClass->GetProfessionalServices($arr);
        
        // Get Professional sub service details
        $arr['serviceType'] = 'subService';
        $arr['service_id'] = $profServiceList['service_id'];
        $profSubServiceList = $professionalsClass->GetProfessionalServices($arr);
        unset($arr['serviceType']);
    }
    
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
                <label>Assigned Services</label>
                <div class="value">
                    <?php if(!empty($profServiceList)) {
                            echo (!empty($profServiceList)) ? $profServiceList['service_title'] : 'Not Available';
                        } else {  
                            echo "Not Available"; 
                        } 
                    ?>
                </div>
            </div>
            <div class="editform">
                <label>Assigned Sub Services</label>
                <div class="value">
                    <?php if(!empty($profSubServiceList)) {
                            $subServiceList = '';
                            foreach ($profSubServiceList AS $key => $valSubService) {
                                $subServiceList .= $valSubService['recommomded_service'] . "," . "<br/>";
                            }

                            echo (!empty($subServiceList)) ? rtrim(trim($subServiceList), ",") : 'Not Available';
                        } else {  
                            echo "Not Available"; 
                        } 
                    ?>
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
else if($_REQUEST['action']=='Add_professional_Bank_details')
{
    // Getting Professional Details
    $profId = $_REQUEST['service_professional_id'];
    $bankDtls = $professionalsClass->getProfBankDtlsByProfId($profId);
    ?>
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if (!empty($bankDtls)) { echo "Edit"; } else { echo "Add"; } ?> Bank Details</h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_bank_dtls" id="frm_add_bank_dtls" method="post" action ="professional_ajax_process.php?action=add_professional_bank_dtls" autocomplete="off">
            <div class="scrollbars">
                <div class="editform">
                    <label>Account Name<span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="hid_id" id="hid_id" value="<?php if (!empty($bankDtls['id'])) { echo $bankDtls['id']; } ?>" />
                        <input type="hidden" name="hid_service_professional_id" id="hid_service_professional_id" value="<?php if (!empty($profId)) { echo $profId; } ?>" />
                        <input type="text" name="Account_Name" id="Account_Name"  value="<?php if (!empty($bankDtls['Account_name'])) { echo $bankDtls['Account_name']; } else if (!empty($_POST['Account_name'])) { echo $_POST['Account_name']; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Account Number<span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="Account_Number" id="Account_Number" value="<?php if (!empty($bankDtls['Account_number'])) { echo $bankDtls['Account_number']; } else if (!empty($_POST['Account_number'])) { echo $_POST['Account_number']; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Bank Name<span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="Bank_Name" id="Bank_Name" value="<?php if (!empty($bankDtls['Bank_name'])) { echo $bankDtls['Bank_name']; } else if (!empty($_POST['Bank_name'])) { echo $_POST['Bank_name']; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Brach</label>
                    <div class="value">
                        <input type="text" name="Branch_Name" id="Branch_Name" value="<?php if (!empty($bankDtls['Branch'])) { echo $bankDtls['Branch']; } else if (!empty($_POST['Branch'])) { echo $_POST['Branch']; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>IFSC Code</label>
                    <div class="value">
                        <input type="text" name="IFSC_Code" id="IFSC_Code" value="<?php if (!empty($bankDtls['IFSC_code'])) { echo $bankDtls['IFSC_code']; } else if (!empty($_POST['IFSC_code'])) { echo $_POST['IFSC_code']; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Account Type</label>
                    <div class="value">
                        <select  class="form-control" name="Account_Type" id="Account_Type" style="width:100% !important;">
                            <option value=""<?php if($_POST['Account_Type'] == '') { echo 'selected="selected"'; } else if($bankDtls['Account_type'] == '') { echo 'selected="selected"'; }?>>Select Account Status</option>
                            <option value="Current"<?php if($_POST['Account_Type'] == 'Current') { echo 'selected="selected"'; } else if($bankDtls['Account_type'] == 'Current') { echo 'selected="selected"'; }?>>Current</option>
                            <option value="Saving"<?php if($_POST['Account_Type'] == 'Saving') { echo 'selected="selected"'; } else if($bankDtls['Account_type'] == 'Saving') { echo 'selected="selected"'; }?>>Saving</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer" align="center">
                <input type="button" class="btn btn-download" name="submitForm" id="submitForm" value="Save Changes" onclick="return add_professional_bank_details(<?php echo $professional_id; ?>);" />
            </div>  
        </form>
    </div>
    <?php
}
else if($_REQUEST['action']=='vw_professional_Feedback_list')
{
// Getting Professional Details
$arr['service_professional_id']=$_REQUEST['service_professional_id'];
    $ProfDtls=$professionalsClass->API_GetProfessionalById_document_list($arr);
    // Getting Professional other details
    $ProfOtherDtls=$professionalsClass->GetProfessionalOtherDtlsById($arr);
    $professional_id=$arr['service_professional_id'];
    
?>
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($ProfDtls)) { echo "Edit"; } else { echo "Add"; } ?> Professional Feedback</h4>
</div>

<div class="row" >
 <?php //if(!empty($ProfDtls['Services'])) { echo $ProfDtls['Services']; } else {  echo "-"; } ?>
         <?php    
        $professional_list= mysql_query("SELECT * FROM sp_feedback_for_app  where professional_id='$professional_id'");
        $row_count = mysql_num_rows($professional_list);
        if($row_count > 0)
        {
            $Count=1;
            while ($professional_list_rows = mysql_fetch_array($professional_list))
            {
                
                $feedback=$professional_list_rows['feedback']; 
                $added_date=$professional_list_rows['added_date'];
            
            
            ?>
            
            <div class="row">
            <div class="col-lg-12">
            <div class="col-lg-12">
            <label style="font-size:15px;margin-left:10px"><?php echo $Count; ?> ] <?php echo $feedback; ?> - <?php echo $added_date; ?></label> 
            </div>
            
            
            </div>
        </div>
        
        <br>    
            <?php
            $Count++;
        }
        }
        ?>
    
</div>

<?php
    
}
else if($_REQUEST['action']=='vw_professional_paytm_payment_list')
{
    $orderId=$_REQUEST['orderId'];
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Paytm Payment Details</h4>
    </div>
    <div class="row" >
    <table class="table table-hover table-bordered" style="width:90%;margin-left:30px;">
                <tr> 
                <th width="15%">Event ID</th>
                <th width="15%">Amount</th>
                <th width="20%">Received Date</th>
                </tr>
        
 <?php //if(!empty($ProfDtls['Services'])) { echo $ProfDtls['Services']; } else {  echo "-"; } ?>
         <?php    
        $payments_details= mysql_query("SELECT * FROM sp_payments_received_by_professional  where Transaction_ID='$orderId'");
        $row_count = mysql_num_rows($payments_details);
        if($row_count > 0)
        {
            $Count=1;
            while ($payment_rows = mysql_fetch_array($payments_details))
            {
                $event_id=$payment_rows['event_id'];
                $event_details= mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
                $event_data = mysql_fetch_array($event_details);
                $event_code=$event_data['event_code'];
                $amount=$payment_rows['amount'];
                $added_date=$payment_rows['date_time'];
                $type=$payment_rows['type'];
                $cheque_path_id=$payment_rows['cheque_path_id'];
                
                
            ?>
            <tr> 
                <td width="15%"><?php echo $event_code; ?></td>
                <td width="15%"><?php echo $amount; ?></td>
                <td width="15%"><?php echo  date('d M Y h:i A', strtotime($added_date)); ?></td>
                
           </tr>
        
            
            <?php
            $Count++;
            }
        }
        ?>
    </table>
</div>
    <?php
}
else if($_REQUEST['action']=='vw_professional_with_payment_list')
{
    $professionalId = $_REQUEST['service_professional_id'];
    $type = $_REQUEST['type'];
    
    $type = $_REQUEST['type'];
    $eventFromDate = (!empty($_REQUEST['eventFromDate']) ? date('Y-m-d', strtotime($_REQUEST['eventFromDate'])) : '');
    $eventToDate = (!empty($_REQUEST['eventToDate']) ? date('Y-m-d', strtotime($_REQUEST['eventToDate'])): '');
	//Get Professional payment details
    $profPaymentList = $professionalsClass->professionalPaymentList($professionalId, $type, $eventFromDate, $eventToDate);
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"> <?php echo $type; ?> Amount <?php if ($type == 'Received') { echo "From"; } else { echo "With"; } ?> Professional</h4>
    </div>
    <div class="row" >
    <table class="table table-hover table-bordered" style="width:90%;margin-left:30px;">
                <tr> 
                <th width="15%">Event ID</th>
				<th width="25%">Patient Name</th>
                <th width="14%">Amount</th>
                <!--<th width="17%">Payment Type</th> -->
                <th width="14%">Payment Mode</th>
                <th width="20%">Added Date</th>
				<th width="12%">Action</th>
                </tr>
         <?php

			if (!empty($profPaymentList)) {
				foreach ($profPaymentList AS $key => $valPayment) {
					if (!empty($valPayment['Url_path'])) {
						$imageUrl = $paymentChequeUrl.$valPayment['Url_path'];
					}
					?>
                        <tr>
                            <td width="15%">
								<?php echo $valPayment['event_code']; ?>
							</td>
							
							<td width="25%">
								<?php echo $valPayment['patient_name'] . 
								'<br> (' . $valPayment['hhc_code'] .')' .
								'<br> (' . $valPayment['patient_mobile_no'] .')'; ?>
							</td>
							
                            <td width="14%">
								<?php echo $valPayment['amount']; ?>
							</td>
                            <!--
							<td width="17%">
								<?php if (!empty($valPayment['paymentTypeVal'])) { echo $valPayment['paymentTypeVal']; } else { echo "NA"; } ?>
							</td>
							-->
                            <td width="14%">
								<?php if (!empty($valPayment['paymentModeVal'])) { echo $valPayment['paymentModeVal']; } else { echo "NA"; } ?>
							</td>
                            <td width="20%">
								<?php if (!empty($valPayment['date_time']) && $valPayment['date_time'] != '0000-00-00 00:00:00') { 
										echo date('d M Y', strtotime($valPayment['date_time'])) .'<br>' . date('h:i A', strtotime($valPayment['date_time']));
									} else { echo "NA"; }
								?>
							</td>
                            <td width="12%">
								<?php if (!empty($imageUrl)) {
								?>
									<a href="<?php echo $imageUrl; ?>" target="_blank">View</a>
								<?php } else {
									echo "NA";
								} ?>
							</td>
                        </tr>
                    <?php 
					unset($imageUrl);
				}
			} else {
				?>
					<tr>
						<td colspan="6" style="text-align: center !important;">
							<span style="color: red !important;">
								<b>No payment details available</b>
							</span>
						</td>
					</tr>
				<?php
			}
        ?>
    </table>
</div>
    <?php
}
else if($_REQUEST['action']=='vw_professional_document_list')
{   
    // Getting Professional Details
    $serviceProfessionalId = $_REQUEST['service_professional_id'];
    
    //Get professional service details
    $arg['service_professional_id'] = $serviceProfessionalId;
    $profServiceDtls = $professionalsClass->GetProfessionalServices($arg);
    unset($arg);
    
    // Get all document list by service id
    if (!empty($profServiceDtls)) {
        $documentList = $professionalsClass->getDocumentsListByServiceId($profServiceDtls['service_id']);
        
        
        
        // Get all documents by professional id
        $profDocumentList = $professionalsClass->getProfDocumentsList($serviceProfessionalId);
        
                $profDocumentListIds = array();
                $profDocumentListNew = ""; 
                if (!empty($profDocumentList)) {
                    foreach ($profDocumentList AS $key => $valProfDocument) {
                        $profDocumentListIds[] = $valProfDocument['document_list_id'];
                    }
                    // combine both array for unique keys
                    $profDocumentListNew = array_combine($profDocumentListIds, $profDocumentList);
                }
                        
                      $resultantArr = array();
                
                foreach($documentList AS $key => $valDocument) {
                    $docListId = $valDocument['document_list_id'];
                    
                    if ($valDocument['document_list_id'] == $profDocumentListNew[$docListId]['document_list_id']) {
                        
                        $valDocument['Documents_id'] = $profDocumentListNew[$docListId]['Documents_id'];
                        $valDocument['url_path'] = $profDocumentListNew[$docListId]['url_path'];
                        $valDocument['rejection_reason'] = $profDocumentListNew[$docListId]['rejection_reason'];
                        $valDocument['status'] = $profDocumentListNew[$docListId]['status'];
                        
                    } else {
                        $valDocument['Documents_id'] = "";
                        $valDocument['url_path'] = "";
                        $valDocument['rejection_reason'] = "";
                        $valDocument['status'] = "";
                        
                    }
                    
                    $resultantArr[] = $valDocument;
                }  
       
       
        $profDocumentList = array();
        if (!empty($resultantArr)) {
           $profDocumentList = $resultantArr;
          
        }
        
        
        //$profDocumentList = $professionalsClass->getProfessionalDocumentsList($serviceProfessionalId, $profServiceDtls['service_id']);
    }
    
    
?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		<h4 class="modal-title">
			<?php if(!empty($ProfDtls)) { echo "Edit"; } else { echo "Add"; } ?> professional document
		</h4>
	</div>
	<div class="modal-body">
		<?php if (!empty($profDocumentList)) { 
			$i = 1;
			foreach ($profDocumentList AS $key => $valProfDocument) { ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="col-lg-6">
							<?php echo $i . ". " . $valProfDocument['Documents_name'];
							if ($valProfDocument['isManadatory'] == '2') {
								echo '&nbsp;<span class="required">*</span>';
							}
						//	$imageUrl = $ProfDocument.$valProfDocument['url_path'];
							//$ext = pathinfo($imageUrl, PATHINFO_EXTENSION);
						//	echo $ext;
							if (!empty($valProfDocument['url_path']) /*&& file_exists($imageUrl)*/) {
								$imageUrl = $ProfDocument.$valProfDocument['url_path'];
								
								//Get File extension
                                $fileData = explode('.', $valProfDocument['url_path']);
                                $fileExtension = '';
                                if (!empty($fileData)) {
                                    $fileExtension = ($fileData[1] ? $fileData[1] : '');
                                }

                                //JPEG, JPG, PNG
                                // PDF
                                $thumbnailImage = '';
                                if (!empty($fileExtension)) {
                                    if (strtolower($fileExtension) == 'pdf') {
                                        $thumbnailImage = "images/pdficon.png";
                                    } else if (strtolower($fileExtension) == 'jpeg'
                                        || strtolower($fileExtension) == 'jpg'
                                        || strtolower($fileExtension) == 'png') {
                                        $thumbnailImage = $imageUrl;
                                    }
                                }
                                
                              //  echo "Hi". $imageUrl;
                                
								echo "<br/><span><a href='$imageUrl' target='_blank'><img src='$thumbnailImage' height='100px' width='100px' /></a></span>
								<div id='docImage' style='display:none'></div>";
							}
							?>
							
						</div>
						<div class="col-lg-4">
						    
							<select class="chosen-select form-control" name="doc_Status_<?php echo $valProfDocument['document_list_id']; ?>" id="doc_Status_<?php echo $valProfDocument['document_list_id']; ?>" onChange="javascript: return changeDocumentStatus('<?php echo $serviceProfessionalId; ?>','<?php echo $valProfDocument['document_list_id']; ?>');" <?php if ($valProfDocument['status'] == '1' || empty($valProfDocument['url_path'])) { echo "disabled"; } ?>>
								<option value=""<?php if ($valProfDocument['status'] == '') { echo 'selected="selected"'; } ?>>Choose action</option>
								<option value="1"<?php if ($valProfDocument['status'] == '1') { echo 'selected="selected"'; } ?>>Verified</option>
								<option value="3"<?php if ($valProfDocument['status'] == '3') { echo 'selected="selected"'; } ?>>Reject</option>
							</select>
							<div class="clearfix"></div>
							<div id="RejectionReasonDiv_<?php echo $valProfDocument['document_list_id']; ?>" class="marginT10 marginB20" style="<?php if ($valProfDocument['status'] != '3') { echo 'display:none;'; } ?>">
								<textarea name="rejection_reason_<?php echo $valProfDocument['document_list_id']; ?>" id="rejection_reason_<?php echo $valProfDocument['document_list_id']; ?>" rows="5" cols="5" class="form-control" onkeydown="keyDownFunction(event, <?php echo $valProfDocument['document_list_id']; ?>)"><?php echo $valProfDocument['rejection_reason']; ?></textarea>
								<span class="formErrorSelf docNotiMsg" style="<?php if (!empty($valProfDocument['rejection_reason']) && strlen($valProfDocument['rejection_reason']) >= 10) { echo 'display:none'; } else { 'display:block'; } ?>">Charcter limit Min :10 & Max :100</span>
							</div>
						</div>
						<div class="col-lg-2 marginB20">
							<input type="button" name="btn_update_<?php echo $valProfDocument['document_list_id']; ?>" id="btn_update_<?php echo $valProfDocument['document_list_id']; ?>" value="Save" class="btn btn-download" align="center" onclick="return updateDocumentStatus(<?php echo $serviceProfessionalId; ?>,<?php echo $valProfDocument['document_list_id']; ?>,<?php echo $valProfDocument['Documents_id']; ?>);" <?php if ($valProfDocument['status'] == '1' || empty($valProfDocument['url_path'])) { echo "disabled"; } ?> />
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
		<?php 
			$i++;
			} 
		} ?>
		<div class="row">
			<div class="col-lg-12 text-center">
				<input type="button" value="Final Approval" class="btn btn-download" onclick="return updateDocumentFinalStatus(<?php echo $serviceProfessionalId; ?>);">
			</div>
		</div>
	</div>
	
	<div class="clearfix"></div>
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
    
    $Selcfromdate = date('d-m-Y', strtotime('-15 days', strtotime($todaysdate)));
    $SelctoDate = date('d-m-Y', strtotime('+30 days', strtotime($todaysdate)));
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
    } else if($_REQUEST['action'] == 'getSubServices') {
        $serviceId = $_REQUEST['service_id'];
        $checkSubServiceExist = "SELECT sub_service_id, recommomded_service FROM sp_sub_services WHERE service_id = '" . $serviceId . "'";
        if(mysql_num_rows($db->query($checkSubServiceExist)))
        {
            $subServicesList = $professionalsClass->GetAllServicesByServiceId($serviceId);
            if (!empty($subServicesList)) {
                echo '<div id = "" class="editform list_select">
                        <label>Assign Sub Services <span class="required">*</span></label>
                        <div class="value dropdown">
                            <div class="dd">
                                <select name="sub_service_id[]" id="sub_service_id" class="col-lg-4 paddingR0 dropdown multiselect form-control" multiple="multiple">';
                                    foreach($subServicesList as $key => $valSubService) {
                                        if(!empty($_REQUEST['sub_service_id']) && $valSubService['sub_service_id'] == $_REQUEST['sub_service_id']) {
                                            echo '<option value="' . $valSubService['sub_service_id'] . '" selected="selected">' . $valSubService['recommomded_service'] . '</option>';
                                        } else {
                                            echo '<option value="' . $valSubService['sub_service_id'] . '">' . $valSubService['recommomded_service'] . '</option>';
                                        }
                                    }
                                echo '</select>
                            </div>
                        </div>
                    </div>';
            }
        }
        else
        {
            echo 'error';
            exit;
        }
    } else if ($_REQUEST['action'] == 'add_professional_bank_dtls') {
        $success=0;
        $errors=array(); 
        $i=0;
        if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
        {
            $recordId = strip_tags($_POST['hid_id']);
            $servicePrfId = strip_tags($_POST['hid_service_professional_id']);
            $accountName = $_POST['Account_Name'];
            $acccountNumber = strip_tags($_POST['Account_Number']);
            $bankName = strip_tags($_POST['Bank_Name']);
            $branchName = $_POST['Branch_Name'];
            $ifscCode = strip_tags($_POST['IFSC_Code']);
            $accountType = strip_tags($_POST['Account_Type']);
            
            if($accountName == '')
            {
                $success=0;
                $errors[$i++]="Please enter account name";
            }
            
            if($acccountNumber == '')
            {
                $success=0;
                $errors[$i++]="Please enter account number";
            }
            
            
            if($bankName == '')
            {
                $success=0;
                $errors[$i++]="Please enter bank name";
            }
            
            if(count($errors))
            {
                echo 'validationError';
                exit;
            } else {
                $success = 1;
                $arr['id'] = $recordId;
                $arr['Professional_id'] = $servicePrfId;
                $arr['Account_name'] = $accountName;
                $arr['Account_number'] = $acccountNumber;
                $arr['Bank_name'] = $bankName;
                $arr['Branch'] = $branchName;
                $arr['IFSC_code'] = $ifscCode;
                $arr['Account_type'] = $accountType;
                
                $InsertRecord = $professionalsClass->addProfBankDtls($arr); 
                if(!empty($InsertRecord))
                {
                    if($recordId)
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
            }
        }
    } else if ($_REQUEST['action'] == 'update_document_status') {
        
        $arr = array();
        
        $arr['service_professional_id'] = $_REQUEST['service_professional_id'];
        $arr['Documents_id'] = $_REQUEST['Documents_id'];
        $arr['document_list_id'] = $_REQUEST['document_list_id'];
        $arr['status'] = $_REQUEST['document_status'];
        $arr['rejection_reason'] = $_REQUEST['rejection_reason'];
        $updateRecord = $professionalsClass->updateDocumentStatus($arr);
        
        if($updateRecord)
        {
            if ($updateRecord == 1) {
                echo 'UpdateSuccess';
            } else if ($updateRecord == 2) {
                echo 'NotificationError';
            }
            exit;
        }
        else 
        {
            echo 'Error';
            exit;
        }
        
    } else if ($_REQUEST['action'] == 'update_document_final_status') {
        
        $arr = array();
        
        $arr['service_professional_id'] = $_REQUEST['service_professional_id'];
        $updateRecord = $professionalsClass->updateDocumentFinalStatus($arr);
        
        if($updateRecord)
        {
            if ($updateRecord == 1) {
                echo 'UpdateSuccess';
            } else if ($updateRecord == 2) {
                echo 'NotificationError';
            }
            exit;
        }
        else 
        {
            echo 'Error';
            exit;
        }
        
    } else if ($_REQUEST['action'] == 'update_leave_status') {
        
        $arr = array();
        
        $arr['service_professional_id'] = $_REQUEST['service_professional_id'];
        $arr['professional_weekoff_id'] = $_REQUEST['professional_weekoff_id'];
        $arr['Leave_status'] = $_REQUEST['leave_status'];
        $arr['rejection_reason'] = $_REQUEST['rejection_reason'];
        $updateRecord = $professionalsClass->updateLeaveStatus($arr);
        
        if($updateRecord)
        {
            if ($updateRecord == 1) {
                echo 'UpdateSuccess';
            } else if ($updateRecord == 2) {
                echo 'NotificationError';
            }
            exit;
        }
        else 
        {
            echo 'Error';
            exit;
        }
        
    }
    
     else if ($_REQUEST['action'] == 'showMap') {
        $serviceProfessionalId =$_REQUEST['profID'];
        $locationId =$_REQUEST['locationId'];
        // Get Professional Location preference Details
        $profLocPrefDtls = $professionalsClass->getProfLocationPreferences($locationId);
        if (!empty($profLocPrefDtls)) {
            /* Convert data to json */
            echo json_encode($profLocPrefDtls);
        }
    } else if ($_REQUEST['action'] == 'remove_location') {
        $serviceProfessionalId =$_REQUEST['profID'];
        $locationId =$_REQUEST['locationId'];

        if (!empty($serviceProfessionalId) && !empty($locationId)) {
            // check is it active availabilty preference present for this prefernece
			$chkActiveAvailabiltySql = "SELECT professional_availability_detail
                FROM sp_professional_availability_detail 
                WHERE professional_location_id = '" . $locationId . "' ";

            // echo '<pre>chkActiveAvailabiltySql <br>';
            // print_r($chkActiveAvailabiltySql);
            // echo '</pre>';
            
            if (mysql_num_rows($db->query($chkActiveAvailabiltySql)) == 0) {
                $result = $professionalsClass->removeProfLocationPreferences($serviceProfessionalId, $locationId);

                // echo '<pre>result <br>';
                // print_r($result);
                // echo '</pre>';
                if (!empty($result)) {
                    echo "Success";
                    exit;
                } else {
                    echo "Error";
                    exit;
                }
            } else {
                echo "ActiveAvailabilityExists";
                exit;
            }
        } else {
            echo "MissingParam";
            exit;
        }
    } else if ($_REQUEST['action'] == 'vw_add_availability') {
        // Getting Professional Details
        $arr = array();
        $arr['service_professional_id'] = $_REQUEST['service_professional_id'];
        $profDtls = $professionalsClass->GetProfessionalById($arr);

        // Getting days values
        $timestamp = strtotime('next Sunday');
        $days = array();
        for ($i = 0; $i < 7; $i++) {
            $days[] = strftime('%A', $timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }

        //Getting professional location details
        $profLocationList = $professionalsClass->profLocationPrefList($_REQUEST['service_professional_id']);
        ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> Add Professional Availability</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline" name="frm_add_availability" id="frm_add_availability" method="post" action ="professional_ajax_process.php?action=add_professional_availability" autocomplete="off">
                    <div class="scrollbars">
                        <div class="row">
                            <!-- Display days section start here -->
                            <div class="form-row">
                                <?php if (!empty($days)) {
                                    $i = 0;
                                    echo '<div class="form-group col-md-3">
                                            <input type="checkbox" name="checkAll" id="checkAll" value="" /> Select All
                                        </div>';
                                    foreach ($days AS $key => $valDay) {
                                        $key++;
                                        echo '<div class="form-group col-md-3">
                                                <input type="checkbox" name="chk_day" id="chk_day_' . $key . '" class="check_class" value="' . $key . '" /> ' . $valDay . '
                                              </div>';
                                    }
                                }
                                ?>
                            </div>
                            <input type="hidden" name="selectedDays" id="selectedDays" value="" />
                            <input type="hidden" name="service_professional_id" id="service_professional_id" value="<?php echo $arr['service_professional_id']; ?>" />
                            <!-- Display days section ends here -->

                            <!-- Add time section start here -->
                            <div class="datepairExample_0" style="padding-top:85px !important;">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        Select Time
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input name="starttime_0_0" id="starttime_0_0" type="text" class="form-control time start validate_time ui-timepicker-input" placeholder="From Time" autocomplete="off" style="margin-bottom:15px;">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input name="endtime_0_0" id="endtime_0_0" type="text" class="form-control time end validate_time ui-timepicker-input" placeholder="To Time" autocomplete="off" style="margin-bottom:15px;">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <div style="padding:5px;">
                                            <a href="javascript:void(0);" title="Add" onclick="javascript:addRow(0);"><img src="images/add.png"></a>
                                            <a href="javascript:void(0);" title="Remove" onclick="javascript:removeRow(0);"><img src="images/remove1.png"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="extras_0" id="extras_0" value="0" />
                            <!-- Add time section ends here -->

                            <!-- Add multiple row code start here -->
                            <div id="div_1_0">
                            </div>
                            <!-- Add multiple row code start here -->

                            <!-- Location section start here -->
                            <div class="clearfix"></div>
                            <?php
                                if (!empty($profLocationList)) {
                            ?>
                            <div class="form-row" style="padding-top:25px !important;">
                                <div class="form-group col-md-2">
                                    Location
                                </div>
                                <div class="form-group col-md-6">
                                    <?php
                                        if (!empty($profLocationList)) {
                                            echo '<select name="location" id="location">';
                                            echo "<option value = ''>-Select location-</option>";
                                            foreach ($profLocationList AS $valLocation) {
                                                echo '<option value = "' . $valLocation['Professional_location_id'] . '">' . $valLocation['Name'] . '</option>';
                                            }
                                            echo '</select>';
                                        }
                                    ?>
                                </div>
                                <div class="form-group col-md-4">
                                </div>
                            </div>
                            <?php } ?>
                            <!-- Location section ends here -->
                    </div>
                    <div class="modal-footer">
                        <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_professional_availability_submit();" />
                    </div>  
                </form>
            </div>  
        <?php
    } else if ($_REQUEST['action'] == 'vw_edit_availability') {
        // Getting Professional Details
        $arr = array();
        $arr['service_professional_id'] = $_REQUEST['service_professional_id'];
        $profDtls = $professionalsClass->GetProfessionalById($arr);

        //Getting professional availability details
        $profAvailabilityDtls = $professionalsClass->getAvailabilityDtlsById($_REQUEST['professional_avaibility_id']);

        //echo '<pre>profAvailabilityDtls <br>';
        //print_r($profAvailabilityDtls);
        //echo '</pre>';

        //Getting professional location details
        $profLocationList = $professionalsClass->profLocationPrefList($_REQUEST['service_professional_id']);

        //echo '<pre>profLocationList <br>';
        //print_r($profLocationList);
        //echo '</pre>';
        ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> Edit Professional Availability</h4>
            </div>
            <div class="modal-body">
                <form class="form-inline" name="frm_edit_availability" id="frm_edit_availability" method="post" action ="professional_ajax_process.php?action=edit_professional_availability" autocomplete="off">
                    <div>
                        <div class="row">
                            <div class="datepairExample_0">
                                <div class="form-row">
                                    <?php if (!empty($profAvailabilityDtls)) {
                                        foreach ($profAvailabilityDtls AS $key => $availabilityDtl) {
                                            ?>
                                                <div class="form-group col-md-2">
                                                    Time
                                                    <input type="hidden" name="professional_availability_detail_<?php echo $key; ?>" id="professional_availability_detail_<?php echo $key; ?>" value="<?php echo $availabilityDtl['professional_availability_detail']; ?>" />
                                                    <input type="hidden" name="professional_location_id_<?php echo $key; ?>" id="professional_location_id_<?php echo $key; ?>" value="<?php echo $availabilityDtl['professional_location_id']; ?>" />
                                                    <input type="hidden" name="professional_availability_id_<?php echo $key; ?>" id="professional_availability_id_<?php echo $key; ?>" value="<?php echo $availabilityDtl['professional_availability_id']; ?>" />
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <input name="starttime_<?php echo $key; ?>_0" id="starttime_<?php echo $key; ?>_0" type="text" class="form-control time start validate_time ui-timepicker-input" placeholder="From Time" autocomplete="off" style="margin-bottom:15px;" value="<?php if (!empty($availabilityDtl['start_time'])) { echo $availabilityDtl['start_time']; } ?>" />
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <input name="endtime_<?php echo $key; ?>_0" id="endtime_<?php echo $key; ?>_0" type="text" class="form-control time end validate_time ui-timepicker-input" placeholder="To Time" autocomplete="off" style="margin-bottom:15px;" value="<?php if (!empty($availabilityDtl['end_time'])) { echo $availabilityDtl['end_time']; } ?> " />
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <div style="padding:5px;">
                                                        <?php if ($key == 0) {
                                                            echo '<a href="javascript:void(0);" title="Add" onclick="javascript:addRow(0);"><img src="images/add.png"></a>
                                                                <a href="javascript:void(0);" title="Remove" onclick="javascript:removeRow(0);"><img src="images/remove1.png"></a>';
                                                        } else {
                                                            echo '<a href="javascript:void(0);" title="Remove" onclick="javascript:remove_availability('.$arr['service_professional_id'].', '.$availabilityDtl['professional_availability_id'].' ,'.$availabilityDtl['professional_availability_detail'].');"><img src="images/icon-inactive.png"></a>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            <?php
                                        }
                                    } 
                                    ?>
                                </div>
                            </div>
                            <input type="hidden" name="extras_0" id="extras_0" value="0" />
                        </div>
                        <!-- Add multiple row code start here -->
                            <div id="div_1_0">
                            </div>
                        <!-- Add multiple row code start here -->
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    Location
                                </div>
                                <div class="form-group col-md-6">
                                    <?php
                                        if (!empty($profLocationList)) {
                                            echo '<select name="location" id="location">';
                                            foreach ($profLocationList AS $valLocation) {
                                                echo '<option value="' . $valLocation['Professional_location_id'] . '">' . $valLocation['Name'] . '</option>';
                                            }
                                            echo '</select>';
                                        }
                                    ?>
                                </div>
                                <div class="form-group col-md-4">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return edit_professional_availability_submit();" />
                    </div>  
                </form>
            </div>  
        <?php
    } else if($_REQUEST['action'] == 'AddMoreAvailabilityRow') {   
        $profID = $_REQUEST['profID'];
            $number = $_REQUEST['number'];
            $i = $_REQUEST['curr_div'];  
            $j = $i+1;
            echo '<div class="datepairExample_0" style="padding-top:55px !important;">
                    <div class="form-row">
                        <div class="form-group col-md-2">
                        </div>
                        <div class="form-group col-md-4">
                            <input name="starttime_' . $i . '_' . $number . '" id="starttime_0_0" type="text" class="form-control time start validate_time ui-timepicker-input" placeholder="From Time" autocomplete="off" style="margin-bottom:15px;">
                        </div>
                        <div class="form-group col-md-4">
                            <input name="endtime_' . $i . '_' . $number . '" id="endtime_0_0" type="text" class="form-control time end validate_time ui-timepicker-input" placeholder="To Time" autocomplete="off" style="margin-bottom:15px;">
                        </div>
                    </div>
                <div>';
            echo '<div id="div_'.$j.'_'.$number.'"></div>';
    } else if ($_REQUEST['action'] == 'remove_availability') {
        $serviceProfessionalId = $_REQUEST['profID'];
        $avaibilityId = $_REQUEST['professional_avaibility_id'];
        $recordId = $_REQUEST['professional_availability_detail'];

        if (!empty($serviceProfessionalId) && !empty($avaibilityId)) {
            $result = $professionalsClass->removeProfAvailabilityPreferences($serviceProfessionalId, $avaibilityId, $recordId);
            if (!empty($result)) {
                echo "Success";
                exit;
            } else {
                echo "Error";
                exit;
            }
        } else {
            echo "MissingParam";
            exit;
        }
    } else if ($_REQUEST['action'] == 'add_professional_availability') {
        $success=0;
        $errors=array(); 
        $i=0;

        if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
        {
            $serviceProfessionalId = strip_tags($_POST['service_professional_id']);
            $checkAll     = strip_tags($_POST['checkAll']);
            $selectedDays = strip_tags($_POST['selectedDays']);
            $startTime    = strip_tags($_POST['starttime_0_0']);
            $endTime      = strip_tags($_POST['endtime_0_0']);
            $extras       = strip_tags($_POST['extras_0']);
            $location     = strip_tags($_POST['location']);

            //check is it check all option selected
            if (empty($checkAll) && empty($selectedDays))
            {
                $success = 0;
                $errors[$i++] = "Please select atleast one day";
            }

            // check start time
            if ($startTime == '')
            {
                $success = 0;
                $errors[$i++] = "Please select start time";
            }

            // check end time
            if ($endTime == '')
            {
                $success = 0;
                $errors[$i++] = "Please select end time";
            }

            // check location
            if ($location == '')
            {
                $success = 0;
                $errors[$i++] = "Please select location";
            }

            if (count($errors))
            {
                echo 'validationError';
                exit;
            } else {
                $success = 1;
                // first insert data in sp_professional_avaibility table
                $arr['professional_service_id'] = $serviceProfessionalId;

                $timeArr = array();
                if (!empty($extras)) {
                    for ($i = 0; $i <= $extras; $i++) {
                        $timeArr[] =  $_POST['starttime_' . $i . '_0'] . "-" . $_POST['endtime_' . $i . '_0'];
                    }
                } else {
                    $timeArr[] =  $_POST['starttime_0_0'] . "-" . $_POST['endtime_0_0'];
                }

                $arr['professional_location_id'] = $location;
                $arr['timeVal'] = $timeArr;

                if ($selectedDays) {
                    $daysArr = explode(',', $selectedDays);
                    $totalRecordCount = count($daysArr);
                    $successRecordCount = 0;
                    $failureRecordCount = 0;

                    $daysValArr = array(
                        1 => 'Sunday',
                        2 => 'Monday',
                        3 => 'Tuesday',
                        4 => 'Wednesday',
                        5 => 'Thursday',
                        6 => 'Friday',
                        7 => 'Saturday'
                    );

                    foreach ($daysArr AS $days) {
                        $arr['day'] = $days;
                        $result = $professionalsClass->addProfessionalAvaibility($arr);
                        if (!empty($result)) {
                            $successRecordCount++;
                        } else {
                            $failureRecordCount++;
                            // Display error message
                            echo "Error";
                            echo "@#@";
                            echo "This schedule conflicts with another schedule on " . $daysValArr[$arr['day']];
                            exit;
                        }
                    }

                    /*echo '<pre>totalRecordCount <br> ';
                    print_r($totalRecordCount);
                    echo '<br>successRecordCount<br>';
                    print_r($successRecordCount);
                    echo '<br>failureRecordCount<br>';
                    print_r($failureRecordCount);
                    echo '</pre>';*/

                    if ($totalRecordCount == $successRecordCount) {
                        echo 'InsertSuccess'; // Insert Record
                        exit;
                    } else {
                        echo 'ErrorInInsert'; // Error
                        exit;
                    }
                } 
            }
        }
    } else if ($_REQUEST['action'] == 'edit_professional_availability') {
        $serviceProfessionalId = strip_tags($_POST['service_professional_id']);
        $startTime    = strip_tags($_POST['starttime_0_0']);
        $endTime      = strip_tags($_POST['endtime_0_0']);
        $extras       = strip_tags($_POST['extras_0']);
        $location     = strip_tags($_POST['location']);
        $professionalAvailabilityId = strip_tags($_POST['professional_availability_id_0']);
        $professionalLocId = strip_tags($_POST['professional_location_id_0']);
        $recordId = strip_tags($_POST['professional_availability_detail_0']);
        

        // check start time
        if ($startTime == '')
        {
            $success = 0;
            $errors[$i++] = "Please select start time";
        }

        // check end time
        if ($endTime == '')
        {
            $success = 0;
            $errors[$i++] = "Please select end time";
        }

        // check location
        if ($location == '')
        {
            $success = 0;
            $errors[$i++] = "Please select location";
        }

        if (count($errors))
        {
            echo 'validationError';
            exit;
        } else {
            $success = 1;
            $arr['professional_service_id'] = $serviceProfessionalId;
            $arr['professional_availability_id'] = $professionalAvailabilityId;
            $arr['actual_professional_location_id'] = $professionalLocId;
            $arr['professional_availability_detail'] = $recordId;

            $timeArr = array();
            if (!empty($extras)) {
                for ($i = 0; $i <= $extras; $i++) {
                    $timeArr[] =  $_POST['starttime_' . $i . '_0'] . "-" . $_POST['endtime_' . $i . '_0'];
                }
            } else {
                $timeArr[] = $_POST['starttime_0_0'] . "-" . $_POST['endtime_0_0'];
            }

            $arr['professional_location_id'] = $location;
            $arr['timeVal'] = $timeArr;

            $result = $professionalsClass->addProfessionalAvaibilityDetail($arr);
            if (!empty($result)) {
                echo 'InsertSuccess'; // Insert Record
                exit;
            } else {
                echo 'ErrorInInsert'; // Error
                exit;
            }
        }
    } else if ($_REQUEST['action'] == 'addLocPref') {
        $serviceProfessionalId = strip_tags($_POST['profID']);
        $locCoordsArr          = strip_tags($_POST['locCoordsArr']);
        $preferenceName        = strip_tags($_POST['preferenceName']);
        // check start time
        if (empty($locCoordsArr)) {
            $success = 0;
            $errors[$i++] = "Please select location";
        }

        if (empty($preferenceName)) {
            $success = 0;
            $errors[$i++] = "Please enter preference name";
        }

        if (count($errors)) {
            echo 'validationError';
            exit;
        } else {
            $success = 1;
            $arr['professional_service_id'] = $serviceProfessionalId;
            $arr['Name'] = $preferenceName;
            $arr['coordsArr'] = '';
            if (!empty($locCoordsArr)) {
                $arr['coordsArr'] = array_chunk(explode(',', $locCoordsArr), 2);
            }
            $result = $professionalsClass->addProfLoctionPreference($arr);
            if (!empty($result)) {
                echo 'InsertSuccess'; // Insert Record
                exit;
            } else {
                echo 'ErrorInInsert'; // Error
                exit;
            }
        }
    } else if ($_REQUEST['action'] == "vw_physiotherapy_unit_calculation") {
        $arr = array();
        $arr['service_professional_id'] = $_REQUEST['service_professional_id'];
        $arr['searchfromDate'] = $_REQUEST['search_start_date'];
        $arr['searchToDate'] = $_REQUEST['search_end_date'];

        $unitDtls = $professionalsClass->getPhysiotherapyUnitDtls($arr);

        ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">Details Of Physiotherapy Unit</h4>
            </div>

            <div class="modal-body">
                <div class="row" >
                    <table class="table table-hover table-bordered" style="width:90%;margin-left:30px;">
                        <tr> 
                            <th>Voucher Date</th>
                            <th>Party Name</th>
                            <th>Stock Item</th>
                            <th>QTY</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>UOM</th>
                        </tr>

                        <?php if (!empty($unitDtls)) {
                            $totalSperoAmount = 0;
                            $totalProfAmount = 0;
                            $conveyanceAmount = 0;
                            $professionalRate = $unitDtls[0]['Physio_Rate'];
                            foreach ($unitDtls AS $key => $valUnit) {
                                $totalAmount = $valUnit['cost'] * $valUnit['totalUnits'];
                                echo "<tr>
                                    <td>" . date('d M Y',strtotime($valUnit['event_date'])) . "</td>
                                    <td>" . $valUnit['patient_name'] . "</td>
                                    <td>" . $valUnit['recommomded_service'] . "</td>
                                    <td>" . $valUnit['totalUnits'] . "</td>
                                    <td>" . $valUnit['cost'] . "</td>
                                    <td>" . $totalAmount . "</td>
                                    <td>" . date('d M Y',strtotime($valUnit['service_date'])) . "</td>
                                    <td>" . date('d M Y',strtotime($valUnit['service_date_to'])) . "</td>
                                    <td>" . $valUnit['UOM'] . "</td>
                                </tr>";
                                $totalSperoAmount += $totalAmount;
                                unset($totalAmount);
                            }
                        } else {
                            echo "<tr><td colspan='9'>No Record fouund</td></tr>";
                        }
                        ?>
                        
                    </table>
                </div>
                <div class="row">
                    <table class="table table-hover table-bordered" style="width:90%;margin-left:30px;">
                            <tr>
                                <th>Total (Spero Cost)</th>
                                <th>Total (Prof. Cost)</th>
                                <th>TDS 10 %</th>
                                <th>Gross Total</th>
                                <th>Conveyance</th>
                                <th>Net Amount</th>
                            </tr>

                            <tr>
                                <td><?php echo $totalSperoAmount; ?></td>
                                <td><?php echo ($totalSperoAmount * $professionalRate); ?></td>
                                <td><?php echo (($totalSperoAmount * $professionalRate) * 0.1); ?></td>
                                <td><?php echo (($totalSperoAmount * $professionalRate) - (($totalSperoAmount * $professionalRate) * 0.1)); ?></td>
                                <td><?php echo $conveyanceAmount; ?></td>
                                <td><?php echo  ((($totalSperoAmount * $professionalRate) - (($totalSperoAmount * $professionalRate) * 0.1)) + $conveyanceAmount); ?></td>
                            </tr>
                    </table>
                </div>
            </div>
        <?php
    } else if ($_REQUEST['action'] == "vw_add_professional_document") {
        // Get professional document details
        $arg = array();
        $arg['service_professional_id'] = $_REQUEST['service_professional_id'];

        $profServiceDtls = $professionalsClass->GetProfessionalServices($arg);

        // Get all document list by service id
        if (!empty($profServiceDtls)) {
            $documentList = $professionalsClass->getDocumentsListByServiceId($profServiceDtls['service_id']);
            // Get all documents by professional id
            $profDocumentList = $professionalsClass->getProfDocumentsList($arg['service_professional_id']);

            $profDocumentListIds = array();
            $profDocumentListNew = ""; 
            if (!empty($profDocumentList)) {
                foreach ($profDocumentList AS $key => $valProfDocument) {
                    $profDocumentListIds[] = $valProfDocument['document_list_id'];
                }
                // combine both array for unique keys
                $profDocumentListNew = array_combine($profDocumentListIds, $profDocumentList);
            }
                        
            $resultantArr = array();
                
            foreach($documentList AS $key => $valDocument) {
                $docListId = $valDocument['document_list_id'];
                if ($valDocument['document_list_id'] == $profDocumentListNew[$docListId]['document_list_id']) {
                    $valDocument['Documents_id']     = $profDocumentListNew[$docListId]['Documents_id'];
                    $valDocument['url_path']         = $profDocumentListNew[$docListId]['url_path'];
                    $valDocument['rejection_reason'] = $profDocumentListNew[$docListId]['rejection_reason'];
                    $valDocument['status']           = $profDocumentListNew[$docListId]['status'];  
                } else {
                    $valDocument['Documents_id']     = "";
                    $valDocument['url_path']         = "";
                    $valDocument['rejection_reason'] = "";
                    $valDocument['status']           = ""; 
                }
                $resultantArr[] = $valDocument;
            }

            $profDocumentList = array();
            if (!empty($resultantArr)) {
                $profDocumentList = $resultantArr;
            }
        }
        ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?php if(!empty($ProfDtls)) { echo "Edit"; } else { echo "Add"; } ?> Professional Document </h4>
            </div>

            <div class="modal-body">
                <form class="form-inline" id="frm_add_professional_document" method="post" enctype="multipart/form-data" action ="professional_ajax_process.php?action=add_professional_document" autocomplete="off">
                    <div class="scrollbars">
                        <input type="hidden" name="service_professional_id" id="service_professional_id" value="<?php echo $arg['service_professional_id']; ?>" />
                        <?php if (!empty($profDocumentList)) {
                            $i = 1;
                            foreach ($profDocumentList AS $key => $valProfDocument) { ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-6">
                                        <?php echo $i . ". " . $valProfDocument['Documents_name'];
                                            if ($valProfDocument['isManadatory'] == '2') {
                                                echo '&nbsp;<span class="required">*</span>';
                                            }
        
                                            if (!empty($valProfDocument['url_path'])) {
                                                $imageUrl = $ProfDocument.$valProfDocument['url_path'];
                                                
                                                //Get File extension
                                                $fileData = explode('.', $valProfDocument['url_path']);
                                                $fileExtension = '';
                                                if (!empty($fileData)) {
                                                    $fileExtension = ($fileData[1] ? $fileData[1] : '');
                                                }
                
                                                //JPEG, JPG, PNG
                                                // PDF
                                                $thumbnailImage = '';
                                                if (!empty($fileExtension)) {
                                                    if (strtolower($fileExtension) == 'pdf') {
                                                        $thumbnailImage = "images/pdficon.png";
                                                    } else if (strtolower($fileExtension) == 'jpeg'
                                                        || strtolower($fileExtension) == 'jpg'
                                                        || strtolower($fileExtension) == 'png') {
                                                        $thumbnailImage = $imageUrl;
                                                    }
                                                }
                                                echo "<br/>
                                                    <div style='margin-bottom:10px !important;'>
                                                        <span>
                                                            <a href='$imageUrl' target='_blank'>
                                                            <img src='$thumbnailImage' height='100px' width='100px' /></a>
                                                        </span>
                                                    </div>
                                                <div id='docImage' style='display:none'></div>";
                                            }
                                            ?>
                                        </div>

                                        <div class="col-lg-4">
                                            <?php if (!empty($valProfDocument['status'])) { ?>
                                                <?php if ($valProfDocument['status'] != '4' && $valProfDocument['status'] != '3' ) { ?>
                                                    <select class="chosen-select form-control" name="doc_Status_<?php echo $valProfDocument['document_list_id']; ?>" id="doc_Status_<?php echo $valProfDocument['document_list_id']; ?>" onChange="javascript: return changeDocumentStatus('<?php echo $serviceProfessionalId; ?>','<?php echo $valProfDocument['document_list_id']; ?>');" <?php if ($valProfDocument['status'] == '1' || empty($valProfDocument['url_path'])) { echo "disabled"; } ?>>
                                                        <option value=""<?php if ($valProfDocument['status'] == '') { echo 'selected="selected"'; } ?>>Choose action</option>
                                                        <option value="1"<?php if ($valProfDocument['status'] == '1') { echo 'selected="selected"'; } ?>>Verified</option>
                                                        <option value="3"<?php if ($valProfDocument['status'] == '3') { echo 'selected="selected"'; } ?>>Reject</option>
                                                    </select>
                                                <?php } else { ?>
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="userFile_<?php echo $valProfDocument['document_list_id']; ?>" id="userFile_<?php echo $valProfDocument['document_list_id']; ?>" />
                                                    </div>
                                                    <?php if ($valProfDocument['status'] == '3') { ?>
                                                        <span class = "required"> Document rejected. Please upload again</span>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="userFile_<?php echo $valProfDocument['document_list_id']; ?>" id="userFile_<?php echo $valProfDocument['document_list_id']; ?>" />
                                                </div>
                                            <?php } ?>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="col-lg-2 marginB50">
                                        </div>
                                    </div>
                                </div>
                            <?php
                            $i++; 
                            } 
                        } ?>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_professional_document_submit();" />
                    </div>
                </form>
            </div>
        <?php
    } else if ($_REQUEST['action'] == 'add_professional_document') {
        $success = 0;
        $errors  = array(); 
        $i       = 0;

        if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
        {

            foreach ($documentList as $key => $document) {
                echo '<pre>';
                print_r($document);
                echo '</pre>';
                exit;
                if ($_FILES['userFile_' .  $document[$key]]['size'] == 0) {
                    unset($_FILES['userFile_' .  $document[$key]]);
                }
            }
            $serviceProfessionalId = strip_tags($_POST['service_professional_id']);
            // Get professional service details
            $arg['service_professional_id'] = $serviceProfessionalId;
            $profServiceDtls = $professionalsClass->GetProfessionalServices($arg);
            unset($arg);
            if (!empty($profServiceDtls)) {
                // Get all document list by service id
                $documentList = $professionalsClass->getDocumentsListByServiceId($profServiceDtls['service_id']);

                $newDocumentList = array();

                foreach ($documentList as $key => $document) {
                    if ($_FILES['userFile_' .  $document['document_list_id']]['size'] == 0) {
                        unset($_FILES['userFile_' .  $document['document_list_id']]);
                    }
                }
                if (!empty($documentList)) {
                    $validExtensions = array(
                        'jpeg',
                        'jpg',
                        'png',
                        'pdf'
                    ); // valid extensions

                    $path = '../assets/profDocuments/'; // upload directory

                    $recordCnt  = 0;
                    $successCnt = 0;
                    $failureCnt = 0;
                    $invalidFileExtension = 0; 

                    $arr['service_professional_id'] = $serviceProfessionalId;

                    for ($i = 0; $i < count($documentList); $i++) {
                        ob_start();
                        $arr['document_list_id'] = $documentList[$i]['document_list_id'];
                        $arr['upload_file_name'] = $_FILES['userFile_' .  $documentList[$i]['document_list_id']]['name'];

                        if (!empty($arr['upload_file_name'])) {
                            $recordCnt += 1;
                            $ext = strtolower(pathinfo($arr['upload_file_name'], PATHINFO_EXTENSION));

                            

                            if (in_array($ext, $validExtensions)) {
                                $arr['url_path'] = "ProfDocument_" . $serviceProfessionalId . "_Img_". $recordCnt . "_" . date('Y_m_d_H_i_s') . "." . $ext;

                                $path = $path . $arr['url_path'];

                                try {
                                    if (move_uploaded_file($_FILES['userFile_' . $documentList[$i]['document_list_id']]['tmp_name'], $path)) {
                                        $recordId = $professionalsClass->addProfessionalDocument($arr);
                                        unset($arr['url_path']);
                                        if (!empty($recordId)) {
                                            $successCnt += 1;
                                        } else {
                                            $failureCnt += 1;
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo '<pre>';
                                    print_r($e);
                                    echo '</pre>';
                                    exit;
                                }
                            } else {
                                $invalidFileExtension = 1;
                                break;
                            }
                        }
                        unset($_FILES['userFile_' . $documentList[$i]['document_list_id']]['tmp_name'], $arr['document_list_id'], $arr['upload_file_name']);
                        ob_flush();
                    }

                    if ($invalidFileExtension) {
                        echo "invalidFileExtension";
                        exit;
                    } else if ($successCnt || $failureCnt) {
                        echo "success";
                        echo "@#@";
                        echo $recordCnt . "_" . $successCnt . "_" . $failureCnt; 
                        exit;
                    } else{
                        echo "Error";
                        exit;
                    }
                } else {
                    echo "docListNotFound";
                    exit;
                }
            } else {
                echo "profServiceDtlsNotFound";
                exit;
            }
        } else {
            echo 'NoPostData'; // No posted data
            exit;
        }
    }
?>
