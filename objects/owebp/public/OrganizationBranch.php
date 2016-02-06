<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_organizationBranch extends owebp_Base {
	function __construct() {
		$this->collectionName = 'organization_branch';
	} /* __construct */
	public $fields = array (
			'organization' => array (
					'type' => 'foreign_key',
					'foreign_collection' => 'organization',
					'foreign_search_fields' => 'abbreviation,name',
					'foreign_title_fields' => 'abbreviation,name' 
			),
			'code' => array (
					'type' => 'string',
					'show_in_list' => 1,
					'required' => 1 
			),
			'name' => array (
					'type' => 'string',
					'help' => 'Name of the organization branch',
					'show_in_list' => 1 
			)
	); /* fields */
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
	
		$rStr .= '<table class="ui-widget">';
		$rStr .= '<tr><td class="ui-widget-header" colspan="2"><h2>' . $doc ['name'] . '</h2></td></tr>';
	
		$rStr .= '<tr class="ui-widget-content"><td class="jpmContentPadding" colspan="2">' . $doc ['statement'] . '</td></tr>';
	
		$rStr .= '<tr class="ui-widget-content">';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .=  "Code";
		$rStr .= '</td>';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .= $doc['code'];
		$rStr .= '</td>';
		$rStr .= '</tr>';

		$rStr .= '<tr class="ui-widget-content">';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .=  "Name";
		$rStr .= '</td>';
		$rStr .= '<td class="jpmContentPadding">';
		$rStr .= $doc['name'];
		$rStr .= '</td>';
		$rStr .= '</tr>';
	
		$rStr .= '</table>';
		return $rStr;
	}	
	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr = '<ul>';
		foreach ( $docCursor as $doc ) {
			$rStr .= '<li><a href="/organization_branch/present?id=' . (string) ($doc['_id']) . '" >'
					. $doc['code'] . '</a>: ' . $doc['name'];
			$org = $this->getDocumentById('organization', $doc['organization']);
				$rStr .= ' | Organization: <a href="/organization/present?id='
						. ( string ) ($org ['_id']) . '" >'
								. $org ['name'] . '</a>';
			$rStr .=  '</li>';
		}
		return $rStr . '</ul>';
	}	
} /* class */
?>
