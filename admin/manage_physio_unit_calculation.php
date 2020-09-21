<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
	   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Physiotherapy Unit Report</title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:400px}</style>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php  include "include/header.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <img src="images/system_user_big.png">Manage Physiotherapy Unit Report    
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Physiotherapy Unit"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="searchBox">
                            <input type="text" class="data-entry-search datepicker_from"  id="event_from_date" name="event_from_date" placeholder="From" onChange="searchRecords();">  
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="searchBox">
                            <input type="text" class="data-entry-search datepicker_to"  id="event_to_date" name="event_to_date" placeholder="To" onChange="searchRecords();"> 
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                    </div>
                    <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_physiotherapy_unit_calculation_report_list.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;" >
                            <img src="images/icon-download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="physiotherapyUnitCalculationReportListing">
                        <?php include "include_physiotherapy_unit_calculation_report.php"; ?>
                    </div>
                </div>   
              </div>
               <!-- Main Content End-->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <div class="modal fade" id="edit_physiotherapy_unit_calculation_report"> 
        <div class="modal-dialog" style="width:1024px !important;">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
   <?php  include "include/scripts.php"; ?>
    <link href="css/validationEngine.jquery.css" rel="stylesheet" />
    <script src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery.validationEngine-en.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootbox.js"></script>
	<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/jquery.classyscroll.js"></script>
    <link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
    <script src="js/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script>
	<script src="js/development-bundle/ui/jquery.timepicker.min.js"></script>
	<script src="js/development-bundle/ui/jquery.datepair.js"></script>
	
	<script src="js/jquery-timepicker-master/jquery.timepicker.js"></script>
	
    <script type="text/javascript">
        $(document).ready(function() 
        {
            textboxes = $("input.data-entry-search");
            $(textboxes).keydown (checkForEnterSearch);
			
			$.datepicker._generateHTML_Old = $.datepicker._generateHTML; $.datepicker._generateHTML = function(inst)
            {
               res = this._generateHTML_Old(inst); res = res.replace("_hideDatepicker()","_clearDate('#"+inst.id+"')"); return res;
            }

            // Start Date code start here
        $('.datepicker_from').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
            maxDate:new Date(),
            minDate: '-3M',      // minDate: -5,
            onSelect: function() 
            {
                var date1 = $('.datepicker_from').datepicker('getDate');           
                var date = new Date( Date.parse( date1 ) ); 
                date.setDate( date.getDate() + 1 );        
                var newDate = date.toDateString(); 
                newDate = new Date( Date.parse( newDate ) );                      
                $('.datepicker_to').datepicker("option","minDate",newDate);

                // check is it end date selected
                if ($('#event_to_date').val()) {
                    searchRecords();
                } else {
                    bootbox.alert("<div class='msg-error'>Please select end date</div>", function() {
                        $('#event_to_date').focus();
                    });
                }  
            }
        });
        // Start Date code end here

        // end date code start here
        $('.datepicker_to').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
            maxDate: new Date(),
            onSelect: function() 
            {
                searchRecords();
            }
        });
        // end date code ends here
        });
        function checkForEnterSearch (event) 
        {
            if (event.keyCode == 13) 
            {
                searchRecords();
            }
        }
        function searchRecords()
        {
            changePagination('physiotherapyUnitCalculationReportListing','include_physiotherapy_unit_calculation_report.php','','','','');
        }

        function view_physiotherapy_unit_calculation(profId, searchStartDate, searchEndDate)
        {
            if (profId) {
                var data1="service_professional_id="+profId+"&search_start_date="+searchStartDate+"&search_end_date="+searchEndDate+"&action=vw_physiotherapy_unit_calculation";
                $.ajax({
                    url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        $('#edit_physiotherapy_unit_calculation_report').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                        setTimeout("$('.scrollbars').ClassyScroll();",100); 
                    },
                    complete : function()
                    {
                        Popup_Hide_Load();
                    }
                });
            }
        }
    </script>
</body>
</html>