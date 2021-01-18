
<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
  
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    //print_r($EditedResponseArr);
    if($EditedResponseArr['event_id'])
    {
        $SearchByPatients = $EditedResponseArr['patient_id'];
        $temp_event_id = $EditedResponseArr['event_id'];
        $exi_purpose_id = $EditedResponseArr['purpose_id'];
        
        $arrpass['event_id'] = $EditedResponseArr['event_id'];
        $EditedRequirements = $eventClass->GetEventRequirement($arrpass);
        //$countarray = 0;
        //print_r($EditedRequirements);
        foreach($EditedRequirements as $key=>$valRequirements)
        {
            $countarray[] = $valRequirements['service_id'];
        }
        //echo count($countarray);
        //print_r($countarray);
    }
    ?>
	
<script src="js/jRating.jquery.js" type="text/javascript"></script>
	 <script src="dropdown/chosen.jquery.js" type="text/javascript"></script>
    <script src="dropdown/docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
 <script>
 function Hospital_List()
	{
		var hospital_id=document.getElementById('hospital_name').value;
		//alert(hospital_id);
		var xmlhttp;
		if(window.XMLHttpRequest)
		{
		xmlhttp=new XMLHttpRequest();
		}
		else
		{
			xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange = function()
		{
			
			if(xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				
				//alert(xmlhttp.responseText);
				
				document.getElementById("hospital_dropdown").innerHTML=xmlhttp.responseText;
				document.getElementById('hospital_dropdown').style.display = 'block';
			}
		}
		
		xmlhttp.open("POST","consultant_hospital_ajax.php?hospital_id="+hospital_id,true);
		xmlhttp.send();
		
	}
	function error_remove_hospital_name()
	{
		document.getElementById('error_msg_hospital_name').innerHTML="";
	}
	function Save_Hospital_Name()
	{
		var Hospital_Name=document.getElementById('Hospital_Name').value;
		var employee_id=document.getElementById('employee_id').value;
		if(Hospital_Name=='')
		{
			document.getElementById('error_msg_hospital_name').innerHTML="Please Enter hospital Name";
		}
		else
		{
		var xmlhttp;
		if(window.XMLHttpRequest)
		{
		xmlhttp=new XMLHttpRequest();
		}
		else
		{
			xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange = function()
		{
			
			if(xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				//document.getElementById("hospital_dropdown").innerHTML=xmlhttp.responseText;
				if(xmlhttp.responseText=='exist')
				{
					
					alert('Hospital Name already exist Please check');
				}
				else
				{
					
				document.getElementById("hospital_name").innerHTML=xmlhttp.responseText;
				document.getElementById('hospital_dropdown').style.display = 'none';
				alert('Hospital Added successfully');
				
				}
			}
		}
		xmlhttp.open("POST","Save_hospital_name.php?Hospital_Name="+Hospital_Name+"&employee_id="+employee_id,true);
		xmlhttp.send();
		}
	}
	function Cosultant_add()
	{
		
		var Consultant=document.getElementById('Consultant').value;
		if(Consultant=='Other')
		{
		document.getElementById('consultant_other_textbox').style.display = 'block';
		}
		else
		{
		document.getElementById('consultant_other_textbox').style.display = 'none';	
		}
	}
	function Save_consultant_Name()
	{
		var Consultant_Name = document.getElementById('Consultant_Name').value;
		var employee_id = document.getElementById('employee_id').value;
		var hospital_id = document.getElementById('hospital_name').value;
		var Consultant_Mobile_no = document.getElementById('Consultant_Mobile_no').value;
		if(hospital_id=='')
		{
			document.getElementById('error_msg_hospital_name').innerHTML="Please Enter hospital Name";
		}
		else if(Consultant_Name=='')
		{
			document.getElementById('error_msg_consultant_name').innerHTML="Please Enter Consultant Name";
		}
		else if(Consultant_Mobile_no=='')
		{
			document.getElementById('error_msg_consultant_mobileno').innerHTML="Please Enter consultant mobile no";
		}
		else 
		{
		var xmlhttp;
		if(window.XMLHttpRequest)
		{
		xmlhttp=new XMLHttpRequest();
		}
		else
		{
			xmlhttp= new ActiveXObject("microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange = function()
		{
			
			if(xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				//document.getElementById("hospital_dropdown").innerHTML=xmlhttp.responseText;
				if(xmlhttp.responseText=='exist')
				{
					
					alert('consultant Name already exist Please check');
				}
				else
				{
					
				//document.getElementById("hospital_dropdown").innerHTML=xmlhttp.responseText;
				//$( "#consultant_other_textbox" ).load(window.location.href + " #consultant_other_textbox" );
				document.getElementById('consultant_other_textbox').style.display = 'none';
				alert('consultant Added successfully');
				
				}
			}
		}
		xmlhttp.open("POST","Save_consultant_name.php?Consultant_Name="+Consultant_Name+"&employee_id="+employee_id+"&hospital_id="+hospital_id+"&Consultant_Mobile_no="+Consultant_Mobile_no,true);
		xmlhttp.send();
		}
	}
	function error_remove_consultant_name()
	{
		document.getElementById('error_msg_consultant_name').innerHTML="";
	}
	function error_remove_mobile_no()
	{
		document.getElementById('error_msg_consultant_mobileno').innerHTML="";
	}
 </script> 
	
        <form class="form-horizontal" name="RequirementForm" id="RequirementForm" method="post" action="event_ajax_process.php?action=SubmitRequirement">
            <h4 class="section-head" id="title01"><span><img src="images/requirnment-icon.png" width="29" height="29"></span>CONSULTANT DETAILS</h4>
		
			<div class="value dropdown">
                            <label>
							<select class="form-control ServiceClass" name="hospital_name" id="hospital_name" onchange="Hospital_List();" style="width:340px;">
                            <?php
								$Query=mysql_query("select * from sp_hospitals ORDER BY hospital_id ASC");
								while($row=mysql_fetch_array($Query))
								{
							?>
							<option value="<?php echo $row['hospital_id'] ;?>" ><?php echo $row['hospital_name'];?> </option>
							<?php
								}
							?>
							<option value="Other">Other</option>
							</select>
							</label>
                        </div>
						<div class="value dropdown" id="hospital_dropdown">
                           
                        </div>
						<br>
						<div id="consultant_other_textbox" style="display:none">
						<div class="value dropdown" >
							<input class="form-control ServiceClass" id="Consultant_Name" Placeholder="Enter Consultant Name" style="width:340px;"  onkeypress="error_remove_consultant_name();"></input>
							<div id="error_msg_consultant_name" style="color:red"></div>
							<br>
						
							<input class="form-control ServiceClass" id="Consultant_Mobile_no" Placeholder="Enter mobile no" style="width:340px;"  onkeypress="error_remove_mobile_no();"></input>
							<div id="error_msg_consultant_mobileno" style="color:red"></div>
						</div>
						<br>
						<div class="col-sm-12 text-right">
							<input class="btn btn-primary" type="button" value="Save" onclick="Save_consultant_Name();"></input>
						</div>
						</div>
						
			<input id="employee_id" style="display:none" value="<?php echo $_SESSION['employee_id']; ?>"></input>
			
			<h4 class="section-head" id="title01"><span><img src="images/requirnment-icon.png" width="29" height="29"></span>REQUIREMENTS</h4>
            <!-- ---------------- hidden field ---------------- -->
                    <input type="hidden" name="purpose_id_temp" id="purpose_id_temp" value="<?php if($exi_purpose_id) echo $exi_purpose_id;?>" />
                    <input type="hidden" name="event_id_temp" id="event_id_temp" value="<?php if($temp_event_id) echo $temp_event_id;?>" />
                    <input type="hidden" name="patient_id_temp" id="patient_id_temp" value="<?php if($SearchByPatients) echo $SearchByPatients; else echo '';?>" />
                  <!-- ---------------- hidden field end ---------------- -->  
            <div class="form-group">
              <div class="col-sm-12 ">    
                  <div class="form-group">
                      <?php
                        $selectServces = "select service_id,service_title,is_hd_access from sp_services where status = '1' ";
                          $dataServices = $db->fetch_all_array($selectServces);
                          foreach($dataServices as $key=>$valServices)
                            {   
                              echo '<input type="hidden" name="isAccessHD_'.$valServices['service_id'].'" id="isAccessHD_'.$valServices['service_id'].'" value="'.$valServices['is_hd_access'].'" >';
                            }
                          ?>
                    <div class="col-sm-12 select_requirnment" id="requirment_physician">
                      <!--<label class="select-box-lbl">-->
                          <select class="form-control ServiceClass" id="requireservicesAll" name="requireservices[]" multiple="multiple" >
                          <!--<option value="">Select <?php //echo $recordServices['service_title'];?></option>-->
                            <?php
                                $selectServces = "select service_id,service_title,is_hd_access from sp_services where status = '1' ";
                            $dataServices = $db->fetch_all_array($selectServces);
                            foreach($dataServices as $key=>$valServices)
                            {   
                                    $class = '';
                                    for($i=0;$i<count($countarray);$i++)
                                    {
                                        if($countarray[$i] == $valServices['service_id'])
                                            $class = 'selected="selected"';
                                    }
                                    //echo '<input type="hidden" name="isAccessHD_'.$valServices['service_id'].'" id="isAccessHD_'.$valServices['service_id'].'" value="'.$valServices['is_hd_access'].'" >';
                                    echo '<option '.$class.' value="'.$valServices['service_id'].'">'.$valServices['service_title'].'</option>';
                            }
                        ?>
                        </select>
                      <!--</label>-->
                    </div>
                </div>
<!--              <div class="dropdown">    
                  
                   
                  
                  
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                  Services
                  <span class="caret"></span>
                </button>
                <div class="clearfix"></div>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="position:static !important;">
                    <?php
                          $selectServces = "select service_id,service_title,is_hd_access from sp_services where status = '1' ";
                          $dataServices = $db->fetch_all_array($selectServces);
                          foreach($dataServices as $key=>$valServices)
                          {      
                              $class = '';
                              for($i=0;$i<count($countarray);$i++)
                              {
                                  if($countarray[$i] == $valServices['service_id'])
                                      $class = "checked";
                              }
                              echo '<input type="hidden" name="isAccessHD_'.$valServices['service_id'].'" id="isAccessHD_'.$valServices['service_id'].'" value="'.$valServices['is_hd_access'].'" >';
                              echo '<li role="presentation" >
							  		
                                      <a role="menuitem" tabindex="-1" href="javascript:void(0);" >
                                          <div class="checkbox">
                                            <label>
                                              <input type="checkbox" '.$class.' class="ServiceClass" onclick="return Change_Subservice('.$valServices['service_id'].');" name="requireservices[]" id="services_'.$valServices['service_id'].'" value="'.$valServices['service_id'].'" >'.$valServices['service_title'].'
                                             <label>
                                             </div>
                                     </a>
									   
                                  </li>';
                              //<input type="checkbox" '.$class.' class="ServiceClass" onclick="return Change_Subservice('.$valServices['service_id'].');" name="services_'.$valServices['service_id'].'" id="services_'.$valServices['service_id'].'" value="'.$valServices['service_id'].'" >'.$valServices['service_title'].'
                          }
                      ?>

                        <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0);"><input type="checkbox"> Another action</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0);"><input type="checkbox"> Something else here</a></li>
                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0);"><input type="checkbox"> Separated link</a></li>
                </ul>
               
              </div>-->
              </div>
            </div>
            <div id="newData">
                <?php
                if(count($countarray))
                {
                    //echo count($countarray);
                    $selectExistRequirement = "select distinct service_id from sp_event_requirements where event_id = '".$EditedResponseArr['event_id']."' ";
                    $ptrExistReq = $db->fetch_all_array($selectExistRequirement);
                    foreach($ptrExistReq as $key=>$valRequirements)
                    {
                        $subServices = array();
                        $selectExistSubser = "select sub_service_id from sp_event_requirements where service_id = '".$valRequirements['service_id']."' and event_id = '".$EditedResponseArr['event_id']."' ";
                        $ptrExistReq_sub = $db->fetch_all_array($selectExistSubser);
                        foreach($ptrExistReq_sub as $key=>$ValSubServices)
                        {
                            $subServices[] = $ValSubServices['sub_service_id'];
                        }                        
                        ?>
                            <div class="form-group" id="ServiceDiv_<?php echo $valRequirements['service_id'];?>">
                                <div class="col-sm-12">
								
                                    <!--<label class="select-box-lbl">-->
                                        <select class="form-control" id="sub_service_id_multiselect_<?php echo $valRequirements['service_id'];?>" multiple="multiple" name="sub_service_id_multiselect_<?php echo $valRequirements['service_id'];?>[]">
                                            <!--<option value="">Select <?php echo $valRequirements['service_title'];?></option>-->
                                            <?php
                                                $selectServces = "select sub_service_id,recommomded_service from sp_sub_services where status = '1' and service_id = '".$valRequirements['service_id']."' ORDER BY recommomded_service ASC ";
                                                $dataServices = $db->fetch_all_array($selectServces);
                                                foreach($dataServices as $key=>$valServices)
                                                {
                                                    $class = '';
                                                    //echo count($countProfarray);
                                                    for($i=0;$i<count($subServices);$i++)
                                                    {
                                                        if($subServices[$i] == $valServices['sub_service_id'])
                                                            $class = 'selected="selected"';
                                                    }
                                                    //if($valRequirements['sub_service_id'] == $valServices['sub_service_id'])
                                                        //echo '<option value="'.$valServices['sub_service_id'].'" selected="selected">'.$valServices['recommomded_service'].'</option>';
                                                    //else
                                                        echo '<option '.$class.' value="'.$valServices['sub_service_id'].'">'.$valServices['recommomded_service'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    <!--</label>-->
									
                                </div>
								
                            </div>
                        <?php
                    }
					$query=mysql_query("SELECT * FROM sp_events where event_id='".$EditedResponseArr['event_id']."'") or die(mysql_error());
					$row = mysql_fetch_array($query) or die(mysql_error());
					$notes=$row['note'];
					
					?>
					<div class="form-group">
              <div class="col-sm-12">
                <textarea class="form-control"  id="notes" name="notes"   placeholder="Notes"><?php echo $notes; ?></textarea>
              </div>
            </div>
					<?php
                }
                ?>
            </div>
            
            <?php 
         /*   $show = 'yes';
            if($EditedResponseArr['event_status'] == '3' || $EditedResponseArr['event_status'] == '4')
            {
                $show = 'No';
            }
            if($show == 'yes')
            { */
?>				<div class="form-group">
              <div class="col-sm-12">
                <textarea class="form-control"  id="notes" name="notes" placeholder="Notes"></textarea>
              </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="button" class="btn btn-primary" id="dispatch" name="dispatch" value="DISPATCH" onclick="return dispatchRequirement('1');">
                    <input type="button" class="btn btn-disabled" id="invisibleDispatch" value="DISPATCH" style="display: none;">
                    <!--<button type="submit" class="btn btn-primary" data-toggle="button"> DISPATCH</button>-->
                    &nbsp;
                    <input type="button" class="btn btn-primary" id="sharewithHCM" name="sharewithHCM" value="SHARE WITH HCM" onclick="return dispatchRequirement('2');">
                    <!--<button type="submit" class="btn btn-primary" data-toggle="button"> SHARE WITH HCM</button>-->
                </div>
            </div>
            <?php //} ?>
        </form>
<?php
}?>