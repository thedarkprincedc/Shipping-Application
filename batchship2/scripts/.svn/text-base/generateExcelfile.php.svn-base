<?php
	session_start();
	require_once("class.pgsqldatabase.php");
	require_once("../../../amfphp/services/class.Database.php");
	require_once('../../../Schedule_List_Views/common/php/PHPExcel.php');
	require_once('../../../Schedule_List_Views/common/php/PHPExcel/IOFactory.php');
	$batchid = (isset($_REQUEST["batchid"])) ? $_REQUEST["batchid"] : null;
	$userid = (isset($_SESSION["username"])) ? $_SESSION["username"] : null;

	ob_start();
	$dbconn = null;
	$spreadsheetHeaders = array("Store Name" => "store_name",	"Store Number" => "store_number",
							"Address1" => "address1", "Address2" => "address2",	"Address3" => "address3", "City" => "city",
							"State/Province" => "state_province", "Postal Code" => "postal_code", "Country" => "country",
							"Attn" => "attn", "Phone Number" =>	"phone_number", "KitNumber"	=> "kit_number", "Weight" => "weight",
							"Dimensions" => "dimensions", "Misc Reference 1" => "misc_reference_1",
							"Misc Reference 2" => "misc_reference_2", "Misc Reference 3" => "misc_reference_3",
							"Misc Reference 4" => "misc_reference_4", "Misc Reference 5" => "misc_reference_5",
							"Insurance" =>	"insurance","ThirdPartyCompanyName"=>"thirdparty_company",	
							"ThirdPartyStreet"=> "thirdparty_street","ThirdPartyCountry"=>"thirdparty_country",
							"ThirdPartyZip" => "thirdparty_zip","ThirdPartyCity"=>	"thirdparty_city",
							"ThirdPartyState" => "thirdparty_statep", "ThirdPartyAccount"=>"thirdparty_account", 
							"Shipping Method" => "shipping_method", "Po Number" => "po_number");
	$columns = array();
	$header = array();
	foreach($spreadsheetHeaders as $index => $values){
		$columns[] = $values;
		$header[] = $index;
	}
	
	$columns = implode(", ", $columns);
	$pgsqldatabase = new PGSQLDatabase();
	
	if(($dbconn = $pgsqldatabase->getConnection()) == FALSE){
		
	}	
	$objPHPExcel = new PHPExcel();
	$rowCount = 1;
	if( ($result = pg_query_params($dbconn, "SELECT {$columns} FROM batchship_items WHERE batch_id = $1", array($batchid))) == TRUE)
	{
		while ($row = pg_fetch_assoc($result)) {
			if($rowCount==1){
				$objPHPExcel->getActiveSheet()->fromArray($header, NULL, 'A'.$rowCount);
				$rowCount++;
			}
			$objPHPExcel->getActiveSheet()->fromArray($row, NULL, 'A'.$rowCount);
			$rowCount++;
		}
	}
	foreach(range("A","Z") as $columnID) {
	    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	}
	$objPHPExcel->getActiveSheet()->getColumnDimension("AA")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AB")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension("AC")->setAutoSize(true);
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);	
	ob_end_clean();
	$filename = date("Ymd") . "-{$userid}-{$batchid}.xlsx";
	header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary ");
	$objWriter->save('php://output');
?>