<?php

class EN81Calculator
{
	var $tableOne;
	var $tableTwo;
	
	function __construct() {
		$this->tableOne = new TableOne();
		$this->tableTwo = new TableTwo();
	}
	
	function getMinLoadByArea($area) {
		$result = $this->tableOne->findLoad($area);
		return $result->getNextLoad();
	}
	
	function getInterpolatedLoadByArea($area) {
		$result = $this->tableOne->findLoad($area);
		return $result->getLoad();
	}
	
	function getPassengersByArea($area) {
		$result = $this->tableTwo->findPassengers($area);
		return $result->getPassengers();
	}
	
	function getMaxArea($minLoad) {
		$result = $this->tableOne->findArea($minLoad);
		
		if ($result->getLoad() == $minLoad)
			return $result->getArea();
		else
			return $result->getNextArea();
	}
	
	function getMinArea($minLoad) {
		$passengers = intval(floor($minLoad / 75.0));
		$result = $this->tableTwo->findArea($passengers);
		return $result->getArea();
	}
	
	function getPassengersByLoad($minLoad) {
		// 8.2.3 Number of passengers
		return intval(floor($minLoad / 75.0));
	}
	
	function getLoadByPassengers($passengers) {
		return $passengers * 75;
	}
	
	function getPassengers($minLoad, $area) {
		return min(getPassengersByLoad($minLoad), getPassengersByArea($area));
	}
	
	function getTableOneAsHTML() {
		
	}
	
	function getTableTwoAsHTML() {
		
	}
}
?>