<?php
	/*
	//
	//	Fresh Vine Link Shortener
	//
	//
	//	Page Purpose:
	//	Drop this into your app - include it, and make your short links. Also can generate basic reports.
	//
	//	Version: 1.0
	//
	*/


	if( !defined( 'FVLS_APP_PATH' ) )
		define( 'FVLS_APP_PATH', '/' . trim(substr( __FILE__, 0, -23 ), '/' ) . '/' );	// Build out the path to this file

	//
	// Include the required files
	include FVLS_APP_PATH . 'fvls_config.php';	// Ensure this file is in the same folder as this one
	include FVLS_APP_PATH . 'fvls_db.php';		// Ensure this file is in the same folder as this one


	//
	// If you wish to set your own Bad Words list, simple set the variable $BadWords before including this file
	if( !isset( $BadWords ) ){
		// Bad Word list provided by http://codewithdesign.com/2011/05/20/php-array-of-bad-words/
		$BadWords = array('fuck', 'ass', 'shit', 'asshole', 'cunt', 'fag', 'fuk', 'fck', 'fcuk', 'assfuck', 'assfucker', 'fucker', 'motherfucker', 'asscock', 'asshead', 'asslicker', 'asslick', 'assnigger', 'nigger', 'asssucker', 'bastard', 'bitch', 'bitchtits', 'bitches', 'bitch', 'brotherfucker', 'bullshit', 'bumblefuck', 'buttfucka', 'fucka', 'buttfucker', 'buttfucka', 'fagbag', 'fagfucker', 'faggit', 'faggot', 'faggotcock', 'fagtard', 'fatass', 'fuckoff', 'fuckstick', 'fucktard', 'fuckwad', 'fuckwit', 'dick', 'dickfuck', 'dickhead', 'dickjuice', 'dickmilk', 'doochbag', 'douchebag', 'douche', 'dickweed', 'dyke', 'dumbass', 'dumass', 'fuckboy', 'fuckbag', 'gayass', 'gayfuck', 'gaylord', 'gaytard', 'nigga', 'niggers', 'niglet', 'paki', 'piss', 'prick', 'pussy', 'poontang', 'poonany', 'porchmonkey', 'porchmonkey', 'poon', 'queer', 'queerbait', 'queerhole', 'queef', 'renob', 'rimjob', 'ruski', 'sandnigger', 'sandnigger', 'schlong', 'shitass', 'shitbag', 'shitbagger', 'shitbreath', 'chinc', 'carpetmuncher', 'chink', 'choad', 'clitface', 'clusterfuck', 'cockass', 'cockbite', 'cockface', 'skank', 'skeet', 'skullfuck', 'slut', 'slutbag', 'splooge', 'twatlips', 'twat', 'twats', 'twatwaffle', 'vaj', 'vajayjay', 'va-j-j', 'wank', 'wankjob', 'wetback', 'whore', 'whorebag', 'whoreface');
	}


	/**
	 * Returns a brand new short link for the supplied end point
	 **/
	function CreateShortLink( $EndPoint, $CustomerID = NULL, $UserID = NULL, $ForType = NULL, $ForTypeID = NULL, $ForceNew = false ){
		if( !is_null( $CustomerID ) && !is_numeric( $CustomerID ) )
			return false;
		if( !is_null( $UserID ) && !is_numeric( $UserID ) )
			return false;

		//
		// Required Fields
		$Data = array();
		$Data['EndLink'] = addslashes( $EndPoint );
		$parsed = parse_url( $EndPoint );
		if( !isset( $parsed['host'] ) )
			return false;
		$Data['EndLinkDomain'] = $parsed['host'];


		//
		// Optional Fields
		if( !is_null( $CustomerID ) )
			$Data['CustomerId'] = $CustomerID;
		if( !is_null( $UserID ) )
			$Data['UserId'] = $UserID;
		if( !is_null( $ForType ) )
			$Data['ForType'] = addslashes( $ForType );
		if( !is_null( $ForTypeID ) )
			$Data['ForTypeId'] = addslashes( $ForTypeID );


		//
		// Check if it already exists
		global $fvls_TablePrefix;
		$table = $fvls_TablePrefix . 'link';
		if( !$ForceNew ){
			$Where = array();
			foreach( $Data as $k => $v )
				$Where[] = "`$k` = '$v'";
			$WhereIn = implode( ' AND ', $Where );
			$sql = "SELECT * FROM `$table`
						WHERE $WhereIn
					ORDER BY `Created` DESC";
			$result = fvls_db_ExecuteQuery( $sql );	// process the query

			if( $result->num_rows != 0 ){
				$row = $result->fetch_assoc();
				return rtrim( FVLS_SITE_DOMAIN . FVLS_SITE_PATH, '/' ) . '/' . $row['ShortTag'];	// Return the most recent version
			}
		}



		//
		// Create the new link
		$ShortTag = GenerateShortTag();
		$Data['ShortTag'] = $ShortTag;
		$Vals[0] = '`' . implode('`, `', array_keys( $Data ) ) . '`';
		$Vals[1] = '\'' . implode('\', \'', $Data ) . '\'';
		$sql = "INSERT INTO `$table` ($Vals[0]) VALUES ( $Vals[1] );";
		fvls_db_ExecuteQuery( $sql );	// process the query

		return rtrim( FVLS_SITE_DOMAIN . FVLS_SITE_PATH, '/' ) . '/' . $ShortTag;
	}


	/**
	 * Remove short link by Short Tag
	 **/
	function RemoveShortLink( $ShortTag, $CustomerID = NULL, $UserID = NULL ){
		$ShortTag = addslashes( $ShortTag );	// Sanitize it

		//
		// Prepare the query
		$Where = NULL;
		if( !is_null( $CustomerID ) && is_numeric( $CustomerID ) )
			$Where .= " AND `CustomerId` = '$CustomerID'";
		if( !is_null( $UserID ) && is_numeric( $UserID ) )
			$Where .= " AND `UserId` = '$UserID'";


		//
		// Run the query
		global $fvls_TablePrefix;
		$TableName = $fvls_TablePrefix . 'link';
		$sql = "UPDATE `$TableName`
				SET `Active` = '0'
				WHERE `ShortTag` = '$ShortTag'
					$Where
					AND `Active` = '1';";
		fvls_db_ExecuteQuery( $sql );	// process the query


		//
		// Check the Response
		if( fvls_db_AffectedRows() == 0 )
			return false;

		return true;
	}


	/**
	 * Returns a brand new short tag
	 **/
	function GenerateShortTag(){
		global $fvls_TablePrefix;
		$TableName = $fvls_TablePrefix . 'link_conf';
		$sql = "SELECT `Datum` FROM `$TableName` WHERE `Named` = 'link_id';";
		$result = fvls_db_ExecuteQuery( $sql );
		$row = $result->fetch_assoc();
		$i = $row['Datum'];
		++$i;	// Increment for the next attempt


		//
		// Ensure we get a valid short tag
		$run = true;
		while( $run == true ){

			//
			// Convert an numeric value into a short code
		    $base='0aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ123456789-';
			$len = strlen( $base );
			$num = $i + pow($len, 2) + 63;	// Converst 1 into the lowest string with a length of 3
			$r = $num % $len;
			$ShortTag = $base[$r];
			$q = floor($num/$len);
			while( $q ){
				$r = $q % $len;
				$q =floor($q/$len);
				$ShortTag = $base[$r].$ShortTag;
		    }
			// Convert an numeric value into a short code
			//


			if( strlen( $ShortTag ) < 3 ){
				++$i;
				continue;
			}


			//
			// Ensure we don't offsend someone
			if( !IsShortTagAllowed( $ShortTag ) ){
				++$i;
				continue;
			}


			//
			// Ensure we haven't used it before
			global $fvls_TablePrefix;
			$TableName = $fvls_TablePrefix . 'link';
			$sql = "SELECT `ShortTag` FROM `$TableName` WHERE `ShortTag` = '$ShortTag';";
			$result = fvls_db_ExecuteQuery( $sql );	// process the query
			if( $result->num_rows == 1 ){
				++$i;	// Increment and try again for a valid value
				continue;
			}

			$run = false;
			break;
		}
		// Ensure we get a valid short tag
		//


		//
		// Cache the current link_id increment value
		global $fvls_TablePrefix;
		$TableName = $fvls_TablePrefix . 'link_conf';
		$sql = "UPDATE `$TableName` SET `Datum` = '$i' WHERE `Named` = 'link_id';";
		fvls_db_ExecuteQuery( $sql );


		//
		// Return the brand new short tag
		return $ShortTag;
	}


	/**
	 * Ensure the term is not excluded
	 **/
	function IsShortTagAllowed( $ShortTag ){
		if( trim( $ShortTag, '-' ) != $ShortTag || substr_count( $ShortTag ,'--') > 0 )
			return false;	// Cannot be all dashes, start with a dash, or end with a dash

		//
		// Build out all available versions of this short tag
		$Leet = array(0 => array('o'), 1 => array('l', 'i'), 2 => array('r','z'), 3 => array('b','e'), 4 => array('a','h'), 5 => array('s'), 6 => array('g','b'), 7 => array('t'), 8 => array('b'), 9 => array('g'), 12 => array('r'), 13 => array('b'));
		$Tags[] = $ShortTag = strtolower( $ShortTag );	// Case doesn't matter here
		if( preg_replace("/[^0-9]/", "", $ShortTag ) !== $ShortTag ){	// I'm sure this can be optimized
			foreach( $Leet as $k => $vals ){
				foreach( $Tags as $thisTag ){
					if( !preg_match('/['.$k.']/', $thisTag) )
						continue;

					foreach( $vals as $v ){
						$tmp = str_replace( $k, $v, $thisTag );
						if( !in_array( $tmp, $Tags ) )
							$Tags[] = $tmp;
					}
				}
			}
		}
		$Tags = array_unique( $Tags );


		//
		// Ensure we don't use any bad words in our short tags
		global $BadWords;
		$BadWords = array_merge( array('404','landing-page'), $BadWords );	// Ensure our dedicated terms are never used - will screw up errors and landing pages if removed
		foreach( $Tags as $thisTag ){
			// Lets take it at face value
			if( in_array( $thisTag, $BadWords ) )
				return false;

			foreach( $BadWords as $a ){
				if( stripos($thisTag, strtolower( $a ) ) !== false )
					return false;
			}
		}


		//
		// Looks like a winner!
		return true;
	}
?>