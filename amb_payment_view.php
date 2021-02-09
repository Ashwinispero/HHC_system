<?php 
require_once('inc_classes.php'); 
require_once 'classes/ambulanceClass.php';
$AmbulanceClass=new AmbulanceClass();
 
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{ 
$recList= $AmbulanceClass->amb_EventList();
?>
<div class="row">
  <div class="col-lg-1">
  <h4>Call Type</h4>
  </div>
  <div class="col-lg-2">
      <select class="validate[required] chosen-select form-control"  name="CallType" id="CallType" onchange="return ChangeCallType(this.value);">
          <option value="">Purpose Of Call</option>
          <option value='1'>Drop Call</option>
          <option value='2'>Payment & Job Closure</option>
      </select>
  </div>
</div>
<table border="1" bordercolor="#ddd" id="logTable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th style="text-align: center;" width="8%">Incident ID</th>
                <th style="text-align: center;" width="5%">Call Type</th>
                <th style="text-align: center;" width="8%">Ambulance No</th>
                <th style="text-align: center;" width="8%">Patient Name</th>
                <th style="text-align: center;" width="8%">Chief Complaint</th>
                <th  style="text-align: center;" width="8%">Pickup Address</th>
                <th style="text-align: center;" width="8%">Drop Adress</th>
                <th style="text-align: center;" width="8%">Schedule Date </th>
                <th style="text-align: center;" width="5%">Datetime</th>
                <th style="text-align: center;" width="3%">Action</th>
              </tr>
            </thead>
            <tbody>
            
            <?php
            foreach ($recList as $recListKey => $recListValue) 
            {
                if($recListValue['purpose_id'] == '1'){
                  $purpose = 'Drop Call';
                }else if($recListValue['purpose_id'] == '2'){
                  $purpose = 'Payment Call';
                }
                echo '<tr style = "' . $complimentaryVisitStyle .'">
                <td style = "' . $patientStyle .'">'.$recListValue['event_code'].' </td>
                <td>'.$purpose.'</td>
                <td>'.$recListValue['selected_amb'].'</td>
                <td>'.$recListValue['first_name'].' '.$recListValue['name'].'</td>
                <td>'.$recListValue['ct_type'].'</td>
                <td>'.$recListValue['google_pickup_location'].'</td>
                <td>'.$recListValue['google_drop_location'].'</td>
                <td>'.$recListValue['date'].'</td>
                <td>'.$recListValue['time'].'</td>';
                ?>
                <td>
                <?php 
                if($recListValue['event_status']=='1')
                {
                  ?>
                  
                  <div class="col-sm-9 text-center">
                  <input type="button" class="btn btn-primary" id="submit" value="Payment" onclick="return SubmitPayment(<?php echo $recListValue['event_id'] ; ?> );">
                  </div>
                  
                  <?php
                }
                else if($recListValue['event_status']=='2'){
                  ?>
                  
                  <div class="col-sm-9 text-center">
                  <input type="button" class="btn btn-primary" id="Job_Closure" value="Closure" onclick="return SubmitJobClosure(<?php echo $recListValue['event_code'] ; ?> );">
                  </div>
                  
                  <?php
                }
                ?>
                </td>
                
                <?php
                echo '</td>
                </tr>';
            } 
            ?>
            </tbody>
  </table>

<?php } ?>
