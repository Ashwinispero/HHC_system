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
    <title>Manage Feedback</title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:300px;}</style>
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
                            <img src="images/manage-feedback-big.png" > Manage Feedback                   
                            <a href="javascript:void(0);" onclick="return vw_add_feedback(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD FEEDBACK</a>                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Feedback "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                       <?php if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') {  echo '<a href="manage_feedback_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; } ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="FeedbackListing">
                        <?php include "include_feedback.php";?>
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
    <div class="modal fade" id="edit_feedback"> 
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
            changePagination('FeedbackListing','include_feedback.php','','','','');
        }
        function vw_add_feedback(value)
        {
            var data1="feedback_id="+value+"&action=vw_add_feedback";
            $.ajax({
                url: "feedback_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
                },
                success: function (html)
                {
                   $('#edit_feedback').modal('show'); 
                   $("#AllAjaxData").html(html);
                   $("#OptionList").hide();    
                   setTimeout("$('.scrollbars').ClassyScroll();",100);
                   $("#frm_add_feedback").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
            });
        }
        function add_feedback_submit()
        {
           if($("#frm_add_feedback").validationEngine('validate'))
           {
                $("#frm_add_feedback").ajaxForm({
                    beforeSend: function() 
                    {
                       Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                        $('#submitForm').prop('disabled', true);
                        //alert(result);
                        if(result=='ValidationError')
                        {
                           bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                        }
                        if(result=='feedbackexists')
                        {
                           bootbox.alert("<div class='msg-error'>Feedback details already exists, it may be on trash list, so please try another one.</div>"); 
                        }
                        else 
                        {
                            $('#edit_feedback').modal('hide'); 
                             if(result=='InsertSuccess')
                             {
                                bootbox.alert("<div class='msg-success'>Feedback added successfully.</div>", function() 
                                {
                                    changePagination('FeedbackListing','include_feedback.php','','','','');
                                }); 
                             }
                             else if(result=='UpdateSuccess')
                             {
                                bootbox.alert("<div class='msg-success'>Feedback updated successfully.</div>", function() 
                                {
                                    changePagination('FeedbackListing','include_feedback.php','','','','');
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
                   $("#question").focus();
               });
           }
        }
        function vw_edit_feedback(value)
        {
            var data1="feedback_id="+value+"&action=vw_edit_feedback";
            $.ajax({
                url: "feedback_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
                },
                success: function (html)
                {
                   $('#edit_feedback').modal('show'); 
                   $("#AllAjaxData").html(html);
                   setTimeout("$('.scrollbars').ClassyScroll();",100);
                   $("#frm_add_feedback").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
            }); 
        }
        function edit_feedback_submit()
        {
            if($("#frm_edit_feedback").validationEngine('validate')) 
            {
                $('#submitForm').prop('disabled', true);
                $("#frm_edit_feedback").ajaxForm({
                    beforeSend: function() 
                    {
                       Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                        $('#submitForm').prop('disabled', true);
                        //alert(result);
                        $('#edit_feedback').modal('hide'); 
                        if(result=='InsertSuccess')
                        {
                            bootbox.alert("<div class='msg-success'>Feedback added successfully.</div>", function() 
                            {
                                changePagination('FeedbackListing','include_feedback.php','','','','');
                            });
                        }
                        else if(result=='UpdateSuccess')
                        {
                            bootbox.alert("<div class='msg-success'>Feedback updated successfully.</div>", function() 
                            {
                                changePagination('FeedbackListing','include_feedback.php','','','','');
                            });
                        }
                        else
                        {
                            bootbox.alert("<div class='msg-error'>Feedback already exists please try another one.</div>");
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
               bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
               {
                   $('#submitForm').prop('disabled', false);
                   $("#question").focus();
               }); 
            }
        }
        function view_feedback(feedback_id)
        {
            var data1="feedback_id="+feedback_id+"&action=vw_feedback";
            $.ajax({
                url: "feedback_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
                },
                success: function (html)
                {
                   $('#edit_feedback').modal('show'); 
                   $("#AllAjaxData").html(html);
                   $("#frm_add_feedback").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
            });
        }
        function change_status(feedback_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this feedback question ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this feedback question ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this feedback question ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&feedback_id="+feedback_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                 //  alert(data1);
                   $.ajax({
                       url: "feedback_ajax_process.php", type: "post", data: data1, cache: false,async: false,
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
                              bootbox.alert("<div class='msg-success'>Feedback "+success_msg+" successfully.</div>",function()
                              {
                                  changePagination('FeedbackListing','include_feedback.php','','','','');
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
        function getOption(optionType)
        {
           if(optionType)
           {
              if(optionType) 
              {
                 if(optionType !='1' && optionType !='4')
                 {
                    $("#OptionList").show();
                    $(".multioptions").show();
                    $("#AddMoreDiv").show();
                 }
                 else
                 {
                    $("#OptionList").hide();
                    $(".multioptions").hide();
                    $("#AddMoreDiv").hide();
                 }
              }
           }
        }
        function add_more_option()
        {
          var i = parseInt(document.getElementById('extras').value);

           if(i==0)
           {
               i=1;
           }
           else
           {
               i= parseInt(i)+1;
           }
           document.getElementById('extras').value= i;

           var next = parseInt(i)+1;
           var curr_div = "div_"+i;

           // alert(curr_div);

           if(document.getElementById(curr_div).style.display === 'none')
           {
               document.getElementById(curr_div).style.display = 'block';
           }
           else
           {
                var data1="curr_div="+i;
                // alert(data1);
                $.ajax({
                    url: "feedback_ajax_process.php?action=AddOptionRow", type: "post", data: data1, cache: false,async: false,
                   beforeSend: function() 
                   {
                      Display_Load();
                   },
                   success: function (html)
                   {
                       // alert(html);
                       Display_Load();
                       document.getElementById(curr_div).innerHTML = html;
                       $("#frm_add_feedback").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                       Hide_Load();
                    },
                   complete : function()
                   {
                      Hide_Load();
                   }
                });               
           }
        }
        function del_more_option()
        {
            var j=document.getElementById('extras').value;
            
            if(j != 0)
            {
               Display_Load();
               var curr_div = "div_"+j;
               document.getElementById(curr_div).style.display='none';
               previouss= j;
               if(previouss==0)
               {
                   previouss=0;
               }
                else
                {
                    previouss= parseInt(j)-1;
                }
               document.getElementById('extras').value=previouss;
               $("#option_value"+j).val('');
               Hide_Load();
            }
        }
        function delete_option(option_id)
        {
            bootbox.confirm("Are you sure you want delete this option ?", function (res) 
            {   
                if(res==true)
                {
                    if(option_id)
                    {
                        var data1 = "feedback_option_id="+option_id+"&action=delete_feedback_option" ;
                        //alert(data1);
                        $.ajax({
                                url: "feedback_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                                beforeSend: function() 
                                {
                                   Display_Load();
                                },
                                success: function (html)
                                {
                                    var result = html.trim();
                                    //alert(result);
                                    if(result=='success')
                                    {
                                        bootbox.alert("<div class='msg-success'>Feedback Option deleted successfully.</div>", function() 
                                        {
                                             $("#OptionData_"+option_id).remove();
                                             changePagination('FeedbackListing','include_feedback.php','','','','');
                                        });
                                    }
                                    else
                                    {
                                       bootbox.alert("<div class='msg-error'>Error In Operation.</div>"); 
                                    } 
                                },
                                complete : function()
                                {
                                   Hide_Load();
                                }
                         });
                    }
                }
            });   
        }
    </script>
</body>
</html>