<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class owebp_Html_Select extends owebp_Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('size', '');
		$this->setOptionDefault('multiple', '');
		$this->setOptionDefault('selected', '');
		$this->setOptionDefault('position', '');
		$this->setOptionDefault('required', '');

		/* update options with input */
		parent::__construct($opts);

		/* these options can nt be changed */
		$this->setOptionDefault('tag', 'select');
		$this->setOptionDefault('class', 'jpm-html-select ui-menu ui-widget ui-state-default ui-state-hover ui-state-focus');
	}

} /* class */
?>
