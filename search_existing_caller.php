<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass();

if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{    
    if(1)//(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
    include "pagination-include.php";
    if($_POST['SearchKey'] && $_POST['SearchKey']!="undefined")
        $search_Value=$_POST['SearchKey'];
    else
        $search_Value=""; 
   
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['phone_no']=$_POST['phone_no'];
    $recArgs['employee_id']=$_SESSION['employee_id'];
    $recArgs['filter_type']= "DESC";
   // var_dump($recArgs);die();
  // print_r($recArgs);
    $recListResponseSear= $patientsClass->SearchCallerHistory($recArgs);
     //print_r($recListResponse);
    $recListSearch=$recListResponseSear['data'];
    $recListCount=$recListResponseSear['count'];
    if($recListCount > 0)
    {
        $paginationCount=getAjaxPagination($recListCount);
    }
    if(!$recListCount)
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    if($recListCount)
    {
    echo '
            <h2 class="page-title">Caller History Results</h2>
            <div class="row">
            <div id="freewall" class="free-wall">';
            foreach ($recListSearch as $recListKey => $recListValueSearch) 
            {
            echo '<div class="brick">
                    <div class="search-result" >
           		<div class="result-list">
                            <label>Event Code:</label>
                            <div class="search-text">'.$recListValueSearch['event_code'].'</div>
                        </div>
                        <div class="result-list">
                            <label>Purpose Of Call:</label> 
                            <div class="search-text">'.$recListValueSearch['purpose_name'].'</div>
                        </div>
                        <div class="result-list">
                        <label>DateTime:</label> 
                        <div class="search-text">'.$recListValueSearch['added_date'].'</div>
                    </div>
                        <div class="result-list">
                            <label>Patinet Name:</label> 
                            <div class="search-text">'.$recListValueSearch['name']." ".$recListValueSearch['first_name']." ".$recListValueSearch['middle_name'].'</div>
                        </div>
                       
                        <div class="result-list">
                            <label>Contact No:</label>
                            <div class="search-text">'.$recListValueSearch['mobile_no'].'</div>
                        </div>
                        <div class="result-list last">
                            <label>Address:</label>
                            <div class="search-text">'.$recListValueSearch['residential_address'].'</div>
                        </div>
                        <div class="clearfix"></div>';
                       // <div class="text-right padding10"><input type="submit" class="btn btn-select" value="Select" onclick="return SeclectPatient('.$recListValueSearch['patient_id'].')"></div>
                    echo '</div> 
		</div>';
            }
		    
        echo '</div>
            
            </div> ';
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
                                <label class="select-box-lbl">
                                    <select class="form-control" name="show_records" onchange="changePagination(\'ExistingCallerForm\',\'include_existing_caller.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                <td align="right" onclick="changePagination(\'ExistingCallerForm\',\'include_existing_caller.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
        }
        else
        {
            echo '
                <td style="width:10px;"></td>
                <td align="right" valign="middle"><input type="button" class="btn" value="<"></td>';
        }
        if($pageId!=($paginationCount))
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input onclick="changePagination(\'ExistingCallerForm\',\'include_existing_caller.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
        }
        else
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input type="button" class="btn" value=">"></td>';
        }
        echo '          </tr>
                    </tbody>
                </table>
            </div>';
    }
    ?>
        
<!--          <div class="text-right">
            <nav>
              <ul class="pagination pagination-sm">
                <li><a href="#">Previous</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">Next</a></li>
              </ul>
            </nav>
          </div>-->
    <?php }
}?>