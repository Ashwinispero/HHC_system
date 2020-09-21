<?php require_once('inc_classes.php');
    require_once '../classes/professionalsClass.php';
    $professionalsClass = new professionalsClass();

    // Get location preferences
    $prId = $_REQUEST['prID'];
    $profId = '';
    if ($prId) {
        $profId = base64_decode($prID);
    }

    $availabilityList = $professionalsClass->profAvailabilityList($profId);

    // echo '<pre>';
    // print_r($availabilityList);
    // echo '</pre>';
    // exit;



?>
<div class="row">
    <div class="col-lg-6 marginB20 paddingl0" >
        <span style="color:#00cfcb; font-size:18px;">
            Availability Details <?php /*if (empty($availabilityList)) {*/ echo '<a href="javascript:void(0);" onclick="return vw_add_availability(' . $profId . ');" data-toggle="modal" class="btn btn-download">Add Availability</a>'; /*}*/ ?>
        </span>
    </div>
</div>

<div class="row">
<?php
    if (!empty($availabilityList)) {
        echo '<table class="table table-hover table-bordered">
            <tr>
                <th>Day</th>
                <th>Time</th>
                <th>Location</th>
                <th>Action</th>
            </tr>
        ';
        foreach ($availabilityList AS $valAvailability) {
            echo '<tr>
                <td>' . $valAvailability['dayVal'] . '</td>
                <td>' . str_replace(",","<br>", $valAvailability['timeSlot'])  . '</td>
                <td>' . str_replace(",","<br>", $valAvailability['locationNm']) . ' </td>
                <td>
                    <a href="javascript:void(0);" onclick="return vw_edit_availability(' . $profId . ', ' . $valAvailability['professional_avaibility_id'] . ');" data-toggle="tooltip" title="Edit"><img src="images/icon-edit.png" alt="Edit"></a>
                    <a href="javascript:void(0);" onclick="return remove_availability(' . $profId . ', ' . $valAvailability['professional_avaibility_id'] . ');" data-toggle="tooltip" title="Edit"><img src="images/icon-delete.png" alt="Delete"></a>
                </td>
            </tr>'; 
        }
        echo '</table>';
    } else {
        echo 'No availability available';
    }
?>
</div>