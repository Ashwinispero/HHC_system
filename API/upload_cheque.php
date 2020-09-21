
<?php

require_once 'classes/professionalsClass.php';
//require_once 'classes/commonClass.php';
$professionalsClass=new professionalsClass();
    include('config.php');
 header("Accept:application/json ; Content-Type: multipart/form-data; boundary=-------------------------acebdf13572468");

 

 if($_SERVER['REQUEST_METHOD']=='POST')
{ 
 
	if(isset($_COOKIE['id']))
	 
	 {
		date_default_timezone_set("Asia/Calcutta");
		$service_professional_id=$_COOKIE['id'];
				$uniqid=uniqid();
				$errors = array();
				$headers = array();
					$date = date('Y-m-d H:i:s');
				foreach ($_SERVER as $key => $value)
				{
					if (strpos($key, 'HTTP_') === 0)
						{
							$headers[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
			   
						} 
					
				}
				
			    	
					$types=$headers['Sessionid'];
				
				 
				 
				 $date = date('Y-m-d H:i:s');
								
						  $file_name = $_FILES['filename']['name'];
						  $file_size = $_FILES['filename']['size'];
						  $file_tmp = $_FILES['filename']['tmp_name'];
						  $file_type = $_FILES['filename']['type'];
						  $tmp = explode('.', $file_name);
                          $file_ext = end($tmp);
						  
						  $expensions= array("pdf","jpeg","jpg","png");
						   
						  
						  if(in_array($file_ext,$expensions)=== false)
						  {
							http_response_code(400);
						  }
						  
						
						  
						   else  
						    {
									  $extension=(".$file_ext");
									  move_uploaded_file($file_tmp,"../assets/Cheques/".$service_professional_id.$uniqid.$extension);
								      $file=$service_professional_id.$uniqid.$extension;
									  
									  
									  //move_uploaded_file($file_tmp,"Documents/payments/".$types.$uniqid.$extension);
									  //$file="http://45.40.136.143/~spero/Spero_HHC_API/API/Documents/prof_doc/payments/".$types.$uniqid.$extension;
									   
									//   $file="http://127.0.0.1/Professional_API/Documents/payments/".$types.$uniqid.$extension;
									   
									   
										
									  
									   $Prof_query= mysql_query("SELECT * FROM sp_detailed_event_plan_of_care WHERE professional_vender_id = '$service_professional_id' AND Detailed_plan_of_care_id = '$types' ");
										$row_count = mysql_num_rows($Prof_query);
										if ($row_count > 0)
										{
										$args['Url_path']=$file;										
										$args['Detailed_plan_of_care_id']=$types;
										$args['Added_date']=$date;
										
									 $InsertRecord=$professionalsClass->API_AddsessionCheque($args);
									  // $query= mysql_query("INSERT INTO sp_cheque_images() VALUES ('','$types','$file','$date')");
									   $Documents_id=mysql_insert_id();
									   $Prof_querys= mysql_query("SELECT * FROM sp_cheque_images WHERE cheque_id = '$Documents_id' ");
										$row_counts = mysql_num_rows($Prof_querys);
										
											 $row = mysql_fetch_array($Prof_querys);
											 
											 $cheque_id=$row['cheque_id'];
											 $cheque_id=(int)$cheque_id;
											 $Url_path=$row['Url_path'];
											  
											  echo json_encode(array("data"=>array("id"=>$cheque_id,"url"=>$Url_path),"error"=>null));
										
								 	}
									  else
											{
											 http_response_code(401);
											 
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