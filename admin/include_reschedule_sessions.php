<?php require_once('inc_classes.php'); 
      require_once '../classes/rescheduleSessionClass.php';  
      $rescheduleSessionClass = new rescheduleSessionClass();
      if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1')
      {
          $col_class="icon4";
          $del_visible="Y";
      }
      else 
      {
         $col_class="icon3"; 
         $del_visible="N";
      } 
?>
<?php
if(!$_SESSION['admin_user_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";
    if ($_POST['SearchKey'] && $_POST['SearchKey'] != "undefined")
        $searchValue = $_POST['SearchKey'];
    else
        $searchValue = "";

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

    
      
    $recArgs['pageIndex'] = $pageId;
    $recArgs['pageSize'] = PAGE_PER_NO;
    $recArgs['search_value'] = $searchValue;
    $recArgs['admin_id'] = $_SESSION['admin_user_id'];
    $recArgs['searchfromDate'] = $searchfromDate;
    $recArgs['searchToDate']   = $searchToDate;
	
    if($_POST['sort_order']) {
        $order1=$_POST['sort_order'];
	}
    else {
        $order1='desc';
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
    if (isset($_POST['sort_field']))
    {
        $sortVariable = $_POST['sort_field'];  
    }
    else
    {
        $sortVariable = "";
    }
    
    if($_POST['sort_field']=='name') {
        $img1 = $img;
	}
    if($_POST['sort_field']=='email_id') {
        $img2 = $img;
	}
    if($_POST['sort_field']=='landline_no') {
        $img3 = $img;
	}
    if($_POST['sort_field']=='mobile_no') {
        $img4 = $img;
	}
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='name' || $_POST['sort_field']=='email_id' || $_POST['sort_field']=='landline_no' || $_POST['sort_field']=='mobile_no'))
    {
        $recArgs['filter_name'] = $_POST['sort_field'];
        $recArgs['filter_type'] = $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name'] = "reschedule_session_id";
        $recArgs['filter_type'] = "DESC";
    }
	
	
    //var_dump($recArgs);
    //print_r($recArgs);
    $_SESSION['reschedule_session_list_args'] = $recArgs;
	
	//$rescheduleSessionId = 5;
	//$test = $rescheduleSessionClass->getRescheduleSessionById($rescheduleSessionId);

    //echo '<pre>';
    //print_r($test);
    //echo '</pre>';
    //exit;

	//$recListResponse = $rescheduleSessionClass->updateSessionDtls($test);
	
	//echo '<pre>';
    //print_r($recListResponse);
	//echo '</pre>';
	//exit;
	
    $recListResponse = $rescheduleSessionClass->rescheduleSessionList($recArgs);
    $recList = $recListResponse['data'];

    $recListCount = $recListResponse['count']; 
    if($recListCount > 0)
    {
        $paginationCount = getAjaxPagination($recListCount);
    }
    if(!$recListCount)
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    if($recListCount)
    {      
        echo '<div class="table-responsive"><table class="table table-hover table-bordered">
                <tr> 
                    <th width="7%"><a href="javascript:void(0);" onclick="changePagination(\'rescheduleSessionListing\',\'include_reschedule_sessions.php\',\'\',\'\',\''.$order.'\',\'event_id\');" style="color:#00cfcb;">Event Id '.' '.$img1.'</a></th>
                    <th width="13%"><a href="javascript:void(0);" onclick="changePagination(\'rescheduleSessionListing\',\'include_reschedule_sessions.php\',\'\',\'\',\''.$order.'\',\'professional_name\');" style="color:#00cfcb;">Prof. Name '.' '.$img2.'</a></th>
                    <th width="12%"><a href="javascript:void(0);" onclick="changePagination(\'rescheduleSessionListing\',\'include_reschedule_sessions.php\',\'\',\'\',\''.$order.'\',\'patient_name\');" style="color:#00cfcb;">PatientName '.' '.$img3.'</a></th>
                    <th width="21%"><a href="javascript:void(0);" onclick="changePagination(\'rescheduleSessionListing\',\'include_reschedule_sessions.php\',\'\',\'\',\''.$order.'\',\'event_date_time\');" style="color:#00cfcb;">Actual Date Time / <br> Proposed Date Time  '.' '.$img4.'</a></th>
					<th width="8%">Raised by</th>
					<th>Reason</th>
                    <th width="10%">Added Date</th>   
                    <th width="7%">Status</th>   
                    <th class="'.$col_class.'">Action</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
            
           $rescheduleSessionId = $recListValue['reschedule_session_id'];
		   $actualDate = "";
		   if (date('Y-m-d',strtotime($recListValue['session_start_date'])) == date('Y-m-d', strtotime($recListValue['session_end_date']))) {
			   $actualDate = date('d M Y h:i A', strtotime($recListValue['session_start_date'])) ." - " . 
					date('h:i A', strtotime($recListValue['session_end_date']));
		   } else {
			   $actualDate = date('d M Y h:i A', strtotime($recListValue['session_start_date'])) ." <br/> TO " . 
					date('d M Y h:i A',strtotime($recListValue['session_end_date']));
		   }
		   
		   $proposedDate = "";
		   if (date('Y-m-d', strtotime($recListValue['reschedule_start_date'])) == date('Y-m-d', strtotime($recListValue['reschedule_end_date']))) {
			   $proposedDate = date('d M Y h:i A', strtotime($recListValue['reschedule_start_date'])) ." - " . 
					date('h:i A', strtotime($recListValue['reschedule_end_date']));
		   } else {
			   $proposedDate = date('d M Y h:i A', strtotime($recListValue['reschedule_start_date'])) ." <br/> TO " . 
					date('d M Y h:i A',strtotime($recListValue['reschedule_end_date']));
		   }
            echo '<tr id="rescheduleSessionRecord_' . $rescheduleSessionId  . '">
					<td>'.$recListValue['event_code'].'</td>
                    <td>'.$recListValue['professional_name'] . '<br/> Mobile :<br/>' . $recListValue['mobile_no'] . '</td>
                    <td>'.$recListValue['patient_name'] . '<br/> Mobile :<br/>' . $recListValue['patient_mobile_no'] .'</td>
                    <td>' . $actualDate . '<br/><br/>'
						. $proposedDate .
					'</td>
                    <td>'.$recListValue['raisedBy'].'</td>
					<td>'.$recListValue['reschedule_reason'].'</td>
                    <td>'.date('d M Y',strtotime($recListValue['added_date'])).'</td>
                    <td>'.$recListValue['statusVal'].'</td>';
                    echo '<td>
                              <ul class="actionlist">
                                    <li><a href="javascript:void(0);" onclick="return view_reschedule_session('.$rescheduleSessionId .');" data-toggle="tooltip" title="View User"><img src="images/icon-view.png" alt="View Reschedule Sessions"></a></li>
									<li><a href="javascript:void(0);" onclick="return vw_change_status('.$rescheduleSessionId .');" data-toggle="tooltip" title="Change Status"><img src="images/icon-add.png" alt="Change Status"></a></li>';
                                    //echo '<li><a href="javascript:void(0);" onclick="return vw_add_reschedule_session('.$rescheduleSessionId .');" data-toggle="tooltip" title="Edit"><img src="images/icon-edit.png" alt="Edit"></a></li>';
                        echo '</ul></td>
                  </tr>';
        }
        echo '</table></div>';
    }
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
                                <label>
                                    <select name="show_records" onchange="changePagination(\'rescheduleSessionListing\',\'include_reschedule_sessions.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'rescheduleSessionListing\',\'include_reschedule_sessions.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'rescheduleSessionListing\',\'include_reschedule_sessions.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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