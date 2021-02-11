/*---------- MI13 ------------*/

var $placeSearch, $autocomplete, $callIncidentMap,$callIncidentMapUI,$H_Platform;
var $callIncidentBehavior;
var $incGeoFence = null;
var $ambMapMarkers = null;
var $DirectionLine = null;
var $DirectionLineGroup = null;

var $infoWindows = [];
var $incGeocoder;
var $incMapMarker,$incMarkerGroup = null;

var $isExtendedMapOpened = 0;
var $ExtendMapWindow; //popup window

//var map_pin_image_hover = base_url+'themes/backend/images/select_bike_icon.png';
//var stand_map_pin_image_hover = base_url+'themes/backend/images/select_bike_icon.png';
// var map_pin_image = base_url+'themes/backend/images/select_icon_pin.png';

var map_pin_image_hover = base_url+'themes/backend/images/select_bike_icon.png';
var stand_map_pin_image_hover = base_url+'themes/backend/images/select_bike_icon.png';
var map_pin_image = base_url+'themes/backend/images/select_icon_pin.png';
var map_102_pin_image = base_url+'themes/backend/images/amb-green_102.png';
var stand_map_pin_image = base_url+'themes/backend/images/select_icon_pin.png';

var $directionsService;
var $directionsDisplay;

//var $MapPins = {
//    1:base_url+'themes/backend/images/map_pin.png',
//    2: base_url+'themes/backend/images/yellow_amb_pin.png',
// //   3: base_url+'themes/backend/images/yellow_amb_pin.png',
//    6:base_url+'themes/backend/images/orange_map_pin.png'
//};
//var $MapPins = {
//    1:base_url+'themes/backend/images/select_bike_icon.png',
//    2: base_url+'themes/backend/images/red_bike_icon.png',
// //   3: base_url+'themes/backend/images/yellow_amb_pin.png',
//    6:base_url+'themes/backend/images/yellow_bike_icon.png',
//    7:base_url+'themes/backend/images/blue_bike_icon.png'
//};
var $MapPins = {
    
    1:'http://localhost/HHC_system//images/amb_green.png',
    2:'http://localhost/HHC_system//images/amb_red.png'
    
};
var $MapPins = {
    1:'http://localhost/HHC_system//images/amb_green.png',
    2:'http://localhost/HHC_system//images/amb_red.png'
};

function initIncidentMap() {

     var myLatLng = {lat: 18.532293, lng: 73.847192};

    // Create a map object and specify the DOM element for display.
//    $callIncidentMap = new google.maps.Map(document.getElementById('INCIDENT_MAP'), {
//      center: myLatLng,
//      zoom: 7
//    });
    $H_Platform = new H.service.Platform({
        apikey: 'yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ',
    });
    var defaultLayers = $H_Platform.createDefaultLayers();
    
    $callIncidentMap = new H.Map( document.getElementById('INCIDENT_MAP'),
        defaultLayers.vector.normal.map, {
        center: myLatLng,
        zoom: 10,
        //pixelRatio: window.devicePixelRatio || 1
    });
    
    $callIncidentBehavior = new H.mapevents.Behavior(new H.mapevents.MapEvents($callIncidentMap));

    $callIncidentMap.tagId = 'INCIDENT_MAP';
    
    window.addEventListener('resize', () => $callIncidentMap.getViewPort().resize());
    $callIncidentMapUI = H.ui.UI.createDefault($callIncidentMap, defaultLayers);

    map_autocomplete();
    
//    $directionsService = new google.maps.DirectionsService();
//    $directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true});
//    
//
//    var input = document.getElementById('google_pickup_location');
//    var options = {
//     types: ['geocode'],
////      componentRestrictions: {country: 'in'}
//    };
//   $url =  "https://geocoder.ls.hereapi.com/6.2/geocode.json?apiKey=yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ&searchtext=425+W+Randolph+Chicago";
//    
//    $callIncidentMap.data.loadGeoJson(base_url+'/google.json?t=14');
//    
//    $callIncidentMap.data.setStyle(function(feature) {
//            
//          var color = feature.getProperty('color');
//          
//          return /** @type {!google.maps.Data.StyleOptions} */({
//            fillColor: color,
//            strokeColor: color,
//            strokeWeight: 1
//          });
//    });

//
//    $autocomplete  = new google.maps.places.SearchBox(input, options);
//    $autocomplete.addListener('places_changed', auto_complete_place_changed);
//    $incGeoFence = new google.maps.Circle({});
//    
//    
//    $callIncidentMap.data.addListener('click', function(event) {
//            
//          //event.feature.setProperty('setaddress', true);
//          district = event.feature.getProperty('district');
//          state = event.feature.getProperty('state');
//          var $place_details = {'state':state,'district':district,'latlng':event.latLng}
//           set_inc_main_pin($place_details);
//           set_inc_add_details($place_details);
//   });
        
    
//     var trafficLayer = new google.maps.TrafficLayer();
//     trafficLayer.setMap($callIncidentMap);
    
}
function map_autocomplete_patient(){
    $("#google_location").autocomplete({
        select: function( event, ui ) {
          
            $.get(ui.item.href,function($data){
                
                var places = $data.location;
              // alert(places.position[0]);
               $("#Lat_pat").val(places.position[0]);
               $("#lng_pat").val(places.position[1]);
               
            });

       // console.log(  ui.item );
        },
         source: function (request, response) {
             //console.log(response);
             $.ajax({
               // url: "https://places.ls.hereapi.com/places/v1/autosuggest?in=34.083813%2C74.809463%3Br%3D5000000&size=1000&tf=plain&addressFilter=stateCode%3DMH&X-Mobility-Mode=drive&Accept-Language=en&app_id=hSm0zJfek39BQxeXGRYZ&app_code=46aPkrbrHTb7tEdxSzLMiA&apiKey=yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ",
                
                 url: "https://places.ls.hereapi.com/places/v1/discover/search?in=19.0760%2C72.8777%3Br%3D4717093&recd=false&size=100&tf=html&X-Mobility-Mode=drive&Accept-Language=en&app_id=hSm0zJfek39BQxeXGRYZ&app_code=46aPkrbrHTb7tEdxSzLMiA",
                 data: { q: request.term },
                 dataType: "json",
                 success: function ($data) {

                    var $items = $data.results.items;
                    $.each($items,function(){
                        
                        this.title = this.title.replace('<br/>',' ');
                        this.vicinity = this.vicinity.replace('<br/>',' ');
                        this.label = this.title+' '+this.vicinity;
                    });
                   // console.log($data);
                    response($items);
                    //return $items;
                    
                 },
                 error: function () {
                     response([]);
                 }
             });
         }
    }); 
}
function map_autocomplete_drop(){
    
    $("#google_drop_location").autocomplete({
        select: function( event, ui ) {
          
            $.get(ui.item.href,function($data){
                
                var place_details = $data.location;
                $("#lat_drp").val(place_details.position[0]);
               $("#lng_drp").val(place_details.position[1]);
                //auto_complete_place_changed($place_details);
                
                //set_inc_main_pin($place_details);
                //set_inc_add_details($place_details);
            });

       // console.log(  ui.item );
        },
         source: function (request, response) {
             //console.log(response);
             $.ajax({
               // url: "https://places.ls.hereapi.com/places/v1/autosuggest?in=34.083813%2C74.809463%3Br%3D5000000&size=1000&tf=plain&addressFilter=stateCode%3DMH&X-Mobility-Mode=drive&Accept-Language=en&app_id=hSm0zJfek39BQxeXGRYZ&app_code=46aPkrbrHTb7tEdxSzLMiA&apiKey=yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ",
                
                 url: "https://places.ls.hereapi.com/places/v1/discover/search?in=19.0760%2C72.8777%3Br%3D4717093&recd=false&size=100&tf=html&X-Mobility-Mode=drive&Accept-Language=en&app_id=hSm0zJfek39BQxeXGRYZ&app_code=46aPkrbrHTb7tEdxSzLMiA",
                 data: { q: request.term },
                 dataType: "json",
                 success: function ($data) {

                    var $items = $data.results.items;
                    $.each($items,function(){
                        
                        this.title = this.title.replace('<br/>',' ');
                        this.vicinity = this.vicinity.replace('<br/>',' ');
                        this.label = this.title+' '+this.vicinity;
                    });
                   // console.log($data);
                    response($items);
                    //return $items;
                    
                 },
                 error: function () {
                     response([]);
                 }
             });
         }
    });            
}

