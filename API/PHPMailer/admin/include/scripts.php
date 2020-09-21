<link rel="SHORTCUT ICON" href="images/favicon.ico" type="image/ico" /> 
    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-11.1.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
   
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/jquery.classyscroll.js"></script>
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

            $("#menu-scollbar").css({'height':heigh-90});	
			//$(".hide-scrollbars").css({'height':heigh+70});
            $("#page-wrapper").css({'min-height':heigh-50});
			 $(".login-page").css({'height':heigh-50});
            //alert(heigh);
            // $(".footer_cont").css({'width':width-150});
        }
        $(document).ready(function() 
        {
            heightAdjIE6();
            $(window).resize(function() 
            {
                heightAdjIE6();
            });                
        });
		$('.scrollbars1').ClassyScroll();
        $('a').tooltip('hide');
        $('a:hover').tooltip('show');
        function changePagination(assingedDivClass,processPage,pageId,show_records,sort_type,sort_variable)
        {
             //$(".flash").show();
             var queryString='';
             var dataString='';
             if(assingedDivClass=="ServicesListing" || assingedDivClass=="ServicesTrashListing" || assingedDivClass=="SystemUserListing" || assingedDivClass=="SystemUserTrashListing" || assingedDivClass=="MedicinesListing" || assingedDivClass=="MedicinesTrashListing" || assingedDivClass == "ConsumablessListing" || assingedDivClass=="ConsumablesTrashListing" || assingedDivClass=="FeedbackListing" || assingedDivClass=="FeedbackTrashListing" || assingedDivClass=="EmployeesListing" || assingedDivClass=="EmployeesTrashListing" || assingedDivClass=="ProfessionalsListing" || assingedDivClass=="ProfessionalsTrashListing" || assingedDivClass=="KnowledgeDocsListing" || assingedDivClass=="KnowledgeDocsTrashListing" || assingedDivClass=="ConsultantsListing" || assingedDivClass=="ConsultantsTrashListing" || assingedDivClass=="LocationsListing" || assingedDivClass=="LocationsTrashListing" || assingedDivClass=="PatientsListing" || assingedDivClass=="PatientsTrashListing" || assingedDivClass=="EventsListing" || assingedDivClass=="EventsTrashListing" || assingedDivClass=="SpecialtyListing" || assingedDivClass=="SpecialtyTrashListing" || assingedDivClass=="SubLocationsListing" || assingedDivClass=="SubLocationsTrashListing" || assingedDivClass=="HospitalsListing" || assingedDivClass=="TrashHospitalsListing" || assingedDivClass=="PhysiotherapyEventsListing")
             {
                 var SearchKey=$("#SearchKeyword").val();
                 if(SearchKey !='undefined' && SearchKey !='null')
                 {
                    queryString+='&SearchKey='+SearchKey;
                 }
                 
                 var SearchByLocation=$("#location_id").val();
                 if(SearchByLocation !='undefined' && SearchByLocation !='null')
                 {
                    queryString+='&location_id='+SearchByLocation;
                 }
                 
                 var SearchByservices=$("#Prof_service_id").val();
                 if(SearchByservices !='undefined' && SearchByservices !='null')
                 {
                    queryString+='&Prof_service_id='+SearchByservices;
                 }
                 
                 var SearchByProfession=$("#reference_type").val();
                 if(SearchByProfession !='undefined' && SearchByProfession !='null')
                 {
                    queryString+='&reference_type='+SearchByProfession;
                 }
                 
                 var SearchByPurpose=$("#search_purpose_id").val();
                 if(SearchByPurpose !='undefined' && SearchByPurpose !='null')
                 {
                    queryString+='&SearchByPurpose='+SearchByPurpose;
                 }
                 
                 
                 var SearchByEmployee=$("#search_employee_id").val();
                 if(SearchByEmployee !='undefined' && SearchByEmployee !='null')
                 {
                    queryString+='&SearchByEmployee='+SearchByEmployee;
                 }
                 
                 var SearchByProfessional=$("#search_professional_id").val();
                 if(SearchByProfessional !='undefined' && SearchByProfessional !='null')
                 {
                    queryString+='&SearchByProfessional='+SearchByProfessional;
                 }
                 
                 var SearchByService=$("#search_service_id").val();
                 if(SearchByService !='undefined' && SearchByService !='null')
                 {
                    queryString+='&SearchByService='+SearchByService;
                 }
                 
                 var SearchfromDate=$("#event_from_date").val();
                 if(SearchfromDate !='undefined' && SearchfromDate !='null')
                 {
                    queryString+='&SearchfromDate='+SearchfromDate;
                 }
                 
                 var SearchToDate=$("#event_to_date").val();
                 if(SearchToDate !='undefined' && SearchToDate !='null')
                 {
                    queryString+='&SearchToDate='+SearchToDate;
                 }
                 
                 var RecordId=$("#record_id").val();
                 if(RecordId !='undefined' && RecordId !='null')
                 {
                    queryString+='&record_id='+RecordId;
                 }
             }
             if(RecordId)
                dataString = 'pageId='+ pageId+queryString+'&show_records='+show_records+'&sort_field='+sort_variable+'&sort_order='+sort_type+'&record_id='+RecordId;
             else
                dataString = 'pageId='+ pageId+queryString+'&show_records='+show_records+'&sort_field='+sort_variable+'&sort_order='+sort_type;
            
             Display_Load();
             //alert(dataString);
             $.ajax({
                   type: "POST",
                   url: processPage,
                   data: dataString,
                   cache: false,
                   success: function(html)
                   {
                        if(html=="notLoggedIn")
                            notLoggedIn();
                        else
                        {
                            $("."+assingedDivClass).html(html);
                            $('a').tooltip('hide');
                            $('a:hover').tooltip('show'); 
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
            //$("#loadingAjax").show();
            //alert(heightWindow);
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