<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_ApplicationEnvironment extends owebp_Base {
	public $fields = array (
			'value' => array (
					'type' => 'integer',
					'required' => 1 
			),
			'title' => array (
					'type' => 'text',
					'required' => 1 
			) 
	);
	private $current = 5;
	public function setTitle($value) {
		$this->current = 0;
		foreach ( $this->table as $record ) {
			if ($record ['title'] == $value) {
				$this->current = $record ['value'];
			}
		}
		if ($this->current == 0) {
			$this->errorMessage = "Application Environment: invalid value " . $value . " to set.";
			$this->current = 5; /* default is development */
		}
	}
	public function setValue($value) {
		$this->current = 0;
		foreach ( $this->table as $record ) {
			if ($record ['value'] == $value) {
				$this->current = $record ['value'];
			}
		}
		if ($this->current == 0) {
			$this->errorMessage = "Application Environment: invalid value " . $value . " to set.";
			$this->current = 5; /* default is development */
		}
	}
	public function getValue() {
		return $this->current;
	}
	public function getTitle() {
		foreach ( $this->table as $record ) {
			if ($record ['value'] == $this->current) {
				return $record ['title'];
			}
		}
	}
	public $dataLocation = DATA_LOCATION_SERVER_CODE;
	public $table = array (
			array (
					'value' => 1,
					'title' => 'Production' 
			),
			array (
					'value' => 2,
					'title' => 'Disaster Recovery' 
			),
			array (
					'value' => 3,
					'title' => 'Production Fix' 
			),
			array (
					'value' => 4,
					'title' => 'Testing' 
			),
			array (
					'value' => 5,
					'title' => 'Devlopment' 
			) 
	);
}
?>