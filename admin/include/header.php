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
<script src="js/jquery-11.1.js"></script>
<script type="text/javascript" lanuage="javascript">
$(document).ready(function()  {
    $('.navbar_child_li > ul').hide();
    $(".navbar_child_li").on("click", function (event) {
        event.stopPropagation();
        var selectedId = $(this).attr('id');

        // Get Active menu id
        var preActiveMenu = $("li").find(".active").parent().attr('id');
        if (preActiveMenu != undefined) {
            $("#"+ preActiveMenu).find('ul').removeClass("active");
            $("#"+ preActiveMenu).find('ul').slideToggle();
        }

        if (selectedId != preActiveMenu) {
            $("#"+ selectedId).find('ul').addClass("active");
            $("#"+ selectedId).find('ul').slideToggle();
        }
    });
}); 
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
                <ul class="nav navbar-nav side-nav navbar_master_ul">
                    <!-- My profile section start here -->
                    <?php if (in_array("1", $moduleids)) { ?>
                        <li class="<?php if($page_name == 'my-profile.php') echo 'active'; ?>">
                            <a href="my-profile.php"><i><img src="images/my-profile.png"></i> My Profile</a>
                        </li>
                    <?php } ?>
                    <!-- My profile section ends here -->
                    <!-- Manage Dashboard section start here -->
                    <?php if (in_array("42", $moduleids)) { ?>
                        <li class="<?php if ($page_name == 'dashboard.php') echo 'active'; ?>">
                            <a href="dashboard.php"><i><img src="images/my-profile.png"></i> Dashboard</a>
                        </li>
                    <?php } ?>
                    <!-- Manage Dashboard section ends here -->

                    <!-- HD section start here -->
                    <li class="navbar_child_li" id = "hd_master_menu">
                        <a href="javacript:void(0);">
                            <i><img src="images/my-profile.png"></i> HD
                        </a>
                        <ul>
                        <!-- manage session section start here -->
                        <?php if (in_array("40", $moduleids)) { ?>
                            <li class="<?php if($page_name == 'manage_sessions.php') echo 'active'; ?>">
                                <a href="manage_sessions.php"><i><img src="images/manage_professionals.png"></i> Manage Sessions </a>
                            </li>
                        <?php } ?>
                        <!-- manage session section ends here -->

                        <!-- manage reschedule session section start here -->
                        <?php if (in_array("37", $moduleids)) { ?>
                            <li class="<?php if($page_name == 'manage_reschedule_sessions.php') echo 'active'; ?>">
                                <a href="manage_reschedule_sessions.php"><i><img src="images/my-profile.png"></i> Manage Reschedule Session</a>
                            </li>
                        <?php } ?>
                        <!-- manage reschedule session section ends here -->

                        <!-- manage event section start here -->
                        <?php if (in_array("15", $moduleids)) { ?>
                            <li class="<?php if($page_name == 'manage_events.php') echo 'active'; ?>">
                                <a href="manage_events.php"><i><img src="images/manage_events.png"></i> Manage Events </a>
                            </li>
                        <?php } ?>
                        <!-- manage event section ends here -->

                        <!-- manage patient section start here -->
                        <?php if (in_array("12", $moduleids)) { ?>
                            <li class="<?php if($page_name == 'manage_patients.php') echo 'active'; ?>">
                                <a href="manage_patients.php"><i><img src="images/manage_patients.png"></i> Manage Patients </a>
                            </li>
                        <?php } ?>
                        <!-- manage patient section ends here -->

                        <!-- manage extend inquiry service section start here -->
                        <?php if (in_array("33", $moduleids)) { ?>
                            <li class="<?php if($page_name == 'Manage_Extend_service_Enquiry.php') echo 'active'; ?>">
                                <a href="Manage_Extend_service_Enquiry.php"><i><img src="images/manage_locations.png"></i>Extend Service Enquiry</a>
                            </li>
                        <?php } ?>
                        <!-- manage extend inquiry service service ends here -->
                        </ul>
                    </li>
                    <!-- HD section ends here -->
                    <li class="navbar_child_li" id = "hcm_master_menu">
                        <a href="javacript:void(0);">
                            <i><img src="images/my-profile.png"></i> HCM
                        </a>
                        <ul>
                             <!-- manage services section start here -->
                             <?php if (in_array("4", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage-services.php') echo 'active'; ?>">
                                    <a href="manage-services.php"><i><img src="images/manage_services.png"></i> Manage Services </a>
                                </li>
                            <?php } ?>
                            <!-- manage services section ends here -->

                            <!-- manage hospital section start here -->
                            <?php if (in_array("14", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_hospitals.php') echo 'active'; ?>">
                                    <a href="manage_hospitals.php"><i><img src="images/manage_hospitals.png"></i> Manage Hospitals </a>
                                </li>
                            <?php } ?>
                            <!-- manage hospital service ends here -->

                            <!-- manage professionals section start here -->
                            <?php if (in_array("6", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_professionals.php') echo 'active'; ?>">
                                    <a href="manage_professionals.php"><i><img src="images/manage_professionals.png"></i> Manage Professionals </a>
                                </li>
                            <?php } ?>
                            <!-- manage professionals section ends here -->

                            <!-- manage availability section start here -->
                            <?php if (in_array("41", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_professionals_availability.php') echo 'active'; ?>">
                                    <a href="manage_professionals_availability.php"><i><img src="images/manage_professionals.png"></i> Manage Availability </a>
                                </li>
                            <?php } ?>
                            <!-- manage availability section ends here -->

                            <!-- manage add scheduled section start here -->
                            <?php if (in_array("13", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'add_scheduled.php') echo 'greentext'; ?>">
                                    <a href="add_scheduled.php"> Add Scheduled</a>
                                </li>
                            <?php } ?>
                            <!-- manage add scheduled section ends here -->

                            <!-- manage view scheduled section start here -->
                            <?php if (in_array("13", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'view_scheduled.php') echo 'greentext'; ?>">
                                    <a href="view_scheduled.php">View / Edit Scheduled</a>
                                </li>
                            <?php } ?>
                            <!-- manage view scheduled service ends here -->

                            <!-- manage consultant  section start here -->
                            <?php if (in_array("7", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_consultants.php') echo 'active'; ?>">
                                    <a href="manage_consultants.php"><i><img src="images/manage_consultants.png"></i> Manage Consultants </a>
                                </li>
                            <?php } ?>
                            <!-- manage consultant section ends here -->

                            <!-- manage medicine section start here -->
                            <?php if (in_array("8", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_medicines.php') echo 'active'; ?>">
                                    <a href="manage_medicines.php"><i><img src="images/medicines.png"></i> Manage Medicines </a>
                                </li>
                            <?php } ?>
                            <!-- manage medicine section ends here -->

                            <!-- manage consumable section start here -->
                            <?php if (in_array("9", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_consumables.php') echo 'active'; ?>">
                                    <a href="manage_consumables.php"><i><img src="images/consumables.png"></i> Manage Consumables </a>
                                </li>
                            <?php } ?>
                            <!-- manage medicine section ends here -->

                            <!-- manage knowledge document section start here -->
                            <?php if (in_array("11", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_knowledge_documents.php') echo 'active'; ?>">
                                    <a href="manage_knowledge_documents.php"><i><img src="images/manage_knowledge_documents.png"></i> Manage Knowledge Docs </a>
                                </li>
                            <?php } ?>
                            <!-- manage knowledge document section ends here -->

                            <!-- manage feedback section start here -->
                            <?php if (in_array("10", $moduleids)) { ?>
                                <li class="<?php if($page_name=='manage_feedback.php') echo 'active'; ?>">
                                    <a href="manage_feedback.php"><i><img src="images/manage-feedback.png"></i> Manage Feedback </a>
                                </li>
                            <?php } ?>
                            <!-- manage feedback section ends here -->

                            <!-- manage app notes section start here -->
                            <?php if (in_array("34", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'Manage_Mobile_App_Notes.php') echo 'active'; ?>">
                                    <a href="Manage_Mobile_App_Notes.php"><i><img src="images/manage_locations.png"></i>Mobile App Notes</a>
                                </li>
                            <?php } ?>
                            <!-- manage app notes section ends here -->
                        </ul>
                    </li>

                    <!-- HR section start here -->
                    <li class="navbar_child_li" id = "hr_master_menu">
                        <a href="javacript:void(0);">
                            <i><img src="images/my-profile.png"></i> HR
                        </a>
                        <ul>
                            <!-- manage system users section start here -->
                            <?php if (in_array("2", $moduleids)) { ?>
                                <li  class="<?php if($page_name == 'manage_system_users.php') echo 'active'; ?>">
                                    <a href="manage_system_users.php"><i><img src="images/manage_system_users.png"></i> Manage System Users </a>
                                </li>
                            <?php } ?>
                            <!-- manage system users section ends here -->

                            <!-- manage employee section start here -->
                            <?php if (in_array("5", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_employees.php') echo 'active'; ?>">
                                    <a href="manage_employees.php"><i><img src="images/manage_employees.png"></i> Manage Employees </a>
                                </li>
                            <?php } ?>
                            <!-- manage employee section ends here -->

                            <!-- manage document approval section start here -->
                            <?php if (in_array("28", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'Manage_professional_document.php') echo 'active'; ?>">
                                    <a href="Manage_professional_document.php"><i><img src="images/manage_locations.png"></i> Document Approval </a>
                                </li>
                            <?php } ?>
                            <!-- manage document approval section ends here -->

                            <!-- manage leave approval section start here -->
                            <?php if (in_array("29", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'Manage_professional_Leaves.php') echo 'active'; ?>">
                                    <a href="Manage_professional_Leaves.php"><i><img src="images/manage_locations.png"></i> Leave Approval </a>
                                </li>
                            <?php } ?>
                            <!-- manage leave approval section ends here -->

                            <!-- manage professional weekoff section start here -->
                            <?php if (in_array("22", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'add_professional_weekoff.php') echo 'active'; ?>">
                                    <a href="add_professional_weekoff.php"><i><img src="images/add-schedule.png"></i>Add Professional Week OFF </a>
                                </li> 
                            <?php } ?>
                            <!-- manage professional weekoff section ends here -->
                        </ul>
                    </li>
                    <!-- HR section ends here -->

                    <!-- Account section start here -->
                    <li class="navbar_child_li" id="account_master_menu">
                        <a href="javacript:void(0);">
                            <i><img src="images/my-profile.png"></i> Account
                        </a>
                        <ul>
                            <!-- manage new export receipt section start here -->
                            <?php if (in_array("21", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'New_export_receipt.php') echo 'active'; ?>">
                                    <a href="New_export_receipt.php"><i><img src="images/manage_locations.png"></i>New Export Receipt </a>
                                </li> 
                            <?php } ?>
                            <!-- manage new export receipt section ends here -->

                            <!-- manage export receipt section start here -->
                            <?php if (in_array("18", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'export_receipt.php') echo 'active'; ?>">
                                    <a href="export_receipt.php"><i><img src="images/manage_locations.png"></i> Export Receipt </a>
                                </li> 
                            <?php } ?>
                            <!-- manage export receipt section ends here -->

                            <!-- manage export invoice section start here -->
                            <?php if (in_array("19", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'export_invoice.php') echo 'active'; ?>">
                                    <a href="export_invoice.php"><i><img src="images/manage_locations.png"></i> Export Invoice </a>
                                </li> 
                            <?php } ?>
                            <!-- manage export invoice section ends here -->

                            <!-- manage payments section start here -->
                            <?php if (in_array("17", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_payments.php') echo 'active'; ?>">
                                    <a href="manage_payments.php"><i><img src="images/manage_locations.png"></i> Manage Payments </a>
                                </li> 
                            <?php } ?>
                            <!-- manage payments section start here -->

                            <!-- manage day print section start here -->
                            <?php if (in_array("20", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_day_print.php') echo 'active'; ?>">
                                    <a href="manage_day_print.php"><i><img src="images/manage_locations.png"></i> Day Print </a>
                                </li> 
                            <?php } ?>
                            <!-- manage day print section ends here -->

                            <!-- manage paytm payment section start here -->
                            <?php if (in_array("36", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'Manage_paytmpayment.php') echo 'active'; ?>">
                                    <a href="Manage_paytmpayment.php"><i><img src="images/manage_locations.png"></i> Paytm Payment </a>
                                </li>
                            <?php } ?>
                            <!-- manage paytm payment section ends here -->

                            <!-- manage payment with professional section start here -->
                            <?php if (in_array("32", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'Manage_payment_with_professional.php') echo 'active'; ?>">
                                    <a href="Manage_payment_with_professional.php"><i><img src="images/manage_locations.png"></i>Payment With Professional</a>
                                </li>
                            <?php } ?>
                            <!-- manage payment with professional section ends here -->

                            <!-- manage physio unit calculation section start here -->
                            <?php if (in_array("26", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_physio_unit_calculation.php') echo 'active'; ?>">
                                    <a href="manage_physio_unit_calculation.php"><i><img src="images/add-schedule.png"></i>Physio Unit Calculation</a>
                                </li> 
                            <?php } ?>
                            <!-- manage physio unit calculation section ends here -->

                            <!-- manage job closure report section start here -->
                            <?php if (in_array("23", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_job_closure_report.php') echo 'active'; ?>">
                                    <a href="manage_job_closure_report.php"><i><img src="images/add-schedule.png"></i>Job Closure Report</a>
                                </li> 
                            <?php } ?>
                            <!-- manage job closure report section ends here -->

                            <!-- manage day print BHV section start here -->
                            <?php if (in_array("27", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_day_print_BHV.php') echo 'active'; ?>">
                                    <a href="manage_day_print_BHV.php"><i><img src="images/manage_locations.png"></i> Day Print - BHV</a>
                                </li>
                            <?php } ?>
                            <!-- manage day print BHV section ends here -->
                        </ul>
                    </li>
                    <!-- Account section ends here -->
                    <!-- Revoltel Report section start here -->
                    <li class="navbar_child_li" id="report_master_menu">
                        <a href="javacript:void(0);">
                            <i><img src="images/my-profile.png"></i> Revoltel Report
                        </a>
                        <ul>
                            <!-- manage missed call report section start here -->
                            <?php if (in_array("43", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_missed_Call_report.php') echo 'active'; ?>">
                                    <a href="manage_missed_Call_report.php"><i><img src="images/manage_locations.png"></i>Missed Call Report</a>
                                </li> 
                            <?php } ?>
                            <!-- manage missed call report section end here -->
                            <!-- manage adio report section start here -->
                            <?php if (in_array("44", $moduleids)) { ?>
                                <li class="<?php if($page_name == 'manage_adiocall_report.php') echo 'active'; ?>">
                                    <a href="manage_adiocall_report.php"><i><img src="images/manage_locations.png"></i>Audio File Report</a>
                                </li> 
                            <?php } ?>
                            <!-- manage adio call report section start here -->
                        </ul>
                    </li>
                    <!-- Revoltel Report section ends here -->

                    <!-- Set cookies section start here -->
                    <?php if (in_array("15", $moduleids)) { ?>
                        <li class="<?php if($page_name == 'set_cookies.php') echo 'active'; ?>">
                            <a href="set_cookies.php"><i><img src="images/manage_cookies.png"></i> Set Cookies</a>
                        </li>
                    <?php } ?>
                    <!-- My cookies section ends here -->
                </ul>
            </div>
        </div>
    </div>
    <!-- /.navbar-collapse -->
</nav>