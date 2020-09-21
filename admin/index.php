<?php ob_start(); ?>
<?php $page_name = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Login</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">
<!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php  include "include/scripts.php"; ?>
    
    <script type="text/javascript">
        function LoginSubmit()
        {
            var userEmail=$("#email_id").val();
            if($("#email_id").val() != '' && $("#password").val() != '')
            {
                Display_Load();
            }
            var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
            var valid = emailReg.test(userEmail);
            if(valid)
            {
                $("#frm_login").ajaxForm({
                success: function (html)
                {
                   var result = html.trim();
                   //alert(result);
                   if(result=='incorrect')
                   {
                        $("#error").show();
                        $("#error").text("The email or password you entered is incorrect.");
                        $('#error').delay(15000).fadeOut('slow');
                   }
                   else if(result=='inactive' || result=='deleted')
                   {
                       $("#error").show();
                       $("#error").text("Your account is inactive / deleted please contact to administrator.");
                       $('#error').delay(15000).fadeOut('slow');
                   }
                   else if(result=='notexists')
                   {
                       $("#error").show();
                       $("#error").text("Your account details is not match with our database please contact to administrator.");
                       $('#error').delay(15000).fadeOut('slow');
                   }
                   else
                   {
                        window.location="my-profile.php";
                   }
                   Hide_Load();
                }
                }).submit(); 
            }
            else
            {
                Hide_Load();
            }
        }
        function ForgotPasswordSubmit()
        {
            var userEmail=$("#forgot_email_id").val();
            if($("#forgot_email_id").val() != '')
            {
                Display_Load();
            }
            var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
            var valid = emailReg.test(userEmail);
            if(valid)
            {
               $("#frm_forgot_password").ajaxForm({
                success: function (html)
                {
                    var contents = $.trim(html);
                   // alert(contents);
                    if(contents == 'success')
                    {
                        $(".modal-body").html('<span style="font-size:16px;color:#4074E0;">Thank you for your request. We have reset your password and sent it to the registered email address.</span>');
                        setInterval('window.location.reload()', 5000);
                    }
                    else
                    {
                        $("#forgot_email_id").val('');
                        $("#forgot_error").show();
                        $("#forgot_error").text("Enter your registered email.");
                        $('#forgot_error').delay(15000).fadeOut('slow');
                    }
                    Hide_Load();
                }
                }).submit();  
            }
            else
            {
               Hide_Load(); 
            } 
        }
        function checkForEnter (event) 
        {
            if (event.keyCode == 13) 
            {
                LoginSubmit();           
            } 
        }
   </script>
</head>
<body class="login-page">
    <div class="container-fluid">
        <!-- Navigation -->
        <?php  // include "include/header.php"; ?>
      <div class="col-lg-3 login-box">
        <div class="text-center"><img  alt="SPERO Logo" src="../images/login-logo.png"></div>
        <form role="form" class="marginTB28" name="login" id="frm_login" method="post" action="admin_ajax_process.php?action=DoLogin">
            <div class="form-group">
                <input type="text" name="email_id" id="email_id" class="validate[required,custom[email]] form-control exhiLogin" value="<?php if(isset($_COOKIE['Adminname'])) { echo $_COOKIE['Adminname']; } else { echo ""; } ?>" placeholder="Email Id" maxlength="50" />
            </div>
            <div class="form-group">
              <input type="password" name="password" id="password" class="validate[required] form-control exhiLogin" placeholder="Password" maxlength="25" />
            </div>
            <div id="error" style="color:red;display:none;"> 
            </div>
            <div class="form-group">
                <div class="checkbox" style="width:50%; float:left; margin-top:5px;">
                    <label>
                      <input type="checkbox" name="admin_remember" id="admin_remember" value="1"<?php if(isset($_COOKIE['Adminname'])) { echo 'checked="checked"'; } ?> /> Remember me
                    </label>
                </div> 
                <div class="help-block text-right" style="width:50%; float:left; margin-top:5px;">
                  <a href="javascript:void(0);" class="help-block" data-toggle="modal" data-target="#forgot-password">Forgot Password?</a>
                </div> 
            </div>
            <input type="button" name="btn_login" id="btn_login" value="Login" class="btn btn-login marginT10" onclick="return LoginSubmit();" />
        </form>
      </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery Version 1.11.0 -->
    <?php  include "include/scripts.php"; ?>
    <link href="css/validationEngine.jquery.css" rel="stylesheet" />
    <script src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery.validationEngine-en.js"></script>
    <script src="js/jquery.form.js"></script>
    <script type="text/javascript">
        $(document).ready(function() 
        {
            $("#frm_login").validationEngine('attach',{promptPosition : "bottomLeft"});
            $("#frm_forgot_password").validationEngine('attach',{promptPosition : "bottomLeft"});
           // $("#email_id").focus();
            textboxes = $("input.exhiLogin");
            $(textboxes).keypress (checkForEnter);
        });
    </script>
    <!-- Forgot Passward-->
    <div class="modal fade bs-example-modal-sm" id="forgot-password">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Forgot Password</h4>
          </div>
            <div class="modal-body">
                <div id="forgot_error" style="color:red;display:none;">
                </div>
                <form role="form" class="" name="forgot_password" id="frm_forgot_password" method="post" action="ajax_process.php?action=forgotPassword">
                    <div class="form-group">
                        <input type="text" name="forgot_email_id" id="forgot_email_id" class="validate[required,custom[email]] form-control"  placeholder="Enter email" maxlength="50" />
                    </div>
                    <div class="form-group text-right">
                        <input type="button" name="btn_login" id="btn_login" value="Submit" class="btn btn-download marginT10" onclick="return ForgotPasswordSubmit();" />
                    </div>
                </form>
          </div>
        </div> 
      </div>
    </div>
</body>
</html>
<?php ob_flush(); ?>