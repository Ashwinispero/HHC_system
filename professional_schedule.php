<?php   require_once 'inc_classes.php';        
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";        
        include "classes/eventClass.php";
        $eventClass = new eventClass();        
        require_once 'classes/functions.php'; 
?>

<?php
if($_REQUEST['action']=='viewProfessionalSchedule')
{ 
    $professional_id = $_REQUEST['professional_id'];
    $GetProfessionalSql="SELECT service_professional_id,professional_code,name,first_name,middle_name,location_id FROM sp_service_professionals WHERE service_professional_id='".$professional_id."'";
    $valRecords=$db->fetch_array($db->query($GetProfessionalSql)); 
  ?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">View Professional Scheduled</h4>
</div>
<div class="modal-body">
  <div class="mCustom Scrollbar">
      <div id='calendar'></div>
  </div>
</div>
<?php  
}
else if($_REQUEST['action'] == 'FetchProfessional')
{
    $professiona_id = $_REQUEST['professional_id'];
    $events = array();
	$Schedquery = "select schedule_id,scheduled_date,from_time,to_time from sp_professional_scheduled where status='1' and professiona_id = '".$professiona_id."'";
	$ptr_scheduled = $db->fetch_all_array($Schedquery);
        foreach($ptr_scheduled as $key=>$valScheduled)
        {
            $e = array();
            $e['id'] = $valScheduled['schedule_id'];
            $e['title'] = $valScheduled['from_time'].' To '.$valScheduled['to_time'];
            $e['start'] = $valScheduled['scheduled_date'];
            $e['end'] = $valScheduled['scheduled_date'];

            //$allday = ($fetch['allDay'] == "true") ? true : false;
            $e['allDay'] = 'false';

            array_push($events, $e);
        }
	
	echo json_encode($events);
}
else if($_REQUEST['action'] == 'viewBusyScheduled')
{
    $professional_id = $_REQUEST['professional_id'];
    $GetProfessionalSql="SELECT service_professional_id,professional_code,name,first_name,middle_name,location_id FROM sp_service_professionals WHERE service_professional_id='".$professional_id."'";
    $valRecords=$db->fetch_array($db->query($GetProfessionalSql)); 
    ?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" <?php echo $onclick;?> ><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">View Busy Scheduled</h4>
</div>
<div class="modal-body">
    <?php
    $selectStatus = "select event_professional_id,service_closed, event_requirement_id, plan_of_care_id,service_id,event_id from sp_event_professional where professional_vender_id = '".$professional_id."' and service_closed = 'N'  ";
    if(mysql_num_rows($db->query($selectStatus)))
    {
    ?>
  <div class="mCustomScrollbar">
        <div class="clearfix"></div>
        <div>
            Professional Name : <?php echo $valRecords['name'].' '.$valRecords['first_name'].' '.$valRecords['middle_name'];?>
        </div>
                <div class="share_table_content">
                    <table cellpadding="0" cellspacing="0" class="table table-bordered table-hover"> 
                        <tr>
                            <th width="15%">Patient Name</th>
                            <th width="15%">From Date</th>
                            <th width="15%">To Date</th>
                            <th width="20%">Time</th>
                            <th width="13%">Location</th>
                            <th width="23%">Recommended Service</th>
                        </tr>
                        <?php
                            $valProfessional = $db->fetch_all_array($selectStatus);
                            foreach($valProfessional AS $key=>$valExistSched)
                            {
                                  $select_plancare = "select service_date,service_date_to,start_date,end_date from sp_event_plan_of_care where event_id='".$valExistSched['event_id']."' and event_requirement_id = '".$valExistSched['event_requirement_id']."'";
                                  $ptr_plancare = $db->fetch_all_array($select_plancare);
                                  foreach($ptr_plancare as $key=>$val_planofcare)
                                  {

                                    $EveArr['event_id'] = $valExistSched['event_id'];
                                    $GetCaller=$eventClass->GetEventCaller($EveArr);
                                    $Patiarr['patient_id']=$GetCaller['patient_id'];
                                    $ValpatientSummary=$eventClass->GetPatientById($Patiarr);

                                    $select_services_id= "select sub_service_id from sp_event_requirements where event_requirement_id = '".$valExistSched['event_requirement_id']."' and status='1'";
                                    $val_Id = $db->fetch_array($db->query($select_services_id));

                                    $select_services= "select recommomded_service from sp_sub_services where sub_service_id = '".$val_Id['sub_service_id']."' and status='1'";
                                    $val_RecommendedSer = $db->fetch_array($db->query($select_services));
                                echo '<tr>
                                        <td width="15%">'.$ValpatientSummary['name'].' '.$ValpatientSummary['first_name'].' '.$ValpatientSummary['middle_name'].'</td> 
                                        <td width="15%">'.date('d-m-Y',strtotime($val_planofcare['service_date'])).'</td>
                                        <td width="15%">'.date('d-m-Y',strtotime($val_planofcare['service_date_to'])).'</td>
                                        <td width="20%">'.$val_planofcare['start_date'].' To '.$val_planofcare['end_date'].'</td>
                                        <td width="15%">'.$ValpatientSummary['locationNm'].'</td>
                                        <td width="20%">'.$val_RecommendedSer['recommomded_service'].'</td>
                                    </tr>';
                                   
                                   // unset($val_RecommendedSer['recommomded_service']);
                                }
                            }
                            
                        ?>

                    </table>
                </div>
        
  </div>
    <?php } ?>
</div>
<?php
}
?>
