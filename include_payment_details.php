<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass(); 
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";    
    
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['employee_id']=$_SESSION['employee_id'];
    //if($EID)
    $recArgs['event_id']=$_REQUEST['event_id'];
    //var_dump($recArgs);
    //print_r($recArgs);
    $recListResponse= $eventClass->planofcareRecords($recArgs);
    
   // echo '<pre>';
    //print_r($recListResponse);
   // echo '</pre>';
   // exit;

    $EventResponse = $eventClass->GetEvent($recArgs);
    $patArg['patient_id'] = $EventResponse['patient_id'];
    $patientHHCresponse = $patientsClass->GetPatientById($patArg);
    //var_dump($recListResponse);
   // exit;
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count']; 
    if($recListCount > 0)
    {
        $paginationCount=getAjaxPagination($recListCount);
    }
    if(!$recListCount)
        echo '<center><br><br><h1 class="messageText">No records found related to your search, please try again.</h1></center>';
    if($recListCount)
    {
    echo '<h2 class="page-title">Payments Details </h2>';

?>
	<form name="PlanofCareForm" id="PlanofCareForm" method="post" action="event_ajax_process.php?action=submitPlanofCare">
        <div id="logTable"> 
            <input type="hidden"  name="PlanEvent_id" id="PlanEvent_id" value="<?php echo $_REQUEST['event_id'];?>" > 
            <input type="hidden" name="EstimationRadioStatus" id="EstimationRadioStatus">
            <div class="main-row" style="background: #00cfcb;color:#fff;font-size:16px;">
				<div class="text-left" style="width:15%;display:inline-block;padding:4px;">Date</div>
				<div class="text-left" style="width:15%;display:inline-block;padding:4px;">Professional Name</div>
				<div class="text-left" style="width:15%;display:inline-block;padding:4px;">Transaction Type</div>
                <div class="text-left" style="width:15%;display:inline-block;padding:4px;">Amount <img src="images/rupee.png" style="vertical-align:inherit;" /></div> 
				<div class="text-left" style="width:15%;display:inline-block;padding:4px;">Mode Of Payment</div>
				<div class="text-left" style="width:15%;display:inline-block;padding:4px;">download</div>
            </div>
            <div class="clearfix"></div>
		<?php
		
		$eventid = $_REQUEST['event_id'];
		$event_details = mysql_query("SELECT * FROM sp_events WHERE event_id='$eventid'");
		$all_event_details = mysql_fetch_array($event_details);
		$event_code=$all_event_details['event_code'];
		
		$payments = mysql_query("SELECT * FROM sp_payments WHERE event_id='$eventid' and status='1' ORDER BY date_time ASC");

		$row_count = mysql_num_rows($payments);
		
		$total_paid_query = mysql_query("SELECT SUM(amount) FROM sp_payments WHERE event_id='$eventid' and status='1'");
		$total_paid_array = mysql_fetch_array($total_paid_query);


		$finalcost = ($finalcost - $EventResponse['discount_amount']);
		$due_balance = $finalcost - $total_paid_array[0];
		//echo $due_balance;
		
		$paid_amount = 0;
		
		if((($row_count > 0)&&($due_balance == 0)) OR (($row_count > 0)&&($due_balance < 0)))
		{
		for($i=1; $i<=$row_count;)
			{
			while ($payment_rows = mysql_fetch_array($payments))
				{		
			$payment_id=$payment_rows['payment_id'];
			$date_time = explode(" ",$payment_rows['date_time']);
			$exploded_date = $date_time[0];
			//$time = $date_time[1];
			$date = date('d-m-Y',strtotime($exploded_date));
			
			/*Refund amount conversion*/
			//echo $payment_rows['professional_name'];
			
			$Transaction_Type=$payment_rows['Transaction_Type'];
			if($Transaction_Type=='Refund')
			{
				$Amount=explode("-",$payment_rows['amount']);
				$Amount1=$Amount[1];
			}
			if($Transaction_Type=='Payment')
			{
				$Amount1=$payment_rows['amount'];
			}
			
			echo '<div class="main-row">
				<div class="datepairExample_0">
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input type="text" value="'.$date.'" name="" id="" class="form-control datepicker_eve_0 readonly"  /></div>
					</div>
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">'.$payment_rows['professional_name'].'</div>
					</div>
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">'.$payment_rows['Transaction_Type'].'</div>
					</div>
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">'.$Amount1.'</div>
					</div>
					<div style="width:20%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">'.$payment_rows['type'].'</div>
					</div>
					
					<div style="width:10%;display:inline-block;padding-right:1%;" >
					 <a href="javascript:void(0);" onclick="download_receipt('.$payment_id.','.$eventid.')"  data-toggle="tooltip" title="Download PDF">
					<img alt="Download PDF" src="images/pdf-icon.png" />
					</a>
				</div>
			</div>';
			$i++;
			$paid_amount = $paid_amount + $payment_rows['amount'];
				}
			}
			echo '<center><h4 class="messageText">All payments are completed</h4></center>';
			$date1 = date('d-m-Y');
			echo '<div class="main-row">
			<h3 class="messageText" Style="margin-left:30%;">Enter the refund below</h3>
			</div>';
			echo '<div class="main-row">
				<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input type="text" value="'.$date1.'" name="today_payment_date" id="" class="form-control datepicker_eve_0 readonly" disabled /></div>
				</div>
				<div style="width:15%;display:inline-block;padding-right:1%;">
				<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<select name="Professional_name" id="Professional_name" class="form-control" onchange="return remove_error_professional_name();">
							<option value="">Select Professional Name</option>';
							$fetch_prof_nm = mysql_query("SELECT * FROM sp_event_professional WHERE event_id='$eventid'");
							while($row=mysql_fetch_array($fetch_prof_nm))
							{
					$professional_vender_id = $row['professional_vender_id'];
					//echo $professional_vender_id;
					//echo '<option value="">'.$professional_vender_id.'</option>';
					$fetch_prof_name = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id=$professional_vender_id");
					$prof_name_row = mysql_fetch_array($fetch_prof_name);
					$title = $prof_name_row['title'];
					$name = $prof_name_row['name'];
					$first_name = $prof_name_row['first_name'];
					$middle_name = $prof_name_row['middle_name'];
					$mobile_no=$prof_name_row['mobile_no'];
					echo '<option value="'.$title.' '.$name.' '.$first_name.' '.$middle_name.'">'.$title.' '.$name.' '.$first_name.' '.$middle_name.'</option>';
				}
				echo '	</select>
				
				</div>
				<div id="error_message_Professional_name" style="color:red"></div>
				</div>
				
				<div style="width:15%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<select name="Transaction_Type" id="Transaction_Type" class="form-control" onchange="return remove_error_Transaction_Type();">
							<option value="">Select Transaction Type</option>
							<option value="Refund">Refund</option>
							
					</select>
					<div id="error_message_Transaction_Type" style="color:red"></div>
					</div>
					<!--<input type="text" value="" name="Comments" id="Comments" class="form-control" placeholder="Comments" />-->
				</div>
				<div style="width:15%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input type="text" value="" name="amount" id="amount" class="form-control" placeholder="Rs" onblur="return remove_error_amount();" onkeypress="return isNumberKey(event)" /></div>
				<div id="error_message_amount" style="color:red"></div>
				</div>
				
				<div style="width:15%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<select name="payment_type" id="payment_type" class="form-control" onchange="change_type_of_payment();">
							<option value="">Select Type Of Payment</option>
							<option value="Cash">Cash</option>
							<option value="Card">Card</option>
							<option value="Cheque">Cheque</option>
							<option value="NEFT">NEFT</option>
					</select></div>
					<div id="error_message_paytype" style="color:red"></div>
					<!--<input type="text" value="" name="Comments" id="Comments" class="form-control" placeholder="Comments" />-->
				</div>
				<div style="width:30%;display:none;padding-right:1%;" id="Cheque_DD__NEFT_no_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:190%"  type="text" value="" name="Cheque_DD__NEFT_no" id="Cheque_DD__NEFT_no" class="form-control" placeholder="Cheque/DD/NEFT_no" />
					</div>
					
				</div>
				<div style="width:30%;display:none;padding-right:1%;" id="Cheque_DD__NEFT_date_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:180%"  type="date" value="" name="Cheque_DD__NEFT_date" id="Cheque_DD__NEFT_date" class="form-control" placeholder="Cheque_DD__NEFT_date" />
					</div>
					
				</div>
				
				<div style="width:30%;display:none;padding-right:1%;" id="Party_bank_name_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:190%"  type="text" value="" name="Party_bank_name" id="Party_bank_name" class="form-control" placeholder="Party Bank Name" />
					</div>
					
				</div>
				<div style="width:20%;display:none;padding-right:1%;" id="Card_Number_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:130%"  type="text" value="" name="Card_Number" id="Card_Number" class="form-control" placeholder="Card Number" />
					</div>
					<div id="error_message_card_no" style="color:red"></div>
				</div>
				<div style="width:20%;display:none;padding-right:1%;" id="Transaction_ID_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:130%"  type="text" value="" name="Transaction_ID" id="Transaction_ID" class="form-control" placeholder="Transaction ID" />
					</div>
					<div id="error_message_transaction_id" style="color:red"></div>
				</div>
				<div style="width:40%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:250%"  type="text" value="" name="Comments" id="Comments" class="form-control" placeholder="Narration" />
					</div>
					
				</div>
				
				
				<div style="width:8%;display:inline-block;padding-right:1%;">
					<div class="pull-right" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input type="button" class="btn btn-primary" id="submit" value="SAVE" onclick="return SubmitPayment('.$eventid.',\''.$event_code.'\');"></div>
				</div>
			</div>';
		}
		
		else if(($row_count > 0)&&($due_balance > 0))
		{
			for($i=1; $i<=$row_count;)
			{
			while ($payment_rows = mysql_fetch_array($payments))
				{		
			$payment_id=$payment_rows['payment_id'];
			$date_time = explode(" ",$payment_rows['date_time']);
			$exploded_date = $date_time[0];
			//$time = $date_time[1];
			$date = date('d-m-Y',strtotime($exploded_date));
			
			/*Refund amount conversion*/
			$Transaction_Type=$payment_rows['Transaction_Type'];
			if($Transaction_Type=='Refund')
			{
				$Amount=explode("-",$payment_rows['amount']);
				$Amount1=$Amount[1];
			}
			if($Transaction_Type=='Payment')
			{
				$Amount1=$payment_rows['amount'];
			}
			
			
			echo '<div class="main-row">
				<div class="datepairExample_0">
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input type="text" value="'.$date.'" name="" id="" class="form-control datepicker_eve_0 readonly" disabled/></div>
					</div>
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">'.$payment_rows['professional_name'].'</div>
					</div>
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">'.$payment_rows['Transaction_Type'].'</div>
					</div>
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">'.$Amount1.'</div>
					</div>
					<div style="width:20%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">'.$payment_rows['type'].'</div>
					</div>
					
					<div style="width:10%;display:inline-block;padding-right:1%;" >
					 <a href="javascript:void(0);" onclick="download_receipt('.$payment_id.','.$eventid.')"  data-toggle="tooltip" title="Download PDF">
					<img alt="Download PDF" src="images/pdf-icon.png" />
					</a>
					
					</div>
				</div>
			</div>';
			$i++;
			$paid_amount = $paid_amount + $payment_rows['amount'];
				}
			}
			$date1 = date('d-m-Y');
			echo '<div class="main-row">
					<h3 class="messageText" Style="margin-left:30%;">Enter the current payment below </h3>
				</div>';

			echo '<div class="main-row">
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input type="text" value="'.$date1.'" name="today_payment_date" id="" class="form-control datepicker_eve_0 readonly" disabled /></div>
					</div>
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
							<select name="Professional_name" id="Professional_name" class="form-control" onchange="return remove_error_professional_name();">
								<option value="">Select Professional Name</option>';
								$fetch_prof_nm = mysql_query("SELECT * FROM sp_event_professional WHERE event_id = '$eventid' ");
								while ($row = mysql_fetch_array($fetch_prof_nm)) {
									$professional_vender_id = $row['professional_vender_id'];
									$fetch_prof_name = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id = $professional_vender_id");
									$prof_name_row = mysql_fetch_array($fetch_prof_name);
									$title = $prof_name_row['title'];
									$name = $prof_name_row['name'];
									$first_name = $prof_name_row['first_name'];
									$middle_name = $prof_name_row['middle_name'];
									$mobile_no=$prof_name_row['mobile_no'];
									echo '<option value="'.$title.' '.$name.' '.$first_name.' '.$middle_name.'">'.$title.' '.$name.' '.$first_name.' '.$middle_name.'</option>';
								}
						echo '</select>
							<div id="error_message_Professional_name" style="color:red"></div>
						</div>
					</div>
				
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
							<select name="Transaction_Type" id="Transaction_Type" class="form-control" onchange="return remove_error_Transaction_Type();">
								<option value="">Select Transaction Type</option>
								<option value="Payment">Payment</option>
								<option value="Refund">Refund</option>		
							</select>
						</div>
						<div id="error_message_Transaction_Type" style="color:red"></div>
						<!--<input type="text" value="" name="Comments" id="Comments" class="form-control" placeholder="Comments" />-->
					</div>

					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
							<input type="text" value="" name="amount" id="amount" class="form-control" placeholder="Rs" onblur="return remove_error_amount();" onkeypress="return isNumberKey(event)"/>
						</div>
						<div id="error_message_amount" style="color:red"></div>
					</div>
				
					<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<select name="payment_type" id="payment_type" class="form-control" onchange="change_type_of_payment();">
								<option value="">Select Type Of Payment</option>
								<option value="Cash">Cash</option>
								<option value="Card">Card</option>
								<option value="Cheque">Cheque</option>
								<option value="NEFT">NEFT</option>
						</select></div>
						<div id="error_message_paytype" style="color:red"></div>
						<!--<input type="text" value="" name="Comments" id="Comments" class="form-control" placeholder="Comments" />-->
					</div>
					<div style="width:30%;display:none;padding-right:1%;" id="Cheque_DD__NEFT_no_div">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
							<input style="width:190%"  type="text" value="" name="Cheque_DD__NEFT_no" id="Cheque_DD__NEFT_no" class="form-control" placeholder="Cheque/DD/NEFT_no" />
						</div>	
					</div>

					<div style="width:30%;display:none;padding-right:1%;" id="Cheque_DD__NEFT_date_div">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input style="width:180%"  type="date" value="" name="Cheque_DD__NEFT_date" id="Cheque_DD__NEFT_date" class="form-control" placeholder="Cheque_DD__NEFT_date" />
						</div>
					</div>
				
					<div style="width:30%;display:none;padding-right:1%;" id="Party_bank_name_div">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input style="width:190%"  type="text" value="" name="Party_bank_name" id="Party_bank_name" class="form-control" placeholder="Party Bank Name" />
						</div>	
					</div>

					<div style="width:20%;display:none;padding-right:1%;" id="Card_Number_div">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input style="width:130%"  type="text" value="" name="Card_Number" id="Card_Number" class="form-control" placeholder="Card Number" />
						</div>
						<div id="error_message_card_no" style="color:red"></div>
					</div>
					<div style="width:20%;display:none;padding-right:1%;" id="Transaction_ID_div">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input style="width:130%"  type="text" value="" name="Transaction_ID" id="Transaction_ID" class="form-control" placeholder="Transaction ID" />
						</div>
						<div id="error_message_transaction_id" style="color:red"></div>
					</div>
					<div style="width:40%;display:inline-block;padding-right:1%;" >
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input style="width:250%"  type="text" value="" name="Comments" id="Comments" class="form-control" placeholder="Narration" />
						</div>
					</div>

					<div style="width:8%;display:inline-block;padding-right:1%;">
						<div class="pull-right" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input type="button" class="btn btn-primary" id="submit" value="SAVE" onclick="return SubmitPayment('.$eventid.',\''.$event_code.'\');"></div>
					</div>
			</div>';
		}
		else 
		{
			$date1 = date('d-m-Y');
			echo '<div class="main-row">
			<h3 class="messageText" Style="margin-left:30%;">Enter the current payment below</h3>
			</div>';
			echo '<div class="main-row">
				<div style="width:15%;display:inline-block;padding-right:1%;">
						<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
						<input type="text" value="'.$date1.'" name="" id="" class="form-control datepicker_eve_0 readonly"  /></div>
				</div>
				<div style="width:15%;display:inline-block;padding-right:1%;">
				<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<select name="Professional_name" id="Professional_name" class="form-control" onchange="return remove_error_professional_name();">
							<option value="">Select Professional Name</option>';
							$fetch_prof_nm = mysql_query("SELECT * FROM sp_event_professional WHERE event_id='$eventid'");
							while($row=mysql_fetch_array($fetch_prof_nm))
							{
					$professional_vender_id = $row['professional_vender_id'];
					//echo $professional_vender_id;
					//echo '<option value="">'.$professional_vender_id.'</option>';
					$fetch_prof_name = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id=$professional_vender_id");
					$prof_name_row = mysql_fetch_array($fetch_prof_name);
					$title = $prof_name_row['title'];
					$name = $prof_name_row['name'];
					$first_name = $prof_name_row['first_name'];
					$middle_name = $prof_name_row['middle_name'];
					$mobile_no=$prof_name_row['mobile_no'];
					echo '<option value="'.$title.' '.$name.' '.$first_name.' '.$middle_name.'">'.$title.' '.$name.' '.$first_name.' '.$middle_name.'</option>';
				}
				echo '	</select>
				</div>
				<div id="error_message_Professional_name" style="color:red"></div>
				</div>
				
				<div style="width:15%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<select name="Transaction_Type" id="Transaction_Type" class="form-control" onchange="return remove_error_Transaction_Type();">
							<option value="">Select Transaction Type</option>
							<option value="Payment">Payment</option>
							<option value="Refund">Refund</option>
							
					</select></div>
					<!--<input type="text" value="" name="Comments" id="Comments" class="form-control" placeholder="Comments" />-->
				<div id="error_message_Transaction_Type" style="color:red"></div>
				</div>
				<div style="width:15%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input type="text" value="" name="amount" id="amount" class="form-control" placeholder="Rs" onblur="return remove_error_amount();" onkeypress="return isNumberKey(event)"/></div>
				<div id="error_message_amount" style="color:red"></div>
				</div>
				<div style="width:15%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<select name="payment_type" id="payment_type" class="form-control" onchange="change_type_of_payment();">
							<option value="">Select Type Of Payment</option>
							<option value="Cash">Cash</option>
							<option value="Card">Card</option>
							<option value="Cheque">Cheque</option>
							<option value="NEFT">NEFT</option>
					</select>
					</div>
					<div id="error_message_paytype" style="color:red"></div>
				</div>
				<div style="width:30%;display:none;padding-right:1%;" id="Cheque_DD__NEFT_no_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:190%"  type="text" value="" name="Cheque_DD__NEFT_no" id="Cheque_DD__NEFT_no" class="form-control" placeholder="Cheque/DD/NEFT_no" />
					</div>
					
				</div>
				<div style="width:30%;display:none;padding-right:1%;" id="Cheque_DD__NEFT_date_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:180%"  type="date" value="" name="Cheque_DD__NEFT_date" id="Cheque_DD__NEFT_date" class="form-control" placeholder="Cheque_DD__NEFT_date" />
					</div>
					
				</div>
				
				<div style="width:30%;display:none;padding-right:1%;" id="Party_bank_name_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:190%"  type="text" value="" name="Party_bank_name" id="Party_bank_name" class="form-control" placeholder="Party Bank Name" />
					</div>
					
				</div>
				<div style="width:20%;display:none;padding-right:1%;" id="Card_Number_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:130%"  type="text" value="" name="Card_Number" id="Card_Number" class="form-control" placeholder="Card Number" />
					</div>
					<div id="error_message_card_no" style="color:red"></div>
				</div>
				<div style="width:20%;display:none;padding-right:1%;" id="Transaction_ID_div">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:130%"  type="text" value="" name="Transaction_ID" id="Transaction_ID" class="form-control" placeholder="Transaction ID" />
					</div>
					<div id="error_message_transaction_id" style="color:red"></div>
				</div>
				<div style="width:40%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input style="width:250%"  type="text" value="" name="Comments" id="Comments" class="form-control" placeholder="Narration" />
					</div>
					
				</div>
				<div style="width:8%;display:inline-block;padding-right:1%;">
					<div class="pull-right" style="width:auto;display:inline-block;padding-right:2%;padding:4px;">
					<input type="button" class="btn btn-primary" id="submit" value="SAVE" onclick="return SubmitPayment('.$eventid.',\''.$event_code.'\');"></div>
				</div>
			</div>';
		}
		//This code is echoed as it was needed for + button. Now not in use.
		//echo '<input type="hidden" name="Paymentextras_'.$eventid.'" id="Paymentextras_'.$eventid.'" value="0" />';
		//echo '<tr><td colspan="5"><div id="div_1_'.$eventid.'"><table><tr ><td colspan="5"> </td> </tr></table></div></td></tr>';
		
			//$eventid2 = $eventid + 1;
			
			/*echo '<div class="col-sm-12 text-right">
						<input type="button" class="btn btn-primary" id="submit" value="SAVE" onclick="return SubmitPayment('.$eventid.');">
				</div>';*/
			
			$final_balance = $finalcost - $paid_amount;
			$balance = number_format($final_balance,2);
			echo '<div class="main-row" style="background: #fdeed4; margin-top:35px;"> 
				<div style="width:92%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">TOTAL BALANCE:</div>
					  <div id="TotalEstCost" class="text-right" style="width:7%;display:inline-block;vertical-align:top;padding-right:1%;padding:4px;">'.$balance.'</div>
			</div>';
		

		?>
        </div>
		
    </form>
	
	<?php
	} 
	// Code for + button. Removed and added to the bottom for future reference.
	/*<div style="width:30%;display:inline-block;padding-right:1%;">
					<div class="pull-left" style="width:10%;display:inline-block;padding-left:2%;padding:4px;">';
					if($recListValue['recommomded_service'] !="Other") { $service_type="1"; } else {  $service_type="2"; }
					echo '<a href="javascript:void(0);" title="Add" onclick="javascript:addMorePayments('.$eventid.','.$service_type.');"><img src="images/add.png"></a></div>
				</div>*/
}
	?>