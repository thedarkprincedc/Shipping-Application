using System;
using System.Collections.Generic;
using System.Text;
using XAVWSSample.XAVWebReference;

namespace XAVWSSample
{
    class XAVWSClient
    {
        static void Main()
        {
            try
            {
            XAVService xavSvc = new XAVService();
            XAVRequest xavRequest = new XAVRequest();
            UPSSecurity upss = new UPSSecurity();
            UPSSecurityServiceAccessToken upssSvcAccessToken = new UPSSecurityServiceAccessToken();
            upssSvcAccessToken.AccessLicenseNumber = "Your access license number";
            upss.ServiceAccessToken = upssSvcAccessToken;
            UPSSecurityUsernameToken upssUsrNameToken = new UPSSecurityUsernameToken();
            upssUsrNameToken.Username = "Your user name";
            upssUsrNameToken.Password = "Your password";
            upss.UsernameToken = upssUsrNameToken;
            xavSvc.UPSSecurityValue = upss;
            RequestType request = new RequestType();

            //Below code contains dummy data for reference. Please update as required.
            String[] requestOption = { "1" };
            request.RequestOption = requestOption;
            xavRequest.Request = request;
            AddressKeyFormatType addressKeyFormat = new AddressKeyFormatType();
            String[] addressLine = { "3930 KRISTI COURT" };
            //addressKeyFormat.ItemsElementName = new ItemsChoiceType[] { ItemsChoiceType.PoliticalDivision1,ItemsChoiceType.PoliticalDivision2,ItemsChoiceType.PostcodePrimaryLow };
            String[] addressKeyFormatItems = { "CA", "Cumming", "95827" };
            //addressKeyFormat.Items = addressKeyFormatItems;
            addressKeyFormat.AddressLine = addressLine;
            addressKeyFormat.Urbanization = "SACRAMENTO CA 95827";
            addressKeyFormat.ConsigneeName = "Some Consignee";
            addressKeyFormat.CountryCode = "US";
            xavRequest.AddressKeyFormat = addressKeyFormat;
            System.Net.ServicePointManager.CertificatePolicy = new TrustAllCertificatePolicy();
            XAVResponse xavResponse = xavSvc.ProcessXAV(xavRequest);
            Console.WriteLine("Response Status Code " + xavResponse.Response.ResponseStatus.Code);
            Console.WriteLine("Response Status Description " + xavResponse.Response.ResponseStatus.Description);
            Console.ReadLine();
            }
            catch (System.Web.Services.Protocols.SoapException ex)
            {
                Console.WriteLine("");
                Console.WriteLine("---------XAV Web Service returns error----------------");
                Console.WriteLine("---------\"Hard\" is user error \"Transient\" is system error----------------");
                Console.WriteLine("SoapException Message= " + ex.Message);
                Console.WriteLine("");
                Console.WriteLine("SoapException Category:Code:Message= " + ex.Detail.LastChild.InnerText);
                Console.WriteLine("");
                Console.WriteLine("SoapException XML String for all= " + ex.Detail.LastChild.OuterXml);
                Console.WriteLine("");
                Console.WriteLine("SoapException StackTrace= " + ex.StackTrace);
                Console.WriteLine("-------------------------");
                Console.WriteLine("");
            }
            catch (System.ServiceModel.CommunicationException ex)
            {
                Console.WriteLine("");
                Console.WriteLine("--------------------");
                Console.WriteLine("CommunicationException= " + ex.Message);
                Console.WriteLine("CommunicationException-StackTrace= " + ex.StackTrace);
                Console.WriteLine("-------------------------");
                Console.WriteLine("");

            }
            catch (Exception ex)
            {
                Console.WriteLine("");
                Console.WriteLine("-------------------------");
                Console.WriteLine(" Generaal Exception= " + ex.Message);
                Console.WriteLine(" Generaal Exception-StackTrace= " + ex.StackTrace);
                Console.WriteLine("-------------------------");

            }
            finally
            {
                Console.ReadKey();
            }
        }

    }
}
