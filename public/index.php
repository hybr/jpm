<?php

/**
* This is the entry file for JPM framework.
* A web framework based on jquery, php, mongodb
* Adding AngularJS use from this release
* 
* This framework is designed to host multiple websites from a single 
* location and their data in mongodb. These small websites could be 
* personal of commercial websites.
*
* LICENSE: Owned by HYBR
*
* @category   HYBR
* @package    JPM
* @subpackage index
* @copyright  Copyright (c) 2011-2016 HYBR Trust (http://www.hybr.in)
* @license    Private
* @version    $Id:$
* @link       http://www.hybr.in
* @since      File available since Release 1.0
*/

?>

<?php

/**
* session_start() creates a session or resumes the current one based on a session 
* identifier passed via a GET or POST request, or passed via a cookie.
*/

session_start(); 

?>


<?php

/**
* autoload.php has common logic for the framework
*/

include dirname(__FILE__) . '/../autoload.php' 

?>


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

/**
* This is main home page code file.
* It has a mechanisum to create the home page based on domain name
*/

if(file_exists(dirname(__FILE__) . '/home_pages/_default.php')) 
	include dirname(__FILE__) . '/home_pages/_default.php'; 

?>