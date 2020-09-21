<?php
 

require_once 'classes/locationsClass.php';
$locationsClass=new locationsClass();  
include('config.php');
         
		 if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
			    date_default_timezone_set("Asia/Calcutta");
							$data = json_decode(file_get_contents('php://input'));
							$professional_service_id=$_COOKIE['id'];
							$professional_id = $data->id;
							$role = $data->role;
							$loggedFrom = $data->loggedFrom;
							$logStatement = $data->logStatement;
							
						
							 $args['Role_id']=$role;
							 $args['professional_id']=$professional_id;
							 $args['logged_from']=$loggedFrom;
							$args['logStatement']=$logStatement;
							
							 
							 if($role == ''  || $loggedFrom == '' || $logStatement == ''|| $professional_id == '')	 
										{
								
											http_response_code(400);
						
										}
							
							else
							{
				 
							
										
										
										
											$InsertOtherDtlsRecord=$locationsClass->APILogevent($args);
									
										echo json_encode(array("data"=>null,"error"=>null));
									
								
								
							
							  
							}		 
			 
			  
		}
		
		
		else
			{
			   http_response_code(405);
		     }
		
?>