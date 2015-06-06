<?php
include_once('src/autoload.php');

class InputField
{
	var $decimals;
	var $readOnly;
	var $displayName;
	
	function __construct($displayName, $decimals, $readOnly = false) {
		$this->decimals = $decimals;
		$this->readOnly = $readOnly;
		$this->displayName = $displayName;
	}
	
	function getDecimals() { return $this->decimals; }
	function getReadOnly() { return $this->readOnly; }
	function getDisplayName() { return $this->displayName; }
		
}

function sanitizeInput($input){
	$input = trim($input);
	$input = stripslashes($input);
	$input = htmlspecialchars($input);
	if (!is_numeric($input)) throw new InvalidArgumentException();
	return floatval($input);	
}

function prettyPrintDecimals($num, $decimals) {
	//$numDecimals = (intval($num) == $num) ? 1 : 3;
	return number_format($num, $decimals, ".", "");
}

function get($name) {
	
	if (!isset($_GET[$name])) {
		return NULL;
	}
	
	return sanitizeInput($_GET[$name]);
	
}

	if ($_SERVER["REQUEST_METHOD"] != "GET") {
		die("Server only supports GET.");
	}

	$inputFields = array(
		'X' => new InputField('X', 3), 
		'Y' => new InputField('Y', 3), 
		'F' => new InputField('F', 3), 
		'D' => new InputField('D', 3), 
		'f' => new InputField('f', 3), 
		'd' => new InputField('d', 3), 
		'S' => new InputField('S', 3), 
		'minL' => new InputField('minL', 0), 
		'LI' => new InputField('L.I.', 0), 
		'maxN' => new InputField('maxN', 0), 
		'Smin' => new InputField('Smin', 3, true), 
		'Smax' => new InputField('Smax', 3, true)
	);
  
  foreach ($inputFields as $field => $properties) {
	  $$field = get($field);
  }

  $validInput = true;
  
  $en81 = new EN81Calculator();
  
  $basicAreaParametersAreSet = isset($X) && isset($Y) && isset($F) && isset($D);
  $areaIsSet = isset($S);
  
  if ($basicAreaParametersAreSet || $areaIsSet) {
	  if ($basicAreaParametersAreSet) {
		  $f = get("f");
		  $d = get("d");
		  if (!isset($f)) $f = 0.0;
		  if (!isset($d)) $d = 0.0;
		  
		  $S = $X * $Y + $F * $D + $f * $d;
	  }
	
		$LI = $en81->getInterpolatedLoadByArea($S);
	  
	  $minL = $en81->getMinLoadByArea($S);
	  echo $S;
	  echo '<br />';
	  echo $minL;
	  $maxN = $en81->getPassengersByArea($S);
	  
	  $L = $en81->getInterpolatedLoadByArea($S);
	  
  } else if (isset($minL)) {
	  $maxN = $en81->getPassengersByLoad($minL);
  } else if (isset($maxN)) {
	  $minL = $en81->getLoadByPassengers($maxN);
  } else {
	  $validInput = false;
  }
  
  if ($validInput) {
	$Smax = $en81->getMaxArea($minL);
	$Smin = $en81->getMinArea($maxN);
	if ($minL < $maxN * 75) {
		$error = "minL < maxN * 75";
	}
  } else {
	  $error = "No valid input was given.";
  }
  


?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
function removeEmptyInputFields() {
	var form = document.getElementById('input-form');
	var inputs = form.getElementsByTagName('input');
    var input, i;

    for(i = inputs.length - 1; i >= 0 ; i--) {
		input = inputs[i];
		if (input.value.length == 0) {
			input.parentNode.removeChild(input);
		}
    }
}
</script>
</head>
<body>
<div id="error"><?php if (isset($error)) echo $error; ?></div>
<form id="input-form" action="index.php" method="get" onsubmit="removeEmptyInputFields()">
<?php

foreach ($inputFields as $field => $properties) {
	echo "<p>".$properties->getDisplayName().": <input type=\"text\" name=\"".$field."\"";
	if (isset($$field)) echo " value=\"".prettyPrintDecimals($$field, $properties->getDecimals())."\"";
	if ($properties->getReadOnly()) echo " readonly";
	echo "/></p>\n";
}

?>

 <p><input type="submit" /></p>
</form>
</body>
</html>