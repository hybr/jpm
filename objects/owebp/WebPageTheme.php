<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_WebPageTheme extends owebp_Base {
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
					'value' => 'black-tie',
					'title' => 'black-tie' 
			),
			array (
					'value' => 'blitzer',
					'title' => 'blitzer' 
			),
			array (
					'value' => 'cupertino',
					'title' => 'cupertino' 
			),
			array (
					'value' => 'dark-hive',
					'title' => 'dark-hive' 
			),
			array (
					'value' => 'dot-luv',
					'title' => 'dot-luv' 
			),
			array (
					'value' => 'eggplant',
					'title' => 'eggplant' 
			),
			array (
					'value' => 'excite-bike',
					'title' => 'excite-bike' 
			),
			array (
					'value' => 'flick',
					'title' => 'flick' 
			),
			array (
					'value' => 'hot-sneaks',
					'title' => 'hot-sneaks' 
			),
			array (
					'value' => 'humanity',
					'title' => 'humanity' 
			),
			array (
					'value' => 'le-frog',
					'title' => 'le-frog' 
			),
			array (
					'value' => 'mint-choc',
					'title' => 'mint-choc' 
			),
			
			array (
					'value' => 'overcast',
					'title' => 'overcast' 
			),
			
			array (
					'value' => 'pepper-grinder',
					'title' => 'pepper-grinder' 
			),
			array (
					'value' => 'redmond',
					'title' => 'redmond' 
			),
			array (
					'value' => 'smoothness',
					'title' => 'smoothness' 
			),
			array (
					'value' => 'south-street',
					'title' => 'south-street' 
			),
			array (
					'value' => 'start',
					'title' => 'start' 
			),
			array (
					'value' => 'sunny',
					'title' => 'sunny' 
			),
			array (
					'value' => 'swanky-purse',
					'title' => 'swanky-purse' 
			),
			array (
					'value' => 'trontastic',
					'title' => 'trontastic' 
			),
			array (
					'value' => 'ui-darkness',
					'title' => 'ui-darkness' 
			),
			array (
					'value' => 'ui-lightness',
					'title' => 'ui-lightness' 
			),
			array (
					'value' => 'vader',
					'title' => 'vader' 
			) 
	);
}
?>