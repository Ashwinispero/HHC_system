<?php
require_once 'classes/eventClass.php';
//require_once 'classes/commonClass.php';
$eventClass=new eventClass();
 require_once('config.php');

require_once("./lib/encdec_paytm.php");

 
    
if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
		    if(isset($_COOKIE['id']))
			 {
			     	$device_id=$_COOKIE['device_id'];
			     	date_default_timezone_set("Asia/Calcutta");
			     	 $added_date=date('Y-m-d H:i:s');
                    $data = json_decode(file_get_contents('php://input'));
                    $mId = $data->mId;
                    $channelId = $data->channelId;
                    $custId = $data->custId;
                    $mobileNo = $data->mobileNo;
                    $email = $data->email;
                    $txnAmount = $data->txnAmount;
                    $website = $data->website;
                    $industryTypeId = $data->industryTypeId;
                    $callbackUrl = $data->callbackUrl;	
                    $eventList = $data->eventList;	
                    $professional_vender_id=$_COOKIE['id'];
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_vender_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
			     //define("merchantMid", "ZtMRFP63725883705572");
                 //define("merchantKey", "SxhUtBVxwsLlQtQo");
                 
                  define("merchantMid", "nNWgFz64055580237341");
                 define("merchantKey", "nTyAARhx&_HF6ZDp");

	                    $arg['mId']=$mId;
						$arg['channelId']=$channelId;
						$arg['professional_id']=$custId;
						$arg['mobileNo']=$mobileNo;
						$arg['email']=$email;
						$arg['transaction_Amount']=$txnAmount;						
						$arg['website']=$website;						
						$arg['industryTypeId']=$industryTypeId;
						$arg['callbackUrl']=$callbackUrl;
						$arg['work_experience']=$professional_vender_id;
						$arg['added_date']=$added_date;	
					 	$InsertRecord=$eventClass->API_Initialize_Transaction($arg);
					 	$ORDER_ID=mysql_insert_id(); 
								
                        define("orderId", "$ORDER_ID");
                        define("channelId", "$channelId");
                        define("custId", "$custId");
                        define("mobileNo", "$mobileNo");
                        define("email", "$email");
                        define("txnAmount", "$txnAmount");
                        define("website", "$website");
                        // This is the staging value. Production value is available in your dashboard
                        define("industryTypeId", "$industryTypeId");
                        // This is the staging value. Production value is available in your dashboard
                        //define("callbackUrl", "https://securegw-stage.paytm.in/theia/paytmCallback?ORDER_ID=$ORDER_ID");
                        //Production call back url
                        define("callbackUrl", "https://securegw.paytm.in/theia/paytmCallback?ORDER_ID=$ORDER_ID");
                      
                        $paytmParams = array();
                        $paytmParams["MID"] = merchantMid;
                        $paytmParams["ORDER_ID"] = orderId;
                        $paytmParams["CUST_ID"] = custId;
                        $paytmParams["MOBILE_NO"] = mobileNo;
                        $paytmParams["EMAIL"] = email;
                        $paytmParams["CHANNEL_ID"] = channelId;
                        $paytmParams["TXN_AMOUNT"] = txnAmount;
                        $paytmParams["WEBSITE"] = website;
                        $paytmParams["INDUSTRY_TYPE_ID"] = industryTypeId;
                        $paytmParams["CALLBACK_URL"] = callbackUrl;
                       
                        $paytmChecksum = getChecksumFromArray($paytmParams, merchantKey);
                    
                    /*foreach($_POST as $key=>$value)
                    {  
                      $pos = strpos($value, $findme);
                      $pospipe = strpos($value, $findmepipe);
                      if ($pos === false || $pospipe === false) 
                        {
                            $paramList[$key] = $value;
                        }
                    }*/
                     
                    //Here checksum string will return by getChecksumFromArray() function.
                    //$checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
                       $Loc_query=mysql_query("UPDATE sp_payment_transaction SET checksumHash='$paytmChecksum' WHERE  orderId='$ORDER_ID' ");
                    
                     $data=array("ORDER_ID" => $ORDER_ID,"CHECKSUMHASH" => $paytmChecksum);
                    
                     $out=array("data"=>$data,"error"=>null);
                      foreach($eventList as $key=>$valServices)
                    		{
                    			
                    			 $paymentId = mysql_real_escape_string($valServices->paymentId);
                    			 $amount = mysql_real_escape_string($valServices->amount);
                    			 
                    			 $sql = mysql_query("UPDATE sp_payments_received_by_professional SET  Transaction_ID='$ORDER_ID' WHERE payment_id ='$paymentId'  ");
                    									
                    			 
                    		
                    				
                    		}
                        echo json_encode($out);
 
		}
			 }
		
		else			 
			{
				http_response_code(401); 
				
				
			}
		}
			else
		{
			http_response_code(405); 
			
			
		}
?>
