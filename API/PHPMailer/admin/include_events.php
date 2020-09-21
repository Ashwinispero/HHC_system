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
    
    if($_POST['SearchByProfessional'] && $_POST['SearchByProfessional']!="undefined")
        $search_by_professional=$_POST['SearchByProfessional'];
    else
        $search_by_professional="";
    
    if($_POST['SearchByService'] && $_POST['SearchByService']!="undefined")
        $search_by_service=$_POST['SearchByService'];
    else 
        $search_by_service="";
    
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
    $recArgs['SearchByPurpose']=$search_by_purpose;
    $recArgs['SearchByEmployee']=$search_by_employee;
    $recArgs['SearchByProfessional']=$search_by_professional;
    $recArgs['SearchByService']=$search_by_service;
    $recArgs['SearchfromDate']=$search_fromDate;
    $recArgs['SearchToDate']=$search_todate;
    $recArgs['admin_id']=$_SESSION['admin_user_id'];
    
    if(isset($_REQUEST['patient_id']))
        $recArgs['patient_id']=base64_decode($_REQUEST['patient_id']);
    else if(isset($_REQUEST['record_id']))
        $recArgs['patient_id']=$_REQUEST['record_id'];
    
    if($_POST['sort_order'])
        $order1=$_POST['sort_order'];
    else
        $order1='DESC';    
    if($_POST['sort_order']=='ASC')
    {
        $order='DESC';
        $img = "<img src='images/uparrow.png' border='0'>";
    }
    else if($_POST['sort_order']=='DESC')
    {
        $order='ASC';
        $img = "<img src='images/downarrow.png' border='0'>";
    }
    else
    {
        $order='DESC';
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
    if($_POST['sort_field']=='event_code')
        $img1 = $img;
    
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='event_code'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "event_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
    $recListResponse= $patientsClass->GetPatientEventList($recArgs);
    // var_dump($recListResponse);
    
   // echo '<pre>';
   // print_r($recArgs);
  //  echo '</pre>';
    
    
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
        echo '<div class="table-responsive"><table class="table table-hover table-bordered">
                <tr> 
                    <th><a href="javascript:void(0);" onclick="changePagination(\'EventsListing\',\'include_events.php\',\'\',\'\',\''.$order.'\',\'event_code\');" style="color:#00cfcb;">Event Id '.' '.$img1.'</a></th>
                    <th>Caller Name</th>
                    <th>Purpose</th>
                    <th>Event Date</th>
                    <th>Note</th>
                    <th>Description</th> 
                    <th width="10%">Added Date</th>   
                    <th width="7%">Status</th>   
                    <th class="'.$col_class.'">Action</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
           $event_id=$recListValue['event_id']; 
            echo '<tr id="EventRecord_'.$event_id.'">
                    <td>'.$recListValue['event_code'].'</td>
                    <td>'.$recListValue['callerLName']." ".$recListValue['callerFName']." ".$recListValue['callerMName'].'</td>
                    <td>'.$recListValue['purposeNm'].'</td>
                    <td>'.date('d M Y',strtotime($recListValue['event_date'])).'</td>
                    <td>'.$recListValue['note'].'</td>
                    <td>'.$recListValue['description'].'</td>
                    <td>'.date('d M Y',strtotime($recListValue['added_date'])).'</td>
                    <td>'.$recListValue['statusVal'].'</td>';
                    echo '<td>
                              <ul class="actionlist">
                                <li><a href="manage_event_summary.php?patient_id='.base64_encode($recListValue['patient_id']).'&event_id='.base64_encode($event_id).'" data-toggle="tooltip" title="View Event"><img src="images/icon-view.png"  alt="View Event"></a></li>';
                    if($del_visible=='Y') { echo '<li><a href="javascript:void(0);" onclick="return change_status('.$event_id.','.$recListValue['status'].',\'Delete\');" data-toggle="tooltip" title="Delete"><img src="images/icon-delete.png"  alt="Delete"></a></li>'; }
                    else {  echo '<li><a href="javascript:void(0);"></a></li>'; }         
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
                                    <select name="show_records" onchange="changePagination(\'EventsListing\',\'include_events.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'EventsListing\',\'include_events.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'EventsListing\',\'include_events.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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