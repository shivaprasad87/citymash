<?php
/**
 * Created by PhpStorm.
 * User: komalkumra
 * Date: 2018-12-31
 * Time: 11:16
 */

class CiphersUtil
{


    /**
     * @param $data
     * @param $key
     * @return string
     */
    public function calculateHMAC($data, $key){

        try{
            $hmac = hash_hmac('sha1', $data,$key);
            return $hmac;
        }
        catch (Exception $exception){
        throw new SignatureException("Failed to generate HMAC".$exception->getMessage());
        }

     }

}