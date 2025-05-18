<?php
/*
   Plugin Name: Tranzila Payment
   Plugin URI: http://quicksolutions.co.il/
   description: A plugin to Credit clearing by tranzila.
   Version: 1.0.0
   Author: Rivka Chollack
   Author URI: http://quicksolutions.co.il/
   */

defined( 'ABSPATH' ) or die( 'No access' );

class Tranzila_Payment	{

	//https://secure5.tranzila.com/cgi-bin/tranzila71pme.cgi?supplier=ttxtelemshaz&tranmode=A&ccno=12312312&expdate=0525&sum=15&currency=1&cred_type=1&myid=123456789&mycvv=123&TranzilaPW=puGBjHd5
	
	// private $TERMINAL_NAME = 'ttxtelemshaz';
	// private $TOKEN_TERMINAL_NAME = 'ttxtelemshaztok';
	// private $TranzilaTokPW = 'puGBjHd5';
	// private $TranzilaPW = 'C9x-6652';
	// //$Cancellation_PW = 'OW7QkzxH';
	// private $query_string = '';
	// private $tranzila_api_host = 'secure5.tranzila.com';
	// private $tranzila_api_path = '/cgi-bin/tranzila71pme.cgi';



	private $TERMINAL_NAME = 'netzach';
	private $TOKEN_TERMINAL_NAME = 'netzachtok';
	private $TranzilaTokPW = 'IILkEYmZ';
	private $TranzilaPW = 'zvRtE15R';
	private $CreditPass = 'rbtVAPjq';
	private $query_string = '';
	private $tranzila_api_host = 'secure5.tranzila.com';
	private $tranzila_api_path = '/cgi-bin/tranzila71u.cgi';

	private function tranzila_curl_exec(){
		$cr = curl_init();

		curl_setopt($cr, CURLOPT_URL, "https://$this->tranzila_api_host$this->tranzila_api_path");
		curl_setopt($cr, CURLOPT_POST, 1);
		curl_setopt($cr, CURLOPT_FAILONERROR, true);
		curl_setopt($cr, CURLOPT_POSTFIELDS, $this->query_string);
		curl_setopt($cr, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, 0);
		// Execute request
		$result = curl_exec($cr);
		$error = curl_error($cr);

		if (!empty($error)) {
			die ($error);
		}
		curl_close($cr);

		return $result;
	}

	private function tranzila_get_response($result){
		// Preparing associative array with response data
		$response_array = explode('&', $result);
		$response_assoc = array();
		if (count($response_array) > 1) {
			foreach ($response_array as $value) {
				$tmp = explode('=', $value);
				if (count($tmp) > 1) {
					$response_assoc[$tmp[0]] = $tmp[1];
				}
			}
		}

		$response = "";
		// Analyze the result string 
		if (!isset($response_assoc['Response'])) {
			$response = array("result" => "0", "message" => $result);
			//die ($result . "\n");
			/**
         * When there is no 'Response' parameter it either means
         * that some pre-transaction error happened (like authentication
         * problems), in which case the result string will be in HTML format,
         * explaining the error, or the request was made for generate token only
         * (in this case the response string will contain only 'TranzilaTK'
         * parameter)
         */
		} else if ($response_assoc['Response'] !== '000') {
			$response = array("result" => "0", "message" => $response_assoc['Response'] . '(' . $this->tranzila_getTextForResponseCode($response_assoc['Response']) . ')');
			//die ($response_assoc['Response'] . "\n");
			// Any other than '000' code means transaction failure
			// (bad card, expiry, etc..)
		} else {
			$response = array("result" => "1", "message" => "Success");
			//die ("Success\n");
		}
		return $response;
	}

