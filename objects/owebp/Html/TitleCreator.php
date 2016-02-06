<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_TitleCreator extends owebp_Root { 

	function __construct($opts = array()) {
		/* define options  and their defaults */
		$this->setOptionDefault('string', '');

		/* update options with input */
		parent::__construct($opts);
	}

	/* convert string with underscores as Title */
	public function get() {
		$ta = split( '_', $this->getOption('string'));
		$rS = '';
		foreach ( $ta as $w ) {
			$rS .= ' ' . ucfirst ( strtolower ( $w ) );
		}
		return $rS;
	}


} /* class */
?>
