<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php"; 
      require_once '../classes/commonClass.php';
      $commonClass=new commonClass();
      require_once '../classes/employeesClass.php';
      $employeesClass=new employeesClass();
      require_once '../classes/professionalsClass.php';
      $professionalsClass=new professionalsClass(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Physiotherapy Events </title>
    <?php include "include/css-includes.php";?>
</head>
<body onload="myFunction()">
    <div id="wrapper">
        <!-- Navigation -->
        <?php  include "include/header.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <img src="images/events_big.png" alt="Manage Physiotherapy Events"> Manage Physiotherapy Events 
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="PhysiotherapyEventsListing">
                        <?php include "include_physiotherapy_events.php";?>
                    </div>
                </div>   
              </div>
               <!-- Main Content End-->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <div class="modal fade" id="edit_event"> 
        <div class="modal-dialog">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
   <?php  include "include/scripts.php"; ?>
<!--    <link rel="stylesheet" href="css/bootstrap.min.css" />-->
    <link rel="stylesheet" href="css/jquery.dataTables.css" />
    <script src="js/jquery.dataTables.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(window).load(function() 
        {   
           $('#jsontable').dataTable({
               "iDisplayLength": 10,
               "aLengthMenu": [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]]
           });
             
        });
    </script>
</body>
</html>