<?php 
      include "inc_classes.php";
      include "admin_authentication.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Dashboard</title>
        <?php include "include/css-includes.php";?> 
        <script language="javascript" type="text/javascript">
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php  include "include/header.php"; ?>
            <div id="page-wrapper">
                <div class="container-fluid">
                    <div class="dashboardListing">
                        <?php include "include_dashboard.php"; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php  include "include/scripts.php"; ?>
    <script src="js/bootbox.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

        });

        function searchRecords() {
            changePagination('dashboardListing','include_dashboard.php','','','','');
        }
    </script>
</html>