<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='GET')
 {
	
			$query= mysql_query("SELECT * FROM sp_no_reason_for_service WHERE is_deleted = 1 ");
			$row_count = mysql_num_rows($query);
			
			if ($row_count > 0)
			{	
				$rows = array();
				 while ($row = mysql_fetch_assoc($query))
			  {
				
				 $S_id=$row['reason_id'];
				 	$S_id=(int)$S_id;
				 $S_name=$row['reason_title'];

				$result[]=(array('id' => $S_id, 'name'=>$S_name)); 
				
				
			  }
				$data=$result;
				echo json_encode(array("data"=>$data,"error"=>null));

			 
			}
	
 }
else 
{
	http_response_code(405); 
		 
	
}
?>