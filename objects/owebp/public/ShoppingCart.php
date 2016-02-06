<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_ShoppingCart extends owebp_Base {
	function __construct() {
		$this->collectionName = 'shopping_cart';
	} /* __construct */
	
	public $fields = array (
		'item' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'item',
			'foreign_search_fields' => 'UPC,title,summary',
			'foreign_title_fields' => 'UPC,title,summary'
		),

		'supplier' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'organization',
			'foreign_search_fields' => 'abbreviation,name',
			'foreign_title_fields' => 'abbreviation,name'
		),

		'price' => array(),
		'price_currency' => array(),
		'price_per' => array(),
		'price_per_unit' => array(),

		'quantity' => array(
			'type' => 'number',
			'required' => 1,
		),

		'appointment' => array(),

		'session_used' => array(),

 		'status' => array (
                      'type' => 'container',
                      'show_in_list' => 0,
                      'fields' => array (
				'flag' => array (
					'type' => 'list',
					'list_class' => 'owebp_ShoppingCartItemStatus',
					'input_mode' => 'selecting',
					'default' => 'Selected For Purchase',
				),
				'date' => array (
					'type' => 'date' ,
					'required' => 1,
				),
				'time' => array (
					'type' => 'time' ,
					'required' => 1,
				),
			)
		),

	); /* fields */
	

	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';

		return $rStr;
	}	

	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr = '';

		/* if $_POST has item to add in cart do that */
		if ($_POST['item'] != '') {
			array_push($_POST['status'], array('Selected For Purchase', date(), date()));
			$rStr .= $this->save(array());
		}
		/* show items in cart for payment */
                $rStr .= '<ul class="jpmPns">';
                foreach ( $docCursor as $doc ) {
//echo '<pre>'; print_r($doc); echo '</pre>';
                        $rStr .= '<li>' . '<ul>';
			$pasDoc = $this->getDocumentById ( 'item', (string) $doc ['item'] );
                        /* make sure item is for sale */
                        $forSale = false;
                        foreach ( $pasDoc ['price'] as $price ) {
				if (strpos ( $price ['for'], 'Sale' )) { $forSale = true; }
				if (! empty ( $pasDoc ) && $forSale) {
                                                if ($pasDoc ['manufacturar'] == 'COMMON_ITEM') {
                                                        $manufacturarDoc = $_SESSION ['url_domain_org'];
                                                } else {
                                                        $manufacturarDoc = $this->getDocumentById (
                                                                'organization', $pasDoc ['manufacturar']
                                                        );
                                                }
					$rStr .= '<li><a href="/item/present?id=' . ( string ) ($pasDoc ['_id']) . '">';
                        		foreach ( $doc['status'] as $status ) {
						$rStr .= $this->getFieldValue($status, 'flag', '<br />');
					}

					if ( isset($pasDoc['photo']) && !empty($pasDoc['photo'])) {
                                                        $rStr .= '<img height="20%" src="'.$pasDoc['photo'][0]['file_name'].'" />';
					}
					$rStr .= $this->getFieldValue($pasDoc, 'title', '<br />');
					$rStr .= $this->getFieldValue($manufacturarDoc, 'name', '<br />By: ');
					$rStr .= $this->getFieldValue($doc, 'price', '<br />Price: ');
					$rStr .= $this->getFieldValue($doc, 'price_currency', ' ');
					$rStr .= $this->getFieldValue($doc, 'price_per', ' per ');
					$rStr .= $this->getFieldValue($doc, 'price_per_unit', ' ');
					$rStr .= $this->getFieldValue($doc, 'appointment', '<br />Appointment: ');
					$rStr .= '</a></li>';
			
				}
                	}

		} /* foreach ( $docCursor as $doc ) */
                return $rStr . '</ul>';
        }

} /* class */
?>