function map_autocomplete(){
    
    $("#google_pickup_location").autocomplete({
        select: function( event, ui ) {
          
            $.get(ui.item.href,function($data){
                
                var place_details = $data.location;
                console.log(place_details);
                
                auto_complete_place_changed(place_details);
                $("#lat_pick").val(place_details.position[0]);
                $("#lng_pick").val(place_details.position[1]);
                //set_inc_main_pin($place_details);
                //set_inc_add_details($place_details);
            });

       // console.log(  ui.item );
        },
         source: function (request, response) {
             //console.log(response);
             $.ajax({
               // url: "https://places.ls.hereapi.com/places/v1/autosuggest?in=34.083813%2C74.809463%3Br%3D5000000&size=1000&tf=plain&addressFilter=stateCode%3DMH&X-Mobility-Mode=drive&Accept-Language=en&app_id=hSm0zJfek39BQxeXGRYZ&app_code=46aPkrbrHTb7tEdxSzLMiA&apiKey=yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ",
                
                 url: "https://places.ls.hereapi.com/places/v1/discover/search?in=19.0760%2C72.8777%3Br%3D4717093&recd=false&size=100&tf=html&X-Mobility-Mode=drive&Accept-Language=en&app_id=hSm0zJfek39BQxeXGRYZ&app_code=46aPkrbrHTb7tEdxSzLMiA",
                 data: { q: request.term },
                 dataType: "json",
                 success: function ($data) {

                    var $items = $data.results.items;
                    $.each($items,function(){
                        
                        this.title = this.title.replace('<br/>',' ');
                        this.vicinity = this.vicinity.replace('<br/>',' ');
                        this.label = this.title+' '+this.vicinity;
                    });
                   // console.log($data);
                    response($items);
                    //return $items;
                 },
                 error: function () {
                     response([]);
                 }
             });
         }
    });            
}
function whatthreewordstoaddress(latlng, lt, lg){
        $.ajax({
            url: 'https://reverse.geocoder.ls.hereapi.com/6.2/reversegeocode.json',
            type: 'GET',
            dataType: 'text',
            jsonp: 'jsoncallback',
            data: {
              prox: latlng,
              mode: 'retrieveAddresses',
              maxresults: '1',
              gen: '9',
              apiKey: 'yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ'
            },
            success: function (data) {
            var data = JSON.parse(data);
            
            document.getElementById("google_pickup_location").value = data.Response.View[0].Result[0].Location.Address.Label;
            document.getElementById("google_pickup_location").focus();
            $place_details = data.Response.View[0].Result[0].Location;
            $place_details.position = [];
            $place_details.position[0]= data.Response.View[0].Result[0].Location.DisplayPosition.Latitude;
            $place_details.position[1]= data.Response.View[0].Result[0].Location.DisplayPosition.Longitude;
            auto_complete_place_changed($place_details)

            }
          });
    }
function ext_map_autocomplete(){
    
    $("#inc_ext_map_address").autocomplete({
        select: function( event, ui ) {
          
            $.get(ui.item.href,function($data){
                //console.log($data);
                var $place_details = $data.location;
                window.opener.ext_auto_complete_place_changed($place_details);
                
                //set_inc_main_pin($place_details);
                //set_inc_add_details($place_details);
            });

       // console.log(  ui.item );
        },
         source: function (request, response) {
             //console.log(response);
             $.ajax({
               // url: "https://places.ls.hereapi.com/places/v1/autosuggest?in=34.083813%2C74.809463%3Br%3D5000000&size=1000&tf=plain&addressFilter=stateCode%3DMH&X-Mobility-Mode=drive&Accept-Language=en&app_id=hSm0zJfek39BQxeXGRYZ&app_code=46aPkrbrHTb7tEdxSzLMiA&apiKey=yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ",
               url: "https://places.ls.hereapi.com/places/v1/discover/search?in=19.0760%2C72.8777%3Br%3D4717093&addressFilter=stateCode%3DMH&recd=false&size=100&tf=html&X-Mobility-Mode=drive&Accept-Language=en&app_id=hSm0zJfek39BQxeXGRYZ&app_code=46aPkrbrHTb7tEdxSzLMiA",
                 data: { q: request.term },
                 dataType: "json",
                 success: function ($data) {

                     var $items = $data.results.items;
                    $.each($items,function(){
                        this.label = this.title+' '+this.vicinity;
                    });
                    console.log($data);
                   // console.log($data);
                    response($items);
                    //return $items;
                 },
                 error: function () {
                     response([]);
                 }
             });
         }
    });            
}

function auto_complete_place_changed(places){
   //var places = $autocomplete.getPlaces();
    //if (places.length > 0) {
        set_inc_main_pin(places);
        get_inc_ambu(places);
       // set_inc_add_details(places);
   // }
    
}

function get_inc_ambu(places){
   // alert((places.position[0]));
    var data1="&lat="+places.position[0]+"&long="+places.position[1]+"&action=view_amb_list";
     $.ajax({
        url: "amb_incident_summary_ajax_process.php", type: "post", data: data1, cache: false,async: false,
        beforeSend: function() 
        {
            Display_Load();
        },
        success: function (html)
        {
          //  alert(html);
          $("#logTable").html(html);
          update_ambulance_inc_map();
        },
        complete : function()
        {
           Hide_Load();
        }
 }); 
}

function ext_auto_complete_place_changed(places){
    
    //var places = $ExtendMapWindow.$ext_autocomplete.getPlaces();
//    if (places.length > 0) {
        set_inc_main_pin(places);
        set_inc_add_details(places);
 //   }
    
}


