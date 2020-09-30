<?php   require_once 'inc_classes.php';
        require_once "emp_authentication.php";
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";
        
        require_once 'classes/eventClass.php';
        $eventClass=new eventClass();
?>
<?php
if($_REQUEST['EID'])
{
    $requested_id = $_REQUEST['EID'];
    $EID = base64_decode($requested_id);
    $_REQUEST['event_id'] = $EID;
    
    $arg['event_id'] = $EID;
    $EditedResponseArr = $eventClass->GetEventCaller($arg);
    
    $argDoc['doctor_type'] = '1';
    $argDoc['event_id'] = $EID;
    $EditedResponseDoctor = $eventClass->GetDoctorConsultant($argDoc);
    
    $argCons['doctor_type'] = '2';
    $argCons['event_id'] = $EID;
    $EditedResponseConsultant = $eventClass->GetDoctorConsultant($argCons);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Plan of Care</title>
<style type="text/css" rel="stylesheet">
#container {
	width: 940px;
	margin: 0 auto;
}
.consent_form {
	position: relative;
}
.consent_form div {
	position: relative;
	margin-bottom: 5px;
}
.consent_form label {
	position: relative;
	display: inline-block;
	border-bottom: 1px dotted #d8d8d8;
	min-width: 500px;
}
.label_small {
	position: relative;
	display: inline-block;
	border-bottom: 1px dotted #d8d8d8;
	min-width: 150px !important;
}
.consent_form span {
	font-size: 12px;
	color: #666;
}
 @media only screen and (max-width: 768px) {
#container {
	width: 90%;
	margin: 0 auto;
}
}
</style>
<body>
<?php include "include/header.php"; ?>
<section>
  <div class="container-fluid">
    <div class="row"> 
      <!-- Left Start-->
      <div class="col-lg-4 col-left">
        <div id="content-1" class="content mCustomScrollbar">
          <div id="callerDiv" >
            <?php include "include_callers.php"; ?>
          </div>
          <div class="line-seprator"></div>
          <div id="PatientDiv">
            <h4 class="section-head"><span><img src="images/patient-icon.png" width="29" height="29"></span>CONSENT <a href="javascript:void(0);" class="edit-details"><span aria-hidden="true" class="glyphicon glyphicon-pencil pull-right"></span></a></h4>
            <div role="tabpanel"> 
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active" id="ExTab"><a href="#existing" aria-controls="home" role="tab" data-toggle="tab">EXISTING</a></li>
                <li role="presentation" id="NewTab"><a href="#new" aria-controls="profile" role="tab" data-toggle="tab" id="NewPatienttab">NEW</a></li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <input type="hidden" name="selected_patient_exist" id="selected_patient_exist" value="" >
                <div role="tabpanel" class="tab-pane active" id="existing">
                  <div class="exPatientListing">
                    <?php include "include_existing_patient.php"; ?>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane " id="new">
                  <div class="newPatientListing">
                    <?php include "include_new_patient.php"; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="line-seprator"></div>
          <div id="PatientDiv">
            <h4 class="section-head"><span><img src="images/patient-icon.png" width="29" height="29"></span>PATIENT Details <a href="javascript:void(0);" class="edit-details"><span aria-hidden="true" class="glyphicon glyphicon-pencil pull-right"></span></a></h4>
            <div role="tabpanel"> 
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active" id="ExTab"><a href="#existing" aria-controls="home" role="tab" data-toggle="tab">EXISTING</a></li>
                <li role="presentation" id="NewTab"><a href="#new" aria-controls="profile" role="tab" data-toggle="tab" id="NewPatienttab">NEW</a></li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <input type="hidden" name="selected_patient_exist" id="selected_patient_exist" value="" >
                <div role="tabpanel" class="tab-pane active" id="existing">
                  <div class="exPatientListing">
                    <?php include "include_existing_patient.php"; ?>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane " id="new">
                  <div class="newPatientListing">
                    <?php include "include_new_patient.php"; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="requirementDiv">
            <div class="requirementListing" >
              <?php include "include_requirements.php"; ?>
            </div>
          </div>
          <div class="EnquiryNoteListing" style="display: none;">
            <form class="form-horizontal" name="EnquiryNoteForm" id="EnquiryNoteForm" method="post">
              <h4 class="section-head"><span><img src="images/requirnment-icon.png" width="29" height="29"></span>Enquiry Note</h4>
              <div class="form-group">
                <div class="col-sm-12">
                  <textarea name="enquiry_note" id="enquiry_note" class="form-control" placeholder="Enquiry Notes"></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <input type="button" class="btn btn-primary" id="enquiryNote" name="enquiryNote" value="SUBMIT" onclick="return SubmitEnquiryNote();">
                </div>
              </div>
            </form>
          </div>
          <div id="generalinfoDiv" style="display: none;">
            <form class="form-horizontal" name="generalInfoForm" id="generalInfoForm" method="post" >
              <h4 class="section-head"><span><img src="images/requirnment-icon.png" width="29" height="29"></span>General Information</h4>
              <div class="form-group">
                <div class="col-sm-12">
                  <textarea name="general_info" id="general_info" class="form-control" placeholder="General Information"></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12">
                  <input type="button" class="btn btn-primary" id="general_infoSubmit" name="general_infoSubmit" value="SUBMIT" onclick="return generalInfo();">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Left End-->
      <div class="col-lg-8 col-left-right">
        <div class="col-lg-12 white-bg" > 
          <!-- ---------------- Event Log start ----------- -->
          <div id="RightSideDiv" style="display: n one;">
            <h2 class="page-title">Plan of Care <small class="pull-right event-id">Event ID:   SPHHCAAA123PN/E001</small></h2>
            <a href="Javascript:void(0);" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#viewEventDetails">Display Popup</a> 
            
            <!-- Modal -->
            <div class="modal fade bs-example-modal-lg" id="viewEventDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">View Event Details</h4>
                  </div>
                  <div class="modal-body">
                    <div class="mCustomScrollbar">
                      <h4 class="section-head text-left"><span><img height="29" width="29" src="images/coller-icon.png" class="mCS_img_loaded"></span> CALLER DETAILS</h4>
                      <form class="form-horizontal" style="padding-left:50px;">
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Contact :</label>
                          <div class="col-sm-10"> 9552509302 </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Name :</label>
                          <div class="col-sm-10"> Nitin Shinde </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Relation :</label>
                          <div class="col-sm-10"> Self </div>
                        </div>
                      </form>
                      <div class="line-seprator"></div>
                      <h4 class="section-head"><span><img height="29" width="29" src="images/patient-icon.png" class="mCS_img_loaded"></span> Consent </h4>
                      <div class="consent_form">
                        <div>I,
                          <label id="" style="font-weight:bold;">Nitin 	Suryabhan Shinde</label>
                          Age
                          <label id="" style="font-weight:bold;" class="label_small"></label>
                        </div>
                        <div>Residing at
                          <label id="" style="font-weight:bold;"></label>
                        </div>
                        <div>am suffering from
                          <label id="" style="font-weight:bold;"></label>
                          <span>(Provisional/final Diagnosis)</span></div>
                        <div>and I,
                          <label id="" style="font-weight:bold;"></label>
                          <span>(Name of Relative)</span> Age
                          <label id="" class="label_small" style="font-weight:bold;"></label>
                        </div>
                        <div>Residing at
                          <label id="" style="font-weight:bold;"></label>
                        </div>
                        <div>am made aware that
                          <label id="" style="font-weight:bold;"></label>
                          <span>(Name of Patient)</span> is suffering from
                          <label id="" style="font-weight:bold;"></label>
                          <span>(Provisional/final Diagnosis)</span></div>
                        <ul type="1" style="margin-left:0px; padding-left:20px;">
                          <li >
                            <div>That, above named patient has been taking treatment from
                              <label id="" style="font-weight:bold;"></label>
                              <span>(Name of Consultant) </span> for above mentioned disease.</div>
                          </li>
                          <li>
                            <div>That, above named consultant has explained that, patient now can be treated/cared at home.</div>
                          </li>
                          <li>
                            <div>That, Spero Home Healthcare professional has explained us all the details including 
                              limitations, advantages, disadvantages, effects, side-effects, pros, conts etc. of Home
                              Helthcare including instructions and rules
                              <label id="" style="font-weight:bold;"></label>
                              <span>(Name of procedure)</span></div>
                          </li>
                          <li>
                            <div>That, after understanding limitations of Home Helth Care all the details, I am giving free consent for
                              providing Home Helth Care including
                              <label id="" style="font-weight:bold;"></label>
                              <span>(Name of procedure/service)</span> to
                              <label id="" style="font-weight:bold;"></label>
                              <span>Name of Patient</span></div>
                          </li>
                          <li>
                            <div>That, we have been made aware by Spero professional that, Spero Home Health Care is involved only
                              in executing medical instructions/advices/oedersof concerned consultant. Further, we have been made
                              aware that, we shall contact/consult concerned consultant/Hospital in case of further medical management
                              of disease condition.</div>
                          </li>
                          <li>
                            <div>That, we have been made aware by Spero professional that, we shall follow all instructions/advices/orders of 
                              Hospital given by concerned consultant and we shall consult /visit concerned consultant/Hospital for follow -up, 
                              if any. </div>
                          </li>
                        </ul>
                      
                      <div style="clear:both; margin-top:20px;"></div>
                      <div class="col-lg-5 pull-left text-center" style="margin-top:20px;">Signature with Name of Patient</div>
                      <div class="col-lg-5 pull-right text-center" style="margin-top:20px;">Signature with Name of <br>Relative/Guardin/Attendant/Next Friend of Patient</div>
                      </div>
                       <div style="clear:both;"></div>
                      <div class="line-seprator"></div>
                      <h4 class="section-head"><span><img height="29" width="29" src="images/patient-icon.png" class="mCS_img_loaded"></span> PATIENT Details </h4>
                      <form class="form-horizontal" style="padding-left:50px;">
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Name :</label>
                          <div class="col-sm-10"> Nitin Shinde </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Residential Address :</label>
                          <div class="col-sm-10"> A1 - 605, Gannga Sarovar old Mundhava Road, Wadgaon Sheri, Pune - 411014. </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Location :</label>
                          <div class="col-sm-10"> Wadgaon Sheri </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Pin Code :</label>
                          <div class="col-sm-10"> 411014 </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Mobile :</label>
                          <div class="col-sm-10"> 9552509302 </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Email Id :</label>
                          <div class="col-sm-10"> nitupune@gmail.com </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Landline :</label>
                          <div class="col-sm-10"> 020 25856958 </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">DOB :</label>
                          <div class="col-sm-10"> 14/01/1985 </div>
                        </div>
                        <div class="line-dotted"></div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Family Doctor :</label>
                          <div class="col-sm-10"> Sampat Patil </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Contact No :</label>
                          <div class="col-sm-10"> 9898989898 </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Email id :</label>
                          <div class="col-sm-10"> SampatPatil@hotmail.cpm </div>
                        </div>
                        <div class="line-dotted"></div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Consultant :</label>
                          <div class="col-sm-10"> Nitin Shinde </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Contact No :</label>
                          <div class="col-sm-10"> 9898989898 </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-2 control-label" style="padding-top:0px;">Email id :</label>
                          <div class="col-sm-10"> NitinShinde@hotmail.cpm </div>
                        </div>
                      </form>
                      <div class="line-seprator"></div>
                      <h4 class="section-head"><span><img height="29" width="29" src="images/requirnment-icon.png" class="mCS_img_loaded"></span>REQUIREMENTS</h4>
                      <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th width="30%">HCA Serives</th>
                            <th width="22%">Selectd Service</th>
                            <th>Note</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Select Transport ambulance services </td>
                            <td>Ac Ambulance </td>
                            <td>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when</td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="line-seprator"></div>
                      <h4 class="section-head"><span><img height="29" width="29" src="images/plan-of-care.png" class="mCS_img_loaded"></span> PLAN OF CARE </h4>
                      <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th>Professional</th>
                            <th>Recommended Service</th>
                            <th>Date</th>
                            <th>From / To</th>
                            <th>Cost</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>HCA </td>
                            <td>4 Hours Service</td>
                            <td>28/03/2015</td>
                            <td>12:00pm to 4:00pm</td>
                            <td>1500/-</td>
                          </tr>
                          <tr>
                            <td>Nurse </td>
                            <td>Enema </td>
                            <td>12/05/2015</td>
                            <td>12:00pm to 2:00pm</td>
                            <td>1000/-</td>
                          </tr>
                          <tr class="tax-row">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>(Includes Tax):</td>
                            <td>0</td>
                          </tr>
                          <tr class="total-row">
                            <td>TOTAL ESTIMATED COST</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Rs. 2500/-</td>
                          </tr>
                          <tr class="tax-row">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">Conform Estimated Cost:</td>
                            <td><span class="available">Yes</span> <span class="busy">No</span></td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="line-seprator"></div>
                      <h4 class="section-head"><span><img height="29" width="29" src="images/profesnals.png" class="mCS_img_loaded"></span> PROFESSIONAL </h4>
                      <table  class="table table-bordered-hca" cellspacing="0" width="100%">
                        <thead style="color:#fff !important">
                          <tr class="color-row">
                            <th>SP NO.</th>
                            <th>NAME</th>
                            <th>SKILL-SET</th>
                            <th>TYPE</th>
                            <th>LOCATION</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>SPHCA001 </td>
                            <td>Pandit Joglekar</td>
                            <td>Skill-Set</td>
                            <td>HCA</td>
                            <td>Karve Nagar</td>
                          </tr>
                          <tr>
                            <td>SPHCA001 </td>
                            <td>Pandit Joglekar</td>
                            <td>Skill-Set</td>
                            <td>HCA</td>
                            <td>Karve Nagar</td>
                          </tr>
                          <tr>
                            <td>SPHCA001 </td>
                            <td>Pandit Joglekar</td>
                            <td>Skill-Set</td>
                            <td>Nurs</td>
                            <td>Karve Nagar</td>
                          </tr>
                        </tbody>
                      </table>
                      <h4 class="section-head"><span><img height="29" width="29" src="images/feedback.png" class="mCS_img_loaded"></span> FEEDBACK </h4>
                      <table  class="table table-bordered-hca" cellspacing="0" width="100%">
                        <tbody>
                          <tr>
                            <td>1</td>
                            <td>Lorem Ipsum is simply dummy text of the printing </td>
                            <td><img src="images/rating.png" /></td>
                          </tr>
                          <tr>
                            <td>2</td>
                            <td>Lorem Ipsum is simply dummy text of the printing </td>
                            <td><img src="images/rating.png" /></td>
                          </tr>
                          <tr>
                            <td>3</td>
                            <td>Lorem Ipsum is simply dummy text of the printing </td>
                            <td><img src="images/rating.png" /></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer"> Download: <a href="javascript:void(0);"><img src="images/pdf-icon.png" /></a> &nbsp; <a href="javascript:void(0);"><img src="images/exl.png" /></a> </div>
                </div>
              </div>
            </div>
            
            <!-- Modal End--> 
            
          </div>
          <!-- ---------------- Event Log End ----------- --> 
          
          <!-- ---------------- Search Patient ----------- -->
          <div id="SearchRightSideDiv" style="display: none;">
            <div class="searchPatientListing">
              <?php include "search_existing_patient.php"; ?>
            </div>
          </div>
          <!-- ---------------- Search Patient end ----------- -->
          
          <div id="PlanOfCareDiv" style="display: none;">
            <div class="searchPatientListing">
              <?php // include "include_plan_of_care.php"; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Popup code start --->
  
  <!-- Modal Popup code end ---> 
  <!-- Modal Popup code start --->
  <div class="modal fade" id="vw_professional">
    <div class="modal-dialog" style="width:900px !important;">
      <div class="modal-content" id="AllAjaxData"> </div>
      <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
  <!-- Modal Popup code end ---> 
