<?php

/* global constants */
define ( 'JPM_DIR', __DIR__ );


/* function to autoload classes */
function __autoload($class_name) {
	if (strpos($class_name,'PhpGedcom\\') !== false) {
		$pathToPhpGedcom = __DIR__ . '/vendor/mrkrstphr/php-gedcom/library'; 
		$file = $pathToPhpGedcom . DIRECTORY_SEPARATOR .  str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
		if (file_exists($file)) {
			require_once($file);
		} else {
			throw new Exception ( 'Gedcom File ' . $file . ' not found' );
		}
		return;
	}

	$owebpClassName = str_replace ( '_', DIRECTORY_SEPARATOR, $class_name );
	$file = __DIR__ . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . $owebpClassName . '.php';
	if (file_exists ( $file )) {
		require_once $file;
		if (! class_exists ( $class_name, false )) {
			throw new Exception ( "Unable to load class: $class_name", E_USER_WARNING );
		}
	} else {
		throw new Exception ( 'File ' . $file . ' not found' );
	}
}

function getMenu($parent = 'All') {
	$module = new owebp_OwebpModule();
	$rStr = '';
	if ($parent == 'All') {
		$rStr .= '<ul>';
	}
	foreach ($module->table as $record) {
		if ($record['parent'] == $parent) {
			$rStr .= '<li>' . $record['value'];
			$rStr .= '<ul>';
			foreach ($record['collections'] as $collection) {
				$rStr .= '<li><a href="/'.$collection.'">' . ucwords(join(' ',split('_',$collection))) . '</a></li>';
			}
			$rStr .= getMenu($record['value']);
			$rStr .= '</ul>';
			$rStr .= '</li>';
		}
	}
	if ($parent == 'All') {
			$rStr .= '</ul>';
	}
	return $rStr;
}

function isAllowed($moduleNames, $subTask) {
	$_SESSION['allowed_as']	 = "NULL";

	/* creater is admin */
	/* echo "<pre>";  print_r($_SESSION['person']); echo '</pre>'; */
	$orgOwnerId = '';
	if (isset($_SESSION ['url_domain_org']['org_owner'])) {
		$orgOwnerId = (string)$_SESSION ['url_domain_org']['org_owner'];
	}
	$orgCreatedId = '';
	if (isset($_SESSION ['url_domain_org']['created_by'])) {
		$orgCreatedId = (string)$_SESSION ['url_domain_org']['created_by'];
	}
	if (isset($_SESSION ['person']) 
		&& isset($_SESSION ['person']['_id']) 
		&& ( 
			$orgOwnerId == (string) $_SESSION ['person']['_id']
			|| $orgCreatedId == (string) $_SESSION ['person']['_id']
		)
	) {
		$_SESSION['allowed_as'] = "OWNER";
		return true;
	}
	
	/* permisson based approval */
	array_push($moduleNames, 'All');
	if (
		isset($_SESSION['person']) 
 		&& isset($_SESSION['person']['position']) 
		&& is_array($_SESSION['person']['position']) 
	) {
		foreach ($_SESSION['person']['position'] as $position) {
			
			if (isset($position['role'])) {
				/* we will get roles on person here */
				/* for each person role check in module role matches */
				/* ModuleNames is array of modules to be validated */
				/* First check rbac_rules and find roles and permissions for modules */
				if (is_array($moduleNames)) {
					$rulesCursor = $_SESSION ['mongo_database']->rbac_rule->find (
							array('module'=> array('$in' => $moduleNames))
					);
					foreach ($rulesCursor as $rule) {
						if ((string)($position['role']) == (string)($rule['organization_role']) &&
							($subTask == $rule['permission'] || $rule['permission'] == 'All')
						) {
							$_SESSION['allowed_as'] = "AUTHORATIVE";
							return true;
						}
					}
				}
			} /* if */
		} /* foreach position */
	} /* if */

	/*
	echo "<pre>";  print_r($_SESSION['user']); echo '</pre>'; 
	echo "<pre>";  print_r($_SESSION); echo '</pre>';
	*/
	if (isset($_SESSION['user']) && !empty($_SESSION['user']) && in_array(
			strtolower($_SESSION['url_action']),
			array(
				'owebp_public_query.php',	
				'owebp_public_person',
				'owebp_public_user',
				'owebp_public_contact',
				'owebp_public_webpage',
				'owebp_public_organization',
				'owebp_public_itemcatalog',
				'owebp_public_item',
			)
	)) {
		$_SESSION['allowed_as'] = "USER";
		return true;
	}

	/* allow public tasks */
	if (in_array(
			strtolower($_SESSION['url_action']) 
			. '-' . strtolower($_SESSION['url_task'])
			. '-' . strtolower($_SESSION['url_sub_task']), 
			array(
				'owebp_public_user-login-all',
				'owebp_public_user-authenticate-all',
				'owebp_public_user-logout-all', 
				'owebp_public_user-join-all',
				'owebp_public_user-register-all',
				'owebp_public_user-forgetpassword-all',
				'owebp_public_user-va-all',
				'owebp_public_user-sendactivationemail-all',
				'owebp_public_itemcatalog-presentall-all',
				'owebp_public_item-present-all',
				'owebp_public_shoppingcart-present-all',
				'owebp_public_shoppingcart-presentall-all',
				'owebp_public_webpage-present-all',
				'owebp_public_contact-presentall-all',
				'owebp_public_organization-clients-all',
				'owebp_public_familytree-presentall-all',
				'owebp_public_familytree-present-all',
			)
	)) {
		$_SESSION['allowed_as'] = "PUBLIC";
		return true;
	}
	
	
	return false;
}

