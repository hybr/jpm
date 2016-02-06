<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";

class owebp_InputSelect extends owebp_Base {

	public $value = '';

	public $field = array();

	public function show() {
		$rStr = '';

		$listInstance = new $this->field ['list_class'] ();
		if ($listInstance->help != '') {
			$field['help'] .= $listInstance->help;
		}

		$rStr .= $this->showLabel ($field);
		$rStr .= $this->showHelp ($field);

		/* select start */
		$rStr .= '<select class="ui-menu ui-widget ui-state-default ui-state-hover ui-state-focus" ';
		$rStr .= $this->showCommonAttributes ($field);
		$rStr .= ' size="' . $field ['select_tag_hight'] . '"';
		$rStr .= $this->showValidators ($field);
		if ($field ['multiple'] != 0) {
			$rStr .= ' multiple="multiple"';
		}
		$rStr .= ' >';
		
		/* options */
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
		/* options */
		$rStr .= '</select>';
		return $rStr;
	}
}; /* class */
