<?php   require_once 'inc_classes.php';
        //require_once '../classes/locationsClass.php';
        //$locationsClass = new locationsClass();
?>
<?php
    $csv='';
    $bgColorCounter=1;
    /*$recArgs=$_SESSION['location_list_args'];
    $recArgs['pageSize']='all';
    $recListResponse= $locationsClass->LocationsList($recArgs);
    $recList=$recListResponse['data'];
    $recListCount=$recListResponse['count'];*/
	
	$payments = mysql_query("SELECT * FROM sp_payments where status='1'ORDER BY date_time DESC");

	$row_count = mysql_num_rows($payments);
	
    if($row_count > 0)
    {
        $datas .=  '<table cellpadding="1" cellspacing="1" align="left" border="1" id="mainTableBg">
                        <tr height="30">
							<td width="15%">Sr No.</td>
                            <td width="15%">Event Id</td>
							<td width="20%">Payment Date</td>
							<td width="15%">Amount</td>
							<td width="15%">Mode</td>
                        </tr>';
             //$i = 0;
            for($i=1; $i<=$row_count;)
			{
			while ($payment_rows = mysql_fetch_array($payments))
				{		
			
			$date_time = explode(" ",$payment_rows['date_time']);
			$exploded_date = $date_time[0];
			//$time = $date_time[1];
			$date = date('d-m-Y',strtotime($exploded_date));
			
			include "include/paging_script.php";
			
			 $datas .= '<tr id="'.$bgcolor.'" class="admin">                                   
                           <td>'.$i.'</td>';
             $datas .= '<td>'.$payment_rows['event_id'].'</td>';
			$datas .= '<td>'.$date.'</td>';
			$datas .= '<td>'.$payment_rows['amount'].'</td>';
			$datas .= '<td>'.$payment_rows['type'].'</td>';
			$datas .= '</tr>';
			$i++;
				}
			}
        }
        else
            $datas.='No record found related to your search criteria';

    $db->close();
    //echo $csv;
    $filepath="CSV/".time()."PaymentsList.xls";
	//echo $filepath;exit;
    $file=fopen($filepath,"w");
    fwrite($file,$datas);
    fclose($file);
    header("Content-Disposition: attachment; filename=PaymentsList_".date("Y-m-d").".xls");
    header("Content-Type: application/vnd.ms-excel");
    readfile($filepath);
    unlink($filepath);
?>