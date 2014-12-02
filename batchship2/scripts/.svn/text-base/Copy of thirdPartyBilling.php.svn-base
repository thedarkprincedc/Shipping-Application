<?php

include '../../db_connect.php';

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

$result = odbc_exec($connect, $qString);
$thirdPartyBilling = array();

$numRecords = odbc_num_rows($result);
while($address = odbc_fetch_array($result)){
	$thirdPartyBilling[] = $address;
}
//echo "<pre>";
//var_dump($thirdPartyBilling);
//echo "</pre>";

echo json_encode($thirdPartyBilling);
/*
while($address = odbc_fetch_array($result)) {
	foreach($address as $key=>$line){
		$thirdPartyBilling = $thirdPartyBilling . $key."=".$line."|";
	}
	$thirdPartyBilling = substr($thirdPartyBilling,0,-1) . "%";
}

if($numRecords > 0){
	echo substr($thirdPartyBilling, 0, -1);
}
else{
	echo "Error";
}*/
?>