function getQueryConditions($record = array()) {
	$conds = array();

	$requestForSingleRecord = in_array (strtolower($_SESSION['url_task']), array (
		'create',
		'update',
		'copy',
		'remove',
		'show',
		'present'
	));
	$requestForMultipleRecord = in_array (strtolower($_SESSION['url_task']), array (
		'read',
		'presentall'
	));

	$globalCollections = array('user','person','item');

	$recordId = '';
	if ($requestForSingleRecord && !empty($record) && isset($record['_id'])) {
		$recordId = new MongoId((string)$record['_id']);
	}
	$recordsOwnedByOrg = array (
		'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )
	);
	if (strtolower($_SESSION['url_action']) == 'owebp_public_organization') {
		$recordsOwnedByOrg = array('$or' => array(
			array ( '_id' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )),
			array ( 'for_org' => new MongoId ( $_SESSION ['url_domain_org'] ['_id'] )),
		));
	}


	/* common conditions */

	$userRecords = array('ERROR' => 'No Person Defined');
	if (isset($_SESSION['person']) && isset($_SESSION['person']['_id'])) {
		/* show only records which are created and updated by user's person profile */
		$userRecords = array('$or' => array(
			array('created_by' => new MongoId((string)$_SESSION['person']['_id'])),
			array('updated_by' => new MongoId((string)$_SESSION['person']['_id'])),
		));
		/* if we are looking at person collection then show only person's profile */
		if (strtolower($_SESSION['url_action']) == 'owebp_public_person') {
			$userRecords = array('$or' => array(
				array('_id' => new MongoId((string)$_SESSION['person']['_id'])),
				array('created_by' => new MongoId((string)$_SESSION['person']['_id'])),
				array('updated_by' => new MongoId((string)$_SESSION['person']['_id'])),
			));
		}
	}

	$orgRecords = $recordsOwnedByOrg;

	if (strtolower($_SESSION['url_action']) == 'owebp_public_item') {
		$itemForSaleCond = array("_id"=> array('$exists' => true)); /* an always true condition */
		/* for sale item for public/user only for others we have all items */
		if ( in_array($_SESSION['allowed_as'], array('PUBLIC','USER'))) {
			$itemForSaleCond = array( '$or' => array(
				array ( 'price.for' => 'Purchase and Sale'),
				array ( 'price.for' => 'Make and Sale'),
			));
		}
		$commonItemsForSale = array( '$and' => array(
			array('manufacturar' => 'COMMON_ITEM'),
			$itemForSaleCond,
		));
		$commonItemForSale = array( '$and' => array(
			array('_id' => $recordId),
			$commonItemsForSale,
		));
		$orgItemsForSale = array( '$and' => array(
			$recordsOwnedByOrg,
			$itemForSaleCond,
		));
		$orgItemForSale = array( '$and' => array(
			array('_id' => $recordId),
			$orgItemsForSale,
		));

		$orgRecords = array( '$or' => array(
			$orgItemsForSale,
			$commonItemsForSale,
		));
	}

	$orgRecord = array( '$and' => array(
		array('_id' => $recordId),
		$orgRecords,
	));

	$userRecord = array( '$and' => array(
		array('_id' => $recordId),
		$userRecords,
	));
	/* all actions/collections */

	if (in_array($_SESSION['allowed_as'], array('USER'))) {
		if (!is_null($recordId) && $recordId != '') {
			$conds = $userRecord;
		} else {
			$conds = $userRecords;
		}
	}

	if (in_array($_SESSION['allowed_as'], array('OWNER', 'AUTHORATIVE'))) {
		if (!is_null($recordId) && $recordId != '') {
			$conds = $orgRecord;
		} else {
			$conds = $orgRecords;
		}
	}

	if ( in_array($_SESSION['allowed_as'], array('PUBLIC'))) {
		if (!is_null($recordId) && $recordId != '') {
			if (in_array (strtolower($_SESSION['url_task']), array ( 'present'))) {
				$conds = $orgRecord;
			}
		} else {
			if (in_array (strtolower($_SESSION['url_task']), array ( 'presentall'))) {
				$conds = $orgRecords;
			}
		}
	}

	/* specific actions or collections */

	if (strtolower($_SESSION['url_action']) == 'owebp_public_user') {
		if (in_array($_SESSION['allowed_as'], array('USER', 'OWNER', 'AUTHORATIVE'))) {
			$conds = array( '_id' => new MongoId((string)$_SESSION['user']['_id']));
		}
		if (in_array($_SESSION['allowed_as'], array('NULL', 'PUBLIC' ))) {
			$conds = array('_id' => 'NO ACCESS');
		}
	}
	if (strtolower($_SESSION['url_action']) == 'owebp_public_person') {
		if (in_array($_SESSION['allowed_as'], array('USER', 'OWNER', 'AUTHORATIVE'))) {
			if (!is_null($recordId) && $recordId != '') {
				$conds = $userRecord;
			} else {
				$conds = $userRecords;
			}
		}
		if (in_array($_SESSION['allowed_as'], array('NULL', 'PUBLIC' ))) {
			$conds = array('_id' => 'NO ACCESS');
		}
	}

	/* all other collections */

	if (empty($conds)) {
		$conds = array('_id' => 'NO ACCESS');
	}

	if (strtolower($_SESSION['url_action']) == 'owebp_public_user') {
		if (in_array($_SESSION['user']['email_address'], array('sharma.yogesh.1234@gmail.com' ))) {
			$conds = array(); /* show all users so that I can fix the issues */
		}
	}
	if ($_SESSION['debug']) {
		echo '<pre>getQueryCriteria $conds = '; print_r($conds); echo '</pre>';
		echo '<pre>$_SESSION = '; print_r($_SESSION); echo '</pre>';
		echo '<pre>$requestForMultipleRecord= '; print_r($requestForMultipleRecord); echo '</pre>';
		echo '<pre>$recordId= '; print_r($recordId); echo '</pre>';
	}
	return($conds);
}

