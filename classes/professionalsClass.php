<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class professionalsClass extends AbstractDB 
{
    private $result;
    public function __construct() 
    {
        parent::__construct();
        $this->result = NULL;
        $this->connect();
        return true;
    }
    public function close() 
    {
        parent::close();            
    }
    public function GetProfessionalpaymnet($arg)
    {
		$service_professional_id=$this->escape($arg['service_professional_id']);
		$GetOneProfessionalpaymentDtlsSql="SELECT * FROM sp_payments_received_by_professional WHERE professional_vender_id='".$service_professional_id."'";
		if($this->num_of_rows($this->query($GetOneProfessionalpaymentDtlsSql)))
        {
			
            $ProfessionalOtherDtls=$this->fetch_array($this->query($GetOneProfessionalpaymentDtlsSql));
            return $ProfessionalOtherDtls;
        }
        else 
            return 0;
    }
    public function daily_service_count()
    {
	mysql_connect("localhost", "root", " ") or
	mysql_select_db("hospitalguru_local");
	
	$date = date('d-m-Y');
	$new_date=date('Y-m-d H:i:s', strtotime($date));
	$new_date1 = date('Y-m-d H:i:s', strtotime($new_date . ' +1 days'));
	$today_date=date('Y-m-d', strtotime($date));
	$Previous_date = date('Y-m-d H:i:s', strtotime($new_date . ' -65 days'));
			
	$Current_call=$_GET['flag'];

	$Physician_assistant=0;
	$Physiotherapy=0;
	$Healthcare_attendants=0;
	$Nurse=0;
	$Laboratory_services=0;
	$Respiratory_care=0;
	$X_rayat_home=0;
	$Hca_package=0;
	$Medical_transportation=0;
	$Physiotherapy_New=0;
	$Assisted_living=0;
	$Physician_service=0;
	$Maid_service=0;
	$Total_Services=0;
	$plan_of_care=mysql_query("SELECT * FROM sp_event_plan_of_care  where added_date BETWEEN '$Previous_date%' AND '$new_date1%'");
			while($plan_of_care_detail=mysql_fetch_array($plan_of_care))
			{
				$service_date=$plan_of_care_detail['service_date'];
				$service_date_to=$plan_of_care_detail['service_date_to'];
				$event_requirement_id=$plan_of_care_detail['event_requirement_id'];
					$professional_vender_id=$plan_of_care_detail['professional_vender_id'];
				
				$event_requirement=mysql_query("SELECT * FROM sp_event_requirements where event_requirement_id='$event_requirement_id'") or die(mysql_error());
				$event_requirement_row = mysql_fetch_array($event_requirement);
				$service_id=$event_requirement_row['service_id'];
				$sub_service_id=$event_requirement_row['sub_service_id'];
				if($service_id!=10 AND $service_id!=6 AND $sub_service_id!=423)
				{
					
					
					
				$event_Service=mysql_query("SELECT * FROM sp_services where service_id='$service_id'") or die(mysql_error());
				$event_Service_row = mysql_fetch_array($event_Service);
				$service_title=$event_Service_row['service_title'];
					
				$begin = new DateTime($service_date);
				$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
				$end = new DateTime($end);
				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
				foreach($daterange as $date)
				{
					$date_service=$date->format("Y-m-d") ;
					if($date_service==$today_date)
					{
						//echo $date_service;
						if($service_id==2){$Physician_assistant++;}
					elseif($service_id==3){$Physiotherapy++;}
					elseif($service_id==4){$Healthcare_attendants++;}
					elseif($service_id==5){$Nurse++;}
					elseif($service_id==8){$Laboratory_services++;}
					elseif($service_id==11){$Respiratory_care++;}
					elseif($service_id==12){$X_rayat_home++;}
					elseif($service_id==13){$Hca_package++;}
					elseif($service_id==15){$Medical_transportation++;}
					elseif($service_id==16){$Physiotherapy_New++;}
					elseif($service_id==17){$Assisted_living++;}
					elseif($service_id==18){$Physician_service++;}
					elseif($service_id==19){$Maid_service++;}
					$count++;
					}
				}
				}
			}
			$txtMsg1 .= "Spero Home Healthcare,";
			$txtMsg1 .= "\n Total Services Count";
			$txtMsg1 .= "\n Physician Assistant-".$Physician_assistant.",";
			$txtMsg1 .= "\n Physiotherapy-".$Physiotherapy.",";
			$txtMsg1 .= "\n Healthcare Attendants-".$Healthcare_attendants.",";
			$txtMsg1 .= "\n Nurse-".$Nurse.",";
			$txtMsg1 .= "\n Laboratory Services-".$Laboratory_services.",";
			$txtMsg1 .= "\n Respiratory Care-".$Respiratory_care.",";
			$txtMsg1 .= "\n X-ray at home-".$X_rayat_home.",";
			$txtMsg1 .= "\n Hca Package-".$Hca_package.",";
			$txtMsg1 .= "\n Medical Transportation-".$Medical_transportation.",";
			$txtMsg1 .= "\n Physiotherapy New-".$Physiotherapy_New.",";
			$txtMsg1 .= "\n Physician Service-".$Physician_service.",";
			$txtMsg1 .= "\n Maid Service-".$Maid_service.",";
			$txtMsg1 .= "\n Total Services-".$Total_Services;
			          
			
			$mobile_no =  "8551995260";
			$curl = curl_init();
			$message = rawurlencode($txtMsg1);
			curl_setopt_array($curl, array(
			CURLOPT_URL => "http://chat.chatmybot.in/whatsapp/api/v1/sendmessage?access-token=4197-35YW4IZVOETDQT0MDI&phone=91-".$mobile_no."&content=".$message."&fileName=test.jpg&caption=testingonol&contentType=1",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "",
			));
		      
			$response = curl_exec($curl);
			$err = curl_error($curl);
			echo $response;
			curl_close($curl);
		      
			if ($err) {
			echo "cURL Error #:" . $err;
			} else {
			echo $response;
			}	
        
    }
    public function Professionals_Notifinction()
    {
	mysql_connect("localhost", "root", " ") or
	//die("Could not connect: " . mysql_error()); 
      // mysql_connect("localhost", "spero_pune", "Spero@Pune@2016") or
	//die("Could not connect: " . mysql_error());
        mysql_select_db("hospitalguru_local");	
        $preWhere="";
        $filterWhere="";
        $join="";
        $today = date("Y-m-d"); 
        //$preWhere=" AND (Actual_Service_date BETWEEN '$today 00:00:00' AND '$today 23:59:59' )"; 
        $preWhere=" AND service_date = '".$today."' "; 
        $ProfessionalsSql=mysql_query("SELECT t1.* FROM sp_detailed_event_plan_of_care as t1 WHERE 1 ".$preWhere." ");
        if($this->num_of_rows($ProfessionalsSql))
        { 
	//var_dump(mysql_fetch_array($ProfessionalsSql));
	while ($val_records=mysql_fetch_array($ProfessionalsSql)) 
	{    
		
		$Actual_Service_date=$val_records['Actual_Service_date'];
		$start_date=$val_records['start_date'];
		$end_date=$val_records['end_date'];
		$service_date=$val_records['service_date'];
		$service_date_to=$val_records['service_date_to'];
		
		
		$event_requirement_id=$val_records['event_requirement_id'];
		$event_id=$val_records['event_id'];
		$professional_vender_id=$val_records['professional_vender_id'];
		$plan_of_care_id=$val_records['plan_of_care_id'];
		$professional= mysql_query("SELECT * FROM sp_event_professional  where event_requirement_id='$event_requirement_id'");
		if(mysql_num_rows($professional) < 1 )
		{
		$professional_vender_id='';
		}
		else
		{
		$professional_new = mysql_fetch_array($professional) or die(mysql_error());
		$professional_vender_id=$professional_new['professional_vender_id'];
		$professional_name= mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$professional_vender_id'");
		$professional_name_abc = mysql_fetch_array($professional_name) or die(mysql_error());
		$name=$professional_name_abc['name'];
		$title=$professional_name_abc['title'];
		$first_name=$professional_name_abc['first_name'];
		$middle_name=$professional_name_abc['middle_name'];
		}
		
			
						
		$payments_event_code = mysql_query("SELECT * FROM sp_patients  where patient_id='$patient_id'");
		$row2 = mysql_fetch_array($payments_event_code) or die(mysql_error());
		$first_name=$row2['first_name'];
		$middle_name=$row2['middle_name'];
		$name=$row2['name'];
		$hhc_code=$row2['hhc_code'];
		$Patinet_name = $first_name." ".$name;
		

		$txtMsg = '';
                    	
		$txtMsg1 .= "\nDear ".$title." ".$first_name.",";
		$txtMsg1 .= "\nThis is reminder for your  service on ".$service_date." To ".$service_date_to." \n\n";
		$txtMsg1 .= "\nPatient Name: ".$Patinet_name." [".$hhc_code." ],";
		$txtMsg1 .= "\nEvent No: ".$event_code." ";
		$txtMsg1 .= "\n\nAddress : ".$residential_address.",";
		$txtMsg1 .= "\n\nService Name: ".$service_name." ";
		//$txtMsg1 .= "\nSub-Service Name: ".$sub_service_detail." ";
		//$txtMsg1 .= "\n".$sub_service_detail." ";
		$txtMsg1 .= "\n\nPayment Status: ".$payment_status." ";
                    	$txtMsg1.= "\n\nIf you have any query please call on 7620400100.";
		$txtMsg1 .= "\n\nThank You.";
		$txtMsg1 .= "\nSpero";
		var_dump($txtMsg1);die();
		
		$mobile_no =  "8551995260";
			$curl = curl_init();
			$message = rawurlencode($txtMsg1);
			curl_setopt_array($curl, array(
			CURLOPT_URL => "http://chat.chatmybot.in/whatsapp/api/v1/sendmessage?access-token=4197-35YW4IZVOETDQT0MDI&phone=91-".$mobile_no."&content=".$message."&fileName=test.jpg&caption=testingonol&contentType=1",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "",
			));
		      
			$response = curl_exec($curl);
			$err = curl_error($curl);
			echo $response;
			curl_close($curl);
		      
			if ($err) {
			echo "cURL Error #:" . $err;
			} else {
			echo $response;
			}

	}
	
        }
        	
    }
    public function ProfessionalsList_Active_Inactive($arg)
    {
        $preWhere="";
        $filterWhere="";
        $join="";
        $search_value= $this->escape($arg['search_Value']);
        $ref_type_value= $this->escape($arg['ref_type_Value']);
        $location_value= $this->escape($arg['location_Value']);
        $service_Value= $this->escape($arg['service_Value']);
        $isPhysiotherapy=$this->escape($arg['isPhysiotherapy']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
		$documentlistFlag = $this->escape($arg['documentListFlag']);
		$leaveListFlag = $this->escape($arg['leaveListFlag']);
		$searchfromDate  = $this->escape($arg['searchfromDate']);
		$searchToDate    = $this->escape($arg['searchToDate']);
		$isActiveOnly = $this->escape($arg['isActiveOnly']);

        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere=" AND (professional_code LIKE '%".$search_value."%' OR name LIKE '%".$search_value."%'  OR first_name LIKE '%".$search_value."%' OR middle_name LIKE '%".$search_value."%' OR email_id LIKE '%".$search_value."%' OR phone_no LIKE '%".$search_value."%' OR mobile_no LIKE '%".$search_value."%' OR work_email_id LIKE '%".$search_value."%' OR work_phone_no LIKE '%".$search_value."%' OR google_home_location LIKE '%".$search_value."%' OR google_work_location LIKE '%".$search_value."%' )"; 
        }
        if(!empty($ref_type_value) && $ref_type_value !='null')
        {
           $preWhere .=" AND t1.reference_type='".$ref_type_value."'"; 
        }
        if(!empty($location_value) && $location_value !='null')
        {
           $preWhere .=" AND t1.location_id='".$location_value."'";   
        }
        if(!empty($service_Value) && $service_Value !='null')
        {
           $preWhere .=" AND t2.service_id='".$service_Value."'";   
        }
        
        if(!empty($isPhysiotherapy) && $isPhysiotherapy !='null')
        {
           $preWhere .=" AND t2.service_id='16'";  
        }
        
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .=" ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        if(!empty($isTrash) && $isTrash !='null')
        {
           $preWhere .=" AND t1.status='3'"; 
        } else if ($isActiveOnly) {
			$preWhere .=" AND t1.status IN ('1','2') AND t1.document_status='1'";
		} else {
          $preWhere .=" AND t1.status IN ('1','2')";   
		}
		

		if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(pl.date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
			if ($leaveListFlag) {
				$preWhere .= " AND DATE_FORMAT(pl.date,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
			}
            
        }
		
		if ($documentlistFlag) {
			$join = " INNER JOIN sp_professional_documents pd ON pd.professional_id = t2.service_professional_id";
			$preWhere .=" AND t1.status = '1'";
		}
		
		if ($leaveListFlag) {
			$join = " INNER JOIN sp_professional_weekoff pl ON pl.service_professional_id = t2.service_professional_id";
			$preWhere .=" AND t1.status = '1'";
		}
		
		$groupBy = " GROUP BY t1.service_professional_id";

        //$ProfessionalsSql="SELECT service_professional_id FROM sp_service_professionals WHERE 1 ".$preWhere." ".$filterWhere." ";
        $ProfessionalsSql="SELECT t1.service_professional_id,t1.mobile_no FROM sp_service_professionals as t1 LEFT JOIN sp_professional_services as t2 ON t1.service_professional_id = t2.service_professional_id " . $join . " WHERE 1 ".$preWhere."  " . $groupBy . " ".$filterWhere."";
		

		//echo '<pre>';
		//print_r($ProfessionalsSql);
		//echo '</pre>';


        $this->result = $this->query($ProfessionalsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($ProfessionalsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT service_professional_id,
                    professional_code,
                    reference_type,
                    title,
                    name,
                    first_name,
                    middle_name,
                    mobile_no,
		    email_id,
                    work_email_id,
                    work_phone_no,
                    location_id,
                    location_id_home,
                    set_location,
                    address,
                    status,
                    isDelStatus,
                    added_date,
                    google_home_location,
                    google_work_location,
                    document_status,
                    (CASE
                        WHEN document_status = '1' THEN 'Verified'
                        WHEN document_status = '2' THEN 'Need more details'
                        WHEN document_status = '3' THEN 'Rejected'
                        WHEN document_status = '4' THEN 'In progress'
                    END) AS documentStatusVal,
                    reg_source,
                    (CASE
                        WHEN reg_source = '1' THEN 'System'
                        WHEN reg_source = '2' THEN 'App'
                    END) AS regSourceVal
                    FROM sp_service_professionals 
                    WHERE service_professional_id = '" . $val_records['service_professional_id'] . "'";
                $RecordResult = $this->fetch_array($this->query($RecordSql));
                
                 // If Work Mobile Number Not Available
                
                if(empty($RecordResult['mobile_no']))
                    $RecordResult['mobile_no']='Not Available';
                
                // If Address Not Available
                
                if(empty($RecordResult['address']))
                    $RecordResult['address']='Not Available';
                
                // If Work Mobile Number Not Available
                
                if(empty($RecordResult['mobile_no']))
                    $RecordResult['mobile_no']='Not Available';
                
                // Getting User Type
                if(!empty($RecordResult['reference_type']))
                {
                    $ProfessionalTypeArr=array(1=>'Professional',2=>'Vendor');
                    $RecordResult['typeVal']=$ProfessionalTypeArr[$RecordResult['reference_type']];
                }
                // Getting Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                //Getting Location Details
                if(!empty($RecordResult['location_id']))
                {
                    $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$RecordResult['location_id']."'";
                    $LocationDtls=$this->fetch_array($this->query($LocationSql));
                    $RecordResult['locationNm']=$LocationDtls['location']; 
                    $RecordResult['LocationPinCode']=$LocationDtls['pin_code']; 
                }
                
                //Getting Services
                $arr['service_professional_id']=$RecordResult['service_professional_id'];
                $Services=$this->GetAssignServicesByProfessional($arr);
                if(!empty($Services))
                    $RecordResult['Services']=$Services;
                else 
                   $RecordResult['Services']="Not Available";
                
                $this->resultProfessional[]=$RecordResult;
                
                unset($arr);
                unset($RecordResult['Services']);
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultProfessional))
        {
            $resultArray['data']=$this->resultProfessional;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function ProfessionalsList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $join="";
        $search_value= $this->escape($arg['search_Value']);
        $ref_type_value= $this->escape($arg['ref_type_Value']);
        $location_value= $this->escape($arg['location_Value']);
        $service_Value= $this->escape($arg['service_Value']);
        $isPhysiotherapy=$this->escape($arg['isPhysiotherapy']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
		$documentlistFlag = $this->escape($arg['documentListFlag']);
		$leaveListFlag = $this->escape($arg['leaveListFlag']);
		$searchfromDate  = $this->escape($arg['searchfromDate']);
		$searchToDate    = $this->escape($arg['searchToDate']);
		$isActiveOnly = $this->escape($arg['isActiveOnly']);

        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere=" AND (professional_code LIKE '%".$search_value."%' OR name LIKE '%".$search_value."%'  OR first_name LIKE '%".$search_value."%' OR middle_name LIKE '%".$search_value."%' OR email_id LIKE '%".$search_value."%' OR phone_no LIKE '%".$search_value."%' OR mobile_no LIKE '%".$search_value."%' OR work_email_id LIKE '%".$search_value."%' OR work_phone_no LIKE '%".$search_value."%' OR google_home_location LIKE '%".$search_value."%' OR google_work_location LIKE '%".$search_value."%' )"; 
        }
        if(!empty($ref_type_value) && $ref_type_value !='null')
        {
           $preWhere .=" AND t1.reference_type='".$ref_type_value."'"; 
        }
        if(!empty($location_value) && $location_value !='null')
        {
           $preWhere .=" AND t1.location_id='".$location_value."'";   
        }
        if(!empty($service_Value) && $service_Value !='null')
        {
           $preWhere .=" AND t2.service_id='".$service_Value."'";   
        }
        
        if(!empty($isPhysiotherapy) && $isPhysiotherapy !='null')
        {
           $preWhere .=" AND t2.service_id='16'";  
        }
        
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .=" ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        if(!empty($isTrash) && $isTrash !='null')
        {
           $preWhere .=" AND t1.status='3'"; 
        } else if ($isActiveOnly) {
			$preWhere .=" AND t1.status IN ('1')";
		} else {
          $preWhere .=" AND t1.status IN ('1','2')";   
		}
		

		if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(pl.date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
			if ($leaveListFlag) {
				$preWhere .= " AND DATE_FORMAT(pl.date,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
			}
            
        }
		
		if ($documentlistFlag) {
			$join = " INNER JOIN sp_professional_documents pd ON pd.professional_id = t2.service_professional_id";
			$preWhere .=" AND t1.status = '1'";
		}
		
		if ($leaveListFlag) {
			$join = " INNER JOIN sp_professional_weekoff pl ON pl.service_professional_id = t2.service_professional_id";
			$preWhere .=" AND t1.status = '1'";
		}
		
		$groupBy = " GROUP BY t1.service_professional_id";

        //$ProfessionalsSql="SELECT service_professional_id FROM sp_service_professionals WHERE 1 ".$preWhere." ".$filterWhere." ";
        $ProfessionalsSql="SELECT t1.service_professional_id FROM sp_service_professionals as t1 LEFT JOIN sp_professional_services as t2 ON t1.service_professional_id = t2.service_professional_id " . $join . " WHERE 1 ".$preWhere."  " . $groupBy . " ".$filterWhere."";
		

		//echo '<pre>';
		//print_r($ProfessionalsSql);
		//echo '</pre>';


        $this->result = $this->query($ProfessionalsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($ProfessionalsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT service_professional_id,
                    professional_code,
                    reference_type,
                    title,
                    name,
                    first_name,
                    middle_name,
                    mobile_no,
		    email_id,
                    work_email_id,
                    work_phone_no,
                    location_id,
                    location_id_home,
                    set_location,
                    address,
                    status,
                    isDelStatus,
                    added_date,
                    google_home_location,
                    google_work_location,
                    document_status,
                    (CASE
                        WHEN document_status = '1' THEN 'Verified'
                        WHEN document_status = '2' THEN 'Need more details'
                        WHEN document_status = '3' THEN 'Rejected'
                        WHEN document_status = '4' THEN 'In progress'
                    END) AS documentStatusVal,
                    reg_source,
                    (CASE
                        WHEN reg_source = '1' THEN 'System'
                        WHEN reg_source = '2' THEN 'App'
                    END) AS regSourceVal
                    FROM sp_service_professionals 
                    WHERE service_professional_id = '" . $val_records['service_professional_id'] . "'";
                $RecordResult = $this->fetch_array($this->query($RecordSql));
                
                 // If Work Mobile Number Not Available
                
                if(empty($RecordResult['mobile_no']))
                    $RecordResult['mobile_no']='Not Available';
                
                // If Address Not Available
                
                if(empty($RecordResult['address']))
                    $RecordResult['address']='Not Available';
                
                // If Work Mobile Number Not Available
                
                if(empty($RecordResult['mobile_no']))
                    $RecordResult['mobile_no']='Not Available';
                
                // Getting User Type
                if(!empty($RecordResult['reference_type']))
                {
                    $ProfessionalTypeArr=array(1=>'Professional',2=>'Vendor');
                    $RecordResult['typeVal']=$ProfessionalTypeArr[$RecordResult['reference_type']];
                }
                // Getting Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                //Getting Location Details
                if(!empty($RecordResult['location_id']))
                {
                    $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$RecordResult['location_id']."'";
                    $LocationDtls=$this->fetch_array($this->query($LocationSql));
                    $RecordResult['locationNm']=$LocationDtls['location']; 
                    $RecordResult['LocationPinCode']=$LocationDtls['pin_code']; 
                }
                
                //Getting Services
                $arr['service_professional_id']=$RecordResult['service_professional_id'];
                $Services=$this->GetAssignServicesByProfessional($arr);
                if(!empty($Services))
                    $RecordResult['Services']=$Services;
                else 
                   $RecordResult['Services']="Not Available";
                
                $this->resultProfessional[]=$RecordResult;
                
                unset($arr);
                unset($RecordResult['Services']);
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultProfessional))
        {
            $resultArray['data']=$this->resultProfessional;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetProfessionalById($arg)
    {
        $service_professional_id=$this->escape($arg['service_professional_id']);
        $GetOneProfessionalSql="SELECT service_professional_id,professional_code,reference_type,title,Job_type,name,first_name,middle_name,email_id,phone_no,mobile_no,dob,address,work_email_id,work_phone_no,work_address,location_id,location_id_home,set_location,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date,google_home_location,google_work_location,Physio_Rate FROM sp_service_professionals WHERE service_professional_id='".$service_professional_id."'";
        if($this->num_of_rows($this->query($GetOneProfessionalSql)))
        {
            $Professional=$this->fetch_array($this->query($GetOneProfessionalSql));
            // Getting User Type
            if(!empty($Professional['reference_type']))
            {
                $ProfessionalTypeArr=array(1=>'Professional',2=>'Vendor');
                $Professional['typeVal']=$ProfessionalTypeArr[$Professional['reference_type']];
            }
            // Getting Location Name
            
            if(!empty($Professional['location_id']))
            {
               $LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$Professional['location_id']."'";
               $LocationDtls=$this->fetch_array($this->query($LocationSql));
               $Professional['locationNm']=$LocationDtls['location']; 
               $Professional['LocationPinCode']=$LocationDtls['pin_code']; 
            }
            
            // Getting Status
            if(!empty($Professional['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $Professional['statusVal']=$StatusArr[$Professional['status']];
            }
            
            // Getting Added User Name 
            if(!empty($Professional['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Professional['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $Professional['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($Professional['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$Professional['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $Professional['last_modified_by']=$ModifiedUser['name'];
            }
            
            //Getting Services
            $arr['service_professional_id']=$Professional['service_professional_id'];
            $Professional['Services']=$this->GetAssignServicesByProfessional($arr);
            unset($arr);   
            return $Professional;
        }
        else 
            return 0;            
	}
	
	/**
	 *
	 * This function is used for add professional details
	 *
	 * @param array $arg
	 *
	 * @return int $recordId
	 *
	 */
    public function AddProfessional($arg)
    {
		$service_professional_id = $this->escape($arg['service_professional_id']);
		// Generate Random Number      
		$GetMaxRecordIdSql = "SELECT MAX(service_professional_id) AS MaxId FROM sp_service_professionals";
		$getMaxRecordId = 0;
		if ($this->num_of_rows($this->query($GetMaxRecordIdSql))) {
			$MaxRecord = $this->fetch_array($this->query($GetMaxRecordIdSql));
			$getMaxRecordId = $MaxRecord['MaxId'];
		}
		$prefix = $GLOBALS['ProfPrefix'];
		$ProfessionalCode = Generate_Number($prefix,$getMaxRecordId);

		if (!empty($service_professional_id) && $service_professional_id != '') {
			// Get professional details
			$profDtls = $this->GetProfessionalById($arg);
			$ChkProfessionalSql = "SELECT service_professional_id FROM sp_service_professionals WHERE  mobile_no = '" . $arg['mobile_no'] . "' AND status != '3' AND service_professional_id != '" . $service_professional_id . "'";
		} else {
			$ChkProfessionalSql = "SELECT service_professional_id FROM sp_service_professionals WHERE mobile_no = '" . $arg['mobile_no'] . "' AND status != '3' AND professional_code != '" . $ProfessionalCode . "'";   
		}

		if ($this->num_of_rows($this->query($ChkProfessionalSql)) == 0) {
			$insertData = array();
			if (empty($service_professional_id)) {
				$insertData['professional_code'] = $ProfessionalCode;
			}
			$insertData['reference_type']       = $this->escape($arg['reference_type']);
			$insertData['title']                = $this->escape($arg['title']);
			$insertData['Job_type']             = $this->escape($arg['Job_type']);
			$insertData['name']                 = $this->escape($arg['name']);
			$insertData['first_name']           = $this->escape($arg['first_name']);
			$insertData['middle_name']          = $this->escape($arg['middle_name']);
			$insertData['email_id']             = $this->escape($arg['email_id']);
			$insertData['phone_no']             = $this->escape($arg['phone_no']);
			$insertData['mobile_no']            = $this->escape($arg['mobile_no']);
			$insertData['dob']                  = $this->escape($arg['dob']);
			$insertData['address']              = $this->escape($arg['address']);
			$insertData['work_email_id']        = $this->escape($arg['work_email_id']);
			$insertData['work_phone_no']        = $this->escape($arg['work_phone_no']);
			$insertData['work_address']         = $this->escape($arg['work_address']);
			$insertData['location_id']          = $this->escape($arg['location_id']);
			$insertData['location_id_home']     = $this->escape($arg['location_id_home']);
			$insertData['set_location']         = $this->escape($arg['set_location']);
			$insertData['google_home_location'] = $this->escape($arg['google_home_location']);
			$insertData['google_work_location'] = $this->escape($arg['google_work_location']);
			$insertData['Physio_Rate']          = $this->escape($arg['Physio_Rate']);
			$insertData['last_modified_by']     = $this->escape($arg['last_modified_by']);
			$insertData['last_modified_date']   = $this->escape($arg['last_modified_date']);
			
			/*          get lettitude/ langitude          */
			if ($arg['set_location'] == '2') {
				$locs            = $arg['location_id'];
				$add             = $arg['work_address'];
				$google_location = $arg['google_work_location'];
			} else {
				$locs            = $arg['location_id_home'];
				$add             = $arg['address'];
				$google_location = $arg['google_home_location'];
			}
			$region = 'IND';
			if ($google_location == '') {
				$select_locationd = "select location from sp_locations where location_id = '".$locs."'";
				$valLocation = $this->fetch_array($this->query($select_locationd));
				$location = $valLocation['location'];
				$mainAddress = $add.','.$location.', Pune, Maharashtra,India';                
				$address = str_replace(" ", "+", $mainAddress);
			} else {
				$address = str_replace(" ", "+", $google_location);
			}
			
			$json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyAqSFjKrqU52WGRggTJLD6QkZvOQeZp4bI&address=$address&sensor=false&region=$region");
			$json = json_decode($json);

			$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
			$long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

			$insertData['lattitude'] = $lat;
			$insertData['langitude'] = $long;
			/*          get lettitude/ langitude          */
			$RecordId = '';
			if (!empty($service_professional_id)) {
				$where = "service_professional_id ='" . $service_professional_id . "'";
				$RecordId = $this->query_update('sp_service_professionals', $insertData, $where); 
				$serArrr['professional_id'] = $service_professional_id;
			} else {
				$insertData['document_status'] = 1; // TODO : Need to be set 2 when we introduce add document functionality from admin side
				$insertData['status']     = $this->escape($arg['status']);
				$insertData['added_by']   = $this->escape($arg['added_by']);
				$insertData['added_date'] = $this->escape($arg['added_date']);
				$RecordId=$this->query_insert('sp_service_professionals', $insertData);
				$serArrr['professional_id'] = $RecordId;
			}
			
			if (!empty($RecordId)) {
				// Add activity log while adding professional
				$param = array();
                $param['professional_id']   = $serArrr['professional_id'];
                $param['professional_dtls'] = $profDtls;
                $param['record_data']   = $insertData;
                $this->addProfessionalActivity($param);
				unset($param);

				$serDataArr = array();
				$serDataArr['added_by']        = $arg['added_by'];
				$serDataArr['service_id']      = $arg['service_id'];
				$serDataArr['professional_id'] = (!empty($service_professional_id) ? $service_professional_id : $RecordId);
				// Add Professional services in database
				$serviceId = $this->AddServices($serDataArr);
				unset($serDataArr['service_id']);

				if (!empty($serviceId)) {
					$serDataArr['service_id'] = $serviceId;
					$serDataArr['sub_service_id'] = $arg['sub_service_id'];
					// Add Professional sub services in database
					$this->AddServices($serDataArr, 'SubService');
				}
				return $RecordId;
			} else {
				return 0;
			}
		}
		else {
			return 0;
		}
	}
	
	/**
	 *
	 * This function is used for change status of professional
	 *
	 * @param array $arg
	 *
	 * @return int $recordId
	 *
	 */
    public function ChangeStatus($arg)
    {
        $service_professional_id = $this->escape($arg['service_professional_id']);
        $status                  = $this->escape($arg['status']);
        $pre_status              = $this->escape($arg['curr_status']);
        $istrashDelete           = $this->escape($arg['istrashDelete']);
        $login_user_id           = $this->escape($arg['login_user_id']);
        $ChkProfessionaSql = "SELECT service_professional_id,
			status,
			isDelStatus,
			last_modified_by,
			last_modified_date
		FROM sp_service_professionals 
		WHERE service_professional_id = '" . $service_professional_id . "'";

        if ($this->num_of_rows($this->query($ChkProfessionaSql))) {
			$profDtls = $this->fetch_array($this->query($ChkProfessionaSql));
            if ($istrashDelete) {
                // Delete Professional Other Details
                $DelProfOtherDtls = "DELETE FROM sp_service_professional_details WHERE service_professional_id = '" . $service_professional_id . "'";
                $this->query($DelProfOtherDtls);
                $UpdateStatusSql="DELETE FROM sp_service_professionals WHERE service_professional_id = '" . $service_professional_id . "'";
            } else {
                // Update Professional Other Details
                $UpdateProfOtherDtls = "UPDATE sp_service_professional_details SET status = '" . $status . "', last_modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE service_professional_id = '" . $service_professional_id . "'";
                $this->query($UpdateProfOtherDtls);
                
                $UpdateStatusSql = "UPDATE sp_service_professionals SET status = '" . $status . "', isDelStatus = '" . $pre_status . "', last_modified_by = '" . $login_user_id . "', last_modified_date = '" . date('Y-m-d H:i:s') . "' WHERE service_professional_id = '" . $service_professional_id . "'";
            }
			$RecordId = $this->query($UpdateStatusSql);
			
			if (!empty($RecordId)) {
				$insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '6';
                $insertActivityArr['module_name']   = 'Manage Professionals';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Professional details modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";

                $activityDesc .= "status is change from " . $profDtls['status'] . " to " . $status . "\r\n";
                $activityDesc .= "isDelStatus is change from " . $profDtls['isDelStatus'] . " to " . $pre_status . "\r\n";
                $activityDesc .= "modified_by is change from " . $profDtls['modified_by'] . " to " . $login_user_id . "\r\n";
                $activityDesc .= "last_modified_date is change from " . $profDtls['last_modified_date'] . " to " . date('Y-m-d H:i:s') . "\r\n";

                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
			}
            return $RecordId;
        }
        else {
			return 0;
		}
    }
    public function GetProfessionalOtherDtlsById($arg)
    {
        $service_professional_id=$this->escape($arg['service_professional_id']);
        $GetOneProfessionalOtherDtlsSql="SELECT detail_id,service_professional_id,qualification,specialization,skill_set,work_experience,hospital_attached_to,pancard_no,service_tax,added_by,added_date,last_modified_by,last_modified_date FROM sp_service_professional_details WHERE service_professional_id='".$service_professional_id."'";
        if($this->num_of_rows($this->query($GetOneProfessionalOtherDtlsSql)))
        {
            $ProfessionalOtherDtls=$this->fetch_array($this->query($GetOneProfessionalOtherDtlsSql));
            return $ProfessionalOtherDtls;
        }
        else 
            return 0;
    }
    public function AddProfessionalOtherDtls($arg)
    {
        $service_professional_id=$this->escape($arg['service_professional_id']);
        $detail_id=$this->escape($arg['detail_id']);
        
        if(!empty($detail_id) && $detail_id !='')
             $ChkProfessionalOtherDtlsSql="SELECT detail_id FROM sp_service_professional_details WHERE service_professional_id='".$service_professional_id."' AND detail_id !='".$detail_id."'";
        else 
            $ChkProfessionalOtherDtlsSql="SELECT detail_id FROM sp_service_professional_details WHERE service_professional_id='".$service_professional_id."'"; 
      
      if($this->num_of_rows($this->query($ChkProfessionalOtherDtlsSql))==0)
      {
        $insertData['service_professional_id']=$this->escape($arg['service_professional_id']); 
        $insertData['qualification']=$this->escape($arg['qualification']);
        $insertData['specialization']=$this->escape($arg['specialization']);
        $insertData['skill_set']=$this->escape($arg['skill_set']);
        $insertData['work_experience']=$this->escape($arg['work_experience']);
        $insertData['hospital_attached_to']=$this->escape($arg['hospital_attached_to']);
        $insertData['pancard_no']=$this->escape($arg['pancard_no']);
        if(!empty($detail_id))
        {
         $where = "detail_id='".$detail_id."'";
         $RecordId=$this->query_update('sp_service_professional_details',$insertData,$where); 
        }
        else 
        {
         $insertData['status']=$this->escape($arg['status']);
         $insertData['added_by']=$this->escape($arg['added_by']);
         $insertData['added_date']=$this->escape($arg['added_date']);
         $RecordId=$this->query_insert('sp_service_professional_details',$insertData);
        }
      }    
    }
    public function Add_emp_Spero($arr_new)
    {
         $insertData['mobile_no']=$this->escape($arr_new['mobile_no']);
         $insertData['DOJ']=$this->escape($arr_new['DOJ']);
         $insertData['status']=$this->escape($arr_new['status']);
         $insertData['fname']=$this->escape($arr_new['fname']);
         $insertData['birth_date']=$this->escape($arr_new['birth_date']);
         $RecordId=$this->query_insert('sp_emp_spero',$insertData);
    
    }
    public function GetAssignServicesByProfessional($arg)
    {
        $service_professional_id=$this->escape($arg['service_professional_id']);
        $GetServicesSql="SELECT t2.service_id,t1.service_title FROM sp_professional_services t2 INNER JOIN sp_services t1 ON t2.service_id=t1.service_id  WHERE t2.service_professional_id='".$service_professional_id."'";
        if($this->num_of_rows($this->query($GetServicesSql)))
        {
            $ResultData=$this->fetch_all_array($GetServicesSql);
            
            $AllServices="";
            
            foreach($ResultData as $key=>$valServices)
            {
                $AllServices .=$valServices['service_title'].",";
            }
            
            $Services=substr_replace($AllServices, "", -1);
            
            if(!empty($Services))
                return $Services;
            else 
                return 0;   
        }
        else 
            return 0;
       
    }
    public function API_GetProfessionalById_document_list($arg)
    {
        $service_professional_id=$this->escape($arg['service_professional_id']);
        $GetServicesSql="SELECT t2.service_id,t1.service_title FROM sp_professional_services t2 INNER JOIN sp_services t1 ON t2.service_id=t1.service_id  WHERE t2.service_professional_id='".$service_professional_id."'";
        if($this->num_of_rows($this->query($GetServicesSql)))
        {
            $ResultData=$this->fetch_all_array($GetServicesSql);
            
            $AllServices="";
            
             foreach($ResultData as $key=>$valServices)
            {
                $AllServices .=$valServices['service_id'].",";
            }
            
            $Services=substr_replace($AllServices, "", -1);
            
			
			
            if(!empty($Services))
                return $Services;
            else 
                return 0;   
        }
        else 
            return 0;
    }
	
	/**
	* This function is used for get all sub services based on service id
	*/
	public function GetAllServicesByServiceId($serviceId)
	{
		if (!empty($serviceId)) {
			$subServiceSql = "SELECT sub_service_id, recommomded_service FROM sp_sub_services WHERE service_id = '" . $serviceId . "' AND status = '1'";
			if ($this->num_of_rows($this->query($subServiceSql))) {
				$subServiceList = $this->fetch_all_array($subServiceSql);
				
				if (!empty($subServiceList)) {
					return $subServiceList;
				} else {
					return 0;
				}
			}
		} else {
			return 0;
		}
	} 
	
	/**
	 * 
	 * This function is useful for add  service / sub service data
	 *
	 * @param array $arg
	 *
	 * @param string $serviceType
	 *
	 * @return int 0|1
	*/
	public function AddServices($arg, $serviceType = 'Service') 
	{
		if ($serviceType != 'Service') {
			if (!empty($arg['sub_service_id']) && !empty($arg['service_id'])  && !empty($arg['professional_id'])) {
				//Check is it any sub service data present in table
				$checkSubServiceExist = "SELECT professional_services_subservices_id FROM sp_professional_sub_services WHERE service_professional_id = '" . $arg['professional_id'] . "'";
				if (mysql_num_rows($this->query($checkSubServiceExist))) {
					// First delete all data from sub service table w.r.t professional
					$deleteSubServiceRecords = "DELETE FROM sp_professional_sub_services WHERE service_professional_id ='" . $arg['professional_id'] . "'";
					$this->query($deleteSubServiceRecords);
				}
				// Add data in sub service table
				if (!empty($arg['sub_service_id']) && count($arg['sub_service_id']) > 0) {
					$insertSubServiceData = array();
					$insertSubServiceData['service_id']              = $arg['service_id'];
					$insertSubServiceData['service_professional_id'] = $arg['professional_id'];
					$subServiceArr = array();
					for ($i = 0; $i < count($arg['sub_service_id']); $i++) {
						$insertSubServiceData['sub_service_id']      = $arg['sub_service_id'][$i];
						// Add data in service table
						$subServiceId = $this->query_insert('sp_professional_sub_services', $insertSubServiceData);
						if (!empty($subServiceId)) {
							$subServiceArr[] = $insertSubServiceData['service_id'];
						}
					}

					if (!empty($subServiceArr)) {
						// add activity log for sub services
						$param = array();
						$param['professional_id'] = $arg['professional_id'];
						$param['service_id']      = $arg['service_id'];
						$param['sub_service_id']  = $arg['sub_service_id'];
						$param['subServiceArr'] = $subServiceArr;
						$this->addServiceActivity($param);
						unset($param);
					}
					return 1;
				} else {
					return 0;
				}
			}
		} else {
			if (!empty($arg['service_id']) && !empty($arg['professional_id'])) {
				//Check is it any service data present in table
				$checkServiceExist = "SELECT professional_service_id FROM sp_professional_services WHERE service_professional_id ='" . $arg['professional_id'] . "'";
				if (mysql_num_rows($this->query($checkServiceExist))) {
					// First delete all data from service table w.r.t professional
					$deleteServiceRecords = "DELETE FROM sp_professional_services WHERE service_professional_id ='" . $arg['professional_id'] . "'";
					$this->query($deleteServiceRecords);
				}
				
				$insertData = array();
                $insertData['service_id']              = $arg['service_id'];
                $insertData['service_professional_id'] = $arg['professional_id'];
                $insertData['status']                  = 1;
                $insertData['added_by']                = $arg['added_by'];
                $insertData['added_date']              = date('Y-m-d H:i:s');
                $insertData['modified_by']             = $arg['added_by'];
                $insertData['last_modified_date']      = date('Y-m-d H:i:s');
				
				// Add data in service table
                $serviceRecordId = $this->query_insert('sp_professional_services', $insertData);
				if (!empty($serviceRecordId)) {
					// add activity log for sub services
					// add activity log for sub services
					$param = array();
					$param['professional_id'] = $arg['professional_id'];
					$param['service_id']      = $arg['service_id'];
					$this->addServiceActivity($param);
					unset($param);
					return $arg['service_id'];
				} else {
					return 0;
				}
			}
		}
	}
	/**
	* This function is used to get details service / sub service details
	*/
	public function GetProfessionalServices($arg)
    {
        $service_professional_id = $this->escape($arg['service_professional_id']);
		$serviceType = $this->escape($arg['serviceType']);
		$serviceId = $this->escape($arg['service_id']);
		$tableName = (!empty($serviceType) && $serviceType == 'subService') ? 'sp_professional_sub_services t1' : 'sp_professional_services t1';
		$columnName = (!empty($serviceType) && $serviceType == 'subService') ? 't1.professional_services_subservices_id, t1.sub_service_id, t2.recommomded_service' : 't1.professional_service_id, t1.service_id, t2.service_title';
		$join = (!empty($serviceType) && $serviceType == 'subService') ? ' INNER JOIN sp_sub_services t2 ON t1.sub_service_id = t2.sub_service_id' : ' INNER JOIN sp_services t2 ON t1.service_id = t2.service_id';
		$whereClause = (!empty($serviceType) && $serviceType == 'subService') ? "WHERE t1.service_professional_id = '" . $service_professional_id . "' AND t1.service_id = '" . $serviceId ."'"  : " WHERE t1.service_professional_id = " . $service_professional_id;
		
		$GetServiceDtlsSql = "SELECT  " . $columnName . " FROM " . $tableName . " " . $join . " " . $whereClause . "";
		
		// echo '<pre>';
		// print_r($GetServiceDtlsSql);
		// echo '</pre>';
		// exit;
	
        if($this->num_of_rows($this->query($GetServiceDtlsSql)))
        {
            $serviceDtls = ($serviceType == 'subService') ? $this->fetch_all_array($GetServiceDtlsSql) : $this->fetch_array($this->query($GetServiceDtlsSql));
            return $serviceDtls;
        }
        else 
            return 0;
    }
	
	/**
	* This function is used to get professional bank details by professional id
	*/
	public function getProfBankDtlsByProfId($profId)
    {
		if (!empty($profId)) {
			$profBankDtlsSql = "SELECT * FROM sp_bank_details WHERE Professional_id = '" . $profId . "'";
			if ($this->num_of_rows($this->query($profBankDtlsSql))) {
				$getBankDtls = $this->fetch_array($this->query($profBankDtlsSql));
				if (!empty($getBankDtls)) {
					return $getBankDtls;
				} else {
					return 0;
				}
			}
		} else {
			return 0;
		}
	}
	
	/**
	* This function is used to add / update professional bank details
	*/
	public function addProfBankDtls($arg)
    {
		if (!empty($arg)) {
			$insertData = array();
			$insertData['Professional_id'] = $arg['Professional_id'];
			$insertData['Account_name']    = $arg['Account_name'];
			$insertData['Account_number']  = $arg['Account_number'];
			$insertData['Bank_name']       = $arg['Bank_name'];
			$insertData['Branch']          = $arg['Branch'];
			$insertData['IFSC_code']       = $arg['IFSC_code'];
			$insertData['Account_type']    = $arg['Account_type'];
		
			// Add data in service table
			if (!empty($arg['id'])) {
				// Get bank details
				$bankDtls = $this->getProfBankDtlsById($arg['id']);
				$where = "id ='" . $arg['id'] . "'";
                $recordId = $this->query_update('sp_bank_details', $insertData, $where);
			} else {
				$recordId = $this->query_insert('sp_bank_details', $insertData);
			}

			if (!empty($recordId)) {
				// Add bank activity log details
				$param = array();
				$param['id']          = $arg['id'];
				$param['bank_dtls']   = $bankDtls;
				$param['record_data'] = $insertData;
				$this->addBankActivity($param);
				unset($param);
				return (!empty($arg['id']) ? $arg['id'] : $recordId);
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	/**
	* This function is used to update document status
	*/
	public function updateDocumentStatus($arg)
    {
		
		if (!empty($arg)) {
			// Get document details
			$docDtls = $this->getProfDocDtlsById($arg['Documents_id']);
			$updateData = array();
			$updateData['professional_id']   = $arg['service_professional_id'];
			$updateData['status']            = $arg['status'];
			$updateData['rejection_reason']  = $arg['rejection_reason'];

			$where = "Documents_id ='" . $arg['Documents_id'] . "'";
            $recordId = $this->query_update('sp_professional_documents', $updateData, $where);

			if (!empty($recordId)) {
				$data= array();
				$data = array (
					'Type'             => '4',
					'Professional_id'  => $updateData['professional_id'],
					'Title'            => ($updateData['status']== 1 ? 1 : 2),
					'rejection_reason' => $updateData['rejection_reason'], 
					"Document_id"      => $arg['document_list_id']
				);	
				$data = json_encode($data);
				$FCM_FILE_URL = "http://hospitalguru.in/push_notify.php";
				$out = send_curl_request($FCM_FILE_URL, $data, "post");
				$resultData = json_decode($out);
				if ($resultData->success == 1 && $recordId) {
					// Add activity log while update document status
					$param = array();
					$param['professional_id'] = $arg['service_professional_id'];
					$param['document_id']    = $arg['Documents_id'];
					$param['document_dtls']   = $docDtls;
					$param['record_data']     = $updateData;

					$this->addDocumentActivity($param);
					return 1;
				} else {
					return 2;
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	/**
	* This function is used to get professional documnent
	*/
	public function getProfessionalDocumentsList($profId, $serviceId) {
	if (!empty($profId) && !empty($serviceId)) {
		    
			$sql = "SELECT dl.Documents_name,
						pd.status,
						dl.document_list_id,
						pd.url_path,
						pd.rejection_reason,
						dl.isManadatory
					FROM `sp_professional_services` ps
					LEFT JOIN `sp_documetns_list` dl
						ON dl.professional_type = ps. service_id
						AND dl.professional_type = '" . $serviceId . "'
					LEFT JOIN `sp_professional_documents` pd
						ON pd.document_list_id = dl.document_list_id
					WHERE ps.`service_professional_id` = '" . $profId . "'
					GROUP BY dl.document_list_id";
					
			// echo "<pre>";
			// print_r($sql);
			// echo "</pre>";
			// exit;
		
			if ($this->num_of_rows($this->query($sql))) {
				$getProfessionalDocuments = $this->fetch_all_array($sql);
				return $getProfessionalDocuments;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	/**
	* This function is used to update document final status
	*/
	public function updateDocumentFinalStatus($arg)
    {
		
		if (!empty($arg)) {
			// Get document status details
			$sql = "SELECT document_status 
				FROM `sp_service_professionals` 
				WHERE `service_professional_id` = '" . $arg['service_professional_id'] . "' ";

			if ($this->num_of_rows($this->query($sql))) {
				$profDocDtls = $this->fetch_array($this->query($sql));
			}

			$updateData = array();
			$updateData['document_status']   = 1;
			$where = "service_professional_id ='" . $arg['service_professional_id'] . "'";
            $recordId = $this->query_update('sp_service_professionals', $updateData, $where);

			if (!empty($recordId)) {
				$data= array();
				$data = array (
					'Type'             => '4',
					'Professional_id'  => $arg['service_professional_id'],
					'Title'            => 3
				);	
				$data = json_encode($data);
					$FCM_FILE_URL = "http://hospitalguru.in/push_notify.php";			
		
				$out = send_curl_request($FCM_FILE_URL, $data, "post");
				$resultData = json_decode($out);
				if ($resultData->success == 1 && $recordId) {
					// Add activity log while update document status
					$insertActivityArr = array();
					$insertActivityArr['module_type']   = '2';
					$insertActivityArr['module_id']     = '28';
					$insertActivityArr['module_name']   = 'Document Approval';
					$insertActivityArr['event_id']      = '';
					$insertActivityArr['purpose_id']    = '';
					$insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
					$insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
					$insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
					$activityDesc = "Document final status modified successfully by " . $_SESSION['admin_user_name'] . "\r\n";
					$activityDesc .= "document_status is change from " . $profDocDtls['document_status'] . " to 1 \r\n";
					$activityDesc .= "Updated information sent by Push Notification successfully \r\n";
					$insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
					$this->query_insert('sp_user_activity', $insertActivityArr);
					unset($insertActivityArr);
					return 1;
				} else {
					return 2;
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	/**
	* This function is used to get professional leaves
	*/
	public function getProfessionalLeaveList($profId) {
		if (!empty($profId)) {
			$sql = "SELECT pl.professional_weekoff_id,
						ps.service_professional_id,
						pl.date_form,
						pl.date_to,
						pl.Note,
						(CASE
							WHEN pl.Leave_Conflit = '1' THEN 'Conflict'
							WHEN pl.Leave_Conflit = '2' THEN 'No conflict'
						END) AS leaveConflictStatus,
						pl.Leave_Conflit,
						pl.Leave_status,
						(CASE
							WHEN pl.Leave_status = '1' THEN 'Applied'
							WHEN pl.Leave_status = '2' THEN 'Approved'
							WHEN pl.Leave_status = '3' THEN 'Pending'
							WHEN pl.Leave_status = '4' THEN 'Rejected'
							WHEN pl.Leave_status = '5' THEN 'Cancelled'
						END) AS leaveStatus,
						pl.status,
						pl.rejection_reason
					FROM `sp_professional_services` ps
					LEFT JOIN `sp_professional_weekoff` pl
						ON pl.service_professional_id = ps.service_professional_id
					WHERE ps.`service_professional_id` = '" . $profId . "' AND pl.status = '1' AND ps.status = '1' GROUP BY pl.professional_weekoff_id ORDER BY pl.date DESC";
						
			if ($this->num_of_rows($this->query($sql))) {
				$getProfessionalLeaves = $this->fetch_all_array($sql);
				return $getProfessionalLeaves;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	/**
	* This function is used to update document status
	*/
	public function updateLeaveStatus($arg)
    {
		if (!empty($arg)) {
			// Get leave details
			$professionalWeekoffId = $arg['professional_weekoff_id'];
			$getLeaveDtls = $this->getLeaveById($professionalWeekoffId);
			$updateData = array();
			$updateData['service_professional_id']   = $arg['service_professional_id'];
			$updateData['Leave_status']            = $arg['Leave_status'];
			$updateData['rejection_reason']  = $arg['rejection_reason'];
			
			$where = "professional_weekoff_id ='" . $arg['professional_weekoff_id'] . "'";
            $recordId = $this->query_update('sp_professional_weekoff', $updateData, $where);

			if (!empty($recordId)) {
				$data= array();
				$data = array (
					'Type'             => '2',
					'Professional_id'  => $arg['service_professional_id'],
					'Title'            => ($updateData['Leave_status']== 2 ? 1 : 2),
					'rejection_reason' => $arg['rejection_reason'], 
					"Leave_id"      => $arg['professional_weekoff_id']
				);	
				$data = json_encode($data);
				$FCM_FILE_URL = "http://hospitalguru.in/push_notify.php";
				$out = send_curl_request($FCM_FILE_URL, $data, "post");
				$resultData = json_decode($out);
				if ($resultData->success == 1 && $recordId) {
					//Add activity log
					$param = array();
					$param['professional_weekoff_id'] = $professionalWeekoffId;
					$param['leave_dtls']              = $getLeaveDtls;
					$param['record_data'] 		      = $updateData;
					$this->addLeaveActivity($param);
					unset($param);
					return 1;
				} else {
					return 2;
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	
	public function getDocumentsListByServiceId($serviceId) 
	{
	    if (!empty($serviceId)) {
	        $sql = "SELECT document_list_id,
	                    Documents_name,
						isManadatory
					FROM  `sp_documetns_list` pd
				    WHERE professional_type = '" . $serviceId . "' ORDER BY document_list_id ASC ";
				    
			if ($this->num_of_rows($this->query($sql))) {
				$documentList = $this->fetch_all_array($sql);
				
				return $documentList;
			} else {
				return 0;
			}
	    } else {
	        return 0;
	    }
	    
	}
	
	public function getProfDocumentsList($profId) 
	{
	    if (!empty($profId)) {
	        $sql = "SELECT Documents_id,
	                    url_path,
						rejection_reason,
						document_list_id,
						status
					FROM  `sp_professional_documents`
				    WHERE professional_id = '" . $profId . "' ORDER BY document_list_id ASC";
				    
			if ($this->num_of_rows($this->query($sql))) {
				$profDocumentList = $this->fetch_all_array($sql);
				
				return $profDocumentList;
			} else {
				return 0;
			}
	    } else {
	        return 0;
	    }
	}
	
	public function API_AddProfessional_notification($args)
	{
		$insertData['professional_id']=$this->escape($args['professional_id']);
		$insertData['type']=$this->escape($args['type']);
        $insertData['title']=$this->escape($args['title']);
        $insertData['notification_detail_id']=$this->escape($args['notification_detail_id']);
        $insertData['message']=$this->escape($args['message']);
	    $insertData['added_date']=$this->escape($args['added_date']);
	    $insertData['last_modify_date']=$this->escape($args['last_modify_date']);
	    $insertData['Acknowledged']=$this->escape($args['Acknowledged']);
	    
		    	
	    
	    $RecordId = $this->query_insert('sp_professional_notification',$insertData);
	   	if (!empty($RecordId)) {
	   	    return $RecordId;
	   	    
	   	} else {
	   	    return 0;
	   	}
	}

    /**
    * This function is used to get professional document status
    */
    public function getProfDocumentStatus($profId)
    {
        if (!empty($profId)) {
            $resultArr = array();
            $profDocumentDtlsSql = "SELECT * FROM sp_professional_documents WHERE professional_id = '" . $profId . "'";
            if ($this->num_of_rows($this->query($profDocumentDtlsSql))) {
                $getDocumentDtls = $this->fetch_all_array($profDocumentDtlsSql);
                $totalDocument = count($getDocumentDtls);
                $verifiedCnt = 0;
                $rejectedCnt = 0;
                $inProgressCnt = 0;
                $needMoreCnt = 0;
                foreach ($getDocumentDtls AS $key => $valDocument) {

                    if ($valDocument['status'] == 1) {
                        $verifiedCnt += 1;
                    }

                    if ($valDocument['status'] == 2) {
                        $needMoreCnt += 1;
                    }

                    if ($valDocument['status'] == 3) {
                        $rejectedCnt += 1;
                    }

                    if ($valDocument['status'] == 4) {
                        $inProgressCnt += 1;
                    }

                }

                if ($totalDocument == $verifiedCnt) {
                    $resultArr['documentStatus'] = 'All Approved';
                } else if ($totalDocument == $inProgressCnt) {
                    $resultArr['documentStatus'] = 'All Uploaded';
                } else if ($totalDocument == $rejectedCnt) {
                    $resultArr['documentStatus'] = 'All Rejected';
                } else {
                   $resultArr['documentStatus'] = 'Partial Action Completed'; 
                } 
            } else {
                 $resultArr['documentStatus'] = 'NotSingleDocumentUploaded';
            }

            return $resultArr;
        }
    }

	/**
	* This function is used for get professional paytm payment details
	*/
	public function professionalPaytmPaymentList()
    {
		$getProfessionalPaytmPaymentSql = "SELECT pt.orderId,
				pt.mId,
				pt.channelId,
				pt.professional_id,
				sp.professional_code,
				sp.email_id AS `professional_email_id`,
				sp.mobile_no AS `professional_mobile_no`,
				CONCAT_WS(' ', sp.first_name, sp.name) AS `professional_name`,
				pt.mobileNo,
				pt.email,
				pt.transaction_Amount,
				pt.website,
				pt.industryTypeId,
				pt.callbackUrl,
				pt.checksumHash,
				pt.pay_status,
				pt.added_date,
				ps.transaction_id,
				ps.bank_transaction_id,
				ps.transcation_amount,
				ps.status,
				(CASE
					WHEN ps.status = 'TXN_SUCCESS' THEN 'Success'
					WHEN ps.status = 'TXN_FAILURE' THEN 'Failure'
				END) AS transStatus,
				ps.gateway_name,
				ps.response_code,
				ps.response_msg,
				ps.bank_name,
				ps.MID,
				ps.payment_mode,
				ps.refund_amount,
				ps.transcation_date
			FROM sp_payment_transaction AS pt
			INNER JOIN sp_payment_response AS ps
				ON pt.orderId = ps.order_id
			INNER JOIN sp_service_professionals AS sp
				ON pt.professional_id = sp.service_professional_id
			WHERE pt.pay_status = '2'";
			
			if ($this->num_of_rows($this->query($getProfessionalPaytmPaymentSql)))
			{
				$getProfessionalPaymentDtls = $this->fetch_all_array($getProfessionalPaytmPaymentSql);
				return $getProfessionalPaymentDtls;
			} else {
				return 0;
			}
	}

	/**
	 * This function is used for get professional locations
	 * @param int $profId
	 * @return array $result 
	 */
	public function profLocationPrefList($profId)
	{
		$result = array();
		if (!empty($profId)) {
			$getLocationPrefSql = "SELECT Professional_location_id,
				Name 
				FROM sp_professional_location
				WHERE professional_service_id = '" . $profId . "'";

			if ($this->num_of_rows($this->query($getLocationPrefSql))) {
				$result = $this->fetch_all_array($getLocationPrefSql);
			}
		}
		return $result;
	}

	/**
	 * This function is used for get professional availability
	 * @param int $profId
	 * @return array $resultantArr 
	 */
	public function profAvailabilityList($profId)
	{
		$resultantArr = array();
		if (!empty($profId)) {
			$getAvailabilitySql = "SELECT professional_avaibility_id,
				day,
				(CASE
					WHEN day = '1' THEN 'Sunday'
					WHEN day = '2' THEN 'Monday'
					WHEN day = '3' THEN 'Tuesday'
					WHEN day = '4' THEN 'Wednesday'
					WHEN day = '5' THEN 'Thursday'
					WHEN day = '6' THEN 'Friday'
					WHEN day = '7' THEN 'Saturday'
				END) AS dayVal
				FROM sp_professional_avaibility
				WHERE professional_service_id = '".$profId."' ";

			if ($this->num_of_rows($this->query($getAvailabilitySql))) {
				$avaibilityResult = $this->fetch_all_array($getAvailabilitySql);

				foreach ($avaibilityResult AS $key => $avaibilityVal) {
					$getAvailDtlsSql = "SELECT t1.start_time,
						t1.end_time,
						CONCAT(TIME_FORMAT(t1.start_time, '%h:%i %p'), ' To ', TIME_FORMAT(t1.end_time, '%h:%i %p')) AS timeVal,
						t2.Professional_location_id,
						t2.Name
					FROM sp_professional_availability_detail t1
					LEFT JOIN sp_professional_location AS t2
						ON t1.professional_location_id = t2.Professional_location_id
					WHERE t1.professional_availability_id = '" . $avaibilityVal['professional_avaibility_id'] . "' ";

					if ($this->num_of_rows($this->query($getAvailDtlsSql))) {
						$availResult = $this->fetch_all_array($getAvailDtlsSql);
						$timeSlot = "";
						$locationNm = "";
						foreach ($availResult AS $key => $valAvaility) {
							$timeSlot .= $valAvaility['timeVal'] . ",";
							$locationNm .= $valAvaility['Name'] . ",";
						}

						// check time slot

						// check location
						//$locationVal = array_unique(explode(',' $locationNm));

						$avaibilityVal['timeSlot'] = rtrim($timeSlot, ',');
						$avaibilityVal['locationNm'] = rtrim($locationNm, ',');
					}

					$resultantArr[] = $avaibilityVal;
				}
			}
		}

		return $resultantArr;
	}

	/**
	 * This function is used for get professional locations by locationId
	 * @param int $locationId
	 * @return array $result 
	 */
	public function getProfLocationPreferences($locationId)
	{
		$result = array();
		if (!empty($locationId)) {
			$getLocationPrefDtlsSql = "SELECT location_name AS name,
				lattitude,
				longitude
				FROM sp_professional_location_details
				WHERE professional_location_id = '" . $locationId . "'";

			if ($this->num_of_rows($this->query($getLocationPrefDtlsSql))) {
				$result = $this->fetch_all_array($getLocationPrefDtlsSql);
			}
		}
		return $result;
	}

	/**
	 * this function is used for remove professional location preference
	 */
	public function removeProfLocationPreferences($profId, $locationId)
	{
		if (!empty($profId) && !empty($locationId)) {

			// Get location details
			$locationDtlsSql = "SELECT Professional_location_id,
				Name,
				professional_service_id
			FROM sp_professional_location 
			WHERE Professional_location_id = '" . $locationId . "'";

			if ($this->num_of_rows($this->query($locationDtlsSql))) {
				$locationDtls = $this->fetch_array($this->query($locationDtlsSql));
			}

			$chkLocationDtlSql = "SELECT professional_location_details_id
				FROM `sp_professional_location_details` WHERE professional_location_id = '" . $locationId . "' ";

			if ($this->num_of_rows($this->query($chkLocationDtlSql))) {
				$delProfLocationDtls = "DELETE 
					FROM sp_professional_location_details 
					WHERE professional_location_id = '" . $locationId . "'";

				$this->query($delProfLocationDtls);

				$delProfLocPref = "DELETE 
					FROM sp_professional_location_preferences 
					WHERE professional_location_id = '" . $locationId . "'";

				$this->query($delProfLocPref);
			}

			$delProfLocation = "DELETE 
				FROM sp_professional_location 
				WHERE Professional_location_id = '" . $locationId . "'";

			$delRecord = $this->query($delProfLocation);
			
			if (!empty($delRecord) && !empty($locationDtls)) {
				// Add  activity log while removing location preference
				$insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '6';
                $insertActivityArr['module_name']   = 'Manage Professionals';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $activityDesc = "Professional " . $locationDtls['professional_service_id'] . " " . $locationDtls['Name'] . " location details removed successfully  by " . $_SESSION['admin_user_name'] . "\r\n";
                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);
				return 1; //success
			} else {
				return 0; //error in del
			}
		} else {
			return 0; // missing parameter
		}
	}

	/**
	 * This function is used for get professional availability
	 * @param int $profAvailabilityId
	 * @return array $result 
	 */
	public function getAvailabilityDtlsById($profAvailabilityId)
	{
		$result = array();
		if (!empty($profAvailabilityId)) {
			$getAvailabilitySql = "SELECT professional_availability_detail,
				professional_location_id,
				professional_availability_id,
				start_time,
				end_time 
				FROM sp_professional_availability_detail
				WHERE professional_availability_id = '" . $profAvailabilityId . "'";

			if ($this->num_of_rows($this->query($getAvailabilitySql))) {
				$result = $this->fetch_all_array($getAvailabilitySql);
			}
		}
		return $result;
	}

	/**
	 * this function is used for remove professional availability preference
	 */
	public function removeProfAvailabilityPreferences($profId, $avaibilityId, $recordId) 
	{
		if (!empty($profId) && !empty($avaibilityId)) {
			$activityDesc = "";
			$whereClause = (!empty($recordId) ? "professional_availability_detail = '" . $recordId . "' " : "professional_availability_id = '" . $avaibilityId . "'");

			// Delete all child records
			$chkAvailabilityDtlsSql = "SELECT professional_availability_detail,
					professional_availability_id,
					start_time,
					end_time,
					professional_location_id
				FROM `sp_professional_availability_detail` WHERE $whereClause ";

			if ($this->num_of_rows($this->query($chkAvailabilityDtlsSql))) {
				// Get Records details
				$availabilityDtls = $this->fetch_all_array($chkAvailabilityDtlsSql);
				$availabilityArr = array();
				foreach ($availabilityDtls AS $valAvailability) {
					$availabilityArr[] = $valAvailability['start_time'] . " - " . $valAvailability['end_time'];
				}

				$delProfAvailabilityDtls = "DELETE 
					FROM sp_professional_availability_detail 
					WHERE $whereClause ";

				if (!empty($recordId)) {
					$delRecord = $this->query($delProfAvailabilityDtls);
				} else {
					$this->query($delProfAvailabilityDtls);
				}

				// Add activity log for mapping table
				if (!empty($availabilityArr)) {
					$activityDesc .=  "Availability details removed successfully  by " . $_SESSION['admin_user_name'] . "\r\n" .
						" Details are as follows ". rtrim(implode(',' , $availabilityArr), ',') . "\r\n";
				}
				
			}

			if (empty($recordId)) {
				// Get availability record details
				$getAvailabilitySql = "SELECT t1.professional_avaibility_id,
					t1.professional_service_id,
					t2.professional_code,
					CONCAT_WS(' ', t2.title, t2.first_name, t2.middle_name, t2.name) AS professionalName,
					t1.day,
					(CASE
						WHEN t1.day = '1' THEN 'Sunday'
						WHEN t1.day = '2' THEN 'Monday'
						WHEN t1.day = '3' THEN 'Tuesday'
						WHEN t1.day = '4' THEN 'Wednesday'
						WHEN t1.day = '5' THEN 'Thursday'
						WHEN t1.day = '6' THEN 'Friday'
						WHEN t1.day = '7' THEN 'Saturday'
					END) AS dayVal
				FROM sp_professional_avaibility AS t1
				INNER JOIN sp_service_professionals AS t2
					ON t1.professional_service_id = t2.service_professional_id
				WHERE t1.professional_avaibility_id = '" . $avaibilityId . "'";

				if ($this->num_of_rows($this->query($getAvailabilitySql))) {
					$availabilityDtls = $this->fetch_array($this->query($getAvailabilitySql));
					$delProfAvailability = "DELETE 
					FROM sp_professional_avaibility 
					WHERE professional_avaibility_id = '" . $avaibilityId . "'";

					$delRecord = $this->query($delProfAvailability);
				}
			}

			if (!empty($delRecord)) {
				// Add activity log while delete professional avaibility
				$activityDesc .= $availabilityDtls['dayVal'] . " availbility removed succussfully of " . $availabilityDtls['professionalName'] . "\r\n";

				$insertActivityArr = array();
                $insertActivityArr['module_type']   = '2';
                $insertActivityArr['module_id']     = '6';
                $insertActivityArr['module_name']   = 'Manage Professionals';
                $insertActivityArr['event_id']      = '';
                $insertActivityArr['purpose_id']    = '';
                $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
                $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
                $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
                $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
                $this->query_insert('sp_user_activity', $insertActivityArr);
                unset($insertActivityArr);

				return 1; //success
			} else {
				return 0; //error in del
			}
		} else {
			return 0; // missing parameter
		}
	}

	/**
	 * This function is used for add location availablity
	 */
	public function addProfessionalAvaibility($arg)
	{
		if (!empty($arg)) {
			// first check is it record present
			$ChkAvaibilitySql = "SELECT professional_avaibility_id 
				FROM sp_professional_avaibility 
				WHERE professional_service_id = '"  .$arg['professional_service_id'] . "'
					AND day = '". $arg['day'] ."'";

			$daysValArr = array(
				1 => 'Sunday',
				2 => 'Monday',
				3 => 'Tuesday',
				4 => 'Wednesday',
				5 => 'Thursday',
				6 => 'Friday',
				7 => 'Saturday'
			);

			/*echo '<pre>ChkAvaibilitySql <br> ';
			print_r($ChkAvaibilitySql);
			echo '</pre>';*/
			
			if ($this->num_of_rows($this->query($ChkAvaibilitySql)) == 0)
        	{
				$insertData = array();

				$insertData['professional_service_id'] = $this->escape($arg['professional_service_id']);
				$insertData['day'] 					   = $this->escape($arg['day']);

				/*echo '<pre>insertData <br> ';
				print_r($insertData);
				echo '</pre>';*/

				$recordId = $this->query_insert('sp_professional_avaibility',$insertData);

				if (!empty($recordId)) {
					$avaibilityDtlArr['professional_availability_id'] = $recordId;

					$activityDesc = "Professional avaibility details added successfully for " . $daysValArr[$arg['day']] . " by " . $_SESSION['admin_user_name'] . "\r\n";

					$avaibilityDtlArr['professional_location_id'] = $arg['professional_location_id'];
					$timeArr = $arg['timeVal'];
					if (!empty($timeArr)) {
						$recordcnt = count($timeArr);
						$successCnt = 0;
						$failureCnt = 0;

						// Activity for time value 
						$activityDesc .=  "Time details are as follows ". rtrim(implode(',' , $timeArr), ',') . "\r\n";

						foreach ($timeArr AS $valTime) {
							$timeContent = '';
							$timeContent = explode('-', $valTime);
							if (!empty($timeContent)) {
								$avaibilityDtlArr['start_time'] = date("H:i:s", strtotime($timeContent[0]));
								$avaibilityDtlArr['end_time'] = date("H:i:s", strtotime($timeContent[1]));

								/*echo '<pre>insertData <br> ';
								print_r($avaibilityDtlArr);
								echo '</pre>'; */
								$avaibilityRecordId = $this->query_insert('sp_professional_availability_detail',$avaibilityDtlArr);

								if (!empty($avaibilityRecordId)) {
									$successCnt++;
								} else {
									$failureCnt++;
								}
							}
							unset($timeContent);
						}

						if ($recordcnt == $successCnt) {
							return 1;
						} else {
							return 0;
						}
					}

					// Add activity log while adding professional availability
					$param = array();
					$param['professional_service_id']      = $arg['professional_service_id'];
					$param['professional_availability_id'] = $recordId;
					$param['activity_description']         = $activityDesc;
					$this->addAvailabilityActivity($param);
					unset($param);
				} else {
					return 0;
				}
			} else {
				$timeArr = $arg['timeVal'];
				if (!empty($timeArr)) {
					$recordcnt = count($timeArr);
					$successCnt = 0;
					$failureCnt = 0;

					$activityDesc = "Professional availbility details modified successfully for " . $daysValArr[$arg['day']] . " by " . $_SESSION['admin_user_name'] . "\r\n";
					$activityDesc .=  "Time details are as follows \r\n";
					$activityDescContentArr = array();
					foreach ($timeArr AS $valTime) {
						$avaibilityDtlArr = array();
						$timeContent = '';
						$timeContent = explode('-', $valTime);

						if (!empty($timeContent)) {
							$avaibilityDtlArr['professional_location_id'] = $arg['professional_location_id'];
							$avaibilityDtlArr['start_time'] = date("H:i:s", strtotime($timeContent[0]));
							$avaibilityDtlArr['end_time'] = date("H:i:s", strtotime($timeContent[1]));

							// Check is it record present with same location and same time value
							$ChkAvaibilitySql = "SELECT /*DISTINCT*/ professional_avaibility_id
							FROM sp_professional_avaibility AS t1
							INNER JOIN sp_professional_availability_detail AS t2
								ON t1.professional_avaibility_id = t2.professional_availability_id
								/*AND t2.start_time <= t2.end_time 
								AND t2.end_time >= t2.end_time*/
							WHERE t1.professional_service_id = '" . $arg['professional_service_id'] . "'
								AND t1.day = '". $arg['day'] . "'
								AND (t2.start_time <= '" . $avaibilityDtlArr['start_time'] . "' AND t2.end_time >= '" . $avaibilityDtlArr['start_time'] . "')
								AND (t2.end_time >= '" . $avaibilityDtlArr['end_time'] . "' AND t2.start_time <= '" . $avaibilityDtlArr['end_time'] . "')
							";

							// echo '<pre>$ChkAvaibilitySql <br>';
							// print_r($ChkAvaibilitySql);
							// echo '</pre>';
							// exit;

							if ($this->num_of_rows($this->query($ChkAvaibilitySql)) == 0) {

								//Get availability id

								$getAvaSql = "SELECT professional_avaibility_id 
									FROM sp_professional_avaibility
									WHERE professional_service_id = '" . $arg['professional_service_id'] . "'
										AND day = '". $arg['day'] . "'
									";
								if ($this->num_of_rows($this->query($getAvaSql))) {
									$avaibilityResult = $this->fetch_array($this->query($getAvaSql));
									if (!empty($avaibilityResult)) {
										$avaibilityDtlArr['professional_availability_id'] = $avaibilityResult['professional_avaibility_id'];
										
										// echo '<pre>$avaibilityDtlArr <br>';
										// print_r($avaibilityDtlArr);
										// echo '</pre>';
										// exit;
										
										$avaibilityRecordId = $this->query_insert('sp_professional_availability_detail', $avaibilityDtlArr);
										if (!empty($avaibilityRecordId)) {
											$activityDescContentArr[] =  $avaibilityDtlArr['start_time'] . " - " . $avaibilityDtlArr['end_time'];
											$successCnt++;
										} else {
											$failureCnt++;
											return 0;
										}
									}
								}
							} else {
								// Record present show error message with details

								//echo '<pre>error messag <br>';
								//print_r($$avaibilityDtlArr);
								//echo '</pre>';
								//exit;
								return 0;
							}
						} else {
							// Time is not available
							return 0;
						}
						unset($timeContent);
					}

					if ($recordcnt == $successCnt) {
						// Add activity log while adding professional availability
						$param = array();
						$param['professional_service_id']      = $arg['professional_service_id'];
						$param['professional_availability_id'] =  $avaibilityResult['professional_avaibility_id'];

						if (!empty($activityDescContentArr)) {
							$activityDesc .= rtrim(implode(',' , $activityDescContentArr), ',') . "\r\n";
						}

						$param['activity_description']         = $activityDesc;
						$this->addAvailabilityActivity($param);
						unset($param);
						return 1;
					} else {
						return 0;
					}
				} else {
					// No time data post
					return 0;
				}
			}
		} else {
			return 0;
		}
	}

	/**
	 * Add / Update availability details
	 */
	public function addProfessionalAvaibilityDetail($arg)
	{
		if (!empty($arg)) {
			//check is it record available
			$ChkAvaibilitySql = "SELECT professional_availability_detail 
				FROM sp_professional_availability_detail 
				WHERE professional_location_id = '"  .$arg['actual_professional_location_id'] . "'
					AND professional_availability_id = '". $arg['professional_availability_id'] ."'";

			if ($this->num_of_rows($this->query($ChkAvaibilitySql)))
			{
				// first Delete all records
				$delRecords = "DELETE FROM sp_professional_availability_detail
					WHERE professional_location_id = '"  .$arg['actual_professional_location_id'] . "'
					AND professional_availability_id = '". $arg['professional_availability_id'] ."'";

				// echo '<pre>delRecords <br> ';
				// print_r($delRecords);
				// echo '</pre>';

				$this->query($delRecords);
			}

			$insertData = array();
			$insertData['professional_availability_id'] = $this->escape($arg['professional_availability_id']);
			$insertData['professional_location_id'] = $this->escape($arg['professional_location_id']);
			$timeValArr = $arg['timeVal'];
			if (!empty($timeValArr)) {
				$recordCnt = count($timeValArr);
				$successCnt = 0;
				$failureCnt = 0;

				foreach ($timeValArr AS $key => $valTime) {
					$timeContent = '';
					$timeContent = explode('-', $valTime);
					if (!empty($timeContent)) {
						$insertData['start_time'] = date("H:i:s", strtotime($timeContent[0]));
						$insertData['end_time'] = date("H:i:s", strtotime($timeContent[1]));

						//echo '<pre>insertData <br> ';
						//print_r($insertData);
						//echo '</pre>'; 

						$recordId = $this->query_insert('sp_professional_availability_detail',$insertData);

						if (!empty($recordId)) {
							$successCnt++;
						} else {
							$failureCnt++;
						}
					}
					unset($timeContent);
				}

				//echo '<pre>recordcnt <br> ';
				//print_r($recordcnt);
				// echo '<br>successCnt <br>';
				// print_r($successCnt);
				// echo '<br>errorCnt <br>';
				// print_r($failureCnt);
				// echo '</pre>'; 

				if ($recordcnt == $successCnt) {
					return 1;
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	/**
	 *
	 * This method is used for add professional location preference
	 *
	 * @param array $arg
	 *
	 * @return 0|1
	 */
	public function addProfLoctionPreference($arg)
	{
		if (!empty($arg)) {
			$insertData = array();
			$insertData['professional_service_id'] = $this->escape($arg['professional_service_id']);
			$insertData['Name'] = $this->escape($arg['Name']);
			$recordId = $this->query_insert('sp_professional_location', $insertData);
			$activityDesc = "";
			if (!empty($recordId)) {
				// Add activity log while adding profession location
				$activityDesc .= "Professional location details added successfully named as " . $insertData['Name'] . " by " . $_SESSION['admin_user_name'] . "\r\n";
				$insertCoordsData = array();
				$insertCoordsData['professional_location_id'] = $recordId;
				$coordsArr = $arg['coordsArr'];

				if (!empty($coordsArr)) {
					$locationDtlsArr = array();
					foreach ($coordsArr AS $valCoord) {
						$insertCoordsData['lattitude'] = number_format((float)$valCoord[0], 6, '.', '');
						$insertCoordsData['longitude'] = number_format((float)$valCoord[1], 6, '.', '');
						// Get location Name
						$geolocation = $insertCoordsData['lattitude'] . "," . $insertCoordsData['longitude'];
						$request = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC8lSxG4pg8hWyd52oqUQJKWnjQSe20dvc&latlng='.$geolocation.'&sensor=false'; 
						$file_contents = file_get_contents($request);
						$output = json_decode($file_contents);
						$insertCoordsData['location_name'] = '';
						if (!empty($output)) {
							$insertCoordsData['location_name'] = $output->results[0]->formatted_address;
						}
						$recLocationId = $this->query_insert('sp_professional_location_details', $insertCoordsData);

						if (!empty($recLocationId)) {
							// Add activity log  while adding 
							$locationDtlsArr[] = " Lattitude : " . $insertCoordsData['lattitude'] . " longitude : " . $insertCoordsData['longitude'] . "\r\n" .
								" and location name : " . $insertCoordsData['location_name'];
						}
					}

					if (!empty($locationDtlsArr)) {
						$activityDesc .= "Location  details are as follows \r\n" . implode(',', $locationDtlsArr) . "\r\n";
					}

					// Add details in sp_professional_location_preferences
					$sql = "SELECT 
						pl.professional_service_id,
						pl.Professional_location_id,
						MAX(pld.lattitude) AS max_latitude,
						MIN(pld.lattitude) AS min_latitude,
						MAX(pld.longitude) AS max_longitude,
						MIN(pld.longitude) AS min_longitude
					FROM sp_professional_location_details AS pld
					INNER JOIN sp_professional_location AS pl
						ON pl.Professional_location_id = pld.professional_location_id
					WHERE pl.Professional_location_id = '" . $recordId . "' ";

					if ($this->num_of_rows($this->query($sql))) {
						$insertPrefData = array();
						$prefResult = $this->fetch_array($this->query($sql));
						if (!empty($prefResult)) {
							$insertPrefData['service_professional_id'] = $prefResult['professional_service_id'];
							$insertPrefData['professional_location_id'] = $prefResult['Professional_location_id'];
							$insertPrefData['max_latitude'] = number_format((float)$prefResult['max_latitude'], 6, '.', '');
							$insertPrefData['min_latitude'] = number_format((float)$prefResult['min_latitude'], 6, '.', '');
							$insertPrefData['max_longitude'] = number_format((float)$prefResult['max_longitude'], 6, '.', '');
							$insertPrefData['min_longitude'] = number_format((float)$prefResult['min_longitude'], 6, '.', '');
							$mapRecordId = $this->query_insert('sp_professional_location_preferences',$insertPrefData);
							
							if (!empty($mapRecordId)) {
								// Add activity log while adding location preferences
								$activityDesc .= "Location preferences details are as follows \r\n";
								$activityDesc .= "max_latitude : " . $insertPrefData['max_latitude'] . " \r\n";
								$activityDesc .= "min_latitude : " . $insertPrefData['min_latitude'] . " \r\n";
								$activityDesc .= "max_longitude : " . $insertPrefData['max_longitude'] . " \r\n";
								$activityDesc .= "min_longitude : " . $insertPrefData['min_longitude'] . " \r\n";
							}
						}
					}
					// Add activity log while adding location preferences
					$param = array();
					$param['professional_service_id'] = $insertData['professional_service_id'];
					$param['professional_location_id'] = $recordId;
					$param['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
					$this->addLocationActivity($param);
					unset($param);
				}
				return 1;
			} else {
				return 0;
			}
		}
	}

	/**
	 * This function is used for get professionals Payment List
	 *
	 * @param array $arg
	 *
	 * @return  array $result
	 *
	 */
	public function professionalsPaymentList($arg)
	{
		$preWhere = "";
        $filterWhere = "";
		$join = "";
		$groupBy = "";

		$search_value = $this->escape($arg['search_Value']);
		$filter_name  = $this->escape($arg['filter_name']);
		$filter_type  = $this->escape($arg['filter_type']);
		$searchfromDate  = $this->escape($arg['searchfromDate']);
        $searchToDate    = $this->escape($arg['searchToDate']);

		if (!empty($search_value) && $search_value !='null') {
			$preWhere .= " AND (sp.professional_code LIKE '%".$search_value."%' OR
				sp.title LIKE '%".$search_value."%'  OR
				sp.first_name LIKE '%".$search_value."%' OR
				sp.middle_name LIKE '%".$search_value."%' OR
				sp.name LIKE '%".$search_value."%' OR
				sp.mobile_no LIKE '%".$search_value."%' OR
				sp.phone_no LIKE '%".$search_value."%' OR
				sp.email_id LIKE '%".$search_value."%' )"; 
		}
		 
		if ((!empty($filter_name) && $filter_name != 'null') &&
			(!empty($filter_type) && $filter_type != 'null')) {
			$filterWhere .= " ORDER BY " . $filter_name . " " . $filter_type . ""; 
		}


		if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(prbp.date_time, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND DATE_FORMAT(prbp.date_time,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
        }

		$groupBy = " GROUP BY prbp.professional_vender_id  HAVING (pendingAmount != 0 || receivedAmount != 0)";

		$professionalsPaymentSql = "SELECT sp.service_professional_id,
				sp.professional_code,
				CONCAT_WS(' ', sp.title, sp.first_name, sp.middle_name, sp.name) AS professionalName,
				SUM(IF((prbp.OTP_verifivation = 1 AND prbp.payment_status = 2), prbp.amount, 0)) AS receivedAmount,
				SUM(IF((prbp.OTP_verifivation = 1 AND prbp.payment_status = 1), prbp.amount, 0)) AS pendingAmount,
				SUM(IF(prbp.OTP_verifivation = 1 , prbp.amount, 0)) AS totalAmount,
				sp.mobile_no,
				sp.phone_no,
				sp.email_id
			FROM sp_payments_received_by_professional AS prbp
			INNER JOIN sp_service_professionals AS sp
				ON  prbp.professional_vender_id = sp.service_professional_id
			WHERE prbp.status = '1'
			 " . $preWhere . " " . $groupBy . "  " . $filterWhere . " ";


		$this->result = $this->query($professionalsPaymentSql);

		if ($this->num_of_rows($this->result)) {
			$pager = new PS_Pagination($professionalsPaymentSql, $arg['pageSize'], $arg['pageIndex'], '');
			$all_records = $pager->paginate();

			while ($val_records = $this->fetch_array($all_records))
            {
				$this->resultProfessionalPayment[] = $val_records;
			}

			$resultArray['count'] = $pager->total_rows;
		}

		if (count($this->resultProfessionalPayment)) {
            $resultArray['data'] = $this->resultProfessionalPayment;
            return $resultArray;
        }
        else {
			return array('data' => array(), 'count' => 0);
		}
	}
	
	/**
	* This function is used for get professional payment details
	*/
	public function professionalPaymentList($professionalId, $type, $searchfromDate = NULL, $searchToDate = NULL)
    {
		if (!empty($professionalId) && !empty($type)) {
			$whereClause = '';
			if ($type == 'Total') {
				$whereClause = '';
			} else if ($type == 'Received') {
				$whereClause = " AND payment_status = '2'";
			}  else if ($type == 'Pending') {
				$whereClause = " AND payment_status = '1'";
			}

			if (!empty($searchfromDate) &&  !empty($searchToDate)) {
				$whereClause .= " AND (DATE_FORMAT(prp.date_time, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
			} else {
				$whereClause .= " AND DATE_FORMAT(prp.date_time,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
				1 DAY)";
			}

			$getProfessionalPaymentSql = "SELECT prp.payment_id,
				prp.cheque_DD__NEFT_no,
				prp.cheque_DD__NEFT_date,
				prp.cheque_path_id,
				prp.party_bank_name,
				prp.professional_vender_id,
				prp.amount,
				prp.type,
				prp.added_by,
				prp.added_by_type,
				prp.Transaction_ID,
				prp.date_time,
				prp.Comments,
				prp.status,
				(CASE
					WHEN prp.status = '1' THEN 'Active'
					WHEN prp.status = '2' THEN 'Inactive'
				END) AS statusVal,
				prp.payment_status,
				(CASE
					WHEN prp.payment_status = '1' THEN 'Amount with professional'
					WHEN prp.payment_status = '2' THEN 'Amount paid to spero'
				END) AS paymentStatusVal,
				prp.Payment_type,
				(CASE
					WHEN prp.Payment_type = '1' THEN 'Offline'
					WHEN prp.Payment_type = '2' THEN 'Online'
				END) AS paymentTypeVal,
				prp.Payment_mode,
				(CASE
					WHEN prp.Payment_mode = '1' THEN 'Cash'
					WHEN prp.Payment_mode = '2' THEN 'Cheque'
				END) AS paymentModeVal,
				prp.OTP_verifivation,
				e.event_id,
				e.event_code,
				e.patient_id,
				sp.professional_code,
				sp.email_id,
				sp.mobile_no,
				CONCAT_WS(' ', sp.first_name, sp.name) AS `professional_name`,
				CONCAT_WS(' ', p.first_name, p.name) AS `patient_name`,
				p.email_id AS patient_email_id,
				p.mobile_no AS patient_mobile_no,
				p.hhc_code,
				ci.Url_path
				FROM sp_payments_received_by_professional AS prp
				INNER JOIN sp_service_professionals AS sp 
					ON prp.professional_vender_id = sp.service_professional_id
				INNER JOIN sp_event_requirements AS er
					ON prp.event_requirement_id = er.event_requirement_id
				INNER JOIN sp_events AS e
					ON er.event_id = e.event_id
				INNER JOIN sp_patients AS p
					ON e.patient_id = p.patient_id
				LEFT JOIN sp_cheque_images AS ci
					ON prp.Session_id = ci.Detailed_plan_of_care_id
				WHERE prp.professional_vender_id = '" . $professionalId . "' AND 
					OTP_verifivation = '1' AND prp.amount != 0 " . $whereClause . " ";
				
				//echo '<pre>getProfessionalPaymentSql';
				//print_r($getProfessionalPaymentSql);
				//echo '</pre>';
				//exit;
				
			if ($this->num_of_rows($this->query($getProfessionalPaymentSql)))
			{
				$getProfessionalPaymentDtls = $this->fetch_all_array($getProfessionalPaymentSql);
				return $getProfessionalPaymentDtls;
			}
		} else {
			return 0;
		}
	}
	
	/**
	 * 
	 */
	public function professionalsPaytmPaymentList($arg)
	{
		$preWhere = "";
        $filterWhere = "";
		$join = "";
		$groupBy = "";

		$search_value = $this->escape($arg['search_Value']);
		$filter_name  = $this->escape($arg['filter_name']);
		$filter_type  = $this->escape($arg['filter_type']);
		$searchfromDate  = $this->escape($arg['searchfromDate']);
		$searchToDate    = $this->escape($arg['searchToDate']);
		

		if (!empty($search_value) && $search_value !='null') {
			$preWhere .= " AND (sp.professional_code LIKE '%".$search_value."%' OR
				sp.title LIKE '%".$search_value."%'  OR
				sp.first_name LIKE '%".$search_value."%' OR
				sp.middle_name LIKE '%".$search_value."%' OR
				sp.name LIKE '%".$search_value."%' OR
				sp.mobile_no LIKE '%".$search_value."%' OR
				sp.phone_no LIKE '%".$search_value."%' OR
				ps.transaction_id LIKE '%".$search_value."%' OR
				ps.transcation_amount LIKE '%".$search_value."%' OR
				ps.status LIKE '%".$search_value."%' OR
				sp.email_id LIKE '%".$search_value."%' )"; 
		}
		 
		if ((!empty($filter_name) && $filter_name != 'null') &&
			(!empty($filter_type) && $filter_type != 'null')) {
			$filterWhere .= " ORDER BY " . $filter_name . " " . $filter_type . ""; 
		}


		if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(pt.added_date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND DATE_FORMAT(pt.added_date,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
        }

		$professionalPaytmPaymentSql = "SELECT pt.orderId,
				pt.mId,
				pt.channelId,
				pt.professional_id,
				sp.professional_code,
				sp.email_id AS `professional_email_id`,
				sp.mobile_no AS `professional_mobile_no`,
				CONCAT_WS(' ', sp.first_name, sp.name) AS `professional_name`,
				pt.mobileNo,
				pt.email,
				pt.transaction_Amount,
				pt.website,
				pt.industryTypeId,
				pt.callbackUrl,
				pt.checksumHash,
				pt.pay_status,
				pt.added_date,
				ps.transaction_id,
				ps.bank_transaction_id,
				ps.transcation_amount,
				ps.status,
				(CASE
					WHEN ps.status = 'TXN_SUCCESS' THEN 'Success'
					WHEN ps.status = 'TXN_FAILURE' THEN 'Failure'
				END) AS transStatus,
				ps.gateway_name,
				ps.response_code,
				ps.response_msg,
				ps.bank_name,
				ps.MID,
				ps.payment_mode,
				ps.refund_amount,
				ps.transcation_date
			FROM sp_payment_transaction AS pt
			INNER JOIN sp_payment_response AS ps
				ON pt.orderId = ps.order_id
			INNER JOIN sp_service_professionals AS sp
				ON pt.professional_id = sp.service_professional_id
			WHERE pt.pay_status = '2' 
			" . $preWhere . " " . $groupBy . "  " . $filterWhere . " ";

		$this->result = $this->query($professionalPaytmPaymentSql);

		if ($this->num_of_rows($this->result)) {
			$pager = new PS_Pagination($professionalPaytmPaymentSql, $arg['pageSize'], $arg['pageIndex'], '');
			$all_records = $pager->paginate();

			while ($val_records = $this->fetch_array($all_records))
			{
				$this->resultProfessionalPaytmPayment[] = $val_records;
			}

			$resultArray['count'] = $pager->total_rows;
		}

		if (count($this->resultProfessionalPaytmPayment)) {
			$resultArray['data'] = $this->resultProfessionalPaytmPayment;
			return $resultArray;
		}
		else {
			return array('data' => array(), 'count' => 0);
		}
	}


	/**
	 * This function is used for get physiotherapy unit calculation list
	 */
	public function physiotherapyUnitCalculationList($arg)
	{
		$preWhere = "";
        $filterWhere = "";
        $join = "";
        $searchValue = $this->escape($arg['search_value']);
        $filterName = $this->escape($arg['filter_name']);
        $filterType = $this->escape($arg['filter_type']);
        $searchfromDate  = $this->escape($arg['searchfromDate']);
		$searchToDate    = $this->escape($arg['searchToDate']);
		
		if (!empty($searchValue) && $searchValue !='null')
        {
            $preWhere = " AND (sp.first_name LIKE '%" . $searchValue . "%' OR sp.name LIKE '%" . $searchValue . "%'  OR sp.mobile_no LIKE '%" . $searchValue . "%')"; 
        }
        
        if ((!empty($filterName) && $filterName != 'null') && (!empty($filterType) && $filterType != 'null'))
        {
            $filterWhere .= " ORDER BY " . $filterName . " " . $filterType . ""; 
        }

        if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= "  AND sp.status = '1' AND (ep.service_id = '3' || ep.service_id = '16') AND (epc.service_date BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND sp.status = '1' AND (ep.service_id = '3' || ep.service_id = '16') AND epc.service_date  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
        }

		$groupBy = 'GROUP BY ep.professional_vender_id, epc.professional_vender_id, ps.service_professional_id';

		$physiotherapyUnitCalculationSql = "SELECT ep.event_professional_id,
			ep.professional_vender_id,
			CONCAT(sp.title, '. ', sp.first_name, ' ', sp.middle_name, ' ', sp.name) AS professional_name,
			sp.email_id,
			sp.phone_no,
			sp.mobile_no,
			sp.work_email_id,
			sp.work_phone_no,
			ep.event_id,
			ep.event_requirement_id,
			ep.professional_vender_id,
			ep.plan_of_care_id,
			ep.service_id,
			SUM((DATEDIFF(epc.service_date_to, epc.service_date) + 1)) AS totalUnits
			FROM sp_event_professional AS ep
			LEFT JOIN sp_professional_services AS ps
				ON ps.service_professional_id = ep.professional_vender_id
			LEFT JOIN sp_service_professionals AS sp
				ON sp.service_professional_id = ps.service_professional_id
			LEFT JOIN sp_event_plan_of_care epc
				ON ep.event_requirement_id = epc.event_requirement_id
			" . $join . " 
			AND ep.professional_vender_id IS NOT NULL 
			WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "";


		//echo '<pre>';
		//print_r($physiotherapyUnitCalculationSql);
		//echo '</pre>';

		$this->result = $this->query($physiotherapyUnitCalculationSql);

		if ($this->num_of_rows($this->result))
		{
			$pager = new PS_Pagination($physiotherapyUnitCalculationSql, $arg['pageSize'], $arg['pageIndex'], '');
			$allRecords = $pager->paginate();
			while ($valRecords = $this->fetch_array($allRecords)) {
				$this->resultPhysiotherapyUnitCalculation[] = $valRecords;
			}
			$resultArray['count'] = $pager->total_rows;
		}
		
		if (count($this->resultPhysiotherapyUnitCalculation)) {
			$resultArray['data'] = $this->resultPhysiotherapyUnitCalculation;
			return $resultArray;
		}
		else {
			return array(
				'data' => array(),
				'count' => 0
			);
		}
	}

	/**
	 * This function is used for get Physiotherapy Unit Details
	 */
	public function getPhysiotherapyUnitDtls($arg)
	{
		$resultantArr = array();
		if (!empty($arg)) {
			$serviceProfessionalId    = $this->escape($arg['service_professional_id']);
			$searchfromDate  =  $this->escape($arg['searchfromDate']);
			$searchToDate    =  $this->escape($arg['searchToDate']);

			if (!empty($searchfromDate) &&
				$searchfromDate != 'null' &&
				!empty($searchToDate) &&
				$searchToDate != 'null') {

				$searchfromDate = date('Y-m-d', strtotime($searchfromDate));
				$searchToDate = date('Y-m-d', strtotime($searchToDate));

				$preWhere .= " AND epc.professional_vender_id = '" . $serviceProfessionalId . "' AND sp.status = '1' AND (er.service_id = '3' || er.service_id = '16') AND (epc.service_date BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
			} else {
				$preWhere .= " AND epc.professional_vender_id = '" . $serviceProfessionalId . "' AND sp.status = '1' AND (er.service_id = '3' || er.service_id = '16') AND epc.service_date  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
				1 DAY)";
			}

			$physiotherapyUnitCalculationSql = "SELECT epc.plan_of_care_id,
					CONCAT(sp.title, '. ', sp.first_name, ' ', sp.middle_name, ' ', sp.name) AS professional_name,
					sp.Physio_Rate,
					CONCAT(p.first_name, ' ', p.middle_name, ' ', p.name) AS patient_name,
					p.hhc_code,
					p.mobile_no AS patient_mobile_no,
					e.event_date,
					s.service_title,
					ss.recommomded_service,
					ss.cost,
					ss.UOM,
					epc.service_date,
					epc.service_date_to,
					SUM((DATEDIFF(epc.service_date_to, epc.service_date) + 1)) AS totalUnits
				FROM sp_event_plan_of_care AS epc
				LEFT JOIN sp_event_requirements AS er
					ON er.event_requirement_id = epc.event_requirement_id
				LEFT JOIN sp_events AS e
					ON er.event_id = e.event_id
				LEFT JOIN sp_patients AS p
					ON p.patient_id = e.patient_id
				LEFT JOIN sp_service_professionals AS sp
					ON er.professional_vender_id = sp.service_professional_id
				LEFT JOIN sp_services AS s
					ON er.service_id = s.service_id
				LEFT JOIN sp_sub_services AS ss
					ON er.sub_service_id = ss.sub_service_id
				WHERE 1 " . $preWhere . " GROUP BY epc.plan_of_care_id";


			//echo '<pre>';
			//print_r($physiotherapyUnitCalculationSql);
			//echo '</pre>';


			if ($this->num_of_rows($this->query($physiotherapyUnitCalculationSql))) {
				$resultantArr = $this->fetch_all_array($physiotherapyUnitCalculationSql);
			}
		}
		return $resultantArr;
	}

	/**
	 * This function is used for get professional feedback list
	 */
	public function ProfessionalsFeedbackList($arg)
	{
		$preWhere = "";
		$filterWhere = "";
		$join = "";
		$searchValue = $this->escape($arg['search_value']);
		$filterName = $this->escape($arg['filter_name']);
		$filterType = $this->escape($arg['filter_type']);
		$searchfromDate  = $this->escape($arg['searchfromDate']);
		$searchToDate    = $this->escape($arg['searchToDate']);

			
		if (!empty($searchValue) && $searchValue !='null')
		{
			$preWhere = " AND (af.feedback LIKE '%" . $searchValue . "%' OR sp.professional_code LIKE '%" . $searchValue . "%' OR sp.first_name LIKE '%" .$searchValue . "%' OR sp.name LIKE '%" . $searchValue . "%' OR sp.email_id LIKE '%" . $searchValue . "%' OR sp.mobile_no LIKE '%" . $searchValue . "%')"; 
		}
			
		if ((!empty($filterName) && $filterName != 'null') && (!empty($filterType) && $filterType != 'null'))
		{
			$filterWhere .= " ORDER BY " . $filterName . " " . $filterType . ""; 
		}

		if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(af.added_date, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND DATE_FORMAT(af.added_date,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
        }
			
		$professionalsFeedbackSql = "SELECT af.app_feedback_id,
				af.feedback,
				af.added_date,
				sp.professional_code,
				sp.email_id AS `professional_email_id`,
				sp.mobile_no AS `professional_mobile_no`,
				CONCAT_WS(' ', sp.first_name, sp.name) AS `professional_name`
			FROM sp_feedback_for_app AS af 
			LEFT JOIN sp_service_professionals AS sp 
				ON af.professional_id = sp.service_professional_id
			" . $join . " 
			WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "";


		// echo '<pre>';
		// print_r($professionalsFeedbackSql);
		// echo '</pre>';
		// exit;
			
		$this->result = $this->query($professionalsFeedbackSql);
		
		if ($this->num_of_rows($this->result))
		{
			$pager = new PS_Pagination($professionalsFeedbackSql, $arg['pageSize'], $arg['pageIndex'], '');
			$allRecords = $pager->paginate();
			while ($valRecords = $this->fetch_array($allRecords))
			{
				// Getting Record Detail
				$this->resultProfessionalsFeedback[] = $valRecords;
			}
			$resultArray['count'] = $pager->total_rows;
		}
		
		if (count($this->resultProfessionalsFeedback)) {
			$resultArray['data'] = $this->resultProfessionalsFeedback;
			return $resultArray;
		}
		else {
			return array(
				'data' => array(),
				'count' => 0
			);
		}
	}

	/**
	 * This function is used for get payment list
	 */
	public function paymentsList($arg)
	{
		$preWhere            = "";
		$filterWhere         = "";
		$join                = "";
		$searchValue         = $this->escape($arg['search_value']);
		$filterName          = $this->escape($arg['filter_name']);
		$filterType          = $this->escape($arg['filter_type']);
		$searchfromDate      = $this->escape($arg['searchfromDate']);
		$searchToDate        = $this->escape($arg['searchToDate']);
		$searchByPaymentType = $this->escape($arg['searchByPaymentType']);
		$searchByHospital    = $this->escape($arg['searchByHospital']);

		if (!empty($searchByPaymentType) && $searchByPaymentType !='null')
		{
			$preWhere .= " AND pay.type = '" . $searchByPaymentType . "' "; 
		}

		if (!empty($searchByHospital) && $searchByHospital !='null')
		{
			$preWhere .= " AND pay.hospital_id = '" . $searchByHospital . "' "; 
		}

		if (!empty($searchValue) && $searchValue !='null')
		{
			$preWhere .= " AND (e.event_code LIKE '%" . $searchValue . "%' OR pt.hhc_code LIKE '%" . $searchValue . "%' OR pt.first_name LIKE '%" .$searchValue . "%' OR pt.name LIKE '%" . $searchValue . "%' OR pt.email_id LIKE '%" . $searchValue . "%' OR pt.mobile_no LIKE '%" . $searchValue . "%')"; 
		}
			
		if ((!empty($filterName) && $filterName != 'null') && (!empty($filterType) && $filterType != 'null'))
		{
			$filterWhere .= " ORDER BY " . $filterName . " " . $filterType . ""; 
		}

		if (!empty($searchfromDate) &&
            $searchfromDate != 'null' &&
            !empty($searchToDate) &&
            $searchToDate != 'null') {

            $searchfromDate = date('Y-m-d', strtotime($searchfromDate));
            $searchToDate = date('Y-m-d', strtotime($searchToDate));

            $preWhere .= " AND (DATE_FORMAT(pay.date_time, '%Y-%m-%d') BETWEEN  '" . $searchfromDate . "'  AND '" . $searchToDate . "') ";
        } else {
            $preWhere .= " AND DATE_FORMAT(pay.date_time,'%Y-%m-%d')  >= DATE_SUB(LAST_DAY(NOW()),INTERVAL DAY(LAST_DAY(NOW())) -
			1 DAY)";
		}
	
		$paymentSql = "SELECT e.event_id,
				e.event_code,
				pt.patient_id,
				pt.hhc_code,
				CONCAT_WS(' ', pt.first_name, pt.name) AS `patient_name`,
				pt.email_id,
				pt.mobile_no,
				pay.payment_id,
				pay.Transaction_Type,
				pay.amount AS `payment_amount`,
				pay.type AS `payment_type`,
				pay.date_time
			FROM sp_payments AS pay
			LEFT JOIN sp_events AS e 
				ON pay.event_id = e.event_id
			LEFT JOIN sp_patients AS pt
				ON pt.patient_id = e.patient_id	
			" . $join . " 
			WHERE 1 " . $preWhere . "  " . $groupBy . " " . $filterWhere . "";


		//echo '<pre>';
		//print_r($paymentSql);
		//echo '</pre>';
		//exit;
			
		$this->result = $this->query($paymentSql);
		
		if ($this->num_of_rows($this->result))
		{
			$pager = new PS_Pagination($paymentSql, $arg['pageSize'], $arg['pageIndex'], '');
			$allRecords = $pager->paginate();
			while ($valRecords = $this->fetch_array($allRecords))
			{
				// Getting Record Detail
				$this->resultPayments[] = $valRecords;
			}
			$resultArray['count'] = $pager->total_rows;
		}
		
		if (count($this->resultPayments)) {
			$resultArray['data'] = $this->resultPayments;
			return $resultArray;
		}
		else {
			return array(
				'data' => array(),
				'count' => 0
			);
		}
	}

	/**
	 * This function is used for upload professional document
	 */
	public function addProfessionalDocument($arg)
	{
		$recordId = 0;
		if (!empty($arg)) {
			$serviceProfessionalId = $this->escape($arg['service_professional_id']);
			$documentListId = $this->escape($arg['document_list_id']);
			$urlPath = $this->escape($arg['url_path']);

			$ChkProfDocExistsSql = "SELECT Documents_id 
				FROM sp_professional_documents 
				WHERE professional_id = '" . $serviceProfessionalId . "' AND
					document_list_id = '" . $documentListId . "' ";

			// echo '<pre>ChkProfDocExistsSql <br>';
			// print_r($ChkProfDocExistsSql);
			// echo '</pre>';
			// exit;

			if ($this->num_of_rows($this->query($ChkProfDocExistsSql)) == 0) {
				$insertData = array();

				$insertData['professional_id']  = $serviceProfessionalId;
				$insertData['document_list_id'] = $documentListId;
				$insertData['url_path']         = $urlPath;
				$insertData['rejection_reason'] = "";
				$insertData['status']           = "4";
				$insertData['isVerified']       = "1";

				$recordId = $this->query_insert('sp_professional_documents', $insertData);
			} else {
				$updateData['url_path'] = $this->escape($arg['url_path']);
				$updateProfDocument = "UPDATE sp_professional_documents SET  
					url_path = '" . $urlPath . "',
					rejection_reason = '',
					status = '4'
					WHERE professional_id = '" . $serviceProfessionalId . "' AND 
						document_list_id = '" . $documentListId. "' ";

				$recordId = $this->query($updateProfDocument);
			}
		}

		return $recordId;
	}

	/**
	 *
	 * This function is used for get leave details
	 *
	 * @param int $professionalWeekoffId
	 *
	 * @return array $leaveDtls
	 *
	 */
	public function getLeaveById($professionalWeekoffId)
	{
		$leaveDtls = array();
		if (!empty($professionalWeekoffId)) {
			$getLeaveSql = "SELECT professional_weekoff_id,
				service_professional_id,
				date_form,
				date_to,
				Note,
				date,
				Leave_Conflit,
				Leave_status,
				rejection_reason,
				status
			FROM sp_professional_weekoff
			WHERE professional_weekoff_id = '" . $professionalWeekoffId . "'";

			if ($this->num_of_rows($this->query($getLeaveSql))) {
				$leaveDtls = $this->fetch_array($this->query($getLeaveSql));
			}
		}
		return $leaveDtls;
	}

	/**
	 *
	 * This function is used for add leave activity
	 *
	 * @param array $args
	 *
	 * @return int $recordId
	 *
	 */
	public function addLeaveActivity($args)
	{
		$recordId = 0;
        if (!empty($args)) {
            $profWeekoffId   = $args['professional_weekoff_id'];
            $leaveDtls       = $args['leave_dtls'];
            $insertData      = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
			$insertActivityArr['module_id']     = '29';
			$insertActivityArr['module_name']   = 'Leave Approval';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Leave details " . ( $profWeekoffId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($leaveDtls) && !empty($insertData)) {
                unset($leaveDtls['professional_weekoff_id'],
                    $leaveDtls['service_professional_id'],
                    $leaveDtls['date_form'],
                    $leaveDtls['date_to'],
                    $leaveDtls['Note'],
					$leaveDtls['date'],
					$leaveDtls['status']
                );
                $leaveDiff = array_diff_assoc($leaveDtls, $insertData);
                if (!empty($leaveDiff)) {
                    foreach ($leaveDtls AS $key => $valLeave) {
                        $activityDesc .= $key . " is change from " . $valLeave . " to " . $insertData[$key] . "\r\n";
                    }
				}
				
				$activityDesc .= "Updated information sent by Push Notification successfully \r\n";
            }
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
	}

	/**
	 *
	 * This function is used to get professional bank details by id
	 * 
	 * @param int $id
	 *
	 * @return array $result
	 *
	*/
	public function getProfBankDtlsById($id)
    {
		if (!empty($id)) {
			$profBankDtlsSql = "SELECT id,
				Professional_id,
				Account_name,
				Account_number,
				Bank_name,
				Branch,
				IFSC_code,
				Account_type,
				Amount_with_spero,
				Amount_with_me
			FROM sp_bank_details
			WHERE id = '" . $id . "'";

			if ($this->num_of_rows($this->query($profBankDtlsSql))) {
				$getBankDtls = $this->fetch_array($this->query($profBankDtlsSql));
				if (!empty($getBankDtls)) {
					return $getBankDtls;
				} else {
					return 0;
				}
			}
		} else {
			return 0;
		}
	}

	/**
	 *
	 * This function is used for add bank activity
	 *
	 * @param array $args
	 *
	 * @return int $recordId
	 *
	 */
	public function addBankActivity($args)
	{
		$recordId = 0;
        if (!empty($args)) {
            $id         = $args['id'];
            $bankDtls   = $args['bank_dtls'];
            $insertData = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
			$insertActivityArr['module_id']     = '28';
			$insertActivityArr['module_name']   = 'Document Approval';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Bank details " . ( $id ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($bankDtls) && !empty($insertData)) {
				unset($bankDtls['id'],
					$bankDtls['Professional_id']
				);
                $bankDiff = array_diff_assoc($bankDtls, $insertData);
                if (!empty($bankDiff)) {
                    foreach ($bankDiff AS $key => $valBank) {
                        $activityDesc .= $key . " is change from " . $valBank . " to " . $insertData[$key] . "\r\n";
                    }
				}
            }
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
	}

	/**
	 *
	 * This function is used to get professional document details by id
	 * 
	 * @param int $docId
	 *
	 * @return array $result
	 *
	*/
	public function getProfDocDtlsById($docId)
    {
		if (!empty($docId)) {
			$profDocDtlsSql = "SELECT Documents_id,
				professional_id,
				document_list_id,
				url_path,
				rejection_reason,
				status,
				isVerified
			FROM sp_professional_documents
			WHERE Documents_id = '" . $docId . "'";

			if ($this->num_of_rows($this->query($profDocDtlsSql))) {
				$getDocDtls = $this->fetch_array($this->query($profDocDtlsSql));
				if (!empty($getDocDtls)) {
					return $getDocDtls;
				} else {
					return 0;
				}
			}
		} else {
			return 0;
		}
	}

	/**
	 *
	 * This function is used for add document activity
	 *
	 * @param array $args
	 *
	 * @return int $recordId
	 *
	 */
	public function addDocumentActivity($args)
	{
		$recordId = 0;
        if (!empty($args)) {
            $documentId = $args['document_id'];
            $docDtls    = $args['document_dtls'];
            $insertData = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
			$insertActivityArr['module_id']     = '28';
			$insertActivityArr['module_name']   = 'Document Approval';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Document details " . ( $documentId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($docDtls) && !empty($insertData)) {
				unset($docDtls['url_path'],
					$docDtls['document_list_id'],
					$docDtls['Documents_id'],
					$docDtls['isVerified']
				);
                $docDiff = array_diff_assoc($docDtls, $insertData);
                if (!empty($docDiff)) {
                    foreach ($docDiff AS $key => $valDoc) {
                        $activityDesc .= $key . " is change from " . $valDoc . " to " . $insertData[$key] . "\r\n";
                    }
				}
				$activityDesc .= "Updated information sent by Push Notification successfully \r\n";
            }
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
	}

	/**
     *
     * This function is used for add activity
     *
     * @param array $args
     *
     * @return int $recordId
     *
     */
    public function addProfessionalActivity($args)
    {
        $recordId = 0;
        if (!empty($args)) {
            $professionalId   = $args['professional_id'];
            $professionalDtls = $args['professional_dtls'];
            $insertData       = $args['record_data'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
			$insertActivityArr['module_id']     = '6';
			$insertActivityArr['module_name']   = 'Manage Professionals';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = "Professional details " . ( $professionalId ? 'modified' : 'added' ) . " successfully by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($professionalDtls) && !empty($insertData)) {
                unset($professionalDtls['professional_id'],
                    $professionalDtls['typeVal'],
                    $professionalDtls['locationNm'],
                    $professionalDtls['LocationPinCode'],
					$professionalDtls['statusVal'],
					$professionalDtls['added_by'],
					$professionalDtls['last_modified_by'],
					$professionalDtls['Services']
                );
                $professionalDiff = array_diff_assoc($professionalDtls, $insertData);
                if (!empty($professionalDiff)) {
                    foreach ($professionalDiff AS $key => $valProfessional) {
                        $activityDesc .= $key . " is change from " . $valProfessional . " to " . $insertData[$key] . "\r\n";
                    }
                }
            }
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
	}
	
	/**
	 *
	 * This function is used to add activity for service / sub service
	 * 
	 * @param array $args
     *
     * @return int $recordId
	 *
	 */
	public function addServiceActivity($args)
	{
		$recordId = 0;
        if (!empty($args)) {
            $professionalId = $args['professional_id'];
            $serviceId      = $args['service_id'];
			$subServiceId   = $args['sub_service_id'];
			$subServiceIds  = implode(",", $args['subServiceArr']);

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '6';
            $insertActivityArr['module_name']   = 'Manage Professionals';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');

            $activityDesc = ($subServiceIds ? 'Sub services' : 'Service' ) . " details added successfully for " . $professionalId . " by " . $_SESSION['admin_user_name'] . "\r\n";

            if (!empty($subServiceId) && !empty($subServiceIds)) {
				$activityDesc .= "Sub services are " . rtrim($subServiceIds, ',') . " added successfully. \r\n";
            } else {
				$activityDesc .= " Services  " . $serviceId . " added successfully. \r\n";
			}
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? nl2br($activityDesc) : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
	}

	/**
	 *
	 * This function is used to add activity for professional location preferences
	 * 
	 * @param array $args
     *
     * @return int $recordId
	 *
	 */
	public function addLocationActivity($args)
	{
		$recordId = 0;
        if (!empty($args)) {
            $professionalId = $args['professional_service_id'];
            $locationId     = $args['professional_location_id'];
			$activityDesc   = $args['activity_description'];

            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '6';
            $insertActivityArr['module_name']   = 'Manage Professionals';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? $activityDesc : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
	}

	/**
	 *
	 * This function is used to add activity for professional Availability
	 * 
	 * @param array $args
     *
     * @return int $recordId
	 *
	 */
	public function addAvailabilityActivity($args)
	{
		$recordId = 0;
        if (!empty($args)) {
            $professionalId = $args['professional_service_id'];
            $availabilityId = $args['professional_availability_id'];
			$activityDesc   = $args['activity_description'];
            $insertActivityArr = array();
            $insertActivityArr['module_type']   = '2';
            $insertActivityArr['module_id']     = '6';
            $insertActivityArr['module_name']   = 'Manage Professionals';
            $insertActivityArr['event_id']      = '';
            $insertActivityArr['purpose_id']    = '';
            $insertActivityArr['added_by_type'] = $_SESSION['admin_user_type'];
            $insertActivityArr['added_by_id']   = $_SESSION['admin_user_id'];
            $insertActivityArr['added_by_date'] = date('Y-m-d H:i:s');
            $insertActivityArr['activity_description'] = (!empty($activityDesc) ? $activityDesc : "");
            $recordId = $this->query_insert('sp_user_activity', $insertActivityArr);
            unset($insertActivityArr);
        }
        return $recordId;
	}
}
//END
?>