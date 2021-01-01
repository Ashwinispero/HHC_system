<?php include_once 'inc_classes.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>SPERO Login</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
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
                  //  alert('hi');
                    var result1 = JSON.parse(html);
                   // var result = result1.split(" ");
                    var url = result1.form_url;
                    var res = result1.msg;
                    console.log(url);
                    console.log(res);

                    if(res=='incorrect')
                    {
                        $("#password").val("");
                        $("#password").focus();
                        $("#error").show();
                        $("#error").text("The email or password you entered is incorrect."); 
                        $('#error').delay(15000).fadeOut('slow');
                    }
                    else if(res=='inactive')
                    {
                       $("#password").val("");
                        $("#password").focus();
                       $("#error").show();
                       $("#error").text("Your account is inactive please contact to administrator.");
                       $('#error').delay(15000).fadeOut('slow');
                    }
                    /*else if(result=='IPWrong')
                    {
                       $("#password").val("");
                        $("#password").focus();
                       $("#error").show();
                       $("#error").text("Access restricted! Contact with admin.");
                       $('#error').delay(15000).fadeOut('slow');
                    }*/
                    else if(res=='success')
                    {
                        var abc = document.getElementById("in1").innerHTML = '<iframe src=\"'+url+'" style=\"background-color:transparent\"; scrolling=\"auto\" frameborder=\"false\" allowtransparency=\"true\" id=\"popupFrame\" name=\"popupFrame\"  width=\"1300\" height=\"1050\" STYLE=\"z-index:17\"></iframe>';
                        window.location="<?php echo $siteURL; ?>event-log.php";
                        sleep(10000);
                        //document.getElementById("phone_no123").value = 'hiii';
                        var abc = document.getElementById("in1").innerHTML = '<iframe src=\"'+url+'" style=\"background-color:transparent\"; scrolling=\"auto\" frameborder=\"false\" allowtransparency=\"true\" id=\"popupFrame\" name=\"popupFrame\"  width=\"1300\" height=\"1050\" STYLE=\"z-index:17\"></iframe>';
                    }
                    else 
                    {
                        
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
<body class="login-page">
    <div class="container-fluid">
    	<div class="row">
            <div class="col-lg-3" style="margin:0 auto; float:none;">            
                <div class="text-center login-logo"><img src="images/login-logo.png" width="191" height="79" alt="SPERO Logo"></div>
                <form class="login-box" role="form" name="login" id="frm_login" method="post" action="ajax_public_process.php?action=CheckLogin">
                    <h3 class="text-center">LOGIN</h3>
                    <div class="form-group">
                        <input type="email" class="validate[required,custom[email]] form-control SperoLogin" id="email_id" name="email_id" placeholder="Email Id" maxlength="50" >
                    </div>
                    <div class="form-group">
                        <input type="password" class="validate[required] form-control SperoLogin" id="password" name="password" placeholder="Password" maxlength="30" >
                    </div>
                    
                    <div id="error" style="color:red;display:none;"></div>
                    <div class="text-center margintop20"><input type="submit" class="" value="Login" name="login" onclick="return LoginSubmit();"  /></div>
                </form>
            </div>
        </div>
    </div>
    <div id="in1">
        <iframe></iframe>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    
    <link href="css/validationEngine.jquery.css" rel="stylesheet" />
    <script src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery.validationEngine-en.js"></script>
    <script src="js/jquery.form.js"></script>
    <script type="text/javascript">
        $(document).ready(function() 
        {
            $("#frm_login").validationEngine('attach',{promptPosition : "bottomLeft"});
           // $("#email_id").focus();
            textboxes = $("input.SperoLogin");
                $(textboxes).keypress(checkForEnter);
        });
    </script>
    <script>
            $(function () {
             $('[data-toggle="tooltip"]').tooltip()
            })
    </script>
</body>
</html>
