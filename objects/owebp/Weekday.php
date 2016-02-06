<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_Weekday extends owebp_Base {
	public $titleValueConversionRequired = 0;
	public $fields = array (
			'value' => array (
					'type' => 'integer',
					'required' => 1 
			),
			'title' => array (
					'type' => 'text',
					'required' => 1 
			)
	);
	public $dataLocation = DATA_LOCATION_SERVER_CODE;
	public $table = array (
			array (
					'value' => 'Mon',
					'title' => 'Mon'					
			),
			array (
					'value' => 'Tue',
					'title' => 'Tue' 
			),
			array (
					'value' => 'Wed',
					'title' => 'Wed' 
			),
			array (
					'value' => 'Thu',
					'title' => 'Thu' 
			),
			array (
					'value' => 'Fri',
					'title' => 'Fri' 
			),
			array (
					'value' => 'Sat',
					'title' => 'Sat' 
			),
			array (
					'value' => 'Sun',
					'title' => 'Sun' 
			)
	);
}
?>