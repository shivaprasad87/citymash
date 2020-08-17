<?php
/**
 * Created by PhpStorm.
 * User: komalkumra
 * Date: 2019-01-09
 * Time: 15:53
 */

class QDPServiceNotReachable extends Exception
{


    /**
     * QDPServiceNotReachable constructor.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}