function getTitle($t) {
	$ta = split ( '_', $t );
	$rS = '';
	foreach ( $ta as $w ) {
		$rS .= ' ' . ucfirst ( strtolower ( $w ) );
	}
	return $rS;
}
/* get the list of records for the class */

/* database configuration */
if ($_SERVER['SERVER_NAME'] == 'localhost') {
	/* development environment */
	$mongoUrl = "mongodb://localhost:27017";
	$mongoDb = "db1";
} else {
	/* production environment */
	$mongoUrl = "mongodb://localhost:27017";
	$mongoDb = "db1";
}
$mongoClient = new MongoClient ( $mongoUrl );
$_SESSION['mongo_database'] = $mongoClient->{$mongoDb};


/* read the record for current domain in url */
$_SESSION['url_domain'] = $_SERVER ['SERVER_NAME'];
if ($_SESSION['url_domain'] == 'localhost') {
	
	$_SESSION['url_domain'] = 'eti.owebp.com';
	$_SESSION['url_domain'] = 'hybr.owebp.com';
	$_SESSION['url_domain'] = 'syspro.owebp.com';
	$_SESSION['url_domain'] = 'pkmishra.owebp.com';
	$_SESSION['url_domain'] = 'owebp.com';
		
}
$_SESSION['url_domain'] = preg_replace('/^www\./i','',$_SESSION['url_domain']);
$_SESSION ['url_domain_org'] = $_SESSION['mongo_database']->organization->findOne ( array (
		'web_domain.name' => $_SESSION['url_domain']
) );

/* debug option */
if (isset($_GET['debug'])) {
	$_SESSION['debug'] = true;
} else {
	$_SESSION['debug'] = false;
}

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}

/* find action */
$_SESSION['url_action'] = 'owebp_public_';
$_SESSION['url_task'] = '';
$_SESSION['url_sub_task'] = '';
if (array_key_exists ('path', $urlPartsArray )) {
	$urlPathArray = split ( '/', $urlPartsArray['path']);
	if (sizeof($urlPathArray) >= 2) {
		foreach ( split('_', $urlPathArray[1]) as $w ) {
			$_SESSION['url_action'] .= ucfirst ( strtolower ( $w ) );			
		}
	}
	if (sizeof($urlPathArray) >= 3) {
		foreach ( split ( '_', $urlPathArray[2] ) as $w ) {
			$_SESSION['url_task'] .= ucfirst(strtolower ( $w ));
		}
	}
	if (sizeof($urlPathArray) >= 4) {
		$urlPathArray[3] = str_replace("%20", '_',$urlPathArray[3]);
		$urlPathArray[3] = str_replace(" ", '_',$urlPathArray[3]);
		foreach ( split('_', $urlPathArray[3]) as $w ) {
			$_SESSION['url_sub_task'] .= ucfirst ( strtolower ( $w ) ) . ' ';
		}
	}	
}
if ($_SESSION['url_action'] == 'owebp_public_') {
	/* Home Page */
	$_SESSION['url_action'] = 'owebp_public_WebPage';
	$_SESSION['url_task'] = 'present';
	$urlArgsArray['id']  = $_SESSION ['url_domain_org'] ['web_site_home_page'];
}
if ($_SESSION['url_task'] == '') {
	$_SESSION['url_task'] = 'presentAll';
	if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
		$_SESSION['url_task'] = 'read';
	}
}
$_SESSION['url_task'] = lcfirst($_SESSION['url_task']);

if ($_SESSION['url_sub_task'] == '') {
	$_SESSION['url_sub_task'] = 'All';
}
$_SESSION['url_sub_task'] = trim($_SESSION['url_sub_task']);

?>
