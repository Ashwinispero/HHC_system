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
    <title>Manage System Users </title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:400px}</style>
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
                            <img src="images/system_user_big.png"> Manage System Users                   
                            <a href="javascript:void(0);" onclick="return vw_add_system_user(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD SYSTEMS USERS</a>                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Users "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                        <?php
                            if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') { echo '<a href="manage_system_users_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>';  } 
                       ?>
                    </div>
                    <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_system_user_list.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;" >
                            <img src="images/icon-download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="SystemUserListing">
                        <?php include "include_system_users.php";?>
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
    <div class="modal fade" id="edit_system_user"> 
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
            changePagination('SystemUserListing','include_system_users.php','','','','');
        }
        function vw_add_system_user(value)
        {
            var data1="admin_user_id="+value+"&action=vw_add_system_user";
            $.ajax({
                url: "system_user_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
                },
                success: function (html)
                {
                   $('#edit_system_user').modal({backdrop: 'static',keyboard: false}); 
                   $("#AllAjaxData").html(html);
                   setTimeout("$('.scrollbars').ClassyScroll();",100);
                   $("#frm_add_system_user").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
            });
        }
        function add_system_user_submit()
        {
           if($("#frm_add_system_user").validationEngine('validate')) 
           {
               $('#submitForm').prop('disabled', true);
               $("#frm_add_system_user").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                       // alert(result);
                        if(result=='validationError')
                        {
                             bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                        }
                        if(result=='userexists')
                        {
                            $("#email_id").val('');
                            $("#email_id").focus();
                            bootbox.alert("<div class='msg-error'>System user already exists please try another one.</div>");
                        }
                        else
                        {
                             $('#edit_system_user').modal('hide');
                             if(result=='InsertSuccess')
                             {
                                  bootbox.alert("<div class='msg-success'>System user details added successfully.</div>",function()
                                  {
                                      changePagination('SystemUserListing','include_system_users.php','','','','');   
                                  });
                              }
                             if(result=='UpdateSuccess')
                             {
                                    bootbox.alert("<div class='msg-success'>System user details updated successfully.</div>",function()
                                    {
                                        changePagination('SystemUserListing','include_system_users.php','','','','');   
                                    });
                             }
                             
                          
                         }
                         $('#submitForm').prop('disabled', false);
                    },
                    complete : function()
                    {
                       Hide_Load();
                    } 
                }).submit();
           }
           else 
            {
                $('#submitForm').prop('disabled', false);
                bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
                {
                    $("#type").focus();
                });
            }
        }
        function change_status(admin_user_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this system user ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this system user ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this system user ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&admin_user_id="+admin_user_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                 //  alert(data1);
                   $.ajax({
                       url: "system_user_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                           Popup_Display_Load();
                        },
                       success: function (html)
                       {
                          var result=html.trim();
                          // alert(result);
                          
                          if(result=='success')
                          {
                              bootbox.alert("<div class='msg-success'>System user "+success_msg+" successfully.</div>",function()
                              {
                                  changePagination('SystemUserListing','include_system_users.php','','','','');
                              }); 
                          }
                          else
                          {
                              bootbox.alert("<div class='msg-error'>Error In Operation</div>");
                          }
                       },
                        complete : function()
                        {
                           Popup_Hide_Load();
                        }
                   });
               }
           });   
        }
        function view_user(admin_user_id)
        {
            var data1="admin_user_id="+admin_user_id+"&action=vw_user";
            //alert(data1);
             $.ajax({
                    url: "system_user_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                       Popup_Display_Load();
                    },
                    success: function (html)
                    {
                       // alert(html);
                        $('#edit_system_user').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                    },
                    complete : function()
                    {
                       Popup_Hide_Load();
                    }
             }); 
        }
        function chkEmails()
        {
            if($("#email_id").val() && $("#alternate_email_id").val())
            {
                var email_id=$("#email_id").val();
                var alternate_email_id=$("#alternate_email_id").val();
                
                if(email_id==alternate_email_id)
                {
                   $("#alternate_email_id").val('');
                   $("#alternate_email_id").focus();
                   $("#form_error").show();
                   $("#form_error").text("alternate email address is same as email address please choose another one !");
                   $('#form_error').delay(6000).fadeOut('slow');
                }
                else
                    return false;
            }
            else
                return false;
        }
    </script>
</body>
</html>