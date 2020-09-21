<?php 
      include "inc_classes.php";
     // include "admin_authentication.php";      
     // include "pagination-include.php";
?>
<html lang="en">
<head>
<title>Welcome to SPERO</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="http://www.instinctcoder.com/wp-content/uploads/2014/02/tabnavi.js"></script>
</head>
<script>
  $(function () {
    $( "#tabs" ).tabs();
 
  });
 </script>

<body style="background-color: #FFFAF0;">
<div id="wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-lg-12">
<h1 class="page-header" align="center" style="color:black">
    Spero Employee Schedule Details              
</h1>
</div>
</div>
   <div id="tabs" >
  <ul style="font-weight:bold" >
    <li class="active" ><a style="background-color:#00cfcb" href="#tabs-1">Healthcare attendants</a></li>
    <li><a href="#tabs-2" style="background-color:#00cfcb">Nurse</a></li>
    <li><a href="#tabs-3" style="background-color:#00cfcb">Physiotherapy</a></li>
	<li><a href="#tabs-4" style="background-color:#00cfcb">Physician assistant</a></li>
	<li><a href="#tabs-5" style="background-color:#00cfcb">Other Services</a></li>
  </ul>
  <div class="tab-content">
  <div id="tabs-1">
    <?php include "Healthcare_attendants.php";?>
  </div>
  <div id="tabs-2">
    <?php  include "Nurse_schedule_details.php";?>
  </div>
   <div id="tabs-3">
    <?php include "Physiotherapy_schedule_details.php";?>
  </div>
  <div id="tabs-4">
    <?php include "Physician_assistant_schedule.php";?>
  </div>
  <div id="tabs-5">
    <?php include "Other_sevices_schedule.php";?>
  </div>
  </div>
</div>
</div>             
</div>
</body>
</html>