function load_inc_address() {
    $incGeocoder = new google.maps.Geocoder();
    var address = document.getElementById('google_pickup_location').value;
    $incGeocoder.geocode( { 'address': address}, function(results, status) {
        if (status == 'OK') {
            set_inc_main_pin(results[0]);
        }
    });
}
function on_cluster_change_load_map($this){
    $incGeocoder = new google.maps.Geocoder();
    
    var cluster_lat = $($this).find(':selected').attr('data-lat');
    var cluster_lng = $($this).find(':selected').attr('data-lng');
    var address = cluster_lat+','+cluster_lng;
    //console.log(address);
    $incGeocoder.geocode( { 'address': address}, function(results, status) {
        if (status == 'OK') {
   
            set_inc_main_pin(results[0]);
            (results[0]);
        }
    });
}
function set_inc_main_pin(place){
   // alert(JSON.stringify(place));
    if(typeof $incMapMarker != "undefined"){
        //$incMapMarker.setMap(null);
    }

   
//    var p_lat = place.geometry.location.lat();
//    var p_lng = place.geometry.location.lng();
//    if(place.latlng){
        var p_lat = place.position[0];
        var p_lng = place.position[1];
        //alert(p_lat);
        if($incMarkerGroup != null){
            $callIncidentMap.removeObject($incMarkerGroup);
            $incMarkerGroup = null;
        }
         
        $incMapMarker = new H.map.Marker({lat:p_lat, lng:p_lng},{volatility: true});
        $incMapMarker.draggable = true;
        
//        $callIncidentMap.addEventListener('dragstart', function(evt){
//            console.log(evt);
//
//        }, false);
        
         $incMapMarker.addEventListener('dragstart', function(ev) {
            var target = ev.target,
                pointer = ev.currentPointer;
            if (target instanceof H.map.Marker) {
              var targetPosition = $callIncidentMap.geoToScreen(target.getGeometry());
              target['offset'] = new H.math.Point(pointer.viewportX - targetPosition.x, pointer.viewportY - targetPosition.y);
              $callIncidentBehavior.disable();
            }
          }, false);

          $incMapMarker.addEventListener('dragend', function(ev) {
            var target = ev.target;
            if (target instanceof H.map.Marker) {
              $callIncidentBehavior.enable();
               var inc_marker_place = $incMapMarker.getGeometry();
               inc_marker_dragged(inc_marker_place);
            }
          }, false);

           $incMapMarker.addEventListener('drag', function(ev) {
            var target = ev.target,
                pointer = ev.currentPointer;
            if (target instanceof H.map.Marker) {
              target.setGeometry($callIncidentMap.screenToGeo(pointer.viewportX - target['offset'].x, pointer.viewportY - target['offset'].y));
              
            }
          }, false);
        
        $incMarkerGroup = new H.map.Group();
        $incMarkerGroup.addObject($incMapMarker);
        $callIncidentMap.addObject($incMarkerGroup);
        $callIncidentMap.getViewModel().setLookAtData({
            bounds: $incMarkerGroup.getBoundingBox()
        });
           
    if($isExtendedMapOpened){
        
        if($ExtendMapWindow.$incExtMapMarker != null){
            $ExtendMapWindow.$ExtendMap.removeObject($ExtendMapWindow.$incExtMapMarkerGroups);
            $ExtendMapWindow.$incExtMapMarkerGroups = null;
        }
         
        $ExtendMapWindow.$incExtMapMarker = new $ExtendMapWindow.H.map.Marker({lat:p_lat, lng:p_lng},{volatility: true});
        $ExtendMapWindow.$incExtMapMarker.draggable = true;
        
        $ExtendMapWindow.$incExtMapMarkerGroups = new $ExtendMapWindow.H.map.Group();
        $ExtendMapWindow.$incExtMapMarkerGroups.addObject($ExtendMapWindow.$incExtMapMarker );
        $ExtendMapWindow.$ExtendMap.addObject($ExtendMapWindow.$incExtMapMarkerGroups);
    
    }
}

// set pin when extend map open
function set_inc_ext_pin(){
    
    if($ExtendMapWindow.$incExtMapMarkerGroups != null){
        $ExtendMapWindow.$ExtendMap.removeObject($ExtendMapWindow.$incExtMapMarkerGroups);
        $ExtendMapWindow.$incExtMapMarkerGroups = null;
    }
    
    //var p_lat_lng = $incMarkerGroup.getPosition();
    var p_lat_lng = $incMapMarker.getGeometry();

    var p_lat = p_lat_lng.lat;
    var p_lng = p_lat_lng.lng;
        
         
        $ExtendMapWindow.$incExtMapMarker = new $ExtendMapWindow.H.map.Marker({lat:p_lat, lng:p_lng},{volatility: true});
         $ExtendMapWindow.$incExtMapMarker.draggable = true;
        
        $ExtendMapWindow.$incExtMapMarkerGroups = new $ExtendMapWindow.H.map.Group();
        $ExtendMapWindow.$incExtMapMarkerGroups.addObject($ExtendMapWindow.$incExtMapMarker);
        $ExtendMapWindow.$ExtendMap.addObject($ExtendMapWindow.$incExtMapMarkerGroups);
       // window.opener.set_inc_ext_pin_events();
        
//    $ExtendMapWindow.$incExtMapMarker.addListener('dragend',function(event) {
//        inc_marker_dragged(this);
//    });
         
}

function set_inc_ext_pin_events(){
    
    $ExtendMapWindow.$ExtendMap.addEventListener('dragstart', function (ev) {
        //console.log(ev);
        var target = ev.target,
                pointer = ev.currentPointer;
        if (target instanceof $ExtendMapWindow.H.map.Marker) {
            var targetPosition = $ExtendMapWindow.$ExtendMap.geoToScreen(target.getGeometry());
            target['offset'] = new $ExtendMapWindow.H.math.Point(pointer.viewportX - targetPosition.x, pointer.viewportY - targetPosition.y);
            $ExtendMapWindow.$ExtendBehavior.disable();
        }
    }, false);

    $ExtendMapWindow.$ExtendMap.addEventListener('dragend', function (ev) {
        var target = ev.target;
        if (target instanceof $ExtendMapWindow.H.map.Marker) {
            $ExtendMapWindow.$ExtendBehavior.enable();
            var inc_marker_place = $ExtendMapWindow.$incExtMapMarker.getGeometry();
            //window.opener.ext_inc_marker_dragged(inc_marker_place);
            ext_inc_marker_dragged(inc_marker_place);
        }
    }, false);

    $ExtendMapWindow.$ExtendMap.addEventListener('drag', function (ev) {
        var target = ev.target,
                pointer = ev.currentPointer;
        if (target instanceof $ExtendMapWindow.H.map.Marker) {
            target.setGeometry($ExtendMapWindow.$ExtendMap.screenToGeo(pointer.viewportX - target['offset'].x, pointer.viewportY - target['offset'].y));

        }
    }, false);
    
}
function set_inc_add_details(place,map_tagId){
    
    if( typeof(map_tagId)==='undefined' ){
        map_tagId = 'INCIDENT_MAP';
    }

    var p_lat = place.position[0];
    var p_lng = place.position[1];
       
    //console.log(p_lat);
    //console.log(p_lng);
    
    var input = document.getElementById('google_pickup_location');
   
    
    $('#add_inc_details #lat').val(p_lat);
    $('#add_inc_details #lng').val(p_lng);
    
    var $amb_type = [];
    $('input[name="amb_type[]"]:checked').each(function() {
         $amb_type.push($(this).val());
    });

    $inc_type = $('#inc_type').val();
    var $dist_id = $("#incient_dist .mi_autocomplete").val();
     
     
    $('#get_ambu_details').attr('href',base_url+'inc/get_inc_ambu?showprocess=yes&inc_type='+$inc_type+'&amb_tp='+$amb_type+'&lat='+p_lat+'&lng='+p_lng);
    $("#get_ambu_details").click();
   
    $('.previous_inc_btn').attr('data-href',base_url+'inc/previous_incident?showprocess=yes&inc_type='+$inc_type+'lat='+p_lat+'&lng='+p_lng);
    $('.previous_inc_btn').attr('href',base_url+'inc/previous_incident?showprocess=yes&inc_type='+$inc_type+'lat='+p_lat+'&lng='+p_lng);
    $('.previous_inc_btn').attr('data-qr','output_position=previous_incident_details&inc_type='+$inc_type+'&lat='+p_lat+'&lng='+p_lng);
    $('#get_previous_inc_details').attr('data-qr','output_position=previous_incident_details&inc_type='+$inc_type+'&lat='+p_lat+'&lng='+p_lng);
   
    $("#get_previous_inc_details").click();
    
   // init_auto_address('INCIDENT_MAP');
   
    cur_autocom = $(input).attr('data-rel');
    var auto = $('.' + cur_autocom);
    dt_rel = auto.attr('data-rel');
    
    dt_state = 'yes';
    dt_dist = 'yes';
    dt_thl ='yes';
    dt_city = 'yes';
    dt_area = 'yes'
    dt_pin = 'yes'
    loc_dist = "";
    loc_state = "";
    loc_state = "Maharashtra";
    loc_thl ="";
    //console.log(place);
        loc_dist = place.address.county;
        loc_city =  place.address.district;
        loc_state = place.address.state;
        loc_area =  place.address.district;
        loc_pin = place.address.postalCode;
        loc_thl = place.address.city;
        


    data_qr = "dt_auto_addr=inc_auto_addr&dt_rel=incient&dt_state=" + dt_state + "&dt_dist=" + dt_dist + "&dt_thl=" + dt_thl + "&dt_city=" + dt_city + "&dt_area=" + dt_area + "&dt_pin=" + dt_pin + "&loc_state=" + loc_state + "&loc_dist=" + loc_dist + "&loc_city=" + loc_city + "&loc_area=" + loc_area + "&loc_thl=" + loc_thl + "&loc_pin=" + loc_pin + "&dt_lat=" + p_lat + "&dt_log=" + p_lng;

   

    xhttprequest($(this), base_url + 'auto/manage_addr', data_qr);

}

