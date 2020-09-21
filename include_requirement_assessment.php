<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
  
if(!$_SESSION['employee_id'] && $_SESSION['employee_type']=='1')
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";
    if($_POST['SearchKey'] && $_POST['SearchKey']!="undefined")
        $search_Value=$_POST['SearchKey'];
    else
        $search_Value="";
    
    if($_POST['SearchByPurpose'] && $_POST['SearchByPurpose']!="undefined")
        $search_by_purpose=$_POST['SearchByPurpose'];
    else
        $search_by_purpose="";
    
    if($_POST['SearchByEmployee'] && $_POST['SearchByEmployee']!="undefined")
        $search_by_employee=$_POST['SearchByEmployee'];
    else
        $search_by_employee="";
    
    if($_POST['SearchToDate'] && $_POST['SearchToDate']!="undefined")
        $search_todate=$_POST['SearchToDate'];
    else
        $search_todate="";
    
    if($_POST['SearchfromDate'] && $_POST['SearchfromDate']!="undefined")
        $search_fromDate=$_POST['SearchfromDate'];
    else
        $search_fromDate=""; 
 
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['employee_id']=$_SESSION['employee_id'];
    $recArgs['SearchfromDate']=$search_fromDate;
    $recArgs['SearchToDate']=$search_todate;
    $recArgs['SearchByPurpose']=$search_by_purpose;
    $recArgs['SearchByEmployee']=$search_by_employee;
    
    if($_POST['sort_order'])
        $order1=$_POST['sort_order'];
    else
        $order1='desc';    
    if($_POST['sort_order']=='asc')
    {
        $order='desc';
        $img = "<img src='images/sort_up.png' border='0'>";
    }
    else if($_POST['sort_order']=='desc')
    {
        $order='asc';
        $img = "<img src='images/sort_dwon.png' border='0'>";
    }
    else
    {
        $order='desc';
        $img = "<img src='images/sort_up.png' border='0'>";
    }    
    if(isset($_POST['sort_field']))
    {
        $sort_variable=$_POST['sort_field'];  
    }
    else
    {
        $sort_variable="";
    }    
    if($_POST['sort_field']=='event_code')
        $img1 = $img;
    
    if($_POST['sort_field']=='event_date')
        $img2 = $img;
   
    if($_POST['sort_field']=='event_status')
        $img3 = $img;
    

    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='event_code' || $_POST['sort_field']=='event_date'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "event_share_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
//    echo '<pre>';
//   print_r($recArgs);
//    echo '</pre>';
    
    $recListResponse= $eventClass->GetAssessment($recArgs);
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
        echo '<table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                    <tr>
                      <th>HHC No</th>
                      <th><a href="javascript:void(0);" onclick="changePagination(\'AssessmentListing\',\'include_requirement_assessment.php\',\'\',\'\',\''.$order.'\',\'event_code\');" style="color:#fff;">Event Id'.' '.$img1.'</a></th>
                      <th><a href="javascript:void(0);" onclick="changePagination(\'AssessmentListing\',\'include_requirement_assessment.php\',\'\',\'\',\''.$order.'\',\'event_date\');" style="color:#fff;">Event Date Time'.' '.$img2.'</a></th>
                      <th>Call Purpose</th>
                      <th>Caller Name</th>
                      <th>Attended By</th>
                      <th style="text-align:center !important;"><a href="javascript:void(0);" onclick="changePagination(\'AssessmentListing\',\'include_requirement_assessment.php\',\'\',\'\',\''.$order.'\',\'event_status\');" style="color:#fff; text-align:center">Status '.$img3.' </a></th>
                      <th>Action</th>
                    </tr>
                </thead>'; 
       echo '<tbody>';
        foreach ($recList as $recListKey => $recListValue) 
        {  
           $event_share_id=$recListValue['event_share_id']; 
           echo '<tr>
                    <td>'.$recListValue['hhc_code'].'</td>
                    <td>'.$recListValue['event_code'].'</td>
                    <td>'.$recListValue['event_date'].'</td>
                    <td>'.$recListValue['call_purpose'].'</td>
                    <td>'.$recListValue['callerName'].'</td>
                    <td>'.$recListValue['added_by'].'</td>
                    <td width="15%">
                        <div class="col-lg-5 paddingLR0 text-right">'.$recListValue['event_status'].' &nbsp; </div>
                        <div class="col-lg-7 paddingLR0">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="'.$recListValue['event_status'].'" aria-valuemin="0" aria-valuemax="100" style="width: '.$recListValue['event_status'].';"> </div> 
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="View Event" onclick="ViewEvent('.$event_share_id.')";><span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></a> 
                        <a href="event-log.php?EID='.base64_encode($recListValue['event_id']).'" data-toggle="tooltip" data-placement="top" title="Edit Event"><span aria-hidden="true" class="glyphicon glyphicon-pencil"></span></a>
                    </td>
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
                                    <select class="form-control" name="show_records" onchange="changePagination(\'AssessmentListing\',\'include_requirement_assessment.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                                    <td align="right" onclick="changePagination(\'AssessmentListing\',\'include_requirement_assessment.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                                    <td valign="middle"><input onclick="changePagination(\'AssessmentListing\',\'include_requirement_assessment.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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