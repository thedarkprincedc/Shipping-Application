<?php
	require_once("class.pgsqldatabase.php");
	$query = "SELECT * FROM event_log ORDER BY id DESC";
	$jsonArray = array();
	$pgsqldatabase = new PGSQLDatabase();
	
	if(($dbconn = $pgsqldatabase->getConnection()) == FALSE){
		$jsonArray["sql"]["error"][] = "Error: Could not connect to database host";
		$jsonArray["sql"]["success"] = FALSE;
	}
	if(($result = pg_query($dbconn, $query)) == FALSE)
	{
		$jsonArray["sql"]["error"][] = "Error: Could not process query";
		$jsonArray["sql"]["success"] = FALSE;
		$jsonArray["sql"]["rows_affected"] = pg_affected_rows($result);
	}
	else{
		$jsonArray["sql"]["success"] = TRUE;
		$jsonArray["sql"]["rows_affected"] = pg_affected_rows($result);
		while ($row = pg_fetch_array($result)) {
			$item["id"] = $row["id"];
			$item["subject"] = $row["subject"];
			$item["description"] = $row["description"];
			$item["batch_id"] = $row["batch_id"];
			$item["user_id"] = $row["user_id"];
			$item["timestamp"] = $row["timestamp"];
			$jsonArray["items"][] = $item;
		}
		echo json_encode($jsonArray);
	}
	
?>