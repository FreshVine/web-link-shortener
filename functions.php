<?php
	/*
	//
	//	Fresh Vine Link Shortener
	//
	//
	//	Page Purpose:
	//	General Functions for the application to work.
	//
	*/


	if( defined('FVLS_FUNCTIONS_INCLUDE') )
		return;

	define('FVLS_FUNCTIONS_INCLUDE', true);


	/**
	 * Get or post data to another server.
	 **/
	function fvls_curl( $EndPoint, $Data = array(), $Method = 'post', $Headers = array() ){
		// Ensure that the Endpoint is a URL, $Data is an array, and Method is either GET, POST, or DELETE
		$Method = strtolower( $Method );
		$parsedURL = parse_url( $EndPoint );
		if( empty( $parsedURL ) || !is_array( $Data ) || !in_array( $Method, array( 'get','post','delete' ) ) )
			return false;	// Something was not correctly set

		//
		// Convert Data to the usable format (easy to convert into a string)
		$Prepped = array();
		foreach( $Data as $key => $var ){
			if( !is_array( $var ) )
				$Prepped[] = $key .'='. $var;
			else
				$Prepped[] = $key .'[]='. $var;
		}
		// Convert Data to the usable format (easy to convert into a string)
		//


		//
		// Create the new curl instance
		$cHANDLE = curl_init();
		curl_setopt($cHANDLE, CURLOPT_SSL_VERIFYPEER, false);	// Skip the SSL cerficiate validation
		curl_setopt($cHANDLE, CURLOPT_RETURNTRANSFER, true);	// Outputs the response to the variable instead of printing it


		//
		// Add any custom headers required
		$DefaultHeaders['Accept-language'] = 'en';
		$DefaultHeaders['Content-type'] = 'application/x-www-form-urlencoded';
		$DefaultHeaders['User-Agent'] = 'Fresh Vine';
		$Headers = array_merge( $DefaultHeaders, $Headers );	// Ensure that defaults can be over written
		if( is_array( $Headers ) && !empty( $Headers ) ){
			$cHEADER = array();
			foreach( $Headers as $key => $var )
				$cHEADER[] = $key.': '. $var;

			if( !empty( $cHEADER ) )
				curl_setopt($cHANDLE, CURLOPT_HTTPHEADER, $cHEADER);
		}
		// Add any custom headers required
		//


		//
		// Prepare the GET request
		if( $Method == 'get' ){
			curl_setopt($cHANDLE, CURLOPT_HTTPGET, true);
			curl_setopt($cHANDLE, CURLOPT_CUSTOMREQUEST, 'GET');

			if( !empty( $Prepped ) ){
				if( strpos( $EndPoint, '?' ) === false )
					$EndPoint .= '?';
				else
					$EndPoint .= '&';

				$EndPoint .= implode( '$', $Prepped );
			}
		}

		//
		// Prepare the POST request
		if( $Method == 'post' ){
			curl_setopt( $cHANDLE, CURLOPT_POST, 1);

			if( !empty( $Prepped ) )
				curl_setopt( $cHANDLE, CURLOPT_POSTFIELDS, implode( '&', $Prepped ) );
		}

		//
		// Set the end point we're calling and execute
		curl_setopt( $cHANDLE, CURLOPT_URL, $EndPoint );			// Set the Endpoint for the conection
		$response = curl_exec( $cHANDLE );
		$http_status = curl_getinfo($cHANDLE, CURLINFO_HTTP_CODE);
		if( $http_status >= 400 )
			return false;	// Not a valid response

		return $response;
	}

	/**
	 * Set the correct MIME type for the file they are accessing
	 **/
	function fvls_SetContentType( $Filename ){
		$mime_types = array("323" => "text/h323",
							"acx" => "application/internet-property-stream",
							"ai" => "application/postscript",
							"aif" => "audio/x-aiff",
							"aifc" => "audio/x-aiff",
							"aiff" => "audio/x-aiff",
							"asf" => "video/x-ms-asf",
							"asr" => "video/x-ms-asf",
							"asx" => "video/x-ms-asf",
							"au" => "audio/basic",
							"avi" => "video/x-msvideo",
							"axs" => "application/olescript",
							"bas" => "text/plain",
							"bcpio" => "application/x-bcpio",
							"bin" => "application/octet-stream",
							"bmp" => "image/bmp",
							"c" => "text/plain",
							"cat" => "application/vnd.ms-pkiseccat",
							"cdf" => "application/x-cdf",
							"cer" => "application/x-x509-ca-cert",
							"class" => "application/octet-stream",
							"clp" => "application/x-msclip",
							"cmx" => "image/x-cmx",
							"cod" => "image/cis-cod",
							"cpio" => "application/x-cpio",
							"crd" => "application/x-mscardfile",
							"crl" => "application/pkix-crl",
							"crt" => "application/x-x509-ca-cert",
							"csh" => "application/x-csh",
							"css" => "text/css",
							"dcr" => "application/x-director",
							"der" => "application/x-x509-ca-cert",
							"dir" => "application/x-director",
							"dll" => "application/x-msdownload",
							"dms" => "application/octet-stream",
							"doc" => "application/msword",
							"dot" => "application/msword",
							"dvi" => "application/x-dvi",
							"dxr" => "application/x-director",
							"eps" => "application/postscript",
							"etx" => "text/x-setext",
							"evy" => "application/envoy",
							"exe" => "application/octet-stream",
							"fif" => "application/fractals",
							"flr" => "x-world/x-vrml",
							"flv" => "video/x-flv",
							"gif" => "image/gif",
							"gtar" => "application/x-gtar",
							"gz" => "application/x-gzip",
							"h" => "text/plain",
							"hdf" => "application/x-hdf",
							"hlp" => "application/winhlp",
							"hqx" => "application/mac-binhex40",
							"hta" => "application/hta",
							"htc" => "text/x-component",
							"htm" => "text/html",
							"html" => "text/html",
							"htt" => "text/webviewhtml",
							"ico" => "image/x-icon",
							"ief" => "image/ief",
							"iii" => "application/x-iphone",
							"ins" => "application/x-internet-signup",
							"isp" => "application/x-internet-signup",
							"jfif" => "image/pipeg",
							"jpe" => "image/jpeg",
							"jpeg" => "image/jpeg",
							"jpg" => "image/jpeg",
							"js" => "application/x-javascript",
							"latex" => "application/x-latex",
							"lha" => "application/octet-stream",
							"lsf" => "video/x-la-asf",
							"lsx" => "video/x-la-asf",
							"lzh" => "application/octet-stream",
							"m13" => "application/x-msmediaview",
							"m14" => "application/x-msmediaview",
							"m3u" => "audio/x-mpegurl",
							"man" => "application/x-troff-man",
							"mdb" => "application/x-msaccess",
							"me" => "application/x-troff-me",
							"mht" => "message/rfc822",
							"mhtml" => "message/rfc822",
							"mid" => "audio/mid",
							"mny" => "application/x-msmoney",
							"mov" => "video/quicktime",
							"movie" => "video/x-sgi-movie",
							"mp2" => "video/mpeg",
							"mp3" => "audio/mpeg",
							"mpa" => "video/mpeg",
							"mpe" => "video/mpeg",
							"mpeg" => "video/mpeg",
							"mpg" => "video/mpeg",
							"mpp" => "application/vnd.ms-project",
							"mpv2" => "video/mpeg",
							"ms" => "application/x-troff-ms",
							"mvb" => "application/x-msmediaview",
							"nws" => "message/rfc822",
							"oda" => "application/oda",
							"p10" => "application/pkcs10",
							"p12" => "application/x-pkcs12",
							"p7b" => "application/x-pkcs7-certificates",
							"p7c" => "application/x-pkcs7-mime",
							"p7m" => "application/x-pkcs7-mime",
							"p7r" => "application/x-pkcs7-certreqresp",
							"p7s" => "application/x-pkcs7-signature",
							"pbm" => "image/x-portable-bitmap",
							"pdf" => "application/pdf",
							"pfx" => "application/x-pkcs12",
							"pgm" => "image/x-portable-graymap",
							"pko" => "application/ynd.ms-pkipko",
							"pma" => "application/x-perfmon",
							"pmc" => "application/x-perfmon",
							"pml" => "application/x-perfmon",
							"pmr" => "application/x-perfmon",
							"pmw" => "application/x-perfmon",
							"png" => "image/png",
							"pnm" => "image/x-portable-anymap",
							"pot" => "application/vnd.ms-powerpoint",
							"ppm" => "image/x-portable-pixmap",
							"pps" => "application/vnd.ms-powerpoint",
							"ppt" => "application/vnd.ms-powerpoint",
							"prf" => "application/pics-rules",
							"ps" => "application/postscript",
							"pub" => "application/x-mspublisher",
							"qt" => "video/quicktime",
							"ra" => "audio/x-pn-realaudio",
							"ram" => "audio/x-pn-realaudio",
							"ras" => "image/x-cmu-raster",
							"rgb" => "image/x-rgb",
							"rmi" => "audio/mid",
							"roff" => "application/x-troff",
							"rtf" => "application/rtf",
							"rtx" => "text/richtext",
							"scd" => "application/x-msschedule",
							"sct" => "text/scriptlet",
							"setpay" => "application/set-payment-initiation",
							"setreg" => "application/set-registration-initiation",
							"sh" => "application/x-sh",
							"shar" => "application/x-shar",
							"sit" => "application/x-stuffit",
							"snd" => "audio/basic",
							"spc" => "application/x-pkcs7-certificates",
							"spl" => "application/futuresplash",
							"src" => "application/x-wais-source",
							"sst" => "application/vnd.ms-pkicertstore",
							"stl" => "application/vnd.ms-pkistl",
							"stm" => "text/html",
							"svg" => "image/svg+xml",
							"sv4cpio" => "application/x-sv4cpio",
							"sv4crc" => "application/x-sv4crc",
							"swf" => "application/x-shockwave-flash",
							"t" => "application/x-troff",
							"tar" => "application/x-tar",
							"tcl" => "application/x-tcl",
							"tex" => "application/x-tex",
							"texi" => "application/x-texinfo",
							"texinfo" => "application/x-texinfo",
							"tgz" => "application/x-compressed",
							"tif" => "image/tiff",
							"tiff" => "image/tiff",
							"tr" => "application/x-troff",
							"trm" => "application/x-msterminal",
							"tsv" => "text/tab-separated-values",
							"txt" => "text/plain",
							"uls" => "text/iuls",
							"ustar" => "application/x-ustar",
							"vcf" => "text/x-vcard",
							"vrml" => "x-world/x-vrml",
							"wav" => "audio/x-wav",
							"wcm" => "application/vnd.ms-works",
							"wdb" => "application/vnd.ms-works",
							"wks" => "application/vnd.ms-works",
							"wmf" => "application/x-msmetafile",
							"wps" => "application/vnd.ms-works",
							"wri" => "application/x-mswrite",
							"wrl" => "x-world/x-vrml",
							"wrz" => "x-world/x-vrml",
							"xaf" => "x-world/x-vrml",
							"xbm" => "image/x-xbitmap",
							"xla" => "application/vnd.ms-excel",
							"xlc" => "application/vnd.ms-excel",
							"xlm" => "application/vnd.ms-excel",
							"xls" => "application/vnd.ms-excel",
							"xlt" => "application/vnd.ms-excel",
							"xlw" => "application/vnd.ms-excel",
							"xof" => "x-world/x-vrml",
							"xpm" => "image/x-xpixmap",
							"xwd" => "image/x-xwindowdump",
							"z" => "application/x-compress",
							"zip" => "application/zip");


		// Parse the Filename to get the extension
		if( strpos( $Filename, '.' ) ){
			$ext = substr( $Filename, strripos( $Filename, '.' ) + 1 );
			if( isset( $mime_types[$ext] ) ){
				$MIME = $mime_types[$ext];
				header("Content-Type: $MIME");
			}
		}

		return;
	}
?>