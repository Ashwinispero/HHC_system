<?php   
    include "inc_classes.php";
    include "organizer_authentication.php";  
    if($_REQUEST['type']=='KnowledgeDocumentFile')
    {
       $document_id= base64_decode($_REQUEST['knowledge_document_id']);
       $select_file = "SELECT document_id,document_file FROM sp_knowledge_base_documents where document_id='".$document_id."'";
       $val_docFile = $db->fetch_array($db->query($select_file));
       if($val_docFile['document_file'] && file_exists('KnowlegeDocuments/'.$val_docFile['document_file']))
       {
           $folderName = 'KnowlegeDocuments/'.$val_docFile['document_file'];
           $ext = $val_docFile['document_file'];
       }  
    }
    header("Cache-Control: "); // leave blank to avoid IE errors
    header("Pragma: "); // leave blank to avoid IE errors
    header("Content-type: application/vnd.ms-word");
    header ("Content-type: octet/stream");
    
    if($_REQUEST['type']=='KnowledgeDocumentFile')
        header('Content-Disposition: attachment;Filename=KnowledgeDocumentFile_'.date('mdYHis').'.'.$val_docFile['document_file']);
    
    header('Content-Length: ' . filesize($folderName));
    header('Content-type: video/mpeg');
    readfile($folderName);
    //unlink($folderName);    
?>