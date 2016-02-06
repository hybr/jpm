<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_RbacRule extends owebp_Base {
	function __construct() {
		$this->collectionName = 'rbac_rule';
	} /* __construct */
	public $fields = array (
			'module' => array (
					'type' => 'list',
					'list_class' => 'owebp_OwebpModule',
					'input_mode' => 'selecting',
					'show_in_list' => 1,
					'default' => 'All'
			),
			'organization_role' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'organization_role',
					'foreign_search_fields' => 'abbreviation,name',
					'foreign_title_fields' => 'abbreviation,name',
					'show_in_list' => 1,
					'required' => 1 
			),
			'permission' => array (
					'type' => 'list',
					'list_class' => 'owebp_RbacPermission',
					'input_mode' => 'selecting',
					'show_in_list' => 1,
					'multiple' => 1,
					'default' => 'Show'
			)
	); /* fields */
	
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
		
		return $rStr;
	}	
} /* class */
?>