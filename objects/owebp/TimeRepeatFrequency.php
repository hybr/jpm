<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_TimeRepeatFrequency extends owebp_Base {
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
	public $help = '<ul><li>Working Day: Mon to Fri</li><li>Bi-week: Every 14 days </li></ul>';
	public $table = array (
		array (
			'value' => 'Year',
			'title' => 'Year' 
		),
		array (
			'value' => 'Quater',
			'title' => 'Quater' 
		),
		array (
			'value' => 'Month',
			'title' => 'Month' 
		),
		array (
			'value' => 'Bi-week',
			'title' => 'Bi-week' 
		),
		array (
			'value' => 'Week',
			'title' => 'Week' 
		),
		array (
			'value' => 'Day',
			'title' => 'Day' 
		),
		array (
			'value' => 'Working Day',
			'title' => 'Working Day' 
		),
		array (
			'value' => 'Hour',
			'title' => 'Hour' 
		),
	);
}
?>
