<?php
require_once 'inc_classes.php';
require_once '../classes/eventClass.php';
$eventClass=new eventClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['event_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['event_id'] =$_REQUEST['event_id'];
        if($_REQUEST['actionval']=='Active')
            $arr['status']='1';
        if($_REQUEST['actionval']=='Inactive')
           $arr['status']='2';
        if($_REQUEST['actionval']=='Delete')
           $arr['status']='3';
        if($_REQUEST['actionval']=='Revert')
        {
            if(!empty($_REQUEST['curr_status']))
                $arr['status']=$_REQUEST['curr_status'];
            else 
                $arr['status']='1';
        }
        if($_REQUEST['actionval']=='CompleteDelete')
           $arr['status']='5';
        
        $arr['curr_status']=$_REQUEST['curr_status'];
        $arr['login_user_id']=$_REQUEST['login_user_id'];
        $arr['istrashDelete']=$_REQUEST['trashDelete'];

        $ChangeStatus =$eventClass->ChangeStatus($arr);
        if(!empty($ChangeStatus))
        {
            echo 'success';
            exit;
        }
        else
        {
            echo 'error';
            exit;
        }
    } 
}
?>