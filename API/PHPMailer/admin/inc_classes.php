<?php session_start();
    $show_records = 10;
    $escapeCharacters=array('?','&','/','\\','%');      

    include(dirname(__FILE__) . "/../classes/config.php"); 
    include(dirname(__FILE__) . "/../classes/functions.php");
    require_once('../classes/AbstractDB.php');
    $db = new AbstractDB();
    $db->connect();    
    $page_name = basename($_SERVER['PHP_SELF']);
?>