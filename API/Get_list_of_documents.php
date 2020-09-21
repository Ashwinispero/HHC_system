<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
 {
	 if(isset($_COOKIE['id']))
		
		{
				$data = json_decode(file_get_contents('php://input'));
			
	    	$Doc_status = $data->status;
	    	$service_professional_id=$_COOKIE['id'];
	 		$device_id=$_COOKIE['device_id'];
	 		
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$service_professional_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
		if($Doc_status==0)
		{
				$query= mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id ='$service_professional_id'  ");
			$Query_rows=mysql_fetch_array($query);
			$availability_status=$Query_rows['availability_status'];		
			
			$sqls= mysql_query("SELECT * FROM sp_professional_services WHERE service_professional_id = '$service_professional_id'");
					
					$row = mysql_fetch_array($sqls);
											
					$service_id=$row['service_id'];
					$service_id=(int)$service_id;
				
				$sql= mysql_query("SELECT * FROM sp_documetns_list WHERE professional_type = '$service_id'");
					
					while($rows = mysql_fetch_assoc($sql))
					{
											
							$Documents_name=$rows['Documents_name'];
							$document_list_id=$rows['document_list_id'];
							$document_list_id=(int)$document_list_id;
							$isManadatory=$rows['isManadatory'];
							$gracePeriod=$rows['gracePeriod'];
							$gracePeriod=(int)$gracePeriod;
					
					
								if($isManadatory==2)
									{
										$isManadatory_status=true;
									}	
								elseif($isManadatory==1)
									{
										$isManadatory_status=false;
									}
									$results=array('id' => $document_list_id, 'name'=>$Documents_name);
										
								
						
							
						
						$querys= mysql_query("SELECT * FROM sp_professional_documents WHERE professional_id='$service_professional_id' AND document_list_id='$document_list_id'   ");
						$num_rows = mysql_num_rows($querys);
					if($num_rows > 0)
					{		
				
						while($Query_row=mysql_fetch_assoc($querys))
						{	
			
			
					
								$Documents_id=$Query_row['Documents_id'];
								$Documents_id=(int)$Documents_id;
								$doc=$Query_row['url_path'];
								$Name=$Query_row['Name'];
								$status=$Query_row['status'];
								$isVerified=$Query_row['isVerified'];
								$document_listid=$Query_row['document_list_id'];
							   	$doc_URl=$PROF_PROF_DOCUMENTS_URL.$doc;
								
								$status=(int)$status;	
							
						if($isVerified==2)
						{
							$S_status=true;
						}	
						elseif($isVerified==1)
						{
							$S_status=false;
						}
					
									
						
						
							$user[] = array(
									'id'=>$Documents_id,	
									'type'=>$results,
									'url'=>$doc_URl, 
									'isVerified'=>$S_status, 
									'isManadatory'=>$isManadatory_status, 
									'gracePeriod'=>$gracePeriod,
									'status'=>$status
									);
					
				
						
						}
						
						
					
					}
					else
					{
						$user[] = array(
									'id'=>null,	
									'type'=>$results,
									'url'=>null, 
									'isVerified'=>false, 
									'isManadatory'=>$isManadatory_status, 
									'gracePeriod'=>$gracePeriod,
									'status'=>2
									);
										
						
					}
				}
					
				echo json_encode(array("data"=>$user,"error"=>null));
			
			
		}
								
		else
		{
			
			$query= mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id ='$service_professional_id'  ");
			$Query_rows=mysql_fetch_array($query);
			$availability_status=$Query_rows['availability_status'];		
			
			$sqls= mysql_query("SELECT * FROM sp_professional_services WHERE service_professional_id = '$service_professional_id'");
					
					$row = mysql_fetch_array($sqls);
											
					$service_id=$row['service_id'];
					$service_id=(int)$service_id;
				
				$sql= mysql_query("SELECT * FROM sp_documetns_list WHERE professional_type = '$service_id'");
					
					while($rows = mysql_fetch_assoc($sql))
					{
											
							$Documents_name=$rows['Documents_name'];
							$document_list_id=$rows['document_list_id'];
							$document_list_id=(int)$document_list_id;
							$isManadatory=$rows['isManadatory'];
							$gracePeriod=$rows['gracePeriod'];
							$gracePeriod=(int)$gracePeriod;
					
					
								if($isManadatory==2)
									{
										$isManadatory_status=true;
									}	
								elseif($isManadatory==1)
									{
										$isManadatory_status=false;
									}
							$results=array('id' => $document_list_id, 'name'=>$Documents_name);
										
								
						
								
					
	
					$query_doc= mysql_query("SELECT * FROM sp_professional_documents WHERE professional_id='$service_professional_id' AND document_list_id='$document_list_id' AND status='$Doc_status'    ");
					$num_row_doc = mysql_num_rows($query_doc);
					if($num_row_doc > 0)
					{	
				
						while($Query_row=mysql_fetch_assoc($query_doc))
						{	
			
			
					
								$Documents_id=$Query_row['Documents_id'];
								$Documents_id=(int)$Documents_id;
								$doc=$Query_row['url_path'];
								$Name=$Query_row['Name'];
								$status=$Query_row['status'];
								$isVerified=$Query_row['isVerified'];
								$document_listid=$Query_row['document_list_id'];
								
						$doc_URl=$PROF_PROF_DOCUMENTS_URL.$doc;
				
								
								$status=(int)$status;	
							
						if($isVerified==2)
						{
							$S_status=true;
						}	
						elseif($isVerified==1)
						{
							$S_status=false;
						}
					
									
						
						
							$user[] = array(
									'id'=>1,	
									'id'=>$Documents_id,	
									'type'=>$results,
									'url'=>$doc, 
									'isVerified'=>$S_status, 
									'isManadatory'=>$isManadatory_status, 
									'gracePeriod'=>$gracePeriod,
									'status'=>$status
									);
									
					
						}
						
						
					
					}
					else
					{
						
						$user[] = array(
									'id'=>$Documents_id,	
									'type'=>$results,
									'url'=>null, 
									'isVerified'=>false, 
									'isManadatory'=>$isManadatory_status, 
									'gracePeriod'=>$gracePeriod,
									'status'=>2
									);
										
						
					}
				}
					
				echo json_encode(array("data"=>$user,"error"=>null));
				
			
		}			
			
				
		 }	
		}

	 	else 
{
	http_response_code(401); 
		 

}	
	
 }
else 
{
	http_response_code(405); 
		 

}
?>