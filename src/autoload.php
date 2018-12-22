<?php
/**
 * Date: 10.08.2018
 */
spl_autoload_register(function($class) {

	$class = preg_replace('~[^a-zA-Z0-9\\\\]~mu', '', $class);

	$pathArr = explode('\\', $class);
	$path = __DIR__ . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $pathArr) . '.php';

	if (file_exists($path)) {
		require_once $path;
	}
}, false);
