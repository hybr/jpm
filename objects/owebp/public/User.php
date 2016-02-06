<?php
require_once JPM_DIR . DIRECTORY_SEPARATOR . "objects" . DIRECTORY_SEPARATOR . "owebp" . DIRECTORY_SEPARATOR . "Base.php";
class owebp_public_User extends owebp_Base {
	function __construct() {
		$this->collectionName = 'user';
	}
	public $fields = array (
		'email_address' => array (
			'type' => 'email',
			'placeholder' => 'Email Address',
			'required' => 1,
			'unique' => 1,
			'show_in_list' => 1 
		),
		'password' => array (
			'type' => 'password',
			'placeholder' => 'Password',
			'help' => 'Length between 5 to 12 charecters',
			'required' => 1,
			'minlength' => 5,
			'maxlength' => 12 
		),
		'provider' => array (
			'type' => 'list',
			'list_class' => 'owebp_AuthenticationProvider',
			'input_mode' => 'clicking',
			'required' => 1,
			'show_in_list' => 1 
		),
		'person' => array (
			'type' => 'foreign_key',
			'foreign_collection' => 'person',
			'foreign_search_fields' => 'name.first,name.middle,name.last',
			'foreign_title_fields' => 'name,gender' 
		),
		'veryfied' => array(
			'default' => 0
		)
	);
	/*
	 *
	 * private function loginCredential() {
	 * return new owebp_LoginCredential ();
	 * }
	 */
	private function sendAccountVerificationEmailToUser($user) {
		$message = '<html><head><title>Account Verification Email</title></head><body>';
		$message .= '<b>Hello</b>,<br /><p>Please verify your email address to activate the account. <a href="http://'
			.$_SESSION['url_domain']
			.'/user/va?c=' .$user['veryfied']
			.'&e='.md5($user['email_address']).'">Click here</a></p><br />Thanks<br />'
			.$_SESSION['url_domain'];
		$message .= '</body></html>';

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: '.$user['email_address'].'' . "\r\n";
		$headers .= 'From: noreply@'.$_SESSION['url_domain'].'' . "\r\n";
		$headers .= 'Reply-To: noreply@'.$_SESSION['url_domain'].'' 
			. "\r\n" .  'X-Mailer: PHP/' . phpversion();
		mail($user['email_address'], "Please verify your email address", $message, $headers);
	}
	public function login($urlArgsArray) {
		$rStr = '';
		/* this process is to receive login credentials for authentication */
		$f = new owebp_InputForm ();
		$f->form ['label'] = 'left';
		$f->form ['title'] = 'Login';
		
		if (array_key_exists ( 'ea', $urlArgsArray )) {
			$this->fields ['email_address'] ['value'] = $urlArgsArray ['ea'];
		}
		unset ( $this->fields ['provider'] );
		unset ( $this->fields ['person'] );
		unset ( $this->fields ['veryfied'] );
		$f->curlsMode = 'Login';
		
		$rStr .= $f->showForm ( $urlArgsArray, '/user/authenticate', array (), $this->fields );
		$rStr .= '<a href="/user/forget_password">Forget Password</a>';
//		$rStr .= '<a href="/user/activate_account">Activate Account</a>';
		return $rStr;
	}
	public function logout($urlArgsArray) {
		unset ( $_SESSION ['user'] );
		unset ( $_SESSION ['person'] );
		unset ( $_SESSION ['login_person_id'] );
		header('Location: /');    
	}
	public function join($urlArgsArray) {
		$rStr = '';
		/* this process is to receive login credentials for authentication */
		$f = new owebp_InputForm ();
		$f->form ['label'] = 'left';
		$f->form ['title'] = 'Registration';
		$f->curlsMode = 'Join';
		unset ( $this->fields ['person'] );
		unset ( $this->fields ['provider'] );
		unset ( $this->fields ['veryfied'] );
		if (array_key_exists ( 'ea', $urlArgsArray )) {
			$this->fields ['email_address'] ['value'] = $urlArgsArray ['ea'];
		}
		$rStr .= $f->showForm ( $urlArgsArray, '/user/register', array (), $this->fields );
		return $rStr;
	}
	public function va($urlArgsArray) {
		/* va = varify account */
		/* read the record */

		$user = $_SESSION ['mongo_database']->user->findOne ( array (
			'veryfied' => $urlArgsArray ['c']
		) );
		if (empty($user)) {
			array_push ( $this->errorMessage, 'Invalid activation code' );
			return $this->showError ();
		}
		if (md5($user['email_address']) !=  $urlArgsArray ['e']) {
			array_push ( $this->errorMessage, 'Invalid activation email' );
			return $this->showError ();
		}
		$user['password'] = $user['veryfied'];
		$user['veryfied'] = 1;
		$user['session_id'] = session_id();
		$_POST = $user;
		$rStr = $this->save ( $urlArgsArray );
		if ($rStr == ' Saved successfully ') {
			$rStr = "Account verified and password updated";
		}
		return $rStr;
	}

	public function sendactivationemail($urlArgsArray) {
		/* read the record */
		$user = $_SESSION ['mongo_database']->user->findOne ( array (
			'email_address' => $_POST ['email_address']
		) );

		if (empty($user)) {
			array_push ( $this->errorMessage, $_POST ['email_address'] . ' does not exists' );
			return $this->showError ();
		}

		/* convert password to md5 code and save in record, keep rest stuff same */
		$user['veryfied'] = md5($_POST['password']);
		$user['session_id'] = $_POST['session_id'];
		$_POST = $user;

		$rStr = $this->save ( $urlArgsArray );

		if ($rStr == ' Saved successfully ') {
			$rStr = "Activation email is sent to " . $user['email_address'] . '. Please click on activate account link';
		}

		$this->sendAccountVerificationEmailToUser($user);

		unset($user);
		return $rStr;
	}

