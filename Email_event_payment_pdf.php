<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/Exception.php';
require './PHPMailer/PHPMailer.php';
require './PHPMailer/SMTP.php';

$mail = new PHPMailer(true);  

require_once 'inc_classes.php';
	   require_once 'classes/eventClass.php';
       $eventClass=new eventClass();
       include "classes/commonClass.php";
$commonClass= new commonClass();
	if($_GET['export'])
    {
        $folder="eventsPDF/";
        $tmpfname=trim($_GET['file']);
        if(file_exists($folder.$tmpfname))
        {	
            header("Cache-Control: ");// leave blank to avoid IE errors
            header("Pragma: ");// leave blank to avoid IE errors
            header('Content-Disposition: attachment; filename="'.$tmpfname.'"');
            header('Content-Type: application/xls');
            header('Content-Length: ' . filesize($folder.$tmpfname));
            readfile($folder.$tmpfname);
            unlink($folder.$tmpfname);
        }
    }
    else
    {
        //$data='';
        $eventid=$_POST['eventid'];
		//echo $payment_id;
		//$EventId=$EventDtls['event_code'];
		$fetch_event_id = mysql_query("SELECT * FROM sp_events WHERE event_id='$eventid'");
    	$get_event_number = mysql_fetch_array($fetch_event_id);
	    $event_code = $get_event_number['event_code'];
	    $patient_id = $get_event_number['patient_id'];
	    $bill_no_ref_no = $get_event_number['bill_no_ref_no'];
	    
	    
	    
	    $patient_details = mysql_query("SELECT * FROM sp_patients WHERE patient_id='$patient_id'");
	    $name_address = mysql_fetch_array($patient_details);
	    $email=$name_address['email_id'];
           $first_name=$name_address['first_name'];
       	$datais = $_POST['html'];
        $folder="eventsPDF/";            
        $tmpfname ="Email_PaymentSummary_".$bill_no_ref_no."_".$event_code."_".date("m-d-Y His").'.pdf';
        print_r($tmpfname);
        
        //$data="<html><meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\"><link href=\"css/bootstrap.css\" rel=\"stylesheet\" type=\"text/css\" /><link href=\"css/style.css\" rel=\"stylesheet\" type=\"text/css\" /><body style='color: #666666;font-size: 11px;text-decoration: none;'>";
         $html=$datais;
        //$data.='</body></html>';
        ini_set('max_execution_time',1000);
        define('_MPDF_PATH','classes/MPDF54/');
        include("classes/MPDF54/mpdf.php");
        //$html = $data; // file_get_contents('http://decizonsoft.com/persaf/health-records.html');;
        
        $mpdf = new mPDF('utf-8','','','',5,5,5,5,5,5,'P');//A4-L - second paramiter $mpdf=new mPDF('c'); 
        $mpdf->default_lineheight_correction = 2;
        $mpdf->autoPageBreak = true;
        $mpdf->AddPage();
      //  if(isset($_POST['type']) && $_POST['type'] != '' ){
            $javascript = 'this.print();';
            $mpdf->SetJS($javascript);     
       // }         
        $mpdf->WriteHTML($html);  
        $mpdf->Output($folder.$tmpfname,'F');
        
        $name  = "Spero Healthcare Innovations Pvt Ltd"	;		
						$subject  = "Spero - Payment Receipt For ".$event_code." ";			
						$email =$email;			
						$body ="<table cellspacing='5' cellpadding='5' width='90%'>
						<tr><td>Dear ".$first_name.",</td></tr>
						<tr>
                        <td>Please find the attachement of payment Receipt for Event ID ".$eventid.".</td>
                    </tr>
                    <tr><td>Regards,</td></tr>  
                    <tr><td>Spero Healthcare Innovations Pvt Ltd</td></tr>  
                      <tr><td>7620400100</td></tr>  
                    <tr><td ><a href='".$siteURL."'>http://sperohealthcare.in</a></td></tr>
						<table>";			
									
	$mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 's45-40-136-143.secureserver.net';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'noreply@sperocloud.com';                 // SMTP username
    $mail->Password = 'p-UP?4KhOd)#';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('noreply@sperocloud.com', 'Spero Healthcare Innovations Pvt Ltd');
    $mail->addAddress($email, $first_name);     // Add a recipient
    //$mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('noreply@sperocloud.com');
 //  $mail->addCC('Ashwinik.speroinfosystems@gmail.com');
   $mail->addCC('kalpana@sperohealthcare.in');
  //$mail->addBCC('info@sperohealthcare.in');


    //Attachments
    $mail->addAttachment('./eventsPDF/'.$tmpfname);         // Add attachments


    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
   // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();	
    
    
    
		
						$query=mysql_query("SELECT * FROM sp_events where event_id='".$eventid."'") or die(mysql_error());
						$row = mysql_fetch_array($query) or die(mysql_error());
						$patient_id=$row['patient_id'];
                        $Total_amt=$row['finalcost'];
                        $event_code=$row['event_code'];
						
						$patient_detail=mysql_query("SELECT * FROM sp_patients where patient_id='".$patient_id."'") or die(mysql_error());
						$row_patient_detail= mysql_fetch_array($patient_detail) or die(mysql_error());
						$first_name=$row_patient_detail['first_name'];
						$name=$row_patient_detail['name'];
						$query_requirement_id= mysql_query("SELECT * FROM sp_event_requirements  where event_id=".$eventid."");
						$Service_name1=array();
						while($row_query_requirement_id=mysql_fetch_array($query_requirement_id))
						{
							
							$service_id=$row_query_requirement_id['service_id'];
							
							$Service_name=mysql_query("SELECT * FROM sp_services where service_id='".$service_id."'") or die(mysql_error());
							$row_Service_name= mysql_fetch_array($Service_name) or die(mysql_error());
							$service_title=$row_Service_name['service_title'];
							 
							$Service_name1[] =$service_title;
							
						}
						$services_name = implode(',',$Service_name1);
						
						$Payment_amt= mysql_query("SELECT SUM(amount) as amount,type,Max(payment_id) as payment_id FROM sp_payments where event_id=".$eventid." and status=1");
						$Payment_detail_row= mysql_fetch_array($Payment_amt) or die(mysql_error());
						$Paid_amount=$Payment_detail_row['amount'];
						$type=$Payment_detail_row['type'];
						$balance_amt=$Total_amt-$Paid_amount;
						$payment_id=$Payment_detail_row['payment_id'];
						
						$payment_detail_data=mysql_query("SELECT * FROM sp_payments where payment_id='$payment_id'") or die(mysql_error());
						$payment_details_row= mysql_fetch_array($payment_detail_data) or die(mysql_error());
						$Transaction_Type=$payment_details_row['Transaction_Type'];
						$amt=$payment_details_row['amount'];
						$type=$payment_details_row['type'];
						
						$Added_date=date('Y-m-d H:i:s');
						$profmob1 =$row_patient_detail['mobile_no'];
							//$profmob1 =9623499965;
						
						$txtMsg1 .= "Dear " .$first_name ." ".$name ;
						$txtMsg1 .= ", Rs.".$amt;
						$txtMsg1 .= "/- received by ".$type ;
						$txtMsg1 .= " on".$Added_date ;
						$txtMsg1 .= " to Spero for ".$services_name;
						$txtMsg1 .= ". Total Amount ".$Total_amt;
						$txtMsg1 .= ", Balance Amount ".$balance_amt;
                        $txtMsg1 .= ". Thank you. For queries please contact 7620400100";
                        
                       
						
					/*	$args = array(
                            'event_code'=> $event_code,
                            'msg' => $txtMsg1,
                            'mob_no' => $profmob1
                            );
                        $sms_data =$commonClass->sms_send($args);*/
						/*
                        $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";
                            $data_to_post = array();
                            $data_to_post['uname'] = 'SperocHL';
                            $data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
                            $data_to_post['send'] = 'speroc';
                            $data_to_post['dest'] = $profmob1; 
                            $data_to_post['msg'] = $txtMsg1;
                        $curl = curl_init();
                        curl_setopt($curl,CURLOPT_URL, $form_url);
                        curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
                        curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
						$result = curl_exec($curl);
                        curl_close($curl);
		
		*/
   

	}
?>
