<?php

error_reporting ( E_ALL );
ini_set ( 'display_errors', 'On' );
require_once 'config.php';

/** initialize and configure Services_WorkXpress objects **/
// get the configuration settings
$app_role = Services_WorkXpress::APPLICATION_ROLE_OCD;
$api_version = $services_workxpress_config ['api_version'];
$auth_key = $services_workxpress_config [$app_role] ['auth_key'];
$remote_host = $services_workxpress_config [$app_role] ['remote_host'];

// load the Services_WorkXpress object
$workxpress = new Services_WorkXpress ( );
$workxpress->setAPIVersion ( $services_workxpress_config ['api_version'] );
$workxpress->setAuthKey ( $services_workxpress_config [$app_role] ['auth_key'] );
$workxpress->setRemoteHost ( $services_workxpress_config [$app_role] ['remote_host'] );

// load the request object
$request = $workxpress->loadRequest ( 'AddNewItem' );

/** build the request **/
// add items to the request
$item_array = array ('item_type_id' => 11, 'reference' => 'my_first_contact', 'fields' => array (array ('id' => 3100, 'value' => 'John' ), array ('id' => 3101, 'value' => 'Doe' ) ) );
$request->addItem ( $item_array );

/** make the API call **/
try {
	// make the call and get the data array
	$response = $request->call ();
	$items = $response->getDataArray ();
	
	// show the results
	echo '<pre>' . print_r ( $items, true ) . '</pre>';
} // end try
catch ( Services_WorkXpress_Exception $e ) {
	echo '<h1>Error</h1><pre>' . $e->getMessage () . '</pre>';
} // end catch Services_WorkXpress_Exception


?>
