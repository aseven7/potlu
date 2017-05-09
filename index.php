<?php
    /*******************************
	 * Potlu Framework 1.0
	 * Antony Tanuputra - 2017
	 *
	 ******************************/ 

	session_start();
	define('APP_PATH', dirname(__FILE__) . '\\app\\');
	
	/* setup application */
	require 'framework/application.php';


	/* setup dependency */
	loadLibrary();
	loadHelper();
	loadStarter();
	loadMenu();

	/* retrive configuration */
	$appConfig = include APP_PATH . '\\config\\app.php';
	
	// setup base_url
	Flight::set("flight.base_url", $appConfig['baseurl']);

	// setup database
	ORM::configure($appConfig['dsn']);
	ORM::configure('username', $appConfig['username']);
	ORM::configure('password', $appConfig['password']);

	/* setup route */
	require APP_PATH . '\\routes.php';
	
	/* start */
	Flight::start();