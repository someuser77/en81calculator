<?php
class EN81Tests extends PHPUnit_Framework_TestCase {
	var $en81;
	
	function __construct() {
		$this->en81 = new EN81Calculator();
	}
	
	function testGetMinLoadByAreaInterpolated() {
		$expected = 525;
		$actual = $this->en81->getMinLoadByArea(1.31);
		$this->assertEquals($expected, $actual);
	}
	
	function testGetMinLoadByAreaExact() {
		$expected = 450;
		$actual = $this->en81->getMinLoadByArea(1.30);
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
		$actual = $this->en81->getMinArea(18);
		$this->assertEquals($expected, $actual);
	}
	
	function testGetPassengersByLoad() {
		$expected = 18;
		$actual = $this->en81->getPassengersByLoad(1351);
		$this->assertEquals($expected, $actual);
	}
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testInputStringIsRejected()
	{
		$area = 1.1 * 2.1 + 0.08 * 0.9 + 0.04 * 0.45; // = 2.4
		$area = (string)$area;
		$result = $this->en81->getMinLoadByArea($area);
		
		$this->assertEquals(1050, $result);
	}
}

?>