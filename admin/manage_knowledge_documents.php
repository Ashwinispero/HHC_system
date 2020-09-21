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
    <title>Manage Knowledge Documents</title>
    <?php include "include/css-includes.php";?>
<!--    <style>.scrollbars{height:400px}</style>-->
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
                            <img src="images/knowledge_docs_big.png" alt="Manage Knowledge Documents"> Manage Knowledge Documents                
                            <a href="javascript:void(0);" onclick="return vw_add_knowledge_document(0);" data-toggle="modal" class="btn btn-download pull-right font18">+ ADD KNOWLEDGE DOCUMENT</a>                            
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-4 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Knowledge Document "/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="pull-right paddingLR0" style="padding-left:15px !important;">
                       <?php if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1') { echo '<a href="manage_knowledge_documents_trash.php" data-toggle="tooltip" title="Trash"><img src="images/trash.png" alt="trash"></a>'; } ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="KnowledgeDocsListing">
                        <?php include "include_knowledge_documents.php";?>
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
    <div class="modal fade" id="edit_knowledge_document"> 
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
    <script src="js/file_validation.js"></script>
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
            changePagination('KnowledgeDocsListing','include_knowledge_documents.php','','','','');
        }
        function add_more_document()
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
                 url: "knowledge_documents_ajax_process.php?action=AddDocumentRow", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Display_Load();
                },
                success: function (html)
                {
                   // alert(html);
                   document.getElementById(curr_div).innerHTML = html;
                },
                complete : function()
                {
                   Hide_Load();
                }
             });               
           }
        }
        function del_more_document()
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
               Hide_Load();
            }
        }
        function vw_add_knowledge_document(value)
        {
            var _URL = window.URL || window.webkitURL;
            var data1="knowledge_document_id="+value+"&action=vw_add_knowledge_document";
            $.ajax({
                url: "knowledge_documents_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                   Popup_Display_Load();
                },
                success: function (html)
                {
                   $('#edit_knowledge_document').modal('show'); 
                   $("#AllAjaxData").html(html);
                  // setTimeout("$('.scrollbars').ClassyScroll();",100);
                   $("#frm_add_knowledge_document").validationEngine('attach',{promptPosition : "bottomLeft"});
                   $(document).on("change", ".docfile", function()
                   {
                        var knowledge_doc_file;
                        knowledge_doc_file = $('.docfile')[0].files[0];
                        var f_type = "Document File";
                        var f_ext = $(".docfile").val().split('.').pop().toLowerCase();
                        if (window.File && window.FileReader && window.FileList && window.Blob)
                        {
                            var f_size = $('.docfile')[0].files[0].size;
                        }
                        var f_valid_format =['pdf']; 
                        var validate_file = chk_file_validation(f_type,f_size,f_ext,f_valid_format);
                        
                        if(validate_file !='success')
                        {
                            bootbox.alert("<div class='msg-error'>"+validate_file+"</div>", function() 
                            {
                               $('.docfile').val('');
                            });
                        // $(this).removeClass("formErrorSelf");
                         return false;
                        }
                        else if($(".docfile")[0].files[0].size >5242880)
                        {
                            bootbox.alert("<div class='msg-error'>Document should be less than 5 MB.</div>", function() 
                            {
                               $(".docfile").val();
                            });
                            //$(this).removeClass("formErrorSelf");
                            return false;  
                        }
                        else 
                        {
                            this.focus();
                           // $(this).removeClass("formErrorSelf");
                            var inputs = $(this).closest('form').find(':input'); inputs.eq( inputs.index(this)+ 1 ).focus();
                            return true;
                        }
                    });
                },
                complete : function()
                {
                    Popup_Hide_Load();
                }
            });
        }
        function add_knowledge_document_submit()
        {
            if($("#frm_add_knowledge_document").validationEngine('validate'))
            {
                $('#submitForm').prop('disabled', true);
                $("#frm_add_knowledge_document").ajaxForm({
                    beforeSend: function() 
                    {
                       Display_Load();
                    },
                    success: function (html)
                    {
                        var result=html.trim();
                      // alert(result);

                       if(result=='ValidationError')
                       {
                          bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>");  
                       }
                       if(result=='knowledgedocexists')
                       {
                          bootbox.alert("<div class='msg-error'>Knowledge document already exists, it may be on trash list, so please try another one.</div>"); 
                       }
                       else 
                       {
                            $('#edit_knowledge_document').modal('hide'); 
                            if(result=='InsertSuccess')
                            {
                                 bootbox.alert("<div class='msg-success'>Knowledge document added successfully.</div>",function()
                                 {
                                     changePagination('KnowledgeDocsListing','include_knowledge_documents.php','','','','');
                                 });
                            }
                            else if(result=='UpdateSuccess')
                            {
                                 bootbox.alert("<div class='msg-success'>Knowledge document updated successfully.</div>",function()
                                 {
                                     changePagination('KnowledgeDocsListing','include_knowledge_documents.php','','','','');
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
                     $("#title").focus();
                 });
            }
        }
        function change_status(document_id,curr_status,actionVal)
        { 
           var prompt_msg="";
           var success_msg=""; 
           if(actionVal=='Active')
           {
               prompt_msg ="Are you sure you want to activate this knowledge document ?"; 
               success_msg="activated";  
           }
           else if(actionVal=='Inactive')
           {
               prompt_msg ="Are you sure you want to inactive this knowledge document ?"; 
               success_msg="deactivated";  
           }
           else if(actionVal=='Delete')
           {
               prompt_msg="Are you sure you want to delete this knowledge document ?";
               success_msg="deleted";
           }
           bootbox.confirm(prompt_msg, function (res) 
           {
               if(res==true)
               {
                   var data1 = "login_user_id=<?php echo $_SESSION['admin_user_id']; ?>&knowledge_document_id="+document_id+"&curr_status="+curr_status+"&actionval="+actionVal+"&action=change_status";
                   //alert(data1);
                   $.ajax({
                       url: "knowledge_documents_ajax_process.php", type: "post", data: data1, cache: false,async: false,
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
                               bootbox.alert("<div class='msg-success'>Knowledge document "+success_msg+" successfully.</div>",function()
                               {
                                   changePagination('KnowledgeDocsListing','include_knowledge_documents.php','','','','');
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