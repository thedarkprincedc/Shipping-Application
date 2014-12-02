<?php

class BatchShipLogging {
	protected $dbconn;
	protected $flatfilename;
	protected $time_start = null;
	protected $time_end = null;
	
	public function __construct() {
		
	}
	public function startTimeStamp(){
		$this->time_start = microtime(true);
	}
	public function endTimeStamp(){
		$this->time_end = microtime(true);
	}
	public function timeDifference(){
		return round(($this->time_end - $this->time_start),8);
	}
	/*public function stopWatchFunction($functionName){
		$this->startTimeStamp();
		$functionName();
		$this->endTimeStamp();
		return $this->timeDifference();
	}*/
	public function loadDatabaseConfiguration($configurationFile){
		// Database Initialization
		if( ($this->dbconn = pg_connect("host={$dbhost} port={$dbport} dbname={$dbname} user={$dbusername} password={$dbpassword}")) == FALSE){
			echo "Error: Could not connect to database host";
		}
	}
	public function setFlatFileConfiguration($sflatFilename){
		$this->flatfilename = $sflatFilename;
	}
	public function loglineRecuriveArrayFlatfile($arraydata){
		if( ($handle = fopen($this->flatfilename, "a+"))) {
			fwrite($handle, print_r($arraydata, true));
			fclose($handle);
		}
	}
	public function loglineFlatfile($logText){
		if( ($handle = fopen($this->flatfilename, "a+"))) {
			//print($logText . "\n");
			$logtextArray = explode(";", $logText);
			//print_r($logtextArray);
			$resultarray = array();
			for ($i=0; $i < (count($logtextArray)-1); $i++) { 
				$logtextSubArray = explode("=", $logtextArray[$i]);
				$resultarray[$logtextSubArray[0]] = $logtextSubArray[1];
			}
			//print_r($resultarray);
			$resultarray["timestamp"] = date('Y-m-d H:i:s');
			$format = "";
			$count = 0;
			foreach ($resultarray as $key => $value) {		
				$format .= ($count == 0) ? "{$key}='%s'" : ", \t {$key}='%s'";		
				$count++;
			}
			fwrite($handle, vsprintf ($format . "\n", $resultarray));
			fclose($handle);
		}
		else{
			throw new Exception("Error: Could not open file for writing");
		} 
		return $resultarray;
	}
	public function loglinesql($tablename, $logText){
		if(strlen($tablename) == 0)
			throw new Exception("Error: No Table supplied");
		if(strlen($logText) == 0)
			throw new Exception("Error: No Logging text supplied");
		
		$logtextArray = explode(";", $logText);
		$resultarray = array();
		for ($i=0; $i < (count($logtextArray)-1); $i++) { 
			$logtextSubArray = explode("=", $logtextArray[$i]);
			$resultarray[$logtextSubArray[0]] = $logtextSubArray[1];
		}
		//$resultarray["timestamp"] = date('Y-m-d H:i:s');
		$columns = "";
		$values = "";
		$count = 0;
		foreach ($resultarray as $key => $value) {		
			$columns .= ($count == 0) ? "{$key}" : ", {$key}";		
			$values .= ($count == 0) ? "'{$value}'" : ", '{$value}'";	
			$count++;
		}
		$query = "INSERT INTO {$tablename} ({$columns}) VALUES ({$values})";
		return $query;
	}
}
?>