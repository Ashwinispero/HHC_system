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
    if (trim($_REQUEST['status']) != '') {
        $condition .= "AND status = '" . $_REQUEST['status'] . "' ";
    }

   $max_id=mysql_query("SELECT * FROM sp_incoming_call  where is_deleted = '0' $condition ORDER BY call_datetime DESC limit 0, 1") or die(mysql_error());
   $max_id_row = mysql_fetch_array($max_id);
   $row_count = mysql_num_rows($max_id); 

			if($row_count > 0)
			{ 
        $calling_phone_no=$max_id_row['calling_phone_no'];
?>
<style>
.digit,
.dig {
  float: left;
  padding: 10px 30px;
  width: 80px;
  font-size: 2rem;
  cursor: pointer;
}

.sub {
  font-size: 0.8rem;
  color: grey;
}

.container {
  background-color: white;
  width: 280px;
  padding: 20px;
  margin: 30px auto;
  height: 420px;
  text-align: center;
  box-shadow: 0 4px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
}

#output {
  font-family: "Exo";
  font-size: 2rem;
  height: 60px;
  font-weight: bold;
  color: #45B39D ;
}

#call {
  display: inline-block;
  background-color: #66bb6a;
  padding: 4px 30px;
  margin: 10px;
  color: white;
  border-radius: 4px;
  float: left;
  cursor: pointer;
}

.botrow {
  margin: 0 auto;
  width: 280px;
  clear: both;
  text-align: center;
  font-family: 'Exo';
}

.digit:active,
.dig:active {
  background-color: #e6e6e6;
}

#call:hover {
  background-color: #81c784;
}

.dig {
  float: left;
  padding: 10px 20px;
  margin: 10px;
  width: 30px;
  cursor: pointer;
}
</style>
<script>
var count = 0;

$(".digit").on('click', function() {
  var num = ($(this).clone().children().remove().end().text());
  if (count < 11) {
    $("#output").append('<span>' + num.trim() + '</span>');

    count++
  }
});

$('.fa-long-arrow-left').on('click', function() {
  $('#output span:last-child').remove();
  count--;
});
</script>
<link href="https://fonts.googleapis.com/css?family=Exo" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
        <div style="background-color: #76D7C4  ">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" align="center">Incomming Call</span></h3>	
        </div>
        </div>
        <div>
        <h2 align="center"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span><?php echo $calling_phone_no; ?></h2>
        </div>
        <div align="center ">
        
        <button type="button" class="btn-lg btn-success" onclick="return acceptCaller(<?php echo $calling_phone_no; ?>);"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>Call Accept</button>
        <button type="button" class="btn-lg btn-danger"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>Call Decline</button>
        </div>
        <br>
  <?php  }else{
    return 'false';
  } ?>
<?php  
}
?>