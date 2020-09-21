<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
 {
	
			 if(isset($_COOKIE['id']))
			 {		
	
	
	  
			
		
				$restrication_details= mysql_query("SELECT * FROM sp_restriction_for_session_complete WHERE  status=1 ");
					$row_count = mysql_num_rows($restrication_details);
		if ($row_count > 0)
		{
					$restrication_details_row = mysql_fetch_array($restrication_details);
					{
					$distance=$restrication_details_row['distance'];
					$distance=(int)$distance;
					$duration=$restrication_details_row['duration'];
					$duration=(int)$duration;
					
				
					
					$types_profs=array('distance'=>$distance,
									'duration'=>$duration);
					
					
					}
					
					$sub_data=$types_profs;
					
					
				
					
					echo json_encode(array("data"=>$sub_data,"error"=>null));

	 
					}
					else
					{
					   	echo json_encode(array("data"=>"","error"=>null));
					
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