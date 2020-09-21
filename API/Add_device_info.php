<?php
require_once 'classes/professionalsClass.php';

$professionalsClass=new professionalsClass();

   

include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
 {
	$data = json_decode(file_get_contents('php://input'));
	
    date_default_timezone_set("Asia/Calcutta");
	$DeviceId = $data->DeviceId;
	$OSVersion = $data->OSVersion;
	$OSName = $data->OSName;		
	$DevicePlatform = $data->DevicePlatform;
	$AppVersion = $data->AppVersion;
	$DeviceTimezone = $data->DeviceTimezone;
	$DeviceCurrentTimestamp= $data->DeviceCurrentTimestamp;
	$Token = $data->Token;
	$ModelName = $data->ModelName;
	
	$sql_query= mysql_query("SELECT * FROM sp_device_version_info WHERE osName = '$OSName' AND status=1 ");
								$rows = mysql_fetch_array($sql_query);
								$id=$rows['id'];
								//$devicePlatform=$rows['devicePlatform'];
								$currentVersion=$rows['osVersion'];
								$locationPath=$rows['location_path'];
								$lastCompulsoryVersion=$rows['compulsory_version'];
								//$previousVersion=$rows['previousVersion'];
	
	if($DeviceId==0)
	{
		
		if($OSVersion == '' || $OSName == '' || $DevicePlatform == '' || $AppVersion == '' || $DeviceTimezone == '' || $DeviceCurrentTimestamp == '' || $ModelName == '')
		{
			http_response_code(400);
			
		}
		else
		{	
				$arg = array();
				
					$added_date=date('Y-m-d H:i:s');
					$arg['OSVersion']=$OSVersion;
					$arg['OSName']=$OSName;		
					$arg['DevicePlatform']=$DevicePlatform;
					$arg['AppVersion']=$AppVersion ;
					$arg['DeviceTimezone']=$DeviceTimezone ;
					$arg['DeviceCurrentTimestamp']=$DeviceCurrentTimestamp;
					$arg['Token']=$Token;
					$arg['ModelName']=$ModelName;
					$arg['added_date']=$added_date;
					$InsertRecord=$professionalsClass->API_AddDevice_info($arg);
					
				
					$sqls= mysql_query("SELECT MAX(device_id) As device_id FROM sp_professional_device_info");
					$sqls_row = mysql_fetch_assoc($sqls);
					$device_ids = $sqls_row['device_id'];
					$device_ids=(int)$device_ids;
					 
					
	
				
				$result=array("data"=>array("deviceId"=>$device_ids,"versionInfo"=>array("devicePlatform"=>"$DevicePlatform","currentVersion"=>"$currentVersion","locationPath"=>"$locationPath","lastCompulsoryVersion"=>"$lastCompulsoryVersion")),"error"=>null);
				
				echo json_encode($result);
		
		}
	}
	else
	{
		if($OSVersion == '' || $OSName == '' || $DevicePlatform == '' || $AppVersion == '' || $DeviceTimezone == '' || $DeviceCurrentTimestamp == '' || $Token == '' || $ModelName == '')
		{
			http_response_code(400);
			
		}
		else
		{
					$sql= mysql_query("SELECT * FROM sp_professional_device_info  WHERE device_id = '$DeviceId' ");
					$row_count = mysql_num_rows($sql);
			
					if ($row_count > 0)
					{
						$query_update=mysql_query("UPDATE  sp_professional_device_info SET OSVersion='$OSVersion',OSName='$OSName',DevicePlatform='$DevicePlatform',AppVersion='$AppVersion',DeviceTimezone='$DeviceTimezone',DeviceCurrentTimestamp='$DeviceCurrentTimestamp',Token='$Token',ModelName='$ModelName' WHERE device_id = '$DeviceId' ");
						if($query_update)
						{
							
								
							$result=array("data"=>array("deviceId"=>$DeviceId,"versionInfo"=>array("devicePlatform"=>"$DevicePlatform","currentVersion"=>"$currentVersion","locationPath"=>"$locationPath","lastCompulsoryVersion"=>"$lastCompulsoryVersion")),"error"=>null);
							
							echo json_encode($result);
						}
						
					}
					else
						{
							echo json_encode(array("data"=>null,"error"=>array("message"=>"ID not exist")));
						}
					
		}
	}
	 			
 }

else 
    {
		http_response_code(405); 
		 
	   
    }
?>