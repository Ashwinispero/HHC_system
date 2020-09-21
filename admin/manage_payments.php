<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
      require_once '../classes/commonClass.php';
      $commonClass=new commonClass(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Payments</title>
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
                            <img src="images/system_user_big.png" alt="Manage Payments"> Manage Payments 
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-3 paddingR0 inline_dp">
                        <div class="searchBox" >
                            <input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Payments"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onClick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
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

                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">                            
                            <select id="search_payment_type" name="search_payment_type" class="form-control" onChange="searchRecords();">
                                <option value="">Select Payment Type</option>
                                <option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="NEFT">NEFT</option>
                                <option value="Cheque">Cheque</option>
							</select>
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">                            
                            <select id="search_hospital_id" name="search_hospital_id" class="form-control" onChange="searchRecords();">
                                <option value="">Select Hospital</option>
                                <?php
                                  $hospitalList = $commonClass->GetAllHospitals();
                                  if(!empty($hospitalList))
                                  {
                                        foreach($hospitalList as $key => $valHospital)
                                        {
                                          if ($_POST['search_hospital_id'] == $valHospital['hospital_id']) {
                                              echo '<option value="' . $valHospital['hospital_id'] . '" selected="selected">' . $valHospital['hospital_name'] . '</option>';
                                          } else {
                                              echo '<option value="' . $valHospital['hospital_id'] . '">' . $valHospital['hospital_name'] . '</option>';
                                          }
                                        }
                                  }
                                  ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_payments_report_list.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;">
                            <img src="images/icon-download.png" class="example-fade" />                                
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="paymentsListing">
                        <?php include "include_payments.php";?>
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
    <div class="modal fade" id="edit_payment"> 
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
            changePagination('paymentsListing','include_payments.php','','','','');
        }
    </script>
</body>
</html>