/* 
 ** 
 ** Filename: JAXWSXAVClient.java 
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

import java.io.BufferedWriter;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.util.Calendar;
import java.util.List;
import java.util.Properties;

import javax.xml.ws.BindingProvider;

import com.ups.wsdl.xoltws.xav.v1.XAVErrorMessage;
import com.ups.wsdl.xoltws.xav.v1.XAVPortType;
import com.ups.wsdl.xoltws.xav.v1.XAVService;
import com.ups.xmlschema.xoltws.common.v1.RequestType;
import com.ups.xmlschema.xoltws.error.v1.CodeType;
import com.ups.xmlschema.xoltws.error.v1.ErrorDetailType;
import com.ups.xmlschema.xoltws.error.v1.Errors;
import com.ups.xmlschema.xoltws.upss.v1.UPSSecurity;
import com.ups.xmlschema.xoltws.upss.v1.UPSSecurity.ServiceAccessToken;
import com.ups.xmlschema.xoltws.upss.v1.UPSSecurity.UsernameToken;
import com.ups.xmlschema.xoltws.xav.v1.AddressKeyFormatType;
import com.ups.xmlschema.xoltws.xav.v1.XAVRequest;
import com.ups.xmlschema.xoltws.xav.v1.XAVResponse;

public class JAXWSXAVClient {
	private static String accesskey;
	private static String username;
	private static String password;
	private static String out_file_location = "out_file_location";
	private static String tool_or_webservice_name = "tool_or_webservice_name";
	private static final String endpoint_url = "url";
	static Properties props = null;
	static{
        try{
        	props = new Properties();
        	props.load(new FileInputStream("./build.properties"));
	  		accesskey = props.getProperty("accesskey");
	  		username = props.getProperty("username");
	  		password = props.getProperty("password");
        }
        catch(Exception e){
        	e.printStackTrace();
        }
	}
	
	public static void main(String args[])throws Exception {
		String statusCode = null;
		String description = null;
    try {
      	XAVService xavService = new XAVService();
		XAVPortType xavPort = xavService.getXAVPort();
		BindingProvider bp = (BindingProvider)xavPort;
    	bp.getRequestContext().put(BindingProvider.ENDPOINT_ADDRESS_PROPERTY, props.getProperty(endpoint_url));
		System.out.println("url...."+xavPort);
		XAVRequest xavRequest = new XAVRequest();
		RequestType reqType = new RequestType();
		List<String> requestOption = reqType.getRequestOption();
		requestOption.add("1");
		xavRequest.setRequest(reqType);
		
		AddressKeyFormatType addressKeyFormat = new AddressKeyFormatType();
		List<String> addressKeyFormatLine = addressKeyFormat.getAddressLine();
		addressKeyFormatLine.add("26601 ALISO CREEK ROAD");
		addressKeyFormatLine.add("STE D");
		addressKeyFormatLine.add("ALISO VIEJO TOWN CENTER");
		addressKeyFormat.setCountryCode("US");
		addressKeyFormat.setAttentionName("");
		addressKeyFormat.setConsigneeName("RITZ CAMERA CENTERS-1749");
		addressKeyFormat.setPoliticalDivision1("CA");
		addressKeyFormat.setPoliticalDivision2("ALISO VIEJO");
		addressKeyFormat.setPostcodeExtendedLow("1521");
		addressKeyFormat.setPostcodePrimaryLow("92656");
		addressKeyFormat.setRegion("ROSWELL,GA,30075-1521");
		addressKeyFormat.setUrbanization("porto arundal");
		
		xavRequest.setAddressKeyFormat(addressKeyFormat); 
		xavRequest.setMaximumCandidateListSize("10");
		
		/** ************UPSSE***************************/
		UPSSecurity upsSecurity = new UPSSecurity();
		UsernameToken usernameToken = new UsernameToken();
		usernameToken.setUsername(username);
		usernameToken.setPassword(password);
		upsSecurity.setUsernameToken(usernameToken);
		ServiceAccessToken accessToken = new ServiceAccessToken();
		accessToken.setAccessLicenseNumber(accesskey);
		upsSecurity.setServiceAccessToken(accessToken);
		/** ************UPSSE******************************/
	
		
		XAVResponse xavResponse = xavPort.processXAV(xavRequest, upsSecurity);
		statusCode = xavResponse.getResponse().getResponseStatus().getCode();
		description = xavResponse.getResponse().getResponseStatus().getDescription();
		updateResultsToFile(statusCode, description);
		System.out.println("Transaction Status: "+ xavResponse.getResponse().getResponseStatus().getDescription());
		
		
	} catch (XAVErrorMessage avE) {
		Errors errs= avE.getFaultInfo();
		List<ErrorDetailType> errDetailList = errs.getErrorDetail();
		ErrorDetailType aError = errDetailList.get(0);
		
		CodeType primaryError = aError.getPrimaryErrorCode();
		description = primaryError.getDescription();			
		statusCode = primaryError.getCode();
		updateResultsToFile(statusCode, description);
		System.out.println("\nThe Error Response: Code=" +statusCode + " Decription=" + description);
	} catch (Exception e) {
			description=e.getMessage();
			statusCode=e.toString();
			updateResultsToFile(statusCode, description);
			e.printStackTrace();
		}
		
	}
	/**
     * This method updates the XOLTResult.xml file with the received status and description
     * @param statusCode
     * @param description
     */
	private static void updateResultsToFile(String statusCode, String description){
    	BufferedWriter bw = null;
    	try{    		
    		
    		File outFile = new File(props.getProperty(out_file_location));
    		System.out.println("Output file deletion status: " + outFile.delete());
    		outFile.createNewFile();
    		System.out.println("Output file location: " + outFile.getCanonicalPath());
    		bw = new BufferedWriter(new FileWriter(outFile));
    		StringBuffer strBuf = new StringBuffer();
    		strBuf.append("<ExecutionAt>");
    		strBuf.append(Calendar.getInstance().getTime());
    		strBuf.append("</ExecutionAt>\n");
    		strBuf.append("<ToolOrWebServiceName>");
    		strBuf.append(props.getProperty(tool_or_webservice_name));
    		strBuf.append("</ToolOrWebServiceName>\n");
    		strBuf.append("\n");
    		strBuf.append("<ResponseStatus>\n");
    		strBuf.append("\t<Code>");
    		strBuf.append(statusCode);
    		strBuf.append("</Code>\n");
    		strBuf.append("\t<Description>");
    		strBuf.append(description);
    		strBuf.append("</Description>\n");
    		strBuf.append("</ResponseStatus>");
    		bw.write(strBuf.toString());
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
