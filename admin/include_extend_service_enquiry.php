<?php require_once('inc_classes.php'); 
      require_once '../classes/extendServiceClass.php';  
	  $extendServiceClass = new extendServiceClass();

      if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type'] == '1')
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
    
    if($_POST['sort_field']=='event_code') {
        $img1 = $img;
	}
    if($_POST['sort_field']=='service_date') {
        $img2 = $img;
	}
    if($_POST['sort_field']=='service_date_to') {
        $img3 = $img;
	}
    if($_POST['sort_field']=='startTime') {
        $img4 = $img;
	}

	if($_POST['sort_field']=='endTime') {
        $img5 = $img;
	}

	if($_POST['sort_field']=='estimate_cost') {
        $img6 = $img;
	}

	if($_POST['sort_field']=='added_date') {
        $img7 = $img;
	}
   
	if($_POST['sort_order'] != '' && ($_POST['sort_field'] == 'event_code' || $_POST['sort_field'] == 'service_date' || 
		$_POST['sort_field'] == 'service_date_to' || $_POST['sort_field'] == 'startTime' || $_POST['sort_field'] == 'endTime' ||
		$_POST['sort_field'] == 'estimate_cost' || $_POST['sort_field'] == 'added_date'))
    {
        $recArgs['filter_name'] = $_POST['sort_field'];
        $recArgs['filter_type'] = $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name'] = "added_date";
        $recArgs['filter_type'] = "DESC";
    }
	
	
    //var_dump($recArgs);
    //print_r($recArgs);
    $_SESSION['extend_service_enquiry_list_args'] = $recArgs;
	
    $recListResponse = $extendServiceClass->extendServiceList($recArgs);
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
                    <th><a href="javascript:void(0);" onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\'\',\'\',\''.$order.'\',\'event_code\');" style="color:#00cfcb;">Event ID '.' '.$img1.'</a></th>
                    <th><a href="javascript:void(0);" onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\'\',\'\',\''.$order.'\',\'service_date\');" style="color:#00cfcb;">Start Date'.' '.$img2.'</a></th>
                    <th><a href="javascript:void(0);" onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\'\',\'\',\''.$order.'\',\'service_date_to\');" style="color:#00cfcb;">End Date '.' '.$img3.'</a></th>
					<th><a href="javascript:void(0);" onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\'\',\'\',\''.$order.'\',\'startTime\');" style="color:#00cfcb;">Start Time '.' '.$img4.'</a></th>
					<th><a href="javascript:void(0);" onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\'\',\'\',\''.$order.'\',\'endTime\');" style="color:#00cfcb;">End Time '.' '.$img5.'</a></th>
					<th><a href="javascript:void(0);" onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\'\',\'\',\''.$order.'\',\'estimate_cost\');" style="color:#00cfcb;">Cost '.' '.$img6.'</a></th>   
					<th><a href="javascript:void(0);" onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\'\',\'\',\''.$order.'\',\'added_date\');" style="color:#00cfcb;">Added Date '.' '.$img7.'</a></th>
					<th>Status</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
            
           $extendServiceId = $recListValue['extend_service_id'];

            echo '<tr id="extendServiceEnquiryRecord_' . $extendServiceId . '">
					<td>' . $recListValue['event_code'] . '</td>
                    <td>' . date('d M Y h:i A',strtotime($recListValue['service_date'])) . '</td>
                    <td>' . date('d M Y h:i A',strtotime($recListValue['service_date_to'])) . '</td>
                    <td>' . date('h:i A',strtotime($recListValue['startTime'])) . '</td>
                    <td>' . date('h:i A',strtotime($recListValue['endTime'])) . '</td>
					<td>' . $recListValue['estimate_cost'] . '</td>
                    <td>' . date('d M Y h:i A',strtotime($recListValue['added_date'])) . '</td>
                    <td>' . $recListValue['statusVal'] . '</td>
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
                                    <select name="show_records" onchange="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'extendServiceEnquiryListing\',\'include_extend_service_enquiry.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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