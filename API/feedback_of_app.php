<?php
  require_once 'classes/feedbackClass.php';
//require_once 'classes/commonClass.php';
$feedbackClass=new feedbackClass();
include('config.php');
        
		
		 if($_SERVER['REQUEST_METHOD']=='POST')
		{ 
			 if(isset($_COOKIE['id']))
			 {
					$data = json_decode(file_get_contents('php://input'));
					date_default_timezone_set("Asia/Calcutta");			
					$feedback = $data->feedback;			
					$professional_vender_id=$_COOKIE['id'];
					$added_date=date('Y-m-d H:i:s');
					$device_id=$_COOKIE['device_id'];
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_vender_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
						
								$arg['feedback']=$feedback;
								$arg['professional_id']=$professional_vender_id;
								$arg['added_date']=$added_date;
								
								$InsertRecord=$feedbackClass->API_feedback_for_app($arg);	
						
							    echo json_encode(array("data"=>null,"error"=>null));
			
		
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