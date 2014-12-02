<?php
require_once("class.pgsqldatabase.php");
require_once("class.logging.php");
require_once("../../../amfphp/services/class.Database.php");
require_once('../../../Schedule_List_Views/common/php/PHPExcel.php');
require_once('../../../Schedule_List_Views/common/php/PHPExcel/IOFactory.php');
require_once('./PHPMailer/PHPMailerAutoload.php');
require_once('../shipping/ups_api/inc/config.php');
require_once("../shipping/ups_api/UpsAPI.php");
require_once("../shipping/ups_api/UpsAPI/USAddressValidation.php");
require_once("../shipping/ups_api/UpsAPI/XAVUSAddressValidation.php");
define("MAX_ADDRESS_CHUNK", 100);
define("DEBUG_ADDRESS_CHUNK", 1);

// Batch Defines
define("BATCH_UPLOAD", "BATCH_UPLOAD");
define("BATCH_DELETE", "BATCH_DELETE");
define("BATCH_REUPLOAD", "BATCH_REUPLOAD");
define("BATCH_PROCESSED", "BATCH_PROCESSED");

// Batch Item Defines
define("BATCHITEM_DELETE", "BATCHITEM_DELETE");
define("BATCHITEM_MODIFED", "BATCHITEM_MODIFED");

// Address Verification
define("ADDRESS_VERIFICATION", "ADDRESS_VERIFICATION");

// User Defines
define("USER_ADDED", "USER_ADDED");
define("USER_DELETED", "USER_DELETED");
define("USER_MODIFED", "USER_MODIFED");
define("USER_LOGIN", "USER_LOGIN");

// Process Defines
define("PROCESSED_SUCCESS", "PROCESSED_SUCCESSFULLY");
define("PROCESSED_FAILURE", "PROCESSED_FAILED");

function addToEventLog($subject, $desc, $batchid){
	try{
		if( ($dbconn = pg_connect("host=sched-db.fcp.biz port=5432 dbname=batchship2 user=bmosley password=bm7830")) == FALSE){
			 throw new Exception("Error: Could not connect to database host");
		}	
		if( ($result = pg_query_params($dbconn, 
			"INSERT INTO event_log (subject, description, batch_id, user_id, timestamp) VALUES ($1, $2, $3, $4, $5)", 
			array($subject, 
				$desc, 
				$batchid, 
				((isset($_SESSION["userid"]) != null) ? $_SESSION["userid"] : null), 
				date('Y-m-d H:i:s')))) == FALSE)
		{
			//throw new Exception("Error: Could not process query " . pg_errormessage($this->dbconn));
		}
	}
	catch(Exception $e){
		
	}
	
	
}	
class BatchShip extends PGSQLDatabase{
	//protected $dbconn = null;
	protected $allowedExts = null;
	protected $maxfilesize = null;
	protected $allowed_file_type = null;
	protected $config_object = null;
	protected $email_credentials = null;
	protected $spreadsheetHeaders = null;
	protected $spreadsheetHeadersLower = null;
	protected $outputDelimHeader = null;
	protected $database = null;
	protected $statelist = null;
	protected $error_array = null;
	protected $logging = null;
	protected $appname = null;
	protected $appcodename = null;
	public function bv_MarkAllAsValidated($_REQUEST)
	{
		$arrayResult = array();
		
		try{
			$batchid = (isset($_REQUEST["batchid"])) ? $_REQUEST["batchid"] : null;
			if($batchid == null)
				throw new Exception("Error: No BatchID was supplied");
			// Check for correct shipping method.
			if( ($result = pg_query_params($this->dbconn, "SELECT * FROM batchship_items WHERE batch_id = $1 AND verified = 'f'", array($batchid))) == FALSE){
				throw new Exception("Error: Could not update batchship_items");
			}
			$info = array();
			
			$rowarray = pg_fetch_all($result);
			foreach ($rowarray as $key => &$value) {
				if(!$this->checkshipping_type($value["shipping_method"])){
					$info["incorrectid"][] = $value["id"];
					continue;
				}
				$info["correctid"][] = $value["id"];
			}
			foreach ($info["correctid"] as $key => $value) {
				if( (pg_query_params($this->dbconn, "UPDATE batchship_items SET verify_state = 'passed', verified = 't' WHERE id = $1",	array($value))) == FALSE){
					throw new Exception("Error: Could not update batchship_items");
				}
			}

			//if( (pg_query_params($this->dbconn, "UPDATE batchship_items SET verify_state = 'passed', verified = 't' WHERE batch_id = $1 AND verified = 'f'",	array($batchid))) == FALSE){
			//	throw new Exception("Error: Could not update batchship_items");
			//}
			$arrayResult = array("status" => 200, "message" => "Successfully validated addresses", "corrected" => count($info["correctid"]), "notcorrected" => count($info["incorrectid"]));
		}
		catch(Exception $e)
		{
			$arrayResult = array("status" => 500, "message" => $e->getMessage());
		}
		return $arrayResult;
	}
	public function modifyUserInfo($requestJSON){
		try{
			$arrayResult = array();
			$result = 0;
			$id = (isset($requestJSON->id)) ? $requestJSON->id : null;
			if($id == null){
				throw new Exception("Error: ID is equal to null user");
			}
			$privaleges =  (isset($requestJSON->privaleges)) ? $requestJSON->privaleges : "";
			$emailaddress = (isset($requestJSON->emailaddress)) ? $requestJSON->emailaddress : "";
			$username = (isset($requestJSON->username)) ? $requestJSON->username : ""; 
			
			if( ($result = pg_query_params($this->dbconn, "SELECT * FROM users WHERE id = $1 AND privaleges = 'admin'", array($_SESSION["userid"]))) == FALSE){
				throw new Exception("Error: Query Could not be processed");
			}
			
			if(pg_num_rows($result) > 0){	
				if( ($result = pg_query_params($this->dbconn, "UPDATE users SET username = $1, privaleges =$2, email_address=$3 WHERE id = $4", 
							array($requestJSON->username, $requestJSON->privaleges, $requestJSON->emailaddress, $requestJSON->id))) != FALSE)
				{
					$arrayResult = array("status" => 200, "message" => "Change applied succesfully");
				}
			}
			else{
				throw new Exception("Error: You do not have permissions to change this");
			}
		}
		catch(Exception $e){
			$arrayResult = array("status" => 500, "message" => $e->getMessage() );
		}
		return $arrayResult;
	}
	public function deletethirdpartyshipinfo($request){
		try{
			$id = (isset($request->thirdpartyid)) ? $request->thirdpartyid : null;
			if( ($result = pg_query_params($this->dbconn, "DELETE FROM thirdpartyaddress WHERE id = $1", array($id))) == FALSE){
				throw new Exception("Error: Cannot delete thirdparty address ". pg_errormessage($this->dbconn));
			}
			else{
				$arrayResult = array("status" => 200, "message" => "Successfully deleted");
			}
			
		}
		catch(Exception $e){
			$arrayResult = array("status" => 500, "message" => $e->getMessage());
		}
		return $arrayResult;
	}
	public function modifyThirdPartyShipping($request){
		$arrayResult = array();
		try{
			$id = (isset($request->id)) ? $request->id : null;
			$companyname = (isset($request->company_name)) ? $request->company_name : "";
			$street = (isset($request->street)) ? $request->street : "";
			$city = (isset($request->city)) ? $request->city : "";
			$state = (isset($request->state)) ? $request->state : "";
			$zipcode = (isset($request->zipcode)) ? $request->zipcode : "";
			$country = (isset($request->country)) ? $request->country : "";
			$carrier = (isset($request->carrier)) ? $request->carrier : "";
			$accountnumber = (isset($request->accountnumber)) ? $request->accountnumber : "";
			$shipping_account = json_encode(array("carrier" => $carrier, 
													"accountnumber" => $accountnumber, 
													"lastupdated" => date('Y-m-d')));
			
			if( $id == null){
				throw new Exception("No ID was supplied");
			}
			
			if( ($result = pg_query_params($this->dbconn, "UPDATE thirdpartyaddress SET company_name=$1, street=$2, city=$3, state=$4, zipcode=$5, country=$6, shippingaccount=$7 WHERE id = $8",
					array($companyname, $street, $city, $state, $zipcode, $country, $shipping_account, $id))) != FALSE)
			{
				$arrayResult = array("status" => 200, "message" => "Processed Successfull");
			}
			else{
				throw new Exception("Could not write user values ". pg_errormessage($this->dbconn));	
			}	
			
		}
		catch(Exception $e){
			$arrayResult = array("status" => 500, "message" => $e->getMessage());
		}
		return $arrayResult;
	}
	public function getThirdPartyShippingInfo($request){
		$thirdpartyaddressid = (isset($request["thirdpartyaddressid"])) ? $request["thirdpartyaddressid"] : null;
		$arrayResult = array();
		if( ($result = pg_query_params($this->dbconn, "SELECT * FROM thirdpartyaddress ORDER BY company_name", array())) != FALSE){
			$rowarray = pg_fetch_all($result);
			foreach ($rowarray as $key => &$value) {
				$re = json_decode($value["shippingaccount"]);
				$value["carrier"] = $re->carrier;
				$value["accountnumber"] = $re->accountnumber;
				$value["label"] = "{$value["company_name"]} - {$value["carrier"]} #{$value["accountnumber"]}";
				$value["labelrev"] = "#{$value["accountnumber"]} - {$value["carrier"]}";
			}
			$arrayResult = array("status" => 200, "data" => $rowarray);
		} 
		return $arrayResult;
	}
	
	public function addThirdPartyShippingInfo($request){
		$arrayResult = array();
		try{
			$companyname = (isset($request->company_name)) ? $request->company_name : "";
			$street = (isset($request->street)) ? $request->street : "";
			$city = (isset($request->city)) ? $request->city : "";
			$state = (isset($request->state)) ? $request->state : "";
			$zipcode = (isset($request->zipcode)) ? $request->zipcode : "";
			$country = (isset($request->country)) ? $request->country : "";
			$carrier = (isset($request->carrier)) ? $request->carrier : "";
			$accountnumber = (isset($request->accountnumber)) ? $request->accountnumber : "";
			$shipping_account = json_encode(array("carrier" => $carrier, "accountnumber" => $accountnumber, "lastupdated" => date('Y-m-d')));
			
			if( $companyname == ""){
				throw new Exception("Error: Missing Company name");
			}
			if( $carrier == ""){
				throw new Exception("Error: Missing carrier");
			}
			if( $accountnumber == ""){
				throw new Exception("Error: Missing account number");
			}
			if( ( $result = pg_query_params($this->dbconn, "INSERT INTO thirdpartyaddress (company_name, street, city, state, zipcode, country, shippingaccount) VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING id", 
						array($companyname, $street, $city, $state, $zipcode, $country, $shipping_account))) == FALSE)
			{
				throw new Exception("Error: could not update thirdparty accounts" . " " . pg_errormessage($this->dbconn));	
			}
			else{
				$row = pg_fetch_array($result);
				$arrayResult = array("status" => 200, "message" => "Account Added Successfully", "data" => array("id" => $row["id"]));
			}
		}
		catch(Exception $e){
			$arrayResult = array("status" => 500, "message" => $e->getMessage());
		}
		return $arrayResult;
	}
	
