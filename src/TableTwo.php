<?php
class TableTwoValue {
	
	private $passengers;
	private $area;
	private $isExtrapolated;
	
	function __construct($passengers, $area, $isExtrapolated) {
		$this->passengers = $passengers;
		$this->area = $area;
		$this->isExtrapolated = $isExtrapolated;
	}
	
	public function getPassengers() { return $this->passengers; }
	public function getArea() { return $this->area; }
	public function isExtrapolated() { return $this->isExtrapolated; }
}

class TableTwoValuePair {
	private $value;
	private $next;
	
	public function __construct($passengers, $area, $isExtrapolated, $nextLoad = null, $nextArea = null, $isNextExtrapolated = null) {
		$this->value = new TableTwoValue($passengers, $area, $isExtrapolated);
		if ($nextLoad != null && $nextArea != null && $isNextExtrapolated != null) {
			$this->next = new TableTwoValue($nextPassengers, $nextArea, $isNextExtrapolated);
		} else {
			$this->next = null;
		}
	}
	
	public function getPassengers() { return $this->value->getPassengers(); }
	public function getArea() { return $this->value->getArea(); }
	public function isExtrapolated() { return $this->value->isExtrapolated(); }
	public function getNextPassengers() { return $this->next->getPassengers(); }
	public function getNextArea() { return $this->next->getArea(); }
	public function isNextExtrapolated() { return $this->next->isExtrapolated(); }
}


class TableTwo extends Table {
	var $passengers;
	var $area;
	var $maxDefinedPassengers;
	var $maxDefinedArea;
	var $areaToAddBeyondTable;
	
	function __construct() {
		$table = [
			1 => 0.28,
			2 => 0.49,
			3 => 0.60,
			4 => 0.79,
			5 => 0.98,
			6 => 1.17,
			7 => 1.31,
			8 => 1.45,
			9 => 1.59,
			10 => 1.73,
			11 => 1.87,
			12 => 2.01,
			13 => 2.15,
			14 => 2.29,
			15 => 2.43,
			16 => 2.57,
			17 => 2.71,
			18 => 2.85,
			19 => 2.99,
			20 => 3.13
		];
	
		$this->passengers = array_keys($table);
		$this->area = array_values($table);
		$this->maxDefinedPassengers = $this->passengers[count($this->passengers) - 1];
		$this->maxDefinedArea = $this->area[count($this->area) - 1];
		$this->areaToAddBeyondTable = 0.115;
		
		parent::__construct($this->load, $this->area);
	}
	
	private function findInPassengersColumn($passengers) { return $this->findInFirstColumn($passengers); }
	private function findInAreaColumn($area) { return $this->findInSecondColumn($area); }
	
	function findArea($passengers) {
		if (!is_int($passengers)) throw new InvalidArgumentException("'".$passengers."' is not a valid integer.");
		if ($passengers < 1) throw new InvalidArgumentException("The number of passengers must be more than 0.");
		if ($passengers < $this->maxDefinedPassengers) 
			return new TableTwoValuePair($passengers, $this->area[$passengers], false);
		$extraPassengers = $passengers - $this->maxDefinedPassengers;
		$area = $this->maxDefinedArea + $extraPassengers * $this->areaToAddBeyondTable;
		return new TableTwoValuePair($passengers, $area, true, $passengers + 1, $area + $this->areaToAddBeyondTable);
	}
	
	function findPassengers($area) {
		
	}
}

?>