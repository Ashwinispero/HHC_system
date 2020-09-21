<?php
function isValidURLPHP($parm1)
{
        return preg_match('/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/', $parm1);
}
function isValidEmailPHP($email)
{
        //return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
        return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email);
}
function isValidNumberPHP($number)
{
        if(is_numeric($number))	return 1;
        else return 0;
}
function isValidDatePHP($date)
{
        $date_array=explode("-",$date);
        return checkdate  ( $date_array[1],$date_array[2],$date_array[0]  );
}
function randomPass($length=10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm')
{//
    for($i = 0; $i < $length; $i++)
    {
        $pwd .= $chrs{mt_rand(0, strlen($chrs)-1)};
    }
    return $pwd;
}
function All_useremail($toEmailid,$message,$subject)
{
	
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From:no-reply@hindavi.in'. "\n";
    if(mail($toEmailid, $subject, $message, $headers))
    {
        return 1;
    }
    else
    {
       return 0;
    }
}
function generateRandomString($length = 10) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) 
{
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    
    if (All_useremail_attachment($mailto, $message, $subject, $filename)) {
        return true;
    } else {
        return false;
    }
}
function All_useremail_attachment($toEmailid,$message,$subject,$my_file)
{
    require_once('PHPMailer/class.phpmailer.php');
    $mail             = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    //$mail->Host       = "ssl://smtp.gmail.com"; // SMTP server
    $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 465;                  // set the SMTP port for the GMAIL server 25 
    $mail->Username   = "prashant.dabhole@hindavi.in";  // GMAIL username
    $mail->Password   = "Tuljabhavani";            // GMAIL password
    
    $mail->SetFrom('info@sperohealthcare.in', 'SPERO');
    $mail->AddReplyTo('info@sperohealthcare.in', 'SPERO');
    $mail->Subject    = $subject;
    //$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
    $mail->MsgHTML($message);
    echo $message;
    $mail->AddAttachment("eventsPDF/".$my_file);      // attachment
    $emails=explode(",",$toEmailid);
    $emails1=0;
    if(count($emails)==1)
    {
        if($mail->ValidateAddress($toEmailid))
        {
            $mail->AddAddress(trim($toEmailid));
            $emails1=1;
        }
    }
    else
    {
        for($i=0;$i<count($emails);$i++)
        {
           // echo $emails[$i];
            if($emails[$i] && $mail->ValidateAddress($emails[$i]))
            {
                
                 $mail->AddAddress(trim($emails[$i]));
                $emails1=1;
            }
        }
    }
    //$mail->AddAttachment($my_path."/".$my_file);      // attachment
    //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
    
    if($emails1 && !$mail->Send()) 
         return 1;
    else
        return 0;
}
function getAjaxPagination($count)
{
      $paginationCount= floor($count / PAGE_PER_NO);
      $paginationModCount= $count % PAGE_PER_NO;
      if(!empty($paginationModCount)){
               $paginationCount++;
      }
      return $paginationCount;
}
function distance($lat1, $lon1, $lat2, $lon2, $unit) 
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
    return ($miles * 1.609344);
    } else if ($unit == "N") {
    return ($miles * 0.8684);
    } else {
    return $miles;
    }
}

function swapValues2( $array, $dex, $dex2) 
{
    list($array[$dex],$array[$dex2]) = array($array[$dex2], $array[$dex]);
    return $array;
}

function bubbleSort($array)
{
    for($out=0,$size =count($array);$out<$size -1 ;$out++ )
    {
        for($in=$out+1;$in<$size;$in++) 
        {
            if(strtotime($array[$out]) > strtotime($array[$in])) 
            {
                $array = swapValues2($array, $out, $in);
            }
        }
    }
    return $array;  
}

function sendSMS($destno,$msg)
{
    
    $t = 't';
    $password = "s1M$$t~I)";
    //$url = "http://api.unicel.in/SendSMS/sendmsg.php?uname=SperocHL&pass=".$password."&send=speroc&dest=8600334476&msg=Testing";
    $url = "http://api.unicel.in/SendSMS/sendmsg.php?uname=SperocHL&pass=".$password."&send=speroc&dest=".$destno."&msg=".$msg."&udhi=1&dcs=8";
    header('location: '.$url);
    return 1;
}
function Generate_Number($prefix,$recordId)
{
    $GenerateNo="";
    
    if(!empty($prefix))
    {
        $first_place = $prefix."00000";
        $second_place= $prefix."0000";
        $third_place = $prefix."000";
        $fourth_place= $prefix."00";
        $fifth_place = $prefix."0";
        $sixth_place = $prefix;
        
        if($recordId==0)
        {
            $result = "1";
            $GenerateNo = $first_place.''.$result;
        }
        else if($recordId>=1 && $recordId<9)
        {
            $result = $recordId+1;
            $GenerateNo = $first_place.''.$result;
        }
        else if($recordId>=9 && $recordId<99)
        {
            $result = $recordId +1;
            $GenerateNo = $second_place.''.$result; 
        }
        else if($recordId>=99 && $recordId<999)
        {
            $result = $recordId +1;
            $GenerateNo = $third_place.''.$result; 
        }
        else if($recordId>=999 && $recordId<9999)
        {
            $result = $recordId +1;
            $GenerateNo = $fourth_place.''.$result; 
        }
        else if($recordId>=9999 && $recordId<99999)
        {
            $result = $recordId +1;
            $GenerateNo = $fifth_place.''.$result; 
        }
        else if($recordId>=99999 && $recordId<999999)
        {
            $result = $recordId +1;
            $GenerateNo = $sixth_place.''.$result; 
        }
        
        return $GenerateNo;  
    }
    else 
    {
        return 0;
    }
}
function getDatesFromRange($start, $end)
    {
        $dates = array($start);
        while(end($dates) < $end){
            $dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
        }
        return $dates;
    }
?>
