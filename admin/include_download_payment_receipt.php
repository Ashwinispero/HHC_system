<?php
    require_once 'inc_classes.php';            
    
    $event_id=$_POST['event_id'];
//echo $event_id;
$event_requirement_id= $_POST['event_requirement_id'];
$payment_id = $_POST['payment_id'];
$plan_of_care= mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id'");
if(mysql_num_rows($plan_of_care) < 1 )
{
	$service_cost ='';
}
else
{
	$row4 = mysql_fetch_array($plan_of_care) or die(mysql_error());
	//$service_cost=$row4['service_cost'];
	$event_id=$row4['event_id'];
	$service_date_to=$row4['service_date_to'];
	$service_date=$row4['service_date'];
	$start_time=$row4['start_date'];
	$endtime=$row4['end_date'];
	$fromDate = date('d-m-Y',strtotime($service_date)); //d-m-Y
    $toDate = date('d-m-Y',strtotime($service_date_to));
	
	$fetch_prof_nm = mysql_query("SELECT * FROM sp_event_professional WHERE event_id='$event_id'");
	$fetch_prof_nm = mysql_fetch_array($fetch_prof_nm);
	$professional_vender_id = $fetch_prof_nm['professional_vender_id'];
	
	$fetch_prof_name = mysql_query("SELECT * FROM sp_service_professionals WHERE service_professional_id='$professional_vender_id'");
	$fetch_prof_name = mysql_fetch_array($fetch_prof_name);
	$title = $fetch_prof_name['title'];
	$name = $fetch_prof_name['name'];
	$first_name = $fetch_prof_name['first_name'];
	$middle_name = $fetch_prof_name['middle_name'];
	$mobile_no=$fetch_prof_name['mobile_no'];
	$professional_name=$title.' '.$name.' '.$first_name.' '.$middle_name.'/'.$mobile_no;
	
	
	
	$fetch_event_id = mysql_query("SELECT * FROM sp_events WHERE event_id='$event_id'");
	$get_event_number = mysql_fetch_array($fetch_event_id);
	$patient_id = $get_event_number['patient_id'];
	$bill_no_ref_no=$get_event_number['bill_no_ref_no'];
	$event_code=$get_event_number['event_code'];
	$event_date=$get_event_number['event_date'];
	
	$patient_details = mysql_query("SELECT hhc_code,name,first_name,residential_address FROM sp_patients WHERE patient_id='$patient_id'");
	$name_address = mysql_fetch_array($patient_details);
	$hhc_code=$name_address['hhc_code'];
	$first_name_patient=$name_address['first_name'];
	$middle_name_patient=$name_address['middle_name'];
	$name_patient=$name_address['name'];
	$permanant_address=$name_address['permanant_address'];
	$mobile_no=$name_address['mobile_no'];
?>
<html>
    <head>
        <title>Payment Receipt</title> 
       
    </head>
    <body style="font-family:arial; font-size:11px; color:#000; background:no-repeat url(images/pdf-bg.png) center;">
        <div style="width:795px; margin:0 auto; padding:20px 35px 0;">
            <!--Header Start-->
            <div style="border-bottom:1px solid #fbb336;">
             <table cellpadding="0" cellspacing="0" width="100%">
             	<tr>
                    <td align="left"> 
                      <div style="float:left;">
                        <h3 style="color:#00cfcb; font-size:20px; vertical-align:middle; font-weight:normal; color:#000;"> Receipt  <span style="float:right;padding-right: 25px;"><br/> <?php echo "Event:".$event_code; ?>     |  <?php  echo  "HHC No. :".$hhc_code;  ?></span></h3>
                      </div>
                      <div style="float:left;">
                          <h3 style="color:#00cfcb; font-size:20px; vertical-align:middle; font-weight:normal; color:#000;">Event Date :<?php echo $event_date;?></h3>
                      </div>
                    </td>
                    
                    <td align="right"><div style="float:right;"><img src="images/logo_spero.png"></div></td>
                </tr>
             </table>
              <div style="clear:both;"></div>
            </div>
          <!--Header End-->
          <!--Body Start-->
          <div style="padding:15px 30px;">

	<?php
	//PHP code for invoice fields - Amod
	
	

	

	$event_details = mysql_query("SELECT * FROM sp_events WHERE event_id='$event_id'");
	$all_event_details = mysql_fetch_array($event_details);
	$bill_no_ref_no=$all_event_details['bill_no_ref_no'];
	

	$added_by = $get_event_number['added_by'];
	$added_by_details = mysql_query("SELECT hospital_id FROM sp_employees WHERE employee_id='$added_by'");
	$hospital_id = mysql_fetch_array($added_by_details);

	$hospital_id_ref = $hospital_id['hospital_id'];
	$hosp_code = mysql_query("SELECT hospital_short_code FROM sp_hospitals WHERE hospital_id='$hospital_id_ref'");
	$hospital_short_code = mysql_fetch_array($hosp_code);

	$full_code = $hospital_short_code['hospital_short_code'];
	list($code,$hc) = explode('HC',$full_code);
	$current_year = Date("d-m-Y");
	list($d,$m,$y) = explode('-',$current_year);
	$fin_year = $y+1;
	
	?>
              
			 
                        <div style="margin-bottom:10px;" >
                            <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/coller-icon.png"></span>BILL DETAILS</h4>
                            <div style="padding-left:45px;">
                                
                                    <div style="margin-bottom:10px;">
                                    <table cellpadding="0" cellspacing="0" width="100%">
										<tr>
                                            <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Bill Number :</div> </td>
                                            <td><div style="width:260px; color:#000;  float:left;"><?php  echo "$code / 2017-18 / $bill_no_ref_no";  ?></div><div style="clear:both;"></div></td>
										
<?php 

$row_date = "$get_event_number[added_date]";
list($date,$time) = explode(' ',$row_date);
$invoice_date = Date("d-m-Y", strtotime($date));
?>
										
											<td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Date :</div> </td>
                                            <td><div style="width:250px; color:#000;  float:left;"><?php  echo "$invoice_date";  ?></div><div style="clear:both;"></div></td>
										</tr>
										<tr>
											<td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Reference Number :</div> </td>
                                            <td><div style="width:250px; color:#000;  float:left;"><?php  echo "$name_address[hhc_code] / $event_code";  ?></div><div style="clear:both;"></div></td>
											<td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Professional Name :</div> </td>
                                            <td><div style="width:250px; color:#000;  float:left;"><?php echo "$professional_name" ; ?></div><div style="clear:both;"></div></td>
										
										</tr>
                                    </table>
                                    </div>
                                                         
                            </div> 
                        </div>
                        <div style="background:#e4e1e1; height:1px;"></div>
              
                            <div style="margin-bottom:10px;">
                            <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/patient-icon.png"></span>PATIENT DETAILS</h4>
                            <div style="padding-left:45px;">
                              <div style="margin-bottom:10px;">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                     <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;"><label class="col-sm-2 control-label" style="padding-top:0px;">Name :</label></div> </td>
                                     <td><div style="width:500px; color:#000;  float:left;"><?php  echo $first_name_patient." ";  echo $middle_name_patient." ";   echo $name_patient;  ?></div></td>
                                    
                                     <td style="width:175px; color:#000;"><div style="width:175px; margin-right:10px;  float:left;">Mobile:</div> </td>
                                     <td> <div style="width:500px; color:#000;  float:left;"><?php  echo $mobile_no;  ?></div></td>
                                    </tr>
									
                                    <tr>
                                     <td style="width:175px; color:#000;"> <div style="width:175px; margin-right:10px;  float:left;">Residential Address:</div> </td>
                                     <td><div style="width:500px; color:#000;  float:left;"><?php  echo $residential_address;  ?></div></td>
                                    
                                     <td style="width:175px; color:#000;"> <div style="width:175px; margin-right:10px;  float:left;">Permanent Address:</div> </td>
                                     <td><div style="width:500px; color:#000;  float:left;"><?php  echo $permanant_address;  ?></div></td>
                                    </tr>
									                               
                                </table>
                                <div style="clear:both;"></div>
                              </div>
							</div>
							</div>
                  
          
                                    
                        <h4 style="color:#000; font-size:16px; vertical-align:middle;"><span><img style="margin-right:10px; vertical-align: middle;" width="29" height="29" src="images/plan-of-care.png"></span>Service Details</h4>
                        <div style="margin-bottom:10px; padding-left:0px;">
                          <table cellspacing="0" cellpadding="5" width="100%" style="border:0px solid #CCC; font-size:11px">
                              <thead>
                                <tr style="background:#00cfcb; color:#fff; font-size:11px; padding:3px;">
                                  <th>Service</th>
                                  <th>Recommended Service</th>
                                  <th>Date (From/To)</th>
                                  <th>Time(From/To)</th>
                                  <th>Cost <img src="images/rupee.png" style="vertical-align:inherit;" /></th>
                                </tr>
                              </thead>
                              <tbody>
                                  <?php
                                         
                                                  $total_cost = 0; 
                                                  $totalTax=0;
                                                  $i=0;
                                        //
										
	$event_requirement_id2 = mysql_query("SELECT * FROM sp_event_requirements WHERE event_id='$event_id'");
	while ($event_requirement_id1 = mysql_fetch_array($event_requirement_id2))
	{		
	
	$event_requirement_id=$event_requirement_id1['event_requirement_id'];
	$plan_of_care= mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id'");
	$row4 = mysql_fetch_array($plan_of_care) or die(mysql_error());
	$service_cost=$row4['service_cost'];
	
	$service_id=$event_requirement_id1['service_id'];
	$sub_service_id=$event_requirement_id1['sub_service_id'];
	$service_details = mysql_query("SELECT * FROM sp_sub_services WHERE service_id='$service_id' and sub_service_id='$sub_service_id'");
	$service_details = mysql_fetch_array($service_details);
	$recommomded_service=$service_details['recommomded_service'];
	//echo $recommomded_service;
	$service_name = mysql_query("SELECT * FROM sp_services WHERE service_id='$service_id' ");
	$service_name = mysql_fetch_array($service_name);
	$service_title=$service_name['service_title'];
	
	$service_details = mysql_query("SELECT * FROM sp_sub_services WHERE service_id='$service_id' and sub_service_id='$sub_service_id'");
	$service_details = mysql_fetch_array($service_details);
	$recommomded_service=$service_details['recommomded_service'];
	//echo $recommomded_service;
	$service_name = mysql_query("SELECT * FROM sp_services WHERE service_id='$service_id' ");
	$service_name = mysql_fetch_array($service_name);
	$service_title=$service_name['service_title'];
	//echo $service_title;
										//
                                                        
                                                         
												if($recommomded_service=='Consultant Charges')
												{
												$query=mysql_query("SELECT * FROM sp_event_requirements where event_id='$event_id' ") or die(mysql_error());
												   $row = mysql_fetch_array($query) or die(mysql_error());
												   $Consultant=$row['Consultant'];
												   $hospital_id=$row['hospital_id'];													   
												   if($Consultant==0)
													{
														$telephonic_consultation_fees=0;
													}
													else
													{
												   
												   $query=mysql_query("SELECT * FROM sp_doctors_consultants where hospital_id='$hospital_id' and doctors_consultants_id='$Consultant' ") or die(mysql_error());
												   $row = mysql_fetch_array($query) or die(mysql_error());
												   $telephonic_consultation_fees=$row['telephonic_consultation_fees'];
													}	
												   echo '<tr>
                                                             <td>'.$service_title.' </td>
                                                             <td>'.$recommomded_service.'</td>';
                                                            echo '<td>NA</td>';  
                                                             if(!empty($start_time) && !empty($endtime)) 
                                                             { 
                                                                echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                             }
                                                              else 
                                                             {
                                                                  echo '<td>NA</td>'; 
                                                             }
                                                             echo '<td>'.$telephonic_consultation_fees.'/-</td>
                                                        </tr>';
												   }
												else
												{
                                                                  echo '<tr>
                                                                              <td>'.$service_title.' </td>
                                                                              <td>'.$recommomded_service.'</td>';
                                                                              if(!empty($fromDate) && !empty($toDate)) 
                                                                              {
                                                                                  echo '<td>'.$fromDate.' to '.$toDate.'</td>';
                                                                              }
                                                                              else 
                                                                              {
                                                                                 echo '<td>NA</td>'; 
                                                                              }
                                                                              if(!empty($start_time) && !empty($endtime)) 
                                                                              {
                                                                                  echo '<td>'.$start_time.' to '.$endtime.'</td>';
                                                                              }
                                                                              else 
                                                                              {
                                                                                 echo '<td>NA</td>'; 
                                                                              }
                                                                              if(!empty($fromDate) && !empty($toDate)) 
                                                                              {
                                                                                  echo '<td>'.$service_cost.'/-</td>';
                                                                              }
                                                                              else 
                                                                              {
                                                                                  echo '<td>NA</td>';
                                                                              }
                                                                            echo '</tr>';
												}      
                                                $totalTax = $totalTax+$service_cost;             
                                     }     
                                                                  
                                                          
  
                                                           echo '<tr><td colspan="5"><div><table><tr ><td colspan="5"></td></tr></table></div></td></tr>';
                                                          //$allRequirements[] = $event_requirement_id;
                                                          
                                                  
  
                                                  //$passArray = implode(",",$allRequirements);	
                                                  echo '<tr class="tax-row"><td colspan="4" style="text-align:right;"></td><td></td></tr>';
                                                  //$totalTax = 0;

                                                  $totalEstimatedCost = ($total_cost + $totalTax + $telephonic_consultation_fees);
                                                  $finalcost = ($totalEstimatedCost - $all_event_details['discount_amount']);

                                                  echo '<tr class=" ' . ($all_event_details['discount_amount'] == '0.00' ? 'total-row' : '') . '">
                                                            <td colspan="4" style="background:#fdeed4; padding:5px; font-size:11px; color:#333;">
                                                                TOTAL ESTIMATED COST:
                                                            </td>';
                                                            if(!empty($fromDate) && !empty($toDate)) 
                                                            {
                                                                echo '<td style="background:#fdeed4;"> ' . $totalEstimatedCost . '/-</td>';
                                                            }
                                                            else 
                                                            {
                                                                echo '<td style="background:#fdeed4;">NA</td>';
                                                            }
                                                   echo '</tr>';

                                                   if ($all_event_details['discount_amount'] != '0.00') {
                                                        echo '<tr>
                                                            <td colspan="4" style="padding:5px; font-size:11px; color:#333;">
                                                                TOTAL DISCOUNT COST:
                                                            </td>
                                                            <td>
                                                                ' . ($all_event_details['discount_amount'] ? number_format($all_event_details['discount_amount'], 2) . '/-' : 'NA') . '
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5"></td>
                                                        </tr>';
                                
                                                        echo '<tr class="total-row">
                                                            <td colspan="4" style="background:#fdeed4; padding:5px; font-size:11px; color:#333;">
                                                                TOTAL ESTIMATED COST WITH DISCOUNT:
                                                            </td>
                                                            <td style="background:#fdeed4;">
                                                                ' . ($finalcost ? number_format($finalcost, 2) . '/-' : 'NA') . '
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5"></td>
                                                        </tr>';
                                                }

                                                
	
//Function to convert number to words			

function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'Zero',
        1                   => 'One',
        2                   => 'Two',
        3                   => 'Three',
        4                   => 'Four',
        5                   => 'Five',
        6                   => 'Six',
        7                   => 'Seven',
        8                   => 'Eight',
        9                   => 'Nine',
        10                  => 'Ten',
        11                  => 'Eleven',
        12                  => 'Twelve',
        13                  => 'Thirteen',
        14                  => 'Fourteen',
        15                  => 'Fifteen',
        16                  => 'Sixteen',
        17                  => 'Seventeen',
        18                  => 'Eighteen',
        19                  => 'Nineteen',
        20                  => 'Twenty',
        30                  => 'Thirty',
        40                  => 'Fourty',
        50                  => 'Fifty',
        60                  => 'Sixty',
        70                  => 'Seventy',
        80                  => 'Eighty',
        90                  => 'Ninety',
        100                 => 'Hundred',
        1000                => 'Thousand',
        1000000             => 'Million',
        1000000000          => 'Billion',
        1000000000000       => 'Trillion',
        1000000000000000    => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}					
					
					
					echo '<tr class="total-row"><td colspan="1" style="text-align:left; background:#fdeed4; padding:5px; font-size:11px; color:#333;">AMOUNT IN WORDS:</td>';
                                            if(!empty($fromDate) && !empty($toDate)) 
                                            {
												$in_words = convert_number_to_words($finalcost);
                                               echo '<td colspan="4" style="text-align:right;background:#fdeed4;">Rupees '.$in_words.' Only</td>';
                                            }
                                            else 
                                            {
                                               echo '<td>NA</td>'; 
                                            }
                    echo '</tr>';
                                          
  
                            ?>
                              </tbody>
                            </table>
                          </div>
						   <div style="background:#e4e1e1; height:1px; width:100%; margin-top:20px; margin-bottom:20px;"></div>
						  <div class="row">
		<div class="col-lg-6">
		<form class="form-horizontal rounded-corner col-lg-12">
				<div class="form-group">
				<div class="col-lg-5"><b>Company's Bank Detail:</b></div>
				<div class="col-lg-12">Bank Name:HDFC BANK C.C A/C - 50200010027418</div>
				<div class="col-lg-12">A/C No.  : 50200010027418</div>
				<div class="col-lg-12">Branch & IFS Code: BHANDARKAR ROAD & HDFC0000007</div><br>
				</div>
			</form> </div>
			</div>
                       
                    <div class="form-group">
				
				<div class="col-lg-5"><b>Declaration:</b></div>
				<div class="col-lg-2" style="font-weight:normal;">We declare that this Bill shows the actual prise of the services described and that all particulars are true and correct. </div>
			</div>
					
				
			<div style="background:#e4e1e1; height:1px; width:100%; margin-top:20px; margin-bottom:20px;"></div>
			  
                                <h4 style="color:#000; font-size:16px; vertical-align:middle;">
                                    <span><img style="margin-right:10px; vertical-align: middle;" height="29" width="29" src="images/feedback.png"></span> PATIENT PAYMENT RECEIPT DETAILS 
                                </h4>
							     
					
                    <?php
                        $Transaction_Type = '';
                        $amount = '';
                        $type = '';
                        $date_time = '';	
						$paymentSql = "SELECT p.payment_id,
                                            p.event_id,
                                            p.Transaction_Type,
                                            p.amount AS receiptTotalAmount,
                                            p.type,
                                            pd.event_requrement_id,
                                            pd.amount,
                                            p.date_time
                                        FROM sp_payments AS p 
                                        INNER JOIN sp_payment_details AS pd
                                            ON p.payment_id = pd.payment_id
                                        WHERE p.event_id = '" . $event_id . "' AND 
                                            pd.event_requrement_id = '" . $_POST['event_requirement_id'] . "' AND
                                            p.payment_id = '" . $payment_id . "' ";

                        if (mysql_num_rows($db->query($paymentSql))) {
                            $paymentDetails = $db->fetch_array($db->query($paymentSql));

                            $Transaction_Type = $paymentDetails['Transaction_Type'];
                            $amount           = $paymentDetails['amount'];
                            $type             = $paymentDetails['type'];
                            $date_time        = $paymentDetails['date_time'];  
                        }	
					?>
						<table id="logTable" class="table table-striped" cellspacing="0" width="100%" style="border:0px solid #CCC; font-size:11px;margin-top:10%;">
                              <thead>
							  <tr style="font-size:11px; padding:1px;">
                                  <th style="text-align:left;font-weight:normal;">Receipt Number:<?php echo "$event_code / 2017-18 / $event_id";?></th>
                                  
								</tr>
								</thead></table>
								
				<div class="col-lg-12">Received From:
				<?php  echo $first_name_patient." ";  echo $middle_name_patient." ";  echo $name_patient;   ?></div>			 
                                  <!--<div style="text-align:left;font-weight:normal;">Received From:</Div>-->
                                 
								
								<table id="logTable" class="table table-striped" cellspacing="0" width="100%" style="border:0px solid #CCC; font-size:11px;">
							  <thead>
								<tr style=" padding:3px;">
                                  <th style="text-align:left;font-weight:normal;width:50%;">Received Rs. :<?php echo $amount;?></th>
                                  <th style="text-align:left;font-weight:normal;width:50%;">Rupees:<?php  echo convert_number_to_words($amount); ?></th>
								  <th style="text-align:left;font-weight:normal;">Only</th>
                                </tr></thead></table>
								
                                  <div style="text-align:left;font-weight:normal;">Type Of Transaction:<?php echo $Transaction_Type ;?></div>
                                 <table id="logTable" class="table table-striped" cellspacing="0" width="100%" style="border:0px solid #CCC; font-size:11px;">
							  <thead>
								<tr style=" padding:2px;">
                                  <th style="text-align:left;font-weight:normal;width:50%;">Mode Of Transaction:<?php echo $type; ?></th>
                                  <th style="text-align:left;font-weight:normal;">Of Date :<?php  echo $date_time; ?> </th>
                                </tr></thead></table>
								
                                  <div style="text-align:left;font-weight:normal;">Remark:<?php echo $Comments; ?></div>
                                  
								
                                  <div style="text-align:left;font-weight:normal;">towards11 settlement of the above bill.</div>
                                  
								
						
				
				<!--<table id="logTable" class="table table-striped" cellspacing="0" width="100%" style="border:0px solid #CCC; font-size:11px;margin-top:5%">
                              <thead>
                                <tr style="font-size:11px; padding:2px;">
                                  <th >Signature with Name of Patient</th>
                                  <th >Signature with Name of Authority</th>
                                </tr> </thead></table>-->
						<?php // }?>
				
					
					<br>
					
			<form class="form-horizontal rounded-corner col-lg-12">
				
					<div class="col-lg-12" ><b>Address:</b></div>
					<div class="col-lg-12" >Office No 5,Bhosale House Apts, Karve Road Pune, Maharashtra,411004, Email : Info@sperohealthcare.in, Website:WWW.Sperohealthcare.in, Phone :7620400100</div>
				
			</form>
			
			
			
					<br>
					
			<form class="form-horizontal rounded-corner col-lg-12">
				<div class="col-lg-12" >This is computer generated document and no authentication required.</div>
			</form>
                   <?php
                
            ?>
            
                               
                   <?php
                  
            ?>
          </div>
          <!--Body End-->
        <div style="clear:both;"></div>
        </div>
    </body>
</html>
<?php }
?>