	//create tolen - no payment
	public function create_token($data, $offline_flag = false){
		
		// Prepare transaction parameters
		$query_parameters['supplier'] = $this->TOKEN_TERMINAL_NAME;// 'TERMINAL_NAME' should be replaced by actual terminal name
		$query_parameters['ccno'] = $data['ccno']; //12312312 Test card number
		$query_parameters['TranzilaTokPW'] = $this->TranzilaTokPW; // Token password if required
		$query_parameters['TranzilaTK'] = '1'; //Make transaction
		// Prepare query string
		$this->query_string = '';
		foreach ($query_parameters as $name => $value) {
			$this->query_string .= $name . '=' . $value . '&';
		}
		
		$this->query_string = substr($this->query_string, 0, -1); // Remove trailing '&'
		$result = $this->tranzila_curl_exec();
		// error_log("create_token: ".print_r($result, true));
		if (strpos($result, 'TranzilaTK') !== false) {
			$response_array = explode('=', $result);//TranzilaTK=b13a6e0649b95482312
			if ($offline_flag) {
				return trim($response_array[1]);
			}
			else {
				update_user_meta(get_current_user_ID(), 'tranzila_token', trim($response_array[1]));
				update_user_meta(get_current_user_ID(), 'tranzila_card_details', array('expdate' => $data['expdate']));
				return true;
			}
		}
		return false;
	}
	
	//regular transaction
	public function create_transaction($data, $offline_flag = false){
		// Prepare transaction parameters
		$query_parameters['supplier'] = $this->TERMINAL_NAME;//'TERMINAL_NAME';// 'TERMINAL_NAME' should be replaced by actual terminal name
		$query_parameters['sum'] = $data['sum']; //Transaction sum 
		$query_parameters['tranmode'] = 'A'; //Transaction mode 
		$query_parameters['currency'] = '1'; //Type of currency 1 NIS, 2 USD, 978 EUR, 826 GBP, 392 JPY
		$query_parameters['ccno'] = $data['ccno'];//'12312312'; // Test card number
		$query_parameters['expdate'] = $data['expdate']; // Card expiry date: mmyy
		//$query_parameters['myid'] = '12312312'; //ID number if required
		$query_parameters['mycvv'] = $data['mycvv']; // number if required
		$query_parameters['cred_type'] = '1'; // This field specifies the type of transaction, 1 - normal transaction, 6 - credit, 8 - payments
		$query_parameters['TranzilaPW'] = $this->TranzilaPW;//'TranzilaPW'; // Token password if required
		// Prepare query string
		$this->query_string = '';
		foreach ($query_parameters as $name => $value) {
			$this->query_string .= $name . '=' . $value . '&';
		}

		$this->query_string = substr($this->query_string, 0, -1); // Remove trailing '&'

		$result = $this->tranzila_curl_exec();

		// error_log("resultttt tranzila_curl_exec: ". print_r($result, true));

		if ($offline_flag) return $result;
		$response = $this->tranzila_get_response($result);

		return $response;
	}
							 
	//regular token transaction
	public function create_tk_transaction($data){
		// Prepare transaction parameters
		if (empty($data['terminal_name']))
			$terminal_name = 'TOKEN_TERMINAL_NAME';
		else
			$terminal_name = $data['terminal_name'];
		$query_parameters['supplier'] = $this->$terminal_name;//'TERMINAL_NAME';// 'TERMINAL_NAME' should be replaced by actual terminal name
		$query_parameters['sum'] = $data['sum']; //Transaction sum 
		//$query_parameters['tranmode'] = 'A'; //Transaction mode 
		$query_parameters['currency'] = '1'; //Type of currency 1 NIS, 2 USD, 978 EUR, 826 GBP, 392 JPY
		//$query_parameters['ccno'] = $data['ccno'];//'12312312'; // Test card number
		$query_parameters['expdate'] = $data['expdate']; // Card expiry date: mmyy
		//$query_parameters['myid'] = '12312312'; //ID number if required
		//$query_parameters['mycvv'] = $data['mycvv']; // number if required
		$query_parameters['cred_type'] = '1'; // This field specifies the type of transaction, 1 - normal transaction, 6 - credit, 8 - payments
		$query_parameters['TranzilaPW'] = $terminal_name == 'TOKEN_TERMINAL_NAME' ? $this->TranzilaTokPW : $this->TranzilaPW;//'TranzilaPW'; // Token password if required
		$query_parameters['TranzilaTK'] = $data['TranzilaTK'];//'TranzilaTK'; // Token created for user
		// Prepare query string
		$this->query_string = '';
		foreach ($query_parameters as $name => $value) {
			$this->query_string .= $name . '=' . $value . '&';
		}

		$this->query_string = substr($this->query_string, 0, -1); // Remove trailing '&'

		$result = $this->tranzila_curl_exec();
		//die($this->query_string);
		$response = $this->tranzila_get_response($result);

		return $response;
	}
							 
