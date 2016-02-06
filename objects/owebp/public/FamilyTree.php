<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_FamilyTree extends owebp_Base {
	function __construct() {
		$this->collectionName = 'family_tree';
	} /* __construct */
	public $fields = array (
		'gedcom_file_name' => array ('type' => 'file_list', 'required' => 1),
		'primary' => array (
			'type' => 'list',
			'help' => 'Is this the main GEDCOM database?',
			'list_class' => 'owebp_Boolean',
			'input_mode' => 'clicking',
			'show_in_list' => 1,
			'default' => 'False',
		),
	); /* fields */

	private $gedcomDbInstance = NULL;
	private $gedcomFileName = '';
	private $gedcomFileRecordId = '';	

	private function getFromObjectFunction($obj, $method) {
		/*
		if (is_object($obj) && method_exists($obj, $method) && is_callable($obj, $method)) {
			return $obj->$method();
		}
		*/
		if (is_object($obj)) {
			return $obj->$method();
		}
		return '';
	}

	private function getAttributePresentation($attribute) {
		$attributeType =  $this->getFromObjectFunction($attribute, 'getType');
		$attributeValue =  '';
		$attributePlace =  $this->getFromObjectFunction($attribute, 'getPlac');
		$attributePlace =  $this->getFromObjectFunction($attributePlace, 'getPlac');
		$attributeDate =  $this->getFromObjectFunction($attribute, 'getDate');
		$attributeSource =  $this->getFromObjectFunction($attribute, 'getSour');
		$attributeNote =  $this->getFromObjectFunction($attribute, 'getNote');

		$rStr = '<br />';
		$attributeAddress = '';
		if ($attributeType == 'RESI') { 
			$rStr .= 'Resident '; 
			$attributeValue =  $this->getFromObjectFunction($attribute, 'getAttr');
			$attributeAddress =  $this->getFromObjectFunction($attribute, 'getAddr');
			$attributeAddress =  $this->getFromObjectFunction($attributeAddress, 'getAddr');
		}
		if ($attributeType == 'CAST') { 
			$rStr .= 'Caste '; 
			$attributeValue =  $this->getFromObjectFunction($attribute, 'getAttr');
		}
		if ($attributeType == 'EDUC') { 
			$rStr .= 'Education '; 
			$attributeValue =  $this->getFromObjectFunction($attribute, 'getAttr');
		}
		if ($attributeType == 'OCCU') { 
			$rStr .= 'Occupation '; 
			$attributeValue =  $this->getFromObjectFunction($attribute, 'getAttr');
		}
		if ($attributeType == 'DEAT') { $rStr .= 'Death '; }
		if ($attributeType == 'BIRT') { $rStr .= 'Birth '; }
		if ($attributeValue != '') {
			$rStr .= ' ' . $attributeValue;
		}
		if ($attributeDate != '') {
			$rStr .= ' on ' . $attributeDate;
		}
		if ($attributeAddress != '') {
			$rStr .= ' at ' . $attributeAddress;
		}
		if ($attributePlace != '') {
			$rStr .= ' at ' . $attributePlace;
		}
		if (!empty($attributeSource)) {
			$rStr .= ' source(' . $attributeSource . ')';
		}
		if (!empty($attributeNote)) {
			$rStr .= ' Note: ' . $attributeNote;
		}
		return $rStr;
	}

	private function getGedcomFileName ($doc) {
		if (array_key_exists('gedcom_file_name', $doc)) {
                	return __DIR__ . '/../../../public/'.  $doc['gedcom_file_name'];
		}
		return '';
	}

	private function getGedcomFileId ($doc) {
		if (array_key_exists('gedcom_file_name', $doc)) {
			return (string)$doc['_id'];
		}
		return '';
	}


	private function getGedcomParserInstance($doc) {
		$this->gedcomFileName = $this->getGedcomFileName($doc);
		if ($this->gedcomFileName != '') {
			$parser = new \PhpGedcom\Parser();
			$this->gedcomDbInstance = $parser->parse($this->gedcomFileName);
			$this->gedcomFileRecordId = $this->getGedcomFileId($doc);
			return true;
		}
		return false;	
	}

	private function getIndividualLink($individual) {
		$nameObj =  $this->getFromObjectFunction($individual, 'getName');
		$name =  $this->getFromObjectFunction(current($nameObj), 'getName');
		$nickName =  $this->getFromObjectFunction(current($nameObj), 'getNick');

		if ($nickName != '')  { $nickName = ', ' . $nickName; }
		return ' <a href="/family_tree/present?id=' . $this->gedcomFileRecordId . '&iid=' 
			. $individual->getId() . '">' 
			. str_replace('/','',$name) 
			. $nickName
		. '</a> ';
	}

	private function getIndividualLinkById($id) {
		foreach ($this->gedcomDbInstance->getIndi() as $individual) {
			if ($individual->getId() == $id) {
				return $this->getIndividualLink($individual);
			}
		}
		return '';
	}

	private function getFamilyPresentation($familyId, $iid) {
		$rStr = '';
		foreach($this->gedcomDbInstance->getFam() as $fam) {
			if ($fam->getId() == $familyId) {
				$husbandId =  $this->getFromObjectFunction($fam, 'getHusb');
				$wifeId =  $this->getFromObjectFunction($fam, 'getWife');
				/*echo 'Fam <pre>'; print_r($fam); echo '</pre>' . $husbandId; */
				if ($iid == $husbandId) {
					$rStr .= $this->getIndividualLinkById($husbandId);
					$rStr .= ' and ' . $this->getIndividualLinkById($wifeId);
				} else {
					$rStr .= $this->getIndividualLinkById($wifeId);
					$rStr .= ' and ' . $this->getIndividualLinkById($husbandId);
				}

				$children =  $this->getFromObjectFunction($fam, 'getChil');
	 			$rStr .= '<ul>';
				foreach ($children as $childId) {
					$rStr .= '<li>';
					$rStr .= $this->getIndividualDecendentById($childId);
					$rStr .= '</li>';
				}
				$rStr .= '</ul>';
			}
		}
		return $rStr;
	}

	private function getIndividualDecendentById($id) {
		$rStr = '';
		foreach ($this->gedcomDbInstance->getIndi() as $individual) {
			if ($individual->getId() == $id) {
				foreach($this->getFromObjectFunction($individual, 'getFams') as $familyObj) {
					$rStr .= $this->getFamilyPresentation($familyObj->getFams(), $id);
				}
				if ($rStr == '') {
					$rStr .= $this->getIndividualLink($individual);
				}
			}
		}
		return $rStr;
	}
	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';
		$familyIds = array();
		$relImageDir = __DIR__ . '/../../../public/file/'.  (string) $_SESSION['url_domain_org']['_id'];
		$webImageDir = '/file/'.  (string) $_SESSION['url_domain_org']['_id'];
		$gedComParsingSuccess = $this->getGedcomParserInstance($doc);
		if (!$gedComParsingSuccess) {
			return 'Unable to get GEDCOM db instance';
		}
		foreach ($this->gedcomDbInstance->getIndi() as $individual) {
			if ($individual->getId() == $_GET['iid']) {
				$imgStr = '';
				foreach (glob($relImageDir . '/' . $_GET['iid'] . '-*' ) as $filename) { 
					$imgStr .= '<li><img src="'.$webImageDir .'/' . basename($filename) .'"  /><br />';
					$imgStr .= str_replace('_', ' ', split('\.',split('-',basename($filename))[1])[0]) . '</li>';
				}
				if ($imgStr != '') {
					$rStr .= '<ul class="jpmPns">'  . $imgStr . '</ul>';
				}
				$rStr .= 'Name : ' . current($individual->getName())->getName();
				$indiNickName = current($individual->getName())->getNick();
				if ($indiNickName != '') {
					$rStr .= '<br />Nickname : ' . $indiNickName;
				}
				$rStr .= '<br />Gender : ' . $individual->getSex();
				foreach($individual->getEven() as $event) {
					$rStr .= $this->getAttributePresentation($event);
				}
				foreach($individual->getAttr() as $attribute) {
					$rStr .= $this->getAttributePresentation($attribute);
				}
				/* family id with spouse */
				$FamilyWithSpouse =  $this->getFromObjectFunction($individual, 'getFams');
				if ($FamilyWithSpouse != '') {
					$rStr .= '<hr /><h2>Family with Spouse</h2><ul>';
					foreach($FamilyWithSpouse as $familyObj) {
						// echo 'FamilyObj <pre>'; print_r($familyObj); echo '</pre>'; 
						$rStr .= $this->getFamilyPresentation($familyObj->getFams(), $individual->getId());
					}
					$rStr .= '</ul>';
				}
				/* parents family */
				$FamilyWithParents =  $this->getFromObjectFunction($individual, 'getFamc');
				if ($FamilyWithParents != '') {
					$rStr .= '<hr /><h2>Family with Parents</h2> <ul>';
					/*echo 'FamilyWithParents <pre>'; print_r($FamilyWithParents); echo '</pre>'; */
					foreach($FamilyWithParents as $familyObj) {
						/* echo 'FamilyObj <pre>'; print_r($familyObj); echo '</pre>'; */
						$rStr .= $this->getFamilyPresentation($familyObj->getFamc(), $individual->getId());
					}
					$rStr .= '</ul>';
				}
				$rStr .= '<hr />Last modified : ' . current($individual->getChan());
				/* echo 'Indi <pre>'; print_r($individual); echo '</pre>'; */
			}
		}
		return $rStr;
	}	

	public function presentAllDocument($subTaskKeyToSave, $fields, $docCursor) {
		$rStr = '';
		$fileName = '';
		$machedDoc = array();
                foreach ( $docCursor as $doc ) {
			if ($doc['primary'] == 'True') {
				$machedDoc = $doc;
				break;
			}
		}
		if (empty($machedDoc)) {
			return "No primary gedcom file assigned";
		}

		$gedComParsingSuccess = $this->getGedcomParserInstance($machedDoc);
		if ($gedComParsingSuccess) {
			$rStr .= 'Please click on the name listd below to know more about the person <br /><br />';
			foreach ($this->gedcomDbInstance->getIndi() as $individual) {
				$rStr .= $this->getIndividualLink($individual) . '<br />';
			}
		}

                return $rStr;
        }

} /* class */
?>
