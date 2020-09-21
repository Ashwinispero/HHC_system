<?php 
require_once('inc_classes.php'); 
require_once 'classes/knowledgedocsClass.php';
$knowledgedocsClass=new knowledgedocsClass();
  
if(!$_SESSION['employee_id'])
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
    if($_POST['sort_field']=='title')
        $img1 = $img;
    

    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='title'))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "document_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
   //print_r($recArgs);
    $recListResponse= $knowledgedocsClass->knowledgeDocsList($recArgs);
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
        echo '<table id="logTable" class="table table-striped" cellspacing="0">
                <thead>
                    <tr>
                      <th>Title</th>
                      <th width="20%">Action</th>
                    </tr>
                </thead>'; 
       echo '<tbody>';
        foreach ($recList as $recListKey => $recListValue) 
        { 
           $knowledge_document_id=$recListValue['document_id']; 
           //<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="View Document" onclick="javascript:return OpenPDF('.$knowledge_document_id.');"><span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></a>
            echo '<tr>
                      <td>'.$recListValue['title'].'</td>
                      <td>
                        <a href="'.$recListValue['doc_file'].'" data-toggle="tooltip" data-placement="top" title="View Document" target="_blank"><span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></a> 
                      </td>
                  </tr>';
        }
        echo '</tbody>';
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
                                <label class="select-box-lbl">
                                    <select class="form-control" name="show_records" onchange="changePagination(\'KnowledgeDocsListing\',\'include_knowledge_documents.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
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
                                    <td align="right" onclick="changePagination(\'KnowledgeDocsListing\',\'include_knowledge_documents.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
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
                                    <td valign="middle"><input onclick="changePagination(\'KnowledgeDocsListing\',\'include_knowledge_documents.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
                            }
                            else
                            {
                                echo '
                                    <td style="width:5px;"></td>
                                    <td valign="middle"><input type="button" class="btn" value=">"></td>';
                            }
                    echo '</tr>
                </tbody>
            </table>
        </div>';
    }
}?>