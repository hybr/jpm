<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_RbacPermission extends owebp_Base {
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
					'value' => 'All',
					'title' => 'All' 
			),
			array (
					'value' => 'Create',
					'title' => 'Create' 
			),
			array (
					'value' => 'Remove',
					'title' => 'Remove' 
			),
			array (
					'value' => 'Update',
					'title' => 'Update'
			),
			array (
					'value' => 'List',
					'title' => 'List'
			),
			array (
					'value' => 'Show',
					'title' => 'Show'
			),
			array (
					'value' => 'Present',
					'title' => 'Present'
			),
			array (
					'value' => 'Present All',
					'title' => 'Present All'
			)
	);
}
?>