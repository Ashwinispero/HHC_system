<?php
//Database Connection
//$con=mysql_connect("localhost","root","","") or die(mysql_error());
//mysql_select_db("amod",$con) or die(mysql_error());
mysql_connect("localhost","hospital_sp_pune","Spero@Pune@2016") or die(mysql_error("Not Connected"));
mysql_query("use hospital_spero_broadcast_live") or die(mysql_error("Not Connected"));
?>
