<?php
class owebp_Root {

	function __construct($opts = array()) {
		/* echo '<pre>'; print_r($opts); echo '</pre>'; */
		foreach($opts as $key => $value) {
			$this->setOption($key, $value);
		}
	/*
		$this->callbacks[$onAction][] = $callbackMethod;
		echo get_class($this) . '<pre>|'; print_r($this->getOptions()); echo '|</pre>'; 
	*/

	}

	private $options = array();

	public function getOptions() {
		return $this->options;
	}

	public function setOptionDefault($key = '', $value = '') {
		$this->options[$key] = $value;
	}

	public function setOption($key = '', $value = '') {
		if (array_key_exists($key, $this->options)) {
			$this->options[$key] = $value;
		} else {
			$this->options[$key] = 'Invalid Option';
		}
	}

	public function getOption($key = '') {
		if (array_key_exists($key, $this->options) && isset($this->options[$key])) {
			return $this->options[$key];
		}
		return '';
	}

} /* class */
?>
