<?php


include('config.php');

if($_SERVER['REQUEST_METHOD']=='GET')
 {
			
	 if(isset($_COOKIE['id']))
	
	 {
	
		$Professional_id=$_COOKIE['id'];
			$device_id=$_COOKIE['device_id'];
		
			$querys_session= mysql_query("SELECT * FROM sp_session WHERE service_professional_id=$Professional_id AND device_id=$device_id AND status=2 ");
			$row_count_session = mysql_num_rows($querys_session);
		if ($row_count_session > 0)
			{
				http_response_code(401);
			  
			
			
			}
			else
			{
		$query= mysql_query("SELECT * FROM sp_bank_details WHERE Professional_id='$Professional_id'  ");
		$row_count = mysql_num_rows($query);
		
		if ($row_count > 0)
		{	
	    
			$row = mysql_fetch_array($query);
		
				 $accountNumber=$row['Account_number'];
				 $accountName=$row['Account_name'];
				  $bank=$row['Bank_name'];
				 $branch=$row['Branch']; 
				 $ifscCode=$row['IFSC_code'];
				 $accountType=$row['Account_type'];
				 $amountWithSpero=$row['Amount_with_spero'];
				 $amountWithSpero=(int)$amountWithSpero;
				 $amountWithMe=$row['Amount_with_me'];
				  $amountWithMe=(int)$amountWithMe;
						  
			  $querys= mysql_query("SELECT * FROM sp_event_requirements WHERE professional_vender_id='$Professional_id'  ");
			  $row_counts = mysql_num_rows($querys);
						 
						 $user = array(
									'accountNumber'=>$accountNumber,
									'accountName'=>$accountName, 
									'bank'=>$bank, 
									'branch'=>$branch, 
									'ifscCode'=>$ifscCode,	
									'accountType'=>$accountType,		
									'numberOfServices'=>$row_counts,
									'amountWithMe'=>$amountWithMe
									
									);
		 
		
	    echo json_encode(array("data"=>$user,"error"=>null));
	  
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