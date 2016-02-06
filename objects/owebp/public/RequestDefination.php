<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_RequestDefination extends owebp_Base {
	function __construct() {
		$this->collectionName = 'request_defination';
	}
	public $fields = array (
		'title' => array(
			'required' => 1, 
			'show_in_list' => 1,
		),
		'description' => array(
			'type' => 'string', 
			'required' => 1,  
		),
		'type'  => array (
			'type' => 'list',
			'list_class' => 'owebp_RequestType',
			'input_mode' => 'selecting',
			'default' => 'Service',
			'show_in_list' => 1,
		),
		'category' => array (
                        'type' => 'foreign_key',
                        'foreign_collection' => 'request_defination',
                        'foreign_search_fields' => 'title,description,type',
                        'foreign_title_fields' => 'title,type',
			'default' => '5528f478a93499f610ca7073', /* all account */
			'show_in_list' => 1,
                ),
	);

	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
                $rStr = '';
		$title = $this->getFieldValue($doc, 'title');
		$rStr .= $title;
                return $rStr;
        }

	private function showRequest($docCursor, $doc, $level) {
		$rStr = '';
		$type = $this->getFieldValue($doc, 'type');
		$title = $this->getFieldValue($doc, 'title');
		if ($title == '') {
			$title = 'No Title';
		}
		$description = $this->getFieldValue($doc, 'description');
		if ($type != 'Category') {
			$rStr .= $type . ' : <a href="/request_defination/present?id='.(string)$doc['_id'].'">' . $title . '</a> : ' . $description;
		} else {
			$rStr .= $title . ' : ' . $description;
		}
		$rStrSub = '';
		foreach($docCursor as $subDoc) {
			if ($this->getFieldValue($subDoc, 'category') == (string)$doc['_id']) {
				$rStrSub .= $this->showRequest($docCursor,$subDoc,$level+1);
			};
		}
		if ($rStrSub != '') {
			$rStrSub = '<ul>' . $rStrSub . '</ul>';
		}
		return '<li>' . $rStr . '</li>' . $rStrSub;
	}

	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr = '<ul>';
		$rStr .= $this->showRequest($docCursor, $this->getDocumentById('request_defination', '5528f478a93499f610ca7073'), 1) ;
		return $rStr . '</ul>';
	} /* presentAllDocument */

} /* class */
