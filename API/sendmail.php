<?php
  require_once('config.php');
require_once "PHPMailer/class.phpmailer.php";

$mail = new PHPMailer;

                                  

$mail->From = "sameerpabale7@gmail.com";
$mail->FromName = "Sameer Pabale";

$mail->addAddress("sameer.speroinfosystems@gmail.com", "Recepient Name");

//$mail->isHTML(true);

$mail->Subject = "Test Mail";
$mail->Body = "Mail body in App";
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
   echo json_encode(array("data"=>null,"error"=>null));
}
 ?>