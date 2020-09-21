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
    <title>Manage Consumables</title>
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
                            <img src="images/consumables_big.png" alt="Manage Consumable"> Manage Consumable                   
                            <a href="javascript:void(0);" onclick="return consumable_add(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD CONSUMABLE</a>                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search consumable "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                       <?php if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') { echo '<a href="manage_consumables_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; } ?>
                    </div>
                    <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_consumable_list.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;" >
                            <img src="images/icon-download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="ConsumablessListing">
                        <?php include "include_consumables.php";?>
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
            changePagination('ConsumablessListing','include_consumables.php','','','','');
        }
        function consumable_add(value)
        {
            var data1="consumable_id="+value+"&action=add_consumable_form";
            $.ajax({
                url: "consumables_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
                },
                success: function (html)
                {
                   $('#PopupData').modal('show'); 
                   $("#AllAjaxData").html(html);
                   $("#frm_add_record").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
            });
        }
        function add_consumables_submit()
        {
           if($("#name").val() && $("#type").val() && $("#manufacture_name").val() && $("#rate").val())
           {
               $("#frm_add_record").ajaxForm({
                    success: function (html)
                    {
                        var result=html.trim();
                        Popup_Display_Load();
                        $('button').prop('disabled', true);
                        //alert(result);
                        if(result=='ValidationError')
                        {
                           bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>");  
                        }
                        if(result=='Recordexists')
                        {
                           bootbox.alert("<div class='msg-error'>Consumable details already exists, it may be on trash list, so please try another one.</div>"); 
                        }
                        else 
                        {
                            $('#PopupData').modal('hide'); 
                             if(result=='InsertSuccess')
                             {
                                 bootbox.alert("<div class='msg-success'>Consumable details updated successfully.</div>", function() 
                                 {
                                     changePagination('ConsumablessListing','include_consumables.php','','','','');
                                 });
                             }
                             else if(result=='UpdateSuccess')
                             {
                                 changePagination('ConsumablessListing','include_consumables.php','','','','');
                             }
                        }
                        $('button').prop('disabled', false);
                        Popup_Hide_Load();
                    }  
                }).submit();
           }
           else 
           {
               $('button').prop('disabled', false);
               bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
               {
                   $("#name").focus();
               });
           }
        }
        function change_status(record_id,curr_status,actionVal)
        {
           var prompt_msg="";
           var success_msg="";
           if(actionVal=='1')
           {
               prompt_msg ="Are you sure you want to activate this record ?";
               success_msg="activated";
           }
           else if(actionVal=='2')
           {
               prompt_msg ="Are you sure you want to inactive this record ?"; 
               success_msg="inactivated";  
           }
           else if(actionVal=='3')
           {
               prompt_msg="Are you sure you want to delete this record ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "consumable_id="+record_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                   $.ajax({
                       url: "consumables_ajax_process.php", type: "post", data: data1, cache: false,
                       success: function (html)
                       {
                          var result=html.trim();
                          Popup_Display_Load();
                          //alert(result);
                          if(result=='success')
                          {
                            bootbox.alert("<div class='msg-success'>Consumables "+success_msg+" successfully.</div>", function() 
                            {
                               changePagination('ConsumablessListing','include_consumables.php','','','','');
                            }); 
                          }
                          else 
                          {
                              bootbox.alert("<div class='msg-error'>Error In Operation</div>"); 
                          }
                          Popup_Hide_Load();
                       }
                   });
               }
           });   
        }
    </script>
</body>
</html>