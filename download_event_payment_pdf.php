<?php  require_once 'inc_classes.php';
	   require_once 'classes/eventClass.php';
	   $eventClass=new eventClass();
	if($_GET['export'])
    {
        $folder="eventsPDF/";
        $tmpfname=trim($_GET['file']);
        if(file_exists($folder.$tmpfname))
        {	
            header("Cache-Control: ");// leave blank to avoid IE errors
            header("Pragma: ");// leave blank to avoid IE errors
            header('Content-Disposition: attachment; filename="'.$tmpfname.'"');
            header('Content-Type: application/xls');
            header('Content-Length: ' . filesize($folder.$tmpfname));
            readfile($folder.$tmpfname);
            unlink($folder.$tmpfname);
        }
    }
    else
    {
        //$data='';
        $eventid=$_POST['eventid'];
		//echo $payment_id;
		//$EventId=$EventDtls['event_code'];
		$fetch_event_id = mysql_query("SELECT * FROM sp_events WHERE event_id='$eventid'");
    	$get_event_number = mysql_fetch_array($fetch_event_id);
	    $event_code = $get_event_number['event_code'];
	    $patient_id = $get_event_number['patient_id'];
	    $bill_no_ref_no = $get_event_number['bill_no_ref_no'];
		
		
		
       	$datais = $_POST['html'];
        $folder="eventsPDF/";            
        $tmpfname ="PaymentSummary_".$bill_no_ref_no."_".$event_code."_".date("m-d-Y His").'.pdf';
        //$data="<html><meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\"><link href=\"css/bootstrap.css\" rel=\"stylesheet\" type=\"text/css\" /><link href=\"css/style.css\" rel=\"stylesheet\" type=\"text/css\" /><body style='color: #666666;font-size: 11px;text-decoration: none;'>";
         $html=$datais;
        //$data.='</body></html>';
        ini_set('max_execution_time',1000);
        define('_MPDF_PATH','classes/MPDF54/');
        include("classes/MPDF54/mpdf.php");
        //$html = $data; // file_get_contents('http://decizonsoft.com/persaf/health-records.html');;
        
        $mpdf = new mPDF('utf-8','','','',5,5,5,5,5,5,'P');//A4-L - second paramiter $mpdf=new mPDF('c'); 
        $mpdf->default_lineheight_correction = 2;
        $mpdf->autoPageBreak = true;
        $mpdf->AddPage();
      //  if(isset($_POST['type']) && $_POST['type'] != '' ){
            $javascript = 'this.print();';
            $mpdf->SetJS($javascript);     
       // }         
        $mpdf->WriteHTML($html);  
        $mpdf->Output($folder.$tmpfname,'F');
        echo $tmpfname;
	}
?>
