<?php
	// 
	//	This is the configuration page for the Fresh Vine URL Shortener
	//


	// Lets not do this twice - shall we?
	if( defined('FVLS_CONFIG_LOADED') )
		return;	// Thanks for coming!

	define('FVLS_CONFIG_LOADED', true); 

	//
	// General Details
	/**
	 * The name of your site or service (used in default pages)
	 **/
	if( !defined( 'FVLS_SERVICE_NAME' ) )
		define('FVLS_SERVICE_NAME', '');

	/**
	 * URL path to your site that promots your service - ex: https://freshvine.co/
	 **/
	if( !defined( 'FVLS_SERVICE_URL' ) )
		define('FVLS_SERVICE_URL', '');

	/**
	 * The year to start your copyright claim - YYYY - ex: 2014
	 **/
	if( !defined( 'FVLS_COPYRIGHT_START' ) )
		define('FVLS_COPYRIGHT_START', date(Y));
	// General Details
	//


	
	//
	// MySQL Database Configuration
	/**
	 * The hostname for how to connect to your database
	 **/
	if( !defined( 'FVLS_MYSQL_HOST' ) )
		define('FVLS_MYSQL_HOST', 'localhost');

	/**
	 *  The username to connect to the database with full access
	 **/
	if( !defined( 'FVLS_MYSQL_USER' ) )
		define('FVLS_MYSQL_USER', 'XXXXXXXXXX');

	/**
	 * The password for the database user
	 **/
	if( !defined( 'FVLS_MYSQL_PASS' ) )
		define('FVLS_MYSQL_PASS', 'XXXXXXXXXX');

	/**
	 * The name of the database that you are accessing
	 **/
	if( !defined( 'FVLS_MYSQL_DATABASE' ) )
		define('FVLS_MYSQL_DATABASE', 'XXXXXXXXXX');

	/**
	 * The database table prefix if one exists. If not, then leave blank
	 **/
	if( !defined( 'FVLS_MYSQL_TABLE_PREFIX' ) )
		define('FVLS_MYSQL_TABLE_PREFIX', '');
	// MySQL Database Configuration
	//


	//
	// Structure and Path stuff
	/**
	 * The path to the folder (leave NULL if it is not in a folder). EX: 'shortened/links'
	 **/
	if( !defined( 'FVLS_SITE_PATH' ) )
		define('FVLS_SITE_PATH', NULL);
	// Structure and Path stuff
	//
?>