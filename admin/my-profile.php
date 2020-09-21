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
    <title>My Profile</title>
    <?php include "include/css-includes.php";?> 
    <script language="javascript" type="text/javascript"> 
       function edit_admin(admin_id)
        {
             var data1="admin_id="+admin_id+"&action=ModifyAdmin";
             $.ajax({
                    url: "admin_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        $('#edit-profile').modal('show'); 
                        $("#AllAjaxData").html(html);
                        $("#frm_modify_admin").validationEngine('attach',{promptPosition : "bottomLeft"});  
                    },
                    complete : function()
                    {
                       Popup_Hide_Load();
                    }
             });    
        }
        function SubmitModifyAdmin()
        {
            if($("#frm_modify_admin").validationEngine('validate')) 
            {
                $('#btn_submit').prop('disabled', true);
                
                $("#frm_modify_admin").ajaxForm({
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                    var res = html.trim(); 
                    // alert(res);
                    if(res)
                    {
                        if(res=='emailExists')
                        {
                            bootbox.alert("<div class='msg-error'>Email address is already in use please try another one.</div>");  
                        }
                        else if(res=='SameEmails')
                        {
                           bootbox.alert("<div class='msg-error'>Alernate email address is same as email address please choose another one.</div>",function()
                           {
                               $("#alternate_email_id").val('');
                               $("#alternate_email_id").focus();
                           });
                        }
                        else 
                        {
                            var findme="ValidationError";
                            if ( res.indexOf(findme) > -1) 
                            {
                                $("#error").show();
                                $("#alternate_email_id").val('');
                                $("#error").text("Alernate email id is same as email id please choose another one.");
                                $('#error').delay(15000).fadeOut('slow');

                            }
                            else
                            {
                                var content=res.split('AdminProfileContent');
                                var AdminName=content[0];
                                var ProfileContent=content[1];
                                $('#edit-profile').modal('hide');
                                bootbox.alert("<div class='msg-success'>Profile details updated successfully.</div>",function()
                                {
                                    $("#LeftAdminNm").html(AdminName);
                                    $("#AdminProfile").html(ProfileContent);
                                });   
                            }
                        } 
                    }
                    else
                    {
                       bootbox.alert("<div class='msg-error'>Error in update Profile.</div>");
                    }
                    $('#btn_submit').prop('disabled', false);
                },
                complete : function()
                {
                   Hide_Load();
                }
            }).submit();
            }
            else 
            {
                $('#btn_submit').prop('disabled', false);
                bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
                {
                    $("#name").focus();
                });
            }
        }
        function change_password(admin_id)
        {
            var data1="admin_id="+admin_id+"&action=change_admin_password" ;
             $.ajax({
                    url: "admin_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                       Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        $('#edit-profile').modal('show'); 
                        $("#AllAjaxData").html(html);
                        $("#frm_change_admin_password").validationEngine('attach',{promptPosition : "bottomLeft"});
                    },
                    complete : function()
                    {
                       Popup_Hide_Load();
                    }
             });
        }
        function change_admin_password_Submit()
        {
            if($("#frm_change_admin_password").validationEngine('validate'))
            {
                $("#frm_change_admin_password").ajaxForm({
                    beforeSend: function() 
                    {
                       Display_Load();
                    },
                    success: function (html)
                    {
                        var res = html.trim();
                       // alert(res);
                        if(res=='invalidPassword')
                        {
                            $("#admin_old_password").val('');
                            $("#admin_new_password").val('');
                            $("#admin_confirm_password").val('');
                            $("#error").show();
                            $("#error").text("Current password is wrong please try again !");
                            $('#error').delay(15000).fadeOut('slow');
                        }
                        else if(res=='SamePassword')
                        {
                           $("#admin_new_password").val('');
                           $("#admin_confirm_password").val('');
                           $("#error").show();
                           $("#error").text("New password is same as old password please try another one !");
                           $('#error').delay(15000).fadeOut('slow');
                        }
                        else
                        {
                            $('#edit-profile').modal('hide'); 
                            bootbox.alert("<div class='msg-success'>Password updated successfully.</div>",function(){
                               $("#AdminProfile").show(); 
                            });
                        }
                    },
                    complete : function()
                    {
                        Hide_Load();
                    }
                }).submit();
            }
            else 
            {
                bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
                {
                    $('#btn_change_password').prop('disabled', false);
                    $("#admin_old_password").focus();
                });
            }
        }  
    </script> 
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
                            <img src="images/icon-myprofile.png" alt="My Profile"> My Profile
                        </h1>                        
                    </div>
                </div>         
                <div class="col-lg-2 whiteBg">
                    <div class="text-center">
                        <div class="clearfix"></div>
                    </div>
                    <div class="editprofile marginT10">
                        <ul>
                            <li class="edit"><a href="javascript:void(0);" onclick="return edit_admin('<?php echo $_SESSION['admin_user_id']; ?>');" data-toggle="modal" >Edit Profile</a></li>
                            <li class="changepass"><a href="javascript:void(0);" onclick="return change_password('<?php echo $_SESSION['admin_user_id']; ?>');" data-toggle="modal">Change Password</a></li>
                        </ul>
                   </div>
                  </div>
              <div class="col-lg-10 whiteBg">
                    <div class="col-lg-9 paddingLR20 paddingt20" id="AdminProfile">
                       <?php include('profile_content.php'); ?>  
                    </div>
              </div>
               <!-- Main Content End-->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->   
 <div class="modal fade" id="edit-profile"> 
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
</body>
</html>