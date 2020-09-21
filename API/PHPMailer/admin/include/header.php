<?php include '../classes/adminClass.php';      
      include "admin_authentication.php";      
      $adminClass = new adminClass();
      include '../classes/adminuserClass.php';   
      $adminuserClass=new adminuserClass();
      $adminDetails = $adminClass->selectAdmin($_SESSION['admin_user_id']);
      // Getting Permissionn details 
      $arr['admin_user_id']=$_SESSION['admin_user_id'];
      $PermissionDtls=$adminuserClass->GetUserPermissionsById($arr);
      $moduleids=array();
      foreach($PermissionDtls as $key=>$valmoduleids)
      {
          $moduleids[]=$valmoduleids['module_id'];
      }
     // print_r($moduleids);
?>
<script type="text/javascript" lanuage="javascript">  
</script>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="my-profile.php"><img src="images/logo.png"  alt="Logo"></a>
    </div>
    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span id="LeftAdminNm"><?php  if(!empty($adminDetails['name'])) { echo $adminDetails['name']." "; } if(!empty($adminDetails['first_name'])) { echo $adminDetails['first_name']." "; } if(!empty($adminDetails['middle_name'])) { echo $adminDetails['middle_name']; } else { echo ""; }  ?></span> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="my-profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="admin_ajax_process.php?action=logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                </li>
            </ul>
        </li>
    </ul>
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
   	
    <div class="collapse navbar-collapse navbar-ex1-collapse">
    
    <div class="hide-scrollbars">
    <div class="scrollbars1" id="menu-scollbar">
        <ul class="nav navbar-nav side-nav" id="">
            <?php if (in_array("1", $moduleids)) { ?>
            <li class="<?php if($page_name == 'my-profile.php') echo 'active'; ?>">
                <a href="my-profile.php"><i><img src="images/my-profile.png"></i> My Profile</a>
            </li>
            <?php } ?>
            <?php if (in_array("2", $moduleids)) { ?>
            <li  class="<?php if($page_name == 'manage_system_users.php') echo 'active'; ?>">
                <a href="manage_system_users.php"><i><img src="images/manage_system_users.png"></i> Manage System Users </a>
            </li>
            <?php } ?>
            <?php if (in_array("3", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_locations.php') echo 'active'; ?>">
                <a href="manage_locations.php"><i><img src="images/manage_locations.png"></i> Manage Locations </a>
            </li>
            <?php } ?>
            <?php if (in_array("4", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage-services.php') echo 'active'; ?>">
                <a href="manage-services.php"><i><img src="images/manage_services.png"></i> Manage Services </a>
            </li>
            <?php } ?>
             <?php if (in_array("14", $moduleids)) { ?>
                <li class="<?php if($page_name == 'manage_hospitals.php') echo 'active'; ?>">
                   <a href="manage_hospitals.php"><i><img src="images/manage_hospitals.png"></i> Manage Hospitals </a>
               </li>
            <?php } ?>
            <?php if (in_array("5", $moduleids)) { ?>
             <li class="<?php if($page_name == 'manage_employees.php') echo 'active'; ?>">
                <a href="manage_employees.php"><i><img src="images/manage_employees.png"></i> Manage Employees </a>
            </li>
            <?php } ?>
            <?php if (in_array("6", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_professionals.php') echo 'active'; ?>">
                <a href="manage_professionals.php"><i><img src="images/manage_professionals.png"></i> Manage Professionals </a>
            </li>
            <?php } ?>
            <?php if (in_array("13", $moduleids)) { ?>
            <li class="<?php if($page_name == 'add_scheduled.php' || $page_name == 'view_scheduled.php') echo 'active'; ?>">
                <a href="add_scheduled.php"><i><img src="images/add-schedule.png"></i> Add Scheduled </a>
            <ul id="demo" class="collapse <?php if($page_name == 'add_scheduled.php' || $page_name == 'view_scheduled.php') echo 'in'; ?>">
                    <li class="<?php if($page_name == 'add_scheduled.php') echo 'greentext'; ?>">
                        <a href="add_scheduled.php"> Add Scheduled</a>
                    </li>
                    <li class="<?php if($page_name == 'view_scheduled.php') echo 'greentext'; ?>">
                        <a href="view_scheduled.php">View / Edit Scheduled</a>
                    </li>
                    
                </ul>
            </li>
            <?php } ?>
            <?php if (in_array("7", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_consultants.php') echo 'active'; ?>">
                <a href="manage_consultants.php"><i><img src="images/manage_consultants.png"></i> Manage Consultants </a>
            </li>
             <?php } ?>
            <?php if (in_array("8", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_medicines.php') echo 'active'; ?>">
                <a href="manage_medicines.php"><i><img src="images/medicines.png"></i> Manage Medicines </a>
            </li>
            <?php } ?>
            <?php if (in_array("9", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_consumables.php') echo 'active'; ?>">
                <a href="manage_consumables.php"><i><img src="images/consumables.png"></i> Manage Consumables </a>
            </li>
             <?php } ?>
            <?php if (in_array("10", $moduleids)) { ?>
            <li class="<?php if($page_name=='manage_feedback.php') echo 'active'; ?>">
                <a href="manage_feedback.php"><i><img src="images/manage-feedback.png"></i> Manage Feedback </a>
            </li>
            <?php } ?>
            
            <?php if (in_array("11", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_knowledge_documents.php') echo 'active'; ?>">
                <a href="manage_knowledge_documents.php"><i><img src="images/manage_knowledge_documents.png"></i> Manage Knowledge Docs </a>
            </li>
             <?php } ?>
            
            <?php if (in_array("12", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_patients.php') echo 'active'; ?>">
                <a href="manage_patients.php"><i><img src="images/manage_patients.png"></i> Manage Patients </a>
            </li>
            <?php } ?>
            
            <?php if (in_array("15", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_events.php') echo 'active'; ?>">
                <a href="manage_events.php"><i><img src="images/manage_events.png"></i> Manage Events </a>
            </li>
            <?php } ?>
            
            <?php if (in_array("16", $moduleids)) { ?>
            <li class="<?php if($page_name == 'manage_physiotherapy_events.php') echo 'active'; ?>">
                <a href="manage_physiotherapy_events.php"><i><img src="images/manage_events.png"></i> Manage Physiotherapy Events </a>
            </li>
            <?php } ?>
            
            <?php //if (in_array("15", $moduleids)) { ?>
            <li class="<?php if($page_name == 'set_cookies.php') echo 'active'; ?>">
                <a href="set_cookies.php"><i><img src="images/manage_cookies.png"></i> Set Cookies </a>
            </li>
            <?php //} ?>
            
            <!--
            <li class="<?php // if($page_name == 'manage_specialty.php') echo 'active'; ?>">
                <a href="manage_specialty.php"><i><img src="images/manage_specialty.png"></i> Manage Specialty </a>
            </li>
            -->
        </ul>
        </div>
    </div>
    </div>
    <!-- /.navbar-collapse -->
</nav>