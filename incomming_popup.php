<?php   
        require_once 'inc_classes.php';        
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";        
        include "classes/eventClass.php";
        $eventClass = new eventClass();
        include "classes/employeesClass.php";
        $employeesClass = new employeesClass();
	      include "classes/professionalsClass.php";
        $professionalsClass = new professionalsClass();
        include "classes/commonClass.php";
        $commonClass= new commonClass();
        require_once 'classes/functions.php'; 
        require_once 'classes/config.php'; 
?>
<?php
if($_REQUEST['action']=='chk_call')
{
    $args['ext_no'] = $_SESSION['avaya_agentid'];
    if (trim($args['ext_no']) != '') {
      $condition .= "AND ext_no = '" . $args['ext_no'] . "' ";
    }
    /* if (trim($args['calling_phone_no']) != '') {
          $condition .= "AND calling_phone_no = '" . $args['calling_phone_no'] . "' ";
      }
      if (trim($args['CallUniqueID']) != '') {
          $condition .= "AND CallUniqueID = '" . $args['CallUniqueID'] . "' ";
      }
    */ 
   /* if (trim($_REQUEST['status']) != '') {
        $condition .= "AND cl_status = '1' ";
    }*/

    $max_id=mysql_query("SELECT * FROM sp_incoming_call  where is_deleted = '0'  $condition ORDER BY call_datetime DESC limit 0, 1") or die(mysql_error());
    $max_id_row = mysql_fetch_array($max_id);
    $row_count = mysql_num_rows($max_id); 
    if($row_count > 0)
		{ 
        $calling_phone_no=$max_id_row['calling_phone_no'];
        $status=$max_id_row['status'];
        $cl_status=$max_id_row['cl_status'];
        $CallUniqueID=$max_id_row['CallUniqueID'];
        //var_dump($status);die();
        if($status == 'R' && $cl_status == '1'){
          ?>
          <div style="background-color: #76D7C4  ">
        <div class="modal-header">
        <button type="button" id="avaya_close" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" align="center">Incomming Call</span></h3>	
        </div>
        </div>
        <div>
        <h2 align="center"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span><?php echo $calling_phone_no; ?></h2>
        </div>
        <div align="center ">
        
        <button type="button" class="btn-lg btn-success" onclick="return acceptCaller(<?php echo $calling_phone_no; ?>,<?php echo $CallUniqueID ;?>);"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>Call Accept</button>
       <!-- <button type="button" class="btn-lg btn-danger" onclick="return disconnect_Caller(<?php echo $calling_phone_no; ?>,<?php echo $CallUniqueID ;?>);"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>Call Decline</button>-->
        </div>
        <br>
          <?php 
        }
        else if($status == 'D' && $cl_status == '2'){ 
        ?>
        
       
        <div class="modal-header" style="background-color: #76D7C4  ">
        <button type="button" id="avaya_close" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" align="center">Call Disconnected</span></h3>	
        </div>
        
        <div>
        <h2 align="center"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span><?php echo $calling_phone_no; ?></h2>
        </div>
        <div class="row" align="center" >
        <h3>Enter Remark:</h3>	
        <textarea id= "disconect_remark" rows="4" cols="50"></textarea>
        </div>
        <div align="center ">
        <button type="button" class="btn-lg btn-success" onclick="return disconnect_Caller(<?php echo $calling_phone_no; ?>,<?php echo $CallUniqueID ;?>);">OK</button>
         </div>
      
        <br>
      <?php  }
?>
<?php  }else{
    return 'false';
  } ?>
<?php  
}
?>