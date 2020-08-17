<?php
/**
 * Created by PhpStorm.
 * User: komalkumra
 * Date: 2019-01-08
 * Time: 16:13
 */

class IllegalStateException extends Exception
{
    /**
     * IllegalStateException constructor.
     * @param $message
     */
    public function __construct($message){
        $this->message=$message;
    }

}