<!DOCTYPE html>
<?php require_once('inc_classes.php'); 
        //require_once '../classes/locationsClass.php';
        //$locationsClass = new locationsClass();
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

	//-----Author: ashwini 31-05-2016-----
	//--Code for date range--
	$PD=$_GET['Professional_id'];
	
//echo $Next_date;

 ?>
 <div id="Next_date_report_Nurse">
				<!--<div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                       <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Download Report" onclick="return Nurse_Scheduling_dw();">
                            <img src="images/download.png" border="0" class="example-fade" />                                
                        </a>
                    </div>-->
					<div class="col-lg-2 paddingR0 pull-LEFT text-LEFT dropdown">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Back" onclick="return Previous_date_Nurse();">
                            <img src="images/Button-Previous-icon.png" border="0" class="example-fade" />                                
                        </a>
                    </div>	
					<div class="col-lg-2 paddingR0 pull-LEFT text-LEFT dropdown" ><label >Select professional Name</label></div>
				<div class="col-lg-3 paddingR0 pull-LEFT text-LEFT dropdown">
					
					<input type="text" id="Professional_id" onchange="Nurse_Profesional_Name_Search(this.value)"  list="Professional_Nurse" placeholder="Enter Professional Name" style="width:100%">
					<datalist id="Professional_Nurse">
						<?php
						$Query=mysql_query("SELECT * FROM `sp_professional_services` where service_id='5' and status='1' ORDER BY service_professional_id  ASC");
						$row_count = mysql_num_rows($Query);
						if($row_count > 0)
						{
							while ($Professional_id = mysql_fetch_array($Query))
							{
								$service_professional_id=$Professional_id['service_professional_id'];
								$query1=mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id' and status='1'");
								if(mysql_num_rows($query1) < 1 )
								{
									//echo 'abc';
								}
								else
								{
					
									$Professional_name = mysql_fetch_array($query1) or die(mysql_error());
									$title=$Professional_name['title'];
									$name=$Professional_name['name'];
									$first_name=$Professional_name['first_name'];
									$middle_name=$Professional_name['middle_name'];
									?>
									
									<option id="<?php echo  $service_professional_id ;?>" value="<?php echo $title.' '.$first_name.' '.$middle_name.' '.$name;?>"> </option>
									
									<?php
								}
								
							}
							
						}
						?>
					</datalist>
					
					<!--<input type="text" id="default" list="languages" placeholder="JavaScript">-->
				</div>
				
				<div class="col-lg-2 paddingR0 pull-right text-right dropdown">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-original-title="Next" onclick="return Next_date_Nurse();">
                            <img src="images/Button-Next-icon.png" border="0" class="example-fade" />                                
                        </a>
                </div>
                <div class="panel-body">
				<table class="table table-bordered">
				<?php 
	
		
		date_default_timezone_set('Asia/Kolkata'); 
			$date = date('d-m-Y');
			$new_date=date('Y-m-d', strtotime($date));
			
			$date1 = strtotime("+1 day");
			$date1= date('d-m-Y', $date1);
			$new_date1=date('Y-m-d', strtotime($date1));
			
			$date2 = strtotime("+2 day");
			$date2= date('d-m-Y', $date2);
			$new_date2=date('Y-m-d', strtotime($date2));
			
			$date3 = strtotime("+3 day");
			$date3= date('d-m-Y', $date3);
			$new_date3=date('Y-m-d', strtotime($date3));
			
			$date4 = strtotime("+4 day");
			$date4= date('d-m-Y', $date4);
			$new_date4=date('Y-m-d', strtotime($date4));
			
			$date5 = strtotime("+5 day");
			$date5= date('d-m-Y', $date5);
			$new_date5=date('Y-m-d', strtotime($date5));
			
			$date6 = strtotime("+6 day");
			$date6= date('d-m-Y', $date6);
			$new_date6=date('Y-m-d', strtotime($date6));
			
			//echo $new_date6;
		
		?>
		<input style="display:none" id="Next_date_Nurse" value="<?php echo $date6;?>"></input>
	<input style="display:none" id="Previous_date_Nurse" value="<?php echo $date;?>"></input>
	
  <thead>
    <tr>
      <th style="width:11%">Nurse Name</th>
      <th style="width:12%">Prefered Location</th>
      <th style="width:5%">Type</th>
      <th align="left" style="width:10%"><?php echo $date;?></th>
      <th align="left" style="width:10%"><?php echo $date1;?></th>
      <th style="width:10%"><?php echo $date2;?></th>
	  <th style="width:10%"><?php echo $date3;?></th>
	  <th style="width:10%"><?php echo $date4;?></th>
	  <th style="width:10%"><?php echo $date5;?></th>
	  <th style="width:10%"><?php echo $date6;?></th>
    </tr>
  </thead>
  <tbody>
