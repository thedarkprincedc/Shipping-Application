<?php
ob_start("ob_gzhandler");
$returnarray = array();
try{
	$dbconn = null;
	$thirdpartybilling = array();
	if( ($dbconn = pg_connect("host=sched-db.fcp.biz port=5432 dbname=batchship2 user=bmosley password=bm7830")) == FALSE){
		 throw new Exception("Error: Could not connect to database host");
	}	
	
	if( ($result = pg_query($dbconn, "SELECT * FROM v_thirdpartyaddress")) != FALSE){
		$returnarray = pg_fetch_all($result);
		foreach ($returnarray as $key => &$value) {
			$value["label"] = "{$value["companyname"]} - {$value["carrier"]} #{$value["accountnumber"]}";
			$value["country"] = "US";
		}
	}
}
catch(Exception $e){
	
}
header('Content-Type: application/javascript');
print(json_encode($returnarray));
?>