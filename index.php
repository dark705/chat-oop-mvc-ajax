<?php
spl_autoload_register(function ($class) {
	$type = substr($class, 0, 1);
	switch($type) {
		case "v":
			$file = 'templates/' . $class . '.php';
			break;
		case "m":
			$file = 'lib/' . $class . '.php';
			break;
		case "c":
			$file = 'controllers/' . $class . '.php';
			break;
		default:
			$file = $class . '.php';
	}
	if (file_exists($file))
		include_once $file;
});

$obj = new cMain;
$obj->request();
?>