<?php 

				$service_professional_id=$PD;
				//$service_professional_id='220';
				$query=mysql_query("SELECT * FROM sp_service_professionals  where service_professional_id='$service_professional_id' and status='1'");
				if(mysql_num_rows($query) < 1 )
				{
					//echo 'abc';
				}
				else
				{
					//echo 'xyz';
					$row = mysql_fetch_array($query) or die(mysql_error());
					$title=$row['title'];
					$name=$row['name'];
					$first_name=$row['first_name'];
					$middle_name=$row['middle_name'];
					$mobile_no=$row['mobile_no'];
					$Job_type=$row['Job_type'];
					
						$work_location=mysql_query("SELECT * FROM sp_service_professionals where service_professional_id='$service_professional_id'") or die(mysql_error());
				$work_location = mysql_fetch_array($work_location) or die(mysql_error());
				$set_location=$work_location['set_location'];
				if($set_location=='1')
				{
					
					$location_id=$work_location['location_id_home'];
					$google_location = $work_location['google_home_location'];
					//$preferd_location=
				}
				elseif($set_location=='2')
				{
					$location_id=$work_location['location_id'];
					$google_location = $work_location['google_work_location'];
					//$preferd_location=
				}
				if($google_location == '')
				{
					$LocationSql="SELECT location_id,location,pin_code FROM sp_locations WHERE location_id='".$location_id."'";
					$LocationDtls=$db->fetch_array($db->query($LocationSql));
					if($LocationDtls['location'])
						$google_location=$LocationDtls['location']; 
				}
			$event_professional=mysql_query("SELECT * FROM sp_event_requirements  where professional_vender_id='$service_professional_id'");
			if(mysql_num_rows($event_professional) < 1 )
				{
					$day1='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
					$day2='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
					$day3='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
					$day4='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
					$day5='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
					$day6='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
					$day7='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
				}
				else{
			while ($rows = mysql_fetch_array($event_professional))
			{
				$event_requirement_id=$rows['event_requirement_id'];
			
					$requirement_id=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id'");
					while ($requirement_id_pln = mysql_fetch_array($requirement_id))
					{
						$plan_of_care_id=$requirement_id_pln['plan_of_care_id'];
						
						$plan_of_care=mysql_query("SELECT * FROM sp_event_plan_of_care  where plan_of_care_id='$plan_of_care_id'");
					$plan_of_care_detail = mysql_fetch_array($plan_of_care) or die(mysql_error());
					$service_date=$plan_of_care_detail['service_date'];
					//echo $service_date;
					$service_date_to=$plan_of_care_detail['service_date_to'];
					$start_time=$plan_of_care_detail['start_date'];
					$end_time=$plan_of_care_detail['end_date'];
					
						//echo $service_date;
						$begin = new DateTime($service_date);
						$end=date('Y-m-d', strtotime('+1 day', strtotime($service_date_to)));
						$end = new DateTime($end);
						$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
						//echo $new_date;
						$i=1;
						foreach($daterange as $date)
						{
							$final_date=$date->format("Y-m-d");
						//echo $final_date;
						if($final_date==$new_date)
						{
							$day1='<span class="glyphicon glyphicon-remove" style="color:red"></span>';
							$event_professional1=mysql_query("SELECT * FROM sp_event_requirements  where professional_vender_id='$service_professional_id' ");
							while ($rows1 = mysql_fetch_array($event_professional1))
							{
								$event_requirement_id_new=$rows1['event_requirement_id'];
								//echo $event_requirement_id;
								$requirement_id_new=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id_new'");
								while ($requirement_id_pln_new = mysql_fetch_array($requirement_id_new))
								{
								$plan_of_care_id1=$requirement_id_pln_new['plan_of_care_id'];
								
								$sql = "SELECT * FROM sp_event_plan_of_care  where plan_of_care_id='$plan_of_care_id1'  ";
								//echo $service_professional_id;
								$plan_of_care1=mysql_query($sql);
								$plan_of_care_detail1 = mysql_fetch_array($plan_of_care1) or die(mysql_error());
								$start_time1=$plan_of_care_detail1['start_date'];
								$end_time1=$plan_of_care_detail1['end_date'];
								$service_date=$plan_of_care_detail1['service_date'];
								$service_date_to=$plan_of_care_detail1['service_date_to'];
								$event_id=$plan_of_care_detail1['event_id'];
								
								$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
								$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
								$event_code=$sql11['event_code'];
								$patient_id=$sql11['patient_id'];
								
								$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
								$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
								$Patient_name=$patient_nm['name'];
								$Patient_first_name=$patient_nm['first_name'];
								$Patient_middle_name=$patient_nm['middle_name'];
								$patient_name=$Patient_first_name.' '.$Patient_name;
								if ($service_date_to < $new_date)
								{}
								else
								{
									if($new_date >= $service_date && $service_date_to >= $new_date)
									{	
									$day1=$day1.'<br>'.$start_time1.'-'.$end_time1.'<br><strong>'.$event_code.'</strong><br><strong>'.$patient_name.'</strong><br>';
									}
									else
									{
										
									}
									
									
								}
								$start_time1='';
								$end_time1='';
							}
							}
							
						}	
						elseif($final_date==$new_date1)
						{
							$day2='<span class="glyphicon glyphicon-remove" style="color:red"></span>';
							$event_professional1=mysql_query("SELECT * FROM sp_event_requirements  where professional_vender_id='$service_professional_id' ");
							while ($rows1 = mysql_fetch_array($event_professional1))
							{
								$event_requirement_id_new=$rows1['event_requirement_id'];
								//echo $event_requirement_id;
								$requirement_id_new=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id_new'");
								while ($requirement_id_pln_new = mysql_fetch_array($requirement_id_new))
								{
								$plan_of_care_id1=$requirement_id_pln_new['plan_of_care_id'];
								
								$sql = "SELECT * FROM sp_event_plan_of_care  where plan_of_care_id='$plan_of_care_id1'  ";
								//echo $service_professional_id;
								$plan_of_care1=mysql_query($sql);
								$plan_of_care_detail1 = mysql_fetch_array($plan_of_care1) or die(mysql_error());
								$start_time1=$plan_of_care_detail1['start_date'];
								$end_time1=$plan_of_care_detail1['end_date'];
								$service_date=$plan_of_care_detail1['service_date'];
								$service_date_to=$plan_of_care_detail1['service_date_to'];
								$event_id=$plan_of_care_detail1['event_id'];
								
								$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
								$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
								$event_code=$sql11['event_code'];
								$patient_id=$sql11['patient_id'];
								
								$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
								$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
								$Patient_name=$patient_nm['name'];
								$Patient_first_name=$patient_nm['first_name'];
								$Patient_middle_name=$patient_nm['middle_name'];
								$patient_name=$Patient_first_name.' '.$Patient_name;
								if ($service_date_to < $new_date)
								{
									//$day2=$day2;
								}
								else
								{
									if($new_date1 >= $service_date && $service_date_to >= $new_date1)
									{	
									$day2=$day2.'<br>'.$start_time1.'-'.$end_time1.'<br><strong>'.$event_code.'</strong><br><strong>'.$patient_name.'</strong><br>';
									}
									else
									{
										
									}
								}
								$start_time1='';
							$end_time1='';
							}
							}
						}	
						elseif($final_date==$new_date2)
						{
							$day3='<span class="glyphicon glyphicon-remove" style="color:red"></span>';
							$event_professional1=mysql_query("SELECT * FROM sp_event_requirements  where professional_vender_id='$service_professional_id' ");
							while ($rows1 = mysql_fetch_array($event_professional1))
							{
								$event_requirement_id_new=$rows1['event_requirement_id'];
								//echo $event_requirement_id;
								$requirement_id_new=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id_new'");
								while ($requirement_id_pln_new = mysql_fetch_array($requirement_id_new))
								{
								$plan_of_care_id1=$requirement_id_pln_new['plan_of_care_id'];
								
								$sql = "SELECT * FROM sp_event_plan_of_care  where plan_of_care_id='$plan_of_care_id1'  ";
								//echo $service_professional_id;
								$plan_of_care1=mysql_query($sql);
								$plan_of_care_detail1 = mysql_fetch_array($plan_of_care1) or die(mysql_error());
								$start_time1=$plan_of_care_detail1['start_date'];
								$end_time1=$plan_of_care_detail1['end_date'];
								$service_date=$plan_of_care_detail1['service_date'];
								$service_date_to=$plan_of_care_detail1['service_date_to'];
								$event_id=$plan_of_care_detail1['event_id'];
								
								$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
								$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
								$event_code=$sql11['event_code'];
								$patient_id=$sql11['patient_id'];
								
								$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
								$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
								$Patient_name=$patient_nm['name'];
								$Patient_first_name=$patient_nm['first_name'];
								$Patient_middle_name=$patient_nm['middle_name'];
								$patient_name=$Patient_first_name.' '.$Patient_name;
								if ($service_date_to < $new_date)
								{}
								else
								{
									if($new_date2 >= $service_date && $service_date_to >= $new_date2)
									{	
									$day3=$day3.'<br>'.$start_time1.'-'.$end_time1.'<br><strong>'.$event_code.'</strong><br><strong>'.$patient_name.'</strong><br>';
									}
									else
									{
										
									}
									
									
								}
								$start_time1='';
								$end_time1='';
							}
							}	
						}	
						elseif($final_date==$new_date3)
						{
							$day4='<span class="glyphicon glyphicon-remove" style="color:red"></span>';
							$event_professional1=mysql_query("SELECT * FROM sp_event_requirements  where professional_vender_id='$service_professional_id' ");
							while ($rows1 = mysql_fetch_array($event_professional1))
							{
								$event_requirement_id_new=$rows1['event_requirement_id'];
								//echo $event_requirement_id;
								$requirement_id_new=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id_new'");
								while ($requirement_id_pln_new = mysql_fetch_array($requirement_id_new))
								{
								$plan_of_care_id1=$requirement_id_pln_new['plan_of_care_id'];
								
								//echo $plan_of_care_id1;
								$sql = "SELECT * FROM sp_event_plan_of_care  where plan_of_care_id='$plan_of_care_id1'  ";
								//echo $service_professional_id;
								$plan_of_care1=mysql_query($sql);
								$plan_of_care_detail1 = mysql_fetch_array($plan_of_care1) or die(mysql_error());
								$start_time1=$plan_of_care_detail1['start_date'];
								$end_time1=$plan_of_care_detail1['end_date'];
								$service_date=$plan_of_care_detail1['service_date'];
								$service_date_to=$plan_of_care_detail1['service_date_to'];
								$event_id=$plan_of_care_detail1['event_id'];
								
								$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
								$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
								$event_code=$sql11['event_code'];
								$patient_id=$sql11['patient_id'];
								
								$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
								$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
								$Patient_name=$patient_nm['name'];
								$Patient_first_name=$patient_nm['first_name'];
								$Patient_middle_name=$patient_nm['middle_name'];
								$patient_name=$Patient_first_name.' '.$Patient_name;
								if ($service_date_to < $new_date)
								{}
								else
								{
									if($new_date3 >= $service_date && $service_date_to >= $new_date3)
									{	
									$day4=$day4.'<br>'.$start_time1.'-'.$end_time1.'<br><strong>'.$event_code.'</strong><br><strong>'.$patient_name.'</strong><br>';
									}
									else
									{
										
									}
									
									
								}
								$start_time1='';
								$end_time1='';
							}
							}
						}	
						elseif($final_date==$new_date4)
						{
							$day5='<span class="glyphicon glyphicon-remove" style="color:red"></span>';
							$event_professional1=mysql_query("SELECT * FROM sp_event_requirements  where professional_vender_id='$service_professional_id' ");
							while ($rows1 = mysql_fetch_array($event_professional1))
							{
								$event_requirement_id_new=$rows1['event_requirement_id'];
								//echo $event_requirement_id;
								$requirement_id_new=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id_new'");
								while ($requirement_id_pln_new = mysql_fetch_array($requirement_id_new))
								{
								$plan_of_care_id1=$requirement_id_pln_new['plan_of_care_id'];
								
								
								$sql = "SELECT * FROM sp_event_plan_of_care  where plan_of_care_id='$plan_of_care_id1'  ";
								//echo $service_professional_id;
								$plan_of_care1=mysql_query($sql);
								$plan_of_care_detail1 = mysql_fetch_array($plan_of_care1) or die(mysql_error());
								$start_time1=$plan_of_care_detail1['start_date'];
								$end_time1=$plan_of_care_detail1['end_date'];
								$service_date=$plan_of_care_detail1['service_date'];
								$service_date_to=$plan_of_care_detail1['service_date_to'];
								$event_id=$plan_of_care_detail1['event_id'];
								
								$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
								$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
								$event_code=$sql11['event_code'];
								$patient_id=$sql11['patient_id'];
								
								$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
								$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
								$Patient_name=$patient_nm['name'];
								$Patient_first_name=$patient_nm['first_name'];
								$Patient_middle_name=$patient_nm['middle_name'];
								$patient_name=$Patient_first_name.' '.$Patient_name;
								if ($service_date_to < $new_date)
								{}
								else
								{
									if($new_date4 >= $service_date && $service_date_to >= $new_date4)
									{	
									$day5=$day5.'<br>'.$start_time1.'-'.$end_time1.'<br><strong>'.$event_code.'</strong><br><strong>'.$patient_name.'</strong><br>';
									}
									else
									{
										
									}
									
									
								}
								$start_time1='';
								$end_time1='';
							}
						}
						}	
						elseif($final_date==$new_date5)
						{
							$day6='<span class="glyphicon glyphicon-remove" style="color:red"></span>';
							$event_professional1=mysql_query("SELECT * FROM sp_event_requirements  where professional_vender_id='$service_professional_id' ");
							while ($rows1 = mysql_fetch_array($event_professional1))
							{
								$event_requirement_id_new=$rows1['event_requirement_id'];
								//echo $event_requirement_id;
								$requirement_id_new=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id_new'");
								while ($requirement_id_pln_new = mysql_fetch_array($requirement_id_new))
								{
								$plan_of_care_id1=$requirement_id_pln_new['plan_of_care_id'];
								
								
								$sql = "SELECT * FROM sp_event_plan_of_care  where plan_of_care_id='$plan_of_care_id1'  ";
								//echo $service_professional_id;
								$plan_of_care1=mysql_query($sql);
								$plan_of_care_detail1 = mysql_fetch_array($plan_of_care1) or die(mysql_error());
								$start_time1=$plan_of_care_detail1['start_date'];
								$end_time1=$plan_of_care_detail1['end_date'];
								$service_date=$plan_of_care_detail1['service_date'];
								$service_date_to=$plan_of_care_detail1['service_date_to'];
								$event_id=$plan_of_care_detail1['event_id'];
								
								$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
								$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
								$event_code=$sql11['event_code'];
								$patient_id=$sql11['patient_id'];
								
								$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
								$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
								$Patient_name=$patient_nm['name'];
								$Patient_first_name=$patient_nm['first_name'];
								$Patient_middle_name=$patient_nm['middle_name'];
								$patient_name=$Patient_first_name.' '.$Patient_name;
								if ($service_date_to < $new_date)
								{}
								else
								{
									if($new_date5 >= $service_date && $service_date_to >= $new_date5)
									{	
									$day6=$day6.'<br>'.$start_time1.'-'.$end_time1.'<br><strong>'.$event_code.'</strong><br><strong>'.$patient_name.'</strong><br>';
									}
									else
									{
										
									}
									
									
								}
								$start_time1='';
								$end_time1='';
							}
							}
						}	
						elseif($final_date==$new_date6)
						{
							$day7='<span class="glyphicon glyphicon-remove" style="color:red"></span>';
							$event_professional1=mysql_query("SELECT * FROM sp_event_requirements  where professional_vender_id='$service_professional_id' ");
							while ($rows1 = mysql_fetch_array($event_professional1))
							{
								$event_requirement_id_new=$rows1['event_requirement_id'];
								//echo $event_requirement_id;
								$requirement_id_new=mysql_query("SELECT * FROM sp_event_plan_of_care  where event_requirement_id='$event_requirement_id_new'");
								while ($requirement_id_pln_new = mysql_fetch_array($requirement_id_new))
								{
								$plan_of_care_id1=$requirement_id_pln_new['plan_of_care_id'];
								
								
								$sql = "SELECT * FROM sp_event_plan_of_care  where plan_of_care_id='$plan_of_care_id1'  ";
								//echo $service_professional_id;
								$plan_of_care1=mysql_query($sql);
								$plan_of_care_detail1 = mysql_fetch_array($plan_of_care1) or die(mysql_error());
								$start_time1=$plan_of_care_detail1['start_date'];
								$end_time1=$plan_of_care_detail1['end_date'];
								$service_date=$plan_of_care_detail1['service_date'];
								$service_date_to=$plan_of_care_detail1['service_date_to'];
								$event_id=$plan_of_care_detail1['event_id'];
								
								$sql1=mysql_query("SELECT * FROM sp_events  where event_id='$event_id'");
								$sql11 = mysql_fetch_array($sql1) or die(mysql_error());
								$event_code=$sql11['event_code'];
								$patient_id=$sql11['patient_id'];
								
								$patient_nm=mysql_query("SELECT * FROM sp_patients where patient_id='$patient_id'") or die(mysql_error());
								$patient_nm = mysql_fetch_array($patient_nm) or die(mysql_error());
								$Patient_name=$patient_nm['name'];
								$Patient_first_name=$patient_nm['first_name'];
								$Patient_middle_name=$patient_nm['middle_name'];
								$patient_name=$Patient_first_name.' '.$Patient_name;
								if ($service_date_to < $new_date)
								{}
								else
								{
									if($new_date6 >= $service_date && $service_date_to >= $new_date6)
									{	
									$day7=$day7.'<br>'.$start_time1.'-'.$end_time1.'<br><strong>'.$event_code.'</strong><br><strong>'.$patient_name.'</strong><br>';
									}
									else
									{
										
									}
									
									
								}
								$start_time1='';
								$end_time1='';
							}
							}
						}
								
						
						
						if($day1=='')
						{
							$day1='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
						}
						if($day2=='')
						{
							$day2='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
						}
						if($day3=='')
						{
							$day3='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
						}
						if($day4=='')
						{
							$day4='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
						}
						if($day5=='')
						{
							$day5='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
						}
						if($day6=='')
						{
							$day6='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
							
						}
						if($day7=='')
						{
							$day7='<span class="glyphicon glyphicon-ok" style="color:green"></span>';
							
						}
						}
					
				
						
				}
			}
		}
		$professional_holiday=mysql_query("SELECT * FROM sp_professional_weekoff  where service_professional_id='$service_professional_id' and status='1'");
			if(mysql_num_rows($professional_holiday) < 1 )
			{
				
			}
			else
			{
				while ($row1234 = mysql_fetch_array($professional_holiday))
				{
					$date_form=$row1234['date_form'];
					//echo $date_form;
					$date_to=$row1234['date_to'];
					if($new_date >= $date_form && $date_to >= $new_date)
					{	
						$day1='<span style="color:red">Week-OFF</span>';
					}
					if($new_date1 >= $date_form && $date_to >= $new_date1)
					{	
						$day2='<span style="color:red">Week-OFF</span>';
					}
					if($new_date2 >= $date_form && $date_to >= $new_date2)
					{	
						$day3='<span style="color:red">Week-OFF</span>';
					}
					if($new_date3 >= $date_form && $date_to >= $new_date3)
					{	
						$day4='<span style="color:red">Week-OFF</span>';
					}
					if($new_date4 >= $date_form && $date_to >= $new_date4)
					{	
						$day5='<span style="color:red">Week-OFF</span>';
					}
					if($new_date5 >= $date_form && $date_to >= $new_date5)
					{	
						$day6='<span style="color:red">Week-OFF</span>';
					}
					if($new_date6 >= $date_form && $date_to >= $new_date6)
					{	
						$day7='<span style="color:red">Week-OFF</span>';
					}
					
				}
			}	
 echo '<tr>
		<td>'.$title.' '.$first_name.' '.$middle_name.' '.$name.'<br><strong>'.$mobile_no.'</strong></td>
		<td>'.$google_location.'</td>
		<td>'.$Job_type.'</td>
		<td>'.$day1.'</td>
		<td>'.$day2.'</td>
		<td>'.$day3.'</td>
		<td>'.$day4.'</td>
		<td>'.$day5.'</td>
		<td>'.$day6.'</td>
		<td>'.$day7.'</td>';
		echo '</tr>'; 
				$day1='';
				$day2='';
				$day3='';
				$day4='';
				$day5='';
				$day6='';
				$day7='';	
				
				}
		
		
		?> 
   
  </tbody>
		
</table>
				</div>
				
		</div>