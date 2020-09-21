<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Hospitals Trash</title>
    <?php include "include/css-includes.php";?>
</head>
<body>
    <div id="wrapper">
        <!-- Navigation -->
        <?php  include "include/header.php"; ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <img src="images/hospitals_big.png" > Manage Hospitals Trash 
                            <a class="btn btn-download pull-right font18" href="manage_hospitals.php" data-original-title="" title="">VIEW HOSPITALS</a>
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Hospital "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                        
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="TrashHospitalsListing">
                        <?php include "include_hospitals_trash.php";?>
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
    <div class="modal fade" id="edit_hospital_trash"> 
        <div class="modal-dialog">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
   <?php  include "include/scripts.php"; ?>
    <script src="js/bootbox.js"></script>
    <script type="text/javascript">
        $(document).ready(function() 
        {
            textboxes = $("input.data-entry-search");
            $(textboxes).keydown (checkForEnterSearch); 
        });
        function checkForEnterSearch (event) 
        {
            if (event.keyCode == 13) 
            {
                searchRecords();
            }
        }
        function searchRecords()
        {
            changePagination('TrashHospitalsListing','include_hospitals_trash.php','','','','');
        }
        function change_status(hospital_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           var trashDelete=0;
           if(actionVal=='Revert')
           {
               trashDelete=0;
               prompt_msg ="Are you sure you want to revert this hospital ?"; 
               success_msg="reverted";   
           }
           else if(actionVal=='CompleteDelete')
           {
               trashDelete=1;
               prompt_msg ="Are you sure you want to permanent delete this hospital ?"; 
               success_msg="permanent delete";    
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&hospital_id="+hospital_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&trashDelete="+trashDelete+"&action=change_status";
                 //  alert(data1);
                   $.ajax({
                       url: "hospital_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                            Display_Load();
                        },
                       success: function (html)
                       {
                          var result=html.trim();
                          // alert(result);
                          
                          if(result=='success')
                          {
                              bootbox.alert("<div class='msg-success'>Hospital "+success_msg+" successfully.</div>",function()
                              {
                                  changePagination('TrashHospitalsListing','include_hospitals_trash.php','','','','');
                              });
                          }
                          else
                          {
                              bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                          }
                       },
                        complete : function()
                        {
                           Hide_Load();
                        }
                   });
               }
           });   
        }
    </script>
</body>
</html>