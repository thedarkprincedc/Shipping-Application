<?php
	require_once('../../../Schedule_List_Views/common/php/class.LDAP.php');
	class ADLogin
	{
		protected $ldap = null;
		protected $dbconn = null;
		public function __construct() 
		{
			if( ($this->dbconn = pg_connect("host=sched-db.fcp.biz port=5432 dbname=batchship2 user=bmosley password=bm7830")) == FALSE){
				throw new Exception("Error: Could not connect to database host");
			}
			$this->ldap = new LDAP();
			$this->ldap->init_client();
		}
		public function updateemail($userid, $emailaddress){
			$returnArray = array();
			try{
				if($userid == null)
					throw new Exception("Error: Userid is null");
				if($emailaddress == null)
					throw new Exception("Error:Emailaddress is null");
				
				$query = "";
				if( ($result = pg_query_params($this->dbconn, 
						"UPDATE users SET email_address = $2, registered=$3 WHERE id = $1", 
						array($userid, $emailaddress, "t"))) == FALSE)
				{
					throw new Exception("Error: Could process query");
				}
				else{
					if(pg_affected_rows($result)> 0)
					{
						$returnArray = array("status" => 200, "message"=> "<b>Success</b>: Entry updated");
						$returnArray["query"] = $query;
						$returnArray["userid"] = $userid;
						$returnArray["emailadd"] = $emailaddress;
					}
					else{
						throw new Exception("Error: not process query");
					}
				}
			}
			catch(Exception $e){
				$returnArray = array("status" => 500, "messages"=> $e->getMessage());
			}
			return $returnArray;
		}
		public function ldapgetuserinfo($username, $password){
			
			return $this->ldap->get_user($username, $password);
		}
		public function removeuser($userid){
			$returnArray = array();
			try
			{
				if($userid == null)
					throw new Exception("Error: Could process query-> userid is null");
				if($result = pg_query_params($this->dbconn, "DELETE FROM users WHERE id=$1", array($userid))){
					$returnArray = array("status" => 200, "message"=> "<b>Success: </b> " . pg_affected_rows($result) . " row affected");
				}
			}
			catch(Exception $e)
			{
				$returnArray = array("status" => 500, "message"=> $e->getMessage());
			}
			return $returnArray;
		}
		public function getuserinfo($userid)
		{
			$returnArray = array();
			try
			{
				if($userid == null)
					throw new Exception("Error: Could process query-> userid is null");
				
				$strArry = array();
				if(strcasecmp($userid, "all") == 0)
				{
					$result = pg_query($this->dbconn, "SELECT * FROM users");
					while( ($row = pg_fetch_assoc($result))){
						$strArry[] =$row;
					}
				}
				else{
					if( ($result = pg_query_params($this->dbconn, "SELECT * FROM users WHERE id=$1", array($userid))) != FALSE){	
						if( ($row = pg_fetch_assoc($result))){
							$strArry[] = $row;
						}
					}
				}
				$returnArray = array("status" => 200, "messages"=> "<b>Success: </b>" . pg_num_rows($result) . " rows returned", "data" => $strArry);
			}
			catch(Exception $e)
			{
				$returnArray = array("status" => 500, "messages"=> $e->getMessage());
			}
			return $returnArray;
		}
		public function addAccountToUsers($username, $password)
		{
			try
			{
				if($username == null)
					throw new Exception("Error: Could process query-> username is null");
				if($password == null)
					throw new Exception("Error: Could process query-> password is null");
				$returnArray = array();	
				$userAuthInfo = $this->ldap->get_user($username, $password);
				if($userAuthInfo["authenticated"] == true)
				{
					$returnArray["first_name"] = $userAuthInfo["ldap_info"][0]["givenname"][0];
					$returnArray["last_name"] = $userAuthInfo["ldap_info"][0]["sn"][0];
					if( ($result = pg_query_params($this->dbconn, "SELECT * FROM users WHERE username=$1", array($username))) != FALSE)
					{
						 if(pg_num_rows($result) > 0)
						 {
						 	if( ( $result = pg_query_params($this->dbconn, "INSERT INTO users (username, first_name, last_name, privaleges) VALUES ($1, $2, $3, 'user')",
						 		array($username, $returnArray["first_name"], $returnArray["last_name"]))) != FALSE)
					 		{
						 		$returnArray["status"] = 200;
						 	}
						 }
					}
				}
			}
			catch(Exception $e)
			{
				$returnArray = array("status" => 500, "messages"=> $e->getMessage());
			}
			return $returnArray;
		}
		public function authenticate($username, $password)
		{
			try
			{
				if($username == null)
					throw new Exception("Error: Could process query-> username is null");
				if($password == null)
					throw new Exception("Error: Could process query-> password is null");
				$returnArray = array();
				$userAuthInfo = $this->ldap->get_user($username, $password);
				$isInBatchShipAppGroup = false;
				foreach ($userAuthInfo["groups"] as $key => $value) {
					if( (preg_match("/batch ship app/i", $value)) == 1){
						$isInBatchShipAppGroup = true;
					}
				}
				if($userAuthInfo["authenticated"] == true)
				{
					
					if( ($result = pg_query_params($this->dbconn, "SELECT * FROM users WHERE username = $1", array($username)))){
						if(pg_num_rows($result) > 0){
							if($row = pg_fetch_array($result)){
								foreach ($userAuthInfo["groups"] as $key => $value) {
									$value=explode(",", $value);
									$value=explode("=", $value[0]);
									$returnArray["ad_groups"][] = $value[1];
								}
								$returnArray = array("status" => 200, 
													"message" => "Welcome " . $userAuthInfo["ldap_info"][0]["givenname"][0], 
													"data" => array(
														"username" => $username,
														"firstname" => $userAuthInfo["ldap_info"][0]["givenname"][0],
														"lastname" => $userAuthInfo["ldap_info"][0]["sn"][0],
														"userid" => $row["id"], 
														"emailaddress" => $row["email_address"],
														"validated" => TRUE,
														"logon_message" => "Welcome " . $username, 
														"privs" => $row["privaleges"],
														"isinbatchshipappgroup" => $isInBatchShipAppGroup,
														"registered" => $row["registered"])
													);
								
							}		
						}
						else{
							// is not found needs to registered
							$returnArray = array("status" => 200, "message" => "User needs to register");
							$query = "INSERT INTO users (username,first_name,last_name,privaleges,registered) VALUES ($1,$2,$3,$4,$5) RETURNING id";
							if($result = pg_query_params($this->dbconn, $query, 
								array($username, 
									$userAuthInfo["ldap_info"][0]["givenname"][0], 
									$userAuthInfo["ldap_info"][0]["sn"][0], 
									"user",
									"f"
								))){
									if($row = pg_fetch_assoc($result))
									{
										$returnArray = array("status" => 200, 
													"message" => "Welcome " . $userAuthInfo["ldap_info"][0]["givenname"][0], 
													"data" => array(
														"username" => $username,
														"firstname" => $userAuthInfo["ldap_info"][0]["givenname"][0],
														"lastname" => $userAuthInfo["ldap_info"][0]["sn"][0],
														"userid" => $row["id"], 
														"emailaddress" => $row["email_address"],
														"validated" => TRUE,
														"logon_message" => "Welcome " . $username, 
														"privs" => $row["privaleges"],
														"isinbatchshipappgroup" => $isInBatchShipAppGroup,
														"registered" => $row["registered"])
										);
									}	
										
							}
								
						}
					}
				/*	if( ($result = pg_query_params($this->dbconn, "SELECT * FROM users WHERE username = $1", array($username))) != FALSE){
						
						if($row = pg_fetch_array($result)){	
							foreach ($userAuthInfo["groups"] as $key => $value) {
								$value=explode(",", $value);
								$value=explode("=", $value[0]);
								$returnArray["ad_groups"][] = $value[1];
							}		
							$returnArray = array("status" => 200, 
													"message" => "Welcome " . $userAuthInfo["ldap_info"][0]["givenname"][0], 
													"data" => array(
														"username" => $username,
														"firstname" => $userAuthInfo["ldap_info"][0]["givenname"][0],
														"lastname" => $userAuthInfo["ldap_info"][0]["sn"][0],
														"userid" => $row["id"], 
														"emailaddress" => $row["email_address"],
														"validated" => TRUE,
														"logon_message" => "Welcome " . $username, 
														"privs" => $row["privaleges"],
														"registered" => $row["registered"])
													);
						}
					}
				}
				else{
					$returnArray = array("status" => 200, 
											"message" => "User Credentials Failed Authentication",
											"data" => array(
												"validated" => FALSE, 
												"privs" => "none")
											);*/
				}
				else{
					$returnArray = array("status" => 200, "message" => "User Credentials Failed Authentication",
											"data" => array("validated" => FALSE, "privs" => "none"));
				}
			}
			catch(Exception $e)
			{
				$returnArray = array("status" => 500, "message"=> $e->getMessage());
			}
			return $returnArray;
		}
	} 
?>