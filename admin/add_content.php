<?php 
      include "inc_classes.php";
      include "admin_authentication.php";      
      include "pagination-include.php";
      require_once '../classes/commonClass.php';
      $commonClass = new commonClass();
      $hpID = $_REQUEST['hpId'];
      $hospitalID = '';
      if ($hpID) {
          $hospitalID = base64_decode($hpID);
      }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Add Content</title>
    <?php include "include/css-includes.php"; ?>
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
                            <img src="images/add-schedule-big.png" alt="Add Content"> Add Content
                            <a class="btn btn-download pull-right font18" href="manage_hospitals.php" data-original-title="" title="">VIEW HOSPITAL</a>
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                    <div class="col-sm-8">
                        <form  method = "post" name="frm_add_content" id="frm_add_content" action ="hospital_ajax_process.php?action=add_content" autocomplete="off">
                            <div class = "form-group">
                                <label for = "contentType"><b>Content Type</b></label><span class="required">*</span>
                                <input type="hidden" name="hospital_id" id="hospital_id" value="<?php echo $hospitalID; ?>" />
                                <input type="hidden" name="content_id" id="content_id" value="" />
                                <select name = "content_type" id = "content_type" class="form-control" onChange = "getContent(this.value)">
                                    <option value="">-Select-</option>
                                    <option value="1">Header</option>
                                    <option value="2">Footer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for = "contentDesc"><b>Content Description</b></label><span class="required">*</span>
                                <input type="hidden" value="" name="content_value_data" id="content_value_data" />
                                <textarea id = "content_value" name = "content_value" class = "form-control"></textarea>
                            </div>
                            <input type="button" class="btn btn-download" name="btn_add_content" id="btn_add_content" onclick="return add_content_submit();" value="Save changes">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php  include "include/scripts.php"; ?>
    <link href="css/validationEngine.jquery.css" rel="stylesheet" />
    <script src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery.validationEngine-en.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootbox.js"></script>
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/jquery.classyscroll.js"></script>
    <!-- <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script> -->
    <script src="libraries/ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            CKEDITOR.replace( 'content_value', {
                language: 'en',
                uiColor: '#9AB8F3',
                customConfig: '',
                resize_enabled : 'false',
                resize_maxHeight : '300',
                resize_maxWidth : '948',
                resize_minHeight: '200',
                resize_minWidth: '948',
                toolbar:  'Basic'
            });
        });

        /**
        * Add content
        */
        function add_content_submit() {
            var contentData = CKEDITOR.instances.content_value.getData();
            if ($('#content_type').val() != '' && contentData != '') {
                $("#content_value_data").val(contentData);
                $("#frm_add_content").ajaxForm({
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        var result = html.trim();
                        if (result == 'validationError') {
                            bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>"); 
                        } else if (result == 'insertSuccess') {
                            bootbox.alert("<div class='msg-success'>Content details added successfully.</div>",function()
                            {
                                location.reload();
                            });
                        } else if (result == 'updateSuccess') {
                            bootbox.alert("<div class='msg-success'>Content details updated successfully.</div>",function()
                            {
                                location.reload();
                            });
                        } else {
                            bootbox.alert("<div class='msg-error'>Error in insert / update content detail.</div>");
                        }

                        $('#btn_add_content').prop('disabled', false);
                    },
                    complete : function()
                    {
                    Hide_Load();
                    } 
                }).submit();
            } else {
                $('#btn_add_content').prop('disabled', false);
                if ($('#content_type').val() == '') {
                    bootbox.alert("<div class='msg-error'>Please select content type.</div>");
                    return false;
                }
            }
        }

        function getContent(contentType)
        {
            var hospitalId = $("#hospital_id").val();

            if (contentType && hospitalId) {
                var data1="hospital_id="+hospitalId+"&content_type="+contentType;
                $.ajax({
                    url: "hospital_ajax_process.php?action=getContent", type: "post", data: data1, cache: false, async: false,
                    beforeSend: function() 
                    {
                        Popup_Display_Load();
                    },
                    success: function (html)
                    {
                        var result = html.trim();
                        if (result != 'error') {

                            // split content

                            var resultantData = result.split('htmlSeperator');

                            if (resultantData) {
                                console.log("content_id", resultantData[0]);
                                console.log("content_value_data", resultantData[1]);
                                $("#content_id").val(resultantData[0]);
                                $("#content_value_data").val(resultantData[1]);
                                CKEDITOR.instances.content_value.setData(resultantData[1]);
                            }
                        }
                    },
                    complete : function()
                    {
                        Popup_Hide_Load();
                    }
                });
            }
        }
    </script>
</body>
</html>