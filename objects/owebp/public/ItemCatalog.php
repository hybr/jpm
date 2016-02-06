<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_ItemCatalog extends owebp_Base {
	function __construct() {
		$this->collectionName = 'item_catalog';
	} /* __construct */
	public $fields = array (
			'category' => array (
					'required' => 1 
			),
			'summary' => array (),
			'parent_category' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'item_catalog',
					'foreign_search_fields' => 'category,summary',
					'foreign_title_fields' => 'category,summary' 
			),
			'use' => array (
					'type' => 'list',
					'help' => 'Purpose of this catalog category',
					'list_class' => 'owebp_ItemFor',
					'input_mode' => 'selecting',
					'show_in_list' => 1,
					'default' => 'Make and Sale',
					'required' => 1 
			),
			'pas' => array (
					'type' => 'container',
					'show_in_list' => 0,
					'fields' => array (
							'pas_id' => array (
									'type' => 'foreign_key',
									'foreign_collection' => 'item',
									'foreign_search_fields' => 'title,summary',
									'foreign_title_fields' => 'type,title,manufacturar',
									'help' => 'PAS = Product and Service' 
							),
							'live' => array (
				                                'type' => 'list',
				                                'list_class' => 'owebp_ItemLiveType',
				                                'input_mode' => 'clicking',
				                                'show_in_list' => 1,
				                                'default' => 'Proposed',
				                        ),
					) 
			),
			'profit_required' => array (
					'type' => 'number',
					'required' => 1,
					'default' => 0,
					'help' => 'Number as percentage for profit in category' 
			),
		'delivery' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'area' => array(),
				'method' => array (
		                	'type' => 'list',
		                        'list_class' => 'owebp_ItemDeliveryMethod',
		                        'input_mode' => 'selecting',
					'multiple' => 1,
		                        'default' => 'Post',
				),
			) 
		),
	); /* fields */
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
		
		$rStr .= '<table class="ui-widget">';
		$rStr .= '<tr><td class="ui-widget-header" colspan="2"><h2>' . $doc ['category'] . '</h2></td></tr>';
		$rStr .= '<tr class="ui-widget-content"><td class="jpmContentPadding" colspan="2">' . $doc ['summary'] . '</td></tr>';
		
		foreach ( $doc ['item'] as $item ) {
			if (isset ( $item ['id'] ) && $item ['id'] != '') {
				$itemDoc = $this->getDocumentById ( 'item', $item ['id'] );
				
				$rStr .= '<tr class="ui-widget-content">';
				
				$rStr .= '<td class="jpmContentPadding">';
				$rStr .= '<a href="/item/present?id=' . ( string ) ($item ['id']) . '">' . $itemDoc ['title'] . '</a>';
				$rStr .= '</td>';
				
				$rStr .= '<td class="jpmContentPadding">';
				$rStr .= $itemDoc ['summary'];
				$rStr .= '</td>';
				$rStr .= '</tr>';
			}
		}
		
		$rStr .= '</table>';
		return $rStr;
	}
	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr = '<ul class="jpmPns">';
		foreach ( $docCursor as $doc ) {
			$rStr .= '<li>' . $doc ['category'] . '<ul>';
			foreach ( $doc ['pas'] as $pas ) {
				if (isset ( $pas ['pas_id'] )) {
					$pasDoc = $this->getDocumentById ( 'item', $pas ['pas_id'] );
					/* make sure item is for sale */
					$forSale = false;
					foreach ( $pasDoc ['price'] as $price ) {
						if (strpos ( $price ['for'], 'Sale' )) {
							$forSale = true;
						}
					}
					if (! empty ( $pasDoc ) && $forSale) {
						if ($pasDoc ['manufacturar'] == 'COMMON_ITEM') {
							$manufacturarDoc = $_SESSION ['url_domain_org'];
						} else {
							$manufacturarDoc = $this->getDocumentById ( 
								'organization', $pasDoc ['manufacturar'] 
							);	
						}
						$rStr .= '<li><a href="/item/present?id=' . ( string ) ($pas ['pas_id']) . '">';
						if ( isset($pasDoc['photo']) && !empty($pasDoc['photo'])) {
							$rStr .= '<img height="20%" src="'.$pasDoc['photo'][0]['file_name'].'" />';
						}
						$rStr .= $this->getFieldValue($pasDoc, 'title', '<br />');
						$rStr .= $this->getFieldValue($manufacturarDoc, 'name', '<br />By: ');
						if (isset($doc ['delivery'])) {
							$lStr = '';
							foreach ($doc ['delivery'] as $delivery) {
								$lStr .= $delivery ['area'] . ', ';
							}
							if ($lStr != '') {
								$rStr .= '<br />Delivery Area: ' . trim($lStr, ", "); 
							}
						}
						$rStr .= '</a></li>';
					}
				}
			}
			$rStr .= '</ul></li>';
		}
		
		return $rStr . '</ul>';
	}
} /* class */
?>
