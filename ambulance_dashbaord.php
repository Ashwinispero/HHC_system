<?php   require_once 'inc_classes.php';
        require_once "emp_authentication.php";
        require_once "classes/thumbnail_images.class.php";
        require_once "classes/SimpleImage.php";
        require_once 'classes/eventClass.php';
        require_once 'classes/config.php';
        $eventClass=new eventClass();
        require_once 'classes/commonClass.php';
        $commonClass=new commonClass();
        require_once 'classes/employeesClass.php';
        $employeesClass=new employeesClass();
        require_once 'classes/professionalsClass.php';
        $professionalsClass=new professionalsClass();  
?>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/pinterest-style.css" />
<link rel="stylesheet" href="dropdown/docsupport/prism.css">
<link rel="stylesheet" href="dropdown/chosen.css">  
<link rel="stylesheet" href="js/jRange-master/jquery.range.css">
<style type="text/css" media="all">
    /* fix rtl for demo */
    .chosen-rtl .chosen-drop { left: -9000px; }
    #calendar { max-width: 900px; margin: 0 auto; }

  
.notification {
  background-color: #555;
  color: white;
  text-decoration: none;
  padding: 15px 26px;
  position: relative;
  display: inline-block;
  border-radius: 2px;
}


.notification .badge {
  position: absolute;
  
  right: -10px;
  padding: 5px 10px;
  border-radius: 50%;
  color: white;
  background-color: red;
}
.input_box
{
  margin-left:-10%
}
.input_box_first
{
  margin-left:-2%
}
</style>  
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Welcome to SPERO</title>
</head>
<body>
<?php include "include/amb_header.php"; ?>
<section>
<div id="DispatchdDiv" style="background-color:white;">
<div class="container-fluid" style="margin-left:2%;margin-right:2%;border: 2px solid #E8E8E8;border-radius: 8px;">
<div class="row">
<div class="col-lg-12" style="margin-top:2%;">
<!--<label for="inputPassword3" class="col-lg-1 control-label">Location : <span style="color:red;">*</span></label>
<div class="col-lg-3">
        <input maxlength="100" id="google_location" name="google_location" type="text" value="<?php if($recListResponse['locationNm']) echo $recListResponse['locationNm']; else echo $_POST['locationNm']; ?>" class="validate[required] form-control"  />   
        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
</div>-->
<h2 class="page-title">Ambulance Dispatch Form</h2>
<div class="modal-body">
<div class="mCustomScrollbar">

<div id="Block1">
<h1 class="div_header">Caller Details</h1>
<form class="row" style="padding-left:5px;">
<label class="col-sm-1 label_style">Contact :</label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-2 label_style">Last Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerNameText" value="<?php if($EditedResponseArr['caller_last_name']) echo $EditedResponseArr['caller_last_name']; else echo $_POST['caller_last_name'];  ?>" id="name" name="name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-2 label_style">First Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerFNameText" value="<?php if($EditedResponseArr['caller_first_name']) echo $EditedResponseArr['caller_first_name']; else echo $_POST['caller_first_name'];  ?>" id="caller_first_name" name="caller_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" /></div>
<label for="inputPassword3" class="col-lg-1 label_style">Relation :</label>
                      <div class="col-lg-2 input_box_first">
                          <!--<input type="text" class="form-control" id="relation" name="relation" maxlength="30" >-->
                          <!--<label class="select-box-lbl">-->
                              <select class="chosen-select form-control"  name="relation" id="relation" onchange="return changeRelation(this.value);">
                                <option value="">Relation</option>
                                <?php
                                    $selectRecord = "SELECT relation_id,relation FROM sp_caller_relation WHERE status='1' ORDER BY relation ASC";
                                    $AllRrecord = $db->fetch_all_array($selectRecord);
                                    foreach($AllRrecord as $key=>$valRecords)
                                    {
                                        if($EditedResponseArr['relation'] == $valRecords['relation'])
                                            echo '<option value="'.$valRecords['relation'].'" selected="selected" >'.$valRecords['relation'].'</option>';
                                        else
                                            echo '<option value="'.$valRecords['relation'].'">'.$valRecords['relation'].'</option>';
                                    }
                                ?>
                            </select>
                          <!--</label>-->
                      </div>
                      </form>
