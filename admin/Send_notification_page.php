<?php
require_once('inc_classes.php');
include "classes/commonClass.php";
$commonClass= new commonClass();

$Prof_service_id=$_GET['Prof_service_id'];
$msg=$_GET['msg'];
$type=$_GET['type'];

$name  = "Spero Healthcare Innovations Pvt Ltd"	;		
$subject  = "Spero Healthcare Innovations Pvt Ltd";			
$email ="noreply@sperocloud.com";			
//$body ="testing mail";			
	$body ="<table cellspacing='5' cellpadding='5' width='90%'>
						<tr><td>Dear Ashwini,</td></tr>
						<tr>
                        <td>".$msg."</td>
                        </tr>
					    <tr><td>For queries please contact 7620400100  </td></tr>
						<tr><td>Regards,</td></tr>  
						<tr><td ><a>http://sperohealthcare.in</a></td></tr>
						<table>";
$to = "Ashwinik.speroinfosystems@gmail.com";

$headers  = 'MIME-Version: 1.0' . "\r\n";			
$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";			
$headers .= 'From: '.$name.' < '.$email.' >'."\r\n";			
$headers .= 'Reply-To: '.$name.' < '.$email.' >'."\r\n";									
mail($to,$subject,$body,$headers);

if($type==1)
{
	$Notification = mysql_query("SELECT * FROM sp_professional_services where service_id='$Prof_service_id' and status=1");
	$row_count = mysql_num_rows($Notification);
	if($row_count > 0)
	{
		while ($Notification_rows = mysql_fetch_array($Notification))
		{		
			$service_professional_id=$Notification_rows['service_professional_id'];
			$professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id' AND status='2'");
			$professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
			$Professionalname=$professional_name_abc['name'];
			$title=$professional_name_abc['title'];
			$first_name=$professional_name_abc['first_name'];
			$middle_name=$professional_name_abc['middle_name'];
			$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
			$email_id=$professional_name_abc['email_id'];
			$mobile_no=$professional_name_abc['mobile_no'];
			/*
			
                $name  = "Spero Healthcare Innovations Pvt Ltd"	;		
                $subject  = "Spero Healthcare Innovations Pvt Ltd";			
                $email ="noreply@sperocloud.com";			
                //$body ="testing mail";			
                	$body ="<table cellspacing='5' cellpadding='5' width='90%'>
                						<tr><td>Respected ".$title.".".$Professionalname.",</td></tr>
                						<tr>
                                        <td>".$msg."</td>
                						</tr>
                						<tr><td>Regards,</td></tr>  
                						<tr><td ><a>http://sperohealthcare.in</a></td></tr>
                						<table>";
                $to = "Ashwinik.speroinfosystems@gmail.com";
                //$to=$email_id;
                $headers  = 'MIME-Version: 1.0' . "\r\n";			
                $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";			
                $headers .= 'From: '.$name.' < '.$email.' >'."\r\n";			
                $headers .= 'Reply-To: '.$name.' < '.$email.' >'."\r\n";									
                mail($to,$subject,$body,$headers);
			*/
		}
	}
}
else if($type==2)
{
    $phone_no=8551995260;
	$Name='Ashwini';
	$txtMsg1 .= "Dear".$Name;
	$txtMsg1 .= ",".$msg;
	//$txtMsg1 .= ",".$msg;
    	$txtMsg1 .= ",For queries please contact 7620400100";
	$txtMsg1 .= "Regards,";
	$txtMsg1 .= "Spero Healthcare Innovations Pvt Ltd.";
	$args = array(
		'msg' => $txtMsg1,
		'mob_no' => $phone_no
		);
	$sms_data =$commonClass->sms_send($args);	
/*
    $form_url = "http://api.unicel.in/SendSMS/sendmsg.php";                   
    $data_to_post = array();
    $data_to_post['uname'] = 'SperocHL';
    $data_to_post['pass'] = 'SpeRo@12';//s1M$t~I)';
    $data_to_post['send'] = 'speroc';
    $data_to_post['dest'] = $phone_no; 
    $data_to_post['msg'] = $txtMsg1;

    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL, $form_url);
    curl_setopt($curl,CURLOPT_POST, sizeof($data_to_post));
    curl_setopt($curl,CURLOPT_POSTFIELDS, $data_to_post);
    $result = curl_exec($curl);
    curl_close($curl);
    */
	$Notification = mysql_query("SELECT * FROM sp_professional_services where service_id='$Prof_service_id' and status=1");
	$row_count = mysql_num_rows($Notification);
	if($row_count > 0)
	{
		while ($Notification_rows = mysql_fetch_array($Notification))
		{		
			$service_professional_id=$Notification_rows['service_professional_id'];
			$professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id' AND status='2'");
			$professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
			$name=$professional_name_abc['name'];
			$title=$professional_name_abc['title'];
			$first_name=$professional_name_abc['first_name'];
			$middle_name=$professional_name_abc['middle_name'];
			$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
			
			$email_id=$professional_name_abc['email_id'];
			$mobile_no=$professional_name_abc['mobile_no'];
			
		}
	}
}
else if($type==3)
{
	$Notification = mysql_query("SELECT * FROM sp_professional_services where service_id='$Prof_service_id' and status=1");
	$row_count = mysql_num_rows($Notification);
	if($row_count > 0)
	{
		while ($Notification_rows = mysql_fetch_array($Notification))
		{		
			$service_professional_id=$Notification_rows['service_professional_id'];
			$professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id'");
			$professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
			$name=$professional_name_abc['name'];
			$title=$professional_name_abc['title'];
			$first_name=$professional_name_abc['first_name'];
			$middle_name=$professional_name_abc['middle_name'];
			$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name;
			
			$email_id=$professional_name_abc['email_id'];
			$mobile_no=$professional_name_abc['mobile_no'];
		
		$url = "http://45.40.136.143/~spero/Spero_HHC_API/API/push_notify.php";

				$data= array();

				$data = '{"Type":5,
				"Msg":$msg,
				"Professional_id":$service_professional_id
				}';	
				$out = send_curl_request($url, $data, "post");
			//	echo $out;
			
		}
	}
}
?>