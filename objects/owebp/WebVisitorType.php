<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_WebVisitorType extends owebp_Base {
	public $titleValueConversionRequired = 0;
	public $fields = array (
			'value' => array (
					'type' => 'integer',
					'required' => 1 
			),
			'title' => array (
					'type' => 'text',
					'required' => 1 
			) 
	);
	public $dataLocation = DATA_LOCATION_SERVER_CODE;
	public $table = array (
			array (
					'value' => 'Any',
					'title' => 'Any' 
			),
			array (
					'value' => 'Member', /* who can login */
					'title' => 'Member' 
			),
			array (
					'value' => 'Customer', /* who has purchase history */
					'title' => 'Customer' 
			),
			array (
					'value' => 'Supplier', /* who provides pns to us */
					'title' => 'Supplier' 
			),
			array (
					'value' => 'Worker', /* who works for us */
					'title' => 'Worker' 
			)
	);
}
?>