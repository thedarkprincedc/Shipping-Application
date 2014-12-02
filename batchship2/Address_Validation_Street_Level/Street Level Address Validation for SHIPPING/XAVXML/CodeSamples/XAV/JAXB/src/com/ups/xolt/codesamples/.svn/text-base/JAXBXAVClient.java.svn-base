/* 
 ** 
 ** Filename: JAXBXAVClient.java
 ** Authors: United Parcel Service of America
 ** 
 ** The use, disclosure, reproduction, modification, transfer, or transmittal 
 ** of this work for any purpose in any form or by any means without the 
 ** written permission of United Parcel Service is strictly prohibited. 
 ** 
 ** Confidential, Unpublished Property of United Parcel Service. 
 ** Use and Distribution Limited Solely to Authorized Personnel. 
 ** 
 ** Copyright 2009 United Parcel Service of America, Inc.  All Rights Reserved. 
 ** 
 */ 
package com.ups.xolt.codesamples;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.ByteArrayInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.StringWriter;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLConnection;
import java.util.List;
import java.util.Properties;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;

import com.ups.xolt.codesamples.accessrequest.jaxb.AccessRequest;
import com.ups.xolt.codesamples.request.jaxb.AddressKeyFormatType;
import com.ups.xolt.codesamples.request.jaxb.AddressValidationRequest;
import com.ups.xolt.codesamples.request.jaxb.ObjectFactory;
import com.ups.xolt.codesamples.request.jaxb.RequestType;
import com.ups.xolt.codesamples.response.jaxb.AddressValidationResponse;

public class JAXBXAVClient {
	
	private static final String LICENSE_NUMBER = "accesskey";
	private static final String USER_NAME = "username";
	private static final String PASSWORD = "password";
	private static final String ENDPOINT_URL="url";
	private static final String OUT_FILE_LOCATION = "out_file_location";
	static Properties props = null;
	static{
    	try{
        	props = new Properties();
        	props.load(new FileInputStream("./build.properties"));
    	}catch (Exception e) {
			e.printStackTrace();
		}    	
    }
  
    public static void main( String[] args ) {    
		StringWriter strWriter = null;
        try {	    
        	
        	//Create JAXBContext and marshaller for AccessRequest object        			
        	JAXBContext accessRequestJAXBC = JAXBContext.newInstance(AccessRequest.class.getPackage().getName() );	            
			Marshaller accessRequestMarshaller = accessRequestJAXBC.createMarshaller();
			com.ups.xolt.codesamples.accessrequest.jaxb.ObjectFactory accessRequestObjectFactory = new com.ups.xolt.codesamples.accessrequest.jaxb.ObjectFactory();
			AccessRequest  accessRequest = accessRequestObjectFactory.createAccessRequest();
			populateAccessRequest(accessRequest);
			 
			//Create JAXBContext and marshaller for AddressValidationRequest object
			JAXBContext avRequestJAXBC = JAXBContext.newInstance(AddressValidationRequest.class.getPackage().getName() );	            
			Marshaller xavRequestMarshaller = avRequestJAXBC.createMarshaller();
			ObjectFactory requestObjectFactory = new ObjectFactory();
			AddressValidationRequest xavRequest = requestObjectFactory.createAddressValidationRequest();
			populateXAVRequest(xavRequest);			
			//Get String out of access request and AddressValidationRequest objects.
			strWriter = new StringWriter();       		       
			accessRequestMarshaller.marshal(accessRequest, strWriter);
			xavRequestMarshaller.marshal(xavRequest, strWriter);
			strWriter.flush();
			strWriter.close();
			System.out.println("Request: " + strWriter.getBuffer().toString());
			
			String strResults =contactService(strWriter.getBuffer().toString());
			
			//Parse response object.
			JAXBContext xavResponseJAXBC = JAXBContext.newInstance(AddressValidationResponse.class.getPackage().getName());
			Unmarshaller xavResponseUnmarhsaller = xavResponseJAXBC.createUnmarshaller();
			ByteArrayInputStream input = new ByteArrayInputStream(strResults.getBytes());
			Object objResponse = xavResponseUnmarhsaller.unmarshal(input);
			AddressValidationResponse	xavResponse = (AddressValidationResponse)objResponse;
			System.out.println("Response Status: " + xavResponse.getResponse().getResponseStatusDescription());
			
			List<com.ups.xolt.codesamples.response.jaxb.AddressKeyFormatType> addressKeyformatList  = xavResponse.getAddressKeyFormat();
			if(addressKeyformatList != null && addressKeyformatList.size()> 0){
				System.out.println("<AddressKeyFormat List>");
				int cnt = 0;
				while(cnt < addressKeyformatList.size()){
					com.ups.xolt.codesamples.response.jaxb.AddressKeyFormatType addressKeyFormat = addressKeyformatList.get(cnt);
					System.out.println("AddressLine: " + addressKeyFormat.getAddressLine().get(0));
					System.out.println("Region: " + addressKeyFormat.getRegion());
					System.out.println("PoliticalDivision2: " + addressKeyFormat.getPoliticalDivision2());
					System.out.println("PoliticalDivision1: " + addressKeyFormat.getPoliticalDivision1());
					System.out.println("PostcodePrimaryLow: " + addressKeyFormat.getPostcodePrimaryLow());
					System.out.println("PostcodeExtendedLow: " + addressKeyFormat.getPostcodeExtendedLow());
					System.out.println("");
					cnt++;
				}
			}
			
			updateResultsToFile(strResults);		   
        } catch (Exception e) {
			updateResultsToFile(e.toString());
			e.printStackTrace();
		} finally{
			try{
				if(strWriter != null){
					strWriter.close();
					strWriter = null;
				}
			}catch (Exception e) {
				updateResultsToFile(e.toString());
				e.printStackTrace();
			}
		}
    }    
    
