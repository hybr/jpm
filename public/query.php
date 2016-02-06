
<?php session_start(); ?>
<?php include '../autoload.php'?>

<?php

// Prevent caching.
header ( 'Cache-Control: no-cache, must-revalidate' );
/* header ( 'Expires: ' . date () ); */

// The JSON standard MIME header.
header ( 'Content-type: application/json' );

if (empty($_SESSION ['user'])) {
	echo json_encode ( array (
		array (
			'label' => 'Please login first',
			'value' => '' 
		) 
	) );
	return;
}

/* find arguments */
$urlPartsArray = parse_url ( $_SERVER ['REQUEST_URI'] );

$urlArgsArray = array ();
if (array_key_exists ( 'query', $urlPartsArray )) {
	parse_str ( $urlPartsArray ['query'], $urlArgsArray );
}

$classForQuery = 'owebp_public_';
foreach ( split('_', $urlArgsArray ['c']) as $w ) {
	$classForQuery .= ucfirst ( strtolower ( $w ) );			
}
$actionInstance = new $classForQuery();
if (!isAllowed(array($actionInstance->myModuleName()), $_SESSION['url_sub_task'])) {
	echo json_encode ( array (
		array (
			'label' => 'No Access to ' . $_SESSION['url_task'] . '/' .  $_SESSION['url_sub_task'],
			'value' => ''
		)
	));
	return;
}

/* if request is for businesses then show the businesses user owners */
$arr = array ();
$sfs = split ( ",", $urlArgsArray ['sf'] );
$condition = array ();
$jsConf = '';
$searchConditions = array ();
$limit = 10;

/* create search conditions array */
foreach ( $sfs as $sf ) {
	array_push ( $searchConditions, array (
			$sf => array (
					'$regex' => new MongoRegex ( "/" . $urlArgsArray ['p'] . "/i" ) 
			) 
	) );
}

	/* there are few collections which are open for public, for rest add organization as conditions */
	if (in_array($urlArgsArray ['c'], array('user', 'person', 'organization', 'item'))) {
		/* for public */
		$searchConditions = array(
			'$or' => $searchConditions
		);
	} else {
		$id = '';
		if (isset($_SESSION ['url_domain_org']) && isset ( $_SESSION ['url_domain_org'] ['_id'] )) {
			$id = $_SESSION ['url_domain_org'] ['_id'];
		} else {
			$id = '54c27c437f8b9a7a0d074be6'; /* owebp */
		}		
		$isOwnedByCurrentUrlDomain = array (
				'for_org' => new MongoId ( $id )
		);
		/* specific to the domain */
		$searchConditions = array(
				'$and' => array(
						$isOwnedByCurrentUrlDomain,
						array(
							'$or' => $searchConditions
						)
				)
		);
	}

	/* print_r($searchConditions); */
	
	$findCursor = $_SESSION['mongo_database']->{$urlArgsArray ['c']}->find ($searchConditions)->limit ( $limit );


$arr = array ();
$tfs = split ( ",", $urlArgsArray ['tf'] );
foreach ( $findCursor as $doc ) {
	$label = '';
	foreach ( $tfs as $tf ) {
		if (!isset($doc[$tf])) {
			continue;
		}
		if (is_array ( $doc [$tf] )) {
			foreach ( $doc [$tf] as $subField ) {
				foreach ( $subField as $subElem => $val ) {
					$label .= $val . ' ';
				}
				$label = rtrim ( $label, " " );
				$label .= '; ';
			}
			$label = rtrim ( $label, "; " );
		} else {
			if(isset($doc[$tf]) && $doc[$tf] != '') {
				$label .= $doc [$tf] . ', ';
			}
		}
		$label .= ", ";
	}
	$label = rtrim ( $label, ", " );
	array_push ( $arr, array (
			'label' => $label,
			'value' => ( string ) $doc ['_id'] 
	) );
}

echo json_encode ( $arr );

?>
