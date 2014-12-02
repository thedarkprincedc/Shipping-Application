<?php

  //Configuration
  $access = "1CB7DF83DFECF1D5";
  //$userid = "blenzo";
  //$passwd = "blenzo1848";
  $userid = "brettmosley";
  $passwd = "DricasM4x";
  $wsdl = "UPSWSDL/XAV.wsdl";
  $operation = "ProcessXAV";
  $endpointurl = 'https://wwwcie.ups.com/webservices/XAV';
  $outputFileName = "XOLTResult.xml";

  function processXAV()
  {
      //create soap request
      $option['RequestOption'] = '1';
      $request['Request'] = $option;

      $request['RegionalRequestIndicator'] = '';
      $addrkeyfrmt['ConsigneeName'] = 'RITZ CAMERA CENTERS-1979';
      $addrkeyfrmt['AddressLine'] = array
      (
         '26601 ALISO CREEK ROAD',
 	     'STE D',
 		 'ALISO VIEJO TOWN CENTER'
      );
     // $addrkeyfrmt['Region'] = 'ROSWELL,GA,30075-1521';
 	  $addrkeyfrmt['PoliticalDivision2'] = 'ALISO VIEJOj';
 	  $addrkeyfrmt['PoliticalDivision1'] = 'CA';
 	  $addrkeyfrmt['PostcodePrimaryLow'] = '92656';
 	  $addrkeyfrmt['PostcodeExtendedLow'] = '1521';
 	  $addrkeyfrmt['Urbanization'] = 'porto arundal';
 	  $addrkeyfrmt['CountryCode'] = 'US';
 	  $request['AddressKeyFormat'] = $addrkeyfrmt;

 	  echo "Request.......\n";
	  print_r($request);
      echo "\n\n";
      return $request;
  }

  try
  {

    $mode = array
    (
         'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
         'trace' => 1
    );

    // initialize soap client
  //	$client = new SoapClient($wsdl , $mode);
	$client = new SoapClient($wsdl , $mode);
	//$client = new SoapClient($wsdl, array('trace' => 1)); 
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


    //get response
  	$resp = $client->__soapCall($operation ,array(processXAV()));
	echo "<pre>";
	var_dump($resp);
	echo "</pre>";
    //get status
    echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";

    //save soap request and response to file
    $fw = fopen($outputFileName , 'w');
    fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
    fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
    fclose($fw);

  }
  catch(Exception $ex)
  {
  	print("<pre>");
  	print_r ($ex);
	print("</pre>");
  }

?>
