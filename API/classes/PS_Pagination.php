<?php
/**
 * PHPSense Pagination Class
 *
 * PHP tutorials and scripts
 *
 * @package		PHPSense
 * @author		Jatinder Singh Thind
 * @copyright	Copyright (c) 2006, Jatinder Singh Thind
 * @link		http://www.phpsense.com
 */
 
// ------------------------------------------------------------------------

class PS_Pagination extends AbstractDB 
{
	var $php_self;
	var $rows_per_page; //Number of records to display per page
	var $total_rows; //Total number of rows returned by the query
	var $page_no; //Number of links to display per page
	var $sql;
	var $debug = false;
	var $conn;
	var $page;
	var $max_pages;
	var $offset;
	
	/**
	 * Constructor
	 *
	 * @param resource $connection Mysql connection link
	 * @param string $sql SQL query to paginate. Example : SELECT * FROM users
	 * @param integer $rows_per_page Number of records to display per page. Defaults to 10
	 * @param integer $links_per_page Number of links to display per page. Defaults to 5
	 */
	 
	function PS_Pagination($sql, $rows_per_page, $page_no = 1,$query_string) {
		//$this->conn = $connection;
		$this->sql = $sql;
                if(!empty($rows_per_page))
                    $this->rows_per_page = $rows_per_page;
                else
                    $this->rows_per_page = pageSize;
		$this->query_string=$query_string;
		$this->php_self = htmlspecialchars($_SERVER['PHP_SELF']);
                $this->page = intval($page_no);
	}
	
	/**
	 * Executes the SQL query and initializes internal variables
	 *
	 * @access public
	 * @return resource
	 */
	function paginate() {
		/*if(!$this->conn) {
			if($this->debug) echo "MySQL connection missing<br />";
			return false;
		}*/
		
		$all_rs = mysql_query($this->sql);
		if(!$all_rs) {
			if($this->debug) echo "SQL query failed. Check your query.<br />";
			return false;
		}
		$this->total_rows = $this->num_of_rows($all_rs);
		//@mysql_close($all_rs);
		if($this->rows_per_page!="all")
                {
                    $this->max_pages = ceil($this->total_rows/$this->rows_per_page);
                    //Check the page value just in case someone is trying to input an aribitrary value
                    if($this->page > $this->max_pages || $this->page <= 0) {
                            $this->page = 1;
                    }

                    //Calculate Offset
                    $this->offset = $this->rows_per_page * ($this->page-1);

                    //Fetch the required result set
                    $rs = mysql_query($this->sql." LIMIT {$this->offset}, {$this->rows_per_page}");
                }
                else
                    $rs = mysql_query($this->sql);
		if(!$rs) {
			if($this->debug) echo "Pagination query failed. Check your query.<br />";
			return false;
		}
		return $rs;
	}
	
	/**
	 * Display the link to the first page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'First'
	 * @return string
	 */
	function renderFirst($tag='&#171; First') {
		if($this->page == 1) {
			return "<span class=\"disabled\">".$tag."</span>";
		}
		else {
			return '<a href="'.$this->php_self.'?page=1'.$this->query_string.'">'.$tag.'</a>';
		}
	}
	
	/**
	 * Display the link to the last page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'Last'
	 * @return string
	 */
	function renderLast($tag='Last &#187;') {
		if($this->page == $this->max_pages) {
			return "<span class=\"disabled\">".$tag."</span>";
		}
		else {
			return '<a href="'.$this->php_self.'?page='.$this->max_pages.$this->query_string.'">'.$tag.'</a>';
		}
	}
	
	/**
	 * Display the next link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '>>'
	 * @return string
	 */
	function renderNext($tag='&#187;') {
		if($this->page < $this->max_pages) {
			return '<a href="'.$this->php_self.'?page='.($this->page+1).$this->query_string.'">'.$tag.'</a>';
		}
		else {
			return "<span class=\"disabled\">".$tag."</span>";
		}
	}
	
	/**
	 * Display the previous link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '<<'
	 * @return string
	 */
	function renderPrev($tag='&#171;') {
		if($this->page > 1) {
			return '<a href="'.$this->php_self.'?page='.($this->page-1).$this->query_string.'">'.$tag.'</a>';
		}
		else {
			return "<span class=\"disabled\">".$tag."</span>";
		}
	}
	
	/**
	 * Display the page links
	 *
	 * @access public
	 * @return string
	 */
	function renderNav() {
		for($i=1;$i<=$this->max_pages;$i+=$this->links_per_page) {
			if($this->page >= $i) {
				$start = $i;
			}
		}
		
		if($this->max_pages > $this->links_per_page) {
			$end = $start+$this->links_per_page;
			if($end > $this->max_pages) $end = $this->max_pages+1;
		}
		else {
			$end = $this->max_pages;
		}
			
		$links = '';
		
		for( $i=$start ; $i<$end ; $i++) {
			if($i == $this->page) {
				$links .= "<span class=\"current\">$i</span>";
			}
			else {
				$links .= ' <a href="'.$this->php_self.'?page='.$i.$this->query_string.'">'.$i.'</a> ';
			}
		}
		
		return $links;
	}
	
	/**
	 * Display full pagination navigation
	 *
	 * @access public
	 * @return string
	 */
	function renderFullNav() 
	{
		if($this->max_pages > 1 && $this->max_pages <= 5)
			return "<div class=\"pagination\">".$this->renderPrev().'&nbsp;'.$this->renderNav().'&nbsp;'.$this->renderNext().'&nbsp;'."</div>";
		else if($this->max_pages > 1)
			return "<div class=\"pagination\">".$this->renderFirst().'&nbsp;'.$this->renderPrev().'&nbsp;'.$this->renderNav().'&nbsp;'.$this->renderNext().'&nbsp;'.$this->renderLast()."</div>";		
	}
	
	/**
	 * Set debug mode
	 *
	 * @access public
	 * @param bool $debug Set to TRUE to enable debug messages
	 * @return void
	 */
	function setDebug($debug) {
		$this->debug = $debug;
	}
}
?>
