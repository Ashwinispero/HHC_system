<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
  //  header("Accept: application/json");
    //header("Content-Type: application/json; charset=UTF-8");
    
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
           $preWhere .=" AND t2.service_id='3'";  
        }
        
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .=" ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        if(!empty($isTrash) && $isTrash !='null')
        {
           $preWhere .=" AND t1.status='3'"; 
        }
        else 
        {
          $preWhere .=" AND t1.status IN ('1','2')";   
        }
        //$ProfessionalsSql="SELECT service_professional_id FROM sp_service_professionals WHERE 1 ".$preWhere." ".$filterWhere." ";
        $ProfessionalsSql="SELECT distinct t1.service_professional_id FROM sp_service_professionals as t1 LEFT JOIN sp_professional_services as t2 ON t1.service_professional_id = t2.service_professional_id WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($ProfessionalsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($ProfessionalsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT service_professional_id,professional_code,reference_type,title,name,first_name,middle_name,mobile_no,work_email_id,work_phone_no,location_id,location_id_home,set_location,address,status,isDelStatus,added_date,google_home_location,google_work_location FROM sp_service_professionals WHERE service_professional_id='".$val_records['service_professional_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
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
        $GetOneProfessionalSql="SELECT service_professional_id,professional_code,reference_type,title,name,first_name,middle_name,email_id,phone_no,mobile_no,dob,address,work_email_id,work_phone_no,work_address,location_id,location_id_home,set_location,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date,google_home_location,google_work_location FROM sp_service_professionals WHERE service_professional_id='".$service_professional_id."'";
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
    public function AddProfessional($arg)
    {
        $service_professional_id=$this->escape($arg['service_professional_id']);
        // Generate Random Number      
        $GetMaxRecordIdSql="SELECT MAX(service_professional_id) AS MaxId FROM sp_service_professionals";
        if($this->num_of_rows($this->query($GetMaxRecordIdSql)))
        {
            $MaxRecord=$this->fetch_array($this->query($GetMaxRecordIdSql));
            $getMaxRecordId=$MaxRecord['MaxId'];
        }
        else 
        {
            $getMaxRecordId=0;
        }
        $prefix=$GLOBALS['ProfPrefix'];
        $ProfessionalCode=Generate_Number($prefix,$getMaxRecordId);
      
        /*
        if(!empty($service_professional_id) && $service_professional_id !='')
            $ChkProfessionalSql="SELECT service_professional_id FROM sp_service_professionals WHERE  status !='3' AND service_professional_id !='".$service_professional_id."'";
        else 
            $ChkProfessionalSql="SELECT service_professional_id FROM sp_service_professionals WHERE  status !='3' and professional_code !='".$ProfessionalCode."'"; 
        */      

        if(!empty($service_professional_id) && $service_professional_id !='')
            $ChkProfessionalSql="SELECT service_professional_id FROM sp_service_professionals WHERE  mobile_no='".$arg['mobile_no']."' AND status !='3' AND service_professional_id !='".$service_professional_id."'";
        else
            $ChkProfessionalSql="SELECT service_professional_id FROM sp_service_professionals WHERE mobile_no='".$arg['mobile_no']."' AND status !='3' AND professional_code !='".$ProfessionalCode."'";   
       
    if($this->num_of_rows($this->query($ChkProfessionalSql))==0)
        {
     
            $insertData = array();
            if(empty($service_professional_id))
            $insertData['professional_code']=$ProfessionalCode;
             $insertData['reference_type']=$this->escape($arg['reference_type']);
            $insertData['title']=$this->escape($arg['title']);
			$insertData['Job_type']=$this->escape($arg['Job_type']);
            $insertData['name']=$this->escape($arg['name']);
            $insertData['first_name']=$this->escape($arg['first_name']);
            $insertData['middle_name']=$this->escape($arg['middle_name']);
            $insertData['email_id']=$this->escape($arg['email_id']);
            $insertData['phone_no']=$this->escape($arg['phone_no']);
            $insertData['mobile_no']=$this->escape($arg['mobile_no']);
            $insertData['dob']=$this->escape($arg['dob']);
            $insertData['address']=$this->escape($arg['address']);
            $insertData['work_email_id']=$this->escape($arg['work_email_id']);
            $insertData['work_phone_no']=$this->escape($arg['work_phone_no']);
            $insertData['work_address']=$this->escape($arg['work_address']);
            $insertData['location_id']=$this->escape($arg['location_id']);
            $insertData['location_id_home']=$this->escape($arg['location_id_home']);
            $insertData['set_location']=$this->escape($arg['set_location']);
            $insertData['google_home_location']=$this->escape($arg['google_home_location']);
            $insertData['google_work_location']=$this->escape($arg['google_work_location']);
            $insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            
            /*          get lettitude/ langitude          */
            if($arg['set_location'] == '2')
            {
                $locs = $arg['location_id'];
                $add = $arg['work_address'];
                $google_location = $arg['google_work_location'];
            }
            else
            {
                $locs = $arg['location_id_home'];
                $add = $arg['address'];
                $google_location = $arg['google_home_location'];
            }
            $region = 'IND';
            if($google_location == '')
            {
                $select_locationd = "select location from sp_locations where location_id = '".$locs."'";
                $valLocation = $this->fetch_array($this->query($select_locationd));
                $location = $valLocation['location'];
                $mainAddress = $add.','.$location.', Pune, Maharashtra,India';                
                $address = str_replace(" ", "+", $mainAddress);
            }
            else
                $address = str_replace(" ", "+", $google_location);
            
            $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
            $json = json_decode($json);

            $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

            $insertData['lattitude']=$lat;
            $insertData['langitude']=$long;
            /*          get lettitude/ langitude          */
        
            if(!empty($service_professional_id))
            {
                $where = "service_professional_id ='".$service_professional_id."'";
                $RecordId=$this->query_update('sp_service_professionals',$insertData,$where); 
                $serArrr['professional_id']=$service_professional_id;
            }
            else 
            {
                $insertData['status']=$this->escape($arg['status']);
                $insertData['added_by']=$this->escape($arg['added_by']);
                $insertData['added_date']=$this->escape($arg['added_date']);
                $RecordId=$this->query_insert('sp_service_professionals',$insertData);
                $serArrr['professional_id']=$RecordId;
            }
            $serArrr['service_ids']=$arg['service_ids'];
            $serArrr['added_by']=$arg['added_by'];
            
            $this->AssignServices($serArrr);
            
            if(!empty($RecordId))
                return $RecordId; 
            else
                return 0;
      }
      else
          return 0;
    }
    public function ChangeStatus($arg)
    {
        $service_professional_id=$this->escape($arg['service_professional_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkProfessionaSql="SELECT service_professional_id FROM sp_service_professionals WHERE service_professional_id='".$service_professional_id."'";
        if($this->num_of_rows($this->query($ChkProfessionaSql)))
        {
            if($istrashDelete)
            {
                // Delete Professional Other Details
                
                $DelProfOtherDtls="DELETE FROM sp_service_professional_details WHERE service_professional_id='".$service_professional_id."'";
                $this->query($DelProfOtherDtls);
                $UpdateStatusSql="DELETE FROM sp_service_professionals WHERE service_professional_id='".$service_professional_id."'";
            }
            else 
            {
                // Update Professional Other Details
                $UpdateProfOtherDtls="UPDATE sp_service_professional_details SET  status='".$status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE service_professional_id='".$service_professional_id."'";
                $this->query($UpdateProfOtherDtls);
                
                $UpdateStatusSql="UPDATE sp_service_professionals SET status='".$status."',isDelStatus='".$pre_status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE service_professional_id='".$service_professional_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return $RecordId;
        }
        else 
            return 0;
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
    public function AssignServices($arg)
    {
        $service_ids = $arg['service_ids'];
        if(count($service_ids))
        {
           $delete_allExistingrecord = "DELETE FROM sp_professional_services WHERE service_professional_id ='".$arg['professional_id']."'";
           $ptr_var = $this->query($delete_allExistingrecord);
           
           for($i=0;$i<count($service_ids);$i++)
           {
               $insertData = array();
               $insertData['service_id']=$service_ids[$i];
               $insertData['service_professional_id']=$arg['professional_id'];
               //$insertData['availability']=$arg['availability'];
               $insertData['status']=1;
               $insertData['added_by']=$arg['added_by'];
               $insertData['added_date'] = date('Y-m-d H:i:s');
               $insertData['modified_by']=$arg['added_by'];
               $insertData['last_modified_date'] = date('Y-m-d H:i:s');
               
               $this->query_insert('sp_professional_services',$insertData); 
           }

           return 'success';
        }
        else        
           return 0; 
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
		public function API_AddProfessional($arg)
    {
		    
        $GetMaxRecordIdSql="SELECT MAX(service_professional_id) AS MaxId FROM sp_service_professionals";
        if($this->num_of_rows($this->query($GetMaxRecordIdSql)))
        {
            $MaxRecord=$this->fetch_array($this->query($GetMaxRecordIdSql));
            $getMaxRecordId=$MaxRecord['MaxId'];
        }
        else 
        {
            $getMaxRecordId=0;
        }
        $prefix=$GLOBALS['ProfPrefix'];
        $ProfessionalCode=Generate_Number($prefix,$getMaxRecordId);
      
       
        if(!empty($service_professional_id) && $service_professional_id !='')
            $ChkProfessionalSql="SELECT service_professional_id FROM sp_service_professionals WHERE  mobile_no='".$arg['mobile_no']."' AND status !='3' AND service_professional_id !='".$service_professional_id."'";
        else
            $ChkProfessionalSql="SELECT service_professional_id FROM sp_service_professionals WHERE mobile_no='".$arg['mobile_no']."' AND status !='3' AND professional_code !='".$ProfessionalCode."'";   
       
    if($this->num_of_rows($this->query($ChkProfessionalSql))==0)
        {
			$reference_type=1;
            $insertData = array();
            //if(empty($service_professional_id))
            $insertData['professional_code']=$ProfessionalCode;
            $insertData['reference_type']=$reference_type;
            $insertData['title']=$this->escape($arg['title']);
			$insertData['Job_type']=$this->escape($arg['Job_type']);
            $insertData['name']=$this->escape($arg['name']);
            $insertData['first_name']=$this->escape($arg['first_name']);
            $insertData['middle_name']=$this->escape($arg['middle_name']);
             $insertData['document_status']=$this->escape($arg['document_status']);
            $insertData['email_id']=$this->escape($arg['email_id']);
            $insertData['phone_no']=$this->escape($arg['phone_no']);
            $insertData['mobile_no']=$this->escape($arg['mobile_no']);
            $insertData['dob']=$this->escape($arg['dob']);
            $insertData['address']=$this->escape($arg['address']);
            $insertData['work_email_id']=$this->escape($arg['work_email_id']);
            $insertData['work_phone_no']=$this->escape($arg['work_phone_no']);
            $insertData['work_address']=$this->escape($arg['work_address']);
            $insertData['location_id']=$this->escape($arg['location_id']);
            $insertData['location_id_home']=$this->escape($arg['location_id_home']);
            $insertData['set_location']=$this->escape($arg['set_location']);
            $insertData['google_home_location']=$this->escape($arg['google_home_location']);
            $insertData['google_work_location']=$this->escape($arg['google_work_location']);
            $insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
			$insertData['APP_password']=$this->escape($arg['Login_PW']);
			$insertData['reg_source']=2;
				
            
            /*          get lettitude/ langitude          */
            if($arg['set_location'] == '2')
            {
                $locs = $arg['location_id'];
                $add = $arg['work_address'];
                $google_location = $arg['google_work_location'];
            }
            else
            {
                $locs = $arg['location_id_home'];
                $add = $arg['address'];
                $google_location = $arg['google_home_location'];
            }
            $region = 'IND';
            if($google_location == '')
            {
                $select_locationd = "select location from sp_locations where location_id = '".$locs."'";
                $valLocation = $this->fetch_array($this->query($select_locationd));
                $location = $valLocation['location'];
                $mainAddress = $add.','.$location.', Pune, Maharashtra,India';                
                $address = str_replace(" ", "+", $mainAddress);
            }
            else
                $address = str_replace(" ", "+", $google_location);
            
            $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
            $json = json_decode($json);

            $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

            $insertData['lattitude']=$lat;
            $insertData['langitude']=$long;
            /*          get lettitude/ langitude          */
        
            if(!empty($service_professional_id))
            {
                $where = "service_professional_id ='".$service_professional_id."'";
                $RecordId=$this->query_update('sp_service_professionals',$insertData,$where); 
                $serArrr['professional_id']=$service_professional_id;
            }
            else 
            {
                $insertData['status']=$this->escape($arg['status']);
                $insertData['added_by']=$this->escape($arg['added_by']);
                $insertData['added_date']=$this->escape($arg['added_date']);
                $RecordId=$this->query_insert('sp_service_professionals',$insertData);
                $serArrr['professional_id']=$RecordId;
            }
            $serArrr['service_ids']=$arg['service_ids'];
            $serArrr['added_by']=$arg['added_by'];
            
           // $this->API_AssignServices($serArrr);
            
            if(!empty($RecordId))
                return $RecordId; 
            else
                return 0;
      }
      else
          return 0;
		
    }
public function API_AssignServices($arg)
    {
       
       
           $delete_allExistingrecord = "DELETE FROM sp_professional_services WHERE service_professional_id ='".$arg['professional_id']."'";
           $ptr_var = $this->query($delete_allExistingrecord);
           
           
               $insertData = array();
               $insertData['service_id']=$arg['service_id'];
               $insertData['service_professional_id']=$arg['service_professional_id'];
               //$insertData['availability']=$arg['availability'];
               $insertData['status']=2;
               $insertData['added_by']=$arg['added_by'];
               $insertData['added_date'] = date('Y-m-d H:i:s');
               $insertData['modified_by']=$arg['added_by'];
               $insertData['last_modified_date'] = date('Y-m-d H:i:s');
               
               $this->query_insert('sp_professional_services',$insertData); 
			  $Last_id=mysql_insert_id();
			
			 
           

           return 'success';
      
    }
    public function API_updateServices($arg)
    {
       
       
           $delete_allExistingrecord = "DELETE FROM sp_professional_services WHERE service_professional_id ='".$arg['professional_id']."'";
           $ptr_var = $this->query($delete_allExistingrecord);
           
           $insertData['service_id']=$arg['service_id'];
               $insertData = array();
               $insertData['service_id']=$service_ids;
               $insertData['service_professional_id']=$arg['professional_id'];
               //$insertData['availability']=$arg['availability'];
               $insertData['status']=2;
               $insertData['added_by']=$arg['added_by'];
               $insertData['added_date'] = date('Y-m-d H:i:s');
               $insertData['modified_by']=$arg['added_by'];
               $insertData['last_modified_date'] = date('Y-m-d H:i:s');
               
               $this->query_insert('sp_professional_services',$insertData); 
			  $Last_id=mysql_insert_id();
			
			 
           

           return 'success';
      
    }
    public function API_AddProfessionalOtherDtls($arg)
    {
        ///$service_professional_id=$this->escape($arg['service_professional_id']);
        //$detail_id=$this->escape($arg['detail_id']);
        if(!empty($detail_id) && $detail_id !='')
             $ChkProfessionalOtherDtlsSql="SELECT detail_id FROM sp_service_professional_details WHERE service_professional_id='".$service_professional_id."' AND detail_id !='".$detail_id."'";
        else 
            $ChkProfessionalOtherDtlsSql="SELECT detail_id FROM sp_service_professional_details WHERE service_professional_id='".$service_professional_id."'"; 
      
      if($this->num_of_rows($this->query($ChkProfessionalOtherDtlsSql))==0)
      {
            
        $insertData['service_professional_id']=$this->escape($arg['Professional_id']); 
        $insertData['qualification']=$this->escape($arg['qualification']);
        $insertData['specialization']=$this->escape($arg['specialization']);
        $insertData['skill_set']=$this->escape($arg['skill_set']);
        $insertData['work_experience']=$this->escape($arg['work_experience']);
        $insertData['hospital_attached_to']=$this->escape($arg['hospital_attached_to']);
        $insertData['pancard_no']=$this->escape($arg['pancard_no']);
        $insertData['reference_1']=$this->escape($arg['reference_1']);
         $insertData['reference_2']=$this->escape($arg['reference_2']);
          $insertData['reference_1_contact_num']=$this->escape($arg['reference_1_contact_num']);
         $insertData['reference_2_contact_num']=$this->escape($arg['reference_2_contact_num']);
          
        
        
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
	
	public function API_AddDevice_info($arg)
	{
		//$insertData['Professional_id']=$this->escape($arg['Professional_id']); 
        $insertData['device_id']=$this->escape($arg['device_id']);
        $insertData['OSVersion']=$this->escape($arg['OSVersion']);
        $insertData['OSName']=$this->escape($arg['OSName']);
        $insertData['DevicePlatform']=$this->escape($arg['DevicePlatform']);
        $insertData['AppVersion']=$this->escape($arg['AppVersion']);
        $insertData['DeviceTimezone']=$this->escape($arg['DeviceTimezone']);
		$insertData['DeviceCurrentTimestamp']=$this->escape($arg['DeviceCurrentTimestamp']);
        $insertData['Token']=$this->escape($arg['Token']);
        $insertData['ModelName']=$this->escape($arg['ModelName']);
        $insertData['added_date']=$this->escape($arg['added_date']);
		$RecordId=$this->query_insert('sp_professional_device_info',$insertData);		
		
		
		
		
	}
	public function API_addsubservices($subs)
	{
		
        
		
			$insertData['service_professional_id']=$this->escape($subs['service_professional_id']);
				$insertData['service_id']=$this->escape($subs['service_id']);
			$insertData['sub_service_id']=$this->escape($subs['sub_service_id']);
			$RecordId=$this->query_insert('sp_professional_sub_services',$insertData);			
					
		
		
		
	}
	public function API_AddProfessionalLeaves($arg)
	{
		 $insertData['service_professional_id']=$this->escape($arg['service_professional_id']);
        $insertData['date_form']=$this->escape($arg['startDateTime']);
        $insertData['date_to']=$this->escape($arg['endDateTime']);
        $insertData['Note']=$this->escape($arg['reason']);
		$insertData['date']=$this->escape($arg['date']);
		$insertData['Leave_status']='1';
		
       	$insertData['Leave_Conflit']=$this->escape($arg['Leave_Conflit']);
	   $RecordId=$this->query_insert('sp_professional_weekoff',$insertData);
	}
	
	public function API_AddBankDetails($arg)
		
	
	{
		 $insertData['Professional_id']=$this->escape($arg['Professional_id']);
        $insertData['Account_number']=$this->escape($arg['Account_number']);
        $insertData['Account_name']=$this->escape($arg['Account_name']);
        $insertData['Bank_name']=$this->escape($arg['Bank_name']);
		$insertData['Branch']=$this->escape($arg['Branch']);
		$insertData['IFSC_code']=$this->escape($arg['IFSC_code']);
		$insertData['Account_type']=$this->escape($arg['Account_type']);
		
       
	   $RecordId=$this->query_insert('sp_bank_details',$insertData);
	}
	
	public function API_Addsessions($arg)
	{
		 $insertData['device_id']=$this->escape($arg['device_id']);
        $insertData['service_professional_id']=$this->escape($arg['service_professional_id']);
        $insertData['added_date']=$this->escape($arg['added_date']);
        $insertData['status']=$this->escape($arg['status']);
       
	   $RecordId=$this->query_insert('sp_session',$insertData);
	}
	public function API_payments($args)
	{
	
		
		 $insertData['event_id']=$this->escape($args['event_id']);
         $insertData['cheque_DD__NEFT_no']=$this->escape($args['cheque_DD__NEFT_no']);
		 $insertData['party_bank_name']=$this->escape($args['party_bank_name']);
		 	 $insertData['cheque_path_id']=$this->escape($args['cheque_path_id']);
		 $insertData['cheque_DD__NEFT_date']=$this->escape($args['cheque_DD__NEFT_date']);
		 $insertData['professional_name']=$this->escape($args['professional_name']);
		 $insertData['amount']=$this->escape($args['amount']);
         $insertData['date_time']=$this->escape($args['date_time']);
		 $insertData['Payment_type']=$this->escape($args['Payment_type']);
		 $insertData['Payment_mode']=$this->escape($args['Payment_mode']);
		   $insertData['OTP_verifivation']=2;
		  $insertData['Session_id']=$this->escape($args['Session_id']);
		 
        
	   $RecordId=$this->query_insert('sp_payments',$insertData);
	   		
	}
		public function API_payments_by_professional($args)
	{
	
		
		 $insertData['event_id']=$this->escape($args['event_id']);
		  $insertData['event_requirement_id']=$this->escape($args['event_requirement_id']);
		 	 $insertData['Session_id']=$this->escape($args['Session_id']);
		 	 $insertData['professional_vender_id']=$this->escape($args['professional_vender_id']);
         $insertData['cheque_DD__NEFT_no']=$this->escape($args['cheque_DD__NEFT_no']);
		 $insertData['party_bank_name']=$this->escape($args['party_bank_name']);
		 	 $insertData['cheque_path_id']=$this->escape($args['cheque_path_id']);
		 $insertData['cheque_DD__NEFT_date']=$this->escape($args['cheque_DD__NEFT_date']);
		 $insertData['professional_name']=$this->escape($args['professional_name']);
		 $insertData['amount']=$this->escape($args['amount']);
         $insertData['date_time']=$this->escape($args['date_time']);
		 $insertData['Payment_type']=$this->escape($args['Payment_type']);
		 $insertData['Payment_mode']=$this->escape($args['Payment_mode']);
		 $insertData['OTP_verifivation']=$this->escape($args['OTP_verifivation']); 
		  // $insertData['OTP_verifivation']=2;
		
		  $insertData['Session_id']=$this->escape($args['Session_id']);
		 
        
	   $RecordId=$this->query_insert('sp_payments_received_by_professional',$insertData);
	   		
	}
	 public function API_jobclosure_detail_datewise($args)
	{
												
			
																			
		 $insertData['event_id']=$this->escape($args['event_id']);
         $insertData['service_id']=$this->escape($args['service_id']);
		 $insertData['sub_service_id']=$this->escape($args['sub_service_id']);
		 $insertData['service_date']=$this->escape($args['service_date']);
		 $insertData['actual_service_date']=$this->escape($args['actual_service_date']);
         $insertData['job_closure_detail']=$this->escape($args['job_closure_detail']);
		 $insertData['StartTime']=$this->escape($args['StartTime']);
		 $insertData['Endtime']=$this->escape($args['Endtime']);
         $insertData['added_by']=$this->escape($args['added_by']);
         $insertData['added_date']=$this->escape($args['added_date']);
		
		 
	   $RecordId=$this->query_insert('sp_jobclosure_detail_datewise',$insertData);
	   	

	}
	public function API_sp_payment_details($args)
	{																
		 $insertData['event_id']=$this->escape($args['event_id']);
         $insertData['event_requrement_id']=$this->escape($args['event_requrement_id']);
		 $insertData['amount']=$this->escape($args['amount']);
		 $insertData['payment_id']=$this->escape($args['payment_id']);
		 $insertData['date']=$this->escape($args['date']);
         $insertData['status']=$this->escape($args['status']);
		  $insertData['OTP_verifivation']=$this->escape($args['OTP_verifivation']);
		  $insertData['Session_id']=$this->escape($args['Session_id']);
		 
		 
	   $RecordId=$this->query_insert('sp_payment_details',$insertData);
	   	

		
	}

	public function API_availblity($args)
	{		

		 $insertData['day']=$this->escape($args['day']);
         $insertData['professional_service_id']=$this->escape($args['professional_service_id']);
		
		
		
		 
	   $RecordId=$this->query_insert('sp_professional_avaibility',$insertData);
	   	

		
	}
	
		public function API_Add_Documents($arrs)
	{	
	    
	    
						
		$insertData['professional_id']=$this->escape($arrs['professional_id']);
		$insertData['document_list_id']=$this->escape($arrs['document_list_id']);
		$insertData['url_path']=$this->escape($arrs['url_path']);
		//$insertData['Name']=$this->escape($arrs['Name']);
		$insertData['status']='4';
		$insertData['isVerified']='1';
			
		
         
		 
	   $RecordId=$this->query_insert('sp_professional_documents',$insertData);
	   	 $Documents_id=mysql_insert_id();

		
	}
	
	public function API_AddsessionCheque($args)
	{	
						
		$insertData['Detailed_plan_of_care_id']=$this->escape($args['Detailed_plan_of_care_id']);
		$insertData['Url_path']=$this->escape($args['Url_path']);
		$insertData['Added_date']=$this->escape($args['Added_date']);
		
         
		 
	   $RecordId=$this->query_insert('sp_cheque_images',$insertData);
	   	 $Documents_id=mysql_insert_id();

		
	}
	 public function API_Extend_services($args)
	{
												
			
																			
		 $insertData['event_id']=$this->escape($args['event_id']);
		   $insertData['plan_of_care_id']=$this->escape($args['plan_of_care_id']);
         $insertData['event_requirement_id']=$this->escape($args['event_requirement_id']);
		 $insertData['index_of_Session']=$this->escape($args['index_of_Session']);
		 $insertData['service_date']=$this->escape($args['service_date']);
		 $insertData['service_date_to']=$this->escape($args['service_date_to']);
		  $insertData['professional_vender_id']=$this->escape($args['professional_vender_id']);
         $insertData['Actual_Service_date']=$this->escape($args['Actual_Service_date']);
		 $insertData['start_date']=$this->escape($args['start_date']);
		
         $insertData['end_date']=$this->escape($args['end_date']);
         $insertData['added_date']=$this->escape($args['added_date']);
		 $insertData['last_modified_date']=$this->escape($args['last_modified_date']);
		 
	   $RecordId=$this->query_insert('sp_detailed_event_plan_of_care',$insertData);
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
	   $RecordId=$this->query_insert('sp_professional_notification',$insertData);
	   	$notification_id = mysql_insert_id();

	}
	
	 public function API_AddSession($args)
	{
												
			
				               
																		
		 $insertData['device_id']=$this->escape($args['device_id']);
		 $insertData['service_professional_id']=$this->escape($args['service_professional_id']);
         $insertData['added_date']=$this->escape($args['added_date']);
         $insertData['status']=$this->escape($args['status']);
         	 
	   $RecordId=$this->query_insert('sp_session',$insertData);
	   	

	}
	 public function API_Add_notification_subservices($arg)
	{
			               
																		
		 $insertData['professional_vender_id']=$this->escape($arg['professional_vender_id']);
		  $insertData['sub_service_id']=$this->escape($arg['sub_service_id']);
		   $insertData['notification_id']=$this->escape($arg['notification_id']);
		
         	 
	   $RecordId=$this->query_insert('sp_notification_subservices',$insertData);
	   	

	}
	
	
	 
	
	
}

//END

?>