	//j5 transaction - only check card details
	public function create_J5_transaction($data){
		// Prepare transaction parameters
		$query_parameters['supplier'] = $this->TERMINAL_NAME;// 'TERMINAL_NAME' should be replaced by actual terminal name
		$query_parameters['sum'] = $data['sum']; //Transaction sum 
		$query_parameters['currency'] = '1'; //Type of currency 1 NIS, 2 USD, 978 EUR, 826 GBP, 392 JPY
		$query_parameters['ccno'] = $data['ccno'];//'12312312'; // Test card number
		$query_parameters['expdate'] = $data['expdate']; // Card expiry date: mmyy
		//$query_parameters['myid'] = '12312312'; // ID number
		$query_parameters['mycvv'] = $data['mycvv'];// CVV number
		$query_parameters['cred_type'] = '1'; // This field specifies the type of transaction, 1 - normal transaction, 6 - credit, 8 - payments
		$query_parameters['TranzilaPW'] = $this->TranzilaPW; // Token password if required
		$query_parameters['tranmode'] = 'V'; //Mode for verify transaction
		// Prepare query string
		$this->query_string = '';
		foreach ($query_parameters as $name => $value) {
			$this->query_string .= $name . '=' . $value . '&';
		}

		$this->query_string = substr($this->query_string, 0, -1); // Remove trailing '&'

		$result = $this->tranzila_curl_exec();

		$response = $this->tranzila_get_response($result);

		if ($response["result"] == "1"){
			update_user_meta(get_current_user_ID(), 'tranzila_index', $response_assoc['index']);
			update_user_meta(get_current_user_ID(), 'tranzila_ConfirmationCode', $response_assoc['index']);
		}

		return $response;

	}

	//payment transaction - from the j5 transaction
	public function forced_transaction($data){
		// Prepare transaction parameters
		$query_parameters['supplier'] = $this->TERMINAL_NAME;//'TERMINAL_NAME' should be replaced by actual terminal name
		$query_parameters['sum'] = $data['sum']; //Transaction sum
		$query_parameters['currency'] = '1'; //Type of currency 1 NIS, 2 USD, 978 EUR, 826 GBP, 392 JPY
		//$query_parameters['ccno'] = '12312312'; // Test card number
		//$query_parameters['expdate'] = '0820'; // Card expiry date: mmyy
		//$query_parameters['myid'] = '12312312'; //ID number if required
		//$query_parameters['mycvv'] = 'mycvv'; //CVV number if required
		//$query_parameters['cred_type'] = '1'; // This field specifies the type of transaction, 1 - normal transaction, 6 - credit, 8 - payments
		$query_parameters['TranzilaPW'] = $this->TranzilaPW;//Token password if required
		$query_parameters['tranmode'] = 'F'; //Mode for verify transaction
		$query_parameters['authnr'] = $data['authnr']; // Authorization number
		$query_parameters['index'] = $data['index']; // index return in J5 transaction
		// Prepare query string
		$this->query_string = '';
		foreach ($query_parameters as $name => $value) {
			$this->query_string .= $name . '=' . $value . '&';
		}

		$this->query_string = substr($this->query_string, 0, -1); // Remove trailing '&'

		$result = tranzila_curl_exec();

		$response = tranzila_get_response($result);

		return $result;

	}
	