</div>
<div class="line-seprator"></div>
<div id="Block4">
<h5  class="div_header" >Incident Details</h5>
<form class="row" style="padding-left:5px;">
<label class="col-sm-2 label_style">No Of Patient :</label>
<div class="col-lg-3  ">
<input type="text" class="validate[required,custom[phone],minSize[1],maxSize[2]] form-control" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="2" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-2 label_style">Chief Complaint :</label>
                      <div class="col-lg-3">
                          <!--<input type="text" class="form-control" id="relation" name="relation" maxlength="30" >-->
                          <!--<label class="select-box-lbl">-->
                              <select class="chosen-select form-control"  name="relation" id="relation" onchange="return changeRelation(this.value);">
                                <option value="">Chief Complaint</option>
                                <?php
                                    $selectRecord = "SELECT * FROM sp_ems_complaint_types WHERE ct_status='1' ORDER BY ct_type ASC";
                                    $AllRrecord = $db->fetch_all_array($selectRecord);
                                    foreach($AllRrecord as $key=>$valRecords)
                                    {
                                        echo '<option value="'.$valRecords['ct_id'].'">'.$valRecords['ct_type'].'</option>';
                                    }
                                ?>
                            </select>
                          <!--</label>-->
                      </div>
                      </form>
</div>
<div class="line-seprator"></div>
<div id="Block2">
<h5 class="div_header">Patient Details</h5>
<form style="padding-left:5px;">
<div class="row">
<label for="inputPassword3" class="col-lg-2 label_style">First Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerFNameText" value="<?php if($EditedResponseArr['caller_first_name']) echo $EditedResponseArr['caller_first_name']; else echo $_POST['caller_first_name'];  ?>" id="caller_first_name" name="caller_first_name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" /></div>
<label for="inputPassword3" class="col-lg-2 label_style">Last Name : <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<input type="text" style="text-transform: capitalize;" class="validate[required] form-control callerNameText" value="<?php if($EditedResponseArr['caller_last_name']) echo $EditedResponseArr['caller_last_name']; else echo $_POST['caller_last_name'];  ?>" id="name" name="name" maxlength="50" onkeyup="if (/[^A-Za-z ]/g.test(this.value)) this.value = this.value.replace(/[^A-Za-z ]/g,'')" />
</div>
<label class="col-sm-1">Contact :</label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[6],maxSize[15]] form-control callerPhone" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="15" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label for="inputPassword3" class="col-lg-1 label_style">Age:</label>
<div class="col-lg-2 input_box_first">
<input type="text" maxlength="30" style="text-transform: capitalize;" class="form-control" id="Age" name="Age" value="<?php if($recListResponse['Age']) echo $recListResponse['Age']; else echo $_POST['Age']; ?>" />
</div>
</div><br>
<div class="row">
<label for="inputPassword3" class="col-lg-2 label_style">Address : <span style="color:red;">*</span></label>
<div class="col-lg-4 input_box">
        <input maxlength="100" id="google_location_new" name="google_location_new" type="text" value="<?php if($recListResponse['locationNm']) echo $recListResponse['locationNm']; else echo $_POST['locationNm']; ?>" class="validate[required] form-control"  />   
        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
</div>

</div>
</form>
</div>
<div class="line-seprator"></div>

