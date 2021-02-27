<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.css">


</head>
<?php
require_once 'classes/employeesClass.php';
$employeesClass=new employeesClass();
$arr['employee_id'] = $_SESSION['employee_id'];
$RequestedRec = $employeesClass->GetEmployeeById($arr);
$RequestedStatus = $employeesClass->get_emp_status($arr);
?>
<header style="background-color:#f6f6f6;">
  <nav class="navbar navbar-default" style="margin-left:1%;margin-right:1%;border: 1px solid #23131357;border-radius: 8px;margin-top:-1%">
    <div class="container-fluid">
      <div class="navbar-header">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a href="event-log.php" class="navbar-brand clearSessioncls"><img src="images/logo.png" alt="SPERO"></a> </div>
      <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav navbar-right">
       <li class="dropdown"><a href="javascript:void(0);" data-toggle="dropdown" class="dropdown-toggle" id="drop1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Profile</a>
            <ul aria-labelledby="drop1" role="menu" class="dropdown-menu my-profile" style="width:450px;">
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text">
                    <div class="col-lg-5"><span>Name:</span></div><?php if(!empty($RequestedRec['name'])) { echo $RequestedRec['name']; } ?></a>
                </li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5"><span>Email:</span></div><?php if(!empty($RequestedRec['email_id'])) { echo $RequestedRec['email_id']; } ?></a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5"><span>Mobile:</span></div><?php if(!empty($RequestedRec['mobile_no'])) { echo $RequestedRec['mobile_no']; } ?></a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5"><span>Landline:</span></div><?php if(!empty($RequestedRec['phone_no'])) { echo $RequestedRec['phone_no']; } ?></a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5"><span>Work Email:</span></div><?php if(!empty($RequestedRec['work_email_id'])) { echo $RequestedRec['work_email_id']; } ?></a></li>
                <li class="divider" role="presentation"></li>
                <li role="presentation"><a href="Javascript:void(0);" class="profile-text"><div class="col-lg-5"><span>Office Phone:</span></div><?php if(!empty($RequestedRec['work_phone_no'])) { echo $RequestedRec['work_phone_no']; } ?></a></li> 
            </ul>
          </li>
          <li><a href="javascript:void(0);" class="headerText"> Welcome <span class="user-name"><?php echo $RequestedRec['name'];?></span></a></li>
          <li><a href="ajax_public_process.php?action=logout" class="btn btn-logout">Logout</a></li>
        	
        </ul>
      </div>
    
  </nav>
 
</header>