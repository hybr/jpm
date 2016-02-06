<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_FileList extends owebp_Root {

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

/*
        private function showFileList($value, $field) {
                $rStr = '';
                $rStr .= $this->showLabel ($field);
                $rStr .= $this->showHelp ($field);
                $rStr .= '<a target="_blank" href="/file_upload/create">Upload</a> ' . 'file if neeed <br />' ;
                $rStr .= '<select class="ui-menu ui-widget ui-state-default ui-state-hover ui-state-focus" ';
                $rStr .= $this->showCommonAttributes ($field);
                $rStr .= ' size="' . $field ['select_tag_hight'] . '"';
                $rStr .= $this->showValidators ($field);
                if ($field ['multiple'] != 0) {
                        $rStr .= ' multiple="multiple"';
                }
                $rStr .= ' >';

                $target_folder = '/hybr/websites/jpm/public/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'];
                if(is_dir($target_folder) && is_readable($target_folder)) {
                        if ($handle = opendir($target_folder)) {
                        $rStr .= '<option value=""';
                        $rStr .= ' class="ui-menu-item"';
                        if ($value == "") {
                                $rStr .= ' selected="selected"';
                        }
                        $rStr .= ' >No File</option>';
                        while (false !== ($entry = readdir($handle))) {
                                if ($entry == '.') { continue; }
                                if ($entry == '..') { continue; }
                                $rStr .= '<option ';
                                $rStr .= ' class="ui-menu-item"';
                                $rStr .= ' value="/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'] . '/' . $entry . '"';
                                if ('/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'] . '/' . $entry == $value) {
                                        $rStr .= ' selected="selected"';
                                }
                                $rStr .= ' >';
                                $rStr .= $entry;
                                $rStr .= '</option>';
                        }
                        closedir($handle);
                        }
                }

                $rStr .= '</select>';
                return $rStr;
        }

*/


	public function show() {
		$dataClassName = $this->getOption('dataClassName');
		if ($dataClassName == '') { return 'data source mising'; }
		$dataClassInstance = new $dataClassName();

		/* option are content  of select tag */
		$target_folder = '/hybr/websites/jpm/public/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'];
		$rStr = '';
                if(is_dir($target_folder) && is_readable($target_folder)) {
                        if ($handle = opendir($target_folder)) {
                        $rStr .= '<option value=""';
                        $rStr .= ' class="ui-menu-item"';
                        if ($value == "") {
                                $rStr .= ' selected="selected"';
                        }  
                        $rStr .= ' >No File</option>';
                        while (false !== ($entry = readdir($handle))) {
                                if ($entry == '.') { continue; }
                                if ($entry == '..') { continue; }
                                $rStr .= '<option ';
                                $rStr .= ' class="ui-menu-item"';
                                $rStr .= ' value="/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'] . '/' . $entry . '"';
                                if ('/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'] . '/' . $entry == $value) {
                                        $rStr .= ' selected="selected"';
                                } 
                                $rStr .= ' >';
                                $rStr .= $entry;
                                $rStr .= '</option>';
                        }
                        closedir($handle);
                        }
                }

		$selectOptionsList = '';
		$currentValue = $this->getOption('value');

		$target_folder = '/hybr/websites/jpm/public/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'];
		while (false !== ($entry = readdir($handle))) {
			if ($entry == '.') { continue; }
			if ($entry == '..') { continue; }
			$thisValue = '/file/' . ( string ) $_SESSION ['url_domain_org'] ['_id'] . '/' . $entry;
			$selected = '';
                        if ($thisValue == $currentValue) {
                                $selected = 'selected';
                        }
			$contentHtmlTag = new owebp_Html_SelectOption(array(
				'value' => $thisValue,
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
