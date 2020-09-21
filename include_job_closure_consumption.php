<div class="rounded-corner col-lg-12">
    <div class="color-text">Consumption Details:</div>
        <div class="row margintop10">
            <div class="col-lg-4">
        <label class="name color-text">Medicines:</label>
        <br/>
        <input type="hidden" name="tot_unit_medicine" id="tot_unit_medicine" value="<?php if(!empty($UnitMedicineArr)) { echo count($UnitMedicineArr); } ?>" />
    </div>
        <?php
                if(!empty($UnitMedicineArr)) { 
                    for($a=0;$a<count($UnitMedicineArr);$a++) {
        ?>
             <div class="clearfix"></div>
             <div class="col-lg-4">
                 <?php if($a==0) { ?>
                     <label class="name">Unit:</label>
                 <?php } else { ?>
                     <label class="name"></label>
                 <?php } ?>
                     <div class="select-holder">
<!--                     <label class="select-box-lbl chose">-->
                         <input type="hidden" name="unit_medicine_consumption_id<?php echo $a;?>" id="unit_medicine_consumption_id<?php echo $a;?>" value="<?php if(!empty($UnitMedicineRecordArr[$a])) { echo $UnitMedicineRecordArr[$a]; }  ?>" />
                         <select class="chosen-select  form-control" name="unit_medicine_id<?php echo $a;?>" id="unit_medicine_id<?php echo $a;?>">
                             <option value="">Medicines</option>
                              <?php   if(!empty($UnitMedicinesList))
                                      {
                                          for($i=0;$i<count($UnitMedicinesList);$i++)
                                          {
                                              $class = '';
                                              if($UnitMedicineArr[$a] == $UnitMedicinesList[$i]['medicine_id'])
                                                  $class = 'selected="selected"';

                                              echo '<option '.$class.' value="'.$UnitMedicinesList[$i]['medicine_id'].'">'.$UnitMedicinesList[$i]['name'].'</option>';
                                          }
                                      }
                                      ?>
                          </select>
<!--                     </label>-->
                  </div>
             </div>
             <div class="col-lg-4">
                 <label class="name"></label>
                 <input type="text" name="unit_medicine_quantity<?php echo $a;?>" id="unit_medicine_quantity<?php echo $a;?>" value="<?php if(!empty($UnitMedicineQtyArr[$a])) { echo $UnitMedicineQtyArr[$a]; } ?>" class="form-control" maxlength="20" />
             </div>
             <div class="col-lg-1">
                 <label class="name"></label>
                 <div class="clearfix"></div>
                 <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:delete_consumption_option('<?php  if(!empty($UnitMedicineRecordArr[$a])) { echo $UnitMedicineRecordArr[$a]; } ?>','<?php echo $recSummary['event_id']; ?>');"><img src="images/icon-inactive.png" /></a>
             </div>
         <?php if($a==0) { ?>
             <div class="col-lg-3">
                 <label class="name"></label>
                 <div class="clearfix"></div>
                 <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_unit_medicine('1');"><img src="images/add.png"></a> &nbsp;  
                 <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_unit_medicine('1');"><img src="images/remove1.png"></a>  
             </div>
         <?php } else { ?>
             <div class="col-lg-3"> 
                 <label class="name"></label>
                 <div class="clearfix"></div> 
             </div>
         <?php } ?>
                <?php } } else {  ?>
                    <div class="clearfix"></div>
                    <div class="col-lg-4">
                        <label class="name">Unit:</label>
                        <div class="select-holder">
<!--                            <label class="select-box-lbl chose">-->
                                <select class="chosen-select form-control" name="unit_medicine_id" id="unit_medicine_id"> 
                                    <option value="">Medicines</option>
                                    <?php   if(!empty($UnitMedicinesList))
                                            {
                                                for($i=0;$i<count($UnitMedicinesList);$i++)
                                                {
                                                    echo '<option value="'.$UnitMedicinesList[$i]['medicine_id'].'">'.$UnitMedicinesList[$i]['name'].'</option>';
                                                }
                                            }
                                    ?>
                                </select>
<!--                            </label>-->
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label class="name"></label>
                        <input type="text" name="unit_medicine_quantity" id="unit_medicine_quantity" class="form-control" value="" maxlength="20" />
                    </div>
                    <div class="col-lg-4">
                        <label class="name"></label>
                        <div class="clearfix"></div>
                        <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_unit_medicine('1');"><img src="images/add.png"></a> &nbsp;  
                        <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_unit_medicine('1');"><img src="images/remove1.png"></a>  
                    </div>
                <?php } ?>
             <input type="hidden" name="extras" id="extras" value='0' />
             <div id='div_1'>
             </div>
             <input type="hidden" name="tot_non_unit_medicine" id="tot_non_unit_medicine" value="<?php if(!empty($NonUnitMedicineArr)) { echo count($NonUnitMedicineArr); } ?>" />
         <?php
            if(!empty($NonUnitMedicineArr)) { 
             for($b=0;$b<count($NonUnitMedicineArr);$b++) {
        ?>
         <div class="clearfix"></div>
         <div class="col-lg-4">
             <?php if($b==0) { ?>
                 <label class="name"> Non Unit:</label>
             <?php } else { ?>
                 <label class="name"></label>
             <?php } ?>
             <div class="select-holder">
<!--                 <label class="select-box-lbl chose">-->
                     <input type="hidden" name="non_unit_medicine_consumption_id<?php echo $b;?>" id="non_unit_medicine_consumption_id<?php echo $b;?>" value="<?php if(!empty($NonUnitMedicineRecordArr[$b])) { echo $NonUnitMedicineRecordArr[$b]; }  ?>" />
                     <select class="chosen-select  form-control" name="non_unit_medicine_id<?php echo $b;?>" id="non_unit_medicine_id<?php echo $b;?>"> 
                         <option value="">Select Medicines</option>
                         <?php   if(!empty($NonUnitMedicinesList))
                                 {
                                     for($j=0;$j<count($NonUnitMedicinesList);$j++)
                                     {
                                         $class = '';
                                         if($NonUnitMedicineArr[$b] == $NonUnitMedicinesList[$j]['medicine_id'])
                                             $class = 'selected="selected"';
                                         echo '<option '.$class.' value="'.$NonUnitMedicinesList[$j]['medicine_id'].'" >'.$NonUnitMedicinesList[$j]['name'].'</option>';
                                     }
                                 }
                         ?>
                     </select>
<!--                 </label>-->
             </div>

         </div>
         <div class="col-lg-4">
             <label class="name"></label>
             <input type="text" name="non_unit_medicine_quantity<?php echo $b;?>" id="non_unit_medicine_quantity<?php echo $b;?>" class="form-control" value="<?php if(!empty($NonUnitMedicineQtyArr[$b])) { echo $NonUnitMedicineQtyArr[$b]; } ?>" maxlength="20" />
         </div>
         <div class="col-lg-1">
             <label class="name"></label>
             <div class="clearfix"></div> 
                 <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:delete_consumption_option('<?php  if(!empty($NonUnitMedicineRecordArr[$b])) { echo $NonUnitMedicineRecordArr[$b]; } ?>','<?php echo $recSummary['event_id']; ?>');"><img src="images/icon-inactive.png" /></a>
         </div>
         <?php if($b==0) { ?>
             <div class="col-lg-3">
                 <label class="name"></label>
                 <div class="clearfix"></div>
                 <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_non_unit_medicine('1');"><img src="images/add.png"></a> &nbsp;  
                 <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_non_unit_medicine('1');"><img src="images/remove1.png"></a> 
             </div>
         <?php } else { ?>
             <div class="col-lg-3">
                 <label class="name"></label>
                 <div class="clearfix"></div> 
             </div>
         <?php } ?>
            <?php } } else {  ?>
                <div class="clearfix"></div>
                    <div class="col-lg-4">
                        <label class="name"> Non Unit:</label>
                        <div class="select-holder">
<!--                            <label class="select-box-lbl chose">-->
                                <select class="chosen-select form-control" name="non_unit_medicine_id" id="non_unit_medicine_id"> 
                                    <option value="">Medicines</option>
                                    <?php   if(!empty($NonUnitMedicinesList))
                                            {
                                                for($j=0;$j<count($NonUnitMedicinesList);$j++)
                                                {
                                                    echo '<option value="'.$NonUnitMedicinesList[$j]['medicine_id'].'" >'.$NonUnitMedicinesList[$j]['name'].'</option>';
                                                }
                                            }
                                    ?>
                                </select>
<!--                            </label>-->
                        </div>
                       
                    </div>
                    <div class="col-lg-4">
                        <label class="name"></label>
                        <input type="text" name="non_unit_medicine_quantity" id="non_unit_medicine_quantity" class="form-control" value="" maxlength="20" />
                    </div>
                    <div class="col-lg-4">
                        <label class="name"></label>
                        <div class="clearfix"></div>
                        <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_non_unit_medicine('1');"><img src="images/add.png"></a> &nbsp;  
                        <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_non_unit_medicine('1');"><img src="images/remove1.png"></a> 
                    </div>
            <?php } ?>
             <input type="hidden" name="extras_2" id="extras_2" value='0' />
             <div id='non_div_1'>
             </div>
        </div>
        <div class="row margintop10">
    <div class="col-lg-4">
        <label class="name color-text">Consumables:</label>
        <br/>
        <input type="hidden" name="tot_unit_consumable_medicine" id="tot_unit_consumable_medicine" value="<?php if(!empty($UnitConsumbaleArr)) { echo count($UnitConsumbaleArr); } ?>" />
    </div>
    <?php
        if(!empty($UnitConsumbaleArr)) { 
        for($c=0;$c<count($UnitConsumbaleArr);$c++) {
   ?> 
        <div class="clearfix"></div>
        <div class="col-lg-4">
            <?php if($c==0) { ?>
                <label class="name">Unit:</label>
            <?php } else { ?>
                 <label class="name"></label>
            <?php } ?>
            <div class="select-holder">
<!--                <label class="select-box-lbl chose">-->
                    <input type="hidden" name="unit_consumable_consumption_id<?php echo $c;?>" id="unit_consumable_consumption_id<?php echo $c;?>" value="<?php if(!empty($UnitConsumbaleRecordArr[$c])) { echo $UnitConsumbaleRecordArr[$c]; }  ?>" />
                    <select class="chosen-select form-control" name="unit_consumable_id<?php echo $c;?>" id="unit_consumable_id<?php echo $c;?>"> 
                        <option value="">Consumables</option>
                        <?php   if(!empty($UnitConsumablesList))
                                {
                                    for($k=0;$k<count($UnitConsumablesList);$k++)
                                    {
                                        $class = '';
                                        if($UnitConsumbaleArr[$c] == $UnitConsumablesList[$k]['consumable_id'])
                                            $class = 'selected="selected"';

                                        echo '<option '.$class.' value="'.$UnitConsumablesList[$k]['consumable_id'].'">'.$UnitConsumablesList[$k]['name'].'</option>';
                                    }
                                }
                        ?>
                    </select>
<!--                </label>-->
            </div>
        </div>
        <div class="col-lg-4">
            <label class="name"></label>
            <input type="text" name="unit_consumable_quantity<?php echo $c;?>" id="unit_consumable_quantity<?php echo $c;?>" class="form-control" value="<?php if(!empty($UnitConsumbaleQtyArr[$c])) { echo $UnitConsumbaleQtyArr[$c]; } ?>" maxlength="20" />
        </div>
        <div class="col-lg-1">
            <label class="name"></label>
            <div class="clearfix"></div> 
                <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:delete_consumption_option('<?php  if(!empty($UnitConsumbaleRecordArr[$c])) { echo $UnitConsumbaleRecordArr[$c]; } ?>','<?php echo $recSummary['event_id']; ?>');"><img src="images/icon-inactive.png" /></a>
        </div>
        <?php if($c==0) { ?>
            <div class="col-lg-3">
                <label class="name"></label>
                <div class="clearfix"></div>
                <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_unit_consumable('1');"><img src="images/add.png"></a> &nbsp;  
                <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_unit_consumable('1');"><img src="images/remove1.png"></a>  
            </div>
        <?php } else { ?>
            <div class="col-lg-3">
                <label class="name"></label>
                <div class="clearfix"></div>
            </div>
        <?php } ?>
        <?php } } else { ?>
            <div class="clearfix"></div>
                <div class="col-lg-4">
                    <label class="name">Unit:</label>
                    <div class="select-holder">
<!--                        <label class="select-box-lbl chose">-->
                            <select class="chosen-select form-control" name="unit_consumable_id" id="unit_consumable_id"> 
                                <option value="">Consumables</option>
                                <?php   if(!empty($UnitConsumablesList))
                                        {
                                            for($i=0;$i<count($UnitConsumablesList);$i++)
                                            {
                                                echo '<option value="'.$UnitConsumablesList[$i]['consumable_id'].'">'.$UnitConsumablesList[$i]['name'].'</option>';
                                            }
                                        }
                                ?>
                            </select>
<!--                        </label>-->
                    </div>
                </div>
                <div class="col-lg-4">
                    <label class="name"></label>
                    <input type="text" name="unit_consumable_quantity" id="unit_consumable_quantity" class="form-control" value="" maxlength="20" />
                </div>
                <div class="col-lg-4">
                    <label class="name"></label>
                    <div class="clearfix"></div>
                    <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_unit_consumable('1');"><img src="images/add.png"></a> &nbsp;  
                    <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_unit_consumable('1');"><img src="images/remove1.png"></a>  
                </div>
        <?php  } ?>
        <input type="hidden" name="consumable_extras" id="consumable_extras" value='0' />
        <div id='consumable_unit_div_1'>
        </div>
        <input type="hidden" name="tot_non_unit_consumable_medicine" id="tot_non_unit_consumable_medicine" value="<?php if(!empty($NonUnitConsumbaleArr)) { echo count($NonUnitConsumbaleArr); } ?>" />
            <?php
                if(!empty($NonUnitConsumbaleArr)) { 
                for($d=0;$d<count($NonUnitConsumbaleArr);$d++) {
           ?>
            <div class="clearfix"></div>
            <div class="col-lg-4">
                <?php if($d==0) { ?>
                    <label class="name">Non Unit:</label>
                <?php } else { ?>
                    <label class="name"></label>
                <?php } ?>
                <div class="select-holder">
<!--                    <label class="select-box-lbl chose">-->
                        <input type="hidden" name="non_unit_consumable_consumption_id<?php echo $d;?>" id="non_unit_consumable_consumption_id<?php echo $d;?>" value="<?php if(!empty($NonUnitConsumbaleRecordArr[$d])) { echo $NonUnitConsumbaleRecordArr[$d]; }  ?>" />
                        <select class="chosen-select form-control" name="non_unit_consumable_id<?php echo $d;?>" id="non_unit_consumable_id<?php echo $d;?>"> 
                            <option value="">Consumables</option>
                            <?php   if(!empty($NonUnitConsumablesList))
                                    {
                                        for($l=0;$l<count($NonUnitConsumablesList);$l++)
                                        {
                                            $class = '';
                                            if($NonUnitConsumbaleArr[$d] == $NonUnitConsumablesList[$l]['consumable_id'])
                                                $class = 'selected="selected"';

                                            echo '<option '.$class.' value="'.$NonUnitConsumablesList[$l]['consumable_id'].'" >'.$NonUnitConsumablesList[$l]['name'].'</option>';
                                        }
                                    }
                            ?>
                        </select>
<!--                    </label>-->
                </div>

            </div>
            <div class="col-lg-4">
                <label class="name"></label>
                <input type="text" name="non_unit_consumable_quantity<?php echo $d;?>" id="non_unit_consumable_quantity<?php echo $d;?>" class="form-control" value="<?php if(!empty($NonUnitConsumbaleQtyArr[$d])) { echo $NonUnitConsumbaleQtyArr[$d]; } ?>" maxlength="20" />
            </div>
            <div class="col-lg-1">
                <label class="name"></label>
                <div class="clearfix"></div> 
                    <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:delete_consumption_option('<?php  if(!empty($NonUnitConsumbaleRecordArr[$d])) { echo $NonUnitConsumbaleRecordArr[$d]; } ?>','<?php echo $recSummary['event_id']; ?>');"><img src="images/icon-inactive.png" /></a>
            </div>
            <?php if($d==0) { ?>
                <div class="col-lg-3">
                    <label class="name"></label>
                    <div class="clearfix"></div>
                    <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_non_unit_consumable('1');"><img src="images/add.png"></a> &nbsp;  
                    <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_non_unit_consumable('1');"><img src="images/remove1.png"></a> 
                </div>
          <?php } else { ?>
                <div class="col-lg-3">
                    <label class="name"></label>
                    <div class="clearfix"></div>
                </div>
          <?php } ?>
        <?php } } else { ?>
             <div class="clearfix"></div>
                <div class="col-lg-4">
                    <label class="name"> Non Unit:</label>
                    <div class="select-holder">
<!--                        <label class="select-box-lbl chose">-->

                            <select class="chosen-select form-control" name="non_unit_consumable_id" id="non_unit_consumable_id"> 
                                <option value="">Consumables</option>
                                <?php   if(!empty($NonUnitConsumablesList))
                                        {
                                            for($j=0;$j<count($NonUnitConsumablesList);$j++)
                                            {
                                                echo '<option value="'.$NonUnitConsumablesList[$j]['consumable_id'].'" >'.$NonUnitConsumablesList[$j]['name'].'</option>';
                                            }
                                        }
                                ?>
                            </select>
<!--                        </label>-->
                    </div>

                </div>
                <div class="col-lg-4">
                    <label class="name"></label>
                    <input type="text" name="non_unit_consumable_quantity" id="non_unit_consumable_quantity" class="form-control" value="" maxlength="20" />
                </div>
                <div class="col-lg-4">
                    <label class="name"></label>
                    <div class="clearfix"></div>
                    <a href="javascript:void(0);" style="color:#7CA32F;" title="Add" onclick="javascript:add_more_non_unit_consumable('1');"><img src="images/add.png"></a> &nbsp;  
                    <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete" onclick="javascript:del_more_non_unit_consumable('1');"><img src="images/remove1.png"></a> 
                </div>
        <?php } ?>
    <input type="hidden" name="non_unit_consumable_extras" id="non_unit_consumable_extras" value='0' />
    <div id='non_unit_consumable_div_1'>
    </div>
</div>
</div>