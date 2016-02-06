<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_OrganizationRole extends owebp_Base {
	function __construct() {
		$this->collectionName = 'organization_role';
	} /* __construct */
	public $fields = array (
			'abbreviation' => array (
					'type' => 'string',
					'show_in_list' => 1 
			),
			'name' => array (
					'type' => 'string',
					'help' => 'Name of the organization position',
					'show_in_list' => 1,
					'required' => 1 
			),
			'organization' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'organization',
					'foreign_search_fields' => 'abbreviation,name',
					'foreign_title_fields' => 'abbreviation,name',
					'required' => 1 
			),
			'team' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'organization_team',
					'foreign_search_fields' => 'abbreviation,name',
					'foreign_title_fields' => 'abbreviation,name',
					'required' => 1 
			),
			'level' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'organization_team_member_level',
					'foreign_search_fields' => 'name',
					'foreign_title_fields' => 'name',
					'required' => 1 
			),
			'location' => array (
					'type' => 'container',
					'required' => 1,
					'fields' => array (
							'seat' => array (
									'help' => 'Add one seat for each position',
									'type' => 'foreign_key',
									'foreign_collection' => 'organization_working_seat',
									'foreign_search_fields' => 'floor,room,seat',
									'foreign_title_fields' => 'floor,room,seat',
									'required' => 1 
							) 
					) 
			) 
	)
	; /* fields */
} /* class */
?>
