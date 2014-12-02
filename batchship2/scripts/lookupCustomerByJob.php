<?php
//$csr_email = '';// init

require_once("../../db_connect.php");
$jobnumber = (isset($_REQUEST["jobnumber"])) ? $_GET["jobnumber"] : null;
function getCustomerNameByJobNumber($jobnumber, $connect){
	$jsonArray = array();
	try{
		if($jobnumber == null)
			throw new Exception("Error: No job number was entered");
		
		$where_clause = "";
		if(substr($jobnumber,-1) == "@")
		{
			$jobNumber = substr($jobnumber,0,-1);
			$where_clause = "WHERE Job_Number LIKE '{$jobnumber}%'";
		}
		else {
			$where_clause = "WHERE Job_Number = '{$jobnumber}'";
		}
		$qString = "SELECT Customer_Name, Job_Number, CS_Rep FROM Job_Tickets {$where_clause}";
		
		if( ($result = odbc_exec($connect, $qString)) == FALSE)
			throw new Exception("Error: Could not execute query");
		$data = array();
		while($job = odbc_fetch_array($result)) {
			
			$csremail;
			if( ($result2 = odbc_exec($connect, "SELECT Initials, User_ID, Last_Name, First_Name, email FROM csr WHERE Initials = '{$job["CS_REP"]}'")) == FALSE){
				throw new Exception("Could not process csr");
			}
			if( (odbc_num_rows($result2)) > 1){
				throw new Exception("Multiple CSR's found with the same initials");
			}
			while($row2 = odbc_fetch_array($result2)) {
				$csremail = $row2["EMAIL"];
			}	
			
			$data[] = array(
							"customer_name" => $job["CUSTOMER_NAME"],
							"job_number" => $job["JOB_NUMBER"],
							"csr_rep" => $job["CS_REP"],
							"csr_email" => $csremail
							);
		}

		$jsonArray = array("status" => 200, "message" => odbc_num_rows($result) . " rows returned successfully", "data" => $data);
	}
	catch(Exception $e){
		$jsonArray = array("status" => 500, "error" => $e->getMessage());
	}
	return $jsonArray;
}
$arru = getCustomerNameByJobNumber($jobnumber, $connect);
print( json_encode($arru) );
?>