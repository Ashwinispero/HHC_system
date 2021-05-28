<?php
require_once 'inc_classes.php';
require_once '../classes/consultantsClass.php';
$consultantsClass=new consultantsClass();
if($_REQUEST['action']=='vw_pending_consultant')
{
     // Getting Consultant Details
     $arr['doctors_consultants_id']=$_REQUEST['doctors_consultants_id'];
     $ConsultantDtls=$consultantsClass->GetConsultantById($arr);
  ?>
     <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
         <h4 class="modal-title">Approval For Consultant </h4>
     </div>
     <div class="modal-body">
         <form class="form-inline" name="frm_approve_consultant" id="frm_approve_consultant" method="post" action ="consultant_ajax_process.php?action=approve_consultant" autocomplete="off">
             <div class="scrollbars">
                 <div class="editform">
                     <label>Select Type <span class="required">*</span></label>
                         <div class="value dropdown">
                             <label>
                                 <select name="type" id="type" class="validate[required]">
                                     <option value=""<?php if($_POST['type']=='') { echo 'selected="selected"'; } else if($ConsultantDtls['type']=='') { echo 'selected="selected"'; } ?>>Type</option>
                                     <option value="1"<?php if($_POST['type']=='1') { echo 'selected="selected"'; } else if($ConsultantDtls['type']=='1') { echo 'selected="selected"'; }?>>Doctor</option>
                                     <option value="2"<?php if($_POST['type']=='2') { echo 'selected="selected"'; } else if($ConsultantDtls['type']=='2') { echo 'selected="selected"'; }?>>Consultant</option>
                                 </select>
                             </label>
                         </div>
                 </div>
                 <div class="editform">
                     <label>Hospital Name<span class="required">*</span></label>
                     <div class="value">
                         <input type="text" disabled value="<?php if(!empty($_POST['hos_nm'])) { echo $_POST['hos_nm']; } else if(!empty($ConsultantDtls['hos_nm'])) { echo $ConsultantDtls['hos_nm']; } else { echo ""; } ?>" class="form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                     </div>
                 </div>
                 <div class="editform">
                     <label>Select Hospital <span class="required">*</span></label>
                         <div class="value dropdown">
                             <label>
                                 <select name="hospital_id" id="hospital_id" class="validate[required]">
                                    
                                    <option value="" >Hospiatls</option> 
                                             
                                             <?php
                                                         $Query=mysql_query("select * from sp_hospitals ORDER BY hospital_name ASC");
                                                         while($row=mysql_fetch_array($Query))
                                                         {
                                             ?>
                                                 <option value="<?php echo $row['hospital_id'] ;?>" ><?php echo $row['hospital_name'];?> <?php if($_POST['hospital_id']=='1') { echo 'selected="selected"'; } else if($ConsultantDtls['hospital_id']=='1') { echo 'selected="selected"'; }?></option>
         
                                             <?php
                                                         }
                                                         
                                             ?>
                                         </select>
                                 
                             </label>
                         </div>
                 </div>
                 <div class="editform">
                     <label>Last Name <span class="required">*</span></label>
                     <div class="value">
                         <input type="hidden" name="doctors_consultants_id" id="doctors_consultants_id" value="<?php echo $arr['doctors_consultants_id']; ?>" />
                         <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])) { echo $_POST['name']; } else if(!empty($ConsultantDtls['name'])) { echo $ConsultantDtls['name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                     </div>
                 </div>
                 <div class="editform">
                     <label>First Name <span class="required">*</span></label>
                     <div class="value">
                         <input type="text" name="first_name" id="first_name" value="<?php if(!empty($_POST['first_name'])) { echo $_POST['first_name']; } else if(!empty($ConsultantDtls['first_name'])) { echo $ConsultantDtls['first_name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                     </div>
                 </div>
                 <div class="editform">
                     <label>Middle Name</label>
                     <div class="value">
                         <input type="text" name="middle_name" id="middle_name" value="<?php if(!empty($_POST['middle_name'])) { echo $_POST['middle_name']; } else if(!empty($ConsultantDtls['middle_name'])) { echo $ConsultantDtls['middle_name']; } else { echo ""; } ?>" class="validate[maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                     </div>
                 </div>
                 <div class="editform">
                     <label>Email Address</label>
                     <div class="value">
                         <input type="text" name="email_id" id="email_id" value="<?php if(!empty($_POST['email_id'])) { echo $_POST['email_id']; } else if(!empty($ConsultantDtls['email_id'])) { echo $ConsultantDtls['email_id']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" />
                     </div>
                 </div>
                 <div class="editform">
                     <label>Phone</label>
                     <div class="value">
                         <input type="text" name="phone_no" id="phone_no" value="<?php if(!empty($_POST['phone_no'])) { echo $_POST['phone_no']; } else if(!empty($ConsultantDtls['phone_no'])) { echo $ConsultantDtls['phone_no']; }  else { echo ""; } ?>" class="validate[minSize[11],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                     </div>
                 </div>
                 <div class="editform">
                     <label>Mobile <span class="required">*</span></label>
                     <div class="value">
                         <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($ConsultantDtls['mobile_no'])) { echo $ConsultantDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                     </div>
                 </div>
                 <div class="editform">
                     <label>Work Phone</label>
                     <div class="value">
                         <input type="text" name="work_phone_no" id="work_phone_no" value="<?php if(!empty($_POST['work_phone_no'])) { echo $_POST['work_phone_no']; } else if(!empty($ConsultantDtls['work_phone_no'])) { echo $ConsultantDtls['work_phone_no']; }  else { echo ""; } ?>" class="validate[minSize[11],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                     </div>
                 </div>
                 <div class="editform">
                     <label>Work Email Address</label>
                     <div class="value">
                         <input type="text" name="work_email_id" id="work_email_id" value="<?php if(!empty($_POST['work_email_id'])) { echo $_POST['work_email_id']; } else if(!empty($ConsultantDtls['work_email_id'])) { echo $ConsultantDtls['work_email_id']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" onblur="javascript:return chkEmails();" />
                         <div id="form_error" class="formErrorSelf"></div>
                     </div>
                 </div>
                 <div class="editform">
                     <label>Work Address</label>
                     <div class="value">
                         <textarea name="work_address" id="work_address" class="form-control" maxlength="160" style="width: 275px; height: 100px;"><?php if(!empty($_POST['work_address'])) { echo $_POST['work_address']; } else if(!empty($ConsultantDtls['work_address'])) { echo $ConsultantDtls['work_address']; }  else { echo ""; } ?></textarea>
                     </div>
                 </div>
                 <div class="editform">
                     <label>Speciality</label>
                     <div class="value">
                         <input type="text" name="speciality" id="speciality" value="<?php if(!empty($_POST['speciality'])) { echo $_POST['speciality']; } else if(!empty($ConsultantDtls['speciality'])) { echo $ConsultantDtls['speciality']; } else { echo ""; } ?>" class="form-control" maxlength="70" style="width:100% !important;" />
                         <div id="form_error" class="formErrorSelf"></div>
                     </div>
                 </div>
                  <div class="editform">
                     <label>Consultant Cost</label>
                     <div class="value">
                         
                         <input type="text" name="telephonic_consultation_fees" id="telephonic_consultation_fees" value="<?php if(!empty($_POST['telephonic_consultation_fees'])) { echo $_POST['telephonic_consultation_fees']; } else if(!empty($ConsultantDtls['telephonic_consultation_fees'])) { echo $ConsultantDtls['telephonic_consultation_fees']; } else { echo ""; } ?>" class="form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                     </div>
                 </div>
                 
              </div>
                 <div class="modal-footer">
                     <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Approve" onclick="return approve_consultant_submit();" />
                 </div>  
         </form>
     </div>
  <?php  
}
else if($_REQUEST['action']=='vw_add_consultant')
{
    // Getting Consultant Details
    $arr['doctors_consultants_id']=$_REQUEST['doctors_consultants_id'];
    $ConsultantDtls=$consultantsClass->GetConsultantById($arr);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($ConsultantDtls)) { echo "Edit"; } else { echo "Add"; } ?> Consultant </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_consultant" id="frm_add_consultant" method="post" action ="consultant_ajax_process.php?action=add_consultant" autocomplete="off">
            <div class="scrollbars">
                <div class="editform">
                    <label>Select Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="type" id="type" class="validate[required]">
                                    <option value=""<?php if($_POST['type']=='') { echo 'selected="selected"'; } else if($ConsultantDtls['type']=='') { echo 'selected="selected"'; } ?>>Type</option>
                                    <option value="1"<?php if($_POST['type']=='1') { echo 'selected="selected"'; } else if($ConsultantDtls['type']=='1') { echo 'selected="selected"'; }?>>Doctor</option>
                                    <option value="2"<?php if($_POST['type']=='2') { echo 'selected="selected"'; } else if($ConsultantDtls['type']=='2') { echo 'selected="selected"'; }?>>Consultant</option>
                                </select>
                            </label>
                        </div>
                </div>
				
				<div class="editform">
                    <label>Select Hospital <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="hospital_id" id="hospital_id" class="validate[required]">
                                   
								   <option value="" >Hospiatls</option> 
											
											<?php
														$Query=mysql_query("select * from sp_hospitals ORDER BY hospital_name ASC");
														while($row=mysql_fetch_array($Query))
														{
											?>
												<option value="<?php echo $row['hospital_id'] ;?>" ><?php echo $row['hospital_name'];?> <?php if($_POST['hospital_id']=='1') { echo 'selected="selected"'; } else if($ConsultantDtls['hospital_id']=='1') { echo 'selected="selected"'; }?></option>
		
											<?php
														}
														
											?>
										</select>
								
                            </label>
                        </div>
                </div>
                <div class="editform">
                    <label>Last Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="doctors_consultants_id" id="doctors_consultants_id" value="<?php echo $arr['doctors_consultants_id']; ?>" />
                        <input type="text" name="name" id="name" value="<?php if(!empty($_POST['name'])) { echo $_POST['name']; } else if(!empty($ConsultantDtls['name'])) { echo $ConsultantDtls['name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>First Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="first_name" id="first_name" value="<?php if(!empty($_POST['first_name'])) { echo $_POST['first_name']; } else if(!empty($ConsultantDtls['first_name'])) { echo $ConsultantDtls['first_name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Middle Name</label>
                    <div class="value">
                        <input type="text" name="middle_name" id="middle_name" value="<?php if(!empty($_POST['middle_name'])) { echo $_POST['middle_name']; } else if(!empty($ConsultantDtls['middle_name'])) { echo $ConsultantDtls['middle_name']; } else { echo ""; } ?>" class="validate[maxSize[50]] form-control" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')"  maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Email Address</label>
                    <div class="value">
                        <input type="text" name="email_id" id="email_id" value="<?php if(!empty($_POST['email_id'])) { echo $_POST['email_id']; } else if(!empty($ConsultantDtls['email_id'])) { echo $ConsultantDtls['email_id']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Phone</label>
                    <div class="value">
                        <input type="text" name="phone_no" id="phone_no" value="<?php if(!empty($_POST['phone_no'])) { echo $_POST['phone_no']; } else if(!empty($ConsultantDtls['phone_no'])) { echo $ConsultantDtls['phone_no']; }  else { echo ""; } ?>" class="validate[minSize[11],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Mobile <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="mobile_no" id="mobile_no" value="<?php if(!empty($_POST['mobile_no'])) { echo $_POST['mobile_no']; } else if(!empty($ConsultantDtls['mobile_no'])) { echo $ConsultantDtls['mobile_no']; }  else { echo ""; } ?>" class="validate[required,minSize[10],maxSize[15],custom[mobile]] form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Work Phone</label>
                    <div class="value">
                        <input type="text" name="work_phone_no" id="work_phone_no" value="<?php if(!empty($_POST['work_phone_no'])) { echo $_POST['work_phone_no']; } else if(!empty($ConsultantDtls['work_phone_no'])) { echo $ConsultantDtls['work_phone_no']; }  else { echo ""; } ?>" class="validate[minSize[11],maxSize[15],custom[phone]] form-control" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Work Email Address</label>
                    <div class="value">
                        <input type="text" name="work_email_id" id="work_email_id" value="<?php if(!empty($_POST['work_email_id'])) { echo $_POST['work_email_id']; } else if(!empty($ConsultantDtls['work_email_id'])) { echo $ConsultantDtls['work_email_id']; } else { echo ""; } ?>" class="validate[custom[email]] form-control" maxlength="70" style="width:100% !important;" onblur="javascript:return chkEmails();" />
                        <div id="form_error" class="formErrorSelf"></div>
                    </div>
                </div>
                <div class="editform">
                    <label>Work Address</label>
                    <div class="value">
                        <textarea name="work_address" id="work_address" class="form-control" maxlength="160" style="width: 275px; height: 100px;"><?php if(!empty($_POST['work_address'])) { echo $_POST['work_address']; } else if(!empty($ConsultantDtls['work_address'])) { echo $ConsultantDtls['work_address']; }  else { echo ""; } ?></textarea>
                    </div>
                </div>
                <div class="editform">
                    <label>Speciality</label>
                    <div class="value">
                        <input type="text" name="speciality" id="speciality" value="<?php if(!empty($_POST['speciality'])) { echo $_POST['speciality']; } else if(!empty($ConsultantDtls['speciality'])) { echo $ConsultantDtls['speciality']; } else { echo ""; } ?>" class="form-control" maxlength="70" style="width:100% !important;" />
                        <div id="form_error" class="formErrorSelf"></div>
                    </div>
                </div>
                 <div class="editform">
                    <label>Consultant Cost</label>
                    <div class="value">
                        
                        <input type="text" name="telephonic_consultation_fees" id="telephonic_consultation_fees" value="<?php if(!empty($_POST['telephonic_consultation_fees'])) { echo $_POST['telephonic_consultation_fees']; } else if(!empty($ConsultantDtls['telephonic_consultation_fees'])) { echo $ConsultantDtls['telephonic_consultation_fees']; } else { echo ""; } ?>" class="form-control" onkeyup="if (/[^0-9+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()+.]/g,'')" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                
             </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_consultant_submit();" />
                </div>  
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='approve_consultant')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $doctors_consultants_id=strip_tags($_POST['doctors_consultants_id']);
		$hospital_id=strip_tags($_POST['hospital_id']);
		$type=strip_tags($_POST['type']);
        $name=strip_tags($_POST['name']);
        $first_name=strip_tags($_POST['first_name']);
        $middle_name=strip_tags($_POST['middle_name']);
        $email_id=strip_tags($_POST['email_id']);
        $phone_no=strip_tags($_POST['phone_no']);
        $mobile_no=strip_tags($_POST['mobile_no']);
        $work_phone_no=strip_tags($_POST['work_phone_no']);
        $work_email_id=strip_tags($_POST['work_email_id']);
        $work_address=$_POST['work_address'];
        $speciality=strip_tags($_POST['speciality']);
		$telephonic_consultation_fees=strip_tags($_POST['telephonic_consultation_fees']);
        
        if($type=='')
        {
            $success=0;
            $errors[$i++]="Please enter type";
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
        }*/
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error
           exit;
        }
        
        /**/
        
        // Check Record Exists 
        if($doctors_consultants_id)
            $chk_consultant_sql="SELECT doctors_consultants_id FROM sp_doctors_consultants WHERE mobile_no='".$mobile_no."' AND doctors_consultants_id !='".$doctors_consultants_id."'";
        else 
           $chk_consultant_sql="SELECT doctors_consultants_id FROM sp_doctors_consultants WHERE mobile_no='".$mobile_no."'"; 
        
        if(mysql_num_rows($db->query($chk_consultant_sql)))
        {
            $success=0;
            echo 'consultantexists';
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
            $arr['doctors_consultants_id']=$doctors_consultants_id;
            $arr['type']=$type;
            $arr['name']=ucwords(strtolower($name));
            $arr['first_name']=ucwords(strtolower($first_name));
            $arr['middle_name']=ucwords(strtolower($middle_name));
            $arr['email_id']=strtolower($email_id);
            $arr['phone_no']=$phone_no;
            $arr['mobile_no']=$mobile_no;
            $arr['work_phone_no']=$work_phone_no;
            $arr['work_email_id']=strtolower($work_email_id);
            $arr['work_address']=$work_address;
            $arr['speciality']=$speciality;
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
			$arr['hospital_id']=$hospital_id;
			$arr['telephonic_consultation_fees']=$telephonic_consultation_fees;
            $arr['status']='1';
            if(empty($doctors_consultants_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }     
            $InsertRecord=$consultantsClass->ApprovedConsultant($arr); 
            if(!empty($InsertRecord))
            {
                if($doctors_consultants_id)
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
               echo 'consultantexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='add_consultant')
{
    $success=0;
    $errors=array(); 
    $i=0;
    
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $doctors_consultants_id=strip_tags($_POST['doctors_consultants_id']);
		$hospital_id=strip_tags($_POST['hospital_id']);
		$type=strip_tags($_POST['type']);
        $name=strip_tags($_POST['name']);
        $first_name=strip_tags($_POST['first_name']);
        $middle_name=strip_tags($_POST['middle_name']);
        $email_id=strip_tags($_POST['email_id']);
        $phone_no=strip_tags($_POST['phone_no']);
        $mobile_no=strip_tags($_POST['mobile_no']);
        $work_phone_no=strip_tags($_POST['work_phone_no']);
        $work_email_id=strip_tags($_POST['work_email_id']);
        $work_address=$_POST['work_address'];
        $speciality=strip_tags($_POST['speciality']);
		$telephonic_consultation_fees=strip_tags($_POST['telephonic_consultation_fees']);
        
        if($type=='')
        {
            $success=0;
            $errors[$i++]="Please enter type";
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
        }*/
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error
           exit;
        }
        
        /**/
        
        // Check Record Exists 
        if($doctors_consultants_id)
            $chk_consultant_sql="SELECT doctors_consultants_id FROM sp_doctors_consultants WHERE mobile_no='".$mobile_no."' AND doctors_consultants_id !='".$doctors_consultants_id."'";
        else 
           $chk_consultant_sql="SELECT doctors_consultants_id FROM sp_doctors_consultants WHERE mobile_no='".$mobile_no."'"; 
        
        if(mysql_num_rows($db->query($chk_consultant_sql)))
        {
            $success=0;
            echo 'consultantexists';
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
            $arr['doctors_consultants_id']=$doctors_consultants_id;
            $arr['type']=$type;
            $arr['name']=ucwords(strtolower($name));
            $arr['first_name']=ucwords(strtolower($first_name));
            $arr['middle_name']=ucwords(strtolower($middle_name));
            $arr['email_id']=strtolower($email_id);
            $arr['phone_no']=$phone_no;
            $arr['mobile_no']=$mobile_no;
            $arr['work_phone_no']=$work_phone_no;
            $arr['work_email_id']=strtolower($work_email_id);
            $arr['work_address']=$work_address;
            $arr['speciality']=$speciality;
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
			$arr['hospital_id']=$hospital_id;
			$arr['telephonic_consultation_fees']=$telephonic_consultation_fees;
            if(empty($doctors_consultants_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }            
            $InsertRecord=$consultantsClass->AddConsultant($arr); 
            if(!empty($InsertRecord))
            {
                if($doctors_consultants_id)
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
               echo 'consultantexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['doctors_consultants_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['doctors_consultants_id'] =$_REQUEST['doctors_consultants_id'];
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

        $ChangeStatus =$consultantsClass->ChangeStatus($arr);
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
// import consultant excel file
else if($_REQUEST['action'] == 'ImportExcel')
{
    ?>
    <div>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Import Consultant/Doctor File</h4>
        </div>
        <div class="modal-body">
            <form class="form-inline" name="excelConsultantForm" id="excelConsultantForm" method="post"  enctype="multipart/form-data" action="consultant_ajax_process.php?action=SubmitExcelForm" autocomplete="off">
                <div class="editform" >
                    <label>Upload Consultant/Doctor File</label>
                    <div class="value" >
                        <input type="file" name="consultantFile" id="consultantExcel" class="brochurefile" />
                        <br><br>
                        <a href="include/consultantExcel.xls" target="_blank"><img src="images/icon-xls25.png" /> Sample File </a>
                    </div>
                </div>
                <div class="editform" >
                    <label style="color:#e7394d;font-size:15px;">Important Notes :-</label>
                    <div class="value" style="color:#e7394d;font-size:13px;">
                        Type,Last Name,First Name,Mobile these fields are compulsory.<br/>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Upload" onclick="return ExcelFile_submit();" />
                </div>
            </form>
        </div>
    </div>
        <?php
    
}
else if($_REQUEST['action']=='SubmitExcelForm')
    {
       $success=0;
       $errors=array(); 
       $i=0;
       if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
       {
            $ExcelFile_image="";
            if(count($errors)==0 && $_FILES['consultantFile']["name"])
            {                
                $file_str=preg_replace('/\s+/', '_', $_FILES['consultantFile']["name"]);
                $ExcelFile_image=time().basename($file_str);
                $newfile = "consultantImport/";

                $filename = $_FILES['consultantFile']['tmp_name']; // File being uploaded.
                $filetype = $_FILES['consultantFile']['type']; // type of file being uploaded
                $filesize = filesize($filename); // File size of the file being uploaded.
                $source1 = $_FILES['consultantFile']['tmp_name'];
                $target_path1 = $newfile.$ExcelFile_image;

                $filename_temp = basename($_FILES['consultantFile']['name']);
                 $ext = substr($filename_temp, strrpos($filename_temp, '.') + 1);

                     list($width1, $height1, $type1, $attr1) = getimagesize($source1);
                     if(strtolower($ext) == "xls"  || strtolower($ext) == "xlsx" ) //|| strtolower($ext) == "csv" )
                     {
                         if(move_uploaded_file($source1, $target_path1))
                         {
                             $thump_target_path="consultantImport/".$ExcelFile_image;
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
                $excel->read("consultantImport/".$ExcelFile_image);
                
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
                              $cellarr['type'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '2')
                              $cellarr['name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '3')
                              $cellarr['first_name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '4')
                              $cellarr['middle_name'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '5')
                              $cellarr['email_id'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '6')
                              $cellarr['phone_no'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '7')
                              $cellarr['mobile_no'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '8')
                              $cellarr['work_phone'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '9')
                              $cellarr['work_email_id'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '10')
                              $cellarr['work_address'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                          
                          else if($y == '11')
                              $cellarr['speciality'] = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                                                    
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
                   $type=str_replace(' ','',$excel->sheets[0]['cells'][1][1]);
                   $name=str_replace(' ','',$excel->sheets[0]['cells'][1][2]);
                   $first_name=str_replace(' ','',$excel->sheets[0]['cells'][1][3]);
                   $middle_name=str_replace(' ','',$excel->sheets[0]['cells'][1][4]);
                   $email_id=str_replace(' ','',$excel->sheets[0]['cells'][1][5]);
                   $phone_no=str_replace(' ','',$excel->sheets[0]['cells'][1][6]);
                   $mobile_no=str_replace(' ','',$excel->sheets[0]['cells'][1][7]);
                   $work_email_id=str_replace(' ','',$excel->sheets[0]['cells'][1][8]);
                   $work_phone_no=str_replace(' ','',$excel->sheets[0]['cells'][1][9]);
                   $work_address=str_replace(' ','',$excel->sheets[0]['cells'][1][10]);
                   $speciality=str_replace(' ','',$excel->sheets[0]['cells'][1][11]);

                if((strtolower($type) == 'type') && (strtolower($name) == 'lastname') && (strtolower($first_name) == 'firstname') && (strtolower($middle_name) == 'middlename') && (strtolower($email_id) == 'emailaddress') && (strtolower($phone_no) == 'phone') && (strtolower($mobile_no) == 'mobile') && (strtolower($work_address) == 'workaddress') && (strtolower($speciality) == 'speciality')  )
                {
                    $totalRowsCount = $excel->sheets[0]['numRows']; 
                    $excel_data = sheetData($excel->sheets[0]);
                    
                     for($j=0;$j<count($excel_data);$j++)
                     {
                         $type = $db->escape(trim($excel_data[$j]['type'])); 
                         $name = $db->escape(trim($excel_data[$j]['name'])); 
                         $first_name = $db->escape(trim($excel_data[$j]['first_name'])); 
                         $middle_name = $db->escape(trim($excel_data[$j]['middle_name'])); 
                         $email_id = $db->escape(trim($excel_data[$j]['email_id'])); 
                         $phone_no = $db->escape(trim($excel_data[$j]['phone_no']));
                         $mobile_no = $db->escape(trim($excel_data[$j]['mobile_no']));
                         $work_email_id = $db->escape(trim($excel_data[$j]['work_email_id']));
                         $work_phone_no = $excel_data[$j]['work_phone'];
                         $work_address = $db->escape(trim($excel_data[$j]['work_address']));
                         $speciality = $db->escape(trim($excel_data[$j]['speciality']));
                         
                         $professional_id = '';$ServiceIds = '';$typeVal='';
                            
                         if(!empty($type) &&  !empty($name) &&  !empty($first_name) &&  !empty($mobile_no)  )
                         {
                             if($type == 'consultant')
                                 $typeVal = '1';
                             else
                                 $typeVal = '2';
                            $importData['type'] = $typeVal;
                            $importData['name'] = $name;
                            $importData['first_name'] = $first_name;
                            $importData['middle_name'] = $middle_name;
                            $importData['email_id'] = $email_id;
                            $importData['phone_no'] = $phone_no;
                            $importData['mobile_no'] = $mobile_no;
                            $importData['work_email_id'] = $work_email_id;
                            $importData['work_phone_no'] = $work_phone_no;
                            $importData['work_address'] = $work_address;
                            $importData['speciality'] = $speciality;
                            
                            $importData['added_by']=strip_tags($_SESSION['admin_user_id']);
                            $importData['added_date']=date('Y-m-d H:i:s');
                            $importData['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
                            $importData['last_modified_date']=date('Y-m-d H:i:s');
                            
                            //check exit;
                            $selectConsultant = "select doctors_consultants_id from sp_doctors_consultants where mobile_no = '".$mobile_no."' and name = '".$name."' ";
                            if(mysql_num_rows($db->query($selectConsultant)))
                            {
                                $val_cons = $db->fetch_array($db->query($selectConsultant));
                              $where = "doctors_consultants_id ='".$val_cons['doctors_consultants_id']."'";
                              $RecordId=$db->query_update('sp_doctors_consultants',$importData,$where); 
                            }
                            else 
                            {
                              $importData['status']='1';
                              $RecordId=$db->query_insert('sp_doctors_consultants',$importData);
                            }                            
                         }
                         unset($importData);
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