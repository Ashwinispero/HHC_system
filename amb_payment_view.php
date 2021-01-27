<?php 
require_once('inc_classes.php'); 
require_once 'classes/ambulanceClass.php';
$AmbulanceClass=new AmbulanceClass();
 
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{ 


     $recList= $AmbulanceClass->amb_EventList();
     //$recList=$recListResponse['data'];
    // var_dump($recList)

?>
<form class="form-horizontal" name="DropForm" id="DropForm" method="post" action="amb_incident_summary_ajax_process.php?action=SubmitDropCall">
<div id="Block9">
<h1 class="div_header">Call Type</h1>
<div class="row" style="padding-left:5px;">
<label for="inputPassword3" class="col-lg-2 label_style">Purpose Of Call :<span style="color:red;">*</span></label>
  <div class="col-lg-2 input_box_first">
    <select class="validate[required] chosen-select form-control"  name="CallType" id="CallType" onchange="return ChangeCallType(this.value);">
        <option value="">Purpose Of Call</option>
        <option value='1'>Drop Call</option>
        <option value='2'>Payment</option>
    </select>
  </div>
  </div>
</div>
<div class="line-seprator"></div>
<table id="logTable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Incident No</th>
                <th>Call Type</th>
                <th>Ambulance No</th>
                <th>Patient No</th>
                <th>Cief Complaint</th>
                <th>Pickup Address</th>
                <th>Drop Adress</th>
                <th>Schedule Datetime</th>
                <th>Dispatch Datetime</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            
            <?php
            foreach ($recList as $recListKey => $recListValue) 
            {
                
                $EventIDS = base64_encode($event_id);
                
                echo '<tr style = "' . $complimentaryVisitStyle .'">
                <td style = "' . $patientStyle .'">'.$recListValue['event_id'].' </td>
                <td>'.$recListValue['purpose_id'].'</td>
                <td>'.$recListValue['amb_no'].'</td>
                <td>'.$recListValue['patient_id'].'</td>
                <td>'.$recListValue['Complaint_type'].'</td>
                <td>'.$recListValue['google_pickup_location'].'</td>
                <td>'.$recListValue['google_drop_location'].'</td>
                <td>'.$recListValue['date'].'</td>
                <td>'.$recListValue['time'].'</td>
                <td>'.'Action'.'</td>';
                echo '</td>
                </tr>';
            } 
            ?>
            </tbody>
  </table>
</form>
<?php } ?>
