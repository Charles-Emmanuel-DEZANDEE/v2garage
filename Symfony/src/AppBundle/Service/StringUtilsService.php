<?php

namespace AppBundle\Service;

class StringUtilsService
{

    public function generateToken($length){
        $result = bin2hex(openssl_random_pseudo_bytes($length));
        return $result;
    }

}