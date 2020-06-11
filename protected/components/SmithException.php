<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SmithException extends Exception
{
    const USER_NOT_FOUND    = 1400;
    const FORMAT_EXCELL     = 1401;
    
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

//    public function customFunction() {
//        echo "Uma função específica desse tipo de exceção\n";
//    }
}