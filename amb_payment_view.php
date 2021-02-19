<?php 
require_once('inc_classes.php'); 
require_once 'classes/ambulanceClass.php';
$AmbulanceClass=new AmbulanceClass();
 
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{ 
  include "pagination-include.php";
  $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;

$recListResponse= $AmbulanceClass->amb_EventList($recArgs);

$recList=$recListResponse['data'];
$recListCount=$recListResponse['count']; 

    if($recListCount > 0)
    {
        $paginationCount=getAjaxPagination($recListCount);
    }
    
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
  <?php
  // $paginationCount =1 ;
   if($paginationCount)
        {
        echo '<div class="clearfix"></div>';
        echo '<div class="col-lg-12 paddingR0 text-right">
                <table cellspacing="0" cellpadding="0" align="right">
                    <tbody>
                        <tr>
                            <td>Show</td>
                            <td style="width:10px;"></td>
                            <td class="pagination-dropdown">
                                <label class="select-box-lbl">
                                    <select class="form-control" name="show_records" onchange="changePagination(\'eventLogListing\',\'ambulance_dashbaord.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
                                    for($s=0;$s<count($GLOBALS['show_records_arr']);$s++)
                                    {
                                        if($_SESSION['per_page']==$GLOBALS['show_records_arr'][$s] || $_SESSION['per_page']==$GLOBALS["records_all"])
                                            echo '<option selected="selected" value="'.$GLOBALS['show_records_arr'][$s].'">'.$GLOBALS['show_records_arr'][$s].' Records</option>';
                                        else
                                            echo '<option value="'.$GLOBALS['show_records_arr'][$s].'">'.$GLOBALS['show_records_arr'][$s].' Records</option>';
                                    }
                                echo'</select>
                                </label>
                            </td>
                            <td style="width:10px;"></td>';
        if($recListCount<($start+PAGE_PER_NO))
            $pagesOf=($start+1).'-'.($recListCount).' of '.$recListCount;
        else
            $pagesOf=($start+1).'-'.($start+PAGE_PER_NO).' of '.$recListCount;
                        echo '<td>'.$pagesOf.'</td>';
        if($pageId>1)
        {
            echo '
                <td style="width:10px;"></td>
                <td align="right" onclick="changePagination(\'eventLogListing\',\'ambulance_dashbaord.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
        }
        else
        {
            echo '
                <td style="width:10px;"></td>
                <td align="right" valign="middle"><input type="button" class="btn" value="<"></td>';
        }
        if($pageId!=($paginationCount))
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input onclick="changePagination(\'eventLogListing\',\'ambulance_dashbaord.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
        }
        else
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input type="button" class="btn" value=">"></td>';
        }
        echo '          </tr>
                    </tbody>
                </table>
            </div>';
        echo '<div class="clearfix"></div>';
    }
 } ?>
