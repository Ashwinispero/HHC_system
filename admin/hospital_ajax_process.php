<?php
require_once 'inc_classes.php';
require_once '../classes/hospitalClass.php';
$hospitalClass=new hospitalClass();
require_once '../classes/commonClass.php';
$commonClass=new commonClass();
// Get All Location
$LocationList=$commonClass->GetAllLocations();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='vw_add_hospital')
{
    // Getting System User Details
    $arr['hospital_id']=$_REQUEST['hospital_id'];
    $HospitalDtls=$hospitalClass->GetHospitalById($arr);
    // Get All IPS of this event
    $HospitalIPS=$hospitalClass->GetIPSByHospitalId($arr);  
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($HospitalDtls)) { echo "Edit"; } else { echo "Add"; } ?> Hospital </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_hospital" id="frm_add_hospital" method="post" action ="hospital_ajax_process.php?action=add_hospital" autocomplete="off">
            <div class="scrollbars">
                <div class="editform">
                    <label>Hospital Name <span class="required">*</span></label>
                    <div class="value">
                        <input type="hidden" name="hospital_id" id="hospital_id" value="<?php echo $arr['hospital_id']; ?>" />
                        <input type="text" name="hospital_name" id="hospital_name" value="<?php if(!empty($_POST['hospital_name'])) { echo $_POST['hospital_name']; } else if(!empty($HospitalDtls['hospital_name'])) { echo $HospitalDtls['hospital_name']; } else { echo ""; } ?>" class="validate[required,maxSize[70]] form-control" onkeyup="if (/[^A-Za-z0-9-+() ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9-+() ]/g,'')"  maxlength="70" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Hospital Short Code <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="hospital_short_code" id="hospital_short_code" value="<?php if(!empty($_POST['hospital_short_code'])) { echo $_POST['hospital_short_code']; } else if(!empty($HospitalDtls['hospital_short_code'])) { echo $HospitalDtls['hospital_short_code']; } else { echo ""; } ?>" class="validate[required,minSize[5],maxSize[5]] form-control" onkeyup="if (/[^A-Za-z]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z]/g,'')"  maxlength="5" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Phone Number <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="phone_no" id="phone_no" value="<?php if(!empty($_POST['phone_no'])) { echo $_POST['phone_no']; } else if(!empty($HospitalDtls['phone_no'])) { echo $HospitalDtls['phone_no']; } else { echo ""; } ?>" class="validate[required,minSize[11],maxSize[15],custom[phone]] form-control" onkeyup="if (/[^0-9()-.]/g.test(this.value)) this.value = this.value.replace(/[^0-9()-.]/g,'')" maxlength="15" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Website <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="website_url" id="website_url" value="<?php if(!empty($_POST['website_url'])) { echo $_POST['website_url']; } else if(!empty($HospitalDtls['phone_no'])) { echo $HospitalDtls['website_url']; } else { echo ""; } ?>" class="validate[required,custom[Website]] form-control" maxlength="50" style="width:100% !important;" />
                    </div>
                </div>
                <div class="editform">
                    <label>Location <span class="required">*</span></label>
                    <div class="value dropdown">
                        <label>
                            <select name="location_id" id="location_id" class="validate[required]">
                                <option value=""<?php if($_POST['location_id']=='') { echo 'selected="selected"'; } else if($HospitalDtls['location_id']=='') { echo 'selected="selected"'; } ?>>Location</option>
                                <?php
                                    foreach($LocationList as $key=>$valLocation)
                                    {
                                        if($HospitalDtls['location_id'] == $valLocation['location_id'])
                                            echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                        else if($_POST['location_id'] == $valLocation['location_id'])
                                            echo '<option value="'.$valLocation['location_id'].'" selected="selected">'.$valLocation['location'].'</option>';
                                        else
                                            echo '<option value="'.$valLocation['location_id'].'">'.$valLocation['location'].'</option>';
                                    }                            
                                ?>
                            </select>
                        </label>
                    </div>
                </div>
                <div class="editform">
                    <label>Address <span class="required">*</span></label>
                    <div class="value">
                        <textarea name="address" id="address" class="validate[required] form-control" maxlength="160" style="width: 275px; height: 100px;"><?php if(!empty($_POST['address'])) { echo $_POST['address']; } else if(!empty($HospitalDtls['address'])) { echo $HospitalDtls['address']; }  else { echo ""; } ?></textarea>
                    </div>
                </div>
                <div class="editform">
                    <label>Assign IPS</label>
                    <div class="value">
                    </div>
                </div>
                
                <?php if(!empty($HospitalIPS)) { 
                    include 'vw_edit_ips.php'; ?>
                <?php } else { ?>
                <div class="editform">
                    <label>Enter IP Address <span class="required">*</span></label>
                    <div class="value">
                        <input type="text" name="hospital_ip_first<?php echo $a;?>" id="hospital_ip_first" value="<?php if($_POST['submitForm']) { echo $_POST['hospital_ip_first']; }  else { echo ""; } ?>" class="validate[required,minSize[1],maxSize[3]] form-control" onkeypress="return isNumber(event);"  onkeyup="movetoNext(this, 'hospital_ip_second');"  maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                        <input type="text" name="hospital_ip_second" id="hospital_ip_second" value="<?php if($_POST['submitForm']) { echo $_POST['hospital_ip_second']; }  else { echo ""; } ?>" class="validate[required,minSize[1],maxSize[3]] form-control" onkeypress="return isNumber(event);"  maxlength="3"  onkeyup="movetoNext(this, 'hospital_ip_third');" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                        <input type="text" name="hospital_ip_third" id="hospital_ip_third" value="<?php if($_POST['submitForm']) { echo $_POST['hospital_ip_third']; }  else { echo ""; } ?>" class="validate[required,minSize[1],maxSize[3]] form-control" onkeypress="return isNumber(event);"  maxlength="3"  onkeyup="movetoNext(this, 'hospital_ip_fourth');" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                        <input type="text" name="hospital_ip_fourth" id="hospital_ip_fourth" value="<?php if($_POST['submitForm']) { echo $_POST['hospital_ip_fourth']; }  else { echo ""; } ?>" class="validate[required,minSize[1],maxSize[3]] form-control" onkeypress="return isNumber(event);"  maxlength="3"  style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                         <label style="width:20% !important;">
                            <a href="javascript:void(0);" style="color:#7CA32F;" title="Add IP" onclick="javascript:add_more_ip('1');"><img src="images/add.png" alt="Add"></a>   
                            <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete IP" onclick="javascript:del_more_ip('1');"><img src="images/remove1.png" alt="Add"></a> 
                        </label>
                    </div>
                </div>        
                <input type="hidden" name="extras" id="extras" value='0' />
                <div id='div_1'>
                </div>
                <?php } ?>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_hospital_submit();" />
                </div>  
            </div>
        </form>
    </div>
 <?php   
} 
else if($_REQUEST['action']=='add_hospital')
{
    $success=0;
    $errors=array(); 
    $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $hospital_id=strip_tags($_POST['hospital_id']);
        $hospital_name=strip_tags($_POST['hospital_name']);
        $hospital_short_code=strip_tags($_POST['hospital_short_code']);
        $phone_no=strip_tags($_POST['phone_no']);
        $website_url=strip_tags($_POST['website_url']);
        $location_id=strip_tags($_POST['location_id']);
        $address=strip_tags($_POST['address']);
        $extra_sub_ips = strip_tags($_POST['extras']);
        $total_records=strip_tags($_POST['total_records']);
        // Getting IP Address Value 
        $ip_address_1=strip_tags($_POST['hospital_ip_first']);
        $ip_address_2=strip_tags($_POST['hospital_ip_second']);
        $ip_address_3=strip_tags($_POST['hospital_ip_third']);
        $ip_address_4=strip_tags($_POST['hospital_ip_fourth']);
        if($hospital_name=='')
        {
            $success=0;
            $errors[$i++]="Please enter hospital name";
        }
        if($hospital_short_code=='')
        {
            $success=0;
            $errors[$i++]="Please enter hospital short code";
        }
        if($phone_no=='')
        {
            $success=0;
            $errors[$i++]="Please enter phone number";
        }
        if($website_url=='')
        {
            $success=0;
            $errors[$i++]="Please enter website url";
        }
        if($location_id=='')
        {
            $success=0;
            $errors[$i++]="Please select location";
        }
        if($address=='')
        {
            $success=0;
            $errors[$i++]="Please enter address";
        }
        
        if(empty($total_records))
        {
            if($ip_address_1=="" && $ip_address_2=="" && $ip_address_3=="" && $ip_address_4=="")
            {
                $success=0;
                $errors[$i++]="Please enter ip address";
            }
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        
        // Check Record Exists 
        if($hospital_id)
            $chk_hospital_sql="SELECT hospital_id FROM sp_hospitals WHERE hospital_name='".$hospital_name."' AND hospital_short_code='".$hospital_short_code."' AND hospital_id !='".$hospital_id."'";
        else 
            $chk_hospital_sql="SELECT hospital_id FROM sp_hospitals WHERE hospital_name='".$hospital_name."' AND hospital_short_code='".$hospital_short_code."'"; 
        
        if(mysql_num_rows($db->query($chk_hospital_sql)))
        {
            $success=0;
            echo 'hospitalexists'; // Validation error/record exists
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
            $arr['hospital_id']=$hospital_id;
            $arr['hospital_name']=ucfirst(strtolower($hospital_name));
            $arr['hospital_short_code']=strtoupper(strtolower($hospital_short_code));
            $arr['phone_no']=$phone_no;
            $arr['website_url']=strtolower($website_url);
            $arr['location_id']=$location_id;
            $arr['address']=$address;
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($hospital_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$hospitalClass->AddHospital($arr); 
            if(!empty($InsertRecord))
            {
                // Insert First IP
                if(!empty($hospital_id))
                    $arg['hospital_id']=$hospital_id;
                else 
                   $arg['hospital_id']=$InsertRecord;
                
                // Get Hospital IP
                
                if($total_records)
                {
                    for($s=0;$s<$total_records;$s++)
                    {
                        $exit_hospital_ip_1=strip_tags($_POST['hospital_ip_exist_first_'.$s]);
                        $exit_hospital_ip_2=strip_tags($_POST['hospital_ip_exist_second_'.$s]);
                        $exit_hospital_ip_3=strip_tags($_POST['hospital_ip_exist_third_'.$s]);
                        $exit_hospital_ip_4=strip_tags($_POST['hospital_ip_exist_fourth_'.$s]);
                        
                        $exit_hospital_ip=$exit_hospital_ip_1.".".$exit_hospital_ip_2.".".$exit_hospital_ip_3.".".$exit_hospital_ip_4;
                        
                        $arg['hosp_ip_id']=strip_tags($_POST['hosp_ip_id_'.$s]);
                        $arg['hospital_ip']=strip_tags($exit_hospital_ip);
                        $hospitalClass->AddHospitalIPS($arg); 
                        
                        unset($exit_hospital_ip);
                        unset($arg['hosp_ip_id']);
                        unset($arg['hospital_ip']); 
                    }
                }
                else 
                {
                    $hospital_ip=$ip_address_1.".".$ip_address_2.".".$ip_address_3.".".$ip_address_4;
                    $arg['hospital_ip']=strip_tags($hospital_ip);
                    // Insert Record 
                    $InsertFirstRecord=$hospitalClass->AddHospitalIPS($arg); 
                }
                unset($hospital_ip);
                unset($arg['hospital_ip']);

                if($extra_sub_ips > 0)
                {
                    for($a=1;$a<=$extra_sub_ips;$a++)
                    {
                        $extra_hospital_ip_1=strip_tags($_POST['hospital_ip_first_'.$a]);
                        $extra_hospital_ip_2=strip_tags($_POST['hospital_ip_second_'.$a]);
                        $extra_hospital_ip_3=strip_tags($_POST['hospital_ip_third_'.$a]);
                        $extra_hospital_ip_4=strip_tags($_POST['hospital_ip_fourth_'.$a]); 
                        $arg['hospital_ip']=strip_tags($extra_hospital_ip_1.".".$extra_hospital_ip_2.".".$extra_hospital_ip_3.".".$extra_hospital_ip_4);
                        if(!empty($arg['hospital_ip']))
                        {
                            $InsertExtraRecord=$hospitalClass->AddHospitalIPS($arg); 
                        }
                          // Clear ip array after inserting record
                          unset($arg['hospital_ip']);
                    }
                }
                if($hospital_id)
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
               echo 'hospitalexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['hospital_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['hospital_id'] =$_REQUEST['hospital_id'];
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

        $ChangeStatus =$hospitalClass->ChangeStatus($arr);
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
else if($_REQUEST['action']=='AddIPRow')
{
    $i = $_REQUEST['curr_div'];  
    $j = $i+1;
    ?>
       <div id="div_<?php echo $i;?>" class="multioptions">
            <div class="editform">   
                <label>Enter IP Address <span class="required">*</span></label>
                <div class="value">
                    <input type="text" name="hospital_ip_first_<?php echo $i;?>" id="hospital_ip_first_<?php echo $i;?>" value="<?php if(!empty($_POST['hospital_ip_first_'.$i])) { echo $_POST['hospital_ip_first_'.$i]; } else { echo ""; } ?>" class="validate[required] form-control" onkeypress="return isNumber(event);" onkeyup="movetoNext(this, 'hospital_ip_second_<?php echo $i;?>');"  maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                    <input type="text" name="hospital_ip_second_<?php echo $i;?>" id="hospital_ip_second_<?php echo $i;?>" value="<?php if(!empty($_POST['hospital_ip_second_'.$i])) { echo $_POST['hospital_ip_second_'.$i]; } else { echo ""; } ?>" class="validate[required] form-control" onkeypress="return isNumber(event);" onkeyup="movetoNext(this, 'hospital_ip_third_<?php echo $i;?>');" maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                    <input type="text" name="hospital_ip_third_<?php echo $i;?>" id="hospital_ip_third_<?php echo $i;?>" value="<?php if(!empty($_POST['hospital_ip_third_'.$i])) { echo $_POST['hospital_ip_third_'.$i]; } else { echo ""; } ?>" class="validate[required] form-control" onkeypress="return isNumber(event);" onkeyup="movetoNext(this, 'hospital_ip_fourth_<?php echo $i;?>');" maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                    <input type="text" name="hospital_ip_fourth_<?php echo $i;?>" id="hospital_ip_fourth_<?php echo $i;?>" value="<?php if(!empty($_POST['hospital_ip_fourth_'.$i])) { echo $_POST['hospital_ip_fourth_'.$i]; } else { echo ""; } ?>" class="validate[required] form-control" onkeypress="return isNumber(event);"  maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;"/>
                </div>
            </div>
        </div>
        <div id="div_<?php echo $j;?>"></div>
   <?php 
}
else if($_REQUEST['action']=='delete_ip_content')
{
    $arr['hosp_ip_id']=$_REQUEST['hosp_ip_id'];
    
    if($arr['hosp_ip_id'])
    {
        $DeleteIP =$hospitalClass->RemoveIP($arr);
        
        if($DeleteIP)
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
else if($_REQUEST['action']=='add_content')
{
    $success = 0;
    $errors  = array(); 
    $i       = 0;

    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
        $hospitalId = strip_tags($_POST['hospital_id']);
        $contentType = strip_tags($_POST['content_type']);
        $contentId =  strip_tags($_POST['content_id']);
        $contentDesc = $_POST['content_value_data'];

        if ($contentType == '') {
            $success = 0;
            $errors[$i++] = "Please select content type";
        }
        if ($contentDesc == '') {
            $success = 0;
            $errors[$i++] = "Please enter content description";
        }

        if (count($errors))
        {
           echo 'validationError'; // Validation error/record exists
           exit;
        } else {
            $success = 1;
            $arr['hospital_id']      = $hospitalId;
            $arr['content_id']      = $contentId;
            $arr['content_type']     = $contentType;
            $arr['content_value']    = $contentDesc;
            $arr['status']           = '1';
            $arr['added_user_id']    = strip_tags($_SESSION['admin_user_id']);
            $arr['added_date']       = date('Y-m-d H:i:s');
            $arr['modified_date']    = strip_tags($_SESSION['admin_user_id']);
            $arr['modified_user_id'] = date('Y-m-d H:i:s');

            $InsertId = $hospitalClass->addContent($arr);

            if (!empty($InsertId)) {
                echo 'insertSuccess'; // Insert Record
                exit;
            } else {
               echo 'insertError';
               exit;
            }
        }
    }
} else if ($_REQUEST['action']=='getContent') {
    $arr['hospital_id'] = $_REQUEST['hospital_id'];
    $arr['content_type'] = $_REQUEST['content_type'];
    if ($arr) {
        $contentDtls = $hospitalClass->getContentById($arr);
        if ($contentDtls) {
            echo $contentDtls[0]['content_id'];
            echo "htmlSeperator";
            echo $contentDtls[0]['content_value'];
            exit;
        }
        else {
            echo "error";
            exit;
        }
    }
}
?>