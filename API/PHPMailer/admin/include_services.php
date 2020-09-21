<?php require_once('inc_classes.php'); 
        require_once '../classes/adminClass.php';
        $adminClass = new adminClass();
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
<script src="js/action.js"></script>
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
    if($_POST['sort_field']=='service_title')
        $img1 = $img;
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='service_title'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "service_title";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
    //print_r($recArgs);
    $recListResponse= $adminClass->selectAllServices($recArgs);
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
        echo '<div class="table-responsive"><table id="mytable" class="table-category table table-hover table-bordered">
                <tr>
                    <th><a href="javascript:void(0);" onclick="changePagination(\'ServicesListing\',\'include_services.php\',\'\',\'\',\''.$order.'\',\'service_title\');" style="color:#00cfcb;">Service Title '.' '.$img1.'</a></th>';
                   echo ' 
                        <th class="'.$col_class.'">Action</th>
                </tr>';
        foreach ($recList as $recListKey => $recListValue) 
        {
            $service_id = $recListValue['service_id'];     
            $recArr['service_id'] = $service_id;
            $AllSubServices = $adminClass->selectSubServices($recArr); 
                
            echo '<tr data-depth="0" class="collapse1 level0"  id="NewsRecord_'.$service_id.'">
                    <td class="toggle collapse">';
                    if(!empty($AllSubServices))
                        echo '<span></span>';
                    echo '<span class="category-holder" id="Category_Nm_'.$categoryId.'">'.$recListValue['service_title'].'</span></td>';                    
                    echo '<td >
                              <ul class="actionlist">
                                <li><a href="javascript:void(0);" data-toggle="tooltip" title="Add Sub Service" onclick="return addSubService('.$service_id.',0);" ><img src="images/icon-add.png"  alt="Add Sub Service"></a></li>';
                                if($recListValue['status']=='1')
                                {
                                    echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return change_status('.$service_id.','.$recListValue['status'].',2);" title="Active"><img src="images/icon-active.png"  alt="Active"></a></li>';
                                }
                                if($recListValue['status']=='2')
                                {
                                    echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return change_status('.$service_id.','.$recListValue['status'].',1);" title="Inactive"><img src="images/icon-inactive.png"  alt="Inactive"></a></li>'; 
                                }
                                echo '<li><a href="javascript:void(0);" onclick="return add_services('.$service_id.');" data-toggle="tooltip" title="Edit Service"><img src="images/icon-edit.png"  alt="Edit Service"></a></li> ';
                                if($del_visible=='Y') { echo '<li><a href="javascript:void(0);" onclick="return change_status('.$service_id.','.$recListValue['status'].',3);" data-toggle="tooltip" title="Delete Service"><img src="images/icon-delete.png"  alt="Delete Service"></a></li>'; }
                                else { echo '<li><a href="javascript:void(0);"></a></li>'; }
                                
                    echo '</ul></td>
                </tr>';
                
                if(!empty($AllSubServices))
                {
                    foreach($AllSubServices as $key=>$valSubCategoryId)
                    {
                        $sub_service_id = $valSubCategoryId['sub_service_id'];
                        echo '<tr data-depth="1" class="collapse1 level1">                    
                                <td id="Category_Nm_'.$sub_service_id.'" width="50%">'.$valSubCategoryId['recommomded_service'].'</td>
                                <td>
                                    <ul class="actionlist">
                                        <li><a title="" href="javascript:void(0);"><img alt="Add Sub Category" src="images/icon-add-desable.png"></a></li>';
                                        if($valSubCategoryId['status']=='1')
                                        {
                                            echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return change_SubService_status('.$sub_service_id.',2);" title="Active"><img src="images/icon-active.png"  alt="Active"></a></li>';
                                        }
                                        if($valSubCategoryId['status']=='2')
                                        {
                                            echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return change_SubService_status('.$sub_service_id.',1);" title="Inactive"><img src="images/icon-inactive.png"  alt="Inactive"></a></li>'; 
                                        }                
                                        if($valSubCategoryId['status'] !='3')
                                        {
                                            echo '<li><a href="javascript:void(0);" onclick="return addSubService('.$service_id.','.$sub_service_id.');" data-toggle="tooltip" title="Edit"><img src="images/icon-edit.png" alt="Edit"></a></li>';
                                            if($del_visible=='Y') { echo '<li><a href="javascript:void(0);" onclick="return change_SubService_status('.$sub_service_id.',3);" data-toggle="tooltip" title="Delete"><img src="images/icon-delete.png"  alt="Delete"></a></li>'; }
                                            else { echo '<li><a href="javascript:void(0);"></a></li>'; }
                                        }
                                        else 
                                        {
                                           echo '<li><a title="" data-toggle="tooltip" href="javascript:void(0);" data-original-title="Edit"><img alt="Edit" src="images/icon-edit_desable.png"></a></li>'
                                                .'<li><a href="javascript:void(0);" onclick="return change_SubService_status('.$sub_service_id.',5);" data-toggle="tooltip" title="Revert"><img src="images/revert.png"  alt="Revert"></a></li>';
                                           if($del_visible=='Y') { echo '<li><a href="javascript:void(0);" onclick="return change_SubService_status('.$sub_service_id.',4);" data-toggle="tooltip" title="Permanent Delete"><img src="images/icon-delete.png"  alt="Permanent Delete"></a></li>'; }
                                           else { echo '<li><a href="javascript:void(0);"></a></li>'; }
                                        }

                                    echo '</ul>
                                </td>
                             </tr>';
                    }
                }
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
                                    <select name="show_records" onchange="changePagination(\'ServicesListing\',\'include_services.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'ServicesListing\',\'include_services.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'ServicesListing\',\'include_services.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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