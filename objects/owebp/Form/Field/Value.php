<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_Value extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('value', '');
                $this->setOptionDefault('jpm_foreign_collection', '');
                $this->setOptionDefault('jpm_foreign_title_fields', '');
                $this->setOptionDefault('dataClassName', '');
                /* update options with input */
                parent::__construct($opts);
        }

        private function isValidMongoObjectID($str) {
                // A valid Object Id must be 24 hex characters
                return preg_match ( '/^[0-9a-fA-F]{24}$/', $str );
        }

        private function getDocumentById($collection,$id) {
                if (! $this->isValidMongoObjectID(trim($id))) {
                        return array();
                }
                return $_SESSION ['mongo_database']->{$collection}->findOne ( array (
                                '_id' => new MongoId((string)(trim($id)))
                ) );
        }


	/* convert string with underscores as Title */
        private function getTitle($t) {
                $ta = split ( '_', $t );
                $rS = '';
                foreach ( $ta as $w ) {
                        $rS .= ' ' . ucfirst ( strtolower ( $w ) );
                }
                return $rS;
        }


	private function showSelectedReadOnlyFields($allFields, $record) {
                $rStr = '';
                foreach ( $allFields as $oneField ) {
                        $fieldValue = '';

                        if (isset ( $record [trim ( $oneField )] )) {
                                if (is_array ( $record [trim ( $oneField )] )) {

                                        foreach ( $record [trim ( $oneField )] as $subField ) {
                                                foreach ( $subField as $subElem => $val ) {
                                                        if ($val != '') {
                                                                $fieldValue .= $val . ' ';
                                                        }
                                                }
                                                $fieldValue = rtrim ( $fieldValue, " " );
                                                $fieldValue .= '; ';
                                        }
                                        $fieldValue = rtrim ( $fieldValue, "; " );
                                } else {
                                        if ($record [trim ( $oneField )] != '') {
                                                $fieldValue .= $record [trim ( $oneField )] . ', ';
                                        }
                                }
                                $fieldValue .= ", ";
                                $fieldValue = rtrim ( $fieldValue, ", " );
                        }
                        if ($fieldValue != '') {
                                $rStr .= $this->getTitle ( $oneField ) . ': ' . $fieldValue . '<br/ >';
                        }
                }
                return $rStr;
        }

        private function showSelectedReadOnlyFieldsFromDocOfCollection($docId, $collection, $allShowFields) {
                $fkDoc = array ();
                $rStr = '';
                $rStr .= '<div><b>';

                try {
                        $frId = ( string ) $docId;
                        if ($frId == 'COMMON_ITEM') {
                                $rStr .= "Common Item";
                        } else {
                                if (! $this->isValidMongoObjectID ( $frId )) {
                                        $rStr .= 'Invalid format of key';
                                } else {
                                        $frId = new MongoId ( trim ( $frId ) );
                                        $fkDoc = $this->getDocumentById($collection, $frId);
                                        if (empty ( $fkDoc )) {
                                                $rStr .= 'No such record exists';
                                        } else {
                                                $fkTitleFields = split ( ",", $allShowFields );
                                                $rStr .= $this->showSelectedReadOnlyFields ( $fkTitleFields, $fkDoc );
                                        }
                                }
                        }
                } catch ( MongoException $em ) {
                        $rStr .= ( string ) $docId . ' Invalid value of key';
                }
                $rStr .= '</b></div>';
                return $rStr;
        }

	public function get() {

		$value = $this->getOption('value');

		if ($this->getOption('dataClassName') != '') {
                	$dataClassName = $this->getOption('dataClassName');
	                $dataClassInstance = new $dataClassName();
                        if ($dataClassInstance->titleValueConversionRequired) {
				$titleValue = '';
                                foreach ( $dataClassInstance->getTable () as $r ) {
                                        if ($r ['value'] == $value) {
                                                $titleValue = $r ['title'];
						break;
                                        }
                                }
                                if ($titleValue == '') {
                                        $titleValue = $value;
                                }
				$value = $titleValue;
                        }
		}
		

		if ($this->getOption('jpm_foreign_collection') != '' && $value != '') {
                        $value = $this->showSelectedReadOnlyFieldsFromDocOfCollection ( 
				$this->getOption('value'), 
				$this->getOption('jpm_foreign_collection'), 
				$this->getOption('jpm_foreign_title_fields')
			);
		}
		/* return with cover */

		return $value;
	}

} /* class */