	public function getPoNumbersByBatch($request){
		$arrayResult = array();
		try{
			$batchid = (isset($request)) ? $request["batchid"] : null;
			if( ($result = pg_query_params($this->dbconn, 
					"SELECT po_number FROM batchship_items WHERE batch_id=$1", array($batchid))) == FALSE)
			{
				throw new Exception("Could not query batchship items");
			}
			$po_number_count = 0;
			$po_array = array();
			while($rows = pg_fetch_assoc($result)){
				// Only use the po number that have a value
				if(strlen($rows["po_number"]) > 0){
					$po_array[$rows["po_number"]]++;
				}
			}
			$pojsarray = "";
			$pojsare = array();
			foreach ($po_array as $key => $value) {
				$pojsarray .= ($pojsarray == "") ? $key : ", {$key}";
				$pojsare[] = array("po" => $key,
									"numofelements" => $value
									);
				$po_number_count +=	$value;
			}
			$arrayResult = array("status" => 200, "message" => $request["batchid"], 
									"data" => array("ponumberlist" => $pojsarray,
													"ponumberarray" =>$pojsare,
													"total_rows_returned" => pg_numrows($result),
													"po_numbers_counted" => $po_number_count,
													"batchid" => $batchid
													)
									);
		}
		catch(Exception $e){
			$arrayResult = array("status" => 500, "message" => $e->getMessage());
		}
		
		return $arrayResult;
		
	}
	public function isHoliday($_REQUEST){
		$arrayResult = array();
		try{
			if( (isset($_REQUEST["datestring"])) == FALSE){
				throw new Exception("There was no holiday string sent");
			}
			$isHoliday = ($this->database->is_holiday($_REQUEST["datestring"])) ? TRUE : FALSE;
		
			$arrayResult = array("status" => 200, 
							"message" => ($isHoliday) ? "Is a holiday" : "Is not a holiday",  
							"data" => array(
								"date" => $_REQUEST["datestring"],
								"isholiday" => $isHoliday));		
		}
		catch(Exception $e){
			$arrayResult = array("status" => 500, "message" => $e->getMessage());
		}
		return $arrayResult;
	}
	public function __construct() 
	{
		parent::__construct();
		
		$this->logging = new BatchShipLogging();
		$this->logging->setFlatFileConfiguration("testlog.txt");
		
		$this->config_object = parse_ini_file("../configuration.ini", true);
		$this->appname = $this->config_object["general_settings"]["app_name"];
		$this->appcodename = $this->config_object["general_settings"]["app_codename"];
		
		// this database is function is used for holidays and such
		$this->database = new Database();
		
		//UPS Configuration
		$this->upss = array("UsernameToken" => 
				    			array(
				    				"Username" => $this->config_object["ups_account_settings"]["Username"],
				    				"Password" => $this->config_object["ups_account_settings"]["Password"]
								),
				    		  "ServiceAccessToken" => 
				    		  	array(
				    		  		"AccessLicenseNumber" => $this->config_object["ups_account_settings"]["ServiceAccessLicenseKey1"]
								)
						);
		$this->upssEndpoint = $this->config_object["ups_account_settings"]["Endpoint"];
		//FedEx Configuration
		$this->fedexcredentials = array('UserCredential' =>
		                          	array('Key' => $this->config_object["fedex_account_settings"]["auth_key"], 
		                            	  'Password' => $this->config_object["fedex_account_settings"]["password"])
									);
		$this->fedexcredentialdetail = array('AccountNumber' => $this->config_object["fedex_account_settings"]["account_number"], 
											 'MeterNumber' => $this->config_object["fedex_account_settings"]["meter_number"]);
		$this->fedexvalidationoptions = array('MaximumNumberOfMatches' => 1,
				                             'StreetAccuracy' => 'LOOSE',
				                             'DirectionalAccuracy' => 'LOOSE',
				                             'CompanyNameAccuracy' => 'LOOSE',
				                             'ConvertToUpperCase' => 1,
				                             'RecognizeAlternateCityNames' => 1,
											 'VerifyAddresses' => 1);
						
		//print_r($this->upss);
		$string = file_get_contents("../content/states_titlecase.json");
		$this->statelist=json_decode($string, true);
		foreach ($this->statelist as $key => &$value) {
			foreach ($value as $key => &$valueA) {
				$valueA = strtolower($valueA);
			}
		}
		$string = file_get_contents("../js/shippingmethods.json");
		$this->shippingmethods = json_decode($string, true);
		
		//print_r($this->shippingmethods["shipping_method"]["options"]);
		foreach ($this->shippingmethods["shipping_method"][0]["options"] as $key => &$value) {
			$this->shippingarray[$value["value"]] = $value["name"];
		}
		foreach ($this->shippingmethods["shipping_method"][1]["options"] as $key => &$value) {
			$this->shippingarray[$value["value"]] = $value["name"];
		}	
		//$jsonfile = file_get_contents("../js/batchship.json");
		//$jsonshipping = json_decode($jsonfile, true);
		// Email Settings
		$this->email_credentials = $this->config_object["email_settings"];
		$this->email_credentials["batchship_mailinglist_to"] = explode(";", $this->email_credentials["batchship_mailinglist_to"]);
		
		$this->allowedExts = explode("|", $this->config_object["upload_settings"]["allowed_file_extensions"]);
		$this->maxfilesize = $this->config_object["upload_settings"]["max_filesize"];
		$this->allowed_file_type = explode("|", $this->config_object["upload_settings"]["allowed_file_type"]);
		$this->outputDelimHeader = array("SeqNumber", "JobNumber", "Kit", "PONumber", "CompanyName", 
				"AttentionTo", "Add1", "Add2", "Add3", "City", "StateProv", "Country", "Zip", "PhoneNum", 
				"Service", "BillTo", "Package", "Weight", "SatDelivery", "ResDelivery", "GeneralDescription",
				"InvReasonForExport", "ImporterSameAsShipTo", "GoodsDescriptionOfGoods", "GoodsInvNAFTACOCountryOfOrigin",
				"GoodsInvoiceUnits", "GoodsInvoiceUnitsOfMeasure", "GoodsInvoiceSEDUnitPrice", "GoodsSEDCOGrossWeight",
				"ThirdPartyCompanyName", "ThirdPartyStreet", "ThirdPartyCountry", "ThirdPartyZip",	"ThirdPartyCity",
				"ThirdPartyState", "ThirdPartyAccount",	"ShipDate",	"DeliverBy", "Blind Company", "Dimension", "PackID",
				"Misc Reference 1", "Misc Reference 2","Misc Reference 3",	"Misc Reference 4", "Misc Reference 5",	
				"Insurance", "Shipment_Type","Shipment_Type_Value");
		$this->error_array = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI");
		
		$this->spreadsheetHeaders = array("Store Name" => "store_name",	"Store Number" => "store_number",
							"Address1" => "address1", "Address2" => "address2",	"Address3" => "address3", "City" => "city",
							"State/Province" => "state_province", "Postal Code" => "postal_code", "Country" => "country",
							"Attn" => "attn", "Phone Number" =>	"phone_number", "KitNumber"	=> "kit_number", "Weight" => "weight",
							"Dimensions" => "dimensions", "Misc Reference 1" => "misc_reference_1",
							"Misc Reference 2" => "misc_reference_2", "Misc Reference 3" => "misc_reference_3",
							"Misc Reference 4" => "misc_reference_4", "Misc Reference 5" => "misc_reference_5",
							"Insurance" =>	"insurance","ThirdPartyCompanyName"=>"thirdparty_company",	
							"ThirdPartyStreet"=> "thirdparty_street","ThirdPartyCountry"=>"thirdparty_country",
							"ThirdPartyZip" => "thirdparty_zip","ThirdPartyCity"=>	"thirdparty_city",
							"ThirdPartyState" => "thirdparty_statep", "ThirdPartyAccount"=>"thirdparty_account", 
							"Shipping Method" => "shipping_method", "Po Number" => "po_number", "Multiple Shipment" => "multiplier", "Package Type" => "package_type");
		$this->spreadsheetHeadersLower = array_change_key_case($this->spreadsheetHeaders, CASE_LOWER);
	}
	function getConfiguration(){
		//$returnArray = array();
		//foreach ($this->config_object as $key => $value) {
		//	$returnArray[][$key] = ""; 
		//}
		
		//var_dump($returnArray);
		return $this->config_object;
	}
	/*
	 * bs_UpdateAddressLine
	 * 
	 */
	function bs_UpdateAddressLine($batchid, $addressLine){
		global $dbconn;
		$returnArray = array();
		if(count($addressLine) == 5){
			$query = "UPDATE batchship_items SET address1=$1, city=$2, state_province=$3, postal_code=$4, country=$5 WHERE id=$6";
			$returnArray["query"] = $query;
			if(	($result = pg_query_params($dbconn, $query, array($addressLine[0],$addressLine[1],$addressLine[2],$addressLine[3],$addressLine[4],$batchid) ) 
				) == FALSE)
			{
				$returnArray["status"] = 500;
				$returnArray["message"] = "Error: Could not process query";
			}
			else{
				$returnArray["status"] = 200;
				$rowsaffected = pg_affected_rows($result);
				$returnArray["message"] = "{$rowsaffected} rows affected";
			}
		}				
		else{
			$returnArray["status"] = 500;
			$returnArray["message"] = "Error: Could process address because the number of lines dont match";	
		}
		return $returnArray;
	}
	function exportToPipeDelim($requestJSON){
		$filename = "";
		try{
			
			//$batch_id = uniqid("batch_");
			$arrayResult = array();
			
			$batchid = $requestJSON->batchid;
			$jobNumber = $requestJSON->jobnumber;
			$shipDate = $requestJSON->shipDate;
			$blindshipcompany = $requestJSON->blindshipcompany;
			//*************************************************
			
			$formPONumberOverride = $requestJSON->useformponumbers;
			$POnumber = $requestJSON->ponumber;
			
			//*************************************************
			$packageType = $requestJSON->packageType;
			$multipleshipquantities = $requestJSON->multipleshipquantities;
			$billTo = (isset($requestJSON->billto)) ? $requestJSON->billto : null;
			$thirdpartyaccountinfo = (isset($requestJSON->thirdpartyaccount)) ? $requestJSON->thirdpartyaccount : null;
			$datenow = date("Ymd");
			
			$filename = "../temp/{$datenow}-{$_SESSION["username"]}-{$batchid}.txt";
			if (!$handle = fopen($filename, 'w')) {
		  		throw new Exception("Cannot open file {$fullFileName}");
			}
			
			$exportContent = "";
			$exportContent = implode("|", $this->outputDelimHeader) . "\n";
			//*************************************************************
			$batch_seqid = "";
			
			if( ($result = pg_query_params($this->dbconn, "SELECT * FROM import_queue WHERE id = $1", array($batchid))) != FALSE){
				$rows = pg_fetch_all($result);
				$batch_seqid = $rows[0]["seqid"];
			}
				
			
			
			if( ($result = pg_query_params($this->dbconn, "SELECT * FROM batchship_items WHERE batch_id = $1 ORDER BY kit_number, shipping_method, country, state_province, postal_code", array($batchid))) == FALSE)
				throw new Exception("Could not query batchship items");
			
			$rows = pg_fetch_all($result);
			$description = (isset($requestJSON->description)) ? $requestJSON->description : "";
			foreach($rows as $index => $value)
			{
				
				$unitPrice = 0;
				$packageType = ($value["package_type"] != "") ? $value["package_type"] : $packageType;
				if(strcasecmp($packageType, "Letter") == 0){
					$description = ($description == "") ? "Printed Material" : $description;
					$unitPrice = "0";
					$dimension = "";
				}
				else{
					$description = ($description == "") ? "Printed Material $1.00 Value" : $description;
					$unitPrice = "1";
					$dimension = $value["dimensions"];
				}
				
				
				$ThirdPartyCompanyName = "";
				$ThirdPartyStreet = "";
				$ThirdPartyCountry = "";
				$ThirdPartyZip = "";
				$ThirdPartyCity = "";
				$ThirdPartyState = "";
				$ThirdPartyAccount = "";
				
				// if third party account is selected we overide all rows
				if($billTo == "Third Party"){
					$ThirdPartyCompanyName = $thirdpartyaccountinfo->company_name;
					$ThirdPartyStreet = $thirdpartyaccountinfo->street;
					$ThirdPartyCountry = $thirdpartyaccountinfo->country;
					$ThirdPartyZip = $thirdpartyaccountinfo->zipcode;
					$ThirdPartyCity = $thirdpartyaccountinfo->city;
					$ThirdPartyState = $thirdpartyaccountinfo->state;
					$ThirdPartyAccount = $thirdpartyaccountinfo->accountnumber;				
				}
				else{
					$ThirdPartyCompanyName = $value["thirdparty_company"];
					$ThirdPartyStreet = $value["thirdparty_address2"];
					$ThirdPartyCountry = $value["thirdparty_country"];
					$ThirdPartyZip = $value["thirdparty_zip"];
					$ThirdPartyCity = $value["thirdparty_city"];
					$ThirdPartyState = $value["thirdparty_statep"];
					$ThirdPartyAccount = $value["thirdparty_account"];
				}
				// replace anything that is set at the row level
			
				//$useformponumbers = (isset($requestJSON->useformponumbers)) ? $requestJSON->useformponumbers : false;
				//$iiponumber = ($requestJSON->useformponumbers == true) ? $value["po_number"] : $POnumber;
				if(strlen($requestJSON->ponumber) > 0)
				{
					$value["po_number"] = $requestJSON->ponumber;
				}
				//$value["po_number"] = ($formPONumberOverride == TRUE) ? $requestJSON->ponumber : $value["po_number"];
				
				// Set the correct zip code - Nextship doesnt support extended zipcodes
				// So we need to strip them out
				if($value["country"] == "US"){
					$tempzipcode = $value["postal_code"];
					if( (preg_match("/(\d{5})(\s{0,})-(\s{0,})(\d{4})/", $value["postal_code"])) > 0){
						$tempzipcode = explode("-", $value["postal_code"]);
						$tempzipcode = "{$tempzipcode[0]}-{$tempzipcode[1]}";
					}
					if( (preg_match("/(\d{5})(\s{1,})(\d{4})/", $value["postal_code"])) > 0){
						$tempzipcode = explode(" ", $value["postal_code"]);
						$tempzipcode = "{$tempzipcode[0]}-{$tempzipcode[1]}";	
					}	
					// first part for teritories
					if($value["stateprov"] == "PR" || strtolower($value["stateprov"]) == "vi" || strtolower($value["stateprov"]) == "gu" || strtolower($value["stateprov"]) == "mp"){
						$tempzipcode = explode("-", $value["postal_code"]);
						$tempzipcode = $tempzipcode[0];
					}
					while( (strlen($tempzipcode)) < 5){
				    	$tempzipcode = "0" . $tempzipcode;
					}
					
					$value["postal_code"] = $tempzipcode;
					
				}
				
				//*********************************************************************
				// Correct the phone number 
				// if there is not phone number present set the phone number to FCP
				
				//if($value["phone_number"] == "") {
				$value["phone_number"] = preg_replace('/-|\(|\)|\s+/', '', trim($value["phone_number"]));
				if(strlen($value["phone_number"]) == 0) {
				  $value["phone_number"] = "5856639000";
				}
				else {
				  // let's strip all nonsense out
				  //$value["phone_number"] = preg_replace('/\D+/', '', $value["phone_number"]);
				  if (strlen($value["phone_number"]) < 10) // assume it's missing area code. and it's 585 because thats how we type numbers here
				    $value["phone_number"] = "585{$value['phone_number']}";
				}
				
				
				$exportRow = array();
				$exportRow[0] = uniqid(); // unique SeqNumber
				$exportRow[1] = $jobNumber; // JobNumber
				$exportRow[2] = $value["kit_number"]; // Kit
				//$exportRow[3] = $POnumber . " - " . $value["misc_reference_1"];
				//*******************************************************************************
				$exportRow[3] = (isset($value["misc_reference_1"])) ? "{$value["po_number"]} - {$value["misc_reference_1"]}" : $value["po_number"];
				//*******************************************************************************
				$exportRow[4] = $value["store_name"] . " #" . $value["store_number"]; //CompanyName
				$exportRow[5] = $value["attn"]; // AttentionTo
				//*******************
				$addressArr = array($value["address1"], $value["address2"], $value["address3"]);
				$arrCnt = 0;
				foreach ($addressArr as $key => $value) {
					if(strlen($value) > 0){
						array_push($exportRow, $value);
						$arrCnt++;
					}
				}
				for ($i=$arrCnt; $i < 3; $i++) { 
					array_push($exportRow, "");
				}
				//*******************
				//$exportRow[6] = $value["address1"]; // Add1
				//$exportRow[7] = $value["address2"]; // Add2
				//$exportRow[8] = $value["address3"]; // Add3
				
				$exportRow[9] = $value["city"]; // City
				$exportRow[10] = $value["state_province"]; // StateProv
				$exportRow[11] = $value["country"]; // Country
				$exportRow[12] = $value["postal_code"]; // Zip/Postal Code
				$exportRow[13] = $value["phone_number"]; // PhoneNum
				$exportRow[14] = $value["shipping_method"]; // Service
				$exportRow[15] = $billTo; // BillTo
				$exportRow[16] = $packageType; // Package
				$exportRow[17] = $value["weight"]; // Weight
				$exportRow[18] = "N"; // SatDelivery
				$exportRow[19] = "N"; // ResDelivery
				$exportRow[20] = $description; // GeneralDescription
				$exportRow[21] = "Sale"; // InvReasonForExport
				$exportRow[22] = "Y"; // ImporterSameAsShipTo
				$exportRow[23] = $description; // GoodsDescriptionOfGoods
				$exportRow[24] = "US"; // GoodsInvNAFTACOCountryOfOrigin
				$exportRow[25] = "1"; // GoodsInvoiceUnits
				$exportRow[26] = "EA"; // GoodsInvoiceUnitsOfMeasure
				$exportRow[27] = $unitPrice; // GoodsInvoiceSEDUnitPrice
				$exportRow[28] = ceil(floatval($value["weight"])); // GoodsSEDCOGrossWeight
				$exportRow[29] = $ThirdPartyCompanyName; // ThirdPartyCompanyName
				$exportRow[30] = $ThirdPartyStreet; // ThirdPartyStreet
				$exportRow[31] = $ThirdPartyCountry; // ThirdPartyCountry
				$exportRow[32] = $ThirdPartyZip; // ThirdPartyZip
				$exportRow[33] = $ThirdPartyCity; // ThirdPartyCity
				$exportRow[34] = $ThirdPartyState; // ThirdPartyState
				$exportRow[35] = $ThirdPartyAccount; // ThirdPartyAccount
				$exportRow[36] = $shipDate; // ShipDate
				$exportRow[37] = ""; // DeliverBy
				$exportRow[38] = $blindshipcompany; // Blind Company
				$exportRow[39] = $dimension;// Dimension
				$exportRow[40] = ""; // Pack id -- ignore this, was used at some point for custom workflow for FPC (see #8259)
				$exportRow[41] = ($value["misc_reference_1"] == "") ? $jobNumber : $value["misc_reference_1"]; // Misc Ref 1 or Job if blank
				//********************************************************************************
				
				$exportRow[42] = ($value["misc_reference_2"] == "") ? // Misc Ref 2 or PO if blank
										$value["po_number"] 
									: 	$value["misc_reference_2"]; 
				//********************************************************************************
				$exportRow[43] = ($value["misc_reference_3"] == "") ? $value["kit_number"] : $value["misc_reference_3"]; // Misc Ref 3 or Kit if blank
				$exportRow[44] = $value["misc_reference_4"]; // Misc Ref 4
				$exportRow[45] = $value["misc_reference_5"]; // Misc Ref 5 -- overriden for multiples
				$exportRow[46] = round(floatval($value["insurance"]),2);
				$exportRow[47] = "Batch Ship"; // Shipment_Type
				$exportRow[48] = $batch_seqid;
				//$exportRow[48] = $batch_seqid; // Shipment_Type_Value
				//$lineitem = implode("|", $exportRow) . "\n";
				
				$multiplier = ( ($value["multiplier"] != 0) || ($value["multiplier"] != "") ) ? $value["multiplier"] : 0;
				
				//$exportContent .= implode("|", $exportRow) . "\n";
				if($value["multiplier"] == 0 || $multipleshipquantities == FALSE){
					$lineitem = implode("|", $exportRow) . "\n";
					$exportContent .= $lineitem;
				}
				else{
					for ($i=0; $i < $multiplier; $i++) {
						if($i > 0){
							$exportRow[0] = uniqid();
						}
						$lineitem = implode("|", $exportRow) . "\n";
						$exportContent .= $lineitem;
					}
				}
			}
			//*************************************************************
			// write export file to destination directory on server
			
			if (fwrite($handle, $exportContent) === FALSE) {
				throw new Exception("Cannot write to file {$fullFileName}");
			}
			//close text file write
			fclose($handle);
		}
		catch(Exception $e){
			return FALSE;
		}
		return $filename;
	}