</section>
<?php include "include/scripts.php"; ?>
<script>
		(function($){
			$(window).load(function(){
				
				$("#viewEventDetails .modal-body").mCustomScrollbar({
					setHeight:500,
					//theme:"minimal-dark"
				});
				
				
			});
		})(jQuery);
	</script> 
<script src="js/easyResponsiveTabs.js"></script> 
<!-- J slider--> 
<script type="text/javascript" src="js/jslider/tmpl.js"></script> 
<script type="text/javascript" src="js/jslider/jquery.dependClass-0.1.js"></script> 
<script type="text/javascript" src="js/jslider/draggable-0.1.js"></script> 
<script type="text/javascript" src="js/jslider/jquery.slider.js"></script> 
<!-- j Slider --> 
<script src="js/jquery.form.js"></script>
<link href="css/validationEngine.jquery.css" rel="stylesheet" />
<script src="js/jquery.validationEngine.js"></script> 
<script src="js/jquery.validationEngine-en.js"></script> 
<!-- ------------- datepicker ------------ -->
<link rel="stylesheet" href="js/development-bundle/themes/base/jquery-ui.css" />
<script src="js/development-bundle/ui/jquery.ui.core.js"></script> 
<script src="js/development-bundle/ui/jquery.ui.widget.js"></script> 
<script src="js/development-bundle/ui/jquery.ui.datepicker.js"></script> 
<!-- ------------- Timepicker ------------ --> 
<script type="text/javascript" src="js/jquery-timepicker-master/jquery.timepicker.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery-timepicker-master/jquery.timepicker.css" />
<script type="text/javascript" src="js/jquery-timepicker-master/datepair.js"></script> 
<script type="text/javascript" src="js/jquery-timepicker-master/jquery.datepair.js"></script> 
<script type="text/javascript" src="js/freoewall.js"></script> 
<script type="text/javascript" charset="utf-8">
      
      jQuery("#Slider5").slider({ from: 480, to: 1020, step: 15, dimension: '', scale: ['0', '5', '10', '15', '20', '25', '30', '35', '40', '45'], limits: false, calculate: function( value ){
        var hours = Math.floor( value / 60 );
        var mins = ( value - hours*60 );
        return (hours < 10 ? "0"+hours : hours) + ":" + ( mins == 0 ? "00" : mins );
      }})
    </script> 
