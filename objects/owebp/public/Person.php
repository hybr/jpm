<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_Person extends owebp_Base {
	function __construct() {
		$this->collectionName = 'person';
	} /* __construct */
	public $fields = array (
		'name' => array (
			'type' => 'container',
			'required' => 1,
			'show_in_list' => 1,
			'fields' => array (
				'type' => array (
					'type' => 'list',
					'list_class' => 'owebp_PersonNameType',
					'input_mode' => 'clicking',
					'default' => 'Official' 
				),
				'prefix' => array (),
				'first' => array ( 'required' => 1 ),
				'middle' => array (),
				'last' => array (),
				'suffix' => array () 
			) 
		),
		'gender' => array (
			'type' => 'list',
			'list_class' => 'owebp_PersonGender',
			'input_mode' => 'clicking',
			'show_in_list' => 1,
			'default' => 'Male' 
		),
		'login_credential' => array (
			'type' => 'container',
			'show_in_list' => 1,
			'fields' => array (
				'primary' => array (
					'type' => 'list',
					'list_class' => 'owebp_Boolean',
					'input_mode' => 'clicking',
					'show_in_list' => 1,
					'default' => 'False'
				),							
				'email_address' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'user',
					'foreign_search_fields' => 'email_address',
					'foreign_title_fields' => 'email_address,provider' 
				) 
			) 
		),
		'position' => array (
			'type' => 'container',
			'fields' => array (
				'role' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'organization_role',
					'foreign_search_fields' => 'name',
					'foreign_title_fields' => 'name'
				)
			)
		),			
		'relative' => array (
			'type' => 'container',
			'fields' => array (
				'relation' => array (
					'type' => 'list',
					'list_class' => 'owebp_PersonRelation',
					'input_mode' => 'clicking' 
				),
				'person' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'person',
					'foreign_search_fields' => 'name.first,name.middle,name.last',
					'foreign_title_fields' => 'name,gender' 
				) 
			) 
		),
		'photo' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'caption' => array (),
				'file_name' => array (
					'type' => 'file_list',
					'required' => 1 
				),
				'click_link_url' => array (
					'type' => 'url' 
				) 
			) 
		 ) ,
		'check_duplicate' => array (
			'type' => 'list',
			'list_class' => 'owebp_Boolean',
			'input_mode' => 'clicking',
			'default' => 'True'
		),
	); /* fields */
	public function getFullName($type = 'Official', $relatives = false) {
		if (!isset($this->record ['name'])) {
			return 'No name defined';
		}
		$rStr = '';
		foreach ( $this->record ['name'] as $name ) {
			if (isset($name['type']) && $name ['type'] == $type) {
				$rStr .= $name['prefix'] . ' '
					. $name['first'] . ' '
					. $name['middle'] . ' '
					. $name['last'] . ' '
					. $name['suffix'] . ' '
				;
				if ($relatives && isset($this->record ['relative'])) {
					foreach ( $this->record ['relative'] as $relative ) {
						$relativeDoc = $this->getDocumentById('person', (string)$relative['person']);
						foreach ( $relativeDoc['name'] as $relativeName ) {
							if (isset($relativeName ['type']) && $relativeName ['type'] == $type) {
								$rStr .= '<br />' . $relative['relation'] . ': ' 
									. $relativeName['prefix'] . ' '
									. $relativeName['first'] . ' '
									. $relativeName['middle'] . ' '
									. $relativeName['last'] . ' '
									. $relativeName['suffix'] . ' ';
							} /* if ($relativeName ['type'] == $type) */
						} /* foreach ( $relativeDoc['name'] as $relativeName ) */ 
					} /* foreach ( $this->record ['relative'] as $relative ) */
				} /* if ($relatives) */
			} /* if ($name ['type'] == $type) */
		}
		return $rStr;
	} /* public function getFullName($type = 'Official', $relatives = false)  */
	public function getEmailAddress($primary = true) {
		if (!isset($this->record ['login_credential'])) {
			return 'No email defined';
		}
		foreach ( $this->record ['login_credential'] as $loginCredential ) {
			if ($loginCredential['primary'] == $primary) {
				$userDoc = $this->getDocumentById('user', (string)$loginCredential['email_address']);
				return $userDoc['email_address'];
			}
		}
		return '';
	} /* public function getEmailAddress($primary = true) */
	public function getOfficialFullName() {
		return $this->getFullName('Official', false);
	} /* public function getOfficialFullName() */
} /* class */
?>
