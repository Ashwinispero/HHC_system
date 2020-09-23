
<?php
    if(!class_exists('AbstractDB'))
        require_once dirname(__FILE__) . '/AbstractDB.php';
    if(!class_exists('PS_Pagination'))
        require_once dirname(__FILE__) . '/PS_Pagination.php';
    require_once dirname(__FILE__) . '/functions.php';

    class avayaClass extends AbstractDB 
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
       public function insert_avaya_incoming_call($arg)
    {
      //echo 'dfvsf';
        $query = $this->query_insert('sp_incoming_call', $arg);
        return 'True';
        
    }
}