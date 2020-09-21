<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='POST')
 {
		
	 
	
	 if($professional_vender_id=$_COOKIE['id'])
	 {
	     	$data = json_decode(file_get_contents('php://input'));
	     	$professional_vender_id=$_COOKIE['id'];
	     	$pageIndex = $data->pageIndex;
			$pageSize = $data->pageSize;
    	
    		$device_id=$_COOKIE['device_id'];
			
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$professional_vender_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
			    	 if($pageIndex == '' || $pageSize == '' )
				{
				 
					http_response_code(400);
					
				}
			
			else
				
				{
			    
		        
		        $begin = ($pageIndex * $pageSize) - $pageSize;
		         
				
			    
    		$query=mysql_query("SELECT * FROM sp_professional_weekoff WHERE service_professional_id='$professional_vender_id'");
    		$row_count = mysql_num_rows($query);
		
			$querys=mysql_query("SELECT * FROM sp_professional_weekoff WHERE service_professional_id='$professional_vender_id '  ORDER BY date DESC  LIMIT $begin, $pageSize");
	    	$row_counts = mysql_num_rows($querys);
			$pages = ceil($row_count / $pageSize);
    		if ($row_counts > 0)
    		{	
    	   
    			while ($row = mysql_fetch_assoc($querys))
    			{
        			$id=$row['professional_weekoff_id'];
        			
        			$date_form=$row['date_form'];
        			$date_to=$row['date_to'];
        		    $Note=$row['Note'];
        			$status=$row['Leave_status'];
        			$S_id=(int)$id;
        				$status=(int)$status;
        			 $result[]=array("id"=>$S_id,"startDateTime"=>$date_form,"endDateTime"=>$date_to,"reason"=>$Note,"status"=>$status);
    			 
    		    }
    			
    				$out=array("data"=>array("leaves"=>$result,"pageIndex"=>$pageIndex,"totalNumberOfPages"=>$pages),"error"=>null);
    			 echo json_encode($out);

    	 }	
	 else
	 {
	    	$out=array("data"=>array("leaves"=>[],"pageIndex"=>$pageIndex,"totalNumberOfPages"=>$pages),"error"=>null);
			 echo json_encode($out);
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