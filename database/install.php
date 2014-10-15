<?php
	/*
	//
	//	Fresh Vine Link Shortener
	//
	//
	//	Page Purpose:
	//	Creates all the database structure and schema needed for the link shortener to work
	//
	*/


	if( defined('FVLS_INSTALL_INCLUDE') )
		return;

	define('FVLS_INSTALL_INCLUDE', true);
	if( !isset( $FLVS_db_link ) || is_null( $FLVS_db_link ) ){
		if( defined('FVLS_DEVELOPER_MODE') && FVLS_DEVELOPER_MODE )
			echo 'FV Link Shortener Error: You must first connect to the database';

		exit();
	}


	$Tables = array();
	$sql = "SHOW TABLES LIKE '".$fvls_TablePrefix."link%'";
	$results = fvls_db_ExecuteQuery( $sql );

	if( $results->num_rows > 0 ){
		while( $rows = $results->fetch_array() ){
			$Tables[] = $rows[0];
		}
	}


	/**
	 * Create the `link` table
	 **/
	$TableName = $fvls_TablePrefix . 'link';
	if( !in_array( $TableName, $Tables ) ){
		$sql = "CREATE TABLE `$TableName` (
				  `ID` int(11) NOT NULL AUTO_INCREMENT,
				  `ShortTag` varchar(12) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				  `EndLink` varchar(1024) NOT NULL,
				  `EndLinkDomain` varchar(1024) NOT NULL,
				  `CustomerId` int(11) DEFAULT NULL,
				  `UserId` int(11) DEFAULT NULL,
				  `ForType` varchar(255) DEFAULT NULL COMMENT 'Specifiy what type of element created it - ex: event, email',
				  `ForTypeId` int(11) DEFAULT NULL COMMENT 'Specify the id of the type element that created it',
				  `Active` tinyint(1) NOT NULL DEFAULT '1',
				  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `Removed` timestamp NULL DEFAULT NULL,
				  PRIMARY KEY (`ID`),
				  UNIQUE KEY `ShortTag` (`ShortTag`),
				  KEY `EndLink` (`EndLink`(255)),
				  KEY `EndLinkDomain` (`EndLinkDomain`(255))
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$results = fvls_db_ExecuteQuery( $sql );
	}


	/**
	 * Create the `link_click` table
	 **/
	$TableName = $fvls_TablePrefix . 'link_click';
	if( !in_array( $TableName, $Tables ) ){
		$sql = "CREATE TABLE `$TableName` (
				  `ID` int(20) NOT NULL AUTO_INCREMENT,
				  `ShortTag` varchar(12) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				  `ClickTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `Location` varchar(255) DEFAULT NULL,
				  `PersonID` int(30) DEFAULT NULL,
				  `VariableA` varchar(128) DEFAULT NULL,
				  `VariableB` varchar(128) DEFAULT NULL,
				  `VariableC` varchar(128) DEFAULT NULL,
				  `RefererURL` varchar(2048) NOT NULL DEFAULT 'direct',
				  `IPAddress` varchar(512) NOT NULL,
				  `UserAgentKey` varchar(60) NOT NULL COMMENT 'SHA1 of their user-agent string',
				  `RefererDomain` varchar(255) DEFAULT NULL,
				  `Processed` tinyint(1) NOT NULL DEFAULT '0',
				  `Country` varchar(2) DEFAULT NULL,
				  `Region` varchar(2) DEFAULT NULL,
				  `City` varchar(255) DEFAULT NULL,
				  `Latitude` decimal(20,16) DEFAULT NULL,
				  `Longitude` decimal(20,16) DEFAULT NULL,
				  PRIMARY KEY (`ID`),
				  KEY `ShortTag` (`ShortTag`),
				  KEY `UserAgentKey` (`UserAgentKey`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		$results = fvls_db_ExecuteQuery( $sql );
	}


	/**
	 * Create the `link_conf` table
	 **/
	$TableName = $fvls_TablePrefix . 'link_conf';
	if( !in_array( $TableName, $Tables ) ){
		$sql = "CREATE TABLE `$TableName` (
				  `Named` varchar(32) NOT NULL,
				  `Datum` varchar(32) NOT NULL,
				  `Created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  PRIMARY KEY (`Named`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$results = fvls_db_ExecuteQuery( $sql );

		// Install the starting values
		$sql = "INSERT INTO `$TableName` (`Named`, `Datum`)
				VALUES ('db_version', '1'),
					('link_id','0');";
		$results = fvls_db_ExecuteQuery( $sql );
	}



	/**
	 * Create the `link_ua` table
	 **/
	$TableName = $fvls_TablePrefix . 'link_ua';
	if( !in_array( $TableName, $Tables ) ){
		$sql = "CREATE TABLE `$TableName` (
				  `UAkey` varchar(60) NOT NULL COMMENT 'the SHA1 version of the user-agent string',
				  `UAstring` varchar(512) NOT NULL,
				  `DeviceType` set('Desktop','Mobile','Crawler','Other') DEFAULT NULL,
				  `OS_Vendor` varchar(128) DEFAULT NULL,
				  `OS_VersionMajor` varchar(11) DEFAULT NULL,
				  `OS_VersionMinor` varchar(11) DEFAULT NULL,
				  `DeviceManufactor` varchar(128) DEFAULT NULL,
				  `DeviceName` varchar(128) DEFAULT NULL,
				  `Browser` varchar(128) DEFAULT NULL,
				  `BrowserVersion` varchar(64) DEFAULT NULL,
				  PRIMARY KEY (`UAkey`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$results = fvls_db_ExecuteQuery( $sql );
	}

?>