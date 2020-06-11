<?php

class Helper {
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function dateDB2View($date){
        //2013-04-05 05/04/2013

        $date = explode('-',$date);
        return $date[2].'/'.$date[1].'/'.$date[0];
    }
    
    public static function view2DateDB($date){
        //05/04/2013 - 2013-04-05
        $date = explode('/',$date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
    
    public static function string2Float($valor){
        if($valor == "") $valor=0;
        //15.000,00  15000.00
        return  str_replace(",",".",str_replace(".","", $valor));
    }
    
    public static function float2String($valor){
        //15000.00 15000,00
        $valor = str_replace(".",",",$valor);
        
        return  $valor;
    }
}
