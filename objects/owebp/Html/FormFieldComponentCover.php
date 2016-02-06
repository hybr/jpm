<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class owebp_Html_FormFieldComponentCover extends owebp_Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('position', '');

		/* update options with input */
		parent::__construct($opts);

		/* these options can nt be changed */
		$coverHtmlTagName = 'span';
		/* values include top, bottom, left, right */
                if ($this->getOption('position') == 'bottom'
			|| $this->getOption('position') == 'top'
		) {
                        $coverHtmlTagName = 'div';
                }

		$this->setOptionDefault('tag', $coverHtmlTagName);
		$this->setOptionDefault('class', 'jpm-html-form-field-component-cover');
	}

} /* class */
?>
