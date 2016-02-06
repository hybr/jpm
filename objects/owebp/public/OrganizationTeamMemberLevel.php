<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_OrganizationTeamMemberLevel extends owebp_Base {
	function __construct() {
		$this->collectionName = 'organization_team_member_level';
	} /* __construct */
	public $fields = array (
		'name' => array (
			'type' => 'string',
			'help' => 'Level of member in the team',
			'show_in_list' => 1,
			'required' => 1
		) 
	); /* fields */
} /* class */
?>
