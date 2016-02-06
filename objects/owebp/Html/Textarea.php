<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class owebp_Html_Textarea extends owebp_Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('rows', 3);
		$this->setOptionDefault('cols', 30);
		$this->setOptionDefault('position', '');
		$this->setOptionDefault('required', '');

		/* update options with input */
		parent::__construct($opts);

		/* these options can nt be changed */
		$this->setOptionDefault('tag', 'textarea');
	}

} /* class */
?>
