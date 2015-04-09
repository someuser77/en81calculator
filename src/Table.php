<?php
abstract class Table {
	protected $firstColumn;
	protected $secondColumn;
	
	function __construct($firstColumn, $secondColumn) {
		if (!is_array($firstColumn)) throw new InvalidArgumentException('firstColumn is expected to be an array.');
		if (!is_array($secondColumn)) throw new InvalidArgumentException('secondColumn is expected to be an array.');
		if (count($firstColumn) != count($secondColumn)) {
			throw new LengthException('Arrays were of different sizes.');
		}
		$this->firstColumn = $firstColumn;
		$this->secondColumn = $secondColumn;
	}
	
	protected function findInFirstColumn($elem) {
		return $this->find($this->firstColumn, $elem);
	}
	
	protected function findInSecondColumn($elem) {
		return $this->find($this->secondColumn, $elem);
	}
	
	private function find($array, $elem) {
		// from http://en.wikibooks.org/wiki/Algorithm_Implementation/Search/Binary_search
		$top = count($array) - 1;
		$bot = 0;
		$lo = $bot;
		$up = $top;
		
		if (count($array) == 0) return -1;
		
		while($top >= $bot)
		{
			$p = floor(($top + $bot) / 2);
			
			if ($array[$p] <= $elem && $p > $lo) $lo = $p;
			if ($array[$p] > $elem && $p < $up) $up = $p;
			
			if ($array[$p] < $elem) { $bot = $p + 1; }
			elseif ($array[$p] > $elem) { $top = $p - 1; }
			else return $p;
		}
		//echo $lo;
		//echo $up;
		if ($lo == $up) return -1;
		else return $lo;
	}
}
?>