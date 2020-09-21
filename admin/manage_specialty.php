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
    <title>Manage Specialty </title>
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
                            <img src="images/specialty_big.png" alt="Manage Specialty"> Manage Specialty                   
                            <a href="javascript:void(0);" onclick="return vw_add_specialty(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD SPECIALTY</a>                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Specialty"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                       <a href="manage_specialty_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a> 
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="SpecialtyListing">
                        <?php include "include_specialty.php";?>
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
    <div class="modal fade" id="edit_specialty"> 
        <div class="modal-dialog">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
   <?php  include "include/scripts.php"; ?>
    <link href="css/validationEngine.jquery.css" rel="stylesheet" />
    <script src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery.validationEngine-en.js"></script>
    <script src="js/jquery.form.js"></script>
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
            changePagination('SpecialtyListing','include_specialty.php','','','','');
        }
        function vw_add_specialty(value)
        {
            Popup_Display_Load();
            var data1="specialty_id="+value+"&action=vw_add_specialty";
            $.ajax({
                url: "specialty_ajax_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                   // alert(html);
                   $('#edit_specialty').modal('show'); 
                   $("#AllAjaxData").html(html);
                   $("#frm_add_specialty").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                   Popup_Hide_Load();
                }
            });
        }
        function add_specialty_submit()
        {
           if($("#abbreviation").val()) 
               Popup_Display_Load();
           $("#frm_add_specialty").ajaxForm({
               success: function (html)
               {
                   var result=html.trim();
                  // alert(result);
                  if(result=='ValidationError')
                   {
                      bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                       Popup_Hide_Load();
                   }
                   if(result=='specialtyexists')
                   {
                      bootbox.alert("<div class='msg-error'>Specialty details already exists, it may be on trash list, so please try another one.</div>"); 
                      Popup_Hide_Load();
                   }
                   else 
                   {
                        $('#edit_specialty').modal('hide'); 
                        if(result=='InsertSuccess')
                             bootbox.alert("<div class='msg-success'>Specialty details added successfully.</div>");

                        else if(result=='UpdateSuccess')
                             bootbox.alert("<div class='msg-success'>Specialty details updated successfully.</div>");

                        Popup_Hide_Load();
                        changePagination('SpecialtyListing','include_specialty.php','','','','');
                   }
               }  
           }).submit();
        }
        function change_status(specialty_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this specialty ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this specialty ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this specialty ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&specialty_id="+specialty_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                 //  alert(data1);
                   $.ajax({
                       url: "specialty_ajax_process.php", type: "post", data: data1, cache: false,
                       success: function (html)
                       {
                          var result=html.trim();
                          // alert(result);
                          if(result=='success')
                              bootbox.alert("<div class='msg-success'>Specialty "+success_msg+" successfully.</div>");       
                          else
                              bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                          
                          Popup_Hide_Load();
                          changePagination('SpecialtyListing','include_specialty.php','','','','');
                       }
                   });
               }
           });   
        }
    </script>
</body>
</html>