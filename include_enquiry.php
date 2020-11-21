<?php require_once('inc_classes.php');
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    if($EditedResponseArr['patient_id'])
    {
        $temp_event_id = $EditedResponseArr['event_id'];
        $exi_purpose_id = $EditedResponseArr['purpose_id'];
        
        $enquiryNote=$EditedResponseArr['note'];
    }
    ?>
    <!--datetimepicker-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <!-- Added JS Files --->
    <script src="js/jRating.jquery.js" type="text/javascript"></script>
	<script src="dropdown/chosen.jquery.js" type="text/javascript"></script>
    <script src="dropdown/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
    <!-- Added JS Files --->
    <form class="form-horizontal" name="EnquiryNoteForm" id="EnquiryNoteForm" method="post" action="enquiry_ajax_process.php?action=EnquiryNoteForm">
        <input type="hidden" class="prv_purpose_id" name="enq_purpose_id" id="enq_purpose_id" value="<?php echo $exi_purpose_id;?>" />
        <input type="hidden" name="enquiryEvent_id" id="enquiryEvent_id" value="<?php echo $temp_event_id; ?>" />
<!--        <input type="hidden" name="exist_patient_id" id="exist_patient_id"  />-->
        
        <!-- Enquiry for section start here -->
        <h4 class = "section-head">
            <span><img src = "images/requirnment-icon.png" width = "29" height = "29"></span>
            REQUIREMENTS
        </h4>
        
        <div class = "form-group">
            <div class = "col-sm-12">
                <div class = "form-group">
                    <div class = "col-sm-12 select_requirnment" id = "enquiry_requirment">
                        <select class = "form-control ServiceClass" id = "enquiryRequirnment" name = "enquiryRequirnment[]" multiple = "multiple">
                        <?php
                            $servicesSql = "SELECT service_id, service_title, is_hd_access FROM sp_services WHERE status = '1' ";
                            $serviceList = $db->fetch_all_array($servicesSql);
                            foreach ($serviceList AS $key => $valService)
                            {   
                                echo '<option value = "' . $valService['service_id'] .'">' . $valService['service_title'] . '</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <h4 class = "section-head">
            <span></span>
        </h4>

        <!-- Sub service data start here -->
        <div id="subServiceData">
        </div>
        <!-- Sub service data ends here -->

        <!-- Enquiry for section ends here -->
        
        <h4 class="section-head"><span><img src="images/requirnment-icon.png" width="29" height="29"></span>Enquiry Note</h4>
        <div class="form-group">
            <div class="col-sm-12">
                <textarea name="enquiry_note" id="enquiry_note" class="validate[required]  form-control" placeholder="Enquiry Notes"><?php if(isset($enquiryNote)) echo $enquiryNote;?></textarea>
            </div>
        </div>
		<h4 class="section-head"><span><img src="images/requirnment-icon.png" width="29" height="29"></span>Enquiry Date of Service</h4>
        <input  class="form-control" name="date_of_service" id="date_of_service" value="" width="420">                           
         <br>
        <div class="form-group">
            <div class="col-sm-12">                            
                <input type="button" class="btn btn-primary" id="enquiryNote" name="enquiryNote" value="SUBMIT" onclick="return SubmitEnquiryNote();">
            </div>
        </div>
        <script>
        $('#date_of_service').datetimepicker({ 
		footer: true, 
		modal: true });
    </script>
        </form>
<?php
}?>