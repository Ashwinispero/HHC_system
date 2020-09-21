<?php

include('config.php');
if($_SERVER['REQUEST_METHOD']=='POST')
 {
	$data = json_decode(file_get_contents('php://input'));
	$service_id = $data->service_id;
	
	$added_date=date('Y-m-d H:i:s');	

	$query= mysql_query("SELECT * FROM sp_professional_services WHERE (service_id='$service_id') AND status=1 ");
	$row_count = mysql_num_rows($query);

	if ($row_count > 0)
	{	
		$rows = array();
		while ($row = mysql_fetch_assoc($query))
		{
			
		$S_id=$row['service_professional_id'];		
	$queryssss= mysql_query("SELECT * FROM sp_professional_services WHERE service_id='22' AND service_professional_id='$S_id' AND status=1 ");
	$row_countssss = mysql_num_rows($queryssss);
		if ($row_countssss > 0){
			
		}
		else
		{
			$query_insert=mysql_query("insert into sp_professional_services() VALUES('','22','','1','$S_id','','','','1','','$added_date','','$added_date')");
		
		}		
	    }

		echo json_encode(array("data"=>$S_id,"error"=>null));


	}
		else
	{
		http_response_code(405);
	}				
}
else
{
	http_response_code(405);
}

?>




/*


include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
{ 

$data = json_decode(file_get_contents('php://input'));

	$data = json_decode(file_get_contents('php://input'));
	$service_id = $data->service_id;
	
	$added_date=date('Y-m-d H:i:s');	

	$query= mysql_query("SELECT * FROM sp_professional_services WHERE (service_id='$service_id') AND status=1 ");
	$row_count = mysql_num_rows($query);

	if ($row_count > 0)
	{	
		$rows = array();
		while ($row = mysql_fetch_assoc($query))
		{
			
			$S_id=$row['service_professional_id'];
			$Delete_data=mysql_query("DELETE  FROM sp_professional_services  WHERE service_id ='22' AND service_professional_id='$S_id'  ");				
        	echo json_encode(array("data"=>$S_id,"error"=>null));
		}
}
}					
else 
{
http_response_code(405); 

}

				
*/	
