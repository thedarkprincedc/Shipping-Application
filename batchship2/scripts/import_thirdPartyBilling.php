<?php
require_once("class.pgsqldatabase.php");
include '../../db_connect.php';
try{
	

	$qString = "SELECT ID as ID,
			CompanyName as CompanyName,
			Street as Street,
			City as City,
			State as State,
			Zip as Zip,
			Country as Country,
			AccountNumber as AccountNumber,
			Carrier as Carrier,
			type as type,
			shipping_address as shipping_address
		FROM ThirdPartyBillAddresses
		WHERE type='Batch Ship'
		ORDER BY CompanyName";
	$dbconn = null;
$pgsqldatabase = new PGSQLDatabase();
	if(($dbconn = $pgsqldatabase->getConnection()) == FALSE){	
		 throw new Exception("Error: Could not connect to database host");
	}	
	
	$result = odbc_exec($connect, $qString);
	$thirdPartyBilling = array();
	
	$numRecords = odbc_num_rows($result);
	pg_query($dbconn, "DELETE FROM thirdpartyaddress") or die("sadasdsadsad");
	while($address = odbc_fetch_array($result)){
		$thirdPartyBilling[] = $address;
		$shippingaccount = array(
										"carrier" => $address["Carrier"], 
										"accountnumber" =>	$address["AccountNumber"],
										"lastupdated" => date("Y-m-d")
									);
		//print_r(json_encode($jsonshippingaccount));
		//die();
		
		if( ($resulta = pg_query_params($dbconn, 
			"INSERT INTO thirdpartyaddress (id, company_name, street, city, state, zipcode, shippingaccount, country) VALUES ($1, $2, $3, $4, $5, $6, $7, $8) RETURNING id", 
			array($address["ID"], 
					$address["CompanyName"], 
					$address["Street"],
					$address["City"],
					$address["State"],
					$address["Zip"], json_encode($shippingaccount), $address["Country"]))) != FALSE)
		{
			
		}
		else{
			throw new Exception("Failed address account");
		}
		
	}
}
catch(Exception $e){
	echo $e->getMessage();
}
//echo json_encode($thirdPartyBilling);
?>