<?php require_once('inc_classes.php'); 
        require_once '../classes/specialtyClass.php';
        $specialtyClass = new specialtyClass();
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
    if($_POST['sort_field']=='abbreviation')
        $img1 = $img;

    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='location' || $_POST['sort_field']=='pin_code'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "specialty_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
    $recListResponse= $specialtyClass->SpecialtyList($recArgs);
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
                    <th width="70%"><a href="javascript:void(0);" onclick="changePagination(\'SpecialtyListing\',\'include_specialty.php\',\'\',\'\',\''.$order.'\',\'abbreviation\');" style="color:#00cfcb;">Specialty  '.' '.$img1.'</a></th>
                    <th width="10%">Added Date</th>   
                    <th width="10%">Status</th>   
                    <th width="10%">Action</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
           $specialty_id=$recListValue['specialty_id']; 
            echo '<tr id="SpecialtyRecord_'.$location_id.'">
                    <td width="70%">'.$recListValue['abbreviation'].'</td>
                    <td width="10%">'.date('d M Y',strtotime($recListValue['added_date'])).'</td>
                    <td width="10%">'.$recListValue['statusVal'].'</td>';
                    echo '<td width="10%">
                              <ul class="actionlist">';
                                    if($recListValue['status']=='1')
                                        echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return change_status('.$specialty_id.','.$recListValue['status'].',\'Inactive\');" title="Active"><img src="images/icon-active.png"  alt="Active"></a></li>';
                                    if($recListValue['status']=='2')
                                        echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return change_status('.$specialty_id.','.$recListValue['status'].',\'Active\');" title="Inactive"><img src="images/icon-inactive.png"  alt="Inactive"></a></li>'; 
                                
                                    echo '<li><a href="javascript:void(0);" onclick="return vw_add_specialty('.$specialty_id.');" data-toggle="tooltip" title="Edit"><img src="images/icon-edit.png" width="22" height="23" alt="Edit"></a></li>
                                         <li><a href="javascript:void(0);" onclick="return change_status('.$specialty_id.','.$recListValue['status'].',\'Delete\');" data-toggle="tooltip" title="Delete"><img src="images/icon-delete.png"  alt="Delete"></a></li>'; 
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
                                    <select name="show_records" onchange="changePagination(\'SpecialtyListing\',\'include_specialty.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'SpecialtyListing\',\'include_specialty.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'SpecialtyListing\',\'include_specialty.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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