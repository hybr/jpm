<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_Contact extends owebp_Base {
	function __construct() {
		$this->collectionName = 'contact';
		
	} /* __construct */
	public $fields = array (
			'public' => array (
					'type' => 'list',
					'help' => 'If true then this contact info will be on contact us page',
					'list_class' => 'owebp_Boolean',
					'input_mode' => 'clicking',
					'default' => 'False',
					'sub_tasks' => array('all', 'Phone','Fax','Pager','Voip','Email Address','Postal Address','Web')
			),
			'location' => array (
					'type' => 'list',
					'list_class' => 'owebp_ContactLocation',
					'input_mode' => 'clicking',
					'show_in_list' => 1,
					'default' => 'Work',
					'sub_tasks' => array('all', 'Phone','Fax','Pager','Voip','Email Address','Postal Address','Web')
			),
			'medium' => array (
					'type' => 'list',
					'list_class' => 'owebp_ContactMedium',
					'input_mode' => 'selecting',
					'show_in_list' => 1,
					'default' => 'Phone',
					'sub_tasks' => array('all')
			),
			'primary' => array (
					'type' => 'list',
					'help' => 'Is thiis a main contact?',
					'list_class' => 'owebp_Boolean',
					'input_mode' => 'clicking',
					'show_in_list' => 1,
					'default' => 'False',
					'sub_tasks' => array('all', 'Phone','Fax','Pager','Voip','Email Address','Postal Address','Web')
			),
			'phone_number' => array (
					'type' => 'number',
					'help' => 'Format: (Country Code)(Full Number)',
					'sub_tasks' => array('all', 'Phone')
			),
			'fax_number' => array (
					'type' => 'number',
					'sub_tasks' => array('all', 'Fax')
			),
			'pager_number' => array (
					'type' => 'number',
					'sub_tasks' => array('all', 'Pager')
			),
			'voip_number' => array (
					'type' => 'number',
					'help' => 'Voice over IP',
					'sub_tasks' => array('all', 'Voip')
			),
			'email_address' => array (
					'type' => 'email',
					'sub_tasks' => array('all', 'Email Address')
			),
			'web_url' => array (
					'type' => 'url',
					'sub_tasks' => array('all', 'Web')
			),			
			'city' => array (
					'type' => 'geonames_city',
					'help'=> 'Type first three letters of city to start autocomplete',
					'sub_tasks' => array('all', 'Postal Address')
			),
			'pin_or_zip' => array (
					'sub_tasks' => array('all', 'Postal Address')
			),
			'area' => array (
					'sub_tasks' => array('all', 'Postal Address')
			),
			'street' => array (
					'sub_tasks' => array('all', 'Postal Address')
			),
			'home_or_building' => array (
					'sub_tasks' => array('all', 'Postal Address'),
					'help' => 'Home / Flat /Apartment / Building number' 
			) 
	); /* fields */
	public $subTaskKeyToSave = 'medium';

	public function showContactAsTableRow ($rec) {

		$rStr = '<tr>';
		$rStr .= '<td>';
		if (strtolower($rec['primary']) == 'true') {
			$rStr .= 'Primary ';
		}
		$rStr .= $rec['location'] . ' ';
		$rStr .= $rec['medium'] . ' ';
		$rStr .= '</td>';
		$rStr .= '<td>';
		if ($rec['medium'] == 'Email Address') {
			$rStr .= '<a target="_blank" href="mailto:'.$rec['email_address'].'">'.str_replace('.',' dot ',str_replace('@',' at ',$rec['email_address'])).'</a>';
		}
		if ($rec['medium'] == 'Phone') {
			$rStr .= '<a href="tel:+'.$rec['phone_number'].'">+'.$rec['phone_number'].'</a>';
		}
		if ($rec['medium'] == 'Fax') {
			$rStr .= '<a href="tel:+'.$rec['fax_number'].'">+'.$rec['fax_number'].'</a>';
		}
		if ($rec['medium'] == 'Pager') {
			$rStr .= '<a href="tel:+'.$rec['pager_number'].'">+'.$rec['pager_number'].'</a>';
		}
		if ($rec['medium'] == 'VoIP') {
			$rStr .= '<a href="tel:+'.$rec['voip_number'].'">+'.$rec['voip_number'].'</a>';
		}
		if ($rec['medium'] == 'Postal Address') {
			$buildingNumberExists = false;
			if (isset($rec['home_or_building']) && $rec['home_or_building'] != '') {
				$rStr .= $rec['home_or_building'];
				$buildingNumberExists = true;
			}
			if (isset($rec['street']) && $rec['street'] != '') {
				if ($buildingNumberExists) {
					$rStr .= ', ';
				}
				$rStr .= $rec['street'];
			}
			if (isset($rec['area']) && $rec['area'] != '') {
				$rStr .= '<br />';
				$rStr .= $rec['area'];
			}
			$cityExists = false;
			if (isset($rec['city']) && $rec['city'] != '') {
				$rStr .= '<br />';
				$rStr .= $rec['city'];
				$cityExists = true;
			}
			if (isset($rec['pin_or_zip']) && $rec['pin_or_zip'] != '') {
				if ($cityExists) {
					$rStr .= ', ';
				} else {
					$rStr .= '<br />';
				}
				$rStr .= $rec['pin_or_zip'];
			}
		}
		$rStr .= '</td>';
		$rStr .= '</tr>';
		return $rStr;
	}

        public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr .= '<table class="showTable">';
		$contactInstance = new owebp_public_Contact();
                foreach ( $docCursor as $doc ) {
			if (isset($doc['public']) && strtolower($doc['public']) == 'true') {
                        	$rStr .= $this->showContactAsTableRow($doc);
			}
		}
		$rStr .= '</table>';
                return $rStr;
        }

} /* class */
?>
