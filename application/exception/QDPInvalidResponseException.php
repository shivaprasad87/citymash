<?php
/**
 * Created by PhpStorm.
 * User: komalkumra
 * Date: 2019-01-08
 * Time: 10:17
 */

class QDPInvalidResponseException extends Exception
{
    /**
     * QDPInvalidResponseException constructor.
     * @param $message
     */
    public function __construct($message){
       $this->message=$message;
    }

}