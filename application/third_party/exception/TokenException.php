<?php
/**
 * Created by PhpStorm.
 * User: komalkumra
 * Date: 2019-01-02
 * Time: 18:30
 */

class TokenException extends Exception
{
    /**
     * TokenException constructor.
     * @param $message
     */
  public function __construct($message){
      $this->message=$message;

  }

}