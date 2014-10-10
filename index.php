<?php

	//
	// Bring in the config file
	if( !is_file( 'config.php' ) )
		exit('You need to copy the default-config.php file to config.php and fill it out!');

	include('config.php');
	include('functions.php');
	$IndexOrder = array('index.php', 'index.htm', 'index.html');


	//
	// Lets get some basic things out of the way
	$BaseURL = 'http://';
	if( isset( $_SERVER['SERVER_PORT'] ) && $_SERVER['SERVER_PORT'] == 443 ){
		$BaseURL = 'https://';
	}

	$BaseURL .= $_SERVER['HTTP_HOST'];
	$Requested = ltrim( $_SERVER['REQUEST_URI'], '/' );
	
	if( !is_null( FVUS_SITE_PATH ) ){
		$BaseURL = rtrim( $BaseURL, '/' ) . '/' . trim( FVUS_SITE_PATH, '/' ) . '/';	// Lets get our base

		if( stripos( $Requested, '/' ) !== false ){
			$len = strlen( trim( FVUS_SITE_PATH, '/' ) . '/' );
			if( $len != strlen( $Requested ) )
				$Requested = substr( $Requested, strpos( stripos( $Requested, trim( FVUS_SITE_PATH, '/' ) . '/' ) )  + $len );
			else
				$Requested = NULL;
		}
	}
	// Lets get some basic things out of the way
	//


	//
	// Check if we're looking for the landing page
	if( is_null( $Requested ) || stripos( $Requested, 'landing-page' ) !== false ){
		$CustomLandingPage = $IndexFile = null;
		// Preference is for their version of the landing page
		if( is_dir('landing-page') ){
			foreach( $IndexOrder as $try ){
				if( !is_file( 'landing-page/' . $try ) ){ continue; }

				$IndexFile = $try;
				$CustomLandingPage = true;
				break;
			}
		}

		if( !$CustomLandingPage && is_dir('default-landing-page') ){
			foreach( $IndexOrder as $try ){
				if( !is_file( 'default-landing-page/' . $try ) ){ continue; }

				$IndexFile = $try;
				$CustomLandingPage = false;
				break;
			}
		}


		//
		// Are we loading the 
		if( $CustomLandingPage )
			$path = 'landing-page/';
		else if( !$CustomLandingPage )
			$path = 'default-landing-page/';


		// Throw an error since there is no content
		if( !is_null( $Requested ) && !is_file( $path  . $Requested ) ){
			header("HTTP/1.0 404 Not Found");
			exit();
		}


		if( is_bool( $CustomLandingPage ) ){
			if( is_null( $Requested ) ){
				$Requested = $IndexFile;	// Check if this is the base landing page
				SetContentType( 'its-the-index.html' );	// Found the file
			}else{
				SetContentType( $Requested );	// Found the file
			}

			$FilePath = urldecode( $path  . $Requested  );
			// $handle = @fopen( $FilePath, "rb");
			ob_start();
			include( $FilePath );
			// @fpassthru($handle);
			header('Content-Length: '.ob_get_length(), true);
			ob_end_flush();

			exit();	// stop progression
		}


		exit('There is no configured landing page');
	}
	// Check if we're looking for the landing page
	//


	
//
//
// Process the Short Link
// Process the Short Link
//
//




	//
	// Looks like we didn't find anything - time to load up an error
	$Custom404Page = $IndexFile = null;
	// Preference is for their version of the landing page
	if( is_dir('404') ){
		foreach( $IndexOrder as $try ){
			if( !is_file( '404/' . $try ) ){ continue; }

			$IndexFile = $try;
			$Custom404Page = true;
			break;
		}
	}

	if( !$Custom404Page && is_dir('default-404') ){
		foreach( $IndexOrder as $try ){
			if( !is_file( 'default-404/' . $try ) ){ continue; }

			$IndexFile = $try;
			$Custom404Page = false;
			break;
		}
	}


	//
	// Check if we were trying to load the error file
	if( strpos( $Requested, '.' ) === false )
		$Requested = null;



	//
	// Are we loading the 
	if( $Custom404Page )
		$path = '404/';
	else if( !$Custom404Page )
		$path = 'default-404/';


	// Throw an error since there is no content
	if( !is_null( $Requested ) && !is_file( $path  . $Requested ) ){
		header("HTTP/1.0 404 Not Found");
		exit();
	}


	if( is_bool( $Custom404Page ) ){
		if( is_null( $Requested ) ){
			$Requested = $IndexFile;	// Check if this is the base landing page
			SetContentType( 'its-the-index.html' );	// Found the file
		}else{
			SetContentType( $Requested );	// Found the file
		}

		$FilePath = urldecode( $path  . $Requested  );
		// $handle = @fopen( $FilePath, "rb");
		ob_start();
		include( $FilePath );
		// @fpassthru($handle);
		header('Content-Length: '.ob_get_length(), true);
		ob_end_flush();

		exit();	// stop progression
	}


	exit('There is no error page');
	// Looks like we didn't find anything - time to load up an error
	//
	
?>