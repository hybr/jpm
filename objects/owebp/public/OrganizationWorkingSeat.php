<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_organizationWorkingSeat extends owebp_Base {
	function __construct() {
		$this->collectionName = 'organization_working_seat';
	} /* __construct */
	public $fields = array (
		'building' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'organization_building',
			'foreign_search_fields' => 'code,name',
			'foreign_title_fields' => 'code,name' 
		),
		'floor' => array (
			'help' => 'Floor number',
			'type' => 'number',
			'show_in_list' => 1,
			'required' => 1 
		),
		'room' => array (
			'help' => 'Room number',
			'show_in_list' => 1,
			'required' => 1 
		),
		'seat' => array (
			'help' => 'Seat number',
			'show_in_list' => 1,
			'required' => 1 
		)
	); /* fields */
} /* class */
?>
