<?php
abstract class Table {
	protected $firstColumn;
	protected $secondColumn;
	protected $table;
	
	function __construct($firstColumn, $secondColumn) {
		if (!is_array($firstColumn)) throw new InvalidArgumentException('firstColumn is expected to be an array.');
		if (!is_array($secondColumn)) throw new InvalidArgumentException('secondColumn is expected to be an array.');
		if (count($firstColumn) != count($secondColumn)) {
			throw new LengthException('Arrays were of different sizes.');
		}
		$this->firstColumn = $firstColumn;
		$this->secondColumn = $secondColumn;
		$table = array_combine($firstColumn, $secondColumn);
	}
	
	public function getTable() { return $this->table; }
	
	private function getSlope($lower_first, $higher_first, $lower_second, $higher_second) {
		return ($higher_second - $lower_second) / ($higher_first - $lower_first);
	}
	
	protected function interpolateSecondColumn($lower_first, $higher_first, $lower_second, $higher_second, $valueInFirst) {
		$m = $this->getSlope($lower_first, $higher_first, $lower_second, $higher_second);
		return $m * ($valueInFirst - $lower_first) + $lower_second;
	}
	
	protected function interpolateFirstColumn($lower_first, $higher_first, $lower_second, $higher_second, $valueInSecond) {
		$m = $this->getSlope($lower_first, $higher_first, $lower_second, $higher_second);
		return (1.0/$m) * ($valueInSecond - $lower_second) + $lower_first;
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