function inc_marker_dragged($incMarkerObj){
    
    var p_lat = $incMarkerObj.lat;
    var p_lng = $incMarkerObj.lng;
    
    address1 =p_lat+','+p_lng;
    
    var $Url = "https://reverse.geocoder.ls.hereapi.com/6.2/reversegeocode.json?prox="+address1+"&mode=retrieveAddresses&maxresults=1&gen=9&apiKey=yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ&politicalview=IND";
    //console.log($Url);
    
    $.get($Url,function($data){
               
                var $place_details =  $data.Response.View[0].Result[0].Location;
                
                $place_details_address = $data.Response.View[0].Result[0].Location.Address.Label;
               
                $('#google_pickup_location').val($place_details_address);
                $place_details.position = [];
                $place_details.address = [];
                $place_details.position[0] = $data.Response.View[0].Result[0].Location.DisplayPosition.Latitude;
                $place_details.position[1] = $data.Response.View[0].Result[0].Location.DisplayPosition.Longitude;
                $place_details.address = $data.Response.View[0].Result[0].Location.Address;
                $place_details.address.county =  $data.Response.View[0].Result[0].Location.Address.County;
                $place_details.address.district =  $data.Response.View[0].Result[0].Location.Address.County;
                $place_details.address.state = $data.Response.View[0].Result[0].Location.Address.State;
                $place_details.address.area =  $data.Response.View[0].Result[0].Location.Address.Label;
                $place_details.address.postalCode = $data.Response.View[0].Result[0].Location.Address.PostalCode;
                $place_details.address.city = $data.Response.View[0].Result[0].Location.Address.City ;
                //console.log($place_details);
            // auto_complete_place_changed($place_details);
                set_inc_add_details($place_details);
                set_inc_main_pin($place_details);
            });
    
}
function ext_inc_marker_dragged($incMarkerObj){
    
    var p_lat = $incMarkerObj.lat;
    var p_lng = $incMarkerObj.lng;
    
    
    address1 =p_lat+','+p_lng;
    
    var $Url = "https://reverse.geocoder.ls.hereapi.com/6.2/reversegeocode.json?prox="+address1+"&mode=retrieveAddresses&maxresults=1&gen=9&apiKey=yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ&politicalview=IND";
    //console.log($Url);
    
    $.get($Url,function($data){
               
                var $place_details =  $data.Response.View[0].Result[0].Location;
                
                $place_details_address = $data.Response.View[0].Result[0].Location.Address.Label;
                
                $('#google_pickup_location').val($place_details_address);
                $place_details.position = [];
                $place_details.address = [];
                $place_details.position[0] = $data.Response.View[0].Result[0].Location.DisplayPosition.Latitude;
                $place_details.position[1] = $data.Response.View[0].Result[0].Location.DisplayPosition.Longitude;
                $place_details.address = $data.Response.View[0].Result[0].Location.Address;
                $place_details.address.county =  $data.Response.View[0].Result[0].Location.Address.County;
                $place_details.address.district =  $data.Response.View[0].Result[0].Location.Address.County;
                $place_details.address.state = $data.Response.View[0].Result[0].Location.Address.State;
                $place_details.address.area =  $data.Response.View[0].Result[0].Location.Address.Label;
                $place_details.address.postalCode = $data.Response.View[0].Result[0].Location.Address.PostalCode;
                $place_details.address.city = $data.Response.View[0].Result[0].Location.Address.City ;
//                window.opener.set_inc_ext_pin();
//                window.opener.set_inc_ext_pin_events();
//                set_inc_ext_pin();
//                set_inc_ext_pin_events();
                set_inc_add_details($place_details);
                set_inc_main_pin($place_details);
            });
    
}
// Update Ambulance Map Pins When Ambulance load
function update_ambulance_inc_map(){
    
    
    if( jQuery('#google_pickup_location').length < 1 ){
        return false;
    }
    
    $('#SelectedAmbulance').html('');
    
    //$ambMapMarkers = {};
    $infoWindows   = [];
    //var $markerBounds = new google.maps.LatLngBounds();
    if($ambMapMarkers != null){
        $callIncidentMap.removeObject($ambMapMarkers);
        $ambMapMarkers = null;
    }
    $ambMapMarkers = new H.map.Group();
    
    //console.log($ambMapMarkers);
    $callIncidentMap.addObject($ambMapMarkers);
    
    var inc_lat = $('#add_inc_details #lat').val();
    var inc_lng = $('#add_inc_details #lng').val();

    //$markerBounds.extend({lat: parseFloat(inc_lat), lng: parseFloat(inc_lng) });

    $('#logTable .ambulance_item_list').each( function() {
        
        
        var amb_obj = this;
        var amb_id = $(this).attr('data-amb_id');
        var amb_lat = $(this).attr('data-lat');
        var amb_lng = $(this).attr('data-lng');
     //   var amb_title = $(this).attr('data-title');
      //  var amb_rto_no = $(this).attr('data-rto-no');
        var amb_status = $(this).attr('data-amb_status');
     //   var amb_details = $(this).find('.ambu_pin_info').html();

      //  console.log(amb_status);
        //console.log($MapPins[amb_status]);
        
        var markerLatLng = {lat: parseFloat(amb_lat), lng: parseFloat(amb_lng) };
        var $amb_icon = new H.map.Icon($MapPins[amb_status], {size: {w: 21, h: 21}});

        var $marker = new H.map.Marker(markerLatLng,{icon: $amb_icon});
         //$marker.setData(amb_details);
         
        $marker.addEventListener('pointerenter', function(evt){

            var infowindow =  new H.ui.InfoBubble(markerLatLng, {
                content: amb_details
            });

            //remove infobubbles
            $callIncidentMapUI.getBubbles().forEach(bub => $callIncidentMapUI.removeBubble(bub));
            $callIncidentMapUI.addBubble(infowindow);

        }, false);
        
        
        $marker.addEventListener('tap', function(evt){
            $('.ambulance_item_list#Search_Amb_'+amb_id+' .amb_check_box').click();
            return false;
        });
        
        $marker.addEventListener('dbltap', function(evt){
            $('.ambulance_item_list#Search_Amb_'+amb_id+' .amb_stand_check_box').click();
            return false;
        });
        
        $ambMapMarkers.addObject($marker);

    });
    
    //$callIncidentMap.fitBounds($markerBounds);
//    $callIncidentMap.setCenter({lat: parseFloat(inc_lat), lng: parseFloat(inc_lng) });
    //console.log('123');
    

    
    if(jQuery("#DefaultSelectedAmb").length >= 1 ){
        var DefaultSelectedAmb = JSON.parse(jQuery("#DefaultSelectedAmb").val());
        
        jQuery.each(DefaultSelectedAmb,function(){
            var amb_id = $('.inc_ambu_list .searched_ambu_item[data-rto-no="'+this+'"]').attr('data-amb_id');
            //google.maps.event.trigger($ambMapMarkers[amb_id] , 'click');
            $('.searched_ambu_item#Search_Amb_'+amb_id+' .amb_check_box').click();
        });
        
    }else{
        // Select First ambulance by default 
        var amb_id = $('.inc_ambu_list .searched_ambu_item').filter('[data-amb_status="41"]').eq(0).attr('data-amb_id');
        if(typeof amb_id == 'undefined'){
              var amb_id = $('.inc_ambu_list .searched_ambu_item').filter('[data-amb_status="11"]').eq(0).attr('data-amb_id');
        }
       //google.maps.event.trigger($ambMapMarkers[amb_id] , 'click');
        $('.searched_ambu_item#Search_Amb_'+amb_id+' .amb_check_box').click();
    }
    
    if($isExtendedMapOpened){
        update_ambulance_inc_ext_map();
    }
    
}

