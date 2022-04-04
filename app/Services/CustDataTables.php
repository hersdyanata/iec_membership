<?php
namespace App\Services;
use DB;

class CustDataTables{
	var $table;
	var $colOrder;
	var $colSearch;
	var $order;
	var $CI;
	var $dbcustom;
	
	public function set_table($table) 
	{
		$this->table = $table;
	}
	
	public function set_col_order($colOrder) 
	{
		$this->colOrder = $colOrder;
	}
	
	public function set_col_search($colSearch) 
	{
		$this->colSearch = $colSearch;
	}
	
	public function set_order($order) 
	{
		$this->order = $order;
	}
	
	private function _get_datatables_query()
	{
		$filter = array();
		foreach ($this->colSearch as $item) // loop column 
		{
			if($_POST['search']['value']) $filter[] = $item." LIKE '%".$_POST['search']['value']."%'";
		}
		
		$where = "";
		if ($filter) $where = "where ".implode("OR ",$filter);
		
		if(isset($_POST['order'])) $order = "order by ".$this->colOrder[$_POST['order']['0']['column']]." ".$_POST['order']['0']['dir'];
		else if(isset($this->order)) $order = $this->order;
		
		return "select * from (".$this->table.") zzz $where $order ";
	}

	function get_datatables()
	{
		$query = $this->_get_datatables_query();
		if($_POST['length'] != -1) $query .= "limit ".$_POST['start'].", ".$_POST['length'];
		$query = DB::select(DB::raw("SELECT * from ($query)z"));
		return $query;
	}

	function count_filtered()
	{
		
		$query = $this->_get_datatables_query();
		$query = DB::select(DB::raw($query));
		return count($query);
	}

	public function count_all()
	{
		$query = DB::select(DB::raw($this->table));
		return count($query);
	}
}