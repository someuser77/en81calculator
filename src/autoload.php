<?php
//spl_autoload_extensions('.php');
spl_autoload_register(function ($class) {
    include_once 'src/Table.php';
	include_once 'src/TableOne.php';
	include_once 'src/TableTwo.php';
});

?>