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
    <title>Manage Professionals Document</title>
    <?php include "include/css-includes.php";?>
    <style>.scrollbars{height:400px}
    
/* pac container class is for google locaion display on modal.. do not change it  */
.pac-container {
    z-index: 1051 !important;
}
.ui-autocomplete {
    z-index: 1051 !important;
}
    </style>
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
                            <img src="images/professionals_big.png" alt="Manage Professionals"> Manage Professionals Details            
                                                       
                        </h1>
                    </div>
                </div>
                <div class="col-lg-12 whiteBg">
                <div class="col-lg-12 paddingLR20 paddingt20">
                    <div class="col-lg-3 marginB20 paddingl0">
                        <div class="searchBox" ><input type="text" name="SearchKeyword" id="SearchKeyword" class="data-entry-search" placeholder="Search Professional"/> 
                            <a href="javascript:void(0);" class="pull-right"><img onclick="searchRecords();" src="images/icon-search.png" width="24" height="24" alt="Search"></a>
                        </div>
                    </div>
                    <div class="col-lg-2 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="dp_country" name="reference_type" id="reference_type" onchange="searchRecords();">
                                <option value=""<?php if($_REQUEST['reference_type']=='') { echo 'selected="selected"'; } ?>>Search By Professional</option>
                                 <option value="1"<?php if($_REQUEST['reference_type']=='1') { echo 'selected="selected"'; } ?>>Professional</option>
                                 <option value="2"<?php if($_REQUEST['reference_type']=='2') { echo 'selected="selected"'; } ?>>Vender</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 paddingR0 inline_dp pull-left dropdown">
                        <div class="dd">
                            <select class="dp_country" name="Prof_service_id" id="Prof_service_id" onchange="searchRecords();">
                                <option value="">Search By Services</option>
                                <?php
                                    // Getting All Locations
                                    $ServiceList=$commonClass->GetAllServices();  
                                    foreach($ServiceList as $recListKey => $servicesAll)
                                    {
                                        if($_REQUEST['Prof_service_id']==$servicesAll['service_id'])
                                            echo '<option value="'.$servicesAll['service_id'].'" selected="selected">'.$servicesAll['service_title'].'</option>';
                                        else
                                            echo '<option value="'.$servicesAll['service_id'].'">'.$servicesAll['service_title'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="ProfessionalsDocumentListing">
                        <?php include "include_Professional_Document_Upload.php";?>
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
    <div class="modal fade" id="edit_professional"> 
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
    <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
    <link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
    <script src="js/development-bundle/ui/jquery.ui.core.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.widget.js"></script>
    <script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script>
    <!-- ------------- Timepicker ------------ -->   
    <script type="text/javascript" src="../js/jquery-timepicker-master/jquery.timepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="../js/jquery-timepicker-master/jquery.timepicker.css" />
    <script type="text/javascript" src="../js/jquery-timepicker-master/datepair.js"></script>
    <script type="text/javascript" src="../js/jquery-timepicker-master/jquery.datepair.js"></script>
    
    
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
        changePagination('ProfessionalsDocumentListing','include_Professional_Document_Upload.php','','','','');
    }
    
    function view_professional_document_list(service_professional_id)
    {
        var data1="service_professional_id="+service_professional_id+"&action=vw_professional_document_list";
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
                    $('#edit_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
         }); 
    }
    
	 function vw_add_bank_details(serviceProfId)
    {
        var data1 = "service_professional_id="+serviceProfId+"&action=Add_professional_Bank_details";
         $.ajax({
                url: "professional_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                beforeSend: function() 
                {
                    Popup_Display_Load();
                },
                success: function (html)
                {
                    $('#edit_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(html);
					setTimeout("$('.scrollbars').ClassyScroll();",100);
                },
                complete : function()
                {
                   Popup_Hide_Load();
                }
         }); 
    }
    function add_professional_bank_details(professional_id)
	{
		if($("#frm_add_bank_dtls").validationEngine('validate'))
		{
			$("#frm_add_bank_dtls").ajaxForm({
				beforeSend: function() 
				{
					Display_Load();
				},
				success: function (html)
				{
					var result=html.trim();
					$('#submitForm').prop('disabled', true);
					if(result=='ValidationError')
					{
						bootbox.alert("<div class='msg-error'>There is some validation error please check all fields are proper.</div>");  
					}
					else 
					{
						$('#edit_professional').modal('hide'); 
						if(result=='InsertSuccess')
						{
							bootbox.alert("<div class='msg-success'>Bank details added successfully.</div>", function() 
							{
							changePagination('ProfessionalsDocumentListing','include_Professional_Document_Upload.php','','','','');
							}); 
						} else if(result=='UpdateSuccess')
						{
							bootbox.alert("<div class='msg-success'>Bank details updated successfully.</div>", function() 
							{
							changePagination('ProfessionalsDocumentListing','include_Professional_Document_Upload.php','','','','');
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
                    $("#Account_Name").focus();
               }); 
           }
	}
	
	function changeDocumentStatus(profId, docId) {
		if(profId && docId) {
			var selectedDocVal = $("#doc_Status_" + docId).val();
			if (selectedDocVal == '3') {
				$("#RejectionReasonDiv_" + docId).attr("style", "display:block;");
				$("#btn_update_" + docId).attr("disabled", true);
			} else {
				$("#rejection_reason_" + docId).val('');
				$("#RejectionReasonDiv_" + docId).attr("style", "display:none;");
				$("#btn_update_" + docId).removeAttr("disabled");
				
			}
		}
	}
	
	function keyDownFunction(event, docId) {
		var minLength = 10;
	    var maxLength = 99;
		var selectedRecordVal = $("#rejection_reason_" + docId).val();
		var textLength = ($("#rejection_reason_" + docId).val()).length;
		if (textLength <= maxLength) {
			if (textLength >= minLength) {
				$("#btn_update_" + docId).removeAttr("disabled");
			} else {
				$("#btn_update_" + docId).attr("disabled", true);
			}
			
			if (!$(".docNotiMsg").is(':visible')) {
				$(".docNotiMsg").removeAttr("style", "display:none;");
				$(".docNotiMsg").attr("style", "display:block;");
			}
			$(".docNotiMsg").text("Characters left: " + (maxLength - textLength));
		} else {
			event.preventDefault(); 
		}
	}

	function updateDocumentStatus(profId, docId, recordId) {
		if (profId && docId) {
			var docStatus = $("#doc_Status_" + docId).val();
			var rejectReason = '';
			if (docStatus != '' && docStatus != undefined) {
				rejectReason = $("#rejection_reason_" + docId).val();
			}
			
		
			
			var data1 = "service_professional_id="+profId+"&document_list_id="+docId+"&document_status="+docStatus+"&rejection_reason="+rejectReason+"&Documents_id="+recordId+"&action=update_document_status";
			$.ajax({
				url: "professional_ajax_process.php", type: "post", data: data1, cache: false, async: false,
				beforeSend: function() 
				{
					Popup_Display_Load();
				},
				success: function (html)
				{
					var result = html.trim();
					if (result == 'UpdateSuccess') {
						bootbox.alert("<div class='msg-success'>Document status updated successfully.</div>");
						var selectedDocVal = $("#doc_Status_" + docId).val();
						if (selectedDocVal == '1') {
							$("#doc_Status_" + docId).attr("disabled", true);
							$("#btn_update_" + docId).attr("disabled", true);
						}
						
					} else if (result == 'NotificationError') {
						bootbox.alert("<div class='msg-error'>Error in send push notification.</div>");
					} else if (result == 'Error') {
						bootbox.alert("<div class='msg-error'>Error in update document status.</div>");
					}
				},
				complete : function()
				{
					Popup_Hide_Load();
				}
			});	
		}
	}
	
	function updateDocumentFinalStatus(profId) {
		if (profId) {
			var data1 = "service_professional_id="+profId+"&action=update_document_final_status";
			$.ajax({
				url: "professional_ajax_process.php", type: "post", data: data1, cache: false, async: false,
				beforeSend: function() 
				{
					Popup_Display_Load();
				},
				success: function (html)
				{
					var result = html.trim();
					if (result == 'UpdateSuccess') {
						bootbox.alert("<div class='msg-success'>Document final status updated successfully.</div>", function() {
                            $('#edit_professional').modal('hide');
                            changePagination('ProfessionalsDocumentListing','include_Professional_Document_Upload.php','','','','');
                        });
					} else if (result == 'NotificationError') {
						bootbox.alert("<div class='msg-error'>Error in send push notification.</div>");
					} else if (result == 'Error') {
						bootbox.alert("<div class='msg-error'>Error in update document final status.</div>");
					}
				},
				complete : function()
				{
					Popup_Hide_Load();
				}
			});
		}
	}

    function showDocument(docUrl) {
        if (docUrl) {
            $("#docImage").html("<iframe src="+ docUrl +" height='200' width='300'></iframe>");
        }
    }
</script>
</body>
</html>