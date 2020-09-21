<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
?>
<html lang="en">
<head>
<title>Welcome to SPERO</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>


</head>
<script>
function Enquiry_call_back(event_id)
	{
		var event_id1=event_id;
		//alert(event_id1);
		var xmlhttp;
		if(window.XMLHttpRequest)
		{
		xmlhttp=new XMLHttpRequest();
		}
		else
		{
			xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange = function()
		{
			
			if(xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				
				alert('call back');
				search_enquiry_record();
				//location.reload();
				 // $("#Enquiry_records").load(location.href+" #Enquiry_records>*","");
				//$('#thisdiv').load(document.URL +  ' #thisdiv');
			}
		}
		
		xmlhttp.open("POST","Enquiry_status_change.php?event_id="+event_id1+"&flag=1",true);
		xmlhttp.send();
		
	}
	function Enquiry_call_confirm(event_id)
	{
		var event_id1=event_id;
		//alert(event_id1);
		var xmlhttp;
		if(window.XMLHttpRequest)
		{
		xmlhttp=new XMLHttpRequest();
		}
		else
		{
			xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange = function()
		{
			
			if(xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				
				alert('comfirmed');
				//location.reload();
				search_enquiry_record();
				
			}
		}
		
		xmlhttp.open("POST","Enquiry_status_change.php?event_id="+event_id1+"&flag=2",true);
		xmlhttp.send();
	}
	function Enquiry_call_cancle(event_id)
	{
		var event_id1=event_id;
		//alert(event_id1);
		var xmlhttp;
		if(window.XMLHttpRequest)
		{
		xmlhttp=new XMLHttpRequest();
		}
		else
		{
			xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange = function()
		{
			
			if(xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				
				alert('cancel');
				search_enquiry_record();
				//location.reload();
				
			}
		}
		
		xmlhttp.open("POST","Enquiry_status_change.php?event_id="+event_id1+"&flag=3",true);
		xmlhttp.send();
	}
	
	function search_enquiry_record()
	{
		//-------Sort By payment date function-----------
		    //-----Author: ashwini 31-05-2016-----
            //changePagination('LocationsListing','include_payments.php','','','','');
			
			 var enquiry_from_date=document.getElementById('enquiry_from_date').value;
			 var enquiry_to_date=document.getElementById('enquiry_to_date').value;
			// var type_of_payment=document.getElementById('type_of_payment').value;
			 
			// var event_code=document.getElementById('event_code').value;
			 //alert(event_code);
			// var HHC_NO=document.getElementById('HHC_NO').value;
			//alert(toDate_invoice);
			 var xmlhttp;
			 if(window.XMLHttpRequest)
			{
				xmlhttp=new XMLHttpRequest();
			}
			else
			{
				xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function()
			{
                if(xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					//alert(xmlhttp.responseText);
                   	document.getElementById("Enquiry_records").innerHTML=xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST","include_enquiry_call_details.php?enquiry_from_date="+enquiry_from_date+"&enquiry_to_date="+enquiry_to_date,true);
			xmlhttp.send();
	}
</script>
<body>


<div id="wrapper">
        <!-- Navigation -->
        
        <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Enquiry Report Details              
                           
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                <div class="form-group col-lg-4">
                      <div class="row">
                        
                        <div class="col-sm-4" >
                          <input type="date"  id="enquiry_from_date" name="event_from_date" placeholder="From" >
                        </div>
						 <div class="col-sm-2" ></div>
                        <div class="col-sm-4" >
                          <input type="date"   id="enquiry_to_date" name="event_to_date" placeholder="To" >
                        </div>
                      </div>
                    </div>   
                   
				    <div class="col-lg-3 marginB20 paddingl0">
                             <input type="button" style="background-color:#00cfcb;color:white" onclick="return search_enquiry_record();" value="View Enquiry Call" name="btn-view-schedule" class="btn btn-download">
                     </div>
					<div>
                    <!--<div class="pull-right paddingLR0" style="padding-left:15px !important;">
                        <?php //if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') { echo '<a href="manage_locations_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; }
                        ?>
                    </div>-->
                    
					
					
					<div id="Enquiry_records">
                    <div class="clearfix"></div>
                    <div class="LocationsListing">
					<div>Please Select Date First...</div>
                        <?php //include "include_export_invoice.php";?>
                    </div>
                </div>
              </div>
               <!-- Main Content End-->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
	</div>
	</div>

	
</body>