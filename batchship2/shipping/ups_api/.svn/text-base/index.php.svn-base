<?php
/**
 * Include the configuration file
 */
require_once('inc/config.php');
require_once("./UpsAPI.php");

//require_once("USAddressValidation.php");
for($i=0;$i<1;$i++){

/*
        $address['city'] = "Rochester";
        $address['state'] = "NY";
        $address['zip_code'] = "14606";
		$address['zip_code_ext'] = "";
		$address['company_name'] = "Flower City Printing";
		$address['Building_Name'] = "";
		$address['street1'] = "1725 Mt. Read Blvd";
		$address['street2'] = "";
		$address['street3'] = "";
		$address['country'] = "US";
*/		
		$address = array('city' => "Rochester", 'state' => "NY", 'zip_code' => "14606", 'zip_code_ext' => "",
			'company_name' => "Flower City Printing", 'Building_Name' => "", 'street1' => "1725 Mt. Read Blvd",
			'street2' => "", 'street3' => "", 'country' => "US");
		
        $validation = new UpsAPI_USAddressValidation($address);
		
        $xml = $validation->buildRequest("");
       
		var_dump($xml);
		
		echo "<br><br>";

        // check the output type
        //if ($_GET['output'] == 'array')
        //{
        //        $response = $validation->sendRequest($xml, false);
        //        echo 'Response Output:<br />';
        //        var_dump($response);
        //} // end if the output type is an array
        //else
        //{
		$response = $validation->sendRequest($xml, false);
		print("<pre>");
		var_dump($response);
		print("</pre>");
		//		echo 'Response Output:<br />';
        //        echo '<pre>'.htmlentities($response).'</pre>';
        //} // end else the output type is XML
        
        echo 'UpsAPI_USAddressValidation::getMatchType() Output:<br />';
        var_dump($validation->getMatchType());
        echo 'UpsAPI_USAddressValidation::getMatches() Output:<br />';
        var_dump($validation->getMatches());
}

?>