<?php include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";  
?>
<?php
    $cookie_SpNm = "SpCkStNpDt";
    $cookie_value = base64_encode('Spero@cookie123*#');
    setcookie($cookie_SpNm, $cookie_value, time() + (60*60*24*100), "/"); // 86400 = 1 day
    //setcookie($cookie_SpNm, '', time() - (60*60*24*100), "/");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Set Cookies</title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:400px}</style>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php  include "include/header.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                
                <?php
                    echo 'Cookie set successfully';
                if(!isset($_COOKIE[$cookie_SpNm])) {
                    //echo "Fail";
                } else {
                    //echo "Cookie '" . $cookie_SpNm . "' is set!<br>";
                    //echo "Value is: " . $_COOKIE[$cookie_SpNm];
                    //echo 'success';
                }
                ?>
               <!-- Main Content End-->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
   <?php include "include/scripts.php"; ?>    
</body>
</html>