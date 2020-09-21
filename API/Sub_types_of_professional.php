<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
 {
			
	
	
	  $data = json_decode(file_get_contents('php://input'));
		$id = $data->id;
		 if($id == '' )
				{
				
					http_response_code(400);
					
				}
			
			else{
					$query= mysql_query("SELECT sub_service_id,recommomded_service FROM sp_sub_services WHERE  service_id=$id AND flag = 1 AND  status=1 ");
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
						$data=$result;
					  }
					
					echo json_encode(array("data"=>$data,"error"=>null));

	 
					}
					
			
				}
		
	
 }
else 
{
	http_response_code(405); 
		 
	
}
?>