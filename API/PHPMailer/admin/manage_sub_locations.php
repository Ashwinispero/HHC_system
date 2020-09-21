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
    <title>Manage Sub Locations </title>
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
                            <img src="images/locations_big.png" alt="Manage Sub Locations"> Manage Sub Locations                   
                            <a href="javascript:void(0);" onclick="return vw_add_sub_location('<?php if(!empty($_REQUEST['location_id'])) { echo base64_decode($_REQUEST['location_id']); } ?>',0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD SUB LOCATION</a>                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox">
                            <input type="hidden" name="location_id" id="location_id" value="<?php if(!empty($_REQUEST['location_id'])) { echo base64_decode($_REQUEST['location_id']); } ?>" />
                            <input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Sub Location "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                       <a href="manage_locations_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a> 
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="SubLocationsListing">
                        <?php include "include_sub_locations.php";?>
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
    <div class="modal fade" id="edit_sub_location"> 
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
            changePagination('SubLocationsListing','include_sub_locations.php','','','','');
        }
        function vw_add_sub_location(location_id,value)
        {
            Popup_Display_Load();
            var data1="location_id="+location_id+"&sub_location_id="+value+"&action=vw_add_sub_location";
            //alert(data1);
            $.ajax({
                url: "location_ajax_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                   // alert(html);
                   $('#edit_sub_location').modal('show'); 
                   $("#AllAjaxData").html(html);
                   $("#frm_add_sub_location").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                   Popup_Hide_Load();
                }
            });
        }
        function add_sub_location_submit()
        {
           if($("#location_name").val()) 
               Popup_Display_Load();
           $("#frm_add_sub_location").ajaxForm({
               success: function (html)
               {
                   var result=html.trim();
                   alert(result);
                  if(result=='ValidationError')
                   {
                      bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                       Popup_Hide_Load();
                   }
                   if(result=='sublocationexists')
                   {
                      bootbox.alert("<div class='msg-error'>Sub location details already exists, it may be on trash list, so please try another one.</div>"); 
                      Popup_Hide_Load();
                   }
                   else 
                   {
                        $('#edit_sub_location').modal('hide'); 
                        if(result=='InsertSuccess')
                             bootbox.alert("<div class='msg-success'>Sub location details added successfully.</div>");

                        else if(result=='UpdateSuccess')
                             bootbox.alert("<div class='msg-success'>Sub location details updated successfully.</div>");

                        Popup_Hide_Load();
                        changePagination('SubLocationsListing','include_sub_locations.php','','','','');
                   }
               }  
           }).submit();
        }
        function change_status(sub_location_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this sub location ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this sub location ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this sub location ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&sub_location_id="+sub_location_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_sub_location_status";
                 //  alert(data1);
                   $.ajax({
                       url: "location_ajax_process.php", type: "post", data: data1, cache: false,
                       success: function (html)
                       {
                          var result=html.trim();
                          // alert(result);
                          
                          if(result=='success')
                              bootbox.alert("<div class='msg-success'>Sub location "+success_msg+" successfully.</div>");       
                          else
                              bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                          
                          Popup_Hide_Load();
                          changePagination('SubLocationsListing','include_sub_locations.php','','','','');
                       }
                   });
               }
           });   
        }
    </script>
</body>
</html>