<script>    
    $(document).ready(function() 
        {
			
			 $(document).ready(function() {
        //Horizontal Tab
        $('#parentHorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function(event) { // Callback function if tab is switched
                var $tab = $(this);
                var $info = $('#nested-tabInfo');
                var $name = $('span', $info);
                $name.text($tab.text());
                $info.show();
            }
        });
		
		
		$('#parentHorizontalTab1').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_2', // The tab groups identifier
            activate: function(event) { // Callback function if tab is switched
                var $tab = $(this);
                var $info = $('#nested-tabInfo2');
                var $name = $('span', $info);
                $name.text($tab.text());
                $info.show();
            }
        });

       

        //Vertical Tab
        $('#parentVerticalTab').easyResponsiveTabs({
            type: 'vertical', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            closed: 'accordion', // Start closed if in accordion view
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function(event) { // Callback function if tab is switched
                var $tab = $(this);
                var $info = $('#nested-tabInfo2');
                var $name = $('span', $info);
                $name.text($tab.text());
                $info.show();
            }
        });
    });
			
            $("#CallerForm").validationEngine('attach',{promptPosition : "bottomLeft"}); 
            $("#NewPatientForm").validationEngine('attach',{promptPosition : "bottomLeft"}); 
            $('[data-toggle="tooltip"]').tooltip();
            $('.datepicker').datepicker({ 
                       changeMonth: true,
                       changeYear: true, 
                       dateFormat: 'yy-mm-dd',
                       yearRange: '1900:+0',
                       maxDate:new Date(),
                       onSelect: function() {
                            $('#datepicker').val($(this).datepicker({
                              dateFormat: 'yy-mm-dd'
                            }).val());
                          }
                   });
                   
                $('.datepicker_ex').datepicker({ 
                       changeMonth: true,
                       changeYear: true, 
                       dateFormat: 'yy-mm-dd',
                       yearRange: '1900:+0',
                       maxDate:new Date(),
                       onSelect: function() {
                            $('#datepicker').val($(this).datepicker({
                              dateFormat: 'yy-mm-dd'
                            }).val());
                          }
                });
                   
                $('.datepicker_from').datepicker({ 
                    changeMonth: true,
                    changeYear: true, 
                    dateFormat: 'yy-mm-dd',
                    yearRange: '2015:+0',
                    maxDate:new Date(),
                    onSelect: function() {
                         searchRecords();
                       }
                });

                $('.datepicker_to').datepicker({ 
                    changeMonth: true,
                    changeYear: true, 
                    dateFormat: 'yy-mm-dd',
                    yearRange: '2015:+0',
                    maxDate:new Date(),
                    onSelect: function() {
                         searchRecords();
                       }
                });
                
            textboxes = $("input.searchKeywords");
            $(textboxes).keydown (checkForEnterSearch);
            
            //datesearch = $("input.datepicker_from");
            //$(datesearch).keyup(checkForEnterSearch);
            
            
            $('.datepicker_eve').datepicker({ 
                    changeMonth: true,
                    changeYear: true, 
                    dateFormat: 'yy-mm-dd',
                    minDate:new Date(),
                    onSelect: function() {
                         $('#datepicker_eve').val($(this).datepicker({
                              dateFormat: 'yy-mm-dd'
                            }).val());
                       }
                    });
                    $('.datepairExample .time').timepicker({
                                    'showDuration': true,
                                    'timeFormat': 'h:i A'
                                });
                    $('.datepairExample').datepair();
           
        });
        function ChangePurposeCall(purpose_id)
        {
            if(purpose_id == 6)
            {
                $("#PatientDiv").hide();
                $(".requirementListing").hide();
                $(".EnquiryNoteListing").hide();
                $("#generalinfoDiv").show();
            }
            else if(purpose_id == 2)
            {
                $("#PatientDiv").show();
                $(".requirementListing").hide();
                $(".EnquiryNoteListing").show();
                $("#generalinfoDiv").hide();
            }
            else if(purpose_id == 4 || purpose_id == 5)
            {
                $("#PatientDiv").show();
                $(".requirementListing").hide();
                $(".EnquiryNoteListing").hide();
                $("#generalinfoDiv").hide();
            }
            else
            {
                $("#PatientDiv").show();
                $(".requirementListing").show();
                $(".EnquiryNoteListing").hide();
                $("#generalinfoDiv").hide();
            }
        }
        function checkForEnterSearch (event) 
        {
            if (event.keyCode == 13)
            {
                searchRecords();
            }
        }
        function searchRecords()
        {
            changePagination('eventLogListing','include_event_log.php','','','','');
        }
        
        $( ".callerPhone" ).keyup(function()
        {
            var phno = $("#phone_no").val();
                var data1="phone_no="+phno+"&action=CheckCallerExist";
                $.ajax({
                    url: "event_ajax_process.php", type: "post", data: data1, cache: false,
                    success: function (html)
                    {
                        var result=html.trim();
                        $("#name").val(result);
                    }
                });
        });
        
        function Change_Subservice(value)
        {
            
            var checkAllservices = 'no';
            var i;
            for(i=4;i<10;i++)
            {
               // alert('hi');            
                var checkedService = $('#services_'+i).is(':checked'); 
                //alert(checkedService);
                if(checkedService == true)
                    checkAllservices = 'yes';    
            }
            if(checkAllservices == 'yes')
            {
                $("#invisibleDispatch").show();
                $("#dispatch").hide();
            }
            else
            {
                $("#invisibleDispatch").hide();
                $("#dispatch").show();
            }
            
            var checkdata = $('#services_'+value).is(':checked'); 
            var data1="service_ids="+value+"&action=ChangeSubServices";
            $.ajax({
                url: "ajax_public_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                   //alert(checkdata);
                    if(checkdata == true)
                        $("#newData").append(html);
                    else
                        $("#ServiceDiv_"+value).remove();    
                    
                }
            });
        }
        function SubmitCaller()
        {
            //alert('hi');
            if($("#purpose_id").val() && $("#phone_no").val() && $("#name").val())
                Display_Load();
            $("#CallerForm").ajaxForm({
               success: function (html)
               {
                    var result=html.trim();
                    $("#temp_event_id").val(result);
                    $("#event_id_temp").val(result);
                    $("#callerEvent_id").val(result);
                    changePagination('eventLogListing','include_event_log.php','','','','');
                    Hide_Load();
                   //alert(result);
               }
           }).submit();
        }
        function generate_hhc_no()
        {
            var selected_purpose = $("#purpose_id").val();
            $("#prv_purpose_id").val(selected_purpose);
            var existEvent = $("#temp_event_id").val();
            var existpurpose = $("#prv_purpose_id").val();
            //alert(existpurpose);
            if(existpurpose == '')
            {
                alert('Please select purpose of call');
                return false;
            }
            else if(existEvent == '')
            {
                alert('Please submit caller details form');
                return false;
            }
            else if($("#patient_name").val() == '' || $("#patient_location").val() =='' ||  $("#patient_mobile_no").val() =='' ||  $("#patient_email_id").val()=='')
            {
                alert('Please enter all patient details');
                return false;
            }
            else
            {
                Display_Load();
                $("#NewPatientForm").ajaxForm({
                   success: function (html)
                   {
                        var result=html.trim();  
                        //alert(result);       
                        $("#patient_id_temp").val(result);
                        changePagination('eventLogListing','include_event_log.php','','','','');
                        Hide_Load();
                       //alert(result);
                   }
               }).submit();
            }
        }
        function SelctedDoctors(value,type)
        {
            Display_Load();
            var data1="doctor_consId="+value+"&type="+type+"&action=DoctorsConsultantChange";
            $.ajax({
                url: "event_ajax_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                   //alert(html);
                    if(type == '2')
                        $("#consultantDetails").html(html);
                    else
                        $("#doctorsDetails").html(html);
                    Hide_Load();
                }
            });
        }
        function ChangeLocation(value,type)
        {
            Display_Load();
            var data1="type_id="+value+"&type="+type+"&action=LocationSelect";
            $.ajax({
                url: "event_ajax_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                   //alert(html);
                    if(type == 'location')
                        $("#PINCODEList").html(html);
                    else
                        $("#LOCATIONList").html(html);
                    Hide_Load();
                }
            });
        }
        function dispatchRequirement(value)
        {
            var selected_purpose = $("#purpose_id").val();
            $("#purpose_id_temp").val(selected_purpose);
            var existEvent = $("#event_id_temp").val();
            var existPatient = $("#patient_id_temp").val();
            var existpurpose = $("#purpose_id_temp").val();
            //alert(existpurpose);
           var checkdatas = $('.ServiceClass').is(':checked'); 
           //alert(checkdatas);
            if(existpurpose == '')
            {
                alert('Please select purpose of call');
                return false;
            }
            else if(existEvent == '')
            {
                alert('Please submit caller details form');
                return false;
            }
            else if(existPatient == '')
            {
                alert('Please enter patient details');
                return false;
            }
            else if(checkdatas == false )
            {
                alert('Please select service');
                return false;
            }
            else if($("#sub_service_id").val() == '' )
            {
                alert('Please select service');
                return false;
            }
            else
            {               
                $("#RequirementForm").ajaxForm({
                   success: function (html)
                   {
                        var result=html.trim();
                        //alert(result);
                        if(value=='2')  /* Value 2 means share with hcm  and Value 1 means dispatch */
                        {
                           Hide_Load();
                           ShareWithHCM(result);
                        }
                        else 
                        {
                            Hide_Load();
                            planOfCare(result); 
                        }  
                   }
               }).submit();
            }
        }
        function planOfCare(event_id)
        {
            Display_Load();
            var data1="event_id="+event_id;
            $.ajax({
                url: "include_plan_of_care.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                   //alert(html);
                    $("#RightSideDiv").html(html);
                   
                    datepickerVal();                    
                    Hide_Load();
                }
            });
        }
        function datepickerVal()
        {
            $('.datepicker_eve').datepicker({ 
                    changeMonth: true,
                    changeYear: true, 
                    dateFormat: 'yy-mm-dd',
                    minDate:new Date(),
                    onSelect: function() {
                         $('#datepicker_eve').val($(this).datepicker({
                              dateFormat: 'yy-mm-dd'
                            }).val());
                       }
                    });
                    $('.datepairExample .time').timepicker({
                                    'showDuration': true,
                                    'timeFormat': 'h:i A'
                                });
                    $('.datepairExample').datepair();
        }
        function SearchPatients()
        {
            var existCaller = $("#callerEvent_id").val();
            if(existCaller == '')
            {
                alert('Please submit caller details form');
                //return false;
            }
            if($("#existing_hhc_code").val() == '' && $("#existing_patient_name").val() == '' && $("#existing_mobile_no").val() == '' && $("#ex_landline_no").val() == '' )
            {
                alert('Please enter any search field.');
                return false;
            }
            else
            {
                Display_Load();
                $("#ExistingPatientForm").ajaxForm({
                    success: function (html)
                    {
                         var result=html.trim();  
                         //alert(result);                     
                         $("#RightSideDiv").hide();
                         $("#SearchRightSideDiv").show();
                         changePagination('searchPatientListing','search_existing_patient.php','','','','');
                         //$("#SearchRightSideDiv").html(result);
                         Hide_Load();
                    }
                }).submit();
            }
        }
        function SeclectPatient(patient_id)
        {   
            $("#SearchRightSideDiv").hide();
            $("#RightSideDiv").show();
            $("#selected_patient_exist").val(patient_id);
            
            changePatientTab('New');
            
            searchRecords();
            changePagination('newPatientListing','include_new_patient.php','','','','');
            
        }
        function confirmEstimatedCost(value,serviceArray)
        {
            //alert(value);
            if(value == '2')
            {
                window.location.href = '<?php echo $siteURL;?>event-log.php';
            }
            else
            {
                
                var temp = new Array();
                temp = serviceArray.split(',');
                //alert(temp.length);
                for(i=0;i<temp.length;i++)
                {
                    //alert(temp[i]); 
                    if($("#eve_date_"+temp[i]).val() == '')
                    {
                        alert('Please enter event date');
                        return false;
                    }
                }
                Display_Load();
                $("#PlanofCareForm").ajaxForm({
                    success: function (html)
                    {
                         var result=html.trim();  
                         alert(result);                     
                         
                         Hide_Load();
                    }
                }).submit();
            }
        }
        function checkAddress()
        {
            var checkedAdd = $('#sameaddress').is(':checked'); 
            //alert(checkedAdd);
            var resAdd = $("#residential_address").val();
            if(checkedAdd == true)
            {                
                $("#permanant_address").val(resAdd);
            }
            else
                $("#permanant_address").val('');
        }
        function changePatientTab(value)
        {
            if(value == 'New')
            {
                $("#new").addClass('active');
                $("#existing").removeClass('active');
                $("#ExTab").removeClass('active');
                $("#NewTab").addClass('active');
                $("#NewPatienttab").html('UPDATE');
                
            }
        }
        function changeRightTab()
        {
            $("#PlanOfCareDiv").show();
            $("#RightSideDiv").hide();
            $("#SearchRightSideDiv").hide();
            
        }
        function ShareWithHCM(event_id)
        {
           // Display_Load();
            var data1="event_id="+event_id+"&action=vw_share_with_hcm";
            $.ajax({
                url: "event_ajax_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                    var result=html.trim();
                    //alert(html);
                    $('#vw_professional').modal({backdrop: 'static',keyboard: false}); 
                    $("#AllAjaxData").html(result);
                   // Hide_Load();
                }
            }); 
        }
        function SearchRecord()
        {
          // Getting Values
          var eventId=$('#event_id').val();
          var SearchKeyword="";
          if($('#employee_id').val())
          {
             SearchKeyword="employee_id="+$('#employee_id').val(); 
          }
          if($('#location_id').val())
          {
             SearchKeyword +="&location_id="+$('#location_id').val();  
          }
          if($('#specialty').val())
          {
            SearchKeyword +="&specialty="+$('#specialty').val();  
          }
          
          if(SearchKeyword)
          {
             // Display_Load();
              var data1=SearchKeyword+"&event_id="+eventId+"&action=load_share_event_table_content";
              //alert(data1);
              $.ajax({
                url: "event_ajax_process.php", type: "post", data: data1, cache: false,
                success: function (html)
                {
                    var result=html.trim();
                    //alert(html);
                    $(".share_table_content").html(result);
                  //  Hide_Load();
                }
            }); 
          }
          else
          {
              ShareWithHCM(eventId);
          }
       }
        function ShareEventToHCM(employee_id,event_id)
        {
          if(employee_id && event_id) 
          {
              //  Display_Load();
                var data1="login_user_id=<?php echo $_SESSION['employee_id']; ?>&employee_id="+employee_id+"&event_id="+event_id+"&action=share_event_to_hcm";
                $.ajax({
                    url: "event_ajax_process.php", type: "post", data: data1, cache: false,
                    success: function (html)
                    {
                        var result=html.trim();
                        //alert(html);
                        if(result=='success')
                        {
                            alert("Event shared successfully.");
                            window.location="event-log.php";
                        }
                        else 
                        {
                            alert("Error in shared event.");
                        }
                    }
                });  
          }
       } 
</script>
<?php
if($EID)
{
  if($EditedResponseArr['patient_id'])
  {
?>
<script type="text/javascript">
    changePatientTab('New');    
</script>
<?php
  }
 $selectExistPlanofcare = "select event_requirement_id from sp_event_requirements where event_id = '".$EID."' and status = '1' ";
if(mysql_num_rows($db->query($selectExistPlanofcare)))
{
    ?>
<script type="text/javascript">
        changeRightTab('New');    
    </script>
<?php
}
}?>
</body>
</html>
