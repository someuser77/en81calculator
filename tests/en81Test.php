<?php
class EN81Tests extends PHPUnit_Framework_TestCase {
	var $en81;
	
	function __construct() {
		$this->en81 = new EN81Calculator();
	}
	
	function testGetMinLoadByArea() {
		$expected = 525;
		$actual = $this->en81->getMinLoadByArea(1.31);
		$this->assertEquals($expected, $actual);
	}
	
	function testGetInterpolatedLoadByArea() {
		$expected =  (525 - 450) / (1.45 - 1.30) * (1.31 - 1.30) + 450;
		$actual = $this->en81->getInterpolatedLoadByArea(1.31);
		$this->assertEquals($expected, $actual);
	}
	
	function testGetPassengersByArea() {
		$expected = 16;
		$actual = $this->en81->getPassengersByArea(2.6);
		$this->assertEquals($expected, $actual);
	}
	
	function testGetMaxAreaExact() {
		$expected = 3.1;
		$actual = $this->en81->getMaxArea(1350);
		$this->assertEquals($expected, $actual);
	}
	
	function testGetMaxArea() {
		$expected = (3.25 - 3.1) / (1425.0 - 1350.0) * (1351 - 1350) + 3.1;
		$actual = $this->en81->getMaxArea(1351);
		$this->assertEquals($expected, $actual);
	}
	
	function testGetMinArea() {
		$expected = 2.85;
		$actual = $this->en81->getMinArea(1351);
		$this->assertEquals($expected, $actual);
	}
	
	function testGetPassengersByLoad() {
		$expected = 18;
		$actual = $this->en81->getPassengersByLoad(1351);
		$this->assertEquals($expected, $actual);
	}
}

?>