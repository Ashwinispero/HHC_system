<?php
    if($_SESSION['last_login_time'] && (time()-$_SESSION['last_login_time'])>1200)
        $_SESSION['last_login_time']="";
    else
        $_SESSION['last_login_time']=time();    
    
    $sql_record= "SELECT admin_user_id FROM sp_admin_users where admin_user_id='".$_SESSION['admin_user_id']."'"; 
  
    if($_REQUEST['action']=='logout' || !mysql_num_rows($db->query($sql_record)))
    {
        session_destroy();
        ?>
        <script language="javascript">
            document.location.href='index.php';
        </script>
        <?php
        exit(0);
    }
?>
