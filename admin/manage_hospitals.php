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
    <title>Manage Hospitals</title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:450px;}</style>
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
                            <img src="images/hospitals_big.png" > Manage Hospitals                   
                            <a href="javascript:void(0);" onclick="return vw_add_hospital(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD HOSPITALS</a>                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Hospital "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                       <?php // if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') {echo '<a href="manage_hospitals_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; } ?>
                    </div>
                    <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_hospital_list.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;">
                            <img src="images/icon-download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="HospitalsListing">
                        <?php include "include_hospitals.php";?>
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
    <div class="modal fade" id="edit_hospital"> 
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
            changePagination('HospitalsListing','include_hospitals.php','','','','');
        }
        function vw_add_hospital(value)
        {
            var data1="hospital_id="+value+"&action=vw_add_hospital";
            $.ajax({
                url: "hospital_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                   $('#edit_hospital').modal('show'); 
                   $("#AllAjaxData").html(html);
                   setTimeout("$('.scrollbars').ClassyScroll();",100);
                   $("#frm_add_hospital").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                },
                complete : function()
                {
                   Hide_Load();
                }
            });
        }
        function add_hospital_submit()
        {
           if($("#frm_add_hospital").validationEngine('validate')) 
           {
                $('#submitForm').prop('disabled', true);
                $("#frm_add_hospital").ajaxForm({
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
                        if(result=='hospitalexists')
                        {
                           bootbox.alert("<div class='msg-error'>Hospital details already exists please try another one.</div>"); 
                        }
                        else 
                        {
                            $('#edit_hospital').modal('hide'); 
                             if(result=='InsertSuccess')
                             {
                                  bootbox.alert("<div class='msg-success'>Hospital added successfully.</div>",function()
                                  {
                                      changePagination('HospitalsListing','include_hospitals.php','','','','');
                                  });
                             }
                             else if(result=='UpdateSuccess')
                             {
                                  bootbox.alert("<div class='msg-success'>Hospital updated successfully.</div>",function()
                                  {
                                     changePagination('HospitalsListing','include_hospitals.php','','','',''); 
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
                bootbox.alert("<div class='msg-error'>Please fill the required fields.</div>", function() 
                {
                    $('#submitForm').prop('disabled', false);
                    $("#hospital_name").focus();
                });
           }
        }
        function change_status(hospital_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this hospital ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this hospital ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this hospital ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&hospital_id="+hospital_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                 //  alert(data1);
                   $.ajax({
                       url: "hospital_ajax_process.php", type: "post", data: data1, cache: false,async: false,
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
                            bootbox.alert("<div class='msg-success'>Hospital "+success_msg+" successfully.</div>",function()
                            {
                                changePagination('HospitalsListing','include_hospitals.php','','','','');
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
        function add_more_ip()
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
                 url: "hospital_ajax_process.php?action=AddIPRow", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                       // alert(html);
                       document.getElementById(curr_div).innerHTML = html;
                       $("#frm_add_hospital").validationEngine('attach',{promptPosition : "bottomLeft"}); 
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             });               
           }
        }
        function del_more_ip()
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
               $("#hospital_ip_first_"+j).val('');
               $("#hospital_ip_second_"+j).val('');
               $("#hospital_ip_third_"+j).val('');
               $("#hospital_ip_fourth_"+j).val('');
               Hide_Load();
            }
        }
        function delete_ip_content(val)
        {
            var recordId=val;
            if(recordId)
            {
                bootbox.confirm("Are you sure you want to delete this record ?", function (res) 
                {
                      if(res==true)
                      {
                          var data="hosp_ip_id="+recordId;
                          // alert(data);
                           $.ajax({
                               url: "hospital_ajax_process.php?action=delete_ip_content", type: "post", data: data, cache: false,async: false,
                               beforeSend: function() 
                               {
                                   Display_Load();
                               },
                               success: function (html)
                               {
                                   var result=html.trim();
                                   if(result=='success')
                                   {
                                        bootbox.alert("<div class='msg-success'>Hospital IP deleted successfully.</div>", function() 
                                        {
                                             $("#IPData_"+recordId).remove();
                                             changePagination('HospitalsListing','include_hospitals.php','','','','');
                                        });
                                   }
                                   else if(result=='error')
                                   {
                                      bootbox.alert('<div class="msg-error">Error in deleting option .</div>'); 
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
        }
        function movetoNext(current, nextFieldID) 
        {
            if (current.value.length >= current.maxLength) 
            {
                $("#"+nextFieldID).focus();
            }
        }
        function isNumber(evt) 
        {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) 
            {
                return false;
            }
            return true;
        }
    </script>
</body>
</html>