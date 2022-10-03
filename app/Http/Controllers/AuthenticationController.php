<?php

namespace App\Http\Controllers;

use App\Models\Datalog;
use App\Models\EzypayUser;
use App\Models\JpjinfoUser;
use App\Models\Log;
use App\Models\LogXml;
use App\Models\Pengadu;
use App\Models\User;
use CodeDredd\Soap\Facades\Soap;
use DOMDocument;
use Illuminate\Http\Request;
use stdClass;
use App\Http\Controllers\SecCheckController;

class AuthenticationController extends Controller
{

    public function semakIdAwam(Request $request)
    {
        $nokp = $request->nokp;
        // $function = "semakIdAwam";
        // $service = "Semak ID Awam";
        // $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        // $ref = $_SERVER['HTTP_REFERER'];
        // $agent = $_SERVER['HTTP_USER_AGENT'];
        // $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        // LogController::insertLog($ip, $service, $function, '', $agent, $host_name);

        $jenis = 1;
        $jenis_data = "JSON";
        $soapUrl = "https://mobile.jpj.gov.my/jpj-revamp-svc-pvr-ws/idm_public_mobile_registration";
        $data = json_encode($request->all());
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $data);

        $soapUrl ="https://mobile.jpj.gov.my/jpj-revamp-svc-pvr-ws/idm_public_mobile_registration";
            $soapUser = "username";  //  username
            $soapPassword = "password"; // password
            $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:idm="http://www.example.org/idm_public_mobile_registration/">
            <soapenv:Header/>
            <soapenv:Body>
               <idm:findPublicUser>
                  <!--Optional:-->
                  <userId>'.$nokp.'</userId>
               </idm:findPublicUser>
            </soapenv:Body>
         </soapenv:Envelope>';