<div id="Block3">
<h5 class="div_header">Ambulance Details</h5>
<form style="padding-left:5px;">
<div class="col-lg-12">
<div role="tabpanel" id="Patienttabs"> 
                    <!-- Nav tabs -->
                    <ul id="MainTabs" class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active" id="google_search"><a href="#google" aria-controls="home" role="tab" data-toggle="tab" id="google_search">Google Search</a></li>
                      <li role="presentation" id="manual_search"><a href="#manual" aria-controls="profile" role="tab" data-toggle="tab" id="manual_serach">Manual Search</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="google">
                            <div class="exPatientListing">
                            <label for="inputPassword3" class="col-lg-2 label_style">Pickup Location : <span style="color:red;">*</span></label>
                          <div class="col-lg-4">
                                  <input maxlength="100" id="google_location_new" name="google_location_new" type="text" value="<?php if($recListResponse['locationNm']) echo $recListResponse['locationNm']; else echo $_POST['locationNm']; ?>" class="validate[required] form-control"  />   
                                  <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
                          </div>    
                            </div>
                            <br><br>
                            <table id="logTable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Ambulance No</th>
                <th>base Location</th>
                <th>Mobile No</th>
                <th>Ambulance Type</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php 
             $selectRecord = "SELECT amb.*,base_loc.id,base_loc.base_name FROM sp_ems_ambulance as amb
                              LEFT JOIN sp_ems_base_location as base_loc ON amb.base_loc = base_loc.id
                              WHERE amb.status='1'  ORDER BY amb.id ASC";
             $AllRrecord = $db->fetch_all_array($selectRecord);
             foreach($AllRrecord as $key=>$valRecords)
             {
              echo '<tr style = "' . $complimentaryVisitStyle .'">
                <td>'.$valRecords['amb_no'].'</td>
                <td>'.$valRecords['base_name'].'</td>
                <td>'.$valRecords['mob_no'].'</td>
                <td>'.$valRecords['amb_type'].'</td>
                <td>'.$valRecords['amb_status'].'</td>
                <td>'; 
                ?> 
                <input type="checkbox" name="sameaddress" id="sameaddress" value="1" onclick="return checkAddress();" >
                <?php 
                echo '</td>
                </tr>';
                } 
            ?>
            </tbody>
            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="manual" style="hight:50%;">
                            <div class="newPatientListing" >
                            <div class="row">
                                  <label for="inputPassword3" class="col-lg-3 label_style">Select Ambulance :</label>
                                  <div class="col-lg-3 input_box">
                                      <select class="chosen-select form-control"  name="amb_no" id="amb_no" onchange="return changeambulance(this.value);">
                                      <option value="">Ambulance</option>
                                      <?php
                                          $selectRecord = "SELECT id,amb_no FROM sp_ems_ambulance WHERE status='1' ORDER BY id ASC";
                                          $AllRrecord = $db->fetch_all_array($selectRecord);
                                          foreach($AllRrecord as $key=>$valRecords)
                                          {
                                            echo '<option value="'.$valRecords['amb_no'].'">'.$valRecords['amb_no'].'</option>';
                                          }
                                      ?>
                                      </select>
                                  </div><br><br>
                                  <br><br><br><br>
                                  </div>
                                  <div class="row" id="ambulance_list"></div>
                                 
                            </div>
                        </div>
                       
                    </div>
                </div></div>

</form>
</div>
<div class="line-seprator"></div>
<div id="Block5">
<h5 class="div_header">Ambulance Schedule Details</h5>
<form class="row" style="padding-left:5px;">

<label for="inputPassword3" class="col-lg-2 label_style">Purpose Of Call :</label>
                      <div class="col-lg-2 input_box_first">
                          <!--<input type="text" class="form-control" id="relation" name="relation" maxlength="30" >-->
                          <!--<label class="select-box-lbl">-->
                              <select class="chosen-select form-control"  name="relation" id="relation" onchange="return changeRelation(this.value);">
                                <option value="">Purpose Of Call</option>
                                <option>Drop Call</option>
                                <option>Hospital Transfer Call</option>
                            </select>
                          <!--</label>-->
                      </div>
                      <label class="col-sm-1 label_style">Date :</label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[1],maxSize[2]] form-control" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="2" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
<label class="col-sm-1 label_style">Time :</label>
<div class="col-lg-2 input_box_first">
<input type="text" class="validate[required,custom[phone],minSize[1],maxSize[2]] form-control" value="<?php if($EditedResponseArr['phone_no']) echo $EditedResponseArr['phone_no']; else echo $_POST['phone_no'];  ?>" id="phone_no" name="phone_no" maxlength="2" onkeyup="if (/[^0-9-()-+.]/g.test(this.value)) this.value = this.value.replace(/[^0-9-()-+.]/g,'')" />
</div>
                      </form>
