<?php
$cookie_SpNm = "SpCkStNpDt";
$cookie_value = base64_encode('Spero@cookie123*#');
setcookie($cookie_SpNm, $cookie_value, time() + (60*60*24*100), "/"); // 86400 = 1 day
//setcookie($cookie_SpNm, '', time() - (60*60*24*100), "/");
?>
<html>
<body>

<?php
if(!isset($_COOKIE[$cookie_SpNm])) {
    echo "Fail";
} else {
    //echo "Cookie '" . $cookie_SpNm . "' is set!<br>";
    //echo "Value is: " . $_COOKIE[$cookie_SpNm];
    echo 'success';
}
?>