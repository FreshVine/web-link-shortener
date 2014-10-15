<?php
	/*
	//
	//	Fresh Vine Link Shortener
	//
	//
	//	Page Purpose:
	//	Builds and manages the connections to the database.
	//
	//	Version: 1.0
	//
	*/


	if( defined('FVLS_DB_INCLUDE') )
		return;

	define('FVLS_DB_INCLUDE', true);


	$FLVS_db_link = NULL;
	$DatabaseVersion = '1';
	fvls_db_connect();

	$fvls_TablePrefix = '';
	if( defined('FVLS_MYSQL_TABLE_PREFIX') && FVLS_MYSQL_TABLE_PREFIX != '' )
		$fvls_TablePrefix = rtrim( FVLS_MYSQL_TABLE_PREFIX, '_' ) . '_';

	//
	// Check if we need to install or update
	if( is_file( FVLS_APP_PATH . 'database/install.php') ) 
		include FVLS_APP_PATH . 'database/install.php';


	/**
	 * Return the number of rows affected by the most recent query
	 **/
	function fvls_db_AffectedRows(){
		global $FLVS_db_link;

		return $FLVS_db_link->affected_rows;
	}


	/**
	 * Establish a connection ot the database
	 **/
	function fvls_db_connect(){
		// function will open the database connection
		if( !defined('FVLS_MYSQL_HOST') || !defined('FVLS_MYSQL_USER') || !defined('FVLS_MYSQL_PASS') || !defined('FVLS_MYSQL_DATABASE') )
			return false;

		$startarray = explode( ' ', microtime() );
		$FLVS_db_Timer  = (double) $startarray[1] + (double) $startarray[0];

		//
		// Connect to the database
		global $FLVS_db_link;
		if( $FLVS_db_link instanceof MySQLi )
			return true;	// Already have a connction established - and a valid link resource

		$FLVS_db_link = new mysqli( FVLS_MYSQL_HOST, FVLS_MYSQL_USER, FVLS_MYSQL_PASS, FVLS_MYSQL_DATABASE);	// Lets get connection
		if ($FLVS_db_link->connect_errno) {	// Checking for an error
			if( defined('FVLS_DEVELOPER_MODE') && FVLS_DEVELOPER_MODE )
			    echo "FV Link Shortener Error: Failed to connect to MySQL - (" . $FLVS_db_link->connect_errno . ") " . $FLVS_db_link->connect_error;
			return false;
		}


		//
		// Set the timezone
		if( defined( 'FVLS_TIMEZONE' ) && trim( FVLS_TIMEZONE ) != '' ){
			$FLVS_db_link->query("SET SESSION time_zone = '" . FVLS_TIMEZONE . "';");
			if( strtolower( FVLS_TIMEZONE ) != 'system' )
				date_default_timezone_set(FVLS_TIMEZONE);
		}

		return true;
	}


	/**
	 * Close the database connection
	 **/
	function fvls_db_close(){
		global $FLVS_db_link;
		$return = $FLVS_db_link->close();	// Close the connection and store success status (boolean response)
		if( $return )
			$FLVS_db_link = NULL;	// Closing the connectino doesn't change the variable resource type - so we need to manually alter it

		return $return;	// Kick back out the boolean status
	}


	/**
	 * Run a query through the database. There is no sanitization that happens here - assumed you already took care of it in your query prep.
	 **/
	function fvls_db_ExecuteQuery($sql){
		global $FLVS_db_link;
		if( !$FLVS_db_link instanceof MySQLi )
			return false;

		if ( !($response = $FLVS_db_link->query( $sql ) ) ){
			if( defined('FVLS_DEVELOPER_MODE') && FVLS_DEVELOPER_MODE )
			    echo "FV Link Shortener Error: mySQL Query Error - $FLVS_db_link->error\n";
			return false;
		}

		return $response;
	}
?>