// Update Ambulance Extended Map Pins When Ambulance load
function update_ambulance_inc_ext_map(){
     
    $ExtendMapWindow.$infoExtMapWindows= [];
    
    if($ExtendMapWindow.$ambExtMarkersGroups != null){
        $ExtendMapWindow.$ExtendMap.removeObject($ambExtMarkersGroups);
        $ExtendMapWindow.$ambExtMarkersGroups = null;
    }
    $ExtendMapWindow.$ambExtMarkersGroups = new $ExtendMapWindow.H.map.Group();
    
    //console.log($ambMapMarkers);
    $ExtendMapWindow.$ExtendMap.addObject($ExtendMapWindow.$ambExtMarkersGroups);
    
    //var $ExtmarkerBounds = new $ExtendMapWindow.google.maps.LatLngBounds();
    
    //mark selected ambulance 
    var $SelectedAmbulance=[];
    if(jQuery("#SelectedAmbulance input.selected_ambu_input").length >= 1 ){
        jQuery("#SelectedAmbulance input.selected_ambu_input").each(function(){
            var amb_id = $(this).attr('data-amb_id');
            $SelectedAmbulance[$SelectedAmbulance.length] = amb_id;
        });
    }

    var $StandbyAmbulance=[];
    if(jQuery("#StandbyAmbulance input.standby_ambu_input").length >= 1 ){
        jQuery("#StandbyAmbulance input.standby_ambu_input").each(function(){
            var amb_id = $(this).attr('data-amb_id');
            $StandbyAmbulance[$StandbyAmbulance.length] = amb_id;
        });
    }
    var inc_lat = $('#add_inc_details #lat').val();;
    var inc_lng = $('#add_inc_details #lng').val();;
   // $ExtmarkerBounds.extend({lat: parseFloat(inc_lat), lng: parseFloat(inc_lng) });
    
    $('.inc_ambu_list .searched_ambu_item').each( function() {
        
        var amb_obj = this;
        var amb_id = $(this).attr('data-amb_id');
        var amb_lat = $(this).attr('data-lat');
        var amb_lng = $(this).attr('data-lng');
        var amb_title = $(this).attr('data-title');
        var amb_rto_no = $(this).attr('data-rto-no');
        var amb_status = $(this).attr('data-amb_status');
        var amb_details = $(this).find('.ambu_pin_info').html();
        var amb_type = $(this).attr('data-amb_type');
        var amb_map_pin = $MapPins[amb_status];
        
        if($.inArray(amb_id,$SelectedAmbulance) > -1 ){
            if(amb_type == 2){
                 amb_map_pin = map_102_pin_image;
            }else{
                 amb_map_pin = map_pin_image_hover;
            }
            
        }
        if($.inArray(amb_id,$StandbyAmbulance) > -1 ){
             if(amb_type == 2){
                amb_map_pin = map_102_pin_image;
            }else{
                amb_map_pin = stand_map_pin_image_hover;
            }   
        }
        
        var markerLatLng = {lat: parseFloat(amb_lat), lng: parseFloat(amb_lng) };
        
        var $amb_icon = new $ExtendMapWindow.H.map.Icon(amb_map_pin, {size: {w: 21, h: 21}});
        //$ExtmarkerBounds.extend(markerLatLng); 
        
        var $ext_marker = new $ExtendMapWindow.H.map.Marker(markerLatLng,{icon: $amb_icon});
         
        $ext_marker.addEventListener('pointerenter', function(evt){

            var infowindow =  new $ExtendMapWindow.H.ui.InfoBubble(markerLatLng, {
                content: amb_details
            });

            //remove infobubbles
            $ExtendMapWindow.$ExtendMapUI.getBubbles().forEach(bub => $ExtendMapWindow.$ExtendMapUI.removeBubble(bub));
            $ExtendMapWindow.$ExtendMapUI.addBubble(infowindow);

        }, false);
        
         $ext_marker.addEventListener('tap', function(evt){
            $('.searched_ambu_item#Search_Amb_'+amb_id+' .amb_check_box').click();
            return false;
        });
        
        $ext_marker.addEventListener('dbltap', function(evt){
            $('.searched_ambu_item#Search_Amb_'+amb_id+' .amb_stand_check_box').click();
            return false;
        });
        
        $ExtendMapWindow.$ambExtMarkersGroups.addObject($ext_marker);
        
        
        //if( amb_status == 11 || amb_status == 12 || amb_status == 41 || amb_status == 42 ){ 
            
//            marker.addListener('click', function() {
//                $('.searched_ambu_item#Search_Amb_'+amb_id+' .amb_check_box').click();
//                return false;
//            });
//
//            marker.addListener('dblclick', function() {
//                $('.searched_ambu_item#Search_Amb_'+amb_id+' .amb_stand_check_box').click();
//                return false;
//            });
//        
//       // }
//        
//        var infowindow = new $ExtendMapWindow.google.maps.InfoWindow({
//            content: amb_details
//        });
//        $ExtendMapWindow.$infoExtMapWindows.push(infowindow); 
//
//        marker.addListener('mouseover', function() {
//            for (var i=0;i<$ExtendMapWindow.$infoExtMapWindows.length;i++) {
//                $ExtendMapWindow.$infoExtMapWindows[i].close();
//            }
//            infowindow.open($ExtendMapWindow.$ambExtMapMarkers, marker);
//        });
//
//        $ExtendMapWindow.$ambExtMapMarkers[amb_id] = marker; 
       
    });
    
    //$ExtendMapWindow.$ExtendMap.fitBounds($ExtmarkerBounds);
    // $ExtendMapWindow.$ExtendMap.setCenter({lat: parseFloat(inc_lat), lng: parseFloat(inc_lng) });
     
    //show checked ambulance direction on extended map

    jQuery('.searched_ambu_item .amb_check_box:checked').each(function(){
        
        jQuery(this).click();
    });

}
//Set direction to amb
function set_amb_direction($origin,$destination) {
    
    if($DirectionLineGroup != null){
         $callIncidentMap.removeObject($DirectionLineGroup);
         $DirectionLineGroup = null;
    }
    $DirectionLineGroup = new  H.map.Group();
    $callIncidentMap.addObject($DirectionLineGroup);
    var request = {
        'routingMode': 'fast',
        'transportMode': 'car',
        'origin': $origin,
        'destination': $destination,
        'return': 'polyline'
    };
   
    var $directionsService = $H_Platform.getRoutingService(null, 8);
    $directionsService.calculateRoute(request, function(result) {
        
        if (result.routes.length) {
            result.routes[0].sections.forEach((section) => {

                var $linestring = H.geo.LineString.fromFlexiblePolyline(section.polyline);
                
                $DirectionLine = new H.map.Polyline(
                    $linestring, { style: { lineWidth: 5 }}
                );
                $DirectionLineGroup.addObject($DirectionLine);
                //$callIncidentMap.addObject($DirectionLine);

                //$callIncidentMap.getViewModel().setLookAtData({bounds: $DirectionLine.getBoundingBox()});
                
            });
        }
        
    },
    function(error) {
        //alert(error.message);
    });
    if($isExtendedMapOpened){
        
        if($ExtendMapWindow.$ExtDirectionLineGroup != null){
            $ExtendMapWindow.$ExtendMap.removeObject($ExtendMapWindow.$ExtDirectionLineGroup);
            $ExtendMapWindow.$ExtDirectionLineGroup = null;
        }
            $ExtendMapWindow.$ExtDirectionLineGroup = new $ExtendMapWindow.H.map.Group();
            $ExtendMapWindow.$ExtendMap.addObject($ExtendMapWindow.$ExtDirectionLineGroup);
            var request = {
                'routingMode': 'fast',
                'transportMode': 'car',
                'origin': $origin,
                'destination': $destination,
                'return': 'polyline'
            };

            var $directionsService =  $ExtendMapWindow.$H_Platform_ext.getRoutingService(null, 8);
            $directionsService.calculateRoute(request, function(result) {

                if (result.routes.length) {
                    result.routes[0].sections.forEach((section) => {

                        var $linestring = $ExtendMapWindow.H.geo.LineString.fromFlexiblePolyline(section.polyline);

                        $ExtDirectionLine = new $ExtendMapWindow.H.map.Polyline(
                            $linestring, { style: { lineWidth: 5 }}
                        );
                        $ExtendMapWindow.$ExtDirectionLineGroup.addObject($ExtDirectionLine);
                        //$callIncidentMap.addObject($DirectionLine);

                        //$ExtendMapWindow.$ExtendMap.getViewModel().setLookAtData({bounds: $ExtDirectionLine.getBoundingBox()});

                    });
                }

            },
            function(error) {
                alert(error.message);
            });
    }
    
}


