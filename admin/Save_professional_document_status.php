<?php
require_once('inc_classes.php'); 

$professional_id=$_GET['professional_id'];
$document_list_id=$_GET['document_list_id'];
$Doc_Status=$_GET['Doc_Status'];
$flag=$_GET['flag'];
if($flag==1)
{
$query=mysql_query("update sp_professional_documents set Status =".$Doc_Status." where professional_id=".$professional_id." AND document_list_id=".$document_list_id." ");
//echo "UPDATE sp_professional_documents SET Status ='$Doc_Status' where professional_id='$professional_id' AND document_list_id='$document_list_id' ";
$url = "http://45.40.136.143/~spero/Spero_HHC_API/API/push_notify.php";

				$data= array();

				$data = '{"Type":2,
				"Professional_id":$professional_id,
				"Leave_id":$week_offID,
				"Document_id":$document_list_id
				}';	
				$out = send_curl_request($url, $data, "post");
}
elseif($flag==2)
{
	$query=mysql_query("update sp_service_professionals set document_status =1 where professional_id=".$professional_id." ");
//
}

?>