            $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://www.example.org/idm_public_mobile_registration/",
                        "Content-length: ".strlen($xml_post_string),
                        ); //SOAPAction: your op URL

            $url = $soapUrl;
            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch);
            // dd($response);
            curl_close($ch);

            $doc1 = new DOMDocument();
            $doc1->loadXML($response);
            
            $response_status = $doc1->getElementsByTagName('respSta')->item(0)->nodeValue;
            $response_msg = $doc1->getElementsByTagName('respMsg')->item(0)->nodeValue;
            // dd($userID);

            if ($response_status == 00) {
                $userID = $doc1->getElementsByTagName('userID')->item(0)->nodeValue;
                $userName = $doc1->getElementsByTagName('userName')->item(0)->nodeValue;
                $userEmail = $doc1->getElementsByTagName('userEmail')->item(0)->nodeValue;
                $userPhone = $doc1->getElementsByTagName('userPhone')->item(0)->nodeValue;
                $userCat = $doc1->getElementsByTagName('userCat')->item(0)->nodeValue;
            }

        $obj = new stdclass;
        $obj->status = $response_status;
        $obj->message = $response_msg;
        if ($response_status == 00) {
            $obj->nokp = $userID;
            $obj->name = $userName;
            $obj->email = $userEmail;
            $obj->phone = $userPhone;
            $obj->category = $userCat;
        }

        $jenis = 3;
        $jenis_data = "XML";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $raw);

        $jenis = 4;
        $jenis_data = "JSON";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, json_encode($response));

        return response()->json($obj);
    }

    public function registerIdAwam(Request $req)
    {
        
        // $function = "checkUser";
        // $service = "Pendaftaran ID Awam Standard";
        // $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        // // $ref = $_SERVER['HTTP_REFERER'];
        // $agent = $_SERVER['HTTP_USER_AGENT'];
        // $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        // LogController::insertLog($ip, $service, $function, '', $agent, $host_name);

        $nokp = $req->nokp;
        $kategori = $req->kategori;
        $nama = $req->nama;
        $emel = $req->emel;
        $telefon = $req->telefon;
        $postdata = $req->all();

        // $jenis = 1;
        // $jenis_data = "JSON";
        // $soapUrl = "http://10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_mobile_registration";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, json_encode($postdata));

        // $raw = Soap::baseWsdl('http://10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_mobile_registration/IdmPublicMobileRegistration.wsdl')
        //     ->call('createPublicUser', [
        //         'userId' => $nokp,
        //         'userName' => $nama,
        //         'userEmail' => $emel,
        //         'userPhone' => $telefon,
        //     ]);
        // $response = json_decode($raw->body())->out;

        $soapUrl ="http:///10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_mobile_registration";
            $soapUser = "username";  //  username
            $soapPassword = "password"; // password
            $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:idm="http://www.example.org/idm_public_mobile_registration/">
            <soapenv:Header/>
            <soapenv:Body>
               <idm:createPublicUser>
                    <!--Optional:-->
                    <reqProfileData>
                    <userID>'.$nokp.'</userID>
                    <userName>'.$nama.'</userName>
                    <userEmail>'.$emel.'</userEmail>
                    <userPhone>'.$telefon.'</userPhone>
                    </reqProfileData>
               </idm:createPublicUser>
            </soapenv:Body>
         </soapenv:Envelope>';

            $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://www.example.org/idm_public_mobile_registration/",
                        "Content-length: ".strlen($xml_post_string),
                        ); //SOAPAction: your op URL

            $url = $soapUrl;

            // $jenis = 2;
            // $jenis_data = "XML";
            // $this->appsModel->dataLog($jenis,$jenis_data,$ip,$service,$function,$xml_post_string);

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch);
            curl_close($ch);

            // dd($response);

            $doc1 = new DOMDocument();
            $doc1->loadXML($response);
            $response_status = $doc1->getElementsByTagName('respSta')->item(0)->nodeValue;
            $response_msg = $doc1->getElementsByTagName('respMsg')->item(0)->nodeValue;
            
            $obj = new stdClass;
            if($response_status == "00"){
                $tempPwd = $doc1->getElementsByTagName('userTempPwd')->item(0)->nodeValue;
                $userid = $doc1->getElementsByTagName('userID')->item(0)->nodeValue;
                $obj->userId = $userid;
                $obj->tempPwd = $tempPwd;
            }
            
            $obj->status = $response_status;
            $obj->msg = $response_msg;

        // $jenis = 3;
        // $jenis_data = "XML";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $raw);

        // $jenis = 4;
        // $jenis_data = "JSON";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, json_encode($response));

        return response()->json($obj);
    }

    public function firstTimeLogin(Request $request)
    {
        $username = SecCheckController::strip_alphanum($request->nokp);
        $password = $request->password;
        $username = trim($username);
        $password = trim($password);

        // echo $username . " " . $password;
        try {
            $soapUrl = "http://10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_login_authentication"; // asmx URL of WSDL
            $soapUser = "username";  //  username
            $soapPassword = "password"; // password

            $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:idm="http://www.example.org/idm_public_login_authentication/">
            <soapenv:Header/>
            <soapenv:Body>
               <idm:authenticateUserLogin>
                  <!--Optional:-->
                  <reqLoginInput>
                     <userLoginId>'.$username.'</userLoginId>
                     <userLoginPassword>'.$password.'</userLoginPassword>
                  </reqLoginInput>
               </idm:authenticateUserLogin>
            </soapenv:Body>
         </soapenv:Envelope>';

            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: http://www.gov.jpj.org/idm_public_login_authentication/",
                "Content-length: " . strlen($xml_post_string),
            ); //SOAPAction: your op URL

            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch);
            curl_close($ch);
            // dd($response);

            $doc = new DOMDocument();
            $doc->loadXML($response);
            $i = 0;
            // $bil = $doc->getElementsByTagName('loginUserDetResp')->length;

            $obj = new stdClass;
            $obj->statusCode = $doc->getElementsByTagName('resultCode')->item(0)->nodeValue;
            $obj->statusMsg = $doc->getElementsByTagName('resultMessage')->item(0)->nodeValue;
            // dd($obj);
            // $statusCode = 0;
            if ($obj->statusCode == 00) {
                $obj->idmpuUsrId = $doc->getElementsByTagName('idmpuUsrId')->item(0)->nodeValue;
                $obj->idmpuPassword = $doc->getElementsByTagName('idmpuPassword')->item(0)->nodeValue;
                $obj->idmpuManutiae = $doc->getElementsByTagName('idmpuManutiae')->item(0)->nodeValue;
                $obj->idmpuStatus = $doc->getElementsByTagName('idmpuStatus')->item(0)->nodeValue;
                $obj->idmpuAclId = $doc->getElementsByTagName('idmpuAclId')->item(0)->nodeValue;
                $obj->idmpuUserEmail = $doc->getElementsByTagName('idmpuUserEmail')->item(0)->nodeValue;
                $obj->idmpuUserName = $doc->getElementsByTagName('idmpuUserName')->item(0)->nodeValue;
                $obj->idmpuUserGrpId = $doc->getElementsByTagName('idmpuUserGrpId')->item(0)->nodeValue;
                $obj->idmpuRefNo = $doc->getElementsByTagName('idmpuRefNo')->item(0)->nodeValue;
                $obj->idmpuTransCode = $doc->getElementsByTagName('idmpuTransCode')->item(0)->nodeValue;
                $obj->idmpuPwdCount = $doc->getElementsByTagName('idmpuPwdCount')->item(0)->nodeValue;
                $obj->idmpuPrevPassword = $doc->getElementsByTagName('idmpuPrevPassword')->item(0)->nodeValue;
                $obj->idmpuPwdActiveDate = $doc->getElementsByTagName('idmpuPwdActiveDate')->item(0)->nodeValue;
                $obj->idmpuPrvAcl = $doc->getElementsByTagName('idmpuPrvAcl')->item(0)->nodeValue;
                $obj->idmpuPrvStatus = $doc->getElementsByTagName('idmpuPrvStatus')->item(0)->nodeValue;
                $obj->idmpuLastLoginTime = $doc->getElementsByTagName('idmpuLastLoginTime')->item(0)->nodeValue;
                $obj->idmpuSQ1Id = $doc->getElementsByTagName('idmpuSQ1Id')->item(0)->nodeValue;
                $obj->idmpuSQ1Ans = $doc->getElementsByTagName('idmpuSQ1Ans')->item(0)->nodeValue;
                $obj->idmpuSQ2Id = $doc->getElementsByTagName('idmpuSQ2Id')->item(0)->nodeValue;
                $obj->idmpuSQ2Ans = $doc->getElementsByTagName('idmpuSQ2Ans')->item(0)->nodeValue;
                $obj->idmpuSQ3Id = $doc->getElementsByTagName('idmpuSQ3Id')->item(0)->nodeValue;
                $obj->idmpuSQ3Ans = $doc->getElementsByTagName('idmpuSQ3Ans')->item(0)->nodeValue;
                $obj->idmpuSQPriority = $doc->getElementsByTagName('idmpuSQPriority')->item(0)->nodeValue;
                $obj->idmpuIndividualInd = $doc->getElementsByTagName('idmpuIndividualInd')->item(0)->nodeValue;
                $obj->idmpuCompRepInd = $doc->getElementsByTagName('idmpuCompRepInd')->item(0)->nodeValue;
                $obj->idmpuCompAppInd = $doc->getElementsByTagName('idmpuCompAppInd')->item(0)->nodeValue;
                $obj->idmpuStaffInd = $doc->getElementsByTagName('idmpuStaffInd')->item(0)->nodeValue;

            } 
        } catch (\Throwable $th) {
            $this->index();
        }

        return response()->json($obj);
    }

    public function soalanKeselamatan(Request $req)
    {
        // $function = "changePasswordIdAwam";
        // $service = "Tukar Kata laluan ID Awam Standard";
        // $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP'] ?: $_SERVER['REMOTE_ADDR'];
        // $ref = $_SERVER['HTTP_REFERER'];
        // $agent = $_SERVER['HTTP_USER_AGENT'];
        // $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        $postdata = $req->all();
        $nokp = $req->nokp;
        $soalan1 = $req->soalan1;
        $jawapan1 = $req->jawapan1;
        $soalan2 = $req->soalan2;
        $jawapan2 = $req->jawapan2;
        $soalan3 = $req->soalan3;
        $jawapan3 = $req->jawapan3;

        // LogController::insertLog($ip, $service, $function, $ref, $agent, $host_name);

        // $jenis = 1;
        // $jenis_data = "JSON";
        // $soapUrl = "http://10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_security_question";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $postdata);

        // $raw = Soap::baseWsdl('http://10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_security_question/IdmPublicSecurityQuestion.wsdl')
        //     ->call('UpdateSecurityQuestion', [
        //         'userId' => $nokp,
        //         'SQ1ID' => $soalan1,
        //         'SQ2ID' => $soalan2,
        //         'SQ3ID' => $soalan3,
        //         'SQ1Ans' => $jawapan1,
        //         'SQ2Ans' => $jawapan2,
        //         'SQ3Ans' => $jawapan3,
        //     ]);
        // $response = json_decode($raw->body())->loginUserDetResp;

        // $jenis = 2;
        // $jenis_data = "XML";
        // $xml_post_string = '<?xml version="1.0" encoding="utf-8"
        //                             <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:idm="http://www.example.org/idm_public_security_question/">
        //                             <soapenv:Header/>
        //                             <soapenv:Body>
        //                             <idm:UpdateSecurityQuestion>
        //                                 <reqSecQuestData>
        //                                     <userId>' . $nokp . '</userId>
        //                                     <SQ1ID>' . $soalan1 . '</SQ1ID>
        //                                     <SQ2ID>' . $soalan2 . '</SQ2ID>
        //                                     <SQ3ID>' . $soalan3 . '</SQ3ID>
        //                                     <SQ1Ans>' . $jawapan1 . '</SQ1Ans>
        //                                     <SQ2Ans>' . $jawapan2 . '</SQ2Ans>
        //                                     <SQ3Ans>' . $jawapan3 . '</SQ3Ans>
        //                                 </reqSecQuestData>
        //                             </idm:UpdateSecurityQuestion>
        //                             </soapenv:Body>
        //                         </soapenv:Envelope>';
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $xml_post_string);


        // $jenis = 3;
        // $jenis_data = "XML";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $response);

        $soapUrl ="http://10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_security_question";
            $soapUser = "username";  //  username
            $soapPassword = "password"; // password
            $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:idm="http://www.example.org/idm_public_security_question/">
                                    <soapenv:Header/>
                                    <soapenv:Body>
                                    <idm:UpdateSecurityQuestion>
                                        <reqSecQuestData>
                                            <userId>'.$nokp.'</userId>
                                            <SQ1ID>'.$soalan1.'</SQ1ID>
                                            <SQ2ID>'.$soalan2.'</SQ2ID>
                                            <SQ3ID>'.$soalan3.'</SQ3ID>
                                            <SQ1Ans>'.$jawapan1.'</SQ1Ans>
                                            <SQ2Ans>'.$jawapan2.'</SQ2Ans>
                                            <SQ3Ans>'.$jawapan3.'</SQ3Ans>
                                        </reqSecQuestData>
                                    </idm:UpdateSecurityQuestion>
                                    </soapenv:Body>
                                </soapenv:Envelope>';

            $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: http://www.jpj.gov.my/idm_public_security_question/",
                        "Content-length: ".strlen($xml_post_string),
                        ); //SOAPAction: your op URL

            $url = $soapUrl;

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch);
            curl_close($ch);

        $doc1 = new DOMDocument();
        $doc1->loadXML($response);
        $response_status = $doc1->getElementsByTagName('respSta')->item(0)->nodeValue;
        $response_msg = $doc1->getElementsByTagName('respMsg')->item(0)->nodeValue;

        $obj = new stdClass;
        $obj->status = $response_status;
        $obj->msg = $response_msg;
        // $jenis = 4;
        // $jenis_data = "JSON";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, json_encode($obj));

        return response()->json($obj);
    }

    public function changePasswordIdAwam(Request $req)
    {
        // $function = "changePasswordIdAwam";
        // $service = "Tukar Kata laluan ID Awam Standard";
        // $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        // // $ref = $_SERVER['HTTP_REFERER'];
        // $agent = $_SERVER['HTTP_USER_AGENT'];
        // $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        $postdata = $req->all();
        $nokp = $req->nokp;
        $katalaluan = $req->katalaluan_baru;

        // LogController::insertLog($ip, $service, $function, '', $agent, $host_name);

        // $jenis = 1;
        // $jenis_data = "JSON";
        $soapUrl = "http://10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_mobile_reset_pwd";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $postdata);

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:idm="http://www.example.org/idm_public_mobile_reset_pwd/">
                                   <soapenv:Header/>
                                   <soapenv:Body>
                                      <idm:UpdatePublicUserPwd>
                                         <header>
                                            <module>VEL</module>
                                            <channel>01</channel>
                                            <agency/>
                                            <branch>0110011</branch>
                                            <pcid>E71o3UZovGh00L31GfrLUb+bnLIMC3jM3F4VhVnSC5Ay2mnh7hPRcEB+pOTxK29w</pcid>
                                            <userId>11111</userId>
                                            <transCode>IDM001</transCode>
                                            <currDate>20220623</currDate>
                                            <currTime>093904</currTime>
                                            <deviceId>D01</deviceId>
                                        </header>
                                     <reqProfileData>
                                        <userId>' . $nokp . '</userId>
                                        <userPassword>' . $katalaluan . '</userPassword>
                                     </reqProfileData>
                                  </idm:UpdatePublicUserPwd>
                               </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.jpj.gov.my/idm_public_mobile_reset_pwd/",
            "Content-length: " . strlen($xml_post_string),
        ); //SOAPAction: your op URL

        $url = $soapUrl;

        // $jenis = 2;
        // $jenis_data = "XML";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $xml_post_string);

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);

        // $jenis = 3;
        // $jenis_data = "XML";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $response);

        $doc1 = new DOMDocument();
        $doc1->loadXML($response);
        $response_status = $doc1->getElementsByTagName('respSta')->item(0)->nodeValue;
        $response_msg = $doc1->getElementsByTagName('respMsg')->item(0)->nodeValue;

        $obj = new stdClass;
        $obj->status = $response_status;
        $obj->msg = $response_msg;
        // $jenis = 4;
        // $jenis_data = "JSON";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, json_encode($obj));

        return response()->json($obj);
    }

    public function resetPasswordIdAwam(Request $req)
    {
        // $function = "changePasswordIdAwam";
        // $service = "Tukar Kata laluan ID Awam Standard";
        // $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP'] ?: $_SERVER['REMOTE_ADDR'];
        // $ref = $_SERVER['HTTP_REFERER'];
        // $agent = $_SERVER['HTTP_USER_AGENT'];
        // $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        $postdata = $req->all();
        $nokp = $req->nokp;
        $emel = $req->emel;
        $katalaluan = $req->katalaluan;

        // LogController::insertLog($ip, $service, $function, $ref, $agent, $host_name);

        // $jenis = 1;
        // $jenis_data = "JSON";
        $soapUrl = "http://10.180.5.38:9081/jpj-revamp-svc-pvr-ws/idm_public_mobile_reset_pwd";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $postdata);

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:idm="http://www.example.org/idm_public_mobile_reset_pwd/">
        <soapenv:Header/>
        <soapenv:Body>
           <idm:InquiryPublicUser>
              <!--Optional:-->
              <reqDataInfo>
              <userId>' . $nokp . '</userId>
              <userEmail>' . $emel . '</userEmail>
                 <locale>EN</locale>
              </reqDataInfo>
           </idm:InquiryPublicUser>
        </soapenv:Body>
     </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.jpj.gov.my/idm_public_mobile_reset_pwd/",
            "Content-length: " . strlen($xml_post_string),
        ); //SOAPAction: your op URL

        $url = $soapUrl;

        // $jenis = 2;
        // $jenis_data = "XML";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $xml_post_string);

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);

        // $jenis = 3;
        // $jenis_data = "XML";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, $response);

        $doc1 = new DOMDocument();
        $doc1->loadXML($response);
        $response_status = $doc1->getElementsByTagName('respSta')->item(0)->nodeValue;
        $response_msg = $doc1->getElementsByTagName('respMsg')->item(0)->nodeValue;
        $obj = new stdClass;
        if ($response_status == "00") {
            $nama = $doc1->getElementsByTagName('userName')->item(0)->nodeValue;
            $kategori = $doc1->getElementsByTagName('userCat')->item(0)->nodeValue;
            $tac = $doc1->getElementsByTagName('userTAC')->item(0)->nodeValue;

            $obj->nama = $nama;
            $obj->kategori = $kategori;
            $obj->tac = $tac;
        }

        $obj->status = $response_status;
        $obj->msg = $response_msg;
        // $jenis = 4;
        // $jenis_data = "JSON";
        // LogController::insertDatalog($jenis, $jenis_data, $ip, 'services', $function, $soapUrl, json_encode($obj));

        return response()->json($obj);
    }


    // ---------------- try cara lain ------------------ //

    public function semakId()
    {
        $response = Soap::baseWsdl('http://192.168.0.149:9081/jpj-revamp-svc-pvr-ws/idm_public_mobile_registration/IdmPublicMobileRegistration.wsdl')
            ->call('findPublicUser', [
                'userId' => '940128145306',
            ]);
        dd($response->body());
        $response = json_decode($response->body())->out;
        return response()->json($response);
    }

    public function regId()
    {
        $response = Soap::baseWsdl('http://192.168.0.149:9081/jpj-revamp-svc-pvr-ws/idm_public_mobile_registration/IdmPublicMobileRegistration.wsdl')
            ->call('createPublicUser', [
                'userId' => '940128145306',
                'userName' => 'Najhan Najib',
                'userEmail' => 'najhan.mnajib@gmail.com',
                'userPhone' => '0122263479',
            ]);
        $response = json_decode($response->body())->out;
        return response()->json_encode($response);
    }

    public function firsttime(Request $request)
    {
        $username = $request->username;
        $password = $request->katalaluan;
        $onesignal_id = $request->playerid;
        $uuid = $request->uuid;

        $sourcexml = "login from apps";
        $logxml = new LogXml();
        $logxml->sourcexml = json_encode($request->all());
        $logxml->xml = $sourcexml;
        $logxml->save();

        if ($username == "999999999999") {
            $userObj = new stdClass;
            $userObj->nama = "Abdul Wahub";
            $userObj->emel = "abd.wahub@gmail.com";
            $userObj->nokp = "999999999999";
            $userObj->status = 0;
            $userObj->message = "Success";

            $emel = $userObj->emel;
            $nama = $userObj->nama;
            $nokp = $userObj->nokp;

            $token = $emel . $nama . $nokp . date("YmdHis");
            $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
            $token = crypt($token, '$2y$12$' . $salt);

            $userObj->token = $token;
            $data = Pengadu::where('nokp', $nokp)->first();
            if (count($data) == 0) {
                $pengadu = new Pengadu();
                $pengadu->username = $nokp;
                $pengadu->nama = $nama;
                $pengadu->nokp = $nokp;
                $pengadu->emel = $emel;
                $pengadu->token = $token;
                $pengadu->password = $password;
                $pengadu->save();
            }
            // $this->aduanModel->loglogin($username, $status_login);
            // $sql = "insert into log_login (user_id,response) values ('$username','$status_login')";

            return json_encode($userObj);
        } else {
            $raw = Soap::baseWsdl('https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/public_SSO_Login/PublicSSOLogin.wsdl')
                ->call('public_SSO_Login', [
                    'userName' => $username,
                    'password' => $password,
                    'sessionId' => '',
                    'publikType' => 'Citizen',
                    'publikLocale' => 'en',
                    'ssoFlag' => 'sso',
                ]);
            $response = json_decode($raw->body())->loginUserDetResp;

            $sourcexml = "Login mysikap response";
            $logxml->sourcexml = $raw;
            $logxml->xml = $sourcexml;
            $logxml->save();

            $doc = new DOMDocument();
            $doc->loadXML($response);
            $nama = $response->idmpuUserName;
            $emel = $response->idmpuUserEmail;
            $nokp = $response->idmpuUsrId;
            $sec = $response->idmpuSQ2Ans;
            $password = $response->idmpuPassword;
            $status_login = $response->statusCode;

            if ($status_login == 0) {
                $userObj = new stdClass;
                $userObj->nama = trim($nama);
                $userObj->emel = trim($emel);
                $userObj->nokp = trim($nokp);
                $userObj->status = 0;
                $userObj->message = "Success";

                $token = $emel . $nama . $nokp . date("YmdHis");
                $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
                $token = crypt($token, '$2y$12$' . $salt);

                $userObj->token = $token;
                // $this->loginModel->insertUserPublic($nama,$emel,$nokp,$token,$passwd);
                $data = Pengadu::where('nokp', $nokp)->first();
                $data2 = EzypayUser::where('nokp', $nokp)->first();
                $data3 = JpjinfoUser::where('nokp', $nokp)->first();

                if (count($data) == 0) {
                    $pengadu = new Pengadu();
                    $pengadu->username = $nokp;
                    $pengadu->nama = $nama;
                    $pengadu->nokp = $nokp;
                    $pengadu->emel = $emel;
                    $pengadu->token = $token;
                    $pengadu->password = $password;
                    $pengadu->save();
                }

                if (count($data2) == 0) {
                    $pengadu = new EzypayUser();
                    $pengadu->username = $nokp;
                    $pengadu->nama = $nama;
                    $pengadu->nokp = $nokp;
                    $pengadu->emel = $emel;
                    $pengadu->token = $token;
                    $pengadu->password = $password;
                    $pengadu->save();
                }

                if (count($data3) == 0) {
                    $pengadu = new JpjinfoUser();
                    $pengadu->username = $nokp;
                    $pengadu->nama = $nama;
                    $pengadu->nokp = $nokp;
                    $pengadu->emel = $emel;
                    $pengadu->token = $token;
                    $pengadu->password = $password;
                    $pengadu->onesignal_id = $onesignal_id;
                    $pengadu->uuid = $uuid;
                    $pengadu->save();
                } else {
                    $pengadu = JpjinfoUser::where('nokp', $nokp)->first();
                    $pengadu->username = $nokp;
                    $pengadu->nama = $nama;
                    $pengadu->nokp = $nokp;
                    $pengadu->emel = $emel;
                    $pengadu->token = $token;
                    $pengadu->password = $password;
                    $pengadu->onesignal_id = $onesignal_id;
                    $pengadu->uuid = $uuid;
                    $pengadu->save();
                }

                // $this->aduanModel->loglogin($username, $status_login);

                // exec("wget http://egate.jpj.gov.my/jpjinfo-api/apps/".$nokp."/1");
                $tarikh = date("Y-m-d");
                $token = sha1($tarikh . $nokp . $tarikh);
                $url1 = "http://egate.jpj.gov.my/jpjinfo-api/apps/getUserInfo2/" . $nokp . "/1/" . $token;
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url1);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $server_output = curl_exec($ch);
                curl_close($ch);
                // echo $server_output;

                return response()->json($userObj);
            } else {
                $userObj = new stdClass;
                $userObj->status = $status_login;
                $userObj->message = "Login Fail";
                $userObj->status_login = $status_login;
                // $this->aduanModel->loglogin($username, $status_login);
                return response()->json($userObj);
            }
        }
    }
}
