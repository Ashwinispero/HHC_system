<?php 
require_once('inc_classes.php'); 
require_once 'classes/eventClass.php';
$eventClass=new eventClass();
require_once 'classes/patientsClass.php';
$patientsClass=new patientsClass(); 
require_once 'classes/commonClass.php';
$commonClass=new commonClass();
if(!$_SESSION['employee_id'])
    echo 'notLoggedIn';
else
{
    include "pagination-include.php";    
    $recSummary['pageIndex']=$pageId;
    $recSummary['pageSize']=PAGE_PER_NO;
    $recSummary['employee_id']=$_SESSION['employee_id'];
    $recSummary['event_id']=$_REQUEST['event_id'];
    //var_dump($recArgs);
    //print_r($recSummary);
    // First Check is it job closure available for this event
     $FeedBack= $eventClass->GetJobClosure($recSummary);
 
     $selectEventCost = "select finalcost from sp_events where event_id ='".$_REQUEST['event_id']."' ";
     $valCost = $db->fetch_array($db->query($selectEventCost));
     // Getting plan of care date
      $GetPlanofCareDatesSql="SELECT service_date,service_date_to FROM sp_event_plan_of_care WHERE event_id='".$recSummary['event_id']."'";
      $GetPlanofCareDates=$db->fetch_all_array($GetPlanofCareDatesSql);
      if(!empty($GetPlanofCareDates))
      {
          $DateArr=array();
          foreach($GetPlanofCareDates as $valDates) 
          {
            /*------- date difference ----*/
              
            $service_start_date=$valDates['service_date'];
            $service_end_date=$valDates['service_date_to'];
            $diff = (strtotime($service_end_date)- strtotime($service_start_date))/24/3600; 
            $dateDiff = $diff+1;
            if($dateDiff)
            {
                for($i=0;$i<$dateDiff;$i++)
                {
                   $DateArr[]=date('Y-m-d',strtotime($service_start_date . "+$i days"));
                }
            }
          }
          $AllDates=array_values(array_unique($DateArr));
          $resultDates=bubbleSort($AllDates);  
      }
    if(!$FeedBack)
        echo '<center><br><br><h1 class="messageText">You have not yet done job closure please submit job closure first </h1></center>';
    if($FeedBack) 
    {
    echo '<h4 class="text-center title-services">FEEDBACK</h4>';
    ?> 
        <form name="formfeedback" id="formfeedback" method="post" action="event_ajax_process.php?action=SubmitFeedbackFrm">
            <input type="hidden" name="feedbackEventId" id="feedbackEventId" value="<?php if(!empty($_REQUEST['event_id'])) { echo $_REQUEST['event_id']; } else if(!empty($FeedBack['event_id'])) { echo$FeedBack['event_id'] ; }?>" />
            <input type="hidden" name="feedbackCallerId" id="feedbackCallerId" value="<?php echo $_REQUEST['caller_id'];?>" />
            <input type="hidden" name="prevFeedbackEveId" id="prevFeedbackEveId" value="<?php echo $_REQUEST['eventIDForClosure'];?>" />
                <div class="form-group col-lg-4">
                    <label class="pull-left" style="margin:5px 10px 20px 0px !important;">Select Date:</label>
                    <div class="">
                        <select class="validate[required] chosen-select form-control" name="feedback_service_date" id="feedback_service_date" onchange="return GetFeedbackDtls(this.value);"> 
                            <option value="">Date</option>
                            <?php   if(!empty($resultDates))
                                    {
                                        for($s=0;$s<count($resultDates);$s++)
                                        {
                                            $class = '';
                                            if(!empty($_REQUEST['feedback_service_date']) && $_REQUEST['feedback_service_date'] !='0000-00-00')
                                            {
                                                if($resultDates[$s]==$_REQUEST['feedback_service_date'])
                                                {
                                                     $class = 'selected="selected"'; 
                                                }
                                            }
                                            echo '<option '.$class.' value="'.$resultDates[$s].'">'.date('d-m-Y',strtotime($resultDates[$s])).'</option>';  
                                        }
                                    }
                            ?>
                        </select>
                    </div>
                </div>
           <div class="clearfix"></div>
           <table class="table table-bordered-hcaNew" cellspacing="0" width="100%" id="FeedbackContent" style="display:none;">
                <tbody>
                    
                    <?php 
                     $argpass1['allques'] = 'No';
                        $FeedBackQuestions= $eventClass->GetFeedbackQuestions($argpass1);
                        if($FeedBackQuestions)
                        {
                        $srNo = '1'; $checkBox = '';
                        foreach($FeedBackQuestions as $key=>$valQuestions)
                        {
                            $feedback_id = $valQuestions['feedback_id'];
                            $select_CallerAnswer = "SELECT option_id,answer FROM sp_feedback_answers WHERE feedback_id = '".$feedback_id."' AND event_id = '".$_REQUEST['event_id']."' AND service_date='".$_REQUEST['feedback_service_date']."'  ";
                            if($valQuestions['option_type'] != '3')
                            {
                                $selectExistanswers = $db->fetch_array($db->query($select_CallerAnswer));
                            }
                            else
                            {
                                $selectExistanswers = $db->fetch_all_array($select_CallerAnswer);
                                foreach($selectExistanswers as $key=>$valExisCheckbox)
                                {
                                    $checkBox[] = $valExisCheckbox['option_id'];
                                }
                            }
                            if($valQuestions['option_type'] == '1')
                            {
                                echo '<tr>
                                        <td style="width:50px; text-align:center;">'.$srNo.'</td>
                                        <td class="feedback-question">
                                        <div class="feedback-question">'.$valQuestions['question'].'</div>
                                          <div class="margintop10">
                                            <div class="text-holder col-lg-6">
                                              <textarea name="answer_'.$feedback_id.'" id="answer_'.$feedback_id.'" maxlegth="200"> '.$selectExistanswers['answer'].'</textarea>
                                            </div>
                                          </div></td>
                                      </tr>';
                            }
                            else if($valQuestions['option_type'] == '2')
                            {
                                echo '<tr style="background:#f8f8f8;">
                                        <td style="text-align:center;">'.$srNo.'</td>
                                        <td><div class="feedback-question">'.$valQuestions['question'].' </div>
                                          <div class="margintop10">
                                            <div>';
                                $recOpt['feedback_id'] = $valQuestions['feedback_id'];
                                $FeedBackOptions= $eventClass->GetFeedbackOptions($recOpt);
                                
                                foreach($FeedBackOptions as $key=>$valuesOptions)
                                {
                                    if($selectExistanswers['option_id'] == $valuesOptions['feedback_option_id'])
                                        $checked = 'checked';
                                    else
                                        $checked = '';
                                    echo '<input type="radio" '.$checked.' name="option_value_'.$feedback_id.'" id="option_value_'.$feedback_id.'" value="'.$valuesOptions['feedback_option_id'].'">&nbsp;'.$valuesOptions['option_value'].' &nbsp;&nbsp;';
                                }
                                              
                                echo '   </div>   </div>
                                        </td>
                                    </tr>';
                            }
                            else if($valQuestions['option_type'] == '3')
                            {
                                echo '<tr>
                                        <td style="text-align:center;">'.$srNo.'</td>
                                        <td><div class="feedback-question">'.$valQuestions['question'].'</div>
                                          <div class="margintop10">
                                            <div>';
                                $recOpt['feedback_id'] = $valQuestions['feedback_id'];
                                $FeedBackOptions= $eventClass->GetFeedbackOptions($recOpt);
                                
                                foreach($FeedBackOptions as $key=>$valuesOptions)
                                {
                                    $class = '';                                    
                                    for($OParr=0;$OParr<count($checkBox);$OParr++)
                                    {
                                        if($checkBox[$OParr] == $valuesOptions['feedback_option_id'])
                                            $class = "checked";
                                    }
                                    echo '<input type="Checkbox" '.$class.' name="option_value_check_'.$feedback_id.'[]" id="option_value_check_'.$feedback_id.'" value="'.$valuesOptions['feedback_option_id'].'">&nbsp;'.$valuesOptions['option_value'].' &nbsp;&nbsp;';
                                }
                                echo '</div>
                                          </div></td>
                                        
                                      </tr>';
                            }
                            else
                            {
                                echo '<tr style="background:#f8f8f8;">
                                        <td style="text-align:center;">'.$srNo.'</td>
                                        <td class="feedback-question" style="color:#fe8502">'.$valQuestions['question'].'  				
                                            <div class="exemple margintop10">
                                            <div class="basic" data-average="12" data-id="1" id="'.$feedback_id.'"></div>
                                            </div>
                                            <input type="hidden" name="rating_val_'.$feedback_id.'" id="rating_val_'.$feedback_id.'"  />
                                        </td>  
                                  </tr>';
                            }
                            $srNo++;
                        }
                        $selectQuestion = "select feedback_id,question,option_type from sp_feedback_form where status = '1' and feedback_id ='1' ";                        
                        if(mysql_num_rows($db->query($selectQuestion)))
                        {
                            $valFirstQues = $db->fetch_array($db->query($selectQuestion));
                        }
                        
                        $select_CallerAnswer1 = "SELECT option_id,answer FROM sp_feedback_answers WHERE feedback_id = '".$valFirstQues['feedback_id']."' AND event_id = '".$_REQUEST['event_id']."' AND service_date='".$_REQUEST['feedback_service_date']."'  ";
                        $selectExistanswers1 = $db->fetch_array($db->query($select_CallerAnswer1));
                        echo '<tr style="background:#f8f8f8;">
                                    <td style="text-align:center;">'.$srNo.'</td>
                                    <td>
                                    <div class="feedback-question">'.$valFirstQues['question'].'&nbsp; '.$valCost['finalcost'].' INR</div>
                                    <div class="margintop10">';
                                $recOptFirst['feedback_id'] = $valFirstQues['feedback_id'];
                                $FeedBackFirst= $eventClass->GetFeedbackOptions($recOptFirst);
                                
                                foreach($FeedBackFirst as $key=>$valuesOptionsFirst)
                                {
                                    if($selectExistanswers1['option_id'] == $valuesOptionsFirst['feedback_option_id'])
                                        $checked = 'checked';
                                    else
                                        $checked = '';
                                    echo '<input type="radio" '.$checked.' name="option_value_'.$valFirstQues['feedback_id'].'" id="option_value_'.$valFirstQues['feedback_id'].'" value="'.$valuesOptionsFirst['feedback_option_id'].'" onclick="changeamountbox(this.value);">&nbsp;'.$valuesOptionsFirst['option_value'].' &nbsp;&nbsp;';
                                }
//                                       
//                                           <input type="radio" name="lastoption" id="latoption1" value="yes" onclick="changeamountbox(this.value);">Yes
//                                           <input type="radio" name="lastoption" id="latoption1" value="no" onclick="changeamountbox(this.value);">No
                                $ansFirst = '';
                                if($selectExistanswers1['option_id'] == '2')
                                {
                                    $ansFirst = $selectExistanswers1['answer'];
                                }
                                 echo '</div>
                                        <div class="margintop20" id="noamount" style="display:none">
                                            <div class="text-holder col-lg-6">
                                              <textarea name="answer_1" id="answer_1" maxlegth="200">'.$ansFirst.' </textarea>
                                            </div>
                                        </div>
                                    </td>
                                  </tr>';
                        
                        echo '<tr><td colspan="2" align="left">
						<div class="form-group">
                            <div class="text-left">
                              <input type="button" class="btn btn-primary" id="ProfSubmit" value="SUBMIT" onclick="return SubmitFeedback();">
                            </div>
                        </div></td></tr>';
                        }
                        else
                            echo '<center><br><br><h1 class="messageText">Record not found.</h1></center>';
                    ?>
                </tbody>
            </table>  
        </form>
        <div class="clearfix"></div>
    <?php
    } 
}
?>