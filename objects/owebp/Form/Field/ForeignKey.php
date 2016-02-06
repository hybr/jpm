<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_ForeignKey extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */

                $this->setOptionDefault('jpm_foreign_collection', '');
                $this->setOptionDefault('jpm_foreign_search_fields', '');
                $this->setOptionDefault('jpm_foreign_title_fields', '');

                /* update options with input */
                parent::__construct($opts);
                $this->setOptionDefault('class', 'jpm_foreign_key_input');
        }

	public function show() {
		$inputHtmlTag = new owebp_Html_Input(array(
			'name' => $this->getOption('name'),
			'id' => $this->getOption('name'),
                	'size' => $this->getOption('size'),
                	'class' => $this->getOption('class'),
                	'required' => $this->getOption('required'),
			'content' => $this->getOption('value'),

			'jpm_foreign_collection' => $this->getOption('jpm_foreign_collection'),
			'jpm_foreign_search_fields' => $this->getOption('jpm_foreign_search_fields'),
			'jpm_foreign_title_fields' => $this->getOption('jpm_foreign_title_fields'),
		));


		$valueInstance = new owebp_Form_Field_Value(array(
                        'value' => $this->getOption('value'),
                        'jpm_foreign_collection' => $this->getOption('jpm_foreign_collection'),
                        'jpm_foreign_title_fields' => $this->getOption('jpm_foreign_title_fields'),
                ));

		/* return with cover */
		return (new owebp_Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $inputHtmlTag->get() . $valueInstance->get(),
		)))->get();

	}

} /* class */
