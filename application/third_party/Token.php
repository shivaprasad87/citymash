<?php
/**
 * Created by PhpStorm.
 * User: komalkumra
 * Date: 2018-12-31
 * Time: 11:09
 */



class Token
{

    function __construct() {

    }

   private $appId;
   private $emailId;
   private $tokenId;
   private $token;
   private $expiryTime;


    /**
     * @return mixed
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param mixed $appId
     * @return Token
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailId()
    {
        return $this->emailId;
    }

    /**
     * @param mixed $emailId
     */
    public function setEmailId($emailId)
    {
        $this->emailId = $emailId;
    }

    /**
     * @return mixed
     */
    public function getTokenId()
    {
        return $this->tokenId;
    }

    /**
     * @param mixed $tokenId
     */
    public function setTokenId($tokenId)
    {
        $this->tokenId = $tokenId;
    }

    /**
     * @return mixed
     */
    public function getExpiryTime()
    {
        return $this->expiryTime;
    }

    /**
     * @param mixed $expiryTime
     */
    public function setExpiryTime($expiryTime)
    {
        $this->expiryTime = $expiryTime;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }







}