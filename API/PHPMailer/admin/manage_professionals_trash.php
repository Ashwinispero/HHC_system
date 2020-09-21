<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
      require_once '../classes/commonClass.php';
      $commonClass=new commonClass();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Manage Professionals Trash </title>
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
                            <img src="images/professionals_big.png" alt="Manage Professionals Trash"> Manage Professionals Trash
                            <a class="btn btn-download pull-right font18" href="manage_professionals.php" data-original-title="" title="">VIEW PROFESSIONALS</a>
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Professional"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;"> 
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="dp_country" name="reference_type" id="reference_type" onchange="searchRecords();">
                                <option value=""<?php if($_REQUEST['reference_type']=='') { echo 'selected="selected"'; } ?>>Search By Profession</option>
                                 <option value="1"<?php if($_REQUEST['reference_type']=='1') { echo 'selected="selected"'; } ?>>Professional</option>
                                 <option value="2"<?php if($_REQUEST['reference_type']=='2') { echo 'selected="selected"'; } ?>>Vender</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="dp_country" name="location_id" id="location_id" onchange="searchRecords();">
                                <option value="">Search By Location</option>
                                <?php
                                    // Getting All Locations
                                    $recList=$commonClass->GetAllLocations($arr);
                                    foreach($recList as $recListKey => $valLocations)
                                    {
                                        if($_REQUEST['location_id']==$valLocations['location_id'])
                                            echo '<option value="'.$valLocations['location_id'].'" selected="selected">'.$valLocations['location'].'</option>';
                                        else
                                            echo '<option value="'.$valLocations['location_id'].'">'.$valLocations['location'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="ProfessionalsTrashListing">
                        <?php include "include_professionals_trash.php";?>
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
    <div class="modal fade" id="edit_professional_trash"> 
        <div class="modal-dialog">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
   <?php  include "include/scripts.php"; ?>
    <script src="js/bootbox.js"></script>
    <script type="text/javascript">
        $(document).ready(function() 
        {
            textboxes = $("input.data-entry-search");
            $(textboxes).keydown (checkForEnterSearch);
            $.datepicker._generateHTML_Old = $.datepicker._generateHTML; $.datepicker._generateHTML = function(inst)
            {
               res = this._generateHTML_Old(inst); res = res.replace("_hideDatepicker()","_clearDate('#"+inst.id+"')"); return res;
            }
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
            changePagination('ProfessionalsTrashListing','include_professionals_trash.php','','','','');
        }
        function vw_add_professional(value)
        {
            var data1="service_professional_id="+value+"&action=vw_add_professional";
            $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                    //alert(html);
                   $('#edit_professional').modal({backdrop: 'static',keyboard: false}); 
                   $("#AllAjaxData").html(html);
                   if(value)
                   {
                       var ref_type= $(html).find('#detail_id').val();
                       if(ref_type=='1')
                           $(".cls_prof").show();
                       else 
                           $(".cls_prof").hide();
                       
                       $(".ProfOtherContent").show(); 
                   }
                   else 
                   {
                       $(".cls_prof").hide();
                       $(".ProfOtherContent").hide();
                   }
                   setTimeout("$('.scrollbars').ClassyScroll();",100);
                   $("#frm_add_professional").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                   $('.datepicker').datepicker({changeMonth: true,changeYear: true,dateFormat:'yy-mm-dd',yearRange: "-60:-20"});
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
            });
        }
        function add_professional_submit()
        {
           if($("#frm_add_professional").validationEngine('validate'))
           {
                $('#submitForm').prop('disabled', true);
                
               $("#frm_add_professional").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                   success: function (html)
                   {
                       var result=html.trim();
                       //alert(result);
                       if(result=='InsertSuccess')
                       {
                            bootbox.alert("<div class='msg-success'>Professional details added successfully.</div>",function()
                            {
                                changePagination('ProfessionalsTrashListing','include_professionals_trash.php','','','','');
                            });
                       }
                       else if(result=='UpdateSuccess')
                       {
                            bootbox.alert("<div class='msg-success'>Professional details updated successfully.</div>",function()
                            {
                                changePagination('ProfessionalsTrashListing','include_professionals_trash.php','','','','');
                            });
                       }
                       else
                       {
                            bootbox.alert("<div class='msg-error'>Professional already exists please try another one.</div>");
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
                    $("#reference_type").focus();
                });
           }
           
        }
        function change_status(service_professional_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           var trashDelete=0;
           if(actionVal=='Revert')
           {
               trashDelete=0;
               prompt_msg ="Are you sure you want to revert this professional ?"; 
               success_msg="reverted";   
           }
           else if(actionVal=='CompleteDelete')
           {
               trashDelete=1;
               prompt_msg ="Are you sure you want to permanent delete this professional ?"; 
               success_msg="permanent delete";    
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&service_professional_id="+service_professional_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&trashDelete="+trashDelete+"&action=change_status";
                 //  alert(data1);
                   $.ajax({
                       url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                       beforeSend: function() 
                       {
                           Display_Load();
                       },
                       success: function (html)
                       {
                          var result=html.trim();
                          // alert(result);
                          if(result=='success')
                          {
                              bootbox.alert("<div class='msg-success'>Professional "+success_msg+" successfully.</div>",function()
                              {
                                  changePagination('ProfessionalsTrashListing','include_professionals_trash.php','','','','');
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
        function view_professional(service_professional_id)
        {
            var data1="service_professional_id="+service_professional_id+"&action=vw_professional";
            //alert(data1);
             $.ajax({
                    url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Popup_Display_Load();
                    },
                    success: function (html)
                    {
                       // alert(html);
                        $('#edit_professional_trash').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                    },
                    complete : function()
                    {
                        Popup_Hide_Load();
                    }
             }); 
        }
    </script>
</body>
</html>