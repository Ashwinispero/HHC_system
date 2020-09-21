<?php   
    require_once 'inc_classes.php';
    require_once "emp_authentication.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Knowledge Base</title>
    </head>
    <body>
    <?php include "include/header.php"; ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-left-right">
                    <h2 class="page-title">Knowledge Base</h2>
                    <div class="col-lg-8 white-bg">            
                        <!-- ---------------- Event Log start ----------- -->
                        <div id="EventLogDiv">
                            <div class="form-inline serch-box">
                                <div class="form-group col-lg-6">
                                  <div class="row">
                                    <div class="input-group col-lg-11"> 
                                        <span class="input-group-addon text-left" style="width:5%;">
                                            <a href="javascript:void(0);"><img onclick="searchRecords();" src="images/search-icon.png" width="22" height="21" alt="Search icon"></a>
                                        </span>
                                        <input type="text" class="form-control searchKeywords" id="SearchKeyword" name="SearchKeyword" aria-describedby="" />
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group col-lg-6">
                                </div>
                            </div>
                            <div class="KnowledgeDocsListing">
                                <?php include 'include_knowledge_documents.php'; ?>
                            </div>  
                        </div>
                        <!-- ---------------- Event Log End ----------- -->   
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include "include/scripts.php"; ?>
<script>    
    $(document).ready(function() 
    {
        textboxes = $("input.searchKeywords");
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
    
    function OpenPDF(doc_id)
    {
        if(doc_id)
        {
            var data="doc_id="+doc_id+"&action=OpenPDFDoc";
            $.ajax({
                url: "ajax_public_process.php", type: "post", data: data, cache: false,async: false,
                beforeSend: function() 
                {
                },
                success: function (html)
                { 
                    var res=html.trim();
                    
                    if (res.indexOf('success') > -1) 
                    {
                        var words = res.split("htmlSeperator");
                        
                         if(words[1])
                         {
                             var url=words[1];
                             window.open("http://www.google.com", '_blank');
                         }
                    } 
                    else if(res=='doc_doesnot_exit')
                    {
                        return false;
                    }
                    else if(res=='error')
                    {
                        
                    }
                    
                    
                },
                complete : function()
                {
                }
         });
        }
    }
</script>
</body>
</html>