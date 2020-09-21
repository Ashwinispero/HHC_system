<?php require_once('inc_classes.php'); 
        require_once '../classes/testClass.php';
        $testClass = new testClass();
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
    if($_POST['sort_field']=='product')
        $img1 = $img;
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='product' || $_POST['sort_field']=='EAN13' || $_POST['sort_field']=='UPCA' || $_POST['sort_field']=='UPCE' || $_POST['sort_field']=='brand_name'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "product_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
    
    $recListResponse= $testClass->TestList($recArgs);
  
     var_dump($recListResponse);
    $recList=$recListResponse['data'];
    $exe_time=$recListResponse['timedata'];
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
                    <th width="10%"><a href="javascript:void(0);" onclick="changePagination(\'TestListing\',\'include_test.php\',\'\',\'\',\''.$order.'\',\'product\');" style="color:#00cfcb;">Product '.' '.$img1.'</a></th>
                    <th width="7%">EAN13</th>
                    <th width="7%">UPCA</th>
                    <th width="7%">UPCE</th> 
                    <th width="10%">Brand name</th>
                    <th width="10%">Description</th>
                    <th width="10%">Added Date</th> 
                    <th width="10%">Modified Date</th>    
                    <th width="7%">status</th>
                </tr>';   
        foreach ($recList as $recListKey => $recListValue) 
        { 
            
           $product_id=$recListValue['product_id']; 
            echo '<tr id="TestRecord_'.$product_id.'">
                    <td width="10%">'.substr($recListValue['product'], 0, 35).'</td>
                    <td width="7%">'.$recListValue['EAN13'].'</td>
                    <td width="7%">'.$recListValue['UPCA'].'</td>
                    <td width="7%">'.$recListValue['UPCE'].'</td>
                    <td width="10%">'.substr($recListValue['brand_name'], 0, 35).'</td>
                    <td width="10%">'.substr($recListValue['description'], 0, 35).'</td>
                    <td width="10%">'.date('d M Y',strtotime($recListValue['created'])).'</td>
                    <td width="10%">'.date('d M Y',strtotime($recListValue['modified'])).'</td>
                    <td width="7%">'.$recListValue['status'].'</td>
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
                                    <select name="show_records" onchange="changePagination(\'TestListing\',\'include_test.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'TestListing\',\'include_test.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                <td valign="middle"><input onclick="changePagination(\'TestListing\',\'include_test.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
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