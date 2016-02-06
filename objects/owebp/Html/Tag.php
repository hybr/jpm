<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Html_Tag extends owebp_Root { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('tag', '');
		$this->setOptionDefault('content', '');
		$this->setOptionDefault('id', '');
		$this->setOptionDefault('class', '');
		$this->setOptionDefault('style', '');
		$this->setOptionDefault('name', '');

		/* update options with input */
		parent::__construct($opts);
	}

	private function getTagAttribute($attribute) {
		if (trim($this->getOption($attribute)) != '') {
			return ' ' . $attribute . '="' 
				. trim($this->getOption($attribute))
				. '"';
		} else {
			return '';
		}
	}
	private function getTagId() {
		if ($this->getOption('id') != '') {
			return ' id="' 
				. 'jpm_' . str_replace ( ']', '', str_replace ( '[', '_', trim($this->getOption('id')) ) )
				. '"';
		} else {
			return '';
		}
	}

	private function getStartTag() {
		if ($this->getOption('tag') != '') {
			/* $rStr = '<' . $this->getOption('tag') . $this->getTagId(); */
			$rStr = '<' . $this->getOption('tag');
			foreach ($this->getOptions() as $key => $value) {
				if (in_array($key, array('tag','content','id'))) {
					continue;
				}
				$rStr .= $this->getTagAttribute($key);
			} /* foreach($this->getOptions() as $key => $value) */
			$rStr .= ' >';
			return $rStr;
		} else {
			return '';
		}
	}

	private function getEndTag() {
		if ($this->getOption('tag') != '') {
			return '</' . trim($this->getOption('tag'))
				. '>';
		} else {
			return '';
		}
	}


	public function get() {
		if (in_array($this->getOption('tag'), array('input'))) {
			return $this->getStartTag() . $this->getEndTag();
		}
		return $this->getStartTag() . trim($this->getOption('content')) . $this->getEndTag();
	}
} /* class */
?>
