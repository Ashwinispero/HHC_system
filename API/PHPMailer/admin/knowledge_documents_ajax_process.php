<?php
require_once 'inc_classes.php';
require_once '../classes/knowledgedocsClass.php';
$knowledgedocsClass=new knowledgedocsClass();
require_once "../classes/thumbnail_images.class.php";
require_once "../classes/SimpleImage.php";
if($_REQUEST['action']=='vw_add_knowledge_document')
{
    // Getting Feedback Details
    $arr['knowledge_document_id']=$_REQUEST['knowledge_document_id'];
    $KnowledgeDocDtls=$knowledgedocsClass->GetKnowledgeDocumentById($arr);
 ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><?php if(!empty($KnowledgeDocDtls)) { echo "Edit"; } else { echo "Add"; } ?> Knowledge Document </h4>
    </div>
    <div class="modal-body">
        <form class="form-inline" name="frm_add_knowledge_document" id="frm_add_knowledge_document" method="post" enctype="multipart/form-data" action ="knowledge_documents_ajax_process.php?action=add_knowledge_document" autocomplete="off">
            <div class="scrollbars">
                <div class="editform">
                    <label>
                        <input type="hidden" name="knowledge_document_id" id="knowledge_document_id" value="<?php if(!empty($KnowledgeDocDtls)) { echo $KnowledgeDocDtls['document_id']; } ?>" />
                        <input type="text" name="title" id="title" value="<?php if(!empty($POST['title'])) { echo $_POST['title']; } else if($KnowledgeDocDtls['title']) { echo $KnowledgeDocDtls['title'];  } else { echo ""; } ?>" class="validate[required,maxSize[50]] form-control" placeholder="title" maxlength="50" onkeyup="if (/[^a-zA-Z0-9 ,-/()]/g.test(this.value)) this.value = this.value.replace(/[^a-zA-Z0-9 ,-/()]/g,'')" style="padding-left: 5%;" />
                    </label>
                    <div class="value">
                        <input type="file" name="userfile" id="userfile" class="docfile <?php if(empty($KnowledgeDocDtls)) { echo "validate[required]"; } ?>" />
                        <div class="clearfix"></div>
                        <span class="text-hint">(Only PDF files are allowed)</span>                                
                    </div>
                    <?php if(empty($KnowledgeDocDtls)) { ?>
                    <label style="width:10%;">
                        <a href="javascript:void(0);" style="color:#7CA32F;" title="Add Document" onclick="javascript:add_more_document('1');">(+)</a>   
                            <a href="javascript:void(0);" style="color:#7CA32F;" title="Delete Document" onclick="javascript:del_more_document('1');">(-)</a> 
                    </label>
                    <?php } ?>
                </div>
                <input type="hidden" name="extras" id="extras" value='0' />
                <div id='div_1'>
                </div>
                <div class="modal-footer">
                    <input type="button" name="submitForm" id="submitForm" class="btn btn-download" value="Save Changes" onclick="return add_knowledge_document_submit();" />
                </div>  
            </div>
        </form>
    </div>
 <?php   
}
else if($_REQUEST['action']=='AddDocumentRow')
{
    $i = $_REQUEST['curr_div'];  
    $j = $i+1;
    ?>
        <div id="div_<?php echo $i;?>">
            <div class="editform">   
                <label>
                    <input type="text" name="title<?php echo $i;?>" id="title<?php echo $i;?>" class="validate[required,maxSize[50]] form-control" placeholder="title" maxlength="50" onkeyup="if (/[^a-zA-Z0-9 ,-/()]/g.test(this.value)) this.value = this.value.replace(/[^a-zA-Z0-9 ,-/()]/g,'')" />
                </label>
                <div class="value">
                    <input type="file" name="userfile<?php echo $i;?>" id="userfile<?php echo $i;?>" class="docfile validate[required]" /><br/><span class="text-hint">(Only PDF files are allowed)</span>
                </div>
         </div>
        </div>
        <div id="div_<?php echo $j;?>"></div>
   <?php 
}
else if($_REQUEST['action']=='add_knowledge_document')
{
    $success=0;
    $errors=array(); 
    $i=0;
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $extra_sub_documents = strip_tags($_POST['extras']);
        $knowledge_document_id=strip_tags($_POST['knowledge_document_id']);
        $title=strip_tags($_POST['title']);
        if($title=='')
        {
            $success=0;
            $errors[$i++]="Please enter title";
        }
        if(!empty($_FILES['userfile']["name"]))
        {
           $uploaded_document_file="";          
           if(count($errors)==0 && $_FILES['userfile']["name"])
           { 
               $file_str=preg_replace('/\s+/', '_', $_FILES['userfile']["name"]);
               $uploaded_document_file=time().basename($file_str);
               $newfile ="../admin/KnowlegeDocuments/";
               $filename = $_FILES['userfile']['tmp_name']; // File being uploaded.
               $filetype = $_FILES['userfile']['type']; // type of file being uploaded
               $filesize = filesize($filename); // File size of the file being uploaded.
               $source1 = $_FILES['userfile']['tmp_name'];
               $target_path1 = $newfile.$uploaded_document_file;
               list($width1, $height1, $type1, $attr1) = getimagesize($source1);
               if(move_uploaded_file($source1, $target_path1))
               {
                    $thump_target_path="KnowlegeDocuments/".$uploaded_document_file;
                    copy($target_path1,$thump_target_path);
                    list($width, $height, $type, $attr) = getimagesize($thump_target_path);
                    $document_file_uploaded=1;
               }
                else
                {
                    $document_file_uploaded=0;
                    $success=0;
                    $errors[$i++]="There are some errors while uploading document, please try again";
                }
           }
        }
        if(count($errors))
        {
           echo 'ValidationError'; // Validation error/record exists
           exit;
        }
        // Check Record Exists 
        if($knowledge_document_id)
            $chk_knowledge_document_sql="SELECT document_id FROM sp_knowledge_base_documents WHERE title='".$title."' AND document_id !='".$knowledge_document_id."'";
        else 
            $chk_knowledge_document_sql="SELECT document_id FROM sp_knowledge_base_documents WHERE title='".$title."'"; 
        
        if(mysql_num_rows($db->query($chk_knowledge_document_sql)))
        {
            $success=0;
            echo 'knowledgedocexists';
            exit;
        }
        
        if(count($errors))
        {
           echo 'knowledgedocexists'; // Validation error/record exists
           exit;
        }
        else 
        {
            $success=1;
            $arr['document_id']=$knowledge_document_id;
            $arr['title']=  ucfirst(strtolower($title));
            if($document_file_uploaded)
            {
               $arr['document_file'] = $uploaded_document_file;
            }
           
            $arr['last_modified_by']=strip_tags($_SESSION['admin_user_id']);
            $arr['last_modified_date']=date('Y-m-d H:i:s');
            
            if(empty($knowledge_document_id))
            {
               $arr['status']='1';
               $arr['added_by']=strip_tags($_SESSION['admin_user_id']);
               $arr['added_date']=date('Y-m-d H:i:s');
            }
            $InsertRecord=$knowledgedocsClass->AddKnowledgeDocument($arr); 
            
            unset($arr['title']);
            unset($arr['document_file']);
            
            if(!empty($InsertRecord))
            {
                if($extra_sub_documents > 0)
                {
                    for($a=1;$a<=$extra_sub_documents;$a++)
                    {
                       $arr['title'] = mysql_real_escape_string($_POST['title'.$a]);
                       $uploaded_multiple_document_file=""; 
                        if(!empty($_FILES['userfile'.$a]["name"]))
                        {
                            if($_FILES['userfile'.$a]["name"])
                            { 
                                $file_str=preg_replace('/\s+/', '_', $_FILES['userfile'.$a]["name"]);
                                $uploaded_multiple_document_file=time().basename($file_str);
                                $newfile ="../admin/KnowlegeDocuments/";
                                $filename = $_FILES['userfile'.$a]['tmp_name']; // File being uploaded.
                                $filetype = $_FILES['userfile'.$a]['type']; // type of file being uploaded
                                $filesize = filesize($filename); // File size of the file being uploaded.
                                $source1 = $_FILES['userfile'.$a]['tmp_name'];
                                $target_path1 = $newfile.$uploaded_multiple_document_file; 
                                list($width1, $height1, $type1, $attr1) = getimagesize($source1);
                                if(move_uploaded_file($source1, $target_path1))
                                {
                                    $thump_target_path="../admin/KnowlegeDocuments/".$uploaded_multiple_document_file;
                                    copy($target_path1,$thump_target_path);
                                    list($width, $height, $type, $attr) = getimagesize($thump_target_path);
                                    $Multiple_document_file_uploaded=1;
                                }
                                else 
                                {
                                    $Multiple_document_file_uploaded=0;
                                }
                                if(!empty($uploaded_multiple_document_file) && $Multiple_document_file_uploaded==1)
                                 $arr['document_file'] = $uploaded_multiple_document_file;
                            }  
                        }
                        
                        if(!empty($arr['title'])&& !empty($arr['document_file']))
                            $InsertMultiDoc = $knowledgedocsClass->AddKnowledgeDocument($arr); 
                        
                            // Clear image array after inserting record
                           //unset($arr['document_file']);
                    }
                }
              
                if($knowledge_document_id)
                {
                    echo 'UpdateSuccess'; // Update Record
                    exit;
                }
                else 
                {
                    echo 'InsertSuccess'; // Insert Record
                    exit;
                }
            }
            else 
            {
               echo 'knowledgedocexists';
               exit;
            }
        } 
    }
}
else if($_REQUEST['action']=='change_status')
{
    if(!empty($_REQUEST['knowledge_document_id']) && !empty($_REQUEST['actionval']))
    {
        $arr['knowledge_document_id'] =$_REQUEST['knowledge_document_id'];
        if($_REQUEST['actionval']=='Active')
            $arr['status']='1';
        if($_REQUEST['actionval']=='Inactive')
           $arr['status']='2';
        if($_REQUEST['actionval']=='Delete')
           $arr['status']='3';
        if($_REQUEST['actionval']=='Revert')
        {
           if(!empty($_REQUEST['curr_status'])) 
            $arr['status']=$_REQUEST['curr_status'];
           else 
            $arr['status']='1';   
        }
        
        if($_REQUEST['actionval']=='CompleteDelete')
           $arr['status']='5';
        
        $arr['curr_status']=$_REQUEST['curr_status'];
        $arr['login_user_id']=$_REQUEST['login_user_id'];
        $arr['istrashDelete']=$_REQUEST['trashDelete'];

        $ChangeStatus =$knowledgedocsClass->ChangeStatus($arr);
        if(!empty($ChangeStatus))
        {
            echo 'success';
            exit;
        }
        else
        {
            echo 'error';
            exit;
        }
    } 
}
?>