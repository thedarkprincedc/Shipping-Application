<?php
	class PGSQLDatabase{
		public $dbconn = null;
		public $host = null;
		public $port = null;
		public $dbname = null;
		public $user = null;
		public $password = null;
		public function __construct(){
			try{
				$iniinfo = parse_ini_file("../configuration.ini", true);
				$this->host = $iniinfo["database_settings"]["host"];
				$this->port = $iniinfo["database_settings"]["port"];
				$this->dbname = $iniinfo["database_settings"]["dbname"];
				$this->user = $iniinfo["database_settings"]["user"];
				$this->password = $iniinfo["database_settings"] ["passsword"];
				if( ($this->dbconn = pg_connect("host={$this->host} port={$this->port} dbname={$this->dbname} user={$this->user} password={$this->password}")) == FALSE){
					throw new Exception("Error: Could not connect to database host");
				}
				
			}
			catch(PDOException $e){
				print($e->getMessage());
			}		
		}
		public function getConnection(){
			return $this->dbconn;
		}
	}
?>