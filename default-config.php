<?php
	// 
	//	This is the configuration page for the Fresh Vine URL Shortener
	//


	// Lets not do this twice - shall we?
	if( defined('FVUS_CONFIG_LOADED') )
		return;	// Thanks for coming!

	define('FVUS_CONFIG_LOADED', true); 

	//
	// General Details
	/**
	 * The name of your site or service (used in default pages)
	 **/
	if( !defined( 'FVUS_SERVICE_NAME' ) )
		define('FVUS_SERVICE_NAME', '');

	/**
	 * URL path to your site that promots your service - ex: https://freshvine.co/
	 **/
	if( !defined( 'FVUS_SERVICE_URL' ) )
		define('FVUS_SERVICE_URL', '');

	/**
	 * The year to start your copyright claim - YYYY - ex: 2014
	 **/
	if( !defined( 'FVUS_COPYRIGHT_START' ) )
		define('FVUS_COPYRIGHT_START', date(Y));
	// General Details
	//


	
	//
	// MySQL Database Configuration
	/**
	 * The hostname for how to connect to your database
	 **/
	if( !defined( 'FVUS_MYSQL_HOST' ) )
		define('FVUS_MYSQL_HOST', 'localhost');

	/**
	 *  The username to connect to the database with full access
	 **/
	if( !defined( 'FVUS_MYSQL_USER' ) )
		define('FVUS_MYSQL_USER', 'XXXXXXXXXX');

	/**
	 * The password for the database user
	 **/
	if( !defined( 'FVUS_MYSQL_PASS' ) )
		define('FVUS_MYSQL_PASS', 'XXXXXXXXXX');

	/**
	 * The name of the database that you are accessing
	 **/
	if( !defined( 'FVUS_MYSQL_DATABASE' ) )
		define('FVUS_MYSQL_DATABASE', 'XXXXXXXXXX');

	/**
	 * The database table prefix if one exists. If not, then leave blank
	 **/
	if( !defined( 'FVUS_MYSQL_TABLE_PREFIX' ) )
		define('FVUS_MYSQL_TABLE_PREFIX', '');
	// MySQL Database Configuration
	//


	//
	// Structure and Path stuff
	/**
	 * The path to the folder (leave NULL if it is not in a folder). EX: 'shortened/links'
	 **/
	if( !defined( 'FVUS_SITE_PATH' ) )
		define('FVUS_SITE_PATH', NULL);
	// Structure and Path stuff
	//
?>