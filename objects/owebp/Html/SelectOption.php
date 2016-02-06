<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class owebp_Html_SelectOption extends owebp_Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('value', '');
		$this->setOptionDefault('selected', '');

		/* update options with input */
		parent::__construct($opts);

		/* these options can not be changed */
		$this->setOptionDefault('tag', 'option');
		$this->setOptionDefault('class', 'jpm-html-select-option ui-menu-item');
	}

} /* class */
?>
