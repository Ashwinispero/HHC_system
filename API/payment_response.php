<?php
require_once 'classes/eventClass.php';
//require_once 'classes/commonClass.php';
$eventClass=new eventClass();
 require_once('config.php');

    
if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
		    if(isset($_COOKIE['id']))
			 {
			     
				 
                        date_default_timezone_set("Asia/Calcutta");
 
						$added_date=date('Y-m-d H:i:s');
						$data = json_decode(file_get_contents('php://input'));
						$TXNID = $data->TXNID;
						$BANKTXNID = $data->BANKTXNID;
						$ORDERID = $data->ORDERID;
						$TXNAMOUNT = $data->TXNAMOUNT;
						$STATUS = $data->STATUS;
						$TXNTYPE = $data->TXNTYPE;
						$GATEWAYNAME = $data->GATEWAYNAME;
						$RESPCODE = $data->RESPCODE;
						$RESPMSG = $data->RESPMSG;	
						$BANKNAME = $data->BANKNAME;
						$MID = $data->MID;
						$PAYMENTMODE = $data->PAYMENTMODE;
						$REFUNDAMT = $data->REFUNDAMT;	
						$TXNDATE = $data->TXNDATE;	
						
						$professional_vender_id=$_COOKIE['id'];

						$arg['transaction_id']=$TXNID;
						$arg['bank_transaction_id']=$BANKTXNID;
						$arg['order_id']=$ORDERID;
						$arg['transcation_amount']=$TXNAMOUNT;
						$arg['status']=$STATUS;
						$arg['transcation_type']=$TXNTYPE;						
						$arg['gateway_name']=$GATEWAYNAME;						
						$arg['response_code']=$RESPCODE;
						$arg['response_msg']=$RESPMSG;
						$arg['bank_name']=$BANKNAME;
						
						$arg['MID']=$MID;
						$arg['payment_mode']=$PAYMENTMODE;
						$arg['refund_amount']=$REFUNDAMT;
						$arg['transcation_date']=$TXNDATE;
						
						$InsertRecord=$eventClass->API_Payment_response($arg);
						
$query2=mysql_query("SELECT SUM(amount) as amount FROM sp_payments_received_by_professional  where professional_vender_id='$professional_vender_id' AND OTP_verifivation=1 AND Payment_type=1 AND Payment_mode=1 AND payment_status=1 ");
										$query2 = mysql_fetch_array($query2);
										$payment_amount=$query2['amount'];
										$payment_amount=(int)$payment_amount;
										
									$Amount_with_me= $payment_amount-$TXNAMOUNT;

if($STATUS=="TXN_SUCCESS")
{
     $Loc_query=mysql_query("UPDATE sp_bank_details SET Amount_with_me='$Amount_with_me' WHERE  Professional_id='$professional_vender_id' ");
     
     $sql = mysql_query("UPDATE sp_payments_received_by_professional SET  payment_status=2 WHERE Transaction_ID='$ORDERID'  ");
     $sql_pay = mysql_query("UPDATE sp_payment_transaction SET  pay_status=2 WHERE orderId='$ORDERID'  ");
      $out=array("data"=>null,"error"=>null);
 
echo json_encode($out);
}
 else
 {
      $out=array("data"=>null,"error"=>null);
 
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
