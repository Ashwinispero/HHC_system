<?php require_once('inc_classes.php'); 
      require_once '../classes/professionalsClass.php';  
      $professionalsClass = new professionalsClass();
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
    
    if($_POST['sort_field']=='professional_code') {
        $img1 = $img;
	}
    if($_POST['sort_field']=='professional_name') {
        $img2 = $img;
	}
    if($_POST['sort_field']=='professional_mobile_no') {
        $img3 = $img;
	}
    if($_POST['sort_field']=='feedback') {
        $img4 = $img;
	}
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='professional_code' || $_POST['sort_field']=='professional_name' || $_POST['sort_field']=='professional_mobile_no' || $_POST['sort_field']=='feedback'))
    {
        $recArgs['filter_name'] = $_POST['sort_field'];
        $recArgs['filter_type'] = $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name'] = "app_feedback_id";
        $recArgs['filter_type'] = "DESC";
    }
	
	
    //var_dump($recArgs);
    //print_r($recArgs);
    $_SESSION['professionals_feedback_list_args'] = $recArgs;
	
	//echo '<pre>';
    //print_r($recListResponse);
	//echo '</pre>';
	//exit;
	
    $recListResponse = $professionalsClass->ProfessionalsFeedbackList($recArgs);
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
                    <th><a href="javascript:void(0);" onclick="changePagination(\'professionalsFeedbackListing\',\'include_professionals_feedback.php\',\'\',\'\',\''.$order.'\',\'professional_code\');" style="color:#00cfcb;">Prof. Id '.' '.$img1.'</a></th>
                    <th><a href="javascript:void(0);" onclick="changePagination(\'professionalsFeedbackListing\',\'include_professionals_feedback.php\',\'\',\'\',\''.$order.'\',\'professional_name\');" style="color:#00cfcb;">Prof. Name '.' '.$img2.'</a></th>
                    <th><a href="javascript:void(0);" onclick="changePagination(\'professionalsFeedbackListing\',\'include_professionals_feedback.php\',\'\',\'\',\''.$order.'\',\'professional_mobile_no\');" style="color:#00cfcb;">Mobile No '.' '.$img3.'</a></th>
                    <th><a href="javascript:void(0);" onclick="changePagination(\'professionalsFeedbackListing\',\'include_professionals_feedback.php\',\'\',\'\',\''.$order.'\',\'feedback\');" style="color:#00cfcb;">Feedback '.' '.$img4.'</a></th>
                    <th>Added Date</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
            
           $feedbackId = $recListValue['app_feedback_id'];

            echo '<tr id="professionalFeedbackRecord_' . $feedbackId  . '">
					<td>'.$recListValue['professional_code'].'</td>
                    <td>'.$recListValue['professional_name'] .'</td>
                    <td>'.$recListValue['professional_mobile_no'] .'</td>
                    <td>'.$recListValue['feedback'].'</td>
                    <td>'.date('d M Y',strtotime($recListValue['added_date'])).'</td>
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
                                    <select name="show_records" onchange="changePagination(\'professionalsFeedbackListing\',\'include_professionals_feedback.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'professionalsFeedbackListing\',\'include_professionals_feedback.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'professionalsFeedbackListing\',\'include_professionals_feedback.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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