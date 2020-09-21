<?php
        include "admin_authentication.php"; 
        $adminClass = new adminClass();
        $admin_user_id = $_SESSION['admin_user_id'];  
        $adminDetails = $adminClass->selectAdmin($admin_user_id);  
?>
<form>
    
    <div class="profileform">
        <label>Name</label>
         <div class="value">
             <?php if(!empty($adminDetails['name'])) { echo $adminDetails['name']." "; } if(!empty($adminDetails['first_name'])) { echo $adminDetails['first_name']." "; } if(!empty($adminDetails['middle_name'])) { echo $adminDetails['middle_name']; } else { echo ""; } ?>
         </div>
    </div>
    <div class="profileform">
        <label>Email</label>
        <div class="value">
            <?php if(!empty($adminDetails['email_id'])) { echo $adminDetails['email_id']; } else { echo "-"; } ?>
        </div>
    </div>
    <div class="profileform">
        <label>Mobile Number</label>
        <div class="value">
            <?php if(!empty($adminDetails['mobile_no'])) { echo $adminDetails['mobile_no']; } else { echo "-"; } ?>
        </div>
    </div>  
    <div class="profileform">
        <label>Landline Number</label>
        <div class="value">
            <?php if(!empty($adminDetails['landline_no'])) { echo $adminDetails['landline_no']; } else { echo "-"; } ?>
        </div>
    </div> 
    <div class="profileform">
        <label>Alternate Email</label>
        <div class="value">
             <?php if(!empty($adminDetails['alternate_email_id'])) { echo $adminDetails['alternate_email_id']; } else { echo "-"; } ?>
        </div>
    </div>
</form>