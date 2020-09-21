<?php
require_once 'inc_classes.php';
require_once '../classes/feedbackClass.php';
$feedbackClass=new feedbackClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='vw_add_feedback')
{
    // Getting Feedback Details
    $arr['feedback_id']=$_REQUEST['feedback_id'];
    $FeedbackDtls=$feedbackClass->GetFeedbackById($arr);  
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($FeedbackDtls)) { echo "Edit"; } else { echo "Add"; } ?> Feedback </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_feedback" id="frm_add_feedback" method="post" action ="feedback_ajax_process.php?action=add_feedback" autocomplete="off">
            <div class="scrollbars" style="height:185px !important;">
                <div class="editform">
                    <label style="width:25% !important;">Question <span class="required">*</span></label>
                    <div class="value" style="width:75% !important;" >
                        <input type="hidden" name="feedback_id" id="feedback_id" value="<?php echo $arr['feedback_id']; ?>" />
                        <textarea name="question" id="question" class="validate[required,maxSize[160]] form-control" maxlength="160" onkeyup="if (/[^a-zA-Z0-9 ,-/()?!]/g.test(this.value)) this.value = this.value.replace(/[^a-zA-Z0-9 ,-/()?!]/g,'')" style="width: 100%;" rows="3"><?php if($_POST['submitForm']) { echo $_POST['question']; } else if($FeedbackDtls['question']) echo $FeedbackDtls['question']; else { echo ""; } ?></textarea>
                    </div>
                </div>
                <div class="editform">
                    <label style="width:25% !important;">Option Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <select name="option_type" id="option_type" class="validate[required]" onchange="return getOption(this.value);">
                                    <option value=""<?php if($_POST['option_type']=='') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='') { echo 'selected="selected"'; } ?>>Option Type</option>
                                    <option value="1"<?php if($_POST['option_type']=='1') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='1') { echo 'selected="selected"'; }?>>Textual</option>
                                    <option value="2"<?php if($_POST['option_type']=='2') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='2') { echo 'selected="selected"'; }?>>Radio</option>
                                    <option value="3"<?php if($_POST['option_type']=='3') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='3') { echo 'selected="selected"'; }?>>Checkbox</option>
                                    <option value="4"<?php if($_POST['option_type']=='4') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='4') { echo 'selected="selected"'; }?>>Rating</option>
                                </select>
                            </label>
                        </div>
                </div>
                <div id="OptionList">
                    <div class="editform">
                        <label style="width:25% !important;">Enter Option Value <span class="required">*</span></label>
                        <div class="value" style="width:50% !important;">
                            <input type="text" name="option_value" id="option_value" value="<?php if($_POST['submitForm']) { echo $_POST['option_value']; }  else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" onkeyup="if (/[^A-Za-z0-9 ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9 ]/g,'')"  maxlength="50" style="width: 95% !important; margin-right: 3px;" />
                       </div>
                        <label style="width:15%;">
                            <a href="javascript:void(0);" style="color:#7CA32F;" title="Add Option" onclick="javascript:add_more_option('1');"><img src="images/add.png" width="20" height="20" alt="Add"></a>   
                            <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete Option" onclick="javascript:del_more_option('1');"><img src="images/remove1.png" width="20" height="20" alt="Add"></a> 
                        </label>
                    </div>        
                </div>
                <input type="hidden" name="extras" id="extras" value='0' />
                <div id='div_1'>
                </div>
               </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_feedback_submit();" />
                </div>  
        </form>
    </div>
 <?php   
}
else if($_REQUEST['action']=='AddOptionRow')
{
    $i = $_REQUEST['curr_div'];  
    $j = $i+1;
    ?>
       <div id="div_<?php echo $i;?>" class="multioptions">
            <div class="editform">   
                <label style="width:25% !important;">Enter Option Value <span class="required">*</span></label>
                <div class="value" style="width:50% !important;">
                    <input type="text" name="option_value<?php echo $i;?>" id="option_value<?php echo $i;?>" value="<?php if(!empty($_POST['option_value'.$i])) { echo $_POST['option_value'.$i]; } else { echo ""; } ?>" class="validate[required] form-control" onkeyup="if (/[^A-Za-z0-9 / ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9 / ]/g,'')"  maxlength="50" style="width: 95% !important" />
                </div>
            </div>
        </div>
        <div id="div_<?php echo $j;?>"></div>
   <?php 
}
else if($_REQUEST['action']=='add_feedback')
{
    $success=0;
    $errors=array(); 
    $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {       
        $extra_sub_options = strip_tags($_POST['extras']);
        $feedback_id=strip_tags($_POST['feedback_id']);
        $question=strip_tags($_POST['question']);
        $option_type=strip_tags($_POST['option_type']);
        if($question=='')
        {
            $success=0;
            $errors[$i++]="Please enter name";
        }
        if($option_type=='')
        {
            $success=0;
            $errors[$i++]="Please select type";
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        // Check Record Exists 
        if($feedback_id)
            $chk_feedback_sql="SELECT feedback_id FROM sp_feedback_form WHERE question='".$question."' AND feedback_id !='".$feedback_id."'";
        else 
            $chk_feedback_sql="SELECT feedback_id FROM sp_feedback_form WHERE question='".$question."'"; 
        
        if(mysql_num_rows($db->query($chk_feedback_sql)))
        {
            $success=0;
            echo 'feedbackexists';
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
            $arr['feedback_id']=$feedback_id;
            $arr['question']=ucfirst(strtolower($question));
            $arr['option_type']=$option_type;
            $arr['modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($feedback_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$feedbackClass->AddFeedback($arr); 
            if(!empty($InsertRecord))
            {
                // Insert First Option Value
                $arg['feedback_id']=$InsertRecord;
                $arg['option_value']=ucfirst(strtolower(strip_tags($_POST['option_value'])));
                $arg['status']='1';
                $arg['added_by']=strip_tags($_SESSION['admin_user_id']);
                $arg['added_date']=date('Y-m-d H:i:s');
                $arg['modified_by']=strip_tags($_SESSION['admin_user_id']);
                $arg['last_modified_date']=date('Y-m-d H:i:s');
                if($arr['option_type'] !='1' && $arr['option_type'] !='4')
                   $Insertoptions=$feedbackClass->addMultiOptions($arg); 
                
                if($extra_sub_options > 0)
                {
                    for($a=1;$a<=$extra_sub_options;$a++)
                    {
                       $arg['option_value'] = ucfirst(strtolower(mysql_real_escape_string($_POST['option_value'.$a])));

                        if(!empty($arg['option_value']))
                        {
                            $Insertoptions=$feedbackClass->addMultiOptions($arg); 
                        }
                          // Clear option array after inserting record
                          unset($arg['option_value']);
                    }
                }
                
                if($feedback_id)
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
               echo 'feedbackexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['feedback_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['feedback_id'] =$_REQUEST['feedback_id'];
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

        $ChangeStatus =$feedbackClass->ChangeStatus($arr);
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
else if($_REQUEST['action']=='vw_feedback')
{
    // Getting Feedback Details
    $arr['feedback_id']=$_REQUEST['feedback_id'];
    $FeedbackDtls=$feedbackClass->GetFeedbackById($arr);
    // Getting All options
    $FeedbackOptionDtls=$feedbackClass->GetAllFeedbackOptions($arr);
    
    if(!empty($FeedbackOptionDtls))
    {
        for($i=0;$i<count($FeedbackOptionDtls);$i++)
        {
            if(!empty($FeedbackOptionDtls[$i]['option_value']))
            {
               $optionVal .=$FeedbackOptionDtls[$i]['option_value'].','; 
            }
        }
    }
    else 
    {
       $optionVal=""; 
    }
    ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">View Feedback Details</h4>
        </div>
        <div class="modal-body">
            <div>
                <div class="editform">
                    <label style="width:20% !important;"> Question : </label>
                    <div class="value" style="width:80% !important;">
                        <?php if(!empty($FeedbackDtls['question'])) { echo $FeedbackDtls['question']; } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="editform">
                    <label style="width:20% !important;"> Option Type : </label>
                    <div class="value" style="width:80% !important;">
                        <?php if(!empty($FeedbackDtls['option_typeVal'])) { echo $FeedbackDtls['option_typeVal'];  } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="editform">
                    <label style="width:20% !important;"> Option Value : </label>
                    <div class="value" style="width:80% !important;">
                        <?php if(!empty($optionVal)) { echo substr_replace($optionVal, "", -1); } else {  echo "-"; } ?>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    <?php
}
else if($_REQUEST['action']=='vw_edit_feedback')
{
    // Getting Feedback Details
    $arr['feedback_id']=$_REQUEST['feedback_id'];
    $FeedbackDtls=$feedbackClass->GetFeedbackById($arr);
    // Getting All options
    $FeedbackOptionDtls=$feedbackClass->GetAllFeedbackOptions($arr);
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($FeedbackDtls)) { echo "Edit"; } else { echo "Add"; } ?> Feedback </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_edit_feedback" id="frm_edit_feedback" method="post" action ="feedback_ajax_process.php?action=edit_feedback" autocomplete="off">
            <div class="scrollbars" style="height:235px !important; width:100% !important;">
                <div class="editform">
                    <label style="width:25% !important;">Question <span class="required">*</span></label>
                    <div class="value" style="width:75% !important;">
                        <input type="hidden" name="feedback_id" id="feedback_id" value="<?php echo $arr['feedback_id']; ?>" />
                        <textarea name="question" id="question" class="validate[required,maxSize[160]] form-control" maxlength="160" onkeyup="if (/[^a-zA-Z0-9 ,-/()?!]/g.test(this.value)) this.value = this.value.replace(/[^a-zA-Z0-9 ,-/()?!]/g,'')" style="width:100%;" rows="3"><?php if($_POST['submitForm']) { echo $_POST['question']; } else if($FeedbackDtls['question']) echo $FeedbackDtls['question']; else { echo ""; } ?></textarea>
                    </div>
                </div>
                <?php if(!empty($FeedbackDtls['option_type']) && $FeedbackDtls['option_type'] !='1' && $FeedbackDtls['option_type'] !='4') { $styl="display:block;"; } else { $styl="display:none;"; } ?> 
                <div class="editform">
                    <label style="width:25% !important;">Option Type <span class="required">*</span></label>
                        <div class="value dropdown">
                            <label>
                                <input type="hidden" name="pre_option_type" id="pre_option_type" value="<?php if(!empty($FeedbackDtls['option_type'])) { echo $FeedbackDtls['option_type']; } ?>" />
                                <select name="option_type" id="option_type" class="validate[required]" onchange="return getOption(this.value);">
                                    <option value=""<?php if($_POST['option_type']=='') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='') { echo 'selected="selected"'; } ?>>Option Type</option>
                                    <option value="1"<?php if($_POST['option_type']=='1') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='1') { echo 'selected="selected"'; }?>>Textual</option>
                                    <option value="2"<?php if($_POST['option_type']=='2') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='2') { echo 'selected="selected"'; }?>>Radio</option>
                                    <option value="3"<?php if($_POST['option_type']=='3') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='3') { echo 'selected="selected"'; }?>>Checkbox</option>
                                    <option value="4"<?php if($_POST['option_type']=='4') { echo 'selected="selected"'; } else if($FeedbackDtls['option_type']=='4') { echo 'selected="selected"'; }?>>Rating</option>
                                </select>
                            </label>
                            <span id="AddMoreDiv" style="<?php echo $styl; ?>">
                                <a href="javascript:void(0);" style="color:#7CA32F;" title="Add Option" onclick="javascript:add_more_option('1');"><img src="images/add.png" width="20" height="20" alt="Add"></a>
                                <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete Option" onclick="javascript:del_more_option('1');"><img src="images/remove1.png" width="20" height="20" alt="Add"></a>
                            </span>
                        </div>
                </div>
                <div id="OptionList" style="<?php echo $styl; ?>">
                    <input type="hidden" name="tot_feedback_options" id="tot_feedback_options" value="<?php if(!empty($FeedbackOptionDtls)) { echo count($FeedbackOptionDtls); } ?>" />
                     <?php
                         if(!empty($FeedbackOptionDtls)) 
                         { 
                             for($i=0;$i<count($FeedbackOptionDtls);$i++)
                             {
                               ?>
                                 <div class="editform" id="OptionData_<?php echo $FeedbackOptionDtls[$i]['feedback_option_id']; ?>">
                                     <label style="width:25% !important;">Enter Option Value <span class="required">*</span></label>
                                     <div class="value" style="width:70%;">
                                         <input type="hidden" name="feedback_option_id_<?php echo $i; ?>" id="feedback_option_id_<?php echo $i; ?>" value="<?php if(!empty($FeedbackOptionDtls[$i]['feedback_option_id'])) { echo $FeedbackOptionDtls[$i]['feedback_option_id']; } ?>" />
                                         <input type="text" name="option_value_<?php echo $i; ?>" id="option_value_<?php echo $i; ?>"  value="<?php if($_POST['submitForm']) { echo $_POST['option_value_'.$i]; } else if(!empty($FeedbackOptionDtls[$i]['option_value'])) { echo $FeedbackOptionDtls[$i]['option_value']; } else { echo ""; } ?>" class="validate[required] form-control opt_val" onkeyup="if (/[^A-Za-z0-9 / ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9 / ]/g,'')"  maxlength="50" style="width: 85% !important; margin-right: 3px;" />
                                         <img src="images/icon-inactive.png" onclick="javascript:delete_option('<?php echo $FeedbackOptionDtls[$i]['feedback_option_id']; ?>');" />
                                         <br/>
                                    </div> 
                                 </div>
                            <?php 
                             }
                         }
                         else
                         { ?>                                 
                             <div class="editform">
                                 <label style="width:25% !important;">Enter Option Value <span class="required">*</span></label>
                                 <div class="value" style="width:50%;">
                                     <input type="text" name="option_value" id="option_value" value="<?php if($_POST['submitForm']) { echo $_POST['option_value']; }  else { echo ""; } ?>" class="validate[required] form-control opt_val" onkeyup="if (/[^A-Za-z0-9 ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z0-9 ]/g,'')"  maxlength="50" style="width: 95% !important; margin-right: 3px;" />      
                                </div> 
                             </div>
                         <?php   
                         }
                     ?> 
                 </div>
                 <input type="hidden" name="extras" id="extras" value='0' />
                 <div id='div_1'>
                 </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return edit_feedback_submit();" />
                </div>  
            </div>
        </form>
    </div>
 <?php  
}
else if($_REQUEST['action']=='edit_feedback')
{
    $success=0;
    $errors=array(); 
    $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $extra_sub_options = strip_tags($_POST['extras']);
        $feedback_id=strip_tags($_POST['feedback_id']);
        $question=strip_tags($_POST['question']);
        $option_type=strip_tags($_POST['option_type']);
        $option_value=strip_tags($_POST['option_value']);
        $pre_option_type=strip_tags($_POST['pre_option_type']);
        $tot_feedback_options=$_POST['tot_feedback_options'];
        if($question=='')
        {
            $success=0;
            $errors[$i++]="Please enter name";
        }
        if($option_type=='')
        {
            $success=0;
            $errors[$i++]="Please select type";
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        // Check Record Exists 
        if($feedback_id)
            $chk_feedback_sql="SELECT feedback_id FROM sp_feedback_form WHERE question='".$question."' AND feedback_id !='".$feedback_id."'";
        else 
            $chk_feedback_sql="SELECT feedback_id FROM sp_feedback_form WHERE question='".$question."'"; 
        if(mysql_num_rows($db->query($chk_feedback_sql)))
        {
            $success=0;
            echo 'feedbackexists'; 
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
            $arr['feedback_id']=$feedback_id;
            $arr['question']=ucfirst(strtolower($question));
            $arr['option_type']=$option_type;
            $arr['modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            if(empty($feedback_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$feedbackClass->AddFeedback($arr); 
            if(!empty($InsertRecord))
            {
                // Insert First Option Value
                $arg['feedback_id']=$feedback_id;
                $arg['pre_option_type'] =$pre_option_type;
                $arg['option_type']=$option_type;
                $arg['option_value']=ucfirst(strtolower(strip_tags($option_value)));
                $arg['status']='1';
                $arg['added_by']=strip_tags($_SESSION['admin_user_id']);
                $arg['added_date']=date('Y-m-d H:i:s');
                $arg['modified_by']=strip_tags($_SESSION['admin_user_id']);
                $arg['last_modified_date']=date('Y-m-d H:i:s');
                
                if($arg['option_type'] !='1' && $arg['option_type'] !='4' && !empty($arg['option_value']))
                {
                   $feedbackClass->addMultiOptions($arg); 
                } 
                unset($arg['option_value']);
                
                if(!empty($tot_feedback_options))
                {
                    for($s=0;$s<$tot_feedback_options;$s++)
                    {
                        $arg['feedback_option_id'] = mysql_real_escape_string($_POST['feedback_option_id_'.$s]);
                        $arg['option_value'] = mysql_real_escape_string($_POST['option_value_'.$s]);
                        if(!empty($arg['option_value']))
                            $feedbackClass->addMultiOptions($arg);
                        
                        unset($arg['feedback_option_id']);
                        unset($arg['option_value']);   
                    }  
                }
                if($extra_sub_options > 0)
                {
                    for($a=1;$a<=$extra_sub_options;$a++)
                    {
                       $arg['option_value'] = mysql_real_escape_string($_POST['option_value'.$a]);

                        if(!empty($arg['option_value']))
                        {
                            $feedbackClass->addMultiOptions($arg); 
                        }
                          // Clear option array after inserting record
                          unset($arg['option_value']);
                    }
                }
                if($feedback_id)
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
               echo 'feedbackexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='delete_feedback_option')
{
    if(!empty($_REQUEST['feedback_option_id']))
    {
        $arr['feedback_option_id']=$_REQUEST['feedback_option_id'];
        $DeleteOption=$feedbackClass->RemoveFeedbackOptionById($arr);
        if(!empty($DeleteOption))
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
    else
    {
        echo 'error';
        exit;
    }
}
?>