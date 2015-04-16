<?php
class TableOneLookupTests extends PHPUnit_Framework_TestCase {
	
	var $tableOne;
	
	function __construct() {
		$this->tableOne = new TableOne();
	}
	
	public function testExactAreaLookup()
    {
		$actual = $this->tableOne->findArea(300);
		$expected = new TableOneValuePair(300, 0.90, false);
        
        $this->assertEquals($expected, $actual);
		
    }
	
	public function testLowerBoundAreaLookup()
    {
		$actual = $this->tableOne->findArea(100);
		$expected = new TableOneValuePair(100, 0.37, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testUpperBoundAreaLookup()
    {
		$actual = $this->tableOne->findArea(2500);
		$expected = new TableOneValuePair(2500, 5.0, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testIntermediateAreaLookup()
    {
		$load = 550;
		$actual = $this->tableOne->findArea($load);
		$expectedArea = (1.60 - 1.45) / (600 - 525) * ($load - 525) + 1.45;
		$expected = new TableOneValuePair($load, $expectedArea, true, 600, 1.60, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testAreaLookupWithOneExtrapolation()
	{
		$load = 2545;
		$result = $this->tableOne->findArea($load);
		$expectedArea = 5.0 + (5.16 - 5.0) / (2600 - 2500) * ($load - 2500);
		$expected = new TableOneValuePair($load, $expectedArea, true, 2600, 5.16, true);
        
        $this->assertEquals($expected, $result);
	}
	
	public function testAreaLookupWithTwoExtrapolation()
	{
		$load = 2699;
		$result = $this->tableOne->findArea($load);
		$expectedArea = 5.16 + (5.32 - 5.16) / (2700 - 2600) * ($load - 2600);
		$expected = new TableOneValuePair($load, $expectedArea, true, 2700, 5.32, true);
        
        $this->assertEquals($expected, $result);
	}
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testAreaBelowMinimal()
    {
		$result = $this->tableOne->findArea(10);
    }
	
	public function testExactLoadLookup()
    {
		$actual = $this->tableOne->findLoad(0.90);
		$expected = new TableOneValuePair(300, 0.90, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testLowerBoundLoadLookup()
    {
		$actual = $this->tableOne->findLoad(0.37);
		$expected = new TableOneValuePair(100, 0.37, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testUpperBoundLoadLookup()
    {
		$actual = $this->tableOne->findLoad(5.0);
		$expected = new TableOneValuePair(2500, 5.0, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testIntermediateLoadLookup()
    {
		$area = 1.5;
		$actual = $this->tableOne->findLoad($area);
		$expectedLoad = (600.0 - 525.0) / (1.6 - 1.45) * ($area - 1.45) + 525;
		$expected = new TableOneValuePair($expectedLoad, $area, true, 600, 1.6, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testLoadLookupWithOneExtrapolation()
	{
		$area = 5.072;
		$result = $this->tableOne->findLoad($area);
		$expectedLoad = (2600 - 2500) / (5.16 - 5.0) * ($area - 5.0) + 2500;
		$expected = new TableOneValuePair($expectedLoad, $area, true, 2600, 5.16, true);
        
        $this->assertEquals($expected, $result);
	}
	
	public function testLoadLookupWithTwoExtrapolation()
	{
		$area = 5.3184;
		$result = $this->tableOne->findLoad($area);
		$expectedLoad = (2700 - 2600) / (5.32 - 5.16) * ($area - 5.16) + 2600;
		$expected = new TableOneValuePair($expectedLoad, $area, true, 2700, 5.32, true);
        
        $this->assertEquals($expected, $result);
	}
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testLoadBelowMinimal()
    {
		$result = $this->tableOne->findArea(10);
    }
}

?>