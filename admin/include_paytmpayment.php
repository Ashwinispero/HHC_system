<?php require_once('inc_classes.php'); 
	require_once '../classes/professionalsClass.php';
	$professionalsClass = new professionalsClass();
	  
	if(isset($_SESSION['admin_user_type']) && $_SESSION['admin_user_type']=='1')
	{
	  $col_class="icon3";
	  $del_visible="Y";
	}
	else 
	{
	 $col_class="icon2"; 
	 $del_visible="N";
	} 
?>
<?php
if(!$_SESSION['admin_user_id'])
    echo 'notLoggedIn';
else
{
	$recList = $professionalsClass->professionalPaytmPaymentList();
	
	if (!empty($recList)) {
		echo '<table class="table table-hover table-bordered">
                <tr> 
					<th width="15%">Professional Code</th>
					<th width="20%">Name</th>
					<th width="11%">Transaction Id</th>
					<th width="10%">Amount</th>
					<th width="16%" >Status</th>
					<th width="16%" >Date</th>
					<th width="15%" >Action</th>
				</tr>';
				
		foreach ($recList as $recListKey => $recListValue) 
        {
			echo '<tr>
                    <td>' . $recListValue['professional_code'] . '</td>
					<td>' . $recListValue['professional_name'] . '<br>'. '(' . $recListValue['professional_mobile_no'] . ')'. '</td>
					<td>' . $recListValue['bank_transaction_id'] . '</td>
					<td>' . $recListValue['transaction_Amount'] . '</td>
					<td>' . $recListValue['transStatus'] . '</td>
					<td>' . date('d M Y h:i A', strtotime($recListValue['transcation_date'])) . '</td>
					<td>
						<ul class="actionlist">
							<li>
								<a href="javascript:void(0);" onclick="return view_detail_paytm_payment(' . $recListValue['orderId'] . ');" data-toggle="tooltip" title="" data-original-title="View payment details"><img src="images/icon-view.png" alt="View payment details"></a>
							</li>
						</ul>
					</td>
				  <tr>';
		}
		echo '</table>';
	} else {
		echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
	}		
}
?>