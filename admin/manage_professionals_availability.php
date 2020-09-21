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
    <title>Manage Professionals </title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:400px}
    
/* pac container class is for google locaion display on modal.. do not change it  */
.pac-container {
    z-index: 1051 !important;
}
.ui-autocomplete {
    z-index: 1051 !important;
}
    </style>
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
                            <img src="images/professionals_big.png" alt="Manage Professionals Availability"> Manage Professionals Availability                                               
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-2 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="dp_country" name="prof_service_id" id="prof_service_id" onchange="getSubServices(this.value);">
                                <option value="">Search By Services</option>
                                <?php
                                    // Getting All Locations
                                    $ServiceList=$commonClass->GetAllServices();  
                                    foreach($ServiceList as $recListKey => $servicesAll)
                                    {
                                        if($_REQUEST['prof_service_id']==$servicesAll['service_id'])
                                            echo '<option value="'.$servicesAll['service_id'].'" selected="selected">'.$servicesAll['service_title'].'</option>';
                                        else
                                            echo '<option value="'.$servicesAll['service_id'].'">'.$servicesAll['service_title'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
					
					<!-- Get Sub Services based on selected service id -->
					<div id="subServiceDivContent">
					</div>
					<!-- Get Sub Services based on selected service id -->

					<div class="col-lg-2 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="dp_country" name="location_id" id="location_id" onchange="searchRecords();">
                                <option value="">Search By Location</option>
                                <?php
                                    // Getting All Locations
                                    $recList=$commonClass->GetAllLocations($arr);
                                    foreach($recList as $recListKey => $valLocations)
                                    {
                                        if ($_REQUEST['location_id'] == $valLocations['location_id']) {
                                            echo '<option value="'.$valLocations['location_id'].'" selected="selected">'.$valLocations['location'].'</option>';
										}
                                        else {
                                            echo '<option value="'.$valLocations['location_id'].'">'.$valLocations['location'].'</option>';
										}
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
					
					<div class="col-lg-5 marginB20 paddingl0">
                        <div class="searchBox" >
							<input type="text" name="google_home_location" id="google_home_location" class="data-entry-search" placeholder="Search Location" />
							<a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>

					<div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Professional"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="professionalsAvailabilityListing">
                        <?php include "include_professionals_availability.php";?>
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
    <div class="modal fade" id="edit_professional_availability"> 
        <div class="modal-dialog">
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
    <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
    <link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
    <script src="js/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <!-- ------------- Timepicker ------------ -->   
    <script type="text/javascript" src="../js/jquery-timepicker-master/jquery.timepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="../js/jquery-timepicker-master/jquery.timepicker.css" />
    <script type="text/javascript" src="../js/jquery-timepicker-master/datepair.js"></script>
    <script type="text/javascript" src="../js/jquery-timepicker-master/jquery.datepair.js"></script>
    
    
<script type="text/javascript">
    $(document).ready(function() 
    {
        textboxes = $("input.data-entry-search");
        $(textboxes).keydown (checkForEnterSearch);
        $.datepicker._generateHTML_Old = $.datepicker._generateHTML; $.datepicker._generateHTML = function(inst)
        {
           res = this._generateHTML_Old(inst); res = res.replace("_hideDatepicker()","_clearDate('#"+inst.id+"')"); return res;
        }
		
		$location_input = $("#google_work_location");
		var options = {
			//types: ['(postal_town)'],
			componentRestrictions: {country: 'in'}
		};
		autocomplete = new google.maps.places.Autocomplete($location_input.get(0), options);    
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var data = $("#google_work_location").val();
			console.log('blah');
		  //  show_submit_data(data);
			return false;
		});
		
		$location_input_home = $("#google_home_location");
		var options = {
			//types: ['(postal_town)'],
			componentRestrictions: {country: 'in'}
		};
		autocomplete_home = new google.maps.places.Autocomplete($location_input_home.get(0), options);    
		google.maps.event.addListener(autocomplete_home, 'place_changed', function() {
			var datas = $("#google_home_location").val();
			console.log('blah');
		  //  show_submit_data(data);
			return false;
		});
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
        changePagination('professionalsAvailabilityListing','include_professionals_availability.php','','','','');
    }

    function checkworklocation()
    {
        var addressField = document.getElementById('google_work_location');
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode(
        {'address': addressField.value}, 
        function(results, status) { 
            if (status == google.maps.GeocoderStatus.OK) 
            {
                var loc = results[0].geometry.location;
                console.log(addressField.value+" found on Google");
                submitPorfForm();
                //var datas = valid_google_location('yes');
            } else {
                console.log(addressField.value+" not found on Google");
                alert('Please select valid work location.');
                //var datas = valid_google_location('no');
                var found = '2';
                return false;
            } 
        }
        );
    }

    function view_professional(service_professional_id)
    {
        var data1="service_professional_id="+service_professional_id+"&action=vw_professional";
        //alert(data1);
         $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                   // alert(html);
                    $('#edit_professional_availability').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
         }); 
    }
	
	function view_professional_availability(serviceProfessionalId, locationVal) {
		var data1="service_professional_id="+serviceProfessionalId+"&location_value="+locationVal+"&action=vw_professional_availability";
        //alert(data1);
         $.ajax({
                url: "professional_availability_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                   // alert(html);
                    $('#edit_professional_availability').modal({backdrop: 'static',keyboard: false}); 
					$('#edit_professional_availability').find('.modal-dialog').css({
						width:'auto',
                        height:'auto', 
                       'max-width':'60%'
					});
                    $("#AllAjaxData").html(html);
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
         });
	}
	
	// This function is used for get sub services based on selected service id
	function getSubServices(serviceId)
    {
		if (serviceId) {
			var data1 = "service_id=" + serviceId + "&action=getSubServices";
			 $.ajax({
					url        : "professional_availability_ajax_process.php",
					type       : "post",
					data       : data1, 
					cache      : false,
					async      : false,
					success: function (html) {
					   $("#subServiceDivContent").css('display','block');
					   $("#subServiceDivContent").empty().html(html);
					   $('#sub_service_id').multiselect({
							enableCaseInsensitiveFiltering: true,
							enableFiltering: true,
							nonSelectedText: 'Select Sub Service',
							maxHeight: 250,
							buttonWidth:'auto!important',
							includeSelectAllOption: true
					  });
					  $(".multiselect-search").keydown(function(event) 
					  {
						if (event.keyCode == 13) 
						{
							return false;
						}
					  });
				   }
			 });
			 searchRecords();
		} else {
			$("#subServiceDivContent").css('display','block');
			$("#subServiceDivContent").empty();
		}
    }
</script>

<script>
    function show_submit_data(data) {
        $("#selcGog_Location").val(data);
    }    
</script>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&sensor=true"></script>-->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&libraries=places"></script>

</body>
</html>