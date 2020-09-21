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
    <title>Manage Patients </title>
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
                            <img src="images/patients_big.png" alt="Manage Patients"> Manage Patients                                               
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" >
                            <input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Patient "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                       <?php if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') { echo '<a href="manage_patients_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; } ?>
                    </div>
                    <div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="window.open('csv_patient_list.php','_self','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=800,height=600,directories=no,location=no'); return false;" >
                            <img src="images/icon-download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="PatientsListing">
                        <?php include "include_patients.php";?>
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
    <div class="modal fade" id="edit_patient"> 
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
            changePagination('PatientsListing','include_patients.php','','','','');
        }
        function change_status(patient_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this patient ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this patient ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this patient ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&patient_id="+patient_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                   //alert(data1);
                   $.ajax({
                       url: "patient_ajax_process.php", type: "post", data: data1, cache: false,async: false,
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
                             bootbox.alert("<div class='msg-success'>Patient "+success_msg+" successfully.</div>", function() 
                             {
                                 changePagination('PatientsListing','include_patients.php','','','','');
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
        function view_patient(patient_id)
        {
            var data1="patient_id="+patient_id+"&action=vw_patient";
            //alert(data1);
             $.ajax({
                    url: "patient_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                       Popup_Display_Load();
                    },
                    success: function (html)
                    {
                       // alert(html);
                        $('#edit_patient').modal({backdrop: 'static',keyboard: false}); 
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