<?php
    //ini_set('display_errors', '1');
    error_reporting(1);
    ini_set('realpath_cache_size','16k');
    ini_set('realpath_cache_ttl','120');
    ini_set('max_execution_time','300');
    ini_set('max_input_time','300');
    ini_set('memory_limit','1024M');
    ini_set('session.gc_maxlifetime', 3*60*60);
    const SECRET = "f3b2c60cf125efcaff4dc6d3c906d73c";
    if(str_replace("www.","",$_SERVER['HTTP_HOST'])=="192.168.12.120")
     {
        $siteURL                    = "http://45.40.136.143/~spero/speroapp_broadcast/";
        $siteUrlName                = "SPERO";
        $GLOBALS["host"]            = "localhost";
        $GLOBALS["dbuid"]           = "spero_pune";
        $GLOBALS["dbpwd"]           = "Spero@Pune@2016";
        $GLOBALS["dbname"]          = "spero_pune";  // SPERO Pune
    }
    else
    {
		$siteURL                    = "";
        $siteUrlName                = "SPERO";
        $GLOBALS["host"]            = "localhost";
        $GLOBALS["dbuid"]           = "root";
        $GLOBALS["dbpwd"]           = "";
        $GLOBALS["dbname"]          = "hospitalguru_local";
       /* $siteURL                    = "http://localhost/HHC_system/";
        $siteUrlName                = "SPERO";
        $GLOBALS["host"]            = "localhost";
        $GLOBALS["dbuid"]           = "root";
        $GLOBALS["dbpwd"]           = "";
		$GLOBALS["dbname"]          = "hospitalguru_local";*/
        //$GLOBALS["dbname"]          = "Spero_live_test";
        //$GLOBALS["dbname"]          = "hospital_spero_broadcast_live"; 
		
       /* $siteURL                    = "https://www.hospitalguru.in/";
        $siteUrlName                = "SPERO";
        $GLOBALS["host"]            = "localhost";
        $GLOBALS["dbuid"]           = "hospital_sp_pune";
        $GLOBALS["dbpwd"]           = "Spero@Pune@2016";
        $GLOBALS["dbname"]          = "hospital_spero_broadcast_live";  //SPERO Pune*/
    }
    
    $uploadURLForFiles=str_replace("classes","",dirname(__FILE__));
    $GLOBALS['siteURL']=$siteURL;
    $GLOBALS['knowledgeDocument']=$siteURL.'admin/KnowlegeDocuments/';
    $GLOBALS['EmpPrefix']="IH";
    $GLOBALS['ProfPrefix']="IHPF";
    
    $GLOBALS['FCM_FILE_URL']= $siteURL . "push_notify.php";

    $GLOBALS['ProfProfilePic']= $siteURL.'assets/profProfilePic/';
	$GLOBALS['ProfDocument']= $siteURL.'assets/profDocuments/';
	
	$GLOBALS['paymentChequeUrl']= $siteURL.'assets/Cheques/';


    $GLOBALS['timeIntervalVal']="1";
    
    $box_message_top='<br>
                        <table style=" color:#FFFFFF; width:90%; background-color:#00cfcb"><tr><td>
                        <table align="center" width="100%" cellpadding="1" cellspacing="1">
                        <tr><td style="color:#FFFFFF;padding-left:10px;	font-size:18px;	font-weight:600;padding-top:5px; padding-bottom:5px;"><img src="../images/white-logo.png"> Spero Message</td></tr>
                        <tr><td style="color:#000000; padding:8px; background-color:#FFFFFF;" align="left">';
    $box_message_bottom='</td></tr>
                        </table></td></tr>
                        </table>';
    $show_records = 10;    
    $escapeCharacters=array('?','&','/','\\','%','"',':',' ','?'); 
    $escapeCharactersReplace=array('','and',' or ','','','','','-','.Q');
    $show_records_arr=array(0=>'10',1=>'20','100','All');
    $records_all=2147483647;
    
    /*
     * 1 - Spero service
     * 2 - Enquiry
     * 3 - Feedback
     * 4 - Consultant call
     * 5 - Follow-up call
     * 6 - General inforamation
     * 7 - Job closure
     */
    function sms_send($args){
       
        $text_msg = $args['msg'];
        $mobile_no=$args['mob_no'];
        $mobile_no =  "8551995260";
        $apiKey = urlencode('DYj0ooG2pfo-150ozYrDn36WfoGBkZOum6v5J76fIk');
	
        // Message details
        $numbers = array($mobile_no);
        $sender = urlencode('TXTLCL');
        $message = rawurlencode($text_msg);
    
        $numbers = implode(',', $numbers);
        var_dump($message);die();
        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
    
        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        var_dump($response);die();
        curl_close($ch);
        // Process your response here
        echo $response;
        /*$form_url = "http://www.mgage.solutions/SendSMS/sendmsg.php?uname=BVGMEMS&pass=m2v5c2&send=BVGEMS&dest=".urlencode($mobile_no)."&msg=".urlencode($text_msg);
        $data_to_post = array();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $form_url);
        curl_setopt($curl, CURLOPT_POST, sizeof($data_to_post));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_to_post);
        $result = curl_exec($curl);
        curl_close($curl);*/
       // var_dump($result);die();
       // $asSMSReponse = explode("-", $send_dr_sms);
        //var_dump($result);die();
        /*$result = array('inc_ref_id' => $inc_id,
            'sms_usertype' => 'Dispatch',
            'sms_res' => $result[0],
            'sms_res_text' => $asSMSReponse[1] ? $asSMSReponse[1] : '',
            'sms_datetime' => $datetime);*/
       
        //$CI->inc_model->insert_sms_response($result);
}
     
          function send_curl_request($url, $parameter = '', $method = "get") {

       set_time_limit(0);

       if (function_exists("curl_init") && $url) {

           $user_agent = $_SERVER['HTTP_USER_AGENT'];

           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $url);

           if (is_array($parameter)) {

               //$query_string = http_build_query($parameter);
				$query_string = array();
				
				foreach($parameter as $key => $value){
					
					$query_string []= $key."=".$value;
				
				}
				$query_string = join("&",$query_string);
				
           } else {

               $query_string = $parameter;
           }
          
           curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookiesjar.txt');

          
         
          curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

           curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

           curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

          /// curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

           if ($method == "post") {

               curl_setopt($ch, CURLOPT_POST, 1);

               if ($parameter != "") {

                   curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
               }
           } else {

               curl_setopt($ch, CURLOPT_HTTPGET, 1);

               if ($parameter != "") {

                   $url = trim($url, "?") . "?" . urlencode($query_string);
               }
           }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           
           $document = curl_exec($ch);

          // echo "error>". curl_error($ch);
         //  print_r($document);

           curl_close($ch);

           return $document;
       }
   }
?>
