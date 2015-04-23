<?php

class TableOneValue {
	
	private $load;
	private $area;
	private $isExtrapolated;
	
	function __construct($load, $area, $isExtrapolated) {
		$this->load = $load;
		$this->area = $area;
		$this->isExtrapolated = $isExtrapolated;
	}
	
	public function getLoad() { return $this->load; }
	public function getArea() { return $this->area; }
	public function isExtrapolated() { return $this->isExtrapolated; }
}

class TableOneValuePair {
	private $value;
	private $next;
	
	public function __construct($load, $area, $isExtrapolated, $nextLoad = null, $nextArea = null, $isNextExtrapolated = null) {
		$this->value = new TableOneValue($load, $area, $isExtrapolated);
		if ($nextLoad !== null && $nextArea !== null && $isNextExtrapolated !== null) {
			$this->next = new TableOneValue($nextLoad, $nextArea, $isNextExtrapolated);
		} else {
			$this->next = null;
		}
	}
	
	public function getLoad() { return $this->value->getLoad(); }
	public function getArea() { return $this->value->getArea(); }
	public function isExtrapolated() { return $this->value->isExtrapolated(); }
	public function getNextLoad() { return $this->next->getLoad(); }
	public function getNextArea() { return $this->next->getArea(); }
	public function isNextExtrapolated() { return $this->next->isExtrapolated(); }
}

class TableOne extends Table {
	var $load;
	var $area;
	var $maxDefinedLoad;
	var $maxDefinedArea;
	
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
		$this->maxDefinedLoad = $this->load[count($this->load) - 1];
		$this->maxDefinedArea = $this->area[count($this->area) - 1];
		
		parent::__construct($this->load, $this->area);
	}
	
	private function findInLoadColumn($load) { return $this->findInFirstColumn($load); }
	private function findInAreaColumn($area) { return $this->findInSecondColumn($area); }
	
	private function intrapolateArea($load_low, $load_high, $area_low, $area_high, $load) {
		return $this->interpolateSecondColumn($load_low, $load_high, $area_low, $area_high, $load);
	}
	
	private function intrapolateLoad($load_low, $load_high, $area_low, $area_high, $area) {
		return $this->interpolateFirstColumn($load_low, $load_high, $area_low, $area_high, $area);
	}
	
	function findArea($load) {
		if ($load < $this->load[0]) throw new InvalidArgumentException('The minimal load is '.$this->load[0]);
		
		$idx = $this->findInLoadColumn($load);
		$found = !($idx < 0);
		
		if ($found) {
			if ($this->load[$idx] == $load)
				return new TableOneValuePair($load, $this->area[$idx], false);
				
			// interpolate inside table	
			$loadMin = $this->load[$idx];
			$loadMax = $this->load[$idx + 1];
			$areaMin = $this->area[$idx];
			$areaMax = $this->area[$idx + 1];
			$upperBoundIntrapolated = false;
		} else {
			// interpolate outside table
			if ($load < $this->maxDefinedLoad) {
				throw new LogicalException('The load '.$load.' was smaller than the last element in the table but no match was found.');
			}
			
			$loadMin = floor($load / 100.0) * 100;
			$loadMax = ceil($load / 100.0) * 100;
			
			$addedSlotMin = ($loadMin - $this->maxDefinedLoad) / 100;
			$addedSlotMax = ($loadMax - $this->maxDefinedLoad) / 100;
			
			$areaMin = $this->maxDefinedArea + $addedSlotMin * 0.16;
			$areaMax = $this->maxDefinedArea + $addedSlotMax * 0.16;
			
			$upperBoundIntrapolated = true;
		}
		
		$area = $this->intrapolateArea($loadMin, $loadMax, $areaMin, $areaMax, $load);
		
		return new TableOneValuePair($load, $area, true, $loadMax, $areaMax, $upperBoundIntrapolated);
	}
	
	function findLoad($area) {
		if ($area < $this->area[0]) throw new InvalidArgumentException('The minimal area is '.$area[0]);
		
		$idx = $this->findInAreaColumn($area);
		$found = !($idx < 0);
		
		if ($found) {
			if ($this->area[$idx] == $area)
				return new TableOneValuePair($this->load[$idx], $area, false);
				
			// interpolate inside table	
			$loadMin = $this->load[$idx];
			$loadMax = $this->load[$idx + 1];
			$areaMin = $this->area[$idx];
			$areaMax = $this->area[$idx + 1];
			$upperBoundIntrapolated = false;
		} else {
			// interpolate outside table
			if ($area < $this->maxDefinedArea) {
				throw new LogicException ('The area '.$area.' was smaller than the last element in the table but no match was found.');
			}
			
			$areaMin = $this->maxDefinedArea + floor(($area - 5.0) / 0.16) * 0.16;
			$areaMax = $this->maxDefinedArea + ceil(($area - 5.0) / 0.16) * 0.16;
			
			$addedSlotMin = floor(($area - 5.0) / 0.16);
			$addedSlotMax = ceil(($area - 5.0) / 0.16);
			
			$loadMin = $this->maxDefinedLoad + $addedSlotMin * 100;
			$loadMax = $this->maxDefinedLoad + $addedSlotMax * 100;
			
			$upperBoundIntrapolated = true;
		}
		
		$load = $this->intrapolateLoad($loadMin, $loadMax, $areaMin, $areaMax, $area);
		
		return new TableOneValuePair($load, $area, true, $loadMax, $areaMax, $upperBoundIntrapolated);
	}
}

?>