<?php
class TableLookupTests extends PHPUnit_Framework_TestCase {
	
	var $tableOne;
	
	function __construct() {
		$this->tableOne = new TableOne();
	}
	
	public function testExactItemLookup()
    {
		$result = $this->tableOne->findArea(300);
		
        // Assert
        $this->assertEquals(0.90, $result);
    }
	
	public function testLowerBoundItemLookup()
    {
		$result = $this->tableOne->findArea(100);
		
        // Assert
        $this->assertEquals(0.37, $result);
    }
	
	public function testUpperBoundItemLookup()
    {
		$result = $this->tableOne->findArea(2500);
		
        // Assert
        $this->assertEquals(5.00, $result);
    }
	
	public function testIntermediateAreaLookup()
    {
		$load = 550;
		$actual = $this->tableOne->findArea($load);
		$x1 = 525;
		$y1 = 1.45;
		$x2 = 600;
		$y2 = 1.60;
		$expected = ($y2 - $y1) / ($x2 - $x1) * ($load - $x1) + $y1;
        // Assert
        $this->assertEquals($expected, $actual);
    }
	
	public function testExceedingAreaLookup()
	{
		$result = $this->tableOne->findArea(2503);
		
        // Assert
        $this->assertEquals(5.16, $result);
	}
	
	public function testIntermediateLoadLookup()
	{
	
	}
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testBelowMinimalLoad()
    {
		$result = $this->tableOne->findArea(10);
    }
}

?>