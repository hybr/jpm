<html>

	/* providing a way to add extra home pages for the different domains */
	/* if home page is not created for the domain then the default home page of owebp.com will be used */
	<?php $homePageDir = dirname(__FILE__) . '/' . 'owebp.com';
	if (file_exists(dirname(__FILE__) . '/' . $_SESSION['url_domain'])) {
		$homePageDir = dirname(__FILE__) . '/' . $_SESSION['url_domain'];
	}?>

	<head>
		<?php if(file_exists($homePageDir . '/_head.php')) include $homePageDir . '/_head.php'; ?>

	</head>

	<body>
		<?php if(file_exists($homePageDir . '/_body.php')) include $homePageDir . '/_body.php'; ?>
	</body>
</html>
