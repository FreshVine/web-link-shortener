<?php
	/*
	//
	//	Fresh Vine Link Shortener
	//
	//
	//	Page Purpose:
	//	This includes all of the data processing for the service
	//
	//	Version: 1.0
	//
	*/


	if( defined('FVLS_PROCESS_INCLUDE') )
		return;

	define('FVLS_PROCESS_INCLUDE', true);

	if( !isset( $FLVS_db_link ) || is_null( $FLVS_db_link ) ){
		if( defined('FVLS_DEVELOPER_MODE') && FVLS_DEVELOPER_MODE )
			echo 'FV Link Shortener Error: You must first connect to the database';

		exit();
	}


	//
	// Load up Browscap | used to parse out the user_agent string.
	ini_set("memory_limit","512M");	// Needed to successfully cache the browser cache
	require 'fvls_Browscap.php';
	use phpbrowscap\Browscap;			// The Browscap class is in the phpbrowscap namespace, so import it



	/**
	 * Check if the short tag exists, and if it does - return the desired link
	 **/
	function FVLS_CheckShortTag( $ShortTag ){
		//
		// Check if the short tag is set
		$ShortTag = addslashes( $ShortTag );	// Ensure no shenanigans
		$response = fvls_db_ExecuteQuery( "SELECT * FROM `link`
											WHERE `ShortTag` = '$ShortTag'
												AND `Active` = '1'" );	// Look for this short tag
		if( $response->num_rows !== 1 )
			return false;	// not a valid response


		// Get short tag info
		$row = $response->fetch_assoc();


		//
		// Lets log this click
		$tmp = FVLS_LogClick( $ShortTag );

		return $row['EndLink'];
	}


	/**
	 * Will log the basics about the particular click
	 **/
	function FVLS_LogClick( $ShortTag ){
		$ShortTag = addslashes( stripslashes( $ShortTag ) );
		//
		// Build the query
			//
			// Required Values
			$Insert = array('ShortTag' => $ShortTag);
			$Insert['IPAddress'] = addslashes( $_SERVER['REMOTE_ADDR'] );
			// $Insert['IPAddress'] = '8.8.8.8';	// TEMP for testing
			$Insert['UserAgentKey'] = sha1( $_SERVER['HTTP_USER_AGENT'] );

			//
			// Optional Values
			if( isset( $_SERVER['HTTP_REFERER'] ) ){
				$Insert['RefererURL'] = addslashes( $_SERVER['HTTP_REFERER'] );
				$parsed = parse_url( $Insert['RefererURL'] );

				if( isset( $parsed['host'] ) )
					$Insert['RefererDomain'] = $parsed['host'];
			}

			if( isset( $_REQUEST['l'] ) )
				$Insert['Location'] = addslashes( $_REQUEST['l'] );	// Grab the location aspect
			if( isset( $_REQUEST['p'] ) )
				$Insert['PersonID'] = addslashes( $_REQUEST['p'] );	// Grab the person ID aspect

			if( isset( $_REQUEST['a'] ) )
				$Insert['VariableA'] = addslashes( $_REQUEST['a'] );		// Grab the variable a
			if( isset( $_REQUEST['b'] ) )
				$Insert['VariableB'] = addslashes( $_REQUEST['b'] );		// Grab the variable b
			if( isset( $_REQUEST['c'] ) )
				$Insert['VariableC'] = addslashes( $_REQUEST['c'] );		// Grab the variable c
			// Optional Values
			//

		$sql = "INSERT INTO `link_click` (`". implode( '`,`', array_keys( $Insert ) ) ."`)
					VALUES ('". implode( "','", $Insert ) ."');";
		// Build the query
		//


		//
		// Save the click
		$response = fvls_db_ExecuteQuery( $sql );

		//
		// Check the Response - ensure we added 1 row
		if( fvls_db_AffectedRows() != 1 )
			return false;



		//
		// Check if the User Agent needs to be added
		$sqlUAcheck = "SELECT * FROM `link_ua`
						WHERE `UAkey` = '$Insert[UserAgentKey]';";
		$response = fvls_db_ExecuteQuery( $sqlUAcheck );
		if( $response->num_rows == 0 ){
			$uas = addslashes( $_SERVER['HTTP_USER_AGENT'] );
			$sqlUAadd = "INSERT INTO `link_ua` (`UAkey`, `UAstring`)
							VALUES ('$Insert[UserAgentKey]', '$uas');";
			$response = fvls_db_ExecuteQuery( $sqlUAadd );

			if( fvls_db_AffectedRows() != 1 )
				return false;
		}
		// Check if the User Agent needs to be added
		//

		return true;
	}



	/**
	 * Process through the clicks that have not had their IP and User Agent processed yet
	 **/
	function FVLS_ProcessClicks(){



		//
		// Process the IP addresses
		$sqlCheck = "SELECT * FROM `link_click`
						WHERE `Processed` = '0'
						GROUP BY `IPAddress`;";
		$results = fvls_db_ExecuteQuery( $sqlCheck );
		if( $results->num_rows > 0 ){
			while( $rows = $results->fetch_assoc() ){
				$endpoint = "http://freegeoip.net/json/" . $rows['IPAddress'];	// Possible alternative would be http://ipinfo.io/
				$IPCoded = $Geo = fvls_curl( $endpoint, array(), 'get', array(), true );
				if( !is_array( $IPCoded ) )
					$Geo = json_decode( $IPCoded, true );

				if( $IPCoded === false || !is_array( $Geo ) )
					continue;	// Unable to process

				if( array_key_exists('error', $Geo ) && $Geo['error'] == 400 )
					continue;

				//
				// Prepare the Update
				$Update = array('Processed' => 1 );
				if( isset( $Geo['country_code'] ) && $Geo['country_code'] != '' )
					$Update['Country'] = $Geo['country_code'];
				if( isset( $Geo['region_code'] ) && $Geo['region_code'] != '' )
					$Update['Region'] = $Geo['region_code'];
				if( isset( $Geo['city'] ) && $Geo['city'] != '' )
					$Update['City'] = $Geo['city'];
				if( isset( $Geo['latitude'] ) && $Geo['latitude'] != '' )
					$Update['Latitude'] = $Geo['latitude'];
				if( isset( $Geo['longitude'] ) && $Geo['longitude'] != '' )
					$Update['Longitude'] = $Geo['longitude'];


				//
				// Build out the query
				$SetTemp = array();
				foreach( $Update as $k => $v )
					$SetTemp[] = "`$k` = '" . addslashes( $v ) . "'";
				$SetTemp = implode( ', ', $SetTemp );

				$sqlUpdate  = "UPDATE `link_click`
								SET $SetTemp
								WHERE `IPAddress` = '$rows[IPAddress]'
									AND `Processed` = '0'";

				fvls_db_ExecuteQuery( $sqlUpdate );	// process the query

				if( fvls_db_AffectedRows() == 0 )
					return false;	// Something is not right here
			}
		}
		// Process the IP addresses
		//


		//
		// Process the User Agents
		$sqlCheck = "SELECT * FROM `link_ua`
						WHERE `DeviceType` IS NULL;";	// Lets us update them in batches
		$results = fvls_db_ExecuteQuery( $sqlCheck );
		if( $results->num_rows > 0 ){

			$CachePath = FVLS_APP_PATH . 'includes/';
			if( defined( 'FVLS_CACHE_PATH' ) && !is_null( FVLS_CACHE_PATH ) )
				$CachePath = FVLS_CACHE_PATH;	// prep the cache path


			while( $rows = $results->fetch_assoc() ){
				$bc = new Browscap($CachePath);	// Create a new Browscap object (loads or creates the cache)
		

				// Get information about the current browser's user agent
				$browser = $bc->getBrowser( $rows['UAstring'] );
				$Update = array();

				$Update['DeviceType'] = 'Other';		// 'Desktop', 'Mobile', 'Crawler', 'Other'
				if( $browser->Crawler )
					$Update['DeviceType'] = 'Crawler';	// Web Crawler
				else if( $browser->isMobileDevice == 1 )
					$Update['DeviceType'] = 'Mobile';
				else if( $browser->isMobileDevice == 0 )
					$Update['DeviceType'] = 'Desktop';

				//
				// Learn about the Device
				$Update['DeviceManufactor'] = $browser->Device_Maker;
				$Update['DeviceName'] = $browser->Device_Name;

				//
				// Learn about the operating system
				$Update['OS_Vendor'] = $browser->Platform;
				$version = explode( '.', $browser->Platform_Version, 2 );
				var_dump( $version );
				$Update['OS_VersionMajor'] = $version[0];
				$Update['OS_VersionMinor'] = 0;
				if( array_key_exists( 1, $version ) )
					$Update['OS_VersionMinor'] = $version[1];

				//
				// Learna about the browser being used
				$Update['Browser'] = $browser->Browser;
				$Update['BrowserVersion'] = $browser->Version;


				//
				// Build out the query
				$SetTemp = array();
				foreach( $Update as $k => $v )
					$SetTemp[] = "`$k` = '" . addslashes( $v ) . "'";
				$SetTemp = implode( ', ', $SetTemp );

				$sqlUpdate = "UPDATE `link_ua`
								SET $SetTemp 
								WHERE `UAkey` = '$rows[UAkey]';";	// Lets us update them in batches
				fvls_db_ExecuteQuery( $sqlUpdate );	// process the query

				// if( fvls_db_AffectedRows() == 0 )
				// 	return false;	// Something is not right here
			}
		}
		// Process the User Agents
		//

		return true;
	}
?>