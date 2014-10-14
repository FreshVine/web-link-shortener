<?php
	$thisBaseURL = $BaseURL . 'default-404/';
	
?><!DOCTYPE html>
<html lang="en">
<head>
	<title>Link not Found - <?php echo FVUS_SERVICE_NAME; ?> URL Shortener</title>
	<base href="<?php echo $thisBaseURL; ?>" target="_blank"><?php // Required to work with dynamic re-writing - this path is correct ?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<link href="style.css" rel="stylesheet">

	<!-- Include the pretty icons for the fav-icon and bookmarks -->
	<link rel="apple-touch-icon" sizes="144x144" href="touch-icon-144.png">
	<link rel="apple-touch-icon" sizes="114x114" href="touch-icon-114.png">
	<link rel="apple-touch-icon" sizes="72x72" href="touch-icon-72.png">
	<link rel="apple-touch-icon" sizes="57x57" href="touch-icon-57.png">
	<link rel="shortcut icon" sizes="196x196" href="touch-icon-196.png">
	<link rel="shortcut icon" type="image/png" href="touch-icon-196.png">
	<link rel="icon" type="image/png" href="touch-icon-196.png">
	<link rel="icon" type="image/png" href="favicon.png">
</header>
<body>
	<header class="theBase">
		<div class="vertCenter textCenter">
			<h1>Oh, no! Link not Found</h1>
			Looks like your link was lost somewhere in space.<br />
			At least you can learn more about <a href="<?php echo FVUS_SERVICE_URL; ?>"><?php echo FVUS_SERVICE_NAME; ?></a>
		</div>
	</header>
</body>
</html>