function addCircleToMap($amb_origins,$distance_value){
    
    if($incGeoFence != null){
     $callIncidentMap.removeObject($incGeoFence);
    }
    $incGeoFence = new H.map.Circle(
    // The central point of the circle
    $amb_origins,
    // The radius of the circle in meters
    $distance_value*100,
    {
      style: {
        strokeColor: 'rgba(55, 85, 170, 0.6)', // Color of the perimeter
        lineWidth: 2,
        fillColor: 'rgba(255, 0, 0, 0.5)'  // Color of the circle
      }
    }
  )
  $callIncidentMap.addObject($incGeoFence);
  
    if($isExtendedMapOpened){
        if($ExtendMapWindow.$incExtGeoFence != null){
            $ExtendMapWindow.$ExtendMap.removeObject($ExtendMapWindow.$incExtGeoFence);
        }
                    
        $ExtendMapWindow.$incExtGeoFence = new $ExtendMapWindow.H.map.Circle(
           // The central point of the circle
           $amb_origins,
           // The radius of the circle in meters
           $distance_value*100,
           {
             style: {
               strokeColor: 'rgba(55, 85, 170, 0.6)', // Color of the perimeter
               lineWidth: 2,
               fillColor: 'rgba(255, 0, 0, 0.7)'  // Color of the circle
             }
           }
         );
        $ExtendMapWindow.$ExtendMap.addObject($ExtendMapWindow.$incExtGeoFence);
    }
}

