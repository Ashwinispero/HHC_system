<?php require_once('inc_classes.php');
    require_once '../classes/professionalsClass.php';
    $professionalsClass = new professionalsClass();

    // Get location preferences
    $prId = $_REQUEST['prID'];
    $profId = '';
    if ($prId) {
        $profId = base64_decode($prId);
    }

    $locationList = $professionalsClass->profLocationPrefList($profId);

//     echo '<pre>';
//     print_r($locationList);
//     echo '</pre>';
//     exit;
 ?>
<div class="row">
    <div class="col-lg-6 marginB20 paddingl0" >
        <span style="color:#00cfcb; font-size:18px;">
            Location Preference Details 
            <a href="vw_add_location_preference.php?prID=<?php echo $prId; ?>" target="_blank" data-toggle="tooltip" class="btn btn-download">Add Location Preference</a>
        </span>
    </div>
</div>

<div class="row">
<?php
    if (!empty($locationList)) {
        echo '<ol>';
        foreach ($locationList AS $valLocation) {
            echo '<li>
                    <div style="margin: 0px 0px 5px 0px">
                        <a onclick="return showMap('.$valLocation['Professional_location_id'].')">' . $valLocation['Name'] . '</a>&nbsp;&nbsp;&nbsp;
                        <input type="button" name="btn_remove_location" id="btn_remove_location" value="Remove" onclick="return removeLocation('.$valLocation['Professional_location_id'].')" />
                    </div>
                </li>';
        } 
        echo '</ol>';  
    } else {
        echo 'No locations available';
    }
?>
    <div id ="mapContent">
        <div id="map_canvas" style="height: 354px; width:713px;"></div>
    </div>
</div>