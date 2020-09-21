<?php 
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    if($EditedResponseArr['event_id'])
    {
        $temp_event_id = $EditedResponseArr['event_id'];
        $exi_purpose_id = $EditedResponseArr['purpose_id'];
        
        $generalInformation=$EditedResponseArr['note'];
    }
    ?>
        <form class="form-horizontal" name="generalInfoForm" id="generalInfoForm" method="post" action="general_info_ajax_process.php?action=GeneralInfoFormSubmit">
            <input type="hidden" class="prv_purpose_id" name="general_purpose_id" id="enq_purpose_id" value="<?php echo $exi_purpose_id;?>" />
            <input type="hidden" name="enquiryEvent_id" id="generalEvent_id" value="<?php echo $temp_event_id; ?>" />
            <h4 class="section-head"><span><img src="images/requirnment-icon.png" width="29" height="29"></span>General Information</h4>
            <div class="form-group">
                <div class="col-sm-12">
                    <textarea name="general_info" id="general_info" class="validate[required] form-control" placeholder="General Information"><?php if(isset($generalInformation)) echo $generalInformation;?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">                            
                    <input type="button" class="btn btn-primary" id="general_infoSubmit" name="general_infoSubmit" value="SUBMIT" onclick="return submitGeneralInfo();">

                </div>
            </div>
        </form>
<?php
}?>