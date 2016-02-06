<title>
<?php 
	echo $_SESSION ['url_domain_org']['abbreviation'];
	if ($actionInstance->collectionName == 'web_page' && $_SESSION['url_task'] == 'present') { 
		echo ''; /* web page title get added via javascript */ 
	} else {
		echo getTitle($actionInstance->collectionName) . ' ' . getTitle($_SESSION['url_task']);
	}
?>
</title>
