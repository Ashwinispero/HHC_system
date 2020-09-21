<?php require_once('inc_classes.php');
require_once '../classes/professionalsClass.php';
$professionalsClass = new professionalsClass();

$prId = $_REQUEST['prID'];
$profId = '';
if ($prId) {
    $profId = base64_decode($prId);
}
?>
<!DOCTYPE html >
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Add Location Preference</title>
        <?php include "include/css-includes.php"; ?>
        <style>
        /* Always set the map height explicitly to define the size of the div
        * element that contains the map. */
        #map {
            height: 100%;
        }
        </style>
    </head>
    <body>
        <div class="row">
            <div class="col-lg-7" style ="margin-bottom : 10px;">
                <h1 class="page-header">
                    <a class="btn btn-download pull-right font18" href="add_availability.php?prID=<?php echo $prId; ?>" data-original-title="" title="">BACK</a>
                </h1>
            </div>
        </div>

        <div id="map" style="height: 700px; width:900px;"></div>
        <?php include "include/scripts.php"; ?>
        <link href="css/validationEngine.jquery.css" rel="stylesheet" />
        <script src="js/jquery.validationEngine.js"></script>
        <script src="js/jquery.validationEngine-en.js"></script>
        <script src="js/jquery.form.js"></script>
        <script src="js/bootbox.js"></script>

        <div class="container" style="margin-top:20px !important; float:left;">
            <form class="form-inline" name="frm_add_location_preference" id="frm_add_location_preference" method="post" action ="professional_ajax_process.php?action=edit_professional_availability" autocomplete="off">
                <div class="form-group mx-sm-3 mb-2">
                    <label><b>Location Name</b><span class="required">*</span></label>
                    <input type="hidden" name="hid_coords_arr" id="hid_coords_arr" value="" />
                    <input type="text" name="preference_name" id="preference_name" value="<?php if(!empty($_POST['preference_name'])) { echo $_POST['preference_name']; } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" maxlength="50" />
                </div>
                <div class="form-group mb-2">
                    <input type="button" name="btn_add_location_preference" id="btn_add_location_preference" onclick="return addLocPref(<?php echo $profId; ?>)" value="Save" class="btn btn-download" />
                </div>
            </form>
        </div>

        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&callback=initMap">
        </script>
        <script type="text/javascript">
            //This function is used for init map
            function initMap()
            {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: new google.maps.LatLng(18.5204,73.8567),
                    zoom: 12,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var coordsArr = [];
                //var polylineArr = [];
                //var elementData = {};

                var infoWindow = new google.maps.InfoWindow;

                google.maps.event.addListener(map, "click", function (event) {
                    //lat and lng is available in e object
                    var latLng = event.latLng;
                    var latitude = event.latLng.lat();
                    var longitude = event.latLng.lng();
                    coordsArr.push(latitude, longitude);
                    console.log("coordsArr", coordsArr);

                    //elementData.lat = parseFloat(latitude);
                    //elementData.lng = parseFloat(longitude);
                    //polylineArr.push(elementData);

                    //console.log("polylineArr", polylineArr);

                    $("#hid_coords_arr").val(coordsArr);
                    placeMarker(latLng, map /*, polylineArr*/);
                });  
            }

            //This function is used for place marker
            function placeMarker(location, map /*, polylineArr*/)
            {
                var marker = new google.maps.Marker({
                    position: location, 
                    map: map,
                    draggable:true
                });

                /*
                var flightPath = new google.maps.Polyline({
                    path: polylineArr,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });
                */

                map.panTo(location);

                //flightPath.setMap(map);
            }

            //This function is used for add location preferences
            function addLocPref(profId)
            {
                var locCoordsArr = $("#hid_coords_arr").val();
                var preferenceName = $("#preference_name").val();
                if (profId && locCoordsArr && preferenceName) {
                    var data1="profID=<?php echo $profId;?>&locCoordsArr=" + locCoordsArr + "&preferenceName=" + preferenceName;
                    $.ajax({
                        url: "professional_ajax_process.php?action=addLocPref",
                        type: "post",
                        data: data1,
                        cache: false,
                        async: false,
                        beforeSend: function() 
                        {
                            Display_Load();
                        },
                        success: function (html)
                        {
                            var result = html.trim();
                            if (result == 'validationError') {
                                bootbox.alert("Please check mandatory fields");
                            } else if (result == 'InsertSuccess') {
                                bootbox.alert("<div class='msg-success'>Location preference details added successfully.</div>", function() {
                                    window.close;
                                    window.location.href = 'add_availability.php?prID=<?php echo $prId; ?>';
                                });
                            } else if (result == 'ErrorInInsert') {
                                bootbox.alert("<div class='msg-error'>Error while adding location preference details.</div>");
                            }
                        },
                        complete : function()
                        {
                            Hide_Load();
                        }
                    });
                } else {
                    if (locCoordsArr == '') {
                        bootbox.alert("<div class='msg-error'>Please add atleast one location.</div>");
                    } else if (preferenceName == '') {
                        bootbox.alert("<div class='msg-error'>Please enter preference name.</div>");
                    } 
                }
            }
        </script>
    </body>
</html>