	public function forgetpassword($urlArgsArray) {
		$this->debugPrintArray($_SESSION ); 
		$rStr = '';
		/* this process is to receive login credentials for authentication */
		$f = new owebp_InputForm ();
		$f->form ['label'] = 'left';
		$f->form ['title'] = 'Password Update and Account Activation';
		
		if (array_key_exists ( 'ea', $urlArgsArray )) {
			$this->fields ['email_address'] ['value'] = $urlArgsArray ['ea'];
		}
		/* unset ( $this->fields ['password'] ); */
		unset ( $this->fields ['provider'] );
		unset ( $this->fields ['person'] );
		unset ( $this->fields ['veryfied'] );
		$f->curlsMode = 'Forget Password';
		
		$rStr .= 'Note: To just activate account you can keep password as same as current.' 
			. $f->showForm ( $urlArgsArray, '/user/sendactivationemail', array (), $this->fields );
		return $rStr;
	}
	public function authenticate($urlArgsArray) {
		/* $this->debugPrintArray($_POST); */ 
		
		/* read the record */
		$_SESSION ['user'] = $_SESSION ['mongo_database']->user->findOne ( array (
				'email_address' => $_POST ['email_address']
		) );
		
		
		/* $this->debugPrintArray($_SESSION ['user']); */
		
		if (empty($_SESSION ['user'])) {
			array_push ( $this->errorMessage, $_POST ['email_address'] . ' does not exists' );
			unset($_SESSION['user']);
			return $this->showError ();
		}

		if (isset($_SESSION ['user']['veryfied'])) {
			if ($_SESSION ['user']['veryfied'] != 1) {
				array_push ( $this->errorMessage, $_POST ['email_address'] . ' is  not veryfied yet.' );
				unset($_SESSION['user']);
				return $this->showError ();
			}
		} else {
			array_push ( $this->errorMessage, $_POST ['email_address'] . ' is  not veryfied. Use '
				. '<a href="/user/forget_password">Forget Password</a> to activate.' );
			unset($_SESSION['user']);
			return $this->showError ();
		}

		if ($_SESSION ['user'] ['password'] != md5 ( $_POST ['password'] )) {
			array_push ( $this->errorMessage, 'Password does not match' );
			unset($_SESSION['user']);
			return $this->showError ();
		}
		
		$rStr = 'You are logged in.';

		/* get the person record also */
		$_SESSION ['person'] = array();
		/*
		$this->debugPrintArray($_SESSION['user']);
		$this->debugPrintArray($_SESSION['url_domain_org']); 
		*/
		if (isset($_SESSION ['user'] ['person'])) {
			$_SESSION ['person'] = $this->getDocumentById('person', (string) $_SESSION ['user'] ['person']); 
		}
		
		if (empty($_SESSION ['person'])) {
			$rStr .= ' | No associated person record found';
		} else {
			$_SESSION ['login_person_id'] = $_SESSION ['user'] ['person'];
		}
		
		header('Location: /person');    
		return $rStr;
	} /* authenticate */
	public function register($urlArgsArray) {
		$userRec = array();

		if (!isset($_POST ['email_address'])) {
			array_push ( $this->errorMessage, 'Provide email address to register' );
		} else {
			/* first check if user exists in db or not */
			$userRec = $_SESSION ['mongo_database']->user->findOne ( array (
				'email_address' => trim ( $_POST ['email_address'] ) 
			) );
		}
		
		if (! empty ( $userRec )) {
			/* if user exists then find its associated person */
			/* get the person record also */
			array_push ( $this->errorMessage, 'User account with this email <b>' . trim ( $_POST ['email_address'] ) . '</b> already exists' );
			if (isset ( $userRec ['person'] ) && $userRec ['person'] != '') {
				$personRec = $_SESSION ['mongo_database']->person->findOne ( array (
						'_id' => new MongoId ( $userRec ['person'] ) 
				) );
				if (empty ( $personRec )) {
					array_push ( $this->errorMessage, 'No associated person record found.' );
				} else {
					$personCommunicationEmail = '';
					foreach ( $personRec ['login_credential'] as $lc ) {
						$personCommunicationEmail = $lc ['email_address'];
						if (isset ( $lc ['primary'] ) && $lc ['primary']) {
							break;
						}
						/* if primary found then that is primary email or the last one */
					}
					/* get the login credential account to get the email address */
					if ($personCommunicationEmail != '') {
						$lcRec = $_SESSION ['mongo_database']->user->findOne ( array (
								'_id' => new MongoId ( $personCommunicationEmail ) 
						) );
						$personCommunicationEmail = $lcRec ['email_address'];
						
						$personClass = new owebp_public_Person ();
						$personClass->record = $personRec;
						array_push ( $this->errorMessage, $personClass->getOfficialFullName () . ' is holding this user account, <a href="mainto:' . $personCommunicationEmail . '">request</a> the person to migrate it to you.' );
					}
				}
			}
		}
		
		if (! empty ( $this->errorMessage )) {
			return $this->showError ();
		}

		/* Create mechanisum to activate the account */
		$_POST['veryfied'] = md5($_POST['password']);

		$rStr =  "Registered Successfully";

		$this->save ( $urlArgsArray );
		if ($rStr == ' Saved successfully ') {
			$rStr = "An activation email is sent to " . $user['email_address'] . '. Please click on activate account link to activate your user account.';
			$this->sendAccountVerificationEmailToUser($_POST);
		}


		return $rStr;

		/* save this as new user */
	}
}
?>
