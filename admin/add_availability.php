<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
      require_once '../classes/commonClass.php';
      $commonClass=new commonClass();
      $prID = $_REQUEST['prID'];
      $profID = '';
      if($prID)
      {
          $profID = base64_decode($prID);
      }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Add Professionals Availability</title>
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
                            <img src="images/add-schedule-big.png" alt="Add Professional Availability"> Add Professional Availability
                            <a class="btn btn-download pull-right font18" href="manage_professionals.php" data-original-title="" title="">VIEW PROFESSIONAL</a>
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                    <?php
                    if($profID)
                    {
                        $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where service_professional_id = '".$profID."' ";
                        $ptrval = $db->fetch_array($db->query($selectProfessional));
                        echo '<div class="col-lg-6 marginB20 paddingl0" ><span style="color:#00cfcb; font-size:18px;">Professional Name</span> : '.$ptrval['name'].' '.$ptrval['first_name'].' '.$ptrval['middle_name'].'</div>';
                    }
                    ?>
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="locationPrefListing">
                        <?php include "include_location_pref.php"; ?>
                    </div>
                    <div class="availabilityListing" style="margin-top:20px !important;">
                        <?php include "include_availability.php"; ?>
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
    <div class="modal fade" id="edit_professional"> 
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
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/jquery.classyscroll.js"></script>
    
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

<!--<link rel="stylesheet" href="../js/bootstrap-multiselect-master/dist/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="../js/bootstrap-multiselect-master/dist/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="../js/bootstrap-multiselect-master/dist/js/bootstrap-multiselect-collapsible-groups.js"></script>-->

