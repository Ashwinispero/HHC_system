<link rel="SHORTCUT ICON" href="images/favicon.ico" type="image/ico" /> 
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script> 
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
<!-- Scrollbar Styles--> 
<script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script> 
<!-- Scrollbar Styles End--> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
    <script type="text/javascript">	
        function heightAdjIE6()
        {
            var heigh= 0;
            var width= 0;			
            if (document.body && document.body.offsetWidth)
            {
                width = document.body.offsetWidth;
                heigh = document.body.offsetHeight;
            }
            if (document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth ) 
            {
                width = document.documentElement.offsetWidth;
                heigh = document.documentElement.offsetHeight;
            }
            if (window.innerWidth && window.innerHeight) 
            {
                width = window.innerWidth;
                heigh = window.innerHeight;
            }
            // $(".login-page").css({'height':heigh-50});
            //alert(heigh);
             $(".col-left-right").css({'width':width-430});
        }
        $(document).ready(function() 
        {
            heightAdjIE6();
            $(window).resize(function() 
            {
                heightAdjIE6();
            });                
        });
        $('a').tooltip('hide');
        $('a:hover').tooltip('show');        
        function changePagination(assingedDivClass,processPage,pageId,show_records,sort_type,sort_variable)
        {
             //$(".flash").show();
             var queryString='';
             var dataString='';
             if(assingedDivClass=="eventLogListing" || assingedDivClass=="KnowledgeDocsListing" ||  assingedDivClass=="AssessmentListing" || assingedDivClass == "enquiryFollowUpListing")
             {
                 var SearchKey=$("#SearchKeyword").val();
                 var SearchKeyword_new=$("#SearchKeyword_new").val();
                 var SearchByPurpose=$("#search_purpose_id").val();
                // var SearchByEmployee=$("#search_employee_id").val();
                 var SearchByProfessional=$("#search_professional_id").val();
                 var SearchfromDate=$("#event_from_date").val();
                 var SearchToDate=$("#event_to_date").val();
                 var SearchToDate_service=$("#event_to_date_service").val();
                 var SearchfromDate_service=$("#event_from_date_service").val();
                 var SearchByPatients=$("#selected_patient_id").val();
                 var purpose_call_event=$("#purpose_call_event").val();
                 var status_val=$("#list_status_val").val();
                // alert(status_val);
                 //queryString+='&SearchKey='+SearchKey='&SearchfromDate='+SearchfromDate+'&SearchToDate='+SearchToDate;
                 queryString+='&SearchKey='+SearchKey;
                 queryString+='&SearchKeyword_new='+SearchKeyword_new;
                 queryString+='&SearchByPurpose='+SearchByPurpose;
             //    queryString+='&SearchByEmployee='+SearchByEmployee;
                 queryString+='&SearchByProfessional='+SearchByProfessional;
                 queryString+='&SearchfromDate='+SearchfromDate;
                 queryString+='&SearchToDate='+SearchToDate;
                 queryString+='&SearchfromDate_service='+SearchfromDate_service;
                 queryString+='&SearchToDate_service='+SearchToDate_service;
                 queryString+='&SearchByPatients='+SearchByPatients;
                 queryString+='&purpose_call_event='+purpose_call_event;
                 queryString+='&isStatus='+status_val;
             }
             else if(assingedDivClass=="searchPatientListing")
             {
                 //var SearchKey=$("#organizerKeyword").val();
                 var existing_hhc_code=$("#existing_hhc_code").val();
                 var existing_patient_name=$("#existing_patient_name").val();
                 var existing_mobile_no=$("#existing_mobile_no").val();
                 var ex_landline_no=$("#ex_landline_no").val();
                 var ex_dob=$("#existing_dob").val();
                 queryString+='&existing_hhc_code='+existing_hhc_code;
                 queryString+='&existing_patient_name='+existing_patient_name;
                 queryString+='&existing_mobile_no='+existing_mobile_no;
                 queryString+='&ex_landline_no='+ex_landline_no;
                 queryString+='&existing_dob='+ex_dob;
             }
             else if(assingedDivClass == 'newPatientListing')
             {
                 var SearchByPatients=$("#selected_patient_exist").val();
                 var temp_event_id=$("#temp_event_id").val();
                 var prv_purpose_id=$("#prv_purpose_id").val();
                 queryString+='&SearchByPatients='+SearchByPatients;
                 queryString+='&temp_event_id='+temp_event_id;
                 queryString+='&Select_purpose_id='+prv_purpose_id;
             }
             else //if(assingedDivClass == 'ProfessionalIncludeDiv')
             {
                 var professionalKeyword=$("#professionalKeyword").val();
                 var availability=$("#availability").val();
                 var Proflocation_id=$("#Proflocation_id").val();
                 var Prof_service_id=$("#Prof_service_id").val();
                 var profes_event_id=$("#profes_event_id").val();
                 queryString+='&professionalKeyword='+professionalKeyword;
                 queryString+='&availability='+availability;
                 queryString+='&Proflocation_id='+Proflocation_id;
                 queryString+='&service_id='+Prof_service_id;
                 queryString+='&profes_event_id='+profes_event_id;
             }
             //if(RecordId)
            //    dataString = 'pageId='+ pageId+queryString+'&show_records='+show_records+'&sort_field='+sort_variable+'&sort_order='+sort_type+'&record_id='+RecordId;
            // else
                dataString = 'pageId='+pageId+queryString+'&show_records='+show_records+'&sort_field='+sort_variable+'&sort_order='+sort_type;
            //alert(queryString);
            
             Display_Load();
             //alert(dataString);
             $.ajax({
                   type: "POST",
                   url: processPage,
                   data: dataString,
                   cache: false,
                   success: function(html)
                   {
                       //alert(html);
                        if(html=="notLoggedIn")
                            notLoggedIn();
                        else
                        {
                            $("."+assingedDivClass).html(html);
                            $('a').tooltip('hide');
                            $('a:hover').tooltip('show'); 
                        }
                        if(assingedDivClass=="searchPatientListing")
                        {
                            var wall = new Freewall("#freewall");
                            wall.fitWidth();
                            ResizeWindow();
                        }
                        Hide_Load();
                   }
              });
        }
        function changePagePatient(assingedDivClass,processPage,pageId,show_records,sort_type,sort_variable,service_id)
        {
             //$(".flash").show();
             var queryString='';
             var dataString='';    
                  var SearchByPatients=$("#selected_patient_exist").val();
                 var temp_event_id=$("#temp_event_id").val();
                 var prv_purpose_id=$("#purpose_call_event").val();
                 queryString+='&SearchByPatients='+SearchByPatients;
                 queryString+='&temp_event_id='+temp_event_id;
                 queryString+='&Select_purpose_id='+prv_purpose_id;
             //if(RecordId)
            //    dataString = 'pageId='+ pageId+queryString+'&show_records='+show_records+'&sort_field='+sort_variable+'&sort_order='+sort_type+'&record_id='+RecordId;
            // else
                dataString = 'pageId='+pageId+queryString+'&show_records='+show_records+'&sort_field='+sort_variable+'&sort_order='+sort_type;
            //alert(queryString);
            
             Display_Load();
             //alert(dataString);
             $.ajax({
                   type: "POST",
                   url: processPage,
                   data: dataString,
                   cache: false,
                   success: function(html)
                   {
                       //alert(html);
                        if(html=="notLoggedIn")
                            notLoggedIn();
                        else
                        {
                            $("."+assingedDivClass).html(html);
                            $('a').tooltip('hide');
                            $('a:hover').tooltip('show'); 
                        }
                        if(assingedDivClass=="searchPatientListing")
                        {
                            var wall = new Freewall("#freewall");
                            wall.fitWidth();
                            ResizeWindow();
                        }
                        Hide_Load();
                   }
              });
        }
        function changePageProfessional(assingedDivClass,processPage,pageId,show_records,sort_type,sort_variable,service_id)
        {
             //$(".flash").show();
             var queryString='';
             var dataString='';    
                 var professionalKeyword=$("#professionalKeyword_"+service_id).val();
                 var availability=$("#availability_"+service_id).val();
                 var Proflocation_id=$("#Proflocation_id_"+service_id).val();
                 var Prof_service_id=$("#Prof_service_id_"+service_id).val();
                 var profes_event_id=$("#profes_event_id").val();
                 var kmslider=$("#kmslider_"+service_id).val();
                 var splitStr = kmslider.split(',');
                 if(splitStr[0])
                     kmsliderfrom = splitStr[0];
                 else
                     kmsliderfrom = '0';
                 
                 if(splitStr[1])
                     kmsliderto = splitStr[1];
                 else
                     kmsliderto = '10';
                 
                 queryString+='&professionalKeyword='+professionalKeyword;
                 queryString+='&availability='+availability;
                 queryString+='&Proflocation_id='+Proflocation_id;
                 queryString+='&service_id='+Prof_service_id;
                 queryString+='&profes_event_id='+profes_event_id;
                 queryString+='&kmsliderfrom='+kmsliderfrom;
                 queryString+='&kmsliderto='+kmsliderto;
             //if(RecordId)
            //    dataString = 'pageId='+ pageId+queryString+'&show_records='+show_records+'&sort_field='+sort_variable+'&sort_order='+sort_type+'&record_id='+RecordId;
            // else
                dataString = 'pageId='+pageId+queryString+'&show_records='+show_records+'&sort_field='+sort_variable+'&sort_order='+sort_type;
            //alert(queryString);
            
             Display_Load();
             //alert(dataString);
             $.ajax({
                   type: "POST",
                   url: processPage,
                   data: dataString,
                   cache: false,
                   success: function(html)
                   {
                      //alert(html);
                        if(html=="notLoggedIn")
                            notLoggedIn();
                        else
                        {
                            $("."+assingedDivClass).html(html);
                            $('a').tooltip('hide');
                            $('a:hover').tooltip('show'); 
                            //alert('hi');
                            $(".profeCOntent").mCustomScrollbar({
                                    setHeight:350,
                                    //theme:"minimal-dark"
                            });
                        }
                        if(assingedDivClass=="searchPatientListing")
                        {
                            var wall = new Freewall("#freewall");
                            wall.fitWidth();
                            ResizeWindow();
                        }
                        Hide_Load();
                   }
              });
        }
        function notLoggedIn()
        {
            alert("Your session expired, please login.");
            window.location.href='index.php';
        }
        function Display_Load()
        {        
            var heightWindow=$(document.body).prop('scrollHeight');
            $("#loadingAjax").css('height',heightWindow+100);
            $("#loadingAjax").fadeIn(900,0);
            //$("#loadingAjax").show();
            //alert(heightWindow);
        }
        //Hide Loading Image
        function Hide_Load()
        {
            $("#loadingAjax").fadeOut('slow');
        }
        function Popup_Display_Load()
        {      
            var heightWindow=$(document.body).prop('scrollHeight');
            $("#PopuploadingAjax").css('height',heightWindow+100);
            $("#PopuploadingAjax").fadeIn(900,0);
        }
        //Hide Loading Image
        function Popup_Hide_Load()
        {
            $("#PopuploadingAjax").fadeOut('slow');
        }	
    </script>
    <div id="loadingAjax" style="display: none;">
    	<div class="spinner">
          <div class="spinner-container container1">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
          <div class="spinner-container container2">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
          <div class="spinner-container container3">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
      </div>
    </div>
    
    <div id="PopuploadingAjax" style="display: none;">
    	<div class="spinner">
          <div class="spinner-container container1">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
          <div class="spinner-container container2">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
          <div class="spinner-container container3">
            <div class="circle1"></div>
            <div class="circle2"></div>
            <div class="circle3"></div>
            <div class="circle4"></div>
          </div>
      </div>
    </div>