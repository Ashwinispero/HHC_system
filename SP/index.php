<!DOCTYPE html>

<html>
    <head>
         <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Job Closure</title>
 <link rel="shortcut icon" type="image/x-icon" href="image/logo.png" />
 <!--<script src="js/prefixfree.min.js"></script>-->
<script type="text/javascript" language="javascript" src="js/jquery_1.5.2.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/custom.js"></script>-->
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery.label_better.js"></script>
<script type="text/javascript" src="js/jquery.label_better.min.js"></script>
<script type="text/javascript" src="js/jquery.label_better_1.js"></script>
<!--<link href="css/style.css" rel="stylesheet" type="text/css" />-->
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">



	<script type="text/javascript">
	function Enter_key()
	{
		if(event.keyCode === 13) 
		{
			//alert();
		var Usertype=document.getElementById('Usertype').value;
		var UserEmail=document.getElementById('UserEmail').value;
		var password=document.getElementById('Password').value;
		if(!($.trim(password)=='') && (!($.trim(UserEmail)=='')) && (!($.trim(Usertype)=='')))
		{
            $.post("Submit_login_details.php",{ UserEmail:UserEmail,password:password,Usertype:Usertype
		},
        function(status){
				var x = status;
				alert(x);
                if ($.trim(x)=='Login')
                {
					window.location ="Physiotherapy_job_closure.php";						
                     //alert('success');      
                }
				else
				{
					alert('You have entered wrong Username and Password');
				}
                                
						
			});		}
		}
		
	}
	function Job_closure_login()
	{
		//alert();
		var Usertype=document.getElementById('Usertype').value;
		var UserEmail=document.getElementById('UserEmail').value;
		var password=document.getElementById('Password').value;
		if(!($.trim(password)=='') && (!($.trim(UserEmail)=='')) && (!($.trim(Usertype)=='')))
		{
            $.post("Submit_login_details.php",{ UserEmail:UserEmail,password:password,Usertype:Usertype
		},
        function(status){
				var x = status;
				alert(x);
                if ($.trim(x)=='Login')
                {
					window.location ="Physiotherapy_job_closure.php";						
                     //alert('success');      
                }
				else
				{
					alert('You have entered wrong Username and Password');
				}
                                
						
			});		}
		
	}
	

			
</script>
    </head>
<?php
include('config.php');
?>
    <body style="background-color:#cecece;">
          <!-- Page Content -->
    <div class="container">

        <!-- Marketing Icons Section -->
        <div class="row">
            
<div class="col-md-EROlogin" style="margin-top:10%;background-color:#00cfcb;">
    <div class="col-lg-12" >
                <h1 class="page-header" style="color:white" align="center">
                    Job Closure Login
                </h1>
            </div>
            <div class="col-md-12" >
                <div class="panel-body">
                        
						 <label style="color:white">User Type :</label>
						<div class="form-group">
                       
                        <select id="Usertype" class="form-control" autofocus="autofocus">
						<option value="" >User Type</option> 
						
						<?php
						$Query=mysql_query("select * from sp_services  ORDER BY service_title ASC");
						while($row=mysql_fetch_array($Query))
						{
						?>
						<option value="<?php echo $row['service_id'] ;?>" ><?php echo $row['service_title'];?> </option>
						<?php
						}
						?>
						</select>
                       
                        </div>
						 <label style="color:white">User Email :</label>
                <div class="form-group">
                    <input type="text" class="form-control" id="UserEmail" placeholder="User Email" >
					
                </div>
				 <label style="color:white">Password :</label>
                <div class="form-group">
                    <input type="password" class="form-control" id="Password" placeholder="Password" onkeypress="Enter_key();" >
					
                </div>
                
               
               <br>
				<div class="row" align='center'>
                
                    <input type="button" class="btn btn-default" id="Loginbutton"  onclick="Job_closure_login();" value="Submit" style="background-color:#ffbf00;border-radius:15px;">
              </div>
                    </div>
               
            </div>
           
       </div>     
        </div>
    </div>
    </body>
</html>
