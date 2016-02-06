<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_InputForm extends owebp_Base {
	public $form = array (
		'label' => 'top', /* two values left and top */
		'title' => '' 
	);
	private function nameToTagId($name) {
		return 'fi_' . str_replace ( ']', '', str_replace ( '[', '_', $name ) );
	}
	private function showReadOnlyValue($value, $field) {
		$rStr = '';
		if ($field ['type'] == 'foreign_key' && $value != '') {
			$rStr .= $this->showSelectedReadOnlyFieldsFromDocOfCollection ( $value, $field ['foreign_collection'], $field ['foreign_title_fields'] );
		} elseif ($field ['type'] == 'list' && $value != '') {
			$listInstance = new $field ['list_class'] ();
			if ($listInstance->titleValueConversionRequired) {
				foreach ( $listInstance->getTable () as $r ) {
					if ($r ['value'] == $value) {
						$rStr = $r ['title'];
					}
				}
				if ($rStr == '') {
					$rStr = $value;
				}
			} else {
				$rStr = $value;
			}
		} else {
			
			$rStr = $value;
		}
		return $rStr;
	}
	private function showLabel($field) {
		$rStr = '';
		/* label start */
		$rStr .= '<label ';
		$rStr .= ' for="' . $this->nameToTagId ( $field ['name'] ) . '"';
		$rStr .= ' class="ui-widget-header"';
		$rStr .= ' style="padding: 2px;" >';
		$rStr .= '	<span>' . $this->getTitle ( $field ['title'] ) . '</span>';
		if ($field ['required'] == 1) {
			$rStr .= '	<span style="color:red;"> * </span>';
		}
		$rStr .= '</label> ';
		/* label end */
		if ($this->form ['label'] == 'top') {
			$rStr .= '<br />';
		}
		return $rStr;
	}
	private function showHelp($field) {
		$rStr = '';
		/* help */
		if ($field ['help'] != '') {
			$rStr .= '<span style="padding: 2px;">Help: ' . $field ['help'] . '</span><br />';
		}
		return $rStr;
	}
	private function showValidators($field) {
		$rStr = '';
		if ($field ['required'] == 1) {
			$rStr .= ' required';
		}
		if ($field ['minlength'] != - 1) {
			$rStr .= ' minlength="' . $field ['minlength'] . '"';
		}
		if ($field ['maxlength'] != - 1) {
			$rStr .= ' maxlength="' . $field ['maxlength'] . '"';
		}
		return $rStr;
	}
	private function showCommonAttributes($field) {
		$rStr = '';
		$rStr .= ' id="' . $this->nameToTagId ( $field ['name'] ) . '"';
		$rStr .= ' name="' . $field ['name'] . '"';
		if ($field['type'] == 'number') {
			$field ['placeholder'] = 'Number';
		}
		$rStr .= ' placeholder="' . $field ['placeholder'] . '"';
		return $rStr;
	}

	private function showFileList($value, $field) {
		$rStr = '';
		$rStr .= $this->showLabel ($field);
		$rStr .= $this->showHelp ($field);
		/* select start */
		$rStr .= '<a target="_blank" href="/file_upload/create">Upload</a> ' . 'file if neeed <br />' ;
		$rStr .= '<select class="ui-menu ui-widget ui-state-default ui-state-hover ui-state-focus" ';
		$rStr .= $this->showCommonAttributes ($field);
		$rStr .= ' size="' . $field ['select_tag_hight'] . '"';
		$rStr .= $this->showValidators ($field);
		if ($field ['multiple'] != 0) {
			$rStr .= ' multiple="multiple"';
		}
		$rStr .= ' >';
	
		/* options */
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
		/* options */
		$rStr .= '</select>';
		return $rStr;
	}	

	private function showContainer($slfs, $fieldParentName, $subField, $subFieldCounter, $title, $parentUid, $type, $templateId) {
		$rStr = '';
		$fieldSetId = 'rm' . $parentUid . '_' . $subFieldCounter;
		
		$rmBtn = '<span onclick="removeSubFields(\'' . $fieldSetId . '\')">( Remove )</span>';
		
		if ($this->curlsMode == 'Show' || $this->curlsMode == 'Remove') {
			$rmBtn = '';
		}
		
		$rStr .= ' <fieldset id="' . $fieldSetId . '"';
		$rStr .= ' >';
		$rStr .= '<legend><b> ' . $subFieldCounter . ' ' . $this->getTitle ( $title ) . ' </b>' . $rmBtn . '</legend>';
		$rStr .= '<table>';
		$rStr .= $this->showSameLevelFields ( $slfs, $fieldParentName, $subField, $parentUid );
		$rStr .= '</table>';
		$rStr .= '</fieldset>';
		return $rStr;
	}
	private $templateCodes = '';
	private function getDefaultValue($field) {
		$dv = $field ['default'];
		if ($field ['type'] == 'time') {
			$dv = date ( 'H:i', (new MongoDate ())->sec );
		}
		if ($field ['type'] == 'date') {
			$dv = date ( 'Y-M-d', (new MongoDate ())->sec );
		}
		if ($field ['type'] == 'datetime') {
			$dv = date ( 'Y-M-d H:i e', (new MongoDate ())->sec );
		}
		if ($field ['name'] == 'created_by') {
			$dv = $_SESSION ['login_person_id'];
		}
		if ($field ['name'] == 'updated_by') {
			$dv = $_SESSION ['login_person_id'];
		}
		if ($field ['name'] == 'for_org') {
			$dv = ( string ) $_SESSION ['url_domain_org'] ['_id'];
		}
		return $dv;
	}
	public function showSameLevelFields($slfs, $fieldParentName, $rec, $parentUid) {
		$rStr = '';
		$fieldCount = 1;
		foreach ( $slfs as $key => $val ) {
			
			$fieldUid = $parentUid . '_' . $fieldCount;
			
			/* create the dynamic default values */
			$this->fieldDefault ['title'] = $key;
			if ($fieldParentName == '') {
				$this->fieldDefault ['name'] = $key;
			} else {
				$this->fieldDefault ['name'] = $fieldParentName . '[' . $key . ']';
			}
			
			/* create the field definations by merging default and defined in class */
			$field = array_merge ( $this->fieldDefault, $val );
			
			/* echo "<hr /> Field <b>" . $key . '</b> LEVEL = ' . $level; print_r($field); */
			
			/* makesure this field is for requested sub task */
			$OkField = 0;
			if (in_array ( $_SESSION ['url_sub_task'], $field ['sub_tasks'] )) {
				$OkField = 1;
			}
			foreach ( $this->defaultFields as $defaultKey => $defaultValue ) {
				/* always show the default fields */
				if ($key == $defaultKey) {
					$OkField = 1;
					break;
				}
			}
			if ($OkField == 0) {
				continue;
			}
			
			/* start the form field row */
			// $rStr .= '<tr><td class="jpmFormField ui-corner-all" >';
			$rStr .= ' <tr><td>';
			if (! isset ( $rec [$key] ) || $rec[$key] == '') {
				$rec [$key] = $this->getDefaultValue ($field); /* dynamic default value */
			}

			/* find if field should be shown as readonly */
			$showReadOnly = false;
	                if (in_array ( $field ['name'], array (
                                'created_on',
                                'updated_on',
                                'created_by',
                                'updated_by',
                                'for_org'
			) )) {
				$showReadOnly = true;
			}
			if ($this->curlsMode == 'Show' || $this->curlsMode == 'Remove') {
				$showReadOnly = true;
			}
			if ($field['name'] == 'updated_by'
                                && in_array($this->collectionName, array('organization','person'))
                                && $this->curlsMode == 'Update') {
				$showReadOnly = false;
				/* this is done to migrate organizatio and person to someone else */
			}

			/* show field input based on their types */
			if ($field ['type'] == 'container') {
				$rStr .= '<div class="jpm-html-form-field-cover">';
;
				$rStr .= $this->showLabel ($field);
				$rStr .= $this->showHelp ($field);
				$title = $this->getTitle ( $field ['title'] );
				/* $id = $this->nameToTagId ( $field ['name'] ); */
				
				$subFieldCounter = 0;
								
				/* create already existing fields */
				$existingSubFieldsStr = '';
				if (isset ( $rec [$key] ) && is_array ( $rec [$key] )) {
					foreach ( $rec [$key] as $subField ) {
						$subFieldCounter ++;
						$subFieldId = $fieldUid . '_' . $subFieldCounter;
						/* showContainer($slfs, $fieldParentName, $subField, $subFieldCounter, $title, $fieldId, $type) */
						$existingSubFieldsStr .= $this->showContainer (
								$field ['fields'], /* slfs */
								$field['name'] . '[' . $subFieldCounter . ']', /* field name */
								$subField, /* field defination */
								$subFieldCounter, /* this sub field counter */
								$key, /* title */
								$subFieldId, /* parent id for sub fields */
								'd',
								'' /* template id */
						);
				
					}
				}

				/* fields to start will have next sub field id */
				$subFieldCounter ++;
				$subFieldId = $fieldUid . '_' . $subFieldCounter;
								
				$divIdUnderWhichSubFieldToBeAdded = 'a' . $subFieldId . 'a';
				$templateId = 't' . $subFieldId . 't';
				$replaceTextForSubFieldCounter = 'i' . $subFieldId . 'i';
								
				$rStr .= '<div id="' . $divIdUnderWhichSubFieldToBeAdded . '">';
				$rStr .= $existingSubFieldsStr;
				$rStr .= '</div>';

				
				if (in_array($this->curlsMode, array('Update', 'Create', 'Copy'))) {
					$this->templateCodes .= '<div class="jpmHide" id="' . $templateId . '">' 
							. $this->showContainer ( 
									$field ['fields'], /* slfs */
									$field['name'] . '[' . $replaceTextForSubFieldCounter . ']', /* field name */
									array (), /* field defination */
									$replaceTextForSubFieldCounter, /* this sub field counter */
									$key, /* title */
									$fieldUid, /* parent id for sub fields */
									't',
									$templateId
								) . '</div>';
					$rStr .= '<br /><div onclick="addSubField('
							.'\'' . $divIdUnderWhichSubFieldToBeAdded . '\','
							.'\'' . $templateId . '\','
							.'\'' . $replaceTextForSubFieldCounter . '\''
							.')">Add ' . $title . '</div>';
				}
				$rStr .= '</div>';
			} elseif ($field ['type'] == 'file_list') {
				$rStr .= $this->showFileList ( $rec [$key], $field );				
			} else {

				$size = $field ['input_tag_length'];

				if ($field ['type'] == 'list') {
					if ($field ['input_mode'] == 'selecting') {
						$field['type'] = 'select';
					} else {
						if ($field ['multiple'] == 1) {
							$field['type'] = 'checkboxes';
						} else {
							$field['type'] = 'radios';
						}
					}
					$size = $field ['select_tag_hight'];
				}

				if($showReadOnly) {
					$field['type'] = 'readonly';
				}

		                $rStr .= (new owebp_Form_Field_Widget(array(
					'inputTag' => $field ['type'],
					'title' => $field['title'],
					'name' => $field['name'],
					'value' => $rec[$key],
					'required' => $field['required'],
					'help' => $field['help'],
					'size' => $size,
					'jpm_foreign_collection' => $field['foreign_collection'],
					'jpm_foreign_search_fields' => $field['foreign_search_fields'],
					'jpm_foreign_title_fields' => $field['foreign_title_fields'],
					'dataClassName' => $field['list_class'],
				)))->show();
			}
			/* end of the form field row */
			$rStr .= '</td></tr>';
			$fieldCount++;
		} /* while */
		
		return $rStr;
	}
	public function showForm($urlArgsArray, $action, $rec, $fields) {
		$rStr = '';
		$this->templateCodes = '';
		
		$st = '';
		if ($_SESSION ['url_sub_task'] != 'All') {
			$st = '/' . $_SESSION ['url_sub_task'];
		}
				
		if ( $_SESSION['debug']) {
			$action = $action . '?debug';
		}
		$rStr .= ' <form action="' . $action . $st . '" method="POST" enctype="multipart/form-data" class="ui-widget">';
		if (isset ( $rec ['_id'] )) {
			/* this is so that save will update insted of insert */
			if ($this->curlsMode == 'Update' || $this->curlsMode == 'Remove') {
				$rStr .= '<input type="hidden" name="_id" value="' . $rec ['_id'] . '" />';
			}
		}
		/* to provide the file name to delete via POST to delete the file along with record */
		if ($this->collectionName == 'file_upload') {
			/* this is so that save will update insted of insert */
			if ($this->curlsMode == 'Update' || $this->curlsMode == 'Remove') {
				$rStr .= '<input type="hidden" name="file_name" value="' . $rec ['file_name'] . '" />';
			}
		}
		/* session id is set to make sure no one else is doing save and delete */
		$rStr .= '<input type="hidden" name="session_id" value="' . session_id() . '" />';
		
		/* save the value of sub task if it is not all */
		if ($_SESSION ['url_sub_task'] != 'all') {
			$rStr .= '<input type="hidden" name="' . $this->subTaskKeyToSave . '" value="' . $_SESSION ['url_sub_task'] . '" />';
		}
		
		$rStr .= ' <table>';
		$rStr .= '<tr><td><div class="ui-widget-header ui-corner-top jpmHeaderPadding">' 
			. $this->form ['title'] . '</div></td></tr>';
		if (isset ( $rec ['_id'] )) {
			/* this is so that save will update insted of insert */
			
			if ($this->curlsMode == 'Show' || $this->curlsMode == 'Remove') {
				$rStr .= ' <tr><td><div class="jpmContentPadding ui-widget-content ui-corner-all" >';
				$rStr .= 'ID : ' . $rec ['_id'];
				$rStr .= ' </div></td></tr>';

			}
		}
		$rStr .= $this->showSameLevelFields ( $fields, '', $rec, 'p');
		if (in_array ( $this->curlsMode, array (
				'Update',
				'Copy',
				'Remove',
				'Create',
				'Login',
				'Join',
				'Forget Password' 
		) )) {
			$rStr .= ' <tr><td class="jpmFormField ui-corner-all" >';
			$rStr .= ' <input type="reset" />';
			$rStr .= ' <input type="submit"/>';
			$rStr .= ' for ' . $this->curlsMode . ' </td></tr>';
		}
		$rStr .= '</table></form>';
		$rStr .= $this->templateCodes;
		return $rStr;
	} /* showForm */
}
?>
