<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class knowledgedocsClass extends AbstractDB 
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
    public function knowledgeDocsList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        $isTrash=$this->escape($arg['isTrash']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (title LIKE '%".$search_value."%')"; 
        }
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .="ORDER BY ".$filter_name." ".$filter_type.""; 
        }
        if(!empty($isTrash) && $isTrash !='null')
        {
           $preWhere .="AND status='3'"; 
        }
        else 
        {
          $preWhere .="AND status IN ('1','2')";   
        }
        $KnowledgeDocumentsSql="SELECT document_id FROM sp_knowledge_base_documents WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($KnowledgeDocumentsSql);
        if ($this->num_of_rows($this->result))
        {
            $pager = new PS_Pagination($KnowledgeDocumentsSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Record Detail
                $RecordSql="SELECT document_id,title,document_file,status,isDelStatus,added_date FROM sp_knowledge_base_documents WHERE document_id='".$val_records['document_id']."'";
                $RecordResult=$this->fetch_array($this->query($RecordSql));
                
                // Getting Knowledge Document Status
                if(!empty($RecordResult['status']))
                {
                    $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                    $RecordResult['statusVal']=$StatusArr[$RecordResult['status']];
                }
                
                // Document File 
                if(!empty($RecordResult['document_file']))
                {
                   $RecordResult['doc_file']=$GLOBALS['knowledgeDocument'].$RecordResult['document_file']; 
                }
                
                
                $this->resultKnowledgeDocuments[]=$RecordResult;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultKnowledgeDocuments))
        {
            $resultArray['data']=$this->resultKnowledgeDocuments;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
    public function GetKnowledgeDocumentById($arg)
    {
        $knowledge_document_id=$this->escape($arg['knowledge_document_id']);
        $GetOneKnowledgeDocSql="SELECT document_id,title,document_file,status,isDelStatus,added_by,added_date,last_modified_by,last_modified_date FROM sp_knowledge_base_documents WHERE document_id='".$knowledge_document_id."'";
        if($this->num_of_rows($this->query($GetOneKnowledgeDocSql)))
        {
            $KnowledgeDoc=$this->fetch_array($this->query($GetOneKnowledgeDocSql));
            // Getting Status
            if(!empty($KnowledgeDoc['status']))
            {
                $StatusArr=array(1=>'Active',2=>'Inactive',3=>'Deleted');
                $KnowledgeDoc['statusVal']=$StatusArr[$KnowledgeDoc['status']];
            }
            // Getting Added User Name 
            if(!empty($KnowledgeDoc['added_by']))
            {
               $AddedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$KnowledgeDoc['added_by']."'";
               $AddedUser=$this->fetch_array($this->query($AddedUserSql));
               $KnowledgeDoc['added_by']=$AddedUser['name']; 
            }
            // Getting Last Mpdofied User Name 
            if(!empty($KnowledgeDoc['last_modified_by']))
            {
               $ModifiedUserSql="SELECT name FROM sp_admin_users WHERE admin_user_id='".$KnowledgeDoc['last_modified_by']."'";
               $ModifiedUser=$this->fetch_array($this->query($ModifiedUserSql));
               $KnowledgeDoc['last_modified_by']=$ModifiedUser['name'];
            }
            return $KnowledgeDoc;
        }
        else 
            return 0;            
    }
    public function AddKnowledgeDocument($arg)
    {
      $knowledge_document_id=$this->escape($arg['document_id']);
      if(!empty($knowledge_document_id) && $knowledge_document_id !='')
          $ChkKnowledgeDocSql="SELECT document_id FROM sp_knowledge_base_documents WHERE title='".$arg['title']."' AND status !='3' AND document_id !='".$knowledge_document_id."'";
      else 
          $ChkKnowledgeDocSql="SELECT document_id FROM sp_knowledge_base_documents WHERE title='".$arg['title']."' AND status !='3'";
      
      if($this->num_of_rows($this->query($ChkKnowledgeDocSql)) == 0)
      {
            $insertData = array();
            $insertData['title']=$this->escape($arg['title']);
            if(!empty($arg['document_file']))
            {
                // Getting Knowledge Document while updating record 
                
                 if(!empty($knowledge_document_id))
                 {
                     
                    $KnowledgeDocSql="SELECT document_id,document_file FROM sp_knowledge_base_documents WHERE document_id='".$knowledge_document_id."'";
                    $KnowledgeDoc=$this->fetch_array($this->query($KnowledgeDocSql));
                    $document_file =$KnowledgeDoc['document_file'];
                    if(!empty($document_file) && file_exists('../admin/KnowlegeDocuments/'.$document_file))
                    {
                        unlink('../admin/KnowlegeDocuments/'.$document_file);
                    }
                 }
                 
                $insertData['document_file']=$this->escape($arg['document_file']);
            }
            
            $insertData['last_modified_by']=$this->escape($arg['last_modified_by']);
            $insertData['last_modified_date']=$this->escape($arg['last_modified_date']);
            if(!empty($knowledge_document_id))
            {
              $where="document_id='".$knowledge_document_id."'";
              $RecordId=$this->query_update('sp_knowledge_base_documents',$insertData,$where); 
            }
            else 
            {
              $insertData['status']=$this->escape($arg['status']);
              $insertData['added_by']=$this->escape($arg['added_by']);
              $insertData['added_date']=$this->escape($arg['added_date']);
              $RecordId=$this->query_insert('sp_knowledge_base_documents',$insertData);
            }
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
        $knowledge_document_id=$this->escape($arg['knowledge_document_id']);
        $status=$this->escape($arg['status']);
        $pre_status=$this->escape($arg['curr_status']);
        $istrashDelete=$this->escape($arg['istrashDelete']);
        $login_user_id=$this->escape($arg['login_user_id']);
        $ChkKnowledgeDocumentSql="SELECT document_id,document_file FROM sp_knowledge_base_documents WHERE document_id='".$knowledge_document_id."'";
        if($this->num_of_rows($this->query($ChkKnowledgeDocumentSql)))
        {
            // Getting Document file
            
            $Knowledge_Doc_Result=$this->fetch_array($this->query($ChkKnowledgeDocumentSql));
            if($istrashDelete)
            {
                $document_file =$Knowledge_Doc_Result['document_file'];
                if(!empty($document_file) && file_exists('../admin/KnowlegeDocuments/'.$document_file))
                    unlink('../admin/KnowlegeDocuments/'.$document_file);
                
                $UpdateStatusSql="DELETE FROM sp_knowledge_base_documents WHERE document_id='".$knowledge_document_id."'";
            }
            else 
            {
                $UpdateStatusSql="UPDATE sp_knowledge_base_documents SET status='".$status."',isDelStatus='".$pre_status."',last_modified_by='".$login_user_id."',last_modified_date='".date('Y-m-d H:i:s')."' WHERE document_id='".$knowledge_document_id."'";
            }
            $RecordId=$this->query($UpdateStatusSql);
            return $RecordId;
        }
        else 
            return 0;
    }
}
//END
?>