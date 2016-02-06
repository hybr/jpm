<?php

require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Root.php";
class owebp_Form_Field_Label extends owebp_Root {

        function __construct($opts = array()) {
                /* define options  and their defaults */
                $this->setOptionDefault('title', 'No Title');
                $this->setOptionDefault('name', 'NoFieldName');
                $this->setOptionDefault('required', '');
                $this->setOptionDefault('position', 'top'); /* possible values top, left */

                /* update options with input */
                parent::__construct($opts);
        }

	public function show() {
		$contentHtmlTag = new owebp_Html_Tag(array(
                        'tag' => 'span',
                        'content' => (new owebp_TitleCreator(array( 'string' => $this->getOption('title'))))->get(),
                ));
		$titleText = trim($contentHtmlTag->get());
		if ($this->getOption('required') == 1) {
			$requiredHtmlTag = new owebp_Html_Tag(array(
				'tag' => 'span',
				'content' => ' *',
                        	'style' => 'color: red;',
			));
			$titleText .= ' ' . $requiredHtmlTag->get();
		}
		$labelHtmlTag = new owebp_Html_Tag(array(
			'tag' => 'label',
			'class' => 'ui-widget-header',
			'id' => $this->getOption('name'),
                        'style' => 'padding: 2px;',
			'content' => trim($titleText),
		));

                /* return with cover */
                return (new owebp_Html_FormFieldComponentCover(array(
                        'position' => $this->getOption('position'),
                        'content' => $labelHtmlTag->get(),
                )))->get();
	}

} /* class */
