<?php require_once('inc_classes.php'); 
        require_once '../classes/professionalsClass.php';
        $professionalsClass = new professionalsClass();
        
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
    if ($_POST['SearchKey'] && $_POST['SearchKey']!="undefined")
        $search_Value=$_POST['SearchKey'];
    else
        $search_Value="";

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
 
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['admin_id']=$_SESSION['admin_user_id'];
    $recArgs['searchfromDate'] = $searchfromDate;
    $recArgs['searchToDate']   = $searchToDate;

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
    if($_POST['sort_field']=='sp.professional_code')
        $img1 = $img;

    if($_POST['sort_field']=='sp.name')
        $img2 = $img;
    
    if($_POST['sort_field']=='transaction_id')
        $img3 = $img;

    if($_POST['sort_field']=='transcation_amount')
        $img4 = $img;

    if($_POST['sort_field']=='transStatus')
        $img5 = $img;

    if($_POST['sort_field']=='transcation_date')
        $img6 = $img;
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field'] == 'sp.professional_code' || $_POST['sort_field'] == 'sp.name' || 
    $_POST['sort_field'] == 'transaction_id' || $_POST['sort_field'] == 'transcation_amount' || $_POST['sort_field'] == 'transStatus' ||
    $_POST['sort_field'] == 'transcation_date'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "transcation_date";
        $recArgs['filter_type']= "DESC";
    }


    $_SESSION['professional_paytm_payment_list_args'] = $recArgs;
    $recListResponse = $professionalsClass->professionalsPaytmPaymentList($recArgs);
    $recList = $recListResponse['data'];
    $recListCount = $recListResponse['count'];

    //echo '<pre>';
    //print_r($recListResponse);
    //echo '</pre>';
    //exit;

    if($recListCount > 0)
    {
        $paginationCount = getAjaxPagination($recListCount);
    }
    if(!$recListCount)
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    if($recListCount)
    {      
        echo '<table class="table table-hover table-bordered">
                <tr> 
                    <th>
                        <a href="javascript:void(0);" onclick="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\'\',\'\',\''.$order.'\',\'sp.professional_code\');" style="color:#00cfcb;">Professional Id '.' '.$img1.'</a>
                    </th>
                    <th>
                        <a href="javascript:void(0);" onclick="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\'\',\'\',\''.$order.'\',\'professionalName\');" style="color:#00cfcb;">Professional Name '.' '.$img2.'</a>
                    </th>
                    <th>
                        <a href="javascript:void(0);" onclick="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\'\',\'\',\''.$order.'\',\'transaction_id\');" style="color:#00cfcb;">Transaction Id '.' '.$img3.'</a>
                    </th>
                    <th>
                        <a href="javascript:void(0);" onclick="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\'\',\'\',\''.$order.'\',\'transcation_amount\');" style="color:#00cfcb;">Amount '.' '.$img4.'</a>
                    </th>
                    <th>
                        <a href="javascript:void(0);" onclick="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\'\',\'\',\''.$order.'\',\'transStatus\');" style="color:#00cfcb;">Status '.' '.$img5.'</a>
                    </th>
                    <th>
                        <a href="javascript:void(0);" onclick="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\'\',\'\',\''.$order.'\',\'transcation_date\');" style="color:#00cfcb;">Date '.' '.$img6.'</a>
                    </th>
                    <th> Action
                    </th>
                </tr>';
        foreach ($recList as $recListKey => $recListValue) 
        { 
            $service_professional_id = $recListValue['service_professional_id'];

            echo '<tr id="ProfessionalPaymentRecord_'.$service_professional_id.'">
                        <td>' . $recListValue['professional_code'] . '</td>
                        <td>' . $recListValue['professional_name'] . '<br>'. '(' . $recListValue['professional_mobile_no'] . ')'. '</td>
                        <td>' . $recListValue['bank_transaction_id'] . '</td>
                        <td>' . $recListValue['transaction_Amount'] . '</td>
                        <td>' . $recListValue['transStatus'] . '</td>
                        <td>' . date('d M Y h:i A', strtotime($recListValue['transcation_date'])) . '</td>
                        <td>
                            <ul class="actionlist">
                                <li>
                                    <a href="javascript:void(0);" onclick="return view_detail_paytm_payment(' . $recListValue['orderId'] . ');" data-toggle="tooltip" title="" data-original-title="View payment details"><img src="images/icon-view.png" alt="View payment details"></a>
                                </li>
                            </ul>
                        </td>
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
                                    <select name="show_records" onchange="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'professionalsPaytmPaymentListing\',\'include_paytm_payment_with_professional.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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