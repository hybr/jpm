<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_Textarea extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('rows', '');
                $this->setOptionDefault('cols', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */

                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {
		$textareaHtmlTag = new owebp_Html_Textarea(array(
			'name' => $this->getOption('name'),
			'id' => $this->getOption('name'),
                	'rows' => $this->getOption('rows'),
                	'cols' => $this->getOption('cols'),
                	'required' => $this->getOption('required'),
			'content' => $this->getOption('value'),
		));

		/* return with cover */
		return (new owebp_Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $textareaHtmlTag->get(),
		)))->get();

	}

} /* class */
