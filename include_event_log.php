<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();

if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";
    if($_POST['SearchKey'] && $_POST['SearchKey']!="undefined")
        $search_Value=$_POST['SearchKey'];
    else
        $search_Value="";
        if($_POST['SearchKeyword_new'] && $_POST['SearchKeyword_new']!="undefined")
        $SearchKeyword_new=$_POST['SearchKeyword_new'];
    else
        $SearchKeyword_new="";
    if($_POST['SearchByPurpose'] && $_POST['SearchByPurpose']!="undefined")
        $search_by_purpose=$_POST['SearchByPurpose'];
    else
        $search_by_purpose="";
    /*
    if($_POST['SearchByEmployee'] && $_POST['SearchByEmployee']!="undefined")
        $search_by_employee=$_POST['SearchByEmployee'];
    else
        $search_by_employee="";
     * 
     */
    
     if($_POST['SearchByProfessional'] && $_POST['SearchByProfessional']!="undefined")
        $search_by_professional=$_POST['SearchByProfessional'];
    else
        $search_by_professional="";
      
    if($_POST['SearchToDate'] && $_POST['SearchToDate']!="undefined")
        $search_todate=$_POST['SearchToDate'];
    else
        $search_todate=""; 
    
    if($_POST['SearchfromDate'] && $_POST['SearchfromDate']!="undefined")
        $search_fromDate=$_POST['SearchfromDate'];
    else
        $search_fromDate=""; 
        
    if($_POST['SearchToDate_service'] && $_POST['SearchToDate_service']!="undefined")
        $search_todate_service=$_POST['SearchToDate_service'];
    else
        $search_todate_service=""; 
    
    if($_POST['SearchfromDate_service'] && $_POST['SearchfromDate_service']!="undefined")
        $search_fromDate_service=$_POST['SearchfromDate_service'];
    else
        $search_fromDate_service="";
    
    if($_POST['SearchByPatients'] && $_POST['SearchByPatients']!="undefined")
        $SearchByPatients=$_POST['SearchByPatients'];
    else
        $SearchByPatients="";
  
    $purpose_call_event = $_REQUEST['purpose_call_event'];
   
    $recArgs['pageIndex']=$pageId;
    $recArgs['pageSize']=PAGE_PER_NO;
    $recArgs['search_Value']=$search_Value;
    $recArgs['SearchKeyword_new']=$SearchKeyword_new;
    $recArgs['SearchByPurpose']=$search_by_purpose;
  //  $recArgs['SearchByEmployee']=$search_by_employee;
    $recArgs['SearchByProfessional']=$search_by_professional;
    $recArgs['SearchfromDate']=$search_fromDate;
    $recArgs['SearchToDate']=$search_todate;
    $recArgs['SearchfromDate_service']=$search_fromDate_service;
    $recArgs['SearchToDate_service']=$search_todate_service;
    $recArgs['SearchByPatients']=$SearchByPatients;
    $recArgs['listPageDefaultFilter'] = 1;
    $recArgs['employee_id']=$_SESSION['employee_id'];
    $recArgs['employee_type']=$_SESSION['employee_type'];
    $recArgs['hospital_id']=$_SESSION['employee_hospital_id'];
    
    if($_REQUEST['isStatus'])
    {
        if($_REQUEST['isStatus']=='2')
            $recArgs['isStatus']='2';
        if($_REQUEST['isStatus']=='3')
            $recArgs['isStatus']='3'; 
    }
    else 
        $recArgs['isStatus']='1';
    
    
    
    if($_POST['sort_order'])
        $order1=$_POST['sort_order'];
    else
        $order1='desc';    
    if($_POST['sort_order']=='asc')
    {
        $order='desc';
        $img = "<img src='images/sort_up.png' border='0'>";
    }
    else if($_POST['sort_order']=='desc')
    {
        $order='asc';
        $img = "<img src='images/sort_dwon.png' border='0'>";
    }
    else
    {
        $order='desc';
        $img = "<img src='images/sort_up.png' border='0'>";
    }
    if(isset($_POST['sort_field']))
    {
        $sort_variable=$_POST['sort_field'];  
    }
    else
    {
        $sort_variable="";
    }    
    if($_POST['sort_field']=='hhc_code')
        $img1 = $img;
    if($_POST['sort_field']=='event_code')
        $img2 = $img;
    if($_POST['sort_field']=='event_date')
        $img3 = $img;
    if($_POST['sort_field']=='added_by')
        $img4 = $img;
    if($_POST['sort_field']=='event_status')
        $img5 = $img;
   
    if($_POST['sort_order'] !='' && ($_POST['sort_field']=='hhc_code' || $_POST['sort_field']=='event_code' || $_POST['sort_field']=='event_date' || $_POST['sort_field']=='added_by' || $_POST['sort_field'] == 'event_status' ))
    {
        $recArgs['filter_name']= $_POST['sort_field'];
        $recArgs['filter_type']= $_POST['sort_order'];
    }
    else
    {
        $recArgs['filter_name']= "event_id";
        $recArgs['filter_type']= "DESC";
    }
    //var_dump($recArgs);
    
    $recListResponse= $eventClass->EventList($recArgs);
   //  var_dump($recListResponse);
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
    ?>              
    <?php 
    echo '<table id="logTable" class="table table-striped" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th><a href="javascript:void(0);" onclick="changePagination(\'eventLogListing\',\'include_event_log.php\',\'\',\'\',\''.$order.'\',\'hhc_code\');" style="color:#fff;">HHC No '.$img1.'</a> </th>
                <th><a href="javascript:void(0);" onclick="changePagination(\'eventLogListing\',\'include_event_log.php\',\'\',\'\',\''.$order.'\',\'event_code\');" style="color:#fff;">Event Id '.$img2.' </a></th>
                <th><a href="javascript:void(0);" onclick="changePagination(\'eventLogListing\',\'include_event_log.php\',\'\',\'\',\''.$order.'\',\'event_date\');" style="color:#fff;">Event Date Time '.$img3.' </a></th>
                <th>Call Purpose</th>
                <th>Caller No</th>
                <th>Patient Name</th>
                <th>Patient No</th>
                <th>Professional Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Rt.Vou.No</th>
                <th>Pending Amount</th>
                <th style="text-align:center !important;"><a href="javascript:void(0);" onclick="changePagination(\'eventLogListing\',\'include_event_log.php\',\'\',\'\',\''.$order.'\',\'event_status\');" style="color:#fff; text-align:center">Status '.$img5.' </a></th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>';
        foreach ($recList as $recListKey => $recListValue) 
        {
            $event_id=$recListValue['event_id']; 
            $EventIDS = base64_encode($event_id);
            $progress = $recListValue['event_status']*20;
            
            $estimate_cost = $recListValue['estimate_cost'];
            
             $max_id=mysql_query("SELECT MAX(plan_of_care_id) as max_id,service_date FROM sp_event_plan_of_care  where event_id='$event_id'") or die(mysql_error());
			$max_id_row = mysql_fetch_array($max_id);
            $plan_of_care_id=$max_id_row['max_id'];
            $service_date_from=$max_id_row['service_date'];
            $service_date=date('d-m-Y', strtotime($service_date_from));

            $plan_of_care_max=mysql_query("SELECT service_date_to FROM sp_event_plan_of_care  where plan_of_care_id=$plan_of_care_id");
			$plan_of_care_max_row = mysql_fetch_array($plan_of_care_max);
            $service_date_to=$plan_of_care_max_row['service_date_to'];
            
            $service_date_to_max=date('d-m-Y', strtotime($service_date_to));
           
            if($service_date=='01-01-1970' && $service_date_to_max=='01-01-1970')
            {
                $service_date='NA';
                $service_date_to_max='NA';
            }
            
            $event_code=$recListValue['event_code'];
			$fetch_event_id = mysql_query("SELECT event_id FROM sp_events WHERE event_code='$event_code'");
			$get_event_number = mysql_fetch_array($fetch_event_id);
            $event_number = $get_event_number['event_id'];

            //Check is it patient is VIP
            $patientStyle = '';
            if (!empty($recListValue['isVIP']) && $recListValue['isVIP'] == 'Y') {
                $patientStyle = 'background-color : #b3ffb3 !important;';
            }

            $payments = "SELECT payment_id,event_id,amount,Transaction_Type,payment_receipt_no_voucher_no FROM sp_payments WHERE event_id = '" . $event_number . "' ";
            $row_count_payment = $db->fetch_all_array($payments);
        
            // Check for partial payment
            $isPartialPayment = 'N';
            $style = '';
            $paymentReceiptNumbers = '';
            $Pending_amt = '';
			if(!empty($row_count_payment))
			{
                $totalReceivedAmount = 0;
                foreach($row_count_payment AS $valPayment) {
                    if ($valPayment['Transaction_Type'] == 'Payment') {
                        $totalReceivedAmount += $valPayment['amount'];
                    }
                    $paymentReceiptNumbers .= $valPayment['payment_receipt_no_voucher_no'] . '<br>';
                }

                if ($recListValue['finalcost'] != $totalReceivedAmount &&
                    ($totalReceivedAmount < $recListValue['finalcost'])
                ) {
                    $Pending_amt = $recListValue['finalcost'] - $totalReceivedAmount;
                    $isPartialPayment = 'Y';
                    $style = 'background-color : #ffd9b3 !important;';
                }
            }
            else{
                $Pending_amt = $recListValue['finalcost'];
            }

            // Check for complemantory visit
            $isComplimentaryVisit = 'N';
            $complimentaryVisitStyle = '';

            // echo '<pre>';
            // print_r($recListValue);
            // echo '</pre>';
            // exit;


            if (empty($recListValue['finalcost']) && $recListValue['Invoice_narration'] == 'Complimentary visit') {
                $isComplimentaryVisit = 'Y';
                $complimentaryVisitStyle = 'background-color : #c6ecc6 !important;';
            }
            
            $paymentReceiptNumbers = (!empty($paymentReceiptNumbers) ? substr(trim($paymentReceiptNumbers), 0, -1) : 'NA') ;
			
            if($estimate_cost == '2')
                $callpurpose = 'Estimation Call';
            else
                $callpurpose = $recListValue['call_purpose'];

            // check is it inquiry converted as event 
            $isConvertedServiceStyle = '';
            $isConvertedService = ($recListValue['isConvertedService'] == 2 ? 'Yes' : 'No');
            if ($isConvertedService == 'Yes') {
                $isConvertedServiceStyle = 'background-color : #ffcce6 !important;';
            } else {
                // check is it inquiry cancelled
                $isCancelInquiry = (($recListValue['purpose_id'] == 2 && $recListValue['enquiry_status'] == 4) ? 'Yes' : 'No');
                if ($isCancelInquiry == 'Yes') {
                    $isConvertedServiceStyle = 'background-color : #ff8080 !important;';
                }
            }
            echo '<tr style = "' . $complimentaryVisitStyle .'">
                <td style = "' . $patientStyle .'">'.$recListValue['hhc_code'].' </td>
                <td>'.$recListValue['event_code'].'</td>
                <td>'.date('d M Y h:i A',strtotime($recListValue['event_date'])).'</td>
                <td style = "' . $isConvertedServiceStyle .'">'.$callpurpose.'</td>
                <td>
                <a href="javascript:void(0);" title="Soft Dial" onclick="soft_call_dial('.$recListValue['phone_no'].')"; data-toggle="tooltip" data-placement="top" title="Softdial">
                '.$recListValue['phone_no'].'</a>
                </td>
                <td>';
                    if(!empty($recListValue['patientNm'])) { echo $recListValue['patientNm']; } else {  echo "NA"; }
                echo '</td>
                <td>
                <a href="javascript:void(0);" title="Soft Dial" onclick="soft_call_dial('.$recListValue['mobile_no'].')"; data-toggle="tooltip" data-placement="top" title="Softdial">
                '.$recListValue['mobile_no'].'</a>
                </td>
                <td>';
                 if(!empty($recListValue['profNm'])) { echo $recListValue['profNm']; } else {  echo "NA"; }
                echo '</td>
                <td style = "' . $style .'">' . $service_date . '</td>
                <td style = "' . $style .'">' . $service_date_to_max . '</td>
                <td style = "' . $style .'">' . $paymentReceiptNumbers . '</td>
                <td style = "' . $style .'">' . $Pending_amt . '</td>';
              //  echo '<td style = "' . $style .'">'; if($recListValue['call_audio'] ==''){ }else { ?><!--<a  target="_blank" href=" <?php //echo $recListValue['call_audio']; ?> " ><span aria-hidden="true" class="glyphicon glyphicon-play"></span></a> --><?php // }
               // echo '</td>';
                echo '<td width="15%">
                    <div class="col-lg-5 paddingLR0 text-right">'.$progress.'% &nbsp; </div>
                    <div class="col-lg-7 paddingLR0">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%;"> </div> 
                        </div>
                    </div>
                </td>
                <td>
                <a href="javascript:void(0);" title="View Event" onclick="ViewEvent('.$event_id.')"; data-toggle="tooltip" data-placement="top" title="View Log">
                    <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span>
                </a>
				<a href="javascript:void(0);" title="Print Receipt" onclick="PrintReceipt('.$event_id.')"; data-toggle="tooltip" data-placement="top" title="View Log">
                    <span aria-hidden="true" class="glyphicon glyphicon-print"></span>
                </a>';
            if($recListValue['event_status'] == '5' && $purpose_call_event !='7' && $purpose_call_event !='3')
            {
                if($recListValue['isArchive']=='1')
                    echo '<a href="javascript:void(0);" title="Archive" onclick="isArchive('.$event_id.');" data-toggle="tooltip" data-placement="top" title="Archive Event"><span aria-hidden="true" class="glyphicon glyphicon-folder-open"></span></a>';
                else
                    echo '';
                        
            }
            else
            {
                if($purpose_call_event == 4 || $purpose_call_event == 5)
                    echo '';
                else
                {
                    if($purpose_call_event == 7)
                    {
                        echo '<a href="javascript:void(0);" onclick="return OpenJobClosureDiv('.$event_id.');" data-toggle="tooltip" data-placement="top" title="Edit Event">
                            <span aria-hidden="true" class="glyphicon glyphicon-pencil"></span>
                        </a>';
                    }
                    else if($purpose_call_event == 3)
                    {
                        echo '<a href="javascript:void(0);" onclick="return OpenFeedbackDiv('.$event_id.');" data-toggle="tooltip" data-placement="top" title="Edit Event">
                            <span aria-hidden="true" class="glyphicon glyphicon-pencil"></span>
                        </a>';
                    }
                    else
                    {
                            echo '<a href="event-log.php?EID='.$EventIDS.'" data-toggle="tooltip" data-placement="top" title="Edit">
                                <span aria-hidden="true" class="glyphicon glyphicon-pencil"></span>
                            </a>';
                    }
                }
            }
            echo '    </td>
              </tr>';
        }
        ?>
            </tbody>
          </table>
        <?php
        } 
        if($paginationCount)
        {
        echo '<div class="clearfix"></div>';
        echo '<div class="col-lg-12 paddingR0 text-right">
                <table cellspacing="0" cellpadding="0" align="right">
                    <tbody>
                        <tr>
                            <td>Show</td>
                            <td style="width:10px;"></td>
                            <td class="pagination-dropdown">
                                <label class="select-box-lbl">
                                    <select class="form-control" name="show_records" onchange="changePagination(\'eventLogListing\',\'include_event_log.php\',\'\',this.value,\''.$order1.'\',\''.$sort_variable.'\')">';                            
                                    for($s=0;$s<count($GLOBALS['show_records_arr']);$s++)
                                    {
                                        if($_SESSION['per_page']==$GLOBALS['show_records_arr'][$s] || $_SESSION['per_page']==$GLOBALS["records_all"])
                                            echo '<option selected="selected" value="'.$GLOBALS['show_records_arr'][$s].'">'.$GLOBALS['show_records_arr'][$s].' Records</option>';
                                        else
                                            echo '<option value="'.$GLOBALS['show_records_arr'][$s].'">'.$GLOBALS['show_records_arr'][$s].' Records</option>';
                                    }
                                echo'</select>
                                </label>
                            </td>
                            <td style="width:10px;"></td>';
        if($recListCount<($start+PAGE_PER_NO))
            $pagesOf=($start+1).'-'.($recListCount).' of '.$recListCount;
        else
            $pagesOf=($start+1).'-'.($start+PAGE_PER_NO).' of '.$recListCount;
                        echo '<td>'.$pagesOf.'</td>';
        if($pageId>1)
        {
            echo '
                <td style="width:10px;"></td>
                <td align="right" onclick="changePagination(\'eventLogListing\',\'include_event_log.php\',\''.($pageId-1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" valign="middle"><input type="button" class="btn btn-prev" value="<"></td>';
        }
        else
        {
            echo '
                <td style="width:10px;"></td>
                <td align="right" valign="middle"><input type="button" class="btn" value="<"></td>';
        }
        if($pageId!=($paginationCount))
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input onclick="changePagination(\'eventLogListing\',\'include_event_log.php\',\''.($pageId+1).'\',\'\',\''.$order1.'\',\''.$sort_variable.'\')" type="button" class="btn btn-next" value=">"></td>';
        }
        else
        {
            echo '
                <td style="width:5px;"></td>
                <td valign="middle"><input type="button" class="btn" value=">"></td>';
        }
        echo '          </tr>
                    </tbody>
                </table>
            </div>';
        echo '<div class="clearfix"></div>';
    }
    ?>
<?php
}?>