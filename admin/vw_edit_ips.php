<?php
    // Getting All IPS 
    for($i=0;$i<count($HospitalIPS);$i++)
    { 
       // Get Ip Address
        $hospital_ip=$HospitalIPS[$i]['hospital_ip'];
        $hospital_ip_val=explode(".", $hospital_ip);
        if(!empty($hospital_ip_val))
        {
            $hospital_ip_first=$hospital_ip_val[0];
            $hospital_ip_second=$hospital_ip_val[1];
            $hospital_ip_third=$hospital_ip_val[2];
            $hospital_ip_fourth=$hospital_ip_val[3];
        }
        ?>
            <div class="editform" id="IPData_<?php echo $HospitalIPS[$i]['hosp_ip_id']; ?>">
                <label>Enter IP Address <span class="required">*</span></label>
                <div class="value">
                    <input type="hidden" name="total_records" id="total_records" value="<?php echo count($HospitalIPS); ?>" />
                    <input type="hidden" name="hospital_id_<?php echo $i;?>" id="hospital_id_<?php echo $i;?>" value="<?php if(!empty($HospitalIPS[$i]['hospital_id'])) { echo $HospitalIPS[$i]['hospital_id']; } ?>" />
                    <input type="hidden" name="hosp_ip_id_<?php echo $i;?>" id="hosp_ip_id_<?php echo $i;?>" value="<?php if(!empty($HospitalIPS[$i]['hosp_ip_id'])) { echo $HospitalIPS[$i]['hosp_ip_id']; } ?>" />
                    <input type="text" name="hospital_ip_exist_first_<?php echo $i;?>" id="hospital_ip_exist_first_<?php echo $i;?>" value="<?php if($_POST['submitForm']) { echo $_POST['hospital_ip_exist_first_'.$i]; } else if($hospital_ip_first) { echo $hospital_ip_first; }  else { echo ""; } ?>" class="validate[required,minSize[1],maxSize[3]] form-control" onkeyup="if (/[^0-9]/g.test(this.value)) this.value = this.value.replace(/[^0-9]/g,'')"  maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                    <input type="text" name="hospital_ip_exist_second_<?php echo $i;?>" id="hospital_ip_exist_second_<?php echo $i;?>" value="<?php if($_POST['submitForm']) { echo $_POST['hospital_ip_exist_second_'.$i]; } else if($hospital_ip_second) { echo $hospital_ip_second; }  else { echo ""; } ?>" class="validate[required,minSize[1],maxSize[3]] form-control" onkeyup="if (/[^0-9]/g.test(this.value)) this.value = this.value.replace(/[^0-9]/g,'')"  maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                    <input type="text" name="hospital_ip_exist_third_<?php echo $i;?>" id="hospital_ip_exist_third_<?php echo $i;?>" value="<?php if($_POST['submitForm']) { echo $_POST['hospital_ip_exist_third_'.$i]; }  else if($hospital_ip_third) { echo $hospital_ip_third; } else { echo ""; } ?>" class="validate[required,minSize[1],maxSize[3]] form-control" onkeyup="if (/[^0-9]/g.test(this.value)) this.value = this.value.replace(/[^0-9]/g,'')"  maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                    <input type="text" name="hospital_ip_exist_fourth_<?php echo $i;?>" id="hospital_ip_exist_fourth_<?php echo $i;?>" value="<?php if($_POST['submitForm_'.$i]) { echo $_POST['hospital_ip_exist_fourth_']; } else if($hospital_ip_fourth) { echo $hospital_ip_fourth; } else { echo ""; } ?>" class="validate[required,minSize[1],maxSize[3]] form-control" onkeyup="if (/[^0-9]/g.test(this.value)) this.value = this.value.replace(/[^0-9]/g,'')"  maxlength="3" style="width:18% !important;margin-right: 2%;padding:6px 8px !important;" />
                    <?php if($i==0) { ?>
                        <label style="width:20% !important;">
                            <a href="javascript:void(0);" style="color:#7CA32F;" title="Add IP" onclick="javascript:add_more_ip('1');"><img src="images/add.png" alt="Add"></a>   
                            <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete IP" onclick="javascript:del_more_ip('1');"><img src="images/remove1.png" alt="Add"></a> 
                        </label>
                    <?php } else { ?>
                        <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:delete_ip_content('<?php  if(!empty($HospitalIPS[$i]['hosp_ip_id'])) { echo $HospitalIPS[$i]['hosp_ip_id']; } ?>');"><img src="images/icon-inactive.png" /></a>
                    <?php } ?>
                </div>
            </div>
        <?php
        
        unset($hospital_ip_val);
    }
    ?>
        <input type="hidden" name="extras" id="extras" value='0' />
        <div id='div_1'>
        </div>
    <?php 
?>