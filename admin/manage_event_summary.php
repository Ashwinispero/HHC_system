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
    <title>Manage Event Summary </title>
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
                            <img src="images/event_summary_big.png" alt="Manage Event Summary"> Manage Events Summary 
                            <a class="btn btn-download pull-right font18" href="manage_events.php?patient_id=<?php if(!empty($_REQUEST['patient_id'])) { echo $_REQUEST['patient_id']; } ?>" data-original-title="" title="">VIEW EVENTS</a>
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                    </div>
                    <div class="pull-right paddingLR0">
                        
                    </div>
                    <div class="clearfix"></div>
                    <div class="EventSummaryListing">
                        <?php include "include_event_summary.php";?>
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
    <div class="modal fade" id="edit_event"> 
        <div class="modal-dialog">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
   <?php  include "include/scripts.php"; ?>
   <link href="css/validationEngine.jquery.css" rel="stylesheet" />
    <script src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery.validationEngine-en.js"></script>
    
    <script src="js/bootbox.js"></script>
    
    <script src="js/jquery.form.js"></script>
    
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
            changePagination('EventsListing','include_events.php','','','','');
        }
        function downloadPDFReport(event_id)
        {
            if(event_id)
            {
                Popup_Display_Load();
                var data1="event_id="+event_id;
                 $.ajax({
                    url: "include_download_pdf.php", type: "post", data: data1, cache: false,
                    success: function (html)
                    {
                        //alert(html);
                        var dataRecipt=html;
                        var maindata = {html:dataRecipt, event_id:+event_id};
                        var siteurl='<?php echo $siteURL;?>';     
                        $.ajax({
                        url: 'download_event_pdf.php',
                        data: maindata,
                        type: 'POST',
                        success: function(result) 
                        {
                                var w = location.href=siteurl+'download_event_pdf.php?export=1&file='+result;
                        }
                        });				
                        Popup_Hide_Load();
                    }
                });

            }
        }
    </script>
</body>
</html>