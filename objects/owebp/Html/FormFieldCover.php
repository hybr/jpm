<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class owebp_Html_FormFieldCover extends owebp_Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */

		/* update options with input */
		parent::__construct($opts);

		/* these options can not be changed */
		$this->setOptionDefault('tag', 'div');
		$this->setOptionDefault('class', 'jpm-html-form-field-cover');
	}

} /* class */
?>
