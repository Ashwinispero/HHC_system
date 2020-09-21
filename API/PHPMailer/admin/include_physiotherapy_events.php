<?php require_once('inc_classes.php'); 
        require_once '../classes/patientsClass.php';
        $patientsClass = new patientsClass();
        
      if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1')
      {
          $col_class="icon2";
          $del_visible="Y";
      }
      else 
      {
         $col_class="icon1"; 
         $del_visible="N";
      } 
      ?>
<?php
if(!$_SESSION['admin_user_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";

    $recArgs['admin_id']=$_SESSION['admin_user_id'];

    $recArgs['filter_name']= "event_id";
    $recArgs['filter_type']= "DESC";
   
    //var_dump($recArgs);
   //print_r($recArgs);
    $recListResponse= $patientsClass->GetEventListByService($recArgs);
   
    // var_dump($recListResponse);
    $recList=$recListResponse;
  
    if(empty($recListResponse))
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    else
    {      
        echo '<div class="table-responsive"><table id="jsontable" class="table table-hover table-bordered">
                <thead>
                <tr> 
                    <th>Event Id</th>
                    <th>Patient Name</th>
                    <th>Prof. Name</th>
                    <th>Recommonded Service</th>
                    <th>Service Date</th>
                    <th>Service Time</th>
                    <th class="'.$col_class.'">Action</th>
                </tr></thead>';   
                foreach ($recList as $recListKey => $recListValue) 
                { 
                   $event_id=$recListValue['event_id']; 
                    echo '<tr id="EventRecord_'.$event_id.'">
                            <td>'.$recListValue['event_code'].'</td>
                            <td>'.$recListValue['patient_name'].'</td>
                            <td>'.$recListValue['service_professional'].'</td>
                            <td>'.$recListValue['recommomded_service'].'</td>
                            <td>'.date('d M Y',strtotime($recListValue['service_date'])).'</td>
                            <td>'.$recListValue['service_time'].'</td>';
                            echo '<td>
                                      <ul class="actionlist">
                                        <li><a href="manage_event_summary.php?patient_id='.base64_encode($recListValue['patient_id']).'&event_id='.base64_encode($event_id).'" data-toggle="tooltip" title="View Event"><img src="images/icon-view.png"  alt="View Event"></a></li>';    
                                echo '</ul></td>
                          </tr>';
                }
        echo '</table></div>';
    }
}?>