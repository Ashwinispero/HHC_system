<?php   require_once 'inc_classes.php';        
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
    if($_REQUEST['action']=='vw_dial'){
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
  width: 250px;
  padding: 20px;
  margin: 30px auto;
  height: 300px;
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
  var num1 = ($(this).clone().children().remove().end().text());
 // alert(num1);
  if (count < 11) {
    
     var ex_no = document.getElementById("output").value;
     //alert(ex_no);
     var phone_no = ex_no + num1;
    document.getElementById("output").value = phone_no;
   // $("#output").append('<span>' + num.trim() + '</span>');

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
        <button type="button" id="avaya_close" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" align="center"> Soft Phone  </span></h3>	
        </div>
        </div>
        <br>
        <div align="center" >
        <input id="output" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="11"></input>
        </div>
        <div align="center " class="container">
        <div class="row">
          <div class="digit" id="one" >1</div>
          <div class="digit" id="two" >2</div>
          <div class="digit" id="three">3</div>
        </div>
        <div class="row">
          <div class="digit" id="four">4</div>
          <div class="digit" id="five">5</div>
          <div class="digit" id="six">6 </div>
        </div>
        <div class="row">
          <div class="digit" id="seven">7</div>
          <div class="digit" id="eight">8</div>
          <div class="digit" id="nine">9</div>
        </div>
        <div class="row">
          <div class="digit">*</div>
          <div class="digit" id="zero">0</div>
          <div class="digit">#</div>
        </div>
        <button type="button" class="btn-lg btn-success" onclick="return soft_call();"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>Softdial</button>
    </div>
        <br>
<?php }
elseif($_REQUEST['action']=='vw_softdial')
{
  $phone_no=$_REQUEST['phone_no'];
  $user = $_SESSION['first_name'];
  $form_url =  "http://192.168.0.131/API/Click2call.php?user=".$user."&phoneno=".urlencode($phone_no)."";
  $data_to_post = array();
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $form_url);
  curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
  $result = curl_exec($curl);
  curl_close($curl);
//  $rel = explode(" ",$result,0);
  //echo json_encode($rel);
  echo $result;
}
elseif($_REQUEST['action']=='vw_pause_mode')
{
  $status=$_REQUEST['status'];
  $user = $_SESSION['first_name'];
  $form_url =  "http://192.168.0.131/API/ChangeState.php?user=".$user."&value=PAUSE";
  $data_to_post = array();
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $form_url);
  curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
  $result = curl_exec($curl);
  curl_close($curl);
  echo $result;
}
elseif($_REQUEST['action']=='vw_ready_mode')
{
  $status=$_REQUEST['status'];
  $user = $_SESSION['first_name'];
  $form_url =  "http://192.168.0.131/API/ChangeState.php?user=".$user."&value=RESUME";
  $data_to_post = array();
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $form_url);
  curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
  $result = curl_exec($curl);
  curl_close($curl);
  echo $result;
}
elseif($_REQUEST['action']=='vw_hang_mode')
{
  $status=$_REQUEST['status'];
  $user = $_SESSION['first_name'];
  $form_url =  "http://192.168.0.131/API/Hangup.php?user=".$user;
  $data_to_post = array();
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $form_url);
  curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
  $result = curl_exec($curl);
  curl_close($curl);
  echo $result;
}
elseif($_REQUEST['action']=='vw_conf')
{ 
  //$status=$_REQUEST['status'];
  ?>
  

  <div style="background-color: #76D7C4  ">
        <div class="modal-header">
        <button type="button" id="avaya_close" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" align="center">Add Conferance Call</span></h3>	
        </div>
        </div>
        <br>
        <div align="center" >
        <input id="conf_no" style="width:80%;" onkeyup="this.value=this.value.replace(/[^\d]/,'')" maxlength="11"></input>
        </div>
        <br>
        <div align="center " id="btn_incoming">
        
        <button type="button" class="btn-lg btn-success" onclick="return add_call();"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>Add Call</button>
        </div>
        <br>
        <?php
}
elseif($_REQUEST['action']=='vw_conf_mode'){
  $status=$_REQUEST['status'];
  //$no = '77';
  $conf_no=$_REQUEST['conf_no'];
 // $conf_no_new=$no.''.$conf_no;
  $user = $_SESSION['first_name'];
  //$user = $_SESSION['first_name']; http://192.168.0.131/API/Conference.php?user=ashwini&phoneno=XXXXXX
  $form_url =  "http://192.168.0.131/API/Conference.php?user=".$user."&phoneno=".urlencode($conf_no)."";
    var_dump($form_url);
  $data_to_post = array();
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $form_url);
  curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
  $result = curl_exec($curl);
  curl_close($curl);
  echo $result;
}
?>
