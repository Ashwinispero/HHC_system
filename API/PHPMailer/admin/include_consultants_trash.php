<?php require_once('inc_classes.php'); 
        require_once '../classes/consultantsClass.php';
        $consultantsClass = new consultantsClass();
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
      
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['isTrash']='1';
    $recArgs['admin_id']=$_SESSION['admin_user_id'];
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
    if($_POST['sort_field']=='name')
        $img1 = $img;
    if($_POST['sort_field']=='email_id')
        $img2 = $img;
    if($_POST['sort_field']=='phone_no')
        $img3 = $img;
 
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='name' || $_POST['sort_field']=='email_id' || $_POST['sort_field']=='phone_no'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "doctors_consultants_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
    $recListResponse= $consultantsClass->ConsultantsList($recArgs);
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
        echo '<table class="table table-hover table-bordered">
                <tr> 
                    <th width="20%"><a href="javascript:void(0);" onclick="changePagination(\'ConsultantsTrashListing\',\'include_consultants_trash.php\',\'\',\'\',\''.$order.'\',\'name\');" style="color:#00cfcb;">Name '.' '.$img1.'</a></th>
                    <th width="20%"><a href="javascript:void(0);" onclick="changePagination(\'ConsultantsTrashListing\',\'include_consultants_trash.php\',\'\',\'\',\''.$order.'\',\'email_id\');" style="color:#00cfcb;">Email Id '.' '.$img2.'</a></th>
                    <th width="13%"><a href="javascript:void(0);" onclick="changePagination(\'ConsultantsTrashListing\',\'include_consultants_trash.php\',\'\',\'\',\''.$order.'\',\'phone_no\');" style="color:#00cfcb;">Phone No '.' '.$img3.'</a></th>
                    <th width="11%">Mobile No</th>
                    <th width="10%">Type</th>
                    <th width="10%">Added Date</th>   
                    <th width="6%">Status</th>   
                    <th width="10%">Action</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
            
           $doctors_consultants_id=$recListValue['doctors_consultants_id']; 
            echo '<tr id="ConsultantRecord_'.$doctors_consultants_id.'">
                    <td width="20%">';
                        if(!empty($recListValue['name'])) { echo $recListValue['name']." "; }
                        if(!empty($recListValue['first_name'])) { echo $recListValue['first_name']." "; }
                        if(!empty($recListValue['middle_name'])) { echo $recListValue['middle_name']; }
		    echo '</td>
                    <td width="20%">'.$recListValue['email_id'].'</td>
                    <td width="13%">'.$recListValue['phone_no'].'</td>
                    <td width="11%">'.$recListValue['mobile_no'].'</td>
                    <td width="10%">'.$recListValue['typeVal'].'</td>
                    <td width="10%">'.date('d M Y',strtotime($recListValue['added_date'])).'</td>
                    <td width="6%">'.$recListValue['statusVal'].'</td>';
                    echo '<td width="10%">
                              <ul class="actionlist">
                                <li><a href="javascript:void(0);" onclick="return change_status('.$doctors_consultants_id.','.$recListValue['isDelStatus'].',\'Revert\');" data-toggle="tooltip" title="Revert"><img src="images/revert.png"  alt="Revert"></a></li>
                                <li><a href="javascript:void(0);" onclick="return change_status('.$doctors_consultants_id.','.$recListValue['status'].',\'CompleteDelete\');" data-toggle="tooltip" title="Delete"><img src="images/icon-delete.png"  alt="Delete"></a></li>'; 
                        echo '</ul></td>
                  </tr>';
        }
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
                                <label>
                                    <select name="show_records" onchange="changePagination(\'ConsultantsTrashListing\',\'include_consultants_trash.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'ConsultantsTrashListing\',\'include_consultants_trash.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'ConsultantsTrashListing\',\'include_consultants_trash.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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