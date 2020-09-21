<?php
require_once 'inc_classes.php';
require_once '../classes/professionalsClass.php';
require_once '../classes/commonClass.php';
$professionalsClass=new professionalsClass();
$commonClass=new commonClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";

     
    if($Selcfromdate)
        $formDate = date('Y-m-d', strtotime($Selcfromdate));
    else
        $formDate = date('Y-m-d', strtotime($_REQUEST['fromdate']));
     
    if($SelctoDate)
        $toDate = date('Y-m-d', strtotime($SelctoDate));
    else      
        $toDate = date('Y-m-d', strtotime($_REQUEST['toDate']));
    
    $profID = $_REQUEST['profID'];
    $date1 = strtotime($toDate);
    $date2 = strtotime($formDate);
    $diff = ($date1-$date2);
    $totaldays = floor($diff/(60*60*24));
    //echo $totaldays;
    //exit;
    echo '<input type="hidden" name="fromDateselcted" id="fromDateselcted" value="'.$formDate.'" >
            <input type="hidden" name="toDateselc" id="toDateselc" value="'.$toDate.'" >'
            . '<input type="hidden" name="editedRecord" id="editedRecord"  value="yes" >'
            . '<input type="hidden" name="editedPROfRecord" id="editedPROfRecord"  value="'.$profID.'" >';
    for($i=0;$i<=$totaldays;$i++)
    {
        if($profID)
            $preID = " and professiona_id = '".$profID."'";
        else
            $preID = '';
        $newdate = date('d-m-Y', strtotime($formDate));
        
        $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where status = '1'  order by name asc ";
        $ptrval = $db->fetch_all_array($selectProfessional);
        
        $select_existRecord = "select distinct from_time, to_time, schedule_id from sp_professional_scheduled where scheduled_date = '".date('Y-m-d',strtotime($newdate))."' ".$preID." ";
        if(mysql_num_rows($db->query($select_existRecord)))
        {
            $ptrSchrec = $db->fetch_all_array($select_existRecord);
            $srNo = 0;
            foreach($ptrSchrec as $key=>$valScheduledData)
            {
                $extArray = array();
                $selectexistProf = "select professiona_id from sp_professional_scheduled where scheduled_date = '".date('Y-m-d',strtotime($newdate))."' and from_time = '".$valScheduledData['from_time']."' and to_time = '".$valScheduledData['to_time']."' ";
                $ptrvalExit = $db->fetch_all_array($selectexistProf);
                foreach($ptrvalExit as $key=>$valExisArray)
                {
                    $professiona_id = $valExisArray['professiona_id'];
                    $extArray[] = $professiona_id;
                }
                //print_r($extArray);
                
                if($srNo == '0')
                    $printdate = $newdate;
                else
                    $printdate = '';
                echo '
                <div class="row">            
                <input type="hidden" name="schedule_date_'.$i.'" id="schedule_date_'.$i.'" value="'.$newdate.'" >
                    <div style="width:20%; display:inline-block; float:left; vertical-align:top; padding-right:1%;padding:4px;">'.$printdate.' </div>
                    <div class="datepairExample_0">
                    <div class="pull-left" style="width:20%;display:inline-block;padding-right:2%;padding:4px;">
                        <label style="display:block;">
                            <input value="'.$valScheduledData['from_time'].'" placeholder="From Time" name="starttime_'.$srNo.'_'.$i.'" id="starttime_'.$srNo.'_'.$i.'" type="text" class="form-control time start validate_time" />
                        </label>
                    </div>
                    <div class="pull-left" style="width:20%;display:inline-block;padding-left:2%;padding:4px;">       
                        <label style="display:block;">
                            <input  placeholder="To Time"  value="'.$valScheduledData['to_time'].'" name="endtime_'.$srNo.'_'.$i.'" id="endtime_'.$srNo.'_'.$i.'"  type="text" class="form-control time end validate_time" />
                        </label>                
                    </div>   
                    </div>';
                if($profID == '')
                {
                    echo '<div style="width:25%; display:inline-block; vertical-align:top; padding:4px;" class="text-left value select-pro">';
                
                    echo '<label><select name="professional_id_'.$srNo.'_'.$i.'[]" id="professional_id" class="validate[required] ServiceClass" multiple="multiple">';
                    $selectProfessional = "select service_professional_id,professional_code,name,first_name,middle_name,email_id from sp_service_professionals where status = '1' order by name asc ";
                    $ptrval = $db->fetch_all_array($selectProfessional);
                    foreach($ptrval as $key=>$valProfessional)
                    {
                        $class = '';
                        for($m=0;$m<=count($extArray);$m++)
                        {
                            if($extArray[$m] == $valProfessional['service_professional_id'])
                                $class = 'selected="selected"';
                        }
                        echo '<option '.$class.' value="'.$valProfessional['service_professional_id'].'">'.$valProfessional['name'].' '.$valProfessional['first_name'].' '.$valProfessional['middle_name'].'</option>';
                    }
                echo '</select></label>';
                echo '</div> ';
                }
                else
                {
                    echo '<div style="width:25%; display:inline-block; vertical-align:top; padding:4px;" class="text-left value select-pro">
                        <a href="javascript:void(0);" title="Remove" onclick="javascript:deleteScheduled('.$valScheduledData['schedule_id'].');"><img src="images/icon-inactive.png"></a>
                          </div>';
                    echo '<input type="hidden" name="existProfId" id="existProfId" value="'.$profID.'" >';    
                }
                echo '</div>  
                    
                ';
                $srNo++;
            }
            echo '<div class="line"></div>';
            $record = 'Yes';
        }
       $formDate = date('Y-m-d', strtotime('+1 day' , strtotime($newdate)));
       echo '<input type="hidden" name="extras_'.$i.'" id="extras_'.$i.'" value="'.$srNo.'" >';
    }
    
    if($record == 'Yes')
    {
    ?>
    <div>
        <input class="btn btn-download" type="button" name="scheduledSubmit" id="scheduledSubmit" value="Edit Scheduled" onclick="return scheduleSubForm();"> 
    </div>
<?php
    }
    else
    {
        echo '<h1 class="messageText">No records found related to your search, please try again.</h1>';
    }

?>