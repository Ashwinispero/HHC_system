<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
 {
	
			 if(isset($_COOKIE['id']))
			 {		
	
	
	  $data = json_decode(file_get_contents('php://input'));
		$service_professional_id=$_COOKIE['id'];
		 if($service_professional_id == '' )
				{
				
					http_response_code(400);
					
				}
			
			else{
			    
					$Sub_service= mysql_query("SELECT * FROM sp_professional_sub_services WHERE  service_professional_id=$service_professional_id  ");
					while($Sub_services = mysql_fetch_array($Sub_service))
					{
					    
					$Sub_service_id=$Sub_services['sub_service_id'];
					$Sub_service_id=(int)$Sub_service_id;
					
					$Sub_service_name= mysql_query("SELECT * FROM sp_sub_services WHERE  sub_service_id=$Sub_service_id ");
					$Sub_servicesss = mysql_fetch_array($Sub_service_name);
					$Sub_service_names=$Sub_servicesss['recommomded_service'];
					
					$types_profs[]=array('id'=>$Sub_service_id,
									'name'=>$Sub_service_names);
					
					
					}
					
					$sub_data=$types_profs;
					
					
					/*$query= mysql_query("SELECT sub_service_id,recommomded_service FROM sp_sub_services WHERE  service_id=$id ");
					$row_count = mysql_num_rows($query);
					
					if ($row_count > 0)
					{	
						$rows = array();
						 while ($row = mysql_fetch_assoc($query))
					  {
						 
						 $S_id=$row['sub_service_id'];
						 $S_id=(int)$S_id;
						 $S_name=$row['recommomded_service'];
						 
							 $result[]=(array('id' => $S_id, 'name'=>$S_name)); 
						//echo json_encode(array("data"=>array('id' => $S_id, 'name'=>$S_name, array("error"=>null))));
						$data=$sub_data;
					  }*/
					
					echo json_encode(array("data"=>$sub_data,"error"=>null));

	 
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