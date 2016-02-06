<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_WebPageComponent extends owebp_Base {
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
					'value' => 'Learn More',
					'title' => 'Learn More' 
			),
			array (
					'value' => 'Image Slider',
					'title' => 'Image Slider' 
			),
			array (
					'value' => 'Three Media Boxes',
					'title' => 'Three Media Boxes'
			),
			array (
					'value' => 'Paragraph',
					'title' => 'Paragraph' 
			),
			array (
					'value' => 'Video URL Link',
					'title' => 'Video URL Link'
			),
			array (
					'value' => 'Image URL Link',
					'title' => 'Image URL Link'
			),
			array (
					'value' => 'Facebook Link',
					'title' => 'Facebook Link'
			),
			array (
					'value' => 'Contacts',
					'title' => 'Contacts'
			)
	);
}
?>
