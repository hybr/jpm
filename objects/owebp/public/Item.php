<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_Item extends owebp_Base {
	function __construct() {
		$this->collectionName = 'item';
	} /* __construct */
	public $dataAccess = array (
		/* table */	
		1/*0*/,1/*1*/,1/*2*/,/*read yes, write yes, present yes for person's own at table level */
		1/*3*/,1/*4*/,1/*5*/,/*read yes, write yes, present yes for org's own at table level */
		1/*6*/,0/*7*/,1/*8*/,/*read no, write no, present no for public at table level */
		/* record */	
		1/*9*/,1/*10*/,1/*11*/,/*read yes, write yes, present yes for person's own at record level */
		1/*12*/,1/*13*/,1/*14*/,/*read yes, write yes, present yes for org's own at record level */
		1/*15*/,0/*16*/,1/*17*/,/*read no, write no, present no for public at record level */			
	);
	
	public $fields = array (
		'title' => array (
			'required' => 1,
			'show_in_list' => 1 
		),
		'UPC' => array (
			'required' => 1 
		),
		'summary' => array (
			'required' => 1 
		),
		'manufacturar' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'organization',
			'foreign_search_fields' => 'abbreviation,name',
			'foreign_title_fields' => 'abbreviation,name'
		),
		'live' => array (
			'type' => 'list',
			'list_class' => 'owebp_ItemLiveType',
			'input_mode' => 'clicking',
			'show_in_list' => 1,
			'default' => 'Proposed',
		),		
		'type' => array (
			'type' => 'list',
			'list_class' => 'owebp_ItemType',
			'input_mode' => 'clicking',
			'show_in_list' => 1,
			'default' => 'Service' 
		),		
		'virtual' => array (
			'help' => 'Is item virtual or real? Virtual items can be delivered by internet.',
			'type' => 'list',
			'list_class' => 'owebp_Boolean',
			'input_mode' => 'clicking',
			'default' => 'False',
			'required' => 1,
		),							
		'about' => array (
			'type' => 'container',
			'required' => 1,
			'show_in_list' => 0,
			'fields' => array (
				'category' => array (
					'required' => 1 
				),
				'about_category' => array (),
				'attribute' => array (
					'type' => 'container',
					'required' => 1,
					'show_in_list' => 0,
					'fields' => array (
						'name' => array ('required' => 1),
						'summary' => array (),
						'value' => array (),
						'unit' => array (),
						'image' => array (
							'type' => 'file_list' 
						) 
					) 
				) 
			) 
		),
		'photo' => array (
			'type' => 'container',
			'show_in_list' => 0,
			'fields' => array (
				'caption' => array (),
				'file_name' => array (
				'type' => 'file_list',
					'required' => 1 
				),
				'click_link_url' => array (
					'type' => 'url' 
				) 
			) 
		),
			'price' => array (
				'type' => 'container',
				'show_in_list' => 0,
				'fields' => array (
					'for' => array (
						'type' => 'list',
						'help' => 'Purpose of this item',
						'list_class' => 'owebp_ItemFor',
						'input_mode' => 'selecting',
						'show_in_list' => 1,
						'default' => 'Make and Sale',
						'required' => 1
					),							
					'type' => array (
						'type' => 'list',
						'list_class' => 'owebp_ItemPriceType',
						'input_mode' => 'clicking',
						'default' => 'Amount',
						'required' => 1  
					),
					'amount' => array (
						'type' => 'number',
						'required' => 1 
					),
					'currency' => array (
						'type' => 'string',
						'required' => 1,
						'max_length' => 3,
						'min_length' => 3,
						'default' => 'INR'
					),							
					'per' => array (
						'type' => 'number',
						'required' => 1,
						'default' => 1
					),
					'per_unit' => array (
						'type' => 'string',
						'required' => 1
					),							
				) 
			),
			'service_hours' => array (
				'help' => 'If this is a real service then provide the service hours of each provider.',
				'type' => 'container',
				'show_in_list' => 0,
				'fields' => array (
					'provider' => array (
						'type' => 'foreign_key',
						'foreign_collection' => 'person',
						'foreign_search_fields' => 'name.first,name.middle,name.last',
						'foreign_title_fields' => 'name,gender'
					),
					'every' => array (
						'type' => 'number' ,
						'required' => 1,  
					),
					'frequency' => array (
						'type' => 'list',
						'list_class' => 'owebp_TimeRepeatFrequency',
						'input_mode' => 'selecting',
						'default' => 'Day',
						'required' => 1,  
					),
					'start_date' => array (
						'type' => 'date' ,
						'required' => 1,  
					),
					'start_time' => array (
						'type' => 'time' ,
						'required' => 1,  
					),
					'duration' => array (
						'type' => 'number' ,
						'required' => 1,  
					),
					'duration_unit' => array (
						'type' => 'list',
						'list_class' => 'owebp_TimeRepeatFrequency',
						'input_mode' => 'selecting',
						'default' => 'Hour',
						'required' => 1,  
					),
					'end_date' => array (
						'type' => 'date' ,
						'required' => 1,  
					),
					'end_time' => array (
						'type' => 'time' ,
						'required' => 1,  
					),
				) 
			),
			'pre_requisites' => array (
				'type' => 'container',
				'show_in_list' => 0,
				'fields' => array (
					'mendatory' => array (
						'type' => 'list',
						'list_class' => 'owebp_Boolean',
						'input_mode' => 'clicking',
						'default' => 'False',
						'required' => 1,
					),							
					'condition' => array (
						'required' => 1,
					),
				) 
			),
	); /* fields */
	

	private function getNumberOfSecondsSinceMidnight() {
		return date('G') * 3600 + date('i') * 60 + date('s');
	}

	public function presentDocument($subTaskKeyToSave, $fields, $doc) {
		$rStr = '';

		$rStr .= '<div class="ui-widget">';
		$rStr .= $this->getFieldValue($doc, 'title', '<div class="ui-widget-header ui-corner-top jpmHeaderPadding">', '</div>');

		$rStr .= '<div class="ui-widget-content ui-corner-bottom jpmContentPadding">';
		$rStr .= '<form action="/shopping_cart/presentAll" method="POST"><table class="showTable">';
		$rStr .= '<input type="hidden" name="item" value="'.(string) $doc['_id'].'" />';
		$rStr .= '<input type="hidden" name="session_id" value="'.session_id ().'" />';
		$rStr .= '<input type="hidden" name="session_used" value="'.session_id ().'" />';
		
		$rStr .= $this->getFieldValue($doc, 'summary', '<tr><td colspan="2">','</td></tr>');
		
		
 		if ($doc ['manufacturar'] == 'COMMON_ITEM') {
			$manufacturarDoc = $_SESSION ['url_domain_org'];
		} else {
			$manufacturarDoc = $this->getDocumentById ( 'organization', $doc ['manufacturar'] );
		}
		$rStr .= '<input type="hidden" name="supplier" value="'.(string) $manufacturarDoc['_id'].'" />';

		$rStr .= $this->getFieldValue($manufacturarDoc, 'name', '<tr><td>Provider</td><td>','</td></tr>');

		if ( isset($doc['photo']) && !empty($doc['photo'])) {
			$rStr .= '<tr><td colspan="2"><ul class="jpmPns">';
			foreach($doc['photo'] as $photo) {
				$image = $this->getFieldValue($photo, 'file_name', '<img height="20%" src="','" />');
				$caption = $this->getFieldValue($photo, 'caption', '<br />','');
				$imageLink = $this->getFieldValue($photo, 'file_name', '<a href="','">'.$image.'</a>');
				$rStr .= $this->getFieldValue($photo, 'file_name', '<li>', '</li>', $imageLink . $caption);
			}
			$rStr .= '</ul></td></tr>';
		}
		
		if ( isset($doc['about']) && !empty($doc['about'])) {
			foreach($doc['about'] as $about) {
				$rStr .= '<tr>';
				
				$rStr .= '<td>';
				$rStr .= $this->getFieldValue($about, 'category', '<u>', '</u> : ');
				$rStr .= $this->getFieldValue($about, 'about_category');
				$rStr .= '</td>';
				
				$rStr .= '<td><ol>';
				foreach ($about['attribute'] as $attribute) {
					$summary = $this->getFieldValue($attribute, 'summary');
					$unit = $this->getFieldValue($attribute, 'unit');
					$value = $this->getFieldValue($attribute, 'value', '<b>', $unit . '</b>');
					$rStr .= $this->getFieldValue($attribute, 'name', '<li><u>', '</u> : ' . $summary . '</li>');
				}
				$rStr .= '</ol></td>';
				$rStr .= '</tr>';
			}
		}
		
		if ( isset($doc['price']) && !empty($doc['price'])) {
			foreach($doc['price'] as $price) {
				$rStr .= '<tr>';
				
				$rStr .= '<td>';
				$rStr .= $this->getFieldValue($price, 'for', 'We ');
				$rStr .= '</td>';
				
				$rStr .= '<td>';
				if (isset($price['type'])) {
					if ($price['type'] == 'Quote') {
						$rStr .= '<a href="/contact/present_all">Request a quote</a>';
						$rStr .= '<input type="hidden" name="price" value="Quote" />';
					} else {
						$rStr .= $price['amount'] . ' ' . $price['currency'] 
						. ' per ' . $price['per'] . ' ' . $price['per_unit'];
						$rStr .= '<input type="hidden" name="price" value="'.$price['amount'].'" />';
					}
					$rStr .= '<input type="hidden" name="price_currency" value="'.$price['currency'].'" />';
					$rStr .= '<input type="hidden" name="price_per" value="'.$price['per'].'" />';
					$rStr .= '<input type="hidden" name="price_per_unit" value="'.$price['per_unit'].'" />';
				}
				$rStr .= '</td>';
				$rStr .= '</tr>';
			}		
		}
		
		if ( isset($doc['pre_requisites']) && !empty($doc['pre_requisites'])) {
			$rStr .= '<tr>';
			
			$rStr .= '<td>';
			$rStr .= 'Pre Requisites';
			$rStr .= '</td>';
			$rStr .= '<td>';
			foreach($doc['pre_requisites'] as $pre_requisites) {
				
				if (isset($pre_requisites['condition'])) {
					if ($pre_requisites['mendatory'] == 'True') {
						$rStr .= 'Mendatory: ';
					} else {
						$rStr .= 'Optional: ';
					}
					$rStr .= $this->getFieldValue($pre_requisites, 'condition', '', '<br />'); 
				}
				
			}		
			$rStr .= '</td>';
			$rStr .= '</tr>';
		}
		if (	isset($doc['type']) && $doc['type'] == 'Service'
			&& isset($doc['virtual']) && $doc['virtual'] == 'False'
			&& isset($doc['service_hours']) 
			&& !empty($doc['service_hours'])
		) {
			$rStr .= '<tr>';
			
			$rStr .= '<td>';
			$rStr .= 'Appointment';
			$rStr .= '</td>';

			$rStr .= '<td>';
			$nowEpoch = strtotime("now");
			$startTimeEpoch = '';
			$slots = array();
			foreach($doc['service_hours'] as $sh) {
				$startDate = $this->getFieldValue($sh, 'start_date');
				if ($startDate == '') { break; }
				$startTime = $this->getFieldValue($sh, 'start_time');
				if ($startTime == '') { break; }
				$endDate = $this->getFieldValue($sh, 'end_date');
				$endTime = $this->getFieldValue($sh, 'end_time');

				$startTimeEpoch = strtotime($startDate . ' ' . $startTime);
				$endTimeEpoch = strtotime($endDate . ' ' . $endTime);

                                $personClass =  new owebp_public_Person();
                                $personClass->record = $this->getDocumentById('person', $this->getFieldValue($sh, 'provider'));
                                $personName = $personClass->getFullName('Official');



				$totalSlotsPerProvider = 10; 
				$slotTimeEpoch = $startTimeEpoch;
				while($totalSlotsPerProvider > 0) {
					/* find next slot */
					/* calculate next slotStartTime based on repetition */
					switch( $this->getFieldValue($sh, 'frequency')) {
						case "Year":
							$slotTimeEpoch = strtotime("+1 Year", $slotTimeEpoch);
							break;
						case "Quater":
							$slotTimeEpoch = strtotime("+3 Months", $slotTimeEpoch);
							break;
						case "Month":
							$slotTimeEpoch = strtotime("+1 Months", $slotTimeEpoch);
							break;
						case "Bi-week":
							$slotTimeEpoch = strtotime("+14 Days", $slotTimeEpoch);
							break;
						case "Week":
							$slotTimeEpoch = strtotime("+7 Days", $slotTimeEpoch);
							break;
						case "Day":
							$slotTimeEpoch = strtotime("+1 Day", $slotTimeEpoch);
							break;
						case "Working Day":
							if (date("N", $slotTimeEpoch) < 5) { /* if fri then next create mon slot */
								$slotTimeEpoch = strtotime("+1 Day", $slotTimeEpoch);
							} else {	
								$slotTimeEpoch = strtotime("+3 Day", $slotTimeEpoch);
							}
							break;
						case "Hour":
							$slotTimeEpoch = strtotime("+1 Hour", $slotTimeEpoch);
							break;
						default:
							$slotTimeEpoch = strtotime("+1 Day", $slotTimeEpoch);
					} /* switch */

					if ($slotTimeEpoch > $endTimeEpoch) { break; }
					if ($slotTimeEpoch >  $nowEpoch ) { 
						/* Create the slot */
						array_push($slots, date("Y-m-d H:i A l", $slotTimeEpoch) 
							. ' by ' . $personName . ' for '  
							.  $this->getFieldValue($sh, 'duration')
							.  ' ' . $this->getFieldValue($sh, 'duration_unit')
						);
						$totalSlotsPerProvider--;
					}
				} /* for */
				
			}		
			sort($slots);
			$rStr .= '<select name="appointment">';
			foreach($slots as $slot) {
				$rStr .= '<option>' . $slot . '</option>';
			}
			$rStr .= '</select>';
			$rStr .= '</td>';
			$rStr .= '</tr>';
		}

		$rStr .= '<tr><td>Quantity</td><td><input type="number" name="quantity" value="1" /></td></tr>';
		$rStr .= '<tr><td colspan="2"><input type="reset" /> <input type="submit" value="Add to cart"/></td></tr>';

		$rStr .= '</table></form></div>';
		$rStr .= '</div">';
		return $rStr;
	}	
} /* class */
?>
