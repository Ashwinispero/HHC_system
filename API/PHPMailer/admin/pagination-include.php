<?php
//------------if paging request -----------------
    if($_REQUEST['show_records'] && $_REQUEST['show_records']!="undefined")
    {
        $_SESSION['per_page'] =$_REQUEST['show_records']; 
        if($_REQUEST['show_records']=="All")
            $_SESSION['per_page']=$GLOBALS["records_all"];
    }
    if($_SESSION['per_page'])
        define('PAGE_PER_NO',$_SESSION['per_page']);
    else
        define('PAGE_PER_NO',10); // number of results per page.

    if(isset($_POST['pageId']) && !empty($_POST['pageId']))
        $pageId=$_POST['pageId'];
    else
        $pageId='1';
    $start = ($pageId-1)*PAGE_PER_NO;
//-----------------------------------------------------
?>