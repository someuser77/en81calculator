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
	
	private function getAreaBeyondTable($load) {
		$extraLoad = $load - $this->load[count($this->load) - 1];
		// beyond 2500 we add 0.16 for each extra 100 kg. 
		return $this->area[count($this->area) - 1] + ceil($extraLoad / 100.0) * 0.16;
	}
	
	private function findInLoadColumn($load) { return $this->findInFirstColumn($load); }
	private function findInAreaColumn($area) { return $this->findInSecondColumn($area); }
	
	function findArea($load) {
		if ($load < $this->load[0]) throw new InvalidArgumentException('The minimal load is '.$load[0]);
		
		$idx = $this->findInLoadColumn($load);
		
		if ($idx == -1 && $load > $this->load[count($this->load) - 1]){
			return $this->getAreaBeyondTable($load);
		}
		
		if ($idx == -1) return -1;
		
		if ($this->load[$idx] == $load) {
			return $this->area[$idx];
		}
		
		$loadMin = $this->load[$idx];
		$loadMax = $this->load[$idx + 1];
		$areaMin = $this->area[$idx];
		$areaMax = $this->area[$idx + 1];		
		
		return ($areaMax - $areaMin) / ($loadMax - $loadMin) * ($load - $loadMin) + $areaMin;
	}
	
	
}

?>