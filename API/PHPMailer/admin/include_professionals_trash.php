<?php require_once('inc_classes.php'); 
        require_once '../classes/professionalsClass.php';
        $professionalsClass = new professionalsClass();
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
      
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['ref_type_Value']=$reference_type_Value;
    $recArgs['location_Value']=$location_Value;
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
    if($_POST['sort_field']=='professional_code')
        $img1 = $img;
    if($_POST['sort_field']=='name')
        $img2 = $img;
    if($_POST['sort_field']=='work_email_id')
        $img3 = $img;
    if($_POST['sort_field']=='mobile_no')
        $img4 = $img;
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='professional_code' || $_POST['sort_field']=='name' || $_POST['sort_field']=='email_id' || $_POST['sort_field']=='mobile_no' || $_POST['sort_field']=='dob' || $_POST['sort_field']=='work_email_id' || $_POST['sort_field']=='work_phone_no'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "service_professional_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
    $recListResponse= $professionalsClass->ProfessionalsList($recArgs);
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
                    <th width="10%"><a href="javascript:void(0);" onclick="changePagination(\'ProfessionalsTrashListing\',\'include_professionals_trash.php\',\'\',\'\',\''.$order.'\',\'professional_code\');" style="color:#00cfcb;">Prof Id '.' '.$img1.'</a></th>
                    <th width="15%"><a href="javascript:void(0);" onclick="changePagination(\'ProfessionalsTrashListing\',\'include_professionals_trash.php\',\'\',\'\',\''.$order.'\',\'name\');" style="color:#00cfcb;">Name '.' '.$img2.'</a></th>
                    <th width="15%"><a href="javascript:void(0);" onclick="changePagination(\'ProfessionalsTrashListing\',\'include_professionals_trash.php\',\'\',\'\',\''.$order.'\',\'work_email_id\');" style="color:#00cfcb;">Email Id '.' '.$img3.'</a></th>
                    <th width="11%"><a href="javascript:void(0);" onclick="changePagination(\'ProfessionalsTrashListing\',\'include_professionals_trash.php\',\'\',\'\',\''.$order.'\',\'mobile_no\');" style="color:#00cfcb;">Mobile No '.' '.$img4.'</a></th>
                    <th width="19%">Services</th> 
                    <th width="9%">Address</th>
                    <th width="9%">Type</th>
                    <th class="icon2">Action</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
           $service_professional_id=$recListValue['service_professional_id']; 
            echo '<tr id="ProfessionalRecord_'.$service_professional_id.'">
                    <td>'.$recListValue['professional_code'].'</td>
                    <td>';
                        if(!empty($recListValue['name'])) { echo $recListValue['name']." ";}
                        if(!empty($recListValue['first_name'])) { echo $recListValue['first_name']." ";}
                        if(!empty($recListValue['middle_name'])) { echo $recListValue['middle_name']." ";}
                        echo '</td>
                    <td>'.$recListValue['work_email_id'].'</td>
                    <td>'.$recListValue['mobile_no'].'</td>
                    <td>'.$recListValue['Services'].'</td>
                    <td>'.$recListValue['address'].'</td>
                    <td>'.$recListValue['typeVal'].'</td>';
                    echo '<td>
                              <ul class="actionlist">
                                    <li><a href="javascript:void(0);" onclick="return view_professional('.$service_professional_id.');" data-toggle="tooltip" title="View Professional"><img src="images/icon-view.png" alt="View Professional"></a></li>
                                    <li><a href="javascript:void(0);" onclick="return change_status('.$service_professional_id.','.$recListValue['isDelStatus'].',\'Revert\');" data-toggle="tooltip" title="Revert"><img src="images/revert.png"  alt="Revert"></a></li>
                                    <li><a href="javascript:void(0);" onclick="return change_status('.$service_professional_id.','.$recListValue['status'].',\'CompleteDelete\');" data-toggle="tooltip" title="Delete"><img src="images/icon-delete.png"  alt="Delete"></a></li>'; 
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
                                    <select name="show_records" onchange="changePagination(\'ProfessionalsTrashListing\',\'include_professionals_trash.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'ProfessionalsTrashListing\',\'include_professionals_trash.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'ProfessionalsTrashListing\',\'include_professionals_trash.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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