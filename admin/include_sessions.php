<?php require_once('inc_classes.php'); 
        require_once '../classes/sessionsClass.php';
        $sessionsClass = new sessionsClass();
        
        if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1')
        {
          $col_class="icon6";
          $del_visible="Y";
        }
        else 
        {
         $col_class="icon5"; 
         $del_visible="N";
        } 
?>
<?php
if(!$_SESSION['admin_user_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";


    // echo '<pre>$_POST ------<br>';
    // print_r($_POST);
    // echo '</pre>';

    if ($_POST['SearchKey'] && $_POST['SearchKey']!="undefined") {
        $search_Value = $_POST['SearchKey'];
    }
    else {
        $search_Value = "";
    }
    
    if($_POST['reference_type'] && $_POST['reference_type']!= "undefined" && $_POST['reference_type']!= "null") {
        $reference_type_Value = $_POST['reference_type'];
    }
    else {
        $reference_type_Value = "";
    }
     
    if ($_POST['location_id'] && $_POST['location_id']!= "undefined" && $_POST['location_id']!= "null") {
        $location_Value = $_POST['location_id'];
    }
    else {
        $location_Value = "";
    }
    
    if ($_POST['Prof_service_id'] && $_POST['Prof_service_id']!="undefined" && $_POST['Prof_service_id']!="null") {
        $service_Value = $_POST['Prof_service_id'];
    }
    else {
        $service_Value = "";
    }

    if ($_POST['SearchfromDate'] &&
        $_POST['SearchfromDate'] != "undefined" &&
        $_POST['SearchfromDate'] != "null") {
        $searchfromDate = $_POST['SearchfromDate'];
    }
    else {
        $searchfromDate = "";
    }

    if ($_POST['SearchToDate'] &&
        $_POST['SearchToDate'] != "undefined" &&
        $_POST['SearchToDate'] != "null") {
        $searchToDate = $_POST['SearchToDate'];
    }
    else {
        $searchToDate = "";
    }
      
    $recArgs['pageIndex']      = $pageId;
    $recArgs['pageSize']       = PAGE_PER_NO;
    $recArgs['search_Value']   = $search_Value;
    $recArgs['ref_type_Value'] = $reference_type_Value;
    $recArgs['location_Value'] = $location_Value;
    $recArgs['service_Value']  = $service_Value;

    $recArgs['searchfromDate'] = $searchfromDate;
    $recArgs['searchToDate']   = $searchToDate;
    
    $recArgs['admin_id']       = $_SESSION['admin_user_id'];
    $recArgs['assigned_hospital_ids'] = $_SESSION['assigned_hospital_ids'];

    if ($_POST['sort_order']) {
        $order1 = $_POST['sort_order'];
    }
    else {
        $order1 = 'desc';    
    }
    if ($_POST['sort_order'] == 'asc')
    {
        $order = 'desc';
        $img = "<img src='images/uparrow.png' border='0'>";
    }
    else if ($_POST['sort_order']=='desc')
    {
        $order = 'asc';
        $img = "<img src='images/downarrow.png' border='0'>";
    }
    else
    {
        $order = 'desc';
        $img = "<img src='images/uparrow.png' border='0'>";
    }    
    if(isset($_POST['sort_field']))
    {
        $sort_variable=$_POST['sort_field'];  
    }
    else
    {
        $sort_variable="";
    }

    if($_POST['sort_field'] == 't4.event_code') {
        $img1 = $img;
    }

    if($_POST['sort_field'] == 'patient_name') {
        $img2 = $img;
    }

    if($_POST['sort_field'] == 'professional_name') {
        $img3 = $img;
    }
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field'] == 't4.event_code' || $_POST['sort_field'] == 'patient_name' || $_POST['sort_field'] == 'professional_name'))
    {
        $recArgs['filter_name'] = $_POST['sort_field'];
        $recArgs['filter_type'] = $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name'] = "t1.Detailed_plan_of_care_id";
        $recArgs['filter_type'] = "DESC";
    }

    $_SESSION['session_list_args'] = $recArgs;
    $recListResponse = $sessionsClass->sessionsList($recArgs);

     //echo '<pre>';
     //print_r($recListResponse);
     //echo '</pre>';
     //exit;

    $recList = $recListResponse['data'];
    $recListCount = $recListResponse['count']; 
    if ($recListCount > 0)
    {
        $paginationCount = getAjaxPagination($recListCount);
    }

    if(!$recListCount) {
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    }

    if($recListCount)
    {      
        echo '<table class="table table-hover table-bordered">
                <tr> 
                    <th width="10%"><a href="javascript:void(0);" onclick="changePagination(\'sessionListing\',\'include_sessions.php\',\'\',\'\',\''.$order.'\',\'t4.event_code\');" style="color:#00cfcb;">Event Id '.' '.$img1.'</a></th>
                    <th width="20%"><a href="javascript:void(0);" onclick="changePagination(\'sessionListing\',\'include_sessions.php\',\'\',\'\',\''.$order.'\',\'patient_name\');" style="color:#00cfcb;">Patient Name '.' '.$img2.'</a></th>
                    <th width="20%"><a href="javascript:void(0);" onclick="changePagination(\'sessionListing\',\'include_sessions.php\',\'\',\'\',\''.$order.'\',\'professional_name\');" style="color:#00cfcb;">Professional Name '.' '.$img3.'</a></th>
                    <th width="15%">Services</th>
                    <th width="15%">Date</th>
                    <th width="10%">Status</th> 
                    <th width="10%">Action</th>
                </tr>';
        foreach ($recList as $recListKey => $recListValue) 
        {
            $detailedPlanOfCareId = $recListValue['Detailed_plan_of_care_id'];

            // Get Session date and time
            $sessionStartDate = date('d M Y', strtotime($recListValue['start_date']));
            $sessionStartTime = date('h:i A', strtotime($recListValue['start_date']));
            $sessionEndDate = date('Y-m-d', strtotime($recListValue['end_date']));
            $sessionEndTime = date('h:i A', strtotime($recListValue['end_date']));

            $sessionDate = $sessionStartDate . "<br>(" . $sessionStartTime . "-" . $sessionEndTime . ")";


            echo '<tr id="sessionRecord_'. $detailedPlanOfCareId .'">
                    <td>' . $recListValue['event_code'] . '</td>
                    <td>' . $recListValue['patient_name'] . '</td>
                    <td>'. $recListValue['professional_name'] . '</td>
                    <td>' . $recListValue['service_title'] . '<br>' . $recListValue['recommomded_service'] . '</td>
                    <td>' . $sessionDate . '</td>
                    <td>'. $recListValue['statusVal'] . '</td>';

                    echo '<td>
                                <ul class="actionlist">
                                    <li><a href="javascript:void(0);" onclick="return view_session(' . $detailedPlanOfCareId . ');" data-toggle="tooltip" title="View Session"><img src="images/icon-view.png" alt="View Session"></a>
                                    </li>';
									if ($recListValue['Session_status'] == '2' || $recListValue['Session_status'] == '6') {
										
                                    echo '<li>
											<a href="javascript:void(0);" onclick="javascript:void(0);" data-toggle="tooltip" title="Change Status"><img src="images/icon-add-desable.png" alt="Change Status"></a>
										</li>';
									
									} else {
										echo '<li>
											<a href="javascript:void(0);" onclick="return vw_change_status('.$detailedPlanOfCareId .');" data-toggle="tooltip" title="Change Status"><img src="images/icon-add.png" alt="Change Status"></a>
										</li>';
									}
                                echo '</ul>
                        </td>
                  </tr>';
        }
        echo '</table>';
    }
    if ($paginationCount)
    {
        echo '<div class="clearfix"></div>';
        echo '<div class="col-lg-12 paddingR0 text-right">
                <table cellspacing="0" cellpadding="0" align="right">
                    <tbody>
                        <tr>
                            <td>Show</td>
                            <td style="width:10px;"></td>
                            <td class="pagination-dropdown">
                                <label>
                                    <select name="show_records" onchange="changePagination(\'sessionListing\',\'include_sessions.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'sessionListing\',\'include_sessions.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
        }
        else
        {
            echo '
                <td style="width:10px;"></td>
                <td align="right" valign="middle"><input type="button" class="btn btn-disabled" value="<"></td>';
        }
        if($pageId!=($paginationCount))
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input onclick="changePagination(\'sessionListing\',\'include_sessions.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
        }
        else
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input type="button" class="btn btn-disabled" value=">"></td>';
        }
        echo '          </tr>
                    </tbody>
                </table>
            </div>';
    }
}?>