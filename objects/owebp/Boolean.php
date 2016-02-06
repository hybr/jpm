<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_Boolean extends owebp_Base {
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
		'icon' => array (
				'type' => 'text',
				'required' => 1
		)
	);
	public $dataLocation = DATA_LOCATION_SERVER_CODE;
	public $table = array (
		array (
			'value' => 'True',
			'title' => 'True',
			'icon' => 'ui-icon-check'
		),
		array (
			'value' => 'False',
			'title' => 'False',
			'icon' => 'ui-icon-closethick' 
		)
	);
}
?>
