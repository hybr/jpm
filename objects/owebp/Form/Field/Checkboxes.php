<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_Checkboxes extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */
                $this->setOptionDefault('dataClassName', '');

                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {
		$dataClassName = $this->getOption('dataClassName');
		if ($dataClassName == '') { return 'data source mising'; }
		$dataClassInstance = new $dataClassName();

		/* option are content  of select tag */
		$selectOptionsList = '';
		$currentValue = $this->getOption('value');
                foreach ( $dataClassInstance->getTable () as $r ) {
			$checked = '';
                        if ($r['value'] == $currentValue) {
                                $checked = 'checked';
                        }
			$oneBox = '<label>';
			$input = new owebp_Html_Input(array(
				'type' => 'checkbox',
				'name' => $this->getOption('name') . '[]',
				'content' => $r['value'],
				'checked' => $checked,
			));
			$input->setOption('type','checkbox');
			$oneBox .= $input->get();
			$oneBox .= $r['title'];
			$oneBox .= '</label>';
			$selectOptionsList .= $oneBox;
                } /* foreach ( $dataClassInstance->getTable () as $r ) */

		/* return with cover */
		return (new owebp_Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $selectOptionsList,
		)))->get();

	}

} /* class */
