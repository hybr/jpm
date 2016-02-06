<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Html" . DIRECTORY_SEPARATOR . "Tag.php";
class owebp_Html_Input extends owebp_Html_Tag { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('size', 33);
		$this->setOptionDefault('position', '');
		$this->setOptionDefault('checked', '');
		$this->setOptionDefault('required', '');

                $this->setOptionDefault('jpm_foreign_collection', '');
                $this->setOptionDefault('jpm_foreign_search_fields', '');
                $this->setOptionDefault('jpm_foreign_title_fields', '');

		/* update options with input */
		parent::__construct($opts);

		/* these options can nt be changed */
		$this->setOptionDefault('tag', 'input');
		$this->setOptionDefault('type', 'text');
		$this->setOptionDefault('value', $this->getOption('content'));
	}

} /* class */
?>