	// Export
	function exportBatchToExcel($batchid){
		
		$objPHPExcel = new PHPExcel();
		$columns = array();
		$header = array();
		foreach($this->spreadsheetHeaders as $index => $values){
			$columns[] = $values;
			$header[] = $index;
		}
		$columns = implode(", ", $columns);
	
		if( ($result = pg_query_params($this->dbconn, "SELECT {$columns} FROM batchship_items WHERE batch_id = $1", array($batchid))) == TRUE)
		{
			try
			{
				$rowCount = 1;
				$datenow = date("Ymd");
				$filename = "../temp/{$datenow}-{$_SESSION["username"]}-{$batchid}.xlsx";
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getActiveSheet()->fromArray($header, NULL, 'A'.$rowCount);
				$rowCount++;
				
				while ($row = pg_fetch_assoc($result)) {
					$objPHPExcel->getActiveSheet()->fromArray($row, NULL, 'A'.$rowCount);
					$rowCount++;
				}
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");	
				$objWriter->save($filename);
				return $filename;
			}
			catch(Exception $e){
				throw new Exception($e->getMessage());
			}	
		}
		return FALSE;
	}




	/*function email_toperson($emailTo, $emailSubject, $emailBody){
		try{
			$headers = array("From" => "brett_mosley@fcp.biz",
							"FromName" => "Batch Ship Request System",
							"To" => $emailTo,
							"EmailSubject" => $emailSubject,
							"EmailBody" => $emailBody
							);
			$mail = new PHPMailer;
			$mail->isSMTP();                                     			// Set mailer to use SMTP
			$mail->Host = $this->email_credentials["server_hostname"];  	// Specify main and backup SMTP servers
			$mail->Username = $this->email_credentials["server_account"];   // SMTP username
			$mail->Password = $this->email_credentials["server_password "]; // SMTP password
			
			$mail->From = $headers["From"];
			$mail->FromName = $headers["FromName"];
			$mail->addAddress($headers["To"]);     	// Add a recipient
			
			$mail->addReplyTo($headers["From"]);
			$mail->WordWrap = 50;                                 			// Set word wrap to 50 characters
			$mail->isHTML(TRUE);                                 			// Set email format to HTML
			$mail->Subject = $headers["EmailSubject"];
			$mail->Body = $headers["EmailBody"];
			$mail->$AltBody = $headers["EmailBody"];
			if(!$mail->send()) {
				throw new Exception("Message could not be sent" . $mail->ErrorInfo);
			} else {
				
			   $returnArray = array("status" => 200, "message" => 'Sending Email to {$emailTo}');
			}
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => "Mailer Error" . $e-getMessage());
		}
		
	}*/

