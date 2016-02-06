<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_Time extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */

                /* update options with input */
                parent::__construct($opts);
                // $this->setOptionDefault('class', 'jpm_timepicker');
        }

	public function show() {
		$inputHtmlTag = new owebp_Html_Input(array(
			'name' => $this->getOption('name'),
			'id' => $this->getOption('name'),
                	'size' => $this->getOption('size'),
                	'class' => $this->getOption('class'),
                	'required' => $this->getOption('required'),
			'content' => $this->getOption('value'),
		));

		/* need a jquery plugin for time  and then remve this */
		$inputHtmlTag->setOptionDefault('type', 'time');

		/* return with cover */
		return (new owebp_Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $inputHtmlTag->get(),
		)))->get();

	}

} /* class */
