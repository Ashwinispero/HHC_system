<?php 
 require_once('config.php');


if($_SERVER['REQUEST_METHOD']=='POST')
{
  if(isset($_COOKIE['id']))
	 {
 
		$service_professional_id=$_COOKIE['id'];
		
	
		$sql = mysql_query("SELECT document_status FROM sp_service_professionals WHERE service_professional_id = '$service_professional_id'");
		$row_count = mysql_num_rows($sql);
		if ($row_count > 0)
		{
			$results = mysql_fetch_array($sql);
		    $status = $results['document_status'];
		    $query_update= mysql_query("SELECT * FROM sp_professional_avaibility WHERE professional_service_id = '$service_professional_id' ");
                                                    	$query_update_count = mysql_num_rows($query_update);
                                                    	if ($query_update_count > 0)
                                                    	{
                                                    	   
                                                    	     $sqls = mysql_query("UPDATE sp_service_professionals SET  availability_status=2, location_status=2 WHERE service_professional_id ='$service_professional_id'");
                                                    	     
                                                    	}							
            										    else
            										    {
            										         $sqls = mysql_query("UPDATE sp_service_professionals SET  availability_status=1, location_status=1 WHERE service_professional_id ='$service_professional_id'");
            										       
            										    }
			
			$query= mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id ='$service_professional_id'  ");
			$Query_rows=mysql_fetch_array($query);
			$location_status=$Query_rows['location_status'];
			
			if($location_status==1)
						{
							$S_status=false;
						}	
			elseif($location_status==2)
						{
							$S_status=true;
						}
			
				$query_bank_status= mysql_query("SELECT * FROM sp_bank_details WHERE Professional_id='$service_professional_id'  ");
		$row_count_bank_status = mysql_num_rows($query_bank_status);
		
			
		    $bank_array=mysql_fetch_array($query_bank_status);
			$Account_number=$bank_array['Account_number'];
		    if ($Account_number!="")
		    {
		    
		    $bank_status=true;
		    
		    }
		  	else
    		{
    		    $bank_status=false;
    		}
		
	
						
					$result=array("data"=>array("documentVerificationStatus"=>$status,"locationSubmitted"=>$S_status,"bankDetailsStatus"=>$bank_status),"error"=>null);
			
				echo json_encode($result, JSON_NUMERIC_CHECK);
		}
		else
		{
			    http_response_code(401);
				
		}
		
    	
	 }

			else{
				http_response_code(401); 
				
			    }
}
else
{
	http_response_code(405); 
				 
			
}


?>