$(document).ready(function(){
    
    $('#container').on('change', '.inc_ambu_list .searched_ambu_item input[type="checkbox"].amb_check_box' , function () {
        
        var $amb_obj = $(this).parents('.searched_ambu_item');
        var amb_id = $amb_obj.attr('data-amb_id');
        var amb_status = $amb_obj.attr('data-amb_status');
        var amb_rto_no = $amb_obj.attr('data-rto-no');
        var amb_lat = $amb_obj.attr('data-lat');
        var amb_lng = $amb_obj.attr('data-lng');
        var amb_type =  $amb_obj.attr('data-amb_type');
        var inc_lat = $('#add_inc_details #lat').val();
        var inc_lng = $('#add_inc_details #lng').val();
        var $distance_value = $amb_obj.attr('data-amb_geofence');
        
        if($distance_value == 'defult distance' || $distance_value == '' || $distance_value == 0 ){
            $distance_value = 10;
        }
       
        
        //google.maps.event.trigger($ambMapMarkers[amb_id] , 'click');
        //$ExtendMapWindow.google.maps.event.trigger($ExtendMapWindow.$ambExtMapMarkers[amb_id] , 'click');
        if(typeof H != 'undefined'){
           // $incGeoFence.setMap(null);
        }
        
        var $inc_type =  $('#inc_type').val();

        if( $.trim($inc_type) == 'non-mci' ||  $.trim($inc_type) == 'NON_MCI' ||  $.trim($inc_type) == 'VIP_CALL' ){
            //console.log($inc_type);

            //$(amb_obj).find('.amb_check_box').prop('checked', true);
            $amb_obj.addClass('amb_selected');
            $('#SelectedAmbulance .selected_ambu_input').remove();
            $('.inc_ambu_list .searched_ambu_item').removeClass('amb_selected');
            $('.inc_ambu_list .searched_ambu_item').find('.amb_check_box').prop('checked', false);

        }

        if( $('#StandbyAmbulance #Standby_Amb_ID_'+amb_id).length >= 1 ){
            //$ambMapMarkers[amb_id].setIcon($MapPins[amb_status]);
            if($isExtendedMapOpened){
                //$ExtendMapWindow.$ambExtMapMarkers[amb_id].setIcon($MapPins[amb_status]);
            }
            $amb_obj.removeClass('amb_selected');
            $amb_obj.find('.amb_stand_check_box').prop('checked', false);
            $('#StandbyAmbulance #Standby_Amb_ID_'+amb_id).remove();
        }

        if( $('#SelectedAmbulance #Selected_Amb_ID_'+amb_id).length >= 1 ){
            //$ambMapMarkers[amb_id].setIcon($MapPins[amb_status]);
            if($isExtendedMapOpened){
                //$ExtendMapWindow.$ambExtMapMarkers[amb_id].setIcon($MapPins[amb_status]);
            }
            $amb_obj.removeClass('amb_selected');
            $amb_obj.find('.amb_check_box').prop('checked', false);
            $('#SelectedAmbulance #Selected_Amb_ID_'+amb_id).remove();
        }else{
             if(amb_type == 2){
                map_pin_image_hover = map_102_pin_image;
            }else if(amb_type == 1){
                map_pin_image_hover = map_pin_image_hover;
            }else{
                map_pin_image_hover = map_pin_image;
            }
              //console.log("non_mci");
            
            if(typeof H != 'undefined'){
                //$ambMapMarkers[amb_id].setIcon(map_pin_image_hover);
                if($isExtendedMapOpened){  
                    //$ExtendMapWindow.$ambExtMapMarkers[amb_id].setIcon(map_pin_image_hover);
                }
            }
            $amb_obj.addClass('amb_selected');
            $amb_obj.find('.amb_check_box').prop('checked', true);

            $ambulance_input = '<input type="hidden" name="incient[amb_id]['+amb_id+']" id="Selected_Amb_ID_'+amb_id+'" data-amb_id="'+amb_id+'" value="'+amb_rto_no+'" class="selected_ambu_input filter_required" data-errors="{filter_required:\'Please select Ambulance\'}">';
            $('#SelectedAmbulance').append($ambulance_input);
            
             //set direction
          //  $destination = {lat: parseFloat(inc_lat), lng:  parseFloat(inc_lng)};
            $amb_origin = {lat:  parseFloat(amb_lat), lng:  parseFloat(amb_lng)};
           var $amb_origins = {lat:  parseFloat(amb_lat), lng:  parseFloat(amb_lng)};
            $destination = inc_lat+','+inc_lng;
            $origin = amb_lat+','+amb_lng;
            
            if(typeof H != 'undefined'){
                
                set_amb_direction($origin,$destination);
                //addCircleToMap($amb_origins,$distance_value);

            }
            
        }
        
    });
    
    $('#container').on('change', '.inc_ambu_list .searched_ambu_item input[type="checkbox"].amb_stand_check_box' , function () {
        
        
        var $amb_obj = $(this).parents('.searched_ambu_item');
        var amb_id = $amb_obj.attr('data-amb_id');
        var amb_status = $amb_obj.attr('data-amb_status');
        var amb_rto_no = $amb_obj.attr('data-rto-no');
        var amb_lat = $amb_obj.attr('data-lat');
        var amb_lng = $amb_obj.attr('data-lng');
        var inc_lat = $('#add_inc_details #lat').val();
        var inc_lng = $('#add_inc_details #lng').val();
        var $distance_value = $amb_obj.attr('data-amb_geofence');
        var amb_type =  $amb_obj.attr('data-amb_type');
        
        if($distance_value == 'defult distance' || $distance_value == '' || $distance_value == 0){
            $distance_value = 5;
        }
        

        
        //google.maps.event.trigger($ambMapMarkers[amb_id] , 'dblclick');
        //$ExtendMapWindow.google.maps.event.trigger($ExtendMapWindow.$ambExtMapMarkers[amb_id] , 'dblclick');
        
        var $inc_type =  $('#inc_type').val();

        if( $('#SelectedAmbulance #Selected_Amb_ID_'+amb_id).length >= 1 ){
            if(typeof H != 'undefined'){
                $ambMapMarkers[amb_id].setIcon($MapPins[amb_status]);
                if($isExtendedMapOpened){
                    $ExtendMapWindow.$ambExtMapMarkers[amb_id].setIcon($MapPins[amb_status]);
                }
            }
            $amb_obj.removeClass('amb_selected');
            $amb_obj.find('.amb_check_box').prop('checked', false);
            $('#SelectedAmbulance #Selected_Amb_ID_'+amb_id).remove();
        }

        if( $('#StandbyAmbulance #Standby_Amb_ID_'+amb_id).length >= 1 ){
            
            if(typeof H != 'undefined'){
                $ambMapMarkers[amb_id].setIcon($MapPins[amb_status]);
                if($isExtendedMapOpened){
                    $ExtendMapWindow.$ambExtMapMarkers[amb_id].setIcon($MapPins[amb_status]);
                }
            }
            $amb_obj.removeClass('amb_selected');
            $amb_obj.find('.amb_stand_check_box').prop('checked', false);
            $('#StandbyAmbulance #Standby_Amb_ID_'+amb_id).remove();
        }else{
            
            if(amb_type == 2){
                stand_map_pin_image_hover = map_102_pin_image;
           }else if(amb_type == 1){
                stand_map_pin_image_hover = stand_map_pin_image_hover;
            } else{
                map_pin_image_hover = map_pin_image;
            }
            
            $ambMapMarkers[amb_id].setIcon(stand_map_pin_image_hover);
            if($isExtendedMapOpened){
                $ExtendMapWindow.$ambExtMapMarkers[amb_id].setIcon(stand_map_pin_image_hover);
            }
            $amb_obj.addClass('amb_selected');
            $amb_obj.find('.amb_stand_check_box').prop('checked', true);

            $ambulance_input = '<input type="hidden" name="incient[stand_amb_id]['+amb_id+']" id="Standby_Amb_ID_'+amb_id+'" data-amb_id="'+amb_id+'" value="'+amb_rto_no+'" class="standby_ambu_input filter_required" data-errors="{filter_required:\'Please select Ambulance\'}">';
            $('#StandbyAmbulance').append($ambulance_input);
            
            //set direction
          //  $destination = {lat: parseFloat(inc_lat), lng:  parseFloat(inc_lng)};
            $amb_origin = {lat:  parseFloat(amb_lat), lng:  parseFloat(amb_lng)};
            $destination = inc_lat+','+inc_lng;
            $origin = amb_lat+','+amb_lng;
            //console.log($destination);
            
            if(typeof H != 'undefined'){
                //if($destination != ''){
                    set_amb_direction($origin,$destination);
                //}
                addCircleToMap($origin,$destination);
            }
        }
        
    });
    
    $('#container').on('mouseover', '.inc_ambu_list .searched_ambu_item' , function () {
        
        var amb_lat = $(this).attr('data-lat');
        var amb_lng = $(this).attr('data-lng');
        var amb_id  = $(this).attr('data-amb_id');
        if(typeof google != 'undefined'){
            google.maps.event.trigger($ambMapMarkers[amb_id] , 'mouseover');
            if($isExtendedMapOpened){
                $ExtendMapWindow.google.maps.event.trigger($ExtendMapWindow.$ambExtMapMarkers[amb_id] , 'mouseover');
            }
        }
    });
    
    $('#container').on('click', '#inc_recomended_ambu .amb_type_checkbox' , function () {
        var p_lat = $('#add_inc_details #lat').val();
        var p_lng = $('#add_inc_details #lng').val();

        var $amb_type = [];
        $('input[name="amb_type[]"]:checked').each(function() {
             $amb_type.push($(this).val());
        });

         $inc_type = $('#inc_type').val();
         var $dist_id = $("#incient_dist .mi_autocomplete").val();

        if ($("input[name='addtess_type']:checked").val() == 'google_addres') {
            if(p_lat != '' && p_lng != ''){
            
         
            $('#get_ambu_details').attr('href',base_url+'inc/get_inc_ambu?inc_type='+$inc_type+'&amb_tp='+$amb_type+'&lat='+p_lat+'&lng='+p_lng+'&district_id='+$dist_id);
            $("#get_ambu_details").click();
            }
        }else{
            $('#get_ambu_details').attr('href',base_url+'inc/get_inc_ambu?inc_type='+$inc_type+'&amb_tp='+$amb_type+'&lat='+p_lat+'&lng='+p_lng+'&district_id='+$dist_id);
            $("#get_ambu_details").click();
        }
    });
    $('#container').on('click', '#inc_recomended_ambu .amb_status_checkbox' , function () {
        var p_lat = $('#add_inc_details #lat').val();
        var p_lng = $('#add_inc_details #lng').val();
        
          var $amb_type = [];
        $('input[name="amb_type[]"]:checked').each(function() {
             $amb_type.push($(this).val());
        });

        var $status = [1];
        
        $('input[name="amb_status[]"]:checked').each(function() {
             $status.push($(this).val());
        });
        
        var $dist_id = $("#incient_dist .mi_autocomplete").val();

        $('#get_ambu_details').attr('href',base_url+'inc/get_inc_ambu?inc_type='+$inc_type+'&amb_tp='+$amb_type+'&lat='+p_lat+'&lng='+p_lng+'&inc_status='+$status+'&district_id='+$dist_id);
        $("#get_ambu_details").click();
    });
    
    
});

