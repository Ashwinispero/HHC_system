<?php 
    include "inc_classes.php";
    include "admin_authentication.php";
    require_once '../classes/professionalsClass.php';
    $professionalsClass = new professionalsClass();
    require_once '../classes/commonClass.php';
    $commonClass = new commonClass();
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Professional Dashboard</title>
        <?php include "include/css-includes.php";?> 
        <script language="javascript" type="text/javascript">
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php  include "include/header.php"; ?>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <!-- Page Heading -->
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header">
                                    <img src="images/icon-myprofile.png" alt="Professional Dashboard"> Professional Dashboard
                                </h1>                        
                            </div>
                        </div>
                    <!-- Page Heading -->

                    <!-- Search filter -->
                    <div class="col-lg-12 whiteBg">
                        <div class="col-lg-12 paddingLR20 paddingt20">
                            <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                                <div class="dd">
                                <?php
                                        $recArgs['pageIndex']     = '1';
                                        $recArgs['pageSize']      = 'all';
                                        $recArgs['service_Value'] =  $_REQUEST['service_id'];
                                        $recArgs['isActiveOnly'] =  1;
                                        $recListResponse          = $professionalsClass->ProfessionalsList($recArgs);
                                        $professionalList          = $recListResponse['data'];
                                    ?>
                                    <select class="chosen-select form-control" name="search_professional_id" id="search_professional_id" onChange="searchRecords();">
                                        <option value="">Search Professional</option>
                                        <?php
                                        if(!empty($professionalList)) {
                                            foreach ($professionalList as $key => $valProfessional) {
                                                if ($_POST['search_professional_id'] == $valProfessional['service_professional_id']) {
                                                    echo '<option value="'.$valProfessional['service_professional_id'].'" selected="selected">'.$valProfessional['first_name']." ".$valProfessional['name'].'</option>';
                                                } else {
                                                    echo '<option value="'.$valProfessional['service_professional_id'].'">'.$valProfessional['first_name']." ".$valProfessional['name'].'</option>';
                                                }
                                            } 
                                        } 
                                        ?>
                                    </select>     
                                </div>
                            </div>

                            <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                                <div class="dd">
                                    <?php
                                        $param['service_id'] =  $_REQUEST['service_id'];
                                        $subServiceList      = $commonClass->getAllSubServices($param);
                                    ?>
                                    <select class="chosen-select form-control" name="search_sub_service_id" id="search_sub_service_id" onChange="searchRecords();">
                                        <option value="">Search Sub Service</option>
                                        <?php
                                        if(!empty($subServiceList)) {
                                            foreach ($subServiceList AS $valSubService) {
                                                if ($_POST['search_sub_service_id'] == $valSubService['sub_service_id']) {
                                                    echo '<option value="' . $valSubService['sub_service_id'] . '" selected="selected">' . $valSubService['recommomded_service'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $valSubService['sub_service_id']. '">' . $valSubService['recommomded_service'] . '</option>';
                                                }
                                            } 
                                        } 
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Search filter ends here -->

                    <div class="professionalDashboardListing">
                        <?php include "include_professional_dashboard.php"; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php  include "include/scripts.php"; ?>
    <script src="js/bootbox.js"></script>
    <link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
    <script src="js/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
    <link rel="stylesheet" href="../dropdown/docsupport/prism.css">
    <link rel="stylesheet" href="../dropdown/chosen.css"> 
    <script src="../dropdown/chosen.jquery.js" type="text/javascript"></script>
    <script src="../dropdown/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            textboxes = $("input.data-entry-search");
            $(textboxes).keydown (checkForEnterSearch);
            var config = {
                '.chosen-select'           : {width:"99%"},
                '.chosen-select-deselect'  : {allow_single_deselect:true},
                '.chosen-select-no-single' : {disable_search_threshold:10},
                '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chosen-select-width'     : {width:"95%"}
            }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
        });

        function checkForEnterSearch (event) 
        {
            if (event.keyCode == 13) {
                searchRecords();
            }
        }

        function searchRecords() {
            changePagination('professionalDashboardListing','include_professional_dashboard.php?hospital_id=<?php echo $_REQUEST['hospital_id']; ?>&service_id=<?php echo $_REQUEST['service_id']; ?>&report=<?php echo $_REQUEST['report']; ?>','','','','');
        }
    </script>
</html>