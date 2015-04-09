<?php
class TableLookupTests extends PHPUnit_Framework_TestCase {
	
	var $tableOne;
	
	function __construct() {
		$this->tableOne = new TableOne();
	}
	
	public function testExactItemLookup()
    {
		$result = $this->tableOne->findArea(100);
		
        // Assert
        $this->assertEquals(0.37, $result, $result);
    }
}

?>