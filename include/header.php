<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<?php
require_once 'classes/employeesClass.php';
$employeesClass=new employeesClass();
$arr['employee_id'] = $_SESSION['employee_id'];
$RequestedRec = $employeesClass->GetEmployeeById($arr);
?>
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a href="event-log.php" class="navbar-brand clearSessioncls"><img src="images/logo.png" alt="SPERO"></a> </div>
      <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav navbar-right">
       <!-- <li> 
        <button type="button" id="soft" class="btn-lg btn-info"  onclick="return softdial();"><span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span> soft call</button>
        
        <a href="javascript:void(0);" data-placement="top" title="View Dialer Box" onclick="softdial()"; data-toggle="tooltip" class="clearSessioncls"> <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span> Soft Dial</a>
        </li>-->
        <li>
        <a href="javascript:void(0);"  title="View Dialer Box" onclick="softdial()"; > <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span> Soft Dial</a>
        </li>
        
            <li><a href="event-log.php" class="clearSessioncls"> <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span> Attend Call</a></li>
        <?php if($_SESSION['employee_type']=='1') { ?>
            <li><a href="requirement-assessment.php"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Requirement Assessment</a></li>
        <?php } ?>
        <li><a href="enquiry-follow-up.php"> <span class="glyphicon glyphicon-list" aria-hidden="true"></span> Follow up </a></li>
          <li><a href="knowledge-base.php"> <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Knowledge Base</a></li>
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
      <!--/.nav-collapse --> 
      <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav navbar-right">
        <button type="button" id="hang_mode" class="btn-lg btn-danger" style="display:none" onclick="return hang_mode();"> Hang Up Mode</button>
        <button type="button" id="ready_mode" class="btn-lg btn-warning"  onclick="return ready_mode();"><span class="glyphicon glyphicon-pause" aria-hidden="true"></span> Pause Mode</button>
        <button type="button" id="pause_mode" class="btn-lg btn-success" style="display:none" onclick="return pause_mode();"><span class="glyphicon glyphicon-play" aria-hidden="true"></span> Ready Mode</button>
        <button type="button" id="conf_mode" class="btn-lg btn-info" style="display:none"  onclick="return conf_mode();"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span> Conferance call</button>
        </ul>
      </div>
    </div>
    <!--/.container-fluid --> 
  </nav>
</header>