<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_Help extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('text', '');
                $this->setOptionDefault('position', '');
                $this->setOptionDefault('fieldName', 'NoFieldName');

                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {
		if ($this->getOption('text') == '') {
			return '';
		}
		$contentHtmlTag = new owebp_Html_Tag(array(
                        'tag' => 'span',
			'id' => $this->getOption('fieldName'),
                        'content' => '<b>Help</b>: ' . $this->getOption('text'),
                ));
		/* return with cover */
                return (new owebp_Html_FormFieldComponentCover(array(
                        'position' => $this->getOption('position'),
                        'content' => $contentHtmlTag->get(),
                )))->get();
	}

} /* class */
