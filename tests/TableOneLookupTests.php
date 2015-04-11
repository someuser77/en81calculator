<?php
class TableLookupTests extends PHPUnit_Framework_TestCase {
	
	var $tableOne;
	
	function __construct() {
		$this->tableOne = new TableOne();
	}
	
	public function testExactAreaLookup()
    {
		$actual = $this->tableOne->findArea(300);
		$expected = new TableOneValuePair(300, 0.90, false);
        // Assert
        $this->assertEquals($expected, $actual);
		
    }
	
	public function testLowerBoundAreaLookup()
    {
		$actual = $this->tableOne->findArea(100);
		$expected = new TableOneValuePair(100, 0.37, false);
        // Assert
        $this->assertEquals($expected, $actual);
    }
	
	public function testUpperBoundAreaLookup()
    {
		$actual = $this->tableOne->findArea(2500);
		$expected = new TableOneValuePair(2500, 5.0, false);
        // Assert
        $this->assertEquals($expected, $actual);
    }
	
	public function testIntermediateAreaLookup()
    {
		$load = 550;
		$actual = $this->tableOne->findArea($load);
		$x1 = 525;
		$y1 = 1.45;
		$x2 = 600;
		$y2 = 1.60;
		$expectedArea = ($y2 - $y1) / ($x2 - $x1) * ($load - $x1) + $y1;
		$expected = new TableOneValuePair($load, $expectedArea, true, $x2, $y2, false);
        // Assert
        $this->assertEquals($expected, $actual);
    }
	
	public function testAreaLookupWithOneExtrapolation()
	{
		$load = 2545;
		$result = $this->tableOne->findArea($load);
		$expectedArea = 5.0 + (5.16 - 5.0) / (2600 - 2500) * ($load - 2500);
		$expected = new TableOneValuePair($load, $expectedArea, true, 2600, 5.16, true);
        // Assert
        $this->assertEquals($expected, $result);
	}
	
	public function testAreaLookupWithTwoExtrapolation()
	{
		$load = 2699;
		$result = $this->tableOne->findArea($load);
		$expectedArea = 5.16 + (5.32 - 5.16) / (2700 - 2600) * ($load - 2600);
		$expected = new TableOneValuePair($load, $expectedArea, true, 2700, 5.32, true);
        // Assert
        $this->assertEquals($expected, $result);
	}
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testAreaBelowMinimal()
    {
		$result = $this->tableOne->findArea(10);
    }
}

?>