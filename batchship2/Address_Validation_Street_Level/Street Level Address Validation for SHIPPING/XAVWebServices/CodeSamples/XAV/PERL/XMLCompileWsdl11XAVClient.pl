 no warnings; # turn off warnings
 
 use XML::Compile::WSDL11;
 use XML::Compile::SOAP11;
 use XML::Compile::Transport::SOAPHTTP;
 use HTTP::Request;
 use HTTP::Response;
 use Data::Dumper;
 
 #Configuration
 $access = " Add License Key Here";
 $userid = " Add User Id Here";
 $passwd = " Add Password Here";
 $operation = "ProcessXAV";
 $endpointurl = " Add URL Here";
 $wsdlfile = " Add Wsdl File Here ";
 $schemadir = "Add Schema Location Here";
 $outputFileName = "XOLTResult.xml";
 
 sub processXAV
 {
 	my $request =
 	{
 		UPSSecurity =>  
	  	{
		   UsernameToken =>
		   {
			   Username => "$userid",
			   Password => "$passwd"
		   },
		   ServiceAccessToken =>
		   {
			   AccessLicenseNumber => "$access"
		   }
	  	},
	  
 		Request =>
 		{
 			RequestOption => '1'
 		},
 		
 		RegionalRequestIndicator => '',
 		AddressKeyFormat =>
 		{
 			ConsigneeName => 'RITZ CAMERA CENTERS-1979',
 			AddressLine =>
 			[
 				'26601 ALISO CREEK ROAD',
 				'STE D',
 				'ALISO VIEJO TOWN CENTER'
 			],
 			Region => 'ROSWELL,GA,30075-1521',
 			PoliticalDivision2 => 'ALISO VIEJO',
 			PoliticalDivision1 => 'CA',
 			PostcodePrimaryLow => '92656',
 			PostcodeExtendedLow => '1521',
 			Urbanization => 'porto arundal',
 			CountryCode => 'US'
 		}
 	};
 	
 	return $request;
 }
 
 my $wsdl = XML::Compile::WSDL11->new( $wsdlfile );
 #my @schemas = glob "$schemadir/*.xsd";
 #$wsdl->importDefinitions(\@schemas);
 my $operation = $wsdl->operation($operation);
 my $call = $operation->compileClient(endpoint => $endpointurl);
 #print $wsdl->explain('ProcessXAV' , PERL => 'INPUT' , recurse => 1);
 
 ($answer , $trace) = $call->(processXAV() , 'UTF-8');	
 
 if($answer->{Fault})
 {
	print $answer->{Fault}->{faultstring} ."\n";
	print Dumper($answer);
	print "See XOLTResult.xml for details.\n";
		
	# Save Soap Request and Response Details
	open(fw,">$outputFileName");
	$trace->printRequest(\*fw);
	$trace->printResponse(\*fw);
	close(fw);
 }
 else
 {
	# Get Response Status Description
    print "Description: " . $answer->{Body}->{Response}->{ResponseStatus}->{Description} . "\n"; 
        
    # Print Request and Response
    my $req = $trace->request();
 	print "Request: \n" . $req->content() . "\n";
	my $resp = $trace->response();
	print "Response: \n" . $resp->content();
		
	# Save Soap Request and Response Details
	open(fw,">$outputFileName");
	$trace->printRequest(\*fw);
	$trace->printResponse(\*fw);
	close(fw);
}
 