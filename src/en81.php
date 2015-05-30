<?php

class EN81Calculator
{
	var $tableOne;
	var $tableTwo;
	
	function __construct() {
		$this->tableOne = new TableOne();
		$this->tableTwo = new TableTwo();
	}
		
	private function ensureNumeric($var) {
		if (!is_int($var) && !is_float($var)) 
			throw new InvalidArgumentException('Numeric type was expected. Input was: '.gettype($var));
	}
	
	function getMinLoadByArea($area) {
		$this->ensureNumeric($area);
		$result = $this->tableOne->findLoad($area);
		return $result->getNextLoad();
	}
	
	function getInterpolatedLoadByArea($area) {
		$this->ensureNumeric($area);
		$result = $this->tableOne->findLoad($area);
		return $result->getLoad();
	}
	
	function getPassengersByArea($area) {
		$this->ensureNumeric($area);
		$result = $this->tableTwo->findPassengers($area);
		return $result->getPassengers();
	}
	
	function getMaxArea($minLoad) {
		$this->ensureNumeric($minLoad);
		
		$result = $this->tableOne->findArea($minLoad);
		
		if ($result->getLoad() == $minLoad)
			return $result->getArea();
		else
			return $result->getNextArea();
	}
	
	function getMinArea($passengers) {
		$this->ensureNumeric($passengers);
		$passengers = intval($passengers);
		$result = $this->tableTwo->findArea($passengers);
		return $result->getArea();
	}
	
	function getPassengersByLoad($minLoad) {
		$this->ensureNumeric($minLoad);
		// 8.2.3 Number of passengers
		return intval(floor($minLoad / 75.0));
	}
	
	function getLoadByPassengers($passengers) {
		$this->ensureNumeric($passengers);
		return $passengers * 75;
	}
	
	function getPassengers($minLoad, $area) {
		$this->ensureNumeric($area);
		$this->ensureNumeric($minLoad);
		return min(getPassengersByLoad($minLoad), getPassengersByArea($area));
	}
	
	function getTableOneAsHTML() {
		
	}
	
	function getTableTwoAsHTML() {
		
	}
}
?>