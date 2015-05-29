<?php
class TableTwoLookupTests extends PHPUnit_Framework_TestCase {
	
	var $tableTwo;
	
	function __construct() {
		$this->tableTwo = new TableTwo();
	}
	
	public function testExactAreaLookup()
    {
		$actual = $this->tableTwo->findArea(5);
		$expected = new TableTwoValue(5, 0.98, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testLowerBoundAreaLookup()
    {
		$actual = $this->tableTwo->findArea(1);
		$expected = new TableTwoValue(1, 0.28, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testUpperBoundAreaLookup()
    {
		$actual = $this->tableTwo->findArea(20);
		$expected = new TableTwoValue(20, 3.13, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testZeroPassangersAreaLookup()
    {
		$this->tableTwo->findArea(0);
    }
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testNegativePassangersAreaLookup()
    {
		$this->tableTwo->findArea(-1);
    }
	
	/**
     * @expectedException InvalidArgumentException
     */
	public function testInvalidNumberOfPassangersAreaLookup()
    {
		$this->tableTwo->findArea(16.5);
    }
	
	public function testAreaLookupBeyondTable()
	{
		$result = $this->tableTwo->findArea(26);
		$expectedArea = 3.13 + 6 * 0.115;
		$expected = new TableTwoValue(26, $expectedArea, true);
        
        $this->assertEquals($expected, $result);
	}
	
	public function testExactPassangersLookup()
    {
		$actual = $this->tableTwo->findPassengers(2.99);
		$expected = new TableTwoValue(19, 2.99, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testLowerBoundPassangersLookup()
    {
		$actual = $this->tableTwo->findPassengers(0.28);
		$expected = new TableTwoValue(1, 0.28, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testUpperBoundPassangersLookup()
    {
		$actual = $this->tableTwo->findPassengers(3.13);
		$expected = new TableTwoValue(20, 3.13, false);
        
        $this->assertEquals($expected, $actual);
    }
	
	public function testPassangersLookupAtTableBorder()
	{
		$area = 3.15;
		$result = $this->tableTwo->findPassengers($area);
		$expectedPassengers = 20;
		$expected = new TableTwoValue($expectedPassengers, $area, true);
        
        $this->assertEquals($expected, $result);
	}
	
	public function testPassangersLookupBeyondTable()
	{
		$area = 5.15;
		$result = $this->tableTwo->findPassengers($area);
		$expectedPassengers = 20 + floor(($area - 3.13) / 0.115);
		$expected = new TableTwoValue($expectedPassengers, $area, true);
        
        $this->assertEquals($expected, $result);
	}
	
	public function testTableTwoValueAPI()
	{
		$item = new TableTwoValue(1, 2, true);
		$this->assertEquals(1, $item->getPassengers());
		$this->assertEquals(2, $item->getArea());
		$this->assertEquals(true, $item->isExtrapolated());
	}
	
	public function testFindAreaForLargePassengers()
	{
		$passengers = 114;
		$result = $this->tableTwo->findArea($passengers);
		
		$expectedArea = 3.13 + (114.0 - 20.0) * 0.115;
		$expected = new TableTwoValue($passengers, $expectedArea, true);
        
        $this->assertEquals($expected, $result);
	}
}

?>