<?php
	$thisBaseURL = $BaseURL . 'default-landing-page/';
	
?><!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo FVLS_SERVICE_NAME; ?> URL Shortener</title>
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
	<div class="theBase"></div>
	<header class="primary-header">
		<h1><?php echo FVLS_SERVICE_NAME; ?>  Link Shortener</h1>
	</header>

	<section id="primary-content">
		<div class="row-fluid">
			<article class="offset2 span8 textCenter description">
				A link shortening service for <?php echo FVLS_SERVICE_NAME; ?><br />
				Learn more about what we do at <a href="<?php echo FVLS_SERVICE_URL; ?>"><?php echo rtrim( substr( FVLS_SERVICE_URL, strpos( FVLS_SERVICE_URL, '://' ) + 3 ), '/' ); ?></a>
			</div>
		</div>
		<div class="row-fluid">
			<article class="span6">
				<header class="textCenter">
					<div class="circle"><img src="short-links.png" /></div>
					<h2>Keeping Links Classy</h2>
				</header>

				<div class="textJustify">
					<p>Keeping links short helps people out.
						First it allows you to share short links instead of long complicated ones.
						It's easier to tweet, text, and print short links.
						The shorter the less chance of typos or links becoming cut off.</p>
					<p>There are numerous link shortening services out there.
						This one is designed to work with <a href="<?php echo FVLS_SERVICE_URL; ?>"><?php echo FVLS_SERVICE_NAME; ?></a>.
						So if you're wondering where the link came from.
						Checking them out would be the logical choice.</p>
				</div>
			</article>
		
			<article class="span6">
				<header class="textCenter">
					<div class="circle"><img src="understanding.png" /></div>
					<h2>Increasing Understanding</h2>
				</header>

				<div class="textJustify">
					<p>Creating content is hard work.
						Understanding who is reading and engaging with that content is even harder.
						But when we can see what catches on - and what doesn't - we make better content in the future.</p>
					<p>The ultimate goal of tracking clicks and other metrics is to understand what our audience is interested in.
						That lets us focus our energy in areas that are more meaningful for you.</p>
				</div>
			</article>
		</div>
	</section>

	<footer>
		<p>&copy; <?php echo FVLS_COPYRIGHT_START; if( FVLS_COPYRIGHT_START !== date('Y') ){ echo '-' . date('y'); } ?> <a href="<?php echo FVLS_SERVICE_URL; ?>"><?php echo FVLS_SERVICE_NAME; ?></a>
			&middot; <a href="https://github.com/FreshVine/link-shortener">Follow on Github</a>
		</p>
	</footer>
</body>
</html>