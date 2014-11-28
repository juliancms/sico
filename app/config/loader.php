<?php

$loader = new \Phalcon\Loader();

/**
 * Registro de directorios ubicados en el archivo de configuración
 */
$loader->registerDirs(
	array(
		APP_PATH . $config->application->controllersDir,
		APP_PATH . $config->application->libraryDir,
		APP_PATH . $config->application->modelsDir
	)
)->register();

