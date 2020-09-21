<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';
    
class testClass extends AbstractDB 
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
    public function TestList($arg)
    {
        $preWhere="";
        $filterWhere="";
        $search_value= $this->escape($arg['search_Value']);
        $filter_name=$this->escape($arg['filter_name']);
        $filter_type=$this->escape($arg['filter_type']);
        if(!empty($search_value) && $search_value !='null')
        {
           $preWhere="AND (product LIKE '%".$search_value."%' OR brand_name LIKE '%".$search_value."%')"; 
        }
        if((!empty($filter_name) && $filter_name !='null') && (!empty($filter_type) && $filter_type !='null'))
        {
            $filterWhere .="ORDER BY ".$filter_name." ".$filter_type.""; 
        }
       
        //$EmployeesSql="SELECT product_id,product,EAN13,UPCA,UPCE,product,brand_name,description,created,status FROM ps_product WHERE 1 ".$preWhere." ".$filterWhere." ";
        
        $EmployeesSql="SELECT product_id FROM ps_product WHERE 1 ".$preWhere." ".$filterWhere." ";
        $this->result = $this->query($EmployeesSql);
        if ($this->num_of_rows($this->result))
        {
            $time_start = microtime(true);
           // $GetProductDtlsSql="SELECT * FROM ps_product WHERE 1 ".$preWhere." ".$filterWhere."";
            //$GetProductDtls=$this->fetch_array($this->query($GetProductDtlsSql));   
                
            $pager = new PS_Pagination($EmployeesSql,$arg['pageSize'],$arg['pageIndex'],'');
            $all_records= $pager->paginate();
            while($val_records=$this->fetch_array($all_records))
            {
                // Getting Product Details 

                $GetProductDtlsSql="SELECT product,EAN13,UPCA,UPCE,product,brand_name,description,created,status FROM ps_product WHERE product_id='".$val_records['product_id']."'";
                $GetProductDtls=$this->fetch_array($this->query($GetProductDtlsSql));    
                
                if(!empty($GetProductDtls['created']) && $GetProductDtls['created'] !='0000-00-00')
                    $GetProductDtls['created']=$GetProductDtls['created'];
                else 
                   $GetProductDtls['created']='Not Available'; 
                
                $this->resultEmployees[]=$GetProductDtls;
                 
                 
                 
               //$this->resultEmployees[]=$val_records;
            }
            $resultArray['count'] = $pager->total_rows;
        }
        if(count($this->resultEmployees))
        {
            $resultArray['timedata']=(microtime(true) - $time_start);
            $resultArray['data']=$this->resultEmployees;
            return $resultArray;
        }
        else
            return array('data' => array(), 'count' => 0); 
    }
}
//END
?>