	private function tranzila_getTextForResponseCode( $code ) {
		$response_messages = array(
			'000' => 'Transaction approved',
			'001' => 'Blocked confiscate card.',
			'002' => 'Stolen confiscate card.',
			'003' => 'Contact credit company.',
			'004' => 'Refusal.',
			'005' => 'Forged. confiscate card.',
			'006' => 'Identity Number of CVV incorrect.',
			'007' => 'Must contact Credit Card Company',
			'008' => 'Fault in building of access key to blocked cards file.',
			'009' => 'Contact unsuccessful.',
			'010' => 'Program ceased by user instruction (ESC).',
			'011' => 'No confirmation for the ISO currency clearing.',
			'012' => 'No confirmation for the ISO currency type.',
			'013' => 'No confirmation for charge/discharge transaction.',
			'014' => 'Unsupported card',
			'015' => 'Number Entered and Magnetic Strip do not match',
			'017' => 'Last 4 digets not entered',
			'019' => 'Record in INT_IN shorter than 16 characters.',
			'020' => 'Input file (INT_IN) does not exist.',
			'021' => 'Blocked cards file (NEG) non-existent or has not been updated - execute transmission or request authorization for each transaction.',
			'022' => 'One of the parameter files or vectors do not exist.',
			'023' => 'Date file (DATA) does not exist.',
			'024' => 'Format file (START) does not exist.',
			'025' => 'Difference in days in input of blocked cards is too large - execute transmission or request authorization for each transaction.',
			'026' => 'Difference in generations in input of blocked cards is too large - execute transmission or request authorization for each transaction.',
			'027' => 'Where the magnetic strip is not completely entered',
			'028' => 'Central terminal number not entered into terminal defined for work as main supplier.',
			'029' => 'Beneficiary number not entered into terminal defined as main beneficiary.',
			'030' => 'Terminal not updated as main supplier/beneficiary and supplier/beneficiary number entered.',
			'031' => 'Terminal updated as main supplier and beneficiary number entered',
			'032' => 'Old transactions - carry out transmission or request authorization for each transaction.',
			'033' => 'Defective card',
			'034' => 'Card not permitted for this terminal or no authorization for this type of transaction.',
			'035' => 'Card not permitted for transaction or type of credit.',
			'036' => 'Expired.',
			'037' => 'Error in instalments - Amount of transaction needs to be equal to the first instalment + (fixed instalments times no. of instalments)',
			'038' => 'Cannot execute transaction in excess of credit card ceiling for immediate debit.',
			'039' => 'Control number incorrect.',
			'040' => 'Terminal defined as main beneficiary and supplier number entered.',
			'041' => 'Exceeds ceiling where input file contains J1 or J2 or J3 (contact prohibited).',
			'042' => 'Card blocked for supplier where input file contains J1 or J2 or J3 (contact prohibited).',
			'043' => 'Random where input file contains J1 (contact prohibited).',
			'044' => 'Terminal prohibited from requesting authorization without transaction (J5)',
			'045' => 'Terminal prohibited for supplier-initiated authorization request (J6)',
			'046' => 'Terminal must request authorization where input file contains J1 or J2 or J3 (contact prohibited).',
			'047' => 'Secret code must be entered where input file contains J1 or J2 or J3 (contact prohibited).',
			'051' => ' Vehicle number defective.',
			'052' => 'Distance meter not entered.',
			'053' => 'Terminal not defined as gas station. (petrol card passed or incorrect transaction code).',
			'057' => 'Identity Number Not Entered',
			'058' => 'CVV2 Not Entered',
			'059' => 'Identiy Number and CVV2 Not Entered',
			'060' => 'ABS attachment not found at start of input data in memory.',
			'061' => 'Card number not found or found twice',
			'062' => 'Incorrect transaction type',
			'063' => 'Incorrect transaction code.',
			'064' => 'Type of credit incorrect.',
			'065' => 'Incorrect currency.',
			'066' => 'First instalment and/or fixed payment exists for non-instalments type of credit.',
			'067' => 'Number of instalments exists for type of credit not requiring this.',
			'068' => 'Linkage to dollar or index not possible for credit other than instalments.',
			'069' => 'Length of magnetic strip too short.',
			'070' => 'PIN terminal not defined',
			'071' => 'PIN must be enetered',
			'072' => 'Secret code not entered.',
			'073' => 'Incorrect secret code.',
			'074' => 'Incorrect secret code - last try.',
			'079' => 'Currency is not listed in vector 59.',
			'080' => '"Club code" entered for unsuitable credit type',
			'090' => 'Transaction cancelling is not allowed for this card.',
			'091' => 'Transaction cancelling is not allowed for this card.',
			'092' => 'Transaction cancelling is not allowed for this card.',
			'099' => 'Cannot read/write/open TRAN file.',
			'100' => 'No equipment for inputting secret code.',
			'101' => 'No authorization from credit company for work.',
			'107' => 'Transaction amount too large - split into a number of transactions.',
			'108' => 'Terminal not authorized to execute forced actions.',
			'109' => 'Terminal not authorized for card with service code 587.',
			'110' => 'Terminal not authorized for immediate debit card.',
			'111' => 'Terminal not authorized for instalments transaction.',
			'112' => 'Terminal not authorized for telephone/signature only instalments transaction.',
			'113' => 'Terminal not authorized for telephone transaction.',
			'114' => 'Terminal not authorized for "signature only" transaction.',
			'115' => 'Terminal not authorized for dollar transaction.',
			'116' => 'Terminal not authorized for club transaction.',
			'117' => 'Terminal not authorized for stars/points/miles transaction.',
			'118' => 'Terminal not authorized for Isracredit credit.',
			'119' => 'Terminal not authorized for Amex Credit credit.',
			'120' => 'Terminal not authorized for dollar linkage.',
			'121' => 'Terminal not authorized for index linkage.',
			'122' => 'Terminal not authorized for index linkage with foreign cards.',
			'123' => 'Terminal not authorized for stars/points/miles transaction for this type of credit.',
			'124' => 'Terminal not authorized for Isracredit payments.',
			'125' => 'Terminal not authorized for Amex payments.',
			'126' => 'Terminal not authorized for this club code.',
			'127' => 'Terminal not authorized for immediate debit transaction except for immediate debit cards.',
			'128' => 'Terminal not authorized to accept Visa card staring with 3.',
			'129' => 'Terminal not authorized to execute credit transaction above the ceiling.',
			'130' => 'Card not permitted for execution of club transaction.',
			'131' => 'Card not permitted for execution stars/points/miles transaction.',
			'132' => 'Card not permitted for execution of dollar transactions (regular or telephone).',
			'133' => 'Card not valid according Isracard list of valid cards.',
			'134' => 'Defective card according to system definitions (Isracard VECTOR1) - no. of figures on card - error.',
			'135' => 'Card not permitted to execute dollar transactions according to system definition (Isracard VECTOR1).',
			'136' => 'Card belongs to group not permitted to execute transactions according to system definition (Visa VECTOR 20).',
			'137' => 'Card prefix (7 figures) invalid according to system definition (Diners VECTOR21)',
			'138' => 'Card not permitted to carry out instalments transaction according to Isracard list of valid cards.',
			'139' => 'Number of instalments too large according to Isracard list of valid cards.',
			'140' => 'Visa and Diners cards not permitted for club instalments transactions.',
			'141' => 'Series of cards not valid according to system definition (Isracard VECTOR5).',
			'142' => 'Invalid service code according to system definition (Isracard VECTOR6).',
			'143' => 'Card prefix (2 figures) invalid according to system definition (Isracard VECTOR7).',
			'144' => 'Invalid service code according to system definition (Visa VECTOR12).',
			'145' => 'Invalid service code according to system definition (Visa VECTOR13).',
			'146' => 'Immediate debit card prohibited for execution of credit transaction.',
			'147' => 'Card not permitted to execute instalments transaction according to Leumicard vector no. 31.',
			'148' => 'Card not permitted for telephone and signature only transaction according to Leumicard vector no. 31',
			'149' => 'Card not permitted for telephone transaction according to Leumicard vector no. 31',
			'150' => 'Credit not approved for immediate debit cards.',
			'151' => 'Credit not approved for foreign cards.',
			'152' => 'Club code incorrect.',
			'153' => 'Card not permitted to execute flexible credit transactions (Adif/30+) according to system definition (Diners VECTOR21).',
			'154' => 'Card not permitted to execute immediate debit transactions according to system definition (Diners VECTOR21).',
			'155' => 'Amount of payment for credit transaction too small.',
			'156' => 'Incorrect number of instalments for credit transaction',
			'157' => '0 ceiling for this type of card for regular credit or Credit transaction.',
			'158' => '0 ceiling for this type of card for immediate debit credit transaction',
			'159' => '0 ceiling for this type of card for immediate debit in dollars.',
			'160' => '0 ceiling for this type of card for telephone transaction.',
			'161' => '0 ceiling for this type of card for credit transaction.',
			'162' => '0 ceiling for this type of card for instalments transaction.',
			'163' => 'American Express card issued abroad not permitted for instalments transaction.',
			'164' => 'JCB cards permitted to carry out regular credit transactions.',
			'165' => 'Amount in stars/points/miles larger than transaction amount.',
			'166' => 'Club card not in terminal range.',
			'167' => 'Stars/points/miles transaction cannot be executed.',
			'168' => 'Dollar transaction cannot be executed for this type of card.',
			'169' => 'Credit transaction cannot be executed with other than regular credit.',
			'170' => 'Amount of discount on stars/points/miles greater than permitted.',
			'171' => 'Forced transaction cannot be executed with credit/immediate debut card.',
			'172' => 'Previous transaction cannot be cancelled (credit transaction or card number not identical).',
			'173' => 'Double transaction.',
			'174' => 'Terminal not permitted for index linkage for this type of credit.',
			'175' => 'Terminal not permitted for dollar linkage for this type of credit.',
			'176' => 'Card invalid according to system definition (Isracard VECTOR1)',
			'177' => 'Cannot execute "Self-Service" transaction at gas stations except at "Self-Service at gas stations".',
			'178' => 'Credit transaction forbidden with stars/points/miles.',
			'179' => 'Dollar credit transaction forbidden on tourist card.',
			'180' => 'Club Card can not preform Telephone Transactions',
			'200' => 'Application error.',
			'700' => 'Approved TEST Masav transaction',
			'701' => 'Invalid Bank Number',
			'702' => 'Invalid Branch Number',
			'703' => 'Invalid Account Number',
			'704' => 'Incorrect Bank/Branch/Account Combination',
			'705' => 'Application Error',
			'706' => 'Supplier directory does not exist',
			'707' => 'Supplier configuration does not exist',
			'708' => 'Charge amount zero or negative',
			'709' => 'Invalid configuration file',
			'710' => 'Invalid date format',
			'711' => 'DB Error',
			'712' => 'Required parameter is missing',
			'800' => 'Transaction Canceled',
			'900' => '3D Secure Failed',
			'903' => 'Fraud suspected',
			'951' => 'Protocol Error',
			'952' => 'Payment not completed',
			'954' => 'Payment Failed',
			'955' => 'Payment status error',
			'959' => 'Payment completed unsuccessfully',
		);

		return $response_messages[$code];
	}
	
	//credit transaction
	public function create_credit_transaction($data){
		// Prepare transaction parameters
		$query_parameters['supplier'] = $this->TOKEN_TERMINAL_NAME;
		$query_parameters['sum'] = $data['sum'];
		$query_parameters['tranmode'] = 'C';
		$query_parameters['currency'] = '1';
		$query_parameters['cred_type'] = '1';
		$query_parameters['TranzilaPW'] = $this->TranzilaTokPW;
		$query_parameters['CreditPass'] = $this->CreditPass;
		$query_parameters['authnr'] = $data['authnr'];
		$query_parameters['TranzilaTK'] = $data['TranzilaTK'];
		$query_parameters['expdate'] = $data['expdate'];

		$this->query_string = '';
		foreach ($query_parameters as $name => $value) {
			$this->query_string .= $name . '=' . $value . '&';
		}

		$this->query_string = substr($this->query_string, 0, -1); // Remove trailing '&'

		$result = $this->tranzila_curl_exec();

		$response = $this->tranzila_get_response($result);

		return $response;
	}
}
?>