<script type="text/javascript">
    $(document).ready(function() 
    {
        $("#mapContent").hide();
        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date(y, m + 1, 0);
        var firstDayPrevMonth = new Date(y,m-1,1);
        $('.datepicker_from').datepicker({ 
            changeMonth: true,
            changeYear: true, 
            dateFormat: 'dd-mm-yy',
            minDate:firstDayPrevMonth,
            maxDate:lastDay,
            onSelect: function(selected)
            {
            $(".datepicker_to").datepicker("option","minDate", selected);     
            },
            onClose: function() 
            { 
                this.focus();
            }
        });
    });

    function datepair()
    {
       $('.datepairExample_0 .time').timepicker({
            'showDuration': true,
            'timeFormat': 'h:i A'
        });
        $('.datepairExample_0').keypress(function(event) {event.preventDefault();});                      
        $('.datepairExample_0').datepair();
    }

    /**
     * Show map 
     */
    function showMap(locationId)
    {
        if (locationId) {
            var data1="locationId="+locationId;
            $.ajax({
              url: "professional_ajax_process.php?action=showMap&profID=<?php echo $profID;?>", type: "post", data: data1, cache: false,async: false,
              beforeSend: function() 
              {
                 Popup_Display_Load();
              },
              success: function (html)
              {
                  $("#mapContent").show();
                  var markers = html;
                  initMap(markers);
              },
              complete : function()
              {
                   Popup_Hide_Load();
              }
          })
        }
    }

    /**
     * Init map 
     */
    function initMap(markers) {
        var latlng = new google.maps.LatLng(18.5204,73.8567);
        var myOptions = {
            zoom: 11,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false
        };

        var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
        // add a markers reference
        map.markers = [];
        var polylineArr = [];
        var infowindow = new google.maps.InfoWindow(), marker, lat, lng;
        var json = JSON.parse(markers);
        for( var o in json ) {

            lat  = json[ o ].lattitude;
            lng  = json[ o ].longitude;
            name = json[ o ].name;
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat,lng),
                name:name,
                map: map
            });

            // add to array
            map.markers.push( marker );
            // create a string
            var elementData = {};
            elementData.lat = parseFloat(lat);
            elementData.lng = parseFloat(lng);
            polylineArr.push(elementData);

            google.maps.event.addListener( marker, 'click', function(e) {
                infowindow.setContent( this.name );
                infowindow.open( map, this );
            }.bind( marker ) );
        }

        //console.log("polylineArr",polylineArr);

        var flightPath = new google.maps.Polygon({
            path: polylineArr,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });

        //console.log("flightPath",flightPath);

        flightPath.setMap(map);

        //console.log("map.markers",map.markers);
    }

    /**
     * Remove location
     */
    function removeLocation(locationId)
    {
        prompt_msg = "Are you sure you want to delete this location ?";
        success_msg = "location preference";
        bootbox.confirm(prompt_msg, function (res) 
        {
            if (res == true)
            {
                var data1 = "profID=<?php echo $profID; ?>&locationId="+locationId+"&action=remove_location";
                //  alert(data1);
                $.ajax({
                    url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                        // alert(result);

                        if(result=='Success')
                        {
                            bootbox.alert("<div class='msg-success'>Professional "+success_msg+" removed successfully.</div>",function()
                            {
                                //Remove specfic row
                                location.reload();
                            });  
                        }
                        else {
                            bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                        }
                    },
                    complete : function()
                    {
                        Hide_Load();
                    }
                });
            }
        });
    }

    /**
     * view add availability
     */
    function vw_add_availability(profId) {
        if (profId) {
            var data1="service_professional_id="+profId+"&action=vw_add_availability";
            $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                    $('#edit_professional').modal({backdrop: 'static',keyboard: false});  
                    $("#AllAjaxData").html(html);  
                    datepair();
                    $("#checkAll").click(function() {
                        $('input:checkbox').not(this).prop('checked', this.checked);
                        if ($(this).is(':checked')) {
                            $("#checkAll").val('1');
                        } else {
                            $("#checkAll").val('0');
                        }
                        setDaysValue();
                    });

                    // remove checkAll checked property when unselect any other checkbox
                    $(".check_class").click(function() {
                        if (this.checked == false) {
                            $("#checkAll").prop('checked', false);
                        } else {
                            // get all seletected checkbox count
                            var count = 0;
                            $(".check_class").each(function()
                            {
                                if ($(this).is(':checked'))
                                {
                                    count++;
                                }
                            });

                            if (count == 7) {
                                $("#checkAll").prop('checked', true);
                                $("#checkAll").val('1');
                            } else {
                                $("#checkAll").prop('checked', false);
                                $("#checkAll").val('0');
                            }
                        }
                        setDaysValue();
                    });

                    setTimeout("$('.scrollbars').ClassyScroll();",100);

                },
                complete : function()
                {
                    Popup_Hide_Load();
                }
            });
        }
    }

    /**
     * add availability
     */
    function add_professional_availability_submit() {
        if ($("input:checked").length > 0 &&
            $('#starttime_0_0').val() != '' &&
            $('#endtime_0_0').val() != '' &&
            $("#location").val() != ''
            ) {
            
                $("#frm_add_availability").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result = html.trim();

                        if (result.indexOf('@#@') > -1) {
                            var resData = result.split("@#@");
                            if (resData[1]) {
                                bootbox.alert("<div class='msg-error'>" + resData[1] + "</div>");
                            }
                        } else if(result=='validationError') {
                            bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                        } else {
                            $('#edit_professional').modal('hide');
                            if(result=='InsertSuccess')
                            {
                                bootbox.alert("<div class='msg-success'>Professional availability added successfully.</div>",function()
                                {
                                    location.reload();
                                });
                            }
                            $('#submitForm').prop('disabled', false);
                        }
                    },
                    complete : function()
                    {
                       Hide_Load();
                    } 
                }).submit();
        }
        else 
        {
            $('#submitForm').prop('disabled', false);
            if ($("input:checked").length == 0) {
                bootbox.alert("<div class='msg-error'>Please select atleast one day.</div>");
                return false;
            } else if ($('#starttime_0_0').val() == '' || $('#endtime_0_0').val() == '') {
                bootbox.alert("<div class='msg-error'>Please select time.</div>");
                return false;
            } else if ($("#location").val() == '') {
                bootbox.alert("<div class='msg-error'>Please select location.</div>");
                return false;
            }
           
        }
    }

    /**
     * view edit availability
     */
    function vw_edit_availability(profId, avaibilityId) {
        if (profId && avaibilityId) {
            var data1="service_professional_id="+profId+"&professional_avaibility_id="+avaibilityId+"&action=vw_edit_availability";
            $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                    $('#edit_professional').modal({
                        backdrop: 'static',
                        keyboard: false,
                        height : 300
                    });  
                    $("#AllAjaxData").html(html);
                    datepair();
                },
                complete : function()
                {
                    Popup_Hide_Load();
                }
            });
        }
    }

    /**
     * edit professional availability
     */
    function edit_professional_availability_submit() {
        if ($('#starttime_0_0').val() != '' &&
            $('#endtime_0_0').val() != '' &&
            $("#location").val() != ''
            ) {
                $("#frm_edit_availability").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result = html.trim();
                        if(result=='validationError')
                        {
                            bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                        } else {
                            $('#edit_professional').modal('hide');
                            if(result=='UpdateSuccess')
                            {
                                bootbox.alert("<div class='msg-success'>Professional availability added successfully.</div>",function()
                                {
                                    location.reload();
                                });
                            }
                            $('#submitForm').prop('disabled', false);
                        }
                    },
                    complete : function()
                    {
                       Hide_Load();
                    } 
                }).submit();
        }
        else 
        {
            $('#submitForm').prop('disabled', false);
            if ($('#starttime_0_0').val() == '' || $('#endtime_0_0').val() == '') {
                bootbox.alert("<div class='msg-error'>Please select time.</div>");
                return false;
            } else if ($("#location").val() == '') {
                bootbox.alert("<div class='msg-error'>Please select location.</div>");
                return false;
            }
           
        }
    }

    /**
     * Add row
     */
    function addRow(number)
    {
        var i = parseInt(document.getElementById('extras_'+number).value);
        //alert(i);
        if(i==0)
        {
            i=1;
        }
        else
        {
            i= parseInt(i)+1;
        }
        document.getElementById('extras_'+number).value= i;
        var next = parseInt(i)+1;
        var curr_div = "div_"+i+"_"+number;
        //alert(curr_div);
        if(document.getElementById(curr_div).style.display === 'none')
        {
            document.getElementById(curr_div).style.display = 'block';
        }
        else
        {
            var data1="number="+number+"&curr_div="+i;
         // alert(data1);
          $.ajax({
              url: "professional_ajax_process.php?action=AddMoreAvailabilityRow&profID=<?php echo $profID; ?>", type: "post", data: data1, cache: false,async: false,
              beforeSend: function() 
              {
                 Popup_Display_Load();
              },
              success: function (html)
              {
                  //alert(html);
                  document.getElementById(curr_div).innerHTML = html;
                  datepair();
              },
              complete : function()
              {
                   Popup_Hide_Load();
              }
          });               
        }
    }

    /**
     * Remove row
     */
    function removeRow(number)
    {
        var j=document.getElementById('extras_'+number).value;
        if(j != 0)
        {
           Popup_Display_Load();
           var curr_div = "div_"+j+"_"+number;
           document.getElementById(curr_div).style.display='none';
           previouss= j;
           if(previouss==0)
           {
               previouss=0;
           }
           else
            {
                previouss= parseInt(j)-1;
            }
           document.getElementById('extras_'+number).value=previouss;
           Popup_Hide_Load();
        } 
    }

    /**
     *  remove availability
     */
    function remove_availability(profId, avaibilityId, recordId='') {
        prompt_msg = "Are you sure you want to delete this availability ?";
        success_msg = "availability";
        bootbox.confirm(prompt_msg, function (res) 
        {
            if (res == true)
            {
                var data1 = "profID=<?php echo $profID; ?>&professional_avaibility_id="+avaibilityId+"&professional_availability_detail="+recordId+"&action=remove_availability";
                //  alert(data1);
                $.ajax({
                    url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                        // alert(result);

                        if(result == 'Success')
                        {
                            bootbox.alert("<div class='msg-success'>Professional "+success_msg+" removed successfully.</div>",function()
                            {
                                //Remove specfic row
                                location.reload();
                            });  
                        }
                        else
                            bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                    },
                    complete : function()
                    {
                        Hide_Load();
                    }
                });
            }
        });
    }

    function setDaysValue() {
        var ids = [];
        $(".check_class").each(function()
        {
            if ($(this).is(':checked'))
            {
                ids.push(this.value); 
            }
        });

        console.log(ids);

        if (ids) {
            $("#selectedDays").val(ids);
        }
    }

	
    </script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC8lSxG4pg8hWyd52oqUQJKWnjQSe20dvc&libraries=places"></script>
    
</body>
</html>