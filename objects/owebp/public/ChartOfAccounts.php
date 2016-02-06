<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_ChartOfAccounts extends owebp_Base {
	function __construct() {
		$this->collectionName = 'chart_of_accounts';
	} /* __construct */
	public $fields = array (
			'title' => array (),
			'summary' => array (),
			'category' => array (
					'required' => 1 
			),
			'parent_account' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'chart_of_accounts',
					'foreign_search_fields' => 'title,summary,category',
					'foreign_title_fields' => 'category,title,summary' 
			),
	); /* fields */
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
		
		$rStr .= '<table class="ui-widget">';
		$rStr .= '<tr><td class="ui-widget-header" colspan="2"><h2>' . $doc ['title'] . '</h2></td></tr>';
		$rStr .= '<tr class="ui-widget-content"><td colspan="2">' . $doc ['summary'] . '</td></tr>';
		$rStr .= '<tr class="ui-widget-content"><td>Category</td><td>' . $doc ['category'] . '</td></tr>';
		if ((isset($doc ['parent_account']) && (string) $doc ['parent_account'] != '') {
			$parentAccount = $this->getDocumentById ( 'chart_of_accounts', $doc ['parent_account'] );
			$rStr .= '<tr class="ui-widget-content"><td>Parent Account</td><td>' . $parentAccount ['title'] . '</td></tr>';
		}
		$rStr .= '</table>';
		return $rStr;
	}
	private function presentBranch ($doc) {
		$rStr = '';
		
		$rStr .= '<table class="ui-widget">';
		$rStr .= '<tr><td class="ui-widget-header" colspan="2"><h2>' . $doc ['title'] . '</h2></td></tr>';
		$rStr .= '<tr class="ui-widget-content"><td colspan="2">' . $doc ['summary'] . '</td></tr>';
		$rStr .= '<tr class="ui-widget-content"><td>Category</td><td>' . $doc ['category'] . '</td></tr>';
		if ((isset($doc ['parent_account']) && (string) $doc ['parent_account'] != '') {
			$parentAccount = $this->getDocumentById ( 'chart_of_accounts', $doc ['parent_account'] );
			$rStr .= '<tr class="ui-widget-content"><td>Parent Account</td><td>' . $parentAccount ['title'] . '</td></tr>';
		}
		$rStr .= '</table>';
		return $rStr;
	}
	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr = '<ul>';
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
							$manufacturarDoc = $this->getDocumentById ( 'organization', $pasDoc ['manufacturar'] );	
						}
						$rStr .= '<li><a href="/item/present?id=' 
								. ( string ) ($pas ['pas_id']) . '">';
						if ( isset($pasDoc['photo']) && !empty($pasDoc['photo'])) {
							$rStr .= '<img height="200px" src="'.$pasDoc['photo'][0]['file_name'].'" />';
						}
						$rStr .= '<br />' . $pasDoc ['title'] . '<br />By: ' . $manufacturarDoc ['name'] . '</a></li>';
					}
				}
			}
			$rStr .= '</ul></li>';
		}
		
		return $rStr . '</ul>';
	}
} /* class */
?>
