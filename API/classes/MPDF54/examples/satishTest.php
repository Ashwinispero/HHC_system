<?php
ini_set('max_execution_time',1000);



define('_MPDF_PATH','../');
include("../mpdf.php");

//define('_MPDF_PATH','classes/MPDF54/');
//include("classes/MPDF54/mpdf.php");

$html =file_get_contents('http://www.persaf.com/PSFOnline/index.htm');;

$mpdf = new mPDF('utf-8','','','',5,5,5,5,5,5,'P');//A4-L - second paramiter $mpdf=new mPDF('c'); 
$mpdf->default_lineheight_correction = 1;
$mpdf->autoPageBreak = true;
$mpdf->AddPage();
$mpdf->WriteHTML($html);            
$mpdf->Output();
?>