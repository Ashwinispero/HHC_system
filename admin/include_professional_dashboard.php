<?php require_once('inc_classes.php');
    require_once '../classes/dashboardClass.php';
    $dashboardClass = new dashboardClass();
if (!$_SESSION['admin_user_id']) {
    echo 'notLoggedIn';
} else {
    // Get professional wise revenue
    $arg['hospital_id'] = $_REQUEST['hospital_id'];
    $arg['service_id'] = $_REQUEST['service_id'];
    $arg['filter_name'] = 'dpc.Detailed_plan_of_care_id';
    $arg['filter_type'] = 'ASC';

    if (!empty($_REQUEST['report'])) {

        // Yesterday's date 
        $yesterdayDate = date('Y-m-d', strtotime("-1 days"));

        // Month's start date 
        $currMonthStartDate = date('Y-m-01');

        // Financial year start date
        $financialYearStartDate = date('Y-04-01');

        // User friendly date format
        $yesterdayDateUI = date('d M Y', strtotime($yesterdayDate));

        // User friendly current month date format
        $currMonthDateUI = "1-" . $yesterdayDateUI;

        // User friendly year date format
        $yearDateUI = date('d M ', strtotime($financialYearStartDate)) . "-" . $yesterdayDateUI;

        if ($_REQUEST['report'] == 'daily') {
            $reportTitle = "Report of " . $yesterdayDateUI;
            $arg['event_from_date'] = $yesterdayDate;
            $arg['event_to_date']   = $yesterdayDate;
        } else if ($_REQUEST['report'] == 'mothly') {
            $reportTitle = "Report from " . $currMonthDateUI;
            $arg['event_from_date'] = $currMonthStartDate;
            $arg['event_to_date']   = $yesterdayDate;
        } else if ($_REQUEST['report'] == 'yearly') {
            $reportTitle = "Report from " . $yearDateUI ;
            $arg['event_from_date'] = $financialYearStartDate;
            $arg['event_to_date']   = $yesterdayDate;
        }

    }

    $searchByProfessional = "";
    if ($_POST['SearchByProfessional'] && $_POST['SearchByProfessional'] != "undefined") {
        $searchByProfessional = $_POST['SearchByProfessional'];
    }

    $SearchBySubService = "";
    if ($_POST['SearchBySubServices'] && $_POST['SearchBySubServices']!="undefined") {
        $SearchBySubService = $_POST['SearchBySubServices'];
    }

    $arg['SearchByProfessional'] = $searchByProfessional;
    $arg['SearchBySubService'] = $SearchBySubService;

    $recList = $dashboardClass->getServiceWiseProfessionalRevenue($arg);
    ?>
    <!-- Content Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="well">
                <h4> <?php echo $recList[0]['service_title'] . " " . $reportTitle;  ?></h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Sr. No.</th>
                            <th scope="col">Professional Code</th>
                            <th scope="col">Professional Name</th>
                            <th scope="col">Service Name</th>
                            <th scope="col">Sub Service Name</th>
                            <th scope="col">Service Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recList)) {
                            $cnt = 1;
                            $totalRevenue = 0;
                            foreach ($recList AS $key => $valRec) {
                                echo "<tr>
                                    <td>" . $cnt . "</td>
                                    <td>" . $valRec['professional_code'] . "</td>
                                    <td>" . $valRec['professional_name'] . "</td>
                                    <td>" . $valRec['service_title'] . "</td>
                                    <td>" . $valRec['recommomded_service'] . "</td>
                                    <td>" . $valRec['cost'] . "</td>
                                </tr>";
                                $totalRevenue += $valRec['cost'];
                                $cnt++;
                            }
                        } ?>

                        <tr>
                            <td scope="col" colspan="5" style="text-align:right !important;">Revenue : </td>
                            <td><?php echo number_format($totalRevenue, 2); ?></td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Content Row -->
<?php } ?>