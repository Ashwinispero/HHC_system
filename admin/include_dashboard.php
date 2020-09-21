<?php require_once('inc_classes.php');
    require_once '../classes/dashboardClass.php';
    $dashboardClass = new dashboardClass();
    require_once '../classes/commonClass.php';
    $commonClass = new commonClass();
if (!$_SESSION['admin_user_id']) {
    echo 'notLoggedIn';
} else {

    // Get all active services
    $serviceList = $commonClass->GetAllServices();

    // Get all active hospitals
    $hospitalList = $commonClass->GetAllHospitals();

    // Yesterday's date 
    $yesterdayDate = date('Y-m-d', strtotime("-1 days"));

    // Month's start date 
    $currMonthStartDate = date('Y-m-01');

    // Financial year start date
    $financialYearStartDate = date('Y-04-01');

    // Get todays total calls
    if ($_POST['searchByHospital'] && $_POST['searchByHospital'] != "undefined" && $_POST['searchByHospital'] != "null") {
        $arg['hospital_id'] = $_POST['searchByHospital']; 
    } else {
        $arg['hospital_id'] = $_POST['searchByHospital'] = 2; // Deenanath mangeshkar hospital
    }

    $arg['filter_name']     = 'e.event_id';
    $arg['filter_type']     = 'ASC';
    $arg['event_from_date'] = $yesterdayDate;
    $arg['event_to_date']   = $yesterdayDate;

    $todaysCalls = $dashboardClass->getEventReport($arg);

    // Get todays total enquiry count
    $todaysEnquiry = $dashboardClass->getEnquiryReport($arg);

    // Get todays service wise calls
    if (!empty($serviceList)) {
        $resultArr = array();
        foreach ($serviceList AS $key => $valService) {
            $arg['service_id'] = $valService['service_id'];
            $resultArr[$valService['service_id']] = $dashboardClass->getServiceWiseEventReport($arg);
            unset($arg['service_id']);
        }
        $yesterdaysServiceWiseCalls = $resultArr;
        unset($resultArr);
    }
    
    unset($arg['event_from_date'], $arg['event_to_date']);

    // Get monthly total calls
    $arg['event_from_date'] = $currMonthStartDate;
    $arg['event_to_date']   = $yesterdayDate;
    $monthlyCalls = $dashboardClass->getEventReport($arg);

    // Get monthly total enquiry count
    $monthlyEnquiry = $dashboardClass->getEnquiryReport($arg);

    // Get monthly service wise calls
    if (!empty($serviceList)) {
        $resultantArr = array();
        foreach ($serviceList AS $key => $valService) {
            $arg['service_id'] = $valService['service_id'];
            $resultantArr[$valService['service_id']] = $dashboardClass->getServiceWiseEventReport($arg);
            unset($arg['service_id']);
        }
        $monthlyServiceWiseCalls = $resultantArr;
        unset($resultantArr);
    }

    // Get monthly total enquiry converted into service
    $arg['is_converted_service'] = '2';
    $monthlyEnquiryConvertedIntoService = $dashboardClass->getEnquiryReport($arg);
    unset($arg['is_converted_service']);

    // Get yearly service wise calls
    if (!empty($serviceList)) {
    $arg['event_from_date'] = $financialYearStartDate;
    $resultantArr = array();
    foreach ($serviceList AS $key => $valService) {
        $arg['service_id'] = $valService['service_id'];
        $resultantArr[$valService['service_id']] = $dashboardClass->getServiceWiseEventReport($arg);
        unset($arg['service_id']);
    }

    $yearlyServiceWiseCalls = $resultantArr;
    unset($resultantArr);
    }

    unset($arg['event_from_date'], $arg['event_to_date']);

    unset($arg['filter_name'], $arg['filter_type']);

    // User friendly date format
    $yesterdayDateUI = date('d M Y', strtotime($yesterdayDate));

    // User friendly current month date format
    $currMonthDateUI = "01-" . $yesterdayDateUI;

    // User friendly year date format
    $yearDateUI = date('d M ', strtotime($financialYearStartDate)) . "-" . $yesterdayDateUI;

    ?>
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-10">
                <h1 class="page-header">
                    <img src="images/icon-myprofile.png" alt="Dashboard"> Dashboard
                </h1>                        
            </div>
            <div class="col-lg-2 paddingR0 inline_dp pull-right dropdown" style="margin-top: -68px;">
                <div class="dd">
                    <select class="dp_country" name="search_hospital_id" id="search_hospital_id" onchange="searchRecords();">
                        <option value="">Search By Hospital</option>
                        <?php
                            if (!empty($hospitalList)) {
                                foreach ($hospitalList as $recListKey => $valHospital) {
                                    if ($_POST['searchByHospital'] == $valHospital['hospital_id']) {
                                        echo '<option value="' . $valHospital['hospital_id' ] . '" selected="selected">' . $valHospital['hospital_name'] . '</option>';
                                    } else {
                                        echo '<option value="' . $valHospital['hospital_id'] . '">' . $valHospital['hospital_name'] . '</option>';
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <!-- Page Heading -->
        <!-- Content Row -->
        <div class="row">
            <div class="col-sm-3">
                <div class="well">
                    <h4>Enquiries of <?php echo $yesterdayDateUI; ?></h4>
                    <p class="dashboard-content"><?php echo $todaysEnquiry['totalRecords']; ?></p> 
                </div>
            </div>
            <div class="col-sm-3">
                <div class="well">
                    <h4>Enquiries of <?php echo $currMonthDateUI; ?></h4>
                    <p class="dashboard-content"><?php echo $monthlyEnquiry['totalRecords']; ?></p> 
                </div>
            </div>
            <div class="col-sm-3">
                <div class="well">
                    <h4>Enquiries converted into service from <?php echo $currMonthDateUI; ?></h4>
                    <p class="dashboard-content"><?php echo $monthlyEnquiryConvertedIntoService['totalRecords']; ?></p> 
                </div>
            </div>
            <div class="col-sm-3">
                <div class="well">
                    <h4>Calls of <?php echo $yesterdayDateUI; ?></h4>
                    <p class="dashboard-content"><?php echo $todaysCalls['totalRecords']; ?></p> 
                </div>
            </div>
        </div>
        <!-- Content Row -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-sm-3">
                <div class="well">
                    <h4>calls from <?php echo $currMonthDateUI; ?></h4>
                    <p class="dashboard-content"><?php echo $monthlyCalls['totalRecords']; ?></p> 
                </div>
            </div>
        </div>
        <!-- Content Row -->

        <!-- Content Row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="well">
                        <h4>Service wise calls</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Sr. No.</th>
                                    <th scope="col">Services</th>
                                    <th scope="col">Calls of <?php echo $yesterdayDateUI; ?></th>
                                    <th scope="col">Revenue</th>

                                    <th scope="col" style="text-align:center;">Calls from <?php echo "<br/>" . $currMonthDateUI; ?></th>
                                    <th scope="col">Revenue</th>
                                    <th scope="col" style="text-align:center;">Calls from <?php echo "<br/>" . $yearDateUI; ?></th>
                                    <th scope="col">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($serviceList)) {
                                    $cnt = 1;
                                    $yesterdaysServiceCnt = 0;
                                    $yesterdaysRevenues   = 0;
                                    $monthlyServiceCnt = 0;
                                    $monthlyRevenues   = 0;
                                    $yearlyServiceCnt = 0;
                                    $yearlyRevenues   = 0;
                                    foreach ($serviceList AS $key => $valService) {
                                        echo "<tr>
                                            <td> " . $cnt . "</td>
                                            <td> " . $valService['service_title']. "</td>";
                                            if (!empty($yesterdaysServiceWiseCalls)) {
                                                foreach ($yesterdaysServiceWiseCalls AS $Key => $valYesterdaysServiceWiseCall) {
                                                    if ($valService['service_id'] == $Key) {
                                                        echo "<td><a href='professional_dashboard.php?hospital_id=" . $_POST['searchByHospital'] . "&service_id=" . $valService['service_id'] . "&report=daily' target='_blank'>" . (!empty($valYesterdaysServiceWiseCall['totalCount']) ? $valYesterdaysServiceWiseCall['totalCount'] : 0) . "</a></td>";
                                                        echo "<td>" . (!empty($valYesterdaysServiceWiseCall['totalRevenue']) ? $valYesterdaysServiceWiseCall['totalRevenue'] : 0) . "</td>";
                                                    
                                                        $yesterdaysServiceCnt += $valYesterdaysServiceWiseCall['totalCount'];
                                                        $yesterdaysRevenues += $valYesterdaysServiceWiseCall['totalRevenue'];
                                                    }
                                                }
                                            }

                                            if (!empty($monthlyServiceWiseCalls)) {
                                                foreach ($monthlyServiceWiseCalls AS $key => $valMonthlyServiceWiseCalls) {
                                                    if ($valService['service_id'] == $key) {
                                                        echo "<td><a href='professional_dashboard.php?hospital_id=" . $_POST['searchByHospital'] . "&service_id=" . $valService['service_id'] . "&report=mothly' target='_blank'>" . (!empty($valMonthlyServiceWiseCalls['totalCount']) ? $valMonthlyServiceWiseCalls['totalCount'] : 0) . "</a></td>";
                                                        echo "<td>" . (!empty($valMonthlyServiceWiseCalls['totalRevenue']) ? $valMonthlyServiceWiseCalls['totalRevenue'] : 0) . "</td>";
                                                        $monthlyServiceCnt += $valMonthlyServiceWiseCalls['totalCount'];
                                                        $monthlyRevenues += $valMonthlyServiceWiseCalls['totalRevenue'];
                                                    }
                                                }
                                            }

                                            if (!empty($yearlyServiceWiseCalls)) {
                                                foreach ($yearlyServiceWiseCalls AS $key => $valYearlyServiceWiseCalls) {
                                                    if ($valService['service_id'] == $key) {
                                                        echo "<td><a href='professional_dashboard.php?hospital_id=" . $_POST['searchByHospital'] . "&service_id=" . $valService['service_id'] . "&report=yearly' target='_blank'>" . (!empty($valYearlyServiceWiseCalls['totalCount']) ? $valYearlyServiceWiseCalls['totalCount'] : 0) . "</a></td>";
                                                        echo "<td>" . (!empty($valYearlyServiceWiseCalls['totalRevenue']) ? $valYearlyServiceWiseCalls['totalRevenue'] : 0) . "</td>";
                                                        $yearlyServiceCnt += $valYearlyServiceWiseCalls['totalCount'];
                                                        $yearlyRevenues += $valYearlyServiceWiseCalls['totalRevenue'];
                                                    }
                                                }
                                            }
                                        echo "</tr>";
                                        $cnt++;
                                    }

                                }?>
                                <tr style="font-weight:bold;">
                                    <td colspan="2">
                                    </td>
                                    <td>
                                        <?php echo $yesterdaysServiceCnt; ?>
                                    </td>
                                    <td>
                                        <?php echo $yesterdaysRevenues; ?>
                                    </td>
                                    <td>
                                        <?php echo $monthlyServiceCnt; ?>
                                    </td>
                                    <td>
                                        <?php echo $monthlyRevenues; ?>
                                    </td>
                                    <td>
                                        <?php echo $yearlyServiceCnt; ?>
                                    </td>
                                    <td>
                                        <?php echo $yearlyRevenues; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <!-- Content Row -->

    <?php
}
?>