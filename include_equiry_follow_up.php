<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass = new eventClass();
  
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";
    if($_POST['SearchKey'] && $_POST['SearchKey']!="undefined")
        $search_Value=$_POST['SearchKey'];
    else
        $search_Value=""; 
 
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    if($_POST['sort_order'])
        $order1=$_POST['sort_order'];
    else
        $order1='desc';    
    if($_POST['sort_order']=='asc')
    {
        $order='desc';
        $img = "<img src='images/uparrow.png' border='0'>";
    }
    else if($_POST['sort_order']=='desc')
    {
        $order='asc';
        $img = "<img src='images/downarrow.png' border='0'>";
    }
    else
    {
        $order='desc';
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
    if($_POST['sort_field']=='title')
        $img1 = $img;
    

    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='title'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "follow_up_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
    $recListResponse = $eventClass->enquiryFollowUpList($recArgs);
    // var_dump($recListResponse);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count']; 
    if($recListCount > 0)
    {
        $paginationCount=getAjaxPagination($recListCount);
    }
    if(!$recListCount)
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    if($recListCount)
    {      
        echo '<table id="logTable" class="table table-striped" cellspacing="0">
                <thead>
                    <tr>
                      <th width="8%">Event Code</th>
                      <th width="8%">Caller Name</th>
                      <th width="8%">Caller Phone</th>
                      <th width="7%">HHC Code</th>
                      <th width="10%">Patient Name</th>
                      <th width="9%">Patient Phone</th>
                      <th width="10%">Description</th>
                      <th width="7%">Added By</th>
                      <th width="7%">Added Date</th>
                      <th width="7%">Modified By</th>
                      <th width="9%">Modified Date</th>
                      <th width="10%">Action</th>
                    </tr>
                </thead>'; 
       echo '<tbody>';
        foreach ($recList as $recListKey => $recListValue) 
        {
           $followUpId = $recListValue['follow_up_id'];
           $eventId = $recListValue['event_id'];
            echo '<tr>
                      <td>' . $recListValue['event_code'] . '</td>
                      <td>' . $recListValue['caller_name'] . '</td>
                      <td>' . $recListValue['caller_phone_no'] . '</td>
                      <td>' . (!empty($recListValue['hhc_code']) ? $recListValue['hhc_code'] : 'N/A') . '</td>
                      <td>' . (!empty($recListValue['patient_name']) ? $recListValue['patient_name'] : 'N/A') . '</td>
                      <td>' . (!empty($recListValue['patient_phone_no']) ? $recListValue['patient_phone_no'] : 'N/A') . '</td>
                      <td>' . $recListValue['follow_up_desc'] . '</td>
                      <td>' . $recListValue['added_by_emp_name'] . '</td>
                      <td>' . date('d M Y h:i A', strtotime($recListValue['added_date'])) . '</td>
                      <td>' . $recListValue['modified_by_emp_name'] . '</td>
                      <td>' . date('d M Y h:i A', strtotime($recListValue['last_modified_date'])) . '</td>
                      <td>';
                        if ($recListValue['is_read_status'] == 'N') {
                            echo '<a href="javascript:void(0);" onclick="return change_notification_status(' . $eventId . ',' . $followUpId .');" data-toggle="tooltip" title="follow up">
                                    <img src="images/follow-up.png" alt="follow up" height="24px" width="24px">
                                </a>
                                
                                <a href="javascript:void(0);" data-toggle="tooltip" title="Cancel" onclick="cancelEnquiry(' . $eventId . ')">
                                    <img src="images/cancel.png" alt="Cancel">
                                </a>

                                <a href="javascript:void(0);" data-toggle="tooltip" title="Convert" onclick="convertEnquiryIntoService(' . $eventId . ')">
                                    <img src="images/convert.png" alt="Convert">
                                </a>';
                        } else {
                            echo '<a href="javascript:void(0);" data-toggle="tooltip" title="Mark as read">
                                    <img src="images/icon-view.png" alt="Mark as read">
                                </a>
                                
                                <a href="javascript:void(0);" data-toggle="tooltip" title="Cancel">
                                    <img src="images/cancel.png" alt="Cancel">
                                </a>

                                <a href="javascript:void(0);" data-toggle="tooltip" title="Convert">
                                    <img src="images/convert.png" alt="Convert">
                                </a>'; 
                        }
                       
                    echo '<td>
                  </tr>';
        }
        echo '</tbody>';
        echo '</table>';
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
                                <label class="select-box-lbl">
                                    <select class="form-control" name="show_records" onchange="changePagination(\'enquiryFollowUpListing\',\'include_equiry_follow_up.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                                    <td align="right" onclick="changePagination(\'enquiryFollowUpListing\',\'include_equiry_follow_up.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                                    <td valign="middle"><input onclick="changePagination(\'enquiryFollowUpListing\',\'include_equiry_follow_up.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
                            }
                            else
                            {
                                echo '
                                    <td style="width:5px;"></td>
                                    <td valign="middle"><input type="button" class="btn" value=">"></td>';
                            }
                    echo '</tr>
                </tbody>
            </table>
        </div>';
    }
}?>