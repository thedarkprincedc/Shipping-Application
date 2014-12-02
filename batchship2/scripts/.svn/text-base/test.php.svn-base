<pre>
<?php
	require_once("class.logging.php");
	
	
	$logging = new BatchShipLogging();
	$logging->setFlatFileConfiguration("testlog.txt");
	$logging->startTimeStamp();
	$logging->loglineFlatfile("subject=testing;description=could not process request;batch_id=1;user_id=1;hello=ddssa;");
	$logging->loglinesql("logging", "subject=testing;description=could not process request;batch_id=1;user_id=1;hello=ddssa;");
	$logging->endTimeStamp();
	$timediff = $logging->timeDifference();
	//$logging->loglineRecuriveArrayFlatfile(array("status" =>"aaaaaa"));
	$logging->loglineFlatfile("subject=timediff;description={$timediff}s;batch_id=1;user_id=1;");
	
?>
</pre>