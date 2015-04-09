<?php
class TableOne extends Table {
	var $load;
	var $area;
	
	function __construct() {
		$table = [
			100 => 0.37,
			180 => 0.58,
			225 => 0.70,
			300 => 0.90,
			375 => 1.10,
			400 => 1.17,
			450 => 1.30,
			525 => 1.45,
			600 => 1.60,
			630 => 1.66,
			675 => 1.75,
			750 => 1.90,
			800 => 2.0,
			825 => 2.05,
			900 => 2.20,
			975 => 2.35,
			1000 => 2.40,
			1050 => 2.50,
			1125 => 2.65,
			1200 => 2.80,
			1250 => 2.90,
			1275 => 2.95,
			1350 => 3.10,
			1425 => 3.25,
			1500 => 3.40,
			1600 => 3.56,
			2000 => 4.20,
			2500 => 5.00
		];
	
		$this->load = array_keys($table);
		$this->area = array_values($table);
		parent::__construct($this->load, $this->area);
	}
	
	function findArea($load) {
		if ($load < 100) throw new InvalidArgumentException('The minimal load is 100.');
		$idx = $this->findInFirstColumn($load);
		if ($idx == -1) return -1;
		if ($this->firstColumn[$idx] == $load) {
			return $this->secondColumn[$idx];
		}
		
		$loadMin = $this->firstColumn[$idx];
		$loadMax = $this->firstColumn[$idx + 1];
		$areaMin = $this->secondColumn[$idx];
		$areaMax = $this->secondColumn[$idx + 1];
		
		return ($areaMax - $areaMin) / ($loadMax - $loadMin) * ($load - $loadMin) + $areaMin;		
	}
}

?>