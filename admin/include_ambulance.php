<?php require_once('inc_classes.php'); 
        require_once '../classes/AmbulanceClass.php';
        $AmbulanceClass = new AmbulanceClass();
        
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
    if($_POST['SearchKey'] && $_POST['SearchKey']!="undefined")
        $search_Value=$_POST['SearchKey'];
    else
        $search_Value="";
    
    if($_POST['reference_type'] && $_POST['reference_type']!="undefined" && $_POST['reference_type']!="null")
        $reference_type_Value=$_POST['reference_type'];
    else 
        $reference_type_Value="";
     
    if($_POST['location_id'] && $_POST['location_id']!="undefined" && $_POST['location_id']!="null")
        $location_Value=$_POST['location_id'];
    else 
        $location_Value="";
    
    if($_POST['Prof_service_id'] && $_POST['Prof_service_id']!="undefined" && $_POST['Prof_service_id']!="null")
        $service_Value=$_POST['Prof_service_id'];
    else 
        $service_Value="";
      
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['ref_type_Value']=$reference_type_Value;
    $recArgs['location_Value']=$location_Value;
    $recArgs['service_Value']=$service_Value;
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
    if($_POST['sort_field']=='t1.professional_code')
        $img1 = $img;
    if($_POST['sort_field']=='t1.name')
        $img2 = $img;
    if($_POST['sort_field']=='t1.mobile_no')
        $img3 = $img;
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='t1.professional_code' || $_POST['sort_field']=='t1.name' || $_POST['sort_field']=='t1.mobile_no'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "t1.service_professional_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
    $_SESSION['professional_list_args'] = $recArgs;
    $recListResponse = $AmbulanceClass->AmbulanceList($recArgs);
    //echo '<pre>';
    //print_r($recListResponse);
    //echo '</pre>';
    //exit;
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
                    <th width="15%">Ambulance No</th>
                    <th width="12%">Mobile No</th>
                    <th width="20%">Base Location </th>
                    <th width="15%">Ambulance Type</th>
                    <th width="12%">Status</th>
                   
                    <th width="20%">Action</th>
                </tr>';   //<th width="9%">Type</th>
        foreach ($recList as $recListKey => $recListValue) 
        { 
            
            echo '<tr id="ProfessionalRecord_'.$recListValue['id'].'">
                    <td>'.$recListValue['amb_no'].'</td>
                    <td>'.$recListValue['mob_no'].'</td>
                    <td>'.$recListValue['bs_nm'].'</td>
                    <td>'.$recListValue['amb_status'].'</td>
                    <td>'.$recListValue['amb_type'].'</td>
                     ';
                    echo '<td>
                              <ul class="actionlist">
                                    <li><a href="javascript:void(0);" onclick="return view_professional('.$service_professional_id.');" data-toggle="tooltip" title="View Professional"><img src="images/icon-view.png" alt="View Professional"></a></li>';
                                    echo '<li><a href="javascript:void(0);" onclick="return vw_add_professional('.$service_professional_id.');" data-toggle="tooltip" title="Edit"><img src="images/icon-edit.png" alt="Edit"></a></li>';
                                    // if($del_visible=='Y'){ echo '<li><a href="javascript:void(0);" onclick="return change_status('.$service_professional_id.','.$recListValue['status'].',\'Delete\');" data-toggle="tooltip" title="Delete"><img src="images/icon-delete.png" alt="Delete"></a></li>'; }
                                    // else { echo '<li><a href="javascript:void(0);"></a></li>'; } 
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
                                    <select name="show_records" onchange="changePagination(\'ProfessionalsListing\',\'include_professionals.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'ProfessionalsListing\',\'include_professionals.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'ProfessionalsListing\',\'include_professionals.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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