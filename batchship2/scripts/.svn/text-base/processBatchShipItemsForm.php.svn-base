<?php
	error_reporting(0);
	session_start();
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	ob_start("ob_gzhandler");
	require_once("class.batchship.php");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	$action = ( isset($_REQUEST["action"]) ) ? $_REQUEST["action"] : null;
	$jsonArr = array();
	
	$batchShipObj = new BatchShip();
	
	switch($action){
		case "modifyuserinfo": 				$jsonArr = $batchShipObj->modifyUserInfo($request); 							break;
		// ThirdPartyShipping
		case "modifythirdpartyshipinfo": 	$jsonArr = $batchShipObj->modifyThirdPartyShipping($request);		break;
		case "getThirdPartyShipInfo":		
		
		$jsonArr = $batchShipObj->getThirdPartyShippingInfo($_REQUEST);					break;
		case "addthirdpartyshipinfo" : 		$jsonArr = $batchShipObj->addThirdPartyShippingInfo($request);					break;
		case "deletethirdpartyshipinfo": 	$jsonArr = $batchShipObj->deletethirdpartyshipinfo($request); 					break;
		
		case "getconfigs": 					$jsonArr = $batchShipObj->getConfiguration(); 									break;
		case "test_pipe_file": 				$batchShipObj->exportToPipeDelim("test.txt", "");								break;
		case "update_singleaddress": 		$jsonArr = $batchShipObj->update_singleaddress($request); 						break;
		case "batchtakepropadd": 			$jsonArr = $batchShipObj->bs_takePropAddressesByBatchID($_REQUEST["batchid"]); 	break;
		
		// ValidateAddress
		
		case "validateAddress" :  			$jsonArr = $batchShipObj->validateAddress($_REQUEST); 							break;
		case "validateFedExAddress":		$jsonArr = $batchShipObj->bv_FedexValidateBatch($_REQUEST); 					break;
		case "validateUPSAddress":																							break;
		case "markallasvalidated": 			$jsonArr = $batchShipObj->bv_MarkAllAsValidated($_REQUEST);																				break;
		// **************
		case "processbatchship_request":	$jsonArr = $batchShipObj->processBatchShipRequest($_REQUEST);					break;	
		case "deletebatchitem":				
		$jsonArr = $batchShipObj->bs_deleteBatchImportItem($_REQUEST["batchid"]);		
		break;
		case "update":						$jsonArr = $batchShipObj->bs_GetImportBatchItemsByID($_REQUEST["batchid"]);		break;
		case "getBatchItemListByUserID": 	$jsonArr = $batchShipObj->bs_GetImportBatchItemsByUserID($_REQUEST["userid"], $_REQUEST["privs"]); 	break;
		case "uploadandprocess":
			 			
			$jsonArr = $batchShipObj->batchUploadFile($_REQUEST, $_FILES); 
		break;
		case "validatethirdparties":
			$jsonArr = $batchShipObj->thirdpartyvalidate();
		break;
		case "showaddresses":				$jsonArr = $batchShipObj->bs_GetAddressesByBatchID($_REQUEST["batchid"]);		break;
		case "showaddressesbybatchandstate":
			$jsonArr = $batchShipObj->bs_GetAddressesByBatchIDAndStatus($_REQUEST["batchid"], $_REQUEST["status"]); 
		break;
		case "showaddressesbywhere":
			$jsonArr = $batchShipObj->bs_GetAddressesByWhere($request);
		break;
		case "getaddressbatchinfo":
			$jsonArr = $batchShipObj->bs_GetAddressBatchInfo($_REQUEST["batchid"]);
			break;
		case "deleteaddressitem":			$jsonArr = $batchShipObj->bs_DeleteBatchShipAddressItem($_REQUEST["addressid"]); 	break;
		case "getponumbersbybatch": 		$jsonArr = $batchShipObj->getPoNumbersByBatch($_REQUEST);				break; 
		case "processbatchshiprequest":		$jsonArr = $batchShipObj->processBatchShipRequest($request);			break;
		case "isholiday": 
			$jsonArr = $batchShipObj->isHoliday($_REQUEST);
		break;
	}
	if($action == "exportbatchexcelcoutput"){
		$batchShipObj->exportBatchToExcelCOutput($_REQUEST["batchid"]);
	}
	else{
		header('Content-Type: application/javascript');
		print(json_encode($jsonArr));
	}
?>