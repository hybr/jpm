<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_Widget extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('title', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('help', '');
                $this->setOptionDefault('multiple', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('dataClassName', '');

                $this->setOptionDefault('jpm_foreign_collection', '');
                $this->setOptionDefault('jpm_foreign_search_fields', '');
                $this->setOptionDefault('jpm_foreign_title_fields', '');

                $this->setOptionDefault('layout', 'stack'); /* values: stack, left_title */
                $this->setOptionDefault('inputTag', 'select'); /* values: select, input */

                /* update options with input */
                parent::__construct($opts);
        }


	public function show() {
                $rStr = '';

                /* label */
		$position = 'top';
		if ($this->getOption('layout') == 'left_title') {
			$position = 'left';
		}
                $rStr .= (new owebp_Form_Field_Label(array(
                        'title' => $this->getOption('title'),
                        'name' => $this->getOption('name'),
                        'required' => $this->getOption('required'),
			'position' => $position,
                )))->show();

                /* help */
                $helpText = $this->getOption('help');
		if (in_array($this->getOption('inputTag'), array('select', 'radios', 'checkboxes'))) {
	                $dataClassName = $this->getOption('dataClassName');
	                if ($dataClassName == '') { return 'data source mising'; }
	                $dataClassInstance = new $dataClassName();
                	if ($dataClassInstance->help != '') { 
				$helpText .= $listInstance->help; 
			}
		}
		if ($this->getOption('inputTag') == 'foreign_key') {
			$t = (new owebp_TitleCreator(array( 'string' => $this->getOption('jpm_foreign_collection'))))->get();
			$helpText .= 'If required please add <a target="_blank" href="/' 
				. $this->getOption('jpm_foreign_collection') 
				. '/create">'. $t .'</a>';
		}
                $rStr .= (new owebp_Form_Field_Help(array(
                        'text' => $helpText,
			'position' => $position,
                )))->show();


		/* input form field */
		$fieldClass = 'owebp_Form_Field_' . ucfirst(strtolower($this->getOption('inputTag')));
		if (strtolower($this->getOption('inputTag')) == 'foreign_key') {
			$fieldClass = 'owebp_Form_Field_ForeignKey';
		}
		if (strtolower($this->getOption('inputTag')) == 'geonames_city') {
			$fieldClass = 'owebp_Form_Field_GeoNamesCity';
		}
		if (strtolower($this->getOption('inputTag')) == 'file_list') {
			$fieldClass = 'owebp_Form_Field_FileList';
		}

		$allOptions = array(
			'name' => $this->getOption('name'),
			'size' => $this->getOption('size'),
			'value' => $this->getOption('value'),
			'required' => $this->getOption('required'),
			'position' => $position,
		);
		if (in_array($this->getOption('inputTag'), array('select', 'radios', 'checkboxes'))) {
			$allOptions['dataClassName'] = $this->getOption('dataClassName');
		} else if (in_array($this->getOption('inputTag'), array('foreign_key', 'readonly'))) {
			$allOptions['jpm_foreign_collection'] = $this->getOption('jpm_foreign_collection');
			$allOptions['jpm_foreign_search_fields'] = $this->getOption('jpm_foreign_search_fields');
			$allOptions['jpm_foreign_title_fields'] = $this->getOption('jpm_foreign_title_fields');
		} else if ($this->getOption('inputTag') == 'text') {
			$allOptions['rows'] = $this->getOption('rows');
			$allOptions['cols'] = $this->getOption('cols');
		}
		$rStr .= (new $fieldClass($allOptions))->show();

		/* return with cover */
		return (new owebp_Html_FormFieldCover(array(
			'content' => $rStr,
		)))->get();
	}

} /* class */
