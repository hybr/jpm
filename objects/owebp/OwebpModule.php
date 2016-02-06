<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_OwebpModule extends owebp_Base {
	public $titleValueConversionRequired = 0;
	public $fields = array (
			'value' => array (
					'type' => 'integer',
					'required' => 1 
			),
			'title' => array (
					'type' => 'text',
					'required' => 1 
			),
			'collections' => array (
					'type' => 'array',
					'required' => 1 
			) 
	);
	public $dataLocation = DATA_LOCATION_SERVER_CODE;
	public $table = array (
			array (
					'value' => 'All',
					'title' => 'All',
					'collections' => array(),
					'parent' => ''
			),
			array (
					'value' => 'Person',
					'title' => 'Person',
					'collections' => array(
							'person', 'user', 'contact'
					),
					'parent' => 'All'
			),
			array (
					'value' => 'Location',
					'title' => 'Location',
					'collections' => array(
							'organization_branch', 'organization_building',
							'organization_working_seat', 'contact'
					),
					'parent' => 'Organization Structure'
			),
			array (
					'value' => 'Team',
					'title' => 'Team',
					'collections' => array(
							'organization_team',
							'organization_team_member_level'
					),
					'parent' => 'Organization Structure'
			),
			array (
					'value' => 'Role',
					'title' => 'Role',
					'collections' => array(
							'organization_role', 'rbac_rule'
					),
					'parent' => 'Organization Structure'
			),			
			array (
					'value' => 'Organization Structure',
					'title' => 'Organization Structure',
					'collections' => array(
							'organization'
					),
					'parent' => 'All'
			),			
			array (
					'value' => 'Web Presence',
					'title' => 'Web Presence',
					'collections' => array(
							'web_page', 'file_upload'
					),
					'parent' => 'All'
			),
			array (
					'value' => 'Product and Service',
					'title' => 'Product and Service',
					'collections' => array(
							'item_catalog', 'item'
					),
					'parent' => 'All'
			)
	);
	public function getCollections($value) {
		foreach ($this->table as $record) {
			if ($record['value'] == $value) {
				return $record['collections'];
			}
		}
		return array();
	}
}
?>