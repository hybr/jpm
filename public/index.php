
<?php session_start(); ?>
<?php include '../autoload.php'?>

<?php
	$jpmContent = '';
	$actionInstance = NULL;
	try {	
		$actionInstance = new $_SESSION['url_action'] ();
		if (method_exists ( $actionInstance, $_SESSION['url_task'] )) {
			if (isAllowed(array($actionInstance->myModuleName()), $_SESSION['url_sub_task'])) {
				$jpmContent .= $actionInstance->{$_SESSION['url_task']} ( $urlArgsArray );
			} else {
				$jpmContent .= 'Sorry, No Access Please';
			}
		} else {
			echo 'invalid task ' . $_SESSION['url_task'];
		}
	} catch ( Exception $e ) {
		echo 'invalid action ' . $_SESSION['url_action'] . $e->getMessage ();
	}
?>
<?php 
	if(file_exists('home_pages/_default.php')) 
		include 'home_pages/_default.php';
?>