</div>
<div class="line-seprator"></div>
<div id="Block6">
<h1 class="div_header">Other Details</h1>
<form class="row" style="padding-left:5px;">

<label for="inputPassword3" class="col-lg-2 label_style">Notes: <span style="color:red;">*</span></label>
<div class="col-lg-2 input_box">
<textarea id="w3review" name="w3review" rows="4" cols="80"></textarea>

</div>


                      </form>
</div>
</div>
<!--<a href="javascript:void(0);" title="Dispatch_form" onclick="Dispatch_form()"; data-toggle="tooltip" data-placement="top" title="View Log">
<span aria-hidden="true">Ambulance Dispatch Form</span></a>  -->   
<div>
</div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&libraries=places"></script>
<?php include "include/scripts.php"; ?>
<?php include "include/eventLogscripts.php"; ?>
<script>
      $(document).ready(function () 
      {
        $location_input = $("#google_location");
        
        var options = {
            //types: ['(postal_town)'],
            componentRestrictions: {country: 'in'}
        };
        autocomplete = new google.maps.places.Autocomplete($location_input.get(0), options);    
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var data = $("#google_location").val();
            //console.log('blah')
            show_submit_data(data);
            return false;
        });
        
         $(".number").keydown(function (e) 
         {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                 // Allow: Ctrl+C
                (e.keyCode == 67 && e.ctrlKey === true) ||
                 // Allow: Ctrl+X
                (e.keyCode == 88 && e.ctrlKey === true) ||
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) 
            {
                e.preventDefault();
            }
        });
       
        /*
        
        $('.callerPhone').bind('cut copy paste', function (e) 
        {
            e.preventDefault(); //disable cut,copy,paste
        });
        
        */
    });

    function show_submit_data(data) {
        $("#selcGog_Location").val(data);
    }
</script>
<script type="text/javascript"> 
function changeambulance(amb_no){
  var data1="amb_no="+amb_no+"&action=vw_ambulance_list";
            $.ajax({
                    url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                      $("#ambulance_list").html(html);
                       
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
}
function Dispatch_form()
{
        var status='1';
            var data1="event_id="+status+"&action=vw_dispatch_form";
            $.ajax({
                    url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
                    beforeSend: function() 
                    {
                        Display_Load();
                    },
                    success: function (html)
                    {
                        alert(html);
                        $('#vw_dispatch_form').modal({backdrop: 'static',keyboard: false}); 
                        $("#AllAjaxData").html(html);
                        // start work on google location on modal - 
                        $location_input_home = $("#google_home_location");
                       
                    var options = {
                        //types: ['(postal_town)'],
                        componentRestrictions: {country: 'in'}
                    };
                    autocomplete_home = new google.maps.places.Autocomplete($location_input_home.get(0), options);    
                    google.maps.event.addListener(autocomplete_home, 'place_changed', function() {
                        var datas = $("#google_home_location").val();
                        console.log('blah');
                      //  show_submit_data(data);
                        return false;
                    });
                    
                    // complete google location
                        $("#viewEventDetails .modal-body").mCustomScrollbar({
                                        setHeight:500,
                                        //theme:"minimal-dark"
                                });
                    },
                    complete : function()
                    {
                       Hide_Load();
                    }
             }); 
}  
</script>
<script src="dropdown/chosen.jquery.js" type="text/javascript"></script>
    <script src="dropdown/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
    var config = {
      '.chosen-select'           : {width:"99%"},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
    </script>
  <!-- Modal Popup code start ---> 
  <div class="modal fade" id="vw_dispatch_form"> 
        <div class="modal-dialog" style="width:1200px !important;">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
<?php // include "amb_dashboard.php"; ?>

</div>
</div>
</div>
</section>
</body>
</html>