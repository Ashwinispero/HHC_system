<?php require_once('inc_classes.php'); 
        require_once '../classes/consultantsClass.php';
        $consultantsClass = new consultantsClass();
        
        if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1')
      {
          $col_class="icon3";
          $del_visible="Y";
      }
      else 
      {
         $col_class="icon2"; 
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
    $_SESSION['consultant_list_args'] = $recArgs;
    $recListResponse= $consultantsClass->ConsultantsList($recArgs);
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
                    <th width="10%"><a href="javascript:void(0);" onclick="changePagination(\'ConsultantsListing\',\'include_consultants.php\',\'\',\'\',\''.$order.'\',\'name\');" style="color:#00cfcb;">Name '.' '.$img1.'</a></th>
                     <th width="10%">Hospital</th>
					<th width="10%"><a href="javascript:void(0);" onclick="changePagination(\'ConsultantsListing\',\'include_consultants.php\',\'\',\'\',\''.$order.'\',\'email_id\');" style="color:#00cfcb;">Email Id '.' '.$img2.'</a></th>
                    <th width="11%"><a href="javascript:void(0);" onclick="changePagination(\'ConsultantsListing\',\'include_consultants.php\',\'\',\'\',\''.$order.'\',\'phone_no\');" style="color:#00cfcb;">Phone No '.' '.$img3.'</a></th>
                   
				   <th width="10%">Mobile No</th>
                    <th width="8%">Work Phone</th>
                    <th width="11%">Work Email</th>   
                    <th width="10%">Work address</th>  
					 <th width="6%">Charges</th>
                    <th width="7%">Type</th>
                    <th class="'.$col_class.'">Action</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
            
           $doctors_consultants_id=$recListValue['doctors_consultants_id']; 
		   
			$query=mysql_query("SELECT * FROM sp_doctors_consultants where doctors_consultants_id='$doctors_consultants_id' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			$telephonic_consultation_fees=$row['telephonic_consultation_fees'];
			$hospital_id=$row['hospital_id'];
			
		/*	$query=mysql_query("SELECT * FROM sp_hospitals where hospital_id='$hospital_id' ") or die(mysql_error());
			$row = mysql_fetch_array($query) or die(mysql_error());
			
			$hospital_Name=$row['hospital_name'];
		*/	
            echo '<tr id="ConsultantRecord_'.$doctors_consultants_id.'">
                    <td width="10%">';
                        if(!empty($recListValue['name'])) { echo $recListValue['name']." "; }
                        if(!empty($recListValue['first_name'])) { echo $recListValue['first_name']." "; }
                        if(!empty($recListValue['middle_name'])) { echo $recListValue['middle_name']; }
                echo '</td>
				 <td>'.$hospital_Name.'</td>
                    <td>'.$recListValue['email_id'].'</td>
                    <td>'.$recListValue['phone_no'].'</td>
                    <td>'.$recListValue['mobile_no'].'</td>
                    <td>'.$recListValue['work_phone_no'].'</td>
                    <td>'.$recListValue['work_email_id'].'</td>
                    <td>'.$recListValue['work_address'].'</td>
					 <td>'.$telephonic_consultation_fees.'</td>
					
                    <td>'.$recListValue['typeVal'].'</td>';
                    echo '<td>
                              <ul class="actionlist">';
                                    if($recListValue['status']=='1')
                                        echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return change_status('.$doctors_consultants_id.','.$recListValue['status'].',\'Inactive\');" title="Active"><img src="images/icon-active.png"  alt="Active"></a></li>';
                                    if($recListValue['status']=='2')
                                        echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return change_status('.$doctors_consultants_id.','.$recListValue['status'].',\'Active\');" title="Inactive"><img src="images/icon-inactive.png"  alt="Inactive"></a></li>'; 
                                    if($recListValue['status']=='4')
                                        echo '<li><a href="javascript:void(0);" data-toggle="tooltip" onclick="return vw_pending_consultant('.$doctors_consultants_id.');" title="pending"><img src="images/pending.png"  alt="Inactive"></a></li>'; 
                                          
                                    echo '<li><a href="javascript:void(0);" onclick="return vw_add_consultant('.$doctors_consultants_id.');" data-toggle="tooltip" title="pending"><img src="images/icon-edit.png" width="22" height="23" alt="Edit"></a></li>';
                                    // if($del_visible=='Y') {  echo '<li><a href="javascript:void(0);" onclick="return change_status('.$doctors_consultants_id.','.$recListValue['status'].',\'Delete\');" data-toggle="tooltip" title="Delete"><img src="images/icon-delete.png"  alt="Delete"></a></li>';  }
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
                                    <select name="show_records" onchange="changePagination(\'ConsultantsListing\',\'include_consultants.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'ConsultantsListing\',\'include_consultants.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'ConsultantsListing\',\'include_consultants.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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