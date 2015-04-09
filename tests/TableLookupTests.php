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
        $this->assertEquals(0.90, $result, $result);
    }
}

?>