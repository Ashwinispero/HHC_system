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
    <title>Manage Services</title>
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
                            <img src="images/services_big.png" > Manage Services                   
                            <a href="javascript:void(0);" onclick="return add_services(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD SERVICE</a>                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Service"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                        <?php // if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') { echo '<a href="manage_services_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; } ?>
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="ServicesListing">
                        <?php include "include_services.php";?>
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
    <div class="modal fade" id="PopupData"> 
        <div class="modal-dialog">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
  <?php  include "include/scripts.php"; ?>
    <script src="js/action.js"></script>
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
            changePagination('ServicesListing','include_services.php','','','','');
        }
        function add_services(value)
        {
            var data1="service_id="+value+"&action=add_services";
            $.ajax({
                url: "services_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                   $('#PopupData').modal({backdrop: 'static',keyboard: false}); 
                   $("#AllAjaxData").html(html);
                   $("#frm_services").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete:function()
                {
                   Hide_Load(); 
                } 
            });
        }
        function select_day()
		{
			var Service_day=document.getElementById('Service_day').value;
			document.getElementById("Service_select").value = Service_day;

		}
        function submit_services()
        {
            if($("#service_title").val() && $('input[name=is_hd_access]:checked').length > 0) 
            {
                $('#submitForm').prop('disabled', true);
                $("#frm_services").ajaxForm({
                    beforeSend: function() 
                    {
                       Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        // alert(html);
                        var result=html.trim();
                        if(result=='ValidationError')
                        {
                             bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>");
                        }
                        if(result=='RecordExist')
                        {
                            $("#service_title").val('');
                            $("#service_title").focus();
                            bootbox.alert("<div class='msg-error'>Service details already exists please try another one.</div>");
                        }
                        else 
                        {
                            $('#PopupData').modal('hide'); 
                             if(result=='InsertSuccess')
                             {
                                bootbox.alert("<div class='msg-success'>Service details added successfully.</div>", function() 
                                {
                                   changePagination('ServicesListing','include_services.php','','','','');
                                }); 
                             }                        
                             else if(result=='UpdateSuccess')
                             {
                                 bootbox.alert("<div class='msg-success'>Service details updated successfully.</div>", function() 
                                {
                                   changePagination('ServicesListing','include_services.php','','','','');
                                });  
                             }                        
                         }
                         $('#submitForm').prop('disabled', false);
                    },
                    complete:function()
                    {
                       Popup_Hide_Load(); 
                    }  
                }).submit();
            }
            else 
            {
                $('#submitForm').prop('disabled', false);
                bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
		{
                    $("#service_title").focus();
		}); 
            }
        }
        function addSubService_vidio(service_id,subservice_id)
		{
			var data1="service_id="+service_id+"&subservice_id="+subservice_id+"&action=add_SubService_media_vidio";
            $.ajax({
                url: "services_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                   $('#PopupData').modal({backdrop: 'static',keyboard: false}); 
                   $("#AllAjaxData").html(html);
                   $("#frm_sub_services").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Hide_Load();
                }
            });
		}
		function addSubService_Photo(service_id,subservice_id)
		{
			var data1="service_id="+service_id+"&subservice_id="+subservice_id+"&action=add_SubService_media";
            $.ajax({
                url: "services_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                   $('#PopupData').modal({backdrop: 'static',keyboard: false}); 
                   $("#AllAjaxData").html(html);
                   $("#frm_sub_services").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Hide_Load();
                }
            });
		}
        function change_status(record_id,curr_status,actionVal)
        {
           var prompt_msg="";
           var success_msg="";
           if(actionVal=='1')
           {
               prompt_msg ="Are you sure you want to activate this service ?";
               success_msg="activated";
           }
           else if(actionVal=='2')
           {
               prompt_msg ="Are you sure you want to inactive this service ?"; 
               success_msg="inactivated";  
           }
           else if(actionVal=='3')
           {
               prompt_msg="Are you sure you want to delete this service ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "service_id="+record_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                   $.ajax({
                       url: "services_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                            beforeSend: function() 
                            {
                                 Display_Load();
                            },
                            success: function (html)
                            {
                               var result=html.trim();
                               if(result=='success')
                               {
                                 bootbox.alert("<div class='msg-success'>Service details "+success_msg+" successfully.</div>", function() 
                                 {
                                     changePagination('ServicesListing','include_services.php','','','','');
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
        function addSubService(service_id,subservice_id)
        {
            var data1="service_id="+service_id+"&subservice_id="+subservice_id+"&action=add_SubService";
            $.ajax({
                url: "services_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Display_Load();
                },
                success: function (html)
                {
                   $('#PopupData').modal({backdrop: 'static',keyboard: false}); 
                   $("#AllAjaxData").html(html);
                   $("#frm_sub_services").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Hide_Load();
                }
            });
        }
        function submit_sub_services()
        {
           if($("#frm_sub_services").validationEngine('validate'))
           {
                $("#frm_sub_services").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim(); 
                        //alert(result);
                        if(result=='ValidationError')
                        {
                             bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>");
                        }
                        if(result=='RecordExist')
                        {
                            bootbox.alert("<div class='msg-error'>Sub service details already exists please try another one.</div>",function()
                            {
                                $("#recommomded_service").val('');
                                $("#recommomded_service").focus();
                            });
                        }
                        else 
                        {
                            $('#PopupData').modal('hide'); 
                             if(result=='InsertSuccess')
                             {
                                 bootbox.alert("<div class='msg-success'>Sub service details added successfully.</div>", function() 
                                 {
                                    changePagination('ServicesListing','include_services.php','','','','');  
                                 });
                             }                        
                             else if(result=='UpdateSuccess')
                             {
                                 bootbox.alert("<div class='msg-success'>Sub service details updated successfully.</div>", function() 
                                 {
                                    changePagination('ServicesListing','include_services.php','','','','');  
                                 }); 
                             }                        
                        } 
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }  
                }).submit();
           }
        }
        function change_SubService_status(sub_service_id,actionVal)
        { 
           var prompt_msg="";
           var success_msg="";
           if(actionVal=='1')
           {
               prompt_msg ="Are you sure you want to activate this sub service ?";
               success_msg="activated";
           }
           else if(actionVal=='2')
           {
               prompt_msg ="Are you sure you want to inactive this sub service ?"; 
               success_msg="inactivated";  
           }
           else if(actionVal=='3')
           {
               prompt_msg="Are you sure you want to delete this sub service ?";
               success_msg="deleted";
           }
           else if(actionVal=='4')
           {
               prompt_msg="Are you sure you want to permanent delete this sub service ?";
               success_msg="permanent deleted";
           }
           else if(actionVal=='5')
           {
               actionVal = 1;
               prompt_msg="Are you sure you want to reverted this sub service ?";
               success_msg="reverted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "sub_service_id="+sub_service_id+"&actionval="+actionVal+"&action=ChangeSubService_statuis";
                   //alert(data1);
                   $.ajax({
                       url: "services_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                        beforeSend: function() 
                        {
                            Display_Load();
                        },
                        success: function (html)
                        {
                           var result=html.trim();
                           //alert(result);
                           if(result=='success')
                           {
                                 bootbox.alert("<div class='msg-success'>Sub service details "+success_msg+" successfully.</div>", function() 
                                 {
                                     changePagination('ServicesListing','include_services.php','','','','');
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