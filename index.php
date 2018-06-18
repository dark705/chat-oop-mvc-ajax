<?php
spl_autoload_register(function ($class) {
	$file = $class . '.php';
	if (file_exists($file))
		include_once $class . '.php';
});

$obj = new cMain;
$obj->request();
?>