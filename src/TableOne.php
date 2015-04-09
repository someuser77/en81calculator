<?php
class TableOne extends Table {
	var $load;
	var $area;
	
	function __construct() {
		$table = [
			100 => 0.37
		];
	
		$this->load = array_keys($table);
		$this->area = array_values($table);
		parent::__construct($this->load, $this->area);
	}
	
	function findArea($load) {
		$idx = $this->findInFirstColumn($load);
		if ($idx == -1) return -1;
		if ($this->firstColumn[$idx] == $load) {
			return $this->secondColumn[$idx];
		}
		
		return -1;
	}
}

?>