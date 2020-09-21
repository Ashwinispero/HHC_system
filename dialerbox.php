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
<?php
    if($_REQUEST['action']=='vw_dial')
    {
?>
<div style="background-color: #76D7C4  ">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">Soft Phone</span></h4>
</div>

<div class="container">
  <div id="output"></div>
  <div class="row">
    <div class="digit" id="one">1</div>
    <div class="digit" id="two">2
      <div class="sub">ABC</div>
    </div>
    <div class="digit" id="three">3
      <div class="sub">DEF</div>
    </div>
  </div>
  <div class="row">
    <div class="digit" id="four">4
      <div class="sub">GHI</div>
    </div>
    <div class="digit" id="five">5
      <div class="sub">JKL</div>
    </div>
    <div class="digit">6
      <div class="sub">MNO</div>
    </div>
  </div>
  <div class="row">
    <div class="digit">7
      <div class="sub">PQRS</div>
    </div>
    <div class="digit">8
      <div class="sub">TUV</div>
    </div>
    <div class="digit">9
      <div class="sub">WXYZ</div>
    </div>
  </div>
  <div class="row">
    <div class="digit">*
    </div>
    <div class="digit">0
    </div>
    <div class="digit">#
    </div>
  </div>
  <div class="botrow"><i class="fa fa-star-o dig" aria-hidden="true"></i>
    <div id="call"><i class="fa fa-phone" aria-hidden="true"></i></div>
    <i class="fa fa-long-arrow-left dig" aria-hidden="true"></i>
  </div>
</div>
</div>
<?php  
}
?>
