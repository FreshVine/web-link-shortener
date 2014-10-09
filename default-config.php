<?php
	// 
	//	This is the configuration page for the Fresh Vine URL Shortener
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
?>