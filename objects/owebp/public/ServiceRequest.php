<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_User extends owebp_Base {
	function __construct() {
		$this->collectionName = 'user';
	}
	public $fields = array (
		'requester' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last',
			'foreign_title_fields' => 'name,gender' 
		),
		'email_address' => array (
			'type' => 'email',
			'placeholder' => 'Email Address',
			'required' => 1,
			'unique' => 1,
			'show_in_list' => 1 
		),
			'password' => array (
					'type' => 'password',
					'placeholder' => 'Password',
					'help' => 'Length between 5 to 12 charecters',
					'required' => 1,
					'minlength' => 5,
					'maxlength' => 12 
			),
			'provider' => array (
					'type' => 'list',
					'list_class' => 'owebp_AuthenticationProvider',
					'input_mode' => 'clicking',
					'required' => 1,
					'show_in_list' => 1 
			),
			'veryfied' => array(
				'default' => 0
			)
	);

} /* class */
