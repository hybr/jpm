<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_organizationBuilding extends owebp_Base {
	function __construct() {
		$this->collectionName = 'organization_building';
	} /* __construct */
	public $fields = array (
			'organization_branch' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'organization_branch',
					'foreign_search_fields' => 'code,name',
					'foreign_title_fields' => 'code,name'
			),			
			'code' => array (
					'type' => 'string',
					'show_in_list' => 1,
					'required' => 1 
			),
			'name' => array (
					'type' => 'string',
					'help' => 'Name of the organization branch',
					'show_in_list' => 1 
			),
			'operating_hours' => array (
					'type' => 'container',
					'required' => 1,
					'fields' => array (
							'weekday' => array (
									'type' => 'list',
									'list_class' => 'owebp_Weekday',
									'input_mode' => 'clicking',
									'show_in_list' => 1,
									'multiple' => 1,
									'default' => 'Mon' 
							),
							'open' => array (
									'type' => 'time',
									'required' => 1 
							),
							'close' => array (
									'type' => 'time',
									'required' => 1 
							),
							'reach_us' => array(
									'type' => 'container',
									'required' => 1,
									'fields' => array (
											'contact' => array (
													'type' => 'foreign_key',
													'foreign_collection' => 'contact',
													'foreign_search_fields' => 'location,medium,phone_number,fax_number,pager_number,voip_number,email_address,city,pin_or_zip,area,street,home_or_building',
													'foreign_title_fields' => 'location,medium,phone_number,fax_number,pager_number,voip_number,email_address,city,pin_or_zip,area,street,home_or_building'
											)											
									),							
							),							
							 
					) 
			) 
	); /* fields */
} /* class */
?>
