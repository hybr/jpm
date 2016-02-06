<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_Select extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('name', '');
                $this->setOptionDefault('size', '');
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('multiple', '');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'bottom'); /* possible values bottom, right */
                $this->setOptionDefault('dataClassName', '');

                /* update options with input */
                parent::__construct($opts);
        }

                /* select start */
		/*
                $rStr .= '<select class="ui-menu ui-widget ui-state-default ui-state-hover ui-state-focus" ';
                $rStr .= $this->showCommonAttributes ($field);
                $rStr .= ' size="' . $field ['select_tag_hight'] . '"';
                $rStr .= $this->showValidators ($field);
                if ($field ['multiple'] != 0) {
                        $rStr .= ' multiple="multiple"';
                }
                $rStr .= ' >';
               	*/ 
                /* options */
		/*
                foreach ( $listInstance->getTable () as $r ) {
                        $rStr .= '<option ';
                        $rStr .= ' class="ui-menu-item"';
                        $rStr .= ' value="' . $r ['value'] . '"';
                        if ($r ['value'] == $value) {
                                $rStr .= ' selected="selected"';
                        }
                        $rStr .= ' >';
                        $rStr .= $r ['title'];
                        $rStr .= '</option>';
                }
		*/
                /* options */
                // $rStr .= '</select>';


	public function show() {
		$dataClassName = $this->getOption('dataClassName');
		if ($dataClassName == '') { return 'data source mising'; }
		$dataClassInstance = new $dataClassName();

		/* option are content  of select tag */
		$selectOptionsList = '';
		$currentValue = $this->getOption('value');
                foreach ( $dataClassInstance->getTable () as $r ) {
			$selected = '';
                        if ($r['value'] == $currentValue) {
                                $selected = 'selected';
                        }
			$contentHtmlTag = new owebp_Html_SelectOption(array(
				'value' => $r['value'],
				'content' => $r['title'],
				'selected' => $selected,
			));
			$selectOptionsList .= $contentHtmlTag->get();
                } /* foreach ( $dataClassInstance->getTable () as $r ) */

		/* select */
                if($this->getOption('multiple') == 1) {
			$this->setOption('multiple', 'multiple');
		}
		$selectHtmlTag = new owebp_Html_Select(array(
			'name' => $this->getOption('name'),
			'id' => $this->getOption('name'),
                	'size' => $this->getOption('size'),
                	'multiple' => $this->getOption('multiple'),
                	'required' => $this->getOption('required'),
			'content' => $selectOptionsList,
		));

		/* return with cover */
		return (new owebp_Html_FormFieldComponentCover(array(
			'position' => $this->getOption('position'),
			'content' => $selectHtmlTag->get(),
		)))->get();

	}

} /* class */
