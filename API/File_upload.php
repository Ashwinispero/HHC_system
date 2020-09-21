<?php

require_once 'classes/professionalsClass.php';
$professionalsClass=new professionalsClass();

require_once('config.php');
header("Accept:application/json ; Content-Type: multipart/form-data; boundary=-------------------------acebdf13572468");

 

 if($_SERVER['REQUEST_METHOD']=='POST')
{ 
 
	if(isset($_COOKIE['id']))
	 
	 {
		
			$service_professional_id=$_COOKIE['id'];
			$uniqid=uniqid();
			$errors = array();
			$headers = array();
			$device_id=$_COOKIE['device_id'];
		    date_default_timezone_set("Asia/Calcutta");
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$service_professional_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{			
				foreach ($_SERVER as $key => $value) {
				if (strpos($key, 'HTTP_') === 0)
					{
					$headers[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
			   
				   } 
					
				}
			$types=$headers['Documenttype'];

		 if($types==0)
		   {
					
		 
							
					      $file_name = $_FILES['filename']['name'];
						  $file_size = $_FILES['filename']['size'];
						  $file_tmp = $_FILES['filename']['tmp_name'];
						  $file_type = $_FILES['filename']['type'];
						  $tmp = explode('.', $file_name);
                                $file_ext = end($tmp);
							  
							  $expensions= array("jpeg","jpg","png");
							 
							  
							 if(in_array($file_ext,$expensions)=== false)
							  {
								http_response_code(400);
							  }
							  
							 
							  
							 else if(empty($errors)==true) 
							  {
								  $extension=(".$file_ext");
								  
								 move_uploaded_file($file_tmp,"../assets/profProfilePic/".$service_professional_id.$uniqid.$extension);
								 $file=$service_professional_id.$uniqid.$extension;
								  //$file="http://127.0.0.1/Professional_API/Documents/profile_pic/".$service_professional_id.$uniqid.$extension;
									
									$sql = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$service_professional_id'");
		
									$row_count = mysql_num_rows($sql);
									if ($row_count > 0)
									{										
									
									$sqls = mysql_query("UPDATE sp_service_professionals SET  Profile_pic  =  '$file' WHERE service_professional_id ='$service_professional_id'");
									if(mysql_affected_rows())
								{
									 $Prof_query= mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = '$service_professional_id'  ");
								$row_count = mysql_num_rows($Prof_query);
								if ($row_count > 0)
								{
								 $row = mysql_fetch_array($Prof_query);
							   	 
								 $Profile_pic=$row['Profile_pic'];
								 $Profile_pic_url=$PROF_PROFILE_PIC_URL.$Profile_pic;
								
								echo json_encode(array("data"=>array("id"=>0,"url"=>$Profile_pic_url),"error"=>null));	
								}
								}
									
									}
									
							  }
							  else{
								  http_response_code(400);
								 
							  }
		   }

			
		   else
			   
			 {
				 $sql= mysql_query("SELECT * FROM sp_documetns_list WHERE document_list_id = '$types'");
				 $rows = mysql_fetch_array($sql);
				 $Documents_name=$rows['Documents_name'];
				 $document_list_id=$rows['document_list_id'];
						
						  $file_name = $_FILES['filename']['name'];
						  $file_size = $_FILES['filename']['size'];
						  $file_tmp = $_FILES['filename']['tmp_name'];
						  $file_type = $_FILES['filename']['type'];
						 $tmp = explode('.', $file_name);
                         $file_ext = end($tmp);
						  
						  $expensions= array("jpeg","jpg","png","pdf","JPG","JPEG");
						   $extension=(".$file_ext");
						  
						  if(in_array($file_ext,$expensions)=== false)
						  {
						     
							http_response_code(400);
						  }
						 
						 
						  
						 else if(empty($errors)==true) 
						  {
							   move_uploaded_file($file_tmp,"../assets/profDocuments/".$service_professional_id.$uniqid.$extension);
							   $file=$service_professional_id.$uniqid.$extension;
							   
							   //$file="http://127.0.0.1/Professional_API/Documents/prof_doc/".$service_professional_id.$uniqid.$extension;
							  
							  $Prof_query= mysql_query("SELECT * FROM sp_professional_documents WHERE professional_id = '$service_professional_id' AND document_list_id = '$types' ");
								$row_count = mysql_num_rows($Prof_query);
								if ($row_count > 0)
								{
								 $row = mysql_fetch_array($Prof_query);
							   	 
								 $Documents_id=$row['Documents_id'];
								 $url_path=$row['url_path'];
								 $Loc_query=mysql_query("UPDATE sp_professional_documents SET url_path='$file',status=4 WHERE professional_id = '$service_professional_id' AND document_list_id = '$types'");
								if(mysql_affected_rows())
								{
									 $Prof_query= mysql_query("SELECT * FROM sp_professional_documents WHERE professional_id = '$service_professional_id' AND document_list_id = '$types' ");
								$row_count = mysql_num_rows($Prof_query);
								if ($row_count > 0)
								{
								 $row = mysql_fetch_array($Prof_query);
							   	 
								 $Documents_id=$row['Documents_id'];
								 $url_path=$row['url_path'];
								 $doc_url_path=$PROF_PROF_DOCUMENTS_URL.$url_path;
								echo json_encode(array("data"=>array("id"=>$Documents_id,"url"=>$doc_url_path),"error"=>null));	
								}
								}
								
								
								}
							else
							{
							    
							    
							    	
									$arrs['professional_id']=$service_professional_id;
									$arrs['document_list_id']=$document_list_id;
									$arrs['url_path']=$file;										
									//$arrs['Name']=$Documents_name;
								
									
										$InsertRecord=$professionalsClass->API_Add_Documents($arrs);
										 $Documents_id=mysql_insert_id(); 
							   
							    
							    
							 
							  // $query= mysql_query("INSERT INTO sp_professional_documents() VALUES ('','$service_professional_id','$document_list_id','$file','$Documents_name','4','1')");
							   //$Documents_id=mysql_insert_id();
							   $Prof_query= mysql_query("SELECT * FROM sp_professional_documents WHERE professional_id = '$service_professional_id' AND Documents_id = '$Documents_id' ");
								$row_count = mysql_num_rows($Prof_query);
								if ($row_count > 0)
								{
								 $row = mysql_fetch_array($Prof_query);
							   	 
								     $Documents_id=$row['Documents_id'];
								    $url_path=$row['url_path'];
								    $doc_url_path=$PROF_PROF_DOCUMENTS_URL.$url_path;
								    
							echo json_encode(array("data"=>array("id"=>$Documents_id,"url"=>$doc_url_path),"error"=>null));
							
							
								}
							}
						  }
						  
						  else
						  {
						       
							  http_response_code(400);
							 
						  }
			  
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