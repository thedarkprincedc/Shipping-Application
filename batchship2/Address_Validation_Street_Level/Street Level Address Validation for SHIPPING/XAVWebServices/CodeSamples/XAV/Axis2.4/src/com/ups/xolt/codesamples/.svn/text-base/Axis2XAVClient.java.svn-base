/* 
 ** 
 ** Filename: Axis2XAVClient.java 
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
import java.util.Properties;

import com.ups.www.wsdl.xoltws.xav.v1_0.XAVErrorMessage;
import com.ups.www.wsdl.xoltws.xav.v1_0.XAVServiceStub;

public class Axis2XAVClient {
	
	private static String url;
	private static String accesskey;
	private static String username;
	private static String password;
	private static String out_file_location = "out_file_location";
	private static String tool_or_webservice_name = "tool_or_webservice_name";
	static Properties props = null;
	static{
        try{
        	props = new Properties();
        	props.load(new FileInputStream("./build.properties"));
 	  		url = props.getProperty("url");
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
			XAVServiceStub xavServiceStub = new XAVServiceStub(url);
			XAVServiceStub.XAVRequest xavRequest = new XAVServiceStub.XAVRequest();			
			XAVServiceStub.RequestType request = new XAVServiceStub.RequestType();
			String[] requestOption = { "1" };
			request.setRequestOption(requestOption);
			xavRequest.setRequest(request);
			XAVServiceStub.AddressKeyFormatType addressKeyFormat = new XAVServiceStub.AddressKeyFormatType();
			String addressLines[] = {"3930 KRISTI COURT"};
			addressKeyFormat.setAddressLine(addressLines);
			addressKeyFormat.setCountryCode("US");
			xavRequest.setAddressKeyFormat(addressKeyFormat); 
			
			/** ************UPSSE***************************/
			XAVServiceStub.UPSSecurity upss = new XAVServiceStub.UPSSecurity();
			
			XAVServiceStub.UsernameToken_type0 upsUsrToken = new XAVServiceStub.UsernameToken_type0();
			upsUsrToken.setPassword(password);
			
			upsUsrToken.setUsername(username);
			XAVServiceStub.ServiceAccessToken_type0 token = new XAVServiceStub.ServiceAccessToken_type0();
			token.setAccessLicenseNumber(accesskey);
			upss.setUsernameToken(upsUsrToken);
			upss.setServiceAccessToken(token);
			/** ************UPSSE******************************/
			XAVServiceStub.XAVResponse xavResponse = xavServiceStub.ProcessXAV(xavRequest, upss);
			statusCode = xavResponse.getResponse().getResponseStatus().getCode();
			description = xavResponse.getResponse().getResponseStatus().getDescription();
			updateResultsToFile(statusCode, description);
			
			System.out.println("Transaction Status: "
					+ xavResponse.getResponse().getResponseStatus()
							.getDescription());
			System.out.println("Response"+xavServiceStub.ProcessXAV(xavRequest, upss));
		} catch (Exception e) {
			if(e instanceof XAVErrorMessage){
				XAVErrorMessage errors = (XAVErrorMessage)e;
				statusCode = errors.getMessage();
				description = errors.getLocalizedMessage();
				updateResultsToFile(statusCode, description);
			}else{
				description=e.getMessage();
				statusCode=e.toString();
				updateResultsToFile(statusCode, description);
			}
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
