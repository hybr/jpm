<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_ShoppingCartItemStatus extends owebp_Base {
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
				'value' => 'Selected For Purchase',
				'title' => 'Selected For Purchase',
			),
			array (
				'value' => 'Purchased and Unpaid',
				'title' => 'Purchased and Unpaid',
			),
			array (
				'value' => 'Purchased and Paid',
				'title' => 'Purchased and Paid',
			),
			array (
				'value' => 'Ready To Ship',
				'title' => 'Ready To Ship',
			),
			array (
				'value' => 'Shipped',
				'title' => 'Shipped',
			),
			array (
				'value' => 'Received and Accepted',
				'title' => 'Received and Accepted',
			),
			array (
				'value' => 'Received and Not Accepted',
				'title' => 'Received and Not Accepted',
			),
			array (
				'value' => 'Return Requested',
				'title' => 'Return Requested',
			),
			array (
				'value' => 'Return Approved',
				'title' => 'Return Approved',
			),
			array (
				'value' => 'Return Shipped',
				'title' => 'Return Shipped',
			),
			array (
				'value' => 'Return Received',
				'title' => 'Return Received',
			),
			array (
				'value' => 'Return Refunded',
				'title' => 'Return Refunded',
			),
	);
}
?>
