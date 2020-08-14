<?php
/**
 * Created by PhpStorm.
 * User: komalkumra
 * Date: 2018-12-31
 * Time: 11:41
 */


include (APPPATH."/third_party/Token.php");
foreach (glob("Utils/*.php") as $filename)
{
    include $filename;
}

foreach (glob("exception/*.php") as $filename)
{

    include $filename;
}

class  QDPServiceImpl
{

    private static $host = null ;

    private static $appId = null ;

    private static $secretKey = null ;

    private static $email = null ;

    private static $storedToken= null;

/**
     * @param array $properties
     */
    public static function init(array $properties){
      self::$host=$properties["host"];
      self::$appId=$properties["appId"];
      self::$secretKey=$properties["secretKey"];
      self::$email=$properties["email"];

      }

    /**
     * @throws IllegalStateException thrown if any property is null
     */

    public static function getInstance(){

        if(empty(self::$host)){
            throw new IllegalStateException("Please initialize the host before use, call init()");
        }

        if (empty(self::$appId)){
            throw new IllegalStateException("Please initialize the appId before use, call init()");
        }

        if (empty(self::$secretKey)){
            throw new IllegalStateException("Please initialize the secretKey before use, call init()");
        }

        if (empty(self::$email)){
            throw new IllegalStateException("Please initialize the email before use, call init()");
        }

    }

    /**
     * @return Token
     * @throws QDPInvalidResponseException Invalid response from Token API
     * @throws QDPServiceNotReachable
     * @throws TokenException Invalid email , appId, Signature. Signature is null.
     */


    public function getToken():Token
    {
        $data = self::$email.self::$appId.date("Y-m-d") ;
        $cipherUtils=new CiphersUtil();
        $signature = $cipherUtils->calculateHMAC($data,self::$secretKey);
        if(null == $signature){
            throw new TokenException("Invalid email , appId, Signature. Signature is null.");

        }else{
            $resultToken = new Token();
            $resultToken = self::generateToken($signature);
            if (null != $resultToken->getToken() && 0 != $resultToken->getTokenId()){
                $resultToken->setEmailId(self::$email);
                $resultToken->setAppId(self::$appId);
                self::$storedToken = $resultToken;
                return $resultToken;
            }
            else{
                throw new QDPInvalidResponseException("Invalid response from Token API");
            }

        }


    }

    /**
     * @return array
     * @throws QDPInvalidResponseException Token is null . Invalid email or appId or secretKey
     * @throws QDPServiceNotReachable
     * @throws TokenException
     */

    public function getAuthHeaders():array {
        //Removing warning from func_get_arg(arg_num)
        error_reporting(E_ERROR | E_PARSE);
        $token = func_get_arg(0);
        if(null == $token){
            $token = self::getToken();
        }
            $data = $token->getAppId() . $token->getEmailId() . date("Y-m-d");
            $cipherUtils = new CiphersUtil();
            $headers_array = array(Constant::$X_QUIKR_APP_ID => $token->getAppId(),
                Constant::$X_QUIKR_TOKEN_ID => $token->getTokenId(),
                Constant::$X_QUIKR_SIGNATURE_V2 => $cipherUtils->calculateHMAC($data, $token->getToken()));
        return $headers_array;
    }

    /**
     * @param $signature
     * @return Token
     * @throws QDPInvalidResponseException Invalid Response from QDP Token API
     * @throws QDPServiceNotReachable
     * @throws TokenException Invalid email , appId, Signature. Signature is null
     */

    private static function generateToken($signature){
        $responseToken = new Token();
        if (null == $signature){
            throw new TokenException("Invalid email , appId, Signature. Signature is null.");
        }
        if(null != $signature){
            //API URL
            $url=self::$host."/".Constant::$AUTH_URL;
            //create new curl resource
            $ch = curl_init($url);
            $data= array(
                'appId'=> self::$appId,
                'signature' => $signature
            );
            $body = json_encode($data);
            //attach encoded JSON string to POST field
            curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
            //set the content type to application/json
            curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:'.Constant::$CONTENT_TYPE));
            //return response instead of outputting
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //execute post request
            $response = curl_exec($ch);
            //Fetch http code
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(curl_errno($ch)){
                throw new QDPServiceNotReachable(curl_error($ch));
            }
           if (null == $response){
                throw new QDPInvalidResponseException("Invalid Response from QDP Token API");
            }
            elseif (200 == $httpcode){
                $obj = json_decode($response);
                if($obj->{'error'} == "true"){
                    throw new TokenException("Invalid emailId or appId or secretKey");
                }
                if (null != $obj){
                    $responseToken->setToken($obj->{'token'});
                    $responseToken->setTokenId($obj->{'tokenId'});
                    $milliseconds = round(microtime(true) * 1000/1000);
                    $responseToken->setExpiryTime($milliseconds+$obj->{'expiresAfter'});
                    return $responseToken;
                }
                else{
                    throw new QDPInvalidResponseException("Invalid response from QDP Token API");
                }
            }
            elseif (400 == $httpcode){
                throw new QDPInvalidResponseException("Error 400 Http Bad Request");
            }
            elseif (401 == $httpcode ){
                throw new QDPInvalidResponseException("Error 401 Http Unauthorised Access");
            }
            elseif (404 == $httpcode){
                throw new QDPInvalidResponseException("Error 404 Http Not Found");
            }
            curl_close($ch);
        }
        return $responseToken;

    }


}