	function email_to($batchid, $headers){
		$arrayResult = array();
		if( (isset($headers["From"]) == FALSE))
			throw new Exception("No from user was found");
		if( (isset($headers["FromName"]) == FALSE))
			throw new Exception("No from name user was found");
		if( (isset($headers["To"]) == FALSE))
			throw new Exception("No to user was found");
		if( (isset($headers["EmailSubject"]) == FALSE))
			throw new Exception("No email subject found");
		if( (isset($headers["EmailBody"]) == FALSE))
			throw new Exception("No email body found");
		$mail = new PHPMailer;
		$mail->isSMTP();                                     			// Set mailer to use SMTP
		$mail->isHTML(FALSE);
		$mail->Host = $this->email_credentials["server_hostname"];  	// Specify main and backup SMTP servers
		$mail->Username = $this->email_credentials["server_account"];   // SMTP username
		$mail->Password = $this->email_credentials["server_password"]; // SMTP password
		$mail->From = $headers["From"];
		$mail->FromName = $headers["FromName"];
		foreach ($headers["To"] as $key => $value) {
			$mail->addAddress($value);
		}	
		$mail->addReplyTo($headers["From"]);
		//$mail->WordWrap = 50;                                 			// Set word wrap to 50 characters
		foreach ($headers["EmailAttachment"] as $key => $value) {
			$mail->addAttachment($value);
		}
		                               			// Set email format to HTML
		$mail->Subject = $headers["EmailSubject"];
		//$mail->Body = $headers["EmailBodyHtml"];
		//$mail->AltBody = $headers["EmailBody"];
		$mail->Body = $headers["EmailBody"];
		//$mail->AltBody = $headers["EmailBody"];
		if(!$mail->send()) {
			throw new Exception("Message could not be sent <br/>Mailer Error: " . $mail->ErrorInfo);
		} else {
			$arrayResult = "Mail Successfully Delivered";
		}
		return $arrayResult;
	}
	function processBatchShipRequest($jsonString)
	{
		$returnArray = array();
		$mailArray = array();
		try{
			
			$batchid = (isset($jsonString->batchid)) ? $jsonString->batchid : null;
			$jobNumber = (isset($jsonString->jobnumber)) ? $jsonString->jobnumber : null;
			$shipDate = (isset($jsonString->shipDate)) ? $jsonString->shipDate : null;
			$poNumber = (isset($jsonString->ponumber)) ? $jsonString->ponumber : null;
			$billTo = (isset($jsonString->billto)) ? $jsonString->billto : null;
			$thirdpartyaccount = (isset($jsonString->thirdpartyaccount)) ? $jsonString->thirdpartyaccount : null;
			$thirdpartylabel = (isset($thirdpartyaccount->label)) ? $thirdpartyaccount->label : "N/A";
			$packageType = (isset($jsonString->packageType)) ? $jsonString->packageType : null;
			$companyName = (isset($jsonString->companyname)) ? $jsonString->companyname : null;
			$shipFrom = (isset($jsonString->shipFrom)) ? $jsonString->shipFrom : null;
			$comments = (isset($jsonString->comments)) ? $jsonString->comments : null;
			$rate = ($shipFrom == "Rate Only") ? "YES" : "No";
			$promocode = (isset($jsonString->promocode)) ? $jsonString->promocode : "";
			$shippingMethod = "";
			if( ($result = pg_query_params($this->dbconn, "SELECT shipping_method FROM batchship_items WHERE batch_id = $1 GROUP BY shipping_method", array($batchid))) != FALSE) {
				$rows = pg_fetch_all($result);
				foreach ($rows as $key => $value) {
					$shippingMethod .= (strlen($shippingMethod) > 0) ? ", {$value["shipping_method"]}" : "{$value["shipping_method"]}";
				}
			}
			else{
				throw new Exception("Could not find a shipping type was not supplied");	
			}
			if($batchid == null)		throw new Exception("Batchid was not supplied");
			if($jobNumber == null)		throw new Exception("jobNumber was not supplied");
			if($shipDate == null)		throw new Exception("shipDate was not supplied");
			if($poNumber == null)		throw new Exception("poNumber was not supplied");
			if($billTo == null)			throw new Exception("billTo was not supplied");
			if($billTo == "Third Party" && $thirdpartyaccount == null)		
				throw new Exception("No Thirdparty shipping account was select");
			if($packageType == null)	throw new Exception("packageType was not supplied");
			if($companyName == null)	throw new Exception("companyName was not supplied");
			
			// Dump Excel file As It
			if( (($filename = $this->exportBatchToExcel($batchid)) != FALSE) && (($pipefilename = $this->exportToPipeDelim($jsonString)) != FALSE) ){
					
				//Build Email Body
				//replace any dashes so as not to screw up the auto-due-date set scrip in RT
				$promoStripped = str_replace("-","_",$promocode);	
				$sdate = date("Y-m-d",strtotime($jsonString->shipDate));
				$emailSubject = "{$this->appcodename} - {$jobNumber} for {$companyName} {$promoStripped} - {$jsonString->shipDate}";
				$dlfile = explode("/", $pipefilename);
				
$emailBody = <<<EOT
Instructions: Please download the attached text file to the 'batch_in' folder on the Batch Ship server (/Nextship/batch_in).\n\n
After labels are printed, please resolve this ticket to alert the user that their batch ship request has been processed.\n\n
Project: batchship\n
Job Number: {$jobNumber}\n
Customer: {$companyName}\n
Site: Mt. Read\n
Promo: {$promocode}\n
PO: {$poNumber}\n
Ship Date: {$sdate}\n
Due Date: {$sdate}\n
Shipping Method: {$shippingMethod}\n
Third Party Billing Acct: {$thirdpartyaccount->accountnumber}\n
Bill to {$billTo}\n
Number of kits: {$jsonString->totaladdresses}\n
Number of shipments: {$jsonString->totaladdresses}\n
Rate Only: {$rate}\n
{$comments}\n
Generated by {$this->appname}
EOT;

				$emailREPBody = str_replace("\n", "<br/>", $emailBody);
				$emailHTMLBody = "<html><head><title>refre</title></head><body>{$emailREPBody}</body></html>";		
				
				// Add Fields to excel file
				$csr_email = "";
				if( ($result = pg_query_params($this->dbconn, "SELECT csremail FROM import_queue WHERE id = $1", 
					array($jsonString->batchid))) != FALSE){
					$row = pg_fetch_array($result);
					$csr_email = $row["csremail"];
				}
				$this->email_credentials["batchship_mailinglist_to"][] = $csr_email;
				$headers = array("From" => $csr_email,
									"FromName" => "Batch Ship Request System",								
									"To" => $this->email_credentials["batchship_mailinglist_to"],
									"EmailSubject" => $emailSubject,
									"EmailBody" => $emailBody,
									//"EmailBody" => $emailBody,
									//"EmailBodyHtml" => $emailHTMLBody,
									"EmailAttachment" => array($filename, $pipefilename)															
							);
				// Email Excel file to the user
				$mailArray = $this->email_to($jsonString->batchid, $headers);
				
				$datenow = date("Ymd");
				//$dateYear = date("Y");
				//$dateMonth = date("m");
				$datestr = date("Y/m/Ymd");
				if( ($result = pg_query_params($this->dbconn, "SELECT * FROM import_queue WHERE id=$1", array($jsonString->batchid))) == FALSE)
				{
					throw new Exception("Could not select from batchship items");
				}
				$row = pg_fetch_assoc($result);
				//$dirFragement = "../archive/{$datestr} - {$jsonString->batchid}";
				$dirFragement = $row["archivepath"];
				$outputfiles = array();
				$outputfiles["excelinput"] = $filename;
				$outputfiles["exceloutput"] = "{$dirFragement}/output_{$datenow}-bmosley-{$jsonString->batchid}.xlsx";
				$outputfiles["pipeinput"] = $pipefilename;
				$outputfiles["pipeoutput"] = "{$dirFragement}/output_{$datenow}-bmosley-{$jsonString->batchid}.txt";
				$archiveExists = FALSE;
				if(!file_exists($dirFragement))
				{
					if(mkdir($dirFragement, 0777, TRUE)){
						$archiveExists = TRUE;
					}
					else{
						throw new Exception("Error: Could not create directory {$dirFragement}");
					}
				} 
				else {
					$archiveExists = TRUE;
				}
				if($archiveExists == TRUE){
					//Copy Output files to 
					copy($outputfiles["excelinput"], $outputfiles["exceloutput"]);
					copy($outputfiles["pipeinput"], $outputfiles["pipeoutput"]);
					unlink($outputfiles["excelinput"]);
					unlink($outputfiles["pipeinput"]);
					// Set import queue to processed 	
					$this->autoUpdateArchiveJSON($jsonString->batchid);	
					if(($result = pg_query_params($this->dbconn, "UPDATE import_queue SET processed='true' WHERE id=$1", array($jsonString->batchid))) == FALSE){
						throw new Exception("Error: Prepared statement failed");
					}
					
				}
				$returnArray = array("status" => 200, 
										"message" => "Email successfully sent to batchship queue {$datestr}",
										"outputfiles" => $outputfiles, 
										"input" => $jsonString, 
										"mailer" => $mailArray);
			}
			else{
				throw new Exception("Could not create excel dump");
			}
			
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;
	}
	function createArchive($batchid){
		$dirFragement = "../archive/".date("Ym")."/{$batchid}";
		$archiveExists = FALSE;
		// Check if the archive exists then create the archive
		if(!file_exists($dirFragement)){
			if(mkdir($dirFragement, 0777, TRUE)){
				$archiveExists = TRUE;
			}
			else{
				throw new Exception("Error: Could not create directory");
			}
		}
		else{
			$archiveExists = TRUE;
		}
		// Add a record in the database to store directory path for this archive
		// So we can store subsequent files
		if( ($result = pg_query_params($this->dbconn, "UPDATE import_queue SET archivepath=$2 WHERE id = $1", array($batchid, $dirFragement))) != FALSE){
			
		}
	}
	function addtoArchive($batchid, $inputfilename, $prefix = null){
		// Get the archivepath from the batch_item
		if( ($result = pg_query_params($this->dbconn, "SELECT * FROM import_queue WHERE id = $1", array($batchid))) != FALSE){
			// Copy the file from temp
			$row = pg_fetch_array($result);
			$file = end(explode("/", $inputfilename));
			$outputfilename = ($prefix != null) ? ($row["archivepath"] . "/{$prefix}_{$file}") : ($row["archivepath"] . $file);
			copy($inputfilename, $outputfilename);
			unlink($inputfilename);
			$filepathjson = array();
				$filepathjson[] = array("title" => "input", 
										"filename" => $file, 
										"filepath" => $outputfilename);
			// Delete file from temp
			pg_query_params($this->dbconn, "UPDATE import_queue SET filename=$2, filepath=$3, filepathjson=$4 WHERE id=$1", 
									array($row["id"], $file, $outputfilename, json_encode($filepathjson))); 
			// Update database links for files
		} 
	}
	function autoUpdateArchiveJSON($batchid){
		if( ($result = pg_query_params($this->dbconn, "SELECT * FROM import_queue WHERE id = $1", array($batchid))) == FALSE){
			throw new Exception("Could not find a entry from the import queues");
		}
		$row = pg_fetch_array($result); 
		$filepathjson = array();
		
		if ($handle = opendir($row["archivepath"])) {
			while (false !== ($entry = readdir($handle))) {
				
				if(preg_match("/^input_[a-zA-Z.0-9]*/", $entry))
				{
					$filepathjson[] = array("title" => "input", "filename" => $entry, "filepath" => $row["archivepath"]. "/{$entry}");
		       		//echo "$entry\n";
				}
				if(preg_match("/^output_[a-zA-Z.0-9]*/", $entry))
				{
					$te = explode(".", $entry);
					$filepathjson[] = array("title" => "output_" . $te[1], "filename" => $entry, "filepath" => $row["archivepath"]. "/{$entry}");
		       		//echo "$entry\n";
				}
		    }
		}
		
		if( ($result = pg_query_params($this->dbconn, "UPDATE import_queue SET filepathjson = $2 WHERE id = $1", array($batchid, json_encode($filepathjson)))) == FALSE){
			throw new Exception("could not creat encoded file " . $batchid);
		}
	}
	/*
	 * BatchUploadFile 
	 * This function is to upload files to the batchship database
	 */
	 function batchUploadFile($requestdata, &$fileHandle)
	{
		$process_results=null;
		try{
			//return(array("data" => $this->statelist));
			// This is needed for all 
			$statusmessage = "";
			$filepathjson = array();
			// Only supplied if we are doing a reupload
			$batchid = (isset($requestdata["batchid"])) ? $requestdata["batchid"] : null;
			$files = (isset($fileHandle)) ? $fileHandle : null;
			if($files == null){
					throw new Exception("Error: No files were supplied");
			}
			// Check if file handle is null
			if($files != null)
			{
				
				// The are supplied if we are uploading for the first time 
				$jobnumber = (isset($requestdata["jobnumber"])) ? $requestdata["jobnumber"] : null;
				$customername = (isset($requestdata["customername"])) ? $requestdata["customername"] : null;
				$userid = (isset($_SESSION["userid"])) ? $_SESSION["userid"] : null;
				$csremail  = (isset($requestdata["csremail"])) ? $requestdata["csremail"] : null;
				$query = null;
				$queryArray = null;
				
				$files = $files["file"];
				
				if ($files["error"] > 0) {
					throw new Exception("Error: " . $files[0]["error"]);
				}
				
				if(in_array($files["type"], $this->allowed_file_type ) == FALSE){
					throw new Exception("Error: File type not allowed ");
				}
				
			
				$filepathjson[] = array("title" => "input", 
										"filename" => $files["name"], 
										"uploadfilepath" => "../temp/{$files["name"]}",
										"tempfilepath" => $files["tmp_name"],
										"timestamp" => date('Y-m-d H:i:s')
										
										);
									
			
				if($batchid == null){
					// this will be a new upload job
					if($jobnumber == null)
						throw new Exception("Error: Job Number empty");
					if($customername == null)
						throw new Exception("Error: Customer name empty");		
					if($userid == null)
						throw new Exception("Error: UserID empty");	
					
					$query = pg_prepare($this->dbconn, 
						"UPLOAD", 
						"INSERT INTO import_queue (filename, fcp_jobnumber, company_name, filepath, user_id, lastmodified, filepathjson, uuid, seqid, csremail) VALUES ($1, $2, $3, $4, $5, $6, $7, uuid(), $8, $9) RETURNING id");
					$queryArray = array($filepathjson[0]["filename"],
										$jobnumber,
										$customername,
										$filepathjson[0]["uploadfilepath"],
										(int)$userid,
										$filepathjson[0]["timestamp"],
										json_encode($filepathjson),
										uniqid("batch_"),
										$csremail
										);
				}
				else{
					// this will be a reupload 
					if( ($query = pg_prepare($this->dbconn, 
						"UPLOAD", 
						"UPDATE import_queue SET filename = $1, filepath = $2, user_id = $3, lastmodified = $4, filepathjson = $5 WHERE id=$6 RETURNING id")
						) == FALSE)
					{
						throw new Exception("Could not create reupload");		
					}
					$queryArray = array($filepathjson[0]["filename"],
										$filepathjson[0]["uploadfilepath"],
										(int)$userid,
										$filepathjson[0]["timestamp"],
										json_encode($filepathjson),
										$batchid);
					$this->bs_DeleteAddressesByBatchID($batchid);
				}
				
				if( ($result = pg_query($this->dbconn, "BEGIN TRANSACTION")) == TRUE){
					if( ($uploadresult = pg_execute($this->dbconn, "UPLOAD", $queryArray)) == FALSE)
					{
						throw new Exception("Error: param Could not process query " . pg_errormessage($this->dbconn));
					}
					if( ($row = pg_fetch_all($uploadresult)) != FALSE){
						$batchid = $row[0]["id"];
						$uploadstatus = false; 
						if ( ($uploadstatus = move_uploaded_file($filepathjson[0]["tempfilepath"], $filepathjson[0]["uploadfilepath"]))){
							// Validate Headers
							$this->batchUploadValidate($filepathjson[0]["uploadfilepath"]);
							//return array("aarr" => "eee");
							$this->logging->startTimeStamp();
							$process_results = $this->bs_ProceessAddresses($row[0]["id"]);
							$this->logging->endTimeStamp();
							$timediff = $this->logging->timeDifference();
							$this->logging->loglineFlatfile("subject=proccess_addresses;description=addresses processed;batch_id=1;user_id=1;");
							$this->logging->loglineFlatfile("subject=timediff;description={$timediff}s;batch_id=1;user_id=1;");
							//return array("aarr" => $process_results);
							$_REQUEST["batchid"] = $row[0]["id"];
							//$this->logging->startTimeStamp();
							$validate_results = $this->validateAddress($_REQUEST);
							//$this->logging->endTimeStamp();
							//$timediff = $this->logging->timeDifference();
							$this->logging->loglineFlatfile("subject=address_validated;description=addresses validated;batch_id={$batchid};user_id={$batchid};");
							//$this->logging->loglineFlatfile("subject=timediff;description={$timediff}s;batch_id=1;user_id=1;");
							// Collect results and print something usefull
							$statusmessage = ($uploadstatus == true) ? "Uploaded Succesfully" : "Upload Failed";
							$statusmessage .= ($process_results["status"] == 200) ? "Processed Successfully" : "Processed Failed";
							$statusmessage .= ($validate_results["status"] == 200) ? "Validated Successfull" : "Validation Failed";		
						}
					}
					if( (pg_query($this->dbconn, "END TRANSACTION")) == TRUE){
						
						$this->createArchive($batchid);
						$this->addtoArchive($batchid, $filepathjson[0]["uploadfilepath"], "input");
						$this->autoUpdateArchiveJSON($batchid);
						// move files to the archive
						// update file entries in batch
					}
				}
			}
//Uploaded Successfully by {$_SESSION["userid"]} <br/>{$statusmessage}
			$returnArray = array("status" => 200, 
									"message"=> "<b>Success</b> Batch {$batchid} Uploaded Successfully" , 
									"data" =>
										array(
											"process_results" => $process_results
										)
								);
			
			
			//addToEventLog(BATCH_UPLOAD, "BATCH {$batchid} was uploaded successfully by user {$_SESSION["username"]}.<br/> {$re}", $row["id"]);
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message"=> $e->getMessage());
			// ROLL BACK to fix any changes
			/*if( ($result = pg_query($this->dbconn, "ROLLBACK")) == FALSE){
				throw new Exception("Error: ROLLBACK was not successful");		
			}*/
		}
		return $returnArray;
	}
	function compareheaders($array, $index, $value){
		foreach ($variable as $key => $value) {
			
		}
	}
	
	function batchUploadValidate($filename){
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		$worksheet = $objPHPExcel->getSheet(0);
		$worksheet_array = $worksheet->toArray();
		// get the columns that only appear in the spreadsheet slice everything else off
		$list = array_slice(array_map('strtolower', $worksheet_array[0]), 0, count($this->spreadsheetHeaders));
		// dump the keys ofthe the headers to an array
		$headercol = array_keys($this->spreadsheetHeaders);
		//make the keys lowercase
		$headercol = array_map('strtolower', $headercol);
		// find the missing keys
		$tr = array_diff($headercol, $list);
		// then display the error
		if(count($tr) > 0){
			$lengthr = "";
			foreach($tr as $key => $value){
				$col_number = array_search($value, $headercol);
				$colerr = (isset($this->error_array[$col_number])) ? "Column {$this->error_array[$col_number]}" : "Column {$col_number}";
				$lengthr .= (strlen($lengthr) > 0) ? ", <b><u>{$value}</u></b>($colerr)" : "<b><u>{$value}</u></b>($colerr)";
				//$lengthr .= "<b><u>{$value}</u></b>($colerr) ";	
			}
			throw new Exception("Error: This spreadsheet could not be validated. The following headers are missing from this spreadsheet. {$lengthr}.<br/>");
		}
	}
	/*
	 * bs_DeleteAddressesByBatchID
	 * purge addresses by batchid
	*/
	function bs_DeleteAddressesByBatchID($batchid){
		$returnArray = array();
		try{
			if($batchid == null)
				throw new Exception("Error: Batchid is empty");
			if( ($result = pg_query_params($this->dbconn, "DELETE FROM batchship_items WHERE batch_id = $1", array($batchid))) == FALSE)
			{
				throw new Exception("Error: Prepared statement execute failed");
			}
			$returnArray = array("status" => 200, "message" => pg_affected_rows($result) . " rows removed");
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;
	}
	
	
	/*
	 * GetAddressesByBatchID
	 * 
	 */
	function bs_GetAddressesByBatchID($batchid = null){
		$returnArray = array();
		$query = (isset($batchid) == TRUE) ? "SELECT * FROM batchship_items WHERE batch_id={$batchid}" : "SELECT * FROM batchship_items";
		$returnArray["query"] = $query;
		
		if(($result = pg_query($this->dbconn, $query)) == FALSE)
		{
			$returnArray["status"] = 500;
			$returnArray["message"] = "Error: Could not process query {$query}";
		}
		else{
			$returnArray["status"] = 200;
			$rowsreturned = pg_num_rows($result);
			$returnArray["message"] = "{$rowsreturned} rows retrieved";
			$returnArray["data"] = pg_fetch_all($result);
			$returnArray["info"] = pg_fetch_all(pg_query($this->dbconn, "SELECT * FROM v_batchship_req WHERE batch_id={$batchid}"));
		}
		return $returnArray;
	}
	function bs_GetAddressBatchInfo($batchid){
		$returnArray = array();
		try{// 
			$result = pg_query_params($this->dbconn, "SELECT * FROM v_batchship_req2 WHERE batch_id=$1", array($batchid));	
			$returnArray = array("status" => 200, "data" => pg_fetch_all($result));
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;
	}
	function bs_GetAddressesByWhere($whereclause){
		$returnArray = array();
		try{
			$batchid = (isset($whereclause->batchid)) ? "WHERE batch_id={$whereclause->batchid}": "";
			$limit = (isset($whereclause->limit)) ? "LIMIT {$whereclause->limit}" : "";
			$offset = (isset($whereclause->offset)) ? "OFFSET {$whereclause->offset}" : "";
			$search = (isset($whereclause->search)) ? "AND (address1 ILIKE '%{$whereclause->search}%' OR address2 ILIKE '%{$whereclause->search}%' OR address3 ILIKE '%{$whereclause->search}%' OR store_name ILIKE '%{$whereclause->search}%' OR store_number ILIKE '%{$whereclause->search}%')" : "";
			if(isset($whereclause->status)){
				if(strcasecmp($whereclause->status, "all") == 0)
				{
					$status = "";
				}
				else{
					$status = "AND verify_state='{$whereclause->status}'";
				}
			}
			else{
				$status = "";
			}
			$query = "SELECT * FROM batchship_items {$batchid} {$status} {$search} ORDER BY id {$limit} {$offset}";
			if(($result = pg_query($this->dbconn, $query)) == FALSE){
				throw new Exception("Error Processing Request");
			}
			$returnArray = array("status" => 200, "message" => "aaaaa", "query" => $query, "data" => pg_fetch_all($result));
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;
	}
	function bs_GetAddressesByBatchIDAndStatus($batchid, $status){
		$returnArray = array();
		if($status != "all"){
			$query = "SELECT * FROM batchship_items WHERE batch_id={$batchid} AND verify_state='{$status}'";		
		}
		else{
			$query = "SELECT * FROM batchship_items WHERE batch_id={$batchid}";
		}
		
		$returnArray["query"] = $query;
		
		if(($result = pg_query($this->dbconn, $query)) == FALSE)
		{
			$returnArray["status"] = 500;
			$returnArray["message"] = "Error: Could not process query {$query}";
		}
		else{
			$returnArray["status"] = 200;
			$rowsreturned = pg_num_rows($result);
			$returnArray["message"] = "{$rowsreturned} rows retrieved";
			$returnArray["data"] = pg_fetch_all($result);
			$returnArray["info"] = pg_fetch_all(pg_query($this->dbconn, "SELECT * FROM v_batchship_req2 WHERE batch_id={$batchid}"));
		}
		return $returnArray;
	}
	
	function addToEventLog($subject, $desc, $batchid){
		$userid = (isset($_SESSION["userid"]) != null) ? $_SESSION["userid"] : null;
		
		$query = "INSERT INTO event_log (subject, description, batch_id, user_id, timestamp) VALUES ($1, $2, $3, $4, $5)";
		if( ($result = pg_query_params($this->dbconn, $query, array($subject, $desc, $batchid, $userid, date('Y-m-d H:i:s')))) == FALSE)
		{
			//throw new Exception("Error: Could not process query " . pg_errormessage($this->dbconn));
		}
	}			
	function recursiveDelete($filenamepath){
		//print_r($filenamepath);
		foreach(glob($filenamepath . '/*') as $file) 
	  	{ 
    		if(is_dir($file)) 
    			rmdir($file); 
			else 
				unlink($file); 
  		} 
  		rmdir($filenamepath); 
	}				
// Delete Batch item
	function bs_deleteBatchImportItem($batchid)
	{
		$returnArray = array();
		try{
			//return array("action" => 200);
			if( $batchid == null)
				throw new Exception("Error: Batch id is empty");	
				
			if( ($result = pg_query_params($this->dbconn, 'SELECT * FROM import_queue WHERE id=$1', array($batchid))) == FALSE)
			{
				throw new Exception("Error: Could not process query" . pg_errormessage($this->dbconn));
			}
			
			if (pg_num_rows($result)>0)
			{
				$row = pg_fetch_assoc($result);
				//print_r($row);
				// Delete all addresses associated with this id
				$tr = $this->bs_DeleteAddressesByBatchID($batchid);
				
				// Delete excel file associated with this id
				/*
				if(file_exists($row["filepath"]))
				{
					// Delete File here
					if(unlink($row["filepath"]) == FALSE){
						throw new Exception("Error: File wasnt deleted");
					}
				}
				*/
				// Delete recursively
				//print("archive path: {$row["archivepath"]}");
				
				$this->recursiveDelete($row["archivepath"]);
				//return $row;
				// Delete from queue
				//$query = 'DELETE FROM import_queue WHERE id=$1';
				if( ($result = pg_query_params($this->dbconn, 
						'DELETE FROM import_queue WHERE id=$1', 
						array($batchid))) == FALSE)
				{
					throw new Exception("Error: Could not process query " . pg_errormessage($this->dbconn));
				}
				else 
				{
					$returnArray = array("status" => 200, "message" => "Success: Deleted batch {$batchid}");
					//addToEventLog(BATCH_DELETE, "BATCH {$batchid} was deleted by user {$_SESSION["username"]}", $batchid);
				}
			}
			else{
				$returnArray = array("status" => 500, "message" => "Error: No results found to delete");
			}
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "messages" => $e->getMessage());
		}
		return $returnArray;
	}
	/*
	 * bs_DeleteBatchShipAddressItem 
	 * Deletes address items
	 */
	function bs_DeleteBatchShipAddressItem($addressid){
		
		$returnArray = array();
		try
		{
			if($addressid == NULL){
				throw new Exception("Error: address id is null");
			}
			
			$query = "DELETE FROM batchship_items WHERE id = $1 RETURNING batch_id";
			if( ($result = pg_query_params($this->dbconn, $query, array($addressid))) == FALSE)
			{
				throw new Exception("Error: Could not process query");
			}
			else
			{
				$returnArray = array("status" => 200, "message" => pg_affected_rows($result) . " rows deleted sucessfully");
				if ( ($row = pg_fetch_array($result))) 
				{
					$batch_id = $row["batch_id"];
					$userid = 0;
					$desc = "Removing Item {$addressid} from BATCH {$batchid}";
					if( ($result = pg_query_params($this->dbconn, 
						"INSERT INTO event_log (subject, description, batch_id, user_id, timestamp) VALUES ($1, $2, $3, $4, $5)", 
						array("BATCHITEM_DELETE", $desc, $batch_id, $userid, date('Y-m-d H:i:s') ))) == FALSE)
					{
						throw new Exception("Error: Could not process query");
					}
				}
			}	
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		} 
		return $returnArray;
	}
	function bs_Sanitize($batchid){
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load($row["filepath"]);
		$worksheet = $objPHPExcel->getSheet(0);
		foreach($worksheet_array as $key => &$value){
			if(count($value) > 27){
				$value = array_slice($value, 0, 27);
			}
		}
	}
	function update_singleaddress($bshipinfo){
		$returnArray = array();
		try{
			$validation_array = null;
			if($bshipinfo == null)
				throw new Exception("Error: Could not update address shipping object was null");
					
			if($bshipinfo->address->address1 == null){
				$bshipinfo->address->address1 = "";
			}
			if($bshipinfo->address->address2 == null){
				$bshipinfo->address->address2 = "";
			}
			if($bshipinfo->address->address3 == null){
				$bshipinfo->address->address3 = "";
			}
			
			$address = array($bshipinfo->addressid, 
					$bshipinfo->address->address1,	
					$bshipinfo->address->address2,
					$bshipinfo->address->address3,	
					$bshipinfo->address->city,
					$bshipinfo->address->state,	
					$bshipinfo->address->postalcode,
					$bshipinfo->address->country);
			$query = array();
			if($bshipinfo->markasvalidated == TRUE)
			{
				if( ($result = pg_query_params($this->dbconn, 
						"UPDATE batchship_items SET verified=$2, verify_state=$3 WHERE id=$1", 
						array($bshipinfo->addressid, "t", "passed"))) == FALSE){
					throw new Exception("Error: Could not update address {$bshipinfo->addressid}");												
				}
				
			}
			else{
			$thp = (isset($bshipinfo->itemdata->thirdpartyshipping)) ? $bshipinfo->itemdata->thirdpartyshipping : null;
			 if($thp != null){
			 	
			 
			 if( ($result = pg_query_params($this->dbconn, 
				"UPDATE batchship_items SET thirdparty_company=$2, thirdparty_street=$3, thirdparty_country=$4, thirdparty_city=$5, thirdparty_statep=$6, thirdparty_zip=$7, thirdparty_account=$8 WHERE id=$1",
				array($bshipinfo->addressid, 
						$bshipinfo->itemdata->thirdpartyshipping->company_name,
						$bshipinfo->itemdata->thirdpartyshipping->street,
						$bshipinfo->itemdata->thirdpartyshipping->country,
						$bshipinfo->itemdata->thirdpartyshipping->city,
						$bshipinfo->itemdata->thirdpartyshipping->state,
						$bshipinfo->itemdata->thirdpartyshipping->zipcode,
						$bshipinfo->itemdata->thirdpartyshipping->accountnumber))) == FALSE){
							throw new Exception("Error: Could not update thirdparty info");
						}
				
				}
				
				if( ($result = pg_query_params($this->dbconn, 
					"UPDATE batchship_items SET store_name=$2, store_number=$3, attn=$4, phone_number=$5, kit_number=$6, weight=$7, dimensions=$8, insurance=$9, shipping_method=$10 WHERE id=$1", 
				array(
					$bshipinfo->addressid, 
					$bshipinfo->itemdata->store_name, 
					$bshipinfo->itemdata->store_number,
					$bshipinfo->itemdata->attn,
					$bshipinfo->itemdata->phone_number,
					$bshipinfo->itemdata->kit_number,
					$bshipinfo->itemdata->weight,
					$bshipinfo->itemdata->dimensions, 
					$bshipinfo->itemdata->insurance,
					$bshipinfo->itemdata->shipping_method->value
				))) == FALSE){
					throw new Exception("Error: Could not update address info");												
				}
				
				if( ($result = pg_query_params($this->dbconn, 
					"UPDATE batchship_items SET address1=$2, address2=$3, address3=$4, city=$5, state_province=$6, postal_code=$7, country=$8  WHERE id=$1", 
				array(
					$bshipinfo->addressid, 
					$bshipinfo->address->address1,	
					$bshipinfo->address->address2,
					$bshipinfo->address->address3,	
					$bshipinfo->address->city,
					$bshipinfo->address->state,	
					$bshipinfo->address->postalcode,
					$bshipinfo->address->country
				))) == FALSE){
					throw new Exception("Error: Could not update address");												
				}
			}
			$returnArray = array("status" => 200, "message" => "Address Updated Successfully", "data" => $bshipinfo);
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;
	}
			
	/*
	 * bs_ProceessAddresses
	 */ 
	function bs_findAddressLine($addressLines){
		foreach($addressLines as $key => $value){
			if( (preg_match("/^[0-9-]+ [A-Za-z 0-9]+/", $value)) == 1){
				return $key;
			}
		}
		return FALSE;
	}
	function bs_NormalizeRowData(&$rowData){
		//var_dump($this->statelist);
		
		// Normalize, Country
		$rowData[8] = ($rowData[8] == "USA") ? "US" : $rowData[8];
		// Normalize, State
		if($rowData[8] == "US"){
			foreach ($this->statelist as $key => $value) {
				if( (strcmp($value["name"], strtolower($rowData[6]))) == 0){
					$rowData[6] = strtoupper($value["abbreviation"]);
					break;
				}
			}	
		}
	}
	function trim_value(&$value) 
	{ 
	    $value = trim($value); 
	}
	function empty_value(&$value, $index, &$cnt) 
	{ 
	    //$value = trim($value); 
	    $cnt += (strlen($value) > 0) ? 1 : 0;
	    return ($value != "") ? TRUE : FALSE;
	}
	
	
	function bs_ProceessAddresses($batchid){ 
		$returnArray = array();
		try{
			if($batchid == null)
				throw new Exception("Error: Batch id is empty (process)");
			if( ($result = pg_query_params($this->dbconn, "SELECT * FROM import_queue WHERE id=$1", array($batchid))) == FALSE){
				throw new Exception("Error: Could not process query");
			}	
			else{
				// Shows the number of rows found for the batch if	
				if ($row = pg_fetch_assoc($result)) {
					$objPHPExcel = new PHPExcel();
					$objPHPExcel = PHPExcel_IOFactory::load($row["filepath"]);
					$worksheet = $objPHPExcel->getSheet(0);
					$worksheet_array = $worksheet->toArray();
					//var_dump($worksheet_array);
					$numberOfKeys = 0;
					$values = array();
					
					// Find and save array headers
					foreach($worksheet_array[0] as $key => $value){
						if(array_key_exists ($value, $this->spreadsheetHeaders) == true){
							$columns[] = $this->spreadsheetHeaders[$value];
							$values[] = "$".($numberOfKeys+1);
						}
						$numberOfKeys++;
					}
					
					$columns[]="batch_id";
					$values[] = "$".($numberOfKeys+1);
					
					foreach($worksheet_array as $key => &$value){
						// trims leading and trailing spaces
						array_walk($value, "trim");
						
						// Removes empty rows
						$cnt = 0;
						array_walk(&$value, array($this,"empty_value"), &$cnt);						
						if($cnt == 0){
							unset($worksheet_array[$key]);
							continue;
						}
						
						//print($cnt . "<br/>");
						if(count($value) > count($columns)){
							// Slice off unnecessary columns
							$value = array_slice($value, 0, count($this->spreadsheetHeaders));
							foreach($value as $k => &$y){
								if (is_null($y)) {
							         $y = '';
							    }				
							}
						}
						else{
							// Shifts address lines so the address only appears in line2
							$v = array();
							$v[] = $value[2];
							$v[] = $value[3];
							$v[] = $value[4];
							//var_dump($v);
							if( ($line = $this->bs_findAddressLine($v)) !== FALSE)
							{
								if($line == 0){
									$value[2] = "";
									$value[3] = $v[0];
									$value[4] = $v[1];
								}
								//echo $line;
							}
						}
					}
				
					$columns =implode(",", $columns);
					$values =implode(",", $values);
					$prepared_query = "INSERT INTO batchship_items ({$columns}) VALUES ({$values})";
					//echo $prepared_query;
					//var_dump($prepared_query);
			
					if( ($result = pg_prepare($this->dbconn, "Insert_Into_BatchshipItem2", $prepared_query)) == FALSE)
					{
						throw new Exception("Error: Prepared statement creation failed (Insert_Into_BatchshipItem2) " . pg_errormessage($this->dbconn));
					}
					//var_dump($value);
					// Start Processing worksheet items
					$tempAffectedRow = 0;
					foreach($worksheet_array as $key => &$value){
						//Exclude the header line 	
						if($key==0)
							continue;
						//Find FCP number
						array_walk($value, array($this, 'trim_value'));
						$this->bs_NormalizeRowData($value);
						//if($this->checkifarrayisempty($value)){
							///print_r($value);
						//}
						//$re = count(array_filter($value, array($this, 'empty_value')));
						
						//var_dump($re);
						
						$value[]=$batchid;
						if( ($result = pg_execute($this->dbconn, "Insert_Into_BatchshipItem2", $value)) == TRUE ){
							$tempAffectedRow += pg_affected_rows($result);
						}
						else{
							throw new Exception("Error: Could not Execute Request");
						}
					}
					$returnArray = array("status" => 200, "message" => "{$tempAffectedRow} rows affected");	
					//addToEventLog(BATCH_PROCESSED, "BATCH {$batchid} was processed successfully by user {$_SESSION["username"]}", $batchid);	
				}
			}
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;
	}
	function bs_GetImportBatchItemsByUserID($userid, $privaleges = "user"){
		try{
			
			$returnArray = array();
			if( $userid == null)
				throw new Exception("Error: Userid is empty");
			if( ($result = pg_query($this->dbconn, "SELECT * FROM users WHERE id = {$userid}")) == FALSE)
			{
				throw new Exception("Error: could not find user");
			}
			$row = pg_fetch_array($result);
			$query = "SELECT * FROM v_import_queue WHERE user_id = {$userid}";
			if( ($row["privaleges"] == "admin") && ($privaleges == "admin") ){
				$query = "SELECT * FROM v_import_queue";
			}
				
			if(($result = pg_query($this->dbconn, $query)) == FALSE){
				throw new Exception("Error: Could not process query");		
			}
			else{
				$tempdata = array();
				while ($row = pg_fetch_assoc($result)) {
					$row["validated_percent"] = round(($row["validation_passed"]/$row["total_addresses"])*100);
					$tempdata[] = $row;
				}
				$returnArray = array("status"=> 200, "message" => pg_num_rows($result) . " rows retrieved", "data" => $tempdata);
			}	
			
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;
	}
	function checkshipping_type($string){
		return (isset($this->shippingarray[$string])) ? TRUE : FALSE;
	}
	/*
	 * bs_GetImportBatchItemsByID
	 * This function allows the user to access the importbatchqueue
	 */
	function bs_GetImportBatchItemsByID($batchid){
		try{
			if( $batchid == null)
				throw new Exception("Error: Batch id is empty");
			$returnArray = array();
			$query = ($batchid != "all") ? "SELECT * FROM v_import_queue WHERE id = {$batchid}" : "SELECT * FROM v_import_queue";
			if(($result = pg_query($this->dbconn, $query)) == FALSE){
				throw new Exception("Error: Could not process query");		
			}
			else{
				$tempdata = array();
				while ($row = pg_fetch_assoc($result)) {
					$row["validated_percent"] = round(($row["validation_passed"]/$row["total_addresses"])*100);
					$tempdata[] = $row;
				}
				$returnArray = array("status"=> 200, "message" => pg_num_rows($result) . " rows retrieved", "data" => $tempdata);
			}
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;
	}
	function bs_takePropAddressesByBatchID($batchid){
		
		$returnArray = array();
		try{
			if( $batchid == null)
				throw new Exception("Error: Batch id is empty");
			
			$result = null;
			
			if(($result = pg_query_params($this->dbconn, "SELECT * FROM batchship_items WHERE batch_id = $1 AND verified = 'f'", array($batchid) )) == FALSE)
				throw new Exception("Error: Could not process query");	
			$batchArray = pg_fetch_all($result);
			pg_prepare($this->dbconn, "UPDATE_BY_VALIDATION_DATA", "UPDATE batchship_items SET address2=$2, city=$3, state_province=$4, postal_code=$5, country=$6 WHERE id=$1");
			foreach ($batchArray as $key => $row) {
				if($row["verify_data"] != null){
					$verifedData = json_decode($row["verify_data"]);
					if($verifedData->Severity == "SUCCESS"){
						if( ($verifedData->ProposedAddressDetails->Score > 0) && (count($verifedData->ProposedAddressDetails->Address) == 1) ){
							if( ($result = pg_execute($this->dbconn, "UPDATE_BY_VALIDATION_DATA", 
								array($row["id"], $verifedData->ProposedAddressDetails->Address[0]->StreetLines, 
									$verifedData->ProposedAddressDetails->Address[0]->City, 
									$verifedData->ProposedAddressDetails->Address[0]->StateOrProvinceCode, 
									$verifedData->ProposedAddressDetails->Address[0]->PostalCode, 
									$verifedData->ProposedAddressDetails->Address[0]->CountryCode) )) == TRUE)
							{
								$affected_rows += pg_affected_rows($result);	
							}
						}
					}
				}	
			}
			// validate rows somewhere
			$this->validateAddress($_REQUEST);
			$returnArray = array("status" => 200, "message" => $affected_rows . " rows updated");
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "messages" => $e->getMessage());
		}
		return $returnArray;
	}
	
	
	
	
	function bv_Validate_Batch($batch){
		if($batch->Notifications->Severity != FAILURE){
			return  true;	
		}
		return false;
	}
	function invalidjsondata($queryrow, $reason)
	{
		return array(
			"id" => $queryrow["id"], 
			"ServiceType" => "none", 
			"Severity" => "FAILURE", 
			"Message" => $reason,
			"ProposedAddressDetails" =>
			  	array("Score" => 0,
			  		  "Changes" => $reason,
			  		  "Original_Address" => 
					  		array(
					  			"id" => $queryrow["id"],
					  			"CompanyName" => $queryrow["store_name"],
					            "StreetLines" => $queryrow["address2"],
					            "City" => $queryrow["city"],
					            "StateOrProvinceCode" => $queryrow["state_province"],
					            "PostalCode" => $queryrow["postal_code"],
					            "CountryCode" => $queryrow["country"]
							)
				)
		);
	}
	function validateAddress($request){
		try{
			$returnArray = array();
			$rows_wo_ship = 0;
			if( (isset($request["batchid"])) == TRUE ){
				//throw new Exception("No Batch Id was entered");	
			//}	'AND shipping_method IS NULL
			if( ($result = pg_query_params($this->dbconn, "SELECT * FROM batchship_items WHERE batch_id = $1", array($request["batchid"]))) !=FALSE){
				$row = pg_fetch_all($result);
				
				 
				
				 
				// failaddresses addresses with out shipping type
				foreach ($row as $key => $value) {
					if( array_key_exists($value["shipping_method"], $this->shippingarray) == FALSE){
						$trsx = (strlen($value["shipping_method"]) > 0) ? "Invalid Shipping Type selected, Please Select a valid one" : "Insuficient Data, No ServiceType Selected";
						if( ($resulta = pg_query_params($this->dbconn, "UPDATE batchship_items SET verify_data=$2, verified=$3, verify_state=$4 WHERE id = $1", 
										array($value["id"], json_encode($this->invalidjsondata($value, $trsx)), "f", "error"))) == FALSE)
						{
							throw new Exception("Could not add faild information " . pg_errormessage($dbconn));			
						}
					}
					//$jsonData = array("id" => $value["id"], "Severity" => "FAILURE", "Message" => "Insuficient Data, No ServiceType Selected");	
				}
			}
			}
			$fedex_validation = $this->bv_FedexValidateBatch($request);
			$ups_validation = $this->bv_UPSValidationBatch($request);
			$returnArray = array("status" => 200, "message" => "Success: ", "fedex_val" => $fedex_validation,"ups_val" =>$ups_validation);
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => "Error: {$e->getMessage()}");
		}
		return $returnArray;
	}
/*
	function thirdpartyvalidate($resultjson){
		$returnArray = array();
		try{
			$returnArray = $resultjson;
			/*$id = (isset($resultjson->Id)) ? $resultjson->Id : NULL;
			$street = (isset($resultjson->Street)) ? $resultjson->Street : NULL;
			$city = (isset($resultjson->City)) ? $resultjson->City : NULL;
			$state = (isset($resultjson->State)) ? $resultjson->State : NULL;
			$zipcode = (isset($resultjson->ZipCode)) ? $resultjson->ZipCode : NULL;
			$zipcodeext = (isset($resultjson->ZipcodeExtended)) ? $resultjson->ZipcodeExtended : NULL;
			$country = (isset($resultjson->Country)) ? $resultjson->Country : NULL;
			if($id == NULL)			throw new Exception("No ID Supplied");
			if($street == NULL)		throw new Exception("No Street Supplied");
			if($city == NULL)		throw new Exception("No City Supplied");
			if($state == NULL)		throw new Exception("No State Supplied");
			if($zipcode == NULL)	throw new Exception("No ZipCode Supplied");
			if($zipcodeext == NULL)	throw new Exception("No ZipCodeExt Supplied");
			if($country == NULL)	throw new Exception("No Country Supplied");
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => "Error: {$e->getMessage()}");
		}
		return $returnArray;
	}*/
	function thirdpartyvalidate(){
		$returnArray = array();
		$access = "1CB7DF83DFECF1D5";
		$userid = "brettmosley";
		$passwd = "DricasM4x";
		$wsdl = "PHPUPSWSDL/UPSWSDL/XAV.wsdl";
		$operation = "ProcessXAV";
		//$endpointurl = 'https://wwwcie.ups.com/webservices/XAV';
		$endpointurl = 'https://onlinetools.ups.com/webservices/XAV';
		$outputFileName = "XOLTResult.xml";
		$mode = array
	    (
	         'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
	         'trace' => 1
	    );
			
	    // initialize soap client
		$client = new SoapClient($wsdl , $mode);
	  	//set endpoint url
	  	$client->__setLocation($endpointurl);
	
	
	    //create soap header
	    $usernameToken['Username'] = $userid;
	    $usernameToken['Password'] = $passwd;
	    $serviceAccessLicense['AccessLicenseNumber'] = $access;
	    $upss['UsernameToken'] = $usernameToken;
	    $upss['ServiceAccessToken'] = $serviceAccessLicense;
	    $header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
	    $client->__setSoapHeaders($header);
		$result = pg_query($this->dbconn, "SELECT * FROM thirdpartyaddress");
		$allresult = pg_fetch_all($result);
		$respMe =array();
		foreach ($allresult as $key => $value) {
		//	var_dump($value);
			$addressArray = array("Request" => array("RequestOption" => "3"),
									//"RegionalRequestIndicator" => "",
									"AddressKeyFormat" => array(
												"ConsigneeName" => $value["company_name"],
												"AddressLine" => array (
													$value["street"]
												),
											    "PoliticalDivision2" => $value["city"],
											    "PoliticalDivision1" => $value["state"],
											 	"PostcodePrimaryLow" => $value["zipcode"],
											 	//"PostcodeExtendedLow" => "1521",											 	
											 	"CountryCode" => "US"											    
										)
									
								);
					//print_r($addressArray);
				    //get response
				$resp = $client->__soapCall($operation, array($addressArray));
				$resp->OriginalAddress = $addressArray;
				$resp->service_type = "ups";
				$proposed = array();
				switch($resp->AddressClassification->Description){
					case "Commercial":	$proposed["ProposedAddressDetails"]["ResidentialStatus"] = "Business";			break;
					default:			$proposed["ProposedAddressDetails"]["ResidentialStatus"] = "UNDETERMINED";
				}
				$proposed["ProposedAddressDetails"]["DeliveryPointValidation"] = ($resp->AddressClassification->DeliveryPointValidation == 0) ? "UNCONFIRMED" : "CONFIRMED";
				if( (isset($resp->ValidAddressIndicator)) == TRUE){
					$proposed["ProposedAddressDetails"]["Score"] = 100;
				}
				if( (isset($resp->AmbiguousAddressIndicator)) == TRUE){
					$proposed["ProposedAddressDetails"]["Score"] = 50;
				}
				if( (isset($resp->Candidate)) == TRUE){
					switch(gettype($resp->Candidate)){
						case "object":
							$proposed["ProposedAddressDetails"]["Address"][] = array(
								"StreetLines" => $resp->Candidate->AddressKeyFormat->AddressLine,
								"City" => $resp->Candidate->AddressKeyFormat->PoliticalDivision2,
								"StateOrProvinceCode" => $resp->Candidate->AddressKeyFormat->PoliticalDivision1,
								"PostalCode" => implode("-", array($resp->Candidate->AddressKeyFormat->PostcodePrimaryLow, $resp->Candidate->AddressKeyFormat->PostcodeExtendedLow)),
								"CountryCode" => $resp->Candidate->AddressKeyFormat->CountryCode
							);
						break;
						case "array":
						
							foreach ($resp->Candidate as $keya => $valuea) {
								$proposed["ProposedAddressDetails"]["Address"][] = array("StreetLines" => $valuea->AddressKeyFormat->AddressLine,
										"City" => $valuea->AddressKeyFormat->PoliticalDivision2,
										"StateOrProvinceCode" => $valuea->AddressKeyFormat->PoliticalDivision1,
										"PostalCode" => implode("-", array($valuea->AddressKeyFormat->PostcodePrimaryLow, $valuea->AddressKeyFormat->PostcodeExtendedLow)),
										"CountryCode" => $valuea->AddressKeyFormat->CountryCode
									);
							}
						break;
					}
				}
				$proposed["ProposedAddressDetails"]["Original_Address"] = array(
									"StreetLines" => implode(" ", $resp->OriginalAddress["AddressKeyFormat"]["AddressLine"]),
					                "City" => $resp->OriginalAddress["AddressKeyFormat"]["PoliticalDivision2"],
					                "StateOrProvinceCode" => $resp->OriginalAddress["AddressKeyFormat"]["PoliticalDivision1"],
					                "PostalCode" => $resp->OriginalAddress["AddressKeyFormat"]["PostcodePrimaryLow"],
					                "CountryCode" => $resp->OriginalAddress["AddressKeyFormat"]["CountryCode"]);
				$proposed["ServiceType"] = "ups";
				$proposed["id"] = $value["id"];
				$proposed["Severity"] = "SUCCESS";
				$respMe[] = $proposed;
				//print_r($resp);
				if( (isset($resp->ValidAddressIndicator)) == TRUE){
					$tr = $resp->Candidate->AddressKeyFormat;
					pg_query_params($this->dbconn, 
					"UPDATE thirdpartyaddress SET verified = $2, street = $3, city = $4, state = $5, zipcode = $6, verify_state=$7, verify_data=$8 WHERE id = $1", 
					array($value["id"], "t", $tr->AddressLine, $tr->PoliticalDivision2, $tr->PoliticalDivision1, "{$tr->PostcodePrimaryLow} - {$tr->PostcodeExtendedLow}", "passed", json_encode($proposed) ) );
				}
				if( (isset($resp->AmbiguousAddressIndicator)) == TRUE){
					$tr = $resp->Candidate->AddressKeyFormat;
					pg_query_params($this->dbconn, 
					"UPDATE thirdpartyaddress SET verified = $2, verify_data=$3 WHERE id = $1", 
					array($value["id"], "f", json_encode($proposed)) );
				}
		}
		$returnArray = array("status" => 200, "message" => $respMe);
		return $returnArray;
	}
	function bv_UPSValidationBatch($request){
		$returnArray = array();
		//$rowResp = "5645445";
		try
		{
			$addressid = (isset($request["addressid"])) ? $request["addressid"] : null;
			$batchid = (isset($request["batchid"])) ? $request["batchid"] : null;
			if($batchid == null && $addressid == null){
				throw new Exception("Error: No AddressID or BatchID was supplied");
			}
			if($batchid != null && $addressid != null){
				throw new Exception("Error: To many AddressID or BatchID was supplied");
			}
			$result = null;
			if($addressid != null){
				if( ($result = pg_query_params($this->dbconn, 
						"SELECT id, store_name, address1, address2, address3, city, state_province, postal_code, country, shipping_method FROM batchship_items WHERE id = $1 AND shipping_method ILIKE 'UPS%'", 
						array($addressid))) == FALSE)
				{
					throw new Exception("Error: Could not process query");
				}		
			}
			if($batchid != null){
				if( ($result = pg_query_params($this->dbconn, 
						"SELECT id, store_name, address1, address2, address3, city, state_province, postal_code, country, shipping_method FROM batchship_items WHERE batch_id = $1 AND shipping_method ILIKE 'UPS%'", 
						array($batchid))) == FALSE)
				{
					throw new Exception("Error: Could not process query");
				}	
			}
			$respMe = array();
			$operation = "ProcessXAV";
			$outputFileName = "XOLTResult.xml";
			$client = new SoapClient("PHPUPSWSDL/UPSWSDL/XAV.wsdl", array('soap_version' => 'SOAP_1_1', 'trace' => 1));
			$client->__setLocation($this->upssEndpoint);
			$header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$this->upss);
		    $client->__setSoapHeaders($header);
			$this->logging->startTimeStamp();
			
			while($row = pg_fetch_assoc($result))
			{
				$addressArray = array("Request" => array(
										"RequestOption" => "3"
									),
									"AddressKeyFormat" => array(
												"ConsigneeName" => $row["store_name"],
												"AddressLine" => array (
													$row["address1"], 
													$row["address2"],
													$row["address3"]
												),
											    "PoliticalDivision2" => $row["city"],
											    "PoliticalDivision1" => $row["state_province"],
											 	"PostcodePrimaryLow" => $row["postal_code"],
											 	//"PostcodeExtendedLow" => "1521",											 	
											 	"CountryCode" => "US"
											    
										)
									
								);
				    $resp = $client->__soapCall($operation, array($addressArray));
					$resp->OriginalAddress = $addressArray;
					$resp->service_type = "ups";					
					$proposed = array();
					switch($resp->AddressClassification->Description){
						case "Commercial":	$proposed["ProposedAddressDetails"]["ResidentialStatus"] = "Business";			break;
						default:			$proposed["ProposedAddressDetails"]["ResidentialStatus"] = "UNDETERMINED";
					}
					$proposed["ProposedAddressDetails"]["DeliveryPointValidation"] = (isset($resp->AddressClassification->DeliveryPointValidation)) ? 
						(($resp->AddressClassification->DeliveryPointValidation == 0) ? "UNCONFIRMED" : "CONFIRMED") :
						"UNCONFIRMED";
					if( (isset($resp->ValidAddressIndicator)) == TRUE){
						$proposed["ProposedAddressDetails"]["Score"] = 100;
						$proposed["ProposedAddressDetails"]["Changes"] = "CONFIRMED";
					}
					if( (isset($resp->AmbiguousAddressIndicator)) == TRUE){
						$proposed["ProposedAddressDetails"]["Score"] = 50;
						$proposed["ProposedAddressDetails"]["Changes"] = "Ambiguous";
					}
					if( ((isset($resp->AmbiguousAddressIndicator)) == FALSE) && ((isset($resp->ValidAddressIndicator)) == FALSE)){
						$proposed["ProposedAddressDetails"]["Score"] = 0;
						$proposed["ProposedAddressDetails"]["Changes"] = "No Addresses found";
					}
					
					switch(gettype($resp->Candidate)){
						case "object":
							$proposed["ProposedAddressDetails"]["Address"][] = array(
								"StreetLines" => $resp->Candidate->AddressKeyFormat->AddressLine,
								"City" => $resp->Candidate->AddressKeyFormat->PoliticalDivision2,
								"StateOrProvinceCode" => $resp->Candidate->AddressKeyFormat->PoliticalDivision1,
								"PostalCode" => implode("-", array($resp->Candidate->AddressKeyFormat->PostcodePrimaryLow, $resp->Candidate->AddressKeyFormat->PostcodeExtendedLow)),
								"CountryCode" => $resp->Candidate->AddressKeyFormat->CountryCode
							);
						break;
						case "array":
							foreach ($resp->Candidate as $key => $value) {
								$proposed["ProposedAddressDetails"]["Address"][] = array(
										"StreetLines" => $value->AddressKeyFormat->AddressLine,
										"City" => $value->AddressKeyFormat->PoliticalDivision2,
										"StateOrProvinceCode" => $value->AddressKeyFormat->PoliticalDivision1,
										"PostalCode" => implode("-", array($value->AddressKeyFormat->PostcodePrimaryLow, $value->AddressKeyFormat->PostcodeExtendedLow)),
										"CountryCode" => $value->AddressKeyFormat->CountryCode
									);
							}
						break;
					}

					$proposed["ProposedAddressDetails"]["Original_Address"] = array(
										"StreetLines" => $resp->OriginalAddress["AddressKeyFormat"]["AddressLine"],
						                "City" => $resp->OriginalAddress["AddressKeyFormat"]["PoliticalDivision2"],
						                "StateOrProvinceCode" => $resp->OriginalAddress["AddressKeyFormat"]["PoliticalDivision1"],
						                "PostalCode" => $resp->OriginalAddress["AddressKeyFormat"]["PostcodePrimaryLow"],
						                "CountryCode" => $resp->OriginalAddress["AddressKeyFormat"]["CountryCode"]);
					$proposed["ServiceType"] = "ups";
					$proposed["shipping_method"] = $row["shipping_method"];
					$proposed["id"] = $row["id"];
					$proposed["Severity"] = "SUCCESS";
					$respMe[] = $proposed;	    
				}

				$this->logging->endTimeStamp();
				$delta = $this->logging->timeDifference();
				$this->logging->loglineFlatfile("subject=ups-validation-loopruntime;description={$delta};batch_id=1;user_id=1;");
				
				$this->logging->startTimeStamp();
				foreach($respMe as &$value)
				{			
					if($value["Severity"] != "FAILURE")
					{
						//*****************************************
						if(preg_match("/(P.O. Box|PO Box)/i", $value["ProposedAddressDetails"]["Address"][0]["StreetLines"])){
							$value["Severity"] = "FAILURE";
							$value["ProposedAddressDetails"]["Changes"] = "Error: Cannot validate P.O. Boxes";
							$value["Message"] = "Error: Cannot validate P.O. Boxes";
						}
						if(!($this->checkshipping_type($value["shipping_method"]))){
							$value["Severity"] = "FAILURE";
							$value["ProposedAddressDetails"]["Changes"] = "Error: {$value["shipping_method"]} is not a valid shipping method";
							$value["Message"] = "Error: {$value["shipping_method"]} is not a valid shipping method";
							$value["ProposedAddressDetails"]["Score"] = 0;
						}
					
						//*****************************************
						if($value["ProposedAddressDetails"]["Score"] == 100)
						{
							
							
							$address1 = $value["ProposedAddressDetails"]["Original_Address"]["StreetLines"][0];
							$address2 = $value["ProposedAddressDetails"]["Original_Address"]["StreetLines"][1];
							$address3 = $value["ProposedAddressDetails"]["Original_Address"]["StreetLines"][2];	
						
							switch( (gettype($value["ProposedAddressDetails"]["Address"][0]["StreetLines"]))){
								case "array":
									$address2 = $value["ProposedAddressDetails"]["Address"][0]["StreetLines"][0];
									break;
								default:
								$address2 = $value["ProposedAddressDetails"]["Address"][0]["StreetLines"];
							}
							/*if( (gettype($value["ProposedAddressDetails"]["Address"][0]["StreetLines"])) == "array"){
								switch(count($value["ProposedAddressDetails"]["Address"][0]["StreetLines"])){
									case 2;
									$address2 = $value["ProposedAddressDetails"]["Address"][0]["StreetLines"][1];
									$address3 = $value["ProposedAddressDetails"]["Address"][0]["StreetLines"][2];
									break;
									case 3;
									$address2 = $value["ProposedAddressDetails"]["Address"][0]["StreetLines"][1];
									$address3 = $value["ProposedAddressDetails"]["Address"][0]["StreetLines"][2];
									$address2 = $value["ProposedAddressDetails"]["Address"][0]["StreetLines"][3];
									break;									
								}
							}
							else{
								$address2 = $value["ProposedAddressDetails"]["Address"][0]["StreetLines"];
							}*/			
						
							if( ($result = pg_query_params($this->dbconn, 
												"UPDATE batchship_items SET address1=$2, address2=$3, address3=$4, city=$5, state_province=$6, postal_code=$7, country=$8, verify_data=$9, verified=$10, verify_state=$11 WHERE id = $1", 
												array($value["id"], $address1, $address2, $address3,
													$value["ProposedAddressDetails"]["Address"][0]["City"],
												 	$value["ProposedAddressDetails"]["Address"][0]["StateOrProvinceCode"],
												 	$value["ProposedAddressDetails"]["Address"][0]["PostalCode"],
												 	$value["ProposedAddressDetails"]["Address"][0]["CountryCode"],
												 	json_encode($value), 't', 'passed'))) == FALSE )
							{	
								throw new Exception("Error: Prepared statement execute failed");						
							}		
						}
						else{
							if($value["Severity"] != "FAILURE"){		
								if( (pg_query_params($this->dbconn, "UPDATE batchship_items SET verify_data=$2, verified=$3, verify_state=$4 WHERE id = $1", 
								 		array($value["id"], json_encode($value), "f", "not_passed"))) == FALSE)
								{	
									throw new Exception("Error: Prepared statsssement execute failed: " . pg_errormessage($this->dbconn));
								}
							}	
							else{
								if( (pg_query_params($this->dbconn, "UPDATE batchship_items SET verify_data=$2, verified=$3, verify_state=$4 WHERE id = $1", 
								 		array($value["id"], json_encode($value), "f", "error"))) == FALSE)
								{	
									throw new Exception("Error: Prepared statsssement execute failed: " . pg_errormessage($this->dbconn));
								}
							}
						}
					}
				}
				$this->logging->endTimeStamp();
				$delta = $this->logging->timeDifference();
				$this->logging->loglineFlatfile("subject=ups-sqlwriteback-loopruntime;description={$delta};batch_id=1;user_id=1;");
				$returnArray = array("status" => 200, "message" => "", "data" => $respMe);
		}
		catch(Exception $e){
			$this->logging->loglineFlatfile("subject=bv_UPSValidationBatch;description=Error: {$e->getMessage()};batch_id=1;user_id=1;");
			$returnArray = array("status" => 500, "message" => $e->getMessage());
			//print_r( $client->__getLastRequest());
			//print("<br/>" );
			//print_r( $client->__getLastResponse());
		}
		return $returnArray;
	}
	function bv_FedexValidateBatch($request){
		$returnArray = array();
		try{
			//*********************************************		
			$addressid = (isset($request["addressid"])) ? $request["addressid"] : null;
			$batchid = (isset($request["batchid"])) ? $request["batchid"] : null;
			
			if($batchid == null && $addressid == null){
				throw new Exception("Error: No AddressID or BatchID was supplied");
			}
			if($batchid != null && $addressid != null){
				throw new Exception("Error: To many AddressID or BatchID was supplied");
			}
			if($addressid != null){
				if( ($result = pg_query_params($this->dbconn, 
						"SELECT id, store_name AS \"CompanyName\", address2 AS \"StreetLines\", city AS \"City\", state_province AS \"StateOrProvinceCode\", postal_code AS \"PostalCode\", country AS \"CountryCode\", shipping_method FROM batchship_items WHERE id = $1 AND shipping_method ILIKE 'FDX%'", 
						array($addressid))) == FALSE){
					throw new Exception("Error: Could not process query");
				}
			}
			
			if($batchid != null){
				$query = "";
				if( ($result = pg_query_params($this->dbconn, 
						"SELECT id, store_name AS \"CompanyName\", address2 AS \"StreetLines\", city AS \"City\", state_province AS \"StateOrProvinceCode\", postal_code AS \"PostalCode\", country AS \"CountryCode\", shipping_method FROM batchship_items WHERE batch_id = $1 AND shipping_method ILIKE 'FDX%'", 
						array($batchid))) == FALSE)
				{
					throw new Exception("Error: Could not process query");
				}	
			}
			
			$adr= array();
			$rows_failed = 0;
			$rows_passed = 0;
			$rows_need_mod =0;
			
			if( ($rows = pg_fetch_all($result)) != FALSE)
			{
				//***********************************************************
				$path_to_wsdl = "../wsdl/AddressValidationService_v2.wsdl";  
				ini_set("soap.wsdl_cache_enabled", "0");
				$client = new SoapClient($path_to_wsdl, array('trace' => 1)); 
				$request['WebAuthenticationDetail'] = $this->fedexcredentials;
		     	$request['ClientDetail'] = $this->fedexcredentialdetail;
				// Replace 'XXX' with client's account and meter number
				$request['TransactionDetail'] = array('CustomerTransactionId' => '123');
				$request['Version'] = array('ServiceId' => 'aval', 'Major' => '2', 'Intermediate' => '0', 'Minor' => '0');
				$request['RequestTimestamp'] = date('c');
				$request['Options'] = $this->fedexvalidationoptions;
				//**************************************************************
				$addressgroup = array();	
				$addressdict = array();
			
				//$request["AddressesToValidate"];
				foreach($rows as $key => $value){
					$addressgroup[$key]["Address"] = $value;
					$addressdict[$key] = array("id" => $value["id"], "Original_Address" => $value, "shipping_method" => $value["shipping_method"]);
				}
				$arrayChunk = array_chunk(&$addressgroup, MAX_ADDRESS_CHUNK);
				$arrayDictChunk = array_chunk(&$addressdict, MAX_ADDRESS_CHUNK);
				$arrayChunkCount = 0;
				
				foreach($arrayChunk as $index => $addressSubset){
					try{
						$request['AddressesToValidate'] = $addressSubset;
						$response = $client->addressValidation($request);
					
						if( ($response->HighestSeverity == "SUCCESS") && (count($addressSubset) > 1) ){
							foreach($response->AddressResults as $index => $addressResultSubset){
								$addressResultSubset->ProposedAddressDetails->Original_Address = $arrayDictChunk[$arrayChunkCount][$index]["Original_Address"];
								$addressResultSubset->ProposedAddressDetails->Address = array($addressResultSubset->ProposedAddressDetails->Address);
								$addressResultSubset->ServiceType = "fedex";
								
								$addressResultSubset->shipping_method = $arrayDictChunk[$arrayChunkCount][$index]["shipping_method"];
								$addressResultSubset->id = $arrayDictChunk[$arrayChunkCount][$index]["id"];
								$addressResultSubset->Severity = $response->HighestSeverity;
								$adr[] = $addressResultSubset;
							}
						}
						else{
							throw new Exception("Failure");
						}
					}
					catch(Exception $e){
						$arrayChunkError = array_chunk(&$addressSubset, DEBUG_ADDRESS_CHUNK);
						$arrayDictChunkError = array_chunk(&$arrayDictChunk, DEBUG_ADDRESS_CHUNK);
						
						foreach($arrayChunkError as $index => $addressSubsetB){
							$request['AddressesToValidate'] = $addressSubsetB;
							$response = $client->addressValidation($request);
							$addressResultSubset = new stdClass();
							if( ($response->HighestSeverity == "SUCCESS") ){
								
								$addressResultSubset->ServiceType = "fedex";
								$addressResultSubset->shipping_method = $arrayDictChunk[$arrayChunkCount][$index]["shipping_method"];
								$addressResultSubset->id = $arrayDictChunkError[$arrayChunkCount][0][$index]["id"];
								$addressResultSubset->Severity = $response->HighestSeverity;
								$addressResultSubset->ProposedAddressDetails = $response->AddressResults->ProposedAddressDetails;
								$addressResultSubset->ProposedAddressDetails->Original_Address = $arrayDictChunkError[$arrayChunkCount][0][$index]["Original_Address"];
								$addressResultSubset->ProposedAddressDetails->Address = array($response->AddressResults->ProposedAddressDetails->Address);
								$adr[] = $addressResultSubset;
							}
							else{
								// Dont do anything if we find the failure point
								//throw new Exception("Failure");
								$addressResultSubset->ServiceType = "fedex";
								
								$addressResultSubset->shipping_method = $arrayDictChunk[$arrayChunkCount][$index]["shipping_method"];
								$addressResultSubset->id = $arrayDictChunkError[$arrayChunkCount][0][$index]["id"];
								$addressResultSubset->Severity = $response->HighestSeverity;
								$addressResultSubset->Message = $response->Notifications->Message;
								$addressResultSubset->ProposedAddressDetails->Original_Address = $arrayDictChunkError[$arrayChunkCount][0][$index]["Original_Address"];
								$adr[] = $addressResultSubset;
							}
						}
						//var_dump($adr);
						//$response = $client->addressValidation($request);
						//return array("status" => 500, "message" => $e->getMessage(), "data" => $response);
					}
					$arrayChunkCount++;
				}
				//**************************************************************
				// Add data to database
				//var_dump($adr);
				
				$result = pg_prepare($this->dbconn, "UPDATE_SUCCESS", 
					"UPDATE batchship_items SET address2=$2, city=$3, state_province=$4, postal_code=$5, country=$6, verify_data=$7, verified=$8, verify_state=$9 WHERE id = $1");
				$result = pg_prepare($this->dbconn, "UPDATE_LESS_THAN_100", 
					"UPDATE batchship_items SET verify_data=$2, verified=$3, verify_state=$4 WHERE id = $1");
				//print_r($adr);
				foreach($adr as $index => $value)
				{
					if(preg_match("/(P.O. Box|PO Box)/i",$value->ProposedAddressDetails->Address[0]->StreetLines)){
						$value->Severity = "FAILURE";
						$value->ProposedAddressDetails->Changes = "Error: Cannot validate P.O. Boxes";
						$value->Message = "Error: Cannot validate P.O. Boxes";
					}
					//print_r($value);
					if(!($this->checkshipping_type($value->shipping_method))){
						
							$value->Severity = "FAILURE";
							$value->ProposedAddressDetails->Changes = "Error: {$value->shipping_method} is not a valid shipping method";
							$value->Message = "Error: {$value->shipping_method} is not a valid shipping method";
							$value->ProposedAddressDetails->Score = 0;
						}
					// IF Score was 100% then we mark this item as validated
					if( ($value->Severity == "SUCCESS") && $value->ProposedAddressDetails->Score == 100)
					{
						$dtarray = array( $value->id, 
									$value->ProposedAddressDetails->Address[0]->StreetLines, 
									$value->ProposedAddressDetails->Address[0]->City, 
							 		$value->ProposedAddressDetails->Address[0]->StateOrProvinceCode, 
							 		$value->ProposedAddressDetails->Address[0]->PostalCode, 
							 		$value->ProposedAddressDetails->Address[0]->CountryCode, 
							 		json_encode($value), 't', 'passed');
						if( (pg_execute($this->dbconn, "UPDATE_SUCCESS", $dtarray) ) != FALSE)
						{
							$rows_passed++;
						}
					}
					
					// IF Score < 100% then we mark this item validated failed
					if( ($value->Severity == "SUCCESS") && $value->ProposedAddressDetails->Score < 100)
					{
						$dtarray = array($value->id, json_encode($value), "f", "not_passed");
						if( (pg_execute($this->dbconn, "UPDATE_LESS_THAN_100", $dtarray) ) != FALSE)
						{
							$rows_need_mod++;
						}
					}
					
					// IF Score is error then validated error
					if( ($value->Severity == "FAILURE") )
					{
						$dtarray = array($value->id, json_encode($value), "f", "error");
						// IF Score is error then validated error
					 	if( (pg_execute($this->dbconn, "UPDATE_LESS_THAN_100",	$dtarray) ) != FALSE)
						{
							$rows_failed++;
						}
					}
					
				}
			}
			$returnArray = array("status" => 200, "message" => "", 
							"failed" => $rows_failed, 
							"passed" => $rows_passed,
							"need_mod" => $rows_need_mod,
							"data" => $adr);
		}
		catch(Exception $e){
			$returnArray = array("status" => 500, "message" => $e->getMessage());
		}
		return $returnArray;				
	}
}
?>

