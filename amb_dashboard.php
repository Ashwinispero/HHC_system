<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();

if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
?>
<style type="text/css" media="all">
.input_box
{
  margin-left:-10%
}
.input_box_first
{
  margin-left:-3%
}
</style>

<div class="container-fluid">
<div class="row">
<div class="col-lg-12">
<h2 class="page-title">Ambulance Dashboard</h2>
<label for="inputPassword3" class="col-lg-1 control-label">Location : <span style="color:red;">*</span></label>
<div class="col-lg-3">
        <input maxlength="100" id="google_location" name="google_location" type="text" value="<?php if($recListResponse['locationNm']) echo $recListResponse['locationNm']; else echo $_POST['locationNm']; ?>" class="validate[required] form-control"  />   
        <input id="selcGog_Location" name="selcGog_Location" type="hidden" value="" /> 
</div>
<a href="javascript:void(0);" title="Dispatch_form" onclick="Dispatch_form()"; data-toggle="tooltip" data-placement="top" title="View Log">
<span aria-hidden="true">Ambulance Dispatch Form</span></a>     
<div>
</div>
</div>
<?php
            echo '<table id="logTable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Date Time</th>
                <th>Incident ID</th>
                <th>Ambulance No</th>
                <th>Address</th>
            <th>Action</th>
              </tr>
            </thead>
            <tbody>';
} ?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&libraries=places"></script>
<?php include "include/scripts.php"; ?>
<?php include "include/eventLogscripts.php"; ?>
<script>
      $(document).ready(function () 
      {
        $location_input = $("#google_location");
        alert($location_input);
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

  <!-- Modal Popup code start ---> 
  <div class="modal fade" id="vw_dispatch_form"> 
        <div class="modal-dialog" style="width:1200px !important;">
          <div class="modal-content" id="AllAjaxData">
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>