	private static String contactService(String xmlInputString) throws Exception{		
		String outputStr = null;
		OutputStream outputStream = null;
		try {

			URL url = new URL(props.getProperty(ENDPOINT_URL));
			System.out.println("url:"+url);
			
			HttpURLConnection connection = (HttpURLConnection) url.openConnection();
			System.out.println("Client established connection with " + url.toString());
			// Setup HTTP POST parameters
			connection.setDoOutput(true);
			connection.setDoInput(true);
			connection.setUseCaches(false);
			
			outputStream = connection.getOutputStream();		
			outputStream.write(xmlInputString.getBytes());
			outputStream.flush();
			outputStream.close();
			System.out.println("Http status = " + connection.getResponseCode() + " " + connection.getResponseMessage());
			
			outputStr = readURLConnection(connection);			
		} catch (Exception e) {
			System.out.println("Error sending data to server");
			throw e;
		} finally {						
			if(outputStream != null){
				outputStream.close();
				outputStream = null;
			}
		}		
		return outputStr;
	}
	
	/**
	 * This method read all of the data from a URL connection to a String
	 */

	public static String readURLConnection(URLConnection uc) throws Exception {
		StringBuffer buffer = new StringBuffer();
		BufferedReader reader = null;
		try {
			reader = new BufferedReader(new InputStreamReader(uc.getInputStream()));
			int letter = 0;			
			while ((letter = reader.read()) != -1){
				buffer.append((char) letter);
			}
			reader.close();
		} catch (Exception e) {
			System.out.println("Could not read from URL: " + e.toString());
			throw e;
		} finally {
			if(reader != null){
				reader.close();
				reader = null;
			}
		}
		return buffer.toString();
	}

    /**
     * Populates the access request object.
     * @param accessRequest
     */
    private static void populateAccessRequest(AccessRequest accessRequest){
    	accessRequest.setAccessLicenseNumber(props.getProperty(LICENSE_NUMBER));
    	accessRequest.setUserId(props.getProperty(USER_NAME));
    	accessRequest.setPassword(props.getProperty(PASSWORD));
    }
   
    /**
     * Populate AddressValidationRequest object
     * @param avRequest
     */
    private static void populateXAVRequest(AddressValidationRequest xavRequest){   	
    	RequestType request = new RequestType();
     	//set request option and request action
    	request.setRequestOption("3");
     	request.setRequestAction("XAV");
     	xavRequest.setRequest(request);
     	//set addresskeyformattype
     	AddressKeyFormatType addressKeyFormatType = new AddressKeyFormatType();
     	addressKeyFormatType.getAddressLine().add("AIRWAY ROAD SUITE 7");
     	addressKeyFormatType.setPoliticalDivision2("SAN DIEGO");
     	addressKeyFormatType.setPoliticalDivision1("CA");
     	addressKeyFormatType.setPostcodePrimaryLow("92154");
     	addressKeyFormatType.setCountryCode("US");
     	xavRequest.getAddressKeyFormat().add(addressKeyFormatType);
    }
    
    /**
     * This method updates the XOLTResult.xml file with the received status and description
     * @param statusCode
     * @param description
     */
    
    private static void updateResultsToFile(String response){
    	BufferedWriter bw = null;
    	try{    		
    		
    		File outFile = new File(props.getProperty(OUT_FILE_LOCATION));
    		System.out.println("Output file deletion status: " + outFile.delete());
    		outFile.createNewFile();
    		System.out.println("Output file location: " + outFile.getCanonicalPath());
    		bw = new BufferedWriter(new FileWriter(outFile));
    		bw.write(response);
    		bw.close();    		    		
    	}catch (Exception e) {
			e.printStackTrace();
		}finally{
			try{
				if (bw != null){
					bw.close();
					bw = null;
				}
			}catch (Exception e) {
				e.printStackTrace();
			}			
		}		
    }
    

}