$(document).ready(function(){

    $('#container').on('change', '.inc_ambu_list .searched_ambu_item input[type="checkbox"]' , function () {
        if($(this).is(':checked')){
            $(this).parent().find('input[type="checkbox"]').prop('checked',false);
            $(this).prop('checked',true);
        }
    });

});

function get_location_by_address(address){
    
  //  var address =$('#incient_dist .mi_autocomplete_input').val();
  
    address = address;
    //address = address;
    $('#google_pickup_location').val(address);
    if(typeof google != 'undefined'){
        $incGeocoder = new google.maps.Geocoder();
        $incGeocoder.geocode( { 'address': address}, function(results, status) {
            if (status == 'OK') {
                set_inc_main_pin(results[0]);
                set_inc_add_details(results[0]);
            } else {
                alert('Google was not successful for the following reason: ' + status);
            }
        });
    }
}
function get_location_by_address_tahsil(address){
    
    var dist_address =$('#incient_dist .mi_autocomplete_input').val();

    address = 'Bus stop, '+dist_address+','+address+' ';
    $('#google_pickup_location').val(address);
    if(typeof google != 'undefined'){

        $incGeocoder = new google.maps.Geocoder();
        $incGeocoder.geocode( { 'address': address}, function(results, status) {
            if (status == 'OK') {
                set_inc_main_pin(results[0]);
                set_inc_add_details(results[0]);
            } else {
                alert('Google was not successful for the following reason: ' + status);
            }
        });
    }
}
function get_location_by_address_city(address){
    
    var dist_address =$('#incient_dist .mi_autocomplete_input').val();
    var tahsil_address =$('#incient_tahsil .mi_autocomplete_input').val();
    
    address = 'Bus stop, '+dist_address+','+tahsil_address+', '+address+'';
    $('#google_pickup_location').val(address);

    $incGeocoder = new google.maps.Geocoder();
    $incGeocoder.geocode( { 'address': address}, function(results, status) {
        if (status == 'OK') {
            set_inc_main_pin(results[0]);
            set_inc_add_details(results[0]);
        } else {
            alert('Google was not successful for the following reason: ' + status);
        }
    });
}


function get_address_ambulance(){
    var address =$('#IncAddress').val();
   // address = 'Bus stop, '+address+', Tamil Nadu';
   //var address = address1.replace('<br/>','');
    $incGeocoder = new google.maps.Geocoder();
    $incGeocoder.geocode( { 'address': address}, function(results, status) {
        if (status == 'OK') {
            set_inc_add_details(results[0]);
        } else {
            alert('Google was not successful for the following reason: ' + status);
        }
    });
}
function get_new_hospital_ambulance(){
    var address =$('#facility_details .new_hos_address').val();
    var lat =$('#facility_details .new_hos_lat').val();
    var lng =$('#facility_details .new_hos_lng').val();
    address1 =lat+','+lng;
    console.log($("input[name='addtess_type']:checked").val());

    $('#google_pickup_location').val(address);
    address1 =lat+','+lng;
    if ($("input[name='addtess_type']:checked").val() != 'manual_address') {

    
    var $Url = "https://reverse.geocoder.ls.hereapi.com/6.2/reversegeocode.json?prox="+address1+"&mode=retrieveAddresses&maxresults=1&gen=9&apiKey=yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ&politicalview=IND";
    
    $.get($Url,function($data){
               
                var $place_details =  $data.Response.View[0].Result[0].Location;
                
                $place_details.position = [];
                $place_details.address = [];
                $place_details.position[0] = $data.Response.View[0].Result[0].Location.DisplayPosition.Latitude;
                $place_details.position[1] = $data.Response.View[0].Result[0].Location.DisplayPosition.Longitude;
                $place_details.address = $data.Response.View[0].Result[0].Location.Address;
//                $place_details.address.county =  $data.Response.View[0].Result[0].Location.Address.County;
//                $place_details.address.district =  $data.Response.View[0].Result[0].Location.Address.County;
//                $place_details.address.state = $data.Response.View[0].Result[0].Location.Address.State;
//                $place_details.address.area =  $data.Response.View[0].Result[0].Location.Address.Label;
//                $place_details.address.postalCode = $data.Response.View[0].Result[0].Location.Address.PostalCode;
//                $place_details.address.city = $data.Response.View[0].Result[0].Location.Address.City ;
            // auto_complete_place_changed($place_details);
                set_inc_add_details($place_details);
                set_inc_main_pin($place_details);
            });
        }
    
//    $incGeocoder = new google.maps.Geocoder();
//    $incGeocoder.geocode( { 'address': address1}, function(results, status) {
//        if (status == 'OK') {
//            set_inc_add_details(results[0]);
//            set_inc_main_pin(results[0]);
//        } else {
//            alert('Google was not successful for the following reason: ' + status);
//        }
//    });
}


//Extent Map popup window functions


function open_extend_map(url){
    $ExtendMapWindow = window.open(url, "Incident Map", "width=800,height=600,location=no,menubar=no,left=10%,top=5%","replace=true");
    $isExtendedMapOpened = 1;
    $($ExtendMapWindow).on("beforeunload", function() { 
        $isExtendedMapOpened = 0;
    });
}

$(window).load(function(){
    
    if($isExtendedMapOpened){
        $(window).on("beforeunload", function() { 
            $ExtendMapWindow.close();
        });
    }
    
});



var $ExtendMap,$ExtendMapUI,$H_Platform_ext,$ExtendBehavior;
var $ambExtMarkersGroups = null;
var $ExtDirectionLineGroup = null;
var $ambExtMapMarkers = {};
var $infoExtMapWindows = [];
var $incExtMapMarker;
var $incExtMapMarkerGroups = null;
var $ext_autocomplete;

var $ext_directionsService;
var $ext_directionsDisplay;

$(window).load(function(){
    
    if( $("#EXTENDED_INCIDENT_MAP").length >= 1 ){
        initExtIncidentMap();
       
    }
    
});



function initExtIncidentMap() {

    var myLatLng = {lat: 19.0760, lng: 72.8777};
    
    $H_Platform_ext = new H.service.Platform({
        apikey: 'yrjPrIYd0xU9KJpe1xlaR1_K1wFrwc9U-_-99n040JQ'
    });
    var defaultLayers = $H_Platform_ext.createDefaultLayers();
    
    $ExtendMap = new H.Map(
        document.getElementById('EXTENDED_INCIDENT_MAP'),
        defaultLayers.vector.normal.map, {
        center: myLatLng,
        zoom: 10,
        //pixelRatio: window.devicePixelRatio || 1
      });
      
 
      
     $ExtendBehavior = new H.mapevents.Behavior(new H.mapevents.MapEvents($ExtendMap));

    
    $ExtendMap.tagId = 'EXTENDED_INCIDENT_MAP';
    
    window.addEventListener('resize', () => $ExtendMap.getViewPort().resize());
    $ExtendMapUI = H.ui.UI.createDefault($ExtendMap, defaultLayers);
    ext_map_autocomplete();
    
    window.opener.update_ambulance_inc_ext_map();
    //window.opener.auto_complete_place_changed();
    window.opener.set_inc_ext_pin();
    window.opener.set_inc_ext_pin_events();
    

//    $ext_autocomplete  = new google.maps.places.SearchBox(input, options);
//    $ext_autocomplete.addListener('places_changed', window.opener.ext_auto_complete_place_changed );
//    
//    $incExtGeoFence = new google.maps.Circle({});
    
}
