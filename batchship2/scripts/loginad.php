<?php
	session_start();
	ob_start("ob_gzhandler");
	require_once("class.adlogin.php");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	$action = (isset($_REQUEST["action"])) ? $_REQUEST["action"] : null;
	$adLDAP = new ADLogin();
	$jsonArr = array();
	switch($action)
	{
		case "getalluserinfo": 
			$jsonArr = $adLDAP->ldapgetuserinfo($_REQUEST["username"], $_REQUEST["password"]); 	
		break;
		case "getuserinfo":		$jsonArr = $adLDAP->getuserinfo($_REQUEST["userid"]);								break;
		case "updateemail": 	$jsonArr = $adLDAP->updateemail($_REQUEST["userid"], $_REQUEST["email_address"]);	break;
		case "getsessiondatadump": print_r($_SESSION); break;
		case "getsessiondata": 	$jsonArr = array("status" => 200, "data" => $_SESSION);								break;
		case "setsessiondata":
			
			try{
				$_SESSION["barracuda_login"] = ($request->barracuda_login) ? TRUE : FALSE;
				$jsonArr = array("status" => 200, "data" => $_SESSION);	
			} 
			catch(Exception $e){
				$jsonArr = array("status" => 500, "message" => $e->getMessage());	
			}
		break;
		case "isloggedin":
			$jsonArr = (isset($_SESSION["validated"])) ? 
				 array("status" => 200, 
						"message" => "User is logged in",
						"data" => 
							array("validated" => $_SESSION["validated"])
					  )
				:array("status" => 200, 
						"message" => "User is not Loggedin",
						"data" => 
							array("validated" => FALSE)
					  );
		break;
		case "validate":		$jsonArr = $adLDAP->authenticate($_REQUEST["username"], $_REQUEST["password"]);		break;
		case "removeuser":		$jsonArr = $adLDAP->removeuser($_REQUEST["userid"]); 								break;
		
		case "login":	
			if($request == null)		
				$jsonArr = $adLDAP->authenticate($_REQUEST["username"], $_REQUEST["password"]);
			else {
				$jsonArr = $adLDAP->authenticate($request->username, $request->password);
			}
			$_SESSION = $jsonArr["data"];
		break;
		case "logout":			
			session_destroy();	
			$jsonArr = array("status" => 200, "message" => "User has been logged out");						
		break;
	}
	header('Content-Type: application/javascript');
	print(json_encode($jsonArr));
?>